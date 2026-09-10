<?php
/**
 * Promotion / campaign banner section.
 *
 * All content is Customizer-controlled (inc/customizer.php) — same
 * reasoning as the hero in Phase 3: this is marketing copy an admin
 * changes often, not structural content.
 *
 * @package TechMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$title = get_theme_mod( 'techmart_promo_title', '' );

if ( '' === $title ) {
	return;
}
?>
<section class="tm-band" aria-label="<?php esc_attr_e( 'Featured promotion', 'techmart' ); ?>">
	<div class="tm-container">
		<?php
		techmart_get_template_part(
			'template-parts/components/promo-banner',
			null,
			array(
				'eyebrow'     => get_theme_mod( 'techmart_promo_eyebrow', __( 'Limited Time Offer', 'techmart' ) ),
				'title'       => $title,
				'subtitle'    => get_theme_mod( 'techmart_promo_subtitle', '' ),
				'cta_text'    => get_theme_mod( 'techmart_promo_cta_text', __( 'Shop Now', 'techmart' ) ),
				'cta_url'     => get_theme_mod( 'techmart_promo_cta_url', '#' ),
				'badge_text'  => get_theme_mod( 'techmart_promo_badge_text', '' ),
				'image_id'    => absint( get_theme_mod( 'techmart_promo_image', 0 ) ),
				'bg_image_id' => absint( get_theme_mod( 'techmart_promo_bg_image', 0 ) ),
			)
		);
		?>
	</div>
</section>
