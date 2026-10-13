<?php
/**
 * SVG uploads, limited to administrators and to files without active content.
 *
 * @package BGI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether the current user may upload SVG files.
 *
 * @return bool
 */
function bgi_user_can_upload_svg() {
	return current_user_can( 'manage_options' );
}

add_filter( 'upload_mimes', 'bgi_allow_svg_mime' );
/**
 * Add SVG to the allowed upload types for administrators.
 *
 * @param string[] $mimes Allowed mime types keyed by extension pattern.
 * @return string[]
 */
function bgi_allow_svg_mime( $mimes ) {
	if ( bgi_user_can_upload_svg() ) {
		$mimes['svg'] = 'image/svg+xml';
	}
	return $mimes;
}

add_filter( 'wp_check_filetype_and_ext', 'bgi_check_svg_filetype', 10, 3 );
/**
 * Confirm the type of .svg files, which the server-side sniffing often reports as plain text.
 *
 * @param array  $data     Values for the extension, mime type and corrected filename.
 * @param string $file     Full path to the file.
 * @param string $filename The name of the file.
 * @return array
 */
function bgi_check_svg_filetype( $data, $file, $filename ) {
	if ( ! bgi_user_can_upload_svg() ) {
		return $data;
	}
	if ( 'svg' === strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) ) ) {
		$data['ext']  = 'svg';
		$data['type'] = 'image/svg+xml';
	}
	return $data;
}

add_filter( 'wp_handle_upload_prefilter', 'bgi_reject_unsafe_svg' );
add_filter( 'wp_handle_sideload_prefilter', 'bgi_reject_unsafe_svg' );
/**
 * Refuse SVG files that are not SVG, or that carry scripts, event handlers or embedded documents.
 *
 * @param array $file An element from the $_FILES array.
 * @return array
 */
function bgi_reject_unsafe_svg( $file ) {
	if ( empty( $file['name'] ) || 'svg' !== strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) ) ) {
		return $file;
	}

	if ( ! bgi_user_can_upload_svg() ) {
		$file['error'] = __( 'Only administrators can upload SVG files.', 'bgi' );
		return $file;
	}

	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Local temporary upload, not a remote URL.
	$svg = empty( $file['tmp_name'] ) ? false : file_get_contents( $file['tmp_name'] );

	if ( false === $svg || false === stripos( $svg, '<svg' ) ) {
		$file['error'] = __( 'This file is not a valid SVG image.', 'bgi' );
		return $file;
	}

	$unsafe = array(
		'/<\s*script/i',
		'/<\s*(foreignObject|iframe|object|embed|handler|listener)\b/i',
		'/\son[a-z]+\s*=/i',
		'/(href|src)\s*=\s*["\']?\s*(javascript|data\s*:\s*text\/html|vbscript)\s*:?/i',
		'/<!\s*(ENTITY|DOCTYPE)/i',
	);

	foreach ( $unsafe as $pattern ) {
		if ( preg_match( $pattern, $svg ) ) {
			$file['error'] = __( 'This SVG contains scripts or other active content and cannot be uploaded.', 'bgi' );
			return $file;
		}
	}

	return $file;
}
