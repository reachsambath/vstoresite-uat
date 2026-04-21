<?php
/**
 * The dynamic CSS for Carousel layout.
 *
 * @package Smart_Post_Show
 */

$carousel_nav_position = SP_PC_Functions::pcp_metabox_value( 'pcp_carousel_nav_position', $view_options );
if ( 'vertically_center_outer' === $carousel_nav_position ) {
	$custom_css .= '.pcp-wrapper-{$pcp_id} .swiper-container{ position: static; }';
}

// Pagination options.
$_pagination_data        = isset( $view_options['carousel_pagination_group'] ) ? $view_options['carousel_pagination_group'] : array();
$_pagination             = isset( $_pagination_data['pcp_pagination'] ) ? $_pagination_data['pcp_pagination'] : true;
$_pagination_on_mobile   = isset( $_pagination_data['pagination_hide_on_mobile'] ) ? $_pagination_data['pagination_hide_on_mobile'] : false;
$_pagination_color_set   = $view_options['pcp_pagination_color_set'];
$_pagination_colors      = $_pagination_color_set['pcp_pagination_color'];
$pagination_color        = $_pagination_colors['color'];
$pagination_color_active = $_pagination_colors['active-color'];
if ( $_pagination_on_mobile ) {
	$custom_css .= "@media (max-width: 480px) { #pcp_wrapper-{$pcp_id} .pcp-pagination{ display: none; } }";
} $custom_css .= "#pcp_wrapper-{$pcp_id} .dots .swiper-pagination-bullet{ background: {$pagination_color}; } #pcp_wrapper-{$pcp_id} .dots .swiper-pagination-bullet-active { background: {$pagination_color_active}; }";

$carousel_nav_position = SP_PC_Functions::pcp_metabox_value( 'pcp_carousel_nav_position', $view_options );
if ( 'vertically_center_outer' === $carousel_nav_position ) {
	$custom_css .= '.pcp-wrapper-{$pcp_id} .swiper-container{ position: static; }';
}
// Navigation options.
$_nav_colors        = SP_PC_Functions::pcp_metabox_value( 'pcp_nav_colors', $view_options );
$nav_color          = SP_PC_Functions::pcp_metabox_value( 'color', $_nav_colors );
$nav_color_hover    = SP_PC_Functions::pcp_metabox_value( 'hover-color', $_nav_colors );
$nav_color_bg       = SP_PC_Functions::pcp_metabox_value( 'bg', $_nav_colors );
$nav_color_bg_hover = SP_PC_Functions::pcp_metabox_value( 'hover-bg', $_nav_colors );
$_nav_border        = SP_PC_Functions::pcp_metabox_value(
	'pcp_nav_border',
	$view_options,
	array(
		'all'           => '1',
		'style'         => 'solid',
		'color'         => '#aaa',
		'hover_color'   => '#e1624b',
		'border_radius' => '0',
		'unit'          => 'px',
	)
);
// Navigation options.
$_navigation_data      = isset( $view_options['pcp_navigation_data'] ) ? $view_options['pcp_navigation_data'] : array();
$_navigation           = isset( $_navigation_data['pcp_navigation'] ) ? $_navigation_data['pcp_navigation'] : true;
$_navigation_on_mobile = isset( $_navigation_data['nev_hide_on_mobile'] ) ? $_navigation_data['nev_hide_on_mobile'] : false;

if ( $_navigation_on_mobile ) {
	$custom_css .= "@media (max-width: 480px) { #pcp_wrapper-{$pcp_id} .pcp-button-prev, #pcp_wrapper-{$pcp_id} .pcp-button-next{ display: none; } }";
}
$custom_css .= "#pcp_wrapper-{$pcp_id} .pcp-button-prev,
#pcp_wrapper-{$pcp_id} .pcp-button-next{ background-image: none; background-size: auto; background-color: {$nav_color_bg}; height: 33px; width: 33px; margin-top: 8px; border: {$_nav_border['all']}px {$_nav_border['style']} {$_nav_border['color']}; text-align: center; line-height: 30px; -webkit-transition: 0.3s; border-radius: {$_nav_border['border_radius']}{$_nav_border['unit']}; }";
$custom_css .= "#pcp_wrapper-{$pcp_id} .pcp-button-prev:hover, #pcp_wrapper-{$pcp_id} .pcp-button-next:hover{ background-color: {$nav_color_bg_hover}; border-color: {$_nav_border['hover_color']}; } #pcp_wrapper-{$pcp_id} .pcp-button-prev .fa, #pcp_wrapper-{$pcp_id} .pcp-button-next .fa { color: {$nav_color}; } #pcp_wrapper-{$pcp_id} .pcp-button-prev:hover .fa, #pcp_wrapper-{$pcp_id} .pcp-button-next:hover .fa { color: {$nav_color_hover}; } #pcp_wrapper-{$pcp_id}.pcp-carousel-wrapper .sp-pcp-post{ margin-top: 0; }";
