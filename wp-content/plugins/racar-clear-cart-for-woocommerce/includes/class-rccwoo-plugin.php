<?php
/**
*  
* includes/class-rccwoo-plugin.php
* Starts off the plugin
*  
*/


if( ! class_exists( 'rccwoo_Plugin' ) ) {	
	class rccwoo_Plugin {
		
		private $options;

		public static function init() {
			// runs only if woo is active
			if ( class_exists( 'WC_Payment_Gateway' ) ) {
				self::includes();
//trace(' active woo');
				

				//only front end
				if ( false === is_admin() ) {
					//add_action( 'wp_enqueue_scripts', array( __CLASS__ , 'rccwoo_register_resources' );
					add_action( 'woocommerce_cart_actions' , array( __CLASS__ , 'rccwoo_clear_cart_button' ) );
            		add_action( 'init', array( __CLASS__ , 'woocommerce_clear_cart_url') );
				}

				//only back end
				if ( true === is_admin() ) {
					add_action( 'admin_enqueue_scripts' , array( __CLASS__ , 'register_admin_resources' ) ) ;
				}
			} else { // woo is not active
				add_action( 'admin_notices', array( __CLASS__, 'woocommerce_missing_notice' ) );
//trace('not active woo');
			}
		}


		/**
		 * Includes.
		 */
		private static function includes() {
			/*if ( false === is_admin() ) {

			}*/

			if ( true === is_admin() ) {
				include_once 'admin/class-rccwoo_Action_List.php';
				include_once 'admin/class-rccwoo_Admin_Options.php';
			}
		}

		/*public static function rccwoo_register_frontend_resources(){
			rccwoo_script_file();
			rccwoo_style_file();
		}
		
		if( ! function_exists('rccwoo_script_file') ) {
			function rccwoo_script_file() {
				global $rccwoo_NOME_JAVASCRIPT;
				global $rccwoo_DIR_JAVASCRIPT;
				global $rccwoo_EXT_JAVASCRIPT;
				wp_register_script( $rccwoo_NOME_JAVASCRIPT, plugins_url( $rccwoo_DIR_JAVASCRIPT.$rccwoo_NOME_JAVASCRIPT.$rccwoo_EXT_JAVASCRIPT, __FILE__ ) );
				wp_enqueue_script( $rccwoo_NOME_JAVASCRIPT );
			}
		}
		
		if( ! function_exists('rccwoo_style_file') ) {
			function rccwoo_style_file() {
				global $rccwoo_NOME_STYLESHEET;
				global $rccwoo_DIR_STYLESHEET;
				global $rccwoo_EXT_STYLESHEET;
				// Respects SSL, Style.css is relative to the current file
				wp_register_style( $rccwoo_NOME_STYLESHEET, plugins_url( $rccwoo_DIR_STYLESHEET.$rccwoo_NOME_STYLESHEET.$rccwoo_EXT_STYLESHEET, __FILE__ ) );
				wp_enqueue_style( $rccwoo_NOME_STYLESHEET );
			}
		}*/

		
		/*if( ! function_exists('rccwoo_register_admin_resources') ) {
			private static function rccwoo_register_admin_resources() {
				rccwoo_register_admin_script();
				rccwoo_register_admin_style();
			}
		}*/

		/**
		 * Admin Resources
		 * @since release
		 */
		public static function register_admin_resources() {
				self::rccwoo_register_admin_script();
				self::rccwoo_register_admin_style();
			}

		/**
		 * Admin Styles
		 * @since release
		 */
		public static function rccwoo_register_admin_style() {
			global $rccwoo_NOME_ADMIN_STYLESHEET;
			global $rccwoo_DIR_STYLESHEET;
			global $rccwoo_EXT_STYLESHEET;
			wp_register_style( $rccwoo_NOME_ADMIN_STYLESHEET, $rccwoo_DIR_STYLESHEET.$rccwoo_NOME_ADMIN_STYLESHEET.$rccwoo_EXT_STYLESHEET , array() , '0.9' );
			wp_enqueue_style( $rccwoo_NOME_ADMIN_STYLESHEET );
		}
		
		/**
		 * Admin Scripts
		 * @since release
		 */
		public static function rccwoo_register_admin_script() {
			global $rccwoo_NOME_ADMIN_JAVASCRIPT;
			global $rccwoo_DIR_ADMIN_JAVASCRIPT;
			global $rccwoo_EXT_ADMIN_JAVASCRIPT;
			wp_register_script( $rccwoo_NOME_ADMIN_JAVASCRIPT, $rccwoo_DIR_ADMIN_JAVASCRIPT.$rccwoo_NOME_ADMIN_JAVASCRIPT.$rccwoo_EXT_ADMIN_JAVASCRIPT , array( 'jquery', 'wp-color-picker' ) , '1.0' , true );
			wp_enqueue_script( $rccwoo_NOME_ADMIN_JAVASCRIPT );
		}
		

		/**
		 * WooCommerce missing notice.
		 * @since 1.1.0
		 */
		public static function woocommerce_missing_notice() {
			include dirname( __FILE__ ) . '/admin/views/html-notice-missing-woocommerce.php';
		}

		/**
		 * Button output on cart page
		 * @since release
		 */
		public static function rccwoo_clear_cart_button() {
			include dirname( __FILE__ ) . '/views/class-racar-clear-cart-button.php';
		}


		/**
		 * Button action on cart page
		 * @since release
		 */
		public static function woocommerce_clear_cart_url() {
			if( ! isset( $_REQUEST['clear-cart'] ) ) {
				return;
			}

			$nonce_field_name = '_wpnonce_racar_clear_cart'; // Nome do campo oculto do nonce
    		$nonce_action_name = 'racar_clear_cart_nonce';    // Nome da ação do nonce

			if( ! isset( $_REQUEST[ $nonce_field_name ] ) OR ! wp_verify_nonce( sanitize_key( $_REQUEST[ $nonce_field_name ] ), $nonce_action_name ) ) {
				wp_die( 'Invalid Request', 'Security Fail', array( 'response' => 403 ) );
			}

			global $woocommerce;
			$options = get_option( 'rccwoo_settings' );
			$woocommerce->cart->empty_cart();
			if( isset( $options['rccwoo_redirection_url'] ) AND ! empty( $options['rccwoo_redirection_url'] ) ) {
				wp_safe_redirect( $options['rccwoo_redirection_url'] , 302 , 'Shop Clear Cart' );
				exit;
			}
		}

		
	}
}
	