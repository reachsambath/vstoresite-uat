<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link        https://smartpostshow.com/
 * @since      2.2.0
 *
 * @package    Smart_Post_Show
 * @subpackage Smart_Post_Show/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 */
class Smart_Post_Show_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    2.2.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'post-carousel',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}

}
