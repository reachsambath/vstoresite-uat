<?php

/**
 * Plugin Name: Product Builder for WooCommerce
 * Plugin URI: https://villatheme.com/
 * Description: Increases sales with Building product configuration for your online store. Help build a complete product from small components
 * Version: 1.0.24
 * Author: VillaTheme
 * Author URI: https://villatheme.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: woo-product-builder
 * Domain Path: /languages
 * Copyright 2018-2025 VillaTheme.com. All rights reserved.
 * Requires Plugins: woocommerce
 * Requires PHP: 7.0
 * Requires at least: 5.0
 * Tested up to: 6.8
 * WC requires at least: 7.0
 * WC tested up to: 10.3
 */

if (!defined('ABSPATH')) {
    exit;
}

define('VI_WPRODUCTBUILDER_F_VERSION', '1.0.24');
/**
 * Detect plugin. For use on Front End only.
 */
include_once(ABSPATH . 'wp-admin/includes/plugin.php');
if (!class_exists('VI_WPRODUCTBUILDER_F')) {

    class VI_WPRODUCTBUILDER_F {
        public function __construct() {
            //compatible with 'High-Performance order storage (COT)'
            add_action('before_woocommerce_init', array($this, 'before_woocommerce_init'));
            if (is_plugin_active('woocommerce-product-builder/woocommerce-product-builder.php')) {
                return;
            }
            add_action('plugins_loaded', array($this, 'init'));
            register_activation_hook(__FILE__, array($this, 'install'));
            register_deactivation_hook(__FILE__, array($this, 'uninstall'));
        }

        public function init() {
            $include_dir = plugin_dir_path(__FILE__) . 'includes/';
            if (!class_exists('VillaTheme_Require_Environment')) {
                include_once $include_dir . 'support.php';
            }

            $environment = new VillaTheme_Require_Environment([
                    'plugin_name' => 'Product Builder for WooCommerce',
                    'php_version' => '7.0',
                    'wp_version' => '5.0',
                    'require_plugins' => [
                        [
                            'slug' => 'woocommerce',
                            'name' => 'WooCommerce',
							'defined_version' => 'WC_VERSION',
                            'version' => '7.0',
                        ]
                    ]
                ]
            );

            if ($environment->has_error()) {
                return;
            }

            $init_file = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . "woo-product-builder" . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "define.php";
            require_once $init_file;
        }

        public function before_woocommerce_init() {
            if (class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil')) {
                \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
            }
        }


        public function install() {
            flush_rewrite_rules();
        }

        public function uninstall() {
            flush_rewrite_rules();
        }
    }

    new VI_WPRODUCTBUILDER_F();
}