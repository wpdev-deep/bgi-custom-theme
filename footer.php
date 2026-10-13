<?php
/**
 * Site footer: brand block, registration details, legal links and the WhatsApp button.
 *
 * @package BGI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$bgi_footer_logo = bgi_opt( 'footer_logo', bgi_opt( 'logo' ) );
$bgi_tagline     = bgi_opt( 'footer_tagline', __( "We don't capture spaces. We turn them into performance assets.", 'bgi' ) );
$bgi_domain      = bgi_opt( 'footer_domain', 'bgi.sa' );
$bgi_email       = sanitize_email( bgi_opt( 'footer_email', 'wael@bgi.sa' ) );
$bgi_cr_label    = trim( (string) bgi_opt( 'footer_cr_label', __( 'CR No.', 'bgi' ) ) );
$bgi_cr_number   = trim( (string) bgi_opt( 'footer_cr_number', '' ) );
$bgi_misa_label  = trim( (string) bgi_opt( 'footer_misa_label', __( 'MISA License No.', 'bgi' ) ) );
$bgi_misa_number = trim( (string) bgi_opt( 'footer_misa_number', '' ) );
$bgi_madein      = bgi_opt( 'footer_madein', __( 'Made in Saudi Arabia 🇸🇦', 'bgi' ) );

/* translators: %s: current year. */
$bgi_copyright = bgi_opt( 'footer_copyright', __( '© %s BGI. All rights reserved.', 'bgi' ) );
$bgi_copyright = str_replace( array( '%s', '{year}' ), wp_date( 'Y' ), $bgi_copyright );

$bgi_wa_number  = preg_replace( '/\D/', '', (string) bgi_opt( 'whatsapp_number', '966559608939' ) );
$bgi_wa_message = bgi_opt( 'whatsapp_message', __( "Hello BGI, I'd like to learn more about your immersive experience services.", 'bgi' ) );
$bgi_wa_url     = 'https://wa.me/' . $bgi_wa_number . '?text=' . rawurlencode( $bgi_wa_message );
?>
</main>

<footer>
	<div class="container">
		<div class="footer-top">
			<div>
				<?php get_template_part( 'template-parts/logo', null, array( 'image' => $bgi_footer_logo ) ); ?>
				<p class="footer-tagline"><?php echo esc_html( $bgi_tagline ); ?></p>
			</div>

			<div class="footer-contact" id="footerContactBlock">
				<div class="footer-contact-domain"><?php echo esc_html( $bgi_domain ); ?></div>
				<?php if ( $bgi_email ) : ?>
					<div class="footer-contact-email"><a href="<?php echo esc_url( 'mailto:' . $bgi_email ); ?>"><?php echo esc_html( $bgi_email ); ?></a></div>
				<?php endif; ?>
				<?php if ( $bgi_cr_number ) : ?>
					<div class="footer-contact-reg"><span><?php echo esc_html( $bgi_cr_label ); ?></span> <?php echo esc_html( $bgi_cr_number ); ?></div>
				<?php endif; ?>
				<?php if ( $bgi_misa_number ) : ?>
					<div class="footer-contact-reg"><span><?php echo esc_html( $bgi_misa_label ); ?></span> <?php echo esc_html( $bgi_misa_number ); ?></div>
				<?php endif; ?>
			</div>
		</div>

		<div class="footer-bottom">
			<span><?php echo esc_html( $bgi_copyright ); ?></span>
			<div class="footer-legal">
				<?php bgi_nav_menu( 'footer' ); ?>
			</div>
			<span><?php echo esc_html( $bgi_madein ); ?></span>
		</div>
	</div>
</footer>

<?php if ( $bgi_wa_number ) : ?>
	<a class="whatsapp-fab" id="whatsappFab" href="<?php echo esc_url( $bgi_wa_url ); ?>" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'Chat with BGI on WhatsApp', 'bgi' ); ?>">
		<svg viewBox="0 0 32 32" width="28" height="28" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
			<path d="M16.02 3C9.4 3 4 8.37 4 15c0 2.36.68 4.56 1.86 6.42L4 29l7.77-1.83A11.9 11.9 0 0 0 16.02 27C22.63 27 28 21.63 28 15S22.63 3 16.02 3Z" fill="#25D366"/>
			<path d="M22.1 18.28c-.33-.17-1.96-.97-2.27-1.08-.3-.11-.53-.17-.75.17-.22.33-.86 1.08-1.06 1.3-.2.22-.39.25-.72.08-.33-.17-1.4-.52-2.67-1.66-.99-.88-1.65-1.97-1.85-2.3-.19-.33-.02-.51.15-.68.15-.15.33-.39.5-.58.16-.2.22-.33.33-.55.11-.22.06-.42-.03-.58-.08-.17-.75-1.82-1.03-2.49-.27-.65-.55-.56-.75-.57-.19-.01-.42-.01-.64-.01-.22 0-.58.08-.89.42-.3.33-1.16 1.14-1.16 2.78s1.19 3.22 1.36 3.44c.16.22 2.34 3.58 5.68 5.02.79.34 1.41.55 1.89.7.79.25 1.52.22 2.09.13.64-.1 1.96-.8 2.24-1.58.28-.77.28-1.44.19-1.58-.08-.14-.3-.22-.63-.38Z" fill="#fff"/>
		</svg>
	</a>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
