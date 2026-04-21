<?php
/**
 * Fired during plugin updates
 *
 * @link       https://smartpostshow.com/
 * @since      2.2.0
 *
 * @package    Smart_Post_Show
 * @subpackage Smart_Post_Show/includes
 */

// don't call the file directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fired during plugin updates.
 *
 * This class defines all code necessary to run during the plugin's updates.
 */
class Smart_Post_Show_Updates {

	/**
	 * DB updates that need to be run
	 *
	 * @var array
	 */
	private static $updates = array(
		'2.2.0'  => 'updates/update-2.2.0.php',
		'2.4.13' => 'updates/update-2.4.13.php',
		'3.0.0'  => 'updates/update-3.0.0.php',
	);

	/**
	 * Binding all events
	 *
	 * @since 2.2.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'do_updates' ) );
	}

	/**
	 * Check if need any update
	 *
	 * @since 2.2.0
	 *
	 * @return boolean
	 */
	public function is_needs_update() {
		$installed_version = get_option( 'smart_post_show_version' );
		$first_version     = get_option( 'smart_post_show_first_version' );
		$activation_date   = get_option( 'smart_post_show_activation_date' );

		if ( false === $installed_version ) {
			update_option( 'smart_post_show_version', SMART_POST_SHOW_VERSION );
			update_option( 'smart_post_show_db_version', SMART_POST_SHOW_VERSION );
		}
		if ( false === $first_version ) {
			update_option( 'smart_post_show_first_version', SMART_POST_SHOW_VERSION );
		}
		if ( false === $activation_date ) {
			update_option( 'smart_post_show_activation_date', current_time( 'timestamp' ) );
		}

		if ( version_compare( $installed_version, SMART_POST_SHOW_VERSION, '<' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Do updates.
	 *
	 * @since 2.2.0
	 *
	 * @return void
	 */
	public function do_updates() {
		$this->perform_updates();
	}

	/**
	 * Perform all updates
	 *
	 * @since 2.2.0
	 *
	 * @return void
	 */
	public function perform_updates() {
		if ( ! $this->is_needs_update() ) {
			return;
		}

		$installed_version = get_option( 'smart_post_show_version' );

		foreach ( self::$updates as $version => $path ) {
			if ( version_compare( $installed_version, $version, '<' ) ) {
				include $path;
				update_option( 'smart_post_show_version', $version );
			}
		}

		update_option( 'smart_post_show_version', SMART_POST_SHOW_VERSION );
	}
}
new Smart_Post_Show_Updates();
