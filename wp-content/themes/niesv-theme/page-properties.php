<?php
/**
 * Template Name: Property Listings
 * Template for Property Listings page
 *
 * @package NIESV_Theme
 */

get_header(); ?>

<main class="flex-1 px-4 sm:px-6 lg:px-24 py-8">
    <div class="max-w-7xl mx-auto">
        
        <!-- Page Header -->
        <div class="mb-8 text-center">
            <h1 class="text-4xl font-extrabold tracking-tight text-[var(--primary-color)] sm:text-5xl">
                <?php echo get_field('properties_page_title') ?: 'Property Listings'; ?>
            </h1>
            <p class="mt-4 text-xl text-gray-500">
                <?php echo get_field('properties_page_subtitle') ?: 'Discover available properties in the Abuja area.'; ?>
            </p>
        </div>
        
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
                               id="search" 
                               name="search" 
                               placeholder="Search by location, type, price..." 
                               type="text"
                               value="<?php echo isset($_GET['search']) ? esc_attr($_GET['search']) : ''; ?>"/>
                    </div>
                </div>
                
                <!-- Property Type Filter -->
                <div>
                    <label class="sr-only" for="property_type">Property Type</label>
                    <select class="form-select block w-full py-3 px-3 text-base rounded-md border-gray-300 focus:outline-none focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)]" 
                            id="property_type" 
                            name="property_type">
                        <option value="">All Property Types</option>
                        <option value="residential" <?php selected(isset($_GET['property_type']) ? $_GET['property_type'] : '', 'residential'); ?>>Residential</option>
                        <option value="commercial" <?php selected(isset($_GET['property_type']) ? $_GET['property_type'] : '', 'commercial'); ?>>Commercial</option>
                        <option value="land" <?php selected(isset($_GET['property_type']) ? $_GET['property_type'] : '', 'land'); ?>>Land</option>
                        <option value="industrial" <?php selected(isset($_GET['property_type']) ? $_GET['property_type'] : '', 'industrial'); ?>>Industrial</option>
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
        
        <!-- Properties Grid -->
        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            <?php
            // Query arguments
            $args = array(
                'post_type' => 'properties',
                'posts_per_page' => 12,
                'post_status' => 'publish',
                'orderby' => 'date',
                'order' => 'DESC'
            );
            
            // Handle search
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $search_term = sanitize_text_field($_GET['search']);
                $args['s'] = $search_term;
            }
            
            // Handle property type filter
            if (isset($_GET['property_type']) && !empty($_GET['property_type'])) {
                $property_type = sanitize_text_field($_GET['property_type']);
                $args['meta_query'] = array(
                    array(
                        'key' => 'property_type',
                        'value' => $property_type,
                        'compare' => '='
                    )
                );
            }
            
            $properties_query = new WP_Query($args);
            
            if ($properties_query->have_posts()) :
                while ($properties_query->have_posts()) : $properties_query->the_post();
                    $property_price = get_field('property_price');
                    $property_type = get_field('property_type');
                    $property_status = get_field('property_status');
                    $property_location = get_field('property_location');
                    $property_size = get_field('property_size');
                    $property_bedrooms = get_field('property_bedrooms');
                    $property_bathrooms = get_field('property_bathrooms');
                    $agent_name = get_field('agent_name');
                    $agent_email = get_field('agent_email');
                    $agent_mobile = get_field('agent_mobile');
                    
                    // Format property type for display
                    $type_labels = array(
                        'residential' => 'Residential',
                        'commercial' => 'Commercial',
                        'land' => 'Land',
                        'industrial' => 'Industrial'
                    );
                    $type_display = isset($type_labels[$property_type]) ? $type_labels[$property_type] : ucfirst($property_type);
                    
                    // Format property status for display
                    $status_labels = array(
                        'for_sale' => 'For Sale',
                        'for_rent' => 'For Rent',
                        'sold' => 'Sold',
                        'rented' => 'Rented'
                    );
                    $status_display = isset($status_labels[$property_status]) ? $status_labels[$property_status] : ucfirst($property_status);
                    $status_color = ($property_status == 'for_sale' || $property_status == 'for_rent') ? 'bg-[var(--primary-color)]' : 'bg-gray-500';
            ?>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden transform hover:-translate-y-1 transition-transform duration-300">
                    <div class="relative">
                        <?php if (has_post_thumbnail()) : ?>
                            <img alt="<?php the_title(); ?>" 
                                 class="w-full h-56 object-cover" 
                                 src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>"/>
                        <?php else : ?>
                            <div class="w-full h-56 bg-gray-200 flex items-center justify-center">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                            </div>
                        <?php endif; ?>
                        <div class="absolute top-2 right-2 <?php echo $status_color; ?> text-white text-sm font-semibold px-3 py-1 rounded-full">
                            <?php echo $status_display; ?>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-[var(--primary-color)] mb-2">
                            <a href="<?php the_permalink(); ?>" class="hover:text-opacity-80 transition-colors">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        
                        <?php if ($property_location) : ?>
                            <p class="text-gray-600 mb-4 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <?php echo $property_location; ?>
                            </p>
                        <?php endif; ?>
                        
                        <!-- Property Features -->
                        <?php if ($property_bedrooms || $property_bathrooms || $property_size) : ?>
                            <div class="flex items-center text-gray-500 text-sm mb-4 space-x-4">
                                <?php if ($property_bedrooms && $property_type == 'residential') : ?>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                                        </svg>
                                        <?php echo $property_bedrooms; ?> Beds
                                    </span>
                                <?php endif; ?>
                                
                                <?php if ($property_bathrooms && $property_type == 'residential') : ?>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path clip-rule="evenodd" d="M4.5 2A1.5 1.5 0 003 3.5v13A1.5 1.5 0 004.5 18h11a1.5 1.5 0 001.5-1.5V6.621a1.5 1.5 0 00-.44-1.06L13.44.439A1.5 1.5 0 0012.38 0H4.5zm8 2.5V5h-2V2.5a.5.5 0 01.5-.5h1a.5.5 0 01.5.5z" fill-rule="evenodd"></path>
                                        </svg>
                                        <?php echo $property_bathrooms; ?> Baths
                                    </span>
                                <?php endif; ?>
                                
                                <?php if ($property_size) : ?>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                            <path clip-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" fill-rule="evenodd"></path>
                                        </svg>
                                        <?php echo $property_size; ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Price -->
                        <?php if ($property_price) : ?>
                            <p class="text-lg font-semibold text-gray-800 mb-4">
                                ₦<?php echo number_format($property_price); ?>
                                <?php if ($property_status == 'for_rent') echo ' / annum'; ?>
                            </p>
                        <?php endif; ?>
                        
                        <!-- Agent Contact and View Details -->
                        <div class="flex items-center justify-between border-t pt-4">
                            <div class="flex gap-2">
                                <?php if ($agent_mobile) : ?>
                                    <a class="text-gray-400 hover:text-[#25D366]" href="tel:<?php echo esc_attr($agent_mobile); ?>" title="Call Agent">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                                        </svg>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($agent_email) : ?>
                                    <a class="text-gray-400 hover:text-[#1877F2]" href="mailto:<?php echo esc_attr($agent_email); ?>" title="Email Agent">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                                        </svg>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($agent_mobile) : ?>
                                    <a class="text-gray-400 hover:text-[#25D366]" href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $agent_mobile); ?>" title="WhatsApp Agent" target="_blank">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M20.1 3.9C17.9 1.7 15 .5 12 .5 5.8.5.7 5.6.7 11.9c0 2.1.6 4.2 1.7 6.1L.9 23.1l5.3-1.5c1.8 1 3.9 1.5 6 1.5h.1c6.3 0 11.4-5.1 11.4-11.4-.1-3-1.3-5.8-3.5-7.9zM12 21.1c-1.9 0-3.8-.5-5.4-1.5l-.4-.2-4 1.1.9-3.8-.2-.4c-1-1.7-1.6-3.6-1.6-5.6 0-5.4 4.4-9.8 9.8-9.8s9.8 4.4 9.8 9.8-4.3 9.8-9.8 9.8zM17.3 14.4c-.3-.2-1.8-1-2.1-1.1-.3-.1-.5-.1-.7.1-.2.2-.8.9-1 1.1-.2.2-.4.2-.6.1s-1.5-.5-2.9-1.8c-1.1-1-1.8-2.2-2-2.6-.2-.4 0-.6.2-.8.1-.1.3-.3.4-.4.1-.1.2-.2.3-.4.1-.2 0-.4 0-.5C8.3 9 7.4 6.9 7.1 6.3s-.5-.5-.7-.5h-.7c-.2 0-.5.2-.7.4-.2.2-.8 1-.8 2.3s.8 2.7 1 2.9c.1.2 1.8 2.8 4.3 3.8 2.5 1 2.5.7 3 .6.5-.1 1.8-.7 2-1.4.3-.6.3-1.2.2-1.4-.1-.1-.3-.2-.6-.4z"/>
                                        </svg>
                                    </a>
                                <?php endif; ?>
                            </div>
                            <a href="<?php the_permalink(); ?>" 
                               class="bg-[var(--primary-color)] text-white text-sm font-semibold px-4 py-2 rounded-md hover:bg-opacity-90 transition-opacity">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            <?php 
                endwhile;
            else :
            ?>
                <div class="col-span-full text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No properties found</h3>
                    <p class="mt-1 text-sm text-gray-500">Try adjusting your search criteria.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Pagination -->
        <?php niesv_pagination($properties_query, 'Properties navigation'); ?>
        
        <?php wp_reset_postdata(); ?>
    </div>
</main>

<?php get_footer(); ?>
