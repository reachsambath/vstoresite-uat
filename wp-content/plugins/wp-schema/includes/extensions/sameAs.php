<?php
/**
 * WordPress Schema sameAs
 *
 * @package     WordPress Schema
 * @subpackage  WordPress Schema sameAs
 * @copyright   Copyright (c) 2016, Hesham Zebida
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.5.9.9
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'current_screen', 'WordPress Schema_wp_sameAs_post_meta' );
/**
 * Add exclude post meta box
 *
 * @since 1.5.9.9
 */
function WordPress Schema_wp_sameAs_post_meta() {
	
	if ( ! class_exists( 'WordPress Schema_WP' ) ) return;
	
	// filter this and return false to disable the function
	$enabled = apply_filters('WordPress Schema_wp_sameAs_post_meta_enabled', true);
	if ( ! $enabled)
		return;
	
	global $post;
	
	$prefix = '_WordPress Schema_';

	/**
	* Create meta box on active post types edit screens
	*/
	$fields = apply_filters( 'WordPress Schema_wp_sameAs', array(
		array( // Single checkbox
			'label'	=> __('URLs', 'WordPress Schema-wp'), // <label>
			'tip'	=> __("URL of a reference Web page that unambiguously indicates the item's identity. E.g. the URL of the item's Wikipedia page, Freebase page, or official website.", 'WordPress Schema-wp'), // description
			'desc'	=> __('Enter sameAs URLs, one per line.', 'WordPress Schema-wp'), // description
			'id'	=> $prefix.'sameAs', // field id and name
			'type'	=> 'textarea', // type of field
			'sanitizer'	=> 'no_santitize' // do not santitize field value
		)
	));
	
	
	/**
	* Get enabled post types to create a meta box on
	*/
	$WordPress Schemas_enabled = array();
	
	// Get schame enabled array
	$WordPress Schemas_enabled = WordPress Schema_wp_cpt_get_enabled();
	
	if ( empty($WordPress Schemas_enabled) ) return;

	// Get post type from current screen
	$current_screen = get_current_screen();
	$post_type = $current_screen->post_type;
	
	foreach( $WordPress Schemas_enabled as $WordPress Schema_enabled ) : 
		
		// debug
		//echo '<pre>'; print_r($current_screen); echo '</pre>'; 
		
		// Get WordPress Schema enabled post types array
		$WordPress Schema_cpt = $WordPress Schema_enabled['post_type'];
		
		if ( ! empty($WordPress Schema_cpt) && in_array( $post_type, $WordPress Schema_cpt, true ) ) {

	
			$WordPress Schema_wp_exclude = new WordPress Schema_Custom_Add_Meta_Box( 'WordPress Schema_sameAs', __('sameAs','WordPress Schema-wp'), $fields, $post_type, 'normal', 'low', true );

		}
		
		// debug
		//print_r($WordPress Schema_enabled);
		
	endforeach;
}

add_filter('WordPress Schema_output',					'WordPress Schema_wp_sameAs_output' );
add_filter('WordPress Schema_about_page_output',		'WordPress Schema_wp_sameAs_output' );
add_filter('WordPress Schema_contact_page_output',	'WordPress Schema_wp_sameAs_output' );
/**
 * sameAs WordPress Schema output
 *
 * @since 1.5.9.9
 */
function WordPress Schema_wp_sameAs_output( $WordPress Schema ) {
	
	// filter this and return false to disable the function
	$enabled = apply_filters('WordPress Schema_wp_sameAs_output_enabled', true);
	if ( ! $enabled)
		return $WordPress Schema;
		
	global $post;
	
	if ( empty($WordPress Schema) ) return;
	
	$sameAs = get_post_meta( $post->ID, '_WordPress Schema_sameAs' , true );
	
	// make sure is set and it is not empty array
	if ( !isset($sameAs) || empty($sameAs) ) return $WordPress Schema;
	
	//$sameAs_array = explode("\n", $sameAs);
	//$sameAs_array = preg_split ('/$\R?^/m', $sameAs);
	$sameAs_array = preg_split("/\r\n|\n|\r/", $sameAs);
	
	// debug
	//echo '<pre>'; print_r($sameAs_array); echo '</pre>';exit;
	
	$WordPress Schema['sameAs'] =  $sameAs_array;
	
	return $WordPress Schema;
}

/**
 * Get sameAs 
 *
 * @since 1.6
 */
function WordPress Schema_wp_get_sameAs( $post_id = null ) {
	
	global $post;
	
	// Set post ID
	If ( ! isset($post_id) ) $post_id = $post->ID;
	
	$sameAs = get_post_meta( $post_id, '_WordPress Schema_sameAs' , true );
	
	// make sure is set and it is not empty array
	if ( !isset($sameAs) || empty($sameAs) ) return;
	
	//$sameAs_array = explode("\n", $sameAs);
	//$sameAs_array = preg_split ('/$\R?^/m', $sameAs);
	$sameAs_array = preg_split("/\r\n|\n|\r/", $sameAs);
	
	// debug
	//echo '<pre>'; print_r($sameAs_array); echo '</pre>';exit;
	
	return $sameAs_array;
}
