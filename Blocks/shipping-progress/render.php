<?php
if (!class_exists('WooCommerce')) return;

$threshold = $attributes['threshold'] ?? 100;
$msg_pend = $attributes['msgPending'] ?? 'Add {amount} more for free shipping!';
$msg_succ = $attributes['msgSuccess'] ?? '🎉 You qualify for free shipping!';
$h = $attributes['barHeight'] ?? 8;
$r = $attributes['barRadius'] ?? 4;
$track_c = $attributes['trackColor'] ?? '#e2e8f0';
$fill_c = $attributes['fillColor'] ?? '#3b82f6';
$succ_c = $attributes['successColor'] ?? '#10b981';
$text_c = $attributes['textColor'] ?? '#0f172a';
$use_grad = $attributes['useGradient'] ?? false;

$unique_id = uniqid('mc-ship-prog-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "modern-core-shipping-progress {$unique_id}"]);

// Initial Server-Side Calculation
$cart_total = WC()->cart ? WC()->cart->get_subtotal() : 0;
$currency_symbol = get_woocommerce_currency_symbol();

$is_success = $cart_total >= $threshold;
$percent = min(($cart_total / $threshold) * 100, 100);
$amount_needed = max($threshold - $cart_total, 0);

$display_msg = $is_success ? $msg_succ : str_replace('{amount}', wc_price($amount_needed), $msg_pend);
?>

<div <?php echo $wrapper_attributes; ?> 
    data-threshold="<?php echo esc_attr($threshold); ?>"
    data-pend="<?php echo esc_attr($msg_pend); ?>"
    data-succ="<?php echo esc_attr($msg_succ); ?>"
    data-sym="<?php echo esc_attr($currency_symbol); ?>">
    
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            --sp-h: <?php echo esc_attr($h); ?>px;
            --sp-r: <?php echo esc_attr($r); ?>px;
            --sp-track: <?php echo esc_attr($track_c); ?>;
            --sp-fill: <?php echo esc_attr($fill_c); ?>;
            --sp-succ: <?php echo esc_attr($succ_c); ?>;
            --sp-text: <?php echo esc_attr($text_c); ?>;
            
            width: 100%; padding: 15px 0;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-spb-text {
            font-size: 14px; font-weight: bold; margin-bottom: 12px; text-align: center;
            color: var(--sp-text); transition: color 0.3s ease;
        }
        
        .<?php echo esc_attr($unique_id); ?>.is-success .mc-spb-text {
            color: var(--sp-succ);
        }

        .<?php echo esc_attr($unique_id); ?> .mc-spb-track {
            width: 100%; height: var(--sp-h); background-color: var(--sp-track);
            border-radius: var(--sp-r); overflow: hidden;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-spb-fill {
            height: 100%; border-radius: var(--sp-r); background-color: var(--sp-fill);
            transition: width 0.6s cubic-bezier(0.165, 0.84, 0.44, 1), background-color 0.4s ease;
            <?php if ($use_grad) : ?>
                background: linear-gradient(90deg, var(--sp-fill), var(--sp-succ));
            <?php endif; ?>
        }

        .<?php echo esc_attr($unique_id); ?>.is-success .mc-spb-fill {
            background: var(--sp-succ); /* Forces solid success color at 100% regardless of gradient setting */
        }
    </style>

    <div class="mc-spb-text"><?php echo wp_kses_post($display_msg); ?></div>
    
    <div class="mc-spb-track">
        <div class="mc-spb-fill" style="width: <?php echo esc_attr($percent); ?>%;"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const wrapper = document.querySelector('.<?php echo esc_attr($unique_id); ?>');
            if (!wrapper) return;

            const textEl = wrapper.querySelector('.mc-spb-text');
            const fillEl = wrapper.querySelector('.mc-spb-fill');
            
            const threshold = parseFloat(wrapper.dataset.threshold);
            const pendTemplate = wrapper.dataset.pend;
            const succMsg = wrapper.dataset.succ;
            const sym = wrapper.dataset.sym;

            // Simple price formatter (assumes standard $X.XX format, could be expanded for complex locales)
            const formatPrice = (amount) => sym + amount.toFixed(2);

            const updateBar = (cartTotal) => {
                const isSuccess = cartTotal >= threshold;
                const percent = Math.min((cartTotal / threshold) * 100, 100);
                const needed = Math.max(threshold - cartTotal, 0);

                // Update DOM Math
                fillEl.style.width = percent + '%';
                wrapper.classList.toggle('is-success', isSuccess);

                // Update Messaging
                if (isSuccess) {
                    textEl.innerHTML = succMsg;
                } else {
                    textEl.innerHTML = pendTemplate.replace('{amount}', formatPrice(needed));
                }
            };

            // Connect to WooCommerce jQuery triggers (Cart Page & Checkout)
            if (typeof jQuery !== 'undefined') {
                jQuery(document.body).on('updated_cart_totals updated_checkout', function() {
                    // Fetch the latest cart subtotal securely from WC AJAX fragment
                    jQuery.ajax({
                        url: mc_ajax.ajaxurl || '/wp-admin/admin-ajax.php', 
                        type: 'POST',
                        data: { action: 'mc_get_cart_subtotal' },
                        success: function(response) {
                            if (response.success) updateBar(parseFloat(response.data.subtotal));
                        }
                    });
                });
            }
        });
    </script>
</div>