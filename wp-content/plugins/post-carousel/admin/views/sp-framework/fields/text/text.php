<?php
/**
 * The framework text fields file.
 *
 * @package Smart_Post_Show
 * @subpackage Smart_Post_Show/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SP_PC_Field_text' ) ) {
	/**
	 * SP_PC_Field_text
	 */
	class SP_PC_Field_text extends SP_PC_Fields {

		/**
		 * Field constructor.
		 *
		 * @param array  $field The field type.
		 * @param string $value The values of the field.
		 * @param string $unique The unique ID for the field.
		 * @param string $where To where show the output CSS.
		 * @param string $parent The parent args.
		 */
		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {

			parent::__construct( $field, $value, $unique, $where, $parent );
		}
		/**
		 * Render method.
		 *
		 * @return void
		 */
		public function render() {

			$type     = ( ! empty( $this->field['attributes']['type'] ) ) ? $this->field['attributes']['type'] : 'text';
			$url_type = ( ! empty( $this->field['url'] ) ) ? $this->field['url'] : '';

			echo wp_kses_post( $this->field_before() );
			if ( $url_type ) {
				echo '<div class="pro_preview-image"><img src="' . esc_url( $url_type ) . '" alt="pro-img" title="pro image"/></div>';
			} else {
				echo '<input type="' . esc_attr( $type ) . '" name="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $this->value ) . '"' . wp_kses_post( $this->field_attributes() ) . ' />';
			}
			echo wp_kses_post( $this->field_after() );

		}

	}
}
