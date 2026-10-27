<?php
/**
 * Template Name: Legal
 *
 * Privacy Policy and Terms layout: title, last updated date and page content.
 *
 * @package BGI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Legal text keeps the straight quotes it was written with, as in the source design.
remove_filter( 'the_content', 'wptexturize' );
remove_filter( 'the_title', 'wptexturize' );

get_header();

while ( have_posts() ) {
	the_post();
	?>
	<section class="legal-hero">
		<div class="container">
			<div class="eyebrow"><?php esc_html_e( 'Legal', 'bgi' ); ?></div>
			<h1><?php the_title(); ?></h1>
			<p class="legal-meta">
				<?php
				/* translators: %s: date the page was last updated, e.g. August 9, 2026. */
				echo esc_html( sprintf( __( 'Last updated: %s', 'bgi' ), bgi_legal_updated_date() ) );
				?>
			</p>
		</div>
	</section>

	<section class="legal-content">
		<div class="container">
			<?php the_content(); ?>
		</div>
	</section>
	<?php
}

get_footer();
