<?php
/**
 * Plugin Name: Variation Auto Expire For WooCommerce
 * Version: 1.0.6
 * Description: You can set date and time for variable product's specific variation auto expiring (delete/out of stock) in WooCommerce/WordPress.
 * Author: Yakacj
 * Author URI: https://profiles.wordpress.org/yakacj/
 * 
 * Requires at least: 6.1
 * Tested up to: 6.4.3
 * 
 * WC requires at least: 6.0
 * WC tested up to: 8.5.2
 * Requires PHP: 7.4
 * 
 * License: GPL version 3 or later - http://www.gnu.org/licenses/old-licenses/gpl-3.0.html
 *
 */
 
    defined( 'ABSPATH' ) or exit;
    
    /**
     * Check WooCommerce is active
     */
    if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        return;
    }
    
    defined( 'YCVEPLUGIN_FILE' ) or define( 'YCVEPLUGIN_FILE', __FILE__ );
    
   /**
    * The core plugin class that is used to define load actions,
    * admin-specific hooks, and public-facing site hooks.
    */
	
	require plugin_dir_path( __FILE__ ) . 'includes/class_ycve_init.php';
	
	/**
	 * Load required files and classes
	 */
	function ycve_init__plugin() {
		$plugin = new \Ycve\Variation\Ycve_Init();
		$plugin->run();
	}
	ycve_init__plugin();
	