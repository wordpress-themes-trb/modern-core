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
 * Strips legacy WordPress front-end extras that add requests without benefit.
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
		$hooks->add_filter( 'wp_resource_hints', $this, 'resource_hints', 10, 2 );
	}

	/**
	 * Remove the legacy emoji script and styles. Every current browser renders emoji natively.
	 */
	public function disable_emojis(): void {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_action( 'wp_enqueue_scripts', 'wp_enqueue_emoji_styles' );
		remove_action( 'admin_enqueue_scripts', 'wp_enqueue_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	}

	/**
	 * Drop the emoji CDN hint and let sites add their own via the filter.
	 *
	 * @param array<int, mixed> $urls          Hint URLs.
	 * @param string            $relation_type Hint type.
	 * @return array<int, mixed>
	 */
	public function resource_hints( array $urls, string $relation_type ): array {
		if ( 'dns-prefetch' === $relation_type ) {
			$urls = array_values(
				array_filter(
					$urls,
					static fn( $url ) => ! is_string( $url ) || false === strpos( $url, 's.w.org/images/core/emoji' )
				)
			);
		}
		return (array) apply_filters( 'modern_core_resource_hints', $urls, $relation_type );
	}
}
