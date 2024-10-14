<?php
/**
 * Social Share block
 *
 * @package      Cultivate
 * @author       CultivateWP
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

use BEStarter\Blocks\Social_Share;

$title = function_exists( 'get_field' ) ? get_field( 'title' ) : '';
Social_Share\display( [ 'title' => esc_html( $title ), 'icon_size' => 32 ] );
