<?php
if (!class_exists('WooCommerce')) return;

$placement = $attributes['placementMode'] ?? 'sidebar-left';
$filters = $attributes['filters'] ?? [];
$show_counts = $attributes['showCounts'] ?? true;
$show_chips = $attributes['showActiveChips'] ?? true;
$chip_bg = $attributes['chipBgColor'] ?? '#f1f5f9';
$chip_text = $attributes['chipTextColor'] ?? '#0f172a';
$head_c = $attributes['headingColor'] ?? '#0f172a';
$accent = $attributes['accentColor'] ?? '#3b82f6';

$unique_id = uniqid('mc-filter-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "modern-core-filter-sidebar {$unique_id} placement-{$placement}"]);

// Active Filter Logic (Simulated for this block context)
// In a full theme, this parses $_GET variables (e.g. ?min_price=50&filter_color=black)
$active_filters = []; 
if (isset($_GET['min_price']) && isset($_GET['max_price'])) {
    $active_filters[] = ['type' => 'price', 'label' => '$' . $_GET['min_price'] . ' - $' . $_GET['max_price'], 'key' => 'price'];
}
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            --mc-accent: <?php echo esc_attr($accent); ?>;
            --mc-head: <?php echo esc_attr($head_c); ?>;
            background: #fff;
        }

        /* Placement Logic */
        .<?php echo esc_attr($unique_id); ?>.placement-sidebar-left,
        .<?php echo esc_attr($unique_id); ?>.placement-sidebar-right {
            width: 300px; padding: 20px 0;
        }
        .<?php echo esc_attr($unique_id); ?>.placement-horizontal {
            width: 100%; display: flex; flex-wrap: wrap; gap: 20px; align-items: flex-start;
        }
        .<?php echo esc_attr($unique_id); ?>.placement-horizontal .mc-f-group {
            width: 250px; border-bottom: none; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; background: #fff;
        }

        @media (max-width: 900px) {
            .<?php echo esc_attr($unique_id); ?> {
                width: 100% !important;
                display: none; /* In a full theme, mobile filters move into the Off-Canvas Drawer block */
            }
            .<?php echo esc_attr($unique_id); ?>.is-mobile-open { display: block; }
        }

        /* Active Chips */
        .<?php echo esc_attr($unique_id); ?> .mc-f-chips { margin-bottom: 25px; }
        .<?php echo esc_attr($unique_id); ?> .mc-f-chips-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .<?php echo esc_attr($unique_id); ?> .mc-f-chip {
            display: inline-flex; align-items: center; gap: 8px; padding: 6px 12px;
            background: <?php echo esc_attr($chip_bg); ?>; color: <?php echo esc_attr($chip_text); ?>;
            border-radius: 50px; font-size: 12px; font-weight: bold; text-decoration: none;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-f-chip:hover { opacity: 0.8; }

        /* Filter Groups */
        .<?php echo esc_attr($unique_id); ?> .mc-f-group { border-bottom: 1px solid #e2e8f0; padding-bottom: 20px; margin-bottom: 20px; }
        .<?php echo esc_attr($unique_id); ?> .mc-f-group:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
        .<?php echo esc_attr($unique_id); ?> .mc-f-group-head {
            display: flex; justify-content: space-between; align-items: center; cursor: pointer;
            font-size: 13px; text-transform: uppercase; letter-spacing: 1px; font-weight: bold; color: var(--mc-head);
        }
        .<?php echo esc_attr($unique_id); ?> .mc-f-group-head svg { transition: transform 0.3s; }
        .<?php echo esc_attr($unique_id); ?> .mc-f-group.is-collapsed .mc-f-group-head svg { transform: rotate(180deg); }
        .<?php echo esc_attr($unique_id); ?> .mc-f-content { margin-top: 15px; display: block; }
        .<?php echo esc_attr($unique_id); ?> .mc-f-group.is-collapsed .mc-f-content { display: none; }

        /* Types */
        /* Checkbox */
        .<?php echo esc_attr($unique_id); ?> .mc-f-cb-item { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; cursor: pointer; }
        .<?php echo esc_attr($unique_id); ?> .mc-f-cb-item:last-child { margin-bottom: 0; }
        .<?php echo esc_attr($unique_id); ?> .mc-f-cb-label { font-size: 14px; color: #0f172a; display: flex; align-items: center; gap: 10px; }
        .<?php echo esc_attr($unique_id); ?> input[type="checkbox"] { width: 16px; height: 16px; accent-color: var(--mc-accent); cursor: pointer; }
        .<?php echo esc_attr($unique_id); ?> .mc-f-count { font-size: 12px; color: #94a3b8; }

        /* Color Swatches */
        .<?php echo esc_attr($unique_id); ?> .mc-f-color-wrap { display: flex; flex-wrap: wrap; gap: 10px; }
        .<?php echo esc_attr($unique_id); ?> .mc-f-color {
            width: 24px; height: 24px; border-radius: 50%; cursor: pointer; position: relative;
        }
        .<?php echo esc_attr($unique_id); ?> input[type="checkbox"].mc-f-color-cb { display: none; }
        .<?php echo esc_attr($unique_id); ?> input[type="checkbox"].mc-f-color-cb:checked + .mc-f-color { box-shadow: 0 0 0 2px #fff, 0 0 0 4px var(--mc-accent); }

        /* Pills */
        .<?php echo esc_attr($unique_id); ?> .mc-f-pill-wrap { display: flex; flex-wrap: wrap; gap: 8px; }
        .<?php echo esc_attr($unique_id); ?> .mc-f-pill {
            padding: 6px 12px; font-size: 13px; border: 1px solid #e2e8f0; border-radius: 4px; cursor: pointer; transition: all 0.2s;
        }
        .<?php echo esc_attr($unique_id); ?> input[type="checkbox"].mc-f-pill-cb { display: none; }
        .<?php echo esc_attr($unique_id); ?> input[type="checkbox"].mc-f-pill-cb:checked + .mc-f-pill {
            background: var(--mc-accent); color: #fff; border-color: var(--mc-accent); font-weight: bold;
        }

        /* Submit Button (Hidden usually for AJAX, visible as fallback) */
        .<?php echo esc_attr($unique_id); ?> .mc-f-submit {
            width: 100%; padding: 12px; background: var(--mc-accent); color: #fff; font-weight: bold; border: none; border-radius: 4px; cursor: pointer; margin-top: 20px;
        }
    </style>

    <form class="mc-filter-form" action="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" method="GET">
        
        <?php if ($show_chips && !empty($active_filters)) : ?>
            <div class="mc-f-chips">
                <div class="mc-f-chips-head">
                    <span style="font-size: 13px; font-weight: bold; color: var(--mc-head);">Active Filters</span>
                    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" style="font-size: 12px; color: #ef4444; text-decoration: underline;">Clear All</a>
                </div>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <?php foreach ($active_filters as $f) : ?>
                        <a href="#" class="mc-f-chip"><?php echo esc_html($f['label']); ?> <span style="opacity: 0.5;">✕</span></a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php foreach ($filters as $index => $filter) : 
            $is_collapsed = !$filter['expanded'] ? 'is-collapsed' : '';
        ?>
            <div class="mc-f-group <?php echo $is_collapsed; ?>">
                <div class="mc-f-group-head">
                    <?php echo esc_html($filter['label']); ?>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </div>
                <div class="mc-f-content">
                    
                    <?php if ($filter['type'] === 'checkbox') : 
                        // Simulated data. A real implementation fetches `get_terms` for the specific taxonomy
                        $terms = ['Clothing' => 24, 'Accessories' => 12, 'Shoes' => 8];
                    ?>
                        <?php foreach ($terms as $name => $count) : ?>
                            <label class="mc-f-cb-item">
                                <div class="mc-f-cb-label">
                                    <input type="checkbox" name="filter_<?php echo esc_attr($filter['attribute']); ?>[]" value="<?php echo esc_attr(strtolower($name)); ?>" class="mc-ajax-trigger" />
                                    <span><?php echo esc_html($name); ?></span>
                                </div>
                                <?php if ($show_counts) echo '<span class="mc-f-count">('.esc_html($count).')</span>'; ?>
                            </label>
                        <?php endforeach; ?>

                    <?php elseif ($filter['type'] === 'color') : 
                        $colors = ['#000000' => 'black', '#ffffff' => 'white', '#3b82f6' => 'blue', '#ef4444' => 'red'];
                    ?>
                        <div class="mc-f-color-wrap">
                            <?php foreach ($colors as $hex => $slug) : ?>
                                <label>
                                    <input type="checkbox" name="filter_<?php echo esc_attr($filter['attribute']); ?>[]" value="<?php echo esc_attr($slug); ?>" class="mc-f-color-cb mc-ajax-trigger" />
                                    <div class="mc-f-color" style="background-color: <?php echo esc_attr($hex); ?>; border: <?php echo $hex==='#ffffff' ? '1px solid #cbd5e1' : 'none'; ?>" title="<?php echo esc_attr(ucfirst($slug)); ?>"></div>
                                </label>
                            <?php endforeach; ?>
                        </div>

                    <?php elseif ($filter['type'] === 'pill') : 
                        $sizes = ['S', 'M', 'L', 'XL'];
                    ?>
                        <div class="mc-f-pill-wrap">
                            <?php foreach ($sizes as $size) : ?>
                                <label>
                                    <input type="checkbox" name="filter_<?php echo esc_attr($filter['attribute']); ?>[]" value="<?php echo esc_attr(strtolower($size)); ?>" class="mc-f-pill-cb mc-ajax-trigger" />
                                    <div class="mc-f-pill"><?php echo esc_html($size); ?></div>
                                </label>
                            <?php endforeach; ?>
                        </div>

                    <?php elseif ($filter['type'] === 'price') : ?>
                        <div style="padding: 10px 0;">
                            <input type="range" min="0" max="1000" style="width: 100%; accent-color: var(--mc-accent);" class="mc-ajax-trigger" />
                            <div style="display: flex; justify-content: space-between; margin-top: 10px; font-size: 13px; color: #64748b;">
                                <span>Min: $0</span>
                                <span>Max: $1000</span>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        <?php endforeach; ?>

        <noscript><button type="submit" class="mc-f-submit">Apply Filters</button></noscript>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const wrapper = document.querySelector('.<?php echo esc_attr($unique_id); ?>');
            if (!wrapper) return;

            // Group Toggle Logic
            const heads = wrapper.querySelectorAll('.mc-f-group-head');
            heads.forEach(head => {
                head.addEventListener('click', function() {
                    this.parentElement.classList.toggle('is-collapsed');
                });
            });

            // AJAX Trigger Logic
            const form = wrapper.querySelector('.mc-filter-form');
            const triggers = wrapper.querySelectorAll('.mc-ajax-trigger');

            triggers.forEach(trigger => {
                trigger.addEventListener('change', () => {
                    // 1. Build Query String
                    const formData = new FormData(form);
                    const params = new URLSearchParams(formData);
                    
                    // 2. Visual Loading State on Product Grid
                    const grid = document.querySelector('.modern-core-product-grid');
                    if (grid) grid.style.opacity = '0.5';

                    // 3. In a full theme, this fires an AJAX request to fetch new products
                    // Example: fetch(`${mc_ajax.ajaxurl}?action=mc_filter_products&${params.toString()}`)
                    // Or relies on standard WC redirection if AJAX is disabled
                    console.log('AJAX Filter Triggered:', params.toString());
                    
                    // Simulate loading finish
                    setTimeout(() => { if (grid) grid.style.opacity = '1'; }, 500);
                });
            });
        });
    </script>
</div>