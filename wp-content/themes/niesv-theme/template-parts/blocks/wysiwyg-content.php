<?php
/**
 * WYSIWYG Content Block Template
 *
 * @package NIESV_Theme
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'wysiwyg-content-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'wysiwyg-content-block';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $className .= ' align' . $block['align'];
}

// Get field values
$content = get_field('content');
$background_color = get_field('background_color') ?: 'transparent';
$text_color = get_field('text_color') ?: 'inherit';
$padding = get_field('padding') ?: 'default';
$max_width = get_field('max_width') ?: 'container';

// Set padding classes
$padding_class = '';
switch ($padding) {
    case 'none':
        $padding_class = '';
        break;
    case 'small':
        $padding_class = 'py-8';
        break;
    case 'large':
        $padding_class = 'py-20';
        break;
    default:
        $padding_class = 'py-12';
        break;
}

// Set max width classes
$width_class = '';
switch ($max_width) {
    case 'narrow':
        $width_class = 'max-w-3xl mx-auto';
        break;
    case 'wide':
        $width_class = 'max-w-6xl mx-auto';
        break;
    case 'full':
        $width_class = 'w-full';
        break;
    default:
        $width_class = 'container mx-auto';
        break;
}

// Build inline styles
$inline_styles = '';
if ($background_color !== 'transparent') {
    $inline_styles .= 'background-color: ' . esc_attr($background_color) . ';';
}
if ($text_color !== 'inherit') {
    $inline_styles .= 'color: ' . esc_attr($text_color) . ';';
}

?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?> <?php echo esc_attr($padding_class); ?>" <?php echo $inline_styles ? 'style="' . $inline_styles . '"' : ''; ?>>
    <div class="<?php echo esc_attr($width_class); ?> px-4 sm:px-6 lg:px-8">
        <?php if ($content) : ?>
            <div class="wysiwyg-content prose prose-lg max-w-none">
                <?php echo wp_kses_post($content); ?>
            </div>
        <?php else : ?>
            <!-- Preview/Placeholder content for editor -->
            <?php if (is_admin()) : ?>
                <div class="wysiwyg-content prose prose-lg max-w-none">
                    <h2>WYSIWYG Content Block</h2>
                    <p>Add your content using the WYSIWYG editor in the block settings. This block supports rich text formatting, links, lists, and more.</p>
                    <ul>
                        <li>Rich text formatting (bold, italic, etc.)</li>
                        <li>Links and buttons</li>
                        <li>Lists and quotes</li>
                        <li>Custom styling options</li>
                    </ul>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<style>
/* WYSIWYG Content Block Styles */
.wysiwyg-content-block .wysiwyg-content {
    font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    line-height: 1.7;
}

.wysiwyg-content-block .wysiwyg-content h1,
.wysiwyg-content-block .wysiwyg-content h2,
.wysiwyg-content-block .wysiwyg-content h3,
.wysiwyg-content-block .wysiwyg-content h4,
.wysiwyg-content-block .wysiwyg-content h5,
.wysiwyg-content-block .wysiwyg-content h6 {
    color: var(--primary-color);
    font-weight: 700;
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.wysiwyg-content-block .wysiwyg-content h1 {
    font-size: 2.5rem;
    line-height: 1.2;
}

.wysiwyg-content-block .wysiwyg-content h2 {
    font-size: 2rem;
    line-height: 1.3;
}

.wysiwyg-content-block .wysiwyg-content h3 {
    font-size: 1.5rem;
    line-height: 1.4;
}

.wysiwyg-content-block .wysiwyg-content h4 {
    font-size: 1.25rem;
    line-height: 1.5;
}

.wysiwyg-content-block .wysiwyg-content p {
    margin-bottom: 1.5rem;
    color: var(--text-primary);
}

.wysiwyg-content-block .wysiwyg-content a {
    color: var(--primary-color);
    text-decoration: underline;
    transition: color 0.3s ease;
}

.wysiwyg-content-block .wysiwyg-content a:hover {
    color: var(--secondary-color);
}

.wysiwyg-content-block .wysiwyg-content ul,
.wysiwyg-content-block .wysiwyg-content ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}

.wysiwyg-content-block .wysiwyg-content li {
    margin-bottom: 0.5rem;
    color: var(--text-primary);
}

.wysiwyg-content-block .wysiwyg-content blockquote {
    border-left: 4px solid var(--accent-gold);
    padding-left: 1.5rem;
    margin: 2rem 0;
    font-style: italic;
    color: var(--text-secondary);
}

.wysiwyg-content-block .wysiwyg-content img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 1.5rem 0;
}

.wysiwyg-content-block .wysiwyg-content table {
    width: 100%;
    border-collapse: collapse;
    margin: 1.5rem 0;
}

.wysiwyg-content-block .wysiwyg-content th,
.wysiwyg-content-block .wysiwyg-content td {
    border: 1px solid #e5e7eb;
    padding: 0.75rem;
    text-align: left;
}

.wysiwyg-content-block .wysiwyg-content th {
    background-color: var(--secondary-color);
    font-weight: 600;
    color: var(--primary-color);
}

.wysiwyg-content-block .wysiwyg-content code {
    background-color: #f3f4f6;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-family: 'Courier New', monospace;
    font-size: 0.875rem;
}

.wysiwyg-content-block .wysiwyg-content pre {
    background-color: #f3f4f6;
    padding: 1rem;
    border-radius: 8px;
    overflow-x: auto;
    margin: 1.5rem 0;
}

.wysiwyg-content-block .wysiwyg-content pre code {
    background-color: transparent;
    padding: 0;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .wysiwyg-content-block .wysiwyg-content h1 {
        font-size: 2rem;
    }
    
    .wysiwyg-content-block .wysiwyg-content h2 {
        font-size: 1.75rem;
    }
    
    .wysiwyg-content-block .wysiwyg-content h3 {
        font-size: 1.375rem;
    }
}
</style>
