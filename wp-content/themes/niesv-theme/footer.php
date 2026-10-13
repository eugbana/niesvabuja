<?php
/**
 * The template for displaying the footer
 *
 * @package NIESV_Theme
 */
?>

        <footer class="bg-[var(--primary-color)] text-white">
            <div class="container mx-auto px-10 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- About Section -->
                    <div>
                        <h3 class="footer-heading">
                            <?php echo wp_kses_post(get_field('footer_about_title', 'option') ?: 'NIESV Abuja'); ?>
                        </h3>
                        <p class="text-gray-300">
                            <?php echo wp_kses_post(get_field('footer_about_text', 'option') ?: 'The Nigerian Institution of Estate Surveyors and Valuers, Abuja Branch.'); ?>
                        </p>
                        
                        <?php if (get_field('footer_logo', 'option')) : ?>
                            <div class="mt-4">
                                <img src="<?php echo esc_url(get_field('footer_logo', 'option')); ?>" alt="NIESV Logo" class="h-12">
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Quick Links -->
                    <div>
                        <h3 class="footer-heading">Quick Links</h3>
                        <?php if (has_nav_menu('footer')) : ?>
                            <?php
                            wp_nav_menu(array(
                                'theme_location' => 'footer',
                                'menu_class' => 'footer-menu',
                                'container' => false,
                                'walker' => new NIESV_Footer_Walker_Nav_Menu(),
                            ));
                            ?>
                        <?php else : ?>
                            <ul>
                                <li class="mb-2">
                                    <a class="hover:text-gray-300 transition-colors" href="<?php echo esc_url(home_url('/about')); ?>">About Us</a>
                                </li>
                                <li class="mb-2">
                                    <a class="hover:text-gray-300 transition-colors" href="<?php echo esc_url(home_url('/membership')); ?>">Membership</a>
                                </li>
                                <li class="mb-2">
                                    <a class="hover:text-gray-300 transition-colors" href="<?php echo esc_url(home_url('/events')); ?>">Events</a>
                                </li>
                                <li class="mb-2">
                                    <a class="hover:text-gray-300 transition-colors" href="<?php echo esc_url(home_url('/news')); ?>">News</a>
                                </li>
                                <li class="mb-2">
                                    <a class="hover:text-gray-300 transition-colors" href="<?php echo esc_url(home_url('/contact')); ?>">Contact</a>
                                </li>
                            </ul>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Contact Information -->
                    <div>
                        <h3 class="footer-heading">Contact Us</h3>
                        
                        <?php if (get_field('contact_address', 'option')) : ?>
                            <p class="text-gray-300 mb-2">
                                <?php echo wp_kses_post(get_field('contact_address', 'option')); ?>
                            </p>
                        <?php endif; ?>
                        
                        <?php if (get_field('contact_email', 'option')) : ?>
                            <p class="text-gray-300 mb-2">
                                Email: <a href="mailto:<?php echo esc_attr(get_field('contact_email', 'option')); ?>" class="hover:text-white transition-colors">
                                    <?php echo wp_kses_post(get_field('contact_email', 'option')); ?>
                                </a>
                            </p>
                        <?php endif; ?>
                        
                        <?php if (get_field('contact_phone', 'option')) : ?>
                            <p class="text-gray-300">
                                Phone: <a href="tel:<?php echo esc_attr(get_field('contact_phone', 'option')); ?>" class="hover:text-white transition-colors">
                                    <?php echo wp_kses_post(get_field('contact_phone', 'option')); ?>
                                </a>
                            </p>
                        <?php endif; ?>
                        
                        <!-- Default contact info if ACF fields are empty -->
                        <?php if (!get_field('contact_address', 'option') && !get_field('contact_email', 'option') && !get_field('contact_phone', 'option')) : ?>
                            <p class="text-gray-300 mb-2">
                                1234 Real Estate Avenue, Abuja, Nigeria.
                            </p>
                            <p class="text-gray-300 mb-2">Email: info@niesvabuja.org</p>
                            <p class="text-gray-300">Phone: +234 123 456 7890</p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Social Media -->
                    <div>
                        <h3 class="footer-heading">Follow Us</h3>
                        <div class="flex items-center gap-4 flex-wrap">
                            <?php
                            // Get individual social media fields
                            $social_facebook = get_field('social_facebook', 'option');
                            $social_twitter = get_field('social_twitter', 'option');
                            $social_linkedin = get_field('social_linkedin', 'option');
                            $social_whatsapp = get_field('social_whatsapp', 'option');
                            $social_instagram = get_field('social_instagram', 'option');

                            // Display social icons if URLs are provided
                            if ($social_facebook) : ?>
                                <a class="hover:opacity-80 transition-opacity"
                                   href="<?php echo esc_url($social_facebook); ?>"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   title="Facebook">
                                    <?php echo niesv_get_social_icon('facebook'); ?>
                                </a>
                            <?php endif;

                            if ($social_twitter) : ?>
                                <a class="hover:opacity-80 transition-opacity"
                                   href="<?php echo esc_url($social_twitter); ?>"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   title="Twitter">
                                    <?php echo niesv_get_social_icon('twitter'); ?>
                                </a>
                            <?php endif;

                            if ($social_linkedin) : ?>
                                <a class="hover:opacity-80 transition-opacity"
                                   href="<?php echo esc_url($social_linkedin); ?>"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   title="LinkedIn">
                                    <?php echo niesv_get_social_icon('linkedin'); ?>
                                </a>
                            <?php endif;

                            if ($social_whatsapp) : ?>
                                <a class="hover:opacity-80 transition-opacity"
                                   href="<?php echo esc_url($social_whatsapp); ?>"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   title="WhatsApp">
                                    <?php echo niesv_get_social_icon('whatsapp'); ?>
                                </a>
                            <?php endif;

                            if ($social_instagram) : ?>
                                <a class="hover:opacity-80 transition-opacity"
                                   href="<?php echo esc_url($social_instagram); ?>"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   title="Instagram">
                                    <?php echo niesv_get_social_icon('instagram'); ?>
                                </a>
                            <?php endif;

                            // Show default icons if no social media URLs are set
                            if (!$social_facebook && !$social_twitter && !$social_linkedin && !$social_whatsapp && !$social_instagram) : ?>
                                <a class="hover:opacity-80 transition-opacity" href="#" target="_blank" rel="noopener noreferrer" title="Twitter">
                                    <?php echo niesv_get_social_icon('twitter'); ?>
                                </a>
                                <a class="hover:opacity-80 transition-opacity" href="#" target="_blank" rel="noopener noreferrer" title="Facebook">
                                    <?php echo niesv_get_social_icon('facebook'); ?>
                                </a>
                                <a class="hover:opacity-80 transition-opacity" href="#" target="_blank" rel="noopener noreferrer" title="WhatsApp">
                                    <?php echo niesv_get_social_icon('whatsapp'); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Newsletter Signup -->
                        <?php if (get_field('show_newsletter_signup', 'option')) : ?>
                            <div class="mt-6">
                                <h4 class="text-base font-semibold mb-2">Newsletter</h4>
                                <form class="flex gap-2" action="#" method="post">
                                    <input type="email" placeholder="Your email" class="flex-1 px-3 py-2 text-gray-900 rounded-md text-sm" required>
                                    <button type="submit" class="px-4 py-2 bg-[var(--accent-gold)] text-[var(--primary-color)] rounded-md text-sm font-semibold hover:bg-opacity-90 transition-all">
                                        Subscribe
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="border-t border-gray-700 mt-8 pt-6 text-center text-gray-400">
                    <p>
                        <?php echo wp_kses_post(get_field('copyright_text', 'option') ?: '© ' . date('Y') . ' NIESV Abuja Branch. All Rights Reserved.'); ?>
                    </p>
                    
                    <?php if (get_field('show_privacy_links', 'option')) : ?>
                        <div class="mt-2 flex justify-center gap-4 text-sm">
                            <a href="<?php echo esc_url(get_field('privacy_policy_link', 'option') ?: '#'); ?>" class="hover:text-white transition-colors">Privacy Policy</a>
                            <a href="<?php echo esc_url(get_field('terms_of_service_link', 'option') ?: '#'); ?>" class="hover:text-white transition-colors">Terms of Service</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </footer>
    </div>
</div>

<!-- Footer Submenu Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Footer Submenu Toggle
    const footerToggles = document.querySelectorAll('.footer-toggle');

    footerToggles.forEach(function(toggle) {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();

            const parentLi = this.closest('li');
            const submenu = parentLi.querySelector('.footer-submenu');
            const arrow = this.querySelector('.footer-arrow');

            if (submenu) {
                // Toggle submenu visibility
                submenu.classList.toggle('hidden');

                // Rotate arrow
                if (arrow) {
                    arrow.classList.toggle('rotate-180');
                }
            }
        });
    });
});
</script>

<?php wp_footer(); ?>

</body>
</html>

<?php
/**
 * Custom Walker for Footer Navigation Menu with Collapsible Submenus
 */
class NIESV_Footer_Walker_Nav_Menu extends Walker_Nav_Menu {

    function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"footer-submenu ml-4 mt-2 space-y-1 hidden\">\n";
    }

    function end_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }

    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        // Check if this item has children
        $has_children = in_array('menu-item-has-children', $classes);

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        // Different styling for different levels
        $li_class = 'mb-2';
        if ($depth == 0) {
            $li_class .= ' footer-menu-parent';
            if ($has_children) {
                $li_class .= ' has-submenu';
            }
        } else {
            $li_class .= ' footer-menu-child';
        }

        $output .= $indent . '<li class="' . $li_class . '"' . $id . $class_names .'>';

        $attributes = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
        $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target     ) .'"' : '';
        $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn        ) .'"' : '';
        $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url        ) .'"' : '';

        // Different link styling for different levels
        $link_class = 'hover:text-gray-300 transition-colors';
        if ($depth == 0) {
            $link_class .= ' font-medium text-white';
            if ($has_children) {
                $link_class .= ' footer-toggle';
            }
        } else {
            $link_class .= ' text-gray-300 text-sm pl-2';
        }

        $item_output = isset($args->before) ? $args->before : '';
        $item_output .= '<a class="' . $link_class . '"' . $attributes .'>';
        $item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '');

        // Add arrow for parent items with children
        if ($depth == 0 && $has_children) {
            $item_output .= '<svg class="w-4 h-4 transform transition-transform footer-arrow" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>';
        }

        $item_output .= '</a>';
        $item_output .= isset($args->after) ? $args->after : '';

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= "</li>\n";
    }
}
?>
