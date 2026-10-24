<?php
/**
 * Modern Core theme bootstrap.
 *
 * Everything lives in inc/ under the ModernCore namespace. This file only
 * defines constants, autoloads those classes and boots the theme.
 *
 * @package ModernCore
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MODERN_CORE_VERSION', wp_get_theme( get_template() )->get( 'Version' ) ?: '1.0.0' );
define( 'MODERN_CORE_DIR', get_template_directory() );
define( 'MODERN_CORE_URI', get_template_directory_uri() );

/*
 * PSR-4 autoloader for ModernCore\ => inc/.
 *
 * The theme does not depend on Composer at runtime, so the vendor/ folder
 * (PHPUnit, PHPCS) is only needed for development and is not shipped.
 */
spl_autoload_register(
	static function ( string $class_name ): void {
		$prefix = 'ModernCore\\';
		if ( 0 !== strpos( $class_name, $prefix ) ) {
			return;
		}
		$relative = str_replace( '\\', '/', substr( $class_name, strlen( $prefix ) ) );
		$file     = MODERN_CORE_DIR . '/inc/' . $relative . '.php';
		if ( is_readable( $file ) ) {
			require_once $file;
		}
	}
);

require_once MODERN_CORE_DIR . '/inc/helpers.php';

( new \ModernCore\Core\ThemeLoader() )->boot();
