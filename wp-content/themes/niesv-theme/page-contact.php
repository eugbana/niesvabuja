<?php
/**
 * Template Name: Contact Page
 * Template for Contact Us page
 *
 * @package NIESV_Theme
 */

get_header(); ?>

<main class="flex-grow">
    <?php while (have_posts()) : the_post(); ?>
        
        <!-- Hero Section -->
        <div class="bg-[var(--secondary-color)]">
            <div class="container mx-auto px-6 py-16 text-center">
                <h1 class="text-5xl font-extrabold tracking-tight text-[var(--text-primary)]">
                    <?php echo get_field('contact_hero_title') ?: 'Get in Touch'; ?>
                </h1>
                <p class="mx-auto mt-4 max-w-2xl text-lg text-[var(--text-secondary)]">
                    <?php echo get_field('contact_hero_subtitle') ?: "We'd love to hear from you. Whether you have a question about our services, membership, or anything else, our team is ready to answer all your questions."; ?>
                </p>
            </div>
        </div>
        
        <div class="container mx-auto px-6 py-20">
            <div class="grid grid-cols-1 gap-16 lg:grid-cols-2">
                
                <!-- Contact Information -->
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="text-3xl font-bold tracking-tight text-[var(--text-primary)]">
                        <?php echo get_field('contact_info_title') ?: 'Contact Information'; ?>
                    </h2>
                    <p class="mt-3 text-lg text-[var(--text-secondary)]">
                        <?php echo get_field('contact_info_subtitle') ?: 'Our office is open from 9am to 5pm, Monday to Friday.'; ?>
                    </p>
                    
                    <div class="mt-8 space-y-6">
                        
                        <!-- Address -->
                        <?php if (get_field('office_address')) : ?>
                            <div class="flex items-start gap-4">
                                <svg class="mt-1 h-6 w-6 shrink-0 text-[var(--primary-color)]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <div>
                                    <h3 class="text-lg font-semibold">Address</h3>
                                    <p class="text-[var(--text-secondary)]"><?php echo get_field('office_address'); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Phone Numbers -->
                        <?php
                        $phone_section_title = get_field('phone_section_title') ?: 'Phone';
                        $office_phones = get_field('office_phones');
                        if ($office_phones) : ?>
                            <div class="flex items-start gap-4">
                                <svg class="mt-1 h-6 w-6 shrink-0 text-[var(--primary-color)]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <div>
                                    <h3 class="text-lg font-semibold"><?php echo esc_html($phone_section_title); ?></h3>
                                    <div class="text-[var(--text-secondary)] space-y-1">
                                        <?php foreach ($office_phones as $phone) : ?>
                                            <p>
                                                <a href="tel:<?php echo esc_attr(str_replace(' ', '', $phone['phone_number'])); ?>" class="hover:text-[var(--primary-color)] transition-colors">
                                                    <?php echo esc_html($phone['phone_number']); ?>
                                                </a>
                                            </p>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Email Addresses -->
                        <?php
                        $email_section_title = get_field('email_section_title') ?: 'Email';
                        $office_emails = get_field('office_emails');
                        if ($office_emails) : ?>
                            <div class="flex items-start gap-4">
                                <svg class="mt-1 h-6 w-6 shrink-0 text-[var(--primary-color)]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <div>
                                    <h3 class="text-lg font-semibold"><?php echo esc_html($email_section_title); ?></h3>
                                    <div class="text-[var(--text-secondary)] space-y-1">
                                        <?php foreach ($office_emails as $email) : ?>
                                            <p>
                                                <a href="mailto:<?php echo esc_attr($email['email_address']); ?>" class="hover:text-[var(--primary-color)] transition-colors">
                                                    <?php echo esc_html($email['email_address']); ?>
                                                </a>
                                            </p>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Office Hours -->
                        <?php if (get_field('office_hours')) : ?>
                            <div class="flex items-start gap-4">
                                <svg class="mt-1 h-6 w-6 shrink-0 text-[var(--primary-color)]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12,6 12,12 16,14"></polyline>
                                </svg>
                                <div>
                                    <h3 class="text-lg font-semibold">Office Hours</h3>
                                    <div class="text-[var(--text-secondary)]"><?php echo get_field('office_hours'); ?></div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Default contact info if ACF fields are empty -->
                        <?php if (!get_field('office_address') && !get_field('office_phone') && !get_field('office_email')) : ?>
                            <div class="flex items-start gap-4">
                                <svg class="mt-1 h-6 w-6 shrink-0 text-[var(--primary-color)]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <div>
                                    <h3 class="text-lg font-semibold">Address</h3>
                                    <p class="text-[var(--text-secondary)]">Suite 205, Second Floor, City Plaza, Garki, Abuja, Nigeria</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <svg class="mt-1 h-6 w-6 shrink-0 text-[var(--primary-color)]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <div>
                                    <h3 class="text-lg font-semibold">Phone</h3>
                                    <p class="text-[var(--text-secondary)]">+234 123 456 7890</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <svg class="mt-1 h-6 w-6 shrink-0 text-[var(--primary-color)]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <div>
                                    <h3 class="text-lg font-semibold">Email</h3>
                                    <p class="text-[var(--text-secondary)]">info@niesvabuja.org</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Contact Form -->
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="text-3xl font-bold tracking-tight text-[var(--text-primary)]">
                        <?php echo get_field('contact_form_title') ?: 'Send us a Message'; ?>
                    </h2>
                    <p class="mt-3 text-lg text-[var(--text-secondary)]">
                        <?php echo get_field('contact_form_subtitle') ?: 'Fill out the form below and we will get back to you as soon as possible.'; ?>
                    </p>
                    
                    <form class="mt-8 space-y-6" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                        <input type="hidden" name="action" value="niesv_contact_form">
                        <?php wp_nonce_field('niesv_contact_form', 'niesv_contact_nonce'); ?>
                        
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700">First Name *</label>
                                <input type="text" name="first_name" id="first_name" required 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] sm:text-sm">
                            </div>
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name *</label>
                                <input type="text" name="last_name" id="last_name" required 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] sm:text-sm">
                            </div>
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address *</label>
                            <input type="email" name="email" id="email" required 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] sm:text-sm">
                        </div>
                        
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <input type="tel" name="phone" id="phone" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] sm:text-sm">
                        </div>
                        
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700">Subject *</label>
                            <input type="text" name="subject" id="subject" required 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] sm:text-sm">
                        </div>
                        
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700">Message *</label>
                            <textarea name="message" id="message" rows="6" required 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] sm:text-sm"></textarea>
                        </div>
                        
                        <div>
                            <button type="submit" 
                                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[var(--primary-color)] hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--primary-color)] transition-all">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Map Section -->
            <?php if (get_field('show_map_section')) : ?>
                <div class="mt-16">
                    <h2 class="text-3xl font-bold tracking-tight text-[var(--primary-color)] text-center mb-8">
                        <?php echo get_field('map_section_title') ?: 'Find Us'; ?>
                    </h2>
                    
                    <?php if (get_field('google_maps_embed')) : ?>
                        <div class="rounded-lg overflow-hidden shadow-lg">
                            <?php echo get_field('google_maps_embed'); ?>
                        </div>
                    <?php else : ?>
                        <div class="bg-gray-200 rounded-lg h-96 flex items-center justify-center">
                            <p class="text-gray-600">Map will be displayed here when Google Maps embed code is added.</p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <!-- Additional Content -->
            <?php if (get_the_content()) : ?>
                <div class="mt-16 prose prose-lg max-w-none">
                    <?php the_content(); ?>
                </div>
            <?php endif; ?>
        </div>
        
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
