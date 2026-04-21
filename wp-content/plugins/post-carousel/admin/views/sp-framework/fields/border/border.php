<?php
/**
 * The framework border fields file.
 *
 * @package Smart_Post_Show
 * @subpackage Smart_Post_Show/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SP_PC_Field_border' ) ) {
	/**
	 * SP_PC_Field_border
	 */
	class SP_PC_Field_border extends SP_PC_Fields {
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
					'top_icon'           => '<i class="fa fa-long-arrow-up"></i>',
					'left_icon'          => '<i class="fa fa-long-arrow-left"></i>',
					'bottom_icon'        => '<i class="fa fa-long-arrow-down"></i>',
					'right_icon'         => '<i class="fa fa-long-arrow-right"></i>',
					'all_icon'           => '<i class="sps-icon-border"></i>',
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
					'unit'               => 'px',
					'min'                => '0',
					'hover_color'        => false,
					'border_radius'      => false,
					'show_units'         => false,
				)
			);

			$default_value = array(
				'top'           => '',
				'right'         => '',
				'bottom'        => '',
				'left'          => '',
				'color'         => '',
				'hover_color'   => '',
				'border_radius' => '',
				'style'         => 'solid',
				'all'           => '',
				'min'           => '',
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

			$default_value = ( ! empty( $this->field['default'] ) ) ? wp_parse_args( $this->field['default'], $default_value ) : $default_value;

			$value = wp_parse_args( $this->value, $default_value );

			echo wp_kses_post( $this->field_before() );

			echo '<div class="spf--inputs">';
			$min = ( isset( $args['min'] ) ) ? ' min="' . $args['min'] . '"' : '';

			if ( ! empty( $args['all'] ) ) {

				$placeholder = ( ! empty( $args['all_placeholder'] ) ) ? ' placeholder="' . $args['all_placeholder'] . '"' : '';

				echo '<div class="spf--border">';
				echo '<div class="spf--title">' . esc_html__( 'Width', 'post-carousel' ) . '</div>';
				echo '<div class="spf--input">';
				echo ( ! empty( $args['all_icon'] ) ) ? '<span class="spf--label spf--icon">' . wp_kses_post( $args['all_icon'] ) . '</span>' : '';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[all]' ) ) . '" value="' . esc_attr( $value['all'] ) . '"' . wp_kses_post( $placeholder ) . wp_kses_post( $min ) . ' class="spf-input-number spf--is-unit" />';
				echo ( ! empty( $args['unit'] ) ) ? '<span class="spf--label spf--unit">' . esc_html( $args['unit'] ) . '</span>' : '';
				echo '</div>';
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
					echo '<input type="number" name="' . esc_attr( $this->field_name( '[' . $property . ']' ) ) . '" value="' . esc_attr( $value[ $property ] ) . '"' . wp_kses_post( $placeholder . $min ) . ' class="spf-input-number spf--is-unit" />';
					echo ( ! empty( $args['unit'] ) ) ? '<span class="spf--label spf--unit">' . esc_html( $args['unit'] ) . '</span>' : '';
					echo '</div>';

				}
			}

			if ( ! empty( $args['style'] ) ) {
				echo '<div class="spf--border">';
				echo '<div class="spf--title">' . esc_html__( 'Style', 'post-carousel' ) . '</div>';
				echo '<div class="spf--input">';
				echo '<select name="' . esc_attr( $this->field_name( '[style]' ) ) . '">';
				foreach ( $border_props as $border_prop_key => $border_prop_value ) {
					$selected = ( $value['style'] === $border_prop_key ) ? ' selected' : '';
					echo '<option value="' . esc_attr( $border_prop_key ) . '"' . esc_attr( $selected ) . '>' . esc_html( $border_prop_value ) . '</option>';
				}
				echo '</select>';
				echo '</div>';
				echo '</div>';
			}
			echo '</div>';
			if ( ! empty( $args['color'] ) ) {
				$default_color_attr = ( ! empty( $default_value['color'] ) ) ? ' data-default-color="' . esc_attr( $default_value['color'] ) . '"' : '';
				echo '<div class="spf--color">';
				echo '<div class="spf-field-color">';
				echo '<div class="spf--title">' . esc_html__( 'Color', 'post-carousel' ) . '</div>';
				echo '<input type="text" name="' . esc_attr( $this->field_name( '[color]' ) ) . '" value="' . esc_attr( $value['color'] ) . '" class="spf-color"' . wp_kses_post( $default_color_attr ) . ' />';
				echo '</div>';
				echo '</div>';
			}
			if ( ! empty( $args['hover_color'] ) ) {
				$default_color_attr = ( ! empty( $default_value['hover_color'] ) ) ? ' data-default-color="' . esc_attr( $default_value['hover_color'] ) . '"' : '';
				echo '<div class="spf--color">';
				echo '<div class="spf-field-color">';
				echo '<div class="spf--title">' . esc_html__( 'Hover Color', 'post-carousel' ) . '</div>';
				echo '<input type="text" name="' . esc_attr( $this->field_name( '[hover_color]' ) ) . '" value="' . esc_attr( $value['hover_color'] ) . '" class="spf-color"' . wp_kses_post( $default_color_attr ) . ' />';
				echo '</div>';
				echo '</div>';
			}

			if ( ! empty( $args['border_radius'] ) ) {

				$placeholder = ( ! empty( $args['all_placeholder'] ) ) ? $args['all_placeholder'] : '';
				echo '<div class="spf--color border-radius">';
				echo '<div class="spf--title">' . esc_html__( 'Radius', 'post-carousel' ) . '</div>';
				echo '<div class="spf--input">';
				echo ( ! empty( $args['all_icon'] ) ) ? '<span class="spf--label spf--icon"><i class="sps-icon-radius-01"></i></span>' : '';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[border_radius]' ) ) . '" value="' . esc_attr( $value['border_radius'] ) . '" placeholder="' . esc_attr( $placeholder ) . '" class="spf-input-number spf--is-unit" step="any" />';
				if ( $args['show_units'] && ( $args['units'] ) > 1 ) {
					echo '<div class="spf--input spf--border-select">';
					echo '<select name="' . esc_attr( $this->field_name( '[unit]' ) ) . '">';
					foreach ( $args['units'] as $unit ) {
						$selected = ( $value['unit'] === $unit ) ? ' selected' : '';
						echo '<option value="' . esc_attr( $unit ) . '"' . esc_attr( $selected ) . '>' . esc_attr( $unit ) . '</option>';
					}
					echo '</select>';
					echo '</div>';
				} else {
					echo ( ! empty( $args['unit'] ) ) ? '<span class="spf--label spf--unit">' . esc_attr( $args['unit'] ) . '</span>' : '';

				}
				echo '</div></div>';

			}

			echo '<div class="clear"></div>';

			echo wp_kses_post( $this->field_after() );

		}
	}
}
