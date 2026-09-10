<?php
/**
 * Category drawer / dropdown.
 *
 * Populated from real WooCommerce product categories via get_terms() —
 * not a hardcoded list — per the brief's data-driven-homepage
 * requirement. Falls back to the 'category-nav' menu location only if
 * no product categories exist yet (e.g. WooCommerce just installed),
 * so the drawer is never silently empty during setup.
 *
 * The SAME markup renders as a desktop dropdown panel or a mobile
 * off-canvas panel purely through CSS (header.css / responsive.css).
 * app.js only adds scroll-lock and focus-trap behavior when the mobile
 * layout is active — a desktop dropdown is a simple disclosure widget
 * and doesn't need modal-style focus trapping.
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
	)
);

$has_categories = ! is_wp_error( $categories ) && ! empty( $categories );
?>
<div id="tm-category-drawer" class="tm-category-drawer" hidden>
	<div class="tm-category-drawer__header">
		<span class="tm-label"><?php esc_html_e( 'All Categories', 'techmart' ); ?></span>
		<button type="button" class="tm-category-drawer__close">
			<span aria-hidden="true">&times;</span>
			<span class="screen-reader-text"><?php esc_html_e( 'Close menu', 'techmart' ); ?></span>
		</button>
	</div>

	<?php if ( $has_categories ) : ?>
		<ul class="tm-category-drawer__list">
			<?php foreach ( $categories as $category ) : ?>
				<li>
					<a href="<?php echo esc_url( get_term_link( $category ) ); ?>">
						<?php echo esc_html( $category->name ); ?>
						<span class="tm-category-drawer__count">(<?php echo esc_html( $category->count ); ?>)</span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php elseif ( has_nav_menu( 'category-nav' ) ) : ?>
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'category-nav',
				'container'      => false,
				'menu_class'     => 'tm-category-drawer__list',
				'depth'          => 1,
			)
		);
		?>
	<?php else : ?>
		<p class="tm-category-drawer__empty"><?php esc_html_e( 'No product categories yet.', 'techmart' ); ?></p>
	<?php endif; ?>
</div>
