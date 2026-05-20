<?php
/**
 * Theme Initialization and Configuration Engine.
 *
 * @package ModernCore\Core
 */

declare(strict_types=1);

namespace ModernCore\Core;

/**
 * Automates core site settings and handles safe demo content orchestration via core APIs.
 */
class ConfigurationEngine {

    /**
     * Attaches configuration hooks to theme activation.
     *
     * @param HooksManager $hooks_manager The theme hooks engine.
     * @return void
     */
    public function register_hooks( HooksManager $hooks_manager ): void {
        // Runs exactly once when the store admin activates the theme
        add_action( 'after_switch_theme', [ $this, 'initialize_storefront_configuration' ] );
    }

    /**
     * Executes official WordPress and WooCommerce API methods to prepare the storefront.
     *
     * @return void
     */
    public function initialize_storefront_configuration(): void {
        // 1. Force WordPress to route the root URL to a static front page (our front-page.html template)
        update_option( 'show_on_front', 'page' );

        // Check if an actual page named "Home" exists; if not, safely create an empty page shell
        $home_page_id = get_page_by_path( 'home' ) ? get_page_by_path( 'home' )->ID : 0;
        if ( ! $home_page_id ) {
            $home_page_id = wp_insert_post( [
                'post_title'  => 'Home',
                'post_status' => 'publish',
                'post_type'   => 'page',
            ] );
        }
        if ( $home_page_id ) {
            update_option( 'page_on_front', $home_page_id );
        }

        // 2. Instruct WooCommerce to auto-create and link its mandatory functional shop pages
        if ( class_exists( 'WC_Install' ) ) {
            // This safely creates missing pages for Shop, Cart, Checkout, and My Account,
            // mapping them directly to our page-cart.html, page-checkout.html, and page-account.html FSE structures
            \WC_Install::create_pages();
        }

        // 3. Optional: Trigger a safe indicator flag for demo product handling
        if ( class_exists( 'WooCommerce' ) && ! get_option( 'mc_sample_catalog_seeded' ) ) {
            $this->seed_sample_woocommerce_products();
        }
    }

    /**
     * Generates standard sample catalog items using the official WooCommerce Product CRUD API API.
     * We NEVER interact with SQL directly; we use WooCommerce object methods.
     *
     * @return void
     */
    private function seed_sample_woocommerce_products(): void {
        if ( ! class_exists( 'WC_Product_Simple' ) ) {
            return;
        }

        // Create Sample Product 1: The Essential Tailored Blazer
        $product1 = new \WC_Product_Simple();
        $product1->set_name( 'Essential Tailored Blazer' );
        $product1->set_status( 'publish' );
        $product1->set_catalog_visibility( 'visible' );
        $product1->set_description( 'An unlined blazer crafted from mid-weight sustainable linen fabric.' );
        $product1->set_short_description( 'Refined structural silhouette matching an after-hours aesthetic.' );
        $product1->set_regular_price( '185.00' );
        $product1->set_sku( 'MC-BLZR-01' );
        $product1->set_manage_stock( false );
        $product1->set_stock_status( 'instock' );
        $product1->save(); // WooCommerce safely pushes this directly to the database for us

        // Create Sample Product 2: Classic Minimalist Leather Tote
        $product2 = new \WC_Product_Simple();
        $product2->set_name( 'Classic Minimalist Leather Tote' );
        $product2->set_status( 'publish' );
        $product2->set_catalog_visibility( 'visible' );
        $product2->set_regular_price( '240.00' );
        $product2->set_sale_price( '195.00' ); // Automatically populates our "On Sale" product carousel block!
        $product2->set_sku( 'MC-TOTE-02' );
        $product2->set_stock_status( 'instock' );
        $product2->save();

        // Lock execution option so this script never loops or duplicates items
        update_option( 'mc_sample_catalog_seeded', true );
    }
}