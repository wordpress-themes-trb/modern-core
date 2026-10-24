<?php
$width_mode = $attributes['widthMode'] ?? 'contained';
$show_search = $attributes['showSearch'] ?? true;
$show_account = $attributes['showAccount'] ?? true;
$show_cart = $attributes['showCart'] ?? true;
$show_badge = $attributes['showCartBadge'] ?? true;
$size = $attributes['iconSize'] ?? 24;
$spacing = $attributes['iconSpacing'] ?? 20;
$color = $attributes['iconColor'] ?? '#0f172a';

$wrapper_attributes = get_block_wrapper_attributes([
    'class' => 'mc-block width-' . esc_attr($width_mode),
    'style' => "display: flex; align-items: center; gap: {$spacing}px; color: {$color};"
]);

// WC()->cart is only set up on front-end requests (not in the Site Editor or REST previews).
$cart_count  = ( function_exists( 'WC' ) && WC()->cart ) ? (int) WC()->cart->get_cart_contents_count() : 0;
$account_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url();
$search_id   = wp_unique_id( 'mc-hsearch-' );
?>

<div <?php echo $wrapper_attributes; ?>>
    <?php if ($show_search) : ?>
        <button type="button" class="modern-core-search-trigger mc-icon-btn" aria-expanded="false" aria-controls="<?php echo esc_attr( $search_id ); ?>" aria-label="<?php esc_attr_e( 'Search', 'modern-core' ); ?>" style="color: inherit;">
            <svg width="<?php echo esc_attr($size); ?>" height="<?php echo esc_attr($size); ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
        </button>
        <form id="<?php echo esc_attr( $search_id ); ?>" class="mc-header-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" hidden>
            <label class="screen-reader-text" for="<?php echo esc_attr( $search_id ); ?>-input"><?php esc_html_e( 'Search products', 'modern-core' ); ?></label>
            <input id="<?php echo esc_attr( $search_id ); ?>-input" type="search" name="s" placeholder="<?php esc_attr_e( 'Search products…', 'modern-core' ); ?>" autocomplete="off">
            <?php if ( class_exists( 'WooCommerce' ) ) : ?><input type="hidden" name="post_type" value="product"><?php endif; ?>
        </form>
    <?php endif; ?>

    <?php
    $wishlist_url = ( $attributes['showWishlist'] ?? true ) ? modern_core_page_url( 'wishlist' ) : '';
    if ( $wishlist_url ) :
        ?>
        <a href="<?php echo esc_url( $wishlist_url ); ?>" class="mc-header-wishlist" aria-label="<?php esc_attr_e( 'Wishlist', 'modern-core' ); ?>" style="color: inherit; display: flex; position: relative;">
            <svg width="<?php echo esc_attr($size); ?>" height="<?php echo esc_attr($size); ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1.1L12 21.3l7.8-7.8 1-1.1a5.5 5.5 0 0 0 0-7.8z"></path></svg>
            <span class="modern-core-cart-badge mc-wishlist-badge" data-mc-wishlist-count="0" hidden style="position: absolute; top: -5px; right: -8px; background-color: <?php echo esc_attr($color); ?>; color: #ffffff; font-size: <?php echo (int) max( 10, $size * 0.4 ); ?>px; padding: 2px 6px; border-radius: 12px; font-weight: bold; line-height: 1;">0</span>
        </a>
    <?php endif; ?>

    <?php if ($show_account) : ?>
        <a href="<?php echo esc_url( $account_url ); ?>" aria-label="<?php esc_attr_e( 'My account', 'modern-core' ); ?>" style="color: inherit; display: flex;">
            <svg width="<?php echo esc_attr($size); ?>" height="<?php echo esc_attr($size); ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle>
            </svg>
        </a>
    <?php endif; ?>

    <?php if ($show_cart) : ?>
        <a href="<?php echo function_exists('wc_get_cart_url') ? esc_url(wc_get_cart_url()) : '#cart'; ?>" class="modern-core-cart-trigger mc-trigger-cart" aria-label="<?php esc_attr_e( 'Cart', 'modern-core' ); ?>" style="position: relative; display: flex; color: inherit; text-decoration: none;">
            <svg width="<?php echo esc_attr($size); ?>" height="<?php echo esc_attr($size); ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path>
            </svg>
            <?php if ($show_badge) : ?>
                <span class="modern-core-cart-badge" style="position: absolute; top: -5px; right: -8px; background-color: <?php echo esc_attr($color); ?>; color: #ffffff; font-size: <?php echo max(10, $size * 0.4); ?>px; padding: 2px 6px; border-radius: 12px; font-weight: bold; line-height: 1;">
                    <span data-mc-cart-count="<?php echo esc_attr( (string) $cart_count ); ?>"><?php echo esc_html( (string) $cart_count ); ?></span>
                </span>
            <?php endif; ?>
        </a>
    <?php endif; ?>
</div>