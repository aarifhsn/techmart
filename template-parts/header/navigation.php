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
 * @package TechMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
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
