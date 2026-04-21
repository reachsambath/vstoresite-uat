<?php
/**
 * The framework radio fields file.
 *
 * @package Smart_Post_Show
 * @subpackage Smart_Post_Show/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SP_PC_Field_radio' ) ) {
	/**
	 * SP_PC_Field_radio
	 */
	class SP_PC_Field_radio extends SP_PC_Fields {

		/**
		 * The radio field constructor.
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
					'inline'     => false,
					'query_args' => array(),
				)
			);

			$inline_class = ( $args['inline'] ) ? ' class="spf--inline-list"' : '';

			echo wp_kses_post( $this->field_before() );

			if ( isset( $this->field['options'] ) ) {

				$options = $this->field['options'];
				$options = ( is_array( $options ) ) ? $options : array_filter( $this->field_data( $options, false, $args['query_args'] ) );

				if ( is_array( $options ) && ! empty( $options ) ) {

					echo '<ul' . wp_kses_post( $inline_class ) . '>';
					foreach ( $options as $option_key => $pcp_metabox_value ) {

						if ( is_array( $pcp_metabox_value ) && ! empty( $pcp_metabox_value ) ) {

							echo '<li>';
							echo '<ul>';
							echo '<li><strong>' . esc_html( $option_key ) . '</strong></li>';
							foreach ( $pcp_metabox_value as $sub_key => $sub_value ) {
								$checked = ( $sub_key == $this->value ) ? ' checked' : '';
								echo '<li><label><input type="radio" name="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $sub_key ) . '"' . wp_kses_post( $this->field_attributes() ) . esc_attr( $checked ) . '/> ' . wp_kses_post( $sub_value ) . '</label></li>';
							}
							echo '</ul>';
							echo '</li>';

						} else {

							$checked = ( $option_key == $this->value ) ? ' checked' : '';
							echo '<li><label><input type="radio" name="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $option_key ) . '"' . wp_kses_post( $this->field_attributes() ) . esc_attr( $checked ) . '/> ' . esc_html( $pcp_metabox_value ) . '</label></li>';

						}
					}
					echo '</ul>';

				} else {

					echo ( esc_html( ! empty( $this->field['empty_message'] ) ) ) ? esc_html( $this->field['empty_message'] ) : esc_html__( 'No data provided for this option type.', 'post-carousel' );

				}
			} else {
					$label = ( isset( $this->field['label'] ) ) ? $this->field['label'] : '';
					echo '<label><input type="radio" name="' . esc_attr( $this->field_name() ) . '" value="1"' . wp_kses_post( $this->field_attributes() . checked( $this->value, 1, false ) ) . '/> ' . esc_html( $label ) . '</label>';
			}

			echo wp_kses_post( $this->field_after() );

		}

	}
}
