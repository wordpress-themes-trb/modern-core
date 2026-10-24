<?php
$bg_color = $attributes['bgColor'] ?? '#000000';
$bg_image = $attributes['bgImageUrl'] ?? '';
$opacity = ($attributes['overlayOpacity'] ?? 50) / 100;

$unique_id = uniqid('mc-slide-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "mc-block modern-core-slide {$unique_id}"]);
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            position: relative;
            background-color: <?php echo esc_attr($bg_color); ?>;
            <?php if ($bg_image) : ?>
                background-image: url('<?php echo esc_url($bg_image); ?>');
            <?php endif; ?>
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-height: clamp(400px, 60vh, 800px);
            padding: 80px 5%;
            box-sizing: border-box;
            overflow: hidden;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-slide-overlay {
            position: absolute;
            inset: 0;
            background-color: #000000;
            opacity: <?php echo esc_attr($opacity); ?>;
            z-index: 1;
            pointer-events: none;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-slide-content {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }
    </style>

    <div class="mc-slide-overlay"></div>
    
    <div class="mc-slide-content">
        <?php echo $content; ?>
    </div>
</div>