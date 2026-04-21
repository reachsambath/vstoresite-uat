<?php
/**
 * Smart Post Show
 *
 * @link              https://smartpostshow.com/
 * @since             2.2.0
 * @package           Smart_Post_Show
 *
 * @wordpress-plugin
 * Plugin Name:       Smart Post Show
 * Plugin URI:        https://smartpostshow.com/
 * Description:       Filter and display posts, pages, taxonomy (categories, tags, & post formats), in beautiful layouts (carousel, grid) easily without coding! Highly customizable and developer-friendly with free active support.
 * Version:           3.0.4
 * Author:            ShapedPlugin LLC
 * Author URI:        https://shapedplugin.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       post-carousel
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Currently plugin version.
 * Start at version 2.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SMART_POST_SHOW_VERSION', '3.0.4' );
define( 'SMART_POST_SHOW_BASENAME', plugin_basename( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-smart-post-show-activator.php
 */
function activate_smart_post_show() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-smart-post-show-activator.php';
	Smart_Post_Show_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-smart-post-show-deactivator.php
 */
function deactivate_smart_post_show() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-smart-post-show-deactivator.php';
	Smart_Post_Show_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_smart_post_show' );
register_deactivation_hook( __FILE__, 'deactivate_smart_post_show' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-smart-post-show.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    2.0.0
 */
function run_smart_post_show() {
	$plugin = new Smart_Post_Show();
	$plugin->run();
}

require_once ABSPATH . 'wp-admin/includes/plugin.php';
if ( ! ( is_plugin_active( 'smart-post-show-pro/smart-post-show-pro.php' ) || is_plugin_active_for_network( 'smart-post-show-pro/smart-post-show-pro.php' ) ) ) {
	run_smart_post_show();
}
