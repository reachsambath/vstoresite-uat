<?php
/**
 * Plugin admin timezones class
 * 
 * Used when local time and server time are different.
 * User can specify date-time by selecting appropriate 
 * time zone.
 * 
 * @since       1.0.2
 *
 * @package     ycve
 * @subpackage  ycve/includes
 * @author      yakacj
 * @link        https://profiles.wordpress.org/yakacj/
 */
 
namespace Ycve\Timezones;
defined( 'ABSPATH' ) or exit;

class Ycve_Timezones{

    public function __construct() {
  
        add_filter( 'woocommerce_settings_tabs_array', array( $this, 'ycve_woocommerce_settings_tabs' ), 99 );
        add_action( 'woocommerce_sections_yctimezones', array( $this, 'ycve_woocommerce_sections_yctimezones' ), 10 );
        add_action( 'woocommerce_settings_yctimezones', array( $this, 'ycve_woocommerce_settings_yctimezones' ), 10 );
        add_action( 'woocommerce_settings_save_yctimezones', array( $this, 'ycve_woocommerce_settings_save_yctimezones' ), 10 );
    }
    
        /**
         * Add timezone tab to WooCommerce settings
         * 
         * @since    1.0.0
	     * @access   public
	     * @param    array new tab
	     * @return   array $settings_tabs
         */
        public function ycve_woocommerce_settings_tabs( $settings_tabs ) {
            $settings_tabs['yctimezones'] = __( 'Time-zones', 'ycve' );
            return $settings_tabs;
        }
    
        /**
         * Get system date time zones
         * 
         * @since    1.0.0
	     * @access   private
	     * @return   array $cont
         */
        private function ycve_get_tmz(){
            $timezones = \DateTimeZone::listAbbreviations();
            $cont = array();
            
            foreach( $timezones as $key => $zones ){ 
                
                foreach( $zones as $id => $zone ){
                    
                    $tz_id = isset( $zone['timezone_id'] ) ? $zone['timezone_id'] : '';
                    
                    if ( $tz_id && preg_match( '/^(Africa|America|Antartica|Arctic|Asia|Atlantic|Australia|Europe|Indian|Pacific)\//', $tz_id)) {
                        
                        $t = explode( '/', $tz_id);
                        $ti = reset( $t );
                        
                        if( $ti ){
                            $cont[ $ti ][ $tz_id ] = $tz_id;
                        }
                    }
                }
            }
    
            return $cont;
        }
        
        /**
         * Get timezone sectins to WooCommerce settings
         * 
         * @since    1.0.0
	     * @access   public
	     * @param    array new tab
	     * @return   array $settings_tabs
         */
        public function ycve_woocommerce_sections_yctimezones() {
            global $current_section;
        
            $selected 	= get_option( 'ycve_timezone' ) ? get_option( 'ycve_timezone' ) : __( 'none', 'ycve' );
            $tab_id 	= 'yctimezones';
            $sections 	= array();
        
            foreach( $this->ycve_get_tmz() as $k => $n){
                $sections[] = $k;
            }
    
            asort( $sections);
            ?>
            <ul class="subsubsub">

            <?php if( ! $current_section ) $current_section = 'africa';?>
            <?php foreach ( $sections as $id => $label ) :?>
                    
                <li><a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-settings&tab=' . $tab_id . '&section='.sanitize_title( $label )));?>" class="<?php  echo esc_attr( $current_section == sanitize_title( $label ) ? 'current' : '' );?>"><?php echo esc_attr( $label );?></a><?php echo esc_attr( end( $sections ) == $label ? '' : '|') ;?></li>
               
            <?php endforeach ;?>
            </ul><br class="clear" />
            <h4><?php echo esc_attr__( 'Current selected','ycve' ) .' : '. esc_attr( $selected );?></h4>
            <?php
    
        }

        /**
         * Create fields for timezone settings
         * 
         * @since    1.0.0
	     * @access   private
	     * @return   array $settings
         */
        private function get_yctimezones_settings() {
            global $current_section;
        
            $settings = $time_zones = $none = array();
            
            $none[''] = __( 'Select', 'ycve' );
            
            foreach( $this->ycve_get_tmz() as $k => $n){
                $zones = array_merge( $none, $n );
                $time_zones[ $k ]= $zones;
            }

            $values = array_keys( $time_zones );

            if ( $current_section && in_array( ucfirst( $current_section ) , $values )) {
       
                $settings = array(
                
                    array(
                        'title'     => '',
                        'type'      => 'title',
                        'id'        => 'yc_time_zone_title'
                    ),
                
                    array(
                        'title'     => __( 'Select time zone', 'ycve' ),
                        'type'      => 'select',
                        'desc'      => __( 'Select time zone if local time and server time is different', 'ycve' ),
                        'desc_tip'  => true,
                        'id'        => 'ycve_timezone',
                        'class'     => 'wc-enhanced-select',
                        'css'       => 'min-width:300px;',
                        'options'   =>  $time_zones[ ucfirst( $current_section ) ]
                    ),

                    array(
                        'type'      => 'sectionend',
                        'id'        => 'yc_time_zone_title'
                    ),
                );
                
            } else {
        
                $settings = array(
                
                    array(
                        'title'     => __( 'Africa', 'ycve' ),
                        'type'      => 'title',
                        'id'        => 'yc_time_zone_title'
                    ),
                
                    array(
                        'title'     => __( 'Select time zone', 'ycve' ),
                        'type'      => 'select',
                        'desc'      => __( 'Select time zone if local time and server time is different', 'ycve' ),
                        'desc_tip'  => true,
                        'id'        => 'ycve_timezone',
                        'class'     => 'wc-enhanced-select',
                        'css'       => 'min-width:300px;',
                        'options'   =>  $time_zones['Africa']
                    ),
                
                    array(
                        'type'      => 'sectionend',
                        'id'        => 'yc_time_zone_title'
                    ),
                );
            }

            return $settings;
        }

        /**
         * Send created settings fields to WooCommerce
         * 
         * @since    1.0.0
	     * @access   public
	     * @return   void
         */
        public function ycve_woocommerce_settings_yctimezones() {
            $settings = $this->get_yctimezones_settings();
            \WC_Admin_Settings::output_fields( $settings );  
        }

        /**
         * Save timezone settings
         * 
         * @since    1.0.0
	     * @access   public
	     * @param    array new tab
	     * @return   void
         */
        public function ycve_woocommerce_settings_save_yctimezones() {
            global $current_section;
        
            $tab_id = 'yctimezones';
            $settings = $this->get_yctimezones_settings();
        
            \WC_Admin_Settings::save_fields( $settings );
        
            if ( $current_section ) {
                do_action( 'woocommerce_update_options_' . $tab_id . '_' . $current_section );
            }
        }
    
}
