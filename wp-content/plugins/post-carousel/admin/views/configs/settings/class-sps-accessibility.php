<?php
/**
 * The Accessibility setting configurations.
 *
 * @package Smart_Post_Show
 * @subpackage Smart_Post_Show/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access pages directly.

/**
 * The accessibility setting class.
 */
class SPS_Accessibility {

	/**
	 * Accessibility setting section.
	 *
	 * @param string $prefix The settings.
	 * @return void
	 */
	public static function section( $prefix ) {
		SP_PC::createSection(
			$prefix,
			array(
				'title'  => __( 'Accessibility', 'post-carousel' ),
				'icon'   => 'fa fa-braille',
				'fields' => array(
					array(
						'id'         => 'accessibility',
						'type'       => 'switcher',
						'title'      => __( 'Carousel Accessibility', 'post-carousel' ),
						'text_on'    => __( 'Enabled', 'post-carousel' ),
						'text_off'   => __( 'Disabled', 'post-carousel' ),
						'text_width' => 100,
						'default'    => true,
					),
					array(
						'id'         => 'prev_slide_message',
						'type'       => 'text',
						'title'      => __( 'Previous Slide Message', 'post-carousel' ),
						'default'    => __( 'Previous slide', 'post-carousel' ),
						'dependency' => array( 'accessibility', '==', 'true' ),
					),
					array(
						'id'         => 'next_slide_message',
						'type'       => 'text',
						'title'      => __( 'Next Slide Message', 'post-carousel' ),
						'default'    => __( 'Next slide', 'post-carousel' ),
						'dependency' => array( 'accessibility', '==', 'true' ),
					),
					array(
						'id'         => 'first_slide_message',
						'type'       => 'text',
						'title'      => __( 'First Slide Message', 'post-carousel' ),
						'default'    => __( 'This is the first slide', 'post-carousel' ),
						'dependency' => array( 'accessibility', '==', 'true' ),
					),
					array(
						'id'         => 'last_slide_message',
						'type'       => 'text',
						'title'      => __( 'Last Slide Message', 'post-carousel' ),
						'default'    => __( 'This is the last slide', 'post-carousel' ),
						'dependency' => array( 'accessibility', '==', 'true' ),
					),
					array(
						'id'         => 'pagination_bullet_message',
						'type'       => 'text',
						'title'      => __( 'Pagination Bullet Message', 'post-carousel' ),
						'default'    => __( 'Go to slide {{index}}', 'post-carousel' ),
						'dependency' => array( 'accessibility', '==', 'true' ),
					),
				),
			)
		);
	}
}
