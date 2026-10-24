<?php
$width_mode = $attributes['widthMode'] ?? 'contained';
$show_label = $attributes['showLabel'] ?? true;
$label_text = $attributes['labelText'] ?? 'We ship with';
$style      = $attributes['badgeStyle'] ?? 'mono-dark';
$height     = $attributes['iconHeight'] ?? 28;
$gap        = $attributes['gap'] ?? 30;
$align      = $attributes['alignment'] ?? 'center';
$active     = $attributes['activeIcons'] ?? [];
$label_c    = $attributes['labelColor'] ?? '#64748b';

if (empty($active)) return;

$unique_id = uniqid('mc-sp-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "mc-block width-" . esc_attr($width_mode) . " modern-core-shipping-partners {$unique_id} style-{$style} align-{$align}"]);

if (!function_exists('mc_get_shipping_svg_vector')) {
    function mc_get_shipping_svg_vector($id) {
        // ... switch statement ...

    // These are simplified, highly optimized structural representations of carrier logos
    // designed to scale perfectly and adapt to CSS color variables.
    switch ($id) {
        case 'dhl':
            return '<svg viewBox="0 0 100 30" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="100" height="30" fill="var(--sp-bg, #FFCC00)"/><path d="M15 8h10c5 0 8 2.5 8 7s-3 7-8 7H15V8zm6 10h4c2.5 0 3.5-1 3.5-3s-1-3-3.5-3h-4v6zM40 8h6v5h10V8h6v14h-6v-5H46v5h-6V8zM70 8h6v10h12v4H70V8z" fill="var(--sp-fg, #D40511)"/></svg>';
        case 'fedex':
            return '<svg viewBox="0 0 100 30" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10 8h20v4H16v2h12v4H16v4h-6V8zM34 16c0-3.5 2.5-6 6-6s6 2.5 6 6h-6v2h6v2h-12v-4zm6-2c-1 0-1.5.5-1.5 1.5h3c0-1-.5-1.5-1.5-1.5zM48 8h6v6c1.5-2 3.5-2 5-2 3.5 0 6 2.5 6 6s-2.5 6-6 6c-1.5 0-3.5 0-5-2v2h-6V8zm6 10c0 1.5 1 2.5 2.5 2.5s2.5-1 2.5-2.5-1-2.5-2.5-2.5-2.5 1-2.5 2.5z" fill="var(--sp-fg, #4D148C)"/><path d="M70 16c0-3.5 2.5-6 6-6s6 2.5 6 6h-6v2h6v2H70v-4zm6-2c-1 0-1.5.5-1.5 1.5h3c0-1-.5-1.5-1.5-1.5zM84 12l4 5-4 5h7l-2.5-3L91 22h7l-4-5 4-5h-7l2.5 3L91 12h-7z" fill="var(--sp-fg2, #FF6600)"/></svg>';
        case 'ups':
            return '<svg viewBox="0 0 30 36" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15 0L0 5v12c0 10 7 15 15 19 8-4 15-9 15-19V5L15 0z" fill="var(--sp-bg, #351C15)"/><path d="M15 6L6 9v7c0 5 4 8 9 10 5-2 9-5 9-10V9l-9-3z" fill="var(--sp-fg, #FFB500)"/><path d="M10 14v4c0 1.5 1 2 2.5 2S15 19.5 15 18v-4h-2v4c0 .5-.5.5-1 .5s-1 0-1-.5v-4h-1zM17 14h3c1.5 0 2 1 2 2s-.5 2-2 2h-1v2h-2v-6zm2 3c.5 0 1 0 1-.5s-.5-.5-1-.5h-1v1h1z" fill="var(--sp-bg, #351C15)"/></svg>';
        case 'usps':
            return '<svg viewBox="0 0 80 30" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 8h6v8c0 2 1 3 3 3s3-1 3-3V8h6v8c0 4-3 7-9 7s-9-3-9-7V8zM30 18c0-3 2-4 5-4h2v-2h-7V8h7c4 0 6 2 6 5 0 3-2 4-5 4h-2v2h7v4h-7c-4 0-6-2-6-5zM50 8h8c4 0 6 2 6 5 0 3-2 5-6 5h-2v4h-6V8zm6 6h2c1 0 2 0 2-1s-1-1-2-1h-2v2zM70 18c0-3 2-4 5-4h2v-2h-7V8h7c4 0 6 2 6 5 0 3-2 4-5 4h-2v2h7v4h-7c-4 0-6-2-6-5z" fill="var(--sp-fg, #004B87)"/></svg>';
        case 'royalmail':
            return '<svg viewBox="0 0 120 30" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 8h10c5 0 8 2 8 6 0 3-2 4-4 5l4 5H15l-3-4H11v4H5V8zm6 8h4c1.5 0 2-.5 2-2s-.5-2-2-2h-4v4zM30 15c0-4 3-7 7-7s7 3 7 7-3 7-7 7-7-3-7-7zm6 0c0 1 .5 2 1 2s1-1 1-2-.5-2-1-2-1 1-1 2zM48 8h6l3 6 3-6h6v14h-5v-6l-4 6-4-6v6h-5V8z" fill="var(--sp-fg, #CE0024)"/></svg>';
        default:
            // Text fallback for unsupported custom requests without an image
            return '<svg viewBox="0 0 100 30" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="100" height="30" rx="3" fill="var(--sp-bg, #E2E8F0)"/><text x="50" y="20" font-family="sans-serif" font-size="14" font-weight="bold" fill="var(--sp-fg, #64748B)" text-anchor="middle">'.esc_attr(strtoupper($id)).'</text></svg>';
    }
}
}
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            --sp-gap: <?php echo esc_attr($gap); ?>px;
            --sp-h: <?php echo esc_attr($height); ?>px;
            width: 100%; padding: 20px 0;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-sp-label {
            font-size: 13px; font-weight: bold; letter-spacing: 0.5px; margin-bottom: 20px; text-transform: uppercase;
            color: <?php echo esc_attr($label_c); ?>;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-sp-track {
            display: inline-flex; flex-wrap: wrap; gap: var(--sp-gap); align-items: center;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-sp-track svg {
            height: var(--sp-h); width: auto; display: block;
        }

        /* Presentation Style Variants */
        .<?php echo esc_attr($unique_id); ?>.style-mono-dark {
            --sp-bg: #1e293b; --sp-fg: #1e293b; --sp-fg2: #1e293b;
        }
        .<?php echo esc_attr($unique_id); ?>.style-mono-dark svg {
            filter: grayscale(1); opacity: 0.85;
        }

        .<?php echo esc_attr($unique_id); ?>.style-mono-light {
            --sp-bg: #ffffff; --sp-fg: #ffffff; --sp-fg2: #ffffff;
        }
        .<?php echo esc_attr($unique_id); ?>.style-mono-light svg {
            filter: brightness(0) invert(1); opacity: 0.9;
        }

        /* Alignment Rules */
        .<?php echo esc_attr($unique_id); ?>.align-left { text-align: left; }
        .<?php echo esc_attr($unique_id); ?>.align-left .mc-sp-track { justify-content: flex-start; }
        
        .<?php echo esc_attr($unique_id); ?>.align-center { text-align: center; }
        .<?php echo esc_attr($unique_id); ?>.align-center .mc-sp-track { justify-content: center; }
        
        .<?php echo esc_attr($unique_id); ?>.align-right { text-align: right; }
        .<?php echo esc_attr($unique_id); ?>.align-right .mc-sp-track { justify-content: flex-end; }
    </style>

    <?php if ($show_label && !empty($label_text)) : ?>
        <div class="mc-sp-label"><?php echo esc_html($label_text); ?></div>
    <?php endif; ?>

    <div class="mc-sp-track">
        <?php foreach ($active as $icon_id) {
            echo mc_get_shipping_svg_vector($icon_id);
        } ?>
    </div>
</div>