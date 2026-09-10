<?php
/**
 * Reusable category card: thumbnail, name, and an optional product count.
 *
 * Renders the homepage "Shop by Category" grid. The category drawer
 * (Phase 2) deliberately uses its own compact list markup instead of
 * this component — a dropdown/off-canvas list and a grid of cards have
 * different layout needs even though both read the same get_terms()
 * data, so sharing one component between them would mean fighting the
 * CSS in one context or the other.
 *
 * The thumbnail reads WooCommerce's native category thumbnail field
 * (term meta 'thumbnail_id', set from Products → Categories in
 * wp-admin) through the 'techmart-category-thumb' image size
 * registered in Phase 1. No thumbnail set → a plain initial-letter
 * placeholder, never a broken image or a generic stock icon standing
 * in for a real product image.
 *
 * @package TechMart
 *
 * @var array $args {
 *     @type WP_Term $category   Required.
 *     @type bool    $show_count Optional. Default false.
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( empty( $args['category'] ) || ! ( $args['category'] instanceof WP_Term ) ) {
	return;
}

$category   = $args['category'];
$show_count = ! empty( $args['show_count'] );
$thumb_id   = get_term_meta( $category->term_id, 'thumbnail_id', true );
?>
<a href="<?php echo esc_url( get_term_link( $category ) ); ?>" class="tm-category-item">
	<span class="tm-category-item__image">
		<?php if ( $thumb_id ) : ?>
			<?php echo wp_get_attachment_image( $thumb_id, 'techmart-category-thumb', false, array( 'alt' => esc_attr( $category->name ) ) ); ?>
		<?php else : ?>
			<span class="tm-category-item__initial" aria-hidden="true"><?php echo esc_html( mb_substr( $category->name, 0, 1 ) ); ?></span>
		<?php endif; ?>
	</span>

	<span class="tm-category-item__name">
		<?php echo esc_html( $category->name ); ?>
		<?php if ( $show_count ) : ?>
			<span class="tm-category-item__count">(<?php echo esc_html( $category->count ); ?>)</span>
		<?php endif; ?>
	</span>
</a>
