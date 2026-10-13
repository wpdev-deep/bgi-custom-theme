<?php
/**
 * Block render template: Hero.
 *
 * @package BGI
 *
 * @var array $block The block settings and attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$bgi_eyebrow         = get_field( 'eyebrow' );
$bgi_heading_line_1  = get_field( 'heading_line_1' );
$bgi_heading_line_2  = get_field( 'heading_line_2' );
$bgi_subtitle        = get_field( 'subtitle' );
$bgi_primary_label   = get_field( 'primary_label' );
$bgi_primary_url     = get_field( 'primary_url' );
$bgi_secondary_label = get_field( 'secondary_label' );
$bgi_secondary_url   = get_field( 'secondary_url' );
$bgi_scroll_label    = get_field( 'scroll_label' );

$bgi_classes = 'hero';
if ( ! empty( $block['className'] ) ) {
	$bgi_classes .= ' ' . $block['className'];
}
?>
<section class="<?php echo esc_attr( $bgi_classes ); ?>"<?php echo ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : ''; ?>>
	<div id="hero-scene"></div>
	<svg id="hero-fallback" class="hero-fallback" viewBox="0 0 1200 700" preserveAspectRatio="xMidYMax slice" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
		<defs>
			<linearGradient id="hf-fade" x1="0" y1="0" x2="0" y2="1">
				<stop offset="0%" stop-color="#c9963a" stop-opacity="0.55"/>
				<stop offset="100%" stop-color="#c9963a" stop-opacity="0.08"/>
			</linearGradient>
		</defs>
		<g stroke="url(#hf-fade)" stroke-width="1.4" fill="none">
			<rect x="90" y="230" width="150" height="380"/>
			<rect x="270" y="330" width="110" height="280"/>
			<rect x="410" y="120" width="130" height="490"/>
			<rect x="900" y="260" width="120" height="350"/>
			<rect x="1040" y="340" width="90" height="270"/>
		</g>
		<g stroke="#f0d78c" stroke-opacity="0.75" stroke-width="1.6" fill="none">
			<rect x="560" y="70" width="150" height="540"/>
			<line x1="560" y1="70" x2="710" y2="70"/>
		</g>
		<g stroke="#c9963a" stroke-opacity="0.22" stroke-width="1">
			<line x1="0" y1="610" x2="1200" y2="610"/>
			<line x1="0" y1="650" x2="1200" y2="650"/>
			<line x1="150" y1="610" x2="150" y2="700"/>
			<line x1="350" y1="610" x2="350" y2="700"/>
			<line x1="550" y1="610" x2="550" y2="700"/>
			<line x1="750" y1="610" x2="750" y2="700"/>
			<line x1="950" y1="610" x2="950" y2="700"/>
			<line x1="1150" y1="610" x2="1150" y2="700"/>
		</g>
	</svg>
	<div class="hero-shade"></div>

	<div class="hero-content container">
		<?php if ( $bgi_eyebrow ) : ?>
			<div class="eyebrow"><?php echo esc_html( $bgi_eyebrow ); ?></div>
		<?php endif; ?>

		<?php if ( $bgi_heading_line_1 || $bgi_heading_line_2 ) : ?>
			<h1 class="disp">
				<?php if ( $bgi_heading_line_1 ) : ?>
					<span><?php echo esc_html( $bgi_heading_line_1 ); ?></span>
				<?php endif; ?>
				<?php if ( $bgi_heading_line_1 && $bgi_heading_line_2 ) : ?>
					<br>
				<?php endif; ?>
				<?php if ( $bgi_heading_line_2 ) : ?>
					<span class="line2 serif"><?php echo esc_html( $bgi_heading_line_2 ); ?></span>
				<?php endif; ?>
			</h1>
		<?php endif; ?>

		<?php if ( $bgi_subtitle ) : ?>
			<p class="subtitle"><?php echo esc_html( $bgi_subtitle ); ?></p>
		<?php endif; ?>

		<?php if ( $bgi_primary_label || $bgi_secondary_label ) : ?>
			<div class="hero-ctas">
				<?php if ( $bgi_primary_label && $bgi_primary_url ) : ?>
					<a class="btn btn-primary" href="<?php echo esc_url( bgi_link( $bgi_primary_url ) ); ?>"><?php echo esc_html( $bgi_primary_label ); ?></a>
				<?php elseif ( $bgi_primary_label ) : ?>
					<button type="button" class="btn btn-primary" data-bgi-demo-open><?php echo esc_html( $bgi_primary_label ); ?></button>
				<?php endif; ?>

				<?php if ( $bgi_secondary_label && $bgi_secondary_url ) : ?>
					<a class="btn btn-outline" href="<?php echo esc_url( bgi_link( $bgi_secondary_url ) ); ?>"><?php echo esc_html( $bgi_secondary_label ); ?></a>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( $bgi_scroll_label ) : ?>
			<div class="scroll-cue">
				<span class="line"></span>
				<span><?php echo esc_html( $bgi_scroll_label ); ?></span>
			</div>
		<?php endif; ?>
	</div>
</section>
