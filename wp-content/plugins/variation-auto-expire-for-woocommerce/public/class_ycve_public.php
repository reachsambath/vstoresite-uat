<?php

/**
 * Plugin public class
 *
 * @since       1.0.0
 * @package     ycve
 * @subpackage  ycve/public
 * @author      yakacj
 * @link        https://profiles.wordpress.org/yakacj/
 */

namespace Ycve\Variation;
defined( 'ABSPATH' ) or exit;

class Ycve_Public {

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
	 * @since   1.0.0
	 * @param   string    $plugin_name   The name of the plugin.
	 * @param   string    $version   The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name 	= $plugin_name;
		$this->version 		= $version;
	}
    /**
     * Add to cart validation
     * 
     * @since	1.0.6
	 * @param 	bool 	$passed
     * @param 	int 	$product_id
     * @param 	int 	$quantity
     * @param 	int 	$variation_id
     * @return 	bool 	$passed
     */
    public function add_to_cart_validation( $passed, $product_id, $quantity, $variation_id = null ){
	    $verify = $variation_id ? $variation_id : $product_id;
        $this->ycve_check_exp_date_to( $verify );
        $product  = wc_get_product( $verify );
        if( $product ) return $product->is_purchasable();
	    return $passed;
    }
	
    /**
     * Check date is valid
     * 
     * @access  private
     * @since   1.0.0
     * @param   string  date-time value
     * @return  bool
     */
    private function ycve_is_valid_date( $value ) {
        if ( ! $value ) return false;
        try {
            new \DateTime( $value );
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Set action according to date-time by id
     * 
     * @access  private
     * @since   1.0.0
     * @param   int $id variation id
     * @return  void
     */
    private function ycve_check_exp_date_to( $id ){
        
        $action_type        = get_post_meta( $id, '_yc_exp_type', true );
        $action_date        = get_post_meta( $id, '_yc_exp_date', true);
        $custom_timezone    = get_option( 'ycve_timezone' );
        
        if( $custom_timezone ) {
            date_default_timezone_set( $custom_timezone );
        }

        $now_time           = date( "Y-m-d\TH:i" );
        $var_date           = date( "Y-m-d\TH:i", strtotime( $action_date ) );
        $variation_product  = wc_get_product( $id );
    
        if( ! empty( $action_date ) && $this->ycve_is_valid_date( $action_date ) ) {

            if( $variation_product && $now_time >= $var_date ){
                if( $action_type ){
                    $variation_product->delete( true );
                } else {
                    $variation_product->set_stock_quantity('');
                    $variation_product->set_stock_status( 'outofstock' );
                    $variation_product->save();
                    
                    wp_set_post_terms( $id, 'outofstock', 'product_visibility', true );
                    wc_delete_product_transients( $id );
              }

            }
        }
    }
    
    /**
     * Filter variations
     * 
     * @access  public
     * @since   1.0.0
     * @param   Array   $variations
     * @return  Array   $variations
     */
    public function ycve_filter_available_variations( $variations ) {
        $this->ycve_check_exp_date_to( $variations['variation_id'] );
        return $variations;
    }
    
    /**
     * Check at checkout if any
     * 
     * @access  public
     * @since   1.0.0
     * @param   Array   $fields
     * @param   Object  $errors
     */
    public function ycve_variation_validate_dt( $fields, $errors ){
 
        foreach ( WC()->cart->cart_contents as $key => $item ) {
            
            $product_id = $item['product_id'];
            $product    = wc_get_product( $product_id );
            
            // Check if the product is a variable product
            if ( $product->is_type( 'variable' ) ) {
                $variations = $product->get_children();
            
                if( ! empty( $variations ) ){
                    foreach( $variations as $variation ){
                        $this->ycve_check_exp_date_to( $variation );
                    }
                }
				
            }
            
        }
	
    }
 
}

