<?php

/**
 * The core plugin class.
 *
 * This is used to define admin-specific hooks and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    ycve
 * @subpackage ycve/includes
 * @author     Yakacj
 */
namespace Ycve\Variation;
defined( 'ABSPATH' ) or exit;

class Ycve_Init {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;
	

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
	    
	    define( 'YCVE_VERSION', $this->get_yc_plugin_version() );
	    
		$this->plugin_name = 'ycve';
		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}
    
   /**
    * Currently plugin version.
    * Start at version 1.0.0 and use SemVer - https://semver.org
    *
    * @since    1.0.0
	* @access   private
	* @return   string    $plugin_version    Getting from plugin data
    */
    private function get_yc_plugin_version(){
        
	    if( ! function_exists( 'get_plugin_data' ) ){
		    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	    }
	        $plugin_data 	= get_plugin_data( YCVEPLUGIN_FILE );
	        $plugin_version = isset( $plugin_data['Version'] ) ? $plugin_data['Version'] : '1.0.0';
	        
        return $plugin_version;
    }
    
	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - includes/loader class. Orchestrates the hooks of the plugin.
	 * - admin/admin class. Defines all hooks for the admin area.
	 * - public/public class. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class_ycve_loader.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
	    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class_ycve_admin.php';
	    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class_ycve_timezones.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class_ycve_public.php';

		$this->loader = new \Ycve\Variation\Ycve_Loader();
	

	}
	

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new \Ycve\Variation\Ycve_Admin( $this->get_plugin_name(), $this->get_version() );
        $settings = new \Ycve\Timezones\Ycve_Timezones();
		
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'ycve_timezones_wc_submenu', 99 );
        $this->loader->add_filter( 'plugin_action_links', $plugin_admin, 'ycve_plugin_action_links', 10, 2 );
        $this->loader->add_action( 'woocommerce_variation_options_pricing', $plugin_admin, 'ycve_add_custom_field_to_variations', 10, 3 );
        $this->loader->add_action( 'woocommerce_save_product_variation', $plugin_admin, 'ycve_save_exp_date_variations', 10, 2 );

    }

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new \Ycve\Variation\Ycve_Public( $this->get_plugin_name(), $this->get_version() );
			
        $this->loader->add_filter( 'woocommerce_available_variation', $plugin_public,'ycve_filter_available_variations' );
		$this->loader->add_action('woocommerce_add_to_cart_validation', $plugin_public, 'add_to_cart_validation', 10, 4 );
        $this->loader->add_action( 'woocommerce_after_checkout_validation', $plugin_public,'ycve_variation_validate_dt', 10, 2 );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    includes/loader class    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
