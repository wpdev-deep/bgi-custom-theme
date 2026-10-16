<?php
/**
 * 404, ported from 404.html. Shown inside the site header and footer; the header's
 * language toggle replaces the source page's own.
 *
 * @package BGI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$bgi_404_eyebrow       = bgi_opt( 'error_eyebrow', __( 'Lost in the Digital Twin', 'bgi' ) );
$bgi_404_heading       = bgi_opt( 'error_heading', __( "This space hasn't been built yet.", 'bgi' ) );
$bgi_404_text          = bgi_opt( 'error_text', __( "The page you're looking for doesn't exist or may have moved. Let's get you back to solid ground.", 'bgi' ) );
$bgi_404_home_label    = bgi_opt( 'error_home_label', __( 'Back to Home', 'bgi' ) );
$bgi_404_contact_label = bgi_opt( 'error_contact_label', __( 'Contact Us', 'bgi' ) );
$bgi_404_contact_url   = bgi_link( bgi_opt( 'error_contact_url', '#contact' ) );
?>

<section class="error-404">
	<div class="error-404-inner">
		<?php // Western digits in both languages, as in the source. ?>
		<div class="error-404-num disp">404</div>

		<?php if ( $bgi_404_eyebrow ) : ?>
			<div class="eyebrow"><?php echo esc_html( $bgi_404_eyebrow ); ?></div>
		<?php endif; ?>

		<h1><?php echo esc_html( $bgi_404_heading ); ?></h1>

		<?php if ( $bgi_404_text ) : ?>
			<p class="sub"><?php echo esc_html( $bgi_404_text ); ?></p>
		<?php endif; ?>

		<div class="actions">
			<?php if ( $bgi_404_home_label ) : ?>
				<a class="btn btn-primary" href="<?php echo esc_url( bgi_home_url() ); ?>"><?php echo esc_html( $bgi_404_home_label ); ?></a>
			<?php endif; ?>
			<?php if ( $bgi_404_contact_label && $bgi_404_contact_url ) : ?>
				<a class="btn btn-outline" href="<?php echo esc_url( $bgi_404_contact_url ); ?>"><?php echo esc_html( $bgi_404_contact_label ); ?></a>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php
get_footer();
