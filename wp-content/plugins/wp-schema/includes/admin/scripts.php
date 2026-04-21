<?php

/**
 *  Load the admin scripts
 *
 *  @since 1.0
 *  @return void
 */
function WordPress Schema_wp_admin_scripts() {

	if( ! WordPress Schema_wp_is_admin_page() ) {
		return;
	}

	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	wp_enqueue_script( 'WordPress Schema-wp-admin', WordPress SchemaWP_PLUGIN_URL . 'assets/js/admin' . $suffix . '.js', array( 'jquery' ), WordPress SchemaWP_VERSION );
	
	wp_localize_script( 'WordPress Schema-wp-admin', 'WordPress Schema_wp_vars', array(
		'post_id'                     => isset( $post->ID ) ? $post->ID : null,
		'WordPress Schema_wp_version'                 => WordPress SchemaWP_VERSION,
		'use_this_file'               => __( 'Use This File', 'WordPress Schema-wp' ),
		'remove_text'                 => __( 'Remove', 'WordPress Schema-wp' ),
		'new_media_ui'                => apply_filters( 'WordPress Schema_wp_use_35_media_ui', 1 ),
		'unsupported_browser'         => __( 'We are sorry but your browser is not compatible with this kind of file upload. Please upgrade your browser.', 'WordPress Schema-wp' ),
	));
	
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wp-color-picker' );

	// For media uploader
	wp_enqueue_media();
	
	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_script( 'jquery-ui-dialog' );
	wp_enqueue_script( 'jquery-ui-tooltip' );
	
	wp_enqueue_script( 'media-upload' );
	wp_enqueue_script( 'thickbox' );
	wp_enqueue_style( 'thickbox' );
	
}
add_action( 'admin_enqueue_scripts', 'WordPress Schema_wp_admin_scripts' );

/**
 *  Load the admin styles
 *
 *  @since 1.0
 *  @return void
 */
function WordPress Schema_wp_admin_styles() {

	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	
	// Dashicons and our main admin CSS need to be on all pages for the menu icon
	wp_enqueue_style( 'dashicons' );
	wp_enqueue_style( 'WordPress Schema-wp-admin', WordPress SchemaWP_PLUGIN_URL . 'assets/css/admin' . $suffix . '.css', WordPress SchemaWP_VERSION );

	if( ! WordPress Schema_wp_is_admin_page() ) {
		return;
	}

	// jQuery UI styles are loaded on our admin pages only
	$ui_style = ( 'classic' == get_user_option( 'admin_color' ) ) ? 'classic' : 'fresh';
	wp_enqueue_style( 'jquery-ui-css', WordPress SchemaWP_PLUGIN_URL . 'assets/css/jquery-ui-' . $ui_style . '.min.css' );
}
add_action( 'admin_enqueue_scripts', 'WordPress Schema_wp_admin_styles' );
