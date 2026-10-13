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
function bgi_setup() {
	load_theme_textdomain( 'bgi', BGI_DIR . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );

	register_nav_menus(
		array(
			'primary' => esc_html__( 'Header Menu', 'bgi' ),
			'footer'  => esc_html__( 'Footer Menu', 'bgi' ),
		)
	);
}

/**
 * Plain Nav Walker that outputs clean <a> links.
 */
class BGI_Plain_Nav_Walker extends Walker_Nav_Menu {
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$attr = ! empty( $item->url ) ? ' href="' . esc_url( $item->url ) . '"' : '';
		$attr .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
		$attr .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
		$title = apply_filters( 'the_title', $item->title, $item->ID );
		$output .= '<a' . $attr . '>' . esc_html( $title ) . '</a>';
	}
	public function end_el( &$output, $item, $depth = 0, $args = null ) {}
	public function start_lvl( &$output, $depth = 0, $args = null ) {}
	public function end_lvl( &$output, $depth = 0, $args = null ) {}
}

/**
 * Default fallback links for Header Menu.
 */
function bgi_primary_menu_fallback() {
	$is_ar = ( 'ar' === bgi_lang() );
	$items = array(
		array( 'url' => '#journey', 'title' => $is_ar ? 'رحلتنا' : 'The Journey' ),
		array( 'url' => '#trust',   'title' => $is_ar ? 'عملاؤنا' : 'Clients' ),
		array( 'url' => '#deliver', 'title' => $is_ar ? 'خدماتنا' : 'Services' ),
		array( 'url' => '#process', 'title' => $is_ar ? 'آلية العمل' : 'Process' ),
		array( 'url' => 'blog.html', 'title' => $is_ar ? 'المدونة' : 'Blog' ),
		array( 'url' => '#contact', 'title' => $is_ar ? 'تواصل معنا' : 'Contact' ),
	);
	foreach ( $items as $item ) {
		printf(
			'<a href="%s">%s</a>',
			esc_url( bgi_link( $item['url'] ) ),
			esc_html( $item['title'] )
		);
	}
}

/**
 * Default fallback links for Footer Menu.
 */
function bgi_footer_menu_fallback() {
	$is_ar = ( 'ar' === bgi_lang() );
	$items = array(
		array( 'url' => 'privacy.html', 'title' => $is_ar ? 'سياسة الخصوصية' : 'Privacy Policy' ),
		array( 'url' => 'terms.html',   'title' => $is_ar ? 'الشروط والأحكام' : 'Terms & Conditions' ),
	);
	foreach ( $items as $item ) {
		printf(
			'<a href="%s">%s</a>',
			esc_url( bgi_link( $item['url'] ) ),
			esc_html( $item['title'] )
		);
	}
}


/**
 * Warn in the dashboard when a hard dependency is missing.
 */
add_action( 'admin_notices', 'bgi_dependency_notice' );
function bgi_dependency_notice() {
	if ( ! function_exists( 'acf_add_options_page' ) ) {
		echo '<div class="notice notice-error"><p>' . esc_html__( 'BGI theme requires ACF Pro.', 'bgi' ) . '</p></div>';
	}
	if ( ! function_exists( 'pll_current_language' ) ) {
		echo '<div class="notice notice-warning"><p>' . esc_html__( 'BGI theme expects Polylang to be active.', 'bgi' ) . '</p></div>';
	}
}
