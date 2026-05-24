<?php
$width_mode = $attributes['widthMode'] ?? 'contained';
$item1 = $attributes['item1'] ?? 'Free Worldwide Shipping';
$item2 = $attributes['item2'] ?? '30-Day Easy Returns';
$item3 = $attributes['item3'] ?? 'Secure SSL Checkout';
$item4 = $attributes['item4'] ?? '24/7 Customer Support';
$icon_size = $attributes['iconSize'] ?? 16;
$font_size = $attributes['fontSize'] ?? 12;
$gap = $attributes['gap'] ?? 24;
$show_dividers = $attributes['showDividers'] ?? true;
$text_color = $attributes['textColor'] ?? 'inherit';
$icon_color = $attributes['iconColor'] ?? '#3b82f6';
$alignment = $attributes['alignment'] ?? 'center';

$unique_id = uniqid('mc-trust-');
$flex_align = $alignment === 'center' ? 'center' : ($alignment === 'right' ? 'flex-end' : 'flex-start');

$wrapper_attributes = get_block_wrapper_attributes([
    'class' => "mc-block width-" . esc_attr($width_mode) . " modern-core-trust-strip {$unique_id}"
]);

$items = array_filter([
    ['text' => $item1, 'icon' => '<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line>'],
    ['text' => $item2, 'icon' => '<polyline points="1 4 1 10 7 10"></polyline><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>'],
    ['text' => $item3, 'icon' => '<rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path>'],
    ['text' => $item4, 'icon' => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>']
], function($item) { return !empty($item['text']); });
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            display: flex;
            flex-wrap: wrap;
            justify-content: <?php echo esc_attr($flex_align); ?>;
            gap: <?php echo esc_attr($gap); ?>px;
            color: <?php echo esc_attr($text_color); ?>;
            opacity: 0.8;
            width: 100%;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-trust-item {
            display: flex;
            align-items: center;
            gap: 8px;
            position: relative;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-trust-item svg {
            color: <?php echo esc_attr($icon_color); ?>;
            flex-shrink: 0;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-trust-item span {
            font-size: <?php echo esc_attr($font_size); ?>px;
            font-weight: 500;
            white-space: nowrap;
        }
        
        <?php if ($show_dividers) : ?>
        .<?php echo esc_attr($unique_id); ?> .mc-trust-item:not(:last-child)::after {
            content: '';
            position: absolute;
            right: calc(-<?php echo esc_attr($gap / 2); ?>px - 1px);
            top: 15%;
            height: 70%;
            width: 1px;
            background-color: currentColor;
            opacity: 0.2;
        }
        <?php endif; ?>

        @media (max-width: 768px) {
            .<?php echo esc_attr($unique_id); ?> {
                gap: 15px;
                justify-content: flex-start;
            }
            .<?php echo esc_attr($unique_id); ?> .mc-trust-item:not(:last-child)::after {
                display: none; /* Hide dividers on mobile to allow wrapping cleanly */
            }
        }
    </style>

    <?php foreach ($items as $item) : ?>
        <div class="mc-trust-item">
            <svg width="<?php echo esc_attr($icon_size); ?>" height="<?php echo esc_attr($icon_size); ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <?php echo $item['icon']; ?>
            </svg>
            <span><?php echo esc_html($item['text']); ?></span>
        </div>
    <?php endforeach; ?>
</div>