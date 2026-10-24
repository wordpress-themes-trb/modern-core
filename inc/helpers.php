<?php
/**
 * Template helpers shared by block render files.
 *
 * Plain functions (not a class) so render.php files can call them directly.
 *
 * @package ModernCore
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'modern_core_is_product_archive' ) ) {
	/**
	 * Whether the main query is a product listing: shop, category, tag, attribute or product search.
	 */
	function modern_core_is_product_archive(): bool {
		if ( ! function_exists( 'is_shop' ) ) {
			return false;
		}
		if ( is_shop() || is_product_taxonomy() ) {
			return true;
		}
		return is_search() && 'product' === get_query_var( 'post_type' );
	}
}

if ( ! function_exists( 'modern_core_product_query_args' ) ) {
	/**
	 * WP_Query arguments for a product block.
	 *
	 * Always hides products excluded from the catalogue and, on a product
	 * page, the product being viewed.
	 *
	 * @param string               $sort  date|recent|popularity|rating|sale|featured|price|price-desc|rand.
	 * @param int                  $count Number of products.
	 * @param array<string, mixed> $extra Extra WP_Query arguments.
	 * @return array<string, mixed>
	 */
	function modern_core_product_query_args( string $sort, int $count, array $extra = array() ): array {
		$args = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'posts_per_page'      => max( 1, $count ),
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'tax_query'           => array(), // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		);

		$hidden_terms = array( 'exclude-from-catalog' );
		if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
			$hidden_terms[] = 'outofstock';
		}
		$args['tax_query'][] = array(
			'taxonomy' => 'product_visibility',
			'field'    => 'slug',
			'terms'    => $hidden_terms,
			'operator' => 'NOT IN',
		);

		if ( function_exists( 'is_product' ) && is_product() ) {
			$args['post__not_in'] = array( get_queried_object_id() );
		}

		switch ( $sort ) {
			case 'popularity':
				$args['meta_key'] = 'total_sales'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				$args['orderby']  = array(
					'meta_value_num' => 'DESC',
					'date'           => 'DESC',
				);
				break;
			case 'rating':
				$args['meta_key'] = '_wc_average_rating'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				$args['orderby']  = array(
					'meta_value_num' => 'DESC',
					'date'           => 'DESC',
				);
				break;
			case 'sale':
				$args['post__in'] = array_merge( array( 0 ), function_exists( 'wc_get_product_ids_on_sale' ) ? wc_get_product_ids_on_sale() : array() );
				break;
			case 'featured':
				$args['tax_query'][] = array(
					'taxonomy' => 'product_visibility',
					'field'    => 'slug',
					'terms'    => array( 'featured' ),
				);
				break;
			case 'price':
			case 'price-desc':
				$args['meta_key'] = '_price'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				$args['orderby']  = 'meta_value_num';
				$args['order']    = 'price' === $sort ? 'ASC' : 'DESC';
				break;
			case 'rand':
				$args['orderby'] = 'rand';
				break;
			default:
				$args['orderby'] = 'date';
				$args['order']   = 'DESC';
		}

		if ( isset( $extra['post__not_in'], $args['post__not_in'] ) ) {
			$extra['post__not_in'] = array_merge( $args['post__not_in'], (array) $extra['post__not_in'] );
		}
		if ( ! empty( $extra['tax_query'] ) ) { // Add to the visibility clauses rather than replacing them.
			$extra['tax_query'] = array_merge( $args['tax_query'], (array) $extra['tax_query'] ); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		}

		return (array) apply_filters( 'modern_core_product_query_args', array_merge( $args, $extra ), $sort, $count );
	}
}

if ( ! function_exists( 'modern_core_swatch_color' ) ) {
	/**
	 * Swatch colour for a colour attribute term.
	 *
	 * Uses term meta "color" or "swatch" (as saved by most swatch plugins),
	 * then the term name if it is a CSS colour or a common fashion colour.
	 * Returns "" when no colour is known, so callers can show a text label.
	 *
	 * @param WP_Term $term Attribute term.
	 */
	function modern_core_swatch_color( WP_Term $term ): string {
		foreach ( array( 'color', 'swatch', 'product_attribute_color' ) as $key ) {
			$meta = (string) get_term_meta( $term->term_id, $key, true );
			if ( preg_match( '/^#[0-9a-f]{3,8}$/i', $meta ) ) {
				return $meta;
			}
		}

		$name    = strtolower( preg_replace( '/[^a-z]/i', '', $term->name ) );
		$fashion = array(
			'camel'     => '#c19a6b',
			'beige'     => '#e8dcc8',
			'cream'     => '#f5efe0',
			'ivory'     => '#fffff0',
			'ecru'      => '#e7dfc6',
			'sand'      => '#d9c6a5',
			'stone'     => '#b7ae9f',
			'taupe'     => '#8b7d6b',
			'charcoal'  => '#36454f',
			'burgundy'  => '#800020',
			'rust'      => '#b7410e',
			'mustard'   => '#d4a017',
			'khaki'     => '#c3b091',
			'mint'      => '#98ff98',
			'blush'     => '#e8b4b8',
			'denim'     => '#1560bd',
			'oatmeal'   => '#d8cbb0',
			'multi'     => 'conic-gradient(#ef4444, #f59e0b, #10b981, #3b82f6, #8b5cf6, #ef4444)',
		);
		if ( isset( $fashion[ $name ] ) ) {
			return $fashion[ $name ];
		}

		$css = array( 'black', 'white', 'gray', 'grey', 'silver', 'red', 'maroon', 'orange', 'yellow', 'gold', 'olive', 'lime', 'green', 'teal', 'aqua', 'cyan', 'blue', 'navy', 'purple', 'violet', 'fuchsia', 'magenta', 'pink', 'brown', 'tan', 'chocolate', 'coral', 'salmon', 'crimson', 'indigo', 'lavender', 'plum', 'orchid', 'turquoise', 'skyblue', 'royalblue', 'darkgreen', 'forestgreen', 'seagreen', 'wheat', 'linen', 'tomato', 'peachpuff', 'lightblue', 'lightgray', 'lightgrey', 'darkgray', 'darkgrey' );
		return in_array( $name, $css, true ) ? $name : '';
	}
}

if ( ! function_exists( 'modern_core_save_button' ) ) {
	/**
	 * "Save to wishlist" or "Add to compare" toggle for a product.
	 *
	 * The list itself lives in the visitor's browser (assets/src/js/main.js
	 * handles the click), so it works for guests and on cached pages.
	 *
	 * @param int    $product_id Product ID.
	 * @param string $list       wishlist|compare.
	 * @param string $class      Extra CSS classes.
	 */
	function modern_core_save_button( int $product_id, string $list = 'wishlist', string $class = '' ): string {
		$is_wish = 'wishlist' === $list;
		$label   = $is_wish ? __( 'Save to wishlist', 'modern-core' ) : __( 'Add to compare', 'modern-core' );
		$icon    = $is_wish
			? '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1.1L12 21.3l7.8-7.8 1-1.1a5.5 5.5 0 0 0 0-7.8z"/></svg>'
			: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M16 3h5v5M4 20 21 3M21 16v5h-5M15 15l6 6M4 4l5 5"/></svg>';
		return sprintf(
			'<button type="button" class="mc-save-btn mc-save-%1$s %2$s" data-mc-%1$s="%3$d" aria-pressed="false" aria-label="%4$s" title="%4$s">%5$s</button>',
			esc_attr( $list ),
			esc_attr( $class ),
			$product_id,
			esc_attr( $label ),
			$icon // Static SVG.
		);
	}
}

if ( ! function_exists( 'modern_core_page_url' ) ) {
	/**
	 * URL of a published page by slug, or "" if it doesn't exist.
	 *
	 * @param string $slug Page slug, e.g. "wishlist".
	 */
	function modern_core_page_url( string $slug ): string {
		$page = get_page_by_path( $slug );
		return ( $page && 'publish' === $page->post_status ) ? (string) get_permalink( $page ) : '';
	}
}

if ( ! function_exists( 'modern_core_product_image_url' ) ) {
	/**
	 * A product's main image URL, or WooCommerce's placeholder when it has none.
	 *
	 * @param WC_Product|null $product Product.
	 * @param string          $size    Image size.
	 */
	function modern_core_product_image_url( $product, string $size = 'woocommerce_thumbnail' ): string {
		$url = ( $product && $product->get_image_id() ) ? wp_get_attachment_image_url( $product->get_image_id(), $size ) : '';
		if ( ! $url && $product && $product->get_parent_id() ) {
			$parent = wc_get_product( $product->get_parent_id() ); // Variation without its own image.
			$url    = ( $parent && $parent->get_image_id() ) ? wp_get_attachment_image_url( $parent->get_image_id(), $size ) : '';
		}
		return $url ? $url : ( function_exists( 'wc_placeholder_img_src' ) ? wc_placeholder_img_src( $size ) : '' );
	}
}

if ( ! function_exists( 'modern_core_url' ) ) {
	/**
	 * Turn a site-relative path ("/shop/") from a block attribute into a full URL.
	 * Absolute URLs, anchors and empty values pass through unchanged.
	 *
	 * @param string $url URL or path.
	 */
	function modern_core_url( string $url ): string {
		if ( '' !== $url && '/' === $url[0] && ( ! isset( $url[1] ) || '/' !== $url[1] ) ) {
			return home_url( $url );
		}
		return $url;
	}
}
