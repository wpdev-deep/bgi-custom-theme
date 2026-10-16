<?php
/**
 * Post card for the blog grid. Uses the current post in the loop.
 *
 * @package BGI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<a class="post-card" href="<?php the_permalink(); ?>">
	<div class="post-meta">
		<span><?php echo esc_html( bgi_post_date( _x( 'M j, Y', 'blog card date format', 'bgi' ) ) ); ?></span>
		<span aria-hidden="true">·</span>
		<span><?php echo esc_html( bgi_reading_time() ); ?></span>
	</div>
	<h2><?php the_title(); ?></h2>
	<p><?php echo esc_html( get_the_excerpt() ); ?></p>
	<span class="post-read"><?php esc_html_e( 'Read Article', 'bgi' ); ?> <span aria-hidden="true"><?php echo is_rtl() ? '&larr;' : '&rarr;'; ?></span></span>
</a>
