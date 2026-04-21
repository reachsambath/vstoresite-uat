<?php
/**
 * The plugin gutenberg block.
 *
 * @link       https://shapedplugin.com/
 * @since      2.4.2
 *
 * @package    Smart_Post_Show
 * @subpackage Smart_Post_Show/Admin
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Smart_Post_Show_Gutenberg_Block' ) ) {

	/**
	 * Custom Gutenberg Block.
	 */
	class Smart_Post_Show_Gutenberg_Block {

		/**
		 * Block Initializer.
		 */
		public function __construct() {
			require_once SP_PC_PATH . 'admin/GutenbergBlock/class-smart-post-show-gutenberg-block-init.php';
			new Smart_Post_Show_Gutenberg_Block_Init();
		}

	}
}
