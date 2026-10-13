<?php
/**
 * The front page template file
 *
 * This template displays the front page with the exact structure from index.html
 * If a static front page is set and has blocks, it will display those instead.
 *
 * @package NIESV_Theme
 */

get_header();

// Check if we have a static front page set with content
$front_page_id = get_option('page_on_front');
$has_blocks = false;

if ($front_page_id && $front_page_id != 0) {
    $front_page_content = get_post_field('post_content', $front_page_id);
    $has_blocks = has_blocks($front_page_content);
}

if ($has_blocks) :
    // Display the page content with blocks
    while (have_posts()) : the_post();
        the_content();
    endwhile;
else :
    // Display the original index.html structure
    include(get_template_directory() . '/index.php');
endif;

get_footer(); ?>
