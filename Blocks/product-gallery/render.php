<?php
if (!class_exists('WooCommerce')) return;

global $product;
// Fallback for editor/global template context
if (empty($product)) {
    $products = wc_get_products(['limit' => 1]);
    if (!empty($products)) $product = $products[0];
    else return;
}

$thumb_pos = $attributes['thumbPosition'] ?? 'bottom';
$ratio = $attributes['aspectRatio'] ?? '1/1';
$gap = $attributes['gap'] ?? 15;
$radius = $attributes['radius'] ?? 8;
$active_border = $attributes['activeBorder'] ?? '#0f172a';
$enable_zoom = $attributes['enableZoom'] ?? true;
$zoom_level = $attributes['zoomLevel'] ?? 2;
$enable_lightbox = $attributes['enableLightbox'] ?? true;

$unique_id = uniqid('mc-pg-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "modern-core-product-gallery {$unique_id} pos-{$thumb_pos}"]);

// Fetch Images
$main_image_id = $product->get_image_id();
$attachment_ids = $product->get_gallery_image_ids();
array_unshift($attachment_ids, $main_image_id); // Put main image at the start of the array
$attachment_ids = array_unique(array_filter($attachment_ids));

if (empty($attachment_ids)) {
    // Fallback if no image
    echo '<div ' . $wrapper_attributes . '><div style="aspect-ratio:1/1; background:#f1f5f9; display:flex; align-items:center; justify-content:center; border-radius:8px;">No image available</div></div>';
    return;
}

// Pre-fetch initial image data
$initial_img = wp_get_attachment_image_url($attachment_ids[0], 'woocommerce_single');
$initial_full = wp_get_attachment_image_url($attachment_ids[0], 'full');
?>

<div <?php echo $wrapper_attributes; ?> 
    data-zoom="<?php echo esc_attr($enable_zoom); ?>" 
    data-zlevel="<?php echo esc_attr($zoom_level); ?>"
    data-lightbox="<?php echo esc_attr($enable_lightbox); ?>">
    
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            --pg-gap: <?php echo esc_attr($gap); ?>px;
            --pg-rad: <?php echo esc_attr($radius); ?>px;
            --pg-active: <?php echo esc_attr($active_border); ?>;
            --pg-ratio: <?php echo esc_attr($ratio); ?>;
            width: 100%; position: relative;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-pg-layout {
            display: flex; gap: var(--pg-gap);
            flex-direction: <?php echo $thumb_pos === 'left' ? 'row-reverse' : 'column'; ?>;
        }

        /* --- Main Image Viewport --- */
        .<?php echo esc_attr($unique_id); ?> .mc-pg-main-wrap {
            flex: 1; border-radius: var(--pg-rad); overflow: hidden; position: relative;
            background: #f8fafc; cursor: <?php echo $enable_lightbox ? 'zoom-in' : 'default'; ?>;
            aspect-ratio: <?php echo $ratio === 'auto' ? 'auto' : 'var(--pg-ratio)'; ?>;
        }
        
        .<?php echo esc_attr($unique_id); ?> .mc-pg-img-base {
            width: 100%; height: 100%; object-fit: contain; display: block;
            transition: opacity 0.3s ease;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-pg-img-base.is-fading { opacity: 0; }

        /* Zoom Overlay Engine */
        .<?php echo esc_attr($unique_id); ?> .mc-pg-zoom-overlay {
            position: absolute; inset: 0; background-repeat: no-repeat;
            opacity: 0; transition: opacity 0.2s; pointer-events: none; z-index: 2;
        }

        /* --- Thumbnails --- */
        .<?php echo esc_attr($unique_id); ?> .mc-pg-thumbs {
            display: <?php echo $thumb_pos === 'dots' ? 'none' : 'flex'; ?>; gap: var(--pg-gap);
            flex-direction: <?php echo $thumb_pos === 'left' ? 'column' : 'row'; ?>;
            overflow: <?php echo $thumb_pos === 'left' ? 'hidden' : 'auto'; ?>;
            scrollbar-width: none; /* Firefox */
        }
        .<?php echo esc_attr($unique_id); ?> .mc-pg-thumbs::-webkit-scrollbar { display: none; }
        
        .<?php echo esc_attr($unique_id); ?> .mc-pg-thumb {
            width: <?php echo $thumb_pos === 'left' ? '80px' : 'calc(25% - (var(--pg-gap) * 3 / 4))'; ?>;
            flex-shrink: 0; aspect-ratio: <?php echo $ratio === 'auto' ? '1/1' : 'var(--pg-ratio)'; ?>;
            object-fit: cover; border-radius: calc(var(--pg-rad) / 2); cursor: pointer;
            border: 2px solid transparent; transition: border-color 0.2s, opacity 0.2s;
            background: #f8fafc;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-pg-thumb:hover { opacity: 0.8; }
        .<?php echo esc_attr($unique_id); ?> .mc-pg-thumb.is-active { border-color: var(--pg-active); opacity: 1; }

        /* --- Dots Navigation --- */
        .<?php echo esc_attr($unique_id); ?> .mc-pg-dots {
            display: <?php echo $thumb_pos === 'dots' ? 'flex' : 'none'; ?>; justify-content: center; gap: 8px; margin-top: 10px;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-pg-dot {
            width: 10px; height: 10px; border-radius: 50%; background: #cbd5e1; cursor: pointer; transition: background 0.2s;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-pg-dot.is-active { background: var(--pg-active); }

        /* Responsive Overrides */
        @media (max-width: 768px) {
            .<?php echo esc_attr($unique_id); ?> .mc-pg-layout { flex-direction: column; }
            .<?php echo esc_attr($unique_id); ?> .mc-pg-thumbs { flex-direction: row; overflow-x: auto; scroll-snap-type: x mandatory; }
            .<?php echo esc_attr($unique_id); ?> .mc-pg-thumb { width: 80px; scroll-snap-align: start; }
            .<?php echo esc_attr($unique_id); ?> .mc-pg-main-wrap { pointer-events: none; /* Disable zoom/lightbox on touch to allow native swipe parsing later */ }
        }

        /* --- Lightbox --- */
        .mc-pg-lightbox {
            position: fixed; inset: 0; z-index: 999999; background: rgba(255,255,255,0.98);
            display: flex; align-items: center; justify-content: center; opacity: 0; visibility: hidden; transition: opacity 0.3s;
        }
        .mc-pg-lightbox.is-open { opacity: 1; visibility: visible; }
        .mc-pg-lb-close { position: absolute; top: 20px; right: 30px; font-size: 40px; cursor: pointer; opacity: 0.5; transition: opacity 0.2s; line-height: 1; }
        .mc-pg-lb-close:hover { opacity: 1; }
        .mc-pg-lb-img { max-width: 90vw; max-height: 90vh; object-fit: contain; box-shadow: 0 25px 50px rgba(0,0,0,0.1); }
    </style>

    <div class="mc-pg-layout">
        <div class="mc-pg-main-wrap">
            <img src="<?php echo esc_url($initial_img); ?>" class="mc-pg-img-base" alt="<?php echo esc_attr($product->get_name()); ?>" />
            <?php if ($enable_zoom) : ?>
                <div class="mc-pg-zoom-overlay" style="background-image: url('<?php echo esc_url($initial_full); ?>'); background-size: <?php echo ($zoom_level * 100); ?>%;"></div>
            <?php endif; ?>
        </div>

        <div class="mc-pg-thumbs">
            <?php foreach ($attachment_ids as $index => $id) : 
                $thumb_src = wp_get_attachment_image_url($id, 'thumbnail');
                $single_src = wp_get_attachment_image_url($id, 'woocommerce_single');
                $full_src = wp_get_attachment_image_url($id, 'full');
            ?>
                <img src="<?php echo esc_url($thumb_src); ?>" 
                     class="mc-pg-thumb <?php echo $index === 0 ? 'is-active' : ''; ?>" 
                     data-single="<?php echo esc_url($single_src); ?>"
                     data-full="<?php echo esc_url($full_src); ?>"
                     data-index="<?php echo esc_attr($index); ?>" />
            <?php endforeach; ?>
        </div>

        <div class="mc-pg-dots">
            <?php foreach ($attachment_ids as $index => $id) : ?>
                <div class="mc-pg-dot <?php echo $index === 0 ? 'is-active' : ''; ?>" data-index="<?php echo esc_attr($index); ?>"></div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if ($enable_lightbox) : ?>
        <script type="text/template" class="mc-pg-lb-template">
            <div class="mc-pg-lightbox">
                <div class="mc-pg-lb-close">×</div>
                <img src="" class="mc-pg-lb-img" />
            </div>
        </script>
    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const wrapper = document.querySelector('.<?php echo esc_attr($unique_id); ?>');
            if (!wrapper) return;

            const mainWrap = wrapper.querySelector('.mc-pg-main-wrap');
            const mainImg = wrapper.querySelector('.mc-pg-img-base');
            const zoomOverlay = wrapper.querySelector('.mc-pg-zoom-overlay');
            const thumbs = wrapper.querySelectorAll('.mc-pg-thumb');
            const dots = wrapper.querySelectorAll('.mc-pg-dot');
            
            const enableZoom = wrapper.dataset.zoom === '1';
            const enableLb = wrapper.dataset.lightbox === '1';
            let currentFullSrc = thumbs[0]?.dataset.full || mainImg.src;

            // 1. Navigation Logic
            const swapImage = (index) => {
                const targetThumb = thumbs[index];
                if (!targetThumb) return;

                const singleSrc = targetThumb.dataset.single;
                currentFullSrc = targetThumb.dataset.full;

                if (mainImg.src === singleSrc) return;

                // Sync UI
                thumbs.forEach(t => t.classList.remove('is-active'));
                dots.forEach(d => d.classList.remove('is-active'));
                targetThumb.classList.add('is-active');
                if (dots[index]) dots[index].classList.add('is-active');

                // Crossfade
                mainImg.classList.add('is-fading');
                setTimeout(() => {
                    mainImg.src = singleSrc;
                    if (enableZoom && zoomOverlay) {
                        zoomOverlay.style.backgroundImage = `url('${currentFullSrc}')`;
                    }
                    mainImg.onload = () => mainImg.classList.remove('is-fading');
                }, 150);
            };

            thumbs.forEach((t, i) => t.addEventListener('click', () => swapImage(i)));
            dots.forEach((d, i) => d.addEventListener('click', () => swapImage(i)));

            // 2. Zoom Logic (Desktop Only)
            if (enableZoom && zoomOverlay) {
                mainWrap.addEventListener('mousemove', (e) => {
                    const rect = mainWrap.getBoundingClientRect();
                    // Calculate percentage position
                    const x = ((e.clientX - rect.left) / rect.width) * 100;
                    const y = ((e.clientY - rect.top) / rect.height) * 100;
                    
                    zoomOverlay.style.opacity = '1';
                    zoomOverlay.style.backgroundPosition = `${x}% ${y}%`;
                    mainImg.style.opacity = '0'; // Hide base image to show sharp zoom
                });

                mainWrap.addEventListener('mouseleave', () => {
                    zoomOverlay.style.opacity = '0';
                    mainImg.style.opacity = '1';
                });
            }

            // 3. Lightbox Logic
            if (enableLb) {
                const template = wrapper.querySelector('.mc-pg-lb-template');
                if (template) {
                    // Inject to body to avoid clipping issues
                    document.body.insertAdjacentHTML('beforeend', template.innerHTML);
                    const lightbox = document.body.lastElementChild;
                    const lbImg = lightbox.querySelector('.mc-pg-lb-img');
                    const closeBtn = lightbox.querySelector('.mc-pg-lb-close');

                    mainWrap.addEventListener('click', () => {
                        lbImg.src = currentFullSrc;
                        lightbox.classList.add('is-open');
                    });

                    const closeLb = () => lightbox.classList.remove('is-open');
                    closeBtn.addEventListener('click', closeLb);
                    lightbox.addEventListener('click', (e) => {
                        if (e.target === lightbox) closeLb();
                    });
                }
            }
        });
    </script>
</div>