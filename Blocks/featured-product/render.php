<?php
if (!class_exists('WooCommerce')) return;

$product_id = $attributes['productId'] ?? 0;
if (!$product_id) return;

$product = wc_get_product($product_id);
if (!$product) return;

$layout = $attributes['layoutMode'] ?? 'side-by-side';
$pre_head = $attributes['preHeadline'] ?? '';
$show_rating = $attributes['showRating'] ?? true;
$show_price = $attributes['showPrice'] ?? true;
$show_excerpt = $attributes['showExcerpt'] ?? true;
$show_gallery = $attributes['showGallery'] ?? true;
$img_bg = $attributes['imageBg'] ?? '#f8fafc';
$content_bg = $attributes['contentBg'] ?? '#ffffff';
$text_color = $attributes['textColor'] ?? '#0f172a';
$accent = $attributes['accentColor'] ?? '#3b82f6';
$btn_style = $attributes['buttonStyle'] ?? 'solid';

$unique_id = uniqid('mc-featprod-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "modern-core-featured-product {$unique_id} layout-{$layout}"]);

// Get Images
$main_img_url = wp_get_attachment_image_url($product->get_image_id(), 'large');
$gallery_ids = $product->get_gallery_image_ids();
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            display: flex;
            flex-direction: <?php echo $layout === 'stacked' ? 'column' : 'row'; ?>;
            background-color: <?php echo esc_attr($content_bg); ?>;
            color: <?php echo esc_attr($text_color); ?>;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.05);
            margin: 40px auto;
            max-width: <?php echo $layout === 'stacked' ? '600px' : '1200px'; ?>;
        }

        @media (max-width: 900px) {
            .<?php echo esc_attr($unique_id); ?> { flex-direction: column; }
        }

        /* Image Zone */
        .<?php echo esc_attr($unique_id); ?> .mc-fp-image-zone {
            flex: 1;
            background-color: <?php echo esc_attr($img_bg); ?>;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-fp-main-img {
            width: 100%;
            aspect-ratio: 3/4;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: opacity 0.3s ease;
        }
        
        /* Gallery Thumbnails */
        .<?php echo esc_attr($unique_id); ?> .mc-fp-gallery {
            display: flex; gap: 15px; margin-top: 20px; width: 100%; justify-content: center; overflow-x: auto; padding-bottom: 10px;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-fp-thumb {
            width: 60px; height: 60px; border-radius: 4px; object-fit: cover; cursor: pointer;
            border: 2px solid transparent; opacity: 0.6; transition: all 0.2s ease;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-fp-thumb.is-active {
            border-color: <?php echo esc_attr($accent); ?>; opacity: 1;
        }

        /* Content Zone */
        .<?php echo esc_attr($unique_id); ?> .mc-fp-content-zone {
            flex: 1;
            padding: 60px 8%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-fp-title { font-size: 36px; font-weight: 800; line-height: 1.1; margin: 0 0 15px 0; }
        .<?php echo esc_attr($unique_id); ?> .mc-fp-price { font-size: 24px; font-weight: bold; margin-bottom: 20px; }
        .<?php echo esc_attr($unique_id); ?> .mc-fp-price del { opacity: 0.5; font-size: 18px; font-weight: normal; margin-left: 10px; }
        .<?php echo esc_attr($unique_id); ?> .mc-fp-price ins { text-decoration: none; color: #ef4444; }
        .<?php echo esc_attr($unique_id); ?> .mc-fp-excerpt { font-size: 16px; opacity: 0.7; line-height: 1.6; margin-bottom: 30px; }

        /* Native WooCommerce Form Overrides to match theme */
        .<?php echo esc_attr($unique_id); ?> form.cart { margin-top: 20px; display: flex; flex-direction: column; gap: 15px; }
        .<?php echo esc_attr($unique_id); ?> .variations { width: 100%; margin-bottom: 20px; }
        .<?php echo esc_attr($unique_id); ?> .variations label { font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .<?php echo esc_attr($unique_id); ?> .variations select { width: 100%; padding: 12px; border: 1px solid rgba(0,0,0,0.1); border-radius: 4px; background: transparent; color: inherit; }
        
        .<?php echo esc_attr($unique_id); ?> .single_add_to_cart_button {
            width: 100%; padding: 16px; text-align: center; font-size: 16px; font-weight: bold; border-radius: 8px; cursor: pointer;
            transition: opacity 0.2s ease;
            <?php if ($btn_style === 'solid') : ?> background: <?php echo esc_attr($accent); ?>; color: #fff; border: none;
            <?php else : ?> background: transparent; color: <?php echo esc_attr($accent); ?>; border: 2px solid <?php echo esc_attr($accent); ?>; <?php endif; ?>
        }
        .<?php echo esc_attr($unique_id); ?> .single_add_to_cart_button:hover { opacity: 0.8; }
        .<?php echo esc_attr($unique_id); ?> .quantity { display: none; /* Hide qty input for cleaner look, optional */ }
    </style>

    <div class="mc-fp-image-zone">
        <img src="<?php echo esc_url($main_img_url); ?>" alt="Main Product Image" class="mc-fp-main-img" id="main-img-<?php echo esc_attr($unique_id); ?>" />
        
        <?php if ($show_gallery && !empty($gallery_ids)) : ?>
            <div class="mc-fp-gallery">
                <img src="<?php echo esc_url($main_img_url); ?>" data-full="<?php echo esc_url($main_img_url); ?>" class="mc-fp-thumb is-active" />
                <?php foreach ($gallery_ids as $thumb_id) : 
                    $thumb_src = wp_get_attachment_image_url($thumb_id, 'woocommerce_thumbnail');
                    $full_src = wp_get_attachment_image_url($thumb_id, 'large');
                ?>
                    <img src="<?php echo esc_url($thumb_src); ?>" data-full="<?php echo esc_url($full_src); ?>" class="mc-fp-thumb" />
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="mc-fp-content-zone">
        <?php if ($pre_head) echo '<span style="font-size: 11px; text-transform: uppercase; letter-spacing: 2px; color: '.esc_attr($accent).'; font-weight: bold; margin-bottom: 15px;">'.esc_html($pre_head).'</span>'; ?>
        
        <h2 class="mc-fp-title"><?php echo $product->get_name(); ?></h2>
        
        <?php if ($show_rating && $product->get_rating_count() > 0) : ?>
            <div style="color: #f59e0b; font-size: 14px; margin-bottom: 20px;">
                <?php echo wc_get_rating_html($product->get_average_rating()); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($show_price) : ?>
            <div class="mc-fp-price"><?php echo $product->get_price_html(); ?></div>
        <?php endif; ?>
        
        <?php if ($show_excerpt && $product->get_short_description()) : ?>
            <div class="mc-fp-excerpt"><?php echo apply_filters('woocommerce_short_description', $product->get_short_description()); ?></div>
        <?php endif; ?>

        <?php 
            // This hook handles Simple, Variable, External, and Grouped products perfectly
            global $post;
            $post = get_post($product_id);
            setup_postdata($post);
            woocommerce_template_single_add_to_cart();
            wp_reset_postdata();
        ?>
    </div>

    <?php if ($show_gallery && !empty($gallery_ids)) : ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.querySelector('.<?php echo esc_attr($unique_id); ?>');
            if (!container) return;

            const mainImg = container.querySelector('#main-img-<?php echo esc_attr($unique_id); ?>');
            const thumbs = container.querySelectorAll('.mc-fp-thumb');

            thumbs.forEach(thumb => {
                thumb.addEventListener('click', () => {
                    // Update Active State
                    thumbs.forEach(t => t.classList.remove('is-active'));
                    thumb.classList.add('is-active');

                    // Crossfade Image
                    mainImg.style.opacity = '0';
                    setTimeout(() => {
                        mainImg.src = thumb.dataset.full;
                        mainImg.style.opacity = '1';
                    }, 300);
                });
            });
        });
    </script>
    <?php endif; ?>
</div>