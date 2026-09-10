<?php
/**
 * Why Shop With Us.
 *
 * Unlike the hero/announcement/promo sections, these five items are
 * structural, rarely-changed copy rather than marketing content an
 * admin swaps often — so they're a filterable array in code, not a
 * Customizer section. Adding a Customizer UI for content this stable
 * would be exactly the "unnecessary custom admin panel" §31 warns
 * against; a documented filter is the right amount of configurability
 * for a developer to adjust without one.
 *
 * @package TechMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$items = apply_filters(
	'techmart_trust_items',
	array(
		array(
			'icon'        => 'shield',
			'title'       => __( 'Genuine Products', 'techmart' ),
			'description' => __( '100% authentic items', 'techmart' ),
		),
		array(
			'icon'        => 'tag',
			'title'       => __( 'Best Price Guaranteed', 'techmart' ),
			'description' => __( 'We beat any price', 'techmart' ),
		),
		array(
			'icon'        => 'truck',
			'title'       => __( 'Fast & Free Shipping', 'techmart' ),
			'description' => __( 'On orders over $99', 'techmart' ),
		),
		array(
			'icon'        => 'refresh',
			'title'       => __( 'Easy Returns', 'techmart' ),
			'description' => __( '30 days return policy', 'techmart' ),
		),
		array(
			'icon'        => 'lock',
			'title'       => __( 'Secure Checkout', 'techmart' ),
			'description' => __( 'Your data is protected', 'techmart' ),
		),
	)
);

if ( empty( $items ) ) {
	return;
}
?>
<section class="tm-why-shop tm-band" aria-label="<?php esc_attr_e( 'Why shop with us', 'techmart' ); ?>">
	<div class="tm-container tm-why-shop__grid">
		<?php foreach ( $items as $item ) : ?>
			<?php techmart_get_template_part( 'template-parts/components/trust-item', null, $item ); ?>
		<?php endforeach; ?>
	</div>
</section>
