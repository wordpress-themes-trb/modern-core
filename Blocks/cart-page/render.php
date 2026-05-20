<?php
if (!class_exists('WooCommerce')) return;

$layout = $attributes['layoutMode'] ?? 'two-column';
$show_headers = $attributes['showHeaders'] ?? true;
$auto_update = $attributes['autoUpdate'] ?? true;
$summary_bg = $attributes['summaryBg'] ?? '#f8fafc';
$summary_rad = $attributes['summaryRadius'] ?? 12;
$accent = $attributes['accentColor'] ?? '#3b82f6';
$text_c = $attributes['textColor'] ?? '#0f172a';
$border_c = $attributes['borderColor'] ?? '#e2e8f0';

$unique_id = uniqid('mc-cart-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "modern-core-cart {$unique_id} layout-{$layout}"]);

// We output empty state purely via WooCommerce defaults to maintain functionality
if (WC()->cart->is_empty()) {
    echo '<div ' . $wrapper_attributes . '>';
    wc_get_template('cart/cart-empty.php');
    echo '</div>';
    return;
}
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            --cart-bg: <?php echo esc_attr($summary_bg); ?>;
            --cart-acc: <?php echo esc_attr($accent); ?>;
            --cart-txt: <?php echo esc_attr($text_c); ?>;
            --cart-border: <?php echo esc_attr($border_c); ?>;
            --cart-rad: <?php echo esc_attr($summary_rad); ?>px;
            
            width: 100%; padding: 60px 0; color: var(--cart-txt);
        }

        /* The Grid Overlay over WooCommerce Native Elements */
        .<?php echo esc_attr($unique_id); ?> .woocommerce {
            display: grid; gap: 60px; max-width: 1400px; margin: 0 auto; padding: 0 5%; align-items: start;
        }

        .<?php echo esc_attr($unique_id); ?>.layout-two-column .woocommerce { grid-template-columns: 2fr 1fr; }
        .<?php echo esc_attr($unique_id); ?>.layout-stacked .woocommerce { grid-template-columns: 1fr; max-width: 900px; }

        @media (max-width: 900px) {
            .<?php echo esc_attr($unique_id); ?> .woocommerce { grid-template-columns: 1fr; gap: 40px; }
        }

        /* --- LEFT: Table Overrides --- */
        .<?php echo esc_attr($unique_id); ?> .woocommerce-cart-form { width: 100%; }
        .<?php echo esc_attr($unique_id); ?> table.shop_table { width: 100%; border-collapse: collapse; border: none; }
        
        <?php if (!$show_headers) : ?>
            .<?php echo esc_attr($unique_id); ?> table.shop_table thead { display: none; }
        <?php else : ?>
            .<?php echo esc_attr($unique_id); ?> table.shop_table thead th { border-bottom: 1px solid var(--cart-border); padding-bottom: 15px; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; color: var(--cart-txt); opacity: 0.6; }
        <?php endif; ?>

        .<?php echo esc_attr($unique_id); ?> table.shop_table tbody td { border-bottom: 1px solid var(--cart-border); padding: 25px 0; vertical-align: middle; }
        
        .<?php echo esc_attr($unique_id); ?> .product-remove a.remove { color: var(--cart-txt); font-size: 24px; text-decoration: none; font-weight: normal; opacity: 0.5; transition: opacity 0.2s; }
        .<?php echo esc_attr($unique_id); ?> .product-remove a.remove:hover { opacity: 1; background: transparent; color: #ef4444; }

        .<?php echo esc_attr($unique_id); ?> .product-thumbnail img { width: 80px; height: auto; border-radius: 4px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .<?php echo esc_attr($unique_id); ?> .product-name a { color: var(--cart-txt); font-weight: bold; text-decoration: none; font-size: 16px; }
        .<?php echo esc_attr($unique_id); ?> .product-price, .<?php echo esc_attr($unique_id); ?> .product-subtotal { font-weight: bold; }

        /* Quantity Input */
        .<?php echo esc_attr($unique_id); ?> .quantity input.qty { border: 1px solid var(--cart-border); padding: 10px 15px; border-radius: 4px; width: 70px; text-align: center; font-size: 16px; }

        /* Coupon Row */
        .<?php echo esc_attr($unique_id); ?> .actions { padding-top: 30px !important; border: none !important; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; }
        .<?php echo esc_attr($unique_id); ?> .coupon { display: flex; gap: 10px; }
        .<?php echo esc_attr($unique_id); ?> .coupon input.input-text { padding: 14px 20px; border: 1px solid var(--cart-border); border-radius: 4px; min-width: 200px; outline: none; }
        .<?php echo esc_attr($unique_id); ?> .coupon input.input-text:focus { border-color: var(--cart-acc); }
        .<?php echo esc_attr($unique_id); ?> .coupon button.button { padding: 14px 24px; background: transparent; border: 1px solid var(--cart-acc); color: var(--cart-acc); border-radius: 4px; font-weight: bold; cursor: pointer; transition: all 0.2s; }
        .<?php echo esc_attr($unique_id); ?> .coupon button.button:hover { background: var(--cart-acc); color: #fff; }

        /* Auto-update Hack: Hide the Update button if enabled */
        <?php if ($auto_update) : ?>
            .<?php echo esc_attr($unique_id); ?> button[name="update_cart"] { display: none !important; }
        <?php else : ?>
            .<?php echo esc_attr($unique_id); ?> button[name="update_cart"] { padding: 14px 24px; background: #e2e8f0; color: #0f172a; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; }
        <?php endif; ?>

        /* --- RIGHT: Summary Card Overrides --- */
        .<?php echo esc_attr($unique_id); ?> .cart-collaterals { width: 100%; }
        .<?php echo esc_attr($unique_id); ?> .cart_totals { background: var(--cart-bg); padding: 40px; border-radius: var(--cart-rad); border: 1px solid var(--cart-border); width: 100%; float: none; }
        .<?php echo esc_attr($unique_id); ?> .cart_totals h2 { font-size: 24px; font-weight: 800; margin: 0 0 25px 0; border: none; padding: 0; }
        
        .<?php echo esc_attr($unique_id); ?> .cart_totals table { width: 100%; border-collapse: collapse; }
        .<?php echo esc_attr($unique_id); ?> .cart_totals th, .<?php echo esc_attr($unique_id); ?> .cart_totals td { padding: 15px 0; border-bottom: 1px solid var(--cart-border); }
        .<?php echo esc_attr($unique_id); ?> .cart_totals th { text-align: left; font-weight: normal; opacity: 0.8; }
        .<?php echo esc_attr($unique_id); ?> .cart_totals td { text-align: right; font-weight: bold; }
        
        .<?php echo esc_attr($unique_id); ?> .order-total th { font-size: 20px; font-weight: bold; color: var(--cart-txt); opacity: 1; border-bottom: none; padding-top: 25px; }
        .<?php echo esc_attr($unique_id); ?> .order-total td { font-size: 24px; border-bottom: none; padding-top: 25px; }

        /* Checkout Button */
        .<?php echo esc_attr($unique_id); ?> .wc-proceed-to-checkout { padding: 25px 0 0 0; }
        .<?php echo esc_attr($unique_id); ?> .checkout-button { display: block; width: 100%; padding: 20px; background: var(--cart-acc); color: #fff; text-align: center; border-radius: 6px; font-weight: bold; font-size: 16px; text-decoration: none; transition: opacity 0.2s; }
        .<?php echo esc_attr($unique_id); ?> .checkout-button:hover { opacity: 0.9; }

        /* Cross-sells */
        .<?php echo esc_attr($unique_id); ?> .cross-sells { grid-column: 1 / -1; margin-top: 40px; border-top: 1px solid var(--cart-border); padding-top: 40px; }
        .<?php echo esc_attr($unique_id); ?> .cross-sells h2 { font-size: 24px; font-weight: bold; margin-bottom: 30px; }
        .<?php echo esc_attr($unique_id); ?> .cross-sells ul.products { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 30px; list-style: none; padding: 0; margin: 0; }
    </style>

    <?php 
    // Execute Native WooCommerce Shortcode Logic within our grid wrapper
    echo do_shortcode('[woocommerce_cart]'); 
    ?>

    <?php if ($auto_update) : ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof jQuery === 'undefined') return;

            // Debounce function to prevent hammering the server if user clicks fast
            let timeout;
            jQuery(document.body).on('change keyup mouseup', '.<?php echo esc_attr($unique_id); ?> .qty', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    jQuery('.<?php echo esc_attr($unique_id); ?> [name="update_cart"]').prop('disabled', false).trigger('click');
                }, 500); // 500ms delay after user finishes clicking
            });
        });
    </script>
    <?php endif; ?>
</div>