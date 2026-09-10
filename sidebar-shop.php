<?php
/**
 * Shop sidebar.
 *
 * Loaded by WooCommerce's woocommerce_sidebar() → core WordPress
 * get_sidebar('shop') template-hierarchy convention (this file's name
 * and location are a WordPress core convention, not a WooCommerce-
 * specific override) — see woocommerce/archive-product.php.
 *
 * Populate the 'shop-sidebar' widget area (registered in inc/setup.php)
 * from Appearance → Widgets with WooCommerce's own native filter
 * widgets — Filter Products by Price, Filter Products by Attribute,
 * Filter Products by Rating, Product Categories, Active Product
 * Filters — rather than this theme building custom filter checkboxes
 * and re-deriving WooCommerce's already-correct query-var handling
 * (§21: "use WooCommerce hooks and template APIs where practical").
 * All of those widgets ship with WooCommerce core; nothing extra to
 * install.
 *
 * @package TechMart
 */

defined( 'ABSPATH' ) || exit;

if ( ! is_active_sidebar( 'shop-sidebar' ) ) {
	return;
}
?>
<div class="tm-shop-sidebar__widgets">
	<?php dynamic_sidebar( 'shop-sidebar' ); ?>
</div>
