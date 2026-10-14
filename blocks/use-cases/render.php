<?php
/**
 * Block render template: Use Cases.
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
		$bgi_icon  = isset( $bgi_row['icon'] ) ? trim( (string) $bgi_row['icon'] ) : '';
		$bgi_title = isset( $bgi_row['title'] ) ? trim( (string) $bgi_row['title'] ) : '';
		$bgi_desc  = isset( $bgi_row['description'] ) ? trim( (string) $bgi_row['description'] ) : '';
		if ( '' !== $bgi_title || '' !== $bgi_desc ) {
			$bgi_items[] = array(
				'icon'        => $bgi_icon,
				'title'       => $bgi_title,
				'description' => $bgi_desc,
			);
		}
	}
}

// Until use cases are entered, show the four from the design.
if ( empty( $bgi_items ) ) {
	$bgi_items = array(
		array(
			'icon'        => '🚀',
			'title'       => __( 'Pre-Opening', 'bgi' ),
			'description' => __( 'Sell before you open.', 'bgi' ),
		),
		array(
			'icon'        => '📣',
			'title'       => __( 'Marketing', 'bgi' ),
			'description' => __( 'Drive engagement.', 'bgi' ),
		),
		array(
			'icon'        => '💼',
			'title'       => __( 'Sales', 'bgi' ),
			'description' => __( 'Convert interest into bookings.', 'bgi' ),
		),
		array(
			'icon'        => '⚙️',
			'title'       => __( 'Operations', 'bgi' ),
			'description' => __( 'Train teams and optimize performance.', 'bgi' ),
		),
	);
}

$bgi_classes = 'block light';
if ( ! empty( $block['className'] ) ) {
	$bgi_classes .= ' ' . $block['className'];
}

$bgi_anchor = ! empty( $block['anchor'] ) ? $block['anchor'] : 'usecases';
$bgi_gold   = array( 'span' => array( 'class' => array() ) );
?>
<section id="<?php echo esc_attr( $bgi_anchor ); ?>" class="<?php echo esc_attr( $bgi_classes ); ?>">
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

		<div class="usecase-grid">
			<?php foreach ( $bgi_items as $bgi_i => $bgi_item ) : ?>
				<div class="usecase-card reveal reveal-delay-<?php echo esc_attr( ( $bgi_i % 4 ) + 1 ); ?> tilt">
					<div class="tilt-glare"></div>
					<?php if ( '' !== $bgi_item['icon'] ) : ?>
						<div class="usecase-icon" aria-hidden="true"><?php echo esc_html( $bgi_item['icon'] ); ?></div>
					<?php endif; ?>
					<?php if ( '' !== $bgi_item['title'] ) : ?>
						<h3><?php echo esc_html( $bgi_item['title'] ); ?></h3>
					<?php endif; ?>
					<?php if ( '' !== $bgi_item['description'] ) : ?>
						<p><?php echo esc_html( $bgi_item['description'] ); ?></p>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>

		<?php if ( $bgi_footer ) : ?>
			<p class="section-footer-line usecases-footer"><?php echo wp_kses( bgi_highlight( $bgi_footer ), $bgi_gold ); ?></p>
		<?php endif; ?>
	</div>
</section>
