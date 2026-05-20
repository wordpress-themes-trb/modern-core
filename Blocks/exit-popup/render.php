<?php
$trigger = $attributes['triggerType'] ?? 'exit';
$mobile_fallback = $attributes['mobileFallback'] ?? 10;
$suppression = $attributes['suppressionDays'] ?? 7;
$headline = $attributes['headline'] ?? 'Wait — before you go!';
$subhead = $attributes['subhead'] ?? 'Take 10% off your first order.';
$offer = $attributes['offerHighlight'] ?? 'USE CODE: SAVE10';
$cta = $attributes['ctaText'] ?? 'Get my discount';
$dismiss = $attributes['dismissText'] ?? 'No thanks, I\'ll pay full price';
$show_email = $attributes['showEmail'] ?? true;
$show_timer = $attributes['showCountdown'] ?? false;
$bg = $attributes['bgColor'] ?? '#0f172a';
$text_c = $attributes['textColor'] ?? '#ffffff';
$accent = $attributes['accentColor'] ?? '#3b82f6';
$opacity = ($attributes['overlayOpacity'] ?? 80) / 100;
$blur = $attributes['overlayBlur'] ?? 4;
$anim = $attributes['animStyle'] ?? 'scale';

$unique_id = uniqid('mc-exit-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "modern-core-exit-popup {$unique_id} anim-{$anim}"]);
?>

<div <?php echo $wrapper_attributes; ?> 
    data-suppress="<?php echo esc_attr($suppression); ?>"
    data-trigger="<?php echo esc_attr($trigger); ?>"
    data-mobile="<?php echo esc_attr($mobile_fallback); ?>">
    
    <style>
        /* Overlay Mechanics */
        .<?php echo esc_attr($unique_id); ?> {
            position: fixed; inset: 0; z-index: 999999;
            background: rgba(0,0,0, <?php echo esc_attr($opacity); ?>);
            backdrop-filter: blur(<?php echo esc_attr($blur); ?>px);
            -webkit-backdrop-filter: blur(<?php echo esc_attr($blur); ?>px);
            display: flex; align-items: center; justify-content: center; padding: 20px;
            
            /* Hidden by default */
            opacity: 0; visibility: hidden; pointer-events: none;
            transition: opacity 0.4s ease, visibility 0.4s;
        }

        .<?php echo esc_attr($unique_id); ?>.is-active {
            opacity: 1; visibility: visible; pointer-events: auto;
        }

        /* Modal Card */
        .<?php echo esc_attr($unique_id); ?> .mc-exit-modal {
            background: <?php echo esc_attr($bg); ?>; color: <?php echo esc_attr($text_c); ?>;
            padding: 50px 40px; border-radius: 16px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
            width: 100%; max-width: 480px; text-align: center; position: relative;
            
            /* Entrance Animations */
            transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.5s;
        }

        /* Animation States */
        .<?php echo esc_attr($unique_id); ?>.anim-scale .mc-exit-modal { transform: scale(0.9); opacity: 0; }
        .<?php echo esc_attr($unique_id); ?>.anim-slide .mc-exit-modal { transform: translateY(-40px); opacity: 0; }
        .<?php echo esc_attr($unique_id); ?>.anim-fade .mc-exit-modal { opacity: 0; }
        
        .<?php echo esc_attr($unique_id); ?>.is-active .mc-exit-modal { transform: scale(1) translateY(0); opacity: 1; }

        /* Internal UI */
        .<?php echo esc_attr($unique_id); ?> .mc-exit-close {
            position: absolute; top: 15px; right: 20px; font-size: 24px; line-height: 1;
            opacity: 0.5; cursor: pointer; border: none; background: none; color: inherit; transition: opacity 0.2s;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-exit-close:hover { opacity: 1; }

        .<?php echo esc_attr($unique_id); ?> .mc-exit-hl { font-size: clamp(24px, 4vw, 32px); font-weight: 900; margin: 0 0 10px 0; line-height: 1.1; }
        .<?php echo esc_attr($unique_id); ?> .mc-exit-sub { font-size: 16px; opacity: 0.8; margin: 0 0 25px 0; line-height: 1.5; }
        
        .<?php echo esc_attr($unique_id); ?> .mc-exit-offer {
            background: rgba(255,255,255,0.1); padding: 12px; border-radius: 8px; font-weight: bold;
            letter-spacing: 1px; margin-bottom: 25px; border: 1px dashed rgba(255,255,255,0.2);
        }

        .<?php echo esc_attr($unique_id); ?> .mc-exit-input {
            width: 100%; padding: 15px; border-radius: 8px; border: 1px solid rgba(0,0,0,0.1);
            margin-bottom: 15px; font-size: 16px; outline: none; box-sizing: border-box; background: #fff; color: #000;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-exit-btn {
            width: 100%; padding: 16px; border-radius: 8px; border: none; background: <?php echo esc_attr($accent); ?>;
            color: #fff; font-size: 16px; font-weight: bold; cursor: pointer; margin-bottom: 15px; transition: opacity 0.2s;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-exit-btn:hover { opacity: 0.9; }

        .<?php echo esc_attr($unique_id); ?> .mc-exit-dismiss {
            font-size: 13px; opacity: 0.5; text-decoration: underline; cursor: pointer; display: inline-block; transition: opacity 0.2s;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-exit-dismiss:hover { opacity: 1; }

        .<?php echo esc_attr($unique_id); ?> .mc-exit-timer {
            display: flex; justify-content: center; gap: 15px; margin-bottom: 25px; font-size: 20px; font-weight: bold;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-exit-timer span { font-size: 11px; display: block; opacity: 0.6; font-weight: normal; }
    </style>

    <div class="mc-exit-modal">
        <button class="mc-exit-close" aria-label="Close">✕</button>
        
        <h2 class="mc-exit-hl"><?php echo esc_html($headline); ?></h2>
        <?php if ($subhead) echo '<p class="mc-exit-sub">' . esc_html($subhead) . '</p>'; ?>

        <?php if ($offer) : ?>
            <div class="mc-exit-offer"><?php echo esc_html($offer); ?></div>
        <?php endif; ?>

        <?php if ($show_timer) : ?>
            <div class="mc-exit-timer">
                <div>04<span>MIN</span></div>:<div>59<span>SEC</span></div>
            </div>
        <?php endif; ?>

        <form class="mc-exit-form">
            <?php if ($show_email) : ?>
                <input type="email" class="mc-exit-input" placeholder="Enter your email address" required />
            <?php endif; ?>
            <button type="button" class="mc-exit-btn mc-exit-close-action"><?php echo esc_html($cta); ?></button>
        </form>

        <?php if ($dismiss) : ?>
            <div class="mc-exit-dismiss mc-exit-close-action"><?php echo esc_html($dismiss); ?></div>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const wrapper = document.querySelector('.<?php echo esc_attr($unique_id); ?>');
            if (!wrapper) return;

            const suppDays = parseInt(wrapper.dataset.suppress) || 7;
            const trigger = wrapper.dataset.trigger;
            const mobileDelay = parseInt(wrapper.dataset.mobile) || 10;
            
            const closeBtns = wrapper.querySelectorAll('.mc-exit-close, .mc-exit-close-action');
            const storageKey = 'mc_exit_suppressed';

            // 1. Check Suppression
            const checkSuppression = () => {
                const suppressedUntil = localStorage.getItem(storageKey);
                if (suppressedUntil && new Date().getTime() < parseInt(suppressedUntil)) {
                    return true;
                }
                return false;
            };

            if (checkSuppression()) return; // Abort if suppressed

            // 2. Actions
            const showModal = () => {
                if (!wrapper.classList.contains('is-active')) {
                    wrapper.classList.add('is-active');
                }
            };

            const hideAndSuppress = () => {
                wrapper.classList.remove('is-active');
                // Calculate future date in ms
                const futureDate = new Date().getTime() + (suppDays * 24 * 60 * 60 * 1000);
                localStorage.setItem(storageKey, futureDate);
            };

            // Bind Close Buttons
            closeBtns.forEach(btn => btn.addEventListener('click', (e) => {
                e.preventDefault();
                hideAndSuppress();
            }));

            // Close on overlay click
            wrapper.addEventListener('click', (e) => {
                if (e.target === wrapper) hideAndSuppress();
            });

            // 3. Trigger Logic
            let triggered = false;

            const isTouchDevice = () => {
                return (('ontouchstart' in window) || (navigator.maxTouchPoints > 0) || (navigator.msMaxTouchPoints > 0));
            };

            if (trigger === 'exit' && !isTouchDevice()) {
                // Desktop Exit Intent
                document.addEventListener('mouseleave', (e) => {
                    if (e.clientY <= 5 && !triggered && !checkSuppression()) {
                        triggered = true;
                        showModal();
                    }
                });
            } else {
                // Time Delay (or mobile fallback for exit intent)
                const delay = trigger === 'time' ? mobileDelay * 1000 : mobileDelay * 1000;
                setTimeout(() => {
                    if (!triggered && !checkSuppression()) {
                        triggered = true;
                        showModal();
                    }
                }, delay);
            }
        });
    </script>
</div>