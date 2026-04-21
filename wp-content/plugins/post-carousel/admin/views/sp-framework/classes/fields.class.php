<?php
/**
 * The fields class of the plugin.
 *
 * @package Smart_Post_Show
 * @subpackage Smart_Post_Show/views/sp-framework/classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.


if ( ! class_exists( 'SP_PC_Fields' ) ) {
	/**
	 *
	 * Fields Class
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	abstract class SP_PC_Fields extends SP_PC_Abstract {

		/**
		 * Field
		 *
		 * @var array
		 */
		public $field = array();
		/**
		 * Value
		 *
		 * @var string
		 */
		public $value = '';
		/**
		 * Unique
		 *
		 * @var string
		 */
		public $unique = '';
		/**
		 * Where
		 *
		 * @var string
		 */
		public $where = '';
		/**
		 * Parent
		 *
		 * @var string
		 */
		public $parent = '';

		/**
		 * The field constructor.
		 *
		 * @param array  $field The field type.
		 * @param string $value The values of the field.
		 * @param string $unique The unique ID for the field.
		 * @param string $where To where show the output CSS.
		 * @param string $parent The parent args.
		 */
		public function __construct( $field = array(), $value = '', $unique = '', $where = '', $parent = '' ) {
			$this->field  = $field;
			$this->value  = $value;
			$this->unique = $unique;
			$this->where  = $where;
			$this->parent = $parent;
		}

		/**
		 * Field name method.
		 *
		 * @param string $nested_name Nested field name.
		 * @return string
		 */
		public function field_name( $nested_name = '' ) {

			$field_id   = ( ! empty( $this->field['id'] ) ) ? $this->field['id'] : '';
			$unique_id  = ( ! empty( $this->unique ) ) ? $this->unique . '[' . $field_id . ']' : $field_id;
			$field_name = ( ! empty( $this->field['name'] ) ) ? $this->field['name'] : $unique_id;
			$tag_prefix = ( ! empty( $this->field['tag_prefix'] ) ) ? $this->field['tag_prefix'] : '';

			if ( ! empty( $tag_prefix ) ) {
				$nested_name = str_replace( '[', '[' . $tag_prefix, $nested_name );
			}

			return $field_name . $nested_name;

		}

		/**
		 * Field attributes.
		 *
		 * @param array $custom_atts Custom attributes.
		 * @return mixed
		 */
		public function field_attributes( $custom_atts = array() ) {

			$field_id   = ( ! empty( $this->field['id'] ) ) ? $this->field['id'] : '';
			$attributes = ( ! empty( $this->field['attributes'] ) ) ? $this->field['attributes'] : array();

			if ( ! empty( $field_id ) ) {
				$attributes['data-depend-id'] = $field_id;
			}

			if ( ! empty( $this->field['placeholder'] ) ) {
				$attributes['placeholder'] = $this->field['placeholder'];
			}

			$attributes = wp_parse_args( $attributes, $custom_atts );

			$atts = '';

			if ( ! empty( $attributes ) ) {
				foreach ( $attributes as $key => $value ) {
					if ( 'only-key' === $value ) {
						$atts .= ' ' . $key;
					} else {
						$atts .= ' ' . $key . '="' . $value . '"';
					}
				}
			}

			return $atts;

		}

		/**
		 * Field before.
		 *
		 * @return mixed
		 */
		public function field_before() {
			return ( ! empty( $this->field['before'] ) ) ? $this->field['before'] : '';
		}

		/**
		 * Field after.
		 *
		 * @return mixed
		 */
		public function field_after() {

			$output  = ( ! empty( $this->field['after'] ) ) ? $this->field['after'] : '';
			$output .= ( ! empty( $this->field['desc'] ) ) ? '<p class="spf-text-desc">' . $this->field['desc'] . '</p>' : '';
			$output .= ( ! empty( $this->field['help'] ) ) ? '<span class="spf-help"><span class="spf-help-text">' . $this->field['help'] . '</span><span class="fa fa-question-circle"></span></span>' : '';
			$output .= ( ! empty( $this->field['_error'] ) ) ? '<p class="spf-text-error">' . $this->field['_error'] . '</p>' : '';

			return $output;

		}

		/**
		 * Field Data.
		 *
		 * @param array   $type       post types.
		 * @param boolean $term      post term.
		 * @param array   $query_args post term.
		 * @param array   $field_unique unique id.
		 *
		 * @return array
		 */
		public static function field_data( $type = '', $term = false, $query_args = array(), $field_unique = null ) {

			$options      = array();
			$array_search = false;

			// sanitize type name.
			if ( in_array( $type, array( 'page', 'pages' ) ) ) {
				$option = 'page';
			} elseif ( in_array( $type, array( 'post', 'posts' ) ) ) {
				$option = 'post';
			} elseif ( in_array( $type, array( 'category', 'categories' ) ) ) {
				$option = 'category';
			} elseif ( in_array( $type, array( 'tag', 'tags' ) ) ) {
				$option = 'post_tag';
			} elseif ( in_array( $type, array( 'custom_fields', 'custom_field' ) ) ) {
				$option = 'custom_field';
			} elseif ( in_array( $type, array( 'menu', 'menus' ) ) ) {
				$option = 'nav_menu';
			} else {
				$option = '';
			}

			switch ( $type ) {

				case 'page':
				case 'pages':
				case 'post':
				case 'posts':
					// term query required for ajax select.
					if ( ! empty( $term ) ) {
						$query = new WP_Query(
							wp_parse_args(
								$query_args,
								array(
									's'              => $term,
									'post_type'      => $option,
									'post_status'    => 'publish',
									'posts_per_page' => 25,
								)
							)
						);
					} else {
						$query = new WP_Query(
							wp_parse_args(
								$query_args,
								array(
									'post_type'   => $option,
									'post_status' => 'publish',
								)
							)
						);
					}
					if ( ! is_wp_error( $query ) && ! empty( $query->posts ) ) {
						foreach ( $query->posts as $item ) {
							$options[ $item->ID ] = $item->post_title;
						}
					}

					break;
				case 'sp_post_carousel':
					$pcp_get_specific = array(
						'post_type' => 'sp_post_carousel',
					);
					$query_args       = array_merge( $query_args, $pcp_get_specific );
					$all_posts        = get_posts( $query_args );

					if ( ! is_wp_error( $all_posts ) && ! empty( $all_posts ) ) {
						foreach ( $all_posts as $post_obj ) {
							$options[ $post_obj->ID ] = isset( $post_obj->post_title ) && ! empty( $post_obj->post_title ) ? $post_obj->post_title : 'Untitled';
						}
					}
					wp_reset_postdata();
					break;

				case 'category':
				case 'categories':
				case 'tag':
				case 'tags':
				case 'menu':
				case 'menus':
					if ( ! empty( $term ) ) {
							$query = new WP_Term_Query(
								wp_parse_args(
									$query_args,
									array(
										'search'     => $term,
										'taxonomy'   => $option,
										'hide_empty' => false,
										'number'     => 25,
									)
								)
							);
					} else {
						$query = new WP_Term_Query(
							wp_parse_args(
								$query_args,
								array(
									'taxonomy'   => $option,
									'hide_empty' => false,
								)
							)
						);
					}
					if ( ! is_wp_error( $query ) && ! empty( $query->terms ) ) {
						foreach ( $query->terms as $item ) {
								$options[ $item->term_id ] = $item->name;
						}
					}

					break;

				case 'user':
				case 'users':
					if ( ! empty( $term ) ) {
							$query = new WP_User_Query(
								array(
									'search'  => '*' . $term . '*',
									'number'  => 25,
									'orderby' => 'title',
									'order'   => 'ASC',
									'fields'  => array( 'display_name', 'ID' ),
								)
							);
					} else {
						$query = new WP_User_Query( array( 'fields' => array( 'display_name', 'ID' ) ) );
					}
					if ( ! is_wp_error( $query ) && ! empty( $query->get_results() ) ) {
						foreach ( $query->get_results() as $item ) {
								$options[ $item->ID ] = $item->display_name;
						}
					}

					break;

				case 'role':
				case 'roles':
					global $wp_roles;
					if ( ! empty( $wp_roles ) ) {
						if ( ! empty( $wp_roles->roles ) ) {
							foreach ( $wp_roles->roles as $role_key => $role_value ) {
								$options[ $role_key ] = $role_value['name'];
							}
						}
					}
					$array_search = true;

					break;

				case 'post_type':
				case 'post_types':
					$post_types = get_post_types( array( 'show_in_nav_menus' => true ), 'objects' );
					if ( ! is_wp_error( $post_types ) && ! empty( $post_types ) ) {
						foreach ( $post_types as $post_type ) {
							$options[ $post_type->name ] = $post_type->labels->name;
						}
					}
					$array_search = true;

					break;

				case 'taxonomies':
				case 'taxonomy':
					global $post;
					$view_options       = get_post_meta( $post->ID, 'sp_pcp_view_options', true );
					$pcp_post_types     = isset( $view_options['pcp_select_post_type'] ) && ! empty( $view_options['pcp_select_post_type'] ) ? $view_options['pcp_select_post_type'] : 'post';
						$taxonomy_names = get_object_taxonomies( $pcp_post_types, 'names' );
					if ( ! is_wp_error( $taxonomy_names ) && ! empty( $taxonomy_names ) ) {
						$options[''] = __( 'Select Taxonomy', 'post-carousel' );
						foreach ( $taxonomy_names as $taxonomy => $label ) {
							$options[ $label ] = $label;
						}
					}
					break;

				case 'terms':
				case 'term':
					global $post;
					$view_options   = get_post_meta( $post->ID, 'sp_pcp_view_options', true );
					$pcp_post_types = isset( $view_options['pcp_select_post_type'] ) && ! empty( $view_options['pcp_select_post_type'] ) ? $view_options['pcp_select_post_type'] : get_post_types( array(), 'names' );

					$field_index  = preg_replace( '/[^0-9]/', '', $field_unique );
					$pcp_taxonomy = isset( $view_options['pcp_filter_by_taxonomy']['pcp_taxonomy_and_terms'][ $field_index ]['pcp_select_taxonomy'] ) ? $view_options['pcp_filter_by_taxonomy']['pcp_taxonomy_and_terms'][ $field_index ]['pcp_select_taxonomy'] : get_object_taxonomies( $pcp_post_types, 'names' );
					if ( version_compare( get_bloginfo( 'version' ), '4.5', '>=' ) ) {
						$terms = get_terms( array( 'taxonomy' => $pcp_taxonomy ) );
					} else {
						$terms = get_terms( array( $pcp_taxonomy ) );
					}

					if ( ! is_wp_error( $terms ) && ! empty( $terms ) && ! isset( $terms['errors'] ) ) {
						foreach ( $terms as $key => $value ) {
							$options[ $value->term_id ] = $value->name;
						}
					}

					break;

				case 'post_status':
				case 'post_statuses':
					$statuses = get_post_stati( null, 'objects' );
					foreach ( $statuses as $status => $object ) {
						$options[ $status ] = ucfirst( $object->label );
					}

					break;

				default:
					if ( function_exists( $type ) ) {
						if ( ! empty( $term ) ) {
							$options = call_user_func( $type, $query_args );
						} else {
							$options = call_user_func( $type, $term, $query_args );
						}
					}
					break;
			}
				// Array search by "term".
			if ( ! empty( $term ) && ! empty( $options ) && ! empty( $array_search ) ) {
				$options = preg_grep( '/' . $term . '/i', $options );
			}
				// Make multidimensional array for ajax search.
			if ( ! empty( $term ) && ! empty( $options ) ) {
				$arr = array();
				foreach ( $options as $option_key => $option_value ) {
					$arr[] = array(
						'value' => $option_key,
						'text'  => $option_value,
					);
				}
					$options = $arr;
			}
				return $options;
		}

		/**
		 * WP Query data title
		 *
		 * @param string $type The field option type.
		 * @param array  $values Values of the field.
		 * @since 2.1.1
		 * @return statement
		 */
		public function field_wp_query_data_title( $type, $values ) {
			$options = array();
			if ( ! empty( $values ) && is_array( $values ) ) {
				foreach ( $values as $value ) {
					switch ( $type ) {
						case 'post':
						case 'posts':
						case 'page':
						case 'pages':
							$title = get_the_title( $value );
							if ( ! is_wp_error( $title ) && ! empty( $title ) ) {
								$options[ $value ] = $title;
							}
							break;
						case 'category':
						case 'categories':
						case 'tag':
						case 'tags':
						case 'menu':
						case 'menus':
							$term = get_term( $value );
							if ( ! is_wp_error( $term ) && ! empty( $term ) ) {
								$options[ $value ] = $term->name;
							}
							break;
						case 'user':
						case 'users':
							$user = get_user_by( 'id', $value );
							if ( ! is_wp_error( $user ) && ! empty( $user ) ) {
								$options[ $value ] = $user->display_name;
							}
							break;
						case 'sidebar':
						case 'sidebars':
							global $wp_registered_sidebars;
							if ( ! empty( $wp_registered_sidebars[ $value ] ) ) {
								$options[ $value ] = $wp_registered_sidebars[ $value ]['name'];
							}
							break;
						case 'role':
						case 'roles':
							global $wp_roles;
							if ( ! empty( $wp_roles ) && ! empty( $wp_roles->roles ) && ! empty( $wp_roles->roles[ $value ] ) ) {
								$options[ $value ] = $wp_roles->roles[ $value ]['name'];
							}
							break;
						case 'post_type':
						case 'post_types':
							$post_types = get_post_types( array( 'show_in_nav_menus' => true ) );
							if ( ! is_wp_error( $post_types ) && ! empty( $post_types ) && ! empty( $post_types[ $value ] ) ) {
								$options[ $value ] = ucfirst( $value );
							}
							break;

						default:
							if ( function_exists( $type . '_title' ) ) {
								$options[ $value ] = call_user_func( $type . '_title', $value );
							} else {
								$options[ $value ] = ucfirst( $value );
							}
							break;
					}
				}
			}
			return $options;
		}

	}
}
