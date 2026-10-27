<?php
/**
 * Site header: preloader, overlays, mobile menu, demo modal and the main navigation.
 *
 * @package BGI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$bgi_cta_label = bgi_opt( 'cta_text', __( 'Book a Demo', 'bgi' ) );
$bgi_switcher  = bgi_get_language_switcher_data();

$bgi_demo_form_id = bgi_demo_form_id();
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="preloader" id="preloader">
	<div class="preloader-mark disp"><?php echo esc_html( bgi_opt( 'preloader_mark', 'BGI' ) ); ?></div>
	<div class="preloader-bar"></div>
</div>

<?php if ( ! is_404() ) : // The source's 404 page has no grain. ?>
	<div class="grain"></div>
<?php endif; ?>
<?php if ( is_front_page() ) : // Only the home page darkens its edges; inner pages are lighter without it. ?>
	<div class="vignette"></div>
<?php endif; ?>
<div class="cursor-ring" id="cursorRing"></div>
<div class="cursor-dot" id="cursorDot"></div>

<div class="menu-overlay" id="menuOverlay"></div>
<div class="mobile-menu" id="mobileMenu">
	<button type="button" class="mobile-menu-close" id="mobileMenuClose" aria-label="<?php esc_attr_e( 'Close menu', 'bgi' ); ?>">&times;</button>
	<?php bgi_nav_menu( 'primary' ); ?>
	<button type="button" class="btn btn-primary" data-bgi-demo-open><?php echo esc_html( $bgi_cta_label ); ?></button>
</div>

<div class="demo-overlay" id="demoOverlay"></div>
<div class="demo-modal" id="demoModal" role="dialog" aria-modal="true" aria-labelledby="demoModalTitle">
	<button type="button" class="demo-modal-close" id="demoModalClose" aria-label="<?php esc_attr_e( 'Close', 'bgi' ); ?>">&times;</button>
	<div class="eyebrow"><?php echo esc_html( bgi_opt( 'demo_eyebrow', __( 'Book a Demo', 'bgi' ) ) ); ?></div>
	<h2 id="demoModalTitle"><?php echo esc_html( bgi_opt( 'demo_heading', __( "Let's show you what your property could look like.", 'bgi' ) ) ); ?></h2>
	<p class="demo-modal-sub"><?php echo esc_html( bgi_opt( 'demo_sub', __( "Tell us a bit about your property and preferred time — we'll get back to you to confirm.", 'bgi' ) ) ); ?></p>
	<?php if ( $bgi_demo_form_id ) : ?>
		<?php echo do_shortcode( sprintf( '[contact-form-7 id="%d" html_id="demoForm"]', $bgi_demo_form_id ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Markup built and escaped by Contact Form 7. ?>
	<?php endif; ?>
</div>

<?php // Embed pop-up for card links (main.js). ?>
<div class="embed-overlay" id="embedOverlay"></div>
<div class="embed-modal" id="embedModal" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Virtual tour', 'bgi' ); ?>">
	<button type="button" class="embed-modal-close" id="embedModalClose" aria-label="<?php esc_attr_e( 'Close', 'bgi' ); ?>">&times;</button>
	<div class="embed-frame" id="embedFrame">
		<p class="embed-loading"><?php esc_html_e( 'Loading…', 'bgi' ); ?></p>
	</div>
</div>

<header id="siteHeader">
	<div class="container">
		<nav aria-label="<?php esc_attr_e( 'Primary', 'bgi' ); ?>">
			<?php get_template_part( 'template-parts/logo', null, array( 'image' => bgi_opt( 'logo' ) ) ); ?>

			<div class="nav-links">
				<?php bgi_nav_menu( 'primary' ); ?>
			</div>

			<div class="nav-actions">
				<?php if ( $bgi_switcher['target_url'] ) : ?>
					<a class="lang-toggle" id="langToggle" href="<?php echo esc_url( $bgi_switcher['target_url'] ); ?>" hreflang="<?php echo esc_attr( $bgi_switcher['target_slug'] ); ?>">
						<span id="langToggleLabel"><?php echo esc_html( $bgi_switcher['target_name'] ); ?></span>
					</a>
				<?php endif; ?>
				<button type="button" class="hamburger" id="hamburgerBtn" aria-controls="mobileMenu" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle navigation', 'bgi' ); ?>">
					<span></span><span></span><span></span>
				</button>
			</div>
		</nav>
	</div>
</header>

<main id="main">
