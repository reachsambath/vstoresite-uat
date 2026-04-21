<?php
/**
 * Main plugin functions (like registering menu etc).
 *
 * @package LiveChat
 */

namespace LiveChat;

use Exception;
use WP_Error;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use function LiveChat\Templates\text_render_resources_page;
use function LiveChat\Templates\text_render_successfully_connected_view;
use function LiveChat\Templates\text_render_connect_view;

/**
 * Initialize the plugin.
 *
 * @param string $plugin Activated plugin path.
 * @return bool
 */
function is_text_plugin( string $plugin ): bool {
	return TEXT_PLUGIN_BASE === $plugin;
}

/**
 * Register hooks and initialize the plugin.
 *
 * @return void
 */
function text_suite_init(): void {
	add_action( 'activated_plugin', 'LiveChat\text_activate_plugin' );
	add_action( 'plugins_loaded', 'LiveChat\text_load_plugin' );
	add_action( 'rest_api_init', 'LiveChat\routes\text_register_get_diagnose_route' );
}

/**
 * Enable auto update for the plugin.
 *
 * @param string $plugin Plugin path.
 * @return void
 */
function text_enable_auto_update( string $plugin ): void {
	$auto_updates = get_option( 'auto_update_plugins', array() );

	if ( ! in_array( $plugin, $auto_updates, true ) ) {
		$auto_updates[] = $plugin;
		update_option( 'auto_update_plugins', $auto_updates );
	}
}


/**
 * Redirect to settings page after plugin activation.
 *
 * @param string $plugin Plugin path.
 * @return void
 */
function text_activate_plugin( string $plugin ): void {
	if ( ! is_text_plugin( $plugin ) ) {
		return;
	}

	text_enable_auto_update( $plugin );
	wp_safe_redirect( admin_url( 'admin.php?page=livechat_settings' ) );
	exit;
}

/**
 * Load plugin styles.
 *
 * @return void
 */
function text_load_styles(): void {
	wp_enqueue_style(
		'text-style',
		TEXT_PLUGIN_URL . '/includes/css/text.css',
		array(),
		TEXT_PLUGIN_VERSION,
		'all'
	);
}

/**
 * Check if the WooCommerce plugin is active.
 *
 * @return bool
 */
function text_is_woo_plugin_active(): bool {
	return in_array( 'woocommerce/woocommerce.php', get_option( 'active_plugins', array() ), true );
}

/**
 * Check if the Elementor plugin is active.
 *
 * @return bool
 */
function text_is_elementor_plugin_active(): bool {
	return (
		in_array(
			'elementor/elementor.php',
			get_option( 'active_plugins', array() ),
			true
		) &&
		class_exists( '\Elementor\Plugin' )
	);
}

/**
 * Load plugin and register hooks.
 *
 * @return void
 */
function text_load_plugin(): void {
	text_load_plugin_for_website();
	text_load_plugin_for_wpadmin();
	$addons = array();

	if ( text_is_woo_plugin_active() ) {
		$addons[] = 'woocommerce';

		add_action( 'wp_ajax_text-refresh-cart', 'LiveChat\refresh_cart_action' );
		add_action( 'wp_ajax_nopriv_text-refresh-cart', 'LiveChat\refresh_cart_action' );
	}

	if ( text_is_elementor_plugin_active() ) {
		$addons[] = 'elementor';

		add_action( 'elementor/init', 'LiveChat\text_register_categories' );
		add_filter( 'elementor/icons_manager/additional_tabs', 'LiveChat\text_register_elementor_common_icons' );

		if ( has_action( 'elementor/widgets/register' ) ) {
			add_action( 'elementor/widgets/register', 'LiveChat\text_register_elementor_widgets' );
		} else {
			add_action( 'elementor/widgets/widgets_registered', 'LiveChat\text_register_elementor_widgets' );
		}
	}

	add_action(
		'wp_enqueue_scripts',
		function () use ( $addons ) {
			wp_enqueue_script(
				'text-connect',
				TEXT_PLUGIN_URL . '/includes/js/textConnect.js',
				array(),
				TEXT_PLUGIN_VERSION,
				false // Load in header.
			);

			wp_localize_script(
				'text-connect',
				'textConnect',
				array(
					'addons'   => $addons,
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'visitor'  => text_get_visitor_data(),
				)
			);
		}
	);
	add_action( 'wp_ajax_disconnect_account', 'LiveChat\text_disconnect_account' );
}

/**
 * Load plugin for website.
 *
 * @return void
 */
function text_load_plugin_for_website(): void {
	if ( is_admin() ) {
		return;
	}

	// Load widget with initial data.
	add_action( 'wp_enqueue_scripts', 'LiveChat\text_load_widget' );
}

/**
 * Register connect notification as admin notice.
 *
 * @return void
 */
function text_load_connect_notification(): void {
	$token_data = text_get_token();

	if ( $token_data ) {
		return;
	}

	add_action( 'admin_notices', 'LiveChat\templates\text_render_connect_notification' );
}

/**
 * Add action links to plugin page.
 *
 * @param array $actions Action links.
 * @return array
 */
function add_action_links( $actions ) {
	$mylinks = array(
		'<a href="' . admin_url( 'admin.php?page=livechat_settings' ) . '">Settings</a>',
	);
	$actions = array_merge( $mylinks, $actions );
	return $actions;
}

/**
 * Load plugin for WP admin.
 *
 * @return void
 */
function text_load_plugin_for_wpadmin(): void {
	if ( ! is_admin() ) {
		return;
	}

	// Load global styles.
	add_action( 'admin_init', 'LiveChat\text_load_styles' );

	// Load connect notice.
	text_load_connect_notification();

	// Load menu.
	add_action( 'admin_menu', 'LiveChat\text_add_admin_menu' );

	// Load deactivation handler only for plugins page.
	add_action(
		'admin_enqueue_scripts',
		function ( $hook ) {
			if ( 'plugins.php' !== $hook ) {
				return;
			}

			text_register_deactivation_handler();
		}
	);

	// Add action links.
	add_filter( 'plugin_action_links_' . TEXT_PLUGIN_BASE, 'LiveChat\add_action_links' );
}

/**
 * Register deactivation handler script.
 *
 * @return void
 */
function text_register_deactivation_handler(): void {
	wp_enqueue_script(
		'deactivation-handler',
		TEXT_PLUGIN_URL . '/includes/js/deactivationHandler.js',
		array(),
		TEXT_PLUGIN_VERSION,
		true
	);

	wp_localize_script(
		'deactivation-handler',
		'deactivationHandler',
		array(
			'deactivationFeedbackUrl' => get_deactivation_feedback_url(),
		)
	);
}

/**
 * Opens Agent App in new tab
 *
 * @param string $url URL to filter.
 * @return string
 */
function go_to_livechat_link( $url ) {
	if ( strpos( $url, 'livechatlink' ) !== false ) {
		return 'https://my.livechatinc.com';
	}
	return $url;
}

/**
 * Register register admin menu.
 *
 * @return void
 */
function text_add_admin_menu(): void {
	$token_data = text_get_token();

	$menu_slug  = 'livechat';
	$capability = 'administrator';

	add_menu_page(
		'LiveChat',
		$token_data ? 'LiveChat' : 'LiveChat <span class="awaiting-mod">!</span>',
		$capability,
		$menu_slug,
		'LiveChat\text_settings_page',
		TEXT_PLUGIN_URL . '/resources/images/livechat-icon.svg'
	);

	add_submenu_page(
		$menu_slug,
		__( 'Settings', 'text-app-plugin' ),
		__( 'Settings', 'text-app-plugin' ),
		$capability,
		$menu_slug . '_settings',
		'LiveChat\text_settings_page'
	);

	add_submenu_page(
		$menu_slug,
		__( 'Resources', 'text-app-plugin' ),
		__( 'Resources', 'text-app-plugin' ),
		$capability,
		$menu_slug . '_resources',
		'LiveChat\text_resources_page'
	);

	add_filter( 'clean_url', 'LiveChat\go_to_livechat_link' );

	remove_submenu_page( $menu_slug, $menu_slug );

	if ( ! $token_data ) {
		return;
	}

	add_submenu_page(
		$menu_slug,
		__( 'Go to LiveChat', 'text-app-plugin' ),
		__( 'Go to LiveChat', 'text-app-plugin' ),
		$capability,
		$menu_slug . 'link',
		'__return_false'
	);
}

/**
 * Disconnect account action.
 *
 * @return void
 */
function text_disconnect_account(): void {
	check_ajax_referer( 'disconnect_account' );

	try {
		text_cleanup();
		wp_send_json_success();
	} catch ( Exception $e ) {
		wp_send_json_error(
			new WP_Error( $e->getCode(), $e->getMessage() )
		);
	}
}

/**
 * Validate and save token in database.
 *
 * @param string $token Token to validate.
 * @return void
 */
function text_validate_and_save_token( string $token ): void {
	$cert_req = wp_remote_get( TEXT_PUBLIC_KEY_URL );
	$cert     = wp_remote_retrieve_body( $cert_req );

	try {
		$token_data = JWT::decode( $token, new Key( $cert, 'RS256' ) );
	} catch ( \Exception $e ) {
		// TODO: decide if we want to keep it.
		// phpcs:disable WordPress.PHP.DevelopmentFunctions.error_log_error_log
		error_log( $e->getMessage() );
		// phpcs:enable WordPress.PHP.DevelopmentFunctions.error_log_error_log
		return;
	}

	$keys_to_keep = array( 'websiteUuid', 'widgetScriptUrl' );

	// Save token.
	text_update_token(
		array_intersect_key(
			get_object_vars( $token_data ),
			array_flip( $keys_to_keep )
		)
	);

	// Remove legacy options.
	text_remove_legacy_options();
}

/**
 * Show settings page.
 *
 * @return void
 */
function text_settings_page(): void {
	if ( array_key_exists( 'token', $_GET ) && array_key_exists( '_wpnonce', $_GET ) ) {
		wp_verify_nonce( $_GET['_wpnonce'], 'text_connect' );
		text_validate_and_save_token( $_GET['token'] );
	}

	$token_data = text_get_token();

	if ( ! $token_data ) {
		text_render_connect_view();
		return;
	}

	text_render_successfully_connected_view();
}

/**
 * Show resources page.
 *
 * @return void
 */
function text_resources_page(): void {
	text_render_resources_page();
}

/**
 * Load widget script.
 *
 * @return void
 */
function text_load_widget(): void {
	$token_data = text_get_token();

	if ( ! $token_data || ! $token_data['widgetScriptUrl'] ) {
		text_load_legacy_widget();
		return;
	}

	wp_enqueue_script(
		'text-widget',
		$token_data['widgetScriptUrl'],
		array(),
		TEXT_PLUGIN_VERSION,
		array(
			'async'     => true,
			'in_footer' => true,
		)
	);
}

/**
 * Load legacy widget script.
 *
 * @return void
 */
function text_load_legacy_widget(): void {
	$widget_url = text_get_legacy_widget_url();

	if ( ! $widget_url ) {
		return;
	}

	wp_enqueue_script(
		'text-legacy-widget',
		$widget_url,
		array(),
		TEXT_PLUGIN_VERSION,
		array(
			'async'     => true,
			'in_footer' => true,
		)
	);
}
