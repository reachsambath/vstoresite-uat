<?php 
/*
*
* class Options Page
* class_Admin_Options.php
*
*/
/**
 * Prevent direct access to the script.
 */
if( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! class_exists( 'rccwoo_Admin_Options' ) ) {	
	class rccwoo_Admin_Options {
		/**
		 * Holds the values to be used in the fields callbacks
		 */
		private $options;

		private $page_title = 'RaCar rccwoo Options Page';
		private	$menu_title = 'RaCar Plugins';
		private	$capability = 'manage_options';
		private	$menu_slug = 'racar-admin-page.php';
		private	$function_main_menu = 'racar_admin_page'; // if altering this, alter throughout this file.
		private	$icon_url = 'dashicons-lightbulb'; //dashicons
		private	$position = 99; 
		
		private $sub_page_title = 'Racar Clear Cart for WooCommerce';
		private $sub_menu_title = 'Clear Cart WC';
		private $capability_sub = 'manage_options';
		private $page_url = 'rccwoo-config';
		private $function_sub_page = 'rccwoo_options_page'; // if altering this, alter throughout this file.

		/**
		 * Start up
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'racar_main_admin_menu' ) );
			add_action( 'admin_menu', array( $this, 'rccwoo_admin_menu' ) );
			add_action( 'admin_init', array( $this, 'remove_admin_submenu' ) );
			add_action( 'admin_init', array( $this, 'register_plugin_settings' ) );
		}
		
		public function racar_main_admin_menu() {
			global $menu;
			global $submenu;
			if( empty( $GLOBALS['admin_page_hooks']['racar-admin-page.php'] ) ) {
				add_menu_page( 
					$this->page_title, 
					$this->menu_title, 
					$this->capability, 
					'racar-admin-page.php', 
					array( $this , $this->function_main_menu ),
					$this->icon_url, 
					$this->position
				);
			}	
		}
		
		public function remove_admin_submenu() {
			remove_submenu_page( 'racar-admin-page.php' , 'racar-admin-page.php' );
		}

		
		public function racar_admin_page(){
			?>
			<div class="wrap">
				<h1>Plugins RaCar</h1>
				
			</div>
			<?php
		}
		public function rccwoo_admin_menu() { 
			add_submenu_page( 
				'racar-admin-page.php',
				$this->sub_page_title,
				$this->sub_menu_title,
				$this->capability_sub,
				$this->page_url,
				array( $this , $this->function_sub_page )
			);
		}
		
		public function rccwoo_options_page() { 
			global $rccwoo_VERSION;
			// Set class property
			$this->options = get_option( 'rccwoo_settings' );
			?>
				<form action='options.php' method='post'>
					<?php
						if( function_exists( 'wp_nonce_field' ) ) 
							wp_nonce_field( 'rccwoo_update_options' , 'nonce_rccwoo_settings' ); 
					?>
					<h2>RaCar Clear Cart for WooCommerce v.<?php echo esc_html( $rccwoo_VERSION ); ?></h2>
					
					<?php 
					settings_fields( 'rccwoo_option_group_1' );
					do_settings_sections( 'rccwoo-options-page' );
					submit_button();
					?>

				</form>
			<?php
		}

		
		
		
		public function register_plugin_settings() { 
			register_setting(
				'rccwoo_option_group_1',  // Option group
				'rccwoo_settings' , // Options name
				array( $this, 'sanitize' ) // Sanitize 
			);
			
			add_settings_section(
				'rccwoo_section_1', // ID
				__( 'Preferences', 'racar-clear-cart-for-woocommerce' ), // title
				array( $this, 'plugin_settings_section_callback' ), // callback
				'rccwoo-options-page' // Page
			);
			
			/*add_settings_field( 
				'rccwoo_enabled', // ID
				'<span class="option-name">' . __( 'Enable Plugin', 'racar-clear-cart-for-woocommerce' ) . '<span class="quick-exp">(' . __( 'Show Button', 'racar-clear-cart-for-woocommerce' ) . ')</span></span>', // Title 
				array( $this , 'rccwoo_enabled_render' ), // Callback
				'rccwoo-options-page',  // Page
				'rccwoo_section_1'  // Section   
			);*/
		
			add_settings_field( 
				'rccwoo_button_text', 
				__( 'Button Text', 'racar-clear-cart-for-woocommerce' ), 
				array( $this , 'rccwoo_button_text_render' ), 
				'rccwoo-options-page', 
				'rccwoo_section_1' 
			);
			
			add_settings_field( 
				'rccwoo_confirm_text', 
				__( 'Confirmation Text', 'racar-clear-cart-for-woocommerce' ), 
				array( $this , 'rccwoo_confirm_text_render' ), 
				'rccwoo-options-page', 
				'rccwoo_section_1' 
			);
			
			add_settings_field( 
				'rccwoo_use_default_css_class',
				'<span class="option-name">' . __( 'Do NOT use default CSS Class "button"', 'racar-clear-cart-for-woocommerce' ) . '<span class="quick-exp">("button" ' . __( 'is the default CSS class for many themes. Should you not want to use this class in your button, check the box, please.', 'racar-clear-cart-for-woocommerce' ) . ')</span></span>',
				array( $this, 'rccwoo_use_default_css_class_render' ),
				'rccwoo-options-page',
				'rccwoo_section_1' 
			);
			
			add_settings_field( 
				'rccwoo_button_css_classes',
				'<span class="option-name">' . __( 'CSS Classes', 'racar-clear-cart-for-woocommerce' ) . '<span class="quick-exp">(' . __( 'Type in (or delete) the CSS classes you wish your button to have', 'racar-clear-cart-for-woocommerce' ) . ')</span></span>',
				array( $this, 'rccwoo_button_css_classes_render' ),
				'rccwoo-options-page',
				'rccwoo_section_1' 
			); 
			
			add_settings_field( 
				'rccwoo_radiobox_1', 
				sprintf(
					'<span class="option-name">%s<span class="quick-exp">(%s<br>%s)</span></span>',
					__( 'Side (float)', 'racar-clear-cart-for-woocommerce' ),
					__( 'Choose at what side from the update cart button', 'racar-clear-cart-for-woocommerce' ),
					__( 'should the clear cart button be', 'racar-clear-cart-for-woocommerce' )
				),
				array( $this , 'rccwoo_radiobox_1_render' ), 
				'rccwoo-options-page', 
				'rccwoo_section_1' 
			);
			
			add_settings_field( 
				'rccwoo_background',
				__( 'Background Color', 'racar-clear-cart-for-woocommerce' ),
				array( $this, 'rccwoo_background_render' ),
				'rccwoo-options-page',
				'rccwoo_section_1' 
			); // id, title, display cb, page, section
			
			add_settings_field( 
				'rccwoo_text_color',
				__( 'Button Text Color', 'racar-clear-cart-for-woocommerce' ),
				array( $this, 'rccwoo_text_color_render' ),
				'rccwoo-options-page',
				'rccwoo_section_1' 
			); 
			
			add_settings_field( 
				'rccwoo_background_hover_color',
				__( 'Background Hover Color', 'racar-clear-cart-for-woocommerce' ),
				array( $this, 'rccwoo_background_hover_color_render' ),
				'rccwoo-options-page',
				'rccwoo_section_1' 
			); // id, title, display cb, page, section
			
			add_settings_field( 
				'rccwoo_hover_text_color',
				__( 'Button Text Color on Hover', 'racar-clear-cart-for-woocommerce' ),
				array( $this, 'rccwoo_hover_text_color_render' ),
				'rccwoo-options-page',
				'rccwoo_section_1' 
			); 

			add_settings_field( 
				'rccwoo_redirection_url',
				'<span class="option-name">' . __( 'Redirection after clearing cart', 'racar-clear-cart-for-woocommerce' ) . '<span class="quick-exp">(' . __( 'Type in the whole URL address you\'d like<br>to send customers if not to the cart itself', 'racar-clear-cart-for-woocommerce' ) . ')</span></span>',
				array( $this, 'rccwoo_redirection_url_render' ),
				'rccwoo-options-page',
				'rccwoo_section_1' 
			); 
			
			add_settings_field( 
				'rccwoo_rate',
				__( 'Plugin Rating', 'racar-clear-cart-for-woocommerce' ),
				array( $this, 'rccwoo_rate_render' ),
				'rccwoo-options-page',
				'rccwoo_section_1' 
			);

			add_settings_field( 
				'rccwoo_support',
				__( 'Support', 'racar-clear-cart-for-woocommerce' ),
				array( $this, 'rccwoo_support_render' ),
				'rccwoo-options-page',
				'rccwoo_section_1' 
			);

			add_settings_field( 
				'rccwoo_donate',
				__( 'Show your Appreciation', 'racar-clear-cart-for-woocommerce' ),
				array( $this, 'rccwoo_donate_render' ),
				'rccwoo-options-page',
				'rccwoo_section_1' 
			);
		}
		
		
		
		
		

		/**
		 * Sanitize each setting field as needed
		 *
		 * @param array $input Contains all settings fields as array keys
		 */
		public function sanitize( $input ) {
			
			// if this fails, check_admin_referer() will automatically print a "failed" page and die.
			
			$new_input = array();
			// only sanitize and save if wp_nonce_field is right
			if ( ! empty( $_POST ) && check_admin_referer( 'rccwoo_update_options', 'nonce_rccwoo_settings' ) ) {
				if( isset( $input['rccwoo_button_text'] ) )
					$new_input['rccwoo_button_text'] = sanitize_text_field( $input['rccwoo_button_text'] );
				
				if( isset( $input['rccwoo_confirm_text'] ) )
					$new_input['rccwoo_confirm_text'] = sanitize_text_field( $input['rccwoo_confirm_text'] );
				
				/*if( isset( $input['rccwoo_enabled'] ) )
				$new_input['rccwoo_enabled'] = absint( $input['rccwoo_enabled'] );*/
			
				if( isset( $input['rccwoo_use_default_css_class'] ) )
					$new_input['rccwoo_use_default_css_class'] = absint( $input['rccwoo_use_default_css_class'] );
			
				if( isset( $input['rccwoo_button_css_classes'] ) ) {
					$classes = explode( " " , $input['rccwoo_button_css_classes'] );
					$cleaned_classes = array();
					foreach( $classes as $class ) {
						if( $class != '') {
							$cleaned_classes[] =  sanitize_html_class( $class ) ;
						}
					}
					
					$new_input['rccwoo_button_css_classes'] = substr( implode( " " , $cleaned_classes) , 0 , 100 );
				} else {
					$new_input['rccwoo_button_css_classes'] = '';
				}
			
				if( isset( $input['rccwoo_radiobox_1'] ) )
				$new_input['rccwoo_radiobox_1'] = $input['rccwoo_radiobox_1'];
			
				// if( isset( $input['rccwoo_background'] ) ) {
				// 	$background = trim( $input['rccwoo_background'] );
				// 	$background = wp_strip_all_tags( stripslashes( $background ) );
				// 	if( FALSE === $this->check_color( $background ) ) {
				// 		// Set the error message
				// 		add_settings_error( 'rccwoo_settings', 'rccwoo_bg_error', __('Insert a valid color for Background' , 'racar-clear-cart-for-woocommerce' ) , 'error' ); // $setting, $code, $message, $type
				// 		// Get the previous valid value
				// 		// $new_input['rccwoo_background'] = $this->options['rccwoo_background'];
				// 	} else {
				// 		$new_input['rccwoo_background'] = $input['rccwoo_background'];
				// 	}
				// }
				if( isset( $input['rccwoo_background'] ) AND ! empty( $input['rccwoo_background'] ) ) {
					$background = trim( $input['rccwoo_background'] );
					$background = wp_strip_all_tags( stripslashes( $background ) );
					$bg_color = '';
					if( '#' != $background[0]) {
						$background = '#' . $background;
					}
					$new_input['rccwoo_background'] = sanitize_hex_color( $background );
				}
				
				if( isset( $input['rccwoo_background_hover_color'] ) AND ! empty( $input['rccwoo_background_hover_color'] ) ) {
					$background = trim( $input['rccwoo_background_hover_color'] );
					$background = wp_strip_all_tags( stripslashes( $background ) );
					$bg_color = '';
					if( '#' != $background[0]) {
						$background = '#' . $background;
					}
					$new_input['rccwoo_background_hover_color'] = sanitize_hex_color( $background );
				}
				
				if( isset( $input['rccwoo_text_color'] ) AND ! empty( $input['rccwoo_text_color'] ) ) {
					$textcolor = trim( $input['rccwoo_text_color'] );
					$textcolor = wp_strip_all_tags( stripslashes( $textcolor ) );
					$txt_color = '';
					if( '#' != $textcolor[0]) {
						$textcolor = '#' . $textcolor;
					}
					$new_input['rccwoo_text_color'] = sanitize_hex_color( $textcolor );
				}
				
				if( isset( $input['rccwoo_hover_text_color'] ) AND ! empty( $input['rccwoo_hover_text_color'] ) ) {
					$textcolor = trim( $input['rccwoo_hover_text_color'] );
					$textcolor = wp_strip_all_tags( stripslashes( $textcolor ) );
					$txt_color = '';
					if( '#' != $textcolor[0]) {
						$textcolor = '#' . $textcolor;
					}
					$new_input['rccwoo_hover_text_color'] = sanitize_hex_color( $textcolor );
				}

				if( isset( $input['rccwoo_redirection_url'] ) )
					$new_input['rccwoo_redirection_url'] = sanitize_url( $input['rccwoo_redirection_url'] , array('http', 'https') );
				
			}

			return $new_input;
		}
		
		public function check_color( $value ) { 
			if ( preg_match( '/^#[a-f0-9]{6}$/i', $value ) ) { // if user insert a HEX color with #     
				return true;
			}
			return false;
		}

		/** 
		 * Print the Section text
		 */
		public function plugin_settings_section_callback() { 
			echo esc_html__( 'Enter your preferences below:', 'racar-clear-cart-for-woocommerce' );
		}

		/*public function rccwoo_enabled_render() { 
			$checked = ( isset( $this->options['rccwoo_enabled'] ) && $this->options['rccwoo_enabled'] == 1 ) ? 1 : 0;
			$html = '<input type="checkbox" id="rccwoo_enabled" name="rccwoo_settings[rccwoo_enabled]" value="1"' . checked( 1, $checked, false ) . '/>';
			echo wp_kses_post( $html );
		}*/
		
		public function rccwoo_button_text_render() { 
			printf(
				'<input type="text" id="button-text" name="rccwoo_settings[rccwoo_button_text]" value="%s" placeholder="%s" />',
				isset( $this->options['rccwoo_button_text'] ) ? esc_attr( $this->options['rccwoo_button_text']) : '' ,
				esc_html__( 'Clear Cart','racar-clear-cart-for-woocommerce' )
			);
		}
		
		public function rccwoo_confirm_text_render() { 
			printf(
				'<input type="text" id="confirm-text" name="rccwoo_settings[rccwoo_confirm_text]" value="%s" placeholder="%s" />',
				isset( $this->options['rccwoo_confirm_text'] ) ? esc_attr( $this->options['rccwoo_confirm_text']) : '' ,
				esc_html__( 'Are you sure you wish to clear your shopping cart?','racar-clear-cart-for-woocommerce' )
			);
		}
		
		public function rccwoo_use_default_css_class_render() {
			global $allowed_html;
			$checked = ( isset( $this->options['rccwoo_use_default_css_class'] ) ) ? 1 : 0;
			$html = '<input type="checkbox" id="rccwoo_use_default_css_class" name="rccwoo_settings[rccwoo_use_default_css_class]" value="1"' . checked( 1, $checked, false ) . '/>';
			echo wp_kses( $html , $allowed_html );
		}
		
		public function rccwoo_button_css_classes_render() { 
			printf(
				'<input type="text" id="button-css-classes" name="rccwoo_settings[rccwoo_button_css_classes]" value="%s" />',
				isset( $this->options['rccwoo_button_css_classes'] ) ? esc_attr( $this->options['rccwoo_button_css_classes']) : ''
			);
		}
		
		public function rccwoo_radiobox_1_render() {
			$html = '';
			global $allowed_html;
			if( isset( $this->options["rccwoo_radiobox_1"] ) ){
				$html = __( 'Unset' , 'racar-clear-cart-for-woocommerce' ) . ' <input type="radio" class="rccwoo_radiobox_1" name="rccwoo_settings[rccwoo_radiobox_1]" value="unset"';
				if( 'unset' == $this->options['rccwoo_radiobox_1'] ) $html .= 'checked';
				$html .= '/>';
				$html .= __( 'Inherit' , 'racar-clear-cart-for-woocommerce' ) . ' <input type="radio" class="rccwoo_radiobox_1" name="rccwoo_settings[rccwoo_radiobox_1]" value="inherit"';
				if( 'inherit' == $this->options['rccwoo_radiobox_1'] ) $html .= 'checked';
				$html .= '/>';
				$html .= __( 'Left' , 'racar-clear-cart-for-woocommerce' ) . ' <input type="radio" class="rccwoo_radiobox_1" name="rccwoo_settings[rccwoo_radiobox_1]" value="left"';
				if( 'left' == $this->options['rccwoo_radiobox_1'] ) $html .= 'checked';
				$html .= '/>';
				$html .= __( 'Right' , 'racar-clear-cart-for-woocommerce' ) . ' <input type="radio" class="rccwoo_radiobox_1" name="rccwoo_settings[rccwoo_radiobox_1]" value="right"';
				if( 'right' == $this->options['rccwoo_radiobox_1'] ) $html .= 'checked';
				$html .= '/>';
			} else {
				$html = '<div><input type="radio" class="rccwoo_radiobox_1" id="float-unset" name="rccwoo_settings[rccwoo_radiobox_1]" value="unset"/>' . __( 'Unset' , 'racar-clear-cart-for-woocommerce' ) . '</div>';
				$html .= '<div><input type="radio" class="rccwoo_radiobox_1" name="rccwoo_settings[rccwoo_radiobox_1]" value="inherit"/>' . __( 'Inherit' , 'racar-clear-cart-for-woocommerce' ) . '</div>';
				$html .= '<div><input type="radio" class="rccwoo_radiobox_1" name="rccwoo_settings[rccwoo_radiobox_1]" value="left"/>' . __( 'Left' , 'racar-clear-cart-for-woocommerce' ) . '</div>';
				$html .= '<div><input type="radio" class="rccwoo_radiobox_1" name="rccwoo_settings[rccwoo_radiobox_1]" value="right"/>' . __( 'Right' , 'racar-clear-cart-for-woocommerce' ) . '</div>';
			}
			echo wp_kses( $html , $allowed_html );
		}
		
		public function rccwoo_background_render() {
			$val = '';
			global $allowed_html;
			if( isset( $this->options["rccwoo_background"] ) ){
				$val = $this->options['rccwoo_background'];
			}
			$html = '<input type="text" id="rccwoo_background" class="rccwoo-colorpicker" name="rccwoo_settings[rccwoo_background]" value="' . $val . '" />';
			echo wp_kses( $html , $allowed_html );
		}
		
		public function rccwoo_background_hover_color_render() {
			$val = '';
			global $allowed_html;
			if( isset( $this->options['rccwoo_background_hover_color'] ) ){
				$val = $this->options['rccwoo_background_hover_color'];
			}
			$html = '<input type="text" id="rccwoo_background_hover_color" class="rccwoo-colorpicker" name="rccwoo_settings[rccwoo_background_hover_color]" value="' . $val . '" />';
			echo wp_kses( $html , $allowed_html );
		}
		
		public function rccwoo_text_color_render() {
			$val = '';
			global $allowed_html;
			if( isset( $this->options["rccwoo_text_color"] ) ){
				$val = $this->options['rccwoo_text_color'];
			}
			$html = '<input type="text" id="rccwoo_text_color" class="rccwoo-colorpicker" name="rccwoo_settings[rccwoo_text_color]" value="' . $val . '" />';
			echo wp_kses( $html , $allowed_html );
		}
		
		public function rccwoo_hover_text_color_render() {
			$val = '';
			global $allowed_html;
			if( isset( $this->options["rccwoo_hover_text_color"] ) ){
				$val = $this->options['rccwoo_hover_text_color'];
			}
			$html = '<input type="text" id="rccwoo_hover_text_color" class="rccwoo-colorpicker" name="rccwoo_settings[rccwoo_hover_text_color]" value="' . $val . '" />';
			echo wp_kses( $html , $allowed_html );
		}

		public function rccwoo_redirection_url_render() { 
			printf(
				'<input type="text" id="button-css-classes" name="rccwoo_settings[rccwoo_redirection_url]" value="%s" placeholder="%s"/>',
				isset( $this->options['rccwoo_redirection_url'] ) ? esc_attr( $this->options['rccwoo_redirection_url']) : '',
				esc_html__( 'https://mysite.com/shop' , 'racar-clear-cart-for-woocommerce' )
			);
			echo '<div>'. esc_html__('Leave blank to keep customer in cart page after cart deletion' , 'racar-clear-cart-for-woocommerce' ) . '</div>';
		}

		public function rccwoo_rate_render() {
			global $allowed_html;
			$html = __( 'Do you like this plugin? Please show your love' , 'racar-clear-cart-for-woocommerce' ) . ' <a href="https://wordpress.org/plugins/racar-clear-cart-for-woocommerce/#reviews" target="_blank">' . __('here' , 'racar-clear-cart-for-woocommerce') . '</a>';
			echo wp_kses( $html , $allowed_html );
		}

		public function rccwoo_support_render() {
			global $allowed_html;
			$html = __( 'Do you need help with this plugin? Please open a ticket' , 'racar-clear-cart-for-woocommerce' ) . ' <a href="//wordpress.org/support/plugin/racar-clear-cart-for-woocommerce/" target="_blank">' . __('here' , 'racar-clear-cart-for-woocommerce') . '</a>';
			echo wp_kses( $html , $allowed_html );
		}

		public function rccwoo_donate_render() {
			global $allowed_html;
			$html = __( 'Do you want to show your love? Please buy me some coffee' , 'racar-clear-cart-for-woocommerce' ) . ' <a href="https://www.paypal.com/paypalme/RafaCarvalhido" target="_blank">' . __('by clicking here' , 'racar-clear-cart-for-woocommerce') . '</a>';
			echo wp_kses( $html , $allowed_html );
		}

	}
}

$my_settings_page = new rccwoo_Admin_Options();