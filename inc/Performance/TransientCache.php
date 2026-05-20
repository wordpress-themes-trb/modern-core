<?php
/**
 * WooCommerce Loop Transient Caching.
 *
 * @package ModernCore\Performance
 */

declare(strict_types=1);

namespace ModernCore\Performance;

use ModernCore\Core\HooksManager;

/**
 * Caches heavy WooCommerce product queries using the Transients API.
 */
class TransientCache {

	/**
	 * Cache expiration time in seconds (e.g., 1 hour).
	 */
	private const CACHE_TTL = 3600;

	/**
	 * Register hooks with the centralized HooksManager.
	 *
	 * @param HooksManager $hooks The hooks manager instance.
	 * @return void
	 */
	public function register_hooks( HooksManager $hooks ): void {
		// Clear transients when a product is updated, deleted, or stock changes.
		$hooks->add_action( 'save_post_product', $this, 'flush_product_transients' );
		$hooks->add_action( 'woocommerce_reduce_order_stock', $this, 'flush_product_transients' );
	}

	/**
	 * Wrapper for wc_get_products that implements transient caching.
	 * Use this method in custom blocks instead of wc_get_products directly.
	 *
	 * @param array<string, mixed> $query_args The product query arguments.
	 * @return array<int, \WC_Product> Array of product objects.
	 */
	public function get_cached_products( array $query_args ): array {
		// Create a unique hash based on the query arguments.
		$transient_key = 'mc_products_' . md5( wp_json_encode( $query_args ) );
		$cached_data   = get_transient( $transient_key );

		if ( false !== $cached_data ) {
			return $cached_data;
		}

		// Cache miss: run the actual database query.
		$products = wc_get_products( $query_args );

		// Only cache if the object cache is active (e.g., Redis/Memcached on hPanel).
		// Storing massive product objects in the default `wp_options` table degrades performance.
		if ( wp_using_ext_object_cache() ) {
			set_transient( $transient_key, $products, self::CACHE_TTL );
		}

		return $products;
	}

	/**
	 * Flush all product-related transients when inventory or data changes.
	 *
	 * @return void
	 */
	public function flush_product_transients(): void {
		global $wpdb;
		// Delete all transients that begin with our custom prefix.
		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_mc_products_%'" );
		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_mc_products_%'" );
		
		// Also flush the object cache if Redis/Memcached is configured.
		if ( wp_using_ext_object_cache() ) {
			wp_cache_flush();
		}
	}
}