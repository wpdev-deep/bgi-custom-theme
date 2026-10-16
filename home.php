<?php
/**
 * Blog index (Posts page). Hero + post cards, ported from blog.html.
 *
 * Polylang serves the translated Posts page and limits the loop to the current language.
 *
 * @package BGI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<section class="blog-hero">
	<div class="container">
		<div class="eyebrow"><?php echo esc_html( bgi_opt( 'blog_eyebrow', __( 'Insights', 'bgi' ) ) ); ?></div>
		<h1><?php echo esc_html( bgi_opt( 'blog_heading', __( 'Ideas on digital twins, virtual reality and spatial intelligence for hospitality.', 'bgi' ) ) ); ?></h1>
		<p><?php echo esc_html( bgi_opt( 'blog_intro', __( 'Practical, no-hype guides for hotel owners and operators evaluating what digital transformation actually means for their properties.', 'bgi' ) ) ); ?></p>
	</div>
</section>

<?php get_template_part( 'template-parts/post-grid' ); ?>

<?php
get_footer();
