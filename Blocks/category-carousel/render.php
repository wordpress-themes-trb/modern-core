<?php
$width_mode = $attributes['widthMode'] ?? 'contained';
if (!class_exists('WooCommerce')) return;

$source = $attributes['sourceMode'] ?? 'dynamic';
$count = $attributes['catCount'] ?? 8;
$cols_d = $attributes['colsDesktop'] ?? 6;
$cols_t = $attributes['colsTablet'] ?? 4;
$cols_m = $attributes['colsMobile'] ?? 3.2;
$shape = $attributes['tileShape'] ?? 'rounded-square';
$morph_mobile = $attributes['mobileMorphCircle'] ?? true;
$placement = $attributes['labelPlacement'] ?? 'overlay';
$show_count = $attributes['showCount'] ?? false;
$opacity = ($attributes['overlayOpacity'] ?? 40) / 100;
$hover_effect = $attributes['hoverEffect'] ?? 'zoom';
$arrows = $attributes['showArrows'] ?? true;
$dots = $attributes['showDots'] ?? false;
$autoplay = $attributes['autoplay'] ?? false;
$delay = $attributes['delay'] ?? 4000;
$accent = $attributes['accentColor'] ?? '#0f172a';
$text_c = $attributes['textColor'] ?? '#ffffff';

// Force placement to below if desktop shape is circle
if ($shape === 'circle') $placement = 'below';

$unique_id = uniqid('mc-catcar-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "mc-block width-" . esc_attr($width_mode) . " modern-core-cat-carousel {$unique_id} shape-{$shape} label-{$placement} hover-{$hover_effect}"]);

// Query Categories
$args = [ 'taxonomy' => 'product_cat', 'hide_empty' => true, 'number' => $count, 'orderby' => 'count', 'order' => 'DESC' ];
$uncategorized = get_term_by('slug', 'uncategorized', 'product_cat');
if ($uncategorized) $args['exclude'] = [$uncategorized->term_id];

$categories = get_terms($args);
if (empty($categories) || is_wp_error($categories)) return;

// Geometry Math
$border_radius = '0'; $aspect_ratio = '1/1';
if ($shape === 'rounded-square') $border_radius = '12px';
if ($shape === 'circle') $border_radius = '50%';
if ($shape === 'pill') { $border_radius = '50px'; $aspect_ratio = '2/3'; }
if ($shape === 'rectangle') { $border_radius = '0'; $aspect_ratio = '4/5'; }
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            --mc-cols: <?php echo esc_attr($cols_d); ?>;
            --mc-radius: <?php echo esc_attr($border_radius); ?>;
            --mc-ratio: <?php echo esc_attr($aspect_ratio); ?>;
            --mc-gap: 20px;
            width: 100%; position: relative; padding: 40px 0;
        }

        @media (max-width: 1024px) { .<?php echo esc_attr($unique_id); ?> { --mc-cols: <?php echo esc_attr($cols_t); ?>; } }
        @media (max-width: 768px) { .<?php echo esc_attr($unique_id); ?> { --mc-cols: <?php echo esc_attr($cols_m); ?>; --mc-gap: 15px; } }

        /* Track Physics */
        .<?php echo esc_attr($unique_id); ?> .mc-cc-track {
            display: flex; gap: var(--mc-gap); overflow-x: auto; scroll-snap-type: x mandatory;
            padding: 0 5% 20px 5%; scrollbar-width: none; scroll-behavior: smooth;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-cc-track::-webkit-scrollbar { display: none; }

        /* Tile Physics */
        .<?php echo esc_attr($unique_id); ?> .mc-cc-tile {
            flex: 0 0 calc((100% - (var(--mc-cols) - 1) * var(--mc-gap)) / var(--mc-cols));
            scroll-snap-align: start; position: relative; display: flex; flex-direction: column; align-items: center;
            text-decoration: none; color: inherit;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-cc-img-zone {
            width: 100%; aspect-ratio: var(--mc-ratio); border-radius: var(--mc-radius);
            position: relative; overflow: hidden; display: block; background: #f1f5f9;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-cc-img {
            width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
        }
        
        .<?php echo esc_attr($unique_id); ?>.hover-zoom .mc-cc-tile:hover .mc-cc-img { transform: scale(1.08); }

        /* Overlay text */
        .<?php echo esc_attr($unique_id); ?> .mc-cc-overlay {
            position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,<?php echo esc_attr($opacity + 0.3); ?>), rgba(0,0,0,0)); z-index: 1; pointer-events: none; transition: opacity 0.3s;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-cc-content-overlay {
            position: absolute; inset: 0; z-index: 2; padding: 20px; display: flex; flex-direction: column;
            justify-content: center; align-items: center; text-align: center; color: <?php echo esc_attr($text_c); ?>; pointer-events: none;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-cc-title-overlay { font-size: 18px; font-weight: bold; margin: 0; line-height: 1.2; text-shadow: 0 2px 10px rgba(0,0,0,0.3); }

        /* Caption Text (Below) */
        .<?php echo esc_attr($unique_id); ?> .mc-cc-caption {
            padding: 15px 0; text-align: center; color: <?php echo esc_attr($accent); ?>; width: 100%;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-cc-title-below { font-size: 16px; font-weight: bold; margin: 0; transition: color 0.2s; }
        .<?php echo esc_attr($unique_id); ?> .mc-cc-count { font-size: 12px; opacity: 0.6; display: block; margin-top: 4px; }
        .<?php echo esc_attr($unique_id); ?> .mc-cc-tile:hover .mc-cc-title-below { color: #000; }

        /* Mobile Morphing Logic */
        <?php if ($morph_mobile) : ?>
        @media (max-width: 768px) {
            .<?php echo esc_attr($unique_id); ?> { --mc-radius: 50%; --mc-ratio: 1/1; }
            .<?php echo esc_attr($unique_id); ?> .mc-cc-overlay, 
            .<?php echo esc_attr($unique_id); ?> .mc-cc-content-overlay { display: none !important; }
            .<?php echo esc_attr($unique_id); ?> .mc-cc-caption { display: block !important; }
            .<?php echo esc_attr($unique_id); ?> .mc-cc-title-below { font-size: 14px; }
        }
        <?php endif; ?>

        /* Arrows */
        .<?php echo esc_attr($unique_id); ?> .mc-cc-arrow {
            position: absolute; top: 40%; transform: translateY(-50%); z-index: 10;
            background: #fff; color: #000; width: 40px; height: 40px; border-radius: 50%;
            border: 1px solid rgba(0,0,0,0.1); box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            display: flex; align-items: center; justify-content: center; cursor: pointer;
            transition: opacity 0.3s ease, transform 0.2s ease; opacity: 0; pointer-events: none;
        }
        .<?php echo esc_attr($unique_id); ?>:hover .mc-cc-arrow { opacity: 1; pointer-events: auto; }
        .<?php echo esc_attr($unique_id); ?> .mc-cc-prev { left: 15px; }
        .<?php echo esc_attr($unique_id); ?> .mc-cc-next { right: 15px; }
        @media (max-width: 768px) { .<?php echo esc_attr($unique_id); ?> .mc-cc-arrow { display: none; } }
    </style>

    <div class="mc-cc-track">
        <?php foreach ($categories as $cat) : 
            $link = get_term_link($cat);
            $thumbnail_id = get_term_meta($cat->term_id, 'thumbnail_id', true);
            $img_url = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'medium_large') : wc_placeholder_img_src();
        ?>
            <a href="<?php echo esc_url($link); ?>" class="mc-cc-tile">
                <div class="mc-cc-img-zone">
                    <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($cat->name); ?>" class="mc-cc-img" loading="lazy" />
                    
                    <?php if ($placement === 'overlay') : ?>
                        <div class="mc-cc-overlay"></div>
                        <div class="mc-cc-content-overlay">
                            <h3 class="mc-cc-title-overlay"><?php echo esc_html($cat->name); ?></h3>
                            <?php if ($show_count) echo '<span class="mc-cc-count">' . esc_html($cat->count) . ' items</span>'; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mc-cc-caption" style="<?php echo ($placement === 'overlay' && !$morph_mobile) ? 'display: none;' : ''; ?>">
                    <h3 class="mc-cc-title-below"><?php echo esc_html($cat->name); ?></h3>
                    <?php if ($show_count) echo '<span class="mc-cc-count">' . esc_html($cat->count) . ' items</span>'; ?>
                </div>
            </a>
        <?php endforeach; ?>
    </div>

    <?php if ($arrows) : ?>
        <button class="mc-cc-arrow mc-cc-prev"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg></button>
        <button class="mc-cc-arrow mc-cc-next"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg></button>
    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const carousel = document.querySelector('.<?php echo esc_attr($unique_id); ?>');
            if (!carousel) return;

            const track = carousel.querySelector('.mc-cc-track');
            const prevBtn = carousel.querySelector('.mc-cc-prev');
            const nextBtn = carousel.querySelector('.mc-cc-next');
            const auto = <?php echo $autoplay ? 'true' : 'false'; ?>;
            const delay = <?php echo (int)$delay; ?>;
            
            let timer = null;

            const getScrollAmount = () => {
                const card = track.querySelector('.mc-cc-tile');
                const gap = parseInt(window.getComputedStyle(carousel).getPropertyValue('--mc-gap')) || 0;
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

            const startAutoplay = () => {
                if (auto && window.innerWidth > 768) {
                    timer = setInterval(() => {
                        if (track.scrollLeft + track.clientWidth >= track.scrollWidth - 10) {
                            track.scrollTo({ left: 0, behavior: 'smooth' });
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
</div>