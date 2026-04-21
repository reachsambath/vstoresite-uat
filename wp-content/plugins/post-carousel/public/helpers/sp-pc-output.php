<?php
/**
 * The file of html inside the loop.
 *
 * @package Smart_Post_Show
 * @subpackage public
 *
 * @since 2.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Post output query.
 *
 * @since 2.0.0
 */
class SP_PC_Output {
	/**
	 * Post responsive columns class.
	 *
	 * @param string $layout Layout preset.
	 * @param string $columns Columns number.
	 * @return string
	 */
	public static function pcp_post_responsive_columns( $layout, $columns ) {
		$pcp_post_columns = '';
		if ( 'carousel_layout' === $layout || 'slider_layout' === $layout ) {
			$pcp_post_columns .= ' swiper-slide swiper-lazy';
		} elseif ( 'list_layout' === $layout ) {
			$pcp_post_columns = 'sp-pcp-col-xs-1';
		} else {
			$pcp_post_columns .= " sp-pcp-col-xs-$columns[mobile] sp-pcp-col-sm-$columns[mobile_landscape] sp-pcp-col-md-$columns[tablet] sp-pcp-col-lg-$columns[desktop] sp-pcp-col-xl-$columns[lg_desktop]";
		}
		return $pcp_post_columns;
	}
	/**
	 * PCP shortcode markup wrapper classes.
	 *
	 * @param string $layout_preset The selected layout name.
	 * @param int    $shortcode_id The shortcode ID.
	 */
	public static function pcp_wrapper_classes( $layout_preset, $shortcode_id ) {
		$wrapper_class = "sp-pcp-section sp-pcp-container pcp-wrapper-{$shortcode_id}";
		switch ( $layout_preset ) {
			case 'carousel_layout':
			case 'slider_layout':
				$wrapper_class .= ' pcp-carousel-wrapper';
				break;
		}
		echo esc_attr( $wrapper_class );
	}
	/**
	 * Post Loop.
	 *
	 * @param array  $options Views options.
	 * @param string $layout Layout preset.
	 * @param array  $sorter Post sorting options.
	 * @param int    $scode_id Shortcode ID.
	 * @return void
	 */
	public static function pcp_post_loop( $options, $layout, $sorter, $scode_id ) {
		global $post;
		$number_of_columns = SP_PC_Functions::pcp_metabox_value( 'pcp_number_of_columns', $options );
		$item_class        = 'slider_layout' === $layout ? 'sp-overlay overlay-content-position-middle' : 'left-thumb';
		?>
		<div class="<?php echo esc_attr( self::pcp_post_responsive_columns( $layout, $number_of_columns ) ); ?>">
			<?php if ( 'slider_layout' === $layout || 'list_layout' === $layout ) { ?>
				<div class="sp-pcp-post <?php echo esc_attr( $item_class ); ?> pcp-item-<?php echo esc_attr( $post->ID ); ?>" data-id="<?php echo esc_attr( $post->ID ); ?>">
				<?php
					SP_PC_HTML::pcp_post_thumb_html( $sorter, $scode_id, $post, $options, $layout );
				?>
				<div class="sp-pcp-post-details">
					<div class="sp-pcp-post-details-content">
					<?php
					SP_PC_HTML::pcp_post_content_without_thumb( $sorter, $layout, $post, $options );
					?>
					</div>
				</div>
			</div>
			<?php } else { ?>
			<div class="sp-pcp-post pcp-item-<?php echo esc_attr( $post->ID ); ?>" data-id="<?php echo esc_attr( $post->ID ); ?>">
				<?php
					SP_PC_HTML::pcp_post_content_with_thumb( $sorter, $layout, $scode_id, $post, $options );
				?>
			</div>
			<?php } ?>
		</div>
		<?php
	}

	/**
	 * Get all query posts.
	 *
	 * @param array  $options Views options.
	 * @param array  $layout Layout preset.
	 * @param array  $sorter  Post sorting options.
	 * @param object $pcp_query post query.
	 * @param int    $view_id Shortcode ID.
	 * @return void
	 */
	public static function pcp_get_posts( $options, $layout, $sorter, $pcp_query, $view_id ) {
		$pcp_count = 1;
		while ( $pcp_query->have_posts() ) {
			$pcp_query->the_post();
			$without_post_thumbnail = apply_filters( 'sps_without_thumbnail_post_hide', true ) ? true : has_post_thumbnail();
			if ( $without_post_thumbnail ) {
				self::pcp_post_loop( $options, $layout, $sorter, $view_id );
			}
			$pcp_count++;
		}
		wp_reset_postdata();
	}

	/**
	 * All markup
	 *
	 * @param  mixed $view_options shortcode options.
	 * @param  mixed $layout all layout based options.
	 * @param  mixed $shortcode_id shortcode id.
	 * @param  mixed $section_title Section title.
	 * @return void
	 */
	public static function pc_html_show( $view_options, $layout, $shortcode_id, $section_title ) {
		$pcp_settings         = get_option( 'sp_post_carousel_settings' );
		$post_content_sorter  = isset( $view_options['post_content_sorter'] ) ? $view_options['post_content_sorter'] : '';
		$pcp_content_position = isset( $view_options['post_content_orientation'] ) ? $view_options['post_content_orientation'] : '';
		$margin_between_post  = isset( $view_options['margin_between_post']['left-right'] ) ? $view_options['margin_between_post'] : array(
			'top-bottom' => 20,
			'left-right' => 20,
		);
		$show_preloader       = isset( $view_options['show_preloader'] ) ? $view_options['show_preloader'] : 0;
		$query_args           = SP_PC_QueryInside::get_filtered_content( $view_options, $shortcode_id, $layout );
		$pcp_query            = new WP_Query( $query_args );
		$total_post_count     = $pcp_query->post_count;
		// Pagination.
		$show_pagination = isset( $view_options['show_post_pagination'] ) ? $view_options['show_post_pagination'] : '';
		$layout_preset   = isset( $layout['pcp_layout_preset'] ) ? $layout['pcp_layout_preset'] : '';
		wp_enqueue_script( 'pcp_script' );
		$pcp_custom_js = $pcp_settings['pcp_custom_js'];
		if ( ! empty( $pcp_custom_js ) ) {
			wp_add_inline_script( 'pcp_script', $pcp_custom_js );
		}

		if ( 'carousel_layout' === $layout_preset || 'slider_layout' === $layout_preset ) {
			require SP_PC_TEMPLATE_PATH . '/carousel.php';
		} elseif ( 'grid_layout' === $layout_preset ) {
			require SP_PC_TEMPLATE_PATH . '/grid.php';
		} elseif ( 'list_layout' === $layout_preset ) {
			require SP_PC_TEMPLATE_PATH . '/list.php';
		}
	}
}
