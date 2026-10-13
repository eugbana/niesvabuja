<?php
/**
 * Template for gallery archive page
 *
 * @package NIESV_Theme
 */

get_header(); ?>

<?php
    // Get hero section data
    $hero_args = array(
        'title' => 'Image Gallery',
        'subtitle' => 'Explore moments from our past events, conferences, and community gatherings. Select a folder to view the images within.',
        'background_image' => null,
        'overlay_color' => 'rgba(30, 58, 138, 0.8)',
        'size' => 'medium',
        'show_breadcrumb' => true,
    );

    // Include hero section
    get_template_part('template-parts/hero-section', null, $hero_args);

    // Gallery albums are handled by the main query (12 per page set in functions.php)
?>

<main class="flex-1 bg-gray-50">
    <!-- Gallery Albums Grid -->
    <section class="py-20 md:py-28">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <?php if (have_posts()) : ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    <?php while (have_posts()) : the_post(); 
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
                                <h3 class="font-serif text-xl font-bold text-black">
                                    <?php the_title(); ?>
                                </h3>
                                <p class="text-sm text-gray-500 mt-1 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                    </svg>
                                    <?php echo wp_kses_post($image_count); ?> images
                                    <?php if ($gallery_date) : ?>
                                        <span class="mx-2">•</span>
                                        <?php echo date('M j, Y', strtotime($gallery_date)); ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </a>
                    <?php endwhile; ?>
                </div>
                
                <!-- Pagination -->
                <?php niesv_pagination(null, 'Gallery navigation'); ?>
                
            <?php else : ?>
                <div class="text-center py-16">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">No Gallery Albums Found</h3>
                    <p class="text-gray-600">There are no gallery albums available at the moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
