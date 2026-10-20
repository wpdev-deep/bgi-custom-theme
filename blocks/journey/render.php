<?php
/**
 * Block render template: Journey.
 *
 * @package BGI
 *
 * @var array $block The block settings and attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$bgi_eyebrow  = get_field( 'eyebrow' );
$bgi_heading  = get_field( 'heading' );
$bgi_intro    = get_field( 'intro', false, false );
$bgi_rows     = get_field( 'items' );
$bgi_image_id = (int) get_field( 'image' );

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

// Until points are entered, show the three from the design.
if ( empty( $bgi_items ) ) {
	$bgi_items = array(
		array(
			'title'       => __( 'Visibility', 'bgi' ),
			'description' => __( 'Be discovered across high-intent platforms.', 'bgi' ),
		),
		array(
			'title'       => __( 'Conversion', 'bgi' ),
			'description' => __( 'Convert demand into confirmed bookings.', 'bgi' ),
		),
		array(
			'title'       => __( 'Experience', 'bgi' ),
			'description' => __( 'Create measurable engagement that drives decisions.', 'bgi' ),
		),
	);
}

$bgi_classes = 'block light';
if ( ! empty( $block['className'] ) ) {
	$bgi_classes .= ' ' . $block['className'];
}

// The header menu links to #journey, so that is the id unless another anchor is set.
$bgi_anchor = ! empty( $block['anchor'] ) ? $block['anchor'] : 'journey';
$bgi_gold   = array( 'span' => array( 'class' => array() ) );
?>
<section id="<?php echo esc_attr( $bgi_anchor ); ?>" class="<?php echo esc_attr( $bgi_classes ); ?>"<?php bgi_highlight_color_attr( $block ); ?>>
	<div class="container">
		<div class="split">
			<div class="reveal">
				<?php if ( $bgi_eyebrow ) : ?>
					<div class="eyebrow"><?php echo esc_html( $bgi_eyebrow ); ?></div>
				<?php endif; ?>

				<?php if ( $bgi_heading ) : ?>
					<h2 class="journey-heading"><?php echo wp_kses( bgi_highlight( $bgi_heading ), array( 'span' => array( 'class' => array() ) ) ); ?></h2>
				<?php endif; ?>

				<?php if ( $bgi_intro ) : ?>
					<p class="journey-intro"><?php echo wp_kses( bgi_inline_text( $bgi_intro ), bgi_inline_allowed_tags() ); ?></p>
				<?php endif; ?>

				<div class="num-list">
					<?php foreach ( $bgi_items as $bgi_item ) : ?>
						<div class="num-item">
							<div class="num-body">
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
			</div>

			<div class="frame reveal reveal-delay-1">
				<?php
				$bgi_image = '';
				if ( $bgi_image_id ) {
					/*
					 * The image is cropped to fill a 440px-high frame (320px on tablets and phones), so a
					 * landscape photo is drawn wider than the frame. Desktop: the larger of the frame's
					 * width (about 490px) and 440px times the image's shape.
					 */
					$bgi_meta    = wp_get_attachment_metadata( $bgi_image_id );
					$bgi_desktop = 580;
					if ( ! empty( $bgi_meta['width'] ) && ! empty( $bgi_meta['height'] ) ) {
						$bgi_desktop = max( 490, (int) round( 440 * $bgi_meta['width'] / $bgi_meta['height'] ) );
					}

					// WordPress would add "sizes=auto" to a lazy image, which sizes by the frame box, not the wider crop.
					add_filter( 'wp_img_tag_add_auto_sizes', '__return_false' );
					$bgi_image = wp_get_attachment_image(
						$bgi_image_id,
						'full',
						false,
						array(
							'loading'  => 'lazy',
							'decoding' => 'async',
							'sizes'    => '(max-width: 980px) 100vw, ' . $bgi_desktop . 'px',
						)
					);
					remove_filter( 'wp_img_tag_add_auto_sizes', '__return_false' );
				}

				// No image chosen, or the stored one is not a usable image on this site: show the design's.
				if ( '' === $bgi_image ) {
					$bgi_image = sprintf(
						'<img src="%1$s" alt="%2$s" loading="lazy" decoding="async">',
						esc_url( BGI_URI . '/assets/img/facade-image.jpg' ),
						esc_attr__( 'Hospitality property facade', 'bgi' )
					);
				}

				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Built by wp_get_attachment_image() or from escaped values above.
				echo $bgi_image;
				?>
			</div>
		</div>
	</div>
</section>
