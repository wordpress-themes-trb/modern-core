<?php
/**
 * Theme Orchestrator.
 *
 * @package ModernCore\Core
 */

declare(strict_types=1);

namespace ModernCore\Core;

/**
 * Orchestrates the initialization of the theme architecture.
 */
class ThemeLoader {

    /**
     * The dependency injection container.
     *
     * @var ServiceContainer
     */
    private ServiceContainer $container;

    /**
     * The hooks manager.
     *
     * @var HooksManager
     */
    private HooksManager $hooks_manager;

    /**
     * Constructor. Initializes the core systems.
     */
    public function __construct() {
        $this->container     = new ServiceContainer();
        $this->hooks_manager = new HooksManager();
    }

    /**
     * Boot the theme components.
     *
     * @return void
     */
    public function boot(): void {
        $this->register_services();
        $this->initialize_services();
        $this->hooks_manager->register();
        
        // Boot up premium UX & distribution systems
        $this->register_ux_hooks();
    }

    /**
     * Register all theme services into the container.
     */
    private function register_services(): void {
        $services = [
            'setup'         => new ThemeSetup(),
            'settings'      => new ThemeSettings(),
            'editor'        => new EditorFeatures(),
            'translations'  => new \ModernCore\I18n\Translations(),
            'accessibility' => new \ModernCore\Accessibility\A11yEnforcer(),
            'performance'   => new \ModernCore\Performance\Optimization(),
            'cache'         => new \ModernCore\Performance\TransientCache(),
            'blocks'        => new \ModernCore\Blocks\BlockManager(),
            'dependencies'  => new DependencyManager(),
            'configuration' => new ConfigurationEngine(),
            'woocommerce'   => new \ModernCore\Woocommerce\WooCommerceIntegration(),
            'ajax'          => new \ModernCore\Store\AjaxEndpoints(),
            'newsletter'    => new \ModernCore\Store\Newsletter(),
            'back_in_stock' => new \ModernCore\Store\BackInStock(),
        ];
        foreach ( $services as $name => $service ) {
            $this->container->register( $name, $service );
        }
    }

    /**
     * Access a registered service, e.g. the product cache from a block.
     *
     * @param string $name Service name.
     */
    public function get( string $name ): ?object {
        return $this->container->get( $name );
    }

    /**
     * Iterate through registered services and attach their hooks.
     */
    private function initialize_services(): void {
        foreach ( $this->container->get_services() as $service ) {
            if ( method_exists( $service, 'register_hooks' ) ) {
                $service->register_hooks( $this->hooks_manager );
            }
        }
    }

    /**
     * Wire up the structural and distribution hooks that power the user onboarding layer.
     */
    private function register_ux_hooks(): void {
        // Register the template blueprints as 1-click install patterns
        add_action( 'init', [ $this, 'register_pattern_architecture' ] );

        // Render administrative workspace notice
        add_action( 'admin_notices', [ $this, 'render_onboarding_wizard_notice' ] );
        add_action( 'admin_init', [ $this, 'process_onboarding_dismissal' ] );
    }

    /**
     * Exposes full-page structural compositions in the block inserter tool.
     */
    public function register_pattern_architecture(): void {
        register_block_pattern_category(
            'modern-core-starter-compositions',
            [ 'label' => esc_html__( 'ModernCore Full Store Layouts', 'modern-core' ) ]
        );

        // Fetch our front-page blueprint content directly to provide an unboxing template configuration
        $front_page_path = get_template_directory() . '/templates/front-page.html';
        if ( is_readable( $front_page_path ) ) {
            // Template parts (header/footer) belong to the template, not to a page pattern.
            $composition_data = preg_replace( '/<!--\s+wp:template-part\b.*?\/-->\s*/s', '', (string) file_get_contents( $front_page_path ) );
            if ( ! empty( $composition_data ) ) {
                register_block_pattern(
                    'modern-core/editorial-storefront',
                    [
                        'title'       => esc_html__( 'Fashion Editorial Storefront (Starter Site)', 'modern-core' ),
                        'categories'  => [ 'modern-core-starter-compositions' ],
                        'description' => esc_html__( 'Deploy a pre-configured enterprise homepage flow built with modern-core components.', 'modern-core' ),
                        'content'     => $composition_data,
                    ]
                );
            }
        }
    }

    /**
     * Provides an executive setup directive to guide non-technical users directly to editing capabilities.
     */
    public function render_onboarding_wizard_notice(): void {
        if ( get_option( 'mc_onboarding_complete_flag' ) || ! current_user_can( 'manage_options' ) ) {
            return;
        }

        $editor_route = admin_url( 'site-editor.php' );
        $dismiss_ajax = wp_nonce_url( add_query_arg( 'mc_dismiss_wizard', '1' ), 'mc_dismiss_action' );

        ?>
        <div class="notice notice-info" style="background: #0f172a; color: #ffffff; border-left: 4px solid #e2b57a; padding: 24px; margin-top: 20px; border-radius: 4px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 20px;">
                <div style="flex: 1; min-width: 300px;">
                    <h2 style="color: #ffffff; margin: 0 0 8px 0; font-size: 20px; font-weight: 700; letter-spacing: -0.02em;"><?php esc_html_e( 'Modern Core is active', 'modern-core' ); ?></h2>
                    <p style="margin: 0; font-size: 14px; color: #cbd5e1; line-height: 1.5;"><?php echo esc_html( sprintf( /* translators: %d: number of blocks */ __( '%d store blocks and full-site templates are ready. Open the Site Editor to customise your header, homepage and shop.', 'modern-core' ), count( glob( MODERN_CORE_DIR . '/blocks/*/block.json' ) ?: [] ) ) ); ?></p>
                </div>
                <div style="display: flex; gap: 12px; align-items: center;">
                    <a href="<?php echo esc_url( $editor_route ); ?>" class="button button-primary" style="background: #e2b57a; color: #0f172a; border: none; text-shadow: none; font-weight: 600; padding: 8px 20px; height: auto; border-radius: 4px; box-shadow: 0 2px 4px rgba(226,181,122,0.2);"><?php esc_html_e( 'Open Site Editor', 'modern-core' ); ?></a>
                    <a href="<?php echo esc_url( $dismiss_ajax ); ?>" class="button button-secondary" style="background: transparent; color: #94a3b8; border-color: rgba(255,255,255,0.15); padding: 8px 16px; height: auto; border-radius: 4px;"><?php esc_html_e( 'Dismiss', 'modern-core' ); ?></a>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Intercepts dismissal request arguments to manage dashboard execution states.
     */
    public function process_onboarding_dismissal(): void {
        if ( isset( $_GET['mc_dismiss_wizard'] ) && current_user_can( 'manage_options' ) && check_admin_referer( 'mc_dismiss_action' ) ) {
            update_option( 'mc_onboarding_complete_flag', true );
            wp_redirect( remove_query_arg( [ 'mc_dismiss_wizard', '_wpnonce' ] ) );
            exit;
        }
    }
}