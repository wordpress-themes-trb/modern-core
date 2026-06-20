<?php
$width_mode = $attributes['widthMode'] ?? 'contained';
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
$wrapper_attributes = get_block_wrapper_attributes([
    'class' => "mc-block width-" . esc_attr($width_mode) . " modern-core-cart {$unique_id} layout-{$layout}"
]);

// Empty Cart State
if (!empty(WC()->cart) && WC()->cart->is_empty()) {
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
            
            width: 100%; 
            padding: 20px 0 80px 0; 
            color: var(--cart-txt);
        }

        /* Width modes */
        .<?php echo esc_attr($unique_id); ?>.width-contained .woocommerce {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 5%;
        }
        .<?php echo esc_attr($unique_id); ?>.width-wide .woocommerce {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 5%;
        }
        .<?php echo esc_attr($unique_id); ?>.width-fullwidth .woocommerce {
            max-width: 100%;
            padding: 0 5%;
        }

        /* 1. THE NUKE: Destroy Woo's invisible grid-breakers and floats */
        .<?php echo esc_attr($unique_id); ?> .woocommerce::before,
        .<?php echo esc_attr($unique_id); ?> .woocommerce::after {
            display: none !important;
            content: none !important;
        }
        .<?php echo esc_attr($unique_id); ?> .woocommerce .cart-collaterals,
        .<?php echo esc_attr($unique_id); ?> .woocommerce .cart-collaterals .cart_totals {
            float: none !important;
            width: 100% !important;
        }

        /* 2. THE GRID ENGINE */
        .<?php echo esc_attr($unique_id); ?> .woocommerce {
            display: grid !important; 
            grid-template-columns: 2fr 1fr !important;
            gap: 60px !important; 
            align-items: start !important;
        }

        /* Hide empty notices so they don't consume a grid row */
        .<?php echo esc_attr($unique_id); ?> .woocommerce-notices-wrapper:empty,
        .<?php echo esc_attr($unique_id); ?> .woocommerce-NoticeGroup:empty {
            display: none !important;
        }
        .<?php echo esc_attr($unique_id); ?> .woocommerce-notices-wrapper {
            grid-column: 1 / -1 !important;
        }

        /* 3. SHOPIFY-STYLE LEFT COLUMN (TABLE) */
        .<?php echo esc_attr($unique_id); ?> .woocommerce-cart-form { 
            grid-column: 1 / 2 !important; 
            min-width: 0;
        }
        
        .<?php echo esc_attr($unique_id); ?> table.shop_table { 
            border: none !important; 
            border-radius: 0 !important; 
            margin-bottom: 0 !important;
            table-layout: fixed !important;
            width: 100% !important;
            border-collapse: collapse !important;
        }
        
        .<?php echo esc_attr($unique_id); ?> table.shop_table thead { 
            display: <?php echo $show_headers ? 'table-header-group' : 'none'; ?> !important;
        }
        
        .<?php echo esc_attr($unique_id); ?> table.shop_table th { 
            border-bottom: 1px solid var(--cart-border) !important; 
            border-top: none !important;
            border-left: none !important;
            border-right: none !important;
            padding: 0 0 15px 0 !important; 
            font-size: 12px !important; 
            text-transform: uppercase !important; 
            letter-spacing: 1px !important; 
            color: #6b7280 !important; 
            font-weight: 600 !important;
        }

        .<?php echo esc_attr($unique_id); ?> table.shop_table td { 
            border-bottom: 1px solid var(--cart-border) !important; 
            border-top: none !important;
            border-left: none !important;
            border-right: none !important;
            padding: 30px 0 !important; 
            vertical-align: middle !important; 
        }

        /* ===== DESKTOP COLUMN WIDTHS ===== */
        .<?php echo esc_attr($unique_id); ?> table.shop_table th.product-remove,
        .<?php echo esc_attr($unique_id); ?> table.shop_table td.product-remove {
            width: 40px !important;
            padding: 30px 0 !important;
            text-align: center !important;
        }
        .<?php echo esc_attr($unique_id); ?> table.shop_table th.product-name,
        .<?php echo esc_attr($unique_id); ?> table.shop_table td.product-name {
            width: auto !important;
            min-width: 0 !important;
        }
        .<?php echo esc_attr($unique_id); ?> table.shop_table th.product-price,
        .<?php echo esc_attr($unique_id); ?> table.shop_table td.product-price {
            width: 15% !important;
            text-align: left !important;
        }
        .<?php echo esc_attr($unique_id); ?> table.shop_table th.product-quantity,
        .<?php echo esc_attr($unique_id); ?> table.shop_table td.product-quantity {
            width: 20% !important;
            text-align: center !important;
        }
        .<?php echo esc_attr($unique_id); ?> table.shop_table th.product-subtotal,
        .<?php echo esc_attr($unique_id); ?> table.shop_table td.product-subtotal {
            width: 20% !important;
            text-align: right !important;
        }

        /* ===== DESKTOP REMOVE BUTTON ===== */
        .<?php echo esc_attr($unique_id); ?> .product-remove a.remove {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 32px !important;
            height: 32px !important;
            color: #9ca3af !important; 
            font-size: 24px !important; 
            font-weight: 300 !important; 
            border: none !important;
            background: transparent !important;
            margin: 0 auto !important;
            line-height: 1 !important;
            text-decoration: none !important;
            transition: color 0.2s !important;
        }
        .<?php echo esc_attr($unique_id); ?> .product-remove a.remove:hover { 
            color: #ef4444 !important; 
            background: transparent !important;
        }

        /* Product Thumb & Name */
        .<?php echo esc_attr($unique_id); ?> .product-thumbnail { 
            width: 90px !important;
            padding: 0 !important;
        }
        .<?php echo esc_attr($unique_id); ?> .product-thumbnail img { 
            width: 90px !important; 
            height: auto !important; 
            border-radius: 8px !important; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.06) !important; 
            display: block !important;
        }
        .<?php echo esc_attr($unique_id); ?> .product-name { 
            padding-left: 15px !important;
        }
        .<?php echo esc_attr($unique_id); ?> .product-name a { 
            color: var(--cart-txt) !important; 
            font-weight: 600 !important; 
            font-size: 16px !important; 
            text-decoration: none !important; 
        }
        .<?php echo esc_attr($unique_id); ?> .product-name .variation {
            font-size: 13px !important;
            opacity: 0.6 !important;
            margin-top: 4px !important;
        }
        
        /* Quantity Input */
        .<?php echo esc_attr($unique_id); ?> .quantity input.qty { 
            border: 1px solid var(--cart-border) !important; 
            padding: 12px 15px !important; 
            border-radius: 6px !important; 
            width: 80px !important; 
            text-align: center !important; 
            font-size: 15px !important; 
            font-weight: 600 !important;
            color: var(--cart-txt) !important;
            background: #fff !important;
            -moz-appearance: textfield !important;
        }
        .<?php echo esc_attr($unique_id); ?> .quantity input.qty::-webkit-outer-spin-button,
        .<?php echo esc_attr($unique_id); ?> .quantity input.qty::-webkit-inner-spin-button {
            -webkit-appearance: none !important;
            margin: 0 !important;
        }

        /* Coupon Row */
        .<?php echo esc_attr($unique_id); ?> table.shop_table td.actions { 
            padding: 30px 0 !important; 
            border: none !important; 
        }
        .<?php echo esc_attr($unique_id); ?> .coupon { 
            display: flex !important; 
            gap: 12px !important; 
            width: 100%; 
            max-width: 400px; 
        }
        .<?php echo esc_attr($unique_id); ?> .coupon input.input-text { 
            padding: 0 20px !important; 
            height: 48px !important;
            border: 1px solid var(--cart-border) !important; 
            border-radius: 6px !important; 
            width: 100% !important;
            flex-grow: 1 !important;
            font-size: 15px !important;
            background: #fff !important;
        }
        .<?php echo esc_attr($unique_id); ?> .coupon button.button { 
            height: 48px !important;
            padding: 0 24px !important; 
            background: #f3f4f6 !important; 
            color: #374151 !important; 
            border: none !important;
            border-radius: 6px !important; 
            font-weight: 600 !important; 
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            font-size: 13px !important;
            transition: all 0.2s !important; 
            white-space: nowrap !important;
        }
        .<?php echo esc_attr($unique_id); ?> .coupon button.button:hover { 
            background: #e5e7eb !important; 
        }

        /* Auto-update Hack */
        <?php if ($auto_update) : ?>
            .<?php echo esc_attr($unique_id); ?> button[name="update_cart"] { display: none !important; }
        <?php else : ?>
            .<?php echo esc_attr($unique_id); ?> button[name="update_cart"] { 
                float: right !important; 
                height: 48px !important; 
                padding: 0 24px !important; 
                background: var(--cart-acc) !important; 
                color: #fff !important; 
                border-radius: 6px !important; 
                border: none !important; 
                font-weight: 600 !important; 
                text-transform: uppercase !important; 
                font-size: 13px !important; 
                letter-spacing: 0.5px !important; 
            }
        <?php endif; ?>

        /* 4. SHOPIFY-STYLE RIGHT COLUMN (ORDER SUMMARY) */
        .<?php echo esc_attr($unique_id); ?> .cart-collaterals { 
            grid-column: 2 / 3 !important; 
            min-width: 0;
        }
        
        .<?php echo esc_attr($unique_id); ?> .cart_totals { 
            background: var(--cart-bg) !important; 
            padding: 40px !important; 
            border-radius: var(--cart-rad) !important; 
            box-shadow: 0 4px 24px rgba(0,0,0,0.04) !important; 
            border: 1px solid var(--cart-border) !important;
        }
        .<?php echo esc_attr($unique_id); ?> .cart_totals h2 { 
            font-size: 20px !important; 
            font-weight: 700 !important; 
            margin: 0 0 25px 0 !important; 
            padding: 0 !important;
            border: none !important;
            color: var(--cart-txt) !important;
        }
        
        .<?php echo esc_attr($unique_id); ?> .cart_totals table { 
            width: 100% !important; 
            border: none !important; 
            margin: 0 !important; 
            table-layout: fixed !important;
        }
        .<?php echo esc_attr($unique_id); ?> .cart_totals th, 
        .<?php echo esc_attr($unique_id); ?> .cart_totals td { 
            padding: 15px 0 !important; 
            border-bottom: 1px solid var(--cart-border) !important; 
            border-top: none !important;
            border-left: none !important;
            border-right: none !important;
            background: transparent !important;
        }
        .<?php echo esc_attr($unique_id); ?> .cart_totals th { 
            text-align: left !important; 
            font-weight: 500 !important; 
            color: #4b5563 !important; 
            width: 40% !important;
        }
        .<?php echo esc_attr($unique_id); ?> .cart_totals td { 
            text-align: right !important; 
            font-weight: 600 !important; 
            color: var(--cart-txt) !important; 
            width: 60% !important;
        }
        
        /* Total Row */
        .<?php echo esc_attr($unique_id); ?> .order-total th,
        .<?php echo esc_attr($unique_id); ?> .order-total td { 
            border-bottom: none !important; 
            padding-top: 25px !important; 
            padding-bottom: 0 !important;
        }
        .<?php echo esc_attr($unique_id); ?> .order-total th { 
            font-size: 18px !important; 
            font-weight: 700 !important; 
            color: var(--cart-txt) !important; 
        }
        .<?php echo esc_attr($unique_id); ?> .order-total td { 
            font-size: 24px !important; 
            font-weight: 800 !important; 
        }

        /* Checkout Button */
        .<?php echo esc_attr($unique_id); ?> .wc-proceed-to-checkout { 
            padding: 30px 0 0 0 !important; 
        }
        .<?php echo esc_attr($unique_id); ?> .checkout-button { 
            display: flex !important; 
            justify-content: center !important;
            align-items: center !important;
            width: 100% !important; 
            height: 60px !important;
            background: var(--cart-acc) !important; 
            color: #fff !important; 
            border-radius: 6px !important; 
            font-weight: 600 !important; 
            font-size: 16px !important; 
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
            text-decoration: none !important; 
            transition: transform 0.2s, box-shadow 0.2s !important; 
            margin: 0 !important;
            border: none !important;
        }
        .<?php echo esc_attr($unique_id); ?> .checkout-button:hover { 
            transform: translateY(-2px) !important;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
            color: #fff !important;
        }

        /* Cross-sells */
        .<?php echo esc_attr($unique_id); ?> .cross-sells { 
            grid-column: 1 / -1 !important; 
            margin-top: 60px !important; 
        }

        /* ===== MOBILE RESPONSIVE ===== */
        @media (max-width: 768px) {
            .<?php echo esc_attr($unique_id); ?> .woocommerce { 
                grid-template-columns: 1fr !important; 
                gap: 40px !important; 
            }
            .<?php echo esc_attr($unique_id); ?> .woocommerce-cart-form,
            .<?php echo esc_attr($unique_id); ?> .cart-collaterals {
                grid-column: 1 / -1 !important; 
            }
            
            /* Hide table headers */
            .<?php echo esc_attr($unique_id); ?> table.shop_table thead {
                display: none !important;
            }
            
            /* Convert table rows to grid cards */
            .<?php echo esc_attr($unique_id); ?> table.shop_table tbody tr {
                display: grid !important;
                grid-template-columns: 90px 1fr 44px;
                grid-template-rows: auto auto auto;
                grid-template-areas: 
                    "img info delete"
                    "img price delete"
                    "img qty delete";
                gap: 6px 15px;
                padding: 20px 0 !important;
                border-bottom: 1px solid var(--cart-border) !important;
                align-items: start !important;
            }
            
            /* Hide desktop remove (×) */
            .<?php echo esc_attr($unique_id); ?> table.shop_table td.product-remove {
                display: none !important;
            }
            
            /* Product thumbnail */
            .<?php echo esc_attr($unique_id); ?> table.shop_table td.product-thumbnail {
                grid-area: img;
                padding: 0 !important;
                width: 90px !important;
                border: none !important;
            }
            .<?php echo esc_attr($unique_id); ?> table.shop_table td.product-thumbnail img {
                width: 90px !important;
                height: auto !important;
                border-radius: 8px !important;
            }
            
            /* Product name */
            .<?php echo esc_attr($unique_id); ?> table.shop_table td.product-name {
                grid-area: info;
                padding: 0 !important;
                border: none !important;
                min-width: 0 !important;
            }
            .<?php echo esc_attr($unique_id); ?> table.shop_table td.product-name a {
                font-size: 15px !important;
                line-height: 1.3 !important;
                display: block !important;
                white-space: nowrap !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
            }
            
            /* Price */
            .<?php echo esc_attr($unique_id); ?> table.shop_table td.product-price {
                grid-area: price;
                padding: 0 !important;
                border: none !important;
                text-align: left !important;
                font-size: 16px !important;
                font-weight: 700 !important;
            }
            
            /* Quantity */
            .<?php echo esc_attr($unique_id); ?> table.shop_table td.product-quantity {
                grid-area: qty;
                padding: 0 !important;
                border: none !important;
                text-align: left !important;
            }
            .<?php echo esc_attr($unique_id); ?> table.shop_table td.product-quantity .quantity {
                margin: 0 !important;
            }
            .<?php echo esc_attr($unique_id); ?> table.shop_table td.product-quantity .quantity input.qty {
                width: 70px !important;
                padding: 8px 10px !important;
                font-size: 14px !important;
            }
            
            /* Subtotal - hide on mobile */
            .<?php echo esc_attr($unique_id); ?> table.shop_table td.product-subtotal {
                display: none !important;
            }
            
            /* Mobile delete icon (trash) */
            .<?php echo esc_attr($unique_id); ?> table.shop_table td.product-remove-mobile {
                display: flex !important;
                grid-area: delete;
                align-items: center;
                justify-content: center;
                padding: 0 !important;
                border: none !important;
            }
            .<?php echo esc_attr($unique_id); ?> table.shop_table td.product-remove-mobile a {
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                width: 40px !important;
                height: 40px !important;
                border-radius: 50% !important;
                background: #fef2f2 !important;
                color: #ef4444 !important;
                text-decoration: none !important;
                font-size: 0 !important;
            }
            .<?php echo esc_attr($unique_id); ?> table.shop_table td.product-remove-mobile a::before {
                content: "" !important;
                display: inline-block !important;
                width: 18px !important;
                height: 18px !important;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23ef4444' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16'/%3E%3C/svg%3E") !important;
                background-size: contain !important;
                background-repeat: no-repeat !important;
            }
            
            /* Actions row (coupon) */
            .<?php echo esc_attr($unique_id); ?> table.shop_table td.actions {
                grid-column: 1 / -1 !important;
                padding: 20px 0 !important;
            }
            .<?php echo esc_attr($unique_id); ?> .coupon {
                max-width: 100% !important;
            }
        }
        
        /* Hide mobile delete column on desktop */
        @media (min-width: 769px) {
            .<?php echo esc_attr($unique_id); ?> table.shop_table td.product-remove-mobile {
                display: none !important;
            }
        }
    </style>

    <?php echo do_shortcode('[woocommerce_cart]'); ?>

    <?php if ($auto_update) : ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof jQuery === 'undefined') return;
            let timeout;
            jQuery(document.body).on('change keyup mouseup', '.<?php echo esc_attr($unique_id); ?> .qty', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    jQuery('.<?php echo esc_attr($unique_id); ?> [name="update_cart"]').prop('disabled', false).trigger('click');
                }, 500);
            });
        });
    </script>
    <?php endif; ?>
</div>