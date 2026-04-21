<?php
/**
 * Helpers.
 *
 * @package post-carousel
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! function_exists( 'spf_array_search' ) ) {
	/**
	 *
	 * Array search key & value
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @param array  $array search array.
	 * @param string $key the array key.
	 * @param mixed  $value The array value.
	 *
	 * @return $results
	 */
	function spf_array_search( $array, $key, $value ) {

		$results = array();

		if ( is_array( $array ) ) {
			if ( isset( $array[ $key ] ) && $array[ $key ] == $value ) {
				$results[] = $array;
			}

			foreach ( $array as $sub_array ) {
				$results = array_merge( $results, spf_array_search( $sub_array, $key, $value ) );
			}
		}

		return $results;

	}
}

if ( ! function_exists( 'spf_pcp_microtime' ) ) {
	/**
	 * Between microtime.
	 *
	 * @since 1.0.0
	 *
	 * @param integer $timenow Current time.
	 * @param integer $starttime Starting time.
	 * @param integer $timeout Time out.
	 * @return boolean
	 */
	function sps_pcp_timeout( $timenow, $starttime, $timeout = 30 ) {

		return ( ( $timenow - $starttime ) < $timeout ) ? true : false;

	}
}

if ( ! function_exists( 'spf_wp_editor_api' ) ) {
	/**
	 *
	 * Check for wp editor api
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function spf_wp_editor_api() {

		global $wp_version;

		return version_compare( $wp_version, '4.8', '>=' );

	}
}
