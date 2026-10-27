<?php
/**
 * Polylang helpers.
 *
 * @package BGI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Current language slug, with a safe default when Polylang is inactive.
 *
 * @return string
 */
function bgi_lang() {
	if ( function_exists( 'pll_current_language' ) ) {
		$lang = pll_current_language();
		if ( $lang ) {
			return $lang;
		}
	}
	return 'en';
}

/**
 * Home URL for the current language.
 *
 * @return string
 */
function bgi_home_url() {
	return function_exists( 'pll_home_url' ) ? pll_home_url() : home_url( '/' );
}

/**
 * Turn a "#section" anchor into an absolute link so it works on inner pages.
 *
 * @param string $url Link from a menu item or an ACF field.
 * @return string
 */
function bgi_link( $url ) {
	if ( is_string( $url ) && isset( $url[0] ) && '#' === $url[0] ) {
		return trailingslashit( bgi_home_url() ) . $url;
	}
	return $url;
}

/**
 * Data for the header language toggle: the first language that is not the current one.
 *
 * @return array {
 *     @type string $current_lang Current language slug.
 *     @type string $target_slug  Slug of the language the toggle switches to.
 *     @type string $target_name  Native name of that language.
 *     @type string $target_url   URL of the current page in that language. Empty when there is nothing to switch to.
 * }
 */
function bgi_get_language_switcher_data() {
	$data = array(
		'current_lang' => bgi_lang(),
		'target_slug'  => '',
		'target_name'  => '',
		'target_url'   => '',
	);

	if ( ! function_exists( 'pll_the_languages' ) ) {
		return $data;
	}

	$languages = pll_the_languages( array( 'raw' => 1 ) );
	if ( ! is_array( $languages ) ) {
		return $data;
	}

	foreach ( $languages as $language ) {
		if ( ! empty( $language['current_lang'] ) || empty( $language['url'] ) ) {
			continue;
		}
		$data['target_slug'] = $language['slug'];
		$data['target_name'] = $language['name'];
		$data['target_url']  = $language['url'];
		break;
	}

	return $data;
}

add_filter( 'pll_copy_post_metas', 'bgi_pll_language_specific_metas' );
/**
 * Keep per-language custom fields out of Polylang's custom field sync.
 *
 * @param string[] $metas Meta keys Polylang copies between translations.
 * @return string[]
 */
function bgi_pll_language_specific_metas( $metas ) {
	return array_values( array_diff( $metas, array( 'breadcrumb_label', '_breadcrumb_label' ) ) );
}
