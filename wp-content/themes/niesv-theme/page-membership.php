<?php
/**
 * Template for Membership page
 *
 * @package NIESV_Theme
 */

get_header(); ?>

<main class="flex-grow">
    <?php while (have_posts()) : the_post(); ?>
        
        <!-- Hero Section -->
        <section class="bg-[var(--primary-color)] text-white py-20">
            <div class="container mx-auto px-6 text-center">
                <h1 class="text-4xl md:text-6xl font-extrabold leading-tight">
                    <?php echo wp_kses_post(get_field('membership_hero_title') ?: 'Become a Member'); ?>
                </h1>
                <p class="mt-4 text-lg md:text-xl max-w-3xl mx-auto">
                    <?php echo wp_kses_post(get_field('membership_hero_subtitle') ?: 'Join a vibrant community of estate surveyors and valuers in Abuja. Unlock a world of opportunities, professional growth, and collaboration.'); ?>
                </p>
            </div>
        </section>
        
        <!-- Why Join Section -->
        <section class="py-16 sm:py-24">
            <div class="container mx-auto px-6">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-[var(--text-primary)]">
                        <?php echo wp_kses_post(get_field('benefits_section_title') ?: 'Why Join NIESV Abuja?'); ?>
                    </h2>
                    <p class="mt-3 text-lg text-[var(--text-secondary)] max-w-2xl mx-auto">
                        <?php echo wp_kses_post(get_field('benefits_section_subtitle') ?: 'Membership offers unparalleled advantages for your professional journey.'); ?>
                    </p>
                </div>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    
                    <!-- Networking Opportunities -->
                    <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 transform hover:-translate-y-1 flex flex-col">
                        <div class="flex-grow">
                            <div class="flex items-center justify-center h-16 w-16 rounded-full bg-[var(--primary-color)] text-white mb-6">
                                <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M117.25,157.92a60,60,0,1,0-66.5,0A95.83,95.83,0,0,0,3.53,195.63a8,8,0,1,0,13.4,8.74,80,80,0,0,1,134.14,0,8,8,0,0,0,13.4-8.74A95.83,95.83,0,0,0,117.25,157.92ZM40,108a44,44,0,1,1,44,44A44.05,44.05,0,0,1,40,108Zm210.14,98.7a8,8,0,0,1-11.07-2.33A79.83,79.83,0,0,0,172,168a8,8,0,0,1,0-16,44,44,0,1,0-16.34-84.87,8,8,0,1,1-5.94-14.85,60,60,0,0,1,55.53,105.64,95.83,95.83,0,0,1,47.22,37.71A8,8,0,0,1,250.14,206.7Z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold mb-3">Networking Opportunities</h3>
                            <p class="text-[var(--text-secondary)]">Connect with peers, industry leaders, and potential clients through our exclusive events, meetings, and online platforms.</p>
                        </div>
                    </div>
                    
                    <!-- Professional Development -->
                    <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 transform hover:-translate-y-1 flex flex-col">
                        <div class="flex-grow">
                            <div class="flex items-center justify-center h-16 w-16 rounded-full bg-[var(--primary-color)] text-white mb-6">
                                <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M216,56H176V48a24,24,0,0,0-24-24H104A24,24,0,0,0,80,48v8H40A16,16,0,0,0,24,72V200a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V72A16,16,0,0,0,216,56ZM96,48a8,8,0,0,1,8-8h48a8,8,0,0,1,8,8v8H96ZM216,72v41.61A184,184,0,0,1,128,136a184.07,184.07,0,0,1-88-22.38V72Zm0,128H40V131.64A200.19,200.19,0,0,0,128,152a200.25,200.25,0,0,0,88-20.37V200ZM104,112a8,8,0,0,1,8-8h32a8,8,0,0,1,0,16H112A8,8,0,0,1,104,112Z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold mb-3">Professional Development</h3>
                            <p class="text-[var(--text-secondary)]">Access workshops, seminars, and conferences to stay ahead with the latest industry trends and best practices.</p>
                        </div>
                    </div>
                    
                    <!-- Access to Resources -->
                    <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 transform hover:-translate-y-1 flex flex-col">
                        <div class="flex-grow">
                            <div class="flex items-center justify-center h-16 w-16 rounded-full bg-[var(--primary-color)] text-white mb-6">
                                <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M216,40H136V24a8,8,0,0,0-16,0V40H40A16,16,0,0,0,24,56V176a16,16,0,0,0,16,16H79.36L57.75,219a8,8,0,0,0,12.5,10l29.59-37h56.32l29.59,37a8,8,0,1,0,12.5-10l-21.61-27H216a16,16,0,0,0,16-16V56A16,16,0,0,0,216,40Zm0,136H40V56H216V176ZM104,120v24a8,8,0,0,1-16,0V120a8,8,0,0,1,16,0Zm32-16v40a8,8,0,0,1-16,0V104a8,8,0,0,1,16,0Zm32-16v56a8,8,0,0,1-16,0V88a8,8,0,0,1,16,0Z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold mb-3">Access to Resources</h3>
                            <p class="text-[var(--text-secondary)]">Gain a competitive edge with our wealth of research reports, market data, and crucial legal updates.</p>
                        </div>
                    </div>
                    
                    <!-- Advocacy & Representation -->
                    <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 transform hover:-translate-y-1 flex flex-col">
                        <div class="flex-grow">
                            <div class="flex items-center justify-center h-16 w-16 rounded-full bg-[var(--primary-color)] text-white mb-6">
                                <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm0,192a88,88,0,1,1,88-88A88.1,88.1,0,0,1,128,216Zm40-68a28,28,0,0,1-28,28H96a8,8,0,0,1,0-16h44a12,12,0,0,0,0-24H116a28,28,0,0,1,0-56h24a8,8,0,0,1,0,16H116a12,12,0,0,0,0,24h24A28,28,0,0,1,168,148Z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold mb-3">Advocacy & Representation</h3>
                            <p class="text-[var(--text-secondary)]">Have your voice heard in policy discussions and regulatory matters affecting the profession.</p>
                        </div>
                    </div>
                    
                    <!-- Certification & Recognition -->
                    <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 transform hover:-translate-y-1 flex flex-col">
                        <div class="flex-grow">
                            <div class="flex items-center justify-center h-16 w-16 rounded-full bg-[var(--primary-color)] text-white mb-6">
                                <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M216,56H176a48,48,0,0,0-96,0H40A16,16,0,0,0,24,72V200a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V72A16,16,0,0,0,216,56ZM128,32a32,32,0,0,1,32,32H96A32,32,0,0,1,128,32Zm88,168H40V72H80V88a8,8,0,0,0,16,0V72h64V88a8,8,0,0,0,16,0V72h40V200Zm-68-76a36,36,0,1,0-36,36A36,36,0,0,0,148,124Zm-36,20a20,20,0,1,1,20-20A20,20,0,0,1,112,144Z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold mb-3">Certification & Recognition</h3>
                            <p class="text-[var(--text-secondary)]">Enhance your professional credibility with recognized certifications and continuing education credits.</p>
                        </div>
                    </div>
                    
                    <!-- Business Opportunities -->
                    <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 transform hover:-translate-y-1 flex flex-col">
                        <div class="flex-grow">
                            <div class="flex items-center justify-center h-16 w-16 rounded-full bg-[var(--primary-color)] text-white mb-6">
                                <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M224,48H32A16,16,0,0,0,16,64V192a16,16,0,0,0,16,16H224a16,16,0,0,0,16-16V64A16,16,0,0,0,224,48Zm0,144H32V64H224V192ZM176,88a8,8,0,0,1,8-8h32a8,8,0,0,1,0,16H184A8,8,0,0,1,176,88Zm0,24a8,8,0,0,1,8-8h32a8,8,0,0,1,0,16H184A8,8,0,0,1,176,112ZM64,168a36,36,0,1,0-36-36A36,36,0,0,0,64,168Zm0-56a20,20,0,1,1-20,20A20,20,0,0,1,64,112Z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold mb-3">Business Opportunities</h3>
                            <p class="text-[var(--text-secondary)]">Access exclusive job postings, project opportunities, and business partnerships within our network.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Membership Types Section -->
        <section class="py-16 bg-[var(--secondary-color)]">
            <div class="container mx-auto px-6">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-[var(--text-primary)]">
                        <?php echo wp_kses_post(get_field('membership_types_title') ?: 'Membership Categories'); ?>
                    </h2>
                    <p class="mt-3 text-lg text-[var(--text-secondary)] max-w-2xl mx-auto">
                        <?php echo wp_kses_post(get_field('membership_types_subtitle') ?: 'Choose the membership category that best fits your professional status and career goals.'); ?>
                    </p>
                </div>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                    
                    <!-- Full Membership -->
                    <div class="bg-white p-8 rounded-lg shadow-lg border-t-4 border-[var(--primary-color)]">
                        <div class="text-center mb-6">
                            <h3 class="text-2xl font-bold text-[var(--text-primary)] mb-2">Full Membership</h3>
                            <p class="text-[var(--text-secondary)]">For qualified Estate Surveyors and Valuers</p>
                        </div>
                        
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Voting rights in elections
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Access to all events and workshops
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Professional certification support
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Directory listing
                            </li>
                        </ul>
                        
                        <div class="text-center">
                            <a href="<?php echo esc_url(get_field('full_membership_link') ?: '#'); ?>" 
                               class="btn-primary px-6 py-3 rounded-lg font-semibold">
                                Apply Now
                            </a>
                        </div>
                    </div>
                    
                    <!-- Associate Membership -->
                    <div class="bg-white p-8 rounded-lg shadow-lg border-t-4 border-[var(--accent-gold)]">
                        <div class="text-center mb-6">
                            <h3 class="text-2xl font-bold text-[var(--text-primary)] mb-2">Associate Membership</h3>
                            <p class="text-[var(--text-secondary)]">For graduates and professionals in training</p>
                        </div>
                        
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Access to training programs
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Mentorship opportunities
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Networking events access
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Career guidance
                            </li>
                        </ul>
                        
                        <div class="text-center">
                            <a href="<?php echo esc_url(get_field('associate_membership_link') ?: '#'); ?>" 
                               class="bg-[var(--accent-gold)] text-[var(--primary-color)] px-6 py-3 rounded-lg font-semibold hover:bg-opacity-90 transition-all">
                                Apply Now
                            </a>
                        </div>
                    </div>
                    
                    <!-- Student Membership -->
                    <div class="bg-white p-8 rounded-lg shadow-lg border-t-4 border-[var(--text-secondary)]">
                        <div class="text-center mb-6">
                            <h3 class="text-2xl font-bold text-[var(--text-primary)] mb-2">Student Membership</h3>
                            <p class="text-[var(--text-secondary)]">For current students in relevant programs</p>
                        </div>
                        
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Discounted event rates
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Access to study materials
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Scholarship opportunities
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Industry exposure
                            </li>
                        </ul>
                        
                        <div class="text-center">
                            <a href="<?php echo esc_url(get_field('student_membership_link') ?: '#'); ?>" 
                               class="bg-[var(--text-secondary)] text-white px-6 py-3 rounded-lg font-semibold hover:bg-opacity-90 transition-all">
                                Apply Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Application Process Section -->
        <section class="py-16">
            <div class="container mx-auto px-6">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-[var(--text-primary)]">
                        <?php echo wp_kses_post(get_field('application_process_title') ?: 'How to Apply'); ?>
                    </h2>
                    <p class="mt-3 text-lg text-[var(--text-secondary)] max-w-2xl mx-auto">
                        <?php echo wp_kses_post(get_field('application_process_subtitle') ?: 'Follow these simple steps to join our professional community.'); ?>
                    </p>
                </div>
                
                <div class="max-w-4xl mx-auto">
                    <div class="grid md:grid-cols-3 gap-8">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-[var(--primary-color)] text-white rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">1</div>
                            <h3 class="text-xl font-semibold mb-3">Submit Application</h3>
                            <p class="text-[var(--text-secondary)]">Complete the online application form with your professional details and qualifications.</p>
                        </div>
                        
                        <div class="text-center">
                            <div class="w-16 h-16 bg-[var(--primary-color)] text-white rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">2</div>
                            <h3 class="text-xl font-semibold mb-3">Document Review</h3>
                            <p class="text-[var(--text-secondary)]">Our membership committee will review your application and supporting documents.</p>
                        </div>
                        
                        <div class="text-center">
                            <div class="w-16 h-16 bg-[var(--primary-color)] text-white rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">3</div>
                            <h3 class="text-xl font-semibold mb-3">Welcome Aboard</h3>
                            <p class="text-[var(--text-secondary)]">Upon approval, you'll receive your membership certificate and access to all benefits.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- CTA Section -->
        <section class="py-16 bg-[var(--primary-color)] text-white">
            <div class="container mx-auto px-6 text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">
                    <?php echo wp_kses_post(get_field('cta_title') ?: 'Ready to Join Our Community?'); ?>
                </h2>
                <p class="text-lg mb-8 max-w-2xl mx-auto">
                    <?php echo wp_kses_post(get_field('cta_subtitle') ?: 'Take the next step in your professional journey and become part of the leading estate surveying and valuation community in Abuja.'); ?>
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="<?php echo esc_url(get_field('primary_cta_link') ?: '#'); ?>" 
                       class="bg-[var(--accent-gold)] text-[var(--primary-color)] px-8 py-4 rounded-lg font-bold text-lg hover:bg-opacity-90 transition-all">
                        <?php echo wp_kses_post(get_field('primary_cta_text') ?: 'Apply for Membership'); ?>
                    </a>
                    <a href="<?php echo esc_url(get_field('secondary_cta_link') ?: home_url('/contact')); ?>" 
                       class="border-2 border-white text-white px-8 py-4 rounded-lg font-bold text-lg hover:bg-white hover:text-[var(--primary-color)] transition-all">
                        <?php echo wp_kses_post(get_field('secondary_cta_text') ?: 'Contact Us'); ?>
                    </a>
                </div>
            </div>
        </section>
        
        <!-- Additional Content -->
        <?php if (get_the_content()) : ?>
            <section class="py-16">
                <div class="container mx-auto px-6">
                    <div class="prose prose-lg max-w-4xl mx-auto">
                        <?php the_content(); ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>
        
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
