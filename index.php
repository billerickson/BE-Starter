<?php
/**
 * Base template
 *
 * @package      BEStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

get_header();

tha_content_before();

	echo '<div class="content-area">';
	tha_content_wrap_before();
	echo '<main class="site-main" role="main">';
	tha_content_top();
	tha_content_loop();
	tha_content_bottom();
	echo '</main>';
	get_sidebar();
	tha_content_wrap_after();
	echo '</div>';
tha_content_after();

get_footer();
