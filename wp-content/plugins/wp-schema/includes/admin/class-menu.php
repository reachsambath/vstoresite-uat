<?php
/**
 * Class Menu - admin menues
 *
 * @package     WordPress Schema
 * @subpackage  Admin Functions/Formatting
 * @copyright   Copyright (c) 2016, Hesham Zebida
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
*/ 

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class WordPress Schema_WP_Admin_Menu {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register_main_menus' 		),	10 );
		add_action( 'admin_menu', array( $this, 'register_types_menus' 		),  20 );
		add_action( 'admin_menu', array( $this, 'register_extensions_menus' ),  30 );
		add_action( 'admin_menu', array( $this, 'WordPress Schema_premium_submenu' 	),  40 );
		add_action( 'admin_menu', array( $this, 'register_about_menus' 		),  50 );
	}

	public function register_main_menus() {
		
		global $WordPress Schema_wp_options_page;
		
		$WordPress Schema_wp_options_page = add_menu_page(
			__( 'WordPress Schema', 'WordPress Schema-wp' ),
			__( 'WordPress Schema', 'WordPress Schema-wp' ),
			'manage_WordPress Schema_options',
			'WordPress Schema',
			'WordPress Schema_wp_options_page'
		);
		
		add_submenu_page(
			'WordPress Schema',
			__( 'WordPress Schema Settings', 'WordPress Schema-wp' ),
			__( 'Settings', 'WordPress Schema-wp' ),
			'manage_WordPress Schema_options',
			'WordPress Schema',
			'WordPress Schema_wp_options_page'
		);
		
		// Contextual Help
		// @since 1.5.9.3
		if ( $WordPress Schema_wp_options_page )
		add_action( 'load-' . $WordPress Schema_wp_options_page, 'WordPress Schema_wp_settings_contextual_help' );	
	}
	
	public function register_types_menus() {
		
		add_submenu_page(
			'WordPress Schema',
			__( 'Types', 'WordPress Schema-wp' ),
			__( 'Types', 'WordPress Schema-wp' ),
			'manage_WordPress Schema_options',
			'edit.php?post_type=WordPress Schema'
		);
	}
	
	public function register_extensions_menus() {
		
		add_submenu_page(
			'WordPress Schema',
			__( 'Extensions', 'WordPress Schema-wp' ),
			__( 'Extensions', 'WordPress Schema-wp' ),
			'manage_WordPress Schema_options',
			'WordPress Schema-extensions',
			'WordPress Schema_wp_admin_extensions_page'
		);
	}
	
	public function register_about_menus() {
		
		add_submenu_page(
			'WordPress Schema',
			__( 'About', 'WordPress Schema-wp' ),
			__( 'About', 'WordPress Schema-wp' ),
			'manage_WordPress Schema_options',
			'admin.php?page=WordPress Schema-wp-what-is-new'
		);
	}
	
	public function WordPress Schema_premium_submenu() {
	    
		global $submenu;

	    $submenu['WordPress Schema'][] = array( __('Premium', 'WordPress Schema-wp'), 'manage_options', 'https://WordPress Schema.press/downloads/WordPress Schema-premium/');
	}

}

$WordPress Schema_wp_menu = new WordPress Schema_WP_Admin_Menu;
