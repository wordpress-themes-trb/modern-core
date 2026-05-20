<?php
$show_search = $attributes['showSearch'] ?? true;
$show_account = $attributes['showAccount'] ?? true;
$show_cart = $attributes['showCart'] ?? true;
$show_badge = $attributes['showCartBadge'] ?? true;
$size = $attributes['iconSize'] ?? 24;
$spacing = $attributes['iconSpacing'] ?? 20;
$color = $attributes['iconColor'] ?? '#0f172a';

$wrapper_attributes = get_block_wrapper_attributes([
    'style' => "display: flex; align-items: center; gap: {$spacing}px; color: {$color};"
]);

// If WooCommerce is active, we can pull the real cart count. For now, it defaults to a static '0' or '3' for frontend preview.
$cart_count = function_exists('WC') ? WC()->cart->get_cart_contents_count() : 3;
?>

<div <?php echo $wrapper_attributes; ?>>
    <?php if ($show_search) : ?>
        <a href="#search" class="modern-core-search-trigger" style="color: inherit;">
            <svg width="<?php echo esc_attr($size); ?>" height="<?php echo esc_attr($size); ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
        </a>
    <?php endif; ?>

    <?php if ($show_account) : ?>
        <a href="<?php echo esc_url(wp_login_url()); ?>" style="color: inherit;">
            <svg width="<?php echo esc_attr($size); ?>" height="<?php echo esc_attr($size); ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle>
            </svg>
        </a>
    <?php endif; ?>

    <?php if ($show_cart) : ?>
        <a href="<?php echo function_exists('wc_get_cart_url') ? esc_url(wc_get_cart_url()) : '#cart'; ?>" class="modern-core-cart-trigger" style="position: relative; display: flex; color: inherit; text-decoration: none;">
            <svg width="<?php echo esc_attr($size); ?>" height="<?php echo esc_attr($size); ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path>
            </svg>
            <?php if ($show_badge) : ?>
                <span class="modern-core-cart-badge" style="position: absolute; top: -5px; right: -8px; background-color: <?php echo esc_attr($color); ?>; color: #ffffff; font-size: <?php echo max(10, $size * 0.4); ?>px; padding: 2px 6px; border-radius: 12px; font-weight: bold; line-height: 1;">
                    <?php echo esc_html($cart_count); ?>
                </span>
            <?php endif; ?>
        </a>
    <?php endif; ?>
</div>