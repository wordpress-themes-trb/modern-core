<?php
/**
 * Elementor compatibility.
 *
 * @package ModernCore\Elementor
 */

declare(strict_types=1);

namespace ModernCore\Elementor;

use ModernCore\Blocks\BlockManager;
use ModernCore\Core\HooksManager;

/**
 * Makes the theme editable with Elementor.
 *
 * - Every standalone theme block is available as an Elementor widget
 *   (see BlockWidget), grouped under "Modern Core" and "Modern Core Shop".
 * - Pages built with Elementor use the full-width template, so sections are
 *   not squeezed into the reading column; the front page switches too.
 * - header.php / footer.php (theme root) let Elementor's "Full Width"
 *   template show the theme header and footer.
 * - Elementor Pro Theme Builder headers and footers replace the theme's
 *   template parts when they are set.
 * - The theme's colours, fonts and content width are copied into Elementor's
 *   global settings once, and the self-hosted theme fonts are offered in
 *   Elementor's font list without loading them from Google.
 *
 * Every hook here only fires when Elementor is active, so the class is inert
 * otherwise.
 */
class ElementorIntegration {

	/**
	 * Blocks that are not offered as widgets: structure Elementor already
	 * provides (sections, columns, tabs), child blocks and the empty header shell.
	 */
	private const EXCLUDED = [ 'section-wrapper', 'columns-builder', 'column', 'content-tabs', 'tab-pane', 'slide', 'advanced-header' ];

	/**
	 * Blocks listed under "Modern Core Shop".
	 */
	private const SHOP_PATTERN = '/product|cart|checkout|categor|compare|wishlist|account|order|filter|shop|variation|stock|bundle|review|quick-view|recently|shipping|payment|sales|pagination/';

	/**
	 * Option that records which kit already received the theme's global styles.
	 */
	private const KIT_OPTION = 'modern_core_elementor_kit_synced';

	/**
	 * Attach hooks.
	 *
	 * @param HooksManager $hooks Hooks manager.
	 */
	public function register_hooks( HooksManager $hooks ): void {
		$hooks->add_action( 'elementor/elements/categories_registered', $this, 'register_categories' );
		$hooks->add_action( 'elementor/widgets/register', $this, 'register_widgets' );
		$hooks->add_filter( 'elementor/fonts/groups', $this, 'add_font_group' );
		$hooks->add_filter( 'elementor/fonts/additional_fonts', $this, 'add_theme_fonts' );
		$hooks->add_action( 'elementor/theme/register_locations', $this, 'register_locations' );
		$hooks->add_filter( 'render_block_core/template-part', $this, 'replace_template_part', 10, 2 );

		foreach ( [ 'page', 'single', 'frontpage' ] as $type ) {
			$hooks->add_filter( $type . '_template_hierarchy', $this, 'full_width_for_elementor' );
		}

		// Copy theme colours/fonts into Elementor once (after activation of either).
		$hooks->add_action( 'elementor/init', $this, 'maybe_sync_kit' );
	}

	/**
	 * Is Elementor loaded?
	 */
	public static function active(): bool {
		return did_action( 'elementor/loaded' ) > 0 && class_exists( '\Elementor\Plugin' );
	}

	/**
	 * Widget categories.
	 *
	 * @param \Elementor\Elements_Manager $elements_manager Elementor elements manager.
	 */
	public function register_categories( $elements_manager ): void {
		$elements_manager->add_category(
			'modern-core',
			[
				'title' => esc_html__( 'Modern Core', 'modern-core' ),
				'icon'  => 'eicon-theme-builder',
			]
		);
		$elements_manager->add_category(
			'modern-core-shop',
			[
				'title' => esc_html__( 'Modern Core Shop', 'modern-core' ),
				'icon'  => 'eicon-woocommerce',
			]
		);
	}

	/**
	 * Blocks offered as widgets: folder => block.json data.
	 *
	 * @return array<string, array<string, mixed>>
	 */
	public static function widget_blocks(): array {
		static $blocks = null;
		if ( null !== $blocks ) {
			return $blocks;
		}
		$blocks = [];
		$dir    = BlockManager::blocks_dir();
		foreach ( (array) glob( $dir . '/*/block.json' ) as $file ) {
			$folder = basename( dirname( (string) $file ) );
			$meta   = json_decode( (string) file_get_contents( (string) $file ), true );
			if ( ! is_array( $meta ) || in_array( $folder, self::EXCLUDED, true ) || ! empty( $meta['parent'] ) ) {
				continue;
			}
			$blocks[ $folder ] = $meta;
		}
		ksort( $blocks );
		return $blocks;
	}

	/**
	 * Which Elementor category a block belongs to.
	 *
	 * @param string $folder Block folder name.
	 */
	public static function category_for( string $folder ): string {
		return preg_match( self::SHOP_PATTERN, $folder ) ? 'modern-core-shop' : 'modern-core';
	}

	/**
	 * Register one widget per block.
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 */
	public function register_widgets( $widgets_manager ): void {
		foreach ( array_keys( self::widget_blocks() ) as $folder ) {
			if ( ! \WP_Block_Type_Registry::get_instance()->is_registered( 'modern-core/' . $folder ) ) {
				continue;
			}
			$widgets_manager->register( new BlockWidget( [], [ 'mc_block' => $folder ] ) );
		}
	}

	/**
	 * Is this post (or the page being previewed in Elementor) built with Elementor?
	 *
	 * @param int $post_id Post ID.
	 */
	public static function is_elementor_post( int $post_id ): bool {
		if ( ! self::active() || ! $post_id ) {
			return false;
		}
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only check of Elementor's preview flag.
		if ( isset( $_GET['elementor-preview'] ) && (int) $_GET['elementor-preview'] === $post_id ) {
			return true;
		}
		return 'builder' === get_post_meta( $post_id, '_elementor_edit_mode', true );
	}

	/**
	 * Use the full-width template for Elementor pages unless the editor
	 * picked a template for the page themselves.
	 *
	 * @param string[] $templates Template slugs in priority order.
	 * @return string[]
	 */
	public function full_width_for_elementor( array $templates ): array {
		$post_id = (int) get_queried_object_id();
		if ( ! is_singular() || ! self::is_elementor_post( $post_id ) ) {
			return $templates;
		}
		$chosen = (string) get_page_template_slug( $post_id );
		if ( '' !== $chosen && 'default' !== $chosen ) {
			return $templates;
		}
		array_unshift( $templates, 'page-full-width' );
		return $templates;
	}

	/**
	 * Elementor Pro Theme Builder: header and footer locations.
	 *
	 * @param \ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $manager Locations manager.
	 */
	public function register_locations( $manager ): void {
		$manager->register_core_location( 'header' );
		$manager->register_core_location( 'footer' );
	}

	/**
	 * Swap the theme header/footer template part for an Elementor Pro Theme
	 * Builder header/footer when one applies to the current page.
	 *
	 * @param string               $html  Rendered template part.
	 * @param array<string, mixed> $block Parsed block.
	 */
	public function replace_template_part( string $html, array $block ): string {
		if ( ! function_exists( 'elementor_theme_do_location' ) || is_admin() ) {
			return $html;
		}
		$slug = (string) ( $block['attrs']['slug'] ?? '' );
		$area = (string) ( $block['attrs']['area'] ?? $slug );
		$location = in_array( $area, [ 'header', 'footer' ], true ) ? $area : ( in_array( $slug, [ 'header', 'footer' ], true ) ? $slug : '' );
		if ( '' === $location ) {
			return $html;
		}
		ob_start();
		$done   = elementor_theme_do_location( $location );
		$output = (string) ob_get_clean();
		return $done ? $output : $html;
	}

	/**
	 * Theme fonts group in Elementor's font picker.
	 *
	 * @param array<string, string> $groups Font groups.
	 * @return array<string, string>
	 */
	public function add_font_group( array $groups ): array {
		return [ 'modern-core' => esc_html__( 'Theme fonts', 'modern-core' ) ] + $groups;
	}

	/**
	 * The theme's self-hosted fonts. Their group type is not one Elementor
	 * downloads, so they are served by the theme's own @font-face rules.
	 *
	 * @param array<string, string> $fonts Font name => group.
	 * @return array<string, string>
	 */
	public function add_theme_fonts( array $fonts ): array {
		foreach ( self::theme_font_families() as $family ) {
			$fonts[ $family['name'] ] = 'modern-core';
		}
		return $fonts;
	}

	/**
	 * Font families from theme.json: slug, first family name and full stack.
	 *
	 * @return array<int, array{slug: string, name: string, stack: string}>
	 */
	public static function theme_font_families(): array {
		$settings = wp_get_global_settings( [ 'typography', 'fontFamilies', 'theme' ] );
		$out      = [];
		foreach ( (array) $settings as $family ) {
			$stack = (string) ( $family['fontFamily'] ?? '' );
			$name  = trim( (string) strtok( $stack, ',' ), " '\"" );
			if ( '' !== $name ) {
				$out[] = [
					'slug'  => (string) ( $family['slug'] ?? '' ),
					'name'  => $name,
					'stack' => $stack,
				];
			}
		}
		return $out;
	}

	/**
	 * Copy the theme's palette, fonts and content width into Elementor's
	 * active kit, once per kit, so new Elementor designs match the theme.
	 * Kits the site owner already customised are left alone.
	 */
	public function maybe_sync_kit(): void {
		if ( ! self::active() || ! is_admin() || ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$kit_id = (int) get_option( 'elementor_active_kit' );
		if ( ! $kit_id || (int) get_option( self::KIT_OPTION ) === $kit_id ) {
			return;
		}
		$current = get_post_meta( $kit_id, '_elementor_page_settings', true );
		$current = is_array( $current ) ? $current : [];

		update_option( self::KIT_OPTION, $kit_id, false );
		if ( ! empty( $current['system_colors'] ) || ! empty( $current['custom_colors'] ) ) {
			return; // Already designed by someone; don't overwrite it.
		}

		$kit = \Elementor\Plugin::$instance->documents->get( $kit_id );
		if ( ! $kit ) {
			return;
		}
		$kit->update_settings( self::kit_settings() );
		if ( isset( \Elementor\Plugin::$instance->files_manager ) ) {
			\Elementor\Plugin::$instance->files_manager->clear_cache();
		}
	}

	/**
	 * Elementor kit settings that mirror theme.json.
	 *
	 * @return array<string, mixed>
	 */
	public static function kit_settings(): array {
		$palette = [];
		foreach ( (array) wp_get_global_settings( [ 'color', 'palette', 'theme' ] ) as $color ) {
			$palette[ (string) $color['slug'] ] = [
				'name'  => (string) $color['name'],
				'color' => (string) $color['color'],
			];
		}
		$pick = static fn( string $slug, string $fallback ): string => $palette[ $slug ]['color'] ?? $fallback;

		$system_colors = [
			[ '_id' => 'primary', 'title' => esc_html__( 'Primary', 'modern-core' ), 'color' => $pick( 'contrast', '#0f172a' ) ],
			[ '_id' => 'secondary', 'title' => esc_html__( 'Secondary', 'modern-core' ), 'color' => $pick( 'dark-surface', '#1e293b' ) ],
			[ '_id' => 'text', 'title' => esc_html__( 'Text', 'modern-core' ), 'color' => $pick( 'neutral-text', '#64748b' ) ],
			[ '_id' => 'accent', 'title' => esc_html__( 'Accent', 'modern-core' ), 'color' => $pick( 'accent', '#e2b57a' ) ],
		];
		$custom_colors = [];
		foreach ( $palette as $slug => $color ) {
			$custom_colors[] = [
				'_id'   => 'mc' . substr( md5( $slug ), 0, 6 ),
				'title' => $color['name'],
				'color' => $color['color'],
			];
		}

		$fonts   = [];
		foreach ( self::theme_font_families() as $family ) {
			$fonts[ $family['slug'] ] = $family['name'];
		}
		$body    = $fonts['primary'] ?? 'Inter';
		$heading = $fonts['secondary'] ?? $body;
		$type    = static fn( string $id, string $title, string $family, string $weight ): array => [
			'_id'                    => $id,
			'title'                  => $title,
			'typography_typography'  => 'custom',
			'typography_font_family' => $family,
			'typography_font_weight' => $weight,
		];

		return [
			'system_colors'     => $system_colors,
			'custom_colors'     => $custom_colors,
			'system_typography' => [
				$type( 'primary', esc_html__( 'Primary', 'modern-core' ), $heading, '700' ),
				$type( 'secondary', esc_html__( 'Secondary', 'modern-core' ), $heading, '400' ),
				$type( 'text', esc_html__( 'Text', 'modern-core' ), $body, '400' ),
				$type( 'accent', esc_html__( 'Accent', 'modern-core' ), $body, '600' ),
			],
			'container_width'   => [
				'unit' => 'px',
				'size' => (int) ( wp_get_global_settings( [ 'layout', 'wideSize' ] ) ? preg_replace( '/\D/', '', (string) wp_get_global_settings( [ 'layout', 'wideSize' ] ) ) : 1200 ),
			],
			// The theme's own page title is hidden on Elementor pages that ask for it.
			'page_title_selector' => 'h1.entry-title, h1.wp-block-post-title',
		];
	}
}
