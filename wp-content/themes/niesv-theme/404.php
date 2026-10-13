<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package NIESV_Theme
 */

get_header(); ?>

<main class="flex-1 bg-[var(--background-color)]">
    <section class="py-20 md:py-32">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
            
            <!-- 404 Illustration -->
            <div class="mb-8">
                <svg class="mx-auto h-32 w-32 text-[var(--text-secondary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.291-1.007-5.691-2.709M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
            </div>
            
            <!-- Error Message -->
            <h1 class="text-6xl md:text-8xl font-extrabold text-[var(--primary-color)] mb-4">404</h1>
            <h2 class="text-2xl md:text-3xl font-bold text-[var(--text-primary)] mb-6">Page Not Found</h2>
            <p class="text-lg text-[var(--text-secondary)] mb-8 max-w-2xl mx-auto">
                Sorry, we couldn't find the page you're looking for. The page may have been moved, deleted, or you may have entered an incorrect URL.
            </p>
            
            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?php echo home_url(); ?>" 
                   class="inline-flex items-center px-6 py-3 bg-[var(--primary-color)] text-white rounded-lg font-semibold hover:bg-opacity-90 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Go to Homepage
                </a>
                
                <button onclick="history.back()" 
                        class="inline-flex items-center px-6 py-3 border-2 border-[var(--primary-color)] text-[var(--primary-color)] rounded-lg font-semibold hover:bg-[var(--primary-color)] hover:text-white transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Go Back
                </button>
            </div>
            
            <!-- Search Form -->
            <div class="mt-12 max-w-md mx-auto">
                <h3 class="text-lg font-semibold text-[var(--text-primary)] mb-4">Try searching for what you need:</h3>
                <form role="search" method="get" action="<?php echo home_url('/'); ?>" class="flex gap-2">
                    <input type="search" 
                           name="s" 
                           placeholder="Search..." 
                           class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent">
                    <button type="submit" 
                            class="px-6 py-3 bg-[var(--accent-gold)] text-[var(--primary-color)] rounded-lg font-semibold hover:bg-opacity-90 transition-all">
                        Search
                    </button>
                </form>
            </div>
            
            <!-- Helpful Links -->
            <div class="mt-16">
                <h3 class="text-lg font-semibold text-[var(--text-primary)] mb-6">You might be looking for:</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                    
                    <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="w-12 h-12 bg-[var(--primary-color)] text-white rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold mb-2">About Us</h4>
                        <p class="text-[var(--text-secondary)] text-sm mb-4">Learn about NIESV Abuja Branch</p>
                        <a href="<?php echo home_url('/about'); ?>" class="text-[var(--primary-color)] font-semibold hover:text-[var(--accent-gold)] transition-colors">
                            Learn More →
                        </a>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="w-12 h-12 bg-[var(--primary-color)] text-white rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold mb-2">Events</h4>
                        <p class="text-[var(--text-secondary)] text-sm mb-4">Upcoming conferences and workshops</p>
                        <a href="<?php echo home_url('/events'); ?>" class="text-[var(--primary-color)] font-semibold hover:text-[var(--accent-gold)] transition-colors">
                            View Events →
                        </a>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="w-12 h-12 bg-[var(--primary-color)] text-white rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold mb-2">Contact</h4>
                        <p class="text-[var(--text-secondary)] text-sm mb-4">Get in touch with us</p>
                        <a href="<?php echo home_url('/contact'); ?>" class="text-[var(--primary-color)] font-semibold hover:text-[var(--accent-gold)] transition-colors">
                            Contact Us →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
