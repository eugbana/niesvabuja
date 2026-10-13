<?php
/**
 * Template Name: Edit Property
 * Property editing form for logged-in users
 *
 * @package NIESV_Theme
 */

// Redirect if not logged in
if (!is_user_logged_in()) {
    wp_redirect(home_url('/login/'));
    exit;
}

$current_user = wp_get_current_user();

// Get property ID from URL
$property_id = isset($_GET['property_id']) ? intval($_GET['property_id']) : 0;

// Validate property exists and user owns it
if (!$property_id) {
    wp_redirect(home_url('/property-dashboard/'));
    exit;
}

$property = get_post($property_id);

// Check if property exists and user is the author
if (!$property || $property->post_type !== 'properties' || $property->post_author != $current_user->ID) {
    wp_redirect(home_url('/property-dashboard/'));
    exit;
}

// Check if user has property access (verified member)
if (!niesv_user_has_property_access($current_user->ID)) {
    wp_redirect(add_query_arg('error', 'not_verified', home_url('/property-dashboard/')));
    exit;
}

// Handle form submission
if (isset($_POST['update_property']) && wp_verify_nonce($_POST['edit_property_nonce'] ?? '', 'edit_property_' . $property_id)) {
    $title = sanitize_text_field(wp_unslash($_POST['property_title'] ?? ''));
    $description = wp_kses_post(wp_unslash($_POST['property_description'] ?? ''));
    $price = floatval(wp_unslash($_POST['property_price'] ?? ''));
    $type = sanitize_text_field(wp_unslash($_POST['property_type'] ?? ''));
    $status = sanitize_text_field(wp_unslash($_POST['property_status'] ?? ''));
    $location = sanitize_text_field(wp_unslash($_POST['property_location'] ?? ''));
    $size = sanitize_text_field(wp_unslash($_POST['property_size'] ?? ''));
    $bedrooms = intval(wp_unslash($_POST['property_bedrooms'] ?? ''));
    $bathrooms = intval(wp_unslash($_POST['property_bathrooms'] ?? ''));
    $agent_name = sanitize_text_field(wp_unslash($_POST['agent_name'] ?? ''));
    $agent_email = sanitize_email(wp_unslash($_POST['agent_email'] ?? ''));
    $agent_mobile = sanitize_text_field(wp_unslash($_POST['agent_mobile'] ?? ''));
    
    $errors = array();
    
    // Validation
    if (empty($title)) {
        $errors[] = 'Property title is required.';
    }
    
    if (empty($price)) {
        $errors[] = 'Property price is required.';
    }
    
    if (empty($location)) {
        $errors[] = 'Property location is required.';
    }
    
    if (empty($agent_name)) {
        $errors[] = 'Agent name is required.';
    }
    
    if (empty($agent_email) || !is_email($agent_email)) {
        $errors[] = 'Valid agent email is required.';
    }
    
    if (empty($agent_mobile)) {
        $errors[] = 'Agent mobile number is required.';
    }
    
    // If no errors, update property
    if (empty($errors)) {
        $property_data = array(
            'ID' => $property_id,
            'post_title' => $title,
            'post_content' => $description,
        );
        
        $result = wp_update_post($property_data);
        
        if (!is_wp_error($result)) {
            // Update custom fields
            update_field('property_price', $price, $property_id);
            update_field('property_type', $type, $property_id);
            update_field('property_status', $status, $property_id);
            update_field('property_location', $location, $property_id);
            update_field('property_size', $size, $property_id);
            update_field('property_bedrooms', $bedrooms, $property_id);
            update_field('property_bathrooms', $bathrooms, $property_id);
            update_field('agent_name', $agent_name, $property_id);
            update_field('agent_email', $agent_email, $property_id);
            update_field('agent_mobile', $agent_mobile, $property_id);
            
            // Handle featured image upload
            if (!empty($_FILES['featured_image']['name'])) {
                $attachment_id = niesv_handle_property_image_upload('featured_image', $property_id);
                if (!is_wp_error($attachment_id)) {
                    set_post_thumbnail($property_id, $attachment_id);
                }
            }
            
            // Redirect to dashboard with success message
            $redirect_url = !empty(get_user_meta($current_user->ID, 'membership_no', true)) 
                ? home_url('/member-dashboard/') 
                : home_url('/property-dashboard/');
            wp_redirect(add_query_arg('updated', '1', $redirect_url));
            exit;
        } else {
            $errors[] = 'Failed to update property. Please try again.';
        }
    }
}

// Get existing property data
$property_price = get_field('property_price', $property_id);
$property_type = get_field('property_type', $property_id);
$property_status = get_field('property_status', $property_id);
$property_location = get_field('property_location', $property_id);
$property_size = get_field('property_size', $property_id);
$property_bedrooms = get_field('property_bedrooms', $property_id);
$property_bathrooms = get_field('property_bathrooms', $property_id);
$agent_name = get_field('agent_name', $property_id);
$agent_email = get_field('agent_email', $property_id);
$agent_mobile = get_field('agent_mobile', $property_id);

get_header(); ?>

<main class="flex-1 bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Property</h1>
                    <p class="text-gray-600 mt-1">Update your property listing</p>
                </div>
                <a href="<?php echo !empty(get_user_meta($current_user->ID, 'membership_no', true)) ? home_url('/member-dashboard/') : home_url('/property-dashboard/'); ?>"
                   class="text-gray-600 hover:text-gray-900 transition-colors">
                    ← Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Error Messages -->
        <?php if (isset($errors) && !empty($errors)) : ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md mb-6">
                <ul class="list-disc list-inside space-y-1">
                    <?php foreach ($errors as $error) : ?>
                        <li><?php echo wp_kses_post($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Property Form -->
        <div class="bg-white rounded-lg shadow p-8">
            <form method="post" action="" enctype="multipart/form-data">
                <?php wp_nonce_field('edit_property_' . $property_id, 'edit_property_nonce'); ?>
                <div class="space-y-8">

                    <!-- Basic Information -->
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Basic Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label for="property_title" class="block text-sm font-medium text-gray-700 mb-2">
                                    Name your property *
                                </label>
                                <input type="text"
                                       id="property_title"
                                       name="property_title"
                                       required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent"
                                       placeholder="e.g., Luxury 3-Bedroom Apartment in Maitama"
                                       value="<?php echo isset($_POST['property_title']) ? esc_attr($_POST['property_title']) : esc_attr($property->post_title); ?>">
                            </div>

                            <div>
                                <label for="property_type" class="block text-sm font-medium text-gray-700 mb-2">
                                    Property Type *
                                </label>
                                <select id="property_type"
                                        name="property_type"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent">
                                    <option value="residential" <?php selected(isset($_POST['property_type']) ? $_POST['property_type'] : $property_type, 'residential'); ?>>Residential</option>
                                    <option value="commercial" <?php selected(isset($_POST['property_type']) ? $_POST['property_type'] : $property_type, 'commercial'); ?>>Commercial</option>
                                    <option value="land" <?php selected(isset($_POST['property_type']) ? $_POST['property_type'] : $property_type, 'land'); ?>>Land</option>
                                    <option value="industrial" <?php selected(isset($_POST['property_type']) ? $_POST['property_type'] : $property_type, 'industrial'); ?>>Industrial</option>
                                </select>
                            </div>

                            <div>
                                <label for="property_status" class="block text-sm font-medium text-gray-700 mb-2">
                                    Property Status *
                                </label>
                                <select id="property_status"
                                        name="property_status"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent">
                                    <option value="for_sale" <?php selected(isset($_POST['property_status']) ? $_POST['property_status'] : $property_status, 'for_sale'); ?>>For Sale</option>
                                    <option value="for_rent" <?php selected(isset($_POST['property_status']) ? $_POST['property_status'] : $property_status, 'for_rent'); ?>>For Rent</option>
                                    <option value="sold" <?php selected(isset($_POST['property_status']) ? $_POST['property_status'] : $property_status, 'sold'); ?>>Sold</option>
                                    <option value="rented" <?php selected(isset($_POST['property_status']) ? $_POST['property_status'] : $property_status, 'rented'); ?>>Rented</option>
                                </select>
                            </div>

                            <div>
                                <label for="property_price" class="block text-sm font-medium text-gray-700 mb-2">
                                    Price (₦) *
                                </label>
                                <input type="number"
                                       id="property_price"
                                       name="property_price"
                                       required
                                       min="0"
                                       step="1000"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent"
                                       placeholder="e.g., 50000000"
                                       value="<?php echo isset($_POST['property_price']) ? esc_attr($_POST['property_price']) : esc_attr($property_price); ?>">
                            </div>

                            <div>
                                <label for="property_location" class="block text-sm font-medium text-gray-700 mb-2">
                                    Location *
                                </label>
                                <input type="text"
                                       id="property_location"
                                       name="property_location"
                                       required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent"
                                       placeholder="e.g., Maitama, Abuja"
                                       value="<?php echo isset($_POST['property_location']) ? esc_attr($_POST['property_location']) : esc_attr($property_location); ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Property Details -->
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Property Details</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="property_size" class="block text-sm font-medium text-gray-700 mb-2">
                                    Size
                                </label>
                                <input type="text"
                                       id="property_size"
                                       name="property_size"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent"
                                       placeholder="e.g., 500 sqm"
                                       value="<?php echo isset($_POST['property_size']) ? esc_attr($_POST['property_size']) : esc_attr($property_size); ?>">
                            </div>

                            <div>
                                <label for="property_bedrooms" class="block text-sm font-medium text-gray-700 mb-2">
                                    Bedrooms
                                </label>
                                <input type="number"
                                       id="property_bedrooms"
                                       name="property_bedrooms"
                                       min="0"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent"
                                       value="<?php echo isset($_POST['property_bedrooms']) ? esc_attr($_POST['property_bedrooms']) : esc_attr($property_bedrooms); ?>">
                            </div>

                            <div>
                                <label for="property_bathrooms" class="block text-sm font-medium text-gray-700 mb-2">
                                    Bathrooms
                                </label>
                                <input type="number"
                                       id="property_bathrooms"
                                       name="property_bathrooms"
                                       min="0"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent"
                                       value="<?php echo isset($_POST['property_bathrooms']) ? esc_attr($_POST['property_bathrooms']) : esc_attr($property_bathrooms); ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="property_description" class="block text-sm font-medium text-gray-700 mb-2">
                            Property Description
                        </label>
                        <textarea id="property_description"
                                  name="property_description"
                                  rows="6"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent"
                                  placeholder="Describe the property features, amenities, titles such as (R of O, C of O) and other details..."><?php echo isset($_POST['property_description']) ? esc_textarea($_POST['property_description']) : esc_textarea($property->post_content); ?></textarea>
                    </div>

                    <!-- Featured Image -->
                    <div>
                        <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">
                            Featured Image
                        </label>
                        <?php if (has_post_thumbnail($property_id)) : ?>
                            <div class="mb-3">
                                <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                                <img src="<?php echo get_the_post_thumbnail_url($property_id, 'medium'); ?>"
                                     alt="Current property image"
                                     class="w-48 h-32 object-cover rounded-lg border border-gray-300">
                            </div>
                        <?php endif; ?>
                        <input type="file"
                               id="featured_image"
                               name="featured_image"
                               accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent">
                        <p class="text-sm text-gray-500 mt-1">Upload a new image to replace the current one (optional).</p>
                    </div>

                    <!-- Agent Information -->
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Agent Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="agent_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Agent Name *
                                </label>
                                <input type="text"
                                       id="agent_name"
                                       name="agent_name"
                                       required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent"
                                       placeholder="e.g., John Doe"
                                       value="<?php echo isset($_POST['agent_name']) ? esc_attr($_POST['agent_name']) : esc_attr($agent_name); ?>">
                            </div>

                            <div>
                                <label for="agent_email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Agent Email *
                                </label>
                                <input type="email"
                                       id="agent_email"
                                       name="agent_email"
                                       required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent"
                                       placeholder="agent@example.com"
                                       value="<?php echo isset($_POST['agent_email']) ? esc_attr($_POST['agent_email']) : esc_attr($agent_email); ?>">
                            </div>

                            <div>
                                <label for="agent_mobile" class="block text-sm font-medium text-gray-700 mb-2">
                                    Agent Mobile *
                                </label>
                                <input type="tel"
                                       id="agent_mobile"
                                       name="agent_mobile"
                                       required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent"
                                       placeholder="+234 801 234 5678"
                                       value="<?php echo isset($_POST['agent_mobile']) ? esc_attr($_POST['agent_mobile']) : esc_attr($agent_mobile); ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-4">
                        <a href="<?php echo !empty(get_user_meta($current_user->ID, 'membership_no', true)) ? home_url('/member-dashboard/') : home_url('/property-dashboard/'); ?>"
                           class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                        <button type="submit"
                                name="update_property"
                                class="px-6 py-2 bg-[var(--primary-color)] text-white rounded-md hover:bg-opacity-90 transition-colors font-medium">
                            Update Property
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<?php get_footer(); ?>

