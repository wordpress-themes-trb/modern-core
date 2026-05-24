<?php

/**
 * ModernCore Advanced Footer Rendering Engine
 * Handles full responsive configurations, customizable layouts, and dynamic styling variables.
 *
 * @package ModernCore\Blocks
 */

declare(strict_types=1);

$width_mode = $attributes['widthMode'] ?? 'contained';
// Extract configuration attributes populated from the block.json blueprint
$layout_mode          = $attributes['layoutMode'] ?? 'four-column';
$brand_name          = $attributes['brandName'] ?? 'MODERNCORE';
$brand_description   = $attributes['brandDescription'] ?? 'Elevated minimal essentials curated for the modern wardrobe.';
$footer_bg            = $attributes['footerBg'] ?? '#0f172a';
$text_color          = $attributes['textColor'] ?? '#f8fafc';
$accent_color        = $attributes['accentColor'] ?? '#e2b57a';
$show_payment_icons  = $attributes['showPaymentIcons'] ?? true;
$show_social         = $attributes['showSocial'] ?? true;
$enable_reveal       = $attributes['enableReveal'] ?? false;

// Spacing scale conversions
$pad_top_desktop     = ($attributes['paddingTopDesktop'] ?? 80) . 'px';
$pad_bottom_desktop  = ($attributes['paddingBottomDesktop'] ?? 40) . 'px';
$pad_top_mobile      = ($attributes['paddingTopMobile'] ?? 40) . 'px';
$pad_bottom_mobile   = ($attributes['paddingBottomMobile'] ?? 24) . 'px';

$unique_id = uniqid('mc-footer-');

// Compile modern inline styling block supporting responsive calculations safely
$inline_styles = "
    .{$unique_id} { 
        background-color: {$footer_bg} !important; 
        color: {$text_color} !important; 
        padding-top: {$pad_top_mobile}; 
        padding-bottom: {$pad_bottom_mobile};
        width: 100%;
        overflow: hidden;
    }
    @media (min-width: 768px) {
        .{$unique_id} { 
            padding-top: {$pad_top_desktop}; 
            padding-bottom: {$pad_bottom_desktop}; 
        }
    }
    .{$unique_id} a { color: inherit; transition: color 0.2s ease-in-out; }
    .{$unique_id} a:hover { color: {$accent_color}; }
    .{$unique_id} h4 { color: #ffffff !important; font-weight: 700; margin-bottom: 1.25rem; font-size: 0.875rem; letter-spacing: 0.05em; text-transform: uppercase; }
";

if ($enable_reveal && !is_admin()) {
    $inline_styles .= "
        .{$unique_id} { position: fixed !important; bottom: 0; left: 0; z-index: -10; }
        .wp-site-blocks { position: relative; z-index: 1; box-shadow: 0 10px 30px rgba(0,0,0,0.15); }
    ";
}

$wrapper_attributes = get_block_wrapper_attributes([
    'class' => "mc-block width-" . esc_attr($width_mode) . " modern-core-advanced-footer {$unique_id}",
    'style' => 'width: 100%;'
]);
?>

<style><?php echo $inline_styles; ?></style>

<footer <?php echo $wrapper_attributes; ?>>
    <div class="mc-footer-container" style="max-width: 1200px; margin: 0 auto; padding-left: 20px; padding-right: 20px;">
        
        <?php if ($layout_mode === 'four-column') : ?>
            <div class="mc-footer-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 40px; margin-bottom: 60px;">
                <div class="mc-footer-brand-col">
                    <h3 style="font-family: var(--wp--preset--font-family--primary); font-size: 1.5rem; font-weight: 800; letter-spacing: -0.03em; margin: 0 0 1rem 0; color: #ffffff;"><?php echo esc_html($brand_name); ?></h3>
                    <p style="font-size: 0.875rem; line-height: 1.6; opacity: 0.7; margin-bottom: 1.5rem; max-width: 280px;"><?php echo esc_html($brand_description); ?></p>
                    <?php if ($show_social) : ?>
                        <div class="mc-footer-socials" style="display: flex; gap: 16px; align-items: center;">
                            <a href="#" aria-label="Instagram">🗲</a><a href="#" aria-label="Facebook">🗪</a><a href="#" aria-label="Pinterest">🗱</a>
                        </div>
                    <?php endif; ?>
                </div>
                <div>
                    <h4>Shop Collections</h4>
                    <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.875rem; line-height: 2.2;">
                        <li><a href="/shop">New Arrivals</a></li>
                        <li><a href="/product-category/women">Women's Wear</a></li>
                        <li><a href="/product-category/men">Men's Wear</a></li>
                        <li><a href="/sale">Current Promotions</a></li>
                    </ul>
                </div>
                <div>
                    <h4>Customer Support</h4>
                    <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.875rem; line-height: 2.2;">
                        <li><a href="/contact">Contact Support</a></li>
                        <li><a href="/order-tracking">Track Order Delivery</a></li>
                        <li><a href="/returns">Easy Returns Policy</a></li>
                        <li><a href="/faq">Store Frequently Asked Questions</a></li>
                    </ul>
                </div>
                <div>
                    <h4>Corporate Trust</h4>
                    <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.875rem; line-height: 2.2;">
                        <li><a href="/about">Our Story</a></li>
                        <li><a href="/sustainability">Sustainable Execution</a></li>
                        <li><a href="/privacy-policy">Privacy & Cookie Mandates</a></li>
                        <li><a href="/terms">Terms of Enterprise Operation</a></li>
                    </ul>
                </div>
            </div>

        <?php elseif ($layout_mode === 'minimal-centered') : ?>
            <div class="mc-footer-centered-flow" style="text-align: center; margin-bottom: 40px; padding: 20px 0;">
                <h3 style="font-size: 1.75rem; font-weight: 800; color: #ffffff; margin-bottom: 1.5rem; letter-spacing: -0.03em;"><?php echo esc_html($brand_name); ?></h3>
                <div class="mc-footer-centered-links" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 24px; margin-bottom: 1.5rem; font-size: 0.875rem;">
                    <a href="/shop">Shop</a><a href="/about">About Us</a><a href="/faq">FAQ</a><a href="/contact">Contact</a><a href="/privacy-policy">Privacy</a>
                </div>
                <?php if ($show_social) : ?>
                    <div style="display: flex; justify-content: center; gap: 20px; font-size: 1.25rem;">
                        <a href="#">🗲</a><a href="#">🗪</a><a href="#">🗱</a>
                    </div>
                <?php endif; ?>
            </div>

        <?php else : ?>
            <div class="mc-footer-split-flow" style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 30px; margin-bottom: 40px; padding-bottom: 30px; border-bottom: 1px solid rgba(255,255,255,0.08);">
                <div>
                    <h3 style="font-size: 1.5rem; font-weight: 800; color: #ffffff; margin: 0 0 0.5rem 0;"><?php echo esc_html($brand_name); ?></h3>
                    <p style="font-size: 0.875rem; opacity: 0.6; margin: 0;"><?php echo esc_html($brand_description); ?></p>
                </div>
                <div style="display: flex; gap: 24px; font-size: 0.875rem;">
                    <a href="/shop">Catalogue Index</a><a href="/returns">Returns Pipeline</a><a href="/contact">Helpdesk</a>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty(trim($content))) : ?>
            <div class="mc-footer-injected-blocks" style="margin-bottom: 40px; width: 100%;">
                <?php echo $content; ?>
            </div>
        <?php endif; ?>

        <div class="mc-footer-base-strip" style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 20px; padding-top: 24px; border-top: 1px solid rgba(255,255,255,0.08); font-size: 0.75rem; opacity: 0.6;">
            <div class="mc-copyright-text">
                &copy; <?php echo date('Y'); ?> <?php echo esc_html($brand_name); ?> eCommerce Inc. All Rights Reserved. Designed for Enterprise Scale.
            </div>
            
            <?php if ($show_payment_icons) : ?>
                <div class="mc-payment-badges-strip" style="display: flex; gap: 8px; align-items: center; filter: grayscale(1) brightness(2);">
                    <div style="width: 38px; height: 24px; background: rgba(255,255,255,0.1); border-radius: 3px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 8px;">VISA</div>
                    <div style="width: 38px; height: 24px; background: rgba(255,255,255,0.1); border-radius: 3px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 8px;">MC</div>
                    <div style="width: 38px; height: 24px; background: rgba(255,255,255,0.1); border-radius: 3px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 8px;">AMEX</div>
                    <div style="width: 38px; height: 24px; background: rgba(255,255,255,0.1); border-radius: 3px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 8px;">PP</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</footer>

<?php if ($enable_reveal && !is_admin()) : ?>
<script>
    (function() {
        const initReveal = () => {
            const footer = document.querySelector('.<?php echo esc_attr($unique_id); ?>');
            const wrapper = document.querySelector('.wp-site-blocks');
            if (footer && wrapper) {
                const triggerResize = () => {
                    wrapper.style.marginBottom = footer.offsetHeight + 'px';
                };
                triggerResize();
                window.addEventListener('resize', triggerResize);
            }
        };
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initReveal);
        } else {
            initReveal();
        }
    })();
</script>
<?php endif; ?>