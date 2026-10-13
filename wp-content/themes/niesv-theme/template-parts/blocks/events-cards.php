<?php
/**
 * Events Cards Block Template
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param int|string $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'events-cards-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'events-cards-block';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}

// Load values and assign defaults.
$section_title = get_field('section_title') ?: 'Upcoming Events';
$number_of_events = get_field('number_of_events') ?: 4;
$show_past_events = get_field('show_past_events') ?: false;
$event_type_filter = get_field('event_type_filter');

// Query arguments
$args = array(
    'post_type' => 'events',
    'posts_per_page' => $number_of_events,
    'post_status' => 'publish',
    'meta_key' => 'event_date',
    'orderby' => 'meta_value',
    'order' => 'ASC'
);

// Filter by upcoming or past events
if (!$show_past_events) {
    $args['meta_query'] = array(
        array(
            'key' => 'event_date',
            'value' => date('Y-m-d'),
            'compare' => '>='
        )
    );
}

// Add event type filter if selected
if ($event_type_filter) {
    if (!isset($args['meta_query'])) {
        $args['meta_query'] = array();
    }
    $args['meta_query'][] = array(
        'key' => 'event_type',
        'value' => $event_type_filter,
        'compare' => '='
    );
    
    if (count($args['meta_query']) > 1) {
        $args['meta_query']['relation'] = 'AND';
    }
}

$events_query = new WP_Query($args);
?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?> py-12 md:py-20 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Title -->
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold tracking-tight mb-2 text-[rgba(0,0,40,1)] border-b-2 border-[#d4af37] inline-block pb-2">
                <?php echo esc_html($section_title); ?>
            </h2>
        </div>
        
        <?php if ($events_query->have_posts()) : ?>
            <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">
                <?php while ($events_query->have_posts()) : $events_query->the_post(); 
                    $event_date = get_field('event_date');
                    $event_time = get_field('event_time');
                    $event_location = get_field('event_location');
                    $event_type = get_field('event_type');
                    $registration_link = get_field('registration_link');
                    
                    $date_obj = $event_date ? DateTime::createFromFormat('Y-m-d', $event_date) : null;
                    $is_upcoming = $date_obj && $date_obj >= new DateTime();
                ?>
                    <article class="bg-[#d4af37] rounded-xl shadow-lg flex items-center p-6 transform hover:scale-105 transition-transform duration-300">
                        
                        <!-- Date Display -->
                        <?php if ($date_obj) : ?>
                            <div class="w-20 h-20 bg-white text-[rgba(0,0,40,1)] flex flex-col items-center justify-center rounded-lg mr-6 flex-shrink-0 shadow-md">
                                <span class="text-2xl font-bold"><?php echo $date_obj->format('d'); ?></span>
                                <span class="text-sm"><?php echo $date_obj->format('M'); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Event Details -->
                        <div class="flex-1">
                            <!-- Event Type Badge -->
                            <?php if ($event_type) : ?>
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full mb-2 bg-white text-[rgba(0,0,40,1)] shadow-sm">
                                    <?php echo ucfirst($event_type); ?>
                                </span>
                            <?php endif; ?>

                            <!-- Event Title -->
                            <h3 class="text-xl font-semibold mb-2 text-black">
                                <a href="<?php the_permalink(); ?>" class="text-black hover:text-black transition-colors">
                                    <?php the_title(); ?>
                                </a>
                            </h3>
                            
                            <!-- Event Meta -->
                            <div class="text-sm text-[rgba(0,0,40,1)] space-y-1">
                                <?php if ($event_time) : ?>
                                    <p class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12,6 12,12 16,14"></polyline>
                                        </svg>
                                        <?php echo wp_kses_post($event_time); ?>
                                    </p>
                                <?php endif; ?>

                                <?php if ($event_location) : ?>
                                    <p class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <?php echo wp_kses_post($event_location); ?>
                                    </p>
                                <?php endif; ?>
                            </div>

                            <!-- Event Excerpt -->
                            <p class="text-[rgba(0,0,40,1)] text-sm mt-2 leading-relaxed">
                                <?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?>
                            </p>
                            
                            <!-- Action Buttons -->
                            <div class="flex gap-3 mt-4">
                                <a href="<?php the_permalink(); ?>"
                                   class="text-sm px-4 py-2 bg-white text-[rgba(0,0,40,1)] rounded-md font-semibold hover:bg-gray-100 transition-all shadow-sm">
                                    Learn More
                                </a>

                                <?php if ($is_upcoming && $registration_link) : ?>
                                    <a href="<?php echo esc_url($registration_link); ?>"
                                       target="_blank" rel="noopener noreferrer"
                                       class="text-sm px-4 py-2 bg-[rgba(0,0,40,1)] text-white rounded-md font-semibold hover:bg-opacity-90 transition-all shadow-sm">
                                        Register
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            
            <!-- View All Link -->
            <div class="text-center mt-12">
                <a href="<?php echo get_post_type_archive_link('events'); ?>" 
                   class="inline-flex items-center px-6 py-3 bg-[rgba(0,0,40,1)] text-white rounded-lg font-semibold hover:bg-opacity-90 transition-all">
                    View All Events
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
            
        <?php else : ?>
            <!-- No Events Found -->
            <div class="text-center py-12">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Events Found</h3>
                <p class="text-gray-600">
                    <?php echo $show_past_events ? 'There are currently no events to display.' : 'There are currently no upcoming events.'; ?>
                </p>
            </div>
        <?php endif; ?>
        
        <?php wp_reset_postdata(); ?>
    </div>
</section>

<?php if ($is_preview) : ?>
    <style>
        .events-cards-block {
            background: #f8fafc;
            border: 2px dashed #ccc;
            padding: 2rem;
        }
    </style>
<?php endif; ?>
