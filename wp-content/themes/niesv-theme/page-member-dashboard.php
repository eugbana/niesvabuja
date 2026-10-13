<?php
/**
 * Template Name: Member Dashboard
 * Member-specific dashboard for verified members
 *
 * @package NIESV_Theme
 */

// Redirect if not logged in
if (!is_user_logged_in()) {
    wp_redirect(home_url('/login/'));
    exit;
}

$current_user = wp_get_current_user();
$membership_no = get_user_meta($current_user->ID, 'membership_no', true);
$membership_verified = get_user_meta($current_user->ID, 'membership_verified', true);

// Check if user has a membership number
if (empty($membership_no)) {
    wp_redirect(home_url('/register/'));
    exit;
}

// Get member information from member area
$member_info = null;
if (!empty($membership_no)) {
    $member_query = get_posts(array(
        'post_type' => 'member_area',
        'meta_query' => array(
            array(
                'key' => 'membership_no',
                'value' => $membership_no,
                'compare' => '='
            )
        ),
        'posts_per_page' => 1
    ));
    
    if (!empty($member_query)) {
        $member_info = $member_query[0];
        // Auto-verify only accounts that have never had a verification decision,
        // so an admin's "Unverify Membership" is not silently undone here.
        if (!metadata_exists('user', $current_user->ID, 'membership_verified')
            && get_field('membership_status', $member_info->ID) === 'active') {
            update_user_meta($current_user->ID, 'membership_verified', true);
            $membership_verified = true;
        }
    }
}

// Handle property deletion
niesv_handle_property_delete_request();

// Get user's properties
$user_properties = get_posts(array(
    'post_type' => 'properties',
    'author' => $current_user->ID,
    'post_status' => array('publish', 'draft', 'pending'),
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC'
));

get_header(); ?>

<main class="flex-1 bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Member Dashboard</h1>
                    <p class="text-gray-600 mt-1">Welcome back, <?php echo $current_user->display_name; ?>!</p>
                </div>
                <div class="flex items-center space-x-4">
                    <?php if ($membership_verified): ?>
                        <a href="<?php echo home_url('/add-property/'); ?>" 
                           class="bg-[var(--primary-color)] text-white px-4 py-2 rounded-md hover:bg-opacity-90 transition-colors font-medium">
                            Add New Property
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo wp_logout_url(home_url()); ?>" 
                       class="text-gray-600 hover:text-gray-900 transition-colors">
                        Logout
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Success Messages -->
        <?php if (isset($_GET['created']) && $_GET['created'] === '1') : ?>
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md mb-6">
                Property created successfully!
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['updated']) && $_GET['updated'] === '1') : ?>
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md mb-6">
                Property updated successfully!
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['deleted']) && $_GET['deleted'] === '1') : ?>
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md mb-6">
                Property deleted successfully.
            </div>
        <?php endif; ?>

        <!-- Membership Status Alert -->
        <?php if (!$membership_verified || !$member_info): ?>
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-md mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <div>
                        <h3 class="font-medium">Membership Verification Pending</h3>
                        <p class="text-sm mt-1">
                            Your membership number (<?php echo esc_html($membership_no); ?>) is being verified. 
                            Property management features will be available once verification is complete.
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Member Information Card -->
        <?php if ($member_info): ?>
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">Member Information</h2>
                    <button id="edit-profile-btn" class="text-[var(--primary-color)] hover:text-opacity-80 font-medium">
                        Edit Profile
                    </button>
                </div>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Membership Number</label>
                        <p class="text-gray-900 font-medium"><?php echo esc_html(get_field('membership_no', $member_info->ID)); ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Full Name</label>
                        <p class="text-gray-900"><?php echo esc_html(get_field('full_name', $member_info->ID)); ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Category</label>
                        <p class="text-gray-900">
                            <?php
                            $category = get_field('category', $member_info->ID);
                            $category_labels = array(
                                'fellow' => 'Fellow (FNIVS)',
                                'associate' => 'Associate (ANIVS)',
                                'graduate' => 'Graduate (GNIVS)',
                                'student' => 'Student Member',
                                'corporate' => 'Corporate Member',
                            );
                            echo esc_html($category_labels[$category] ?? ucfirst($category));
                            ?>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Branch</label>
                        <p class="text-gray-900"><?php echo esc_html(ucfirst(str_replace('_', ' ', get_field('branch', $member_info->ID)))); ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            <?php
                            $status = get_field('membership_status', $member_info->ID);
                            switch($status) {
                                case 'active':
                                    echo 'bg-green-100 text-green-800';
                                    break;
                                case 'inactive':
                                    echo 'bg-red-100 text-red-800';
                                    break;
                                case 'suspended':
                                    echo 'bg-yellow-100 text-yellow-800';
                                    break;
                                case 'pending':
                                    echo 'bg-blue-100 text-blue-800';
                                    break;
                                default:
                                    echo 'bg-gray-100 text-gray-800';
                            }
                            ?>">
                            <?php echo esc_html(ucfirst($status)); ?>
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                        <p class="text-gray-900"><?php echo esc_html(get_field('email_address', $member_info->ID)); ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Properties</p>
                        <p class="text-2xl font-semibold text-gray-900"><?php echo count($user_properties); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Published</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            <?php echo count(array_filter($user_properties, function($p) { return $p->post_status === 'publish'; })); ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pending</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            <?php echo count(array_filter($user_properties, function($p) { return $p->post_status === 'pending'; })); ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-gray-100 rounded-lg">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Drafts</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            <?php echo count(array_filter($user_properties, function($p) { return $p->post_status === 'draft'; })); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Properties Section -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900">My Properties</h2>
                    <?php if ($membership_verified): ?>
                        <a href="<?php echo home_url('/add-property/'); ?>" 
                           class="bg-[var(--primary-color)] text-white px-4 py-2 rounded-md hover:bg-opacity-90 transition-colors text-sm font-medium">
                            Add Property
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="p-6">
                <?php if (!empty($user_properties)): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($user_properties as $property): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <?php if (has_post_thumbnail($property->ID)): ?>
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img class="h-10 w-10 rounded-lg object-cover" 
                                                             src="<?php echo get_the_post_thumbnail_url($property->ID, 'thumbnail'); ?>" 
                                                             alt="<?php echo esc_attr($property->post_title); ?>">
                                                    </div>
                                                <?php endif; ?>
                                                <div class="<?php echo has_post_thumbnail($property->ID) ? 'ml-4' : ''; ?>">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <a href="<?php echo get_permalink($property->ID); ?>" class="hover:text-[var(--primary-color)]">
                                                            <?php echo esc_html($property->post_title); ?>
                                                        </a>
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        <?php echo date('M j, Y', strtotime($property->post_date)); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo esc_html(ucfirst(get_field('property_type', $property->ID) ?: 'N/A')); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo esc_html(get_field('property_location', $property->ID) ?: 'N/A'); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php 
                                            $price = get_field('property_price', $property->ID);
                                            echo $price ? '₦' . number_format($price) : 'N/A';
                                            ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                <?php
                                                switch($property->post_status) {
                                                    case 'publish':
                                                        echo 'bg-green-100 text-green-800';
                                                        break;
                                                    case 'pending':
                                                        echo 'bg-yellow-100 text-yellow-800';
                                                        break;
                                                    case 'draft':
                                                        echo 'bg-gray-100 text-gray-800';
                                                        break;
                                                    default:
                                                        echo 'bg-red-100 text-red-800';
                                                }
                                                ?>">
                                                <?php echo esc_html(ucfirst($property->post_status)); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="<?php echo add_query_arg('property_id', $property->ID, home_url('/edit-property/')); ?>"
                                               class="text-[var(--primary-color)] hover:text-opacity-80 mr-3">Edit</a>
                                            <a href="<?php echo get_permalink($property->ID); ?>"
                                               class="text-gray-600 hover:text-gray-900 mr-3" target="_blank">View</a>
                                            <a href="<?php echo esc_url(niesv_delete_property_url($property->ID)); ?>"
                                               class="text-red-600 hover:text-red-900"
                                               onclick="return confirm('Are you sure you want to delete this property? This action cannot be undone.')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011 1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No properties</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating your first property listing.</p>
                        <?php if ($membership_verified): ?>
                            <div class="mt-6">
                                <a href="<?php echo home_url('/add-property/'); ?>" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[var(--primary-color)] hover:bg-opacity-90">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Add Property
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<!-- Profile Edit Modal -->
<div id="profile-edit-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Edit Profile</h3>
                <button id="close-profile-modal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="profile-edit-form" method="post">
                <?php wp_nonce_field('niesv_update_profile', 'profile_nonce'); ?>
                <input type="hidden" name="action" value="update_profile">

                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="edit_first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                        <input type="text" id="edit_first_name" name="first_name"
                               value="<?php echo esc_attr($current_user->first_name); ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent">
                    </div>
                    <div>
                        <label for="edit_last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                        <input type="text" id="edit_last_name" name="last_name"
                               value="<?php echo esc_attr($current_user->last_name); ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="edit_email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" id="edit_email" name="email"
                           value="<?php echo esc_attr($current_user->user_email); ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent">
                </div>

                <div class="mb-4">
                    <label for="edit_current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                    <input type="password" id="edit_current_password" name="current_password" autocomplete="current-password"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Only needed if you change your email address.</p>
                </div>

                <div class="mb-4">
                    <label for="edit_phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <input type="tel" id="edit_phone" name="phone"
                           value="<?php echo esc_attr(get_user_meta($current_user->ID, 'phone', true)); ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent">
                </div>

                <div class="mb-6">
                    <label for="edit_bio" class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                    <textarea id="edit_bio" name="bio" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent"
                              placeholder="Tell us about yourself..."><?php echo esc_textarea(get_user_meta($current_user->ID, 'description', true)); ?></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" id="cancel-profile-edit"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-[var(--primary-color)] text-white rounded-md hover:bg-opacity-90 transition-colors">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editBtn = document.getElementById('edit-profile-btn');
    const modal = document.getElementById('profile-edit-modal');
    const closeBtn = document.getElementById('close-profile-modal');
    const cancelBtn = document.getElementById('cancel-profile-edit');
    const form = document.getElementById('profile-edit-form');

    // Open modal
    if (editBtn) {
        editBtn.addEventListener('click', function() {
            modal.classList.remove('hidden');
        });
    }

    // Close modal
    function closeModal() {
        modal.classList.add('hidden');
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
    }

    if (cancelBtn) {
        cancelBtn.addEventListener('click', closeModal);
    }

    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    // Handle form submission
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(form);

            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Profile updated successfully!');
                    location.reload();
                } else {
                    alert('Error updating profile: ' + data.data);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating your profile.');
            });
        });
    }
});
</script>

<?php get_footer(); ?>
