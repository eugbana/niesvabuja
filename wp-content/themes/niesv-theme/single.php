<?php
/**
 * The template for displaying all single posts
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
    <div class="py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-12">
                
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <?php while (have_posts()) : the_post(); ?>
                        
                        <article class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                            
                            <!-- Featured Image -->
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="aspect-video overflow-hidden">
                                    <?php the_post_thumbnail('large', array('class' => 'w-full h-full object-cover')); ?>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Article Content -->
                            <div class="p-8">
                                
                                <!-- Article Meta -->
                                <div class="flex items-center gap-4 text-sm text-gray-600 mb-6">
                                    <time datetime="<?php echo get_the_date('c'); ?>" class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <?php echo get_the_date(); ?>
                                    </time>
                                    
                                    <span class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <?php the_author(); ?>
                                    </span>
                                    
                                    <?php if (has_category()) : ?>
                                        <span class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                            </svg>
                                            <?php the_category(', '); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Article Content -->
                                <div class="prose prose-lg max-w-none">
                                    <?php the_content(); ?>
                                </div>
                                
                                <!-- Tags -->
                                <?php if (has_tag()) : ?>
                                    <div class="mt-8 pt-6 border-t border-gray-200">
                                        <div class="flex flex-wrap gap-2">
                                            <?php
                                            $tags = get_the_tags();
                                            foreach ($tags as $tag) :
                                            ?>
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    <?php echo esc_html($tag->name); ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                            </div>
                        </article>
                        
                        <!-- Post Navigation -->
                        <div class="mt-8 flex justify-between items-center">
                            <div class="flex-1">
                                <?php
                                $prev_post = get_previous_post();
                                if ($prev_post) :
                                ?>
                                    <a href="<?php echo get_permalink($prev_post); ?>" class="group flex items-center gap-3 text-sm text-gray-600 hover:text-[var(--primary-color)] transition-colors">
                                        <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                        <div>
                                            <div class="text-xs uppercase tracking-wide">Previous</div>
                                            <div class="font-medium"><?php echo wp_trim_words($prev_post->post_title, 6); ?></div>
                                        </div>
                                    </a>
                                <?php endif; ?>
                            </div>
                            
                            <div class="flex-1 text-right">
                                <?php
                                $next_post = get_next_post();
                                if ($next_post) :
                                ?>
                                    <a href="<?php echo get_permalink($next_post); ?>" class="group flex items-center justify-end gap-3 text-sm text-gray-600 hover:text-[var(--primary-color)] transition-colors">
                                        <div class="text-right">
                                            <div class="text-xs uppercase tracking-wide">Next</div>
                                            <div class="font-medium"><?php echo wp_trim_words($next_post->post_title, 6); ?></div>
                                        </div>
                                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                    <?php endwhile; ?>
                </div>
                
                <!-- Sidebar -->
                <aside class="lg:col-span-1 space-y-8">
                    
                    <!-- Share Article -->
                    <?php niesv_social_share(array('title' => 'Share this post')); ?>
                    
                    <!-- Recent Posts -->
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Recent Posts</h3>
                        <?php
                        $recent_posts = new WP_Query(array(
                            'posts_per_page' => 5,
                            'post__not_in' => array(get_the_ID()),
                            'post_status' => 'publish'
                        ));
                        
                        if ($recent_posts->have_posts()) :
                        ?>
                            <div class="space-y-4">
                                <?php while ($recent_posts->have_posts()) : $recent_posts->the_post(); ?>
                                    <article class="group">
                                        <a href="<?php the_permalink(); ?>" class="block">
                                            <h4 class="text-sm font-medium text-gray-900 group-hover:text-[var(--primary-color)] transition-colors line-clamp-2">
                                                <?php the_title(); ?>
                                            </h4>
                                            <time class="text-xs text-gray-500 mt-1 block">
                                                <?php echo get_the_date(); ?>
                                            </time>
                                        </a>
                                    </article>
                                <?php endwhile; ?>
                            </div>
                        <?php
                        endif;
                        wp_reset_postdata();
                        ?>
                    </div>
                    
                    <!-- Categories -->
                    <?php
                    $categories = get_categories(array('hide_empty' => true));
                    if ($categories) :
                    ?>
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Categories</h3>
                            <div class="space-y-2">
                                <?php foreach ($categories as $category) : ?>
                                    <a href="<?php echo get_category_link($category); ?>" 
                                       class="flex items-center justify-between text-sm text-gray-600 hover:text-[var(--primary-color)] transition-colors">
                                        <span><?php echo esc_html($category->name); ?></span>
                                        <span class="text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded-full">
                                            <?php echo $category->count; ?>
                                        </span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                </aside>
                
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>
