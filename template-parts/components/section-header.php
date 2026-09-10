<?php
/**
 * Section header: a title with an optional "View All" link.
 *
 * Shared by every homepage product/category section — Shop by Category
 * now; Trending Products, Best Sellers, and Weekly Deals from Phase 4 —
 * so the heading style and "View All →" link are never reimplemented
 * per section. Called via techmart_get_template_part() so callers pass
 * $args cleanly (see inc/template-functions.php for that wrapper).
 *
 * @package TechMart
 *
 * @var array $args {
 *     @type string $title      Section heading text. Required.
 *     @type string $link_url   Optional "View All" URL. Omit to hide the link.
 *     @type string $link_text  Optional link text. Default "View All".
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args = wp_parse_args(
	$args,
	array(
		'title'     => '',
		'link_url'  => '',
		'link_text' => __( 'View All', 'techmart' ),
	)
);

if ( '' === $args['title'] ) {
	return;
}
?>
<div class="tm-section-header">
	<h2 class="tm-section-header__title"><?php echo esc_html( $args['title'] ); ?></h2>

	<?php if ( $args['link_url'] ) : ?>
		<a href="<?php echo esc_url( $args['link_url'] ); ?>" class="tm-section-header__link">
			<?php echo esc_html( $args['link_text'] ); ?>
			<?php techmart_icon( 'arrow-right' ); ?>
		</a>
	<?php endif; ?>
</div>
