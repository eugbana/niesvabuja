<?php
/**
 * Setup Hero Data - Run this once to populate default hero slides
 * 
 * Instructions:
 * 1. Upload this file to your theme directory
 * 2. Visit: yoursite.com/wp-content/themes/niesv-theme/setup-hero-data.php
 * 3. This will create default hero slide data
 * 4. Delete this file after running
 */

// Load WordPress
require_once('../../../../wp-load.php');

// Check if user is admin
if (!current_user_can('manage_options')) {
    die('Access denied. You must be an administrator.');
}

echo '<h1>NIESV Hero Data Setup</h1>';

// Get or create front page
$front_page_id = get_option('page_on_front');

if (!$front_page_id) {
    // Create a front page
    $front_page = array(
        'post_title'    => 'Home',
        'post_content'  => '',
        'post_status'   => 'publish',
        'post_type'     => 'page',
        'post_name'     => 'home'
    );
    
    $front_page_id = wp_insert_post($front_page);
    
    if ($front_page_id) {
        // Set as front page
        update_option('page_on_front', $front_page_id);
        update_option('show_on_front', 'page');
        echo '<p>✅ Created and set front page (ID: ' . $front_page_id . ')</p>';
    } else {
        echo '<p>❌ Failed to create front page</p>';
        exit;
    }
} else {
    echo '<p>✅ Front page exists (ID: ' . $front_page_id . ')</p>';
}

// Check if hero slides already exist
$existing_slides = get_field('hero_slides', $front_page_id);
if ($existing_slides && !empty($existing_slides)) {
    echo '<p>⚠️ Hero slides already exist. Current slides:</p>';
    echo '<ul>';
    foreach ($existing_slides as $i => $slide) {
        echo '<li>Slide ' . ($i + 1) . ': ' . ($slide['slide_title'] ?? 'No title') . '</li>';
    }
    echo '</ul>';
    echo '<p><a href="?force=1">Click here to replace with default slides</a></p>';
    
    if (!isset($_GET['force'])) {
        exit;
    }
}

// Create default hero slides data
$hero_slides_data = array(
    array(
        'slide_title' => 'NIESV Abuja Branch',
        'slide_subtitle' => 'Setting the standard for estate surveying and valuation in the Federal Capital Territory.',
        'slide_button_text' => 'Explore Our World',
        'slide_button_link' => home_url('/about'),
        'slide_background_image' => array(
            'url' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80',
            'alt' => 'NIESV Building'
        )
    ),
    array(
        'slide_title' => 'Professional Excellence',
        'slide_subtitle' => 'Committed to maintaining the highest standards in estate surveying and valuation services.',
        'slide_button_text' => 'Our Services',
        'slide_button_link' => home_url('/services'),
        'slide_background_image' => array(
            'url' => 'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80',
            'alt' => 'Professional Services'
        )
    ),
    array(
        'slide_title' => 'Join Our Community',
        'slide_subtitle' => 'Connect with fellow professionals and advance your career in estate surveying.',
        'slide_button_text' => 'Become a Member',
        'slide_button_link' => home_url('/membership'),
        'slide_background_image' => array(
            'url' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80',
            'alt' => 'Community'
        )
    )
);

// Save the hero slides data
$result = update_field('hero_slides', $hero_slides_data, $front_page_id);

if ($result) {
    echo '<p>✅ Successfully created ' . count($hero_slides_data) . ' hero slides!</p>';
    echo '<h2>Created Slides:</h2>';
    echo '<ul>';
    foreach ($hero_slides_data as $i => $slide) {
        echo '<li><strong>Slide ' . ($i + 1) . ':</strong> ' . $slide['slide_title'] . '</li>';
    }
    echo '</ul>';
    
    echo '<h2>Next Steps:</h2>';
    echo '<ol>';
    echo '<li>Visit your website homepage to see the hero slides</li>';
    echo '<li>Go to WordPress Admin → Pages → Edit "Home" to customize the slides</li>';
    echo '<li>Delete this setup file for security</li>';
    echo '</ol>';
    
    echo '<p><a href="' . home_url() . '" target="_blank">View Your Homepage</a></p>';
    echo '<p><a href="' . admin_url('post.php?post=' . $front_page_id . '&action=edit') . '" target="_blank">Edit Front Page in Admin</a></p>';
    
} else {
    echo '<p>❌ Failed to save hero slides data</p>';
    echo '<p>Make sure ACF is installed and activated.</p>';
}

echo '<hr>';
echo '<p><small>You can safely delete this file after running it.</small></p>';
?>
