<?php
if (!class_exists('WooCommerce')) return;

$source = $attributes['sourceMode'] ?? 'dynamic';
$count = $attributes['catCount'] ?? 6;
$layout = $attributes['layoutMode'] ?? 'grid';
$cols_d = $attributes['colsDesktop'] ?? 3;
$cols_m = $attributes['colsMobile'] ?? 2;
$ratio = $attributes['imageRatio'] ?? '3/4';
$placement = $attributes['labelPlacement'] ?? 'overlay';
$txt_pos = $attributes['textPosition'] ?? 'bottom-left';
$show_count = $attributes['showCount'] ?? false;
$hover_effect = $attributes['hoverEffect'] ?? 'zoom-darken';
$radius = $attributes['cardRadius'] ?? 8;
$opacity = ($attributes['overlayOpacity'] ?? 40) / 100;
$accent = $attributes['accentColor'] ?? '#0f172a';
$text_c = $attributes['textColor'] ?? '#ffffff';

$unique_id = uniqid('mc-cat-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "modern-core-category-grid {$unique_id} layout-{$layout} label-{$placement} hover-{$hover_effect}"]);

// Query Categories
$args = [
    'taxonomy'   => 'product_cat',
    'hide_empty' => true,
    'number'     => $count,
    'orderby'    => 'count',
    'order'      => 'DESC',
];

// Exclude 'Uncategorized' typically
$uncategorized = get_term_by('slug', 'uncategorized', 'product_cat');
if ($uncategorized) {
    $args['exclude'] = [$uncategorized->term_id];
}

$categories = get_terms($args);
if (empty($categories) || is_wp_error($categories)) return;

// Align Styles
$flex_align = 'align-items: flex-start; justify-content: flex-end;'; // bottom-left
if ($txt_pos === 'center') $flex_align = 'align-items: center; justify-content: center; text-align: center;';
if ($txt_pos === 'bottom-center') $flex_align = 'align-items: center; justify-content: flex-end; text-align: center;';
if ($txt_pos === 'top-left') $flex_align = 'align-items: flex-start; justify-content: flex-start;';

$grad_style = strpos($txt_pos, 'bottom') !== false 
    ? "linear-gradient(to top, rgba(0,0,0," . ($opacity + 0.3) . "), rgba(0,0,0,0))" 
    : "rgba(0,0,0,{$opacity})";
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            --mc-cols: <?php echo esc_attr($cols_d); ?>;
            --mc-cols-m: <?php echo esc_attr($cols_m); ?>;
            --mc-ratio: <?php echo esc_attr($ratio); ?>;
            --mc-radius: <?php echo esc_attr($radius); ?>px;
            width: 100%;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-cat-wrap {
            display: grid;
            grid-template-columns: repeat(var(--mc-cols), 1fr);
            gap: 20px;
        }

        /* Layout Modes */
        .<?php echo esc_attr($unique_id); ?>.layout-scroll .mc-cat-wrap {
            display: flex; overflow-x: auto; scroll-snap-type: x mandatory; padding-bottom: 20px; scrollbar-width: none;
        }
        .<?php echo esc_attr($unique_id); ?>.layout-scroll .mc-cat-wrap::-webkit-scrollbar { display: none; }
        
        .<?php echo esc_attr($unique_id); ?>.layout-featured .mc-cat-wrap .mc-cat-tile:first-child {
            grid-column: span 2; grid-row: span 2;
        }

        @media (max-width: 768px) {
            .<?php echo esc_attr($unique_id); ?> .mc-cat-wrap { grid-template-columns: repeat(var(--mc-cols-m), 1fr); gap: 10px; }
            .<?php echo esc_attr($unique_id); ?>.layout-featured .mc-cat-wrap .mc-cat-tile:first-child { grid-column: span 1; grid-row: span 1; }
        }

        /* Tile Physics */
        .<?php echo esc_attr($unique_id); ?> .mc-cat-tile {
            position: relative; border-radius: var(--mc-radius); overflow: hidden; display: flex; flex-direction: column;
            text-decoration: none; color: inherit;
        }
        .<?php echo esc_attr($unique_id); ?>.layout-scroll .mc-cat-tile {
            flex: 0 0 calc((100% - (var(--mc-cols) - 1) * 20px) / var(--mc-cols)); scroll-snap-align: start;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-cat-img-box {
            position: relative; width: 100%; aspect-ratio: var(--mc-ratio); overflow: hidden; display: block;
        }
        .<?php echo esc_attr($unique_id); ?>.layout-featured .mc-cat-wrap .mc-cat-tile:first-child .mc-cat-img-box {
            height: 100%; aspect-ratio: auto; min-height: 400px;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-cat-img {
            width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        /* Hover Effects */
        .<?php echo esc_attr($unique_id); ?>.hover-zoom-darken .mc-cat-tile:hover .mc-cat-img { transform: scale(1.06); }
        .<?php echo esc_attr($unique_id); ?>.hover-zoom-darken .mc-cat-tile:hover .mc-cat-overlay { opacity: 1; }

        /* Overlay Styles */
        .<?php echo esc_attr($unique_id); ?> .mc-cat-overlay {
            position: absolute; inset: 0; background: <?php echo $grad_style; ?>;
            transition: opacity 0.4s ease; z-index: 1;
        }
        
        .<?php echo esc_attr($unique_id); ?> .mc-cat-content-overlay {
            position: absolute; inset: 0; z-index: 2; padding: 30px; display: flex; flex-direction: column;
            color: <?php echo esc_attr($text_c); ?>; <?php echo $flex_align; ?>
        }
        
        .<?php echo esc_attr($unique_id); ?> .mc-cat-name { font-size: 24px; font-weight: bold; margin: 0; line-height: 1.1; }
        .<?php echo esc_attr($unique_id); ?> .mc-cat-count { font-size: 13px; opacity: 0.8; margin-top: 5px; }

        /* Caption Styles */
        .<?php echo esc_attr($unique_id); ?> .mc-cat-caption {
            padding: 20px 0; text-align: center; color: <?php echo esc_attr($accent); ?>;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-cat-caption .mc-cat-name { font-size: 18px; }
        .<?php echo esc_attr($unique_id); ?> .mc-cat-caption .mc-cat-count { font-size: 12px; opacity: 0.6; display: block; }
    </style>

    <div class="mc-cat-wrap">
        <?php foreach ($categories as $cat) : 
            $link = get_term_link($cat);
            $thumbnail_id = get_term_meta($cat->term_id, 'thumbnail_id', true);
            $img_url = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'large') : wc_placeholder_img_src();
        ?>
            <a href="<?php echo esc_url($link); ?>" class="mc-cat-tile">
                <div class="mc-cat-img-box">
                    <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($cat->name); ?>" class="mc-cat-img" loading="lazy" />
                    
                    <?php if ($placement === 'overlay') : ?>
                        <div class="mc-cat-overlay"></div>
                        <div class="mc-cat-content-overlay">
                            <h3 class="mc-cat-name"><?php echo esc_html($cat->name); ?></h3>
                            <?php if ($show_count) echo '<span class="mc-cat-count">' . esc_html($cat->count) . ' products</span>'; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($placement === 'below') : ?>
                    <div class="mc-cat-caption">
                        <h3 class="mc-cat-name"><?php echo esc_html($cat->name); ?></h3>
                        <?php if ($show_count) echo '<span class="mc-cat-count">' . esc_html($cat->count) . ' products</span>'; ?>
                    </div>
                <?php endif; ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>