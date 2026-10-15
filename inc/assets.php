<?php
/**
 * Asset registration. Block CSS/JS is declared in each block.json.
 *
 * @package BGI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Version string for a theme asset: its modification time, so edits bust caches.
 *
 * @param string $path Path relative to the theme root, with a leading slash.
 * @return string
 */
function bgi_asset_version( $path ) {
	$file = BGI_DIR . $path;
	return file_exists( $file ) ? (string) filemtime( $file ) : BGI_VERSION;
}

add_action( 'wp_enqueue_scripts', 'bgi_enqueue_assets' );
/**
 * Enqueue the global stylesheet and script, and register vendor handles.
 *
 * @return void
 */
function bgi_enqueue_assets() {
	wp_enqueue_style( 'bgi-style', BGI_URI . '/assets/css/style.css', array(), bgi_asset_version( '/assets/css/style.css' ) );
	wp_enqueue_script( 'bgi-main', BGI_URI . '/assets/js/main.js', array(), bgi_asset_version( '/assets/js/main.js' ), true );

	// Registered only; the hero block lists this handle as a dependency.
	wp_register_script( 'bgi-three', BGI_URI . '/assets/js/three.min.js', array(), bgi_asset_version( '/assets/js/three.min.js' ), true );
}

add_action( 'enqueue_block_assets', 'bgi_legal_editor_styles' );
/**
 * In the block editor, style the content of pages using the Legal template like the front end.
 *
 * @return void
 */
function bgi_legal_editor_styles() {
	if ( ! is_admin() ) {
		return;
	}
	$post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only check of the page being edited.
	if ( ! $post_id || 'page-templates/legal.php' !== get_page_template_slug( $post_id ) ) {
		return;
	}
	wp_enqueue_style( 'bgi-editor-legal', BGI_URI . '/assets/css/editor-legal.css', array(), bgi_asset_version( '/assets/css/editor-legal.css' ) );
}
