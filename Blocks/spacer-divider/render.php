<?php
$width_mode = $attributes['widthMode'] ?? 'contained';
$mode = $attributes['mode'] ?? 'spacer';
$h_desk = $attributes['heightDesktop'] ?? 60;
$h_mob = $attributes['heightMobile'] ?? 40;
$style = $attributes['dividerStyle'] ?? 'solid';
$color = $attributes['dividerColor'] ?? '#e2e8f0';
$thickness = $attributes['dividerThickness'] ?? 1;
$width = $attributes['dividerWidth'] ?? 100;
$align = $attributes['dividerAlign'] ?? 'center';
$center = $attributes['centerElement'] ?? 'none';
$text = $attributes['centerText'] ?? '✧';

$unique_id = uniqid('mc-sd-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "mc-block width-" . esc_attr($width_mode) . " modern-core-spacer {$unique_id} mode-{$mode} align-{$align}"]);

// FIXED: Check if the function exists before declaring it to prevent fatal errors when multiple blocks are on the page
if ( ! function_exists( 'mc_get_wave_svg_data_uri' ) ) {
    function mc_get_wave_svg_data_uri($color, $thickness) {
        // Strip the '#' from the hex color for URI encoding
        $color_enc = str_replace('#', '%23', $color);
        // A clean bezier wave path
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="40" height="20" viewBox="0 0 40 20"><path d="M0,10 Q10,0 20,10 T40,10" fill="none" stroke="'.$color_enc.'" stroke-width="'.$thickness.'" stroke-linecap="round"/></svg>';
        return 'data:image/svg+xml;utf8,' . $svg;
    }
}
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            --sd-h-desk: <?php echo esc_attr($h_desk); ?>px;
            --sd-h-mob: <?php echo esc_attr($h_mob); ?>px;
            --sd-color: <?php echo esc_attr($color); ?>;
            --sd-thick: <?php echo esc_attr($thickness); ?>px;
            --sd-width: <?php echo esc_attr($width); ?>%;
            
            width: 100%; height: var(--sd-h-desk); display: flex; align-items: center; clear: both;
        }

        /* Alignment Logic */
        .<?php echo esc_attr($unique_id); ?>.align-left { justify-content: flex-start; }
        .<?php echo esc_attr($unique_id); ?>.align-center { justify-content: center; }
        .<?php echo esc_attr($unique_id); ?>.align-right { justify-content: flex-end; }

        @media (max-width: 768px) {
            .<?php echo esc_attr($unique_id); ?> { height: var(--sd-h-mob); }
        }

        /* Divider Core Styles */
        .<?php echo esc_attr($unique_id); ?> .mc-sd-inner {
            width: var(--sd-width); display: flex; align-items: center; justify-content: center; gap: 20px; color: var(--sd-color);
        }

        /* Standard Lines */
        .<?php echo esc_attr($unique_id); ?> .mc-sd-line { flex: 1; border-top: var(--sd-thick) solid var(--sd-color); height: 0; }
        .<?php echo esc_attr($unique_id); ?>.style-dashed .mc-sd-line { border-top-style: dashed; }
        .<?php echo esc_attr($unique_id); ?>.style-dotted .mc-sd-line { border-top-style: dotted; }
        
        /* Double Line Hack */
        .<?php echo esc_attr($unique_id); ?>.style-double .mc-sd-line { border-top: calc(var(--sd-thick) * 3) double var(--sd-color); }

        /* Gradient Fade */
        .<?php echo esc_attr($unique_id); ?>.style-gradient .mc-sd-line {
            border: none; height: var(--sd-thick);
            background: linear-gradient(90deg, transparent, var(--sd-color), transparent);
        }

        /* Wave Pattern via CSS Background */
        .<?php echo esc_attr($unique_id); ?>.style-wave .mc-sd-inner {
            height: 20px; width: var(--sd-width);
            background-image: url('<?php echo mc_get_wave_svg_data_uri($color, max(1, $thickness)); ?>');
            background-repeat: repeat-x; background-position: center;
        }
        .<?php echo esc_attr($unique_id); ?>.style-wave .mc-sd-line { display: none; }

        /* Center Decorators */
        .<?php echo esc_attr($unique_id); ?> .mc-sd-diamond { width: 8px; height: 8px; background: var(--sd-color); transform: rotate(45deg); flex-shrink: 0; }
        .<?php echo esc_attr($unique_id); ?> .mc-sd-text { font-weight: bold; font-size: 16px; flex-shrink: 0; letter-spacing: 2px; }
    </style>

    <?php if ($mode === 'divider') : ?>
        <div class="mc-sd-inner style-<?php echo esc_attr($style); ?>">
            <?php if ($style !== 'wave') : ?>
                <div class="mc-sd-line"></div>
                
                <?php if (in_array($style, ['solid', 'dashed', 'dotted']) && $center !== 'none') : ?>
                    <?php if ($center === 'diamond') : ?>
                        <div class="mc-sd-diamond"></div>
                    <?php elseif ($center === 'text') : ?>
                        <div class="mc-sd-text"><?php echo esc_html($text); ?></div>
                    <?php endif; ?>
                    <div class="mc-sd-line"></div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>