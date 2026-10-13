<?php
/**
 * Team Members Block Template
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param int|string $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'team-members-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'team-members-block';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}

// Load values and assign defaults.
$section_title = get_field('section_title') ?: 'Our Team';
$number_of_members = get_field('number_of_members') ?: 6;
$show_position = get_field('show_position') !== false;
$show_bio = get_field('show_bio') !== false;
$show_contact = get_field('show_contact') !== false;

// Query team members
$args = array(
    'post_type' => 'team_members',
    'posts_per_page' => $number_of_members,
    'post_status' => 'publish',
    'orderby' => 'menu_order',
    'order' => 'ASC'
);

$team_query = new WP_Query($args);
?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?> py-12 md:py-20 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Title -->
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold tracking-tight mb-2 text-[rgba(0,0,40,1)] border-b-2 border-[#d4af37] inline-block pb-2">
                <?php echo esc_html($section_title); ?>
            </h2>
        </div>
        
        <?php if ($team_query->have_posts()) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <?php while ($team_query->have_posts()) : $team_query->the_post();
                    $member_name = get_field('member_name') ?: get_the_title();
                    $member_position = get_field('member_position');
                    $member_bio = get_field('member_bio');
                    $member_email = get_field('member_email');
                    $member_phone = get_field('member_phone');
                    $member_linkedin = get_field('member_linkedin');
                ?>
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden group transform hover:-translate-y-2 transition-all duration-300">
                        
                        <!-- Member Photo -->
                        <div class="relative">
                            <?php
                            $profile_image = get_field('member_profile_image');
                            if ($profile_image) : ?>
                                <img src="<?php echo esc_url($profile_image['sizes']['medium'] ?? $profile_image['url']); ?>"
                                     alt="<?php echo esc_attr($profile_image['alt'] ?: $member_name); ?>"
                                     class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300">
                            <?php elseif (has_post_thumbnail()) : ?>
                                <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium'); ?>"
                                     alt="<?php echo esc_attr($member_name); ?>"
                                     class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300">
                            <?php else : ?>
                                <div class="w-full h-64 bg-[rgba(0,0,40,1)] text-white flex items-center justify-center">
                                    <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path>
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Member Info -->
                        <div class="p-6">
                            <!-- Name -->
                            <h3 class="text-xl font-bold mb-2 text-[rgba(0,0,40,1)]">
                                <a href="<?php the_permalink(); ?>" class="hover:text-[#d4af37] transition-colors">
                                    <?php echo esc_html($member_name); ?>
                                </a>
                            </h3>
                            
                            <!-- Position -->
                            <?php if ($show_position && $member_position) : ?>
                                <p class="text-[#49739c] font-semibold mb-3">
                                    <?php echo esc_html($member_position); ?>
                                </p>
                            <?php endif; ?>
                            
                            <!-- Bio -->
                            <?php if ($show_bio && $member_bio) : ?>
                                <div class="text-[#49739c] text-sm mb-4 leading-relaxed">
                                    <?php echo wp_trim_words(strip_tags($member_bio), 20, '...'); ?>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Contact Info -->
                            <?php if ($show_contact && ($member_email || $member_phone || $member_linkedin)) : ?>
                                <div class="flex items-center justify-center gap-3 pt-4 border-t border-gray-200">
                                    <?php if ($member_email) : ?>
                                        <a href="mailto:<?php echo esc_attr($member_email); ?>" 
                                           class="w-8 h-8 bg-[rgba(0,0,40,1)] text-white rounded-full flex items-center justify-center hover:bg-[#d4af37] hover:text-[rgba(0,0,40,1)] transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($member_phone) : ?>
                                        <a href="tel:<?php echo esc_attr($member_phone); ?>" 
                                           class="w-8 h-8 bg-[rgba(0,0,40,1)] text-white rounded-full flex items-center justify-center hover:bg-[#d4af37] hover:text-[rgba(0,0,40,1)] transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($member_linkedin) : ?>
                                        <a href="<?php echo esc_url($member_linkedin); ?>" 
                                           target="_blank" rel="noopener noreferrer"
                                           class="w-8 h-8 bg-[rgba(0,0,40,1)] text-white rounded-full flex items-center justify-center hover:bg-[#d4af37] hover:text-[rgba(0,0,40,1)] transition-all">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"></path>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                            <!-- View Profile Link -->
                            <div class="mt-4">
                                <a href="<?php the_permalink(); ?>" 
                                   class="block w-full text-center py-2 px-4 bg-[rgba(0,0,40,1)] text-white rounded-md text-sm font-semibold hover:bg-[#d4af37] hover:text-[rgba(0,0,40,1)] transition-all">
                                    View Profile
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            
            <!-- View All Link -->
            <div class="text-center mt-12">
                <a href="<?php echo get_post_type_archive_link('team_members'); ?>" 
                   class="inline-flex items-center px-6 py-3 bg-[rgba(0,0,40,1)] text-white rounded-lg font-semibold hover:bg-[#d4af37] hover:text-[rgba(0,0,40,1)] transition-all">
                    View All Team Members
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
            
        <?php else : ?>
            <!-- No Team Members Found -->
            <div class="text-center py-12">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Team Members Found</h3>
                <p class="text-gray-600">Please add some team members to display them here.</p>
            </div>
        <?php endif; ?>
        
        <?php wp_reset_postdata(); ?>
    </div>
</section>

<?php if ($is_preview) : ?>
    <style>
        .team-members-block {
            background: #f8fafc;
            border: 2px dashed #ccc;
            padding: 2rem;
        }
    </style>
<?php endif; ?>
