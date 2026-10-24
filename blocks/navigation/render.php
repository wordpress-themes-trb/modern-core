<?php
$link_color = $attributes['linkColor'] ?? '#0f172a';
$hover_color = $attributes['hoverColor'] ?? '#3b82f6';
$hover_effect = $attributes['hoverEffect'] ?? 'underline';
$font_size = $attributes['fontSize'] ?? 15;
$font_weight = $attributes['fontWeight'] ?? '500';
$item_spacing = $attributes['itemSpacing'] ?? 32;
$mobile_display = $attributes['mobileMenuDisplay'] ?? 'expanded';

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
        .<?php echo esc_attr($unique_id); ?> .wp-block-navigation-item a { 
            color: <?php echo esc_attr($link_color); ?> !important; 
            font-size: <?php echo esc_attr($font_size); ?>px !important; 
            font-weight: <?php echo esc_attr($font_weight); ?> !important; 
            position: relative; 
            transition: color 0.3s ease; 
            text-decoration: none !important; 
        }
        
        <?php if ($hover_effect === 'color') : ?>
            .<?php echo esc_attr($unique_id); ?> .wp-block-navigation-item__content:hover,
            .<?php echo esc_attr($unique_id); ?> .wp-block-navigation-item a:hover { 
                color: <?php echo esc_attr($hover_color); ?> !important; 
            }
        <?php elseif ($hover_effect === 'underline') : ?>
            .<?php echo esc_attr($unique_id); ?> .wp-block-navigation-item { position: relative; }
            .<?php echo esc_attr($unique_id); ?> .wp-block-navigation-item::after { 
                content: ''; position: absolute; width: 0; height: 2px; bottom: -4px; left: 0; 
                background-color: <?php echo esc_attr($hover_color); ?>; transition: width 0.3s ease; 
            }
            .<?php echo esc_attr($unique_id); ?> .wp-block-navigation-item:hover .wp-block-navigation-item__content,
            .<?php echo esc_attr($unique_id); ?> .wp-block-navigation-item:hover a { 
                color: <?php echo esc_attr($hover_color); ?> !important; 
            }
            .<?php echo esc_attr($unique_id); ?> .wp-block-navigation-item:hover::after { width: 100%; }
        <?php endif; ?>

        /* When 'Always Expanded' is chosen, force the native container to stack the links vertically on mobile */
        <?php if ($mobile_display === 'expanded') : ?>
            @media (max-width: 767px) {
                .<?php echo esc_attr($unique_id); ?> .wp-block-navigation__container { 
                    display: flex !important; 
                    flex-direction: column !important; 
                    align-items: center !important; 
                    gap: 15px !important; 
                }
            }
        <?php endif; ?>
    </style>

    <?php echo $content; ?>
</div>