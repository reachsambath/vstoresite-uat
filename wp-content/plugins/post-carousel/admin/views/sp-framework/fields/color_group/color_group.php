<?php
/**
 * The framework color group fields file.
 *
 * @package Smart_Post_Show
 * @subpackage Smart_Post_Show/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SP_PC_Field_color_group' ) ) {
	/**
	 * SP_PC_Field_color_group
	 */
	class SP_PC_Field_color_group extends SP_PC_Fields {

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
		 * Render
		 *
		 * @return void
		 */
		public function render() {

			$options = ( ! empty( $this->field['options'] ) ) ? $this->field['options'] : array();

			echo wp_kses_post( $this->field_before() );

			if ( ! empty( $options ) ) {
				foreach ( $options as $key => $option ) {

					$color_value  = ( ! empty( $this->value[ $key ] ) ) ? $this->value[ $key ] : '';
					$default_attr = ( ! empty( $this->field['default'][ $key ] ) ) ? ' data-default-color="' . esc_attr( $this->field['default'][ $key ] ) . '"' : '';

					echo '<div class="spf--left spf-field-color">';
					echo '<div class="spf--title">' . $option . '</div>';
					echo '<input type="text" name="' . $this->field_name( '[' . $key . ']' ) . '" value="' .  esc_attr( $color_value ) . '" class="spf-color"' . $default_attr . $this->field_attributes() . '/>';// phpcs:ignore
					echo '</div>';

				}
			}

			echo '<div class="clear"></div>';

			echo wp_kses_post( $this->field_after() );

		}

	}
}
