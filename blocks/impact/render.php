<?php
/**
 * Block render template: Impact.
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
$bgi_rows    = get_field( 'items' );

$bgi_items = array();
if ( is_array( $bgi_rows ) ) {
	foreach ( $bgi_rows as $bgi_row ) {
		$bgi_title = isset( $bgi_row['title'] ) ? trim( (string) $bgi_row['title'] ) : '';
		$bgi_desc  = isset( $bgi_row['description'] ) ? trim( (string) $bgi_row['description'] ) : '';
		if ( '' !== $bgi_title || '' !== $bgi_desc ) {
			$bgi_items[] = array(
				'title'       => $bgi_title,
				'description' => $bgi_desc,
			);
		}
	}
}

// Until outcomes are entered, show the four from the design.
if ( empty( $bgi_items ) ) {
	$bgi_items = array(
		array(
			'title'       => __( 'Engage Guests Before Arrival', 'bgi' ),
			'description' => __( 'Drive early interest.', 'bgi' ),
		),
		array(
			'title'       => __( 'Strengthen Emotional Connection', 'bgi' ),
			'description' => __( 'Create memorable experiences.', 'bgi' ),
		),
		array(
			'title'       => __( 'Elevate Brand Perception', 'bgi' ),
			'description' => __( 'Position your property ahead.', 'bgi' ),
		),
		array(
			'title'       => __( 'Influence Decision-Making', 'bgi' ),
			'description' => __( 'Turn interest into action.', 'bgi' ),
		),
	);
}

$bgi_classes = 'block dark';
if ( ! empty( $block['className'] ) ) {
	$bgi_classes .= ' ' . $block['className'];
}

$bgi_anchor = ! empty( $block['anchor'] ) ? $block['anchor'] : 'impact';
$bgi_gold   = array( 'span' => array( 'class' => array() ) );
?>
<section id="<?php echo esc_attr( $bgi_anchor ); ?>" class="<?php echo esc_attr( $bgi_classes ); ?>"<?php bgi_highlight_color_attr( $block ); ?>>
	<div class="container">
		<?php if ( $bgi_eyebrow || $bgi_heading ) : ?>
			<div class="section-head center reveal">
				<?php if ( $bgi_eyebrow ) : ?>
					<div class="eyebrow"><?php echo esc_html( $bgi_eyebrow ); ?></div>
				<?php endif; ?>

				<?php if ( $bgi_heading ) : ?>
					<h2><?php echo wp_kses( bgi_highlight( $bgi_heading ), $bgi_gold ); ?></h2>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="impact-grid">
			<?php foreach ( $bgi_items as $bgi_i => $bgi_item ) : ?>
				<div class="impact-card reveal reveal-delay-<?php echo esc_attr( ( $bgi_i % 2 ) + 1 ); ?> tilt">
					<div class="tilt-glare"></div>
					<div class="num"><?php echo esc_html( sprintf( '%02d', $bgi_i + 1 ) ); ?></div>
					<?php if ( '' !== $bgi_item['title'] ) : ?>
						<h3><?php echo wp_kses( bgi_highlight( $bgi_item['title'] ), $bgi_gold ); ?></h3>
					<?php endif; ?>
					<?php if ( '' !== $bgi_item['description'] ) : ?>
						<p><?php echo wp_kses( bgi_highlight( $bgi_item['description'] ), $bgi_gold ); ?></p>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>

		<?php if ( $bgi_footer ) : ?>
			<p class="section-footer-line impact-footer"><?php echo wp_kses( bgi_highlight( $bgi_footer ), $bgi_gold ); ?></p>
		<?php endif; ?>
	</div>
</section>
