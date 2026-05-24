<?php
$width_mode = $attributes['widthMode'] ?? 'contained';
$logo_type = $attributes['logoType'] ?? 'text';
$brand_name = $attributes['brandName'] ?? 'MODERN CORE';
$media_url = $attributes['mediaUrl'] ?? '';
$logo_width = $attributes['logoWidth'] ?? 150;
$tagline = $attributes['tagline'] ?? '';
$alignment = $attributes['alignment'] ?? 'left';

// Convert alignment to flexbox property
$flex_align = $alignment === 'center' ? 'center' : ($alignment === 'right' ? 'flex-end' : 'flex-start');

$wrapper_attributes = get_block_wrapper_attributes([
    'class' => 'mc-block width-' . esc_attr($width_mode),
    'style' => "display: flex; flex-direction: column; align-items: {$flex_align}; justify-content: center; width: 100%;"
]);
?>

<div <?php echo $wrapper_attributes; ?>>
    <div style="display: flex; align-items: center; gap: 15px;">
        <?php if ( ($logo_type === 'image' || $logo_type === 'both') && !empty($media_url) ) : ?>
            <img src="<?php echo esc_url($media_url); ?>" alt="<?php echo esc_attr($brand_name); ?>" style="width: <?php echo esc_attr($logo_width); ?>px; height: auto; display: block;" />
        <?php endif; ?>

        <?php if ( $logo_type === 'text' || $logo_type === 'both' ) : ?>
            <h2 style="margin: 0; font-weight: 800; letter-spacing: -1px; font-size: 1.75rem; color: #0f172a;">
                <?php echo esc_html($brand_name); ?>
            </h2>
        <?php endif; ?>
    </div>

    <?php if ( !empty($tagline) ) : ?>
        <p style="margin: 5px 0 0 0; font-size: 0.85rem; color: #64748b; letter-spacing: 0.5px;">
            <?php echo esc_html($tagline); ?>
        </p>
    <?php endif; ?>
</div>