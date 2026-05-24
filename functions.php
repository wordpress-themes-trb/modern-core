<?php
/**
 * Theme functions and definitions
 *
 * @package ModernCore
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// 1. Require Composer autoloader.
$modern_core_autoloader = __DIR__ . '/vendor/autoload.php';
if ( is_readable( $modern_core_autoloader ) ) {
    require_once $modern_core_autoloader;
}

// 2. Boot the theme architecture.
if ( class_exists( '\ModernCore\Core\ThemeLoader' ) ) {
    $modern_core = new \ModernCore\Core\ThemeLoader();
    $modern_core->boot();
}

// 3. Register the Custom Block Category
add_filter( 'block_categories_all', function( $categories ) {
    return array_merge(
        $categories,
        [ [ 'slug' => 'modern-core', 'title' => 'Modern Core Blocks' ] ]
    );
} );

// ==========================================
// THEME SETUP & WOOCOMMERCE FSE SUPPORT
// ==========================================
add_action( 'after_setup_theme', 'modern_core_theme_setup' );
function modern_core_theme_setup() {
    // 1. Essential Gutenberg FSE Supports
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'editor-styles' );
    
    // 2. Declare standard WooCommerce compatibility
    add_theme_support( 'woocommerce', array(
        'thumbnail_image_width' => 600,
        'single_image_width'    => 800,
    ) );
    
    // 3. Enable Premium WooCommerce Gallery Features (for single product fallback)
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    // 4. Remove default WP patterns to keep the Site Editor clean & premium
    remove_theme_support( 'core-block-patterns' );
}

// 5. Disable default WooCommerce stylesheet bloat (we handle styling in our blocks)
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
// ==========================================
// DIRECT BLOCK REGISTRATION & ENQUEUEING
// ==========================================
add_action('init', function() {
    $dist_path = get_template_directory_uri() . '/assets/dist/blocks/';
    
    // Standard dependencies for most blocks
    $standard_deps = ['wp-blocks', 'wp-element', 'wp-components', 'wp-block-editor', 'wp-i18n'];
    
    // Header requires 'wp-data' for the 1-Click Preset Engine
    $header_deps = array_merge($standard_deps, ['wp-data']);

    // A. Register all JavaScript files
    wp_register_script('modern-core-header-js', $dist_path . 'advanced-header/index.js', $header_deps, time(), true);
    wp_register_script('modern-core-marquee-js', $dist_path . 'marquee/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-logo-js', $dist_path . 'logo/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-icons-js', $dist_path . 'header-icons/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-navigation-js', $dist_path . 'navigation/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-hamburger-js', $dist_path . 'hamburger/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-search-js', $dist_path . 'search/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-footer-js', $dist_path . 'advanced-footer/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-payment-icons-js', $dist_path . 'payment-icons/index.js', $standard_deps, time(), true);    wp_register_script('modern-core-offcanvas-js', $dist_path . 'off-canvas/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-cart-items-js', $dist_path . 'cart-items/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-filters-js', $dist_path . 'product-filters/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-stickybar-js', $dist_path . 'mobile-sticky-bar/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-hero-js', $dist_path . 'hero/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-hero-layout-js', $dist_path . 'hero-layout/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-trust-js', $dist_path . 'trust-strip/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-countdown-js', $dist_path . 'countdown/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-slider-js', $dist_path . 'slider/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-slide-js', $dist_path . 'slide/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-promo-js', $dist_path . 'promo-banner/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-split-banner-js', $dist_path . 'split-banner/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-video-hero-js', $dist_path . 'video-hero/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-announcement-js', $dist_path . 'announcement-bar/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-countdown-banner-js', $dist_path . 'countdown-banner/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-product-grid-js', $dist_path . 'product-grid/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-product-carousel-js', $dist_path . 'product-carousel/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-product-card-js', $dist_path . 'product-card/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-featured-product-js', $dist_path . 'featured-product/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-product-tabs-js', $dist_path . 'product-tabs/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-lookbook-js', $dist_path . 'lookbook/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-quick-view-js', $dist_path . 'quick-view/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-recently-viewed-js', $dist_path . 'recently-viewed/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-compare-js', $dist_path . 'compare-table/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-wishlist-js', $dist_path . 'wishlist/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-bundle-js', $dist_path . 'bundle-upsell/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-stock-js', $dist_path . 'stock-countdown/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-category-grid-js', $dist_path . 'category-grid/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-category-carousel-js', $dist_path . 'category-carousel/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-mega-menu-js', $dist_path . 'mega-menu/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-filter-sidebar-js', $dist_path . 'filter-sidebar/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-shop-toolbar-js', $dist_path . 'shop-toolbar/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-pagination-js', $dist_path . 'pagination/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-testimonials-js', $dist_path . 'testimonials/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-reviews-summary-js', $dist_path . 'reviews-summary/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-feature-list-js', $dist_path . 'icon-feature-list/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-stats-js', $dist_path . 'stats-counter/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-posts-grid-js', $dist_path . 'posts-grid/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-faq-js', $dist_path . 'faq-accordion/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-content-tabs-js', $dist_path . 'content-tabs/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-pricing-table-js', $dist_path . 'pricing-table/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-trust-badges-js', $dist_path . 'trust-badges/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-newsletter-js', $dist_path . 'newsletter/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-shipping-partners-js', $dist_path . 'shipping-partners/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-live-sales-js', $dist_path . 'live-sales-popup/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-shipping-progress-js', $dist_path . 'shipping-progress/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-exit-popup-js', $dist_path . 'exit-popup/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-cookie-js', $dist_path . 'cookie-consent/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-back-in-stock-js', $dist_path . 'back-in-stock/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-single-product-js', $dist_path . 'single-product/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-mini-cart-js', $dist_path . 'mini-cart/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-cart-page-js', $dist_path . 'cart-page/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-checkout-page-js', $dist_path . 'checkout-page/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-order-confirmation-js', $dist_path . 'order-confirmation/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-my-account-js', $dist_path . 'my-account/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-product-gallery-js', $dist_path . 'product-gallery/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-variation-picker-js', $dist_path . 'variation-picker/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-section-wrapper-js', $dist_path . 'section-wrapper/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-columns-js', $dist_path . 'columns-builder/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-spacer-divider-js', $dist_path . 'spacer-divider/index.js', $standard_deps, time(), true);
    wp_register_script('modern-core-icon-js', $dist_path . 'icon/index.js', $standard_deps, time(), true);


    // B. Register all Blocks automatically via loop
    $custom_blocks = [
        'advanced-header',
        'marquee',
        'logo',
        'header-icons',
        'navigation',
        'hamburger',
        'search',
        'advanced-footer',
        'newsletter',
        'payment-icons',
        'off-canvas',
        'cart-items',
        'product-filters',
        'mobile-sticky-bar',
        'hero',
        'hero-layout',
        'trust-strip',
        'countdown',
        'slider',
        'slide',
        'promo-banner',
        'split-banner',
        'video-hero',
        'announcement-bar',
        'countdown-banner',
        'product-grid',
        'product-carousel',
        'product-card',
        'featured-product',
        'product-tabs',
        'lookbook',
        'quick-view',
        'recently-viewed',
        'compare-table',
        'wishlist',
        'bundle-upsell',
        'stock-countdown',
        'category-grid',
        'category-carousel',
        'mega-menu',
        'filter-sidebar',
        'shop-toolbar',
        'pagination',
        'testimonials',
        'reviews-summary',
        'icon-feature-list',  
        'stats-counter',
        'posts-grid',
        'faq-accordion',
        'content-tabs',
        'tab-pane',
        'pricing-table',
        'trust-badges',
        'shipping-partners',
        'live-sales-popup',
        'shipping-progress',
        'exit-popup',
        'cookie-consent',
        'back-in-stock',
        'single-product',
        'mini-cart',
        'cart-page',
        'checkout-page',
        'order-confirmation',
        'my-account',
        'product-gallery',
        'variation-picker',
        'section-wrapper',
        'columns-builder',
        'column',
        'spacer-divider',
        'icon'


    ];

    foreach ( $custom_blocks as $block ) {
        $block_path = get_template_directory() . '/blocks/' . $block;
        if ( file_exists( $block_path . '/block.json' ) ) {
            register_block_type( $block_path );
        }
    }
});
add_action('wp_ajax_mc_quickview_ajax', 'mc_quickview_ajax_handler');
add_action('wp_ajax_nopriv_mc_quickview_ajax', 'mc_quickview_ajax_handler');

function mc_quickview_ajax_handler() {
    if (!isset($_POST['product_id']) || !class_exists('WooCommerce')) {
        wp_send_json_error();
    }

    $product_id = intval($_POST['product_id']);
    global $post;
    $post = get_post($product_id);
    setup_postdata($post);
    $product = wc_get_product($product_id);

    if (!$product) wp_send_json_error();

    // Generate Image HTML
    $image_html = '<div style="position:relative;">';
    $image_html .= get_the_post_thumbnail($product_id, 'large', ['style' => 'width:100%; height:auto; border-radius:8px;']);
    $image_html .= '</div>';

    // Generate Content HTML
    ob_start();
    ?>
    <h2 style="font-size: 24px; margin: 0 0 10px 0; color: #0f172a;"><?php echo $product->get_name(); ?></h2>
    
    <?php if ($product->get_rating_count() > 0) : ?>
        <div style="color: #f59e0b; font-size: 14px; margin-bottom: 15px;">
            <?php echo wc_get_rating_html($product->get_average_rating()); ?>
        </div>
    <?php endif; ?>

    <div style="font-size: 20px; font-weight: bold; color: #0f172a; margin-bottom: 15px;">
        <?php echo $product->get_price_html(); ?>
    </div>

    <div style="font-size: 14px; color: #0f172a; opacity: 0.7; margin-bottom: 20px; line-height: 1.5;">
        <?php echo apply_filters('woocommerce_short_description', $product->get_short_description()); ?>
    </div>

    <?php woocommerce_template_single_add_to_cart(); ?>

    <div style="text-align: center; margin-top: 15px;">
        <a href="<?php echo get_permalink($product_id); ?>" style="font-size: 13px; color: inherit; text-decoration: underline;">View full details</a>
    </div>
    <?php
    $content_html = ob_get_clean();

    wp_reset_postdata();

    wp_send_json_success([
        'image_html' => $image_html,
        'content_html' => $content_html
    ]);
}

// Make sure ajaxurl is available globally
add_action('wp_head', function() {
    echo '<script>var mc_ajax = { ajaxurl: "' . admin_url('admin-ajax.php') . '" };</script>';
});
// 1. Tracker Script (Only runs on Single Product Pages)
add_action('wp_footer', function() {
    if (is_product()) {
        global $post;
        $product_id = $post->ID;
        ?>
        <script>
            (function() {
                const pId = <?php echo $product_id; ?>;
                let history = [];
                try {
                    const stored = localStorage.getItem('mc_recently_viewed');
                    if (stored) history = JSON.parse(stored);
                } catch(e) {}
                
                // Remove if exists so we can move it to the front
                history = history.filter(item => item.id !== pId);
                
                // Add to front with timestamp
                history.unshift({ id: pId, time: new Date().getTime() });
                
                // Keep only last 20 globally
                if (history.length > 20) history = history.slice(0, 20);
                
                localStorage.setItem('mc_recently_viewed', JSON.stringify(history));
            })();
        </script>
        <?php
    }
});

// 2. AJAX Endpoint
add_action('wp_ajax_mc_recent_products_ajax', 'mc_recent_products_ajax_handler');
add_action('wp_ajax_nopriv_mc_recent_products_ajax', 'mc_recent_products_ajax_handler');

function mc_recent_products_ajax_handler() {
    if (!class_exists('WooCommerce')) wp_send_json_error();

    $is_fallback = $_POST['is_fallback'] === 'true';
    $args = [
        'post_type' => 'product',
        'post_status' => 'publish',
        'meta_query' => [['key' => '_stock_status', 'value' => 'instock']] // Hide out of stock as requested!
    ];

    if ($is_fallback) {
        $fallback = sanitize_text_field($_POST['fallback_query']);
        $args['posts_per_page'] = 8;
        if ($fallback === 'popularity') {
            $args['meta_key'] = 'total_sales'; $args['orderby'] = 'meta_value_num';
        } elseif ($fallback === 'sale') {
            $args['post__in'] = array_merge([0], wc_get_product_ids_on_sale());
        }
    } else {
        $ids = json_decode(stripslashes($_POST['product_ids']), true);
        if (empty($ids)) wp_send_json_error();
        $args['post__in'] = $ids;
        $args['orderby'] = 'post__in'; // Maintain history order
        $args['posts_per_page'] = count($ids);
    }

    $products = new WP_Query($args);
    
    if (!$products->have_posts()) wp_send_json_error();

    ob_start();
    while ($products->have_posts()) {
        $products->the_post();
        global $product;
        // We will output a streamlined version of the Product Card CSS structure we already built
        ?>
        <div <?php wc_product_class('mc-product-card', $product); ?> style="background: #fff; border-radius: 8px; border: 1px solid rgba(0,0,0,0.05); overflow: hidden; display: flex; flex-direction: column;">
            <a href="<?php echo esc_url($product->get_permalink()); ?>" style="display: block; width: 100%; aspect-ratio: 3/4;">
                <img src="<?php echo esc_url(wp_get_attachment_image_url($product->get_image_id(), 'woocommerce_thumbnail')); ?>" style="width: 100%; height: 100%; object-fit: cover;" />
            </a>
            <div style="padding: 15px; display: flex; flex-direction: column; flex: 1;">
                <a href="<?php echo esc_url($product->get_permalink()); ?>" style="font-size: 14px; font-weight: bold; text-decoration: none; color: inherit; margin-bottom: 10px;"><?php echo $product->get_name(); ?></a>
                <div style="margin-top: auto; font-weight: bold;"><?php echo $product->get_price_html(); ?></div>
            </div>
        </div>
        <?php
    }
    $html = ob_get_clean();
    wp_reset_postdata();

    wp_send_json_success(['html' => $html]);
}
add_action('wp_ajax_mc_wishlist_ajax', 'mc_wishlist_ajax_handler');
add_action('wp_ajax_nopriv_mc_wishlist_ajax', 'mc_wishlist_ajax_handler');

function mc_wishlist_ajax_handler() {
    if (!class_exists('WooCommerce')) wp_send_json_error();

    $ids = json_decode(stripslashes($_POST['product_ids']), true);
    if (empty($ids)) wp_send_json_error();

    $show_stock = $_POST['show_stock'] === '1';
    $show_price = $_POST['show_price'] === '1';

    $args = [
        'post_type' => 'product',
        'post_status' => 'publish',
        'post__in' => $ids,
        'posts_per_page' => -1
    ];
    
    $products = new WP_Query($args);
    if (!$products->have_posts()) wp_send_json_error();

    ob_start();
    while ($products->have_posts()) {
        $products->the_post();
        global $product;
        
        $is_oos = !$product->is_in_stock();
        $oos_class = ($show_stock && $is_oos) ? 'is-oos' : '';
        
        // Price Drop Calculation (Simple Sale Check)
        $has_drop = $show_price && $product->is_on_sale();
        ?>
        <div class="mc-wish-card <?php echo $oos_class; ?>">
            <button class="mc-wish-remove" data-id="<?php echo $product->get_id(); ?>">✕</button>
            
            <a href="<?php echo esc_url($product->get_permalink()); ?>" class="mc-wish-img-wrap">
                <img src="<?php echo esc_url(wp_get_attachment_image_url($product->get_image_id(), 'woocommerce_thumbnail')); ?>" class="mc-wish-img" />
                <?php if ($has_drop) echo '<span class="mc-badge-drop">PRICE DROP</span>'; ?>
                <?php if ($show_stock && $is_oos) echo '<span class="mc-badge-oos">OUT OF STOCK</span>'; ?>
            </a>
            
            <div class="mc-wish-info">
                <a href="<?php echo esc_url($product->get_permalink()); ?>" class="mc-wish-title"><?php echo $product->get_name(); ?></a>
                <div class="mc-wish-price"><?php echo $product->get_price_html(); ?></div>
                
                <?php if (!$is_oos) {
                    woocommerce_template_loop_add_to_cart();
                } else {
                    echo '<div style="padding: 10px; background: #e2e8f0; color: #94a3b8; text-align: center; border-radius: 4px; font-size: 12px; font-weight: bold;">Unavailable</div>';
                } ?>
            </div>
        </div>
        <?php
    }
    $html = ob_get_clean();
    wp_reset_postdata();

    wp_send_json_success(['html' => $html]);
}

add_action('wp_ajax_mc_bundle_add_ajax', 'mc_bundle_add_ajax_handler');
add_action('wp_ajax_nopriv_mc_bundle_add_ajax', 'mc_bundle_add_ajax_handler');

function mc_bundle_add_ajax_handler() {
    if (!class_exists('WooCommerce')) wp_send_json_error();

    $ids = json_decode(stripslashes($_POST['product_ids']), true);
    if (empty($ids)) wp_send_json_error();

    $added = false;
    foreach ($ids as $product_id) {
        $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($product_id));
        $quantity = 1;
        $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
        $product_status = get_post_status($product_id);

        if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity) && 'publish' === $product_status) {
            $added = true;
        }
    }

    if ($added) {
        // Return standard fragments so the Sticky Bar mini-cart updates automatically!
        WC_AJAX::get_refreshed_fragments();
    } else {
        wp_send_json_error();
    }
}

add_action('wp_ajax_mc_newsletter_submit_ajax', 'mc_newsletter_submit_ajax_handler');
add_action('wp_ajax_nopriv_mc_newsletter_submit_ajax', 'mc_newsletter_submit_ajax_handler');

function mc_newsletter_submit_ajax_handler() {
    $email = sanitize_email($_POST['mc_email'] ?? '');
    $fname = sanitize_text_field($_POST['mc_fname'] ?? '');
    $provider = sanitize_text_field($_POST['provider'] ?? 'native');

    if (!is_email($email)) {
        wp_send_json_error(['message' => 'Invalid email address.']);
    }

    // Example Provider Routing Shell:
    // In a production theme, you would query get_option('mc_api_key_klaviyo') 
    // and execute a wp_remote_post() to their respective endpoints.

    if ($provider === 'klaviyo') {
        // execute Klaviyo API
    } elseif ($provider === 'mailchimp') {
        // execute Mailchimp API
    } else {
        // Native WordPress fallback (Save as a Custom Post Type or User Meta)
    }

    // For this architecture build, we simulate a successful API transmission
    sleep(1); // Simulate API delay
    wp_send_json_success();
}
add_action('wp_ajax_mc_get_cart_subtotal', 'mc_get_cart_subtotal_ajax');
add_action('wp_ajax_nopriv_mc_get_cart_subtotal', 'mc_get_cart_subtotal_ajax');

function mc_get_cart_subtotal_ajax() {
    if (!class_exists('WooCommerce') || !WC()->cart) {
        wp_send_json_error();
    }
    wp_send_json_success(['subtotal' => WC()->cart->get_subtotal()]);
}
// 1. Handle Form Submission
add_action('wp_ajax_mc_back_in_stock_ajax', 'mc_back_in_stock_ajax_handler');
add_action('wp_ajax_nopriv_mc_back_in_stock_ajax', 'mc_back_in_stock_ajax_handler');

function mc_back_in_stock_ajax_handler() {
    $product_id = intval($_POST['product_id']);
    $email = sanitize_email($_POST['bis_email'] ?? '');
    $name = sanitize_text_field($_POST['bis_name'] ?? '');

    if (!$product_id || !is_email($email)) {
        wp_send_json_error(['message' => 'Invalid data.']);
    }

    $waitlist = get_post_meta($product_id, '_mc_bis_waitlist', true);
    if (!is_array($waitlist)) $waitlist = [];

    // Prevent duplicates
    foreach ($waitlist as $entry) {
        if ($entry['email'] === $email) wp_send_json_success(); // Silently succeed
    }

    $waitlist[] = ['email' => $email, 'name' => $name];
    update_post_meta($product_id, '_mc_bis_waitlist', $waitlist);

    wp_send_json_success();
}

// 2. Automated Email Trigger on Restock
add_action('woocommerce_product_set_stock_status', 'mc_trigger_back_in_stock_emails', 10, 3);

function mc_trigger_back_in_stock_emails($product_id, $stock_status, $product) {
    if ($stock_status !== 'instock') return;

    $waitlist = get_post_meta($product_id, '_mc_bis_waitlist', true);
    if (empty($waitlist) || !is_array($waitlist)) return;

    // Fetch block settings globally (fallback logic if block defaults needed)
    $subject_template = "Great news! {product_name} is back in stock.";
    $body_template = "The wait is over. {product_name} is now available again. Grab yours before it sells out!\n\n{product_url}";

    $product_name = $product->get_name();
    $product_url = $product->get_permalink();

    $subject = str_replace('{product_name}', $product_name, $subject_template);
    $body_base = str_replace(['{product_name}', '{product_url}'], [$product_name, $product_url], $body_template);

    foreach ($waitlist as $entry) {
        // Send email securely via standard WP Mail
        wp_mail($entry['email'], $subject, $body_base);
    }

    // Clear waitlist to prevent duplicate emails on future stock updates
    delete_post_meta($product_id, '_mc_bis_waitlist');
}
add_action('wp_ajax_mc_get_cart_count', 'mc_get_cart_count_ajax');
add_action('wp_ajax_nopriv_mc_get_cart_count', 'mc_get_cart_count_ajax');

function mc_get_cart_count_ajax() {
    if (!class_exists('WooCommerce') || !WC()->cart) {
        wp_send_json_error();
    }
    wp_send_json_success(['count' => WC()->cart->get_cart_contents_count()]);
}

require_once get_template_directory() . '/inc/Core/dynamic-header-footer.php';