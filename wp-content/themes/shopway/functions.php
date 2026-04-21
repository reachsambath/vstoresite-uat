<?php
/**
 * Theme functions and definitions
 *
 * @package ShopWay
 */

/**
 * After setup theme hook
 */
function shopway_theme_setup(){
    /*
     * Make child theme available for translation.
     * Translations can be filed in the /languages/ directory.
     */
    load_child_theme_textdomain( 'shopway' );	
}
add_action( 'after_setup_theme', 'shopway_theme_setup' );

/**
 * Load assets.
 */

function shopway_theme_css() {
	wp_enqueue_style( 'shopway-parent-theme-style', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'shopway_theme_css', 99);

require get_stylesheet_directory() . '/theme-functions/controls/class-customize.php';

/**
 * Import Options From Parent Theme
 *
 */
function shopway_parent_theme_options() {
	$shopway_mods = get_option( 'theme_mods_shopire' );
	if ( ! empty( $shopway_mods ) ) {
		foreach ( $shopway_mods as $shopway_mod_k => $shopway_mod_v ) {
			set_theme_mod( $shopway_mod_k, $shopway_mod_v );
		}
	}
}
add_action( 'after_switch_theme', 'shopway_parent_theme_options' );