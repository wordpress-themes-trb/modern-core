<?php
$width_mode = $attributes['widthMode'] ?? 'contained';
$target     = $attributes['targetDate']     ?? '';
$layout     = $attributes['layoutMode']     ?? 'card';
$style      = $attributes['timerStyle']     ?? 'boxed';
$bg_mode    = $attributes['bgMode']         ?? 'gradient';
$c1         = $attributes['color1']         ?? '#dc2626';
$c2         = $attributes['color2']         ?? '#7f1d1d';
$digit_color = $attributes['digitColor']   ?? '#ffffff';
$box_color  = $attributes['boxColor']       ?? 'rgba(0,0,0,0.5)';
$action     = $attributes['expireAction']   ?? 'hide';
$msg        = $attributes['expireMessage']  ?? 'This flash sale has ended.';
$url        = $attributes['expireUrl']      ?? '';
$show_days  = $attributes['showDays']       ?? true;

$uid = 'mc-cdbanner-' . uniqid();
$wrapper_attributes = get_block_wrapper_attributes([
    'id'          => esc_attr( $uid ),
    'class'       => "mc-block width-" . esc_attr($width_mode) . " modern-core-countdown-banner {$uid} layout-{$layout} style-{$style}",
]);

$bg_css = $bg_mode === 'gradient'
    ? "linear-gradient(135deg, {$c1}, {$c2})"
    : $c1;
?>

<div <?php echo $wrapper_attributes; ?>>
<style>
.<?php echo esc_attr($uid); ?> {
    box-sizing:      border-box;
    width:           100%;
    max-width:       100%;
    overflow:        hidden;
    background:      <?php echo $bg_css; ?>;
    color:           #fff;
    display:         flex;
    align-items:     center;
    justify-content: <?php echo $layout === 'split' ? 'space-between' : 'center'; ?>;
    gap:             40px;
    transition:      opacity 0.5s ease, height 0.5s ease;
    padding-block:  <?php echo $layout === 'strip' ? '20px' : '60px'; ?>;
    padding-inline: clamp(1rem, 5%, 80px);
}

<?php if ( $layout === 'card' ) : ?>
.<?php echo esc_attr($uid); ?> {
    flex-direction: column;
    max-width:      1000px;
    width:          calc(100% - 2 * clamp(1rem, 4vw, 40px));
    margin:         40px auto;
    border-radius:  16px;
    box-shadow:     0 25px 50px -12px rgba(0,0,0,0.5);
}
<?php elseif ( $layout === 'split' ) : ?>
.<?php echo esc_attr($uid); ?> {
    flex-direction: row;
}
<?php else : /* strip */ ?>
.<?php echo esc_attr($uid); ?> {
    flex-direction: column;
}
<?php endif; ?>

.<?php echo esc_attr($uid); ?> .mc-cdb-content {
    flex:      <?php echo $layout === 'split' ? '1' : 'none'; ?>;
    width:     100%;
    max-width: 600px;
    text-align:<?php echo $layout === 'split' ? 'left' : 'center'; ?>;
    z-index:   2;
}

.<?php echo esc_attr($uid); ?> .mc-cdb-timer-zone {
    flex:           <?php echo $layout === 'split' ? '1' : 'none'; ?>;
    display:        flex;
    flex-direction: column;
    align-items:    <?php echo $layout === 'split' ? 'flex-end' : 'center'; ?>;
    justify-content:<?php echo $layout === 'split' ? 'flex-end' : 'center'; ?>;
    z-index:        2;
}

.<?php echo esc_attr($uid); ?> .mc-timer-wrap {
    display: flex;
    gap:     clamp(8px, 1.5vw, 15px);
    flex-wrap: wrap;
    justify-content: center;
}
.<?php echo esc_attr($uid); ?> .mc-unit {
    display:        flex;
    flex-direction: column;
    align-items:    center;
    justify-content:center;
    min-width:      clamp(55px, 8vw, 80px);
    position:       relative;
    box-sizing:     border-box;
}
.<?php echo esc_attr($uid); ?> .mc-digit {
    font-size:           clamp(28px, 4vw, 42px);
    font-weight:         800;
    line-height:         1;
    color:               <?php echo esc_attr($digit_color); ?>;
    font-variant-numeric:tabular-nums;
    position:            relative;
    z-index:             2;
}
.<?php echo esc_attr($uid); ?> .mc-label {
    font-size:     11px;
    text-transform:uppercase;
    letter-spacing:1px;
    margin-top:    5px;
    opacity:       0.8;
}

<?php if ( $style === 'boxed' || $style === 'flip' ) : ?>
.<?php echo esc_attr($uid); ?> .mc-unit {
    background:    <?php echo esc_attr($box_color); ?>;
    padding:       clamp(10px, 2vw, 15px) clamp(12px, 2.5vw, 20px);
    border-radius: 8px;
}
<?php endif; ?>

<?php if ( $style === 'flip' ) : ?>
.<?php echo esc_attr($uid); ?> .mc-unit {
    border:     1px solid rgba(255,255,255,0.1);
    box-shadow: inset 0 2px 0 rgba(255,255,255,0.1), 0 5px 15px rgba(0,0,0,0.3);
}
.<?php echo esc_attr($uid); ?> .mc-unit::after {
    content:    '';
    position:   absolute;
    top:        50%;
    left:       0;
    right:      0;
    height:     1px;
    background: rgba(0,0,0,0.6);
    z-index:    3;
}
<?php endif; ?>

<?php if ( $style === 'neon' ) : ?>
.<?php echo esc_attr($uid); ?> .mc-digit {
    text-shadow:
        0 0 10px <?php echo esc_attr($digit_color); ?>,
        0 0 20px <?php echo esc_attr($digit_color); ?>,
        0 0 40px <?php echo esc_attr($digit_color); ?>;
}
<?php endif; ?>

.<?php echo esc_attr($uid); ?> .mc-cdb-msg {
    display:    none;
    font-size:  24px;
    font-weight:bold;
    width:      100%;
    text-align: center;
}

@media (max-width: 768px) {
    .<?php echo esc_attr($uid); ?> {
        flex-direction: column;
        text-align:     center;
        padding-block:  40px;
        gap:            24px;
    }
    .<?php echo esc_attr($uid); ?> .mc-cdb-content,
    .<?php echo esc_attr($uid); ?> .mc-cdb-timer-zone {
        justify-content: center;
        text-align:      center;
        align-items:     center;
    }
    .<?php echo esc_attr($uid); ?>.layout-card {
        width:         100%;
        margin-left:   0;
        margin-right:  0;
        border-radius: 0;
    }
}
</style>

    <div class="mc-cdb-content">
        <?php echo $content; ?>
    </div>

    <div class="mc-cdb-timer-zone">
        <div class="mc-timer-wrap">
            <?php if ( $show_days ) : ?>
                <div class="mc-unit">
                    <span class="mc-digit mc-d">00</span>
                    <span class="mc-label">Days</span>
                </div>
            <?php endif; ?>
            <div class="mc-unit"><span class="mc-digit mc-h">00</span><span class="mc-label">Hours</span></div>
            <div class="mc-unit"><span class="mc-digit mc-m">00</span><span class="mc-label">Mins</span></div>
            <div class="mc-unit"><span class="mc-digit mc-s">00</span><span class="mc-label">Secs</span></div>
        </div>
        <div class="mc-cdb-msg"><?php echo esc_html( $msg ); ?></div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var uid     = <?php echo wp_json_encode( $uid ); ?>;
        var rawDate = <?php echo wp_json_encode( $target ); ?>;
        var action  = <?php echo wp_json_encode( $action ); ?>;
        var url     = <?php echo wp_json_encode( $url ); ?>;

        var banner = document.getElementById(uid);
        if (!banner || !rawDate) return;

        var elD = banner.querySelector('.mc-d');
        var elH = banner.querySelector('.mc-h');
        var elM = banner.querySelector('.mc-m');
        var elS = banner.querySelector('.mc-s');
        var wrap = banner.querySelector('.mc-timer-wrap');
        var msgEl = banner.querySelector('.mc-cdb-msg');
        var contentZone = banner.querySelector('.mc-cdb-content');

        function pad(n) {
            return String(Math.max(0, n)).padStart(2, '0');
        }

        function parseLocal(str) {
            var s     = str.trim().replace(' ', 'T');
            var parts = s.split('T');
            var d     = (parts[0] || '').split('-').map(Number);
            var t     = (parts[1] || '00:00').split(':').map(Number);
            if (!d[0] || !d[1] || !d[2]) return NaN;
            return new Date(d[0], d[1] - 1, d[2], t[0] || 0, t[1] || 0, t[2] || 0).getTime();
        }

        var targetTime = parseLocal(rawDate);
        if (isNaN(targetTime)) return;

        function update() {
            var diff = targetTime - Date.now();

            if (diff <= 0) {
                if (elD) elD.textContent = '00';
                if (elH) elH.textContent = '00';
                if (elM) elM.textContent = '00';
                if (elS) elS.textContent = '00';
                clearInterval(timer);

                if (action === 'redirect') {
                    if (url) { window.location.href = url; }
                } else if (action === 'message') {
                    if (wrap) wrap.style.display = 'none';
                    if (contentZone) contentZone.style.display = 'none';
                    if (msgEl) msgEl.style.display = 'block';
                } else if (action === 'hide') {
                    banner.style.opacity = '0';
                    setTimeout(function() { banner.style.display = 'none'; }, 500);
                }
                return;
            }

            var totalSec = Math.floor(diff / 1000);
            if (elD) elD.textContent = pad(Math.floor(totalSec / 86400));
            if (elH) elH.textContent = pad(Math.floor(totalSec / 3600) % 24);
            if (elM) elM.textContent = pad(Math.floor(totalSec / 60)   % 60);
            if (elS) elS.textContent = pad(totalSec % 60);
        }

        update();
        var timer = setInterval(update, 1000);
    });
    </script>
</div>