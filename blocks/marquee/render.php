<?php
/**
 * Block render template: Marquee.
 *
 * @package BGI
 *
 * @var array $block The block settings and attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$bgi_items = array();
$bgi_rows  = get_field( 'items' );

if ( is_array( $bgi_rows ) ) {
	foreach ( $bgi_rows as $bgi_row ) {
		$bgi_text = isset( $bgi_row['text'] ) ? trim( (string) $bgi_row['text'] ) : '';
		if ( '' !== $bgi_text ) {
			$bgi_items[] = $bgi_text;
		}
	}
}

// Until keywords are entered, show the ones from the design.
if ( empty( $bgi_items ) ) {
	$bgi_items = array(
		__( 'Immersive Experiences', 'bgi' ),
		__( 'Virtual Reality', 'bgi' ),
		__( 'Spatial Intelligence', 'bgi' ),
		__( 'Hospitality', 'bgi' ),
	);
}

$bgi_classes = 'marquee';
if ( ! empty( $block['className'] ) ) {
	$bgi_classes .= ' ' . $block['className'];
}

// Repeat the keywords an even number of times for a seamless loop.
$bgi_repeats = 8;
?>
<div class="<?php echo esc_attr( $bgi_classes ); ?>"<?php echo ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : ''; ?>>
	<div class="marquee-track">
		<?php for ( $bgi_i = 0; $bgi_i < $bgi_repeats; $bgi_i++ ) : ?>
			<?php foreach ( $bgi_items as $bgi_item ) : ?>
				<span<?php echo $bgi_i > 0 ? ' aria-hidden="true"' : ''; ?>><?php echo esc_html( $bgi_item ); ?></span><span class="dot" aria-hidden="true">&middot;</span>
			<?php endforeach; ?>
		<?php endfor; ?>
	</div>
</div>
