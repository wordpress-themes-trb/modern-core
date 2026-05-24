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

// Hook to completely override the default WooCommerce Dashboard
if (!function_exists('mc_custom_account_dashboard')) {
    function mc_custom_account_dashboard() {
        global $current_user;
        $name = $current_user->first_name ?: $current_user->display_name;
        // The welcome message is generated via JS mapping below to respect the block settings
        echo '<div class="mc-dashboard-injection-point" data-name="' . esc_attr($name) . '"></div>';
    }
}
// Remove standard WC dashboard content and inject ours
remove_action('woocommerce_account_dashboard', 'woocommerce_account_dashboard');
add_action('woocommerce_account_dashboard', 'mc_custom_account_dashboard');
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
        .<?php echo esc_attr($unique_id); ?> .woocommerce-MyAccount-navigation li { margin: 0; }
        .<?php echo esc_attr($unique_id); ?> .woocommerce-MyAccount-navigation a { text-decoration: none; color: inherit; display: block; }
        
        .<?php echo esc_attr($unique_id); ?> .woocommerce-MyAccount-content {
            background: var(--ma-con-bg); border: 1px solid var(--ma-border); border-radius: var(--ma-rad);
            padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); width: 100%; box-sizing: border-box;
        }

        /* --- 1. Sidebar Layout --- */
        .<?php echo esc_attr($unique_id); ?>.layout-sidebar .woocommerce { display: flex; gap: 50px; align-items: flex-start; }
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

            // 1. Detect if we are on the main dashboard endpoint
            const injectionPoint = wrapper.querySelector('.mc-dashboard-injection-point');
            if (injectionPoint) {
                wrapper.classList.add('is-dashboard');
                
                // Build Dashboard Header
                const welcomeFormat = wrapper.dataset.welcome;
                const name = injectionPoint.dataset.name;
                const showOrders = wrapper.dataset.orders === '1';
                
                const welcomeStr = welcomeFormat.replace('{name}', name);
                
                let dashboardHTML = `<h2 class="mc-acct-welcome">${welcomeStr}</h2>`;
                
                // Note: Real "Recent Orders" injection requires a custom WP_Query endpoint. 
                // For this native block structure, we rely on the user clicking the "Orders" tab/card,
                // but we output the structural welcome text to cleanly replace the WC default text.
                
                injectionPoint.innerHTML = dashboardHTML;
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