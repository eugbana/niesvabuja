<?php
/**
 * Hero Section Block Template
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param int|string $post_id The post ID this block is saved to.
 */

// Debug: Always show this block is loading
error_log('NIESV Hero Block: Template loaded');

// Simple test - always output something visible
echo '<!-- NIESV Hero Block Template Loaded -->';

// Create id attribute allowing for custom "anchor" value.
$id = 'hero-section-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'hero-section';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $className .= ' align' . $block['align'];
}

// Load values and assign defaults.
$hero_title = get_field('hero_title') ?: 'NIESV Abuja Branch';
$hero_subtitle = get_field('hero_subtitle') ?: 'Setting the standard for estate surveying and valuation in the Federal Capital Territory.';
$hero_background_image = get_field('hero_background_image');
$hero_button_text = get_field('hero_button_text') ?: 'Learn More';
$hero_button_link = get_field('hero_button_link') ?: home_url('/about');
$hero_overlay_opacity = get_field('hero_overlay_opacity') ?: 70;

// Default background image if none selected
$background_image = $hero_background_image ?: 'https://lh3.googleusercontent.com/aida-public/AB6AXuBKDf3P5asmG-v4EY8nAU9L0H5PZUiJ0YNgPybkSNq3irU15GRrcsB5XncMNTjAUq3nwakDyylES6YwIA_4VcbPjr6AMa5wdryZ9T5G3QnyhToDnXC9FWwcG9-MC6BnU8DaMslFruUrSEzK5Orq8Ha80DQlPU02chZPuo9GD4fEWD8kNAFdZzaf729thiK_t1qRoYzg4e4DVAvG9yE7CVEEl0gDxkMLGCNl6W4miEOkryLEGdODEFL3ViX-SGwDYUcsVnIq1GZtqpl7';

// Debug: Log the values
error_log('NIESV Hero: Title = ' . $hero_title);
error_log('NIESV Hero: Subtitle = ' . $hero_subtitle);
error_log('NIESV Hero: Background = ' . $background_image);

?>

<!-- NIESV Hero Section Block -->
<section id="<?php echo esc_attr($id); ?>"
         class="<?php echo esc_attr($className); ?> relative min-h-[600px] flex items-center justify-center bg-cover bg-center"
         style='background-image: linear-gradient(rgba(30, 58, 138, <?php echo $hero_overlay_opacity / 100; ?>) 0%, rgba(30, 58, 138, <?php echo ($hero_overlay_opacity + 20) / 100; ?>) 100%), url("<?php echo esc_url($background_image); ?>");'>

    <div class="text-center text-white px-4">
        <h1 class="hero-title text-6xl md:text-7xl font-extrabold leading-tight tracking-tighter mb-5">
            <?php echo esc_html($hero_title); ?>
        </h1>

        <p class="hero-subtitle text-xl md:text-2xl max-w-4xl mx-auto font-light">
            <?php echo esc_html($hero_subtitle); ?>
        </p>

        <?php if ($hero_button_text && $hero_button_link) : ?>
            <a href="<?php echo esc_url($hero_button_link); ?>"
               class="mt-10 flex min-w-[120px] mx-auto cursor-pointer items-center justify-center overflow-hidden rounded-lg h-14 px-8 bg-[var(--accent-gold)] text-[var(--primary-color)] text-xl font-bold leading-normal tracking-wide hover:bg-opacity-90 transition-all shadow-lg">
                <span class="truncate"><?php echo esc_html($hero_button_text); ?></span>
            </a>
        <?php endif; ?>
    </div>
</section>

<!-- Debug: Always show this for troubleshooting -->
<?php if (!$is_preview) : ?>
    <!-- Hero block loaded successfully -->
    <script>console.log('NIESV Hero Block: Rendered on frontend');</script>
<?php endif; ?>

<?php if ($is_preview) : ?>
    <style>
        .hero-section {
            min-height: 400px;
            border: 2px dashed #d4af37;
            position: relative;
        }
        .hero-section::before {
            content: 'Hero Section Block - Configure in sidebar';
            position: absolute;
            top: 10px;
            left: 10px;
            background: #d4af37;
            color: rgba(0,0,40,1);
            padding: 5px 10px;
            font-size: 12px;
            font-weight: bold;
            border-radius: 4px;
            z-index: 10;
        }
    </style>
<?php endif; ?>

<!-- Debug info for troubleshooting -->
<?php if ($is_preview) : ?>
    <div style="background: #f0f0f0; padding: 10px; margin: 10px 0; border-radius: 4px; font-size: 12px;">
        <strong>Debug Info:</strong><br>
        Hero Title: <?php echo $hero_title ? $hero_title : 'Not set'; ?><br>
        Hero Subtitle: <?php echo $hero_subtitle ? 'Set' : 'Not set'; ?><br>
        Background Image: <?php echo $hero_background_image ? 'Set' : 'Using default'; ?><br>
        Button Text: <?php echo $hero_button_text ? $hero_button_text : 'Not set'; ?><br>
        Button Link: <?php echo $hero_button_link ? 'Set' : 'Not set'; ?>
    </div>
<?php endif; ?>
