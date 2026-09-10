<?php
/**
 * Sticky mobile add-to-cart bar.
 *
 * Hidden entirely on desktop (single-product.css); shown on mobile/
 * tablet only once the real add-to-cart form has scrolled out of view
 * (assets/js/single-product.js, via IntersectionObserver watching
 * #tm-add-to-cart — see inc/woocommerce.php for where that ID comes
 * from).
 *
 * Deliberately does NOT duplicate WooCommerce's add-to-cart logic: for
 * a variable product, a variation must be selected first, and
 * reimplementing that selection state here risks getting out of sync
 * with WooCommerce's own variation form. This bar's button instead
 * scrolls back to the real form — correct for every product type,
 * rather than a second, parallel add-to-cart mechanism that could
 * silently diverge from the real one.
 *
 * @package TechMart
 *
 * @var array $args { @type WC_Product $product Required. }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( empty( $args['product'] ) || ! ( $args['product'] instanceof WC_Product ) ) {
	return;
}

$product = $args['product'];
?>
<div class="tm-sticky-bar" id="tm-sticky-bar" hidden>
	<div class="tm-container tm-sticky-bar__inner">
		<div class="tm-sticky-bar__info">
			<?php
			echo $product->get_image( 'thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WC_Product::get_image() returns already-escaped wp_get_attachment_image() output.
			?>
			<div>
				<p class="tm-sticky-bar__title"><?php echo esc_html( $product->get_name() ); ?></p>
				<div class="tm-sticky-bar__price tm-price">
					<?php
					echo $product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce formats/escapes this internally.
					?>
				</div>
			</div>
		</div>

		<button type="button" class="tm-button tm-button--primary" data-scroll-to="#tm-add-to-cart">
			<?php esc_html_e( 'View Options', 'techmart' ); ?>
		</button>
	</div>
</div>
