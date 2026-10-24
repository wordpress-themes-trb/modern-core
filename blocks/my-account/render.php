<?php
$width_mode = $attributes['widthMode'] ?? 'contained';
if (!class_exists('WooCommerce')) return;

$layout = $attributes['layoutMode'] ?? 'sidebar';
$nav_w = $attributes['navWidth'] ?? 280;
$welcome = $attributes['welcomeFormat'] ?? 'Welcome back, {name}!';
$show_orders = $attributes['showRecentOrders'] ?? true;
$nav_bg = $attributes['navBg'] ?? '#f8fafc';
$content_bg = $attributes['contentBg'] ?? '#ffffff';
$accent = $attributes['accentColor'] ?? '#3b82f6';
$text_c = $attributes['textColor'] ?? '#0f172a';
$border_c = $attributes['borderColor'] ?? '#e2e8f0';
$rad = $attributes['cardRadius'] ?? 12;

$unique_id = uniqid('mc-acct-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "mc-block width-" . esc_attr($width_mode) . " modern-core-my-account {$unique_id} layout-{$layout}"]);

// Dashboard welcome + recent orders, rendered on the server from this block's settings.
$GLOBALS['mc_account_dashboard'] = array(
    'welcome'     => $welcome,
    'show_orders' => (bool) $show_orders,
);
if ( ! function_exists( 'mc_custom_account_dashboard' ) ) {
    function mc_custom_account_dashboard() {
        $settings = $GLOBALS['mc_account_dashboard'] ?? array( 'welcome' => '', 'show_orders' => false );
        $user     = wp_get_current_user();
        $name     = $user->first_name ? $user->first_name : $user->display_name;

        echo '<div class="mc-dashboard-extra">';
        if ( '' !== $settings['welcome'] ) {
            echo '<h2 class="mc-acct-welcome">' . esc_html( str_replace( '{name}', $name, $settings['welcome'] ) ) . '</h2>';
        }
        if ( $settings['show_orders'] && function_exists( 'wc_get_orders' ) ) {
            $orders = wc_get_orders( array( 'customer_id' => $user->ID, 'limit' => 3, 'orderby' => 'date', 'order' => 'DESC' ) );
            if ( $orders ) {
                echo '<h3 class="mc-acct-recent-title">' . esc_html__( 'Recent orders', 'modern-core' ) . '</h3><ul class="mc-acct-recent">';
                foreach ( $orders as $order ) {
                    printf(
                        '<li><a href="%1$s">%2$s</a><span>%3$s</span><span>%4$s</span><strong>%5$s</strong></li>',
                        esc_url( $order->get_view_order_url() ),
                        /* translators: %s: order number */
                        esc_html( sprintf( __( 'Order #%s', 'modern-core' ), $order->get_order_number() ) ),
                        esc_html( wc_format_datetime( $order->get_date_created() ) ),
                        esc_html( wc_get_order_status_name( $order->get_status() ) ),
                        wp_kses_post( $order->get_formatted_order_total() )
                    );
                }
                echo '</ul>';
            }
        }
        echo '</div>';
    }
}
remove_action( 'woocommerce_account_dashboard', 'woocommerce_account_dashboard' );
if ( ! has_action( 'woocommerce_account_dashboard', 'mc_custom_account_dashboard' ) ) {
    add_action( 'woocommerce_account_dashboard', 'mc_custom_account_dashboard' );
}
?>

<div <?php echo $wrapper_attributes; ?>
    data-welcome="<?php echo esc_attr($welcome); ?>"
    data-orders="<?php echo esc_attr($show_orders); ?>">
    
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            --ma-nav-w: <?php echo esc_attr($nav_w); ?>px;
            --ma-nav-bg: <?php echo esc_attr($nav_bg); ?>;
            --ma-con-bg: <?php echo esc_attr($content_bg); ?>;
            --ma-acc: <?php echo esc_attr($accent); ?>;
            --ma-txt: <?php echo esc_attr($text_c); ?>;
            --ma-border: <?php echo esc_attr($border_c); ?>;
            --ma-rad: <?php echo esc_attr($rad); ?>px;
            
            width: 100%; padding: 60px 0; color: var(--ma-txt);
        }

        .<?php echo esc_attr($unique_id); ?> .woocommerce { max-width: 1200px; margin: 0 auto; padding: 0 5%; }

        /* --- Global Reset for WC Account --- */
        .<?php echo esc_attr($unique_id); ?> .woocommerce-MyAccount-navigation ul { list-style: none; margin: 0; padding: 0; }
        .<?php echo esc_attr($unique_id); ?> .woocommerce-MyAccount-navigation li { margin: 0; padding: 0; border: 0; }
        .<?php echo esc_attr($unique_id); ?> .woocommerce-MyAccount-navigation a { text-decoration: none; color: inherit; display: block; }
        
        .<?php echo esc_attr($unique_id); ?> .woocommerce-MyAccount-content {
            background: var(--ma-con-bg); border: 1px solid var(--ma-border); border-radius: var(--ma-rad);
            padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); width: 100%; box-sizing: border-box;
        }

        /* --- 1. Sidebar Layout --- */
        /* Side-by-side only when logged in (the login/register screen has no account menu). */
        .<?php echo esc_attr($unique_id); ?>.layout-sidebar .woocommerce:has(> .woocommerce-MyAccount-navigation) { display: flex; gap: 50px; align-items: flex-start; flex-wrap: wrap; }
        .<?php echo esc_attr($unique_id); ?>.layout-sidebar .woocommerce > .woocommerce-notices-wrapper { flex: 1 1 100%; }
        .<?php echo esc_attr($unique_id); ?> .woocommerce-notices-wrapper:empty { display: none; }
        .<?php echo esc_attr($unique_id); ?>.layout-sidebar .woocommerce-MyAccount-content { flex: 1 1 0; min-width: 0; }
        .<?php echo esc_attr($unique_id); ?> .woocommerce:not(:has(> .woocommerce-MyAccount-navigation)) { max-width: 520px; }
        /* Dashboard: welcome heading first, then WooCommerce's text, then recent orders. */
        .<?php echo esc_attr($unique_id); ?> .woocommerce-MyAccount-content:has(.mc-dashboard-extra) { display: flex; flex-direction: column; }
        .<?php echo esc_attr($unique_id); ?> .mc-dashboard-extra { display: contents; }
        .<?php echo esc_attr($unique_id); ?> .mc-acct-welcome { order: -1; margin: 0 0 16px; font-size: 26px; }
        .<?php echo esc_attr($unique_id); ?> .mc-acct-recent-title { margin: 24px 0 10px; font-size: 16px; }
        .<?php echo esc_attr($unique_id); ?> .mc-acct-recent { list-style: none; margin: 0; padding: 0; border-top: 1px solid var(--ma-border); }
        .<?php echo esc_attr($unique_id); ?> .mc-acct-recent li { display: grid; grid-template-columns: 1.2fr 1fr 1fr auto; gap: 12px; padding: 12px 0; border-bottom: 1px solid var(--ma-border); font-size: 14px; align-items: center; }
        .<?php echo esc_attr($unique_id); ?> .mc-acct-recent a { font-weight: 700; color: inherit; }
        @media (max-width: 560px) { .<?php echo esc_attr($unique_id); ?> .mc-acct-recent li { grid-template-columns: 1fr auto; } }
        .<?php echo esc_attr($unique_id); ?>.layout-sidebar .woocommerce-MyAccount-navigation {
            width: var(--ma-nav-w); flex-shrink: 0; background: var(--ma-nav-bg); border: 1px solid var(--ma-border); border-radius: var(--ma-rad); overflow: hidden;
        }
        .<?php echo esc_attr($unique_id); ?>.layout-sidebar .woocommerce-MyAccount-navigation a { padding: 18px 24px; border-bottom: 1px solid var(--ma-border); font-weight: bold; opacity: 0.7; transition: all 0.2s; }
        .<?php echo esc_attr($unique_id); ?>.layout-sidebar .woocommerce-MyAccount-navigation li:last-child a { border-bottom: none; }
        .<?php echo esc_attr($unique_id); ?>.layout-sidebar .woocommerce-MyAccount-navigation li.is-active a { opacity: 1; border-left: 4px solid var(--ma-acc); background: var(--ma-con-bg); color: var(--ma-acc); }

        @media (max-width: 900px) {
            .<?php echo esc_attr($unique_id); ?>.layout-sidebar .woocommerce { flex-direction: column; gap: 30px; }
            .<?php echo esc_attr($unique_id); ?>.layout-sidebar .woocommerce-MyAccount-navigation { width: 100%; }
        }

        /* --- 2. Tabs Layout --- */
        .<?php echo esc_attr($unique_id); ?>.layout-tabs .woocommerce { display: flex; flex-direction: column; gap: 30px; }
        .<?php echo esc_attr($unique_id); ?>.layout-tabs .woocommerce-MyAccount-navigation { border-bottom: 2px solid var(--ma-border); overflow-x: auto; scrollbar-width: none; }
        .<?php echo esc_attr($unique_id); ?>.layout-tabs .woocommerce-MyAccount-navigation::-webkit-scrollbar { display: none; }
        .<?php echo esc_attr($unique_id); ?>.layout-tabs .woocommerce-MyAccount-navigation ul { display: flex; gap: 10px; }
        .<?php echo esc_attr($unique_id); ?>.layout-tabs .woocommerce-MyAccount-navigation a { padding: 15px 24px; font-weight: bold; opacity: 0.6; white-space: nowrap; border-radius: var(--ma-rad) var(--ma-rad) 0 0; transition: all 0.2s; }
        .<?php echo esc_attr($unique_id); ?>.layout-tabs .woocommerce-MyAccount-navigation li.is-active a { opacity: 1; color: var(--ma-acc); border-bottom: 3px solid var(--ma-acc); margin-bottom: -2px; }

        /* --- 3. Cards Layout --- */
        .<?php echo esc_attr($unique_id); ?>.layout-cards .woocommerce { display: flex; flex-direction: column; gap: 30px; }
        .<?php echo esc_attr($unique_id); ?>.layout-cards.is-dashboard .woocommerce-MyAccount-content { display: none; } /* Hide empty content on main dashboard */
        .<?php echo esc_attr($unique_id); ?>.layout-cards.is-dashboard .woocommerce-MyAccount-navigation { width: 100%; }
        .<?php echo esc_attr($unique_id); ?>.layout-cards.is-dashboard .woocommerce-MyAccount-navigation ul { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
        .<?php echo esc_attr($unique_id); ?>.layout-cards.is-dashboard .woocommerce-MyAccount-navigation a { background: var(--ma-con-bg); border: 1px solid var(--ma-border); padding: 35px 25px; border-radius: var(--ma-rad); font-weight: bold; font-size: 18px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 15px rgba(0,0,0,0.02); transition: all 0.2s; }
        .<?php echo esc_attr($unique_id); ?>.layout-cards.is-dashboard .woocommerce-MyAccount-navigation a::after { content: '→'; opacity: 0.3; transition: opacity 0.2s; }
        .<?php echo esc_attr($unique_id); ?>.layout-cards.is-dashboard .woocommerce-MyAccount-navigation a:hover { border-color: var(--ma-acc); color: var(--ma-acc); transform: translateY(-3px); }
        .<?php echo esc_attr($unique_id); ?>.layout-cards.is-dashboard .woocommerce-MyAccount-navigation a:hover::after { opacity: 1; }
        
        /* When NOT on dashboard in cards mode, fallback to Tabs style navigation */
        .<?php echo esc_attr($unique_id); ?>.layout-cards:not(.is-dashboard) .woocommerce-MyAccount-navigation { border-bottom: 2px solid var(--ma-border); margin-bottom: 30px; }
        .<?php echo esc_attr($unique_id); ?>.layout-cards:not(.is-dashboard) .woocommerce-MyAccount-navigation ul { display: flex; gap: 10px; overflow-x: auto; }
        .<?php echo esc_attr($unique_id); ?>.layout-cards:not(.is-dashboard) .woocommerce-MyAccount-navigation a { padding: 15px 24px; font-weight: bold; opacity: 0.6; white-space: nowrap; border-radius: var(--ma-rad) var(--ma-rad) 0 0; }
        .<?php echo esc_attr($unique_id); ?>.layout-cards:not(.is-dashboard) .woocommerce-MyAccount-navigation li.is-active a { opacity: 1; color: var(--ma-acc); border-bottom: 3px solid var(--ma-acc); margin-bottom: -2px; }

        /* --- Internal Table & Form Styling --- */
        .<?php echo esc_attr($unique_id); ?> .woocommerce-MyAccount-content h3 { font-size: 24px; font-weight: 800; margin: 0 0 25px 0; }
        .<?php echo esc_attr($unique_id); ?> table.shop_table { width: 100%; border-collapse: collapse; }
        .<?php echo esc_attr($unique_id); ?> table.shop_table th { text-align: left; padding-bottom: 15px; border-bottom: 1px solid var(--ma-border); opacity: 0.6; text-transform: uppercase; font-size: 12px; }
        .<?php echo esc_attr($unique_id); ?> table.shop_table td { padding: 20px 0; border-bottom: 1px solid var(--ma-border); vertical-align: middle; }
        .<?php echo esc_attr($unique_id); ?> table.shop_table .button { padding: 10px 20px; background: transparent; border: 1px solid var(--ma-acc); color: var(--ma-acc); border-radius: 4px; font-weight: bold; font-size: 13px; text-decoration: none; }
        .<?php echo esc_attr($unique_id); ?> table.shop_table .button:hover { background: var(--ma-acc); color: #fff; }
        
        .<?php echo esc_attr($unique_id); ?> .order-status { padding: 4px 10px; border-radius: 50px; font-size: 12px; font-weight: bold; }
        .<?php echo esc_attr($unique_id); ?> .status-completed { background: #d1fae5; color: #059669; }
        .<?php echo esc_attr($unique_id); ?> .status-processing { background: #dbeafe; color: #2563eb; }
        .<?php echo esc_attr($unique_id); ?> .status-on-hold { background: #fef3c7; color: #d97706; }

        .<?php echo esc_attr($unique_id); ?> .woocommerce-Address-title h3 { font-size: 20px; float: left; margin: 0; }
        .<?php echo esc_attr($unique_id); ?> .woocommerce-Address-title a { float: right; font-weight: bold; color: var(--ma-acc); text-decoration: none; }
        .<?php echo esc_attr($unique_id); ?> address { clear: both; padding-top: 20px; font-style: normal; line-height: 1.6; opacity: 0.8; }
        
        /* Dashboard Custom Injection UI */
        .<?php echo esc_attr($unique_id); ?> .mc-acct-welcome { font-size: 32px; font-weight: 900; margin: 0 0 40px 0; }
    </style>

    <?php 
    // Output Native WC Engine
    echo do_shortcode('[woocommerce_my_account]'); 
    ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const wrapper = document.querySelector('.<?php echo esc_attr($unique_id); ?>');
            if (!wrapper) return;

            // 1. Dashboard flag for the card layout (welcome + recent orders are rendered on the server).
            if (wrapper.querySelector('.mc-dashboard-extra')) {
                wrapper.classList.add('is-dashboard');
            }

            // 2. Add visual 'is-active' classes to Native WC Nav
            const currentUrl = window.location.href;
            const navItems = wrapper.querySelectorAll('.woocommerce-MyAccount-navigation li');
            
            navItems.forEach(li => {
                const link = li.querySelector('a');
                if (link && link.href === currentUrl) {
                    li.classList.add('is-active');
                }
            });
        });
    </script>
</div>