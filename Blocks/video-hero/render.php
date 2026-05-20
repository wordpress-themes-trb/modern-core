<?php
if (empty($attributes['videoUrl'])) return;

$video_url = $attributes['videoUrl'];
$poster_url = $attributes['posterUrl'] ?? '';
$mobile_behavior = $attributes['mobileBehavior'] ?? 'play';
$mobile_video = $attributes['mobileVideoUrl'] ?? '';
$mobile_poster = $attributes['mobilePosterUrl'] ?? '';
$speed = $attributes['playbackSpeed'] ?? 1.0;
$show_mute = $attributes['showMuteToggle'] ?? true;
$show_play = $attributes['showPlayToggle'] ?? false;
$overlay_type = $attributes['overlayType'] ?? 'bottom-gradient';
$overlay_color = $attributes['overlayColor'] ?? '#000000';
$opacity = ($attributes['overlayOpacity'] ?? 70) / 100;
$min_height = $attributes['minHeight'] ?? 80;
$align = $attributes['contentAlign'] ?? 'center';

$unique_id = uniqid('mc-vid-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "modern-core-video-hero {$unique_id}"]);

$flex_align = $align === 'center' ? 'center' : ($align === 'right' ? 'flex-end' : 'flex-start');
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            position: relative;
            width: 100%;
            min-height: <?php echo esc_attr($min_height); ?>vh;
            display: flex;
            align-items: center;
            justify-content: <?php echo esc_attr($flex_align); ?>;
            padding: 40px 8%;
            overflow: hidden;
            background-color: #0f172a;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-video-element {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 0;
            transition: opacity 0.5s ease;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-video-overlay {
            position: absolute;
            inset: 0;
            z-index: 1;
            <?php if ($overlay_type === 'solid') : ?>
                background: <?php echo esc_attr($overlay_color); ?>;
                opacity: <?php echo esc_attr($opacity); ?>;
            <?php elseif ($overlay_type === 'bottom-gradient') : ?>
                background: linear-gradient(to top, <?php echo esc_attr($overlay_color); ?> 0%, rgba(0,0,0,0) 60%);
                opacity: <?php echo esc_attr($opacity); ?>;
            <?php endif; ?>
        }

        .<?php echo esc_attr($unique_id); ?> .mc-video-content {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 800px;
            text-align: <?php echo esc_attr($align); ?>;
        }

        /* UI Controls */
        .<?php echo esc_attr($unique_id); ?> .mc-video-controls {
            position: absolute;
            bottom: 30px;
            right: 30px;
            z-index: 10;
            display: flex;
            gap: 15px;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-v-btn {
            background: rgba(0,0,0,0.4);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.2);
            color: #fff;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s ease;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-v-btn:hover { background: rgba(0,0,0,0.8); }

        /* Mobile Image Fallback */
        .mc-mobile-fallback { display: none; }
        @media (max-width: 768px) {
            .<?php echo esc_attr($unique_id); ?>.mobile-image .mc-video-element { display: none; }
            .<?php echo esc_attr($unique_id); ?>.mobile-image .mc-mobile-fallback {
                display: block; position: absolute; inset: 0; width: 100%; height: 100%;
                object-fit: cover; z-index: 0;
            }
        }
    </style>

    <video 
        class="mc-video-element" 
        autoPlay loop muted playsInline 
        poster="<?php echo esc_url($poster_url); ?>"
        data-desktop="<?php echo esc_url($video_url); ?>"
        data-mobile="<?php echo esc_url($mobile_video); ?>"
        data-behavior="<?php echo esc_attr($mobile_behavior); ?>"
        data-speed="<?php echo esc_attr($speed); ?>"
    >
        <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
    </video>

    <?php if ($mobile_behavior === 'image' && $mobile_poster) : ?>
        <img class="mc-mobile-fallback" src="<?php echo esc_url($mobile_poster); ?>" alt="Hero Fallback" />
    <?php endif; ?>

    <div class="mc-video-overlay"></div>

    <div class="mc-video-content">
        <?php echo $content; ?>
    </div>

    <div class="mc-video-controls">
        <?php if ($show_play) : ?>
            <button class="mc-v-btn mc-v-play" aria-label="Toggle Play">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="4" width="4" height="16"></rect><rect x="14" y="4" width="4" height="16"></rect></svg>
            </button>
        <?php endif; ?>
        <?php if ($show_mute) : ?>
            <button class="mc-v-btn mc-v-mute" aria-label="Toggle Audio">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><line x1="23" y1="9" x2="17" y2="15"></line><line x1="17" y1="9" x2="23" y2="15"></line></svg>
            </button>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.querySelector('.<?php echo esc_attr($unique_id); ?>');
            if (!container) return;

            const video = container.querySelector('.mc-video-element');
            if (!video) return;

            const behavior = video.dataset.behavior;
            const isMobile = window.innerWidth <= 768;

            // Handle Mobile Behaviors
            if (isMobile) {
                if (behavior === 'image') {
                    container.classList.add('mobile-image');
                } else if (behavior === 'video' && video.dataset.mobile) {
                    video.src = video.dataset.mobile;
                    video.load();
                }
            }

            // Set custom playback speed for slow-motion effects
            video.playbackRate = parseFloat(video.dataset.speed) || 1.0;

            // UI Controllers
            const muteBtn = container.querySelector('.mc-v-mute');
            const playBtn = container.querySelector('.mc-v-play');

            if (muteBtn) {
                muteBtn.addEventListener('click', () => {
                    video.muted = !video.muted;
                    muteBtn.innerHTML = video.muted 
                        ? '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><line x1="23" y1="9" x2="17" y2="15"></line><line x1="17" y1="9" x2="23" y2="15"></line></svg>' // Muted Icon
                        : '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path></svg>'; // Unmuted Icon
                });
            }

            if (playBtn) {
                playBtn.addEventListener('click', () => {
                    if (video.paused) {
                        video.play();
                        playBtn.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="4" width="4" height="16"></rect><rect x="14" y="4" width="4" height="16"></rect></svg>'; // Pause Icon
                    } else {
                        video.pause();
                        playBtn.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>'; // Play Icon
                    }
                });
            }
        });
    </script>
</div>