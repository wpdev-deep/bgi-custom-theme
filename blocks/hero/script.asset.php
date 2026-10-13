<?php
/**
 * Dependencies and version for the hero block's front-end script.
 *
 * @package BGI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	'dependencies' => array( 'bgi-three' ),
	'version'      => (string) filemtime( __DIR__ . '/script.js' ),
);
