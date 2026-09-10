<?php
/**
 * Shop by Category.
 *
 * Categories are pulled from real WooCommerce data via get_terms() —
 * not a hardcoded list — per the brief's data-driven-homepage
 * requirement. Each item renders through the reusable category-item
 * component (template-parts/components/category-item.php); the section
 * itself only fetches data and lays out the grid.
 *
 * @package TechMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$categories = get_terms(
	array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
		'parent'     => 0,
		'number'     => 8,
	)
);

if ( is_wp_error( $categories ) || empty( $categories ) ) {
	return;
}
?>
<section class="tm-categories tm-band" aria-label="<?php esc_attr_e( 'Shop by category', 'techmart' ); ?>">
	<div class="tm-container">
		<?php
		techmart_get_template_part(
			'template-parts/components/section-header',
			null,
			array(
				'title'     => __( 'Shop by Category', 'techmart' ),
				'link_url'  => get_post_type_archive_link( 'product' ),
				'link_text' => __( 'View All Categories', 'techmart' ),
			)
		);
		?>

		<ul class="tm-categories__grid">
			<?php foreach ( $categories as $category ) : ?>
				<li>
					<?php
					techmart_get_template_part(
						'template-parts/components/category-item',
						null,
						array( 'category' => $category )
					);
					?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
