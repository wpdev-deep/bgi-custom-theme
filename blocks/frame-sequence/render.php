<?php
/**
 * Block render template: Frame Sequence.
 *
 * One template for both scroll sequences in the design. The "style" field picks the
 * class set (cinema-* or twin-*), which sets the scroll length, shading and frame filter,
 * and the frames and caption used until the editor supplies their own.
 *
 * @package BGI
 *
 * @var array $block The block settings and attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$bgi_style = get_field( 'style' );
if ( ! in_array( $bgi_style, array( 'cinema', 'twin' ), true ) ) {
	$bgi_style = 'cinema';
}

// Design defaults per style: frames as "file => alt", and the caption heading.
$bgi_defaults = array(
	'cinema' => array(
		'frames'  => array(
			'lobby-1.jpg' => __( 'Property facade', 'bgi' ),
			'lobby-2.jpg' => __( 'Property facade digitizing', 'bgi' ),
			'lobby-3.jpg' => __( 'Property facade as an immersive experience', 'bgi' ),
			'lobby-5.jpg' => __( 'Arriving in the digital lobby', 'bgi' ),
			'lobby-4.jpg' => __( 'Threshold into the digital lobby', 'bgi' ),
		),
		'heading' => __( 'One continuous scroll — from the entrance to every corner your guests will remember.', 'bgi' ),
	),
	'twin'   => array(
		'frames'  => array(
			'corridor-1.jpg' => __( 'Space visualized as an immersive experience', 'bgi' ),
			'corridor-2.jpg' => __( 'Space visualized as an immersive experience', 'bgi' ),
			'corridor-3.jpg' => __( 'Space visualized as an immersive experience', 'bgi' ),
			'corridor-5.jpg' => __( 'Space visualized as an immersive experience', 'bgi' ),
		),
		'heading' => __( 'Every property has two versions. We build the one that never stops working for you.', 'bgi' ),
	),
);

// The eyebrow field is only shown for the twin style, so a value left over from it is ignored for cinema.
$bgi_eyebrow = ( 'twin' === $bgi_style ) ? get_field( 'eyebrow' ) : '';
$bgi_heading = trim( (string) get_field( 'heading' ) );
if ( '' === $bgi_heading ) {
	$bgi_heading = $bgi_defaults[ $bgi_style ]['heading'];
}

$bgi_frames = array();
$bgi_ids    = get_field( 'frames' );
if ( is_array( $bgi_ids ) ) {
	foreach ( $bgi_ids as $bgi_id ) {
		/*
		 * Each frame fills the screen with object-fit: cover. On screens taller than the image's shape
		 * it is drawn at the screen height and cropped at the sides, so it is wider than the screen.
		 * "100vw" made phones download a file a third of the size needed, which looked soft.
		 */
		$bgi_meta  = wp_get_attachment_metadata( (int) $bgi_id );
		$bgi_sizes = '100vw';
		if ( ! empty( $bgi_meta['width'] ) && ! empty( $bgi_meta['height'] ) ) {
			$bgi_sizes = sprintf(
				'(max-aspect-ratio: %1$d/%2$d) %3$svh, 100vw',
				(int) $bgi_meta['width'],
				(int) $bgi_meta['height'],
				round( 100 * $bgi_meta['width'] / $bgi_meta['height'], 2 )
			);
		}

		$bgi_img = wp_get_attachment_image(
			(int) $bgi_id,
			'full',
			false,
			array(
				'class'      => $bgi_style . '-frame',
				'data-frame' => count( $bgi_frames ),
				'loading'    => 'eager',
				'decoding'   => 'async',
				'sizes'      => $bgi_sizes,
			)
		);
		if ( '' !== $bgi_img ) {
			$bgi_frames[] = $bgi_img;
		}
	}
}

// No frames chosen, or none of them are usable images on this site: show the design's.
if ( empty( $bgi_frames ) ) {
	foreach ( $bgi_defaults[ $bgi_style ]['frames'] as $bgi_file => $bgi_alt ) {
		$bgi_frames[] = sprintf(
			'<img class="%1$s" data-frame="%2$d" src="%3$s" alt="%4$s" loading="eager" decoding="async">',
			esc_attr( $bgi_style . '-frame' ),
			count( $bgi_frames ),
			esc_url( BGI_URI . '/assets/img/' . $bgi_file ),
			esc_attr( $bgi_alt )
		);
	}
}

$bgi_classes = $bgi_style . '-wrap';
if ( ! empty( $block['className'] ) ) {
	$bgi_classes .= ' ' . $block['className'];
}
?>
<div class="<?php echo esc_attr( $bgi_classes ); ?>" data-bgi-frame-sequence="<?php echo esc_attr( $bgi_style ); ?>"<?php echo ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : ''; ?><?php bgi_highlight_color_attr( $block ); ?>>
	<div class="<?php echo esc_attr( $bgi_style ); ?>-progress" data-bgi-frame-progress></div>
	<div class="<?php echo esc_attr( $bgi_style ); ?>-stage">
		<?php
		foreach ( $bgi_frames as $bgi_frame ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Built by wp_get_attachment_image() or from escaped values above.
			echo $bgi_frame;
		}
		?>
		<div class="<?php echo esc_attr( $bgi_style ); ?>-shade"></div>
		<div class="<?php echo esc_attr( $bgi_style ); ?>-caption container">
			<?php if ( $bgi_eyebrow ) : ?>
				<div class="eyebrow"><?php echo esc_html( $bgi_eyebrow ); ?></div>
			<?php endif; ?>
			<h3><?php echo wp_kses( bgi_highlight( $bgi_heading ), array( 'span' => array( 'class' => array() ) ) ); ?></h3>
		</div>
	</div>
</div>
