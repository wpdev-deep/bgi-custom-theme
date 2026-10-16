<?php
/**
 * Single article. Hero, breadcrumb and body, ported from the blog article HTML.
 *
 * @package BGI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$bgi_posts_page = bgi_posts_page_id();
	$bgi_category   = bgi_post_category();
	$bgi_author     = get_the_author();
	/* translators: %s: post author's display name. */
	$bgi_byline     = $bgi_author ? sprintf( __( 'By %s', 'bgi' ), $bgi_author ) : '';
	$bgi_cta_title  = bgi_opt( 'article_cta_heading', __( 'See what this looks like for your own property.', 'bgi' ) );
	$bgi_cta_label  = bgi_opt( 'article_cta_label', __( 'Get in Touch', 'bgi' ) );
	$bgi_cta_url    = bgi_link( bgi_opt( 'article_cta_url', '#contact' ) );
	$bgi_related    = bgi_related_posts( get_the_ID() );
	?>

	<section class="article-hero">
		<div class="container">
			<nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'bgi' ); ?>">
				<a href="<?php echo esc_url( bgi_home_url() ); ?>"><?php esc_html_e( 'Home', 'bgi' ); ?></a>
				<?php if ( $bgi_posts_page ) : ?>
					/ <a href="<?php echo esc_url( get_permalink( $bgi_posts_page ) ); ?>"><?php echo esc_html( get_the_title( $bgi_posts_page ) ); ?></a>
				<?php endif; ?>
				/ <span aria-current="page"><?php the_title(); ?></span>
			</nav>
			<?php if ( $bgi_category ) : ?>
				<div class="eyebrow"><?php echo esc_html( $bgi_category->name ); ?></div>
			<?php endif; ?>
			<h1><?php the_title(); ?></h1>
			<div class="article-meta">
				<span><?php echo esc_html( bgi_post_date( _x( 'F j, Y', 'article date format', 'bgi' ) ) ); ?></span>
				<span aria-hidden="true">·</span>
				<span><?php echo esc_html( bgi_reading_time() ); ?></span>
				<?php if ( $bgi_byline ) : ?>
					<span aria-hidden="true">·</span>
					<span><?php echo esc_html( $bgi_byline ); ?></span>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<section class="article-body">
		<div class="container">
			<?php the_content(); ?>

			<?php if ( $bgi_cta_title || $bgi_cta_label ) : ?>
				<div class="article-cta">
					<?php if ( $bgi_cta_title ) : ?>
						<h3><?php echo esc_html( $bgi_cta_title ); ?></h3>
					<?php endif; ?>
					<?php if ( $bgi_cta_label && $bgi_cta_url ) : ?>
						<a class="btn btn-primary" href="<?php echo esc_url( $bgi_cta_url ); ?>"><?php echo esc_html( $bgi_cta_label ); ?></a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<?php if ( $bgi_related ) : ?>
		<section class="related">
			<div class="container">
				<h3><?php esc_html_e( 'Continue Reading', 'bgi' ); ?></h3>
				<?php foreach ( $bgi_related as $bgi_related_post ) : ?>
					<a href="<?php echo esc_url( get_permalink( $bgi_related_post ) ); ?>"><?php echo esc_html( get_the_title( $bgi_related_post ) ); ?></a>
				<?php endforeach; ?>
			</div>
		</section>
	<?php endif; ?>

	<?php
endwhile;

get_footer();
