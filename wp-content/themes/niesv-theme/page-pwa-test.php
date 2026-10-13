<?php
/**
 * Template for PWA Testing Page
 * 
 * @package NIESV_Theme
 */

// Diagnostics page: administrators only
if (!current_user_can('manage_options')) {
    wp_safe_redirect(home_url('/'));
    exit;
}

get_header(); ?>

<main class="main-content flex-1 px-10 py-8">
    <div class="max-w-6xl mx-auto">
        
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-[var(--text-primary)] mb-4">🚀 NIESV PWA Test Suite</h1>
            <p class="text-xl text-[var(--text-secondary)]">This page helps you test and verify that the PWA functionality is working correctly.</p>

            <!-- Environment Information -->
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 class="font-semibold text-blue-800 mb-2">Environment Information:</h3>
                <div class="text-sm text-blue-700 space-y-1">
                    <div><strong>Current URL:</strong> <span id="current-url"><?php echo home_url(); ?></span></div>
                    <div><strong>Hostname:</strong> <span id="hostname"><?php echo $_SERVER['HTTP_HOST']; ?></span></div>
                    <div><strong>Expected Cache Name:</strong> <span id="expected-cache">niesv-pwa-<?php echo (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '.local') !== false || strpos($_SERVER['HTTP_HOST'], '.test') !== false || strpos($_SERVER['HTTP_HOST'], '.dev') !== false) ? 'development' : 'production'; ?>-v1.0.0</span></div>
                    <div><strong>Environment:</strong> <span id="environment" class="font-bold"><?php echo (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '.local') !== false || strpos($_SERVER['HTTP_HOST'], '.test') !== false || strpos($_SERVER['HTTP_HOST'], '.dev') !== false) ? 'DEVELOPMENT' : 'PRODUCTION'; ?></span></div>
                </div>
            </div>
        </div>
        
        <!-- PWA Caching Settings -->
        <div class="bg-white rounded-xl p-8 shadow-sm border border-[var(--secondary-color)] mb-8">
            <h2 class="text-2xl font-bold text-[var(--text-primary)] mb-6 flex items-center">
                <span class="mr-3">⚙️</span> PWA Caching Settings
            </h2>

            <div class="space-y-4">
                <p class="text-[var(--text-secondary)]">
                    Choose how aggressively the PWA should cache content. This affects both regular browser usage and installed PWA experience.
                </p>

                <div class="space-y-3">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="radio" name="caching-mode" value="minimal" class="text-[var(--primary-color)]" checked>
                        <div>
                            <div class="font-semibold">Minimal Caching (Recommended)</div>
                            <div class="text-sm text-[var(--text-secondary)]">Only caches essential files. Better for regular browser usage.</div>
                        </div>
                    </label>

                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="radio" name="caching-mode" value="full" class="text-[var(--primary-color)]">
                        <div>
                            <div class="font-semibold">Full PWA Caching</div>
                            <div class="text-sm text-[var(--text-secondary)]">Aggressive caching for full offline experience. Best for installed PWA.</div>
                        </div>
                    </label>
                </div>

                <button id="save-caching-preference" class="bg-[var(--primary-color)] text-white px-6 py-2 rounded hover:bg-opacity-90 transition-colors">
                    Save Preference
                </button>

                <div id="caching-status" class="text-sm text-[var(--text-secondary)]"></div>
            </div>
        </div>

        <!-- PWA Feature Tests -->
        <div class="bg-white rounded-xl p-8 shadow-sm border border-[var(--secondary-color)] mb-8">
            <h2 class="text-2xl font-bold text-[var(--text-primary)] mb-6 flex items-center">
                <span class="mr-3">📱</span> PWA Feature Tests
            </h2>
            
            <div class="space-y-6">
                <div class="border-l-4 border-blue-500 pl-4">
                    <h3 class="text-lg font-semibold mb-2">1. Service Worker Registration</h3>
                    <div id="sw-test" class="flex items-center gap-3">
                        <span class="status pending px-3 py-1 rounded text-sm font-bold">TESTING...</span>
                        <span id="sw-status">Checking service worker registration...</span>
                    </div>
                </div>
                
                <div class="border-l-4 border-blue-500 pl-4">
                    <h3 class="text-lg font-semibold mb-2">2. Manifest File</h3>
                    <div id="manifest-test" class="flex items-center gap-3">
                        <span class="status pending px-3 py-1 rounded text-sm font-bold">TESTING...</span>
                        <span id="manifest-status">Checking manifest file...</span>
                    </div>
                </div>
                
                <div class="border-l-4 border-blue-500 pl-4">
                    <h3 class="text-lg font-semibold mb-2">3. Install Prompt</h3>
                    <div id="install-test" class="flex items-center gap-3">
                        <span class="status pending px-3 py-1 rounded text-sm font-bold">TESTING...</span>
                        <span id="install-status">Checking install prompt availability...</span>
                        <button id="trigger-install" class="ml-4 bg-[var(--primary-color)] text-white px-4 py-2 rounded hover:bg-opacity-90" style="display: none;">Trigger Install Prompt</button>
                    </div>
                </div>
                
                <div class="border-l-4 border-blue-500 pl-4">
                    <h3 class="text-lg font-semibold mb-2">4. Offline Functionality</h3>
                    <div id="offline-test" class="flex items-center gap-3">
                        <span class="status pending px-3 py-1 rounded text-sm font-bold">TESTING...</span>
                        <span id="offline-status">Testing offline capabilities...</span>
                        <button id="test-offline" class="ml-4 bg-[var(--accent-gold)] text-[var(--primary-color)] px-4 py-2 rounded hover:bg-opacity-90">Test Offline Mode</button>
                    </div>
                </div>
                
                <div class="border-l-4 border-blue-500 pl-4">
                    <h3 class="text-lg font-semibold mb-2">5. Cache Status</h3>
                    <div id="cache-test" class="flex items-center gap-3">
                        <span class="status pending px-3 py-1 rounded text-sm font-bold">TESTING...</span>
                        <span id="cache-status">Checking cache status...</span>
                        <button id="clear-cache" class="ml-4 bg-red-500 text-white px-4 py-2 rounded hover:bg-opacity-90">Clear Cache</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Manual Testing Instructions -->
        <div class="bg-white rounded-xl p-8 shadow-sm border border-[var(--secondary-color)] mb-8">
            <h2 class="text-2xl font-bold text-[var(--text-primary)] mb-6 flex items-center">
                <span class="mr-3">🧪</span> Manual Testing Instructions
            </h2>
            
            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-3">Chrome DevTools Testing:</h3>
                    <ol class="list-decimal list-inside space-y-2 text-[var(--text-secondary)]">
                        <li>Open Chrome DevTools (F12)</li>
                        <li>Go to <strong>Application</strong> tab</li>
                        <li>Check <strong>Service Workers</strong> section</li>
                        <li>Check <strong>Manifest</strong> section</li>
                        <li>Use <strong>Lighthouse</strong> tab to run PWA audit</li>
                    </ol>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-3">Installation Testing:</h3>
                    <ol class="list-decimal list-inside space-y-2 text-[var(--text-secondary)]">
                        <li>Look for install prompt in address bar</li>
                        <li>Try installing the app</li>
                        <li>Test app shortcuts (if supported)</li>
                        <li>Test standalone mode</li>
                    </ol>
                </div>
            </div>
        </div>
        
        <!-- Test Results -->
        <div class="bg-white rounded-xl p-8 shadow-sm border border-[var(--secondary-color)]">
            <h2 class="text-2xl font-bold text-[var(--text-primary)] mb-6 flex items-center">
                <span class="mr-3">📊</span> Test Results
            </h2>
            <div id="test-results">
                <p class="text-[var(--text-secondary)]">Test results will appear here...</p>
            </div>
        </div>
    </div>
</main>

<style>
.status.pass { 
    background: #10b981; 
    color: white; 
}
.status.fail { 
    background: #ef4444; 
    color: white; 
}
.status.pending { 
    background: #f59e0b; 
    color: white; 
}
</style>

<script>
// PWA Test Suite
class PWATestSuite {
    constructor() {
        this.results = {};
        this.init();
    }
    
    init() {
        console.log('NIESV PWA Test Suite: Starting tests...');
        this.testServiceWorker();
        this.testManifest();
        this.testInstallPrompt();
        this.testOfflineCapability();
        this.testCacheStatus();
        this.setupEventListeners();
    }
    
    updateStatus(testId, status, message) {
        const statusEl = document.querySelector(`#${testId} .status`);
        const messageEl = document.getElementById(`${testId}-status`);
        
        if (statusEl && messageEl) {
            statusEl.className = `status ${status} px-3 py-1 rounded text-sm font-bold`;
            statusEl.textContent = status.toUpperCase();
            messageEl.textContent = message;
            
            this.results[testId] = { status, message };
            this.updateResults();
        }
    }
    
    async testServiceWorker() {
        try {
            if ('serviceWorker' in navigator) {
                // Wait a bit for service worker to register
                setTimeout(async () => {
                    const registration = await navigator.serviceWorker.getRegistration();
                    if (registration) {
                        this.updateStatus('sw-test', 'pass', `Service Worker registered: ${registration.scope}`);
                    } else {
                        this.updateStatus('sw-test', 'fail', 'Service Worker not registered yet - check console for errors');
                    }
                }, 2000);
            } else {
                this.updateStatus('sw-test', 'fail', 'Service Worker not supported in this browser');
            }
        } catch (error) {
            this.updateStatus('sw-test', 'fail', `Service Worker error: ${error.message}`);
        }
    }
    
    async testManifest() {
        try {
            const manifestLink = document.querySelector('link[rel="manifest"]');
            if (manifestLink) {
                const response = await fetch(manifestLink.href);
                if (response.ok) {
                    const manifest = await response.json();
                    this.updateStatus('manifest-test', 'pass', `Manifest loaded: ${manifest.name || 'NIESV'}`);
                } else {
                    this.updateStatus('manifest-test', 'fail', `Manifest file not accessible: ${response.status}`);
                }
            } else {
                this.updateStatus('manifest-test', 'fail', 'Manifest link not found in page head');
            }
        } catch (error) {
            this.updateStatus('manifest-test', 'fail', `Manifest error: ${error.message}`);
        }
    }
    
    testInstallPrompt() {
        let installPromptAvailable = false;
        
        window.addEventListener('beforeinstallprompt', (e) => {
            installPromptAvailable = true;
            this.updateStatus('install-test', 'pass', 'Install prompt available');
            document.getElementById('trigger-install').style.display = 'inline-block';
            
            document.getElementById('trigger-install').onclick = () => {
                e.prompt();
            };
        });
        
        // Check if already installed
        if (window.matchMedia('(display-mode: standalone)').matches) {
            this.updateStatus('install-test', 'pass', 'App is already installed and running in standalone mode');
        } else {
            setTimeout(() => {
                if (!installPromptAvailable) {
                    this.updateStatus('install-test', 'fail', 'Install prompt not available (may need HTTPS or already installed)');
                }
            }, 3000);
        }
    }
    
    async testOfflineCapability() {
        try {
            // Test if offline page exists
            const offlineUrl = '<?php echo home_url('/offline/'); ?>';
            const offlineResponse = await fetch(offlineUrl, { method: 'HEAD' });
            if (offlineResponse.ok) {
                this.updateStatus('offline-test', 'pass', 'Offline page available');
            } else {
                this.updateStatus('offline-test', 'fail', 'Offline page not found - may need to be created');
            }
        } catch (error) {
            this.updateStatus('offline-test', 'fail', `Offline test error: ${error.message}`);
        }
    }
    
    async testCacheStatus() {
        try {
            if ('caches' in window) {
                const cacheNames = await caches.keys();
                const expectedCache = document.getElementById('expected-cache').textContent;
                const niesvCaches = cacheNames.filter(name => name.startsWith('niesv-pwa-'));

                if (cacheNames.length > 0) {
                    let message = `${cacheNames.length} cache(s) found: ${cacheNames.join(', ')}`;

                    // Check if the expected environment cache exists
                    if (niesvCaches.includes(expectedCache)) {
                        message += ` ✓ Environment-specific cache (${expectedCache}) is active`;
                        this.updateStatus('cache-test', 'pass', message);
                    } else if (niesvCaches.length > 0) {
                        message += ` ⚠️ Found NIESV caches but not the expected one (${expectedCache})`;
                        this.updateStatus('cache-test', 'fail', message);
                    } else {
                        this.updateStatus('cache-test', 'pass', message);
                    }
                } else {
                    this.updateStatus('cache-test', 'fail', 'No caches found - service worker may not be working');
                }
            } else {
                this.updateStatus('cache-test', 'fail', 'Cache API not supported in this browser');
            }
        } catch (error) {
            this.updateStatus('cache-test', 'fail', `Cache test error: ${error.message}`);
        }
    }
    
    setupEventListeners() {
        document.getElementById('test-offline').onclick = () => {
            alert('To test offline mode:\n1. Open DevTools (F12)\n2. Go to Network tab\n3. Check "Offline"\n4. Navigate to different pages\n5. Try accessing <?php echo home_url('/offline/'); ?>');
        };
        
        document.getElementById('clear-cache').onclick = async () => {
            if ('caches' in window) {
                const cacheNames = await caches.keys();
                const niesvCaches = cacheNames.filter(name => name.startsWith('niesv-pwa-'));

                if (niesvCaches.length > 0) {
                    await Promise.all(niesvCaches.map(name => caches.delete(name)));
                    alert(`Environment-specific PWA caches cleared (${niesvCaches.join(', ')})! Refresh the page to test caching again.`);
                } else {
                    alert('No NIESV PWA caches found to clear.');
                }
                location.reload();
            }
        };
    }
    
    updateResults() {
        const resultsEl = document.getElementById('test-results');
        const passed = Object.values(this.results).filter(r => r.status === 'pass').length;
        const total = Object.keys(this.results).length;
        
        let scoreColor = 'text-red-600';
        if (passed === total) scoreColor = 'text-green-600';
        else if (passed > total / 2) scoreColor = 'text-yellow-600';
        
        resultsEl.innerHTML = `
            <h3 class="text-xl font-semibold mb-4 ${scoreColor}">Overall Score: ${passed}/${total} tests passed</h3>
            <div class="space-y-3">
                ${Object.entries(this.results).map(([test, result]) => 
                    `<div class="flex items-center gap-3">
                        <span class="status ${result.status} px-3 py-1 rounded text-sm font-bold">${result.status.toUpperCase()}</span>
                        <strong>${test.replace('-test', '').replace('-', ' ').toUpperCase()}:</strong>
                        <span class="text-[var(--text-secondary)]">${result.message}</span>
                    </div>`
                ).join('')}
            </div>
            ${passed < total ? `
                <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <h4 class="font-semibold text-yellow-800 mb-2">Troubleshooting Tips:</h4>
                    <ul class="text-sm text-yellow-700 space-y-1">
                        <li>• Make sure you're accessing this page via your WordPress site URL (not file://)</li>
                        <li>• PWAs require HTTPS in production (localhost is OK for testing)</li>
                        <li>• Check browser console for any JavaScript errors</li>
                        <li>• Try refreshing the page and waiting a few seconds for service worker registration</li>
                    </ul>
                </div>
            ` : ''}
        `;
    }
}

// Initialize test suite when page loads
document.addEventListener('DOMContentLoaded', () => {
    new PWATestSuite();
    initCachingSettings();
});

// Initialize caching settings
function initCachingSettings() {
    // Load current preference
    const currentMode = localStorage.getItem('niesv_pwa_caching_mode') || 'minimal';
    const radioButton = document.querySelector(`input[name="caching-mode"][value="${currentMode}"]`);
    if (radioButton) {
        radioButton.checked = true;
    }

    // Update status
    updateCachingStatus(currentMode);

    // Handle save button
    document.getElementById('save-caching-preference').addEventListener('click', function() {
        const selectedMode = document.querySelector('input[name="caching-mode"]:checked').value;

        if (window.niesvPWA && window.niesvPWA.setCachingMode) {
            window.niesvPWA.setCachingMode(selectedMode);
            updateCachingStatus(selectedMode);

            // Show success message
            const statusEl = document.getElementById('caching-status');
            statusEl.innerHTML = '<span class="text-green-600 font-semibold">✓ Preference saved! Refresh the page to apply changes.</span>';

            setTimeout(() => {
                updateCachingStatus(selectedMode);
            }, 3000);
        } else {
            alert('PWA functions not available. Make sure the page has fully loaded.');
        }
    });
}

function updateCachingStatus(mode) {
    const statusEl = document.getElementById('caching-status');
    const descriptions = {
        'minimal': 'Current mode: Minimal caching - Only essential files are cached for better browser performance.',
        'full': 'Current mode: Full PWA caching - All resources are cached for complete offline experience.'
    };

    statusEl.innerHTML = descriptions[mode] || 'Unknown caching mode';
}
</script>

<?php get_footer(); ?>
