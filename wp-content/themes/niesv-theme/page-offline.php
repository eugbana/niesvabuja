<?php
/**
 * Template for the offline page
 * This page is shown when users are offline and try to access content not in cache
 *
 * @package NIESV_Theme
 */

get_header(); ?>

<main class="main-content flex-1 px-10 py-8">
    <div class="max-w-4xl mx-auto">
        
        <!-- Offline Hero Section -->
        <div class="text-center py-16 bg-gradient-to-br from-[var(--background-color)] to-[var(--secondary-color)] rounded-2xl mb-12">
            <div class="max-w-2xl mx-auto px-8">
                
                <!-- Offline Icon -->
                <div class="mb-8">
                    <svg class="w-32 h-32 mx-auto text-[var(--text-secondary)] opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 109.75 9.75A9.75 9.75 0 0012 2.25z"></path>
                    </svg>
                </div>
                
                <!-- Offline Message -->
                <h1 class="text-4xl md:text-5xl font-bold text-[var(--text-primary)] mb-6">
                    You're Offline
                </h1>
                
                <p class="text-xl text-[var(--text-secondary)] mb-8 leading-relaxed">
                    It looks like you're not connected to the internet. Don't worry, you can still browse some of the content you've previously visited.
                </p>
                
                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button onclick="window.location.reload()" class="bg-[var(--primary-color)] text-white py-4 px-8 rounded-lg font-medium hover:bg-opacity-90 transition-all duration-300 transform hover:scale-105">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Try Again
                    </button>
                    
                    <a href="<?php echo home_url('/'); ?>" class="bg-[var(--secondary-color)] text-[var(--primary-color)] py-4 px-8 rounded-lg font-medium hover:bg-opacity-90 transition-all duration-300 transform hover:scale-105">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Go to Homepage
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Offline Features Section -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
            
            <!-- Cached Content -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-[var(--secondary-color)]">
                <div class="w-12 h-12 bg-[var(--primary-color)] rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-[var(--text-primary)] mb-2">Cached Content</h3>
                <p class="text-[var(--text-secondary)]">
                    You can still access pages you've visited recently. They're stored locally on your device.
                </p>
            </div>
            
            <!-- Offline Forms -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-[var(--secondary-color)]">
                <div class="w-12 h-12 bg-[var(--accent-gold)] rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-[var(--text-primary)] mb-2">Form Submissions</h3>
                <p class="text-[var(--text-secondary)]">
                    Any forms you submit while offline will be saved and sent automatically when you're back online.
                </p>
            </div>
            
            <!-- App Features -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-[var(--secondary-color)]">
                <div class="w-12 h-12 bg-[var(--text-secondary)] rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-[var(--text-primary)] mb-2">App Features</h3>
                <p class="text-[var(--text-secondary)]">
                    Core app functionality continues to work offline, including navigation and cached data.
                </p>
            </div>
        </div>
        
        <!-- Quick Links Section -->
        <div class="bg-white rounded-xl p-8 shadow-sm border border-[var(--secondary-color)]">
            <h2 class="text-2xl font-bold text-[var(--text-primary)] mb-6 text-center">
                Quick Links (When Online)
            </h2>
            
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="<?php echo home_url('/about/'); ?>" class="flex items-center p-4 rounded-lg border border-[var(--secondary-color)] hover:bg-[var(--secondary-color)] transition-colors group">
                    <svg class="w-6 h-6 text-[var(--text-secondary)] group-hover:text-[var(--primary-color)] mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium text-[var(--text-primary)]">About Us</span>
                </a>
                
                <a href="<?php echo home_url('/membership/'); ?>" class="flex items-center p-4 rounded-lg border border-[var(--secondary-color)] hover:bg-[var(--secondary-color)] transition-colors group">
                    <svg class="w-6 h-6 text-[var(--text-secondary)] group-hover:text-[var(--primary-color)] mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span class="font-medium text-[var(--text-primary)]">Membership</span>
                </a>
                
                <a href="<?php echo home_url('/events/'); ?>" class="flex items-center p-4 rounded-lg border border-[var(--secondary-color)] hover:bg-[var(--secondary-color)] transition-colors group">
                    <svg class="w-6 h-6 text-[var(--text-secondary)] group-hover:text-[var(--primary-color)] mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="font-medium text-[var(--text-primary)]">Events</span>
                </a>
                
                <a href="<?php echo home_url('/contact/'); ?>" class="flex items-center p-4 rounded-lg border border-[var(--secondary-color)] hover:bg-[var(--secondary-color)] transition-colors group">
                    <svg class="w-6 h-6 text-[var(--text-secondary)] group-hover:text-[var(--primary-color)] mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span class="font-medium text-[var(--text-primary)]">Contact</span>
                </a>
            </div>
        </div>
        
        <!-- Connection Status -->
        <div class="mt-8 text-center">
            <div id="connection-status" class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium">
                <div id="connection-indicator" class="w-3 h-3 rounded-full mr-2"></div>
                <span id="connection-text">Checking connection...</span>
            </div>
        </div>
    </div>
</main>

<script>
// Connection status checker
function updateConnectionStatus() {
    const indicator = document.getElementById('connection-indicator');
    const text = document.getElementById('connection-text');
    const status = document.getElementById('connection-status');
    
    if (navigator.onLine) {
        indicator.className = 'w-3 h-3 rounded-full mr-2 bg-green-500';
        text.textContent = 'Connected - You can now browse normally';
        status.className = 'inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800';
        
        // Auto-redirect to homepage after 3 seconds if online
        setTimeout(function() {
            if (navigator.onLine) {
                window.location.href = '<?php echo home_url('/'); ?>';
            }
        }, 3000);
    } else {
        indicator.className = 'w-3 h-3 rounded-full mr-2 bg-red-500';
        text.textContent = 'Offline - Limited functionality available';
        status.className = 'inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-red-100 text-red-800';
    }
}

// Check connection status on load and when it changes
updateConnectionStatus();
window.addEventListener('online', updateConnectionStatus);
window.addEventListener('offline', updateConnectionStatus);

// Check connection every 5 seconds
setInterval(updateConnectionStatus, 5000);
</script>

<?php get_footer(); ?>
