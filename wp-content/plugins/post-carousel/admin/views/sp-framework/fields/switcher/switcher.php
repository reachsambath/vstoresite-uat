<?php
/**
 * The framework preview fields file.
 *
 * @package Smart_Post_Show
 * @subpackage Smart_Post_Show/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SP_PC_Field_switcher' ) ) {
	/**
	 * SP_PC_Field_switcher
	 */
	class SP_PC_Field_switcher extends SP_PC_Fields {
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

			$active     = ( ! empty( $this->value ) ) ? ' spf--active' : '';
			$text_on    = ( ! empty( $this->field['text_on'] ) ) ? $this->field['text_on'] : esc_html__( 'On', 'post-carousel' );
			$text_off   = ( ! empty( $this->field['text_off'] ) ) ? $this->field['text_off'] : esc_html__( 'Off', 'post-carousel' );
			$text_width = ( ! empty( $this->field['text_width'] ) ) ? ' style="width: ' . $this->field['text_width'] . 'px;"' : '';

			echo wp_kses_post( $this->field_before() );

			echo '<div class="spf--switcher' . esc_attr( $active ) . '"' . wp_kses_post( $text_width ) . '>';
			echo '<span class="spf--on">' . esc_html( $text_on ) . '</span>';
			echo '<span class="spf--off">' . esc_html( $text_off ) . '</span>';
			echo '<span class="spf--ball"></span>';
			echo '<input type="text" name="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $this->value ) . '"' . wp_kses_post( $this->field_attributes() ) . ' />';
			echo '</div>';

			echo ( ! empty( $this->field['label'] ) ) ? '<span class="spf--label">' . esc_html( $this->field['label'] ) . '</span>' : '';

			echo '<div class="clear"></div>';

			echo wp_kses_post( $this->field_after() );

		}

	}
}
