<?php
$layout_mode   = $attributes['layoutMode']      ?? 'full-center';
$min_height    = $attributes['minHeight']        ?? 90;
$bg_type       = $attributes['bgType']           ?? 'gradient';
$bg_color      = $attributes['bgColor']          ?? '#0f172a';
$bg_image      = $attributes['bgImageUrl']       ?? '';
$opacity       = ( $attributes['overlayOpacity'] ?? 60 ) / 100;
$ken_burns     = $attributes['enableKenBurns']   ?? true;
$noise         = $attributes['enableNoise']      ?? true;
$content_card  = $attributes['contentCard']      ?? false;

$uid = 'mc-hero-' . uniqid();
$wrapper_attributes = get_block_wrapper_attributes([
    'class' => "mc-block modern-core-hero {$uid}",
]);

$is_center  = strpos( $layout_mode, 'center' ) !== false;
$is_bottom  = strpos( $layout_mode, 'bottom' ) !== false;
$is_split   = strpos( $layout_mode, 'split'  ) !== false;

$align_items     = $is_center ? 'center'        : ( $is_bottom  ? 'flex-end'      : 'flex-start' );
$justify_content = $is_center ? 'center'        : ( $is_split   ? 'space-between' : 'flex-start' );
$content_maxw    = $is_center ? '800px'         : '600px';

$noise_svg = "url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.65%22 numOctaves=%223%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E')";
?>

<div <?php echo $wrapper_attributes; ?>>
<style>
.<?php echo esc_attr($uid); ?> {
    position:        relative;
    box-sizing:      border-box; 
    width:           100%;
    max-width:       100%;
    overflow:        hidden;
    min-height:      <?php echo esc_attr($min_height); ?>vh;
    display:         flex;
    align-items:     <?php echo esc_attr($align_items); ?>;
    justify-content: <?php echo esc_attr($justify_content); ?>;
    background-color:<?php echo esc_attr($bg_color); ?>;
    padding-block:   clamp(40px, 5vh, 120px);
    padding-inline:  clamp(1.25rem, 8%, 120px);
}

.<?php echo esc_attr($uid); ?> .mc-hero-bg {
    position:            absolute;
    inset:               -5%;
    z-index:             0;
    background-size:     cover;
    background-position: center;
    background-repeat:   no-repeat;
    <?php if ( $bg_type === 'image' && $bg_image ) : ?>
        background-image: url('<?php echo esc_url( $bg_image ); ?>');
    <?php elseif ( $bg_type === 'gradient' ) : ?>
        background-image: radial-gradient(circle at center, #1e293b 0%, #0f172a 100%);
    <?php endif; ?>
}

<?php if ( $ken_burns && $bg_type === 'image' ) : ?>
.<?php echo esc_attr($uid); ?> .mc-hero-bg {
    animation: <?php echo esc_attr($uid); ?>_kenburns 20s ease-out forwards;
}
@keyframes <?php echo esc_attr($uid); ?>_kenburns {
    0%   { transform: scale(1);    }
    100% { transform: scale(1.08); }
}
<?php endif; ?>

.<?php echo esc_attr($uid); ?> .mc-hero-overlay {
    position:   absolute;
    inset:      0;
    z-index:    1;
    background: rgba(0,0,0,<?php echo esc_attr($opacity); ?>);
}

<?php if ( $noise ) : ?>
.<?php echo esc_attr($uid); ?> .mc-hero-noise {
    position:         absolute;
    inset:            0;
    z-index:          2;
    background-image: <?php echo $noise_svg; ?>;
    opacity:          0.05;
    pointer-events:   none;
    mix-blend-mode:   overlay;
}
<?php endif; ?>

.<?php echo esc_attr($uid); ?> .mc-hero-content-wrapper {
    position:  relative;
    z-index:   3;
    width:     100%;
    max-width: <?php echo esc_attr($content_maxw); ?>;
}

<?php if ( $content_card ) : ?>
.<?php echo esc_attr($uid); ?> .mc-hero-content-wrapper {
    background:    rgba(0,0,0,0.45);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border:        1px solid rgba(255,255,255,0.08);
    border-radius: 16px;
    padding:       clamp(24px, 4vw, 48px);
}
<?php endif; ?>

.mc-js .<?php echo esc_attr($uid); ?> .mc-stagger-item {
    opacity:   0;
    transform: translateY(20px) scale(0.98);
    filter:    blur(10px);
}
.<?php echo esc_attr($uid); ?> .mc-stagger-group.is-visible .mc-stagger-item {
    animation: <?php echo esc_attr($uid); ?>_blurReveal 1.2s cubic-bezier(0.19,1,0.22,1) forwards;
}
.<?php echo esc_attr($uid); ?> .mc-stagger-group.is-visible .mc-stagger-item:nth-child(1) { animation-delay: 0.1s; }
.<?php echo esc_attr($uid); ?> .mc-stagger-group.is-visible .mc-stagger-item:nth-child(2) { animation-delay: 0.3s; }
.<?php echo esc_attr($uid); ?> .mc-stagger-group.is-visible .mc-stagger-item:nth-child(3) { animation-delay: 0.5s; }
.<?php echo esc_attr($uid); ?> .mc-stagger-group.is-visible .mc-stagger-item:nth-child(4) { animation-delay: 0.7s; }
.<?php echo esc_attr($uid); ?> .mc-stagger-group.is-visible .mc-stagger-item:nth-child(5) { animation-delay: 0.9s; }

@keyframes <?php echo esc_attr($uid); ?>_blurReveal {
    0%   { opacity: 0; transform: translateY(20px) scale(0.98); filter: blur(10px); }
    100% { opacity: 1; transform: translateY(0)    scale(1);    filter: blur(0);    }
}

.<?php echo esc_attr($uid); ?> h1 em,
.<?php echo esc_attr($uid); ?> h2 em {
    font-style: italic;
    position:   relative;
    display:    inline-block;
}
.<?php echo esc_attr($uid); ?> h1 em::after,
.<?php echo esc_attr($uid); ?> h2 em::after {
    content:          '';
    position:         absolute;
    left:             0;
    bottom:           -2px;
    width:            0;
    height:           3px;
    background-color: #e2b57a;
    border-radius:    2px;
    transition:       width 1s cubic-bezier(0.19,1,0.22,1) 1s;
}
.<?php echo esc_attr($uid); ?> .mc-stagger-group.is-visible h1 em::after,
.<?php echo esc_attr($uid); ?> .mc-stagger-group.is-visible h2 em::after {
    width: 100%;
}

@media (max-width: 768px) {
    .<?php echo esc_attr($uid); ?> {
        min-height:   <?php echo max(50, $min_height - 20); ?>vh;
        align-items:  flex-start;
        padding-block:clamp(32px, 4vh, 60px);
    }
    .<?php echo esc_attr($uid); ?> .mc-hero-content-wrapper {
        max-width: 100%;
    }
}
</style>

    <div class="mc-hero-bg"></div>
    <div class="mc-hero-overlay"></div>
    <?php if ( $noise ) : ?>
        <div class="mc-hero-noise"></div>
    <?php endif; ?>

    <?php echo $content; ?>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const hero = document.querySelector('.<?php echo esc_js($uid); ?>');
        if (!hero) return;

        const staggerGroup = hero.querySelector('.mc-stagger-group');
        if (!staggerGroup) return;

        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        staggerGroup.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.15 }
        );

        observer.observe(hero);
    });
    </script>
</div>