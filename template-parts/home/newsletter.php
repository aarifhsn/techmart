<?php
/**
 * Newsletter signup.
 *
 * The form is fully real — nonce-protected, posts to admin-post.php,
 * and is handled by techmart_handle_newsletter_signup() in
 * inc/newsletter.php — but that handler does not subscribe anyone to
 * anything by default. Per §19, this theme does not fake a newsletter
 * backend; see inc/newsletter.php for the documented action a real
 * provider (Mailchimp, Fluent Forms, a custom REST call) hooks into,
 * and for why the status message below is honest about whether that
 * hook exists yet.
 *
 * @package TechMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$status = isset( $_GET['newsletter'] ) ? sanitize_key( wp_unslash( $_GET['newsletter'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only GET flag used only to select which status message to display.

$messages = array(
	'success'      => __( 'Thanks — you are subscribed!', 'techmart' ),
	'unconfigured' => __( 'Signup received, but no newsletter provider is connected yet.', 'techmart' ),
	'invalid'      => __( 'Please enter a valid email address.', 'techmart' ),
	'error'        => __( 'Something went wrong — please try again.', 'techmart' ),
);
?>
<section class="tm-newsletter tm-band" aria-label="<?php esc_attr_e( 'Newsletter signup', 'techmart' ); ?>">
	<div class="tm-container tm-newsletter__inner">
		<div class="tm-newsletter__content">
			<h2 class="tm-newsletter__title"><?php esc_html_e( 'Subscribe to our newsletter', 'techmart' ); ?></h2>
			<p class="tm-newsletter__description"><?php esc_html_e( 'Get the latest updates on new products and upcoming sales', 'techmart' ); ?></p>
		</div>

		<form class="tm-newsletter__form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="techmart_newsletter_signup">
			<?php wp_nonce_field( 'techmart_newsletter_signup', 'techmart_newsletter_nonce' ); ?>

			<label class="screen-reader-text" for="tm-newsletter-email"><?php esc_html_e( 'Email address', 'techmart' ); ?></label>
			<input
				type="email"
				id="tm-newsletter-email"
				name="email"
				required
				placeholder="<?php esc_attr_e( 'Enter your email address', 'techmart' ); ?>"
				class="tm-newsletter__input"
			>

			<button type="submit" class="tm-button tm-newsletter__submit">
				<?php esc_html_e( 'Subscribe', 'techmart' ); ?>
			</button>
		</form>

		<?php if ( isset( $messages[ $status ] ) ) : ?>
			<p class="tm-newsletter__notice tm-newsletter__notice--<?php echo esc_attr( $status ); ?>" role="status">
				<?php echo esc_html( $messages[ $status ] ); ?>
			</p>
		<?php endif; ?>
	</div>
</section>
