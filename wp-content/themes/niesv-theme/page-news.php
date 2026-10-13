<?php
/**
 * Template Name: News Page
 * Template for News/Blog page
 *
 * @package NIESV_Theme
 */

get_header(); ?>

<main class="container mx-auto flex-grow px-4 sm:px-6 lg:px-8 py-12 md:py-20">
    <div class="max-w-6xl mx-auto">
        <?php while (have_posts()) : the_post(); ?>
            
            <!-- Page Header -->
            <div class="text-center mb-16">
                <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-[var(--primary-color)]">
                    <?php echo wp_kses_post(get_field('page_title') ?: get_the_title()); ?>
                </h1>
                <?php if (get_field('page_subtitle')) : ?>
                    <p class="mt-4 text-lg text-[var(--text-secondary)] max-w-3xl mx-auto">
                        <?php echo wp_kses_post(get_field('page_subtitle')); ?>
                    </p>
                <?php endif; ?>
            </div>
            
            <!-- Featured Post -->
            <?php if (get_field('show_featured_post') && get_field('featured_post')) : 
                $featured_post = get_field('featured_post');
            ?>
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-16">
                    <div class="grid md:grid-cols-2 gap-0">
                        <?php if (has_post_thumbnail($featured_post->ID)) : ?>
                            <div class="h-64 md:h-auto">
                                <img src="<?php echo get_the_post_thumbnail_url($featured_post->ID, 'large'); ?>" 
                                     alt="<?php echo $featured_post->post_title; ?>" 
                                     class="w-full h-full object-cover">
                            </div>
                        <?php endif; ?>
                        
                        <div class="p-8 md:p-12 flex flex-col justify-center">
                            <div class="inline-block bg-[var(--accent-gold)] text-[var(--primary-color)] px-4 py-2 rounded-full text-sm font-semibold mb-4 w-fit">
                                Featured Article
                            </div>
                            <h2 class="text-2xl md:text-3xl font-bold text-[var(--primary-color)] mb-4">
                                <?php echo $featured_post->post_title; ?>
                            </h2>
                            <p class="text-[var(--text-secondary)] mb-6 leading-relaxed">
                                <?php echo wp_trim_words($featured_post->post_content, 30); ?>
                            </p>
                            
                            <div class="flex items-center text-sm text-[var(--text-secondary)] mb-6">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <?php echo get_the_date('F j, Y', $featured_post->ID); ?>
                            </div>
                            
                            <a href="<?php echo get_permalink($featured_post->ID); ?>" 
                               class="inline-flex items-center text-[var(--primary-color)] hover:text-[var(--accent-gold)] font-semibold transition-colors duration-300">
                                Read Full Article
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- News Categories Filter -->
            <div class="flex flex-wrap gap-4 mb-12 justify-center">
                <button class="news-filter-btn active px-6 py-2 rounded-full bg-[var(--primary-color)] text-white font-semibold transition-all duration-300" data-filter="all">
                    All News
                </button>
                <?php
                $categories = get_categories(array('hide_empty' => true));
                foreach ($categories as $category) :
                    if ($category->slug !== 'uncategorized') :
                ?>
                    <button class="news-filter-btn px-6 py-2 rounded-full border-2 border-gray-300 text-gray-600 font-semibold hover:border-[var(--primary-color)] hover:text-[var(--primary-color)] transition-all duration-300" 
                            data-filter="<?php echo $category->slug; ?>">
                        <?php echo $category->name; ?>
                    </button>
                <?php 
                    endif;
                endforeach; 
                ?>
            </div>
            
            <!-- News Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8" id="news-grid">
                <?php
                // Get all posts
                $posts_query = new WP_Query(array(
                    'post_type' => 'post',
                    'posts_per_page' => 12,
                    'post_status' => 'publish',
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));

                if ($posts_query->have_posts()) :
                    while ($posts_query->have_posts()) : $posts_query->the_post();
                        $categories = get_the_category();
                        $category_slugs = array();
                        foreach ($categories as $category) {
                            $category_slugs[] = $category->slug;
                        }
                        $category_classes = implode(' ', $category_slugs);
                ?>
                    <article class="news-card bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300" 
                             data-categories="<?php echo esc_attr($category_classes); ?>">
                        
                        <?php if (has_post_thumbnail()) : ?>
                            <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium'); ?>" 
                                 alt="<?php the_title(); ?>" 
                                 class="w-full h-48 object-cover">
                        <?php else : ?>
                            <div class="w-full h-48 bg-gradient-to-br from-[var(--primary-color)] to-[var(--primary-color)]/80 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                </svg>
                            </div>
                        <?php endif; ?>
                        
                        <div class="p-6">
                            <!-- Categories -->
                            <?php if ($categories) : ?>
                                <div class="flex flex-wrap gap-2 mb-3">
                                    <?php foreach ($categories as $category) : ?>
                                        <?php if ($category->slug !== 'uncategorized') : ?>
                                            <span class="bg-[var(--accent-gold)]/20 text-[var(--primary-color)] px-3 py-1 rounded-full text-xs font-semibold">
                                                <?php echo $category->name; ?>
                                            </span>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            
                            <h3 class="text-lg font-bold text-[var(--primary-color)] mb-3 line-clamp-2">
                                <?php the_title(); ?>
                            </h3>
                            
                            <p class="text-[var(--text-secondary)] text-sm mb-4 leading-relaxed line-clamp-3">
                                <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                            </p>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-xs text-[var(--text-secondary)]">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <?php echo get_the_date('M j, Y'); ?>
                                </div>
                                
                                <a href="<?php the_permalink(); ?>" 
                                   class="text-[var(--primary-color)] hover:text-[var(--accent-gold)] font-semibold text-sm inline-flex items-center transition-colors duration-300">
                                    Read More
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php 
                    endwhile;
                    wp_reset_postdata();
                else : 
                ?>
                    <div class="col-span-full text-center py-12">
                        <p class="text-[var(--text-secondary)] text-lg">No news articles found.</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Load More Button -->
            <?php if ($posts_query->found_posts > 12) : ?>
                <div class="text-center mt-12">
                    <button id="load-more-news" 
                            class="bg-[var(--primary-color)] text-white px-8 py-3 rounded-lg font-semibold hover:bg-opacity-90 transition-all duration-300">
                        Load More Articles
                    </button>
                </div>
            <?php endif; ?>
            
        <?php endwhile; ?>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.news-filter-btn');
    const newsCards = document.querySelectorAll('.news-card');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Update active button
            filterBtns.forEach(b => {
                b.classList.remove('active', 'bg-[var(--primary-color)]', 'text-white');
                b.classList.add('border-2', 'border-gray-300', 'text-gray-600');
            });
            this.classList.add('active', 'bg-[var(--primary-color)]', 'text-white');
            this.classList.remove('border-2', 'border-gray-300', 'text-gray-600');
            
            // Filter news
            newsCards.forEach(card => {
                const categories = card.getAttribute('data-categories');
                if (filter === 'all' || categories.includes(filter)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<?php get_footer(); ?>
