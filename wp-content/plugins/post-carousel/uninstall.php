<?php
/**
 * Uninstall.php for cleaning plugin database.
 *
 * Trigger the file when plugin is deleted.
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * @link        https://smartpostshow.com/
 * @since      2.0.0
 *
 * @package    Smart_Post_Show
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Collect and delete all data of the plugin.
 *
 * @return void
 */
function sp_post_carousel_data_delete() {
	// Delete plugin option settings.
	$option_name = 'sp_post_carousel_settings';
	delete_option( $option_name );
	// For a multi-site.
	delete_site_option( $option_name );
	// Delete Smart Post Show post type.
	$post_carousel_posts = get_posts(
		array(
			'numberposts' => -1,
			'post_type'   => 'sp_post_carousel',
			'post_status' => 'any',
		)
	);
	foreach ( $post_carousel_posts as $post_carousel ) {
		wp_delete_post( $post_carousel->ID, true );
	}

	// Delete Smart Post Show post meta.
	delete_post_meta_by_key( 'sp_pcp_layouts' );
	delete_post_meta_by_key( 'sp_pcp_view_options' );
}

// Load Smart Post Show file.
require plugin_dir_path( __FILE__ ) . '/main.php';
$pcp_options = get_option( 'sp_post_carousel_settings' );
$delete_data = isset( $pcp_options['pcp_delete_all_data'] ) ? $pcp_options['pcp_delete_all_data'] : false;

if ( $delete_data ) {
	sp_post_carousel_data_delete();
}
