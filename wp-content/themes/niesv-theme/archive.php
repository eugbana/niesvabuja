<?php
/**
 * Generic archive template (categories, tags, authors, dates).
 *
 * Post type archives use their own archive-{post_type}.php templates. Without this
 * file these archives fell back to index.php, which renders the homepage.
 *
 * @package NIESV_Theme
 */

get_header();

get_template_part('template-parts/hero-section', null, array(
    'title' => wp_strip_all_tags(get_the_archive_title()),
    'subtitle' => wp_strip_all_tags(get_the_archive_description()),
    'background_image' => null,
    'overlay_color' => 'rgba(30, 58, 138, 0.8)',
    'size' => 'medium',
    'show_breadcrumb' => true,
));
?>

<main class="flex-1 bg-[var(--background-color)]">
    <section class="py-12 md:py-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">

            <?php if (have_posts()) : ?>

                <div class="space-y-8">
                    <?php while (have_posts()) : the_post(); ?>
                        <article class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h2 class="text-xl md:text-2xl font-bold mb-3">
                                        <a href="<?php the_permalink(); ?>" class="text-[var(--text-primary)] hover:text-[var(--primary-color)] transition-colors">
                                            <?php the_title(); ?>
                                        </a>
                                    </h2>

                                    <div class="flex flex-wrap items-center gap-4 text-sm text-[var(--text-secondary)] mb-4">
                                        <span><?php echo esc_html(get_the_date()); ?></span>
                                        <span><?php the_author(); ?></span>
                                    </div>

                                    <div class="text-[var(--text-secondary)] leading-relaxed">
                                        <?php echo esc_html(wp_trim_words(has_excerpt() ? get_the_excerpt() : wp_strip_all_tags(get_the_content()), 30, '...')); ?>
                                    </div>
                                </div>

                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="ml-6 flex-shrink-0">
                                        <a href="<?php the_permalink(); ?>">
                                            <img src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'medium')); ?>"
                                                 alt="<?php the_title_attribute(); ?>"
                                                 class="w-24 h-24 object-cover rounded-lg">
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="pt-4 border-t border-gray-200">
                                <a href="<?php the_permalink(); ?>"
                                   class="inline-flex items-center font-semibold text-[var(--primary-color)] hover:text-[var(--accent-gold)] transition-colors">
                                    Read More
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </a>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>

                <?php niesv_pagination(null, 'Archive navigation'); ?>

            <?php else : ?>

                <div class="text-center py-16">
                    <h2 class="text-2xl font-bold text-[var(--text-primary)] mb-4">Nothing Here Yet</h2>
                    <p class="text-[var(--text-secondary)] mb-8">There are no posts in this archive.</p>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="text-[var(--primary-color)] font-semibold hover:text-[var(--accent-gold)] transition-colors">
                        Back to Home →
                    </a>
                </div>

            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
