<?php
/**
 * Advanced Footer block.
 *
 * Brand column, link columns, social links, copyright and payment badges.
 * Link columns come from the block's inner blocks; when there are none, a
 * default set built from the store's own pages is shown.
 *
 * @package ModernCore
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner blocks.
 * @var WP_Block $block      Block instance.
 */

defined( 'ABSPATH' ) || exit;

$layout_mode       = $attributes['layoutMode'] ?? 'four-column';
$brand_name        = $attributes['brandName'] ?? 'MODERN CORE';
$brand_description = $attributes['brandDescription'] ?? '';
$footer_bg         = $attributes['footerBg'] ?? '#0f172a';
$text_color        = $attributes['textColor'] ?? '#f8fafc';
$accent_color      = $attributes['accentColor'] ?? '#e2b57a';
$show_payment      = $attributes['showPaymentIcons'] ?? true;
$show_social       = $attributes['showSocial'] ?? true;
$enable_reveal     = $attributes['enableReveal'] ?? false;
$pad_top_d         = (int) ( $attributes['paddingTopDesktop'] ?? 80 );
$pad_bottom_d      = (int) ( $attributes['paddingBottomDesktop'] ?? 40 );
$pad_top_m         = (int) ( $attributes['paddingTopMobile'] ?? 40 );
$pad_bottom_m      = (int) ( $attributes['paddingBottomMobile'] ?? 24 );
$copyright         = trim( (string) ( $attributes['copyrightText'] ?? '' ) );

$uid = wp_unique_id( 'mc-footer-' );

// Social networks: only those with a URL are shown.
$socials = array(
	'instagramUrl' => array( 'Instagram', '<path d="M7.5 2h9A5.5 5.5 0 0 1 22 7.5v9a5.5 5.5 0 0 1-5.5 5.5h-9A5.5 5.5 0 0 1 2 16.5v-9A5.5 5.5 0 0 1 7.5 2zm0 2A3.5 3.5 0 0 0 4 7.5v9A3.5 3.5 0 0 0 7.5 20h9a3.5 3.5 0 0 0 3.5-3.5v-9A3.5 3.5 0 0 0 16.5 4zm4.5 3.5a4.5 4.5 0 1 1 0 9 4.5 4.5 0 0 1 0-9zm0 2a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM17.3 5.7a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>' ),
	'facebookUrl'  => array( 'Facebook', '<path d="M13.5 22v-8.2h2.8l.4-3.2h-3.2V8.5c0-.9.3-1.6 1.6-1.6h1.7V4.1c-.3 0-1.3-.1-2.5-.1-2.5 0-4.2 1.5-4.2 4.3v2.4H7.3v3.2h2.8V22z"/>' ),
	'pinterestUrl' => array( 'Pinterest', '<path d="M12 2a10 10 0 0 0-3.6 19.3c-.1-.8-.2-2 0-2.9l1.2-5s-.3-.6-.3-1.5c0-1.4.8-2.5 1.8-2.5.9 0 1.3.7 1.3 1.5 0 .9-.6 2.2-.9 3.4-.2 1 .5 1.9 1.6 1.9 1.9 0 3.3-2 3.3-4.8 0-2.5-1.8-4.3-4.4-4.3-3 0-4.8 2.3-4.8 4.6 0 .9.4 1.9.8 2.4.1.1.1.2.1.3l-.3 1.2c0 .2-.2.2-.4.1-1.4-.6-2.2-2.6-2.2-4.2 0-3.4 2.5-6.6 7.2-6.6 3.8 0 6.7 2.7 6.7 6.3 0 3.7-2.4 6.8-5.6 6.8-1.1 0-2.2-.6-2.5-1.3l-.7 2.6c-.3 1-.9 2.2-1.4 2.9A10 10 0 1 0 12 2z"/>' ),
	'tiktokUrl'    => array( 'TikTok', '<path d="M16.6 2h-3.3v13.4a2.9 2.9 0 1 1-2-2.8V9.2a6.2 6.2 0 1 0 5.3 6.2V8.6a7.7 7.7 0 0 0 4.4 1.4V6.7a4.4 4.4 0 0 1-4.4-4.4z"/>' ),
	'xUrl'         => array( 'X', '<path d="M17.8 3h3.1l-6.8 7.7L22 21h-6.2l-4.9-6.4L5.3 21H2.2l7.2-8.3L1.9 3h6.4l4.4 5.8zm-1.1 16.2h1.7L7.4 4.7H5.6z"/>' ),
	'youtubeUrl'   => array( 'YouTube', '<path d="M21.6 7.2a2.5 2.5 0 0 0-1.8-1.8C18.2 5 12 5 12 5s-6.2 0-7.8.4a2.5 2.5 0 0 0-1.8 1.8C2 8.8 2 12 2 12s0 3.2.4 4.8a2.5 2.5 0 0 0 1.8 1.8C5.8 19 12 19 12 19s6.2 0 7.8-.4a2.5 2.5 0 0 0 1.8-1.8c.4-1.6.4-4.8.4-4.8s0-3.2-.4-4.8zM10 15V9l5.2 3z"/>' ),
);
$social_links = array();
foreach ( $socials as $key => $social ) {
	if ( ! empty( $attributes[ $key ] ) ) {
		$social_links[] = sprintf(
			'<a href="%1$s" target="_blank" rel="noopener noreferrer" aria-label="%2$s"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">%3$s</svg></a>',
			esc_url( $attributes[ $key ] ),
			esc_attr( $social[0] ),
			$social[1]
		);
	}
}
$social_html = $show_social && $social_links ? '<div class="mc-footer-socials">' . implode( '', $social_links ) . '</div>' : '';

// Default link columns, built from the store's real pages.
$page_url = static function ( string $wc_page, string $fallback_path ): string {
	if ( function_exists( 'wc_get_page_permalink' ) && $wc_page ) {
		$id = wc_get_page_id( $wc_page );
		if ( $id > 0 ) {
			return (string) get_permalink( $id );
		}
	}
	return home_url( $fallback_path );
};
$privacy_url   = get_privacy_policy_url() ? get_privacy_policy_url() : home_url( '/privacy-policy/' );
$default_links = array(
	__( 'Shop', 'modern-core' )    => array(
		__( 'Shop all', 'modern-core' )    => $page_url( 'shop', '/shop/' ),
		__( 'New in', 'modern-core' )      => home_url( '/product-category/new-in/' ),
		__( 'Women', 'modern-core' )       => home_url( '/product-category/women/' ),
		__( 'Men', 'modern-core' )         => home_url( '/product-category/men/' ),
	),
	__( 'Help', 'modern-core' )    => array(
		__( 'My account', 'modern-core' )  => $page_url( 'myaccount', '/my-account/' ),
		__( 'Your bag', 'modern-core' )    => $page_url( 'cart', '/cart/' ),
		__( 'Shipping & returns', 'modern-core' ) => home_url( '/shipping-returns/' ),
		__( 'Contact us', 'modern-core' )  => home_url( '/contact/' ),
	),
	__( 'Company', 'modern-core' ) => array(
		__( 'About us', 'modern-core' )    => home_url( '/about/' ),
		__( 'Journal', 'modern-core' )     => get_option( 'page_for_posts' ) ? get_permalink( (int) get_option( 'page_for_posts' ) ) : home_url( '/blog/' ),
		__( 'Privacy policy', 'modern-core' ) => $privacy_url,
		__( 'Terms', 'modern-core' )       => $page_url( 'terms', '/terms/' ),
	),
);

// Drop default links to pages this site doesn't have (About, Contact, ...).
$known_urls = array_filter(
	array(
		home_url( '/about/' )            => get_page_by_path( 'about' ),
		home_url( '/contact/' )          => get_page_by_path( 'contact' ),
		home_url( '/shipping-returns/' ) => get_page_by_path( 'shipping-returns' ),
		home_url( '/privacy-policy/' )   => get_page_by_path( 'privacy-policy' ),
		home_url( '/terms/' )            => get_page_by_path( 'terms' ),
		home_url( '/blog/' )             => false,
	),
	static fn( $page ) => ! $page || 'publish' !== $page->post_status
);
foreach ( $default_links as $heading => $links ) {
	$default_links[ $heading ] = array_filter( $links, static fn( $url ) => ! array_key_exists( $url, $known_urls ) );
	if ( ! $default_links[ $heading ] ) {
		unset( $default_links[ $heading ] );
	}
}

$has_inner = '' !== trim( wp_strip_all_tags( (string) $content, true ) );

if ( '' === $copyright ) {
	/* translators: 1: year, 2: brand name */
	$copyright = sprintf( __( '© %1$s %2$s. All rights reserved.', 'modern-core' ), wp_date( 'Y' ), $brand_name );
} else {
	$copyright = str_replace( '{year}', wp_date( 'Y' ), $copyright );
}

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => "modern-core-advanced-footer {$uid} layout-{$layout_mode}",
	)
);
?>
<style>
.<?php echo esc_attr( $uid ); ?> { background-color: <?php echo esc_attr( $footer_bg ); ?>; color: <?php echo esc_attr( $text_color ); ?>; padding: <?php echo (int) $pad_top_m; ?>px 0 <?php echo (int) $pad_bottom_m; ?>px; width: 100%; overflow: hidden; }
@media (min-width: 768px) { .<?php echo esc_attr( $uid ); ?> { padding-top: <?php echo (int) $pad_top_d; ?>px; padding-bottom: <?php echo (int) $pad_bottom_d; ?>px; } }
.<?php echo esc_attr( $uid ); ?> .mc-footer-container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
.<?php echo esc_attr( $uid ); ?> a { color: inherit; text-decoration: none; transition: color .2s ease; }
.<?php echo esc_attr( $uid ); ?> a:hover, .<?php echo esc_attr( $uid ); ?> a:focus-visible { color: <?php echo esc_attr( $accent_color ); ?>; }
.<?php echo esc_attr( $uid ); ?> h3, .<?php echo esc_attr( $uid ); ?> h4 { color: inherit; }
.<?php echo esc_attr( $uid ); ?> .mc-footer-grid { display: grid; grid-template-columns: 1.4fr repeat(3, 1fr); gap: 40px; margin-bottom: 56px; }
.<?php echo esc_attr( $uid ); ?> .mc-footer-links { display: contents; }
.<?php echo esc_attr( $uid ); ?> .mc-footer-links > * { min-width: 0; margin: 0; }
.<?php echo esc_attr( $uid ); ?> .mc-footer-brand h3 { font-size: 1.4rem; font-weight: 800; letter-spacing: -.02em; margin: 0 0 12px; }
.<?php echo esc_attr( $uid ); ?> .mc-footer-brand p { font-size: .875rem; line-height: 1.6; opacity: .7; margin: 0 0 20px; max-width: 300px; }
.<?php echo esc_attr( $uid ); ?> h4, .<?php echo esc_attr( $uid ); ?> .mc-footer-links .wp-block-heading { font-size: .8rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; margin: 0 0 16px; }
.<?php echo esc_attr( $uid ); ?> ul { list-style: none; padding: 0; margin: 0; font-size: .875rem; line-height: 2.1; opacity: .85; }
.<?php echo esc_attr( $uid ); ?> .mc-footer-socials { display: flex; gap: 14px; align-items: center; }
.<?php echo esc_attr( $uid ); ?> .mc-footer-socials svg { display: block; }
.<?php echo esc_attr( $uid ); ?> .mc-footer-row { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 24px; margin-bottom: 40px; }
.<?php echo esc_attr( $uid ); ?> .mc-footer-row nav { display: flex; flex-wrap: wrap; gap: 24px; font-size: .875rem; }
.<?php echo esc_attr( $uid ); ?>.layout-minimal-centered .mc-footer-row { flex-direction: column; text-align: center; }
.<?php echo esc_attr( $uid ); ?> .mc-footer-base { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 16px; padding-top: 24px; border-top: 1px solid rgba(255,255,255,.1); font-size: .75rem; }
.<?php echo esc_attr( $uid ); ?> .mc-footer-base p { margin: 0; opacity: .65; }
.<?php echo esc_attr( $uid ); ?> .mc-pay { display: flex; gap: 8px; align-items: center; opacity: .75; }
.<?php echo esc_attr( $uid ); ?> .mc-pay span { min-width: 40px; height: 26px; padding: 0 6px; border: 1px solid rgba(255,255,255,.25); border-radius: 4px; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 9px; letter-spacing: .04em; }
@media (max-width: 900px) { .<?php echo esc_attr( $uid ); ?> .mc-footer-grid { grid-template-columns: 1fr 1fr; } .<?php echo esc_attr( $uid ); ?> .mc-footer-brand { grid-column: 1 / -1; } }
@media (max-width: 520px) { .<?php echo esc_attr( $uid ); ?> .mc-footer-grid { grid-template-columns: 1fr; gap: 32px; } }
<?php if ( $enable_reveal ) : ?>
@media (min-width: 768px) { .<?php echo esc_attr( $uid ); ?> { position: sticky; bottom: 0; z-index: 0; } }
<?php endif; ?>
/* Content built in the editor (presets or your own blocks). */
.<?php echo esc_attr( $uid ); ?> .mc-footer-custom p, .<?php echo esc_attr( $uid ); ?> .mc-footer-custom h1, .<?php echo esc_attr( $uid ); ?> .mc-footer-custom h2, .<?php echo esc_attr( $uid ); ?> .mc-footer-custom h3, .<?php echo esc_attr( $uid ); ?> .mc-footer-custom h4, .<?php echo esc_attr( $uid ); ?> .mc-footer-custom li { color: inherit; }
.<?php echo esc_attr( $uid ); ?> .mc-footer-custom .wp-block-columns { gap: 40px; margin-bottom: 0; }
.<?php echo esc_attr( $uid ); ?> .mc-footer-custom ul { list-style: none; padding-left: 0; margin: 0; }
.<?php echo esc_attr( $uid ); ?> .mc-footer-custom .wp-block-heading { margin: 0 0 14px; }
.<?php echo esc_attr( $uid ); ?> .mc-footer-custom p { opacity: .8; }
.<?php echo esc_attr( $uid ); ?> .mc-footer-custom .mc-logo-link { color: inherit; }
.<?php echo esc_attr( $uid ); ?> .wp-block-modern-core-newsletter { background: transparent !important; padding: 0 !important; border: none !important; }
.<?php echo esc_attr( $uid ); ?> .wp-block-modern-core-newsletter input[type='email'] { background: rgba(255,255,255,.05) !important; border: 1px solid rgba(255,255,255,.2) !important; color: #fff !important; }
</style>
<footer <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by core. ?>>
	<div class="mc-footer-container">
		<?php if ( $has_inner ) : ?>
			<?php // The footer has blocks inside (chosen preset or custom layout): show exactly those. ?>
			<div class="mc-footer-custom"><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- rendered blocks. ?></div>
	</div>
</footer>
			<?php
			return;
		endif;
		// No blocks inside yet: built-in default footer, so the site never shows an empty bar.
		?>
		<?php if ( 'four-column' === $layout_mode ) : ?>
			<div class="mc-footer-grid">
				<div class="mc-footer-brand">
					<h3><?php echo esc_html( $brand_name ); ?></h3>
					<?php if ( $brand_description ) : ?>
						<p><?php echo esc_html( $brand_description ); ?></p>
					<?php endif; ?>
					<?php echo $social_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built above from escaped parts. ?>
				</div>
				<?php if ( $has_inner ) : ?>
					<div class="mc-footer-links"><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- rendered blocks. ?></div>
				<?php else : ?>
					<?php foreach ( $default_links as $heading => $links ) : ?>
						<nav aria-label="<?php echo esc_attr( $heading ); ?>">
							<h4><?php echo esc_html( $heading ); ?></h4>
							<ul>
								<?php foreach ( $links as $label => $url ) : ?>
									<li><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $label ); ?></a></li>
								<?php endforeach; ?>
							</ul>
						</nav>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		<?php else : ?>
			<div class="mc-footer-row">
				<div class="mc-footer-brand">
					<h3><?php echo esc_html( $brand_name ); ?></h3>
					<?php if ( $brand_description && 'minimal-centered' !== $layout_mode ) : ?>
						<p><?php echo esc_html( $brand_description ); ?></p>
					<?php endif; ?>
				</div>
				<?php if ( $has_inner ) : ?>
					<div class="mc-footer-links-inline"><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- rendered blocks. ?></div>
				<?php else : ?>
					<nav aria-label="<?php esc_attr_e( 'Footer', 'modern-core' ); ?>">
						<?php foreach ( array_slice( array_merge( ...array_values( $default_links ) ), 0, 6, true ) as $label => $url ) : ?>
							<a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $label ); ?></a>
						<?php endforeach; ?>
					</nav>
				<?php endif; ?>
				<?php echo $social_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built above from escaped parts. ?>
			</div>
		<?php endif; ?>

		<div class="mc-footer-base">
			<p class="mc-copyright-text"><?php echo esc_html( $copyright ); ?></p>
			<?php if ( $show_payment ) : ?>
				<div class="mc-pay" aria-label="<?php esc_attr_e( 'Accepted payment methods', 'modern-core' ); ?>">
					<span>VISA</span><span>MC</span><span>AMEX</span><span>PAYPAL</span>
				</div>
			<?php endif; ?>
		</div>
	</div>
</footer>
