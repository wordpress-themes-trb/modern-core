<?php
/**
 * Translations.
 *
 * @package ModernCore\I18n
 */

declare(strict_types=1);

namespace ModernCore\I18n;

use ModernCore\Core\HooksManager;

/**
 * Loads the theme text domain from languages/.
 */
class Translations {

	public const TEXT_DOMAIN = 'modern-core';

	/**
	 * Attach hooks.
	 *
	 * @param HooksManager $hooks Hooks manager.
	 */
	public function register_hooks( HooksManager $hooks ): void {
		$hooks->add_action( 'after_setup_theme', $this, 'load_text_domain' );
	}

	/**
	 * Load translations.
	 */
	public function load_text_domain(): void {
		load_theme_textdomain( self::TEXT_DOMAIN, MODERN_CORE_DIR . '/languages' );
	}
}
