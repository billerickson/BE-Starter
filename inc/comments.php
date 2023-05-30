<?php
/**
 * Comments
 *
 * @package      BEStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

namespace BEStarter\Comments;

/**
 * Display Comments
 */
function display_comments() {
	echo '<div id="comments" class="entry-comments">';

	\comment_form();

	if ( \have_comments() ) :
		echo '<h2 class="comments-title">' . esc_html( get_comments_number_text() ) . '</h2>';
		comment_navigation( 'before' );
		echo '<ol class="comment-list">';
		\wp_list_comments( [ 'style' => 'ol', 'type' => 'comment' ] );
		echo '</ol>';
		comment_navigation( 'after' );
	endif;

	echo '</div>';
}
add_action( 'tha_comments_before', __NAMESPACE__ . '\\display_comments', 40 );

/**
 * Comments Tempalte
 */
function comments_template() {
	if ( is_single() && ( comments_open() || get_comments_number() ) ) {
		\comments_template();
	}
}
add_action( 'tha_content_while_after', __NAMESPACE__ . '\\comments_template' );

/**
 * Comment Navigation
 *
 * @param string $location Location.
 */
function comment_navigation( $location = '' ) {
	$comment_nav_locations = [ 'after' ];
	if ( ! in_array( $location, $comment_nav_locations, true ) ) {
		return;
	}

	if ( get_comment_pages_count() <= 1 ) {
		return;
	}

	$output  = '<nav id="comment-nav-' . esc_attr( $location ) . '" class="navigation comment-navigation" role="navigation">';
	$output .= '<h3 class="screen-reader-text">' . esc_html__( 'Comment navigation', 'bestarter_textdomain' ) . '</h3>';
	$output .= '<div class="nav-links">';
	$output .= '<div class="nav-previous">' . get_previous_comments_link( esc_html__( 'Older Comments', 'bestarter_textdomain' ) ) . '</div>';
	$output .= '<div class="nav-next">' . get_next_comments_link( esc_html__( 'Newer Comments', 'bestarter_textdomain' ) ) . '</div>';
	$output .= '</div>';
	$output .= '</nav>';

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Value is hardcoded and safe, not set via input.
	echo apply_filters( 'be_comment_navigation', $output, $location );
}

/**
 * Comment links as buttons
 */
function comment_link_attributes( $atts = '' ) {
	return ' class="wp-element-button"';
}
add_filter( 'previous_comments_link_attributes', __NAMESPACE__ . '\\comment_link_attributes' );
add_filter( 'next_comments_link_attributes', __NAMESPACE__ . '\\comment_link_attributes' );

/**
 * Staff comment class
 *
 * @param array        $classes    An array of comment classes.
 * @param string       $class      A comma-separated list of additional classes added to the list.
 * @param int          $comment_id The comment ID.
 * @param \WP_Comment  $comment    The comment object.
 * @param int|\WP_Post $post_id    The post ID or WP_Post object.
 */
function staff_comment_class( $classes, $class, $comment_id, $comment, $post_id ) {
	if ( empty( $comment->user_id ) ) {
		return $classes;
	}
	$staff_roles = array( 'comment_manager', 'author', 'editor', 'administrator' );
	$staff_roles = apply_filters( 'be_staff_roles', $staff_roles );
	$user        = get_userdata( (int) $comment->user_id );
	if ( $user instanceof \WP_User && is_array( $user->roles ) && ! empty( array_intersect( $user->roles, $staff_roles ) ) ) {
		$classes[] = 'staff';
	}
	return $classes;
}
add_filter( 'comment_class', __NAMESPACE__ . '\\staff_comment_class', 10, 5 );


/**
 * Remove avatars from comment list
 *
 * @param string $avatar Avatar.
 */
function remove_avatars_from_comments( $avatar ) {
	global $in_comment_loop;
	return $in_comment_loop ? '' : $avatar;
}
add_filter( 'get_avatar', __NAMESPACE__ . '\\remove_avatars_from_comments' );

/**
 * Remove URL field from comment form
 *
 * @param array $fields Comment form fields.
 */
function remove_url_from_comment_form( $fields ) {
	unset( $fields['url'] );
	return $fields;
}
add_filter( 'comment_form_default_fields', __NAMESPACE__ . '\\remove_url_from_comment_form' );

/**
 * Remove URL from existing comments
 *
 * @param string $author_link HTML of author link.
 * @param string $author Author Name.
 */
function remove_url_from_existing_comments( $author_link, $author ) {
	return $author;
}
add_filter( 'get_comment_author_link', __NAMESPACE__ . '\\remove_url_from_existing_comments', 10, 2 );

/**
 * Comment form, button class
 *
 * @param array $args Comment Form args.
 */
function comment_form_button_class( $args ) {
	$args['class_submit'] = 'submit wp-element-button';
	$args['title_reply'] = __( 'Leave a comment', 'bestarter_textdomain' );
	$args['title_reply_before'] = str_replace( 'h3', 'h2', $args['title_reply_before'] );
	$args['title_reply_after'] = str_replace( 'h3', 'h2', $args['title_reply_after'] );
	return $args;
}
add_filter( 'comment_form_defaults', __NAMESPACE__ . '\\comment_form_button_class' );
