/**
 * TechMart — weekly deals countdown.
 *
 * Loaded only on the homepage (see inc/enqueue.php), since a countdown
 * only ever appears in the Weekly Deals section. Each countdown reads a
 * real Unix timestamp from its own data-expires attribute, sourced from
 * WooCommerce's native "Sale price dates" field — never a hardcoded or
 * guessed duration. See techmart_get_weekly_deal_products() in
 * inc/woocommerce.php for how that timestamp is selected.
 *
 * A separate file from app.js, per the brief's own suggested asset
 * structure — this is homepage-only, self-contained interval-timer
 * behavior, distinct from the header interactions app.js owns.
 */
( function () {
	'use strict';

	var countdowns = document.querySelectorAll( '.tm-countdown[data-expires]' );

	if ( ! countdowns.length ) {
		return;
	}

	countdowns.forEach( initCountdown );

	function initCountdown( el ) {
		var expires = parseInt( el.getAttribute( 'data-expires' ), 10 ) * 1000;

		if ( ! expires ) {
			return;
		}

		update();
		var timer = setInterval( update, 1000 );

		function update() {
			var remaining = expires - Date.now();

			if ( remaining <= 0 ) {
				clearInterval( timer );
				el.setAttribute( 'hidden', '' );
				return;
			}

			setUnit( 'days', Math.floor( remaining / ( 1000 * 60 * 60 * 24 ) ) );
			setUnit( 'hours', Math.floor( ( remaining / ( 1000 * 60 * 60 ) ) % 24 ) );
			setUnit( 'minutes', Math.floor( ( remaining / ( 1000 * 60 ) ) % 60 ) );
			setUnit( 'seconds', Math.floor( ( remaining / 1000 ) % 60 ) );
		}

		function setUnit( unit, value ) {
			var target = el.querySelector( '[data-unit="' + unit + '"]' );
			if ( target ) {
				target.textContent = String( value ).padStart( 2, '0' );
			}
		}
	}
} )();
