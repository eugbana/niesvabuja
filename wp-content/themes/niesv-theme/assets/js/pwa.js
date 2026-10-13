/**
 * NIESV PWA JavaScript
 * Handles PWA installation, offline functionality, and app-like behavior
 */

(function() {
    'use strict';

    // PWA Installation
    let deferredPrompt;
    let installBanner;
    let installBtn;
    let dismissBtn;

    // Initialize PWA functionality
    document.addEventListener('DOMContentLoaded', function() {
        initPWAInstallation();
        initOfflineHandling();
        initAppBehavior();
        checkOnlineStatus();
        initServiceWorkerMessaging();
    });

    /**
     * Initialize PWA installation functionality
     */
    function initPWAInstallation() {
        installBanner = document.getElementById('pwa-install-banner');
        installBtn = document.getElementById('pwa-install-btn');
        dismissBtn = document.getElementById('pwa-dismiss-btn');

        // Listen for the beforeinstallprompt event
        window.addEventListener('beforeinstallprompt', function(e) {
            console.log('NIESV PWA: Install prompt available');
            
            // Prevent the mini-infobar from appearing on mobile
            e.preventDefault();
            
            // Save the event so it can be triggered later
            deferredPrompt = e;
            
            // Show custom install banner if not already installed
            if (!isAppInstalled()) {
                showInstallBanner();
            }
        });

        // Handle install button click
        if (installBtn) {
            installBtn.addEventListener('click', function() {
                if (deferredPrompt) {
                    // Show the install prompt
                    deferredPrompt.prompt();
                    
                    // Wait for the user to respond to the prompt
                    deferredPrompt.userChoice.then(function(choiceResult) {
                        if (choiceResult.outcome === 'accepted') {
                            console.log('NIESV PWA: User accepted the install prompt');
                            hideInstallBanner();
                            
                            // Store installation preference
                            localStorage.setItem('niesv_pwa_installed', 'true');
                        } else {
                            console.log('NIESV PWA: User dismissed the install prompt');
                        }
                        deferredPrompt = null;
                    });
                }
            });
        }

        // Handle dismiss button click
        if (dismissBtn) {
            dismissBtn.addEventListener('click', function() {
                hideInstallBanner();
                
                // Store dismissal preference (don't show again for 7 days)
                const dismissUntil = Date.now() + (7 * 24 * 60 * 60 * 1000);
                localStorage.setItem('niesv_pwa_dismiss_until', dismissUntil.toString());
            });
        }

        // Listen for app installation
        window.addEventListener('appinstalled', function(e) {
            console.log('NIESV PWA: App was installed');
            hideInstallBanner();
            localStorage.setItem('niesv_pwa_installed', 'true');
            
            // Show success message
            showNotification('NIESV app installed successfully!', 'success');
        });
    }

    /**
     * Show install banner
     */
    function showInstallBanner() {
        // Check if user has dismissed recently
        const dismissUntil = localStorage.getItem('niesv_pwa_dismiss_until');
        if (dismissUntil && Date.now() < parseInt(dismissUntil)) {
            return;
        }

        // Check if already installed
        if (isAppInstalled()) {
            return;
        }

        if (installBanner) {
            installBanner.classList.remove('hidden');
            
            // Add animation
            setTimeout(function() {
                installBanner.style.transform = 'translateY(0)';
                installBanner.style.opacity = '1';
            }, 100);
        }
    }

    /**
     * Hide install banner
     */
    function hideInstallBanner() {
        if (installBanner) {
            installBanner.style.transform = 'translateY(100%)';
            installBanner.style.opacity = '0';
            
            setTimeout(function() {
                installBanner.classList.add('hidden');
            }, 300);
        }
    }

    /**
     * Check if app is installed
     */
    function isAppInstalled() {
        // Check if running in standalone mode
        if (window.matchMedia('(display-mode: standalone)').matches) {
            return true;
        }
        
        // Check if running in PWA mode on iOS
        if (window.navigator.standalone === true) {
            return true;
        }
        
        // Check localStorage flag
        return localStorage.getItem('niesv_pwa_installed') === 'true';
    }

    /**
     * Initialize offline handling
     */
    function initOfflineHandling() {
        // Listen for online/offline events
        window.addEventListener('online', handleOnline);
        window.addEventListener('offline', handleOffline);
        
        // Check initial online status
        updateOnlineStatus();
    }

    /**
     * Handle online event
     */
    function handleOnline() {
        console.log('NIESV PWA: Back online');
        updateOnlineStatus();
        showNotification('You\'re back online!', 'success');
        
        // Sync any pending data
        syncPendingData();
    }

    /**
     * Handle offline event
     */
    function handleOffline() {
        console.log('NIESV PWA: Gone offline');
        updateOnlineStatus();
        showNotification('You\'re now offline. Some features may be limited.', 'warning');
    }

    /**
     * Update online status indicator
     */
    function updateOnlineStatus() {
        const isOnline = navigator.onLine;
        const statusIndicator = document.getElementById('online-status');
        
        if (statusIndicator) {
            statusIndicator.textContent = isOnline ? 'Online' : 'Offline';
            statusIndicator.className = isOnline ? 'online' : 'offline';
        }
        
        // Update body class
        document.body.classList.toggle('offline', !isOnline);
        document.body.classList.toggle('online', isOnline);
    }

    /**
     * Check online status on load
     */
    function checkOnlineStatus() {
        updateOnlineStatus();
        
        // Add online status indicator if it doesn't exist
        if (!document.getElementById('online-status')) {
            addOnlineStatusIndicator();
        }
    }

    /**
     * Add online status indicator
     */
    function addOnlineStatusIndicator() {
        const indicator = document.createElement('div');
        indicator.id = 'online-status';
        indicator.className = 'fixed top-4 right-4 px-3 py-1 rounded-full text-sm font-medium z-40 transition-all duration-300';
        indicator.style.display = 'none';
        
        document.body.appendChild(indicator);
        
        // Show indicator when offline
        if (!navigator.onLine) {
            indicator.style.display = 'block';
        }
    }

    /**
     * Initialize app-like behavior
     */
    function initAppBehavior() {
        // Prevent zoom on double tap (iOS)
        let lastTouchEnd = 0;
        document.addEventListener('touchend', function(event) {
            const now = (new Date()).getTime();
            if (now - lastTouchEnd <= 300) {
                event.preventDefault();
            }
            lastTouchEnd = now;
        }, false);

        // Handle pull-to-refresh
        initPullToRefresh();
        
        // Handle app shortcuts
        initAppShortcuts();
        
        // Handle background sync
        initBackgroundSync();
    }

    /**
     * Initialize pull-to-refresh
     */
    function initPullToRefresh() {
        let startY = 0;
        let currentY = 0;
        let pulling = false;
        
        document.addEventListener('touchstart', function(e) {
            if (window.scrollY === 0) {
                startY = e.touches[0].pageY;
                pulling = true;
            }
        });
        
        document.addEventListener('touchmove', function(e) {
            if (pulling) {
                currentY = e.touches[0].pageY;
                const pullDistance = currentY - startY;
                
                if (pullDistance > 100) {
                    // Show refresh indicator
                    showRefreshIndicator();
                }
            }
        });
        
        document.addEventListener('touchend', function(e) {
            if (pulling && currentY - startY > 100) {
                // Trigger refresh
                window.location.reload();
            }
            pulling = false;
            hideRefreshIndicator();
        });
    }

    /**
     * Show refresh indicator
     */
    function showRefreshIndicator() {
        let indicator = document.getElementById('refresh-indicator');
        if (!indicator) {
            indicator = document.createElement('div');
            indicator.id = 'refresh-indicator';
            indicator.innerHTML = '↓ Pull to refresh';
            indicator.className = 'fixed top-0 left-0 right-0 bg-[var(--primary-color)] text-white text-center py-2 z-50';
            document.body.appendChild(indicator);
        }
        indicator.style.display = 'block';
    }

    /**
     * Hide refresh indicator
     */
    function hideRefreshIndicator() {
        const indicator = document.getElementById('refresh-indicator');
        if (indicator) {
            indicator.style.display = 'none';
        }
    }

    /**
     * Initialize app shortcuts
     */
    function initAppShortcuts() {
        // Handle keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + K for search
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                const searchInput = document.querySelector('input[type="search"]');
                if (searchInput) {
                    searchInput.focus();
                }
            }
        });
    }

    /**
     * Initialize background sync
     */
    function initBackgroundSync() {
        if ('serviceWorker' in navigator && 'sync' in window.ServiceWorkerRegistration.prototype) {
            // Register for background sync when forms are submitted offline
            document.addEventListener('submit', function(e) {
                if (!navigator.onLine) {
                    e.preventDefault();
                    storeFormForSync(e.target);
                    showNotification('Form saved. Will submit when back online.', 'info');
                }
            });
        }
    }

    /**
     * Store form data for background sync
     */
    function storeFormForSync(form) {
        const formData = new FormData(form);
        const data = {
            url: form.action,
            method: form.method,
            data: formData,
            timestamp: Date.now()
        };
        
        // Store in IndexedDB or localStorage
        const pendingForms = JSON.parse(localStorage.getItem('niesv_pending_forms') || '[]');
        pendingForms.push(data);
        localStorage.setItem('niesv_pending_forms', JSON.stringify(pendingForms));
        
        // Register for background sync
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.ready.then(function(registration) {
                return registration.sync.register('contact-form-sync');
            });
        }
    }

    /**
     * Sync pending data when back online
     */
    function syncPendingData() {
        const pendingForms = JSON.parse(localStorage.getItem('niesv_pending_forms') || '[]');
        
        if (pendingForms.length > 0) {
            console.log('NIESV PWA: Syncing', pendingForms.length, 'pending forms');
            
            pendingForms.forEach(function(formData, index) {
                // Submit form data
                fetch(formData.url, {
                    method: formData.method,
                    body: formData.data
                }).then(function(response) {
                    if (response.ok) {
                        // Remove from pending list
                        pendingForms.splice(index, 1);
                        localStorage.setItem('niesv_pending_forms', JSON.stringify(pendingForms));
                        showNotification('Form submitted successfully!', 'success');
                    }
                }).catch(function(error) {
                    console.error('NIESV PWA: Failed to sync form:', error);
                });
            });
        }
    }

    /**
     * Show notification
     */
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-4 py-2 rounded-lg text-white z-50 transition-all duration-300 ${getNotificationClass(type)}`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(function() {
            notification.style.transform = 'translateX(0)';
            notification.style.opacity = '1';
        }, 100);
        
        // Remove after 3 seconds
        setTimeout(function() {
            notification.style.transform = 'translateX(100%)';
            notification.style.opacity = '0';
            setTimeout(function() {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }

    /**
     * Get notification CSS class based on type
     */
    function getNotificationClass(type) {
        switch (type) {
            case 'success':
                return 'bg-green-500';
            case 'warning':
                return 'bg-yellow-500';
            case 'error':
                return 'bg-red-500';
            default:
                return 'bg-blue-500';
        }
    }

    /**
     * Initialize service worker messaging for caching preferences
     */
    function initServiceWorkerMessaging() {
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.addEventListener('message', function(event) {
                if (event.data && event.data.type === 'GET_CACHING_PREFERENCE') {
                    // Get user's caching preference from localStorage
                    const preference = localStorage.getItem('niesv_pwa_caching_mode') || 'minimal';

                    // Send response back to service worker
                    event.ports[0].postMessage({
                        preference: preference
                    });
                }
            });
        }
    }

    /**
     * Set PWA caching mode
     */
    function setCachingMode(mode) {
        localStorage.setItem('niesv_pwa_caching_mode', mode);
        console.log('NIESV PWA: Caching mode set to', mode);

        // Notify service worker of the change
        if ('serviceWorker' in navigator && navigator.serviceWorker.controller) {
            navigator.serviceWorker.controller.postMessage({
                type: 'CACHING_MODE_CHANGED',
                mode: mode
            });
        }
    }

    /**
     * Get current caching mode
     */
    function getCachingMode() {
        return localStorage.getItem('niesv_pwa_caching_mode') || 'minimal';
    }

    // Expose functions globally if needed
    window.niesvPWA = {
        showInstallBanner: showInstallBanner,
        hideInstallBanner: hideInstallBanner,
        isAppInstalled: isAppInstalled,
        showNotification: showNotification,
        setCachingMode: setCachingMode,
        getCachingMode: getCachingMode
    };

})();
