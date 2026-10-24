<?php
/**
 * Accessibility helpers.
 *
 * @package ModernCore\Accessibility
 */

declare(strict_types=1);

namespace ModernCore\Accessibility;

use ModernCore\Core\HooksManager;

/**
 * Small accessibility fixes WordPress doesn't apply by default.
 *
 * The skip link itself comes from WordPress core, which adds it to block
 * themes automatically and points it at the template's <main> element.
 */
class A11yEnforcer {

	/**
	 * Attach hooks.
	 *
	 * @param HooksManager $hooks Hooks manager.
	 */
	public function register_hooks( HooksManager $hooks ): void {
		$hooks->add_filter( 'nav_menu_link_attributes', $this, 'add_aria_current', 10, 2 );
	}

	/**
	 * Mark the current menu item for screen readers.
	 *
	 * @param array<string, string> $atts Link attributes.
	 * @param \WP_Post              $item Menu item.
	 * @return array<string, string>
	 */
	public function add_aria_current( array $atts, $item ): array {
		if ( isset( $item->classes ) && in_array( 'current-menu-item', (array) $item->classes, true ) ) {
			$atts['aria-current'] = 'page';
		}
		return $atts;
	}
}
