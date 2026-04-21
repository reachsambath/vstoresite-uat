<?php
/**
 * The framework select fields file.
 *
 * @package Smart_Post_Show
 * @subpackage Smart_Post_Show/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SP_PC_Field_select' ) ) {
	/**
	 * SP_PC_Field_select
	 */
	class SP_PC_Field_select extends SP_PC_Fields {

		/**
		 * The select field constructor.
		 *
		 * @param string $field The field type.
		 * @param string $value The field value.
		 * @param string $unique The unique ID.
		 * @param string $where The place to show the field.
		 * @param string $parent If it has any parent.
		 */
		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		/**
		 * The render function.
		 *
		 * @return void
		 */
		public function render() {

			$args = wp_parse_args(
				$this->field,
				array(
					'placeholder' => '',
					'chosen'      => false,
					'multiple'    => false,
					'sortable'    => false,
					'ajax'        => false,
					'settings'    => array(),
					'query_args'  => array(),
				)
			);

			$this->value = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );

			echo wp_kses_post( $this->field_before() );

			if ( isset( $this->field['options'] ) ) {

				$multiple_type = isset( $this->field['multiple_type'] ) ? $this->field['multiple_type'] : false;

				if ( ! empty( $args['ajax'] ) ) {
					$args['settings']['data']['type']  = $args['options'];
					$args['settings']['data']['nonce'] = wp_create_nonce( 'spf_chosen_ajax_nonce' );
					if ( ! empty( $args['query_args'] ) ) {
						$args['settings']['data']['query_args'] = $args['query_args'];
					}
				}
				$chosen_rtl       = ( is_rtl() ) ? ' chosen-rtl' : '';
				$multiple_name    = ( $args['multiple'] ) ? '[]' : '';
				$multiple_attr    = ( $args['multiple'] ) ? ' multiple="multiple"' : '';
				$chosen_sortable  = ( $args['chosen'] && $args['sortable'] ) ? ' spf-chosen-sortable' : '';
				$chosen_attr      = ( $args['chosen'] ) ? ' class="spf-chosen' . $chosen_rtl . '"' : '';
				$chosen_ajax      = ( $args['chosen'] && $args['ajax'] ) ? ' spf-chosen-ajax' : '';
				$placeholder_attr = ( $args['chosen'] && $args['placeholder'] ) ? ' data-placeholder="' . $args['placeholder'] . '"' : '';
				$field_class      = ( $args['chosen'] ) ? ' class="spf-chosen' . $chosen_rtl . $chosen_sortable . $chosen_ajax . '"' : '';
				$field_name       = $this->field_name( $multiple_name );
				$field_attr       = $this->field_attributes();
				$maybe_options    = $this->field['options'];
				$field_unique     = $this->unique;
				$chosen_data_attr = ( $args['chosen'] && ! empty( $args['settings'] ) ) ? ' data-chosen-settings="' . esc_attr( json_encode( $args['settings'] ) ) . '"' : '';
				if ( is_string( $maybe_options ) && ! empty( $args['chosen'] ) && ! empty( $args['ajax'] ) ) {
					$options = $this->field_wp_query_data_title( $maybe_options, $this->value );
				} elseif ( is_string( $maybe_options ) ) {
					$options = $this->field_data( $maybe_options, false, $args['query_args'], $field_unique );
				} else {
					$options = $maybe_options;
				}
				if ( ( is_array( $options ) && ! empty( $options ) ) || ( ! empty( $args['chosen'] ) && ! empty( $args['ajax'] ) ) ) {
					if ( ! empty( $args['chosen'] ) && ! empty( $args['multiple'] ) ) {
						echo '<select name="' . esc_attr( $field_name ) . '" class="spf-hidden-select spf-hidden"' . wp_kses_post( $multiple_attr . $field_attr ) . '>';
						foreach ( $this->value as $option_key ) {
								echo '<option value="' . esc_attr( $option_key ) . '" selected>' . esc_html( $option_key ) . '</option>';
						}
						echo '</select>';
						$field_name = '_pseudo';
						$field_attr = '';
					}
					echo '<select name="' . esc_attr( $field_name ) . '"' . wp_kses_post( $field_class . $multiple_attr . $placeholder_attr . $field_attr . $chosen_data_attr ) . '>';
					if ( $args['placeholder'] && empty( $args['multiple'] ) ) {
						if ( ! empty( $args['chosen'] ) ) {
							echo '<option value=""></option>';
						} else {
							echo '<option value="">' . esc_html( $args['placeholder'] ) . '</option>';
						}
					}
					if ( $multiple_type ) {
						$options = array_merge(
							$options,
							array(
								'multiple_post_type' => __( 'Multiple Post Types (Pro)', 'post-carousel' ),
							)
						);
					}
					$selected_value = '';
					foreach ( $options as $option_key => $option ) {
						if ( is_array( $option ) && ! empty( $option ) ) {
							echo '<optgroup label="' . esc_attr( $option_key ) . '">';
							foreach ( $option as $sub_key => $sub_value ) {
								$selected = ( in_array( $sub_key, $this->value ) ) ? ' selected' : '';
								echo '<option value="' . esc_attr( $sub_key ) . '" ' . esc_attr( $selected ) . '>' . wp_kses_post( $sub_value ) . '</option>';
							}
							echo '</optgroup>';
						} else {
							$selected = ( in_array( $option_key, $this->value ) ) ? ' selected' : '';
							if ( in_array( $option_key, $this->value ) ) {
								$selected_value = $option_key;
							}
							echo '<option value="' . esc_attr( $option_key ) . '" ' . esc_attr( $selected ) . '>' . wp_kses_post( $option ) . '</option>';
						}
					}
					echo '</select>';
					if ( isset( $args['preview'] ) && $args['preview'] ) {
						echo '<img src="' . esc_url( SP_PC_URL ) . '/admin/views/sp-framework/assets/img/nav-position/' . esc_attr( $selected_value ) . '.svg " class="icon_preview">';
						echo '<div class="pcp-pro-notice"></div>';
					}
				} else {
					echo ( esc_html( ! empty( $this->field['empty_message'] ) ) ) ? esc_html( $this->field['empty_message'] ) : esc_html__( 'No data provided for this option type.', 'post-carousel' );
				}
			}
			echo wp_kses_post( $this->field_after() );
		}
		/**
		 * Enqueue UI Sortable.
		 *
		 * @return void
		 */
		public function enqueue() {
			if ( ! wp_script_is( 'jquery-ui-sortable' ) ) {
				wp_enqueue_script( 'jquery-ui-sortable' );
			}
		}
	}
}
