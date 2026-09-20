<?php
/**
 * Shop sidebar.
 *
 * @package TechMart
 */

defined('ABSPATH') || exit;

/**
 * This sidebar belongs only to WooCommerce product archives.
 *
 * Never render it on:
 * - Single product pages
 * - Cart
 * - Checkout
 * - My Account
 */
if (!is_shop() && !is_product_taxonomy()) {
	return;
}

if (!is_active_sidebar('shop-sidebar')) {
	return;
}
?>

<div class="tm-shop-sidebar__widgets">
	<?php dynamic_sidebar('shop-sidebar'); ?>
</div>