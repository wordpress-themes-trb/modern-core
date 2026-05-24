<?php
$width_mode = $attributes['widthMode'] ?? 'contained';
$banner_id = $attributes['bannerId'] ?? 'promo-1';
$layout_mode = $attributes['layoutMode'] ?? 'full';
$bg_mode = $attributes['bgMode'] ?? 'animated-gradient';
$c1 = $attributes['color1'] ?? '#3b82f6';
$c2 = $attributes['color2'] ?? '#8b5cf6';
$text_color = $attributes['textColor'] ?? '#ffffff';
$is_sticky = $attributes['isSticky'] ?? false;
$pulse = $attributes['attentionPulse'] ?? false;
$enable_dismiss = $attributes['enableDismiss'] ?? true;

$unique_id = uniqid('mc-promo-');
$wrapper_classes = "modern-core-promo-banner {$unique_id}";
if ($is_sticky) $wrapper_classes .= " is-sticky";

$wrapper_attributes = get_block_wrapper_attributes(['class' => $wrapper_classes]);
?>

<div <?php echo $wrapper_attributes; ?> id="banner-<?php echo esc_attr($banner_id); ?>">
    
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            position: relative;
            z-index: 998; /* Just below header */
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px 40px 12px 20px; /* Extra right padding for close button */
            color: <?php echo esc_attr($text_color); ?>;
            overflow: hidden;
            animation: mcSlideDownPromo 0.6s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
            
            /* Layout Modes */
            <?php if ($layout_mode === 'full') : ?>
                width: 100%; margin: 0;
            <?php elseif ($layout_mode === 'contained') : ?>
                width: 90%; max-width: 1200px; margin: 20px auto; border-radius: 8px;
            <?php elseif ($layout_mode === 'floating') : ?>
                width: 90%; max-width: 1200px; margin: 20px auto; border-radius: 16px;
                box-shadow: 0 15px 35px rgba(0,0,0,0.15); transform: translateY(-5px);
            <?php endif; ?>

            /* Background Modes */
            <?php if ($bg_mode === 'solid') : ?>
                background: <?php echo esc_attr($c1); ?>;
            <?php elseif ($bg_mode === 'gradient') : ?>
                background: linear-gradient(90deg, <?php echo esc_attr($c1); ?>, <?php echo esc_attr($c2); ?>);
            <?php elseif ($bg_mode === 'animated-gradient') : ?>
                background: linear-gradient(270deg, <?php echo esc_attr($c1); ?>, <?php echo esc_attr($c2); ?>, <?php echo esc_attr($c1); ?>);
                background-size: 200% 200%;
                animation: mcSlideDownPromo 0.6s cubic-bezier(0.165, 0.84, 0.44, 1) forwards, mcGradientShift 8s ease infinite;
            <?php endif; ?>
        }

        .<?php echo esc_attr($unique_id); ?>.is-sticky {
            position: sticky;
            top: 0;
            /* If the header is also sticky, you would adjust this via JS, but this sets the native behavior */
        }

        /* Enforce inline layout for the InnerBlocks content */
        .<?php echo esc_attr($unique_id); ?> .wp-block-group {
            display: flex; align-items: center; justify-content: center; flex-wrap: wrap; gap: 15px; margin: 0;
        }
        .<?php echo esc_attr($unique_id); ?> p { margin: 0; line-height: 1.2; }

        .<?php echo esc_attr($unique_id); ?> .mc-promo-close {
            position: absolute; right: 15px; top: 50%; transform: translateY(-50%);
            background: none; border: none; color: inherit; opacity: 0.6;
            cursor: pointer; padding: 5px; transition: opacity 0.2s ease;
            display: flex; align-items: center; justify-content: center;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-promo-close:hover { opacity: 1; }

        @keyframes mcSlideDownPromo {
            0% { transform: translateY(-100%); opacity: 0; }
            100% { transform: translateY(<?php echo $layout_mode === 'floating' ? '-5px' : '0'; ?>); opacity: 1; }
        }

        @keyframes mcGradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        <?php if ($pulse) : ?>
        .<?php echo esc_attr($unique_id); ?> .wp-block-group {
            animation: mcPromoPulse 3s infinite ease-in-out;
        }
        @keyframes mcPromoPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
        }
        @media (prefers-reduced-motion: reduce) {
            .<?php echo esc_attr($unique_id); ?> .wp-block-group { animation: none; }
        }
        <?php endif; ?>
    </style>

    <?php echo $content; ?>

    <?php if ($enable_dismiss) : ?>
        <button class="mc-promo-close" aria-label="Dismiss message">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
    <?php endif; ?>

    <script>
        (function() {
            const bannerId = '<?php echo esc_attr($banner_id); ?>';
            const bannerElement = document.getElementById('banner-' + bannerId);
            const cookieName = 'mc_dismiss_' + bannerId;

            // Immediately check cookie so we don't cause layout shift
            if (document.cookie.split(';').some((item) => item.trim().startsWith(cookieName + '='))) {
                if (bannerElement) {
                    bannerElement.style.display = 'none';
                    return; // Stop execution
                }
            }

            // Bind click event if banner exists and isn't hidden
            document.addEventListener('DOMContentLoaded', () => {
                const btn = bannerElement?.querySelector('.mc-promo-close');
                if (btn) {
                    btn.addEventListener('click', () => {
                        // Set cookie for 7 days
                        const d = new Date();
                        d.setTime(d.getTime() + (7*24*60*60*1000));
                        document.cookie = cookieName + "=true;expires=" + d.toUTCString() + ";path=/";
                        
                        // Slide up and remove
                        bannerElement.style.transition = 'margin-top 0.4s ease, opacity 0.4s ease';
                        bannerElement.style.marginTop = `-${bannerElement.offsetHeight}px`;
                        bannerElement.style.opacity = '0';
                        setTimeout(() => bannerElement.style.display = 'none', 400);
                    });
                }
            });
        })();
    </script>
</div>