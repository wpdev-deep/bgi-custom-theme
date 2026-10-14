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
