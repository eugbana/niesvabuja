<?php
/**
 * Template for single team member
 *
 * @package NIESV_Theme
 */

get_header(); ?>

<?php while (have_posts()) : the_post();
    $member_name = get_field('member_name') ?: get_the_title();
    $member_position = get_field('member_position');
    $member_bio = get_field('member_bio');
    $member_qualifications = get_field('member_qualifications');
    $member_email = get_field('member_email');
    $member_phone = get_field('member_phone');
    $member_linkedin = get_field('member_linkedin');
?>

<main class="flex-1 bg-[var(--background-color)]">
    <!-- Member Header -->
    <section class="py-16 md:py-20 bg-[var(--secondary-color)]">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    
                    <!-- Member Photo -->
                    <div class="text-center md:text-left">
                        <?php
                        $profile_image = get_field('member_profile_image');
                        if ($profile_image) : ?>
                            <div class="inline-block">
                                <img src="<?php echo esc_url($profile_image['sizes']['large'] ?? $profile_image['url']); ?>"
                                     alt="<?php echo esc_attr($profile_image['alt'] ?: $member_name); ?>"
                                     class="w-80 h-80 object-cover rounded-full shadow-2xl mx-auto">
                            </div>
                        <?php elseif (has_post_thumbnail()) : ?>
                            <div class="inline-block">
                                <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>"
                                     alt="<?php echo esc_attr($member_name); ?>"
                                     class="w-80 h-80 object-cover rounded-full shadow-2xl mx-auto">
                            </div>
                        <?php else : ?>
                            <div class="w-80 h-80 bg-[var(--primary-color)] text-white rounded-full flex items-center justify-center mx-auto shadow-2xl">
                                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path>
                                </svg>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Member Info -->
                    <div>
                        <h1 class="text-4xl md:text-5xl font-extrabold text-[var(--primary-color)] mb-4">
                            <?php echo esc_html($member_name); ?>
                        </h1>
                        
                        <?php if ($member_position) : ?>
                            <h2 class="text-xl md:text-2xl font-semibold text-[var(--text-secondary)] mb-6">
                                <?php echo wp_kses_post($member_position); ?>
                            </h2>
                        <?php endif; ?>
                        
                        <!-- Contact Information -->
                        <div class="space-y-3 mb-8">
                            <?php if ($member_email) : ?>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-[var(--primary-color)] mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <a href="mailto:<?php echo esc_attr($member_email); ?>" class="text-[var(--text-primary)] hover:text-[var(--primary-color)] transition-colors">
                                        <?php echo wp_kses_post($member_email); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($member_phone) : ?>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-[var(--primary-color)] mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <a href="tel:<?php echo esc_attr($member_phone); ?>" class="text-[var(--text-primary)] hover:text-[var(--primary-color)] transition-colors">
                                        <?php echo wp_kses_post($member_phone); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($member_linkedin) : ?>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-[var(--primary-color)] mr-3" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"></path>
                                    </svg>
                                    <a href="<?php echo esc_url($member_linkedin); ?>" target="_blank" rel="noopener noreferrer" class="text-[var(--text-primary)] hover:text-[var(--primary-color)] transition-colors">
                                        LinkedIn Profile
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4">
                            <?php if ($member_email) : ?>
                                <a href="mailto:<?php echo esc_attr($member_email); ?>" 
                                   class="inline-flex items-center justify-center px-6 py-3 bg-[var(--primary-color)] text-white rounded-lg font-semibold hover:bg-opacity-90 transition-all">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    Send Email
                                </a>
                            <?php endif; ?>
                            
                            <?php if ($member_linkedin) : ?>
                                <a href="<?php echo esc_url($member_linkedin); ?>" 
                                   target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center justify-center px-6 py-3 border-2 border-[var(--primary-color)] text-[var(--primary-color)] rounded-lg font-semibold hover:bg-[var(--primary-color)] hover:text-white transition-all">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"></path>
                                    </svg>
                                    LinkedIn
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Member Details -->
    <section class="py-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <div class="grid lg:grid-cols-3 gap-12">
                    
                    <!-- Main Content -->
                    <div class="lg:col-span-2">
                        
                        <!-- Biography -->
                        <?php if ($member_bio) : ?>
                            <div class="bg-white p-8 rounded-lg shadow-lg mb-8">
                                <h3 class="text-2xl font-bold text-[var(--primary-color)] mb-6">Biography</h3>
                                <div class="prose prose-lg max-w-none text-[var(--text-secondary)]">
                                    <?php echo wp_kses_post($member_bio); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Additional Content -->
                        <?php if (get_the_content()) : ?>
                            <div class="bg-white p-8 rounded-lg shadow-lg mb-8">
                                <h3 class="text-2xl font-bold text-[var(--primary-color)] mb-6">Additional Information</h3>
                                <div class="prose prose-lg max-w-none text-[var(--text-secondary)]">
                                    <?php the_content(); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Sidebar -->
                    <div class="lg:col-span-1">
                        <div class="sticky top-8 space-y-8">
                            
                            <!-- Qualifications -->
                            <?php if ($member_qualifications) : ?>
                                <div class="bg-white p-6 rounded-lg shadow-lg">
                                    <h3 class="text-xl font-bold text-[var(--primary-color)] mb-4">Qualifications</h3>
                                    <div class="text-[var(--text-secondary)] leading-relaxed">
                                        <?php echo wpautop($member_qualifications); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Quick Contact -->
                            <div class="bg-[var(--primary-color)] text-white p-6 rounded-lg">
                                <h3 class="text-xl font-bold mb-4">Get in Touch</h3>
                                <p class="text-gray-300 mb-6">Have questions or need professional consultation?</p>
                                
                                <div class="space-y-3">
                                    <?php if ($member_email) : ?>
                                        <a href="mailto:<?php echo esc_attr($member_email); ?>" 
                                           class="flex items-center w-full p-3 bg-white bg-opacity-20 rounded-lg hover:bg-opacity-30 transition-all">
                                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="font-medium">Send Email</span>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($member_phone) : ?>
                                        <a href="tel:<?php echo esc_attr($member_phone); ?>" 
                                           class="flex items-center w-full p-3 bg-white bg-opacity-20 rounded-lg hover:bg-opacity-30 transition-all">
                                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                            <span class="font-medium">Call Now</span>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Other Team Members -->
                            <?php
                            $other_members = new WP_Query(array(
                                'post_type' => 'team_members',
                                'posts_per_page' => 3,
                                'post__not_in' => array(get_the_ID()),
                                'orderby' => 'menu_order',
                                'order' => 'ASC'
                            ));
                            
                            if ($other_members->have_posts()) :
                            ?>
                                <div class="bg-white p-6 rounded-lg shadow-lg">
                                    <h3 class="text-xl font-bold text-[var(--primary-color)] mb-4">Other Team Members</h3>
                                    <div class="space-y-4">
                                        <?php while ($other_members->have_posts()) : $other_members->the_post();
                                            $other_member_name = get_field('member_name') ?: get_the_title();
                                            $other_position = get_field('member_position');
                                        ?>
                                            <div class="flex items-center">
                                                <?php
                                                $other_profile_image = get_field('member_profile_image');
                                                if ($other_profile_image) : ?>
                                                    <img src="<?php echo esc_url($other_profile_image['sizes']['thumbnail'] ?? $other_profile_image['url']); ?>"
                                                         alt="<?php echo esc_attr($other_profile_image['alt'] ?: $other_member_name); ?>"
                                                         class="w-12 h-12 object-cover rounded-full mr-3">
                                                <?php elseif (has_post_thumbnail()) : ?>
                                                    <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'); ?>"
                                                         alt="<?php echo esc_attr($other_member_name); ?>"
                                                         class="w-12 h-12 object-cover rounded-full mr-3">
                                                <?php else : ?>
                                                    <div class="w-12 h-12 bg-[var(--secondary-color)] rounded-full flex items-center justify-center mr-3">
                                                        <svg class="w-6 h-6 text-[var(--primary-color)]" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path>
                                                        </svg>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <div class="flex-1">
                                                    <h4 class="font-semibold text-sm">
                                                        <a href="<?php the_permalink(); ?>" class="hover:text-[var(--primary-color)] transition-colors">
                                                            <?php echo esc_html($other_member_name); ?>
                                                        </a>
                                                    </h4>
                                                    <?php if ($other_position) : ?>
                                                        <p class="text-xs text-[var(--text-secondary)]"><?php echo wp_kses_post($other_position); ?></p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endwhile; wp_reset_postdata(); ?>
                                    </div>
                                    
                                    <div class="mt-6">
                                        <a href="<?php echo get_post_type_archive_link('team_members'); ?>" 
                                           class="text-[var(--primary-color)] font-semibold hover:text-[var(--accent-gold)] transition-colors text-sm">
                                            View All Team Members →
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php endwhile; ?>

<?php get_footer(); ?>
