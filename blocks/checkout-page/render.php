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
$wrapper_attributes = get_block_wrapper_attributes([
    'class' => "mc-block width-" . esc_attr($width_mode) . " modern-core-checkout {$unique_id} layout-{$layout} label-{$label_style}"
]);

// Empty cart or the order-pay / order-received endpoints: let WooCommerce output its own page.
if ( ! WC()->cart || WC()->cart->is_empty() || is_wc_endpoint_url( 'order-pay' ) || is_wc_endpoint_url( 'order-received' ) ) {
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

            width: 100%;
            color: var(--chk-txt);
            padding: 20px 0 80px 0;
            box-sizing: border-box;
        }

        /* Width Modes */
        .<?php echo esc_attr($unique_id); ?>.width-contained .woocommerce {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0;
        }
        .<?php echo esc_attr($unique_id); ?>.width-wide .woocommerce {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0;
        }
        .<?php echo esc_attr($unique_id); ?>.width-fullwidth .woocommerce {
            max-width: 100%;
            padding: 0 24px;
        }

        /* Trust Strip */
        .<?php echo esc_attr($unique_id); ?> .mc-chk-trust {
            background: #f8fafc;
            padding: 15px;
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border-bottom: 1px solid var(--chk-border);
            margin-bottom: 40px;
            width: 100%;
        }

        /* ===== DESKTOP: Flexbox on form.checkout ===== */
        .<?php echo esc_attr($unique_id); ?> form.checkout.woocommerce-checkout {
            display: flex;
            flex-wrap: nowrap; /* Prevent wrapping on desktop */
            gap: 60px;
            align-items: flex-start;
            width: 100%;
            margin: 0;
            padding: 0;
            border: none;
        }

        /* Customer details - left column */
        .<?php echo esc_attr($unique_id); ?> #customer_details {
            flex: 1 1 55%;
            min-width: 0;
            margin: 0;
            width: auto;
            float: none !important;
        }

        /* Reset WooCommerce .col-1 and .col-2 */
        .<?php echo esc_attr($unique_id); ?> #customer_details .col-1,
        .<?php echo esc_attr($unique_id); ?> #customer_details .col-2 {
            width: 100% !important;
            float: none !important;
            margin: 0 !important;
            padding: 0 !important;
            clear: both !important;
        }

        .<?php echo esc_attr($unique_id); ?> #customer_details .col-2 {
            margin-top: 30px !important;
        }

        /* Order review - right column */
        .<?php echo esc_attr($unique_id); ?> #order_review_heading {
            display: none;
        }

        .<?php echo esc_attr($unique_id); ?> #order_review {
            flex: 0 0 380px; /* Fixed width on desktop */
            margin: 0;
            width: auto;
            background: var(--chk-bg);
            padding: 40px;
            border-radius: var(--chk-rad);
            border: 1px solid var(--chk-border);
            position: sticky;
            top: 30px;
            float: none !important;
            box-sizing: border-box;
        }

        /* Forms & Inputs */
        .<?php echo esc_attr($unique_id); ?> .woocommerce-billing-fields h3,
        .<?php echo esc_attr($unique_id); ?> .woocommerce-shipping-fields h3,
        .<?php echo esc_attr($unique_id); ?> .woocommerce-additional-fields h3 {
            font-size: 22px;
            font-weight: 800;
            margin: 0 0 20px 0;
            border-bottom: none;
        }

        .<?php echo esc_attr($unique_id); ?> .form-row {
            margin: 0 0 20px 0 !important;
            padding: 0 !important;
            float: none !important;
            width: 100% !important;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        /* Two-column fields using CSS Grid */
        .<?php echo esc_attr($unique_id); ?> .woocommerce-billing-fields__field-wrapper,
        .<?php echo esc_attr($unique_id); ?> .woocommerce-shipping-fields__field-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0 20px;
        }

        /* Full-width fields */
        .<?php echo esc_attr($unique_id); ?> .form-row-wide,
        .<?php echo esc_attr($unique_id); ?> #billing_address_1_field,
        .<?php echo esc_attr($unique_id); ?> #billing_address_2_field,
        .<?php echo esc_attr($unique_id); ?> #shipping_address_1_field,
        .<?php echo esc_attr($unique_id); ?> #shipping_address_2_field,
        .<?php echo esc_attr($unique_id); ?> #billing_company_field,
        .<?php echo esc_attr($unique_id); ?> #shipping_company_field,
        .<?php echo esc_attr($unique_id); ?> #billing_country_field,
        .<?php echo esc_attr($unique_id); ?> #shipping_country_field,
        .<?php echo esc_attr($unique_id); ?> #order_comments_field {
            grid-column: 1 / -1;
        }

        .<?php echo esc_attr($unique_id); ?> .form-row-first,
        .<?php echo esc_attr($unique_id); ?> .form-row-last {
            width: 100% !important;
            float: none !important;
            margin-right: 0 !important;
            margin-left: 0 !important;
            clear: none !important;
        }

        /* Label Styles */
        .<?php echo esc_attr($unique_id); ?>.label-floating .form-row label {
            display: none;
        }
        .<?php echo esc_attr($unique_id); ?>.label-above .form-row label {
            font-size: 14px;
            font-weight: 600;
            color: var(--chk-txt);
            margin: 0 !important;
            padding: 0 !important;
            line-height: 1.4;
        }

        /* Inputs */
        .<?php echo esc_attr($unique_id); ?> input.input-text,
        .<?php echo esc_attr($unique_id); ?> select,
        .<?php echo esc_attr($unique_id); ?> textarea {
            padding: 14px 15px !important;
            border-radius: var(--chk-inprad) !important;
            border: 1px solid var(--chk-border) !important;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            background: #fff !important;
            font-size: 15px !important;
            outline: none !important;
            transition: border-color 0.2s, box-shadow 0.2s !important;
            color: var(--chk-txt) !important;
            height: 52px !important;
            line-height: 1.4 !important;
            margin: 0 !important;
            min-height: 52px !important;
        }

        .<?php echo esc_attr($unique_id); ?> input.input-text:focus,
        .<?php echo esc_attr($unique_id); ?> select:focus,
        .<?php echo esc_attr($unique_id); ?> textarea:focus {
            border-color: var(--chk-acc) !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
        }

        /* Select2 Dropdown Override */
        .<?php echo esc_attr($unique_id); ?> .select2-container {
            width: 100% !important;
        }

        .<?php echo esc_attr($unique_id); ?> .select2-container .select2-selection--single {
            height: 52px !important;
            border: 1px solid var(--chk-border) !important;
            border-radius: var(--chk-inprad) !important;
            background: #fff !important;
            display: flex !important;
            align-items: center !important;
        }

        .<?php echo esc_attr($unique_id); ?> .select2-container .select2-selection--single .select2-selection__rendered {
            padding: 0 15px !important;
            line-height: 52px !important;
            color: var(--chk-txt) !important;
            font-size: 15px !important;
            width: 100% !important;
        }

        .<?php echo esc_attr($unique_id); ?> .select2-container .select2-selection--single .select2-selection__arrow {
            height: 52px !important;
            right: 10px !important;
        }

        .<?php echo esc_attr($unique_id); ?> .select2-container .select2-selection--single .select2-selection__arrow b {
            border-color: #64748b transparent transparent transparent !important;
        }

        .<?php echo esc_attr($unique_id); ?> .select2-dropdown {
            border: 1px solid var(--chk-border) !important;
            border-radius: var(--chk-inprad) !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
        }

        .<?php echo esc_attr($unique_id); ?> .select2-results__option {
            padding: 12px 15px !important;
            font-size: 15px !important;
        }

        .<?php echo esc_attr($unique_id); ?> .select2-results__option--highlighted {
            background: var(--chk-acc) !important;
            color: #fff !important;
        }

        /* Order Summary */
        .<?php echo esc_attr($unique_id); ?> table.shop_table {
            border: none;
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }
        .<?php echo esc_attr($unique_id); ?> table.shop_table thead {
            display: none;
        }

        .<?php echo esc_attr($unique_id); ?> table.shop_table td.product-name {
            border-bottom: 1px solid var(--chk-border);
            padding: 20px 0;
            font-size: 15px;
            font-weight: bold;
        }
        .<?php echo esc_attr($unique_id); ?> table.shop_table td.product-name strong.product-quantity {
            opacity: 0.6;
            font-weight: normal;
            margin-left: 8px;
        }
        .<?php echo esc_attr($unique_id); ?> table.shop_table td.product-total {
            border-bottom: 1px solid var(--chk-border);
            padding: 20px 0;
            font-weight: bold;
            text-align: right;
        }

        .<?php echo esc_attr($unique_id); ?> table.shop_table tfoot th,
        .<?php echo esc_attr($unique_id); ?> table.shop_table tfoot td {
            border-bottom: 1px solid var(--chk-border);
            padding: 15px 0;
        }
        .<?php echo esc_attr($unique_id); ?> table.shop_table tfoot th {
            font-weight: normal;
            opacity: 0.8;
            text-align: left;
        }
        .<?php echo esc_attr($unique_id); ?> table.shop_table tfoot td {
            text-align: right;
            font-weight: bold;
        }

        .<?php echo esc_attr($unique_id); ?> table.shop_table tfoot tr.order-total th {
            font-size: 20px;
            font-weight: bold;
            opacity: 1;
            border-bottom: none;
            padding-top: 25px;
        }
        .<?php echo esc_attr($unique_id); ?> table.shop_table tfoot tr.order-total td {
            font-size: 24px;
            border-bottom: none;
            padding-top: 25px;
        }

        /* Payment Section */
        .<?php echo esc_attr($unique_id); ?> #payment {
            background: transparent;
            border-radius: 0;
            padding: 0;
        }
        .<?php echo esc_attr($unique_id); ?> #payment ul.payment_methods {
            border-bottom: none;
            padding: 0;
            margin-top: 30px;
        }
        .<?php echo esc_attr($unique_id); ?> #payment ul.payment_methods li {
            background: #fff;
            border: 1px solid var(--chk-border);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            list-style: none;
        }
        .<?php echo esc_attr($unique_id); ?> #payment ul.payment_methods li input[type=radio] {
            margin-right: 15px;
        }
        .<?php echo esc_attr($unique_id); ?> #payment ul.payment_methods li label {
            font-weight: bold;
            display: inline;
            margin: 0;
        }
        .<?php echo esc_attr($unique_id); ?> #payment div.payment_box {
            background: rgba(0,0,0,0.03);
            border-radius: 4px;
            padding: 15px;
            margin-top: 15px;
            font-size: 14px;
            opacity: 0.8;
        }
        .<?php echo esc_attr($unique_id); ?> #payment div.payment_box::before {
            display: none;
        }

        .<?php echo esc_attr($unique_id); ?> .place-order {
            padding: 30px 0 0 0;
            background: transparent;
            float: none !important;
        }
        .<?php echo esc_attr($unique_id); ?> #place_order {
            width: 100%;
            padding: 20px;
            background: var(--chk-acc);
            color: #fff;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 18px;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.2s;
            height: auto;
            line-height: 1.5;
        }
        .<?php echo esc_attr($unique_id); ?> #place_order:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        /* Multi-Step Navigation */
        .<?php echo esc_attr($unique_id); ?> .mc-chk-nav {
            display: none;
            width: 100%;
        }
        .<?php echo esc_attr($unique_id); ?>.layout-multi .mc-chk-nav {
            display: flex;
            gap: 30px;
            border-bottom: 2px solid rgba(0,0,0,0.05);
            padding-bottom: 15px;
            margin-bottom: 30px;
            width: 100%;
        }
        .<?php echo esc_attr($unique_id); ?>.layout-multi .mc-chk-nav-item {
            font-weight: bold;
            color: rgba(0,0,0,0.3);
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: color 0.2s;
            padding-bottom: 15px;
            margin-bottom: -17px;
            border-bottom: 2px solid transparent;
        }
        .<?php echo esc_attr($unique_id); ?>.layout-multi .mc-chk-nav-item.is-active {
            color: var(--chk-acc);
            border-bottom-color: var(--chk-acc);
        }
        .<?php echo esc_attr($unique_id); ?>.layout-multi .mc-chk-nav-num {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: currentColor;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            flex-shrink: 0;
        }

        .<?php echo esc_attr($unique_id); ?>.layout-multi .mc-step {
            display: none;
            animation-duration: 0.4s;
            animation-fill-mode: forwards;
        }
        .<?php echo esc_attr($unique_id); ?>.layout-multi .mc-step.is-active {
            display: block;
        }

        @keyframes mcChkSlide {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes mcChkFade {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .<?php echo esc_attr($unique_id); ?>.layout-multi.anim-slide .mc-step.is-active {
            animation-name: mcChkSlide;
        }
        .<?php echo esc_attr($unique_id); ?>.layout-multi.anim-fade .mc-step.is-active {
            animation-name: mcChkFade;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-step-btn {
            margin-top: 30px;
            padding: 15px 30px;
            background: var(--chk-acc);
            color: #fff;
            border: none;
            border-radius: var(--chk-inprad);
            font-weight: bold;
            cursor: pointer;
            float: right;
        }

        /* ===== MOBILE RESPONSIVE ===== */
        @media (max-width: 900px) {
            /* Reset flex to block for proper stacking */
            .<?php echo esc_attr($unique_id); ?> form.checkout.woocommerce-checkout {
                display: block !important;
                width: 100% !important;
            }

            .<?php echo esc_attr($unique_id); ?> #customer_details {
                width: 100% !important;
                margin-bottom: 40px;
            }

            .<?php echo esc_attr($unique_id); ?> #order_review {
                width: 100% !important;
                position: static !important;
                padding: 25px !important;
                flex: none !important; /* Remove flex constraints */
            }

            .<?php echo esc_attr($unique_id); ?> .woocommerce-billing-fields__field-wrapper,
            .<?php echo esc_attr($unique_id); ?> .woocommerce-shipping-fields__field-wrapper {
                grid-template-columns: 1fr !important;
            }

            .<?php echo esc_attr($unique_id); ?> .form-row-wide,
            .<?php echo esc_attr($unique_id); ?> #billing_address_1_field,
            .<?php echo esc_attr($unique_id); ?> #billing_address_2_field,
            .<?php echo esc_attr($unique_id); ?> #shipping_address_1_field,
            .<?php echo esc_attr($unique_id); ?> #shipping_address_2_field,
            .<?php echo esc_attr($unique_id); ?> #billing_company_field,
            .<?php echo esc_attr($unique_id); ?> #shipping_company_field,
            .<?php echo esc_attr($unique_id); ?> #billing_country_field,
            .<?php echo esc_attr($unique_id); ?> #shipping_country_field,
            .<?php echo esc_attr($unique_id); ?> #order_comments_field {
                grid-column: 1 !important;
            }

            .<?php echo esc_attr($unique_id); ?> #place_order {
                font-size: 16px !important;
                padding: 18px !important;
            }
        }
            /* select2 keeps the real <select> in the DOM, visually hidden; stop it widening the page. */
        .<?php echo esc_attr($unique_id); ?> select.select2-hidden-accessible { width: 1px !important; min-width: 0 !important; padding: 0 !important; }
    </style>

    <?php if ($show_trust) : ?>
        <div class="mc-chk-trust">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            <?php echo esc_html($trust_text); ?>
        </div>
    <?php endif; ?>

    <?php echo do_shortcode('[woocommerce_checkout]'); ?>

    <?php if ($layout === 'multi') : ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const wrapper = document.querySelector('.<?php echo esc_attr($unique_id); ?>');
            if (!wrapper) return;

            const initMultiStep = () => {
                const form = wrapper.querySelector('form.checkout.woocommerce-checkout');
                const customerDetails = wrapper.querySelector('#customer_details');
                const orderReview = wrapper.querySelector('#order_review');

                if (!form || !customerDetails || !orderReview) return;
                if (wrapper.dataset.mcMultiStepInit === 'true') return;
                wrapper.dataset.mcMultiStepInit = 'true';

                const nav = document.createElement('div');
                nav.className = 'mc-chk-nav';
                nav.innerHTML = `
                    <div class="mc-chk-nav-item is-active" data-step="1">
                        <div class="mc-chk-nav-num">1</div>
                        <span class="mc-chk-nav-label">Details</span>
                    </div>
                    <div class="mc-chk-nav-item" data-step="2">
                        <div class="mc-chk-nav-num">2</div>
                        <span class="mc-chk-nav-label">Payment</span>
                    </div>
                `;

                form.parentNode.insertBefore(nav, form);

                const col1 = customerDetails.querySelector('.col-1');
                const col2 = customerDetails.querySelector('.col-2');

                const step1 = document.createElement('div');
                step1.className = 'mc-step is-active';
                step1.dataset.step = '1';
                if (col1) step1.appendChild(col1);
                if (col2) step1.appendChild(col2);

                const nextBtn = document.createElement('button');
                nextBtn.className = 'mc-step-btn';
                nextBtn.type = 'button';
                nextBtn.innerText = 'Continue to Payment';
                step1.appendChild(nextBtn);

                customerDetails.appendChild(step1);

                const paymentBox = orderReview.querySelector('#payment');
                const step2 = document.createElement('div');
                step2.className = 'mc-step';
                step2.dataset.step = '2';

                if (paymentBox) {
                    step2.appendChild(paymentBox);
                    orderReview.appendChild(step2);
                }

                const navItems = nav.querySelectorAll('.mc-chk-nav-item');
                const steps = wrapper.querySelectorAll('.mc-step');

                const goToStep = (stepNum) => {
                    navItems.forEach(i => i.classList.toggle('is-active', i.dataset.step === stepNum));
                    steps.forEach(s => s.classList.toggle('is-active', s.dataset.step === stepNum));
                };

                nextBtn.addEventListener('click', () => goToStep('2'));

                navItems.forEach(item => {
                    item.addEventListener('click', () => goToStep(item.dataset.step));
                });
            };

            initMultiStep();

            const observer = new MutationObserver(() => initMultiStep());
            observer.observe(wrapper, { childList: true, subtree: true });
            setTimeout(() => observer.disconnect(), 5000);
        });
    </script>
    <?php endif; ?>
</div>