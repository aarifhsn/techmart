<?php
/**
 * Single product rating.
 *
 * Overridden for the same reason techmart_rating() exists at all (see
 * inc/template-functions.php, Phase 4): WooCommerce's default
 * wc_get_rating_html() depends on WooCommerce's own star-rating CSS,
 * which this theme's stylesheet no longer fully loads (see
 * techmart_dequeue_woocommerce_styles() in inc/enqueue.php). Reuses the
 * exact same star-rendering helper the product card uses, so ratings
 * look identical everywhere in the theme rather than reintroducing that
 * dependency for this one spot.
 *
 * @package TechMart
 */

defined('ABSPATH') || exit;

global $product;

if (!$product instanceof WC_Product || 0 === $product->get_rating_count()) {
	return;
}

techmart_rating($product->get_average_rating(), $product->get_rating_count());
