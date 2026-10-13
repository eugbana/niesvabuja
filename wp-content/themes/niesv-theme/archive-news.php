<?php
/**
 * Template for News Archive
 *
 * @package NIESV_Theme
 */

get_header(); ?>

<?php
// Get hero section data
$hero_args = array(
    'title' => get_field('news_page_title', 'option') ?: 'Latest News',
    'subtitle' => get_field('news_page_subtitle', 'option') ?: 'Stay informed with the latest updates, announcements, and insights from NIESV Abuja Branch.',
    'background_image' => get_field('news_hero_background', 'option'),
    'overlay_color' => 'rgba(30, 58, 138, 0.8)',
    'size' => 'medium',
    'show_breadcrumb' => true,
);

// Include hero section
get_template_part('template-parts/hero-section', null, $hero_args);
?>

<main class="flex-1">
    
    <!-- News Content -->
    <section class="py-12 md:py-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            
            <?php if (have_posts()) : ?>
                
                <!-- Featured News Grid (Up to 3 Posts) -->
                <?php if (is_paged() === false) : ?>
                    <?php
                    // Get featured news posts separately
                    $featured_query = new WP_Query(array(
                        'post_type' => 'news',
                        'posts_per_page' => 3,
                        'post_status' => 'publish',
                        'meta_query' => array(
                            array(
                                'key' => 'is_featured_news',
                                'value' => '1',
                                'compare' => '='
                            )
                        ),
                        'orderby' => 'date',
                        'order' => 'DESC'
                    ));

                    if ($featured_query->have_posts()) : ?>
                        <!-- Featured News Section Header -->
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-[var(--primary-color)] mb-2">Featured News</h2>
                            <div class="w-16 h-1 bg-[var(--accent-gold)]"></div>
                        </div>

                        <!-- Featured News Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                            <?php while ($featured_query->have_posts()) : $featured_query->the_post();
                                $listing_image = get_field('news_listing_image');
                                $image_url = '';

                                // Use listing image first, then featured image as fallback
                                if ($listing_image) {
                                    $image_url = $listing_image['sizes']['medium_large'] ?? $listing_image['url'];
                                } elseif (has_post_thumbnail()) {
                                    $image_url = get_the_post_thumbnail_url(get_the_ID(), 'medium_large');
                                }
                            ?>
                                <article class="bg-white rounded-xl shadow-lg overflow-hidden group transform hover:-translate-y-2 transition-transform duration-300 border-2 border-[var(--accent-gold)]">
                                    <div class="p-6">
                                        <!-- Post Meta -->
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="text-sm text-[var(--text-secondary)]">
                                                <?php echo get_the_date('M j, Y'); ?>
                                            </span>
                                            <span class="bg-[var(--accent-gold)] text-[var(--primary-color)] px-3 py-1 rounded-full text-xs font-semibold">
                                                Featured
                                            </span>
                                        </div>

                                        <!-- Listing Image -->
                                        <?php if ($image_url) : ?>
                                            <div class="w-full h-48 bg-cover bg-center group-hover:scale-105 transition-transform duration-300 rounded-lg mb-4 overflow-hidden"
                                                 style="background-image: url('<?php echo esc_url($image_url); ?>')">
                                            </div>
                                        <?php endif; ?>

                                        <!-- Title -->
                                        <h3 class="text-xl font-bold mb-3 text-black">
                                            <a href="<?php the_permalink(); ?>" class="text-black hover:text-black"><?php the_title(); ?></a>
                                        </h3>

                                        <!-- Excerpt -->
                                        <p class="text-[var(--text-secondary)] mb-4 text-sm leading-relaxed">
                                            <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
                                        </p>

                                        <!-- Read More Link -->
                                        <div class="flex items-center justify-between">
                                            <a href="<?php the_permalink(); ?>"
                                               class="font-semibold text-[var(--primary-color)] hover:text-[var(--accent-gold)] transition-colors text-sm">
                                                Read More →
                                            </a>

                                            <!-- Author -->
                                            <div class="flex items-center text-xs text-[var(--text-secondary)]">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                <?php the_author(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                        </div>
                        <?php
                        wp_reset_postdata();
                    endif;
                    ?>
                <?php endif; ?>
                
                <!-- All News Section Header -->
                <?php if (have_posts()) : ?>
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-[var(--primary-color)] mb-2">All News</h2>
                        <div class="w-16 h-1 bg-[var(--secondary-color)]"></div>
                    </div>
                <?php endif; ?>

                <!-- News Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php while (have_posts()) : the_post(); ?>
                        <article class="bg-white rounded-xl shadow-lg overflow-hidden group transform hover:-translate-y-2 transition-transform duration-300">
                            <div class="p-6">
                                <!-- Post Meta -->
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-sm text-[var(--text-secondary)]">
                                        <?php echo get_the_date('M j, Y'); ?>
                                    </span>
                                    <?php if (get_field('news_category')) : ?>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-[var(--secondary-color)] text-[var(--primary-color)]">
                                            <?php echo wp_kses_post(get_field('news_category')); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <!-- Listing Image -->
                                <?php
                                $listing_image = get_field('news_listing_image');
                                $image_url = '';

                                // Use listing image first, then featured image as fallback
                                if ($listing_image) {
                                    $image_url = $listing_image['sizes']['medium_large'] ?? $listing_image['url'];
                                } elseif (has_post_thumbnail()) {
                                    $image_url = get_the_post_thumbnail_url(get_the_ID(), 'medium_large');
                                }

                                if ($image_url) : ?>
                                    <div class="w-full h-48 bg-cover bg-center group-hover:scale-105 transition-transform duration-300 rounded-lg mb-4 overflow-hidden"
                                         style="background-image: url('<?php echo esc_url($image_url); ?>')">
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Title -->
                                <h3 class="text-xl font-bold mb-3 text-black">
                                    <a href="<?php the_permalink(); ?>" class="text-black hover:text-black"><?php the_title(); ?></a>
                                </h3>
                                
                                <!-- Excerpt -->
                                <p class="text-[var(--text-secondary)] mb-4 text-sm leading-relaxed">
                                    <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
                                </p>
                                
                                <!-- Read More Link -->
                                <div class="flex items-center justify-between">
                                    <a href="<?php the_permalink(); ?>" 
                                       class="font-semibold text-[var(--primary-color)] hover:text-[var(--accent-gold)] transition-colors text-sm">
                                        Read More →
                                    </a>
                                    
                                    <!-- Author -->
                                    <div class="flex items-center text-xs text-[var(--text-secondary)]">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <?php the_author(); ?>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
                
                <!-- Pagination -->
                <?php niesv_pagination(null, 'News navigation'); ?>
                
            <?php else : ?>
                <!-- No News Found -->
                <div class="text-center py-16">
                    <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                    <h3 class="text-2xl font-semibold text-gray-900 mb-2">No News Articles Found</h3>
                    <p class="text-gray-600 mb-6">There are currently no news articles available. Please check back later for updates.</p>
                    <a href="<?php echo home_url(); ?>" class="btn-primary">
                        Return to Homepage
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>
    
    <!-- Newsletter Signup Section -->
    <?php if (get_field('show_newsletter_section', 'option')) : ?>
        <section class="py-16 bg-[var(--primary-color)]">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-bold text-white mb-4">
                    <?php echo wp_kses_post(get_field('newsletter_title', 'option') ?: 'Stay Updated'); ?>
                </h2>
                <p class="text-gray-300 mb-8 max-w-2xl mx-auto">
                    <?php echo wp_kses_post(get_field('newsletter_subtitle', 'option') ?: 'Subscribe to our newsletter to receive the latest news and updates directly in your inbox.'); ?>
                </p>
                <form class="max-w-md mx-auto flex gap-4" action="#" method="post">
                    <input type="email" placeholder="Enter your email" 
                           class="flex-1 px-4 py-3 rounded-md text-gray-900 focus:outline-none focus:ring-2 focus:ring-[var(--accent-gold)]" 
                           required>
                    <button type="submit" 
                            class="px-6 py-3 bg-[var(--accent-gold)] text-[var(--primary-color)] rounded-md font-semibold hover:bg-opacity-90 transition-all">
                        Subscribe
                    </button>
                </form>
            </div>
        </section>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
