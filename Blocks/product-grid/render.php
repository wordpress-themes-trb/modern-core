<?php
// Safety check: Don't crash if WooCommerce is not active
if (!class_exists('WooCommerce')) {
    echo '<p>Please install and activate WooCommerce to use the Product Grid.</p>';
    return;
}

$count = $attributes['productCount'] ?? 8;
$sort = $attributes['querySort'] ?? 'date';
$layout = $attributes['layoutMode'] ?? 'grid';
$cols_d = $attributes['columnsDesktop'] ?? 4;
$cols_m = $attributes['columnsMobile'] ?? 2;
$ratio = $attributes['imageRatio'] ?? '3/4';
$hover_effect = $attributes['imageHover'] ?? 'zoom';
$radius = $attributes['cardRadius'] ?? 8;
$card_bg = $attributes['cardBg'] ?? '#ffffff';
$lift = $attributes['cardHoverLift'] ?? true;
$show_cat = $attributes['showCategory'] ?? false;
$show_rating = $attributes['showRating'] ?? true;
$atc_style = $attributes['atcStyle'] ?? 'solid';
$atc_pos = $attributes['atcPosition'] ?? 'below';
$show_toolbar = $attributes['showToolbar'] ?? true;
$accent = $attributes['accentColor'] ?? '#000000';

$unique_id = uniqid('mc-pgrid-');

// Query Arguments
$args = [
    'post_type'      => 'product',
    'posts_per_page' => $count,
    'post_status'    => 'publish',
];

if ($sort === 'popularity') {
    $args['meta_key'] = 'total_sales';
    $args['orderby']  = 'meta_value_num';
} elseif ($sort === 'price') {
    $args['meta_key'] = '_price';
    $args['orderby']  = 'meta_value_num';
    $args['order']    = 'ASC';
} elseif ($sort === 'price-desc') {
    $args['meta_key'] = '_price';
    $args['orderby']  = 'meta_value_num';
    $args['order']    = 'DESC';
}

$products = new WP_Query($args);

$wrapper_classes = "modern-core-product-grid {$unique_id} layout-{$layout} hover-{$hover_effect}";
$wrapper_attributes = get_block_wrapper_attributes(['class' => $wrapper_classes]);
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            --mc-cols: <?php echo esc_attr($cols_d); ?>;
            --mc-cols-m: <?php echo esc_attr($cols_m); ?>;
            --mc-ratio: <?php echo esc_attr($ratio === 'auto' ? 'auto' : $ratio); ?>;
            --mc-radius: <?php echo esc_attr($radius); ?>px;
            --mc-bg: <?php echo esc_attr($card_bg); ?>;
            --mc-accent: <?php echo esc_attr($accent); ?>;
            
            /* Margin, Containment, and Padding Enforcement */
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 5%;
            box-sizing: border-box;
        }

        .<?php echo esc_attr($unique_id); ?> *,
        .<?php echo esc_attr($unique_id); ?> *::before,
        .<?php echo esc_attr($unique_id); ?> *::after {
            box-sizing: border-box !important;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-grid-container {
            display: grid;
            grid-template-columns: repeat(var(--mc-cols), 1fr);
            gap: 30px;
        }

        @media (max-width: 768px) {
            .<?php echo esc_attr($unique_id); ?> { padding: 40px 20px; }
            .<?php echo esc_attr($unique_id); ?> .mc-grid-container {
                grid-template-columns: repeat(var(--mc-cols-m), 1fr);
                gap: 15px;
            }
        }

        /* Card Geometry */
        .<?php echo esc_attr($unique_id); ?> .mc-product-card {
            background: var(--mc-bg);
            border-radius: var(--mc-radius);
            position: relative;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }
        <?php if ($lift) : ?>
        .<?php echo esc_attr($unique_id); ?> .mc-product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        <?php endif; ?>

        /* Image Handling & Hover Effects */
        .<?php echo esc_attr($unique_id); ?> .mc-card-image {
            aspect-ratio: var(--mc-ratio);
            position: relative;
            overflow: hidden;
            display: block;
            width: 100%;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-img-primary {
            width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease, opacity 0.4s ease; display: block;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-img-secondary {
            position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0; transition: opacity 0.4s ease; display: block;
        }

        /* Hover: Zoom */
        .<?php echo esc_attr($unique_id); ?>.hover-zoom .mc-product-card:hover .mc-img-primary { transform: scale(1.08); }
        
        /* Hover: Swap */
        .<?php echo esc_attr($unique_id); ?>.hover-swap .mc-product-card:hover .mc-img-primary { opacity: 0; }
        .<?php echo esc_attr($unique_id); ?>.hover-swap .mc-product-card:hover .mc-img-secondary { opacity: 1; }

        /* Card Content */
        .<?php echo esc_attr($unique_id); ?> .mc-card-info {
            padding: 20px; display: flex; flex-direction: column; flex: 1;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-title {
            font-size: 15px; margin: 0 0 10px 0; line-height: 1.3; color: inherit; text-decoration: none; font-weight: bold;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-price { font-weight: bold; font-size: 15px; margin-bottom: 15px; }
        .<?php echo esc_attr($unique_id); ?> .mc-price del { opacity: 0.5; font-size: 13px; font-weight: normal; margin-left: 5px; }
        .<?php echo esc_attr($unique_id); ?> .mc-price ins { text-decoration: none; color: #ef4444; }

        /* Add to Cart Native Styling */
        .<?php echo esc_attr($unique_id); ?> .ajax_add_to_cart,
        .<?php echo esc_attr($unique_id); ?> .added_to_cart {
            display: block !important; margin-top: auto; padding: 12px !important; text-align: center !important;
            font-size: 13px !important; font-weight: bold !important; border-radius: var(--mc-radius) !important;
            text-decoration: none !important; transition: opacity 0.2s ease !important; width: 100% !important; line-height: 1.2 !important;
            <?php if ($atc_style === 'solid') : ?>
                background: var(--mc-accent) !important; color: #fff !important; border: none !important;
            <?php elseif ($atc_style === 'outline') : ?>
                background: transparent !important; color: var(--mc-accent) !important; border: 2px solid var(--mc-accent) !important;
            <?php else : ?>
                background: transparent !important; color: var(--mc-accent) !important; padding: 0 !important; text-align: left !important; text-decoration: underline !important; margin-top: 5px !important;
            <?php endif; ?>
        }
        .<?php echo esc_attr($unique_id); ?> .ajax_add_to_cart:hover { opacity: 0.8 !important; }
        .<?php echo esc_attr($unique_id); ?> .ajax_add_to_cart.loading { opacity: 0.5 !important; }

        /* ATC Overlay Mode */
        <?php if ($atc_pos === 'overlay') : ?>
        .<?php echo esc_attr($unique_id); ?> .mc-card-info .ajax_add_to_cart,
        .<?php echo esc_attr($unique_id); ?> .mc-card-info .added_to_cart { display: none !important; }
        .<?php echo esc_attr($unique_id); ?> .mc-atc-overlay-wrap {
            position: absolute; bottom: 10px; left: 10px; right: 10px; z-index: 5;
            transform: translateY(20px); opacity: 0; transition: all 0.3s ease;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-product-card:hover .mc-atc-overlay-wrap {
            transform: translateY(0); opacity: 1;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-atc-overlay-wrap .ajax_add_to_cart,
        .<?php echo esc_attr($unique_id); ?> .mc-atc-overlay-wrap .added_to_cart {
            margin: 0 !important; background: #fff !important; color: #000 !important; box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important; border: none !important;
        }
        <?php endif; ?>

        /* Sale Badge */
        .<?php echo esc_attr($unique_id); ?> .onsale {
            position: absolute; top: 10px; left: 10px; background: #ef4444; color: #fff;
            font-size: 10px; font-weight: bold; padding: 4px 10px; border-radius: <?php echo $attributes['saleBadgeStyle'] === 'pill' ? '50px' : '4px'; ?>; z-index: 3; line-height: 1;
        }
    </style>

    <?php if ($show_toolbar) : ?>
        <div class="mc-grid-toolbar" style="display: flex; justify-content: space-between; margin-bottom: 30px; padding-bottom: 15px; border-bottom: 1px solid rgba(0,0,0,0.1); font-size: 13px;">
            <span style="opacity: 0.6;">Showing <?php echo $products->found_posts; ?> results</span>
            <span style="font-weight: bold;">Sort by: Recent</span>
        </div>
    <?php endif; ?>

    <?php if ($products->have_posts()) : ?>
        <div class="mc-grid-container">
            <?php while ($products->have_posts()) : $products->the_post(); 
                global $product;
                $link = apply_filters('woocommerce_loop_product_link', get_the_permalink(), $product);
                $img_id = $product->get_image_id();
                $primary_img = wp_get_attachment_image_url($img_id, 'woocommerce_thumbnail');
                
                $gallery = $product->get_gallery_image_ids();
                $secondary_img = !empty($gallery) ? wp_get_attachment_image_url($gallery[0], 'woocommerce_thumbnail') : null;
            ?>
                <div <?php wc_product_class('mc-product-card', $product); ?>>
                    
                    <a href="<?php echo esc_url($link); ?>" class="mc-card-image">
                        <?php if ($product->is_on_sale()) echo apply_filters('woocommerce_sale_flash', '<span class="onsale">' . esc_html__('Sale!', 'woocommerce') . '</span>',  $product); ?>
                        
                        <?php if ($primary_img) : ?>
                            <img src="<?php echo esc_url($primary_img); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" class="mc-img-primary" loading="lazy" />
                        <?php endif; ?>
                        
                        <?php if ($hover_effect === 'swap' && $secondary_img) : ?>
                            <img src="<?php echo esc_url($secondary_img); ?>" alt="Secondary View" class="mc-img-secondary" loading="lazy" />
                        <?php endif; ?>

                        <?php if ($atc_pos === 'overlay') : ?>
                            <div class="mc-atc-overlay-wrap">
                                <?php woocommerce_template_loop_add_to_cart(); ?>
                            </div>
                        <?php endif; ?>
                    </a>

                    <div class="mc-card-info">
                        <?php if ($show_cat) { echo wc_get_product_category_list($product->get_id(), ', ', '<span style="font-size: 11px; text-transform: uppercase; opacity: 0.5; margin-bottom: 5px; display: block;">', '</span>'); } ?>
                        
                        <a href="<?php echo esc_url($link); ?>" class="mc-title"><?php echo $product->get_name(); ?></a>
                        
                        <?php if ($show_rating && $product->get_rating_count() > 0) : ?>
                            <div style="font-size: 12px; margin-bottom: 10px; color: #f59e0b;">
                                <?php echo wc_get_rating_html($product->get_average_rating()); ?>
                            </div>
                        <?php endif; ?>

                        <div class="mc-price">
                            <?php echo $product->get_price_html(); ?>
                        </div>

                        <?php if ($atc_pos === 'below') : ?>
                            <?php woocommerce_template_loop_add_to_cart(); ?>
                        <?php endif; ?>
                    </div>

                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    <?php else : ?>
        <div style="text-align: center; padding: 60px 20px; background: rgba(0,0,0,0.02); border-radius: 8px;">
            <h3 style="margin-bottom: 15px;">No products found</h3>
            <p style="opacity: 0.6; margin-bottom: 20px;">Try adjusting your filters or checking back later.</p>
        </div>
    <?php endif; ?>
</div>