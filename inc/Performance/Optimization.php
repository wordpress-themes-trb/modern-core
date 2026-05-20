<?php
/**
 * Frontend Performance Optimization.
 *
 * @package ModernCore\Performance
 */

declare(strict_types=1);

namespace ModernCore\Performance;

use ModernCore\Core\HooksManager;

/**
 * Handles font preloading, resource hints, and stripping legacy WordPress bloat.
 */
class Optimization {

	/**
	 * Register hooks with the centralized HooksManager.
	 *
	 * @param HooksManager $hooks The hooks manager instance.
	 * @return void
	 */
	public function register_hooks( HooksManager $hooks ): void {
		$hooks->add_action( 'init', $this, 'disable_emojis' );
		$hooks->add_action( 'wp_head', $this, 'preload_essential_assets', 1 );
		$hooks->add_filter( 'style_loader_tag', $this, 'defer_non_critical_css', 10, 2 );
	}

	/**
	 * Remove legacy emoji scripts and styles to save HTTP requests.
	 * Modern operating systems support emojis natively.
	 *
	 * @return void
	 */
	public function disable_emojis(): void {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	}

	/**
	 * Preload critical assets like primary fonts to improve LCP.
	 *
	 * @return void
	 */
	public function preload_essential_assets(): void {
		// Example: Preload a locally hosted variable font.
		// echo '<link rel="preload" href="' . esc_url( get_template_directory_uri() . '/assets/dist/fonts/inter-variable.woff2' ) . '" as="font" type="font/woff2" crossorigin="anonymous">';
		
		// Preconnect to essential external domains if necessary (e.g., payment gateways).
		echo '<link rel="preconnect" href="https://js.stripe.com" crossorigin>';
	}

	/**
	 * Add 'media="print" onload="this.media=\'all\'"' to non-critical stylesheets
	 * to prevent them from render-blocking the initial page load.
	 *
	 * @param string $html   The link tag for the enqueue style.
	 * @param string $handle The style's registered handle.
	 * @return string Modified link tag.
	 */
	public function defer_non_critical_css( string $html, string $handle ): string {
		// Array of styles to defer. Add plugin styles here as needed.
		$deferred_styles = [ 'wp-block-library', 'wc-blocks-style' ];

		if ( in_array( $handle, $deferred_styles, true ) ) {
			$html = str_replace( "media='all'", "media='print' onload=\"this.media='all'\"", $html );
		}

		return $html;
	}
}