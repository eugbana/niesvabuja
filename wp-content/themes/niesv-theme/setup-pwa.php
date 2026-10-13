<?php
/**
 * PWA Setup Script
 * Run this script once to set up PWA pages and test functionality
 * 
 * Access via: yoursite.com/wp-content/themes/niesv-theme/setup-pwa.php
 */

// Include WordPress
require_once('../../../wp-load.php');

// Check if user is admin
if (!current_user_can('manage_options')) {
    wp_die('You do not have permission to access this page.');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NIESV PWA Setup</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            line-height: 1.6;
        }
        .success { 
            background: #d4edda; 
            color: #155724; 
            padding: 15px; 
            border-radius: 5px; 
            margin: 10px 0; 
        }
        .error { 
            background: #f8d7da; 
            color: #721c24; 
            padding: 15px; 
            border-radius: 5px; 
            margin: 10px 0; 
        }
        .info { 
            background: #d1ecf1; 
            color: #0c5460; 
            padding: 15px; 
            border-radius: 5px; 
            margin: 10px 0; 
        }
        button {
            background: #000028;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            margin: 5px;
        }
        button:hover { background: #49739c; }
        .test-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        .test-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            background: #f9f9f9;
        }
    </style>
</head>
<body>
    <h1>🚀 NIESV PWA Setup</h1>
    
    <?php
    $setup_results = [];
    
    if (isset($_POST['setup_pwa'])) {
        // Create offline page
        $offline_page = get_page_by_path('offline');
        if (!$offline_page) {
            $offline_id = wp_insert_post(array(
                'post_title' => 'Offline',
                'post_name' => 'offline',
                'post_content' => '[offline_content]',
                'post_status' => 'publish',
                'post_type' => 'page'
            ));
            if ($offline_id) {
                $setup_results[] = ['success', 'Offline page created successfully'];
            } else {
                $setup_results[] = ['error', 'Failed to create offline page'];
            }
        } else {
            $setup_results[] = ['info', 'Offline page already exists'];
        }
        
        // Create PWA test page
        $pwa_test_page = get_page_by_path('pwa-test');
        if (!$pwa_test_page) {
            $pwa_test_id = wp_insert_post(array(
                'post_title' => 'PWA Test',
                'post_name' => 'pwa-test',
                'post_content' => 'This page tests PWA functionality.',
                'post_status' => 'publish',
                'post_type' => 'page'
            ));
            if ($pwa_test_id) {
                $setup_results[] = ['success', 'PWA test page created successfully'];
            } else {
                $setup_results[] = ['error', 'Failed to create PWA test page'];
            }
        } else {
            $setup_results[] = ['info', 'PWA test page already exists'];
        }
        
        // Flush rewrite rules
        flush_rewrite_rules();
        $setup_results[] = ['success', 'Rewrite rules flushed'];
    }
    
    // Display results
    foreach ($setup_results as $result) {
        echo "<div class='{$result[0]}'>{$result[1]}</div>";
    }
    ?>
    
    <div class="info">
        <h3>PWA Setup Status</h3>
        <p>This script will create the necessary pages and configure your PWA setup.</p>
    </div>
    
    <form method="post">
        <button type="submit" name="setup_pwa">Setup PWA Pages</button>
    </form>
    
    <h2>📋 PWA Status Check</h2>
    
    <div class="test-grid">
        <div class="test-card">
            <h3>📄 Required Pages</h3>
            <ul>
                <li>Offline Page: <?php echo get_page_by_path('offline') ? '✅ Exists' : '❌ Missing'; ?></li>
                <li>PWA Test Page: <?php echo get_page_by_path('pwa-test') ? '✅ Exists' : '❌ Missing'; ?></li>
            </ul>
        </div>
        
        <div class="test-card">
            <h3>📁 Required Files</h3>
            <ul>
                <li>Manifest: <?php echo file_exists(get_template_directory() . '/manifest.json') ? '✅ Exists' : '❌ Missing'; ?></li>
                <li>Service Worker: <?php echo file_exists(get_template_directory() . '/sw.js') ? '✅ Exists' : '❌ Missing'; ?></li>
                <li>PWA JS: <?php echo file_exists(get_template_directory() . '/assets/js/pwa.js') ? '✅ Exists' : '❌ Missing'; ?></li>
                <li>Offline Template: <?php echo file_exists(get_template_directory() . '/page-offline.php') ? '✅ Exists' : '❌ Missing'; ?></li>
            </ul>
        </div>
        
        <div class="test-card">
            <h3>🎨 PWA Assets</h3>
            <?php
            $icon_sizes = [72, 96, 128, 144, 152, 192, 384, 512];
            $icons_exist = 0;
            foreach ($icon_sizes as $size) {
                if (file_exists(get_template_directory() . "/assets/icons/icon-{$size}x{$size}.png")) {
                    $icons_exist++;
                }
            }
            ?>
            <ul>
                <li>Icons: <?php echo $icons_exist; ?>/<?php echo count($icon_sizes); ?> sizes available</li>
                <li>Shortcuts: <?php 
                    $shortcuts = ['news-shortcut.png', 'events-shortcut.png', 'properties-shortcut.png', 'dashboard-shortcut.png'];
                    $shortcuts_exist = 0;
                    foreach ($shortcuts as $shortcut) {
                        if (file_exists(get_template_directory() . "/assets/icons/{$shortcut}")) {
                            $shortcuts_exist++;
                        }
                    }
                    echo $shortcuts_exist . '/' . count($shortcuts) . ' available';
                ?></li>
            </ul>
        </div>
        
        <div class="test-card">
            <h3>🔧 WordPress Integration</h3>
            <ul>
                <li>Theme: <?php echo wp_get_theme()->get('Name'); ?></li>
                <li>Version: <?php echo wp_get_theme()->get('Version'); ?></li>
                <li>HTTPS: <?php echo is_ssl() ? '✅ Enabled' : '❌ Disabled (required for PWA)'; ?></li>
            </ul>
        </div>
    </div>
    
    <h2>🧪 Testing Links</h2>
    
    <div class="test-grid">
        <div class="test-card">
            <h3>Test Pages</h3>
            <ul>
                <li><a href="<?php echo home_url('/pwa-test/'); ?>" target="_blank">PWA Test Page</a></li>
                <li><a href="<?php echo home_url('/offline/'); ?>" target="_blank">Offline Page</a></li>
                <li><a href="<?php echo home_url('/manifest.json'); ?>" target="_blank">Manifest File</a></li>
                <li><a href="<?php echo home_url('/sw.js'); ?>" target="_blank">Service Worker</a></li>
            </ul>
        </div>
        
        <div class="test-card">
            <h3>Browser Testing</h3>
            <ul>
                <li>Open Chrome DevTools (F12)</li>
                <li>Go to Application tab</li>
                <li>Check Service Workers section</li>
                <li>Check Manifest section</li>
                <li>Run Lighthouse PWA audit</li>
            </ul>
        </div>
    </div>
    
    <div class="info">
        <h3>Next Steps</h3>
        <ol>
            <li>Click "Setup PWA Pages" if you haven't already</li>
            <li>Visit the <a href="<?php echo home_url('/pwa-test/'); ?>">PWA Test Page</a> to verify functionality</li>
            <li>Test the PWA installation prompt in Chrome/Edge</li>
            <li>Test offline functionality using browser DevTools</li>
            <li>Run a Lighthouse audit to check PWA score</li>
        </ol>
    </div>
    
    <div class="info">
        <h3>Troubleshooting</h3>
        <ul>
            <li><strong>Service Worker not registering:</strong> Make sure you're accessing via HTTP/HTTPS, not file://</li>
            <li><strong>Install prompt not showing:</strong> PWA criteria must be met (HTTPS, manifest, service worker, etc.)</li>
            <li><strong>Icons not loading:</strong> Check file paths and ensure PNG format</li>
            <li><strong>Offline page not working:</strong> Verify service worker is registered and caching properly</li>
        </ul>
    </div>
    
    <p><small>After setup is complete, you can delete this file for security.</small></p>
</body>
</html>
