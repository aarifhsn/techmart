<?php
/**
 * Product loop item.
 *
 * WooCommerce calls wc_get_template_part( 'content', 'product' ) once
 * per product — from this archive's loop, and also from the related/
 * upsell/cross-sell product grids on the cart and single-product pages
 * once those exist (Phase 8/9) — with `global $product` already set via
 * wc_setup_product_data() before this file loads. That's exactly what
 * template-parts/product/card.php expects (see the comment there), so
 * this override is one line: delegate to it. This is the file that
 * makes "the shop grid reuses the same product-card component as the
 * homepage" (§23) literally true everywhere WooCommerce renders a
 * product loop, not just here.
 *
 * @package TechMart
 */

defined( 'ABSPATH' ) || exit;

global $product;

techmart_get_template_part( 'template-parts/product/card', null, array( 'product' => $product ) );
