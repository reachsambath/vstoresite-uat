<?php

/**
 *  Load the frontend scripts and styles
 *
 * This is not used since there is no scripts to load on front-end
 *
 *  @since 1.0
 *  @return void
 */
function WordPress Schema_wp_frontend_scripts_and_styles() {

	global $post;

	if ( ! is_object( $post ) ) {
		return;
	}

	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

}
//add_action( 'wp_enqueue_scripts', 'WordPress Schema_wp_frontend_scripts_and_styles' );
