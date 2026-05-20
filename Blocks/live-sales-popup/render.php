<?php
if (!class_exists('WooCommerce')) return;

$lookback = $attributes['lookbackDays'] ?? 7;
$limit = $attributes['maxOrders'] ?? 10;
$privacy = $attributes['privacyMode'] ?? 'first_name';
$position = $attributes['position'] ?? 'bottom-left';
$anim = $attributes['animStyle'] ?? 'slide';
$duration = $attributes['durationMs'] ?? 5000;
$interval = $attributes['intervalMs'] ?? 3000;
$bg = $attributes['cardBg'] ?? '#ffffff';
$text_c = $attributes['textColor'] ?? '#0f172a';
$accent = $attributes['accentColor'] ?? '#3b82f6';
$radius = $attributes['cardRadius'] ?? 8;
$shadow = ($attributes['shadowOpacity'] ?? 15) / 100;

$unique_id = uniqid('mc-sales-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "modern-core-sales-popup {$unique_id} pos-{$position} anim-{$anim}"]);

// 1. Data Aggregation & Caching
$transient_key = "mc_sales_popup_{$lookback}_{$limit}_{$privacy}";
$sales_data = get_transient($transient_key);

if (false === $sales_data) {
    $sales_data = [];
    $orders = wc_get_orders([
        'limit' => $limit,
        'orderby' => 'date',
        'order' => 'DESC',
        'status' => ['wc-processing', 'wc-completed'],
        'date_created' => '>=' . (time() - ($lookback * DAY_IN_SECONDS))
    ]);

    foreach ($orders as $order) {
        $items = $order->get_items();
        if (empty($items)) continue;

        // Just get the first item in the order to feature
        $item = reset($items);
        $product = $item->get_product();
        if (!$product) continue;

        // Privacy Parsing
        $buyer_name = 'A customer';
        if ($privacy === 'first_name') {
            $buyer_name = $order->get_billing_first_name() ?: 'A customer';
        } elseif ($privacy === 'full_name') {
            $buyer_name = trim($order->get_billing_first_name() . ' ' . $order->get_billing_last_name()) ?: 'A customer';
        }

        $city = $order->get_billing_city() ?: 'their city';
        $time_diff = human_time_diff($order->get_date_created()->getOffsetTimestamp(), time()) . ' ago';
        $img_src = wp_get_attachment_image_url($product->get_image_id(), 'thumbnail') ?: wc_placeholder_img_src();

        $sales_data[] = [
            'name' => $buyer_name,
            'city' => $city,
            'product' => $product->get_name(),
            'url' => $product->get_permalink(),
            'image' => $img_src,
            'time' => $time_diff
        ];
    }
    // Cache for 15 minutes to prevent DB hammering
    set_transient($transient_key, $sales_data, 15 * MINUTE_IN_SECONDS);
}

// If no orders found within window, do not render block
if (empty($sales_data)) return;
?>

<div <?php echo $wrapper_attributes; ?>>
    <script type="application/json" class="mc-sales-payload">
        <?php echo wp_json_encode($sales_data); ?>
    </script>

    <style>
        .<?php echo esc_attr($unique_id); ?> {
            --mc-bg: <?php echo esc_attr($bg); ?>;
            --mc-text: <?php echo esc_attr($text_c); ?>;
            --mc-acc: <?php echo esc_attr($accent); ?>;
            --mc-rad: <?php echo esc_attr($radius); ?>px;
            --mc-shad: <?php echo esc_attr($shadow); ?>;
            
            position: fixed; z-index: 9999;
            pointer-events: none; /* Initially unclickable */
        }

        /* Positioning */
        .<?php echo esc_attr($unique_id); ?>.pos-bottom-left { bottom: 30px; left: 30px; }
        .<?php echo esc_attr($unique_id); ?>.pos-bottom-right { bottom: 30px; right: 30px; }
        .<?php echo esc_attr($unique_id); ?>.pos-top-left { top: 30px; left: 30px; }
        .<?php echo esc_attr($unique_id); ?>.pos-top-right { top: 30px; right: 30px; }

        @media (max-width: 768px) {
            .<?php echo esc_attr($unique_id); ?> { bottom: 15px !important; top: auto !important; left: 15px !important; right: 15px !important; }
        }

        /* The Card */
        .<?php echo esc_attr($unique_id); ?> .mc-sales-card {
            background: var(--mc-bg); color: var(--mc-text); padding: 15px; border-radius: var(--mc-rad);
            box-shadow: 0 10px 30px rgba(0,0,0,var(--mc-shad)); width: 320px; max-width: 100%;
            display: flex; gap: 15px; align-items: center; text-decoration: none;
            
            /* Animation Base States */
            opacity: 0; visibility: hidden; pointer-events: none;
            transition: all 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        /* Animation specific transforms */
        .<?php echo esc_attr($unique_id); ?>.anim-slide.pos-bottom-left .mc-sales-card { transform: translateX(-40px); }
        .<?php echo esc_attr($unique_id); ?>.anim-slide.pos-bottom-right .mc-sales-card { transform: translateX(40px); }
        .<?php echo esc_attr($unique_id); ?>.anim-slide.pos-top-left .mc-sales-card { transform: translateX(-40px); }
        .<?php echo esc_attr($unique_id); ?>.anim-slide.pos-top-right .mc-sales-card { transform: translateX(40px); }
        
        .<?php echo esc_attr($unique_id); ?>.anim-scale .mc-sales-card { transform: scale(0.9); }
        
        /* Active State */
        .<?php echo esc_attr($unique_id); ?> .mc-sales-card.is-active {
            opacity: 1; visibility: visible; pointer-events: auto; transform: translateX(0) scale(1) !important;
        }

        /* Internal Structure */
        .<?php echo esc_attr($unique_id); ?> .mc-sales-img {
            width: 60px; height: 60px; border-radius: 4px; background: #f1f5f9; flex-shrink: 0; object-fit: cover;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-sales-content { flex: 1; min-width: 0; }
        .<?php echo esc_attr($unique_id); ?> .mc-sales-action { font-size: 12px; opacity: 0.8; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .<?php echo esc_attr($unique_id); ?> .mc-sales-title { font-size: 14px; font-weight: bold; line-height: 1.3; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: var(--mc-text); }
        .<?php echo esc_attr($unique_id); ?> .mc-sales-meta { font-size: 11px; display: flex; alignItems: center; gap: 5px; opacity: 0.7; }
        .<?php echo esc_attr($unique_id); ?> .mc-sales-verify { display: flex; align-items: center; gap: 2px; color: var(--mc-acc); font-weight: bold; margin-left: auto; opacity: 1; }
    </style>

    <a href="#" class="mc-sales-card">
        <img src="" class="mc-sales-img" alt="" />
        <div class="mc-sales-content">
            <div class="mc-sales-action"><span class="mc-sales-buyer"></span> from <span class="mc-sales-city"></span> bought</div>
            <div class="mc-sales-title"></div>
            <div class="mc-sales-meta">
                <span class="mc-sales-time"></span>
                <span class="mc-sales-verify">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    Verified
                </span>
            </div>
        </div>
    </a>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const wrapper = document.querySelector('.<?php echo esc_attr($unique_id); ?>');
            if (!wrapper) return;

            const payloadEl = wrapper.querySelector('.mc-sales-payload');
            if (!payloadEl) return;

            let data = [];
            try {
                data = JSON.parse(payloadEl.innerHTML);
            } catch(e) { return; }

            if (data.length === 0) return;

            const card = wrapper.querySelector('.mc-sales-card');
            const img = wrapper.querySelector('.mc-sales-img');
            const buyer = wrapper.querySelector('.mc-sales-buyer');
            const city = wrapper.querySelector('.mc-sales-city');
            const title = wrapper.querySelector('.mc-sales-title');
            const time = wrapper.querySelector('.mc-sales-time');

            const duration = <?php echo (int)$duration; ?>;
            const interval = <?php echo (int)$interval; ?>;
            
            let currentIndex = 0;

            const triggerNextPopup = () => {
                const order = data[currentIndex];
                
                // Populate DOM
                card.href = order.url;
                img.src = order.image;
                buyer.innerText = order.name;
                city.innerText = order.city;
                title.innerText = order.product;
                time.innerText = order.time;

                // Animate In
                card.classList.add('is-active');

                // Animate Out after duration
                setTimeout(() => {
                    card.classList.remove('is-active');
                    
                    // Increment and queue next popup
                    currentIndex++;
                    if (currentIndex >= data.length) currentIndex = 0; // Loop back to start
                    
                    setTimeout(triggerNextPopup, interval);
                }, duration);
            };

            // Start loop after initial interval
            setTimeout(triggerNextPopup, interval);
        });
    </script>
</div>