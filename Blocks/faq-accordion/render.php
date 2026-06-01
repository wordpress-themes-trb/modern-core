<?php
$layout_mode = $attributes['layoutMode'] ?? 'flush';
$width_mode = $attributes['widthMode'] ?? 'contained';
$aos_anim = $attributes['aosAnimation'] ?? 'fade-up';
$behavior = $attributes['behavior'] ?? 'exclusive';
$open_first = $attributes['openFirst'] ?? true;
$icon_style = $attributes['iconStyle'] ?? 'chevron';
$icon_pos = $attributes['iconPosition'] ?? 'right';
$anim_duration = $attributes['animDuration'] ?? 300;
$q_bg = $attributes['questionBg'] ?? '#ffffff';
$a_bg = $attributes['answerBg'] ?? '#ffffff';
$text_c = $attributes['textColor'] ?? '#0f172a';
$accent = $attributes['accentColor'] ?? '#000000';
$border_c = $attributes['borderColor'] ?? '#e2e8f0';
$faqs = $attributes['faqs'] ?? [];

if (empty($faqs)) return;

$unique_id = uniqid('mc-faq-');
$wrapper_attributes = get_block_wrapper_attributes([
    'id' => esc_attr($unique_id),
    'class' => "mc-block width-" . esc_attr($width_mode) . " modern-core-faq-accordion layout-{$layout_mode} pos-{$icon_pos}",
    'data-behavior' => esc_attr($behavior),
    'data-aos' => esc_attr($aos_anim)
]);

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

if (!function_exists('mc_get_faq_icon')) {
    function mc_get_faq_icon($style) {
        if ($style === 'plus-minus') {
            return '<div class="mc-faq-icon mc-plus-minus"><span class="mc-pm-h"></span><span class="mc-pm-v"></span></div>';
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
        #<?php echo esc_attr($unique_id); ?> {
            width: 100%;
            --mc-text: <?php echo esc_attr($text_c); ?>;
            --mc-accent: <?php echo esc_attr($accent); ?>;
            --mc-border: <?php echo esc_attr($border_c); ?>;
            --mc-qbg: <?php echo esc_attr($q_bg); ?>;
            --mc-abg: <?php echo esc_attr($a_bg); ?>;
            --mc-dur: <?php echo esc_attr($anim_duration); ?>ms;
        }

        /* ── LAYOUT MODES ───────────────────────── */
        #<?php echo esc_attr($unique_id); ?>.layout-flush .mc-faq-wrapper {
            border-top: 1px solid var(--mc-border);
        }
        #<?php echo esc_attr($unique_id); ?>.layout-flush .mc-faq-item {
            border-bottom: 1px solid var(--mc-border);
        }
        
        #<?php echo esc_attr($unique_id); ?>.layout-elevated .mc-faq-wrapper {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.08);
            border: 1px solid var(--mc-border);
            overflow: hidden;
        }
        #<?php echo esc_attr($unique_id); ?>.layout-elevated .mc-faq-item:not(:last-child) {
            border-bottom: 1px solid var(--mc-border);
        }

        #<?php echo esc_attr($unique_id); ?>.layout-separated .mc-faq-wrapper {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        #<?php echo esc_attr($unique_id); ?>.layout-separated .mc-faq-item {
            background: var(--mc-qbg);
            border-radius: 8px;
            border: 1px solid var(--mc-border);
            overflow: hidden;
        }

        /* ── QUESTION ROW ───────────────────────── */
        #<?php echo esc_attr($unique_id); ?> .mc-faq-question {
            display: flex; align-items: center; justify-content: space-between;
            padding: 20px 24px; background: var(--mc-qbg); color: var(--mc-text);
            cursor: pointer; transition: color 0.2s ease; user-select: none;
        }
        #<?php echo esc_attr($unique_id); ?>.layout-separated .mc-faq-question { padding: 24px; }
        #<?php echo esc_attr($unique_id); ?>.pos-left .mc-faq-question { flex-direction: row-reverse; justify-content: flex-end; gap: 16px; }
        
        #<?php echo esc_attr($unique_id); ?> .mc-faq-title { margin: 0; font-size: 17px; font-weight: 600; letter-spacing: -0.01em; line-height: 1.4; flex: 1; }
        #<?php echo esc_attr($unique_id); ?> .mc-faq-item.is-open .mc-faq-question { color: var(--mc-accent); }

        /* ── ICONS ──────────────────────────────── */
        #<?php echo esc_attr($unique_id); ?> .mc-faq-icon { color: inherit; flex-shrink: 0; transition: transform var(--mc-dur) ease; }
        #<?php echo esc_attr($unique_id); ?> .mc-faq-item.is-open .mc-chevron { transform: rotate(180deg); }
        
        #<?php echo esc_attr($unique_id); ?> .mc-plus-minus { position: relative; width: 14px; height: 14px; }
        #<?php echo esc_attr($unique_id); ?> .mc-pm-v, #<?php echo esc_attr($unique_id); ?> .mc-pm-h { position: absolute; background: currentColor; top: 50%; left: 50%; transform: translate(-50%, -50%); transition: transform var(--mc-dur) ease, opacity var(--mc-dur) ease; }
        #<?php echo esc_attr($unique_id); ?> .mc-pm-h { width: 100%; height: 2px; }
        #<?php echo esc_attr($unique_id); ?> .mc-pm-v { width: 2px; height: 100%; }
        #<?php echo esc_attr($unique_id); ?> .mc-faq-item.is-open .mc-pm-v { transform: translate(-50%, -50%) rotate(90deg); opacity: 0; }
        #<?php echo esc_attr($unique_id); ?> .mc-faq-item.is-open .mc-pm-h { transform: translate(-50%, -50%) rotate(180deg); }

        /* ── CSS GRID ANSWER TRANSITION ─────────── */
        #<?php echo esc_attr($unique_id); ?> .mc-faq-answer-wrap {
            display: grid;
            grid-template-rows: 0fr;
            transition: grid-template-rows var(--mc-dur) cubic-bezier(0.4, 0, 0.2, 1);
            background: var(--mc-abg);
        }
        #<?php echo esc_attr($unique_id); ?> .mc-faq-item.is-open .mc-faq-answer-wrap {
            grid-template-rows: 1fr;
        }
        #<?php echo esc_attr($unique_id); ?> .mc-faq-answer-inner {
            overflow: hidden;
        }
        #<?php echo esc_attr($unique_id); ?> .mc-faq-answer {
            padding: 0 24px 24px 24px; color: var(--mc-text); font-size: 16px; line-height: 1.6; opacity: 0; transition: opacity var(--mc-dur) ease;
        }
        #<?php echo esc_attr($unique_id); ?> .mc-faq-item.is-open .mc-faq-answer {
            opacity: 0.9;
        }
        #<?php echo esc_attr($unique_id); ?>.pos-left .mc-faq-answer { padding-left: 54px; }
    </style>

    <div class="mc-faq-wrapper">
        <?php foreach ($faqs as $index => $faq) : 
            $is_open_class = ($open_first && $index === 0) ? 'is-open' : '';
        ?>
            <div class="mc-faq-item <?php echo $is_open_class; ?>">
                <div class="mc-faq-question" role="button" aria-expanded="<?php echo $is_open_class ? 'true' : 'false'; ?>">
                    <h4 class="mc-faq-title"><?php echo esc_html($faq['question']); ?></h4>
                    <?php echo mc_get_faq_icon($icon_style); ?>
                </div>
                <div class="mc-faq-answer-wrap">
                    <div class="mc-faq-answer-inner">
                        <div class="mc-faq-answer">
                            <?php echo wp_kses_post(nl2br($faq['answer'])); ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var uid = <?php echo wp_json_encode($unique_id); ?>;
            var wrapper = document.getElementById(uid);
            if (!wrapper) return;

            var behavior = wrapper.dataset.behavior;
            var items = wrapper.querySelectorAll('.mc-faq-item');

            items.forEach(function(item) {
                var question = item.querySelector('.mc-faq-question');

                question.addEventListener('click', function() {
                    var isOpen = item.classList.contains('is-open');

                    if (behavior === 'exclusive') {
                        items.forEach(function(otherItem) {
                            if (otherItem !== item) {
                                otherItem.classList.remove('is-open');
                                otherItem.querySelector('.mc-faq-question').setAttribute('aria-expanded', 'false');
                            }
                        });
                    }

                    if (isOpen) {
                        item.classList.remove('is-open');
                        question.setAttribute('aria-expanded', 'false');
                    } else {
                        item.classList.add('is-open');
                        question.setAttribute('aria-expanded', 'true');
                    }
                });
            });
        });
    </script>
</div>