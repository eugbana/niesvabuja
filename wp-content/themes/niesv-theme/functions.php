<?php
/**
 * NIESV Theme Functions
 *
 * @package NIESV_Theme
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/includes/property-image-upload.php';

/**
 * Theme Setup
 */
function niesv_theme_setup() {
    // Add theme support for various features
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    add_theme_support('custom-logo');
    add_theme_support('customize-selective-refresh-widgets');

    // Add support for wide and full width blocks
    add_theme_support('align-wide');

    // Add support for editor styles
    add_theme_support('editor-styles');
    add_editor_style('editor-style.css');

    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'niesv-theme'),
        'footer' => __('Footer Menu', 'niesv-theme'),
    ));

    // Set content width
    if (!isset($content_width)) {
        $content_width = 1200;
    }
}
add_action('after_setup_theme', 'niesv_theme_setup');

/**
 * Enqueue Scripts and Styles
 */
function niesv_theme_scripts() {
    // Note: Using system fonts now, no need for Google Fonts
    // System font stack is defined in CSS variables for better performance

    // Enqueue Tailwind CSS from CDN
    wp_enqueue_style('niesv-tailwind',
        'https://cdn.tailwindcss.com?plugins=forms,container-queries',
        array(),
        '3.4.0'
    );

    // Enqueue theme stylesheet AFTER Tailwind
    wp_enqueue_style('niesv-style',
        get_stylesheet_uri(),
        array('niesv-tailwind'),
        wp_get_theme()->get('Version')
    );

    // Add inline CSS for Tailwind configuration
    $tailwind_config = "
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'niesv-primary': 'rgba(0, 0, 40, 1)',
                        'niesv-secondary': '#e7edf4',
                        'niesv-text-primary': '#0d141c',
                        'niesv-text-secondary': '#49739c',
                        'niesv-background': '#f8fafc',
                        'niesv-accent-gold': '#d4af37'
                    },
                    fontFamily: {
                        'newsreader': ['Newsreader', 'serif'],
                        'noto-sans': ['Noto Sans', 'sans-serif']
                    }
                }
            }
        }
    ";
    wp_add_inline_script('niesv-tailwind', $tailwind_config, 'before');

    // Enqueue theme JavaScript
    wp_enqueue_script('niesv-script',
        get_template_directory_uri() . '/assets/js/theme.js',
        array('jquery'),
        wp_get_theme()->get('Version'),
        true
    );

    // Enqueue PWA JavaScript
    wp_enqueue_script('niesv-pwa-script',
        get_template_directory_uri() . '/assets/js/pwa.js',
        array(),
        wp_get_theme()->get('Version'),
        true
    );

    // Localize script for AJAX
    wp_localize_script('niesv-script', 'niesv_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('niesv_nonce')
    ));

    // Localize PWA script
    wp_localize_script('niesv-pwa-script', 'niesv_pwa', array(
        'sw_url' => get_template_directory_uri() . '/sw.js',
        'manifest_url' => get_template_directory_uri() . '/manifest.json',
        'theme_url' => get_template_directory_uri(),
        'home_url' => home_url('/'),
        'offline_url' => home_url('/offline/'),
        'cache_version' => wp_get_theme()->get('Version')
    ));
}
add_action('wp_enqueue_scripts', 'niesv_theme_scripts');

/**
 * Enqueue admin scripts for ACF character counting
 */
function niesv_admin_scripts($hook) {
    if ('post.php' == $hook || 'post-new.php' == $hook) {
        wp_enqueue_script('niesv-admin-script', get_template_directory_uri() . '/js/admin.js', array('jquery'), '1.0.0', true);
    }
}
add_action('admin_enqueue_scripts', 'niesv_admin_scripts');

/**
 * Register Widget Areas
 */
function niesv_theme_widgets_init() {
    register_sidebar(array(
        'name'          => __('Sidebar', 'niesv-theme'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here.', 'niesv-theme'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => __('Footer Widget Area 1', 'niesv-theme'),
        'id'            => 'footer-1',
        'description'   => __('Add widgets here for footer column 1.', 'niesv-theme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => __('Footer Widget Area 2', 'niesv-theme'),
        'id'            => 'footer-2',
        'description'   => __('Add widgets here for footer column 2.', 'niesv-theme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => __('Footer Widget Area 3', 'niesv-theme'),
        'id'            => 'footer-3',
        'description'   => __('Add widgets here for footer column 3.', 'niesv-theme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => __('Footer Widget Area 4', 'niesv-theme'),
        'id'            => 'footer-4',
        'description'   => __('Add widgets here for footer column 4.', 'niesv-theme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'niesv_theme_widgets_init');

/**
 * Custom Post Types
 */
function niesv_register_post_types() {
    // News Post Type
    register_post_type('news', array(
        'labels' => array(
            'name' => __('News', 'niesv-theme'),
            'singular_name' => __('News Article', 'niesv-theme'),
            'add_new' => __('Add New Article', 'niesv-theme'),
            'add_new_item' => __('Add New News Article', 'niesv-theme'),
            'edit_item' => __('Edit News Article', 'niesv-theme'),
            'new_item' => __('New News Article', 'niesv-theme'),
            'view_item' => __('View News Article', 'niesv-theme'),
            'search_items' => __('Search News', 'niesv-theme'),
            'not_found' => __('No news articles found', 'niesv-theme'),
            'not_found_in_trash' => __('No news articles found in trash', 'niesv-theme'),
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-admin-post',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'author', 'comments'),
        'rewrite' => array('slug' => 'news'),
        'show_in_rest' => true,
    ));

    // Events Post Type
    register_post_type('events', array(
        'labels' => array(
            'name' => __('Events', 'niesv-theme'),
            'singular_name' => __('Event', 'niesv-theme'),
            'add_new' => __('Add New Event', 'niesv-theme'),
            'add_new_item' => __('Add New Event', 'niesv-theme'),
            'edit_item' => __('Edit Event', 'niesv-theme'),
            'new_item' => __('New Event', 'niesv-theme'),
            'view_item' => __('View Event', 'niesv-theme'),
            'search_items' => __('Search Events', 'niesv-theme'),
            'not_found' => __('No events found', 'niesv-theme'),
            'not_found_in_trash' => __('No events found in trash', 'niesv-theme'),
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'rewrite' => array('slug' => 'events'),
        'show_in_rest' => true,
    ));

    // Properties Post Type
    register_post_type('properties', array(
        'labels' => array(
            'name' => __('Properties', 'niesv-theme'),
            'singular_name' => __('Property', 'niesv-theme'),
            'add_new' => __('Add New Property', 'niesv-theme'),
            'add_new_item' => __('Add New Property', 'niesv-theme'),
            'edit_item' => __('Edit Property', 'niesv-theme'),
            'new_item' => __('New Property', 'niesv-theme'),
            'view_item' => __('View Property', 'niesv-theme'),
            'search_items' => __('Search Properties', 'niesv-theme'),
            'not_found' => __('No properties found', 'niesv-theme'),
            'not_found_in_trash' => __('No properties found in trash', 'niesv-theme'),
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-building',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'rewrite' => array('slug' => 'properties'),
        'show_in_rest' => true,
    ));

    // Team Members Post Type
    register_post_type('team_members', array(
        'labels' => array(
            'name' => __('Team Members', 'niesv-theme'),
            'singular_name' => __('Team Member', 'niesv-theme'),
            'add_new' => __('Add New Member', 'niesv-theme'),
            'add_new_item' => __('Add New Team Member', 'niesv-theme'),
            'edit_item' => __('Edit Team Member', 'niesv-theme'),
            'new_item' => __('New Team Member', 'niesv-theme'),
            'view_item' => __('View Team Member', 'niesv-theme'),
            'search_items' => __('Search Team Members', 'niesv-theme'),
            'not_found' => __('No team members found', 'niesv-theme'),
            'not_found_in_trash' => __('No team members found in trash', 'niesv-theme'),
        ),
        'public' => true,
        'has_archive' => false,
        'menu_icon' => 'dashicons-groups',
        'supports' => array('title', 'editor', 'thumbnail'),
        'rewrite' => array('slug' => 'team'),
        'show_in_rest' => true,
    ));

    // Gallery Post Type
    register_post_type('gallery', array(
        'labels' => array(
            'name' => __('Gallery Albums', 'niesv-theme'),
            'singular_name' => __('Gallery Album', 'niesv-theme'),
            'add_new' => __('Add New Album', 'niesv-theme'),
            'add_new_item' => __('Add New Gallery Album', 'niesv-theme'),
            'edit_item' => __('Edit Gallery Album', 'niesv-theme'),
            'new_item' => __('New Gallery Album', 'niesv-theme'),
            'view_item' => __('View Gallery Album', 'niesv-theme'),
            'search_items' => __('Search Gallery Albums', 'niesv-theme'),
            'not_found' => __('No gallery albums found', 'niesv-theme'),
            'not_found_in_trash' => __('No gallery albums found in trash', 'niesv-theme'),
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-format-gallery',
        'supports' => array('title', 'editor', 'thumbnail'),
        'rewrite' => array('slug' => 'gallery'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'niesv_register_post_types', 0); // High priority to ensure post types are registered early

/**
 * Flush rewrite rules on theme activation to ensure gallery URLs work
 */
function niesv_flush_rewrite_rules() {
    niesv_register_post_types();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'niesv_flush_rewrite_rules');

/**
 * Debug template loading for gallery posts
 */
function niesv_debug_template_loading($template) {
    global $wp_query;

    if (is_singular('gallery')) {
        error_log('NIESV: Loading gallery single template: ' . $template);
        error_log('NIESV: Post ID: ' . get_the_ID());
        error_log('NIESV: Post type: ' . get_post_type());
    }

    if (is_post_type_archive('gallery')) {
        error_log('NIESV: Loading gallery archive template: ' . $template);
    }

    return $template;
}
add_filter('template_include', 'niesv_debug_template_loading');

/**
 * Force rewrite rules flush when needed
 */
function niesv_check_rewrite_rules() {
    $rules = get_option('rewrite_rules');
    $gallery_rule_exists = false;

    if ($rules) {
        foreach ($rules as $pattern => $replacement) {
            if (strpos($pattern, 'gallery') !== false && strpos($replacement, 'gallery') !== false) {
                $gallery_rule_exists = true;
                break;
            }
        }
    }

    if (!$gallery_rule_exists) {
        flush_rewrite_rules();
        error_log('NIESV: Rewrite rules flushed due to missing gallery rules');
    }
}
add_action('wp_loaded', 'niesv_check_rewrite_rules');

/**
 * Ensure gallery post type query vars are recognized
 */
function niesv_add_gallery_query_vars($vars) {
    $vars[] = 'gallery';
    return $vars;
}
add_filter('query_vars', 'niesv_add_gallery_query_vars');

/**
 * Add ACF Options Page
 */
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title' => 'Theme General Settings',
        'menu_title' => 'Theme Options',
        'menu_slug' => 'general-settings',
        'capability' => 'edit_posts',
        'icon_url' => 'dashicons-admin-customizer',
    ));

    acf_add_options_sub_page(array(
        'page_title' => 'Header Settings',
        'menu_title' => 'Header',
        'parent_slug' => 'general-settings',
    ));

    acf_add_options_sub_page(array(
        'page_title' => 'Footer Settings',
        'menu_title' => 'Footer',
        'parent_slug' => 'general-settings',
    ));

    acf_add_options_sub_page(array(
        'page_title' => 'PWA Notifications',
        'menu_title' => 'PWA Notifications',
        'parent_slug' => 'general-settings',
    ));
}

/**
 * Custom Image Sizes
 */
function niesv_custom_image_sizes() {
    add_image_size('news-thumbnail', 400, 300, true);
    add_image_size('event-thumbnail', 350, 250, true);
    add_image_size('property-thumbnail', 400, 300, true);
    add_image_size('team-member', 300, 300, true);
    add_image_size('hero-image', 1920, 800, true);
    add_image_size('gallery-thumbnail', 400, 320, true);
    add_image_size('gallery-detail', 300, 240, true);
}
add_action('after_setup_theme', 'niesv_custom_image_sizes');

/**
 * Excerpt Length
 */
function niesv_excerpt_length($length) {
    return 25;
}
add_filter('excerpt_length', 'niesv_excerpt_length');

/**
 * Excerpt More
 */
function niesv_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'niesv_excerpt_more');

/**
 * Check if ACF is active and show admin notice if not
 */
function niesv_check_acf() {
    if (!class_exists('ACF') && !function_exists('get_field')) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p><strong>NIESV Theme:</strong> Advanced Custom Fields (ACF) plugin is required for this theme to work properly. Please install and activate ACF.</p></div>';
        });
    }
}
add_action('admin_init', 'niesv_check_acf');

/**
 * Hide WordPress admin bar for non-admin users
 */
function niesv_hide_admin_bar_for_non_admins() {
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}
add_action('after_setup_theme', 'niesv_hide_admin_bar_for_non_admins');

/**
 * ACF Field Groups
 *
 * IMPORTANT: This function registers LOCAL (code-defined) field groups that do NOT save to the database.
 * The static guard prevents multiple executions per request, which is critical to avoid database duplication.
 *
 * Hook: acf/init - The correct ACF lifecycle hook for registering local field groups
 * Static Guard: Prevents this function from executing more than once per request
 * Local Registration: acf_add_local_field_group() stores field groups in memory, NOT in the database
 *
 * DO NOT use acf_get_field_group() or acf_update_field_group() on local field groups as this
 * will query and save to the database, creating duplicate entries.
 */
function niesv_register_acf_fields() {
    // Static guard: Prevent multiple executions per request
    // This is critical because acf/init can fire multiple times during admin page loads
    static $registered = false;
    if ($registered) {
        return;
    }
    $registered = true;

    // Check if ACF is available
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    // Theme Options Field Group
    acf_add_local_field_group(array(
        'key' => 'group_theme_options',
        'title' => 'Theme Options',
        'fields' => array(
            array(
                'key' => 'field_site_title',
                'label' => 'Site Title',
                'name' => 'site_title',
                'type' => 'text',
                'default_value' => 'NIESV ABUJA',
            ),
            array(
                'key' => 'field_hero_title',
                'label' => 'Hero Title',
                'name' => 'hero_title',
                'type' => 'text',
                'default_value' => 'NIESV Abuja Branch',
            ),
            array(
                'key' => 'field_hero_subtitle',
                'label' => 'Hero Subtitle',
                'name' => 'hero_subtitle',
                'type' => 'textarea',
                'default_value' => 'Setting the standard for estate surveying and valuation in the Federal Capital Territory.',
            ),
            array(
                'key' => 'field_hero_background_image',
                'label' => 'Hero Background Image',
                'name' => 'hero_background_image',
                'type' => 'image',
                'return_format' => 'url',
            ),
            array(
                'key' => 'field_hero_button_text',
                'label' => 'Hero Button Text',
                'name' => 'hero_button_text',
                'type' => 'text',
                'default_value' => 'Explore Our World',
            ),
            array(
                'key' => 'field_hero_button_link',
                'label' => 'Hero Button Link',
                'name' => 'hero_button_link',
                'type' => 'url',
            ),
            array(
                'key' => 'field_show_login_button',
                'label' => 'Show Login Button',
                'name' => 'show_login_button',
                'type' => 'true_false',
                'default_value' => 1,
            ),
            array(
                'key' => 'field_login_button_text',
                'label' => 'Login Button Text',
                'name' => 'login_button_text',
                'type' => 'text',
                'default_value' => 'Login',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_login_button',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_login_button_link',
                'label' => 'Login Button Link',
                'name' => 'login_button_link',
                'type' => 'url',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_login_button',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'general-settings',
                ),
            ),
        ),
    ));

    // Updates Section Field Group
    acf_add_local_field_group(array(
        'key' => 'group_updates_section',
        'title' => 'Important Updates Section',
        'fields' => array(
            array(
                'key' => 'field_show_updates_section',
                'label' => 'Show Updates Section',
                'name' => 'show_updates_section',
                'type' => 'true_false',
                'default_value' => 1,
            ),
            array(
                'key' => 'field_updates_section_title',
                'label' => 'Section Title',
                'name' => 'updates_section_title',
                'type' => 'text',
                'default_value' => 'Important Updates',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_updates_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_update_title',
                'label' => 'Update Title',
                'name' => 'update_title',
                'type' => 'text',
                'default_value' => 'Mandatory Professional Development Course',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_updates_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_update_content',
                'label' => 'Update Content',
                'name' => 'update_content',
                'type' => 'wysiwyg',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_updates_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_update_button_text',
                'label' => 'Button Text',
                'name' => 'update_button_text',
                'type' => 'text',
                'default_value' => 'Register Now',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_updates_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_update_button_link',
                'label' => 'Button Link',
                'name' => 'update_button_link',
                'type' => 'url',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_updates_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'general-settings',
                ),
            ),
        ),
    ));

    // Events Field Group
    acf_add_local_field_group(array(
        'key' => 'group_events',
        'title' => 'Event Details',
        'fields' => array(
            array(
                'key' => 'field_event_date',
                'label' => 'Event Date',
                'name' => 'event_date',
                'type' => 'date_picker',
                'display_format' => 'F j, Y',
                'return_format' => 'Y-m-d',
                'first_day' => 1,
                'required' => 1,
            ),
            array(
                'key' => 'field_event_time',
                'label' => 'Event Time',
                'name' => 'event_time',
                'type' => 'text',
                'placeholder' => 'e.g., 9:00 AM - 5:00 PM',
            ),
            array(
                'key' => 'field_event_location',
                'label' => 'Event Location',
                'name' => 'event_location',
                'type' => 'text',
                'placeholder' => 'e.g., NIESV Secretariat, Abuja',
            ),
            array(
                'key' => 'field_event_type',
                'label' => 'Event Type',
                'name' => 'event_type',
                'type' => 'select',
                'choices' => array(
                    'conference' => 'Conference',
                    'workshop' => 'Workshop',
                    'seminar' => 'Seminar',
                    'meeting' => 'Meeting',
                    'networking' => 'Networking Event',
                    'training' => 'Training',
                ),
                'default_value' => 'conference',
            ),
            array(
                'key' => 'field_event_organizer',
                'label' => 'Event Organizer',
                'name' => 'event_organizer',
                'type' => 'text',
                'default_value' => 'NIESV Abuja Branch',
            ),
            array(
                'key' => 'field_event_capacity',
                'label' => 'Event Capacity',
                'name' => 'event_capacity',
                'type' => 'number',
                'placeholder' => 'Maximum number of attendees',
            ),
            array(
                'key' => 'field_event_price',
                'label' => 'Event Price',
                'name' => 'event_price',
                'type' => 'text',
                'placeholder' => 'Enter "free" or amount in Naira',
                'default_value' => 'free',
            ),
            array(
                'key' => 'field_registration_link',
                'label' => 'Registration Link',
                'name' => 'registration_link',
                'type' => 'url',
            ),
            array(
                'key' => 'field_featured_event',
                'label' => 'Featured Event with Countdown',
                'name' => 'featured_event',
                'type' => 'true_false',
                'instructions' => 'Check this to display this event on the front page with a countdown timer',
                'default_value' => 0,
            ),
            array(
                'key' => 'field_event_datetime',
                'label' => 'Event Date & Time (for countdown)',
                'name' => 'event_datetime',
                'type' => 'date_time_picker',
                'display_format' => 'F j, Y g:i a',
                'return_format' => 'Y-m-d H:i:s',
                'first_day' => 1,
                'instructions' => 'Set the exact date and time for the countdown timer',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_featured_event',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
                'placeholder' => 'Link to registration form or page',
            ),
            array(
                'key' => 'field_is_featured_event',
                'label' => 'Featured Event',
                'name' => 'is_featured_event',
                'type' => 'true_false',
                'message' => 'Mark this event as featured',
            ),
            array(
                'key' => 'field_event_gallery',
                'label' => 'Event Gallery',
                'name' => 'event_gallery',
                'type' => 'repeater',
                'sub_fields' => array(
                    array(
                        'key' => 'field_gallery_image',
                        'label' => 'Image',
                        'name' => 'image',
                        'type' => 'image',
                        'return_format' => 'array',
                    ),
                ),
                'min' => 0,
                'layout' => 'table',
                'button_label' => 'Add Image',
            ),
            array(
                'key' => 'field_event_agenda',
                'label' => 'Event Agenda',
                'name' => 'event_agenda',
                'type' => 'repeater',
                'sub_fields' => array(
                    array(
                        'key' => 'field_session_time',
                        'label' => 'Time',
                        'name' => 'session_time',
                        'type' => 'text',
                        'placeholder' => 'e.g., 9:00 AM',
                    ),
                    array(
                        'key' => 'field_session_title',
                        'label' => 'Session Title',
                        'name' => 'session_title',
                        'type' => 'text',
                    ),
                    array(
                        'key' => 'field_session_description',
                        'label' => 'Description',
                        'name' => 'session_description',
                        'type' => 'textarea',
                    ),
                    array(
                        'key' => 'field_speaker_name',
                        'label' => 'Speaker Name',
                        'name' => 'speaker_name',
                        'type' => 'text',
                    ),
                ),
                'min' => 0,
                'layout' => 'row',
                'button_label' => 'Add Session',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'events',
                ),
            ),
        ),
    ));

    // News Field Group
    acf_add_local_field_group(array(
        'key' => 'group_news',
        'title' => 'News Details',
        'fields' => array(
            array(
                'key' => 'field_news_category',
                'label' => 'News Category',
                'name' => 'news_category',
                'type' => 'select',
                'choices' => array(
                    'announcement' => 'Announcement',
                    'industry_news' => 'Industry News',
                    'branch_news' => 'Branch News',
                    'regulation' => 'Regulation',
                    'market_update' => 'Market Update',
                    'achievement' => 'Achievement',
                ),
                'default_value' => 'announcement',
            ),
            array(
                'key' => 'field_is_featured_news',
                'label' => 'Featured News',
                'name' => 'is_featured_news',
                'type' => 'true_false',
                'message' => 'Mark this news as featured',
            ),
            array(
                'key' => 'field_news_listing_image',
                'label' => 'Listing Image',
                'name' => 'news_listing_image',
                'type' => 'image',
                'instructions' => 'Image to display in news cards and listings. If not set, featured image will be used.',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
            ),
            array(
                'key' => 'field_article_author',
                'label' => 'Article Author',
                'name' => 'article_author',
                'type' => 'text',
                'instructions' => 'Override the default post author name',
                'placeholder' => 'Leave empty to use post author',
            ),
            array(
                'key' => 'field_article_images',
                'label' => 'Article Images',
                'name' => 'article_images',
                'type' => 'gallery',
                'instructions' => 'Additional images to display within the article content',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
                'max' => 3,
            ),
            array(
                'key' => 'field_additional_content',
                'label' => 'Additional Content',
                'name' => 'additional_content',
                'type' => 'wysiwyg',
                'instructions' => 'Content to display after the article images (if any)',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
                'delay' => 0,
            ),
            array(
                'key' => 'field_news_source',
                'label' => 'News Source',
                'name' => 'news_source',
                'type' => 'text',
                'placeholder' => 'Source of the news (optional)',
            ),
            array(
                'key' => 'field_external_link',
                'label' => 'External Link',
                'name' => 'external_link',
                'type' => 'url',
                'placeholder' => 'Link to external article (optional)',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'news',
                ),
            ),
        ),
    ));

    // Properties Field Group
    acf_add_local_field_group(array(
        'key' => 'group_properties',
        'title' => 'Property Details',
        'fields' => array(
            array(
                'key' => 'field_property_price',
                'label' => 'Property Price',
                'name' => 'property_price',
                'type' => 'number',
                'placeholder' => 'Price in Naira',
                'required' => 1,
            ),
            array(
                'key' => 'field_property_type',
                'label' => 'Property Type',
                'name' => 'property_type',
                'type' => 'select',
                'choices' => array(
                    'residential' => 'Residential',
                    'commercial' => 'Commercial',
                    'land' => 'Land',
                    'industrial' => 'Industrial',
                ),
                'default_value' => 'residential',
                'required' => 1,
            ),
            array(
                'key' => 'field_property_status',
                'label' => 'Property Status',
                'name' => 'property_status',
                'type' => 'select',
                'choices' => array(
                    'for_sale' => 'For Sale',
                    'for_rent' => 'For Rent',
                    'sold' => 'Sold',
                    'rented' => 'Rented',
                ),
                'default_value' => 'for_sale',
                'required' => 1,
            ),
            array(
                'key' => 'field_rent_duration',
                'label' => 'Rent Duration',
                'name' => 'rent_duration',
                'type' => 'select',
                'choices' => array(
                    'monthly' => 'Monthly',
                    'quarterly' => 'Quarterly',
                    'semi_annual' => 'Semi-Annual',
                    'annual' => 'Annual',
                ),
                'default_value' => 'monthly',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_property_status',
                            'operator' => '==',
                            'value' => 'for_rent',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_property_location',
                'label' => 'Property Location',
                'name' => 'property_location',
                'type' => 'text',
                'placeholder' => 'e.g., Maitama, Abuja',
                'required' => 1,
            ),
            array(
                'key' => 'field_property_size',
                'label' => 'Property Size',
                'name' => 'property_size',
                'type' => 'text',
                'placeholder' => 'e.g., 500 sqm, 2 hectares',
            ),
            array(
                'key' => 'field_property_bedrooms',
                'label' => 'Number of Bedrooms',
                'name' => 'property_bedrooms',
                'type' => 'number',
                'min' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_property_type',
                            'operator' => '==',
                            'value' => 'residential',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_property_bathrooms',
                'label' => 'Number of Bathrooms',
                'name' => 'property_bathrooms',
                'type' => 'number',
                'min' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_property_type',
                            'operator' => '==',
                            'value' => 'residential',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_agent_name',
                'label' => 'Agent Name',
                'name' => 'agent_name',
                'type' => 'text',
                'placeholder' => 'e.g., John Doe',
                'required' => 1,
            ),
            array(
                'key' => 'field_agent_email',
                'label' => 'Agent Email',
                'name' => 'agent_email',
                'type' => 'email',
                'placeholder' => 'agent@example.com',
                'required' => 1,
            ),
            array(
                'key' => 'field_agent_mobile',
                'label' => 'Agent Mobile Phone',
                'name' => 'agent_mobile',
                'type' => 'text',
                'placeholder' => 'e.g., +234 801 234 5678',
                'required' => 1,
            ),
            array(
                'key' => 'field_contact_agent_link',
                'label' => 'Contact Agent Link (Optional)',
                'name' => 'contact_agent_link',
                'type' => 'url',
                'placeholder' => 'Link to contact form or agent details',
            ),
            array(
                'key' => 'field_property_description',
                'label' => 'Property Description',
                'name' => 'property_description',
                'type' => 'textarea',
                'instructions' => 'Detailed description of the property including amenities, location benefits, and other important details.',
                'placeholder' => 'Describe the property in detail...',
                'rows' => 6,
                'required' => 1,
            ),
            array(
                'key' => 'field_property_images',
                'label' => 'Property Images',
                'name' => 'property_images',
                'type' => 'gallery',
                'instructions' => 'Upload property images. Maximum 10 images allowed. First image will be used as the featured image.',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
                'max' => 10,
                'required' => 0,
            ),
            array(
                'key' => 'field_property_features',
                'label' => 'Property Features',
                'name' => 'property_features',
                'type' => 'repeater',
                'sub_fields' => array(
                    array(
                        'key' => 'field_feature_name',
                        'label' => 'Feature',
                        'name' => 'feature_name',
                        'type' => 'text',
                    ),
                ),
                'min' => 0,
                'layout' => 'table',
                'button_label' => 'Add Feature',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'properties',
                ),
            ),
        ),
    ));

    // Team Members Field Group
    acf_add_local_field_group(array(
        'key' => 'group_team_members',
        'title' => 'Team Member Details',
        'fields' => array(
            array(
                'key' => 'field_member_profile_image',
                'label' => 'Profile Image',
                'name' => 'member_profile_image',
                'type' => 'image',
                'instructions' => 'Upload a profile photo for this team member. Recommended size: 400x400px or larger, square format.',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
                'required' => 0,
            ),
            array(
                'key' => 'field_member_name',
                'label' => 'Full Name',
                'name' => 'member_name',
                'type' => 'text',
                'instructions' => 'Enter the full name of the team member. If left empty, the post title will be used.',
                'placeholder' => 'e.g., John Doe',
                'required' => 0,
            ),
            array(
                'key' => 'field_member_position',
                'label' => 'Position/Title',
                'name' => 'member_position',
                'type' => 'text',
                'placeholder' => 'e.g., Chairman, Secretary',
                'required' => 1,
            ),
            array(
                'key' => 'field_member_bio',
                'label' => 'Biography',
                'name' => 'member_bio',
                'type' => 'wysiwyg',
                'toolbar' => 'basic',
            ),
            array(
                'key' => 'field_member_qualifications',
                'label' => 'Qualifications',
                'name' => 'member_qualifications',
                'type' => 'textarea',
                'placeholder' => 'Professional qualifications and certifications',
            ),
            array(
                'key' => 'field_member_email',
                'label' => 'Email Address',
                'name' => 'member_email',
                'type' => 'email',
            ),
            array(
                'key' => 'field_member_phone',
                'label' => 'Phone Number',
                'name' => 'member_phone',
                'type' => 'text',
            ),
            array(
                'key' => 'field_member_linkedin',
                'label' => 'LinkedIn Profile',
                'name' => 'member_linkedin',
                'type' => 'url',
            ),
            array(
                'key' => 'field_is_leadership',
                'label' => 'Is Leadership Member',
                'name' => 'is_leadership',
                'type' => 'true_false',
                'default_value' => 0,
                'instructions' => 'Check this if this member is part of the leadership team',
            ),
            array(
                'key' => 'field_social_links',
                'label' => 'Social Media Links',
                'name' => 'social_links',
                'type' => 'repeater',
                'sub_fields' => array(
                    array(
                        'key' => 'field_social_platform',
                        'label' => 'Platform',
                        'name' => 'social_platform',
                        'type' => 'select',
                        'choices' => array(
                            'twitter' => 'Twitter',
                            'linkedin' => 'LinkedIn',
                            'facebook' => 'Facebook',
                            'instagram' => 'Instagram',
                            'email' => 'Email',
                        ),
                    ),
                    array(
                        'key' => 'field_social_url',
                        'label' => 'URL',
                        'name' => 'social_url',
                        'type' => 'url',
                        'required' => 1,
                    ),
                ),
                'max' => 5,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'team_members',
                ),
            ),
        ),
    ));

    // About Page Field Group
    acf_add_local_field_group(array(
        'key' => 'group_about_page',
        'title' => 'About Page Content',
        'fields' => array(
            array(
                'key' => 'field_page_title',
                'label' => 'Page Title',
                'name' => 'page_title',
                'type' => 'text',
                'placeholder' => 'Override default page title',
            ),
            array(
                'key' => 'field_page_subtitle',
                'label' => 'Page Subtitle',
                'name' => 'page_subtitle',
                'type' => 'textarea',
            ),
            array(
                'key' => 'field_show_commitment_section',
                'label' => 'Show Commitment Section',
                'name' => 'show_commitment_section',
                'type' => 'true_false',
                'default_value' => 1,
            ),
            array(
                'key' => 'field_commitment_title',
                'label' => 'Commitment Section Title',
                'name' => 'commitment_title',
                'type' => 'text',
                'default_value' => 'Our Commitment to Excellence',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_commitment_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_commitment_content',
                'label' => 'Commitment Content',
                'name' => 'commitment_content',
                'type' => 'wysiwyg',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_commitment_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_commitment_image',
                'label' => 'Commitment Image',
                'name' => 'commitment_image',
                'type' => 'image',
                'return_format' => 'url',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_commitment_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_commitment_button_text',
                'label' => 'Commitment Button Text',
                'name' => 'commitment_button_text',
                'type' => 'text',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_commitment_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_commitment_button_link',
                'label' => 'Commitment Button Link',
                'name' => 'commitment_button_link',
                'type' => 'url',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_commitment_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_show_community_section',
                'label' => 'Show Community Section',
                'name' => 'show_community_section',
                'type' => 'true_false',
                'default_value' => 1,
            ),
            array(
                'key' => 'field_community_title',
                'label' => 'Community Section Title',
                'name' => 'community_title',
                'type' => 'text',
                'default_value' => 'Community and Advocacy',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_community_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_community_content',
                'label' => 'Community Content',
                'name' => 'community_content',
                'type' => 'wysiwyg',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_community_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_community_image',
                'label' => 'Community Image',
                'name' => 'community_image',
                'type' => 'image',
                'return_format' => 'url',
                'preview_size' => 'medium',
                'library' => 'all',
                'instructions' => 'Upload an image that represents community engagement and advocacy work.',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_community_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_community_image_alt',
                'label' => 'Community Image Alt Text',
                'name' => 'community_image_alt',
                'type' => 'text',
                'placeholder' => 'Describe the image for accessibility (e.g., "NIESV members engaging with community leaders")',
                'instructions' => 'Provide descriptive alt text for the community image to improve accessibility.',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_community_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_community_button_text',
                'label' => 'Community Button Text',
                'name' => 'community_button_text',
                'type' => 'text',
                'placeholder' => 'e.g., "Learn More About Our Community Work"',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_community_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_community_button_link',
                'label' => 'Community Button Link',
                'name' => 'community_button_link',
                'type' => 'url',
                'placeholder' => 'https://example.com/community',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_community_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_show_mvv_section',
                'label' => 'Show Mission/Vision/Values Section',
                'name' => 'show_mvv_section',
                'type' => 'true_false',
                'default_value' => 1,
            ),
            array(
                'key' => 'field_mvv_main_title',
                'label' => 'Mission/Vision/Values Main Title',
                'name' => 'mvv_main_title',
                'type' => 'text',
                'default_value' => 'Our Foundation',
                'placeholder' => 'Main heading for the Mission/Vision/Values section',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_mvv_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_mvv_main_subtitle',
                'label' => 'Mission/Vision/Values Subtitle',
                'name' => 'mvv_main_subtitle',
                'type' => 'textarea',
                'default_value' => 'Discover the core principles that guide NIESV Abuja Branch in our commitment to excellence in estate surveying and valuation.',
                'placeholder' => 'Subtitle/description for the Mission/Vision/Values section',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_mvv_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_mission_title',
                'label' => 'Mission Section Title',
                'name' => 'mission_title',
                'type' => 'text',
                'default_value' => 'Our Mission',
                'placeholder' => 'Title for the Mission card',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_mvv_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_mission_statement',
                'label' => 'Mission Statement',
                'name' => 'mission_statement',
                'type' => 'textarea',
                'maxlength' => 200,
                'placeholder' => 'Enter mission statement (max 200 characters)',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_mvv_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_mission_link',
                'label' => 'Mission Statement Link',
                'name' => 'mission_link',
                'type' => 'url',
                'placeholder' => 'Optional link for mission statement',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_mvv_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_vision_title',
                'label' => 'Vision Section Title',
                'name' => 'vision_title',
                'type' => 'text',
                'default_value' => 'Our Vision',
                'placeholder' => 'Title for the Vision card',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_mvv_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_vision_statement',
                'label' => 'Vision Statement',
                'name' => 'vision_statement',
                'type' => 'textarea',
                'maxlength' => 200,
                'placeholder' => 'Enter vision statement (max 200 characters)',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_mvv_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_vision_link',
                'label' => 'Vision Statement Link',
                'name' => 'vision_link',
                'type' => 'url',
                'placeholder' => 'Optional link for vision statement',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_mvv_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_values_title',
                'label' => 'Values Section Title',
                'name' => 'values_title',
                'type' => 'text',
                'default_value' => 'Our Values',
                'placeholder' => 'Title for the Values card',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_mvv_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_core_values',
                'label' => 'Core Values',
                'name' => 'core_values',
                'type' => 'textarea',
                'maxlength' => 200,
                'placeholder' => 'Enter core values (max 200 characters)',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_mvv_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_values_link',
                'label' => 'Core Values Link',
                'name' => 'values_link',
                'type' => 'url',
                'placeholder' => 'Optional link for core values',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_mvv_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'page-about.php',
                ),
            ),
        ),
    ));

    // Contact Page Field Group
    acf_add_local_field_group(array(
        'key' => 'group_contact_page',
        'title' => 'Contact Page Content',
        'fields' => array(
            array(
                'key' => 'field_contact_hero_title',
                'label' => 'Hero Title',
                'name' => 'contact_hero_title',
                'type' => 'text',
                'default_value' => 'Get in Touch',
            ),
            array(
                'key' => 'field_contact_hero_subtitle',
                'label' => 'Hero Subtitle',
                'name' => 'contact_hero_subtitle',
                'type' => 'textarea',
            ),
            array(
                'key' => 'field_contact_intro_text',
                'label' => 'Contact Introduction Text',
                'name' => 'contact_intro_text',
                'type' => 'textarea',
            ),
            array(
                'key' => 'field_office_address',
                'label' => 'Office Address',
                'name' => 'office_address',
                'type' => 'textarea',
            ),
            array(
                'key' => 'field_phone_section_title',
                'label' => 'Phone Section Title',
                'name' => 'phone_section_title',
                'type' => 'text',
                'default_value' => 'Phone',
                'instructions' => 'Title that appears above the phone numbers',
            ),
            array(
                'key' => 'field_office_phones',
                'label' => 'Office Phone Numbers',
                'name' => 'office_phones',
                'type' => 'repeater',
                'min' => 1,
                'max' => 5,
                'layout' => 'row',
                'button_label' => 'Add Phone Number',
                'sub_fields' => array(
                    array(
                        'key' => 'field_phone_number',
                        'label' => 'Phone Number',
                        'name' => 'phone_number',
                        'type' => 'text',
                        'required' => 1,
                        'placeholder' => 'e.g., +234 123 456 7890',
                    ),
                ),
            ),
            array(
                'key' => 'field_email_section_title',
                'label' => 'Email Section Title',
                'name' => 'email_section_title',
                'type' => 'text',
                'default_value' => 'Email',
                'instructions' => 'Title that appears above the email addresses',
            ),
            array(
                'key' => 'field_office_emails',
                'label' => 'Office Email Addresses',
                'name' => 'office_emails',
                'type' => 'repeater',
                'min' => 1,
                'max' => 5,
                'layout' => 'row',
                'button_label' => 'Add Email Address',
                'sub_fields' => array(
                    array(
                        'key' => 'field_email_address',
                        'label' => 'Email Address',
                        'name' => 'email_address',
                        'type' => 'email',
                        'required' => 1,
                        'placeholder' => 'e.g., info@niesvabuja.org',
                    ),
                ),
            ),
            array(
                'key' => 'field_office_hours',
                'label' => 'Office Hours',
                'name' => 'office_hours',
                'type' => 'wysiwyg',
                'toolbar' => 'basic',
            ),
            array(
                'key' => 'field_show_map_section',
                'label' => 'Show Map Section',
                'name' => 'show_map_section',
                'type' => 'true_false',
                'default_value' => 1,
            ),
            array(
                'key' => 'field_google_maps_embed',
                'label' => 'Google Maps Embed Code',
                'name' => 'google_maps_embed',
                'type' => 'textarea',
                'placeholder' => 'Paste Google Maps embed iframe code here',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_map_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'page-contact.php',
                ),
            ),
        ),
    ));

    // Services Page Field Group
    acf_add_local_field_group(array(
        'key' => 'group_services_page',
        'title' => 'Services Page Content',
        'fields' => array(
            array(
                'key' => 'field_services_page_title',
                'label' => 'Page Title',
                'name' => 'page_title',
                'type' => 'text',
                'placeholder' => 'Override default page title',
            ),
            array(
                'key' => 'field_services_page_subtitle',
                'label' => 'Page Subtitle',
                'name' => 'page_subtitle',
                'type' => 'textarea',
            ),
            array(
                'key' => 'field_services_list',
                'label' => 'Services List',
                'name' => 'services_list',
                'type' => 'repeater',
                'sub_fields' => array(
                    array(
                        'key' => 'field_service_title',
                        'label' => 'Service Title',
                        'name' => 'service_title',
                        'type' => 'text',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_service_description',
                        'label' => 'Service Description',
                        'name' => 'service_description',
                        'type' => 'textarea',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_service_icon',
                        'label' => 'Service Icon',
                        'name' => 'service_icon',
                        'type' => 'image',
                        'return_format' => 'array',
                    ),
                    array(
                        'key' => 'field_service_features',
                        'label' => 'Service Features',
                        'name' => 'service_features',
                        'type' => 'repeater',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_feature',
                                'label' => 'Feature',
                                'name' => 'feature',
                                'type' => 'text',
                            ),
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_show_why_choose_section',
                'label' => 'Show Why Choose Us Section',
                'name' => 'show_why_choose_section',
                'type' => 'true_false',
                'default_value' => 1,
            ),
            array(
                'key' => 'field_why_choose_title',
                'label' => 'Why Choose Us Title',
                'name' => 'why_choose_title',
                'type' => 'text',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_why_choose_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_why_choose_subtitle',
                'label' => 'Why Choose Us Subtitle',
                'name' => 'why_choose_subtitle',
                'type' => 'textarea',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_why_choose_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_why_choose_points',
                'label' => 'Why Choose Us Points',
                'name' => 'why_choose_points',
                'type' => 'repeater',
                'sub_fields' => array(
                    array(
                        'key' => 'field_point_title',
                        'label' => 'Point Title',
                        'name' => 'point_title',
                        'type' => 'text',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_point_description',
                        'label' => 'Point Description',
                        'name' => 'point_description',
                        'type' => 'textarea',
                        'required' => 1,
                    ),
                ),
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_why_choose_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_show_cta_section',
                'label' => 'Show Call to Action Section',
                'name' => 'show_cta_section',
                'type' => 'true_false',
                'default_value' => 1,
            ),
            array(
                'key' => 'field_cta_title',
                'label' => 'CTA Title',
                'name' => 'cta_title',
                'type' => 'text',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_cta_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_cta_description',
                'label' => 'CTA Description',
                'name' => 'cta_description',
                'type' => 'textarea',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_cta_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_cta_primary_button_text',
                'label' => 'Primary Button Text',
                'name' => 'cta_primary_button_text',
                'type' => 'text',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_cta_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_cta_primary_button_link',
                'label' => 'Primary Button Link',
                'name' => 'cta_primary_button_link',
                'type' => 'url',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_cta_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_cta_secondary_button_text',
                'label' => 'Secondary Button Text',
                'name' => 'cta_secondary_button_text',
                'type' => 'text',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_cta_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_cta_secondary_button_link',
                'label' => 'Secondary Button Link',
                'name' => 'cta_secondary_button_link',
                'type' => 'url',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_cta_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'page-services.php',
                ),
            ),
        ),
    ));

    // Events Page Field Group
    acf_add_local_field_group(array(
        'key' => 'group_events_page',
        'title' => 'Events Page Content',
        'fields' => array(
            array(
                'key' => 'field_events_page_title',
                'label' => 'Page Title',
                'name' => 'page_title',
                'type' => 'text',
                'placeholder' => 'Override default page title',
            ),
            array(
                'key' => 'field_events_page_subtitle',
                'label' => 'Page Subtitle',
                'name' => 'page_subtitle',
                'type' => 'textarea',
            ),
            array(
                'key' => 'field_show_featured_event',
                'label' => 'Show Featured Event',
                'name' => 'show_featured_event',
                'type' => 'true_false',
                'default_value' => 1,
            ),
            array(
                'key' => 'field_featured_event',
                'label' => 'Featured Event',
                'name' => 'featured_event',
                'type' => 'post_object',
                'post_type' => array('events'),
                'return_format' => 'object',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_featured_event',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'page-events.php',
                ),
            ),
        ),
    ));

    // News Page Field Group
    acf_add_local_field_group(array(
        'key' => 'group_news_page',
        'title' => 'News Page Content',
        'fields' => array(
            array(
                'key' => 'field_news_page_title',
                'label' => 'Page Title',
                'name' => 'page_title',
                'type' => 'text',
                'placeholder' => 'Override default page title',
            ),
            array(
                'key' => 'field_news_page_subtitle',
                'label' => 'Page Subtitle',
                'name' => 'page_subtitle',
                'type' => 'textarea',
            ),
            array(
                'key' => 'field_show_featured_post',
                'label' => 'Show Featured Post',
                'name' => 'show_featured_post',
                'type' => 'true_false',
                'default_value' => 1,
            ),
            array(
                'key' => 'field_featured_post',
                'label' => 'Featured Post',
                'name' => 'featured_post',
                'type' => 'post_object',
                'post_type' => array('post'),
                'return_format' => 'object',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_featured_post',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'page-news.php',
                ),
            ),
        ),
    ));

    // Team Page Field Group
    acf_add_local_field_group(array(
        'key' => 'group_team_page',
        'title' => 'Team Page Content',
        'fields' => array(
            array(
                'key' => 'field_team_page_title',
                'label' => 'Page Title',
                'name' => 'page_title',
                'type' => 'text',
                'placeholder' => 'Override default page title',
            ),
            array(
                'key' => 'field_team_page_subtitle',
                'label' => 'Page Subtitle',
                'name' => 'page_subtitle',
                'type' => 'textarea',
            ),
            array(
                'key' => 'field_show_leadership_section',
                'label' => 'Show Leadership Section',
                'name' => 'show_leadership_section',
                'type' => 'true_false',
                'default_value' => 1,
            ),
            array(
                'key' => 'field_leadership_title',
                'label' => 'Leadership Section Title',
                'name' => 'leadership_title',
                'type' => 'text',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_leadership_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_leadership_subtitle',
                'label' => 'Leadership Section Subtitle',
                'name' => 'leadership_subtitle',
                'type' => 'textarea',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_leadership_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_members_title',
                'label' => 'Members Section Title',
                'name' => 'members_title',
                'type' => 'text',
            ),
            array(
                'key' => 'field_members_subtitle',
                'label' => 'Members Section Subtitle',
                'name' => 'members_subtitle',
                'type' => 'textarea',
            ),
            array(
                'key' => 'field_show_join_cta',
                'label' => 'Show Join CTA Section',
                'name' => 'show_join_cta',
                'type' => 'true_false',
                'default_value' => 1,
            ),
            array(
                'key' => 'field_join_cta_title',
                'label' => 'Join CTA Title',
                'name' => 'join_cta_title',
                'type' => 'text',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_join_cta',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_join_cta_description',
                'label' => 'Join CTA Description',
                'name' => 'join_cta_description',
                'type' => 'textarea',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_join_cta',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_join_cta_button_text',
                'label' => 'Join CTA Button Text',
                'name' => 'join_cta_button_text',
                'type' => 'text',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_join_cta',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_join_cta_button_link',
                'label' => 'Join CTA Button Link',
                'name' => 'join_cta_button_link',
                'type' => 'url',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_join_cta',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_join_cta_secondary_button_text',
                'label' => 'Join CTA Secondary Button Text',
                'name' => 'join_cta_secondary_button_text',
                'type' => 'text',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_join_cta',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_join_cta_secondary_button_link',
                'label' => 'Join CTA Secondary Button Link',
                'name' => 'join_cta_secondary_button_link',
                'type' => 'url',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_join_cta',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'page-team.php',
                ),
            ),
        ),
    ));

    // Front Page Field Group
    acf_add_local_field_group(array(
        'key' => 'group_front_page',
        'title' => 'Front Page Content',
        'fields' => array(
            // Hero Carousel Section
            array(
                'key' => 'field_hero_slides',
                'label' => 'Hero Carousel Slides',
                'name' => 'hero_slides',
                'type' => 'repeater',
                'sub_fields' => array(
                    array(
                        'key' => 'field_slide_title',
                        'label' => 'Slide Title',
                        'name' => 'slide_title',
                        'type' => 'text',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_slide_subtitle',
                        'label' => 'Slide Subtitle',
                        'name' => 'slide_subtitle',
                        'type' => 'textarea',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_slide_background_image',
                        'label' => 'Background Image',
                        'name' => 'slide_background_image',
                        'type' => 'image',
                        'return_format' => 'array',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_slide_button_text',
                        'label' => 'Button Text',
                        'name' => 'slide_button_text',
                        'type' => 'text',
                    ),
                    array(
                        'key' => 'field_slide_button_link',
                        'label' => 'Button Link',
                        'name' => 'slide_button_link',
                        'type' => 'url',
                    ),
                ),
                'min' => 1,
                'max' => 5,
                'layout' => 'block',
            ),

            // Important Updates Section
            array(
                'key' => 'field_show_updates_section',
                'label' => 'Show Important Updates Section',
                'name' => 'show_updates_section',
                'type' => 'true_false',
                'default_value' => 1,
            ),
            array(
                'key' => 'field_updates_title',
                'label' => 'Updates Section Title',
                'name' => 'updates_title',
                'type' => 'text',
                'default_value' => 'Important Updates',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_updates_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_update_headline',
                'label' => 'Update Headline',
                'name' => 'update_headline',
                'type' => 'text',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_updates_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_update_description',
                'label' => 'Update Description',
                'name' => 'update_description',
                'type' => 'wysiwyg',
                'toolbar' => 'basic',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_updates_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_update_button_text',
                'label' => 'Button Text',
                'name' => 'update_button_text',
                'type' => 'text',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_updates_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_update_button_link',
                'label' => 'Button Link',
                'name' => 'update_button_link',
                'type' => 'url',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_updates_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),

            // Custom Content Section (between Updates and News)
            array(
                'key' => 'field_show_custom_content_section',
                'label' => 'Show Custom Content Section',
                'name' => 'show_custom_content_section',
                'type' => 'true_false',
                'default_value' => 0,
            ),
            array(
                'key' => 'field_custom_content_title',
                'label' => 'Custom Content Section Title',
                'name' => 'custom_content_title',
                'type' => 'text',
                'placeholder' => 'Enter section title',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_custom_content_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_custom_content_wysiwyg',
                'label' => 'Custom Content',
                'name' => 'custom_content_wysiwyg',
                'type' => 'wysiwyg',
                'toolbar' => 'full',
                'media_upload' => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_custom_content_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_custom_content_image',
                'label' => 'Custom Content Image',
                'name' => 'custom_content_image',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_custom_content_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_custom_content_link',
                'label' => 'Read More Link',
                'name' => 'custom_content_link',
                'type' => 'link',
                'instructions' => 'Add a link to redirect users to the full content page',
                'return_format' => 'array',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_custom_content_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),

            // Latest News Section
            array(
                'key' => 'field_news_section_title',
                'label' => 'News Section Title',
                'name' => 'news_section_title',
                'type' => 'text',
                'default_value' => 'Latest News',
            ),
            array(
                'key' => 'field_news_posts_count',
                'label' => 'Number of News Posts to Show',
                'name' => 'news_posts_count',
                'type' => 'number',
                'min' => 1,
                'max' => 12,
                'default_value' => 3,
            ),

            // Events Section
            array(
                'key' => 'field_events_section_title',
                'label' => 'Events Section Title',
                'name' => 'events_section_title',
                'type' => 'text',
                'default_value' => 'Upcoming Events',
            ),
            array(
                'key' => 'field_events_count',
                'label' => 'Number of Events to Show',
                'name' => 'events_count',
                'type' => 'number',
                'min' => 1,
                'max' => 8,
                'default_value' => 4,
            ),

            // Team Section
            array(
                'key' => 'field_team_section_title',
                'label' => 'Team Section Title',
                'name' => 'team_section_title',
                'type' => 'text',
                'default_value' => 'Meet Our Team',
            ),
            array(
                'key' => 'field_team_members_count',
                'label' => 'Number of Team Members to Show',
                'name' => 'team_members_count',
                'type' => 'number',
                'min' => 1,
                'max' => 12,
                'default_value' => 4,
            ),
            array(
                'key' => 'field_show_leadership_only',
                'label' => 'Show Leadership Members Only',
                'name' => 'show_leadership_only',
                'type' => 'true_false',
                'default_value' => 1,
            ),

            // Counter Stats Section
            array(
                'key' => 'field_show_counters_section',
                'label' => 'Show Counter Stats Section',
                'name' => 'show_counters_section',
                'type' => 'true_false',
                'default_value' => 1,
            ),
            array(
                'key' => 'field_counters_background_image',
                'label' => 'Counters Background Image',
                'name' => 'counters_background_image',
                'type' => 'image',
                'return_format' => 'array',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_counters_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_front_counters',
                'label' => 'Counters',
                'name' => 'front_counters',
                'type' => 'repeater',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_counters_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
                'min' => 1,
                'max' => 4,
                'layout' => 'row',
                'button_label' => 'Add Counter',
                'sub_fields' => array(
                    array(
                        'key' => 'field_counter_icon',
                        'label' => 'Icon (optional)',
                        'name' => 'icon',
                        'type' => 'image',
                        'return_format' => 'array',
                    ),
                    array(
                        'key' => 'field_counter_value',
                        'label' => 'Number',
                        'name' => 'value',
                        'type' => 'number',
                        'min' => 0,
                        'default_value' => 0,
                    ),
                    array(
                        'key' => 'field_counter_label',
                        'label' => 'Label',
                        'name' => 'label',
                        'type' => 'text',
                    ),
                ),
            ),

            // Feature Cards Section (New Component)
            array(
                'key' => 'field_show_feature_cards_section',
                'label' => 'Show Feature Cards Section',
                'name' => 'show_feature_cards_section',
                'type' => 'true_false',
                'default_value' => 1,
            ),
            array(
                'key' => 'field_feature_cards_title',
                'label' => 'Feature Cards Section Title',
                'name' => 'feature_cards_title',
                'type' => 'text',
                'default_value' => 'Explore NIESV',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_feature_cards_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_feature_cards',
                'label' => 'Cards',
                'name' => 'feature_cards',
                'type' => 'repeater',
                'min' => 1,
                'max' => 8,
                'layout' => 'block',
                'button_label' => 'Add Card',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_feature_cards_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
                'sub_fields' => array(
                    array(
                        'key' => 'field_feature_icon',
                        'label' => 'Icon',
                        'name' => 'icon',
                        'type' => 'image',
                        'return_format' => 'array',
                    ),
                    array(
                        'key' => 'field_feature_title',
                        'label' => 'Title',
                        'name' => 'title',
                        'type' => 'text',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_feature_description',
                        'label' => 'Description',
                        'name' => 'description',
                        'type' => 'textarea',
                    ),
                    array(
                        'key' => 'field_feature_link',
                        'label' => 'Link URL',
                        'name' => 'link',
                        'type' => 'url',
                    ),
                ),
            ),
            // Affiliates Section
            array(
                'key' => 'field_affiliates_section_title',
                'label' => 'Affiliates Section Title',
                'name' => 'affiliates_section_title',
                'type' => 'text',
                'default_value' => 'Our Affiliates',
            ),
            array(
                'key' => 'field_affiliate_logos',
                'label' => 'Affiliate Logos',
                'name' => 'affiliate_logos',
                'type' => 'repeater',
                'sub_fields' => array(
                    array(
                        'key' => 'field_affiliate_name',
                        'label' => 'Affiliate Name',
                        'name' => 'affiliate_name',
                        'type' => 'text',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_affiliate_logo',
                        'label' => 'Affiliate Logo',
                        'name' => 'affiliate_logo',
                        'type' => 'image',
                        'return_format' => 'array',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_affiliate_url',
                        'label' => 'Affiliate Website URL',
                        'name' => 'affiliate_url',
                        'type' => 'url',
                    ),
                ),
                'min' => 1,
                'layout' => 'block',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page_type',
                    'operator' => '==',
                    'value' => 'front_page',
                ),
            ),
        ),
    ));

    // Footer & Social Media Field Group
    acf_add_local_field_group(array(
        'key' => 'group_footer_social',
        'title' => 'Footer & Social Media',
        'fields' => array(
            array(
                'key' => 'field_footer_about_title',
                'label' => 'Footer About Title',
                'name' => 'footer_about_title',
                'type' => 'text',
                'default_value' => 'NIESV Abuja',
            ),
            array(
                'key' => 'field_footer_about_text',
                'label' => 'Footer About Text',
                'name' => 'footer_about_text',
                'type' => 'textarea',
                'default_value' => 'The Nigerian Institution of Estate Surveyors and Valuers, Abuja Branch.',
            ),
            array(
                'key' => 'field_contact_address',
                'label' => 'Contact Address',
                'name' => 'contact_address',
                'type' => 'textarea',
            ),
            array(
                'key' => 'field_contact_email',
                'label' => 'Contact Email',
                'name' => 'contact_email',
                'type' => 'email',
            ),
            array(
                'key' => 'field_contact_phone',
                'label' => 'Contact Phone',
                'name' => 'contact_phone',
                'type' => 'text',
            ),
            array(
                'key' => 'field_social_facebook',
                'label' => 'Facebook URL',
                'name' => 'social_facebook',
                'type' => 'url',
            ),
            array(
                'key' => 'field_social_twitter',
                'label' => 'Twitter URL',
                'name' => 'social_twitter',
                'type' => 'url',
            ),
            array(
                'key' => 'field_social_linkedin',
                'label' => 'LinkedIn URL',
                'name' => 'social_linkedin',
                'type' => 'url',
            ),
            array(
                'key' => 'field_social_whatsapp',
                'label' => 'WhatsApp URL',
                'name' => 'social_whatsapp',
                'type' => 'url',
            ),
            array(
                'key' => 'field_social_instagram',
                'label' => 'Instagram URL',
                'name' => 'social_instagram',
                'type' => 'url',
            ),
            array(
                'key' => 'field_copyright_text',
                'label' => 'Copyright Text',
                'name' => 'copyright_text',
                'type' => 'text',
                'placeholder' => 'Leave empty for default copyright text',
            ),
            array(
                'key' => 'field_default_social_image',
                'label' => 'Default Social Media Image',
                'name' => 'default_social_image',
                'type' => 'image',
                'instructions' => 'Default image for social media sharing when no specific image is set. Recommended size: 1200x630px.',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
            ),
            array(
                'key' => 'field_site_description_social',
                'label' => 'Site Description for Social Media',
                'name' => 'site_description_social',
                'type' => 'textarea',
                'instructions' => 'Default description for social media sharing (55-200 characters recommended).',
                'rows' => 3,
                'maxlength' => 300,
                'default_value' => 'The Nigerian Institution of Estate Surveyors and Valuers, Abuja Branch - Setting the standard for estate surveying and valuation in the Federal Capital Territory.',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'acf-options-footer',
                ),
            ),
        ),
    ));

    // PWA Notification Settings Field Group
    acf_add_local_field_group(array(
        'key' => 'group_pwa_notifications',
        'title' => 'PWA Notification Settings',
        'fields' => array(
            array(
                'key' => 'field_enable_pwa_notifications',
                'label' => 'Enable PWA Notifications',
                'name' => 'enable_pwa_notifications',
                'type' => 'true_false',
                'default_value' => 0,
                'instructions' => 'Enable popup notifications for PWA users',
            ),
            array(
                'key' => 'field_updates_title',
                'label' => 'Notification Title',
                'name' => 'updates_title',
                'type' => 'text',
                'placeholder' => 'Important Update',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_enable_pwa_notifications',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_update_headline',
                'label' => 'Notification Headline',
                'name' => 'update_headline',
                'type' => 'text',
                'placeholder' => 'New announcement available',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_enable_pwa_notifications',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_update_description',
                'label' => 'Notification Description',
                'name' => 'update_description',
                'type' => 'textarea',
                'placeholder' => 'Brief description of the update',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_enable_pwa_notifications',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_update_button_text',
                'label' => 'Button Text',
                'name' => 'update_button_text',
                'type' => 'text',
                'placeholder' => 'Learn More',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_enable_pwa_notifications',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_update_button_link',
                'label' => 'Button Link',
                'name' => 'update_button_link',
                'type' => 'url',
                'placeholder' => 'https://example.com',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_enable_pwa_notifications',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_notification_hash',
                'label' => 'Notification Hash',
                'name' => 'notification_hash',
                'type' => 'text',
                'readonly' => 1,
                'instructions' => 'Auto-generated hash to track notification dismissals (do not edit)',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_enable_pwa_notifications',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'acf-options-pwa-notifications',
                ),
            ),
        ),
    ));

    // Hero Section Block Fields
    acf_add_local_field_group(array(
        'key' => 'group_hero_block',
        'title' => 'Hero Section Block',
        'fields' => array(
            array(
                'key' => 'field_hero_block_title',
                'label' => 'Hero Title',
                'name' => 'hero_title',
                'type' => 'text',
                'default_value' => 'NIESV Abuja Branch',
            ),
            array(
                'key' => 'field_hero_block_subtitle',
                'label' => 'Hero Subtitle',
                'name' => 'hero_subtitle',
                'type' => 'textarea',
                'default_value' => 'Setting the standard for estate surveying and valuation in the Federal Capital Territory.',
            ),
            array(
                'key' => 'field_hero_block_background',
                'label' => 'Background Image',
                'name' => 'hero_background_image',
                'type' => 'image',
                'return_format' => 'url',
            ),
            array(
                'key' => 'field_hero_block_button_text',
                'label' => 'Button Text',
                'name' => 'hero_button_text',
                'type' => 'text',
            ),
            array(
                'key' => 'field_hero_block_button_link',
                'label' => 'Button Link',
                'name' => 'hero_button_link',
                'type' => 'url',
            ),
            array(
                'key' => 'field_hero_overlay_opacity',
                'label' => 'Overlay Opacity (%)',
                'name' => 'hero_overlay_opacity',
                'type' => 'range',
                'min' => 0,
                'max' => 100,
                'step' => 10,
                'default_value' => 70,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/hero-section',
                ),
            ),
        ),
    ));

    // News Cards Block Fields
    acf_add_local_field_group(array(
        'key' => 'group_news_cards_block',
        'title' => 'News Cards Block',
        'fields' => array(
            array(
                'key' => 'field_news_section_title',
                'label' => 'Section Title',
                'name' => 'section_title',
                'type' => 'text',
                'default_value' => 'Latest News',
            ),
            array(
                'key' => 'field_news_number_of_posts',
                'label' => 'Number of Posts',
                'name' => 'number_of_posts',
                'type' => 'number',
                'min' => 1,
                'max' => 12,
                'default_value' => 3,
            ),
            array(
                'key' => 'field_news_show_excerpt',
                'label' => 'Show Excerpt',
                'name' => 'show_excerpt',
                'type' => 'true_false',
                'default_value' => 1,
            ),
            array(
                'key' => 'field_news_show_author',
                'label' => 'Show Author',
                'name' => 'show_author',
                'type' => 'true_false',
                'default_value' => 1,
            ),
            array(
                'key' => 'field_news_show_date',
                'label' => 'Show Date',
                'name' => 'show_date',
                'type' => 'true_false',
                'default_value' => 1,
            ),
            array(
                'key' => 'field_news_category_filter',
                'label' => 'Filter by Category',
                'name' => 'news_category_filter',
                'type' => 'select',
                'choices' => array(
                    '' => 'All Categories',
                    'announcement' => 'Announcement',
                    'industry_news' => 'Industry News',
                    'branch_news' => 'Branch News',
                    'regulation' => 'Regulation',
                    'market_update' => 'Market Update',
                    'achievement' => 'Achievement',
                ),
                'allow_null' => 1,
            ),
            array(
                'key' => 'field_news_show_featured_only',
                'label' => 'Show Featured Only',
                'name' => 'show_featured_only',
                'type' => 'true_false',
                'message' => 'Only show featured news articles',
                'instructions' => 'When enabled, only news marked as featured will be displayed. Limit will be automatically set to 3.',
                'default_value' => 0,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/news-cards',
                ),
            ),
        ),
    ));

    // Events Cards Block Fields
    acf_add_local_field_group(array(
        'key' => 'group_events_cards_block',
        'title' => 'Events Cards Block',
        'fields' => array(
            array(
                'key' => 'field_events_section_title',
                'label' => 'Section Title',
                'name' => 'section_title',
                'type' => 'text',
                'default_value' => 'Upcoming Events',
            ),
            array(
                'key' => 'field_events_number_of_events',
                'label' => 'Number of Events',
                'name' => 'number_of_events',
                'type' => 'number',
                'min' => 1,
                'max' => 12,
                'default_value' => 4,
            ),
            array(
                'key' => 'field_events_show_past',
                'label' => 'Show Past Events',
                'name' => 'show_past_events',
                'type' => 'true_false',
                'default_value' => 0,
            ),
            array(
                'key' => 'field_events_type_filter',
                'label' => 'Filter by Event Type',
                'name' => 'event_type_filter',
                'type' => 'select',
                'choices' => array(
                    '' => 'All Types',
                    'conference' => 'Conference',
                    'workshop' => 'Workshop',
                    'seminar' => 'Seminar',
                    'meeting' => 'Meeting',
                    'networking' => 'Networking Event',
                    'training' => 'Training',
                ),
                'allow_null' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/events-cards',
                ),
            ),
        ),
    ));

    // Important Updates Block Fields
    acf_add_local_field_group(array(
        'key' => 'group_important_updates_block',
        'title' => 'Important Updates Block',
        'fields' => array(
            array(
                'key' => 'field_updates_section_title',
                'label' => 'Section Title',
                'name' => 'section_title',
                'type' => 'text',
                'default_value' => 'Important Updates',
            ),
            array(
                'key' => 'field_updates_update_title',
                'label' => 'Update Title',
                'name' => 'update_title',
                'type' => 'text',
                'default_value' => 'Mandatory Professional Development Course',
            ),
            array(
                'key' => 'field_updates_update_content',
                'label' => 'Update Content',
                'name' => 'update_content',
                'type' => 'wysiwyg',
                'toolbar' => 'basic',
            ),
            array(
                'key' => 'field_updates_button_text',
                'label' => 'Button Text',
                'name' => 'update_button_text',
                'type' => 'text',
            ),
            array(
                'key' => 'field_updates_button_link',
                'label' => 'Button Link',
                'name' => 'update_button_link',
                'type' => 'url',
            ),
            array(
                'key' => 'field_updates_icon',
                'label' => 'Icon',
                'name' => 'update_icon',
                'type' => 'select',
                'choices' => array(
                    'megaphone' => 'Megaphone',
                    'info' => 'Info Circle',
                    'bell' => 'Bell',
                ),
                'default_value' => 'megaphone',
            ),
            array(
                'key' => 'field_updates_background_color',
                'label' => 'Background Color',
                'name' => 'background_color',
                'type' => 'select',
                'choices' => array(
                    'secondary' => 'Light Blue (Default)',
                    'primary' => 'Dark Blue',
                    'white' => 'White',
                    'gold' => 'Gold',
                ),
                'default_value' => 'secondary',
            ),
            array(
                'key' => 'field_updates_additional_updates',
                'label' => 'Additional Updates',
                'name' => 'additional_updates',
                'type' => 'repeater',
                'sub_fields' => array(
                    array(
                        'key' => 'field_additional_title',
                        'label' => 'Title',
                        'name' => 'title',
                        'type' => 'text',
                    ),
                    array(
                        'key' => 'field_additional_content',
                        'label' => 'Content',
                        'name' => 'content',
                        'type' => 'wysiwyg',
                        'instructions' => 'Rich text content for this update',
                        'tabs' => 'all',
                        'toolbar' => 'basic',
                        'media_upload' => 0,
                        'delay' => 0,
                    ),
                    array(
                        'key' => 'field_additional_link',
                        'label' => 'Link',
                        'name' => 'link',
                        'type' => 'url',
                    ),
                ),
                'min' => 0,
                'layout' => 'row',
                'button_label' => 'Add Update',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/important-updates',
                ),
            ),
        ),
    ));

    // Team Members Block Fields
    acf_add_local_field_group(array(
        'key' => 'group_team_members_block',
        'title' => 'Team Members Block',
        'fields' => array(
            array(
                'key' => 'field_team_section_title',
                'label' => 'Section Title',
                'name' => 'section_title',
                'type' => 'text',
                'default_value' => 'Our Team',
            ),
            array(
                'key' => 'field_team_number_of_members',
                'label' => 'Number of Members',
                'name' => 'number_of_members',
                'type' => 'number',
                'min' => 1,
                'max' => 20,
                'default_value' => 6,
            ),
            array(
                'key' => 'field_team_show_position',
                'label' => 'Show Position',
                'name' => 'show_position',
                'type' => 'true_false',
                'default_value' => 1,
            ),
            array(
                'key' => 'field_team_show_bio',
                'label' => 'Show Bio Excerpt',
                'name' => 'show_bio',
                'type' => 'true_false',
                'default_value' => 1,
            ),
            array(
                'key' => 'field_team_show_contact',
                'label' => 'Show Contact Icons',
                'name' => 'show_contact',
                'type' => 'true_false',
                'default_value' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/team-members',
                ),
            ),
        ),
    ));

    // Properties Grid Block Fields
    acf_add_local_field_group(array(
        'key' => 'group_properties_grid_block',
        'title' => 'Properties Grid Block',
        'fields' => array(
            array(
                'key' => 'field_properties_section_title',
                'label' => 'Section Title',
                'name' => 'section_title',
                'type' => 'text',
                'default_value' => 'Featured Properties',
            ),
            array(
                'key' => 'field_properties_number_of_properties',
                'label' => 'Number of Properties',
                'name' => 'number_of_properties',
                'type' => 'number',
                'min' => 1,
                'max' => 20,
                'default_value' => 6,
            ),
            array(
                'key' => 'field_properties_type_filter',
                'label' => 'Filter by Property Type',
                'name' => 'property_type_filter',
                'type' => 'select',
                'choices' => array(
                    '' => 'All Types',
                    'residential' => 'Residential',
                    'commercial' => 'Commercial',
                    'land' => 'Land',
                    'industrial' => 'Industrial',
                ),
                'allow_null' => 1,
            ),
            array(
                'key' => 'field_properties_status_filter',
                'label' => 'Filter by Status',
                'name' => 'property_status_filter',
                'type' => 'select',
                'choices' => array(
                    '' => 'All Status',
                    'for_sale' => 'For Sale',
                    'for_rent' => 'For Rent',
                    'sold' => 'Sold',
                    'rented' => 'Rented',
                ),
                'allow_null' => 1,
            ),
            array(
                'key' => 'field_properties_show_price',
                'label' => 'Show Price',
                'name' => 'show_price',
                'type' => 'true_false',
                'default_value' => 1,
            ),
            array(
                'key' => 'field_properties_show_location',
                'label' => 'Show Location',
                'name' => 'show_location',
                'type' => 'true_false',
                'default_value' => 1,
            ),
            array(
                'key' => 'field_properties_show_features',
                'label' => 'Show Features (Beds/Baths/Size)',
                'name' => 'show_features',
                'type' => 'true_false',
                'default_value' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/properties-grid',
                ),
            ),
        ),
    ));

    // Contact Info Block Fields
    acf_add_local_field_group(array(
        'key' => 'group_contact_info_block',
        'title' => 'Contact Info Block',
        'fields' => array(
            array(
                'key' => 'field_contact_section_title',
                'label' => 'Section Title',
                'name' => 'section_title',
                'type' => 'text',
                'default_value' => 'Get in Touch',
            ),
            array(
                'key' => 'field_contact_section_subtitle',
                'label' => 'Section Subtitle',
                'name' => 'section_subtitle',
                'type' => 'textarea',
                'default_value' => 'We are here to help with all your estate surveying and valuation needs.',
            ),
            array(
                'key' => 'field_contact_office_address',
                'label' => 'Office Address',
                'name' => 'office_address',
                'type' => 'textarea',
                'placeholder' => 'Leave empty to use theme option address',
            ),
            array(
                'key' => 'field_contact_office_phone',
                'label' => 'Office Phone',
                'name' => 'office_phone',
                'type' => 'text',
                'placeholder' => 'Leave empty to use theme option phone',
            ),
            array(
                'key' => 'field_contact_office_email',
                'label' => 'Office Email',
                'name' => 'office_email',
                'type' => 'email',
                'placeholder' => 'Leave empty to use theme option email',
            ),
            array(
                'key' => 'field_contact_office_hours',
                'label' => 'Office Hours',
                'name' => 'office_hours',
                'type' => 'wysiwyg',
                'toolbar' => 'basic',
            ),
            array(
                'key' => 'field_contact_show_social_links',
                'label' => 'Show Social Media Links',
                'name' => 'show_social_links',
                'type' => 'true_false',
                'default_value' => 1,
            ),
            array(
                'key' => 'field_contact_background_style',
                'label' => 'Background Style',
                'name' => 'background_style',
                'type' => 'select',
                'choices' => array(
                    'light' => 'Light (White)',
                    'secondary' => 'Light Blue',
                    'dark' => 'Dark Blue',
                    'gold' => 'Gold',
                ),
                'default_value' => 'light',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/contact-info',
                ),
            ),
        ),
    ));

    // Gallery Field Group
    acf_add_local_field_group(array(
        'key' => 'group_gallery',
        'title' => 'Gallery Album Details',
        'fields' => array(
            array(
                'key' => 'field_gallery_images',
                'label' => 'Gallery Images',
                'name' => 'gallery_images',
                'type' => 'gallery',
                'instructions' => 'Upload images for this gallery album',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
                'min' => 1,
                'max' => 100,
                'required' => 1,
            ),
            array(
                'key' => 'field_gallery_date',
                'label' => 'Event Date',
                'name' => 'gallery_date',
                'type' => 'date_picker',
                'instructions' => 'Date when the photos were taken',
                'display_format' => 'F j, Y',
                'return_format' => 'Y-m-d',
            ),
            array(
                'key' => 'field_gallery_location',
                'label' => 'Location',
                'name' => 'gallery_location',
                'type' => 'text',
                'instructions' => 'Where the photos were taken',
                'placeholder' => 'e.g., NIESV Abuja Branch Office',
            ),
            array(
                'key' => 'field_gallery_photographer',
                'label' => 'Photographer',
                'name' => 'gallery_photographer',
                'type' => 'text',
                'instructions' => 'Name of the photographer (optional)',
            ),
            array(
                'key' => 'field_is_featured_gallery',
                'label' => 'Featured Gallery',
                'name' => 'is_featured_gallery',
                'type' => 'true_false',
                'message' => 'Mark this gallery as featured',
                'instructions' => 'Featured galleries appear prominently on the gallery page',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'gallery',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));

    // Gallery Page Field Group
    acf_add_local_field_group(array(
        'key' => 'group_gallery_page',
        'title' => 'Gallery Page Content',
        'fields' => array(
            array(
                'key' => 'field_gallery_page_title',
                'label' => 'Page Title',
                'name' => 'page_title',
                'type' => 'text',
                'placeholder' => 'Override default page title',
                'default_value' => 'Image Gallery',
            ),
            array(
                'key' => 'field_gallery_page_subtitle',
                'label' => 'Page Subtitle',
                'name' => 'page_subtitle',
                'type' => 'textarea',
                'default_value' => 'Explore moments from our past events, conferences, and community gatherings. Select a folder to view the images within.',
            ),
            array(
                'key' => 'field_gallery_hero_background',
                'label' => 'Hero Background Image',
                'name' => 'gallery_hero_background',
                'type' => 'image',
                'instructions' => 'Background image for the gallery page hero section',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
            ),
            array(
                'key' => 'field_galleries_per_page',
                'label' => 'Galleries Per Page',
                'name' => 'galleries_per_page',
                'type' => 'number',
                'instructions' => 'Number of gallery albums to show per page',
                'default_value' => 12,
                'min' => 1,
                'max' => 24,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'page-gallery.php',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));

    // Gallery Grid Block Fields
    acf_add_local_field_group(array(
        'key' => 'group_gallery_grid_block',
        'title' => 'Gallery Grid Block',
        'fields' => array(
            array(
                'key' => 'field_gallery_section_title',
                'label' => 'Section Title',
                'name' => 'section_title',
                'type' => 'text',
                'default_value' => 'Gallery',
            ),
            array(
                'key' => 'field_gallery_section_subtitle',
                'label' => 'Section Subtitle',
                'name' => 'section_subtitle',
                'type' => 'textarea',
                'default_value' => 'Explore our collection of memorable moments',
            ),
            array(
                'key' => 'field_gallery_number_of_albums',
                'label' => 'Number of Albums',
                'name' => 'number_of_albums',
                'type' => 'number',
                'min' => 1,
                'max' => 12,
                'default_value' => 6,
            ),
            array(
                'key' => 'field_gallery_layout',
                'label' => 'Layout',
                'name' => 'gallery_layout',
                'type' => 'select',
                'choices' => array(
                    'grid-2' => '2 Columns',
                    'grid-3' => '3 Columns',
                    'grid-4' => '4 Columns',
                ),
                'default_value' => 'grid-3',
            ),
            array(
                'key' => 'field_gallery_show_featured_only',
                'label' => 'Show Featured Only',
                'name' => 'show_featured_only',
                'type' => 'true_false',
                'message' => 'Only show featured gallery albums',
                'default_value' => 0,
            ),
            array(
                'key' => 'field_gallery_show_view_all_link',
                'label' => 'Show "View All" Link',
                'name' => 'show_view_all_link',
                'type' => 'true_false',
                'message' => 'Show a link to view all gallery albums',
                'default_value' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/gallery-grid',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));

    // SEO & Social Media Field Group
    acf_add_local_field_group(array(
        'key' => 'group_seo_social',
        'title' => 'SEO & Social Media',
        'fields' => array(
            array(
                'key' => 'field_auto_populate_seo',
                'label' => 'Auto-populate SEO Fields',
                'name' => 'auto_populate_seo',
                'type' => 'true_false',
                'instructions' => 'Automatically generate SEO title and description from post content. Uncheck to use custom values below.',
                'default_value' => 1,
                'ui' => 1,
                'ui_on_text' => 'Auto',
                'ui_off_text' => 'Manual',
            ),
            // Preview fields (shown when auto-populate is enabled)
            array(
                'key' => 'field_seo_title_preview',
                'label' => 'Generated SEO Title (Preview)',
                'name' => 'seo_title_preview',
                'type' => 'text',
                'instructions' => 'This is the auto-generated SEO title that will be used.',
                'readonly' => 1,
                'disabled' => 1,
                'wrapper' => array(
                    'class' => 'seo-preview-field',
                ),
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_auto_populate_seo',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_seo_description_preview',
                'label' => 'Generated SEO Description (Preview)',
                'name' => 'seo_description_preview',
                'type' => 'textarea',
                'instructions' => 'This is the auto-generated SEO description that will be used.',
                'rows' => 3,
                'readonly' => 1,
                'disabled' => 1,
                'wrapper' => array(
                    'class' => 'seo-preview-field',
                ),
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_auto_populate_seo',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            // Manual input fields (shown when auto-populate is disabled)
            array(
                'key' => 'field_seo_title',
                'label' => 'SEO Title',
                'name' => 'seo_title',
                'type' => 'text',
                'instructions' => 'Custom title for search engines and social media. Auto-generated if "Auto-populate" is enabled.',
                'maxlength' => 60,
                'placeholder' => 'Will be auto-generated from post title...',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_auto_populate_seo',
                            'operator' => '!=',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_seo_description',
                'label' => 'SEO Description',
                'name' => 'seo_description',
                'type' => 'textarea',
                'instructions' => 'Description for search engines and social media (55-200 characters recommended). Auto-generated if "Auto-populate" is enabled.',
                'rows' => 3,
                'maxlength' => 300,
                'placeholder' => 'Will be auto-generated from post excerpt/content...',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_auto_populate_seo',
                            'operator' => '!=',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_social_image',
                'label' => 'Social Media Image',
                'name' => 'social_image',
                'type' => 'image',
                'instructions' => 'Image for social media sharing. Will use featured image if not set. Recommended size: 1200x630px.',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
            ),
            array(
                'key' => 'field_twitter_handle',
                'label' => 'Twitter Handle',
                'name' => 'twitter_handle',
                'type' => 'text',
                'instructions' => 'Twitter handle for this content (without @). Leave empty to use site default.',
                'placeholder' => 'username',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'page',
                ),
                array(
                    'param' => 'page_template',
                    'operator' => '!=',
                    'value' => 'page-register.php',
                ),
                array(
                    'param' => 'page_template',
                    'operator' => '!=',
                    'value' => 'page-login.php',
                ),
                array(
                    'param' => 'page_template',
                    'operator' => '!=',
                    'value' => 'page-property-dashboard.php',
                ),
                array(
                    'param' => 'page_template',
                    'operator' => '!=',
                    'value' => 'page-member-dashboard.php',
                ),
            ),
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'post',
                ),
            ),
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'news',
                ),
            ),
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'events',
                ),
            ),
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'gallery',
                ),
            ),
        ),
        'menu_order' => 20,
        'position' => 'side',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
    ));

    // Universal Hero Section Field Group
    acf_add_local_field_group(array(
        'key' => 'group_universal_hero',
        'title' => 'Hero Section',
        'fields' => array(
            array(
                'key' => 'field_hero_title',
                'label' => 'Hero Title',
                'name' => 'hero_title',
                'type' => 'text',
                'instructions' => 'Override the default page/post title. Leave empty to use default.',
                'placeholder' => 'Custom hero title...',
            ),
            array(
                'key' => 'field_hero_subtitle',
                'label' => 'Hero Subtitle',
                'name' => 'hero_subtitle',
                'type' => 'textarea',
                'instructions' => 'Subtitle text to display below the main title.',
                'rows' => 3,
            ),
            array(
                'key' => 'field_hero_background_image',
                'label' => 'Hero Background Image',
                'name' => 'hero_background_image',
                'type' => 'image',
                'instructions' => 'Background image for the hero section. Leave empty to use featured image or default.',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
            ),
            array(
                'key' => 'field_hero_overlay_color',
                'label' => 'Overlay Color',
                'name' => 'hero_overlay_color',
                'type' => 'color_picker',
                'instructions' => 'Color overlay for the background image.',
                'default_value' => '#1e3a8a',
                'enable_opacity' => 1,
            ),
            array(
                'key' => 'field_hero_size',
                'label' => 'Hero Size',
                'name' => 'hero_size',
                'type' => 'select',
                'instructions' => 'Choose the height of the hero section.',
                'choices' => array(
                    'small' => 'Small',
                    'medium' => 'Medium',
                    'large' => 'Large',
                ),
                'default_value' => 'medium',
            ),
            array(
                'key' => 'field_show_hero_breadcrumb',
                'label' => 'Show Breadcrumb',
                'name' => 'show_hero_breadcrumb',
                'type' => 'true_false',
                'message' => 'Display breadcrumb navigation in hero section',
                'default_value' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'page',
                ),
                array(
                    'param' => 'page_template',
                    'operator' => '!=',
                    'value' => 'page-register.php',
                ),
                array(
                    'param' => 'page_template',
                    'operator' => '!=',
                    'value' => 'page-login.php',
                ),
                array(
                    'param' => 'page_template',
                    'operator' => '!=',
                    'value' => 'page-property-dashboard.php',
                ),
                array(
                    'param' => 'page_template',
                    'operator' => '!=',
                    'value' => 'page-member-dashboard.php',
                ),
            ),
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'news',
                ),
            ),
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'events',
                ),
            ),
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'gallery',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));
}
add_action('acf/init', 'niesv_register_acf_fields');

/**
 * Register ACF Blocks
 */
function niesv_register_acf_blocks() {
    // Check if function exists
    if (function_exists('acf_register_block_type')) {

        // Debug: Log that blocks are being registered
        error_log('NIESV: Registering ACF blocks');

        // Hero Section Block
        acf_register_block_type(array(
            'name' => 'hero-section',
            'title' => __('Hero Section'),
            'description' => __('A custom hero section block.'),
            'render_template' => 'template-parts/blocks/hero-section.php',
            'category' => 'niesv-blocks',
            'icon' => 'cover-image',
            'keywords' => array('hero', 'banner', 'header'),
            'supports' => array(
                'align' => array('wide', 'full'),
                'anchor' => true,
            ),
            'mode' => 'edit',
            'example' => array(
                'attributes' => array(
                    'mode' => 'preview',
                    'data' => array(
                        'hero_title' => 'NIESV Abuja Branch',
                        'hero_subtitle' => 'Setting the standard for estate surveying and valuation in the Federal Capital Territory.',
                    )
                )
            ),
        ));

        // Debug: Log hero block registration
        error_log('NIESV: Hero Section block registered');

        // News Cards Block
        acf_register_block_type(array(
            'name' => 'news-cards',
            'title' => __('News Cards'),
            'description' => __('Display latest news in card format.'),
            'render_template' => 'template-parts/blocks/news-cards.php',
            'category' => 'niesv-blocks',
            'icon' => 'admin-post',
            'keywords' => array('news', 'articles', 'posts'),
        ));

        // Events Cards Block
        acf_register_block_type(array(
            'name' => 'events-cards',
            'title' => __('Events Cards'),
            'description' => __('Display upcoming events in card format.'),
            'render_template' => 'template-parts/blocks/events-cards.php',
            'category' => 'niesv-blocks',
            'icon' => 'calendar-alt',
            'keywords' => array('events', 'calendar', 'upcoming'),
        ));

        // Team Members Block
        acf_register_block_type(array(
            'name' => 'team-members',
            'title' => __('Team Members'),
            'description' => __('Display team members grid.'),
            'render_template' => 'template-parts/blocks/team-members.php',
            'category' => 'niesv-blocks',
            'icon' => 'groups',
            'keywords' => array('team', 'staff', 'members'),
        ));

        // Properties Grid Block
        acf_register_block_type(array(
            'name' => 'properties-grid',
            'title' => __('Properties Grid'),
            'description' => __('Display properties in grid format.'),
            'render_template' => 'template-parts/blocks/properties-grid.php',
            'category' => 'niesv-blocks',
            'icon' => 'building',
            'keywords' => array('properties', 'real estate', 'listings'),
        ));

        // Contact Info Block
        acf_register_block_type(array(
            'name' => 'contact-info',
            'title' => __('Contact Information'),
            'description' => __('Display contact information with icons.'),
            'render_template' => 'template-parts/blocks/contact-info.php',
            'category' => 'niesv-blocks',
            'icon' => 'phone',
            'keywords' => array('contact', 'address', 'phone'),
        ));

        // Important Updates Block
        acf_register_block_type(array(
            'name' => 'important-updates',
            'title' => __('Important Updates'),
            'description' => __('Display important announcements and updates.'),
            'render_template' => 'template-parts/blocks/important-updates.php',
            'category' => 'niesv-blocks',
            'icon' => 'megaphone',
            'keywords' => array('updates', 'announcements', 'important'),
        ));

        // Gallery Grid Block
        acf_register_block_type(array(
            'name' => 'gallery-grid',
            'title' => __('Gallery Grid'),
            'description' => __('Display gallery albums in a grid layout.'),
            'render_template' => 'template-parts/blocks/gallery-grid.php',
            'category' => 'niesv-blocks',
            'icon' => 'format-gallery',
            'keywords' => array('gallery', 'images', 'albums', 'photos'),
            'supports' => array(
                'align' => array('wide', 'full'),
                'anchor' => true,
            ),
        ));

        // WYSIWYG Content Block
        acf_register_block_type(array(
            'name' => 'wysiwyg-content',
            'title' => __('WYSIWYG Content'),
            'description' => __('A rich text editor block for flexible content creation.'),
            'render_template' => 'template-parts/blocks/wysiwyg-content.php',
            'category' => 'niesv-blocks',
            'icon' => 'editor-alignleft',
            'keywords' => array('wysiwyg', 'content', 'text', 'editor'),
            'supports' => array(
                'align' => array('wide', 'full'),
                'anchor' => true,
            ),
            'mode' => 'edit',
            'example' => array(
                'attributes' => array(
                    'mode' => 'preview',
                    'data' => array(
                        'content' => '<h2>Sample Content</h2><p>This is a sample WYSIWYG content block that allows you to create rich text content with formatting, links, and more.</p>',
                    )
                )
            ),
        ));
    }
}
add_action('acf/init', 'niesv_register_acf_blocks');

/**
 * Add custom block category
 */
function niesv_block_categories() {
    return array(
        array(
            'slug' => 'niesv-blocks',
            'title' => __('NIESV Blocks', 'niesv-theme'),
            'icon' => 'building',
        ),
    );
}
add_filter('block_categories_all', 'niesv_block_categories', 10, 1);

/**
 * Remove default WordPress blocks and only allow NIESV blocks
 */
function niesv_allowed_block_types() {
    // Only allow NIESV custom blocks
    $niesv_blocks = array(
        'acf/hero-section',
        'acf/news-cards',
        'acf/events-cards',
        'acf/team-members',
        'acf/properties-grid',
        'acf/contact-info',
        'acf/important-updates',
        'acf/gallery-grid',
        'acf/wysiwyg-content',
    );

    return $niesv_blocks;
}
add_filter('allowed_block_types_all', 'niesv_allowed_block_types', 10, 2);

/**
 * Remove default block patterns
 */
function niesv_remove_default_patterns() {
    remove_theme_support('core-block-patterns');
}
add_action('after_setup_theme', 'niesv_remove_default_patterns');

/**
 * Disable remote block patterns
 */
add_filter('should_load_remote_block_patterns', '__return_false');

/**
 * Remove default block styles
 */
function niesv_dequeue_block_styles() {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('wc-blocks-style');
}
add_action('wp_enqueue_scripts', 'niesv_dequeue_block_styles', 100);

/**
 * Customize block editor settings
 */
function niesv_block_editor_settings($settings) {
    // Disable custom colors
    $settings['disableCustomColors'] = true;

    // Disable custom font sizes
    $settings['disableCustomFontSizes'] = true;

    // Set custom color palette
    $settings['colors'] = array(
        array(
            'name' => 'NIESV Primary',
            'slug' => 'niesv-primary',
            'color' => 'rgba(0, 0, 40, 1)',
        ),
        array(
            'name' => 'NIESV Secondary',
            'slug' => 'niesv-secondary',
            'color' => '#e7edf4',
        ),
        array(
            'name' => 'NIESV Gold',
            'slug' => 'niesv-gold',
            'color' => '#d4af37',
        ),
        array(
            'name' => 'Text Primary',
            'slug' => 'text-primary',
            'color' => '#0d141c',
        ),
        array(
            'name' => 'Text Secondary',
            'slug' => 'text-secondary',
            'color' => '#49739c',
        ),
        array(
            'name' => 'White',
            'slug' => 'white',
            'color' => '#ffffff',
        ),
    );

    // Set custom font sizes
    $settings['fontSizes'] = array(
        array(
            'name' => 'Small',
            'slug' => 'small',
            'size' => 14,
        ),
        array(
            'name' => 'Normal',
            'slug' => 'normal',
            'size' => 16,
        ),
        array(
            'name' => 'Large',
            'slug' => 'large',
            'size' => 20,
        ),
        array(
            'name' => 'Extra Large',
            'slug' => 'extra-large',
            'size' => 24,
        ),
    );

    return $settings;
}
add_filter('block_editor_settings_all', 'niesv_block_editor_settings');

/**
 * Add custom CSS to block editor
 */
function niesv_block_editor_styles() {
    wp_enqueue_style(
        'niesv-block-editor-styles',
        get_template_directory_uri() . '/assets/css/block-editor.css',
        array(),
        wp_get_theme()->get('Version')
    );
}
add_action('enqueue_block_editor_assets', 'niesv_block_editor_styles');

/**
 * Remove default WordPress widgets
 */
function niesv_remove_default_widgets() {
    unregister_widget('WP_Widget_Pages');
    unregister_widget('WP_Widget_Calendar');
    unregister_widget('WP_Widget_Archives');
    unregister_widget('WP_Widget_Links');
    unregister_widget('WP_Widget_Meta');
    unregister_widget('WP_Widget_Categories');
    unregister_widget('WP_Widget_Recent_Posts');
    unregister_widget('WP_Widget_Recent_Comments');
    unregister_widget('WP_Widget_RSS');
    unregister_widget('WP_Widget_Tag_Cloud');
    unregister_widget('WP_Widget_Nav_Menu');
    unregister_widget('WP_Widget_Custom_HTML');
}
add_action('widgets_init', 'niesv_remove_default_widgets', 11);

/**
 * Disable WordPress default dashboard widgets
 */
function niesv_remove_dashboard_widgets() {
    global $wp_meta_boxes;

    // Remove default dashboard widgets
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
}
add_action('wp_dashboard_setup', 'niesv_remove_dashboard_widgets');

/**
 * Add custom NIESV dashboard widget
 */
function niesv_add_dashboard_widget() {
    wp_add_dashboard_widget(
        'niesv_dashboard_widget',
        'NIESV Theme Information',
        'niesv_dashboard_widget_content'
    );
}
add_action('wp_dashboard_setup', 'niesv_add_dashboard_widget');

/**
 * NIESV dashboard widget content
 */
function niesv_dashboard_widget_content() {
    echo '<div style="padding: 20px; background: linear-gradient(135deg, rgba(0,0,40,1) 0%, #49739c 100%); color: white; border-radius: 8px; margin: -12px -12px 12px -12px;">';
    echo '<h3 style="margin: 0 0 10px 0; color: #d4af37;">Welcome to NIESV Abuja Theme</h3>';
    echo '<p style="margin: 0; opacity: 0.9;">Manage your professional website with custom NIESV blocks and features.</p>';
    echo '</div>';

    echo '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 20px;">';

    // Quick stats
    $news_count = wp_count_posts('news')->publish;
    $events_count = wp_count_posts('events')->publish;
    $properties_count = wp_count_posts('properties')->publish;
    $team_count = wp_count_posts('team_members')->publish;

    echo '<div style="text-align: center; padding: 15px; background: #f8fafc; border-radius: 6px;">';
    echo '<strong style="font-size: 24px; color: rgba(0,0,40,1);">' . $news_count . '</strong><br>';
    echo '<span style="color: #49739c; font-size: 12px;">News Articles</span>';
    echo '</div>';

    echo '<div style="text-align: center; padding: 15px; background: #f8fafc; border-radius: 6px;">';
    echo '<strong style="font-size: 24px; color: rgba(0,0,40,1);">' . $events_count . '</strong><br>';
    echo '<span style="color: #49739c; font-size: 12px;">Events</span>';
    echo '</div>';

    echo '<div style="text-align: center; padding: 15px; background: #f8fafc; border-radius: 6px;">';
    echo '<strong style="font-size: 24px; color: rgba(0,0,40,1);">' . $properties_count . '</strong><br>';
    echo '<span style="color: #49739c; font-size: 12px;">Properties</span>';
    echo '</div>';

    echo '<div style="text-align: center; padding: 15px; background: #f8fafc; border-radius: 6px;">';
    echo '<strong style="font-size: 24px; color: rgba(0,0,40,1);">' . $team_count . '</strong><br>';
    echo '<span style="color: #49739c; font-size: 12px;">Team Members</span>';
    echo '</div>';

    echo '</div>';

    echo '<div style="margin-top: 20px; padding: 15px; background: #e7edf4; border-radius: 6px;">';
    echo '<h4 style="margin: 0 0 10px 0; color: rgba(0,0,40,1);">Quick Actions</h4>';
    echo '<p style="margin: 0;"><a href="' . admin_url('post-new.php?post_type=news') . '" style="color: rgba(0,0,40,1); text-decoration: none;">→ Add News Article</a></p>';
    echo '<p style="margin: 5px 0 0 0;"><a href="' . admin_url('post-new.php?post_type=events') . '" style="color: rgba(0,0,40,1); text-decoration: none;">→ Add Event</a></p>';
    echo '<p style="margin: 5px 0 0 0;"><a href="' . admin_url('admin.php?page=general-settings') . '" style="color: rgba(0,0,40,1); text-decoration: none;">→ Theme Options</a></p>';
    echo '</div>';
}

/**
 * Handle Contact Form Submission
 */
function niesv_handle_contact_form() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['niesv_contact_nonce'], 'niesv_contact_form')) {
        wp_die('Security check failed');
    }

    // Sanitize form data
    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $subject = sanitize_text_field($_POST['subject']);
    $message = sanitize_textarea_field($_POST['message']);

    // Validate required fields
    if (empty($first_name) || empty($last_name) || empty($email) || empty($subject) || empty($message)) {
        wp_redirect(add_query_arg('contact_error', 'missing_fields', wp_get_referer()));
        exit;
    }

    // Validate email
    if (!is_email($email)) {
        wp_redirect(add_query_arg('contact_error', 'invalid_email', wp_get_referer()));
        exit;
    }

    // Prepare email
    $to = get_option('admin_email');
    $email_subject = 'Contact Form: ' . $subject;
    $email_message = "New contact form submission:\n\n";
    $email_message .= "Name: {$first_name} {$last_name}\n";
    $email_message .= "Email: {$email}\n";
    $email_message .= "Phone: {$phone}\n";
    $email_message .= "Subject: {$subject}\n\n";
    $email_message .= "Message:\n{$message}\n";

    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . get_bloginfo('name') . ' <' . get_option('admin_email') . '>',
        'Reply-To: ' . $first_name . ' ' . $last_name . ' <' . $email . '>'
    );

    // Send email
    $sent = wp_mail($to, $email_subject, $email_message, $headers);

    if ($sent) {
        wp_redirect(add_query_arg('contact_success', '1', wp_get_referer()));
    } else {
        wp_redirect(add_query_arg('contact_error', 'send_failed', wp_get_referer()));
    }
    exit;
}
add_action('admin_post_niesv_contact_form', 'niesv_handle_contact_form');
add_action('admin_post_nopriv_niesv_contact_form', 'niesv_handle_contact_form');

/**
 * Display contact form messages
 */
function niesv_contact_form_messages() {
    if (isset($_GET['contact_success'])) {
        echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">';
        echo '<strong>Success!</strong> Your message has been sent successfully. We will get back to you soon.';
        echo '</div>';
    }

    if (isset($_GET['contact_error'])) {
        $error = $_GET['contact_error'];
        $message = 'An error occurred. Please try again.';

        switch ($error) {
            case 'missing_fields':
                $message = 'Please fill in all required fields.';
                break;
            case 'invalid_email':
                $message = 'Please enter a valid email address.';
                break;
            case 'send_failed':
                $message = 'Failed to send message. Please try again later.';
                break;
        }

        echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">';
        echo '<strong>Error!</strong> ' . $message;
        echo '</div>';
    }
}

/**
 * Custom query modifications for property search
 */
function niesv_modify_property_query($query) {
    if (!is_admin() && $query->is_main_query()) {
        if (is_post_type_archive('properties')) {
            // Handle search
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $search_term = sanitize_text_field($_GET['search']);
                $query->set('s', $search_term);
            }

            // Handle property type filter
            if (isset($_GET['property_type']) && !empty($_GET['property_type'])) {
                $property_type = sanitize_text_field($_GET['property_type']);
                $query->set('meta_query', array(
                    array(
                        'key' => 'property_type',
                        'value' => $property_type,
                        'compare' => '='
                    )
                ));
            }

            // Set posts per page
            $query->set('posts_per_page', 12);
        }
    }
}
add_action('pre_get_posts', 'niesv_modify_property_query');

/**
 * Set posts per page for News, Events, and Gallery archives
 */
function niesv_modify_archive_query($query) {
    if (!is_admin() && $query->is_main_query()) {
        // Set posts per page for News archive
        if (is_post_type_archive('news')) {
            $query->set('posts_per_page', 9);

            // On first page, exclude featured posts from main query since they're shown separately
            if (!is_paged()) {
                $query->set('meta_query', array(
                    array(
                        'key' => 'is_featured_news',
                        'value' => '1',
                        'compare' => '!='
                    )
                ));
            }
        }

        // Set 9 posts per page for Events archive
        if (is_post_type_archive('events')) {
            $query->set('posts_per_page', 9);
        }

        // Set 12 posts per page for Gallery archive
        if (is_post_type_archive('gallery')) {
            $query->set('posts_per_page', 12);
        }
    }
}
add_action('pre_get_posts', 'niesv_modify_archive_query');



/**
 * Property ownership and access control functions
 */

// Restrict property editing to owners only
function niesv_restrict_property_editing($caps, $cap, $user_id, $args) {
    if ($cap === 'edit_post' && isset($args[0])) {
        $post_id = $args[0];
        $post = get_post($post_id);

        if ($post && $post->post_type === 'properties') {
            // Allow administrators to edit any property
            if (user_can($user_id, 'manage_options')) {
                return $caps;
            }

            // Only allow the author to edit their own properties
            if ($post->post_author != $user_id) {
                $caps[] = 'do_not_allow';
            }
        }
    }

    return $caps;
}
add_filter('map_meta_cap', 'niesv_restrict_property_editing', 10, 4);

// Restrict property deletion to owners only
function niesv_restrict_property_deletion($caps, $cap, $user_id, $args) {
    if ($cap === 'delete_post' && isset($args[0])) {
        $post_id = $args[0];
        $post = get_post($post_id);

        if ($post && $post->post_type === 'properties') {
            // Allow administrators to delete any property
            if (user_can($user_id, 'manage_options')) {
                return $caps;
            }

            // Only allow the author to delete their own properties
            if ($post->post_author != $user_id) {
                $caps[] = 'do_not_allow';
            }
        }
    }

    return $caps;
}
add_filter('map_meta_cap', 'niesv_restrict_property_deletion', 10, 4);

// Set default author for new properties
function niesv_set_property_author($post_id) {
    if (get_post_type($post_id) === 'properties') {
        $post = get_post($post_id);

        // If no author is set and user is logged in, set current user as author
        if (!$post->post_author && is_user_logged_in()) {
            wp_update_post(array(
                'ID' => $post_id,
                'post_author' => get_current_user_id()
            ));
        }
    }
}
add_action('wp_insert_post', 'niesv_set_property_author');

// Redirect non-logged-in users trying to access property admin pages
function niesv_redirect_property_admin() {
    global $pagenow;

    if (is_admin() && !is_user_logged_in() &&
        ($pagenow === 'post-new.php' && isset($_GET['post_type']) && $_GET['post_type'] === 'properties')) {
        wp_redirect(home_url('/login/'));
        exit;
    }

    if (is_admin() && !is_user_logged_in() &&
        ($pagenow === 'post.php' && isset($_GET['post']) && get_post_type($_GET['post']) === 'properties')) {
        wp_redirect(home_url('/login/'));
        exit;
    }
}
add_action('admin_init', 'niesv_redirect_property_admin');

// Create default pages for authentication and property management
function niesv_create_auth_pages() {
    $pages = array(
        'login' => array(
            'title' => 'Login',
            'content' => 'Please log in to access your property dashboard.',
            'template' => 'page-login.php'
        ),
        'register' => array(
            'title' => 'Register',
            'content' => 'Create an account to manage your properties.',
            'template' => 'page-register.php'
        ),
        'property-dashboard' => array(
            'title' => 'Property Dashboard',
            'content' => 'Manage your property listings.',
            'template' => 'page-property-dashboard.php'
        ),
        'properties' => array(
            'title' => 'Property Listings',
            'content' => 'Browse available properties in the Abuja area.',
            'template' => 'page-properties.php'
        ),
        'add-property' => array(
            'title' => 'Add Property',
            'content' => 'Create a new property listing.',
            'template' => 'page-add-property.php'
        ),
        'edit-property' => array(
            'title' => 'Edit Property',
            'content' => 'Edit your property listing.',
            'template' => 'page-edit-property.php'
        )
    );

    foreach ($pages as $slug => $page_data) {
        // Check if page already exists
        $existing_page = get_page_by_path($slug);

        if (!$existing_page) {
            $page_id = wp_insert_post(array(
                'post_title' => $page_data['title'],
                'post_content' => $page_data['content'],
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_name' => $slug
            ));

            if (!is_wp_error($page_id) && isset($page_data['template'])) {
                update_post_meta($page_id, '_wp_page_template', $page_data['template']);
            }
        }
    }
}
add_action('after_setup_theme', 'niesv_create_auth_pages');

// Allow subscribers to create properties
function niesv_allow_subscribers_to_create_properties() {
    $subscriber = get_role('subscriber');
    if ($subscriber) {
        $subscriber->add_cap('edit_properties');
        $subscriber->add_cap('edit_published_properties');
        $subscriber->add_cap('publish_properties');
        $subscriber->add_cap('delete_properties');
        $subscriber->add_cap('delete_published_properties');
        $subscriber->add_cap('upload_files');
    }
}
add_action('init', 'niesv_allow_subscribers_to_create_properties');

// Register Member Area custom post type
function niesv_register_member_area_post_type() {
    $labels = array(
        'name'                  => _x('Member Area', 'Post type general name', 'niesv-theme'),
        'singular_name'         => _x('Member', 'Post type singular name', 'niesv-theme'),
        'menu_name'             => _x('Member Area', 'Admin Menu text', 'niesv-theme'),
        'name_admin_bar'        => _x('Member', 'Add New on Toolbar', 'niesv-theme'),
        'add_new'               => __('Add New', 'niesv-theme'),
        'add_new_item'          => __('Add New Member', 'niesv-theme'),
        'new_item'              => __('New Member', 'niesv-theme'),
        'edit_item'             => __('Edit Member', 'niesv-theme'),
        'view_item'             => __('View Member', 'niesv-theme'),
        'all_items'             => __('All Members', 'niesv-theme'),
        'search_items'          => __('Search Members', 'niesv-theme'),
        'parent_item_colon'     => __('Parent Members:', 'niesv-theme'),
        'not_found'             => __('No members found.', 'niesv-theme'),
        'not_found_in_trash'    => __('No members found in Trash.', 'niesv-theme'),
        'featured_image'        => _x('Member Photo', 'Overrides the "Featured Image" phrase', 'niesv-theme'),
        'set_featured_image'    => _x('Set member photo', 'Overrides the "Set featured image" phrase', 'niesv-theme'),
        'remove_featured_image' => _x('Remove member photo', 'Overrides the "Remove featured image" phrase', 'niesv-theme'),
        'use_featured_image'    => _x('Use as member photo', 'Overrides the "Use as featured image" phrase', 'niesv-theme'),
        'archives'              => _x('Member archives', 'The post type archive label', 'niesv-theme'),
        'insert_into_item'      => _x('Insert into member', 'Overrides the "Insert into post" phrase', 'niesv-theme'),
        'uploaded_to_this_item' => _x('Uploaded to this member', 'Overrides the "Uploaded to this post" phrase', 'niesv-theme'),
        'filter_items_list'     => _x('Filter members list', 'Screen reader text for the filter links', 'niesv-theme'),
        'items_list_navigation' => _x('Members list navigation', 'Screen reader text for the pagination', 'niesv-theme'),
        'items_list'            => _x('Members list', 'Screen reader text for the items list', 'niesv-theme'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'member-area'),
        'capability_type'    => 'post',
        'capabilities'       => array(
            'edit_post'          => 'manage_options',
            'read_post'          => 'manage_options',
            'delete_post'        => 'manage_options',
            'edit_posts'         => 'manage_options',
            'edit_others_posts'  => 'manage_options',
            'publish_posts'      => 'manage_options',
            'read_private_posts' => 'manage_options',
        ),
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 26,
        'menu_icon'          => 'dashicons-groups',
        'supports'           => array('title'),
        'show_in_rest'       => false,
    );

    register_post_type('member_area', $args);
}
add_action('init', 'niesv_register_member_area_post_type');

// Register ACF fields for Member Area
function niesv_register_member_area_fields() {
    if (function_exists('acf_add_local_field_group')) {
        acf_add_local_field_group(array(
            'key' => 'group_member_area',
            'title' => 'Member Information',
            'fields' => array(
                array(
                    'key' => 'field_member_membership_no',
                    'label' => 'Membership Number',
                    'name' => 'membership_no',
                    'type' => 'text',
                    'instructions' => 'Enter the unique NIESV membership number.',
                    'required' => 1,
                    'wrapper' => array(
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ),
                ),
                array(
                    'key' => 'field_member_initials',
                    'label' => 'Initials',
                    'name' => 'initials',
                    'type' => 'text',
                    'instructions' => 'Enter member initials (e.g., J.D.)',
                    'required' => 0,
                    'wrapper' => array(
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ),
                ),
                array(
                    'key' => 'field_member_full_name',
                    'label' => 'Full Name',
                    'name' => 'full_name',
                    'type' => 'text',
                    'instructions' => 'Enter the complete full name of the member.',
                    'required' => 1,
                    'wrapper' => array(
                        'width' => '100',
                        'class' => '',
                        'id' => '',
                    ),
                ),
                array(
                    'key' => 'field_member_email_address',
                    'label' => 'Email Address',
                    'name' => 'email_address',
                    'type' => 'email',
                    'instructions' => 'Enter the member\'s email address.',
                    'required' => 1,
                    'wrapper' => array(
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ),
                ),
                array(
                    'key' => 'field_member_telephone',
                    'label' => 'Telephone',
                    'name' => 'telephone',
                    'type' => 'text',
                    'instructions' => 'Enter the member\'s phone number.',
                    'required' => 0,
                    'wrapper' => array(
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ),
                ),
                array(
                    'key' => 'field_member_category',
                    'label' => 'Category',
                    'name' => 'category',
                    'type' => 'select',
                    'instructions' => 'Select the member category.',
                    'required' => 1,
                    'choices' => array(
                        'fellow' => 'Fellow (FNIVS)',
                        'associate' => 'Associate (ANIVS)',
                        'graduate' => 'Graduate (GNIVS)',
                        'student' => 'Student Member',
                        'corporate' => 'Corporate Member',
                    ),
                    'default_value' => 'associate',
                    'allow_null' => 0,
                    'multiple' => 0,
                    'ui' => 1,
                    'return_format' => 'value',
                    'ajax' => 0,
                    'wrapper' => array(
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ),
                ),
                array(
                    'key' => 'field_member_branch',
                    'label' => 'Branch',
                    'name' => 'branch',
                    'type' => 'select',
                    'instructions' => 'Select the member\'s branch.',
                    'required' => 1,
                    'choices' => array(
                        'abuja' => 'Abuja',
                        'lagos' => 'Lagos',
                        'kano' => 'Kano',
                        'port_harcourt' => 'Port Harcourt',
                        'enugu' => 'Enugu',
                        'ibadan' => 'Ibadan',
                        'kaduna' => 'Kaduna',
                        'benin' => 'Benin',
                        'jos' => 'Jos',
                        'maiduguri' => 'Maiduguri',
                        'other' => 'Other',
                    ),
                    'default_value' => 'abuja',
                    'allow_null' => 0,
                    'multiple' => 0,
                    'ui' => 1,
                    'return_format' => 'value',
                    'ajax' => 0,
                    'wrapper' => array(
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ),
                ),
                array(
                    'key' => 'field_member_status',
                    'label' => 'Membership Status',
                    'name' => 'membership_status',
                    'type' => 'select',
                    'instructions' => 'Select the current membership status.',
                    'required' => 1,
                    'choices' => array(
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'suspended' => 'Suspended',
                        'pending' => 'Pending',
                    ),
                    'default_value' => 'active',
                    'allow_null' => 0,
                    'multiple' => 0,
                    'ui' => 1,
                    'return_format' => 'value',
                    'ajax' => 0,
                    'wrapper' => array(
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ),
                ),
                array(
                    'key' => 'field_member_date_joined',
                    'label' => 'Date Joined',
                    'name' => 'date_joined',
                    'type' => 'date_picker',
                    'instructions' => 'Select the date the member joined NIESV.',
                    'required' => 0,
                    'display_format' => 'd/m/Y',
                    'return_format' => 'Y-m-d',
                    'first_day' => 1,
                    'wrapper' => array(
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ),
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'member_area',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => array(
                0 => 'the_content',
                1 => 'excerpt',
                2 => 'discussion',
                3 => 'comments',
                4 => 'revisions',
                5 => 'slug',
                6 => 'author',
                7 => 'format',
                8 => 'page_attributes',
                9 => 'featured_image',
                10 => 'categories',
                11 => 'tags',
                12 => 'send-trackbacks',
            ),
            'active' => true,
            'description' => 'Fields for managing member information in the member area.',
        ));
    }
}
add_action('acf/init', 'niesv_register_member_area_fields');

// Include member area admin interface
if (is_admin()) {
    require_once get_template_directory() . '/admin/member-area-admin.php';
    require_once get_template_directory() . '/admin/member-import-export.php';
}

// Membership verification functions
function niesv_verify_membership($membership_no, $email = '') {
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

    if (empty($member_query)) {
        return array(
            'verified' => false,
            'message' => 'Membership number not found in our records.',
            'member' => null
        );
    }

    $member = $member_query[0];
    $member_status = get_field('membership_status', $member->ID);

    if ($member_status !== 'active') {
        return array(
            'verified' => false,
            'message' => 'This membership is not active.',
            'member' => $member
        );
    }

    // Optional email verification
    if (!empty($email)) {
        $member_email = get_field('email_address', $member->ID);
        if (!empty($member_email) && strtolower($member_email) !== strtolower($email)) {
            return array(
                'verified' => false,
                'message' => 'Email address does not match our records.',
                'member' => $member
            );
        }
    }

    return array(
        'verified' => true,
        'message' => 'Membership verified successfully.',
        'member' => $member
    );
}

// Check if user has property access
function niesv_user_has_property_access($user_id = null) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }

    if (!$user_id) {
        return false;
    }

    // Check if user is verified member
    $membership_verified = get_user_meta($user_id, 'membership_verified', true);

    return $membership_verified === true || $membership_verified === '1';
}

// Restrict property creation to verified members
function niesv_restrict_property_creation($caps, $cap, $user_id, $args) {
    if ($cap === 'edit_properties' || $cap === 'publish_properties') {
        if (!niesv_user_has_property_access($user_id)) {
            return array('do_not_allow');
        }
    }

    return $caps;
}
add_filter('user_has_cap', 'niesv_restrict_property_creation', 10, 4);

// Add membership verification status to user profile
function niesv_add_membership_fields_to_profile($user) {
    if (!current_user_can('edit_user', $user->ID)) {
        return;
    }

    $membership_no = get_user_meta($user->ID, 'membership_no', true);
    $membership_verified = get_user_meta($user->ID, 'membership_verified', true);
    $member_category = get_user_meta($user->ID, 'member_category', true);
    $member_branch = get_user_meta($user->ID, 'member_branch', true);
    ?>

    <h3>NIESV Membership Information</h3>
    <table class="form-table">
        <tr>
            <th><label for="membership_no">Membership Number</label></th>
            <td>
                <input type="text" name="membership_no" id="membership_no" value="<?php echo esc_attr($membership_no); ?>" class="regular-text" />
                <p class="description">NIESV membership number</p>
            </td>
        </tr>
        <tr>
            <th><label for="membership_verified">Verification Status</label></th>
            <td>
                <select name="membership_verified" id="membership_verified">
                    <option value="0" <?php selected($membership_verified, false); ?>>Not Verified</option>
                    <option value="1" <?php selected($membership_verified, true); ?>>Verified</option>
                </select>
                <p class="description">Whether the membership has been verified against the member database</p>
            </td>
        </tr>
        <tr>
            <th><label for="member_category">Member Category</label></th>
            <td>
                <select name="member_category" id="member_category">
                    <option value="">Select Category</option>
                    <option value="fellow" <?php selected($member_category, 'fellow'); ?>>Fellow (FNIVS)</option>
                    <option value="associate" <?php selected($member_category, 'associate'); ?>>Associate (ANIVS)</option>
                    <option value="graduate" <?php selected($member_category, 'graduate'); ?>>Graduate (GNIVS)</option>
                    <option value="student" <?php selected($member_category, 'student'); ?>>Student Member</option>
                    <option value="corporate" <?php selected($member_category, 'corporate'); ?>>Corporate Member</option>
                </select>
                <p class="description">NIESV membership category</p>
            </td>
        </tr>
        <tr>
            <th><label for="member_branch">Branch</label></th>
            <td>
                <input type="text" name="member_branch" id="member_branch" value="<?php echo esc_attr($member_branch); ?>" class="regular-text" />
                <p class="description">NIESV branch</p>
            </td>
        </tr>
    </table>
    <?php
}
add_action('show_user_profile', 'niesv_add_membership_fields_to_profile');
add_action('edit_user_profile', 'niesv_add_membership_fields_to_profile');

// Save membership fields from user profile
function niesv_save_membership_fields_from_profile($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return;
    }

    if (isset($_POST['membership_no'])) {
        update_user_meta($user_id, 'membership_no', sanitize_text_field($_POST['membership_no']));
    }

    if (isset($_POST['membership_verified'])) {
        update_user_meta($user_id, 'membership_verified', $_POST['membership_verified'] === '1');
    }

    if (isset($_POST['member_category'])) {
        update_user_meta($user_id, 'member_category', sanitize_text_field($_POST['member_category']));
    }

    if (isset($_POST['member_branch'])) {
        update_user_meta($user_id, 'member_branch', sanitize_text_field($_POST['member_branch']));
    }
}
add_action('personal_options_update', 'niesv_save_membership_fields_from_profile');
add_action('edit_user_profile_update', 'niesv_save_membership_fields_from_profile');

// Redirect property dashboard to member dashboard for verified members
function niesv_redirect_property_dashboard() {
    if (is_page_template('page-property-dashboard.php')) {
        $current_user = wp_get_current_user();
        $membership_no = get_user_meta($current_user->ID, 'membership_no', true);

        if (!empty($membership_no)) {
            wp_redirect(home_url('/member-dashboard/'));
            exit;
        }
    }
}
add_action('template_redirect', 'niesv_redirect_property_dashboard');

// Add membership verification notice to property edit screen
function niesv_add_membership_notice_to_property_edit() {
    global $post;

    if ($post && $post->post_type === 'properties') {
        $current_user = wp_get_current_user();
        $membership_verified = get_user_meta($current_user->ID, 'membership_verified', true);

        if (!$membership_verified) {
            ?>
            <div class="notice notice-warning">
                <p><strong>Membership Verification Required:</strong> Your NIESV membership needs to be verified to publish properties. Please contact the administrator.</p>
            </div>
            <?php
        }
    }
}
add_action('admin_notices', 'niesv_add_membership_notice_to_property_edit');

// Modify property query to show only user's properties for non-verified members
function niesv_restrict_property_queries($query) {
    if (!is_admin() && $query->is_main_query()) {
        if (is_post_type_archive('properties') || (is_home() && get_query_var('post_type') === 'properties')) {
            // Allow all users to view published properties
            return;
        }
    }

    // In admin, restrict property list for non-verified members
    if (is_admin() && $query->is_main_query()) {
        global $pagenow;

        if ($pagenow === 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] === 'properties') {
            $current_user = wp_get_current_user();
            $membership_verified = get_user_meta($current_user->ID, 'membership_verified', true);

            // Non-verified members can only see their own properties
            if (!$membership_verified && !current_user_can('manage_options')) {
                $query->set('author', $current_user->ID);
            }
        }
    }
}
add_action('pre_get_posts', 'niesv_restrict_property_queries');

// Add membership status column to users list
function niesv_add_membership_column_to_users($columns) {
    $columns['membership_status'] = 'Membership Status';
    return $columns;
}
add_filter('manage_users_columns', 'niesv_add_membership_column_to_users');

function niesv_show_membership_column_content($value, $column_name, $user_id) {
    if ($column_name === 'membership_status') {
        $membership_no = get_user_meta($user_id, 'membership_no', true);
        $membership_verified = get_user_meta($user_id, 'membership_verified', true);

        if (empty($membership_no)) {
            return '<span style="color: #666;">No Membership</span>';
        }

        if ($membership_verified) {
            return '<span style="color: #46b450;">✓ Verified</span><br><small>' . esc_html($membership_no) . '</small>';
        } else {
            return '<span style="color: #dc3232;">✗ Not Verified</span><br><small>' . esc_html($membership_no) . '</small>';
        }
    }

    return $value;
}
add_filter('manage_users_custom_column', 'niesv_show_membership_column_content', 10, 3);

// Add bulk action to verify memberships
function niesv_add_bulk_actions_for_users($bulk_actions) {
    $bulk_actions['verify_membership'] = 'Verify Membership';
    $bulk_actions['unverify_membership'] = 'Unverify Membership';
    return $bulk_actions;
}
add_filter('bulk_actions-users', 'niesv_add_bulk_actions_for_users');

function niesv_handle_bulk_membership_actions($redirect_to, $doaction, $user_ids) {
    if ($doaction === 'verify_membership') {
        foreach ($user_ids as $user_id) {
            update_user_meta($user_id, 'membership_verified', true);
        }
        $redirect_to = add_query_arg('verified_memberships', count($user_ids), $redirect_to);
    } elseif ($doaction === 'unverify_membership') {
        foreach ($user_ids as $user_id) {
            update_user_meta($user_id, 'membership_verified', false);
        }
        $redirect_to = add_query_arg('unverified_memberships', count($user_ids), $redirect_to);
    }

    return $redirect_to;
}
add_filter('handle_bulk_actions-users', 'niesv_handle_bulk_membership_actions', 10, 3);

function niesv_show_bulk_action_notices() {
    if (isset($_REQUEST['verified_memberships'])) {
        $count = intval($_REQUEST['verified_memberships']);
        printf('<div class="notice notice-success is-dismissible"><p>%d membership(s) verified.</p></div>', $count);
    }

    if (isset($_REQUEST['unverified_memberships'])) {
        $count = intval($_REQUEST['unverified_memberships']);
        printf('<div class="notice notice-success is-dismissible"><p>%d membership(s) unverified.</p></div>', $count);
    }
}
add_action('admin_notices', 'niesv_show_bulk_action_notices');

// Handle profile update AJAX request
function niesv_handle_profile_update() {
    // Check nonce
    if (!wp_verify_nonce($_POST['profile_nonce'], 'niesv_update_profile')) {
        wp_send_json_error('Security check failed');
    }

    // Check if user is logged in
    if (!is_user_logged_in()) {
        wp_send_json_error('You must be logged in to update your profile');
    }

    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;

    // Sanitize input data
    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $bio = sanitize_textarea_field($_POST['bio']);

    // Validate email
    if (!is_email($email)) {
        wp_send_json_error('Please enter a valid email address');
    }

    // Check if email is already in use by another user
    if ($email !== $current_user->user_email) {
        $existing_user = get_user_by('email', $email);
        if ($existing_user && $existing_user->ID !== $user_id) {
            wp_send_json_error('This email address is already in use');
        }
    }

    // Update user data
    $user_data = array(
        'ID' => $user_id,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'user_email' => $email,
        'display_name' => $first_name . ' ' . $last_name,
    );

    $result = wp_update_user($user_data);

    if (is_wp_error($result)) {
        wp_send_json_error('Failed to update profile: ' . $result->get_error_message());
    }

    // Update user meta
    update_user_meta($user_id, 'phone', $phone);
    update_user_meta($user_id, 'description', $bio);

    wp_send_json_success('Profile updated successfully');
}
add_action('wp_ajax_update_profile', 'niesv_handle_profile_update');

// Create default pages for member functionality
function niesv_create_member_pages() {
    $pages = array(
        'member-dashboard' => array(
            'title' => 'Member Dashboard',
            'content' => 'This is the member dashboard page.',
            'template' => 'page-member-dashboard.php'
        ),
    );

    foreach ($pages as $slug => $page_data) {
        // Check if page already exists
        $existing_page = get_page_by_path($slug);

        if (!$existing_page) {
            $page_id = wp_insert_post(array(
                'post_title' => $page_data['title'],
                'post_content' => $page_data['content'],
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_name' => $slug
            ));

            if (!is_wp_error($page_id) && isset($page_data['template'])) {
                update_post_meta($page_id, '_wp_page_template', $page_data['template']);
            }
        }
    }
}
add_action('after_setup_theme', 'niesv_create_member_pages');

/**
 * Create default menus on theme activation
 */
function niesv_create_default_menus() {
    // Check if menus already exist
    $primary_menu = wp_get_nav_menu_object('Primary Menu');
    $footer_menu = wp_get_nav_menu_object('Footer Menu');

    if (!$primary_menu) {
        // Create Primary Menu
        $primary_menu_id = wp_create_nav_menu('Primary Menu');

        if (!is_wp_error($primary_menu_id)) {
            // Add menu items
            $menu_items = array(
                array(
                    'menu-item-title' => 'Home',
                    'menu-item-url' => home_url('/'),
                    'menu-item-status' => 'publish',
                    'menu-item-type' => 'custom'
                ),
                array(
                    'menu-item-title' => 'About Us',
                    'menu-item-url' => home_url('/about'),
                    'menu-item-status' => 'publish',
                    'menu-item-type' => 'custom'
                ),
                array(
                    'menu-item-title' => 'Membership',
                    'menu-item-url' => home_url('/membership'),
                    'menu-item-status' => 'publish',
                    'menu-item-type' => 'custom'
                ),
                array(
                    'menu-item-title' => 'Events',
                    'menu-item-url' => home_url('/events'),
                    'menu-item-status' => 'publish',
                    'menu-item-type' => 'custom'
                ),
                array(
                    'menu-item-title' => 'News',
                    'menu-item-url' => home_url('/news'),
                    'menu-item-status' => 'publish',
                    'menu-item-type' => 'custom'
                ),
                array(
                    'menu-item-title' => 'Properties',
                    'menu-item-url' => home_url('/properties'),
                    'menu-item-status' => 'publish',
                    'menu-item-type' => 'custom'
                ),
                array(
                    'menu-item-title' => 'Contact',
                    'menu-item-url' => home_url('/contact'),
                    'menu-item-status' => 'publish',
                    'menu-item-type' => 'custom'
                )
            );

            foreach ($menu_items as $item) {
                wp_update_nav_menu_item($primary_menu_id, 0, $item);
            }

            // Assign to theme location
            $locations = get_theme_mod('nav_menu_locations');
            $locations['primary'] = $primary_menu_id;
            set_theme_mod('nav_menu_locations', $locations);
        }
    }

    if (!$footer_menu) {
        // Create Footer Menu
        $footer_menu_id = wp_create_nav_menu('Footer Menu');

        if (!is_wp_error($footer_menu_id)) {
            // Add footer menu items
            $footer_items = array(
                array(
                    'menu-item-title' => 'About Us',
                    'menu-item-url' => home_url('/about'),
                    'menu-item-status' => 'publish',
                    'menu-item-type' => 'custom'
                ),
                array(
                    'menu-item-title' => 'Membership',
                    'menu-item-url' => home_url('/membership'),
                    'menu-item-status' => 'publish',
                    'menu-item-type' => 'custom'
                ),
                array(
                    'menu-item-title' => 'Events',
                    'menu-item-url' => home_url('/events'),
                    'menu-item-status' => 'publish',
                    'menu-item-type' => 'custom'
                ),
                array(
                    'menu-item-title' => 'News',
                    'menu-item-url' => home_url('/news'),
                    'menu-item-status' => 'publish',
                    'menu-item-type' => 'custom'
                ),
                array(
                    'menu-item-title' => 'Contact',
                    'menu-item-url' => home_url('/contact'),
                    'menu-item-status' => 'publish',
                    'menu-item-type' => 'custom'
                )
            );

            foreach ($footer_items as $item) {
                wp_update_nav_menu_item($footer_menu_id, 0, $item);
            }

            // Assign to theme location
            $locations = get_theme_mod('nav_menu_locations');
            $locations['footer'] = $footer_menu_id;
            set_theme_mod('nav_menu_locations', $locations);
        }
    }
}

/**
 * Create default pages on theme activation
 */
function niesv_create_default_pages() {
    $pages = array(
        'about' => array(
            'title' => 'About Us',
            'content' => 'Learn more about NIESV Abuja Branch, our mission, vision, and commitment to excellence in estate surveying and valuation.',
            'template' => 'page-about.php'
        ),
        'services' => array(
            'title' => 'Our Services',
            'content' => 'Discover the comprehensive range of professional estate surveying and valuation services offered by NIESV Abuja Branch.',
            'template' => 'page-services.php'
        ),
        'events' => array(
            'title' => 'Events',
            'content' => 'Stay updated with the latest events, conferences, workshops, and professional development opportunities from NIESV Abuja Branch.',
            'template' => 'page-events.php'
        ),
        'news' => array(
            'title' => 'News & Updates',
            'content' => 'Read the latest news, industry updates, and insights from NIESV Abuja Branch and the estate surveying profession.',
            'template' => 'page-news.php'
        ),
        'team' => array(
            'title' => 'Our Team',
            'content' => 'Meet the dedicated professionals who lead NIESV Abuja Branch and our esteemed members.',
            'template' => 'page-team.php'
        ),
        'contact' => array(
            'title' => 'Contact Us',
            'content' => 'Get in touch with NIESV Abuja Branch. We are here to help with all your estate surveying and valuation needs.',
            'template' => 'page-contact.php'
        ),
        'membership' => array(
            'title' => 'Membership',
            'content' => 'Join NIESV Abuja Branch and become part of a professional community dedicated to excellence in estate surveying and valuation.',
            'template' => 'page.php'
        )
    );

    foreach ($pages as $slug => $page_data) {
        // Check if page already exists
        $existing_page = get_page_by_path($slug);

        if (!$existing_page) {
            $page_id = wp_insert_post(array(
                'post_title' => $page_data['title'],
                'post_content' => $page_data['content'],
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_name' => $slug
            ));

            if (!is_wp_error($page_id) && isset($page_data['template'])) {
                update_post_meta($page_id, '_wp_page_template', $page_data['template']);
            }
        }
    }
}

/**
 * Create sample content for blocks
 */
function niesv_create_sample_content() {
    // Create sample news article
    $news_exists = get_posts(array('post_type' => 'news', 'posts_per_page' => 1));
    if (empty($news_exists)) {
        $news_id = wp_insert_post(array(
            'post_title' => 'Welcome to NIESV Abuja Branch',
            'post_content' => 'We are excited to launch our new website featuring the latest news, events, and resources for estate surveyors and valuers in the Federal Capital Territory.',
            'post_status' => 'publish',
            'post_type' => 'news',
            'post_author' => 1,
        ));

        if ($news_id) {
            update_field('news_category', 'announcement', $news_id);
            update_field('is_featured_news', true, $news_id);
        }
    }

    // Create sample event
    $events_exists = get_posts(array('post_type' => 'events', 'posts_per_page' => 1));
    if (empty($events_exists)) {
        $event_id = wp_insert_post(array(
            'post_title' => 'Annual Professional Development Conference',
            'post_content' => 'Join us for our annual conference featuring industry experts, networking opportunities, and continuing education credits.',
            'post_status' => 'publish',
            'post_type' => 'events',
            'post_author' => 1,
        ));

        if ($event_id) {
            update_field('event_date', date('Y-m-d', strtotime('+30 days')), $event_id);
            update_field('event_time', '9:00 AM - 5:00 PM', $event_id);
            update_field('event_location', 'NIESV Secretariat, Abuja', $event_id);
            update_field('event_type', 'conference', $event_id);
            update_field('event_organizer', 'NIESV Abuja Branch', $event_id);
        }
    }

    // Create sample property
    $properties_exists = get_posts(array('post_type' => 'properties', 'posts_per_page' => 1));
    if (empty($properties_exists)) {
        $property_id = wp_insert_post(array(
            'post_title' => 'Modern 3-Bedroom Apartment in Maitama',
            'post_content' => 'Beautiful modern apartment with stunning views, premium finishes, and excellent location in the heart of Maitama district.',
            'post_status' => 'publish',
            'post_type' => 'properties',
            'post_author' => 1,
        ));

        if ($property_id) {
            update_field('property_price', 45000000, $property_id);
            update_field('property_type', 'residential', $property_id);
            update_field('property_status', 'for_sale', $property_id);
            update_field('property_location', 'Maitama, Abuja', $property_id);
            update_field('property_size', '150 sqm', $property_id);
            update_field('property_bedrooms', 3, $property_id);
            update_field('property_bathrooms', 2, $property_id);
        }
    }

    // Create sample team member
    $team_exists = get_posts(array('post_type' => 'team_members', 'posts_per_page' => 1));
    if (empty($team_exists)) {
        $team_id = wp_insert_post(array(
            'post_title' => 'John Doe',
            'post_content' => 'John is a seasoned estate surveyor with over 15 years of experience in property valuation and development.',
            'post_status' => 'publish',
            'post_type' => 'team_members',
            'post_author' => 1,
        ));

        if ($team_id) {
            update_field('member_position', 'Chairman', $team_id);
            update_field('member_bio', 'John brings extensive experience in estate surveying and valuation to the NIESV Abuja Branch. He has worked on numerous high-profile projects across the Federal Capital Territory.', $team_id);
            update_field('member_qualifications', 'BSc Estate Management, MSc Property Development, FNIVS', $team_id);
            update_field('member_email', 'john.doe@niesvabuja.org', $team_id);
            update_field('member_phone', '+234 123 456 7890', $team_id);
        }
    }

    // Create sample news posts
    $news_exists = get_posts(array('post_type' => 'news', 'posts_per_page' => 1));
    if (empty($news_exists)) {
        // Sample news post 1
        $news_id_1 = wp_insert_post(array(
            'post_title' => 'NIESV Abuja Hosts Annual Conference',
            'post_content' => 'The Abuja Branch of the Nigerian Institution of Estate Surveyors and Valuers (NIESV) successfully hosted its annual conference, bringing together leading experts in estate surveying and valuation. The conference featured keynote presentations on emerging trends in property valuation, sustainable development practices, and the impact of technology on the real estate sector.',
            'post_excerpt' => 'The Abuja Branch of NIESV successfully hosted its annual conference, bringing together leading experts in estate surveying and valuation.',
            'post_status' => 'publish',
            'post_type' => 'news',
            'post_author' => 1,
            'post_date' => date('Y-m-d H:i:s', strtotime('-2 days')),
        ));

        // Sample news post 2
        $news_id_2 = wp_insert_post(array(
            'post_title' => 'New Real Estate Regulations Announced',
            'post_content' => 'The Federal Capital Territory Administration has announced new regulations affecting real estate development and property valuation in Abuja. These regulations aim to standardize practices and ensure sustainable development across the territory. NIESV Abuja Branch members are encouraged to familiarize themselves with these new requirements.',
            'post_excerpt' => 'The Federal Capital Territory Administration has announced new regulations affecting real estate development and property valuation in Abuja.',
            'post_status' => 'publish',
            'post_type' => 'news',
            'post_author' => 1,
            'post_date' => date('Y-m-d H:i:s', strtotime('-5 days')),
        ));

        // Sample news post 3
        $news_id_3 = wp_insert_post(array(
            'post_title' => 'Professional Development Workshop Series',
            'post_content' => 'NIESV Abuja Branch is pleased to announce a series of professional development workshops scheduled for the coming months. These workshops will cover advanced valuation techniques, property law updates, and emerging technologies in estate surveying. Registration is now open for all NIESV members.',
            'post_excerpt' => 'NIESV Abuja Branch announces a series of professional development workshops covering advanced valuation techniques and property law updates.',
            'post_status' => 'publish',
            'post_type' => 'news',
            'post_author' => 1,
            'post_date' => date('Y-m-d H:i:s', strtotime('-1 week')),
        ));
    }
}

/**
 * Theme activation hook
 */
function niesv_theme_activation() {
    niesv_create_default_menus();
    niesv_create_default_pages();
    niesv_create_sample_content();

    // Flush rewrite rules
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'niesv_theme_activation');

/**
 * Helper function to display hero section
 */
function niesv_display_hero($args = array()) {
    // Set default arguments
    $defaults = array(
        'title' => '',
        'subtitle' => '',
        'background_image' => '',
        'overlay_color' => 'rgba(30, 58, 138, 0.8)',
        'size' => 'small',
        'show_breadcrumb' => true,
    );

    $args = wp_parse_args($args, $defaults);

    // Auto-populate from ACF fields if not provided
    if (empty($args['title'])) {
        $args['title'] = get_field('hero_title') ?: get_the_title();
    }
    if (empty($args['subtitle'])) {
        $args['subtitle'] = get_field('hero_subtitle');
    }
    if (empty($args['background_image'])) {
        $hero_bg = get_field('hero_background_image');
        $args['background_image'] = is_array($hero_bg) ? $hero_bg['url'] : $hero_bg;
    }
    if ($args['overlay_color'] === 'rgba(0, 90, 49, 0.8)' && get_field('hero_overlay_color')) {
        $color = get_field('hero_overlay_color');
        if (strpos($color, '#') === 0) {
            $rgb = sscanf($color, "#%02x%02x%02x");
            $args['overlay_color'] = 'rgba(' . implode(',', $rgb) . ', 0.8)';
        }
    }
    if ($args['size'] === 'small') {
        $args['size'] = get_field('hero_size') ?: 'small';
    }
    if ($args['show_breadcrumb'] === true) {
        $args['show_breadcrumb'] = get_field('show_hero_breadcrumb') !== false;
    }

    get_template_part('template-parts/hero-section', null, $args);
}

/**
 * Ensure blocks are working - add admin notice if ACF is not active
 */
function niesv_admin_notices() {
    if (!function_exists('get_field')) {
        echo '<div class="notice notice-error"><p><strong>NIESV Theme:</strong> Advanced Custom Fields (ACF) plugin is required for the NIESV blocks to work. Please install and activate ACF Pro.</p></div>';
    }

    if (!function_exists('acf_register_block_type')) {
        echo '<div class="notice notice-error"><p><strong>NIESV Theme:</strong> ACF Pro is required for custom blocks. Please upgrade to ACF Pro to use NIESV blocks.</p></div>';
    }
}
add_action('admin_notices', 'niesv_admin_notices');

/**
 * Add body class for NIESV theme
 */
function niesv_body_class($classes) {
    $classes[] = 'niesv-theme';
    return $classes;
}
add_filter('body_class', 'niesv_body_class');

/**
 * Register ACF fields for WYSIWYG Content Block
 */
function niesv_register_wysiwyg_block_fields() {
    if (function_exists('acf_add_local_field_group')) {
        acf_add_local_field_group(array(
            'key' => 'group_wysiwyg_content_block',
            'title' => 'WYSIWYG Content Block',
            'fields' => array(
                array(
                    'key' => 'field_wysiwyg_content',
                    'label' => 'Content',
                    'name' => 'content',
                    'type' => 'wysiwyg',
                    'instructions' => 'Add your rich text content here. You can format text, add links, lists, and more.',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'tabs' => 'all',
                    'toolbar' => 'full',
                    'media_upload' => 1,
                    'delay' => 0,
                ),
                array(
                    'key' => 'field_wysiwyg_background_color',
                    'label' => 'Background Color',
                    'name' => 'background_color',
                    'type' => 'color_picker',
                    'instructions' => 'Choose a background color for this content block (optional).',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '33.33',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => 'transparent',
                    'enable_opacity' => 1,
                    'return_format' => 'string',
                ),
                array(
                    'key' => 'field_wysiwyg_text_color',
                    'label' => 'Text Color',
                    'name' => 'text_color',
                    'type' => 'color_picker',
                    'instructions' => 'Choose a text color for this content block (optional).',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '33.33',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => 'inherit',
                    'enable_opacity' => 1,
                    'return_format' => 'string',
                ),
                array(
                    'key' => 'field_wysiwyg_padding',
                    'label' => 'Padding',
                    'name' => 'padding',
                    'type' => 'select',
                    'instructions' => 'Choose the amount of padding around the content.',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '33.33',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        'none' => 'None',
                        'small' => 'Small',
                        'default' => 'Default',
                        'large' => 'Large',
                    ),
                    'default_value' => 'default',
                    'allow_null' => 0,
                    'multiple' => 0,
                    'ui' => 0,
                    'return_format' => 'value',
                    'ajax' => 0,
                    'placeholder' => '',
                ),
                array(
                    'key' => 'field_wysiwyg_max_width',
                    'label' => 'Content Width',
                    'name' => 'max_width',
                    'type' => 'select',
                    'instructions' => 'Choose the maximum width for the content.',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        'narrow' => 'Narrow (768px)',
                        'container' => 'Container (1200px)',
                        'wide' => 'Wide (1400px)',
                        'full' => 'Full Width',
                    ),
                    'default_value' => 'container',
                    'allow_null' => 0,
                    'multiple' => 0,
                    'ui' => 0,
                    'return_format' => 'value',
                    'ajax' => 0,
                    'placeholder' => '',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'block',
                        'operator' => '==',
                        'value' => 'acf/wysiwyg-content',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
        ));
    }
}
add_action('acf/init', 'niesv_register_wysiwyg_block_fields');



/**
 * Get social media icon SVG
 */
function niesv_get_social_icon($platform) {
    $icons = array(
        'facebook' => '<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"></path></svg>',
        'twitter' => '<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"></path></svg>',
        'instagram' => '<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"></path></svg>',
        'linkedin' => '<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"></path></svg>',
        'youtube' => '<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"></path></svg>',
        'whatsapp' => '<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M16.6 14.2c-.2-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1-.2.2-.6.7-.8.9s-.3.2-.6.1c-.2-.1-1.1-.4-2.1-1.3-.8-.7-1.3-1.5-1.5-1.8-.2-.3 0-.4.1-.5.1-.1.2-.2.4-.4.1-.1.2-.2.2-.4.1-.2 0-.4-.1-.5-.1-.1-.6-1.5-.8-2.1-.2-.5-.4-.5-.6-.5h-.5c-.2 0-.5.1-.7.3-.2.3-.9 1-.9 2.4s1 2.8 1.1 3c.1.2 1.8 2.8 4.4 3.9.6.2 1.1.4 1.5.5.6.2 1.1.1 1.5.1.5-.1 1.5-.6 1.7-1.2.2-.6.2-1.1.1-1.2s-.2-.2-.4-.3zM12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm0 18c-4.4 0-8-3.6-8-8s3.6-8 8-8 8 3.6 8 8-3.6 8-8 8z"></path></svg>',
        'telegram' => '<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"></path></svg>',
        'tiktok' => '<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"></path></svg>',
        'pinterest' => '<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24c6.624 0 11.99-5.367 11.99-12.013C24.007 5.367 18.641.001.012.001z"></path></svg>',
        'snapchat' => '<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24c6.624 0 11.99-5.367 11.99-12.013C24.007 5.367 18.641.001.012.001z"></path></svg>',
    );

    return isset($icons[$platform]) ? $icons[$platform] : '';
}

/**
 * Standardized pagination function for all listing pages
 */
function niesv_pagination($query = null, $screen_reader_text = 'Page navigation') {
    global $wp_query;

    // Use provided query or global query
    $pagination_query = $query ? $query : $wp_query;

    // Only show pagination if there are multiple pages
    if ($pagination_query->max_num_pages <= 1) {
        return;
    }

    echo '<div class="mt-12 flex justify-center">';

    // If custom query is provided, use paginate_links
    if ($query && $query !== $wp_query) {
        echo paginate_links(array(
            'total' => $query->max_num_pages,
            'current' => max(1, get_query_var('paged')),
            'format' => '?paged=%#%',
            'show_all' => false,
            'end_size' => 1,
            'mid_size' => 2,
            'prev_next' => true,
            'prev_text' => __('← Previous', 'niesv-theme'),
            'next_text' => __('Next →', 'niesv-theme'),
            'type' => 'plain',
            'before_page_number' => '<span class="screen-reader-text">' . __('Page', 'niesv-theme') . ' </span>',
        ));
    } else {
        // Use the_posts_pagination for main query
        the_posts_pagination(array(
            'mid_size' => 2,
            'prev_text' => __('← Previous', 'niesv-theme'),
            'next_text' => __('Next →', 'niesv-theme'),
            'class' => 'pagination',
            'screen_reader_text' => __($screen_reader_text, 'niesv-theme'),
            'before_page_number' => '<span class="screen-reader-text">' . __('Page', 'niesv-theme') . ' </span>',
        ));
    }

    echo '</div>';
}

/**
 * Ensure Tailwind CSS is loaded properly
 */
function niesv_add_tailwind_config() {
    if (!is_admin()) {
        ?>
        <script>
            // Ensure Tailwind is loaded
            if (typeof tailwind !== 'undefined') {
                console.log('NIESV: Tailwind CSS loaded successfully');
            } else {
                console.warn('NIESV: Tailwind CSS not loaded');
            }
        </script>
        <?php
    }
}
add_action('wp_footer', 'niesv_add_tailwind_config');

/**
 * Debug function to check if blocks are being processed
 */
function niesv_debug_blocks($content) {
    if (has_blocks($content)) {
        error_log('NIESV: Page has blocks');
        $blocks = parse_blocks($content);
        foreach ($blocks as $block) {
            error_log('NIESV: Block found - ' . $block['blockName']);
        }
    } else {
        error_log('NIESV: No blocks found in content');
    }
    return $content;
}
add_filter('the_content', 'niesv_debug_blocks', 5);

/**
 * Ensure blocks are rendered properly
 */
function niesv_ensure_block_rendering() {
    // Make sure block rendering is enabled
    if (!function_exists('do_blocks')) {
        error_log('NIESV: do_blocks function not available');
    } else {
        error_log('NIESV: Block rendering is available');
    }
}
add_action('init', 'niesv_ensure_block_rendering');

/**
 * PWA Functionality
 */

/**
 * Add PWA support to theme
 */
function niesv_pwa_support() {
    // Add theme support for PWA
    add_theme_support('pwa');

    // Register service worker
    add_action('wp_head', 'niesv_pwa_meta_tags');
    add_action('wp_head', 'niesv_social_meta_tags', 5); // Priority 5 to load early
    add_action('wp_footer', 'niesv_register_service_worker');
}
add_action('after_setup_theme', 'niesv_pwa_support');

/**
 * Generate proper robots.txt content - OVERRIDE WordPress privacy settings for social media
 */
function niesv_robots_txt($output, $public) {
    // ALWAYS allow social media crawlers regardless of WordPress privacy settings
    // This is crucial for social media sharing to work properly

    $output = "User-agent: *\n";
    $output .= "Allow: /\n";
    $output .= "Disallow: /wp-admin/\n";
    $output .= "Disallow: /wp-includes/\n";
    $output .= "Disallow: /wp-content/plugins/\n";
    $output .= "Disallow: /wp-content/themes/\n";
    $output .= "Disallow: /wp-content/uploads/ai1wm-backups/\n";
    $output .= "Disallow: /readme.html\n";
    $output .= "Disallow: /license.txt\n";
    $output .= "Disallow: /*?*\n"; // Block query parameters
    $output .= "Disallow: /search/\n";
    $output .= "Disallow: /author/\n";
    $output .= "Disallow: /tag/\n";
    $output .= "\n";

    // Explicitly allow social media crawlers
    $output .= "User-agent: facebookexternalhit/1.1\n";
    $output .= "Allow: /\n";
    $output .= "\n";

    $output .= "User-agent: facebookexternalhit\n";
    $output .= "Allow: /\n";
    $output .= "\n";

    $output .= "User-agent: Twitterbot\n";
    $output .= "Allow: /\n";
    $output .= "\n";

    $output .= "User-agent: LinkedInBot\n";
    $output .= "Allow: /\n";
    $output .= "\n";

    $output .= "User-agent: WhatsApp\n";
    $output .= "Allow: /\n";
    $output .= "\n";

    // Add sitemap
    $output .= "Sitemap: " . home_url('/sitemap.xml') . "\n";

    return $output;
}
add_filter('robots_txt', 'niesv_robots_txt', 99, 2); // High priority to override other plugins

/**
 * Override WordPress robots meta tag for social media crawlers
 */
function niesv_override_robots_meta($robots) {
    // Check if this is a social media crawler
    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

    $social_crawlers = [
        'facebookexternalhit',
        'Twitterbot',
        'LinkedInBot',
        'WhatsApp',
        'Slackbot',
        'TelegramBot'
    ];

    foreach ($social_crawlers as $crawler) {
        if (stripos($user_agent, $crawler) !== false) {
            // For social media crawlers, always allow indexing
            return [];
        }
    }

    return $robots;
}
add_filter('wp_robots', 'niesv_override_robots_meta', 99);

/**
 * Ensure robots.txt allows social media crawlers
 */
function niesv_ensure_robots_accessibility() {
    // Log if site visibility is set to private (for debugging)
    if (get_option('blog_public') != '1') {
        error_log('NIESV: Site visibility is set to private, but social media crawlers are still allowed via robots.txt override');
    }
}
add_action('init', 'niesv_ensure_robots_accessibility');

/**
 * Add PWA meta tags to head
 */
function niesv_pwa_meta_tags() {
    // Additional PWA meta tags if needed
    echo '<!-- PWA Meta Tags Added via Functions -->' . "\n";
}

/**
 * Add social media meta tags to head
 */
function niesv_social_meta_tags() {
    // Get current post/page data
    global $post;

    // Default values
    $site_name = get_field('site_title', 'option') ?: get_bloginfo('name');
    if (empty($site_name)) {
        $site_name = 'Nigerian Institution of Estate Surveyors and Valuers - Abuja Branch';
    }
    $site_description = get_bloginfo('description');
    $site_url = home_url();
    $current_url = is_front_page() ? $site_url : get_permalink();

    // Get page/post specific data
    if (is_singular()) {
        // Try to get ACF fields, but fallback to basic WordPress data
        $auto_populate = true; // Default to auto-populate
        $custom_title = '';
        $custom_description = '';
        $social_image = null;
        $twitter_handle = '';

        // Try to get ACF fields if available
        if (function_exists('get_field')) {
            $auto_populate_field = get_field('auto_populate_seo');
            if ($auto_populate_field !== null) {
                $auto_populate = $auto_populate_field;
            }

            $custom_title = get_field('seo_title');
            $custom_description = get_field('seo_description');
            $social_image = get_field('social_image');
            $twitter_handle = get_field('twitter_handle') ?: get_field('social_twitter', 'option');
        }

        // Clean up Twitter handle - remove full URLs and ensure proper format
        if ($twitter_handle) {
            // Remove full URLs and extract handle
            $twitter_handle = preg_replace('/https?:\/\/(www\.)?(twitter\.com\/|x\.com\/)/', '', $twitter_handle);
            $twitter_handle = ltrim($twitter_handle, '@');
            if (!empty($twitter_handle)) {
                $twitter_handle = '@' . $twitter_handle;
            }
        }

        // Generate or use custom title/description
        if ($auto_populate || empty($custom_title)) {
            // Auto-generate title and description
            $title = function_exists('niesv_generate_seo_title') ? niesv_generate_seo_title() : get_the_title();
            $description = function_exists('niesv_generate_seo_description') ? niesv_generate_seo_description() : get_the_excerpt();
        } else {
            // Use custom fields
            $title = $custom_title ?: get_the_title();
            $description = $custom_description ?: get_the_excerpt();
        }

        // Fallback for description
        if (empty($description)) {
            $description = wp_trim_words(strip_tags(get_the_content()), 25, '...');
        }

        // Fallback for title
        if (empty($title)) {
            $title = get_the_title();
        }

        // Ensure description is not empty and meaningful
        if (empty($description) || strlen(trim($description)) < 10) {
            $post_type_obj = get_post_type_object(get_post_type());
            $post_type_name = $post_type_obj ? $post_type_obj->labels->singular_name : 'Post';
            $description = "Read about " . get_the_title() . " - Latest " . strtolower($post_type_name) . " from " . $site_name;
        }

        // Get social image with better fallback
        if (!$social_image && has_post_thumbnail()) {
            $thumbnail_data = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
            if ($thumbnail_data) {
                $social_image = array(
                    'url' => $thumbnail_data[0],
                    'width' => $thumbnail_data[1],
                    'height' => $thumbnail_data[2],
                    'alt' => get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true) ?: get_the_title()
                );
            }
        }

        // Format Twitter handle
        if ($twitter_handle && strpos($twitter_handle, '@') !== 0) {
            $twitter_handle = '@' . $twitter_handle;
        }
    } else {
        // For archive pages, home page, etc.
        $title = is_front_page() ? $site_name : get_the_archive_title();
        $description = is_front_page() ? $site_description : get_the_archive_description();
        $social_image = null;
        $twitter_handle = get_field('social_twitter', 'option');

        // Clean up Twitter handle for non-singular pages too
        if ($twitter_handle) {
            $twitter_handle = preg_replace('/https?:\/\/(www\.)?(twitter\.com\/|x\.com\/)/', '', $twitter_handle);
            $twitter_handle = ltrim($twitter_handle, '@');
            if (!empty($twitter_handle)) {
                $twitter_handle = '@' . $twitter_handle;
            }
        }

        // Ensure description is meaningful for homepage
        if (is_front_page() && (empty($description) || strlen(trim($description)) < 10)) {
            $description = "Official website of the Nigerian Institution of Estate Surveyors and Valuers (NIESV) Abuja Branch - Professional estate surveying and valuation services.";
        }
    }

    // Fallback image - try theme option first, then default
    if (!$social_image) {
        // Try to get default social image from theme options
        if (function_exists('get_field')) {
            $default_social_image = get_field('default_social_image', 'option');
            if ($default_social_image && is_array($default_social_image) && isset($default_social_image['url'])) {
                $social_image = $default_social_image;
            }
        }

        // Ultimate fallback - use site icon or theme icon
        if (!$social_image) {
            $site_icon_id = get_option('site_icon');
            if ($site_icon_id) {
                $site_icon_data = wp_get_attachment_image_src($site_icon_id, 'full');
                if ($site_icon_data) {
                    $social_image = array(
                        'url' => $site_icon_data[0],
                        'width' => $site_icon_data[1],
                        'height' => $site_icon_data[2],
                        'alt' => $site_name . ' Logo'
                    );
                }
            } else {
                // Final fallback to theme icon
                $default_image_url = get_template_directory_uri() . '/assets/icons/icon-512x512.png';
                $social_image = array(
                    'url' => $default_image_url,
                    'width' => 512,
                    'height' => 512,
                    'alt' => $site_name . ' Logo'
                );
            }
        }
    }

    // Use custom social description if available
    if (empty($description) && function_exists('get_field')) {
        $social_description_override = get_field('site_description_social', 'option');
        if ($social_description_override) {
            $description = $social_description_override;
        }
    }

    // Final fallback for description
    if (empty($description)) {
        $description = $site_description ?: ($site_name . ' - Professional estate surveying and valuation services');
    }

    // Clean description
    if (!empty($description)) {
        $description = wp_strip_all_tags($description);
        $description = str_replace(array("\r", "\n", "\t"), ' ', $description);
        $description = preg_replace('/\s+/', ' ', $description);
        $description = trim($description);

        // Ensure description is within recommended length
        if (strlen($description) > 300) {
            $description = wp_trim_words($description, 30, '...');
        }
        if (strlen($description) < 55) {
            $description .= ' - ' . $site_name;
        }
    } else {
        $description = $site_description;
    }

    echo "\n";

    // Open Graph tags
    echo '<meta property="og:type" content="' . (is_front_page() ? 'website' : 'article') . '">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url($current_url) . '">' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr($site_name) . '">' . "\n";

    if ($social_image && isset($social_image['url'])) {
        echo '<meta property="og:image" content="' . esc_url($social_image['url']) . '">' . "\n";
        if (isset($social_image['width']) && isset($social_image['height'])) {
            echo '<meta property="og:image:width" content="' . esc_attr($social_image['width']) . '">' . "\n";
            echo '<meta property="og:image:height" content="' . esc_attr($social_image['height']) . '">' . "\n";
        }
        if (isset($social_image['alt'])) {
            echo '<meta property="og:image:alt" content="' . esc_attr($social_image['alt']) . '">' . "\n";
        }
    }

    // Twitter Card tags
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($title) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . "\n";

    if ($social_image && isset($social_image['url'])) {
        echo '<meta name="twitter:image" content="' . esc_url($social_image['url']) . '">' . "\n";
        if (isset($social_image['alt'])) {
            echo '<meta name="twitter:image:alt" content="' . esc_attr($social_image['alt']) . '">' . "\n";
        }
    }

    if ($twitter_handle) {
        echo '<meta name="twitter:site" content="' . esc_attr($twitter_handle) . '">' . "\n";
        echo '<meta name="twitter:creator" content="' . esc_attr($twitter_handle) . '">' . "\n";
    }

    // Additional meta tags
    echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";

    // Article specific tags
    if (is_singular() && !is_page()) {
        echo '<meta property="article:published_time" content="' . get_the_date('c') . '">' . "\n";
        echo '<meta property="article:modified_time" content="' . get_the_modified_date('c') . '">' . "\n";

        $author = get_the_author();
        if ($author) {
            echo '<meta property="article:author" content="' . esc_attr($author) . '">' . "\n";
        }

        $categories = get_the_category();
        if ($categories) {
            foreach ($categories as $category) {
                echo '<meta property="article:section" content="' . esc_attr($category->name) . '">' . "\n";
            }
        }

        $tags = get_the_tags();
        if ($tags) {
            foreach ($tags as $tag) {
                echo '<meta property="article:tag" content="' . esc_attr($tag->name) . '">' . "\n";
            }
        }
    }

    echo "\n";
}

/**
 * Display social sharing buttons
 *
 * @param array $args Optional arguments for customization
 */
function niesv_social_share($args = array()) {
    $defaults = array(
        'title' => 'Share this article',
        'show_whatsapp' => true,
        'show_email' => false,
    );

    $args = wp_parse_args($args, $defaults);

    // Only show on singular posts/pages
    if (!is_singular()) {
        return;
    }

    get_template_part('template-parts/social-share', null, $args);
}

/**
 * Get social sharing URLs for a post
 *
 * @param int $post_id Optional post ID, defaults to current post
 * @return array Array of social sharing URLs
 */
function niesv_get_social_share_urls($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $post_url = get_permalink($post_id);
    $post_title = get_the_title($post_id);
    $post_excerpt = get_the_excerpt($post_id);

    // Clean and prepare sharing text
    $share_text = $post_title;
    if (!empty($post_excerpt)) {
        $share_text .= ' - ' . wp_trim_words($post_excerpt, 15, '...');
    }

    // Get Twitter handle for mentions
    $twitter_handle = get_field('twitter_handle', $post_id) ?: get_field('social_twitter', 'option');
    $twitter_via = '';
    if ($twitter_handle) {
        $twitter_via = '&via=' . str_replace('@', '', $twitter_handle);
    }

    return array(
        'facebook' => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($post_url),
        'twitter' => 'https://twitter.com/intent/tweet?url=' . urlencode($post_url) . '&text=' . urlencode($share_text) . $twitter_via,
        'linkedin' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode($post_url),
        'whatsapp' => 'https://wa.me/?text=' . urlencode($share_text . ' ' . $post_url),
        'email' => 'mailto:?subject=' . urlencode($post_title) . '&body=' . urlencode($share_text . ' ' . $post_url),
        'pinterest' => 'https://pinterest.com/pin/create/button/?url=' . urlencode($post_url) . '&description=' . urlencode($share_text),
    );
}

/**
 * Generate SEO title automatically
 *
 * @param int $post_id Optional post ID, defaults to current post
 * @return string Generated SEO title
 */
function niesv_generate_seo_title($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $post_title = get_the_title($post_id);
    $site_name = get_bloginfo('name');
    $post_type = get_post_type($post_id);

    // Different title formats based on post type
    switch ($post_type) {
        case 'news':
            $category = get_the_terms($post_id, 'category');
            if ($category && !is_wp_error($category)) {
                $category_name = $category[0]->name;
                $title = $post_title . ' | ' . $category_name . ' | ' . $site_name;
            } else {
                $title = $post_title . ' | News | ' . $site_name;
            }
            break;

        case 'events':
            $event_date = get_field('event_date', $post_id);
            if ($event_date) {
                $formatted_date = date('M Y', strtotime($event_date));
                $title = $post_title . ' - ' . $formatted_date . ' | ' . $site_name;
            } else {
                $title = $post_title . ' | Events | ' . $site_name;
            }
            break;

        case 'properties':
            $property_type = get_field('property_type', $post_id);
            $location = get_field('property_location', $post_id);
            if ($property_type && $location) {
                $title = $post_title . ' - ' . ucfirst($property_type) . ' in ' . $location . ' | ' . $site_name;
            } elseif ($property_type) {
                $title = $post_title . ' - ' . ucfirst($property_type) . ' | ' . $site_name;
            } else {
                $title = $post_title . ' | Properties | ' . $site_name;
            }
            break;

        case 'gallery':
            $gallery_date = get_field('gallery_date', $post_id);
            if ($gallery_date) {
                $formatted_date = date('F Y', strtotime($gallery_date));
                $title = $post_title . ' - ' . $formatted_date . ' | Gallery | ' . $site_name;
            } else {
                $title = $post_title . ' | Gallery | ' . $site_name;
            }
            break;

        case 'page':
            if (is_front_page()) {
                $title = $site_name . ' - ' . get_bloginfo('description');
            } else {
                $title = $post_title . ' | ' . $site_name;
            }
            break;

        default:
            $title = $post_title . ' | ' . $site_name;
            break;
    }

    // Ensure title is within recommended length (60 characters)
    if (strlen($title) > 60) {
        $title = wp_trim_words($title, 8, '...');
    }

    return $title;
}

/**
 * Generate SEO description automatically
 *
 * @param int $post_id Optional post ID, defaults to current post
 * @return string Generated SEO description
 */
function niesv_generate_seo_description($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $post_type = get_post_type($post_id);
    $site_name = get_bloginfo('name');
    $excerpt = get_the_excerpt($post_id);
    $content = get_post_field('post_content', $post_id);

    // Start with excerpt or content
    if (!empty($excerpt)) {
        $description = wp_strip_all_tags($excerpt);
    } else {
        $description = wp_trim_words(wp_strip_all_tags($content), 25, '');
    }

    // If still empty or too short, create a meaningful description
    if (empty($description) || strlen(trim($description)) < 20) {
        $post_title = get_the_title($post_id);
        $post_type_obj = get_post_type_object($post_type);
        $post_type_name = $post_type_obj ? $post_type_obj->labels->singular_name : 'Post';

        if ($post_type === 'news') {
            $description = "Read about " . $post_title . " - Latest news and updates from " . $site_name;
        } elseif ($post_type === 'gallery') {
            $description = "View " . $post_title . " - Photo gallery from " . $site_name;
        } elseif ($post_type === 'event') {
            $description = "Learn about " . $post_title . " - Upcoming event from " . $site_name;
        } else {
            $description = "Read about " . $post_title . " - " . $post_type_name . " from " . $site_name;
        }
    }

    // Add context based on post type
    switch ($post_type) {
        case 'news':
            $category = get_the_terms($post_id, 'category');
            if ($category && !is_wp_error($category)) {
                $description .= ' | ' . $category[0]->name . ' from ' . $site_name;
            } else {
                $description .= ' | Latest news from ' . $site_name;
            }
            break;

        case 'events':
            $event_date = get_field('event_date', $post_id);
            $event_location = get_field('event_location', $post_id);
            if ($event_date) {
                $formatted_date = date('F j, Y', strtotime($event_date));
                $description .= ' | Event on ' . $formatted_date;
                if ($event_location) {
                    $description .= ' at ' . $event_location;
                }
            }
            $description .= ' | ' . $site_name;
            break;

        case 'properties':
            $property_type = get_field('property_type', $post_id);
            $location = get_field('property_location', $post_id);
            $price = get_field('property_price', $post_id);

            if ($property_type) {
                $description .= ' | ' . ucfirst($property_type);
            }
            if ($location) {
                $description .= ' in ' . $location;
            }
            if ($price) {
                $description .= ' - ₦' . number_format($price);
            }
            $description .= ' | ' . $site_name;
            break;

        case 'gallery':
            $gallery_date = get_field('gallery_date', $post_id);
            if ($gallery_date) {
                $formatted_date = date('F Y', strtotime($gallery_date));
                $description .= ' | Photo gallery from ' . $formatted_date;
            } else {
                $description .= ' | Photo gallery';
            }
            $description .= ' | ' . $site_name;
            break;

        case 'page':
            if (!is_front_page()) {
                $description .= ' | ' . $site_name;
            }
            break;

        default:
            $description .= ' | ' . $site_name;
            break;
    }

    // Clean up the description
    $description = str_replace(array("\r", "\n", "\t"), ' ', $description);
    $description = preg_replace('/\s+/', ' ', $description);
    $description = trim($description);

    // Ensure description is within recommended length (55-200 characters)
    if (strlen($description) > 200) {
        $description = wp_trim_words($description, 25, '...');
    }

    // Ensure minimum length
    if (strlen($description) < 55) {
        $site_description = get_bloginfo('description');
        if ($site_description) {
            $description .= ' - ' . $site_description;
        }
    }

    return $description;
}

/**
 * Auto-populate SEO fields when post is saved
 */
function niesv_auto_populate_seo_fields($post_id) {
    // Skip if this is an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Skip if user doesn't have permission to edit
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Skip if this is a revision
    if (wp_is_post_revision($post_id)) {
        return;
    }

    // Only process certain post types
    $post_type = get_post_type($post_id);
    $allowed_types = array('post', 'page', 'news', 'events', 'properties', 'gallery');
    if (!in_array($post_type, $allowed_types)) {
        return;
    }

    // Check if auto-populate is enabled (default to true for new posts)
    $auto_populate = get_field('auto_populate_seo', $post_id);
    if ($auto_populate === false || $auto_populate === null) {
        // For new posts, set auto-populate to true by default
        update_field('auto_populate_seo', true, $post_id);
        $auto_populate = true;
    }

    // Only auto-populate if enabled
    if ($auto_populate) {
        // Generate and save SEO title (for preview purposes, even though we generate it dynamically)
        $generated_title = niesv_generate_seo_title($post_id);
        update_field('seo_title_preview', $generated_title, $post_id);

        // Generate and save SEO description (for preview purposes)
        $generated_description = niesv_generate_seo_description($post_id);
        update_field('seo_description_preview', $generated_description, $post_id);
    }
}
add_action('save_post', 'niesv_auto_populate_seo_fields', 20);

/**
 * Add preview fields to show generated SEO content
 *
 * CRITICAL FIX: This function was previously using acf_get_field_group() and acf_update_field_group()
 * which queries and saves to the database on EVERY admin page load, creating 25,000+ duplicate entries!
 *
 * SOLUTION: Preview fields are now added directly in the field group definition in niesv_register_acf_fields()
 * This function is now DISABLED to prevent database duplication.
 *
 * If you need to modify the SEO field group, edit it in niesv_register_acf_fields() function above.
 */
function niesv_add_seo_preview_fields() {
    // DISABLED: This function was causing massive database duplication
    // Preview fields should be added directly in the field group definition
    // See niesv_register_acf_fields() function for the correct implementation
    return;

    /* ORIGINAL PROBLEMATIC CODE - DO NOT USE:
    // This code was querying the database and saving on every page load!
    $field_group = acf_get_field_group('group_seo_social'); // DATABASE QUERY!
    if ($field_group) {
        acf_update_field_group($field_group); // DATABASE SAVE - CREATES DUPLICATES!
    }
    */
}
// DISABLED: Commenting out this hook to prevent database duplication
// add_action('acf/init', 'niesv_add_seo_preview_fields', 20);

/**
 * Add admin styles for SEO preview fields
 */
function niesv_admin_seo_styles() {
    ?>
    <style>
        .seo-preview-field .acf-input {
            background-color: #f8f9fa !important;
            border: 1px solid #e9ecef !important;
        }

        .seo-preview-field .acf-input input,
        .seo-preview-field .acf-input textarea {
            background-color: #f8f9fa !important;
            color: #6c757d !important;
            font-style: italic;
        }

        .seo-preview-field .acf-label label {
            color: #28a745 !important;
            font-weight: 600;
        }

        .seo-preview-field .acf-label .description {
            color: #28a745 !important;
            font-size: 12px;
        }

        /* Add a small icon to indicate auto-generated content */
        .seo-preview-field .acf-label label:before {
            content: "🤖 ";
            margin-right: 5px;
        }

        /* Style the auto-populate toggle */
        .acf-field[data-name="auto_populate_seo"] .acf-label label {
            font-weight: 600;
            color: #007cba;
        }

        .acf-field[data-name="auto_populate_seo"] .acf-true-false {
            background: #f0f8ff;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #cce7ff;
        }
    </style>
    <?php
}
add_action('admin_head', 'niesv_admin_seo_styles');

/**
 * Add JavaScript for SEO preview updates
 */
function niesv_admin_seo_scripts() {
    global $post_type;
    $allowed_types = array('post', 'page', 'news', 'events', 'properties', 'gallery');

    if (!in_array($post_type, $allowed_types)) {
        return;
    }
    ?>
    <script>
    jQuery(document).ready(function($) {
        // Function to update SEO previews
        function updateSEOPreviews() {
            var autoPopulate = $('input[name="acf[field_auto_populate_seo]"]:checked').val();

            if (autoPopulate == '1') {
                var postTitle = $('#title').val() || $('input[name="post_title"]').val();
                var postContent = '';

                // Get content from different editors
                if (typeof tinymce !== 'undefined' && tinymce.get('content')) {
                    postContent = tinymce.get('content').getContent({format: 'text'});
                } else if ($('#content').length) {
                    postContent = $('#content').val();
                }

                // Update preview fields with simulated content
                if (postTitle) {
                    var siteTitle = '<?php echo esc_js(get_bloginfo("name")); ?>';
                    var postType = '<?php echo esc_js($post_type); ?>';

                    // Generate preview title
                    var previewTitle = postTitle;
                    if (postType === 'news') {
                        previewTitle += ' | News | ' + siteTitle;
                    } else if (postType === 'events') {
                        previewTitle += ' | Events | ' + siteTitle;
                    } else if (postType === 'properties') {
                        previewTitle += ' | Properties | ' + siteTitle;
                    } else if (postType === 'gallery') {
                        previewTitle += ' | Gallery | ' + siteTitle;
                    } else {
                        previewTitle += ' | ' + siteTitle;
                    }

                    // Truncate if too long
                    if (previewTitle.length > 60) {
                        previewTitle = previewTitle.substring(0, 57) + '...';
                    }

                    $('input[name="acf[field_seo_title_preview]"]').val(previewTitle);
                }

                // Generate preview description
                if (postContent) {
                    var previewDesc = postContent.substring(0, 150).replace(/\s+/g, ' ').trim();
                    if (previewDesc.length < postContent.length) {
                        previewDesc += '...';
                    }
                    previewDesc += ' | <?php echo esc_js(get_bloginfo("name")); ?>';

                    $('textarea[name="acf[field_seo_description_preview]"]').val(previewDesc);
                }
            }
        }

        // Update previews when title changes
        $('#title, input[name="post_title"]').on('input keyup', function() {
            setTimeout(updateSEOPreviews, 500);
        });

        // Update previews when content changes
        if (typeof tinymce !== 'undefined') {
            $(document).on('tinymce-editor-init', function(event, editor) {
                if (editor.id === 'content') {
                    editor.on('input keyup', function() {
                        setTimeout(updateSEOPreviews, 1000);
                    });
                }
            });
        }

        // Update previews when auto-populate toggle changes
        $(document).on('change', 'input[name="acf[field_auto_populate_seo]"]', function() {
            setTimeout(updateSEOPreviews, 100);
        });

        // Initial update
        setTimeout(updateSEOPreviews, 1000);
    });
    </script>
    <?php
}
add_action('admin_footer', 'niesv_admin_seo_scripts');



/**
 * Register service worker
 */
function niesv_register_service_worker() {
    ?>
    <script>
        // Service Worker Registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                // Register service worker from root to have full scope
                navigator.serviceWorker.register('<?php echo home_url('/sw.js'); ?>', {
                    scope: '/'
                }).then(function(registration) {
                    console.log('NIESV PWA: Service Worker registered successfully:', registration.scope);

                    // Check for updates
                    registration.addEventListener('updatefound', function() {
                        const newWorker = registration.installing;
                        newWorker.addEventListener('statechange', function() {
                            if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                // New content is available, show update notification
                                niesv_showUpdateNotification();
                            }
                        });
                    });

                }).catch(function(error) {
                    console.log('NIESV PWA: Service Worker registration failed:', error);
                    console.log('NIESV PWA: Make sure you are accessing the site via HTTP/HTTPS, not file://');
                });
            });
        } else {
            console.log('NIESV PWA: Service Worker not supported in this browser');
        }

        // Show update notification
        function niesv_showUpdateNotification() {
            if (confirm('A new version of NIESV app is available. Would you like to update?')) {
                window.location.reload();
            }
        }
    </script>
    <?php
}

/**
 * Handle manifest.json requests
 */
function niesv_serve_manifest() {
    // Check if request is for manifest.json
    $request_uri = $_SERVER['REQUEST_URI'];
    if (strpos($request_uri, 'manifest.json') !== false || isset($_GET['manifest'])) {
        header('Content-Type: application/json');
        header('Cache-Control: public, max-age=86400'); // Cache for 1 day

        $manifest_path = get_template_directory() . '/manifest.json';
        if (file_exists($manifest_path)) {
            readfile($manifest_path);
        } else {
            // Fallback manifest
            echo json_encode(array(
                'name' => 'NIESV',
                'short_name' => 'NIESV',
                'start_url' => '/',
                'display' => 'standalone',
                'theme_color' => '#000028',
                'background_color' => '#f8fafc'
            ));
        }
        exit;
    }
}
add_action('init', 'niesv_serve_manifest');

/**
 * Handle service worker requests
 */
function niesv_serve_service_worker() {
    // Check if request is for sw.js
    $request_uri = $_SERVER['REQUEST_URI'];
    if (strpos($request_uri, 'sw.js') !== false || isset($_GET['sw'])) {
        header('Content-Type: application/javascript');
        header('Service-Worker-Allowed: /');
        header('Cache-Control: no-cache'); // Don't cache service worker

        $sw_path = get_template_directory() . '/sw.js';
        if (file_exists($sw_path)) {
            readfile($sw_path);
        } else {
            // Fallback minimal service worker
            echo "console.log('NIESV PWA: Fallback service worker loaded');";
        }
        exit;
    }
}
add_action('init', 'niesv_serve_service_worker');

/**
 * Add PWA install prompt functionality
 */
function niesv_pwa_install_prompt() {
    ?>
    <div id="pwa-install-banner" class="fixed bottom-4 left-4 right-4 bg-[var(--primary-color)] text-white p-4 rounded-lg shadow-lg z-50 hidden">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h3 class="font-bold text-lg">Install NIESV App</h3>
                <p class="text-sm opacity-90">Get quick access to NIESV services on your device</p>
            </div>
            <div class="flex gap-2 ml-4">
                <button id="pwa-install-btn" class="bg-[var(--accent-gold)] text-[var(--primary-color)] px-4 py-2 rounded font-medium hover:bg-opacity-90 transition-colors">
                    Install
                </button>
                <button id="pwa-dismiss-btn" class="text-white hover:text-gray-300 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- PWA Caching Settings Toggle -->
    <div id="pwa-caching-toggle" class="fixed right-4 z-50 hidden" style="top: 50%; transform: translateY(-50%);">
        <button id="pwa-caching-btn" class="bg-gray-800 text-white p-2 rounded-full shadow-lg hover:bg-gray-700 transition-colors" title="PWA Caching Settings">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
        </button>

        <div id="pwa-caching-menu" class="absolute top-0 right-12 bg-white rounded-lg shadow-xl border border-gray-200 p-4 w-64 hidden">
            <h4 class="font-semibold text-gray-800 mb-3">PWA Caching Mode</h4>
            <div class="space-y-2">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="radio" name="pwa-caching-mode" value="minimal" class="text-blue-600" checked>
                    <span class="text-sm">Minimal (Better for browsing)</span>
                </label>
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="radio" name="pwa-caching-mode" value="full" class="text-blue-600">
                    <span class="text-sm">Full (Better for PWA)</span>
                </label>
            </div>
            <button id="apply-caching-mode" class="mt-3 w-full bg-blue-600 text-white py-2 px-4 rounded text-sm hover:bg-blue-700 transition-colors">
                Apply
            </button>
        </div>
    </div>

    <script>
    // PWA Caching Settings Toggle
    document.addEventListener('DOMContentLoaded', function() {
        const toggle = document.getElementById('pwa-caching-toggle');
        const btn = document.getElementById('pwa-caching-btn');
        const menu = document.getElementById('pwa-caching-menu');
        const applyBtn = document.getElementById('apply-caching-mode');

        // Show toggle only if PWA functions are available
        if (window.niesvPWA) {
            toggle.classList.remove('hidden');

            // Load current preference
            const currentMode = window.niesvPWA.getCachingMode();
            const radio = document.querySelector(`input[name="pwa-caching-mode"][value="${currentMode}"]`);
            if (radio) radio.checked = true;
        }

        // Toggle menu
        btn.addEventListener('click', function() {
            menu.classList.toggle('hidden');
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!toggle.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });

        // Apply caching mode
        applyBtn.addEventListener('click', function() {
            const selectedMode = document.querySelector('input[name="pwa-caching-mode"]:checked').value;
            if (window.niesvPWA) {
                window.niesvPWA.setCachingMode(selectedMode);
                menu.classList.add('hidden');

                // Show brief confirmation
                btn.style.backgroundColor = '#10b981';
                setTimeout(() => {
                    btn.style.backgroundColor = '';
                }, 1000);
            }
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'niesv_pwa_install_prompt');

/**
 * Add offline page support
 */
function niesv_add_offline_page() {
    // Check if offline page exists
    $offline_page = get_page_by_path('offline');
    if (!$offline_page) {
        // Create offline page
        wp_insert_post(array(
            'post_title' => 'Offline',
            'post_name' => 'offline',
            'post_content' => '[offline_content]',
            'post_status' => 'publish',
            'post_type' => 'page'
        ));
    }

    // Check if PWA test page exists
    $pwa_test_page = get_page_by_path('pwa-test');
    if (!$pwa_test_page) {
        // Create PWA test page
        wp_insert_post(array(
            'post_title' => 'PWA Test',
            'post_name' => 'pwa-test',
            'post_content' => 'This page tests PWA functionality.',
            'post_status' => 'publish',
            'post_type' => 'page'
        ));
    }
}
add_action('after_switch_theme', 'niesv_add_offline_page');

/**
 * Handle offline content shortcode
 */
function niesv_offline_content_shortcode() {
    ob_start();
    ?>
    <div class="text-center py-16">
        <div class="max-w-md mx-auto">
            <svg class="w-24 h-24 mx-auto mb-8 text-[var(--text-secondary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 109.75 9.75A9.75 9.75 0 0012 2.25z"></path>
            </svg>
            <h1 class="text-3xl font-bold text-[var(--text-primary)] mb-4">You're Offline</h1>
            <p class="text-[var(--text-secondary)] mb-8">
                It looks like you're not connected to the internet. Don't worry, you can still browse some of the content you've previously visited.
            </p>
            <div class="space-y-4">
                <button onclick="window.location.reload()" class="w-full bg-[var(--primary-color)] text-white py-3 px-6 rounded-lg hover:bg-opacity-90 transition-colors">
                    Try Again
                </button>
                <a href="/" class="block w-full bg-[var(--secondary-color)] text-[var(--primary-color)] py-3 px-6 rounded-lg hover:bg-opacity-90 transition-colors">
                    Go to Homepage
                </a>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('offline_content', 'niesv_offline_content_shortcode');

/**
 * Include Cache Clearing Functionality
 */
require_once get_template_directory() . '/cache-clear.php';

/**
 * PWA Notification REST API Endpoint
 */
function niesv_register_pwa_notification_endpoint() {
    register_rest_route('niesv/v1', '/pwa-notification', array(
        'methods' => 'GET',
        'callback' => 'niesv_get_pwa_notification_data',
        'permission_callback' => '__return_true',
    ));
}
add_action('rest_api_init', 'niesv_register_pwa_notification_endpoint');

/**
 * Get PWA Notification Data
 */
function niesv_get_pwa_notification_data() {
    $enabled = get_field('enable_pwa_notifications', 'option');

    if (!$enabled) {
        return array('enabled' => false);
    }

    $title = get_field('updates_title', 'option');
    $headline = get_field('update_headline', 'option');
    $description = get_field('update_description', 'option');
    $button_text = get_field('update_button_text', 'option');
    $button_link = get_field('update_button_link', 'option');
    $hash = get_field('notification_hash', 'option');

    // Generate hash if not exists
    if (!$hash && ($title || $headline || $description)) {
        $hash = md5($title . $headline . $description . time());
        update_field('notification_hash', $hash, 'option');
    }

    return array(
        'enabled' => true,
        'title' => $title ?: 'NIESV Update',
        'headline' => $headline ?: '',
        'description' => $description ?: '',
        'button_text' => $button_text ?: '',
        'button_link' => $button_link ?: '',
        'hash' => $hash
    );
}

/**
 * Auto-generate notification hash when fields are updated
 */
function niesv_update_notification_hash($value, $post_id, $field) {
    // Check if this is one of the notification fields
    $notification_fields = array('updates_title', 'update_headline', 'update_description', 'update_button_text', 'update_button_link');

    if (in_array($field['name'], $notification_fields) && $post_id === 'options') {
        // Generate new hash when notification content changes
        $new_hash = md5(uniqid() . time());
        update_field('notification_hash', $new_hash, 'option');
    }

    return $value;
}
add_filter('acf/update_value', 'niesv_update_notification_hash', 10, 3);
