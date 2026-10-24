<?php
$width_mode = $attributes['widthMode'] ?? 'contained';
$bg_color = $attributes['bgColor'] ?? 'rgba(15, 23, 42, 0.85)';
$icon_color = $attributes['iconColor'] ?? '#94a3b8';
$active_color = $attributes['activeColor'] ?? '#ffffff';
$center_bg = $attributes['centerBtnBg'] ?? '#3b82f6';
$center_icon = $attributes['centerBtnIcon'] ?? '#ffffff';
$blur = $attributes['blurIntensity'] ?? 15;
$auto_hide = $attributes['autoHide'] ?? true;
$show_desktop = $attributes['showOnDesktop'] ?? false;
$cart_drawer = $attributes['cartDrawerId'] ?? 'mc-drawer-1';
$enable_product = $attributes['enableProductMode'] ?? true;
$enable_cart = $attributes['enableCartMode'] ?? true;
$checkout_behavior = $attributes['checkoutBehavior'] ?? 'hide';
$search_heading = $attributes['searchHeading'] ?? 'Trending Searches';
$search_trending = explode(',', $attributes['searchTrending'] ?? 'Sneakers, Jackets, Watches');

$unique_id = uniqid('mc-stickybar-');
$current_url = home_url(add_query_arg([], $GLOBALS['wp']->request));
$home_url = home_url('/');
$account_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : '#';
$cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : '#';
$checkout_url = function_exists('wc_get_checkout_url') ? wc_get_checkout_url() : '#';

// WooCommerce Context Checks
$is_product = function_exists('is_product') && is_product();
$is_cart = function_exists('is_cart') && is_cart();
$is_checkout = function_exists('is_checkout') && is_checkout();
$has_cart   = function_exists( 'WC' ) && WC()->cart; // No cart in the editor or REST requests.
$cart_count = $has_cart ? WC()->cart->get_cart_contents_count() : 0;
$cart_total = $has_cart ? WC()->cart->get_cart_total() : ( function_exists( 'wc_price' ) ? wc_price( 0 ) : '' );

// Early exit if checkout behavior is set to 'hide'
if ($is_checkout && $checkout_behavior === 'hide') return;
?>

<style>
    .<?php echo esc_attr($unique_id); ?> {
        position: fixed; bottom: 0; left: 0; width: 100%;
        background-color: <?php echo esc_attr($bg_color); ?>;
        backdrop-filter: blur(<?php echo esc_attr($blur); ?>px); -webkit-backdrop-filter: blur(<?php echo esc_attr($blur); ?>px);
        border-top: 1px solid rgba(255, 255, 255, 0.1); border-radius: 24px 24px 0 0;
        z-index: 9990; display: flex; justify-content: space-between; align-items: flex-end;
        padding: 15px 20px calc(15px + env(safe-area-inset-bottom)) 20px;
        transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        box-shadow: 0 -10px 40px rgba(0,0,0,0.15);
    }
    <?php if (!$show_desktop) : ?>@media (min-width: 769px) { .<?php echo esc_attr($unique_id); ?> { display: none !important; } }<?php endif; ?>
    .<?php echo esc_attr($unique_id); ?>.mc-hidden { transform: translateY(150%); }

    /* Items */
    .mc-sb-item { display: flex; flex-direction: column; align-items: center; gap: 4px; width: 20%; color: <?php echo esc_attr($icon_color); ?>; text-decoration: none; position: relative; -webkit-tap-highlight-color: transparent; }
    .mc-sb-item.is-active { color: <?php echo esc_attr($active_color); ?>; }
    .mc-sb-label { font-size: 10px; font-weight: 500; }
    .mc-sb-dot { width: 4px; height: 4px; background-color: <?php echo esc_attr($active_color); ?>; border-radius: 50%; margin-top: 2px; }

    /* Center Elevated Button */
    .mc-sb-center-btn { position: absolute; bottom: 5px; width: 56px; height: 56px; background-color: <?php echo esc_attr($center_bg); ?>; color: <?php echo esc_attr($center_icon); ?>; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 20px <?php echo esc_attr($center_bg); ?>66; cursor: pointer; transition: transform 0.2s ease; text-decoration: none; }
    .mc-sb-badge { position: absolute; top: 0; right: 0; background-color: #ef4444; color: #fff; font-size: 10px; font-weight: bold; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; border-radius: 50%; border: 2px solid <?php echo esc_attr($bg_color); ?>; transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .mc-sb-badge.pop { transform: scale(1.3); }

    /* Search Overlay */
    .mc-search-overlay { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(20px); z-index: 9999; opacity: 0; pointer-events: none; transition: opacity 0.3s ease; display: flex; flex-direction: column; padding: 40px 20px; color: #fff; }
    .mc-search-overlay.is-open { opacity: 1; pointer-events: auto; }
</style>

<div class="mc-search-overlay" id="mc-search-<?php echo esc_attr($unique_id); ?>">
    <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" style="display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 30px;">
        <label class="screen-reader-text" for="mc-search-input-<?php echo esc_attr($unique_id); ?>"><?php esc_html_e( 'Search products', 'modern-core' ); ?></label>
        <input id="mc-search-input-<?php echo esc_attr($unique_id); ?>" type="search" name="s" placeholder="<?php esc_attr_e( 'Search products…', 'modern-core' ); ?>" style="background: transparent; border: none; border-bottom: 2px solid rgba(255,255,255,0.3); border-radius: 0; color: #fff; font-size: 22px; flex: 1; min-width: 0; outline: none; padding: 0 0 10px; box-shadow: none;" />
        <?php if ( class_exists( 'WooCommerce' ) ) : ?><input type="hidden" name="post_type" value="product" /><?php endif; ?>
        <button type="button" class="mc-search-close" aria-label="<?php esc_attr_e( 'Close search', 'modern-core' ); ?>" style="background: none; border: none; color: #fff; opacity: 0.7; font-size: 24px; cursor: pointer;">&#x2715;</button>
    </form>
    <?php
    $search_terms = array_filter( array_map( 'trim', (array) $search_trending ) );
    if ( $search_terms ) :
        ?>
        <h4 style="font-size: 12px; text-transform: uppercase; letter-spacing: 1px; opacity: 0.6; margin-bottom: 15px; color: inherit;"><?php echo esc_html($search_heading); ?></h4>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <?php foreach ( $search_terms as $term ) : ?>
                <a href="<?php echo esc_url( add_query_arg( array( 's' => $term, 'post_type' => 'product' ), home_url( '/' ) ) ); ?>" style="background: rgba(255,255,255,0.12); padding: 8px 16px; border-radius: 20px; font-size: 14px; color: #fff; text-decoration: none;"><?php echo esc_html( $term ); ?></a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<div class="<?php echo esc_attr($unique_id); ?>">

    <?php if ($is_cart && $enable_cart) : ?>
        <div style="width: 100%; display: flex; justify-content: space-between; align-items: center;">
            <div style="color: <?php echo esc_attr($active_color); ?>;">
                <p style="margin: 0; font-size: 12px; opacity: 0.6;">Total</p>
                <p style="margin: 0; font-size: 20px; font-weight: bold;"><?php echo $cart_total; ?></p>
            </div>
            <a href="<?php echo esc_url($checkout_url); ?>" style="background-color: <?php echo esc_attr($center_bg); ?>; color: <?php echo esc_attr($center_icon); ?>; padding: 16px 30px; border-radius: 50px; font-weight: bold; text-decoration: none; text-transform: uppercase; letter-spacing: 1px; width: 60%; text-align: center;">Checkout</a>
        </div>
    <?php else : ?>
        <a href="<?php echo esc_url($home_url); ?>" class="mc-sb-item <?php echo trailingslashit($current_url) === trailingslashit($home_url) ? 'is-active' : ''; ?>">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg>
            <span class="mc-sb-label">Home</span>
            <?php if (trailingslashit($current_url) === trailingslashit($home_url)) echo '<div class="mc-sb-dot"></div>'; ?>
        </a>

        <button class="mc-sb-item mc-search-trigger" style="background: none; border: none; padding: 0;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            <span class="mc-sb-label">Search</span>
        </button>

        <div style="width: 20%; display: flex; justify-content: center; position: relative;">
            <?php // Opens the Mini Cart drawer if the page has one (it listens for .mc-trigger-cart), otherwise goes to the cart page. ?>
            <a href="<?php echo esc_url($cart_url); ?>" class="mc-sb-center-btn mc-trigger-cart" aria-label="<?php esc_attr_e( 'Cart', 'modern-core' ); ?>">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                <span class="mc-sb-badge modern-core-cart-badge"><span data-mc-cart-count="<?php echo (int) $cart_count; ?>"><?php echo (int) $cart_count; ?></span></span>
            </a>
        </div>

        <?php $mc_wishlist_url = modern_core_page_url( 'wishlist' ); ?>
        <a href="<?php echo esc_url( $mc_wishlist_url ? $mc_wishlist_url : $account_url ); ?>" class="mc-sb-item">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
            <span class="mc-sb-label"><?php esc_html_e( 'Saved', 'modern-core' ); ?></span>
            <span class="mc-sb-badge mc-wishlist-badge" data-mc-wishlist-count="0" hidden style="right: 18%;">0</span>
        </a>

        <a href="<?php echo esc_url($account_url); ?>" class="mc-sb-item <?php echo strpos($current_url, $account_url) !== false ? 'is-active' : ''; ?>">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            <span class="mc-sb-label">Account</span>
            <?php if (strpos($current_url, $account_url) !== false) echo '<div class="mc-sb-dot"></div>'; ?>
        </a>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const stickyBar = document.querySelector('.<?php echo esc_attr($unique_id); ?>');
        if (!stickyBar) return;

        // 1. SCROLL AUTO-HIDE
        <?php if ($auto_hide) : ?>
        let lastScrollY = window.scrollY;
        window.addEventListener('scroll', () => {
            const currentScrollY = window.scrollY;
            if (currentScrollY <= 0 || currentScrollY < lastScrollY) stickyBar.classList.remove('mc-hidden');
            else if (currentScrollY > 100) stickyBar.classList.add('mc-hidden');
            lastScrollY = currentScrollY;
        });
        <?php endif; ?>

        // 2. SEARCH OVERLAY
        const searchOverlay = document.getElementById('mc-search-<?php echo esc_attr($unique_id); ?>');
        const searchInput = searchOverlay.querySelector('input');
        document.querySelector('.<?php echo esc_attr($unique_id); ?> .mc-search-trigger')?.addEventListener('click', () => {
            searchOverlay.classList.add('is-open');
            setTimeout(() => searchInput.focus(), 100);
        });
        searchOverlay.querySelector('.mc-search-close').addEventListener('click', () => {
            searchOverlay.classList.remove('is-open');
        });

        document.addEventListener('keydown', (e) => { if (e.key === 'Escape') searchOverlay.classList.remove('is-open'); });

        // 3. CART BADGE: the number is swapped in by WooCommerce cart fragments; add a little "pop".
        if (typeof jQuery !== 'undefined') {
            jQuery(document.body).on('added_to_cart', function() {
                const badge = stickyBar.querySelector('.mc-sb-center-btn .mc-sb-badge');
                if (badge) {
                    badge.classList.add('pop');
                    setTimeout(() => badge.classList.remove('pop'), 300);
                }
            });
        }
    });
</script>