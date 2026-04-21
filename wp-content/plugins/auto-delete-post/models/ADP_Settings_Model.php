<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Model class for handling plugin settings
 */
class ADP_Settings_Model {
    
    /**
     * Option key for selected post types
     */
    const OPTION_KEY = 'auto_delete_post_option';
    
    /**
     * Option key for delete method
     */
    const DELETE_OPTION_KEY = 'adp_delete_option';

    /**
     * Get the selected post types for auto deletion
     *
     * @return array Selected post types
     */
    public function get_selected_post_types() {
        return get_option( self::OPTION_KEY, [] );
    }

    /**
     * Save selected post types for auto deletion
     *
     * @param array $post_types Selected post types
     * @return bool True on success, false on failure
     */
    public function save_selected_post_types( $post_types ) {
        return update_option( self::OPTION_KEY, $post_types );
    }

    /**
     * Get the current deletion method option
     *
     * @return string Current deletion method ('move_to_trash' or 'delete_permanently')
     */
    public function get_delete_option() {
        return get_option( self::DELETE_OPTION_KEY, 'move_to_trash' );
    }

    /**
     * Save the deletion method option
     *
     * @param string $option Deletion method ('move_to_trash' or 'delete_permanently')
     * @return bool True on success, false on failure
     */
    public function save_delete_option( $option ) {
        return update_option( self::DELETE_OPTION_KEY, $option );
    }

    /**
     * Option key for redirect settings
     */
    const REDIRECT_OPTION_KEY = 'adp_redirect_option';

    /**
     * Get the redirect settings
     *
     * @return array Redirect settings
     */
    public function get_redirect_settings() {
        return get_option( self::REDIRECT_OPTION_KEY, [] );
    }

    /**
     * Save redirect settings
     *
     * @param array $settings Redirect settings
     * @return bool True on success, false on failure
     */
    public function save_redirect_settings( $settings ) {
        return update_option( self::REDIRECT_OPTION_KEY, $settings );
    }
}