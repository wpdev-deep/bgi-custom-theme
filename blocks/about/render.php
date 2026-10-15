<?php
/**
 * Block render template: About.
 *
 * @package BGI
 *
 * @var array $block The block settings and attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$bgi_eyebrow     = get_field( 'eyebrow' );
$bgi_heading     = get_field( 'heading' );
$bgi_body        = get_field( 'body' );
$bgi_quote       = get_field( 'quote' );
$bgi_quote_name  = get_field( 'quote_name' );
$bgi_quote_title = get_field( 'quote_title' );

$bgi_classes = 'block alt';
if ( ! empty( $block['className'] ) ) {
	$bgi_classes .= ' ' . $block['className'];
}

$bgi_anchor = ! empty( $block['anchor'] ) ? $block['anchor'] : 'about';
$bgi_gold   = array( 'span' => array( 'class' => array() ) );
?>
<section id="<?php echo esc_attr( $bgi_anchor ); ?>" class="<?php echo esc_attr( $bgi_classes ); ?>">
	<div class="container">
		<?php if ( $bgi_eyebrow || $bgi_heading || $bgi_body ) : ?>
			<div class="section-head center reveal">
				<?php if ( $bgi_eyebrow ) : ?>
					<div class="eyebrow"><?php echo esc_html( $bgi_eyebrow ); ?></div>
				<?php endif; ?>

				<?php if ( $bgi_heading ) : ?>
					<h2><?php echo wp_kses( bgi_highlight( $bgi_heading ), $bgi_gold ); ?></h2>
				<?php endif; ?>

				<?php if ( $bgi_body ) : ?>
					<p><?php echo esc_html( $bgi_body ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( $bgi_quote ) : ?>
			<div class="gm-quote reveal">
				<p class="gm-text"><?php echo esc_html( $bgi_quote ); ?></p>

				<?php if ( $bgi_quote_name || $bgi_quote_title ) : ?>
					<div class="gm-attribution">
						<?php if ( $bgi_quote_name ) : ?>
							<span class="gm-name"><?php echo esc_html( $bgi_quote_name ); ?></span>
						<?php endif; ?>
						<?php if ( $bgi_quote_title ) : ?>
							<span class="gm-title"><?php echo esc_html( $bgi_quote_title ); ?></span>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
