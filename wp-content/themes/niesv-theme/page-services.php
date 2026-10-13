<?php
/**
 * Template Name: Services Page
 * Template for Services page
 *
 * @package NIESV_Theme
 */

get_header(); ?>

<main class="container mx-auto flex-grow px-4 sm:px-6 lg:px-8 py-12 md:py-20">
    <div class="max-w-6xl mx-auto">
        <?php while (have_posts()) : the_post(); ?>
            
            <!-- Page Header -->
            <div class="text-center mb-16">
                <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-[var(--primary-color)]">
                    <?php echo get_field('page_title') ?: get_the_title(); ?>
                </h1>
                <?php if (get_field('page_subtitle')) : ?>
                    <p class="mt-4 text-lg text-[var(--text-secondary)] max-w-3xl mx-auto">
                        <?php echo get_field('page_subtitle'); ?>
                    </p>
                <?php endif; ?>
            </div>
            
            <!-- Services Grid -->
            <?php if (have_rows('services_list')) : ?>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                    <?php while (have_rows('services_list')) : the_row(); ?>
                        <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-shadow duration-300">
                            <?php if (get_sub_field('service_icon')) : ?>
                                <div class="w-16 h-16 bg-[var(--primary-color)] rounded-full flex items-center justify-center mb-6">
                                    <img src="<?php echo get_sub_field('service_icon')['url']; ?>" alt="<?php echo get_sub_field('service_title'); ?>" class="w-8 h-8">
                                </div>
                            <?php else : ?>
                                <div class="w-16 h-16 bg-[var(--primary-color)] rounded-full flex items-center justify-center mb-6">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            <?php endif; ?>
                            
                            <h3 class="text-xl font-bold text-[var(--primary-color)] mb-4">
                                <?php echo get_sub_field('service_title'); ?>
                            </h3>
                            
                            <p class="text-[var(--text-secondary)] mb-6 leading-relaxed">
                                <?php echo get_sub_field('service_description'); ?>
                            </p>
                            
                            <?php if (get_sub_field('service_features')) : ?>
                                <ul class="space-y-2">
                                    <?php foreach (get_sub_field('service_features') as $feature) : ?>
                                        <li class="flex items-center text-sm text-[var(--text-secondary)]">
                                            <svg class="w-4 h-4 text-[var(--accent-gold)] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <?php echo $feature['feature']; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
            
            <!-- Why Choose Us Section -->
            <?php if (get_field('show_why_choose_section')) : ?>
                <div class="bg-gray-50 rounded-2xl p-8 md:p-12 mb-16">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl font-bold text-[var(--primary-color)] mb-4">
                            <?php echo get_field('why_choose_title') ?: 'Why Choose NIESV Abuja?'; ?>
                        </h2>
                        <?php if (get_field('why_choose_subtitle')) : ?>
                            <p class="text-lg text-[var(--text-secondary)]">
                                <?php echo get_field('why_choose_subtitle'); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (have_rows('why_choose_points')) : ?>
                        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                            <?php while (have_rows('why_choose_points')) : the_row(); ?>
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-[var(--accent-gold)] rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-6 h-6 text-[var(--primary-color)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-[var(--primary-color)] mb-2">
                                        <?php echo get_sub_field('point_title'); ?>
                                    </h4>
                                    <p class="text-[var(--text-secondary)] text-sm">
                                        <?php echo get_sub_field('point_description'); ?>
                                    </p>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <!-- Call to Action -->
            <?php if (get_field('show_cta_section')) : ?>
                <div class="bg-[var(--primary-color)] rounded-2xl p-8 md:p-12 text-center text-white">
                    <h2 class="text-3xl font-bold mb-4">
                        <?php echo get_field('cta_title') ?: 'Ready to Get Started?'; ?>
                    </h2>
                    <?php if (get_field('cta_description')) : ?>
                        <p class="text-lg mb-8 opacity-90">
                            <?php echo get_field('cta_description'); ?>
                        </p>
                    <?php endif; ?>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <?php if (get_field('cta_primary_button_text')) : ?>
                            <a href="<?php echo get_field('cta_primary_button_link') ?: '#'; ?>" 
                               class="bg-[var(--accent-gold)] text-[var(--primary-color)] px-8 py-3 rounded-lg font-semibold hover:bg-opacity-90 transition-all duration-300">
                                <?php echo get_field('cta_primary_button_text'); ?>
                            </a>
                        <?php endif; ?>
                        
                        <?php if (get_field('cta_secondary_button_text')) : ?>
                            <a href="<?php echo get_field('cta_secondary_button_link') ?: '#'; ?>" 
                               class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-[var(--primary-color)] transition-all duration-300">
                                <?php echo get_field('cta_secondary_button_text'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
