<?php
$width_mode = $attributes['widthMode'] ?? 'contained';
$layout = $attributes['layout'] ?? '1fr 1fr';
$gap = $attributes['gap'] ?? 30;
$align = $attributes['vAlign'] ?? 'start';
$mobile = $attributes['mobileStack'] ?? 'stack';
$reverse = $attributes['reverseMobile'] ?? false;
$dividers = $attributes['showDividers'] ?? false;
$div_color = $attributes['dividerColor'] ?? '#e2e8f0';

$unique_id = uniqid('mc-cols-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "mc-block width-" . esc_attr($width_mode) . " modern-core-columns {$unique_id} m-{$mobile}"]);
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            --cols-gap: <?php echo esc_attr($gap); ?>px;
            --cols-div: <?php echo esc_attr($div_color); ?>;
            
            display: grid;
            grid-template-columns: <?php echo esc_attr($layout); ?>;
            gap: var(--cols-gap);
            align-items: <?php echo esc_attr($align); ?>;
            width: 100%;
        }

        /* Divider Logic */
        <?php if ($dividers) : ?>
            .<?php echo esc_attr($unique_id); ?> > .mc-column:not(:last-child) {
                border-right: 1px solid var(--cols-div);
                padding-right: calc(var(--cols-gap) / 2);
            }
        <?php endif; ?>

        /* Mobile Stacking Logic */
        @media (max-width: 768px) {
            .<?php echo esc_attr($unique_id); ?>.m-stack {
                grid-template-columns: 1fr;
                gap: calc(var(--cols-gap) * 0.8);
            }
            <?php if ($reverse) : ?>
                .<?php echo esc_attr($unique_id); ?>.m-stack { display: flex; flex-direction: column-reverse; }
                .<?php echo esc_attr($unique_id); ?>.m-stack > .mc-column { width: 100%; }
            <?php endif; ?>
            
            <?php if ($dividers) : ?>
                .<?php echo esc_attr($unique_id); ?>.m-stack > .mc-column:not(:last-child) {
                    border-right: none; padding-right: 0;
                    border-bottom: 1px solid var(--cols-div); padding-bottom: calc(var(--cols-gap) * 0.8);
                }
            <?php endif; ?>
        }
    </style>

    <?php echo $content; ?>
</div>