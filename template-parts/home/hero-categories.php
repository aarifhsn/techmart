<?php
/**
 * Persistent category sidebar shown beside the hero banner.
 *
 * Deliberately NOT the same component as the header's category drawer
 * (template-parts/header/category-drawer.php), even though both list
 * the same get_terms() data. The drawer is a toggleable overlay used
 * on every page; this is a plain, always-visible list used only here
 * — matching the reference design ("left side categories shows, not
 * toggleable"). Different interaction models get different markup
 * rather than forcing one component to awkwardly serve both.
 *
 * Uses each category's native WooCommerce thumbnail (same field
 * template-parts/components/category-item.php reads) instead of a
 * fixed icon-per-category-name mapping, so this keeps working
 * correctly for whatever categories a store actually has — not only
 * ones whose names happen to match the reference mockup's electronics
 * categories.
 *
 * @package TechMart
 */

if (!defined('ABSPATH')) {
	exit;
}

$categories = get_terms(
	array(
		'taxonomy' => 'product_cat',
		'hide_empty' => true,
		'parent' => 0,
	)
);

if (is_wp_error($categories) || empty($categories)) {
	return;
}
?>
<aside class="tm-hero__sidebar" aria-label="<?php esc_attr_e('All categories', 'techmart'); ?>">
	<a href="<?php echo esc_url(get_post_type_archive_link('product')); ?>" class="tm-hero__sidebar-heading">
		<?php esc_html_e('All Categories', 'techmart'); ?>
		<?php techmart_icon('arrow-right'); ?>
	</a>

	<ul class="tm-hero__sidebar-list">
		<?php foreach ($categories as $category): ?>
			<?php $thumb_id = get_term_meta($category->term_id, 'thumbnail_id', true); ?>
			<li>
				<a href="<?php echo esc_url(get_term_link($category)); ?>">
					<span class="tm-hero__sidebar-icon">
						<?php if ($thumb_id): ?>
							<?php echo wp_get_attachment_image($thumb_id, 'thumbnail', false, array('alt' => '')); ?>
						<?php else: ?>
							<?php techmart_icon('tag'); ?>
						<?php endif; ?>
					</span>
					<?php echo esc_html($category->name); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</aside>