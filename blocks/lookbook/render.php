<?php
$width_mode = $attributes['widthMode'] ?? 'contained';
$img_url = $attributes['imageUrl'] ?? '';
if (!$img_url) return;

$height = $attributes['imageHeight'] ?? 70;
$pins = $attributes['pins'] ?? [];
$pin_style = $attributes['pinStyle'] ?? 'pulse';
$pin_color = $attributes['pinColor'] ?? '#ffffff';
$pulse_color = $attributes['pulseColor'] ?? '#3b82f6';
$card_bg = $attributes['cardBg'] ?? '#ffffff';
$card_radius = $attributes['cardRadius'] ?? 8;
$overlay_opacity = ($attributes['overlayOpacity'] ?? 10) / 100;

$unique_id = uniqid('mc-lookbook-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "mc-block width-" . esc_attr($width_mode) . " modern-core-lookbook {$unique_id} style-{$pin_style}"]);

$is_wc_active = class_exists('WooCommerce');
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            position: relative;
            width: 100%;
            height: <?php echo esc_attr($height); ?>vh;
            background-image: url('<?php echo esc_url($img_url); ?>');
            background-size: cover;
            background-position: center;
            overflow: hidden;
            display: block;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-lb-overlay {
            position: absolute; inset: 0; background: rgba(0,0,0,<?php echo esc_attr($overlay_opacity); ?>); pointer-events: none; z-index: 1;
        }

        /* The Hotspot Pin */
        .<?php echo esc_attr($unique_id); ?> .mc-hotspot {
            position: absolute; z-index: 10; transform: translate(-50%, -50%);
        }

        .<?php echo esc_attr($unique_id); ?> .mc-pin-btn {
            width: 28px; height: 28px; background: <?php echo esc_attr($pin_color); ?>; border-radius: 50%;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2); border: 2px solid #fff; position: relative; z-index: 2;
            transition: transform 0.2s ease;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-pin-btn::before {
            content: ''; width: 8px; height: 8px; background: <?php echo esc_attr($pulse_color); ?>; border-radius: 50%;
        }

        /* Pulse Animation */
        <?php if ($pin_style === 'pulse') : ?>
        .<?php echo esc_attr($unique_id); ?> .mc-pin-btn::after {
            content: ''; position: absolute; width: 100%; height: 100%; border-radius: 50%;
            background: <?php echo esc_attr($pulse_color); ?>; z-index: -1; animation: mcLivePulse 2s infinite;
        }
        @keyframes mcLivePulse { 0% { transform: scale(1); opacity: 0.8; } 100% { transform: scale(2.5); opacity: 0; } }
        <?php endif; ?>

        /* Product Popup Card */
        .<?php echo esc_attr($unique_id); ?> .mc-popup-card {
            position: absolute; top: 50%; width: 220px; background: <?php echo esc_attr($card_bg); ?>;
            border-radius: <?php echo esc_attr($card_radius); ?>px; padding: 12px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2); opacity: 0; visibility: hidden;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); z-index: 20;
            pointer-events: none; transform: translateY(-50%) scale(0.9);
        }

        /* Hover / Tap State */
        .<?php echo esc_attr($unique_id); ?> .mc-hotspot:hover .mc-popup-card,
        .<?php echo esc_attr($unique_id); ?> .mc-hotspot.is-open .mc-popup-card {
            opacity: 1; visibility: visible; pointer-events: auto; transform: translateY(-50%) scale(1);
        }
        .<?php echo esc_attr($unique_id); ?> .mc-hotspot:hover .mc-pin-btn { transform: scale(1.2); }

        /* Collision Detection CSS (Opens left or right depending on pin X position) */
        .<?php echo esc_attr($unique_id); ?> .pos-right .mc-popup-card { left: 40px; }
        .<?php echo esc_attr($unique_id); ?> .pos-left .mc-popup-card { right: 40px; }

        /* Inside the Card */
        .<?php echo esc_attr($unique_id); ?> .mc-popup-img { width: 100%; aspect-ratio: 1/1; object-fit: cover; border-radius: <?php echo esc_attr(max(0, $card_radius - 4)); ?>px; margin-bottom: 10px; }
        .<?php echo esc_attr($unique_id); ?> .mc-popup-title { font-size: 13px; font-weight: bold; margin: 0 0 5px 0; color: #0f172a; line-height: 1.2; text-decoration: none; display: block; }
        .<?php echo esc_attr($unique_id); ?> .mc-popup-price { font-size: 13px; font-weight: bold; color: #0f172a; margin-bottom: 10px; }
        .<?php echo esc_attr($unique_id); ?> .mc-popup-atc { display: block; width: 100%; text-align: center; padding: 8px; background: <?php echo esc_attr($pulse_color); ?>; color: #fff; font-size: 11px; font-weight: bold; text-decoration: none; border-radius: 4px; transition: opacity 0.2s; }
        .<?php echo esc_attr($unique_id); ?> .mc-popup-atc:hover { opacity: 0.8; }
    </style>

    <div class="mc-lb-overlay"></div>

    <?php 
    if ($is_wc_active && !empty($pins)) {
        foreach ($pins as $pin) {
            $product_id = intval($pin['productId']);
            if (!$product_id) continue;

            $product = wc_get_product($product_id);
            if (!$product) continue;

            // Determine if popup should open to the left or right to avoid clipping
            $pos_class = $pin['x'] > 50 ? 'pos-left' : 'pos-right';
            $img_url = modern_core_product_image_url( $product, 'woocommerce_thumbnail' );
            $link = $product->get_permalink();
            ?>

            <div class="mc-hotspot <?php echo esc_attr($pos_class); ?>" style="left: <?php echo esc_attr($pin['x']); ?>%; top: <?php echo esc_attr($pin['y']); ?>%;">
                <div class="mc-pin-btn"></div>
                
                <div class="mc-popup-card">
                    <a href="<?php echo esc_url($link); ?>">
                        <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" class="mc-popup-img" />
                    </a>
                    <a href="<?php echo esc_url($link); ?>" class="mc-popup-title"><?php echo $product->get_name(); ?></a>
                    <div class="mc-popup-price"><?php echo $product->get_price_html(); ?></div>
                    
                    <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" data-quantity="1" class="mc-popup-atc ajax_add_to_cart" data-product_id="<?php echo esc_attr($product->get_id()); ?>">
                        Add to Cart
                    </a>
                </div>
            </div>

            <?php
        }
    }
    ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.innerWidth <= 768) {
                const hotspots = document.querySelectorAll('.<?php echo esc_attr($unique_id); ?> .mc-hotspot');
                hotspots.forEach(spot => {
                    spot.addEventListener('click', (e) => {
                        // Prevent the click from navigating if they just want to see the card
                        if (!e.target.closest('.mc-popup-card')) {
                            e.preventDefault();
                            // Close others
                            hotspots.forEach(h => { if(h !== spot) h.classList.remove('is-open'); });
                            spot.classList.toggle('is-open');
                        }
                    });
                });
            }
        });
    </script>
</div>