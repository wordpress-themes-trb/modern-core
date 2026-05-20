<?php
$layout = $attributes['layoutType'] ?? 'split';
$ratio = $attributes['splitRatio'] ?? '50-50';
$divider = $attributes['dividerStyle'] ?? 'straight';
$divider_color = $attributes['dividerColor'] ?? '#0f172a';
$mobile_stack = $attributes['mobileStack'] ?? 'content-first';
$offset_x = $attributes['offsetX'] ?? 10;
$offset_y = $attributes['offsetY'] ?? 15;
$content_side = $attributes['contentSide'] ?? 'left';

$unique_id = uniqid('mc-h-layout-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "modern-core-hero-layout {$unique_id}"]);

list($left_pct, $right_pct) = explode('-', $ratio);
$is_content_left = $content_side === 'left';
$content_width = $is_content_left ? $left_pct : $right_pct;
$media_width = $is_content_left ? $right_pct : $left_pct;

// CSS Flexbox & Grid Directives
?>
<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            display: flex;
            width: 100%;
            height: 100%;
            position: relative;
            z-index: 5;
        }

        /* --- SPLIT MODE --- */
        <?php if ($layout === 'split') : ?>
        .<?php echo esc_attr($unique_id); ?> {
            flex-direction: row;
        }
        .<?php echo esc_attr($unique_id); ?> > .wp-block-group:nth-child(<?php echo $is_content_left ? '1' : '2'; ?>) {
            /* Content Zone */
            width: <?php echo esc_attr($content_width); ?>%;
            display: flex; flex-direction: column; justify-content: center;
            position: relative; z-index: 2;
        }
        .<?php echo esc_attr($unique_id); ?> > .wp-block-group:nth-child(<?php echo $is_content_left ? '2' : '1'; ?>) {
            /* Media Zone */
            width: <?php echo esc_attr($media_width); ?>%;
            position: relative; z-index: 1;
        }

        /* SVG Dividers */
        <?php if ($divider === 'diagonal') : ?>
        .<?php echo esc_attr($unique_id); ?> > .wp-block-group:nth-child(<?php echo $is_content_left ? '1' : '2'; ?>)::after {
            content: ''; position: absolute; top: 0; bottom: 0;
            <?php echo $is_content_left ? 'right: -100px;' : 'left: -100px;'; ?>
            width: 100px; background-color: <?php echo esc_attr($divider_color); ?>;
            clip-path: polygon(<?php echo $is_content_left ? '0 0, 0 100%, 100% 100%' : '100% 0, 0 0, 100% 100%'; ?>);
            z-index: 3;
        }
        <?php endif; ?>

        /* Mobile Stacking Rules */
        @media (max-width: 768px) {
            .<?php echo esc_attr($unique_id); ?> {
                flex-direction: <?php echo $mobile_stack === 'content-first' ? 'column' : 'column-reverse'; ?> !important;
            }
            .<?php echo esc_attr($unique_id); ?> > .wp-block-group {
                width: 100% !important;
                height: auto !important;
                min-height: 50vh;
            }
            .<?php echo esc_attr($unique_id); ?> > .wp-block-group::after { display: none; } /* Hide complex dividers on mobile */
        }
        <?php endif; ?>

        /* --- ASYMMETRIC MODE --- */
        <?php if ($layout === 'asymmetric') : ?>
        .<?php echo esc_attr($unique_id); ?> > .wp-block-group:nth-child(<?php echo $is_content_left ? '2' : '1'; ?>) {
            /* Media acts as absolute background */
            position: absolute; inset: 0; width: 100%; height: 100%; z-index: 1;
        }
        .<?php echo esc_attr($unique_id); ?> > .wp-block-group:nth-child(<?php echo $is_content_left ? '1' : '2'; ?>) {
            /* Content is floated and translated */
            position: relative; z-index: 2; width: auto; max-width: 60%;
            transform: translate(<?php echo esc_attr($offset_x); ?>vw, <?php echo esc_attr($offset_y); ?>vh);
        }

        @media (max-width: 768px) {
            .<?php echo esc_attr($unique_id); ?> > .wp-block-group:nth-child(<?php echo $is_content_left ? '1' : '2'; ?>) {
                max-width: 90%;
                transform: translate(5%, 10vh); /* Fallback safe coordinates for mobile */
            }
        }
        <?php endif; ?>
    </style>

    <?php echo $content; ?>

</div>