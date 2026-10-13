<?php
/**
 * Nav walker that prints a flat list of plain links.
 *
 * @package BGI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Outputs each menu item as a bare <a>, matching the source header and footer markup.
 */
class BGI_Plain_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * Sub-menus are not part of the design, so no list wrapper is opened.
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param int      $depth  Depth of menu item.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 * @return void
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {}

	/**
	 * Sub-menus are not part of the design, so no list wrapper is closed.
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param int      $depth  Depth of menu item.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 * @return void
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {}

	/**
	 * Print one menu item as a link.
	 *
	 * @param string   $output            Used to append additional content (passed by reference).
	 * @param WP_Post  $data_object       Menu item data object.
	 * @param int      $depth             Depth of menu item.
	 * @param stdClass $args              An object of wp_nav_menu() arguments.
	 * @param int      $current_object_id Optional. ID of the current menu item.
	 * @return void
	 */
	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
		$item = $data_object;

		if ( empty( $item->url ) ) {
			return;
		}

		$atts = array(
			'href' => bgi_link( $item->url ),
		);
		if ( ! empty( $item->target ) ) {
			$atts['target'] = $item->target;
		}
		$rel = trim( (string) $item->xfn );
		if ( '_blank' === $item->target && false === strpos( $rel, 'noopener' ) ) {
			$rel = trim( $rel . ' noopener' );
		}
		if ( '' !== $rel ) {
			$atts['rel'] = $rel;
		}
		if ( ! empty( $item->current ) ) {
			$atts['aria-current'] = 'page';
		}

		$attributes = '';
		foreach ( $atts as $name => $value ) {
			$value       = ( 'href' === $name ) ? esc_url( $value ) : esc_attr( $value );
			$attributes .= ' ' . $name . '="' . $value . '"';
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		/** This filter is documented in wp-includes/class-walker-nav-menu.php */
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$output .= '<a' . $attributes . '>' . esc_html( wp_strip_all_tags( $title ) ) . '</a>';
	}

	/**
	 * Items are bare links, so there is no closing tag to print.
	 *
	 * @param string   $output      Used to append additional content (passed by reference).
	 * @param WP_Post  $data_object Menu item data object.
	 * @param int      $depth       Depth of menu item.
	 * @param stdClass $args        An object of wp_nav_menu() arguments.
	 * @return void
	 */
	public function end_el( &$output, $data_object, $depth = 0, $args = null ) {}
}
