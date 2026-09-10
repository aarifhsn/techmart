/**
 * TechMart — single product page behavior.
 *
 * Loaded only on the single product page (see inc/enqueue.php). Two
 * small, independent pieces:
 *
 *   1. Reveal the sticky mobile add-to-cart bar once the real
 *      add-to-cart form (#tm-add-to-cart) has scrolled above the
 *      viewport — not simply whenever it's off-screen, which would
 *      also be true before the user has scrolled down to it at all.
 *   2. Wire any [data-scroll-to] button (currently just the sticky
 *      bar's) to smooth-scroll to its target, rather than duplicating
 *      add-to-cart logic — see the comment in
 *      template-parts/product/sticky-bar.php for why.
 */
( function () {
	'use strict';

	initStickyBar();
	initScrollToButtons();

	function initStickyBar() {
		var bar = document.getElementById( 'tm-sticky-bar' );
		var form = document.getElementById( 'tm-add-to-cart' );

		if ( ! bar || ! form || ! ( 'IntersectionObserver' in window ) ) {
			return;
		}

		var observer = new IntersectionObserver( function ( entries ) {
			var entry = entries[ 0 ];

			// Only show once the form has scrolled past the top of the
			// viewport — entry.boundingClientRect.top > 0 means it's
			// still below the viewport (not reached yet), not above it.
			bar.hidden = entry.isIntersecting || entry.boundingClientRect.top > 0;
		} );

		observer.observe( form );
	}

	function initScrollToButtons() {
		document.querySelectorAll( '[data-scroll-to]' ).forEach( function ( button ) {
			button.addEventListener( 'click', function () {
				var target = document.querySelector( button.getAttribute( 'data-scroll-to' ) );

				if ( target ) {
					target.scrollIntoView( { behavior: 'smooth', block: 'center' } );
				}
			} );
		} );
	}
} )();
