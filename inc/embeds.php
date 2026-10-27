<?php
/**
 * Card links that open in the embed pop-up (virtual tours and videos).
 *
 * @package BGI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Iframe address for a link: Matterport autoplay, YouTube and Vimeo player URLs.
 *
 * @param string $url Link address.
 * @return string
 */
function bgi_embed_src( $url ) {
	$host = strtolower( (string) wp_parse_url( $url, PHP_URL_HOST ) );
	$path = (string) wp_parse_url( $url, PHP_URL_PATH );
	parse_str( (string) wp_parse_url( $url, PHP_URL_QUERY ), $query );

	if ( 'matterport.com' === $host || str_ends_with( $host, '.matterport.com' ) ) {
		$add = array();
		if ( ! isset( $query['play'] ) ) {
			$add['play'] = '1';
		}
		if ( ! isset( $query['nt'] ) ) {
			$add['nt'] = '0';
		}
		return $add ? add_query_arg( $add, $url ) : $url;
	}

	$video = '';
	if ( 'youtu.be' === $host ) {
		$video = trim( $path, '/' );
	} elseif ( preg_match( '/(^|\.)youtube\.com$/', $host ) ) {
		if ( ! empty( $query['v'] ) ) {
			$video = $query['v'];
		} elseif ( preg_match( '#^/(?:shorts|live)/([^/]+)#', $path, $m ) ) {
			$video = $m[1];
		}
	}
	if ( '' !== $video && preg_match( '/^[A-Za-z0-9_-]{6,20}$/', $video ) ) {
		return 'https://www.youtube-nocookie.com/embed/' . $video . '?autoplay=1&rel=0';
	}

	if ( preg_match( '/(^|\.)vimeo\.com$/', $host ) && 'player.vimeo.com' !== $host && preg_match( '#^/(\d+)#', $path, $m ) ) {
		return 'https://player.vimeo.com/video/' . $m[1] . '?autoplay=1';
	}

	return $url;
}

/**
 * A card's link and the address its pop-up loads.
 *
 * @param string $raw The Link field: a web address or a pasted <iframe> embed code.
 * @return array|null {
 *     Null when there is no usable link.
 *
 *     @type string $href  Address the card links to (opened in a new tab when the pop-up cannot run).
 *     @type string $embed Address for the pop-up's iframe, or '' when the link cannot load in it.
 *     @type bool   $blank Whether the plain link opens in a new tab.
 * }
 */
function bgi_card_link( $raw ) {
	$raw = trim( (string) $raw );
	if ( '' === $raw ) {
		return null;
	}

	// Embed code: keep only its src address.
	if ( false !== stripos( $raw, '<iframe' ) ) {
		if ( ! preg_match( '/\ssrc\s*=\s*(["\'])(.*?)\1/i', $raw, $m ) ) {
			return null;
		}
		$raw = html_entity_decode( $m[2], ENT_QUOTES );
	}

	// Web addresses and site paths only.
	$raw = trim( $raw );
	if ( ! preg_match( '#^(https?://|/)#i', $raw ) ) {
		return null;
	}

	$url = esc_url_raw( $raw, array( 'http', 'https' ) );
	if ( '' === $url ) {
		return null;
	}

	$host     = strtolower( (string) wp_parse_url( $url, PHP_URL_HOST ) );
	$external = $host && strtolower( (string) wp_parse_url( home_url(), PHP_URL_HOST ) ) !== $host;

	// http pages cannot load inside an https site, so they open in a new tab.
	$popup = 'https' === wp_parse_url( $url, PHP_URL_SCHEME ) || '' === $host;

	return array(
		'href'  => $url,
		'embed' => $popup ? bgi_embed_src( $url ) : '',
		'blank' => $external,
	);
}
