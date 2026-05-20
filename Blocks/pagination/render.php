<?php
if (!class_exists('WooCommerce')) return;

// This block only makes sense on archive pages or product queries
global $wp_query;
if ($wp_query->max_num_pages <= 1) return;

$mode = $attributes['mode'] ?? 'numbers';
$align = $attributes['alignment'] ?? 'center';
$prev_next = $attributes['prevNextStyle'] ?? 'both';
$load_txt = $attributes['loadMoreText'] ?? 'Load More Products';
$loading_txt = $attributes['loadingText'] ?? 'Loading...';
$radius = $attributes['buttonRadius'] ?? 8;
$accent = $attributes['accentColor'] ?? '#3b82f6';
$text_c = $attributes['textColor'] ?? '#0f172a';
$border_c = $attributes['borderColor'] ?? '#e2e8f0';

$unique_id = uniqid('mc-pag-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "modern-core-pagination {$unique_id} mode-{$mode}"]);

$prev_text = ($prev_next === 'both' || $prev_next === 'text') ? __('Previous', 'woocommerce') : '';
$next_text = ($prev_next === 'both' || $prev_next === 'text') ? __('Next', 'woocommerce') : '';

if ($prev_next === 'both' || $prev_next === 'icon') {
    $prev_text = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:5px;"><polyline points="15 18 9 12 15 6"></polyline></svg>' . $prev_text;
    $next_text = $next_text . '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-left:5px;"><polyline points="9 18 15 12 9 6"></polyline></svg>';
}

$pagination_html = paginate_links(apply_filters('woocommerce_pagination_args', [
    'base'      => esc_url_raw(str_replace(999999999, '%#%', remove_query_arg('add-to-cart', get_pagenum_link(999999999, false)))),
    'format'    => '',
    'add_args'  => false,
    'current'   => max(1, get_query_var('paged')),
    'total'     => $wp_query->max_num_pages,
    'prev_text' => $prev_text,
    'next_text' => $next_text,
    'type'      => 'list',
    'end_size'  => 2,
    'mid_size'  => 1,
]));
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            width: 100%; padding: 40px 0; display: flex; justify-content: <?php echo esc_attr($align); ?>;
        }

        /* Native WooCommerce Ul List Override */
        .<?php echo esc_attr($unique_id); ?> ul.page-numbers {
            display: flex; flex-wrap: wrap; align-items: center; gap: 8px; list-style: none; padding: 0; margin: 0;
        }

        .<?php echo esc_attr($unique_id); ?> .page-numbers {
            display: flex; align-items: center; justify-content: center;
            min-width: 40px; height: 40px; padding: 0 15px;
            border: 1px solid <?php echo esc_attr($border_c); ?>; border-radius: <?php echo esc_attr($radius); ?>px;
            color: <?php echo esc_attr($text_c); ?>; font-size: 14px; font-weight: bold; background: #fff;
            text-decoration: none; transition: all 0.2s;
        }
        .<?php echo esc_attr($unique_id); ?> a.page-numbers:hover {
            border-color: <?php echo esc_attr($accent); ?>; color: <?php echo esc_attr($accent); ?>;
        }
        .<?php echo esc_attr($unique_id); ?> .page-numbers.current {
            background: <?php echo esc_attr($accent); ?>; border-color: <?php echo esc_attr($accent); ?>; color: #fff;
        }
        .<?php echo esc_attr($unique_id); ?> .page-numbers.dots {
            border: none; background: transparent; opacity: 0.5; padding: 0 5px;
        }

        /* Hide native pagination if we are using AJAX modes */
        .<?php echo esc_attr($unique_id); ?>.mode-load-more ul.page-numbers,
        .<?php echo esc_attr($unique_id); ?>.mode-infinite ul.page-numbers {
            display: none;
        }

        /* Custom AJAX Elements */
        .<?php echo esc_attr($unique_id); ?> .mc-ajax-btn {
            padding: 16px 40px; border: 2px solid <?php echo esc_attr($accent); ?>; color: <?php echo esc_attr($accent); ?>;
            border-radius: <?php echo esc_attr($radius); ?>px; font-weight: bold; font-size: 15px; cursor: pointer;
            background: transparent; transition: all 0.2s;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-ajax-btn:hover { background: <?php echo esc_attr($accent); ?>; color: #fff; }
        .<?php echo esc_attr($unique_id); ?> .mc-ajax-btn.is-loading { opacity: 0.5; pointer-events: none; }

        .<?php echo esc_attr($unique_id); ?> .mc-inf-loader {
            display: none; padding: 20px; text-align: center; color: <?php echo esc_attr($text_c); ?>; font-weight: bold;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-inf-loader.is-active { display: block; }
    </style>

    <?php if ($mode === 'numbers') : ?>
        <nav class="woocommerce-pagination">
            <?php echo $pagination_html; ?>
        </nav>
    <?php endif; ?>

    <?php if ($mode === 'load-more' || $mode === 'infinite') : ?>
        <nav class="mc-hidden-nav" style="display:none;">
            <?php echo $pagination_html; ?>
        </nav>

        <?php if ($mode === 'load-more') : ?>
            <button class="mc-ajax-btn" data-loading="<?php echo esc_attr($loading_txt); ?>"><?php echo esc_html($load_txt); ?></button>
        <?php endif; ?>

        <?php if ($mode === 'infinite') : ?>
            <div class="mc-inf-loader"><?php echo esc_html($loading_txt); ?></div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($mode !== 'numbers') : ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const wrapper = document.querySelector('.<?php echo esc_attr($unique_id); ?>');
            if (!wrapper) return;

            const mode = '<?php echo esc_attr($mode); ?>';
            const hiddenNav = wrapper.querySelector('.mc-hidden-nav');
            const ajaxBtn = wrapper.querySelector('.mc-ajax-btn');
            const infLoader = wrapper.querySelector('.mc-inf-loader');
            
            // The container where products exist (relies on our grid block classes)
            const targetContainer = document.querySelector('.modern-core-product-grid .mc-grid-container');
            if (!targetContainer) return;

            let isLoading = false;

            const getNextUrl = () => {
                const nextLink = hiddenNav.querySelector('.next.page-numbers');
                return nextLink ? nextLink.href : null;
            };

            const fetchNextPage = () => {
                const nextUrl = getNextUrl();
                if (!nextUrl || isLoading) return;

                isLoading = true;
                if (ajaxBtn) {
                    ajaxBtn.classList.add('is-loading');
                    ajaxBtn.innerText = ajaxBtn.dataset.loading;
                }
                if (infLoader) infLoader.classList.add('is-active');

                fetch(nextUrl)
                    .then(res => res.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');

                        // 1. Extract new product cards
                        const newGrid = doc.querySelector('.modern-core-product-grid .mc-grid-container');
                        if (newGrid) {
                            Array.from(newGrid.children).forEach(card => {
                                targetContainer.appendChild(card);
                            });
                        }

                        // 2. Extract and replace the hidden navigation block
                        const newNav = doc.querySelector('.<?php echo esc_attr($unique_id); ?> .mc-hidden-nav');
                        if (newNav) {
                            hiddenNav.innerHTML = newNav.innerHTML;
                        }

                        // 3. Complete process
                        isLoading = false;
                        
                        if (ajaxBtn) {
                            ajaxBtn.classList.remove('is-loading');
                            ajaxBtn.innerText = '<?php echo esc_attr($load_txt); ?>';
                            // Hide button if no more pages
                            if (!getNextUrl()) ajaxBtn.style.display = 'none';
                        }
                        
                        if (infLoader) {
                            infLoader.classList.remove('is-active');
                        }
                    })
                    .catch(() => {
                        isLoading = false;
                        if (ajaxBtn) {
                            ajaxBtn.classList.remove('is-loading');
                            ajaxBtn.innerText = 'Error loading. Try again.';
                        }
                    });
            };

            // Event Listeners
            if (mode === 'load-more' && ajaxBtn) {
                ajaxBtn.addEventListener('click', fetchNextPage);
            }

            if (mode === 'infinite') {
                const observer = new IntersectionObserver((entries) => {
                    if (entries[0].isIntersecting && !isLoading && getNextUrl()) {
                        fetchNextPage();
                    }
                }, { rootMargin: '0px 0px 400px 0px' }); // Trigger 400px before reaching the bottom

                observer.observe(wrapper);
            }
        });
    </script>
    <?php endif; ?>
</div>