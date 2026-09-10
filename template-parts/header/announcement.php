<?php
/**
 * Announcement bar.
 *
 * Full-bleed background band with content constrained to .tm-container.
 * Left: promotional text, editable via Customizer (inc/customizer.php)
 * since it changes more often than most theme copy. Right: utility
 * links (Track Order / Help Center / Sell on TechMart), rendered from
 * the 'announcement-utility' nav menu location so an admin manages them
 * the same way as the footer columns — no code changes needed either way.
 *
 * @package TechMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$announcement_text = get_theme_mod( 'techmart_announcement_text', __( 'Special Offer! Get 10% OFF for your first order.', 'techmart' ) );
$announcement_code = get_theme_mod( 'techmart_announcement_code', 'FIRST10' );
?>
<div class="tm-announcement tm-band">
	<div class="tm-container tm-announcement__inner">
		<p class="tm-announcement__text">
			<?php echo esc_html( $announcement_text ); ?>
			<?php if ( $announcement_code ) : ?>
				<span class="tm-announcement__code">
					<?php esc_html_e( 'Use code:', 'techmart' ); ?> <strong><?php echo esc_html( $announcement_code ); ?></strong>
				</span>
			<?php endif; ?>
		</p>

		<?php if ( has_nav_menu( 'announcement-utility' ) ) : ?>
			<nav class="tm-announcement__links" aria-label="<?php esc_attr_e( 'Utility links', 'techmart' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'announcement-utility',
						'container'      => false,
						'menu_class'     => 'tm-announcement__list',
						'depth'          => 1,
					)
				);
				?>
			</nav>
		<?php endif; ?>
	</div>
</div>
