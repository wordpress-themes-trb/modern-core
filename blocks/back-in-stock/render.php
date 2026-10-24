<?php
$width_mode = $attributes['widthMode'] ?? 'contained';
if (!class_exists('WooCommerce')) return;

global $product;
if (!$product || $product->is_in_stock()) {
    // If the product is in stock, do not render the block.
    return;
}

$heading = $attributes['heading'] ?? 'Notify me when available';
$msg = $attributes['message'] ?? 'We\'ll send you a quick email when this item is back in stock.';
$btn_text = $attributes['btnText'] ?? 'Notify Me';
$show_name = $attributes['showName'] ?? false;
$privacy = $attributes['privacyNote'] ?? 'We respect your privacy and won\'t spam you.';
$success_h = $attributes['successHeading'] ?? 'You\'re on the list!';
$success_m = $attributes['successMsg'] ?? 'We will email you the moment this returns.';
$bg = $attributes['bgColor'] ?? '#f8fafc';
$text_c = $attributes['textColor'] ?? '#0f172a';
$accent = $attributes['accentColor'] ?? '#3b82f6';
$radius = $attributes['inputRadius'] ?? 6;

$unique_id = uniqid('mc-bis-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "mc-block width-" . esc_attr($width_mode) . " modern-core-bis {$unique_id}"]);
$product_id = $product->get_id();
?>

<div <?php echo $wrapper_attributes; ?> data-pid="<?php echo esc_attr($product_id); ?>">
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            --mc-bg: <?php echo esc_attr($bg); ?>;
            --mc-text: <?php echo esc_attr($text_c); ?>;
            --mc-acc: <?php echo esc_attr($accent); ?>;
            --mc-rad: <?php echo esc_attr($radius); ?>px;
            width: 100%; max-width: 400px;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-bis-card {
            background: var(--mc-bg); color: var(--mc-text); padding: 30px; border-radius: 12px; border: 1px solid rgba(0,0,0,0.05); position: relative;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-bis-icon {
            color: var(--mc-acc); margin-bottom: 10px; display: block;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-bis-title { font-size: 20px; font-weight: bold; margin: 0 0 8px 0; line-height: 1.2; }
        .<?php echo esc_attr($unique_id); ?> .mc-bis-desc { font-size: 14px; opacity: 0.8; margin: 0 0 20px 0; line-height: 1.5; }

        .<?php echo esc_attr($unique_id); ?> .mc-bis-form { display: flex; flex-direction: column; gap: 10px; }
        .<?php echo esc_attr($unique_id); ?> .mc-bis-input {
            padding: 12px 15px; border-radius: var(--mc-rad); border: 1px solid rgba(0,0,0,0.1); width: 100%; box-sizing: border-box; font-size: 15px; outline: none; background: #fff; color: #000;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-bis-input:focus { border-color: var(--mc-acc); }

        .<?php echo esc_attr($unique_id); ?> .mc-bis-btn {
            padding: 12px 15px; border-radius: var(--mc-rad); border: none; background: var(--mc-acc); color: #fff; font-weight: bold; font-size: 15px; width: 100%; cursor: pointer; transition: opacity 0.2s;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-bis-btn:hover { opacity: 0.9; }
        .<?php echo esc_attr($unique_id); ?> .mc-bis-btn.is-loading { opacity: 0.7; pointer-events: none; }

        .<?php echo esc_attr($unique_id); ?> .mc-bis-privacy { font-size: 11px; opacity: 0.6; margin-top: 12px; text-align: center; }

        /* Success State */
        .<?php echo esc_attr($unique_id); ?> .mc-bis-success { display: none; text-align: center; padding: 10px 0; }
        .<?php echo esc_attr($unique_id); ?>.is-success .mc-bis-form-wrapper { display: none; }
        .<?php echo esc_attr($unique_id); ?>.is-success .mc-bis-success { display: block; animation: mcBisFadeIn 0.5s ease; }
        @keyframes mcBisFadeIn { from{opacity:0; transform: translateY(10px);} to{opacity:1; transform: translateY(0);} }
    </style>

    <div class="mc-bis-card">
        <div class="mc-bis-form-wrapper">
            <svg class="mc-bis-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
            <h3 class="mc-bis-title"><?php echo esc_html($heading); ?></h3>
            <p class="mc-bis-desc"><?php echo esc_html($msg); ?></p>
            
            <form class="mc-bis-form">
                <?php if ($show_name) : ?>
                    <input type="text" name="bis_name" class="mc-bis-input" placeholder="Your First Name" required />
                <?php endif; ?>
                <input type="email" name="bis_email" class="mc-bis-input" placeholder="Your Email Address" required />
                <button type="submit" class="mc-bis-btn"><?php echo esc_html($btn_text); ?></button>
            </form>
            
            <?php if ($privacy) echo '<div class="mc-bis-privacy">' . esc_html($privacy) . '</div>'; ?>
        </div>

        <div class="mc-bis-success">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--mc-acc)" stroke-width="2" style="margin: 0 auto 15px auto; display: block;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            <h3 class="mc-bis-title"><?php echo esc_html($success_h); ?></h3>
            <p class="mc-bis-desc" style="margin:0;"><?php echo esc_html($success_m); ?></p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const block = document.querySelector('.<?php echo esc_attr($unique_id); ?>');
            if (!block) return;

            const form = block.querySelector('.mc-bis-form');
            const btn = block.querySelector('.mc-bis-btn');

            form.addEventListener('submit', (e) => {
                e.preventDefault();
                const btnOriginalText = btn.innerText;
                btn.classList.add('is-loading');
                btn.innerText = 'Sending...';

                const formData = new FormData(form);
                formData.append('action', 'mc_back_in_stock_ajax');
                formData.append('nonce', (window.mc_ajax && mc_ajax.nonce) || '');
                formData.append('product_id', block.dataset.pid);

                fetch(mc_ajax.ajaxurl, {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        block.classList.add('is-success');
                    } else {
                        alert(data.data.message || 'There was an error. Please try again.');
                        btn.classList.remove('is-loading');
                        btn.innerText = btnOriginalText;
                    }
                })
                .catch(() => {
                    alert('Network error. Please try again.');
                    btn.classList.remove('is-loading');
                    btn.innerText = btnOriginalText;
                });
            });
        });
    </script>
</div>