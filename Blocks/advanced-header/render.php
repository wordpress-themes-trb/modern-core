<?php
$is_sticky = $attributes['isSticky'] ?? true;
$is_transparent = $attributes['transparentOnLoad'] ?? false;
$auto_hide = $attributes['autoHideOnScroll'] ?? false;
$preset = $attributes['preset'] ?? 'custom';
$is_local = $attributes['isLocal'] ?? false;

$classes = 'modern-core-header-wrapper';
if ( $is_sticky ) { $classes .= ' is-sticky'; }
if ( $is_transparent ) { $classes .= ' is-transparent'; }
if ( $is_local ) { $classes .= ' is-local-header'; }

$wrapper_attributes = get_block_wrapper_attributes( [
    'class' => $classes,
    'data-auto-hide' => $is_sticky && $auto_hide ? 'true' : 'false',
    'data-preset' => esc_attr($preset),
] );
?>

<header <?php echo $wrapper_attributes; ?>>
    
    <style>
        .modern-core-header-wrapper {
            width: 100%;
            position: relative;
            z-index: 999;
        }
        .modern-core-header-wrapper.is-sticky {
            position: sticky;
            top: 0;
            transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1), background-color 0.3s ease;
        }
        .modern-core-header-wrapper.is-transparent {
            background: transparent;
        }
        .modern-core-header-wrapper.mc-scroll-hidden {
            transform: translateY(-100%);
        }
    </style>

    <?php echo $content; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const header = document.querySelector('header.modern-core-header-wrapper');
            if (!header || header.dataset.autoHide !== 'true') return;

            let lastScroll = window.scrollY;
            
            window.addEventListener('scroll', () => {
                const currentScroll = window.scrollY;
                
                if (currentScroll <= 0) {
                    header.classList.remove('mc-scroll-hidden');
                } else if (currentScroll > lastScroll && currentScroll > 100) {
                    header.classList.add('mc-scroll-hidden');
                } else if (currentScroll < lastScroll) {
                    header.classList.remove('mc-scroll-hidden');
                }
                
                lastScroll = currentScroll;
            });
        });
    </script>
</header>