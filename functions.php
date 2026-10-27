<?php
/**
 * BGI theme bootstrap: constants and includes only.
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
require_once BGI_DIR . '/inc/contact-form-7.php';
require_once BGI_DIR . '/inc/class-bgi-plain-nav-walker.php';
require_once BGI_DIR . '/inc/template-tags.php';
require_once BGI_DIR . '/inc/blog.php';
require_once BGI_DIR . '/inc/embeds.php';
require_once BGI_DIR . '/inc/svg.php';
