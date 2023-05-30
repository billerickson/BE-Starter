<?php
/**
 * Block Areas
 *
 * @package      BEStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

namespace BEStarter\Block_Areas;

/**
 * Block Areas
 */
function block_areas() {
	$block_areas = [ 'sidebar', 'after-post', 'before-footer', '404' ];
	return apply_filters( 'be_block_areas', $block_areas );
}

/**
 * Block Area Name
 */
function block_area_name( $block_area ) {
	return ucwords( str_replace( '-', ' ', $block_area ) );
}

/**
 * Register CPT
 */
function register_cpt() {
	$labels = [
		'name'               => __( 'Block Areas', 'bestarter_textdomain' ),
		'singular_name'      => __( 'Block Area', 'bestarter_textdomain' ),
		'add_new'            => __( 'Add New', 'bestarter_textdomain' ),
		'add_new_item'       => __( 'Add New Block Area', 'bestarter_textdomain' ),
		'edit_item'          => __( 'Edit Block Area', 'bestarter_textdomain' ),
		'new_item'           => __( 'New Block Area', 'bestarter_textdomain' ),
		'view_item'          => __( 'View Block Area', 'bestarter_textdomain' ),
		'search_items'       => __( 'Search Block Areas', 'bestarter_textdomain' ),
		'not_found'          => __( 'No Block Areas found', 'bestarter_textdomain' ),
		'not_found_in_trash' => __( 'No Block Areas found in Trash', 'bestarter_textdomain' ),
		'parent_item_colon'  => __( 'Parent Block Area:', 'bestarter_textdomain' ),
		'menu_name'          => __( 'Block Areas', 'bestarter_textdomain' ),
	];

	$args = [
		'labels'              => $labels,
		'hierarchical'        => false,
		'supports'            => [ 'title', 'editor', 'revisions', 'custom-fields' ],
		'public'              => false,
		'publicly_queryable'  => is_admin(),
		'show_ui'             => true,
		'show_in_rest'        => true,
		'exclude_from_search' => true,
		'has_archive'         => false,
		'query_var'           => true,
		'can_export'          => true,
		'rewrite'             => false,
		'menu_icon'           => 'dashicons-layout',
		'show_in_menu'        => 'themes.php',
	];

	register_post_type( 'block_area', $args );
}
add_action( 'init', __NAMESPACE__ . '\\register_cpt', 20 );

/**
 * Register Field Group
 */
function register_field_group() {
	if( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	$block_areas = block_areas();
	if ( empty( $block_areas ) ) {
		return;
	}

	$choices = [];
	foreach( $block_areas as $block_area ) {
		$choices[ $block_area ] = block_area_name( $block_area );
	}

	acf_add_local_field_group(array(
	'key' => 'group_62ec3de5b9125',
	'title' => 'Assigned To',
	'fields' => array(
	array(
		'key' => 'field_62ec3df6af2c1',
		'label' => 'Block Area',
		'name' => 'be_block_area',
		'type' => 'select',
		'instructions' => '',
		'required' => 0,
		'conditional_logic' => 0,
		'wrapper' => array(
			'width' => '',
			'class' => '',
			'id' => '',
		),
		'choices' => $choices,
		'default_value' => false,
		'allow_null' => 1,
		'multiple' => 0,
		'ui' => 1,
		'ajax' => 0,
		'return_format' => 'value',
		'placeholder' => '',
	),
	),
	'location' => array(
	array(
		array(
			'param' => 'post_type',
			'operator' => '==',
			'value' => 'block_area',
		),
	),
	),
	'menu_order' => 0,
	'position' => 'side',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
	'show_in_rest' => 0,
	));

}
add_action( 'init', __NAMESPACE__ . '\\register_field_group', 20 );

/**
 * Ensure only one post per block area
 */
function limit_block_posts( $post_id ) {

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	$block_area = get_post_meta( $post_id, 'be_block_area', true );
	if ( empty( $block_area ) ) {
		return;
	}

	remove_action( 'save_post_block_area', __NAMESPACE__ . '\\limit_block_posts', 10 );

	$others = new \WP_Query( [
		'post_type' => 'block_area',
		'post__not_in' => [ $post_id ],
		'meta_key' => 'be_block_area',
		'meta_value' => $block_area,
		'fields' => 'ids',
	]);

	if ( ! empty( $others->posts ) ) {
		foreach( $others->posts as $other_id ) {
			delete_post_meta( $other_id, 'be_block_area' );
		}
	}
}
add_action( 'acf/save_post', __NAMESPACE__ . '\\limit_block_posts' );

/**
 * Admin Column
 */
function admin_column( $columns ) {
	$new = [];
	foreach( $columns as $key => $value ) {
		$new[ $key ] = $value;

		if ( 'title' === $key ) {
			$new[ 'be_block_area'] = 'Assigned to';
		}
	}
	return $new;
}
add_filter( 'manage_block_area_posts_columns', __NAMESPACE__ . '\\admin_column' );

/**
 * Admin column value
 */
function admin_column_value( $column_name, $post_id ) {
	if ( 'be_block_area' === $column_name ) {
		echo block_area_name( get_post_meta( get_the_ID(), 'be_block_area', true ) );
	}
}
add_action( 'manage_block_area_posts_custom_column', __NAMESPACE__ . '\\admin_column_value', 10, 2 );

/**
 * Admin body class
 */
function admin_body_class( $classes ) {
	$screen = get_current_screen();
	if ( ! method_exists( $screen, 'is_block_editor' ) || ! $screen->is_block_editor() ) {
		return $classes;
	}

	$post_id = isset( $_GET['post'] ) ? intval( $_GET['post'] ) : false;
	if ( empty( $post_id ) || 'block_area' !== get_post_type( $post_id ) ) {
		return $classes;
	}

	$classes .= ' block-area ';
	$block_area = get_post_meta( $post_id, 'be_block_area', true );
	if ( ! empty( $block_area ) ) {
		$classes .= ' block-area-' . str_replace( '_', '-', $block_area ) . ' ';
	}

	return $classes;
}
add_filter( 'admin_body_class', __NAMESPACE__ . '\\admin_body_class' );

/**
 * Show
 */
function show( $block_area = '', $echo = true ) {
	$output = '';
	$args   = [ 'post_type' => 'block_area', 'posts_per_page' => 1, 'post_status' => 'publish', 'meta_key' => 'be_block_area', 'meta_value' => esc_attr( $block_area ) ];
	$loop   = new \WP_Query( $args );

	if( $loop->have_posts() ):
		while( $loop->have_posts() ):
			global $post;
			$loop->the_post();
			$classes = [ 'block-area', 'block-area-' . $block_area ];
			$output .= '<div class="' . esc_attr( join( ' ', $classes ) ) . '">';
			$output .= apply_filters( 'be_the_content', $post->post_content );
			$output .= '</div>';
		endwhile;
	endif;
	wp_reset_postdata();

	if ( $echo ) {
		echo $output;
	} else {
		return $output;
	}
}
