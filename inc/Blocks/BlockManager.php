<?php
/**
 * Block registration.
 *
 * @package ModernCore\Blocks
 */

declare(strict_types=1);

namespace ModernCore\Blocks;

use ModernCore\Core\HooksManager;

/**
 * Registers every block in blocks/ together with its editor script.
 *
 * A block folder needs a block.json. Its editor script handle is always
 * "modern-core-{folder}-js" and points at the Vite build in
 * assets/dist/blocks/{folder}/index.js, so adding a block never requires
 * editing PHP.
 */
class BlockManager {

	/**
	 * Script dependencies shared by every block's editor bundle.
	 */
	private const EDITOR_DEPS = [ 'wp-blocks', 'wp-element', 'wp-components', 'wp-block-editor', 'wp-i18n', 'wp-data', 'wp-api-fetch' ];

	/**
	 * Attach hooks.
	 *
	 * @param HooksManager $hooks Hooks manager.
	 */
	public function register_hooks( HooksManager $hooks ): void {
		$hooks->add_action( 'init', $this, 'register_blocks' );
		$hooks->add_filter( 'block_categories_all', $this, 'register_block_category' );
		$hooks->add_filter( 'render_block', $this, 'move_inline_scripts', 10, 2 );
		$hooks->add_action( 'wp_footer', $this, 'print_moved_scripts', 20 );
	}

	/**
	 * Inline scripts collected from rendered blocks, keyed by content hash.
	 *
	 * @var array<string, string>
	 */
	private array $scripts = [];

	/**
	 * Move a block's inline <script> tags to the footer.
	 *
	 * Block templates pass through wptexturize(), which mistakes a "<" inside
	 * an inline script for the start of an HTML tag and turns the "&&" after
	 * it into "&#038;&#038;", breaking the script. Printing the scripts from
	 * wp_footer keeps them out of that filter. The block's HTML is already in
	 * the page by then, so the scripts find their elements as before.
	 *
	 * @param string               $html  Rendered block.
	 * @param array<string, mixed> $block Parsed block.
	 */
	public function move_inline_scripts( string $html, array $block ): string {
		if ( empty( $block['blockName'] ) || 0 !== strpos( $block['blockName'], 'modern-core/' ) ) {
			return $html;
		}
		if ( false === stripos( $html, '<script' ) || is_admin() || wp_is_json_request() || did_action( 'wp_footer' ) ) {
			return $html;
		}

		return (string) preg_replace_callback(
			'#<script\b([^>]*)>(.*?)</script>#is',
			function ( array $m ): string {
				// Leave external scripts and data blocks (JSON, templates) where they are.
				if ( preg_match( '#\bsrc\s*=|\btype\s*=\s*["\']?(?!text/javascript|module)[^"\'\s>]+#i', $m[1] ) ) {
					return $m[0];
				}
				$this->scripts[ md5( $m[2] ) ] = $m[2];
				return '';
			},
			$html
		);
	}

	/**
	 * Print the collected block scripts.
	 */
	public function print_moved_scripts(): void {
		foreach ( $this->scripts as $hash => $code ) {
			wp_print_inline_script_tag( $code, [ 'id' => 'mc-block-' . substr( $hash, 0, 10 ) ] );
		}
		$this->scripts = [];
	}

	/**
	 * Absolute path to the blocks folder.
	 */
	public static function blocks_dir(): string {
		return MODERN_CORE_DIR . '/blocks';
	}

	/**
	 * Register editor scripts first, then each block from its block.json.
	 */
	public function register_blocks(): void {
		$dir = self::blocks_dir();
		if ( ! is_dir( $dir ) ) {
			return;
		}

		$folders = array_filter(
			(array) scandir( $dir ),
			static fn( $name ) => '.' !== $name[0] && is_file( $dir . '/' . $name . '/block.json' )
		);

		foreach ( $folders as $folder ) {
			$bundle = MODERN_CORE_DIR . '/assets/dist/blocks/' . $folder . '/index.js';
			if ( is_file( $bundle ) ) {
				wp_register_script(
					'modern-core-' . $folder . '-js',
					MODERN_CORE_URI . '/assets/dist/blocks/' . $folder . '/index.js',
					self::EDITOR_DEPS,
					(string) filemtime( $bundle ),
					true
				);
				wp_set_script_translations( 'modern-core-' . $folder . '-js', 'modern-core', MODERN_CORE_DIR . '/languages' );
			}
		}

		foreach ( $folders as $folder ) {
			register_block_type( $dir . '/' . $folder );
		}
	}

	/**
	 * Add the "Modern Core Blocks" inserter category.
	 *
	 * @param array<int, array<string, string>> $categories Existing categories.
	 * @return array<int, array<string, string>>
	 */
	public function register_block_category( array $categories ): array {
		array_unshift(
			$categories,
			[
				'slug'  => 'modern-core',
				'title' => __( 'Modern Core Blocks', 'modern-core' ),
			]
		);
		return $categories;
	}
}
