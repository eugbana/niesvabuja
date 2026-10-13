/**
 * NIESV Theme JavaScript
 *
 * The mobile menu and desktop dropdowns are handled by header.php and style.css.
 */

(function($) {
'use strict';

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling for anchor links
    initSmoothScrolling();

    // Image lightbox
    initLightbox();

    // Contact form handling
    initContactForm();

    // Search functionality
    initSearch();

    // Animation on scroll
    initScrollAnimations();

    // PWA notifications
    initPWANotifications();
});

    /**
     * Initialize smooth scrolling
     */
    function initSmoothScrolling() {
        $('a[href*="#"]:not([href="#"])').click(function() {
            if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    $('html, body').animate({
                        scrollTop: target.offset().top - 100
                    }, 1000);
                    return false;
                }
            }
        });
    }

    /**
     * Initialize lightbox for images
     */
    function initLightbox() {
        // Pages such as single-gallery.php ship their own lightbox
        if (document.getElementById('lightbox')) {
            return;
        }

        // Create lightbox HTML
        const lightboxHTML = `
            <div id="lightbox" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center p-4">
                <div class="relative max-w-4xl max-h-full">
                    <img id="lightbox-image" src="" alt="" class="max-w-full max-h-full object-contain">
                    <button id="lightbox-close" class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300 transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', lightboxHTML);

        // Lightbox functionality
        window.openLightbox = function(imageSrc) {
            const lightbox = document.getElementById('lightbox');
            const lightboxImage = document.getElementById('lightbox-image');
            
            lightboxImage.src = imageSrc;
            lightbox.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        };

        // Close lightbox
        document.addEventListener('click', function(event) {
            const lightbox = document.getElementById('lightbox');
            const lightboxClose = document.getElementById('lightbox-close');
            
            if (lightbox && lightboxClose && (event.target === lightbox || lightboxClose.contains(event.target))) {
                lightbox.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        });

        // Close lightbox with escape key
        document.addEventListener('keydown', function(event) {
            const lightbox = document.getElementById('lightbox');
            if (event.key === 'Escape' && lightbox) {
                lightbox.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        });
    }

    /**
     * Initialize contact form
     */
    function initContactForm() {
        const contactForm = document.querySelector('form[action*="admin-post.php"]');
        
        if (contactForm) {
            contactForm.addEventListener('submit', function(event) {
                // Basic form validation
                const requiredFields = this.querySelectorAll('[required]');
                let isValid = true;
                
                requiredFields.forEach(function(field) {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('border-red-500');
                        
                        // Remove error styling on input
                        field.addEventListener('input', function() {
                            this.classList.remove('border-red-500');
                        });
                    }
                });
                
                if (!isValid) {
                    event.preventDefault();
                    alert('Please fill in all required fields.');
                    return false;
                }
                
                // Show loading state
                const submitButton = this.querySelector('button[type="submit"]');
                const originalText = submitButton.textContent;
                submitButton.textContent = 'Sending...';
                submitButton.disabled = true;
                
                // Re-enable button after 5 seconds (in case of error)
                setTimeout(function() {
                    submitButton.textContent = originalText;
                    submitButton.disabled = false;
                }, 5000);
            });
        }
    }

    /**
     * Initialize search functionality
     */
    function initSearch() {
        const searchForms = document.querySelectorAll('form[method="GET"]');
        
        searchForms.forEach(function(form) {
            form.addEventListener('submit', function(event) {
                const searchInput = this.querySelector('input[name="search"]');
                
                if (searchInput && !searchInput.value.trim()) {
                    event.preventDefault();
                    searchInput.focus();
                    return false;
                }
            });
        });
    }

    /**
     * Initialize scroll animations
     */
    function initScrollAnimations() {
        // Fade in elements on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observe elements with animation class
        const animatedElements = document.querySelectorAll('.card, .event-card, .news-card, .property-card');
        animatedElements.forEach(function(element) {
            observer.observe(element);
        });
    }

    /**
     * Property filter functionality
     */
    window.filterProperties = function() {
        const searchInput = document.querySelector('input[name="search"]');
        const typeSelect = document.querySelector('select[name="property_type"]');
        const properties = document.querySelectorAll('.property-card');
        
        const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
        const selectedType = typeSelect ? typeSelect.value : '';
        
        properties.forEach(function(property) {
            const title = property.querySelector('h3').textContent.toLowerCase();
            const type = property.dataset.type || '';
            const location = property.dataset.location || '';
            
            const matchesSearch = !searchTerm || 
                title.includes(searchTerm) || 
                location.toLowerCase().includes(searchTerm);
            
            const matchesType = !selectedType || type === selectedType;
            
            if (matchesSearch && matchesType) {
                property.style.display = 'block';
            } else {
                property.style.display = 'none';
            }
        });
    };

    /**
     * Back to top button
     */
    $(window).scroll(function() {
        if ($(this).scrollTop() > 300) {
            if (!$('#back-to-top').length) {
                $('body').append(`
                    <button id="back-to-top" class="fixed bottom-8 right-8 bg-[var(--primary-color)] text-white p-3 rounded-full shadow-lg hover:bg-opacity-90 transition-all z-40">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                        </svg>
                    </button>
                `);
                
                $('#back-to-top').click(function() {
                    $('html, body').animate({scrollTop: 0}, 800);
                });
            }
        } else {
            $('#back-to-top').remove();
        }
    });

/**
 * Initialize PWA Notifications
 */
function initPWANotifications() {
    // Check if we're in a PWA environment
    const isPWA = window.matchMedia('(display-mode: standalone)').matches ||
                  window.navigator.standalone === true ||
                  document.referrer.includes('android-app://');

    // Only show notifications in PWA mode on mobile
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

    if (!isPWA && !isMobile) {
        return; // Don't show notifications in regular browser on desktop
    }

    // Get notification data from WordPress
    fetch(window.location.origin + '/wp-json/niesv/v1/pwa-notification')
        .then(response => response.json())
        .then(data => {
            if (data.enabled && data.title && data.headline) {
                showPWANotification(data);
            }
        })
        .catch(error => {
            console.log('PWA notification fetch failed:', error);
        });
}

/**
 * Show PWA Notification
 */
function showPWANotification(data) {
    const notificationId = 'pwa-notification-' + (data.hash || Date.now());

    // Check if notification was already dismissed
    if (localStorage.getItem('dismissed-' + notificationId)) {
        return;
    }

    // Create notification HTML
    const notificationHTML = `
        <div id="${notificationId}" class="fixed top-4 left-4 right-4 z-50 bg-white border border-gray-200 rounded-lg shadow-lg p-4 transform translate-y-0 transition-all duration-300 ease-out" style="max-width: 400px; margin: 0 auto;">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-2">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h4 class="text-sm font-semibold text-gray-900">${data.title}</h4>
                    </div>
                    <h3 class="text-base font-medium text-gray-900 mb-2">${data.headline}</h3>
                    ${data.description ? `<p class="text-sm text-gray-600 mb-3">${data.description}</p>` : ''}
                    ${data.button_text && data.button_link ? `
                        <a href="${data.button_link}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            ${data.button_text}
                        </a>
                    ` : ''}
                </div>
                <button onclick="dismissPWANotification('${notificationId}')" class="ml-4 text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    `;

    // Add notification to page
    document.body.insertAdjacentHTML('afterbegin', notificationHTML);

    // Animate in
    const notification = document.getElementById(notificationId);
    setTimeout(() => {
        notification.style.transform = 'translateY(0)';
    }, 100);

    // Auto-dismiss after 10 seconds if no interaction
    setTimeout(() => {
        if (document.getElementById(notificationId)) {
            dismissPWANotification(notificationId);
        }
    }, 10000);
}

/**
 * Dismiss PWA Notification
 */
function dismissPWANotification(notificationId) {
    const notification = document.getElementById(notificationId);
    if (notification) {
        // Animate out
        notification.style.transform = 'translateY(-100%)';
        notification.style.opacity = '0';

        // Remove from DOM after animation
        setTimeout(() => {
            notification.remove();
        }, 300);

        // Remember dismissal
        localStorage.setItem('dismissed-' + notificationId, 'true');
    }
}

// Make dismiss function globally available
window.dismissPWANotification = dismissPWANotification;

})(jQuery);
