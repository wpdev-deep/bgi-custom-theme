<?php
/**
 * Archives and search results: categories, tags, dates, authors and ?s= searches.
 *
 * The source has no such pages, so they reuse the blog page's layout (blog.html): a hero naming
 * what is listed, then the same article cards and pagination.
 *
 * @package BGI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$bgi_eyebrow = bgi_opt( 'blog_eyebrow', __( 'Insights', 'bgi' ) );
$bgi_intro   = '';
$bgi_empty   = __( 'No articles yet. Check back soon.', 'bgi' );

if ( is_search() ) {
	$bgi_eyebrow = __( 'Search', 'bgi' );
	/* translators: %s: the words searched for. */
	$bgi_heading = sprintf( __( 'Results for “%s”', 'bgi' ), get_search_query( false ) );
	$bgi_found   = (int) $GLOBALS['wp_query']->found_posts;
	if ( $bgi_found ) {
		/* translators: %s: number of articles found. */
		$bgi_intro = bgi_localize_digits( sprintf( _n( '%s article found.', '%s articles found.', $bgi_found, 'bgi' ), number_format_i18n( $bgi_found ) ) );
	}
	$bgi_empty = __( 'No articles match your search. Try other words.', 'bgi' );
} else {
	// "Category: Digital Twins" reads as just "Digital Twins"; the eyebrow already says these are articles.
	add_filter( 'get_the_archive_title_prefix', '__return_empty_string' );
	$bgi_heading = wp_strip_all_tags( get_the_archive_title() );
	remove_filter( 'get_the_archive_title_prefix', '__return_empty_string' );
	$bgi_intro = wp_strip_all_tags( get_the_archive_description() );
}
?>

<section class="blog-hero">
	<div class="container">
		<?php if ( $bgi_eyebrow ) : ?>
			<div class="eyebrow"><?php echo esc_html( $bgi_eyebrow ); ?></div>
		<?php endif; ?>
		<h1><?php echo esc_html( $bgi_heading ); ?></h1>
		<?php if ( '' !== trim( $bgi_intro ) ) : ?>
			<p><?php echo esc_html( trim( $bgi_intro ) ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php
get_template_part( 'template-parts/post-grid', null, array( 'empty' => $bgi_empty ) );

get_footer();
