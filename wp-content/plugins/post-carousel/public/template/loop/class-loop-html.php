<?php
/**
 * The file of query insides.
 *
 * @package Smart_Post_Show
 * @subpackage Smart_Post_Show/public
 *
 * @since 2.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Post all html method.
 *
 * @since 2.0.0
 */
class SP_PC_HTML {

	/**
	 * Section title
	 *
	 * @param int $title shortcode title.
	 * @return void
	 */
	public static function pcp_section_title( $title ) {
		$section_title_text = apply_filters( 'pcp_section_title_text', $title );
		$section_title      = apply_filters( 'pcp_filter_section_title', sprintf( '<h2 class="pcp-section-title">%1$s</h2>', $section_title_text ), $section_title_text );
		echo wp_kses_post( $section_title );
	}

	/**
	 * Post title html.
	 *
	 * @param array  $sorter post content option array.
	 * @param string $layout layout preset.
	 * @param array  $options The options array.
	 * @return void
	 */
	public static function pcp_post_title( $sorter, $layout, $options ) {

		$_meta_settings     = SP_PC_Functions::pcp_metabox_value( 'pcp_post_meta', $sorter );
		$post_meta_fields   = SP_PC_Functions::pcp_metabox_value( 'pcp_post_meta_group', $_meta_settings );
		$show_post_meta     = SP_PC_Functions::pcp_metabox_value( 'show_post_meta', $_meta_settings, true );
		$pcp_page_link_type = SP_PC_Functions::pcp_metabox_value( 'pcp_page_link_type', $options );
		$pcp_link_rel       = SP_PC_Functions::pcp_metabox_value( 'pcp_link_rel', $options );
		$pcp_link_rel_text  = $pcp_link_rel ? 'rel=nofollow' : '';
		$pcp_link_target    = SP_PC_Functions::pcp_metabox_value( 'pcp_link_target', $options );
		if ( is_array( $post_meta_fields ) && 'accordion_layout' !== $layout && $show_post_meta ) {
			foreach ( $post_meta_fields as $each_meta ) {
				if ( 'taxonomy' === $each_meta['select_post_meta'] ) {
					$taxonomy      = $each_meta['post_meta_taxonomy'];
					$meta_position = $each_meta['pcp_meta_position'];
					if ( 'above_title' === $meta_position ) {
						$terms = get_the_term_list( get_the_ID(), $taxonomy, '', ' ' );
						if ( $terms ) {
							?>
						<div class="pcp-category above_title <?php echo esc_attr( $taxonomy ); ?>">
							<?php echo wp_kses_post( $terms ); ?>
						</div>
							<?php
						}
					}
				}
			}
		}

		$post_title_setting = isset( $sorter['pcp_post_title'] ) ? $sorter['pcp_post_title'] : '';
		$show_post_title    = SP_PC_Functions::pcp_metabox_value( 'show_post_title', $post_title_setting );

		$pcp_post_title = get_the_title( get_the_ID() );

		if ( $show_post_title && ! empty( $pcp_post_title ) ) {
			// Post Title Settings.
			$post_title_tag    = SP_PC_Functions::pcp_metabox_value( 'post_title_tag', $post_title_setting, 'h2' );
			$allowed_html_tags = array(
				'em'     => array(),
				'strong' => array(),
				'sup'    => array(),
				'sub'    => array(),
				'i'      => array(),
				'small'  => array(),
				'del'    => array(),
				'br'     => array(),
				'ins'    => array(),
				'span'   => array(
					'style' => array(),
					'class' => array(),
				),
			);
			?>
			<<?php echo tag_escape( $post_title_tag ); ?> class="sp-pcp-title">
				<?php if ( 'none' === $pcp_page_link_type ) { ?>
					<?php echo sprintf( '<a %2$s>%1$s</a>', wp_kses( trim( $pcp_post_title ), $allowed_html_tags ), esc_attr( $pcp_link_rel_text ) ); ?>
			<?php } else { ?>
					<?php echo sprintf( '<a href="%1$s" %3$s target="%4$s">%2$s</a>', esc_url( get_the_permalink() ), wp_kses( trim( $pcp_post_title ), $allowed_html_tags ), esc_attr( $pcp_link_rel_text ), esc_attr( $pcp_link_target ) ); ?>
			<?php } ?>
				</<?php echo tag_escape( $post_title_tag ); ?>>
				<?php
		}
	}

	/**
	 * Show Post Content html.
	 *
	 * @param array $sorter The field ID array.
	 * @param array $options options.
	 * @return void
	 */
	public static function pcp_content_html( $sorter, $options ) {
		$post_content_setting = SP_PC_Functions::pcp_metabox_value( 'pcp_post_content', $sorter );
		$show_post_content    = SP_PC_Functions::pcp_metabox_value( 'show_post_content', $post_content_setting );
		$show_read_more       = SP_PC_Functions::pcp_metabox_value( 'show_read_more', $post_content_setting );
		$pcp_content_type     = SP_PC_Functions::pcp_metabox_value( 'post_content_type', $post_content_setting );
		if ( $show_post_content || $show_read_more ) {
			?>
		<div class="sp-pcp-post-content">
			<?php
			if ( $show_post_content ) {
				echo wp_kses_post( SP_PC_Functions::pcp_content( $post_content_setting, $pcp_content_type ) );
			}
			if ( $show_read_more ) {
				self::pcp_readmore( $post_content_setting, $pcp_content_type, $options );
			}
			?>
		</div>
			<?php
		}
	}

	/**
	 * Read more function
	 *
	 * @param array $view_options Read more options array.
	 * @param array $content_type The content type.
	 * @param array $options The parent of this field.
	 */
	public static function pcp_readmore( $view_options, $content_type, $options ) {
		$show_read_more = isset( $view_options['show_read_more'] ) ? $view_options['show_read_more'] : '';
		if ( ! $show_read_more || 'full_content' === $content_type ) {
			return '';
		}

		$pcp_read_label     = isset( $view_options['pcp_read_label'] ) ? $view_options['pcp_read_label'] : '';
		$pcp_page_link_type = SP_PC_Functions::pcp_metabox_value( 'pcp_page_link_type', $options );
		$pcp_link_rel       = SP_PC_Functions::pcp_metabox_value( 'pcp_link_rel', $options );
		$pcp_link_rel_text  = $pcp_link_rel ? 'rel=nofollow' : '';
		$readmore_target    = SP_PC_Functions::pcp_metabox_value( 'pcp_link_target', $options );
		?>
		<div class="sp-pcp-readmore">
			<?php if ( 'none' === $pcp_page_link_type ) { ?>
				<a class="pcp-readmore-link" target="<?php echo esc_attr( $readmore_target ); ?>" <?php echo esc_attr( $pcp_link_rel_text ); ?>>
			<?php } else { ?>
				<a class="pcp-readmore-link" target="<?php echo esc_attr( $readmore_target ); ?>" href="<?php the_permalink(); ?>" <?php echo esc_attr( $pcp_link_rel_text ); ?>>
			<?php } ?>
			<?php echo esc_html( $pcp_read_label ); ?> </a>
		</div>
			<?php
	}

	/**
	 * Post thumb HTML.
	 *
	 * @param array $sorter post content option array.
	 * @param int   $scode_id Shortcode ID.
	 * @param int   $slide_id The slide/post ID.
	 * @param array $options The slide/post ID.
	 * @return void
	 */
	public static function pcp_post_thumb_html( $sorter, $scode_id, $slide_id, $options ) {
		$_post_thumb_setting = SP_PC_Functions::pcp_metabox_value( 'pcp_post_thumb', $sorter );
		$pcp_page_link_type  = SP_PC_Functions::pcp_metabox_value( 'pcp_page_link_type', $options );
		$pcp_link_rel        = SP_PC_Functions::pcp_metabox_value( 'pcp_link_rel', $options );
		$pcp_link_rel_text   = '';
		if ( $pcp_link_rel ) {
			$pcp_link_rel_text = 'rel=nofollow';
		}
		$pcp_link_target = SP_PC_Functions::pcp_metabox_value( 'pcp_link_target', $options );
		if ( SP_PC_Functions::pcp_metabox_value( 'post_thumb_show', $_post_thumb_setting ) ) {
			$pcp_image_attr = SP_PC_Functions::pcp_sized_thumb( $_post_thumb_setting, $slide_id );
			$thumb_url      = $pcp_image_attr['src'];
			$alter_text     = SP_PC_Functions::pcp_thumb_alter_text( $slide_id );
			if ( ! empty( $thumb_url ) ) {
				?>
				<div class="pcp-post-thumb-wrapper">
					<div class="sp-pcp-post-thumb-area">
					<?php if ( 'none' === $pcp_page_link_type ) { ?>
							<a class="sp-pcp-thumb" <?php echo esc_attr( $pcp_link_rel_text ); ?>>
						<?php } else { ?>
							<a class="sp-pcp-thumb" href="<?php the_permalink(); ?>" target="<?php echo esc_attr( $pcp_link_target ); ?>" <?php echo esc_attr( $pcp_link_rel_text ); ?>>
						<?php } ?>
							<img src="<?php echo esc_url( $thumb_url ); ?>" width="<?php echo esc_attr( $pcp_image_attr['width'] ); ?>" height="<?php echo esc_attr( $pcp_image_attr['height'] ); ?>" alt="<?php echo esc_attr( $alter_text ); ?>">
						</a>
					</div>
				</div>
				<?php
			}
		}
	}

	/**
	 * Post meta HTML
	 *
	 * @param array $sorter post content option array.
	 * @return void
	 */
	public static function pcp_post_meta_html( $sorter ) {
		$_meta_settings   = SP_PC_Functions::pcp_metabox_value( 'pcp_post_meta', $sorter );
		$post_meta_fields = SP_PC_Functions::pcp_metabox_value( 'pcp_post_meta_group', $_meta_settings );
		$show_post_meta   = SP_PC_Functions::pcp_metabox_value( 'show_post_meta', $_meta_settings, true );
		$_meta_separator  = ' ';
		if ( $post_meta_fields && $show_post_meta ) {
			?>
		<div class="sp-pcp-post-meta">
			<?php
			echo wp_kses_post( apply_filters( 'pcp_post_meta_wrapper_start', '<ul>' ) );
				SP_PC_Functions::pcp_get_post_meta( $post_meta_fields, $_meta_separator );
			echo wp_kses_post( apply_filters( 'pcp_post_meta_wrapper_end', '</ul>' ) );
			?>
		</div>
			<?php
		}
	}

	/**
	 * Post content with thumb.
	 *
	 * @param array  $sorter post sorting option.
	 * @param string $layout Layout preset.
	 * @param int    $scode_id The Shortcode ID.
	 * @param object $post The Post object.
	 * @param array  $options The options array.
	 * @return void
	 */
	public static function pcp_post_content_with_thumb( $sorter, $layout, $scode_id, $post, $options ) {
		if ( $sorter ) {
			foreach ( $sorter as $style_key => $style_value ) {
				switch ( $style_key ) {
					case 'pcp_post_thumb':
						self::pcp_post_thumb_html( $sorter, $scode_id, $post->ID, $options );
						break;
					case 'pcp_post_title':
						self::pcp_post_title( $sorter, $layout, $options );
						break;
					case 'pcp_post_content':
						self::pcp_content_html( $sorter, $options );
						break;
					case 'pcp_post_meta':
						self::pcp_post_meta_html( $sorter );
						break;
				}
			}
		}
	}

	/**
	 * Post content without thumb.
	 *
	 * @param array  $sorter post sorting option.
	 * @param string $layout Layout preset.
	 * @param object $post visitor number.
	 * @param array  $options The options array.
	 * @return void
	 */
	public static function pcp_post_content_without_thumb( $sorter, $layout, $post, $options ) {
		if ( $sorter ) {
			foreach ( $sorter as $style_key => $style_value ) {
				switch ( $style_key ) {
					case 'pcp_post_title':
						self::pcp_post_title( $sorter, $layout, $options );
						break;
					case 'pcp_post_content':
						self::pcp_content_html( $sorter, $options );
						break;
					case 'pcp_post_meta':
						self::pcp_post_meta_html( $sorter );
						break;
				}
			}
		}
	}

	/**
	 * Pagination function
	 *
	 * @param object $loop Query array.
	 * @param array  $view_options shortcode options.
	 * @param array  $shortcode_id shortcode id.
	 * @param array  $on_screen screen type.
	 */
	public static function pcp_pagination_bar( $loop, $view_options, $shortcode_id, $on_screen = null ) {
		// $posts_found;
		$posts_found   = $loop->found_posts;
		$post_offset   = isset( $view_options['pcp_post_offset'] ) ? $view_options['pcp_post_offset'] : 0;
		$post_limit    = isset( $view_options['pcp_post_limit'] ) ? $view_options['pcp_post_limit'] : '';
		$post_limit    = ( $post_limit > 0 && $posts_found > $post_limit ) ? $post_limit : $posts_found;
		$post_per_page = isset( $view_options['post_per_page'] ) ? $view_options['post_per_page'] : '';
		$post_per_page = ( $post_per_page > $post_limit ) ? $post_limit : $post_per_page;
		// Post display settings.
		$post_limit = (int) $post_limit;
		if ( $post_limit < 1 ) {
			$pages = 0;
		} else {
			$pages = SP_PC_Functions::pcp_max_pages( $post_limit, $post_per_page );
		}
		$big = 999999999; // need an unlikely integer.
		if ( $pages > 1 ) {
			$paged_var    = 'paged' . $shortcode_id;
			$page_current = ( ! empty( $_GET[ "$paged_var" ] ) ) ? sanitize_text_field( wp_unslash( $_GET[ "$paged_var" ] ) ) : 1;
			$page_links   = paginate_links(
				array(
					'format'    => '?' . $paged_var . '=%#%',
					'current'   => $page_current,
					'total'     => $pages,
					'show_all'  => true,
					'prev_next' => true,
					'type'      => 'array',
					'prev_text' => '<i class="fa fa-angle-left"></i>',
					'next_text' => '<i class="fa fa-angle-right"></i>',
				)
			);
			echo wp_kses_post( implode( $page_links ) );
		}
	}
}
