<?php
/**
 * Template for Properties Archive
 *
 * @package NIESV_Theme
 */

get_header(); ?>

<?php
// Get hero section data
$hero_args = array(
    'title' => get_field('properties_page_title', 'option') ?: 'Property Listings',
    'subtitle' => get_field('properties_page_subtitle', 'option') ?: 'Discover available properties in the Abuja area.',
    'background_image' => get_field('properties_hero_background', 'option'),
    'overlay_color' => 'rgba(30, 58, 138, 0.8)',
    'size' => 'medium',
    'show_breadcrumb' => true,
);

// Include hero section
get_template_part('template-parts/hero-section', null, $hero_args);
?>

<main class="flex-1 px-4 sm:px-6 lg:px-24 py-8">
    <div class="max-w-7xl mx-auto">
        
        <!-- Search and Filter Section -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <form class="grid grid-cols-1 md:grid-cols-4 gap-6" method="GET" action="">
                
                <!-- Search Input -->
                <div class="md:col-span-2">
                    <label class="sr-only" for="search">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg aria-hidden="true" class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path clip-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" fill-rule="evenodd"></path>
                            </svg>
                        </div>
                        <input class="form-input block w-full pl-10 pr-3 py-3 text-base rounded-md border-gray-300 focus:outline-none focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)]" 
                               id="search" name="search" placeholder="Search by location, type, price..." type="text" 
                               value="<?php echo esc_attr(get_query_var('search')); ?>">
                    </div>
                </div>
                
                <!-- Property Type Filter -->
                <div>
                    <!-- <label for="property_type" class="block text-sm font-medium text-gray-700 mb-1">Property Type</label> -->
                    <select id="property_type" name="property_type" 
                            class="block w-full py-3 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)]">
                        <option value="">All Types</option>
                        <option value="residential" <?php selected(get_query_var('property_type'), 'residential'); ?>>Residential</option>
                        <option value="commercial" <?php selected(get_query_var('property_type'), 'commercial'); ?>>Commercial</option>
                        <option value="land" <?php selected(get_query_var('property_type'), 'land'); ?>>Land</option>
                        <option value="industrial" <?php selected(get_query_var('property_type'), 'industrial'); ?>>Industrial</option>
                    </select>
                </div>
                
                <!-- Search Button -->
                <div class="flex items-end">
                    <button type="submit" 
                            class="w-full py-3 px-4 bg-[var(--primary-color)] text-white rounded-md font-semibold hover:bg-opacity-90 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--primary-color)]">
                        Search Properties
                    </button>
                </div>
            </form>
        </div>
        
        <?php if (have_posts()) : ?>
            
            <!-- Results Count -->
            <div class="mb-6 flex justify-between items-center">
                <p class="text-gray-600">
                    Showing <?php echo $wp_query->post_count; ?> of <?php echo $wp_query->found_posts; ?> properties
                </p>
                
                <!-- Sort Options -->
                <div class="flex items-center gap-2">
                    <label for="sort" class="text-sm text-gray-600">Sort by:</label>
                    <select id="sort" name="sort" class="text-sm border-gray-300 rounded-md focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)]">
                        <option value="date">Newest First</option>
                        <option value="price_low">Price: Low to High</option>
                        <option value="price_high">Price: High to Low</option>
                        <option value="title">Name A-Z</option>
                    </select>
                </div>
            </div>
            
            <!-- Properties Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php while (have_posts()) : the_post(); 
                    $property_price = get_field('property_price');
                    $property_type = get_field('property_type');
                    $property_location = get_field('property_location');
                    $property_size = get_field('property_size');
                    $property_bedrooms = get_field('property_bedrooms');
                    $property_bathrooms = get_field('property_bathrooms');
                    $property_status = get_field('property_status');
                ?>
                    <article class="bg-white rounded-lg shadow-lg overflow-hidden group hover:shadow-xl transition-shadow duration-300">
                        
                        <!-- Property Image -->
                        <div class="relative">
                            <?php if (has_post_thumbnail()) : ?>
                                <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'property-thumbnail'); ?>" 
                                     alt="<?php the_title(); ?>" 
                                     class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                            <?php else : ?>
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9h6v6H9z"></path>
                                    </svg>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Property Status Badge -->
                            <?php if ($property_status) : ?>
                                <div class="absolute top-3 left-3">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                        <?php echo $property_status === 'for_sale' ? 'bg-green-100 text-green-800' : 
                                                   ($property_status === 'for_rent' ? 'bg-blue-100 text-blue-800' : 
                                                   ($property_status === 'sold' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')); ?>">
                                        <?php echo ucwords(str_replace('_', ' ', $property_status)); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Property Price -->
                            <?php if ($property_price) : ?>
                                <div class="absolute top-3 right-3">
                                    <span class="bg-[var(--primary-color)] text-white px-3 py-1 rounded-full text-sm font-bold">
                                        ₦<?php echo number_format($property_price); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Property Details -->
                        <div class="p-6">
                            
                            <!-- Property Type -->
                            <?php if ($property_type) : ?>
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-[var(--secondary-color)] text-[var(--primary-color)] mb-2">
                                    <?php echo ucfirst($property_type); ?>
                                </span>
                            <?php endif; ?>
                            
                            <!-- Property Title -->
                            <h3 class="text-xl font-bold mb-2 text-black">
                                <a href="<?php the_permalink(); ?>" class="text-black hover:text-black"><?php the_title(); ?></a>
                            </h3>
                            
                            <!-- Property Location -->
                            <?php if ($property_location) : ?>
                                <p class="text-sm text-[var(--text-secondary)] mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <?php echo $property_location; ?>
                                </p>
                            <?php endif; ?>
                            
                            <!-- Property Features -->
                            <div class="flex items-center gap-4 mb-4 text-sm text-[var(--text-secondary)]">
                                <?php if ($property_bedrooms) : ?>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                        </svg>
                                        <?php echo $property_bedrooms; ?> Bed<?php echo $property_bedrooms > 1 ? 's' : ''; ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if ($property_bathrooms) : ?>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                                        </svg>
                                        <?php echo $property_bathrooms; ?> Bath<?php echo $property_bathrooms > 1 ? 's' : ''; ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if ($property_size) : ?>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                                        </svg>
                                        <?php echo $property_size; ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Property Description -->
                            <p class="text-[var(--text-secondary)] mb-4 text-sm leading-relaxed">
                                <?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?>
                            </p>
                            
                            <!-- Action Buttons -->
                            <div class="flex gap-3">
                                <a href="<?php the_permalink(); ?>" 
                                   class="flex-1 text-center py-2 px-4 bg-[var(--primary-color)] text-white rounded-md text-sm font-semibold hover:bg-opacity-90 transition-all">
                                    View Details
                                </a>
                                
                                <?php if (get_field('contact_agent_link')) : ?>
                                    <a href="<?php echo get_field('contact_agent_link'); ?>" 
                                       class="py-2 px-4 border border-[var(--primary-color)] text-[var(--primary-color)] rounded-md text-sm font-semibold hover:bg-[var(--primary-color)] hover:text-white transition-all">
                                        Contact Agent
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            
            <!-- Pagination -->
            <?php niesv_pagination(null, 'Properties navigation'); ?>
            
        <?php else : ?>
            <!-- No Properties Found -->
            <div class="text-center py-16">
                <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <h3 class="text-2xl font-semibold text-gray-900 mb-2">No Properties Found</h3>
                <p class="text-gray-600 mb-6">
                    <?php if (get_query_var('search') || get_query_var('property_type')) : ?>
                        No properties match your search criteria. Try adjusting your filters.
                    <?php else : ?>
                        There are currently no properties available. Please check back later.
                    <?php endif; ?>
                </p>
                <a href="<?php echo remove_query_arg(array('search', 'property_type')); ?>" class="btn-primary">
                    Clear Filters
                </a>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
