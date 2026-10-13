<?php
/**
 * Universal Hero Section Component
 * 
 * @param array $args {
 *     Optional. Hero section arguments.
 *     @type string $title           Hero title. Default: page title or post title.
 *     @type string $subtitle        Hero subtitle. Default: page excerpt or custom field.
 *     @type string $background_image Background image URL. Default: featured image or custom field.
 *     @type string $overlay_color   Overlay color. Default: 'rgba(30, 58, 138, 0.8)' (NIESV blue).
 *     @type string $text_color      Text color class. Default: 'text-white'.
 *     @type string $size            Hero size. 'small', 'medium', 'large'. Default: 'medium'.
 *     @type bool   $show_breadcrumb Show breadcrumb navigation. Default: true.
 *     @type string $custom_class    Additional CSS classes.
 * }
 */

// Set default arguments
$defaults = array(
    'title' => '',
    'subtitle' => '',
    'background_image' => '',
    'overlay_color' => 'rgba(30, 58, 138, 0.8)',
    'text_color' => 'text-white',
    'size' => 'medium',
    'show_breadcrumb' => true,
    'custom_class' => ''
);

$args = wp_parse_args($args ?? array(), $defaults);

// Auto-detect title if not provided
if (empty($args['title'])) {
    if (is_singular()) {
        $args['title'] = get_the_title();
    } elseif (is_archive()) {
        $args['title'] = get_the_archive_title();
    } elseif (is_search()) {
        $args['title'] = 'Search Results';
    } elseif (is_404()) {
        $args['title'] = 'Page Not Found';
    } else {
        $args['title'] = get_the_title();
    }
}

// Auto-detect subtitle if not provided
if (empty($args['subtitle'])) {
    if (is_singular()) {
        $args['subtitle'] = get_field('hero_subtitle') ?: get_the_excerpt();
    } elseif (is_archive()) {
        $args['subtitle'] = get_the_archive_description();
    } elseif (is_search()) {
        $args['subtitle'] = 'Search results for: ' . get_search_query();
    } elseif (is_404()) {
        $args['subtitle'] = 'The page you are looking for could not be found.';
    }
}

// Auto-detect background image if not provided
if (empty($args['background_image'])) {
    if (is_singular() && has_post_thumbnail()) {
        $args['background_image'] = get_the_post_thumbnail_url(get_the_ID(), 'hero-image');
    } elseif (is_singular()) {
        $args['background_image'] = get_field('hero_background_image');
        if (is_array($args['background_image'])) {
            $args['background_image'] = $args['background_image']['url'];
        }
    }
    
    // Fallback to default hero image
    if (empty($args['background_image'])) {
        $args['background_image'] = 'https://lh3.googleusercontent.com/aida-public/AB6AXuAlG_rWrjyCXhsoqpsoOr08W92LqKJZSMaQXAtN1-ojLiTHnLgnyE2KgUyDOG8VqsIhC94NcPryFYzOEh6KkPjkbD03wJoJfvlONAVwKMIWt_rE_OEWMr2trz4fW5CFBoUn8OLmnki24FIttHyu5PU0m3I-Khc9lnlB5K383L-r7XtboRSgOVca7xuIHTIVpLyPGnmpziMFB3sL47N5vO4SVlR2Nl-muxDaDp6jVUMxn0AOzbpkHafTUBuurG1_790l9_uVdu8eev6n';
    }
}

// Use gallery-style hero section - consistent sizing
$size_classes = 'py-24 md:py-32';
$title_classes = 'text-4xl md:text-6xl';
$subtitle_classes = 'text-lg md:text-xl';

// Build CSS classes
$hero_classes = "hero-section bg-cover bg-center {$size_classes} {$args['custom_class']}";
?>

<section class="<?php echo esc_attr($hero_classes); ?>"
         style="background-image: linear-gradient(<?php echo $args['overlay_color']; ?>, <?php echo $args['overlay_color']; ?>), url('<?php echo esc_url($args['background_image']); ?>');">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="hero-title <?php echo esc_attr($title_classes); ?> font-extrabold tracking-tighter text-white">
            <?php echo esc_html($args['title']); ?>
        </h2>
        <?php if (!empty($args['subtitle'])) : ?>
            <p class="hero-subtitle mt-6 max-w-3xl mx-auto <?php echo esc_attr($subtitle_classes); ?> text-gray-200 leading-relaxed">
                <?php echo esc_html($args['subtitle']); ?>
            </p>
        <?php endif; ?>

        <?php if ($args['show_breadcrumb'] && !is_front_page()) : ?>
            <!-- Breadcrumb -->
            <nav class="mt-8" aria-label="Breadcrumb">
                <ol class="flex items-center justify-center space-x-2 text-sm text-gray-300">
                    <li>
                        <a href="<?php echo home_url(); ?>" class="hover:text-white transition-colors">Home</a>
                    </li>
                    <?php if (is_singular() && !is_page()) : ?>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="<?php echo get_post_type_archive_link(get_post_type()); ?>" class="hover:text-white transition-colors">
                                <?php echo get_post_type_object(get_post_type())->labels->name; ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-400"><?php echo wp_trim_words($args['title'], 5); ?></span>
                    </li>
                </ol>
            </nav>
        <?php endif; ?>
    </div>
</section>
