<?php
/**
 * Blocks
 *
 * @package      BEStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

namespace BEStarter\Blocks;

/**
 * Load Blocks
 */
function load_blocks() {
	$blocks = get_blocks();
	foreach( $blocks as $block ) {
		if ( file_exists( get_template_directory() . '/blocks/' . $block . '/block.json' ) ) {
			register_block_type( get_template_directory() . '/blocks/' . $block . '/block.json' );
			if ( file_exists( get_template_directory() . '/blocks/' . $block . '/style.css' ) ) {
				wp_register_style( 'block-' . $block, get_template_directory_uri() . '/blocks/' . $block . '/style.css', array(), filemtime( get_template_directory() . '/blocks/' . $block . '/style.css' ) );
			}
			if ( file_exists( get_template_directory() . '/blocks/' . $block . '/init.php' ) ) {
				include_once get_template_directory() . '/blocks/' . $block . '/init.php';
			}
		}
	}
}
add_action( 'init', __NAMESPACE__ . '\\load_blocks', 5 );

/**
 * Load ACF field groups for blocks
 */
function load_acf_field_group( $paths ) {
	$blocks = get_blocks();
	foreach( $blocks as $block ) {
		$paths[] = get_template_directory() . '/blocks/' . $block;
	}
	return $paths;
}
add_filter( 'acf/settings/load_json', __NAMESPACE__ . '\\load_acf_field_group' );

/**
 * Get Blocks
 */
function get_blocks() {
	$blocks = scandir( get_template_directory() . '/blocks/' );
	$blocks = array_values( array_diff( $blocks, array( '..', '.', '.DS_Store', '_base-block' ) ) );
	return $blocks;
}
