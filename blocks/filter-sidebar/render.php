<?php
/**
 * Filter Sidebar block.
 *
 * Filters the shop with WooCommerce's own URL parameters, which the main
 * product query already understands:
 *   min_price / max_price   price range
 *   filter_{attribute}      comma-separated term slugs, e.g. filter_color=black,navy
 *   product category        links to the category page
 *
 * Each filter group comes from the "filters" attribute:
 *   { type: price|color|pill|checkbox, label, attribute, expanded }
 * where attribute is a product attribute taxonomy (pa_color) or product_cat.
 *
 * @package ModernCore
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

$placement   = $attributes['placementMode'] ?? 'sidebar-left';
$filters     = is_array( $attributes['filters'] ?? null ) ? $attributes['filters'] : array();
$show_counts = $attributes['showCounts'] ?? true;
$show_chips  = $attributes['showActiveChips'] ?? true;
$chip_bg     = $attributes['chipBgColor'] ?? '#f1f5f9';
$chip_text   = $attributes['chipTextColor'] ?? '#0f172a';
$head_c      = $attributes['headingColor'] ?? '#0f172a';
$accent      = $attributes['accentColor'] ?? '#3b82f6';

$uid = wp_unique_id( 'mc-filter-' );

// The page the filters apply to: the current category/tag, else the shop.
$queried = get_queried_object();
if ( $queried instanceof WP_Term && in_array( $queried->taxonomy, array( 'product_cat', 'product_tag' ), true ) ) {
	$base_url = get_term_link( $queried );
} else {
	$base_url = wc_get_page_permalink( 'shop' );
}
$base_url = is_wp_error( $base_url ) ? home_url( '/' ) : $base_url;

// Current state from the URL.
// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only filter state.
$current_min = isset( $_GET['min_price'] ) ? wc_format_decimal( wp_unslash( $_GET['min_price'] ), 0 ) : '';
$current_max = isset( $_GET['max_price'] ) ? wc_format_decimal( wp_unslash( $_GET['max_price'] ), 0 ) : '';
$keep_args   = array();
foreach ( array( 'orderby', 's', 'post_type' ) as $keep ) {
	if ( isset( $_GET[ $keep ] ) && '' !== $_GET[ $keep ] ) {
		$keep_args[ $keep ] = sanitize_text_field( wp_unslash( $_GET[ $keep ] ) );
	}
}
$flag_in_stock = ! empty( $_GET['in_stock'] );
$flag_on_sale  = ! empty( $_GET['on_sale'] );
$rating_min    = isset( $_GET['rating_filter'] ) ? (int) min( array_map( 'absint', explode( ',', sanitize_text_field( wp_unslash( $_GET['rating_filter'] ) ) ) ) ) : 0;
$chosen        = array(); // taxonomy => [slugs].
foreach ( $_GET as $key => $value ) {
	if ( 0 === strpos( (string) $key, 'filter_' ) && is_string( $value ) && '' !== $value ) {
		$taxonomy = wc_attribute_taxonomy_name( substr( sanitize_key( $key ), 7 ) );
		if ( taxonomy_exists( $taxonomy ) ) {
			$chosen[ $taxonomy ] = array_filter( array_map( 'sanitize_title', explode( ',', wp_unslash( $value ) ) ) );
		}
	}
}
// phpcs:enable

/**
 * Current URL with one parameter changed or removed (for chips and "clear").
 */
$current_url = static function ( array $set = array(), array $remove = array() ) use ( $base_url, $keep_args, $current_min, $current_max, $chosen, $flag_in_stock, $flag_on_sale, $rating_min ): string {
	$args = $keep_args;
	if ( '' !== $current_min ) {
		$args['min_price'] = $current_min;
	}
	if ( '' !== $current_max ) {
		$args['max_price'] = $current_max;
	}
	if ( $flag_in_stock ) {
		$args['in_stock'] = '1';
	}
	if ( $flag_on_sale ) {
		$args['on_sale'] = '1';
	}
	if ( $rating_min ) {
		$args['rating_filter'] = implode( ',', range( $rating_min, 5 ) );
	}
	foreach ( $chosen as $taxonomy => $slugs ) {
		$args[ 'filter_' . wc_attribute_taxonomy_slug( $taxonomy ) ] = implode( ',', $slugs );
	}
	$args = array_merge( array_diff_key( $args, array_flip( $remove ) ), $set );
	return add_query_arg( array_map( 'rawurlencode', array_filter( $args, 'strlen' ) ), $base_url );
};

// Price bounds from WooCommerce's product lookup table.
global $wpdb;
$bounds    = $wpdb->get_row( "SELECT MIN( min_price ) AS min_price, MAX( max_price ) AS max_price FROM {$wpdb->wc_product_meta_lookup}" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
$price_min = $bounds ? (int) floor( (float) $bounds->min_price ) : 0;
$price_max = $bounds ? (int) ceil( (float) $bounds->max_price ) : 0;

// Active chips.
$chips = array();
if ( '' !== $current_min || '' !== $current_max ) {
	$chips[] = array(
		'label' => sprintf(
			/* translators: 1: min price, 2: max price */
			__( '%1$s – %2$s', 'modern-core' ),
			wp_strip_all_tags( wc_price( '' !== $current_min ? $current_min : $price_min, array( 'decimals' => 0 ) ) ),
			wp_strip_all_tags( wc_price( '' !== $current_max ? $current_max : $price_max, array( 'decimals' => 0 ) ) )
		),
		'url'   => $current_url( array(), array( 'min_price', 'max_price' ) ),
	);
}
if ( $flag_in_stock ) {
	$chips[] = array(
		'label' => __( 'In stock', 'modern-core' ),
		'url'   => $current_url( array(), array( 'in_stock' ) ),
	);
}
if ( $flag_on_sale ) {
	$chips[] = array(
		'label' => __( 'On sale', 'modern-core' ),
		'url'   => $current_url( array(), array( 'on_sale' ) ),
	);
}
if ( $rating_min ) {
	$chips[] = array(
		/* translators: %d: minimum star rating */
		'label' => sprintf( __( '%d★ & up', 'modern-core' ), $rating_min ),
		'url'   => $current_url( array(), array( 'rating_filter' ) ),
	);
}
foreach ( $chosen as $taxonomy => $slugs ) {
	foreach ( $slugs as $slug ) {
		$term = get_term_by( 'slug', $slug, $taxonomy );
		if ( ! $term ) {
			continue;
		}
		$key     = 'filter_' . wc_attribute_taxonomy_slug( $taxonomy );
		$rest    = array_diff( $slugs, array( $slug ) );
		$chips[] = array(
			'label' => $term->name,
			'url'   => $rest ? $current_url( array( $key => implode( ',', $rest ) ) ) : $current_url( array(), array( $key ) ),
		);
	}
}

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class'      => "modern-core-filter-sidebar {$uid} placement-{$placement} mc-block width-" . sanitize_html_class( $attributes['widthMode'] ?? 'contained' ),
		'id'         => $uid,
		'aria-label' => __( 'Product filters', 'modern-core' ),
	)
);

$chevron = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="6 9 12 15 18 9"></polyline></svg>';
?>
<aside <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by core. ?>>
	<style>
		.<?php echo esc_attr( $uid ); ?> { --mc-accent: <?php echo esc_attr( $accent ); ?>; --mc-head: <?php echo esc_attr( $head_c ); ?>; width: 100%; max-width: 300px; }
		.<?php echo esc_attr( $uid ); ?>.placement-horizontal { max-width: none; }
		.<?php echo esc_attr( $uid ); ?>.placement-horizontal .mc-filter-form { display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-start; }
		.<?php echo esc_attr( $uid ); ?>.placement-horizontal .mc-f-group { width: 240px; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px; margin: 0; }
		.<?php echo esc_attr( $uid ); ?> .mc-f-drawer-head { display: none; }
		.<?php echo esc_attr( $uid ); ?> .mc-f-chips { margin-bottom: 24px; }
		.<?php echo esc_attr( $uid ); ?> .mc-f-chips-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; font-size: 13px; font-weight: 700; color: var(--mc-head); }
		.<?php echo esc_attr( $uid ); ?> .mc-f-chips-head a { font-weight: 400; color: inherit; text-decoration: underline; }
		.<?php echo esc_attr( $uid ); ?> .mc-f-chip-list { display: flex; flex-wrap: wrap; gap: 8px; }
		.<?php echo esc_attr( $uid ); ?> .mc-f-chip { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: <?php echo esc_attr( $chip_bg ); ?>; color: <?php echo esc_attr( $chip_text ); ?>; border-radius: 999px; font-size: 12px; font-weight: 600; text-decoration: none; }
		.<?php echo esc_attr( $uid ); ?> .mc-f-chip:hover { opacity: .8; }
		.<?php echo esc_attr( $uid ); ?> .mc-f-group { border-bottom: 1px solid #e2e8f0; padding-bottom: 20px; margin-bottom: 20px; }
		.<?php echo esc_attr( $uid ); ?> .mc-f-group:last-of-type { border-bottom: 0; }
		.<?php echo esc_attr( $uid ); ?> .mc-f-group-head { display: flex; width: 100%; justify-content: space-between; align-items: center; padding: 0; background: none; border: 0; cursor: pointer; font: inherit; font-size: 12px; text-transform: uppercase; letter-spacing: .08em; font-weight: 700; color: var(--mc-head); }
		.<?php echo esc_attr( $uid ); ?> .mc-f-group-head svg { transition: transform .2s; }
		.<?php echo esc_attr( $uid ); ?> .mc-f-group-head[aria-expanded="false"] svg { transform: rotate(-90deg); }
		.<?php echo esc_attr( $uid ); ?> .mc-f-content { margin-top: 14px; }
		.<?php echo esc_attr( $uid ); ?> .mc-f-content[hidden] { display: none; }
		.<?php echo esc_attr( $uid ); ?> .mc-f-list { list-style: none; margin: 0; padding: 0; display: grid; gap: 10px; font-size: 14px; }
		.<?php echo esc_attr( $uid ); ?> .mc-f-list a { display: flex; justify-content: space-between; text-decoration: none; color: inherit; }
		.<?php echo esc_attr( $uid ); ?> .mc-f-list a[aria-current] { font-weight: 700; }
		.<?php echo esc_attr( $uid ); ?> .mc-f-cb { display: flex; align-items: center; justify-content: space-between; gap: 10px; cursor: pointer; font-size: 14px; font-weight: 400; }
		.<?php echo esc_attr( $uid ); ?> .mc-f-cb span:first-child { display: flex; align-items: center; gap: 10px; }
		.<?php echo esc_attr( $uid ); ?> input[type="checkbox"] { width: 16px; height: 16px; accent-color: var(--mc-accent); }
		.<?php echo esc_attr( $uid ); ?> .mc-f-count { font-size: 12px; color: #94a3b8; }
		.<?php echo esc_attr( $uid ); ?> .mc-f-swatches, .<?php echo esc_attr( $uid ); ?> .mc-f-pills { display: flex; flex-wrap: wrap; gap: 8px; }
		.<?php echo esc_attr( $uid ); ?> .mc-f-swatches input, .<?php echo esc_attr( $uid ); ?> .mc-f-pills input { position: absolute; opacity: 0; width: 1px; height: 1px; }
		.<?php echo esc_attr( $uid ); ?> .mc-f-swatch { display: block; width: 28px; height: 28px; border-radius: 50%; border: 1px solid rgba(0,0,0,.15); cursor: pointer; }
		.<?php echo esc_attr( $uid ); ?> input:checked + .mc-f-swatch { box-shadow: 0 0 0 2px #fff, 0 0 0 4px var(--mc-accent); }
		.<?php echo esc_attr( $uid ); ?> input:focus-visible + .mc-f-swatch, .<?php echo esc_attr( $uid ); ?> input:focus-visible + .mc-f-pill { outline: 2px solid var(--mc-accent); outline-offset: 2px; }
		.<?php echo esc_attr( $uid ); ?> .mc-f-pill { display: block; min-width: 40px; padding: 7px 12px; font-size: 13px; text-align: center; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; font-weight: 500; }
		.<?php echo esc_attr( $uid ); ?> input:checked + .mc-f-pill { background: var(--mc-accent); border-color: var(--mc-accent); color: #fff; }
		.<?php echo esc_attr( $uid ); ?> .mc-f-price { display: flex; align-items: center; gap: 8px; }
		.<?php echo esc_attr( $uid ); ?> .mc-f-price input { padding: 9px 10px; font-size: 14px; }
		.<?php echo esc_attr( $uid ); ?> .mc-f-apply { width: 100%; margin-top: 12px; padding: 11px; }
		@media (max-width: 900px) {
			.<?php echo esc_attr( $uid ); ?>:not(.placement-horizontal) { position: fixed; inset: 0 auto 0 0; z-index: 100000; width: min(360px, 88vw); max-width: none; padding: 20px; overflow-y: auto; background: #fff; box-shadow: 10px 0 40px rgba(0,0,0,.15); transform: translateX(-105%); visibility: hidden; transition: transform .35s ease, visibility 0s linear .35s; }
			.<?php echo esc_attr( $uid ); ?>.is-mobile-open { transform: none; visibility: visible; transition: transform .35s ease; }
			.<?php echo esc_attr( $uid ); ?> .mc-f-drawer-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; font-weight: 700; font-size: 18px; }
			.<?php echo esc_attr( $uid ); ?> .mc-f-close { background: none; border: 0; font-size: 22px; cursor: pointer; color: inherit; }
			.mc-filter-backdrop { position: fixed; inset: 0; z-index: 99999; background: rgba(15,23,42,.45); }
		}
	</style>

	<div class="mc-f-drawer-head">
		<span><?php esc_html_e( 'Filters', 'modern-core' ); ?></span>
		<button type="button" class="mc-f-close" aria-label="<?php esc_attr_e( 'Close filters', 'modern-core' ); ?>">&#x2715;</button>
	</div>

	<?php if ( $show_chips && $chips ) : ?>
		<div class="mc-f-chips">
			<div class="mc-f-chips-head">
				<span><?php esc_html_e( 'Active filters', 'modern-core' ); ?></span>
				<a href="<?php echo esc_url( add_query_arg( array_map( 'rawurlencode', $keep_args ), $base_url ) ); ?>"><?php esc_html_e( 'Clear all', 'modern-core' ); ?></a>
			</div>
			<div class="mc-f-chip-list">
				<?php foreach ( $chips as $chip ) : ?>
					<a class="mc-f-chip" href="<?php echo esc_url( $chip['url'] ); ?>">
						<?php echo esc_html( $chip['label'] ); ?>
						<span aria-hidden="true">&#x2715;</span>
						<span class="screen-reader-text"><?php esc_html_e( '(remove filter)', 'modern-core' ); ?></span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

	<form class="mc-filter-form" action="<?php echo esc_url( $base_url ); ?>" method="get">
		<?php foreach ( $keep_args as $name => $value ) : ?>
			<input type="hidden" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>">
		<?php endforeach; ?>

		<?php
		foreach ( $filters as $index => $filter ) :
			$type     = $filter['type'] ?? 'checkbox';
			$label    = $filter['label'] ?? '';
			$taxonomy = $filter['attribute'] ?? '';
			$expanded = ! empty( $filter['expanded'] );
			$panel_id = $uid . '-panel-' . $index;

			// Build the group body first; skip groups with nothing to show.
			ob_start();

			if ( 'price' === $type ) {
				if ( $price_max <= $price_min ) {
					ob_end_clean();
					continue;
				}
				?>
				<div class="mc-f-price">
					<label class="screen-reader-text" for="<?php echo esc_attr( $panel_id ); ?>-min"><?php esc_html_e( 'Minimum price', 'modern-core' ); ?></label>
					<input id="<?php echo esc_attr( $panel_id ); ?>-min" type="number" inputmode="numeric" name="min_price" min="<?php echo esc_attr( (string) $price_min ); ?>" max="<?php echo esc_attr( (string) $price_max ); ?>" step="1" placeholder="<?php echo esc_attr( (string) $price_min ); ?>" value="<?php echo esc_attr( (string) $current_min ); ?>">
					<span aria-hidden="true">–</span>
					<label class="screen-reader-text" for="<?php echo esc_attr( $panel_id ); ?>-max"><?php esc_html_e( 'Maximum price', 'modern-core' ); ?></label>
					<input id="<?php echo esc_attr( $panel_id ); ?>-max" type="number" inputmode="numeric" name="max_price" min="<?php echo esc_attr( (string) $price_min ); ?>" max="<?php echo esc_attr( (string) $price_max ); ?>" step="1" placeholder="<?php echo esc_attr( (string) $price_max ); ?>" value="<?php echo esc_attr( (string) $current_max ); ?>">
				</div>
				<button type="submit" class="button mc-f-apply"><?php esc_html_e( 'Apply', 'modern-core' ); ?></button>
				<?php
			} elseif ( 'status' === $type ) {
				?>
				<div class="mc-f-list">
					<label class="mc-f-cb"><span><input type="checkbox" name="in_stock" value="1"<?php checked( $flag_in_stock ); ?>> <?php esc_html_e( 'In stock only', 'modern-core' ); ?></span></label>
					<label class="mc-f-cb"><span><input type="checkbox" name="on_sale" value="1"<?php checked( $flag_on_sale ); ?>> <?php esc_html_e( 'On sale', 'modern-core' ); ?></span></label>
				</div>
				<?php
			} elseif ( 'rating' === $type ) {
				echo '<div class="mc-f-list">';
				foreach ( array( 4, 3, 2 ) as $stars ) {
					printf(
						'<label class="mc-f-cb"><span><input type="radio" name="rating_filter" value="%1$s"%2$s> <span aria-hidden="true" style="color:#f59e0b;letter-spacing:1px">%3$s</span> <span class="screen-reader-text">%4$s</span>%5$s</span></label>',
						esc_attr( implode( ',', range( $stars, 5 ) ) ),
						checked( $rating_min, $stars, false ),
						esc_html( str_repeat( '★', $stars ) . str_repeat( '☆', 5 - $stars ) ),
						/* translators: %d: number of stars */
						esc_html( sprintf( __( '%d stars', 'modern-core' ), $stars ) ),
						esc_html__( '& up', 'modern-core' )
					);
				}
				echo '</div>';
			} elseif ( 'product_cat' === $taxonomy ) {
				$terms = get_terms(
					array(
						'taxonomy'   => 'product_cat',
						'hide_empty' => true,
						'parent'     => 0,
						'exclude'    => array( (int) get_option( 'default_product_cat' ) ),
					)
				);
				if ( is_wp_error( $terms ) || ! $terms ) {
					ob_end_clean();
					continue;
				}
				echo '<ul class="mc-f-list">';
				foreach ( $terms as $term ) {
					$is_current = $queried instanceof WP_Term && (int) $queried->term_id === (int) $term->term_id;
					printf(
						'<li><a href="%1$s"%2$s><span>%3$s</span>%4$s</a></li>',
						esc_url( get_term_link( $term ) ),
						$is_current ? ' aria-current="page"' : '',
						esc_html( $term->name ),
						$show_counts ? '<span class="mc-f-count">' . esc_html( (string) $term->count ) . '</span>' : ''
					);
				}
				echo '</ul>';
			} else {
				if ( ! $taxonomy || ! taxonomy_exists( $taxonomy ) ) {
					ob_end_clean();
					continue;
				}
				// "menu_order" follows the order set under Products > Attributes > Configure terms.
				$terms = get_terms(
					array(
						'taxonomy'   => $taxonomy,
						'hide_empty' => true,
						'orderby'    => 'menu_order',
					)
				);
				if ( is_wp_error( $terms ) || ! $terms ) {
					ob_end_clean();
					continue;
				}
				$field    = 'filter_' . wc_attribute_taxonomy_slug( $taxonomy );
				$selected = $chosen[ $taxonomy ] ?? array();
				$wrap     = 'color' === $type ? 'mc-f-swatches' : ( 'pill' === $type ? 'mc-f-pills' : 'mc-f-list' );
				echo '<div class="' . esc_attr( $wrap ) . '" data-field="' . esc_attr( $field ) . '">';
				foreach ( $terms as $term ) {
					$checked = in_array( $term->slug, $selected, true ) ? ' checked' : '';
					$input   = sprintf( '<input type="checkbox" name="%1$s[]" value="%2$s"%3$s>', esc_attr( $field ), esc_attr( $term->slug ), $checked );
					$color = 'color' === $type ? modern_core_swatch_color( $term ) : '';
					if ( 'color' === $type && '' === $color ) {
						// No known colour for this term: show it as a text pill instead of a blank circle.
						printf( '<label>%1$s<span class="mc-f-pill">%2$s</span></label>', $input, esc_html( $term->name ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $input built from escaped parts.
					} elseif ( 'color' === $type ) {
						printf(
							'<label title="%1$s">%2$s<span class="mc-f-swatch" style="background:%3$s"></span><span class="screen-reader-text">%1$s</span></label>',
							esc_attr( $term->name ),
							$input, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from escaped parts.
							esc_attr( $color )
						);
					} elseif ( 'pill' === $type ) {
						printf( '<label>%1$s<span class="mc-f-pill">%2$s</span></label>', $input, esc_html( $term->name ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $input built from escaped parts.
					} else {
						printf(
							'<label class="mc-f-cb"><span>%1$s %2$s</span>%3$s</label>',
							$input, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from escaped parts.
							esc_html( $term->name ),
							$show_counts ? '<span class="mc-f-count">' . esc_html( (string) $term->count ) . '</span>' : ''
						);
					}
				}
				echo '</div>';
			}

			$body = (string) ob_get_clean();
			?>
			<div class="mc-f-group">
				<button type="button" class="mc-f-group-head" aria-expanded="<?php echo $expanded ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( $panel_id ); ?>">
					<?php echo esc_html( $label ); ?>
					<?php echo $chevron; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
				</button>
				<div class="mc-f-content" id="<?php echo esc_attr( $panel_id ); ?>"<?php echo $expanded ? '' : ' hidden'; ?>>
					<?php echo $body; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built above from escaped parts. ?>
				</div>
			</div>
		<?php endforeach; ?>

		<noscript><button type="submit" class="button mc-f-apply"><?php esc_html_e( 'Apply filters', 'modern-core' ); ?></button></noscript>
	</form>

	<script>
	(function () {
		var root = document.getElementById(<?php echo wp_json_encode( $uid ); ?>);
		if (!root) { return; }
		var form = root.querySelector('.mc-filter-form');

		// Collapse / expand groups.
		root.querySelectorAll('.mc-f-group-head').forEach(function (head) {
			head.addEventListener('click', function () {
				var open = head.getAttribute('aria-expanded') !== 'true';
				head.setAttribute('aria-expanded', open ? 'true' : 'false');
				document.getElementById(head.getAttribute('aria-controls')).hidden = !open;
			});
		});

		// Build the URL WooCommerce expects (filter_x=a,b) and go there.
		var apply = function () {
			var url = new URL(form.action, window.location.href);
			var data = new FormData(form);
			var lists = {};
			data.forEach(function (value, key) {
				if (value === '') { return; }
				if (key.slice(-2) === '[]') {
					key = key.slice(0, -2);
					(lists[key] = lists[key] || []).push(value);
				} else {
					url.searchParams.set(key, value);
				}
			});
			Object.keys(lists).forEach(function (key) { url.searchParams.set(key, lists[key].join(',')); });
			var grid = document.querySelector('.modern-core-product-grid');
			if (grid) { grid.style.opacity = '0.5'; }
			window.location.href = url.toString();
		};
		form.addEventListener('change', function (e) {
			if (e.target.type === 'checkbox' || e.target.type === 'radio') { apply(); }
		});
		form.addEventListener('submit', function (e) { e.preventDefault(); apply(); });

		// Mobile drawer, opened by the Shop Toolbar's "Filters" button.
		// While open, the panel moves to <body> so no page section can paint over it.
		var backdrop = null;
		var marker = document.createComment('mc-filter-sidebar');
		var close = function () {
			if (!root.classList.contains('is-mobile-open')) { return; }
			root.classList.remove('is-mobile-open');
			if (backdrop) { backdrop.remove(); backdrop = null; }
			if (marker.parentNode) { marker.parentNode.replaceChild(root, marker); }
			document.body.style.overflow = '';
		};
		document.addEventListener('mc:offcanvas:open', function (e) {
			if (!e.detail || e.detail.target !== 'filters') { return; }
			root.parentNode.insertBefore(marker, root);
			backdrop = document.createElement('div');
			backdrop.className = 'mc-filter-backdrop';
			backdrop.addEventListener('click', close);
			document.body.appendChild(backdrop);
			document.body.appendChild(root);
			document.body.style.overflow = 'hidden';
			// Next frame, so the slide-in transition runs.
			requestAnimationFrame(function () { root.classList.add('is-mobile-open'); });
			var btn = root.querySelector('.mc-f-close');
			if (btn) { setTimeout(function () { btn.focus(); }, 50); }
		});
		window.addEventListener('resize', function () { if (window.innerWidth > 900) { close(); } });
		root.querySelector('.mc-f-close').addEventListener('click', close);
		document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { close(); } });
	}());
	</script>
</aside>
