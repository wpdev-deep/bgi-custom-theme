<?php
/**
 * Dependencies and version for the frame sequence block's front-end script.
 *
 * @package BGI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	'dependencies' => array(),
	'version'      => (string) filemtime( __DIR__ . '/script.js' ),
);
