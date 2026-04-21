<?php
/**
 * Exclude post from WordPress Schema
 *
 * @package     WordPress Schema
 * @subpackage  WordPress Schema Post Meta
 * @copyright   Copyright (c) 2016, Hesham Zebida
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.5.6
 */


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


add_action( 'current_screen', 'WordPress Schema_wp_exclude_post_meta' );
/**
 * Add exclude post meta box
 *
 * @since 1.5.6
 */
function WordPress Schema_wp_exclude_post_meta() {
	
	if ( ! class_exists( 'WordPress Schema_WP' ) ) return;
	
	global $post;
	
	$prefix = '_WordPress Schema_';

	/**
	* Create meta box on active post types edit screens
	*/
	$fields = apply_filters( 'WordPress Schema_wp_exclude', array(
		array( // Single checkbox
			'label'	=> __('Turn WordPress Schema OFF', 'WordPress Schema-wp'), // <label>
			'desc'	=> __('Tick this checkbox to turn off WordPress Schema output on this entry.', 'WordPress Schema-wp'), // description
			'id'	=> $prefix.'exclude', // field id and name
			'type'	=> 'checkbox' // type of field
		),
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

	
			$WordPress Schema_wp_exclude = new WordPress Schema_Custom_Add_Meta_Box( 'WordPress Schema_exclude', __('WordPress Schema Exclude','WordPress Schema-wp'), $fields, $post_type, 'normal', 'low', true );

		}
		
		// debug
		//print_r($WordPress Schema_enabled);
		
	endforeach;
}
