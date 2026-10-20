<?php
/**
 * Block render template: Process.
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
$bgi_rows    = get_field( 'steps' );

$bgi_steps = array();
if ( is_array( $bgi_rows ) ) {
	foreach ( $bgi_rows as $bgi_row ) {
		$bgi_title = isset( $bgi_row['title'] ) ? trim( (string) $bgi_row['title'] ) : '';
		$bgi_desc  = isset( $bgi_row['description'] ) ? trim( (string) $bgi_row['description'] ) : '';
		if ( '' !== $bgi_title || '' !== $bgi_desc ) {
			$bgi_steps[] = array(
				'title'       => $bgi_title,
				'description' => $bgi_desc,
			);
		}
	}
}

// Until steps are entered, show the five from the design.
if ( empty( $bgi_steps ) ) {
	$bgi_steps = array(
		array(
			'title'       => __( 'Discover', 'bgi' ),
			'description' => __( 'Align with your goals, audience and revenue priorities.', 'bgi' ),
		),
		array(
			'title'       => __( 'Capture', 'bgi' ),
			'description' => __( 'Digitize your space with precision and accuracy.', 'bgi' ),
		),
		array(
			'title'       => __( 'Create', 'bgi' ),
			'description' => __( 'Build immersive environments designed to perform.', 'bgi' ),
		),
		array(
			'title'       => __( 'Deliver', 'bgi' ),
			'description' => __( 'Deploy across all guest-facing platforms.', 'bgi' ),
		),
		array(
			'title'       => __( 'Optimize', 'bgi' ),
			'description' => __( 'Continuously refine based on performance insights.', 'bgi' ),
		),
	);
}

$bgi_classes = 'block alt';
if ( ! empty( $block['className'] ) ) {
	$bgi_classes .= ' ' . $block['className'];
}

// The header menu links to #process, so that is the id unless another anchor is set.
$bgi_anchor = ! empty( $block['anchor'] ) ? $block['anchor'] : 'process';
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

		<div class="process-row">
			<?php foreach ( $bgi_steps as $bgi_i => $bgi_step ) : ?>
				<div class="process-item reveal reveal-delay-<?php echo esc_attr( ( $bgi_i % 3 ) + 1 ); ?>">
					<div class="process-num"><?php echo esc_html( sprintf( '%02d', $bgi_i + 1 ) ); ?></div>
					<div>
						<?php if ( '' !== $bgi_step['title'] ) : ?>
							<h3><?php echo wp_kses( bgi_highlight( $bgi_step['title'] ), $bgi_gold ); ?></h3>
						<?php endif; ?>
						<?php if ( '' !== $bgi_step['description'] ) : ?>
							<p><?php echo wp_kses( bgi_highlight( $bgi_step['description'] ), $bgi_gold ); ?></p>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<?php if ( $bgi_footer ) : ?>
			<p class="section-footer-line process-footer"><?php echo wp_kses( bgi_highlight( $bgi_footer ), $bgi_gold ); ?></p>
		<?php endif; ?>

		<?php if ( $bgi_note ) : ?>
			<p class="section-note process-note"><?php echo wp_kses( bgi_inline_text( $bgi_note ), bgi_inline_allowed_tags() ); ?></p>
		<?php endif; ?>
	</div>
</section>
