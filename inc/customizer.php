<?php
/**
 * Customizer settings.
 *
 * Phase 2 only adds the announcement bar's text/code — the first
 * genuinely admin-editable copy in the theme. Hero content, promo
 * banners, and footer contact/social details (Phase 3/5/6) extend this
 * same file with their own add_section() calls rather than each
 * getting a separate Customizer file.
 *
 * @package TechMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function techmart_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'techmart_announcement',
		array(
			'title'    => __( 'Announcement Bar', 'techmart' ),
			'priority' => 25,
		)
	);

	$wp_customize->add_setting(
		'techmart_announcement_text',
		array(
			'default'           => __( 'Special Offer! Get 10% OFF for your first order.', 'techmart' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'techmart_announcement_text',
		array(
			'label'   => __( 'Announcement text', 'techmart' ),
			'section' => 'techmart_announcement',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'techmart_announcement_code',
		array(
			'default'           => 'FIRST10',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'techmart_announcement_code',
		array(
			'label'       => __( 'Promo code (leave blank to hide)', 'techmart' ),
			'section'     => 'techmart_announcement',
			'type'        => 'text',
		)
	);

	$wp_customize->add_section(
		'techmart_hero',
		array(
			'title'    => __( 'Homepage Hero', 'techmart' ),
			'priority' => 30,
		)
	);

	/**
	 * Every hero text field follows the same setting/control shape, so
	 * they're declared as a small map and looped instead of six nearly
	 * identical add_setting()/add_control() pairs.
	 */
	$hero_text_fields = array(
		'techmart_hero_eyebrow'        => array( __( 'Eyebrow text', 'techmart' ), __( 'New Arrival', 'techmart' ) ),
		'techmart_hero_heading'        => array( __( 'Headline', 'techmart' ), __( 'Modern technology. Built for everyday life.', 'techmart' ) ),
		'techmart_hero_primary_text'   => array( __( 'Primary button text', 'techmart' ), __( 'Shop Now', 'techmart' ) ),
		'techmart_hero_primary_url'    => array( __( 'Primary button URL', 'techmart' ), '#' ),
		'techmart_hero_secondary_text' => array( __( 'Secondary button text', 'techmart' ), __( 'Explore Deals', 'techmart' ) ),
		'techmart_hero_secondary_url'  => array( __( 'Secondary button URL', 'techmart' ), '#' ),
	);

	foreach ( $hero_text_fields as $setting_id => $field ) {
		list( $label, $default ) = $field;

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => $default,
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			$setting_id,
			array(
				'label'   => $label,
				'section' => 'techmart_hero',
				'type'    => 'text',
			)
		);
	}

	$wp_customize->add_setting(
		'techmart_hero_description',
		array(
			'default'           => __( 'Discover the latest in electronics — curated for performance, backed by real support.', 'techmart' ),
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'techmart_hero_description',
		array(
			'label'   => __( 'Description', 'techmart' ),
			'section' => 'techmart_hero',
			'type'    => 'textarea',
		)
	);

	/**
	 * Stored as an attachment ID (sanitized with absint), not a URL —
	 * see the comment in template-parts/home/hero.php for why that
	 * matters for responsive images and alt text.
	 */
	$wp_customize->add_setting(
		'techmart_hero_image',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'techmart_hero_image',
			array(
				'label'     => __( 'Hero image', 'techmart' ),
				'section'   => 'techmart_hero',
				'mime_type' => 'image',
			)
		)
	);

	$wp_customize->add_section(
		'techmart_promo',
		array(
			'title'       => __( 'Promotion Banner', 'techmart' ),
			'description' => __( 'Leave the title blank to hide this section entirely.', 'techmart' ),
			'priority'    => 35,
		)
	);

	$promo_text_fields = array(
		'techmart_promo_eyebrow'    => array( __( 'Eyebrow text', 'techmart' ), __( 'Limited Time Offer', 'techmart' ) ),
		'techmart_promo_title'      => array( __( 'Title (blank hides the section)', 'techmart' ), '' ),
		'techmart_promo_subtitle'   => array( __( 'Subtitle', 'techmart' ), '' ),
		'techmart_promo_badge_text' => array( __( 'Badge text, e.g. "Save up to 40%"', 'techmart' ), '' ),
		'techmart_promo_cta_text'   => array( __( 'Button text', 'techmart' ), __( 'Shop Now', 'techmart' ) ),
		'techmart_promo_cta_url'    => array( __( 'Button URL', 'techmart' ), '#' ),
	);

	foreach ( $promo_text_fields as $setting_id => $field ) {
		list( $label, $default ) = $field;

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => $default,
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			$setting_id,
			array(
				'label'   => $label,
				'section' => 'techmart_promo',
				'type'    => 'text',
			)
		);
	}

	$promo_image_fields = array(
		'techmart_promo_image'    => __( 'Feature image (e.g. a product shot)', 'techmart' ),
		'techmart_promo_bg_image' => __( 'Background photo (optional — falls back to a solid brand background)', 'techmart' ),
	);

	foreach ( $promo_image_fields as $setting_id => $label ) {
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => 0,
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Media_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'     => $label,
					'section'   => 'techmart_promo',
					'mime_type' => 'image',
				)
			)
		);
	}

	$wp_customize->add_section(
		'techmart_footer',
		array(
			'title'    => __( 'Footer', 'techmart' ),
			'priority' => 45,
		)
	);

	$wp_customize->add_setting(
		'techmart_footer_description',
		array(
			'default'           => __( 'Your trusted destination for the latest electronics, quality products, best prices, and excellent service.', 'techmart' ),
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'techmart_footer_description',
		array(
			'label'   => __( 'Brand description', 'techmart' ),
			'section' => 'techmart_footer',
			'type'    => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'techmart_contact_phone',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'techmart_contact_phone',
		array(
			'label'   => __( 'Phone number', 'techmart' ),
			'section' => 'techmart_footer',
			'type'    => 'tel',
		)
	);

	$wp_customize->add_setting(
		'techmart_contact_email',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_email',
		)
	);
	$wp_customize->add_control(
		'techmart_contact_email',
		array(
			'label'   => __( 'Contact email', 'techmart' ),
			'section' => 'techmart_footer',
			'type'    => 'email',
		)
	);

	$wp_customize->add_setting(
		'techmart_contact_address',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'techmart_contact_address',
		array(
			'label'   => __( 'Address', 'techmart' ),
			'section' => 'techmart_footer',
			'type'    => 'textarea',
		)
	);

	/**
	 * Every social field follows the same setting/control shape — same
	 * map-and-loop pattern as the hero text fields above.
	 */
	$social_fields = array(
		'techmart_social_facebook'  => __( 'Facebook URL', 'techmart' ),
		'techmart_social_twitter'   => __( 'Twitter / X URL', 'techmart' ),
		'techmart_social_instagram' => __( 'Instagram URL', 'techmart' ),
		'techmart_social_youtube'   => __( 'YouTube URL', 'techmart' ),
	);

	foreach ( $social_fields as $setting_id => $label ) {
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			$setting_id,
			array(
				'label'   => $label,
				'section' => 'techmart_footer',
				'type'    => 'url',
			)
		);
	}
}
add_action( 'customize_register', 'techmart_customize_register' );
