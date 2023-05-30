<?php
/**
 * Site Header
 *
 * @package      BEStarter
 * @subpackage   site-header/01
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

/**
 * Register nav menus
 */
function be_register_menus() {
	register_nav_menus(
		[
			'primary' => esc_html__( 'Primary Navigation Menu', 'bestarter_textdomain' ),
		]
	);

}
add_action( 'after_setup_theme', 'be_register_menus' );

/**
 * Site Header
 */
function be_site_header() {
	echo '<a href="' . esc_url( home_url() ) . '" rel="home" class="site-header__logo" aria-label="' . esc_attr( get_bloginfo( 'name' ) ) . ' Home">' . get_bloginfo( 'name' ) . '</a>';

	echo '<div class="site-header__toggles">';
	echo be_mobile_menu_toggle();
	echo '</div>';

	echo '<nav class="nav-menu" role="navigation">';
	if ( has_nav_menu( 'primary' ) ) {
		wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu', 'container_class' => 'nav-primary' ) );
	}
	echo '</nav>';

}
add_action( 'tha_header_bottom', 'be_site_header', 11 );

/**
 * Mobile menu toggle
 */
function be_mobile_menu_toggle() {
	$output  = '<button aria-label="Menu" class="menu-toggle">';
	$output .= be_icon( array( 'icon' => 'menu', 'class' => 'open' ) );
	$output .= be_icon( array( 'icon' => 'close', 'class' => 'close' ) );
	$output .= '</button>';
	return $output;
}

/**
 * Add a dropdown icon to top-level menu items.
 *
 * @param string $output Nav menu item start element.
 * @param object $item   Nav menu item.
 * @param int    $depth  Depth.
 * @param object $args   Nav menu args.
 * @return string Nav menu item start element.
 * Add a dropdown icon to top-level menu items
 */
function be_nav_add_dropdown_icons( $output, $item, $depth, $args ) {

	if ( ! isset( $args->theme_location ) || 'primary' !== $args->theme_location ) {
		return $output;
	}

	if ( isset( $args->depth ) && 1 === $args->depth ) {
		return $output;
	}

	if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {

		// Add SVG icon to parent items.
		$icon = be_icon( array( 'icon' => 'carat-down-large', 'size' => 12 ) );

		// Optional - two icons based on open/close state
		//$icon = be_icon( [ 'icon' => 'plus', 'class' => 'open' ] ) . be_icon( [ 'icon' => 'minus', 'class' => 'close' ] );

		$output .= sprintf(
			'<button aria-label="Submenu Dropdown" class="submenu-expand" tabindex="-1">%s</button>',
			$icon
		);
	}

	return $output;
}
add_filter( 'walker_nav_menu_start_el', 'be_nav_add_dropdown_icons', 10, 4 );
