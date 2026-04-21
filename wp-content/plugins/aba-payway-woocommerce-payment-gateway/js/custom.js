jQuery(function($) {

    load_aba_payment();
    jQuery( 'body' ).on( 'updated_checkout', function() {
        load_aba_payment();
    });

    jQuery("form#order_review").submit(function(e) {
        if (jQuery('input[type=radio][name=payment_method]:checked').val() == 'aba_payway_aim') {
            var selected_payment_option = jQuery('input[type=radio][name=payment_method]:checked').attr("real-value");
            jQuery("#aba_main_modal [name='payment_option']").val(selected_payment_option);
            AbaPayway.checkout();
            e.preventDefault();
        }
    });

    jQuery(document).on('click', 'input[type=submit][name=woocommerce_checkout_place_order],button[type=submit][name=woocommerce_checkout_place_order]', function(e) {
        jQuery('.woocommerce-error').remove();
        if (jQuery('input[type=radio][name=payment_method]:checked').val() == 'aba_payway_aim') {
            var first_name = jQuery('#billing_first_name').val();
            var last_name = jQuery('#billing_last_name').val();
            var phone = jQuery('#billing_phone').val();
            var email = jQuery('#billing_email').val();
            var aba_api_url = jQuery('#aba_api_url').val();

            if (aba_api_url == '') {
                jQuery('<div class="woocommerce-error">A required API URL is missing. Please contact the administrator to resolve this issue.</div>').insertBefore('#customer_details');
                jQuery('html, body').animate({
                    scrollTop: (jQuery('.woocommerce-error').offset().top - 100)
                }, 1000);
                return false;
            }

            //var transation_id = Math.floor(Math.random() * 1000000000);
            if (first_name != '' && last_name != '' && phone != '' && email != '' && jQuery('.woocommerce-error').length <= 0) {
                jQuery('input[name=firstname]').val(first_name);
                jQuery('input[name=lastname]').val(last_name);
                jQuery('input[name=phone]').val(phone);
                jQuery('input[name=email]').val(email);
            }
            //---------Close Popup----------------------------
            jQuery(document).on("click touchstart", ".aba-close", function() {
                closePopup();
            });

            var closePopup = function() {
                var confirmClose = true;
                confirmClose = confirm("Do you want to leave?");
                if (confirmClose == true) {
                    jQuery.post(frontend_ajax_object.ajaxurl, {
                        _ajax_nonce: frontend_ajax_object.nonce,
                        action: "aba_check_payment_cancel_order"
                    }, function(data) {
                        location.href = encodeURI(atob(jQuery("#cancel_url").val()));
                    });

                    if (jQuery('#aba_main_modal').hide()) {
                        jQuery('#aba_webservice').attr('src', "");

                        if (jQuery(window).width() < 500) {
                            jQuery('html, body').css({
                                "overflow": "auto",
                                "height": "auto"
                            });
                        }
                    }
                }
            }
        } else {
            jQuery('button[type=submit][name=woocommerce_checkout_place_order]').attr('onclick', '');
        }
    });
});

function load_aba_payment(){
    if( jQuery("#aba_is_enable").val() != "no" ){
        var aba_enable_payment = jQuery("#aba_enable_payment").val();
        if( aba_enable_payment != '' && typeof aba_enable_payment != 'undefined'){
            var aba_enable_payments = aba_enable_payment.split(",");

            if(!window.hasOwnProperty("miniApp")){
                if( jQuery.inArray( "alipay", aba_enable_payments ) != -1 ){
                    var logo_id = "#aba_logo_alipay";
                    var html_payment = prepare_payment_elem("alipay", "Alipay", "Alipay", '<img class="payway_payment_logo" src="'+jQuery(logo_id).val()+'" alt="Alipay">', '<img height="20" src="'+jQuery(logo_id).val()+'" alt="Alipay"');
                    jQuery(".wc_payment_methods.payment_methods.methods li.payment_method_aba_payway_aim:not(.new_element)").after(html_payment);
                }

                if( jQuery.inArray( "wechat", aba_enable_payments ) != -1 ){
                    var logo_id = "#aba_logo_wechat";
                    var html_payment = prepare_payment_elem("wechat", "WeChat", "WeChat", '<img class="payway_payment_logo" src="'+jQuery(logo_id).val()+'" alt="WeChat">', '<img height="20" src="'+jQuery(logo_id).val()+'" alt="WeChat"');
                    jQuery(".wc_payment_methods.payment_methods.methods li.payment_method_aba_payway_aim:not(.new_element)").after(html_payment);
                }

                if( jQuery.inArray( "credit_debit", aba_enable_payments )!= -1 ){
                    var logo_id = "#aba_logo_credit_debit";
                    var html_payment = prepare_payment_elem("cards", "Credit/Debit Card", "Pay with Credit/Debit Card", '<img class="payway_payment_logo" src="'+jQuery(logo_id).val()+'" alt="Credit/Debit Card">');
                    jQuery(".wc_payment_methods.payment_methods.methods li.payment_method_aba_payway_aim:not(.new_element)").after(html_payment);
                }
            }

            if( jQuery.inArray( "abapay_khqr", aba_enable_payments )!= -1 || jQuery.inArray( "abapay", aba_enable_payments )!= -1 ) {
                var html_payment = prepare_payment_elem("abapay_khqr", "ABA KHQR", "Scan to pay with member bank app", '<img class="payway_payment_logo" src="' + jQuery("#aba_logo_aba").val() + '" alt="ABA KHQR">', '<img height="20" src="' + jQuery("#aba_logo_aba").val() + '" alt="ABA KHQR"');
                jQuery(".wc_payment_methods.payment_methods.methods li.payment_method_aba_payway_aim:not(.new_element)").after(html_payment);
            }

            jQuery("li.payment_method_aba_payway_aim:not(.new_element)").remove();
            jQuery("li.payment_method_aba_payway_aim").css("display", "block");

            var img_height = jQuery("#payment .wc_payment_methods .payment_method_payway .aba-left img").height();
            var input_height = jQuery("#payment .payment_methods>.payment_method_payway>input").outerHeight();
            img_height = img_height - input_height;
            var margin_top = img_height/2;
            jQuery("#payment .payment_methods>.payment_method_payway>input").attr("style", "margin-top: "+margin_top+"px !important;");

            var left = jQuery("#payment .wc_payment_methods .payment_method_payway .aba-left").outerWidth() + 40;
            jQuery("#payment .wc_payment_methods .payment_method_payway .aba-right").css("width", "calc(100% - "+left+"px)");

        }
    }
}

function prepare_payment_elem(payment_option, payment_label, description, html_desc ){
    var aba_choosen_method = jQuery("#aba_choosen_method").val();
    var aba_bg_color = jQuery("#aba_bg_color").val();
    var html_payment = '<li id="list_payment_method_'+payment_option+'" class="clearfix wc_payment_method new_element payment_method_payway payment_method_'+payment_option+' '+aba_bg_color+'">';
    html_payment += '    <input '+(aba_choosen_method==payment_option?'checked':'')+' id="payment_method_'+payment_option+'" type="radio" class="input-radio" name="payment_method" real-value="'+payment_option+'" value="aba_payway_aim" data-order_button_text="">';
    html_payment += '    <label for="payment_method_'+payment_option+'">';
    html_payment += '    '+payment_label+' '+html_desc+'</label>';
    html_payment += '</li>';
    return html_payment;
}
