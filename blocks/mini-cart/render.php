<?php
$width_mode = $attributes['widthMode'] ?? 'contained';
if (!class_exists('WooCommerce')) return;

$auto_open = $attributes['autoOpen'] ?? true;
$open_delay = $attributes['openDelay'] ?? 400;
$show_undo = $attributes['showUndo'] ?? true;
$undo_dur = $attributes['undoDuration'] ?? 4000;
$width = $attributes['drawerWidth'] ?? 400;
$title = $attributes['drawerTitle'] ?? 'Your Cart';
$e_head = $attributes['emptyHeading'] ?? 'Your cart is empty';
$e_msg = $attributes['emptyMessage'] ?? 'Looks like you haven\'t added anything yet.';
$e_btn = $attributes['emptyBtnText'] ?? 'Start Shopping';
$e_link = $attributes['emptyBtnLink'] ?? '/shop';
$bg = $attributes['bgColor'] ?? '#ffffff';
$text_c = $attributes['textColor'] ?? '#0f172a';
$accent = $attributes['accentColor'] ?? '#3b82f6';
$opacity = ($attributes['overlayOpacity'] ?? 50) / 100;

$unique_id = uniqid('mc-cart-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "mc-block width-" . esc_attr($width_mode) . " modern-core-minicart {$unique_id}"]);
?>

<div <?php echo $wrapper_attributes; ?> 
    data-auto="<?php echo esc_attr($auto_open); ?>" 
    data-delay="<?php echo esc_attr($open_delay); ?>"
    data-undo="<?php echo esc_attr($show_undo); ?>"
    data-dur="<?php echo esc_attr($undo_dur); ?>">
    
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            --mc-width: <?php echo esc_attr($width); ?>px;
            --mc-bg: <?php echo esc_attr($bg); ?>;
            --mc-text: <?php echo esc_attr($text_c); ?>;
            --mc-acc: <?php echo esc_attr($accent); ?>;
            --mc-op: <?php echo esc_attr($opacity); ?>;
        }

        /* Overlay */
        .<?php echo esc_attr($unique_id); ?> .mc-cart-overlay {
            position: fixed; inset: 0; z-index: 999998;
            background: rgba(0,0,0, var(--mc-op));
            opacity: 0; visibility: hidden; pointer-events: none;
            transition: opacity 0.4s ease, visibility 0.4s;
        }

        /* Drawer */
        .<?php echo esc_attr($unique_id); ?> .mc-cart-drawer {
            position: fixed; top: 0; right: 0; bottom: 0; z-index: 999999;
            width: var(--mc-width); max-width: 100%;
            background: var(--mc-bg); color: var(--mc-text);
            box-shadow: -10px 0 40px rgba(0,0,0,0.1);
            display: flex; flex-direction: column;
            transform: translateX(100%);
            visibility: hidden;
            transition: transform 0.5s cubic-bezier(0.165, 0.84, 0.44, 1), visibility 0s linear 0.5s;
        }

        /* Open State */
        .<?php echo esc_attr($unique_id); ?>.is-open .mc-cart-overlay { opacity: 1; visibility: visible; pointer-events: auto; }
        .<?php echo esc_attr($unique_id); ?>.is-open .mc-cart-drawer { transform: translateX(0); visibility: visible; transition: transform 0.5s cubic-bezier(0.165, 0.84, 0.44, 1), visibility 0s; }

        /* Internal Structure */
        .<?php echo esc_attr($unique_id); ?> .mc-cart-header {
            padding: 25px; border-bottom: 1px solid rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-cart-title { margin: 0; font-size: 20px; font-weight: 800; }
        .<?php echo esc_attr($unique_id); ?> .mc-cart-close { cursor: pointer; font-size: 22px; opacity: 0.5; background: none; border: 0; padding: 4px; color: inherit; transition: opacity 0.2s; line-height: 1; }
        .<?php echo esc_attr($unique_id); ?> .mc-cart-close:hover { opacity: 1; }

        .<?php echo esc_attr($unique_id); ?> .mc-cart-body { flex: 1; overflow-y: auto; position: relative; }
        .<?php echo esc_attr($unique_id); ?> .mc-cart-footer { padding: 25px; border-top: 1px solid rgba(0,0,0,0.05); background: var(--mc-bg); }

        /* Empty State */
        .<?php echo esc_attr($unique_id); ?> .mc-cart-empty {
            display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; padding: 40px 20px; text-align: center;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-cart-empty svg { opacity: 0.2; margin-bottom: 20px; }
        .<?php echo esc_attr($unique_id); ?> .mc-cart-empty-hl { font-size: 20px; font-weight: bold; margin: 0 0 10px 0; }
        .<?php echo esc_attr($unique_id); ?> .mc-cart-empty-sub { opacity: 0.7; font-size: 15px; margin: 0 0 30px 0; }
        
        .<?php echo esc_attr($unique_id); ?> .mc-cart-btn {
            display: block; width: 100%; padding: 16px; background: var(--mc-acc); color: #fff;
            text-align: center; border-radius: 6px; font-weight: bold; text-decoration: none; transition: opacity 0.2s;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-cart-btn:hover { opacity: 0.9; }

        /* WooCommerce Fragment Injection Styling */
        .<?php echo esc_attr($unique_id); ?> .widget_shopping_cart_content { height: 100%; display: flex; flex-direction: column; }
        .<?php echo esc_attr($unique_id); ?> .woocommerce-mini-cart { flex: 1; overflow-y: auto; padding: 25px; margin: 0; list-style: none; }
        .<?php echo esc_attr($unique_id); ?> .woocommerce-mini-cart__total { margin: 0 0 20px 0; display: flex; justify-content: space-between; font-size: 18px; font-weight: bold; }
        .<?php echo esc_attr($unique_id); ?> .woocommerce-mini-cart__buttons { margin: 0; display: flex; flex-direction: column; gap: 10px; }
        
        /* Undo specific states */
        .<?php echo esc_attr($unique_id); ?> .mc-undo-state { display: flex; justify-content: space-between; padding: 15px; background: rgba(0,0,0,0.03); border-radius: 6px; font-size: 14px; }
        .<?php echo esc_attr($unique_id); ?> .mc-undo-btn { color: var(--mc-acc); font-weight: bold; cursor: pointer; text-decoration: underline; }
    </style>

    <div class="mc-cart-overlay"></div>
    
    <div class="mc-cart-drawer" role="dialog" aria-modal="true" aria-label="<?php echo esc_attr( $title ); ?>">
        <div class="mc-cart-header">
            <h3 class="mc-cart-title"><?php echo esc_html($title); ?> <span class="mc-cart-count"></span></h3>
            <button type="button" class="mc-cart-close" aria-label="<?php esc_attr_e( 'Close cart', 'modern-core' ); ?>">&#x2715;</button>
        </div>
        
        <div class="mc-cart-body">
            <div class="widget_shopping_cart_content">
                <?php
                // Real cart contents on first paint; WooCommerce fragments keep it in sync afterwards.
                if ( function_exists( 'WC' ) && WC()->cart && ! WC()->cart->is_empty() ) {
                    woocommerce_mini_cart();
                } else { ?>
                <div class="mc-cart-empty">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" aria-hidden="true"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                    <h4 class="mc-cart-empty-hl"><?php echo esc_html($e_head); ?></h4>
                    <p class="mc-cart-empty-sub"><?php echo esc_html($e_msg); ?></p>
                    <a href="<?php echo esc_url( 0 === strpos( $e_link, '/' ) ? home_url( $e_link ) : $e_link ); ?>" class="mc-cart-btn"><?php echo esc_html($e_btn); ?></a>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const wrapper = document.querySelector('.<?php echo esc_attr($unique_id); ?>');
            if (!wrapper) return;

            const overlay = wrapper.querySelector('.mc-cart-overlay');
            const closeBtn = wrapper.querySelector('.mc-cart-close');
            
            const autoOpen = wrapper.dataset.auto === '1';
            const openDelay = parseInt(wrapper.dataset.delay) || 400;

            const openCart = () => { wrapper.classList.add('is-open'); if (closeBtn) setTimeout(() => closeBtn.focus(), 50); };
            const closeCart = () => wrapper.classList.remove('is-open');
            document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && wrapper.classList.contains('is-open')) closeCart(); });

            // Manual interactions
            if (overlay) overlay.addEventListener('click', closeCart);
            if (closeBtn) closeBtn.addEventListener('click', closeCart);

            // Global trigger support for header cart icons (e.g. adding class 'mc-trigger-cart' to header icon)
            document.querySelectorAll('.mc-trigger-cart').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    openCart();
                });
            });

            // WooCommerce Native Integration
            if (typeof jQuery !== 'undefined') {
                // Listen to Add to Cart event
                jQuery(document.body).on('added_to_cart', function() {
                    if (autoOpen) {
                        setTimeout(openCart, openDelay);
                    }
                });

                // Listen to Cart Updates to update our custom header count
                jQuery(document.body).on('updated_cart_totals wc_fragments_refreshed', function() {
                    // This fetches the count from the WC cart session if available
                    jQuery.ajax({
                        url: mc_ajax.ajaxurl, 
                        type: 'POST',
                        data: { action: 'mc_get_cart_count' },
                        success: function(response) {
                            const countEl = wrapper.querySelector('.mc-cart-count');
                            if (response.success && countEl) {
                                const count = parseInt(response.data.count);
                                countEl.innerText = count > 0 ? `(${count})` : '';
                            }
                        }
                    });
                });
            }
        });
    </script>
</div>