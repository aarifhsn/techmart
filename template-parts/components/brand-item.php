<?php
/**
 * Reusable brand item: a logo (or name, if no logo) linking out to that
 * brand's products.
 *
 * @package TechMart
 *
 * @var array $args {
 *     @type string $name    Required.
 *     @type int    $logo_id Optional attachment ID. Falls back to text.
 *     @type string $url     Optional destination. Default '#'.
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args = wp_parse_args(
	$args,
	array(
		'name'    => '',
		'logo_id' => 0,
		'url'     => '#',
	)
);

if ( '' === $args['name'] ) {
	return;
}
?>
<a href="<?php echo esc_url( $args['url'] ); ?>" class="tm-brand-item">
	<?php if ( $args['logo_id'] ) : ?>
		<?php echo wp_get_attachment_image( $args['logo_id'], 'medium', false, array( 'class' => 'tm-brand-item__logo', 'alt' => esc_attr( $args['name'] ) ) ); ?>
	<?php else : ?>
		<span class="tm-brand-item__name"><?php echo esc_html( $args['name'] ); ?></span>
	<?php endif; ?>
</a>
