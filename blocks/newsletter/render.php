<?php
$mode = $attributes['displayMode'] ?? 'inline';
$trigger = $attributes['triggerType'] ?? 'time';
$trigger_val = $attributes['triggerValue'] ?? 5;
$provider = $attributes['provider'] ?? 'klaviyo';
$layout = $attributes['layout'] ?? 'split';
$heading = $attributes['heading'] ?? 'Stay in the loop';
$subhead = $attributes['subhead'] ?? 'Get exclusive offers, new arrivals, and style inspiration delivered to your inbox.';
$btn_text = $attributes['btnText'] ?? 'Subscribe';
$show_name = $attributes['showName'] ?? false;
$privacy = $attributes['privacyText'] ?? 'We respect your privacy. Unsubscribe at any time.';
$success_msg = $attributes['successMsg'] ?? 'Welcome! Please check your email to confirm.';
$bg = $attributes['bgColor'] ?? '#ffffff';
$text_c = $attributes['textColor'] ?? '#000000';
$accent = $attributes['accentColor'] ?? '#000000';
$radius = $attributes['inputRadius'] ?? 8;

$unique_id = uniqid('mc-nl-');
$wrapper_attributes = get_block_wrapper_attributes(['class' => "mc-block modern-core-newsletter {$unique_id} mode-{$mode} layout-{$layout}"]);

$is_overlay = in_array($mode, ['popup', 'slide', 'sticky']);
?>

<div <?php echo $wrapper_attributes; ?> 
    data-trigger="<?php echo esc_attr($trigger); ?>" 
    data-val="<?php echo esc_attr($trigger_val); ?>"
    data-provider="<?php echo esc_attr($provider); ?>">

    <style>
        .<?php echo esc_attr($unique_id); ?> {
            --mc-bg: <?php echo esc_attr($bg); ?>;
            --mc-text: <?php echo esc_attr($text_c); ?>;
            --mc-accent: <?php echo esc_attr($accent); ?>;
            --mc-rad: <?php echo esc_attr($radius); ?>px;
            box-sizing: border-box;
        }

        .<?php echo esc_attr($unique_id); ?> *,
        .<?php echo esc_attr($unique_id); ?> *::before,
        .<?php echo esc_attr($unique_id); ?> *::after {
            box-sizing: border-box;
        }

        /* 1. Shell & Visibility Physics */
        .<?php echo esc_attr($unique_id); ?>.mode-inline { 
            width: 100%; 
            padding: 80px 5%; 
            background: var(--mc-bg); 
        }

        <?php if ($is_overlay) : ?>
            .<?php echo esc_attr($unique_id); ?> {
                position: fixed; 
                z-index: 99999; 
                opacity: 0; 
                visibility: hidden; 
                pointer-events: none;
                transition: opacity 0.4s ease, visibility 0.4s;
            }
            .<?php echo esc_attr($unique_id); ?>.is-triggered { 
                opacity: 1; 
                visibility: visible; 
                pointer-events: auto; 
            }

            .<?php echo esc_attr($unique_id); ?> .mc-nl-close {
                position: absolute; 
                top: 15px; 
                right: 20px; 
                font-size: 24px; 
                color: var(--mc-text);
                opacity: 0.5; 
                cursor: pointer; 
                border: none; 
                background: none; 
                line-height: 1; 
                z-index: 10;
            }
            .<?php echo esc_attr($unique_id); ?> .mc-nl-close:hover { opacity: 1; }
        <?php endif; ?>

        <?php if ($mode === 'popup') : ?>
            .<?php echo esc_attr($unique_id); ?> { 
                inset: 0; 
                background: rgba(0,0,0,0.6); 
                display: flex; 
                align-items: center; 
                justify-content: center; 
                padding: 20px; 
                overflow-y: auto;
            }
            .<?php echo esc_attr($unique_id); ?> .mc-nl-inner { 
                background: var(--mc-bg); 
                padding: clamp(30px, 5vw, 50px); 
                border-radius: 16px; 
                width: 100%; 
                max-width: 600px; 
                position: relative; 
                transform: scale(0.95); 
                transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); 
            }
            .<?php echo esc_attr($unique_id); ?>.is-triggered .mc-nl-inner { 
                transform: scale(1); 
            }
        <?php endif; ?>

        <?php if ($mode === 'slide') : ?>
            .<?php echo esc_attr($unique_id); ?> { 
                bottom: 30px; 
                right: 30px; 
                left: auto;
                width: auto;
                max-width: 440px;
                display: flex; 
                justify-content: flex-end; 
            }
            .<?php echo esc_attr($unique_id); ?> .mc-nl-inner { 
                background: var(--mc-bg); 
                padding: 40px; 
                border-radius: 16px; 
                width: 100%; 
                max-width: 400px; 
                box-shadow: 0 20px 50px rgba(0,0,0,0.2); 
                position: relative; 
                transform: translateY(50px); 
                transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); 
            }
            .<?php echo esc_attr($unique_id); ?>.is-triggered .mc-nl-inner { 
                transform: translateY(0); 
            }
        <?php endif; ?>

        <?php if ($mode === 'sticky') : ?>
            .<?php echo esc_attr($unique_id); ?> { 
                bottom: 0; 
                left: 0; 
                width: 100vw; 
                background: var(--mc-bg); 
                box-shadow: 0 -10px 40px rgba(0,0,0,0.1); 
                transform: translateY(100%); 
                transition: transform 0.4s ease; 
            }
            .<?php echo esc_attr($unique_id); ?>.is-triggered { 
                transform: translateY(0); 
            }
            .<?php echo esc_attr($unique_id); ?> .mc-nl-inner { 
                padding: 20px 5%; 
                width: 100%; 
                max-width: 1200px; 
                margin: 0 auto;
                position: relative; 
            }
        <?php endif; ?>

        /* 2. Content Layout */
        .<?php echo esc_attr($unique_id); ?> .mc-nl-content { color: var(--mc-text); }

        .<?php echo esc_attr($unique_id); ?>.layout-split .mc-nl-content { 
            display: flex; gap: 60px; align-items: center; max-width: 1200px; margin: 0 auto; 
        }
        .<?php echo esc_attr($unique_id); ?>.layout-split .mc-nl-text { flex: 1; }
        .<?php echo esc_attr($unique_id); ?>.layout-split .mc-nl-form-zone { flex: 1; }

        .<?php echo esc_attr($unique_id); ?>.layout-stack .mc-nl-content { 
            text-align: center; max-width: 600px; margin: 0 auto; 
        }
        .<?php echo esc_attr($unique_id); ?>.layout-stack .mc-nl-text { margin-bottom: 30px; }

        /* Mode Overrides */
        .<?php echo esc_attr($unique_id); ?>.mode-popup .mc-nl-content, 
        .<?php echo esc_attr($unique_id); ?>.mode-slide .mc-nl-content { 
            flex-direction: column; text-align: center; gap: 30px; 
        }
        .<?php echo esc_attr($unique_id); ?>.mode-sticky .mc-nl-content { 
            flex-direction: row; align-items: center; justify-content: space-between; gap: 30px; text-align: left; 
        }
        .<?php echo esc_attr($unique_id); ?>.mode-sticky .mc-nl-text { 
            margin: 0; display: flex; align-items: center; gap: 20px; 
        }

        @media (max-width: 768px) {
            .<?php echo esc_attr($unique_id); ?>.layout-split .mc-nl-content,
            .<?php echo esc_attr($unique_id); ?>.mode-sticky .mc-nl-content { 
                flex-direction: column; text-align: center; gap: 25px; 
            }
            .<?php echo esc_attr($unique_id); ?>.mode-slide { 
                bottom: 15px; right: 15px; left: 15px; max-width: none;
            }
            .<?php echo esc_attr($unique_id); ?>.mode-slide .mc-nl-inner { max-width: 100%; }
            .<?php echo esc_attr($unique_id); ?>.mode-popup { padding: 15px; }
            .<?php echo esc_attr($unique_id); ?>.mode-popup .mc-nl-inner { padding: 30px 20px; }
        }

        /* 3. Typography & Form UI */
        .<?php echo esc_attr($unique_id); ?> .mc-nl-heading { 
            margin: 0 0 10px 0; font-size: clamp(24px, 3vw, 36px); font-weight: 800; line-height: 1.1; color: var(--mc-text);
        }
        .<?php echo esc_attr($unique_id); ?>.mode-sticky .mc-nl-heading { font-size: 20px; margin: 0; }
        .<?php echo esc_attr($unique_id); ?> .mc-nl-sub { 
            margin: 0; font-size: 16px; opacity: 0.8; line-height: 1.5; color: var(--mc-text);
        }

        .<?php echo esc_attr($unique_id); ?> .mc-nl-inputs { display: flex; gap: 10px; margin-bottom: 15px; }
        .<?php echo esc_attr($unique_id); ?>.mode-slide .mc-nl-inputs { flex-direction: column; }
        @media (max-width: 600px) { .<?php echo esc_attr($unique_id); ?> .mc-nl-inputs { flex-direction: column; } }

        .<?php echo esc_attr($unique_id); ?> .mc-nl-input {
            flex: 1; padding: 15px 20px; border: 1px solid rgba(0,0,0,0.15); border-radius: var(--mc-rad);
            font-size: 15px; outline: none; transition: border-color 0.2s; background: #fff; color: #000;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-nl-input:focus { border-color: var(--mc-accent); }

        .<?php echo esc_attr($unique_id); ?> .mc-nl-btn {
            padding: 15px 35px; background: var(--mc-accent); color: #fff; border: none; border-radius: var(--mc-rad);
            font-weight: bold; font-size: 15px; cursor: pointer; transition: opacity 0.2s; white-space: nowrap;
        }
        .<?php echo esc_attr($unique_id); ?> .mc-nl-btn:hover { opacity: 0.8; }
        .<?php echo esc_attr($unique_id); ?> .mc-nl-btn.is-loading { opacity: 0.7; pointer-events: none; }

        .<?php echo esc_attr($unique_id); ?> .mc-nl-privacy { font-size: 12px; opacity: 0.6; color: var(--mc-text); }

        /* 4. States */
        .<?php echo esc_attr($unique_id); ?> .mc-nl-success { 
            display: none; text-align: center; padding: 20px; color: var(--mc-text); font-weight: bold; font-size: 18px; 
        }
        .<?php echo esc_attr($unique_id); ?>.is-success .mc-nl-form { display: none; }
        .<?php echo esc_attr($unique_id); ?>.is-success .mc-nl-success { display: block; animation: mcFadeIn 0.5s ease; }
        @keyframes mcFadeIn { from{opacity:0;} to{opacity:1;} }
    </style>

    <?php if ($is_overlay) echo '<div class="mc-nl-inner"><button class="mc-nl-close" aria-label="Close">✕</button>'; ?>

    <div class="mc-nl-content">
        <div class="mc-nl-text">
            <h3 class="mc-nl-heading" style="color: inherit;"><?php echo esc_html($heading); ?></h3>
            <?php if ($subhead) echo '<p class="mc-nl-sub">' . esc_html($subhead) . '</p>'; ?>
        </div>

        <div class="mc-nl-form-zone">
            <form class="mc-nl-form">
                <div class="mc-nl-inputs">
                    <?php if ($show_name) : ?>
                        <label class="screen-reader-text" for="<?php echo esc_attr( $unique_id ); ?>-name"><?php esc_html_e( 'First name', 'modern-core' ); ?></label>
                        <input id="<?php echo esc_attr( $unique_id ); ?>-name" type="text" name="mc_fname" class="mc-nl-input" placeholder="<?php esc_attr_e( 'First name', 'modern-core' ); ?>" autocomplete="given-name" required />
                    <?php endif; ?>
                    <label class="screen-reader-text" for="<?php echo esc_attr( $unique_id ); ?>-email"><?php esc_html_e( 'Email address', 'modern-core' ); ?></label>
                    <input id="<?php echo esc_attr( $unique_id ); ?>-email" type="email" name="mc_email" class="mc-nl-input" placeholder="<?php esc_attr_e( 'Email address', 'modern-core' ); ?>" autocomplete="email" required />
                    <?php // Spam trap: hidden from people, filled in by bots. ?>
                    <input type="text" name="mc_website" value="" tabindex="-1" autocomplete="off" aria-hidden="true" style="position:absolute;left:-9999px;width:1px;height:1px;opacity:0;" />
                    <button type="submit" class="mc-nl-btn"><?php echo esc_html($btn_text); ?></button>
                </div>
                <?php if ($privacy) echo '<div class="mc-nl-privacy">' . esc_html($privacy) . '</div>'; ?>
            </form>

            <div class="mc-nl-success">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin: 0 auto 15px auto; display: block;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                <?php echo esc_html($success_msg); ?>
            </div>
        </div>
    </div>

    <?php if ($is_overlay) echo '</div>'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const block = document.querySelector('.<?php echo esc_attr($unique_id); ?>');
            if (!block) return;

            const mode = '<?php echo esc_attr($mode); ?>';
            const trigger = block.dataset.trigger;
            const triggerVal = parseInt(block.dataset.val);
            const form = block.querySelector('.mc-nl-form');
            const btn = block.querySelector('.mc-nl-btn');
            const closeBtn = block.querySelector('.mc-nl-close');
            const isOverlay = mode !== 'inline';

            // --- TRIGGER LOGIC ---
            if (isOverlay) {
                if (sessionStorage.getItem('mc_nl_dismissed_<?php echo esc_attr($unique_id); ?>')) return;

                const showBlock = () => {
                    if (!block.classList.contains('is-triggered')) {
                        block.classList.add('is-triggered');
                    }
                };

                const dismissBlock = () => {
                    block.classList.remove('is-triggered');
                    sessionStorage.setItem('mc_nl_dismissed_<?php echo esc_attr($unique_id); ?>', 'true');
                };

                if (closeBtn) closeBtn.addEventListener('click', dismissBlock);
                if (mode === 'popup') {
                    block.addEventListener('click', (e) => {
                        if (e.target === block) dismissBlock(); 
                    });
                }

                if (trigger === 'time') {
                    setTimeout(showBlock, triggerVal * 1000);
                } 
                else if (trigger === 'scroll') {
                    const onScroll = () => {
                        const scrollHeight = document.documentElement.scrollHeight - window.innerHeight;
                        const scrollPercent = (window.scrollY / scrollHeight) * 100;
                        if (scrollPercent >= triggerVal) {
                            showBlock();
                            window.removeEventListener('scroll', onScroll);
                        }
                    };
                    window.addEventListener('scroll', onScroll);
                } 
                else if (trigger === 'exit') {
                    const onMouseLeave = (e) => {
                        if (e.clientY < 0) { 
                            showBlock();
                            document.removeEventListener('mouseleave', onMouseLeave);
                        }
                    };
                    document.addEventListener('mouseleave', onMouseLeave);
                }
            }

            // --- AJAX SUBMIT LOGIC ---
            if (form) {
                form.addEventListener('submit', (e) => {
                    e.preventDefault();

                    const btnOriginalText = btn.innerText;
                    btn.classList.add('is-loading');
                    btn.innerText = 'Sending...';

                    const formData = new FormData(form);
                    formData.append('action', 'mc_newsletter_submit_ajax');
                formData.append('nonce', (window.mc_ajax && mc_ajax.nonce) || '');
                    formData.append('provider', block.dataset.provider);

                    // Ensure ajaxurl is defined by WordPress or your theme
                    const ajaxUrl = (typeof mc_ajax !== 'undefined' && mc_ajax.ajaxurl) ? mc_ajax.ajaxurl : '/wp-admin/admin-ajax.php';

                    fetch(ajaxUrl, {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            block.classList.add('is-success');
                            if (isOverlay) {
                                sessionStorage.setItem('mc_nl_dismissed_<?php echo esc_attr($unique_id); ?>', 'true');
                                setTimeout(() => block.classList.remove('is-triggered'), 4000);
                            }
                        } else {
                            alert(data.data?.message || 'There was an error. Please try again.');
                            btn.classList.remove('is-loading');
                            btn.innerText = btnOriginalText;
                        }
                    })
                    .catch(() => {
                        alert('Network error. Please try again.');
                        btn.classList.remove('is-loading');
                        btn.innerText = btnOriginalText;
                    });
                });
            }
        });
    </script>
</div>