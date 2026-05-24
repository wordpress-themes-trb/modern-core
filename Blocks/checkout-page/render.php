<?php
$width_mode = $attributes['widthMode'] ?? 'contained';
if (!class_exists('WooCommerce')) return;

$layout = $attributes['layoutMode'] ?? 'single';
$prog_style = $attributes['progressStyle'] ?? 'numbered';
$anim = $attributes['animStyle'] ?? 'slide';
$label_style = $attributes['labelStyle'] ?? 'above';
$show_trust = $attributes['showTrustStrip'] ?? true;
$trust_text = $attributes['trustText'] ?? 'SSL Encrypted Secure Checkout';
$sum_bg = $attributes['summaryBg'] ?? '#f8fafc';
$sum_rad = $attributes['summaryRadius'] ?? 12;
$inp_rad = $attributes['inputRadius'] ?? 8;
$accent = $attributes['accentColor'] ?? '#3b82f6';
$text_c = $attributes['textColor'] ?? '#0f172a';
$border_c = $attributes['borderColor'] ?? '#e2e8f0';

$unique_id = uniqid('mc-chk-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "mc-block width-" . esc_attr($width_mode) . " modern-core-checkout {$unique_id} layout-{$layout} label-{$label_style}"]);

// FIX: Verify cart exists before checking if empty (prevents backend crash)
if (!empty(WC()->cart) && WC()->cart->is_empty()) {
    echo '<div ' . $wrapper_attributes . '>';
    echo do_shortcode('[woocommerce_checkout]');
    echo '</div>';
    return;
}
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            --chk-bg: <?php echo esc_attr($sum_bg); ?>;
            --chk-acc: <?php echo esc_attr($accent); ?>;
            --chk-txt: <?php echo esc_attr($text_c); ?>;
            --chk-border: <?php echo esc_attr($border_c); ?>;
            --chk-rad: <?php echo esc_attr($sum_rad); ?>px;
            --chk-inprad: <?php echo esc_attr($inp_rad); ?>px;
            
            width: 100%; color: var(--chk-txt); padding-bottom: 80px;
        }

        /* --- Trust Strip --- */
        .<?php echo esc_attr($unique_id); ?> .mc-chk-trust {
            background: #f8fafc; padding: 15px; text-align: center; font-size: 13px; font-weight: bold; color: #64748b;
            display: flex; align-items: center; justify-content: center; gap: 8px; border-bottom: 1px solid var(--chk-border); margin-bottom: 40px;
        }

        /* --- Layout Grid --- */
        .<?php echo esc_attr($unique_id); ?> .woocommerce {
            display: grid; gap: 60px; max-width: 1300px; margin: 0 auto; padding: 0 5%; align-items: start;
        }
        .<?php echo esc_attr($unique_id); ?> .woocommerce { grid-template-columns: 1.2fr 1fr; }
        
        @media (max-width: 900px) {
            .<?php echo esc_attr($unique_id); ?> .woocommerce { grid-template-columns: 1fr; gap: 40px; }
            .<?php echo esc_attr($unique_id); ?> #customer_details { order: 2; } /* Put form under summary on mobile if desired, or leave standard */
        }

        /* --- WooCommerce Specific Structural Hacks --- */
        /* Split the form into our Grid */
        .<?php echo esc_attr($unique_id); ?> form.checkout { display: contents; } /* Break the form so children act as grid items */
        .<?php echo esc_attr($unique_id); ?> #customer_details { grid-column: 1; margin: 0; width: 100%; display: flex; flex-direction: column; gap: 30px; }
        .<?php echo esc_attr($unique_id); ?> #order_review_heading, 
        .<?php echo esc_attr($unique_id); ?> #order_review { grid-column: 2; margin: 0; width: 100%; }

        @media (max-width: 900px) {
            .<?php echo esc_attr($unique_id); ?> #customer_details { grid-column: 1; }
            .<?php echo esc_attr($unique_id); ?> #order_review_heading, 
            .<?php echo esc_attr($unique_id); ?> #order_review { grid-column: 1; }
        }

        /* --- Forms & Inputs --- */
        .<?php echo esc_attr($unique_id); ?> .woocommerce-billing-fields h3,
        .<?php echo esc_attr($unique_id); ?> .woocommerce-shipping-fields h3,
        .<?php echo esc_attr($unique_id); ?> .woocommerce-additional-fields h3 { font-size: 22px; font-weight: 800; margin: 0 0 20px 0; border-bottom: none; }
        
        .<?php echo esc_attr($unique_id); ?> .form-row { margin-bottom: 20px; padding: 0; float: none; display: flex; flex-direction: column; gap: 8px; }
        .<?php echo esc_attr($unique_id); ?> .form-row-first { width: calc(50% - 10px); float: left; margin-right: 20px; }
        .<?php echo esc_attr($unique_id); ?> .form-row-last { width: calc(50% - 10px); float: right; }
        
        /* Floating Label Logic */
        .<?php echo esc_attr($unique_id); ?>.label-floating .form-row label { display: none; } /* Simplified: relies on placeholder */
        .<?php echo esc_attr($unique_id); ?>.label-above .form-row label { font-size: 14px; font-weight: bold; }

        .<?php echo esc_attr($unique_id); ?> input.input-text,
        .<?php echo esc_attr($unique_id); ?> select,
        .<?php echo esc_attr($unique_id); ?> textarea {
            padding: 15px; border-radius: var(--chk-inprad); border: 1px solid var(--chk-border); width: 100%; box-sizing: border-box; background: transparent; font-size: 16px; outline: none; transition: border-color 0.2s;
        }
        .<?php echo esc_attr($unique_id); ?> input.input-text:focus,
        .<?php echo esc_attr($unique_id); ?> select:focus,
        .<?php echo esc_attr($unique_id); ?> textarea:focus { border-color: var(--chk-acc); }

        /* --- Order Summary Overrides --- */
        .<?php echo esc_attr($unique_id); ?> #order_review_heading { display: none; } /* We build this into the box */
        .<?php echo esc_attr($unique_id); ?> #order_review {
            background: var(--chk-bg); padding: 40px; border-radius: var(--chk-rad); border: 1px solid var(--chk-border); position: sticky; top: 30px;
        }
        
        .<?php echo esc_attr($unique_id); ?> table.shop_table { border: none; width: 100%; border-collapse: collapse; margin: 0; }
        .<?php echo esc_attr($unique_id); ?> table.shop_table thead { display: none; } /* Hide default table headers */
        
        /* The Items */
        .<?php echo esc_attr($unique_id); ?> table.shop_table td.product-name { border-bottom: 1px solid var(--chk-border); padding: 20px 0; font-size: 15px; font-weight: bold; }
        .<?php echo esc_attr($unique_id); ?> table.shop_table td.product-name strong.product-quantity { opacity: 0.6; font-weight: normal; margin-left: 8px; }
        .<?php echo esc_attr($unique_id); ?> table.shop_table td.product-total { border-bottom: 1px solid var(--chk-border); padding: 20px 0; font-weight: bold; text-align: right; }

        /* The Totals */
        .<?php echo esc_attr($unique_id); ?> table.shop_table tfoot th,
        .<?php echo esc_attr($unique_id); ?> table.shop_table tfoot td { border-bottom: 1px solid var(--chk-border); padding: 15px 0; }
        .<?php echo esc_attr($unique_id); ?> table.shop_table tfoot th { font-weight: normal; opacity: 0.8; text-align: left; }
        .<?php echo esc_attr($unique_id); ?> table.shop_table tfoot td { text-align: right; font-weight: bold; }
        
        .<?php echo esc_attr($unique_id); ?> table.shop_table tfoot tr.order-total th { font-size: 20px; font-weight: bold; opacity: 1; border-bottom: none; padding-top: 25px; }
        .<?php echo esc_attr($unique_id); ?> table.shop_table tfoot tr.order-total td { font-size: 24px; border-bottom: none; padding-top: 25px; }

        /* --- Payment Section Overrides --- */
        .<?php echo esc_attr($unique_id); ?> #payment { background: transparent; border-radius: 0; padding: 0; }
        .<?php echo esc_attr($unique_id); ?> #payment ul.payment_methods { border-bottom: none; padding: 0; margin-top: 30px; }
        .<?php echo esc_attr($unique_id); ?> #payment ul.payment_methods li { background: #fff; border: 1px solid var(--chk-border); border-radius: 8px; padding: 15px; margin-bottom: 10px; }
        .<?php echo esc_attr($unique_id); ?> #payment ul.payment_methods li input[type=radio] { margin-right: 15px; }
        .<?php echo esc_attr($unique_id); ?> #payment ul.payment_methods li label { font-weight: bold; }
        .<?php echo esc_attr($unique_id); ?> #payment div.payment_box { background: rgba(0,0,0,0.03); border-radius: 4px; padding: 15px; margin-top: 15px; font-size: 14px; opacity: 0.8; }
        .<?php echo esc_attr($unique_id); ?> #payment div.payment_box::before { display: none; }

        .<?php echo esc_attr($unique_id); ?> .place-order { padding: 30px 0 0 0; background: transparent; }
        .<?php echo esc_attr($unique_id); ?> #place_order { width: 100%; padding: 20px; background: var(--chk-acc); color: #fff; border: none; border-radius: 6px; font-weight: bold; font-size: 18px; cursor: pointer; transition: opacity 0.2s; }
        .<?php echo esc_attr($unique_id); ?> #place_order:hover { opacity: 0.9; }

        /* --- Multi-Step JS Hidden Classes --- */
        .<?php echo esc_attr($unique_id); ?> .mc-chk-nav { display: none; }
        .<?php echo esc_attr($unique_id); ?>.layout-multi .mc-chk-nav { display: flex; gap: 30px; border-bottom: 2px solid rgba(0,0,0,0.05); padding-bottom: 15px; margin-bottom: 30px; }
        .<?php echo esc_attr($unique_id); ?>.layout-multi .mc-chk-nav-item { font-weight: bold; color: rgba(0,0,0,0.3); display: flex; align-items: center; gap: 10px; cursor: pointer; transition: color 0.2s; }
        .<?php echo esc_attr($unique_id); ?>.layout-multi .mc-chk-nav-item.is-active { color: var(--chk-acc); border-bottom: 2px solid var(--chk-acc); margin-bottom: -17px; }
        .<?php echo esc_attr($unique_id); ?>.layout-multi .mc-chk-nav-num { width: 24px; height: 24px; border-radius: 50%; background: currentColor; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 12px; }

        .<?php echo esc_attr($unique_id); ?>.layout-multi .mc-step { display: none; animation-duration: 0.4s; animation-fill-mode: forwards; }
        .<?php echo esc_attr($unique_id); ?>.layout-multi .mc-step.is-active { display: block; }
        
        /* Animations */
        .<?php echo esc_attr($unique_id); ?>.layout-multi.anim-slide .mc-step.is-active { animation-name: mcChkSlide; }
        .<?php echo esc_attr($unique_id); ?>.layout-multi.anim-fade .mc-step.is-active { animation-name: mcChkFade; }
        @keyframes mcChkSlide { from { opacity: 0; transform: translateX(30px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes mcChkFade { from { opacity: 0; } to { opacity: 1; } }

        .<?php echo esc_attr($unique_id); ?> .mc-step-btn { margin-top: 30px; padding: 15px 30px; background: var(--chk-acc); color: #fff; border: none; border-radius: var(--chk-inprad); font-weight: bold; cursor: pointer; float: right; }
    </style>

    <?php if ($show_trust) : ?>
        <div class="mc-chk-trust">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            <?php echo esc_html($trust_text); ?>
        </div>
    <?php endif; ?>

    <?php 
    // Execute Native WooCommerce Checkout Shortcode
    echo do_shortcode('[woocommerce_checkout]'); 
    ?>

    <?php if ($layout === 'multi') : ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const wrapper = document.querySelector('.<?php echo esc_attr($unique_id); ?>');
            if (!wrapper) return;

            // Wait for WooCommerce to render the form structure
            setTimeout(() => {
                const customerDetails = wrapper.querySelector('#customer_details');
                const orderReview = wrapper.querySelector('#order_review');
                if (!customerDetails || !orderReview) return;

                // 1. Build Navigation Bar
                const nav = document.createElement('div');
                nav.className = 'mc-chk-nav';
                nav.innerHTML = `
                    <div class="mc-chk-nav-item is-active" data-step="1"><div class="mc-chk-nav-num">1</div> Details</div>
                    <div class="mc-chk-nav-item" data-step="2"><div class="mc-chk-nav-num">2</div> Payment</div>
                `;
                customerDetails.parentNode.insertBefore(nav, customerDetails);

                // 2. Wrap Sections
                const col1 = customerDetails.querySelector('.col-1');
                const col2 = customerDetails.querySelector('.col-2');
                
                const step1 = document.createElement('div');
                step1.className = 'mc-step is-active';
                if(col1) step1.appendChild(col1);
                if(col2) step1.appendChild(col2);
                
                // Add "Continue to Payment" button to Step 1
                const nextBtn = document.createElement('button');
                nextBtn.className = 'mc-step-btn';
                nextBtn.type = 'button';
                nextBtn.innerText = 'Continue to Payment';
                step1.appendChild(nextBtn);

                customerDetails.appendChild(step1);

                // For Step 2, we actually hide the Payment box in the Order Summary until Step 2 is active
                const paymentBox = orderReview.querySelector('#payment');
                if(paymentBox) paymentBox.style.display = 'none';

                // 3. Navigation Logic
                const navItems = nav.querySelectorAll('.mc-chk-nav-item');
                
                const goToStep = (step) => {
                    navItems.forEach(i => i.classList.toggle('is-active', i.dataset.step === step));
                    
                    if (step === '1') {
                        step1.classList.add('is-active');
                        if(paymentBox) paymentBox.style.display = 'none';
                    } else if (step === '2') {
                        step1.classList.remove('is-active');
                        if(paymentBox) paymentBox.style.display = 'block';
                    }
                };

                nextBtn.addEventListener('click', () => goToStep('2'));
                
                navItems.forEach(item => {
                    item.addEventListener('click', () => goToStep(item.dataset.step));
                });

            }, 500); // 500ms delay ensures WC AJAX is finished loading the fragment
        });
    </script>
    <?php endif; ?>
</div>