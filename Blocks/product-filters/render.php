<?php
$width_mode = $attributes['widthMode'] ?? 'contained';
$show_price = $attributes['showPriceFilter'] ?? true;
$min_price = $attributes['minPrice'] ?? 0;
$max_price = $attributes['maxPrice'] ?? 250;
$show_color = $attributes['showColorFilter'] ?? true;
$swatch_shape = $attributes['swatchShape'] ?? 'circle';
$swatch_size = $attributes['swatchSize'] ?? 32;
$show_status = $attributes['showStatusFilter'] ?? true;
$show_rating = $attributes['showRatingFilter'] ?? true;
$accent_color = $attributes['accentColor'] ?? '#3b82f6';
$heading_color = $attributes['headingColor'] ?? 'inherit';

$unique_id = uniqid('mc-filter-');
$wrapper_attributes = get_block_wrapper_attributes([
    'class' => "mc-block width-" . esc_attr($width_mode) . " modern-core-filters-engine {$unique_id}",
    'style' => 'display: flex; flex-direction: column; gap: 30px; width: 100%; color: inherit;'
]);

$radius = $swatch_shape === 'circle' ? '50%' : '6px';

// Rendered product colors dataset
$colors_list = [
    ['slug' => 'black', 'hex' => '#000000', 'name' => 'Black'],
    ['slug' => 'white', 'hex' => '#ffffff', 'name' => 'White', 'border' => '1px solid rgba(0,0,0,0.15)'],
    ['slug' => 'oatmeal', 'hex' => '#e4dccb', 'name' => 'Oatmeal'],
    ['slug' => 'olive', 'hex' => '#4a5343', 'name' => 'Olive']
];
?>

<div <?php echo $wrapper_attributes; ?>>
    
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <span style="font-size: 12px; font-weight: bold; text-transform: uppercase; opacity: 0.6;">Refine Catalog</span>
        <button class="mc-clear-all-filters" style="background: none; border: none; padding: 0; color: inherit; font-size: 11px; text-decoration: underline; cursor: pointer; opacity: 0.5;">Clear All</button>
    </div>

    <?php if ($show_price) : ?>
        <div style="border-top: 1px solid rgba(255,255,255,0.05); padding-top: 20px;">
            <h4 style="margin: 0 0 15px 0; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; color: <?php echo esc_attr($heading_color); ?>;">Price Range</h4>
            <div style="display: flex; gap: 15px; align-items: center; margin-bottom: 15px;">
                <input type="number" class="mc-price-min" value="<?php echo esc_attr($min_price); ?>" style="width: 70px; padding: 8px; font-size: 13px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: inherit; border-radius: 4px;" />
                <span style="opacity: 0.4;">to</span>
                <input type="number" class="mc-price-max" value="<?php echo esc_attr($max_price); ?>" style="width: 70px; padding: 8px; font-size: 13px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: inherit; border-radius: 4px;" />
            </div>
            <input type="range" min="0" max="<?php echo esc_attr($max_price); ?>" value="<?php echo esc_attr($max_price); ?>" class="mc-price-range-slider" style="width: 100%; accent-color: <?php echo esc_attr($accent_color); ?>; cursor: pointer;" />
        </div>
    <?php endif; ?>

    <?php if ($show_color) : ?>
        <div style="border-top: 1px solid rgba(255,255,255,0.05); padding-top: 20px;">
            <h4 style="margin: 0 0 15px 0; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; color: <?php echo esc_attr($heading_color); ?>;">Filter By Color</h4>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <?php foreach ($colors_list as $color) : ?>
                    <button 
                        class="mc-color-swatch-btn" 
                        data-slug="<?php echo esc_attr($color['slug']); ?>"
                        style="width: <?php echo $swatch_size; ?>px; height: <?php echo $swatch_size; ?>px; background-color: <?php echo $color['hex']; ?>; border-radius: <?php echo $radius; ?>; border: <?php echo $color['border'] ?? '1px solid transparent'; ?>; cursor: pointer; position: relative; transition: transform 0.2s ease; outline: none;"
                        title="<?php echo esc_attr($color['name']); ?>"
                    ></button>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($show_status) : ?>
        <div style="border-top: 1px solid rgba(255,255,255,0.05); padding-top: 20px;">
            <h4 style="margin: 0 0 15px 0; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; color: <?php echo esc_attr($heading_color); ?>;">Availability</h4>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <label style="display: flex; align-items: center; gap: 10px; font-size: 14px; cursor: pointer; width: fit-content;">
                    <input type="checkbox" class="mc-filter-checkbox" data-type="stock" style="accent-color: <?php echo esc_attr($accent_color); ?>;" />
                    <span>In Stock Only</span>
                </label>
                <label style="display: flex; align-items: center; gap: 10px; font-size: 14px; cursor: pointer; width: fit-content;">
                    <input type="checkbox" class="mc-filter-checkbox" data-type="sale" style="accent-color: <?php echo esc_attr($accent_color); ?>;" />
                    <span>On Sale Items</span>
                </label>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($show_rating) : ?>
        <div style="border-top: 1px solid rgba(255,255,255,0.05); padding-top: 20px;">
            <h4 style="margin: 0 0 15px 0; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; color: <?php echo esc_attr($heading_color); ?>;">Product Rating</h4>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <?php foreach ([5, 4, 3] as $rating) : ?>
                    <button class="mc-rating-filter-row" data-stars="<?php echo $rating; ?>" style="background: none; border: none; padding: 0; text-align: left; display: flex; alignItems: center; gap: 10px; font-size: 14px; cursor: pointer; color: inherit; font-family: inherit;">
                        <span style="color: #f59e0b; letter-spacing: 2px;"><?php echo str_repeat('★', $rating) . str_repeat('☆', 5 - $rating); ?></span>
                        <span style="font-size: 12px; opacity: 0.7;">& Up</span>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.querySelector('.<?php echo $unique_id; ?>');
            if (!container) return;

            // Internal State Schema Object 
            let filterState = {
                priceMin: <?php echo $min_price; ?>,
                priceMax: <?php echo $max_price; ?>,
                colors: [],
                inStock: false,
                onSale: false,
                rating: null
            };

            const dispatchStateChange = () => {
                // Programmatic hook to feed updates directly into asynchronous catalogs (AJAX Product Grid)
                const event = new CustomEvent('mc:filters:changed', { detail: filterState });
                document.dispatchEvent(event);
                console.log('ModernCore Filter State Updated:', filterState);
            };

            // Color selection management loop
            container.querySelectorAll('.mc-color-swatch-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const slug = btn.dataset.slug;
                    if (filterState.colors.includes(slug)) {
                        filterState.colors = filterState.colors.filter(c => c !== slug);
                        btn.style.boxShadow = 'none';
                        btn.style.transform = 'scale(1)';
                    } else {
                        filterState.colors.push(slug);
                        btn.style.boxShadow = '0 0 0 2px <?php echo esc_attr($accent_color); ?>';
                        btn.style.transform = 'scale(1.1)';
                    }
                    dispatchStateChange();
                });
            });

            // Range slider input mapping hooks
            const rangeSlider = container.querySelector('.mc-price-range-slider');
            const maxInput = container.querySelector('.mc-price-max');
            if (rangeSlider && maxInput) {
                rangeSlider.addEventListener('input', (e) => {
                    maxInput.value = e.target.value;
                    filterState.priceMax = parseInt(e.target.value);
                    dispatchStateChange();
                });
            }

            // Checkbox value toggle assignments
            container.querySelectorAll('.mc-filter-checkbox').forEach(cb => {
                cb.addEventListener('change', (e) => {
                    if (cb.dataset.type === 'stock') filterState.inStock = e.target.checked;
                    if (cb.dataset.type === 'sale') filterState.onSale = e.target.checked;
                    dispatchStateChange();
                });
            });

            // Star row clicks mapping matrix
            container.querySelectorAll('.mc-rating-filter-row').forEach(row => {
                row.addEventListener('click', () => {
                    const targetStars = parseInt(row.dataset.stars);
                    
                    container.querySelectorAll('.mc-rating-filter-row').forEach(r => r.style.opacity = '0.5');
                    
                    if (filterState.rating === targetStars) {
                        filterState.rating = null;
                        container.querySelectorAll('.mc-rating-filter-row').forEach(r => r.style.opacity = '1');
                    } else {
                        filterState.rating = targetStars;
                        row.style.opacity = '1';
                    }
                    dispatchStateChange();
                });
            });

            // Global reset state callback
            const clearBtn = container.querySelector('.mc-clear-all-filters');
            if (clearBtn) {
                clearBtn.addEventListener('click', () => {
                    filterState = { priceMin: 0, priceMax: <?php echo $max_price; ?>, colors: [], inStock: false, onSale: false, rating: null };
                    container.querySelectorAll('.mc-color-swatch-btn').forEach(b => b.style.boxShadow = 'none');
                    container.querySelectorAll('.mc-filter-checkbox').forEach(c => c.checked = false);
                    container.querySelectorAll('.mc-rating-filter-row').forEach(r => r.style.opacity = '1');
                    if(rangeSlider) rangeSlider.value = <?php echo $max_price; ?>;
                    if(maxInput) maxInput.value = <?php echo $max_price; ?>;
                    dispatchStateChange();
                });
            }
        });
    </script>
</div>