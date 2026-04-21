<?php
/**
 * Generate post meta fields
 *
 * @package     WordPress Schema
 * @subpackage  WordPress Schema Post Meta
 * @copyright   Copyright (c) 2016, Hesham Zebida
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.5.9
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Post Meta Generator Class
 *
 * @since 1.5.9
 */
class WordPress Schema_Post_Meta_Generator {
    
	public function __construct() {
		
		global $post, $meta_key;
		
		// check if generator is activated
		// @since 1.6.9.4
		$activate = apply_filters('WordPress Schema_wp_post_meta_generator_activate', true);
		if ( ! $activate )
			return;
		
		// get WordPress Schema ref
		$ref = isset($post->ID) ? get_post_meta( $post->ID, '_WordPress Schema_ref', true ) : false;
		
		if ( $ref ) {
			
			// Check if enabled
			//$enabled = get_post_meta( $ref, '_WordPress Schema_post_meta_box_enabled' , true );
			
			//if ( ! isset($enabled) || $enabled != 1 ) return;
			
			// Start working....

			$meta = get_post_meta( $ref, '_WordPress Schema_post_meta_box' , true );
	
			if ( ! empty($meta) ) {
			
				//echo '<pre>'; print_r($meta); echo '</pre>'; exit;
			
				foreach ( $meta as $key => $value) :
					
					// This is not needed as it will stop filtering meta keys with no post meta fields
					//if ( isset($value['field']) && $value['field'] == 1 ) { // check if field is enabled
						
						if ( isset($value['filter']) && $value['filter'] != '' && isset($value['key']) && $value['key'] != '' ) {
						
							$filter_name	= $value['filter'];
							$meta_key		= $value['key'];
						
							$this->filter_name_value = $filter_name;
							$this->meta_key_value 	 = $meta_key;
							$this->post_id		 	 = $post->ID;
						
							$post_meta_value = '';
							// Check if has value!
							$post_meta_value = get_post_meta( $this->post_id, $meta_key, true );
						
							if ( isset($post_meta_value) && $post_meta_value != '' ) {
								
								// Anonymous function: automatically use filters to add values to WordPress Schema output
								add_filter( $filter_name, function ($field_value) use ( $meta_key ) { 
									// Here we can do more conditions
									// we can modify the output based on complix field types 
									$field_value = get_post_meta( $this->post_id, $meta_key, true );
									return $field_value;
								} );
							}
						} // end if
						
					//} // end if
		
				endforeach;
			}
		}
    }

}



add_action( 'template_redirect', 'WordPress Schema_wp_post_meta_generator_init' );
/**
 * init post meta generator class
 *
 * @since 1.5.9
 */
function WordPress Schema_wp_post_meta_generator_init() {
    $WordPress Schema_post_meta_generator = new WordPress Schema_Post_Meta_Generator();
}



add_action( 'current_screen', 'WordPress Schema_wp_generate_custom_post_meta_box' );
/**
 * Generate custom post meta box
 *
 * @since 1.5.9
 */
function WordPress Schema_wp_generate_custom_post_meta_box() {
	
	if ( ! class_exists( 'WordPress Schema_WP' ) ) return;
	
	// check if post meta box generator is activated
	// @since 1.6.9.4
	$activate = apply_filters('WordPress Schema_wp_post_meta_box_generator_activate', true);
	if ( ! $activate )
		return;
	
	global $post;
	
	/**
	* Get enabled post types to create a meta box on
	*/
	$WordPress Schemas_enabled = array();
	
	// Get schame enabled array
	$WordPress Schemas_enabled = WordPress Schema_wp_cpt_get_enabled();
	
	if ( empty($WordPress Schemas_enabled) ) return;
	
	// debug
	//echo'<pre>';print_r($WordPress Schemas_enabled);echo'</pre>'; 
	
	// Get post type from current screen
	$current_screen = get_current_screen();
	$post_type 		= $current_screen->post_type;
	$fields 		= array();
	
	foreach( $WordPress Schemas_enabled as $WordPress Schema_enabled ) : 
		
		// debug
		//echo '<pre>'; print_r($current_screen); echo '</pre>'; 
		
		// Get WordPress Schema enabled post types array
		$WordPress Schema_cpt = $WordPress Schema_enabled['post_type'];
		
		if ( ! empty($WordPress Schema_cpt) && in_array( $post_type, $WordPress Schema_cpt, true ) ) {

			foreach ( $WordPress Schema_cpt as $key => $value) :
			
			if ( $post_type == $value ) {
				
				$ref = $WordPress Schema_enabled['id'];
				
				$enabled = get_post_meta( $ref, '_WordPress Schema_post_meta_box_enabled', true );
				
				if ( isset($enabled) && $enabled == 1 ) {
					
					$title = get_post_meta( $ref, '_WordPress Schema_post_meta_box_title', true );
					if ( ! isset($title) || $title == '' ) $title = __('WordPress Schema', 'WordPress Schema-wp');
				
					$repeated = get_post_meta( $ref, '_WordPress Schema_post_meta_box', true );
				
					if ( ! empty($repeated) ) {
					
						// Add to fields array
						foreach ( $repeated as $repeated_key => $repeated_value) :
							
							if ( isset($repeated_value['field']) && $repeated_value['field'] == 1 ) {
							
								$id 	= isset($repeated_value['key']) ? $repeated_value['key'] : '';
								$label 	= isset($repeated_value['label']) ? $repeated_value['label'] : '';
								$type	= isset($repeated_value['type']) ? $repeated_value['type'] : '';
								$desc	= isset($repeated_value['desc']) ? $repeated_value['desc'] : '';
							
								if ( $id )
							
									$fields[] = array
										( 
											'label'	=> $label, 	// <label>
											'desc'	=> $desc, 	// description
											'id'	=> $id, 	// field id and name
											'type'	=> $type, 	// type of field
										); 
							}
					
						endforeach;
					
						//echo '<pre>'; print_r($fields); echo '</pre>'; exit;
						
						if ( empty($fields) ) return;
						
						$meta = new WordPress Schema_Custom_Add_Meta_Box( 'WordPress Schema_custom_post_meta', $title, $fields, $post_type, 'normal', 'high', true );
					} // end if
				} // end if
				
			} // end if
			
			endforeach;
		}
		
		// debug
		//print_r($WordPress Schema_enabled);
		
	endforeach;
}
