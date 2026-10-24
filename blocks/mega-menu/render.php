<?php
$width_mode = $attributes['widthMode'] ?? 'contained';
$label = $attributes['menuLabel'] ?? 'Shop Categories';
$link = $attributes['menuLink'] ?? '#';
$width = $attributes['panelWidth'] ?? 'full';
$anim = $attributes['animStyle'] ?? 'slide';
$open_delay = $attributes['openDelay'] ?? 150;
$close_delay = $attributes['closeDelay'] ?? 250;
$bg = $attributes['panelBg'] ?? '#ffffff';
$radius = $attributes['panelRadius'] ?? 0;
$padding = $attributes['padding'] ?? '40px';
$shadow = ($attributes['shadowOpacity'] ?? 10) / 100;

$unique_id = uniqid('mc-mega-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "mc-block width-" . esc_attr($width_mode) . " modern-core-mega-menu {$unique_id}"]);
?>

<div <?php echo $wrapper_attributes; ?> data-open="<?php echo esc_attr($open_delay); ?>" data-close="<?php echo esc_attr($close_delay); ?>">
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            position: relative;
            display: inline-block;
            font-family: inherit;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-mega-trigger {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 10px 15px; text-decoration: none; color: inherit; font-weight: 600;
            transition: opacity 0.2s ease;
        }
        .<?php echo esc_attr($unique_id); ?>:hover .mc-mega-trigger,
        .<?php echo esc_attr($unique_id); ?>.is-active .mc-mega-trigger {
            opacity: 0.7;
        }

        /* The Hidden Panel */
        .<?php echo esc_attr($unique_id); ?> .mc-mega-panel {
            position: absolute; top: 100%; z-index: 9999;
            background: <?php echo esc_attr($bg); ?>; padding: <?php echo esc_attr($padding); ?>;
            border-radius: 0 0 <?php echo esc_attr($radius); ?>px <?php echo esc_attr($radius); ?>px;
            box-shadow: 0 20px 40px rgba(0,0,0,<?php echo esc_attr($shadow); ?>);
            opacity: 0; visibility: hidden; pointer-events: none;
            transition: opacity 0.3s ease, transform 0.3s ease, visibility 0.3s;
        }

        /* Width Logic */
        <?php if ($width === 'full') : ?>
            .<?php echo esc_attr($unique_id); ?> .mc-mega-panel {
                position: fixed; left: 0; width: 100vw; max-width: 100vw;
                border-top: 1px solid rgba(0,0,0,0.05);
            }
        <?php elseif ($width === 'container') : ?>
            /* Assuming a standard 1200px max-width container, centers using calc */
            .<?php echo esc_attr($unique_id); ?> .mc-mega-panel {
                left: 50%; transform: translateX(-50%) translateY(15px); /* Pre-animation transform */
                width: 100vw; max-width: 1200px; border-top: 1px solid rgba(0,0,0,0.05);
            }
        <?php else : ?>
            /* Nav Width */
            .<?php echo esc_attr($unique_id); ?> .mc-mega-panel {
                left: 0; min-width: 600px;
            }
        <?php endif; ?>

        /* Animation Active States */
        .<?php echo esc_attr($unique_id); ?>.is-active .mc-mega-panel {
            opacity: 1; visibility: visible; pointer-events: auto;
        }

        <?php if ($anim === 'slide') : ?>
            <?php if ($width === 'container') : ?>
                .<?php echo esc_attr($unique_id); ?>.is-active .mc-mega-panel { transform: translateX(-50%) translateY(0); }
            <?php else : ?>
                .<?php echo esc_attr($unique_id); ?> .mc-mega-panel { transform: translateY(15px); }
                .<?php echo esc_attr($unique_id); ?>.is-active .mc-mega-panel { transform: translateY(0); }
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($anim === 'reveal') : ?>
            .<?php echo esc_attr($unique_id); ?> .mc-mega-panel {
                clip-path: inset(0 0 100% 0); transition: clip-path 0.4s cubic-bezier(0.165, 0.84, 0.44, 1), opacity 0.3s;
            }
            .<?php echo esc_attr($unique_id); ?>.is-active .mc-mega-panel {
                clip-path: inset(0 0 0 0);
            }
        <?php endif; ?>

        /* Core Formatting inside Panel */
        .<?php echo esc_attr($unique_id); ?> .mc-mega-panel a { text-decoration: none; color: inherit; display: block; margin-bottom: 8px; transition: opacity 0.2s; }
        .<?php echo esc_attr($unique_id); ?> .mc-mega-panel a:hover { opacity: 0.6; }

        @media (max-width: 900px) {
            .<?php echo esc_attr($unique_id); ?> .mc-mega-panel {
                position: static; width: 100%; box-shadow: none; padding: 20px;
                transform: none !important; clip-path: none !important;
                display: none;
            }
            .<?php echo esc_attr($unique_id); ?>.is-active .mc-mega-panel { display: block; }
        }
    </style>

    <a href="<?php echo esc_url($link); ?>" class="mc-mega-trigger">
        <?php echo esc_html($label); ?>
        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
    </a>

    <div class="mc-mega-panel">
        <?php echo $content; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const wrapper = document.querySelector('.<?php echo esc_attr($unique_id); ?>');
            if (!wrapper) return;

            const openDelay = parseInt(wrapper.dataset.open);
            const closeDelay = parseInt(wrapper.dataset.close);
            let timer = null;

            // Desktop Hover Logic
            if (window.innerWidth > 900) {
                wrapper.addEventListener('mouseenter', () => {
                    clearTimeout(timer);
                    timer = setTimeout(() => wrapper.classList.add('is-active'), openDelay);
                });

                wrapper.addEventListener('mouseleave', () => {
                    clearTimeout(timer);
                    timer = setTimeout(() => wrapper.classList.remove('is-active'), closeDelay);
                });
            } else {
                // Mobile Click Logic
                const trigger = wrapper.querySelector('.mc-mega-trigger');
                trigger.addEventListener('click', (e) => {
                    if (trigger.getAttribute('href') === '#') e.preventDefault();
                    wrapper.classList.toggle('is-active');
                });
            }
        });
    </script>
</div>