<?php
$width_mode = $attributes['widthMode'] ?? 'contained';
$behavior = $attributes['behavior'] ?? 'exclusive';
$open_first = $attributes['openFirst'] ?? true;
$icon_style = $attributes['iconStyle'] ?? 'chevron';
$icon_pos = $attributes['iconPosition'] ?? 'right';
$anim_duration = $attributes['animDuration'] ?? 300;
$q_bg = $attributes['questionBg'] ?? '#ffffff';
$a_bg = $attributes['answerBg'] ?? '#ffffff';
$text_c = $attributes['textColor'] ?? '#0f172a';
$accent = $attributes['accentColor'] ?? '#3b82f6';
$border_c = $attributes['borderColor'] ?? '#e2e8f0';
$faqs = $attributes['faqs'] ?? [];

if (empty($faqs)) return;

$unique_id = uniqid('mc-faq-');
$wrapper_attributes = get_block_wrapper_attributes([
    'class' => "mc-block width-" . esc_attr($width_mode) . " modern-core-faq-accordion {$unique_id} pos-{$icon_pos} style-{$icon_style}",
    'data-behavior' => esc_attr($behavior),
    'data-duration' => esc_attr($anim_duration)
]);

// 1. Generate JSON-LD Schema Array
$schema_entities = [];
foreach ($faqs as $faq) {
    $schema_entities[] = [
        '@type' => 'Question',
        'name' => wp_strip_all_tags($faq['question']),
        'acceptedAnswer' => [
            '@type' => 'Answer',
            'text' => wp_strip_all_tags($faq['answer'])
        ]
    ];
}

$schema_json = [
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => $schema_entities
];

// FIX: Wrap the helper function to prevent redeclaration errors if the block is used multiple times
if (!function_exists('mc_get_faq_icon')) {
    function mc_get_faq_icon($style) {
        if ($style === 'plus-minus') {
            return '<div class="mc-faq-icon mc-plus-minus"><span class="mc-pm-v"></span><span class="mc-pm-h"></span></div>';
        }
        return '<div class="mc-faq-icon mc-chevron"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg></div>';
    }
}
?>

<div <?php echo $wrapper_attributes; ?>>
    
    <script type="application/ld+json">
        <?php echo wp_json_encode($schema_json, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
    </script>

    <style>
        .<?php echo esc_attr($unique_id); ?> {
            width: 100%; border-top: 1px solid <?php echo esc_attr($border_c); ?>;
            --mc-text: <?php echo esc_attr($text_c); ?>;
            --mc-accent: <?php echo esc_attr($accent); ?>;
            --mc-border: <?php echo esc_attr($border_c); ?>;
            --mc-qbg: <?php echo esc_attr($q_bg); ?>;
            --mc-abg: <?php echo esc_attr($a_bg); ?>;
            --mc-dur: <?php echo esc_attr($anim_duration); ?>ms;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-faq-item {
            border-bottom: 1px solid var(--mc-border);
        }

        /* Question Row */
        .<?php echo esc_attr($unique_id); ?> .mc-faq-question {
            display: flex; align-items: center; justify-content: space-between;
            padding: 20px; background: var(--mc-qbg); color: var(--mc-text);
            cursor: pointer; transition: color 0.2s; user-select: none;
        }
        .<?php echo esc_attr($unique_id); ?>.pos-left .mc-faq-question { flex-direction: row-reverse; justify-content: flex-end; gap: 15px; }
        
        .<?php echo esc_attr($unique_id); ?> .mc-faq-title { margin: 0; font-size: 16px; font-weight: bold; line-height: 1.4; flex: 1; }
        .<?php echo esc_attr($unique_id); ?> .mc-faq-item.is-open .mc-faq-question { color: var(--mc-accent); }

        /* Icons */
        .<?php echo esc_attr($unique_id); ?> .mc-faq-icon { color: inherit; flex-shrink: 0; transition: transform var(--mc-dur) ease; }
        
        /* Chevron Logic */
        .<?php echo esc_attr($unique_id); ?> .mc-faq-item.is-open .mc-chevron { transform: rotate(180deg); }
        
        /* Plus Minus Logic */
        .<?php echo esc_attr($unique_id); ?> .mc-plus-minus { position: relative; width: 14px; height: 14px; }
        .<?php echo esc_attr($unique_id); ?> .mc-pm-v, .<?php echo esc_attr($unique_id); ?> .mc-pm-h { position: absolute; background: currentColor; top: 50%; left: 50%; transform: translate(-50%, -50%); transition: transform var(--mc-dur) ease, opacity var(--mc-dur) ease; }
        .<?php echo esc_attr($unique_id); ?> .mc-pm-h { width: 100%; height: 2px; }
        .<?php echo esc_attr($unique_id); ?> .mc-pm-v { width: 2px; height: 100%; }
        .<?php echo esc_attr($unique_id); ?> .mc-faq-item.is-open .mc-pm-v { transform: translate(-50%, -50%) rotate(90deg); opacity: 0; }
        .<?php echo esc_attr($unique_id); ?> .mc-faq-item.is-open .mc-pm-h { transform: translate(-50%, -50%) rotate(180deg); }

        /* Answer Area */
        .<?php echo esc_attr($unique_id); ?> .mc-faq-answer-wrap {
            max-height: 0; overflow: hidden; transition: max-height var(--mc-dur) cubic-bezier(0.4, 0, 0.2, 1);
            background: var(--mc-abg);
        }
        .<?php echo esc_attr($unique_id); ?> .mc-faq-answer {
            padding: 0 20px 20px 20px; color: var(--mc-text); font-size: 15px; line-height: 1.6; opacity: 0.9;
        }
        .<?php echo esc_attr($unique_id); ?>.pos-left .mc-faq-answer { padding-left: 50px; }
    </style>

    <?php foreach ($faqs as $index => $faq) : 
        $is_open_class = ($open_first && $index === 0) ? 'is-open' : '';
    ?>
        <div class="mc-faq-item <?php echo $is_open_class; ?>">
            <div class="mc-faq-question">
                <h4 class="mc-faq-title"><?php echo esc_html($faq['question']); ?></h4>
                <?php echo mc_get_faq_icon($icon_style); ?>
            </div>
            <div class="mc-faq-answer-wrap" style="<?php echo ($open_first && $index === 0) ? 'max-height: none;' : ''; ?>">
                <div class="mc-faq-answer">
                    <?php echo wp_kses_post(nl2br($faq['answer'])); ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const wrapper = document.querySelector('.<?php echo esc_attr($unique_id); ?>');
            if (!wrapper) return;

            const behavior = wrapper.dataset.behavior; // 'exclusive' or 'independent'
            const items = wrapper.querySelectorAll('.mc-faq-item');

            // Initialize correct max-heights for pre-opened items
            items.forEach(item => {
                if (item.classList.contains('is-open')) {
                    const answerWrap = item.querySelector('.mc-faq-answer-wrap');
                    answerWrap.style.maxHeight = answerWrap.scrollHeight + 'px';
                }
            });

            items.forEach(item => {
                const question = item.querySelector('.mc-faq-question');
                const answerWrap = item.querySelector('.mc-faq-answer-wrap');

                question.addEventListener('click', () => {
                    const isOpen = item.classList.contains('is-open');

                    if (behavior === 'exclusive') {
                        // Close all others
                        items.forEach(otherItem => {
                            if (otherItem !== item && otherItem.classList.contains('is-open')) {
                                otherItem.classList.remove('is-open');
                                otherItem.querySelector('.mc-faq-answer-wrap').style.maxHeight = '0px';
                            }
                        });
                    }

                    if (isOpen) {
                        item.classList.remove('is-open');
                        answerWrap.style.maxHeight = '0px';
                    } else {
                        item.classList.add('is-open');
                        answerWrap.style.maxHeight = answerWrap.scrollHeight + 'px';
                        
                        // Handle window resize reflows
                        const resizeObserver = new ResizeObserver(() => {
                            if (item.classList.contains('is-open')) {
                                answerWrap.style.maxHeight = answerWrap.scrollHeight + 'px';
                            }
                        });
                        resizeObserver.observe(answerWrap.querySelector('.mc-faq-answer'));
                    }
                });
            });
        });
    </script>
</div>