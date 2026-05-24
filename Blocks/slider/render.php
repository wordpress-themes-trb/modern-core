<?php
$width_mode = $attributes['widthMode'] ?? 'contained';
$min_height = $attributes['minHeight'] ?? 80;
$transition = $attributes['transition'] ?? 'fade';
$autoplay = $attributes['autoplay'] ?? true;
$delay = $attributes['delay'] ?? 5000;
$show_arrows = $attributes['showArrows'] ?? true;
$show_dots = $attributes['showDots'] ?? true;

$unique_id = uniqid('mc-slider-');
$wrapper_attributes = get_block_wrapper_attributes([
    'class' => "mc-block width-" . esc_attr($width_mode) . " modern-core-slider-engine {$unique_id} mode-{$transition}",
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
        }
        
        .<?php echo esc_attr($unique_id); ?> .mc-slider-track {
            display: flex;
            width: 100%;
            height: 100%;
            transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1); /* Slide easing */
        }

        /* Slide Child Styling */
        .<?php echo esc_attr($unique_id); ?> .mc-slide {
            flex: 0 0 100%;
            height: 100%;
            position: relative;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            padding: 5% 8%;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-slide-overlay {
            position: absolute; inset: 0; background: #000; z-index: 1;
        }
        
        .<?php echo esc_attr($unique_id); ?> .mc-slide-content {
            position: relative; z-index: 2; width: 100%; max-width: 800px;
        }

        /* FADE MODE OVERRIDES */
        .<?php echo esc_attr($unique_id); ?>.mode-fade .mc-slide {
            position: absolute; top: 0; left: 0;
            opacity: 0; transition: opacity 0.8s ease-in-out;
            pointer-events: none;
        }
        .<?php echo esc_attr($unique_id); ?>.mode-fade .mc-slide.is-active {
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
        <?php echo $content; ?>
    </div>

    <?php if ($show_arrows) : ?>
        <button class="mc-arrow mc-prev"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg></button>
        <button class="mc-arrow mc-next"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg></button>
    <?php endif; ?>

    <?php if ($show_dots) : ?>
        <div class="mc-dots"></div>
    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const slider = document.querySelector('.<?php echo esc_attr($unique_id); ?>');
            if (!slider) return;

            const track = slider.querySelector('.mc-slider-track');
            const slides = Array.from(slider.querySelectorAll('.mc-slide'));
            const prevBtn = slider.querySelector('.mc-prev');
            const nextBtn = slider.querySelector('.mc-next');
            const dotsContainer = slider.querySelector('.mc-dots');
            
            const isFade = slider.classList.contains('mode-fade');
            const auto = slider.dataset.autoplay === 'true';
            const delay = parseInt(slider.dataset.delay);
            
            let currentIndex = 0;
            let timer = null;

            // Generate Dots
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

            // Initial Setup
            if (isFade) slides[0].classList.add('is-active');

            // Listeners
            if (prevBtn) prevBtn.addEventListener('click', () => goToSlide(currentIndex - 1));
            if (nextBtn) nextBtn.addEventListener('click', () => goToSlide(currentIndex + 1));

            // Swipe Physics
            let touchStartX = 0;
            slider.addEventListener('touchstart', e => touchStartX = e.touches[0].clientX, { passive: true });
            slider.addEventListener('touchend', e => {
                const touchEndX = e.changedTouches[0].clientX;
                if (touchStartX - touchEndX > 50) goToSlide(currentIndex + 1); // Swipe Left
                if (touchEndX - touchStartX > 50) goToSlide(currentIndex - 1); // Swipe Right
            }, { passive: true });

            // Autoplay Loop
            const startAutoplay = () => {
                if (auto) timer = setInterval(() => goToSlide(currentIndex + 1), delay);
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