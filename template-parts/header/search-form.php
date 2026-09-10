<?php
/**
 * Header search form: optional category filter + text input.
 *
 * Category filtering happens in techmart_filter_search_by_category()
 * (inc/woocommerce.php), which adds a tax_query to the main search
 * query when a product_cat value is present in the URL — WordPress
 * core search has no taxonomy awareness of its own, so that hook is
 * what actually connects this form's category selector to real results.
 *
 * @package TechMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$categories = get_terms(
	array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
		'parent'     => 0,
	)
);

$has_categories = ! is_wp_error( $categories ) && ! empty( $categories );
$selected_cat   = isset( $_GET['product_cat'] ) ? sanitize_title( wp_unslash( $_GET['product_cat'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only GET value used to pre-select a dropdown, no state change.
?>
<form role="search" method="get" class="tm-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<?php if ( $has_categories ) : ?>
		<label class="screen-reader-text" for="tm-search-category"><?php esc_html_e( 'Select a category', 'techmart' ); ?></label>
		<select name="product_cat" id="tm-search-category" class="tm-search__category">
			<option value=""><?php esc_html_e( 'All Categories', 'techmart' ); ?></option>
			<?php foreach ( $categories as $category ) : ?>
				<option value="<?php echo esc_attr( $category->slug ); ?>" <?php selected( $selected_cat, $category->slug ); ?>>
					<?php echo esc_html( $category->name ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	<?php endif; ?>

	<label class="screen-reader-text" for="tm-search-input"><?php esc_html_e( 'Search products, brands and more', 'techmart' ); ?></label>
	<input
		type="search"
		id="tm-search-input"
		class="tm-search__input"
		name="s"
		placeholder="<?php esc_attr_e( 'Search for products, brands and more…', 'techmart' ); ?>"
		value="<?php echo esc_attr( get_search_query() ); ?>"
	>
	<input type="hidden" name="post_type" value="product">

	<button type="submit" class="tm-search__submit">
		<?php techmart_icon( 'search' ); ?>
		<span class="screen-reader-text"><?php esc_html_e( 'Search', 'techmart' ); ?></span>
	</button>
</form>
