<?php
if (!class_exists('WooCommerce')) return;

$layout = $attributes['layoutMode'] ?? 'grid';
$cols = $attributes['gridCols'] ?? 4;
$show_stock = $attributes['showStock'] ?? true;
$show_price = $attributes['showPriceDrop'] ?? true;
$show_share = $attributes['showShare'] ?? true;
$e_head = $attributes['emptyHeading'] ?? 'Your wishlist is empty';
$e_msg = $attributes['emptyMessage'] ?? 'Save items you love to review them later.';
$e_btn = $attributes['emptyButton'] ?? 'Start Shopping';
$e_link = $attributes['emptyLink'] ?? '/shop';
$accent = $attributes['accentColor'] ?? '#0f172a';

$unique_id = uniqid('mc-wishlist-');
$wrapper_attributes = get_block_wrapper_attributes([
    'class' => "modern-core-wishlist {$unique_id} layout-{$layout}",
    'id' => $unique_id,
    'data-stock' => $show_stock ? '1' : '0',
    'data-price' => $show_price ? '1' : '0'
]);
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            width: 100%; padding: 40px 0; position: relative; min-height: 300px;
        }
        
        /* Empty State */
        .<?php echo esc_attr($unique_id); ?> .mc-wish-empty {
            display: none; text-align: center; padding: 60px 20px; background: #f8fafc; border-radius: 12px;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-wish-empty.is-active { display: block; animation: mcFadeIn 0.5s ease; }
        .<?php echo esc_attr($unique_id); ?> .mc-wish-empty-btn {
            display: inline-block; padding: 14px 35px; background: <?php echo esc_attr($accent); ?>; color: #fff;
            border-radius: 50px; font-weight: bold; text-decoration: none; transition: opacity 0.2s;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-wish-empty-btn:hover { opacity: 0.8; }

        /* Active State */
        .<?php echo esc_attr($unique_id); ?> .mc-wish-active { display: none; }
        .<?php echo esc_attr($unique_id); ?> .mc-wish-active.is-active { display: block; animation: mcFadeIn 0.5s ease; }

        /* Grid Layout */
        .<?php echo esc_attr($unique_id); ?>.layout-grid .mc-wish-track {
            display: grid; grid-template-columns: repeat(<?php echo esc_attr($cols); ?>, 1fr); gap: 30px;
        }
        /* List Layout */
        .<?php echo esc_attr($unique_id); ?>.layout-list .mc-wish-track {
            display: flex; flex-direction: column; gap: 20px;
        }
        /* Table Layout */
        .<?php echo esc_attr($unique_id); ?>.layout-table .mc-wish-track {
            display: flex; flex-direction: column; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;
        }

        /* Card Internals */
        .<?php echo esc_attr($unique_id); ?> .mc-wish-card {
            background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; position: relative;
            display: flex; flex-direction: column; transition: transform 0.2s, opacity 0.3s;
        }
        .<?php echo esc_attr($unique_id); ?>.layout-list .mc-wish-card { flex-direction: row; align-items: center; gap: 20px; }
        .<?php echo esc_attr($unique_id); ?>.layout-table .mc-wish-card { flex-direction: row; align-items: center; border: none; border-bottom: 1px solid #e2e8f0; border-radius: 0; }
        .<?php echo esc_attr($unique_id); ?>.layout-table .mc-wish-card:last-child { border-bottom: none; }

        .<?php echo esc_attr($unique_id); ?> .mc-wish-img-wrap {
            position: relative; width: 100%; aspect-ratio: 3/4; border-radius: 4px; overflow: hidden; display: block;
        }
        .<?php echo esc_attr($unique_id); ?>.layout-list .mc-wish-img-wrap { width: 150px; flex-shrink: 0; }
        .<?php echo esc_attr($unique_id); ?>.layout-table .mc-wish-img-wrap { width: 60px; aspect-ratio: 1/1; flex-shrink: 0; }
        
        .<?php echo esc_attr($unique_id); ?> .mc-wish-img { width: 100%; height: 100%; object-fit: cover; }
        
        .<?php echo esc_attr($unique_id); ?> .mc-wish-remove {
            position: absolute; top: 10px; right: 10px; font-size: 16px; color: #94a3b8; cursor: pointer;
            z-index: 10; background: none; border: none; transition: color 0.2s;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-wish-remove:hover { color: #ef4444; }

        /* Badges */
        .<?php echo esc_attr($unique_id); ?> .mc-badge-drop { position: absolute; top: 10px; left: 10px; background: #10b981; color: #fff; font-size: 10px; padding: 4px 8px; border-radius: 4px; font-weight: bold; }
        .<?php echo esc_attr($unique_id); ?> .mc-badge-oos { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: #000; color: #fff; font-size: 11px; padding: 6px 12px; font-weight: bold; white-space: nowrap; }
        .<?php echo esc_attr($unique_id); ?> .is-oos { opacity: 0.6; }

        /* Typography & Buttons */
        .<?php echo esc_attr($unique_id); ?> .mc-wish-info { flex: 1; display: flex; flex-direction: column; justify-content: center; }
        .<?php echo esc_attr($unique_id); ?>.layout-table .mc-wish-info { flex-direction: row; align-items: center; justify-content: space-between; gap: 20px; }
        
        .<?php echo esc_attr($unique_id); ?> .mc-wish-title { font-size: 14px; font-weight: bold; color: inherit; text-decoration: none; margin-bottom: 10px; }
        .<?php echo esc_attr($unique_id); ?>.layout-table .mc-wish-title { margin: 0; flex: 2; }
        
        .<?php echo esc_attr($unique_id); ?> .mc-wish-price { font-weight: bold; margin-bottom: 15px; }
        .<?php echo esc_attr($unique_id); ?>.layout-table .mc-wish-price { margin: 0; flex: 1; }
        .<?php echo esc_attr($unique_id); ?> .mc-wish-price del { opacity: 0.5; font-size: 12px; font-weight: normal; margin-right: 5px; }
        
        .<?php echo esc_attr($unique_id); ?> .ajax_add_to_cart {
            display: block; text-align: center; padding: 10px; background: <?php echo esc_attr($accent); ?>; color: #fff;
            border-radius: 4px; font-size: 12px; font-weight: bold; text-decoration: none; transition: opacity 0.2s;
        }
        .<?php echo esc_attr($unique_id); ?> .ajax_add_to_cart:hover { opacity: 0.8; }
        .<?php echo esc_attr($unique_id); ?>.layout-table .ajax_add_to_cart { flex-shrink: 0; padding: 8px 20px; }

        /* Skeleton Loading State */
        .mc-wish-skeleton { animation: mcPulse 1.5s infinite; opacity: 0.5; }
        @keyframes mcPulse { 0%{opacity:0.3;} 50%{opacity:0.7;} 100%{opacity:0.3;} }
        @keyframes mcFadeIn { from{opacity:0;} to{opacity:1;} }
        
        @media (max-width: 768px) {
            .<?php echo esc_attr($unique_id); ?>.layout-grid .mc-wish-track { grid-template-columns: repeat(2, 1fr); gap: 15px; }
            .<?php echo esc_attr($unique_id); ?>.layout-table .mc-wish-card { flex-direction: column; align-items: flex-start; position: relative; padding-left: 90px; }
            .<?php echo esc_attr($unique_id); ?>.layout-table .mc-wish-img-wrap { position: absolute; left: 15px; top: 15px; }
            .<?php echo esc_attr($unique_id); ?>.layout-table .mc-wish-info { flex-direction: column; align-items: flex-start; gap: 10px; width: 100%; }
        }
    </style>

    <div class="mc-wish-empty">
        <div style="font-size: 48px; color: #cbd5e1; margin-bottom: 20px;">♡</div>
        <h3 style="margin: 0 0 10px 0; font-size: 24px;"><?php echo esc_html($e_head); ?></h3>
        <p style="opacity: 0.6; margin-bottom: 30px;"><?php echo esc_html($e_msg); ?></p>
        <a href="<?php echo esc_url($e_link); ?>" class="mc-wish-empty-btn"><?php echo esc_html($e_btn); ?></a>
    </div>

    <div class="mc-wish-active">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h2 style="margin: 0; font-size: 24px; font-weight: 800;">Your Wishlist</h2>
            <div style="display: flex; gap: 15px;">
                <?php if ($show_share) : ?>
                    <button class="mc-wish-share-btn" style="padding: 10px 20px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 13px; font-weight: bold; background: #fff; cursor: pointer;">Share</button>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="mc-wish-track">
            <?php for($i=0; $i<$cols; $i++) : ?>
                <div class="mc-wish-card mc-wish-skeleton" style="height: 300px; background: #e2e8f0;"></div>
            <?php endfor; ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const wrapper = document.getElementById('<?php echo esc_attr($unique_id); ?>');
            if (!wrapper) return;

            const emptyState = wrapper.querySelector('.mc-wish-empty');
            const activeState = wrapper.querySelector('.mc-wish-active');
            const track = wrapper.querySelector('.mc-wish-track');

            // Global generic function allowing site-wide Add to Wishlist buttons to save IDs to localStorage
            window.mcAddToWishlist = function(productId) {
                let saved = JSON.parse(localStorage.getItem('mc_wishlist') || '[]');
                if (!saved.includes(productId)) saved.push(productId);
                localStorage.setItem('mc_wishlist', JSON.stringify(saved));
                alert('Added to wishlist!'); // In production, replace with a toast notification
            };

            // Read Wishlist Array
            let savedIds = [];
            try {
                savedIds = JSON.parse(localStorage.getItem('mc_wishlist') || '[]');
            } catch(e) {}

            if (savedIds.length === 0) {
                emptyState.classList.add('is-active');
                return;
            }

            activeState.classList.add('is-active');

            // Fetch Hydration Data
            const formData = new FormData();
            formData.append('action', 'mc_wishlist_ajax');
            formData.append('product_ids', JSON.stringify(savedIds));
            formData.append('show_stock', wrapper.dataset.stock);
            formData.append('show_price', wrapper.dataset.price);

            fetch(mc_ajax.ajaxurl, {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.data.html) {
                    track.innerHTML = data.data.html;

                    // Bind Remove Buttons
                    const removeBtns = track.querySelectorAll('.mc-wish-remove');
                    removeBtns.forEach(btn => {
                        btn.addEventListener('click', function() {
                            const pid = parseInt(this.dataset.id);
                            let currentIds = JSON.parse(localStorage.getItem('mc_wishlist') || '[]');
                            currentIds = currentIds.filter(id => id !== pid);
                            localStorage.setItem('mc_wishlist', JSON.stringify(currentIds));
                            
                            const card = this.closest('.mc-wish-card');
                            card.style.opacity = '0';
                            setTimeout(() => {
                                card.remove();
                                if (currentIds.length === 0) {
                                    activeState.classList.remove('is-active');
                                    emptyState.classList.add('is-active');
                                }
                            }, 300);
                        });
                    });

                    // Bind Share Button Native API
                    const shareBtn = wrapper.querySelector('.mc-wish-share-btn');
                    if (shareBtn) {
                        shareBtn.addEventListener('click', () => {
                            if (navigator.share) {
                                navigator.share({
                                    title: 'My Wishlist',
                                    url: window.location.href // Advanced: Generate unique URL param based on IDs
                                });
                            } else {
                                navigator.clipboard.writeText(window.location.href);
                                shareBtn.innerText = 'Link Copied!';
                                setTimeout(() => shareBtn.innerText = 'Share', 2000);
                            }
                        });
                    }
                } else {
                    activeState.classList.remove('is-active');
                    emptyState.classList.add('is-active');
                }
            });
        });
    </script>
</div>