<?php
/**
 * The widget class of the plugin.
 *
 * @package Smart_Post_Show
 * @subpackage Smart_Post_Show/views/sp-framework/classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'SP_PC_Widget' ) ) {
	/**
	 *
	 * Widgets Class
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_PC_Widget extends WP_Widget {

		/**
		 * Unique ID/Name
		 *
		 * @var string
		 */
		public $unique = '';

		/**
		 * Arguments.
		 *
		 * @var array
		 */
		public $args = array(
			'title'       => '',
			'classname'   => '',
			'description' => '',
			'width'       => '',
			'defaults'    => array(),
			'fields'      => array(),
			'class'       => '',
		);

		/**
		 * Constructor.
		 *
		 * @param string $key the key.
		 * @param array  $params The parameters.
		 */
		public function __construct( $key, $params ) {

			$widget_ops  = array();
			$control_ops = array();

			$this->unique = $key;
			$this->args   = apply_filters( "spf_{$this->unique}_args", wp_parse_args( $params, $this->args ), $this );

			// Set control options.
			if ( ! empty( $this->args['width'] ) ) {
				$control_ops['width'] = $this->args['width'];
			}

			// Set widget options.
			if ( ! empty( $this->args['description'] ) ) {
				$widget_ops['description'] = $this->args['description'];
			}

			if ( ! empty( $this->args['classname'] ) ) {
				$widget_ops['classname'] = $this->args['classname'];
			}

			// Set filters.
			$widget_ops  = apply_filters( "spf_{$this->unique}_widget_ops", $widget_ops, $this );
			$control_ops = apply_filters( "spf_{$this->unique}_control_ops", $control_ops, $this );

			parent::__construct( $this->unique, $this->args['title'], $widget_ops, $control_ops );

		}

		/**
		 * Register widget with WordPress.
		 *
		 * @param string $key The key.
		 * @param array  $params The parameters.
		 * @return object
		 */
		public static function instance( $key, $params = array() ) {
			return new self( $key, $params );
		}

		/**
		 * Front-end display of widget.
		 *
		 * @param array $args The arguments.
		 * @param mixed $instance The widget instance.
		 * @return void
		 */
		public function widget( $args, $instance ) {
			call_user_func( $this->unique, $args, $instance );
		}

		/**
		 * Get default value
		 *
		 * @param mixed $field The fields.
		 * @param array $options The options.
		 * @return statement
		 */
		public function get_default( $field, $options = array() ) {

			$default = ( isset( $this->args['defaults'][ $field['id'] ] ) ) ? $this->args['defaults'][ $field['id'] ] : '';
			$default = ( isset( $field['default'] ) ) ? $field['default'] : $default;
			$default = ( isset( $options[ $field['id'] ] ) ) ? $options[ $field['id'] ] : $default;

			return $default;

		}

		/**
		 * Back-end widget form.
		 *
		 * @param array $instance The form instance.
		 * @return void
		 */
		public function form( $instance ) {

			if ( ! empty( $this->args['fields'] ) ) {

				$class = ( $this->args['class'] ) ? ' ' . $this->args['class'] : '';
				echo '<div class="spf spf-widgets spf-fields' . esc_attr( $class ) . '">';

				foreach ( $this->args['fields'] as $field ) {

					$field_value  = '';
					$field_unique = '';

					if ( ! empty( $field['id'] ) ) {

						$field_value  = $this->get_default( $field, $instance );
						$field_unique = 'widget-' . $this->unique . '[' . $this->number . ']';

						if ( 'title' === $field['id'] ) {
							$field['attributes']['id'] = 'widget-' . $this->unique . '-' . $this->number . '-title';
						}
					}

					SP_PC::field( $field, $field_value, $field_unique );

				}

				echo '</div>';

			}

		}

		/**
		 * Sanitize widget form values as they are saved
		 *
		 * @param mixed $new_instance The new instance.
		 * @param mixed $old_instance The old instance.
		 * @return statement
		 */
		public function update( $new_instance, $old_instance ) {

			$new_instance = apply_filters( "spf_{$this->unique}_save", $new_instance, $this->args, $this );

			do_action( "spf_{$this->unique}_save_before", $new_instance, $this->args, $this );

			return $new_instance;

		}
	}
}
