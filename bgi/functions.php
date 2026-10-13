<?php
/**
 * BGI theme bootstrap.
 *
 * @package BGI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BGI_VERSION', '1.0.0' );
define( 'BGI_DIR', get_template_directory() );
define( 'BGI_URI', get_template_directory_uri() );

require_once BGI_DIR . '/inc/setup.php';
require_once BGI_DIR . '/inc/assets.php';
require_once BGI_DIR . '/inc/blocks.php';
require_once BGI_DIR . '/inc/polylang.php';
require_once BGI_DIR . '/inc/options.php';