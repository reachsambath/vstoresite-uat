<?php
/**
 * The framework layout preset fields file.
 *
 * @package Smart_Post_Show
 * @subpackage Smart_Post_Show/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SP_PC_Field_layout_preset' ) ) {
	/**
	 * SP_PC_Field_layout_preset
	 */
	class SP_PC_Field_layout_preset extends SP_PC_Fields {

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
		 * Render method
		 *
		 * @return void
		 */
		public function render() {

			$args = wp_parse_args(
				$this->field,
				array(
					'multiple' => false,
					'options'  => array(),
				)
			);

			$value = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );

			echo wp_kses_post( $this->field_before() );

			if ( ! empty( $args['options'] ) ) {

				echo '<div class="spf-siblings spf--image-group" data-multiple="' . esc_attr( $args['multiple'] ) . '">';

				$num = 1;

				foreach ( $args['options'] as $key => $option ) {

					$type               = ( $args['multiple'] ) ? 'checkbox' : 'radio';
					$extra              = ( $args['multiple'] ) ? '[]' : '';
					$active             = ( in_array( $key, $value ) ) ? ' spf--active' : '';
					$checked            = ( in_array( $key, $value ) ) ? ' checked' : '';
					$pro_only           = isset( $option['pro_only'] ) ? ' disabled' : '';
					$pcp_pro_only_class = isset( $option['pro_only'] ) ? ' pcp-pro-only' : '';

					echo '<div class="spf--sibling spf--image' . esc_attr( $active . $pcp_pro_only_class ) . '">';
					echo '<img src="' . esc_url( $option['image'] ) . '" alt="' . esc_attr( $option['text'] ) . '" />';
					echo '<input ' . esc_attr( $pro_only ) . ' type="' . esc_attr( $type ) . '" name="' . esc_attr( $this->field_name( $extra ) ) . '" value="' . esc_attr( $key ) . '"' . wp_kses_post( $this->field_attributes() ) . esc_attr( $checked ) . '/>';
					if ( isset( $option['option_demo_url'] ) ) {
						echo '<span class="sp-carousel-type">' . esc_html( $option['text'] ) . '<a href="' . esc_url( $option['option_demo_url'] ) . '" tooltip="Demo" class="sps-live-demo-icon" target="_blank"><i class="sps-icon-external_link"></i></a></span>';
					}
					if ( isset( $option['text'] ) && ! isset( $option['option_demo_url'] ) ) {
						echo '<span class="sp-carousel-type">' . esc_html( $option['text'] ) . '</span>';
					}
					// echo '<span class="sp-carousel-type">' . esc_html( $option['text'] ) . '</span>';
					echo '</div>';

				}

				echo '</div>';

			}

			echo '<div class="clear"></div>';

			echo wp_kses_post( $this->field_after() );

		}
	}
}
