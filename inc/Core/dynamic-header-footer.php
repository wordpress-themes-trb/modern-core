
<?php
/**
 * Modern Core - Dynamic Header/Footer System
 * Swaps template parts for local headers/footers on a per-page basis.
 */


if (!defined('ABSPATH')) exit;

class MC_Dynamic_Header_Footer {
    
    public function __construct() {
        add_filter('render_block_core/template-part', [$this, 'swap_template_part'], 10, 3);
        add_action('rest_api_init', [$this, 'register_rest_routes']);
        add_action('add_meta_boxes', [$this, 'add_page_header_footer_meta']);
        add_action('save_post', [$this, 'save_page_header_footer_meta']);
        add_action('enqueue_block_editor_assets', [$this, 'enqueue_sidebar_plugin']);
    }

    
    /**
     * Swap template part at render time based on page context
     */
    public function swap_template_part($block_content, $parsed_block, $wp_block) {
        $slug = $parsed_block['attrs']['slug'] ?? '';
        
        // Only intercept header and footer placeholders
        if (!in_array($slug, ['header', 'footer'])) {
            return $block_content;
        }

        $post_id = get_the_ID();
        if (!$post_id) {
            return $block_content;
        }

        // Check if this page has a local override
        $meta_key = $slug === 'header' ? '_mc_local_header' : '_mc_local_footer';
        $local_data = get_post_meta($post_id, $meta_key, true);

        if (empty($local_data)) {
            return $block_content; // Use global template part
        }

        // Render the local header/footer block
        $local_block = $this->build_local_block($local_data, $slug);
        
        if ($local_block) {
            return do_blocks($local_block);
        }

        return $block_content;
    }

    /**
     * Build the local block markup from saved data
     */
    private function build_local_block($data, $type) {
        if (empty($data['blocks'])) {
            return false;
        }

        $block_name = $type === 'header' ? 'modern-core/advanced-header' : 'modern-core/advanced-footer';
        
        $attrs = [
            'isSticky' => $data['settings']['isSticky'] ?? true,
            'transparentOnLoad' => $data['settings']['transparentOnLoad'] ?? false,
            'autoHideOnScroll' => $data['settings']['autoHideOnScroll'] ?? false,
            'preset' => $data['preset'] ?? 'custom',
            'isLocal' => true,
        ];

        $attrs_json = wp_json_encode($attrs);
        $blocks = $data['blocks'];

        return '<!-- wp:' . $block_name . ' ' . $attrs_json . ' -->' . $blocks . '<!-- /wp:' . $block_name . ' -->';
    }

    /**
     * Register REST API routes for preset management
     */
    public function register_rest_routes() {
        register_rest_route('modern-core/v1', '/header-presets', [
            [
                'methods' => 'GET',
                'callback' => [$this, 'get_header_presets'],
                'permission_callback' => [$this, 'check_permissions'],
            ],
            [
                'methods' => 'POST',
                'callback' => [$this, 'save_header_preset'],
                'permission_callback' => [$this, 'check_permissions'],
            ],
        ]);

        register_rest_route('modern-core/v1', '/page-header', [
            [
                'methods' => 'GET',
                'callback' => [$this, 'get_page_header'],
                'permission_callback' => [$this, 'check_permissions'],
            ],
            [
                'methods' => 'POST',
                'callback' => [$this, 'save_page_header'],
                'permission_callback' => [$this, 'check_permissions'],
            ],
        ]);
    }

    public function check_permissions() {
        return current_user_can('edit_posts');
    }

    /**
     * Get all saved header presets
     */
    public function get_header_presets() {
        $presets = get_option('mc_header_presets', []);
        return ['presets' => $presets];
    }

    /**
     * Save a new header preset
     */
    public function save_header_preset($request) {
        $params = $request->get_json_params();
        $name = sanitize_text_field($params['name'] ?? '');
        $blocks = $params['blocks'] ?? '';
        $settings = $params['settings'] ?? [];

        if (empty($name)) {
            return new WP_Error('missing_name', 'Preset name is required.', ['status' => 400]);
        }

        $presets = get_option('mc_header_presets', []);
        $slug = sanitize_title($name);
        
        $presets[] = [
            'name' => $name,
            'slug' => $slug,
            'blocks' => wp_kses_post($blocks),
            'settings' => $settings,
            'created' => current_time('mysql'),
        ];

        update_option('mc_header_presets', $presets);

        return ['success' => true, 'slug' => $slug];
    }

    /**
     * Get page-specific header data
     */
    public function get_page_header($request) {
        $post_id = intval($request->get_param('post_id'));
        if (!$post_id) {
            return new WP_Error('missing_id', 'Post ID is required.', ['status' => 400]);
        }

        $header_data = get_post_meta($post_id, '_mc_local_header', true);
        
        return [
            'has_local_header' => !empty($header_data),
            'header_data' => $header_data ?: null,
        ];
    }

    /**
     * Save page-specific header data
     */
    public function save_page_header($request) {
        $params = $request->get_json_params();
        $post_id = intval($params['post_id'] ?? 0);
        $header_data = $params['header_data'] ?? null;

        if (!$post_id) {
            return new WP_Error('missing_id', 'Post ID is required.', ['status' => 400]);
        }

        if (empty($header_data)) {
            delete_post_meta($post_id, '_mc_local_header');
            return ['success' => true, 'cleared' => true];
        }

        update_post_meta($post_id, '_mc_local_header', $header_data);

        return ['success' => true];
    }

    /**
     * Add meta box for header/footer selection
     */
    public function add_page_header_footer_meta() {
        $post_types = ['page', 'post'];
        if (class_exists('WooCommerce')) {
            $post_types[] = 'product';
        }

        foreach ($post_types as $post_type) {
            add_meta_box(
                'mc_page_header_footer',
                __('Header & Footer', 'modern-core'),
                [$this, 'render_meta_box'],
                $post_type,
                'side',
                'high'
            );
        }
    }

    /**
     * Render the meta box
     */
    public function render_meta_box($post) {
        wp_nonce_field('mc_header_footer_nonce', 'mc_header_footer_nonce');
        
        $has_local_header = get_post_meta($post->ID, '_mc_local_header', true);
        $presets = get_option('mc_header_presets', []);
        
        ?>
        <p>
            <label><strong><?php _e('Header:', 'modern-core'); ?></strong></label><br>
            <select name="mc_header_mode" id="mc_header_mode" style="width: 100%; margin-top: 5px;">
                <option value="global" <?php selected(empty($has_local_header)); ?>>
                    <?php _e('Use Global Header', 'modern-core'); ?>
                </option>
                <option value="custom" <?php selected(!empty($has_local_header)); ?>>
                    <?php _e('Custom Header (Edit in Block Editor)', 'modern-core'); ?>
                </option>
            </select>
        </p>
        
        <?php if (!empty($presets)): ?>
        <p id="mc_preset_selector" style="display: <?php echo !empty($has_local_header) ? 'block' : 'none'; ?>;">
            <label><strong><?php _e('Start from Preset:', 'modern-core'); ?></strong></label><br>
            <select name="mc_header_preset" style="width: 100%; margin-top: 5px;">
                <option value=""><?php _e('Blank / Current', 'modern-core'); ?></option>
                <option value="megastore"><?php _e('Mega Store', 'modern-core'); ?></option>
                <option value="minimalist"><?php _e('Minimalist', 'modern-core'); ?></option>
                <option value="classic"><?php _e('Classic', 'modern-core'); ?></option>
                <?php foreach ($presets as $preset): ?>
                    <option value="<?php echo esc_attr($preset['slug']); ?>">
                        <?php echo esc_html($preset['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <?php endif; ?>

        <script>
            document.getElementById('mc_header_mode').addEventListener('change', function() {
                var presetSelector = document.getElementById('mc_preset_selector');
                if (presetSelector) {
                    presetSelector.style.display = this.value === 'custom' ? 'block' : 'none';
                }
            });
        </script>
        <?php
    }

    /**
     * Save meta box data
     */
    public function save_page_header_footer_meta($post_id) {
        if (!isset($_POST['mc_header_footer_nonce']) || 
            !wp_verify_nonce($_POST['mc_header_footer_nonce'], 'mc_header_footer_nonce')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $header_mode = sanitize_text_field($_POST['mc_header_mode'] ?? 'global');
        
        if ($header_mode === 'global') {
            delete_post_meta($post_id, '_mc_local_header');
        }
        // If custom, the block editor will handle saving via REST API
    }

    /**
     * Enqueue sidebar plugin for block editor
     */
    public function enqueue_sidebar_plugin() {
        $screen = get_current_screen();
        if (!$screen || !in_array($screen->id, ['page', 'post', 'product'])) {
            return;
        }

        wp_enqueue_script(
            'mc-header-sidebar',
            get_template_directory_uri() . '/assets/dist/blocks/header-sidebar/index.js',
            ['wp-plugins', 'wp-edit-post', 'wp-components', 'wp-data', 'wp-api-fetch'],
            '1.0.0',
            true
        );

        wp_localize_script('mc-header-sidebar', 'mcHeaderData', [
            'postId' => get_the_ID(),
            'restUrl' => rest_url('modern-core/v1/'),
            'nonce' => wp_create_nonce('wp_rest'),
        ]);
    }
}

// Initialize
new MC_Dynamic_Header_Footer();