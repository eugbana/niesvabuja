<?php
/**
 * Template Name: Property Dashboard
 * Property management dashboard for logged-in users
 *
 * @package NIESV_Theme
 */

// Redirect if not logged in
if (!is_user_logged_in()) {
    wp_redirect(home_url('/login/'));
    exit;
}

$current_user = wp_get_current_user();

// Check membership verification status
$membership_no = get_user_meta($current_user->ID, 'membership_no', true);
$membership_verified = get_user_meta($current_user->ID, 'membership_verified', true);

// Redirect to member dashboard if user has membership number
if (!empty($membership_no)) {
    wp_redirect(home_url('/member-dashboard/'));
    exit;
}

// Handle property deletion
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['property_id'])) {
    $property_id = intval($_GET['property_id']);
    $property = get_post($property_id);
    
    // Check if user owns this property
    if ($property && $property->post_author == $current_user->ID && $property->post_type === 'properties') {
        wp_delete_post($property_id, true);
        wp_redirect(add_query_arg('deleted', '1', remove_query_arg(array('action', 'property_id'))));
        exit;
    }
}

// Get user's properties
$user_properties = get_posts(array(
    'post_type' => 'properties',
    'author' => $current_user->ID,
    'posts_per_page' => -1,
    'post_status' => array('publish', 'draft', 'pending')
));

get_header(); ?>

<main class="flex-1 bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Property Dashboard</h1>
                    <p class="text-gray-600 mt-1">Welcome back, <?php echo $current_user->display_name; ?>!</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="<?php echo home_url('/add-property/'); ?>" 
                       class="bg-[var(--primary-color)] text-white px-4 py-2 rounded-md hover:bg-opacity-90 transition-colors font-medium">
                        Add New Property
                    </a>
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

        <?php if (isset($_GET['error']) && $_GET['error'] === 'not_verified') : ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md mb-6">
                You must be a verified member to edit properties. Please contact the administrator.
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
                        <p class="text-sm font-medium text-gray-600">Draft</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            <?php echo count(array_filter($user_properties, function($p) { return $p->post_status === 'draft'; })); ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Views This Month</p>
                        <p class="text-2xl font-semibold text-gray-900">--</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Properties Table -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Your Properties</h2>
            </div>
            
            <?php if (empty($user_properties)) : ?>
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No properties yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating your first property listing.</p>
                    <div class="mt-6">
                        <a href="<?php echo home_url('/add-property/'); ?>" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[var(--primary-color)] hover:bg-opacity-90">
                            Add Property
                        </a>
                    </div>
                </div>
            <?php else : ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($user_properties as $property) : 
                                $property_price = get_field('property_price', $property->ID);
                                $property_type = get_field('property_type', $property->ID);
                                $property_status = get_field('property_status', $property->ID);
                                $property_location = get_field('property_location', $property->ID);
                                
                                // Format property type for display
                                $type_labels = array(
                                    'residential' => 'Residential',
                                    'commercial' => 'Commercial',
                                    'land' => 'Land',
                                    'industrial' => 'Industrial'
                                );
                                $type_display = isset($type_labels[$property_type]) ? $type_labels[$property_type] : ucfirst($property_type);
                                
                                // Format property status for display
                                $status_labels = array(
                                    'for_sale' => 'For Sale',
                                    'for_rent' => 'For Rent',
                                    'sold' => 'Sold',
                                    'rented' => 'Rented'
                                );
                                $status_display = isset($status_labels[$property_status]) ? $status_labels[$property_status] : ucfirst($property_status);
                                
                                // Status badge color
                                $status_color = 'bg-gray-100 text-gray-800';
                                if ($property_status == 'for_sale' || $property_status == 'for_rent') {
                                    $status_color = 'bg-green-100 text-green-800';
                                } elseif ($property_status == 'sold' || $property_status == 'rented') {
                                    $status_color = 'bg-blue-100 text-blue-800';
                                }
                                
                                // Post status badge
                                $post_status_color = 'bg-gray-100 text-gray-800';
                                if ($property->post_status == 'publish') {
                                    $post_status_color = 'bg-green-100 text-green-800';
                                } elseif ($property->post_status == 'draft') {
                                    $post_status_color = 'bg-yellow-100 text-yellow-800';
                                }
                            ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <?php if (has_post_thumbnail($property->ID)) : ?>
                                                    <img class="h-10 w-10 rounded-lg object-cover" 
                                                         src="<?php echo get_the_post_thumbnail_url($property->ID, 'thumbnail'); ?>" 
                                                         alt="<?php echo $property->post_title; ?>">
                                                <?php else : ?>
                                                    <div class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                                        </svg>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?php echo $property->post_title; ?>
                                                </div>
                                                <?php if ($property_location) : ?>
                                                    <div class="text-sm text-gray-500"><?php echo $property_location; ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo $type_display; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="space-y-1">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?php echo $status_color; ?>">
                                                <?php echo $status_display; ?>
                                            </span>
                                            <br>
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?php echo $post_status_color; ?>">
                                                <?php echo ucfirst($property->post_status); ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php if ($property_price) : ?>
                                            ₦<?php echo number_format($property_price); ?>
                                        <?php else : ?>
                                            --
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo date('M j, Y', strtotime($property->post_date)); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        <a href="<?php echo get_permalink($property->ID); ?>"
                                           class="text-blue-600 hover:text-blue-900" target="_blank">View</a>
                                        <a href="<?php echo add_query_arg('property_id', $property->ID, home_url('/edit-property/')); ?>"
                                           class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        <a href="<?php echo add_query_arg(array('action' => 'delete', 'property_id' => $property->ID)); ?>"
                                           class="text-red-600 hover:text-red-900"
                                           onclick="return confirm('Are you sure you want to delete this property? This action cannot be undone.')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>
