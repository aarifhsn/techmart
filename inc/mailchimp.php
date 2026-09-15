<?php
/**
 * Mailchimp newsletter integration.
 *
 * Add the following to wp-config.php (never commit the API key to the theme):
 *
 * define( 'TECHMART_MAILCHIMP_API_KEY', 'your-api-key-us21' );
 * define( 'TECHMART_MAILCHIMP_AUDIENCE_ID', 'your-audience-id' );
 *
 * @package TechMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add an email address to the configured Mailchimp audience.
 *
 * New addresses are submitted with the "pending" status, so Mailchimp sends
 * its confirmation email before adding them as subscribed. Existing members
 * retain their current status.
 *
 * @param true|WP_Error|null $result Existing provider result.
 * @param string             $email  Validated email address.
 * @return true|WP_Error|null
 */
function techmart_mailchimp_subscribe( $result, $email ) {
	if ( null !== $result || ! defined( 'TECHMART_MAILCHIMP_API_KEY' ) || ! defined( 'TECHMART_MAILCHIMP_AUDIENCE_ID' ) ) {
		return $result;
	}

	$api_key     = TECHMART_MAILCHIMP_API_KEY;
	$audience_id = TECHMART_MAILCHIMP_AUDIENCE_ID;
	$key_parts   = explode( '-', $api_key );
	$data_center = end( $key_parts );

	if ( empty( $api_key ) || empty( $audience_id ) || empty( $data_center ) ) {
		return new WP_Error( 'techmart_mailchimp_config', __( 'Mailchimp is not configured correctly.', 'techmart' ) );
	}

	$subscriber_hash = md5( strtolower( trim( $email ) ) );
	$endpoint        = sprintf(
		'https://%1$s.api.mailchimp.com/3.0/lists/%2$s/members/%3$s',
		rawurlencode( $data_center ),
		rawurlencode( $audience_id ),
		$subscriber_hash
	);

	$response = wp_remote_request(
		$endpoint,
		array(
			'method'  => 'PUT',
			'timeout' => 15,
			'headers' => array(
				'Authorization' => 'Basic ' . base64_encode( 'techmart:' . $api_key ), // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode -- HTTP Basic authentication requirement.
				'Content-Type'  => 'application/json',
			),
			'body'    => wp_json_encode(
				array(
					'email_address' => $email,
					'status_if_new' => 'pending',
				)
			),
		)
	);

	if ( is_wp_error( $response ) ) {
		return $response;
	}

	if ( 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
		return new WP_Error( 'techmart_mailchimp_request', __( 'Mailchimp could not process the subscription.', 'techmart' ) );
	}

	return true;
}
add_filter( 'techmart_newsletter_signup_result', 'techmart_mailchimp_subscribe', 10, 2 );
