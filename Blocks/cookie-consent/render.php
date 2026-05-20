<?php
$mode = $attributes['consentMode'] ?? 'simple';
$style = $attributes['placementStyle'] ?? 'bar';
$msg = $attributes['message'] ?? 'We use cookies to improve your experience and analyze our traffic.';
$policy = $attributes['policyLink'] ?? '/privacy-policy';
$btn_acc = $attributes['btnAccept'] ?? 'Accept All';
$btn_dec = $attributes['btnDecline'] ?? 'Decline';
$btn_man = $attributes['btnManage'] ?? 'Manage Preferences';
$days = $attributes['cookieDays'] ?? 365;
$bg = $attributes['bgColor'] ?? '#ffffff';
$text_c = $attributes['textColor'] ?? '#0f172a';
$accent = $attributes['accentColor'] ?? '#3b82f6';
$shadow = ($attributes['shadowOpacity'] ?? 10) / 100;

// VERY IMPORTANT: Server-Side Check
// If the cookie exists, do not output the HTML at all to save DOM size.
if (isset($_COOKIE['mc_cookie_consent'])) return;

$unique_id = uniqid('mc-cookie-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "modern-core-cookie-consent {$unique_id} style-{$style}"]);
?>

<div <?php echo $wrapper_attributes; ?> data-days="<?php echo esc_attr($days); ?>">
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            --mc-bg: <?php echo esc_attr($bg); ?>;
            --mc-text: <?php echo esc_attr($text_c); ?>;
            --mc-acc: <?php echo esc_attr($accent); ?>;
            --mc-shad: <?php echo esc_attr($shadow); ?>;
            
            position: fixed; z-index: 999999;
            /* Animation State */
            opacity: 0; pointer-events: none;
            transition: opacity 0.4s ease, transform 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .<?php echo esc_attr($unique_id); ?>.is-visible { opacity: 1; pointer-events: auto; transform: translateY(0) scale(1); }

        /* Style: Bar */
        .<?php echo esc_attr($unique_id); ?>.style-bar {
            bottom: 0; left: 0; width: 100%; background: var(--mc-bg);
            box-shadow: 0 -10px 30px rgba(0,0,0,var(--mc-shad)); transform: translateY(100%);
        }
        .<?php echo esc_attr($unique_id); ?>.style-bar .mc-cookie-inner {
            max-width: 1400px; margin: 0 auto; padding: 20px 5%; display: flex; align-items: center; justify-content: space-between; gap: 40px;
        }

        /* Style: Card */
        .<?php echo esc_attr($unique_id); ?>.style-card {
            bottom: 30px; left: 30px; transform: translateY(20px);
        }
        .<?php echo esc_attr($unique_id); ?>.style-card .mc-cookie-inner {
            background: var(--mc-bg); width: 340px; padding: 25px; border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,var(--mc-shad)); display: flex; flex-direction: column; gap: 20px;
        }

        /* Style: Modal */
        .<?php echo esc_attr($unique_id); ?>.style-modal {
            inset: 0; background: rgba(0,0,0,0.6); display: flex; align-items: center; justify-content: center; padding: 20px;
            backdrop-filter: blur(4px);
        }
        .<?php echo esc_attr($unique_id); ?>.style-modal .mc-cookie-inner {
            background: var(--mc-bg); width: 100%; max-width: 480px; padding: 40px; border-radius: 16px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.2); text-align: center; transform: scale(0.95); transition: transform 0.5s;
        }
        .<?php echo esc_attr($unique_id); ?>.style-modal.is-visible .mc-cookie-inner { transform: scale(1); }

        /* Internal UI */
        .<?php echo esc_attr($unique_id); ?> .mc-cookie-text { font-size: 14px; line-height: 1.5; margin: 0; color: var(--mc-text); flex: 1; }
        .<?php echo esc_attr($unique_id); ?> .mc-cookie-link { color: var(--mc-acc); text-decoration: underline; font-weight: bold; }
        
        .<?php echo esc_attr($unique_id); ?> .mc-cookie-actions { display: flex; gap: 10px; flex-wrap: wrap; }
        .<?php echo esc_attr($unique_id); ?>.style-card .mc-cookie-actions,
        .<?php echo esc_attr($unique_id); ?>.style-modal .mc-cookie-actions { width: 100%; }
        .<?php echo esc_attr($unique_id); ?>.style-card .mc-cookie-actions { flex-direction: column; }
        .<?php echo esc_attr($unique_id); ?>.style-modal .mc-cookie-actions { justify-content: center; }

        .<?php echo esc_attr($unique_id); ?> .mc-btn {
            padding: 12px 24px; border-radius: 6px; font-size: 14px; font-weight: bold; cursor: pointer; border: none; transition: opacity 0.2s; white-space: nowrap; text-align: center;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-btn:hover { opacity: 0.8; }
        
        .<?php echo esc_attr($unique_id); ?> .mc-btn-accept { background: var(--mc-acc); color: #fff; flex: 1; }
        .<?php echo esc_attr($unique_id); ?> .mc-btn-decline { background: transparent; color: var(--mc-text); border: 1px solid rgba(0,0,0,0.1); flex: 1; }
        .<?php echo esc_attr($unique_id); ?> .mc-btn-manage { background: transparent; color: var(--mc-text); text-decoration: underline; padding: 12px 5px; opacity: 0.7; }

        /* Responsive */
        @media (max-width: 768px) {
            .<?php echo esc_attr($unique_id); ?>.style-bar .mc-cookie-inner { flex-direction: column; align-items: flex-start; gap: 20px; }
            .<?php echo esc_attr($unique_id); ?>.style-bar .mc-cookie-actions { width: 100%; flex-direction: column; }
            .<?php echo esc_attr($unique_id); ?>.style-card { bottom: 15px; left: 15px; right: 15px; }
            .<?php echo esc_attr($unique_id); ?>.style-card .mc-cookie-inner { width: auto; }
        }
    </style>

    <div class="mc-cookie-inner">
        <div class="mc-cookie-text">
            <?php echo esc_html($msg); ?>
            <?php if ($policy) : ?>
                <a href="<?php echo esc_url($policy); ?>" class="mc-cookie-link">Privacy Policy</a>
            <?php endif; ?>
        </div>
        <div class="mc-cookie-actions">
            <?php if ($mode === 'category') : ?>
                <button class="mc-btn mc-btn-manage"><?php echo esc_html($btn_man); ?></button>
            <?php endif; ?>
            <button class="mc-btn mc-btn-decline"><?php echo esc_html($btn_dec); ?></button>
            <button class="mc-btn mc-btn-accept"><?php echo esc_html($btn_acc); ?></button>
        </div>
    </div>

    <?php if ($mode === 'category') : ?>
    <div class="mc-cookie-prefs" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000000; align-items: center; justify-content: center; padding: 20px;">
        <div style="background: var(--mc-bg); color: var(--mc-text); padding: 40px; border-radius: 12px; width: 100%; max-width: 500px;">
            <h3 style="margin: 0 0 20px 0; font-size: 20px;">Manage Cookie Preferences</h3>
            <div style="display: flex; flex-direction: column; gap: 15px; margin-bottom: 30px;">
                <label style="display: flex; justify-content: space-between; padding: 15px; border: 1px solid rgba(0,0,0,0.1); border-radius: 8px;">
                    <div><strong>Strictly Necessary</strong><br><span style="font-size: 12px; opacity: 0.7;">Required for the site to function.</span></div>
                    <input type="checkbox" checked disabled />
                </label>
                <label style="display: flex; justify-content: space-between; padding: 15px; border: 1px solid rgba(0,0,0,0.1); border-radius: 8px;">
                    <div><strong>Analytics</strong><br><span style="font-size: 12px; opacity: 0.7;">Helps us understand visitor behavior.</span></div>
                    <input type="checkbox" id="mc-cookie-analytics" checked />
                </label>
                <label style="display: flex; justify-content: space-between; padding: 15px; border: 1px solid rgba(0,0,0,0.1); border-radius: 8px;">
                    <div><strong>Marketing</strong><br><span style="font-size: 12px; opacity: 0.7;">Used to deliver relevant advertisements.</span></div>
                    <input type="checkbox" id="mc-cookie-marketing" checked />
                </label>
            </div>
            <button class="mc-btn mc-btn-accept" id="mc-save-prefs" style="width: 100%;">Save Preferences</button>
        </div>
    </div>
    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const wrapper = document.querySelector('.<?php echo esc_attr($unique_id); ?>');
            if (!wrapper) return;

            // Secondary Client-Side check just to be safe
            if (document.cookie.split(';').some((item) => item.trim().startsWith('mc_cookie_consent='))) {
                wrapper.remove();
                return;
            }

            const days = parseInt(wrapper.dataset.days) || 365;
            const acceptBtn = wrapper.querySelector('.mc-btn-accept');
            const declineBtn = wrapper.querySelector('.mc-btn-decline');
            const manageBtn = wrapper.querySelector('.mc-btn-manage');
            const prefsModal = wrapper.querySelector('.mc-cookie-prefs');
            const savePrefsBtn = wrapper.querySelector('#mc-save-prefs');

            // Trigger Entrance Animation
            setTimeout(() => wrapper.classList.add('is-visible'), 500);

            const setCookieAndClose = (val) => {
                const date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                document.cookie = `mc_cookie_consent=${val}; expires=${date.toUTCString()}; path=/; SameSite=Lax`;
                wrapper.classList.remove('is-visible');
                setTimeout(() => wrapper.remove(), 500);
            };

            if (acceptBtn) acceptBtn.addEventListener('click', () => setCookieAndClose('all'));
            if (declineBtn) declineBtn.addEventListener('click', () => setCookieAndClose('necessary'));

            // Preference Logic
            if (manageBtn && prefsModal) {
                manageBtn.addEventListener('click', () => {
                    prefsModal.style.display = 'flex';
                });
            }

            if (savePrefsBtn) {
                savePrefsBtn.addEventListener('click', () => {
                    const analytics = document.getElementById('mc-cookie-analytics').checked;
                    const marketing = document.getElementById('mc-cookie-marketing').checked;
                    let val = 'necessary';
                    if (analytics && marketing) val = 'all';
                    else if (analytics) val = 'analytics';
                    else if (marketing) val = 'marketing';
                    
                    setCookieAndClose(val);
                });
            }
        });
    </script>
</div>