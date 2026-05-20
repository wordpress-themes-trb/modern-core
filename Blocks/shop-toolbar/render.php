<?php
if (!class_exists('WooCommerce')) return;

$show_count = $attributes['showCount'] ?? true;
$show_sort = $attributes['showSort'] ?? true;
$show_toggles = $attributes['showViewToggles'] ?? true;
$show_filter = $attributes['showFilterTrigger'] ?? true;
$border_bottom = $attributes['borderBottom'] ?? true;
$pad_v = $attributes['paddingVert'] ?? 15;
$text_c = $attributes['textColor'] ?? '#64748b';
$accent = $attributes['accentColor'] ?? '#0f172a';
$border_c = $attributes['borderColor'] ?? '#e2e8f0';

$unique_id = uniqid('mc-toolbar-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "modern-core-shop-toolbar {$unique_id}"]);
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            padding: <?php echo esc_attr($pad_v); ?>px 0;
            margin-bottom: 30px;
            color: <?php echo esc_attr($text_c); ?>;
            font-size: 13px;
            <?php if ($border_bottom) : ?>
                border-bottom: 1px solid <?php echo esc_attr($border_c); ?>;
            <?php endif; ?>
        }

        .<?php echo esc_attr($unique_id); ?> .mc-tb-group {
            display: flex; align-items: center; gap: 20px; flex-wrap: wrap;
        }

        /* Filter Trigger */
        .<?php echo esc_attr($unique_id); ?> .mc-tb-filter-btn {
            display: none; align-items: center; gap: 8px; font-weight: bold; color: <?php echo esc_attr($accent); ?>; cursor: pointer;
            background: none; border: none; padding: 0; font-size: inherit; font-family: inherit;
        }
        @media (max-width: 900px) {
            .<?php echo esc_attr($unique_id); ?> .mc-tb-filter-btn { display: flex; }
        }

        /* WooCommerce Native Overrides */
        .<?php echo esc_attr($unique_id); ?> .woocommerce-result-count { margin: 0; float: none; }
        .<?php echo esc_attr($unique_id); ?> .woocommerce-ordering { margin: 0; float: none; display: flex; align-items: center; gap: 10px; }
        .<?php echo esc_attr($unique_id); ?> .woocommerce-ordering select {
            border: 1px solid <?php echo esc_attr($border_c); ?>; padding: 8px 12px; border-radius: 4px;
            background: transparent; color: inherit; font-size: 13px; outline: none; cursor: pointer;
        }

        /* View Toggles */
        .<?php echo esc_attr($unique_id); ?> .mc-tb-toggles { display: flex; gap: 8px; }
        .<?php echo esc_attr($unique_id); ?> .mc-tb-toggle-btn {
            background: none; border: none; padding: 0; cursor: pointer; color: <?php echo esc_attr($text_c); ?>; opacity: 0.5; transition: all 0.2s;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-tb-toggle-btn:hover { opacity: 0.8; }
        .<?php echo esc_attr($unique_id); ?> .mc-tb-toggle-btn.is-active { opacity: 1; color: <?php echo esc_attr($accent); ?>; }
        .<?php echo esc_attr($unique_id); ?> .mc-tb-toggle-btn svg { stroke: currentColor; fill: none; }
        .<?php echo esc_attr($unique_id); ?> .mc-tb-toggle-btn.is-active svg { fill: currentColor; }
        
        @media (max-width: 768px) {
            .<?php echo esc_attr($unique_id); ?> .mc-tb-toggles { display: none; } /* Mobile forces grid naturally */
        }
    </style>

    <div class="mc-tb-group">
        <?php if ($show_filter) : ?>
            <button class="mc-tb-filter-btn" aria-label="Open Filters">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                Filters
            </button>
        <?php endif; ?>

        <?php if ($show_count) : ?>
            <div class="mc-tb-count">
                <?php woocommerce_result_count(); ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="mc-tb-group">
        <?php if ($show_sort) : ?>
            <div class="mc-tb-sort">
                <?php woocommerce_catalog_ordering(); ?>
            </div>
        <?php endif; ?>

        <?php if ($show_toggles) : ?>
            <div class="mc-tb-toggles">
                <button class="mc-tb-toggle-btn is-active" data-view="grid" aria-label="Grid View">
                    <svg width="20" height="20" viewBox="0 0 24 24" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                </button>
                <button class="mc-tb-toggle-btn" data-view="list" aria-label="List View">
                    <svg width="20" height="20" viewBox="0 0 24 24" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                </button>
            </div>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toolbar = document.querySelector('.<?php echo esc_attr($unique_id); ?>');
            if (!toolbar) return;

            // View Toggles Logic
            const viewBtns = toolbar.querySelectorAll('.mc-tb-toggle-btn');
            const targetGrid = document.querySelector('.modern-core-product-grid');

            if (targetGrid && viewBtns.length > 0) {
                viewBtns.forEach(btn => {
                    btn.addEventListener('click', () => {
                        const viewMode = btn.dataset.view;
                        
                        // Update buttons
                        viewBtns.forEach(b => b.classList.remove('is-active'));
                        btn.classList.add('is-active');

                        // Update grid architecture
                        targetGrid.classList.remove('layout-grid', 'layout-list');
                        targetGrid.classList.add(`layout-${viewMode}`);
                    });
                });
            }

            // Mobile Filter Drawer Trigger
            const filterBtn = toolbar.querySelector('.mc-tb-filter-btn');
            if (filterBtn) {
                filterBtn.addEventListener('click', () => {
                    // Triggers the custom event that an Off-Canvas block will listen for
                    document.dispatchEvent(new CustomEvent('mc:offcanvas:open', { detail: { target: 'filters' } }));
                });
            }
        });
    </script>
</div>