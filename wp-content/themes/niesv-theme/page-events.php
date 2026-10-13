<?php
/**
 * Template Name: Events Page
 * Template for Events page
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
            
            <!-- Featured Event -->
            <?php if (get_field('show_featured_event') && get_field('featured_event')) : 
                $featured_event = get_field('featured_event');
            ?>
                <div class="bg-gradient-to-r from-[var(--primary-color)] to-[var(--primary-color)]/80 rounded-2xl p-8 md:p-12 text-white mb-16">
                    <div class="grid md:grid-cols-2 gap-8 items-center">
                        <div>
                            <div class="inline-block bg-[var(--accent-gold)] text-[var(--primary-color)] px-4 py-2 rounded-full text-sm font-semibold mb-4">
                                Featured Event
                            </div>
                            <h2 class="text-3xl font-bold mb-4"><?php echo $featured_event->post_title; ?></h2>
                            <p class="text-lg opacity-90 mb-6"><?php echo wp_trim_words($featured_event->post_content, 30); ?></p>
                            
                            <?php 
                            $event_date = get_field('event_date', $featured_event->ID);
                            $event_location = get_field('event_location', $featured_event->ID);
                            ?>
                            
                            <div class="flex flex-col sm:flex-row gap-4 mb-6">
                                <?php if ($event_date) : ?>
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <?php echo date('F j, Y', strtotime($event_date)); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($event_location) : ?>
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <?php echo wp_kses_post($event_location); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <a href="<?php echo get_permalink($featured_event->ID); ?>" 
                               class="inline-block bg-[var(--accent-gold)] text-[var(--primary-color)] px-8 py-3 rounded-lg font-semibold hover:bg-opacity-90 transition-all duration-300">
                                Learn More
                            </a>
                        </div>
                        
                        <?php if (has_post_thumbnail($featured_event->ID)) : ?>
                            <div class="order-first md:order-last">
                                <img src="<?php echo get_the_post_thumbnail_url($featured_event->ID, 'large'); ?>" 
                                     alt="<?php echo $featured_event->post_title; ?>" 
                                     class="w-full h-64 object-cover rounded-lg">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Events Filter -->
            <div class="flex flex-wrap gap-4 mb-12 justify-center">
                <button class="event-filter-btn active px-6 py-2 rounded-full border-2 border-[var(--primary-color)] text-[var(--primary-color)] font-semibold transition-all duration-300" data-filter="all">
                    All Events
                </button>
                <button class="event-filter-btn px-6 py-2 rounded-full border-2 border-gray-300 text-gray-600 font-semibold hover:border-[var(--primary-color)] hover:text-[var(--primary-color)] transition-all duration-300" data-filter="upcoming">
                    Upcoming
                </button>
                <button class="event-filter-btn px-6 py-2 rounded-full border-2 border-gray-300 text-gray-600 font-semibold hover:border-[var(--primary-color)] hover:text-[var(--primary-color)] transition-all duration-300" data-filter="past">
                    Past Events
                </button>
            </div>
            
            <!-- Events Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8" id="events-grid">
                <?php
                // Get all events
                $events_query = new WP_Query(array(
                    'post_type' => 'events',
                    'posts_per_page' => -1,
                    'post_status' => 'publish',
                    'meta_key' => 'event_date',
                    'orderby' => 'meta_value',
                    'order' => 'ASC'
                ));

                if ($events_query->have_posts()) :
                    while ($events_query->have_posts()) : $events_query->the_post();
                        $event_date = get_field('event_date');
                        $event_location = get_field('event_location');
                        $event_time = get_field('event_time');
                        $is_upcoming = $event_date && strtotime($event_date) >= strtotime('today');
                        $day = $event_date ? date('j', strtotime($event_date)) : '';
                        $month = $event_date ? date('M', strtotime($event_date)) : '';
                ?>
                    <div class="event-card bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300" 
                         data-category="<?php echo $is_upcoming ? 'upcoming' : 'past'; ?>">
                        
                        <?php if (has_post_thumbnail()) : ?>
                            <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium'); ?>" 
                                 alt="<?php the_title(); ?>" 
                                 class="w-full h-48 object-cover">
                        <?php else : ?>
                            <div class="w-full h-48 bg-gradient-to-br from-[var(--primary-color)] to-[var(--primary-color)]/80 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        <?php endif; ?>
                        
                        <div class="p-6">
                            <?php if ($event_date) : ?>
                                <div class="flex items-center mb-4">
                                    <div class="bg-[var(--primary-color)] text-white rounded-lg p-3 text-center min-w-[60px] mr-4">
                                        <div class="text-lg font-bold"><?php echo wp_kses_post($day); ?></div>
                                        <div class="text-xs uppercase"><?php echo wp_kses_post($month); ?></div>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-black mb-1">
                                            <?php the_title(); ?>
                                        </h3>
                                        <?php if ($event_time) : ?>
                                            <p class="text-sm text-[var(--text-secondary)]"><?php echo wp_kses_post($event_time); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php else : ?>
                                <h3 class="text-lg font-bold text-black mb-4">
                                    <?php the_title(); ?>
                                </h3>
                            <?php endif; ?>
                            
                            <p class="text-[var(--text-secondary)] text-sm mb-4 leading-relaxed">
                                <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                            </p>
                            
                            <?php if ($event_location) : ?>
                                <div class="flex items-center text-sm text-[var(--text-secondary)] mb-4">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <?php echo wp_kses_post($event_location); ?>
                                </div>
                            <?php endif; ?>
                            
                            <a href="<?php the_permalink(); ?>" 
                               class="text-[var(--primary-color)] hover:text-[var(--accent-gold)] font-semibold text-sm inline-flex items-center transition-colors duration-300">
                                Learn More
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                <?php 
                    endwhile;
                    wp_reset_postdata();
                else : 
                ?>
                    <div class="col-span-full text-center py-12">
                        <p class="text-[var(--text-secondary)] text-lg">No events found.</p>
                    </div>
                <?php endif; ?>
            </div>
            
        <?php endwhile; ?>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.event-filter-btn');
    const eventCards = document.querySelectorAll('.event-card');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Update active button
            filterBtns.forEach(b => {
                b.classList.remove('active', 'bg-[var(--primary-color)]', 'text-white');
                b.classList.add('border-gray-300', 'text-gray-600');
            });
            this.classList.add('active', 'bg-[var(--primary-color)]', 'text-white');
            this.classList.remove('border-gray-300', 'text-gray-600');
            
            // Filter events
            eventCards.forEach(card => {
                if (filter === 'all' || card.getAttribute('data-category') === filter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});
</script>

<?php get_footer(); ?>
