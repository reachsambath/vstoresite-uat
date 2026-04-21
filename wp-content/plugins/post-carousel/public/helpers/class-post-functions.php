<?php
/**
 * The file functions.
 *
 * @package Smart_Post_Show
 * @subpackage public
 *
 * @since 2.2.0
 */

/**
 * Post views helper method.
 *
 * @since 2.2.0
 */
class SP_PC_Functions {
	/**
	 * WP Version compare
	 *
	 * @param int    $version_to_compare The WP version.
	 * @param string $operator Compare operator.
	 * @return mixed
	 */
	public static function wp_version_compare( $version_to_compare, $operator = '>=' ) {
		if ( empty( $version_to_compare ) ) {
			return true;
		}
		global $wp_version;

		// Check if using WordPress version 3.7 or higher.
		return version_compare( $wp_version, $version_to_compare, $operator );
	}

	/**
	 * Tag name to full tag conversion.
	 *
	 * @param array $meta_tag Tag option.
	 * @return string
	 */
	public static function short_tag_to_html( $meta_tag ) {
		$exclude_tag_string = '';
		foreach ( $meta_tag as $key => $value ) {
			$exclude_tag_string .= '<' . $value . '>,';
		}
		return $exclude_tag_string;
	}

	/**
	 * Content function.
	 *
	 * @param array  $view_options Read more options array.
	 * @param string $type Content type.
	 * @return return
	 */
	public static function pcp_content( $view_options, $type ) {
		global $wp_embed;
		$post_content_ellipsis = isset( $view_options['post_content_ellipsis'] ) ? $view_options['post_content_ellipsis'] : '';
		$pcp_strip_tags        = isset( $view_options['pcp_strip_tags'] ) ? $view_options['pcp_strip_tags'] : '';
		$pcp_allow_tag_name    = isset( $view_options['pcp_allow_tag_name'] ) ? $view_options['pcp_allow_tag_name'] : '';
		$allowed_tags          = explode( ',', $pcp_allow_tag_name );
		if ( 'full_content' === $type ) {
			$pcp_post_content = get_the_content();
		} else {
			$pcp_post_content = get_the_excerpt();
		}
		$pcp_post_content = do_shortcode( $wp_embed->autoembed( wpautop( trim( $pcp_post_content ) ) ) );
		return $pcp_post_content;
	}

	/**
	 * Thumbnail alter text
	 *
	 * @param integer $slide_id The slide/post ID.
	 *
	 * @return string
	 */
	public static function pcp_thumb_alter_text( $slide_id ) {
		$image_id = get_post_thumbnail_id( $slide_id );
		$alt_text = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
		return $alt_text;
	}

	/**
	 * Thumb Sized function
	 *
	 * @param array   $post_thumb_setting Thumbnails options array.
	 * @param integer $slide_id The slide/post ID.
	 *
	 * @return string
	 */
	public static function pcp_sized_thumb( $post_thumb_setting, $slide_id ) {
		$thumb_id = '';
		$image    = '';
		if ( has_post_thumbnail( $slide_id ) ) {
			$thumb_id = get_post_thumbnail_id();
		}

		$placeholder_img = SP_PC_URL . 'public/assets/img/placeholder.png';
		$placeholder_img = apply_filters( 'pcp_no_thumb_placeholder', $placeholder_img );
		if ( empty( $thumb_id ) && ! empty( $placeholder_img ) ) {
			$thumb_id = attachment_url_to_postid( $placeholder_img );
		}

		if ( ! empty( $thumb_id ) ) {
			$image_sizes  = isset( $post_thumb_setting['pcp_thumb_sizes'] ) ? $post_thumb_setting['pcp_thumb_sizes'] : '';
			$image_src    = wp_get_attachment_image_src( $thumb_id, $image_sizes );
			$image_src    = is_array( $image_src ) ? $image_src : array( '', '', '' );
			$image        = $image_src[0];
			$image_width  = $image_src[1];
			$image_height = $image_src[2];
		} elseif ( ! empty( $placeholder_img ) ) {
			$image        = $placeholder_img;
			$image_width  = 600;
			$image_height = 450;
		}
		$pcp_image_attr = array(
			'src'    => $image,
			'width'  => $image_width,
			'height' => $image_height,
		);
		return $pcp_image_attr;
	}

	/**
	 * Process all the post meta.
	 *
	 * @param array  $post_meta_fields The selected post meta to show.
	 * @param string $meta_separator The post meta separator.
	 * @return void
	 */
	public static function pcp_get_post_meta( $post_meta_fields, $meta_separator ) {
		$i = 0;
		foreach ( $post_meta_fields as $each_meta ) {
			$selected_meta  = $each_meta['select_post_meta'];
			$meta_icon      = ! empty( $each_meta['select_meta_icon'] ) ? sprintf( '<i class="' . $each_meta['select_meta_icon'] . '"></i>' ) : '';
			$meta_tag_start = apply_filters( 'pcp_post_meta_html_tag_start', '<li>' );
			$meta_tag_end   = apply_filters( 'pcp_post_meta_html_tag_end', '</li>' );

			switch ( $selected_meta ) {
				case 'author':
					if ( 0 < $i ) {
						echo esc_html( $meta_separator );
					}
					echo wp_kses_post( $meta_tag_start );
					?>
					<i class="fa fa-user"></i>
					<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"
						rel="author"><?php echo esc_html( get_the_author() ); ?></a>
					<?php
					echo wp_kses_post( $meta_tag_end );
					break;
				case 'date':
					if ( 0 < $i ) {
						echo esc_html( $meta_separator );
					}
					echo wp_kses_post( $meta_tag_start );
					?>
					<i class="fa fa-calendar"></i>
					<time class="entry-date published updated"><?php echo esc_html( get_the_date() ); ?></time>
					<?php
					echo wp_kses_post( $meta_tag_end );
					break;
				case 'comment_count':
					if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
						if ( 0 < $i ) {
							echo esc_html( $meta_separator );
						}
						echo wp_kses_post( $meta_tag_start );
						?>
						<i class="fa fa-comments-o"></i>
							<a href="<?php the_permalink(); ?>"><?php echo esc_html( get_comments_number() ); ?></a>
						<?php
						echo wp_kses_post( $meta_tag_end );
					}
					break;
			}
			++$i;
		} // End Foreach.
	}

	/**
	 * Maximum pages.
	 *
	 * @param int $total_post Number of total posts.
	 * @param int $post_per_page Posts per page.
	 *
	 * @return void
	 */
	public static function pcp_max_pages( $total_post, $post_per_page ) {
		if ( ! $total_post ) {
			return;
		}
		$max_num_pages = ceil( $total_post / $post_per_page );
		return (int) $max_num_pages;
	}

	/**
	 * Post per page.
	 *
	 * @param int $limit Post Limit.
	 * @param int $post_per_page post per page.
	 * @param int $page paged number.
	 *
	 * @return int
	 */
	public static function pcp_post_per_page( $limit, $post_per_page, $page ) {
		$limit               = ( empty( $limit ) || '-1' === $limit ) ? 1000000 : $limit;
		$offset              = (int) $post_per_page * ( $page - 1 );
		$final_post_per_page = $post_per_page;
		if ( intval( $post_per_page ) > $limit - $offset ) {
			$final_post_per_page = $limit - $offset;
		}
		return $final_post_per_page;
	}

	/**
	 * Pagination last page post
	 *
	 * @param int $limit total post limit.
	 * @param int $post_per_page post per page.
	 * @param int $total_page last post page.
	 *
	 * @return int.
	 */
	public static function pcp_last_page_post( $limit, $post_per_page, $total_page ) {
		$limit              = ( empty( $limit ) || '-1' === $limit ) ? 10000000 : $limit;
		$offset             = $post_per_page * ( $total_page - 1 );
		$pcp_last_page_post = $limit - $offset;
		return $pcp_last_page_post;
	}

	/**
	 * Get view option from view ID
	 *
	 * @param string $shortcode_id ID of custom field.
	 *
	 * @return array
	 */
	public static function view_options( $shortcode_id ) {
		if ( ! $shortcode_id ) {
			return;
		}
		$view_options = get_post_meta( $shortcode_id, 'sp_pcp_view_options', true );
		return $view_options;
	}

	/**
	 * Get value of a setting from global settings array
	 *
	 * @param string     $field        The full name of setting to get value.
	 * @param array      $array_to_get Array to get values of wanted setting.
	 * @param mixed|null $assign       The value to assign if setting is not found.
	 */
	public static function pcp_metabox_value( $field, $array_to_get = null, $assign = null ) {
		global $shortcode_id;
		if ( empty( $array_to_get ) ) {
			$array_to_get = self::view_options( $shortcode_id );
		}
		return isset( $array_to_get[ $field ] ) ? $array_to_get[ $field ] : $assign;
	}
}
