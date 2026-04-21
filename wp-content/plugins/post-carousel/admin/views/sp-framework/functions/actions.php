<?php 
/**
 * The admin action file.
 *
 * @package Smart_Post_Show
 * @subpackage Smart_Post_Show/admin/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! function_exists( 'pcp_get_option' ) ) {
	/**
	 * The pcp_get_option function.
	 *
	 * @param string $option The option unique ID.
	 * @param mixed  $default The default value for the option.
	 * @return statement
	 */
	function pcp_get_option( $option = '', $default = null ) {
		$options = get_option( 'sp_pcp_settings' );
		return ( isset( $options[ $option ] ) ) ? $options[ $option ] : $default;
	}
}

/**
 * Populate the taxonomy name list to the select option.
 *
 * @return void
 */
function sps_get_taxonomies() {
	// Check for nonce security.
	$nonce = ( ! empty( $_POST['nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	// Check for nonce security.
	if ( wp_verify_nonce( $nonce, 'spf_pcp_metabox_nonce' ) ) {
		$capability = apply_filters( 'sp_pc_dashboard_capability', 'manage_options' );
		if ( current_user_can( $capability ) ) {
			$the_pcp_post_types = ( ! empty( $_POST['pcp_post_types'] ) ) ? sanitize_text_field( wp_unslash( $_POST['pcp_post_types'] ) ) : '';
			$sp_post_types      = $the_pcp_post_types ? $the_pcp_post_types : get_post_types( array(), 'names' );

			$taxonomy_names = get_object_taxonomies( $sp_post_types, 'names' );
			if ( ! is_wp_error( $taxonomy_names ) && ! empty( $taxonomy_names ) ) {
				echo '<option value="">' . esc_html__( 'Select Taxonomy', 'post-carousel' ) . '</option>';
				foreach ( $taxonomy_names as $taxonomy => $label ) {
					echo '<option value="' . esc_attr( $label ) . '">' . esc_html( $label ) . '</option>';
				}
			}
		} else {
				wp_send_json_error( array( 'error' => esc_html__( 'You do not have required permissions to access.', 'post-carousel' ) ) );
		}
	} else {
		wp_send_json_error( array( 'error' => esc_html__( 'Error: Nonce verification has failed. Please try again.', 'post-carousel' ) ) );
	}
}
add_action( 'wp_ajax_sps_get_taxonomies', 'sps_get_taxonomies' );

/**
 * Populate the taxonomy terms list to the select option.
 *
 * @return void
 */
function sps_get_terms() {
	$nonce = ( ! empty( $_POST['nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	// Check for nonce security.
	if ( wp_verify_nonce( $nonce, 'spf_pcp_metabox_nonce' ) ) {
		$capability = apply_filters( 'sp_pc_dashboard_capability', 'manage_options' );
		if ( current_user_can( $capability ) ) {
			$the_pcp_taxonomy = ( ! empty( $_POST['pcp_post_taxonomy'] ) ) ? sanitize_text_field( wp_unslash( $_POST['pcp_post_taxonomy'] ) ) : '';
			$sp_post_types    = get_post_types( array(), 'names' );
			$pcp_taxonomy     = $the_pcp_taxonomy ? $the_pcp_taxonomy : get_object_taxonomies( $sp_post_types, 'names' );
			if ( version_compare( get_bloginfo( 'version' ), '4.5', '>=' ) ) {
				$terms = get_terms( array( 'taxonomy' => $pcp_taxonomy ) );
			} else {
				$terms = get_terms( array( $pcp_taxonomy ) );
			}

			foreach ( $terms as $key => $value ) {
				echo '<option value="' . esc_attr( $value->term_id ) . '">' . esc_html( $value->name ) . '</option>';
			}
		} else {
				wp_send_json_error( array( 'error' => esc_html__( 'You do not have required permissions to access.', 'post-carousel' ) ) );
		}
	} else {
		wp_send_json_error( array( 'error' => esc_html__( 'Error: Nonce verification has failed. Please try again.', 'post-carousel' ) ) );
	}
}

	add_action( 'wp_ajax_sps_get_terms', 'sps_get_terms' );

if ( ! function_exists( 'spf_chosen_ajax' ) ) {
	/**
	 *
	 * Chosen Ajax
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function spf_chosen_ajax() {

		$nonce = ( ! empty( $_POST['nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		$type  = ( ! empty( $_POST['type'] ) ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : '';
		$term  = ( ! empty( $_POST['term'] ) ) ? sanitize_text_field( wp_unslash( $_POST['term'] ) ) : '';
		$query = ( ! empty( $_POST['query_args'] ) ) ? wp_kses_post_deep( wp_unslash( $_POST['query_args'] ) ) : array(); // phpcs:ignore
		if ( wp_verify_nonce( $nonce, 'spf_chosen_ajax_nonce' ) ) {
			$capability = apply_filters( 'spf_chosen_ajax_capability', 'manage_options' );
			if ( current_user_can( $capability ) ) {
				$options = SP_PC_Fields::field_data( $type, $term, $query );
				wp_send_json_success( $options );
			} else {
				wp_send_json_error( array( 'error' => esc_html__( 'You do not have required permissions to access.', 'post-carousel' ) ) );
			}
		} else {
			wp_send_json_error( array( 'error' => esc_html__( 'Error: Nonce verification has failed. Please try again.', 'post-carousel' ) ) );
		}
	}
	add_action( 'wp_ajax_spf-chosen', 'spf_chosen_ajax' );
}

if ( ! function_exists( 'spf_set_icons' ) ) {
	/**
	 *
	 * Set icons for wp dialog
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function spf_set_icons() {
		global $post_type;
		if ( 'sp_post_carousel' === $post_type ) {
			?>
			<div id="spf-modal-icon" class="spf-modal spf-modal-icon">
				<div class="spf-modal-table">
				<div class="spf-modal-table-cell">
					<div class="spf-modal-overlay"></div>
					<div class="spf-modal-inner">
					<div class="spf-modal-title">
						<?php esc_html_e( 'Add Icon', 'post-carousel' ); ?>
						<div class="spf-modal-close spf-icon-close"></div>
					</div>
					<div class="spf-modal-header spf-text-center">
						<input type="text" placeholder="<?php esc_html_e( 'Search a Icon...', 'post-carousel' ); ?>" class="spf-icon-search" />
					</div>
					<div class="spf-modal-content">
						<div class="spf-modal-loading"><div class="spf-loading"></div></div>
						<div class="spf-modal-load"></div>
					</div>
					</div>
				</div>
				</div>
			</div>
			<?php
		}

	}
	add_action( 'admin_footer', 'spf_set_icons' );
	add_action( 'customize_controls_print_footer_scripts', 'spf_set_icons' );
}
