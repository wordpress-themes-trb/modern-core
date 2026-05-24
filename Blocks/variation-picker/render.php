<?php
$width_mode = $attributes['widthMode'] ?? 'contained';
if (!class_exists('WooCommerce')) return;

global $product;
// Ensure we only process variable products
if (empty($product) || !$product->is_type('variable')) {
    if (is_admin()) echo '<div style="padding:20px; border:1px dashed #ccc;">Variation Picker: Variable product required.</div>';
    return;
}

$shape = $attributes['swatchShape'] ?? 'circle';
$size = $attributes['swatchSize'] ?? 32;
$rad = $attributes['btnRadius'] ?? 6;
$gap = $attributes['gap'] ?? 12;
$live_label = $attributes['showLiveLabel'] ?? true;
$acc = $attributes['accentColor'] ?? '#3b82f6';
$text_c = $attributes['textColor'] ?? '#0f172a';
$border_c = $attributes['borderColor'] ?? '#e2e8f0';
$disabled_style = $attributes['disabledStyle'] ?? 'strikethrough';

$unique_id = uniqid('mc-var-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "mc-block width-" . esc_attr($width_mode) . " modern-core-variation-engine {$unique_id} shape-{$shape} disabled-{$disabled_style}"]);
?>

<div <?php echo $wrapper_attributes; ?> data-live="<?php echo esc_attr($live_label); ?>">
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            --mc-var-size: <?php echo esc_attr($size); ?>px;
            --mc-var-rad: <?php echo esc_attr($rad); ?>px;
            --mc-var-gap: <?php echo esc_attr($gap); ?>px;
            --mc-var-acc: <?php echo esc_attr($acc); ?>;
            --mc-var-txt: <?php echo esc_attr($text_c); ?>;
            --mc-var-border: <?php echo esc_attr($border_c); ?>;
            
            width: 100%; color: var(--mc-var-txt);
        }

        /* Hide Native Selects & Clean Default Table */
        .<?php echo esc_attr($unique_id); ?> table.variations { width: 100%; border: none; border-collapse: collapse; }
        .<?php echo esc_attr($unique_id); ?> table.variations td { padding: 0 0 25px 0; display: block; border: none; }
        .<?php echo esc_attr($unique_id); ?> table.variations td.label { padding: 0 0 10px 0; font-size: 14px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; }
        .<?php echo esc_attr($unique_id); ?> table.variations select { display: none !important; }
        .<?php echo esc_attr($unique_id); ?> a.reset_variations { display: inline-block; font-size: 12px; margin-top: 10px; color: #ef4444; opacity: 0.8; }

        /* The Live Label Injection */
        .<?php echo esc_attr($unique_id); ?> .mc-live-label { font-weight: normal; opacity: 0.7; text-transform: none; margin-left: 8px; }

        /* Custom Track Injection */
        .<?php echo esc_attr($unique_id); ?> .mc-var-track { display: flex; flex-wrap: wrap; gap: var(--mc-var-gap); }

        /* --- Buttons (Default for sizes/misc) --- */
        .<?php echo esc_attr($unique_id); ?> .mc-var-btn {
            padding: 10px 20px; border: 1px solid var(--mc-var-border); border-radius: var(--mc-var-rad);
            font-size: 14px; font-weight: bold; cursor: pointer; transition: all 0.2s; background: transparent; color: inherit; user-select: none;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-var-btn:hover { border-color: var(--mc-var-acc); }
        .<?php echo esc_attr($unique_id); ?> .mc-var-btn.is-selected { border-color: var(--mc-var-acc); background: var(--mc-var-acc); color: #fff; }

        /* --- Swatches (Auto-detected for Color) --- */
        .<?php echo esc_attr($unique_id); ?> .mc-var-swatch {
            width: var(--mc-var-size); height: var(--mc-var-size); border: 1px solid var(--mc-var-border); padding: 2px;
            cursor: pointer; transition: all 0.2s; position: relative; display: flex; justify-content: center; align-items: center;
        }
        .<?php echo esc_attr($unique_id); ?>.shape-circle .mc-var-swatch, .<?php echo esc_attr($unique_id); ?>.shape-circle .mc-var-swatch-inner { border-radius: 50%; }
        .<?php echo esc_attr($unique_id); ?>.shape-square .mc-var-swatch, .<?php echo esc_attr($unique_id); ?>.shape-square .mc-var-swatch-inner { border-radius: 0; }
        .<?php echo esc_attr($unique_id); ?>.shape-rounded .mc-var-swatch, .<?php echo esc_attr($unique_id); ?>.shape-rounded .mc-var-swatch-inner { border-radius: 6px; }
        
        .<?php echo esc_attr($unique_id); ?> .mc-var-swatch-inner { width: 100%; height: 100%; display: block; border: 1px solid rgba(0,0,0,0.05); }
        .<?php echo esc_attr($unique_id); ?> .mc-var-swatch.is-selected { border-color: var(--mc-var-acc); box-shadow: 0 0 0 1px var(--mc-var-acc); }

        /* Tooltip */
        .<?php echo esc_attr($unique_id); ?> .mc-var-swatch::after {
            content: attr(data-tooltip); position: absolute; bottom: calc(100% + 8px); left: 50%; transform: translateX(-50%) translateY(5px);
            background: #0f172a; color: #fff; padding: 4px 8px; border-radius: 4px; font-size: 11px; white-space: nowrap;
            opacity: 0; pointer-events: none; transition: all 0.2s; z-index: 10;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-var-swatch:hover::after { opacity: 1; transform: translateX(-50%) translateY(0); }

        /* --- Disabled States (WooCommerce Stock Matrix) --- */
        .<?php echo esc_attr($unique_id); ?>.disabled-strikethrough .mc-var-item.is-disabled { opacity: 0.4; position: relative; overflow: hidden; pointer-events: none; cursor: not-allowed; }
        .<?php echo esc_attr($unique_id); ?>.disabled-strikethrough .mc-var-item.is-disabled::before {
            content: ''; position: absolute; top: 50%; left: -10%; width: 120%; height: 1px; background: currentColor; transform: rotate(-30deg); z-index: 2;
        }
        .<?php echo esc_attr($unique_id); ?>.disabled-strikethrough .mc-var-swatch.is-disabled::before { background: #ef4444; height: 2px; } /* Red strike for color swatches */

        .<?php echo esc_attr($unique_id); ?>.disabled-faded .mc-var-item.is-disabled { opacity: 0.2; pointer-events: none; }
        .<?php echo esc_attr($unique_id); ?>.disabled-hidden .mc-var-item.is-disabled { display: none !important; }

        /* Price Animation Override */
        .<?php echo esc_attr($unique_id); ?> .woocommerce-variation-price { animation: mcPriceFadeIn 0.3s cubic-bezier(0.165, 0.84, 0.44, 1); margin-bottom: 20px; font-size: 22px; font-weight: bold; color: var(--mc-var-acc); }
        @keyframes mcPriceFadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }
    </style>

    <?php 
    // Output Native WC Form Structure
    woocommerce_template_single_add_to_cart(); 
    ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const wrapper = document.querySelector('.<?php echo esc_attr($unique_id); ?>');
            if (!wrapper) return;

            const isLiveLabel = wrapper.dataset.live === '1';

            // Utility: Basic color mapping. In a real environment, read from terms meta.
            const colorMap = {
                'black': '#000000', 'white': '#ffffff', 'red': '#ef4444', 'blue': '#3b82f6', 
                'green': '#10b981', 'yellow': '#eab308', 'navy': '#1e3a8a', 'charcoal': '#334155'
            };

            // 1. Build the Custom DOM
            const selects = wrapper.querySelectorAll('table.variations select');
            
            selects.forEach(select => {
                const td = select.closest('td.value');
                const labelTd = select.closest('tr').querySelector('td.label label');
                const attrName = select.getAttribute('name');
                const isColor = attrName.toLowerCase().includes('color');

                // Prepare Label Injection
                let liveLabelEl;
                if (isLiveLabel && labelTd) {
                    liveLabelEl = document.createElement('span');
                    liveLabelEl.className = 'mc-live-label';
                    labelTd.appendChild(liveLabelEl);
                }

                // Create Track
                const track = document.createElement('div');
                track.className = 'mc-var-track';

                // Parse Options
                Array.from(select.options).forEach(option => {
                    if (!option.value) return; // Skip "Choose an option..."

                    const item = document.createElement('div');
                    item.className = `mc-var-item ${isColor ? 'mc-var-swatch' : 'mc-var-btn'}`;
                    item.dataset.value = option.value;
                    item.dataset.selectId = select.id;

                    if (isColor) {
                        const cleanVal = option.value.toLowerCase().replace(/-/g, ' ');
                        let bg = colorMap[cleanVal] || '#ccc'; // Fallback
                        // Handle multi-word matching loosely
                        Object.keys(colorMap).forEach(key => { if(cleanVal.includes(key)) bg = colorMap[key]; });
                        
                        item.innerHTML = `<div class="mc-var-swatch-inner" style="background-color: ${bg}"></div>`;
                        item.dataset.tooltip = option.text;
                    } else {
                        item.innerText = option.text;
                    }

                    // Click handler
                    item.addEventListener('click', () => {
                        if (item.classList.contains('is-disabled')) return;
                        
                        // Set the hidden select value
                        select.value = option.value;
                        
                        // Trigger native WooCommerce JS event
                        if (typeof jQuery !== 'undefined') {
                            jQuery(select).trigger('change');
                        }

                        // Update our UI (handled globally via WC hooks below, but immediate feedback here)
                        Array.from(track.children).forEach(c => c.classList.remove('is-selected'));
                        item.classList.add('is-selected');

                        if (liveLabelEl) liveLabelEl.innerText = option.text;
                    });

                    track.appendChild(item);
                });

                td.insertBefore(track, select);
            });

            // 2. Hook into WooCommerce Native Events
            if (typeof jQuery !== 'undefined') {
                const $form = jQuery(wrapper).find('.variations_form');

                // When WooCommerce recalculates available options (based on stock/logic)
                $form.on('woocommerce_update_variation_values', function() {
                    selects.forEach(select => {
                        const track = select.closest('td.value').querySelector('.mc-var-track');
                        if (!track) return;

                        // Check which options are currently allowed by WC
                        const allowedOptions = Array.from(select.options).filter(o => !o.disabled).map(o => o.value);

                        // Disable our custom UI items that aren't allowed
                        Array.from(track.children).forEach(item => {
                            if (!allowedOptions.includes(item.dataset.value)) {
                                item.classList.add('is-disabled');
                                item.classList.remove('is-selected');
                            } else {
                                item.classList.remove('is-disabled');
                            }
                        });
                    });
                });

                // Clear/Reset selection handling
                $form.on('reset_data', function() {
                    wrapper.querySelectorAll('.mc-var-item').forEach(i => i.classList.remove('is-selected'));
                    wrapper.querySelectorAll('.mc-live-label').forEach(l => l.innerText = '');
                });
            }
        });
    </script>
</div>