<?php
/**
 * Homepage section hooks.
 *
 * front-page.php only fires do_action( 'techmart_homepage' ); every
 * section below hooks into it at a specific priority. Adding, removing,
 * or reordering a homepage section from Phase 4 onward means adding or
 * changing a hook here — front-page.php itself never needs to change
 * again. This mirrors how WooCommerce composes its own archive/single
 * templates (woocommerce_before_shop_loop, woocommerce_single_product_summary,
 * etc.) instead of hardcoding section order into a template file.
 *
 * @package TechMart
 */

if (!defined('ABSPATH')) {
	exit;
}

function techmart_homepage_hero()
{
	get_template_part('template-parts/home/hero');
}
add_action('techmart_homepage', 'techmart_homepage_hero', 10);

function techmart_homepage_categories()
{
	get_template_part('template-parts/home/categories');
}
add_action('techmart_homepage', 'techmart_homepage_categories', 20);

function techmart_homepage_trending()
{
	techmart_get_template_part(
		'template-parts/home/product-section',
		null,
		array(
			'section_class' => 'tm-trending',
			'aria_label' => __('Trending products', 'techmart'),
			'title' => __('Trending Products', 'techmart'),
			'link_url' => add_query_arg( 'techmart_collection', 'trending', wc_get_page_permalink( 'shop' ) ),
			'link_text' => __('View All Products', 'techmart'),
			'products' => techmart_get_trending_products(8),
		)
	);
}
add_action('techmart_homepage', 'techmart_homepage_trending', 30);

function techmart_homepage_best_sellers()
{
	techmart_get_template_part(
		'template-parts/home/product-section',
		null,
		array(
			'section_class' => 'tm-best-sellers',
			'aria_label' => __('Best sellers', 'techmart'),
			'title' => __('Best Sellers', 'techmart'),
			'link_url' => add_query_arg('orderby', 'popularity', wc_get_page_permalink('shop')),
			'link_text' => __('View All', 'techmart'),
			'products' => techmart_get_best_selling_products(8),
		)
	);
}
add_action('techmart_homepage', 'techmart_homepage_best_sellers', 50);

function techmart_homepage_weekly_deals()
{
	get_template_part('template-parts/home/weekly-deals');
}
add_action('techmart_homepage', 'techmart_homepage_weekly_deals', 70);

function techmart_homepage_promotion()
{
	get_template_part('template-parts/home/promotion');
}
add_action('techmart_homepage', 'techmart_homepage_promotion', 40);

function techmart_homepage_why_shop()
{
	get_template_part('template-parts/home/why-shop');
}
add_action('techmart_homepage', 'techmart_homepage_why_shop', 60);

function techmart_homepage_brands()
{
	get_template_part('template-parts/home/brands');
}
add_action('techmart_homepage', 'techmart_homepage_brands', 80);

function techmart_homepage_newsletter()
{
	get_template_part('template-parts/home/newsletter');
}
add_action('techmart_homepage', 'techmart_homepage_newsletter', 90);
