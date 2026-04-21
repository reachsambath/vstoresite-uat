<?php
/**
 *  VideoObject extention
 *
 *  Adds WordPress Schema VideoObject to oEmbed
 *
 *  @since 1.5
 */
 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


add_action( 'admin_init', 'WordPress Schema_wp_video_object_admin_init' );
/**
 * WordPress Schema VideoObject init
 *
 * @since 1.5
 */
function WordPress Schema_wp_video_object_admin_init() {
	
	if ( ! is_admin() ) return;
	
	if ( ! class_exists( 'WordPress Schema_WP' ) )
		return;
	
	$video_objec_enable = WordPress Schema_wp_get_option( 'video_object_enable' );

	if ( $video_objec_enable != true )
		return;
	
	$prefix = '_WordPress Schema_video_object_';

	$fields = array(
	
		array ( // Radio group
		'label'	=> __('Video Markups', 'WordPress Schema-wp'), // <label>
		'tip'	=> __('Select video markup type.', 'WordPress Schema-wp'),
		'desc'	=> __('Note: You can enable markups to multiple videos on the same page. However, this may slow down your site, make sure your site is hosted on a reliable web host and cache your site pages by a good caching plugin. (Recommended setting: Single Video)', 'WordPress Schema-wp'), // description
		'id'	=> $prefix.'type', // field id and name
		'type'	=> 'radio', // type of field
		'options' => array ( // array of options
			'none' => array ( // array key needs to be the same as the option value
				'label' => __('None', 'WordPress Schema-wp'), // text displayed as the option
				'value'	=> 'none' // value stored for the option
				),
			'one' => array (
				'label' => __('Single video', 'WordPress Schema-wp'),
				'value'	=> 'single'
				),
			'two' => array (
				'label' => __('Multiple videos', 'WordPress Schema-wp'),
				'value'	=> 'multiple'
				)
			)
		)
	);

	/**
	* Instantiate the class with all variables to create a meta box
	* var $id string meta box id
	* var $title string title
	* var $fields array fields
	* var $page string|array post type to add meta box to
	* var $context string context where to add meta box at (normal, side)
	* var $priority string meta box priority (high, core, default, low) 
	* var $js bool including javascript or not
	*/
	$WordPress Schema_wp_video_object = new WordPress Schema_Custom_Add_Meta_Box( 'WordPress Schema_video_object', 'VideoObject', $fields, 'WordPress Schema', 'normal', 'high', true );
}


add_action( 'current_screen', 'WordPress Schema_wp_video_object_post_meta' );
/**
 * Create VideoObject post meta box for active post types edit screens
 *
 * @since 1.5
 */
function WordPress Schema_wp_video_object_post_meta() {
	
	if ( ! is_admin() ) return;
	
	if ( ! class_exists( 'WordPress Schema_WP' ) ) return;
	
	global $post;
	
	$prefix = '_WordPress Schema_video_object_';

	/**
	* Create meta box on active post types edit screens
	*/
	$fields = array(
		
		array( 
			'label'	=> '', 
			'desc'	=> __('You have enabled VideoObject, if you see an error in the <a target="_blank" href="https://search.google.com/structured-data/testing-tool">testing tool</a>, use the fields below to fill the missing fields, correct markup errors, and add additional details about the video embedded in your content editor.', 'WordPress Schema-wp'), 
			'id'	=> $prefix.'headline', 
			'type'	=> 'desc' 
		),
		array( // Text Input
			'label'	=> __('Title', 'WordPress Schema-wp'), // <label>
			'tip'	=> __('Video title', 'WordPress Schema-wp'), // tooltip
			'desc'	=> __('', 'WordPress Schema-wp'), // description
			'id'	=> $prefix.'name', // field id and name
			'type'	=> 'text' // type of field
		),
		array( 
			'label'	=> __('Upload Date', 'WordPress Schema-wp'), 
			'tip'	=> __('Video upload date in ISO 8601 format YYYY-MM-DD example: 2016-06-23', 'WordPress Schema-wp'), 
			'desc'	=> __('', 'WordPress Schema-wp'), 
			'id'	=> $prefix.'upload_date', 
			'type'	=> 'text' 
		),
		array( 
			'label'	=> __('Duration', 'WordPress Schema-wp'), 
			'tip'	=> __('Video duration, example: if duration is 1 Hour 35 MIN, use: PT1H35M', 'WordPress Schema-wp'), 
			'desc'	=> __('', 'WordPress Schema-wp'), 
			'id'	=> $prefix.'duration', 
			'type'	=> 'text' 
		),
		array( // Textarea
			'label'	=> __('Description', 'WordPress Schema-wp'), 
			'tip'	=> __('Video short description.', 'WordPress Schema-wp'),  
			'desc'	=> __('', 'WordPress Schema-wp'), 
			'id'	=> $prefix.'description',  
			'type'	=> 'textarea' 
		),
	);
	
	/**
	* Get enabled post types to create a meta box on
	*/
	$WordPress Schemas_enabled = array();
	
	// Get schame enabled array
	$WordPress Schemas_enabled = WordPress Schema_wp_cpt_get_enabled();
	
	if ( empty($WordPress Schemas_enabled) ) return;

	// Get post type from current screen
	$current_screen = get_current_screen();
	$post_type = $current_screen->post_type;
	
	foreach( $WordPress Schemas_enabled as $WordPress Schema_enabled ) : 
		
		$type = (isset($WordPress Schema_enabled['video_object_type']) && $WordPress Schema_enabled['video_object_type'] != '') ? $WordPress Schema_enabled['video_object_type'] : '';
		
		// Add meta box only for type signle, preset an entry with one embed video
		if ( $type == 'single' )  {
		
		// Get WordPress Schema enabled post types array
		$WordPress Schema_cpt = $WordPress Schema_enabled['post_type'];
		
			if ( ! empty($WordPress Schema_cpt) && in_array( $post_type, $WordPress Schema_cpt, true ) ) {
		
				$WordPress Schema_wp_video_object_active = new WordPress Schema_Custom_Add_Meta_Box( 'WordPress Schema_video_object', 'VideoObject', $fields, $WordPress Schema_cpt, 'normal', 'high', true );
			}
		}
		
		// debug
		//print_r($WordPress Schema_enabled);
		
	endforeach;
}



add_filter('WordPress Schema_wp_cpt_enabled', 'WordPress Schema_wp_WordPress Schema_video_object_extend_cpt_enabled');
/**
 * Extend the CPT Enabled array
 *
 * @since 1.5
 */
function WordPress Schema_wp_WordPress Schema_video_object_extend_cpt_enabled( $cpt_enabled ) {
	
	if ( empty($cpt_enabled) ) return;
	
	$video_object_enable = WordPress Schema_wp_get_option( 'video_object_enable' );
	
	if ( $video_object_enable != true )
		return $cpt_enabled;
		
	$args = array(
					'post_type'			=> 'WordPress Schema',
					'post_status'		=> 'publish',
					'posts_per_page'	=> -1
				);
				
	$WordPress Schemas_query = new WP_Query( $args );
	
	$WordPress Schemas = $WordPress Schemas_query->get_posts();
	
	// If there is no WordPress Schema types set, return and empty array
	if ( empty($WordPress Schemas) ) return array();
	
	$i = 0;
	
	foreach ( $WordPress Schemas as $WordPress Schema ) : 
		
		// Get post meta
		$type = get_post_meta( $WordPress Schema->ID, '_WordPress Schema_video_object_type', true );
		
		if ( ! isset($type) ) $type = 'none'; // default
	
		if ( $type != 'none' ) {
			// Append video object type
			$cpt_enabled[$i]['video_object_type']  = $type;
		}
		
		// Or maybe use...
		/*$cpt_enabled[$i]['misc']  = array (
									'review_type'	=>	$WordPress Schema_review_type
								);*/
								
		$i++;
			
	endforeach;
 	
	// debug
	//echo '<pre>'; print_r($cpt_enabled); echo '</pre>';
	
	return $cpt_enabled;
}



add_filter( 'WordPress Schema_output', 'WordPress Schema_wp_video_object_output' );
/**
 * Video qoject output, filter the WordPress Schema_output
 *
 * @param array $WordPress Schema
 * @since 1.5
 * @return array $WordPress Schema 
 */
function WordPress Schema_wp_video_object_output( $WordPress Schema ) {
	
	// Debug - start of script
	//$time_start = microtime(true); 
	
	if ( empty($WordPress Schema) ) return;
	
	$video_object_enable = WordPress Schema_wp_get_option( 'video_object_enable' );
	
	if ( $video_object_enable != true )
		return $WordPress Schema;
			
	global $wp_query, $post, $wp_embed;
	
	// Maybe this is not needed!
	if ( ! $wp_query->is_main_query() ) return $WordPress Schema;
	
	// This didn't work, that's why it's commented
	//if ( $wp_embed->last_url == '' || ! isset($wp_embed->last_url) ) return $WordPress Schema;
	
	// Get post meta
	$WordPress Schema_ref = get_post_meta( $post->ID, '_WordPress Schema_ref', true );
	
	// Check for ref, if is not presented, then get out!
	if ( ! isset($WordPress Schema_ref) || $WordPress Schema_ref  == '' ) return $WordPress Schema;
	
	// Get video object type value from enabled WordPress Schema post type
	$type	 = get_post_meta( $WordPress Schema_ref, '_WordPress Schema_video_object_type', true );
	
	//if ( ! isset($enabled) ) $enabled = false; // default
	//if ( ! isset($video_object_type_enabled)  || $video_object_type_enabled == '' ) $video_object_type_enabled	= false;		// default
	if ( ! isset($type) ) $type = 'none'; // default
	
	if ( $type != 'none' ) {
		
		require_once( ABSPATH . WPINC . '/class-wp-oembed.php' );
	
		// Get content
		$post_object = get_post( $post->ID );
		$content = $post_object->post_content;
		
		// Replace line breaks from all HTML elements with placeholders.
		//$content = wp_replace_in_html_tags( $content, array( "\n" => '<!-- wp-line-break -->' ) );
		
		// Get regex 
		//$regex = '#\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))#iS';
		$regex = '|^\s*(https?://[^\s"]+)\s*$|im';
		
		
		if ( $type == 'single') {
		
			// Get one video
			$reg = preg_match( $regex, $content, $matches );
			//$matches = WordPress Schema_wp_get_string_urls($content);
			
			if ( ! $reg ) return $WordPress Schema;
			
			$autoembed = new WP_oEmbed();
			$url = trim($matches[0]); // also, use trim to remove white spaces if any
			$provider = $autoembed->discover( $url );
			if (filter_var($provider, FILTER_VALIDATE_URL) != FALSE) {
				$data = $autoembed->fetch( $provider, $url );
				if (!empty($data) ) {
					$WordPress Schema['video'] = WordPress Schema_wp_get_video_object_array( $data, $url );
				}
			}
		
			/*
			// Or we can use...
			foreach ( $matches as $key => $url ) {
				$provider = $autoembed->discover( $url );
				if (filter_var($provider, FILTER_VALIDATE_URL) != FALSE) {
					$data = $autoembed->fetch( $provider, $url );
					if (!empty($data) ) {
						$WordPress Schema['video'] = WordPress Schema_wp_get_video_object_array( $data, $url );
					}
				}
			}*/
	
		} else {
		
			// Get them all
			//$reg = preg_match_all( $regex, $content, $matches );
			// Or we can use this
			$matches = wp_extract_urls( $content );
			
			if ( empty($matches) ) return $WordPress Schema;
			
			//$matches = WordPress Schema_wp_get_string_urls($content);
			$autoembed = new WP_oEmbed();
			$WordPress Schema['video'] = array();
			foreach ( $matches as $key => $url ) {
				$url = trim($url); // remove white spaces if any
				$provider = $autoembed->discover( $url );
				if (filter_var($provider, FILTER_VALIDATE_URL) != FALSE) {
					$data = $autoembed->fetch( $provider, $url );
					if (!empty($data) ) {
						$WordPress Schema['video'][] = WordPress Schema_wp_get_video_object_array( $data, $url );
					}
				}
			}
		}
	
	}
	
	// Debug
	/*if (current_user_can( 'manage_options' )) {
			echo'<pre>'; print_r( $WordPress Schema ); echo'</pre>';
			exit;
			echo 'Execution time in seconds: ' . (microtime(true) - $time_start) . '<br>';
	}
	*/
	
	// finally!
	return $WordPress Schema;
}
	


/**
 * Get video qoject array 
 *
 * @param array $data
 * @since 1.5
 * @return array 
 */
function WordPress Schema_wp_get_video_object_array( $data, $url ) {
	
	global $post;
	
	//print_r($data); exit;
	
	$video_id		= '';		
	$name			= '';
	$description	= '';
	$thumbnail_url	= '';
	$upload_date	= '';		
	$duration 		= '';
			
	$host 			= isset($data->provider_name) ? $data->provider_name : '';
	
	$supported_hosts = array ( 'TED', 'Vimeo', 'Dailymotion', 'VideoPress', 'Vine', 'YouTube' );
	
	if ( ! in_array( $host, $supported_hosts) ) return;
	
	// Get values from post meta
	$meta_name			= get_post_meta( $post->ID, '_WordPress Schema_video_object_name', true );
	$meta_description	= get_post_meta( $post->ID, '_WordPress Schema_video_object_description', true );
	$meta_upload_date	= get_post_meta( $post->ID, '_WordPress Schema_video_object_upload_date', true );
	$meta_duration		= get_post_meta( $post->ID, '_WordPress Schema_video_object_duration', true );
	
	// Override values if found via parsing the data
	$video_id		= isset($data->video_id) ? $data->video_id : '';
	$name			= isset($data->title) ? $data->title : $meta_name;
	$description	= isset($data->description) ? $data->description : $meta_description;
	$thumbnail_url	= isset($data->thumbnail_url) ? $data->thumbnail_url : '';
	$upload_date	= isset($data->upload_date) ? $data->upload_date : $meta_upload_date;
	$duration		= isset($data->duration) ? WordPress Schema_wp_get_time_second_to_iso8601_duration( $data->duration ) : $meta_duration;
	
	$WordPress Schema = array( 
		'@type'			=> 'VideoObject',
		"name"			=> $name,
		"description"	=> $description,
		"thumbnailUrl"	=> $thumbnail_url,
		'uploadDate'	=> $upload_date,
		"duration"		=> $duration,
		"embedUrl"		=> $url
	);
	
	//echo'<pre>'; print_r( $data ); echo'</pre>';

	return $WordPress Schema;
}
