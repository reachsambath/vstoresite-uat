<?php
/**
 * The file of query insides.
 *
 * @package Smart_Post_Show
 * @subpackage public
 *
 * @since 2.2.0
 */

/**
 * The query inside class to process the query.
 *
 * @since 2.2.0
 */
class SP_PC_QueryInside {

	/**
	 * The post ID.
	 *
	 * @var string post ID.
	 */

	/**
	 * Filtered content.
	 *
	 * @param  mixed $view_options Shortcode options.
	 * @param  mixed $shortcode_id shortcode id.
	 * @param  mixed $layout Upper layout based options.
	 * @return array
	 */
	public static function get_filtered_content( $view_options, $shortcode_id, $layout ) {

		$pcp_post_type   = isset( $view_options['pcp_select_post_type'] ) && ! empty( $view_options['pcp_select_post_type'] ) ? $view_options['pcp_select_post_type'] : 'any';
		$post_limit      = isset( $view_options['pcp_post_limit'] ) ? $view_options['pcp_post_limit'] : '';
		$post_per_page   = isset( $view_options['post_per_page'] ) ? $view_options['post_per_page'] : '';
		$post_offset     = isset( $view_options['pcp_post_offset'] ) ? $view_options['pcp_post_offset'] : 0;
		$pcp_sticky_post = isset( $view_options['pcp_sticky_post'] ) ? $view_options['pcp_sticky_post'] : 0;
		$post_per_page   = ( $post_per_page > $post_limit ) ? $post_limit : $post_per_page;

		$paged_var = 'paged' . $shortcode_id;
		$paged     = ( ! empty( $_GET[ "$paged_var" ] ) ) ? sanitize_text_field( wp_unslash( $_GET[ "$paged_var" ] ) ) : 1;

		$post_per_page = SP_PC_Functions::pcp_post_per_page( $post_limit, $post_per_page, $paged );
		if ( $post_per_page < 1 ) {
			$post_per_page = isset( $view_options['post_per_page'] ) ? $view_options['post_per_page'] : '';
		}
			$offset               = (int) $post_per_page * ( $paged - 1 );
			$layout_preset        = isset( $layout['pcp_layout_preset'] ) ? $layout['pcp_layout_preset'] : '';
			$sticky_post_position = 'top_list' === $pcp_sticky_post ? 0 : 1;

		if ( 'carousel_layout' === $layout_preset || 'slider_layout' === $layout_preset ) {
			$post_per_page = ( $post_limit > 0 ) ? $post_limit : 999999;
			$args          = array(
				'post_type'           => $pcp_post_type,
				'suppress_filters'    => true,
				'ignore_sticky_posts' => $sticky_post_position,
				'posts_per_page'      => $post_per_page,
				'offset'              => (int) $post_offset,
			);
		} else {
			$args = array(
				'post_type'           => $pcp_post_type,
				'suppress_filters'    => true,
				'ignore_sticky_posts' => $sticky_post_position,
				'posts_per_page'      => $post_per_page,
				'paged'               => $paged,
				'offset'              => (int) $offset + (int) $post_offset,
			);
		}

		// Include specific posts.
		$include_posts = isset( $view_options['pcp_include_only_posts'] ) ? $view_options['pcp_include_only_posts'] : '';
		if ( ! empty( $include_posts ) ) {
			$args['post__in'] = $include_posts;
			$args['orderby']  = 'post__in';
		}
		// Exclude posts.
		$exclude_post_set  = isset( $view_options['pcp_exclude_post_set'] ) ? $view_options['pcp_exclude_post_set'] : '';
		$exclude_too       = ! empty( $exclude_post_set['pcp_exclude_too'] ) ? $exclude_post_set['pcp_exclude_too'] : array();
		$current_post_id   = in_array( 'current', $exclude_too, true ) ? array( get_the_ID() ) : array();
		$sticky_post_ids   = 'hide' === $pcp_sticky_post && in_array( 'post', $pcp_post_type, true ) ? get_option( 'sticky_posts' ) : array();
		$exclude_posts     = ! empty( $exclude_post_set['pcp_exclude_posts'] ) && isset( $exclude_post_set['pcp_exclude_posts'] ) ? $exclude_post_set['pcp_exclude_posts'] : '';
		$exclude_posts_int = array();
		if ( ! empty( $exclude_posts ) ) {
			foreach ( $exclude_posts as $exclude_post ) {
				$exclude_posts_int[] = intval( $exclude_post );
			}
		}
		$exclude_post_list = array_merge( $exclude_posts_int, $sticky_post_ids, $current_post_id );
		if ( ! empty( $exclude_post_list ) && empty( $include_posts ) ) {
			$args['post__not_in'] = ( $exclude_post_list );
		}

		// Exclude password protected posts.
		$password_protected = in_array( 'password_protected', $exclude_too, true );
		if ( $password_protected ) {
			$args['has_password'] = false;
		}
		// Exclude children posts.
		$exclude_children = in_array( 'children', $exclude_too, true );
		if ( $exclude_children ) {
			$args['post_parent'] = 0;
		}

		$advanced_filters = isset( $view_options['pcp_advanced_filter'] ) && ! empty( $view_options['pcp_advanced_filter'] ) ? $view_options['pcp_advanced_filter'] : '';
		if ( $advanced_filters ) {
			foreach ( $advanced_filters as $advanced_filter ) {
				switch ( $advanced_filter ) {
					case 'taxonomy':
						$taxonomy_types = isset( $view_options['pcp_filter_by_taxonomy']['pcp_taxonomy_and_terms'] ) && ! empty( $view_options['pcp_filter_by_taxonomy']['pcp_taxonomy_and_terms'] ) ? $view_options['pcp_filter_by_taxonomy']['pcp_taxonomy_and_terms'] : '';
						if ( ! $taxonomy_types ) {
							break;
						}
						$tax_settings = array();
						foreach ( $taxonomy_types as $tax_type ) {
							$taxonomy = isset( $tax_type['pcp_select_taxonomy'] ) ? $tax_type['pcp_select_taxonomy'] : '';
							$terms    = isset( $tax_type['pcp_select_terms'] ) ? $tax_type['pcp_select_terms'] : '';
							if ( $taxonomy ) {
								if ( $terms ) {
									$operator = isset( $tax_type['pcp_taxonomy_term_operator'] ) ? $tax_type['pcp_taxonomy_term_operator'] : '';
									if ( 'AND' === $operator && 1 === count( $terms ) ) {
										$operator = 'IN';
									}
									$tax_settings[] = array(
										'taxonomy'         => $taxonomy,
										'field'            => 'term_id',
										'terms'            => $terms,
										'operator'         => $operator,
										'include_children' => ( 'AND' === $operator ? 'false' : 'true' ),
									);
								}
							}
						}
						if ( count( $tax_settings ) > 1 ) {
							$tax_settings['relation'] = isset( $view_options['pcp_filter_by_taxonomy']['pcp_taxonomies_relation'] ) ? $view_options['pcp_filter_by_taxonomy']['pcp_taxonomies_relation'] : 'AND';
						}
						$args = array_merge( $args, array( 'tax_query' => $tax_settings ) );

						break;
					case 'author':
						$author_include = isset( $view_options['pcp_filter_by_author']['pcp_select_author_by'] ) ? $view_options['pcp_filter_by_author']['pcp_select_author_by'] : '';
						$author_exclude = isset( $view_options['pcp_filter_by_author']['pcp_select_author_not_by'] ) ? $view_options['pcp_filter_by_author']['pcp_select_author_not_by'] : '';
						$wp37           = SP_PC_Functions::wp_version_compare( '3.7' );
						if ( $author_include ) {
							$args = array_merge(
								$args,
								$wp37 ? array( 'author__in' => array_map( 'intval', $author_include ) ) : array( 'author' => intval( $author_include[0] ) )
							);
						}
						if ( $author_exclude && $wp37 ) {
							$args = array_merge(
								$args,
								array( 'author__not_in' => array_map( 'intval', $author_exclude ) )
							);
						}
						break;
					case 'sortby':
						$orderby        = isset( $view_options['pcp_filter_by_order']['pcp_select_filter_orderby'] ) ? $view_options['pcp_filter_by_order']['pcp_select_filter_orderby'] : '';
						$order          = isset( $view_options['pcp_filter_by_order']['pcp_select_filter_order'] ) ? $view_options['pcp_filter_by_order']['pcp_select_filter_order'] : '';
						$order_settings = array(
							'orderby' => $orderby,
							'order'   => $orderby ? $order : '',
						);
						$args           = array_merge( $args, $order_settings );
						break;
					case 'status':
						$pcp_post_status = isset( $view_options['pcp_filter_by_status']['pcp_select_post_status'] ) && ! empty( $view_options['pcp_filter_by_status']['pcp_select_post_status'] ) ? $view_options['pcp_filter_by_status']['pcp_select_post_status'] : 'publish';
						$args            = array_merge( $args, array( 'post_status' => $pcp_post_status ) );
						break;
					case 'keyword':
						$keyword_value = isset( $view_options['pcp_filter_by_keyword']['pcp_set_post_keyword'] ) && ! empty( $view_options['pcp_filter_by_keyword']['pcp_set_post_keyword'] ) ? $view_options['pcp_filter_by_keyword']['pcp_set_post_keyword'] : '';
						if ( $keyword_value ) {
							$args = array_merge(
								$args,
								array(
									's' => $keyword_value,
								)
							);
						}
						break;
				}
			}
		}
		return $args;
	}
}
