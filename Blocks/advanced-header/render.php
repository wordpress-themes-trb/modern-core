<?php
$is_sticky = $attributes['isSticky'] ?? true;
$is_transparent = $attributes['transparentOnLoad'] ?? false;
$auto_hide = $attributes['autoHideOnScroll'] ?? false;

$classes = 'modern-core-header-wrapper';
if ( $is_sticky ) { $classes .= ' is-sticky'; }
if ( $is_transparent ) { $classes .= ' is-transparent'; }

// We add a data attribute so the JS knows if this behavior is active
$wrapper_attributes = get_block_wrapper_attributes( [
    'class' => $classes,
    'data-auto-hide' => $is_sticky && $auto_hide ? 'true' : 'false'
] );
?>

<header <?php echo $wrapper_attributes; ?>>
    
    <style>
        .modern-core-header-wrapper.is-sticky {
            position: sticky;
            top: 0;
            transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1), background-color 0.3s ease;
        }
        /* When the JS detects a scroll down, it adds this class */
        .modern-core-header-wrapper.mc-scroll-hidden {
            transform: translateY(-100%);
        }
    </style>

    <?php echo $content; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const header = document.querySelector('header.modern-core-header-wrapper');
            
            // If Auto-Hide is turned off in the editor, do nothing.
            if (!header || header.dataset.autoHide !== 'true') return;

            let lastScroll = window.scrollY;
            
            window.addEventListener('scroll', () => {
                const currentScroll = window.scrollY;
                
                // If we are at the absolute top of the page, always show it
                if (currentScroll <= 0) {
                    header.classList.remove('mc-scroll-hidden');
                } 
                // If scrolling DOWN, and we've scrolled past the header height, hide it
                else if (currentScroll > lastScroll && currentScroll > 100) {
                    header.classList.add('mc-scroll-hidden');
                } 
                // If scrolling UP, show it
                else if (currentScroll < lastScroll) {
                    header.classList.remove('mc-scroll-hidden');
                }
                
                lastScroll = currentScroll;
            });
        });
    </script>
</header>