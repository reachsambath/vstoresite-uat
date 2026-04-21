<?php
/**
 * The Typography Meta-box configurations.
 *
 * @package Smart_Post_Show
 * @subpackage Smart_Post_Show/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access pages directly.

/**
 * The Typography class.
 */
class SPS_Typography {

	/**
	 * Typography section metabox.
	 *
	 * @param string $prefix The metabox key.
	 * @return void
	 */
	public static function section( $prefix ) {
		SP_PC::createSection(
			$prefix,
			array(
				'title'           => __( 'Typography', 'post-carousel' ),
				'icon'            => 'sps-icon-typography',
				'enqueue_webfont' => true,
				'fields'          => array(
					array(
						'type'    => 'notice',
						'class'   => 'taxonomy-ajax-filter-notice',
						'content' => __( 'Want to easily customize everything (Typography, Colors, Margin)?', 'post-carousel' ) . ' <a href="https://smartpostshow.com/pricing/?ref=1" target="_blank"><b>' . __( 'Upgrade To Pro!', 'post-carousel' ) . '</b></a> ' . __( 'P.S. Note: The color fields work in the lite version.', 'post-carousel' ),
					),
					array(
						'id'         => 'section_title_typography',
						'type'       => 'typography',
						'title'      => __( 'Section Title', 'post-carousel' ),
						'subtitle'   => __( 'Set item showcase section title font properties.', 'post-carousel' ),
						'default'    => array(
							'color'              => '#444',
							'font-family'        => '',
							'font-weight'        => '',
							'subset'             => '',
							'font-size'          => '24',
							'tablet-font-size'   => '20',
							'mobile-font-size'   => '18',
							'line-height'        => '28',
							'tablet-line-height' => '24',
							'mobile-line-height' => '20',
							'letter-spacing'     => '0',
							'text-align'         => 'left',
							'text-transform'     => 'none',
							'type'               => '',
							'unit'               => 'px',
						),
						'dependency' => array( 'section_title', '==', 'true', 'all' ),
					),
					array(
						'id'              => 'section_title_margin',
						'type'            => 'spacing',
						'title'           => __( 'Section Title Margin', 'post-carousel' ),
						'subtitle'        => __( 'Set margin for the section title.', 'post-carousel' ),
						'all_icon'        => '<i class="fa fa-long-arrow-down"></i>',
						'units'           => array(
							'px',
						),
						'all_placeholder' => 'margin',
						'default'         => array(
							'top'    => '0',
							'right'  => '0',
							'bottom' => '30',
							'left'   => '0',
						),
						'dependency'      => array(
							'section_title',
							'==',
							'true',
							true,
						),
					),
					array(
						'id'          => 'post_title_typography',
						'type'        => 'typography',
						'title'       => __( 'Title', 'post-carousel' ),
						'subtitle'    => __( 'Set title font properties.', 'post-carousel' ),
						'hover_color' => true,
						'default'     => array(
							'color'              => '#111',
							'hover_color'        => '#e1624b',
							'font-family'        => '',
							'font-weight'        => '',
							'subset'             => '',
							'font-size'          => '20',
							'tablet-font-size'   => '18',
							'mobile-font-size'   => '16',
							'line-height'        => '24',
							'tablet-line-height' => '22',
							'mobile-line-height' => '20',
							'letter-spacing'     => '0',
							'text-align'         => 'left',
							'text-transform'     => 'none',
							'type'               => '',
							'unit'               => 'px',
						),
						'dependency'  => array( 'show_post_title', '==', 'true', 'all' ),
					),
					array(
						'id'          => 'post_meta_typography',
						'type'        => 'typography',
						'title'       => __( 'Meta Fields', 'post-carousel' ),
						'subtitle'    => __( 'Set meta fields font properties.', 'post-carousel' ),
						'hover_color' => true,
						'default'     => array(
							'color'              => '#888',
							'hover_color'        => '#e1624b',
							'font-family'        => '',
							'font-weight'        => '',
							'subset'             => '',
							'font-size'          => '14',
							'tablet-font-size'   => '14',
							'mobile-font-size'   => '12',
							'line-height'        => '16',
							'tablet-line-height' => '16',
							'mobile-line-height' => '16',
							'letter-spacing'     => '0',
							'text-align'         => 'left',
							'text-transform'     => 'none',
							'type'               => '',
							'unit'               => 'px',
						),
					),
					array(
						'id'         => 'post_content_typography',
						'type'       => 'typography',
						'title'      => __( 'Content', 'post-carousel' ),
						'subtitle'   => __( 'Set content font properties.', 'post-carousel' ),
						'default'    => array(
							'color'              => '#444',
							'font-family'        => '',
							'font-weight'        => '',
							'subset'             => '',
							'font-size'          => '16',
							'tablet-font-size'   => '14',
							'mobile-font-size'   => '12',
							'line-height'        => '20',
							'tablet-line-height' => '18',
							'mobile-line-height' => '18',
							'letter-spacing'     => '0',
							'text-align'         => 'left',
							'text-transform'     => 'none',
							'type'               => '',
							'unit'               => 'px',
						),
						'dependency' => array( 'show_post_content', '==', 'true', 'all' ),
					),
					array(
						'id'         => 'read_more_typography',
						'type'       => 'typography',
						'title'      => __( 'Read More', 'post-carousel' ),
						'subtitle'   => __( 'Set read more font properties.', 'post-carousel' ),
						'color'      => false,
						'default'    => array(
							'font-family'        => '',
							'font-weight'        => '600',
							'subset'             => '',
							'font-size'          => '12',
							'tablet-font-size'   => '12',
							'mobile-font-size'   => '10',
							'line-height'        => '18',
							'tablet-line-height' => '18',
							'mobile-line-height' => '16',
							'letter-spacing'     => '0',
							'text-align'         => 'left',
							'text-transform'     => 'uppercase',
							'type'               => '',
							'unit'               => 'px',
						),
						'dependency' => array( 'show_read_more|show_post_content', '==|==', 'true|true', 'all' ),
					),

					array(
						'id'       => 'custom_fields_typography',
						'type'     => 'typography',
						'title'    => __( 'Custom Fields', 'post-carousel' ),
						'subtitle' => __( 'Set custom fields font properties.', 'post-carousel' ),
						'color'    => false,
						'default'  => array(
							// 'color'              => '#888',
																	// 'hover_color'        => '#444',
																	'font-family' => '',
							'font-weight'        => '',
							'subset'             => '',
							'font-size'          => '14',
							'tablet-font-size'   => '14',
							'mobile-font-size'   => '12',
							'line-height'        => '18',
							'tablet-line-height' => '18',
							'mobile-line-height' => '16',
							'letter-spacing'     => '0',
							'text-align'         => 'left',
							'text-transform'     => 'none',
							'type'               => '',
							'unit'               => 'px',
						),
					),
				), // End of fields array.
			)
		);
	}
}
