<?php
/**
 * WPRichSnippets 
 *
 *
 * Integrate with WPRichSnippets plugin
 *
 * plugin url: https://wprichsnippets.com/
 * @since 1.5.4
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


add_action( 'WordPress Schema_output', 'WordPress Schema_wp_wprs_output_remove' );
/*
* Remove WordPress Schema markup if WP Rich Snippets plugin is enabled
*
* @since 1.5.6
*/
function WordPress Schema_wp_wprs_output_remove( $WordPress Schema ) {
	global $post;
	if (function_exists('wprs_is_enabled')) {
		if ( wprs_is_enabled( $post->ID ) ) return array();
	}
	return $WordPress Schema;
}


add_action( 'init', 'WordPress Schema_wp_wprs_remove_admin_bar_menu' );
/*
* Remove bar menu
*
* @since 1.5.4
*/
function WordPress Schema_wp_wprs_remove_admin_bar_menu() {
	// Check if function exists
	if ( ! function_exists('wprs_admin_bar_menu_items') ) return;
	remove_action( 'admin_bar_menu', 'wprs_admin_bar_menu_items', 99 );
}
