<?php
/**
 * Handles one property image per request, keeping front-end uploads small.
 */

if (!defined('ABSPATH')) {
    exit;
}

function niesv_upload_property_image() {
    check_ajax_referer('niesv_property_image_upload', 'nonce');

    if (!is_user_logged_in() || empty($_FILES['property_image'])) {
        wp_send_json_error(array('message' => 'Please choose an image while signed in.'), 400);
    }

    $file = $_FILES['property_image'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        wp_send_json_error(array('message' => 'The image upload failed. Please try that image again.'), 400);
    }

    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';

    $attachment_id = media_handle_upload('property_image', 0);
    if (is_wp_error($attachment_id)) {
        wp_send_json_error(array('message' => $attachment_id->get_error_message()), 400);
    }

    $user_id = get_current_user_id();
    wp_update_post(array(
        'ID' => $attachment_id,
        'post_author' => $user_id,
    ));
    update_post_meta($attachment_id, '_niesv_property_upload_user', $user_id);

    wp_send_json_success(array(
        'id' => $attachment_id,
        'url' => wp_get_attachment_image_url($attachment_id, 'thumbnail'),
    ));
}
add_action('wp_ajax_niesv_upload_property_image', 'niesv_upload_property_image');
