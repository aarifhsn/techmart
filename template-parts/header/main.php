<?php
/**
 * Main header row: logo, category toggle, search, wishlist, cart, account.
 *
 * The category toggle button here only controls the drawer's open/
 * closed state (aria-expanded + app.js); the drawer's own markup is
 * rendered once, immediately after this template part, by header.php.
 * The cart link works the same way for its mini-cart dropdown — see
 * template-parts/header/mini-cart.php and initMiniCart() in app.js.
 *
 * @package TechMart
 */

if (!defined('ABSPATH')) {
	exit;
}

$cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : '#';
$cart_count = (function_exists('WC') && WC()->cart) ? WC()->cart->get_cart_contents_count() : 0;
$account_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : wp_login_url();
?>
<div class="tm-header__inner tm-container">
	<div class="tm-header__logo">
		<?php techmart_the_logo(); ?>
	</div>

	<?php get_template_part('template-parts/header/search-form'); ?>

	<div class="tm-header__utility">
		<a href="<?php echo esc_url(techmart_get_wishlist_url()); ?>"
			class="tm-header__utility-link tm-wishlist-toggle">
			<?php techmart_icon('heart'); ?>
			<span class="tm-header__utility-label"><?php esc_html_e('Wishlist', 'techmart'); ?></span>
			<span class="tm-badge-count tm-wishlist-count"><?php echo esc_html(techmart_get_wishlist_count()); ?></span>
		</a>

		<div class="tm-header__cart-wrapper">
			<a href="<?php echo esc_url($cart_url); ?>" class="tm-header__utility-link tm-header__cart-toggle"
				aria-haspopup="true" aria-expanded="false" aria-controls="tm-mini-cart">
				<?php techmart_icon('cart'); ?>
				<span class="tm-header__utility-label"><?php esc_html_e('Cart', 'techmart'); ?></span>
				<span class="tm-badge-count tm-cart-count"><?php echo esc_html($cart_count); ?></span>
			</a>

			<?php get_template_part('template-parts/header/mini-cart'); ?>
		</div>

		<a href="<?php echo esc_url($account_url); ?>" class="tm-header__utility-link">
			<?php techmart_icon('user'); ?>

			<span class="tm-header__utility-label">
				<?php if (is_user_logged_in()): ?>

					<?php
					$current_user = wp_get_current_user();
					$display_name = $current_user->display_name ?: $current_user->user_login;
					?>

					<span>
						<?php
						printf(
							/* translators: %s: customer name */
							esc_html__('Hello, %s', 'techmart'),
							esc_html($display_name)
						);
						?>
					</span><br>

					<strong><?php esc_html_e('My Account', 'techmart'); ?></strong>

				<?php else: ?>

					<span><?php esc_html_e('Hello,', 'techmart'); ?></span><br>
					<strong><?php esc_html_e('Sign In', 'techmart'); ?></strong>

				<?php endif; ?>
			</span>
		</a>
	</div>
</div>