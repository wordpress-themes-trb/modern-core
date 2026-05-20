<?php

declare(strict_types=1);

namespace ModernCore\I18n;

/**
 * Class Translations
 *
 * Handles localization, text domain loading, and RTL stylesheet support.
 */
class Translations {
    /**
     * Theme text domain.
     */
    public const TEXT_DOMAIN = 'modern-core';

    /**
     * Registers the i18n hooks.
     */
    public function register(): void {
        add_action('after_setup_theme', [$this, 'loadTextDomain']);
        add_action('wp_enqueue_scripts', [$this, 'enforceRtlStyles'], 20);
    }

    /**
     * Loads the theme's translated strings.
     */
    public function loadTextDomain(): void {
        load_theme_textdomain(
            self::TEXT_DOMAIN,
            get_template_directory() . '/languages'
        );
    }

    /**
     * Flips stylesheet dependencies if an RTL language is active.
     */
    public function enforceRtlStyles(): void {
        if (is_rtl()) {
            wp_style_add_data('modern-core-style', 'rtl', 'replace');
        }
    }
}