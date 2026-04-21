<?php
update_option( 'smart_post_show_version', '3.0.0' );
update_option( 'smart_post_show_db_version', '3.0.0' );


$args = new WP_Query(
	array(
		'post_type'      => 'sp_post_carousel',
		'post_status'    => 'any',
		'posts_per_page' => '600',
	)
);

$shortcode_ids = wp_list_pluck( $args->posts, 'ID' );
if ( count( $shortcode_ids ) > 0 ) {
	foreach ( $shortcode_ids as $shortcode_key => $shortcode_id ) {
		$view_options = get_post_meta( $shortcode_id, 'sp_pcp_view_options', true );
		if ( ! is_array( $view_options ) ) {
			continue;
		}

		$old_margin_between_post = isset( $view_options['margin_between_post']['all'] ) ? $view_options['margin_between_post']['all'] : 20;

		$view_options['margin_between_post'] = array(
			'top-bottom' => $old_margin_between_post,
			'left-right' => $old_margin_between_post,
		);

		if ( isset( $view_options['post_border']['all'] ) ) {
			$view_options['post_border']['border_radius'] = isset( $view_options['post_border_radius_property']['all'] ) ? $view_options['post_border_radius_property']['all'] : '0';
			$view_options['post_border']['unit']          = isset( $view_options['post_border_radius_property']['unit'] ) ? $view_options['post_border_radius_property']['unit'] : 'px';
		}

		// Read more button border field update.
		$read_more_color = isset( $view_options['post_content_sorter']['pcp_post_content']['readmore_color_button'] ) ? $view_options['post_content_sorter']['pcp_post_content']['readmore_color_button'] : array();
		$view_options['post_content_sorter']['pcp_post_content']['read_more_btn_border'] = array(
			'all'           => '1',
			'style'         => 'solid',
			'color'         => $read_more_color['border'],
			'hover_color'   => $read_more_color['hover_border'],
			'border_radius' => '0',
			'unit'          => 'px',
		);


		// Carousel field updates.
		$carousel_nav          = isset( $view_options['pcp_navigation'] ) ? $view_options['pcp_navigation'] : '';
		$carousel_nav_position = isset( $view_options['pcp_carousel_nav_position'] ) ? $view_options['pcp_carousel_nav_position'] : '';
		$carousel_nav_color    = isset( $view_options['pcp_nav_colors'] ) ? $view_options['pcp_nav_colors'] : '';

		if ( 'show' === $carousel_nav ) {
			$view_options['pcp_navigation_data']['pcp_navigation']     = true;
			$view_options['pcp_navigation_data']['nev_hide_on_mobile'] = false;
		} elseif ( 'hide_on_mobile' === $carousel_nav ) {
			$view_options['pcp_navigation_data']['pcp_navigation']     = true;
			$view_options['pcp_navigation_data']['nev_hide_on_mobile'] = true;
		} else {
			$view_options['pcp_navigation_data']['pcp_navigation']     = false;
			$view_options['pcp_navigation_data']['nev_hide_on_mobile'] = false;
		}
		$view_options['pcp_nav_border'] = array(
			'all'           => '1',
			'style'         => 'solid',
			'color'         => $carousel_nav_color['border-color'],
			'hover_color'   => $carousel_nav_color['hover-border-color'],
			'border_radius' => '3',
			'unit'          => 'px',
		);

		// pagination.
		$carousel_pagination         = isset( $view_options['pcp_pagination'] ) ? $view_options['pcp_pagination'] : '';
		$carousel_dynamic_pagination = isset( $view_options['pcp_dynamicBullets'] ) ? $view_options['pcp_dynamicBullets'] : '';
		if ( 'show' === $carousel_pagination ) {
			$view_options['carousel_pagination_group']['pcp_pagination']     = true;
			$view_options['carousel_pagination_group']['nev_hide_on_mobile'] = false;
		} elseif ( 'hide_on_mobile' === $carousel_pagination ) {
			$view_options['carousel_pagination_group']['pcp_pagination']     = true;
			$view_options['carousel_pagination_group']['nev_hide_on_mobile'] = true;
		} else {
			$view_options['carousel_pagination_group']['pcp_pagination']     = false;
			$view_options['carousel_pagination_group']['nev_hide_on_mobile'] = false;
		}

		$post_pagination_type = isset( $view_options['post_pagination_type'] ) ? $view_options['post_pagination_type'] : '';

		if ( 'no_ajax' === $post_pagination_type ) {
			$view_options['pcp_pagination_btn_border']['color']       = $view_options['pcp_pagination_btn_color']['border_color'];
			$view_options['pcp_pagination_btn_border']['hover_color'] = $view_options['pcp_pagination_btn_color']['border_acolor'];
		}

		update_post_meta( $shortcode_id, 'sp_pcp_view_options', $view_options );
	}
}

