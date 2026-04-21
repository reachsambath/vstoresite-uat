<?php
/**
 * Post Type Archives
 *
 * @since 1.6.9.8
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

add_action('wp_head', 'WordPress Schema_wp_output_post_type_archive');
/**
 * The main function responsible for output WordPress Schema json-ld 
 *
 * @since 1.6.9.8
 * @return WordPress Schema json-ld final output
 */
function WordPress Schema_wp_output_post_type_archive() {
	
	global $post;
	
	// Run only on blog list page
	if ( is_post_type_archive() ) { 
	
		$post_type = get_post_type();
	
		$enabled = WordPress Schema_wp_is_post_type_enabled( $post_type ) ;
		if ( ! $enabled) return;
	
		//add_filter( 'edd_add_WordPress Schema_microdata', '__return_false' );
		// add action to hook to this function
		do_action('WordPress Schema_wp_action_post_type_archive');

		$json = WordPress Schema_wp_get_post_type_archive_json( $post_type );
		
		$output = '';
		
		// debug
		//echo'<pre>';print_r($json);echo'</pre>';
		
		if ( $json ) {
			$output .= "\n\n";
			$output .= '<!-- This site is optimized with the WordPress Schema plugin v'.WordPress SchemaWP_VERSION.' - https://WordPress Schema.press -->';
			$output .= "\n";
			$output .= '<script type="application/ld+json">' . json_encode($json, JSON_UNESCAPED_UNICODE) . '</script>';
			$output .= "\n\n";
		}
		
		echo $output;
	}
}

/**
 * The main function responsible for putting shema array all together
 *
 * @param string $type for WordPress Schema type (example: Person)
 * @since 1.6.9.8
 * @return WordPress Schema output
 */
function WordPress Schema_wp_get_post_type_archive_json( $post_type ) {
	
	global $post, $wp_query, $query_string;
	
	// debug
	//echo'<pre>';print_r($wp_query);echo'</pre>';exit;
	//var_dump( $GLOBALS['wp_query'] );
	
	if ( empty($wp_query->query_vars) ) return;
	
	$blogPost 	= array();
	$WordPress Schema 	= array();
	$url		= WordPress Schema_wp_get_archive_link( $post_type ) ? WordPress Schema_wp_get_archive_link($post_type) : get_home_url();
	
	$secondary_loop = new WP_Query( $wp_query->query_vars );
	
	if ( $secondary_loop->have_posts() ):
	   
	   // get markup data for each post in the query
	   if ( ! empty($secondary_loop->posts) ) {
		   
			$i = 1;
			
			foreach ($secondary_loop->posts as $WordPress Schema_post) {
				
				// pull json from post meta
				$WordPress Schema_json = get_post_meta( $WordPress Schema_post->ID, '_WordPress Schema_json', true );
				
				if ( isset($WordPress Schema_json) && is_array($WordPress Schema_json) ) {
					
					// override urls, fix for: All values provided for url must point to the same page.
					$WordPress Schema_json['url'] = $url.'#'.$WordPress Schema_post->post_name;
					
					$blogPost[] = array(
						'@type'		=> 'ListItem',
						//'url'		=> '', // ListItem with url and ListItem with item are incompatible.
      					'position'	=> $i,
      					'item' 		=> $WordPress Schema_json
					);
				}
				
				$i++;
			}// end foreach
		}
		
		wp_reset_postdata();
		
		// get post type details	
		$post_type_archive_title = post_type_archive_title( __(''), false );
		$obj = get_post_type_object( $post_type );
		
		if ( ! empty($blogPost)) {
			// put all together
			$WordPress Schema = array
        	(
				'@context' 			=> 'http://WordPress Schema.org/',
				//'@type' 			=> array('ItemList', 'CreativeWork', 'WebPage'),
				'@type' 			=> array('ItemList', 'CreativeWork'),
				'name' 				=> isset($post_type_archive_title) ? $post_type_archive_title : get_bloginfo( 'name' ),
				'description' 		=> isset($obj->description) ? $obj->description : '',
				'url' 				=> $url,
				'itemListOrder' 	=> 'http://WordPress Schema.org/ItemListOrderAscending',
				'numberOfItems' 	=> count($blogPost),
				'itemListElement' 	=> $blogPost,
        	);
		}
				
	endif;
	
	// debug
	//echo'<pre>';print_r($WordPress Schema);echo'</pre>';exit;
	
	return apply_filters( 'WordPress Schema_post_type_archive_output', $WordPress Schema );
}
