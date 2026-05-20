<?php
/**
 * ModernCore Section Layout System
 * Handles omni-directional padding, complete 2D Grid layouts (Rows + Columns), and mobile stacking.
 */

// 1. Extract Layout Controls
$layout           = $attributes['layoutStyle'] ?? 'standard';
$grid_cols        = $attributes['columnCount'] ?? 1;
$grid_rows        = $attributes['rowCount'] ?? 1;
$col_gap          = $attributes['columnGap'] ?? 30;
$row_gap          = $attributes['rowGap'] ?? 30;
$stack_on_mobile  = $attributes['stackOnMobile'] ?? true;

// 2. Extract Omni-Directional Padding
$pt_d = $attributes['paddingTopDesktop'] ?? 80;
$pb_d = $attributes['paddingBottomDesktop'] ?? 80;
$pl_d = $attributes['paddingLeftDesktop'] ?? 0;
$pr_d = $attributes['paddingRightDesktop'] ?? 0;

$pt_m = $attributes['paddingTopMobile'] ?? 40;
$pb_m = $attributes['paddingBottomMobile'] ?? 40;
$pl_m = $attributes['paddingLeftMobile'] ?? 20;
$pr_m = $attributes['paddingRightMobile'] ?? 20;

// 3. Extract Structural Controls
$w_width    = $attributes['wrapperWidth'] ?? 'full';
$c_width    = $attributes['contentWidth'] ?? 'contained';
$hide_empty = $attributes['hideWhenEmpty'] ?? false;

// 4. Extract Design Controls
$bg_type    = $attributes['bgType'] ?? 'transparent';
$bg_color   = $attributes['bgColor'] ?? 'transparent';
$text_color = $attributes['textColor'] ?? 'inherit';
$bg_img     = $attributes['bgImageUrl'] ?? '';
$overlay    = ($attributes['bgOverlayOpacity'] ?? 0) / 100;
$t_shape    = $attributes['topShape'] ?? 'none';
$b_shape    = $attributes['bottomShape'] ?? 'none';
$s_height   = $attributes['shapeHeight'] ?? 50;
$s_color    = $attributes['shapeColor'] ?? '#ffffff';
$border     = $attributes['borderType'] ?? 'none';
$b_color    = $attributes['borderColor'] ?? '#e2e8f0';
$rad        = $attributes['borderRadius'] ?? 0;
$shadow     = $attributes['hasShadow'] ?? false;
$overflow   = $attributes['overflowHidden'] ?? false;

if ($hide_empty && trim($content) === '' && !is_admin()) {
    return;
}

$unique_id = uniqid('mc-sw-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "modern-core-section {$unique_id} w-{$w_width}"]);

if (!function_exists('mc_get_simple_divider')) {
    function mc_get_simple_divider($type, $color, $position) {
        $flip = $position === 'top' ? 'transform="rotate(180)" transform-origin="center"' : '';
        $svg_start = '<svg class="mc-sw-shape mc-sw-shape-'.$position.'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none" '.$flip.'>';
        $svg_end = '</svg>';
        switch ($type) {
            case 'angle':
                return $svg_start . '<polygon points="0,100 100,0 100,100" fill="'.esc_attr($color).'" />' . $svg_end;
            case 'straight':
                return $svg_start . '<rect width="100" height="100" fill="'.esc_attr($color).'" />' . $svg_end;
            default:
                return '';
        }
    }
}
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            position: relative;
            padding-top: <?php echo esc_attr($pt_m); ?>px;
            padding-bottom: <?php echo esc_attr($pb_m); ?>px;
            padding-left: <?php echo esc_attr($pl_m); ?>px;
            padding-right: <?php echo esc_attr($pr_m); ?>px;
            border-radius: <?php echo esc_attr($rad); ?>px;
            color: <?php echo esc_attr($text_color); ?>;
            <?php if ($overflow) echo 'overflow: hidden;'; ?>
            <?php if ($shadow) echo 'box-shadow: 0 25px 50px -12px rgba(0,0,0,0.1);'; ?>
            <?php if ($bg_type === 'solid' || $bg_type === 'image') : ?>
                background-color: <?php echo esc_attr($bg_color); ?>;
            <?php endif; ?>
        }

        <?php if ($border === 'tb' || $border === 'top' || $border === 'all') : ?>
            .<?php echo esc_attr($unique_id); ?> { border-top: 1px solid <?php echo esc_attr($b_color); ?>; }
        <?php endif; ?>
        <?php if ($border === 'tb' || $border === 'bottom' || $border === 'all') : ?>
            .<?php echo esc_attr($unique_id); ?> { border-bottom: 1px solid <?php echo esc_attr($b_color); ?>; }
        <?php endif; ?>
        <?php if ($border === 'all') : ?>
            .<?php echo esc_attr($unique_id); ?> { border-left: 1px solid <?php echo esc_attr($b_color); ?>; border-right: 1px solid <?php echo esc_attr($b_color); ?>; }
        <?php endif; ?>

        @media (min-width: 768px) {
            .<?php echo esc_attr($unique_id); ?> {
                padding-top: <?php echo esc_attr($pt_d); ?>px;
                padding-bottom: <?php echo esc_attr($pb_d); ?>px;
                padding-left: <?php echo esc_attr($pl_d); ?>px;
                padding-right: <?php echo esc_attr($pr_d); ?>px;
            }
        }

        .<?php echo esc_attr($unique_id); ?>.w-full {
            width: 100vw; position: relative; left: 50%; right: 50%; margin-left: -50vw; margin-right: -50vw;
        }

        /* 2D Multi-Column Layout Engine */
        .<?php echo esc_attr($unique_id); ?> .mc-sw-inner {
            position: relative; z-index: 10; width: 100%; margin: 0 auto;
            <?php if ($layout === 'multi-column') : ?>
                display: grid;
                column-gap: <?php echo esc_attr($col_gap); ?>px;
                row-gap: <?php echo esc_attr($row_gap); ?>px;
                <?php if ($stack_on_mobile) : ?>
                    grid-template-columns: 1fr;
                <?php else : ?>
                    grid-template-columns: repeat(<?php echo esc_attr($grid_cols); ?>, 1fr);
                    <?php if ($grid_rows > 1) echo "grid-template-rows: repeat(" . esc_attr($grid_rows) . ", 1fr);"; ?>
                <?php endif; ?>
            <?php endif; ?>
        }
        
        <?php if ($c_width === 'contained') : ?>
            .<?php echo esc_attr($unique_id); ?> .mc-sw-inner { max-width: 1200px; }
        <?php endif; ?>

        <?php if ($layout === 'multi-column' && $stack_on_mobile) : ?>
        @media (min-width: 768px) {
            .<?php echo esc_attr($unique_id); ?> .mc-sw-inner {
                grid-template-columns: repeat(<?php echo esc_attr($grid_cols); ?>, 1fr);
                <?php if ($grid_rows > 1) echo "grid-template-rows: repeat(" . esc_attr($grid_rows) . ", 1fr);"; ?>
            }
        }
        <?php endif; ?>

        <?php if ($bg_type === 'image' && !empty($bg_img)) : ?>
            .<?php echo esc_attr($unique_id); ?>::before {
                content: ''; position: absolute; inset: 0; background-image: url('<?php echo esc_url($bg_img); ?>');
                background-size: cover; background-position: center; border-radius: inherit; z-index: 0;
            }
            <?php if ($overlay > 0) : ?>
                .<?php echo esc_attr($unique_id); ?>::after {
                    content: ''; position: absolute; inset: 0; background-color: rgba(0,0,0, <?php echo esc_attr($overlay); ?>);
                    border-radius: inherit; z-index: 1;
                }
            <?php endif; ?>
        <?php endif; ?>

        .<?php echo esc_attr($unique_id); ?> .mc-sw-shape {
            position: absolute; left: 0; width: 100%; height: <?php echo esc_attr($s_height); ?>px; z-index: 5; pointer-events: none;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-sw-shape-top { top: -1px; }
        .<?php echo esc_attr($unique_id); ?> .mc-sw-shape-bottom { bottom: -1px; }
        @media (max-width: 768px) {
            .<?php echo esc_attr($unique_id); ?> .mc-sw-shape { height: <?php echo esc_attr($s_height * 0.6); ?>px; }
        }
    </style>

    <?php if ($t_shape !== 'none') echo mc_get_simple_divider($t_shape, $s_color, 'top'); ?>
    <div class="mc-sw-inner">
        <?php echo $content; ?>
    </div>
    <?php if ($b_shape !== 'none') echo mc_get_simple_divider($b_shape, $s_color, 'bottom'); ?>
</div>