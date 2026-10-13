<?php
/**
 * Template for single news article - Updated to match news-single.html design
 *
 * @package NIESV_Theme
 */

get_header(); ?>

<?php while (have_posts()) : the_post();
    $news_category = get_field('news_category');
    $news_source = get_field('news_source');
    $external_link = get_field('external_link');
    $article_author = get_field('article_author') ?: get_the_author();
    $article_images = get_field('article_images');

    // Get hero section data
    $hero_args = array(
        'title' => get_field('hero_title') ?: get_the_title(),
        'subtitle' => get_field('hero_subtitle') ?: wp_trim_words(get_the_excerpt(), 20),
        'background_image' => get_field('hero_background_image'),
        'overlay_color' => get_field('hero_overlay_color') ? 'rgba(' . implode(',', sscanf(get_field('hero_overlay_color'), "#%02x%02x%02x")) . ', 0.8)' : 'rgba(30, 58, 138, 0.8)',
        'size' => get_field('hero_size') ?: 'small',
        'show_breadcrumb' => get_field('show_hero_breadcrumb') !== false,
    );

    // Include hero section
    get_template_part('template-parts/hero-section', null, $hero_args);
?>

<main class="flex-1 bg-gray-50">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:py-16 lg:px-8">
        <div class="grid grid-cols-1 gap-16 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <div class="mb-8">
                    <!-- Breadcrumb -->
                    <div class="mb-4 text-sm text-gray-500">
                        <a class="hover:underline" href="<?php echo get_post_type_archive_link('news'); ?>">News</a>
                        <span class="mx-2">/</span>
                        <span><?php the_title(); ?></span>
                    </div>

                    <!-- Article Title -->
                    <h1 class="font-newsreader text-4xl font-bold leading-tight tracking-tight text-gray-900 sm:text-5xl lg:text-6xl">
                        <?php the_title(); ?>
                    </h1>
                </div>

                <!-- Featured Image -->
                <?php if (has_post_thumbnail()) :
                    $featured_image_full = get_the_post_thumbnail_url(get_the_ID(), 'full');
                    $featured_image_large = get_the_post_thumbnail_url(get_the_ID(), 'large');
                    $image_title = esc_js(get_the_title());
                ?>
                    <div class="mb-8 h-96 w-full rounded-lg bg-cover bg-center cursor-pointer group transition-all duration-300"
                         style="background-image: url('<?php echo esc_url($featured_image_large); ?>');"
                         onclick="openNewsLightbox('<?php echo esc_js($featured_image_full); ?>', '<?php echo esc_js($image_title); ?>')">
                        <div class="flex items-center justify-center h-full bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 rounded-lg">
                            <svg class="w-12 h-12 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                            </svg>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Article Content -->
                <article class="prose prose-lg max-w-none text-gray-700">
                    <?php the_content(); ?>
                </article>

                <!-- Additional Article Images -->
                <?php if ($article_images) : ?>
                    <div class="my-8 grid grid-cols-2 gap-4">
                        <?php foreach ($article_images as $index => $image) :
                            $image_alt = esc_js($image['alt'] ?: 'Article image');
                            $image_full = isset($image['sizes']['full']) ? $image['sizes']['full'] : $image['url'];
                        ?>
                            <?php if ($index == 0) : ?>
                                <div class="h-48 w-full rounded-lg bg-cover bg-center cursor-pointer transition-all duration-300 relative group"
                                     style="background-image: url('<?php echo esc_url($image['sizes']['medium_large']); ?>');"
                                     onclick="openNewsLightbox('<?php echo esc_js($image_full); ?>', '<?php echo esc_js($image_alt); ?>')">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 rounded-lg flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                        </svg>
                                    </div>
                                </div>
                            <?php elseif ($index == 1) : ?>
                                <div class="h-48 w-full rounded-lg bg-cover bg-center cursor-pointer transition-all duration-300 relative group"
                                     style="background-image: url('<?php echo esc_url($image['sizes']['medium_large']); ?>');"
                                     onclick="openNewsLightbox('<?php echo esc_js($image_full); ?>', '<?php echo esc_js($image_alt); ?>')">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 rounded-lg flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                        </svg>
                                    </div>
                                </div>
                            <?php elseif ($index == 2) : ?>
                                <div class="col-span-2 h-64 w-full rounded-lg bg-cover bg-center cursor-pointer transition-all duration-300 relative group"
                                     style="background-image: url('<?php echo esc_url($image['sizes']['large']); ?>');"
                                     onclick="openNewsLightbox('<?php echo esc_js($image_full); ?>', '<?php echo esc_js($image_alt); ?>')">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 rounded-lg flex items-center justify-center">
                                        <svg class="w-10 h-10 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                        </svg>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Continue Article Content if there are images -->
                <?php if ($article_images) : ?>
                    <article class="wysiwyg-content text-gray-700">
                        <?php
                        $additional_content = get_field('additional_content');
                        if ($additional_content) {
                            // Apply WordPress content filters to process WYSIWYG content
                            echo apply_filters('the_content', $additional_content);
                        }
                        ?>
                    </article>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <aside class="space-y-8 lg:sticky lg:top-28 lg:self-start">
                <!-- Article Details -->
                <div class="rounded-lg border border-gray-200 bg-white p-6">
                    <h3 class="mb-4 font-newsreader text-xl font-semibold text-gray-900">Article Details</h3>
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Author</h4>
                            <p class="mt-1 text-base font-semibold text-gray-800"><?php echo wp_kses_post($article_author); ?></p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Published On</h4>
                            <p class="mt-1 text-base font-semibold text-gray-800"><?php echo get_the_date('F j, Y'); ?></p>
                        </div>
                        <?php if ($news_category) : ?>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Category</h4>
                            <p class="mt-1 text-base font-semibold text-gray-800"><?php echo ucwords(str_replace('_', ' ', $news_category)); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Share Article -->
                <?php niesv_social_share(array('title' => 'Share this article')); ?>
            </aside>
        </div>
    </div>
</main>

<!-- News Image Lightbox Modal -->
<div id="news-lightbox" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center p-4" onclick="closeNewsLightbox()">
    <div class="relative max-w-6xl max-h-full">
        <img id="news-lightbox-image" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
        <button class="absolute top-4 right-4 text-white text-3xl hover:text-gray-300 transition-colors duration-200 bg-black bg-opacity-50 rounded-full w-12 h-12 flex items-center justify-center"
                onclick="closeNewsLightbox()" aria-label="Close lightbox">&times;</button>
        <div id="news-lightbox-caption" class="absolute bottom-4 left-4 right-4 text-white text-center bg-black bg-opacity-50 rounded-lg p-3"></div>
    </div>
</div>

<script>
function openNewsLightbox(imageSrc, imageAlt) {
    console.log('Opening lightbox with image:', imageSrc);

    const lightbox = document.getElementById('news-lightbox');
    const lightboxImage = document.getElementById('news-lightbox-image');
    const lightboxCaption = document.getElementById('news-lightbox-caption');

    if (!lightbox || !lightboxImage || !lightboxCaption) {
        console.error('Lightbox elements not found');
        return;
    }

    lightboxImage.src = imageSrc;
    lightboxImage.alt = imageAlt;
    lightboxCaption.textContent = imageAlt;

    lightbox.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    // Prevent event bubbling when clicking on the image
    lightboxImage.onclick = function(e) {
        e.stopPropagation();
    };
}

function closeNewsLightbox() {
    const lightbox = document.getElementById('news-lightbox');
    lightbox.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close lightbox on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeNewsLightbox();
    }
});

// Prevent scrolling when lightbox is open
document.addEventListener('DOMContentLoaded', function() {
    const lightbox = document.getElementById('news-lightbox');
    lightbox.addEventListener('wheel', function(e) {
        e.preventDefault();
    }, { passive: false });
});
</script>

<?php endwhile; ?>

<?php get_footer(); ?>
