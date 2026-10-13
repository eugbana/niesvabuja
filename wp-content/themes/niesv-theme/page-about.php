<?php
/**
 * Template Name: About Us Page
 *
 * Custom template for About Us page with full-width layout
 * and enhanced Mission, Vision, Values section
 *
 * @package NIESV_Theme
 */

get_header(); ?>

<?php while (have_posts()) : the_post();
    // Get hero section data
    $hero_args = array(
        'title' => get_field('hero_title') ?: get_field('page_title') ?: get_the_title(),
        'subtitle' => get_field('hero_subtitle') ?: get_field('page_subtitle'),
        'background_image' => get_field('hero_background_image'),
        'overlay_color' => get_field('hero_overlay_color') ? 'rgba(' . implode(',', sscanf(get_field('hero_overlay_color'), "#%02x%02x%02x")) . ', 0.8)' : 'rgba(0, 90, 49, 0.8)',
        'size' => get_field('hero_size') ?: 'small',
        'show_breadcrumb' => get_field('show_hero_breadcrumb') !== false,
    );

    // Include hero section
    get_template_part('template-parts/hero-section', null, $hero_args);
?>

<main class="w-full flex-grow">
    <!-- Full-width container for About page -->
    <div class="w-full">

        <!-- Content sections with proper spacing -->
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20">
            <div class="max-w-6xl mx-auto">
            
            <div class="space-y-16">
                
                <!-- Commitment Section -->
                <?php if (get_field('show_commitment_section')) : ?>
                    <div class="grid md:grid-cols-2 gap-12 items-center">
                        <div class="space-y-4">
                            <h3 class="text-3xl font-bold tracking-tight text-[var(--primary-color)]">
                                <?php echo get_field('commitment_title') ?: 'Our Commitment to Excellence'; ?>
                            </h3>
                            <div class="text-lg leading-relaxed text-[var(--text-secondary)] wysiwyg-content prose prose-lg max-w-none">
                                <?php
                                $commitment_content = get_field('commitment_content');
                                if ($commitment_content) {
                                    echo wp_kses_post($commitment_content);
                                } else {
                                    echo 'The NIESV Abuja Branch is dedicated to fostering a community of highly skilled and ethical real estate professionals. We provide continuous professional development opportunities, ensuring our members are at the forefront of industry knowledge and best practices.';
                                }
                                ?>
                            </div>
                            <?php if (get_field('commitment_button_text')) : ?>
                                <a class="inline-flex items-center font-semibold text-[var(--primary-color)] hover:underline" 
                                   href="<?php echo get_field('commitment_button_link') ?: '#'; ?>">
                                    <?php echo get_field('commitment_button_text'); ?>
                                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M17 8l4 4m0 0l-4 4m4-4H3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="rounded-lg overflow-hidden shadow-lg">
                            <?php if (get_field('commitment_image')) : ?>
                                <img alt="<?php echo get_field('commitment_image_alt') ?: 'NIESV members at a professional development seminar'; ?>" 
                                     class="w-full h-full object-cover" 
                                     src="<?php echo get_field('commitment_image'); ?>">
                            <?php else : ?>
                                <img alt="NIESV members at a professional development seminar" 
                                     class="w-full h-full object-cover" 
                                     src="https://lh3.googleusercontent.com/aida-public/AB6AXuB5RFKuTp61Xay7Paw088jxxYCMOookjy9aM9tpZjcBD7BFbApe196mYQ0CXmazOIjR99Z9bWmatvvzoXp-mKHWvVTiDtv9KzgN-aDoin7i6Ok7Fs6AJSEwv-iPJSMt5aqh1GBtJ9A1Z1t5VW9PmOAZWsbT-o4RP9omSbWeMrEG9OYiSu4q6GQ9dcz5f1PNLNM8pgMzsi5I_Q-CNvkOHTnbewDNjPermpmZARlhuqGK_q_kNjr_S_2-8f0z6xJB5vfMKO1ieD3SRavn">
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Community Section -->
                <?php if (get_field('show_community_section')) : ?>
                    <?php $has_community_image = get_field('community_image'); ?>
                    <div class="<?php echo $has_community_image ? 'grid md:grid-cols-2 gap-12 items-center' : ''; ?>">
                        <?php if ($has_community_image) : ?>
                            <div class="rounded-lg overflow-hidden shadow-lg md:order-last">
                                <img alt="<?php echo get_field('community_image_alt') ?: 'Community image'; ?>"
                                     class="w-full h-full object-cover"
                                     src="<?php echo $has_community_image; ?>">
                            </div>
                        <?php endif; ?>
                        <div class="space-y-4">
                            <h3 class="text-3xl font-bold tracking-tight text-[var(--primary-color)]">
                                <?php echo get_field('community_title') ?: 'Community and Advocacy'; ?>
                            </h3>
                            <div class="text-lg leading-relaxed text-[var(--text-secondary)]">
                                <?php echo get_field('community_content') ?: 'As a key stakeholder in the urban development of Abuja, we actively engage in advocacy to influence policy and legislation for a sustainable and well-regulated real estate market.'; ?>
                            </div>
                            <?php if (get_field('community_button_text')) : ?>
                                <a class="inline-flex items-center font-semibold text-[var(--primary-color)] hover:underline" 
                                   href="<?php echo get_field('community_button_link') ?: '#'; ?>">
                                    <?php echo get_field('community_button_text'); ?>
                                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M17 8l4 4m0 0l-4 4m4-4H3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- History Section -->
                <?php if (get_field('show_history_section')) : ?>
                    <section class="p-6 rounded-lg shadow-lg bg-white">
                        <div class="flex justify-between items-start">
                            <h3 class="text-3xl font-bold tracking-tight text-[var(--primary-color)] border-b-2 border-[var(--primary-color)] pb-4 mb-6 flex-grow">
                                <?php echo get_field('history_title') ?: 'Our History'; ?>
                            </h3>
                        </div>
                        
                        <?php if (have_rows('history_timeline')) : ?>
                            <div class="space-y-6">
                                <?php while (have_rows('history_timeline')) : the_row(); ?>
                                    <div class="border-l-4 border-[var(--accent-gold)] pl-6">
                                        <div class="flex items-center gap-4 mb-2">
                                            <span class="bg-[var(--primary-color)] text-white px-3 py-1 rounded-full text-sm font-semibold">
                                                <?php echo get_sub_field('year'); ?>
                                            </span>
                                            <h4 class="text-xl font-semibold text-[var(--primary-color)]">
                                                <?php echo get_sub_field('title'); ?>
                                            </h4>
                                        </div>
                                        <p class="text-[var(--text-secondary)] leading-relaxed">
                                            <?php echo get_sub_field('description'); ?>
                                        </p>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else : ?>
                            <div class="text-[var(--text-secondary)] leading-relaxed">
                                <?php echo get_field('history_content') ?: 'The NIESV Abuja Branch was established to serve the Federal Capital Territory and surrounding areas, bringing together professional estate surveyors and valuers committed to excellence in real estate practice.'; ?>
                            </div>
                        <?php endif; ?>
                    </section>
                <?php endif; ?>
                
                <!-- Mission, Vision, Values - Full Width Section -->
                <?php if (get_field('show_mvv_section')) : ?>
                    </div>
                </div>

                <!-- Full-width Mission, Vision, Values Section with Different Colors -->
                <section class="w-full py-16 bg-gradient-to-r from-gray-50 to-gray-100">
                    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="max-w-6xl mx-auto">
                            <div class="text-center mb-12">
                                <h2 class="text-4xl font-bold text-[var(--primary-color)] mb-4">
                                    <?php echo get_field('mvv_main_title') ?: 'Our Foundation'; ?>
                                </h2>
                                <p class="text-lg text-[var(--text-secondary)] max-w-3xl mx-auto">
                                    <?php echo get_field('mvv_main_subtitle') ?: 'Discover the core principles that guide NIESV Abuja Branch in our commitment to excellence in estate surveying and valuation.'; ?>
                                </p>
                            </div>

                            <div class="grid md:grid-cols-3 gap-8">
                                <!-- Mission -->
                                <?php if (get_field('mission_statement')) : ?>
                                    <?php
                                    $mission_link = get_field('mission_link');
                                    $mission_content = get_field('mission_statement');
                                    ?>
                                    <div class="bg-gradient-to-br from-blue-600 to-blue-800 p-8 rounded-xl shadow-xl text-center text-white transform hover:scale-105 transition-all duration-300">
                                        <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-6">
                                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <h4 class="text-2xl font-bold mb-4"><?php echo get_field('mission_title') ?: 'Our Mission'; ?></h4>
                                        <p class="text-blue-100 leading-relaxed mb-6"><?php echo esc_html($mission_content); ?></p>
                                        <?php if ($mission_link) : ?>
                                            <a href="<?php echo esc_url($mission_link); ?>"
                                               class="inline-flex items-center text-white hover:text-blue-200 font-semibold transition-colors duration-300">
                                                Learn More
                                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                                </svg>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Vision -->
                                <?php if (get_field('vision_statement')) : ?>
                                    <?php
                                    $vision_link = get_field('vision_link');
                                    $vision_content = get_field('vision_statement');
                                    ?>
                                    <div class="bg-gradient-to-br from-emerald-600 to-emerald-800 p-8 rounded-xl shadow-xl text-center text-white transform hover:scale-105 transition-all duration-300">
                                        <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-6">
                                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </div>
                                        <h4 class="text-2xl font-bold mb-4"><?php echo get_field('vision_title') ?: 'Our Vision'; ?></h4>
                                        <p class="text-emerald-100 leading-relaxed mb-6"><?php echo esc_html($vision_content); ?></p>
                                        <?php if ($vision_link) : ?>
                                            <a href="<?php echo esc_url($vision_link); ?>"
                                               class="inline-flex items-center text-white hover:text-emerald-200 font-semibold transition-colors duration-300">
                                                Learn More
                                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                                </svg>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Values -->
                                <?php if (get_field('core_values')) : ?>
                                    <?php
                                    $values_link = get_field('values_link');
                                    $values_content = get_field('core_values');
                                    ?>
                                    <div class="bg-gradient-to-br from-amber-600 to-amber-800 p-8 rounded-xl shadow-xl text-center text-white transform hover:scale-105 transition-all duration-300">
                                        <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-6">
                                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                            </svg>
                                        </div>
                                        <h4 class="text-2xl font-bold mb-4"><?php echo get_field('values_title') ?: 'Our Values'; ?></h4>
                                        <p class="text-amber-100 leading-relaxed mb-6"><?php echo esc_html($values_content); ?></p>
                                        <?php if ($values_link) : ?>
                                            <a href="<?php echo esc_url($values_link); ?>"
                                               class="inline-flex items-center text-white hover:text-amber-200 font-semibold transition-colors duration-300">
                                                Learn More
                                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                                </svg>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Resume container for remaining content -->
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    <div class="max-w-6xl mx-auto">
                <?php endif; ?>
                
                <!-- Main Content -->
                <div class="prose prose-lg max-w-none">
                    <?php the_content(); ?>
                </div>

                    </div>
                </div>
            </div>

        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
