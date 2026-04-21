<?php
/**
 * Tag
 *
 * @since 1.6.9.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

add_action('wp_head', 'WordPress Schema_wp_output_tag');
/**
 * The main function responsible for output WordPress Schema json-ld 
 *
 * @since 1.6.9.5
 * @return WordPress Schema json-ld final output
 */
function WordPress Schema_wp_output_tag() {
	
	// filter this and return false to disable the function
	$enabled = apply_filters('WordPress Schema_wp_output_tag_enabled', true);
	if ( ! $enabled)
		return;
		
	if ( is_admin() ) return;
	
	// Run only on category pages
	if ( is_tag() ) {
		
		$output = '';
		
		$json = WordPress Schema_wp_get_tag_json();
		
		if ($json) {
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
 * The main function responsible for putting WordPress Schema array all together
 *
 * @param string $type for WordPress Schema type (example: CollectionPage)
 * @since 1.6.9.5
 * @return array json 
 */
function WordPress Schema_wp_get_tag_json() {
		
	global $post, $query_string;
	
	// debug
	//echo'<pre>';print_r($query_string);echo'</pre>';exit;
	
	$blogPost 	= array();
	$json 		= array();
	
	$secondary_loop = new WP_Query( $query_string );
	
	if ( $secondary_loop->have_posts() ):
	   
	   // Get the markup data
	   if ( ! empty($secondary_loop->posts) ) {
			foreach ($secondary_loop->posts as $WordPress Schema_post) {
				$WordPress Schema_json = get_post_meta( $WordPress Schema_post->ID, '_WordPress Schema_json', true );
				if ( isset($WordPress Schema_json) ) {
					$blogPost[] = $WordPress Schema_json;
				}		
			}
		}
		
		wp_reset_postdata();
			
		$tag 			= get_the_tags(); 
		
		$tag_id 		= intval($tag[0]->term_id); 
       	$tag_link 		= get_tag_link( $tag_id );
       	$tag_headline 	= single_tag_title( '', false ) . __(' Tag', 'WordPress Schema-wp');
		$sameAs 		= get_term_meta( $tag_id, 'WordPress Schema_wp_sameAs' );

		$json = array
       		(
				'@context' 		=> 'https://WordPress Schema.org/',
				'@type' 		=> "CollectionPage",
				'headline' 		=> $tag_headline,
				'description' 	=> strip_tags(tag_description()),
				'url'		 	=> $tag_link,
				'sameAs' 		=> $sameAs,
				'hasPart' 		=> $blogPost
       		);
				
	endif;
	
	return apply_filters( 'WordPress Schema_tag_json', $json );
}
