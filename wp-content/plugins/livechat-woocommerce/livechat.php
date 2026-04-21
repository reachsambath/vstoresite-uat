<?php
/**
 * Main plugin file.
 *
 * @package LiveChat
 */

/**
 * Plugin Name: LiveChat WooCommerce
 * Plugin URI: https://www.livechat.com/marketplace/apps/woocommerce
 * Description: Live chat software for live help, online sales and customer support. This plugin allows to quickly install LiveChat on any WooCommerce website.
 * Version: 5.0.10
 * Author: LiveChat
 * Author URI: https://www.livechat.com
 * Text Domain: wp-live-chat-software-for-wordpress
 * Domain Path: /languages
 *
 * Copyright: © 2022 LiveChat.
 * License: GNU General Public License v3.0
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

if ( function_exists( 'LiveChat\text_suite_init' ) ) {
	/** Some other plugin is already loaded - skip loading */
	return;
}

require_once __DIR__ . '/vendor/autoload.php';

define( 'TEXT_PLUGIN_VERSION', '5.0.10' );
define( 'TEXT_PLUGIN_DIR', __DIR__ );
define( 'TEXT_PLUGIN_BASE', plugin_basename( __FILE__ ) );
define( 'TEXT_PLUGIN_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'TEXT_PARTNER_ID', 'woocommerce' );
define( 'TEXT_DOMAIN', 'wordpress.livechat.com' );
define( 'TEXT_PUBLIC_KEY_URL', 'https://wordpress.livechat.com/api/v1/public/key.pem' );
define( 'WP_MARKETPLACE_PLUGIN_URL', 'https://wordpress.org/plugins/livechat-woocommerce/' );
define( 'TEXT_PRODUCT_ID', 1 );
define( 'TEXT_DB_PREFIX', 'text_wp_' );

require_once __DIR__ . '/includes/plugin.php';
\LiveChat\text_suite_init();
