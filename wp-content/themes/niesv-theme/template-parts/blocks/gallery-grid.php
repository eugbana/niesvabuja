<?php
/**
 * Gallery Grid Block Template
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param int|string $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'gallery-grid-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'gallery-grid-block';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $className .= ' align' . $block['align'];
}

// Load values and assign defaults.
$section_title = get_field('section_title') ?: 'Gallery';
$section_subtitle = get_field('section_subtitle') ?: 'Explore our collection of memorable moments';
$number_of_albums = get_field('number_of_albums') ?: 6;
$gallery_layout = get_field('gallery_layout') ?: 'grid-3';
$show_featured_only = get_field('show_featured_only');
$show_view_all_link = get_field('show_view_all_link') !== false;

// Query arguments
$args = array(
    'post_type' => 'gallery',
    'posts_per_page' => $number_of_albums,
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC'
);

// Add featured filter if selected
if ($show_featured_only) {
    $args['meta_query'] = array(
        array(
            'key' => 'is_featured_gallery',
            'value' => '1',
            'compare' => '='
        )
    );
}

$gallery_query = new WP_Query($args);

// Determine grid classes based on layout
$grid_classes = '';
switch ($gallery_layout) {
    case 'grid-2':
        $grid_classes = 'md:grid-cols-2';
        break;
    case 'grid-4':
        $grid_classes = 'md:grid-cols-2 lg:grid-cols-4';
        break;
    case 'grid-3':
    default:
        $grid_classes = 'md:grid-cols-2 lg:grid-cols-3';
        break;
}
?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?> py-16 bg-[var(--background-grey)]">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-extrabold text-[var(--niesv-green)] mb-4 font-serif">
                <?php echo esc_html($section_title); ?>
            </h2>
            <?php if ($section_subtitle) : ?>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    <?php echo esc_html($section_subtitle); ?>
                </p>
            <?php endif; ?>
        </div>
        
        <?php if ($gallery_query->have_posts()) : ?>
            <!-- Gallery Grid -->
            <div class="grid grid-cols-1 <?php echo esc_attr($grid_classes); ?> gap-8">
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
                            <h3 class="font-serif text-xl font-bold text-black">
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
            
            <!-- View All Link -->
            <?php if ($show_view_all_link) : ?>
                <div class="text-center mt-12">
                    <a href="<?php echo get_post_type_archive_link('gallery') ?: home_url('/gallery/'); ?>" 
                       class="inline-flex items-center px-6 py-3 bg-[var(--niesv-green)] text-white rounded-full font-semibold hover:bg-opacity-90 transition-all duration-300 transform hover:scale-105">
                        View All Albums
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>
            <?php endif; ?>
            
        <?php else : ?>
            <!-- No Galleries Found -->
            <div class="text-center py-16">
                <div class="max-w-md mx-auto">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No Gallery Albums Found</h3>
                    <p class="text-gray-600">
                        <?php echo $show_featured_only ? 'No featured gallery albums are available at the moment.' : 'No gallery albums are available at the moment.'; ?>
                    </p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
