/**
 * Hero block: WebGL wireframe skyline; SVG fallback on touch, small screens, reduced motion or no WebGL.
 *
 * @package BGI
 */

( function () {
	'use strict';

	const THREE = window.THREE;
	const container = document.getElementById( 'hero-scene' );
	if ( 'undefined' === typeof THREE || ! container ) {
		return;
	}

	const prefersReducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	const isLikelyMobile = window.matchMedia( '(max-width: 768px), (pointer: coarse)' ).matches;
	if ( prefersReducedMotion || isLikelyMobile ) {
		return;
	}

	let renderer;
	try {
		renderer = new THREE.WebGLRenderer( { alpha: true, antialias: true } );
	} catch ( err ) {
		return;
	}

	const fallback = document.getElementById( 'hero-fallback' );
	if ( fallback ) {
		fallback.style.opacity = '0';
	}

	const scene = new THREE.Scene();
	scene.fog = new THREE.FogExp2( 0x080807, 0.042 );

	const camera = new THREE.PerspectiveCamera( 50, container.clientWidth / container.clientHeight, 0.1, 100 );
	camera.position.set( 0, 3.2, 21 );
	camera.lookAt( 0, 3.4, 0 );

	renderer.setPixelRatio( Math.min( window.devicePixelRatio, 1.5 ) );
	renderer.setSize( container.clientWidth, container.clientHeight );
	container.appendChild( renderer.domElement );

	const rig = new THREE.Group();
	scene.add( rig );

	/* --- Street canyon of wireframe towers, receding into depth on both sides --- */
	const goldLine = new THREE.LineBasicMaterial( { color: 0xc9963a, transparent: true, opacity: 0.55 } );
	const goldLineBright = new THREE.LineBasicMaterial( { color: 0xf0d78c, transparent: true, opacity: 0.9 } );
	const windowMat = new THREE.LineBasicMaterial( { color: 0xc9963a, transparent: true, opacity: 0.22 } );
	const panelMat = new THREE.MeshBasicMaterial( { color: 0x1a1408, transparent: true, opacity: 0.22, side: THREE.DoubleSide } );

	function windowGridGeo( h, d, innerX ) {
		const floorH = 0.62;
		const colGap = 0.55;
		const rows = Math.max( 2, Math.round( h / floorH ) );
		const cols = Math.max( 2, Math.round( d / colGap ) );
		const pos = [];
		for ( let r = 0; r <= rows; r++ ) {
			const y = ( r / rows ) * h;
			pos.push( innerX, y, -d / 2, innerX, y, d / 2 );
		}
		for ( let c = 0; c <= cols; c++ ) {
			const z = -d / 2 + ( c / cols ) * d;
			pos.push( innerX, 0, z, innerX, h, z );
		}
		const geo = new THREE.BufferGeometry();
		geo.setAttribute( 'position', new THREE.Float32BufferAttribute( pos, 3 ) );
		return geo;
	}

	// Seeded generator, so the skyline is identical on every load.
	let seed = 7;
	function rnd() {
		seed = ( seed * 9301 + 49297 ) % 233280;
		return seed / 233280;
	}

	[ -1, 1 ].forEach( function ( side ) {
		for ( let i = 0; i < 9; i++ ) {
			const w = 1.6 + rnd() * 1.6;
			const h = 3 + rnd() * 9.5;
			const d = 1.6 + rnd() * 1.6;
			const laneOffset = 3.2 + rnd() * 1.6;
			const x = side * ( laneOffset + w / 2 );
			const z = 7 - i * 4.1 + ( rnd() - 0.5 ) * 1.4;
			const bright = 0 === i % 3;

			const geo = new THREE.BoxGeometry( w, h, d );
			const edges = new THREE.LineSegments( new THREE.EdgesGeometry( geo ), bright ? goldLineBright : goldLine );
			edges.position.set( x, h / 2, z );
			rig.add( edges );

			const panel = new THREE.Mesh( geo, panelMat );
			panel.position.copy( edges.position );
			rig.add( panel );

			const innerX = 1 === side ? -w / 2 : w / 2;
			const windows = new THREE.LineSegments( windowGridGeo( h, d, innerX ), windowMat );
			windows.position.set( x, 0, z );
			rig.add( windows );
		}
	} );

	/* --- Ground grid --- */
	const grid = new THREE.GridHelper( 60, 40, 0xc9963a, 0x241f12 );
	grid.material.transparent = true;
	grid.material.opacity = 0.15;
	rig.add( grid );

	/* --- Rising data particles --- */
	const PCOUNT = 340;
	const positions = new Float32Array( PCOUNT * 3 );
	const speeds = new Float32Array( PCOUNT );
	for ( let i = 0; i < PCOUNT; i++ ) {
		positions[ i * 3 ] = ( Math.random() - 0.5 ) * 26;
		positions[ i * 3 + 1 ] = Math.random() * 12;
		positions[ i * 3 + 2 ] = 8 - Math.random() * 46;
		speeds[ i ] = 0.006 + Math.random() * 0.012;
	}
	const pGeo = new THREE.BufferGeometry();
	pGeo.setAttribute( 'position', new THREE.BufferAttribute( positions, 3 ) );
	const particles = new THREE.Points(
		pGeo,
		new THREE.PointsMaterial( {
			color: 0xf0d78c,
			size: 0.05,
			transparent: true,
			opacity: 0.65,
			blending: THREE.AdditiveBlending,
			depthWrite: false,
		} )
	);
	rig.add( particles );

	/* --- Scanning frame sweeping up through the corridor --- */
	const scanGeo = new THREE.PlaneGeometry( 16, 44 );
	const scanFrame = new THREE.LineSegments(
		new THREE.EdgesGeometry( scanGeo ),
		new THREE.LineBasicMaterial( { color: 0xf0d78c, transparent: true, opacity: 0.45, blending: THREE.AdditiveBlending } )
	);
	scanFrame.rotation.x = -Math.PI / 2;
	scanFrame.position.set( 0, 0, -15 );
	rig.add( scanFrame );

	/* --- Mouse parallax --- */
	let mouseX = 0;
	let mouseY = 0;
	let curRotY = 0;
	let curRotX = 0;

	window.addEventListener( 'mousemove', function ( e ) {
		mouseX = ( e.clientX / window.innerWidth ) * 2 - 1;
		mouseY = ( e.clientY / window.innerHeight ) * 2 - 1;
	} );

	window.addEventListener( 'resize', function () {
		if ( ! container.clientWidth || ! container.clientHeight ) {
			return;
		}
		camera.aspect = container.clientWidth / container.clientHeight;
		camera.updateProjectionMatrix();
		renderer.setSize( container.clientWidth, container.clientHeight );
	} );

	/* --- Render loop, paused while the hero is off screen --- */
	const startZ = 34;
	const endZ = 12;
	const maxH = 12;
	let isVisible = true;
	let rafId = null;
	let idleT = 0;
	let dollyT = 0;

	function animate() {
		if ( ! isVisible ) {
			rafId = null;
			return;
		}
		rafId = window.requestAnimationFrame( animate );

		idleT += 0.0028;
		dollyT = Math.min( 1, dollyT + 0.0045 );
		const eased = 1 - Math.pow( 1 - dollyT, 3 );
		camera.position.z = startZ + ( endZ - startZ ) * eased;
		camera.position.x = Math.sin( idleT * 0.6 ) * 0.9;

		curRotY += ( mouseX * 0.12 - curRotY ) * 0.04;
		curRotX += ( mouseY * 0.06 - curRotX ) * 0.04;
		camera.rotation.y = curRotY;
		camera.rotation.x = curRotX;

		scanFrame.position.y = ( idleT * 2.4 ) % maxH;
		scanFrame.material.opacity = 0.5 * ( 1 - scanFrame.position.y / maxH );

		const posAttr = pGeo.attributes.position;
		for ( let i = 0; i < PCOUNT; i++ ) {
			let y = posAttr.getY( i ) + speeds[ i ];
			if ( y > maxH ) {
				y = 0;
			}
			posAttr.setY( i, y );
		}
		posAttr.needsUpdate = true;

		renderer.render( scene, camera );
	}

	const visibilityObserver = new window.IntersectionObserver(
		function ( entries ) {
			isVisible = entries[ 0 ].isIntersecting;
			if ( isVisible && null === rafId ) {
				animate();
			}
		},
		{ threshold: 0 }
	);
	visibilityObserver.observe( container );

	animate();
} )();
