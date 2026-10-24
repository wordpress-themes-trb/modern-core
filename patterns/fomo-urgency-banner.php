<?php
/**
 * Title: FOMO Urgency Banner
 * Slug: modern-core/fomo-urgency-banner
 * Categories: commerce, header
 * Viewport Width: 1280
 */
?>

<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:0;padding-bottom:0"><!-- wp:modern-core/countdown-banner {"bgMode":"solid","color1":"#1a1a1a","digitColor":"#ffffff","targetDate":"<?php echo esc_attr( wp_date( 'Y-m-d\TH:i', time() + 7 * DAY_IN_SECONDS ) ); ?>"} /-->

<!-- wp:modern-core/stock-countdown {"showThreshold":15,"urgentColor":"#e53e3e","displayMode":"text"} /--></div>
<!-- /wp:group -->

<!-- wp:modern-core/live-sales-popup {"position":"bottom-left"} /-->
