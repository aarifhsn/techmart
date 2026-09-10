<?php
/**
 * TechMart theme bootstrap.
 *
 * This file intentionally does almost nothing itself. It defines the
 * constants used across the theme and requires each inc/ module, which
 * owns one responsibility (setup, asset loading, template tags, and —
 * from later phases — WooCommerce overrides, AJAX handlers, and the
 * Customizer). Keeping functions.php as a thin loader means any file
 * in inc/ can be found by what it does, instead of hunting through a
 * single multi-thousand-line file.
 *
 * @package TechMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Disallow direct access.
}

define( 'TECHMART_VERSION', '1.0.0' );
define( 'TECHMART_DIR', get_template_directory() );
define( 'TECHMART_URI', get_template_directory_uri() );

/**
 * Phase 1 modules.
 */
require TECHMART_DIR . '/inc/setup.php';
require TECHMART_DIR . '/inc/enqueue.php';
require TECHMART_DIR . '/inc/template-functions.php';

/**
 * Phase 2 modules.
 */
require TECHMART_DIR . '/inc/customizer.php';
require TECHMART_DIR . '/inc/woocommerce.php';

/**
 * Phase 3 modules.
 */
require TECHMART_DIR . '/inc/template-hooks.php';

/**
 * Phase 5 modules.
 */
require TECHMART_DIR . '/inc/newsletter.php';

/**
 * Phase 10 modules.
 */
require TECHMART_DIR . '/inc/performance.php';

/**
 * inc/ajax.php, reserved since Phase 1, was never added: every
 * cart/wishlist-adjacent feature this theme built (the header cart
 * badge, the mini-cart dropdown) turned out to need only WooCommerce's
 * existing cart-fragments mechanism, not a custom AJAX endpoint of our
 * own. Left here as a note rather than silently dropped, in case a
 * genuine need for one comes up later — e.g. a custom-built wishlist
 * feature, if one is ever added instead of a plugin.
 */
