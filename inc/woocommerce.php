<?php
/**
 * WooCommerce integration.
 *
 * Phase 2: search-by-category, cache-safe cart count, wishlist
 * integration point. Phase 4 adds this file's biggest piece — the
 * product queries and badge logic shared by every homepage product
 * section (Trending Products, Best Sellers, Weekly Deals).
 *
 * @package TechMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter search results by product_cat when the header search form's
 * category selector is used. WordPress core search has no taxonomy
 * awareness on its own — this is the one place that connects our
 * custom search form (template-parts/header/search-form.php) to a
 * real, filtered query.
 *
 * @param WP_Query $query The main query, passed by reference via the hook.
 */
function techmart_filter_search_by_category( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( ! $query->is_search() || empty( $_GET['product_cat'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only GET filter, no state change.
		return;
	}

	$query->set( 'post_type', 'product' );
	$query->set(
		'tax_query', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- single top-level taxonomy filter on a user-facing search, not a repeated/expensive query.
		array(
			array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => sanitize_title( wp_unslash( $_GET['product_cat'] ) ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only GET filter, no state change.
			),
		)
	);
}
add_action( 'pre_get_posts', 'techmart_filter_search_by_category' );

/**
 * Keep the header cart UI correct under full-page caching.
 *
 * WooCommerce enqueues its own cart-fragments script automatically (no
 * action needed here); it re-fetches registered fragments via AJAX
 * after every add-to-cart and swaps in any element matching a fragment
 * selector. This is the standard WooCommerce pattern for anything
 * cart-related in a header that's otherwise cacheable.
 *
 * Named generically (not techmart_cart_count_fragment, its Phase 2
 * name) since Phase 9 adds a second fragment here — the mini-cart
 * dropdown — rather than the header badge alone. WooCommerce's own
 * Cart widget registers a near-identical fragment for its own markup;
 * this does the same thing directly, since the mini-cart lives in the
 * header rather than a widget area.
 *
 * @param array $fragments Existing fragment selectors => HTML.
 * @return array
 */
function techmart_cart_fragments( $fragments ) {
	$count = ( function_exists( 'WC' ) && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;

	ob_start();
	?>
	<span class="tm-badge-count tm-cart-count"><?php echo esc_html( $count ); ?></span>
	<?php
	$fragments['.tm-cart-count'] = ob_get_clean();

	ob_start();
	woocommerce_mini_cart();
	$fragments['div.widget_shopping_cart_content'] = ob_get_clean();

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'techmart_cart_fragments' );

/**
 * Wishlist integration point.
 *
 * WooCommerce has no native wishlist. Per the brief (§25), the theme
 * does not invent a bespoke database-backed wishlist — instead it
 * exposes two filterable functions that a wishlist plugin (e.g. YITH
 * WooCommerce Wishlist) can hook into to report real data. Until such
 * a plugin is active, the count reads 0 and the link is inert ('#').
 *
 * A plugin (or a future lightweight custom implementation) integrates
 * by hooking these two filters — no template changes required:
 *
 *   add_filter( 'techmart_wishlist_count', function () { ... } );
 *   add_filter( 'techmart_wishlist_url', function () { ... } );
 *
 * The .tm-wishlist-toggle class on the header link (template-parts/
 * header/main.php) is reserved for that plugin's own JS to bind to.
 */
function techmart_get_wishlist_count() {
	return (int) apply_filters( 'techmart_wishlist_count', 0 );
}

function techmart_get_wishlist_url() {
	return apply_filters( 'techmart_wishlist_url', '#' );
}

/**
 * Trending Products query.
 *
 * "Trending" means: WooCommerce's native Featured Products (toggled per
 * product in wp-admin, zero custom UI needed — §31 explicitly warns
 * against building an unnecessary admin panel) if any are marked, with
 * the most recent products used to fill out the row when there aren't
 * enough featured items to avoid a visibly short/lopsided grid. Falls
 * back to plain recency if nothing is marked featured yet.
 *
 * @param int $limit Number of products to return.
 * @return WC_Product[]
 */
function techmart_get_trending_products( $limit = 8 ) {
	$featured_ids = wc_get_featured_product_ids();

	$args = array(
		'status' => 'publish',
		'limit'  => $limit,
	);

	if ( ! empty( $featured_ids ) ) {
		$args['include'] = $featured_ids;
	} else {
		$args['orderby'] = 'date';
		$args['order']   = 'DESC';
	}

	$args     = apply_filters( 'techmart_trending_products_args', $args );
	$products = wc_get_products( $args );

	if ( ! empty( $featured_ids ) && count( $products ) < $limit ) {
		$fill_args = apply_filters(
			'techmart_trending_products_fill_args',
			array(
				'status'  => 'publish',
				'limit'   => $limit - count( $products ),
				'exclude' => $featured_ids,
				'orderby' => 'date',
				'order'   => 'DESC',
			)
		);

		$products = array_merge( $products, wc_get_products( $fill_args ) );
	}

	return $products;
}

/**
 * Best Sellers query.
 *
 * orderby => 'popularity' is WooCommerce's own mapping to the
 * _total_sales meta it already maintains on every order — this is the
 * "WooCommerce-aware" query the brief asks for (§14), not a manual
 * count reconstructed from order line items.
 *
 * @param int $limit Number of products to return.
 * @return WC_Product[]
 */
function techmart_get_best_selling_products( $limit = 8 ) {
	$args = apply_filters(
		'techmart_best_selling_products_args',
		array(
			'status'  => 'publish',
			'limit'   => $limit,
			'orderby' => 'popularity',
		)
	);

	return wc_get_products( $args );
}

/**
 * Weekly Deals query.
 *
 * Only products that are (a) currently on sale — via
 * wc_get_product_ids_on_sale(), WooCommerce's own cached lookup, not a
 * custom price comparison — AND (b) have a scheduled sale end date set
 * (WooCommerce's native "Sale price dates" field on the product,
 * stored as the _sale_price_dates_to meta) qualify as a "deal". That
 * second condition is what makes the countdown in
 * template-parts/home/weekly-deals.php always real: a product on an
 * open-ended sale has no honest expiry to count down to, so it simply
 * doesn't appear in this section (§15 explicitly forbids a fake or
 * guessed countdown). WooCommerce's own wc_scheduled_sales cron already
 * clears the sale price once _sale_price_dates_to passes, so this query
 * never needs to check "is the date still in the future" itself.
 *
 * Sorted soonest-expiring first — see the section-level countdown in
 * weekly-deals.php, which counts down to $products[0]'s expiry.
 *
 * @param int $limit Number of products to return.
 * @return WC_Product[]
 */
function techmart_get_weekly_deal_products( $limit = 5 ) {
	$on_sale_ids = wc_get_product_ids_on_sale();

	if ( empty( $on_sale_ids ) ) {
		return array();
	}

	$args = apply_filters(
		'techmart_weekly_deals_args',
		array(
			'status'     => 'publish',
			'include'    => $on_sale_ids,
			'limit'      => -1,
			'orderby'    => 'meta_value_num',
			'meta_key'   => '_sale_price_dates_to', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'order'      => 'ASC',
			'meta_query' => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'     => '_sale_price_dates_to',
					'value'   => 0,
					'compare' => '>',
					'type'    => 'NUMERIC',
				),
			),
		)
	);

	$products = wc_get_products( $args );

	return array_slice( $products, 0, $limit );
}

/**
 * Compute badges for a single product: sale %, "New", "Top Rated".
 *
 * Deliberately excludes an automatic "Best Seller" badge on every
 * product card, even though §12 lists it as an example. Flagging that
 * would mean an extra popularity comparison per card render just to
 * answer "is this in the top N globally" — real N+1-query-shaped cost
 * for a label the Best Sellers section itself already communicates by
 * context. The three badges below all read data already loaded on the
 * $product object, so they cost nothing extra per card.
 *
 * Thresholds are filterable rather than hardcoded, since "new" and
 * "top rated" are inherently store-specific judgment calls.
 *
 * @param WC_Product $product
 * @return array List of { 'type' => string, 'label' => string }.
 */
function techmart_get_product_badges( $product ) {
	$badges = array();

	if ( $product->is_on_sale() ) {
		$percent  = techmart_get_sale_percentage( $product );
		$badges[] = array(
			'type'  => 'sale',
			'label' => $percent ? '-' . $percent . '%' : __( 'Sale', 'techmart' ),
		);
	}

	$new_days = (int) apply_filters( 'techmart_new_badge_days', 30 );
	$created  = $product->get_date_created();

	if ( $created && ( time() - $created->getTimestamp() ) <= ( $new_days * DAY_IN_SECONDS ) ) {
		$badges[] = array(
			'type'  => 'new',
			'label' => __( 'New', 'techmart' ),
		);
	}

	$min_rating = (float) apply_filters( 'techmart_top_rated_min_average', 4.5 );
	$min_count  = (int) apply_filters( 'techmart_top_rated_min_count', 5 );

	if ( $product->get_average_rating() >= $min_rating && $product->get_rating_count() >= $min_count ) {
		$badges[] = array(
			'type'  => 'top-rated',
			'label' => __( 'Top Rated', 'techmart' ),
		);
	}

	return $badges;
}

/**
 * A single, honest discount percentage — or 0 if one would be misleading.
 *
 * For a simple product this is unambiguous. For a variable product it's
 * only computed when every variation shares the same regular price
 * (get_variation_regular_price('min') === ('max')); otherwise a single
 * "-X%" would misrepresent variations that are discounted by a
 * different amount, so the badge falls back to a plain "Sale" label
 * instead (see techmart_get_product_badges() above) rather than
 * guessing.
 *
 * @param WC_Product $product
 * @return int Whole-number percentage, or 0 if not computable.
 */
function techmart_get_sale_percentage( $product ) {
	$regular = (float) $product->get_regular_price();
	$sale    = (float) $product->get_sale_price();

	if ( $product->is_type( 'variable' ) ) {
		$min_regular = (float) $product->get_variation_regular_price( 'min' );
		$max_regular = (float) $product->get_variation_regular_price( 'max' );

		if ( $min_regular !== $max_regular ) {
			return 0;
		}

		$regular = $min_regular;
		$sale    = (float) $product->get_variation_sale_price( 'min' );
	}

	if ( $regular <= 0 || $sale <= 0 || $sale >= $regular ) {
		return 0;
	}

	return (int) round( ( ( $regular - $sale ) / $regular ) * 100 );
}

/**
 * Brand data source for the Top Brands section.
 *
 * Per §18, brands are "initially manually configured" but the
 * architecture must not lock in that choice. This function is the
 * single seam: it currently returns whatever the 'techmart_brands'
 * filter provides (empty by default — see the note below), each item
 * shaped as:
 *
 *   array( 'name' => string, 'logo_id' => int attachment ID, 'url' => string )
 *
 * Upgrading to a real data source later — a `product_brand` taxonomy,
 * a "Brands" custom post type, or a WooCommerce product attribute —
 * means replacing only the body of this function with a
 * get_terms()/get_posts()/wc_get_attribute() call that returns the
 * same shape. template-parts/home/brands.php and template-parts/
 * components/brand-item.php never need to change.
 *
 * Returns empty by default rather than shipping placeholder brand
 * names — inventing fake brands to make the section look populated
 * would be exactly the "generic AI-generated" filler §42 warns
 * against. Add brands with, e.g.:
 *
 *   add_filter( 'techmart_brands', function () {
 *       return array(
 *           array( 'name' => 'Apple', 'logo_id' => 123, 'url' => '#' ),
 *       );
 *   } );
 *
 * @return array
 */
function techmart_get_brands() {
	return apply_filters( 'techmart_brands', array() );
}

/**
 * Payment methods shown in the footer.
 *
 * Reads WooCommerce's actually-enabled gateways
 * (WC_Payment_Gateways::get_available_payment_gateways()) rather than a
 * hardcoded Visa/Mastercard/PayPal/Apple Pay image set — a store that
 * doesn't accept one of those, or accepts something not in that fixed
 * list (bKash, local bank transfer, COD), would otherwise show
 * inaccurate icons. Each gateway's get_icon() returns whatever icon
 * markup is configured in WooCommerce → Settings → Payments for that
 * gateway; falls back to the gateway's plain title when no icon is set.
 *
 * @return array List of { 'title' => string, 'icon' => string (HTML, may be empty) }.
 */
function techmart_get_payment_methods() {
	if ( ! function_exists( 'WC' ) || ! WC()->payment_gateways() ) {
		return array();
	}

	$methods = array();

	foreach ( WC()->payment_gateways()->get_available_payment_gateways() as $gateway ) {
		$methods[] = array(
			'title' => $gateway->get_title(),
			'icon'  => $gateway->get_icon(),
		);
	}

	return $methods;
}

/**
 * Single product page.
 *
 * Everything below is a hook or filter — no single-product.php or
 * content-single-product.php override exists. WooCommerce's own hook
 * cascade on woocommerce_single_product_summary already produces a
 * correct title, rating, price, excerpt, add-to-cart (with working
 * variations), and meta; woocommerce_after_single_product_summary
 * already produces correct tabs, upsells, and related products. None
 * of that needs reimplementing — only breadcrumbs, a wishlist button,
 * a shipping/returns tab, and the sticky mobile bar are genuinely
 * missing, so those are the only five things added here.
 */

/**
 * Opens the single-product content wrapper and prints the breadcrumb.
 * Replaces the generic wrapper this theme removes in inc/enqueue.php
 * (see techmart_dequeue_woocommerce_styles()'s neighboring remove_action
 * calls) with one that actually matches this theme's container system.
 */
function techmart_single_product_wrapper_start() {
	if ( ! is_product() ) {
		return;
	}
	?>
	<div id="primary" class="tm-container tm-single-product">
		<nav class="tm-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'techmart' ); ?>">
			<?php woocommerce_breadcrumb(); ?>
		</nav>
	<?php
}
add_action( 'woocommerce_before_main_content', 'techmart_single_product_wrapper_start' );

function techmart_single_product_wrapper_end() {
	if ( ! is_product() ) {
		return;
	}

	echo '</div>';
}
add_action( 'woocommerce_after_main_content', 'techmart_single_product_wrapper_end' );

/**
 * Wraps the native add-to-cart hook output in an element with a stable
 * ID, purely so the sticky bar (template-parts/product/sticky-bar.php)
 * has something real to scroll back to. Priorities 29/31 sandwich
 * woocommerce_template_single_add_to_cart at its default priority 30
 * without touching that function itself.
 */
function techmart_single_add_to_cart_wrap_start() {
	echo '<div id="tm-add-to-cart">';
}
add_action( 'woocommerce_single_product_summary', 'techmart_single_add_to_cart_wrap_start', 29 );

function techmart_single_add_to_cart_wrap_end() {
	echo '</div>';
}
add_action( 'woocommerce_single_product_summary', 'techmart_single_add_to_cart_wrap_end', 31 );

/**
 * Wishlist button, placed right after add-to-cart (priority 30) and
 * before product meta (priority 40). Same documented integration point
 * as the product card — see the wishlist functions above.
 */
function techmart_single_product_wishlist_button() {
	global $product;

	if ( ! $product instanceof WC_Product ) {
		return;
	}
	?>
	<button
		type="button"
		class="tm-wishlist-toggle tm-single-product__wishlist tm-button tm-button--secondary"
		data-product-id="<?php echo esc_attr( $product->get_id() ); ?>"
		aria-pressed="false"
	>
		<?php techmart_icon( 'heart' ); ?>
		<?php esc_html_e( 'Add to Wishlist', 'techmart' ); ?>
	</button>
	<?php
}
add_action( 'woocommerce_single_product_summary', 'techmart_single_product_wishlist_button', 35 );

/**
 * Shipping & Returns tab.
 *
 * §22 lists shipping/returns information as required content, but
 * WooCommerce has no native tab for it. woocommerce_product_tabs is the
 * documented, standard way to add one — this adds a tab, it doesn't
 * touch how the existing description/reviews/additional-information
 * tabs are built or styled.
 */
function techmart_add_shipping_tab( $tabs ) {
	$tabs['techmart_shipping'] = array(
		'title'    => __( 'Shipping & Returns', 'techmart' ),
		'priority' => 25,
		'callback' => 'techmart_shipping_tab_content',
	);

	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'techmart_add_shipping_tab' );

function techmart_shipping_tab_content() {
	/**
	 * Static, filterable copy rather than a Customizer field — this is
	 * boilerplate policy text most stores write once and rarely change;
	 * a filter is the appropriate amount of configurability for that,
	 * same reasoning as techmart_trust_items in Phase 5.
	 */
	$content = apply_filters(
		'techmart_shipping_tab_content',
		sprintf(
			'<p>%s</p><p>%s</p>',
			esc_html__( 'Orders are processed within 1–2 business days and shipped via our standard carrier. You will receive a tracking number by email once your order ships.', 'techmart' ),
			esc_html__( 'Items can be returned within 30 days of delivery in their original condition. See our full Returns & Refunds policy for details.', 'techmart' )
		)
	);

	echo wp_kses_post( $content );
}

/**
 * Sticky mobile add-to-cart bar. Hooked after the whole product wrapper
 * closes since it's a fixed-position overlay, not in-flow content.
 */
function techmart_single_product_sticky_bar() {
	global $product;

	if ( ! $product instanceof WC_Product ) {
		return;
	}

	techmart_get_template_part( 'template-parts/product/sticky-bar', null, array( 'product' => $product ) );
}
add_action( 'woocommerce_after_single_product', 'techmart_single_product_sticky_bar' );
