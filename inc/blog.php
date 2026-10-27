<?php
/**
 * Blog helpers used by home.php, single.php and the post card.
 *
 * @package BGI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Words per minute used for the reading time estimate.
 */
define( 'BGI_WORDS_PER_MINUTE', 200 );

/**
 * Localize digits in an HTML fragment's text, leaving tags and attributes alone.
 *
 * @param string $html HTML markup.
 * @return string
 */
function bgi_localize_digits_html( $html ) {
	if ( 'ar' !== bgi_lang() ) {
		return (string) $html;
	}
	return preg_replace_callback(
		'/>([^<]+)</',
		function ( $matches ) {
			return '>' . bgi_localize_digits( $matches[1] ) . '<';
		},
		(string) $html
	);
}

/**
 * ID of the Posts page in the current language.
 *
 * @return int 0 when no Posts page is set.
 */
function bgi_posts_page_id() {
	$page_id = (int) get_option( 'page_for_posts' );
	if ( $page_id && function_exists( 'pll_get_post' ) ) {
		$translated = pll_get_post( $page_id );
		$page_id    = $translated ? (int) $translated : $page_id;
	}
	return $page_id;
}

/**
 * Publish date of a post, in a format the theme translation can reorder per language.
 *
 * @param string           $format Date format.
 * @param int|WP_Post|null $post   Post, defaults to the current one.
 * @return string
 */
function bgi_post_date( $format, $post = null ) {
	return bgi_localize_digits( get_the_date( $format, $post ) );
}

/**
 * Reading time, e.g. "5 min read": the article's field, or estimated from its length.
 *
 * @param int|WP_Post|null $post Post, defaults to the current one.
 * @return string
 */
function bgi_reading_time( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return '';
	}

	$minutes = function_exists( 'get_field' ) ? (int) get_field( 'reading_time', $post->ID ) : 0;
	if ( $minutes < 1 ) {
		$text    = trim( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ) );
		$words   = '' === $text ? 0 : count( preg_split( '/\s+/u', $text ) );
		$minutes = max( 1, (int) ceil( $words / BGI_WORDS_PER_MINUTE ) );
	}

	/* translators: %s: number of minutes. */
	return bgi_localize_digits( sprintf( _n( '%s min read', '%s min read', $minutes, 'bgi' ), $minutes ) );
}

/**
 * Category shown above an article title. The default "Uncategorized" category is skipped.
 *
 * @param int|WP_Post|null $post Post, defaults to the current one.
 * @return WP_Term|null
 */
function bgi_post_category( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return null;
	}

	// Polylang gives every language its own copy of the default category.
	$default  = (int) get_option( 'default_category' );
	$defaults = function_exists( 'pll_get_term_translations' ) ? array_map( 'intval', pll_get_term_translations( $default ) ) : array();
	$defaults = array_merge( array( $default ), $defaults );

	foreach ( get_the_category( $post->ID ) as $category ) {
		if ( ! in_array( (int) $category->term_id, $defaults, true ) ) {
			return $category;
		}
	}
	return null;
}

/**
 * Other articles for "Continue Reading": same category first, then the most recent.
 *
 * @param int $post_id Current article.
 * @param int $count   Number of articles to return.
 * @return WP_Post[]
 */
function bgi_related_posts( $post_id, $count = 2 ) {
	$args = array(
		'post_type'           => 'post',
		'posts_per_page'      => $count,
		'post__not_in'        => array( $post_id ),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	);

	$related  = array();
	$category = bgi_post_category( $post_id );
	if ( $category ) {
		$related = get_posts( array_merge( $args, array( 'cat' => $category->term_id ) ) );
	}

	if ( count( $related ) < $count ) {
		$related = array_merge(
			$related,
			get_posts(
				array_merge(
					$args,
					array(
						'posts_per_page' => $count - count( $related ),
						'post__not_in'   => array_merge( array( $post_id ), wp_list_pluck( $related, 'ID' ) ),
					)
				)
			)
		);
	}

	// Listed oldest first, as the source articles list them.
	usort(
		$related,
		function ( $a, $b ) {
			return strcmp( $a->post_date, $b->post_date );
		}
	);

	return $related;
}

add_action( 'pre_get_posts', 'bgi_blog_order' );
/**
 * Blog and archives list oldest first; search shows articles only.
 *
 * @param WP_Query $query Query being set up.
 * @return void
 */
function bgi_blog_order( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( $query->is_home() || $query->is_archive() ) {
		$query->set( 'order', 'ASC' );
	}
	if ( $query->is_search() ) {
		$query->set( 'post_type', 'post' );
	}
}
