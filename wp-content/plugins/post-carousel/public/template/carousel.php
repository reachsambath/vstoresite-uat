<?php
/**
 *  Carousel view
 *
 * @package    Smart_Post_Show
 * @subpackage Smart_Post_Show/public/template
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$carousel_autoplay = ( isset( $view_options['pcp_autoplay'] ) && ( $view_options['pcp_autoplay'] ) ) ? 'true' : 'false';
$autoplay_speed    = isset( $view_options['pcp_autoplay_speed'] ) ? $view_options['pcp_autoplay_speed'] : '';
$carousel_speed    = isset( $view_options['pcp_carousel_speed'] ) ? $view_options['pcp_carousel_speed'] : '';
$pause_hover       = ( isset( $view_options['pcp_pause_hover'] ) && ( $view_options['pcp_pause_hover'] ) ) ? 'true' : 'false';

$infinite_loop        = ( isset( $view_options['pcp_infinite_loop'] ) && ( $view_options['pcp_infinite_loop'] ) ) ? 'true' : 'false';
$carousel_auto_height = ( isset( $view_options['pcp_adaptive_height'] ) && ( $view_options['pcp_adaptive_height'] ) ) ? 'true' : 'false';
$number_of_columns    = isset( $view_options['pcp_number_of_columns'] ) ? $view_options['pcp_number_of_columns'] : '';
$lazy_load            = ( isset( $view_options['pcp_lazy_load'] ) && ( $view_options['pcp_lazy_load'] ) ) ? 'true' : 'false';
// Direction.
$carousel_direction                   = ( isset( $view_options['pcp_carousel_direction'] ) ) ? $view_options['pcp_carousel_direction'] : '';
$is_carousel_accessibility            = ( isset( $pcp_settings['accessibility'] ) && ( $pcp_settings['accessibility'] ) ) ? 'true' : 'false';
$accessibility_prev_slide_text        = isset( $pcp_settings['prev_slide_message'] ) ? $pcp_settings['prev_slide_message'] : '';
$accessibility_next_slide_text        = isset( $pcp_settings['next_slide_message'] ) ? $pcp_settings['next_slide_message'] : '';
$accessibility_first_slide_text       = isset( $pcp_settings['first_slide_message'] ) ? $pcp_settings['first_slide_message'] : '';
$accessibility_last_slide_text        = isset( $pcp_settings['last_slide_message'] ) ? $pcp_settings['last_slide_message'] : '';
$accessibility_pagination_bullet_text = isset( $pcp_settings['pagination_bullet_message'] ) ? $pcp_settings['pagination_bullet_message'] : '';
if ( 'slider_layout' === $layout_preset ) {
	$number_of_columns = array(
		'lg_desktop'       => '1',
		'desktop'          => '1',
		'tablet'           => '1',
		'mobile_landscape' => '1',
		'mobile'           => '1',
	);
}
if ( $pcp_settings['pcp_swiper_js'] ) {
	wp_enqueue_script( 'pcp_swiper' );
}
// Navigation.
$_navigation_data      = isset( $view_options['pcp_navigation_data'] ) ? $view_options['pcp_navigation_data'] : array();
$_navigation           = isset( $_navigation_data['pcp_navigation'] ) ? $_navigation_data['pcp_navigation'] : true;
$_navigation_on_mobile = isset( $_navigation_data['nev_hide_on_mobile'] ) ? $_navigation_data['nev_hide_on_mobile'] : false;
if ( $_navigation ) {
	$navigation        = 'true';
	$navigation_mobile = 'true';
} elseif ( $_navigation && $_navigation_on_mobile ) {
	$navigation        = 'true';
	$navigation_mobile = 'false';
} else {
	$navigation        = 'false';
	$navigation_mobile = 'false';
}

// Pagination Settings.
$_pagination_data      = isset( $view_options['carousel_pagination_group'] ) ? $view_options['carousel_pagination_group'] : array();
$_pagination           = isset( $_pagination_data['pcp_pagination'] ) ? $_pagination_data['pcp_pagination'] : true;
$_pagination_on_mobile = isset( $_pagination_data['pagination_hide_on_mobile'] ) ? $_pagination_data['pagination_hide_on_mobile'] : false;
if ( $_pagination ) {
	$pagination        = 'true';
	$pagination_mobile = 'true';
} elseif ( $_pagination && $_pagination_on_mobile ) {
	$pagination        = 'true';
	$pagination_mobile = 'false';
} else {
	$pagination        = 'false';
	$pagination_mobile = 'false';
}

$pcp_accessibility  = ( isset( $view_options['pcp_accessibility'] ) && ( $view_options['pcp_accessibility'] ) ) ? 'true' : 'false';
$touch_swipe        = ( isset( $view_options['touch_swipe'] ) && ( $view_options['touch_swipe'] ) ) ? 'true' : 'false';
$slider_draggable   = ( isset( $view_options['slider_draggable'] ) && ( $view_options['slider_draggable'] ) ) ? 'true' : 'false';
$free_mode          = ( isset( $view_options['free_mode'] ) && $view_options['free_mode'] ) ? 'true' : 'false';
$slider_mouse_wheel = ( isset( $view_options['slider_mouse_wheel'] ) && ( $view_options['slider_mouse_wheel'] ) ) ? 'true' : 'false';


?>
<!-- Markup Starts -->
<div id="pcp_wrapper-<?php echo esc_html( $shortcode_id ); ?>" class="<?php self::pcp_wrapper_classes( $layout_preset, $shortcode_id ); ?> standard sp-<?php echo esc_attr( $layout_preset ); ?>" data-sid="<?php echo esc_html( $shortcode_id ); ?>">
<?php if ( $show_preloader ) { ?>
<div id="pcp-preloader" class="pcp-preloader"></div>
	<?php
}
if ( $view_options['section_title'] ) {
	do_action( 'pcp_before_section_title' );
	SP_PC_HTML::pcp_section_title( $section_title );
	do_action( 'pcp_after_section_title' );
}
?>
	<div id="sp-pcp-id-<?php echo esc_html( $shortcode_id ); ?>" class="swiper-container sp-pcp-carousel top_right" dir="<?php echo esc_html( $carousel_direction ); ?>" data-carousel='{ "speed":<?php echo esc_html( $carousel_speed ); ?>, "items":<?php echo esc_html( $number_of_columns['lg_desktop'] ); ?>, "spaceBetween":<?php echo esc_html( $margin_between_post['left-right'] ); ?>, "navigation":<?php echo esc_html( $navigation ); ?>, "pagination": <?php echo esc_html( $pagination ); ?>, "autoplay": <?php echo esc_html( $carousel_autoplay ); ?>, "autoplay_speed": <?php echo esc_html( $autoplay_speed ); ?>, "loop": <?php echo esc_html( $infinite_loop ); ?>, "autoHeight": <?php echo esc_html( $carousel_auto_height ); ?>, "lazy":  <?php echo esc_html( $lazy_load ); ?>, "simulateTouch": <?php echo esc_html( $slider_draggable ); ?>, "freeMode": <?php echo esc_html( $free_mode ); ?>, "slider_mouse_wheel": <?php echo esc_html( $slider_mouse_wheel ); ?>,"allowTouchMove": <?php echo esc_html( $touch_swipe ); ?>, "slidesPerView": {"lg_desktop": <?php echo esc_html( $number_of_columns['lg_desktop'] ); ?>, "desktop": <?php echo esc_html( $number_of_columns['desktop'] ); ?>, "tablet": <?php echo esc_html( $number_of_columns['tablet'] ); ?>, "mobile_landscape": <?php echo esc_html( $number_of_columns['mobile_landscape'] ); ?>, "mobile": <?php echo esc_html( $number_of_columns['mobile'] ); ?>}, "navigation_mobile": <?php echo esc_html( $navigation_mobile ); ?>, "pagination_mobile": <?php echo esc_html( $pagination_mobile ); ?>, "stop_onHover": <?php echo esc_html( $pause_hover ); ?>, "enabled": <?php echo esc_html( $is_carousel_accessibility ); ?>, "prevSlideMessage": "<?php echo esc_html( $accessibility_prev_slide_text ); ?>", "nextSlideMessage": "<?php echo esc_html( $accessibility_next_slide_text ); ?>", "firstSlideMessage": "<?php echo esc_html( $accessibility_first_slide_text ); ?>", "lastSlideMessage": "<?php echo esc_html( $accessibility_last_slide_text ); ?>", "keyboard": "<?php echo esc_html( $pcp_accessibility ); ?>", "paginationBulletMessage": "<?php echo esc_html( $accessibility_pagination_bullet_text ); ?>" }'>
			<div class="swiper-wrapper">
				<?php self::pcp_get_posts( $view_options, $layout_preset, $post_content_sorter, $pcp_query, $shortcode_id ); ?>
			</div>
			<?php
			if ( 'true' === $pagination ) {
				?>
			<div class="pcp-pagination swiper-pagination dots"></div>
			<?php } ?>
			<?php if ( 'true' === $navigation ) { ?>
				<div class="pcp-button-next swiper-button-next top_right"><i class="fa fa-angle-right"></i></div>
				<div class="pcp-button-prev swiper-button-prev top_right"><i class="fa fa-angle-left"></i></div><?php } ?>
	</div>
</div>
