<?php
$width_mode = $attributes['widthMode'] ?? 'contained';
if (!class_exists('WooCommerce')) return;

global $product;
// Fallback for editor/global template context
if (empty($product)) {
    $products = wc_get_products(['limit' => 1]);
    if (!empty($products)) $product = $products[0];
    else return; // No products exist
}

$layout = $attributes['layoutMode'] ?? 'standard';
$split = $attributes['splitRatio'] ?? '50-50';
$thumbs = $attributes['galleryThumbs'] ?? 'bottom';
$ratio = $attributes['imageRatio'] ?? '3/4';
$sticky_atc = $attributes['enableStickyATC'] ?? true;
$elements = $attributes['elements'] ?? [];
$panel_bg = $attributes['panelBg'] ?? '#ffffff';
$panel_rad = $attributes['panelRadius'] ?? 0;
$accent = $attributes['accentColor'] ?? '#0f172a';

// Create a visibility map
$is_visible = [];
foreach ($elements as $el) {
    $is_visible[$el['id']] = $el['visible'] ?? true;
}

$unique_id = uniqid('mc-pdp-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "mc-block width-" . esc_attr($width_mode) . " modern-core-single-product {$unique_id} layout-{$layout} split-{$split} thumbs-{$thumbs}"]);

$product_id = $product->get_id();
$main_image_url = wp_get_attachment_image_url($product->get_image_id(), 'large') ?: wc_placeholder_img_src();
$attachment_ids = $product->get_gallery_image_ids();
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            --pdp-acc: <?php echo esc_attr($accent); ?>;
            --pdp-bg: <?php echo esc_attr($panel_bg); ?>;
            --pdp-rad: <?php echo esc_attr($panel_rad); ?>px;
            --pdp-ratio: <?php echo esc_attr($ratio); ?>;
            width: 100%; position: relative; padding-bottom: 80px;
        }

        /* --- 1. Grid Architectures --- */
        .<?php echo esc_attr($unique_id); ?> .mc-pdp-grid {
            display: grid; gap: 60px; max-width: 1400px; margin: 0 auto; padding: 40px 5%; align-items: start;
        }

        /* Standard Split Math */
        .<?php echo esc_attr($unique_id); ?>.layout-standard.split-50-50 .mc-pdp-grid { grid-template-columns: 1fr 1fr; }
        .<?php echo esc_attr($unique_id); ?>.layout-standard.split-60-40 .mc-pdp-grid { grid-template-columns: 6fr 4fr; }
        .<?php echo esc_attr($unique_id); ?>.layout-standard.split-40-60 .mc-pdp-grid { grid-template-columns: 4fr 6fr; }
        
        /* Stacked Layout */
        .<?php echo esc_attr($unique_id); ?>.layout-stacked .mc-pdp-grid { grid-template-columns: 1fr; max-width: 900px; }

        /* Cinematic Overlay Layout */
        .<?php echo esc_attr($unique_id); ?>.layout-cinematic .mc-pdp-grid {
            grid-template-columns: 1fr; padding: 0; max-width: 100%; position: relative;
        }
        .<?php echo esc_attr($unique_id); ?>.layout-cinematic .mc-pdp-gallery-wrap {
            grid-column: 1 / -1; grid-row: 1; min-height: 80vh;
        }
        .<?php echo esc_attr($unique_id); ?>.layout-cinematic .mc-pdp-main-img { aspect-ratio: auto; height: 85vh; border-radius: 0; }
        
        .<?php echo esc_attr($unique_id); ?>.layout-cinematic .mc-pdp-info-panel {
            grid-column: 1 / -1; grid-row: 1; justify-self: end; align-self: center;
            background: var(--pdp-bg); padding: 50px; margin-right: 5%; width: 100%; max-width: 460px;
            border-radius: var(--pdp-rad); box-shadow: 0 30px 60px -15px rgba(0,0,0,0.2); z-index: 10;
        }

        /* Mobile Overrides */
        @media (max-width: 900px) {
            .<?php echo esc_attr($unique_id); ?> .mc-pdp-grid { grid-template-columns: 1fr !important; padding: 20px 5%; gap: 40px; }
            .<?php echo esc_attr($unique_id); ?>.layout-cinematic .mc-pdp-info-panel { margin-right: 0; max-width: 100%; box-shadow: none; padding: 30px 0; border-radius: 0; }
            .<?php echo esc_attr($unique_id); ?>.layout-cinematic .mc-pdp-main-img { height: 60vh; }
        }

        /* --- 2. Gallery Engine --- */
        .<?php echo esc_attr($unique_id); ?> .mc-pdp-gallery-wrap {
            display: flex; gap: 20px;
            flex-direction: <?php echo $thumbs === 'left' ? 'row-reverse' : 'column'; ?>;
        }
        @media (max-width: 900px) { .<?php echo esc_attr($unique_id); ?> .mc-pdp-gallery-wrap { flex-direction: column; } }

        .<?php echo esc_attr($unique_id); ?> .mc-pdp-main-img-wrap { flex: 1; overflow: hidden; border-radius: 8px; background: #f1f5f9; position: relative; }
        .<?php echo esc_attr($unique_id); ?> .mc-pdp-main-img {
            width: 100%; aspect-ratio: var(--pdp-ratio); object-fit: cover; display: block;
            transition: opacity 0.3s ease;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-pdp-main-img.is-fading { opacity: 0.5; }

        .<?php echo esc_attr($unique_id); ?> .mc-pdp-thumbs {
            display: flex; gap: 10px; overflow-x: auto; scrollbar-width: none;
            flex-direction: <?php echo $thumbs === 'left' ? 'column' : 'row'; ?>;
        }
        @media (max-width: 900px) { .<?php echo esc_attr($unique_id); ?> .mc-pdp-thumbs { flex-direction: row; } }
        .<?php echo esc_attr($unique_id); ?> .mc-pdp-thumbs::-webkit-scrollbar { display: none; }
        
        .<?php echo esc_attr($unique_id); ?> .mc-pdp-thumb {
            width: 80px; height: 80px; flex-shrink: 0; object-fit: cover; border-radius: 4px; cursor: pointer;
            border: 2px solid transparent; transition: border-color 0.2s;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-pdp-thumb.is-active { border-color: var(--pdp-acc); }

        /* --- 3. Info Elements Typography & Spacing --- */
        .<?php echo esc_attr($unique_id); ?> .mc-pdp-info-panel { display: flex; flex-direction: column; gap: 20px; }
        .<?php echo esc_attr($unique_id); ?> .mc-pdp-category { font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--pdp-acc); font-weight: bold; }
        .<?php echo esc_attr($unique_id); ?> .mc-pdp-title { font-size: clamp(28px, 4vw, 42px); font-weight: 900; margin: 0; line-height: 1.1; color: #0f172a; }
        .<?php echo esc_attr($unique_id); ?> .mc-pdp-price { font-size: 24px; font-weight: bold; color: #0f172a; display: flex; gap: 15px; align-items: center; }
        .<?php echo esc_attr($unique_id); ?> .mc-pdp-price del { opacity: 0.5; font-size: 18px; }
        .<?php echo esc_attr($unique_id); ?> .mc-pdp-price ins { text-decoration: none; color: #e11d48; }
        .<?php echo esc_attr($unique_id); ?> .mc-pdp-excerpt { font-size: 16px; line-height: 1.6; opacity: 0.8; margin: 0; }
        .<?php echo esc_attr($unique_id); ?> .mc-pdp-meta { font-size: 13px; opacity: 0.6; padding-top: 20px; border-top: 1px solid rgba(0,0,0,0.1); }

        /* WooCommerce Form Overrides */
        .<?php echo esc_attr($unique_id); ?> form.cart { display: flex; flex-wrap: wrap; gap: 15px; margin-top: 10px; }
        .<?php echo esc_attr($unique_id); ?> .quantity input.qty { width: 70px; height: 50px; text-align: center; border: 1px solid rgba(0,0,0,0.1); border-radius: 4px; font-size: 16px; }
        .<?php echo esc_attr($unique_id); ?> button.single_add_to_cart_button { flex: 1; height: 50px; background: var(--pdp-acc); color: #fff; border: none; border-radius: 4px; font-weight: bold; font-size: 16px; cursor: pointer; transition: opacity 0.2s; }
        .<?php echo esc_attr($unique_id); ?> button.single_add_to_cart_button:hover { opacity: 0.9; }

        /* --- 4. Sticky ATC Bar --- */
        .<?php echo esc_attr($unique_id); ?> .mc-sticky-atc {
            position: fixed; top: 0; left: 0; width: 100%; background: #fff; box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 15px 5%; display: flex; justify-content: space-between; align-items: center; z-index: 99999;
            transform: translateY(-100%); transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }
        .<?php echo esc_attr($unique_id); ?> .mc-sticky-atc.is-visible { transform: translateY(0); }
        .<?php echo esc_attr($unique_id); ?> .mc-sticky-info { display: flex; align-items: center; gap: 15px; }
        .<?php echo esc_attr($unique_id); ?> .mc-sticky-img { width: 40px; height: 40px; border-radius: 4px; object-fit: cover; }
        .<?php echo esc_attr($unique_id); ?> .mc-sticky-title { font-weight: bold; font-size: 15px; }
        .<?php echo esc_attr($unique_id); ?> .mc-sticky-price { font-weight: bold; color: var(--pdp-acc); }
        .<?php echo esc_attr($unique_id); ?> .mc-sticky-btn { padding: 10px 20px; background: var(--pdp-acc); color: #fff; border-radius: 4px; font-weight: bold; text-decoration: none; }
        
        @media (max-width: 768px) {
            .<?php echo esc_attr($unique_id); ?> .mc-sticky-atc { bottom: 0; top: auto; transform: translateY(100%); padding: 15px; }
            .<?php echo esc_attr($unique_id); ?> .mc-sticky-info { display: none; } /* Hide info, just show full-width button on mobile */
            .<?php echo esc_attr($unique_id); ?> .mc-sticky-btn { width: 100%; text-align: center; }
        }
    </style>

    <div class="mc-pdp-grid">
        
        <div class="mc-pdp-gallery-wrap">
            <div class="mc-pdp-main-img-wrap">
                <img src="<?php echo esc_url($main_image_url); ?>" class="mc-pdp-main-img" id="mc-pdp-main-<?php echo esc_attr($unique_id); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" />
            </div>
            
            <?php if ($attachment_ids) : ?>
                <div class="mc-pdp-thumbs">
                    <img src="<?php echo esc_url($main_image_url); ?>" class="mc-pdp-thumb is-active" data-large="<?php echo esc_url($main_image_url); ?>" />
                    <?php foreach ($attachment_ids as $attachment_id) : 
                        $thumb_url = wp_get_attachment_image_url($attachment_id, 'thumbnail');
                        $large_url = wp_get_attachment_image_url($attachment_id, 'large');
                    ?>
                        <img src="<?php echo esc_url($thumb_url); ?>" class="mc-pdp-thumb" data-large="<?php echo esc_url($large_url); ?>" />
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="mc-pdp-info-panel">
            
            <?php if (!empty($is_visible['breadcrumbs'])) : ?>
                <div class="mc-pdp-breadcrumbs"><?php woocommerce_breadcrumb(); ?></div>
            <?php endif; ?>

            <?php if (!empty($is_visible['category'])) : ?>
                <div class="mc-pdp-category"><?php echo wc_get_product_category_list($product->get_id(), ', '); ?></div>
            <?php endif; ?>

            <?php if (!empty($is_visible['title'])) : ?>
                <h1 class="mc-pdp-title"><?php echo esc_html($product->get_name()); ?></h1>
            <?php endif; ?>

            <?php if (!empty($is_visible['rating']) && $product->get_rating_count() > 0) : ?>
                <div class="mc-pdp-rating"><?php echo wc_get_rating_html($product->get_average_rating()); ?> (<?php echo $product->get_review_count(); ?> reviews)</div>
            <?php endif; ?>

            <?php if (!empty($is_visible['price'])) : ?>
                <div class="mc-pdp-price"><?php echo $product->get_price_html(); ?></div>
            <?php endif; ?>

            <?php if (!empty($is_visible['excerpt']) && $product->get_short_description()) : ?>
                <div class="mc-pdp-excerpt"><?php echo apply_filters('woocommerce_short_description', $product->get_short_description()); ?></div>
            <?php endif; ?>

            <?php if (!empty($is_visible['add_to_cart'])) : ?>
                <div class="mc-pdp-atc-wrapper mc-observe-target">
                    <?php woocommerce_template_single_add_to_cart(); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($is_visible['meta'])) : ?>
                <div class="mc-pdp-meta">
                    <?php do_action('woocommerce_product_meta_start'); ?>
                    <?php if (wc_product_sku_enabled() && ($product->get_sku() || $product->is_type('variable'))) : ?>
                        <span class="sku_wrapper"><?php esc_html_e('SKU:', 'woocommerce'); ?> <span class="sku"><?php echo ($sku = $product->get_sku()) ? $sku : esc_html__('N/A', 'woocommerce'); ?></span></span><br>
                    <?php endif; ?>
                    <?php echo wc_get_product_tag_list($product->get_id(), ', ', '<span class="tagged_as">' . _n('Tag:', 'Tags:', count($product->get_tag_ids()), 'woocommerce') . ' ', '</span>'); ?>
                    <?php do_action('woocommerce_product_meta_end'); ?>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <?php if ($sticky_atc && !empty($is_visible['add_to_cart'])) : ?>
        <div class="mc-sticky-atc">
            <div class="mc-sticky-info">
                <img src="<?php echo esc_url(wp_get_attachment_image_url($product->get_image_id(), 'thumbnail')); ?>" class="mc-sticky-img" />
                <div>
                    <div class="mc-sticky-title"><?php echo esc_html($product->get_name()); ?></div>
                    <div class="mc-sticky-price"><?php echo $product->get_price_html(); ?></div>
                </div>
            </div>
            <a href="#mc-pdp-main-<?php echo esc_attr($unique_id); ?>" class="mc-sticky-btn">Add to Cart</a>
        </div>
    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const wrapper = document.querySelector('.<?php echo esc_attr($unique_id); ?>');
            if (!wrapper) return;

            // 1. Gallery Logic
            const mainImg = wrapper.querySelector('.mc-pdp-main-img');
            const thumbs = wrapper.querySelectorAll('.mc-pdp-thumb');

            if (mainImg && thumbs.length > 0) {
                thumbs.forEach(thumb => {
                    thumb.addEventListener('click', () => {
                        const newSrc = thumb.dataset.large;
                        if (mainImg.src === newSrc) return;

                        // Visual Active State
                        thumbs.forEach(t => t.classList.remove('is-active'));
                        thumb.classList.add('is-active');

                        // Crossfade Swap
                        mainImg.classList.add('is-fading');
                        setTimeout(() => {
                            mainImg.src = newSrc;
                            // Wait for image to load before fading back in
                            mainImg.onload = () => mainImg.classList.remove('is-fading');
                        }, 150); // Half of the CSS transition time
                    });
                });
            }

            // 2. Sticky Add To Cart Logic (Intersection Observer)
            <?php if ($sticky_atc) : ?>
                const atcWrapper = wrapper.querySelector('.mc-pdp-atc-wrapper');
                const stickyBar = wrapper.querySelector('.mc-sticky-atc');

                if (atcWrapper && stickyBar) {
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            // If the ATC button scrolls UP and out of view (top < 0)
                            if (!entry.isIntersecting && entry.boundingClientRect.top < 0) {
                                stickyBar.classList.add('is-visible');
                            } else {
                                stickyBar.classList.remove('is-visible');
                            }
                        });
                    }, { threshold: 0, rootMargin: "-100px 0px 0px 0px" });

                    observer.observe(atcWrapper);
                    
                    // Smooth scroll for the sticky button
                    const stickyBtn = stickyBar.querySelector('.mc-sticky-btn');
                    if (stickyBtn) {
                        stickyBtn.addEventListener('click', (e) => {
                            e.preventDefault();
                            atcWrapper.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        });
                    }
                }
            <?php endif; ?>
        });
    </script>
</div>