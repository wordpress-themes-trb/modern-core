<?php
$width_mode = $attributes['widthMode'] ?? 'contained';
if (!class_exists('WooCommerce')) return;

$title = $attributes['sectionTitle'] ?? 'Recently Viewed';
$mode = $attributes['displayMode'] ?? 'carousel';
$max = $attributes['maxProducts'] ?? 8;
$days = $attributes['storageDays'] ?? 7;
$empty_behavior = $attributes['emptyBehavior'] ?? 'hide';
$fallback_query = $attributes['fallbackQuery'] ?? 'popularity';
$fallback_title = $attributes['fallbackTitle'] ?? 'Trending Now';
$cols_d = $attributes['colsDesktop'] ?? 4;
$cols_m = $attributes['colsMobile'] ?? 1.5;

$unique_id = uniqid('mc-recent-');
$wrapper_attributes = get_block_wrapper_attributes([
    'class' => "mc-block width-" . esc_attr($width_mode) . " modern-core-recent-viewed {$unique_id} mode-{$mode}",
    'id' => $unique_id,
    'style' => 'display: none;', // Hidden until JS hydrates it
    'data-max' => esc_attr($max),
    'data-days' => esc_attr($days),
    'data-empty' => esc_attr($empty_behavior),
    'data-fallback' => esc_attr($fallback_query),
    'data-title' => esc_attr($title),
    'data-falltitle' => esc_attr($fallback_title),
    'data-cols' => esc_attr($cols_d),
    'data-colsm' => esc_attr($cols_m)
]);
?>

<div <?php echo $wrapper_attributes; ?>>
    <div class="mc-recent-header" style="margin-bottom: 30px; padding: 0 5%;">
        <h2 class="mc-r-title" style="margin: 0; font-size: 28px; font-weight: 800;"></h2>
    </div>

    <div class="mc-recent-track" style="padding: 0 5%;"></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const wrapper = document.getElementById('<?php echo esc_attr($unique_id); ?>');
        if (!wrapper) return;

        const track = wrapper.querySelector('.mc-recent-track');
        const titleEl = wrapper.querySelector('.mc-r-title');
        
        // 1. Check Local Storage
        let viewedIds = [];
        try {
            const stored = localStorage.getItem('mc_recently_viewed');
            if (stored) {
                const parsed = JSON.parse(stored);
                // Filter out expired items
                const now = new Date().getTime();
                const maxAge = parseInt(wrapper.dataset.days) * 24 * 60 * 60 * 1000;
                viewedIds = parsed.filter(item => (now - item.time) < maxAge).map(item => item.id);
            }
        } catch(e) {}

        // 2. Logic Routing
        const emptyBehavior = wrapper.dataset.empty;
        let requestIds = viewedIds.slice(0, parseInt(wrapper.dataset.max));
        let isFallback = false;

        if (requestIds.length === 0) {
            if (emptyBehavior === 'hide') {
                wrapper.remove(); // Nuke the block, history is empty
                return;
            } else {
                isFallback = true;
            }
        }

        // 3. Set Title
        titleEl.innerText = isFallback ? wrapper.dataset.falltitle : wrapper.dataset.title;

        // 4. Fetch the Cards
        const formData = new FormData();
        formData.append('action', 'mc_recent_products_ajax');
        formData.append('is_fallback', isFallback);
        formData.append('fallback_query', wrapper.dataset.fallback);
        formData.append('product_ids', JSON.stringify(requestIds));
        formData.append('mode', wrapper.classList.contains('mode-carousel') ? 'carousel' : 'grid');
        formData.append('cols', wrapper.dataset.cols);
        formData.append('colsm', wrapper.dataset.colsm);

        fetch(mc_ajax.ajaxurl, {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.data.html) {
                track.innerHTML = data.data.html;
                wrapper.style.display = 'block'; // Reveal the section seamlessly
                
                // If it's a carousel, apply the CSS rules dynamically
                if (wrapper.classList.contains('mode-carousel')) {
                    track.style.display = 'flex';
                    track.style.gap = '30px';
                    track.style.overflowX = 'auto';
                    track.style.scrollSnapType = 'x mandatory';
                    track.style.scrollbarWidth = 'none';
                    track.style.paddingBottom = '20px';
                    
                    const cards = track.querySelectorAll('.mc-product-card');
                    cards.forEach(card => {
                        card.style.flex = `0 0 calc((100% - (${wrapper.dataset.cols} - 1) * 30px) / ${wrapper.dataset.cols})`;
                        card.style.scrollSnapAlign = 'start';
                        if (window.innerWidth <= 768) {
                            card.style.flex = `0 0 calc(100% / ${wrapper.dataset.colsm})`;
                        }
                    });
                } else {
                    track.style.display = 'grid';
                    track.style.gridTemplateColumns = `repeat(${wrapper.dataset.cols}, 1fr)`;
                    track.style.gap = '30px';
                    if (window.innerWidth <= 768) {
                        track.style.gridTemplateColumns = `repeat(2, 1fr)`;
                        track.style.gap = '15px';
                    }
                }
            } else {
                wrapper.remove();
            }
        });
    });
</script>