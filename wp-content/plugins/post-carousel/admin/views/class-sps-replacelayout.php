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
class SPS_ReplaceLayout {
	/**
	 * Create a Replace_Layout page.
	 *
	 * @param string $prefix The settings.
	 * @return void
	 */
	public static function Replace_Layout( $prefix ) {
		SP_PC::createOptions(
			$prefix,
			array(
				'menu_title'       => __( 'Replace Layout', 'post-carousel' ),
				'menu_parent'      => 'edit.php?post_type=sp_post_carousel',
				'menu_type'        => 'submenu', // menu, submenu, options, theme, etc.
				'menu_slug'        => 'pcp_replace_layout',
				'theme'            => 'light',
				'show_all_options' => false,
				'show_search'      => false,
				'show_footer'      => false,
				'show_bar_menu'    => false,
				'show_reset_all'   => false,
				'class'            => 'sp-pc-settings pcp_replace_layout',
				'framework_title'  => __( 'Replace Layout', 'post-carousel' ),
			)
		);

		SP_PC::createSection(
			$prefix,
			array(
				'title'  => __( 'Standard Archives', 'post-carousel' ),
				'fields' => array(
					array(
						'id'         => 'home',
						'type'       => 'switcher',
						'title'      => __( 'Posts Page (Default Blog)', 'post-carousel' ),
						'text_on'    => __( 'Enabled', 'post-carousel' ),
						'text_off'   => __( 'Disabled', 'post-carousel' ),
						'text_width' => 100,
						'default'    => true,
					),
					array(
						'id'         => 'sps_home',
						'type'       => 'fieldset',
						'class'      => 'spsp_no_border',
						'dependency' => array( 'home', '==', 'true', true ),
						'fields'     => array(
							array(
								'id'          => 'pcp_which_show',
								'class'       => 'pcp_post_ids',
								'type'        => 'select',
								'title'       => __( 'The Layout To Show Posts', 'post-carousel' ),
								'options'     => array(
									'sp_post_carousel' => __( 'Choose show(s)', 'post-carousel' ),
								),
								'chosen'      => true,
								'sortable'    => false,
								'multiple'    => false,
								'placeholder' => __( 'Choose show(s)', 'post-carousel' ),
							),
							array(
								'id'      => 'replace_way',
								'class'   => 'pcp_post_ids',
								'type'    => 'radio',
								'title'   => __( 'The Way To Retrieve Posts', 'post-carousel' ),
								'default' => 'no_change',
								'options' => array(
									'no_change'   => 'No change',
									'change_sort' => 'Change order only (use "Sort by" setting of the show)',
									'change_full' => 'Change completely (use all "Filter Content" of the show)',
								),
							),
						),
					),
					array(
						'id'         => 'search',
						'title'      => __( 'Search', 'post-carousel' ),
						'text_on'    => __( 'Enabled', 'post-carousel' ),
						'text_off'   => __( 'Disabled', 'post-carousel' ),
						'type'       => 'switcher',
						'text_width' => 100,
						'default'    => false,
					),
					array(
						'id'         => 'sps_search',
						'type'       => 'fieldset',
						'class'      => 'spsp_no_border',
						'dependency' => array( 'search', '==', 'true', true ),
						'fields'     => array(
							array(
								'id'          => 'pcp_which_show',
								'class'       => 'pcp_post_ids',
								'type'        => 'select',
								'title'       => __( 'The Layout To Show Posts', 'post-carousel' ),
								'options'     => 'sp_post_carousel',
								'chosen'      => true,
								'sortable'    => false,
								'multiple'    => false,
								'placeholder' => __( 'Choose show(s)', 'post-carousel' ),
								'query_args'  => array(
									'posts_per_page' => -1,
								),
							),
							array(
								'id'      => 'replace_way',
								'class'   => 'pcp_post_ids',
								'type'    => 'radio',
								'title'   => __( 'The Way To Retrieve Posts', 'post-carousel' ),
								'default' => 'no_change',
								'options' => array(
									'no_change'   => 'No change',
									'change_sort' => 'Change order only (use "Sort by" setting of the show)',
									'change_full' => 'Change completely (use all "Filter Content" of the show)',
								),
							),
						),
					),
					array(
						'id'         => 'author',
						'title'      => __( 'Author Page', 'post-carousel' ),
						'text_on'    => __( 'Enabled', 'post-carousel' ),
						'text_off'   => __( 'Disabled', 'post-carousel' ),
						'type'       => 'switcher',
						'text_width' => 100,
						'default'    => false,
					),
					array(
						'id'         => 'sps_author',
						'type'       => 'fieldset',
						'class'      => 'spsp_no_border',
						'dependency' => array( 'author', '==', 'true', true ),
						'fields'     => array(
							array(
								'id'          => 'pcp_which_show',
								'class'       => 'pcp_post_ids',
								'type'        => 'select',
								'title'       => __( 'The Layout To Show Posts', 'post-carousel' ),
								'options'     => 'sp_post_carousel',
								'chosen'      => true,
								'sortable'    => false,
								'multiple'    => false,
								'placeholder' => __( 'Choose show(s)', 'post-carousel' ),
								'query_args'  => array(
									'posts_per_page' => -1,
								),
							),
							array(
								'id'      => 'replace_way',
								'class'   => 'pcp_post_ids',
								'type'    => 'radio',
								'title'   => __( 'The Way To Retrieve Posts', 'post-carousel' ),
								'default' => 'no_change',
								'options' => array(
									'no_change'   => 'No change',
									'change_sort' => 'Change order only (use "Sort by" setting of the show)',
									'change_full' => 'Change completely (use all "Filter Content" of the show)',
								),
							),
						),
					),
					array(
						'id'         => 'time',
						'text_on'    => __( 'Enabled', 'post-carousel' ),
						'text_off'   => __( 'Disabled', 'post-carousel' ),
						'type'       => 'switcher',
						'text_width' => 100,
						'default'    => false,
						'title'      => __( 'Date, Month, Year', 'post-carousel' ),
					),
					array(
						'id'         => 'sps_time',
						'type'       => 'fieldset',
						'class'      => 'spsp_no_border',
						'dependency' => array( 'time', '==', 'true', true ),
						'fields'     => array(
							array(
								'id'          => 'pcp_which_show',
								'class'       => 'pcp_post_ids',
								'type'        => 'select',
								'title'       => __( 'The Layout To Show Posts', 'post-carousel' ),
								'options'     => 'sp_post_carousel',
								'chosen'      => true,
								'sortable'    => false,
								'multiple'    => false,
								'placeholder' => __( 'Choose show(s)', 'post-carousel' ),
								'query_args'  => array(
									'posts_per_page' => -1,
								),
							),
							array(
								'id'      => 'replace_way',
								'class'   => 'pcp_post_ids',
								'type'    => 'radio',
								'title'   => __( 'The Way To Retrieve Posts', 'post-carousel' ),
								'default' => 'no_change',
								'options' => array(
									'no_change'   => 'No change',
									'change_sort' => 'Change order only (use "Sort by" setting of the show)',
									'change_full' => 'Change completely (use all "Filter Content" of the show)',
								),
							),
						),
					),
				),
			)
		);

		SP_PC::createSection(
			$prefix,
			array(
				'title'  => __( 'Taxonomy Archives', 'post-carousel' ),
				'fields' => array(),
			)
		);
		SP_PC::createSection(
			$prefix,
			array(
				'title'  => __( 'Post Type Archives', 'post-carousel' ),
				'fields' => array(),
			)
		);
	}
}
