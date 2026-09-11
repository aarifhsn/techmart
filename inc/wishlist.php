<?php
/**
 * Wishlist.
 *
 * A deliberate departure from the original brief (§25), which asked
 * for an integration point for a plugin like YITH rather than a
 * bespoke backend — the client later asked for a real, self-contained
 * wishlist instead, so this file is that: no plugin dependency, no new
 * database table.
 *
 * Storage: logged-in users get user meta (persists across devices).
 * Guests get WooCommerce's own session object (WC()->session) — the
 * exact mechanism WooCommerce itself uses to persist a guest's cart,
 * cookie-backed under the hood. Reusing it means no hand-rolled cookie
 * parsing, no separate expiry/security handling to get right — it's
 * already loaded, already reliable, and already doing this same job
 * for the cart.
 *
 * @package TechMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the current visitor's wishlist as an array of product IDs.
 *
 * @return int[]
 */
function techmart_get_wishlist_ids() {
	if ( is_user_logged_in() ) {
		$ids = get_user_meta( get_current_user_id(), '_techmart_wishlist', true );
		return is_array( $ids ) ? array_map( 'absint', $ids ) : array();
	}

	if ( ! function_exists( 'WC' ) || ! WC()->session ) {
		return array();
	}

	$ids = WC()->session->get( 'techmart_wishlist', array() );

	return is_array( $ids ) ? array_map( 'absint', $ids ) : array();
}

/**
 * Persist a wishlist ID array for the current visitor, deduplicated.
 *
 * @param array $ids
 */
function techmart_save_wishlist_ids( $ids ) {
	$ids = array_values( array_unique( array_map( 'absint', $ids ) ) );

	if ( is_user_logged_in() ) {
		update_user_meta( get_current_user_id(), '_techmart_wishlist', $ids );
		return;
	}

	if ( function_exists( 'WC' ) && WC()->session ) {
		WC()->session->set( 'techmart_wishlist', $ids );
	}
}

function techmart_is_in_wishlist( $product_id ) {
	return in_array( absint( $product_id ), techmart_get_wishlist_ids(), true );
}

function techmart_get_wishlist_count() {
	return count( techmart_get_wishlist_ids() );
}

/**
 * URL of the page containing the [techmart_wishlist] shortcode —
 * picked via a page-picker Customizer control (inc/customizer.php)
 * rather than assumed by slug, the same pattern WordPress core itself
 * uses for Homepage/Posts page.
 *
 * @return string
 */
function techmart_get_wishlist_url() {
	$page_id = absint( get_theme_mod( 'techmart_wishlist_page', 0 ) );

	return $page_id ? get_permalink( $page_id ) : '#';
}

/**
 * AJAX: toggle a product in or out of the current visitor's wishlist.
 * Registered for both logged-in and guest requests since the wishlist
 * works for both.
 */
function techmart_ajax_toggle_wishlist() {
	check_ajax_referer( 'techmart_wishlist', 'nonce' );

	$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;

	if ( ! $product_id || ! get_post( $product_id ) ) {
		wp_send_json_error();
	}

	$ids = techmart_get_wishlist_ids();

	if ( in_array( $product_id, $ids, true ) ) {
		$ids         = array_diff( $ids, array( $product_id ) );
		$in_wishlist = false;
	} else {
		$ids[]       = $product_id;
		$in_wishlist = true;
	}

	techmart_save_wishlist_ids( $ids );

	wp_send_json_success(
		array(
			'in_wishlist' => $in_wishlist,
			'count'       => count( $ids ),
		)
	);
}
add_action( 'wp_ajax_techmart_toggle_wishlist', 'techmart_ajax_toggle_wishlist' );
add_action( 'wp_ajax_nopriv_techmart_toggle_wishlist', 'techmart_ajax_toggle_wishlist' );

/**
 * Merge a guest's session wishlist into their account on login —
 * same convention WooCommerce itself follows for the cart, so a
 * visitor who builds a wishlist before logging in doesn't lose it.
 *
 * @param string  $user_login
 * @param WP_User $user
 */
function techmart_merge_wishlist_on_login( $user_login, $user ) {
	if ( ! function_exists( 'WC' ) || ! WC()->session ) {
		return;
	}

	$session_ids = WC()->session->get( 'techmart_wishlist', array() );

	if ( empty( $session_ids ) ) {
		return;
	}

	$user_ids = get_user_meta( $user->ID, '_techmart_wishlist', true );
	$user_ids = is_array( $user_ids ) ? $user_ids : array();

	$merged = array_values( array_unique( array_map( 'absint', array_merge( $user_ids, $session_ids ) ) ) );

	update_user_meta( $user->ID, '_techmart_wishlist', $merged );
	WC()->session->set( 'techmart_wishlist', array() );
}
add_action( 'wp_login', 'techmart_merge_wishlist_on_login', 10, 2 );

/**
 * [techmart_wishlist] shortcode — the Wishlist page's content.
 *
 * Renders through the exact same product-card component as everywhere
 * else in the theme (template-parts/product/card.php), so a saved
 * product looks and behaves identically here — including its own
 * wishlist button, which is how a card gets removed from this page.
 * The .tm-wishlist-page wrapper class is what tells app.js's toggle
 * handler to remove a card from view (rather than just un-filling its
 * heart) when it's unwished from this specific page — see
 * initWishlistToggles() in assets/js/app.js.
 */
function techmart_wishlist_shortcode() {
	$ids = techmart_get_wishlist_ids();

	ob_start();
	?>
	<div class="tm-wishlist-page">
		<p class="tm-wishlist-page__empty" <?php echo empty( $ids ) ? '' : 'hidden'; ?>>
			<?php esc_html_e( 'Your wishlist is empty. Tap the heart icon on any product to save it here.', 'techmart' ); ?>
		</p>

		<?php if ( ! empty( $ids ) ) : ?>
			<div class="tm-product-grid">
				<?php foreach ( $ids as $id ) : ?>
					<?php
					$product = wc_get_product( $id );

					if ( $product && $product->exists() ) {
						techmart_get_template_part( 'template-parts/product/card', null, array( 'product' => $product ) );
					}
					?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'techmart_wishlist', 'techmart_wishlist_shortcode' );
