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
 * Escape a heading and turn *starred* phrases into the highlight used in the design.
 *
 * @param string $text  Plain text, optionally with phrases wrapped in asterisks.
 * @param string $class Class of the highlight span: "gold" for headings, "hl" for approach points.
 * @return string Safe HTML containing only <span class="..."> elements.
 */
function bgi_highlight( $text, $class = 'gold' ) {
	return preg_replace( '/[*]([^*]+)[*]/', '<span class="' . esc_attr( $class ) . '">$1</span>', esc_html( (string) $text ) );
}

/**
 * Write Western digits as Arabic-Indic digits on Arabic pages, as the design does in dates.
 *
 * @param string $text Text containing digits.
 * @return string
 */
function bgi_localize_digits( $text ) {
	if ( 'ar' !== bgi_lang() ) {
		return (string) $text;
	}
	return strtr( (string) $text, array( '0' => '٠', '1' => '١', '2' => '٢', '3' => '٣', '4' => '٤', '5' => '٥', '6' => '٦', '7' => '٧', '8' => '٨', '9' => '٩' ) );
}

/**
 * Date shown as "Last updated" on a legal page.
 *
 * The page's own "Last updated" field when set, so a typo fix does not change the legal date;
 * otherwise the date the page was last saved.
 *
 * @param int|null $post_id Page ID. Defaults to the current page.
 * @return string Localised date, e.g. "August 9, 2026" or "٩ أغسطس ٢٠٢٦".
 */
function bgi_legal_updated_date( $post_id = null ) {
	$post_id   = $post_id ? (int) $post_id : get_the_ID();
	$timestamp = 0;

	$field = function_exists( 'get_field' ) ? get_field( 'last_updated', $post_id ) : '';
	if ( $field ) {
		$date = DateTimeImmutable::createFromFormat( '!Ymd', (string) $field, wp_timezone() );
		if ( $date ) {
			$timestamp = $date->getTimestamp();
		}
	}
	if ( ! $timestamp ) {
		$timestamp = (int) get_post_modified_time( 'U', true, $post_id );
	}

	$format = ( 'ar' === bgi_lang() ) ? 'j F Y' : 'F j, Y';
	return bgi_localize_digits( wp_date( $format, $timestamp ) );
}

/**
 * Print the style attribute that recolours a block's highlighted words.
 *
 * Uses the block's "Highlight colour" field. A block saved before the field existed has no value
 * yet, so it falls back to the field's default for that block (gold on paper sections, light gold
 * on dark ones). Prints nothing when neither is a valid colour.
 *
 * @param array $block The block settings and attributes.
 * @return void
 */
function bgi_highlight_color_attr( $block = array() ) {
	if ( ! function_exists( 'get_field' ) ) {
		return;
	}
	$color = sanitize_hex_color( (string) get_field( 'highlight_color' ) );
	if ( ! $color && ! empty( $block['name'] ) ) {
		$color = sanitize_hex_color( bgi_block_field_default( $block['name'], 'highlight_color' ) );
	}
	if ( $color ) {
		echo ' style="' . esc_attr( '--bgi-highlight: ' . $color ) . '"';
	}
}

/**
 * Default value of a field in a block's own field group.
 *
 * Several blocks share a field name with different defaults, so the field is looked up in the
 * groups attached to that block rather than by name alone.
 *
 * @param string $block_name Block name, e.g. "bgi/journey".
 * @param string $field_name Field name.
 * @return string Empty when the field or its default is missing.
 */
function bgi_block_field_default( $block_name, $field_name ) {
	static $cache = array();
	$key = $block_name . '|' . $field_name;
	if ( ! isset( $cache[ $key ] ) ) {
		$cache[ $key ] = '';
		if ( function_exists( 'acf_get_field_groups' ) ) {
			foreach ( acf_get_field_groups( array( 'block' => $block_name ) ) as $group ) {
				foreach ( (array) acf_get_fields( $group ) as $field ) {
					if ( $field_name === $field['name'] && isset( $field['default_value'] ) ) {
						$cache[ $key ] = (string) $field['default_value'];
						break 2;
					}
				}
			}
		}
	}
	return $cache[ $key ];
}

/**
 * Tags a section paragraph may carry: bold, italic, links and line breaks from its text editor,
 * and the highlight span that *stars* become.
 *
 * @return array Allowed HTML for wp_kses().
 */
function bgi_inline_allowed_tags() {
	return array(
		'span'   => array(
			'class' => true,
		),
		'strong' => array(),
		'b'      => array(),
		'em'     => array(),
		'i'      => array(),
		'br'     => array(),
		'a'      => array(
			'href'   => true,
			'target' => true,
			'rel'    => true,
			'title'  => true,
		),
	);
}

/**
 * Prepare a section paragraph's editor text for printing inside its <p>.
 *
 * The editor may wrap lines in <p>; they become line breaks, so the section keeps its single
 * paragraph and the design's spacing. Plain text saved before the editor was added works as is.
 * The caller passes the result through wp_kses() with bgi_inline_allowed_tags().
 *
 * @param string $text Raw field value (get_field() with formatting off).
 * @return string
 */
function bgi_inline_text( $text ) {
	$text = str_replace( array( "\r\n", "\r" ), "\n", trim( (string) $text ) );
	// Drop scripts and styles with their contents; wp_kses() would only remove the tags.
	$text = preg_replace( '#<(script|style)\b[^>]*>.*?</\1>#is', '', $text );
	$text = preg_replace( '#</p>\s*<p[^>]*>#i', "\n\n", $text );
	$text = preg_replace( '#</?p[^>]*>#i', '', $text );
	$text = nl2br( trim( $text ), false );

	// In the words (not inside tags): encode quotes, as esc_html() did, so WordPress's later curly-quote
	// pass over the page leaves them straight as in the source design; then turn *words* into highlights.
	return preg_replace_callback(
		'/(^|>)([^<]+)/',
		function ( $matches ) {
			$words = str_replace( array( "'", '"' ), array( '&#039;', '&quot;' ), $matches[2] );
			return $matches[1] . preg_replace( '/[*]([^*]+)[*]/', '<span class="gold">$1</span>', $words );
		},
		$text
	);
}
