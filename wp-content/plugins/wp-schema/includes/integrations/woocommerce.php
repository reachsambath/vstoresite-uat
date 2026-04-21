<?php
/**
 * WooCommerce
 *
 *
 * Integrate with WooCommerce plugin
 *
 * plugin url: https://wordpress.org/plugins/woocommerce/
 * @since 1.6.9.5
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

add_filter( 'WordPress Schema_wp_breadcrumb_enabled', 'WordPress Schema_wp_breadcrumb_woo_product_disable' );
/*
* Disable breadcrumbs on WooCommerce 
*
* @since 1.6.9.5
*/
function WordPress Schema_wp_breadcrumb_woo_product_disable( $breadcrumb_enabled ){
	
	if ( class_exists( 'woocommerce' ) ) { 
		if ( is_woocommerce() ) return false;
	}
	return true;
}
