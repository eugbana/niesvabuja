<?php
/**
 * Template for Events Archive
 *
 * @package NIESV_Theme
 */

get_header(); ?>

<?php
// Get hero section data
$hero_args = array(
    'title' => get_field('events_page_title', 'option') ?: 'Events & Conferences',
    'subtitle' => get_field('events_page_subtitle', 'option') ?: 'Stay updated with our upcoming events, workshops, and conferences.',
    'background_image' => get_field('events_hero_background', 'option'),
    'overlay_color' => 'rgba(30, 58, 138, 0.8)',
    'size' => 'medium',
    'show_breadcrumb' => true,
);

// Include hero section
get_template_part('template-parts/hero-section', null, $hero_args);
?>

<main class="flex-1">
    
    <!-- Events Filter Section -->
    <section class="py-8 bg-[var(--secondary-color)]">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap justify-center gap-4">
                <button class="event-filter-btn active px-6 py-2 rounded-full bg-[var(--primary-color)] text-white font-medium transition-all hover:bg-opacity-90" data-filter="all">
                    All Events
                </button>
                <button class="event-filter-btn px-6 py-2 rounded-full bg-white text-[var(--primary-color)] font-medium border border-[var(--primary-color)] transition-all hover:bg-[var(--primary-color)] hover:text-white" data-filter="upcoming">
                    Upcoming
                </button>
                <button class="event-filter-btn px-6 py-2 rounded-full bg-white text-[var(--primary-color)] font-medium border border-[var(--primary-color)] transition-all hover:bg-[var(--primary-color)] hover:text-white" data-filter="past">
                    Past Events
                </button>
                <button class="event-filter-btn px-6 py-2 rounded-full bg-white text-[var(--primary-color)] font-medium border border-[var(--primary-color)] transition-all hover:bg-[var(--primary-color)] hover:text-white" data-filter="workshops">
                    Workshops
                </button>
                <button class="event-filter-btn px-6 py-2 rounded-full bg-white text-[var(--primary-color)] font-medium border border-[var(--primary-color)] transition-all hover:bg-[var(--primary-color)] hover:text-white" data-filter="conferences">
                    Conferences
                </button>
            </div>
        </div>
    </section>
    
    <!-- Events Grid -->
    <section class="py-12 md:py-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            
            <?php if (have_posts()) : ?>
                <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3" id="events-grid">
                    <?php while (have_posts()) : the_post(); 
                        $event_date = get_field('event_date');
                        $event_time = get_field('event_time');
                        $event_location = get_field('event_location');
                        $event_type = get_field('event_type');
                        $is_featured = get_field('is_featured_event');
                        
                        $date_obj = $event_date ? DateTime::createFromFormat('Y-m-d', $event_date) : null;
                        $is_upcoming = $date_obj && $date_obj >= new DateTime();
                        
                        $event_classes = 'event-card';
                        $event_classes .= $is_upcoming ? ' upcoming' : ' past';
                        $event_classes .= $event_type ? ' ' . sanitize_html_class($event_type) : '';
                    ?>
                        <article class="<?php echo esc_attr($event_classes); ?> bg-white rounded-xl shadow-lg overflow-hidden group transform hover:-translate-y-2 transition-all duration-300 <?php echo $is_featured ? 'ring-2 ring-[var(--accent-gold)]' : ''; ?>">
                            
                            <?php if ($is_featured) : ?>
                                <div class="bg-[var(--accent-gold)] text-[var(--primary-color)] text-center py-2 px-4 font-semibold text-sm">
                                    Featured Event
                                </div>
                            <?php endif; ?>
                            
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="event-image w-full h-48 bg-cover bg-center transition-transform duration-300" 
                                     style="background-image: url('<?php echo get_the_post_thumbnail_url(get_the_ID(), 'event-thumbnail'); ?>')">
                                </div>
                            <?php endif; ?>
                            
                            <div class="p-6">
                                <!-- Event Date -->
                                <?php if ($date_obj) : ?>
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-16 h-16 bg-[var(--primary-color)] text-white flex flex-col items-center justify-center rounded-lg">
                                            <span class="text-2xl font-bold"><?php echo $date_obj->format('d'); ?></span>
                                            <span class="text-xs"><?php echo $date_obj->format('M'); ?></span>
                                        </div>
                                        <div>
                                            <p class="text-sm text-[var(--text-secondary)]">
                                                <?php echo $date_obj->format('l, F j, Y'); ?>
                                            </p>
                                            <?php if ($event_time) : ?>
                                                <p class="text-sm text-[var(--text-secondary)]">
                                                    <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <circle cx="12" cy="12" r="10"></circle>
                                                        <polyline points="12,6 12,12 16,14"></polyline>
                                                    </svg>
                                                    <?php echo wp_kses_post($event_time); ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Event Title -->
                                <h3 class="text-xl font-bold mb-3 text-black">
                                    <a href="<?php the_permalink(); ?>" class="text-black hover:text-black"><?php the_title(); ?></a>
                                </h3>
                                
                                <!-- Event Location -->
                                <?php if ($event_location) : ?>
                                    <p class="text-sm text-[var(--text-secondary)] mb-3 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <?php echo wp_kses_post($event_location); ?>
                                    </p>
                                <?php endif; ?>
                                
                                <!-- Event Excerpt -->
                                <p class="text-[var(--text-secondary)] mb-4 text-sm leading-relaxed">
                                    <?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?>
                                </p>
                                
                                <!-- Event Type Badge -->
                                <?php if ($event_type) : ?>
                                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full mb-4 
                                        <?php echo $event_type === 'workshop' ? 'bg-blue-100 text-blue-800' : 
                                                   ($event_type === 'conference' ? 'bg-green-100 text-green-800' : 
                                                   ($event_type === 'seminar' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800')); ?>">
                                        <?php echo ucfirst($event_type); ?>
                                    </span>
                                <?php endif; ?>
                                
                                <!-- Action Buttons -->
                                <div class="flex gap-3">
                                    <a href="<?php the_permalink(); ?>" 
                                       class="flex-1 text-center py-2 px-4 bg-[var(--primary-color)] text-white rounded-md text-sm font-semibold hover:bg-opacity-90 transition-all">
                                        Learn More
                                    </a>
                                    
                                    <?php if ($is_upcoming && get_field('registration_link')) : ?>
                                        <a href="<?php echo esc_url(get_field('registration_link')); ?>" 
                                           target="_blank" rel="noopener noreferrer"
                                           class="py-2 px-4 bg-[var(--accent-gold)] text-[var(--primary-color)] rounded-md text-sm font-semibold hover:bg-opacity-90 transition-all">
                                            Register
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
                
                <!-- Pagination -->
                <?php niesv_pagination(null, 'Events navigation'); ?>
                
            <?php else : ?>
                <!-- No Events Found -->
                <div class="text-center py-16">
                    <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="text-2xl font-semibold text-gray-900 mb-2">No Events Found</h3>
                    <p class="text-gray-600 mb-6">There are currently no events scheduled. Please check back later.</p>
                    <a href="<?php echo home_url(); ?>" class="btn-primary">
                        Return to Homepage
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<!-- Events Filter JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.event-filter-btn');
    const eventCards = document.querySelectorAll('.event-card');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active', 'bg-[var(--primary-color)]', 'text-white'));
            filterButtons.forEach(btn => btn.classList.add('bg-white', 'text-[var(--primary-color)]'));
            
            this.classList.add('active', 'bg-[var(--primary-color)]', 'text-white');
            this.classList.remove('bg-white', 'text-[var(--primary-color)]');
            
            // Filter events
            eventCards.forEach(card => {
                if (filter === 'all') {
                    card.style.display = 'block';
                } else if (filter === 'upcoming' && card.classList.contains('upcoming')) {
                    card.style.display = 'block';
                } else if (filter === 'past' && card.classList.contains('past')) {
                    card.style.display = 'block';
                } else if (card.classList.contains(filter)) {
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
