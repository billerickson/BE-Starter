<?php
/**
 * Site Footer
 *
 * @package      BEStarter
 * @subpackage   site-header/01
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

use BEStarter\Blocks\Social_Links;

/**
 * Site Footer
 */
function be_site_footer() {
	echo '<p>&copy;' . date( 'Y' ) . ' ' . get_bloginfo( 'name' ) . '. All rights reserved.</p>';
}
add_action( 'tha_footer_top', 'be_site_footer' );
