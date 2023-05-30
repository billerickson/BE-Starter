<?php
/**
 * WPForms
 *
 * @package      BEStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

/**
 * WPForms submit button, match Gutenberg button block
 *
 * @param array $form_data Form data.
 */
function be_wpforms_match_button_block( $form_data ) {
	$form_data['settings']['submit_class'] .= ' wp-element-button';
	return $form_data;
}
add_filter( 'wpforms_frontend_form_data', 'be_wpforms_match_button_block' );

/**
 * WPForms theme locations
 */
function be_wpforms_theme_locations() {
	$locations = [];
	$locations = apply_filters( 'be_wpforms_theme_locations', $locations );
	return $locations;
}

/**
 * Display form by location
 */
function be_display_form_location( $location = false, $title = false, $description = false ) {
	if ( ! function_exists( 'wpforms_display' ) || empty( $location ) ) {
		return;
	}

	$form_id = get_option( 'options_be_' . $location . '_form' );
	if ( ! empty( $form_id ) ) {
		wpforms_display( $form_id, $title, $description );
	}

}

/**
 * WPForms Locations Field Group
 */
function be_wpforms_locations_field_group() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	$fields    = [];
	$locations = be_wpforms_theme_locations();
	if ( empty( $locations ) ) {
		return;
	}

	foreach( $locations as $i => $location ) {
		$fields[] = [
			'key' => 'field_62e4359ddf3d' . $i,
			'label' => ucwords( str_replace( '_', ' ', $location ) ),
			'name' => 'be_' . $location . '_form',
			'type' => 'post_object',
			'post_type' => [ 'wpforms' ],
			'return_format' => 'id',
			'ui' => 1,
		];
	};

	acf_add_local_field_group(array(
		'key' => 'group_62e4358de9f28',
		'title' => 'Form Locations',
		'fields' => $fields,
		'location' => array(
		array(
		array(
			'param' => 'options_page',
			'operator' => '==',
			'value' => 'acf-options-site-options',
		),
		),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
		'show_in_rest' => 0,
	));
}
add_action( 'init', 'be_wpforms_locations_field_group', 20 );


/**
 * WPForms theme locations, class
 *
 * @param array $form_data Form data.
 */
function be_wpforms_theme_locations_class( $form_data ) {
	$locations = be_wpforms_theme_locations();
	if ( empty( $locations ) ) {
		return $form_data;
	}

	foreach ( $locations as $location ) {
		$form_id = get_option( 'options_be_' . $location . '_form' );
		if ( ! empty( $form_id ) && $form_id == $form_data['id'] ) {
			$form_data['settings']['form_class'] .= ' wpforms-location-' . $location;
		}
	}
	return $form_data;
}
add_filter( 'wpforms_frontend_form_data', 'be_wpforms_theme_locations_class' );

/**
 * WPForms admin column
 *
 * @param array $columns Admin columns.
 */
function be_wpforms_theme_locations_column( $columns ) {

	$locations = be_wpforms_theme_locations();
	if ( empty( $locations ) ) {
		return $columns;
	}

	$new_columns = [];
	foreach ( $columns as $key => $value ) {
		$new_columns[ $key ] = $value;
		if ( 'name' === $key ) {
			$new_columns['be_theme_location'] = __( 'Theme Location', 'bestarter_textdomain' );
		}
	}
	return $new_columns;
}
add_filter( 'wpforms_overview_table_columns', 'be_wpforms_theme_locations_column' );

/**
 * WPForms admin column value
 *
 * @param string   $value Value.
 * @param \WP_Post $form Form.
 * @param string   $column_name Column Name.
 */
function be_wpforms_theme_locations_column_value( $value, $form, $column_name ) {
	if ( 'be_theme_location' !== $column_name ) {
		return $value;
	}

	$current   = [];
	$locations = be_wpforms_theme_locations();
	foreach ( $locations as $location ) {
		$form_id = get_option( 'options_be_' . $location . '_form' );
		if ( ! empty( $form_id ) && $form->ID == $form_id ) {
			$current[] = $location;
		}
	}

	if ( ! empty( $current ) ) {
		$value = ucwords( str_replace( '_', ' ', join( ', ', $current ) ) );
	}

	return $value;
}
add_filter( 'wpforms_overview_table_column_value', 'be_wpforms_theme_locations_column_value', 10, 3 );
