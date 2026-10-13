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
