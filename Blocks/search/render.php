<?php
$placeholder = $attributes['placeholder'] ?? 'Search for products...';
$shape = $attributes['shape'] ?? 'pill';
$show_icon = $attributes['showIcon'] ?? true;
$icon_position = $attributes['iconPosition'] ?? 'left';
$bg_color = $attributes['bgColor'] ?? '#f4f4f5';
$text_color = $attributes['textColor'] ?? '#000000';
$width = $attributes['width'] ?? 100;
$trending_tags = $attributes['trendingTags'] ?? 'Minimalist Watch, Canvas Tote, Linen Shirt';

$unique_id = uniqid('mc-search-');
$wrapper_attributes = get_block_wrapper_attributes([
    'class' => "mc-block modern-core-search {$unique_id}",
    'style' => "width: {$width}%; position: relative; margin: 0 auto;"
]);

$border_radius = $shape === 'pill' ? '50px' : ($shape === 'rounded' ? '8px' : '0px');
$is_icon_left = $icon_position === 'left';
?>

<div <?php echo $wrapper_attributes; ?>>
    
    <form role="search" method="get" class="mc-search-form-<?php echo esc_attr($unique_id); ?>" action="<?php echo esc_url(home_url('/')); ?>">
        <input type="hidden" name="post_type" value="product" />
        
        <div style="display: flex; align-items: center; background-color: <?php echo esc_attr($bg_color); ?>; border-radius: <?php echo esc_attr($border_radius); ?>; padding: 10px 20px; transition: box-shadow 0.3s ease; border: 1px solid rgba(0,0,0,0.05);" class="mc-search-input-wrapper-<?php echo esc_attr($unique_id); ?>">
            
            <?php if ($show_icon && $is_icon_left) : ?>
                <button type="submit" style="background:none; border:none; padding:0; cursor:pointer; display:flex;">
                    <svg style="color: <?php echo esc_attr($text_color); ?>; opacity: 0.6; margin-right: 12px; min-width: 18px;" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </button>
            <?php endif; ?>

            <input 
                type="search" 
                name="s"
                class="mc-live-search-input-<?php echo esc_attr($unique_id); ?>"
                placeholder="<?php echo esc_attr($placeholder); ?>" 
                autocomplete="off"
                style="border: none; background: transparent; outline: none; width: 100%; color: <?php echo esc_attr($text_color); ?>; font-size: 15px; box-shadow: none;"
            />

            <?php if ($show_icon && !$is_icon_left) : ?>
                <button type="submit" style="background:none; border:none; padding:0; cursor:pointer; display:flex;">
                    <svg style="color: <?php echo esc_attr($text_color); ?>; opacity: 0.6; margin-left: 12px; min-width: 18px;" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </button>
            <?php endif; ?>
        </div>
    </form>

    <div class="mc-search-results-<?php echo esc_attr($unique_id); ?>" style="position: absolute; top: calc(100% + 10px); left: 0; right: 0; background: #ffffff; border-radius: 12px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); border: 1px solid rgba(0,0,0,0.05); padding: 20px; z-index: 100; opacity: 0; pointer-events: none; transform: translateY(-10px); transition: all 0.2s ease;">
        
        <div class="mc-search-trending-<?php echo esc_attr($unique_id); ?>">
            <p style="font-size: 11px; text-transform: uppercase; color: #64748b; font-weight: 700; margin: 0 0 12px 0; letter-spacing: 1px;">Trending Right Now</p>
            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                <?php 
                $tags = explode(',', $trending_tags);
                foreach ($tags as $tag) : 
                    $clean_tag = trim($tag);
                    if (empty($clean_tag)) continue;
                ?>
                    <span class="mc-trending-tag" style="background: #f4f4f5; padding: 6px 14px; border-radius: 20px; font-size: 13px; color: #000; cursor: pointer; transition: background 0.2s;">
                        <?php echo esc_html($clean_tag); ?>
                    </span>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="mc-search-live-<?php echo esc_attr($unique_id); ?>" style="display: none; margin-top: 5px;">
            </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('.mc-search-form-<?php echo esc_attr($unique_id); ?>');
            const input = document.querySelector('.mc-live-search-input-<?php echo esc_attr($unique_id); ?>');
            const wrapper = document.querySelector('.mc-search-input-wrapper-<?php echo esc_attr($unique_id); ?>');
            const dropdown = document.querySelector('.mc-search-results-<?php echo esc_attr($unique_id); ?>');
            const trending = document.querySelector('.mc-search-trending-<?php echo esc_attr($unique_id); ?>');
            const live = document.querySelector('.mc-search-live-<?php echo esc_attr($unique_id); ?>');
            const trendingTags = document.querySelectorAll('.mc-search-trending-<?php echo esc_attr($unique_id); ?> .mc-trending-tag');

            let searchTimeout;

            // 1. Show Dropdown on Focus
            input.addEventListener('focus', () => {
                dropdown.style.opacity = '1';
                dropdown.style.pointerEvents = 'auto';
                dropdown.style.transform = 'translateY(0)';
                wrapper.style.borderColor = 'rgba(0, 0, 0, 0.2)';
            });

            // 2. Wire up Trending Tags to populate input and submit
            trendingTags.forEach(tag => {
                tag.addEventListener('click', () => {
                    input.value = tag.innerText;
                    form.submit();
                });
                // Add simple hover effect via JS
                tag.addEventListener('mouseenter', () => tag.style.background = '#e2e8f0');
                tag.addEventListener('mouseleave', () => tag.style.background = '#f4f4f5');
            });

            // 3. Native REST API Live Search
            input.addEventListener('input', (e) => {
                const term = e.target.value.trim();
                
                clearTimeout(searchTimeout);

                if (term.length > 2) {
                    trending.style.display = 'none';
                    live.style.display = 'block';
                    live.innerHTML = '<p style="font-size: 13px; color: #64748b; margin: 0;">Searching inventory...</p>';

                    // Debounce to prevent hammering the server
                    searchTimeout = setTimeout(() => {
                        fetch(`/wp-json/wp/v2/product?search=${encodeURIComponent(term)}&per_page=5`)
                            .then(response => {
                                if(!response.ok) throw new Error('API not available');
                                return response.json();
                            })
                            .then(data => {
                                if (data && data.length > 0) {
                                    let html = '<p style="font-size: 11px; text-transform: uppercase; color: #64748b; font-weight: 700; margin: 0 0 12px 0; letter-spacing: 1px;">Product Suggestions</p>';
                                    data.forEach(p => {
                                        // Attempt to safely extract the raw title text
                                        const titleText = p.title?.rendered ? p.title.rendered.replace(/&#8211;/g, '-').replace(/&amp;/g, '&') : 'Product';
                                        
                                        html += `
                                        <a href="${p.link}" style="display: flex; align-items: center; gap: 15px; margin-bottom: 12px; cursor: pointer; text-decoration: none; color: inherit;">
                                            <div style="width: 40px; height: 40px; background: #f4f4f5; border-radius: 6px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                                <svg style="color: #94a3b8;" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                            </div>
                                            <div style="flex:1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                <p style="margin: 0; font-size: 14px; font-weight: 600; color: #000; overflow: hidden; text-overflow: ellipsis;">${titleText}</p>
                                            </div>
                                        </a>`;
                                    });
                                    live.innerHTML = html;
                                } else {
                                    live.innerHTML = '<p style="font-size: 13px; color: #64748b; margin: 0;">No products found.</p>';
                                }
                            })
                            .catch(error => {
                                // If REST API is blocked or WooCommerce doesn't expose it, fallback gracefully
                                live.innerHTML = `<p style="font-size: 13px; color: #64748b; margin: 0;">Press enter to search for "${term}"</p>`;
                            });
                    }, 300); // 300ms debounce
                } else {
                    trending.style.display = 'block';
                    live.style.display = 'none';
                }
            });

            // 4. Close Dropdown logic
            document.addEventListener('click', (e) => {
                if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.style.opacity = '0';
                    dropdown.style.pointerEvents = 'none';
                    dropdown.style.transform = 'translateY(-10px)';
                    wrapper.style.borderColor = 'transparent';
                }
            });
        });
    </script>
</div>