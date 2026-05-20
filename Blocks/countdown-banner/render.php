<?php
$target = $attributes['targetDate'] ?? '';
$layout = $attributes['layoutMode'] ?? 'card';
$style = $attributes['timerStyle'] ?? 'boxed';
$bg_mode = $attributes['bgMode'] ?? 'gradient';
$c1 = $attributes['color1'] ?? '#dc2626';
$c2 = $attributes['color2'] ?? '#7f1d1d';
$digit_color = $attributes['digitColor'] ?? '#ffffff';
$box_color = $attributes['boxColor'] ?? 'rgba(0,0,0,0.5)';
$action = $attributes['expireAction'] ?? 'hide';
$msg = $attributes['expireMessage'] ?? 'This flash sale has ended.';
$url = $attributes['expireUrl'] ?? '';
$show_days = $attributes['showDays'] ?? true;

$unique_id = uniqid('mc-cdbanner-');
$wrapper_attributes = get_block_wrapper_attributes([
    'class' => "modern-core-countdown-banner {$unique_id} layout-{$layout} style-{$style}",
    'data-target' => esc_attr($target),
    'data-action' => esc_attr($action),
    'data-url' => esc_url($url)
]);

$bg_css = $bg_mode === 'gradient' ? "linear-gradient(135deg, {$c1}, {$c2})" : $c1;
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            background: <?php echo $bg_css; ?>;
            color: #fff;
            padding: <?php echo $layout === 'strip' ? '20px 5%' : '60px 5%'; ?>;
            display: flex;
            align-items: center;
            justify-content: <?php echo $layout === 'split' ? 'space-between' : 'center'; ?>;
            gap: 40px;
            overflow: hidden;
            transition: opacity 0.5s ease, height 0.5s ease;
        }

        /* Layout Overrides */
        <?php if ($layout === 'card') : ?>
            .<?php echo esc_attr($unique_id); ?> {
                flex-direction: column;
                max-width: 1000px;
                margin: 40px auto;
                border-radius: 16px;
                box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
            }
        <?php elseif ($layout === 'split') : ?>
            .<?php echo esc_attr($unique_id); ?> {
                flex-direction: row;
            }
        <?php else : ?>
            .<?php echo esc_attr($unique_id); ?> {
                flex-direction: column;
                width: 100%;
            }
        <?php endif; ?>

        /* Zones */
        .<?php echo esc_attr($unique_id); ?> .mc-cdb-content {
            flex: <?php echo $layout === 'split' ? '1' : 'none'; ?>;
            width: 100%;
            max-width: 600px;
            text-align: <?php echo $layout === 'split' ? 'left' : 'center'; ?>;
            z-index: 2;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-cdb-timer-zone {
            flex: <?php echo $layout === 'split' ? '1' : 'none'; ?>;
            display: flex;
            justify-content: <?php echo $layout === 'split' ? 'flex-end' : 'center'; ?>;
            z-index: 2;
        }

        /* Timer UI */
        .<?php echo esc_attr($unique_id); ?> .mc-timer-wrap {
            display: flex;
            gap: 15px;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-unit {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            min-width: 80px; position: relative;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-digit {
            font-size: 42px; font-weight: 800; line-height: 1;
            color: <?php echo esc_attr($digit_color); ?>;
            font-variant-numeric: tabular-nums;
            position: relative; z-index: 2;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-label {
            font-size: 11px; text-transform: uppercase; letter-spacing: 1px;
            margin-top: 5px; opacity: 0.8;
        }

        /* STYLE: Boxed & Flip */
        <?php if ($style === 'boxed' || $style === 'flip') : ?>
            .<?php echo esc_attr($unique_id); ?> .mc-unit {
                background: <?php echo esc_attr($box_color); ?>;
                padding: 15px 20px;
                border-radius: 8px;
            }
        <?php endif; ?>

        /* STYLE: Flip Specifics (Hardware line simulation) */
        <?php if ($style === 'flip') : ?>
            .<?php echo esc_attr($unique_id); ?> .mc-unit {
                border: 1px solid rgba(255,255,255,0.1);
                box-shadow: inset 0 2px 0 rgba(255,255,255,0.1), 0 5px 15px rgba(0,0,0,0.3);
            }
            .<?php echo esc_attr($unique_id); ?> .mc-unit::after {
                content: ''; position: absolute; top: 50%; left: 0; right: 0; height: 1px;
                background: rgba(0,0,0,0.6); z-index: 3;
            }
        <?php endif; ?>

        /* STYLE: Neon Specifics */
        <?php if ($style === 'neon') : ?>
            .<?php echo esc_attr($unique_id); ?> .mc-digit {
                text-shadow: 0 0 10px <?php echo esc_attr($digit_color); ?>, 0 0 20px <?php echo esc_attr($digit_color); ?>, 0 0 40px <?php echo esc_attr($digit_color); ?>;
            }
        <?php endif; ?>

        /* Expiration Message */
        .<?php echo esc_attr($unique_id); ?> .mc-cdb-msg {
            display: none; font-size: 24px; font-weight: bold; width: 100%; text-align: center;
        }

        @media (max-width: 768px) {
            .<?php echo esc_attr($unique_id); ?> { flex-direction: column; text-align: center; padding: 40px 5%; }
            .<?php echo esc_attr($unique_id); ?> .mc-cdb-content, .<?php echo esc_attr($unique_id); ?> .mc-cdb-timer-zone { justify-content: center; text-align: center; }
            .<?php echo esc_attr($unique_id); ?> .mc-digit { font-size: 32px; }
            .<?php echo esc_attr($unique_id); ?> .mc-unit { min-width: 60px; padding: 10px; }
        }
    </style>

    <div class="mc-cdb-content">
        <?php echo $content; ?>
    </div>

    <div class="mc-cdb-timer-zone">
        <div class="mc-timer-wrap">
            <?php if ($show_days) : ?>
                <div class="mc-unit"><span class="mc-digit mc-d">00</span><span class="mc-label">Days</span></div>
            <?php endif; ?>
            <div class="mc-unit"><span class="mc-digit mc-h">00</span><span class="mc-label">Hours</span></div>
            <div class="mc-unit"><span class="mc-digit mc-m">00</span><span class="mc-label">Mins</span></div>
            <div class="mc-unit"><span class="mc-digit mc-s">00</span><span class="mc-label">Secs</span></div>
        </div>
        <div class="mc-cdb-msg"><?php echo esc_html($msg); ?></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const banner = document.querySelector('.<?php echo esc_attr($unique_id); ?>');
            if (!banner) return;

            const targetStr = banner.dataset.target;
            const action = banner.dataset.action;
            const url = banner.dataset.url;
            
            if (!targetStr) return;
            
            const countDownDate = new Date(targetStr).getTime();
            
            const elD = banner.querySelector('.mc-d');
            const elH = banner.querySelector('.mc-h');
            const elM = banner.querySelector('.mc-m');
            const elS = banner.querySelector('.mc-s');
            const wrap = banner.querySelector('.mc-timer-wrap');
            const msgEl = banner.querySelector('.mc-cdb-msg');
            const contentZone = banner.querySelector('.mc-cdb-content');

            const timer = setInterval(() => {
                const now = new Date().getTime();
                const distance = countDownDate - now;

                if (distance < 0) {
                    clearInterval(timer);
                    if(elD) elD.innerText = "00"; elH.innerText = "00"; elM.innerText = "00"; elS.innerText = "00";
                    
                    if (action === 'redirect' && url) {
                        window.location.href = url;
                    } else if (action === 'message') {
                        wrap.style.display = 'none';
                        contentZone.style.display = 'none';
                        msgEl.style.display = 'block';
                    } else if (action === 'hide') {
                        banner.style.opacity = '0';
                        setTimeout(() => banner.style.display = 'none', 500);
                    }
                    return;
                }

                const d = Math.floor(distance / (1000 * 60 * 60 * 24));
                const h = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const s = Math.floor((distance % (1000 * 60)) / 1000);

                if(elD) elD.innerText = d < 10 ? '0' + d : d;
                elH.innerText = h < 10 ? '0' + h : h;
                elM.innerText = m < 10 ? '0' + m : m;
                elS.innerText = s < 10 ? '0' + s : s;
            }, 1000);
        });
    </script>
</div>