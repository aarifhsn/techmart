<?php
/**
 * Theme setup: add_theme_support(), nav menus, image sizes, content width,
 * widget areas.
 *
 * @package TechMart
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Register theme supports and navigation menus.
 */
function techmart_setup()
{
	load_theme_textdomain('techmart', TECHMART_DIR . '/languages');

	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_theme_support('responsive-embeds');
	add_theme_support('automatic-feed-links');
	add_theme_support('customize-selective-refresh-widgets');

	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
			'navigation-widgets',
		)
	);

	add_theme_support(
		'custom-logo',
		array(
			'height' => 60,
			'width' => 180,
			'flex-height' => true,
			'flex-width' => true,
		)
	);

	/**
	 * WooCommerce support. `wc-product-gallery-*` enables WooCommerce's
	 * built-in gallery zoom/lightbox/slider JS on the single product page —
	 * we get that behavior for free without writing our own gallery script.
	 */
	add_theme_support('woocommerce');
	add_theme_support('wc-product-gallery-zoom');
	add_theme_support('wc-product-gallery-lightbox');
	add_theme_support('wc-product-gallery-slider');

	/**
	 * Load base.css in the block editor too, so any editor-rendered content
	 * (blog posts, static pages) uses the same type scale and colors as the
	 * rest of the site instead of the default editor styles.
	 */
	add_theme_support('editor-styles');
	add_editor_style('assets/css/base.css');

	/**
	 * 'category-nav' is a fallback only. The category drawer's primary
	 * data source is get_terms( 'product_cat' ) — see template-parts/
	 * header/category-drawer.php — so this menu is used solely when no
	 * WooCommerce categories exist yet (e.g. right after install).
	 */
	register_nav_menus(
		array(
			'primary' => __('Primary Navigation', 'techmart'),
			'category-nav' => __('Category Drawer Navigation (fallback only)', 'techmart'),
			'announcement-utility' => __('Announcement Bar — Utility Links', 'techmart'),
			'footer-shop' => __('Footer — Shop', 'techmart'),
			'footer-service' => __('Footer — Customer Service', 'techmart'),
			'footer-account' => __('Footer — Account', 'techmart'),
			'support-pages' => __('Support Pages (Info Page related-topics)', 'techmart'),
		)
	);

	/**
	 * Custom image sizes for components introduced from Phase 3 onward.
	 * Registered now so no theme code ever passes raw pixel dimensions to
	 * wp_get_attachment_image() — every size used on the front end has a
	 * name and a single source of truth here.
	 */
	add_image_size('techmart-product-card', 400, 400, true);
	add_image_size('techmart-category-thumb', 160, 160, true);
	add_image_size('techmart-hero', 1200, 1200, false);
}
add_action('after_setup_theme', 'techmart_setup');

/**
 * Set $content_width for embeds/oEmbeds that respect it (e.g. full-size
 * image and video embeds in post content).
 */
function techmart_content_width()
{
	$GLOBALS['content_width'] = apply_filters('techmart_content_width', 1200);
}
add_action('after_setup_theme', 'techmart_content_width', 0);

/**
 * Footer widget area. A single sidebar for Phase 1 — the footer's
 * Shop / Customer Service / Account columns (Phase 6) are rendered from
 * registered nav menus, not widgets, since their content is structural
 * navigation rather than admin-arranged widgets.
 *
 * Shop Sidebar (Phase 7): intended for WooCommerce's own native filter
 * widgets, not custom theme content — see sidebar-shop.php.
 */
function techmart_widgets_init()
{
	register_sidebar(
		array(
			'name' => __('Footer Widget Area', 'techmart'),
			'id' => 'footer-1',
			'description' => __('Optional widgets shown in the footer, below the main footer columns.', 'techmart'),
			'before_widget' => '<div class="tm-footer__widget">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="tm-footer__widget-title">',
			'after_title' => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name' => __('Shop Sidebar', 'techmart'),
			'id' => 'shop-sidebar',
			'description' => __('Shown on the shop and category/tag archives. Add WooCommerce\'s native "Filter Products by Price", "Filter Products by Attribute", "Product Categories", and "Active Product Filters" widgets here.', 'techmart'),
			'before_widget' => '<div class="tm-shop-sidebar__widget">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="tm-shop-sidebar__widget-title">',
			'after_title' => '</h3>',
		)
	);
}
add_action('widgets_init', 'techmart_widgets_init');
