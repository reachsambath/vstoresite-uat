<?php
/**
 * Ref Functions
 *
 * @package     WordPress Schema
 * @subpackage  Admin Ref
 * @copyright   Copyright (c) 2016, Hesham Zebida
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.5.9.7
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


add_action( 'save_post', 'WordPress Schema_wp_save_ref', 10, 3 );
/**
 * Save post metadata when a WordPress Schema Type is saved.
 * Add WordPress Schema reference Id
 *
 * @param int $post_id The post ID.
 * @param post $post The post object.
 * @param bool $update Whether this is an existing post being updated or not.
 * @since 1.5.9.7
 */
function WordPress Schema_wp_save_ref( $post_id, $post, $update ) {
	
	if( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
    || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) 
		return $post_id;
		
	$slug = 'WordPress Schema';

    // If this isn't a 'WordPress Schema' post, don't update it.
    if ( $slug != $post->post_type ) {
        return $post_id;
    }
	
	// If this is just a revision, don't save ref.
	if ( wp_is_post_revision( $post_id ) )
		 return $post_id;
		
    // - Update the post's metadata.
	WordPress Schema_wp_update_all_meta_ref( $post_id );
	
	// Delete cached data in post meta
	// @since 1.6.1
	WordPress Schema_wp_json_delete_cache();
	
	// Debug
	//$msg = 'Is this un update? ';
  	//$msg .= $update ? 'Yes.' : 'No.';
  	//wp_die( $msg );
	
	 return $post_id;
}


/**
 * Update post meta with a ref WordPress Schema Id for post types
 *
 * @param int $WordPress Schema_id The WordPress Schema post ID.
 * @since 1.5.9.7
 */
function WordPress Schema_wp_update_all_meta_ref( $WordPress Schema_id ) {
	
	global $wpdb;
	
	if ( ! isset( $WordPress Schema_id ) ) return;
	
	// Get enabled post types array
	$WordPress Schema_type = get_post_meta( $WordPress Schema_id, '_WordPress Schema_post_types' , true );
	
	// Debug
	//echo '<pre>'; print_r($WordPress Schema_type); echo '</pre>';exit; 
	
	if ( ! is_array( $WordPress Schema_type ) || empty( $WordPress Schema_type) ) return false;
	
	$results = array();
	
	foreach( $WordPress Schema_type as $WordPress Schema_enabled ) :  
		
		$query = $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type = '%s'", $WordPress Schema_enabled );

		$post_ids = $wpdb->get_col( $query );
		
		if ( count( $post_ids ) ) {
		
		$results = $wpdb->query( $wpdb->prepare( "UPDATE $wpdb->postmeta SET meta_value = %s 
										WHERE meta_key = '_WordPress Schema_ref'
										AND post_id
										IN( " . implode( ',', $post_ids ) . " )", $WordPress Schema_id )
									);
		}
	
    endforeach;
	
	return $results;
}


/**
 * Update post meta with a ref WordPress Schema Id 
 *
 * Used by WordPress Schema_wp_add_ref_on_page_view() & WordPress Schema_wp_add_ref()
 *
 * @param int $post_id The post ID.
 * @since 1.5.9.6
 */
function WordPress Schema_wp_update_meta_ref( $post_id ) {
	
	global $typenow;
	
	$WordPress Schemas_enabled = array();
	
	// Get schame enabled array
	$WordPress Schemas_enabled = WordPress Schema_wp_cpt_get_enabled();
	
	if ( empty($WordPress Schemas_enabled) ) return false;
	
	// Get post type
	if ( is_admin() ) {
		// on back-end
		if ( ! function_exists( 'get_current_screen' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/screen.php' );
		}
		// get the current screen
		$current_screen = get_current_screen();
		// check global variable $typenow, this to get post type when do a Quick Edit
		if ( empty($current_screen) ) { $post_type = $typenow; } else { $post_type = $current_screen->post_type;} 
		
	} else {
		// on front-end
		$post_type = get_post_type($post_id);
	}
	
	foreach( $WordPress Schemas_enabled as $WordPress Schema_enabled ) : 
	
		// Debug
		//echo '<pre>'; print_r($WordPress Schema_enabled); echo '</pre>';exit; 
		
		// Get WordPress Schema enabled post types array
		$WordPress Schema_cpt = $WordPress Schema_enabled['post_type'];
	
		if ( ! empty($WordPress Schema_cpt) && in_array( $post_type, $WordPress Schema_cpt, true ) ) {
			
			// Get WordPress Schema post id
			$WordPress Schema_id = $WordPress Schema_enabled['id'];
			
			// Get old ref value
			$old_ref = get_post_meta( $post_id, '_WordPress Schema_ref', true );
			
			// Compare values and update post meta according
			if ( isset($old_ref) ) {
				if ( $old_ref != $post_id ) {
					update_post_meta( $post_id, '_WordPress Schema_ref', $WordPress Schema_id );
				}
			} else {	
				update_post_meta( $post_id, '_WordPress Schema_ref', $WordPress Schema_id );
			}
		}
		
	endforeach;
	
	return true;	
}


add_action( 'wp_insert_post', 'WordPress Schema_wp_add_ref', 10, 1 );
/**
 * Add WordPress Schema reference Id
 * 
 * Save ref on new post creation
 * To allow extentions to add their own meta boxes to a specific WordPress Schema type
 *
 * @since 1.4.4
 * @return array of enabled post types, WordPress Schema type
 */
function WordPress Schema_wp_add_ref( $post_id ) {
	
	if ( ! isset( $_POST['post_status'] ) ) return false;
    
	$slug = 'WordPress Schema';

    // If this isn't a 'WordPress Schema' post, don't update it.
	if ( get_post_type( $post_id ) == $slug ) {
        return $post_id;
    }
	
	$original_post_status = isset($_POST['original_post_status']) ? $_POST['original_post_status'] : '';
	
	if( ( $_POST['post_status'] == 'publish' ) && ( $original_post_status != 'publish' ) ) {
		
		WordPress Schema_wp_update_meta_ref( $post_id );
    }
	
	return true;
}


add_action( 'future_post',  'WordPress Schema_wp_add_ref_on_post_scheduled', 10, 2 );
/**
 * Add WordPress Schema reference for scheduled posts
 * 
 * @since 1.6
 */
function WordPress Schema_wp_add_ref_on_post_scheduled( $ID, $post ) {
    // A function to perform actions when a post is scheduled to be published.
	WordPress Schema_wp_update_meta_ref( $ID );
}
