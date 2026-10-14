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
	// Cards follow the pointer with a slight 3D tilt; a child .tilt-glare tracks it as a highlight.
	document.querySelectorAll( '.client-card.tilt, .feature-card.tilt, .impact-card.tilt, .usecase-card.tilt' ).forEach( function ( el ) {
		const max = 10;
		const scale = 1.03;

		el.addEventListener( 'mousemove', function ( e ) {
			const rect = el.getBoundingClientRect();
			if ( ! rect.width || ! rect.height ) {
				return;
			}
			const px = ( e.clientX - rect.left ) / rect.width;
			const py = ( e.clientY - rect.top ) / rect.height;
			const rx = ( ( 0.5 - py ) * max ).toFixed( 2 );
			const ry = ( ( px - 0.5 ) * max ).toFixed( 2 );
			el.style.transform = 'perspective(900px) rotateX(' + rx + 'deg) rotateY(' + ry + 'deg) scale(' + scale + ')';
			el.classList.add( 'tilting' );
			el.style.setProperty( '--gx', ( px * 100 ).toFixed( 1 ) + '%' );
			el.style.setProperty( '--gy', ( py * 100 ).toFixed( 1 ) + '%' );
		} );

		el.addEventListener( 'mouseleave', function () {
			el.style.transform = '';
			el.classList.remove( 'tilting' );
		} );
	} );

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

	// Placeholder until the form handler exists: nothing is sent to the server yet.
	const demoForm = document.getElementById( 'demoForm' );
	if ( demoForm ) {
		demoForm.addEventListener( 'submit', function ( e ) {
			e.preventDefault();
			const note = document.getElementById( 'demoFormNote' );
			if ( note ) {
				note.classList.add( 'show' );
			}
			demoForm.reset();
			setTimeout( closeDemoModal, 1800 );
		} );
	}
} )();
