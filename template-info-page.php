<?php
/**
 * Template Name: Info Page
 *
 * Shared framework for standalone content pages (Help Center, Track
 * Order, Shipping, Refund & Returns, FAQs, Contact, Sell on TechMart,
 * etc.) — assign this template to each from the block editor's Page
 * panel → Template. Gives every one of them the same header band,
 * consistent prose styling, and a cross-link row to the other pages
 * in the group.
 *
 * @package TechMart
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<div class="tm-info-page__header">
    <div class="tm-container">
        <nav class="tm-breadcrumb" aria-label="<?php esc_attr_e('Breadcrumb', 'techmart'); ?>">
            <?php woocommerce_breadcrumb(); ?>
        </nav>

        <?php while (have_posts()):
            the_post(); ?>
            <h1 class="tm-info-page__title"><?php the_title(); ?></h1>

            <?php if (has_excerpt()): ?>
                <p class="tm-info-page__subtitle"><?php echo esc_html(get_the_excerpt()); ?></p>
            <?php endif; ?>
        <?php endwhile; ?>
    </div>
</div>

<main id="primary" class="tm-container tm-info-page">
    <?php
    rewind_posts();
    while (have_posts()):
        the_post();
        ?>
        <div class="tm-info-page__prose">
            <?php the_content(); ?>
        </div>
    <?php endwhile; ?>

    <?php if (has_nav_menu('support-pages')): ?>
        <nav class="tm-info-page__related" aria-label="<?php esc_attr_e('Related support topics', 'techmart'); ?>">
            <h2 class="tm-info-page__related-title"><?php esc_html_e('Other Topics', 'techmart'); ?></h2>
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'support-pages',
                    'container' => false,
                    'menu_class' => 'tm-info-page__related-list',
                    'depth' => 1,
                )
            );
            ?>
        </nav>
    <?php endif; ?>
</main>

<?php
get_footer();