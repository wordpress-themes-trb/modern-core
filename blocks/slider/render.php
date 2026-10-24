<?php
$min_height = $attributes['minHeight'] ?? 80;
$transition = $attributes['transition'] ?? 'fade';
$autoplay = $attributes['autoplay'] ?? true;
$delay = $attributes['delay'] ?? 5000;
$show_arrows = $attributes['showArrows'] ?? true;
$show_dots = $attributes['showDots'] ?? true;
$slides = $attributes['slides'] ?? [];

if (empty($slides)) return;

$unique_id = uniqid('mc-slider-');
$wrapper_attributes = get_block_wrapper_attributes([
    'class' => "mc-block modern-core-slider-engine {$unique_id} mode-{$transition}",
    'data-autoplay' => $autoplay ? 'true' : 'false',
    'data-delay' => esc_attr($delay)
]);
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            position: relative;
            width: 100%;
            height: <?php echo esc_attr($min_height); ?>vh;
            overflow: hidden;
            background: #000;
            box-sizing: border-box;
        }

        .<?php echo esc_attr($unique_id); ?> *,
        .<?php echo esc_attr($unique_id); ?> *::before,
        .<?php echo esc_attr($unique_id); ?> *::after {
            box-sizing: border-box !important;
        }
        
        .<?php echo esc_attr($unique_id); ?> .mc-slider-track {
            display: flex;
            width: 100%;
            height: 100%;
            transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1);
        }

        .<?php echo esc_attr($unique_id); ?> .modern-core-slide {
            flex: 0 0 100% !important;
            width: 100% !important;
            min-width: 100% !important;
            height: 100%;
            flex-shrink: 0 !important; 
            position: relative;
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 80px 5%;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-slide-overlay {
            position: absolute; inset: 0; background: #000; z-index: 1;
        }
        
        .<?php echo esc_attr($unique_id); ?> .mc-slide-content {
            position: relative; z-index: 2; width: 100%; max-width: 1200px; margin: 0 auto;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-subhead { font-size: 12px; letter-spacing: 2px; text-transform: uppercase; color: #fff; margin: 0 0 15px 0; }
        .<?php echo esc_attr($unique_id); ?> .mc-heading { font-size: clamp(36px, 5vw, 64px); font-weight: 800; line-height: 1.1; color: #fff; margin: 0 0 30px 0; }
        .<?php echo esc_attr($unique_id); ?> .mc-btn { display: inline-block; padding: 12px 30px; background: #fff; color: #000; font-weight: bold; font-size: 14px; text-decoration: none; transition: opacity 0.2s; }
        .<?php echo esc_attr($unique_id); ?> .mc-btn:hover { opacity: 0.8; }

        /* FADE MODE OVERRIDES */
        .<?php echo esc_attr($unique_id); ?>.mode-fade .modern-core-slide {
            position: absolute; top: 0; left: 0;
            opacity: 0; transition: opacity 0.8s ease-in-out;
            pointer-events: none;
        }
        .<?php echo esc_attr($unique_id); ?>.mode-fade .modern-core-slide.is-active {
            opacity: 1; pointer-events: auto; z-index: 2;
        }

        /* UI Controls */
        .<?php echo esc_attr($unique_id); ?> .mc-arrow {
            position: absolute; top: 50%; transform: translateY(-50%); z-index: 10;
            background: rgba(0,0,0,0.3); color: #fff; border: 1px solid rgba(255,255,255,0.2);
            width: 50px; height: 50px; border-radius: 50%; cursor: pointer;
            display: flex; align-items: center; justify-content: center; backdrop-filter: blur(5px);
            transition: background 0.3s ease;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-arrow:hover { background: rgba(0,0,0,0.8); }
        .<?php echo esc_attr($unique_id); ?> .mc-prev { left: 20px; }
        .<?php echo esc_attr($unique_id); ?> .mc-next { right: 20px; }

        .<?php echo esc_attr($unique_id); ?> .mc-dots {
            position: absolute; bottom: 30px; left: 0; right: 0; z-index: 10;
            display: flex; justify-content: center; gap: 10px;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-dot {
            width: 10px; height: 10px; border-radius: 50%; background: rgba(255,255,255,0.3);
            cursor: pointer; transition: background 0.3s ease, transform 0.3s ease;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-dot.is-active { background: #fff; transform: scale(1.3); }
    </style>

    <div class="mc-slider-track">
        <?php foreach ($slides as $index => $slide) : 
            $opacity = ($slide['opacity'] ?? 50) / 100;
            $bg_color = esc_attr($slide['bgColor'] ?? '#000000');
            $bg_image = !empty($slide['bgImage']) ? "background-image: url('" . esc_url($slide['bgImage']) . "');" : "";
        ?>
            <div class="modern-core-slide <?php echo $index === 0 ? 'is-active' : ''; ?>" style="background-color: <?php echo $bg_color; ?>; <?php echo $bg_image; ?>">
                <div class="mc-slide-overlay" style="opacity: <?php echo esc_attr($opacity); ?>;"></div>
                <div class="mc-slide-content">
                    <?php if (!empty($slide['subhead'])) : ?>
                        <p class="mc-subhead"><?php echo esc_html($slide['subhead']); ?></p>
                    <?php endif; ?>
                    
                    <?php if (!empty($slide['heading'])) : ?>
                        <h2 class="mc-heading"><?php echo esc_html($slide['heading']); ?></h2>
                    <?php endif; ?>
                    
                    <?php if (!empty($slide['btnText'])) : ?>
                        <a href="#" class="mc-btn"><?php echo esc_html($slide['btnText']); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ($show_arrows && count($slides) > 1) : ?>
        <button class="mc-arrow mc-prev" aria-label="Previous Slide">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>
        </button>
        <button class="mc-arrow mc-next" aria-label="Next Slide">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </button>
    <?php endif; ?>

    <?php if ($show_dots && count($slides) > 1) : ?>
        <div class="mc-dots"></div>
    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const slider = document.querySelector('.<?php echo esc_attr($unique_id); ?>');
            if (!slider) return;

            const track = slider.querySelector('.mc-slider-track');
            const slides = Array.from(slider.querySelectorAll('.modern-core-slide'));
            if (slides.length <= 1) return;

            const prevBtn = slider.querySelector('.mc-prev');
            const nextBtn = slider.querySelector('.mc-next');
            const dotsContainer = slider.querySelector('.mc-dots');
            
            const isFade = slider.classList.contains('mode-fade');
            const auto = slider.dataset.autoplay === 'true';
            const delay = parseInt(slider.dataset.delay) || 5000;
            
            let currentIndex = 0;
            let timer = null;

            if (dotsContainer) {
                slides.forEach((_, i) => {
                    const dot = document.createElement('div');
                    dot.classList.add('mc-dot');
                    if (i === 0) dot.classList.add('is-active');
                    dot.addEventListener('click', () => goToSlide(i));
                    dotsContainer.appendChild(dot);
                });
            }

            const updateDots = () => {
                if (!dotsContainer) return;
                Array.from(dotsContainer.children).forEach((dot, i) => {
                    dot.classList.toggle('is-active', i === currentIndex);
                });
            };

            const goToSlide = (index) => {
                if (index < 0) index = slides.length - 1;
                if (index >= slides.length) index = 0;
                currentIndex = index;

                if (isFade) {
                    slides.forEach((slide, i) => slide.classList.toggle('is-active', i === currentIndex));
                } else {
                    track.style.transform = `translateX(-${currentIndex * 100}%)`;
                }
                updateDots();
                resetAutoplay();
            };

            if (prevBtn) prevBtn.addEventListener('click', () => goToSlide(currentIndex - 1));
            if (nextBtn) nextBtn.addEventListener('click', () => goToSlide(currentIndex + 1));

            let touchStartX = 0;
            slider.addEventListener('touchstart', e => {
                touchStartX = e.touches[0].clientX;
            }, { passive: true });
            
            slider.addEventListener('touchend', e => {
                const touchEndX = e.changedTouches[0].clientX;
                if (touchStartX - touchEndX > 50) goToSlide(currentIndex + 1); 
                if (touchEndX - touchStartX > 50) goToSlide(currentIndex - 1); 
            }, { passive: true });

            const startAutoplay = () => {
                if (auto) {
                    timer = setInterval(() => goToSlide(currentIndex + 1), delay);
                }
            };
            const resetAutoplay = () => {
                clearInterval(timer);
                startAutoplay();
            };

            slider.addEventListener('mouseenter', () => clearInterval(timer));
            slider.addEventListener('mouseleave', startAutoplay);
            startAutoplay();
        });
    </script>
</div>