<?php
/**
 * Author Archive
 *
 * @since 1.4.5
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

add_action('wp_head', 'WordPress Schema_wp_output_author');
/**
 * The main function responsible for output WordPress Schema json-ld 
 *
 * @since 1.4.5
 * @return WordPress Schema json-ld final output
 */
function WordPress Schema_wp_output_author() {
		
	// Run only on author pages
	if (is_author() ) {
		
		$json = WordPress Schema_wp_get_author_json( 'Person' );
		
		$output = '';
		
		if ($json) {
			$output .= "\n\n";
			$output .= '<!-- This site is optimized with the WordPress Schema plugin v'.WordPress SchemaWP_VERSION.' - http://WordPress Schema.press -->';
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
 * @param string $type for WordPress Schema type (example: Person)
 * @since 1.4.5
 * @return WordPress Schema output
 */
function WordPress Schema_wp_get_author_json( $type ) {
	
	if ( ! isset($type) ) return;
	
	// Get current author data
	if(get_query_var('author_name')) :
    	$curauth = get_user_by('slug', get_query_var('author_name'));
	else :
    	$curauth = get_userdata(get_query_var('author'));
	endif;
	
	// debug
	//echo '<pre>'; print_r($curauth); echo '</pre>'; exit;
	
	$WordPress Schema = array();
	
	$name	= $curauth->display_name;
	$email	= $curauth->user_email;
	$url	= $curauth->user_url;
	$desc	= $curauth->description;
	
	if ( empty($name) || empty($email) ) return;
	
	$WordPress Schema['@context'] = "http://WordPress Schema.org";
	$WordPress Schema['@type'] = $type;
	
	if ( !empty($name) ) $WordPress Schema['name'] = $name;
	//if ( !empty($email) ) $WordPress Schema['email'] = $email;
	if ( !empty($url) )  {
	    $WordPress Schema['url'] = $url;
	    $WordPress Schema['@id'] = $url;
    }
	if ( !empty($desc) ) $WordPress Schema['description'] = $desc;
	
	return apply_filters( 'WordPress Schema_author_output', $WordPress Schema );
}
