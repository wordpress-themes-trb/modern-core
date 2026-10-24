<?php
/**
 * Cookie Consent Block — render.php
 *
 * Storage: document.cookie (primary) + localStorage (backup) + window event (GTM)
 * Gate mode: blurs or hides page content until consent is given
 * Strictly Necessary: always accepted, non-negotiable
 * Preferences modal: teleported to <body> so it never breaks parent layout
 */

$width_mode      = $attributes['widthMode']       ?? 'contained';
$mode            = $attributes['consentMode']      ?? 'simple';
$style           = $attributes['placementStyle']   ?? 'bar';
$gate_mode       = $attributes['gateMode']         ?? false;
$gate_style      = $attributes['gateModeStyle']    ?? 'blur';
$show_overlay    = $attributes['showModalOverlay'] ?? false;
$msg             = $attributes['message']          ?? 'We use cookies to improve your experience and analyze our traffic.';
$policy          = $attributes['policyLink']       ?? '/privacy-policy';
$policy_text     = $attributes['policyLinkText']   ?? 'Privacy Policy';
$btn_acc         = $attributes['btnAccept']        ?? 'Accept All';
$btn_dec         = $attributes['btnDecline']       ?? 'Decline';
$btn_man         = $attributes['btnManage']        ?? 'Manage Preferences';
$days            = $attributes['cookieDays']       ?? 365;
$bg              = $attributes['bgColor']          ?? '#ffffff';
$text_c          = $attributes['textColor']        ?? '#0f172a';
$accent          = $attributes['accentColor']      ?? '#3b82f6';
$shadow          = ( $attributes['shadowOpacity']  ?? 10 ) / 100;

// Server-side check — skip rendering if already consented
// (saves DOM overhead on every page load for returning visitors)
if ( isset( $_COOKIE['mc_cookie_consent'] ) ) return;

$uid = 'mc-cookie-' . uniqid();
$prefs_uid = $uid . '-prefs';

$wrapper_attributes = get_block_wrapper_attributes( [
    'class'       => "modern-core-cookie-consent {$uid} style-{$style}",
    'data-days'   => esc_attr( $days ),
    'data-mode'   => esc_attr( $mode ),
    'data-gate'   => $gate_mode   ? 'true' : 'false',
    'data-gstyle' => esc_attr( $gate_style ),
    'aria-live'   => 'polite',
    'role'        => 'dialog',
    'aria-label'  => 'Cookie consent',
] );
?>

<div <?php echo $wrapper_attributes; ?>>
<style>
/* ── BASE: hidden until JS adds .is-visible ─────────────── */
.<?php echo esc_attr($uid); ?> {
    --mc-bg:   <?php echo esc_attr($bg); ?>;
    --mc-text: <?php echo esc_attr($text_c); ?>;
    --mc-acc:  <?php echo esc_attr($accent); ?>;
    --mc-shad: <?php echo esc_attr($shadow); ?>;

    position:       fixed;
    z-index:        999999;
    opacity:        0;
    pointer-events: none;
    transition:     opacity 0.4s ease, transform 0.5s cubic-bezier(0.165,0.84,0.44,1);
    box-sizing:     border-box;
}
.<?php echo esc_attr($uid); ?>.is-visible {
    opacity:        1;
    pointer-events: auto;
    transform:      translateY(0) !important;
}

/* ── STYLE: BAR ─────────────────────────────────────────── */
.<?php echo esc_attr($uid); ?>.style-bar {
    bottom:     0;
    left:       0;
    width:      100%;
    background: var(--mc-bg);
    box-shadow: 0 -6px 30px rgba(0,0,0,var(--mc-shad));
    transform:  translateY(100%);
    border-top: 1px solid rgba(0,0,0,0.06);
}
.<?php echo esc_attr($uid); ?>.style-bar .mc-cookie-inner {
    max-width:       1400px;
    margin:          0 auto;
    padding:         18px clamp(16px, 5%, 60px);
    display:         flex;
    align-items:     center;
    justify-content: space-between;
    gap:             30px;
    flex-wrap:       wrap;
}

/* ── STYLE: CARD ────────────────────────────────────────── */
.<?php echo esc_attr($uid); ?>.style-card {
    bottom:    30px;
    left:      30px;
    transform: translateY(20px);
    width:     340px;
}
.<?php echo esc_attr($uid); ?>.style-card .mc-cookie-inner {
    background:    var(--mc-bg);
    padding:       28px;
    border-radius: 16px;
    box-shadow:    0 20px 50px rgba(0,0,0,var(--mc-shad));
    display:       flex;
    flex-direction:column;
    gap:           18px;
}

/* ── STYLE: MODAL ───────────────────────────────────────── */
.<?php echo esc_attr($uid); ?>.style-modal {
    inset:           0;
    display:         flex;
    align-items:     center;
    justify-content: center;
    padding:         20px;
    <?php if ($show_overlay): ?>
    background:      rgba(0,0,0,0.55);
    backdrop-filter: blur(4px);
    <?php endif; ?>
}
.<?php echo esc_attr($uid); ?>.style-modal .mc-cookie-inner {
    background:    var(--mc-bg);
    width:         100%;
    max-width:     480px;
    padding:       clamp(28px, 5vw, 44px);
    border-radius: 20px;
    box-shadow:    0 30px 60px rgba(0,0,0,0.15);
    display:       flex;
    flex-direction:column;
    gap:           20px;
    position:      relative;
    transform:     scale(0.95);
    transition:    transform 0.4s cubic-bezier(0.165,0.84,0.44,1);
}
.<?php echo esc_attr($uid); ?>.style-modal.is-visible .mc-cookie-inner {
    transform: scale(1);
}

/* ── CLOSE BUTTON (all styles) ──────────────────────────── */
.<?php echo esc_attr($uid); ?> .mc-close-btn {
    position:   absolute;
    top:        16px;
    right:      16px;
    width:      32px;
    height:     32px;
    border:     none;
    background: rgba(0,0,0,0.06);
    border-radius: 50%;
    cursor:     pointer;
    font-size:  16px;
    display:    flex;
    align-items:center;
    justify-content:center;
    color:      var(--mc-text);
    transition: background 0.2s;
    flex-shrink:0;
    line-height:1;
    padding:    0;
}
.<?php echo esc_attr($uid); ?> .mc-close-btn:hover { background: rgba(0,0,0,0.12); }

/* ── INNER CONTENT ──────────────────────────────────────── */
.<?php echo esc_attr($uid); ?> .mc-cookie-header {
    display:     flex;
    align-items: flex-start;
    gap:         12px;
}
.<?php echo esc_attr($uid); ?> .mc-cookie-icon {
    font-size:  24px;
    flex-shrink:0;
    line-height:1;
}
.<?php echo esc_attr($uid); ?> .mc-cookie-title {
    font-size:   16px;
    font-weight: 700;
    margin:      0 0 4px;
    color:       var(--mc-text);
}
.<?php echo esc_attr($uid); ?> .mc-cookie-text {
    font-size:   13px;
    line-height: 1.6;
    color:       var(--mc-text);
    opacity:     0.8;
    margin:      0;
    flex:        1;
}
.<?php echo esc_attr($uid); ?> .mc-cookie-link {
    color:           var(--mc-acc);
    text-decoration: underline;
    font-weight:     600;
}

/* ── ACTIONS ────────────────────────────────────────────── */
.<?php echo esc_attr($uid); ?> .mc-cookie-actions {
    display:  flex;
    gap:      10px;
    flex-wrap:wrap;
}
.<?php echo esc_attr($uid); ?>.style-card .mc-cookie-actions,
.<?php echo esc_attr($uid); ?>.style-modal .mc-cookie-actions {
    flex-direction: column;
}
.<?php echo esc_attr($uid); ?>.style-bar .mc-cookie-actions {
    flex-shrink: 0;
}

/* ── BUTTONS ────────────────────────────────────────────── */
.<?php echo esc_attr($uid); ?> .mc-btn {
    padding:       11px 22px;
    border-radius: 8px;
    font-size:     13px;
    font-weight:   700;
    cursor:        pointer;
    border:        none;
    transition:    opacity 0.2s, transform 0.15s;
    white-space:   nowrap;
    text-align:    center;
    box-sizing:    border-box;
}
.<?php echo esc_attr($uid); ?> .mc-btn:hover  { opacity: 0.88; transform: translateY(-1px); }
.<?php echo esc_attr($uid); ?> .mc-btn:active { transform: translateY(0); }

.<?php echo esc_attr($uid); ?> .mc-btn-accept  { background: var(--mc-acc); color: #fff; }
.<?php echo esc_attr($uid); ?> .mc-btn-decline {
    background: transparent;
    color:      var(--mc-text);
    border:     1px solid rgba(0,0,0,0.15);
}
.<?php echo esc_attr($uid); ?> .mc-btn-manage {
    background:      transparent;
    color:           var(--mc-acc);
    text-decoration: underline;
    padding:         8px 0;
    font-size:       13px;
    font-weight:     600;
    text-align:      left;
    border:          none;
    cursor:          pointer;
}

/* ── GATE MODE ──────────────────────────────────────────── */
<?php if ( $gate_mode ) : ?>
body.mc-gate-active {
    overflow: hidden;
}
<?php if ( $gate_style === 'blur' ) : ?>
body.mc-gate-active .wp-site-blocks > *:not(.modern-core-cookie-consent):not(#<?php echo esc_attr($uid); ?>-prefs-portal) {
    filter:         blur(8px);
    pointer-events: none;
    user-select:    none;
    transition:     filter 0.5s ease;
}
<?php elseif ( $gate_style === 'blackout' ) : ?>
body.mc-gate-active::after {
    content:    '';
    position:   fixed;
    inset:      0;
    background: rgba(0,0,0,0.85);
    z-index:    999998;
}
<?php endif; ?>
<?php endif; ?>

/* ── MOBILE ─────────────────────────────────────────────── */
@media (max-width: 640px) {
    .<?php echo esc_attr($uid); ?>.style-bar .mc-cookie-inner {
        flex-direction: column;
        align-items:    flex-start;
        gap:            14px;
        padding:        16px;
    }
    .<?php echo esc_attr($uid); ?>.style-bar .mc-cookie-actions { width: 100%; flex-direction: column; }
    .<?php echo esc_attr($uid); ?>.style-card { bottom: 10px; left: 10px; right: 10px; width: auto; }
    .<?php echo esc_attr($uid); ?>.style-modal .mc-cookie-inner { padding: 24px 20px; }
}
</style>

    <div class="mc-cookie-inner">

        <?php /* Close button — shown on all styles */ ?>
        <button class="mc-close-btn" aria-label="Close">&#10005;</button>

        <div class="mc-cookie-header">
            <span class="mc-cookie-icon">🍪</span>
            <div>
                <p class="mc-cookie-title">We value your privacy</p>
                <p class="mc-cookie-text">
                    <?php echo esc_html( $msg ); ?>
                    <?php if ( $policy ) : ?>
                        <a href="<?php echo esc_url( $policy ); ?>" class="mc-cookie-link" target="_blank" rel="noopener">
                            <?php echo esc_html( $policy_text ); ?>
                        </a>
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <div class="mc-cookie-actions">
            <?php if ( $mode === 'category' ) : ?>
                <button class="mc-btn mc-btn-manage" data-action="manage">
                    <?php echo esc_html( $btn_man ); ?>
                </button>
            <?php endif; ?>
            <button class="mc-btn mc-btn-decline" data-action="decline">
                <?php echo esc_html( $btn_dec ); ?>
            </button>
            <button class="mc-btn mc-btn-accept" data-action="accept">
                <?php echo esc_html( $btn_acc ); ?>
            </button>
        </div>
    </div>
</div>

<?php if ( $mode === 'category' ) : ?>
<?php
/*
 * PREFERENCES MODAL — teleported to <body> via JS so it NEVER
 * inherits any overflow/stacking from the banner's DOM position.
 * This is why the old inline approach broke the card layout.
 */
?>
<div id="<?php echo esc_attr( $prefs_uid ); ?>" style="display:none;" aria-hidden="true">
<style>
#<?php echo esc_attr($prefs_uid); ?>-portal {
    position:        fixed;
    inset:           0;
    z-index:         9999999;
    display:         flex;
    align-items:     center;
    justify-content: center;
    padding:         20px;
    background:      rgba(0,0,0,0.5);
    backdrop-filter: blur(4px);
    opacity:         0;
    pointer-events:  none;
    transition:      opacity 0.3s ease;
    box-sizing:      border-box;
}
#<?php echo esc_attr($prefs_uid); ?>-portal.is-open {
    opacity:        1;
    pointer-events: auto;
}
#<?php echo esc_attr($prefs_uid); ?>-portal .mc-prefs-card {
    background:    <?php echo esc_attr($bg); ?>;
    color:         <?php echo esc_attr($text_c); ?>;
    width:         100%;
    max-width:     520px;
    border-radius: 20px;
    box-shadow:    0 30px 60px rgba(0,0,0,0.2);
    overflow:      hidden;
    position:      relative;
    transform:     translateY(16px);
    transition:    transform 0.35s cubic-bezier(0.165,0.84,0.44,1);
}
#<?php echo esc_attr($prefs_uid); ?>-portal.is-open .mc-prefs-card {
    transform: translateY(0);
}
#<?php echo esc_attr($prefs_uid); ?>-portal .mc-prefs-head {
    padding:       28px 28px 20px;
    border-bottom: 1px solid rgba(0,0,0,0.08);
    display:       flex;
    align-items:   center;
    justify-content:space-between;
}
#<?php echo esc_attr($prefs_uid); ?>-portal .mc-prefs-head h3 {
    margin:      0;
    font-size:   18px;
    font-weight: 700;
}
#<?php echo esc_attr($prefs_uid); ?>-portal .mc-prefs-close {
    width:         32px;
    height:        32px;
    border:        none;
    background:    rgba(0,0,0,0.06);
    border-radius: 50%;
    cursor:        pointer;
    font-size:     16px;
    display:       flex;
    align-items:   center;
    justify-content:center;
    color:         <?php echo esc_attr($text_c); ?>;
    flex-shrink:   0;
    transition:    background 0.2s;
    padding:       0;
    line-height:   1;
}
#<?php echo esc_attr($prefs_uid); ?>-portal .mc-prefs-close:hover { background: rgba(0,0,0,0.12); }
#<?php echo esc_attr($prefs_uid); ?>-portal .mc-prefs-body {
    padding:        20px 28px;
    display:        flex;
    flex-direction: column;
    gap:            12px;
    max-height:     50vh;
    overflow-y:     auto;
}
#<?php echo esc_attr($prefs_uid); ?>-portal .mc-pref-row {
    display:       flex;
    align-items:   center;
    justify-content:space-between;
    gap:           16px;
    padding:       16px;
    border:        1px solid rgba(0,0,0,0.08);
    border-radius: 12px;
    background:    rgba(0,0,0,0.015);
}
#<?php echo esc_attr($prefs_uid); ?>-portal .mc-pref-info strong {
    display:     block;
    font-size:   14px;
    font-weight: 700;
    margin-bottom:3px;
}
#<?php echo esc_attr($prefs_uid); ?>-portal .mc-pref-info span {
    font-size: 12px;
    opacity:   0.65;
    line-height:1.4;
}
/* Toggle switch */
#<?php echo esc_attr($prefs_uid); ?>-portal .mc-toggle {
    position:   relative;
    width:      44px;
    height:     24px;
    flex-shrink:0;
}
#<?php echo esc_attr($prefs_uid); ?>-portal .mc-toggle input { opacity: 0; width: 0; height: 0; }
#<?php echo esc_attr($prefs_uid); ?>-portal .mc-toggle-slider {
    position:      absolute;
    inset:         0;
    background:    rgba(0,0,0,0.15);
    border-radius: 99px;
    cursor:        pointer;
    transition:    background 0.25s;
}
#<?php echo esc_attr($prefs_uid); ?>-portal .mc-toggle-slider::before {
    content:       '';
    position:      absolute;
    width:         18px;
    height:        18px;
    left:          3px;
    top:           3px;
    background:    #fff;
    border-radius: 50%;
    transition:    transform 0.25s;
    box-shadow:    0 1px 4px rgba(0,0,0,0.2);
}
#<?php echo esc_attr($prefs_uid); ?>-portal .mc-toggle input:checked + .mc-toggle-slider {
    background: <?php echo esc_attr($accent); ?>;
}
#<?php echo esc_attr($prefs_uid); ?>-portal .mc-toggle input:checked + .mc-toggle-slider::before {
    transform: translateX(20px);
}
#<?php echo esc_attr($prefs_uid); ?>-portal .mc-toggle input:disabled + .mc-toggle-slider {
    opacity: 0.5;
    cursor:  not-allowed;
}
/* Footer */
#<?php echo esc_attr($prefs_uid); ?>-portal .mc-prefs-foot {
    padding:       20px 28px;
    border-top:    1px solid rgba(0,0,0,0.08);
    display:       flex;
    gap:           10px;
    flex-direction:column;
}
#<?php echo esc_attr($prefs_uid); ?>-portal .mc-prefs-accept-all {
    background:    <?php echo esc_attr($accent); ?>;
    color:         #fff;
    padding:       13px;
    border:        none;
    border-radius: 10px;
    font-size:     14px;
    font-weight:   700;
    cursor:        pointer;
    width:         100%;
    transition:    opacity 0.2s;
}
#<?php echo esc_attr($prefs_uid); ?>-portal .mc-prefs-accept-all:hover { opacity: 0.88; }
#<?php echo esc_attr($prefs_uid); ?>-portal .mc-prefs-save {
    background:    transparent;
    color:         <?php echo esc_attr($text_c); ?>;
    padding:       11px;
    border:        1px solid rgba(0,0,0,0.15);
    border-radius: 10px;
    font-size:     13px;
    font-weight:   700;
    cursor:        pointer;
    width:         100%;
    transition:    background 0.2s;
}
#<?php echo esc_attr($prefs_uid); ?>-portal .mc-prefs-save:hover { background: rgba(0,0,0,0.04); }
@media (max-width: 540px) {
    #<?php echo esc_attr($prefs_uid); ?>-portal .mc-prefs-card { border-radius: 16px 16px 0 0; align-self: flex-end; }
    #<?php echo esc_attr($prefs_uid); ?>-portal { align-items: flex-end; }
}
</style>
    <!-- hidden template — cloned into body portal by JS -->
    <template>
        <div id="<?php echo esc_attr($prefs_uid); ?>-portal">
            <div class="mc-prefs-card" role="dialog" aria-modal="true" aria-label="Cookie preferences">
                <div class="mc-prefs-head">
                    <h3>Cookie Preferences</h3>
                    <button class="mc-prefs-close" aria-label="Close preferences">&#10005;</button>
                </div>
                <div class="mc-prefs-body">
                    <!-- Strictly Necessary — always on, locked -->
                    <div class="mc-pref-row">
                        <div class="mc-pref-info">
                            <strong>Strictly Necessary</strong>
                            <span>Required for the site to function. Cannot be disabled.</span>
                        </div>
                        <label class="mc-toggle" aria-label="Strictly necessary cookies — always on">
                            <input type="checkbox" checked disabled />
                            <span class="mc-toggle-slider"></span>
                        </label>
                    </div>
                    <!-- Analytics -->
                    <div class="mc-pref-row">
                        <div class="mc-pref-info">
                            <strong>Analytics</strong>
                            <span>Helps us understand how visitors interact with our site.</span>
                        </div>
                        <label class="mc-toggle" aria-label="Analytics cookies">
                            <input type="checkbox" id="<?php echo esc_attr($prefs_uid); ?>-analytics" checked />
                            <span class="mc-toggle-slider"></span>
                        </label>
                    </div>
                    <!-- Marketing -->
                    <div class="mc-pref-row">
                        <div class="mc-pref-info">
                            <strong>Marketing</strong>
                            <span>Used to deliver relevant ads and track campaign performance.</span>
                        </div>
                        <label class="mc-toggle" aria-label="Marketing cookies">
                            <input type="checkbox" id="<?php echo esc_attr($prefs_uid); ?>-marketing" checked />
                            <span class="mc-toggle-slider"></span>
                        </label>
                    </div>
                    <!-- Personalization -->
                    <div class="mc-pref-row">
                        <div class="mc-pref-info">
                            <strong>Personalization</strong>
                            <span>Remembers your preferences for a tailored experience.</span>
                        </div>
                        <label class="mc-toggle" aria-label="Personalization cookies">
                            <input type="checkbox" id="<?php echo esc_attr($prefs_uid); ?>-personalization" checked />
                            <span class="mc-toggle-slider"></span>
                        </label>
                    </div>
                </div>
                <div class="mc-prefs-foot">
                    <button class="mc-prefs-accept-all">Accept All Cookies</button>
                    <button class="mc-prefs-save">Save My Preferences</button>
                </div>
            </div>
        </div>
    </template>
</div>
<?php endif; ?>

<script>
(function () {
    'use strict';

    const COOKIE_NAME  = 'mc_cookie_consent';
    const STORAGE_KEY  = 'mc_cookie_consent';
    const uid          = '<?php echo esc_js($uid); ?>';
    const prefsUid     = '<?php echo esc_js($prefs_uid); ?>';
    const days         = parseInt('<?php echo esc_js($days); ?>') || 365;
    const gateMode     = <?php echo $gate_mode ? 'true' : 'false'; ?>;
    const consentMode  = '<?php echo esc_js($mode); ?>';

    const banner = document.getElementById(uid) || document.querySelector('.' + uid);
    if (!banner) return;

    // ── Already consented? ──────────────────────────────────
    function getStoredConsent() {
        // Check cookie first
        const match = document.cookie.split(';').find(c => c.trim().startsWith(COOKIE_NAME + '='));
        if (match) return match.split('=')[1].trim();
        // Fallback: localStorage
        try { return localStorage.getItem(STORAGE_KEY); } catch(e) { return null; }
    }

    if (getStoredConsent()) {
        banner.remove();
        return;
    }

    // ── Gate mode: lock page until consent ─────────────────
    if (gateMode) {
        document.body.classList.add('mc-gate-active');
    }

    // ── Show banner ─────────────────────────────────────────
    requestAnimationFrame(() => {
        setTimeout(() => banner.classList.add('is-visible'), 300);
    });

    // ── Consent storage ─────────────────────────────────────
    function storeConsent(value, categories) {
        // 1. Cookie (works server-side for PHP checks)
        const exp = new Date();
        exp.setTime(exp.getTime() + days * 86400000);
        document.cookie = COOKIE_NAME + '=' + value + '; expires=' + exp.toUTCString() + '; path=/; SameSite=Lax';

        // 2. localStorage (client-side fallback + richer data)
        const data = {
            consent:    value,
            categories: categories || {},
            timestamp:  new Date().toISOString(),
            expires:    exp.toISOString(),
        };
        try { localStorage.setItem(STORAGE_KEY, JSON.stringify(data)); } catch(e) {}

        // 3. Custom DOM event — picked up by GTM / analytics scripts
        document.dispatchEvent(new CustomEvent('mc:cookieConsent', {
            detail: data,
            bubbles: true,
        }));

        // 4. Google Consent Mode v2 (if gtag present)
        if (typeof gtag === 'function') {
            gtag('consent', 'update', {
                analytics_storage:     categories && categories.analytics  ? 'granted' : 'denied',
                ad_storage:            categories && categories.marketing  ? 'granted' : 'denied',
                personalization_storage: categories && categories.personalization ? 'granted' : 'denied',
                functionality_storage: 'granted', // strictly necessary
                security_storage:      'granted',
            });
        }
    }

    // ── Close / dismiss banner ───────────────────────────────
    function dismissBanner() {
        if (gateMode) document.body.classList.remove('mc-gate-active');
        banner.classList.remove('is-visible');
        setTimeout(() => banner.remove(), 500);
        // Also close prefs portal if open
        const portal = document.getElementById(prefsUid + '-portal');
        if (portal) portal.classList.remove('is-open');
    }

    // ── Accept all ──────────────────────────────────────────
    function acceptAll() {
        storeConsent('all', {
            necessary:       true,
            analytics:       true,
            marketing:       true,
            personalization: true,
        });
        dismissBanner();
    }

    // ── Decline (necessary only) ────────────────────────────
    function declineAll() {
        storeConsent('necessary', {
            necessary:       true, // always true — non-negotiable
            analytics:       false,
            marketing:       false,
            personalization: false,
        });
        dismissBanner();
    }

    // ── Button listeners ─────────────────────────────────────
    banner.querySelector('[data-action="accept"]')?.addEventListener('click', acceptAll);
    banner.querySelector('[data-action="decline"]')?.addEventListener('click', declineAll);
    banner.querySelector('.mc-close-btn')?.addEventListener('click', declineAll);

    // ── Preferences modal (category mode) ───────────────────
    if (consentMode === 'category') {
        const templateHost = document.getElementById(prefsUid);
        const tpl = templateHost?.querySelector('template');

        // Teleport to <body> so it never inherits overflow from banner
        let portal = null;
        if (tpl) {
            const clone = tpl.content.cloneNode(true);
            document.body.appendChild(clone);
            portal = document.getElementById(prefsUid + '-portal');
        }

        // Open prefs
        banner.querySelector('[data-action="manage"]')?.addEventListener('click', () => {
            if (portal) portal.classList.add('is-open');
        });

        // Close prefs (X button inside prefs modal)
        if (portal) {
            portal.querySelector('.mc-prefs-close')?.addEventListener('click', () => {
                portal.classList.remove('is-open');
            });

            // Close on backdrop click
            portal.addEventListener('click', (e) => {
                if (e.target === portal) portal.classList.remove('is-open');
            });

            // Accept all from prefs
            portal.querySelector('.mc-prefs-accept-all')?.addEventListener('click', () => {
                portal.classList.remove('is-open');
                acceptAll();
            });

            // Save selected preferences
            portal.querySelector('.mc-prefs-save')?.addEventListener('click', () => {
                const analytics       = portal.querySelector('#' + prefsUid + '-analytics')?.checked       ?? false;
                const marketing       = portal.querySelector('#' + prefsUid + '-marketing')?.checked       ?? false;
                const personalization = portal.querySelector('#' + prefsUid + '-personalization')?.checked ?? false;

                let val = 'necessary';
                if (analytics && marketing && personalization) val = 'all';
                else if (analytics || marketing || personalization) val = 'partial';

                storeConsent(val, {
                    necessary:       true,
                    analytics,
                    marketing,
                    personalization,
                });
                portal.classList.remove('is-open');
                dismissBanner();
            });
        }
    }
})();
</script>