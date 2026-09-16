<?php
/**
 * Template Name: Sell on TechMart
 * Vendor-recruitment landing page — informational only for now, ahead of
 * any real multi-vendor build-out.
 */

get_header();
?>

<main id="primary" class="tm-container tm-page tm-sell-page">

    <section class="tm-sell-hero">
        <span class="tm-sell-hero__eyebrow">Become a Seller</span>
        <h1 class="tm-sell-hero__title">Sell on TechMart</h1>
        <p class="tm-sell-hero__subtitle">
            Reach thousands of shoppers already browsing TechMart for electronics
            and accessories — list your products and start selling without
            building your own storefront from scratch.
        </p>
        <a href="#tm-sell-apply" class="tm-button tm-button--primary">Apply to Sell</a>
    </section>

    <section class="tm-sell-benefits">
        <h2 class="tm-section-header__title">Why sell with us</h2>
        <div class="tm-sell-benefits__grid">
            <div class="tm-sell-benefit">
                <h3>Built-in audience</h3>
                <p>Your products appear alongside our existing catalog and traffic.</p>
            </div>
            <div class="tm-sell-benefit">
                <h3>Simple seller tools</h3>
                <p>Manage listings, orders, and stock from one dashboard.</p>
            </div>
            <div class="tm-sell-benefit">
                <h3>Reliable payouts</h3>
                <p>Get paid on a regular schedule once orders are fulfilled.</p>
            </div>
            <div class="tm-sell-benefit">
                <h3>Real support</h3>
                <p>A dedicated seller-support line for listing and order questions.</p>
            </div>
        </div>
    </section>

    <section class="tm-sell-steps">
        <h2 class="tm-section-header__title">How it works</h2>
        <ol class="tm-sell-steps__list">
            <li><span class="tm-sell-steps__num">1</span>
                <div>
                    <h3>Apply</h3>
                    <p>Tell us about your business and what you sell.</p>
                </div>
            </li>
            <li><span class="tm-sell-steps__num">2</span>
                <div>
                    <h3>Get approved</h3>
                    <p>We review applications within a few business days.</p>
                </div>
            </li>
            <li><span class="tm-sell-steps__num">3</span>
                <div>
                    <h3>List products</h3>
                    <p>Add your catalog through the seller dashboard.</p>
                </div>
            </li>
            <li><span class="tm-sell-steps__num">4</span>
                <div>
                    <h3>Start selling</h3>
                    <p>Fulfil orders and get paid on schedule.</p>
                </div>
            </li>
        </ol>
    </section>

    <section class="tm-sell-faq">
        <h2 class="tm-section-header__title">Frequently asked questions</h2>
        <details class="tm-sell-faq__item">
            <summary>How much does it cost to sell?</summary>
            <p>We take a commission per sale — full pricing is shared during onboarding.</p>
        </details>
        <details class="tm-sell-faq__item">
            <summary>Who handles shipping?</summary>
            <p>You fulfil and ship your own orders; shipping options for sellers are still being finalised.</p>
        </details>
        <details class="tm-sell-faq__item">
            <summary>How do I get paid?</summary>
            <p>Payouts are issued on a regular schedule once an order is marked complete.</p>
        </details>
        <details class="tm-sell-faq__item">
            <summary>What can I sell?</summary>
            <p>Electronics and accessories that fit TechMart's existing catalog.</p>
        </details>
    </section>

    <section id="tm-sell-apply" class="tm-sell-apply">
        <h2 class="tm-section-header__title">Apply to sell</h2>
        <p>Leave your details and we'll follow up with next steps.</p>

        <form class="tm-sell-apply__form" method="post"
            action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field('techmart_sell_apply', 'techmart_sell_apply_nonce'); ?>
            <input type="hidden" name="action" value="techmart_sell_apply">

            <div class="tm-form-row">
                <label for="tm-sell-business">Business / brand name</label>
                <input type="text" id="tm-sell-business" name="business_name" required>
            </div>
            <div class="tm-form-row">
                <label for="tm-sell-email">Email</label>
                <input type="email" id="tm-sell-email" name="email" required>
            </div>
            <div class="tm-form-row">
                <label for="tm-sell-phone">Phone</label>
                <input type="tel" id="tm-sell-phone" name="phone">
            </div>
            <div class="tm-form-row">
                <label for="tm-sell-category">What do you sell?</label>
                <input type="text" id="tm-sell-category" name="category"
                    placeholder="e.g. Headphones, smart home devices">
            </div>

            <button type="submit" class="tm-button tm-button--primary">Submit application</button>
        </form>
    </section>

</main>

<?php get_footer(); ?>