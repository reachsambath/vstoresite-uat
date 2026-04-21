<?php
/**
 * The Enqueue and Dequeue CSS and JS files setting configurations.
 *
 * @package Smart_Post_Show
 * @subpackage Smart_Post_Show/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access pages directly.

/**
 * The Layout building class.
 */
class SPS_ScriptsAndStyles {

	/**
	 * Advanced setting section.
	 *
	 * @param string $prefix The settings.
	 * @return void
	 */
	public static function section( $prefix ) {
		SP_PC::createSection(
			$prefix,
			array(
				'title'  => __( 'Control Assets', 'post-carousel' ),
				'icon'   => 'fa fa-tasks',
				'fields' => array(
					array(
						'id'         => 'pcp_swiper_js',
						'type'       => 'switcher',
						'title'      => __( 'Swiper JS', 'post-carousel' ),
						'text_on'    => __( 'Enqueued', 'post-carousel' ),
						'text_off'   => __( 'Dequeued', 'post-carousel' ),
						'text_width' => 110,
						'default'    => true,
					),
					array(
						'id'         => 'pcp_swiper_css',
						'type'       => 'switcher',
						'title'      => __( 'Swiper CSS', 'post-carousel' ),
						'text_on'    => __( 'Enqueued', 'post-carousel' ),
						'text_off'   => __( 'Dequeued', 'post-carousel' ),
						'text_width' => 110,
						'default'    => true,
					),
					array(
						'id'         => 'pcp_fontawesome_css',
						'type'       => 'switcher',
						'title'      => __( 'Font Awesome CSS', 'post-carousel' ),
						'text_on'    => __( 'Enqueued', 'post-carousel' ),
						'text_off'   => __( 'Dequeued', 'post-carousel' ),
						'text_width' => 110,
						'default'    => true,
					),
				),
			)
		);
	}
}
