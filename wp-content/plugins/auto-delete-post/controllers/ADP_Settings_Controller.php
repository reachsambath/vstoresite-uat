<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Include the singleton trait
require_once AUTO_DELETE_POSTS_PATH . 'core/Singleton.php';

/**
 * Controller class for handling settings logic
 */
class ADP_Settings_Controller {
    use ADP_Singleton;
    
    /**
     * @var ADP_Settings_Model The settings model instance
     */
    private $model;
    
    /**
     * @var ADP_Settings_View The settings view instance
     */
    private $settings_view;

    /**
     * Initialize the controller
     */
    protected function init() {
        // Include and instantiate the model
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/ADP_Settings_Model.php';
        $this->model = new ADP_Settings_Model();
        
        // Include and instantiate the view
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'views/ADP_Settings_View.php';
        $this->settings_view = new ADP_Settings_View();
        
        // Hook into WordPress actions
        add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        // Adding a top-level menu page for Auto Delete Posts with content
        // The main menu will display the settings content
        add_menu_page(
            esc_html__( 'Auto Delete Posts', 'auto-delete-post' ), // page title
            esc_html__( 'Auto Delete Posts', 'auto-delete-post' ), // menu title
            'manage_options', // capability
            'adp-main-menu', // menu slug
            [ $this, 'render_settings_page' ], // callback function to show content
            'dashicons-table-col-delete', // icon to show on menu bar
            2 // position in menu
        );

        // Adding the 'Settings' submenu with all the same content
        // This will appear as a submenu under the main menu
        add_submenu_page(
            'adp-main-menu', // parent slug
            esc_html__( 'Settings', 'auto-delete-post' ), // page title
            esc_html__( 'Settings', 'auto-delete-post' ), // menu title
            'manage_options', // capability
            'adp-settings', // menu slug
            [ $this, 'render_settings_page' ] // callback function
        );
    }
    
    /**
     * Render the settings page
     */
    public function render_settings_page() {
        $this->settings_view->render_settings_page();
    }   
}