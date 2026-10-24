<?php
$width_mode = $attributes['widthMode'] ?? 'contained';
$panel_id = $attributes['panelId'] ?? 'mc-drawer-1';
$direction = $attributes['direction'] ?? 'right';
$animation_mode = $attributes['animationMode'] ?? 'overlay';
$width = $attributes['panelWidth'] ?? 400;
$blur = $attributes['overlayBlur'] ?? 10;
$opacity = ($attributes['overlayOpacity'] ?? 50) / 100;
$bg_color = $attributes['bgColor'] ?? 'rgba(15, 23, 42, 0.85)';
$text_color = $attributes['textColor'] ?? '#f8fafc';
$header_text = $attributes['headerText'] ?? 'Your Bag';
$delay = $attributes['triggerDelay'] ?? 0;
$scroll = (int) ( $attributes['triggerScroll'] ?? 0 );
$exit = $attributes['triggerExit'] ?? false;
$memory = $attributes['sessionMemory'] ?? false;

$wrapper_attributes = get_block_wrapper_attributes(['class' => 'mc-block width-" . esc_attr($width_mode) . " modern-core-offcanvas-system']);

// --- Physics Engine CSS Setup ---
$z_index = $animation_mode === 'reveal' ? 9990 : 99999; 
$panel_css = "background-color: {$bg_color}; color: {$text_color}; backdrop-filter: blur({$blur}px); -webkit-backdrop-filter: blur({$blur}px); position: fixed; z-index: {$z_index}; transition: transform 0.5s cubic-bezier(0.19, 1, 0.22, 1); display: flex; flex-direction: column;";

if ($direction === 'right') {
    $panel_css .= "top: 0; bottom: 0; right: 0; width: {$width}px; max-width: 100vw; border-left: 1px solid rgba(255,255,255,0.05);";
    if ($animation_mode !== 'reveal') $panel_css .= " transform: translateX(100%);"; 
} elseif ($direction === 'left') {
    $panel_css .= "top: 0; bottom: 0; left: 0; width: {$width}px; max-width: 100vw; border-right: 1px solid rgba(255,255,255,0.05);";
    if ($animation_mode !== 'reveal') $panel_css .= " transform: translateX(-100%);";
} elseif ($direction === 'top') {
    $panel_css .= "top: 0; left: 0; right: 0; height: auto; max-height: 90vh; transform: translateY(-100%); border-bottom: 1px solid rgba(255,255,255,0.05);";
} else {
    $panel_css .= "bottom: 0; left: 0; right: 0; height: auto; max-height: 90vh; transform: translateY(100%); border-top: 1px solid rgba(255,255,255,0.05);";
}
?>

<div <?php echo $wrapper_attributes; ?>>
    
    <div id="overlay-<?php echo esc_attr($panel_id); ?>" style="position: fixed; inset: 0; background-color: rgba(0,0,0,<?php echo esc_attr($opacity); ?>); z-index: 99998; opacity: 0; pointer-events: none; transition: opacity 0.4s ease;"></div>

    <div id="<?php echo esc_attr($panel_id); ?>" style="<?php echo esc_attr($panel_css); ?>">
        <div style="padding: 25px 30px; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;">
            <h3 style="margin: 0; font-size: 16px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: inherit;"><?php echo esc_html($header_text); ?></h3>
            <button class="mc-close-<?php echo esc_attr($panel_id); ?>" style="background: none; border: none; color: inherit; cursor: pointer; padding: 5px; opacity: 0.6; transition: opacity 0.2s ease;">✕</button>
        </div>
        <div style="flex: 1; overflow-y: auto; padding: 30px; scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.2) transparent;">
            <?php echo $content; ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const panelId = '<?php echo esc_attr($panel_id); ?>';
            const panel = document.getElementById(panelId);
            const overlay = document.getElementById(`overlay-${panelId}`);
            const siteWrapper = document.querySelector('.wp-site-blocks'); // Main FSE container
            
            const direction = '<?php echo esc_attr($direction); ?>';
            const mode = '<?php echo esc_attr($animation_mode); ?>';
            const width = <?php echo (int)$width; ?>;
            const useMemory = <?php echo $memory ? 'true' : 'false'; ?>;
            const hasSeenKey = `mc_seen_${panelId}`;

            // Check Session Memory
            if (useMemory && sessionStorage.getItem(hasSeenKey)) return;

            const openPanel = () => {
                if (useMemory) sessionStorage.setItem(hasSeenKey, 'true');
                
                overlay.style.opacity = '1';
                overlay.style.pointerEvents = 'auto';
                document.body.style.overflow = 'hidden';

                // Overlay Physics (Default)
                if (mode === 'overlay' && direction !== 'reveal') {
                    panel.style.transform = 'translate(0, 0)';
                }
                
                // Push & Reveal Physics
                if (siteWrapper && window.innerWidth > 768) {
                    siteWrapper.style.transition = 'transform 0.5s cubic-bezier(0.19, 1, 0.22, 1)';
                    if (mode === 'push' || mode === 'reveal') {
                        if (mode === 'push') panel.style.transform = 'translate(0, 0)';
                        if (direction === 'right') siteWrapper.style.transform = `translateX(-${width}px)`;
                        if (direction === 'left') siteWrapper.style.transform = `translateX(${width}px)`;
                    }
                }
            };

            const closePanel = () => {
                overlay.style.opacity = '0';
                overlay.style.pointerEvents = 'none';
                document.body.style.overflow = '';
                
                if (mode !== 'reveal') {
                    if (direction === 'right') panel.style.transform = 'translateX(100%)';
                    else if (direction === 'left') panel.style.transform = 'translateX(-100%)';
                    else if (direction === 'top') panel.style.transform = 'translateY(-100%)';
                    else panel.style.transform = 'translateY(100%)';
                }

                if (siteWrapper) siteWrapper.style.transform = 'translate(0, 0)';
            };

            // Standard Click Triggers
            document.querySelector(`.mc-close-${panelId}`).addEventListener('click', closePanel);
            overlay.addEventListener('click', closePanel);
            document.querySelectorAll(`a[href="#${panelId}"], button[data-target="${panelId}"]`).forEach(btn => {
                btn.addEventListener('click', (e) => { e.preventDefault(); openPanel(); });
            });
            document.addEventListener(`mc:open:${panelId}`, openPanel);
            document.addEventListener(`mc:close:${panelId}`, closePanel);

            // AUTOMATIC TRIGGERS
            // 1. Time Delay
            <?php if ($delay > 0) : ?>
                setTimeout(openPanel, <?php echo $delay * 1000; ?>);
            <?php endif; ?>

            // 2. Scroll Depth
            <?php if ($scroll > 0) : ?>
                const scrollTrigger = () => {
                    const scrolled = (window.scrollY / (document.body.scrollHeight - window.innerHeight)) * 100;
                    if (scrolled >= <?php echo $scroll; ?>) {
                        openPanel();
                        window.removeEventListener('scroll', scrollTrigger);
                    }
                };
                window.addEventListener('scroll', scrollTrigger);
            <?php endif; ?>

            // 3. Exit Intent (Desktop only)
            <?php if ($exit) : ?>
                const exitTrigger = (e) => {
                    if (e.clientY < 10) { // Mouse moving up to address bar
                        openPanel();
                        document.removeEventListener('mouseout', exitTrigger);
                    }
                };
                document.addEventListener('mouseout', exitTrigger);
            <?php endif; ?>
        });
    </script>
</div>