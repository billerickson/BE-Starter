<?php
/**
 * Archive partial
 *
 * @package      BEStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

echo '<article class="post-summary">';
be_post_summary_image();

echo '<div class="post-summary__content">';
	be_entry_category();
	be_post_summary_title();
echo '</div>';

echo '</article>';
