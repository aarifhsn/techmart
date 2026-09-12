<?php
/**
 * Empty cart page.
 *
 * Overrides WooCommerce's own template so the "New in store" upsell
 * grid uses our shared product card (template-parts/product/card.php)
 * instead of the block-based cart-empty pattern's own markup — keeping
 * every product card on the site, including this one, on one template
 * (§34).
 *
 * @package TechMart
 */

if (!defined('ABSPATH')) {
    exit;
}

do_action('woocommerce_cart_is_empty');
?>

<div class="tm-cart-empty">
    <p class="tm-cart-empty__message">
        <?php esc_html_e('Your cart is currently empty!', 'techmart'); ?>
    </p>

    <?php if (wc_get_page_id('shop') > 0): ?>
        <p class="tm-cart-empty__action">
            <a class="button tm-button tm-button--primary wc-backward"
                href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>">
                <?php esc_html_e('Return to shop', 'techmart'); ?>
            </a>
        </p>
    <?php endif; ?>
</div>

<?php
/**
 * Upsell / "New in store" grid — pulls recent products the same way
 * Trending Products does on the homepage, rendered through the same
 * card template part so styling matches everywhere.
 */
$new_in_store = wc_get_products(
    array(
        'status' => 'publish',
        'limit' => 8,
        'orderby' => 'date',
        'order' => 'DESC',
    )
);

if (!empty($new_in_store)):
    ?>
    <section class="tm-band" aria-label="<?php esc_attr_e('New in store', 'techmart'); ?>">
        <div class="tm-container">
            <h2 class="tm-section-header__title"><?php esc_html_e('New in stores', 'techmart'); ?></h2>

            <div class="tm-product-grid">
                <?php foreach ($new_in_store as $product): ?>
                    <?php
                    techmart_get_template_part(
                        'template-parts/product/card',
                        null,
                        array(
                            'product' => $product,
                        )
                    );
                    ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
endif;