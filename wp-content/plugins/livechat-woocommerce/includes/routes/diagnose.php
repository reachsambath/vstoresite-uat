<?php
/**
 * Diagnose route.
 *
 * @package LiveChat\Routes
 */

namespace LiveChat\Routes;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use WP_REST_Request;
use WP_Error;
use stdClass;

use function LiveChat\text_is_auto_update_enabled;
use function LiveChat\text_get_token;

/**
 * Check if user has permission to access diagnose route.
 *
 * @param WP_REST_Request $request WP_REST_Request object.
 * @return true|WP_Error
 */
function text_get_diagnose_permission_callback( $request ) {
	$custom_header = $request->get_header( 'X-Text-Token' );

	if ( ! $custom_header ) {
		return new WP_Error( 'rest_unauthorized', 'Missing X-Text-Token header', array( 'status' => 401 ) );
	}

	$cert_req = wp_remote_get( TEXT_PUBLIC_KEY_URL );
	$cert     = wp_remote_retrieve_body( $cert_req );

	try {
		$token_data = JWT::decode( $custom_header, new Key( $cert, 'RS256' ) );
		$request->set_param( 'websiteUuid', $token_data['websiteUuid'] );

		return true;
	} catch ( \Exception $e ) {
		return new WP_Error( 'rest_unauthorized', $e->getMessage(), array( 'status' => 401 ) );
	}
}

/**
 * Collects and returns diagnose data.
 *
 * @param WP_REST_Request $request WP_REST_Request object.
 * @return \WP_REST_Response|WP_Error
 */
function text_get_diagnose_callback( $request ) {
	$plugin_id  = $request->get_param( 'pluginId' );
	$store_uuid = $request->get_param( 'websiteUuid' );

	$token_data = text_get_token();

	$response = new stdClass();

	if ( ! $token_data ) {
		return rest_ensure_response( $response );
	}

	if ( $token_data['websiteUuid'] !== $store_uuid ) {
		return new WP_Error(
			'rest_bad_request',
			'Website already connected with UUID',
			array(
				'status'               => 400,
				'saved_store_uuid'     => $token_data['websiteUuid'],
				'requested_store_uuid' => $store_uuid,
			)
		);
	}

	$response->widget_script_url = $token_data['widgetScriptUrl'];
	$response->plugin_ver        = TEXT_PLUGIN_VERSION;
	$response->php_ver           = phpversion();
	$response->wp_ver            = get_bloginfo( 'version' );
	$response->auto_update       = text_is_auto_update_enabled();

	if ( defined( 'WOOCOMMERCE_VERSION' ) ) {
		$response->woocommerce_ver = WOOCOMMERCE_VERSION;
	}

	if ( defined( 'ELEMENTOR_VERSION' ) ) {
		$response->elementor_ver = ELEMENTOR_VERSION;
	}

	return rest_ensure_response( $response );
}

/**
 * Registers diagnose route.
 *
 * @return void
 */
function text_register_get_diagnose_route() {
	register_rest_route(
		'text/v1',
		'/(?P<pluginId>\d+)/diagnose',
		array(
			'methods'             => 'GET',
			'callback'            => 'LiveChat\routes\text_get_diagnose_callback',
			'permission_callback' => 'LiveChat\routes\text_get_diagnose_permission_callback',
			'args'                => array(
				'pluginId' => array(
					'validate_callback' => function ( $param ) {
						if ( intval( $param ) === TEXT_PRODUCT_ID ) {
							return true;
						}

						return new WP_Error(
							'rest_invalid_param',
							'Product ID is not supported',
							array( 'status' => 400 )
						);
					},
				),
			),
		)
	);
}
