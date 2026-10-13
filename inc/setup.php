<?php
/**
 * Theme setup.
 *
 * @package BGI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'after_setup_theme', 'bgi_setup' );
/**
 * Register theme supports, translations and menu locations.
 *
 * @return void
 */
function bgi_setup() {
	load_theme_textdomain( 'bgi', BGI_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/style.css' );

	register_nav_menus(
		array(
			'primary' => __( 'Header Menu', 'bgi' ),
			'footer'  => __( 'Footer Menu', 'bgi' ),
		)
	);
}

add_filter( 'body_class', 'bgi_body_class' );
/**
 * Add the language class the stylesheet keys its Arabic typography on.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function bgi_body_class( $classes ) {
	$classes[] = 'lang-' . sanitize_html_class( bgi_lang() );
	return $classes;
}

add_action( 'wp_head', 'bgi_fallback_site_icon' );
add_action( 'admin_head', 'bgi_fallback_site_icon' );
add_action( 'login_head', 'bgi_fallback_site_icon' );
/**
 * Print the bundled icon until a Site Icon is set under Settings > General.
 *
 * @return void
 */
function bgi_fallback_site_icon() {
	if ( has_site_icon() ) {
		return;
	}
	printf( '<link rel="icon" href="%s" type="image/svg+xml">' . "\n", esc_url( BGI_URI . '/assets/img/site-icon.svg' ) );
	printf( '<link rel="icon" href="%s" sizes="512x512" type="image/png">' . "\n", esc_url( BGI_URI . '/assets/img/site-icon.png' ) );
	printf( '<link rel="apple-touch-icon" href="%s">' . "\n", esc_url( BGI_URI . '/assets/img/site-icon.png' ) );
}

add_action( 'init', 'bgi_disable_emoji_images' );
/**
 * Keep emoji as native text, as in the source design, instead of swapping them for Twemoji images.
 *
 * @return void
 */
function bgi_disable_emoji_images() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_enqueue_scripts', 'wp_enqueue_emoji_styles' );
}

add_action( 'admin_notices', 'bgi_dependency_notice' );
/**
 * Warn in the dashboard when a hard dependency is missing.
 *
 * @return void
 */
function bgi_dependency_notice() {
	if ( ! function_exists( 'acf_add_options_page' ) ) {
		echo '<div class="notice notice-error"><p>' . esc_html__( 'BGI theme requires ACF Pro.', 'bgi' ) . '</p></div>';
	}
	if ( ! function_exists( 'pll_current_language' ) ) {
		echo '<div class="notice notice-warning"><p>' . esc_html__( 'BGI theme expects Polylang to be active.', 'bgi' ) . '</p></div>';
	}
}
