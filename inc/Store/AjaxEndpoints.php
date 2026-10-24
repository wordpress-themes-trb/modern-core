<?php
/**
 * Storefront AJAX endpoints used by the theme's blocks.
 *
 * @package ModernCore\Store
 */

declare(strict_types=1);

namespace ModernCore\Store;

use ModernCore\Core\HooksManager;
use ModernCore\Core\ThemeSetup;

/**
 * Quick view, recently viewed, wishlist, bundle add-to-cart and cart totals.
 *
 * Read-only endpoints are public so they keep working on cached pages.
 * Endpoints that change data (bundle add-to-cart) require the theme nonce.
 */
class AjaxEndpoints {

	/**
	 * Most product IDs a single request may ask for.
	 */
	private const MAX_IDS = 40;

	/**
	 * Attach hooks.
	 *
	 * @param HooksManager $hooks Hooks manager.
	 */
	public function register_hooks( HooksManager $hooks ): void {
		$endpoints = [
			'mc_quickview_ajax'       => 'quick_view',
			'mc_recent_products_ajax' => 'recent_products',
			'mc_wishlist_ajax'        => 'wishlist',
			'mc_bundle_add_ajax'      => 'bundle_add',
			'mc_get_cart_subtotal'    => 'cart_subtotal',
			'mc_get_cart_count'       => 'cart_count',
		];
		foreach ( $endpoints as $action => $method ) {
			$hooks->add_action( 'wp_ajax_' . $action, $this, $method );
			$hooks->add_action( 'wp_ajax_nopriv_' . $action, $this, $method );
		}
	}

	/**
	 * Read a list of product IDs posted as a JSON array.
	 *
	 * @param string $key POST field.
	 * @return int[]
	 */
	private function posted_ids( string $key ): array {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- decoded then cast to ints below.
		$raw = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : '[]';
		$ids = json_decode( is_string( $raw ) ? $raw : '[]', true );
		if ( ! is_array( $ids ) ) {
			return [];
		}
		$ids = array_map( static fn( $id ) => absint( is_array( $id ) ? ( $id['id'] ?? 0 ) : $id ), $ids );
		return array_slice( array_values( array_unique( array_filter( $ids ) ) ), 0, self::MAX_IDS );
	}

	/**
	 * Read a posted flag ("1", "true").
	 *
	 * @param string $key POST field.
	 */
	private function posted_flag( string $key ): bool {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$value = isset( $_POST[ $key ] ) ? sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) : '';
		return in_array( $value, [ '1', 'true', 'yes' ], true );
	}

	/**
	 * Quick view modal content.
	 */
	public function quick_view(): void {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
		$product    = $product_id && function_exists( 'wc_get_product' ) ? wc_get_product( $product_id ) : null;

		if ( ! $product || 'publish' !== $product->get_status() || ! $product->is_visible() ) {
			wp_send_json_error( [ 'message' => __( 'Product not found.', 'modern-core' ) ], 404 );
		}

		global $post;
		$post = get_post( $product_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- needed by WooCommerce template functions.
		setup_postdata( $post );
		$GLOBALS['product'] = $product;

		$image_html = '<div class="mc-qv-image">' . $product->get_image( 'woocommerce_single', [ 'style' => 'width:100%;height:auto;border-radius:8px;' ] ) . '</div>';

		ob_start();
		?>
		<h2 class="mc-qv-title" style="font-size:24px;margin:0 0 10px;"><?php echo esc_html( $product->get_name() ); ?></h2>
		<?php if ( $product->get_rating_count() > 0 ) : ?>
			<div class="mc-qv-rating" style="color:#f59e0b;font-size:14px;margin-bottom:15px;"><?php echo wp_kses_post( wc_get_rating_html( (float) $product->get_average_rating() ) ); ?></div>
		<?php endif; ?>
		<div class="mc-qv-price" style="font-size:20px;font-weight:700;margin-bottom:15px;"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
		<div class="mc-qv-desc" style="font-size:14px;opacity:.75;margin-bottom:20px;line-height:1.5;"><?php echo wp_kses_post( apply_filters( 'woocommerce_short_description', $product->get_short_description() ) ); ?></div>
		<?php woocommerce_template_single_add_to_cart(); ?>
		<div style="text-align:center;margin-top:15px;">
			<a href="<?php echo esc_url( $product->get_permalink() ); ?>" style="font-size:13px;color:inherit;text-decoration:underline;"><?php esc_html_e( 'View full details', 'modern-core' ); ?></a>
		</div>
		<?php
		$content_html = (string) ob_get_clean();
		wp_reset_postdata();

		wp_send_json_success(
			[
				'image_html'   => $image_html,
				'content_html' => $content_html,
			]
		);
	}

	/**
	 * Product cards for the Recently Viewed block, or a fallback list.
	 */
	public function recent_products(): void {
		if ( ! class_exists( 'WooCommerce' ) ) {
			wp_send_json_error();
		}

		$args = [
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'no_found_rows'  => true,
			'tax_query'      => $this->visible_products_tax_query(), // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		];

		if ( 'yes' !== get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
			$args['meta_query'] = [ [ 'key' => '_stock_status', 'value' => 'outofstock', 'compare' => '!=' ] ]; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
		}

		if ( $this->posted_flag( 'is_fallback' ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Missing
			$fallback               = isset( $_POST['fallback_query'] ) ? sanitize_key( wp_unslash( $_POST['fallback_query'] ) ) : 'popularity';
			$args['posts_per_page'] = 8;
			if ( 'sale' === $fallback ) {
				$args['post__in'] = array_merge( [ 0 ], wc_get_product_ids_on_sale() );
			} elseif ( 'newest' === $fallback || 'date' === $fallback ) {
				$args['orderby'] = 'date';
			} else {
				$args['meta_key'] = 'total_sales'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				$args['orderby']  = 'meta_value_num';
			}
		} else {
			$ids = $this->posted_ids( 'product_ids' );
			if ( ! $ids ) {
				wp_send_json_error();
			}
			$args['post__in']       = $ids;
			$args['orderby']        = 'post__in';
			$args['posts_per_page'] = count( $ids );
		}

		$query = new \WP_Query( $args );
		if ( ! $query->have_posts() ) {
			wp_send_json_error();
		}

		ob_start();
		while ( $query->have_posts() ) {
			$query->the_post();
			$product = wc_get_product( get_the_ID() );
			if ( ! $product ) {
				continue;
			}
			?>
			<div <?php wc_product_class( 'mc-product-card', $product ); ?> style="background:#fff;border-radius:8px;border:1px solid rgba(0,0,0,.05);overflow:hidden;display:flex;flex-direction:column;">
				<a href="<?php echo esc_url( $product->get_permalink() ); ?>" style="display:block;width:100%;aspect-ratio:3/4;">
					<?php echo $product->get_image( 'woocommerce_thumbnail', [ 'style' => 'width:100%;height:100%;object-fit:cover;', 'loading' => 'lazy' ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core image markup. ?>
				</a>
				<div style="padding:15px;display:flex;flex-direction:column;flex:1;">
					<a href="<?php echo esc_url( $product->get_permalink() ); ?>" style="font-size:14px;font-weight:700;text-decoration:none;color:inherit;margin-bottom:10px;"><?php echo esc_html( $product->get_name() ); ?></a>
					<div style="margin-top:auto;font-weight:700;"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
				</div>
			</div>
			<?php
		}
		$html = (string) ob_get_clean();
		wp_reset_postdata();

		wp_send_json_success( [ 'html' => $html ] );
	}

	/**
	 * Product cards for the Wishlist block (IDs come from the visitor's localStorage).
	 */
	public function wishlist(): void {
		if ( ! class_exists( 'WooCommerce' ) ) {
			wp_send_json_error();
		}

		$ids = $this->posted_ids( 'product_ids' );
		if ( ! $ids ) {
			wp_send_json_error();
		}

		$show_stock = $this->posted_flag( 'show_stock' );
		$show_price = $this->posted_flag( 'show_price' );

		$query = new \WP_Query(
			[
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'post__in'       => $ids,
				'orderby'        => 'post__in',
				'posts_per_page' => count( $ids ),
				'no_found_rows'  => true,
			]
		);
		if ( ! $query->have_posts() ) {
			wp_send_json_error();
		}

		ob_start();
		while ( $query->have_posts() ) {
			$query->the_post();
			$product = wc_get_product( get_the_ID() );
			if ( ! $product ) {
				continue;
			}
			$GLOBALS['product'] = $product;
			$is_oos             = ! $product->is_in_stock();
			$has_drop           = $show_price && $product->is_on_sale();
			?>
			<div class="mc-wish-card <?php echo ( $show_stock && $is_oos ) ? 'is-oos' : ''; ?>">
				<button type="button" class="mc-wish-remove" data-id="<?php echo esc_attr( (string) $product->get_id() ); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: product name */ __( 'Remove %s from wishlist', 'modern-core' ), $product->get_name() ) ); ?>">&#x2715;</button>
				<a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="mc-wish-img-wrap">
					<?php echo $product->get_image( 'woocommerce_thumbnail', [ 'class' => 'mc-wish-img', 'loading' => 'lazy' ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core image markup. ?>
					<?php if ( $has_drop ) : ?>
						<span class="mc-badge-drop"><?php esc_html_e( 'PRICE DROP', 'modern-core' ); ?></span>
					<?php endif; ?>
					<?php if ( $show_stock && $is_oos ) : ?>
						<span class="mc-badge-oos"><?php esc_html_e( 'OUT OF STOCK', 'modern-core' ); ?></span>
					<?php endif; ?>
				</a>
				<div class="mc-wish-info">
					<a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="mc-wish-title"><?php echo esc_html( $product->get_name() ); ?></a>
					<div class="mc-wish-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
					<?php
					if ( ! $is_oos ) {
						woocommerce_template_loop_add_to_cart();
					} else {
						echo '<div style="padding:10px;background:#e2e8f0;color:#64748b;text-align:center;border-radius:4px;font-size:12px;font-weight:700;">' . esc_html__( 'Unavailable', 'modern-core' ) . '</div>';
					}
					?>
				</div>
			</div>
			<?php
		}
		$html = (string) ob_get_clean();
		wp_reset_postdata();

		wp_send_json_success( [ 'html' => $html ] );
	}

	/**
	 * Add several products to the cart at once (Bundle Upsell block).
	 */
	public function bundle_add(): void {
		check_ajax_referer( ThemeSetup::AJAX_NONCE, 'nonce' );

		if ( ! class_exists( 'WooCommerce' ) || ! WC()->cart ) {
			wp_send_json_error();
		}

		$added = false;
		foreach ( $this->posted_ids( 'product_ids' ) as $product_id ) {
			$product_id = (int) apply_filters( 'woocommerce_add_to_cart_product_id', $product_id );
			$product    = wc_get_product( $product_id );
			if ( ! $product || 'publish' !== $product->get_status() || ! $product->is_purchasable() || ! $product->is_in_stock() ) {
				continue;
			}
			// Variable products need a variation choice, so they are not added from a bundle.
			if ( $product->is_type( 'variable' ) ) {
				continue;
			}
			if ( apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, 1 ) && WC()->cart->add_to_cart( $product_id, 1 ) ) {
				$added = true;
			}
		}

		if ( ! $added ) {
			wp_send_json_error( [ 'message' => __( 'These items could not be added to your cart.', 'modern-core' ) ] );
		}

		// Return WooCommerce cart fragments so every mini cart on the page refreshes.
		ob_start();
		woocommerce_mini_cart();
		$mini_cart = (string) ob_get_clean();

		wp_send_json_success(
			[
				'fragments' => apply_filters(
					'woocommerce_add_to_cart_fragments',
					[ 'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>' ]
				),
				'cart_hash' => WC()->cart->get_cart_hash(),
				'count'     => (int) WC()->cart->get_cart_contents_count(),
			]
		);
	}

	/**
	 * Cart subtotal (Shipping Progress block).
	 */
	public function cart_subtotal(): void {
		if ( ! class_exists( 'WooCommerce' ) || ! WC()->cart ) {
			wp_send_json_error();
		}
		wp_send_json_success( [ 'subtotal' => (float) WC()->cart->get_subtotal() ] );
	}

	/**
	 * Cart item count (Mini Cart block).
	 */
	public function cart_count(): void {
		if ( ! class_exists( 'WooCommerce' ) || ! WC()->cart ) {
			wp_send_json_error();
		}
		wp_send_json_success( [ 'count' => (int) WC()->cart->get_cart_contents_count() ] );
	}

	/**
	 * Exclude products hidden from the catalog.
	 *
	 * @return array<int|string, mixed>
	 */
	private function visible_products_tax_query(): array {
		$hidden = get_term_by( 'slug', 'exclude-from-catalog', 'product_visibility' );
		if ( ! $hidden ) {
			return [];
		}
		return [
			[
				'taxonomy' => 'product_visibility',
				'field'    => 'term_taxonomy_id',
				'terms'    => [ $hidden->term_taxonomy_id ],
				'operator' => 'NOT IN',
			],
		];
	}
}
