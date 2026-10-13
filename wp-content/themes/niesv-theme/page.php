<?php
/**
 * The template for displaying all pages
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

<main class="flex-1">
    <?php while (have_posts()) : the_post(); ?>

        <?php
        // Debug: Check if this page has blocks
        $content = get_the_content();
        if (has_blocks($content)) {
            echo '<!-- Page has blocks -->';
            // Just render the content with blocks
            the_content();
        } else {
            echo '<!-- Page has no blocks, showing traditional content -->';
            ?>
            <!-- Page Content -->
            <div class="py-12">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="max-w-4xl mx-auto">
                        
                        <!-- Page Content -->
                        <div class="prose prose-lg max-w-none">
                            <?php the_content(); ?>
                        </div>

                        <!-- Social Share -->
                        <div class="mt-12">
                            <?php niesv_social_share(array('title' => 'Share this page')); ?>
                        </div>

                    </div>
                </div>
            </div>
            <?php
        }
        ?>
        
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
