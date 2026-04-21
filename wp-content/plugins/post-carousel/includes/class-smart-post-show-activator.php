<?php
/**
 * Fired during plugin activation
 *
 * @link        https://smartpostshow.com/
 * @since      2.2.0
 *
 * @package    Smart_Post_Show
 * @subpackage Smart_Post_Show/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 */
class Smart_Post_Show_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    2.0.0
	 */
	public static function activate() {
		deactivate_plugins( 'post-carousel-pro/post-carousel-pro.php' );
	}

}
