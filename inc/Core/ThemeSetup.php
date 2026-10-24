<?php
/**
 * Theme supports, global assets and front-end helpers.
 *
 * @package ModernCore\Core
 */

declare(strict_types=1);

namespace ModernCore\Core;

/**
 * Sets up theme supports, enqueues the global stylesheet and script, exposes
 * the AJAX config to block scripts and preloads the front-page hero image.
 */
class ThemeSetup {

	/**
	 * Nonce action shared by the theme's AJAX endpoints.
	 */
	public const AJAX_NONCE = 'modern_core_ajax';

	/**
	 * Attach hooks.
	 *
	 * @param HooksManager $hooks Hooks manager.
	 */
	public function register_hooks( HooksManager $hooks ): void {
		$hooks->add_action( 'after_setup_theme', $this, 'setup' );
		$hooks->add_action( 'wp_enqueue_scripts', $this, 'enqueue_assets' );
		$hooks->add_action( 'wp_head', $this, 'print_ajax_config', 2 );
		$hooks->add_action( 'wp_head', $this, 'preload_front_page_hero', 1 );
		$hooks->add_action( 'wp_footer', $this, 'print_recently_viewed_tracker' );
		$hooks->add_filter( 'woocommerce_enqueue_styles', $this, 'disable_woocommerce_styles' );
	}

	/**
	 * Theme supports.
	 */
	public function setup(): void {
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'editor-styles' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );
		add_theme_support(
			'woocommerce',
			[
				'thumbnail_image_width' => 600,
				'single_image_width'    => 800,
			]
		);
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );

		// Keep the pattern inserter focused on the theme's own layouts.
		remove_theme_support( 'core-block-patterns' );

		if ( is_file( MODERN_CORE_DIR . '/assets/dist/css/editor.css' ) ) {
			add_editor_style( 'assets/dist/css/editor.css' );
		}
	}

	/**
	 * The theme's blocks style themselves, so WooCommerce's legacy CSS is not needed.
	 *
	 * @return array<string, mixed>
	 */
	public function disable_woocommerce_styles(): array {
		return [];
	}

	/**
	 * Global stylesheet and script.
	 */
	public function enqueue_assets(): void {
		$css = MODERN_CORE_DIR . '/assets/dist/css/style.css';
		if ( is_file( $css ) ) {
			wp_enqueue_style( 'modern-core-style', MODERN_CORE_URI . '/assets/dist/css/style.css', [], (string) filemtime( $css ) );
			wp_style_add_data( 'modern-core-style', 'path', $css );
		}

		// Styles bundled with main.js (the AOS entrance animations).
		$main_css = MODERN_CORE_DIR . '/assets/dist/css/main.css';
		if ( is_file( $main_css ) ) {
			wp_enqueue_style( 'modern-core-main', MODERN_CORE_URI . '/assets/dist/css/main.css', [], (string) filemtime( $main_css ) );
		}

		$js = MODERN_CORE_DIR . '/assets/dist/js/main.js';
		if ( is_file( $js ) ) {
			wp_enqueue_script(
				'modern-core-main',
				MODERN_CORE_URI . '/assets/dist/js/main.js',
				[],
				(string) filemtime( $js ),
				[
					'in_footer' => true,
					'strategy'  => 'defer',
				]
			);
		}

		// Blocks such as the mini cart and shipping bar use jQuery for cart fragments.
		if ( class_exists( 'WooCommerce' ) ) {
			wp_enqueue_script( 'jquery' );
		}
	}

	/**
	 * Expose the AJAX URL and nonce to block scripts as window.mc_ajax.
	 *
	 * Printed in the head because block scripts are printed inline in the body.
	 */
	public function print_ajax_config(): void {
		$config = [
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( self::AJAX_NONCE ),
			'shopUrl' => function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' ),
			'cartUrl' => function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/' ),
			'i18n'    => [
				'wishAdded'      => __( 'Saved to your wishlist', 'modern-core' ),
				'wishRemoved'    => __( 'Removed from your wishlist', 'modern-core' ),
				'wishFull'       => __( 'Your wishlist is full', 'modern-core' ),
				'compareAdded'   => __( 'Added to compare', 'modern-core' ),
				'compareRemoved' => __( 'Removed from compare', 'modern-core' ),
				'compareFull'    => __( 'You can compare up to 4 products', 'modern-core' ),
			],
		];
		wp_print_inline_script_tag(
			// "mc-js" lets CSS hide content that a script will animate in, only when scripts run.
			'document.documentElement.classList.add("mc-js");window.mc_ajax = ' . wp_json_encode( $config ) . ';',
			[ 'id' => 'modern-core-ajax-config' ]
		);
	}

	/**
	 * Preload the front-page hero image so it is the first image the browser fetches (LCP).
	 */
	public function preload_front_page_hero(): void {
		if ( ! is_front_page() ) {
			return;
		}

		$url = $this->find_hero_image( $this->front_page_blocks() );
		if ( '' === $url ) {
			return;
		}

		printf( '<link rel="preload" as="image" href="%s" fetchpriority="high">' . "\n", esc_url( $url ) );
	}

	/**
	 * Parsed blocks of the front page: the edited template if any, then the page itself.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	private function front_page_blocks(): array {
		$template = get_block_template( get_stylesheet() . '//front-page' );
		$content  = $template && ! empty( $template->content ) ? $template->content : '';

		if ( '' === $content && 'page' === get_option( 'show_on_front' ) ) {
			$page    = get_post( (int) get_option( 'page_on_front' ) );
			$content = $page ? $page->post_content : '';
		}

		return '' === $content ? [] : parse_blocks( $content );
	}

	/**
	 * Depth-first search for the first hero-type block with a background image.
	 *
	 * @param array<int, array<string, mixed>> $blocks Parsed blocks.
	 */
	private function find_hero_image( array $blocks ): string {
		$hero_blocks = [ 'modern-core/hero', 'modern-core/video-hero', 'modern-core/split-banner', 'modern-core/slide' ];

		foreach ( $blocks as $block ) {
			$name  = $block['blockName'] ?? '';
			$attrs = $block['attrs'] ?? [];

			if ( in_array( $name, $hero_blocks, true ) ) {
				foreach ( [ 'bgImageUrl', 'posterUrl', 'mediaUrl' ] as $key ) {
					if ( ! empty( $attrs[ $key ] ) && is_string( $attrs[ $key ] ) ) {
						return $attrs[ $key ];
					}
				}
			}

			if ( 'core/template-part' === $name ) {
				continue;
			}

			if ( ! empty( $block['innerBlocks'] ) ) {
				$found = $this->find_hero_image( $block['innerBlocks'] );
				if ( '' !== $found ) {
					return $found;
				}
			}
		}

		return '';
	}

	/**
	 * Record product views in localStorage for the Recently Viewed block.
	 */
	public function print_recently_viewed_tracker(): void {
		if ( ! function_exists( 'is_product' ) || ! is_product() ) {
			return;
		}

		$product_id = (int) get_queried_object_id();
		$script     = sprintf(
			'(function(){var id=%d,h=[];try{h=JSON.parse(localStorage.getItem("mc_recently_viewed")||"[]");}catch(e){}'
			. 'if(!Array.isArray(h)){h=[];}h=h.filter(function(i){return i&&i.id!==id;});h.unshift({id:id,time:Date.now()});'
			. 'try{localStorage.setItem("mc_recently_viewed",JSON.stringify(h.slice(0,20)));}catch(e){}})();',
			$product_id
		);
		wp_print_inline_script_tag( $script, [ 'id' => 'modern-core-recently-viewed' ] );
	}
}
