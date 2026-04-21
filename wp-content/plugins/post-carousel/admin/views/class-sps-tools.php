<?php
/**
 * The main class for Settings configurations.
 *
 * @package Smart_Post_Show
 * @subpackage Smart_Post_Show/admin/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

/**
 * Settings.
 */
class SPS_Tools {

	/**
	 * Create a settings page.
	 *
	 * @param string $prefix The settings.
	 * @return void
	 */
	public static function tools( $prefix ) {
		SP_PC::createOptions(
			$prefix,
			array(
				'menu_title'       => __( 'Tools', 'post-carousel' ),
				'menu_parent'      => 'edit.php?post_type=sp_post_carousel',
				'menu_type'        => 'submenu', // menu, submenu, options, theme, etc.
				'menu_slug'        => 'pcp_tools',
				'theme'            => 'light',
				'show_all_options' => false,
				'show_search'      => false,
				'show_footer'      => false,
				'show_bar_menu'    => false,
				'class'            => 'sp-pc-settings pcp_tools',
				'framework_title'  => __( 'Tools', 'post-carousel' ),
				'show_buttons'     => false, // Custom show button option added for hide save button in tools page.
			)
		);

		SP_PC::createSection(
			$prefix,
			array(
				'title'  => __( 'Export', 'post-carousel' ),
				'fields' => array(
					array(
						'id'       => 'pcp_what_export',
						'type'     => 'radio',
						'class'    => 'pcp_what_export',
						'title'    => __( 'Choose What To Export', 'post-carousel' ),
						'multiple' => false,
						'options'  => array(
							'all_shortcodes'      => __( 'All Shows (Shortcodes)', 'post-carousel' ),
							'selected_shortcodes' => __( 'Selected Show(s)', 'post-carousel' ),
						),
						'default'  => 'all_shortcodes',
					),
					array(
						'id'          => 'pcp_post',
						'class'       => 'pcp_post_ids',
						'type'        => 'select',
						'title'       => ' ',
						'options'     => 'sp_post_carousel',
						'chosen'      => true,
						'sortable'    => false,
						'multiple'    => true,
						'placeholder' => __( 'Choose show(s)', 'post-carousel' ),
						'query_args'  => array(
							'posts_per_page' => -1,
						),
						'dependency'  => array( 'pcp_what_export', '==', 'selected_shortcodes', true ),

					),
					array(
						'id'      => 'export',
						'class'   => 'pcp_export',
						'type'    => 'button_set',
						'title'   => ' ',
						'options' => array(
							'' => __( 'Export', 'post-carousel' ),
						),
					),
				),
			)
		);
		SP_PC::createSection(
			$prefix,
			array(
				'title'  => __( 'Import', 'post-carousel' ),
				'fields' => array(
					array(
						'class' => 'pcp_import',
						'type'  => 'custom_import',
						'title' => __( 'Import JSON File To Upload', 'post-carousel' ),
					),
				),
			)
		);
	}

}
