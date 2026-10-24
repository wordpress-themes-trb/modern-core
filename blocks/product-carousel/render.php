<?php
$width_mode = $attributes['widthMode'] ?? 'contained';
if (!class_exists('WooCommerce')) return;

$title = $attributes['sectionTitle'] ?? 'Featured Products';
$subtitle = $attributes['sectionSubtitle'] ?? '';
$view_all = $attributes['viewAllText'] ?? 'View all →';
$view_url = $attributes['viewAllLink'] ?? '#';
$count = $attributes['productCount'] ?? 12;
$sort = $attributes['querySort'] ?? 'date';
$cols_d = $attributes['colsDesktop'] ?? 4;
$cols_t = $attributes['colsTablet'] ?? 2.5;
$cols_m = $attributes['colsMobile'] ?? 1.2;
$ratio = $attributes['imageRatio'] ?? '3/4';
$radius = $attributes['cardRadius'] ?? 8;
$bg = $attributes['cardBg'] ?? '#ffffff';
$lift = $attributes['cardHoverLift'] ?? true;
$show_rating = $attributes['showRating'] ?? true;
$arrows = $attributes['showArrows'] ?? true;
$dots = $attributes['showDots'] ?? true;
$autoplay = $attributes['autoplay'] ?? false;
$delay = $attributes['delay'] ?? 4000;
$accent = $attributes['accentColor'] ?? '#0f172a';

$unique_id = uniqid('mc-carousel-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "mc-block width-" . esc_attr($width_mode) . " modern-core-product-carousel {$unique_id}"]);

$products = new WP_Query( modern_core_product_query_args( (string) $sort, (int) $count ) );
if ( ! $products->have_posts() ) {
    return; // Nothing to show (e.g. no sale items): hide the whole section.
}
$view_url = modern_core_url( (string) $view_url );
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            --mc-cols: <?php echo esc_attr($cols_d); ?>;
            --mc-ratio: <?php echo esc_attr($ratio); ?>;
            --mc-radius: <?php echo esc_attr($radius); ?>px;
            --mc-gap: 30px;
            width: 100%; padding: 60px 0; position: relative;
        }
        @media (max-width: 1024px) { .<?php echo esc_attr($unique_id); ?> { --mc-cols: <?php echo esc_attr($cols_t); ?>; --mc-gap: 20px; } }
        @media (max-width: 768px) { .<?php echo esc_attr($unique_id); ?> { --mc-cols: <?php echo esc_attr($cols_m); ?>; --mc-gap: 15px; } }

        /* Header */
        .<?php echo esc_attr($unique_id); ?> .mc-pc-header {
            display: flex; justify-content: space-between; align-items: flex-end;
            margin-bottom: 40px; padding: 0 5%;
        }

        /* Native Scroll Track */
        .<?php echo esc_attr($unique_id); ?> .mc-pc-track {
            display: flex; gap: var(--mc-gap); overflow-x: auto;
            scroll-snap-type: x mandatory; scroll-behavior: smooth;
            padding: 0 5% 40px 5%; /* Padding bottom for shadow clearance */
            scrollbar-width: none; /* Firefox */
        }
        .<?php echo esc_attr($unique_id); ?> .mc-pc-track::-webkit-scrollbar { display: none; } /* Safari/Chrome */

        /* Card Geometry */
        .<?php echo esc_attr($unique_id); ?> .mc-pc-card {
            flex: 0 0 calc((100% - (var(--mc-cols) - 1) * var(--mc-gap)) / var(--mc-cols));
            scroll-snap-align: start;
            background: <?php echo esc_attr($bg); ?>; border-radius: var(--mc-radius);
            border: 1px solid rgba(0,0,0,0.05); display: flex; flex-direction: column;
            transition: transform 0.3s ease, box-shadow 0.3s ease; position: relative; overflow: hidden;
        }
        <?php if ($lift) : ?>
        .<?php echo esc_attr($unique_id); ?> .mc-pc-card:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(0,0,0,0.1); }
        <?php endif; ?>

        .<?php echo esc_attr($unique_id); ?> .mc-pc-img {
            aspect-ratio: var(--mc-ratio); width: 100%; object-fit: cover;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-pc-info { padding: 20px; display: flex; flex-direction: column; flex: 1; }
        .<?php echo esc_attr($unique_id); ?> .mc-pc-title { font-size: 15px; margin: 0 0 10px 0; color: inherit; text-decoration: none; }
        .<?php echo esc_attr($unique_id); ?> .mc-pc-price { font-weight: bold; font-size: 15px; margin-top: auto; }
        .<?php echo esc_attr($unique_id); ?> .mc-pc-price del { opacity: 0.5; font-size: 13px; font-weight: normal; margin-left: 5px; }

        /* UI Arrows */
        .<?php echo esc_attr($unique_id); ?> .mc-pc-arrow {
            position: absolute; top: 55%; transform: translateY(-50%); z-index: 10;
            background: #fff; color: #000; width: 44px; height: 44px; border-radius: 50%;
            border: 1px solid rgba(0,0,0,0.1); box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            display: flex; align-items: center; justify-content: center; cursor: pointer;
            transition: opacity 0.3s ease, transform 0.2s ease; opacity: 0; pointer-events: none;
        }
        .<?php echo esc_attr($unique_id); ?>:hover .mc-pc-arrow { opacity: 1; pointer-events: auto; }
        .<?php echo esc_attr($unique_id); ?> .mc-pc-arrow:active { transform: translateY(-50%) scale(0.9); }
        .<?php echo esc_attr($unique_id); ?> .mc-pc-prev { left: 2%; }
        .<?php echo esc_attr($unique_id); ?> .mc-pc-next { right: 2%; }

        @media (max-width: 768px) {
            .<?php echo esc_attr($unique_id); ?> .mc-pc-arrow { display: none; } /* Swipe is superior on mobile */
        }
    </style>

    <div class="mc-pc-header">
        <div>
            <?php if ($subtitle) echo '<p style="margin: 0 0 5px 0; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: '.esc_attr($accent).';">' . esc_html($subtitle) . '</p>'; ?>
            <h2 style="margin: 0; font-size: 28px; font-weight: 800;"><?php echo esc_html($title); ?></h2>
        </div>
        <?php if ($view_all) echo '<a href="'.esc_url($view_url).'" style="font-size: 14px; font-weight: bold; color: '.esc_attr($accent).'; text-decoration: none;">' . esc_html($view_all) . '</a>'; ?>
    </div>

    <?php if ($products->have_posts()) : ?>
        <div class="mc-pc-track">
            <?php while ($products->have_posts()) : $products->the_post(); 
                global $product;
                $link = apply_filters('woocommerce_loop_product_link', get_the_permalink(), $product);
            ?>
                <div <?php wc_product_class('mc-pc-card', $product); ?> style="position: relative;">
                    <?php echo modern_core_save_button( $product->get_id(), 'wishlist', 'mc-card-save' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in the helper. ?>
                    <a href="<?php echo esc_url($link); ?>" tabindex="-1" aria-hidden="true">
                        <?php echo $product->get_image( 'woocommerce_thumbnail', array( 'class' => 'mc-pc-img', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core image markup. ?>
                    </a>
                    <div class="mc-pc-info">
                        <a href="<?php echo esc_url($link); ?>" class="mc-pc-title"><?php echo esc_html( $product->get_name() ); ?></a>
                        <?php if ($show_rating && $product->get_rating_count() > 0) : ?>
                            <div style="font-size: 12px; margin-bottom: 10px; color: #f59e0b;">
                                <?php echo wp_kses_post( wc_get_rating_html( (float) $product->get_average_rating() ) ); ?>
                            </div>
                        <?php endif; ?>
                        <div class="mc-pc-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
                    </div>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>

        <?php if ($arrows) : ?>
            <button type="button" class="mc-pc-arrow mc-pc-prev" aria-label="<?php esc_attr_e( 'Previous products', 'modern-core' ); ?>"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg></button>
            <button type="button" class="mc-pc-arrow mc-pc-next" aria-label="<?php esc_attr_e( 'Next products', 'modern-core' ); ?>"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg></button>
        <?php endif; ?>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const carousel = document.querySelector('.<?php echo esc_attr($unique_id); ?>');
                if (!carousel) return;

                const track = carousel.querySelector('.mc-pc-track');
                const prevBtn = carousel.querySelector('.mc-pc-prev');
                const nextBtn = carousel.querySelector('.mc-pc-next');
                const auto = <?php echo $autoplay ? 'true' : 'false'; ?>;
                const delay = <?php echo (int)$delay; ?>;
                
                let timer = null;

                const getScrollAmount = () => {
                    const card = track.querySelector('.mc-pc-card');
                    const gap = parseInt(window.getComputedStyle(track).gap) || 0;
                    return card ? card.offsetWidth + gap : 0;
                };

                if (nextBtn) nextBtn.addEventListener('click', () => {
                    track.scrollBy({ left: getScrollAmount() * Math.floor(parseFloat(carousel.style.getPropertyValue('--mc-cols') || 1)), behavior: 'smooth' });
                    resetAutoplay();
                });
                if (prevBtn) prevBtn.addEventListener('click', () => {
                    track.scrollBy({ left: -(getScrollAmount() * Math.floor(parseFloat(carousel.style.getPropertyValue('--mc-cols') || 1))), behavior: 'smooth' });
                    resetAutoplay();
                });

                // Autoplay Engine
                const startAutoplay = () => {
                    if (auto && window.innerWidth > 768) { // Prevent autoplay jumping on mobile where user is swiping
                        timer = setInterval(() => {
                            if (track.scrollLeft + track.clientWidth >= track.scrollWidth - 10) {
                                track.scrollTo({ left: 0, behavior: 'smooth' }); // Loop back to start
                            } else {
                                track.scrollBy({ left: getScrollAmount(), behavior: 'smooth' });
                            }
                        }, delay);
                    }
                };
                const resetAutoplay = () => { clearInterval(timer); startAutoplay(); };

                carousel.addEventListener('mouseenter', () => clearInterval(timer));
                carousel.addEventListener('mouseleave', startAutoplay);
                startAutoplay();
            });
        </script>
    <?php endif; ?>
</div>