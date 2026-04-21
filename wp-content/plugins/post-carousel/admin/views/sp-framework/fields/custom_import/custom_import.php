<?php 
/**
 * The framework custom import fields file.
 *
 * @package Smart_Post_Show
 * @subpackage Smart_Post_Show/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.
/**
 *
 * Field: Custom_import
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'SP_PC_Field_custom_import' ) ) {
	/**
	 * The import field class.
	 */
	class SP_PC_Field_custom_import extends SP_PC_Fields {

		/**
		 * Import field constructor.
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
		 * Render import function.
		 *
		 * @return void
		 */
		public function render() {
			echo wp_kses_post( $this->field_before() );
			$pcp_shortcodelink = admin_url( 'edit.php?post_type=sp_post_carousel' );
				echo '<p><input type="file" id="import" accept=".json"></p>';
				echo '<p><button type="button" class="import">Import</button></p>';
				echo '<a id="pcp_shortcode_link_redirect" href="' . esc_url( $pcp_shortcodelink ) . '"></a>';
				echo wp_kses_post( $this->field_after() );
		}
	}
}
