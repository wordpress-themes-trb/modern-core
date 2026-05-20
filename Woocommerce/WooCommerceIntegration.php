<?php
/**
 * Core WooCommerce Integration and Optimization.
 *
 * @package ModernCore\Woocommerce
 */

declare(strict_types=1);

namespace ModernCore\Woocommerce;

use ModernCore\Core\HooksManager;

/**
 * Handles WooCommerce theme support, style dequeuing, and fragment caching.
 */
class WooCommerceIntegration {

	/**
	 * Register hooks with the centralized HooksManager.
	 *
	 * @param HooksManager $hooks The hooks manager instance.
	 * @return void
	 */
	public function register_hooks( HooksManager $hooks ): void {
		$hooks->add_action( 'after_setup_theme', $this, 'declare_support' );
		$hooks->add_filter( 'woocommerce_enqueue_styles', $this, 'disable_default_styles' );
		$hooks->add_filter( 'woocommerce_add_to_cart_fragments', $this, 'optimize_ajax_fragments' );
	}

	/**
	 * Declare explicit theme support for WooCommerce FSE features.
	 *
	 * @return void
	 */
	public function declare_support(): void {
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}

	/**
	 * Disable default WooCommerce CSS to rely entirely on our Vite/SCSS pipeline.
	 *
	 * @param array<string, mixed> $enqueue_styles Array of styles to enqueue.
	 * @return array<string, mixed> Empty array to dequeue all.
	 */
	public function disable_default_styles( array $enqueue_styles ): array {
		return [];
	}

	/**
	 * Ensure AJAX add-to-cart fragments do not break the FSE mini-cart block.
	 *
	 * @param array<string, string> $fragments The cart fragments.
	 * @return array<string, string> Modified fragments.
	 */
	public function optimize_ajax_fragments( array $fragments ): array {
		// Only retain the strictly necessary fragments for the cart count token
		// to prevent excessive DOM parsing on every page load.
		if ( isset( $fragments['div.widget_shopping_cart_content'] ) ) {
			// In a block theme, the mini-cart block handles its own state via the REST API.
			// We clear legacy fragments to enforce Core Web Vitals compliance.
			unset( $fragments['div.widget_shopping_cart_content'] );
		}
		return $fragments;
	}
}