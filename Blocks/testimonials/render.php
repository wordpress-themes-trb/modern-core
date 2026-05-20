<?php
$layout = $attributes['layoutMode'] ?? 'grid';
$title = $attributes['sectionTitle'] ?? 'What our customers say';
$show_agg = $attributes['showAggregate'] ?? true;
$score = $attributes['aggregateScore'] ?? '4.9';
$count = $attributes['aggregateCount'] ?? '2,400';
$cols_d = $attributes['colsDesktop'] ?? 3;
$cols_m = $attributes['colsMobile'] ?? 1;
$show_avatar = $attributes['showAvatar'] ?? true;
$show_rating = $attributes['showRating'] ?? true;
$bg = $attributes['cardBg'] ?? '#ffffff';
$radius = $attributes['cardRadius'] ?? 12;
$shadow = ($attributes['shadowOpacity'] ?? 5) / 100;
$text_c = $attributes['textColor'] ?? '#0f172a';
$star_c = $attributes['starColor'] ?? '#f59e0b';
$testimonials = $attributes['testimonials'] ?? [];

if (empty($testimonials)) return;

$unique_id = uniqid('mc-test-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "modern-core-testimonials {$unique_id} layout-{$layout}"]);

function mc_render_stars($count) {
    $count = intval($count);
    return str_repeat('★', $count) . str_repeat('☆', 5 - $count);
}
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            width: 100%; padding: 60px 0;
            --mc-cols: <?php echo esc_attr($cols_d); ?>;
            --mc-cols-m: <?php echo esc_attr($cols_m); ?>;
            --mc-bg: <?php echo esc_attr($bg); ?>;
            --mc-text: <?php echo esc_attr($text_c); ?>;
            --mc-star: <?php echo esc_attr($star_c); ?>;
            --mc-rad: <?php echo esc_attr($radius); ?>px;
        }

        /* Header Area */
        .<?php echo esc_attr($unique_id); ?> .mc-t-header {
            display: flex; justify-content: space-between; align-items: flex-end;
            margin-bottom: 40px; flex-wrap: wrap; gap: 20px; padding: 0 5%;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-t-title { margin: 0; font-size: clamp(24px, 4vw, 36px); font-weight: 800; color: var(--mc-text); }
        .<?php echo esc_attr($unique_id); ?> .mc-t-agg { display: flex; align-items: center; gap: 12px; }
        .<?php echo esc_attr($unique_id); ?> .mc-t-agg-score { font-size: 36px; font-weight: 800; line-height: 1; color: var(--mc-text); }
        .<?php echo esc_attr($unique_id); ?> .mc-t-agg-stars { color: var(--mc-star); font-size: 16px; letter-spacing: 2px; }
        .<?php echo esc_attr($unique_id); ?> .mc-t-agg-count { font-size: 13px; opacity: 0.6; color: var(--mc-text); }

        /* Container Physics */
        .<?php echo esc_attr($unique_id); ?> .mc-t-track {
            padding: 0 5% 20px 5%; /* Bottom padding for shadow clearance */
        }
        
        /* Mode: Grid */
        .<?php echo esc_attr($unique_id); ?>.layout-grid .mc-t-track {
            display: grid; grid-template-columns: repeat(var(--mc-cols), 1fr); gap: 30px;
        }

        /* Mode: Masonry */
        .<?php echo esc_attr($unique_id); ?>.layout-masonry .mc-t-track {
            column-count: var(--mc-cols); column-gap: 30px;
        }
        .<?php echo esc_attr($unique_id); ?>.layout-masonry .mc-t-card {
            break-inside: avoid; margin-bottom: 30px;
        }

        /* Mode: Carousel */
        .<?php echo esc_attr($unique_id); ?>.layout-carousel .mc-t-track {
            display: flex; gap: 30px; overflow-x: auto; scroll-snap-type: x mandatory; scrollbar-width: none;
        }
        .<?php echo esc_attr($unique_id); ?>.layout-carousel .mc-t-track::-webkit-scrollbar { display: none; }
        .<?php echo esc_attr($unique_id); ?>.layout-carousel .mc-t-card {
            flex: 0 0 calc((100% - (var(--mc-cols) - 1) * 30px) / var(--mc-cols)); scroll-snap-align: start;
        }

        /* Mode: Single */
        .<?php echo esc_attr($unique_id); ?>.layout-single .mc-t-track {
            max-width: 800px; margin: 0 auto;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .<?php echo esc_attr($unique_id); ?>.layout-grid .mc-t-track { grid-template-columns: repeat(var(--mc-cols-m), 1fr); gap: 20px; }
            .<?php echo esc_attr($unique_id); ?>.layout-masonry .mc-t-track { column-count: var(--mc-cols-m); }
            .<?php echo esc_attr($unique_id); ?>.layout-carousel .mc-t-card { flex: 0 0 calc(100% / var(--mc-cols-m)); }
        }

        /* Card Geometry */
        .<?php echo esc_attr($unique_id); ?> .mc-t-card {
            background: var(--mc-bg); color: var(--mc-text); padding: 30px; border-radius: var(--mc-rad);
            box-shadow: 0 15px 35px rgba(0,0,0,<?php echo esc_attr($shadow); ?>); border: 1px solid rgba(0,0,0,0.05);
            display: flex; flex-direction: column; transition: transform 0.3s;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-t-card:hover { transform: translateY(-5px); }
        .<?php echo esc_attr($unique_id); ?>.layout-single .mc-t-card { padding: 60px 40px; text-align: center; align-items: center; }

        .<?php echo esc_attr($unique_id); ?> .mc-t-rating { color: var(--mc-star); font-size: 18px; margin-bottom: 20px; letter-spacing: 2px; }
        .<?php echo esc_attr($unique_id); ?> .mc-t-quote { font-size: 16px; line-height: 1.6; font-style: italic; opacity: 0.9; margin: 0 0 25px 0; flex: 1; }
        .<?php echo esc_attr($unique_id); ?>.layout-single .mc-t-quote { font-size: clamp(20px, 3vw, 28px); line-height: 1.4; margin-bottom: 35px; }

        /* Profile */
        .<?php echo esc_attr($unique_id); ?> .mc-t-profile { display: flex; align-items: center; gap: 15px; }
        .<?php echo esc_attr($unique_id); ?>.layout-single .mc-t-profile { justify-content: center; }
        .<?php echo esc_attr($unique_id); ?> .mc-t-avatar { width: 48px; height: 48px; border-radius: 50%; object-fit: cover; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #94a3b8; }
        .<?php echo esc_attr($unique_id); ?> .mc-t-name { font-weight: bold; font-size: 15px; }
        .<?php echo esc_attr($unique_id); ?> .mc-t-title-meta { font-size: 13px; opacity: 0.6; margin-top: 2px; }
    </style>

    <div class="mc-t-header">
        <h2 class="mc-t-title"><?php echo esc_html($title); ?></h2>
        <?php if ($show_agg) : ?>
            <div class="mc-t-agg">
                <span class="mc-t-agg-score"><?php echo esc_html($score); ?></span>
                <div>
                    <div class="mc-t-agg-stars">★★★★★</div>
                    <div class="mc-t-agg-count">based on <?php echo esc_html($count); ?> reviews</div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="mc-t-track">
        <?php foreach ($testimonials as $t) : ?>
            <div class="mc-t-card">
                <?php if ($show_rating) : ?>
                    <div class="mc-t-rating"><?php echo mc_render_stars($t['rating']); ?></div>
                <?php endif; ?>
                
                <p class="mc-t-quote">"<?php echo esc_html($t['quote']); ?>"</p>
                
                <div class="mc-t-profile">
                    <?php if ($show_avatar) : ?>
                        <?php if (!empty($t['avatar'])) : ?>
                            <img src="<?php echo esc_url($t['avatar']); ?>" class="mc-t-avatar" alt="<?php echo esc_attr($t['name']); ?>" />
                        <?php else : ?>
                            <div class="mc-t-avatar"><?php echo esc_html(substr($t['name'], 0, 1)); ?></div>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <div>
                        <div class="mc-t-name"><?php echo esc_html($t['name']); ?></div>
                        <?php if (!empty($t['title'])) : ?>
                            <div class="mc-t-title-meta"><?php echo esc_html($t['title']); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>