<?php
/**
 * Site footer.
 *
 * @package BGI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
</main>

<?php
$is_ar            = ( 'ar' === bgi_lang() );
$footer_logo      = bgi_opt( 'footer_logo' );
if ( empty( $footer_logo ) ) {
    $footer_logo = bgi_opt( 'logo' );
}
$footer_logo_data = bgi_get_image_data( $footer_logo );
$brand_sub        = bgi_opt( 'brand_sub', $is_ar ? 'بسمة العالمية للتكامل ذ.م.م' : 'Basma Global Integration LLC' );
$footer_tagline   = bgi_opt( 'footer_tagline', $is_ar ? 'نحن لا نوثّق المساحات. نحوّلها إلى أصول أداء حقيقية.' : 'We don\'t capture spaces. We turn them into performance assets.' );

$site_domain      = bgi_opt( 'footer_domain' ) ?: bgi_opt( 'domain', 'bgi.sa' );
$site_email       = bgi_opt( 'footer_email' ) ?: bgi_opt( 'email', 'wael@bgi.sa' );
$cr_label         = bgi_opt( 'footer_cr_label', $is_ar ? 'رقم السجل التجاري' : 'CR No.' );
$cr_number        = bgi_opt( 'footer_cr_number' ) ?: bgi_opt( 'cr_number', '1234567890' );
$misa_label       = bgi_opt( 'footer_misa_label', $is_ar ? 'رقم ترخيص هيئة الاستثمار (MISA)' : 'MISA License No.' );
$misa_number      = bgi_opt( 'footer_misa_number' ) ?: bgi_opt( 'misa_number', '1234567890' );

$copyright        = bgi_opt( 'footer_copyright', $is_ar ? '© 2026 بي جي آي. جميع الحقوق محفوظة.' : '© 2026 BGI. All rights reserved.' );
$madein           = bgi_opt( 'footer_madein', $is_ar ? 'صُنع في المملكة العربية السعودية 🇸🇦' : 'Made in Saudi Arabia 🇸🇦' );

$wa_num           = bgi_opt( 'whatsapp_number' ) ?: bgi_opt( 'whatsapp', '966559608939' );
$wa_msg           = bgi_opt( 'whatsapp_message', $is_ar ? 'مرحباً بسمة العالمية، أود معرفة المزيد عن خدمات التجارب الغامرة.' : 'Hello BGI, I\'d like to learn more about your immersive experience services.' );
$wa_digits        = preg_replace( '/\D/', '', $wa_num );
$wa_url           = 'https://wa.me/' . $wa_digits . '?text=' . rawurlencode( $wa_msg );
?>

<footer>
  <div class="container">
    <div class="footer-top">
      <div>
        <a href="<?php echo esc_url( bgi_home_url() ); ?>" class="logo" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
          <?php if ( ! empty( $footer_logo_data['url'] ) ) : ?>
            <img src="<?php echo esc_url( $footer_logo_data['url'] ); ?>" alt="<?php echo esc_attr( $footer_logo_data['alt'] ); ?>" class="site-logo-img">
          <?php else : ?>
            <span class="logo-mark">B</span>
            <span class="logo-text">
              <span class="logo-word">BGI <span style="font-size:0.6rem;color:var(--muted-dim);">LLC</span></span>
              <span class="logo-sub"><?php echo esc_html( $brand_sub ); ?></span>
            </span>
          <?php endif; ?>
        </a>
        <p class="footer-tagline"><?php echo esc_html( $footer_tagline ); ?></p>
      </div>

      <div style="text-align:right" id="footerContactBlock">
        <div class="clabel" style="font-size:0.7rem;color:var(--muted-dim);text-transform:uppercase;letter-spacing:0.1em;"><?php echo esc_html( $site_domain ); ?></div>
        <div style="color:var(--muted);font-size:0.9rem;margin-top:4px;"><a href="mailto:<?php echo esc_attr( $site_email ); ?>"><?php echo esc_html( $site_email ); ?></a></div>
        <div style="color:var(--muted-dim);font-size:0.76rem;margin-top:14px;"><?php echo esc_html( $cr_label ); ?> <?php echo esc_html( $cr_number ); ?></div>
        <div style="color:var(--muted-dim);font-size:0.76rem;margin-top:2px;"><?php echo esc_html( $misa_label ); ?> <?php echo esc_html( $misa_number ); ?></div>
      </div>
    </div>

    <div class="footer-bottom">
      <span><?php echo esc_html( $copyright ); ?></span>
      <div class="footer-legal">
        <?php
        if ( has_nav_menu( 'footer' ) ) {
            wp_nav_menu(
                array(
                    'theme_location' => 'footer',
                    'container'      => false,
                    'items_wrap'     => '%3$s',
                    'walker'         => new BGI_Plain_Nav_Walker(),
                    'fallback_cb'    => 'bgi_footer_menu_fallback',
                )
            );
        } else {
            bgi_footer_menu_fallback();
        }
        ?>
      </div>
      <span><?php echo esc_html( $madein ); ?></span>
    </div>
  </div>
</footer>

<a class="whatsapp-fab" id="whatsappFab" href="<?php echo esc_url( $wa_url ); ?>" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'Chat with BGI on WhatsApp', 'bgi' ); ?>">
  <svg viewBox="0 0 32 32" width="28" height="28" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M16.02 3C9.4 3 4 8.37 4 15c0 2.36.68 4.56 1.86 6.42L4 29l7.77-1.83A11.9 11.9 0 0 0 16.02 27C22.63 27 28 21.63 28 15S22.63 3 16.02 3Z" fill="#25D366"/>
    <path d="M22.1 18.28c-.33-.17-1.96-.97-2.27-1.08-.3-.11-.53-.17-.75.17-.22.33-.86 1.08-1.06 1.3-.2.22-.39.25-.72.08-.33-.17-1.4-.52-2.67-1.66-.99-.88-1.65-1.97-1.85-2.3-.19-.33-.02-.51.15-.68.15-.15.33-.39.5-.58.16-.2.22-.33.33-.55.11-.22.06-.42-.03-.58-.08-.17-.75-1.82-1.03-2.49-.27-.65-.55-.56-.75-.57-.19-.01-.42-.01-.64-.01-.22 0-.58.08-.89.42-.3.33-1.16 1.14-1.16 2.78s1.19 3.22 1.36 3.44c.16.22 2.34 3.58 5.68 5.02.79.34 1.41.55 1.89.7.79.25 1.52.22 2.09.13.64-.1 1.96-.8 2.24-1.58.28-.77.28-1.44.19-1.58-.08-.14-.3-.22-.63-.38Z" fill="#fff"/>
  </svg>
</a>

<?php wp_footer(); ?>
</body>
</html>