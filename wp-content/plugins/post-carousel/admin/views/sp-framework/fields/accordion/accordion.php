<?php
/**
 * The accordion fields of the plugin.
 *
 * @package Smart_Post_Show
 * @subpackage Smart_Post_Show/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SP_PC_Field_accordion' ) ) {
	/**
	 * SP_PC_Field_accordion
	 */
	class SP_PC_Field_accordion extends SP_PC_Fields {

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
		 * The render method.
		 *
		 * @return void
		 */
		public function render() {

			$unallows = array( 'accordion' );

			echo wp_kses_post( $this->field_before() );

			echo '<div class="spf-accordion-items">';

			foreach ( $this->field['accordions'] as $key => $accordion ) {

				echo '<div class="spf-accordion-item">';

				$icon = ( ! empty( $accordion['icon'] ) ) ? 'spf--icon ' . $accordion['icon'] : 'spf-accordion-icon';

				echo '<h4 class="spf-accordion-title">';
				echo '<i class="' . esc_attr( $icon ) . '"></i>';
					echo esc_html( $accordion['title'] );
					echo '</h4>';

				echo '<div class="spf-accordion-content">';

				foreach ( $accordion['fields'] as $field ) {

					if ( in_array( $field['type'], $unallows ) ) {
						$field['_notice'] = true;
					}

					$field_id      = ( isset( $field['id'] ) ) ? $field['id'] : '';
					$field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';
					$field_value   = ( isset( $this->value[ $field_id ] ) ) ? $this->value[ $field_id ] : $field_default;
					$unique_id     = ( ! empty( $this->unique ) ) ? $this->unique . '[' . $this->field['id'] . ']' : $this->field['id'];

					SP_PC::field( $field, $field_value, $unique_id, 'field/accordion' );

				}

				echo '</div>';

				echo '</div>';

			}

			echo '</div>';

			echo wp_kses_post( $this->field_after() );

		}

	}
}
