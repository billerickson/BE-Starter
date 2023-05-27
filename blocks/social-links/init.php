<?php
/**
 * Social Links block
 *
 * @package      BEStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

namespace BEStarter\Blocks\Social_Links;

/**
 * Site social links
 */
function site( $block = [] ) {

	$output   = [];
	$seo_data = get_option( 'wpseo_social' );
	$socials = socials();

	foreach ( $socials as $social => $settings ) {

		$url = '';

		if ( defined( 'WPSEO_VERSION' ) && version_compare( WPSEO_VERSION, '18.9', '>=' ) ) {
			switch ( $social ) {
				case 'facebook':
					$url = $seo_data[ $settings['key'] ];
					break;
				case 'twitter':
					$url = $seo_data[ $settings['key'] ];
					if ( ! empty( $url ) && stripos( $url, $social ) === false ) {
						$url = $settings['prepend'] . $url;
					}
					break;
				default:
					if ( is_array( $seo_data['other_social_urls'] ) ) {
						foreach ( $seo_data['other_social_urls'] as $seo_url ) {
							if ( stripos( $seo_url, $social ) !== false ) {
								$url = $seo_url;
							}
						}
					}
			}
		} else {
			$url = ! empty( $settings['key'] ) && ! empty( $seo_data[ $settings['key'] ] ) ? $seo_data[ $settings['key'] ] : false;
			if ( ! empty( $url ) && ! empty( $settings['prepend'] ) ) {
				$url = $settings['prepend'] . $url;
			}
			if ( ! empty( $settings['url'] ) ) {
				$url = $settings['url'];
			}
		}
		$icon = isset( $settings['icon'] ) ? $settings['icon'] : $social;
		if ( ! empty( $url ) ) {
			$output[] = '<li><a href="' . esc_url_raw( $url ) . '" target="_blank" rel="noopener noreferrer" aria-label="' . $settings['label'] . '">' . be_icon( array( 'icon' => $icon ) ) . '</a></li>';
		}
	}

	$classes = ['social-links'];
	if ( ! empty( $block['alignText'] ) ) {
		$classes[] = 'has-text-align-' . $block['alignText'];
	}

	if ( ! empty( $output ) ) {
		return '<ul class="' . esc_attr( join( ' ', $classes ) ) . '">' . join( PHP_EOL, $output ) . '</ul>';
	}
}

/**
 * User social links
 */
function user( $user_id = false ) {

	if( !empty( $user_id ) ) {
		$user_id = intval( $user_id );
	} elseif( is_author() ) {
		$user_id = get_queried_object_id();
	} else {
		$user_id = get_the_author_meta( 'ID' );
	}

	$output = [];
	$socials = socials();
	foreach( $socials as $social => $settings ) {
		$url = get_the_author_meta( $social, $user_id );
		if ( ! empty( $url ) && ! empty( $settings[ 'prepend' ] ) ) {
			$url = $settings['prepend'] . $url;
		}
		$icon = isset( $settings['icon'] ) ? $settings['icon'] : $social;
		if ( ! empty( $url ) ) {
			$output[] = '<li><a href="' . esc_url_raw( $url ) . '" target="_blank" rel="noopener noreferrer" aria-label="' . $settings['label'] . '">' . be_icon( array( 'icon' => $icon ) ) . '</a></li>';
		}
	}

	if ( ! empty( $output ) ) {
		return '<ul class="social-links">' . join( PHP_EOL, $output ) . '</ul>';
	}

}

/**
 * Socials
 */
function socials() {
	$socials = [
		'instagram' => [
			'key'   => 'instagram_url',
			'label' => 'Instagram',
		],
		'youtube'   => [
			'key'   => 'youtube_url',
			'label' => 'YouTube',
			'icon'  => 'youtube-play',
		],
		'facebook'  => [
			'key'   => 'facebook_site',
			'label' => 'Facebook',
		],
		'twitter'   => [
			'key'     => 'twitter_site',
			'label'   => 'Twitter',
			'prepend' => 'https://twitter.com/',
		],
		'pinterest' => [
			'key'   => 'pinterest_url',
			'label' => 'Pinterest',
		],
		'tiktok'    => [
			'label' => 'TikTok',
		],
		'yummly'    => [
			'label' => 'Yummly',
		],
	];
	return $socials;
}
