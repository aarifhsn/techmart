<?php
/**
 * Primary navigation row, rendered below the main header.
 *
 * Deliberately does NOT collapse behind a second mobile hamburger.
 * The category drawer already owns the "browse via off-canvas panel"
 * pattern in this header; stacking a second, different disclosure
 * control for the primary menu would give the header two competing
 * "hamburger" affordances with different contents, which is more
 * confusing than useful. Instead this row scrolls horizontally below
 * 1024px (see responsive.css) — every link stays reachable, with no
 * extra JS and no extra state to manage.
 *
 * Skips itself entirely on the front page: the reference design has
 * this menu aligned with the category sidebar's heading, not as its
 * own full-width row — template-parts/home/hero-nav.php renders the
 * same 'primary' menu in that position instead. Every other page (no
 * sidebar to align with) keeps this normal full-width row.
 *
 * @package TechMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( is_front_page() ) {
	return;
}

if ( ! has_nav_menu( 'primary' ) ) {
	return;
}
?>
<nav class="tm-primary-nav" aria-label="<?php esc_attr_e( 'Primary', 'techmart' ); ?>">
	<div class="tm-container">
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'tm-nav__list',
				'fallback_cb'    => false,
			)
		);
		?>
	</div>
</nav>
