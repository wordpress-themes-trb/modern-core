<?php
/**
 * Logo block: image and/or text logo linking to the homepage.
 *
 * @package ModernCore
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$logo_type  = $attributes['logoType'] ?? 'text';
$brand_name = $attributes['brandName'] ?? get_bloginfo( 'name' );
$media_url  = $attributes['mediaUrl'] ?? '';
$media_id   = (int) ( $attributes['mediaId'] ?? 0 );
$logo_width = (int) ( $attributes['logoWidth'] ?? 150 );
$tagline    = $attributes['tagline'] ?? '';
$alignment  = $attributes['alignment'] ?? 'left';

$flex_align = 'center' === $alignment ? 'center' : ( 'right' === $alignment ? 'flex-end' : 'flex-start' );
$show_image = in_array( $logo_type, array( 'image', 'both' ), true ) && ( $media_id || $media_url );
$show_text  = in_array( $logo_type, array( 'text', 'both' ), true ) || ! $show_image;

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'mc-logo mc-block width-' . sanitize_html_class( $attributes['widthMode'] ?? 'contained' ),
		'style' => "display:flex;flex-direction:column;align-items:{$flex_align};justify-content:center;",
	)
);
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by core. ?>>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="mc-logo-link" style="display:flex;align-items:center;gap:12px;color:inherit;text-decoration:none;"<?php echo is_front_page() ? ' aria-current="page"' : ''; ?>>
		<?php if ( $show_image ) : ?>
			<?php
			$img_attrs = array(
				'alt'           => $show_text ? '' : $brand_name,
				'style'         => 'width:min(' . $logo_width . 'px, 40vw);height:auto;display:block;',
				'fetchpriority' => 'high',
				'loading'       => 'eager',
			);
			// Library image first; the saved URL if the attachment is gone.
			$img_html = $media_id ? wp_get_attachment_image( $media_id, 'medium', false, $img_attrs ) : '';
			if ( '' === $img_html && $media_url ) {
				$img_html = sprintf( '<img src="%1$s" alt="%2$s" style="%3$s">', esc_url( $media_url ), esc_attr( $img_attrs['alt'] ), esc_attr( $img_attrs['style'] ) );
			}
			echo $img_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core image markup or escaped above.
			?>
		<?php endif; ?>
		<?php if ( $show_text ) : ?>
			<span class="mc-logo-text" style="font-weight:800;letter-spacing:-0.03em;font-size:clamp(1.25rem, 3.5vw, 1.75rem);line-height:1.1;white-space:nowrap;"><?php echo esc_html( $brand_name ); ?></span>
		<?php endif; ?>
	</a>
	<?php if ( $tagline ) : ?>
		<p style="margin:4px 0 0;font-size:0.8rem;opacity:0.65;letter-spacing:0.02em;"><?php echo esc_html( $tagline ); ?></p>
	<?php endif; ?>
</div>
