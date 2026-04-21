<?php 
/**
 * Plugin Name: RaCar Clear Cart for WooCommerce
 * Plugin URI:  https://github.com/rafacarvalhido/racar-clear-cart-woo
 * Description: This plugin adds a convenient button to empty the shopping cart. Clear the entire cart with one click.
 * Version:     2.1.6
 * Author:      Rafa Carvalhido
 * Author URI:  https://profissionalwp.dev.br/blog/contato/rafa-carvalhido/
 * Text Domain: racar-clear-cart-for-woocommerce
 * Domain Path: /languages
 * Requires at least: 4.9
 * Tested up to: 6.8
 * WC requires at least: 3.0.0
 * WC tested up to: 9.9.5
 * Requires Plugins: woocommerce
 * Requires PHP: 7.4
 * License: GPLv2 or later
 * Copyright © 2018-2025 Rafa Carvalhido
 * @package RaCar Clear Cart for WooCommerce

RaCar Clear Cart WooCommerce is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
RaCar Clear Cart WooCommerce is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with RaCar Clear Cart WooCommerce.
*/



	/*=========================================================================*/ 
	/* SECURITY CHECKS */
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	if ( ! defined( 'WPINC' ) ) die; // If this file is called directly, abort.
	
	
	if ( ! defined( 'rccwoo_PLUGIN_FILE' ) ) {
		define( 'rccwoo_PLUGIN_FILE', __FILE__ );//Z:\WP-DesktopServer\plugintester.dev.cc\wp-content\plugins\racar-clear-cart-for-woocommerce\racar-clear-cart-for-woocommerce.php

	}
	
	//start plugin
	if ( ! class_exists( 'rccwoo_Plugin' ) ) {
		include_once dirname( __FILE__ ) . '/includes/class-rccwoo-plugin.php';
		add_action( 'plugins_loaded', array( 'rccwoo_Plugin', 'init' ) );
	}

	add_action( 'before_woocommerce_init', function() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	} );
	
	/*=========================================================================*/
	// SYSTEM VARIABLES
	global $rccwoo_basename;
	$rccwoo_basename = plugin_basename(__FILE__);//racar-clear-cart-for-woocommerce/racar-clear-cart-for-woocommerce.php

	$rccwoo_VERSION = '2.1.6';
	
	$rccwoo_NOME_STYLESHEET = 'rccwoo-stylesheet';
	$rccwoo_DIR_STYLESHEET = plugins_url('css/', __FILE__);
	$rccwoo_EXT_STYLESHEET = '.css';
	
	$rccwoo_NOME_JAVASCRIPT = 'rccwoo-javascript';
	$rccwoo_DIR_JAVASCRIPT = plugins_url('js/', __FILE__);
	$rccwoo_EXT_JAVASCRIPT = '.js';
	
	$rccwoo_OPTIONSON = TRUE;
	
	$rccwoo_NOME_ADMIN_STYLESHEET = 'rccwoo-admin-style';
	$rccwoo_DIR_STYLESHEET = plugins_url('includes/admin/css/', __FILE__);
	$rccwoo_EXT_STYLESHEET = '.css';
	
	$rccwoo_NOME_ADMIN_JAVASCRIPT = 'rccwoo-admin-javascript';
	$rccwoo_DIR_ADMIN_JAVASCRIPT = plugins_url('includes/admin/js/', __FILE__);
	$rccwoo_EXT_ADMIN_JAVASCRIPT = '.js';
	
	
	$allowed_html = array(
		'div' 	=> array(
					'class' => array(),
					'id'    => array(),
				),
		'a'		=> array(
					'href'		=> array(),
					'title'		=> array(),
					'target'	=> array(),
					'rel'		=> array(),
				),
		'i'		=> array(
					'class'		=> array()
				),
		'input'		=> array(
					'type'		=> array(),
					'id'		=> array(),
					'class'		=> array(),
					'name'		=> array(),
					'value'		=> array(),
				),
	);
	
	
	/*=========================================================================*/
	
	/**
	 * Load plugin textdomain.
	 *
	 * @since 1.0.0
	 * @removed 2.1.6
	 */
	// function rccwoo_load_textdomain() {
	// 	$textdomain_loaded = load_plugin_textdomain( 'racar-clear-cart-for-woocommerce', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
	// }
	// add_action( 'init', 'rccwoo_load_textdomain' );
