<?php
/**
 * Article cards for the current query, with pagination, or a message when there are none.
 * Used by the blog page (home.php) and by archives and search results (index.php).
 *
 * @package BGI
 *
 * @var array $args {
 *     Optional. Passed by get_template_part().
 *
 *     @type string $empty Message shown when the query has no posts.
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$bgi_empty = ! empty( $args['empty'] ) ? $args['empty'] : __( 'No articles yet. Check back soon.', 'bgi' );
?>

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
			<p class="blog-empty"><?php echo esc_html( $bgi_empty ); ?></p>
		</div>
	</section>
<?php endif; ?>
