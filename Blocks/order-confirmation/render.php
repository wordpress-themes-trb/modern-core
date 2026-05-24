<?php
$width_mode = $attributes['widthMode'] ?? 'contained';
if (!class_exists('WooCommerce')) return;

global $wp;
// Attempt to get the order ID securely from the query string (the standard WC way)
$order_id = apply_filters('woocommerce_thankyou_order_id', absint($wp->query_vars['order-received'] ?? 0));
$order = $order_id ? wc_get_order($order_id) : null;

$anim = $attributes['animType'] ?? 'checkmark';
$hl = $attributes['headline'] ?? 'Thank you for your order!';
$msg = $attributes['message'] ?? 'We\'ve received your order and are getting it ready to ship.';
$show_tl = $attributes['showTimeline'] ?? true;
$show_xs = $attributes['showCrossSells'] ?? true;
$show_ref = $attributes['showReferral'] ?? true;
$ref_txt = $attributes['referralText'] ?? 'Give $10, Get $10. Share this link with a friend!';
$bg = $attributes['cardBg'] ?? '#ffffff';
$rad = $attributes['cardRadius'] ?? 12;
$acc = $attributes['accentColor'] ?? '#10b981';
$text_c = $attributes['textColor'] ?? '#0f172a';
$border_c = $attributes['borderColor'] ?? '#e2e8f0';

$unique_id = uniqid('mc-order-conf-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "mc-block width-" . esc_attr($width_mode) . " modern-core-order-conf {$unique_id}"]);

// Fallback if accessed directly without an order
if (!$order) {
    echo '<div ' . $wrapper_attributes . '><div style="text-align:center; padding: 60px;"><h3>No order found.</h3><p>This block requires a valid WooCommerce checkout sequence to display data.</p></div></div>';
    return;
}

$order_number = $order->get_order_number();
$email = $order->get_billing_email();
$items = $order->get_items();
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            --oc-bg: <?php echo esc_attr($bg); ?>;
            --oc-acc: <?php echo esc_attr($acc); ?>;
            --oc-txt: <?php echo esc_attr($text_c); ?>;
            --oc-border: <?php echo esc_attr($border_c); ?>;
            --oc-rad: <?php echo esc_attr($rad); ?>px;
            
            width: 100%; color: var(--oc-txt); padding: 60px 5%; overflow-x: hidden;
        }

        /* --- Header & Animations --- */
        .<?php echo esc_attr($unique_id); ?> .mc-oc-header { text-align: center; max-width: 600px; margin: 0 auto 50px auto; }
        .<?php echo esc_attr($unique_id); ?> .mc-oc-hl { font-size: clamp(32px, 5vw, 48px); font-weight: 900; margin: 0 0 10px 0; line-height: 1.1; }
        .<?php echo esc_attr($unique_id); ?> .mc-oc-meta { font-size: 20px; font-weight: bold; opacity: 0.6; margin-bottom: 20px; }
        .<?php echo esc_attr($unique_id); ?> .mc-oc-msg { font-size: 16px; line-height: 1.6; opacity: 0.8; }

        /* Pure CSS Animated Checkmark */
        .<?php echo esc_attr($unique_id); ?> .mc-checkmark-wrap { width: 80px; height: 80px; margin: 0 auto 30px auto; border-radius: 50%; display: block; stroke-width: 4; stroke: #fff; stroke-miterlimit: 10; margin-top: 10%; box-shadow: inset 0px 0px 0px var(--oc-acc); animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both; }
        .<?php echo esc_attr($unique_id); ?> .mc-checkmark-circle { stroke-dasharray: 166; stroke-dashoffset: 166; stroke-width: 4; stroke-miterlimit: 10; stroke: var(--oc-acc); fill: none; animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards; }
        .<?php echo esc_attr($unique_id); ?> .mc-checkmark-check { transform-origin: 50% 50%; stroke-dasharray: 48; stroke-dashoffset: 48; animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards; }
        
        @keyframes stroke { 100% { stroke-dashoffset: 0; } }
        @keyframes scale { 0%, 100% { transform: none; } 50% { transform: scale3d(1.1, 1.1, 1); } }
        @keyframes fill { 100% { box-shadow: inset 0px 0px 0px 40px var(--oc-acc); } }

        /* --- Timeline --- */
        .<?php echo esc_attr($unique_id); ?> .mc-oc-timeline { display: flex; justify-content: space-between; position: relative; max-width: 800px; margin: 0 auto 50px auto; padding: 0 20px; }
        .<?php echo esc_attr($unique_id); ?> .mc-oc-timeline::before { content: ''; position: absolute; top: 18px; left: 40px; right: 40px; height: 2px; background: var(--oc-border); z-index: 0; }
        .<?php echo esc_attr($unique_id); ?> .mc-oc-step { position: relative; z-index: 1; display: flex; flex-direction: column; align-items: center; gap: 10px; font-size: 13px; font-weight: bold; opacity: 0.5; }
        .<?php echo esc_attr($unique_id); ?> .mc-oc-step.is-active { opacity: 1; }
        .<?php echo esc_attr($unique_id); ?> .mc-oc-dot { width: 36px; height: 36px; border-radius: 50%; background: #fff; border: 2px solid var(--oc-border); display: flex; align-items: center; justify-content: center; font-size: 16px; }
        .<?php echo esc_attr($unique_id); ?> .mc-oc-step.is-active .mc-oc-dot { border-color: var(--oc-acc); background: var(--oc-acc); color: #fff; }
        
        @media (max-width: 600px) { .<?php echo esc_attr($unique_id); ?> .mc-oc-timeline { display: none; } }

        /* --- Details Card --- */
        .<?php echo esc_attr($unique_id); ?> .mc-oc-card { background: var(--oc-bg); border: 1px solid var(--oc-border); border-radius: var(--oc-rad); padding: 40px; max-width: 800px; margin: 0 auto 40px auto; box-shadow: 0 10px 40px rgba(0,0,0,0.05); }
        .<?php echo esc_attr($unique_id); ?> .mc-oc-card-title { margin: 0 0 30px 0; font-size: 20px; font-weight: 800; border-bottom: 1px solid var(--oc-border); padding-bottom: 15px; }
        
        .<?php echo esc_attr($unique_id); ?> .mc-oc-item { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--oc-border); padding: 20px 0; gap: 20px; }
        .<?php echo esc_attr($unique_id); ?> .mc-oc-item-img { width: 60px; height: 60px; border-radius: 6px; object-fit: cover; background: #f1f5f9; flex-shrink: 0; }
        .<?php echo esc_attr($unique_id); ?> .mc-oc-item-details { flex: 1; }
        .<?php echo esc_attr($unique_id); ?> .mc-oc-item-name { font-weight: bold; font-size: 15px; margin-bottom: 4px; }
        .<?php echo esc_attr($unique_id); ?> .mc-oc-item-meta { font-size: 13px; opacity: 0.6; }
        .<?php echo esc_attr($unique_id); ?> .mc-oc-item-price { font-weight: bold; font-size: 16px; }

        .<?php echo esc_attr($unique_id); ?> .mc-oc-totals { margin-top: 20px; }
        .<?php echo esc_attr($unique_id); ?> .mc-oc-total-row { display: flex; justify-content: space-between; padding: 10px 0; opacity: 0.8; }
        .<?php echo esc_attr($unique_id); ?> .mc-oc-total-final { display: flex; justify-content: space-between; padding: 20px 0 0 0; font-size: 20px; font-weight: 900; opacity: 1; border-top: 1px solid var(--oc-border); margin-top: 10px; }

        /* --- Promo/Referral --- */
        .<?php echo esc_attr($unique_id); ?> .mc-oc-promo { background: rgba(0,0,0,0.02); border: 2px dashed var(--oc-acc); border-radius: var(--oc-rad); padding: 40px; text-align: center; max-width: 800px; margin: 0 auto 50px auto; }
        .<?php echo esc_attr($unique_id); ?> .mc-oc-promo-hl { color: var(--oc-acc); font-size: 22px; font-weight: 900; margin: 0 0 20px 0; }
        .<?php echo esc_attr($unique_id); ?> .mc-oc-promo-flex { display: flex; justify-content: center; gap: 10px; flex-wrap: wrap; }
        .<?php echo esc_attr($unique_id); ?> .mc-oc-promo-input { padding: 15px; border-radius: 6px; border: 1px solid var(--oc-border); width: 100%; max-width: 300px; outline: none; background: #fff; }
        .<?php echo esc_attr($unique_id); ?> .mc-oc-promo-btn { padding: 15px 30px; background: var(--oc-acc); color: #fff; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; transition: opacity 0.2s; }
        .<?php echo esc_attr($unique_id); ?> .mc-oc-promo-btn:hover { opacity: 0.9; }

        /* Confetti Container */
        .<?php echo esc_attr($unique_id); ?> .mc-confetti-container { position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; overflow: hidden; z-index: 9999; }
        .mc-confetti-piece { position: absolute; width: 10px; height: 20px; opacity: 0; animation: mcFall linear forwards; }
        @keyframes mcFall { 0% { transform: translateY(-100%) rotate(0deg); opacity: 1; } 100% { transform: translateY(100vh) rotate(720deg); opacity: 0; } }
    </style>

    <?php if ($anim === 'confetti') : ?>
        <div class="mc-confetti-container" id="mc-confetti-<?php echo esc_attr($unique_id); ?>"></div>
    <?php endif; ?>

    <div class="mc-oc-header">
        <?php if ($anim === 'checkmark') : ?>
            <svg class="mc-checkmark-wrap" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                <circle class="mc-checkmark-circle" cx="26" cy="26" r="25" fill="none"/>
                <path class="mc-checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
            </svg>
        <?php endif; ?>

        <h1 class="mc-oc-hl"><?php echo esc_html($hl); ?></h1>
        <div class="mc-oc-meta">Order #<?php echo esc_html($order_number); ?></div>
        <p class="mc-oc-msg"><?php echo esc_html($msg); ?> <?php if ($email) echo '<br>Confirmation sent to <strong>' . esc_html($email) . '</strong>'; ?></p>
    </div>

    <?php if ($show_tl) : ?>
        <div class="mc-oc-timeline">
            <div class="mc-oc-step is-active"><div class="mc-oc-dot">✓</div> Order Confirmed</div>
            <div class="mc-oc-step"><div class="mc-oc-dot">2</div> Processing</div>
            <div class="mc-oc-step"><div class="mc-oc-dot">3</div> Shipped</div>
        </div>
    <?php endif; ?>

    <div class="mc-oc-card">
        <h3 class="mc-oc-card-title">Order Summary</h3>
        
        <?php foreach ($items as $item_id => $item) : 
            $product = $item->get_product();
            $img = $product ? wp_get_attachment_image_url($product->get_image_id(), 'thumbnail') : wc_placeholder_img_src();
            
            // Format meta data (Variations)
            $meta_data = $item->get_formatted_meta_data('_');
            $meta_string = '';
            foreach ($meta_data as $meta_id => $meta) {
                $meta_string .= wp_strip_all_tags($meta->display_key) . ': ' . wp_strip_all_tags($meta->display_value) . ' | ';
            }
            $meta_string = rtrim($meta_string, ' | ');
        ?>
            <div class="mc-oc-item">
                <img src="<?php echo esc_url($img); ?>" class="mc-oc-item-img" alt="<?php echo esc_attr($item->get_name()); ?>" />
                <div class="mc-oc-item-details">
                    <div class="mc-oc-item-name"><?php echo esc_html($item->get_name()); ?> <span style="opacity:0.5; font-weight:normal;">x <?php echo $item->get_quantity(); ?></span></div>
                    <?php if ($meta_string) echo '<div class="mc-oc-item-meta">' . esc_html($meta_string) . '</div>'; ?>
                </div>
                <div class="mc-oc-item-price"><?php echo wc_price($item->get_total()); ?></div>
            </div>
        <?php endforeach; ?>

        <div class="mc-oc-totals">
            <div class="mc-oc-total-row"><span>Subtotal</span><span><?php echo $order->get_subtotal_to_display(); ?></span></div>
            <?php if ($order->get_total_discount() > 0) : ?>
                <div class="mc-oc-total-row"><span>Discount</span><span>-<?php echo wc_price($order->get_total_discount()); ?></span></div>
            <?php endif; ?>
            <div class="mc-oc-total-row"><span>Shipping</span><span><?php echo $order->get_shipping_to_display(); ?></span></div>
            <?php if ($order->get_total_tax() > 0) : ?>
                <div class="mc-oc-total-row"><span>Tax</span><span><?php echo wc_price($order->get_total_tax()); ?></span></div>
            <?php endif; ?>
            <div class="mc-oc-total-final"><span>Total</span><span><?php echo $order->get_formatted_order_total(); ?></span></div>
        </div>
    </div>

    <?php if ($show_ref) : ?>
        <div class="mc-oc-promo">
            <h3 class="mc-oc-promo-hl"><?php echo esc_html($ref_txt); ?></h3>
            <div class="mc-oc-promo-flex">
                <input type="text" class="mc-oc-promo-input" value="<?php echo home_url('/?ref=' . wp_generate_password(8, false)); ?>" id="mc-ref-<?php echo esc_attr($unique_id); ?>" readOnly />
                <button class="mc-oc-promo-btn" onclick="let input = document.getElementById('mc-ref-<?php echo esc_attr($unique_id); ?>'); input.select(); document.execCommand('copy'); this.innerText = 'Copied!';">Copy Link</button>
            </div>
        </div>
    <?php endif; ?>

    <?php 
    // Cross-sells Integration
    if ($show_xs) {
        $cross_sells = [];
        foreach ($items as $item) {
            $product = $item->get_product();
            if ($product) {
                $cross_sells = array_merge($cross_sells, $product->get_cross_sell_ids());
            }
        }
        $cross_sells = array_unique($cross_sells);
        
        if (!empty($cross_sells)) {
            echo '<div class="mc-oc-cross-sells" style="max-width: 1200px; margin: 0 auto; text-align: center;">';
            echo '<h2 style="font-size: 28px; font-weight: 800; margin-bottom: 30px;">You might also love</h2>';
            // We use native WC shortcode wrapper to ensure styles carry over
            echo do_shortcode('[products ids="' . implode(',', array_slice($cross_sells, 0, 4)) . '" columns="4"]');
            echo '</div>';
        }
    }
    ?>

    <?php if ($anim === 'confetti') : ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('mc-confetti-<?php echo esc_attr($unique_id); ?>');
            if (!container) return;

            const colors = ['#3b82f6', '#10b981', '#ef4444', '#f59e0b', '#8b5cf6'];
            
            for (let i = 0; i < 60; i++) {
                const conf = document.createElement('div');
                conf.classList.add('mc-confetti-piece');
                
                // Random properties
                const bg = colors[Math.floor(Math.random() * colors.length)];
                const left = Math.random() * 100;
                const animDuration = Math.random() * 3 + 2;
                const delay = Math.random() * 2;
                
                conf.style.backgroundColor = bg;
                conf.style.left = left + '%';
                conf.style.animationDuration = animDuration + 's';
                conf.style.animationDelay = delay + 's';

                container.appendChild(conf);
            }
        });
    </script>
    <?php endif; ?>
</div>