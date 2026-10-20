<?php
/**
 * ACF block registration: every blocks/<slug>/block.json is registered.
 *
 * @package BGI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'bgi_register_blocks' );
/**
 * Register each block folder that ships a block.json.
 *
 * @return void
 */
function bgi_register_blocks() {
	$files = glob( BGI_DIR . '/blocks/*/block.json' );
	if ( empty( $files ) ) {
		return;
	}
	foreach ( $files as $file ) {
		register_block_type( dirname( $file ) );
	}
}

add_action( 'acf/input/admin_enqueue_scripts', 'bgi_acf_admin_styles' );
/**
 * Small tweaks to ACF field controls, loaded wherever ACF renders fields.
 *
 * A gallery field whose wrapper class is "bgi-gallery-no-sort" loses its "Bulk actions"
 * sort dropdown, since ACF has no setting to turn it off. Images can still be dragged into order.
 *
 * @return void
 */
function bgi_acf_admin_styles() {
	wp_add_inline_style( 'acf-input', '.bgi-gallery-no-sort .acf-gallery-sort { display: none; }' );

	/*
	 * ACF clips each text input's wrapper (overflow: hidden). At some browser zoom levels the input's 1px
	 * border then falls on a fractional pixel and is cut off at the left, as seen in the expanded block
	 * editor. Block fields use no prepend/append boxes, which is what the clipping is for.
	 */
	wp_add_inline_style( 'acf-input', '.acf-block-fields .acf-input-wrap { overflow: visible; }' );

	// Colour pickers offer the theme palette (theme.json) as swatches, light gold first; any colour can still be typed.
	wp_add_inline_script(
		'acf-input',
		sprintf(
			'acf.add_filter( "color_picker_args", function ( args ) { args.palettes = %s; return args; } );',
			wp_json_encode( bgi_color_palette() )
		)
	);

	/*
	 * Section paragraph editors read like a form field: the theme's editor styles (the site stylesheet,
	 * with its black page) are left out, the text is dark on white, and the box starts short.
	 */
	wp_add_inline_script(
		'acf-input',
		sprintf(
			'acf.add_filter( "wysiwyg_tinymce_settings", function ( init ) { init.content_css = false; init.content_style = %s; init.height = 120; return init; } );',
			wp_json_encode( 'html, body.mce-content-body { background: #fff; color: #1e1e1e; } body.mce-content-body { margin: 10px 12px; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Tajawal", sans-serif; font-size: 14px; line-height: 1.6; font-weight: 400; } body.mce-content-body a { color: #2271b1; } body.mce-content-body p { margin: 0 0 8px; }' )
		)
	);
}

/**
 * Hex colours of the theme palette in theme.json, light gold (the design's highlight colour) first.
 *
 * @return string[]
 */
function bgi_color_palette() {
	$colors  = array();
	$palette = wp_get_global_settings( array( 'color', 'palette', 'theme' ) );
	foreach ( (array) $palette as $entry ) {
		if ( ! empty( $entry['color'] ) && sanitize_hex_color( $entry['color'] ) ) {
			$colors[ $entry['slug'] ] = $entry['color'];
		}
	}
	if ( isset( $colors['gold-light'] ) ) {
		$colors = array( 'gold-light' => $colors['gold-light'] ) + $colors;
	}
	return array_values( $colors );
}

add_filter( 'acf/fields/wysiwyg/toolbars', 'bgi_wysiwyg_toolbars' );
/**
 * "BGI Inline" toolbar for section paragraphs: bold, italic and links only, so the text stays one
 * paragraph in the design.
 *
 * @param array $toolbars Toolbars keyed by name, each a list of button rows.
 * @return array
 */
function bgi_wysiwyg_toolbars( $toolbars ) {
	$toolbars['BGI Inline'] = array( 1 => array( 'bold', 'italic', 'link', 'unlink' ) );
	return $toolbars;
}

add_filter( 'tiny_mce_before_init', 'bgi_editor_direction' );
/**
 * Type right to left in the text editors of an Arabic page or post.
 *
 * @param array $init TinyMCE settings.
 * @return array
 */
function bgi_editor_direction( $init ) {
	$post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only check of the post being edited.
	if ( $post_id && function_exists( 'pll_get_post_language' ) && 'ar' === pll_get_post_language( $post_id ) ) {
		$init['directionality'] = 'rtl';
	}
	return $init;
}

add_filter( 'block_categories_all', 'bgi_block_categories' );
/**
 * Add the "BGI" category to the top of the block inserter.
 *
 * @param array[] $categories Block categories.
 * @return array[]
 */
function bgi_block_categories( $categories ) {
	array_unshift(
		$categories,
		array(
			'slug'  => 'bgi',
			'title' => __( 'BGI', 'bgi' ),
		)
	);
	return $categories;
}
