/**
 * Frame Sequence block: crossfade the stills on scroll; one still frame for reduced motion.
 *
 * @package BGI
 */

( function () {
	'use strict';

	const wraps = document.querySelectorAll( '[data-bgi-frame-sequence]' );
	if ( ! wraps.length ) {
		return;
	}

	const prefersReducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	function initFrameSequence( wrap ) {
		const progress = wrap.querySelector( '[data-bgi-frame-progress]' );
		const frames = Array.from( wrap.querySelectorAll( '[data-frame]' ) );
		if ( ! frames.length ) {
			return;
		}

		if ( prefersReducedMotion ) {
			if ( progress ) {
				progress.remove();
			}
			wrap.style.height = '100vh';
			frames.forEach( function ( f, i ) {
				f.style.opacity = i === Math.floor( frames.length / 2 ) ? 1 : 0;
			} );
			return;
		}

		const last = frames.length - 1;
		let ticking = false;

		function update() {
			ticking = false;
			const rect = wrap.getBoundingClientRect();
			const total = rect.height - window.innerHeight;
			if ( total <= 0 ) {
				return;
			}
			const p = Math.max( 0, Math.min( 1, -rect.top / total ) );
			if ( progress ) {
				progress.style.width = ( p * 100 ).toFixed( 1 ) + '%';
			}

			const pos = p * last;
			frames.forEach( function ( f, i ) {
				f.style.opacity = Math.max( 0, 1 - Math.abs( pos - i ) );
			} );
		}

		window.addEventListener(
			'scroll',
			function () {
				if ( ! ticking ) {
					window.requestAnimationFrame( update );
					ticking = true;
				}
			},
			{ passive: true }
		);
		window.addEventListener( 'resize', update );
		update();
	}

	wraps.forEach( initFrameSequence );
} )();
