<?php
/**
 * WordPress SEO
 *
 * @package      BEStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

/**
 * Breadcrumbs
 */
function be_breadcrumbs() {
	if ( function_exists( 'yoast_breadcrumb' ) && ! is_front_page() ) {
		yoast_breadcrumb( '<p id="breadcrumbs" class="breadcrumb">', '</p>' );
	}
}
add_action( 'tha_content_top', 'be_breadcrumbs' );
