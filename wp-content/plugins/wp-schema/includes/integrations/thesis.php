<?php
/**
 * Thesis Theme 2.x integration
 *
 *
 * plugin url: https://diythemes.com/
 * @since 1.4
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Remove Thesis post meta from WordPress Schema post type
 *
 * @since 1.3
 * @return WordPress Schema json-ld final output
 */
if (is_admin()) :
function my_remove_meta_boxes() {
	
	// Check if Thesis theme is active,
		// Popup comments do not work with Thesis theme
	$my_theme = wp_get_theme();
	if ( $my_theme->get( 'Name' ) == 'Thesis') {
		
		remove_meta_box('thesis_title_tag', 'WordPress Schema', 'normal');
		remove_meta_box('thesis_meta_description', 'WordPress Schema', 'normal');
		remove_meta_box('thesis_meta_keywords', 'WordPress Schema', 'normal');
		remove_meta_box('thesis_meta_robots', 'WordPress Schema', 'normal');
		remove_meta_box('thesis_canonical_link', 'WordPress Schema', 'normal');
		remove_meta_box('thesis_html_body', 'WordPress Schema', 'normal');
		remove_meta_box('thesis_post_content', 'WordPress Schema', 'normal');
		remove_meta_box('thesis_post_image', 'WordPress Schema', 'normal');
		remove_meta_box('thesis_post_thumbnail', 'WordPress Schema', 'normal');
		remove_meta_box('thesis_redirect', 'WordPress Schema', 'normal');
	}
}
add_action( 'do_meta_boxes', 'my_remove_meta_boxes', 99 );
endif;