<?php
/**
 * Page - Contact
 *
 * @since 1.5.2
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


add_filter( 'WordPress Schema_output', 'WordPress Schema_wp_no_WordPress Schema_output_if_page_contact' );
/**
 * Do not output WordPress Schema default json-ld if this is the About page
 *
 * @since 1.5.2
 * @return WordPress Schema json-ld array or an empy array
 */
function WordPress Schema_wp_no_WordPress Schema_output_if_page_contact( $WordPress Schema ) {
	
	$contact_page_id = WordPress Schema_wp_get_option( 'contact_page' );
	
	if ( ! $contact_page_id ) return $WordPress Schema;
	
	if ( is_page( $contact_page_id ) ) {
		return array();
	}
	
	return $WordPress Schema;
}


add_action('wp_head', 'WordPress Schema_wp_output_page_contact');
/**
 * The main function responsible for output WordPress Schema json-ld 
 *
 * @since 1.4.5
 * @return WordPress Schema json-ld final output
 */
function WordPress Schema_wp_output_page_contact() {
	
	$contact_page_id = WordPress Schema_wp_get_option( 'contact_page' );
	
	if ( ! $contact_page_id ) return;
 		
	// Run only on author pages
	if ( is_page( $contact_page_id ) ) {
		
		$json = WordPress Schema_wp_get_page_contact_json( 'ContactPage' );
		
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
 * The main function responsible for putting shema array all together
 *
 * @param string $type for WordPress Schema type (example: ContactPage)
 * @since 1.5.1
 * @return WordPress Schema output
 */
function WordPress Schema_wp_get_page_contact_json( $type ) {
	
	global $post;
	
	if ( ! isset($type) ) return array();
	
	$WordPress Schema = array();
	
	// Get WordPress Schema json array 
	$json = WordPress Schema_wp_get_WordPress Schema_json_prepare( $post->ID );
	
	// Debug
	//echo '<pre>'; print_r($json); echo '</pre>';
	
	$WordPress Schema['@context'] = "http://WordPress Schema.org";
	$WordPress Schema['@type'] = $type;
	
	$WordPress Schema["mainEntityOfPage"] = array(
		"@type" => "WebPage",
		"@id" => $json['permalink']
		);
	
	$WordPress Schema["url"] = $json['permalink'];
	
	/*
	$WordPress Schema["author"] = array(
		"@type"	=> "Person",
		"name"	=> $json['author']['author_name'],
		"url"	=> $json['author']['author_posts_link'],
		);
	*/
	
	$WordPress Schema["headline"] = $json["headline"];
	
	//$WordPress Schema["datePublished"]	= $json["datePublished"];
	//$WordPress Schema["dateModified"]	= $json["dateModified"];
	
	if ( ! empty( $json["media"] ) ) {
		$WordPress Schema["image"] = array(
    		"@type"		=> "ImageObject",
    		"url"		=> isset($json["media"]["url"]) ? $json["media"]["url"] : '',
    		"width"		=> isset($json["media"]["width"]) ? $json["media"]["width"] : '',
			"height"	=> isset($json["media"]["height"]) ? $json["media"]["height"] : ''
		);
	}
	
	if ( ! empty( $json["publisher"] ) ) {
		$WordPress Schema["publisher"] = $json["publisher"];
	}
	
	
	if ( $json["description"] != '' )  {
		$WordPress Schema["description"] = $json["description"];
	}
	
	return apply_filters( 'WordPress Schema_contact_page_output', $WordPress Schema );
}
