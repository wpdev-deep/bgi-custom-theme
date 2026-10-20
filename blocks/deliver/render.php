<?php
/**
 * Block render template: Deliver.
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
$bgi_note    = get_field( 'note', false, false );
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

// Until services are entered, show the four from the design.
if ( empty( $bgi_items ) ) {
	$bgi_items = array(
		array(
			'title'       => __( 'Immersive Experience', 'bgi' ),
			'description' => __( 'Explore your property remotely, increasing pre-arrival engagement.', 'bgi' ),
		),
		array(
			'title'       => __( 'Virtual Reality', 'bgi' ),
			'description' => __( 'Immerse guests and accelerate decision-making before arrival.', 'bgi' ),
		),
		array(
			'title'       => __( 'Interactive Experiences', 'bgi' ),
			'description' => __( 'Guide users with purpose and influence their journey.', 'bgi' ),
		),
		array(
			'title'       => __( 'Spatial Intelligence', 'bgi' ),
			'description' => __( 'Understand how your spaces perform and where value is created.', 'bgi' ),
		),
	);
}

$bgi_classes = 'block light';
if ( ! empty( $block['className'] ) ) {
	$bgi_classes .= ' ' . $block['className'];
}

// The header menu links to #deliver, so that is the id unless another anchor is set.
$bgi_anchor = ! empty( $block['anchor'] ) ? $block['anchor'] : 'deliver';
$bgi_gold   = array( 'span' => array( 'class' => array() ) );
?>
<section id="<?php echo esc_attr( $bgi_anchor ); ?>" class="<?php echo esc_attr( $bgi_classes ); ?>"<?php bgi_highlight_color_attr( $block ); ?>>
	<div class="container">
		<?php if ( $bgi_eyebrow || $bgi_heading ) : ?>
			<div class="section-head reveal">
				<?php if ( $bgi_eyebrow ) : ?>
					<div class="eyebrow"><?php echo esc_html( $bgi_eyebrow ); ?></div>
				<?php endif; ?>

				<?php if ( $bgi_heading ) : ?>
					<h2><?php echo wp_kses( bgi_highlight( $bgi_heading ), $bgi_gold ); ?></h2>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="feature-grid reveal">
			<?php foreach ( $bgi_items as $bgi_i => $bgi_item ) : ?>
				<div class="feature-card reveal reveal-delay-<?php echo esc_attr( ( $bgi_i % 2 ) + 1 ); ?> tilt">
					<div class="tilt-glare"></div>
					<div class="feature-num"><?php echo esc_html( sprintf( '%02d', $bgi_i + 1 ) ); ?></div>
					<div>
						<?php if ( '' !== $bgi_item['title'] ) : ?>
							<h3><?php echo wp_kses( bgi_highlight( $bgi_item['title'] ), $bgi_gold ); ?></h3>
						<?php endif; ?>
						<?php if ( '' !== $bgi_item['description'] ) : ?>
							<p><?php echo wp_kses( bgi_highlight( $bgi_item['description'] ), $bgi_gold ); ?></p>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<?php if ( $bgi_footer ) : ?>
			<p class="section-footer-line"><?php echo wp_kses( bgi_highlight( $bgi_footer ), $bgi_gold ); ?></p>
		<?php endif; ?>

		<?php if ( $bgi_note ) : ?>
			<p class="section-note"><?php echo esc_html( '📍 ' ) . wp_kses( bgi_inline_text( $bgi_note ), bgi_inline_allowed_tags() ); ?></p>
		<?php endif; ?>
	</div>
</section>
