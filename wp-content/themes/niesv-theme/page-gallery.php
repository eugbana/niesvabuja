<?php
/**
 * Template Name: Gallery Page
 * Template for displaying gallery albums
 *
 * @package NIESV_Theme
 */

get_header(); ?>

<?php while (have_posts()) : the_post(); 
    $page_title = get_field('page_title') ?: 'Image Gallery';
    $page_subtitle = get_field('page_subtitle') ?: 'Explore moments from our past events, conferences, and community gatherings. Select a folder to view the images within.';
    $hero_background = get_field('gallery_hero_background');
    $galleries_per_page = get_field('galleries_per_page') ?: 12;
    
    // Get gallery albums
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $gallery_query = new WP_Query(array(
        'post_type' => 'gallery',
        'posts_per_page' => $galleries_per_page,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
        'paged' => $paged
    ));
?>

<div class="flex flex-col min-h-screen">
    <main class="flex-grow">
        <!-- Hero Section -->
        <section class="py-24 md:py-32 bg-cover bg-center" 
                 style="background-image: linear-gradient(rgba(0, 90, 49, 0.8), rgba(0, 90, 49, 0.8)), url('<?php echo $hero_background ? $hero_background['url'] : 'https://lh3.googleusercontent.com/aida-public/AB6AXuAlG_rWrjyCXhsoqpsoOr08W92LqKJZSMaQXAtN1-ojLiTHnLgnyE2KgUyDOG8VqsIhC94NcPryFYzOEh6KkPjkbD03wJoJfvlONAVwKMIWt_rE_OEWMr2trz4fW5CFBoUn8OLmnki24FIttHyu5PU0m3I-Khc9lnlB5K383L-r7XtboRSgOVca7xuIHTIVpLyPGnmpziMFB3sL47N5vO4SVlR2Nl-muxDaDp6jVUMxn0AOzbpkHafTUBuurG1_790l9_uVdu8eev6n'; ?>');">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-4xl md:text-6xl font-extrabold tracking-tighter text-white font-serif">
                    <?php echo esc_html($page_title); ?>
                </h2>
                <p class="mt-6 max-w-3xl mx-auto text-lg md:text-xl text-gray-200 leading-relaxed">
                    <?php echo esc_html($page_subtitle); ?>
                </p>
            </div>
        </section>
        
        <!-- Gallery Albums Grid -->
        <section class="py-20 md:py-28 bg-[var(--background-grey)]">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <?php if ($gallery_query->have_posts()) : ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                        <?php while ($gallery_query->have_posts()) : $gallery_query->the_post(); 
                            $gallery_images = get_field('gallery_images');
                            $gallery_date = get_field('gallery_date');
                            $image_count = $gallery_images ? count($gallery_images) : 0;
                            $featured_image = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'gallery-thumbnail') : ($gallery_images ? $gallery_images[0]['sizes']['gallery-thumbnail'] : '');
                        ?>
                            <a class="group block bg-white rounded-lg shadow-sm hover:shadow-xl transition-shadow duration-300 overflow-hidden" 
                               href="<?php the_permalink(); ?>">
                                <div class="relative pt-[125%]">
                                    <?php if ($featured_image) : ?>
                                        <img alt="<?php the_title(); ?>" 
                                             class="absolute inset-0 w-full h-full object-cover" 
                                             src="<?php echo esc_url($featured_image); ?>"/>
                                    <?php endif; ?>
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                                </div>
                                <div class="p-5">
                                    <h3 class="font-serif text-xl font-bold text-[var(--niesv-green)] group-hover:text-[var(--niesv-gold)] transition-colors duration-300">
                                        <?php the_title(); ?>
                                    </h3>
                                    <p class="text-sm text-gray-500 mt-1 flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                            <path d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                        </svg>
                                        <?php echo wp_kses_post($image_count); ?> Photos
                                    </p>
                                    <?php if ($gallery_date) : ?>
                                        <p class="text-xs text-gray-400 mt-1">
                                            <?php echo date('F j, Y', strtotime($gallery_date)); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </a>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($gallery_query->max_num_pages > 1) : ?>
                        <div class="mt-12 flex justify-center">
                            <?php
                            echo paginate_links(array(
                                'total' => $gallery_query->max_num_pages,
                                'current' => $paged,
                                'format' => '?paged=%#%',
                                'show_all' => false,
                                'end_size' => 1,
                                'mid_size' => 2,
                                'prev_next' => true,
                                'prev_text' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg"><path d="M165.66,202.34a8,8,0,0,1-11.32,11.32l-80-80a8,8,0,0,1,0-11.32l80-80a8,8,0,0,1,11.32,11.32L91.31,128Z"></path></svg>',
                                'next_text' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg"><path d="M181.66,133.66l-80,80a8,8,0,0,1-11.32-11.32L164.69,128,90.34,53.66a8,8,0,0,1,11.32-11.32l80,80A8,8,0,0,1,181.66,133.66Z"></path></svg>',
                                'type' => 'array'
                            ));
                            ?>
                        </div>
                    <?php endif; ?>
                    
                <?php else : ?>
                    <div class="text-center py-16">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">No Gallery Albums Found</h3>
                        <p class="text-gray-600">There are currently no gallery albums to display. Please check back later.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>
</div>

<?php endwhile; ?>

<?php get_footer(); ?>
