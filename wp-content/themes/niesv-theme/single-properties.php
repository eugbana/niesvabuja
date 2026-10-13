<?php
/**
 * Template for single property
 *
 * @package NIESV_Theme
 */

get_header(); ?>

<?php while (have_posts()) : the_post(); 
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
    $contact_agent_link = get_field('contact_agent_link');
    $property_features = get_field('property_features');
    $property_description = get_field('property_description');
    $property_images = get_field('property_images');
    $rent_duration = get_field('rent_duration');

?>

<main class="flex-1 bg-[#f9fafb]">
    <!-- Property Header -->
    <section class="bg-white border-b border-[#e5e7eb]">
        <div class="container mx-auto px-6 py-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                
                <!-- Property Title and Location -->
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-4">
                        <!-- Property Status Badge -->
                        <?php if ($property_status) : ?>
                            <span class="px-3 py-1 text-sm font-semibold rounded-full 
                                <?php echo $property_status === 'for_sale' ? 'bg-green-100 text-green-800' : 
                                           ($property_status === 'for_rent' ? 'bg-blue-100 text-blue-800' : 
                                           ($property_status === 'sold' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')); ?>">
                                <?php echo ucwords(str_replace('_', ' ', $property_status)); ?>
                            </span>
                        <?php endif; ?>
                        
                        <!-- Property Type -->
                        <?php if ($property_type) : ?>
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-[#f3f3f3] text-[#141414]">
                                <?php echo ucfirst($property_type); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <h1 class="text-3xl md:text-4xl font-bold text-[#141414] mb-3">
                        <?php the_title(); ?>
                    </h1>
                    
                    <?php if ($property_location) : ?>
                        <p class="text-lg text-[#575757] flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <?php echo $property_location; ?>
                        </p>
                    <?php endif; ?>
                </div>
                
                <!-- Property Price -->
                <?php if ($property_price) : ?>
                    <div class="text-right">
                        <div class="text-3xl md:text-4xl font-bold text-[#00573d]">
                            ₦<?php echo number_format($property_price); ?>
                        </div>
                         <?php if ($property_status === 'for_rent' && $rent_duration) : ?>
                            <?php 
                                $duration_labels = array(
                                    'monthly' => 'per month',
                                    'quarterly' => 'per quarter',
                                    'semi_annual' => 'per half year',
                                    'annual' => 'per year',
                                );
                                $duration_label = isset($duration_labels[$rent_duration]) ? $duration_labels[$rent_duration] : 'per month';
                            ?>
                            <div class="text-[#575757]"><?php echo $duration_label; ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    
    <!-- Property Content -->
    <section class="py-12">
        <div class="container mx-auto px-6">
            <div class="grid lg:grid-cols-3 gap-12">
                
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    
                    <!-- Property Images Gallery -->
                    <?php if ($property_images && is_array($property_images)) : ?>
                        <div class="mb-8">
                            <!-- Main Image -->
                            <div class="mb-4 rounded-lg overflow-hidden shadow-lg">
                                <img src="<?php echo esc_url($property_images[0]['sizes']['large']); ?>"
                                     alt="<?php echo esc_attr($property_images[0]['alt']); ?>"
                                     class="w-full h-96 object-cover cursor-pointer property-main-image"
                                     data-index="0">
                            </div>

                            <!-- Thumbnail Gallery -->
                            <?php if (count($property_images) > 1) : ?>
                                <div class="grid grid-cols-4 md:grid-cols-6 gap-2">
                                    <?php foreach ($property_images as $index => $image) : ?>
                                        <div class="aspect-square rounded-lg overflow-hidden cursor-pointer property-thumbnail <?php echo $index === 0 ? 'ring-2 ring-blue-500' : ''; ?>"
                                             data-index="<?php echo $index; ?>">
                                            <img src="<?php echo esc_url($image['sizes']['thumbnail']); ?>"
                                                 alt="<?php echo esc_attr($image['alt']); ?>"
                                                 class="w-full h-full object-cover hover:opacity-80 transition-opacity">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php elseif (has_post_thumbnail()) : ?>
                        <!-- Fallback to featured image if no gallery images -->
                        <div class="mb-8 rounded-lg overflow-hidden shadow-lg">
                            <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>"
                                 alt="<?php the_title(); ?>"
                                 class="w-full h-96 object-cover">
                        </div>
                    <?php endif; ?>
                    
                    <!-- Property Features Grid -->
                    <?php if ($property_bedrooms || $property_bathrooms || $property_size) : ?>
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-[#e5e7eb] mb-8">
                            <h3 class="text-xl font-bold text-[#141414] mb-4">Property Features</h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                                
                                <?php if ($property_bedrooms) : ?>
                                    <div class="text-center">
                                        <div class="w-12 h-12 bg-[#00573d] text-white rounded-full flex items-center justify-center mx-auto mb-2">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                            </svg>
                                        </div>
                                        <div class="text-2xl font-bold text-[#141414]"><?php echo $property_bedrooms; ?></div>
                                        <div class="text-sm text-[#575757]">Bedroom<?php echo $property_bedrooms > 1 ? 's' : ''; ?></div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($property_bathrooms) : ?>
                                    <div class="text-center">
                                        <div class="w-12 h-12 bg-[#00573d] text-white rounded-full flex items-center justify-center mx-auto mb-2">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                                            </svg>
                                        </div>
                                        <div class="text-2xl font-bold text-[#141414]"><?php echo $property_bathrooms; ?></div>
                                        <div class="text-sm text-[#575757]">Bathroom<?php echo $property_bathrooms > 1 ? 's' : ''; ?></div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($property_size) : ?>
                                    <div class="text-center">
                                        <div class="w-12 h-12 bg-[#00573d] text-white rounded-full flex items-center justify-center mx-auto mb-2">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                                            </svg>
                                        </div>
                                        <div class="text-lg font-bold text-[#141414]"><?php echo $property_size; ?></div>
                                        <div class="text-sm text-[#575757]">Size</div>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-[#00573d] text-white rounded-full flex items-center justify-center mx-auto mb-2">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    <div class="text-lg font-bold text-[#141414]"><?php echo ucfirst($property_type); ?></div>
                                    <div class="text-sm text-[#575757]">Type</div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Property Description -->
                    <?php if ($property_description) : ?>
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-[#e5e7eb] mb-8">
                            <h3 class="text-xl font-bold text-[#141414] mb-4">Description</h3>
                            <div class="prose prose-lg max-w-none text-[#575757]">
                                <?php echo nl2br(esc_html($property_description)); ?>
                            </div>
                        </div>
                    <?php elseif (get_the_content()) : ?>
                        <!-- Fallback to post content if no ACF description -->
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-[#e5e7eb] mb-8">
                            <h3 class="text-xl font-bold text-[#141414] mb-4">Description</h3>
                            <div class="prose prose-lg max-w-none text-[#575757]">
                                <?php the_content(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Additional Features -->
                    <?php if ($property_features && is_array($property_features)) : ?>
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-[#e5e7eb] mb-8">
                            <h3 class="text-xl font-bold text-[#141414] mb-4">Additional Features</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <?php foreach ($property_features as $feature) : ?>
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-[#575757]"><?php echo $feature['feature_name']; ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="sticky top-8 space-y-6">
                        
                        <!-- Contact Agent Card -->
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-[#e5e7eb]">
                            <h3 class="text-xl font-bold text-[#141414] mb-4">Contact Agent</h3>
                            
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-[#00573d] text-white rounded-full flex items-center justify-center mr-4">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-[#141414]">
                                            <?php echo $agent_name ?: 'NIESV Agent'; ?>
                                        </div>
                                        <div class="text-sm text-[#575757]">Licensed Property Agent</div>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <?php if ($agent_mobile) : ?>
                                        <a href="tel:<?php echo esc_attr($agent_mobile); ?>"
                                           class="flex items-center w-full p-3 bg-[#f3f3f3] rounded-lg hover:bg-gray-200 transition-colors">
                                            <svg class="w-5 h-5 text-[#00573d] mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                            <div class="flex flex-col">
                                                <span class="text-[#141414] font-medium">Call Agent</span>
                                                <span class="text-sm text-[#575757]"><?php echo $agent_mobile; ?></span>
                                            </div>
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($agent_email) : ?>
                                        <a href="mailto:<?php echo esc_attr($agent_email); ?>"
                                           class="flex items-center w-full p-3 bg-[#f3f3f3] rounded-lg hover:bg-gray-200 transition-colors">
                                            <svg class="w-5 h-5 text-[#00573d] mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                            <div class="flex flex-col">
                                                <span class="text-[#141414] font-medium">Email Agent</span>
                                                <span class="text-sm text-[#575757]"><?php echo $agent_email; ?></span>
                                            </div>
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($agent_mobile) : ?>
                                        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $agent_mobile); ?>"
                                           target="_blank"
                                           class="flex items-center w-full p-3 bg-[#25D366] text-white rounded-lg hover:bg-opacity-90 transition-colors">
                                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M20.1 3.9C17.9 1.7 15 .5 12 .5 5.8.5.7 5.6.7 11.9c0 2.1.6 4.2 1.7 6.1L.9 23.1l5.3-1.5c1.8 1 3.9 1.5 6 1.5h.1c6.3 0 11.4-5.1 11.4-11.4-.1-3-1.3-5.8-3.5-7.9zM12 21.1c-1.9 0-3.8-.5-5.4-1.5l-.4-.2-4 1.1.9-3.8-.2-.4c-1-1.7-1.6-3.6-1.6-5.6 0-5.4 4.4-9.8 9.8-9.8s9.8 4.4 9.8 9.8-4.3 9.8-9.8 9.8zM17.3 14.4c-.3-.2-1.8-1-2.1-1.1-.3-.1-.5-.1-.7.1-.2.2-.8.9-1 1.1-.2.2-.4.2-.6.1s-1.5-.5-2.9-1.8c-1.1-1-1.8-2.2-2-2.6-.2-.4 0-.6.2-.8.1-.1.3-.3.4-.4.1-.1.2-.2.3-.4.1-.2 0-.4 0-.5C8.3 9 7.4 6.9 7.1 6.3s-.5-.5-.7-.5h-.7c-.2 0-.5.2-.7.4-.2.2-.8 1-.8 2.3s.8 2.7 1 2.9c.1.2 1.8 2.8 4.3 3.8 2.5 1 2.5.7 3 .6.5-.1 1.8-.7 2-1.4.3-.6.3-1.2.2-1.4-.1-.1-.3-.2-.6-.4z"/>
                                            </svg>
                                            <span class="font-medium">WhatsApp Agent</span>
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($contact_agent_link) : ?>
                                        <a href="<?php echo $contact_agent_link; ?>"
                                           class="flex items-center justify-center w-full p-3 bg-[#00573d] text-white rounded-lg hover:bg-opacity-90 transition-all font-semibold">
                                            Contact Agent
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Property Summary -->
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-[#e5e7eb]">
                            <h3 class="text-xl font-bold text-[#141414] mb-4">Property Summary</h3>
                            
                            <div class="space-y-3">
                                <?php if ($property_price) : ?>
                                    <div class="flex justify-between">
                                        <span class="text-[#575757]">Price:</span>
                                        <span class="font-semibold text-[#141414]">₦<?php echo number_format($property_price); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($property_type) : ?>
                                    <div class="flex justify-between">
                                        <span class="text-[#575757]">Type:</span>
                                        <span class="font-semibold text-[#141414]"><?php echo ucfirst($property_type); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($property_status) : ?>
                                    <div class="flex justify-between">
                                        <span class="text-[#575757]">Status:</span>
                                        <span class="font-semibold text-[#141414]"><?php echo ucwords(str_replace('_', ' ', $property_status)); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($property_location) : ?>
                                    <div class="flex justify-between">
                                        <span class="text-[#575757]">Location:</span>
                                        <span class="font-semibold text-[#141414]"><?php echo $property_location; ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($property_size) : ?>
                                    <div class="flex justify-between">
                                        <span class="text-[#575757]">Size:</span>
                                        <span class="font-semibold text-[#141414]"><?php echo $property_size; ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Share Property -->
                        <?php niesv_social_share(array(
                            'title' => 'Share Property',
                            'show_whatsapp' => true
                        )); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Related Properties -->
    <?php
    $related_properties = new WP_Query(array(
        'post_type' => 'properties',
        'posts_per_page' => 3,
        'post__not_in' => array(get_the_ID()),
        'meta_query' => array(
            array(
                'key' => 'property_type',
                'value' => $property_type,
                'compare' => '='
            )
        )
    ));
    
    if ($related_properties->have_posts()) :
    ?>
        <section class="py-16 bg-white">
            <div class="container mx-auto px-6">
                <h2 class="text-3xl font-bold text-[#141414] text-center mb-12">Similar Properties</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <?php while ($related_properties->have_posts()) : $related_properties->the_post(); 
                        $related_price = get_field('property_price');
                        $related_location = get_field('property_location');
                    ?>
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                            <?php if (has_post_thumbnail()) : ?>
                                <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'property-thumbnail'); ?>" 
                                     alt="<?php the_title(); ?>" 
                                     class="w-full h-48 object-cover">
                            <?php endif; ?>
                            
                            <div class="p-6">
                                <h3 class="text-xl font-semibold mb-2 text-black">
                                    <a href="<?php the_permalink(); ?>" class="text-black hover:text-black transition-colors">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>
                                
                                <?php if ($related_location) : ?>
                                    <p class="text-[#575757] text-sm mb-3"><?php echo $related_location; ?></p>
                                <?php endif; ?>
                                
                                <?php if ($related_price) : ?>
                                    <div class="text-2xl font-bold text-[#00573d] mb-4">
                                        ₦<?php echo number_format($related_price); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <a href="<?php the_permalink(); ?>" 
                                   class="inline-block bg-[#00573d] text-white px-4 py-2 rounded-md hover:bg-opacity-90 transition-all">
                                    View Details
                                </a>
                            </div>
                        </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
</main>

<?php endwhile; ?>

<!-- Property Gallery Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mainImage = document.querySelector('.property-main-image');
    const thumbnails = document.querySelectorAll('.property-thumbnail');

    if (mainImage && thumbnails.length > 0) {
        // Get all images data
        const images = <?php echo json_encode($property_images ?: []); ?>;

        thumbnails.forEach(function(thumbnail, index) {
            thumbnail.addEventListener('click', function() {
                const imageIndex = parseInt(this.dataset.index);

                if (images[imageIndex]) {
                    // Update main image
                    mainImage.src = images[imageIndex].sizes.large;
                    mainImage.alt = images[imageIndex].alt;
                    mainImage.dataset.index = imageIndex;

                    // Update thumbnail active state
                    thumbnails.forEach(function(thumb) {
                        thumb.classList.remove('ring-2', 'ring-blue-500');
                    });
                    this.classList.add('ring-2', 'ring-blue-500');
                }
            });
        });

        // Optional: Add keyboard navigation
        document.addEventListener('keydown', function(e) {
            const currentIndex = parseInt(mainImage.dataset.index);
            let newIndex = currentIndex;

            if (e.key === 'ArrowLeft' && currentIndex > 0) {
                newIndex = currentIndex - 1;
            } else if (e.key === 'ArrowRight' && currentIndex < images.length - 1) {
                newIndex = currentIndex + 1;
            }

            if (newIndex !== currentIndex) {
                thumbnails[newIndex].click();
            }
        });
    }
});
</script>

<?php get_footer(); ?>
