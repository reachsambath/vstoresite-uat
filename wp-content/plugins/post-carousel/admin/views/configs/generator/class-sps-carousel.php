<?php
/**
 * The Carousel section Meta-box configurations.
 *
 * @package Smart_Post_Show
 * @subpackage Smart_Post_Show/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

/**
 * The Carousel building class.
 */
class SPS_Carousel {

	/**
	 * Carousel section metabox.
	 *
	 * @param string $prefix The metabox key.
	 * @return void
	 */
	public static function section( $prefix ) {
		SP_PC::createSection(
			$prefix,
			array(
				'title'  => __( 'Carousel Settings', 'post-carousel' ),
				'icon'   => 'sps-icon-carousel-settings',
				'fields' => array(
					array(
						'type'  => 'tabbed',
						'class' => 'pcp-carousel-tabs',
						'tabs'  => array(
							array(
								'title'  => __( 'Carousel Controls', 'post-carousel' ),
								'icon'   => '<i class="sps-icon-carousel-basic" aria-hidden="true"></i>',
								'fields' => array(
									array(
										'id'       => 'pcp_carousel_direction',
										'type'     => 'button_set',
										'title'    => __( 'Carousel Direction', 'post-carousel' ),
										'subtitle' => __( 'Choose a carousel direction.', 'post-carousel' ),
										'options'  => array(
											'ltr' => __( 'Right to Left', 'post-carousel' ),
											'rtl' => __( 'Left to Right', 'post-carousel' ),
										),
										'default'  => 'ltr',
									),
									array(
										'id'         => 'pcp_autoplay',
										'type'       => 'switcher',
										'title'      => __( 'AutoPlay', 'post-carousel' ),
										'subtitle'   => __( 'Enable/Disable carousel autoplay.', 'post-carousel' ),
										'default'    => true,
										'text_on'    => __( 'Enabled', 'post-carousel' ),
										'text_off'   => __( 'Disabled', 'post-carousel' ),
										'text_width' => 100,
									),
									array(
										'id'         => 'pcp_autoplay_speed',
										'type'       => 'slider',
										'title'      => __( 'AutoPlay Delay', 'post-carousel' ),
										'subtitle'   => __( 'Set autoplay delay time in millisecond.', 'post-carousel' ),
										'default'    => 2000,
										'sanitize'   => 'spf_pcp_sanitize_number_field',
										'min'        => 0,
										'max'        => 10000,
										'step'       => 100,
										'unit'       => 'ms',
										'title_info' => '<div class="spf-info-label">' . __( 'AutoPlay Delay Time', 'post-carousel' ) . '</div> <div class="spf-short-content">' . __( 'Set autoplay delay or interval time. The amount of time to delay between automatically cycling a member. e.g. 1000 milliseconds(ms) = 1 second.', 'post-carousel' ) . '</div>',
										'dependency' => array( 'pcp_autoplay', '==', true ),
									),
									array(
										'id'       => 'pcp_carousel_speed',
										'type'     => 'slider',
										'title'    => __( 'Carousel Speed', 'post-carousel' ),
										'subtitle' => __( 'Set carousel speed in millisecond.', 'post-carousel' ),
										'sanitize' => 'spf_pcp_sanitize_number_field',
										'class'    => 'carousel_auto_play_ranger',
										'default'  => 600,
										'min'      => 0,
										'max'      => 20000,
										'step'     => 100,
										'unit'     => 'ms',
									),
									array(
										'id'         => 'pcp_pause_hover',
										'type'       => 'switcher',
										'title'      => __( 'Pause on Hover', 'post-carousel' ),
										'subtitle'   => __( 'Enable/Disable carousel stop on hover.', 'post-carousel' ),
										'default'    => true,
										'text_on'    => __( 'Enabled', 'post-carousel' ),
										'text_off'   => __( 'Disabled', 'post-carousel' ),
										'text_width' => 100,
										'dependency' => array( 'pcp_autoplay', '==', true ),

									),
									array(
										'id'         => 'pcp_infinite_loop',
										'type'       => 'switcher',
										'title'      => __( 'Infinite Loop', 'post-carousel' ),
										'subtitle'   => __( 'Enable/Disable carousel infinite loop.', 'post-carousel' ),
										'text_on'    => __( 'Enabled', 'post-carousel' ),
										'text_off'   => __( 'Disabled', 'post-carousel' ),
										'text_width' => 100,
										'default'    => true,
									),
									array(
										'id'         => 'pcp_lazy_load',
										'type'       => 'switcher',
										'title'      => __( 'Lazy Load', 'post-carousel' ),
										'subtitle'   => __( 'Enable/Disable lazy load.', 'post-carousel' ),
										'text_on'    => __( 'Enabled', 'post-carousel' ),
										'text_off'   => __( 'Disabled', 'post-carousel' ),
										'text_width' => 100,
										'default'    => true,
									),
									array(
										'id'         => 'pcp_slide_effect',
										'type'       => 'select',
										'title'      => __( 'Transition Effect', 'post-carousel' ),
										'subtitle'   => __( 'Select a slide transition effect.', 'post-carousel' ),
										'title_info' => __( 'Fade, cube, and flip transition effects work only for the single column view.', 'post-carousel' ),
										'options'    => array(
											'slide'     => __( 'Slide', 'post-carousel' ),
											'fade'      => __( 'Fade (Pro)', 'post-carousel' ),
											'coverflow' => __( 'Coverflow (Pro)', 'post-carousel' ),
											'cube'      => __( 'Cube (Pro)', 'post-carousel' ),
											'flip'      => __( 'Flip (Pro)', 'post-carousel' ),
										),
										'default'    => 'slide',
									),
								),
							),
							array(
								'title'  => __( 'Navigation', 'post-carousel' ),
								'icon'   => '<i class="sps-icon-navigation" aria-hidden="true"></i>',
								'fields' => array(
									// Navigation Settings.
									array(
										'id'     => 'pcp_navigation_data',
										'class'  => 'navigation-and-pagination-style',
										'type'   => 'fieldset',
										'fields' => array(
											array(
												'id'       => 'pcp_navigation',
												'type'     => 'switcher',
												'title'    => __( 'Navigation', 'post-carousel' ),
												'class'    => 'pcp_navigation',
												'subtitle' => __( 'Show/hide navigation.', 'post-carousel' ),
												'text_on'  => __( 'Show', 'post-carousel' ),
												'text_off' => __( 'Hide', 'post-carousel' ),
												'text_width' => 80,
												'default'  => true,
											),
											array(
												'id'      => 'nev_hide_on_mobile',
												'type'    => 'checkbox',
												'class'   => 'pcp_hide_on_mobile',
												'title'   => __( 'Hide on Mobile', 'post-carousel' ),
												'default' => false,
												'dependency' => array( 'pcp_navigation', '==', 'true', true ),
											),
										),
									),
									array(
										'id'         => 'pcp_carousel_nav_position',
										'class'      => 'pcp_carousel_nav_position',
										'type'       => 'select',
										'preview'    => true,
										'title'      => __( 'Navigation Position', 'post-carousel' ),
										'subtitle'   => __( 'Select a position for the navigation arrows.', 'post-carousel' ),
										'options'    => array(
											'top_right'    => __( 'Top Right', 'post-carousel' ),
											'top_center'   => __( 'Top Center', 'post-carousel' ),
											'top_left'     => __( 'Top Left', 'post-carousel' ),
											'bottom_left'  => __( 'Bottom Left', 'post-carousel' ),
											'bottom_center' => __( 'Bottom Center', 'post-carousel' ),
											'bottom_right' => __( 'Bottom Right', 'post-carousel' ),
											'vertically_center_outer' => __( 'Vertical Center Outer', 'post-carousel' ),
											'vertical_center' => __( 'Vertical Center', 'post-carousel' ),
											'vertical_center_inner' => __( 'Vertical Center Inner', 'post-carousel' ),
										),
										'default'    => 'top_right',
										'dependency' => array( 'pcp_navigation|pcp_carousel_mode', '!=|!=', 'hide|ticker', true ),
									),
									array(
										'id'         => 'nev_visible_on_hover',
										'type'       => 'checkbox',
										'title'      => __( 'Visible On Hover', 'post-carousel' ),
										'subtitle'   => __( 'Check to show navigation on hover in the carousel or slider area.', 'post-carousel' ),
										'default'    => false,
										'dependency' => array( 'pcp_navigation|pcp_carousel_mode|pcp_carousel_nav_position', '==|!=|any', 'true|ticker|vertically_center_outer,vertical_center,vertical_center_inner', true ),
									),
									array(
										'id'         => 'pcp_nav_colors',
										'type'       => 'color_group',
										'title'      => __( 'Color', 'post-carousel' ),
										'subtitle'   => __( 'Set color for the carousel navigation.', 'post-carousel' ),
										'sanitize'   => 'spf_pcp_sanitize_color_group_field',
										'options'    => array(
											'color'       => __( 'Color', 'post-carousel' ),
											'hover-color' => __( 'Hover Color', 'post-carousel' ),
											'bg'          => __( 'Background', 'post-carousel' ),
											'hover-bg'    => __( 'Hover Background', 'post-carousel' ),
										),
										'default'    => array(
											'color'       => '#aaa',
											'hover-color' => '#fff',
											'bg'          => '#fff',
											'hover-bg'    => '#D64224',
										),
										'dependency' => array( 'pcp_navigation', '==', 'true' ),
									),
									array(
										'id'            => 'pcp_nav_border',
										'type'          => 'border',
										'title'         => __( 'Border', 'post-carousel' ),
										'subtitle'      => __( 'Set border for the navigation.', 'post-carousel' ),
										'all'           => true,
										'border_radius' => true,
										'hover_color'   => true,
										'show_units'    => true,
										'units'         => array( 'px', '%' ),
										'default'       => array(
											'all'         => '1',
											'style'       => 'solid',
											'color'       => '#aaa',
											'hover_color' => '#e1624b',
											'border_radius' => '0',
											'unit'        => 'px',
										),
										'dependency'    => array( 'pcp_navigation|pcp_carousel_mode', '==|!=', 'true|ticker', true ),
									),
									array(
										'type'    => 'notice',
										'class'   => 'taxonomy-ajax-filter-notice',
										'content' => __( 'Want even more fine-tuned control over your Carousel Navigation display?', 'post-carousel' ) . ' <a href="https://smartpostshow.com/pricing/?ref=1" target="_blank"><b>' . __( 'Upgrade To Pro!', 'post-carousel' ) . '</b></a>',
									),
								),
							),
							array(
								'title'  => __( 'Pagination', 'post-carousel' ),
								'icon'   => '<i class="sps-icon-pagination" aria-hidden="true"></i>',
								'fields' => array(
									array(
										'id'     => 'carousel_pagination_group',
										'class'  => 'navigation-and-pagination-style',
										'type'   => 'fieldset',
										'fields' => array(
											array(
												'id'       => 'pcp_pagination',
												'type'     => 'switcher',
												'title'    => __( 'Pagination', 'post-carousel' ),
												'class'    => 'pcp_pagination',
												'subtitle' => __( 'Show/hide navigation.', 'post-carousel' ),
												'text_on'  => __( 'Show', 'post-carousel' ),
												'text_off' => __( 'Hide', 'post-carousel' ),
												'text_width' => 77,
												'default'  => true,
												'dependency' => array( 'pcp_carousel_mode', '!=', 'ticker', true ),
											),
											array(
												'id'      => 'pagination_hide_on_mobile',
												'type'    => 'checkbox',
												'class'   => 'pcp_hide_on_mobile',
												'title'   => __( 'Hide on Mobile', 'post-carousel' ),
												'default' => false,
												'dependency' => array( 'pcp_carousel_mode|pcp_pagination', '!=|==', 'ticker|true', true ),
											),
										),
									),
									array(
										'id'         => 'bullet_types',
										'type'       => 'layout_preset',
										'class'      => 'hide-active-sign',
										'title'      => __( 'Pagination Type', 'post-carousel' ),
										'subtitle'   => __( 'Select a style for pagination.', 'post-carousel' ),
										'options'    => array(
											'dots'      => array(
												'image' => SP_PC_URL . 'admin/img/pagination-type/bullets.svg',
												'text'  => __( 'Dots', 'post-carousel' ),
											),
											'dynamic'   => array(
												'image'    => SP_PC_URL . 'admin/img/pagination-type/dynamic.svg',
												'text'     => __( 'Dynamic', 'post-carousel' ),
												'pro_only' => true,
											),
											'strokes'   => array(
												'image'    => SP_PC_URL . 'admin/img/pagination-type/strokes.svg',
												'text'     => __( 'Strokes', 'post-carousel' ),
												'pro_only' => true,
											),
											'scrollbar' => array(
												'image'    => SP_PC_URL . 'admin/img/pagination-type/scrollbar.svg',
												'text'     => __( 'Scrollbar', 'post-carousel' ),
												'pro_only' => true,
											),
											'fraction'  => array(
												'image'    => SP_PC_URL . 'admin/img/pagination-type/fraction.svg',
												'text'     => __( 'Fraction', 'post-carousel' ),
												'pro_only' => true,
											),
											'number'    => array(
												'image'    => SP_PC_URL . 'admin/img/pagination-type/numbers.svg',
												'text'     => __( 'Number', 'post-carousel' ),
												'pro_only' => true,
											),
										),
										'default'    => 'dots',
										'dependency' => array( 'pcp_pagination|pcp_carousel_mode', '==|!=', 'true|ticker', true ),
									),
									array(
										'id'         => 'pcp_pagination_color_set',
										'type'       => 'fieldset',
										'class'      => 'pcp-pagination-color-set',
										'title'      => __( 'Pagination Color', 'post-carousel' ),
										'subtitle'   => __( 'Set color for the carousel pagination.', 'post-carousel' ),
										'fields'     => array(
											array(
												'id'      => 'pcp_pagination_color',
												'type'    => 'color_group',
												'options' => array(
													'color' => __( 'Color', 'post-carousel' ),
													'active-color' => __( 'Active Color', 'post-carousel' ),
												),
												'default' => array(
													'color' => '#cccccc',
													'active-color' => '#D64224',
												),
											),
										),
										'dependency' => array( 'pcp_pagination', '!=', 'hide' ),
									),
									array(
										'type'    => 'notice',
										'class'   => 'taxonomy-ajax-filter-notice',
										'content' => __( 'Want even more fine-tuned control over your Carousel Pagination display?', 'post-carousel' ) . ' <a href="https://smartpostshow.com/pricing/?ref=1" target="_blank"><b>' . __( 'Upgrade To Pro!', 'post-carousel' ) . '</b></a>',
									),
								),
							),
							array(
								'title'  => __( 'Miscellaneous', 'post-carousel' ),
								'icon'   => '<i class="sps-icon-others" aria-hidden="true"></i>',
								'fields' => array(
									array(
										'id'         => 'pcp_adaptive_height',
										'type'       => 'switcher',
										'title'      => __( 'Adaptive Carousel Height', 'post-carousel' ),
										'subtitle'   => __( 'Dynamically adjust post carousel height based on each slide\'s height.', 'post-carousel' ),
										'default'    => false,
										'text_on'    => __( 'Enabled', 'post-carousel' ),
										'text_off'   => __( 'Disabled', 'post-carousel' ),
										'text_width' => 100,
									),
									array(
										'id'         => 'pcp_accessibility',
										'type'       => 'switcher',
										'title'      => __( 'Tab and Key Navigation', 'post-carousel' ),
										'subtitle'   => __( 'Enable/Disable carousel scroll with tab and keyboard.', 'post-carousel' ),
										'default'    => true,
										'text_on'    => __( 'Enabled', 'post-carousel' ),
										'text_off'   => __( 'Disabled', 'post-carousel' ),
										'text_width' => 100,
									),
									array(
										'id'         => 'touch_swipe',
										'type'       => 'switcher',
										'title'      => __( 'Touch Swipe', 'post-carousel' ),
										'subtitle'   => __( 'Enable/Disable touch swipe mode.', 'post-carousel' ),
										'text_on'    => __( 'Enabled', 'post-carousel' ),
										'text_off'   => __( 'Disabled', 'post-carousel' ),
										'text_width' => 100,
										'default'    => true,
									),
									array(
										'id'         => 'slider_draggable',
										'type'       => 'switcher',
										'title'      => __( 'Mouse Draggable', 'post-carousel' ),
										'subtitle'   => __( 'Enable/Disable mouse draggable mode.', 'post-carousel' ),
										'text_on'    => __( 'Enabled', 'post-carousel' ),
										'text_off'   => __( 'Disabled', 'post-carousel' ),
										'text_width' => 100,
										'default'    => true,
									),
									array(
										'id'         => 'free_mode',
										'type'       => 'switcher',
										'title'      => __( 'Free Mode', 'post-carousel' ),
										'subtitle'   => __( 'Enable/Disable free mode.', 'post-carousel' ),
										'text_on'    => __( 'Enabled', 'post-carousel' ),
										'text_off'   => __( 'Disabled', 'post-carousel' ),
										'text_width' => 100,
										'default'    => false,
									),
									array(
										'id'         => 'slider_mouse_wheel',
										'type'       => 'switcher',
										'title'      => __( 'Mouse Wheel', 'post-carousel' ),
										'subtitle'   => __( 'Enable/Disable mouse wheel mode.', 'post-carousel' ),
										'text_on'    => __( 'Enabled', 'post-carousel' ),
										'text_off'   => __( 'Disabled', 'post-carousel' ),
										'text_width' => 100,
										'default'    => false,
									),
								),
							),
						),
					),
				), // End of fields array.
			)
		); // Carousel Controls section end.
	}
}
