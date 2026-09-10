<?php
/**
 * Mini-cart dropdown.
 *
 * Renders WooCommerce's own woocommerce_mini_cart() template
 * (cart/mini-cart.php — not overridden; restyled in header.css via its
 * stable public classes) inside our dropdown panel. The wrapping
 * div.widget_shopping_cart_content matches the fragment selector
 * registered in techmart_cart_fragments() (inc/woocommerce.php), so
 * this dropdown's contents refresh automatically after every AJAX
 * add-to-cart, the same mechanism the header's .tm-cart-count badge
 * already relies on since Phase 2 — no extra JS needed to keep the two
 * in sync with each other.
 *
 * @package TechMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="tm-mini-cart" id="tm-mini-cart" hidden>
	<div class="widget_shopping_cart_content">
		<?php woocommerce_mini_cart(); ?>
	</div>
</div>
