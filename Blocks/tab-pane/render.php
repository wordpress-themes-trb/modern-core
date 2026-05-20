<?php
// blocks/tab-pane/render.php

$title = $attributes['tabTitle'] ?? 'New Tab';
$icon = $attributes['tabIcon'] ?? '';

$wrapper_attributes = get_block_wrapper_attributes([
    'class' => 'mc-tab-pane',
    'data-title' => esc_attr($title),
    'data-icon' => esc_attr($icon)
]);
?>

<div <?php echo $wrapper_attributes; ?>>
    <?php echo $content; // This outputs whatever blocks the user placed inside the tab ?>
</div>