<?php
/**
 * Shop / category / tag archive.
 *
 * A full override of WooCommerce's bundled archive-product.php — not
 * just hooks — because the two-column filters-sidebar-plus-content
 * layout, and combining result-count + ordering into one toolbar row,
 * both need control over wrapping markup that hook order alone can't
 * provide. Every hook the stock template fires is still fired here
 * (woocommerce_before_main_content, woocommerce_archive_description,
 * woocommerce_before_shop_loop, woocommerce_shop_loop,
 * woocommerce_after_shop_loop, woocommerce_no_products_found,
 * woocommerce_after_main_content, woocommerce_sidebar) — this overrides
 * layout, not behavior, so any plugin hooking those still works exactly
 * as it would with the stock template. The one deliberate change is
 * WHEN woocommerce_sidebar fires: moved earlier, into the <aside>
 * column, instead of after the main content — its own output
 * (get_sidebar('shop')) doesn't depend on firing order, only on firing
 * once, so this is safe.
 *
 * Deliberately NOT overridden: woocommerce/loop/result-count.php,
 * orderby.php, and pagination.php. Their default markup already carries
 * stable classes (.woocommerce-result-count, .woocommerce-ordering,
 * .woocommerce-pagination) that assets/css/shop.css restyles directly —
 * overriding those templates would just be a longer way of producing
 * HTML WooCommerce already outputs correctly (§21: don't override
 * templates unnecessarily).
 *
 * The product grid itself uses the native WordPress main query
 * (have_posts()/the_post()), not wc_get_products() — this query is
 * already filtered, sorted, and paginated by WooCommerce's own
 * pre_get_posts handling of the URL's page/orderby/filter-widget query
 * vars. Querying separately with wc_get_products() (as the homepage
 * sections do in inc/woocommerce.php) would ignore all of that URL
 * state; it's the right tool for a fixed, hand-picked set of products,
 * not for "whatever the shop URL currently says to show".
 *
 * @package TechMart
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

do_action( 'woocommerce_before_main_content' );
?>

<div id="primary" class="tm-container tm-shop">
	<nav class="tm-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'techmart' ); ?>">
		<?php woocommerce_breadcrumb(); ?>
	</nav>

	<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
		<h1 class="tm-shop__title"><?php woocommerce_page_title(); ?></h1>
	<?php endif; ?>

	<?php
	/**
	 * Hook: woocommerce_archive_description.
	 * Outputs a category/tag's own description text when it has one —
	 * native WooCommerce behavior, nothing custom needed here.
	 */
	do_action( 'woocommerce_archive_description' );
	?>

	<div class="tm-shop__layout">
		<aside class="tm-shop__sidebar" id="tm-shop-sidebar" aria-label="<?php esc_attr_e( 'Product filters', 'techmart' ); ?>">
			<?php
			/**
			 * Hook: woocommerce_sidebar.
			 * Loads sidebar-shop.php, which renders the 'shop-sidebar'
			 * widget area — see that file and inc/setup.php.
			 */
			do_action( 'woocommerce_sidebar' );
			?>
		</aside>

		<div class="tm-shop__main">
			<button
				type="button"
				class="tm-filter-toggle"
				aria-expanded="false"
				aria-controls="tm-shop-sidebar"
			>
				<?php techmart_icon( 'menu' ); ?>
				<?php esc_html_e( 'Filters', 'techmart' ); ?>
			</button>

			<?php if ( woocommerce_product_loop() ) : ?>

				<div class="tm-shop__toolbar">
					<?php
					/**
					 * Hook: woocommerce_before_shop_loop.
					 * Default WooCommerce hooks here: woocommerce_result_count
					 * (priority 20) and woocommerce_catalog_ordering (priority 30).
					 * Wrapping the action call in .tm-shop__toolbar is what lets
					 * plain CSS flexbox lay those two default elements out side by
					 * side — no need to unhook and rebuild either of them.
					 */
					do_action( 'woocommerce_before_shop_loop' );
					?>
				</div>

				<?php woocommerce_product_loop_start(); ?>

				<?php
				if ( wc_get_loop_prop( 'total' ) ) {
					while ( have_posts() ) {
						the_post();

						/**
						 * Hook: woocommerce_shop_loop.
						 */
						do_action( 'woocommerce_shop_loop' );

						/**
						 * Delegates to template-parts/product/card.php —
						 * see woocommerce/content-product.php.
						 */
						wc_get_template_part( 'content', 'product' );
					}
				}
				?>

				<?php woocommerce_product_loop_end(); ?>

				<?php
				/**
				 * Hook: woocommerce_after_shop_loop.
				 * Default WooCommerce hooks woocommerce_pagination here.
				 */
				do_action( 'woocommerce_after_shop_loop' );
				?>

			<?php else : ?>
				<?php
				/**
				 * Hook: woocommerce_no_products_found.
				 * Outputs WooCommerce's own "No products were found
				 * matching your selection." notice.
				 */
				do_action( 'woocommerce_no_products_found' );
				?>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php
do_action( 'woocommerce_after_main_content' );

get_footer( 'shop' );
