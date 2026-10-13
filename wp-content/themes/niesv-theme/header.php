<?php
/**
 * The header for our theme
 *
 * @package NIESV_Theme
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#000028">
    <meta name="msapplication-navbutton-color" content="#000028">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="NIESV">
    <meta name="application-name" content="NIESV">
    <meta name="msapplication-TileColor" content="#000028">
    <meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/assets/icons/icon-144x144.png">

    <!-- PWA Manifest -->
    <link rel="manifest" href="<?php echo home_url('/manifest.json'); ?>">

    <!-- Apple Touch Icons -->
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_template_directory_uri(); ?>/assets/icons/icon-72x72.png">
    <link rel="apple-touch-icon" sizes="96x96" href="<?php echo get_template_directory_uri(); ?>/assets/icons/icon-96x96.png">
    <link rel="apple-touch-icon" sizes="128x128" href="<?php echo get_template_directory_uri(); ?>/assets/icons/icon-128x128.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo get_template_directory_uri(); ?>/assets/icons/icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo get_template_directory_uri(); ?>/assets/icons/icon-152x152.png">
    <link rel="apple-touch-icon" sizes="192x192" href="<?php echo get_template_directory_uri(); ?>/assets/icons/icon-192x192.png">

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_template_directory_uri(); ?>/assets/icons/icon-72x72.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_template_directory_uri(); ?>/assets/icons/icon-72x72.png">
    
    <!-- Preconnect to Google Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Google Fonts -->
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?display=swap&family=Newsreader:wght@400;500;700;800&family=Noto+Sans:wght@400;500;700;900" onload="this.rel='stylesheet'">
    
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Tailwind CSS Script -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,container-queries"></script>

    <!-- Tailwind CSS Configuration -->
    <script>
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
    </script>
    
    <?php wp_head(); ?>
</head>

<body <?php body_class('bg-[var(--background-color)] text-[var(--text-primary)]'); ?>>
<?php wp_body_open(); ?>

<div class="relative flex size-full min-h-screen flex-col group/design-root overflow-x-hidden">
    <div class="layout-container flex h-full grow flex-col">
        <!-- Top Bar -->
        <div class="top-bar bg-[#1e3a8a] text-white py-2 px-4">
            <div class="container mx-auto flex justify-between items-center text-sm">
                <!-- Contact Info -->
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M14.243 5.757a6 6 0 10-.986 9.284 1 1 0 111.087 1.678A8 8 0 1118 10a3 3 0 01-4.8 2.401A4 4 0 1114 10a1 1 0 102 0c0-1.537-.586-3.07-1.757-4.243zM12 10a2 2 0 10-4 0 2 2 0 004 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span>info@niesv.org.ng</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                        </svg>
                        <span>+234 807 819 3014</span>
                    </div>
                </div>

                <!-- Social Media and Auth -->
                <div class="flex items-center gap-4">
                    <!-- Social Media -->
                    <div class="flex items-center gap-3">
                        <a href="#" class="text-blue-400 hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-blue-600 hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                    </div>

                    <!-- Auth Buttons -->
                    <div class="flex items-center gap-2">
                        <?php if (is_user_logged_in()) : ?>
                            <a href="<?php echo esc_url(home_url('/property-dashboard/')); ?>" class="px-3 py-1 bg-blue-600 text-white rounded text-xs hover:bg-blue-700 transition-colors">
                                Dashboard
                            </a>
                            <a href="<?php echo wp_logout_url(home_url()); ?>" class="px-3 py-1 border border-white text-white rounded text-xs hover:bg-white hover:text-blue-600 transition-colors">
                                Logout
                            </a>
                        <?php else : ?>
                            <a href="<?php echo esc_url(home_url('/register/')); ?>" class="px-3 py-1 border border-white text-white rounded text-xs hover:bg-white hover:text-blue-600 transition-colors">
                                REGISTER
                            </a>
                            <a href="<?php echo esc_url(home_url('/login/')); ?>" class="px-3 py-1 bg-yellow-500 text-blue-900 rounded text-xs font-semibold hover:bg-yellow-400 transition-colors">
                                MEMBER LOGIN
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Header -->
        <header class="site-header flex items-center justify-between whitespace-nowrap border-b border-solid border-[var(--secondary-color)] px-10 py-4 shadow-sm" style="background-color: #122251;">
            <div class="flex items-center gap-4">
                <?php if (has_custom_logo()) : ?>
                    <div class="custom-logo">
                        <?php the_custom_logo(); ?>
                    </div>
                <?php else : ?>
                    <svg class="size-10 text-[var(--primary-color)]" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <path d="M44 4H30.6666V17.3334H17.3334V30.6666H4V44H44V4Z" fill="currentColor"></path>
                    </svg>
                <?php endif; ?>
                
                <h2 class="text-2xl font-bold tracking-tight">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="text-[var(--primary-color)] hover:text-[var(--accent-gold)] transition-colors">
                        <?php echo get_field('site_title', 'option') ?: get_bloginfo('name'); ?>
                    </a>
                </h2>
            </div>
            
            <nav class="flex items-center gap-10 main-navigation">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class' => 'flex items-center gap-10',
                    'container' => false,
                    'fallback_cb' => 'niesv_fallback_menu',
                    'walker' => new NIESV_Walker_Nav_Menu(),
                ));
                ?>
            </nav>
            
            <!-- Mobile Menu Button -->
            <button class="md:hidden mobile-menu-toggle" id="mobile-menu-toggle" onclick="toggleMobileMenu()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </header>
        
        <!-- Mobile Menu -->
        <div class="mobile-menu hidden md:hidden" id="mobile-menu">
            <nav class="px-4 py-4">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class' => 'flex flex-col gap-4',
                    'container' => false,
                    'fallback_cb' => 'niesv_fallback_mobile_menu',
                ));
                ?>

                <!-- Mobile Auth Buttons -->
                <div class="mt-4 flex flex-col gap-2">
                    <?php if (is_user_logged_in()) : ?>
                        <a href="<?php echo esc_url(home_url('/property-dashboard/')); ?>" class="header-auth-button text-center">
                            Dashboard
                        </a>
                        <a href="<?php echo wp_logout_url(home_url()); ?>" class="header-auth-button text-center">
                            Logout
                        </a>
                    <?php else : ?>
                        <a href="<?php echo esc_url(home_url('/login/')); ?>" class="header-auth-button text-center">
                            Login
                        </a>
                        <a href="<?php echo esc_url(home_url('/register/')); ?>" class="header-auth-button text-center">
                            Sign Up
                        </a>
                    <?php endif; ?>
                </div>
            </nav>
        </div>

        <!-- Mobile Menu Script -->
        <script>
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileMenuToggle = document.getElementById('mobile-menu-toggle');

            if (mobileMenu && mobileMenuToggle) {
                const isHidden = mobileMenu.classList.contains('hidden');
                const icon = mobileMenuToggle.querySelector('svg path');

                if (isHidden) {
                    mobileMenu.classList.remove('hidden');
                    if (icon) {
                        icon.setAttribute('d', 'M6 18L18 6M6 6l12 12');
                    }
                } else {
                    mobileMenu.classList.add('hidden');
                    if (icon) {
                        icon.setAttribute('d', 'M4 6h16M4 12h16M4 18h16');
                    }
                }
            }
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileMenuToggle = document.getElementById('mobile-menu-toggle');

            if (mobileMenu && mobileMenuToggle && !mobileMenu.classList.contains('hidden')) {
                if (!mobileMenu.contains(event.target) && !mobileMenuToggle.contains(event.target)) {
                    mobileMenu.classList.add('hidden');
                    const icon = mobileMenuToggle.querySelector('svg path');
                    if (icon) {
                        icon.setAttribute('d', 'M4 6h16M4 12h16M4 18h16');
                    }
                }
            }
        });

        // Handle mobile submenu toggles with improved UX
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            if (mobileMenu) {
                const mobileMenuItems = mobileMenu.querySelectorAll('li.menu-item-has-children > a');
                mobileMenuItems.forEach(function(item) {
                    item.addEventListener('click', function(e) {
                        e.preventDefault();
                        const parentLi = this.parentElement;
                        const submenu = this.nextElementSibling;

                        if (submenu && submenu.classList.contains('sub-menu')) {
                            // Toggle submenu visibility
                            const isExpanded = submenu.classList.contains('show');

                            if (isExpanded) {
                                // Collapse
                                submenu.classList.remove('show');
                                parentLi.classList.remove('expanded');
                            } else {
                                // Expand
                                submenu.classList.add('show');
                                parentLi.classList.add('expanded');

                                // Scroll the expanded submenu into view
                                setTimeout(() => {
                                    submenu.scrollIntoView({
                                        behavior: 'smooth',
                                        block: 'nearest'
                                    });
                                }, 100);
                            }
                        }
                    });
                });

                // Add touch-friendly scrolling for mobile
                mobileMenu.style.webkitOverflowScrolling = 'touch';
            }
        });
        </script>

<?php
/**
 * Custom Walker for Navigation Menu with Dropdown Support
 */
class NIESV_Walker_Nav_Menu extends Walker_Nav_Menu {

    // Start Level - Wrap sub-menus in <ul>
    function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"sub-menu\">\n";
    }

    // End Level
    function end_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }

    // Start Element
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        // Add dropdown classes for parent items
        $has_children = in_array('menu-item-has-children', $classes);
        if ($has_children && $depth == 0) {
            $classes[] = 'relative group';
        }

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $output .= $indent . '<li' . $id . $class_names .'>';

        $attributes = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
        $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target     ) .'"' : '';
        $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn        ) .'"' : '';
        $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url        ) .'"' : '';

        $item_output = isset($args->before) ? $args->before : '';

        // Different styles for top-level and dropdown items
        if ($depth == 0) {
            $link_class = 'flex items-center text-lg font-medium text-[var(--text-primary)] hover:text-[var(--primary-color)] transition-colors';
            if ($has_children) {
                $link_class .= ' group-hover:text-[var(--primary-color)]';
            }
        } else {
            $link_class = 'block px-4 py-2 text-sm text-white hover:bg-[#1a1a3a] hover:text-white transition-colors';
        }

        $item_output .= '<a class="' . $link_class . '"' . $attributes .'>';
        $item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '');

        // Add dropdown arrow for parent items
        if ($has_children && $depth == 0) {
            $item_output .= '<svg class="ml-1 w-4 h-4 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>';
        }

        $item_output .= '</a>';
        $item_output .= isset($args->after) ? $args->after : '';

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    // End Element
    function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= "</li>\n";
    }
}

/**
 * Fallback menu for primary navigation
 */
function niesv_fallback_menu() {
    echo '<div class="flex items-center gap-10">';
    echo '<a class="text-lg font-medium text-[var(--text-primary)] hover:text-[var(--primary-color)] transition-colors" href="' . esc_url(home_url('/about')) . '">About Us</a>';
    echo '<a class="text-lg font-medium text-[var(--text-primary)] hover:text-[var(--primary-color)] transition-colors" href="' . esc_url(home_url('/membership')) . '">Membership</a>';
    echo '<a class="text-lg font-medium text-[var(--text-primary)] hover:text-[var(--primary-color)] transition-colors" href="' . esc_url(home_url('/events')) . '">Events</a>';
    echo '<a class="text-lg font-medium text-[var(--text-primary)] hover:text-[var(--primary-color)] transition-colors" href="' . esc_url(home_url('/news')) . '">News</a>';
    echo '<a class="text-lg font-medium text-[var(--text-primary)] hover:text-[var(--primary-color)] transition-colors" href="' . esc_url(home_url('/contact')) . '">Contact</a>';
    echo '</div>';
}

/**
 * Fallback menu for mobile navigation
 */
function niesv_fallback_mobile_menu() {
    echo '<div class="flex flex-col gap-4">';
    echo '<a class="text-lg font-medium text-[var(--text-primary)] hover:text-[var(--primary-color)] transition-colors" href="' . esc_url(home_url('/about')) . '">About Us</a>';
    echo '<a class="text-lg font-medium text-[var(--text-primary)] hover:text-[var(--primary-color)] transition-colors" href="' . esc_url(home_url('/membership')) . '">Membership</a>';
    echo '<a class="text-lg font-medium text-[var(--text-primary)] hover:text-[var(--primary-color)] transition-colors" href="' . esc_url(home_url('/events')) . '">Events</a>';
    echo '<a class="text-lg font-medium text-[var(--text-primary)] hover:text-[var(--primary-color)] transition-colors" href="' . esc_url(home_url('/news')) . '">News</a>';
    echo '<a class="text-lg font-medium text-[var(--text-primary)] hover:text-[var(--primary-color)] transition-colors" href="' . esc_url(home_url('/contact')) . '">Contact</a>';
    echo '</div>';
}
?>
