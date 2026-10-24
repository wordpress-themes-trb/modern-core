<?php
/**
 * Header for PHP page templates (for example Elementor's "Full Width").
 *
 * The theme itself uses block templates; this file only runs when a plugin
 * calls get_header(). It prints the same header template part the block
 * templates use, so those pages keep the store header.
 *
 * @package ModernCore
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="wp-site-blocks">
<?php
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) {
	block_template_part( 'header' );
}
