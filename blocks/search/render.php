<?php
/**
 * Search block: product search with live suggestions.
 *
 * Submits to the normal WordPress/WooCommerce product search, so it works
 * without JavaScript. While typing (3+ characters) it shows matching
 * products from the WooCommerce Store API. Before typing, it shows the
 * store's best sellers.
 *
 * @package ModernCore
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$placeholder   = $attributes['placeholder'] ?? __( 'Search for products…', 'modern-core' );
$shape         = $attributes['shape'] ?? 'pill';
$show_icon     = $attributes['showIcon'] ?? true;
$icon_position = $attributes['iconPosition'] ?? 'right';
$bg_color      = $attributes['bgColor'] ?? '#f1f5f9';
$text_color    = $attributes['textColor'] ?? '#0f172a';
$width         = max( 10, min( 100, (int) ( $attributes['width'] ?? 100 ) ) );

$uid           = wp_unique_id( 'mc-search-' );
$is_woo        = class_exists( 'WooCommerce' );
$border_radius = 'pill' === $shape ? '50px' : ( 'rounded' === $shape ? '8px' : '0' );

// Best sellers shown before the visitor types.
$popular = array();
if ( $is_woo ) {
	$query = new WP_Query( modern_core_product_query_args( 'popularity', 4 ) );
	foreach ( $query->posts as $post ) {
		$product = wc_get_product( $post );
		if ( $product ) {
			$popular[] = $product;
		}
	}
}

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => "mc-search modern-core-search {$uid} mc-block",
		'id'    => $uid,
		'style' => "width: {$width}%; position: relative;",
	)
);

$icon = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>';
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by core. ?>>
	<style>
		.<?php echo esc_attr( $uid ); ?> form { display: flex; align-items: center; gap: 10px; background: <?php echo esc_attr( $bg_color ); ?>; border-radius: <?php echo esc_attr( $border_radius ); ?>; padding: 4px 8px 4px 18px; color: <?php echo esc_attr( $text_color ); ?>; transition: box-shadow .2s; }
		.<?php echo esc_attr( $uid ); ?> form:focus-within { box-shadow: 0 0 0 2px rgba(15,23,42,.15); }
		.<?php echo esc_attr( $uid ); ?> input[type="search"] { flex: 1; min-width: 0; border: 0; background: transparent; padding: 10px 0; color: inherit; font-size: 15px; box-shadow: none; }
		.<?php echo esc_attr( $uid ); ?> input[type="search"]:focus { outline: none; box-shadow: none; }
		.<?php echo esc_attr( $uid ); ?> .mc-s-submit { display: inline-flex; align-items: center; justify-content: center; width: 38px; height: 38px; padding: 0; border: 0; background: none; color: inherit; opacity: .65; cursor: pointer; }
		.<?php echo esc_attr( $uid ); ?> .mc-s-panel { position: absolute; top: calc(100% + 8px); left: 0; right: 0; z-index: 200; background: #fff; color: #0f172a; border-radius: 12px; box-shadow: 0 20px 40px rgba(15,23,42,.12); padding: 16px; }
		.<?php echo esc_attr( $uid ); ?> .mc-s-panel[hidden] { display: none; }
		.<?php echo esc_attr( $uid ); ?> .mc-s-label { margin: 0 0 10px; font-size: 11px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: #64748b; }
		.<?php echo esc_attr( $uid ); ?> .mc-s-list { list-style: none; margin: 0; padding: 0; display: grid; gap: 4px; }
		.<?php echo esc_attr( $uid ); ?> .mc-s-item a { display: flex; align-items: center; gap: 12px; padding: 6px; border-radius: 8px; text-decoration: none; color: inherit; }
		.<?php echo esc_attr( $uid ); ?> .mc-s-item a:hover, .<?php echo esc_attr( $uid ); ?> .mc-s-item a:focus-visible, .<?php echo esc_attr( $uid ); ?> .mc-s-item[aria-selected="true"] a { background: #f1f5f9; }
		.<?php echo esc_attr( $uid ); ?> .mc-s-item img { width: 40px; height: 48px; object-fit: cover; border-radius: 6px; flex: none; background: #e2e8f0; }
		.<?php echo esc_attr( $uid ); ?> .mc-s-name { font-size: 14px; font-weight: 600; display: block; }
		.<?php echo esc_attr( $uid ); ?> .mc-s-price { font-size: 13px; opacity: .7; }
		.<?php echo esc_attr( $uid ); ?> .mc-s-all { display: block; margin-top: 10px; font-size: 13px; font-weight: 600; color: inherit; }
		.<?php echo esc_attr( $uid ); ?> .mc-s-empty { margin: 0; font-size: 14px; opacity: .7; }
		.<?php echo esc_attr( $uid ); ?> .mc-s-tags { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 16px; }
		.<?php echo esc_attr( $uid ); ?> .mc-s-tags a { padding: 6px 14px; border-radius: 20px; background: #f1f5f9; color: #334155; font-size: 13px; text-decoration: none; }
		.<?php echo esc_attr( $uid ); ?> .mc-s-tags a:hover { background: #e2e8f0; }
	</style>

	<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" autocomplete="off">
		<?php if ( $show_icon && 'left' === $icon_position ) : ?>
			<span aria-hidden="true" style="opacity:.6;display:flex;"><?php echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></span>
		<?php endif; ?>
		<label class="screen-reader-text" for="<?php echo esc_attr( $uid ); ?>-input"><?php esc_html_e( 'Search products', 'modern-core' ); ?></label>
		<input id="<?php echo esc_attr( $uid ); ?>-input" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php echo esc_attr( $placeholder ); ?>" role="combobox" aria-expanded="false" aria-controls="<?php echo esc_attr( $uid ); ?>-panel" aria-autocomplete="list">
		<?php if ( $is_woo ) : ?>
			<input type="hidden" name="post_type" value="product">
		<?php endif; ?>
		<button type="submit" class="mc-s-submit" aria-label="<?php esc_attr_e( 'Search', 'modern-core' ); ?>">
			<?php echo ( $show_icon && 'left' !== $icon_position ) ? $icon : '<span aria-hidden="true">&rarr;</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static markup. ?>
		</button>
	</form>

	<?php if ( $is_woo ) : ?>
		<div class="mc-s-panel" id="<?php echo esc_attr( $uid ); ?>-panel" hidden>
			<div class="mc-s-popular">
				<?php
				// Trending search terms, set in the block's settings (comma separated).
				$trending = array_filter( array_map( 'trim', explode( ',', (string) ( $attributes['trendingTags'] ?? '' ) ) ) );
				if ( $trending ) :
					?>
					<p class="mc-s-label"><?php esc_html_e( 'Trending searches', 'modern-core' ); ?></p>
					<div class="mc-s-tags">
						<?php foreach ( $trending as $tag ) : ?>
							<a href="<?php echo esc_url( add_query_arg( array( 's' => $tag, 'post_type' => 'product' ), home_url( '/' ) ) ); ?>"><?php echo esc_html( $tag ); ?></a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
				<?php if ( $popular ) : ?>
					<p class="mc-s-label"><?php esc_html_e( 'Popular right now', 'modern-core' ); ?></p>
					<ul class="mc-s-list">
						<?php foreach ( $popular as $product ) : ?>
							<li class="mc-s-item">
								<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
									<?php echo $product->get_image( 'woocommerce_gallery_thumbnail', array( 'alt' => '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core image markup. ?>
									<span><span class="mc-s-name"><?php echo esc_html( $product->get_name() ); ?></span><span class="mc-s-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span></span>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
			<div class="mc-s-results" hidden aria-live="polite"></div>
		</div>

		<script>
		(function () {
			var root = document.getElementById(<?php echo wp_json_encode( $uid ); ?>);
			if (!root || !window.fetch) { return; }
			var input = root.querySelector('input[type="search"]');
			var panel = root.querySelector('.mc-s-panel');
			var popular = root.querySelector('.mc-s-popular');
			var results = root.querySelector('.mc-s-results');
			var api = <?php echo wp_json_encode( rest_url( 'wc/store/v1/products' ) ); ?>;
			var searchUrl = <?php echo wp_json_encode( add_query_arg( 'post_type', 'product', home_url( '/' ) ) ); ?>;
			var t = {
				suggestions: <?php echo wp_json_encode( __( 'Products', 'modern-core' ) ); ?>,
				none: <?php echo wp_json_encode( __( 'No products match your search.', 'modern-core' ) ); ?>,
				all: <?php echo wp_json_encode( __( 'See all results', 'modern-core' ) ); ?>
			};
			var timer = null, controller = null, active = -1;

			var open = function (show) {
				var hasPopular = !!popular.querySelector('li, .mc-s-tags a');
				var visible = show && (hasPopular || !results.hidden);
				panel.hidden = !visible;
				input.setAttribute('aria-expanded', visible ? 'true' : 'false');
			};
			var esc = function (s) { var d = document.createElement('div'); d.textContent = s == null ? '' : String(s); return d.innerHTML; };
			var money = function (prices) {
				if (!prices || prices.price == null) { return ''; }
				var minor = prices.currency_minor_unit || 0;
				var value = (parseInt(prices.price, 10) / Math.pow(10, minor)).toFixed(minor);
				return esc((prices.currency_prefix || '') + value + (prices.currency_suffix || ''));
			};
			var render = function (term, items) {
				var html = '<p class="mc-s-label">' + esc(t.suggestions) + '</p>';
				if (!items.length) {
					html += '<p class="mc-s-empty">' + esc(t.none) + '</p>';
				} else {
					html += '<ul class="mc-s-list" role="listbox">' + items.map(function (p) {
						var img = p.images && p.images[0] ? (p.images[0].thumbnail || p.images[0].src) : '';
						return '<li class="mc-s-item" role="option"><a href="' + esc(p.permalink) + '">' +
							(img ? '<img src="' + esc(img) + '" alt="" loading="lazy">' : '<img alt="">') +
							'<span><span class="mc-s-name">' + esc(p.name) + '</span><span class="mc-s-price">' + money(p.prices) + '</span></span></a></li>';
					}).join('') + '</ul>';
				}
				html += '<a class="mc-s-all" href="' + esc(searchUrl + '&s=' + encodeURIComponent(term)) + '">' + esc(t.all) + ' &rarr;</a>';
				results.innerHTML = html;
				popular.hidden = true;
				results.hidden = false;
				active = -1;
				open(true);
			};
			var search = function () {
				var term = input.value.trim();
				if (term.length < 3) {
					results.hidden = true;
					popular.hidden = false;
					open(document.activeElement === input);
					return;
				}
				if (controller) { controller.abort(); }
				controller = window.AbortController ? new AbortController() : null;
				fetch(api + '?per_page=5&search=' + encodeURIComponent(term), controller ? { signal: controller.signal } : {})
					.then(function (r) { return r.ok ? r.json() : []; })
					.then(function (items) { render(term, Array.isArray(items) ? items : []); })
					.catch(function () {});
			};

			input.addEventListener('focus', function () { open(true); });
			input.addEventListener('input', function () { clearTimeout(timer); timer = setTimeout(search, 250); });
			input.addEventListener('keydown', function (e) {
				var items = Array.prototype.slice.call(panel.querySelectorAll(results.hidden ? '.mc-s-popular .mc-s-item' : '.mc-s-results .mc-s-item'));
				if (e.key === 'Escape') { open(false); return; }
				if (!items.length || (e.key !== 'ArrowDown' && e.key !== 'ArrowUp' && e.key !== 'Enter')) { return; }
				if (e.key === 'Enter') {
					if (active >= 0 && items[active]) { e.preventDefault(); window.location.href = items[active].querySelector('a').href; }
					return;
				}
				e.preventDefault();
				active = (active + (e.key === 'ArrowDown' ? 1 : -1) + items.length) % items.length;
				items.forEach(function (li, i) { li.setAttribute('aria-selected', i === active ? 'true' : 'false'); });
			});
			document.addEventListener('click', function (e) { if (!root.contains(e.target)) { open(false); } });
		}());
		</script>
	<?php endif; ?>
</div>
