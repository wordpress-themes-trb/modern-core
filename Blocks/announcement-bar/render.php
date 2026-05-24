<?php
$width_mode = $attributes['widthMode']      ?? 'fullwidth';
$bar_id     = $attributes['barId']          ?? 'announce-1';
$mode       = $attributes['displayMode']    ?? 'marquee';
$m1         = $attributes['message1']       ?? '';
$m2         = $attributes['message2']       ?? '';
$m3         = $attributes['message3']       ?? '';
$coupon     = $attributes['couponCode']     ?? '';
$speed      = $attributes['animationSpeed'] ?? 15;
$bg         = $attributes['bgColor']        ?? '#0f172a';
$text       = $attributes['textColor']      ?? '#ffffff';
$sticky     = $attributes['isSticky']       ?? false;
$dismiss    = $attributes['enableDismiss']  ?? true;

/*
 * FIX 1 — unique ID drives BOTH the CSS class AND the DOM id.
 * Never use the user-supplied barId as the DOM id directly because
 * multiple instances on the same page all default to "announce-1",
 * causing getElementById to always find the first one only.
 * The uniqid() value is unique per render, per instance.
 */
$uid = 'mc-annbar-' . uniqid();

$wrapper_classes  = "modern-core-announcement-bar {$uid} mode-{$mode}";
if ( $sticky ) $wrapper_classes .= ' is-sticky';

$wrapper_attributes = get_block_wrapper_attributes( [ 'class' => $wrapper_classes ] );

$messages  = array_values( array_filter( [ $m1, $m2, $m3 ] ) );
$has_coupon = ! empty( $coupon );
// Cookie key still uses the user-editable barId so dismiss preference
// is per-bar-slot, not per-render. Two bars with different barIds
// get independent cookies. Two bars with the same barId share one cookie.
$cookie_key = 'mc_announce_' . preg_replace( '/[^a-zA-Z0-9_-]/', '', $bar_id );
?>

<div <?php echo $wrapper_attributes; ?> id="<?php echo esc_attr( $uid ); ?>">
<style>
/* ── OUTER BAR ──────────────────────────────────────────────
   FIX 2 — box-sizing:border-box + overflow:hidden
   padding is now INSIDE the width, never adds extra pixels.
──────────────────────────────────────────────────────────── */
.<?php echo esc_attr($uid); ?> {
    position:        relative;
    z-index:         999;
    box-sizing:      border-box;      /* padding inside width — no overflow */
    width:           100%;
    max-width:       100%;
    overflow:        hidden;          /* safety net                         */
    background-color:<?php echo esc_attr($bg); ?>;
    color:           <?php echo esc_attr($text); ?>;
    padding:         10px clamp(15px, 3%, 40px);  /* fluid, never bleeds   */
    font-size:       12px;
    font-weight:     700;
    letter-spacing:  1px;
    text-transform:  uppercase;
    display:         flex;
    align-items:     center;
    justify-content: center;
}

.<?php echo esc_attr($uid); ?>.is-sticky {
    position: sticky;
    top:      0;
}

/* ── CLOSE BUTTON ─────────────────────────────────────────── */
.<?php echo esc_attr($uid); ?> .mc-close-btn {
    position:   absolute;
    right:      15px;
    top:        50%;
    transform:  translateY(-50%);
    background: none;
    border:     none;
    color:      inherit;
    opacity:    0.6;
    cursor:     pointer;
    padding:    5px;
    z-index:    10;
    line-height:1;
    font-size:  14px;
}
.<?php echo esc_attr($uid); ?> .mc-close-btn:hover { opacity: 1; }

/* ── COUPON CHIP ──────────────────────────────────────────── */
.<?php echo esc_attr($uid); ?> .mc-coupon {
    background:    rgba(255,255,255,0.15);
    padding:       4px 10px;
    border-radius: 4px;
    cursor:        pointer;
    border:        1px dashed rgba(255,255,255,0.4);
    transition:    background 0.2s;
    position:      relative;
    white-space:   nowrap;
    flex-shrink:   0;
}
.<?php echo esc_attr($uid); ?> .mc-coupon:hover { background: rgba(255,255,255,0.28); }
.<?php echo esc_attr($uid); ?> .mc-coupon.copied::after {
    content:      'COPIED!';
    position:     absolute;
    top:          -26px;
    left:         50%;
    transform:    translateX(-50%);
    background:   #10b981;
    color:        #fff;
    padding:      2px 6px;
    border-radius:3px;
    font-size:    9px;
    pointer-events:none;
}

/* ── MODE: MARQUEE ────────────────────────────────────────── */
.<?php echo esc_attr($uid); ?>.mode-marquee .mc-track-wrapper {
    display:    flex;
    width:      100%;
    overflow:   hidden;  /* clip the scrolling track, not the outer bar */
    white-space:nowrap;
}
.<?php echo esc_attr($uid); ?>.mode-marquee .mc-track {
    display:       flex;
    gap:           40px;
    padding-right: 40px;
    /* FIX 3 — namespaced keyframe so two bars don't share one animation */
    animation: <?php echo esc_attr($uid); ?>_marquee <?php echo esc_attr($speed); ?>s linear infinite;
    will-change: transform;
}
.<?php echo esc_attr($uid); ?>.mode-marquee:hover .mc-track {
    animation-play-state: paused;
}
@keyframes <?php echo esc_attr($uid); ?>_marquee {
    0%   { transform: translateX(0); }
    100% { transform: translateX(-50%); } /* -50% not -100%: two copies = perfect loop */
}

/* ── MODE: ROTATE ─────────────────────────────────────────── */
.<?php echo esc_attr($uid); ?> .mc-rotator {
    position: relative;
    display:  flex;
    align-items: center;
    justify-content: center;
    min-height: 20px;
}
.<?php echo esc_attr($uid); ?>.mode-rotate .mc-msg-slide {
    position:   absolute;
    opacity:    0;
    transition: opacity 0.5s ease;
    display:    flex;
    align-items:center;
    gap:        15px;
    white-space:nowrap;
    pointer-events: none;
}
.<?php echo esc_attr($uid); ?>.mode-rotate .mc-msg-slide.is-active {
    position:       relative;
    opacity:        1;
    pointer-events: auto;
}

/* ── MODE: STATIC ─────────────────────────────────────────── */
.<?php echo esc_attr($uid); ?>.mode-static .mc-static-content {
    display:     flex;
    align-items: center;
    gap:         15px;
    flex-wrap:   wrap;
    justify-content: center;
}

/* ── MOBILE ───────────────────────────────────────────────── */
@media (max-width: 640px) {
    .<?php echo esc_attr($uid); ?> {
        font-size: 10px;
        padding:   8px clamp(10px, 4%, 20px);
    }
    .<?php echo esc_attr($uid); ?>.mode-static .mc-static-content {
        gap: 8px;
    }
}
</style>

    <?php if ( $mode === 'marquee' ) : ?>

        <div class="mc-track-wrapper">
            <?php
            /*
             * FIX 4 — duplicate the track ONCE (2 total copies).
             * The keyframe animates to -50% (not -100%) so the second
             * copy lines up perfectly when the first exits, creating
             * a seamless infinite loop with no visible reset jump.
             */
            for ( $i = 0; $i < 2; $i++ ) : ?>
                <div class="mc-track" aria-hidden="<?php echo $i > 0 ? 'true' : 'false'; ?>">
                    <?php foreach ( $messages as $msg ) : ?>
                        <span class="mc-msg-item"><?php echo esc_html( $msg ); ?></span>
                    <?php endforeach; ?>
                    <?php if ( $has_coupon ) : ?>
                        <span class="mc-coupon" data-code="<?php echo esc_attr( $coupon ); ?>" role="button" tabindex="0">
                            <?php echo esc_html( $coupon ); ?>
                        </span>
                    <?php endif; ?>
                </div>
            <?php endfor; ?>
        </div>

    <?php elseif ( $mode === 'rotate' ) : ?>

        <div class="mc-rotator" role="region" aria-live="polite">
            <?php foreach ( $messages as $index => $msg ) : ?>
                <div class="mc-msg-slide <?php echo $index === 0 ? 'is-active' : ''; ?>">
                    <span><?php echo esc_html( $msg ); ?></span>
                    <?php if ( $index === count( $messages ) - 1 && $has_coupon ) : ?>
                        <span class="mc-coupon" data-code="<?php echo esc_attr( $coupon ); ?>" role="button" tabindex="0">
                            <?php echo esc_html( $coupon ); ?>
                        </span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

    <?php else : ?>

        <div class="mc-static-content">
            <span><?php echo esc_html( $m1 ); ?></span>
            <?php if ( $has_coupon ) : ?>
                <span class="mc-coupon" data-code="<?php echo esc_attr( $coupon ); ?>" role="button" tabindex="0">
                    <?php echo esc_html( $coupon ); ?>
                </span>
            <?php endif; ?>
        </div>

    <?php endif; ?>

    <?php if ( $dismiss ) : ?>
        <button class="mc-close-btn" aria-label="Dismiss announcement">&#10005;</button>
    <?php endif; ?>

<script>
(function () {
    /*
     * FIX 1 — use $uid (uniqid) not $bar_id to find THIS specific bar.
     * Each instance has its own uid so getElementById always finds
     * the correct element regardless of how many bars are on the page.
     */
    const bar        = document.getElementById('<?php echo esc_js($uid); ?>');
    const cookieName = '<?php echo esc_js($cookie_key); ?>';

    if (!bar) return;

    /* Check dismiss cookie BEFORE DOMContentLoaded — prevents flash */
    if (document.cookie.split(';').some(c => c.trim().startsWith(cookieName + '='))) {
        bar.style.display = 'none';
        return;
    }

    function init() {
        /* ── Dismiss ───────────────────────────────────────── */
        const closeBtn = bar.querySelector('.mc-close-btn');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                const exp = new Date();
                exp.setTime(exp.getTime() + 3 * 24 * 60 * 60 * 1000); // 3 days
                document.cookie = cookieName + '=true;expires=' + exp.toUTCString() + ';path=/';
                bar.style.opacity = '0';
                bar.style.transition = 'opacity 0.3s ease';
                setTimeout(() => { bar.style.display = 'none'; }, 320);
            });
        }

        /* ── Copy coupon ───────────────────────────────────── */
        bar.querySelectorAll('.mc-coupon').forEach(chip => {
            const copy = () => {
                navigator.clipboard.writeText(chip.dataset.code).then(() => {
                    chip.classList.add('copied');
                    setTimeout(() => chip.classList.remove('copied'), 2000);
                });
            };
            chip.addEventListener('click', copy);
            chip.addEventListener('keydown', e => {
                if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); copy(); }
            });
        });

        /* ── Rotator ───────────────────────────────────────── */
        <?php if ( $mode === 'rotate' ) : ?>
        const slides = bar.querySelectorAll('.mc-msg-slide');
        if (slides.length > 1) {
            let current = 0;
            setInterval(() => {
                slides[current].classList.remove('is-active');
                current = (current + 1) % slides.length;
                slides[current].classList.add('is-active');
            }, <?php echo esc_attr( intval($speed) * 1000 ); ?>);
        }
        <?php endif; ?>
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init(); /* DOMContentLoaded already fired — run immediately */
    }
})();
</script>

</div>