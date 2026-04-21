<?php
/**
 * The admin validate functions file.
 *
 * @package Smart_Post_Show
 * @subpackage Smart_Post_Show/admin/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! function_exists( 'spf_validate_email' ) ) {
	/**
	 * Email validate
	 *
	 * @param string $value The email.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function spf_validate_email( $value ) {

		if ( ! filter_var( $value, FILTER_VALIDATE_EMAIL ) ) {
			return esc_html__( 'Please write a valid email address!', 'post-carousel' );
		}

	}
}


if ( ! function_exists( 'spf_validate_numeric' ) ) {
	/**
	 *
	 * Numeric validate
	 *
	 * @param string $value The number.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function spf_validate_numeric( $value ) {

		if ( ! is_numeric( $value ) ) {
			return esc_html__( 'Please write a numeric data!', 'post-carousel' );
		}

	}
}


if ( ! function_exists( 'spf_validate_required' ) ) {
	/**
	 *
	 * Required validate
	 *
	 * @param string $value The required data.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function spf_validate_required( $value ) {

		if ( empty( $value ) ) {
			return esc_html__( 'Error! This field is required!', 'post-carousel' );
		}

	}
}


if ( ! function_exists( 'spf_validate_url' ) ) {
	/**
	 *
	 * URL validate
	 *
	 * @param string $value The URL.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function spf_validate_url( $value ) {

		if ( ! filter_var( $value, FILTER_VALIDATE_URL ) ) {
			return esc_html__( 'Please write a valid url!', 'post-carousel' );
		}

	}
}
