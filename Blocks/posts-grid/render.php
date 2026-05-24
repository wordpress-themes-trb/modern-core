<?php
$width_mode = $attributes['widthMode'] ?? 'contained';
$title = $attributes['sectionTitle'] ?? 'From the blog';
$link = $attributes['sectionLink'] ?? '';
$count = $attributes['queryCount'] ?? 6;
$post_type = $attributes['postType'] ?? 'post';
$layout = $attributes['layoutMode'] ?? 'grid';
$cols_d = $attributes['colsDesktop'] ?? 3;
$ratio = $attributes['imageRatio'] ?? '16/9';
$show_cat = $attributes['showCategory'] ?? true;
$show_date = $attributes['showDate'] ?? true;
$show_author = $attributes['showAuthor'] ?? true;
$show_excerpt = $attributes['showExcerpt'] ?? true;
$show_read_time = $attributes['showReadTime'] ?? true;
$excerpt_len = $attributes['excerptLength'] ?? 15;
$radius = $attributes['cardRadius'] ?? 8;
$accent = $attributes['accentColor'] ?? '#0f172a';
$text_c = $attributes['textColor'] ?? '#64748b';

$unique_id = uniqid('mc-posts-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "mc-block width-" . esc_attr($width_mode) . " modern-core-posts-grid {$unique_id} layout-{$layout}"]);

// Query Execution
$args = [
    'post_type' => $post_type,
    'posts_per_page' => $count,
    'post_status' => 'publish',
    'ignore_sticky_posts' => false
];

// Exclude current post if rendering inside a Single Post template
global $post;
if (is_single() && $post) {
    $args['post__not_in'] = [$post->ID];
}

$query = new WP_Query($args);
if (!$query->have_posts()) return;

// FIX: Wrap helper function to prevent redeclaration fatal errors
if (!function_exists('mc_get_read_time')) {
    function mc_get_read_time($content) {
        $word_count = str_word_count(strip_tags($content));
        $minutes = ceil($word_count / 200); // Avg reading speed: 200 wpm
        return max(1, $minutes) . ' min read';
    }
}
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            --mc-cols: <?php echo esc_attr($cols_d); ?>;
            --mc-ratio: <?php echo esc_attr($ratio); ?>;
            --mc-radius: <?php echo esc_attr($radius); ?>px;
            --mc-accent: <?php echo esc_attr($accent); ?>;
            --mc-text: <?php echo esc_attr($text_c); ?>;
            width: 100%; padding: 60px 0;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-post-header {
            display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 40px; padding: 0 5%;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-post-header h2 { margin: 0; font-size: clamp(24px, 4vw, 36px); font-weight: 800; color: #0f172a; }
        .<?php echo esc_attr($unique_id); ?> .mc-post-view-all { color: var(--mc-accent); font-weight: bold; font-size: 14px; text-decoration: none; transition: opacity 0.2s; }
        .<?php echo esc_attr($unique_id); ?> .mc-post-view-all:hover { opacity: 0.7; }

        .<?php echo esc_attr($unique_id); ?> .mc-post-track { padding: 0 5%; }
        
        /* Grid & Featured Mode */
        .<?php echo esc_attr($unique_id); ?>.layout-grid .mc-post-track,
        .<?php echo esc_attr($unique_id); ?>.layout-featured .mc-post-track {
            display: grid; grid-template-columns: repeat(var(--mc-cols), 1fr); gap: 40px 30px;
        }

        /* Magazine Cover CSS Mathematics */
        @media (min-width: 769px) {
            .<?php echo esc_attr($unique_id); ?>.layout-featured .mc-post-track .mc-post-card:first-child { grid-column: span 2; grid-row: span 2; }
            .<?php echo esc_attr($unique_id); ?>.layout-featured .mc-post-track .mc-post-card:first-child .mc-post-img-wrap { height: 100%; aspect-ratio: auto; min-height: 400px; }
            .<?php echo esc_attr($unique_id); ?>.layout-featured .mc-post-track .mc-post-card:first-child .mc-post-title { font-size: 36px; }
            .<?php echo esc_attr($unique_id); ?>.layout-featured .mc-post-track .mc-post-card:first-child .mc-post-excerpt { font-size: 16px; }
        }

        /* List Mode */
        .<?php echo esc_attr($unique_id); ?>.layout-list .mc-post-track { display: flex; flex-direction: column; gap: 40px; }
        .<?php echo esc_attr($unique_id); ?>.layout-list .mc-post-card { display: flex; gap: 40px; align-items: center; }
        .<?php echo esc_attr($unique_id); ?>.layout-list .mc-post-img-wrap { width: 40%; min-width: 300px; flex-shrink: 0; margin-bottom: 0; }

        /* Card Elements */
        .<?php echo esc_attr($unique_id); ?> .mc-post-card { display: flex; flex-direction: column; text-decoration: none; color: inherit; transition: transform 0.3s; }
        .<?php echo esc_attr($unique_id); ?> .mc-post-card:hover { transform: translateY(-4px); }
        
        .<?php echo esc_attr($unique_id); ?> .mc-post-img-wrap {
            width: 100%; aspect-ratio: var(--mc-ratio); border-radius: var(--mc-radius); overflow: hidden; position: relative; background: #f1f5f9; margin-bottom: 20px;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-post-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1); }
        .<?php echo esc_attr($unique_id); ?> .mc-post-card:hover .mc-post-img { transform: scale(1.05); }
        
        .<?php echo esc_attr($unique_id); ?> .mc-post-pill {
            position: absolute; top: 15px; left: 15px; background: var(--mc-accent); color: #fff;
            font-size: 11px; font-weight: bold; padding: 6px 12px; border-radius: 50px; text-transform: uppercase; letter-spacing: 1px;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-post-meta { font-size: 12px; color: var(--mc-text); opacity: 0.8; display: flex; flex-wrap: wrap; gap: 15px; margin-bottom: 12px; }
        .<?php echo esc_attr($unique_id); ?> .mc-post-title { font-size: 22px; font-weight: bold; color: #0f172a; margin: 0 0 12px 0; line-height: 1.3; }
        .<?php echo esc_attr($unique_id); ?> .mc-post-excerpt { font-size: 15px; color: var(--mc-text); opacity: 0.8; line-height: 1.6; margin: 0; }

        @media (max-width: 768px) {
            .<?php echo esc_attr($unique_id); ?>.layout-grid .mc-post-track,
            .<?php echo esc_attr($unique_id); ?>.layout-featured .mc-post-track { grid-template-columns: 1fr; }
            .<?php echo esc_attr($unique_id); ?>.layout-list .mc-post-card { flex-direction: column; align-items: flex-start; gap: 20px; }
            .<?php echo esc_attr($unique_id); ?>.layout-list .mc-post-img-wrap { width: 100%; min-width: 0; }
        }
    </style>

    <?php if ($title || $link) : ?>
        <div class="mc-post-header">
            <h2><?php echo esc_html($title); ?></h2>
            <?php if ($link) : ?>
                <a href="<?php echo esc_url($link); ?>" class="mc-post-view-all">View all articles →</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="mc-post-track">
        <?php while ($query->have_posts()) : $query->the_post(); 
            $permalink = get_permalink();
            $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'large') ?: wc_placeholder_img_src();
            
            $cat_name = '';
            if ($show_cat) {
                $categories = get_the_category();
                if (!empty($categories)) $cat_name = $categories[0]->name;
            }
        ?>
            <a href="<?php echo esc_url($permalink); ?>" class="mc-post-card">
                <div class="mc-post-img-wrap">
                    <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="mc-post-img" loading="lazy" />
                    <?php if ($show_cat && $cat_name) echo '<div class="mc-post-pill">' . esc_html($cat_name) . '</div>'; ?>
                </div>
                
                <div class="mc-post-content">
                    <div class="mc-post-meta">
                        <?php if ($show_date) echo '<span>' . get_the_date() . '</span>'; ?>
                        <?php if ($show_author) echo '<span>By ' . get_the_author() . '</span>'; ?>
                        <?php if ($show_read_time) echo '<span>' . mc_get_read_time(get_the_content()) . '</span>'; ?>
                    </div>
                    
                    <h3 class="mc-post-title"><?php the_title(); ?></h3>
                    
                    <?php if ($show_excerpt) : ?>
                        <p class="mc-post-excerpt"><?php echo wp_trim_words(get_the_excerpt(), $excerpt_len, '...'); ?></p>
                    <?php endif; ?>
                </div>
            </a>
        <?php endwhile; wp_reset_postdata(); ?>
    </div>
</div>