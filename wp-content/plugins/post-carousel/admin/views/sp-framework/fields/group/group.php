<?php
/**
 * The framework group fields file.
 *
 * @package Smart_Post_Show
 * @subpackage Smart_Post_Show/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.


if ( ! class_exists( 'SP_PC_Field_group' ) ) {
	/**
	 * SP_PC_Field_group
	 */
	class SP_PC_Field_group extends SP_PC_Fields {
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

			$args = wp_parse_args(
				$this->field,
				array(
					'max'                    => 0,
					'min'                    => 0,
					'fields'                 => array(),
					'button_title'           => esc_html__( 'Add New', 'post-carousel' ),
					'accordion_title_prefix' => '',
					'accordion_title_number' => false,
					'accordion_title_auto'   => true,
				)
			);

			$title_prefix = ( ! empty( $args['accordion_title_prefix'] ) ) ? $args['accordion_title_prefix'] : '';
			$title_number = ( ! empty( $args['accordion_title_number'] ) ) ? true : false;
			$title_auto   = ( ! empty( $args['accordion_title_auto'] ) ) ? true : false;

			if ( ! empty( $this->parent ) && preg_match( '/' . preg_quote( '[' . $this->field['id'] . ']' ) . '/', $this->parent ) ) {

				echo '<div class="spf-notice spf-notice-danger">' . esc_html__( 'Error: Nested field id can not be same with another nested field id.', 'post-carousel' ) . '</div>';

			} else {

				echo wp_kses_post( $this->field_before() );

				echo '<div class="spf-cloneable-item spf-cloneable-hidden">';

				echo '<div class="spf-cloneable-helper">';
				echo '<i class="spf-cloneable-sort fa-solid fa-angle-down"></i>';
				echo '<i class="spf-cloneable-clone fa fa-clone"></i>';
				echo '<i class="spf-cloneable-remove spf-confirm fa fa-times" data-confirm="' . esc_html__( 'Are you sure to delete this item?', 'post-carousel' ) . '"></i>';
				echo '</div>';

				echo '<h4 class="spf-cloneable-title">';
				echo '<span class="spf-cloneable-text">';
				echo ( $title_number ) ? '<span class="spf-cloneable-title-number"></span>' : '';
				echo ( $title_prefix ) ? '<span class="spf-cloneable-title-prefix">' . esc_html( $title_prefix ) . '</span>' : '';
				echo ( $title_auto ) ? '<span class="spf-cloneable-value"><span class="spf-cloneable-placeholder"></span></span>' : '';
				echo '</span>';
				echo '</h4>';

				echo '<div class="spf-cloneable-content">';
				foreach ( $this->field['fields'] as $field ) {

					$field_parent  = $this->parent . '[' . $this->field['id'] . ']';
					$field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';

					SP_PC::field( $field, $field_default, '_nonce', 'field/group', $field_parent );

				}
				echo '</div>';

				echo '</div>';

				echo '<div class="spf-cloneable-wrapper spf-data-wrapper" data-title-number="' . esc_attr( $title_number ) . '" data-unique-id="' . esc_attr( $this->unique ) . '" data-field-id="[' . esc_attr( $this->field['id'] ) . ']" data-max="' . esc_attr( $args['max'] ) . '" data-min="' . esc_attr( $args['min'] ) . '">';

				if ( ! empty( $this->value ) ) {

					$num = 0;

					foreach ( $this->value as $value ) {

						$first_id = ( isset( $this->field['fields'][0]['id'] ) ) ? $this->field['fields'][0]['id'] : '';

						$first_value = ( isset( $value[ $first_id ] ) ) ? $value[ $first_id ] : '';

						echo '<div class="spf-cloneable-item">';

						echo '<div class="spf-cloneable-helper">';
						echo '<i class="spf-cloneable-sort fa fa-arrows"></i>';
						echo '<i class="spf-cloneable-clone fa fa-clone"></i>';
						echo '<i class="spf-cloneable-remove spf-confirm fa fa-times" data-confirm="' . esc_html__( 'Are you sure to delete this item?', 'post-carousel' ) . '"></i>';
						echo '</div>';

						echo '<h4 class="spf-cloneable-title">';
						echo '<span class="spf-cloneable-text">';
						echo ( $title_number ) ? '<span class="spf-cloneable-title-number">' . esc_html( ( $num + 1 ) ) . ' . </span>' : '';
						echo ( $title_prefix ) ? '<span class="spf-cloneable-title-prefix">' . esc_html( $title_prefix ) . '</span>' : '';
						echo ( $title_auto ) ? '<span class="spf-cloneable-value">' . esc_html( $first_value ) . '</span>' : '';
						echo '</span>';
						echo '</h4>';

						echo '<div class="spf-cloneable-content">';

						foreach ( $this->field['fields'] as $field ) {

							$field_parent = $this->parent . '[' . $this->field['id'] . ']';
							$field_unique = ( ! empty( $this->unique ) ) ? $this->unique . '[' . $this->field['id'] . '][' . $num . ']' : $this->field['id'] . '[' . $num . ']';
							$field_value  = ( isset( $field['id'] ) && isset( $value[ $field['id'] ] ) ) ? $value[ $field['id'] ] : '';

							SP_PC::field( $field, $field_value, $field_unique, 'field/group', $field_parent );

						}

						echo '</div>';

						echo '</div>';

						$num++;

					}
				}

				echo '</div>';

				echo '<div class="spf-cloneable-alert spf-cloneable-max">' . esc_html__( 'You can not add more than', 'post-carousel' ) . ' ' . esc_html( $args['max'] ) . '</div>';
				echo '<div class="spf-cloneable-alert spf-cloneable-min">' . esc_html__( 'You can not remove less than', 'post-carousel' ) . ' ' . esc_html( $args['min'] ) . '</div>';

				echo '<a href="#" class="button button-primary spf-cloneable-add">' . esc_html( $args['button_title'] ) . '</a>';

				echo wp_kses_post( $this->field_after() );

			}

		}

		/**
		 * Enqueue Accordion UI.
		 *
		 * @return void
		 */
		public function enqueue() {

			if ( ! wp_script_is( 'jquery-ui-accordion' ) ) {
				wp_enqueue_script( 'jquery-ui-accordion' );
			}

			if ( ! wp_script_is( 'jquery-ui-sortable' ) ) {
				wp_enqueue_script( 'jquery-ui-sortable' );
			}

		}

	}
}
