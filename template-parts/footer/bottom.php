<?php
/**
 * Footer bottom bar: copyright and payment methods.
 *
 * Payment icons come from techmart_get_payment_methods()
 * (inc/woocommerce.php) — WooCommerce's actually-enabled gateways, not
 * a fixed Visa/Mastercard/PayPal image set. See that function's
 * comment for why.
 *
 * @package TechMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$payment_methods = techmart_get_payment_methods();
?>
<div class="tm-footer__bottom">
	<div class="tm-container tm-footer__bottom-inner">
		<p class="tm-footer__copyright">
			&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'techmart' ); ?>
		</p>

		<?php if ( $payment_methods ) : ?>
			<ul class="tm-footer__payments">
				<?php foreach ( $payment_methods as $method ) : ?>
					<li>
						<?php if ( $method['icon'] ) : ?>
							<?php
							echo $method['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WC_Payment_Gateway::get_icon() returns pre-built, filtered <img> markup from WooCommerce core.
							?>
						<?php else : ?>
							<span class="tm-footer__payment-name"><?php echo esc_html( $method['title'] ); ?></span>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</div>
