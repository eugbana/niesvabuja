<?php
/**
 * News Cards Block Template
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param int|string $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'news-cards-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'news-cards-block';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}

// Load values and assign defaults.
$section_title = get_field('section_title') ?: 'Latest News';
$number_of_posts = get_field('number_of_posts') ?: 3;
$show_excerpt = get_field('show_excerpt') !== false;
$show_author = get_field('show_author') !== false;
$show_date = get_field('show_date') !== false;
$news_category = get_field('news_category_filter');
$show_featured_only = get_field('show_featured_only');

// Limit featured news to 3
if ($show_featured_only) {
    $number_of_posts = min($number_of_posts, 3);
}

// Query arguments
$args = array(
    'post_type' => 'news',
    'posts_per_page' => $number_of_posts,
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC'
);

// Build meta query array
$meta_query = array();

// Add category filter if selected
if ($news_category) {
    $meta_query[] = array(
        'key' => 'news_category',
        'value' => $news_category,
        'compare' => '='
    );
}

// Add featured filter if selected
if ($show_featured_only) {
    $meta_query[] = array(
        'key' => 'is_featured_news',
        'value' => '1',
        'compare' => '='
    );
}

// Apply meta query if we have conditions
if (!empty($meta_query)) {
    if (count($meta_query) > 1) {
        $meta_query['relation'] = 'AND';
    }
    $args['meta_query'] = $meta_query;
}

$news_query = new WP_Query($args);
?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?> py-12 md:py-20">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Title -->
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold tracking-tight mb-2 text-[rgba(0,0,40,1)] border-b-2 border-[#d4af37] inline-block pb-2">
                <?php echo esc_html($section_title); ?>
            </h2>
        </div>
        
        <?php if ($news_query->have_posts()) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-<?php echo min($number_of_posts, 3); ?> gap-8">
                <?php while ($news_query->have_posts()) : $news_query->the_post();
                    $news_category_meta = get_field('news_category');
                    $listing_image = get_field('news_listing_image');
                    $image_url = '';

                    // Use listing image first, then featured image as fallback
                    if ($listing_image) {
                        $image_url = $listing_image['sizes']['news-thumbnail'] ?? $listing_image['url'];
                    } elseif (has_post_thumbnail()) {
                        $image_url = get_the_post_thumbnail_url(get_the_ID(), 'news-thumbnail');
                    }
                ?>
                    <article class="bg-white rounded-xl shadow-lg overflow-hidden group transform hover:-translate-y-2 transition-transform duration-300">

                        <?php if ($image_url) : ?>
                            <div class="w-full h-48 bg-cover bg-center group-hover:scale-105 transition-transform duration-300"
                                 style="background-image: url('<?php echo esc_url($image_url); ?>')">
                            </div>
                        <?php endif; ?>
                        
                        <div class="p-6">
                            <!-- Post Meta -->
                            <div class="flex items-center justify-between mb-3">
                                <?php if ($show_date) : ?>
                                    <span class="text-sm text-[#49739c]">
                                        <?php echo get_the_date('M j, Y'); ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if ($news_category_meta) : ?>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-[#e7edf4] text-[rgba(0,0,40,1)]">
                                        <?php echo ucwords(str_replace('_', ' ', $news_category_meta)); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Title -->
                            <h3 class="text-xl font-bold mb-3 text-black">
                                <a href="<?php the_permalink(); ?>" class="text-black hover:text-black"><?php the_title(); ?></a>
                            </h3>
                            
                            <!-- Excerpt -->
                            <?php if ($show_excerpt) : ?>
                                <p class="text-[#49739c] mb-4 text-sm leading-relaxed">
                                    <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
                                </p>
                            <?php endif; ?>
                            
                            <!-- Read More Link and Author -->
                            <div class="flex items-center justify-between">
                                <a href="<?php the_permalink(); ?>" 
                                   class="font-semibold text-[rgba(0,0,40,1)] hover:text-[#d4af37] transition-colors text-sm">
                                    Read More →
                                </a>
                                
                                <?php if ($show_author) : ?>
                                    <div class="flex items-center text-xs text-[#49739c]">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <?php the_author(); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            
            <!-- View All Link -->
            <div class="text-center mt-12">
                <a href="<?php echo get_post_type_archive_link('news'); ?>" 
                   class="inline-flex items-center px-6 py-3 bg-[rgba(0,0,40,1)] text-white rounded-lg font-semibold hover:bg-opacity-90 transition-all">
                    View All News
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
            
        <?php else : ?>
            <!-- No News Found -->
            <div class="text-center py-12">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No News Articles Found</h3>
                <p class="text-gray-600">There are currently no news articles to display.</p>
            </div>
        <?php endif; ?>
        
        <?php wp_reset_postdata(); ?>
    </div>
</section>

<?php if ($is_preview) : ?>
    <style>
        .news-cards-block {
            background: #f8fafc;
            border: 2px dashed #d4af37;
            padding: 2rem;
            position: relative;
        }
        .news-cards-block::before {
            content: 'News Cards Block - Configure in sidebar';
            position: absolute;
            top: -10px;
            left: 10px;
            background: #d4af37;
            color: rgba(0,0,40,1);
            padding: 5px 10px;
            font-size: 12px;
            font-weight: bold;
            border-radius: 4px;
            z-index: 10;
        }
    </style>

    <!-- Debug info -->
    <div style="background: #f0f0f0; padding: 10px; margin: 10px 0; border-radius: 4px; font-size: 12px;">
        <strong>Debug Info:</strong><br>
        Section Title: <?php echo wp_kses_post($section_title); ?><br>
        Number of Posts: <?php echo wp_kses_post($number_of_posts); ?><br>
        News Found: <?php echo $news_query->found_posts; ?> posts<br>
        Show Excerpt: <?php echo $show_excerpt ? 'Yes' : 'No'; ?><br>
        Show Author: <?php echo $show_author ? 'Yes' : 'No'; ?><br>
        Show Date: <?php echo $show_date ? 'Yes' : 'No'; ?>
    </div>
<?php endif; ?>
