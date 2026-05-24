<?php
$width_mode = $attributes['widthMode'] ?? 'contained';
$link_color = $attributes['linkColor'] ?? '#0f172a';
$hover_color = $attributes['hoverColor'] ?? '#3b82f6';
$hover_effect = $attributes['hoverEffect'] ?? 'underline';
$font_size = $attributes['fontSize'] ?? 15;
$font_weight = $attributes['fontWeight'] ?? '500';
$item_spacing = $attributes['itemSpacing'] ?? 32;
$disable_hamburger = $attributes['disableHamburger'] ?? false;

$unique_id = uniqid('mc-nav-');
$wrapper_attributes = get_block_wrapper_attributes([
    'class' => $unique_id,
    'style' => 'width: 100%; display: flex; justify-content: center;'
]);
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> .wp-block-navigation { gap: <?php echo esc_attr($item_spacing); ?>px; }
        .<?php echo esc_attr($unique_id); ?> .wp-block-navigation-item__content,
        .<?php echo esc_attr($unique_id); ?> .wp-block-navigation-item a { color: <?php echo esc_attr($link_color); ?> !important; font-size: <?php echo esc_attr($font_size); ?>px !important; font-weight: <?php echo esc_attr($font_weight); ?> !important; position: relative; transition: color 0.3s ease; text-decoration: none !important; }
        
        <?php if ($hover_effect === 'color') : ?>
            .<?php echo esc_attr($unique_id); ?> .wp-block-navigation-item__content:hover,
            .<?php echo esc_attr($unique_id); ?> .wp-block-navigation-item a:hover { color: <?php echo esc_attr($hover_color); ?> !important; }
        <?php elseif ($hover_effect === 'underline') : ?>
            .<?php echo esc_attr($unique_id); ?> .wp-block-navigation-item { position: relative; }
            .<?php echo esc_attr($unique_id); ?> .wp-block-navigation-item::after { content: ''; position: absolute; width: 0; height: 2px; bottom: -4px; left: 0; background-color: <?php echo esc_attr($hover_color); ?>; transition: width 0.3s ease; }
            .<?php echo esc_attr($unique_id); ?> .wp-block-navigation-item:hover .wp-block-navigation-item__content,
            .<?php echo esc_attr($unique_id); ?> .wp-block-navigation-item:hover a { color: <?php echo esc_attr($hover_color); ?> !important; }
            .<?php echo esc_attr($unique_id); ?> .wp-block-navigation-item:hover::after { width: 100%; }
        <?php endif; ?>

        /* THE HAMBURGER KILLER */
        <?php if ($disable_hamburger) : ?>
            @media (max-width: 767px) {
                .<?php echo esc_attr($unique_id); ?> .wp-block-navigation__responsive-container-open { display: none !important; }
                .<?php echo esc_attr($unique_id); ?> .wp-block-navigation__responsive-container-close { display: none !important; }
                .<?php echo esc_attr($unique_id); ?> .wp-block-navigation__responsive-container { display: block !important; position: static !important; width: 100% !important; height: auto !important; background: transparent !important; visibility: visible !important; opacity: 1 !important; transform: none !important; padding: 0 !important; }
                .<?php echo esc_attr($unique_id); ?> .wp-block-navigation__responsive-container-content { display: flex !important; flex-direction: column !important; align-items: center !important; gap: 15px !important; }
            }
        <?php endif; ?>
    </style>

    <?php echo $content; ?>
</div>