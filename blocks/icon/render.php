<?php
$width_mode = $attributes['widthMode'] ?? 'contained';
$name = $attributes['iconName'] ?? 'star';
$custom = $attributes['customSvg'] ?? '';
$pres = $attributes['presentation'] ?? 'standalone';
$shape = $attributes['shape'] ?? 'circle';
$size = $attributes['iconSize'] ?? 32;
$pad = $attributes['bgPadding'] ?? 16;
$border = $attributes['borderWidth'] ?? 2;
$text = $attributes['text'] ?? '';
$t_pos = $attributes['textPosition'] ?? 'right';
$link = $attributes['link'] ?? '';
$hover = $attributes['hoverEffect'] ?? 'scale';
$icon_c = $attributes['iconColor'] ?? '#3b82f6';
$bg_c = $attributes['bgColor'] ?? 'transparent';
$border_c = $attributes['borderColor'] ?? '#e2e8f0';
$text_c = $attributes['textColor'] ?? '#0f172a';

$unique_id = uniqid('mc-icon-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "mc-block width-" . esc_attr($width_mode) . " modern-core-icon {$unique_id} p-{$pres} s-{$shape} t-{$t_pos} h-{$hover}"]);

// Wrap function to prevent fatal errors on multiple usages
if (!function_exists('mc_get_core_icon_svg')) {
    function mc_get_core_icon_svg($id, $custom) {
        if ($id === 'custom' && !empty($custom)) return wp_kses_post($custom); // Render Raw custom if provided

        $base = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">';
        switch ($id) {
            case 'star': return $base . '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
            case 'cart': return $base . '<circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>';
            case 'truck': return $base . '<rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>';
            case 'lock': return $base . '<rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>';
            case 'phone': return $base . '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>';
            case 'email': return $base . '<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>';
            case 'user': return $base . '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>';
            case 'check': return $base . '<polyline points="20 6 9 17 4 12"></polyline></svg>';
            case 'arrow-right': return $base . '<line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>';
            default: return $base . '<circle cx="12" cy="12" r="10"></circle></svg>';
        }
    }
}
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            --ic-size: <?php echo esc_attr($size); ?>px;
            --ic-pad: <?php echo esc_attr($pad); ?>px;
            --ic-bw: <?php echo esc_attr($border); ?>px;
            --ic-col: <?php echo esc_attr($icon_c); ?>;
            --ic-bg: <?php echo esc_attr($bg_c); ?>;
            --ic-border: <?php echo esc_attr($border_c); ?>;
            --ic-txt: <?php echo esc_attr($text_c); ?>;
            display: inline-block;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-ic-wrap {
            display: flex; align-items: center; gap: 15px; text-decoration: none;
        }
        
        .<?php echo esc_attr($unique_id); ?>.t-below .mc-ic-wrap { flex-direction: column; text-align: center; gap: 10px; }
        .<?php echo esc_attr($unique_id); ?>.t-left .mc-ic-wrap { flex-direction: row-reverse; }

        .<?php echo esc_attr($unique_id); ?> .mc-ic-graphic {
            width: calc(var(--ic-size) + (var(--ic-pad) * 2)); height: calc(var(--ic-size) + (var(--ic-pad) * 2));
            display: flex; align-items: center; justify-content: center;
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); flex-shrink: 0;
        }
        
        .<?php echo esc_attr($unique_id); ?> .mc-ic-graphic svg {
            width: var(--ic-size); height: var(--ic-size); color: var(--ic-col); display: block;
        }

        /* Presentation */
        .<?php echo esc_attr($unique_id); ?>.p-filled .mc-ic-graphic { background: var(--ic-bg); }
        .<?php echo esc_attr($unique_id); ?>.p-outlined .mc-ic-graphic { border: var(--ic-bw) solid var(--ic-border); }
        
        /* Shape */
        .<?php echo esc_attr($unique_id); ?>.s-circle .mc-ic-graphic { border-radius: 50%; }
        .<?php echo esc_attr($unique_id); ?>.s-rounded .mc-ic-graphic { border-radius: 8px; }
        .<?php echo esc_attr($unique_id); ?>.s-square .mc-ic-graphic { border-radius: 0; }

        /* Text */
        .<?php echo esc_attr($unique_id); ?> .mc-ic-text { color: var(--ic-txt); font-weight: bold; font-size: 15px; margin: 0; }

        /* Hover Animations */
        <?php if (!empty($link)) : ?>
            .<?php echo esc_attr($unique_id); ?>:hover.h-scale .mc-ic-graphic { transform: scale(1.1); }
            .<?php echo esc_attr($unique_id); ?>:hover.h-rotate .mc-ic-graphic { transform: rotate(15deg); }
        <?php endif; ?>
    </style>

    <?php 
    $tag = !empty($link) ? 'a' : 'div';
    $href = !empty($link) ? 'href="'.esc_url($link).'"' : '';
    ?>

    <<?php echo $tag; ?> <?php echo $href; ?> class="mc-ic-wrap">
        <div class="mc-ic-graphic">
            <?php echo mc_get_core_icon_svg($name, $custom); ?>
        </div>
        <?php if (!empty($text)) : ?>
            <div class="mc-ic-text"><?php echo esc_html($text); ?></div>
        <?php endif; ?>
    </<?php echo $tag; ?>>
</div>