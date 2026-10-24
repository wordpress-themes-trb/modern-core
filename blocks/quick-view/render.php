<?php
$width_mode = $attributes['widthMode'] ?? 'contained';
if (!class_exists('WooCommerce')) return;

$width = $attributes['modalWidth'] ?? 900;
$radius = $attributes['modalRadius'] ?? 16;
$overlay_color = $attributes['overlayColor'] ?? 'rgba(15, 23, 42, 0.85)';
$blur = $attributes['overlayBlur'] ?? 10;
$anim = $attributes['animStyle'] ?? 'scale';
$ratio = $attributes['splitRatio'] ?? 50;
$accent = $attributes['accentColor'] ?? '#3b82f6';

$unique_id = uniqid('mc-qv-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "mc-block width-" . esc_attr($width_mode) . " modern-core-quick-view-modal {$unique_id} anim-{$anim}"]);

$img_width = $ratio;
$txt_width = 100 - $ratio;
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            position: fixed; inset: 0; z-index: 99999;
            display: flex; align-items: center; justify-content: center;
            padding: 20px; opacity: 0; pointer-events: none; visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        .<?php echo esc_attr($unique_id); ?>.is-open {
            opacity: 1; pointer-events: auto; visibility: visible;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-qv-overlay {
            position: absolute; inset: 0;
            background-color: <?php echo esc_attr($overlay_color); ?>;
            backdrop-filter: blur(<?php echo esc_attr($blur); ?>px);
            -webkit-backdrop-filter: blur(<?php echo esc_attr($blur); ?>px);
            z-index: 1;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-qv-dialog {
            position: relative; z-index: 2;
            width: 100%; max-width: <?php echo esc_attr($width); ?>px;
            background: #fff; border-radius: <?php echo esc_attr($radius); ?>px;
            display: flex; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
            max-height: 90vh; overflow-y: auto;
        }

        /* Animation States */
        .<?php echo esc_attr($unique_id); ?>.anim-scale .mc-qv-dialog { transform: scale(0.95); transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        .<?php echo esc_attr($unique_id); ?>.is-open.anim-scale .mc-qv-dialog { transform: scale(1); }
        .<?php echo esc_attr($unique_id); ?>.anim-slide .mc-qv-dialog { transform: translateY(30px); transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1); }
        .<?php echo esc_attr($unique_id); ?>.is-open.anim-slide .mc-qv-dialog { transform: translateY(0); }

        .<?php echo esc_attr($unique_id); ?> .mc-qv-close {
            position: absolute; top: 15px; right: 15px; width: 36px; height: 36px;
            background: rgba(0,0,0,0.05); color: #000; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; z-index: 10; transition: background 0.2s; border: none;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-qv-close:hover { background: rgba(0,0,0,0.1); }

        /* Zones */
        .<?php echo esc_attr($unique_id); ?> .mc-qv-img-zone { width: <?php echo esc_attr($img_width); ?>%; background: #f8fafc; padding: 40px; position: relative; }
        .<?php echo esc_attr($unique_id); ?> .mc-qv-txt-zone { width: <?php echo esc_attr($txt_width); ?>%; padding: 40px; display: flex; flex-direction: column; overflow-y: auto; }

        @media (max-width: 768px) {
            .<?php echo esc_attr($unique_id); ?> .mc-qv-dialog { flex-direction: column; }
            .<?php echo esc_attr($unique_id); ?> .mc-qv-img-zone, .<?php echo esc_attr($unique_id); ?> .mc-qv-txt-zone { width: 100%; }
            .<?php echo esc_attr($unique_id); ?> .mc-qv-img-zone { padding: 20px; }
            .<?php echo esc_attr($unique_id); ?> .mc-qv-txt-zone { padding: 20px; }
        }

        /* Skeleton Loader CSS */
        .mc-skeleton { background: #e2e8f0; border-radius: 4px; animation: mcPulse 1.5s infinite ease-in-out; }
        @keyframes mcPulse { 0% { opacity: 0.6; } 50% { opacity: 1; } 100% { opacity: 0.6; } }

        /* Form Reset for Native WC inject */
        .<?php echo esc_attr($unique_id); ?> .mc-qv-txt-zone .single_add_to_cart_button {
            width: 100%; padding: 15px; background: <?php echo esc_attr($accent); ?>; color: #fff;
            border-radius: 8px; font-weight: bold; border: none; margin-top: 15px;
        }
    </style>

    <div class="mc-qv-overlay"></div>

    <div class="mc-qv-dialog">
        <button class="mc-qv-close">✕</button>
        
        <div class="mc-qv-img-zone" id="mc-qv-img-container">
            <div class="mc-skeleton" style="width: 100%; aspect-ratio: 3/4;"></div>
        </div>
        
        <div class="mc-qv-txt-zone" id="mc-qv-txt-container">
            <div class="mc-skeleton" style="width: 80%; height: 30px; margin-bottom: 15px;"></div>
            <div class="mc-skeleton" style="width: 40%; height: 20px; margin-bottom: 20px;"></div>
            <div class="mc-skeleton" style="width: 100%; height: 60px; margin-bottom: 20px;"></div>
            <div class="mc-skeleton" style="width: 100%; height: 50px; border-radius: 8px;"></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.querySelector('.<?php echo esc_attr($unique_id); ?>');
            if (!modal) return;

            const overlay = modal.querySelector('.mc-qv-overlay');
            const closeBtn = modal.querySelector('.mc-qv-close');
            const imgContainer = modal.querySelector('#mc-qv-img-container');
            const txtContainer = modal.querySelector('#mc-qv-txt-container');

            const skeletonImg = `<div class="mc-skeleton" style="width: 100%; aspect-ratio: 3/4;"></div>`;
            const skeletonTxt = `
                <div class="mc-skeleton" style="width: 80%; height: 30px; margin-bottom: 15px;"></div>
                <div class="mc-skeleton" style="width: 40%; height: 20px; margin-bottom: 20px;"></div>
                <div class="mc-skeleton" style="width: 100%; height: 60px; margin-bottom: 20px;"></div>
                <div class="mc-skeleton" style="width: 100%; height: 50px; border-radius: 8px;"></div>
            `;

            const closeModal = () => {
                modal.classList.remove('is-open');
                document.body.style.overflow = '';
            };

            overlay.addEventListener('click', closeModal);
            closeBtn.addEventListener('click', closeModal);

            // Listen for global custom event triggered by Product Cards
            document.addEventListener('mc:quickview:open', (e) => {
                const productId = e.detail.productId;
                if (!productId) return;

                // 1. Open Modal & Show Skeletons instantly
                imgContainer.innerHTML = skeletonImg;
                txtContainer.innerHTML = skeletonTxt;
                modal.classList.add('is-open');
                document.body.style.overflow = 'hidden';

                // 2. Fetch data via WP Native Ajax
                // Note: You must register 'mc_quickview_ajax' action in functions.php
                const formData = new FormData();
                formData.append('action', 'mc_quickview_ajax');
                formData.append('product_id', productId);

                fetch(mc_ajax.ajaxurl, {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        imgContainer.innerHTML = data.data.image_html;
                        txtContainer.innerHTML = data.data.content_html;
                    } else {
                        txtContainer.innerHTML = '<p>Error loading product data.</p>';
                    }
                })
                .catch(() => {
                    txtContainer.innerHTML = '<p>Connection error.</p>';
                });
            });
        });
    </script>
</div>