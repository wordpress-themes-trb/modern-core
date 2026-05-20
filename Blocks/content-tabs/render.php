<?php
$style = $attributes['tabStyle'] ?? 'underline';
$layout = $attributes['layoutMode'] ?? 'horizontal';
$anim = $attributes['animStyle'] ?? 'fade';
$duration = $attributes['animDuration'] ?? 300;
$accent = $attributes['accentColor'] ?? '#0f172a';
$inactive = $attributes['inactiveColor'] ?? '#94a3b8';

$unique_id = uniqid('mc-ctabs-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "modern-core-content-tabs {$unique_id} style-{$style} layout-{$layout} anim-{$anim}"]);
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            --mc-accent: <?php echo esc_attr($accent); ?>;
            --mc-inactive: <?php echo esc_attr($inactive); ?>;
            --mc-dur: <?php echo esc_attr($duration); ?>ms;
            width: 100%; display: flex; flex-direction: column; gap: 30px;
        }
        
        /* Layout: Vertical Overrides */
        @media (min-width: 769px) {
            .<?php echo esc_attr($unique_id); ?>.layout-vertical { flex-direction: row; align-items: flex-start; gap: 50px; }
            .<?php echo esc_attr($unique_id); ?>.layout-vertical .mc-ctabs-nav-container { flex: 0 0 250px; flex-direction: column; align-items: flex-start; border-bottom: none; border-right: 1px solid rgba(0,0,0,0.1); }
            .<?php echo esc_attr($unique_id); ?>.layout-vertical .mc-ctabs-content-wrapper { flex: 1; min-width: 0; }
        }

        /* Nav Container */
        .<?php echo esc_attr($unique_id); ?> .mc-ctabs-nav-container {
            display: flex; gap: 15px; overflow-x: auto; scrollbar-width: none;
        }
        
        /* Nav Buttons */
        .<?php echo esc_attr($unique_id); ?> .mc-ctab-btn {
            background: transparent; border: none; font-size: 15px; font-weight: bold; color: var(--mc-inactive);
            cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 8px; white-space: nowrap;
        }
        
        /* Style: Underline */
        .<?php echo esc_attr($unique_id); ?>.style-underline .mc-ctabs-nav-container { border-bottom: 1px solid rgba(0,0,0,0.1); gap: 30px; }
        .<?php echo esc_attr($unique_id); ?>.style-underline .mc-ctab-btn { padding: 15px 0; border-bottom: 2px solid transparent; }
        .<?php echo esc_attr($unique_id); ?>.style-underline .mc-ctab-btn.is-active { color: var(--mc-accent); border-bottom-color: var(--mc-accent); }
        .<?php echo esc_attr($unique_id); ?>.style-underline.layout-vertical .mc-ctab-btn { padding: 15px 20px; border-bottom: none; border-left: 2px solid transparent; width: 100%; text-align: left; }
        .<?php echo esc_attr($unique_id); ?>.style-underline.layout-vertical .mc-ctab-btn.is-active { border-left-color: var(--mc-accent); }

        /* Style: Pill */
        .<?php echo esc_attr($unique_id); ?>.style-pill .mc-ctab-btn { padding: 10px 24px; border-radius: 50px; border: 1px solid var(--mc-inactive); }
        .<?php echo esc_attr($unique_id); ?>.style-pill .mc-ctab-btn.is-active { background: var(--mc-accent); color: #fff; border-color: var(--mc-accent); }

        /* Style: Box */
        .<?php echo esc_attr($unique_id); ?>.style-box .mc-ctab-btn { padding: 15px 24px; border: 1px solid rgba(0,0,0,0.1); border-radius: 8px; background: #f8fafc; }
        .<?php echo esc_attr($unique_id); ?>.style-box .mc-ctab-btn.is-active { background: #fff; border-color: var(--mc-accent); color: var(--mc-accent); box-shadow: 0 4px 15px rgba(0,0,0,0.05); }

        /* Content Wrapper & Panes */
        .<?php echo esc_attr($unique_id); ?> .mc-ctabs-content-wrapper { position: relative; width: 100%; }
        .<?php echo esc_attr($unique_id); ?> .mc-tab-pane {
            display: none; width: 100%; animation-duration: var(--mc-dur); animation-fill-mode: forwards;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-tab-pane.is-active { display: block; }

        /* Animations */
        .<?php echo esc_attr($unique_id); ?>.anim-fade .mc-tab-pane.is-active { animation-name: mcTabFade; }
        .<?php echo esc_attr($unique_id); ?>.anim-slide-h .mc-tab-pane.is-active { animation-name: mcTabSlideH; }
        .<?php echo esc_attr($unique_id); ?>.anim-slide-v .mc-tab-pane.is-active { animation-name: mcTabSlideV; }

        @keyframes mcTabFade { from { opacity: 0; } to { opacity: 1; } }
        @keyframes mcTabSlideH { from { opacity: 0; transform: translateX(30px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes mcTabSlideV { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>

    <div class="mc-ctabs-nav-container"></div>

    <div class="mc-ctabs-content-wrapper">
        <?php echo $content; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.querySelector('.<?php echo esc_attr($unique_id); ?>');
            if (!container) return;

            const navContainer = container.querySelector('.mc-ctabs-nav-container');
            const panes = container.querySelectorAll('.mc-tab-pane');
            
            if (panes.length === 0) return;

            // 1. Build Navigation dynamically from child pane data attributes
            panes.forEach((pane, index) => {
                const title = pane.dataset.title || `Tab ${index + 1}`;
                const icon = pane.dataset.icon;
                
                const btn = document.createElement('button');
                btn.className = `mc-ctab-btn ${index === 0 ? 'is-active' : ''}`;
                
                let iconHtml = '';
                if (icon === 'star') iconHtml = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
                if (icon === 'info') iconHtml = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>';
                if (icon === 'list') iconHtml = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>';
                
                btn.innerHTML = `${iconHtml}<span>${title}</span>`;
                
                // Click Listener for switching
                btn.addEventListener('click', () => {
                    // Reset
                    container.querySelectorAll('.mc-ctab-btn').forEach(b => b.classList.remove('is-active'));
                    panes.forEach(p => p.classList.remove('is-active'));
                    
                    // Activate
                    btn.classList.add('is-active');
                    void pane.offsetWidth; // Force DOM reflow to restart CSS animation
                    pane.classList.add('is-active');
                });
                
                navContainer.appendChild(btn);

                // Make first pane active by default
                if (index === 0) pane.classList.add('is-active');
            });
        });
    </script>
</div>