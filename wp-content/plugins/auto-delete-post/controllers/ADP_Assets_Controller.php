<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Include the singleton trait
require_once AUTO_DELETE_POSTS_PATH . 'core/Singleton.php';

/**
 * Controller class for handling assets management
 */
class ADP_Assets_Controller {
    use ADP_Singleton;

    /**
     * Initialize the controller
     */
    protected function init() {
        // Hook into WordPress actions
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
    }

    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets() {    
        wp_enqueue_style( 'select2', ADP_CSS . 'select2.min.css', [], ADP_VERSION, 'all' );   
        wp_enqueue_style( 'adp-style', ADP_CSS . 'style.css', [], ADP_VERSION, 'all' );
        
        wp_enqueue_script(
            'select2',
            ADP_JS . 'select2.min.js',
            ['jquery'],
            null,
            true
        );
        wp_enqueue_script(
            'adp-script',
            ADP_JS . 'adp-quick-edit.js',
            ['jquery'],
            ADP_VERSION,
            true
        );
        wp_enqueue_script(
            'adp-admin-script',
            ADP_JS . 'admin.js',
            ['jquery','select2'],
            ADP_VERSION,
            true
        );
    }
}