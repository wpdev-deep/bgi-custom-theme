<?php
/**
 * Block render template: Trust.
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
$bgi_intro   = get_field( 'intro', false, false );
$bgi_footer  = get_field( 'footer' );
$bgi_rows    = get_field( 'clients' );

$bgi_clients = array();
if ( is_array( $bgi_rows ) ) {
	foreach ( $bgi_rows as $bgi_row ) {
		$bgi_name     = isset( $bgi_row['name'] ) ? trim( (string) $bgi_row['name'] ) : '';
		$bgi_location = isset( $bgi_row['location'] ) ? trim( (string) $bgi_row['location'] ) : '';
		if ( '' !== $bgi_name ) {
			$bgi_clients[] = array(
				'name'     => $bgi_name,
				'location' => $bgi_location,
			);
		}
	}
}

// Until clients are entered, show the eight from the design.
if ( empty( $bgi_clients ) ) {
	$bgi_clients = array(
		array(
			'name'     => 'St Regis',
			'location' => __( 'Al Mouj Muscat', 'bgi' ),
		),
		array(
			'name'     => 'Al Bustan Palace',
			'location' => __( 'A Ritz-Carlton Hotel', 'bgi' ),
		),
		array(
			'name'     => 'Mövenpick',
			'location' => __( 'Ghala Muscat', 'bgi' ),
		),
		array(
			'name'     => 'Crowne Plaza',
			'location' => __( 'IHG', 'bgi' ),
		),
		array(
			'name'     => 'Crowne Plaza',
			'location' => __( 'Muscat OCEC', 'bgi' ),
		),
		array(
			'name'     => 'Sheraton',
			'location' => __( 'Hotel & Resorts', 'bgi' ),
		),
		array(
			'name'     => 'MYSK',
			'location' => __( 'Al Mouj Muscat', 'bgi' ),
		),
		array(
			'name'     => 'EM Sherif',
			'location' => __( 'Restaurant', 'bgi' ),
		),
	);
}

$bgi_classes = 'block dark';
if ( ! empty( $block['className'] ) ) {
	$bgi_classes .= ' ' . $block['className'];
}

// The header menu links to #trust, so that is the id unless another anchor is set.
$bgi_anchor = ! empty( $block['anchor'] ) ? $block['anchor'] : 'trust';
$bgi_gold   = array( 'span' => array( 'class' => array() ) );
?>
<section id="<?php echo esc_attr( $bgi_anchor ); ?>" class="<?php echo esc_attr( $bgi_classes ); ?>"<?php bgi_highlight_color_attr( $block ); ?>>
	<div class="container">
		<?php if ( $bgi_eyebrow || $bgi_heading || $bgi_intro ) : ?>
			<div class="section-head center reveal">
				<?php if ( $bgi_eyebrow ) : ?>
					<div class="eyebrow"><?php echo esc_html( $bgi_eyebrow ); ?></div>
				<?php endif; ?>

				<?php if ( $bgi_heading ) : ?>
					<h2><?php echo wp_kses( bgi_highlight( $bgi_heading ), $bgi_gold ); ?></h2>
				<?php endif; ?>

				<?php if ( $bgi_intro ) : ?>
					<p><?php echo wp_kses( bgi_inline_text( $bgi_intro ), bgi_inline_allowed_tags() ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="client-grid">
			<?php foreach ( $bgi_clients as $bgi_i => $bgi_client ) : ?>
				<div class="client-card reveal reveal-delay-<?php echo esc_attr( ( $bgi_i % 4 ) + 1 ); ?> tilt">
					<div class="tilt-glare"></div>
					<div class="cname"><?php echo esc_html( $bgi_client['name'] ); ?></div>
					<?php if ( '' !== $bgi_client['location'] ) : ?>
						<div class="cloc"><?php echo esc_html( $bgi_client['location'] ); ?></div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>

		<?php if ( $bgi_footer ) : ?>
			<p class="section-footer-line trust-footer"><?php echo wp_kses( bgi_highlight( $bgi_footer ), $bgi_gold ); ?></p>
		<?php endif; ?>
	</div>
</section>
