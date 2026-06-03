<?php
$cols_d = $attributes['colsDesktop'] ?? 4;
$cols_m = $attributes['colsMobile'] ?? 2;
$align = $attributes['alignment'] ?? 'center';
$show_dividers = $attributes['showDividers'] ?? true;
$trigger_once = $attributes['triggerOnce'] ?? true;
$use_grad = $attributes['useGradient'] ?? false;
$c1 = $attributes['color1'] ?? '#000000';
$c2 = $attributes['color2'] ?? '#000000';
$num_c = $attributes['numberColor'] ?? '#000000';
$text_c = $attributes['textColor'] ?? '#000000';
$border_c = $attributes['borderColor'] ?? '#e2e8f0';
$stats = $attributes['stats'] ?? [];

if (empty($stats)) return;

$unique_id = uniqid('mc-stats-');
$wrapper_attributes = get_block_wrapper_attributes([
    'class' => "mc-block modern-core-stats-counter {$unique_id} align-{$align}",
    'data-once' => $trigger_once ? 'true' : 'false'
]);

if (!function_exists('mc_render_stat_icon')) {
    function mc_render_stat_icon($stat) {
        $type = $stat['icon'] ?? 'none';
        $custom = $stat['customIcon'] ?? '';
        
        if ($type === 'none') return '';
        if ($type === 'custom' && !empty($custom)) {
            return '<img src="' . esc_url($custom) . '" alt="" class="mc-st-custom-icon" />';
        }

        $s = 'width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mc-st-icon"';
        if ($type === 'users') return "<svg {$s}><path d='M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2'></path><circle cx='9' cy='7' r='4'></circle><path d='M23 21v-2a4 4 0 0 0-3-3.87'></path><path d='M16 3.13a4 4 0 0 1 0 7.75'></path></svg>";
        if ($type === 'award') return "<svg {$s}><circle cx='12' cy='8' r='7'></circle><polyline points='8.21 13.89 7 23 12 20 17 23 15.79 13.88'></polyline></svg>";
        if ($type === 'smile') return "<svg {$s}><circle cx='12' cy='12' r='10'></circle><path d='M8 14s1.5 2 4 2 4-2 4-2'></path><line x1='9' y1='9' x2='9.01' y2='9'></line><line x1='15' y1='9' x2='15.01' y2='9'></line></svg>";
        if ($type === 'star') return "<svg {$s}><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'></polygon></svg>";
        return '';
    }
}
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            --mc-cols: <?php echo esc_attr($cols_d); ?>;
            --mc-cols-m: <?php echo esc_attr($cols_m); ?>;
            --mc-border: <?php echo esc_attr($show_dividers ? $border_c : 'transparent'); ?>;
            width: 100%; max-width: 1200px; margin: 0 auto; padding: 60px 5%; color: <?php echo esc_attr($text_c); ?>;
            box-sizing: border-box;
        }

        .<?php echo esc_attr($unique_id); ?> *,
        .<?php echo esc_attr($unique_id); ?> *::before,
        .<?php echo esc_attr($unique_id); ?> *::after {
            box-sizing: border-box;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-st-grid {
            display: grid; grid-template-columns: repeat(var(--mc-cols), 1fr);
        }

        .<?php echo esc_attr($unique_id); ?> .mc-st-item {
            display: flex; flex-direction: column; padding: 30px;
            align-items: <?php echo $align === 'flex-start' ? 'flex-start' : ($align === 'flex-end' ? 'flex-end' : 'center'); ?>;
            text-align: <?php echo $align === 'flex-start' ? 'left' : ($align === 'flex-end' ? 'right' : 'center'); ?>;
            border-right: 1px solid var(--mc-border);
        }
        
        /* THE FIX: Remove border from row ends AND the absolute last item */
        .<?php echo esc_attr($unique_id); ?> .mc-st-item:nth-child(var(--mc-cols)n),
        .<?php echo esc_attr($unique_id); ?> .mc-st-item:last-child { 
            border-right: none !important; 
        }

        @media (max-width: 768px) {
            .<?php echo esc_attr($unique_id); ?> .mc-st-grid { grid-template-columns: repeat(var(--mc-cols-m), 1fr); }
            .<?php echo esc_attr($unique_id); ?> .mc-st-item { border-right: none !important; border-bottom: 1px solid var(--mc-border); padding: 30px 15px; }
            
            /* Remove bottom border on the last row */
            .<?php echo esc_attr($unique_id); ?> .mc-st-item:nth-last-child(-n+var(--mc-cols-m)),
            .<?php echo esc_attr($unique_id); ?> .mc-st-item:last-child { 
                border-bottom: none !important; 
            }
        }

        /* SVG & Custom Icon Styling */
        .<?php echo esc_attr($unique_id); ?> .mc-st-icon {
            margin-bottom: 20px; color: <?php echo esc_attr($use_grad ? $c1 : $num_c); ?>; display: block;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-st-custom-icon {
            margin-bottom: 20px; width: 36px; height: 36px; object-fit: contain; display: block;
        }

        /* Number Wrappers */
        .<?php echo esc_attr($unique_id); ?> .mc-st-val-wrap {
            font-size: clamp(48px, 4vw, 72px); font-weight: 800; line-height: 1; margin-bottom: 12px; display: flex; align-items: baseline; letter-spacing: -1px;
        }
        
        .<?php echo esc_attr($unique_id); ?> .mc-st-prefix,
        .<?php echo esc_attr($unique_id); ?> .mc-st-suffix {
            font-size: 0.5em; font-weight: 800;
        }
        
        .<?php echo esc_attr($unique_id); ?> .mc-st-number {
            font-variant-numeric: tabular-nums; 
            <?php if ($use_grad) : ?>
                background: linear-gradient(135deg, <?php echo esc_attr($c1); ?>, <?php echo esc_attr($c2); ?>);
                -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            <?php else : ?>
                color: <?php echo esc_attr($num_c); ?>;
            <?php endif; ?>
        }

        .<?php echo esc_attr($unique_id); ?> .mc-st-label {
            font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; opacity: 0.6;
        }
    </style>

    <div class="mc-st-grid">
        <?php foreach ($stats as $s) : ?>
            <div class="mc-st-item">
                <?php echo mc_render_stat_icon($s); ?>
                <div class="mc-st-val-wrap">
                    <?php if (!empty($s['prefix'])) echo '<span class="mc-st-prefix">' . esc_html($s['prefix']) . '</span>'; ?>
                    <span class="mc-st-number" data-target="<?php echo esc_attr($s['number']); ?>" data-duration="<?php echo esc_attr($s['duration']); ?>">0</span>
                    <?php if (!empty($s['suffix'])) echo '<span class="mc-st-suffix">' . esc_html($s['suffix']) . '</span>'; ?>
                </div>
                <div class="mc-st-label"><?php echo esc_html($s['label']); ?></div>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const wrapper = document.querySelector('.<?php echo esc_attr($unique_id); ?>');
            if (!wrapper) return;

            const triggerOnce = wrapper.dataset.once === 'true';
            const numberEls = wrapper.querySelectorAll('.mc-st-number');

            const easeOutCubic = (t) => 1 - Math.pow(1 - t, 3);

            const animateNumber = (el) => {
                const target = parseFloat(el.dataset.target);
                if (isNaN(target)) return;
                
                const duration = parseInt(el.dataset.duration) || 2000;
                
                const isFloat = target % 1 !== 0;
                const decimals = isFloat ? target.toString().split('.')[1].length : 0;
                
                let startTime = null;

                const step = (timestamp) => {
                    if (!startTime) startTime = timestamp;
                    const progress = Math.min((timestamp - startTime) / duration, 1);
                    const easedProgress = easeOutCubic(progress);
                    const current = easedProgress * target;

                    el.innerText = current.toFixed(decimals);

                    if (progress < 1) {
                        window.requestAnimationFrame(step);
                    } else {
                        el.innerText = target.toFixed(decimals);
                    }
                };
                window.requestAnimationFrame(step);
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        numberEls.forEach(el => animateNumber(el));
                        if (triggerOnce) observer.unobserve(wrapper);
                    } else if (!triggerOnce) {
                        numberEls.forEach(el => el.innerText = "0");
                    }
                });
            }, { threshold: 0.2 }); 

            observer.observe(wrapper);
        });
    </script>
</div>