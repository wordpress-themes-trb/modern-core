<?php
$placeholder = $attributes['placeholder'] ?? 'Search for products...';
$shape = $attributes['shape'] ?? 'pill';
$show_icon = $attributes['showIcon'] ?? true;
$icon_position = $attributes['iconPosition'] ?? 'right';
$bg_color = $attributes['bgColor'] ?? '#f1f5f9';
$text_color = $attributes['textColor'] ?? '#0f172a';
$width = $attributes['width'] ?? 100;

$unique_id = uniqid('mc-search-');
$wrapper_attributes = get_block_wrapper_attributes([
    'style' => "width: {$width}%; position: relative;"
]);

$border_radius = $shape === 'pill' ? '50px' : ($shape === 'rounded' ? '8px' : '0px');
$is_icon_left = $icon_position === 'left';
?>

<div <?php echo $wrapper_attributes; ?>>
    
    <div style="display: flex; align-items: center; background-color: <?php echo esc_attr($bg_color); ?>; border-radius: <?php echo esc_attr($border_radius); ?>; padding: 10px 20px; transition: box-shadow 0.3s ease;" class="mc-search-input-wrapper-<?php echo esc_attr($unique_id); ?>">
        
        <?php if ($show_icon && $is_icon_left) : ?>
            <svg style="color: <?php echo esc_attr($text_color); ?>; opacity: 0.6; margin-right: 12px; min-width: 18px;" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        <?php endif; ?>

        <input 
            type="text" 
            class="mc-live-search-input-<?php echo esc_attr($unique_id); ?>"
            placeholder="<?php echo esc_attr($placeholder); ?>" 
            style="border: none; background: transparent; outline: none; width: 100%; color: <?php echo esc_attr($text_color); ?>; font-size: 15px;"
        />

        <?php if ($show_icon && !$is_icon_left) : ?>
            <svg style="color: <?php echo esc_attr($text_color); ?>; opacity: 0.6; margin-left: 12px; min-width: 18px;" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        <?php endif; ?>
    </div>

    <div class="mc-search-results-<?php echo esc_attr($unique_id); ?>" style="position: absolute; top: calc(100% + 10px); left: 0; right: 0; background: #ffffff; border-radius: 12px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); padding: 20px; z-index: 100; opacity: 0; pointer-events: none; transform: translateY(-10px); transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);">
        
        <div class="mc-search-trending-<?php echo esc_attr($unique_id); ?>">
            <p style="font-size: 11px; text-transform: uppercase; color: #94a3b8; font-weight: 700; margin: 0 0 12px 0; letter-spacing: 1px;">Trending Right Now</p>
            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                <span style="background: #f1f5f9; padding: 6px 14px; border-radius: 20px; font-size: 13px; color: #334155; cursor: pointer;">Minimalist Watch</span>
                <span style="background: #f1f5f9; padding: 6px 14px; border-radius: 20px; font-size: 13px; color: #334155; cursor: pointer;">Canvas Tote</span>
                <span style="background: #f1f5f9; padding: 6px 14px; border-radius: 20px; font-size: 13px; color: #334155; cursor: pointer;">Linen Shirt</span>
            </div>
        </div>

        <div class="mc-search-live-<?php echo esc_attr($unique_id); ?>" style="display: none; margin-top: 15px;">
            <p style="font-size: 11px; text-transform: uppercase; color: #94a3b8; font-weight: 700; margin: 0 0 12px 0; letter-spacing: 1px;">Product Suggestions</p>
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px; cursor: pointer;">
                <div style="width: 40px; height: 40px; background: #e2e8f0; border-radius: 6px;"></div>
                <div>
                    <p style="margin: 0; font-size: 14px; font-weight: 600; color: #0f172a;">Searching product...</p>
                    <p style="margin: 0; font-size: 12px; color: #64748b;">$99.00</p>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const input = document.querySelector('.mc-live-search-input-<?php echo esc_attr($unique_id); ?>');
            const wrapper = document.querySelector('.mc-search-input-wrapper-<?php echo esc_attr($unique_id); ?>');
            const dropdown = document.querySelector('.mc-search-results-<?php echo esc_attr($unique_id); ?>');
            const trending = document.querySelector('.mc-search-trending-<?php echo esc_attr($unique_id); ?>');
            const live = document.querySelector('.mc-search-live-<?php echo esc_attr($unique_id); ?>');

            // Open dropdown on focus
            input.addEventListener('focus', () => {
                dropdown.style.opacity = '1';
                dropdown.style.pointerEvents = 'auto';
                dropdown.style.transform = 'translateY(0)';
                wrapper.style.boxShadow = '0 0 0 2px rgba(15, 23, 42, 0.1)';
            });

            // Simulate Live Search typing
            input.addEventListener('input', (e) => {
                if(e.target.value.length > 2) {
                    trending.style.display = 'none';
                    live.style.display = 'block';
                    // Here is where you would normally do an AJAX fetch to WooCommerce!
                } else {
                    trending.style.display = 'block';
                    live.style.display = 'none';
                }
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.style.opacity = '0';
                    dropdown.style.pointerEvents = 'none';
                    dropdown.style.transform = 'translateY(-10px)';
                    wrapper.style.boxShadow = 'none';
                }
            });
        });
    </script>
</div>