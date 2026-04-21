<?php
update_option( 'smart_post_show_version', '2.2.0' );
update_option( 'smart_post_show_db_version', '2.2.0' );

/**
 * Update settings.
 */
$setting_options = array(
	'pcp_delete_all_data' => false,
	'pcp_swiper_js'       => true,
	'pcp_swiper_css'      => true,
	'pcp_fontawesome_css' => true,
	'accessibility'       => false,
	'pcp_custom_css'      => '',
	'pcp_custom_js'       => '',
);
update_option( 'sp_post_carousel_settings', $setting_options );

/**
 * Shortcode query for id.
 */
$args          = new WP_Query(
	array(
		'post_type'      => 'sp_pc_shortcodes',
		'post_status'    => 'any',
		'posts_per_page' => '300',
	)
);
$shortcode_ids = wp_list_pluck( $args->posts, 'ID' );

if ( count( $shortcode_ids ) > 0 ) {
	foreach ( $shortcode_ids as $shortcode_key => $shortcode_id ) {

		$number_of_total_posts    = intval( get_post_meta( $shortcode_id, 'pc_number_of_total_posts', true ) );
		$number_of_column         = intval( get_post_meta( $shortcode_id, 'pc_number_of_column', true ) );
		$number_of_column_desktop = intval( get_post_meta( $shortcode_id, 'pc_number_of_column_desktop', true ) );
		$number_of_column_tablet  = intval( get_post_meta( $shortcode_id, 'pc_number_of_column_tablet', true ) );
		$number_of_column_mobile  = intval( get_post_meta( $shortcode_id, 'pc_number_of_column_mobile', true ) );
		$auto_play_speed          = get_post_meta( $shortcode_id, 'pc_auto_play_speed', true );
		$nav_arrow_color          = get_post_meta( $shortcode_id, 'pc_nav_arrow_color', true );
		$nav_arrow_bg             = get_post_meta( $shortcode_id, 'pc_nav_arrow_bg', true );
		$pagination_color         = get_post_meta( $shortcode_id, 'pc_pagination_color', true );
		$pagination_active_color  = get_post_meta( $shortcode_id, 'pc_pagination_active_color', true );
		$scroll_speed             = get_post_meta( $shortcode_id, 'pc_scroll_speed', true );
		$post_title_color         = get_post_meta( $shortcode_id, 'pc_post_title_color', true );
		$post_title_hover_color   = get_post_meta( $shortcode_id, 'pc_post_title_hover_color', true );
		$post_content_color       = get_post_meta( $shortcode_id, 'pc_post_content_color', true );
		$post_meta_color          = get_post_meta( $shortcode_id, 'pc_post_meta_color', true );
		$post_meta_hover_color    = get_post_meta( $shortcode_id, 'pc_post_meta_hover_color', true );
		$carousel_title_color     = get_post_meta( $shortcode_id, 'pc_carousel_title_color', true );
		$themes                   = get_post_meta( $shortcode_id, 'pc_themes', true );
		$post_title               = get_post_meta( $shortcode_id, 'pc_post_title', 'true' );
		$post_content             = get_post_meta( $shortcode_id, 'pc_post_content', 'true' );
		// $post_author          = get_post_meta( $shortcode_id, 'pc_post_author', 'true' );
		// $post_date            = get_post_meta( $shortcode_id, 'pc_post_date', 'true' );
		$section_title_data = get_post_meta( $shortcode_id, 'pc_carousel_title', true );
		$section_title      = ( 'on' == $section_title_data ) ? true : false;

		$view_options['pcp_select_post_type']        = 'post';
		$view_options['pcp_include_only_posts']      = '';
		$view_options['pcp_exclude_post_set']        = '';
		$view_options['pcp_post_limit']              = $number_of_total_posts;
		$view_options['pcp_advanced_filter']         = '';
		$view_options['section_title']               = $section_title;
		$view_options['section_title_margin']        = array(
			'top'    => '0',
			'right'  => '0',
			'bottom' => '30',
			'top'    => '0',
		);
		$view_options['pcp_number_of_columns']       = array(
			'lg_desktop'       => $number_of_column,
			'desktop'          => $number_of_column_desktop,
			'tablet'           => $number_of_column_tablet,
			'mobile_landscape' => $number_of_column_tablet,
			'mobile'           => $number_of_column_mobile,
		);
		$view_options['margin_between_post']         = array(
			'all' => '20',
		);
		$view_options['post_content_orientation']    = 'default';
		$border_size                                 = ( 'carousel_two' == $themes ) ? '1' : '0';
		$border_color                                = ( 'carousel_two' == $themes ) ? '#ddd' : '#e2e2e2';
		$view_options['post_border']                 = array(
			'all'   => $border_size,
			'style' => 'solid',
			'color' => $border_color,
		);
		$view_options['post_border_radius_property'] = array(
			'all' => '0',
		);
		$view_options['post_background_property']    = 'transparent';
		$inner_padding                               = ( 'carousel_two' == $themes ) ? '15' : '0';
		$view_options['post_inner_padding_property'] = array(
			'top'    => $inner_padding,
			'right'  => $inner_padding,
			'bottom' => $inner_padding,
			'left'   => $inner_padding,
			'unit'   => 'px',
		);
		$view_options['post_content_sorter']         = array(
			'pcp_post_thumb'   => array(
				'post_thumb_show' => true,
				'pcp_thumb_sizes' => 'full',
			),
			'pcp_post_title'   => array(
				'show_post_title' => ( 'on' == $post_title ) ? true : false,
				'post_title_tag'  => 'h2',
			),
			'pcp_post_meta'    => array(
				'show_post_meta'      => true,
				'pcp_post_meta_group' => array(
					array(
						'select_post_meta' => 'author',
						'select_meta_icon' => 'fa fa-user',
					),
					array(
						'select_post_meta' => 'date',
						'select_meta_icon' => 'fa fa-calendar',
					),
				),
			),
			'pcp_post_content' => array(
				'show_post_content'     => ( 'hide' == $post_content ) ? false : true,
				'post_content_type'     => ( 'content_with_limit' == $post_content ) ? 'excerpt' : 'full_content',
				'show_read_more'        => false,
				'pcp_read_label'        => 'Read More',
				'readmore_color_button' => array(
					'standard'     => '#111',
					'hover'        => '#fff',
					'bg'           => 'transparent',
					'hover_bg'     => '#e1624b',
					'border'       => '#888',
					'hover_border' => '#e1624b',
				),
			),
		);
		$view_options['show_post_pagination']        = false;
		$view_options['post_pagination_type']        = 'no_ajax';
		$view_options['pcp_pagination_btn_color']    = array(
			'text_color'        => '#5e5e5e',
			'text_acolor'       => '#ffffff',
			'border_color'      => '#bbbbbb',
			'border_acolor'     => '#e1624b',
			'background'        => '#ffffff',
			'active_background' => '#e1624b',
		);
		$view_options['post_per_page']               = 12;
		$view_options['show_preloader']              = false;

		$view_options['pcp_carousel_speed']       = $scroll_speed;
		$auto_play                                = get_post_meta( $shortcode_id, 'pc_auto_play', 'true' );
		$view_options['pcp_autoplay']             = ( 'on' == $auto_play ) ? true : false;
		$view_options['pcp_autoplay_speed']       = $auto_play_speed;
		$pause_on_hover                           = get_post_meta( $shortcode_id, 'pc_pause_on_hover', 'true' );
		$view_options['pcp_pause_hover']          = ( 'on' == $pause_on_hover ) ? true : false;
		$view_options['pcp_infinite_loop']        = true;
		$view_options['pcp_lazy_load']            = false;
		$rtl                                      = get_post_meta( $shortcode_id, 'pc_rtl', 'true' );
		$view_options['pcp_carousel_direction']   = $rtl;
		$show_navigation                          = get_post_meta( $shortcode_id, 'pc_show_navigation', 'true' );
		$view_options['pcp_navigation']           = ( 'on' == $show_navigation ) ? 'show' : 'hide';
		$view_options['pcp_nav_colors']           = array(
			'color'              => $nav_arrow_color,
			'hover-color'        => $nav_arrow_color,
			'bg'                 => $nav_arrow_bg,
			'hover-bg'           => $nav_arrow_bg,
			'border-color'       => $nav_arrow_bg,
			'hover-border-color' => $nav_arrow_bg,
		);
		$show_pagination_dots                     = get_post_meta( $shortcode_id, 'pc_show_pagination_dots', 'true' );
		$view_options['pcp_pagination']           = ( 'on' == $show_pagination_dots ) ? 'show' : 'hide';
		$view_options['pcp_pagination_color_set'] = array(
			'pcp_pagination_color' => array(
				'color'        => $pagination_color,
				'active-color' => $pagination_active_color,
			),
		);
		$view_options['pcp_adaptive_height']      = '';
		$view_options['pcp_accessibility']        = false;
		$touch_swipe                              = get_post_meta( $shortcode_id, 'pc_touch_swipe', 'true' );
		$view_options['touch_swipe']              = ( 'on' == $touch_swipe ) ? true : false;
		$mouse_draggable                          = get_post_meta( $shortcode_id, 'pc_mouse_draggable', 'true' );
		$view_options['slider_draggable']         = ( 'on' == $mouse_draggable ) ? true : false;

		$view_options['pcp_page_link_type'] = 'single_page';
		$view_options['pcp_link_target']    = '_self';
		$view_options['pcp_link_rel']       = 'false';

		$view_options['post_title_typography']    = array(
			'color'              => $post_title_color,
			'hover_color'        => $post_title_hover_color,
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
		);
		$view_options['post_content_typography']  = array(
			'color'              => $post_content_color,
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
		);
		$view_options['post_meta_typography']     = array(
			'color'              => $post_meta_color,
			'hover_color'        => $post_meta_hover_color,
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
		);
		$view_options['section_title_typography'] = array(
			'color'              => $carousel_title_color,
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
		);

		/**
		 * Layouts.
		 */
		$layouts['pcp_layout_preset'] = 'carousel_layout';

		update_post_meta( $shortcode_id, 'sp_pcp_view_options', $view_options );
		update_post_meta( $shortcode_id, 'sp_pcp_layouts', $layouts );
	}

	/**
	 * Old post type to new for shortcode.
	 */
	global $wpdb;
	$old_post_types = array( 'sp_pc_shortcodes' => 'sp_post_carousel' );
	foreach ( $old_post_types as $old_type => $type ) {
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE {$wpdb->posts} SET post_type = REPLACE(post_type, %s, %s) 
                            WHERE post_type LIKE %s",
				$old_type,
				$type,
				$old_type
			)
		);
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE {$wpdb->posts} SET guid = REPLACE(guid, %s, %s) 
                            WHERE guid LIKE %s",
				"post_type={$old_type}",
				"post_type={$type}",
				"%post_type={$type}%"
			)
		);
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE {$wpdb->posts} SET guid = REPLACE(guid, %s, %s) 
                            WHERE guid LIKE %s",
				"/{$old_type}/",
				"/{$type}/",
				"%/{$old_type}/%"
			)
		);
	}
}
