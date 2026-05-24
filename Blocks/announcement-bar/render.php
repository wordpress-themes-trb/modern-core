<?php
$width_mode = $attributes['widthMode'] ?? 'contained';
$bar_id = $attributes['barId'] ?? 'announce-1';
$mode = $attributes['displayMode'] ?? 'marquee';
$m1 = $attributes['message1'] ?? '';
$m2 = $attributes['message2'] ?? '';
$m3 = $attributes['message3'] ?? '';
$coupon = $attributes['couponCode'] ?? '';
$speed = $attributes['animationSpeed'] ?? 15;
$bg = $attributes['bgColor'] ?? '#0f172a';
$text = $attributes['textColor'] ?? '#ffffff';
$sticky = $attributes['isSticky'] ?? false;
$dismiss = $attributes['enableDismiss'] ?? true;

$unique_id = uniqid('mc-annbar-');
$wrapper_classes = "modern-core-announcement-bar {$unique_id} mode-{$mode}";
if ($sticky) $wrapper_classes .= " is-sticky";

$wrapper_attributes = get_block_wrapper_attributes(['class' => $wrapper_classes]);

// Construct the message sequence
$messages = array_filter([$m1, $m2, $m3]);
$has_coupon = !empty($coupon);
?>

<div <?php echo $wrapper_attributes; ?> id="announce-<?php echo esc_attr($bar_id); ?>">
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            position: relative;
            z-index: 999;
            width: 100%;
            background-color: <?php echo esc_attr($bg); ?>;
            color: <?php echo esc_attr($text); ?>;
            padding: 10px 40px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .<?php echo esc_attr($unique_id); ?>.is-sticky {
            position: sticky;
            top: 0;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-close-btn {
            position: absolute; right: 15px; top: 50%; transform: translateY(-50%);
            background: none; border: none; color: inherit; opacity: 0.6;
            cursor: pointer; padding: 5px; z-index: 10;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-coupon {
            background: rgba(255,255,255,0.15); padding: 4px 10px; border-radius: 4px;
            cursor: pointer; border: 1px dashed rgba(255,255,255,0.4);
            transition: background 0.2s; position: relative;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-coupon:hover { background: rgba(255,255,255,0.25); }
        .<?php echo esc_attr($unique_id); ?> .mc-coupon.copied::after {
            content: 'COPIED!'; position: absolute; top: -25px; left: 50%; transform: translateX(-50%);
            background: #10b981; color: #fff; padding: 2px 6px; border-radius: 3px; font-size: 9px;
        }

        /* Mode: MARQUEE */
        .<?php echo esc_attr($unique_id); ?>.mode-marquee .mc-track-wrapper {
            display: flex; white-space: nowrap; width: 100%;
        }
        .<?php echo esc_attr($unique_id); ?>.mode-marquee .mc-track {
            display: flex; gap: 40px; padding-right: 40px;
            animation: mcMarquee <?php echo esc_attr($speed); ?>s linear infinite;
        }
        .<?php echo esc_attr($unique_id); ?>.mode-marquee:hover .mc-track {
            animation-play-state: paused;
        }
        @keyframes mcMarquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-100%); }
        }

        /* Mode: ROTATE */
        .<?php echo esc_attr($unique_id); ?>.mode-rotate .mc-msg-slide {
            position: absolute; opacity: 0; transition: opacity 0.5s ease;
            display: flex; align-items: center; gap: 15px;
        }
        .<?php echo esc_attr($unique_id); ?>.mode-rotate .mc-msg-slide.is-active {
            position: relative; opacity: 1;
        }

        /* Mode: STATIC */
        .<?php echo esc_attr($unique_id); ?>.mode-static .mc-static-content {
            display: flex; align-items: center; gap: 15px;
        }
    </style>

    <?php if ($mode === 'marquee') : ?>
        <div class="mc-track-wrapper">
            <?php for ($i = 0; $i < 2; $i++) : ?>
                <div class="mc-track">
                    <?php foreach ($messages as $msg) : ?>
                        <span class="mc-msg-item"><?php echo esc_html($msg); ?></span>
                    <?php endforeach; ?>
                    <?php if ($has_coupon) : ?>
                        <span class="mc-coupon" data-code="<?php echo esc_attr($coupon); ?>"><?php echo esc_html($coupon); ?></span>
                    <?php endif; ?>
                </div>
            <?php endfor; ?>
        </div>

    <?php elseif ($mode === 'rotate') : ?>
        <div class="mc-rotator">
            <?php foreach ($messages as $index => $msg) : ?>
                <div class="mc-msg-slide <?php echo $index === 0 ? 'is-active' : ''; ?>">
                    <span><?php echo esc_html($msg); ?></span>
                    <?php if ($index === count($messages) - 1 && $has_coupon) : ?>
                        <span class="mc-coupon" data-code="<?php echo esc_attr($coupon); ?>"><?php echo esc_html($coupon); ?></span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

    <?php else : ?>
        <div class="mc-static-content">
            <span><?php echo esc_html($m1); ?></span>
            <?php if ($has_coupon) : ?>
                <span class="mc-coupon" data-code="<?php echo esc_attr($coupon); ?>"><?php echo esc_html($coupon); ?></span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($dismiss) : ?>
        <button class="mc-close-btn">✕</button>
    <?php endif; ?>

    <script>
        (function() {
            const barId = '<?php echo esc_attr($bar_id); ?>';
            const bar = document.getElementById('announce-' + barId);
            const cookieName = 'mc_announce_' + barId;

            if (document.cookie.split(';').some((item) => item.trim().startsWith(cookieName + '='))) {
                if (bar) bar.style.display = 'none';
                return;
            }

            document.addEventListener('DOMContentLoaded', () => {
                if (!bar) return;

                // Dismiss Logic
                const closeBtn = bar.querySelector('.mc-close-btn');
                if (closeBtn) {
                    closeBtn.addEventListener('click', () => {
                        const d = new Date(); d.setTime(d.getTime() + (3*24*60*60*1000)); // 3 days
                        document.cookie = cookieName + "=true;expires=" + d.toUTCString() + ";path=/";
                        bar.style.display = 'none';
                    });
                }

                // Copy to Clipboard Logic
                const coupons = bar.querySelectorAll('.mc-coupon');
                coupons.forEach(coupon => {
                    coupon.addEventListener('click', () => {
                        navigator.clipboard.writeText(coupon.dataset.code).then(() => {
                            coupon.classList.add('copied');
                            setTimeout(() => coupon.classList.remove('copied'), 2000);
                        });
                    });
                });

                // Rotator Logic
                <?php if ($mode === 'rotate') : ?>
                const slides = bar.querySelectorAll('.mc-msg-slide');
                if (slides.length > 1) {
                    let current = 0;
                    setInterval(() => {
                        slides[current].classList.remove('is-active');
                        current = (current + 1) % slides.length;
                        slides[current].classList.add('is-active');
                    }, <?php echo esc_attr($speed * 1000); ?>);
                }
                <?php endif; ?>
            });
        })();
    </script>
</div>