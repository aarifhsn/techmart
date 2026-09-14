<?php
/**
 * Asset registration and conditional enqueueing.
 *
 * Loading strategy: base.css and components.css are the shared design
 * system and are always loaded. Everything else is scoped to the
 * templates that actually need it, so — for example — product.css never
 * ships to someone reading a blog post. This keeps per-page CSS small as
 * more sections are added in later phases, instead of one large
 * style.css growing forever.
 *
 * @package TechMart
 */

if (!defined('ABSPATH')) {
	exit;
}

function techmart_enqueue_assets()
{
	// Design tokens, reset, typography, container system. Required everywhere.
	// @font-face for the self-hosted Inter variable font lives in base.css
	// itself (Phase 10) — see the comment there for why it replaced the
	// Google Fonts CDN link this function used through Phase 9.
	wp_enqueue_style('techmart-base', TECHMART_URI . '/assets/css/base.css', array(), TECHMART_VERSION);

	// Shared components (buttons, product card, badges, section header). Phase 4+.
	wp_enqueue_style('techmart-components', TECHMART_URI . '/assets/css/components.css', array('techmart-base'), TECHMART_VERSION);

	// Header renders on every template, so its styles are unconditional.
	wp_enqueue_style('techmart-header', TECHMART_URI . '/assets/css/header.css', array('techmart-base'), TECHMART_VERSION);

	// Same reasoning as the header: the footer renders on every template too.
	wp_enqueue_style('techmart-footer', TECHMART_URI . '/assets/css/footer.css', array('techmart-base'), TECHMART_VERSION);

	// Homepage-only sections (hero, category rail, deals, brands, newsletter).
	if (is_front_page()) {
		wp_enqueue_style('techmart-home', TECHMART_URI . '/assets/css/home.css', array('techmart-base'), TECHMART_VERSION);
	}

	/**
	 * Product-card and single-product styling. Loaded on shop/category/tag
	 * archives, cart, checkout, the homepage (which embeds product cards
	 * in Trending/Best Sellers/Weekly Deals), and the Wishlist page —
	 * detected by its shortcode rather than assumed by slug, since an
	 * admin can put [techmart_wishlist] on any page they choose (see
	 * inc/customizer.php's page picker).
	 */
	$queried_post = is_singular() ? get_post() : null;
	$has_wishlist_shortcode = $queried_post && has_shortcode($queried_post->post_content, 'techmart_wishlist');

	if (function_exists('is_woocommerce') && (is_woocommerce() || is_cart() || is_checkout() || is_front_page() || $has_wishlist_shortcode)) {
		wp_enqueue_style('techmart-product', TECHMART_URI . '/assets/css/product.css', array('techmart-base'), TECHMART_VERSION);
	}

	/**
	 * Shop/category/tag archive layout (breadcrumb, title, filters
	 * sidebar, toolbar). Deliberately narrower than the is_woocommerce()
	 * check above — is_woocommerce() also covers the single product
	 * page, which has no archive layout to style (that's Phase 8).
	 */
	if (function_exists('is_shop') && (is_shop() || is_product_taxonomy())) {
		wp_enqueue_style('techmart-shop', TECHMART_URI . '/assets/css/shop.css', array('techmart-base', 'techmart-product'), TECHMART_VERSION);
	}

	// Single product page: gallery/summary layout, tabs, sticky add-to-cart bar.
	if (function_exists('is_product') && is_product()) {
		wp_enqueue_style('techmart-single-product', TECHMART_URI . '/assets/css/single-product.css', array('techmart-base', 'techmart-product'), TECHMART_VERSION);
	}

	// Cart, checkout, and account: these are ordinary WordPress pages
	// (see page.php), not WooCommerce templates, so this depends on
	// techmart-base rather than techmart-product.
	if (function_exists('is_cart') && (is_cart() || is_checkout() || is_account_page())) {
		wp_enqueue_style('techmart-cart-checkout-account', TECHMART_URI . '/assets/css/cart-checkout-account.css', array('techmart-base', 'techmart-components'), TECHMART_VERSION);
	}

	/**
	 * Breakpoint overrides load last so they can win the cascade at their
	 * breakpoints. The dependency list is built up from whichever
	 * conditional stylesheets actually got enqueued above, rather than
	 * relying on wp_enqueue_style() call order to keep responsive.css
	 * printing after them — wp_style_is() here makes that ordering an
	 * explicit, declared dependency instead of an incidental one.
	 */
	$responsive_deps = array('techmart-components', 'techmart-header', 'techmart-footer');

	foreach (array('techmart-home', 'techmart-product', 'techmart-shop', 'techmart-single-product', 'techmart-cart-checkout-account') as $conditional_style) {
		if (wp_style_is($conditional_style, 'enqueued')) {
			$responsive_deps[] = $conditional_style;
		}
	}

	wp_enqueue_style(
		'techmart-responsive',
		TECHMART_URI . '/assets/css/responsive.css',
		$responsive_deps,
		TECHMART_VERSION
	);

	/**
	 * Single JS entry point, deferred and dependency-free (no jQuery).
	 * Phase 2 adds the category-drawer and mobile-nav modules into this
	 * same file rather than adding more <script> tags — see app.js.
	 */
	wp_enqueue_script(
		'techmart-app',
		TECHMART_URI . '/assets/js/app.js',
		array(),
		TECHMART_VERSION,
		array(
			'strategy' => 'defer',
			'in_footer' => true,
		)
	);

	/**
	 * Data for app.js's wishlist toggle handler (initWishlistToggles()).
	 * The nonce is scoped to 'techmart_wishlist' and checked server-side
	 * in techmart_ajax_toggle_wishlist() (inc/wishlist.php).
	 */
	wp_localize_script(
		'techmart-app',
		'techmartData',
		array(
			'ajaxUrl' => admin_url('admin-ajax.php'),
			'wishlistNonce' => wp_create_nonce('techmart_wishlist'),
		)
	);

	/**
	 * Countdown behavior only exists in the Weekly Deals section, so —
	 * same reasoning as home.css/product.css above — it's kept out of
	 * app.js and loaded only where it's used, rather than shipping an
	 * interval timer to every page on the site.
	 */
	if (is_front_page()) {
		wp_enqueue_script(
			'techmart-deals',
			TECHMART_URI . '/assets/js/deals.js',
			array(),
			TECHMART_VERSION,
			array(
				'strategy' => 'defer',
				'in_footer' => true,
			)
		);
	}

	/**
	 * Sticky add-to-cart bar behavior only exists on the single product
	 * page — kept out of app.js for the same reason as deals.js above.
	 */
	if (function_exists('is_product') && is_product()) {
		wp_enqueue_script(
			'techmart-single-product',
			TECHMART_URI . '/assets/js/single-product.js',
			array(),
			TECHMART_VERSION,
			array(
				'strategy' => 'defer',
				'in_footer' => true,
			)
		);
	}
}
add_action('wp_enqueue_scripts', 'techmart_enqueue_assets');

/**
 * Phase 2 disabled WooCommerce's bundled stylesheets entirely
 * (`__return_empty_array`) since every WooCommerce template this theme
 * touches gets its own equivalent styling. Phase 8 narrows that: unset
 * only the specific handles this theme actually replaces
 * (woocommerce-general/-layout/-smallscreen) rather than every
 * stylesheet WooCommerce might register under this filter. A blanket
 * empty array risks silently stripping something never meant to be
 * touched — e.g. gallery-specific styling — whereas removing by known
 * handle is safer and self-documenting about exactly what's replaced.
 */
function techmart_dequeue_woocommerce_styles($styles)
{
	unset($styles['woocommerce-general']);
	unset($styles['woocommerce-layout']);
	unset($styles['woocommerce-smallscreen']);

	return $styles;
}
add_filter('woocommerce_enqueue_styles', 'techmart_dequeue_woocommerce_styles');

/**
 * WooCommerce core hooks a generic content wrapper
 * (<div id="primary" class="content-area"><main id="main" ...>) onto
 * woocommerce_before_main_content/woocommerce_after_main_content — a
 * fallback for themes with no real WooCommerce integration. This theme
 * provides complete, purpose-built wrapper markup of its own for every
 * WooCommerce template it touches (archive-product.php's .tm-shop div;
 * the single-product wrapper hooked in inc/woocommerce.php), so the
 * generic fallback is removed to avoid two extra, unstyled, purposeless
 * wrapper elements in the DOM on every WooCommerce page.
 */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

/**
 * Preload the regular (non-italic) Inter file. It's used for almost
 * all text on almost every page, so the browser would otherwise only
 * discover it after parsing base.css — a real, measurable delay to
 * first text render. The italic file isn't preloaded since it's used
 * rarely enough that a normal, non-blocking font request when it's
 * actually needed is the better trade-off.
 */
function techmart_preload_fonts()
{
	printf(
		'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
		esc_url(TECHMART_URI . '/assets/fonts/InterVariable.woff2')
	);
}
add_action('wp_head', 'techmart_preload_fonts', 1);
