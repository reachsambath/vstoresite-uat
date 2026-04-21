<?php
/**
 * The framework column fields file.
 *
 * @package Smart_Post_Show
 * @subpackage Smart_Post_Show/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.
/**
 *
 * Field: column
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'SP_PC_Field_column' ) ) {

	/**
	 * The column field class.
	 */
	class SP_PC_Field_column extends SP_PC_Fields {

		/**
		 * Column field constructor.
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
		 * Render column function.
		 *
		 * @return void
		 */
		public function render() {

			$args = wp_parse_args(
				$this->field,
				array(
					'lg_desktop_icon'              => '<i class="fa fa-television"></i>',
					'desktop_icon'                 => '<i class="fa fa-desktop"></i>',
					'tablet_icon'                  => '<i class="fa fa-tablet"></i>',
					'mobile_landscape_icon'        => '<i class="fa fa-mobile"></i>',
					'mobile_icon'                  => '<i class="fa fa-mobile"></i>',
					'all_icon'                     => '<i class="fa fa-arrows"></i>',
					'lg_desktop_placeholder'       => esc_html__( 'Large Desktop', 'post-carousel' ),
					'desktop_placeholder'          => esc_html__( 'Desktop', 'post-carousel' ),
					'tablet_placeholder'           => esc_html__( 'Tablet', 'post-carousel' ),
					'mobile_landscape_placeholder' => esc_html__( 'Mobile Landscape', 'post-carousel' ),
					'mobile_placeholder'           => esc_html__( 'Mobile', 'post-carousel' ),
					'all_placeholder'              => esc_html__( 'all', 'post-carousel' ),
					'lg_desktop'                   => true,
					'desktop'                      => true,
					'tablet'                       => true,
					'mobile_landscape'             => true,
					'mobile'                       => true,
					'min'                          => '1',
					'unit'                         => false,
					'decimal'                      => false,
					'all'                          => false,
					'units'                        => array( 'px', '%', 'em' ),
				)
			);

			$default_values = array(
				'lg_desktop'       => '5',
				'desktop'          => '4',
				'tablet'           => '3',
				'mobile_landscape' => '2',
				'mobile'           => '1',
				'min'              => '',
				'all'              => '',
				'unit'             => 'px',
			);

			// $value = wp_parse_args( $this->value, $default_values );
			$value   = wp_parse_args( $this->value, $default_values );
			$unit    = ( count( $args['units'] ) === 1 && ! empty( $args['unit'] ) ) ? $args['units'][0] : '';
			$is_unit = ( ! empty( $unit ) ) ? ' spf--is-unit' : '';

			echo wp_kses_post( $this->field_before() );

			echo '<div class="spf--inputs">';

			$min     = ( isset( $args['min'] ) ) ? ' min="' . $args['min'] . '"' : '';
			$decimal = ( isset( $args['decimal'] ) && $args['decimal'] ) ? ' step="any"' : '';

			if ( ! empty( $args['all'] ) ) {

				$placeholder = ( ! empty( $args['all_placeholder'] ) ) ? ' placeholder="' . $args['all_placeholder'] . '"' : '';

				echo '<div class="spf--input">';
				echo ( ! empty( $args['all_icon'] ) ) ? '<span class="spf--label spf--icon">' . wp_kses_post( $args['all_icon'] ) . '</span>' : '';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[all]' ) ) . '" value="' . esc_attr( $value['all'] ) . '"' . wp_kses_post( $placeholder ) . wp_kses_post( $min . $decimal ) . ' class="spf-number" required/>';
				echo ( count( $args['units'] ) === 1 && ! empty( $args['unit'] ) ) ? '<span class="spf--label spf--unit">' . esc_html( $args['units'][0] ) . '</span>' : '';
				echo '</div>';

			} else {

				$properties = array();

				foreach ( array( 'lg_desktop', 'desktop', 'tablet', 'mobile_landscape', 'mobile' ) as $prop ) {
					if ( ! empty( $args[ $prop ] ) ) {
						$properties[] = $prop;
					}
				}

				$properties = ( array( 'tablet', 'mobile' ) === $properties ) ? array_reverse( $properties ) : $properties;

				foreach ( $properties as $property ) {

					$placeholder = ( ! empty( $args[ $property . '_placeholder' ] ) ) ? ' placeholder="' . $args[ $property . '_placeholder' ] . '"' : '';

					echo '<div class="spf--input">';
					echo ( ! empty( $args[ $property . '_icon' ] ) ) ? '<span class="spf--label spf--icon">' . wp_kses_post( $args[ $property . '_icon' ] ) . '</span>' : '';
					echo '<input type="number" name="' . esc_attr( $this->field_name( '[' . $property . ']' ) ) . '" value="' . esc_attr( $value[ $property ] ) . '"' . wp_kses_post( $placeholder ) . wp_kses_post( $min ) . ' class="spf-number" required/>';
					echo ( count( $args['units'] ) === 1 && ! empty( $args['unit'] ) ) ? '<span class="spf--label spf--unit">' . esc_html( $args['units'][0] ) . '</span>' : '';
					echo '</div>';

				}
			}

			if ( ! empty( $args['unit'] ) && count( $args['units'] ) > 1 ) {
				echo '<div class="spf--input">';

				echo '<select name="' . esc_attr( $this->field_name( '[unit]' ) ) . '">';
				foreach ( $args['units'] as $unit ) {
					$selected = ( $value['unit'] === $unit ) ? ' selected' : '';
					echo '<option value="' . esc_attr( $unit ) . '"' . esc_attr( $selected ) . '>' . esc_html( $unit ) . '</option>';
				}
				echo '</select>';
				echo '</div>';
			}

			echo '</div>';

			echo wp_kses_post( $this->field_after() );

		}
	}
}
