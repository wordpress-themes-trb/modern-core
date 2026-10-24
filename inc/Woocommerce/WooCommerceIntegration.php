<?php
/**
 * WooCommerce integration.
 *
 * @package ModernCore\Woocommerce
 */

declare(strict_types=1);

namespace ModernCore\Woocommerce;

use ModernCore\Core\HooksManager;

/**
 * Loads the WooCommerce scripts the theme's blocks rely on and adds the
 * theme's own cart fragments.
 */
class WooCommerceIntegration {

	/**
	 * Attach hooks.
	 *
	 * @param HooksManager $hooks Hooks manager.
	 */
	public function register_hooks( HooksManager $hooks ): void {
		$hooks->add_action( 'wp_enqueue_scripts', $this, 'enqueue_cart_scripts', 20 );
		$hooks->add_filter( 'woocommerce_add_to_cart_fragments', $this, 'cart_count_fragment' );
		$hooks->add_filter( 'loop_shop_per_page', $this, 'products_per_page' );
		$hooks->add_action( 'template_redirect', $this, 'setup_product_global' );
		$hooks->add_action( 'init', $this, 'normalize_filter_params', 1 );
		$hooks->add_filter( 'hooked_block_types', $this, 'remove_hooked_header_blocks', 20, 4 );
		$hooks->add_action( 'woocommerce_product_query', $this, 'apply_status_filters' );
	}

	/**
	 * "In stock only" (?in_stock=1) and "On sale" (?on_sale=1) filters from the
	 * Filter Sidebar. Price, attribute and rating filters are built into WooCommerce.
	 *
	 * @param \WP_Query $query Main product query.
	 */
	public function apply_status_filters( $query ): void {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only filter state.
		if ( ! empty( $_GET['in_stock'] ) ) {
			$tax_query   = (array) $query->get( 'tax_query' );
			$tax_query[] = [
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => [ 'outofstock' ],
				'operator' => 'NOT IN',
			];
			$query->set( 'tax_query', $tax_query );
		}
		if ( ! empty( $_GET['on_sale'] ) ) {
			$on_sale = array_merge( [ 0 ], wc_get_product_ids_on_sale() );
			$current = (array) $query->get( 'post__in' );
			$query->set( 'post__in', $current ? array_values( array_intersect( $current, $on_sale ) ) ?: [ 0 ] : $on_sale );
		}
		// phpcs:enable
	}

	/**
	 * WooCommerce automatically adds its account and mini-cart blocks after
	 * the last item of any navigation menu in the header. The theme's header
	 * already has its own account and cart icons, so those copies are removed.
	 *
	 * @param string[] $hooked_blocks Block types to insert.
	 * @param string   $position      Relative position.
	 * @param string   $anchor_block  Anchor block type.
	 * @param mixed    $context       Template, template part or post.
	 * @return string[]
	 */
	public function remove_hooked_header_blocks( $hooked_blocks, $position, $anchor_block, $context ): array {
		if ( 'core/navigation' !== $anchor_block && 'core/navigation-link' !== $anchor_block && 'core/page-list' !== $anchor_block ) {
			return (array) $hooked_blocks;
		}
		return array_values( array_diff( (array) $hooked_blocks, [ 'woocommerce/customer-account', 'woocommerce/mini-cart' ] ) );
	}

	/**
	 * WooCommerce reads attribute filters as "filter_color=black,navy". A filter
	 * form submitted without JavaScript sends "filter_color[]=black&filter_color[]=navy",
	 * so join those arrays before WooCommerce builds the product query.
	 */
	public function normalize_filter_params(): void {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only filter state.
		foreach ( $_GET as $key => $value ) {
			if ( 0 !== strpos( (string) $key, 'filter_' ) ) {
				continue;
			}
			if ( is_array( $value ) ) {
				$joined           = implode( ',', array_map( 'sanitize_title', array_map( 'wp_unslash', $value ) ) );
				$_GET[ $key ]     = $joined;
				$_REQUEST[ $key ] = $joined;
			}
			// Several values of one attribute mean "any of these" (navy OR black), unless the URL says otherwise.
			$type_key = 'query_type_' . substr( (string) $key, 7 );
			if ( ! isset( $_GET[ $type_key ] ) ) {
				$_GET[ $type_key ]     = 'or';
				$_REQUEST[ $type_key ] = 'or';
			}
		}
		// phpcs:enable
	}

	/**
	 * On product pages, set the global $product before any block renders.
	 *
	 * Block templates render outside The Loop, so WooCommerce has not set it
	 * yet when the Single Product, Stock Countdown or Reviews blocks run.
	 */
	public function setup_product_global(): void {
		if ( ! function_exists( 'is_product' ) || ! is_product() ) {
			return;
		}
		$post = get_queried_object();
		if ( $post instanceof \WP_Post ) {
			$GLOBALS['post'] = $post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			setup_postdata( $post );
			$GLOBALS['product'] = wc_get_product( $post );
		}
	}

	/**
	 * The mini cart, sticky bar and quick view listen for WooCommerce's
	 * "added_to_cart" and fragment events, which come from these scripts.
	 * WooCommerce no longer loads cart fragments on every page by default.
	 */
	public function enqueue_cart_scripts(): void {
		if ( ! class_exists( 'WooCommerce' ) || is_admin() ) {
			return;
		}
		wp_enqueue_script( 'wc-add-to-cart' );
		wp_enqueue_script( 'wc-cart-fragments' );

		// Quick view renders the single add-to-cart form, which needs variation support.
		wp_enqueue_script( 'wc-add-to-cart-variation' );
	}

	/**
	 * Keep every [data-mc-cart-count] badge in sync after AJAX cart changes.
	 *
	 * @param array<string, string> $fragments Fragments.
	 * @return array<string, string>
	 */
	public function cart_count_fragment( array $fragments ): array {
		if ( WC()->cart ) {
			$count                                   = (int) WC()->cart->get_cart_contents_count();
			$fragments['span[data-mc-cart-count]'] = sprintf( '<span data-mc-cart-count="%1$d">%1$d</span>', $count );
		}
		return $fragments;
	}

	/**
	 * Match the 12-product grid in the shop templates.
	 *
	 * @param int $per_page Products per page.
	 */
	public function products_per_page( $per_page ): int {
		return (int) apply_filters( 'modern_core_products_per_page', 12 );
	}
}
