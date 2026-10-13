<?php
/**
 * Template for single gallery album
 *
 * @package NIESV_Theme
 */

get_header(); ?>

<?php
// Debug: Log that this template is being loaded
error_log('NIESV: single-gallery.php template loaded for post ID: ' . get_the_ID());
?>

<?php while (have_posts()) : the_post();
    $gallery_images = get_field('gallery_images');
    $gallery_date = get_field('gallery_date');
    $gallery_location = get_field('gallery_location');
    $gallery_photographer = get_field('gallery_photographer');

    // Pagination for images using URL parameters
    $images_per_page = 12;

    // Use URL parameter for pagination (more reliable)
    $paged = isset($_GET['gallery_page']) ? max(1, intval($_GET['gallery_page'])) : 1;

    // Debug: Show current page (remove this after testing)
    if (current_user_can('administrator')) {
        echo "<!-- Debug: Current page = $paged, GET parameter = " . (isset($_GET['gallery_page']) ? $_GET['gallery_page'] : 'not set') . " -->";
    }

    $total_images = $gallery_images ? count($gallery_images) : 0;
    $offset = ($paged - 1) * $images_per_page;
    $current_images = $gallery_images ? array_slice($gallery_images, $offset, $images_per_page) : array();
    $max_pages = ceil($total_images / $images_per_page);

    // Build subtitle with date and location
    $subtitle_parts = array();
    if ($gallery_date) {
        $subtitle_parts[] = date('F j, Y', strtotime($gallery_date));
    }
    if ($gallery_location) {
        $subtitle_parts[] = $gallery_location;
    }
    $subtitle = implode(' • ', $subtitle_parts);

    // Get hero section data
    $hero_args = array(
        'title' => get_field('hero_title') ?: get_the_title(),
        'subtitle' => get_field('hero_subtitle') ?: $subtitle,
        'background_image' => get_field('hero_background_image'),
        'overlay_color' => get_field('hero_overlay_color') ? 'rgba(' . implode(',', sscanf(get_field('hero_overlay_color'), "#%02x%02x%02x")) . ', 0.8)' : 'rgba(30, 58, 138, 0.8)',
        'size' => get_field('hero_size') ?: 'small',
        'show_breadcrumb' => get_field('show_hero_breadcrumb') !== false,
    );

    // Include hero section
    get_template_part('template-parts/hero-section', null, $hero_args);
?>

<main class="flex-1 bg-gray-50">
    <div class="container mx-auto px-6 py-12">

        <!-- Back to Gallery Link -->
        <div class="mb-8">
            <a class="text-[var(--niesv-green)] hover:underline flex items-center gap-2" href="<?php echo get_post_type_archive_link('gallery') ?: home_url('/gallery/'); ?>">
                <svg fill="currentColor" height="20" viewBox="0 0 256 256" width="20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M165.66,202.34a8,8,0,0,1-11.32,11.32l-80-80a8,8,0,0,1,0-11.32l80-80a8,8,0,0,1,11.32,11.32L91.31,128Z"></path>
                </svg>
                <span>Back to All Albums</span>
            </a>
        </div>

        <!-- Gallery Title and Info -->
        <div class="mb-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold font-serif text-[var(--text-dark)] mb-4">
                <?php the_title(); ?>
            </h1>
            <?php if ($subtitle) : ?>
                <p class="text-lg text-gray-600"><?php echo esc_html($subtitle); ?></p>
            <?php endif; ?>
        </div>

        <!-- Gallery Description -->
        <?php if (get_the_content()) : ?>
            <div class="mb-8 prose prose-lg max-w-none">
                <?php the_content(); ?>
            </div>
        <?php endif; ?>

        <!-- Gallery Images Grid -->
        <?php if ($current_images) : ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
                <?php foreach ($current_images as $image) : ?>
                    <div class="group relative overflow-hidden rounded-lg shadow-lg cursor-pointer">
                        <?php
                        // Handle both ACF gallery format and regular image arrays
                        $image_url = '';
                        $image_large = '';
                        $image_alt = '';

                        if (is_array($image)) {
                            if (isset($image['sizes'])) {
                                // ACF gallery format
                                $image_url = $image['sizes']['gallery-detail'] ?? $image['sizes']['medium'] ?? $image['url'];
                                $image_large = $image['sizes']['large'] ?? $image['url'];
                                $image_alt = $image['alt'] ?? '';
                            } else {
                                // Simple array format
                                $image_url = $image['url'] ?? $image;
                                $image_large = $image['url'] ?? $image;
                                $image_alt = $image['alt'] ?? '';
                            }
                        } else {
                            // String URL
                            $image_url = $image_large = $image;
                            $image_alt = 'Gallery Image';
                        }
                        ?>
                        <img alt="<?php echo esc_attr($image_alt); ?>"
                             class="w-full h-64 object-cover transform group-hover:scale-110 transition-transform duration-500 ease-in-out"
                             src="<?php echo esc_url($image_url); ?>"
                             data-full-size="<?php echo esc_url($image_large); ?>"
                             onclick="openLightbox('<?php echo esc_url($image_large); ?>', '<?php echo esc_attr($image_alt); ?>')"/>
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-500 flex items-center justify-center">
                            <button aria-label="View Image"
                                    class="text-white opacity-0 group-hover:opacity-100 transform scale-75 group-hover:scale-100 transition-all duration-300"
                                    onclick="openLightbox('<?php echo esc_url($image_large); ?>', '<?php echo esc_attr($image_alt); ?>')">
                                <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"></path>
                                    <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($max_pages > 1) : ?>
                <div class="mt-12 flex justify-center">
                    <?php
                    // Use URL parameters for pagination (more reliable)
                    $pagination_args = array(
                        'base' => add_query_arg('gallery_page', '%#%'),
                        'format' => '',
                        'current' => $paged,
                        'total' => $max_pages,
                        'show_all' => false,
                        'end_size' => 1,
                        'mid_size' => 2,
                        'prev_next' => true,
                        'prev_text' => '← Previous',
                        'next_text' => 'Next →',
                        'type' => 'plain',
                        'add_args' => false,
                    );

                    $pagination_links = paginate_links($pagination_args);

                    if ($pagination_links) {
                        echo '<nav class="navigation pagination" role="navigation" aria-label="Gallery images navigation">';
                        echo '<div class="nav-links">' . $pagination_links . '</div>';
                        echo '</nav>';
                    }
                    ?>
                </div>
            <?php endif; ?>
            
        <?php else : ?>
            <div class="text-center py-16">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">No Images Found</h3>
                <p class="text-gray-600 mb-4">This gallery album doesn't contain any images yet.</p>

                <?php if (current_user_can('edit_posts')) : ?>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 max-w-md mx-auto">
                        <p class="text-sm text-blue-800 mb-2"><strong>For Administrators:</strong></p>
                        <p class="text-sm text-blue-700">To add images to this gallery, edit this post and add images to the "Gallery Images" field using Advanced Custom Fields.</p>
                        <a href="<?php echo get_edit_post_link(); ?>" class="inline-block mt-2 px-4 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">
                            Edit Gallery
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Gallery Info -->
        <?php if ($gallery_photographer) : ?>
            <div class="mt-12 pt-8 border-t border-gray-200">
                <p class="text-sm text-gray-600">
                    <strong>Photographer:</strong> <?php echo esc_html($gallery_photographer); ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
</main>

<!-- Lightbox Modal -->
<div id="lightbox" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center" onclick="closeLightbox()">
    <div class="relative max-w-4xl max-h-full p-4">
        <img id="lightbox-image" src="" alt="" class="max-w-full max-h-full object-contain">
        <button class="absolute top-4 right-4 text-white text-2xl" onclick="closeLightbox()">&times;</button>
    </div>
</div>

<script>
function openLightbox(imageSrc, imageAlt) {
    document.getElementById('lightbox-image').src = imageSrc;
    document.getElementById('lightbox-image').alt = imageAlt;
    document.getElementById('lightbox').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    document.getElementById('lightbox').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close lightbox on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeLightbox();
    }
});
</script>

<?php endwhile; ?>

<?php get_footer(); ?>
