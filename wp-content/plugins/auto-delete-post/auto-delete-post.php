<?php
/**
 * @package AutoDeletePost
 *
 * Plugin Name:       Auto Delete Post
 * Plugin URI:        https://wordpress.org/plugin/auto-delete-post
 * Description:       This plugin automatically deletes a post after a certain time
 * Version:           1.1.8
 * Requires at least: 5.2
 * Tested up to:      6.8.3
 * Requires PHP:      7.2
 * Author:            Shahadat Hossain
 * Author URI:        https://www.linkedin.com/in/palash-wp/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       auto-delete-post
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) exit; // EXIT IF ACCESSED DIRECTLY

// Define the main plugin file constant for use throughout the plugin
if ( ! defined( 'AUTO_DELETE_POSTS_FILE' ) ) {
    define( 'AUTO_DELETE_POSTS_FILE', __FILE__ );
}

if ( ! defined( 'AUTO_DELETE_POSTS_URL' ) ) {
    define( 'AUTO_DELETE_POSTS_URL', plugin_dir_url( __FILE__ ) );	
    define( 'AUTO_DELETE_POSTS_PATH', plugin_dir_path( __FILE__ ) );	
}

/**
 * Initialize the plugin using the MVC and Singleton pattern
 */
function adp_initialize_plugin() {
    // Include Composer autoloader
    if ( file_exists( plugin_dir_path( __FILE__ ) . 'vendor/autoload.php' ) ) {
        require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
    }
    
    // Initialize the main application
    $app = Auto_Delete_Post_App::get_instance();
    
    // Initialize helpers that need to run independently
    ADP_Column_Helper::get_instance();
    ADP_Quick_Edit_Helper::get_instance();
    ADP_Post_Restoration_Helper::get_instance();
}

// Initialize the plugin
add_action( 'plugins_loaded', 'adp_initialize_plugin' );