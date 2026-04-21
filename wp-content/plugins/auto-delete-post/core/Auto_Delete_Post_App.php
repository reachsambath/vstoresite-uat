<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Include the singleton trait
require_once plugin_dir_path( __FILE__ ) . 'Singleton.php';

/**
 * Main application class implementing the singleton pattern
 */
class Auto_Delete_Post_App {
    use ADP_Singleton;

    /**
     * @var array Registered controllers
     */
    private $controllers = [];
    
    /**
     * @var object Post manager controller instance
     */
    public $post_manager;
    
    /**
     * @var object Settings controller instance
     */
    public $settings;
    
    /**
     * @var object Assets controller instance
     */
    public $assets;

    /**
     * Initialize the application.
     */
    protected function init() {
        $this->setup_constants();
        $this->include_dependencies();
        $this->register_controllers();
        $this->init_controllers();
    }

    /**
     * Setup plugin constants.
     */
    private function setup_constants() {
        if ( ! defined( 'ADP_VERSION' ) ) {
            define( 'ADP_VERSION', '1.1.5' );
        }

        if ( ! defined( 'AUTO_DELETE_POSTS_ASSETS' ) ) {
            define( 'AUTO_DELETE_POSTS_ASSETS', AUTO_DELETE_POSTS_URL . 'assets' );
        }

        if ( ! defined( 'ADP_CSS' ) ) {
            define( 'ADP_CSS', AUTO_DELETE_POSTS_ASSETS . '/css/' );
        }

        if ( ! defined( 'ADP_JS' ) ) {
            define( 'ADP_JS', AUTO_DELETE_POSTS_ASSETS . '/js/' );
        }
    }

    /**
     * Include required dependencies.
     */
    private function include_dependencies() {
        // Include all core models
        $models_dir = plugin_dir_path( __FILE__ ) . '../models/';
        if ( is_dir( $models_dir ) ) {
            foreach ( glob( $models_dir . '*.php' ) as $model_file ) {
                require_once $model_file;
            }
        }

        // Include all core controllers
        $controllers_dir = plugin_dir_path( __FILE__ ) . '../controllers/';
        if ( is_dir( $controllers_dir ) ) {
            foreach ( glob( $controllers_dir . '*.php' ) as $controller_file ) {
                require_once $controller_file;
            }
        }

        // Include all helper functions
        $helpers_dir = plugin_dir_path( __FILE__ ) . '../helpers/';
        if ( is_dir( $helpers_dir ) ) {
            foreach ( glob( $helpers_dir . '*.php' ) as $helper_file ) {
                require_once $helper_file;
            }
        }
    }

    /**
     * Register controllers.
     */
    private function register_controllers() {
        $this->controllers = [
            'post_manager' => 'ADP_Post_Manager_Controller',
            'settings'     => 'ADP_Settings_Controller',
            'assets'       => 'ADP_Assets_Controller',
        ];
    }

    /**
     * Initialize controllers.
     */
    private function init_controllers() {
        foreach ( $this->controllers as $name => $class ) {
            if ( class_exists( $class ) ) {
                $this->$name = call_user_func( [ $class, 'get_instance' ] );
            }
        }
    }

    /**
     * Get a controller instance by name.
     *
     * @param string $name Controller name.
     * @return object|null Controller instance or null if not found.
     */
    public function get_controller( $name ) {
        return isset( $this->$name ) ? $this->$name : null;
    }

    /**
     * Get the plugin URL.
     *
     * @return string Plugin URL.
     */
    public function get_plugin_url() {
        return plugin_dir_url( dirname( dirname( __FILE__ ) ) );
    }

    /**
     * Get the plugin path.
     *
     * @return string Plugin path.
     */
    public function get_plugin_path() {
        return plugin_dir_path( dirname( dirname( __FILE__ ) ) );
    }
}