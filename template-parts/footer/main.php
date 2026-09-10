<?php
/**
 * Footer main content: brand info, the Shop / Customer Service /
 * Account nav columns (from the nav menu locations registered back in
 * Phase 1), and contact/social details.
 *
 * All copy here (description, contact details, social URLs) is
 * Customizer-controlled — same pattern as the hero/promo — since it's
 * the kind of thing an admin edits directly rather than a developer.
 * The three columns are real nav menus, not a hardcoded link list, so
 * an admin manages "Shop / Customer Service / Account" the same way as
 * the primary menu: Appearance → Menus.
 *
 * @package TechMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$description = get_theme_mod( 'techmart_footer_description', '' );
$phone       = get_theme_mod( 'techmart_contact_phone', '' );
$email       = get_theme_mod( 'techmart_contact_email', '' );
$address     = get_theme_mod( 'techmart_contact_address', '' );

$socials = array_filter(
	array(
		'facebook'  => get_theme_mod( 'techmart_social_facebook', '' ),
		'twitter'   => get_theme_mod( 'techmart_social_twitter', '' ),
		'instagram' => get_theme_mod( 'techmart_social_instagram', '' ),
		'youtube'   => get_theme_mod( 'techmart_social_youtube', '' ),
	)
);
?>
<div class="tm-footer__main">
	<div class="tm-container tm-footer__grid">
		<div class="tm-footer__brand">
			<?php techmart_the_logo(); ?>

			<?php if ( $description ) : ?>
				<p class="tm-footer__description"><?php echo esc_html( $description ); ?></p>
			<?php endif; ?>

			<?php if ( $socials ) : ?>
				<ul class="tm-footer__social">
					<?php foreach ( $socials as $platform => $url ) : ?>
						<li>
							<a href="<?php echo esc_url( $url ); ?>" aria-label="<?php echo esc_attr( ucfirst( $platform ) ); ?>" target="_blank" rel="noopener noreferrer">
								<?php techmart_icon( $platform ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>

		<?php if ( has_nav_menu( 'footer-shop' ) ) : ?>
			<div class="tm-footer__column">
				<h3 class="tm-footer__heading"><?php esc_html_e( 'Shop', 'techmart' ); ?></h3>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer-shop',
						'container'      => false,
						'menu_class'     => 'tm-footer__list',
						'depth'          => 1,
					)
				);
				?>
			</div>
		<?php endif; ?>

		<?php if ( has_nav_menu( 'footer-service' ) ) : ?>
			<div class="tm-footer__column">
				<h3 class="tm-footer__heading"><?php esc_html_e( 'Customer Service', 'techmart' ); ?></h3>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer-service',
						'container'      => false,
						'menu_class'     => 'tm-footer__list',
						'depth'          => 1,
					)
				);
				?>
			</div>
		<?php endif; ?>

		<?php if ( has_nav_menu( 'footer-account' ) ) : ?>
			<div class="tm-footer__column">
				<h3 class="tm-footer__heading"><?php esc_html_e( 'Account', 'techmart' ); ?></h3>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer-account',
						'container'      => false,
						'menu_class'     => 'tm-footer__list',
						'depth'          => 1,
					)
				);
				?>
			</div>
		<?php endif; ?>

		<?php if ( $phone || $email || $address ) : ?>
			<div class="tm-footer__column">
				<h3 class="tm-footer__heading"><?php esc_html_e( 'Contact Info', 'techmart' ); ?></h3>
				<ul class="tm-footer__contact">
					<?php if ( $phone ) : ?>
						<li>
							<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>">
								<?php techmart_icon( 'phone' ); ?>
								<?php echo esc_html( $phone ); ?>
							</a>
						</li>
					<?php endif; ?>

					<?php if ( $email ) : ?>
						<li>
							<a href="mailto:<?php echo esc_attr( $email ); ?>">
								<?php techmart_icon( 'mail' ); ?>
								<?php echo esc_html( $email ); ?>
							</a>
						</li>
					<?php endif; ?>

					<?php if ( $address ) : ?>
						<li>
							<?php techmart_icon( 'map-pin' ); ?>
							<?php echo esc_html( $address ); ?>
						</li>
					<?php endif; ?>
				</ul>
			</div>
		<?php endif; ?>
	</div>
</div>
