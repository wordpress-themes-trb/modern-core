<?php
$width_mode = $attributes['widthMode'] ?? 'contained';
$drawer_type = $attributes['drawerType'] ?? 'slide-right';
$drawer_width = $attributes['drawerWidth'] ?? 400;
$drawer_bg = $attributes['drawerBg'] ?? '#ffffff';
$overlay_blur = $attributes['overlayBlur'] ?? 5;
$icon_color = $attributes['iconColor'] ?? '#0f172a';
$icon_size = $attributes['iconSize'] ?? 28;

$unique_id = uniqid('mc-drawer-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => 'mc-block width-' . esc_attr($width_mode)]);

// CSS adjustments based on drawer type
$drawer_css = "background-color: {$drawer_bg}; transition: transform 0.4s cubic-bezier(0.77, 0, 0.175, 1); position: fixed; top: 0; bottom: 0; z-index: 99999; overflow-y: auto; padding: 40px;";
if ($drawer_type === 'slide-right') {
    $drawer_css .= "right: 0; width: {$drawer_width}px; max-width: 100vw; transform: translateX(100%);";
} elseif ($drawer_type === 'slide-left') {
    $drawer_css .= "left: 0; width: {$drawer_width}px; max-width: 100vw; transform: translateX(-100%);";
} else { // Fullscreen
    $drawer_css .= "left: 0; right: 0; width: 100vw; transform: translateY(-100%);";
}
?>

<div <?php echo $wrapper_attributes; ?>>
    
    <button class="mc-hamburger-trigger-<?php echo esc_attr($unique_id); ?>" style="background: none; border: none; padding: 0; cursor: pointer; color: <?php echo esc_attr($icon_color); ?>; display: flex; align-items: center; justify-content: center;">
        <svg width="<?php echo esc_attr($icon_size); ?>" height="<?php echo esc_attr($icon_size); ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
    </button>

    <div class="mc-drawer-overlay-<?php echo esc_attr($unique_id); ?>" style="position: fixed; inset: 0; background: rgba(0,0,0,0.4); backdrop-filter: blur(<?php echo esc_attr($overlay_blur); ?>px); z-index: 99998; opacity: 0; pointer-events: none; transition: opacity 0.3s ease;"></div>

    <div class="mc-drawer-panel-<?php echo esc_attr($unique_id); ?>" style="<?php echo esc_attr($drawer_css); ?>">
        
        <button class="mc-drawer-close-<?php echo esc_attr($unique_id); ?>" style="position: absolute; top: 20px; right: 20px; background: none; border: none; cursor: pointer; color: inherit; padding: 5px;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <div class="mc-drawer-content" style="margin-top: 40px; color: inherit;">
            <?php echo $content; ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const trigger = document.querySelector('.mc-hamburger-trigger-<?php echo esc_attr($unique_id); ?>');
            const overlay = document.querySelector('.mc-drawer-overlay-<?php echo esc_attr($unique_id); ?>');
            const panel = document.querySelector('.mc-drawer-panel-<?php echo esc_attr($unique_id); ?>');
            const closeBtn = document.querySelector('.mc-drawer-close-<?php echo esc_attr($unique_id); ?>');

            const openDrawer = () => {
                overlay.style.opacity = '1';
                overlay.style.pointerEvents = 'auto';
                panel.style.transform = 'translate(0, 0)'; // Resets X or Y depending on type
                document.body.style.overflow = 'hidden'; // Prevents background scrolling
            };

            const closeDrawer = () => {
                overlay.style.opacity = '0';
                overlay.style.pointerEvents = 'none';
                document.body.style.overflow = '';
                
                // Reset transform based on drawer type
                const type = '<?php echo esc_attr($drawer_type); ?>';
                if (type === 'slide-right') panel.style.transform = 'translateX(100%)';
                else if (type === 'slide-left') panel.style.transform = 'translateX(-100%)';
                else panel.style.transform = 'translateY(-100%)';
            };

            if(trigger && overlay && panel && closeBtn) {
                trigger.addEventListener('click', openDrawer);
                closeBtn.addEventListener('click', closeDrawer);
                overlay.addEventListener('click', closeDrawer);
            }
        });
    </script>
</div>