<?php
/**
 * Handles one property image per request, keeping front-end uploads small.
 */

if (!defined('ABSPATH')) {
    exit;
}

const NIESV_PROPERTY_IMAGE_MAX_BYTES = 5 * MB_IN_BYTES;

/**
 * Image types members may upload for property listings.
 */
function niesv_property_image_mimes() {
    return array(
        'jpg|jpeg|jpe' => 'image/jpeg',
        'png'          => 'image/png',
        'gif'          => 'image/gif',
        'webp'         => 'image/webp',
    );
}

/**
 * Validate and store a property image from $_FILES[$field].
 *
 * Only raster images up to NIESV_PROPERTY_IMAGE_MAX_BYTES are accepted. WordPress
 * checks the real file contents against the allowed types, so renamed files
 * (e.g. an SVG or HTML file called photo.jpg) are rejected.
 *
 * @return int|WP_Error Attachment ID or error.
 */
function niesv_handle_property_image_upload($field, $parent_id = 0) {
    if (empty($_FILES[$field]) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
        return new WP_Error('upload_failed', 'The image upload failed. Please try that image again.');
    }

    if ($_FILES[$field]['size'] > NIESV_PROPERTY_IMAGE_MAX_BYTES) {
        return new WP_Error('too_large', 'Images must be 5 MB or smaller.');
    }

    // Check the name and the actual bytes ourselves. SVG plugins filter WordPress's
    // own file type check, so it cannot be relied on to keep SVGs out.
    $allowed = niesv_property_image_mimes();
    $by_name = wp_check_filetype(wp_basename($_FILES[$field]['name']), $allowed);
    $by_content = wp_get_image_mime($_FILES[$field]['tmp_name']);
    if (!$by_name['type'] || !$by_content || !in_array($by_content, $allowed, true)) {
        return new WP_Error('invalid_type', 'Please upload a JPG, PNG, GIF or WebP image.');
    }

    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';

    return media_handle_upload($field, $parent_id, array(), array(
        'test_form' => false,
        'mimes'     => niesv_property_image_mimes(),
    ));
}

function niesv_upload_property_image() {
    check_ajax_referer('niesv_property_image_upload', 'nonce');

    if (!is_user_logged_in() || empty($_FILES['property_image'])) {
        wp_send_json_error(array('message' => 'Please choose an image while signed in.'), 400);
    }

    if (!niesv_user_has_property_access()) {
        wp_send_json_error(array('message' => 'Your membership must be verified before you can upload property images.'), 403);
    }

    $attachment_id = niesv_handle_property_image_upload('property_image');
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

/**
 * Delete images uploaded from the property form but never attached to a listing
 * (the form was abandoned). The marker meta is removed once a property is saved.
 */
function niesv_cleanup_orphaned_property_images() {
    $orphans = get_posts(array(
        'post_type'      => 'attachment',
        'post_status'    => 'inherit',
        'post_parent'    => 0,
        'posts_per_page' => 100,
        'fields'         => 'ids',
        'meta_key'       => '_niesv_property_upload_user',
        'date_query'     => array(array('before' => '1 day ago')),
    ));

    foreach ($orphans as $attachment_id) {
        wp_delete_attachment($attachment_id, true);
    }
}
add_action('niesv_cleanup_orphaned_property_images', 'niesv_cleanup_orphaned_property_images');

function niesv_schedule_property_image_cleanup() {
    if (!wp_next_scheduled('niesv_cleanup_orphaned_property_images')) {
        wp_schedule_event(time() + HOUR_IN_SECONDS, 'daily', 'niesv_cleanup_orphaned_property_images');
    }
}
add_action('init', 'niesv_schedule_property_image_cleanup');
