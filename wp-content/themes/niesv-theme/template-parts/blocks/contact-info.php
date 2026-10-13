<?php
/**
 * Contact Info Block Template
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param int|string $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'contact-info-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'contact-info-block';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}

// Load values and assign defaults.
$section_title = get_field('section_title') ?: 'Get in Touch';
$section_subtitle = get_field('section_subtitle') ?: 'We are here to help with all your estate surveying and valuation needs.';
$office_address = get_field('office_address') ?: get_field('contact_address', 'option');
$office_phone = get_field('office_phone') ?: get_field('contact_phone', 'option');
$office_email = get_field('office_email') ?: get_field('contact_email', 'option');
$office_hours = get_field('office_hours');
$show_social_links = get_field('show_social_links') !== false;
$background_style = get_field('background_style') ?: 'light';

// Get social media links from theme options
$social_facebook = get_field('social_facebook', 'option');
$social_twitter = get_field('social_twitter', 'option');
$social_linkedin = get_field('social_linkedin', 'option');
$social_whatsapp = get_field('social_whatsapp', 'option');
$social_instagram = get_field('social_instagram', 'option');

// Set background class
$bg_class = 'bg-white';
if ($background_style === 'dark') {
    $bg_class = 'bg-[rgba(0,0,40,1)] text-white';
} elseif ($background_style === 'secondary') {
    $bg_class = 'bg-[#e7edf4]';
} elseif ($background_style === 'gold') {
    $bg_class = 'bg-[#d4af37] text-[rgba(0,0,40,1)]';
}
?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?> <?php echo $bg_class; ?> py-12 md:py-20">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold tracking-tight mb-4 <?php echo $background_style === 'dark' ? 'text-white' : 'text-[rgba(0,0,40,1)]'; ?>">
                <?php echo esc_html($section_title); ?>
            </h2>
            <?php if ($section_subtitle) : ?>
                <p class="text-lg max-w-2xl mx-auto <?php echo $background_style === 'dark' ? 'text-gray-300' : 'text-[#49739c]'; ?>">
                    <?php echo esc_html($section_subtitle); ?>
                </p>
            <?php endif; ?>
        </div>
        
        <!-- Contact Information Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
            
            <!-- Address -->
            <?php if ($office_address) : ?>
                <div class="text-center group">
                    <div class="w-16 h-16 bg-[#d4af37] text-[rgba(0,0,40,1)] rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 <?php echo $background_style === 'dark' ? 'text-white' : 'text-[rgba(0,0,40,1)]'; ?>">
                        Our Address
                    </h3>
                    <p class="<?php echo $background_style === 'dark' ? 'text-gray-300' : 'text-[#49739c]'; ?> leading-relaxed">
                        <?php echo nl2br(esc_html($office_address)); ?>
                    </p>
                </div>
            <?php endif; ?>
            
            <!-- Phone -->
            <?php if ($office_phone) : ?>
                <div class="text-center group">
                    <div class="w-16 h-16 bg-[#d4af37] text-[rgba(0,0,40,1)] rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 <?php echo $background_style === 'dark' ? 'text-white' : 'text-[rgba(0,0,40,1)]'; ?>">
                        Call Us
                    </h3>
                    <p class="<?php echo $background_style === 'dark' ? 'text-gray-300' : 'text-[#49739c]'; ?> mb-3">
                        <?php echo esc_html($office_phone); ?>
                    </p>
                    <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $office_phone)); ?>" 
                       class="inline-flex items-center px-4 py-2 bg-[rgba(0,0,40,1)] text-white rounded-md hover:bg-[#d4af37] hover:text-[rgba(0,0,40,1)] transition-all text-sm font-semibold">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        Call Now
                    </a>
                </div>
            <?php endif; ?>
            
            <!-- Email -->
            <?php if ($office_email) : ?>
                <div class="text-center group">
                    <div class="w-16 h-16 bg-[#d4af37] text-[rgba(0,0,40,1)] rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 <?php echo $background_style === 'dark' ? 'text-white' : 'text-[rgba(0,0,40,1)]'; ?>">
                        Email Us
                    </h3>
                    <p class="<?php echo $background_style === 'dark' ? 'text-gray-300' : 'text-[#49739c]'; ?> mb-3">
                        <?php echo esc_html($office_email); ?>
                    </p>
                    <a href="mailto:<?php echo esc_attr($office_email); ?>" 
                       class="inline-flex items-center px-4 py-2 bg-[rgba(0,0,40,1)] text-white rounded-md hover:bg-[#d4af37] hover:text-[rgba(0,0,40,1)] transition-all text-sm font-semibold">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Send Email
                    </a>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Office Hours -->
        <?php if ($office_hours) : ?>
            <div class="mt-12 text-center">
                <div class="max-w-2xl mx-auto bg-white bg-opacity-10 p-6 rounded-lg">
                    <h3 class="text-xl font-bold mb-4 <?php echo $background_style === 'dark' ? 'text-white' : 'text-[rgba(0,0,40,1)]'; ?>">
                        Office Hours
                    </h3>
                    <div class="<?php echo $background_style === 'dark' ? 'text-gray-300' : 'text-[#49739c]'; ?>">
                        <?php echo $office_hours; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Social Media Links -->
        <?php if ($show_social_links && ($social_facebook || $social_twitter || $social_linkedin || $social_whatsapp || $social_instagram)) : ?>
            <div class="mt-12 text-center">
                <h3 class="text-xl font-bold mb-6 <?php echo $background_style === 'dark' ? 'text-white' : 'text-[rgba(0,0,40,1)]'; ?>">
                    Follow Us
                </h3>
                <div class="flex justify-center gap-4">
                    <?php if ($social_facebook) : ?>
                        <a href="<?php echo esc_url($social_facebook); ?>" 
                           target="_blank" rel="noopener noreferrer"
                           class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"></path>
                            </svg>
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($social_twitter) : ?>
                        <a href="<?php echo esc_url($social_twitter); ?>" 
                           target="_blank" rel="noopener noreferrer"
                           class="w-12 h-12 bg-blue-400 text-white rounded-full flex items-center justify-center hover:bg-blue-500 transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"></path>
                            </svg>
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($social_linkedin) : ?>
                        <a href="<?php echo esc_url($social_linkedin); ?>" 
                           target="_blank" rel="noopener noreferrer"
                           class="w-12 h-12 bg-blue-800 text-white rounded-full flex items-center justify-center hover:bg-blue-900 transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"></path>
                            </svg>
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($social_whatsapp) : ?>
                        <a href="<?php echo esc_url($social_whatsapp); ?>"
                           target="_blank" rel="noopener noreferrer"
                           class="w-12 h-12 bg-green-600 text-white rounded-full flex items-center justify-center hover:bg-green-700 transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.488"></path>
                            </svg>
                        </a>
                    <?php endif; ?>

                    <?php if ($social_instagram) : ?>
                        <a href="<?php echo esc_url($social_instagram); ?>"
                           target="_blank" rel="noopener noreferrer"
                           class="w-12 h-12 bg-pink-600 text-white rounded-full flex items-center justify-center hover:bg-pink-700 transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"></path>
                            </svg>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php if ($is_preview) : ?>
    <style>
        .contact-info-block {
            border: 2px dashed #ccc;
            position: relative;
        }
        .contact-info-block::before {
            content: 'Contact Info Block';
            position: absolute;
            top: -10px;
            left: 10px;
            background: white;
            padding: 0 10px;
            font-size: 12px;
            color: #666;
        }
    </style>
<?php endif; ?>
