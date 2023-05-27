<?php
/**
 * Archive
 *
 * @package      BEStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

// Full width.
add_filter( 'be_page_layout', 'be_return_full_width_content' );

/**
 * Body Class
 *
 * @param array $classes Body classes.
 */
function be_archive_body_class( $classes ) {
	$classes[] = 'archive';
	return $classes;
}
add_filter( 'body_class', 'be_archive_body_class' );

// Build the page.
require get_template_directory() . '/index.php';
