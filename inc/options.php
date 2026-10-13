<?php
/**
 * ACF options pages: one shared page plus one per language.
 *
 * Shared   (post_id "options")    - language independent values.
 * Per-lang (post_id "bgi_{lang}") - translatable header, footer and modal text.
 *
 * @package BGI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'acf/init', 'bgi_register_options_pages' );
/**
 * Register the Site Settings page and its per-language sub-pages.
 *
 * @return void
 */
function bgi_register_options_pages() {
	if ( ! function_exists( 'acf_add_options_page' ) ) {
		return;
	}

	acf_add_options_page(
		array(
			'page_title' => __( 'Site Settings (Shared)', 'bgi' ),
			'menu_title' => __( 'Site Settings', 'bgi' ),
			'menu_slug'  => 'bgi-settings',
			'post_id'    => 'options',
			'capability' => 'manage_options',
			'icon_url'   => 'dashicons-admin-generic',
			'position'   => 59,
		)
	);

	$langs = array( 'en', 'ar' );
	if ( function_exists( 'pll_languages_list' ) ) {
		$pll_langs = pll_languages_list( array( 'fields' => 'slug' ) );
		if ( ! empty( $pll_langs ) ) {
			$langs = array_unique( array_merge( $langs, $pll_langs ) );
		}
	}

	foreach ( $langs as $slug ) {
		$code = strtoupper( $slug );

		acf_add_options_sub_page(
			array(
				/* translators: %s: language code, e.g. EN. */
				'page_title'  => sprintf( __( 'Site Settings (%s)', 'bgi' ), $code ),
				/* translators: %s: language code, e.g. EN. */
				'menu_title'  => sprintf( __( '%s Texts', 'bgi' ), $code ),
				'menu_slug'   => 'bgi-settings-' . $slug,
				'parent_slug' => 'bgi-settings',
				'post_id'     => 'bgi_' . $slug,
				'capability'  => 'manage_options',
			)
		);
	}
}

/**
 * Whether an ACF value counts as "not filled in".
 *
 * @param mixed $value Field value.
 * @return bool
 */
function bgi_is_blank( $value ) {
	return null === $value || '' === $value || false === $value || array() === $value;
}

/**
 * Read an option, cascading until a value is found:
 * current language page, English page, shared page, then the fallback.
 *
 * @param string $field    ACF field name.
 * @param mixed  $fallback Value returned when the field is empty everywhere.
 * @return mixed
 */
function bgi_opt( $field, $fallback = null ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $fallback;
	}

	$post_ids = array_unique( array( 'bgi_' . bgi_lang(), 'bgi_en', 'options' ) );

	foreach ( $post_ids as $post_id ) {
		$value = get_field( $field, $post_id );
		if ( ! bgi_is_blank( $value ) ) {
			return $value;
		}
	}

	return $fallback;
}

add_filter( 'acf/json/save_file_name', 'bgi_acf_json_file_name', 10, 3 );
/**
 * Keep a field group's JSON file name when it is saved from the dashboard.
 *
 * ACF names files after the group key by default, which would write a second file
 * beside any that has been given a readable name.
 *
 * @param string $filename  The default file name, "{group key}.json".
 * @param array  $post      The field group being saved.
 * @param string $load_path The path the group was loaded from, if any.
 * @return string
 */
function bgi_acf_json_file_name( $filename, $post, $load_path ) {
	if ( is_string( $load_path ) && '' !== $load_path ) {
		return basename( $load_path );
	}
	return $filename;
}
