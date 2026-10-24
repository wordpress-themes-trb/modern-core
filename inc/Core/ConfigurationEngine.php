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
        $hooks_manager->add_action( 'after_switch_theme', $this, 'initialize_storefront_configuration' );
    }

    /**
     * Executes official WordPress and WooCommerce API methods to prepare the storefront.
     *
     * @return void
     */
    public function initialize_storefront_configuration(): void {
        // 1. Route the root URL to a static Home page (rendered by templates/front-page.html),
        // unless the site already uses a static front page.
        if ( 'page' === get_option( 'show_on_front' ) && get_post( (int) get_option( 'page_on_front' ) ) ) {
            $this->create_woocommerce_pages();
            $this->create_theme_pages();
            return;
        }
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

        // With a static homepage, blog posts need their own page ("Journal").
        if ( ! get_option( 'page_for_posts' ) ) {
            $journal = get_page_by_path( 'journal' );
            $journal_id = $journal ? $journal->ID : wp_insert_post( [
                'post_title'  => __( 'Journal', 'modern-core' ),
                'post_name'   => 'journal',
                'post_status' => 'publish',
                'post_type'   => 'page',
            ] );
            if ( $journal_id && ! is_wp_error( $journal_id ) ) {
                update_option( 'page_for_posts', (int) $journal_id );
            }
        }

        $this->create_woocommerce_pages();
        $this->create_theme_pages();

        // 3. Add two sample products, but only to an empty store, never to a live catalogue.
        if ( class_exists( 'WooCommerce' ) && ! get_option( 'mc_sample_catalog_seeded' ) && ! $this->store_has_products() ) {
            $this->seed_sample_woocommerce_products();
        }
    }

    /**
     * Whether the store already has products.
     */
    private function store_has_products(): bool {
        $ids = get_posts(
            [
                'post_type'      => 'product',
                'post_status'    => 'any',
                'posts_per_page' => 1,
                'fields'         => 'ids',
            ]
        );
        return ! empty( $ids );
    }

    /**
     * Create the Wishlist and Compare pages if they don't exist.
     * They hold the theme's Wishlist and Compare Table blocks, so the store
     * owner can edit their wording like any other page.
     */
    private function create_theme_pages(): void {
        $section = static function ( string $heading, string $block ): string {
            return '<!-- wp:modern-core/section-wrapper {"wrapperWidth":"full","contentWidth":"contained","paddingTopDesktop":56,"paddingTopMobile":32,"paddingBottomDesktop":80,"paddingBottomMobile":48} -->' . "\n"
                . '<!-- wp:heading {"level":1} -->' . "\n" . '<h1 class="wp-block-heading">' . esc_html( $heading ) . '</h1>' . "\n" . '<!-- /wp:heading -->' . "\n\n"
                . $block . "\n"
                . '<!-- /wp:modern-core/section-wrapper -->';
        };
        $pages = [
            'wishlist' => [
                __( 'Wishlist', 'modern-core' ),
                $section( __( 'Wishlist', 'modern-core' ), '<!-- wp:modern-core/wishlist {"layoutMode":"grid","gridCols":4,"accentColor":"#0f172a"} /-->' ),
            ],
            'compare'  => [
                __( 'Compare products', 'modern-core' ),
                $section( __( 'Compare products', 'modern-core' ), '<!-- wp:modern-core/compare-table {"sourceMode":"dynamic","maxColumns":4,"accentColor":"#0f172a"} /-->' ),
            ],
            // About and Contact start from the theme's patterns (linked from the footer).
            // Sample stats and reviews stay in the pattern (for owners to fill in) but are
            // left off the live page, so a new store never shows invented customer reviews.
            'about'      => [ __( 'About us', 'modern-core' ), $this->pattern_content( 'modern-core/about-us', [ 'modern-core/stats-counter', 'modern-core/testimonials' ] ) ],
            'contact-us' => [ __( 'Contact us', 'modern-core' ), $this->pattern_content( 'modern-core/contact-us' ) ],
        ];
        foreach ( $pages as $slug => [ $title, $content ] ) {
            if ( get_page_by_path( $slug ) ) {
                continue;
            }
            wp_insert_post(
                [
                    'post_title'    => $title,
                    'post_name'     => $slug,
                    'post_content'  => $content,
                    'post_status'   => 'publish',
                    'post_type'     => 'page',
                    // Full-width template: the section above supplies its own width and heading.
                    'page_template' => 'page-full-width',
                ]
            );
        }
    }

    /**
     * The block markup of a registered pattern, so the page is editable block by
     * block. Falls back to a pattern reference if patterns aren't registered yet.
     */
    private function pattern_content( string $slug, array $exclude = [] ): string {
        if ( ! did_action( 'init' ) || ! \WP_Block_Patterns_Registry::get_instance()->is_registered( $slug ) ) {
            if ( function_exists( '_register_theme_block_patterns' ) ) {
                _register_theme_block_patterns();
            }
        }
        $pattern = \WP_Block_Patterns_Registry::get_instance()->get_registered( $slug );
        if ( empty( $pattern['content'] ) ) {
            return '<!-- wp:pattern {"slug":"' . esc_attr( $slug ) . '"} /-->';
        }
        if ( ! $exclude ) {
            return $pattern['content'];
        }
        $blocks = array_filter(
            parse_blocks( $pattern['content'] ),
            static function ( $block ) use ( $exclude ) {
                // Drop the excluded blocks, whether top-level or alone in a section wrapper.
                $names = [ $block['blockName'] ];
                foreach ( $block['innerBlocks'] as $inner ) {
                    $names[] = $inner['blockName'];
                }
                return ! array_intersect( array_filter( $names ), $exclude ) && null !== $block['blockName'];
            }
        );
        return serialize_blocks( array_values( $blocks ) );
    }

    /**
     * Create any missing Shop, Cart, Checkout and My Account pages.
     */
    private function create_woocommerce_pages(): void {
        if ( class_exists( 'WC_Install' ) ) {
            // This safely creates missing pages for Shop, Cart, Checkout, and My Account,
            // mapping them directly to our page-cart.html, page-checkout.html, and page-account.html FSE structures
            \WC_Install::create_pages();
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

        // Starter categories, so the homepage "Shop by category" section has something to show.
        $category_ids = [];
        foreach ( [ 'clothing' => __( 'Clothing', 'modern-core' ), 'accessories' => __( 'Accessories', 'modern-core' ) ] as $slug => $name ) {
            $term = term_exists( $slug, 'product_cat' );
            if ( ! $term ) {
                $term = wp_insert_term( $name, 'product_cat', [ 'slug' => $slug ] );
            }
            $category_ids[ $slug ] = is_array( $term ) ? (int) $term['term_id'] : 0;
        }

        // Create Sample Product 1: The Essential Tailored Blazer
        $product1 = new \WC_Product_Simple();
        $product1->set_category_ids( array_filter( [ $category_ids['clothing'] ] ) );
        $product1->set_featured( true );
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
        $product2->set_category_ids( array_filter( [ $category_ids['accessories'] ] ) );
        $product2->set_short_description( 'A structured everyday tote in full-grain leather.' );
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