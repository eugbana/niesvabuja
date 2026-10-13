<?php
/**
 * Template Name: Add Property
 * Property creation form for logged-in users
 * Version 1.3
 *
 * @package NIESV_Theme
 */

// Redirect if not logged in
if (!is_user_logged_in()) {
    wp_redirect(home_url('/login/'));
    exit;
}

$current_user = wp_get_current_user();

// Only verified members (and admins) can list properties
if (!niesv_user_has_property_access($current_user->ID)) {
    wp_redirect(add_query_arg('error', 'not_verified', home_url('/property-dashboard/')));
    exit;
}

// Keep this form in step with the Property Details ACF field group used in wp-admin.
$property_types = array('residential', 'commercial', 'land', 'industrial');
$property_statuses = array('for_sale', 'for_rent', 'sold', 'rented');
$rent_durations = array('monthly', 'quarterly', 'semi_annual', 'annual');

if (isset($_POST['submit_property'])) {
    $errors = array();
    if (!isset($_POST['niesv_property_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['niesv_property_nonce'])), 'niesv_create_property')) {
        $errors[] = 'Your session has expired. Please refresh the page and try again.';
    }

    $title = sanitize_text_field(wp_unslash($_POST['property_title'] ?? ''));
    $description = wp_kses_post(wp_unslash($_POST['property_description'] ?? ''));
    $price = isset($_POST['property_price']) ? floatval(wp_unslash($_POST['property_price'])) : 0;
    $type = sanitize_key(wp_unslash($_POST['property_type'] ?? ''));
    $status = sanitize_key(wp_unslash($_POST['property_status'] ?? ''));
    $rent_duration = sanitize_key(wp_unslash($_POST['rent_duration'] ?? ''));
    $location = sanitize_text_field(wp_unslash($_POST['property_location'] ?? ''));
    $size = sanitize_text_field(wp_unslash($_POST['property_size'] ?? ''));
    $bedrooms = max(0, intval(wp_unslash($_POST['property_bedrooms'] ?? 0)));
    $bathrooms = max(0, intval(wp_unslash($_POST['property_bathrooms'] ?? 0)));
    $agent_name = sanitize_text_field(wp_unslash($_POST['agent_name'] ?? ''));
    $agent_email = sanitize_email(wp_unslash($_POST['agent_email'] ?? ''));
    $agent_mobile = sanitize_text_field(wp_unslash($_POST['agent_mobile'] ?? ''));
    $contact_agent_link = esc_url_raw(wp_unslash($_POST['contact_agent_link'] ?? ''));
    $features = isset($_POST['property_features']) && is_array($_POST['property_features'])
        ? array_values(array_filter(array_map('sanitize_text_field', wp_unslash($_POST['property_features']))))
        : array();
    $image_ids = isset($_POST['property_image_ids']) && is_array($_POST['property_image_ids'])
        ? array_values(array_unique(array_filter(array_map('absint', wp_unslash($_POST['property_image_ids'])))))
        : array();

    if (empty($title)) { $errors[] = 'Property title is required.'; }
    if (empty($description)) { $errors[] = 'Property description is required.'; }
    if ($price <= 0) { $errors[] = 'A valid property price is required.'; }
    if (!in_array($type, $property_types, true)) { $errors[] = 'Please select a valid property type.'; }
    if (!in_array($status, $property_statuses, true)) { $errors[] = 'Please select a valid property status.'; }
    if ($status === 'for_rent' && !in_array($rent_duration, $rent_durations, true)) { $errors[] = 'Please select a rent duration.'; }
    if (empty($location)) { $errors[] = 'Property location is required.'; }
    if (empty($agent_name)) { $errors[] = 'Agent name is required.'; }
    if (empty($agent_email) || !is_email($agent_email)) { $errors[] = 'A valid agent email is required.'; }
    if (empty($agent_mobile)) { $errors[] = 'Agent mobile number is required.'; }
    if (!empty($contact_agent_link) && !wp_http_validate_url($contact_agent_link)) { $errors[] = 'Please enter a valid contact-agent URL.'; }
    if (count($image_ids) > 10) { $errors[] = 'You can upload a maximum of 10 property images.'; }
    foreach ($image_ids as $image_id) {
        if (get_post_type($image_id) !== 'attachment'
            || (int) get_post_field('post_author', $image_id) !== (int) $current_user->ID
            || (int) get_post_meta($image_id, '_niesv_property_upload_user', true) !== (int) $current_user->ID) {
            $errors[] = 'One or more uploaded images could not be verified. Please upload them again.';
            break;
        }
    }

    if (empty($errors)) {
        $property_id = wp_insert_post(array(
            'post_title' => $title,
            'post_content' => $description,
            // 'post_status' => current_user_can('publish_posts') ? 'publish' : 'pending',
            'post_status' => 'publish',
            'post_type' => 'properties',
            'post_author' => $current_user->ID,
        ), true);

        if (!is_wp_error($property_id)) {
            foreach (array(
                'property_price' => $price, 'property_type' => $type, 'property_status' => $status,
                'rent_duration' => $status === 'for_rent' ? $rent_duration : '', 'property_location' => $location,
                'property_size' => $size, 'property_bedrooms' => $type === 'residential' ? $bedrooms : '',
                'property_bathrooms' => $type === 'residential' ? $bathrooms : '', 'agent_name' => $agent_name,
                'agent_email' => $agent_email, 'agent_mobile' => $agent_mobile, 'contact_agent_link' => $contact_agent_link,
                'property_description' => $description,
            ) as $field_name => $value) {
                update_field($field_name, $value, $property_id);
            }

            if ($image_ids) {
                foreach ($image_ids as $image_id) {
                    wp_update_post(array('ID' => $image_id, 'post_parent' => $property_id));
                    delete_post_meta($image_id, '_niesv_property_upload_user');
                }
                update_field('property_images', $image_ids, $property_id);
                set_post_thumbnail($property_id, $image_ids[0]);
            }
            if ($features) {
                update_field('property_features', array_map(static function ($feature) { return array('field_feature_name' => $feature); }, $features), $property_id);
            }
            wp_redirect(add_query_arg('created', '1', home_url('/property-dashboard/')));
            exit;
        }
        $errors[] = 'Failed to create property. Please try again.';
    }
}

get_header(); ?>

<main class="flex-1 bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Add New Property</h1>
                    <p class="text-gray-600 mt-1">Create a new property listing</p>
                </div>
                <a href="<?php echo home_url('/property-dashboard/'); ?>" 
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
            <form method="post" action="" enctype="multipart/form-data" id="property-form" data-upload-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" data-upload-nonce="<?php echo esc_attr(wp_create_nonce('niesv_property_image_upload')); ?>">
                <?php wp_nonce_field('niesv_create_property', 'niesv_property_nonce'); ?>
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
                                       value="<?php echo isset($_POST['property_title']) ? esc_attr($_POST['property_title']) : ''; ?>">
                            </div>
                            
                            <div>
                                <label for="property_type" class="block text-sm font-medium text-gray-700 mb-2">
                                    Property Type *
                                </label>
                                <select id="property_type" 
                                        name="property_type" 
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent">
                                    <option value="residential" <?php selected(isset($_POST['property_type']) ? $_POST['property_type'] : '', 'residential'); ?>>Residential</option>
                                    <option value="commercial" <?php selected(isset($_POST['property_type']) ? $_POST['property_type'] : '', 'commercial'); ?>>Commercial</option>
                                    <option value="land" <?php selected(isset($_POST['property_type']) ? $_POST['property_type'] : '', 'land'); ?>>Land</option>
                                    <option value="industrial" <?php selected(isset($_POST['property_type']) ? $_POST['property_type'] : '', 'industrial'); ?>>Industrial</option>
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
                                    <option value="for_sale" <?php selected(isset($_POST['property_status']) ? $_POST['property_status'] : '', 'for_sale'); ?>>For Sale</option>
                                    <option value="for_rent" <?php selected(isset($_POST['property_status']) ? $_POST['property_status'] : '', 'for_rent'); ?>>For Rent</option>
                                    <option value="sold" <?php selected(isset($_POST['property_status']) ? $_POST['property_status'] : '', 'sold'); ?>>Sold</option>
                                    <option value="rented" <?php selected(isset($_POST['property_status']) ? $_POST['property_status'] : '', 'rented'); ?>>Rented</option>
                                </select>
                            </div>
                            <div id="rent-duration-field" class="hidden">
                                <label for="rent_duration" class="block text-sm font-medium text-gray-700 mb-2">Rent Duration *</label>
                                <select id="rent_duration" name="rent_duration" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent">
                                    <option value="">Select duration</option>
                                    <option value="monthly" <?php selected(isset($_POST['rent_duration']) ? $_POST['rent_duration'] : '', 'monthly'); ?>>Monthly</option>
                                    <option value="quarterly" <?php selected(isset($_POST['rent_duration']) ? $_POST['rent_duration'] : '', 'quarterly'); ?>>Quarterly</option>
                                    <option value="semi_annual" <?php selected(isset($_POST['rent_duration']) ? $_POST['rent_duration'] : '', 'semi_annual'); ?>>Semi-Annual</option>
                                    <option value="annual" <?php selected(isset($_POST['rent_duration']) ? $_POST['rent_duration'] : '', 'annual'); ?>>Annual</option>
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
                                       value="<?php echo isset($_POST['property_price']) ? esc_attr($_POST['property_price']) : ''; ?>">
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
                                       value="<?php echo isset($_POST['property_location']) ? esc_attr($_POST['property_location']) : ''; ?>">
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
                                       value="<?php echo isset($_POST['property_size']) ? esc_attr($_POST['property_size']) : ''; ?>">
                            </div>
                            
                            <div id="bedrooms-field">
                                <label for="property_bedrooms" class="block text-sm font-medium text-gray-700 mb-2">
                                    Bedrooms
                                </label>
                                <input type="number" 
                                       id="property_bedrooms" 
                                       name="property_bedrooms"
                                       min="0"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent"
                                       value="<?php echo isset($_POST['property_bedrooms']) ? esc_attr($_POST['property_bedrooms']) : ''; ?>">
                            </div>
                            
                            <div id="bathrooms-field">
                                <label for="property_bathrooms" class="block text-sm font-medium text-gray-700 mb-2">
                                    Bathrooms
                                </label>
                                <input type="number" 
                                       id="property_bathrooms" 
                                       name="property_bathrooms"
                                       min="0"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent"
                                       value="<?php echo isset($_POST['property_bathrooms']) ? esc_attr($_POST['property_bathrooms']) : ''; ?>">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div>
                        <label for="property_description" class="block text-sm font-medium text-gray-700 mb-2">
                            Property Description *
                        </label>
                        <textarea id="property_description" 
                                  name="property_description" 
                                  rows="6"
                                  required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent"
                                  placeholder="Describe the property features, amenities, titles such as (R of O, C of O)and other details..."><?php echo isset($_POST['property_description']) ? esc_textarea($_POST['property_description']) : ''; ?></textarea>
                    </div>
                    
                    <!-- Property Images -->
                    <div>
                        <label for="property_images" class="block text-sm font-medium text-gray-700 mb-2">
                            Property Images
                        </label>
                        <input type="file" 
                               id="property_images" 
                               accept="image/*"
                               multiple
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent">
                        <p class="text-sm text-gray-500 mt-1">Images upload one at a time after being resized. You can add up to 10; the first is the featured image.</p>
                        <p id="property-image-upload-status" class="text-sm text-gray-600 mt-2" aria-live="polite"></p>
                        <div id="property-image-ids"></div>
                        <div id="property-image-preview" class="grid grid-cols-3 sm:grid-cols-5 gap-3 mt-3"></div>
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
                                       value="<?php echo isset($_POST['agent_name']) ? esc_attr($_POST['agent_name']) : $current_user->display_name; ?>">
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
                                       value="<?php echo isset($_POST['agent_email']) ? esc_attr($_POST['agent_email']) : $current_user->user_email; ?>">
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
                                       value="<?php echo isset($_POST['agent_mobile']) ? esc_attr($_POST['agent_mobile']) : get_user_meta($current_user->ID, 'phone', true); ?>">
                            </div>
                            <div class="md:col-span-3">
                                <label for="contact_agent_link" class="block text-sm font-medium text-gray-700 mb-2">Contact Agent Link <span class="text-gray-500">(optional)</span></label>
                                <input type="url" id="contact_agent_link" name="contact_agent_link" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent" placeholder="https://example.com/contact-agent" value="<?php echo isset($_POST['contact_agent_link']) ? esc_attr($_POST['contact_agent_link']) : ''; ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Property Features -->
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Property Features</h2>
                        <div id="property-features" class="space-y-3">
                            <div class="flex gap-3">
                                <input type="text" name="property_features[]" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)]" placeholder="e.g., 24-hour security">
                                <button type="button" class="remove-feature hidden px-3 py-2 text-sm text-red-700 hover:text-red-900">Remove</button>
                            </div>
                        </div>
                        <button type="button" id="add-property-feature" class="mt-3 text-sm font-medium text-[var(--primary-color)] hover:underline">+ Add feature</button>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-4">
                        <a href="<?php echo home_url('/property-dashboard/'); ?>" 
                           class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                                name="submit_property"
                                class="px-6 py-2 bg-[var(--primary-color)] text-white rounded-md hover:bg-opacity-90 transition-colors font-medium">
                            Create Property
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const propertyType = document.getElementById('property_type');
    const propertyStatus = document.getElementById('property_status');
    const rentDuration = document.getElementById('rent-duration-field');
    const bedrooms = document.getElementById('bedrooms-field');
    const bathrooms = document.getElementById('bathrooms-field');
    function updateConditionalFields() {
        const isResidential = propertyType.value === 'residential';
        const isRental = propertyStatus.value === 'for_rent';
        bedrooms.classList.toggle('hidden', !isResidential);
        bathrooms.classList.toggle('hidden', !isResidential);
        rentDuration.classList.toggle('hidden', !isRental);
        document.getElementById('rent_duration').required = isRental;
    }
    propertyType.addEventListener('change', updateConditionalFields);
    propertyStatus.addEventListener('change', updateConditionalFields);
    updateConditionalFields();

    const features = document.getElementById('property-features');
    document.getElementById('add-property-feature').addEventListener('click', function () {
        const row = document.createElement('div');
        row.className = 'flex gap-3';
        row.innerHTML = '<input type="text" name="property_features[]" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)]" placeholder="e.g., 24-hour security"><button type="button" class="remove-feature px-3 py-2 text-sm text-red-700 hover:text-red-900">Remove</button>';
        features.appendChild(row);
    });
    features.addEventListener('click', function (event) {
        if (event.target.classList.contains('remove-feature')) event.target.parentElement.remove();
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('property-form');
    const picker = document.getElementById('property_images');
    const status = document.getElementById('property-image-upload-status');
    const ids = document.getElementById('property-image-ids');
    const preview = document.getElementById('property-image-preview');
    let uploading = false;

    function uploadedCount() {
        return ids.querySelectorAll('input[name="property_image_ids[]"]').length;
    }

    async function resizeImage(file) {
        return new Promise(function (resolve) {
            const image = new Image();
            const objectUrl = URL.createObjectURL(file);
            image.onload = function () {
                URL.revokeObjectURL(objectUrl);
                const maxSize = 1920;
                const scale = Math.min(1, maxSize / Math.max(image.width, image.height));
                const canvas = document.createElement('canvas');
                canvas.width = Math.round(image.width * scale);
                canvas.height = Math.round(image.height * scale);
                canvas.getContext('2d').drawImage(image, 0, 0, canvas.width, canvas.height);
                const isPng = file.type === 'image/png';
                canvas.toBlob(function (blob) {
                    resolve({
                        blob: blob || file,
                        name: file.name.replace(/\.[^.]+$/, '') + (isPng ? '.png' : '.jpg')
                    });
                }, isPng ? 'image/png' : 'image/jpeg', 0.82);
            };
            image.onerror = function () {
                URL.revokeObjectURL(objectUrl);
                resolve({blob: file, name: file.name});
            };
            image.src = objectUrl;
        });
    }

    function addUploadedImage(image) {
        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'property_image_ids[]';
        hidden.value = image.id;
        ids.appendChild(hidden);

        const item = document.createElement('div');
        item.className = 'relative';
        item.innerHTML = '<img src="' + image.url + '" alt="Uploaded property image" class="w-full h-20 object-cover rounded border border-gray-200"><button type="button" class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-red-600 text-white text-xs" aria-label="Remove image">×</button>';
        item.querySelector('button').addEventListener('click', function () {
            hidden.remove();
            item.remove();
        });
        preview.appendChild(item);
    }

    picker.addEventListener('change', async function () {
        const files = Array.from(picker.files || []);
        if (!files.length) return;
        if (uploadedCount() + files.length > 10) {
            status.textContent = 'You can add a maximum of 10 images.';
            picker.value = '';
            return;
        }
        uploading = true;
        for (let index = 0; index < files.length; index++) {
            status.textContent = 'Preparing and uploading image ' + (index + 1) + ' of ' + files.length + '…';
            try {
                const image = await resizeImage(files[index]);
                const data = new FormData();
                data.append('action', 'niesv_upload_property_image');
                data.append('nonce', form.dataset.uploadNonce);
                data.append('property_image', image.blob, image.name);
                const response = await fetch(form.dataset.uploadUrl, {method: 'POST', body: data, credentials: 'same-origin'});
                const responseBody = await response.text();
                let result;
                try {
                    result = JSON.parse(responseBody);
                } catch (parseError) {
                    throw new Error('The server timed out. Please try a smaller image.');
                }
                if (!response.ok || !result.success) throw new Error(result.data && result.data.message ? result.data.message : 'Upload failed.');
                addUploadedImage(result.data);
            } catch (error) {
                status.textContent = 'Could not upload ' + files[index].name + ': ' + error.message;
                uploading = false;
                picker.value = '';
                return;
            }
        }
        status.textContent = uploadedCount() + ' image' + (uploadedCount() === 1 ? '' : 's') + ' ready to submit.';
        uploading = false;
        picker.value = '';
    });

    form.addEventListener('submit', function (event) {
        if (uploading) {
            event.preventDefault();
            status.textContent = 'Please wait until image uploads are complete.';
        }
    });
});
</script>

<?php get_footer(); ?>
