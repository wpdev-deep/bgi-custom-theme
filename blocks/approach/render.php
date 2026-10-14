<?php
/**
 * Block render template: Approach.
 *
 * @package BGI
 *
 * @var array $block The block settings and attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$bgi_eyebrow = get_field( 'eyebrow' );
$bgi_heading = get_field( 'heading' );
$bgi_footer  = get_field( 'footer' );
$bgi_rows    = get_field( 'points' );

$bgi_points = array();
if ( is_array( $bgi_rows ) ) {
	foreach ( $bgi_rows as $bgi_row ) {
		$bgi_text = isset( $bgi_row['text'] ) ? trim( (string) $bgi_row['text'] ) : '';
		if ( '' !== $bgi_text ) {
			$bgi_points[] = $bgi_text;
		}
	}
}

// Until statements are entered, show the three from the design. *Starred* words are highlighted.
if ( empty( $bgi_points ) ) {
	$bgi_points = array(
		__( "We don't deliver *one-time projects*.", 'bgi' ),
		__( 'We build *long-term digital assets* that evolve with your property.', 'bgi' ),
		__( 'From pre-opening to daily operations, our solutions *integrate seamlessly* into your business.', 'bgi' ),
	);
}

$bgi_classes = 'block light';
if ( ! empty( $block['className'] ) ) {
	$bgi_classes .= ' ' . $block['className'];
}

$bgi_anchor = ! empty( $block['anchor'] ) ? $block['anchor'] : 'approach';
$bgi_span   = array( 'span' => array( 'class' => array() ) );
?>
<section id="<?php echo esc_attr( $bgi_anchor ); ?>" class="<?php echo esc_attr( $bgi_classes ); ?>">
	<div class="container">
		<?php if ( $bgi_eyebrow || $bgi_heading ) : ?>
			<div class="section-head center reveal">
				<?php if ( $bgi_eyebrow ) : ?>
					<div class="eyebrow"><?php echo esc_html( $bgi_eyebrow ); ?></div>
				<?php endif; ?>

				<?php if ( $bgi_heading ) : ?>
					<h2><?php echo wp_kses( bgi_highlight( $bgi_heading ), $bgi_span ); ?></h2>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="approach-points">
			<?php foreach ( $bgi_points as $bgi_i => $bgi_point ) : ?>
				<div class="approach-point reveal reveal-delay-<?php echo esc_attr( min( $bgi_i + 1, 5 ) ); ?>"><?php echo wp_kses( bgi_highlight( $bgi_point, 'hl' ), $bgi_span ); ?></div>
			<?php endforeach; ?>
		</div>

		<?php if ( $bgi_footer ) : ?>
			<p class="section-footer-line approach-footer"><?php echo wp_kses( bgi_highlight( $bgi_footer ), $bgi_span ); ?></p>
		<?php endif; ?>
	</div>
</section>
