<?php
/**
 * Single Post
 *
 * @package      BEStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

use BEStarter\Block_Areas;

/**
 * After Post
 */
function be_after_post() {
	Block_Areas\show( 'after-post' );
}
add_action( 'tha_content_while_after', 'be_after_post', 8 );


// Build the page.
require get_template_directory() . '/index.php';
