<?php
/**
 * Cache Clearing Utility for NIESV Theme
 * 
 * This file provides functions to clear various types of cache
 * on the cloud server to help with development and debugging.
 * 
 * Usage: Add ?clear_cache=all to any URL to clear all caches
 * Or use specific cache types: ?clear_cache=wp or ?clear_cache=opcache
 */

// Security check - only allow cache clearing in development or for admins
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Initialize cache clearing functionality
 */
if (!function_exists('niesv_init_cache_clearing')) {
function niesv_init_cache_clearing() {
    // Only allow cache clearing for administrators or in development
    if (is_user_logged_in() && (current_user_can('administrator') || WP_DEBUG || isset($_GET['force_clear']))) {
        add_action('wp_loaded', 'niesv_handle_cache_clear_request');
    }
}
}
add_action('init', 'niesv_init_cache_clearing');

/**
 * Handle cache clearing requests
 */
function niesv_handle_cache_clear_request() {
    if (isset($_GET['clear_cache'])) {
        $cache_type = sanitize_text_field($_GET['clear_cache']);
        $results = niesv_clear_cache($cache_type);
        
        // Display results
        if (!empty($results)) {
            add_action('wp_head', function() use ($results) {
                echo '<script>console.log("Cache Clear Results:", ' . json_encode($results) . ');</script>';
            });
            
            // Show admin notice if in admin area
            if (is_admin()) {
                add_action('admin_notices', function() use ($results) {
                    echo '<div class="notice notice-success"><p><strong>Cache Cleared:</strong><br>';
                    foreach ($results as $type => $result) {
                        echo $type . ': ' . ($result ? 'Success' : 'Failed') . '<br>';
                    }
                    echo '</p></div>';
                });
            }
        }
    }
}

/**
 * Clear various types of cache
 */
function niesv_clear_cache($type = 'all') {
    $results = array();
    
    switch ($type) {
        case 'all':
            $results['wordpress'] = niesv_clear_wordpress_cache();
            $results['opcache'] = niesv_clear_opcache();
            $results['object_cache'] = niesv_clear_object_cache();
            $results['transients'] = niesv_clear_transients();
            $results['rewrite_rules'] = niesv_cache_flush_rewrite_rules();
            $results['pwa_cache'] = niesv_clear_pwa_cache();
            break;
            
        case 'wp':
        case 'wordpress':
            $results['wordpress'] = niesv_clear_wordpress_cache();
            break;
            
        case 'opcache':
            $results['opcache'] = niesv_clear_opcache();
            break;
            
        case 'object':
            $results['object_cache'] = niesv_clear_object_cache();
            break;
            
        case 'transients':
            $results['transients'] = niesv_clear_transients();
            break;
            
        case 'rewrite':
            $results['rewrite_rules'] = niesv_cache_flush_rewrite_rules();
            break;

        case 'pwa':
            $results['pwa_cache'] = niesv_clear_pwa_cache();
            break;
    }

    return $results;
}

/**
 * Clear WordPress cache
 */
function niesv_clear_wordpress_cache() {
    $cleared = false;
    
    // Clear WordPress core cache
    if (function_exists('wp_cache_flush')) {
        wp_cache_flush();
        $cleared = true;
    }
    
    // Clear W3 Total Cache
    if (function_exists('w3tc_flush_all')) {
        w3tc_flush_all();
        $cleared = true;
    }
    
    // Clear WP Super Cache
    if (function_exists('wp_cache_clear_cache')) {
        wp_cache_clear_cache();
        $cleared = true;
    }
    
    // Clear WP Rocket
    if (function_exists('rocket_clean_domain')) {
        rocket_clean_domain();
        $cleared = true;
    }
    
    // Clear LiteSpeed Cache
    if (class_exists('LiteSpeed_Cache_API')) {
        LiteSpeed_Cache_API::purge_all();
        $cleared = true;
    }
    
    return $cleared;
}

/**
 * Clear PHP OPcache
 */
function niesv_clear_opcache() {
    if (function_exists('opcache_reset')) {
        return opcache_reset();
    }
    return false;
}

/**
 * Clear object cache
 */
function niesv_clear_object_cache() {
    global $wp_object_cache;
    
    if (is_object($wp_object_cache)) {
        $wp_object_cache->flush();
        return true;
    }
    
    return false;
}

/**
 * Clear transients
 */
function niesv_clear_transients() {
    global $wpdb;
    
    // Delete expired transients
    $expired = $wpdb->query(
        "DELETE a, b FROM {$wpdb->options} a, {$wpdb->options} b
        WHERE a.option_name LIKE '_transient_%'
        AND a.option_name NOT LIKE '_transient_timeout_%'
        AND b.option_name = CONCAT('_transient_timeout_', SUBSTRING(a.option_name, 12))
        AND b.option_value < UNIX_TIMESTAMP()"
    );
    
    // Optionally delete all transients (uncomment if needed)
    /*
    $all = $wpdb->query(
        "DELETE FROM {$wpdb->options}
        WHERE option_name LIKE '_transient_%'"
    );
    */
    
    return $expired !== false;
}

/**
 * Flush rewrite rules for cache clearing
 */
function niesv_cache_flush_rewrite_rules() {
    flush_rewrite_rules(true);
    return true;
}

/**
 * Clear PWA cache by sending message to service worker
 */
function niesv_clear_pwa_cache() {
    // This function provides a server-side trigger for PWA cache clearing
    // The actual cache clearing happens client-side via JavaScript

    // Set a transient that the PWA script can check for
    set_transient('niesv_pwa_clear_cache', time(), 300); // 5 minutes

    // Add JavaScript to trigger cache clearing on next page load
    add_action('wp_footer', function() {
        ?>
        <script>
        // Clear PWA cache if service worker is available
        if ('serviceWorker' in navigator && 'caches' in window) {
            // Check if we should clear cache
            const shouldClear = <?php echo get_transient('niesv_pwa_clear_cache') ? 'true' : 'false'; ?>;

            if (shouldClear) {
                // Clear all NIESV PWA caches
                caches.keys().then(function(cacheNames) {
                    const niesvCaches = cacheNames.filter(name => name.startsWith('niesv-pwa-'));
                    return Promise.all(
                        niesvCaches.map(function(cacheName) {
                            console.log('Clearing PWA cache:', cacheName);
                            return caches.delete(cacheName);
                        })
                    );
                }).then(function() {
                    console.log('PWA caches cleared successfully');

                    // Send message to service worker to update
                    if (navigator.serviceWorker.controller) {
                        navigator.serviceWorker.controller.postMessage({
                            type: 'SKIP_WAITING'
                        });
                    }
                }).catch(function(error) {
                    console.error('Failed to clear PWA caches:', error);
                });

                // Clear the transient via AJAX
                fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=niesv_clear_pwa_cache_transient&nonce=<?php echo wp_create_nonce('niesv_pwa_clear'); ?>'
                });
            }
        }
        </script>
        <?php
    });

    return true;
}

/**
 * AJAX handler to clear PWA cache transient
 */
function niesv_clear_pwa_cache_transient() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'niesv_pwa_clear')) {
        wp_die('Security check failed');
    }

    delete_transient('niesv_pwa_clear_cache');
    wp_die('success');
}
add_action('wp_ajax_niesv_clear_pwa_cache_transient', 'niesv_clear_pwa_cache_transient');
add_action('wp_ajax_nopriv_niesv_clear_pwa_cache_transient', 'niesv_clear_pwa_cache_transient');

/**
 * Add cache clear button to admin bar
 */
function niesv_add_cache_clear_admin_bar($wp_admin_bar) {
    if (!current_user_can('administrator')) {
        return;
    }
    
    $wp_admin_bar->add_node(array(
        'id' => 'clear-cache',
        'title' => 'Clear Cache',
        'href' => add_query_arg('clear_cache', 'all'),
        'meta' => array(
            'title' => 'Clear all caches'
        )
    ));
    
    // Add submenu items
    $wp_admin_bar->add_node(array(
        'parent' => 'clear-cache',
        'id' => 'clear-wp-cache',
        'title' => 'WordPress Cache',
        'href' => add_query_arg('clear_cache', 'wp')
    ));
    
    $wp_admin_bar->add_node(array(
        'parent' => 'clear-cache',
        'id' => 'clear-opcache',
        'title' => 'PHP OPcache',
        'href' => add_query_arg('clear_cache', 'opcache')
    ));
    
    $wp_admin_bar->add_node(array(
        'parent' => 'clear-cache',
        'id' => 'clear-transients',
        'title' => 'Transients',
        'href' => add_query_arg('clear_cache', 'transients')
    ));

    $wp_admin_bar->add_node(array(
        'parent' => 'clear-cache',
        'id' => 'clear-pwa-cache',
        'title' => 'PWA Cache',
        'href' => add_query_arg('clear_cache', 'pwa')
    ));
}
add_action('admin_bar_menu', 'niesv_add_cache_clear_admin_bar', 100);

/**
 * Add cache status to admin dashboard
 */
function niesv_cache_status_dashboard_widget() {
    echo '<div class="cache-status">';
    echo '<h4>Cache Status</h4>';
    
    // OPcache status
    if (function_exists('opcache_get_status')) {
        $opcache_status = opcache_get_status();
        echo '<p><strong>OPcache:</strong> ' . ($opcache_status['opcache_enabled'] ? 'Enabled' : 'Disabled') . '</p>';
    }
    
    // Object cache status
    echo '<p><strong>Object Cache:</strong> ' . (wp_using_ext_object_cache() ? 'External' : 'Internal') . '</p>';
    
    // Quick clear buttons
    echo '<p>';
    echo '<a href="' . add_query_arg('clear_cache', 'all') . '" class="button button-primary">Clear All Cache</a> ';
    echo '<a href="' . add_query_arg('clear_cache', 'opcache') . '" class="button">Clear OPcache</a> ';
    echo '<a href="' . add_query_arg('clear_cache', 'pwa') . '" class="button">Clear PWA Cache</a>';
    echo '</p>';
    
    echo '</div>';
}

/**
 * Add dashboard widget for admins
 */
function niesv_add_cache_dashboard_widget() {
    if (current_user_can('administrator')) {
        wp_add_dashboard_widget(
            'niesv_cache_status',
            'NIESV Cache Status',
            'niesv_cache_status_dashboard_widget'
        );
    }
}
add_action('wp_dashboard_setup', 'niesv_add_cache_dashboard_widget');

/**
 * Add cache clear to theme customizer
 */
function niesv_customizer_cache_clear($wp_customize) {
    $wp_customize->add_section('niesv_cache_section', array(
        'title' => 'Cache Management',
        'priority' => 200,
    ));
    
    $wp_customize->add_setting('clear_cache_trigger', array(
        'default' => '',
        'transport' => 'postMessage',
    ));
    
    $wp_customize->add_control('clear_cache_control', array(
        'label' => 'Clear Cache',
        'description' => 'Clear all caches to see changes immediately.',
        'section' => 'niesv_cache_section',
        'settings' => 'clear_cache_trigger',
        'type' => 'button',
    ));
}
add_action('customize_register', 'niesv_customizer_cache_clear');
