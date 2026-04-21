<?php
/**
 * The framework icon fields file.
 *
 * @package Smart_Post_Show
 * @subpackage Smart_Post_Show/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SP_PC_Field_icon' ) ) {
	/**
	 * SP_PC_Field_icon
	 */
	class SP_PC_Field_icon extends SP_PC_Fields {

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

			$args = wp_parse_args(
				$this->field,
				array(
					'button_title' => esc_html__( 'Add Icon', 'post-carousel' ),
					'remove_title' => esc_html__( 'Remove Icon', 'post-carousel' ),
				)
			);

			echo wp_kses_post( $this->field_before() );

			$nonce  = wp_create_nonce( 'spf_icon_nonce' );
			$hidden = ( empty( $this->value ) ) ? ' hidden' : '';

			echo '<div class="spf-icon-select">';
			echo '<span class="spf-icon-preview' . esc_attr( $hidden ) . '"><i class="' . esc_attr( $this->value ) . '"></i></span>';
			echo '<a href="#" class="button button-primary spf-icon-add" data-nonce="' . esc_attr( $nonce ) . '">' . esc_html( $args['button_title'] ) . '</a>';
			echo '<a href="#" class="button spf-warning-primary spf-icon-remove' . esc_attr( $hidden ) . '">' . esc_html( $args['remove_title'] ) . '</a>';
			echo '<input type="text" name="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $this->value ) . '" class="spf-icon-value"' . wp_kses_post( $this->field_attributes() ) . ' />';
			echo '</div>';

			echo wp_kses_post( $this->field_after() );

		}

	}
}
