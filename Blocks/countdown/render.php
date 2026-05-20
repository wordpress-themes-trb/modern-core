<?php
$target_date = $attributes['targetDate'] ?? '';
$style_mode = $attributes['styleMode'] ?? 'boxed';
$digit_size = $attributes['digitSize'] ?? 32;
$digit_color = $attributes['digitColor'] ?? '#ffffff';
$label_color = $attributes['labelColor'] ?? 'rgba(255,255,255,0.6)';
$box_bg = $attributes['boxBg'] ?? 'rgba(255,255,255,0.1)';
$box_radius = $attributes['boxRadius'] ?? 8;
$label_d = $attributes['labelDays'] ?? 'Days';
$label_h = $attributes['labelHours'] ?? 'Hours';
$label_m = $attributes['labelMins'] ?? 'Mins';
$label_s = $attributes['labelSecs'] ?? 'Secs';
$expire_action = $attributes['expireAction'] ?? 'stop';
$expire_msg = $attributes['expireMessage'] ?? 'This offer has expired.';
$expire_url = $attributes['expireUrl'] ?? '';

$unique_id = uniqid('mc-countdown-');
$is_boxed = in_array($style_mode, ['boxed', 'pill']);
$radius = $style_mode === 'pill' ? '50px' : "{$box_radius}px";

$wrapper_attributes = get_block_wrapper_attributes([
    'class' => "modern-core-countdown {$unique_id}",
    'data-target' => esc_attr($target_date),
    'data-action' => esc_attr($expire_action),
    'data-url' => esc_url($expire_url)
]);
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            display: flex;
            align-items: center;
            gap: <?php echo $style_mode === 'minimal' ? '15px' : '10px'; ?>;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-cd-unit {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            <?php if ($is_boxed) : ?>
            background-color: <?php echo esc_attr($box_bg); ?>;
            padding: 15px 20px;
            border-radius: <?php echo esc_attr($radius); ?>;
            min-width: 80px;
            border: 1px solid rgba(255,255,255,0.05);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            <?php endif; ?>
        }
        .<?php echo esc_attr($unique_id); ?> .mc-cd-digit {
            font-size: <?php echo esc_attr($digit_size); ?>px;
            font-weight: 700;
            line-height: 1;
            color: <?php echo esc_attr($digit_color); ?>;
            font-variant-numeric: tabular-nums; /* Prevents jumping widths */
        }
        .<?php echo esc_attr($unique_id); ?> .mc-cd-label {
            font-size: <?php echo esc_attr(max(10, $digit_size * 0.3)); ?>px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 6px;
            color: <?php echo esc_attr($label_color); ?>;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-cd-separator {
            font-size: <?php echo esc_attr($digit_size); ?>px;
            color: <?php echo esc_attr($digit_color); ?>;
            opacity: 0.5;
            line-height: 1;
            padding-bottom: <?php echo esc_attr($digit_size * 0.3); ?>px;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-cd-expired-msg {
            display: none;
            font-size: <?php echo esc_attr($digit_size * 0.6); ?>px;
            font-weight: bold;
            color: <?php echo esc_attr($digit_color); ?>;
            padding: 15px;
            background: <?php echo esc_attr($is_boxed ? $box_bg : 'transparent'); ?>;
            border-radius: <?php echo esc_attr($radius); ?>;
        }
        @media (max-width: 600px) {
            .<?php echo esc_attr($unique_id); ?> { gap: 5px; }
            .<?php echo esc_attr($unique_id); ?> .mc-cd-unit { min-width: 60px; padding: 10px; }
            .<?php echo esc_attr($unique_id); ?> .mc-cd-digit { font-size: <?php echo esc_attr($digit_size * 0.75); ?>px; }
            .<?php echo esc_attr($unique_id); ?> .mc-cd-separator { font-size: <?php echo esc_attr($digit_size * 0.75); ?>px; }
        }
    </style>

    <div class="mc-cd-wrapper" style="display: flex; align-items: center; gap: inherit;">
        <div class="mc-cd-unit"><span class="mc-cd-digit mc-d">00</span><span class="mc-cd-label"><?php echo esc_html($label_d); ?></span></div>
        <?php if ($style_mode === 'minimal') echo '<div class="mc-cd-separator">:</div>'; ?>
        
        <div class="mc-cd-unit"><span class="mc-cd-digit mc-h">00</span><span class="mc-cd-label"><?php echo esc_html($label_h); ?></span></div>
        <?php if ($style_mode === 'minimal') echo '<div class="mc-cd-separator">:</div>'; ?>
        
        <div class="mc-cd-unit"><span class="mc-cd-digit mc-m">00</span><span class="mc-cd-label"><?php echo esc_html($label_m); ?></span></div>
        <?php if ($style_mode === 'minimal') echo '<div class="mc-cd-separator">:</div>'; ?>
        
        <div class="mc-cd-unit"><span class="mc-cd-digit mc-s">00</span><span class="mc-cd-label"><?php echo esc_html($label_s); ?></span></div>
    </div>
    
    <div class="mc-cd-expired-msg"><?php echo esc_html($expire_msg); ?></div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.querySelector('.<?php echo esc_attr($unique_id); ?>');
            if (!container) return;

            const targetStr = container.dataset.target;
            const action = container.dataset.action;
            const url = container.dataset.url;
            
            if (!targetStr) return; // If no date set, stay at 00:00:00:00
            
            // Treat input as local time
            const countDownDate = new Date(targetStr).getTime();
            
            const elD = container.querySelector('.mc-d');
            const elH = container.querySelector('.mc-h');
            const elM = container.querySelector('.mc-m');
            const elS = container.querySelector('.mc-s');
            const wrapper = container.querySelector('.mc-cd-wrapper');
            const msg = container.querySelector('.mc-cd-expired-msg');

            const timer = setInterval(() => {
                const now = new Date().getTime();
                const distance = countDownDate - now;

                if (distance < 0) {
                    clearInterval(timer);
                    elD.innerText = "00"; elH.innerText = "00"; elM.innerText = "00"; elS.innerText = "00";
                    
                    if (action === 'redirect' && url) {
                        window.location.href = url;
                    } else if (action === 'message') {
                        wrapper.style.display = 'none';
                        msg.style.display = 'block';
                    }
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                elD.innerText = days < 10 ? '0' + days : days;
                elH.innerText = hours < 10 ? '0' + hours : hours;
                elM.innerText = minutes < 10 ? '0' + minutes : minutes;
                elS.innerText = seconds < 10 ? '0' + seconds : seconds;
            }, 1000);
        });
    </script>
</div>