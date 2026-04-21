<?php
/**
 * The main class for Settings configurations.
 *
 * @package Smart_Post_Show
 * @subpackage Smart_Post_Show/admin/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access pages directly.

/**
 * Settings.
 */
class SPS_Settings {

	/**
	 * Create a settings page.
	 *
	 * @param string $prefix The settings.
	 * @return void
	 */
	public static function settings( $prefix ) {

		SP_PC::createOptions(
			$prefix,
			array(
				'menu_title'       => __( 'Settings', 'post-carousel' ),
				'menu_parent'      => 'edit.php?post_type=sp_post_carousel',
				'menu_type'        => 'submenu', // menu, submenu, options, theme, etc.
				'menu_slug'        => 'pcp_settings',
				'theme'            => 'light',
				'show_all_options' => false,
				'show_search'      => false,
				'show_footer'      => false,
				'show_bar_menu'    => false,
				'show_reset_all'   => false,
				'class'            => 'sp-pc-settings',
				'framework_title'  => __( 'Smart Post Show', 'post-carousel' ),
			)
		);

		SPS_Advanced::section( $prefix );
		SPS_ScriptsAndStyles::section( $prefix );
		SPS_Accessibility::section( $prefix );
		SPS_CustomCSS::section( $prefix );
	}

}
