<?php
$text = $attributes['text'] ?? '';
$speed = $attributes['speed'] ?? 15;
$is_scrolling = $attributes['isScrolling'] ?? true;
$bg_color = $attributes['bgColor'] ?? '#0f172a';
$text_color = $attributes['textColor'] ?? '#ffffff';

$wrapper_attributes = get_block_wrapper_attributes([
    'style' => "background-color: {$bg_color}; color: {$text_color}; overflow: hidden; white-space: nowrap; display: flex; align-items: center; padding: 10px 0; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;"
]);
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        @keyframes modernCoreMarqueeFrontend {
            0% { transform: translateX(100vw); }
            100% { transform: translateX(-100%); }
        }
        .modern-core-marquee-text {
            animation: modernCoreMarqueeFrontend <?php echo esc_attr($speed); ?>s linear infinite;
            display: inline-block;
            min-width: 100%;
        }
        .modern-core-marquee-static {
            width: 100%;
            text-align: center;
        }
    </style>
    
    <div class="<?php echo $is_scrolling ? 'modern-core-marquee-text' : 'modern-core-marquee-static'; ?>">
        <?php echo esc_html($text); ?>
    </div>
</div>