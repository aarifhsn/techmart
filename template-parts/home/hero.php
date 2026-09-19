<?php
/**
 * Hero / main promotional offer.
 *
 * All copy and the image are Customizer-controlled (inc/customizer.php)
 * rather than hardcoded — an admin swaps the promotion without touching
 * template code. The image setting stores an attachment ID, not a raw
 * URL, specifically so it renders through wp_get_attachment_image()
 * using the 'techmart-hero' size registered in Phase 1 — that gets
 * responsive srcset/sizes and a real alt attribute for free, which a
 * plain <img src="{url}"> from a text/URL setting would not.
 *
 * Layout: a persistent category sidebar (template-parts/home/
 * hero-categories.php) spans both grid rows on the left; the primary
 * nav (template-parts/home/hero-nav.php) sits in the top row beside
 * its heading, and the banner card sits in the row below, beside the
 * rest of the sidebar's list — matching the reference design, where
 * the nav aligns with "All Categories" rather than forming its own
 * full-width row above everything.
 *
 * @package TechMart
 */

if (!defined('ABSPATH')) {
	exit;
}

$eyebrow = get_theme_mod('techmart_hero_eyebrow', __('New Arrival', 'techmart'));
$heading = get_theme_mod('techmart_hero_heading', __('Modern technology. Built for everyday life.', 'techmart'));
$description = get_theme_mod('techmart_hero_description', __('Discover the latest in electronics — curated for performance, backed by real support.', 'techmart'));
$primary_text = get_theme_mod('techmart_hero_primary_text', __('Shop Now', 'techmart'));
$primary_url = get_theme_mod('techmart_hero_primary_url', '#');
$secondary_text = get_theme_mod('techmart_hero_secondary_text', __('Explore Deals', 'techmart'));
$secondary_url = get_theme_mod('techmart_hero_secondary_url', '#');
$image_id = absint(get_theme_mod('techmart_hero_image', 0));

/**
 * Trust-badges strip, stacked inside the banner card below the main
 * content — matches the reference design. Reuses the same trust-item
 * component as "Why Shop With Us" (Phase 5) in its 'inline' layout,
 * exactly the reuse that component's original comment anticipated.
 * Filterable, not Customizer-driven, for the same reason as the Why
 * Shop items: stable structural content, not copy an admin swaps often.
 */
$hero_trust_items = apply_filters(
	'techmart_hero_trust_items',
	array(
		array(
			'icon' => 'truck',
			'title' => __('Free Shipping', 'techmart'),
			'description' => __('On orders over ৳ 1500', 'techmart'),
		),
		array(
			'icon' => 'refresh',
			'title' => __('30 Days Returns', 'techmart'),
			'description' => __('Money back guarantee', 'techmart'),
		),
		array(
			'icon' => 'lock',
			'title' => __('Secure Payment', 'techmart'),
			'description' => __('100% secure checkout', 'techmart'),
		),
		array(
			'icon' => 'phone',
			'title' => __('24/7 Support', 'techmart'),
			'description' => __('Dedicated support', 'techmart'),
		),
	)
);
?>
<section class="tm-hero tm-band" aria-label="<?php esc_attr_e('Featured promotion', 'techmart'); ?>">
	<div class="tm-container tm-hero__layout">
		<?php get_template_part('template-parts/home/hero-categories'); ?>

		<?php get_template_part('template-parts/home/hero-nav'); ?>

		<div class="tm-hero__banner-card">
			<div class="tm-hero__inner">
				<div class="tm-hero__content">
					<?php if ($eyebrow): ?>
						<p class="tm-hero__eyebrow tm-label"><?php echo esc_html($eyebrow); ?></p>
					<?php endif; ?>

					<h1 class="tm-hero__heading"><?php echo esc_html($heading); ?></h1>

					<?php if ($description): ?>
						<p class="tm-hero__description"><?php echo esc_html($description); ?></p>
					<?php endif; ?>

					<div class="tm-hero__actions">
						<?php
						techmart_button(
							array(
								'text' => $primary_text,
								'url' => $primary_url,
								'variant' => 'primary',
							)
						);
						techmart_button(
							array(
								'text' => $secondary_text,
								'url' => $secondary_url,
								'variant' => 'secondary',
							)
						);
						?>
					</div>
				</div>

				<?php if ($image_id): ?>
					<div class="tm-hero__media">
						<?php
						/**
						 * The hero image is above the fold and is very likely
						 * this page's Largest Contentful Paint element, so it's
						 * explicitly excluded from the lazy-loading WordPress
						 * applies by default (loading="lazy" on every other
						 * content image) and marked high fetch priority instead.
						 * Lazy-loading an LCP image is a common, measurable
						 * performance regression — worth calling out explicitly
						 * given the WooCommerce speed-optimization work you do.
						 */
						echo wp_get_attachment_image(
							$image_id,
							'techmart-hero',
							false,
							array(
								'class' => 'tm-hero__image',
								'loading' => 'eager',
								'fetchpriority' => 'high',
							)
						);
						?>
					</div>
				<?php endif; ?>
			</div>

			<?php if ($hero_trust_items): ?>
				<div class="tm-hero__trust-strip">
					<div class="tm-hero__trust-strip-inner">
						<?php foreach ($hero_trust_items as $item): ?>
							<?php
							techmart_get_template_part(
								'template-parts/components/trust-item',
								null,
								array_merge($item, array('layout' => 'inline'))
							);
							?>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>