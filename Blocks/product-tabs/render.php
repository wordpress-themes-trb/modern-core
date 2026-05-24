<?php
$width_mode = $attributes['widthMode'] ?? 'contained';
if (!class_exists('WooCommerce')) return;

$title = $attributes['sectionTitle'] ?? 'Shop by Collection';
$subtitle = $attributes['sectionSubtitle'] ?? 'Curated for you';
$tab_style = $attributes['tabStyle'] ?? 'pill';
$tab_align = $attributes['tabAlignment'] ?? 'center';
$anim = $attributes['animationStyle'] ?? 'fade';
$tabs = $attributes['tabsData'] ?? [];
$cols_d = $attributes['colsDesktop'] ?? 4;
$cols_m = $attributes['colsMobile'] ?? 2;
$ratio = $attributes['imageRatio'] ?? '3/4';
$card_bg = $attributes['cardBg'] ?? '#ffffff';
$radius = $attributes['cardRadius'] ?? 8;
$accent = $attributes['accentColor'] ?? '#0f172a';
$inactive = $attributes['inactiveColor'] ?? '#94a3b8';

$unique_id = uniqid('mc-ptabs-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "mc-block width-" . esc_attr($width_mode) . " modern-core-product-tabs {$unique_id} style-{$tab_style} anim-{$anim}"]);

// FIX: Wrap helper function to prevent redeclaration errors if multiple blocks exist
if (!function_exists('mc_get_tab_query')) {
    function mc_get_tab_query($type, $count) {
        $args = ['post_type' => 'product', 'posts_per_page' => $count, 'post_status' => 'publish'];
        if ($type === 'popularity') {
            $args['meta_key'] = 'total_sales'; $args['orderby'] = 'meta_value_num';
        } elseif ($type === 'sale') {
            $args['post__in'] = array_merge([0], wc_get_product_ids_on_sale());
        } elseif ($type === 'rating') {
            $args['meta_key'] = '_wc_average_rating'; $args['orderby'] = 'meta_value_num'; $args['order'] = 'DESC';
        }
        return new WP_Query($args);
    }
}
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            --mc-cols: <?php echo esc_attr($cols_d); ?>;
            --mc-cols-m: <?php echo esc_attr($cols_m); ?>;
            --mc-ratio: <?php echo esc_attr($ratio); ?>;
            --mc-radius: <?php echo esc_attr($radius); ?>px;
            --mc-bg: <?php echo esc_attr($card_bg); ?>;
            --mc-accent: <?php echo esc_attr($accent); ?>;
            --mc-inactive: <?php echo esc_attr($inactive); ?>;
            width: 100%; padding: 60px 0;
        }

        /* Nav Styles */
        .<?php echo esc_attr($unique_id); ?> .mc-tabs-nav {
            display: flex; justify-content: <?php echo esc_attr($tab_align); ?>; gap: 15px; flex-wrap: wrap; margin-bottom: 40px;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-tab-btn {
            padding: 10px 24px; font-size: 14px; font-weight: bold; cursor: pointer; transition: all 0.3s; background: transparent;
        }
        
        <?php if ($tab_style === 'pill') : ?>
            .<?php echo esc_attr($unique_id); ?> .mc-tab-btn { border-radius: 50px; border: 2px solid var(--mc-inactive); color: var(--mc-inactive); }
            .<?php echo esc_attr($unique_id); ?> .mc-tab-btn.is-active { background: var(--mc-accent); border-color: var(--mc-accent); color: #fff; }
        <?php elseif ($tab_style === 'underline') : ?>
            .<?php echo esc_attr($unique_id); ?> .mc-tabs-nav { border-bottom: 1px solid rgba(0,0,0,0.1); gap: 0; }
            .<?php echo esc_attr($unique_id); ?> .mc-tab-btn { border: none; border-bottom: 2px solid transparent; color: var(--mc-inactive); padding: 15px 20px; }
            .<?php echo esc_attr($unique_id); ?> .mc-tab-btn.is-active { border-bottom: 2px solid var(--mc-accent); color: var(--mc-accent); }
        <?php elseif ($tab_style === 'box') : ?>
            .<?php echo esc_attr($unique_id); ?> .mc-tab-btn { border: 1px solid rgba(0,0,0,0.1); color: var(--mc-inactive); border-radius: 4px; }
            .<?php echo esc_attr($unique_id); ?> .mc-tab-btn.is-active { border-color: var(--mc-accent); color: var(--mc-accent); box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        <?php endif; ?>

        /* Panels Container */
        .<?php echo esc_attr($unique_id); ?> .mc-tabs-content-wrapper { position: relative; }
        .<?php echo esc_attr($unique_id); ?> .mc-tab-pane {
            display: none; grid-template-columns: repeat(var(--mc-cols), 1fr); gap: 30px;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-tab-pane.is-active { display: grid; }

        @media (max-width: 768px) {
            .<?php echo esc_attr($unique_id); ?> .mc-tab-pane { grid-template-columns: repeat(var(--mc-cols-m), 1fr); gap: 15px; }
        }

        /* Animation Classes */
        .<?php echo esc_attr($unique_id); ?>.anim-fade .mc-tab-pane { animation: mcFadeIn 0.5s ease forwards; }
        .<?php echo esc_attr($unique_id); ?>.anim-slide .mc-tab-pane { animation: mcSlideIn 0.5s cubic-bezier(0.165, 0.84, 0.44, 1) forwards; }
        .<?php echo esc_attr($unique_id); ?>.anim-scale .mc-tab-pane { animation: mcScalePop 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; }

        @keyframes mcFadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes mcSlideIn { from { opacity: 0; transform: translateX(30px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes mcScalePop { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }

        /* Shared Card Styles */
        .mc-tab-card { background: var(--mc-bg); border-radius: var(--mc-radius); overflow: hidden; border: 1px solid rgba(0,0,0,0.05); transition: transform 0.3s; }
        .mc-tab-card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .mc-tab-img { aspect-ratio: var(--mc-ratio); width: 100%; object-fit: cover; }
        .mc-tab-info { padding: 20px; }
        .mc-tab-title { font-size: 15px; margin: 0 0 10px 0; color: inherit; text-decoration: none; display: block; }
        .mc-tab-price { font-weight: bold; font-size: 15px; }
        .mc-tab-price del { opacity: 0.5; font-size: 13px; font-weight: normal; margin-left: 5px; }
    </style>

    <div style="text-align: center; margin-bottom: 40px;">
        <?php if ($subtitle) echo '<p style="font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--mc-accent); margin: 0 0 10px 0;">' . esc_html($subtitle) . '</p>'; ?>
        <h2 style="font-size: 32px; font-weight: 800; margin: 0;"><?php echo esc_html($title); ?></h2>
    </div>

    <div class="mc-tabs-nav">
        <?php foreach ($tabs as $index => $tab) : ?>
            <button class="mc-tab-btn <?php echo $index === 0 ? 'is-active' : ''; ?>" data-target="tab-<?php echo esc_attr($unique_id . '-' . $index); ?>">
                <?php echo esc_html($tab['label']); ?>
            </button>
        <?php endforeach; ?>
    </div>

    <div class="mc-tabs-content-wrapper">
        <?php foreach ($tabs as $index => $tab) : 
            $query = mc_get_tab_query($tab['query'], $tab['count']);
        ?>
            <div class="mc-tab-pane <?php echo $index === 0 ? 'is-active' : ''; ?>" id="tab-<?php echo esc_attr($unique_id . '-' . $index); ?>">
                <?php if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post(); global $product; ?>
                    <div <?php wc_product_class('mc-tab-card', $product); ?>>
                        <a href="<?php echo esc_url($product->get_permalink()); ?>">
                            <img src="<?php echo esc_url(wp_get_attachment_image_url($product->get_image_id(), 'woocommerce_thumbnail')); ?>" class="mc-tab-img" />
                        </a>
                        <div class="mc-tab-info">
                            <a href="<?php echo esc_url($product->get_permalink()); ?>" class="mc-tab-title"><?php echo $product->get_name(); ?></a>
                            <div class="mc-tab-price"><?php echo $product->get_price_html(); ?></div>
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata(); else: ?>
                    <p>No products found for this category.</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.querySelector('.<?php echo esc_attr($unique_id); ?>');
            if (!container) return;

            const btns = container.querySelectorAll('.mc-tab-btn');
            const panes = container.querySelectorAll('.mc-tab-pane');

            btns.forEach(btn => {
                btn.addEventListener('click', () => {
                    // Reset
                    btns.forEach(b => b.classList.remove('is-active'));
                    panes.forEach(p => p.classList.remove('is-active'));
                    
                    // Activate
                    btn.classList.add('is-active');
                    const targetId = btn.dataset.target;
                    const targetPane = document.getElementById(targetId);
                    if (targetPane) {
                        // Force DOM reflow to restart CSS animation
                        void targetPane.offsetWidth; 
                        targetPane.classList.add('is-active');
                    }
                });
            });
        });
    </script>
</div>