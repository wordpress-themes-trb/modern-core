<?php
/**
 * Shared block features: universal design controls, entrance animations,
 * block width modes and pattern categories.
 *
 * @package ModernCore\Core
 */

declare(strict_types=1);

namespace ModernCore\Core;

/**
 * Adds the same typography, spacing and colour controls to every Modern Core
 * block, the "Entrance Animation" panel (assets/src/js/editor.jsx) and its
 * front-end output, the block width stylesheet and the pattern categories.
 */
class EditorFeatures {

	/**
	 * Attach hooks.
	 *
	 * @param HooksManager $hooks Hooks manager.
	 */
	public function register_hooks( HooksManager $hooks ): void {
		$hooks->add_filter( 'register_block_type_args', $this, 'add_universal_supports', 10, 2 );
		$hooks->add_filter( 'render_block', $this, 'add_animation_attributes', 10, 2 );
		$hooks->add_action( 'enqueue_block_editor_assets', $this, 'enqueue_editor_assets' );
		// Front end and the editor canvas (iframe) both get the width styles.
		$hooks->add_action( 'enqueue_block_assets', $this, 'enqueue_block_widths' );
		$hooks->add_action( 'init', $this, 'register_pattern_categories' );
	}

	/**
	 * Typography, spacing and colour controls for every modern-core/* block.
	 *
	 * @param array<string, mixed> $args       Block type arguments.
	 * @param string               $block_type Block name.
	 * @return array<string, mixed>
	 */
	public function add_universal_supports( array $args, string $block_type ): array {
		if ( 0 !== strpos( $block_type, 'modern-core/' ) ) {
			return $args;
		}
		$args['supports'] = $args['supports'] ?? [];

		$args['supports']['spacing'] = [
			'margin'   => true,
			'padding'  => true,
			'blockGap' => true,
		];
		// Many blocks have their own textColor / backgroundColor / fontSize attributes
		// (hex colours, pixel numbers). WordPress's colour and typography supports use
		// the same attribute names for palette slugs, so only add a support where the
		// block doesn't already own that attribute.
		$own = $args['attributes'] ?? [];

		$typography = [
			'lineHeight'                   => true,
			'__experimentalFontFamily'     => true,
			'__experimentalFontWeight'     => true,
			'__experimentalFontStyle'      => true,
			'__experimentalTextTransform'  => true,
			'__experimentalLetterSpacing'  => true,
			'__experimentalTextDecoration' => true,
		];
		if ( ! isset( $own['fontSize'] ) ) {
			$typography['fontSize'] = true;
		}
		$args['supports']['typography'] = array_merge( $typography, (array) ( $args['supports']['typography'] ?? [] ) );

		$color = [
			'text'       => ! isset( $own['textColor'] ),
			'background' => ! isset( $own['backgroundColor'] ),
			'gradients'  => ! isset( $own['gradient'] ) && ! isset( $own['backgroundColor'] ),
		];
		if ( isset( $args['supports']['color'] ) ) {
			$args['supports']['color'] = array_merge( $color, (array) $args['supports']['color'] );
		} elseif ( $color['text'] || $color['background'] ) {
			$args['supports']['color'] = $color;
		}

		// Animation settings saved by the editor panel.
		$args['attributes'] = array_merge(
			$args['attributes'] ?? [],
			[
				'mcAnimation'    => [ 'type' => 'string', 'default' => 'none' ],
				'mcAnimDuration' => [ 'type' => 'number', 'default' => 800 ],
				'mcAnimDelay'    => [ 'type' => 'number', 'default' => 0 ],
				'mcAnimOffset'   => [ 'type' => 'number', 'default' => 50 ],
			]
		);

		return $args;
	}

	/**
	 * Add entrance-animation data attributes to the block's first element.
	 *
	 * Uses data-mc-aos-* attributes; assets/src/js/main.js renames them to
	 * data-aos-* before starting AOS. If scripts don't run, nothing is hidden.
	 *
	 * @param string               $content Rendered block.
	 * @param array<string, mixed> $block   Parsed block.
	 */
	public function add_animation_attributes( string $content, array $block ): string {
		$name = $block['blockName'] ?? '';
		if ( ! is_string( $name ) || 0 !== strpos( $name, 'modern-core/' ) || '' === trim( $content ) ) {
			return $content;
		}

		$allowed   = [ 'fade-up', 'fade-down', 'fade-left', 'fade-right', 'zoom-in', 'zoom-out', 'fade' ];
		$animation = (string) ( $block['attrs']['mcAnimation'] ?? 'none' );
		if ( ! in_array( $animation, $allowed, true ) ) {
			return $content;
		}

		$payload = sprintf(
			' data-mc-aos="%1$s" data-mc-aos-duration="%2$d" data-mc-aos-delay="%3$d" data-mc-aos-offset="%4$d"',
			esc_attr( $animation ),
			max( 0, min( 5000, (int) ( $block['attrs']['mcAnimDuration'] ?? 800 ) ) ),
			max( 0, min( 5000, (int) ( $block['attrs']['mcAnimDelay'] ?? 0 ) ) ),
			max( 0, min( 1000, (int) ( $block['attrs']['mcAnimOffset'] ?? 50 ) ) )
		);

		// First real element: skip <style>, <script>, <link> and comments.
		return (string) preg_replace(
			'/<(?!\/|!|style\b|script\b|link\b)([a-zA-Z][a-zA-Z0-9\-]*)/',
			'<$1' . $payload,
			$content,
			1
		);
	}

	/**
	 * Editor-only script (Entrance Animation panel) and block width styles.
	 */
	public function enqueue_editor_assets(): void {
		$js = MODERN_CORE_DIR . '/assets/dist/js/editorJs.js';
		if ( is_file( $js ) ) {
			wp_enqueue_script(
				'modern-core-editor-globals',
				MODERN_CORE_URI . '/assets/dist/js/editorJs.js',
				[ 'wp-blocks', 'wp-element', 'wp-components', 'wp-hooks', 'wp-compose', 'wp-block-editor', 'wp-i18n' ],
				(string) filemtime( $js ),
				true
			);
		}
	}

	/**
	 * Width modes (contained / wide / full width) used by the blocks' "Width" setting.
	 */
	public function enqueue_block_widths(): void {
		$css = MODERN_CORE_DIR . '/assets/css/block-widths.css';
		if ( is_file( $css ) ) {
			wp_enqueue_style( 'mc-block-widths', MODERN_CORE_URI . '/assets/css/block-widths.css', [], (string) filemtime( $css ) );
		}
	}

	/**
	 * Pattern inserter categories used by patterns/*.php.
	 */
	public function register_pattern_categories(): void {
		register_block_pattern_category( 'modern-core-sections', [ 'label' => __( 'Modern Core Sections', 'modern-core' ) ] );
		register_block_pattern_category( 'commerce', [ 'label' => __( 'Commerce & Shop', 'modern-core' ) ] );
		register_block_pattern_category( 'modern-core-starter-compositions', [ 'label' => __( 'Modern Core Full Layouts', 'modern-core' ) ] );
	}
}
