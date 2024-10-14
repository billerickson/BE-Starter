<?php
/**
 * Social Share block
 *
 * @package      BEStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

namespace BEStarter\Blocks\Social_Share;

/**
 * Display
 */
function display( $settings = [] ) {

	$settings = wp_parse_args( $settings, [
		'echo' => true,
		'title' => false,
		'services' => [ 'pinterest', 'facebook', 'twitter', 'yummly', 'email' ],
	] );

	$output   = '';

	foreach( $settings['services'] as $service ) {
		$output .= link( $service, $settings );
	}

	if ( empty( $output ) ) {
		return;
	}

	$output = '<div class="social-share__links">' . $output . '</div>';

	if ( ! empty( $settings['title'] ) ) {
		$output = '<div class="social-share__title">' . esc_html( $settings['title'] ) . '</div>' . $output;
	}

	$output = '<div class="block-social-share">' . $output . '</div>';
	if ( $settings['echo'] ) {
		echo $output;
	} else {
		return $output;
	}


}

/**
 * Link
 */
function link( $service = 'pinterest', $settings = [] ) {

	$settings = wp_parse_args( $settings, [
		'id'         => false,
		'hide_icon'  => false,
		'icon_size'  => 20,
		'hide_label' => true,
		'label'      => false,
		'class'      => 'social-share',
	]);

	$id            = ! empty( $settings['id'] ) ? intval( $settings['id'] ) : get_queried_object_id();
	$link          = [];
	$link['type']  = $service;
	$link['class'] = '';
	$link['img']   = apply_filters( 'shared_counts_default_image', '', $id, $link );

	$link['url']   = esc_url( get_permalink( $id ) );
	$link['title'] = wp_strip_all_tags( get_the_title( $id ) );
	if ( has_post_thumbnail( $id ) ) {
		$link['img'] = wp_get_attachment_image_url( get_post_thumbnail_id( $id ), 'full' );
	}
	$link['img']   = apply_filters( 'shared_counts_single_image', $link['img'], $id, $link );
	$link['url']   = apply_filters( 'shared_counts_link_url', $link['url'], $link );
	$link['icon']  = function_exists( 'be_icon' ) ? be_icon( [ 'icon' => $service, 'size' => $settings['icon_size' ] ] ) : '';
	$attr          = [ 'postid' => $id ];

	switch ( $service ) {
		case 'facebook':
			$link['link']           = 'https://www.facebook.com/sharer/sharer.php?u=' . $link['url'] . '&display=popup&ref=plugin&src=share_button';
			$link['label']          = esc_html__( 'Facebook', 'cultivate_textdomain' );
			$link['target']         = '_blank';
			$link['rel']            = 'nofollow noopener noreferrer';
			$link['attr_title']     = esc_html__( 'Share on Facebook', 'cultivate_textdomain' );
			$link['social_network'] = 'Facebook';
			$link['social_action']  = 'Share';
			break;
		case 'twitter':
			$link['link']           = 'https://twitter.com/share?url=' . $link['url'] . '&text=' . htmlspecialchars( rawurlencode( html_entity_decode( $link['title'], ENT_COMPAT, 'UTF-8' ) ), ENT_COMPAT, 'UTF-8' );
			$link['label']          = esc_html__( 'Tweet', 'cultivate_textdomain' );
			$link['target']         = '_blank';
			$link['rel']            = 'nofollow noopener noreferrer';
			$link['attr_title']     = esc_html__( 'Share on Twitter', 'cultivate_textdomain' );
			$link['social_network'] = 'Twitter';
			$link['social_action']  = 'Tweet';
			$link['icon']           = function_exists( 'be_icon' ) ? be_icon( [ 'icon' => 'x', 'size' => $settings['icon_size'] ] ) : '';
			break;
		case 'pinterest':
			$link['img_source'] = 'featured_image';

			// Tasty Pins.
			$tasty_image = get_post_meta( $id, 'tp_pinterest_hidden_image', true );
			if ( ! empty( $tasty_image ) ) {
				$link['img'] = wp_get_attachment_image_url( $tasty_image, 'full' );
				$link['img_source'] = 'tasty_pins';
			}
			$tasty_desc = get_post_meta( $id, 'tp_pinterest_default_text', true );
			if ( ! empty( $tasty_desc ) ) {
				$link['title'] = $tasty_desc;
			}
			// Hubbub
			$grow_share_options = get_post_meta( $id, 'dpsp_share_options' );
			if( is_array( $grow_share_options ) ) {
				if( isset( $grow_share_options[0]['custom_image_pinterest']['src'] ) && ! empty( $grow_share_options[0]['custom_image_pinterest']['src'] ) ) {
					$link['img'] = $grow_share_options[0]['custom_image_pinterest']['src'];
					$link['img_source'] = 'hubbub';
				}
				if( isset( $grow_share_options[0]['custom_title_pinterest'] ) && ! empty( $grow_share_options[0]['custom_title_pinterest'] ) ) {
					$link['title'] = $grow_share_options[0]['custom_title_pinterest'];
				}
			}

			$link['link']           = 'https://pinterest.com/pin/create/button/?url=' . $link['url'] . '&media=' . $link['img'] . '&description=' . $link['title'];
			$link['label']          = esc_html__( 'Pin', 'cultivate_textdomain' );
			$link['target']         = '_blank';
			$link['rel']            = 'nofollow noopener noreferrer';
			$link['attr_title']     = esc_html__( 'Share on Pinterest', 'cultivate_textdomain' );
			$link['social_network'] = 'Pinterest';
			$link['social_action']  = 'Pin';
			break;
		case 'yummly':
			$link['link']           = 'https://www.yummly.com/urb/verify?url=' . $link['url'] . '&title=' . rawurlencode( $link['title'] ) . '&yumtype=button';
			$link['label']          = esc_html__( 'Yummly', 'cultivate_textdomain' );
			$link['target']         = '_blank';
			$link['rel']            = 'nofollow noopener noreferrer';
			$link['attr_title']     = esc_html__( 'Share on Yummly', 'cultivate_textdomain' );
			$link['social_network'] = 'Yummly';
			$link['social_action']  = 'Saved';
			break;
		case 'linkedin':
			$link['link']           = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $link['url'];
			$link['label']          = esc_html__( 'LinkedIn', 'cultivate_textdomain' );
			$link['target']         = '_blank';
			$link['rel']            = 'nofollow noopener noreferrer';
			$link['attr_title']     = esc_html__( 'Share on LinkedIn', 'cultivate_textdomain' );
			$link['social_network'] = 'LinkedIn';
			$link['social_action']  = 'Share';
			break;
		case 'print':
			$link['link']           = 'javascript:window.print()';
			$link['label']          = esc_html__( 'Print', 'cultivate_textdomain' );
			$link['icon']           = function_exists( 'be_icon' ) ? be_icon( [ 'icon' => 'printer', 'size' => $settings['icon_size'] ] ) : '';
			$link['attr_title']     = esc_html__( 'Print this Page', 'cultivate_textdomain' );
			$link['social_network'] = 'Print';
			$link['social_action']  = 'Printed';
			break;
		case 'email':
			$subject      = esc_html__( 'Your friend has shared an article with you.', 'cultivate_textdomain' );
			$body         = html_entity_decode( get_the_title( $id ), ENT_QUOTES ) . "\r\n";
			$body        .= get_permalink( $id ) . "\r\n";
			$link['link'] = 'mailto:?subject=' . rawurlencode( $subject ) . '&body=' . rawurlencode( $body );
			$link['label']          = esc_html__( 'Email', 'cultivate_textdomain' );
			$link['icon']           = function_exists( 'be_icon' ) ? be_icon( [ 'icon' => 'email', 'size' => $settings['icon_size' ] ] ) : '';
			$link['attr_title']     = 'Share via Email';
			$link['class']         .= ' no-scroll';
			$link['social_network'] = 'Email';
			$link['social_action']  = 'Emailed';
			break;
	}

	$link       = apply_filters( 'cwp_social_share_link', $link, $service, $id );
	$data       = '';
	$target     = ! empty( $link['target'] ) ? ' target="' . esc_attr( $link['target'] ) . '" ' : '';
	$rel        = ! empty( $link['rel'] ) ? ' rel="' . esc_attr( $link['rel'] ) . '" ' : '';
	$attr_title = ! empty( $link['attr_title'] ) ? ' title="' . esc_attr( $link['attr_title'] ) . '" ' : '';
	$elements   = [];

	if ( ! empty( $settings['label'] ) ) {
		$link['label'] = $settings['label'];
	}

	// Prevent Pinterest JS from hijacking our button.
	if ( 'pinterest' === $service ) {
		$attr['pin-do'] = 'none';
		$data .= ' data-image-source="' . esc_attr( $link['img_source'] ) . '"';
	}

	// Social interaction data attributes - used for GA social tracking.
	if ( apply_filters( 'shared_counts_social_tracking', true ) ) {
		if ( ! empty( $link['social_network'] ) ) {
			$attr['social-network'] = $link['social_network'];
		}
		if ( ! empty( $link['social_action'] ) ) {
			$attr['social-action'] = $link['social_action'];
		}
		if ( ! empty( $link['url'] ) ) {
			$attr['social-target'] = $link['url'];
		}
	}

	// Add data attribues.
	$attr = apply_filters( 'shared_counts_link_data', $attr, $link, $id );
	if ( ! empty( $attr ) ) {
		foreach ( $attr as $key => $val ) {
			$data .= ' data-' . sanitize_html_class( $key ) . '="' . esc_attr( $val ) . '"';
		}
	}

	$css_classes = ! empty( $settings['class'] ) ? ' class="' . esc_attr( $settings['class'] ) . '"' : '';

	// Build button output.
	$elements['wrap_open']  = sprintf(
		'<a href="%s"%s%s%s%s%s>',
		esc_attr( $link['link'] ),
		$attr_title,
		$target,
		$rel,
		$css_classes,
		$data
	);
	$elements['wrap_close'] = '</a>';

	$elements['icon']  = $link['icon'];
	if( $settings['hide_icon'] ) {
		$elements['icon'] = '';
	}

	$elements['label'] = $link['label'];
	if ( ! empty( $elements['label'] ) && $settings['hide_label'] ) {
		$elements['label'] = '<span class="screen-reader-text">' . $elements['label'] . '</span>';
	}
	return $elements['wrap_open'] . $elements['icon'] . $elements['label'] . $elements['wrap_close'];
}
