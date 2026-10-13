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
 * @param string $url Link from an ACF field.
 * @return string
 */
function bgi_link( $url ) {
	if ( is_string( $url ) && isset( $url[0] ) && '#' === $url[0] ) {
		return trailingslashit( bgi_home_url() ) . $url;
	}
	return $url;
}

/**
 * Get language switcher info for the header.
 *
 * Returns current language, target language slug, display label, and target translation URL.
 *
 * @return array
 */
function bgi_get_language_switcher_data() {
	$current     = bgi_lang();
	$target_slug = ( 'ar' === $current ) ? 'en' : 'ar';
	$target_name = ( 'ar' === $current ) ? 'English' : 'العربية';
	$target_url  = '#';

	if ( function_exists( 'pll_the_languages' ) ) {
		$languages = pll_the_languages( array( 'raw' => 1 ) );
		if ( ! empty( $languages[ $target_slug ]['url'] ) ) {
			$target_url = $languages[ $target_slug ]['url'];
		} elseif ( function_exists( 'pll_home_url' ) ) {
			$target_url = pll_home_url( $target_slug );
		}
	} else {
		// Fallback when Polylang isn't configured yet
		$target_url = add_query_arg( 'lang', $target_slug );
	}

	return array(
		'current_lang' => $current,
		'target_slug'  => $target_slug,
		'target_name'  => $target_name,
		'target_url'   => $target_url,
	);
}

