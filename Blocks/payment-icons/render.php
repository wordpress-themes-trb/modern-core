<?php
$show_label = $attributes['showLabel'] ?? true;
$label_text = $attributes['labelText'] ?? 'Secure payment options';
$style      = $attributes['badgeStyle'] ?? 'color';
$height     = $attributes['iconHeight'] ?? 32;
$gap        = $attributes['gap'] ?? 16;
$align      = $attributes['alignment'] ?? 'center';
$active     = $attributes['activeIcons'] ?? [];
$label_c    = $attributes['labelColor'] ?? '#64748b';

if (empty($active)) return;

$unique_id = uniqid('mc-picons-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "modern-core-payment-icons {$unique_id} style-{$style} align-{$align}"]);

// Unified internal vector storage mapping keys to responsive inline inline strings
// Unified internal vector storage mapping keys to responsive inline inline strings
if (!function_exists('mc_get_payment_svg_vector')) {
    function mc_get_payment_svg_vector($id) {
        switch ($id) {
            case 'visa':
                return '<svg viewBox="0 0 36 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="36" height="24" rx="3" fill="var(--pi-bg, #1A1F71)"/><path d="M13.2 16.5h1.9l1.2-7.3h-1.9l-1.2 7.3zm7.6-7.1c-.4-.2-.9-.3-1.5-.3-1.6 0-2.7.8-2.7 2 0 .9.8 1.4 1.4 1.7.6.3.8.5.8.8 0 .4-.5.6-1 .6-.7 0-1.1-.1-1.7-.4l-.2-.1-.3 1.7c.5.2 1.3.4 2.1.4 1.7 0 2.8-.8 2.8-2.1 0-.7-.4-1.2-1.3-1.6-.5-.3-.9-.5-.9-.8 0-.3.3-.6.9-.6.5 0 .9.1 1.2.2l.2.1.3-1.6zm3.3.1h-1.5c-.5 0-.8.3-.9.7l-2.7 6.5h2l.4-1.1h2.4l.2 1.1h1.8l-1.7-7.2zm-2 3.7l.8-2.3.5 2.3h-1.3zm-13.6-3.7l-1.8 4.9-.2-.9c-.3-1.1-.9-2.2-1.9-2.8l1.7 6h2.1l3.1-7.2H8.5z" fill="var(--pi-fg, #FFF)"/></svg>';
            case 'mastercard':
                return '<svg viewBox="0 0 36 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="36" height="24" rx="3" fill="var(--pi-bg, #222)"/><circle cx="14.5" cy="12" r="7" fill="var(--pi-m1, #EB001B)"/><circle cx="21.5" cy="12" r="7" fill="var(--pi-m2, #F79E1B)" fill-opacity="0.8"/></svg>';
            case 'paypal':
                return '<svg viewBox="0 0 36 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="36" height="24" rx="3" fill="var(--pi-bg, #003087)"/><path d="M12.5 17.5l1.8-8.5h3.5c1.8 0 2.8.8 2.5 2.5-.3 1.8-1.5 2.5-3.2 2.5h-1.8l-.8 3.5h-2zm3-1.5l.8-4h1c1 0 1.5.3 1.3 1.2-.2 1-.8 1.3-1.7 1.3h-0.6l-.3 1.5h-0.5z" fill="var(--pi-fg, #0079C1)"/></svg>';
            case 'applepay':
                return '<svg viewBox="0 0 36 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="36" height="24" rx="3" fill="var(--pi-bg, #000)"/><path d="M14.5 15.2c-.8 0-1.5-.6-1.5-1.5 0-.9.7-1.5 1.5-1.5s1.5.6 1.5 1.5c0 .9-.7 1.5-1.5 1.5zm3.5-5.2c0-.7.4-1.2.9-1.5-.5-.7-1.3-1-2.2-1-1.8 0-2.8 1.1-2.8 2.7 0 1.8 1.2 3.6 2.8 3.6.8 0 1.5-.4 2-.8-.6-.1-1.1-.5-1.3-1.1h1.5c0-.6.4-1.1.9-1.4-.4-.2-.8-.5-.8-.5z" fill="var(--pi-fg, #FFF)"/></svg>';
            case 'googlepay':
                return '<svg viewBox="0 0 36 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="36" height="24" rx="3" fill="var(--pi-bg, #FFF)" stroke="#E2E8F0" stroke-width="1"/><path d="M14.2 12.5v-1.8h4.5c.1.3.1.6.1 1 0 1.2-.4 2.2-1.1 2.9-.7.6-1.8.9-3 .9-2.1 0-3.8-1.7-3.8-3.8s1.7-3.8 3.8-3.8c1 0 1.9.4 2.6 1l-1.1 1.1c-.4-.4-.9-.6-1.5-.6-1.3 0-2.4 1.1-2.4 2.4s1.1 2.4 2.4 2.4c.8 0 1.4-.4 1.7-.9h-1.7z" fill="var(--pi-fg, #4285F4)"/></svg>';
            case 'klarna':
                return '<svg viewBox="0 0 36 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="36" height="24" rx="3" fill="var(--pi-bg, #FFB3C7)"/><path d="M11 15h1.5V9H11v6zm5.5-3.5L18 9h-1.6l-1.2 2.2L14 9h-1.6l1.5 2.5L12.2 15h1.6l1.4-2.5 1.4 2.5h1.6L16.5 11.5zM20.5 14a.8.8 0 1 0 0 1.6.8.8 0 0 0 0-1.6z" fill="var(--pi-fg, #000)"/></svg>';
            case 'amex':
                return '<svg viewBox="0 0 36 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="36" height="24" rx="3" fill="var(--pi-bg, #016FD0)"/><path d="M10 15h1.5l.3-1.2H13l.3 1.2h1.5L13.2 9h-1.5L10 15zm2-2.5l.5-1.8.5 1.8h-1zm4.5-3.5h-1.8v6h1.8v-1.8h1.2l.8 1.8h1.8l-1.1-2.3c.7-.3 1.1-.9 1.1-1.7 0-1.2-.9-2-2-2zm0 1.5h1c.4 0 .6.2.6.5 0 .4-.2.5-.6.5h-1v-1z" fill="var(--pi-fg, #FFF)"/></svg>';
            case 'stripe':
                return '<svg viewBox="0 0 36 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="36" height="24" rx="3" fill="var(--pi-bg, #635BFF)"/><path d="M15.4 11.8c0-.7.5-1.1 1.4-1.1.9 0 1.6.3 2.2.7l.6-1.5c-.7-.4-1.7-.7-2.7-.7-2.1 0-3.4 1.1-3.4 3 0 2.4 3.1 2.1 3.1 3.3 0 .8-.6 1.1-1.5 1.1-1.1 0-2-.4-2.6-1l-.6 1.5c.8.6 2 1 3.2 1 2.2 0 3.5-1.1 3.5-3.1.1-2.5-3.2-2.2-3.2-3.2z" fill="var(--pi-fg, #FFF)"/></svg>';
            default:
                return '<svg viewBox="0 0 36 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="36" height="24" rx="3" fill="var(--pi-bg, #E2E8F0)"/><path d="M14 8h8v2h-8V8zm0 4h8v2h-8v-2zm0 4h5v2h-5v-2z" fill="var(--pi-fg, #94A3B8)"/></svg>';
        }
    }
}
?>

<div <?php echo $wrapper_attributes; ?>>
    <style>
        .<?php echo esc_attr($unique_id); ?> {
            --pi-gap: <?php echo esc_attr($gap); ?>px;
            --pi-h: <?php echo esc_attr($height); ?>px;
            width: 100%;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-pi-label {
            font-size: 13px; font-weight: bold; letter-spacing: 0.5px; margin-bottom: 15px; text-transform: uppercase;
            color: <?php echo esc_attr($label_c); ?>;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-pi-track {
            display: inline-flex; flex-wrap: wrap; gap: var(--pi-gap); align-items: center;
        }

        .<?php echo esc_attr($unique_id); ?> .mc-pi-track svg {
            height: var(--pi-h); width: auto; display: block; border-radius: 4px;
        }

        /* Presentation Style Variants mapping variable boundaries over native brand definitions */
        .<?php echo esc_attr($unique_id); ?>.style-mono-dark {
            --pi-bg: #1e293b; --pi-fg: #f8fafc; --pi-m1: #f8fafc; --pi-m2: #f8fafc;
        }
        .<?php echo esc_attr($unique_id); ?>.style-mono-dark svg {
            filter: grayscale(1) contrast(1.1); opacity: 0.85;
        }

        .<?php echo esc_attr($unique_id); ?>.style-mono-light {
            --pi-bg: rgba(255, 255, 255, 0.15); --pi-fg: #ffffff; --pi-m1: #ffffff; --pi-m2: #ffffff;
        }
        .<?php echo esc_attr($unique_id); ?>.style-mono-light svg {
            filter: brightness(0) invert(1); opacity: 0.9;
        }

        /* Alignment Rules */
        .<?php echo esc_attr($unique_id); ?>.align-left { text-align: left; }
        .<?php echo esc_attr($unique_id); ?>.align-left .mc-pi-track { justify-content: flex-start; }
        
        .<?php echo esc_attr($unique_id); ?>.align-center { text-align: center; }
        .<?php echo esc_attr($unique_id); ?>.align-center .mc-pi-track { justify-content: center; }
        
        .<?php echo esc_attr($unique_id); ?>.align-right { text-align: right; }
        .<?php echo esc_attr($unique_id); ?>.align-right .mc-pi-track { justify-content: flex-end; }
    </style>

    <?php if ($show_label && !empty($label_text)) : ?>
        <div class="mc-pi-label"><?php echo esc_html($label_text); ?></div>
    <?php endif; ?>

    <div class="mc-pi-track">
        <?php foreach ($active as $icon_id) {
            echo mc_get_payment_svg_vector($icon_id);
        } ?>
    </div>
</div>