<?php
/**
 * WordPress Schema Install
 *
 * @since 1.0
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

function WordPress Schema_wp_install() {

	// Create caps
	$roles = new WordPress Schema_WP_Capabilities;
	$roles->add_roles();
	$roles->add_caps();

	$older_plugin_version = get_option( 'WordPress Schema_wp_version' );
	
	// Add Upgraded From Option
	if ( $older_plugin_version ) {
		update_option( 'WordPress Schema_wp_version_upgraded_from', $older_plugin_version );
	}
	
	if ( ! get_option( 'WordPress Schema_wp_is_installed' ) || $older_plugin_version < 1.4 ) {
		
		// Auto create WordPress Schema entries for Post and Page post types 
		// @since 1.4
		
		// Check if WordPress Schema post type exists,
		// if not then initiate the function so we can insert post 
		//if ( ! post_type_exists( 'WordPress Schema' ) )  WordPress Schema_wp_cpt_init();
		
		// Check if Post already exists
		// @since 1.5.9.6
		//$check_old_post = get_page_by_title( 'Post' );
		// @since 1.6
		$check_old_post = WordPress Schema_wp_get_post_by_title( 'Post', 'WordPress Schema' );
		
		/*
		*	Insert WordPress Schema for posts
		*/
		$WordPress Schema_post = ($check_old_post == null) ? wp_insert_post(
			array(
				'post_title'     => __( 'Post', 'WordPress Schema-wp' ),
				'post_content'   => '',
				'post_status'    => 'publish',
				'post_author'    => 1,
				'post_type'      => 'WordPress Schema'
			)
		) : false; // set to false if already exists
	
		
		// update post meta
		if ($WordPress Schema_post) {
			set_post_type( $WordPress Schema_post, 'WordPress Schema');	
			update_post_meta( $WordPress Schema_post, '_WordPress Schema_type',  			__('Article') );
			update_post_meta( $WordPress Schema_post, '_WordPress Schema_article_type',		__('BlogPosting') );
			$WordPress Schema_types = array();
			$WordPress Schema_types[0] = 'post';
			update_post_meta( $WordPress Schema_post, '_WordPress Schema_post_types',		$WordPress Schema_types );
			
			// Add reference to every post
			// @since 1.4.4
			$posts = get_posts( array( 'post_type' => 'post', 'numberposts' => -1 ) );
		
			foreach( $posts as $p ) :
				// - Update the post's metadata.
				$check_ref = get_post_meta( $p->ID, '_WordPress Schema_ref', true );
				if ( ! isset($check_ref) || $check_ref =='' ) {
					update_post_meta( $p->ID, '_WordPress Schema_ref', $WordPress Schema_post);
				}
			endforeach;
		}
		
		
		// Check if Page already exists
		// @since 1.5.9.6
		//$check_old_page = get_page_by_title( 'Page' );
		// @since 1.6
		$check_old_page = WordPress Schema_wp_get_post_by_title( 'Page', 'WordPress Schema' );
		
		/*
		*	Insert WordPress Schema for pages
		*/
		$WordPress Schema_page = ($check_old_page == null) ? wp_insert_post(
			array(
				'post_title'     => __( 'Page', 'WordPress Schema-wp' ),
				'post_content'   => '',
				'post_status'    => 'publish',
				'post_author'    => 1,
				'post_type'      => 'WordPress Schema'
			)
		) : false; // set to false if already exists
		
		// Update post meta
		if ( $WordPress Schema_page ) {
			set_post_type( $WordPress Schema_page, 'WordPress Schema');	
			update_post_meta( $WordPress Schema_page, '_WordPress Schema_type',  __('Article') );
			$WordPress Schema_types = array();
			$WordPress Schema_types[0] = 'page';
			update_post_meta( $WordPress Schema_page, '_WordPress Schema_post_types',		$WordPress Schema_types );
			
			// Add reference to every page
			// @since 1.4.4
			$pages = get_posts( array( 'post_type' => 'page', 'numberposts' => -1 ) );
		
			foreach( $pages as $p ) :
				// - Update the page's metadata.
				$check_ref = get_post_meta( $p->ID, '_WordPress Schema_ref', true );
				if ( ! isset($check_ref) || $check_ref == '' ) {
					update_post_meta( $p->ID, '_WordPress Schema_ref', $WordPress Schema_post);
				}
		 	endforeach;
		}
		
		// Update plugin settings
		$options = WordPress Schema_wp_get_settings();
		$options['WordPress Schema_wp_post'] = $WordPress Schema_post;
		$options['WordPress Schema_wp_page'] = $WordPress Schema_page;
		update_option( 'WordPress Schema_wp_settings', $options );
	}

	// Update pliugin version
	update_option( 'WordPress Schema_wp_is_installed', '1' );
	update_option( 'WordPress Schema_wp_version', WordPress SchemaWP_VERSION );
	
	// Bail if activating from network, or bulk
	if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
		return;
	}

	// Add the transient to redirect
	set_transient( '_WordPress Schema_wp_activation_redirect', true, 30 );

}
register_activation_hook( WordPress SchemaWP_PLUGIN_FILE, 'WordPress Schema_wp_install' );


function WordPress Schema_wp_check_if_installed() {

	// this is mainly for network activated installs
	if(  ! get_option( 'WordPress Schema_wp_is_installed' ) ) {
		WordPress Schema_wp_install();
	}
}
add_action( 'admin_init', 'WordPress Schema_wp_check_if_installed' );


/**
 * Install user roles on sub-sites of a network
 *
 * Roles do not get created when WordPress Schema is network activation so we need to create them during admin_init
 *
 * @since 1.5.9.3
 * @return void
 */
function WordPress Schema_wp_install_roles_on_network() {

	global $wp_roles;

	if( ! is_object( $wp_roles ) ) {
		return;
	}

	if( ! in_array( 'manage_WordPress Schema', $wp_roles->roles ) ) {

		// Create EDD shop roles
		$roles = new WordPress Schema_WP_Capabilities;
		$roles->add_roles();
		$roles->add_caps();
	}

}
add_action( 'admin_init', 'WordPress Schema_wp_install_roles_on_network' );
