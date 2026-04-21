<?php 
if ( ! defined( 'ABSPATH' ) ) exit;

if( ! class_exists( 'RACAR_Clear_Cart_Button' ) ) {	
	class RACAR_Clear_Cart_Button {
		private $options;
		
		public function __construct() {
			$this->options = get_option( 'rccwoo_settings' );

			/*if( ! isset( $this->options["rccwoo_enabled"] ) || $this->options['rccwoo_enabled'] != 1 ) {
				return;
			}*/
			$button_text = __('Clear Cart','racar-clear-cart-for-woocommerce');
			$confirm_text = __('Are you sure you wish to clear your shopping cart?','racar-clear-cart-for-woocommerce');
			$float_option = '';
			$button_bg_color = '';
			$button_bg_hover_color = '';
			$button_text_color = '';
			$button_hover_text_color = '';
			$css_classes = '';
			$fall_out_style_option = 'visibility: visible;'; 
//trace( '$css_classes 0' );
//trace( $css_classes );			
			if( isset( $this->options["rccwoo_radiobox_1"] ) ) {
				$float_option = 'float:' . $this->options["rccwoo_radiobox_1"] . ';';
			}
			if( isset( $this->options["rccwoo_button_text"] ) && $this->options["rccwoo_button_text"] != '' ){
				$button_text = $this->options["rccwoo_button_text"];
			}
			if( isset( $this->options["rccwoo_confirm_text"] ) && $this->options["rccwoo_confirm_text"] != '' ){
				$confirm_text = $this->options["rccwoo_confirm_text"];
			}
			if( isset( $this->options["rccwoo_background"] ) && $this->options["rccwoo_background"] != '' ){
				$button_bg_color = 'background-color:' . $this->options["rccwoo_background"] . ';';
			}
			if( isset( $this->options["rccwoo_background_hover_color"] ) && $this->options["rccwoo_background_hover_color"] != '' ){
				$button_bg_hover_color = 'background-color:' . $this->options["rccwoo_background_hover_color"] . ' !important;';
			}
			if( isset( $this->options["rccwoo_text_color"] ) && $this->options["rccwoo_text_color"] != '' ){
				$button_text_color = 'color:' . $this->options["rccwoo_text_color"] . ';';
			}
			
			if( isset( $this->options["rccwoo_hover_text_color"] ) && $this->options["rccwoo_hover_text_color"] != '' ){
				$button_hover_text_color = 'color:' . $this->options["rccwoo_hover_text_color"] . ' !important;';
			}
			
			if( ! isset( $this->options["rccwoo_use_default_css_class"] ) ){
				if( isset( $this->options["rccwoo_button_css_classes"] ) AND $this->options["rccwoo_button_css_classes"] != '' ) {
						$css_classes .= 'button ';
				} else {
					$css_classes .= 'button';
				}
			}
			if( isset( $this->options["rccwoo_button_css_classes"] ) && $this->options["rccwoo_button_css_classes"] != '' ){
				$css_classes .= $this->options["rccwoo_button_css_classes"];
			}
			
			?>
				<style>
					button[name="clear-cart"]:hover{<?php echo esc_html( $button_bg_hover_color ); echo esc_html( $button_hover_text_color );?>}
				</style>

				<?php echo wp_kses( wp_nonce_field( 'racar_clear_cart_nonce', '_wpnonce_racar_clear_cart' ), $allowed_html ); ?>
			
				<button type="submit" style="<?php echo esc_html( $float_option ); echo esc_html( $button_bg_color ); echo esc_html( $button_text_color ); echo esc_html( $fall_out_style_option );?>" onclick='return confirm("<?php echo esc_html( $confirm_text );?>");' <?php if( ! empty( $css_classes ) ) echo 'class="' . esc_html( $css_classes ) . '"';?> id="clear-cart" name="clear-cart" value="<?php echo esc_html( $button_text );?>"><?php echo esc_html( $button_text );?></button>
			<?php 

		}
	}
}

function rccwoo_add_button_to_cart_page() {
	$button = new RACAR_Clear_Cart_Button();
}
rccwoo_add_button_to_cart_page();