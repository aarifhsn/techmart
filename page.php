<?php
/**
 * Static page template.
 *
 * Used for ordinary WordPress Pages — and, since WooCommerce's Cart,
 * Checkout, and My Account "pages" are just regular WordPress pages
 * whose content is a shortcode ([woocommerce_cart], [woocommerce_checkout],
 * [woocommerce_my_account]), not separate top-level template files this
 * theme could override, this is also what actually renders those three.
 * WooCommerce does not fire woocommerce_before_main_content/_after
 * around shortcode output the way it does inside its own archive/single
 * templates, so the container is built directly here rather than via
 * those hooks (compare inc/woocommerce.php's single-product wrapper,
 * which does use them, correctly, since single-product IS one of
 * WooCommerce's own templates).
 *
 * @package TechMart
 */

if (!defined('ABSPATH')) {
	exit;
}

get_header();

/**
 * A breadcrumb makes sense on the Cart/Checkout/Account pages (part of
 * the shopping flow) but not on an arbitrary static page like "About
 * Us" — so it's shown only in the former case rather than on every page.
 */
$show_breadcrumb = function_exists('is_woocommerce') && (is_cart() || is_checkout() || is_account_page());
?>

<main id="primary" class="tm-container tm-page">
	<?php
	while (have_posts()):
		the_post();
		?>

		<?php if ($show_breadcrumb): ?>
			<nav class="tm-breadcrumb" aria-label="<?php esc_attr_e('Breadcrumb', 'techmart'); ?>">
				<?php woocommerce_breadcrumb(); ?>
			</nav>
		<?php endif; ?>

		<h1 class="tm-page__title"><?php the_title(); ?></h1>

		<div class="tm-page__content">
			<?php the_content(); ?>
		</div>

	<?php endwhile; ?>
</main>

<?php
get_footer();
