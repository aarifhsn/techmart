<?php
/**
 * Sell-on-TechMart applications — stored as a private CPT so submissions
 * are reviewable in wp-admin without needing an email/SMTP setup.
 */

add_action('init', function () {
    register_post_type('techmart_sell_app', array(
        'label' => __('Sell Applications', 'techmart'),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_icon' => 'dashicons-store',
        'supports' => array('title', 'editor'),
        'capability_type' => 'post',
    ));
});

add_action('admin_post_techmart_sell_apply', 'techmart_handle_sell_application');
add_action('admin_post_nopriv_techmart_sell_apply', 'techmart_handle_sell_application');

function techmart_handle_sell_application()
{
    check_admin_referer('techmart_sell_apply', 'techmart_sell_apply_nonce');

    $redirect = wp_get_referer() ? wp_get_referer() : home_url('/');

    $business = isset($_POST['business_name']) ? sanitize_text_field(wp_unslash($_POST['business_name'])) : '';
    $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
    $phone = isset($_POST['phone']) ? sanitize_text_field(wp_unslash($_POST['phone'])) : '';
    $category = isset($_POST['category']) ? sanitize_text_field(wp_unslash($_POST['category'])) : '';

    if (empty($business) || !is_email($email)) {
        wp_safe_redirect(add_query_arg('techmart_sell_application', 'error', $redirect) . '#tm-sell-apply');
        exit;
    }

    wp_insert_post(array(
        'post_type' => 'techmart_sell_app',
        'post_title' => $business,
        'post_content' => "Email: {$email}\nPhone: {$phone}\nSells: {$category}\n",
        'post_status' => 'private',
        'meta_input' => array(
            'email' => $email,
            'phone' => $phone,
            'category' => $category,
        ),
    ));

    wp_safe_redirect(add_query_arg('techmart_sell_application', 'success', $redirect) . '#tm-sell-apply');
    exit;
}