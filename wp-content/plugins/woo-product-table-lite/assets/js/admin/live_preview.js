(function($) {
    "use strict";

    jQuery(document).ready(function ($) {

        // global variable
        var data_old = '';

        // check template
        if ($('.dbody.template')[0]) {

            set_data();
            setInterval(function(){
                set_data();
            },0);

        }

        // DISABLE LINL
        $(document).on('click', '.live-preview a', function (e) {
            e.preventDefault();
        });

        // SCROLL BAR
        $(".live-preview .table").mCustomScrollbar({
            axis: "x", // horizontal scrollbar
            setLeft: 0,
            setTop: 0,
            theme: "minimal-dark",
            mouseWheel: false,
        });

        // SEARCH BOX`S OPTIONS
        $(document).on('click','.live-preview .search .setting',function(){

            var _this = $(this),
                _parent = _this.parent(),
                _options = _parent.parents('.controls').find('.search-options');

            if(_parent.hasClass('active')){
                _parent.removeClass('active');
                _options.hide();
            }else{
                _parent.addClass('active');
                _options.show();
            }

        });

        // VARIATION POPUP
        $(document).on('click','.live-preview .variation-btn',function(){

            var _this = $(this),
                _parent = _this.parent(),
                _options = _parent.find('.variation-popup');

            if(_parent.hasClass('active-variation')){
                _parent.removeClass('active-variation');
                _options.hide();
            }else{
                _parent.addClass('active-variation');
                _options.show();
            }

        });

        // CART FIXED CONTROL
        $(document).on('click', '.cart-button-fixed', function () {

            // VARIABLE
            var _this = $(this);
            var _popup = _this.parent().find('.cart-popup');

            // CONTROL
            if (_this.hasClass('active')) {
                _this.removeClass('active');
                _popup.hide();
            } else {
                _this.addClass('active');
                _popup.css({'display':'flex'});
            }

        });

        /**
         * set style on preview elements
         */
        function set_data() {

            // variables
            var data = get_data();
            if(JSON.stringify(data) !== JSON.stringify(data_old)) {
                var styleElement = $('.style-live-preview');
                var style = '';

                style += `
            .live-preview .title,
            .live-preview .cart-header div,
            .live-preview .subtotal,
            .live-preview .icon-itx{color:${data.cart_title_color} !important;}
            .live-preview .quantity,
            .live-preview .qty,
            .live-preview .subtotal span{color:${data.cart_meta_color} !important;}
            .live-preview .cart,
            .live-preview .cart-popup{background-color:${data.cart_background_color} !important;}
            .live-preview .close svg path:nth-child(1),
            .live-preview .arrow svg path{fill:${data.cart_background_color} !important;}
            .live-preview .cart .title,
            .live-preview .cart .subtotal,
            .live-preview .cart .cart-header div{color:${data.cart_title_color} !important;}
            .live-preview .cart .quantity,
            .live-preview .cart .qty,
            .live-preview .cart .subtotal span{color:${data.cart_meta_color} !important;}
            .live-preview .cart .btn,
            .live-preview .cart-popup .buttons .button{background-color:${data.cart_button_background_color} !important;}
            .live-preview .cart .btn,
            .live-preview .cart .btn a,
            .live-preview .cart-popup .buttons .button{color:${data.cart_button_text_color} !important;}
            .live-preview .cart-button-fixed,
            .live-preview .cart-button-fixed:before{background-color:${data.cart_button_fixed_background_color} !important;}
            .live-preview .cart-button-fixed,
            .live-preview .cart-button-fixed *,
            .live-preview .cart-button-fixed .subtotal span{color:${data.cart_button_fixed_text_color} !important;}
            .live-preview .alarm.alarm-success,
            .live-preview .alarm.alarm-success *{background-color:${data.alarm_success_background_color} !important;color:${data.alarm_success_text_color} !important;}
            .live-preview .alarm.alarm-error,
            .live-preview .alarm.alarm-error *{background-color:${data.alarm_error_background_color} !important;color:${data.alarm_error_text_color} !important;}
            .live-preview .controls .search,
            .live-preview .controls .search-option,
            .live-preview .controls .search-option select,
            .live-preview .controls .select-all,
            .live-preview .controls .search-btn,
            .live-preview .controls .search-reset-btn,
            .live-preview .table .dataTables_length select{background-color:${data.crl_box_background_boxs} !important;color:${data.crl_box_text_color_boxs} !important;}
            .live-preview .controls .button{background-color:${data.crl_box_text_background_btn} !important;color:${data.crl_box_text_text_color_btn} !important;transition:all 0.15s ease-in-out;}
            .live-preview .controls .button:hover{background-color:${data.crl_box_text_background_btn_hover} !important;color:${data.crl_box_text_text_color_btn_hover} !important;}
            .live-preview thead th,
            .live-preview tfoot th{background-color:${data.header_footer_background_color} !important;color:${data.header_footer_text_color} !important;border-bottom:0${data.header_footer_border_width}px solid ${data.header_footer_border_color} !important;padding-top:0${data.header_footer_padding}px !important;padding-bottom:0${data.header_footer_padding}px !important;text-align:${data.header_footer_text_alignment} !important;text-transform:${data.header_footer_text_transform} !important;font-size:0${data.header_footer_font_size}px !important;}
            .live-preview td{background-color:${data.body_background_color} !important;border-bottom:0${data.body_td_border_width}px solid ${data.body_border_color} !important;color:${data.body_text_color} !important;padding-top:0${data.body_td_padding}px !important;padding-bottom:0${data.body_td_padding}px !important;transition:all 0.15s ease-in-out;}
            .live-preview tr:hover td{background-color:${data.body_hover_background_color} !important;}
            .live-preview td a{color:${data.body_link_color} !important;}
            .live-preview td a:hover{color:${data.body_hover_link_color} !important;}
            .live-preview tr:nth-child(2n+0) td{background-color:${data.body_strip_background_color} !important;color:${data.body_strip_text_color} !important;}
            .live-preview tr:nth-child(2n+0):hover td{background-color:${data.body_strip_background_hover_color} !important;}
            .live-preview tr th.sorted,
            .live-preview tr td.sorted{ background-color:${data.body_sorted_column_bg_color} !important;}
            .live-preview .checkbox-selector{background-color:${data.checkbox_color} !important;box-shadow: 0 0 0 1px ${data.checkbox_border_color} !important;}
            .live-preview .checkbox-selector i{background-color:${data.checkbox_sign_color} !important;color: 0 0 0 1px ${data.checkbox_color} !important;}
            .live-preview .add-to-cart.button,
            .live-preview .add-to-cart-link.button,
            .live-preview .view-cart i,
            .live-preview .view-cart i{background-color:${data.button_add_to_cart_background_color} !important; color:${data.button_add_to_cart_text_color} !important;}
            .live-preview .add-to-cart.button:hover,
            .live-preview .add-to-cart-link.button:hover,
            .live-preview .view-cart i:hover,
            .live-preview .view-cart i:hover{ background-color:${data.button_add_to_cart_hover_background_color} !important;color:${data.button_add_to_cart_hover_text_color} !important;}
            .live-preview .variation-btn{background-color:${data.variation_button_background_color} !important;color:${data.variation_button_text_color} !important;}
            .live-preview .variation-btn:hover{background-color:${data.variation_button_hover_background_color} !important; color:${data.variation_button_hover_text_color} !important;}
            .live-preview a.button{background-color:${data.other_background_color} !important;color:${data.other_text_color} !important;}
            .live-preview a.button:hover{background-color:${data.other_hover_background_color} !important;color:${data.other_hover_text_color} !important;}
            .live-preview .variation-popup{background-color:${data.variation_popup_background_color} !important;color:${data.variation_popup_text_color} !important;}
            .live-preview .variation-popup .arrow svg path{fill:${data.variation_popup_background_color} !important;}
            .live-preview .variation-popup select{background-color:transparent;box-shadow:0 0 0 1px ${data.variation_popup_select_border_color} !important;color:${data.variation_popup_select_text_color} !important;}
            .live-preview .variation-popup .button{background-color:${data.variation_popup_button_background_color} !important;color:${data.variation_popup_button_text_color} !important;}
            .live-preview .variation-popup .button:hover{background-color:${data.variation_popup_button_background_color_hover} !important;color:${data.variation_popup_button_text_color_hover} !important;}
            .live-preview .search-options .search-option{width:calc(calc(100% / ${data.ads_column_size_fields}) - 5px) !important;margin-right:5px !important;}
            .live-preview .search-options .search-option:nth-child(${data.ads_column_size_fields}n+0){margin-left:5px !important;margin-right:0 !important;}
            .live-preview .quantity input,
            .live-preview .quantity svg path{background-color:${data.other_qty_background_color} !important;fill:${data.other_qty_background_color} !important;color:${data.other_qty_text_color} !important;}
            .live-preview .msg-in-stock{color:${data.other_in_stock_color} !important;}
            .live-preview .msg-out-of-stock{color:${data.other_out_stock_color} !important;}
            .live-preview .thumbnails_tbl_column .img{border-radius:${(data.other_thum_shape === 'square' ? '0px' : (data.other_thum_shape === 'q' ? '5px' : '999px'))} !important;width:${data.other_thumbs_image_size}px !important;height:${data.other_thumbs_image_size}px !important;}
            .live-preview .pagination .pagination-content{background-color:${data.tmp_pagination_background_color} !important;color:${data.tmp_pagination_text_color} !important;}
            .live-preview .pagination a{color:${data.tmp_pagination_text_color} !important;}
            .live-preview .pagination span.current{color:${data.tmp_pagination_active_text_color} !important;}
            .live-preview .pagination span.current:before{background-color:${data.tmp_pagination_active_background_color} !important;}
            .live-preview .load-more .load-more-content{background-color:${data.tmp_pagination_background_color} !important;color:${data.tmp_pagination_text_color} !important;}
            .live-preview .load-more .load-more-content:before,
            .live-preview .load-more .load-more-content:after{background-color:${data.tmp_pagination_second_color} !important;}
            .live-preview .live-preview-content{filter:drop-shadow(0 0 23px ${data.other_out_shadow_color}) !important;}
            .live-preview  span.current:before, .live-preview .itwpt-pagination-content a.current:before{box-shadow:0 0 18px ${data.other_out_shadow_color};}
            .live-preview .variation-popup{box-shadow:0 0 23px ${data.other_out_shadow_color};}            
            .live-preview .msg-back-order{color:${data.other_back_order_color} !important;}`;

                // CONTROL ICON ADD TO CART
                if(data.button_add_to_cart_icon === 'no-icon') {
                    style += `
                .live-preview .add-to-cart .left-icon,
                .live-preview .add-to-cart .right-icon,
                .live-preview .add-to-cart-link .left-icon,
                .live-preview .add-to-cart-link .right-icon{
                    display:none;
                }`;
                }else if(data.button_add_to_cart_icon === 'only-icon'){
                    style += `
                .live-preview .add-to-cart .right-icon,
                .live-preview .add-to-cart-link .right-icon,
                .live-preview .add-to-cart span,
                .live-preview .add-to-cart-link span{
                    display:none;
                }
                .live-preview .add-to-cart .left-icon,
                .live-preview .add-to-cart-link .left-icon{
                    display:inline-block;
                }`;
                }else if(data.button_add_to_cart_icon === 'left-icon'){
                    style += `
                .live-preview .add-to-cart .right-icon,
                .live-preview .add-to-cart-link .right-icon{
                    display:none;
                }
                .live-preview .add-to-cart .left-icon,
                .live-preview .add-to-cart-link .left-icon{
                    display:inline-block;
                }`;
                }else if(data.button_add_to_cart_icon === 'right-icon'){
                    style += `
                .live-preview .add-to-cart .left-icon,
                .live-preview .add-to-cart-link .left-icon{
                    display:none;
                }
                .live-preview .add-to-cart .right-icon,
                .live-preview .add-to-cart-link .right-icon{
                    display:inline-block;
                }`;
                }

                styleElement.html('<style>' + style + '</style>');
                data_old = data;
            }

        }

        /**
         * get field data
         * @returns array json
         */
        function get_data() {

            // variables
            var fields = $('input.save,select.save');
            var data = {};

            $.each(fields, function () {
                data[$(this).attr('name')] = $(this).val();
            });

            return data;

        }
    });

})(jQuery);