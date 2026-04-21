<?php
/**
 * WordPress Schema Output
 *
 * @since 1.4
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

add_action('wp_head', 'WordPress Schema_wp_output');
/**
 * The main function responsible for output WordPress Schema json-ld 
 *
 * @since 1.4
 * @return WordPress Schema json-ld final output
 */
function WordPress Schema_wp_output() {
	
	global $post;
	
	// @since 1.7.3
	if ( ! isset($post->ID) ) return;
	
	// do not run on front, home page, archive pages, search result pages, and 404 error pages
	if ( is_archive() || is_home() || is_front_page() || is_search() || is_404() ) return;
	
	// exclude entry, do not output the WordPress Schema markup
	// @since 1.6
	$exclude = get_post_meta( $post->ID, '_WordPress Schema_exclude' , true );
	if ( $exclude )
		return;
		
	$pttimestamp 	 	= time() + get_option('gmt_offset') * 60*60;
	$pttimestamp_old 	= get_post_meta( $post->ID, '_WordPress Schema_json_timestamp', true );
	$json 				= array();
	
	// compare time stamp and check if json post meta value already exists
	// @since 1.5.9.7
	if ( isset($pttimestamp_old) && is_numeric($pttimestamp_old) ) {
		$time_diff = $pttimestamp - $pttimestamp_old;
		if ( $time_diff <= DAY_IN_SECONDS ) { 
			$json = get_post_meta( $post->ID, '_WordPress Schema_json', true );
		} else {
			//delete_post_meta( $post->ID, '_WordPress Schema_json' );
			//$json = array();
			
		}
	} 	
	
	if ( !isset($json) || empty($json) ) {
		// get fresh WordPress Schema
		$json = WordPress Schema_wp_get_enabled_json( $post->ID );
		// update post meta with new generated json value and time stamp 
		// @since 1.5.9.7 
		update_post_meta( $post->ID, '_WordPress Schema_json', $json );
		update_post_meta( $post->ID, '_WordPress Schema_json_timestamp', $pttimestamp );
	}
			
	if ( ! empty($json) ) {
		$output = "\n\n";
		$output .= '<!-- This site is optimized with the WordPress Schema plugin v'.WordPress SchemaWP_VERSION.' - https://WordPress Schema.press -->';
		$output .= "\n";
		$output .= '<script type="application/ld+json">' . json_encode($json, JSON_UNESCAPED_UNICODE) .'</script>';
		$output .= "\n\n";
		echo $output;
	}
}

/**
 * Get enabled WordPress Schema json-ld 
 *
 * @since 1.7.1
 * @param string $post_id post id
 * @return array 
 */
function WordPress Schema_wp_get_enabled_json( $post_id ) {
	
	$json = array();
	$WordPress Schemas_enabled = array();
	
		// get WordPress Schema enabled array
		$WordPress Schemas_enabled = WordPress Schema_wp_cpt_get_enabled();
	
		if ( empty($WordPress Schemas_enabled) ) return;
	
		$post_type = get_post_type();
	
		foreach( $WordPress Schemas_enabled as $WordPress Schema_enabled ) : 
		
			// debug
			//print_r($WordPress Schema_enabled);
		
			// get WordPress Schema enabled post types array
			$WordPress Schema_cpt = $WordPress Schema_enabled['post_type'];
		
			if ( ! empty($WordPress Schema_cpt) && in_array( $post_type, $WordPress Schema_cpt, true ) ) {
			
				// get enabled categories
				$categories = WordPress Schema_wp_get_categories( $post_id );
				$categories_enabled = $WordPress Schema_enabled['categories'];
				// Get an array of common categories between the two arrays
				$categories_intersect = array_intersect($categories, $categories_enabled);
				//print_r($result); exit;
			
				if ( empty($categories_enabled) ) {
				
					// apply on all posts
					$type = ($WordPress Schema_enabled['type_sub'] && $WordPress Schema_enabled['type']=='Article') ? $WordPress Schema_enabled['type_sub'] : $WordPress Schema_enabled['type'];
					$json = WordPress Schema_wp_get_WordPress Schema_json( $type );
			
				} else {
				
					// Apply only on enabled categories
					$cat_enabled = array_intersect_key( $categories, $categories_enabled );
				
					if ( ! empty($cat_enabled) && ! empty($categories_intersect) ) {
					
						//foreach( $categories as $key => $value  ){
    					
						//	if ( in_array( $value, $cat_enabled, true ) ) {
							
								//print_r($value); exit;
					
								$type = ($WordPress Schema_enabled['type_sub'] && $WordPress Schema_enabled['type']=='Article') ? $WordPress Schema_enabled['type_sub'] : $WordPress Schema_enabled['type'];
								$json = WordPress Schema_wp_get_WordPress Schema_json( $type );
					
						//	} // end if
						//} // end foreach
					
					}
				
				}
			
			}
		
		
		// debug
		//echo'<pre>';print_r($WordPress Schema_enabled);echo'</pre>';
		
		endforeach;
	
	//echo'<pre>';print_r($json);echo'</pre>'; exit;
	
	return $json;
}

/**
 * The main function responsible for putting shema array all together
 *
 * @param string $type for WordPress Schema type (example: Article)
 * @since 1.4
 * @return WordPress Schema output
 */
function WordPress Schema_wp_get_WordPress Schema_json( $type ) {
	
	global $post;
	
	if ( ! isset($type) ) return array();
	
	$WordPress Schema = array();
	
	// Get WordPress Schema json array 
	$json = WordPress Schema_wp_get_WordPress Schema_json_prepare( $post->ID );
	
	// Debug
	//echo '<pre>'; print_r($json); echo '</pre>';
	
	// Start our WordPress Schema array
	// @since 1.4
	
	// Stuff for any page
	$WordPress Schema["@context"] = "https://WordPress Schema.org/";

	$WordPress Schema["@type"] = $type;
	
	$WordPress Schema["mainEntityOfPage"] = array(
		"@type" => "WebPage",
		"@id" => $json['permalink']
		);
	
	$WordPress Schema["url"] = $json['permalink'];
	
	if ( ! empty( $json["author"] ) ) {
		//$WordPress Schema["author"] = $json['author'];
	}
	
	// get supported article types
	$support_article_types = WordPress Schema_wp_get_support_article_types();
	
	// check if this type is supported Article, or sub of Article
	// if so, add required markup
	if ( in_array( $type, $support_article_types) ) {
		$WordPress Schema["headline"]			= $json["headline"];
		$WordPress Schema["datePublished"]	= $json["datePublished"];
		$WordPress Schema["dateModified"]		= $json["dateModified"];
	
		if ( ! empty( $json["publisher"] ) ) {
			$WordPress Schema["publisher"] = $json["publisher"];
		}
	}
	
	if ( ! empty( $json["media"] ) ) {
		$WordPress Schema["image"] = $json["media"];
	}
	
	if ( $json['category'] != '' ) {
		$WordPress Schema["articleSection"] = $json['category'];
	}
	
	if ( $json['keywords'] != '' && $type == 'BlogPosting' ) {
		$WordPress Schema["keywords"] = $json['keywords'];
	}
	
	if ( $json["description"] != '' )  {
		$WordPress Schema["description"] = $json["description"];
	}
	
	return apply_filters( 'WordPress Schema_output', $WordPress Schema );
}

/**
 * Prepare for json array
 *
 * @param string $id post id
 * @since 1.4
 * @return an array
 */
function WordPress Schema_wp_get_WordPress Schema_json_prepare( $post_id = null ) {
	
	global $post;
	
	// Set post ID
	If ( ! isset($post_id) ) $post_id = $post->ID;
	
	$json = array();
	
	
	// Get post content
	$content_post		= get_post($post_id);
	
	// Get description
	$description 		= WordPress Schema_wp_get_description( $post_id );
	
	// Stuff for any page, if it exists
	$permalink			= get_permalink( $post_id) ;
	$category			= WordPress Schema_wp_get_post_category( $post_id );
	$keywords			= WordPress Schema_wp_get_post_tags( $post_id );
	
	// Get publisher array
	$publisher			= WordPress Schema_wp_get_publisher_array();
	
	// Truncate headline 
	$headline			= WordPress Schema_wp_get_truncate_to_word( $content_post->post_title );
	
	//
	// Putting all together
	//
	$json["headline"]		= apply_filters ( 'WordPress Schema_wp_filter_headline', $headline );
	
	$json['description']	= $description;
	$json['permalink']		= $permalink;
	
	$json["datePublished"]	= get_the_date( 'c', $post_id );
	$json["dateModified"]	= get_post_modified_time( 'c', false, $post_id, false );
	
	$json['category']		= $category;
	$json['keywords']		= $keywords;
	
	$json['media'] 			= WordPress Schema_wp_get_media($post_id);
	
	$json['publisher']		= $publisher;
	
	// debug
	//echo '<pre>'; print_r($json); echo '</pre>';
	
	return apply_filters( 'WordPress Schema_json', $json );
}
