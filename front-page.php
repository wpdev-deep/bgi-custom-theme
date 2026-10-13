<?php
/**
 * Front page: renders the block content of the static home page.
 *
 * @package BGI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) {
	the_post();
	the_content();
}

get_footer();
