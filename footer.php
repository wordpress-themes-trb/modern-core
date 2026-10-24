<?php
/**
 * Footer for PHP page templates (for example Elementor's "Full Width").
 *
 * Counterpart of header.php: prints the footer template part the block
 * templates use.
 *
 * @package ModernCore
 */

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) {
	block_template_part( 'footer' );
}
?>
</div>
<?php wp_footer(); ?>
</body>
</html>
