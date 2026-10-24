<?php
/**
 * Title: Brand story banner
 * Slug: modern-core/home-story
 * Categories: modern-core-starter-compositions
 * Keywords: story, about, split, banner
 * Description: Image and text split banner introducing the brand.
 *
 * @package ModernCore
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:modern-core/split-banner {"mediaWidth":50,"mediaSide":"left","mediaUrl":"<?php echo esc_url( get_theme_file_uri( 'assets/images/story.jpg' ) ); ?>","overlayOpacity":0,"contentBg":"#f3ede6","contentColor":"#0f172a","dividerShape":"none","vAlign":"center","minHeight":560,"mobileStack":"media-first"} -->
<!-- wp:paragraph {"style":{"typography":{"fontSize":"12px","letterSpacing":"0.2em","textTransform":"uppercase","fontWeight":"600"},"color":{"text":"#b8864f"}}} -->
<p class="has-text-color" style="color:#b8864f;font-size:12px;font-weight:600;letter-spacing:0.2em;text-transform:uppercase"><?php esc_html_e( 'Our story', 'modern-core' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"style":{"typography":{"fontSize":"clamp(30px, 3.5vw, 46px)","lineHeight":"1.15"}},"fontFamily":"secondary"} -->
<h2 class="wp-block-heading has-secondary-font-family" style="font-size:clamp(30px, 3.5vw, 46px);line-height:1.15"><?php esc_html_e( 'Fewer, better things', 'modern-core' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"style":{"typography":{"fontSize":"17px","lineHeight":"1.7"}}} -->
<p style="font-size:17px;line-height:1.7"><?php esc_html_e( 'Every piece starts in our studio and is made in small runs with mills we know by name. We choose natural fibres, finish every seam properly and design for the long term, not the season.', 'modern-core' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"style":{"border":{"radius":"50px"}}} -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/about/' ) ); ?>" style="border-radius:50px"><?php esc_html_e( 'Read our story', 'modern-core' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
<!-- /wp:modern-core/split-banner -->
