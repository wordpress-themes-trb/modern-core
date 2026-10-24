<?php
$width_mode = $attributes['widthMode'] ?? 'contained';
$media_width = $attributes['mediaWidth'] ?? 50;
$media_side = $attributes['mediaSide'] ?? 'left';
$media_url = ! empty( $attributes['mediaUrl'] ) ? $attributes['mediaUrl'] : get_theme_file_uri( 'assets/images/story.jpg' );
$overlay_opacity = ($attributes['overlayOpacity'] ?? 40) / 100;
$content_bg = $attributes['contentBg'] ?? '#f8fafc';
$content_color = $attributes['contentColor'] ?? '#0f172a';
$shape = $attributes['dividerShape'] ?? 'diagonal';
$valign = $attributes['vAlign'] ?? 'center';
$min_height = $attributes['minHeight'] ?? 600;
$mobile_stack = $attributes['mobileStack'] ?? 'media-first';

$unique_id = uniqid('mc-split-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "mc-block width-" . esc_attr($width_mode) . " modern-core-split-banner {$unique_id}"]);

$is_left = $media_side === 'left';
$content_width = 100 - $media_width;
$flex_direction = $is_left ? 'row' : 'row-reverse';
$mobile_direction = $mobile_stack === 'media-first' ? 'column' : 'column-reverse';

// Diagonal Geometry Math
$clip_path = 'none';
if ($shape === 'diagonal') {
    // 8vw creates a beautiful ~15 degree slash that maintains its slope fluidly
    $clip_path = $is_left ? 'polygon(0 0, 100% 0, calc(100% - 8vw) 100%, 0 100%)' : 'polygon(8vw 0, 100% 0, 100% 100%, 0 100%)';
}

// Gradient fade direction
$grad_dir = $is_left ? 'to right' : 'to left';
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            display: flex;
            flex-direction: <?php echo esc_attr($flex_direction); ?>;
            width: 100%;
            min-height: <?php echo esc_attr($min_height); ?>px;
            background-color: <?php echo esc_attr($content_bg); ?>;
            color: <?php echo esc_attr($content_color); ?>;
            position: relative;
            overflow: hidden;
        }

        /* 1. Visual Image Zone */
        .<?php echo esc_attr($unique_id); ?> .mc-split-media {
            width: <?php echo esc_attr($media_width); ?>%;
            position: relative;
            background-image: url('<?php echo esc_url($media_url); ?>');
            background-size: cover;
            background-position: center;
            clip-path: <?php echo $clip_path; ?>;
            z-index: 2;
        }
        
        /* Inner Overlay Gradient */
        .<?php echo esc_attr($unique_id); ?> .mc-split-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(<?php echo esc_attr($grad_dir); ?>, rgba(0,0,0,0) 0%, rgba(0,0,0,<?php echo esc_attr($overlay_opacity); ?>) 100%);
        }

        /* 2. Content Text Zone */
        .<?php echo esc_attr($unique_id); ?> .mc-split-content {
            width: <?php echo esc_attr($content_width); ?>%;
            display: flex;
            flex-direction: column;
            justify-content: <?php echo esc_attr($valign); ?>;
            padding: 80px 8%;
            z-index: 1;
        }

        /* Responsive Breakpoint (Stacking) */
        @media (max-width: 900px) {
            .<?php echo esc_attr($unique_id); ?> {
                flex-direction: <?php echo esc_attr($mobile_direction); ?>;
                min-height: auto;
            }
            .<?php echo esc_attr($unique_id); ?> .mc-split-media {
                width: 100%;
                min-height: 400px;
                clip-path: none; /* Disable complex clipping on stacked mobile view */
            }
            .<?php echo esc_attr($unique_id); ?> .mc-split-overlay {
                background: rgba(0,0,0,<?php echo esc_attr($overlay_opacity * 0.5); ?>); /* Flat overlay on mobile */
            }
            .<?php echo esc_attr($unique_id); ?> .mc-split-content {
                width: 100%;
                padding: 60px 5%;
            }
        }
    </style>

    <div class="mc-split-media">
        <div class="mc-split-overlay"></div>
    </div>

    <div class="mc-split-content">
        <?php echo $content; ?>
    </div>
</div>