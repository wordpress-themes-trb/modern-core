<?php
/**
 * Cart Items block: the visitor's real cart, for use inside an Off-Canvas drawer.
 *
 * Shows a free-shipping progress bar, each line with thumbnail, variation,
 * quantity controls and remove link, then the subtotal and a checkout button.
 * Quantity and remove changes go through WooCommerce's own cart form, and the
 * whole block refreshes with WooCommerce cart fragments after AJAX add-to-cart.
 *
 * @package ModernCore
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'WC' ) ) {
	return;
}

$thumb_size    = (int) ( $attributes['thumbSize'] ?? 80 );
$thumb_radius  = (int) ( $attributes['thumbRadius'] ?? 8 );
$qty_style     = $attributes['qtyStyle'] ?? 'plus-minus';
$remove_style  = $attributes['removeStyle'] ?? 'text';
$title_color   = $attributes['titleColor'] ?? 'inherit';
$show_shipping = $attributes['showFreeShipping'] ?? true;
$threshold     = (float) ( $attributes['shippingThreshold'] ?? 150 );
$accent_color  = $attributes['accentColor'] ?? '#0f172a';
$checkout_bg   = $attributes['checkoutBg'] ?? '#0f172a';
$checkout_text = $attributes['checkoutText'] ?? '#ffffff';

$uid  = wp_unique_id( 'mc-ci-' );
$cart = WC()->cart;

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => "mc-cart-items {$uid} mc-block width-" . sanitize_html_class( $attributes['widthMode'] ?? 'contained' ),
		'id'    => $uid,
		'style' => 'display:flex;flex-direction:column;height:100%;color:inherit;',
	)
);
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by core. ?>>
	<style>
		.<?php echo esc_attr( $uid ); ?> .mc-ci-list { flex: 1; display: flex; flex-direction: column; gap: 18px; overflow-y: auto; margin: 0; padding: 0 6px 0 0; list-style: none; }
		.<?php echo esc_attr( $uid ); ?> .mc-ci-item { display: flex; gap: 14px; padding-bottom: 18px; border-bottom: 1px solid rgba(127,127,127,.2); }
		.<?php echo esc_attr( $uid ); ?> .mc-ci-thumb img { width: <?php echo (int) $thumb_size; ?>px; height: <?php echo (int) round( $thumb_size * 1.2 ); ?>px; object-fit: cover; border-radius: <?php echo (int) $thumb_radius; ?>px; display: block; }
		.<?php echo esc_attr( $uid ); ?> .mc-ci-body { flex: 1; min-width: 0; display: flex; flex-direction: column; justify-content: space-between; gap: 8px; }
		.<?php echo esc_attr( $uid ); ?> .mc-ci-title { margin: 0; font-size: 15px; font-weight: 700; color: <?php echo esc_attr( $title_color ); ?>; text-decoration: none; }
		.<?php echo esc_attr( $uid ); ?> .mc-ci-meta, .<?php echo esc_attr( $uid ); ?> .mc-ci-meta dl { margin: 2px 0 0; font-size: 12px; opacity: .65; }
		.<?php echo esc_attr( $uid ); ?> .mc-ci-meta dt, .<?php echo esc_attr( $uid ); ?> .mc-ci-meta dd, .<?php echo esc_attr( $uid ); ?> .mc-ci-meta p { display: inline; margin: 0; }
		.<?php echo esc_attr( $uid ); ?> .mc-ci-row { display: flex; justify-content: space-between; align-items: center; gap: 10px; }
		.<?php echo esc_attr( $uid ); ?> .mc-ci-qty { display: inline-flex; align-items: center; border: 1px solid rgba(127,127,127,.35); border-radius: 4px; }
		.<?php echo esc_attr( $uid ); ?> .mc-ci-qty button { background: none; border: 0; color: inherit; padding: 4px 10px; cursor: pointer; font-size: 15px; }
		.<?php echo esc_attr( $uid ); ?> .mc-ci-qty input { width: 38px; text-align: center; background: transparent; border: 0; color: inherit; font-size: 13px; padding: 4px 0; -moz-appearance: textfield; }
		.<?php echo esc_attr( $uid ); ?> .mc-ci-qty input::-webkit-inner-spin-button { -webkit-appearance: none; }
		.<?php echo esc_attr( $uid ); ?> .mc-ci-remove { background: none; border: 0; color: inherit; font-size: 12px; cursor: pointer; opacity: .65; <?php echo 'text' === $remove_style ? 'text-decoration: underline;' : ''; ?> }
		.<?php echo esc_attr( $uid ); ?> .mc-ci-price { font-weight: 700; font-size: 15px; white-space: nowrap; }
		.<?php echo esc_attr( $uid ); ?> .mc-ci-foot { margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(127,127,127,.2); }
		.<?php echo esc_attr( $uid ); ?> .mc-ci-checkout { display: block; text-align: center; padding: 16px; background: <?php echo esc_attr( $checkout_bg ); ?>; color: <?php echo esc_attr( $checkout_text ); ?>; border-radius: 8px; font-weight: 700; text-decoration: none; letter-spacing: .04em; }
		.<?php echo esc_attr( $uid ); ?>.is-loading { opacity: .5; pointer-events: none; }
	</style>

	<?php if ( $show_shipping && $threshold > 0 ) : ?>
		<?php
		$subtotal = $cart ? (float) $cart->get_displayed_subtotal() : 0.0;
		$left     = max( 0, $threshold - $subtotal );
		$percent  = min( 100, $subtotal / $threshold * 100 );
		?>
		<div class="mc-ci-shipping" style="margin-bottom:22px;">
			<p style="font-size:13px;margin:0 0 10px;font-weight:700;">
				<?php
				if ( $left > 0 ) {
					/* translators: %s: amount left to spend */
					printf( esc_html__( 'You are %s away from free shipping.', 'modern-core' ), '<span style="color:' . esc_attr( $accent_color ) . '">' . wp_kses_post( wc_price( $left ) ) . '</span>' );
				} else {
					esc_html_e( 'You have unlocked free shipping!', 'modern-core' );
				}
				?>
			</p>
			<div style="height:6px;background:rgba(127,127,127,.2);border-radius:10px;overflow:hidden;" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?php echo (int) $percent; ?>">
				<div style="width:<?php echo esc_attr( (string) $percent ); ?>%;height:100%;background:<?php echo esc_attr( $accent_color ); ?>;transition:width .3s ease;"></div>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( ! $cart || $cart->is_empty() ) : ?>
		<p class="mc-ci-empty" style="flex:1;opacity:.7;"><?php esc_html_e( 'Your cart is empty.', 'modern-core' ); ?></p>
	<?php else : ?>
		<ul class="mc-ci-list">
			<?php
			foreach ( $cart->get_cart() as $key => $item ) :
				$product = $item['data'];
				if ( ! $product || ! $product->exists() || $item['quantity'] <= 0 ) {
					continue;
				}
				$link = $product->is_visible() ? $product->get_permalink( $item ) : '';
				$max  = $product->get_max_purchase_quantity();
				?>
				<li class="mc-ci-item" data-key="<?php echo esc_attr( $key ); ?>">
					<div class="mc-ci-thumb"><?php echo $product->get_image( 'woocommerce_thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core image markup. ?></div>
					<div class="mc-ci-body">
						<div>
							<?php if ( $link ) : ?>
								<a class="mc-ci-title" href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
							<?php else : ?>
								<span class="mc-ci-title"><?php echo esc_html( $product->get_name() ); ?></span>
							<?php endif; ?>
							<div class="mc-ci-meta"><?php echo wc_get_formatted_cart_item_data( $item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce escapes this. ?></div>
						</div>
						<div class="mc-ci-row">
							<div class="mc-ci-qty">
								<?php if ( 'plus-minus' === $qty_style ) : ?>
									<button type="button" data-step="-1" aria-label="<?php esc_attr_e( 'Decrease quantity', 'modern-core' ); ?>">&minus;</button>
								<?php endif; ?>
								<label class="screen-reader-text" for="<?php echo esc_attr( $uid . '-' . $key ); ?>"><?php esc_html_e( 'Quantity', 'modern-core' ); ?></label>
								<input id="<?php echo esc_attr( $uid . '-' . $key ); ?>" type="number" inputmode="numeric" min="0" <?php echo $max > 0 ? 'max="' . (int) $max . '"' : ''; ?> value="<?php echo (int) $item['quantity']; ?>">
								<?php if ( 'plus-minus' === $qty_style ) : ?>
									<button type="button" data-step="1" aria-label="<?php esc_attr_e( 'Increase quantity', 'modern-core' ); ?>">+</button>
								<?php endif; ?>
							</div>
							<a class="mc-ci-remove" href="<?php echo esc_url( wc_get_cart_remove_url( $key ) ); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: product name */ __( 'Remove %s from cart', 'modern-core' ), $product->get_name() ) ); ?>">
								<?php echo 'text' === $remove_style ? esc_html__( 'Remove', 'modern-core' ) : '&#x2715;'; ?>
							</a>
						</div>
					</div>
					<div class="mc-ci-price"><?php echo wp_kses_post( $cart->get_product_subtotal( $product, $item['quantity'] ) ); ?></div>
				</li>
			<?php endforeach; ?>
		</ul>

		<div class="mc-ci-foot">
			<div style="display:flex;justify-content:space-between;margin-bottom:18px;font-size:18px;font-weight:700;">
				<span><?php esc_html_e( 'Subtotal', 'modern-core' ); ?></span>
				<span><?php echo wp_kses_post( $cart->get_cart_subtotal() ); ?></span>
			</div>
			<a class="mc-ci-checkout" href="<?php echo esc_url( wc_get_checkout_url() ); ?>"><?php esc_html_e( 'Proceed to checkout', 'modern-core' ); ?></a>
		</div>
	<?php endif; ?>

	<script>
	(function () {
		var root = document.getElementById(<?php echo wp_json_encode( $uid ); ?>);
		if (!root || typeof fetch === 'undefined') { return; }
		var cartUrl = <?php echo wp_json_encode( wc_get_cart_url() ); ?>;
		var nonce = <?php echo wp_json_encode( wp_create_nonce( 'woocommerce-cart' ) ); ?>;
		var timer = null;

		// Send quantity changes through WooCommerce's normal cart form handler, then reload this page's fragments.
		var update = function (key, qty) {
			root.classList.add('is-loading');
			var body = new URLSearchParams();
			body.set('cart[' + key + '][qty]', String(qty));
			body.set('update_cart', '1');
			body.set('woocommerce-cart-nonce', nonce);
			fetch(cartUrl, { method: 'POST', body: body, credentials: 'same-origin' })
				.then(function () { window.location.reload(); })
				.catch(function () { root.classList.remove('is-loading'); });
		};

		root.addEventListener('click', function (e) {
			var btn = e.target.closest('[data-step]');
			if (!btn) { return; }
			var input = btn.parentElement.querySelector('input');
			var next = Math.max(0, (parseInt(input.value, 10) || 0) + parseInt(btn.dataset.step, 10));
			if (input.max) { next = Math.min(next, parseInt(input.max, 10)); }
			input.value = next;
			clearTimeout(timer);
			timer = setTimeout(function () { update(btn.closest('[data-key]').dataset.key, next); }, 500);
		});
		root.addEventListener('change', function (e) {
			if (e.target.matches('.mc-ci-qty input')) {
				update(e.target.closest('[data-key]').dataset.key, Math.max(0, parseInt(e.target.value, 10) || 0));
			}
		});
		// After AJAX add-to-cart elsewhere on the page, show the new cart.
		if (window.jQuery) {
			window.jQuery(document.body).on('added_to_cart removed_from_cart', function () { window.location.reload(); });
		}
	}());
	</script>
</div>
