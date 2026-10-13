<?php
/**
 * Important Updates Block Template
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param int|string $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'important-updates-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'important-updates-block';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}

// Load values and assign defaults.
$section_title = get_field('section_title') ?: 'Important Updates';
$update_title = get_field('update_title') ?: 'Mandatory Professional Development Course';
$update_content = get_field('update_content') ?: 'All members are hereby notified of the upcoming Mandatory Continuing Professional Development (MCPD) course scheduled for August 2024. This is a prerequisite for the renewal of practicing licenses.';
$update_button_text = get_field('update_button_text');
$update_button_link = get_field('update_button_link');
$update_icon = get_field('update_icon') ?: 'megaphone';
$background_color = get_field('background_color') ?: 'secondary';

// Set background class based on selection
$bg_class = 'bg-[#e7edf4]'; // default secondary
if ($background_color === 'primary') {
    $bg_class = 'bg-[rgba(0,0,40,1)] text-white';
} elseif ($background_color === 'white') {
    $bg_class = 'bg-white';
} elseif ($background_color === 'gold') {
    $bg_class = 'bg-[#d4af37] text-[rgba(0,0,40,1)]';
}
?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?> <?php echo $bg_class; ?> py-12 md:py-16">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Title -->
        <div class="text-center mb-8">
            <h2 class="text-3xl md:text-4xl font-bold tracking-tight text-[rgba(0,0,40,1)] <?php echo $background_color === 'primary' ? 'text-white' : ''; ?> inline-flex items-center justify-center gap-3">
                
                <!-- Icon -->
                <?php if ($update_icon === 'megaphone') : ?>
                    <svg class="w-8 h-8 md:w-10 md:h-10 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                    </svg>
                <?php elseif ($update_icon === 'info') : ?>
                    <svg class="w-8 h-8 md:w-10 md:h-10 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                <?php elseif ($update_icon === 'bell') : ?>
                    <svg class="w-8 h-8 md:w-10 md:h-10 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                <?php endif; ?>
                
                <?php echo esc_html($section_title); ?>
            </h2>
        </div>
        
        <!-- Update Content -->
        <div class="max-w-5xl mx-auto bg-white p-6 md:p-8 rounded-xl shadow-2xl border-l-8 border-[#d4af37]">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
                
                <!-- Content -->
                <div class="lg:col-span-2">
                    <h3 class="text-2xl md:text-3xl font-bold text-[rgba(0,0,40,1)] mb-4">
                        <?php echo esc_html($update_title); ?>
                    </h3>
                    
                    <div class="text-[#49739c] text-base md:text-lg leading-relaxed">
                        <?php if (is_string($update_content)) : ?>
                            <?php echo wp_kses_post(wpautop($update_content)); ?>
                        <?php else : ?>
                            <?php echo $update_content; ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Action Button -->
                <?php if ($update_button_text && $update_button_link) : ?>
                    <div class="text-center lg:text-right">
                        <a href="<?php echo esc_url($update_button_link); ?>" 
                           class="inline-block min-w-[160px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-14 px-8 bg-[rgba(0,0,40,1)] text-white text-lg md:text-xl font-bold leading-normal tracking-wide hover:bg-opacity-90 transition-all shadow-lg">
                            <?php echo esc_html($update_button_text); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Additional Updates -->
        <?php if (have_rows('additional_updates')) : ?>
            <div class="max-w-5xl mx-auto mt-8 space-y-4">
                <?php while (have_rows('additional_updates')) : the_row(); 
                    $additional_title = get_sub_field('title');
                    $additional_content = get_sub_field('content');
                    $additional_link = get_sub_field('link');
                ?>
                    <div class="bg-white bg-opacity-50 p-4 rounded-lg border-l-4 border-[#d4af37]">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4 class="font-semibold text-[rgba(0,0,40,1)] mb-2">
                                    <?php echo esc_html($additional_title); ?>
                                </h4>
                                <div class="text-[#49739c] text-sm wysiwyg-content">
                                    <?php
                                    if ($additional_content) {
                                        // Apply WordPress content filters to process WYSIWYG content
                                        echo apply_filters('the_content', $additional_content);
                                    }
                                    ?>
                                </div>
                            </div>
                            
                            <?php if ($additional_link) : ?>
                                <a href="<?php echo esc_url($additional_link); ?>" 
                                   class="ml-4 text-[rgba(0,0,40,1)] hover:text-[#d4af37] transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php if ($is_preview) : ?>
    <style>
        .important-updates-block {
            border: 2px dashed #ccc;
            position: relative;
        }
        .important-updates-block::before {
            content: 'Important Updates Block';
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
