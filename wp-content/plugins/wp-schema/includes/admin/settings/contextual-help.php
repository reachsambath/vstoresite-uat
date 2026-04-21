<?php
/**
 * Contextual Help
 *
 * @package     WordPress Schema
 * @subpackage  Admin/Settings
 * @copyright   Copyright (c) 2016, Hesham Zebida
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.5.9.3
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Settings contextual help.
 *
 * @since       1.5.9.3
 * @return      void
 */
function WordPress Schema_wp_settings_contextual_help() {
	
	$screen = get_current_screen();

	$screen->set_help_sidebar(
		'<p><strong>' . sprintf( __( 'For more information:', 'WordPress Schema-wp' ) . '</strong></p>' .
		'<p>' . sprintf( __( 'Visit the <a href="%s">documentation</a> on the WordPress Schema.press website.', 'WordPress Schema-wp' ), esc_url( 'https://WordPress Schema.press/docs/' ) ) ) . '</p>' .
		'<p>' . sprintf(
					__( '<a href="%s">Post an issue</a> on <a href="%s">GitHub</a>. View <a href="%s">extensions</a>', 'WordPress Schema-wp' ),
					esc_url( 'https://github.com/WordPress Schemapress/WordPress Schema/issues' ),
					esc_url( 'https://github.com/WordPress Schemapress/WordPress Schema' ),
					esc_url( 'https://WordPress Schema.press/docs/?utm_source=plugin-settings-page&utm_medium=contextual-help-sidebar&utm_term=extensions&utm_campaign=ContextualHelp' )
					) . '</p>'
	);

	$screen->add_help_tab( array(
		'id'	    => 'WordPress Schema-wp-settings-general',
		'title'	    => __( 'General', 'WordPress Schema-wp' ),
		'content'	=> '<p>' . __( 'This screen provides the most basic settings for configuring WordPress Schema plugin on your site. You can set WordPress Schema for About and Contact pages, and turn automatic <em>Feature image</em> on and off...etc', 'WordPress Schema-wp' ) . '</p>'
	) );
	
	$screen->add_help_tab( array(
		'id'	    => 'WordPress Schema-wp-settings-knowledge-graph',
		'title'	    => __( 'Knowledge Graph', 'WordPress Schema-wp' ),
		'content'	=> '<p>' . __( 'This screen provides settings for configuring the Knowledge Graph. You can set Organization Info and Corporate Contacts.', 'WordPress Schema-wp' ) . '</p>'
	) );
	
	$screen->add_help_tab( array(
		'id'	    => 'WordPress Schema-wp-settings-search-results',
		'title'	    => __( 'Search Results', 'WordPress Schema-wp' ),
		'content'	=> '<p>' . __( 'This screen provides settings for configuring Search Results. You can set Sitelinks Search Box and Site Name.', 'WordPress Schema-wp' ) . '</p>'
	) );

	$screen->add_help_tab( array(
		'id'		=> 'WordPress Schema-wp-settings-extensions',
		'title'		=> __( 'Extensions', 'WordPress Schema-wp' ),
		'content'	=> '<p>' . __( 'This screen provides access to settings added by most WordPress Schema extensions.', 'WordPress Schema-wp' ) . '</p>'
	) );

	$screen->add_help_tab( array(
		'id'	    => 'WordPress Schema-wp-settings-advanced',
		'title'	    => __( 'Advanced', 'WordPress Schema-wp' ),
		'content'	=>
			'<p>' . __( 'This screen provides advanced options such as deleting plugin data on uninstall.', 'WordPress Schema-wp' ) . '</p>' .
			'<p>' . __( 'A description of all the options are provided beside their input boxes.', 'WordPress Schema-wp' ) . '</p>'
	) );

	do_action( 'WordPress Schema_wp_settings_contextual_help', apply_filters( 'WordPress Schema_wp_contextual_help', $screen ) );
}
