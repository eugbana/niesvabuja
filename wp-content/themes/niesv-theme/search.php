<?php
/**
 * The template for displaying search results pages
 *
 * @package NIESV_Theme
 */

get_header(); ?>

<?php
// Get hero section data
$search_query = get_search_query();
$results_count = $wp_query->found_posts ?? 0;

$subtitle = '';
if (have_posts()) {
    $subtitle = "Found {$results_count} result" . ($results_count !== 1 ? 's' : '') . " for \"{$search_query}\"";
} else {
    $subtitle = "No results found for \"{$search_query}\"";
}

$hero_args = array(
    'title' => 'Search Results',
    'subtitle' => $subtitle,
    'background_image' => null,
    'overlay_color' => 'rgba(30, 58, 138, 0.8)',
    'size' => 'medium',
    'show_breadcrumb' => true,
);

// Include hero section
get_template_part('template-parts/hero-section', null, $hero_args);
?>

<main class="flex-1 bg-[var(--background-color)]">
    <!-- Search Form Section -->
    <section class="py-8 bg-[var(--secondary-color)]">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-md mx-auto">
                <form role="search" method="get" action="<?php echo home_url('/'); ?>" class="flex gap-2">
                    <input type="search"
                           name="s"
                           value="<?php echo get_search_query(); ?>"
                           placeholder="Search..."
                           class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent">
                    <button type="submit"
                            class="px-6 py-3 bg-[var(--primary-color)] text-white rounded-lg font-semibold hover:bg-opacity-90 transition-all">
                        Search
                    </button>
                </form>
            </div>
        </div>
    </section>
    
    <!-- Search Results -->
    <section class="py-12 md:py-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            
            <?php if (have_posts()) : ?>
                
                <!-- Results Grid -->
                <div class="space-y-8">
                    <?php while (have_posts()) : the_post(); ?>
                        <article class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <!-- Post Type Badge -->
                                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-[var(--secondary-color)] text-[var(--primary-color)] mb-3">
                                        <?php 
                                        $post_type = get_post_type();
                                        switch ($post_type) {
                                            case 'news':
                                                echo 'News Article';
                                                break;
                                            case 'events':
                                                echo 'Event';
                                                break;
                                            case 'properties':
                                                echo 'Property';
                                                break;
                                            case 'team_members':
                                                echo 'Team Member';
                                                break;
                                            case 'page':
                                                echo 'Page';
                                                break;
                                            default:
                                                echo 'Post';
                                        }
                                        ?>
                                    </span>
                                    
                                    <!-- Title -->
                                    <h2 class="text-xl md:text-2xl font-bold mb-3">
                                        <a href="<?php the_permalink(); ?>" class="text-[var(--text-primary)] hover:text-[var(--primary-color)] transition-colors">
                                            <?php the_title(); ?>
                                        </a>
                                    </h2>
                                    
                                    <!-- Meta Information -->
                                    <div class="flex flex-wrap items-center gap-4 text-sm text-[var(--text-secondary)] mb-4">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <?php echo get_the_date(); ?>
                                        </span>
                                        
                                        <?php if ($post_type !== 'page') : ?>
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                <?php the_author(); ?>
                                            </span>
                                        <?php endif; ?>
                                        
                                        <!-- Post Type Specific Meta -->
                                        <?php if ($post_type === 'events') : 
                                            $event_date = get_field('event_date');
                                            $event_location = get_field('event_location');
                                            if ($event_date) :
                                        ?>
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                Event: <?php echo date('M j, Y', strtotime($event_date)); ?>
                                            </span>
                                        <?php endif; endif; ?>
                                        
                                        <?php if ($post_type === 'properties') : 
                                            $property_price = get_field('property_price');
                                            $property_location = get_field('property_location');
                                            if ($property_price) :
                                        ?>
                                            <span class="flex items-center font-semibold text-[var(--primary-color)]">
                                                ₦<?php echo number_format($property_price); ?>
                                            </span>
                                        <?php endif; endif; ?>
                                    </div>
                                    
                                    <!-- Excerpt -->
                                    <div class="text-[var(--text-secondary)] leading-relaxed">
                                        <?php 
                                        if (has_excerpt()) {
                                            echo wp_trim_words(get_the_excerpt(), 30, '...');
                                        } else {
                                            echo wp_trim_words(get_the_content(), 30, '...');
                                        }
                                        ?>
                                    </div>
                                </div>
                                
                                <!-- Thumbnail -->
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="ml-6 flex-shrink-0">
                                        <a href="<?php the_permalink(); ?>">
                                            <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium'); ?>" 
                                                 alt="<?php the_title(); ?>" 
                                                 class="w-24 h-24 object-cover rounded-lg">
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Read More Link -->
                            <div class="pt-4 border-t border-gray-200">
                                <a href="<?php the_permalink(); ?>" 
                                   class="inline-flex items-center font-semibold text-[var(--primary-color)] hover:text-[var(--accent-gold)] transition-colors">
                                    Read More
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </a>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
                
                <!-- Pagination -->
                <?php niesv_pagination(null, 'Search results navigation'); ?>
                
            <?php else : ?>
                
                <!-- No Results -->
                <div class="text-center py-16">
                    <svg class="mx-auto h-24 w-24 text-gray-400 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    
                    <h2 class="text-2xl font-bold text-[var(--text-primary)] mb-4">No Results Found</h2>
                    <p class="text-[var(--text-secondary)] mb-8 max-w-2xl mx-auto">
                        We couldn't find any content matching your search. Try using different keywords or browse our categories below.
                    </p>
                    
                    <!-- Suggestions -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-semibold mb-3">Latest News</h3>
                            <p class="text-[var(--text-secondary)] text-sm mb-4">Check out our recent news and announcements</p>
                            <a href="<?php echo get_post_type_archive_link('news'); ?>" class="text-[var(--primary-color)] font-semibold hover:text-[var(--accent-gold)] transition-colors">
                                View News →
                            </a>
                        </div>
                        
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-semibold mb-3">Upcoming Events</h3>
                            <p class="text-[var(--text-secondary)] text-sm mb-4">Discover our conferences and workshops</p>
                            <a href="<?php echo get_post_type_archive_link('events'); ?>" class="text-[var(--primary-color)] font-semibold hover:text-[var(--accent-gold)] transition-colors">
                                View Events →
                            </a>
                        </div>
                        
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-semibold mb-3">Properties</h3>
                            <p class="text-[var(--text-secondary)] text-sm mb-4">Browse available property listings</p>
                            <a href="<?php echo get_post_type_archive_link('properties'); ?>" class="text-[var(--primary-color)] font-semibold hover:text-[var(--accent-gold)] transition-colors">
                                View Properties →
                            </a>
                        </div>
                    </div>
                </div>
                
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
