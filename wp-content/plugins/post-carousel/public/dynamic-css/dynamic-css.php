<?php
/**
 *  Dynamic CSS
 *
 * @package    Smart_Post_Show
 * @subpackage Smart_Post_Show/public
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$layout_preset = isset( $layout['pcp_layout_preset'] ) ? $layout['pcp_layout_preset'] : '';
$section_title = isset( $view_options['section_title'] ) ? $view_options['section_title'] : false;

// Section Title.
if ( $section_title ) {
	$section_title_margin        = isset( $view_options['section_title_margin'] ) && is_array( $view_options['section_title_margin'] ) ? $view_options['section_title_margin'] : array(
		'top'    => '0',
		'right'  => '0',
		'bottom' => '30',
		'left'   => '0',
	);
	$section_title_margin_top    = (int) $section_title_margin ['top'] > 0 ? $section_title_margin ['top'] . 'px' : 0;
	$section_title_margin_right  = (int) $section_title_margin['right'] > 0 ? $section_title_margin['right'] . 'px' : 0;
	$section_title_margin_bottom = (int) $section_title_margin['bottom'] > -50 ? (int) $section_title_margin['bottom'] . 'px' : 0;
	$section_title_margin_left   = (int) $section_title_margin['left'] > 0 ? $section_title_margin['left'] . 'px' : 0;
	$_section_title_color        = isset( $view_options['section_title_typography']['color'] ) ? $view_options['section_title_typography']['color'] : '#111';
	$custom_css                 .= "#poststuff #sp_pcp_display .sp-pcp-section .pcp-section-title, #pcp_wrapper-{$pcp_id} .pcp-section-title{color: {$_section_title_color };margin: {$section_title_margin_top} {$section_title_margin_right} {$section_title_margin_bottom} {$section_title_margin_left}}";
}
$margin_between_horizontal_post = isset( $view_options['margin_between_post']['left-right'] ) ? (int) $view_options['margin_between_post']['left-right'] : 20;
$margin_between_vertical_post   = isset( $view_options['margin_between_post']['top-bottom'] ) ? (int) $view_options['margin_between_post']['top-bottom'] : 20;
$margin_between_post_half       = $margin_between_horizontal_post / 2;
$custom_css                    .= "#pcp_wrapper-{$pcp_id}:not(.sps-glossary-layout) .sp-pcp-row,#pcp_wrapper-{$pcp_id} .sps-glossary-items-group .sps-glossary-items-content {margin-right: -{$margin_between_post_half}px;margin-left: -{$margin_between_post_half}px;}#pcp_wrapper-{$pcp_id} .sp-pcp-row [class*='sp-pcp-col-']{padding-right: {$margin_between_post_half}px;padding-left: {$margin_between_post_half}px; padding-bottom:{$margin_between_vertical_post}px;}";

/**
 * Style for each slide/post.
 */
$post_sorter = isset( $view_options['post_content_sorter'] ) ? $view_options['post_content_sorter'] : '';

// Post Title.
if ( isset( $post_sorter['pcp_post_title']['show_post_title'] ) && $post_sorter['pcp_post_title']['show_post_title'] ) {
	$_post_title_typography = isset( $view_options['post_title_typography'] ) && is_array( $view_options['post_title_typography'] ) ? $view_options['post_title_typography'] : array(
		'color'       => '#111',
		'hover_color' => '#e1624b',
	);
	$custom_css            .= ".pcp-wrapper-{$pcp_id} .sp-pcp-title a {color: {$_post_title_typography['color']};display: inherit;} .pcp-wrapper-{$pcp_id} .sp-pcp-title a:hover {color: {$_post_title_typography['hover_color']};}";
}

// Post Content.
$pcp_post_content  = isset( $post_sorter['pcp_post_content'] ) ? $post_sorter['pcp_post_content'] : '';
$show_post_content = isset( $pcp_post_content['show_post_content'] ) ? $pcp_post_content['show_post_content'] : '';
if ( $show_post_content ) {
	$_post_content_color = isset( $view_options['post_content_typography']['color'] ) ? $view_options['post_content_typography']['color'] : '#444';
	$custom_css         .= ".pcp-wrapper-{$pcp_id} .sp-pcp-post-content{color: {$_post_content_color}; }";
}

if ( 'carousel_layout' === $layout_preset || 'slider_layout' === $layout_preset ) {
	include SP_PC_PATH . '/public/dynamic-css/carousel-css.php';
}

// Post inner padding.
$post_content_orientation = isset( $view_options['post_content_orientation'] ) ? $view_options['post_content_orientation'] : 'default';
if ( 'overlay' !== $post_content_orientation ) {
	$_post_inner_padding       = SP_PC_Functions::pcp_metabox_value( 'post_inner_padding_property', $view_options );
	$post_inner_padding_unit   = $_post_inner_padding['unit'];
	$post_inner_padding_top    = $_post_inner_padding['top'] > 0 ? $_post_inner_padding['top'] . $post_inner_padding_unit : '0';
	$post_inner_padding_right  = $_post_inner_padding['right'] > 0 ? $_post_inner_padding['right'] . $post_inner_padding_unit : '0';
	$post_inner_padding_bottom = $_post_inner_padding['bottom'] > 0 ? $_post_inner_padding['bottom'] . $post_inner_padding_unit : '0';
	$post_inner_padding_left   = $_post_inner_padding['left'] > 0 ? $_post_inner_padding['left'] . $post_inner_padding_unit : '0';
	$custom_css               .= "#pcp_wrapper-{$pcp_id} .sp-pcp-post {padding: {$post_inner_padding_top} {$post_inner_padding_right} {$post_inner_padding_bottom} {$post_inner_padding_left};}#pcp_wrapper-{$pcp_id}.sp-slider_layout .sp-pcp-post .sp-pcp-post-details {top: {$post_inner_padding_top}; right:{$post_inner_padding_right}; bottom:{$post_inner_padding_bottom}; left:{$post_inner_padding_left};}";
}

// Post border.
$_post_border       = $view_options['post_border'];
$post_border_width  = isset( $_post_border['all'] ) ? (int) $_post_border['all'] : '0';
$post_border_style  = $_post_border['style'];
$post_border_color  = $_post_border['color'];
$post_border_radius = isset( $_post_border['border_radius'] ) ? $_post_border['border_radius'] . $_post_border['unit'] : 0;
if ( isset( $post_border_width ) ) {
	$custom_css .= "#pcp_wrapper-{$pcp_id} .sp-pcp-post {border: {$post_border_width}px {$post_border_style} {$post_border_color}; border-radius: {$post_border_radius} }";
}

// Post background color.
$post_background_property = SP_PC_Functions::pcp_metabox_value( 'post_background_property', $view_options );
if ( ! in_array( $post_content_orientation, array( 'overlay', 'overlay-box' ), true ) ) {
	$custom_css .= "#pcp_wrapper-{$pcp_id} .sp-pcp-post{background-color: {$post_background_property};}";
}

	/**
	 * Post Thumbnail CSS.
	 */
	$post_thumb_css = $post_sorter['pcp_post_thumb'];

	// Border for Post thumb.
	$post_thumb_border = isset( $post_thumb_css['pcp_thumb_border'] ) ? $post_thumb_css['pcp_thumb_border'] : array(
		'all'           => '0',
		'style'         => 'solid',
		'color'         => '#dddddd',
		'border_radius' => '0',
		'unit'          => 'px',
	);
	if ( isset( $post_thumb_border['all'] ) ) {
		$custom_css .= "#pcp_wrapper-{$pcp_id} .pcp-post-thumb-wrapper{border: {$post_thumb_border['all']}px {$post_thumb_border['style']} {$post_thumb_border['color']};border-radius:{$post_thumb_border['border_radius']}{$post_thumb_border['unit']};}";
	}

	// Post Meta.
	$_post_meta_color       = isset( $view_options['post_meta_typography']['color'] ) ? $view_options['post_meta_typography']['color'] : '#888';
	$_post_meta_hover_color = isset( $view_options['post_meta_typography']['hover_color'] ) ? $view_options['post_meta_typography']['hover_color'] : '#e1624b';
	$custom_css            .= ".pcp-wrapper-{$pcp_id} .sp-pcp-post-meta li,.pcp-wrapper-{$pcp_id} .sp-pcp-post-meta ul,.pcp-wrapper-{$pcp_id} .sp-pcp-post-meta li a{color: {$_post_meta_color};}";
	$custom_css            .= ".pcp-wrapper-{$pcp_id} .sp-pcp-post-meta li a:hover{color: {$_post_meta_hover_color};}";

	// Post ReadMore Settings.
	$post_content_settings = isset( $post_sorter['pcp_post_content'] ) ? $post_sorter['pcp_post_content'] : '';
	$show_read_more        = isset( $post_content_settings['show_read_more'] ) ? $post_content_settings['show_read_more'] : '';
	if ( $show_read_more ) {
		$_button_color        = $post_content_settings['readmore_color_button'];
		$read_more_btn_border = isset( $post_content_settings['read_more_btn_border'] ) ? $post_content_settings['read_more_btn_border'] : array(
			'all'           => '1',
			'style'         => 'solid',
			'color'         => '#888',
			'hover_color'   => '#e1624b',
			'border_radius' => '0',
			'unit'          => 'px',
		);
		$custom_css          .= "#pcp_wrapper-{$pcp_id} .pcp-readmore-link{ background: {$_button_color['bg']}; color: {$_button_color['standard']}; border: {$read_more_btn_border['all']}px {$read_more_btn_border['style']} {$read_more_btn_border['color']}; border-radius: {$read_more_btn_border['border_radius']}{$read_more_btn_border['unit']}; } #pcp_wrapper-{$pcp_id} .pcp-readmore-link:hover { background-color: {$_button_color['hover_bg']}; color: {$_button_color['hover']}; border-color: {$read_more_btn_border['hover_color']}; }";
	}

	// Pagination CSS and Live filter CSS.
	$show_pagination = isset( $view_options['show_post_pagination'] ) ? $view_options['show_post_pagination'] : '';
	if ( $show_pagination ) {
		$pagination_btn_color = $view_options['pcp_pagination_btn_color'];
		$pagination_border    = isset( $view_options['pcp_pagination_btn_border']['all'] ) ? $view_options['pcp_pagination_btn_border'] : array(
			'all'           => '2',
			'style'         => 'solid',
			'color'         => '#bbbbbb',
			'hover_color'   => '#e1624b',
			'border_radius' => '2',
			'unit'          => 'px',
		);
		$pagination_alignment = isset( $view_options['pagination_alignment'] ) ? $view_options['pagination_alignment'] : 'left';
		$custom_css          .= "#pcp_wrapper-{$pcp_id} .pcp-post-pagination .page-numbers.current, #pcp_wrapper-{$pcp_id} .pcp-post-pagination a.active , #pcp_wrapper-{$pcp_id} .pcp-post-pagination a:hover{ color: {$pagination_btn_color['text_acolor']}; background: {$pagination_btn_color['active_background']}; border-color: {$pagination_border ['hover_color']}; }#pcp_wrapper-{$pcp_id} .pcp-post-pagination .page-numbers, .pcp-post-pagination a{ background: {$pagination_btn_color['background']}; color:{$pagination_btn_color['text_color']}; border: {$pagination_border['all']}px {$pagination_border['style']} {$pagination_border['color']};border-radius: {$pagination_border['border_radius']}{$pagination_border['unit']}; }#pcp_wrapper-{$pcp_id} .pcp-post-pagination{text-align: {$pagination_alignment};}";
	}

	$custom_css .= '@media (min-width: 1200px) {
	.sp-pcp-row .sp-pcp-col-xl-1 {
		flex: 0 0 100%;
	}
	.sp-pcp-row .sp-pcp-col-xl-2 {
		flex: 1 1 calc( 50% - ' . (int) $margin_between_horizontal_post . 'px);
	}
	.sp-pcp-row .sp-pcp-col-xl-3 {
		flex: 1 1 calc( 33.333% - ' . (int) $margin_between_horizontal_post . 'px);
	}
	.sp-pcp-row .sp-pcp-col-xl-4 {
		flex: 1 1 calc( 25% - ' . (int) $margin_between_horizontal_post . 'px);
	}
	.sp-pcp-row .sp-pcp-col-xl-5 {
	    flex: 1 1 calc( 20% - ' . (int) $margin_between_horizontal_post . 'px);
	}
	.sp-pcp-row .sp-pcp-col-xl-6 {
		flex: 1 1 calc( 16.66666666666667% - ' . (int) $margin_between_horizontal_post . 'px);
	}
	.sp-pcp-row .sp-pcp-col-xl-7 {
		flex: 1 1 calc( 14.28571428% - ' . (int) $margin_between_horizontal_post . 'px);
	}
	.sp-pcp-row .sp-pcp-col-xl-8 {
		flex: 1 1 calc( 12.5% - ' . (int) $margin_between_horizontal_post . 'px);
	}
}

@media (max-width: 1200px) {
	.sp-pcp-row .sp-pcp-col-lg-1 {
		flex: 0 0 100%;
	}
	.sp-pcp-row .sp-pcp-col-lg-2 {
		flex: 1 1 calc( 50% - ' . (int) $margin_between_horizontal_post . 'px);
	}
	.sp-pcp-row .sp-pcp-col-lg-3 {
		flex: 1 1 calc( 33.333% - ' . (int) $margin_between_horizontal_post . 'px);
	}
	.sp-pcp-row .sp-pcp-col-lg-4 {
		flex: 1 1 calc( 25% - ' . (int) $margin_between_horizontal_post . 'px);
	}
	.sp-pcp-row .sp-pcp-col-lg-5 {
	    flex: 1 1 calc( 20% - ' . (int) $margin_between_horizontal_post . 'px);
	}
	.sp-pcp-row .sp-pcp-col-lg-6 {
		flex: 1 1 calc( 16.66666666666667% - ' . (int) $margin_between_horizontal_post . 'px);
	}
	.sp-pcp-row .sp-pcp-col-lg-7 {
		flex: 1 1 calc( 14.28571428% - ' . (int) $margin_between_horizontal_post . 'px);
	}
	.sp-pcp-row .sp-pcp-col-lg-8 {
		flex: 1 1 calc( 12.5% - ' . (int) $margin_between_horizontal_post . 'px);
	}
}

@media (max-width: 992px) {
	.sp-pcp-row .sp-pcp-col-md-1 {
		flex: 0 0 100%;
	}
	.sp-pcp-row .sp-pcp-col-md-2 {
		flex: 1 1 calc( 50% - ' . (int) $margin_between_horizontal_post . 'px);
	}
	.sp-pcp-row .sp-pcp-col-md-2-5 {
		flex: 0 0 75%;
	}
	.sp-pcp-row .sp-pcp-col-md-3 {
		flex: 1 1 calc( 33.333% - ' . (int) $margin_between_horizontal_post . 'px);
	}
	.sp-pcp-row .sp-pcp-col-md-4 {
		flex: 1 1 calc( 25% - ' . (int) $margin_between_horizontal_post . 'px);
	}
	.sp-pcp-row .sp-pcp-col-md-5 {
	    flex: 1 1 calc( 20% - ' . (int) $margin_between_horizontal_post . 'px);
	}
	.sp-pcp-row .sp-pcp-col-md-6 {
		flex: 1 1 calc( 16.66666666666667% - ' . (int) $margin_between_horizontal_post . 'px);
	}
	.sp-pcp-row .sp-pcp-col-md-7 {
		flex: 1 1 calc( 14.28571428% - ' . (int) $margin_between_horizontal_post . 'px);
	}
	.sp-pcp-row .sp-pcp-col-md-8 {
		flex: 1 1 calc( 12.5% - ' . (int) $margin_between_horizontal_post . 'px);
	}
}

@media (max-width: 768px) {
	.sp-pcp-row .sp-pcp-col-sm-1 {
		flex: 0 0 100%;
	}
	.sp-pcp-row .sp-pcp-col-sm-2 {
		flex: 1 1 calc( 50% - ' . (int) $margin_between_horizontal_post . 'px);
	}
	.sp-pcp-row .sp-pcp-col-sm-2-5 {
		flex: 0 0 75%;
	}
	.sp-pcp-row .sp-pcp-col-sm-3 {
		flex: 1 1 calc( 33.333% - ' . (int) $margin_between_horizontal_post . 'px);
	}
	.sp-pcp-row .sp-pcp-col-sm-4 {
		flex: 1 1 calc( 25% - ' . (int) $margin_between_horizontal_post . 'px);
	}
	.sp-pcp-row .sp-pcp-col-sm-5 {
	    flex: 1 1 calc( 20% - ' . (int) $margin_between_horizontal_post . 'px);
	}
	.sp-pcp-row .sp-pcp-col-sm-6 {
		flex: 1 1 calc( 16.66666666666667% - ' . (int) $margin_between_horizontal_post . 'px);
	}
	.sp-pcp-row .sp-pcp-col-sm-7 {
		flex: 1 1 calc( 14.28571428% - ' . (int) $margin_between_horizontal_post . 'px);
	}
	.sp-pcp-row .sp-pcp-col-sm-8 {
		flex: 1 1 calc( 12.5% - ' . (int) $margin_between_horizontal_post . 'px);
	}
}

@media (max-width: 420px) {
	.sp-pcp-row .sp-pcp-col-xs-1 {
		flex: 0 0 100%;
	}
	.sp-pcp-row .sp-pcp-col-xs-2 {
		flex: 1 1 calc( 50% - ' . (int) $margin_between_horizontal_post . 'px);
	}
	.sp-pcp-row .sp-pcp-col-xs-3 {
		flex: 1 1 calc( 33.333% - ' . (int) $margin_between_horizontal_post . 'px);
	}
	.sp-pcp-row .sp-pcp-col-xs-4 {
		flex: 1 1 calc( 25% - ' . (int) $margin_between_horizontal_post . 'px);
	}
	.sp-pcp-row .sp-pcp-col-xs-5 {
	    flex: 1 1 calc( 20% - ' . (int) $margin_between_horizontal_post . 'px);
	}
	.sp-pcp-row .sp-pcp-col-xs-6 {
		flex: 1 1 calc( 16.66666666666667% - ' . (int) $margin_between_horizontal_post . 'px);
	}
	.sp-pcp-row .sp-pcp-col-xs-7 {
		flex: 1 1 calc( 14.28571428% - ' . (int) $margin_between_horizontal_post . 'px);
	}
	.sp-pcp-row .sp-pcp-col-xs-8 {
		flex: 1 1 calc( 12.5% - ' . (int) $margin_between_horizontal_post . 'px);
	}
}';
