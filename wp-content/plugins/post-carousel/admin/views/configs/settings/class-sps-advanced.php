<?php
/**
 * The Advanced setting configurations.
 *
 * @package Smart_Post_Show
 * @subpackage Smart_Post_Show/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access pages directly.

/**
 * The Layout building class.
 */
class SPS_Advanced {

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
				'title'  => __( 'Advanced', 'post-carousel' ),
				'icon'   => 'fa fa-wrench',
				'fields' => array(
					array(
						'id'         => 'pcp_delete_all_data',
						'type'       => 'checkbox',
						'title'      => __( 'Clean-up Data on Deletion', 'post-carousel' ),
						'title_info' => __( 'Delete all Smart Post Show data from the database on plugin uninstalling and deletion.', 'post-carousel' ),
						'default'    => false,
					),
				),
			)
		);
	}
}
