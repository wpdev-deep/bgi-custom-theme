<?php
/**
 * Site logo link: the uploaded image, or the text mark when none is set.
 *
 * Expects $args['image'] to hold an ACF image value.
 *
 * @package BGI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$bgi_logo      = bgi_get_image_data( isset( $args['image'] ) ? $args['image'] : null );
$bgi_brand_sub = bgi_opt( 'brand_sub', __( 'Basma Global Integration LLC', 'bgi' ) );
?>
<a href="<?php echo esc_url( bgi_home_url() ); ?>" class="logo" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
	<?php if ( $bgi_logo ) : ?>
		<img
			src="<?php echo esc_url( $bgi_logo['url'] ); ?>"
			alt="<?php echo esc_attr( $bgi_logo['alt'] ); ?>"
			class="site-logo-img"
			<?php if ( $bgi_logo['width'] && $bgi_logo['height'] ) : ?>
				width="<?php echo esc_attr( $bgi_logo['width'] ); ?>"
				height="<?php echo esc_attr( $bgi_logo['height'] ); ?>"
			<?php endif; ?>
		>
	<?php else : ?>
		<span class="logo-mark">B</span>
		<span class="logo-text">
			<span class="logo-word">BGI <span class="logo-suffix">LLC</span></span>
			<span class="logo-sub"><?php echo esc_html( $bgi_brand_sub ); ?></span>
		</span>
	<?php endif; ?>
</a>
