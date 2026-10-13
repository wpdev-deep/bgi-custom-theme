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

<div class="grain"></div>
<div class="vignette"></div>
<div class="cursor-ring" id="cursorRing"></div>
<div class="cursor-dot" id="cursorDot"></div>

<div class="menu-overlay" id="menuOverlay"></div>
<div class="mobile-menu" id="mobileMenu">
	<?php bgi_nav_menu( 'primary' ); ?>
	<button type="button" class="btn btn-primary" data-bgi-demo-open><?php echo esc_html( $bgi_cta_label ); ?></button>
</div>

<div class="demo-overlay" id="demoOverlay"></div>
<div class="demo-modal" id="demoModal" role="dialog" aria-modal="true" aria-labelledby="demoModalTitle">
	<button type="button" class="demo-modal-close" id="demoModalClose" aria-label="<?php esc_attr_e( 'Close', 'bgi' ); ?>">&times;</button>
	<div class="eyebrow"><?php echo esc_html( bgi_opt( 'demo_eyebrow', __( 'Book a Demo', 'bgi' ) ) ); ?></div>
	<h2 id="demoModalTitle"><?php echo esc_html( bgi_opt( 'demo_heading', __( "Let's show you what your property could look like.", 'bgi' ) ) ); ?></h2>
	<p class="demo-modal-sub"><?php echo esc_html( bgi_opt( 'demo_sub', __( "Tell us a bit about your property and preferred time — we'll get back to you to confirm.", 'bgi' ) ) ); ?></p>
	<form id="demoForm">
		<div class="form-group">
			<label for="dm-name"><?php esc_html_e( 'Full Name', 'bgi' ); ?></label>
			<input type="text" id="dm-name" name="name" autocomplete="name" required>
		</div>
		<div class="form-group">
			<label for="dm-email"><?php esc_html_e( 'Email Address', 'bgi' ); ?></label>
			<input type="email" id="dm-email" name="email" autocomplete="email" required>
		</div>
		<div class="form-group">
			<label for="dm-property"><?php esc_html_e( 'Property / Company Name', 'bgi' ); ?></label>
			<input type="text" id="dm-property" name="property" autocomplete="organization" required>
		</div>
		<div class="form-group">
			<label for="dm-time"><?php esc_html_e( 'Preferred Date & Time', 'bgi' ); ?></label>
			<input type="text" id="dm-time" name="preferred_time" placeholder="<?php esc_attr_e( 'e.g. Weekday afternoons', 'bgi' ); ?>">
		</div>
		<div class="form-group">
			<label for="dm-message"><?php esc_html_e( 'Message', 'bgi' ); ?></label>
			<textarea id="dm-message" name="message"></textarea>
		</div>
		<button type="submit" class="btn btn-primary btn-block"><?php esc_html_e( 'Request Demo', 'bgi' ); ?></button>
		<p class="form-note" id="demoFormNote"><?php esc_html_e( "Thanks! Your demo request has been received — we'll be in touch to confirm a time.", 'bgi' ); ?></p>
	</form>
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
					<a class="lang-toggle" id="langToggle" href="<?php echo esc_url( $bgi_switcher['target_url'] ); ?>" lang="<?php echo esc_attr( $bgi_switcher['target_slug'] ); ?>" hreflang="<?php echo esc_attr( $bgi_switcher['target_slug'] ); ?>">
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
