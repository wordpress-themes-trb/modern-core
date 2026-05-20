<?php
$align = $attributes['alignment'] ?? 'center';
$style = $attributes['badgeStyle'] ?? 'mono-dark';
$size = $attributes['badgeSize'] ?? 48;
$gap = $attributes['gap'] ?? 24;
$mobile_behavior = $attributes['mobileBehavior'] ?? 'wrap';
$badges = $attributes['badges'] ?? [];

if (empty($badges)) return;

$unique_id = uniqid('mc-tb-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "modern-core-trust-badges {$unique_id} style-{$style}"]);

// Helper to render native SVGs based on Style
function mc_get_trust_badge_svg($type, $style, $size) {
    $is_filled = ($style === 'filled');
    $stroke = $is_filled ? '#10b981' : 'currentColor'; // Emerald green for filled outline
    $fill = $is_filled ? '#d1fae5' : 'none'; // Light emerald for filled bg
    
    $s = 'width="'.esc_attr($size).'" height="'.esc_attr($size).'" viewBox="0 0 24 24" fill="'.esc_attr($fill).'" stroke="'.esc_attr($stroke).'" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"';
    
    if ($type === 'ssl') return "<svg {$s}><rect x='3' y='11' width='18' height='11' rx='2' ry='2' fill='{$fill}'></rect><path d='M7 11V7a5 5 0 0 1 10 0v4'></path></svg>";
    if ($type === 'guarantee') {
        $g_stroke = $is_filled ? '#f59e0b' : 'currentColor'; // Amber for guarantee
        $g_fill = $is_filled ? '#fef3c7' : 'none';
        $s = 'width="'.esc_attr($size).'" height="'.esc_attr($size).'" viewBox="0 0 24 24" fill="'.$g_fill.'" stroke="'.$g_stroke.'" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"';
        return "<svg {$s}><circle cx='12' cy='8' r='7'></circle><polyline points='8.21 13.89 7 23 12 20 17 23 15.79 13.88'></polyline></svg>";
    }
    if ($type === 'shipping') return "<svg {$s}><rect x='1' y='3' width='15' height='13'></rect><polygon points='16 8 20 8 23 11 23 16 16 16 16 8'></polygon><circle cx='5.5' cy='18.5' r='2.5' fill='".($is_filled?'#10b981':'none')."'></circle><circle cx='18.5' cy='18.5' r='2.5' fill='".($is_filled?'#10b981':'none')."'></circle></svg>";
    if ($type === 'payment') return "<svg {$s}><rect x='1' y='4' width='22' height='16' rx='2' ry='2'></rect><line x1='1' y1='10' x2='23' y2='10'></line></svg>";
    if ($type === 'returns') return "<svg {$s}><polyline points='1 4 1 10 7 10'></polyline><path d='M3.51 15a9 9 0 1 0 2.13-9.36L1 10'></path></svg>";
    
    return "<svg {$s}><circle cx='12' cy='12' r='10'></circle><path d='M12 8v4l3 3'></path></svg>";
}
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            --mc-gap: <?php echo esc_attr($gap); ?>px;
            --mc-size: <?php echo esc_attr($size); ?>px;
            width: 100%; padding: 20px 0;
        }

        /* Color Styles */
        .<?php echo esc_attr($unique_id); ?>.style-mono-dark { color: #0f172a; }
        .<?php echo esc_attr($unique_id); ?>.style-mono-light { color: #ffffff; }
        .<?php echo esc_attr($unique_id); ?>.style-filled { color: #0f172a; }

        .<?php echo esc_attr($unique_id); ?> .mc-tb-track {
            display: flex; gap: var(--mc-gap); justify-content: <?php echo esc_attr($align); ?>; align-items: flex-start;
        }

        /* Mobile Behaviors */
        <?php if ($mobile_behavior === 'wrap') : ?>
            .<?php echo esc_attr($unique_id); ?> .mc-tb-track { flex-wrap: wrap; }
            @media (max-width: 768px) {
                .<?php echo esc_attr($unique_id); ?> .mc-tb-track { justify-content: center; } /* Always center on mobile when wrapping */
            }
        <?php else : ?>
            .<?php echo esc_attr($unique_id); ?> .mc-tb-track { flex-wrap: nowrap; overflow-x: auto; scrollbar-width: none; scroll-snap-type: x mandatory; padding-bottom: 10px; }
            .<?php echo esc_attr($unique_id); ?> .mc-tb-track::-webkit-scrollbar { display: none; }
            .<?php echo esc_attr($unique_id); ?> .mc-tb-item { scroll-snap-align: start; flex-shrink: 0; }
        <?php endif; ?>

        .<?php echo esc_attr($unique_id); ?> .mc-tb-item { display: flex; flex-direction: column; align-items: center; text-align: center; gap: 8px; }
        .<?php echo esc_attr($unique_id); ?> .mc-tb-icon { display: flex; align-items: center; justify-content: center; width: var(--mc-size); height: var(--mc-size); }
        .<?php echo esc_attr($unique_id); ?> .mc-tb-text { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.8; max-width: calc(var(--mc-size) + 40px); line-height: 1.2; }
    </style>

    <div class="mc-tb-track">
        <?php foreach ($badges as $b) : ?>
            <div class="mc-tb-item">
                <div class="mc-tb-icon">
                    <?php if ($b['type'] === 'custom' && !empty($b['customImage'])) : ?>
                        <img src="<?php echo esc_url($b['customImage']); ?>" style="width: 100%; height: 100%; object-fit: contain;" alt="<?php echo esc_attr($b['text']); ?>" loading="lazy" />
                    <?php else : ?>
                        <?php echo mc_get_trust_badge_svg($b['type'], $style, $size); ?>
                    <?php endif; ?>
                </div>
                <?php if (!empty($b['showText']) && !empty($b['text'])) : ?>
                    <div class="mc-tb-text"><?php echo esc_html($b['text']); ?></div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>