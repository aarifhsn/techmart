<?php
/**
 * Fallback template.
 *
 * front-page.php (Phase 3) will own the homepage; archive-product.php
 * and single-product.php (Phase 7/8) will own shop templates. This file
 * exists to satisfy the WordPress template hierarchy for any request
 * that doesn't match a more specific template, and to make sure Phase 1
 * has something renderable to verify the header/footer skeleton against.
 *
 * @package TechMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

	<main id="primary" class="tm-container tm-main">
		<?php if ( have_posts() ) : ?>
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article <?php post_class( 'tm-post' ); ?> id="post-<?php the_ID(); ?>">
					<h1 class="tm-post__title"><?php the_title(); ?></h1>
					<div class="tm-post__content">
						<?php the_content(); ?>
					</div>
				</article>
				<?php
			endwhile;

			the_posts_pagination();
		else :
			?>
			<p><?php esc_html_e( 'Nothing found.', 'techmart' ); ?></p>
			<?php
		endif;
		?>
	</main>

<?php
get_footer();
