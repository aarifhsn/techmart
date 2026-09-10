<?php
/**
 * Generic "product grid with a section header" homepage section.
 *
 * Trending Products and Best Sellers are both just this shape with a
 * different title, query, and link, so rather than two nearly
 * identical template files, the hook functions in inc/template-hooks.php
 * fetch their own product list and pass it here. Weekly Deals needs its
 * own template instead of this one, since it adds a section-level
 * countdown alongside the title and link — a third header element that
 * would make this otherwise-simple, widely-reused shell noticeably more
 * complex for the sake of its one caller that needs it.
 *
 * @package TechMart
 *
 * @var array $args {
 *     @type string       $section_class CSS class for the <section>, e.g. 'tm-trending'.
 *     @type string       $aria_label    Accessible section label.
 *     @type string       $title         Section heading.
 *     @type string       $link_url      "View All" URL.
 *     @type string       $link_text     "View All" link text.
 *     @type WC_Product[] $products      Products to render. Section renders nothing if empty.
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args = wp_parse_args(
	$args,
	array(
		'section_class' => '',
		'aria_label'    => '',
		'title'         => '',
		'link_url'      => '',
		'link_text'     => __( 'View All', 'techmart' ),
		'products'      => array(),
	)
);

if ( empty( $args['products'] ) ) {
	return;
}
?>
<section class="tm-band <?php echo esc_attr( $args['section_class'] ); ?>" aria-label="<?php echo esc_attr( $args['aria_label'] ); ?>">
	<div class="tm-container">
		<?php
		techmart_get_template_part(
			'template-parts/components/section-header',
			null,
			array(
				'title'     => $args['title'],
				'link_url'  => $args['link_url'],
				'link_text' => $args['link_text'],
			)
		);
		?>

		<div class="tm-product-grid">
			<?php foreach ( $args['products'] as $product ) : ?>
				<?php
				techmart_get_template_part(
					'template-parts/product/card',
					null,
					array( 'product' => $product )
				);
				?>
			<?php endforeach; ?>
		</div>
	</div>
</section>
