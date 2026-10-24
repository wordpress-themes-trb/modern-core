<?php
$content_align = $attributes['contentAlign'] ?? 'center';
$width_mode    = $attributes['widthMode']    ?? 'contained';
$aos_anim      = $attributes['aosAnimation'] ?? 'fade-up';
$target_date   = $attributes['targetDate']   ?? '';
$style_mode    = $attributes['styleMode']    ?? 'boxed';
$digit_size    = $attributes['digitSize']    ?? 32;
$digit_color   = $attributes['digitColor']   ?? '#ffffff';
$label_color   = $attributes['labelColor']   ?? '#ffffff';
$box_bg        = $attributes['boxBg']        ?? '#000000';
$box_radius    = $attributes['boxRadius']    ?? 8;
$label_d       = $attributes['labelDays']    ?? 'Days';
$label_h       = $attributes['labelHours']   ?? 'Hours';
$label_m       = $attributes['labelMins']    ?? 'Mins';
$label_s       = $attributes['labelSecs']    ?? 'Secs';
$expire_action = $attributes['expireAction'] ?? 'stop';
$expire_msg    = $attributes['expireMessage'] ?? 'This offer has expired.';
$expire_url    = $attributes['expireUrl']    ?? '';

$uid      = 'mc-cd-' . uniqid();
$is_boxed = in_array( $style_mode, ['boxed', 'pill', 'elevated'] );
$radius   = match( $style_mode ) {
    'pill'     => '999px',
    'elevated' => '16px',
    default    => "{$box_radius}px",
};

$wrapper_attributes = get_block_wrapper_attributes( [
    'id'       => esc_attr( $uid ),
    'class'    => "modern-core-countdown style-{$style_mode} mc-width-{$width_mode}",
    'data-mc-aos' => ( $aos_anim && 'none' !== $aos_anim ) ? esc_attr( $aos_anim ) : null,
] );
?>
<div <?php echo $wrapper_attributes; ?>>
<style>
#<?php echo esc_attr($uid); ?> {
    box-sizing:      border-box;
    width:           100%;
    display:         flex;
    justify-content: <?php echo esc_attr($content_align); ?>;
    align-items:     center;
    padding:         clamp(24px, 4vw, 48px) clamp(16px, 4%, 40px);
}
#<?php echo esc_attr($uid); ?> .mc-cd-row {
    display:         flex;
    align-items:     center;
    gap:             <?php echo $style_mode === 'minimal' ? '20px' : '12px'; ?>;
    flex-wrap:       wrap;
    justify-content: center;
}
#<?php echo esc_attr($uid); ?> .mc-cd-unit {
    display:         flex;
    flex-direction:  column;
    align-items:     center;
    justify-content: center;
    position:        relative;
    <?php if ( $is_boxed ) : ?>
    background:    <?php echo esc_attr($box_bg); ?>;
    border-radius: <?php echo esc_attr($radius); ?>;
    min-width:     clamp(72px, 10vw, 100px);
    padding:       clamp(16px, 2.5vw, 28px) clamp(12px, 2vw, 24px);
    box-shadow:    0 1px 3px rgba(0,0,0,0.06);
    border:        1px solid rgba(0,0,0,0.07);
    <?php endif; ?>
}
#<?php echo esc_attr($uid); ?> .mc-cd-digit {
    font-size:            clamp(<?php echo esc_attr(intval($digit_size * 0.7)); ?>px, <?php echo esc_attr($digit_size); ?>px, <?php echo esc_attr($digit_size); ?>px);
    font-weight:          800;
    line-height:          1;
    letter-spacing:       -0.03em;
    color:                <?php echo esc_attr($digit_color); ?>;
    font-variant-numeric: tabular-nums;
    font-feature-settings:"tnum";
}
#<?php echo esc_attr($uid); ?> .mc-cd-label {
    font-size:      <?php echo esc_attr( max(10, intval($digit_size * 0.28)) ); ?>px;
    font-weight:    600;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-top:     <?php echo $style_mode === 'minimal' ? '0' : '8px'; ?>;
    color:          <?php echo esc_attr($label_color); ?>;
    white-space:    nowrap;
}
#<?php echo esc_attr($uid); ?> .mc-cd-sep {
    font-size:     <?php echo esc_attr($digit_size); ?>px;
    font-weight:   800;
    color:         <?php echo esc_attr($digit_color); ?>;
    opacity:       0.3;
    line-height:   1;
    margin-bottom: <?php echo esc_attr( max(10, intval($digit_size * 0.28)) + 8 ); ?>px;
    user-select:   none;
}
#<?php echo esc_attr($uid); ?> .mc-cd-expired {
    display:       none;
    font-size:     clamp(14px, 2vw, 18px);
    font-weight:   600;
    color:         <?php echo esc_attr($digit_color); ?>;
    letter-spacing:-0.01em;
    text-align:    center;
    <?php if ( $is_boxed ) : ?>
    background:    <?php echo esc_attr($box_bg); ?>;
    padding:       20px 32px;
    border-radius: <?php echo esc_attr($radius); ?>;
    border:        1px solid rgba(0,0,0,0.07);
    <?php endif; ?>
}
</style>

    <div class="mc-cd-row">
        <div class="mc-cd-unit">
            <span class="mc-cd-digit" data-unit="d">00</span>
            <span class="mc-cd-label"><?php echo esc_html($label_d); ?></span>
        </div>
        <?php if ( $style_mode === 'minimal' ) : ?>
            <div class="mc-cd-sep" aria-hidden="true">:</div>
        <?php endif; ?>
        <div class="mc-cd-unit">
            <span class="mc-cd-digit" data-unit="h">00</span>
            <span class="mc-cd-label"><?php echo esc_html($label_h); ?></span>
        </div>
        <?php if ( $style_mode === 'minimal' ) : ?>
            <div class="mc-cd-sep" aria-hidden="true">:</div>
        <?php endif; ?>
        <div class="mc-cd-unit">
            <span class="mc-cd-digit" data-unit="m">00</span>
            <span class="mc-cd-label"><?php echo esc_html($label_m); ?></span>
        </div>
        <?php if ( $style_mode === 'minimal' ) : ?>
            <div class="mc-cd-sep" aria-hidden="true">:</div>
        <?php endif; ?>
        <div class="mc-cd-unit">
            <span class="mc-cd-digit" data-unit="s">00</span>
            <span class="mc-cd-label"><?php echo esc_html($label_s); ?></span>
        </div>
    </div>

    <div class="mc-cd-expired"><?php echo esc_html($expire_msg); ?></div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var uid     = <?php echo wp_json_encode( $uid ); ?>;
    var rawDate = <?php echo wp_json_encode( $target_date ); ?>;
    var action  = <?php echo wp_json_encode( $expire_action ); ?>;
    var url     = <?php echo wp_json_encode( $expire_url ); ?>;

    var wrap = document.getElementById(uid);
    if (!wrap || !rawDate) return;

    var row     = wrap.querySelector('.mc-cd-row');
    var expired = wrap.querySelector('.mc-cd-expired');
    var elD     = wrap.querySelector('[data-unit="d"]');
    var elH     = wrap.querySelector('[data-unit="h"]');
    var elM     = wrap.querySelector('[data-unit="m"]');
    var elS     = wrap.querySelector('[data-unit="s"]');

    function pad(n) {
        return String(Math.max(0, n)).padStart(2, '0');
    }

    /*
     * Parse datetime-local string reliably across all browsers.
     * "2025-06-01T18:00" → split manually and use local Date constructor.
     * Never pass the raw string to new Date() — Safari treats it as UTC.
     */
    function parseLocal(str) {
        var s     = str.trim().replace(' ', 'T');
        var parts = s.split('T');
        var d     = (parts[0] || '').split('-').map(Number);
        var t     = (parts[1] || '00:00').split(':').map(Number);
        if (!d[0] || !d[1] || !d[2]) return NaN;
        return new Date(d[0], d[1] - 1, d[2], t[0] || 0, t[1] || 0, t[2] || 0).getTime();
    }

    var target = parseLocal(rawDate);
    if (isNaN(target)) return;

    /* ── THE ONLY update() function — no duplicates ── */
    function update() {
        var diff = target - Date.now();

        if (diff <= 0) {
            elD.textContent = '00';
            elH.textContent = '00';
            elM.textContent = '00';
            elS.textContent = '00';
            clearInterval(timer);

            if (action === 'redirect') {
                if (url) { window.location.href = url; }
            } else if (action === 'message') {
                row.style.display     = 'none';
                expired.style.display = 'flex';
            }
            return;
        }

        var totalSec = Math.floor(diff / 1000);
        elD.textContent = pad(Math.floor(totalSec / 86400));
        elH.textContent = pad(Math.floor(totalSec / 3600) % 24);
        elM.textContent = pad(Math.floor(totalSec / 60)   % 60);
        elS.textContent = pad(totalSec % 60);
    }

    update();
    var timer = setInterval(update, 1000);
});
</script>

</div>