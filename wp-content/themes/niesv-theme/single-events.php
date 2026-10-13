<?php
/**
 * Template for single event
 *
 * @package NIESV_Theme
 */

get_header(); ?>

<?php while (have_posts()) : the_post(); 
    $event_date = get_field('event_date');
    $event_time = get_field('event_time');
    $event_location = get_field('event_location');
    $event_type = get_field('event_type');
    $registration_link = get_field('registration_link');
    $event_organizer = get_field('event_organizer');
    $event_capacity = get_field('event_capacity');
    $event_price = get_field('event_price');
    
    $date_obj = $event_date ? DateTime::createFromFormat('Y-m-d', $event_date) : null;
    $is_upcoming = $date_obj && $date_obj >= new DateTime();
?>

<main class="flex-1">
    <!-- Event Hero Section -->
    <section class="relative py-20 bg-gradient-to-r from-[var(--primary-color)] to-[var(--text-secondary)]">
        <?php if (has_post_thumbnail()) : ?>
            <div class="absolute inset-0 bg-cover bg-center opacity-20" 
                 style="background-image: url('<?php echo get_the_post_thumbnail_url(get_the_ID(), 'hero-image'); ?>')">
            </div>
        <?php endif; ?>
        
        <div class="relative container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center text-white">
                
                <!-- Event Type Badge -->
                <?php if ($event_type) : ?>
                    <span class="inline-block px-4 py-2 bg-[var(--accent-gold)] text-[var(--primary-color)] rounded-full text-sm font-semibold mb-4">
                        <?php echo ucfirst($event_type); ?>
                    </span>
                <?php endif; ?>
                
                <!-- Event Title -->
                <h1 class="text-4xl md:text-5xl font-extrabold mb-6 leading-tight">
                    <?php the_title(); ?>
                </h1>
                
                <!-- Event Meta -->
                <div class="flex flex-wrap justify-center gap-6 text-lg mb-8">
                    <?php if ($date_obj) : ?>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <?php echo $date_obj->format('l, F j, Y'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($event_time) : ?>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12,6 12,12 16,14"></polyline>
                            </svg>
                            <?php echo $event_time; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($event_location) : ?>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <?php echo $event_location; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Registration Button -->
                <?php if ($is_upcoming && $registration_link) : ?>
                    <a href="<?php echo $registration_link; ?>" 
                       target="_blank" rel="noopener noreferrer"
                       class="inline-block px-8 py-4 bg-[var(--accent-gold)] text-[var(--primary-color)] rounded-lg text-lg font-bold hover:bg-opacity-90 transition-all shadow-lg">
                        Register Now
                    </a>
                <?php elseif (!$is_upcoming) : ?>
                    <span class="inline-block px-8 py-4 bg-gray-600 text-white rounded-lg text-lg font-bold">
                        Event Completed
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </section>
    
    <!-- Event Content -->
    <section class="py-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-12">
                
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <div class="prose prose-lg max-w-none">
                        <?php the_content(); ?>
                    </div>
                    
                    <!-- Event Gallery -->
                    <?php if (have_rows('event_gallery')) : ?>
                        <div class="mt-12">
                            <h3 class="text-2xl font-bold text-[var(--primary-color)] mb-6">Event Gallery</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <?php while (have_rows('event_gallery')) : the_row(); 
                                    $image = get_sub_field('image');
                                ?>
                                    <div class="aspect-square bg-cover bg-center rounded-lg shadow-md hover:shadow-lg transition-shadow cursor-pointer" 
                                         style="background-image: url('<?php echo $image['sizes']['medium']; ?>')"
                                         onclick="openLightbox('<?php echo $image['sizes']['large']; ?>')">
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Event Agenda -->
                    <?php if (have_rows('event_agenda')) : ?>
                        <div class="mt-12">
                            <h3 class="text-2xl font-bold text-[var(--primary-color)] mb-6">Event Agenda</h3>
                            <div class="space-y-4">
                                <?php while (have_rows('event_agenda')) : the_row(); ?>
                                    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-[var(--accent-gold)]">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <h4 class="text-lg font-semibold text-[var(--primary-color)] mb-2">
                                                    <?php echo get_sub_field('session_title'); ?>
                                                </h4>
                                                <p class="text-[var(--text-secondary)] mb-2">
                                                    <?php echo get_sub_field('session_description'); ?>
                                                </p>
                                                <?php if (get_sub_field('speaker_name')) : ?>
                                                    <p class="text-sm text-[var(--text-secondary)]">
                                                        <strong>Speaker:</strong> <?php echo get_sub_field('speaker_name'); ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                            <div class="ml-4 text-right">
                                                <span class="bg-[var(--primary-color)] text-white px-3 py-1 rounded-full text-sm font-semibold">
                                                    <?php echo get_sub_field('session_time'); ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="sticky top-8 space-y-8">
                        
                        <!-- Event Details Card -->
                        <div class="bg-white p-6 rounded-lg shadow-lg">
                            <h3 class="text-xl font-bold text-[var(--primary-color)] mb-4">Event Details</h3>
                            
                            <div class="space-y-4">
                                <?php if ($date_obj) : ?>
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 mr-3 mt-1 text-[var(--primary-color)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <div>
                                            <p class="font-semibold">Date</p>
                                            <p class="text-[var(--text-secondary)]"><?php echo $date_obj->format('l, F j, Y'); ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($event_time) : ?>
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 mr-3 mt-1 text-[var(--primary-color)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12,6 12,12 16,14"></polyline>
                                        </svg>
                                        <div>
                                            <p class="font-semibold">Time</p>
                                            <p class="text-[var(--text-secondary)]"><?php echo $event_time; ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($event_location) : ?>
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 mr-3 mt-1 text-[var(--primary-color)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <div>
                                            <p class="font-semibold">Location</p>
                                            <p class="text-[var(--text-secondary)]"><?php echo $event_location; ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($event_organizer) : ?>
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 mr-3 mt-1 text-[var(--primary-color)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <div>
                                            <p class="font-semibold">Organizer</p>
                                            <p class="text-[var(--text-secondary)]"><?php echo $event_organizer; ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($event_capacity) : ?>
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 mr-3 mt-1 text-[var(--primary-color)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        <div>
                                            <p class="font-semibold">Capacity</p>
                                            <p class="text-[var(--text-secondary)]"><?php echo $event_capacity; ?> attendees</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($event_price) : ?>
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 mr-3 mt-1 text-[var(--primary-color)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                        <div>
                                            <p class="font-semibold">Price</p>
                                            <p class="text-[var(--text-secondary)]">
                                                <?php echo $event_price === '0' || $event_price === 'free' ? 'Free' : '₦' . number_format($event_price); ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Registration Button -->
                            <?php if ($is_upcoming && $registration_link) : ?>
                                <div class="mt-6">
                                    <a href="<?php echo $registration_link; ?>" 
                                       target="_blank" rel="noopener noreferrer"
                                       class="block w-full text-center py-3 px-4 bg-[var(--primary-color)] text-white rounded-md font-semibold hover:bg-opacity-90 transition-all">
                                        Register for Event
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Share Event -->
                        <?php niesv_social_share(array('title' => 'Share Event')); ?>
                        
                        <!-- Related Events -->
                        <?php
                        $related_events = new WP_Query(array(
                            'post_type' => 'events',
                            'posts_per_page' => 3,
                            'post__not_in' => array(get_the_ID()),
                            'meta_key' => 'event_date',
                            'orderby' => 'meta_value',
                            'order' => 'ASC',
                            'meta_query' => array(
                                array(
                                    'key' => 'event_date',
                                    'value' => date('Y-m-d'),
                                    'compare' => '>='
                                )
                            )
                        ));
                        
                        if ($related_events->have_posts()) :
                        ?>
                            <div class="bg-white p-6 rounded-lg shadow-lg">
                                <h3 class="text-xl font-bold text-[var(--primary-color)] mb-4">Upcoming Events</h3>
                                <div class="space-y-4">
                                    <?php while ($related_events->have_posts()) : $related_events->the_post(); 
                                        $related_date = get_field('event_date');
                                        $related_date_obj = $related_date ? DateTime::createFromFormat('Y-m-d', $related_date) : null;
                                    ?>
                                        <div class="border-b border-gray-200 pb-4 last:border-b-0 last:pb-0">
                                            <h4 class="font-semibold text-sm mb-1">
                                                <a href="<?php the_permalink(); ?>" class="hover:text-[var(--primary-color)] transition-colors">
                                                    <?php the_title(); ?>
                                                </a>
                                            </h4>
                                            <?php if ($related_date_obj) : ?>
                                                <p class="text-xs text-[var(--text-secondary)]">
                                                    <?php echo $related_date_obj->format('M j, Y'); ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    <?php endwhile; wp_reset_postdata(); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php endwhile; ?>

<?php get_footer(); ?>
