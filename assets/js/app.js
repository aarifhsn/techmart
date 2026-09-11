/**
 * TechMart — core app script.
 *
 * Single entry point for all theme JS. Phase 3+ modules (mini cart,
 * wishlist toggle, deal countdown) are added as additional functions
 * invoked from this same IIFE rather than as separate inline <script>
 * tags scattered across template parts.
 *
 * No dependencies (no jQuery). Loaded with `defer` — see inc/enqueue.php.
 */
( function () {
	'use strict';

	document.documentElement.classList.remove( 'no-js' );
	document.documentElement.classList.add( 'js' );

	initCategoryDrawer();
	initFilterDrawer();
	initMiniCart();
	initWishlistToggles();

	/**
	 * Category toggle + drawer.
	 *
	 * The same drawer markup serves as a desktop dropdown and a mobile
	 * off-canvas panel — which one applies is decided entirely by CSS
	 * (header.css / responsive.css). Scroll lock and focus trapping are
	 * only engaged when the mobile layout is active: a desktop dropdown
	 * is a simple disclosure widget, not a modal, so it doesn't need
	 * either.
	 */
	function initCategoryDrawer() {
		var toggle = document.querySelector( '.tm-category-toggle' );
		var drawer = document.getElementById( 'tm-category-drawer' );

		if ( ! toggle || ! drawer ) {
			return;
		}

		var closeBtn = drawer.querySelector( '.tm-category-drawer__close' );
		var mobileQuery = window.matchMedia( '(max-width: 1023px)' );
		var focusableSelector = 'a[href], button:not([disabled])';
		var lastFocused = null;

		function isModal() {
			return mobileQuery.matches;
		}

		function openDrawer() {
			toggle.setAttribute( 'aria-expanded', 'true' );
			drawer.hidden = false;
			lastFocused = document.activeElement;

			document.addEventListener( 'keydown', onKeydown );
			document.addEventListener( 'click', onClickOutside );

			if ( isModal() ) {
				document.body.classList.add( 'tm-scroll-lock' );
			}
		}

		function closeDrawer() {
			toggle.setAttribute( 'aria-expanded', 'false' );
			drawer.hidden = true;
			document.body.classList.remove( 'tm-scroll-lock' );

			document.removeEventListener( 'keydown', onKeydown );
			document.removeEventListener( 'click', onClickOutside );

			if ( lastFocused ) {
				lastFocused.focus();
			}
		}

		function onKeydown( event ) {
			if ( event.key === 'Escape' ) {
				closeDrawer();
				return;
			}

			if ( event.key === 'Tab' && isModal() ) {
				trapFocus( event );
			}
		}

		function trapFocus( event ) {
			var focusables = drawer.querySelectorAll( focusableSelector );

			if ( ! focusables.length ) {
				return;
			}

			var first = focusables[ 0 ];
			var last = focusables[ focusables.length - 1 ];

			if ( event.shiftKey && document.activeElement === first ) {
				event.preventDefault();
				last.focus();
			} else if ( ! event.shiftKey && document.activeElement === last ) {
				event.preventDefault();
				first.focus();
			}
		}

		function onClickOutside( event ) {
			if ( ! drawer.contains( event.target ) && ! toggle.contains( event.target ) ) {
				closeDrawer();
			}
		}

		toggle.addEventListener( 'click', function () {
			var isOpen = toggle.getAttribute( 'aria-expanded' ) === 'true';
			if ( isOpen ) {
				closeDrawer();
			} else {
				openDrawer();
			}
		} );

		if ( closeBtn ) {
			closeBtn.addEventListener( 'click', closeDrawer );
		}
	}

	/**
	 * Shop filters toggle + sidebar (Phase 7).
	 *
	 * Deliberately a separate, simpler function rather than a shared
	 * abstraction with initCategoryDrawer() above — the two behave
	 * differently by design: the category drawer is toggle-based at
	 * every breakpoint and starts hidden via the `hidden` attribute,
	 * while the shop sidebar is a normal, always-visible static column
	 * on desktop and only becomes an off-canvas, toggle-based panel
	 * below 1024px (see .tm-shop__sidebar in responsive.css). Forcing
	 * both into one parameterized function would need more branching
	 * than just writing the second, genuinely different one directly.
	 */
	function initFilterDrawer() {
		var toggle = document.querySelector( '.tm-filter-toggle' );
		var drawer = document.getElementById( 'tm-shop-sidebar' );

		if ( ! toggle || ! drawer ) {
			return;
		}

		var mobileQuery = window.matchMedia( '(max-width: 1023px)' );

		function setOpen( isOpen ) {
			drawer.classList.toggle( 'is-open', isOpen );
			toggle.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );

			if ( mobileQuery.matches ) {
				document.body.classList.toggle( 'tm-scroll-lock', isOpen );
			}
		}

		toggle.addEventListener( 'click', function () {
			setOpen( ! drawer.classList.contains( 'is-open' ) );
		} );

		document.addEventListener( 'click', function ( event ) {
			if ( ! mobileQuery.matches || ! drawer.classList.contains( 'is-open' ) ) {
				return;
			}

			if ( ! drawer.contains( event.target ) && ! toggle.contains( event.target ) ) {
				setOpen( false );
			}
		} );

		document.addEventListener( 'keydown', function ( event ) {
			if ( event.key === 'Escape' && drawer.classList.contains( 'is-open' ) ) {
				setOpen( false );
				toggle.focus();
			}
		} );
	}

	/**
	 * Mini-cart dropdown (Phase 9).
	 *
	 * The cart link keeps its real href to the cart page as a working
	 * zero-JS fallback; JS only intercepts the click to show the
	 * dropdown instead of navigating immediately. Content and count are
	 * kept current by WooCommerce's own cart-fragments refresh (see
	 * techmart_cart_fragments() in inc/woocommerce.php) — this module
	 * only opens and closes the panel, it never touches cart contents.
	 */
	function initMiniCart() {
		var toggle = document.querySelector( '.tm-header__cart-toggle' );
		var dropdown = document.getElementById( 'tm-mini-cart' );

		if ( ! toggle || ! dropdown ) {
			return;
		}

		function openDropdown() {
			toggle.setAttribute( 'aria-expanded', 'true' );
			dropdown.hidden = false;
			document.addEventListener( 'keydown', onKeydown );
			document.addEventListener( 'click', onClickOutside );
		}

		function closeDropdown() {
			toggle.setAttribute( 'aria-expanded', 'false' );
			dropdown.hidden = true;
			document.removeEventListener( 'keydown', onKeydown );
			document.removeEventListener( 'click', onClickOutside );
		}

		function onKeydown( event ) {
			if ( event.key === 'Escape' ) {
				closeDropdown();
				toggle.focus();
			}
		}

		function onClickOutside( event ) {
			if ( ! dropdown.contains( event.target ) && ! toggle.contains( event.target ) ) {
				closeDropdown();
			}
		}

		toggle.addEventListener( 'click', function ( event ) {
			event.preventDefault();
			var isOpen = toggle.getAttribute( 'aria-expanded' ) === 'true';

			if ( isOpen ) {
				closeDropdown();
			} else {
				openDropdown();
			}
		} );
	}

	/**
	 * Wishlist toggle buttons (post-launch addition).
	 *
	 * Talks to techmart_ajax_toggle_wishlist() in inc/wishlist.php.
	 * A single product can appear in more than one .tm-wishlist-toggle
	 * on the same page (e.g. the same product in both Trending and Best
	 * Sellers), so every matching button — not just the one clicked —
	 * gets its state updated from one response.
	 */
	function initWishlistToggles() {
		var toggles = document.querySelectorAll( '.tm-wishlist-toggle' );

		if ( ! toggles.length || typeof techmartData === 'undefined' ) {
			return;
		}

		toggles.forEach( function ( button ) {
			button.addEventListener( 'click', function ( event ) {
				event.preventDefault();

				if ( button.disabled ) {
					return;
				}

				var productId = button.getAttribute( 'data-product-id' );

				if ( productId ) {
					toggleWishlist( productId, button );
				}
			} );
		} );

		function toggleWishlist( productId, sourceButton ) {
			sourceButton.disabled = true;

			var formData = new FormData();
			formData.append( 'action', 'techmart_toggle_wishlist' );
			formData.append( 'product_id', productId );
			formData.append( 'nonce', techmartData.wishlistNonce );

			fetch( techmartData.ajaxUrl, {
				method: 'POST',
				credentials: 'same-origin',
				body: formData,
			} )
				.then( function ( response ) {
					return response.json();
				} )
				.then( function ( response ) {
					if ( response && response.success ) {
						updateButtons( productId, response.data.in_wishlist );
						updateCount( response.data.count );
					}
				} )
				.catch( function () {
					// Silently no-op on a network failure — the button just
					// stays in its current (pre-click) state, which is
					// correct since nothing was actually saved.
				} )
				.finally( function () {
					sourceButton.disabled = false;
				} );
		}

		function updateButtons( productId, isActive ) {
			document
				.querySelectorAll( '.tm-wishlist-toggle[data-product-id="' + productId + '"]' )
				.forEach( function ( button ) {
					button.setAttribute( 'aria-pressed', isActive ? 'true' : 'false' );

					// On the Wishlist page itself, removing an item should
					// remove its whole card, not just un-fill the heart —
					// there's no other reason for that card to be there.
					if ( ! isActive ) {
						var card = button.closest( '.tm-wishlist-page .tm-product-card' );
						if ( card ) {
							card.remove();
						}
					}
				} );

			maybeShowEmptyWishlistMessage();
		}

		function updateCount( count ) {
			document.querySelectorAll( '.tm-wishlist-count' ).forEach( function ( el ) {
				el.textContent = count;
			} );
		}

		function maybeShowEmptyWishlistMessage() {
			var page = document.querySelector( '.tm-wishlist-page' );

			if ( ! page ) {
				return;
			}

			var grid = page.querySelector( '.tm-product-grid' );
			var empty = page.querySelector( '.tm-wishlist-page__empty' );

			if ( empty && ( ! grid || ! grid.querySelector( '.tm-product-card' ) ) ) {
				empty.hidden = false;
			}
		}
	}
} )();
