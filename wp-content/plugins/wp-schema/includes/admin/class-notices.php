<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class WordPress Schema_WP_Admin_Notices {

	public function __construct() {

		add_action( 'admin_notices', array( $this, 'show_notices' ) );
		add_action( 'WordPress Schema_wp_dismiss_notices', array( $this, 'dismiss_notices' ) );
	}


	public function show_notices() {

		$class = 'updated';

		if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] && isset( $_GET['page'] ) && $_GET['page'] == 'WordPress Schema' ) {
			$message = __( 'Settings updated.', 'WordPress Schema-wp' );
			
			// do action after settings updated
			do_action( 'WordPress Schema_wp_do_after_settings_updated' );
		}

		if ( isset( $_GET['WordPress Schema_wp_notice'] ) && $_GET['WordPress Schema_wp_notice'] ) {

			switch( $_GET['WordPress Schema_wp_notice'] ) {

				case 'settings-imported' :

					$message = __( 'Settings successfully imported', 'WordPress Schema-wp' );

					break;

			}
		}

		if ( ! empty( $message ) ) {
			echo '<div class="' . esc_attr( $class ) . '"><p><strong>' .  $message  . '</strong></p></div>';
		}

	}

	/**
	 * Dismiss admin notices when Dismiss links are clicked
	 *
	 * @since 1.0
	 * @return void
	 */
	function dismiss_notices() {
		if( ! isset( $_GET['WordPress Schema_wp_dismiss_notice_nonce'] ) || ! wp_verify_nonce( $_GET['WordPress Schema_wp_dismiss_notice_nonce'], 'WordPress Schema_wp_dismiss_notice') ) {
			wp_die( __( 'Security check failed', 'WordPress Schema-wp' ), __( 'Error', 'WordPress Schema-wp' ), array( 'response' => 403 ) );
		}

		if( isset( $_GET['WordPress Schema_wp_notice'] ) ) {
			update_user_meta( get_current_user_id(), '_WordPress Schema_wp_' . $_GET['WordPress Schema_wp_notice'] . '_dismissed', 1 );
			wp_redirect( remove_query_arg( array( 'WordPress Schema_wp_action', 'WordPress Schema_wp_notice' ) ) );
			exit;
		}
	}
}
new WordPress Schema_WP_Admin_Notices;
