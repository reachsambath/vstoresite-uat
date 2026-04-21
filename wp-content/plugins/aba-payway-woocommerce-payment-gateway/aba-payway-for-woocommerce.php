<?php
define ('PW_PLUGIN_VERSION', '2.1.8');
/*
Plugin Name: ABA PayWay Payment Gateway for WooCommerce
Plugin URI: https://wordpress.org/plugins/aba-payway-woocommerce-payment-gateway/#description
Description: PayWay is Cambodia’s leading online payment gateway provided by Advanced Bank of Asia Ltd. (ABA Bank).
Author: ABA Bank
Author URI: https://www.ababank.com/
Version: 2.1.8
*/

if ( ! defined( 'ABSPATH' ) )
	die( "Can't load this file directly" );

if ( in_array(
    'woocommerce/woocommerce.php',
    apply_filters( 'active_plugins', get_option( 'active_plugins' ) )
  )
);
define('ABA_PAYMENT_PLUGIN_PATH', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );

add_action( 'plugins_loaded', 'aba_payway_aim_init', 0 );
function aba_payway_aim_init() {
    //if condition use to do nothin while WooCommerce is not installed
	if ( ! class_exists( 'WC_Payment_Gateway' ) ) return;
	include_once( 'PayWayApiCheckout.php' );

	// class add it too WooCommerce
	add_filter( 'woocommerce_payment_gateways', 'add_aba_payway_gateway' );
	function add_aba_payway_gateway( $methods ) {
		$methods[] = 'aba_payway_aim';
		return $methods;
	}
}

// Add custom action links
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'aba_payway_aim_action_links' );
function aba_payway_aim_action_links( $links ) {
	$plugin_links = array(
		'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout' ) . '">' . __( 'Settings', 'aba-payway-aim' ) . '</a>',
	);
	return array_merge( $plugin_links, $links );
}

function aba_render_checkout_frm($woocommerce, $transactionId, $amount, $items, $shipping_fee){
    $first_name = '';
    $last_name = '';
    $email = '';
    $phone = '';
    if(!empty($woocommerce->customer->billing)){
        $first_name = $woocommerce->customer->billing['first_name'] ?? "";
        $last_name = $woocommerce->customer->billing['last_name'] ?? "";
        $email = $woocommerce->customer->billing['email'] ?? "";
        $phone = $woocommerce->customer->billing['phone'] ?? "";
    }

    $cart_url = get_permalink( wc_get_page_id( 'cart' ) );

    echo '<div id="aba_main_modal" class="aba-modal">';
    echo '<div class="aba-modal-content">';
    echo '<form method="POST" target="aba_webservice" action="'.aba_PAYWAY_AIM::getEndpointApiUrl().'" id="aba_merchant_request" target="aba_webservice" name="aba_merchant_request">';
    echo '<input type="hidden" name="req_time" value="" id="req_time">';
    echo '<input type="hidden" name="merchant_id" value="'.aba_PAYWAY_AIM::getMerchantId().'" id="merchant_id">';
    echo '<input type="hidden" name="payment_gate" value="0">';
    echo '<input type="hidden" name="api_version" id="api_version" value="v1">';
    echo '<input type="hidden" name="hash" id="hash" value="'.aba_PAYWAY_AIM::getHash(aba_PAYWAY_AIM::getMerchantId() . $transactionId . $amount . $items . $shipping_fee).'"/>';
    echo '<input type="hidden" name="tran_id" id="tran_id" value="'.$transactionId.'"/>';
    echo '<input type="hidden" name="amount" value="'.$amount.'" />';
    echo '<input type="hidden" name="firstname" value="'.$first_name.'" />';
    echo '<input type="hidden" name="lastname" value="'.$last_name.'" />';
    echo '<input type="hidden" name="phone" value="'.$phone.'"  />';
    echo '<input type="hidden" name="email" value="'.$email.'" />';
    echo '<input type="hidden" name="currency" value="'.aba_PAYWAY_AIM::getCurrency().'" />';
    echo '<input type="hidden" name="shipping" value="'.(!empty($shipping_fee)?$shipping_fee:'').'" />';
    echo '<input type="hidden" name="cancel_url" value="'.base64_encode($cart_url).'" id="cancel_url">';
    echo '<input type="hidden" name="return_url" value="'.base64_encode(aba_PAYWAY_AIM::getReturnURL($transactionId)).'" id="return_url">';
    echo '<input type="hidden" name="items" value="'.$items.'" id="items"/>';
    echo '<input type="hidden" name="continue_success_url" id="continue_success_url" value="'.aba_PAYWAY_AIM::getContinueSuccessUrl().'">';
    echo '<input type="hidden" name="payment_option" value="cards"/>';
    echo '</form>';
    echo '</div>';
    echo '</div>';

    echo '<input type="hidden" name="aba_api_url" id="aba_api_url" value="'.aba_PAYWAY_AIM::getApiUrl().'">';
    echo '<input type="hidden" id="aba_enable_payment" value="'.aba_PAYWAY_AIM::getPaymentOption().'">';
    echo '<input type="hidden" id="aba_is_enable" value="'.aba_PAYWAY_AIM::isEnabled().'">';

    echo '<input type="hidden" id="aba_logo_aba" value="'.aba_PAYWAY_AIM::getImageUrl('khqr').'">';
    echo '<input type="hidden" id="aba_logo_credit_debit" value="'.aba_PAYWAY_AIM::getImageUrl('cards').'">';
    echo '<input type="hidden" id="aba_logo_bakong" value="'.aba_PAYWAY_AIM::getImageUrl('khqr').'">';
    echo '<input type="hidden" id="aba_logo_wechat" value="'.aba_PAYWAY_AIM::getImageUrl('wechat').'">';
    echo '<input type="hidden" id="aba_logo_alipay" value="'.aba_PAYWAY_AIM::getImageUrl('alipay').'">';
    echo '<input type="hidden" id="aba_bg_color" value="'.aba_PAYWAY_AIM::getBackgroundColor().'">';

    echo '<input type="hidden" id="aba_choosen_method" value="'.(isset($_SESSION['aba_choosen_method'])?$_SESSION['aba_choosen_method']:'').'">';
}

//Add Payway form in checkout page
add_action('woocommerce_after_checkout_form','aba_woocommerce_after_after_woocommerce_pay',10,1);

add_action('after_woocommerce_pay','aba_woocommerce_after_after_woocommerce_pay',10,1);

function aba_woocommerce_after_after_woocommerce_pay(){
    global $woocommerce;

    $order_id = get_query_var('order-pay') ?: $woocommerce->session->order_awaiting_payment;
    $_SESSION['aba_transaction_id'] = $order_id;

    $order = new WC_Order($order_id);
    $items_result = $order->get_items();
    $shipping_fee = $order->get_shipping_total();
    $discount = $order->get_discount_total();
    $amount = sprintf("%.2f", $order->get_total() - $shipping_fee);
    $shipping_fee = sprintf("%.2f", $shipping_fee);

    $key = 0;
    $total_no_discount = $order->get_total() + $discount;
    $items = [];
    foreach ($items_result as $order_item_id => $order_item) {
        $product = $order_item->get_product();
        if(!empty($product)){
            $items[$key]['name'] = $order_item->get_name();
            $items[$key]['quantity'] = $order_item->get_quantity();
            $items[$key]['price'] = sprintf("%.2f", $product->get_price());
            if( $discount > 0 ){
                $items[$key]['discount'] =  (($product->get_price() * $order_item->get_quantity() * 100)/$total_no_discount) * $discount/100;
            }
            $key++;
        }
    }
    $item = base64_encode(json_encode($items));
    aba_render_checkout_frm($woocommerce, $order_id, $amount, $item, $shipping_fee);
}

add_action( 'wp_enqueue_scripts', 'aba_payway_scripts_basic' );
function aba_payway_scripts_basic() {
    $loadScript = false;
    if(strlen(strstr($_SERVER['HTTP_USER_AGENT'] ?? '','ABAMobile')) > 0){
        $loadScript = true;
    }else if(is_page(array('checkout')) || (function_exists( 'is_checkout' ) && is_checkout())){
        $loadScript = true;
    }

    if($loadScript){
        $pay_js = aba_PAYWAY_AIM::getJssend();
        $hide_close = aba_PAYWAY_AIM::getHideclose();
        wp_deregister_script('wc-checkout');
        wp_enqueue_script('wc-checkout', ABA_PAYMENT_PLUGIN_PATH . 'js/checkout.js', array('jquery', 'woocommerce', 'wc-country-select', 'wc-address-i18n'), time(), true);
        wp_enqueue_script( 'aba-payway-custom-script', ABA_PAYMENT_PLUGIN_PATH. 'js/custom.js', array( 'jquery' ), time(), true );
        $query_url = parse_url($pay_js, PHP_URL_QUERY);
        if ($query_url) {
            $pay_js .= '&'.$hide_close;
        } else {
            $pay_js .= '?'.$hide_close;
        }
        wp_enqueue_script( 'checkout-popup', $pay_js, false );

        wp_localize_script( 'aba-payway-custom-script', 'frontend_ajax_object',
            array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
            )
        );
        wp_enqueue_style('aba-payway-checkout-style-icon', plugins_url('css/payway.css?v=1.1',__FILE__ ));
    }
}

add_action( 'wp_footer', 'aba_payway_dynamic_js' );
function aba_payway_dynamic_js() {
    if(strlen(strstr($_SERVER['HTTP_USER_AGENT'] ?? '','ABAMobile')) > 0){
        ?><script>window.miniApp = true;</script><?php
        $dynamicScript = aba_PAYWAY_AIM::getDynamicScript();
        if(!empty($dynamicScript)){
            ?>
            <script>
                <?php echo $dynamicScript; ?>
            </script>
            <?php
        }
    }
}

add_action( 'wp_head', 'aba_payway_dynamic_css' );
function aba_payway_dynamic_css() {
    $dynamicCss = aba_PAYWAY_AIM::getDynamicCSS();
    if(!empty($dynamicCss)){
        ?>
        <style>
            <?php echo $dynamicCss; ?>
        </style>
        <?php
    }
}

add_action('init', 'aba_payway_start_session', 1);
function aba_payway_start_session() {
    if( !session_id() ) {
        session_start();
    }
}

add_action("wp_ajax_aba_check_payment_cancel_order", "aba_check_payment_cancel_order");
add_action("wp_ajax_nopriv_aba_check_payment_cancel_order", "aba_check_payment_cancel_order");
function aba_check_payment_cancel_order(){
    if( !empty($_SESSION['aba_order_id']) ) {
        $order = new WC_Order( $_SESSION['aba_order_id'] );
        if ( $order->status == 'pending') {
          $order->update_status('cancelled');
        }
    }
    exit;
}

// add a wp query variable to redirect to
add_action('query_vars','set_aba_query_var');
function set_aba_query_var($vars) {
	array_push($vars, 'aba_payway_pushback'); // ref url redirected to in add rewrite rule
	return $vars;
}

// Create a redirect
add_action('init', 'custom_add_rewrite_rule');
function custom_add_rewrite_rule(){
	add_rewrite_rule('^abapayway/pushback$','index.php?aba_payway_pushback=1','top');
	//flush the rewrite rules, should be in a plugin activation hook, i.e only run once...
	flush_rewrite_rules();
}

// return the file we want...
add_filter('template_include', 'plugin_include_template');
function plugin_include_template($template) {
    if (get_query_var('aba_payway_pushback')) {
        global $woocommerce;
        $tran_id = '';
        $response_data = [
            "pw_version" => esc_html(!empty(PW_PLUGIN_VERSION) ? PW_PLUGIN_VERSION : '')
        ];

        if (isset($_REQUEST['response'])) {
            $response_raw = stripslashes($_REQUEST['response']);
            $response = json_decode($response_raw, TRUE);

            // Sanitize the decoded response
            if (is_array($response)) {
                $response = array_map('sanitize_text_field', $response);
            }

            $response_data["request"] = $response;

            if (isset($response['status']) && ($response['status'] == 0 || $response['status'] === "0")) {
                $tran_id = isset($response['tran_id']) ? sanitize_text_field($response['tran_id']) : '';
            }
        } elseif (isset($_REQUEST['payway_token'])) {
            $payway_token_raw = sanitize_text_field($_REQUEST['payway_token']);
            $response = json_decode(aba_PAYWAY_AIM::pwDecrypt($payway_token_raw, aba_PAYWAY_AIM::getApiKey()), TRUE);

            // Sanitize the decoded response
            if (is_array($response)) {
                $response = array_map('sanitize_text_field', $response);
            }

            $response_data["payway_token"] = $response;

            if (!empty($response["tran_id"])) {
                if (!empty($response["time"]) && $response["time"] > time() - 1800) {
                    $tran_id = sanitize_text_field($response['tran_id']);
                } else {
                    $response_data["error"] = esc_html("token expired");
                }
            } else {
                $response_data["error"] = esc_html("invalid payway_token");
            }
        }

        if (!empty($tran_id)) {
            if (aba_PAYWAY_AIM::checkTransaction($tran_id)) {
                $response_data["check_transaction"] = esc_html("success");
                $order = new WC_Order($tran_id);
                $order->update_status('completed');
                $woocommerce->cart->empty_cart();
            } else {
                $response_data["check_transaction"] = esc_html("failed");
            }
        }

        // Escape the output before returning it
        echo wp_json_encode($response_data);
        exit;
    }
    return $template;
}
