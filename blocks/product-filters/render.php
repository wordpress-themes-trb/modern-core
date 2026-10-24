<?php
/**
 * Product Filters block.
 *
 * A compact preset of the Filter Sidebar block: it maps this block's
 * show/hide settings onto filter groups and renders them with the same
 * engine, so both blocks filter the shop the same way.
 *
 * @package ModernCore
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

$filters = array();
if ( $attributes['showPriceFilter'] ?? true ) {
	$filters[] = array(
		'type'     => 'price',
		'label'    => __( 'Price', 'modern-core' ),
		'expanded' => true,
	);
}
if ( $attributes['showColorFilter'] ?? true ) {
	$filters[] = array(
		'type'      => 'color',
		'label'     => __( 'Colour', 'modern-core' ),
		'attribute' => 'pa_color',
		'expanded'  => true,
	);
}
if ( $attributes['showStatusFilter'] ?? true ) {
	$filters[] = array(
		'type'     => 'status',
		'label'    => __( 'Availability', 'modern-core' ),
		'expanded' => true,
	);
}
if ( $attributes['showRatingFilter'] ?? true ) {
	$filters[] = array(
		'type'     => 'rating',
		'label'    => __( 'Rating', 'modern-core' ),
		'expanded' => true,
	);
}

$accent = $attributes['accentColor'] ?? '#0f172a';

echo render_block( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- rendered block markup.
	array(
		'blockName'    => 'modern-core/filter-sidebar',
		'attrs'        => array(
			'placementMode'   => 'sidebar-left',
			'filters'         => $filters,
			'showCounts'      => true,
			'showActiveChips' => true,
			'headingColor'    => ( $attributes['headingColor'] ?? 'inherit' ) === 'inherit' ? '#0f172a' : $attributes['headingColor'],
			'accentColor'     => $accent,
			'className'       => 'modern-core-product-filters',
			'widthMode'       => $attributes['widthMode'] ?? 'contained',
		),
		'innerBlocks'  => array(),
		'innerHTML'    => '',
		'innerContent' => array(),
	)
);
