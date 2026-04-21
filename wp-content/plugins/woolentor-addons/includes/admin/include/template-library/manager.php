<?php  
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Template_Library_Manager{

    // Remote URLs
    const REST_ROUTE_URL = 'https://library.shoplentor.com/wp-json/woolentor';
    const BACKUP_REST_ROUTE_URL = 'https://library-backup.shoplentor.com/wp-json/woolentor';

    // Transient Key
    const TRANSIENT_KEYES = [
        'template'  => 'woolentor_template_info',
        'gutenberg' => 'woolentor_gutenberg_template_info',
        'pattern'   => 'woolentor_gutenberg_patterns_info'
    ];

    // API Endpoint
    const API_ENDPOINT = [
        'template'      => 'v1/templates',
        'singletemplate'=> 'v1/templates/%s',
        'gutenberg'     => 'v1/gutenbergtemplates',
        'pattern'       => 'v1/gutenbergpatterns'
    ];

    private static $_instance = null;
    
    /**
     * Class Instance
     */
    public static function instance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Get Template Endpoint with backup support
     */
    public static function get_api_endpoint(){
        if( is_plugin_active('woolentor-addons-pro/woolentor_addons_pro.php') && function_exists('woolentor_pro_template_endpoint') ){
            return woolentor_pro_template_endpoint();
        }
        return self::get_remote_urls('template');
    }

    /**
     * Get Template API with backup support
     * @todo We will remove in Future
     */
    public static function get_api_templateapi(){
        if( is_plugin_active('woolentor-addons-pro/woolentor_addons_pro.php') && function_exists('woolentor_pro_template_url') ){
            return woolentor_pro_template_url();
        }
        return self::get_remote_urls('singletemplate');
    }

    /**
     * Delete Remote Data Fetching cache
     * @return void
     */
    public static function delete_transient_cache_data(){
        if ( get_option( 'woolentor_delete_data_fetch_cache', FALSE ) ) {
            foreach( self::TRANSIENT_KEYES as $transient_key ){
                delete_transient( $transient_key );
            }
            delete_option('woolentor_delete_data_fetch_cache');
        }
    }

    /**
     * Get Remote URL (single URL for backward compatibility)
     *
     * @param [type] $name
     */
    public static function get_remote_url( $name ){
        return sprintf('%s/%s', self::REST_ROUTE_URL, self::API_ENDPOINT[$name]);
    }

    /**
     * Get Remote URLs (primary and backup)
     *
     * @param [type] $name
     * @return array
     */
    public static function get_remote_urls( $name ){
        $primary_url = sprintf('%s/%s', self::REST_ROUTE_URL, self::API_ENDPOINT[$name]);
        $backup_url = sprintf('%s/%s', self::BACKUP_REST_ROUTE_URL, self::API_ENDPOINT[$name]);
        
        return [
            'primary' => $primary_url,
            'backup'  => $backup_url
        ];
    }

    /**
     * Set data to transient with backup server support
     *
     * @param string|array $url
     * @param string $transient_key
     * @param boolean $force_update
     */
    public static function set_templates_info( $url = '', $transient_key = '', $force_update = false ) {
        $transient = get_transient( $transient_key );
        if ( ! $transient || $force_update ) {
            $info = self::get_content_remote_request_with_backup( $url );
            set_transient( $transient_key, wp_json_encode( $info ), WEEK_IN_SECONDS );
        }
    }

    /**
     * Get Remote Template List with backup support
     *
     * @param [type] $type
     * @param [type] $endpoint
     * @param boolean $force_update
     */
    public static function get_template_remote_data( $type, $endpoint = null, $force_update = false ){
        // self::delete_transient_cache_data();

        $transient_key  = self::TRANSIENT_KEYES[$type];
        
        if( $endpoint === null ){
            $endpoint = self::get_remote_urls($type);
        }
        
        if ( !get_transient( $transient_key ) || $force_update ) {
            self::set_templates_info( $endpoint, $transient_key, true );
        }
        return is_array( get_transient( $transient_key ) ) ? get_transient( $transient_key ) : json_decode( get_transient( $transient_key ), JSON_OBJECT_AS_ARRAY );
    }

    /**
     * Get Template List with backup support
     *
     * @param boolean $force_update
     */
    public static function get_templates_info($force_update = false) {
        return self::get_template_remote_data('template', self::get_api_endpoint(), $force_update);
    }

    /**
     * Get Gutenberg Template List with backup support
     *
     * @param boolean $force_update
     */
    public static function get_gutenberg_templates_info($force_update = false) {
        return self::get_template_remote_data('gutenberg', self::get_remote_urls('gutenberg'), $force_update);
    }

    /**
     * Get Gutenberg Patterns list with backup support
     *
     * @param boolean $force_update
     */
    public static function get_gutenberg_patterns_info($force_update = false) {
        return self::get_template_remote_data('pattern', self::get_remote_urls('pattern'), $force_update);
    }

    /**
     * Get Template content by Template ID with backup support
     *
     * @param [type] $type template | gutenberg | pattern
     * @param [type] $template_id
     */
    public static function get_template_data( $type, $template_id ){
        $template_urls = self::get_remote_urls($type);
        $templateurl_primary = sprintf( '%s/%s', $template_urls['primary'], $template_id);
        $templateurl_backup = sprintf( '%s/%s', $template_urls['backup'], $template_id);
        
        $response_data = self::get_content_remote_request_with_backup([
            'primary' => $templateurl_primary,
            'backup'  => $templateurl_backup
        ]);
        
        return $response_data;
    }

    /**
     * Handle remote request with backup server support
     *
     * @param string|array $request_url
     * @return array
     */
    public static function get_content_remote_request_with_backup( $request_url ){
        global $wp_version;

        $urls = [];
        
        // Handle different URL formats
        if( is_string( $request_url ) ){
            // Single URL - create backup URL
            $urls['primary'] = $request_url;
            $urls['backup'] = str_replace( self::REST_ROUTE_URL, self::BACKUP_REST_ROUTE_URL, $request_url );
        } elseif( is_array( $request_url ) ){
            // Array of URLs
            $urls = $request_url;
        } else {
            return [];
        }

        // Try primary URL first
        if( isset( $urls['primary'] ) ){
            $response = self::get_content_remote_request( $urls['primary'] );
            if( !empty( $response ) ){
                return $response;
            }
        }

        // Try backup URL if primary fails
        if( isset( $urls['backup'] ) ){
            $response = self::get_content_remote_request( $urls['backup'] );
            if( !empty( $response ) ){
                return $response;
            }
        }

        // If both fail, return empty array
        return [];
    }

    /**
     * Handle single remote request
     *
     * @param [type] $request_url
     */
    public static function get_content_remote_request( $request_url ){
        global $wp_version;

        $response = wp_remote_get( 
			$request_url,
			array(
				'timeout'    => 25,
				'user-agent' => 'WordPress/' . $wp_version . '; ' . home_url()
			) 
		);

        if ( is_wp_error( $response ) || 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
            return [];
        }

        $result = json_decode( wp_remote_retrieve_body( $response ), true );
        return $result;
    }
}