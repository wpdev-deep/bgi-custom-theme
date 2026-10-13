<?php
/**
 * Site header. Markup is ported from the source HTML in the header/footer phase;
 * all text comes from bgi_opt().
 *
 * @package BGI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php wp_head(); ?>
</head>
<body <?php body_class( 'ar' === bgi_lang() ? 'lang-ar' : 'lang-en' ); ?>>
<?php wp_body_open(); ?>

<?php
$is_ar      = ( 'ar' === bgi_lang() );
$logo_field = bgi_opt( 'logo' );
$logo_data  = bgi_get_image_data( $logo_field );
$brand_sub  = bgi_opt( 'brand_sub', $is_ar ? 'بسمة العالمية للتكامل ذ.م.م' : 'Basma Global Integration LLC' );
$cta_label  = bgi_opt( 'cta_text', $is_ar ? 'احجز عرضاً تجريبياً' : 'Book a Demo' );
?>

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
  <?php
  if ( has_nav_menu( 'primary' ) ) {
      wp_nav_menu(
          array(
              'theme_location' => 'primary',
              'container'      => false,
              'items_wrap'     => '%3$s',
              'walker'         => new BGI_Plain_Nav_Walker(),
              'fallback_cb'    => 'bgi_primary_menu_fallback',
          )
      );
  } else {
      bgi_primary_menu_fallback();
  }
  ?>
  <button class="btn btn-primary" onclick="openDemoModal()"><?php echo esc_html( $cta_label ); ?></button>
</div>

<div class="demo-overlay" id="demoOverlay"></div>
<div class="demo-modal" id="demoModal" role="dialog" aria-modal="true" aria-labelledby="demoModalTitle">
  <button class="demo-modal-close" id="demoModalClose" aria-label="<?php esc_attr_e( 'Close', 'bgi' ); ?>">&times;</button>
  <div class="eyebrow"><?php echo esc_html( bgi_opt( 'demo_eyebrow', false, $is_ar ? 'احجز عرضاً تجريبياً' : 'Book a Demo' ) ); ?></div>
  <h2 id="demoModalTitle"><?php echo esc_html( bgi_opt( 'demo_heading', false, $is_ar ? 'دعنا نريك كيف يمكن أن يبدو عقارك.' : "Let's show you what your property could look like." ) ); ?></h2>
  <p class="demo-modal-sub"><?php echo esc_html( bgi_opt( 'demo_sub', false, $is_ar ? 'أخبرنا عن عقارك والوقت المفضل لديك — سنتواصل معك للتأكيد.' : "Tell us a bit about your property and preferred time — we'll get back to you to confirm." ) ); ?></p>
  <form id="demoForm">
    <div class="form-group">
      <label for="dm-name"><?php echo esc_html( $is_ar ? 'الاسم الكامل' : 'Full Name' ); ?></label>
      <input type="text" id="dm-name" name="name" autocomplete="name" required>
    </div>
    <div class="form-group">
      <label for="dm-email"><?php echo esc_html( $is_ar ? 'البريد الإلكتروني' : 'Email Address' ); ?></label>
      <input type="email" id="dm-email" name="email" autocomplete="email" required>
    </div>
    <div class="form-group">
      <label for="dm-property"><?php echo esc_html( $is_ar ? 'اسم المنشأة / الشركة' : 'Property / Company Name' ); ?></label>
      <input type="text" id="dm-property" name="property" autocomplete="organization" required>
    </div>
    <div class="form-group">
      <label for="dm-time"><?php echo esc_html( $is_ar ? 'التاريخ والوقت المفضل' : 'Preferred Date & Time' ); ?></label>
      <input type="text" id="dm-time" name="preferred_time" placeholder="<?php echo esc_attr( $is_ar ? 'مثال: فترات ما بعد الظهر' : 'e.g. Weekday afternoons' ); ?>">
    </div>
    <div class="form-group">
      <label for="dm-message"><?php echo esc_html( $is_ar ? 'الرسالة' : 'Message' ); ?></label>
      <textarea id="dm-message" name="message"></textarea>
    </div>
    <button type="submit" class="btn btn-primary" style="width:100%"><?php echo esc_html( $is_ar ? 'طلب عرض تجريبي' : 'Request Demo' ); ?></button>
    <p class="form-note" id="demoFormNote"><?php echo esc_html( $is_ar ? 'شكراً لك! تم استلام طلبك — سنتواصل معك قريباً لتأكيد الموعد.' : 'Thanks! Your demo request has been received — we\'ll be in touch to confirm a time.' ); ?></p>
  </form>
</div>

<header id="siteHeader">
  <div class="container">
    <nav>
      <a href="<?php echo esc_url( bgi_home_url() ); ?>" class="logo" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
        <?php if ( ! empty( $logo_data['url'] ) ) : ?>
          <img src="<?php echo esc_url( $logo_data['url'] ); ?>" alt="<?php echo esc_attr( $logo_data['alt'] ); ?>" class="site-logo-img">
        <?php else : ?>
          <span class="logo-mark">B</span>
          <span class="logo-text">
            <span class="logo-word">BGI <span style="font-size:0.6rem;color:var(--muted-dim);">LLC</span></span>
            <span class="logo-sub"><?php echo esc_html( $brand_sub ); ?></span>
          </span>
        <?php endif; ?>
      </a>

      <div class="nav-links">
        <?php
        if ( has_nav_menu( 'primary' ) ) {
            wp_nav_menu(
                array(
                    'theme_location' => 'primary',
                    'container'      => false,
                    'items_wrap'     => '%3$s',
                    'walker'         => new BGI_Plain_Nav_Walker(),
                    'fallback_cb'    => 'bgi_primary_menu_fallback',
                )
            );
        } else {
            bgi_primary_menu_fallback();
        }
        ?>
      </div>

      <div class="nav-actions">
        <?php $switcher = bgi_get_language_switcher_data(); ?>
        <a class="lang-toggle" id="langToggle" href="<?php echo esc_url( $switcher['target_url'] ); ?>" aria-label="<?php echo esc_attr( $switcher['target_name'] ); ?>">
          <span id="langToggleLabel"><?php echo esc_html( $switcher['target_name'] ); ?></span>
        </a>
        <button class="hamburger" id="hamburgerBtn" aria-label="<?php esc_attr_e( 'Toggle navigation', 'bgi' ); ?>">
          <span></span><span></span><span></span>
        </button>
      </div>
    </nav>
  </div>
</header>

<main id="main">