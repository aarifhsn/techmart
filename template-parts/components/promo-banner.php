<?php
/**
 * Reusable promotional/campaign banner.
 *
 * Currently used once (the homepage Promotion section), but built as a
 * documented component per §16 ("do not duplicate banner markup") so a
 * category-specific or seasonal banner elsewhere later reuses this
 * instead of a new one-off block of HTML.
 *
 * The background image (if set) is rendered as a real <img> via
 * wp_get_attachment_image() rather than a CSS background-image, for the
 * same reason as the hero: responsive srcset instead of one fixed file
 * shipped to every screen size.
 *
 * @package TechMart
 *
 * @var array $args {
 *     @type string $eyebrow    Optional small label above the title.
 *     @type string $title      Required.
 *     @type string $subtitle   Optional supporting line.
 *     @type string $cta_text   Optional CTA label.
 *     @type string $cta_url    Optional CTA URL.
 *     @type string $badge_text Optional short badge, e.g. "Save up to 40%".
 *     @type int    $image_id   Optional product/feature image attachment ID.
 *     @type int    $bg_image_id Optional background photo attachment ID.
 *                                Falls back to a solid brand background if omitted.
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args = wp_parse_args(
	$args,
	array(
		'eyebrow'     => '',
		'title'       => '',
		'subtitle'    => '',
		'cta_text'    => '',
		'cta_url'     => '#',
		'badge_text'  => '',
		'image_id'    => 0,
		'bg_image_id' => 0,
	)
);

if ( '' === $args['title'] ) {
	return;
}
?>
<div class="tm-promo <?php echo $args['bg_image_id'] ? 'tm-promo--has-bg-image' : ''; ?>">
	<?php if ( $args['bg_image_id'] ) : ?>
		<div class="tm-promo__bg">
			<?php echo wp_get_attachment_image( $args['bg_image_id'], 'techmart-hero', false, array( 'class' => 'tm-promo__bg-image', 'alt' => '' ) ); ?>
			<span class="tm-promo__overlay" aria-hidden="true"></span>
		</div>
	<?php endif; ?>

	<div class="tm-promo__inner">
		<div class="tm-promo__content">
			<?php if ( $args['eyebrow'] ) : ?>
				<p class="tm-label tm-promo__eyebrow"><?php echo esc_html( $args['eyebrow'] ); ?></p>
			<?php endif; ?>

			<h2 class="tm-promo__title"><?php echo esc_html( $args['title'] ); ?></h2>

			<?php if ( $args['subtitle'] ) : ?>
				<p class="tm-promo__subtitle"><?php echo esc_html( $args['subtitle'] ); ?></p>
			<?php endif; ?>

			<?php if ( $args['cta_text'] ) : ?>
				<?php
				techmart_button(
					array(
						'text'    => $args['cta_text'],
						'url'     => $args['cta_url'],
						'variant' => 'secondary',
						'class'   => 'tm-promo__cta',
					)
				);
				?>
			<?php endif; ?>
		</div>

		<?php if ( $args['image_id'] ) : ?>
			<div class="tm-promo__media">
				<?php echo wp_get_attachment_image( $args['image_id'], 'techmart-hero', false, array( 'class' => 'tm-promo__image' ) ); ?>
			</div>
		<?php endif; ?>

		<?php if ( $args['badge_text'] ) : ?>
			<div class="tm-promo__badge"><?php echo esc_html( $args['badge_text'] ); ?></div>
		<?php endif; ?>
	</div>
</div>
