<?php
/**
 * Default page template.
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
