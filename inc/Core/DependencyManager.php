<?php
/**
 * Theme Dependency & Core Requirements Engine.
 *
 * @package ModernCore\Core
 */

declare(strict_types=1);

namespace ModernCore\Core;

/**
 * Manages plugin dependencies and vital execution states for ModernCore storefronts.
 */
class DependencyManager {

    /**
     * Attaches system hooks to the WordPress initialization lifecycle.
     *
     * @param HooksManager $hooks_manager The theme hooks engine.
     * @return void
     */
    public function register_hooks( HooksManager $hooks_manager ): void {
        // Enforce dependencies on the admin panel dashboard
        add_action( 'admin_notices', [ $this, 'render_dependency_warnings' ] );
        add_action( 'admin_init', [ $this, 'process_automatic_plugin_activation' ] );

        // Prevent frontend breakage if WooCommerce is uninstalled/inactive
        add_filter( 'template_include', [ $this, 'enforce_frontend_failsafe' ], 99 );
    }

    /**
     * Checks if WooCommerce is installed and actively executing.
     *
     * @return bool True if active, false otherwise.
     */
    public function is_woocommerce_active(): bool {
        return class_exists( 'WooCommerce' );
    }

    /**
     * Generates a structural warning notice if enterprise plugins are missing.
     *
     * @return void
     */
    public function render_dependency_warnings(): void {
        if ( $this->is_woocommerce_active() ) {
            return;
        }

        // Only display to store administrators
        if ( ! current_user_can( 'install_plugins' ) ) {
            return;
        }

        $install_url = wp_nonce_url(
            self_admin_url( 'update.php?action=install-plugin&plugin=woocommerce' ),
            'install-plugin_woocommerce'
        );
        
        $activate_url = wp_nonce_url(
            admin_url( 'plugins.php?action=activate&plugin=woocommerce/woocommerce.php' ),
            'activate-plugin_woocommerce/woocommerce.php'
        );

        // Check if plugin file exists locally but is merely deactivated
        $plugin_file_exists = file_exists( WP_PLUGIN_DIR . '/woocommerce/woocommerce.php' );
        $action_url         = $plugin_file_exists ? $activate_url : $install_url;
        $action_text        = $plugin_file_exists ? __( 'Activate WooCommerce Now', 'modern-core' ) : __( '1-Click Install WooCommerce', 'modern-core' );

        ?>
        <div class="notice notice-error" style="border-left: 4px solid #ef4444; background: #ffffff; padding: 20px; margin-top: 20px; border-radius: 4px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
            <h3 style="color: #0f172a; margin: 0 0 6px 0; font-size: 16px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                <span style="color: #ef4444; font-size: 18px;">⚠️</span> Critical System Requirement Missing
            </h3>
            <p style="margin: 0 0 14px 0; font-size: 14px; color: #64748b; line-height: 1.5;">
                The <strong>ModernCore Engine</strong> requires WooCommerce to process custom block layouts, quick views, and shopping features. Your store templates will remain locked until this dependency is active.
            </p>
            <p style="margin: 0;">
                <a href="<?php echo esc_url( $action_url ); ?>" class="button button-primary" style="background: #ef4444; border-color: #dc2626; color: #fff; text-shadow: none; font-weight: 600; padding: 6px 18px; height: auto; border-radius: 4px;">
                    <?php echo esc_html( $action_url ); ?><?php echo esc_html( $action_text ); ?>
                </a>
            </p>
        </div>
        <?php
    }

    /**
     * Intercepts standard page requests to render a pristine placeholder if dependencies fail.
     * This protects against unstyled template block errors before WooCommerce hooks up.
     *
     * @param string $template Path to the rendering template file.
     * @return string Filtered template path.
     */
    public function enforce_frontend_failsafe( string $template ): string {
        if ( $this->is_woocommerce_active() ) {
            return $template;
        }

        // Render an elegant message instead of a fatal error screen
        status_header( 503 );
        wp_die(
            sprintf(
                '<h1>%s</h1><p>%s</p>',
                esc_html__( 'Storefront Setup in Progress', 'modern-core' ),
                esc_html__( 'ModernCore theme requires WooCommerce to be enabled by the administrator to access store templates.', 'modern-core' )
            ),
            esc_html__( 'Requirement Missing', 'modern-core' ),
            [ 'response' => 503 ]
        );
        exit;
    }

    /**
     * Optional placeholder setup to handle automated backgrounds if needed.
     */
    public function process_automatic_plugin_activation(): void {
        // Logic can expand here if automated asynchronous silent installation paths are introduced
    }
}