<?php
/**
 * Performance hardening.
 *
 * Font self-hosting lives in assets/css/base.css (its @font-face rules)
 * and inc/enqueue.php (the preload hint) since those are naturally part
 * of asset loading. This file is for head-output cleanup that doesn't
 * fit either of those — removing default WordPress <head> tags that
 * cost a real inline script/style or unused link tag on every single
 * page load.
 *
 * Deliberately NOT removed here: the REST API discovery link and
 * oEmbed discovery tag. Both are more commonly used by other plugins
 * (Jetpack, contact forms, embeds) than the ones removed below, so
 * removing them trades a small head-size saving for a real, if
 * uncommon, compatibility risk — not a trade worth making by default.
 *
 * @package TechMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function techmart_cleanup_head() {
	// Prints an inline <script> AND an inline <style> block on every
	// page load to support emoji rendering on browsers old enough to
	// need it — effectively none, today.
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );

	// XML-RPC discovery and Windows Live Writer manifest links: both
	// point to functionality essentially no current site or client uses.
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );

	// Redundant with the rel="canonical" tag WordPress already outputs.
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );

	// Don't advertise the exact WordPress version to every visitor.
	remove_action( 'wp_head', 'wp_generator' );
}
add_action( 'init', 'techmart_cleanup_head' );
