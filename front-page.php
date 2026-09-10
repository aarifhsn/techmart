<?php
/**
 * Homepage.
 *
 * Deliberately thin: every section is hooked onto 'techmart_homepage'
 * in inc/template-hooks.php at a specific priority, so adding Phase 4/5
 * sections never means editing this file again — only adding a new
 * hooked function. See inc/template-hooks.php for the full section order.
 *
 * @package TechMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

	<main id="primary" class="tm-home-main">
		<?php do_action( 'techmart_homepage' ); ?>
	</main>

<?php
get_footer();
