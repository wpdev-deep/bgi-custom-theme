<?php
/**
 * Template helpers used by header.php, footer.php and the blocks.
 *
 * @package BGI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Print a menu location as a flat run of links.
 *
 * @param string $location Registered menu location: "primary" or "footer".
 * @return void
 */
function bgi_nav_menu( $location ) {
	wp_nav_menu(
		array(
			'theme_location' => $location,
			'container'      => false,
			'items_wrap'     => '%3$s',
			'depth'          => 1,
			'walker'         => new BGI_Plain_Nav_Walker(),
			'fallback_cb'    => ( 'footer' === $location ) ? 'bgi_footer_menu_fallback' : 'bgi_primary_menu_fallback',
		)
	);
}

/**
 * Print a list of fallback links.
 *
 * @param array[] $items Each item holds a "url" and a "title".
 * @return void
 */
function bgi_print_fallback_links( $items ) {
	foreach ( $items as $item ) {
		if ( empty( $item['url'] ) ) {
			continue;
		}
		printf(
			'<a href="%1$s">%2$s</a>',
			esc_url( bgi_link( $item['url'] ) ),
			esc_html( $item['title'] )
		);
	}
}

/**
 * Header links shown until a menu is assigned to the "primary" location.
 *
 * @return void
 */
function bgi_primary_menu_fallback() {
	bgi_print_fallback_links(
		array(
			array(
				'url'   => '#journey',
				'title' => __( 'The Journey', 'bgi' ),
			),
			array(
				'url'   => '#trust',
				'title' => __( 'Clients', 'bgi' ),
			),
			array(
				'url'   => '#deliver',
				'title' => __( 'Services', 'bgi' ),
			),
			array(
				'url'   => '#process',
				'title' => __( 'Process', 'bgi' ),
			),
			array(
				'url'   => get_post_type_archive_link( 'post' ),
				'title' => __( 'Blog', 'bgi' ),
			),
			array(
				'url'   => '#contact',
				'title' => __( 'Contact', 'bgi' ),
			),
		)
	);
}

/**
 * Footer links shown until a menu is assigned to the "footer" location.
 *
 * @return void
 */
function bgi_footer_menu_fallback() {
	$terms_url  = '';
	$terms_page = get_page_by_path( 'terms' );
	if ( $terms_page ) {
		$terms_id = $terms_page->ID;
		if ( function_exists( 'pll_get_post' ) ) {
			$translated = pll_get_post( $terms_id );
			$terms_id   = $translated ? $translated : $terms_id;
		}
		$terms_url = get_permalink( $terms_id );
	}

	bgi_print_fallback_links(
		array(
			array(
				'url'   => get_privacy_policy_url(),
				'title' => __( 'Privacy Policy', 'bgi' ),
			),
			array(
				'url'   => $terms_url,
				'title' => __( 'Terms & Conditions', 'bgi' ),
			),
		)
	);
}

/**
 * Normalise an ACF image value (array, attachment ID or URL) to url/alt/width/height.
 *
 * @param mixed $image Image field value.
 * @return array|null Null when there is no usable image.
 */
function bgi_get_image_data( $image ) {
	if ( empty( $image ) ) {
		return null;
	}

	if ( is_array( $image ) ) {
		if ( empty( $image['url'] ) ) {
			return null;
		}
		return array(
			'url'    => $image['url'],
			'alt'    => ! empty( $image['alt'] ) ? $image['alt'] : get_bloginfo( 'name' ),
			'width'  => ! empty( $image['width'] ) ? (int) $image['width'] : 0,
			'height' => ! empty( $image['height'] ) ? (int) $image['height'] : 0,
		);
	}

	if ( is_numeric( $image ) ) {
		$src = wp_get_attachment_image_src( (int) $image, 'full' );
		if ( ! $src ) {
			return null;
		}
		$alt = get_post_meta( (int) $image, '_wp_attachment_image_alt', true );
		return array(
			'url'    => $src[0],
			'alt'    => ! empty( $alt ) ? $alt : get_bloginfo( 'name' ),
			'width'  => (int) $src[1],
			'height' => (int) $src[2],
		);
	}

	if ( is_string( $image ) ) {
		return array(
			'url'    => $image,
			'alt'    => get_bloginfo( 'name' ),
			'width'  => 0,
			'height' => 0,
		);
	}

	return null;
}

/**
 * Escape a heading and turn *starred* phrases into the gold highlight used in the design.
 *
 * @param string $text Plain text, optionally with phrases wrapped in asterisks.
 * @return string Safe HTML containing only <span class="gold"> elements.
 */
function bgi_highlight( $text ) {
	return preg_replace( '/[*]([^*]+)[*]/', '<span class="gold">$1</span>', esc_html( (string) $text ) );
}
