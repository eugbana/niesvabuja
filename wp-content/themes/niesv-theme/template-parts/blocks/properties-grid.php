<?php
/**
 * Properties Grid Block Template
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param int|string $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'properties-grid-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'properties-grid-block';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}

// Load values and assign defaults.
$section_title = get_field('section_title') ?: 'Featured Properties';
$number_of_properties = get_field('number_of_properties') ?: 6;
$property_type_filter = get_field('property_type_filter');
$property_status_filter = get_field('property_status_filter');
$show_price = get_field('show_price') !== false;
$show_location = get_field('show_location') !== false;
$show_features = get_field('show_features') !== false;

// Query arguments
$args = array(
    'post_type' => 'properties',
    'posts_per_page' => $number_of_properties,
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC'
);

// Add filters
$meta_query = array();

if ($property_type_filter) {
    $meta_query[] = array(
        'key' => 'property_type',
        'value' => $property_type_filter,
        'compare' => '='
    );
}

if ($property_status_filter) {
    $meta_query[] = array(
        'key' => 'property_status',
        'value' => $property_status_filter,
        'compare' => '='
    );
}

if (!empty($meta_query)) {
    if (count($meta_query) > 1) {
        $meta_query['relation'] = 'AND';
    }
    $args['meta_query'] = $meta_query;
}

$properties_query = new WP_Query($args);
?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?> py-12 md:py-20 bg-[#f8fafc]">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Title -->
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold tracking-tight mb-2 text-[rgba(0,0,40,1)] border-b-2 border-[#d4af37] inline-block pb-2">
                <?php echo esc_html($section_title); ?>
            </h2>
        </div>
        
        <?php if ($properties_query->have_posts()) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php while ($properties_query->have_posts()) : $properties_query->the_post(); 
                    $property_price = get_field('property_price');
                    $property_type = get_field('property_type');
                    $property_status = get_field('property_status');
                    $property_location = get_field('property_location');
                    $property_size = get_field('property_size');
                    $property_bedrooms = get_field('property_bedrooms');
                    $property_bathrooms = get_field('property_bathrooms');
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
                            <?php if ($show_price && $property_price) : ?>
                                <div class="absolute top-3 right-3">
                                    <span class="bg-[rgba(0,0,40,1)] text-white px-3 py-1 rounded-full text-sm font-bold">
                                        ₦<?php echo number_format($property_price); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Property Details -->
                        <div class="p-6">
                            
                            <!-- Property Type -->
                            <?php if ($property_type) : ?>
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-[#e7edf4] text-[rgba(0,0,40,1)] mb-2">
                                    <?php echo ucfirst($property_type); ?>
                                </span>
                            <?php endif; ?>
                            
                            <!-- Property Title -->
                            <h3 class="text-xl font-bold mb-2 text-black">
                                <a href="<?php the_permalink(); ?>" class="text-black hover:text-black"><?php the_title(); ?></a>
                            </h3>
                            
                            <!-- Property Location -->
                            <?php if ($show_location && $property_location) : ?>
                                <p class="text-sm text-[#49739c] mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <?php echo wp_kses_post($property_location); ?>
                                </p>
                            <?php endif; ?>
                            
                            <!-- Property Features -->
                            <?php if ($show_features && ($property_bedrooms || $property_bathrooms || $property_size)) : ?>
                                <div class="flex items-center gap-4 mb-4 text-sm text-[#49739c]">
                                    <?php if ($property_bedrooms) : ?>
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                            </svg>
                                            <?php echo wp_kses_post($property_bedrooms); ?> Bed<?php echo $property_bedrooms > 1 ? 's' : ''; ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if ($property_bathrooms) : ?>
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                                            </svg>
                                            <?php echo wp_kses_post($property_bathrooms); ?> Bath<?php echo $property_bathrooms > 1 ? 's' : ''; ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if ($property_size) : ?>
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                                            </svg>
                                            <?php echo wp_kses_post($property_size); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Property Description -->
                            <p class="text-[#49739c] mb-4 text-sm leading-relaxed">
                                <?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?>
                            </p>
                            
                            <!-- Action Buttons -->
                            <div class="flex gap-3">
                                <a href="<?php the_permalink(); ?>" 
                                   class="flex-1 text-center py-2 px-4 bg-[rgba(0,0,40,1)] text-white rounded-md text-sm font-semibold hover:bg-[#d4af37] hover:text-[rgba(0,0,40,1)] transition-all">
                                    View Details
                                </a>
                                
                                <?php if (get_field('contact_agent_link')) : ?>
                                    <a href="<?php echo esc_url(get_field('contact_agent_link')); ?>" 
                                       class="py-2 px-4 border border-[rgba(0,0,40,1)] text-[rgba(0,0,40,1)] rounded-md text-sm font-semibold hover:bg-[rgba(0,0,40,1)] hover:text-white transition-all">
                                        Contact Agent
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            
            <!-- View All Link -->
            <div class="text-center mt-12">
                <a href="<?php echo get_post_type_archive_link('properties'); ?>" 
                   class="inline-flex items-center px-6 py-3 bg-[rgba(0,0,40,1)] text-white rounded-lg font-semibold hover:bg-[#d4af37] hover:text-[rgba(0,0,40,1)] transition-all">
                    View All Properties
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
            
        <?php else : ?>
            <!-- No Properties Found -->
            <div class="text-center py-12">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Properties Found</h3>
                <p class="text-gray-600">Please add some properties to display them here.</p>
            </div>
        <?php endif; ?>
        
        <?php wp_reset_postdata(); ?>
    </div>
</section>

<?php if ($is_preview) : ?>
    <style>
        .properties-grid-block {
            background: #f8fafc;
            border: 2px dashed #ccc;
            padding: 2rem;
        }
    </style>
<?php endif; ?>
