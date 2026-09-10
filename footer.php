<?php
/**
 * Footer.
 *
 * Phase 6: composes the full footer from template-parts/footer/*.php,
 * same "this file is just assembly order" approach as header.php since
 * Phase 2. The optional widget area sits between the main columns and
 * the bottom bar — matching the description given when it was
 * registered in Phase 1 ("shown in the footer, below the main footer
 * columns").
 *
 * @package TechMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
	<footer id="colophon" class="tm-footer">
		<?php get_template_part( 'template-parts/footer/main' ); ?>

		<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
			<div class="tm-container tm-footer__widgets">
				<?php dynamic_sidebar( 'footer-1' ); ?>
			</div>
		<?php endif; ?>

		<?php get_template_part( 'template-parts/footer/bottom' ); ?>
	</footer>

<?php wp_footer(); ?>
</body>
</html>
