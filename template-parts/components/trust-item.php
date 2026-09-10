<?php
/**
 * Reusable trust/service item: icon + heading + description.
 *
 * Used by the "Why Shop With Us" row (stacked layout: icon above text)
 * and the hero's compact trust strip (inline layout: icon beside text)
 * — the anticipated "other trust/feature row" reuse this component was
 * built generic for back in Phase 5.
 *
 * @package TechMart
 *
 * @var array $args {
 *     @type string $icon        Icon name passed to techmart_icon(). Default 'shield'.
 *     @type string $title       Required.
 *     @type string $description Optional.
 *     @type string $layout      'stacked' (default) or 'inline'.
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args = wp_parse_args(
	$args,
	array(
		'icon'        => 'shield',
		'title'       => '',
		'description' => '',
		'layout'      => 'stacked',
	)
);

if ( '' === $args['title'] ) {
	return;
}

$layout    = ( 'inline' === $args['layout'] ) ? 'inline' : 'stacked';
$title_tag = ( 'inline' === $layout ) ? 'span' : 'h3';
?>
<div class="tm-trust-item tm-trust-item--<?php echo esc_attr( $layout ); ?>">
	<span class="tm-trust-item__icon"><?php techmart_icon( $args['icon'] ); ?></span>
	<span class="tm-trust-item__text">
		<<?php echo esc_html( $title_tag ); ?> class="tm-trust-item__title"><?php echo esc_html( $args['title'] ); ?></<?php echo esc_html( $title_tag ); ?>>
		<?php if ( $args['description'] ) : ?>
			<span class="tm-trust-item__description"><?php echo esc_html( $args['description'] ); ?></span>
		<?php endif; ?>
	</span>
</div>
