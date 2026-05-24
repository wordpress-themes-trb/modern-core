<?php
/**
 * ModernCore Advanced Footer (Dumb Wrapper)
 */

declare(strict_types=1);

$footer_bg  = $attributes['footerBg'] ?? '#0f172a';
$text_color = $attributes['textColor'] ?? '#f8fafc';
$enable_reveal = $attributes['enableReveal'] ?? false;

$unique_id = uniqid('mc-footer-');

$inline_styles = "
    .{$unique_id} { 
        background-color: {$footer_bg} !important; 
        color: {$text_color} !important; 
        --mc-footer-text: {$text_color};
        width: 100%;
        overflow: hidden;
    }
    
    .{$unique_id} p, .{$unique_id} h1, .{$unique_id} h2, .{$unique_id} h3, .{$unique_id} h4, .{$unique_id} h5, .{$unique_id} h6, .{$unique_id} li {
        color: var(--mc-footer-text, inherit);
    }

    /* --- NEWSLETTER DARK MODE STYLING --- */
    .{$unique_id} .wp-block-modern-core-newsletter {
        background: transparent !important;
        padding: 0 !important;
        border: none !important;
    }
    .{$unique_id} .wp-block-modern-core-newsletter input[type='email'] {
        background: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        color: #ffffff !important;
    }
    .{$unique_id} .wp-block-modern-core-newsletter input::placeholder {
        color: rgba(255, 255, 255, 0.5) !important;
    }
";

if ($enable_reveal && !is_admin()) {
    $inline_styles .= " .{$unique_id} { position: fixed !important; bottom: 0; left: 0; z-index: -10; } .wp-site-blocks { position: relative; z-index: 1; box-shadow: 0 10px 30px rgba(0,0,0,0.15); } ";
}

$wrapper_attributes = get_block_wrapper_attributes([
    'class' => "modern-core-advanced-footer {$unique_id}"
]);
?>

<style><?php echo $inline_styles; ?></style>

<footer <?php echo $wrapper_attributes; ?>>
    <div class="mc-footer-container" style="max-width: 1200px; margin: 0 auto; padding-left: 20px; padding-right: 20px;">
        <?php echo $content; ?>
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