<?php
$show_toggle = $attributes['showToggle'] ?? true;
$discount_label = $attributes['discountLabel'] ?? 'Save 20%';
$radius = $attributes['cardRadius'] ?? 12;
$accent = $attributes['accentColor'] ?? '#3b82f6';
$text_c = $attributes['textColor'] ?? '#0f172a';
$bg_base = $attributes['bgBase'] ?? '#ffffff';
$bg_feat = $attributes['bgFeatured'] ?? '#f8fafc';
$tiers = $attributes['tiers'] ?? [];

if (empty($tiers)) return;

$unique_id = uniqid('mc-price-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "modern-core-pricing-table {$unique_id}"]);

function mc_pricing_check() {
    return '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="mc-pt-icon check"><polyline points="20 6 9 17 4 12"></polyline></svg>';
}
function mc_pricing_cross() {
    return '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mc-pt-icon cross"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>';
}
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            --mc-rad: <?php echo esc_attr($radius); ?>px;
            --mc-acc: <?php echo esc_attr($accent); ?>;
            --mc-txt: <?php echo esc_attr($text_c); ?>;
            --mc-bg: <?php echo esc_attr($bg_base); ?>;
            --mc-bg-feat: <?php echo esc_attr($bg_feat); ?>;
            width: 100%; padding: 80px 0; color: var(--mc-txt);
        }

        /* Toggle UI */
        .<?php echo esc_attr($unique_id); ?> .mc-pt-toggle-wrap { display: flex; justify-content: center; align-items: center; gap: 15px; margin-bottom: 60px; }
        .<?php echo esc_attr($unique_id); ?> .mc-pt-label { font-size: 16px; font-weight: bold; opacity: 0.6; transition: opacity 0.3s; cursor: pointer; user-select: none; }
        .<?php echo esc_attr($unique_id); ?> .mc-pt-label.is-active { opacity: 1; }
        
        .<?php echo esc_attr($unique_id); ?> .mc-pt-switch {
            width: 64px; height: 34px; background: rgba(0,0,0,0.1); border-radius: 50px; position: relative; cursor: pointer; transition: background 0.3s;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-pt-switch.is-annual { background: var(--mc-acc); }
        .<?php echo esc_attr($unique_id); ?> .mc-pt-knob {
            width: 28px; height: 28px; background: #fff; border-radius: 50%; position: absolute; top: 3px; left: 3px;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .<?php echo esc_attr($unique_id); ?> .mc-pt-switch.is-annual .mc-pt-knob { transform: translateX(30px); }

        .<?php echo esc_attr($unique_id); ?> .mc-pt-badge {
            background: var(--mc-acc); color: #fff; font-size: 11px; padding: 4px 10px; border-radius: 50px; font-weight: bold; margin-left: 8px;
        }

        /* Grid & Cards */
        .<?php echo esc_attr($unique_id); ?> .mc-pt-grid {
            display: grid; grid-template-columns: repeat(<?php echo count($tiers); ?>, 1fr); gap: 30px; max-width: 1200px; margin: 0 auto; padding: 0 5%; align-items: center;
        }
        @media (max-width: 900px) {
            .<?php echo esc_attr($unique_id); ?> .mc-pt-grid { grid-template-columns: 1fr; align-items: stretch; gap: 40px; }
        }

        .<?php echo esc_attr($unique_id); ?> .mc-pt-card {
            background: var(--mc-bg); padding: 40px 30px; border-radius: var(--mc-rad); box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.05);
            display: flex; flex-direction: column; position: relative; transition: transform 0.3s;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-pt-card.is-featured {
            background: var(--mc-bg-feat); transform: scale(1.05); box-shadow: 0 20px 40px rgba(0,0,0,0.1); border: 2px solid var(--mc-acc); z-index: 10;
        }
        @media (max-width: 900px) { .<?php echo esc_attr($unique_id); ?> .mc-pt-card.is-featured { transform: scale(1); } }

        /* Typography inside Card */
        .<?php echo esc_attr($unique_id); ?> .mc-pt-feat-badge {
            position: absolute; top: -14px; left: 50%; transform: translateX(-50%); background: var(--mc-acc); color: #fff;
            font-size: 12px; font-weight: bold; padding: 6px 20px; border-radius: 50px; white-space: nowrap; text-transform: uppercase; letter-spacing: 1px;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-pt-name { margin: 0 0 10px 0; font-size: 24px; font-weight: 800; }
        .<?php echo esc_attr($unique_id); ?> .mc-pt-desc { font-size: 15px; opacity: 0.8; margin: 0 0 25px 0; line-height: 1.5; min-height: 45px; }
        
        /* Price Animation Area */
        .<?php echo esc_attr($unique_id); ?> .mc-pt-price-wrap { display: flex; align-items: baseline; gap: 5px; margin-bottom: 30px; }
        .<?php echo esc_attr($unique_id); ?> .mc-pt-currency { font-size: 24px; font-weight: bold; }
        .<?php echo esc_attr($unique_id); ?> .mc-pt-amount { font-size: 56px; font-weight: 900; line-height: 1; font-variant-numeric: tabular-nums; }
        .<?php echo esc_attr($unique_id); ?> .mc-pt-period { opacity: 0.6; font-size: 16px; font-weight: bold; }

        /* Features List */
        .<?php echo esc_attr($unique_id); ?> .mc-pt-features { display: flex; flexDirection: column; gap: 15px; flex: 1; margin-bottom: 35px; }
        .<?php echo esc_attr($unique_id); ?> .mc-pt-feature { display: flex; align-items: flex-start; gap: 12px; font-size: 16px; line-height: 1.4; }
        .<?php echo esc_attr($unique_id); ?> .mc-pt-feature.is-excluded { opacity: 0.5; text-decoration: line-through; }
        .<?php echo esc_attr($unique_id); ?> .mc-pt-icon { flex-shrink: 0; margin-top: 2px; }

        /* Button */
        .<?php echo esc_attr($unique_id); ?> .mc-pt-btn {
            display: block; width: 100%; padding: 16px; text-align: center; border-radius: 8px; font-weight: bold; text-decoration: none; font-size: 16px; transition: opacity 0.2s;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-pt-btn:hover { opacity: 0.8; }
        .<?php echo esc_attr($unique_id); ?> .mc-pt-card.is-featured .mc-pt-btn { background: var(--mc-acc); color: #fff; border: none; }
        .<?php echo esc_attr($unique_id); ?> .mc-pt-card:not(.is-featured) .mc-pt-btn { background: transparent; border: 2px solid var(--mc-acc); color: var(--mc-acc); }
    </style>

    <?php if ($show_toggle) : ?>
        <div class="mc-pt-toggle-wrap">
            <span class="mc-pt-label label-monthly is-active">Monthly</span>
            <div class="mc-pt-switch" role="switch" aria-checked="false">
                <div class="mc-pt-knob"></div>
            </div>
            <span class="mc-pt-label label-annual">
                Annually <span class="mc-pt-badge"><?php echo esc_html($discount_label); ?></span>
            </span>
        </div>
    <?php endif; ?>

    <div class="mc-pt-grid">
        <?php foreach ($tiers as $tier) : 
            $is_feat = $tier['isFeatured'] ?? false;
        ?>
            <div class="mc-pt-card <?php echo $is_feat ? 'is-featured' : ''; ?>">
                <?php if ($is_feat && !empty($tier['badge'])) : ?>
                    <div class="mc-pt-feat-badge"><?php echo esc_html($tier['badge']); ?></div>
                <?php endif; ?>
                
                <h3 class="mc-pt-name"><?php echo esc_html($tier['name']); ?></h3>
                <p class="mc-pt-desc"><?php echo esc_html($tier['desc']); ?></p>
                
                <div class="mc-pt-price-wrap">
                    <span class="mc-pt-currency">$</span>
                    <span class="mc-pt-amount" 
                          data-monthly="<?php echo esc_attr($tier['priceMonthly']); ?>" 
                          data-annual="<?php echo esc_attr($tier['priceAnnual']); ?>">
                        <?php echo esc_html($tier['priceMonthly']); ?>
                    </span>
                    <span class="mc-pt-period">/mo</span>
                </div>

                <div class="mc-pt-features">
                    <?php foreach ($tier['features'] as $feat) : 
                        $included = $feat['included'] ?? true;
                    ?>
                        <div class="mc-pt-feature <?php echo $included ? '' : 'is-excluded'; ?>">
                            <?php echo $included ? mc_pricing_check() : mc_pricing_cross(); ?>
                            <span><?php echo esc_html($feat['text']); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <a href="#" class="mc-pt-btn"><?php echo esc_html($tier['button'] ?? 'Select Plan'); ?></a>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ($show_toggle) : ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const wrapper = document.querySelector('.<?php echo esc_attr($unique_id); ?>');
            if (!wrapper) return;

            const toggleWrap = wrapper.querySelector('.mc-pt-toggle-wrap');
            const switcher = wrapper.querySelector('.mc-pt-switch');
            const labelM = wrapper.querySelector('.label-monthly');
            const labelA = wrapper.querySelector('.label-annual');
            const amounts = wrapper.querySelectorAll('.mc-pt-amount');

            let isAnnual = false;

            const updatePricing = () => {
                isAnnual = !isAnnual;
                
                // UI Updates
                switcher.classList.toggle('is-annual', isAnnual);
                switcher.setAttribute('aria-checked', isAnnual);
                labelM.classList.toggle('is-active', !isAnnual);
                labelA.classList.toggle('is-active', isAnnual);

                // Math & Animation Updates
                amounts.forEach(amount => {
                    const targetVal = isAnnual ? amount.dataset.annual : amount.dataset.monthly;
                    
                    // Simple fade animation for the number swap
                    amount.style.opacity = '0';
                    setTimeout(() => {
                        amount.innerText = targetVal;
                        amount.style.opacity = '1';
                    }, 150);
                });
            };

            // Allow clicking the switch or the labels directly
            if (toggleWrap) {
                toggleWrap.addEventListener('click', updatePricing);
                // Setup CSS transition for the fade
                amounts.forEach(amount => amount.style.transition = 'opacity 0.15s ease');
            }
        });
    </script>
    <?php endif; ?>
</div>