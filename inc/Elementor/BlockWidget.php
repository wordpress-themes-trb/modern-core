<?php
/**
 * Elementor widget that renders a theme block.
 *
 * @package ModernCore\Elementor
 */

declare(strict_types=1);

namespace ModernCore\Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;
use ModernCore\Blocks\BlockManager;

/**
 * One class serves every theme block. Elementor passes the constructor
 * arguments of the registered widget to every copy it creates, so the block
 * name travels in $args['mc_block'].
 *
 * Controls come from inc/Elementor/controls.json (generated from each block's
 * edit.jsx by scripts/build-elementor-map.mjs), so the Elementor panel shows
 * the same groups, labels, choices and ranges as the block sidebar. Any
 * attribute the map misses gets a control inferred from block.json.
 *
 * Output is the block's own render.php, called through render_block(), so a
 * widget and the matching block always look the same.
 */
class BlockWidget extends Widget_Base {

	/**
	 * Attributes never shown in Elementor: Elementor's layout and motion
	 * settings do these jobs, and WordPress-only plumbing.
	 */
	private const SKIP = [ 'widthMode', 'mcAnimation', 'mcAnimDuration', 'mcAnimDelay', 'mcAnimOffset', 'className', 'style', 'lock', 'metadata', 'anchor', 'align' ];

	/**
	 * Blocks that print inner content; Elementor gets a rich-text field for it.
	 */
	private const HAS_CONTENT = [ 'navigation', 'mega-menu', 'off-canvas', 'hamburger', 'video-hero', 'countdown-banner', 'promo-banner', 'advanced-footer' ];

	/**
	 * Block folder name, e.g. "faq-accordion".
	 *
	 * @var string
	 */
	private string $mc_block = '';

	/**
	 * Constructor.
	 *
	 * @param array<string, mixed>      $data Element data.
	 * @param array<string, mixed>|null $args Widget arguments; must contain mc_block.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->mc_block = (string) ( $args['mc_block'] ?? '' );
		parent::__construct( $data, $args );
	}

	/**
	 * block.json contents.
	 *
	 * @return array<string, mixed>
	 */
	private function meta(): array {
		return ElementorIntegration::widget_blocks()[ $this->mc_block ] ?? [];
	}

	/**
	 * Attribute definitions from block.json.
	 *
	 * @return array<string, array<string, mixed>>
	 */
	private function block_attributes(): array {
		$attrs = (array) ( $this->meta()['attributes'] ?? [] );
		return array_diff_key( $attrs, array_flip( self::SKIP ) );
	}

	/**
	 * Sidebar map for this block.
	 *
	 * @return array{sections: array<int, array<string, mixed>>, repeaters: array<string, array<string, mixed>>}
	 */
	private function control_map(): array {
		static $map = null;
		if ( null === $map ) {
			$file = __DIR__ . '/controls.json';
			$map  = is_readable( $file ) ? (array) json_decode( (string) file_get_contents( $file ), true ) : [];
		}
		$entry = $map[ $this->mc_block ] ?? [];
		return [
			'sections'  => (array) ( $entry['sections'] ?? [] ),
			'repeaters' => (array) ( $entry['repeaters'] ?? [] ),
		];
	}

	public function get_name() {
		return 'mc-' . $this->mc_block;
	}

	public function get_title() {
		return (string) ( $this->meta()['title'] ?? ucwords( str_replace( '-', ' ', $this->mc_block ) ) );
	}

	public function get_icon() {
		$icons = [
			'product-grid'     => 'eicon-products',
			'product-carousel' => 'eicon-posts-carousel',
			'product-card'     => 'eicon-single-product',
			'product-tabs'     => 'eicon-tabs',
			'product-gallery'  => 'eicon-product-images',
			'single-product'   => 'eicon-single-product',
			'cart'             => 'eicon-cart',
			'checkout'         => 'eicon-checkout',
			'mini-cart'        => 'eicon-cart-medium',
			'category'         => 'eicon-product-categories',
			'compare'          => 'eicon-table',
			'pricing'          => 'eicon-price-table',
			'faq'              => 'eicon-accordion',
			'testimonial'      => 'eicon-testimonial-carousel',
			'review'           => 'eicon-rating',
			'countdown'        => 'eicon-countdown',
			'hero'             => 'eicon-banner',
			'banner'           => 'eicon-banner',
			'slider'           => 'eicon-slides',
			'newsletter'       => 'eicon-mail',
			'search'           => 'eicon-search',
			'logo'             => 'eicon-site-logo',
			'navigation'       => 'eicon-nav-menu',
			'menu'             => 'eicon-nav-menu',
			'hamburger'        => 'eicon-menu-bar',
			'icon'             => 'eicon-icon-box',
			'stats'            => 'eicon-counter',
			'marquee'          => 'eicon-animated-headline',
			'video'            => 'eicon-youtube',
			'posts'            => 'eicon-posts-grid',
			'footer'           => 'eicon-footer',
			'popup'            => 'eicon-call-to-action',
			'wishlist'         => 'eicon-heart-o',
			'account'          => 'eicon-my-account',
			'filter'           => 'eicon-filter',
			'payment'          => 'eicon-credit-card',
			'trust'            => 'eicon-lock-user',
			'spacer'           => 'eicon-spacer',
			'shipping'         => 'eicon-truck',
			'stock'            => 'eicon-product-stock',
			'variation'        => 'eicon-product-add-to-cart',
		];
		foreach ( $icons as $needle => $icon ) {
			if ( false !== strpos( $this->mc_block, $needle ) ) {
				return $icon;
			}
		}
		return 'eicon-apps';
	}

	public function get_categories() {
		return [ ElementorIntegration::category_for( $this->mc_block ) ];
	}

	public function get_keywords() {
		return array_merge( (array) ( $this->meta()['keywords'] ?? [] ), [ 'modern core', str_replace( '-', ' ', $this->mc_block ) ] );
	}

	/**
	 * Output depends on the store (products, cart) and adds footer scripts,
	 * so Elementor must not cache it.
	 */
	protected function is_dynamic_content(): bool {
		return true;
	}

	public function has_widget_inner_wrapper(): bool {
		return false;
	}

	/**
	 * Elementor control id for a block attribute.
	 *
	 * @param string $attr Attribute name.
	 */
	private static function id( string $attr ): string {
		return 'mc_' . $attr;
	}

	/**
	 * Build the panel.
	 */
	protected function register_controls() {
		$attrs = $this->block_attributes();
		$map   = $this->control_map();
		$done  = [];
		$count = 0;

		foreach ( $map['sections'] as $section ) {
			$controls = array_filter(
				(array) $section['controls'],
				static fn( $c ) => isset( $attrs[ (string) ( $c['attr'] ?? $c['repeater'] ?? '' ) ] )
			);
			if ( ! $controls ) {
				continue;
			}
			$this->start_controls_section(
				'mc_section_' . ( $count++ ),
				[
					'label' => (string) $section['title'],
					'tab'   => $this->is_style_section( $section, $controls ) ? Controls_Manager::TAB_STYLE : Controls_Manager::TAB_CONTENT,
				]
			);
			if ( 1 === $count && in_array( $this->mc_block, self::HAS_CONTENT, true ) ) {
				$this->add_content_control();
			}
			foreach ( $controls as $c ) {
				if ( isset( $c['repeater'] ) ) {
					$this->add_repeater_control( (string) $c['repeater'], $attrs[ $c['repeater'] ], (array) ( $map['repeaters'][ $c['repeater'] ]['fields'] ?? [] ) );
					$done[ $c['repeater'] ] = true;
					continue;
				}
				$attr = (string) $c['attr'];
				$args = $this->control_args( $attr, $attrs[ $attr ], $c );
				if ( ! empty( $c['condition'] ) ) {
					$cond = $this->condition( (array) $c['condition'], $attrs );
					if ( $cond ) {
						$args['condition'] = $cond;
					}
				}
				$this->add_control( self::id( $attr ), $args );
				$done[ $attr ] = true;
				if ( ! empty( $c['idAttr'] ) ) {
					$done[ (string) $c['idAttr'] ] = true;
				}
			}
			$this->end_controls_section();
		}

		// Attributes the sidebar map doesn't cover.
		$rest = array_diff_key( $attrs, $done );
		if ( $rest || ( 0 === $count && in_array( $this->mc_block, self::HAS_CONTENT, true ) ) ) {
			$this->start_controls_section(
				'mc_section_more',
				[
					'label' => 0 === $count ? esc_html__( 'Settings', 'modern-core' ) : esc_html__( 'More settings', 'modern-core' ),
					'tab'   => Controls_Manager::TAB_CONTENT,
				]
			);
			if ( 0 === $count && in_array( $this->mc_block, self::HAS_CONTENT, true ) ) {
				$this->add_content_control();
			}
			foreach ( $rest as $attr => $def ) {
				// Media id attributes travel with their url control.
				if ( preg_match( '/^(.*)(Id|ID)$/', $attr, $m ) && isset( $attrs[ $m[1] . 'Url' ] ) ) {
					continue;
				}
				if ( 'array' === ( $def['type'] ?? '' ) ) {
					$this->add_repeater_control( $attr, $def, [] );
					continue;
				}
				$this->add_control( self::id( $attr ), $this->control_args( $attr, $def, [] ) );
			}
			$this->end_controls_section();
		}
	}

	/**
	 * Colour-only or "style/design" panels go on Elementor's Style tab.
	 *
	 * @param array<string, mixed>            $section  Section from the map.
	 * @param array<int, array<string, mixed>> $controls Its controls.
	 */
	private function is_style_section( array $section, array $controls ): bool {
		$colors = array_filter( $controls, static fn( $c ) => 'color' === ( $c['kind'] ?? '' ) );
		if ( count( $colors ) === count( $controls ) ) {
			return true;
		}
		return (bool) preg_match( '/colou?r|style|design|visual|appearance|typograph/i', (string) $section['title'] );
	}

	/**
	 * Rich-text field for blocks that wrap inner content.
	 */
	private function add_content_control(): void {
		$this->add_control(
			'mc_inner_content',
			[
				'label'       => esc_html__( 'Content', 'modern-core' ),
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => '',
				'description' => 'advanced-footer' === $this->mc_block ? esc_html__( 'Leave empty to use the built-in footer layout.', 'modern-core' ) : '',
			]
		);
	}

	/**
	 * Translate a sidebar condition into an Elementor one.
	 *
	 * @param array<string, mixed>                $condition Sidebar condition.
	 * @param array<string, array<string, mixed>> $attrs     Attribute definitions.
	 * @return array<string, mixed>
	 */
	private function condition( array $condition, array $attrs ): array {
		$out = [];
		foreach ( $condition as $key => $value ) {
			$negate = '!' === substr( (string) $key, -1 );
			$attr   = rtrim( (string) $key, '!' );
			if ( ! isset( $attrs[ $attr ] ) ) {
				continue;
			}
			if ( 'boolean' === ( $attrs[ $attr ]['type'] ?? '' ) ) {
				$value = $value ? 'yes' : '';
			}
			$out[ self::id( $attr ) . ( $negate ? '!' : '' ) ] = is_bool( $value ) ? ( $value ? 'yes' : '' ) : (string) $value;
		}
		return $out;
	}

	/**
	 * Readable label from an attribute name ("cardBg" -> "Card bg").
	 *
	 * @param string $attr Attribute name.
	 */
	private static function label( string $attr ): string {
		return ucfirst( strtolower( trim( (string) preg_replace( '/(?<!^)([A-Z])/', ' $1', $attr ) ) ) );
	}

	/**
	 * Kind of control to use when the sidebar map has none.
	 *
	 * @param string               $attr Attribute name.
	 * @param array<string, mixed> $def  Attribute definition.
	 */
	private static function infer_kind( string $attr, array $def ): string {
		$type = $def['type'] ?? 'string';
		if ( 'boolean' === $type ) {
			return 'switch';
		}
		if ( 'number' === $type || 'integer' === $type ) {
			return 'number';
		}
		if ( ! empty( $def['enum'] ) ) {
			return 'select';
		}
		$default = is_scalar( $def['default'] ?? null ) ? (string) $def['default'] : '';
		if ( preg_match( '/(colou?r|Bg|bg$|^bg)/', $attr ) || preg_match( '/^#[0-9a-f]{3,8}$|^rgba?\(/i', $default ) ) {
			return 'color';
		}
		if ( preg_match( '/(image|Image|poster|Poster|media|Media|avatar|Avatar)(Url|URL)?$/', $attr ) ) {
			return 'media';
		}
		if ( preg_match( '/(desc|Desc|text|Text|message|Message|content|Content|quote|answer)$/', $attr ) && strlen( $default ) > 40 ) {
			return 'textarea';
		}
		return 'text';
	}

	/**
	 * Elementor control arguments for one attribute.
	 *
	 * @param string               $attr Attribute name.
	 * @param array<string, mixed> $def  Attribute definition from block.json.
	 * @param array<string, mixed> $c    Sidebar control description (may be empty).
	 * @return array<string, mixed>
	 */
	private function control_args( string $attr, array $def, array $c ): array {
		$kind    = (string) ( $c['kind'] ?? self::infer_kind( $attr, $def ) );
		$type    = (string) ( $def['type'] ?? 'string' );
		$default = $def['default'] ?? null;
		$label   = trim( (string) ( $c['label'] ?? '' ) );
		$args    = [ 'label' => '' !== $label ? $label : self::label( $attr ) ];

		// A text box editing a number attribute is still a number in Elementor.
		if ( in_array( $type, [ 'number', 'integer' ], true ) && in_array( $kind, [ 'text', 'textarea' ], true ) ) {
			$kind = 'number';
		}
		if ( 'boolean' === $type ) {
			$kind = 'switch';
		}

		switch ( $kind ) {
			case 'select':
				$options = [];
				foreach ( (array) ( $c['options'] ?? [] ) as $o ) {
					$options[ (string) $o['value'] ] = (string) ( $o['label'] ?? $o['value'] );
				}
				if ( ! $options && ! empty( $def['enum'] ) ) {
					foreach ( (array) $def['enum'] as $v ) {
						$options[ (string) $v ] = self::label( (string) $v );
					}
				}
				if ( null !== $default && ! isset( $options[ (string) $default ] ) ) {
					$options = [ (string) $default => self::label( (string) $default ) ] + $options;
				}
				$args += [
					'type'    => Controls_Manager::SELECT,
					'options' => $options,
					'default' => null === $default ? '' : (string) $default,
				];
				break;
			case 'switch':
				$args += [
					'type'         => Controls_Manager::SWITCHER,
					'return_value' => 'yes',
					'default'      => $default ? 'yes' : '',
				];
				break;
			case 'number':
				$args += [
					'type'    => Controls_Manager::NUMBER,
					'default' => is_numeric( $default ) ? $default + 0 : '',
				];
				foreach ( [ 'min', 'max', 'step' ] as $k ) {
					if ( isset( $c[ $k ] ) && is_numeric( $c[ $k ] ) ) {
						$args[ $k ] = $c[ $k ] + 0;
					}
				}
				break;
			case 'color':
				$args += [
					'type'    => Controls_Manager::COLOR,
					'default' => (string) $default,
				];
				break;
			case 'media':
				$args += [
					'type'    => Controls_Manager::MEDIA,
					'default' => [ 'url' => (string) $default ],
				];
				break;
			case 'textarea':
				$args += [
					'type'    => Controls_Manager::TEXTAREA,
					'rows'    => (int) ( $c['rows'] ?? 4 ),
					'default' => (string) $default,
				];
				break;
			default:
				$args += [
					'type'        => Controls_Manager::TEXT,
					'default'     => is_scalar( $default ) ? (string) $default : '',
					'label_block' => strlen( (string) ( is_scalar( $default ) ? $default : '' ) ) > 24,
				];
				if ( 'array' === $type ) {
					// A list of plain values (e.g. payment icons) as comma-separated text.
					$args['default']     = implode( ', ', array_map( 'strval', (array) $default ) );
					$args['description'] = esc_html__( 'Comma-separated list.', 'modern-core' );
					$args['label_block'] = true;
				}
		}
		return $args;
	}

	/**
	 * Repeater for an array-of-objects attribute (FAQs, stats, testimonials…).
	 *
	 * @param string                           $attr   Attribute name.
	 * @param array<string, mixed>             $def    Attribute definition.
	 * @param array<int, array<string, mixed>> $fields Field descriptions from the sidebar map.
	 */
	private function add_repeater_control( string $attr, array $def, array $fields ): void {
		$default = (array) ( $def['default'] ?? [] );
		$sample  = isset( $default[0] ) && is_array( $default[0] ) ? $default[0] : null;

		if ( null === $sample && ! $fields ) {
			if ( isset( $default[0] ) && ! is_array( $default[0] ) ) {
				$this->add_control( self::id( $attr ), $this->control_args( $attr, $def, [] ) );
			}
			// Lists without a known item shape (e.g. manually picked ids) stay block-editor only.
			return;
		}

		// Field definitions: from the sidebar first, then any other keys in the default item.
		$by_key = [];
		foreach ( $fields as $f ) {
			$by_key[ (string) $f['key'] ] = $f;
		}
		$examples = []; // First value seen for each key, across all default items.
		foreach ( $default as $item ) {
			foreach ( (array) $item as $key => $value ) {
				if ( ! array_key_exists( $key, $examples ) ) {
					$examples[ $key ] = $value;
				}
				if ( ! isset( $by_key[ $key ] ) ) {
					$by_key[ $key ] = [ 'key' => $key ];
				}
			}
		}

		$repeater = new Repeater();
		$title    = '';
		foreach ( $by_key as $key => $f ) {
			$key   = (string) $key;
			$value = $examples[ $key ] ?? '';
			if ( in_array( $key, [ 'id', '_id' ], true ) && ! isset( $f['kind'] ) ) {
				// Item identity (e.g. which product element a row controls) travels unseen.
				$repeater->add_control( 'mc_item_' . $key, [ 'type' => Controls_Manager::HIDDEN, 'default' => '' ] );
				continue;
			}
			if ( is_array( $value ) ) {
				$repeater->add_control( 'mc_item_' . $key, self::list_field_args( $key, $value ) );
				continue;
			}
			$fdef = [
				'type'    => is_bool( $value ) ? 'boolean' : ( is_int( $value ) || is_float( $value ) ? 'number' : 'string' ),
				'default' => $value,
			];
			$args = $this->control_args( $key, $fdef, $f );
			$repeater->add_control( 'mc_item_' . $key, $args );
			if ( '' === $title && in_array( $args['type'], [ Controls_Manager::TEXT, Controls_Manager::TEXTAREA ], true ) ) {
				$title = 'mc_item_' . $key;
			}
		}

		$items = [];
		foreach ( $default as $item ) {
			if ( ! is_array( $item ) ) {
				continue;
			}
			$row = [];
			foreach ( $item as $key => $value ) {
				$kind = $by_key[ $key ]['kind'] ?? null;
				if ( is_array( $value ) ) {
					$value = self::list_to_text( $value );
				} elseif ( is_bool( $value ) ) {
					$value = $value ? 'yes' : '';
				} elseif ( 'media' === $kind || ( null === $kind && 'media' === self::infer_kind( (string) $key, [ 'default' => $value ] ) ) ) {
					$value = [ 'url' => (string) $value ];
				}
				$row[ 'mc_item_' . $key ] = is_array( $value ) || is_scalar( $value ) ? $value : '';
			}
			$items[] = $row;
		}

		$this->add_control(
			self::id( $attr ),
			[
				'label'       => self::label( $attr ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => $items,
				'title_field' => $title ? '{{{ ' . $title . ' }}}' : '',
			]
		);
	}

	/**
	 * A list inside a list item (e.g. a pricing tier's features) as a text box.
	 *
	 * @param string            $key     Item key.
	 * @param array<int, mixed> $example Example value.
	 * @return array<string, mixed>
	 */
	private static function list_field_args( string $key, array $example ): array {
		$checklist = isset( $example[0] ) && is_array( $example[0] ) && array_key_exists( 'included', $example[0] );
		return [
			'label'       => self::label( $key ),
			'type'        => Controls_Manager::TEXTAREA,
			'rows'        => 5,
			'default'     => self::list_to_text( $example ),
			'description' => $checklist
				? esc_html__( 'One per line. Start a line with "-" for something not included.', 'modern-core' )
				: esc_html__( 'One per line.', 'modern-core' ),
		];
	}

	/**
	 * Nested list -> lines of text.
	 *
	 * @param array<int, mixed> $list List value.
	 */
	private static function list_to_text( array $list ): string {
		$lines = [];
		foreach ( $list as $entry ) {
			if ( is_array( $entry ) ) {
				$text    = (string) ( $entry['text'] ?? $entry['label'] ?? reset( $entry ) );
				$lines[] = ( array_key_exists( 'included', $entry ) && ! $entry['included'] ? '- ' : '' ) . $text;
			} else {
				$lines[] = (string) $entry;
			}
		}
		return implode( "\n", $lines );
	}

	/**
	 * Lines of text -> nested list shaped like the example.
	 *
	 * @param string            $text    Text box value.
	 * @param array<int, mixed> $example Example list (decides the shape).
	 * @return array<int, mixed>
	 */
	private static function text_to_list( string $text, array $example ): array {
		$lines = array_values( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', $text ) ?: [] ), 'strlen' ) );
		$first = $example[0] ?? null;
		if ( ! is_array( $first ) ) {
			return $lines;
		}
		$out = [];
		foreach ( $lines as $line ) {
			$excluded = 1 === preg_match( '/^-\s*/', $line );
			$text     = (string) preg_replace( '/^-\s*/', '', $line );
			$entry    = array_key_exists( 'text', $first ) ? [ 'text' => $text ] : [ (string) array_key_first( $first ) => $text ];
			if ( array_key_exists( 'included', $first ) ) {
				$entry['included'] = ! $excluded;
			}
			$out[] = $entry;
		}
		return $out;
	}

	/**
	 * Turn one Elementor setting back into a block attribute value.
	 *
	 * @param mixed                $value Setting value.
	 * @param array<string, mixed> $def   Attribute definition.
	 * @return mixed
	 */
	private static function to_attribute( $value, array $def ) {
		$type    = $def['type'] ?? 'string';
		$default = $def['default'] ?? null;
		if ( is_array( $value ) && array_key_exists( 'url', $value ) && ! array_key_exists( 0, $value ) ) {
			$value = (string) $value['url']; // Media control.
		}
		switch ( $type ) {
			case 'boolean':
				return 'yes' === $value || true === $value;
			case 'number':
				return is_numeric( $value ) ? $value + 0 : null;
			case 'integer':
				return is_numeric( $value ) ? (int) $value : null;
			case 'array':
				if ( is_string( $value ) ) {
					return array_values( array_filter( array_map( 'trim', explode( ',', $value ) ), 'strlen' ) );
				}
				if ( ! is_array( $value ) ) {
					return null;
				}
				$sample = isset( $default[0] ) && is_array( $default[0] ) ? $default[0] : [];
				$items  = [];
				foreach ( $value as $index => $row ) {
					if ( ! is_array( $row ) ) {
						continue;
					}
					// Start from the matching default item so hidden keys (ids) survive.
					$item = isset( $default[ $index ] ) && is_array( $default[ $index ] ) ? $default[ $index ] : $sample;
					foreach ( $row as $key => $v ) {
						if ( 0 !== strpos( (string) $key, 'mc_item_' ) ) {
							continue;
						}
						$k = substr( (string) $key, 8 );
						if ( is_array( $v ) && array_key_exists( 'url', $v ) ) {
							$v = (string) $v['url'];
						}
						$was = $sample[ $k ] ?? ( $item[ $k ] ?? null );
						if ( is_array( $was ) ) {
							$item[ $k ] = self::text_to_list( (string) $v, $was );
							continue;
						}
						$item[ $k ] = is_bool( $was ) ? ( 'yes' === $v || true === $v ) : ( ( is_int( $was ) || is_float( $was ) ) && is_numeric( $v ) ? $v + 0 : $v );
					}
					$items[] = $item;
				}
				return $items;
			case 'object':
				return is_array( $value ) ? $value : null;
			default:
				return is_scalar( $value ) ? (string) $value : null;
		}
	}

	/**
	 * Render the block.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$attrs    = [];
		foreach ( $this->block_attributes() as $attr => $def ) {
			$key = self::id( $attr );
			if ( ! array_key_exists( $key, $settings ) ) {
				continue;
			}
			$value = self::to_attribute( $settings[ $key ], $def );
			if ( null === $value || ( '' === $value && ! in_array( $def['type'] ?? 'string', [ 'string' ], true ) ) ) {
				continue;
			}
			$attrs[ $attr ] = $value;
			// Media id travels with its url.
			if ( is_array( $settings[ $key ] ) && isset( $settings[ $key ]['id'] ) && isset( $this->block_attributes()[ preg_replace( '/Url$/', 'Id', $attr ) ] ) && preg_match( '/Url$/', $attr ) ) {
				$attrs[ (string) preg_replace( '/Url$/', 'Id', $attr ) ] = (int) $settings[ $key ]['id'];
			}
		}
		$inner = (string) ( $settings['mc_inner_content'] ?? '' );
		$html  = render_block(
			[
				'blockName'    => 'modern-core/' . $this->mc_block,
				'attrs'        => $attrs,
				'innerBlocks'  => [],
				'innerHTML'    => $inner,
				'innerContent' => '' === $inner ? [] : [ $inner ],
			]
		);

		if ( self::is_editor_request() ) {
			$html = self::run_scripts_now( $html );
		}
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the block's render.php escapes its output.
	}

	/**
	 * Is this render for the Elementor editor (live preview or AJAX re-render)?
	 */
	private static function is_editor_request(): bool {
		if ( wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
			return true;
		}
		$plugin = \Elementor\Plugin::$instance;
		return ( isset( $plugin->editor ) && $plugin->editor->is_edit_mode() ) || ( isset( $plugin->preview ) && $plugin->preview->is_preview_mode() );
	}

	/**
	 * In the editor, widgets are re-rendered after the page has loaded, so
	 * "run on DOMContentLoaded" callbacks would never fire. Run them at once.
	 *
	 * @param string $html Widget HTML.
	 */
	private static function run_scripts_now( string $html ): string {
		return (string) preg_replace_callback(
			'#(<script\b(?![^>]*\bsrc=)(?![^>]*type=["\']application/)[^>]*>)(.*?)(</script>)#is',
			static function ( array $m ): string {
				$code = (string) preg_replace(
					'/(?:document|window)\.addEventListener\(\s*([\'"])(?:DOMContentLoaded|load)\1\s*,/',
					'(function (f) { f(); })(',
					$m[2]
				);
				return $m[1] . $code . $m[3];
			},
			$html
		);
	}
}
