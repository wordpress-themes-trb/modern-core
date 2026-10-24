<?php
/**
 * Back-in-stock waitlist.
 *
 * @package ModernCore\Store
 */

declare(strict_types=1);

namespace ModernCore\Store;

use ModernCore\Core\HooksManager;
use ModernCore\Core\ThemeSettings;
use ModernCore\Core\ThemeSetup;

/**
 * Collects waitlist signups from the Back In Stock block and emails everyone
 * on the list when the product (or variation) comes back in stock.
 */
class BackInStock {

	private const META_KEY = '_mc_bis_waitlist';

	/**
	 * Attach hooks.
	 *
	 * @param HooksManager $hooks Hooks manager.
	 */
	public function register_hooks( HooksManager $hooks ): void {
		$hooks->add_action( 'wp_ajax_mc_back_in_stock_ajax', $this, 'submit' );
		$hooks->add_action( 'wp_ajax_nopriv_mc_back_in_stock_ajax', $this, 'submit' );
		$hooks->add_action( 'woocommerce_product_set_stock_status', $this, 'maybe_notify', 10, 3 );
		$hooks->add_action( 'woocommerce_variation_set_stock_status', $this, 'maybe_notify', 10, 3 );
	}

	/**
	 * AJAX: join the waitlist.
	 */
	public function submit(): void {
		check_ajax_referer( ThemeSetup::AJAX_NONCE, 'nonce' );

		$product_id = isset( $_POST['variation_id'] ) && absint( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : ( isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0 );
		$email      = isset( $_POST['bis_email'] ) ? sanitize_email( wp_unslash( $_POST['bis_email'] ) ) : '';
		$name       = isset( $_POST['bis_name'] ) ? sanitize_text_field( wp_unslash( $_POST['bis_name'] ) ) : '';
		$product    = $product_id && function_exists( 'wc_get_product' ) ? wc_get_product( $product_id ) : null;

		if ( ! $product || ! is_email( $email ) ) {
			wp_send_json_error( [ 'message' => __( 'Please enter a valid email address.', 'modern-core' ) ] );
		}

		$waitlist = get_post_meta( $product_id, self::META_KEY, true );
		$waitlist = is_array( $waitlist ) ? $waitlist : [];

		foreach ( $waitlist as $entry ) {
			if ( isset( $entry['email'] ) && strtolower( $entry['email'] ) === strtolower( $email ) ) {
				wp_send_json_success();
			}
		}

		if ( count( $waitlist ) >= 2000 ) {
			wp_send_json_error( [ 'message' => __( 'The waitlist for this item is full.', 'modern-core' ) ] );
		}

		$waitlist[] = [
			'email' => $email,
			'name'  => $name,
			'time'  => time(),
		];
		update_post_meta( $product_id, self::META_KEY, $waitlist );

		wp_send_json_success();
	}

	/**
	 * Email the waitlist when stock returns.
	 *
	 * @param int         $product_id   Product or variation ID.
	 * @param string      $stock_status New stock status.
	 * @param \WC_Product $product      Product.
	 */
	public function maybe_notify( $product_id, $stock_status, $product = null ): void {
		if ( 'instock' !== $stock_status ) {
			return;
		}

		$product_id = (int) $product_id;
		$waitlist   = get_post_meta( $product_id, self::META_KEY, true );
		if ( empty( $waitlist ) || ! is_array( $waitlist ) ) {
			return;
		}

		$product = $product instanceof \WC_Product ? $product : wc_get_product( $product_id );
		if ( ! $product ) {
			return;
		}

		// Clear first so a second stock update can never send duplicates.
		delete_post_meta( $product_id, self::META_KEY );

		$replace = [
			'{product_name}' => wp_strip_all_tags( $product->get_name() ),
			'{product_url}'  => $product->get_permalink(),
			'{site_name}'    => wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ),
		];
		$subject = strtr( ThemeSettings::get( 'bis_subject' ), $replace );

		foreach ( $waitlist as $entry ) {
			if ( empty( $entry['email'] ) || ! is_email( $entry['email'] ) ) {
				continue;
			}
			$body = strtr( ThemeSettings::get( 'bis_body' ), $replace + [ '{customer_name}' => $entry['name'] ?? '' ] );
			wp_mail( $entry['email'], $subject, $body );
		}
	}
}
