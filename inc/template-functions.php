<?php
/**
 * General template tags and hooks shared across templates.
 *
 * @package TechMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add contextual body classes so CSS (and later, JS) can target a
 * template without re-running conditional tags — e.g. `.tm-home` or
 * `.tm-woocommerce-page` — instead of every component file calling
 * is_front_page() or is_woocommerce() itself.
 *
 * @param array $classes Existing body classes.
 * @return array
 */
function techmart_body_classes( $classes ) {
	if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
		$classes[] = 'tm-woocommerce-page';
	}

	if ( is_front_page() ) {
		$classes[] = 'tm-home';
	}

	if ( ! is_active_sidebar( 'footer-1' ) ) {
		$classes[] = 'tm-no-footer-widgets';
	}

	return $classes;
}
add_filter( 'body_class', 'techmart_body_classes' );

/**
 * Output the custom logo, falling back to the site name as a text link.
 * Called from both header.php and template-parts/footer/main.php
 * (Phase 6), so the fallback class is named generically rather than
 * "tm-header__..." — each caller's own stylesheet (header.css /
 * footer.css) styles .tm-logo-text for its own background.
 */
function techmart_the_logo() {
	if ( has_custom_logo() ) {
		the_custom_logo();
		return;
	}
	?>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="tm-logo-text" rel="home">
		<?php bloginfo( 'name' ); ?>
	</a>
	<?php
}

/**
 * Look up a raw inline SVG icon string by name.
 *
 * Split out from techmart_icon() so other helpers — techmart_rating()
 * below needs five unwrapped star icons, not five <span class="tm-icon">
 * wrappers — can reuse the same icon set without parsing techmart_icon()'s
 * printed HTML.
 *
 * @param string $name Icon name.
 * @return string Raw <svg>...</svg> markup, or '' if the name is unknown.
 */
function techmart_get_icon_markup( $name ) {
	$icons = array(
		'menu'         => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" focusable="false"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>',
		'search'       => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>',
		'heart'        => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"/></svg>',
		'cart'         => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.7 13.4a2 2 0 0 0 2 1.6h9.7a2 2 0 0 0 2-1.6L23 6H6"/></svg>',
		'user'         => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
		'chevron-down' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><polyline points="6 9 12 15 18 9"/></svg>',
		'arrow-right'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>',
		'star'         => '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M12 2l2.9 6.26L21 9.27l-4.5 4.38L17.8 21 12 17.77 6.2 21l1.3-7.35L3 9.27l6.1-1.01L12 2z"/></svg>',
		'shield'       => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M12 2l8 4v6c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-4z"/></svg>',
		'tag'          => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M20.59 13.41 11 3.83A2 2 0 0 0 9.59 3.2L4 3a1 1 0 0 0-1 1l.2 5.59a2 2 0 0 0 .58 1.4l9.6 9.6a2 2 0 0 0 2.83 0l4.4-4.4a2 2 0 0 0 0-2.78z"/><circle cx="7.5" cy="7.5" r="1.5"/></svg>',
		'truck'        => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="1" y="6" width="14" height="11"/><path d="M15 9h4l3 3v5h-7z"/><circle cx="6" cy="19" r="2"/><circle cx="17.5" cy="19" r="2"/></svg>',
		'refresh'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><polyline points="1 4 1 10 7 10"/><polyline points="23 20 23 14 17 14"/><path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4-4.64 4.36A9 9 0 0 1 3.51 15"/></svg>',
		'lock'         => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>',
		'facebook'     => '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M13.5 21v-8h2.7l.4-3.1h-3.1V8c0-.9.25-1.5 1.55-1.5H17V3.7C16.7 3.65 15.7 3.5 14.5 3.5c-2.4 0-4 1.45-4 4.15V10H7.8v3.1h2.7v8h3z"/></svg>',
		'twitter'      => '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M18.9 2H22l-7.6 8.7L23 22h-6.8l-5.3-6.9L4.8 22H2l8.1-9.3L1.6 2h7l4.8 6.3L18.9 2z"/></svg>',
		'instagram'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" focusable="false"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="0.5" fill="currentColor" stroke="none"/></svg>',
		'youtube'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="2" y="5" width="20" height="14" rx="4"/><polygon points="10 9 15 12 10 15" fill="currentColor" stroke="none"/></svg>',
		'phone'        => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.68 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.32 1.85.55 2.81.68A2 2 0 0 1 22 16.92z"/></svg>',
		'mail'         => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="2" y="4" width="20" height="16" rx="2"/><polyline points="2 7 12 13 22 7"/></svg>',
		'map-pin'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M21 10c0 7-9 12-9 12s-9-5-9-12a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>',
	);

	return isset( $icons[ $name ] ) ? $icons[ $name ] : '';
}

/**
 * Print a small inline SVG icon.
 *
 * The brief explicitly warns against unnecessary icon libraries (§28
 * performance). A font-icon library would add an HTTP request and a
 * whole glyph set for the handful of icons this theme actually uses,
 * so icons are inline SVG strings instead — no request, no unused
 * glyphs, and they inherit color via `currentColor` for free.
 *
 * @param string $name Icon name: 'menu', 'search', 'heart', 'cart', 'user', 'chevron-down', 'arrow-right', 'star', 'shield', 'tag', 'truck', 'refresh', 'lock', 'facebook', 'twitter', 'instagram', 'youtube', 'phone', 'mail', 'map-pin'.
 * @param array  $args { 'class' => string additional class(es) for the wrapping <span>. }
 */
function techmart_icon( $name, $args = array() ) {
	$args = wp_parse_args( $args, array( 'class' => '' ) );
	$svg  = techmart_get_icon_markup( $name );

	if ( '' === $svg ) {
		return;
	}

	printf(
		'<span class="tm-icon %s">%s</span>',
		esc_attr( $args['class'] ),
		$svg // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static, theme-authored SVG markup from techmart_get_icon_markup(); no user input reaches this string.
	);
}

/**
 * Print a star rating (average out of 5, plus a review count).
 *
 * Deliberately not WooCommerce's wc_get_rating_html(): that function's
 * default markup depends on WooCommerce's bundled star-rating CSS
 * (a background-image sprite), which inc/enqueue.php disables via the
 * woocommerce_enqueue_styles filter in Phase 2, since every other
 * visual in this theme is custom rather than WooCommerce-default. Using
 * wc_get_rating_html() here would silently reintroduce that dependency
 * for one component. This builds the same information from our own
 * inline 'star' icon instead, filled with the brand primary color
 * rather than the generic yellow most storefronts default to (§42:
 * avoid generic, obviously-templated ecommerce patterns).
 *
 * @param float $average Average rating, 0–5.
 * @param int   $count   Review count. Nothing is printed if 0.
 */
function techmart_rating( $average, $count ) {
	$average = (float) $average;
	$count   = (int) $count;

	if ( $count <= 0 ) {
		return;
	}

	$percent = max( 0, min( 100, ( $average / 5 ) * 100 ) );
	$stars   = str_repeat( techmart_get_icon_markup( 'star' ), 5 );

	printf(
		'<span class="tm-rating"><span class="tm-rating__track">%1$s<span class="tm-rating__fill" style="width:%2$s%%">%1$s</span></span><span class="tm-rating__count">(%3$s)</span><span class="screen-reader-text">%4$s</span></span>',
		$stars, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static, theme-authored SVG markup from techmart_get_icon_markup(), repeated; no user input.
		esc_attr( round( $percent, 1 ) ),
		esc_html( number_format_i18n( $count ) ),
		esc_html(
			sprintf(
				/* translators: 1: average rating out of 5, 2: number of reviews */
				__( 'Rated %1$s out of 5 based on %2$s reviews', 'techmart' ),
				$average,
				$count
			)
		)
	);
}

/**
 * Print a consistent button/link.
 *
 * The brief's suggested file tree includes template-parts/components/
 * button.php. A two-argument snippet used inline inside other templates
 * (hero CTAs now; product-card "Add to Cart" and section-header links
 * from Phase 4) reads more clearly as a direct function call than as a
 * get_template_part() file round-trip for something this small — so
 * this is a function instead. Class names still follow the brief's own
 * convention (§32): .tm-button, .tm-button--primary, .tm-button--secondary.
 *
 * @param array $args {
 *     @type string $text    Button label. Required — returns nothing if empty.
 *     @type string $url     Destination URL. Default '#'.
 *     @type string $variant 'primary' (default) or 'secondary'.
 *     @type string $class   Extra class(es).
 * }
 */
function techmart_button( $args ) {
	$args = wp_parse_args(
		$args,
		array(
			'text'    => '',
			'url'     => '#',
			'variant' => 'primary',
			'class'   => '',
		)
	);

	if ( '' === $args['text'] ) {
		return;
	}

	printf(
		'<a href="%1$s" class="tm-button tm-button--%2$s %3$s">%4$s</a>',
		esc_url( $args['url'] ),
		esc_attr( $args['variant'] ),
		esc_attr( $args['class'] ),
		esc_html( $args['text'] )
	);
}

/**
 * Thin, documented wrapper around get_template_part() so component
 * templates (template-parts/product/card.php, etc., from Phase 4
 * onward) always receive an $args array, even when the caller passes
 * none. Not a reimplementation — WordPress core has supported $args
 * since 5.5; this just gives the pattern a theme-prefixed name so it
 * reads consistently alongside the rest of the techmart_ API.
 *
 * @param string $slug Template slug, e.g. 'template-parts/product/card'.
 * @param string|null $name Template name variant.
 * @param array  $args Arguments made available to the template part.
 */
function techmart_get_template_part( $slug, $name = null, $args = array() ) {
	get_template_part( $slug, $name, $args );
}
