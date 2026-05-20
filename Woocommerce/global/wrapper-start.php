<?php
/**
 * Content wrappers
 *
 * @package ModernCore\Woocommerce\Templates
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

// In a Full Site Editing block theme, we do not want WooCommerce injecting 
// legacy <div id="primary"> or <div id="container"> wrappers. Our FSE 
// templates handle the semantic HTML `<main>` tags natively.
return;