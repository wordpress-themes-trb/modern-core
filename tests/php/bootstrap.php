<?php

declare(strict_types=1);

/**
 * PHPUnit Bootstrap File
 *
 * Loads the Composer autoloader so PHPUnit can instantiate our namespace classes.
 */

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

// Mock minimal WP functions required for unit testing standalone classes if necessary.
if (!function_exists('is_rtl')) {
    function is_rtl(): bool { return false; }
}
if (!function_exists('add_action')) {
    function add_action(): bool { return true; }
}