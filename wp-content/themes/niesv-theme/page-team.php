<?php
/**
 * Template Name: Team Page
 * Template for Team/Members page
 *
 * @package NIESV_Theme
 */

get_header(); ?>

<?php
// Get hero section data
$hero_args = array(
    'title' => get_field('hero_title') ?: get_the_title(),
    'subtitle' => get_field('hero_subtitle'),
    'background_image' => get_field('hero_background_image'),
    'overlay_color' => get_field('hero_overlay_color') ? 'rgba(' . implode(',', sscanf(get_field('hero_overlay_color'), "#%02x%02x%02x")) . ', 0.8)' : 'rgba(30, 58, 138, 0.8)',
    'size' => get_field('hero_size') ?: 'small',
    'show_breadcrumb' => get_field('show_hero_breadcrumb') !== false,
);

// Include hero section
get_template_part('template-parts/hero-section', null, $hero_args);
?>

<main class="container mx-auto flex-grow px-4 sm:px-6 lg:px-8 py-12 md:py-20">
    <div class="max-w-6xl mx-auto">
        <?php while (have_posts()) : the_post(); ?>
            
            <!-- Leadership Section -->
            <?php if (get_field('show_leadership_section')) : ?>
                <div class="mb-20">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl font-bold text-[var(--primary-color)] border-b-4 border-[var(--accent-gold)] inline-block pb-2">
                            <?php echo wp_kses_post(get_field('leadership_title') ?: 'Leadership Team'); ?>
                        </h2>
                        <?php if (get_field('leadership_subtitle')) : ?>
                            <p class="mt-4 text-lg text-[var(--text-secondary)]">
                                <?php echo wp_kses_post(get_field('leadership_subtitle')); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    
                    <?php
                    // Get leadership team members
                    $leadership_query = new WP_Query(array(
                        'post_type' => 'team_members',
                        'posts_per_page' => -1,
                        'post_status' => 'publish',
                        'meta_query' => array(
                            array(
                                'key' => 'is_leadership',
                                'value' => '1',
                                'compare' => '='
                            )
                        ),
                        'orderby' => 'menu_order',
                        'order' => 'ASC'
                    ));

                    if ($leadership_query->have_posts()) :
                    ?>
                        <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                            <?php while ($leadership_query->have_posts()) : $leadership_query->the_post();
                                $member_name = get_field('member_name') ?: get_the_title();
                                $profile_image = get_field('member_profile_image');
                            ?>
                                <div class="bg-white rounded-2xl shadow-lg p-6 text-center hover:shadow-xl transition-shadow duration-300">
                                    <?php if ($profile_image) : ?>
                                        <div class="w-32 h-32 rounded-full mx-auto mb-4 overflow-hidden">
                                            <img src="<?php echo esc_url($profile_image['sizes']['medium'] ?? $profile_image['url']); ?>"
                                                 alt="<?php echo esc_attr($profile_image['alt'] ?: $member_name); ?>"
                                                 class="w-full h-full object-cover">
                                        </div>
                                    <?php elseif (has_post_thumbnail()) : ?>
                                        <div class="w-32 h-32 rounded-full mx-auto mb-4 overflow-hidden">
                                            <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium'); ?>"
                                                 alt="<?php echo esc_attr($member_name); ?>"
                                                 class="w-full h-full object-cover">
                                        </div>
                                    <?php else : ?>
                                        <div class="w-32 h-32 rounded-full mx-auto mb-4 bg-gray-200 flex items-center justify-center">
                                            <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    <?php endif; ?>

                                    <h3 class="text-xl font-bold text-[var(--primary-color)] mb-2">
                                        <?php echo esc_html($member_name); ?>
                                    </h3>
                                    
                                    <p class="text-[var(--accent-gold)] font-semibold text-sm mb-4">
                                        <?php echo wp_kses_post(get_field('member_position') ?: 'Member'); ?>
                                    </p>
                                    
                                    <?php if (get_field('member_bio')) : ?>
                                        <p class="text-[var(--text-secondary)] text-sm leading-relaxed mb-4">
                                            <?php echo wp_trim_words(get_field('member_bio'), 20); ?>
                                        </p>
                                    <?php endif; ?>
                                    
                                    <?php if (get_field('member_qualifications')) : ?>
                                        <div class="text-xs text-[var(--text-secondary)] mb-4">
                                            <strong>Qualifications:</strong><br>
                                            <?php echo wp_kses_post(get_field('member_qualifications')); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Social Links -->
                                    <?php if (have_rows('social_links')) : ?>
                                        <div class="flex justify-center space-x-3">
                                            <?php while (have_rows('social_links')) : the_row(); ?>
                                                <a href="<?php echo get_sub_field('social_url'); ?>" 
                                                   target="_blank" 
                                                   class="w-8 h-8 bg-[var(--primary-color)] text-white rounded-full flex items-center justify-center hover:bg-[var(--accent-gold)] transition-colors duration-300">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84"></path>
                                                    </svg>
                                                </a>
                                            <?php endwhile; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php 
                        wp_reset_postdata();
                    endif; 
                    ?>
                </div>
            <?php endif; ?>
            
            <!-- All Members Section -->
            <div class="mb-16">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-[var(--primary-color)] border-b-4 border-[var(--accent-gold)] inline-block pb-2">
                        <?php echo wp_kses_post(get_field('members_title') ?: 'Our Members'); ?>
                    </h2>
                    <?php if (get_field('members_subtitle')) : ?>
                        <p class="mt-4 text-lg text-[var(--text-secondary)]">
                            <?php echo wp_kses_post(get_field('members_subtitle')); ?>
                        </p>
                    <?php endif; ?>
                </div>
                
                <!-- Members Filter -->
                <div class="flex flex-wrap gap-4 mb-12 justify-center">
                    <button class="member-filter-btn active px-6 py-2 rounded-full bg-[var(--primary-color)] text-white font-semibold transition-all duration-300" data-filter="all">
                        All Members
                    </button>
                    <button class="member-filter-btn px-6 py-2 rounded-full border-2 border-gray-300 text-gray-600 font-semibold hover:border-[var(--primary-color)] hover:text-[var(--primary-color)] transition-all duration-300" data-filter="leadership">
                        Leadership
                    </button>
                    <!--<button class="member-filter-btn px-6 py-2 rounded-full border-2 border-gray-300 text-gray-600 font-semibold hover:border-[var(--primary-color)] hover:text-[var(--primary-color)] transition-all duration-300" data-filter="regular">-->
                    <!--    Regular Members-->
                    <!--</button>-->
                </div>
                
                <!-- Members Grid -->
                <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="members-grid">
                    <?php
                    // Get all team members
                    $members_query = new WP_Query(array(
                        'post_type' => 'team_members',
                        'posts_per_page' => -1,
                        'post_status' => 'publish',
                        'orderby' => 'menu_order',
                        'order' => 'ASC'
                    ));

                    if ($members_query->have_posts()) :
                        while ($members_query->have_posts()) : $members_query->the_post();
                            $member_name = get_field('member_name') ?: get_the_title();
                            $is_leadership = get_field('is_leadership');
                            $member_type = $is_leadership ? 'leadership' : 'regular';
                    ?>
                        <div class="member-card bg-white rounded-xl shadow-md p-4 text-center hover:shadow-lg transition-shadow duration-300" 
                             data-type="<?php echo esc_attr($member_type); ?>">
                            
                            <?php
                            $profile_image = get_field('member_profile_image');
                            if ($profile_image) : ?>
                                <div class="w-24 h-24 rounded-full mx-auto mb-3 overflow-hidden">
                                    <img src="<?php echo esc_url($profile_image['sizes']['thumbnail'] ?? $profile_image['url']); ?>"
                                         alt="<?php echo esc_attr($profile_image['alt'] ?: $member_name); ?>"
                                         class="w-full h-full object-cover">
                                </div>
                            <?php elseif (has_post_thumbnail()) : ?>
                                <div class="w-24 h-24 rounded-full mx-auto mb-3 overflow-hidden">
                                    <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'); ?>"
                                         alt="<?php echo esc_attr($member_name); ?>"
                                         class="w-full h-full object-cover">
                                </div>
                            <?php else : ?>
                                <div class="w-24 h-24 rounded-full mx-auto mb-3 bg-gray-200 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            <?php endif; ?>
                            
                            <h4 class="text-lg font-bold text-[var(--primary-color)] mb-1">
                                <?php echo esc_html($member_name); ?>
                            </h4>
                            
                            <p class="text-[var(--accent-gold)] font-medium text-sm mb-2">
                                <?php echo wp_kses_post(get_field('member_position') ?: 'Member'); ?>
                            </p>
                            
                            <?php if (get_field('member_bio')) : ?>
                                <p class="text-[var(--text-secondary)] text-xs leading-relaxed">
                                    <?php echo wp_trim_words(get_field('member_bio'), 15); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php 
                        endwhile;
                        wp_reset_postdata();
                    else : 
                    ?>
                        <div class="col-span-full text-center py-12">
                            <p class="text-[var(--text-secondary)] text-lg">No team members found.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Join Our Team CTA -->
            <?php if (get_field('show_join_cta')) : ?>
                <div class="bg-[var(--primary-color)] rounded-2xl p-8 md:p-12 text-center text-white">
                    <h2 class="text-3xl font-bold mb-4">
                        <?php echo wp_kses_post(get_field('join_cta_title') ?: 'Join Our Professional Community'); ?>
                    </h2>
                    <?php if (get_field('join_cta_description')) : ?>
                        <p class="text-lg mb-8 opacity-90 max-w-2xl mx-auto">
                            <?php echo wp_kses_post(get_field('join_cta_description')); ?>
                        </p>
                    <?php endif; ?>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <?php if (get_field('join_cta_button_text')) : ?>
                            <a href="<?php echo esc_url(get_field('join_cta_button_link') ?: '#'); ?>" 
                               class="bg-[var(--accent-gold)] text-[var(--primary-color)] px-8 py-3 rounded-lg font-semibold hover:bg-opacity-90 transition-all duration-300">
                                <?php echo wp_kses_post(get_field('join_cta_button_text')); ?>
                            </a>
                        <?php endif; ?>
                        
                        <?php if (get_field('join_cta_secondary_button_text')) : ?>
                            <a href="<?php echo esc_url(get_field('join_cta_secondary_button_link') ?: '#'); ?>" 
                               class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-[var(--primary-color)] transition-all duration-300">
                                <?php echo wp_kses_post(get_field('join_cta_secondary_button_text')); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            
        <?php endwhile; ?>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.member-filter-btn');
    const memberCards = document.querySelectorAll('.member-card');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Update active button
            filterBtns.forEach(b => {
                b.classList.remove('active', 'bg-[var(--primary-color)]', 'text-white');
                b.classList.add('border-2', 'border-gray-300', 'text-gray-600');
            });
            this.classList.add('active', 'bg-[var(--primary-color)]', 'text-white');
            this.classList.remove('border-2', 'border-gray-300', 'text-gray-600');
            
            // Filter members
            memberCards.forEach(card => {
                const memberType = card.getAttribute('data-type');
                if (filter === 'all' || memberType === filter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});
</script>

<?php get_footer(); ?>
