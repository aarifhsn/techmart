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
 * Post-launch addition: a real, self-contained wishlist (session +
 * user meta, AJAX toggle, a [techmart_wishlist] shortcode) — the
 * client asked for this after launch instead of the plugin-integration
 * point the original brief specified. This is the first thing in the
 * theme to need admin-ajax.php, so it's also the first inc/ file
 * registering AJAX actions.
 */
require TECHMART_DIR . '/inc/wishlist.php';
