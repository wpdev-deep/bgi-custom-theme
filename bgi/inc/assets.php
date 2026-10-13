<?php
/**
 * Asset registration. Block CSS/JS is declared in each block.json.
 *
 * @package BGI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_enqueue_scripts', 'bgi_enqueue_assets' );
function bgi_enqueue_assets() {
	if ( file_exists( BGI_DIR . '/assets/css/style.css' ) ) {
		wp_enqueue_style( 'bgi-style', BGI_URI . '/assets/css/style.css', array(), BGI_VERSION );
	}

	if ( file_exists( BGI_DIR . '/assets/js/main.js' ) ) {
		wp_enqueue_script( 'bgi-main', BGI_URI . '/assets/js/main.js', array(), BGI_VERSION, true );
	}

	wp_register_script( 'bgi-three', BGI_URI . '/assets/js/three.min.js', array(), '1', true );
}


