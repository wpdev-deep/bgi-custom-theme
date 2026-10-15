<?php
/**
 * Block render template: Contact.
 *
 * @package BGI
 *
 * @var array $block      The block settings and attributes.
 * @var bool  $is_preview True while rendering in the block editor.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$bgi_eyebrow    = get_field( 'eyebrow' );
$bgi_heading    = get_field( 'heading' );
$bgi_subheading = get_field( 'subheading' );
$bgi_form_id    = (int) get_field( 'form' );
$bgi_rows       = get_field( 'items' );

$bgi_items = array();
if ( is_array( $bgi_rows ) ) {
	foreach ( $bgi_rows as $bgi_row ) {
		$bgi_value = isset( $bgi_row['value'] ) ? trim( (string) $bgi_row['value'] ) : '';
		if ( '' !== $bgi_value ) {
			$bgi_items[] = array(
				'icon'  => isset( $bgi_row['icon'] ) ? trim( (string) $bgi_row['icon'] ) : '',
				'label' => isset( $bgi_row['label'] ) ? trim( (string) $bgi_row['label'] ) : '',
				'value' => $bgi_value,
				'link'  => isset( $bgi_row['link'] ) ? trim( (string) $bgi_row['link'] ) : '',
			);
		}
	}
}

// Until details are entered, show the three from the design.
if ( empty( $bgi_items ) ) {
	$bgi_items = array(
		array(
			'icon'  => '✉️',
			'label' => __( 'Email', 'bgi' ),
			'value' => 'wael@bgi.sa',
			'link'  => '',
		),
		array(
			'icon'  => '📞',
			'label' => __( 'Phone', 'bgi' ),
			'value' => '+966 55 960 8939',
			'link'  => '',
		),
		array(
			'icon'  => '🌐',
			'label' => __( 'Website', 'bgi' ),
			'value' => 'bgi.sa',
			'link'  => 'https://bgi.sa',
		),
	);
}

// Emails and phone numbers link themselves when no link is given; phone numbers always read left to right.
foreach ( $bgi_items as $bgi_i => $bgi_item ) {
	$bgi_is_phone = (bool) preg_match( '/^\+?[\d\s().-]{6,}$/', $bgi_item['value'] );
	if ( '' === $bgi_item['link'] ) {
		if ( is_email( $bgi_item['value'] ) ) {
			$bgi_items[ $bgi_i ]['link'] = 'mailto:' . $bgi_item['value'];
		} elseif ( $bgi_is_phone ) {
			$bgi_items[ $bgi_i ]['link'] = 'tel:' . preg_replace( '/[^\d+]/', '', $bgi_item['value'] );
		}
	}
	$bgi_items[ $bgi_i ]['ltr'] = $bgi_is_phone;
}

// The chosen form, else the theme's contact form for the page's language. Nothing renders without Contact Form 7.
$bgi_form_id   = bgi_contact_form_id( $bgi_form_id );
$bgi_form_html = '';
if ( $bgi_form_id ) {
	$bgi_form_html = do_shortcode( sprintf( '[contact-form-7 id="%d" html_id="contactForm"]', $bgi_form_id ) );
}

$bgi_classes = 'block dark';
if ( ! empty( $block['className'] ) ) {
	$bgi_classes .= ' ' . $block['className'];
}

// The header menu links to #contact, so that is the id unless another anchor is set.
$bgi_anchor = ! empty( $block['anchor'] ) ? $block['anchor'] : 'contact';
$bgi_gold   = array( 'span' => array( 'class' => array() ) );
?>
<section id="<?php echo esc_attr( $bgi_anchor ); ?>" class="<?php echo esc_attr( $bgi_classes ); ?>">
	<div class="container">
		<div class="contact-wrap">
			<div class="contact-info reveal">
				<?php if ( $bgi_eyebrow ) : ?>
					<div class="eyebrow"><?php echo esc_html( $bgi_eyebrow ); ?></div>
				<?php endif; ?>

				<?php if ( $bgi_heading ) : ?>
					<h2><?php echo wp_kses( bgi_highlight( $bgi_heading ), $bgi_gold ); ?></h2>
				<?php endif; ?>

				<?php if ( $bgi_subheading ) : ?>
					<p><?php echo esc_html( $bgi_subheading ); ?></p>
				<?php endif; ?>

				<?php foreach ( $bgi_items as $bgi_item ) : ?>
					<div class="contact-item">
						<?php if ( '' !== $bgi_item['icon'] ) : ?>
							<div class="contact-icon" aria-hidden="true"><?php echo esc_html( $bgi_item['icon'] ); ?></div>
						<?php endif; ?>
						<div>
							<?php if ( '' !== $bgi_item['label'] ) : ?>
								<span class="clabel"><?php echo esc_html( $bgi_item['label'] ); ?></span>
							<?php endif; ?>
							<?php if ( '' !== $bgi_item['link'] ) : ?>
								<a class="cval" href="<?php echo esc_url( bgi_link( $bgi_item['link'] ) ); ?>"<?php echo $bgi_item['ltr'] ? ' dir="ltr"' : ''; ?>><?php echo esc_html( $bgi_item['value'] ); ?></a>
							<?php else : ?>
								<span class="cval"<?php echo $bgi_item['ltr'] ? ' dir="ltr"' : ''; ?>><?php echo esc_html( $bgi_item['value'] ); ?></span>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<?php if ( $bgi_form_html ) : ?>
				<div class="contact-form reveal reveal-delay-1">
					<?php echo $bgi_form_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Markup built and escaped by Contact Form 7. ?>
				</div>
			<?php elseif ( ! empty( $is_preview ) ) : ?>
				<div class="contact-form contact-form-empty">
					<?php esc_html_e( 'Choose a Contact Form 7 form in the block settings to show it here.', 'bgi' ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
