<?php
/**
 * Top Brands.
 *
 * Renders whatever techmart_get_brands() (inc/woocommerce.php) returns.
 * Per §18, brands are "initially manually configured" but the theme
 * must not lock the architecture to that — see that function's comment
 * for exactly what changes (only that one function) to upgrade to a
 * taxonomy, CPT, or WooCommerce attribute-backed source later.
 *
 * @package TechMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$brands = techmart_get_brands();

if ( empty( $brands ) ) {
	return;
}
?>
<section class="tm-brands tm-band" aria-label="<?php esc_attr_e( 'Top brands', 'techmart' ); ?>">
	<div class="tm-container">
		<?php
		techmart_get_template_part(
			'template-parts/components/section-header',
			null,
			array( 'title' => __( 'Top Brands', 'techmart' ) )
		);
		?>

		<div class="tm-brands__grid">
			<?php foreach ( $brands as $brand ) : ?>
				<?php techmart_get_template_part( 'template-parts/components/brand-item', null, $brand ); ?>
			<?php endforeach; ?>
		</div>
	</div>
</section>
