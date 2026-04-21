<?php
/**
 * The framework spinner fields file.
 *
 * @package Smart_Post_Show
 * @subpackage Smart_Post_Show/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SP_PC_Field_spinner' ) ) {
	/**
	 * SP_PC_Field_spinner
	 */
	class SP_PC_Field_spinner extends SP_PC_Fields {
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

			$args = wp_parse_args(
				$this->field,
				array(
					'max'  => 100,
					'min'  => 0,
					'step' => 1,
					'unit' => '',
				)
			);

			echo wp_kses_post( $this->field_before() );
			echo '<div class="spf--spin"><input type="number" name="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $this->value ) . '"' . wp_kses_post( $this->field_attributes( array( 'class' => 'spf-input-number' ) ) ) . ' data-max="' . esc_attr( $args['max'] ) . '" data-min="' . esc_attr( $args['min'] ) . '" data-step="' . esc_attr( $args['step'] ) . '" data-unit="' . esc_attr( $args['unit'] ) . '"/></div>';
			echo wp_kses_post( $this->field_after() );

		}

		/**
		 * Enqueue jQuery UI Spinner.
		 *
		 * @return void
		 */
		public function enqueue() {

			if ( ! wp_script_is( 'jquery-ui-spinner' ) ) {
				wp_enqueue_script( 'jquery-ui-spinner' );
			}

		}
	}
}
