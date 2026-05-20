<?php
if (!class_exists('WooCommerce')) return;

$source_mode = $attributes['sourceMode'] ?? 'dynamic';
$static_ids = $attributes['staticProducts'] ?? '';
$max_cols = $attributes['maxColumns'] ?? 4;
$label_w = $attributes['labelWidth'] ?? 20;
$sticky = $attributes['isSticky'] ?? true;
$alt_rows = $attributes['alternateRows'] ?? true;
$show_img = $attributes['showImage'] ?? true;
$show_price = $attributes['showPrice'] ?? true;
$show_rating = $attributes['showRating'] ?? true;
$show_atc = $attributes['showAtc'] ?? true;
$ratio = $attributes['imageRatio'] ?? '1/1';
$radius = $attributes['tableRadius'] ?? 12;
$head_bg = $attributes['headerBg'] ?? '#ffffff';
$row_odd = $attributes['rowOddBg'] ?? '#ffffff';
$row_even = $attributes['rowEvenBg'] ?? '#f8fafc';
$border_c = $attributes['borderColor'] ?? '#e2e8f0';
$accent = $attributes['accentColor'] ?? '#3b82f6';

$unique_id = uniqid('mc-compare-');

// Dynamic Routing
$product_ids = [];
if ($source_mode === 'static' && !empty($static_ids)) {
    $product_ids = array_map('intval', explode(',', $static_ids));
} else {
    // In a full production environment, this checks Session Storage or Cookies for the dynamic "Compare List"
    // For now, we simulate pulling the 3 most recent products if dynamic is selected
    $args = ['post_type' => 'product', 'posts_per_page' => $max_cols, 'post_status' => 'publish'];
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        foreach ($query->posts as $p) { $product_ids[] = $p->ID; }
    }
}

if (empty($product_ids)) {
    echo '<div style="padding: 40px; text-align: center; background: #f8fafc;">Compare list is empty.</div>';
    return;
}

// Slice to max columns
$product_ids = array_slice($product_ids, 0, $max_cols);

// Fetch product objects
$products = [];
foreach ($product_ids as $id) {
    $p = wc_get_product($id);
    if ($p) $products[] = $p;
}

if (empty($products)) return;

// Collect all unique attributes across the selected products
$all_attributes = [];
foreach ($products as $p) {
    foreach ($p->get_attributes() as $slug => $attr) {
        if (!isset($all_attributes[$slug])) {
            $all_attributes[$slug] = wc_attribute_label($slug);
        }
    }
}

$wrapper_attributes = get_block_wrapper_attributes(['class' => "modern-core-compare-table {$unique_id}"]);
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            width: 100%;
            overflow-x: auto; /* Enables horizontal scroll on mobile */
            border: 1px solid <?php echo esc_attr($border_c); ?>;
            border-radius: <?php echo esc_attr($radius); ?>px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            background: #fff;
            position: relative;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-cmp-row {
            display: flex;
            min-width: 800px; /* Forces scroll on small screens */
            border-bottom: 1px solid <?php echo esc_attr($border_c); ?>;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-cmp-row:last-child { border-bottom: none; }

        /* Row Colors */
        <?php if ($alt_rows) : ?>
            .<?php echo esc_attr($unique_id); ?> .mc-cmp-data-row:nth-child(odd) { background-color: <?php echo esc_attr($row_odd); ?>; }
            .<?php echo esc_attr($unique_id); ?> .mc-cmp-data-row:nth-child(even) { background-color: <?php echo esc_attr($row_even); ?>; }
        <?php endif; ?>

        /* Cells */
        .<?php echo esc_attr($unique_id); ?> .mc-cmp-label {
            width: <?php echo esc_attr($label_w); ?>%;
            min-width: 150px;
            padding: 20px;
            font-weight: bold;
            font-size: 13px;
            color: #64748b;
            border-right: 1px solid <?php echo esc_attr($border_c); ?>;
            display: flex;
            align-items: center;
            background: inherit; /* Inherits row color */
            position: sticky; left: 0; z-index: 5; /* Locks left column on scroll */
        }
        
        .<?php echo esc_attr($unique_id); ?> .mc-cmp-cell {
            flex: 1;
            padding: 20px;
            font-size: 14px;
            border-right: 1px solid <?php echo esc_attr($border_c); ?>;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-cmp-cell:last-child { border-right: none; }

        /* Sticky Header row */
        .<?php echo esc_attr($unique_id); ?> .mc-cmp-header-row {
            background-color: <?php echo esc_attr($head_bg); ?>;
            border-bottom: 2px solid <?php echo esc_attr($border_c); ?>;
            <?php if ($sticky) : ?>
            position: sticky; top: 0; z-index: 10;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            <?php endif; ?>
        }
        /* Top-Left intersection must be above both scrolls */
        .<?php echo esc_attr($unique_id); ?> .mc-cmp-header-row .mc-cmp-label { z-index: 15; background-color: <?php echo esc_attr($head_bg); ?>; }

        /* Header Content styling */
        .<?php echo esc_attr($unique_id); ?> .mc-cmp-remove { position: absolute; top: 10px; right: 10px; font-size: 16px; opacity: 0.3; cursor: pointer; border: none; background: none; }
        .<?php echo esc_attr($unique_id); ?> .mc-cmp-remove:hover { opacity: 1; color: #ef4444; }
        .<?php echo esc_attr($unique_id); ?> .mc-cmp-img { width: 100%; max-width: 150px; aspect-ratio: <?php echo esc_attr($ratio); ?>; object-fit: cover; border-radius: 4px; margin-bottom: 15px; }
        .<?php echo esc_attr($unique_id); ?> .mc-cmp-title { font-size: 15px; font-weight: bold; margin: 0 0 10px 0; color: inherit; text-decoration: none; line-height: 1.2; }
        .<?php echo esc_attr($unique_id); ?> .mc-cmp-price { font-weight: bold; font-size: 16px; margin-bottom: 15px; }
        
        .<?php echo esc_attr($unique_id); ?> .ajax_add_to_cart {
            display: block; width: 100%; padding: 12px; background: <?php echo esc_attr($accent); ?>; color: #fff;
            text-align: center; font-size: 13px; font-weight: bold; border-radius: 4px; text-decoration: none;
        }
    </style>

    <div class="mc-cmp-row mc-cmp-header-row">
        <div class="mc-cmp-label"></div>
        <?php foreach ($products as $p) : ?>
            <div class="mc-cmp-cell" style="position: relative; justify-content: flex-start;">
                <?php if ($source_mode === 'dynamic') echo '<button class="mc-cmp-remove">✕</button>'; ?>
                
                <?php if ($show_img) : ?>
                    <a href="<?php echo esc_url($p->get_permalink()); ?>">
                        <img src="<?php echo esc_url(wp_get_attachment_image_url($p->get_image_id(), 'woocommerce_thumbnail')); ?>" class="mc-cmp-img" />
                    </a>
                <?php endif; ?>

                <a href="<?php echo esc_url($p->get_permalink()); ?>" class="mc-cmp-title"><?php echo $p->get_name(); ?></a>
                
                <?php if ($show_rating && $p->get_rating_count() > 0) : ?>
                    <div style="color: #f59e0b; font-size: 12px; margin-bottom: 10px;"><?php echo wc_get_rating_html($p->get_average_rating()); ?></div>
                <?php endif; ?>

                <?php if ($show_price) : ?>
                    <div class="mc-cmp-price"><?php echo $p->get_price_html(); ?></div>
                <?php endif; ?>

                <?php 
                if ($show_atc) {
                    global $post; $post = get_post($p->get_id()); setup_postdata($post);
                    woocommerce_template_loop_add_to_cart();
                    wp_reset_postdata();
                }
                ?>
            </div>
        <?php endforeach; ?>
    </div>

    <?php foreach ($all_attributes as $slug => $label) : ?>
        <div class="mc-cmp-row mc-cmp-data-row">
            <div class="mc-cmp-label"><?php echo esc_html($label); ?></div>
            <?php foreach ($products as $p) : 
                $val = $p->get_attribute($slug);
            ?>
                <div class="mc-cmp-cell">
                    <?php echo empty($val) ? '<span style="opacity: 0.2;">—</span>' : esc_html($val); ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
    
</div>