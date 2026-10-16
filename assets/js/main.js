/**
 * Global scripts for the BGI theme: preloader, cursor, sticky header, mobile menu, reveal, tilt cards, demo modal.
 *
 * @package BGI
 */

( function () {
	'use strict';

	/* ==================== PRELOADER ==================== */
	window.addEventListener( 'load', function () {
		const preloader = document.getElementById( 'preloader' );
		if ( preloader ) {
			setTimeout( function () {
				preloader.classList.add( 'done' );
			}, 700 );
		}
	} );

	/* ==================== CUSTOM CURSOR ==================== */
	( function initCursor() {
		const dot = document.getElementById( 'cursorDot' );
		const ring = document.getElementById( 'cursorRing' );
		const isTouch = window.matchMedia( '(hover:none), (pointer:coarse)' ).matches;
		if ( isTouch || ! dot || ! ring ) {
			return;
		}

		let mx = 0;
		let my = 0;
		let rx = 0;
		let ry = 0;

		window.addEventListener( 'mousemove', function ( e ) {
			mx = e.clientX;
			my = e.clientY;
			dot.style.left = mx + 'px';
			dot.style.top = my + 'px';
		} );

		function loop() {
			rx += ( mx - rx ) * 0.18;
			ry += ( my - ry ) * 0.18;
			ring.style.left = rx + 'px';
			ring.style.top = ry + 'px';
			window.requestAnimationFrame( loop );
		}
		loop();

		document.querySelectorAll( 'a, button, .tilt, input, textarea' ).forEach( function ( el ) {
			el.addEventListener( 'mouseenter', function () {
				ring.classList.add( 'hovered' );
			} );
			el.addEventListener( 'mouseleave', function () {
				ring.classList.remove( 'hovered' );
			} );
		} );
	} )();

	/* ==================== HEADER SCROLL ==================== */
	const header = document.getElementById( 'siteHeader' );
	if ( header ) {
		window.addEventListener(
			'scroll',
			function () {
				header.classList.toggle( 'scrolled', window.scrollY > 40 );
			},
			{ passive: true }
		);
	}

	/* ==================== MOBILE MENU ==================== */
	const hamburgerBtn = document.getElementById( 'hamburgerBtn' );
	const mobileMenu = document.getElementById( 'mobileMenu' );
	const menuOverlay = document.getElementById( 'menuOverlay' );

	function setMenu( isOpen ) {
		if ( mobileMenu ) {
			mobileMenu.classList.toggle( 'open', isOpen );
		}
		if ( menuOverlay ) {
			menuOverlay.classList.toggle( 'open', isOpen );
		}
		if ( hamburgerBtn ) {
			hamburgerBtn.classList.toggle( 'open', isOpen );
			hamburgerBtn.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
		}
	}

	function closeMenu() {
		setMenu( false );
	}

	if ( hamburgerBtn && mobileMenu && menuOverlay ) {
		hamburgerBtn.addEventListener( 'click', function () {
			setMenu( ! mobileMenu.classList.contains( 'open' ) );
		} );
		menuOverlay.addEventListener( 'click', closeMenu );

		// The open panel covers the hamburger, so it carries its own close button.
		const mobileMenuClose = document.getElementById( 'mobileMenuClose' );
		if ( mobileMenuClose ) {
			mobileMenuClose.addEventListener( 'click', function () {
				closeMenu();
				hamburgerBtn.focus();
			} );
		}

		window.addEventListener( 'keydown', function ( e ) {
			if ( 'Escape' === e.key && mobileMenu.classList.contains( 'open' ) ) {
				closeMenu();
				hamburgerBtn.focus();
			}
		} );
		mobileMenu.querySelectorAll( 'a' ).forEach( function ( a ) {
			a.addEventListener( 'click', closeMenu );
		} );
	}

	/* ==================== SCROLL REVEAL ==================== */
	const revealEls = document.querySelectorAll( '.reveal' );
	if ( revealEls.length ) {
		if ( 'IntersectionObserver' in window ) {
			const revealObserver = new window.IntersectionObserver(
				function ( entries ) {
					entries.forEach( function ( entry ) {
						if ( entry.isIntersecting ) {
							entry.target.classList.add( 'visible' );
							revealObserver.unobserve( entry.target );
						}
					} );
				},
				{ threshold: 0.15 }
			);
			revealEls.forEach( function ( el ) {
				revealObserver.observe( el );
			} );
		} else {
			revealEls.forEach( function ( el ) {
				el.classList.add( 'visible' );
			} );
		}
	}

	/* ==================== 3D TILT CARDS ==================== */
	/*
	 * Card content leans towards the pointer and enlarges slightly, with a moving highlight (.tilt-glare).
	 * The pointer is tracked on the grid, which never moves, and compared with each card's
	 * untransformed layout box. A tilted or enlarged card therefore can't slide out from under the
	 * pointer and back, which is what made hover flicker at card edges. Updates are batched to one
	 * per frame, and the CSS eases between them. Skipped on touch screens and for reduced motion.
	 */
	const canTilt = window.matchMedia( '(hover: hover) and (pointer: fine)' ).matches &&
		! window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	if ( canTilt ) {
		document.documentElement.classList.add( 'bgi-tilt' );

		document.querySelectorAll( '.client-grid, .feature-grid, .impact-grid, .usecase-grid' ).forEach( function ( grid ) {
			const cards = Array.from( grid.children ).filter( function ( el ) {
				return el.classList.contains( 'tilt' );
			} );
			// The box itself stays still, so the content tilts a little further than the source's whole card did.
			const maxTilt = 18;
			const scale = 1.05;
			let active = null;
			let frame = 0;
			let pointerX = 0;
			let pointerY = 0;

			function layoutBox( card ) {
				const g = grid.getBoundingClientRect();
				return {
					left: g.left + grid.clientLeft + card.offsetLeft,
					top: g.top + grid.clientTop + card.offsetTop,
					width: card.offsetWidth,
					height: card.offsetHeight,
				};
			}

			function cardAtPointer() {
				for ( let i = 0; i < cards.length; i++ ) {
					const b = layoutBox( cards[ i ] );
					if ( pointerX >= b.left && pointerX < b.left + b.width && pointerY >= b.top && pointerY < b.top + b.height ) {
						return { card: cards[ i ], box: b };
					}
				}
				return null;
			}

			// The card's background stays inside its cell; only its content tilts, so no colour spills over.
			function contentOf( card ) {
				return Array.from( card.children ).filter( function ( el ) {
					return ! el.classList.contains( 'tilt-glare' );
				} );
			}

			function release( card ) {
				card.classList.remove( 'tilting' );
				contentOf( card ).forEach( function ( el ) {
					el.style.transform = '';
				} );
			}

			function update() {
				frame = 0;
				const hit = cardAtPointer();
				const card = hit ? hit.card : null;
				if ( card !== active ) {
					if ( active ) {
						release( active );
					}
					active = card;
					if ( card ) {
						card.classList.add( 'tilting' );
					}
				}
				if ( ! hit ) {
					return;
				}
				const px = Math.min( 1, Math.max( 0, ( pointerX - hit.box.left ) / hit.box.width ) );
				const py = Math.min( 1, Math.max( 0, ( pointerY - hit.box.top ) / hit.box.height ) );
				const rx = ( ( 0.5 - py ) * maxTilt ).toFixed( 2 );
				const ry = ( ( px - 0.5 ) * maxTilt ).toFixed( 2 );
				const transform = 'perspective(900px) rotateX(' + rx + 'deg) rotateY(' + ry + 'deg) scale(' + scale + ')';
				contentOf( card ).forEach( function ( el ) {
					// Rotate every piece about the card's centre, so the content moves as one tilted plane.
					el.style.transformOrigin = ( hit.box.width / 2 - el.offsetLeft ) + 'px ' + ( hit.box.height / 2 - el.offsetTop ) + 'px';
					el.style.transform = transform;
				} );
				card.style.setProperty( '--gx', ( px * 100 ).toFixed( 1 ) + '%' );
				card.style.setProperty( '--gy', ( py * 100 ).toFixed( 1 ) + '%' );
			}

			grid.addEventListener(
				'pointermove',
				function ( e ) {
					if ( 'mouse' !== e.pointerType ) {
						return;
					}
					pointerX = e.clientX;
					pointerY = e.clientY;
					if ( ! frame ) {
						frame = window.requestAnimationFrame( update );
					}
				},
				{ passive: true }
			);

			grid.addEventListener( 'pointerleave', function () {
				if ( frame ) {
					window.cancelAnimationFrame( frame );
					frame = 0;
				}
				if ( active ) {
					release( active );
					active = null;
				}
			} );
		} );
	}

	/* ==================== DEMO MODAL ==================== */
	const demoOverlay = document.getElementById( 'demoOverlay' );
	const demoModal = document.getElementById( 'demoModal' );

	function openDemoModal() {
		if ( ! demoModal ) {
			return;
		}
		closeMenu();
		if ( demoOverlay ) {
			demoOverlay.classList.add( 'open' );
		}
		demoModal.classList.add( 'open' );
		document.body.style.overflow = 'hidden';
		const firstField = document.getElementById( 'dm-name' );
		if ( firstField ) {
			firstField.focus();
		}
	}

	function closeDemoModal() {
		if ( demoOverlay ) {
			demoOverlay.classList.remove( 'open' );
		}
		if ( demoModal ) {
			demoModal.classList.remove( 'open' );
		}
		document.body.style.overflow = '';
	}

	// Any element carrying data-bgi-demo-open opens the modal, including ones inside blocks.
	document.addEventListener( 'click', function ( e ) {
		const trigger = e.target.closest( '[data-bgi-demo-open]' );
		if ( trigger ) {
			e.preventDefault();
			openDemoModal();
		}
	} );

	if ( demoOverlay ) {
		demoOverlay.addEventListener( 'click', closeDemoModal );
	}

	const demoModalClose = document.getElementById( 'demoModalClose' );
	if ( demoModalClose ) {
		demoModalClose.addEventListener( 'click', closeDemoModal );
	}

	window.addEventListener( 'keydown', function ( e ) {
		if ( 'Escape' === e.key && demoModal && demoModal.classList.contains( 'open' ) ) {
			closeDemoModal();
		}
	} );

	// Contact Form 7 sends the demo form; once it has gone, let the thank-you show briefly, then close.
	if ( demoModal ) {
		demoModal.addEventListener( 'wpcf7mailsent', function () {
			setTimeout( closeDemoModal, 1800 );
		} );
	}
} )();
