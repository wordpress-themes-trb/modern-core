<?php
$bg_color = $attributes['bgColor'] ?? '#0f172a';
$bg_image = $attributes['bgImageUrl'] ?? '';
$opacity = ($attributes['overlayOpacity'] ?? 50) / 100;

$wrapper_attributes = get_block_wrapper_attributes(['class' => 'mc-slide']);
?>
<div <?php echo $wrapper_attributes; ?> style="background-color: <?php echo esc_attr($bg_color); ?>; <?php if($bg_image) echo "background-image: url('" . esc_url($bg_image) . "');"; ?>">
    <div class="mc-slide-overlay" style="opacity: <?php echo esc_attr($opacity); ?>;"></div>
    <div class="mc-slide-content">
        <?php echo $content; ?>
    </div>
</div>