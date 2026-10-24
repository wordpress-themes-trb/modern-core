<?php
$width_mode = $attributes['widthMode'] ?? 'contained';
if (!class_exists('WooCommerce')) return;

$title = $attributes['sectionTitle'] ?? 'Frequently bought together';
$source = $attributes['sourceMode'] ?? 'dynamic';
$manual_ids = $attributes['manualIds'] ?? '';
$mode = $attributes['displayMode'] ?? 'checkbox';
$discount_type = $attributes['discountType'] ?? 'percent';
$discount_val = $attributes['discountValue'] ?? 10;
$lock_main = $attributes['lockMainProduct'] ?? true;
$accent = $attributes['accentColor'] ?? '#0f172a';
$badge_color = $attributes['badgeColor'] ?? '#10b981';

$unique_id = uniqid('mc-bundle-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "mc-block width-" . esc_attr($width_mode) . " modern-core-bundle {$unique_id} mode-{$mode}"]);

// Gather Product IDs
$product_ids = [];
global $product;

if ($source === 'dynamic' && is_product() && $product) {
    $product_ids[] = $product->get_id();
    $upsells = $product->get_upsell_ids();
    if (!empty($upsells)) {
        $product_ids = array_merge($product_ids, array_slice($upsells, 0, 3));
    }
} elseif ($source === 'manual' && !empty($manual_ids)) {
    if (is_product() && $product && $lock_main) $product_ids[] = $product->get_id(); // Ensure main product is included
    $manual = array_map('intval', explode(',', $manual_ids));
    $product_ids = array_merge($product_ids, $manual);
}

// Remove duplicates and ensure we have at least 2 products to form a bundle
$product_ids = array_unique($product_ids);
if (count($product_ids) < 2) return;

$bundle_products = [];
foreach ($product_ids as $id) {
    $p = wc_get_product($id);
    if ($p && $p->is_in_stock()) $bundle_products[] = $p;
}
if (count($bundle_products) < 2) return;
?>

<div <?php echo $wrapper_attributes; ?> 
    data-dtype="<?php echo esc_attr($discount_type); ?>" 
    data-dval="<?php echo esc_attr($discount_val); ?>"
    data-currency="<?php echo esc_attr(get_woocommerce_currency_symbol()); ?>">
    
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            width: 100%; padding: 40px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;
            margin: 40px 0;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-b-title { margin: 0 0 25px 0; font-size: 22px; font-weight: 800; color: #0f172a; }

        /* CHECKBOX MODE */
        .<?php echo esc_attr($unique_id); ?> .mc-b-list { display: flex; flex-direction: column; gap: 10px; margin-bottom: 25px; }
        .<?php echo esc_attr($unique_id); ?> .mc-b-item {
            display: flex; align-items: center; gap: 15px; padding: 15px; background: #fff;
            border: 1px solid #e2e8f0; border-radius: 8px; transition: border-color 0.2s;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-b-item.is-unselected { opacity: 0.6; }
        .<?php echo esc_attr($unique_id); ?> input[type="checkbox"] { width: 18px; height: 18px; accent-color: <?php echo esc_attr($accent); ?>; cursor: pointer; }
        .<?php echo esc_attr($unique_id); ?> input[type="checkbox"]:disabled { opacity: 0.5; cursor: not-allowed; }
        .<?php echo esc_attr($unique_id); ?> .mc-b-img { width: 40px; height: 40px; object-fit: cover; border-radius: 4px; }
        .<?php echo esc_attr($unique_id); ?> .mc-b-name { flex: 1; font-size: 14px; font-weight: 500; color: #0f172a; text-decoration: none; }
        .<?php echo esc_attr($unique_id); ?> .mc-b-price { font-weight: bold; font-size: 14px; }

        /* TOTALS AREA */
        .<?php echo esc_attr($unique_id); ?> .mc-b-totals {
            display: flex; align-items: center; justify-content: space-between; padding: 25px; background: #fff;
            border: 1px solid #e2e8f0; border-radius: 8px;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-b-final-price { font-size: 28px; font-weight: 800; color: #0f172a; margin-right: 10px; }
        .<?php echo esc_attr($unique_id); ?> .mc-b-old-price { font-size: 16px; color: #94a3b8; text-decoration: line-through; }
        .<?php echo esc_attr($unique_id); ?> .mc-b-badge {
            display: inline-block; padding: 4px 10px; background: <?php echo esc_attr($badge_color); ?>; color: #fff;
            font-size: 11px; font-weight: bold; border-radius: 4px; margin-top: 5px;
        }
        
        .<?php echo esc_attr($unique_id); ?> .mc-b-add-btn {
            padding: 16px 35px; background: <?php echo esc_attr($accent); ?>; color: #fff; border: none;
            border-radius: 50px; font-weight: bold; font-size: 15px; cursor: pointer; transition: opacity 0.2s;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-b-add-btn:hover { opacity: 0.8; }
        .<?php echo esc_attr($unique_id); ?> .mc-b-add-btn.loading { opacity: 0.5; pointer-events: none; }

        @media (max-width: 768px) {
            .<?php echo esc_attr($unique_id); ?> { padding: 25px 15px; }
            .<?php echo esc_attr($unique_id); ?> .mc-b-totals { flex-direction: column; align-items: flex-start; gap: 20px; }
            .<?php echo esc_attr($unique_id); ?> .mc-b-add-btn { width: 100%; }
        }
    </style>

    <?php if ( "" !== trim( (string) $title ) ) : ?><h3 class="mc-b-title"><?php echo esc_html($title); ?></h3><?php endif; ?>

    <div class="mc-b-list">
        <?php foreach ($bundle_products as $index => $p) : 
            $is_main = ($source === 'dynamic' && $index === 0);
            $locked = ($is_main && $lock_main) ? 'disabled checked' : 'checked';
            $p_price = wc_get_price_to_display($p);
        ?>
            <label class="mc-b-item">
                <input type="checkbox" class="mc-b-check" value="<?php echo esc_attr($p->get_id()); ?>" data-price="<?php echo esc_attr($p_price); ?>" <?php echo $locked; ?> />
                <img src="<?php echo esc_url(modern_core_product_image_url( $p, 'thumbnail' )); ?>" class="mc-b-img" />
                <a href="<?php echo esc_url($p->get_permalink()); ?>" class="mc-b-name">
                    <?php echo $p->get_name(); ?>
                    <?php if ($is_main && $lock_main) echo '<span style="opacity: 0.5; font-size: 12px; margin-left: 5px;">(This Item)</span>'; ?>
                </a>
                <span class="mc-b-price"><?php echo wc_price($p_price); ?></span>
            </label>
        <?php endforeach; ?>
    </div>

    <div class="mc-b-totals">
        <div>
            <div style="font-size: 13px; color: #64748b; margin-bottom: 5px;">Bundle Total:</div>
            <div>
                <span class="mc-b-final-price"></span>
                <span class="mc-b-old-price"></span>
            </div>
            <div class="mc-b-badge-wrap" style="display: none;">
                <span class="mc-b-badge"></span>
            </div>
        </div>
        <button class="mc-b-add-btn">Add Selected to Cart</button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const wrapper = document.querySelector('.<?php echo esc_attr($unique_id); ?>');
            if (!wrapper) return;

            const checkboxes = wrapper.querySelectorAll('.mc-b-check');
            const items = wrapper.querySelectorAll('.mc-b-item');
            const finalEl = wrapper.querySelector('.mc-b-final-price');
            const oldEl = wrapper.querySelector('.mc-b-old-price');
            const badgeWrap = wrapper.querySelector('.mc-b-badge-wrap');
            const badgeEl = wrapper.querySelector('.mc-b-badge');
            const btn = wrapper.querySelector('.mc-b-add-btn');

            const dType = wrapper.dataset.dtype;
            const dVal = parseFloat(wrapper.dataset.dval);
            const currency = wrapper.dataset.currency;

            const calculateTotal = () => {
                let total = 0;
                let activeCount = 0;
                let totalCount = checkboxes.length;

                checkboxes.forEach((cb, index) => {
                    if (cb.checked) {
                        total += parseFloat(cb.dataset.price);
                        activeCount++;
                        items[index].classList.remove('is-unselected');
                    } else {
                        items[index].classList.add('is-unselected');
                    }
                });

                if (activeCount === 0) {
                    finalEl.innerText = currency + '0.00';
                    oldEl.style.display = 'none';
                    badgeWrap.style.display = 'none';
                    btn.innerText = 'Select items to add';
                    btn.classList.add('loading');
                    return;
                }

                btn.classList.remove('loading');
                btn.innerText = activeCount > 1 ? `Add all ${activeCount} to Cart` : 'Add to Cart';

                // Apply discount ONLY if all items are selected (Standard bundle logic)
                if (activeCount === totalCount && dType !== 'none') {
                    let discountAmt = dType === 'percent' ? total * (dVal / 100) : dVal;
                    let finalPrice = total - discountAmt;
                    
                    finalEl.innerText = currency + finalPrice.toFixed(2);
                    oldEl.innerText = currency + total.toFixed(2);
                    oldEl.style.display = 'inline-block';
                    
                    badgeEl.innerText = dType === 'percent' ? `Save ${dVal}% together` : `Save ${currency}${dVal} together`;
                    badgeWrap.style.display = 'block';
                } else {
                    finalEl.innerText = currency + total.toFixed(2);
                    oldEl.style.display = 'none';
                    badgeWrap.style.display = 'none';
                }
            };

            checkboxes.forEach(cb => cb.addEventListener('change', calculateTotal));
            calculateTotal(); // Initial calc

            // AJAX ADD TO CART
            btn.addEventListener('click', () => {
                if (btn.classList.contains('loading')) return;
                
                let selectedIds = [];
                checkboxes.forEach(cb => { if (cb.checked) selectedIds.push(cb.value); });
                
                if (selectedIds.length === 0) return;

                btn.classList.add('loading');
                btn.innerText = 'Adding...';

                const formData = new FormData();
                formData.append('action', 'mc_bundle_add_ajax');
                formData.append('nonce', (window.mc_ajax && mc_ajax.nonce) || '');
                formData.append('product_ids', JSON.stringify(selectedIds));

                fetch(mc_ajax.ajaxurl, {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        btn.innerText = 'Added!';
                        // Trigger native WooCommerce fragment refresh
                        if (typeof jQuery !== 'undefined') {
                            jQuery(document.body).trigger('added_to_cart', [data.data.fragments, data.data.cart_hash, btn]);
                        }
                        setTimeout(() => {
                            btn.classList.remove('loading');
                            calculateTotal();
                        }, 2000);
                    }
                });
            });
        });
    </script>
</div>