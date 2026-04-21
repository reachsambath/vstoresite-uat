<?php

/**
 * Plugin admin class
 *
 * @since       1.0.0
 *
 * @package     ycve
 * @subpackage  ycve/admin
 * @author      yakacj
 * @link        https://profiles.wordpress.org/yakacj/
 */

namespace Ycve\Variation;
defined( 'ABSPATH' ) or exit;

class Ycve_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since       1.0.0
	 * @param      string    $plugin_name  The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	 
	private $link;
	
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->link        = esc_url( add_query_arg( ['page' => 'wc-settings', 'tab' => 'yctimezones'], admin_url( 'admin.php?' ) ) );

	}
    
    /**
     * Add sub menu under woocommerce menu
     */
    public function ycve_timezones_wc_submenu() {
        $timezone	= get_option( 'ycve_timezone' ) ? get_option( 'ycve_timezone' ) : '';
        $zone       = $this->link;
        
        if( $timezone ){
            $cont = explode( '/', $timezone );
            if( !empty( $cont ) && is_array( $cont) ){
              $zone .= '&section='.reset( $cont );
            }
        }
        
        add_submenu_page( 'woocommerce', 'Timezones', 'Timezones', 'manage_woocommerce', $zone ); 
    }
    
    /**
     * Timezones in plugin settings link
     */
    public function ycve_plugin_action_links( $links, $plugin_file ) {
        $settings_links = array();
        
	   if( plugin_basename( YCVEPLUGIN_FILE ) == $plugin_file ) {
	        $settings_links['ycve-timezones'] = sprintf( __( '<a href="%s">Timezones</a>', 'ycve' ), $this->link );
    
	    }
	    return array_merge( $settings_links, $links );
    }
    
    /**
     * Show fields to variations
     * 
     * @access  public
     * @since   1.0.0
     * @param   int $loop
     * @param   array $variation_data
     * @param   array $variation
     * @return  html data
     */
    public function ycve_add_custom_field_to_variations( $loop, $variation_data, $variation ) {
        
        $type           = get_post_meta( $variation->ID, '_yc_exp_type', true );
        $type           = $type == 1 ? 'checked="checked"' : '' ;
        $action_date    = get_post_meta( $variation->ID, '_yc_exp_date', true );
        $timezone		= get_option( 'ycve_timezone' ) ? get_option( 'ycve_timezone' ): __( 'Not selected', 'ycve' ).' <a href="'.$this->link.'">'.__('Select', 'ycve').'</a>';
        
        ?>
        <style>.form-row .woocommerce-help-tip {float: right;font-size: 1.4em;</style>
		<p class="form-row form-row-full yc_exp_timezone">
		    <span style="font-weight: 600;border: 1px solid #FFA500;padding: 5px;"><?php echo wp_kses_post( sprintf( '%1$s %2$s', __( 'Current timezone:', 'ycve' ), $timezone ) );?></span>
        </p>
        <p class="form-row form-row-full yc_exp_type"><label class="tips" data-tip="<?php esc_attr_e( 'Select this variation action delete or out of stock, checked means delete', 'ycve' ); ?>">
            <input type="checkbox" id="yc_exp_type[<?php echo esc_attr( $loop );?>]" name="yc_exp_type[<?php echo esc_attr( $loop );?>]" value="1" <?php echo esc_attr( $type );?>> <?php esc_html_e( 'Delete?', 'ycve' ); ?></label>
        </p>
        <p class="form-row form-row-full yc-exp-date"><label for="yc_exp_date"><?php esc_attr_e( 'Action date-time', 'ycve' );?></label> <?php _e( wc_help_tip( "Select this variation delete date-time" ), 'ycve' );?>
            <input type="datetime-local" id="yc_exp_date[<?php echo esc_attr( $loop );?>]" name="yc_exp_date[<?php echo esc_attr( $loop );?>]"  value="<?php echo esc_attr( $action_date );?>">
        </p>
        <?php
    }
    
    /**
     * Save fields
     * 
     * @access  public
     * @since   1.0.0
     * @param   int $variation_id
     * @param   int $i
     * @return  void
     */
    public function ycve_save_exp_date_variations( $variation_id, $i ) {
        
        $exp_type 		= sanitize_text_field( $_POST['yc_exp_type'][ $i ] );
        $exp_type_val 	= ($exp_type == 1) ? 1 : '' ;
        
        update_post_meta( $variation_id, '_yc_exp_type', $exp_type_val );
        
        $exp_value  = '';
        $exp_date   = sanitize_text_field( $_POST['yc_exp_date'][ $i ] );
        $check_date = date_parse( $exp_date );
        
        if( isset( $exp_date ) && $check_date['error_count'] == 0 ) $exp_value = $exp_date;
        
        update_post_meta( $variation_id, '_yc_exp_date', $exp_value );
        
    }

}



