<?php

declare(strict_types=1);

namespace ModernCore\Accessibility;

/**
 * Class A11yEnforcer
 *
 * Enforces strict WCAG 2.2 AA standards via PHP DOM injection.
 */
class A11yEnforcer {
    /**
     * Registers the accessibility hooks.
     */
    public function register(): void {
        add_filter('nav_menu_link_attributes', [$this, 'addAriaCurrentToActiveLinks'], 10, 2);
        add_action('wp_body_open', [$this, 'injectSkipLink'], 5);
    }

    /**
     * Injects aria-current attributes into active navigation items.
     *
     * @param array    $atts HTML attributes applied to the menu item's anchor element.
     * @param \WP_Post $item The current menu item.
     * @return array Modified attributes.
     */
    public function addAriaCurrentToActiveLinks(array $atts, \WP_Post $item): array {
        if (isset($item->classes) && in_array('current-menu-item', $item->classes, true)) {
            $atts['aria-current'] = 'page';
        }
        return $atts;
    }

    /**
     * Injects a screen-reader-only skip link at the top of the DOM.
     */
    public function injectSkipLink(): void {
        echo '<a class="skip-link screen-reader-text" href="#wp--skip-link--target">' . 
             esc_html__('Skip to main content', 'modern-core') . 
             '</a>';
    }
}