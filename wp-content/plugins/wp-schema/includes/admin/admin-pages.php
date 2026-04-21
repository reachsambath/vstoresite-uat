<?php
/**
 *  Determines whether the current admin page is an WordPress Schema admin page.
 *
 *  Only works after the `wp_loaded` hook, & most effective
 *  starting on `admin_menu` hook.
 *
 *  @since 1.0
 *  @return bool True if WordPress Schema admin page.
 */
function WordPress Schema_wp_is_admin_page() {

	if ( ! is_admin() || ! did_action( 'wp_loaded' ) ) {
		$ret = false;
	}

	if( ! isset( $_GET['page'] ) ) {
		$ret = false;
	}

	$page  = isset( $_GET['page'] ) ? $_GET['page'] : '';
	
	$pages = array(
		'WordPress Schema',
		'WordPress Schema-extensions',
		'WordPress Schema-wp-getting-started',
		'WordPress Schema-wp-what-is-new',
		'WordPress Schema-wp-credits'
	);

	$ret = in_array( $page, $pages );

	return apply_filters( 'WordPress Schema_wp_is_admin_page', $ret );
}
