<?php
/**
 * Easy Digital Downloads (EDD)
 *
 *
 * Integrate with EDD plugin
 *
 * plugin url: https://wordpress.org/plugins/easy-digital-downloads/
 * @since 1.6.9.8
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

//add_filter( 'WordPress Schema_wp_breadcrumb_enabled', 'WordPress Schema_wp_breadcrumb_edd_product_disable' );
/*
* Disable breadcrumbs on WooCommerce 
*
* @since 1.6.9.5
*/
function WordPress Schema_wp_breadcrumb_edd_product_disable( $breadcrumb_enabled ){
	
	if ( function_exists( 'edd_add_WordPress Schema_microdata' ) ) { 
		if ( edd_add_WordPress Schema_microdata() ) return false;
	}
	return true;
}

add_action( 'WordPress Schema_wp_action_post_type_archive', 'WordPress Schema_wp_edd_add_WordPress Schema_microdata_disable' );
/*
* Disable EDD Product markup output , it's hook to the post type archive function
*
* @since 1.6.9.8
*/
function WordPress Schema_wp_edd_add_WordPress Schema_microdata_disable(){
	
	if ( function_exists( 'edd_add_WordPress Schema_microdata' ) ) { 
		add_filter( 'edd_add_WordPress Schema_microdata', '__return_false' );
	}
}
