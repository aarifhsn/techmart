<?php
/**
 * Primary nav, rendered inside the hero grid on the homepage instead
 * of as its own full-width header row.
 *
 * template-parts/header/navigation.php skips itself on the front page
 * specifically so this can take its place here, aligned with the
 * category sidebar's heading — matching the reference design, where
 * the nav sits level with "All Categories" rather than in a separate
 * band above the whole page. Same 'primary' menu location and
 * wp_nav_menu() call as the header version; deliberately not the same
 * template part, since the surrounding markup here has no
 * border-bottom or full-width band — it's one cell in the hero's grid.
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
<nav class="tm-hero__nav" aria-label="<?php esc_attr_e( 'Primary', 'techmart' ); ?>">
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
</nav>
