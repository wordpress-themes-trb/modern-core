<?php
$width_mode = $attributes['widthMode'] ?? 'contained';
if (!class_exists('WooCommerce')) return;

global $product;

// If we are not on a product page or the block is misused, exit gracefully
if (!$product || !is_a($product, 'WC_Product')) return;

// If WooCommerce stock management is disabled for this product, exit
if (!$product->managing_stock()) return;

$qty = $product->get_stock_quantity();

// If out of stock, or stock exceeds our visibility threshold, hide the block entirely
$show_threshold = $attributes['showThreshold'] ?? 20;
if ($qty === null || $qty <= 0 || $qty > $show_threshold) return;

$mode = $attributes['displayMode'] ?? 'bar';
$max = $attributes['maxStock'] ?? 100;
$caution_t = $attributes['cautionThreshold'] ?? 10;
$urgent_t = $attributes['urgentThreshold'] ?? 5;
$neutral_c = $attributes['neutralColor'] ?? '#3b82f6';
$caution_c = $attributes['cautionColor'] ?? '#f59e0b';
$urgent_c = $attributes['urgentColor'] ?? '#ef4444';
$track_c = $attributes['trackColor'] ?? '#e2e8f0';
$text_c = $attributes['textColor'] ?? '#0f172a';
$pulse = $attributes['enablePulse'] ?? true;

$unique_id = uniqid('mc-stock-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "mc-block width-" . esc_attr($width_mode) . " modern-core-stock-countdown {$unique_id}"]);

// Determine active colors and classes
$current_color = $neutral_c;
$status_class = 'is-neutral';
$text_statement = 'Limited stock available';

if ($qty <= $urgent_t) {
    $current_color = $urgent_c;
    $status_class = 'is-urgent';
    $text_statement = 'Almost gone — selling fast';
} elseif ($qty <= $caution_t) {
    $current_color = $caution_c;
    $status_class = 'is-caution';
}

$is_pulsing = ($pulse && $status_class === 'is-urgent');
$pulse_class = $is_pulsing ? 'mc-stock-pulse' : '';
$fill_percent = min(100, max(0, ($qty / $max) * 100));
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            width: 100%;
            margin-bottom: 20px;
        }

        @keyframes mcUrgentPulse {
            0% { opacity: 1; }
            50% { opacity: 0.4; }
            100% { opacity: 1; }
        }

        .mc-stock-pulse {
            animation: mcUrgentPulse 1.5s infinite ease-in-out;
        }

        @media (prefers-reduced-motion: reduce) {
            .mc-stock-pulse { animation: none; }
        }

        /* Mode: Bar */
        .<?php echo esc_attr($unique_id); ?> .mc-st-label {
            display: flex; align-items: center; gap: 8px; margin-bottom: 8px;
            color: <?php echo esc_attr($text_c); ?>; font-weight: bold; font-size: 13px;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-st-dot {
            display: inline-block; width: 8px; height: 8px; border-radius: 50%;
            background-color: <?php echo esc_attr($current_color); ?>;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-st-track {
            width: 100%; height: 8px; background-color: <?php echo esc_attr($track_c); ?>;
            border-radius: 4px; overflow: hidden;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-st-fill {
            height: 100%; border-radius: 4px; background-color: <?php echo esc_attr($current_color); ?>;
            width: <?php echo esc_attr($fill_percent); ?>%; transition: width 0.5s ease;
        }

        /* Mode: Number */
        .<?php echo esc_attr($unique_id); ?> .mc-st-number-wrap {
            color: <?php echo esc_attr($text_c); ?>; font-size: 15px;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-st-number {
            color: <?php echo esc_attr($current_color); ?>; font-weight: 900; font-size: 18px;
        }

        /* Mode: Text */
        .<?php echo esc_attr($unique_id); ?> .mc-st-text {
            color: <?php echo esc_attr($current_color); ?>; font-size: 14px; font-weight: bold;
        }
    </style>

    <?php if ($mode === 'bar') : ?>
        <div class="mc-st-label">
            <span class="mc-st-dot <?php echo esc_attr($pulse_class); ?>"></span>
            Only <?php echo esc_html($qty); ?> left in stock
        </div>
        <div class="mc-st-track">
            <div class="mc-st-fill <?php echo esc_attr($pulse_class); ?>"></div>
        </div>
    <?php elseif ($mode === 'number') : ?>
        <div class="mc-st-number-wrap">
            Only <span class="mc-st-number <?php echo esc_attr($pulse_class); ?>"><?php echo esc_html($qty); ?></span> left!
        </div>
    <?php elseif ($mode === 'text') : ?>
        <div class="mc-st-text <?php echo esc_attr($pulse_class); ?>">
            <?php echo esc_html($text_statement); ?>
        </div>
    <?php endif; ?>
</div>