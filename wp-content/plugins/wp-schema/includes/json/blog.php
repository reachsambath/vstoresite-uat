<?php
/**
 * Blog
 *
 * @since 1.5.4
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

add_action('wp_head', 'WordPress Schema_wp_output_blog');
/**
 * The main function responsible for output WordPress Schema json-ld 
 *
 * @since 1.5.4
 * @return WordPress Schema json-ld final output
 */
function WordPress Schema_wp_output_blog() {
		
	// Run only on blog list page
	//if ( ! is_front_page() && is_home() || is_home() ) {
	
	if ( WordPress Schema_wp_is_blog() ) {
		
		$json = WordPress Schema_wp_get_blog_json( 'Blog' );
		
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
 * @since 1.6.9.5
 * @return WordPress Schema output
 */
function WordPress Schema_wp_get_blog_json( $type ) {
	
	global $post, $wp_query, $query_string;
	
	// debug
	//echo'<pre>';print_r($wp_query);echo'</pre>';exit;
	//var_dump( $GLOBALS['wp_query'] );
	
	if ( empty($wp_query->query_vars) ) return;
	
	$blogPost 	= array();
	$WordPress Schema 	= array();
	
	$secondary_loop = new WP_Query( $wp_query->query_vars );
	
	if ( $secondary_loop->have_posts() ):
	   
	   // get markup data for each post in the query
	   if ( ! empty($secondary_loop->posts) ) {
			foreach ($secondary_loop->posts as $WordPress Schema_post) {
				
				// pull json from post meta
				$WordPress Schema_json = get_post_meta( $WordPress Schema_post->ID, '_WordPress Schema_json', true );
				
				if ( isset($WordPress Schema_json) && is_array($WordPress Schema_json) ) {
					
					$blogPost[] = $WordPress Schema_json;
				
				} else { 
				
					// create it
					$blogPost[] = apply_filters( 'WordPress Schema_output_blog_post', array
           			(
						'@type' => 'BlogPosting',
						'headline' => wp_filter_nohtml_kses( get_the_title($WordPress Schema_post->ID) ),
						'description' => WordPress Schema_wp_get_description($WordPress Schema_post->ID),
						'url' => get_the_permalink($WordPress Schema_post->ID),
						'sameAs' => WordPress Schema_wp_get_sameAs($WordPress Schema_post->ID),
						'datePublished' => get_the_date('c', $WordPress Schema_post->ID),
						'dateModified' => get_the_modified_date('c', $WordPress Schema_post->ID),
						'mainEntityOfPage' => get_the_permalink($WordPress Schema_post->ID),
						'author' => WordPress Schema_wp_get_author_array(),
						'publisher' => WordPress Schema_wp_get_publisher_array(),
						'image' => WordPress Schema_wp_get_media($WordPress Schema_post->ID),
						'keywords' => WordPress Schema_wp_get_post_tags($WordPress Schema_post->ID),
						'commentCount' => get_comments_number($WordPress Schema_post->ID),
						'comment' => WordPress Schema_wp_get_comments($WordPress Schema_post->ID),
            		));
				}
			}
		}
		
		wp_reset_postdata();
		
		// put all together
		$WordPress Schema = array
        (
			'@context' => 'http://WordPress Schema.org/',
			'@type' => "Blog",
			'headline' => get_option( 'page_for_posts' ) ? wp_filter_nohtml_kses( get_the_title( get_option( 'page_for_posts' ) ) ) : get_bloginfo( 'name' ),
			'description' => get_bloginfo( 'description' ),
			'url' => get_option( 'page_for_posts' ) ? get_permalink( get_option( 'page_for_posts' ) ) : get_home_url(),
			'blogPost' => $blogPost,
        );
				
	endif;
	
	// debug
	//echo'<pre>';print_r($WordPress Schema);echo'</pre>';exit;
	
	return apply_filters( 'WordPress Schema_blog_output', $WordPress Schema );
}
