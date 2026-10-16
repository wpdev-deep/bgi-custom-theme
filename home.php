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

<?php if ( have_posts() ) : ?>
	<section class="blog-grid">
		<?php
		while ( have_posts() ) {
			the_post();
			get_template_part( 'template-parts/content-card' );
		}
		?>
	</section>

	<?php
	$bgi_pagination = get_the_posts_pagination(
		array(
			'mid_size'           => 1,
			'prev_text'          => __( 'Previous', 'bgi' ),
			'next_text'          => __( 'Next', 'bgi' ),
			'screen_reader_text' => __( 'More articles', 'bgi' ),
		)
	);
	if ( $bgi_pagination ) {
		echo '<div class="blog-pagination"><div class="container">' . bgi_localize_digits_html( $bgi_pagination ) . '</div></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Markup built and escaped by core.
	}
	?>
<?php else : ?>
	<section class="blog-list">
		<div class="container">
			<p class="blog-empty"><?php esc_html_e( 'No articles yet. Check back soon.', 'bgi' ); ?></p>
		</div>
	</section>
<?php endif; ?>

<?php
get_footer();
