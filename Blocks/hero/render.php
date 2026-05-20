<?php
$layout_mode = $attributes['layoutMode'] ?? 'full-center';
$min_height = $attributes['minHeight'] ?? 90;
$bg_type = $attributes['bgType'] ?? 'gradient';
$bg_color = $attributes['bgColor'] ?? '#0f172a';
$bg_image = $attributes['bgImageUrl'] ?? '';
$opacity = ($attributes['overlayOpacity'] ?? 60) / 100;
$ken_burns = $attributes['enableKenBurns'] ?? true;
$noise = $attributes['enableNoise'] ?? true;
$content_card = $attributes['contentCard'] ?? false;

$unique_id = uniqid('mc-hero-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "modern-core-hero {$unique_id}"]);

$align_items = strpos($layout_mode, 'center') !== false ? 'center' : (strpos($layout_mode, 'bottom') !== false ? 'flex-end' : 'flex-start');
$justify_content = strpos($layout_mode, 'center') !== false ? 'center' : (strpos($layout_mode, 'split') !== false ? 'space-between' : 'flex-start');

// Noise Texture encoded as Base64 SVG so it doesn't require an external image load
$noise_svg = "url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.65%22 numOctaves=%223%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E')";
?>

<div <?php echo $wrapper_attributes; ?>>
    
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            position: relative;
            width: 100%;
            min-height: <?php echo esc_attr($min_height); ?>vh;
            display: flex;
            align-items: <?php echo esc_attr($align_items); ?>;
            justify-content: <?php echo esc_attr($justify_content); ?>;
            padding: 5% 8%;
            overflow: hidden;
            background-color: <?php echo esc_attr($bg_color); ?>;
        }

        /* Background Engine */
        .<?php echo esc_attr($unique_id); ?> .mc-hero-bg {
            position: absolute; inset: -5%; /* Negative inset prevents white edges during Ken Burns */
            z-index: 0;
            background-size: cover;
            background-position: center;
            <?php if ($bg_type === 'image' && $bg_image) : ?>
                background-image: url('<?php echo esc_url($bg_image); ?>');
            <?php elseif ($bg_type === 'gradient') : ?>
                background-image: radial-gradient(circle at center, #1e293b 0%, #0f172a 100%);
            <?php endif; ?>
        }

        /* Ken Burns Physics */
        <?php if ($ken_burns && $bg_type === 'image') : ?>
        .<?php echo esc_attr($unique_id); ?> .mc-hero-bg {
            animation: mcKenBurns 20s ease-out forwards;
        }
        @keyframes mcKenBurns {
            0% { transform: scale(1); }
            100% { transform: scale(1.08); }
        }
        <?php endif; ?>

        /* Overlays */
        .<?php echo esc_attr($unique_id); ?> .mc-hero-overlay {
            position: absolute; inset: 0; z-index: 1;
            background-color: rgba(0,0,0,<?php echo esc_attr($opacity); ?>);
        }
        <?php if ($noise) : ?>
        .<?php echo esc_attr($unique_id); ?> .mc-hero-noise {
            position: absolute; inset: 0; z-index: 2;
            background-image: <?php echo $noise_svg; ?>;
            opacity: 0.05; pointer-events: none; mix-blend-mode: overlay;
        }
        <?php endif; ?>

        /* Content Layer */
        .<?php echo esc_attr($unique_id); ?> .mc-hero-content-wrapper {
            position: relative; z-index: 3;
            width: 100%; max-width: <?php echo strpos($layout_mode, 'center') !== false ? '800px' : '600px'; ?>;
        }

        /* Blur-to-Sharp Stagger Reveal Animation Engine */
        .mc-stagger-item {
            opacity: 0;
            transform: translateY(20px) scale(0.98);
            filter: blur(10px);
        }
        .mc-stagger-group.is-visible .mc-stagger-item {
            animation: mcBlurReveal 1.2s cubic-bezier(0.19, 1, 0.22, 1) forwards;
        }
        /* Stagger Delays */
        .mc-stagger-group.is-visible .mc-stagger-item:nth-child(1) { animation-delay: 0.1s; }
        .mc-stagger-group.is-visible .mc-stagger-item:nth-child(2) { animation-delay: 0.3s; }
        .mc-stagger-group.is-visible .mc-stagger-item:nth-child(3) { animation-delay: 0.5s; }
        .mc-stagger-group.is-visible .mc-stagger-item:nth-child(4) { animation-delay: 0.7s; }

        @keyframes mcBlurReveal {
            0% { opacity: 0; transform: translateY(20px) scale(0.98); filter: blur(10px); }
            100% { opacity: 1; transform: translateY(0) scale(1); filter: blur(0); }
        }

        /* Hand-drawn underline simulation for <em> tags in headlines */
        .<?php echo esc_attr($unique_id); ?> h1 em {
            font-style: italic;
            position: relative;
            display: inline-block;
        }
        .<?php echo esc_attr($unique_id); ?> h1 em::after {
            content: ''; position: absolute; left: 0; bottom: 0; width: 0; height: 4px;
            background-color: #3b82f6; /* Brand accent color */
            transition: width 1s cubic-bezier(0.19, 1, 0.22, 1) 1s; /* Draws after 1s delay */
        }
        .mc-stagger-group.is-visible h1 em::after { width: 100%; }
    </style>

    <div class="mc-hero-bg"></div>
    <div class="mc-hero-overlay"></div>
    <?php if ($noise) echo '<div class="mc-hero-noise"></div>'; ?>

    <?php echo $content; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const wrapper = document.querySelector('.<?php echo esc_attr($unique_id); ?>');
            const contentGroup = wrapper.querySelector('.mc-stagger-group');
            
            if (contentGroup) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            contentGroup.classList.add('is-visible');
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.2 });
                observer.observe(wrapper);
            }
        });
    </script>
</div>