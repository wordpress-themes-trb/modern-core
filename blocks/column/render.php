<?php
$bg = $attributes['bgColor'] ?? 'transparent';
$pad = $attributes['padding'] ?? 0;
$rad = $attributes['radius'] ?? 0;
$border_c = $attributes['borderColor'] ?? 'transparent';
$hide = $attributes['hideOnMobile'] ?? false;

$unique_id = uniqid('mc-col-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "modern-core-column mc-column {$unique_id}"]);
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            background-color: <?php echo esc_attr($bg); ?>;
            padding: <?php echo esc_attr($pad); ?>px;
            border-radius: <?php echo esc_attr($rad); ?>px;
            <?php if ($border_c !== 'transparent') echo "border: 1px solid " . esc_attr($border_c) . ";"; ?>
            min-width: 0; /* Prevents flex/grid blowouts */
        }

        <?php if ($hide) : ?>
        @media (max-width: 768px) {
            .<?php echo esc_attr($unique_id); ?> { display: none !important; }
        }
        <?php endif; ?>
    </style>

    <?php echo $content; ?>
</div>