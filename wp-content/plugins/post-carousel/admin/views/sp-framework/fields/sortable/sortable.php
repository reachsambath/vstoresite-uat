<?php
/**
 * The framework sortable fields file.
 *
 * @package Smart_Post_Show
 * @subpackage Smart_Post_Show/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SP_PC_Field_sortable' ) ) {
	/**
	 * SP_PC_Field_sortable
	 */
	class SP_PC_Field_sortable extends SP_PC_Fields {

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

			echo wp_kses_post( $this->field_before() );

			echo '<div class="spf--sortable">';

			$pre_sortby = array();
			$pre_fields = array();

			// Add array-keys to defined fields for sort by.
			foreach ( $this->field['fields'] as $key => $field ) {
				$pre_fields[ $field['id'] ] = $field;
			}

			// Set sort by by saved-value or default-value.
			if ( ! empty( $this->value ) ) {

				foreach ( $this->value as $key => $value ) {
					$pre_sortby[ $key ] = $pre_fields[ $key ];
				}
			} else {

				foreach ( $pre_fields as $key => $value ) {
					$pre_sortby[ $key ] = $value;
				}
			}

			foreach ( $pre_sortby as $key => $field ) {

				echo '<div class="spf--sortable-item">';

				echo '<div class="spf--sortable-content spf--sortable-helper">';

				$field_default = ( isset( $this->field['default'][ $key ] ) ) ? $this->field['default'][ $key ] : '';
				$field_value   = ( isset( $this->value[ $key ] ) ) ? $this->value[ $key ] : $field_default;
				$unique_id     = ( ! empty( $this->unique ) ) ? $this->unique . '[' . $this->field['id'] . ']' : $this->field['id'];

				SP_PC::field( $field, $field_value, $unique_id, 'field/sortable' );

				echo '</div>';

				// echo '<div class="spf--sortable-helper"><i class="fa fa-arrows"></i></div>';

				echo '</div>';

			}

			echo '</div>';

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
