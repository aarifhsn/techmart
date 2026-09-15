<?php
/**
 * Newsletter signup handler.
 *
 * A standalone file rather than another function in inc/woocommerce.php
 * or inc/template-hooks.php — this isn't WooCommerce-specific and isn't
 * a template hook, it's its own small integration surface, which is
 * exactly the case for giving something its own inc/ file (see
 * functions.php's file-by-responsibility structure from Phase 1).
 *
 * @package TechMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle the newsletter form POST from template-parts/home/newsletter.php.
 *
 * Verifies the nonce and the email address, then fires
 * 'techmart_newsletter_signup' — but does NOT subscribe the address to
 * anything itself. Per §19, this theme does not build a fake email
 * integration. Connect a real provider with:
 *
 *   add_action( 'techmart_newsletter_signup', function ( $email ) {
 *       // e.g. call Mailchimp's API, or hand off to Fluent Forms,
 *       // or POST to a custom REST endpoint.
 *   } );
 *
 * Until something is hooked, the redirect status is honestly
 * "unconfigured" rather than a fake "success".
 */
function techmart_handle_newsletter_signup() {
	$redirect = wp_get_referer() ? wp_get_referer() : home_url( '/' );

	if ( ! isset( $_POST['techmart_newsletter_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['techmart_newsletter_nonce'] ) ), 'techmart_newsletter_signup' ) ) {
		wp_safe_redirect( add_query_arg( 'newsletter', 'error', $redirect ) );
		exit;
	}

	$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';

	if ( ! is_email( $email ) ) {
		wp_safe_redirect( add_query_arg( 'newsletter', 'invalid', $redirect ) );
		exit;
	}

	/**
	 * Providers can return true on success, a WP_Error when their API rejects
	 * the request, or null when they are not configured. The existing action is
	 * still fired below for backwards-compatible third-party integrations.
	 */
	$result = apply_filters( 'techmart_newsletter_signup_result', null, $email );

	if ( is_wp_error( $result ) ) {
		$status = 'error';
	} elseif ( true === $result || has_action( 'techmart_newsletter_signup' ) ) {
		$status = 'success';
	} else {
		$status = 'unconfigured';
	}

	do_action( 'techmart_newsletter_signup', $email );

	wp_safe_redirect( add_query_arg( 'newsletter', $status, $redirect ) );
	exit;
}
add_action( 'admin_post_techmart_newsletter_signup', 'techmart_handle_newsletter_signup' );
add_action( 'admin_post_nopriv_techmart_newsletter_signup', 'techmart_handle_newsletter_signup' );
