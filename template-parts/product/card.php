<?php
/**
 * Reusable product card.
 *
 * Shared, unmodified, by every homepage product section (§34 is
 * explicit: "Trending Products / Best Sellers / Weekly Deals should
 * reuse the same product-card component") and, from Phase 7 onward,
 * the shop/archive grid.
 *
 * Sets `global $product` deliberately: several WooCommerce template
 * functions used below — woocommerce_template_loop_add_to_cart() in
 * particular — read that global rather than accepting a product as a
 * parameter, since they're designed to run inside WooCommerce's own
 * post loop. Setting it here (instead of relying on the caller having
 * looped through the_post()) lets this card work identically whether
 * it's called from a real WP_Query loop or, as on the homepage, from a
 * plain array of WC_Product objects.
 *
 * @package TechMart
 *
 * @var array $args {
 *     @type WC_Product $product          Required.
 *     @type bool        $show_stock_meter Optional. Show a sold/remaining-stock
 *                                          bar when the product has real stock-
 *                                          quantity data. Default false — only
 *                                          Weekly Deals turns this on (§15).
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args = wp_parse_args(
	$args,
	array(
		'product'          => null,
		'show_stock_meter' => false,
	)
);

if ( ! ( $args['product'] instanceof WC_Product ) || ! $args['product']->exists() ) {
	return;
}

global $product;
$product = $args['product'];

$badges       = techmart_get_product_badges( $product );
$rating_count = $product->get_rating_count();
?>
<div class="tm-product-card">
	<div class="tm-product-card__media">
		<?php if ( $badges ) : ?>
			<ul class="tm-badge-list">
				<?php foreach ( $badges as $badge ) : ?>
					<li class="tm-badge tm-badge--<?php echo esc_attr( $badge['type'] ); ?>"><?php echo esc_html( $badge['label'] ); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php
		/**
		 * Wishlist toggle. Real functionality now lives in
		 * inc/wishlist.php (session/user-meta storage + AJAX), bound to
		 * .tm-wishlist-toggle buttons by initWishlistToggles() in
		 * assets/js/app.js. Initial aria-pressed reflects the visitor's
		 * actual saved state so a fresh page load is never wrong before
		 * any click happens.
		 */
		$in_wishlist = techmart_is_in_wishlist( $product->get_id() );
		?>
		<button
			type="button"
			class="tm-wishlist-toggle tm-product-card__wishlist"
			data-product-id="<?php echo esc_attr( $product->get_id() ); ?>"
			aria-pressed="<?php echo $in_wishlist ? 'true' : 'false'; ?>"
		>
			<?php techmart_icon( 'heart' ); ?>
			<span class="screen-reader-text"><?php esc_html_e( 'Add to wishlist', 'techmart' ); ?></span>
		</button>

		<a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="tm-product-card__image-link">
			<?php
			echo $product->get_image( 'techmart-product-card' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WC_Product::get_image() returns wp_get_attachment_image() output, already escaped by WordPress core.
			?>
		</a>
	</div>

	<div class="tm-product-card__body">
		<h3 class="tm-product-card__title">
			<a href="<?php echo esc_url( $product->get_permalink() ); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
		</h3>

		<?php if ( $rating_count > 0 ) : ?>
			<?php techmart_rating( $product->get_average_rating(), $rating_count ); ?>
		<?php endif; ?>

		<div class="tm-product-card__price tm-price">
			<?php
			echo $product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce formats and escapes this internally via wc_price()/wc_format_sale_price().
			?>
		</div>

		<?php if ( $args['show_stock_meter'] && $product->managing_stock() && null !== $product->get_stock_quantity() ) : ?>
			<?php
			$remaining = max( 0, (int) $product->get_stock_quantity() );
			$sold      = max( 0, (int) $product->get_total_sales() );
			$total     = $sold + $remaining;
			?>
			<?php if ( $total > 0 ) : ?>
				<div class="tm-stock-meter">
					<div class="tm-stock-meter__bar">
						<span style="width:<?php echo esc_attr( round( ( $sold / $total ) * 100 ) ); ?>%"></span>
					</div>
					<p class="tm-stock-meter__label">
						<?php
						printf(
							/* translators: %d: number of items left in stock */
							esc_html__( '%d left in stock', 'techmart' ),
							$remaining
						);
						?>
					</p>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<?php
		/**
		 * WooCommerce's own loop add-to-cart template — NOT a custom
		 * button — because it already renders the correct call to
		 * action per product type (AJAX "Add to Cart" for simple
		 * products, "Select options" linking to the product page for
		 * variable/grouped, "Buy Product" for external/affiliate) and
		 * wires up the AJAX/variation JS WooCommerce ships. Reimplementing
		 * this button is exactly how "don't break variable products"
		 * (§12/§22) gets violated in practice. The custom 'class' string
		 * below keeps WooCommerce's own required classes (so its JS still
		 * recognizes the button) and adds our .tm-button styling on top.
		 */
		$wc_classes = implode(
			' ',
			array_filter(
				array(
					'button',
					'tm-button',
					'tm-button--primary',
					'tm-product-card__add-to-cart',
					'product_type_' . $product->get_type(),
					( $product->is_purchasable() && $product->is_in_stock() ) ? 'add_to_cart_button' : '',
					( $product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ) ? 'ajax_add_to_cart' : '',
				)
			)
		);

		woocommerce_template_loop_add_to_cart( array( 'class' => $wc_classes ) );
		?>
	</div>
</div>
