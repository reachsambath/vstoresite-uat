<?php
/**
 *
 * Field: Dimension Advanced
 *
 * @link       https://shapedplugin.com/
 *
 * @package Smart_Post_Show
 * @subpackage Smart_Post_Show/admin/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'SP_PC_Field_dimensions_advanced' ) ) {

	/**
	 * The Advanced Dimensions field class.
	 *
	 * @since 3.5
	 */
	class SP_PC_Field_dimensions_advanced extends SP_PC_Fields {

		/**
		 * Advanced Dimensions field constructor.
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

			$args = wp_parse_args(
				$this->field,
				array(
					'top_icon'           => '<i class="fa fa-long-arrow-up"></i>',
					'right_icon'         => '<i class="fa fa-long-arrow-right"></i>',
					'left_icon'          => '<i class="fa fa-long-arrow-left"></i>',
					'bottom_icon'        => '<i class="fa fa-long-arrow-down"></i>',
					'all_icon'           => '<i class="fa fa-arrows"></i>',
					'top_placeholder'    => esc_html__( 'top', 'post-carousel' ),
					'right_placeholder'  => esc_html__( 'right', 'post-carousel' ),
					'bottom_placeholder' => esc_html__( 'bottom', 'post-carousel' ),
					'left_placeholder'   => esc_html__( 'left', 'post-carousel' ),
					'all_placeholder'    => esc_html__( 'all', 'post-carousel' ),
					'top'                => true,
					'left'               => true,
					'bottom'             => true,
					'right'              => true,
					'all'                => false,
					'color'              => true,
					'style'              => true,
					'styles'             => array( 'Soft-crop', 'Hard-crop' ),
					'units'              => array( 'px', '%', 'em' ),
					'min'                => '0',
				)
			);

			$default_values = array(
				'top'    => '',
				'right'  => '',
				'bottom' => '',
				'left'   => '',
				'color'  => '',
				'style'  => 'solid',
				'all'    => '',
				'min'    => '',
				'unit'   => 'px',
			);

			$border_props = array(
				'solid'  => esc_html__( 'Solid', 'post-carousel' ),
				'dashed' => esc_html__( 'Dashed', 'post-carousel' ),
				'dotted' => esc_html__( 'Dotted', 'post-carousel' ),
				'double' => esc_html__( 'Double', 'post-carousel' ),
				'inset'  => esc_html__( 'Inset', 'post-carousel' ),
				'outset' => esc_html__( 'Outset', 'post-carousel' ),
				'groove' => esc_html__( 'Groove', 'post-carousel' ),
				'ridge'  => esc_html__( 'ridge', 'post-carousel' ),
				'none'   => esc_html__( 'None', 'post-carousel' ),
			);

			$default_values = ( ! empty( $this->field['default'] ) ) ? wp_parse_args( $this->field['default'], $default_values ) : $default_values;

			$value   = wp_parse_args( $this->value, $default_values );
			$unit    = ( count( $args['units'] ) === 1 && ! empty( $args['unit'] ) ) ? $args['units'][0] : '';
			$is_unit = ( ! empty( $unit ) ) ? ' spf--is-unit' : '';

			echo wp_kses_post( $this->field_before() );

			$min = ( isset( $args['min'] ) ) ? ' min="' . $args['min'] . '"' : '';

			echo '<div class="spf--inputs">';
			if ( ! empty( $args['all'] ) ) {

				$placeholder = ( ! empty( $args['all_placeholder'] ) ) ? ' placeholder="' . $args['all_placeholder'] . '"' : '';

				echo '<div class="spf--input">';
				echo ( ! empty( $args['all_icon'] ) ) ? '<span class="spf--label spf--icon">' . wp_kses_post( $args['all_icon'] ) . '</span>' : '';
				echo '<input type="number" disabled="disabled" name="' . esc_attr( $this->field_name( '[all]' ) ) . '" value="' . esc_attr( $value['all'] ) . '"' . wp_kses_post( $placeholder . $min ) . ' class="spf-input-number' . esc_attr( $is_unit ) . '" />';
				echo ( $unit ) ? '<span class="spf--label spf--unit">' . esc_html( $args['units'][0] ) . '</span>' : '';
				echo '</div>';

			} else {

				$properties = array();

				foreach ( array( 'top', 'right', 'bottom', 'left' ) as $prop ) {
					if ( ! empty( $args[ $prop ] ) ) {
						$properties[] = $prop;
					}
				}

				$properties = ( array( 'right', 'left' ) === $properties ) ? array_reverse( $properties ) : $properties;

				foreach ( $properties as $property ) {

					$placeholder = ( ! empty( $args[ $property . '_placeholder' ] ) ) ? ' placeholder="' . $args[ $property . '_placeholder' ] . '"' : '';

					echo '<div class="spf--input">';
					echo ( ! empty( $args[ $property . '_icon' ] ) ) ? '<span class="spf--label spf--icon">' . wp_kses_post( $args[ $property . '_icon' ] ) . '</span>' : '';
					echo '<input type="number" disabled="disabled" name="' . esc_attr( $this->field_name( '[' . $property . ']' ) ) . '" value="' . esc_attr( $value[ $property ] ) . '"' . wp_kses_post( $placeholder . $min ) . ' class="spf-input-number' . esc_attr( $is_unit ) . '" />';
					echo ( $unit ) ? '<span class="spf--label spf--unit">' . esc_html( $args['units'][0] ) . '</span>' : '';
					echo '</div>';

				}
			}

			if ( ! empty( $args['style'] ) ) {
				echo '<div class="spf--input">';
				echo '<select disabled="disabled" name="' . esc_attr( $this->field_name( '[style]' ) ) . '">';
				foreach ( $args['styles'] as $style_prop ) {
					$selected = ( $value['style'] === $style_prop ) ? ' selected' : '';
					echo '<option value="' . esc_attr( $style_prop ) . '"' . esc_attr( $selected ) . '>' . esc_html( $style_prop ) . '</option>';
				}
				echo '</select>';
				echo '</div>';
			}

			if ( ! empty( $args['color'] ) ) {
				$default_color_attr = ( ! empty( $default_values['color'] ) ) ? ' data-default-color="' . esc_attr( $default_values['color'] ) . '"' : '';
				echo '<div class="spf--left spf-field-color">';
				echo '<input type="text" disabled="disabled" name="' . esc_attr( $this->field_name( '[color]' ) ) . '" value="' . esc_attr( $value['color'] ) . '" class="spf-color"' . wp_kses_post( $default_color_attr ) . ' />';
				echo '</div>';
			}

			echo '</div>';

			echo wp_kses_post( $this->field_after() );

		}
	}
}
