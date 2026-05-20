<?php
$thumb_size = $attributes['thumbSize'] ?? 80;
$thumb_radius = $attributes['thumbRadius'] ?? 8;
$qty_style = $attributes['qtyStyle'] ?? 'plus-minus';
$remove_style = $attributes['removeStyle'] ?? 'text';
$title_color = $attributes['titleColor'] ?? 'inherit';
$show_shipping = $attributes['showFreeShipping'] ?? true;
$shipping_threshold = $attributes['shippingThreshold'] ?? 150;
$accent_color = $attributes['accentColor'] ?? '#3b82f6';
$checkout_bg = $attributes['checkoutBg'] ?? '#0f172a';
$checkout_text = $attributes['checkoutText'] ?? '#ffffff';

$wrapper_attributes = get_block_wrapper_attributes([
    'style' => 'display: flex; flex-direction: column; height: 100%; color: inherit;'
]);

// Simulated WooCommerce Data for the frontend preview
$current_cart_total = 90.00;
$amount_left = max(0, $shipping_threshold - $current_cart_total);
$progress_percent = min(100, ($current_cart_total / $shipping_threshold) * 100);
?>

<div <?php echo $wrapper_attributes; ?>>
    
    <?php if ($show_shipping) : ?>
        <div style="margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.1);">
            <?php if ($amount_left > 0) : ?>
                <p style="font-size: 13px; margin: 0 0 10px 0; font-weight: bold;">
                    You are <span style="color: <?php echo esc_attr($accent_color); ?>;">$<?php echo number_format($amount_left, 2); ?></span> away from Free Shipping!
                </p>
            <?php else : ?>
                <p style="font-size: 13px; margin: 0 0 10px 0; font-weight: bold; color: <?php echo esc_attr($accent_color); ?>;">
                    🎉 You have unlocked Free Shipping!
                </p>
            <?php endif; ?>
            <div style="width: 100%; height: 6px; background-color: rgba(255,255,255,0.1); border-radius: 10px; overflow: hidden;">
                <div style="width: <?php echo esc_attr($progress_percent); ?>%; height: 100%; background-color: <?php echo esc_attr($accent_color); ?>; border-radius: 10px; transition: width 0.3s ease;"></div>
            </div>
        </div>
    <?php endif; ?>

    <div class="mc-mini-cart-items" style="flex: 1; display: flex; flex-direction: column; gap: 20px; overflow-y: auto; padding-right: 10px;">
        <?php foreach ([1, 2] as $item) : ?>
            <div style="display: flex; gap: 15px; padding-bottom: 20px; border-bottom: 1px dashed rgba(255,255,255,0.1);">
                <div style="width: <?php echo esc_attr($thumb_size); ?>px; height: <?php echo esc_attr($thumb_size * 1.2); ?>px; background-color: rgba(255,255,255,0.1); border-radius: <?php echo esc_attr($thumb_radius); ?>px; background-image: url('https://via.placeholder.com/<?php echo $thumb_size; ?>x<?php echo $thumb_size * 1.2; ?>/1e293b/ffffff?text=Product'); background-size: cover;"></div>
                
                <div style="flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <h4 style="margin: 0 0 5px 0; font-size: 15px; color: <?php echo esc_attr($title_color); ?>;">Premium Cotton T-Shirt</h4>
                        <p style="margin: 0; font-size: 12px; opacity: 0.6;">Color: Black | Size: M</p>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px;">
                        <div style="display: flex; align-items: center; border: 1px solid rgba(255,255,255,0.2); border-radius: 4px; overflow: hidden;">
                            <?php if ($qty_style === 'plus-minus') : ?><button style="background: none; border: none; color: inherit; padding: 4px 10px; cursor: pointer; opacity: 0.7;">-</button><?php endif; ?>
                            <input type="number" value="1" min="1" style="width: 30px; text-align: center; background: transparent; border: none; color: inherit; font-size: 13px; outline: none; padding: 4px 0; -moz-appearance: textfield;" />
                            <?php if ($qty_style === 'plus-minus') : ?><button style="background: none; border: none; color: inherit; padding: 4px 10px; cursor: pointer; opacity: 0.7;">+</button><?php endif; ?>
                        </div>

                        <button style="background: none; border: none; color: inherit; font-size: 12px; cursor: pointer; opacity: 0.6; text-decoration: <?php echo $remove_style === 'text' ? 'underline' : 'none'; ?>;">
                            <?php echo $remove_style === 'text' ? 'Remove' : ($remove_style === 'trash' ? '🗑️' : '✕'); ?>
                        </button>
                    </div>
                </div>
                <div style="font-weight: bold; font-size: 15px;">$45.00</div>
            </div>
        <?php endforeach; ?>
    </div>

    <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.1);">
        <div style="display: flex; justify-content: space-between; margin-bottom: 20px; font-size: 18px; font-weight: bold;">
            <span>Subtotal</span>
            <span>$<?php echo number_format($current_cart_total, 2); ?></span>
        </div>
        <a href="<?php echo function_exists('wc_get_checkout_url') ? esc_url(wc_get_checkout_url()) : '#checkout'; ?>" style="display: block; text-align: center; width: 100%; padding: 16px; background-color: <?php echo esc_attr($checkout_bg); ?>; color: <?php echo esc_attr($checkout_text); ?>; border-radius: 8px; font-size: 16px; font-weight: bold; text-decoration: none; text-transform: uppercase; letter-spacing: 1px; transition: opacity 0.2s ease;">
            Proceed to Checkout
        </a>
    </div>

</div>