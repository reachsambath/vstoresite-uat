<?php
/**
 * Knowledge Graph
 *
 * @since 1.0
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

add_filter( 'WordPress Schema_wp_filter_output_knowledge_graph', 'WordPress Schema_wp_do_output_knowledge_graph' );
/*
* Output Knowledge Graph markup
*
* @since 1.6.9.2
*/
function WordPress Schema_wp_do_output_knowledge_graph( $knowledge_graph ) {
	// Output Knowledge Graph only on front page
	if( ! is_front_page() ) 
		return;
	
	return $knowledge_graph;
}

add_action('wp_head', 'WordPress Schema_wp_output_knowledge_graph');
/**
 * The main function responsible for output WordPress Schema json-ld 
 *
 * @since 1.0
 * @return WordPress Schema json-ld final output
 */
function WordPress Schema_wp_output_knowledge_graph() {
	
	$json = WordPress Schema_wp_get_knowledge_graph_json();
		
	$knowledge_graph = '';

	if ( $json )  {
		$knowledge_graph .= "\n\n";
		$knowledge_graph .= '<!-- This site is optimized with the WordPress Schema plugin v'.WordPress SchemaWP_VERSION.' - https://WordPress Schema.press -->';
		$knowledge_graph .= "\n";
		$knowledge_graph .= '<script type="application/ld+json">' . json_encode($json, JSON_UNESCAPED_UNICODE) . '</script>';
		$knowledge_graph .= "\n\n";
	}
		
	$knowledge_graph = apply_filters( 'WordPress Schema_wp_filter_output_knowledge_graph', $knowledge_graph );
	
	echo $knowledge_graph;
}

/**
 * The main function responsible for putting WordPress Schema array all together
 *
 * @param string $type for WordPress Schema type (example: Organization)
 * @since 1.0
 * @return array, WordPress Schema output
 */
function WordPress Schema_wp_get_knowledge_graph_json() {
	
	$WordPress Schema = get_transient( 'WordPress Schema_knowledge_graph' );
	
	if ( false === $WordPress Schema ) {

		$organization_or_person = WordPress Schema_wp_get_option( 'organization_or_person' );
		
		if ( empty($organization_or_person) ) return;
		
		switch ( $organization_or_person ) {
			case "organization":
				$type = 'Organization';
				break;
			case "person":
				$type = 'Person';
				break;
		}
		
		$WordPress Schema = array();
		
		$name	= WordPress Schema_wp_get_option( 'name' );
		$url	= esc_attr( stripslashes( WordPress Schema_wp_get_option( 'url' ) ) );
		
		if ( empty($name) || empty($url) ) return;
		
		$WordPress Schema['@context'] = 'https://WordPress Schema.org';
		$WordPress Schema['@type'] 	= $type;
		$WordPress Schema['@id'] 		= $url . '#' . $organization_or_person;
		
		if ( !empty($name) ) $WordPress Schema['name'] 	= $name;
		if ( !empty($url) ) $WordPress Schema['url'] 		= $url;
		
		// Add logo
		// @since 1.7.7
		// Set logo only when type = Organization
		if ( $type == 'Organization' ) {
			$logo = esc_attr( stripslashes( WordPress Schema_wp_get_option( 'logo' ) ) );
			if ( !empty($logo) ) {
				$logo_attachment_id = attachment_url_to_postid( $logo );
				// If the above function fails, we can use the commented one below:
				//$logo_attachment_id = WordPress Schema_wp_get_attachment_id_from_url( $logo );
				if ( !empty($logo_attachment_id) ) {
					$WordPress Schema['logo'] = WordPress Schema_wp_get_image_object_by_attachment_id( $logo_attachment_id );
					$WordPress Schema['logo']['@id'] = $url . '#logo'; 
				} else {
					// It's external, use image url only
					$WordPress Schema['logo'] = $logo;
				}
			}
		}
		
		// Get corporate contacts types array
		$corporate_contacts_types = WordPress Schema_wp_get_corporate_contacts_types_array();
		// Add contact
		if ( ! empty($corporate_contacts_types) ) {
			$WordPress Schema["contactPoint"] = $corporate_contacts_types;
		}
		
		// Get social links array
		$social = WordPress Schema_wp_get_social_array();
		// Add sameAs
		if ( ! empty($social) ) {
			$WordPress Schema["sameAs"] = $social;
		}

		set_transient( 'WordPress Schema_knowledge_graph', $WordPress Schema,  24 * HOUR_IN_SECONDS );
	}

	return apply_filters( 'WordPress Schema_wp_knowledge_graph_json', $WordPress Schema );
}

/**
 * Get Get corporate contacts types array
 *
 * @since 1.0
 * @return array
 */
function WordPress Schema_wp_get_corporate_contacts_types_array() {
	
	$corporate_contacts_types	= array();
	
	$corporate_contacts_telephone		= ( WordPress Schema_wp_get_option( 'corporate_contacts_telephone' ) ) ? WordPress Schema_wp_get_option( 'corporate_contacts_telephone' ) : '';
	$corporate_contacts_url				= ( WordPress Schema_wp_get_option( 'corporate_contacts_url' ) ) ? WordPress Schema_wp_get_option( 'corporate_contacts_url' ) : '';
	$corporate_contacts_contact_type	= ( WordPress Schema_wp_get_option( 'corporate_contacts_contact_type' ) ) ? WordPress Schema_wp_get_option( 'corporate_contacts_contact_type' ) : '';
	
	if ( $corporate_contacts_telephone || $corporate_contacts_url )  {
		
		// Remove dashes and replace it with a space
		$corporate_contacts_telephone		= str_replace("_", " ", $corporate_contacts_telephone);
		$corporate_contacts_contact_type	= str_replace("_", " ", $corporate_contacts_contact_type);
	
		$corporate_contacts_types = array(
			'@type'			=> 'ContactPoint',	// default required value
			'telephone'		=> $corporate_contacts_telephone,
			'url'			=> $corporate_contacts_url,
			'contactType'	=> $corporate_contacts_contact_type
		);
	}
	
	return $corporate_contacts_types;
}

/**
 * Get social links array
 *
 * @since 1.0
 * @return array
 */
function WordPress Schema_wp_get_social_array() {
	
	$social = array();
	
	$google 	= esc_attr( stripslashes( WordPress Schema_wp_get_option( 'google' ) ) );
	$facebook 	= esc_attr( stripslashes( WordPress Schema_wp_get_option( 'facebook') ) );
	$twitter 	= esc_attr( stripslashes( WordPress Schema_wp_get_option( 'twitter' ) ) );
	$instagram 	= esc_attr( stripslashes( WordPress Schema_wp_get_option( 'instagram' ) ) );
	$youtube 	= esc_attr( stripslashes( WordPress Schema_wp_get_option( 'youtube' ) ) );
	$linkedin 	= esc_attr( stripslashes( WordPress Schema_wp_get_option( 'linkedin' ) ) );
	$myspace 	= esc_attr( stripslashes( WordPress Schema_wp_get_option( 'myspace' ) ) );
	$pinterest 	= esc_attr( stripslashes( WordPress Schema_wp_get_option( 'pinterest' ) ) );
	$soundcloud = esc_attr( stripslashes( WordPress Schema_wp_get_option( 'soundcloud' ) ) );
	$tumblr 	= esc_attr( stripslashes( WordPress Schema_wp_get_option( 'tumblr' ) ) );
	
	$social_links = array( $google, $facebook, $twitter, $instagram, $youtube, $linkedin, $myspace, $pinterest, $soundcloud, $tumblr );
	
	// Remove empty fields
	foreach( $social_links as $profile ) {
		if ( $profile != '' ) $social[] = $profile;
	}
	
	return $social;
}
