<?php
/**
 * The main template file - Matches original index.html structure exactly
 *
 * @package NIESV_Theme
 */

get_header(); ?>

<main class="flex-1">
    <!-- Hero Section with Carousel Support -->
    <?php
    // Get front page ID - try multiple methods
    $front_page_id = get_option('page_on_front');
    if (!$front_page_id) {
        $front_page_id = get_queried_object_id();
    }

    $hero_slides = get_field('hero_slides', $front_page_id);
    $has_multiple_slides = $hero_slides && count($hero_slides) > 1;
    ?>

    <section class="relative min-h-[600px] flex items-center justify-center bg-cover bg-center hero-carousel-container">
        <?php if ($hero_slides && !empty($hero_slides)) : ?>
            <?php foreach ($hero_slides as $index => $slide) : ?>
                <?php
                $bg_image = $slide['slide_background_image'];
                $active_class = $index === 0 ? 'active' : '';

                // Ensure we have required data
                $slide_title = !empty($slide['slide_title']) ? $slide['slide_title'] : 'NIESV Abuja Branch';
                $slide_subtitle = !empty($slide['slide_subtitle']) ? $slide['slide_subtitle'] : 'Setting the standard for estate surveying and valuation in the Federal Capital Territory.';
                $bg_image_url = !empty($bg_image['url']) ? $bg_image['url'] : 'https://lh3.googleusercontent.com/aida-public/AB6AXuBKDf3P5asmG-v4EY8nAU9L0H5PZUiJ0YNgPybkSNq3irU15GRrcsB5XncMNTjAUq3nwakDyylES6YwIA_4VcbPjr6AMa5wdryZ9T5G3QnyhToDnXC9FWwcG9-MC6BnU8DaMslFruUrSEzK5Orq8Ha80DQlPU02chZPuo9GD4fEWD8kNAFdZzaf729thiK_t1qRoYzg4e4DVAvG9yE7CVEEl0gDxkMLGCNl6W4miEOkryLEGdODEFL3ViX-SGwDYUcsVnIq1GZtqpl7';
                ?>
                <div class="hero-slide <?php echo esc_attr($active_class); ?> absolute inset-0 w-full h-full flex items-center justify-center bg-cover bg-center transition-opacity duration-1000"
                     style='background-image: linear-gradient(rgba(0, 0, 40, 0.6) 0%, rgba(0, 0, 40, 0.9) 100%), url("<?php echo esc_url($bg_image_url); ?>");'>
                    <div class="text-center text-white px-4">
                        <h1 class="hero-title text-6xl md:text-7xl font-extrabold leading-tight tracking-tighter mb-5">
                            <?php echo esc_html($slide_title); ?>
                        </h1>
                        <p class="hero-subtitle text-xl md:text-2xl max-w-4xl mx-auto font-light">
                            <?php echo esc_html($slide_subtitle); ?>
                        </p>
                        <?php if (!empty($slide['slide_button_text'])) : ?>
                            <a href="<?php echo esc_url($slide['slide_button_link'] ?: '#'); ?>" class="mt-10 inline-flex cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-6 bg-[var(--accent-gold)] text-[var(--primary-color)] text-lg font-bold leading-normal tracking-wide hover:bg-opacity-90 transition-all shadow-lg">
                                <span class="truncate"><?php echo esc_html($slide['slide_button_text']); ?></span>
                            </a>
                        <?php else : ?>
                            <button class="mt-10 inline-flex cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-6 bg-[var(--accent-gold)] text-[var(--primary-color)] text-lg font-bold leading-normal tracking-wide hover:bg-opacity-90 transition-all shadow-lg">
                                <span class="truncate">Explore Our World</span>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <!-- Default slide if no ACF data - exact same structure as before -->
            <div class="hero-slide active absolute inset-0 w-full h-full flex items-center justify-center bg-cover bg-center"
                 style='background-image: linear-gradient(rgba(0, 0, 40, 0.6) 0%, rgba(0, 0, 40, 0.9) 100%), url("https://lh3.googleusercontent.com/aida-public/AB6AXuBKDf3P5asmG-v4EY8nAU9L0H5PZUiJ0YNgPybkSNq3irU15GRrcsB5XncMNTjAUq3nwakDyylES6YwIA_4VcbPjr6AMa5wdryZ9T5G3QnyhToDnXC9FWwcG9-MC6BnU8DaMslFruUrSEzK5Orq8Ha80DQlPU02chZPuo9GD4fEWD8kNAFdZzaf729thiK_t1qRoYzg4e4DVAvG9yE7CVEEl0gDxkMLGCNl6W4miEOkryLEGdODEFL3ViX-SGwDYUcsVnIq1GZtqpl7");'>
                <div class="text-center text-white px-4">
                    <h1 class="hero-title text-6xl md:text-7xl font-extrabold leading-tight tracking-tighter mb-5">
                        NIESV Abuja Branch
                    </h1>
                    <p class="hero-subtitle text-xl md:text-2xl max-w-4xl mx-auto font-light">
                        Setting the standard for estate surveying and valuation in the Federal Capital Territory.
                    </p>
                    <button class="mt-10 inline-flex cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-6 bg-[var(--accent-gold)] text-[var(--primary-color)] text-lg font-bold leading-normal tracking-wide hover:bg-opacity-90 transition-all shadow-lg">
                        <span class="truncate">Explore Our World</span>
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <!-- Carousel Navigation - Only show if multiple slides -->
        <?php if ($has_multiple_slides) : ?>
            <!-- Carousel Dots -->
            <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex space-x-3">
                <?php foreach ($hero_slides as $index => $slide) : ?>
                    <button class="carousel-dot w-3 h-3 rounded-full bg-white/50 hover:bg-white/80 transition-all duration-300 <?php echo $index === 0 ? 'active bg-white' : ''; ?>"
                            data-slide="<?php echo esc_attr($index); ?>"></button>
                <?php endforeach; ?>
            </div>

            <!-- Carousel Arrows -->
            <button class="carousel-prev absolute left-4 top-1/2 transform -translate-y-1/2 w-12 h-12 bg-white/20 hover:bg-white/40 rounded-full flex items-center justify-center text-white transition-all duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button class="carousel-next absolute right-4 top-1/2 transform -translate-y-1/2 w-12 h-12 bg-white/20 hover:bg-white/40 rounded-full flex items-center justify-center text-white transition-all duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        <?php endif; ?>
    </section>

    <!-- Important Updates Section -->
    <?php
    $show_updates = get_field('show_updates_section', $front_page_id);
    if ($show_updates) :
        $updates_title = get_field('updates_title', $front_page_id) ?: 'Important Updates';
        $update_headline = get_field('update_headline', $front_page_id);
        $update_description = get_field('update_description', $front_page_id);
        $update_button_text = get_field('update_button_text', $front_page_id);
        $update_button_link = get_field('update_button_link', $front_page_id);
    ?>
        <section class="bg-[var(--secondary-color)] py-16 px-4 md:px-10 lg:px-20">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold tracking-tight text-[var(--primary-color)] inline-flex items-center gap-3">
                    <svg class="w-10 h-10 text-[var(--accent-gold)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                    </svg>
                    <?php echo wp_kses_post($updates_title); ?>
                </h2>
            </div>

            <?php if ($update_headline || $update_description) : ?>
                <div class="max-w-5xl mx-auto bg-white p-8 rounded-xl shadow-2xl border-l-8 border-[var(--accent-gold)]">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                        <div class="md:col-span-2">
                            <?php if ($update_headline) : ?>
                                <h3 class="text-3xl font-bold text-[var(--primary-color)] mb-4">
                                    <?php echo wp_kses_post($update_headline); ?>
                                </h3>
                            <?php endif; ?>

                            <?php if ($update_description) : ?>
                                <div class="text-[var(--text-secondary)] text-lg">
                                    <?php echo wp_kses_post($update_description); ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if ($update_button_text) : ?>
                            <div class="text-center">
                                <a class="inline-block min-w-[160px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-14 px-8 bg-[var(--primary-color)] text-white text-xl font-bold leading-normal tracking-wide hover:bg-opacity-90 transition-all shadow-lg"
                                   href="<?php echo esc_url($update_button_link ?: '#'); ?>">
                                    <?php echo wp_kses_post($update_button_text); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else : ?>
                <!-- Default content if no ACF data -->
                <div class="max-w-5xl mx-auto bg-white p-8 rounded-xl shadow-2xl border-l-8 border-[var(--accent-gold)]">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                        <div class="md:col-span-2">
                            <h3 class="text-3xl font-bold text-[var(--primary-color)] mb-4">Mandatory Professional Development Course</h3>
                            <p class="text-[var(--text-secondary)] text-lg mb-4">All members are hereby notified of the upcoming Mandatory Continuing Professional Development (MCPD) course scheduled for August 2024. This is a prerequisite for the renewal of practicing licenses.</p>
                            <p class="text-[var(--text-secondary)] text-lg">Please ensure you register on time to secure your spot. Further details on the venue and specific dates will be communicated shortly.</p>
                        </div>
                        <div class="text-center">
                            <a class="inline-block min-w-[160px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-14 px-8 bg-[var(--primary-color)] text-white text-xl font-bold leading-normal tracking-wide hover:bg-opacity-90 transition-all shadow-lg" href="#">Register Now</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </section>
    <?php endif; ?>

    <!-- Featured Event Countdown Section -->
    <?php
    // Get featured events
    $featured_events = get_posts(array(
        'post_type' => 'events',
        'posts_per_page' => 1,
        'meta_query' => array(
            array(
                'key' => 'featured_event',
                'value' => '1',
                'compare' => '='
            )
        ),
        'meta_key' => 'event_datetime',
        'orderby' => 'meta_value',
        'order' => 'ASC'
    ));

    if ($featured_events) :
        $event = $featured_events[0];
        $event_title = get_the_title($event->ID);
        $event_datetime = get_field('event_datetime', $event->ID);
        $event_location = get_field('event_location', $event->ID);
        $event_type = get_field('event_type', $event->ID);
        $registration_link = get_field('registration_link', $event->ID);

        // Check if event is in the future
        if ($event_datetime && strtotime($event_datetime) > time()) :
    ?>
        <section class="bg-[var(--primary-color)] py-16 px-4 md:px-10 lg:px-20 text-white">
            <div class="container mx-auto text-center">
                <div class="mb-8">
                    <span class="inline-block px-4 py-2 bg-white/20 rounded-full text-sm font-semibold uppercase tracking-wide mb-4">
                        <?php echo esc_html(ucfirst($event_type)); ?> Event
                    </span>
                    <h2 class="text-3xl md:text-4xl font-bold mb-4 text-white"><?php echo esc_html($event_title); ?></h2>
                    <?php if ($event_location) : ?>
                        <p class="text-lg opacity-90 mb-6">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <?php echo esc_html($event_location); ?>
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Countdown Timer -->
                <div class="mb-8">
                    <div class="flex flex-wrap justify-center gap-4 md:gap-8" id="countdown-timer" data-event-date="<?php echo esc_attr($event_datetime); ?>">
                        <div class="bg-white/20 rounded-lg p-4 min-w-[80px]">
                            <div class="text-2xl md:text-3xl font-bold" id="days">00</div>
                            <div class="text-sm uppercase tracking-wide">Days</div>
                        </div>
                        <div class="bg-white/20 rounded-lg p-4 min-w-[80px]">
                            <div class="text-2xl md:text-3xl font-bold" id="hours">00</div>
                            <div class="text-sm uppercase tracking-wide">Hours</div>
                        </div>
                        <div class="bg-white/20 rounded-lg p-4 min-w-[80px]">
                            <div class="text-2xl md:text-3xl font-bold" id="minutes">00</div>
                            <div class="text-sm uppercase tracking-wide">Minutes</div>
                        </div>
                        <div class="bg-white/20 rounded-lg p-4 min-w-[80px]">
                            <div class="text-2xl md:text-3xl font-bold" id="seconds">00</div>
                            <div class="text-sm uppercase tracking-wide">Seconds</div>
                        </div>
                    </div>
                </div>

                <?php if ($registration_link) : ?>
                    <div class="text-center">
                        <a href="<?php echo esc_url($registration_link); ?>"
                           class="inline-flex items-center justify-center min-w-[180px] h-14 px-8 bg-white text-[var(--primary-color)] text-xl font-bold rounded-lg hover:bg-gray-100 transition-all shadow-lg"
                           target="_blank">
                            Visit Event
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php
        endif;
    endif;
    ?>

    <!-- Custom Content Section -->
    <?php
    $show_custom_content = get_field('show_custom_content_section', $front_page_id);
    if ($show_custom_content) :
        $custom_content_title = get_field('custom_content_title', $front_page_id);
        $custom_content_wysiwyg = get_field('custom_content_wysiwyg', $front_page_id);
        $custom_content_image = get_field('custom_content_image', $front_page_id);
        $custom_content_link = get_field('custom_content_link', $front_page_id);
    ?>
        <section class="py-16 px-4 md:px-10 lg:px-20 bg-gray-50">
            <div class="max-w-7xl mx-auto">
                <?php if ($custom_content_title) : ?>
                    <div class="text-center mb-12">
                        <h2 class="text-4xl font-bold tracking-tight text-[var(--primary-color)] border-b-4 border-[var(--accent-gold)] inline-block pb-2">
                            <?php echo esc_html($custom_content_title); ?>
                        </h2>
                    </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <!-- Content Column -->
                    <div class="order-2 lg:order-1">
                        <?php if ($custom_content_wysiwyg) : ?>
                            <div class="wysiwyg-content text-[var(--text-secondary)] mb-8">
                                <?php echo apply_filters('the_content', $custom_content_wysiwyg); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($custom_content_link && !empty($custom_content_link['url'])) : ?>
                            <div class="mt-6">
                                <a href="<?php echo esc_url($custom_content_link['url']); ?>"
                                   class="inline-flex items-center justify-center px-8 py-3 bg-[var(--primary-color)] text-white text-lg font-semibold rounded-lg hover:bg-opacity-90 transition-all duration-300 shadow-lg hover:shadow-xl"
                                   <?php if ($custom_content_link['target']) : ?>target="<?php echo esc_attr($custom_content_link['target']); ?>"<?php endif; ?>>
                                    <?php echo esc_html($custom_content_link['title'] ?: 'Read More'); ?>
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Image Column -->
                    <div class="order-1 lg:order-2">
                        <?php if ($custom_content_image) : ?>
                            <div class="relative">
                                <img src="<?php echo esc_url($custom_content_image['url']); ?>"
                                     alt="<?php echo esc_attr($custom_content_image['alt'] ?: $custom_content_title); ?>"
                                     class="w-full h-auto rounded-lg shadow-lg object-cover">
                            </div>
                        <?php else : ?>
                            <!-- Placeholder if no image is set -->
                            <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                                <span class="text-gray-500 text-lg">Image placeholder</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Meet Our Team Section -->
    <section class="py-20 px-4 md:px-10 lg:px-20">
        <div class="mb-12">
            <h2 class="text-4xl font-bold tracking-tight mb-2 text-[var(--primary-color)] border-b-4 border-[var(--accent-gold)] inline-block pb-2">
                <?php echo wp_kses_post(get_field('team_section_title', $front_page_id) ?: 'Meet Our Team'); ?>
            </h2>
        </div>

        <!-- Team Container -->
        <div class="relative">
            <!-- Team Slider Container -->
            <div class="team-slider-container relative overflow-hidden">
                <div class="team-slider flex transition-transform duration-500 ease-in-out" id="team-slider">

                    <?php
                    // Get team members
                    $team_count = get_field('team_members_count', $front_page_id) ?: 4;
                    $show_leadership_only = get_field('show_leadership_only', $front_page_id);

                    $team_query_args = array(
                        'post_type' => 'team_members',
                        'posts_per_page' => $team_count,
                        'post_status' => 'publish',
                        'orderby' => 'menu_order',
                        'order' => 'ASC'
                    );

                    if ($show_leadership_only) {
                        $team_query_args['meta_query'] = array(
                            array(
                                'key' => 'is_leadership',
                                'value' => '1',
                                'compare' => '='
                            )
                        );
                    }

                    $team_query = new WP_Query($team_query_args);

            if ($team_query->have_posts()) :
                while ($team_query->have_posts()) : $team_query->the_post();
                    $member_name = get_field('member_name') ?: get_the_title();
                    $member_position = get_field('member_position');
                    $profile_image = get_field('member_profile_image');
            ?>
                    <div class="team-slide flex-shrink-0 w-64 text-center group px-4">
                        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                            <?php if ($profile_image) : ?>
                                <div class="w-32 h-32 rounded-full mx-auto mb-4 bg-gray-200 flex items-center justify-center overflow-hidden">
                                    <img src="<?php echo esc_url($profile_image['sizes']['medium'] ?? $profile_image['url']); ?>" alt="<?php echo esc_attr($member_name); ?>" class="w-full h-full object-cover">
                                </div>
                            <?php else : ?>
                                <div class="w-32 h-32 rounded-full mx-auto mb-4 bg-gray-200 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A6 6 0 0112 15h0a6 6 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                            <?php endif; ?>
                            <h3 class="text-lg font-bold text-[var(--primary-color)]"><?php echo esc_html($member_name); ?></h3>
                            <?php if ($member_position) : ?>
                                <p class="text-sm text-[var(--text-secondary)] mt-1"><?php echo esc_html($member_position); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
            <?php endwhile; wp_reset_postdata(); else : ?>
                    <div class="text-center group flex-shrink-0 w-48">
                        <div class="w-32 h-32 rounded-full mx-auto mb-4 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-400">No team</span>
                        </div>
                        <h3 class="text-lg font-bold">Member</h3>
                        <p class="text-sm text-gray-600">Position</p>
                    </div>
            <?php endif; ?>
                </div>

            </div>

            <!-- Slider Dots -->
            <div class="flex justify-center mt-6 space-x-2" id="team-slider-dots">
                <!-- Dots will be generated by JavaScript -->
            </div>
        </div>
    </section>

    <!-- Counter Stats Section -->
    <?php if (get_field('show_counters_section', $front_page_id)) :
        $bg = get_field('counters_background_image', $front_page_id);
        $counters = get_field('front_counters', $front_page_id);
    ?>
    <section class="relative py-16 md:py-24 text-white <?php echo $bg ? 'bg-cover bg-center' : 'bg-[var(--primary-color)]'; ?>" <?php if($bg) : ?>style="background-image:url('<?php echo esc_url($bg['url']); ?>')"<?php endif; ?>>
        <div class="absolute inset-0 bg-black/50"></div>
        <div class="relative container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap justify-center items-center gap-12 md:gap-16 lg:gap-20">
                <?php foreach ($counters as $counter) : ?>
                <div class="text-center min-w-[200px]">
                    <div class="w-28 h-28 mx-auto mb-6 rounded-full bg-white/20 flex items-center justify-center border border-white/40 shadow-lg">
                        <?php if (!empty($counter['icon'])) : ?>
                            <img src="<?php echo esc_url($counter['icon']['sizes']['thumbnail'] ?? $counter['icon']['url']); ?>" alt="" class="w-12 h-12 object-contain">
                        <?php else : ?>
                            <svg class="w-12 h-12 text-[var(--accent-gold)]" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16z"/></svg>
                        <?php endif; ?>
                    </div>
                    <div class="text-5xl md:text-6xl font-extrabold mb-2">
                        <span class="counter-value" data-target="<?php echo intval($counter['value'] ?? 0); ?>">0</span>
                    </div>
                    <div class="text-base font-semibold opacity-90 uppercase tracking-wide"><?php echo esc_html($counter['label'] ?? ''); ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Feature Cards Section (New Component) -->
    <?php if (get_field('show_feature_cards_section', $front_page_id)) :
        $feature_cards_title = get_field('feature_cards_title', $front_page_id);
        $feature_cards = get_field('feature_cards', $front_page_id);
    ?>
    <section class="py-16 px-4 md:px-10 lg:px-20 bg-white">
        <?php if ($feature_cards_title) : ?>
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold tracking-tight mb-2 text-[var(--primary-color)] border-b-4 border-[var(--accent-gold)] inline-block pb-2"><?php echo esc_html($feature_cards_title); ?></h2>
        </div>
        <?php endif; ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php if ($feature_cards) : foreach ($feature_cards as $card) : ?>
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 p-6">
                <div class="w-16 h-16 rounded-full bg-[#f5f7fb] flex items-center justify-center mb-4">
                    <?php if (!empty($card['icon'])) : ?>
                        <img src="<?php echo esc_url($card['icon']['sizes']['thumbnail'] ?? $card['icon']['url']); ?>" alt="" class="w-8 h-8 object-contain">
                    <?php else : ?>
                        <svg class="w-8 h-8 text-[var(--primary-color)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    <?php endif; ?>
                </div>
                <h3 class="text-xl font-bold text-[var(--primary-color)]"><?php echo esc_html($card['title'] ?? ''); ?></h3>
                <p class="mt-2 text-[var(--text-secondary)] text-sm leading-relaxed"><?php echo esc_html($card['description'] ?? ''); ?></p>
                <?php if (!empty($card['link'])) : ?>
                <a href="<?php echo esc_url($card['link']); ?>" class="mt-4 inline-flex items-center text-[var(--primary-color)] font-semibold text-sm hover:text-[var(--accent-gold)]">Learn More
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <?php endif; ?>
            </div>
            <?php endforeach; else : ?>
            <div class="bg-white rounded-xl shadow-md p-6"><h3 class="font-semibold">Add cards in admin</h3></div>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>
    <!-- Latest News Section -->
    <section class="py-20 px-4 md:px-10 lg:px-20">
        <div class="mb-12">
            <h2 class="text-4xl font-bold tracking-tight mb-2 text-[var(--primary-color)] border-b-4 border-[var(--accent-gold)] inline-block pb-2">
                <?php echo wp_kses_post(get_field('news_section_title', $front_page_id) ?: 'Latest News'); ?>
            </h2>
        </div>

        <!-- News Carousel Container -->
        <div class="relative">
            <div class="news-carousel-container overflow-hidden">
                <div class="news-carousel flex transition-transform duration-500 ease-in-out" id="news-carousel">

                    <?php
                    // Get latest news posts
                    $news_count = get_field('news_posts_count', $front_page_id) ?: 6;
                    $news_query = new WP_Query(array(
                        'post_type' => 'news',
                        'posts_per_page' => $news_count,
                        'post_status' => 'publish',
                        'orderby' => 'date',
                        'order' => 'DESC'
                    ));

                    if ($news_query->have_posts()) :
                        while ($news_query->have_posts()) : $news_query->the_post();
                    ?>
                        <div class="news-slide flex-shrink-0 w-full md:w-1/2 lg:w-1/3 px-4">
                            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 h-full">
                                <?php
                                $listing_image = get_field('news_listing_image');
                                if ($listing_image) : ?>
                                    <img src="<?php echo esc_url($listing_image['sizes']['large'] ?? $listing_image['url']); ?>" alt="<?php echo esc_attr($listing_image['alt'] ?: get_the_title()); ?>" class="w-full h-48 object-cover">
                                <?php elseif (has_post_thumbnail()) : ?>
                                    <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>" alt="<?php the_title(); ?>" class="w-full h-48 object-cover">
                                <?php else : ?>
                                    <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="<?php the_title(); ?>" class="w-full h-48 object-cover">
                                <?php endif; ?>
                                <div class="p-6">
                                    <h3 class="text-xl font-bold mb-3 text-[var(--primary-color)]">
                                        <?php the_title(); ?>
                                    </h3>
                                    <p class="text-[var(--text-secondary)] mb-4 text-sm leading-relaxed">
                                        <?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?>
                                    </p>
                                    <a class="text-[var(--primary-color)] hover:text-[var(--accent-gold)] transition-colors text-sm font-semibold inline-flex items-center" href="<?php the_permalink(); ?>">
                                        Read More
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php
                        endwhile;
                        wp_reset_postdata();
                    else :
                        // Default news cards if no posts exist
                    ?>
                        <div class="news-slide flex-shrink-0 w-full md:w-1/2 lg:w-1/3 px-4">
                            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 h-full">
                                <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="NIESV Abuja Hosts Annual Conference" class="w-full h-48 object-cover">
                                <div class="p-6">
                                    <h3 class="text-xl font-bold mb-3 text-black">
                                        NIESV Abuja Hosts Annual Conference
                                    </h3>
                                    <p class="text-[var(--text-secondary)] mb-4 text-sm leading-relaxed">
                                        The Abuja Branch of NIESV successfully hosted its annual
                                        conference, bringing together leading experts...
                                    </p>
                                    <a class="text-[var(--primary-color)] hover:text-[var(--accent-gold)] transition-colors text-sm font-semibold inline-flex items-center" href="#">
                                        Read More
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="news-slide flex-shrink-0 w-full md:w-1/2 lg:w-1/3 px-4">
                            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 h-full">
                                <img src="https://images.unsplash.com/photo-1541888946425-d81bb19240f5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="New Real Estate Regulations Announced" class="w-full h-48 object-cover">
                                <div class="p-6">
                                    <h3 class="text-xl font-bold mb-3 text-[var(--primary-color)]">
                                        New Real Estate Regulations Announced
                                    </h3>
                                    <p class="text-[var(--text-secondary)] mb-4 text-sm leading-relaxed">
                                        The Federal Capital Territory Administration has announced
                                        new regulations affecting real estate...
                                    </p>
                                    <a class="text-[var(--primary-color)] hover:text-[var(--accent-gold)] transition-colors text-sm font-semibold inline-flex items-center" href="#">
                                        Read More
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="news-slide flex-shrink-0 w-full md:w-1/2 lg:w-1/3 px-4">
                            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 h-full">
                                <img src="https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Market Trends in Abuja Real Estate" class="w-full h-48 object-cover">
                                <div class="p-6">
                                    <h3 class="text-xl font-bold mb-3 text-[var(--primary-color)]">
                                        Market Trends in Abuja Real Estate
                                    </h3>
                                    <p class="text-[var(--text-secondary)] mb-4 text-sm leading-relaxed">
                                        A recent report by NIESV Abuja highlights the latest trends
                                        and developments in the Abuja real estate market.
                                    </p>
                                    <a class="text-[var(--primary-color)] hover:text-[var(--accent-gold)] transition-colors text-sm font-semibold inline-flex items-center" href="#">
                                        Read More
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Navigation Arrows -->
            <button class="news-carousel-prev absolute left-0 top-1/2 transform -translate-y-1/2 -translate-x-4 w-12 h-12 bg-white hover:bg-gray-100 rounded-full flex items-center justify-center text-[var(--primary-color)] transition-all duration-300 shadow-lg z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button class="news-carousel-next absolute right-0 top-1/2 transform -translate-y-1/2 translate-x-4 w-12 h-12 bg-white hover:bg-gray-100 rounded-full flex items-center justify-center text-[var(--primary-color)] transition-all duration-300 shadow-lg z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </section>

    <!-- Upcoming Events Section -->
    <section class="bg-gray-50 py-20 px-4 md:px-10 lg:px-20">
        <div class="mb-12">
            <h2 class="text-4xl font-bold tracking-tight mb-2 text-[var(--primary-color)] border-b-4 border-[var(--accent-gold)] inline-block pb-2">
                <?php echo wp_kses_post(get_field('events_section_title', $front_page_id) ?: 'Upcoming Events'); ?>
            </h2>
        </div>
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">

            <?php
            // Get upcoming events
            $events_count = get_field('events_count', $front_page_id) ?: 4;
            $events_query = new WP_Query(array(
                'post_type' => 'events',
                'posts_per_page' => $events_count,
                'post_status' => 'publish',
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

            if ($events_query->have_posts()) :
                while ($events_query->have_posts()) : $events_query->the_post();
                    $event_date = get_field('event_date');
                    $day = $event_date ? date('j', strtotime($event_date)) : '15';
                    $month = $event_date ? date('M', strtotime($event_date)) : 'JUL';
            ?>
                <a href="<?php the_permalink(); ?>" class="bg-[#d4af37] rounded-2xl shadow-lg flex items-center p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
                    <div class="bg-white text-[var(--primary-color)] rounded-lg p-4 text-center min-w-[80px] flex flex-col items-center justify-center mr-6 shadow-md">
                        <div class="text-3xl font-bold"><?php echo wp_kses_post($day); ?></div>
                        <div class="text-xs uppercase tracking-wide mt-1"><?php echo strtoupper($month); ?></div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold mb-2 text-[var(--primary-color)]">
                            <?php the_title(); ?>
                        </h3>
                        <p class="text-[var(--primary-color)] text-sm">
                            <?php echo wp_trim_words(get_the_excerpt(), 10, '...'); ?>
                        </p>
                    </div>
                </a>
            <?php
                endwhile;
                wp_reset_postdata();
            else :
                // Query for any published events to use as placeholders
                $placeholder_events_query = new WP_Query(array(
                    'post_type' => 'events',
                    'posts_per_page' => 4,
                    'post_status' => 'publish',
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));

                if ($placeholder_events_query->have_posts()) :
                    // Use actual published events as placeholders
                    while ($placeholder_events_query->have_posts()) : $placeholder_events_query->the_post();
                        $event_date = get_field('event_date');
                        $day = $event_date ? date('j', strtotime($event_date)) : '15';
                        $month = $event_date ? date('M', strtotime($event_date)) : 'JUL';

                        // Determine card styling (alternate between gold and white)
                        $card_index = $placeholder_events_query->current_post;
                        $is_gold = ($card_index < 2);
                        $card_bg = $is_gold ? 'bg-[#d4af37]' : 'bg-white';
                        $date_bg = $is_gold ? 'bg-white text-[var(--primary-color)]' : 'bg-[#1a1a2e] text-white';
                        $text_color = $is_gold ? 'text-[var(--primary-color)]' : 'text-[var(--primary-color)]';
                        $desc_color = $is_gold ? 'text-[var(--primary-color)]' : 'text-[var(--text-secondary)]';
                ?>
                    <a href="<?php the_permalink(); ?>" class="<?php echo esc_attr($card_bg); ?> rounded-2xl shadow-lg flex items-center p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
                        <div class="<?php echo esc_attr($date_bg); ?> rounded-lg p-4 text-center min-w-[80px] flex flex-col items-center justify-center mr-6 shadow-md">
                            <div class="text-3xl font-bold"><?php echo wp_kses_post($day); ?></div>
                            <div class="text-xs uppercase tracking-wide mt-1"><?php echo strtoupper($month); ?></div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold mb-2 <?php echo esc_attr($text_color); ?>">
                                <?php the_title(); ?>
                            </h3>
                            <p class="<?php echo esc_attr($desc_color); ?> text-sm">
                                <?php echo wp_trim_words(get_the_excerpt(), 10, '...'); ?>
                            </p>
                        </div>
                    </a>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    // Fallback: If no events exist at all, link to events archive page
                    $events_archive_url = get_post_type_archive_link('events');
                ?>
                    <a href="<?php echo esc_url($events_archive_url); ?>" class="bg-[#d4af37] rounded-2xl shadow-lg flex items-center p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
                        <div class="bg-white text-[var(--primary-color)] rounded-lg p-4 text-center min-w-[80px] flex flex-col items-center justify-center mr-6 shadow-md">
                            <div class="text-3xl font-bold">15</div>
                            <div class="text-xs uppercase tracking-wide mt-1">JUL</div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold mb-2 text-[var(--primary-color)]">
                                Annual General Meeting
                            </h3>
                            <p class="text-[var(--primary-color)] text-sm">
                                Discussing past achievements and future plans.
                            </p>
                        </div>
                    </a>

                    <a href="<?php echo esc_url($events_archive_url); ?>" class="bg-[#d4af37] rounded-2xl shadow-lg flex items-center p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
                        <div class="bg-white text-[var(--primary-color)] rounded-lg p-4 text-center min-w-[80px] flex flex-col items-center justify-center mr-6 shadow-md">
                            <div class="text-3xl font-bold">28</div>
                            <div class="text-xs uppercase tracking-wide mt-1">AUG</div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold mb-2 text-[var(--primary-color)]">
                                Professional Development Workshop
                            </h3>
                            <p class="text-[var(--primary-color)] text-sm">
                                Enhance your skills with our latest workshop.
                            </p>
                        </div>
                    </a>

                    <a href="<?php echo esc_url($events_archive_url); ?>" class="bg-white rounded-2xl shadow-lg flex items-center p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
                        <div class="bg-[#1a1a2e] text-white rounded-lg p-4 text-center min-w-[80px] flex flex-col items-center justify-center mr-6">
                            <div class="text-3xl font-bold">10</div>
                            <div class="text-xs uppercase tracking-wide mt-1">SEP</div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold mb-2 text-[var(--primary-color)]">Networking Mixer</h3>
                            <p class="text-[var(--text-secondary)] text-sm">
                                Connect with fellow professionals and build relationships.
                            </p>
                        </div>
                    </a>

                    <a href="<?php echo esc_url($events_archive_url); ?>" class="bg-white rounded-2xl shadow-lg flex items-center p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
                        <div class="bg-[#1a1a2e] text-white rounded-lg p-4 text-center min-w-[80px] flex flex-col items-center justify-center mr-6">
                            <div class="text-3xl font-bold">22</div>
                            <div class="text-xs uppercase tracking-wide mt-1">OCT</div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold mb-2 text-[var(--primary-color)]">
                                Charity Gala Night
                            </h3>
                            <p class="text-[var(--text-secondary)] text-sm">
                                A night of elegance, giving back to the community.
                            </p>
                        </div>
                    </a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </section>


    <!-- Our Affiliates Section -->
    <section class="bg-white py-20 px-4 md:px-10 lg:px-20">
        <div class="mb-12">
            <h2 class="text-4xl font-bold tracking-tight mb-2 text-[var(--primary-color)] border-b-4 border-[var(--accent-gold)] inline-block pb-2">
                <?php echo wp_kses_post(get_field('affiliates_section_title', $front_page_id) ?: 'Our Affiliates'); ?>
            </h2>
        </div>

        <!-- Affiliates Carousel Container -->
        <div class="relative">
            <div class="affiliates-carousel-container overflow-hidden">
                <div class="affiliates-carousel flex transition-transform duration-500 ease-in-out" id="affiliates-carousel">
                    <?php
                    $affiliate_logos = get_field('affiliate_logos', $front_page_id);
                    if ($affiliate_logos) :
                        foreach ($affiliate_logos as $affiliate) :
                    ?>
                        <div class="affiliate-slide flex-shrink-0 w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5 px-4">
                            <div class="w-full h-36 bg-gray-100 rounded-lg flex items-center justify-center p-4 hover:shadow-md transition-shadow duration-300">
                                <?php if ($affiliate['affiliate_url']) : ?>
                                    <a href="<?php echo esc_url($affiliate['affiliate_url']); ?>" target="_blank" class="w-full h-full flex items-center justify-center">
                                <?php endif; ?>

                                <?php if ($affiliate['affiliate_logo']) : ?>
                                    <img src="<?php echo esc_url($affiliate['affiliate_logo']['url']); ?>"
                                         alt="<?php echo esc_attr($affiliate['affiliate_name']); ?>"
                                         class="max-w-full max-h-full object-contain">
                                <?php else : ?>
                                    <span class="text-gray-400 text-xs text-center"><?php echo wp_kses_post($affiliate['affiliate_name']); ?></span>
                                <?php endif; ?>

                                <?php if ($affiliate['affiliate_url']) : ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php
                        endforeach;
                    else :
                        // Default affiliate placeholders
                    ?>
                        <div class="affiliate-slide flex-shrink-0 w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5 px-4">
                            <div class="w-full h-36 bg-gray-100 rounded-lg flex items-center justify-center">
                                <span class="text-gray-400 text-xs">Affiliate Logo 1</span>
                            </div>
                        </div>
                        <div class="affiliate-slide flex-shrink-0 w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5 px-4">
                            <div class="w-full h-36 bg-gray-100 rounded-lg flex items-center justify-center">
                                <span class="text-gray-400 text-xs">Affiliate Logo 2</span>
                            </div>
                        </div>
                        <div class="affiliate-slide flex-shrink-0 w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5 px-4">
                            <div class="w-full h-36 bg-gray-100 rounded-lg flex items-center justify-center">
                                <span class="text-gray-400 text-xs">Affiliate Logo 3</span>
                            </div>
                        </div>
                        <div class="affiliate-slide flex-shrink-0 w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5 px-4">
                            <div class="w-full h-36 bg-gray-100 rounded-lg flex items-center justify-center">
                                <span class="text-gray-400 text-xs">Affiliate Logo 4</span>
                            </div>
                        </div>
                        <div class="affiliate-slide flex-shrink-0 w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5 px-4">
                            <div class="w-full h-36 bg-gray-100 rounded-lg flex items-center justify-center">
                                <span class="text-gray-400 text-xs">Affiliate Logo 5</span>
                            </div>
                        </div>
                        <div class="affiliate-slide flex-shrink-0 w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5 px-4">
                            <div class="w-full h-36 bg-gray-100 rounded-lg flex items-center justify-center">
                                <span class="text-gray-400 text-xs">Affiliate Logo 6</span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Navigation Arrows -->
            <button class="affiliates-carousel-prev absolute left-0 top-1/2 transform -translate-y-1/2 -translate-x-4 w-12 h-12 bg-white hover:bg-gray-100 rounded-full flex items-center justify-center text-[var(--primary-color)] transition-all duration-300 shadow-lg z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button class="affiliates-carousel-next absolute right-0 top-1/2 transform -translate-y-1/2 translate-x-4 w-12 h-12 bg-white hover:bg-gray-100 rounded-full flex items-center justify-center text-[var(--primary-color)] transition-all duration-300 shadow-lg z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </section>
</main>

<style>
/* Hero Carousel Styles - Only for carousel functionality */
.hero-slide {
    opacity: 0;
    transition: opacity 1000ms ease-in-out;
}

.hero-slide.active {
    opacity: 1;
}

.carousel-dot.active {
    background-color: white !important;
}

/* Team Slider Styles */
.team-slider-container {
    max-width: 1200px;
    margin: 0 auto;
}

.team-slider {
    display: flex;
    transition: transform 0.5s ease-in-out;
}

.team-slide {
    flex: 0 0 auto;
    width: 280px;
    margin: 0 10px;
}

/* Slider Navigation Buttons - Removed for team slider */

/* Slider Dots */
.slider-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: #d1d5db;
    cursor: pointer;
    transition: all 0.3s ease;
}

.slider-dot.active {
    background-color: var(--accent-gold);
    transform: scale(1.2);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .team-slide {
        width: 240px;
        margin: 0 8px;
    }

    /* Team navigation buttons removed */
}

/* News Carousel Styles */
.news-carousel-container {
    max-width: 100%;
    margin: 0 auto;
}

.news-carousel {
    display: flex;
    transition: transform 0.5s ease-in-out;
}

.news-slide {
    flex: 0 0 auto;
}

/* Affiliates Carousel Styles */
.affiliates-carousel-container {
    max-width: 100%;
    margin: 0 auto;
}

.affiliates-carousel {
    display: flex;
    transition: transform 0.5s ease-in-out;
}

.affiliate-slide {
    flex: 0 0 auto;
}

/* Responsive adjustments for carousels */
@media (max-width: 768px) {
    .news-carousel-prev,
    .news-carousel-next,
    .affiliates-carousel-prev,
    .affiliates-carousel-next {
        width: 40px;
        height: 40px;
    }

    .news-carousel-prev,
    .affiliates-carousel-prev {
        left: -8px;
    }

    .news-carousel-next,
    .affiliates-carousel-next {
        right: -8px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hero Carousel Functionality - Only if multiple slides exist
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.carousel-dot');
    const prevBtn = document.querySelector('.carousel-prev');
    const nextBtn = document.querySelector('.carousel-next');
    const heroContainer = document.querySelector('.hero-carousel-container');

    // Only initialize carousel if there are multiple slides
    if (slides.length > 1) {
        let currentSlide = 0;
        let slideInterval;

        function showSlide(index) {
            // Hide all slides
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));

            // Show current slide
            if (slides[index]) {
                slides[index].classList.add('active');
            }
            if (dots[index]) {
                dots[index].classList.add('active');
            }

            currentSlide = index;
        }

        function nextSlide() {
            const next = (currentSlide + 1) % slides.length;
            showSlide(next);
        }

        function prevSlide() {
            const prev = (currentSlide - 1 + slides.length) % slides.length;
            showSlide(prev);
        }

        function startAutoSlide() {
            slideInterval = setInterval(nextSlide, 5000); // Change slide every 5 seconds
        }

        function stopAutoSlide() {
            clearInterval(slideInterval);
        }

        // Event listeners for carousel navigation
        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                stopAutoSlide();
                nextSlide();
                startAutoSlide();
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                stopAutoSlide();
                prevSlide();
                startAutoSlide();
            });
        }

        // Dot navigation
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                stopAutoSlide();
                showSlide(index);
                startAutoSlide();
            });
        });

        // Pause on hover
        if (heroContainer) {
            heroContainer.addEventListener('mouseenter', stopAutoSlide);
            heroContainer.addEventListener('mouseleave', startAutoSlide);
        }

        // Touch/swipe support for mobile
        let touchStartX = 0;
        let touchEndX = 0;

        if (heroContainer) {
            heroContainer.addEventListener('touchstart', e => {
                touchStartX = e.changedTouches[0].screenX;
            });

            heroContainer.addEventListener('touchend', e => {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            });
        }

        function handleSwipe() {
            const swipeThreshold = 50;
            const diff = touchStartX - touchEndX;

            if (Math.abs(diff) > swipeThreshold) {
                stopAutoSlide();
                if (diff > 0) {
                    nextSlide(); // Swipe left - next slide
                } else {
                    prevSlide(); // Swipe right - previous slide
                }
                startAutoSlide();
            }
        }

        // Start auto-slide
        startAutoSlide();
    }

    // Team Slider Functionality
    const teamSlider = document.getElementById('team-slider');
    const teamSlides = document.querySelectorAll('.team-slide');
    // Team navigation buttons removed
    const teamDotsContainer = document.getElementById('team-slider-dots');

    if (teamSlider && teamSlides.length > 0) {
        let currentTeamSlide = 0;
        const slidesToShow = window.innerWidth >= 768 ? 3 : 1; // Show 3 slides on desktop, 1 on mobile
        const maxSlide = Math.max(0, teamSlides.length - slidesToShow);

        // Create dots
        for (let i = 0; i <= maxSlide; i++) {
            const dot = document.createElement('button');
            dot.classList.add('slider-dot');
            if (i === 0) dot.classList.add('active');
            dot.addEventListener('click', () => goToTeamSlide(i));
            teamDotsContainer.appendChild(dot);
        }

        const teamDots = document.querySelectorAll('.slider-dot');

        function updateTeamSlider() {
            const slideWidth = teamSlides[0].offsetWidth + 20; // Include margin
            teamSlider.style.transform = `translateX(-${currentTeamSlide * slideWidth}px)`;

            // Update dots
            teamDots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentTeamSlide);
            });
        }

        function goToTeamSlide(slideIndex) {
            currentTeamSlide = Math.max(0, Math.min(slideIndex, maxSlide));
            updateTeamSlider();
        }

        function nextTeamSlide() {
            if (currentTeamSlide < maxSlide) {
                currentTeamSlide++;
                updateTeamSlider();
            }
        }

        function prevTeamSlide() {
            if (currentTeamSlide > 0) {
                currentTeamSlide--;
                updateTeamSlider();
            }
        }

        // Navigation button event listeners removed

        // Auto-slide functionality (optional)
        setInterval(() => {
            if (currentTeamSlide >= maxSlide) {
                currentTeamSlide = 0;
            } else {
                currentTeamSlide++;
            }
            updateTeamSlider();
        }, 5000); // Change slide every 5 seconds

        // Handle window resize
        window.addEventListener('resize', () => {
            updateTeamSlider();
        });

        // Initialize
        updateTeamSlider();
    }

    // News Carousel Functionality
    const newsCarousel = document.getElementById('news-carousel');
    const newsSlides = document.querySelectorAll('.news-slide');
    const newsPrevBtn = document.querySelector('.news-carousel-prev');
    const newsNextBtn = document.querySelector('.news-carousel-next');

    if (newsCarousel && newsSlides.length > 0) {
        let currentNewsSlide = 0;
        let newsItemsPerView = window.innerWidth >= 1024 ? 3 : (window.innerWidth >= 768 ? 2 : 1);
        const maxNewsSlide = Math.max(0, newsSlides.length - newsItemsPerView);

        function updateNewsCarousel() {
            const slideWidth = newsSlides[0].offsetWidth;
            newsCarousel.style.transform = `translateX(-${currentNewsSlide * slideWidth}px)`;
        }

        function nextNewsSlide() {
            if (currentNewsSlide < maxNewsSlide) {
                currentNewsSlide++;
            } else {
                currentNewsSlide = 0; // Loop back to start
            }
            updateNewsCarousel();
        }

        function prevNewsSlide() {
            if (currentNewsSlide > 0) {
                currentNewsSlide--;
            } else {
                currentNewsSlide = maxNewsSlide; // Loop to end
            }
            updateNewsCarousel();
        }

        if (newsPrevBtn) {
            newsPrevBtn.addEventListener('click', prevNewsSlide);
        }

        if (newsNextBtn) {
            newsNextBtn.addEventListener('click', nextNewsSlide);
        }

        // Touch/swipe support for news carousel
        let newsTouchStartX = 0;
        let newsTouchEndX = 0;

        newsCarousel.addEventListener('touchstart', e => {
            newsTouchStartX = e.changedTouches[0].screenX;
        });

        newsCarousel.addEventListener('touchend', e => {
            newsTouchEndX = e.changedTouches[0].screenX;
            handleNewsSwipe();
        });

        function handleNewsSwipe() {
            const swipeThreshold = 50;
            const diff = newsTouchStartX - newsTouchEndX;

            if (Math.abs(diff) > swipeThreshold) {
                if (diff > 0) {
                    nextNewsSlide();
                } else {
                    prevNewsSlide();
                }
            }
        }

        // Handle window resize
        window.addEventListener('resize', () => {
            newsItemsPerView = window.innerWidth >= 1024 ? 3 : (window.innerWidth >= 768 ? 2 : 1);
            currentNewsSlide = Math.min(currentNewsSlide, Math.max(0, newsSlides.length - newsItemsPerView));
            updateNewsCarousel();
        });

        updateNewsCarousel();
    }

    // Affiliates Carousel Functionality
    const affiliatesCarousel = document.getElementById('affiliates-carousel');
    const affiliateSlides = document.querySelectorAll('.affiliate-slide');
    const affiliatesPrevBtn = document.querySelector('.affiliates-carousel-prev');
    const affiliatesNextBtn = document.querySelector('.affiliates-carousel-next');

    if (affiliatesCarousel && affiliateSlides.length > 0) {
        let currentAffiliateSlide = 0;
        let affiliatesPerView = window.innerWidth >= 1280 ? 5 : (window.innerWidth >= 1024 ? 4 : (window.innerWidth >= 768 ? 3 : 2));
        const maxAffiliateSlide = Math.max(0, affiliateSlides.length - affiliatesPerView);

        function updateAffiliatesCarousel() {
            const slideWidth = affiliateSlides[0].offsetWidth;
            affiliatesCarousel.style.transform = `translateX(-${currentAffiliateSlide * slideWidth}px)`;
        }

        function nextAffiliateSlide() {
            if (currentAffiliateSlide < maxAffiliateSlide) {
                currentAffiliateSlide++;
            } else {
                currentAffiliateSlide = 0; // Loop back to start
            }
            updateAffiliatesCarousel();
        }

        function prevAffiliateSlide() {
            if (currentAffiliateSlide > 0) {
                currentAffiliateSlide--;
            } else {
                currentAffiliateSlide = maxAffiliateSlide; // Loop to end
            }
            updateAffiliatesCarousel();
        }

        if (affiliatesPrevBtn) {
            affiliatesPrevBtn.addEventListener('click', prevAffiliateSlide);
        }

        if (affiliatesNextBtn) {
            affiliatesNextBtn.addEventListener('click', nextAffiliateSlide);
        }

        // Touch/swipe support for affiliates carousel
        let affiliatesTouchStartX = 0;
        let affiliatesTouchEndX = 0;

        affiliatesCarousel.addEventListener('touchstart', e => {
            affiliatesTouchStartX = e.changedTouches[0].screenX;
        });

        affiliatesCarousel.addEventListener('touchend', e => {
            affiliatesTouchEndX = e.changedTouches[0].screenX;
            handleAffiliatesSwipe();
        });

        function handleAffiliatesSwipe() {
            const swipeThreshold = 50;
            const diff = affiliatesTouchStartX - affiliatesTouchEndX;

            if (Math.abs(diff) > swipeThreshold) {
                if (diff > 0) {
                    nextAffiliateSlide();
                } else {
                    prevAffiliateSlide();
                }
            }
        }

        // Handle window resize
        window.addEventListener('resize', () => {
            affiliatesPerView = window.innerWidth >= 1280 ? 5 : (window.innerWidth >= 1024 ? 4 : (window.innerWidth >= 768 ? 3 : 2));
            currentAffiliateSlide = Math.min(currentAffiliateSlide, Math.max(0, affiliateSlides.length - affiliatesPerView));
            updateAffiliatesCarousel();
        });

        updateAffiliatesCarousel();
    }

    // Counter animation
    const counters = document.querySelectorAll('.counter-value');
    if (counters.length) {
        const animateCounter = (el) => {
            const target = parseInt(el.getAttribute('data-target')) || 0;
            const duration = 1500;
            const start = 0;
            const startTime = performance.now();
            const step = (now) => {
                const progress = Math.min((now - startTime) / duration, 1);
                const value = Math.floor(start + (target - start) * progress);
                el.textContent = value.toLocaleString();
                if (progress < 1) requestAnimationFrame(step);
            };
            requestAnimationFrame(step);
        };

        const io = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.4 });

        counters.forEach(c => io.observe(c));
    }

    // Event Countdown Timer
    const countdownTimer = document.getElementById('countdown-timer');
    if (countdownTimer) {
        const eventDate = new Date(countdownTimer.getAttribute('data-event-date')).getTime();

        function updateCountdown() {
            const now = new Date().getTime();
            const distance = eventDate - now;

            if (distance > 0) {
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById('days').textContent = days.toString().padStart(2, '0');
                document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
                document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
                document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
            } else {
                // Event has passed
                document.getElementById('days').textContent = '00';
                document.getElementById('hours').textContent = '00';
                document.getElementById('minutes').textContent = '00';
                document.getElementById('seconds').textContent = '00';

                // Optionally hide the countdown section or show "Event Started" message
                const countdownSection = countdownTimer.closest('section');
                if (countdownSection) {
                    countdownSection.style.display = 'none';
                }
            }
        }

        // Update countdown immediately and then every second
        updateCountdown();
        setInterval(updateCountdown, 1000);
    }
});
</script>

<?php get_footer(); ?>
