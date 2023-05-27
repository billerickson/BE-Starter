<?php
/**
 * Archive Header
 *
 * @package      BEStarter
 * @subpackage   archive-header/1
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

/**
 * Archive Header
 */
function be_archive_header() {

	if ( is_singular() ) {
		return;
	}

	// Breadcrumbs in archive header
	remove_action( 'tha_content_top', 'be_breadcrumbs' );
	add_action( 'be_archive_header_before', 'be_breadcrumbs' );

	$title       = false;
	$description = false;
	$image       = false;

	if ( is_home() && get_option( 'page_for_posts' ) ) {
		$post_id = get_option( 'page_for_posts' );
		$title = get_post_meta( $post_id, 'be_archive_title', true );
		if ( empty( $title ) ) {
			$title = get_the_title( $post_id );
		}
		$description = get_post_meta( $post_id, 'be_archive_description', true );


	} elseif ( is_search() ) {
		$title = 'Search Results';

	} elseif ( is_archive() ) {
		$title = false;
		if ( is_category() || is_tag() ) {
			$title = get_term_meta( get_queried_object_id(), 'be_archive_headline', true );
			$image = get_term_meta( get_queried_object_id(), 'image', true );
		}
		if ( empty( $title ) ) {
			$title = get_the_archive_title();
		}
		if ( ! get_query_var( 'paged' ) ) {
			$description = get_the_archive_description();
		}
	}

	if ( empty( $title ) && empty( $description ) ) {
		return;
	}

	echo '<div class="archive-header"><div class="wrap">';
	do_action( 'be_archive_header_before' );

	echo '<div class="archive-header__inner">';
	if ( ! empty( $title ) ) {
		echo '<h1 class="archive-title">' . esc_html( wp_strip_all_tags( $title ) ) . '</h1>';
	}
	if ( ! empty( $description ) ) {
		echo '<div class="archive-description">' . wp_kses_post( apply_filters( 'be_the_content', $description ) ) . '</div>';
	}
	if ( is_search() ) {
		echo be_render_search();
	}
	echo '</div>';

	do_action( 'be_archive_header_after' );
	echo '</div></div>';

}
add_action( 'tha_header_after', 'be_archive_header', 16 );
