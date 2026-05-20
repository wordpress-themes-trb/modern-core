<?php
$layout = $attributes['layoutMode'] ?? 'row';
$cols_d = $attributes['colsDesktop'] ?? 4;
$cols_m = $attributes['colsMobile'] ?? 1;
$pos = $attributes['iconPosition'] ?? 'top';
$style = $attributes['iconStyle'] ?? 'outline';
$size = $attributes['iconSize'] ?? 32;
$head_c = $attributes['headingColor'] ?? '#0f172a';
$text_c = $attributes['textColor'] ?? '#64748b';
$accent = $attributes['accentColor'] ?? '#3b82f6';
$bg = $attributes['cardBg'] ?? '#ffffff';
$features = $attributes['features'] ?? [];

if (empty($features)) return;

$unique_id = uniqid('mc-feat-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "modern-core-feature-list {$unique_id} layout-{$layout} pos-{$pos} style-{$style}"]);

// FIX: Wrap the helper function to prevent redeclaration errors if the block is used multiple times
if (!function_exists('mc_get_feature_svg')) {
    function mc_get_feature_svg($type, $size, $color, $is_filled) {
        $stroke = $is_filled ? '#ffffff' : $color;
        $s = 'width="'.esc_attr($size).'" height="'.esc_attr($size).'" viewBox="0 0 24 24" fill="none" stroke="'.esc_attr($stroke).'" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"';
        
        if ($type === 'truck') return "<svg {$s}><rect x='1' y='3' width='15' height='13'></rect><polygon points='16 8 20 8 23 11 23 16 16 16 16 8'></polygon><circle cx='5.5' cy='18.5' r='2.5'></circle><circle cx='18.5' cy='18.5' r='2.5'></circle></svg>";
        if ($type === 'shield') return "<svg {$s}><path d='M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z'></path></svg>";
        if ($type === 'refresh') return "<svg {$s}><polyline points='23 4 23 10 17 10'></polyline><path d='M20.49 15a9 9 0 1 1-2.12-9.36L23 10'></path></svg>";
        if ($type === 'chat') return "<svg {$s}><path d='M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z'></path></svg>";
        return "<svg {$s}><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'></polygon></svg>";
    }
}

$is_filled = ($style === 'circle' || $style === 'square');
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            --mc-cols: <?php echo esc_attr($cols_d); ?>;
            --mc-cols-m: <?php echo esc_attr($cols_m); ?>;
            --mc-accent: <?php echo esc_attr($accent); ?>;
            --mc-head: <?php echo esc_attr($head_c); ?>;
            --mc-text: <?php echo esc_attr($text_c); ?>;
            --mc-size: <?php echo esc_attr($size); ?>px;
            width: 100%; padding: 40px 0;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-fl-wrapper {
            display: grid; grid-template-columns: repeat(var(--mc-cols), 1fr); gap: 40px 30px;
        }
        .<?php echo esc_attr($unique_id); ?>.layout-list .mc-fl-wrapper {
            display: flex; flex-direction: column; gap: 20px;
        }
        
        @media (max-width: 768px) {
            .<?php echo esc_attr($unique_id); ?> .mc-fl-wrapper { grid-template-columns: repeat(var(--mc-cols-m), 1fr); gap: 30px; }
        }

        /* Item Structural Styles */
        .<?php echo esc_attr($unique_id); ?> .mc-fl-item {
            display: flex; flex-direction: column; text-decoration: none; color: inherit; transition: transform 0.2s;
        }
        .<?php echo esc_attr($unique_id); ?> a.mc-fl-item:hover { transform: translateY(-3px); }
        
        .<?php echo esc_attr($unique_id); ?>.layout-card .mc-fl-item {
            background: <?php echo esc_attr($bg); ?>; padding: 30px; border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.05);
        }
        
        /* Position Logic */
        .<?php echo esc_attr($unique_id); ?>.pos-top .mc-fl-item { align-items: center; text-align: center; }
        .<?php echo esc_attr($unique_id); ?>.pos-left-span .mc-fl-item { flex-direction: row; align-items: flex-start; gap: 20px; }
        .<?php echo esc_attr($unique_id); ?>.pos-left-heading .mc-fl-header { display: flex; align-items: center; gap: 15px; margin-bottom: 8px; }
        
        /* Icon Background Styles */
        .<?php echo esc_attr($unique_id); ?> .mc-fl-icon-box {
            display: flex; align-items: center; justify-content: center;
            width: calc(var(--mc-size) * 1.8); height: calc(var(--mc-size) * 1.8);
            margin-bottom: 20px; flex-shrink: 0;
        }
        .<?php echo esc_attr($unique_id); ?>.pos-left-heading .mc-fl-icon-box { margin-bottom: 0; }
        
        .<?php echo esc_attr($unique_id); ?>.style-circle .mc-fl-icon-box { background: var(--mc-accent); border-radius: 50%; color: #fff; }
        .<?php echo esc_attr($unique_id); ?>.style-square .mc-fl-icon-box { background: var(--mc-accent); border-radius: 12px; color: #fff; }
        .<?php echo esc_attr($unique_id); ?>.style-outline .mc-fl-icon-box { border: 2px solid var(--mc-accent); border-radius: 50%; color: var(--mc-accent); }
        .<?php echo esc_attr($unique_id); ?>.style-none .mc-fl-icon-box { width: var(--mc-size); height: var(--mc-size); color: var(--mc-accent); margin-bottom: 15px; }

        /* Typography */
        .<?php echo esc_attr($unique_id); ?> .mc-fl-title { font-size: 18px; font-weight: bold; color: var(--mc-head); margin: 0 0 8px 0; line-height: 1.2; }
        .<?php echo esc_attr($unique_id); ?>.pos-left-heading .mc-fl-title { margin-bottom: 0; }
        .<?php echo esc_attr($unique_id); ?> .mc-fl-desc { font-size: 15px; color: var(--mc-text); line-height: 1.6; opacity: 0.8; margin: 0; }
    </style>

    <div class="mc-fl-wrapper">
        <?php foreach ($features as $f) : 
            $tag = !empty($f['link']) ? 'a' : 'div';
            $href = !empty($f['link']) ? 'href="' . esc_url($f['link']) . '"' : '';
        ?>
            <<?php echo $tag; ?> <?php echo $href; ?> class="mc-fl-item">
                
                <?php if ($pos === 'left-heading') : ?>
                    <div class="mc-fl-header">
                        <div class="mc-fl-icon-box">
                            <?php if (!empty($f['image'])) : ?>
                                <img src="<?php echo esc_url($f['image']); ?>" style="width: <?php echo esc_attr($size); ?>px; height: <?php echo esc_attr($size); ?>px; object-fit: contain;" alt="<?php echo esc_attr($f['title']); ?>" />
                            <?php else : ?>
                                <?php echo mc_get_feature_svg($f['icon'], $size, $accent, $is_filled); ?>
                            <?php endif; ?>
                        </div>
                        <h4 class="mc-fl-title"><?php echo esc_html($f['title']); ?></h4>
                    </div>
                    <p class="mc-fl-desc"><?php echo esc_html($f['desc']); ?></p>

                <?php else : ?>
                    <div class="mc-fl-icon-box">
                        <?php if (!empty($f['image'])) : ?>
                            <img src="<?php echo esc_url($f['image']); ?>" style="width: <?php echo esc_attr($size); ?>px; height: <?php echo esc_attr($size); ?>px; object-fit: contain;" alt="<?php echo esc_attr($f['title']); ?>" />
                        <?php else : ?>
                            <?php echo mc_get_feature_svg($f['icon'], $size, $accent, $is_filled); ?>
                        <?php endif; ?>
                    </div>
                    <div class="mc-fl-content">
                        <h4 class="mc-fl-title"><?php echo esc_html($f['title']); ?></h4>
                        <p class="mc-fl-desc"><?php echo esc_html($f['desc']); ?></p>
                    </div>
                <?php endif; ?>
                
            </<?php echo $tag; ?>>
        <?php endforeach; ?>
    </div>
</div>