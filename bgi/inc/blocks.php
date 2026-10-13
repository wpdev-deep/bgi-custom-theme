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
function bgi_register_blocks() {
	foreach ( glob( BGI_DIR . '/blocks/*/block.json' ) as $file ) {
		register_block_type( dirname( $file ) );
	}
}

add_filter( 'block_categories_all', 'bgi_block_categories' );
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
