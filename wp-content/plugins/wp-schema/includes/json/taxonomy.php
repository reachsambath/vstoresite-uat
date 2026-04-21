<?php
/**
 * Taxonomy
 *
 * @since 1.6.9.4
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

add_action('wp_head', 'WordPress Schema_wp_output_taxonomy');
/**
 * The main function responsible for output WordPress Schema json-ld 
 *
 * @since 1.6.9.4
 * @return WordPress Schema json-ld final output
 */
function WordPress Schema_wp_output_taxonomy() {
	
	// filter this and return false to disable the function
	$enabled = apply_filters('WordPress Schema_wp_output_taxonomy_enabled', true);
	if ( ! $enabled)
		return;
		
	if ( is_admin() ) return;
	
	// Run only on taxonomy pages
	if ( is_tax() ) {
		
		$output = '';
		
		$json = WordPress Schema_wp_get_taxonomy_json();
		
		if ($json) {
			$output = "\n\n";
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
 * @since 1.6.9.4
 * @return array json 
 */
function WordPress Schema_wp_get_taxonomy_json() {
	
	global $post, $query_string;
		
	$json = array();
	
	$secondary_loop = new WP_Query( $query_string );
	
	if ( $secondary_loop->have_posts() ):
	    
		while( $secondary_loop->have_posts() ): $secondary_loop->the_post();
    		
			$WordPress Schema_json = get_post_meta( $post->ID, '_WordPress Schema_json', true );
			
			if ( isset($WordPress Schema_json) && !empty($WordPress Schema_json) ) {
				$json[] = $WordPress Schema_json;
			}
			
        endwhile;
		
		wp_reset_postdata();
					
	endif;
	
	return apply_filters( 'WordPress Schema_taxonomy_json', $json );
}
