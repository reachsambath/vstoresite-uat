<?php
/**
 * The shortcode Meta-box configurations.
 *
 * @package Smart_Post_Show
 * @subpackage Smart_Post_Show/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access pages directly.

/**
 * The Shortcode display class.
 */
class SPS_Shortcode {

	/**
	 * Shortcode display metabox section.
	 *
	 * @param string $prefix The metabox key.
	 * @return void
	 */
	public static function section( $prefix ) {

		SP_PC::createSection(
			$prefix,
			array(
				'fields' => array(
					array(
						'type'  => 'shortcode',
						'class' => 'pcp-admin-sidebar',
					),
				),
			)
		);

	}
}
