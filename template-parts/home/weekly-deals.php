<?php
/**
 * Weekly Deals.
 *
 * Products come from techmart_get_weekly_deal_products() (inc/woocommerce.php),
 * which only includes products that are on sale AND have a real,
 * WooCommerce-scheduled sale end date — see that function's comments
 * for why that's what makes the countdown below honest.
 *
 * The countdown is a single, section-level element (matching the
 * reference layout) rather than one per product, and it counts down to
 * the soonest-expiring deal in the list — $products[0], since the
 * query already sorts soonest-first. That's a real, verifiable
 * timestamp: "the first of these deals to end, ends at this time" —
 * not a fixed or guessed duration (§15 is explicit that a fake
 * countdown is worse than no countdown).
 *
 * This section builds its own header markup instead of reusing
 * template-parts/components/section-header.php, since it needs three
 * elements (title, countdown, link) where that component intentionally
 * only handles two.
 *
 * @package TechMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$products = techmart_get_weekly_deal_products( 5 );

if ( empty( $products ) ) {
	return;
}

$soonest_expiry = (int) get_post_meta( $products[0]->get_id(), '_sale_price_dates_to', true );
$has_countdown  = $soonest_expiry > time();
?>
<section class="tm-band tm-weekly-deals" aria-label="<?php esc_attr_e( 'Weekly deals', 'techmart' ); ?>">
	<div class="tm-container">
		<div class="tm-section-header tm-weekly-deals__header">
			<h2 class="tm-section-header__title"><?php esc_html_e( 'Weekly Deals', 'techmart' ); ?></h2>

			<?php if ( $has_countdown ) : ?>
				<div class="tm-countdown" data-expires="<?php echo esc_attr( $soonest_expiry ); ?>">
					<span class="tm-countdown__label"><?php esc_html_e( 'Ends in', 'techmart' ); ?></span>
					<span class="tm-countdown__unit"><span data-unit="days">00</span><small><?php esc_html_e( 'd', 'techmart' ); ?></small></span>
					<span class="tm-countdown__unit"><span data-unit="hours">00</span><small><?php esc_html_e( 'h', 'techmart' ); ?></small></span>
					<span class="tm-countdown__unit"><span data-unit="minutes">00</span><small><?php esc_html_e( 'm', 'techmart' ); ?></small></span>
					<span class="tm-countdown__unit"><span data-unit="seconds">00</span><small><?php esc_html_e( 's', 'techmart' ); ?></small></span>
				</div>
			<?php endif; ?>

			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="tm-section-header__link">
				<?php esc_html_e( 'View All Deals', 'techmart' ); ?>
				<?php techmart_icon( 'arrow-right' ); ?>
			</a>
		</div>

		<div class="tm-product-grid">
			<?php foreach ( $products as $product ) : ?>
				<?php
				techmart_get_template_part(
					'template-parts/product/card',
					null,
					array(
						'product'          => $product,
						'show_stock_meter' => true,
					)
				);
				?>
			<?php endforeach; ?>
		</div>
	</div>
</section>
