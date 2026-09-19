<?php
/**
 * Header.
 *
 * Phase 2: composes the announcement bar, main header row, category
 * drawer, and primary nav from template-parts/header/*.php. This file's
 * only job is the document shell and assembly order — each part below
 * owns its own markup, data-fetching, and escaping.
 *
 * @package TechMart
 */

if (!defined('ABSPATH')) {
	exit;
}
?>
<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>

	<a class="tm-skip-link screen-reader-text" href="#primary">
		<?php esc_html_e('Skip to content', 'techmart'); ?>
	</a>

	<header id="masthead" class="tm-header">
		<?php get_template_part('template-parts/header/announcement'); ?>

		<div class="tm-header__main">
			<?php
			get_template_part('template-parts/header/main');
			get_template_part('template-parts/header/category-drawer');
			?>
		</div>

		<?php get_template_part('template-parts/header/navigation'); ?>
	</header>