<?php
if ( ! class_exists( 'WooCommerce' ) ) return;

$product_id   = (int) ( $attributes['productId'] ?? 0 );
// No product chosen yet: show the newest featured product, then the newest product.
if ( ! $product_id ) {
    $picked     = wc_get_products( array( 'featured' => true, 'status' => 'publish', 'limit' => 1, 'return' => 'ids' ) );
    $picked     = $picked ? $picked : wc_get_products( array( 'status' => 'publish', 'limit' => 1, 'return' => 'ids' ) );
    $product_id = $picked ? (int) $picked[0] : 0;
}
if ( ! $product_id ) return;

global $post, $product;
$post    = get_post( $product_id );
$product = wc_get_product( $product_id );
if ( ! $product ) return;

$layout        = $attributes['layoutMode']    ?? 'side-by-side';
$pre_head      = $attributes['preHeadline']   ?? '';
$show_rating   = $attributes['showRating']    ?? true;
$show_price    = $attributes['showPrice']     ?? true;
$show_excerpt  = $attributes['showExcerpt']   ?? true;
$show_gallery  = $attributes['showGallery']   ?? true;
$show_qty      = $attributes['showQuantity']  ?? true;
$show_buy_now  = $attributes['showBuyNow']    ?? true;
$img_bg        = $attributes['imageBg']       ?? '#f8fafc';
$content_bg    = $attributes['contentBg']     ?? '#ffffff';
$text_color    = $attributes['textColor']     ?? '#0f172a';
$accent        = $attributes['accentColor']   ?? '#000000';
$btn_style     = $attributes['buttonStyle']   ?? 'outline';
$buy_now_style = $attributes['buyNowStyle']   ?? 'solid';

$uid           = 'mc-featprod-' . uniqid();
$main_img_url  = modern_core_product_image_url( $product, 'large' );
$gallery_ids   = $product->get_gallery_image_ids();
$checkout_url  = wc_get_checkout_url();

$wrapper_attributes = get_block_wrapper_attributes( [
    'id'    => esc_attr( $uid ),
    'class' => "mc-block modern-core-featured-product layout-{$layout}",
] );
?>

<div <?php echo $wrapper_attributes; ?>>
<style>
#<?php echo esc_attr($uid); ?> {
    display:          flex;
    flex-direction:   <?php echo $layout === 'stacked' ? 'column' : 'row'; ?>;
    background-color: <?php echo esc_attr($content_bg); ?>;
    color:            <?php echo esc_attr($text_color); ?>;
    border-radius:    16px;
    overflow:         hidden;
    box-shadow:       0 20px 40px rgba(0,0,0,0.05);
    margin:           40px auto;
    max-width:        <?php echo $layout === 'stacked' ? '600px' : '1200px'; ?>;
    box-sizing:       border-box;
    --mc-accent: <?php echo esc_attr($accent); ?>;
    --mc-imgbg:  <?php echo esc_attr($img_bg); ?>;
}
@media (max-width: 900px) {
    #<?php echo esc_attr($uid); ?> { flex-direction: column; }
}

/* ── IMAGE ZONE ─────────────────────────────────────────── */
#<?php echo esc_attr($uid); ?> .mc-fp-image-zone {
    flex:            1;
    background:      var(--mc-imgbg);
    padding:         40px;
    display:         flex;
    flex-direction:  column;
    align-items:     center;
    justify-content: center;
}
#<?php echo esc_attr($uid); ?> .mc-fp-main-img {
    width:         100%;
    aspect-ratio:  3/4;
    object-fit:    cover;
    border-radius: 8px;
    box-shadow:    0 10px 30px rgba(0,0,0,0.1);
    transition:    opacity 0.3s ease;
    display:       block;
}
#<?php echo esc_attr($uid); ?> .mc-fp-gallery {
    display:         flex;
    gap:             12px;
    margin-top:      20px;
    width:           100%;
    justify-content: center;
    overflow-x:      auto;
    padding-bottom:  8px;
}
#<?php echo esc_attr($uid); ?> .mc-fp-thumb {
    width:         60px;
    height:        60px;
    border-radius: 6px;
    object-fit:    cover;
    cursor:        pointer;
    border:        2px solid transparent;
    opacity:       0.6;
    transition:    all 0.2s ease;
    flex-shrink:   0;
}
#<?php echo esc_attr($uid); ?> .mc-fp-thumb.is-active {
    border-color: var(--mc-accent);
    opacity:      1;
}

/* ── CONTENT ZONE ───────────────────────────────────────── */
#<?php echo esc_attr($uid); ?> .mc-fp-content-zone {
    flex:           1;
    padding:        60px 8%;
    display:        flex;
    flex-direction: column;
    justify-content:center;
    box-sizing:     border-box;
}
#<?php echo esc_attr($uid); ?> .mc-fp-pre    { font-size: 11px; text-transform: uppercase; letter-spacing: 2px; color: var(--mc-accent); font-weight: 700; margin-bottom: 15px; display: block; }
#<?php echo esc_attr($uid); ?> .mc-fp-title  { font-size: 36px; font-weight: 800; line-height: 1.1; margin: 0 0 15px; }
#<?php echo esc_attr($uid); ?> .mc-fp-price  { font-size: 24px; font-weight: 700; margin-bottom: 20px; }
#<?php echo esc_attr($uid); ?> .mc-fp-price del { opacity: 0.5; font-size: 18px; font-weight: 400; margin-left: 10px; }
#<?php echo esc_attr($uid); ?> .mc-fp-price ins { text-decoration: none; color: #ef4444; }
#<?php echo esc_attr($uid); ?> .mc-fp-excerpt { font-size: 16px; opacity: 0.7; line-height: 1.6; margin-bottom: 30px; }

/* ── WOOCOMMERCE FORM ───────────────────────────────────── */
#<?php echo esc_attr($uid); ?> form.cart { margin-top: 20px; }
#<?php echo esc_attr($uid); ?> .variations { width: 100%; margin-bottom: 20px; }
#<?php echo esc_attr($uid); ?> .variations label { font-size: 12px; font-weight: 700; text-transform: uppercase; }
#<?php echo esc_attr($uid); ?> .variations select { width: 100%; padding: 12px; border: 1px solid rgba(0,0,0,0.12); border-radius: 6px; background: transparent; color: inherit; }

/* ── QUANTITY ───────────────────────────────────────────── */
#<?php echo esc_attr($uid); ?> .quantity {
    display:     <?php echo $show_qty ? 'flex' : 'none'; ?> !important;
    align-items: center;
    border:      1px solid rgba(0,0,0,0.12);
    border-radius:8px;
    overflow:    hidden;
    width:       max-content;
    margin-bottom:16px;
}
#<?php echo esc_attr($uid); ?> .quantity input.qty {
    width:      56px;
    height:     48px;
    text-align: center;
    border:     none;
    background: transparent;
    font-weight:700;
    font-size:  16px;
    color:      inherit;
    padding:    0;
    margin:     0;
    -moz-appearance: textfield;
}
#<?php echo esc_attr($uid); ?> .quantity input.qty::-webkit-outer-spin-button,
#<?php echo esc_attr($uid); ?> .quantity input.qty::-webkit-inner-spin-button { -webkit-appearance: none; }
#<?php echo esc_attr($uid); ?> .mc-qty-btn {
    width:           44px;
    height:          48px;
    display:         flex;
    align-items:     center;
    justify-content: center;
    background:      var(--mc-imgbg);
    cursor:          pointer;
    font-weight:     700;
    font-size:       20px;
    user-select:     none;
    transition:      background 0.2s;
    color:           inherit;
    border:          none;
    flex-shrink:     0;
}
#<?php echo esc_attr($uid); ?> .mc-qty-btn:hover { filter: brightness(0.92); }

/* ── ACTION ROW ─────────────────────────────────────────── */
#<?php echo esc_attr($uid); ?> .mc-action-row {
    display:   flex;
    gap:       12px;
    flex-wrap: wrap;
    width:     100%;
    margin-top:8px;
    align-items:stretch;
}

#<?php echo esc_attr($uid); ?> .woocommerce-variation-add-to-cart,
#<?php echo esc_attr($uid); ?> form.cart > .mc-action-row { display: flex !important; }
#<?php echo esc_attr($uid); ?> form.cart > p:has(.single_add_to_cart_button) { margin: 0; }

#<?php echo esc_attr($uid); ?> .single_add_to_cart_button,
#<?php echo esc_attr($uid); ?> .mc-buy-now-btn {
    flex:            1;
    padding:         15px 20px;
    text-align:      center;
    font-size:       15px;
    font-weight:     700;
    border-radius:   8px;
    cursor:          pointer;
    transition:      opacity 0.2s, transform 0.15s;
    border:          2px solid transparent;
    min-width:       130px;
    display:         flex !important;
    align-items:     center;
    justify-content: center;
    text-decoration: none;
    box-sizing:      border-box;
    line-height:     1;
}
#<?php echo esc_attr($uid); ?> .single_add_to_cart_button:hover,
#<?php echo esc_attr($uid); ?> .mc-buy-now-btn:hover { opacity: 0.82; transform: translateY(-1px); }
#<?php echo esc_attr($uid); ?> .single_add_to_cart_button:active,
#<?php echo esc_attr($uid); ?> .mc-buy-now-btn:active { transform: translateY(0); }

<?php if ( $btn_style === 'solid' ) : ?>
#<?php echo esc_attr($uid); ?> .single_add_to_cart_button { background: var(--mc-accent) !important; color: #fff !important; border-color: var(--mc-accent) !important; }
<?php else : ?>
#<?php echo esc_attr($uid); ?> .single_add_to_cart_button { background: transparent !important; color: var(--mc-accent) !important; border-color: var(--mc-accent) !important; }
<?php endif; ?>

<?php if ( $buy_now_style === 'solid' ) : ?>
#<?php echo esc_attr($uid); ?> .mc-buy-now-btn { background: var(--mc-accent); color: #fff; border-color: var(--mc-accent); }
<?php else : ?>
#<?php echo esc_attr($uid); ?> .mc-buy-now-btn { background: transparent; color: var(--mc-accent); border-color: var(--mc-accent); }
<?php endif; ?>

#<?php echo esc_attr($uid); ?> .mc-buy-now-btn.is-loading { opacity: 0.6; pointer-events: none; cursor: wait; }
</style>

    <div class="mc-fp-image-zone">
        <img
            src="<?php echo esc_url( $main_img_url ); ?>"
            alt="<?php echo esc_attr( $product->get_name() ); ?>"
            class="mc-fp-main-img"
            id="mc-main-img-<?php echo esc_attr($uid); ?>"
            loading="eager"
        />
        <?php if ( $show_gallery && ! empty( $gallery_ids ) ) : ?>
            <div class="mc-fp-gallery">
                <img
                    src="<?php echo esc_url( $main_img_url ); ?>"
                    data-full="<?php echo esc_url( $main_img_url ); ?>"
                    class="mc-fp-thumb is-active"
                    alt=""
                    loading="lazy"
                />
                <?php foreach ( $gallery_ids as $gid ) :
                    $thumb_src = wp_get_attachment_image_url( $gid, 'woocommerce_thumbnail' );
                    $full_src  = wp_get_attachment_image_url( $gid, 'large' );
                ?>
                    <img
                        src="<?php echo esc_url( $thumb_src ); ?>"
                        data-full="<?php echo esc_url( $full_src ); ?>"
                        class="mc-fp-thumb"
                        alt=""
                        loading="lazy"
                    />
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="mc-fp-content-zone">
        <?php if ( $pre_head ) : ?>
            <span class="mc-fp-pre"><?php echo esc_html( $pre_head ); ?></span>
        <?php endif; ?>

        <h2 class="mc-fp-title"><?php echo esc_html( $product->get_name() ); ?></h2>

        <?php if ( $show_rating && $product->get_rating_count() > 0 ) : ?>
            <div style="color:#f59e0b;font-size:14px;margin-bottom:20px;">
                <?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
            </div>
        <?php endif; ?>

        <?php if ( $show_price ) : ?>
            <div class="mc-fp-price"><?php echo $product->get_price_html(); ?></div>
        <?php endif; ?>

        <?php if ( $show_excerpt && $product->get_short_description() ) : ?>
            <div class="mc-fp-excerpt">
                <?php echo apply_filters( 'woocommerce_short_description', $product->get_short_description() ); ?>
            </div>
        <?php endif; ?>

        <?php
        setup_postdata( $post );
        ob_start();
        woocommerce_template_single_add_to_cart();
        $wc_form_html = ob_get_clean();
        wp_reset_postdata();

        if ( $show_buy_now ) {
            $wc_form_html = preg_replace(
                '/(<button[^>]*single_add_to_cart_button[^>]*>.*?<\/button>)/s',
                '<div class="mc-action-row">$1' .
                '<button type="button" class="mc-buy-now-btn">' .
                esc_html__( 'Buy Now', 'modern-core' ) .
                '</button></div>',
                $wc_form_html
            );
        }

        echo $wc_form_html;
        ?>

    </div>

<script>
(function () {
    var uid = <?php echo wp_json_encode( $uid ); ?>;

    function init() {
        var container = document.getElementById(uid);
        if (!container) return;

        // 1. Initial Quantity Injection
        injectQtyButtons(container);

        // 2. Global Event Delegation (Immune to WooCommerce re-renders)
        container.addEventListener('click', function(e) {
            
            // --- GALLERY CLICK ---
            var thumb = e.target.closest('.mc-fp-thumb');
            if (thumb) {
                var thumbs = container.querySelectorAll('.mc-fp-thumb');
                var mainImg = document.getElementById('mc-main-img-' + uid);
                thumbs.forEach(function (t) { t.classList.remove('is-active'); });
                thumb.classList.add('is-active');
                if (mainImg) {
                    mainImg.style.opacity = '0';
                    setTimeout(function () {
                        mainImg.src = thumb.dataset.full;
                        mainImg.style.opacity = '1';
                    }, 280);
                }
                return;
            }

            // --- QUANTITY BUTTON CLICK ---
            var qtyBtn = e.target.closest('.mc-qty-btn');
            if (qtyBtn) {
                e.preventDefault();
                var qtyInput = container.querySelector('.qty');
                if (!qtyInput) return;
                
                var v    = parseFloat(qtyInput.value) || 1;
                var min  = parseFloat(qtyInput.min) || 1;
                var max  = parseFloat(qtyInput.max) || 9999;
                var step = parseFloat(qtyInput.step) || 1;

                if (qtyBtn.classList.contains('mc-qty-plus')) {
                    if (v < max) qtyInput.value = v + step;
                } else {
                    if (v > min) qtyInput.value = v - step;
                }
                qtyInput.dispatchEvent(new Event('change', { bubbles: true }));
                return;
            }

            // --- BUY NOW CLICK ---
            var buyNowBtn = e.target.closest('.mc-buy-now-btn');
            if (buyNowBtn) {
                e.preventDefault();
                var form = buyNowBtn.closest('form');
                if (!form) form = container.querySelector('form.cart');
                if (!form) return;

                if (form.classList.contains('variations_form')) {
                    var varInput = form.querySelector('input[name="variation_id"]');
                    if (!varInput || !varInput.value || varInput.value === '0') {
                        alert(<?php echo wp_json_encode( __( 'Please select your options before purchasing.', 'modern-core' ) ); ?>);
                        return;
                    }
                }

                buyNowBtn.classList.add('is-loading');
                buyNowBtn.textContent = <?php echo wp_json_encode( __( 'Processing...', 'modern-core' ) ); ?>;

                var formData = new FormData(form);
                var wcAddBtn = form.querySelector('button[name="add-to-cart"]');
                var productIdToCart = wcAddBtn ? wcAddBtn.value : <?php echo wp_json_encode( $product_id ); ?>;
                formData.append('add-to-cart', productIdToCart);

                fetch(window.location.href, { method: 'POST', body: formData })
                    .then(function ()  { window.location.href = <?php echo wp_json_encode( $checkout_url ); ?>; })
                    .catch(function () { form.submit(); });
            }
        });

        // 3. MutationObserver to catch WooCommerce reloading the variations HTML
        var form = container.querySelector('form.cart');
        if (form) {
            var observer = new MutationObserver(function() {
                injectQtyButtons(container);
            });
            observer.observe(form, { childList: true, subtree: true });
        }
    }

    function injectQtyButtons(container) {
        var qtyWrapper = container.querySelector('.quantity');
        var qtyInput   = container.querySelector('.qty');

        if (qtyWrapper && qtyInput && !qtyWrapper.dataset.mcProcessed) {
            qtyWrapper.dataset.mcProcessed = '1';

            var minus = document.createElement('button');
            minus.type      = 'button';
            minus.className = 'mc-qty-btn mc-qty-minus';
            minus.innerHTML = '&#8722;';

            var plus = document.createElement('button');
            plus.type      = 'button';
            plus.className = 'mc-qty-btn mc-qty-plus';
            plus.innerHTML = '&#43;';

            qtyWrapper.insertBefore(minus, qtyInput);
            qtyWrapper.appendChild(plus);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
</div>