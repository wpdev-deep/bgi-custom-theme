<?php
/**
 * ACF options pages: one shared page plus one per Polylang language.
 *
 * Shared  (post_id "options")  - phone, email, WhatsApp number, CR/MISA, logo.
 * Per-lang (post_id "bgi_{lang}") - every translatable header/footer/modal text.
 *
 * @package BGI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'acf/init', 'bgi_register_options_pages' );
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
		acf_add_options_sub_page(
			array(
				'page_title'  => sprintf( 'Site Settings (%s)', strtoupper( $slug ) ),
				'menu_title'  => strtoupper( $slug ) . ' Texts',
				'menu_slug'   => 'bgi-settings-' . $slug,
				'parent_slug' => 'bgi-settings',
				'post_id'     => 'bgi_' . $slug,
				'capability'  => 'manage_options',
			)
		);
	}
}

/**
 * Read an option with automatic cascading fallback:
 * 1. Current language options page ('bgi_{lang}').
 * 2. English options page fallback ('bgi_en').
 * 3. Shared options page fallback ('options').
 * 4. Default fallback value.
 *
 * @param string $field   ACF field name.
 * @param mixed  $default Default value if field is unset/empty (or bool $shared for legacy calls).
 * @param mixed  $fallback Optional fallback value when second param was bool $shared.
 * @return mixed
 */
function bgi_opt( $field, $default = null, $fallback = null ) {
	if ( ! function_exists( 'get_field' ) ) {
		return is_bool( $default ) ? $fallback : $default;
	}

	$def = is_bool( $default ) ? $fallback : $default;

	// 1. Current language options page (bgi_en or bgi_ar).
	$val = get_field( $field, 'bgi_' . bgi_lang() );
	if ( null !== $val && '' !== $val && array() !== $val && false !== $val ) {
		return $val;
	}

	// 2. English options page fallback.
	$val = get_field( $field, 'bgi_en' );
	if ( null !== $val && '' !== $val && array() !== $val && false !== $val ) {
		return $val;
	}

	// 3. Shared options page fallback ('options').
	$val = get_field( $field, 'options' );
	if ( null !== $val && '' !== $val && array() !== $val && false !== $val ) {
		return $val;
	}

	return $def;
}


/**
 * Safely parse an ACF image field value into an array with url and alt.
 *
 * @param mixed $image Image field return value (array, int ID, or string URL).
 * @return array|null
 */
function bgi_get_image_data( $image ) {
	if ( empty( $image ) ) {
		return null;
	}
	if ( is_array( $image ) && ! empty( $image['url'] ) ) {
		return array(
			'url'    => $image['url'],
			'alt'    => ! empty( $image['alt'] ) ? $image['alt'] : get_bloginfo( 'name' ),
			'width'  => ! empty( $image['width'] ) ? $image['width'] : '',
			'height' => ! empty( $image['height'] ) ? $image['height'] : '',
		);
	}
	if ( is_numeric( $image ) ) {
		$url = wp_get_attachment_image_url( $image, 'full' );
		if ( ! $url ) {
			return null;
		}
		$alt = get_post_meta( $image, '_wp_attachment_image_alt', true );
		return array(
			'url'    => $url,
			'alt'    => ! empty( $alt ) ? $alt : get_bloginfo( 'name' ),
			'width'  => '',
			'height' => '',
		);
	}
	if ( is_string( $image ) ) {
		return array(
			'url'    => $image,
			'alt'    => get_bloginfo( 'name' ),
			'width'  => '',
			'height' => '',
		);
	}
	return null;
}

