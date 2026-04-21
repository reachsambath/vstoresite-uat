<?php
/**
 * The framework spacing fields file.
 *
 * @package Smart_Post_Show
 * @subpackage Smart_Post_Show/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'SP_PC_Field_spacing' ) ) {

	/**
	 *
	 * Field: spacing
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_PC_Field_spacing extends SP_PC_Fields {

		/**
		 * Spacing field constructor.
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
		 * Function to render the field.
		 *
		 * @return void
		 */
		public function render() {

			$args = wp_parse_args(
				$this->field,
				array(
					'top_icon'           => '<i class="fa fa-long-arrow-up"></i>',
					'right_icon'         => '<i class="fa fa-long-arrow-right"></i>',
					'bottom_icon'        => '<i class="fa fa-long-arrow-down"></i>',
					'left_icon'          => '<i class="fa fa-long-arrow-left"></i>',
					'all_icon'           => '<i class="fa fa-arrows-alt"></i>',
					'arrow_v'            => '<i class="fa fa-arrows-v"></i>',
					'arrow_h'            => '<i class="fa fa-arrows-h"></i>',
					'top_placeholder'    => esc_html__( 'top', 'post-carousel' ),
					'right_placeholder'  => esc_html__( 'right', 'post-carousel' ),
					'bottom_placeholder' => esc_html__( 'bottom', 'post-carousel' ),
					'left_placeholder'   => esc_html__( 'left', 'post-carousel' ),
					'all_placeholder'    => esc_html__( 'all', 'post-carousel' ),
					'top_bottom_title'   => esc_html__( 'Vertical Gap', 'post-carousel' ),
					'left_right_title'   => esc_html__( 'Gap', 'post-carousel' ),
					'all_placeholder'    => esc_html__( 'all', 'post-carousel' ),
					'top'                => true,
					'left'               => true,
					'bottom'             => true,
					'right'              => true,
					'unit'               => true,
					'show_units'         => true,
					'all'                => false,
					'gap_between'        => false,
					'units'              => array( 'px', '%', 'em' ),
				)
			);

			$default_values = array(
				'top'        => '',
				'right'      => '',
				'bottom'     => '',
				'left'       => '',
				'all'        => '',
				'top-bottom' => '',
				'left-right' => '',
				'unit'       => 'px',
			);

			$value   = wp_parse_args( $this->value, $default_values );
			$unit    = ( count( $args['units'] ) === 1 && ! empty( $args['unit'] ) ) ? $args['units'][0] : '';
			$is_unit = ( ! empty( $unit ) ) ? ' spf--is-unit' : '';

			echo wp_kses_post( $this->field_before() );

			echo '<div class="spf--inputs">';

			if ( ! empty( $args['all'] ) ) {

				$placeholder = ( ! empty( $args['all_placeholder'] ) ) ? ' placeholder="' . esc_attr( $args['all_placeholder'] ) . '"' : '';

				echo '<div class="spf--input">';
				echo ( ! empty( $args['all_icon'] ) ) ? '<span class="spf--label spf--icon">' . wp_kses_post( $args['all_icon'] ) . '</span>' : '';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[all]' ) ) . '" value="' . esc_attr( $value['all'] ) . '"' . wp_kses_post( $placeholder ) . ' class="spf-input-number' . esc_attr( $is_unit ) . '" step="any" />';
				echo ( $unit ) ? '<span class="spf--label spf--unit">' . esc_attr( $args['units'][0] ) . '</span>' : '';
				echo '</div>';

			} elseif ( $args['gap_between'] ) {

				foreach ( array( 'left-right', 'top-bottom' ) as $prop ) {

					$placeholder = ( ! empty( $args['all_placeholder'] ) ) ? ' placeholder="' . esc_attr( $args['all_placeholder'] ) . '"' : '';

					$icon  = ( 'top-bottom' === $prop ) ? 'arrow_v' : 'arrow_h';
					$name  = ( 'top-bottom' === $prop ) ? 'top-bottom' : 'left-right';
					$title = ( 'top-bottom' === $prop ) ? esc_attr( $args['top_bottom_title'] ) : esc_attr( $args['left_right_title'] );

					echo '<div class="spf--spacing">';
					echo '<div class="spf--title">' . esc_html( $title ) . '</div>';
					echo '<div class="spf--input">';
					echo ( ! empty( $args[ $icon ] ) ) ? '<span class="spf--label spf--icon">' . wp_kses_post( $args[ $icon ] ) . '</span>' : '';
					echo '<input type="number" name="' . esc_attr( $this->field_name( '[' . $name . ']' ) ) . '" value="' . esc_attr( $value[ $name ] ) . '"' . wp_kses_post( $placeholder ) . ' class="spf-input-number' . esc_attr( $is_unit ) . '" step="any" />';
					echo ( $unit ) ? '<span class="spf--label spf--unit">' . esc_attr( $args['units'][0] ) . '</span>' : '';
					echo '</div>';
					echo '</div>';
				}
			} else {

				$properties = array();

				foreach ( array( 'top', 'right', 'bottom', 'left' ) as $prop ) {
					if ( ! empty( $args[ $prop ] ) ) {
						$properties[] = $prop;
					}
				}

				$properties = ( array( 'right', 'left' ) === $properties ) ? array_reverse( $properties ) : $properties;

				foreach ( $properties as $property ) {

					echo '<div class="spf--spacing-input">';
					echo '<div class="spf--title">' . esc_html( $property ) . '</div>';
					echo '<div class="spf--input">';
					echo ( ! empty( $args[ $property . '_icon' ] ) ) ? '<span class="spf--label spf--icon">' . wp_kses_post( $args[ $property . '_icon' ] ) . '</span>' : '';
					echo '<input type="number" name="' . esc_attr( $this->field_name( '[' . $property . ']' ) ) . '" value="' . esc_attr( $value[ $property ] ) . '" class="spf-input-number' . esc_attr( $is_unit ) . '" step="any" />';
					echo ( $unit ) ? '<span class="spf--label spf--unit">' . esc_attr( $args['units'][0] ) . '</span>' : '';
					echo '</div>';
					echo '</div>';

				}
			}

			if ( ! empty( $args['unit'] ) && ! empty( $args['show_units'] ) && count( $args['units'] ) > 1 ) {
				echo '<div class="spf--spacing pcp-units">';
				echo '<div class="spf--input">';
				echo '<select name="' . esc_attr( $this->field_name( '[unit]' ) ) . '">';
				foreach ( $args['units'] as $unit ) {
					$selected = ( $value['unit'] === $unit ) ? ' selected' : '';
					echo '<option value="' . esc_attr( $unit ) . '"' . esc_attr( $selected ) . '>' . esc_attr( $unit ) . '</option>';
				}
				echo '</select>';
				echo '</div>';
				echo '</div>';
			}

			echo '</div>';

			echo wp_kses_post( $this->field_after() );

		}

	}
}
