<?php
$source_mode = $attributes['sourceMode'] ?? 'dynamic';
$product_id = $attributes['productId'] ?? 0;
$m_img = $attributes['manualImage'] ?? '';
$m_title = $attributes['manualTitle'] ?? '';
$m_price = $attributes['manualPrice'] ?? '';
$m_desc = $attributes['manualDesc'] ?? '';
$orient = $attributes['orientation'] ?? 'vertical';
$ratio = $attributes['imageRatio'] ?? '3/4';
$radius = $attributes['cardRadius'] ?? 12;
$bg = $attributes['cardBg'] ?? '#ffffff';
$text_color = $attributes['textColor'] ?? '#000000';
$accent = $attributes['accentColor'] ?? '#000000';
$lift = $attributes['hoverLift'] ?? true;
$show_cat = $attributes['showCategory'] ?? true;
$show_rating = $attributes['showRating'] ?? true;
$show_desc = $attributes['showDesc'] ?? false;
$show_wish = $attributes['showWishlist'] ?? true;
$atc_style = $attributes['atcStyle'] ?? 'solid';

$unique_id = uniqid('mc-scard-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "modern-core-standalone-card {$unique_id} orient-{$orient}"]);

// DATA ROUTING LOGIC
$is_wc_active = class_exists('WooCommerce');
$product = null;
$title = $m_title; $price = $m_price; $desc = $m_desc; $img_url = $m_img; $link = '#'; $cats = ''; $rating_html = '';

if ($source_mode === 'dynamic' && $is_wc_active && $product_id > 0) {
    $product = wc_get_product($product_id);
    if ($product) {
        $title = $product->get_name();
        $price = $product->get_price_html();
        $desc = $product->get_short_description();
        $img_url = modern_core_product_image_url( $product, 'large' );
        $link = $product->get_permalink();
        $cats = wc_get_product_category_list($product->get_id(), ', ');
        if ($product->get_rating_count() > 0) {
            $rating_html = wc_get_rating_html($product->get_average_rating());
        }
    }
}
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            display: flex;
            background-color: <?php echo esc_attr($bg); ?>;
            color: <?php echo esc_attr($text_color); ?>;
            border-radius: <?php echo esc_attr($radius); ?>px;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.08);
            transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.4s ease;
        }

        .<?php echo esc_attr($unique_id); ?> *,
        .<?php echo esc_attr($unique_id); ?> *::before,
        .<?php echo esc_attr($unique_id); ?> *::after {
            box-sizing: border-box !important;
        }

        /* Hover Lift Physics */
        <?php if ($lift) : ?>
        .<?php echo esc_attr($unique_id); ?>:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        <?php endif; ?>

        /* Orientation Logic */
        .<?php echo esc_attr($unique_id); ?>.orient-vertical { flex-direction: column; max-width: 400px; margin: 0 auto; }
        .<?php echo esc_attr($unique_id); ?>.orient-vertical .mc-sc-img-zone { width: 100%; aspect-ratio: <?php echo esc_attr($ratio); ?>; }
        .<?php echo esc_attr($unique_id); ?>.orient-vertical .mc-sc-content { width: 100%; }

        .<?php echo esc_attr($unique_id); ?>.orient-horizontal { flex-direction: row; max-width: 800px; margin: 0 auto; }
        .<?php echo esc_attr($unique_id); ?>.orient-horizontal .mc-sc-img-zone { width: 45%; }
        .<?php echo esc_attr($unique_id); ?>.orient-horizontal .mc-sc-content { width: 55%; justify-content: center; }

        @media (max-width: 768px) {
            .<?php echo esc_attr($unique_id); ?>.orient-horizontal { flex-direction: column; }
            .<?php echo esc_attr($unique_id); ?>.orient-horizontal .mc-sc-img-zone { width: 100%; aspect-ratio: <?php echo esc_attr($ratio); ?>; }
            .<?php echo esc_attr($unique_id); ?>.orient-horizontal .mc-sc-content { width: 100%; }
        }

        /* Image Zone */
        .<?php echo esc_attr($unique_id); ?> .mc-sc-img-zone {
            position: relative; overflow: hidden; display: block;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-sc-img {
            width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease; display: block;
        }
        .<?php echo esc_attr($unique_id); ?>:hover .mc-sc-img { transform: scale(1.05); }

        .<?php echo esc_attr($unique_id); ?> .mc-wish-btn {
            position: absolute; top: 15px; right: 15px; width: 36px; height: 36px;
            background: #fff; color: #000; border-radius: 50%; display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); z-index: 2; text-decoration: none;
        }

        /* Content Zone */
        .<?php echo esc_attr($unique_id); ?> .mc-sc-content {
            padding: 30px; display: flex; flex-direction: column;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-sc-cat { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; opacity: 0.5; margin-bottom: 8px; display: block; }
        .<?php echo esc_attr($unique_id); ?> .mc-sc-title { margin: 0 0 10px 0; font-size: 22px; font-weight: bold; color: inherit; text-decoration: none; line-height: 1.2; display: block; }
        .<?php echo esc_attr($unique_id); ?> .mc-sc-rating { color: #f59e0b; font-size: 13px; margin-bottom: 15px; }
        .<?php echo esc_attr($unique_id); ?> .mc-sc-desc { font-size: 14px; opacity: 0.7; margin-bottom: 25px; line-height: 1.6; }
        .<?php echo esc_attr($unique_id); ?> .mc-sc-price { font-size: 18px; font-weight: bold; margin-bottom: 20px; margin-top: auto; }
        .<?php echo esc_attr($unique_id); ?> .mc-sc-price del { opacity: 0.5; font-size: 14px; font-weight: normal; margin-left: 5px; }
        .<?php echo esc_attr($unique_id); ?> .mc-sc-price ins { text-decoration: none; color: #ef4444; }

        /* Aggressive WooCommerce Button Override to ensure centering */
        .<?php echo esc_attr($unique_id); ?> .mc-sc-btn-wrap { width: 100%; margin-top: 10px; }
        .<?php echo esc_attr($unique_id); ?> .mc-sc-btn-wrap a,
        .<?php echo esc_attr($unique_id); ?> .mc-sc-btn-wrap .button,
        .<?php echo esc_attr($unique_id); ?> .mc-sc-btn-wrap .added_to_cart {
            display: block !important; width: 100% !important; padding: 14px !important; text-align: center !important; 
            font-size: 14px !important; font-weight: bold !important; border-radius: <?php echo esc_attr($radius); ?>px !important;
            text-decoration: none !important; transition: opacity 0.2s !important; margin: 0 !important; line-height: 1.2 !important;
            <?php if ($atc_style === 'solid') : ?> background: <?php echo esc_attr($accent); ?> !important; color: #fff !important; border: none !important;
            <?php elseif ($atc_style === 'outline') : ?> background: transparent !important; color: <?php echo esc_attr($accent); ?> !important; border: 2px solid <?php echo esc_attr($accent); ?> !important;
            <?php else : ?> background: transparent !important; color: <?php echo esc_attr($accent); ?> !important; padding: 0 !important; text-align: left !important; text-decoration: underline !important; <?php endif; ?>
        }
        .<?php echo esc_attr($unique_id); ?> .mc-sc-btn-wrap a:hover { opacity: 0.8 !important; }
    </style>

    <a href="<?php echo esc_url($link); ?>" class="mc-sc-img-zone">
        <?php if ($img_url) : ?>
            <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($title); ?>" class="mc-sc-img" />
        <?php endif; ?>
        <?php if ($show_wish) : ?>
            <?php if ( $product ) : ?>
                <?php echo modern_core_save_button( $product->get_id(), 'wishlist', 'mc-card-save' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in the helper. ?>
            <?php endif; ?>
        <?php endif; ?>
    </a>

    <div class="mc-sc-content">
        <?php if ($show_cat && $cats) echo "<div class='mc-sc-cat'>{$cats}</div>"; ?>
        
        <a href="<?php echo esc_url($link); ?>" class="mc-sc-title"><?php echo esc_html($title); ?></a>
        
        <?php if ($show_rating && $rating_html) echo "<div class='mc-sc-rating'>{$rating_html}</div>"; ?>
        
        <?php if ($show_desc && $desc) echo "<div class='mc-sc-desc'>" . wp_kses_post($desc) . "</div>"; ?>
        
        <div class="mc-sc-price"><?php echo wp_kses_post( $price ); ?></div>

        <div class="mc-sc-btn-wrap">
            <?php 
                if ($product && $source_mode === 'dynamic') {
                    global $post;
                    $post = get_post($product_id);
                    setup_postdata($post);
                    woocommerce_template_loop_add_to_cart();
                    wp_reset_postdata();
                } else {
                    echo '<a href="#">Add to Cart</a>';
                }
            ?>
        </div>
    </div>
</div>