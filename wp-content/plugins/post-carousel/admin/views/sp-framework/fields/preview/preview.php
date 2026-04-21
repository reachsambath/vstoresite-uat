<?php 
/**
 * The framework preview fields file.
 *
 * @package Smart_Post_Show
 * @subpackage Smart_Post_Show/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; 
} // Cannot access directly.

if ( ! class_exists( 'SP_PC_Field_preview' ) ) {	
	/**
	 * SP_PC_Field_preview
	 */
	class SP_PC_Field_preview extends SP_PC_Fields {
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
			echo '<div class="spsp-preview-box"><div id="spsp-preview-box"></div></div>';
		}

	}
}
