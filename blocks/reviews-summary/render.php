<?php
$width_mode = $attributes['widthMode'] ?? 'contained';
if (!class_exists('WooCommerce')) return;

global $product;
if (!$product || !is_a($product, 'WC_Product')) {
    // If not in a product context, exit silently
    return;
}

$review_count = $product->get_review_count();
if ($review_count === 0) {
    // Optional: Render a "No reviews yet" zero-state if desired. For now, we hide it.
    return;
}

$average = $product->get_average_rating();
$rating_counts = $product->get_rating_counts();

$layout = $attributes['layoutMode'] ?? 'horizontal';
$bar_h = $attributes['barHeight'] ?? 8;
$bar_rad = $attributes['barRadius'] ?? 4;
$interactive = $attributes['interactiveRows'] ?? true;
$format = $attributes['countFormat'] ?? 'percentage';
$star_c = $attributes['starColor'] ?? '#f59e0b';
$track_c = $attributes['trackColor'] ?? '#e2e8f0';
$text_c = $attributes['textColor'] ?? '#0f172a';

$unique_id = uniqid('mc-rev-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "mc-block width-" . esc_attr($width_mode) . " modern-core-reviews-summary {$unique_id} layout-{$layout}"]);

// FIX: Wrap helper function to prevent redeclaration errors if multiple blocks exist
if (!function_exists('mc_render_precise_stars')) {
    function mc_render_precise_stars($average) {
        $full_stars = floor($average);
        $half_star = ($average - $full_stars) >= 0.5 ? 1 : 0;
        $empty_stars = 5 - $full_stars - $half_star;
        
        return str_repeat('★', $full_stars) . ($half_star ? '★' : '') . str_repeat('☆', $empty_stars);
    }
}
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            width: 100%;
            padding: 40px 0;
            color: <?php echo esc_attr($text_c); ?>;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-rs-wrapper {
            display: flex;
            gap: 60px;
            align-items: center;
        }

        .<?php echo esc_attr($unique_id); ?>.layout-vertical .mc-rs-wrapper {
            flex-direction: column;
            gap: 30px;
            align-items: flex-start;
        }

        @media (max-width: 768px) {
            .<?php echo esc_attr($unique_id); ?> .mc-rs-wrapper {
                flex-direction: column;
                gap: 40px;
                align-items: flex-start;
            }
        }

        /* Aggregate Column */
        .<?php echo esc_attr($unique_id); ?> .mc-rs-agg {
            display: flex; flex-direction: column; align-items: center; min-width: 200px;
        }
        .<?php echo esc_attr($unique_id); ?>.layout-vertical .mc-rs-agg { align-items: flex-start; }
        @media (max-width: 768px) { .<?php echo esc_attr($unique_id); ?> .mc-rs-agg { align-items: flex-start; } }

        .<?php echo esc_attr($unique_id); ?> .mc-rs-score { font-size: 64px; font-weight: 800; line-height: 1; margin-bottom: 10px; }
        .<?php echo esc_attr($unique_id); ?> .mc-rs-stars { color: <?php echo esc_attr($star_c); ?>; font-size: 24px; letter-spacing: 2px; margin-bottom: 10px; }
        .<?php echo esc_attr($unique_id); ?> .mc-rs-count { font-size: 14px; opacity: 0.7; }

        /* Breakdown Matrix */
        .<?php echo esc_attr($unique_id); ?> .mc-rs-matrix {
            flex: 1; width: 100%; display: flex; flex-direction: column; gap: 12px;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-rs-row {
            display: flex; align-items: center; gap: 15px; text-decoration: none; color: inherit;
        }
        <?php if ($interactive) : ?>
            .<?php echo esc_attr($unique_id); ?> .mc-rs-row { cursor: pointer; transition: opacity 0.2s; }
            .<?php echo esc_attr($unique_id); ?> .mc-rs-row:hover { opacity: 0.7; }
        <?php endif; ?>

        .<?php echo esc_attr($unique_id); ?> .mc-rs-label { width: 60px; font-size: 13px; font-weight: bold; display: flex; align-items: center; gap: 5px; }
        .<?php echo esc_attr($unique_id); ?> .mc-rs-label span { color: <?php echo esc_attr($star_c); ?>; }

        .<?php echo esc_attr($unique_id); ?> .mc-rs-track {
            flex: 1; height: <?php echo esc_attr($bar_h); ?>px; background-color: <?php echo esc_attr($track_c); ?>;
            border-radius: <?php echo esc_attr($bar_rad); ?>px; overflow: hidden;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-rs-fill {
            height: 100%; background-color: <?php echo esc_attr($star_c); ?>; border-radius: <?php echo esc_attr($bar_rad); ?>px;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-rs-val { width: 40px; text-align: right; font-size: 13px; opacity: 0.7; }
    </style>

    <div class="mc-rs-wrapper">
        
        <div class="mc-rs-agg">
            <div class="mc-rs-score"><?php echo number_format($average, 1); ?></div>
            <div class="mc-rs-stars"><?php echo mc_render_precise_stars($average); ?></div>
            <div class="mc-rs-count">Based on <?php echo number_format_i18n($review_count); ?> reviews</div>
        </div>

        <div class="mc-rs-matrix">
            <?php 
            for ($star = 5; $star >= 1; $star--) {
                $count = $rating_counts[$star] ?? 0;
                $percent = $review_count > 0 ? round(($count / $review_count) * 100) : 0;
                $display_val = $format === 'percentage' ? $percent . '%' : $count;

                // If interactive, add WooCommerce native review filter query params
                $href = $interactive ? esc_url(add_query_arg('filter_rating', $star, get_permalink($product->get_id()))) . '#reviews' : '#';
                $tag = $interactive ? 'a' : 'div';
            ?>
                <<?php echo $tag; ?> href="<?php echo $href; ?>" class="mc-rs-row">
                    <div class="mc-rs-label"><?php echo $star; ?> <span>★</span></div>
                    <div class="mc-rs-track">
                        <div class="mc-rs-fill" style="width: <?php echo esc_attr($percent); ?>%;"></div>
                    </div>
                    <div class="mc-rs-val"><?php echo esc_html($display_val); ?></div>
                </<?php echo $tag; ?>>
            <?php } ?>
        </div>

    </div>
</div>