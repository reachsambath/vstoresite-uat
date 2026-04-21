(function ($) {
    "use strict";

    jQuery(document).ready(function ($) {
        /**
         * GLOBAL VARIABLES
         */
        var numberCheck = false;
        var addProduct = false;
        var updateCart = false;
        var sortElement;

        /**
         * REMOVE LOADING
         */
        setTimeout(function () {

            // VARIABLE
            var table_base = $('.itwpt-table-base');

            $('.itwpt-loading-box').remove();
            table_base.css({ 'display': 'block' });

            $(window).trigger('resize');

            // RESET SCROLL
            $.each(table_base, function () {

                $(this).find('.itwpt-table-scroll').mCustomScrollbar({
                    axis: "x", // horizontal scrollbar
                    setLeft: 0,
                    setTop: 0,
                    scrollInertia: 10,
                    theme: "minimal-dark",
                    mouseWheel: false,
                    advanced: { autoScrollOnFocus: "string" },
                    callbacks: {
                        whileScrolling: function () {

                            if ($(".variation-popup")[0]) {
                                $(".itwpt-variation-btn");
                                var _id = $(".variation-popup").data("id");
                                var _this = $("." + _id + " .itwpt-variation-btn");
                                var _table = _this.parents('.itwpt-table');
                                _table.find('.variation-popup').css({
                                    'left': (_this.offset().left - _table.offset().left + ((40 + _this.width()) / 2))
                                });
                            }

                        }
                    }
                });
            });

        }, 500);

        /**
         * CONTROL SCROLL
         */
        $(window).scroll(function () {

            // EACH ON ALL BASE ELEMENTS
            $.each($('.itwpt-table-base .itwpt-table-scroll'), function () {

                var _this = $(this),
                    _scroll = $(window).scrollTop(),
                    _top = _this.offset().top - _scroll,
                    _bottom = $(window).height() - _top,
                    _controler = _this.height() - _bottom;

                if (_bottom > 79 && _controler >= 0) {
                    _this.find('.mCSB_scrollTools_horizontal').attr('style', 'position:absolute; bottom:' + _controler + 'px;');
                } else {
                    _this.find('.mCSB_scrollTools_horizontal').attr('style', 'position:absolute; bottom:0px;');
                }

            });

        });

        /**
         * CONTROL RESIZE
         */
        $(window).resize(function () {
            $.each($('.itwpt-table-base table'), function () {

                var wsize = $(this).parents('.itwpt-table').width();
                $(this).attr('style', 'width:' + wsize + 'px !important;');
                $(this).parents('.mCSB_container').css({ 'width': ($(this).width() > wsize ? $(this).width() : wsize) + 'px' });

            });
        });

        // MULTI SELECT
        if ($('.itwpt-search-option.multiselect')[0]) {
            $('.itwpt-search-option.multiselect select').select2({
                placeholder: $(this).data("placeholder"),
            });
        }

        // STICKY SCRIPT
        Itwpt_Control_Sticky();

        $(document).on('click', '.itwpt-checkbox-selector', function (e) {

            var _this = $(this);
            var _table = $(this).parents('table');

            if (_this.hasClass('active')) {
                _this.removeClass('active');
                $(_this.parents('.itwpt-table-base').find('table.clone tbody tr')[_this.parents('tr').index()]).find('.itwpt-checkbox-selector').removeClass('active');
                $(_this.parents('.itwpt-table-base').find('table:not(.clone) tbody tr')[_this.parents('tr').index()]).find('.itwpt-checkbox-selector').removeClass('active');
            } else {
                _this.addClass('active');
                $(_this.parents('.itwpt-table-base').find('table:not(.clone) tbody tr')[_this.parents('tr').index()]).find('.itwpt-checkbox-selector').addClass('active');
                $(_this.parents('.itwpt-table-base').find('table.clone tbody tr')[_this.parents('tr').index()]).find('.itwpt-checkbox-selector').addClass('active');
            }

            if (_table.find('.itwpt-checkbox:not(.disable) .itwpt-checkbox-selector.active').length == _table.find('.itwpt-checkbox:not(.disable) .itwpt-checkbox-selector').length) {
                _table.parents('.itwpt-table-base').find('.itwpt-select-all .itwpt-checkbox-selector').addClass('active');
            } else {
                _table.parents('.itwpt-table-base').find('.itwpt-select-all .itwpt-checkbox-selector').removeClass('active');
            }

            var btn = _this.parents('.itwpt-table-base').find('.btn-add-all.itwpt-button span');
            btn.html(_this.parents('.itwpt-table-base').find('.itwpt-table table:not(.clone) td:not(.sticky-row) .itwpt-checkbox-selector.active').length);

            e.stopPropagation();

        });

        // CHANGE NUMBER INPUT
        $(document).on('keyup', '.itwpt-quantity input', function () {
            $(this).trigger('change');
        });
        $(document).on('change', '.itwpt-quantity input', function () {

            $(this).parents('tr').find('.add-to-cart').attr('data-quantity', $(this).val());
            $(this).parents('tr').find('.itwpt-checkbox-selector').attr('data-quantity', $(this).val());
            // STICKY RESET
            Itwpt_Control_Sticky();

            // TOTAL PRICE
            Itwpt_Total_Price($(this).parents('tr'));

        });

        // CONTROL NUMBER QUANTITY
        setTimeout(function () {

            $.each($('.itwpt-quantity input'), function () {
                $(this).attr('min', '1');
                $(this).val('1');
            });

        }, 50);

        // CONTROL NUMBER INPUT
        Itwpt_Quantity_Nav();

        // CHECK PRODUCT IN CART
        var checkProductCart = ''; // WOO HASH
        setInterval(function () {

            // VARIABLES
            var allCookie = document.cookie; // GET COOKIES

            if (allCookie !== undefined || allCookie !== '') {

                // VARIABLES
                var isProduct = false; // CHECK PRODUCT IN CART
                allCookie = allCookie.replace(/ /g, ''); // REMOVE SPACE IN COOKIES STRING

                $.each(allCookie.split(';'), function () {

                    // VARIABLES
                    var _this = this.split('='); // GET COOKIE VARIABLE [NAME,VALUE]

                    // CHECK EXIST VARIABLE WOO CART HASH
                    if (_this[0] === 'woocommerce_cart_hash') {

                        // VARIABLES
                        isProduct = true; // CHANGE TO TRUE

                        if (checkProductCart !== _this[1]) {

                            // VARIABLES
                            var ajaxData =
                            {
                                action: "Itwpt_Ajax_Get_Product_Cart",
                                nonce: itwpt_localize.nonce,
                            };

                            // AJAX
                            $.ajax({
                                type: "post",
                                url: itwpt_localize.ajaxUrl,
                                data: ajaxData,
                                success: function (resp) {

                                    // VARIABLES
                                    var cart_items = $('.itwpt-cart');

                                    cart_items.find('.itwpt-cart-items').html(resp);
                                    $.each(cart_items, function () {

                                        // VARIABLES
                                        var total = 0;
                                        var qty = 0;
                                        var symbol = '$';
                                        var symbol = '$';

                                        $.each($(this).find('.itwpt-cart-item'), function () {
                                            total += Number($(this).data('total'));
                                            qty += Number($(this).data('qty'));
                                            symbol = $(this).data('symbol');
                                        });



                                        var html = '';
                                        switch(itwpt_localize.symbol_pos){

                                            case "left" :{
                                                html = '<span class="woocommerce-Price-currencySymbol">' + symbol + '</span>' + total.toFixed(2);
                                            }
                                                break;

                                            case "right" :{
                                                html = total.toFixed(2) + '<span class="woocommerce-Price-currencySymbol">' + symbol + '</span>';
                                            }
                                                break;

                                            case "right_space" :{
                                                html = total.toFixed(2) + ' <span class="woocommerce-Price-currencySymbol">' + symbol + '</span>';
                                            }
                                                break;

                                            case "left_space" :{
                                                html = '<span class="woocommerce-Price-currencySymbol">' + symbol + '</span> ' + total.toFixed(2);
                                            }
                                            break;
                                        }

                                        $(this).find('.subtotal > span').html(html);
                                        $(this).find('.qty > span').html(qty);

                                    });

                                    // ALARM
                                    if (addProduct) {

                                        if (Number(itwpt_localize_data.add_settings_popup_notice)) {
                                            Itwpt_Alarm({
                                                type: 'success',
                                                text: itwpt_localization.add_localization_product_added,
                                                ok: function () {
                                                },
                                                interval: 5000
                                            });
                                        }
                                        addProduct = false;
                                    } else if (updateCart) {
                                        if (Number(itwpt_localize_data.add_settings_popup_notice)) {
                                            /**
                                             * TODO ALARM UPDATE CART FOR NEXT VERSION
                                             */
                                        }
                                        updateCart = false;
                                    }

                                },
                                error: function (ert) {

                                    if (Number(itwpt_localize_data.add_settings_popup_notice)) {
                                        Itwpt_Alarm({
                                            type: 'error',
                                            text: itwpt_localization.add_localization_can_not_cart_update,
                                            ok: function () {
                                            },
                                            interval: 5000
                                        });
                                    }

                                }
                            });

                        }
                        checkProductCart = _this[1];

                    }
                });
                if (!isProduct) {

                    $('.itwpt-cart-items').html('<div class="itwpt-cart-empty"> ' + itwpt_localization.add_localization_cart_empty + '</div><div class="clear"></div>');
                    $('.itwpt-cart .subtotal > span').html((0).toFixed(2));
                    $('.itwpt-cart .qty > span').html('0');

                }
            }


        }, 200);

        // REMOVE ALL PRODUCTS IN CART
        $(document).on('click', '.itwpt-cart .btn-delete', function () {

            var _this = $(this);
            var ajaxData =
            {
                action: "Itwpt_Ajax_Remove_All_Product_Cart",
                nonce: itwpt_localize.nonce,
            };
            $.ajax({
                type: "post",
                url: itwpt_localize.ajaxUrl,
                data: ajaxData,
                beforeSend: function () {
                    Itwpt_Create_Loading(_this.parents('.itwpt-cart, .itwpt-cart-popup'));
                },
                complete: function () {
                    Itwpt_Remove_Loading(_this.parents('.itwpt-cart, .itwpt-cart-popup'));
                },
                success: function (response) {

                    if (Number(itwpt_localize_data.add_settings_popup_notice)) {
                        Itwpt_Alarm({
                            type: 'success',
                            text: itwpt_localization.add_localization_cart_clear,
                            ok: function () {
                            },
                            interval: 5000
                        });
                    }

                    if (response.fragments)
                        $(document.body).trigger('added_to_cart', [response.fragments]);

                },
                error: function (ert) {

                    Itwpt_Alarm({
                        type: 'error',
                        text: itwpt_localization.deleted_all_product_error,
                        ok: function () {
                        },
                        interval: 5000
                    });

                }
            });

        });

        // REMOVE ALL PRODUCTS IN CART
        $(document).on('click', '.itwpt-cart .itwpt-cart-item .delete i', function () {

            var _this = $(this);
            var ajaxData =
            {
                action: "Itwpt_Ajax_Remove_By_Id_Product_Cart",
                id: $(this).data('id'),
                nonce: itwpt_localize.nonce,
            };
            $.ajax({
                type: "post",
                url: itwpt_localize.ajaxUrl,
                data: ajaxData,
                beforeSend: function () {
                    Itwpt_Create_Loading(_this.parents('.itwpt-cart-item'), 'small');
                },
                complete: function () {
                    Itwpt_Remove_Loading(_this);
                },
                success: function (response) {

                    if (Number(itwpt_localize_data.add_settings_popup_notice)) {
                        Itwpt_Alarm({
                            type: 'success',
                            text: itwpt_localization.add_localization_product_deleted,
                            ok: function () {
                            },
                            interval: 5000
                        });
                    }

                    if (response.fragments)
                        $(document.body).trigger('added_to_cart', [response.fragments]);

                },
                error: function () {

                    Itwpt_Alarm({
                        type: 'error',
                        text: itwpt_localization.add_localization_product_deleted_error,
                        ok: function () {
                        },
                        interval: 5000
                    });

                }
            });

        });

        // ADD TO CART
        $(document).on('click', '.add-to-cart.itwpt-button', function () {

            // CONTROL CLONE

            if ($(this).parents('table.clone')[0]) {

                var _tr = $(this).parents('tr');
                var _table = $(this).parents('.itwpt-table').find('table:not(.clone)');
                var _CheckHeader = $(this).parents('table').find('thead')[0];
                var _tblRow = $(_table.find('tr')[_tr.index() + (_CheckHeader ? 1 : 0)]);

                _tblRow.find('.action_custom_column .add-to-cart').trigger('click');
                return false;

            }

            // VARIABLES
            var _this = $(this);
            var _error = false;
            var variation = $(this).attr('data-variation');
            var quantity = $(this).attr('data-quantity');
            var product_id = $(this).attr('data-product_id');
            var product_message = $(this).attr('data-message');
            var item = [{ variation: variation, quantity: quantity, id: product_id, Itwpt_custom_message: product_message }];

            var ajaxData =
            {
                action: "Itwpt_Ajax_Add_Group_Product_Cart",
                nonce: itwpt_localize.nonce,
                products: item
            };

            function Itwpt_Add_To_Cart_Reset() {

                var _tr = _this.parents('tr');
                var _index = _this.parents(_tr.index());
                var _sticky = _this.parents('.itwpt-table').find('table.clone');
                var _trSticky = $(_sticky.find('tr')[_index + 1]);

                _tr.find('.sticky-row .add-to-cart, .sticky-row .itwpt-variation, .sticky-row a').remove();
                _trSticky.find('.sticky-row .add-to-cart, .sticky-row .itwpt-variation, .sticky-row a').remove();

                _tr.find('.sticky-row').append(_this.parent().html());
                _trSticky.find('.sticky-row').append(_this.parent().html());

            }

            $.ajax({
                type: "post",
                url: itwpt_localize.ajaxUrl,
                data: ajaxData,
                beforeSend: function () {

                    _this.addClass('stop-event');
                    Itwpt_Create_Loading(_this, 'small');
                    _this.find('span').html(itwpt_localization.add_localization_add_to_cart_adding_text);
                    Itwpt_Add_To_Cart_Reset();
                    Itwpt_Control_Sticky();

                },
                complete: function () {

                    _this.removeClass('stop-event');
                    Itwpt_Remove_Loading(_this);
                    if (!_error) {
                        _this.find('span').html(' ' + itwpt_localization.add_localization_add_to_cart_added_text);
                    } else {
                        _this.find('span').html(' ' + itwpt_localization.add_localization_add_to_cart_text);
                    }
                    Itwpt_Add_To_Cart_Reset();
                    Itwpt_Control_Sticky();


                    $.each($('.itwpt-table-base'), function () {
                        $(this).find('.itwpt-table-scroll').mCustomScrollbar("update");
                    });

                },
                success: function (response) {

                    var data_msg = Itwpt_Get_Cookie('itwpt_message_cookie');
                    data_msg = JSON.parse(data_msg);

                    // SUCCESS
                    if (data_msg.success[0]) {

                        updateCart = true;
                        if (response.fragments) {
                            $(document.body).trigger('added_to_cart', [response.fragments]);
                            if (!(_this.parent().find('.view-cart')[0])) {
                                _this.parent().append('<a class="view-cart" href="' + itwpt_localize.cartUrl + '"><i class="icon-itpreview itwpt-button"></i></a>');
                            }
                        }

                    }

                    // ERROR
                    if (data_msg.error[0]) {

                        _error = true;
                        if (Number(itwpt_localize_data.add_settings_popup_notice)) {
                            Itwpt_Alarm({
                                type: 'error',
                                text: itwpt_localization.add_localization_can_not_product_added,
                                ok: function () {
                                },
                                interval: 5000
                            });
                        }

                    }

                    if (Number(itwpt_localize_data.add_settings_enable_quick_buy_button)) {
                        location.href = itwpt_localize.checkout;
                    }

                    addProduct = true;
                    Itwpt_Add_To_Cart_Reset();
                    Itwpt_Control_Sticky();

                },
                error: function () {
                    _error = true;
                    // ERROR
                }
            });

        });

        // ADD GROUP
        $(document).on('click', '.btn-add-all', function () {

            // VARIABLES
            var _this = $(this);
            var itemsSelected = $(this).parents('.itwpt-table-base').find('table:not(.clone) td:not(.sticky-row) .itwpt-checkbox-selector');
            var items = [];

            if (!($(this).parents('.itwpt-table-base').find('table:not(.clone) td:not(.sticky-row) .itwpt-checkbox-selector.active')[0])) {
                if (Number(itwpt_localize_data.add_settings_popup_notice)) {
                    Itwpt_Alarm({
                        type: 'error',
                        text: itwpt_localization.add_localization_select_all_item_text,
                        ok: function () {
                        },
                        interval: 5000
                    });
                }
                return false;
            }

            $.each(itemsSelected, function () {

                if ($(this).hasClass('active')) {

                    // VARIABLES
                    var variation = $(this).attr('data-variation');
                    var quantity = $(this).attr('data-quantity');
                    var product_id = $(this).attr('data-id');
                    var product_message = $(this).attr('data-message');
                    items.push({
                        variation: variation,
                        quantity: quantity,
                        id: product_id,
                        Itwpt_custom_message: product_message
                    });

                }

            });

            var ajaxData =
            {
                action: "Itwpt_Ajax_Add_Group_Product_Cart",
                nonce: itwpt_localize.nonce,
                products: items
            };

            $.ajax({
                type: "post",
                url: itwpt_localize.ajaxUrl,
                data: ajaxData,
                beforeSend: function () {
                    _this.addClass('stop-event');
                    Itwpt_Create_Loading(_this, 'small');
                },
                complete: function () {
                    _this.removeClass('stop-event');
                    Itwpt_Remove_Loading(_this);
                },
                success: function (response) {

                    // CONTROL UPDATE CART
                    updateCart = true;

                    // FRAGMENT
                    if (response.fragments)
                        $(document.body).trigger('added_to_cart', [response.fragments]);

                    // ALARM
                    var data_msg = Itwpt_Get_Cookie('itwpt_message_cookie');
                    data_msg = JSON.parse(data_msg);

                    // SUCCESS
                    if (Number(itwpt_localize_data.add_settings_popup_notice) && (data_msg.success !== null || data_msg.success !== '')) {
                        Itwpt_Alarm({
                            type: 'success',
                            text: data_msg.success.join(', ').replace(/\+/g, ' ') + ' ' + itwpt_localization.add_localization_product_added,
                            ok: function () {
                            },
                            interval: 10000
                        });
                    }

                    // ERROR
                    if (Number(itwpt_localize_data.add_settings_popup_notice) && data_msg.error[0]) {

                        Itwpt_Alarm({
                            type: 'error',
                            text: data_msg.error.join(', ').replace(/\+/g, ' ') + ' ' + itwpt_localization.add_localization_can_not_product_added,
                            ok: function () {
                            },
                            interval: 10000
                        });
                    }

                    // REDIRECT
                    if (Number(itwpt_localize_data.add_settings_direct_checkout_page)) {
                        location.href = itwpt_localize.checkout;
                    }

                },
                error: function () {
                    // ERROR
                }
            });

        });

        // CART LAYOUT FIXED - CONTROL ACTIVE
        $(document).on('click', '.itwpt-cart-button-fixed, .itwpt-cart .close, .itwpt-cart .itwpt-cart-overly', function () {

            // VARIABLES
            var _parent = $(this).parents('.itwpt-cart');

            if (_parent.hasClass('active')) {
                _parent.removeClass('active');
            } else {
                _parent.addClass('active');
            }

        });

        $(document).on('click', '.add_to_cart_button', function () {
            updateCart = true;
        });

        // SELECT ALL
        $(document).on('click', '.itwpt-select-all', function (e) {

            // VARIABLES
            var checkSelector = $(this).find('.itwpt-checkbox .itwpt-checkbox-selector');
            var btn = $(this).parents('.itwpt-controls').find('.btn-add-all.itwpt-button span');

            // CONTROL
            if (checkSelector.hasClass('active')) {

                checkSelector.removeClass('active');
                checkSelector.parents('.itwpt-table-base').find('.itwpt-table .itwpt-checkbox-selector').removeClass('active');

            } else {

                $.each(checkSelector.parents('.itwpt-table-base').find('.itwpt-table .itwpt-checkbox-selector'), function () {

                    if (!$(this).parent().hasClass('disable')) {
                        checkSelector.addClass('active');
                        $(this).addClass('active');
                    }

                });

            }

            // CHANGE NUMBER BUTTON
            btn.html(checkSelector.parents('.itwpt-table-base').find('.itwpt-table table:not(.clone) td:not(.sticky-row) .itwpt-checkbox-selector.active').length);

            e.stopPropagation();

        });

        // FILTERS
        $(document).on("change", ".itwpt-filter-selector select", function () {

            // VARIABLES
            var _this = $(this);
            var cls = $(this).parent().hasClass('filter-cat');
            var table = $(this).parents('.itwpt-table-base').find('table');

            // FILTER
            if (cls) { // CATEGORY

                $.each(table.find('td.category_tbl_column'), function () {

                    if (_this.val() === 'all') {
                        table.find('tr').css({ 'display': 'table-row' });
                        _this.parents('.itwpt-table-base').find('.itwpt-filter select').val('all');
                    } else if ($(this).parents('tr').css('display') === 'table-row') {

                        if (this.textContent.indexOf(_this.val()) >= 0) {

                        } else {
                            $(this).parents('tr').css({ 'display': 'none' });
                        }

                    }
                });

            } else { // TAG

                $.each(table.find('td.tags_tbl_column'), function () {

                    if (_this.val() === 'all') {
                        table.find('tr').css({ 'display': 'table-row' });
                        _this.parents('.itwpt-table-base').find('.itwpt-filter select').val('all');
                    } else if ($(this).parents('tr').css('display') === 'table-row') {

                        if (this.textContent.indexOf(_this.val()) >= 0) {

                        } else {
                            $(this).parents('tr').css({ 'display': 'none' });
                        }

                    }

                });

            }

        });

        // CLEAR FILTERS
        $(document).on('click', '.clear-filters', function () {
            $(this).parents('.itwpt-table-base').find('.itwpt-filter select').val('all');
            $(this).parents('.itwpt-table-base').find('tr').css({ 'display': 'table-row' });
        });

        // SEARCH SETTINGS
        $(document).on('click', '.itwpt-table-base .itwpt-search .setting', function () {

            var _this = $(this);
            var options = _this.parents('.itwpt-controls').find('.itwpt-search-options');

            if (_this.hasClass('active')) {
                _this.removeClass('active');
                options.css({ 'display': 'none' });
            } else {
                _this.addClass('active');
                options.css({ 'display': 'block' });
            }

        });

        // OPEN POPUP VARIATION
        $(document).on('click', '.itwpt-variation-btn', function (e) {

            var _this = $(this);
            var _table = _this.parents('.itwpt-table');
            var _popupHtml = Itwpt_Html_Decode(_this.data('popup'));
            var _popupObject = $(_popupHtml);
            $('.variation-popup').remove();

            if ($(this).parent().hasClass('active')) {
                $(this).parent().removeClass('active');
            } else {
                $('.itwpt-variation').removeClass('active');
                $(this).parent().addClass('active');
                _table.append(_popupObject);
                _table.find('.variation-popup').css({
                    'top': (_this.offset().top - _table.offset().top + 50),
                    'left': (_this.offset().left - _table.offset().left + ((40 + _this.width()) / 2))
                });
                if (_this.data('default-val')) {

                    // VARIABLE
                    var data_select = JSON.parse(_this.attr('data-default-val'));

                    $.each(data_select, function (a, v) {
                        _table.find(`.variation-popup [name=${a}]`).val(v);
                    });

                }
            }

        });

        // CONTROLS VARIATIONS
        $(document).on('change', '.variation-popup select', function () {

            /** VARIABLES */
            var _this = $(this);
            var _variationPopup = _this.parents('.variation-popup');
            var _tr = $('.' + _this.parents('.variation-popup').data('id'));
            var variations_json = _tr.data('variation-product');
            var vrn_select = _this.parents('.variation-popup-dropdown').find('select');
            var VariationDataSelectVal = [];
            var checkAllVall = true;
            var status = 'nfp';
            var message = itwpt_localization.add_localization_variation_not_available_text;
            var data_select = {};

            function Itwpt_Get_CVal(obj, a) {
                VariationDataSelectVal[obj.attr(a)] = obj.val();
            }

            function Itwpt_Check_Select() {
                $.each(VariationDataSelectVal, function (key, value) {
                    (value == '0') ? checkAllVall = false : checkAllVall;
                });
            }

            function Itwpt_Variation_Data_Controler(value, key) {
                return (value === "" || value === VariationDataSelectVal[key]);
            }

            $.each(vrn_select, function () {
                data_select[$(this).attr('name')] = $(this).val();
                Itwpt_Get_CVal($(this), 'name');
            });

            _tr.find('.itwpt-variation-btn').attr('data-default-val', JSON.stringify(data_select));

            variations_json.forEach(function (data_each, numberline) {

                // VARIABLES
                var total_rtComb = 0;
                var total_Comb = 0;

                Itwpt_Check_Select();
                if (checkAllVall) {
                    $.each(data_each.attributes, function (key, value) {
                        total_Comb++;
                        if (Itwpt_Variation_Data_Controler(value, key)) total_rtComb++;
                    });
                    if (total_rtComb === total_Comb) {
                        status = parseInt(numberline);
                    }
                } else {
                    message = "Please Select All Items"; //"Please select all Items.";
                }

            });

            if (status !== 'nfp') {

                var varObj = variations_json[status];
                if (varObj.is_in_stock) {

                    message = '<span>' + itwpt_localization.add_localization_in_stock_text + '</span>';
                    var v_price = varObj.price_html;
                    $(this).parents('.variation-popup').find('.price span').html(' ' + v_price);

                    $(document).on('click', _variationPopup.find('.itwpt-button'), function () {
                        _tr.find('.price_custom_column').html(v_price);
                        _tr.find('div.add-to-cart').removeClass('disable').attr('data-variation', varObj.variation_id);
                        _tr.find('div.itwpt-checkbox').removeClass('disable');
                        _tr.find('div.itwpt-checkbox-selector').attr('data-variation', varObj.variation_id);
                        if (_tr.find('.total-price_custom_column')[0]) {
                            _tr.find('.total-price_custom_column .total').attr('data-price', varObj.display_price);
                        }
                    });

                } else {

                    message = '<span>' + itwpt_localization.add_localization_out_of_stock_text + '</span>';
                    $(document).on('click', _variationPopup.find('.itwpt-button'), function () {
                        _tr.find('div.add-to-cart').addClass('disable');
                        _tr.find('div.itwpt-checkbox').addClass('disable').children('div.itwpt-checkbox-selector').removeClass('active');
                    });

                }

            } else {

                message = '<span>' + itwpt_localization.add_localization_select_all_item_text + '</span>';
                $(document).on('click', _variationPopup.find('.itwpt-button'), function () {
                    _tr.find('div.add-to-cart').addClass('disable');
                    _tr.find('div.itwpt-checkbox').addClass('disable').children('div.itwpt-checkbox-selector').removeClass('active');
                });

            }

            // CHECK IN STOCK
            _variationPopup.find('.status').html(message); // varObj.availability_html
            setTimeout(function () {
                Itwpt_Total_Price(_tr);
            }, 50);

        });

        // CLOSE POPUP
        $(document).on('click', '.variation-popup .itwpt-button', function () {

            var _this = $(this);
            var _tr = $('.' + _this.parents('.variation-popup').data('id'));

            // CHECK VALUE SELECTS
            $.each(_this.parent().find('select'), function () {

                if ($(this).val() === '0') {
                    if (Number(itwpt_localize_data.add_settings_popup_notice)) {
                        Itwpt_Alarm({
                            type: 'error',
                            text: itwpt_localization.add_localization_no_right_combination_text,
                            ok: function () {
                            },
                            interval: 5000
                        });
                    }
                    return false;
                }

            });

            // CLOSE VARIATION POPUP
            _tr.find('.itwpt-variation').removeClass('active');
            _this.parents('.variation-popup').remove();

        });

        // CLOSE VARIATION AND POPUP CART
        $(document).on('click', function (e) {

            // VARIATION
            if (!$(e.target).parents().addBack().is('.itwpt-button') && !$(e.target).parents().addBack().is('.variation-popup')) {
                $('.variation-popup').remove();
                $('.itwpt-variation').removeClass('active');
            }

            // CART POPUP
            if (!$(e.target).parents().addBack().is('.itwpt-cart-button-fixed') && !$(e.target).parents().addBack().is('.itwpt-cart-popup')) {
                $('.itwpt-cart.layout-fixed').removeClass('active');
            }

        });

        // SORT TABLE
        $(document).on('click', '.column-sort', function () {

            var _this = $(this);
            var table, rows, switching, i, x, y, shouldSwitch;
            table = document.getElementById(_this.parents('table').attr('id'));
            switching = true;
            var asc = true;
            var columnNumber = $(this).index();
            var _parentBase = _this.parents('.itwpt-table-base');
            var cloneStatus = _parentBase.find('table.clone');

            if (sortElement === undefined) {
                sortElement = _parentBase.find('table:not(.clone)').html();
            }

            var check_asc = _this.hasClass('ascending');

            _this.parents('table').find('td').removeClass('sorted ascending descending');
            _this.parents('table').find('th').removeClass('sorted ascending descending');
            $(_this.parents('table').find('thead th')[cloneStatus[0] ? columnNumber : columnNumber]).addClass('sorted');
            $(_this.parents('table').find('tfoot th')[cloneStatus[0] ? columnNumber : columnNumber]).addClass('sorted');

            if (check_asc) {
                _this.removeClass('ascending');
                _this.addClass('descending');
                asc = false;
            } else {
                _this.addClass('ascending');
                _this.removeClass('descending');
                asc = true;
            }
            /*Make a loop that will continue until
            no switching has been done:*/
            while (switching) {
                //start by saying: no switching is done:
                switching = false;
                rows = table.rows;
                /*Loop through all table rows (except the
                first, which contains table headers):*/
                for (i = 1; i < (rows.length - 1); i++) {
                    //start by saying there should be no switching:
                    shouldSwitch = false;
                    /*Get the two elements you want to compare,
                    one from current row and one from the next:*/
                    $($(rows[i]).find('td')[columnNumber]).addClass('sorted');
                    x = rows[i].getElementsByTagName("TD")[columnNumber];
                    y = rows[i + 1].getElementsByTagName("TD")[columnNumber];
                    //check if the two rows should switch place:
                    if (asc) {
                        if ((x !== undefined && y !== undefined) && x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            //if so, mark as a switch and break the loop:
                            shouldSwitch = true;
                            break;
                        }
                    } else {
                        if ((x !== undefined && y !== undefined) && x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            //if so, mark as a switch and break the loop:
                            shouldSwitch = true;
                            break;
                        }
                    }
                }
                if (shouldSwitch) {
                    /*If a switch has been marked, make the switch
                    and mark that a switch has been done:*/
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                }
            }

            cloneStatus.find('tbody').html($(table).find('tbody').html());

        });

        // ROW NAV
        setTimeout(function () {
            $('.itwpt-filter-row-number select').trigger('change')
        }, 100);
        $(document).on('change', '.itwpt-filter-row-number select', function () {

            var _this = $(this);
            var _tableRow = _this.parents('.itwpt-table-base').find('table');

            $.each(_tableRow, function () {
                var n = 1;
                $.each($(this).find(' tbody tr'), function () {
                    if (n++ <= Number(_this.val())) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

        });

        // SEARCH WITH ENTER
        $(document).on('keydown', '.itwpt-search input, .itwpt-search-option .sku', function (e) {

            if (e.keyCode === 13) {
                $(this).parents('.itwpt-controls').find('.itwpt-search-btn').trigger('click');
            }

        });

        // SEARCH WITH CHANGE DROPDOWN
        $(document).on('change', '.itwpt-search-options select', function () {
            $(this).parents('.itwpt-controls').find('.itwpt-search-btn').trigger('click');
        });

        // SEARCH RESET
        $(document).on('click', '.itwpt-search-reset-btn', function () {

            // VARIABLE
            var _this = $(this);
            var _options = _this.parent().find('.itwpt-search-options');
            var _btnSearch = _this.parent().find('.itwpt-search-btn');
            var n;

            // EACH ON OPTIONS
            $.each(_options.find('.itwpt-search-option'), function () {

                if ($(this).data('reset')) {

                    $(this).find('select').val($(this).data('reset'));
                    $(this).find('input').val($(this).data('reset'));

                }

            });

            // RESET TITLE
            _this.parent().find('.itwpt-search input').val('');

            // RESET SKU
            if (_options.find('.sku')[0]) {
                n = _options.find('.sku');
                n.val('');
            }

            // RESET MULTI SELECT
            if (_options.find('.itwpt-search-option.multiselect select')[0]) {
                n = _options.find('.itwpt-search-option.multiselect select');
                n.val(null).trigger("change");
            }

            // RUN QUERY
            _btnSearch.trigger('click');

        });

        // SEARCH, PAGINATION
        $(document).on('click', '.itwpt-search-btn, .itwpt-pagination a, .itwpt-load-more-content', function (e) {

            // VARIABLES
            var _this = $(this);
            var _base = _this.parents('.itwpt-table-base');
            var _table = _base.find('.itwpt-table-scroll table');
            var _controler = _base.find('.itwpt-search-options');
            var _Search = _controler.parent().find('.itwpt-search input').val();
            var _OrderBy = _controler.find('select.order-by').val();
            var _Order = _controler.find('select.order').val();
            var _PriceMin = _controler.find('select.min-price').val();
            var _PriceMax = _controler.find('select.max-price').val();
            var _Status = _controler.find('select.status').val();
            var _Sku = _controler.find('input.sku').val();
            var data = _base.data('setting');
            var _id = _base.attr('id');
            var pagination = false;
            var loadMoreElement = _base.find('.itwpt-footer').hasClass('load-more-enable');
            var paginationElement = _base.find('.itwpt-footer').hasClass('pagination-enable');
            var ajaxData =
            {
                action: "Itwpt_Ajax_Search",
                nonce: itwpt_localize.nonce,
                search: _Search !== undefined ? _Search : '',
                product_status: _Status !== undefined ? _Status : itwpt_localize_data.add_conditions_product_status,
                product_order_by: _OrderBy !== undefined ? _OrderBy : itwpt_localize_data.add_query_order_by,
                product_order: _Order !== undefined ? _Order : itwpt_localize_data.add_query_order,
                price_range: _PriceMin !== undefined ? _PriceMin + '-' + _PriceMax : itwpt_localize_data.add_conditions_min_price + '-' + itwpt_localize_data.add_conditions_max_price,
                sku: _Sku !== undefined ? _Sku : '',
                data: data,
                id: _id,
                paged: 1
            };

            // EACH ON SELECT TAXONOMY
            $.each(_controler.find('.multi-select'), function () {

                var array = [];
                var name = $(this).data('taxonomy');

                $.each($(this).select2('data'), function () {
                    array.push(this.id);
                });

                if (array[0]) {
                    ajaxData['it' + name] = array.join(',');
                } else {
                    ajaxData['it' + name] = $(this).data('default');
                }

            });

            // PAGINATION
            if (_this.parents('.itwpt-pagination-content')[0]) {

                pagination = true;
                if (_this.hasClass('next')) {
                    ajaxData.paged = Number(_this.parent().attr('data-current-page')) + 1;
                } else if (_this.hasClass('prev')) {
                    ajaxData.paged = Number(_this.parent().attr('data-current-page')) - 1;
                } else {
                    ajaxData.paged = Number($(this).text());
                }

            }

            if (_this.hasClass('itwpt-load-more-content')) {
                pagination = false;
                ajaxData.paged = Number(_this.data('current-page')) + 1;
            }

            // AJAX
            $.ajax({
                type: "post",
                url: itwpt_localize.ajaxUrl,
                data: ajaxData,
                beforeSend: function () {
                    Itwpt_Create_Loading(_this, 'small');
                },
                complete: function () {
                    Itwpt_Remove_Loading(_this);
                },
                success: function (resp) {


                    // VARIABLE
                    var out = '';

                    // MESSAGE
                    if (resp.row === "" && Number(itwpt_localize_data.add_settings_popup_notice)) {
                        Itwpt_Alarm({
                            type: 'error',
                            text: itwpt_localization.add_localization_is_no_more_products_text,
                            ok: function () {
                            },
                            interval: 5000
                        });
                    }

                    // CONTROL ROWS
                    out = resp.row;

                    if (_this.hasClass('itwpt-search-btn')) {
                        _table.find('tbody').html(out);
                    } else {
                        if (!pagination) {
                            if (sortElement !== undefined) {
                                _base.find('table:not(.clone)').html(sortElement);
                                sortElement = undefined;
                            }
                            _table.find('tbody').append(out);
                        } else {
                            _table.find('tbody').html(out);
                        }
                    }
                    Itwpt_Control_Sticky();

                    // PAGINATION
                    if (paginationElement) {
                        if (resp.pagination.html !== null) {

                            var pagination_html = '<div class="itwpt-pagination"><div class="itwpt-pagination-content" data-current-page="' + resp.pagination.paged + '">' + resp.pagination.html + '</div></div>';
                            _base.find('.itwpt-pagination').remove();
                            _base.find('.itwpt-footer').prepend(pagination_html);

                        } else {
                            _base.find('.itwpt-pagination').remove();
                        }
                    }

                    // LOAD MORE
                    if (loadMoreElement) {

                        if (resp.pagination.count === Number(resp.pagination.paged)) {
                            _base.find('.itwpt-load-more').remove();
                        } else if (resp.pagination.html !== null && !(resp.row === "" && loadMoreElement)) {

                            var load_more_html = '<div class="itwpt-load-more"><div class="itwpt-load-more-content" data-current-page="' + resp.pagination.paged + '" data-loading-text="' + itwpt_localization.add_localization_loading_button_text + '">' + itwpt_localization.add_localization_load_more_text + '</div></div>';
                            _base.find('.itwpt-load-more').remove();
                            _base.find('.itwpt-footer').prepend(load_more_html);

                        } else {
                            _base.find('.itwpt-load-more').remove();
                        }

                    }

                    // RESET CONTROL NUMBER
                    Itwpt_Quantity_Nav();

                    // RESET SL, SORT
                    _base.find('td,th').removeClass('sorted ascending');
                    Itwpt_Reset_Sl(_base);

                },
                error: function (ert) {

                    Itwpt_Alarm({
                        type: 'error',
                        text: itwpt_localization.update_cart_error,
                        ok: function () {
                        },
                        interval: 5000
                    });

                }
            });

            // STOP LINK
            e.preventDefault();

        });

        $(document).on('keyup', '.itwpt-short-message', function () {

            // MESSAGE
            $(this).parents('tr').find('.add-to-cart').attr('data-message', $(this).val());
            $(this).parents('tr').find('.itwpt-checkbox-selector').attr('data-message', $(this).val());
            Itwpt_Control_Sticky();

        });

        // ALARM
        function Itwpt_Alarm(options) {

            // VARIABLES
            var icon = '';
            var button = '';
            var cls = 'itwp-alarm-' + Math.floor(Math.random() * 99999999999);

            // CHECK TYPE
            if (typeof options.type != "undefined") {

                if (options.type === 'success') icon = '<div class="icon-it icon-itsuccess"></div>';
                else if (options.type === 'warning') icon = '<div class="icon-it icon-itwarning"></div>';
                else icon = '<div class="icon-it icon-iterror"></div>';

            } else {
                icon = '<div class="icon-it icon-itsuccess"></div>';
            }

            // CHECK BUTTON CANCEL
            if (typeof options.cancel !== "undefined") {
                button += '<div class="btn cancel">cancel</div>';
                $(document).on('click', '.' + cls + ' .btn.cancel', function () {
                    options.cancel();
                    $('.' + cls).remove();
                });
            }

            // CHECK BUTTON OK
            if (typeof options.ok !== "undefined") {
                button += '<div class="btn ok">ok</div>';
                $(document).on('click', '.' + cls + ' .btn.ok', function () {
                    options.ok();
                    $('.' + cls).remove();
                });
            }

            // CREATE HTML
            var html =
                '<div class="alarm alarm-%type% %class%">' +
                '   <div class="content">' +
                '       <div class="icon">%icon%</div> ' +
                '       <div class="desc">%text%</div>' +
                '   </div>' +
                '   %btn%' +
                '</div>';

            html = Itwpt_String_Format(html, {
                "type": options.type,
                "text": options.text,
                "class": cls,
                "icon": icon,
                "btn": (button !== '') ? Itwpt_String_Format('<div class="btns">%buttons%</div>', { "buttons": button }) : ''
            });

            // APPEND AND SET TIMEOUT
            var out = $(html);
            if (!$('.alarm-box')[0]) {
                $('body').append('<div class="alarm-box"></div>');
            }
            $('body .alarm-box').append(out);

            // CLOSE INTERVAL
            if (typeof options.interval !== "undefined") {
                setTimeout(function () {
                    out.remove();
                }, options.interval);
            }

        }

        // STRING FORMAT
        function Itwpt_String_Format(str, array) {

            // ADD % TO NAME ARRAY
            $.each(array, function (index, value) {

                // VARIABLE
                var re = new RegExp('%' + index + '%', "g");

                // REPLACE
                str = str.replace(re, value);

            });

            // RETURN
            return str;

        }

        // FUNCTION CREATE LOADING
        function Itwpt_Create_Loading(elm, size = 'medium') {

            // VARIABLE
            var _textLoading = '';
            if (elm.data('loading-text') !== undefined) {
                _textLoading = '<span>' + elm.data('loading-text') + '</span>';
            }

            elm.append('<div class="itwpt-loading ' + size + '"><div class="itwpt-loading-content">' + _textLoading + ' <img src="' + itwpt_localize.loadingUrl + '"></div></div>');
        }

        // FUNCTION REMOVE LOADING
        function Itwpt_Remove_Loading(elm) {
            elm.find('.itwpt-loading').remove();
        }

        // DECODE HTML
        function Itwpt_Html_Decode(code) {

            var search = ['%A0;', '%A1;', '%A2;', '%A3;', '%A4;', '%A5;'];
            var replace = ['<', '>', "'", '"', '=', ' '];
            var html = code;

            $.each(search, function (index, value) {

                var re = new RegExp(value, "g");
                html = html.replace(re, replace[index]);

            });

            return html;

        }

        function Itwpt_Control_Sticky() {
            $.each($('.itwpt-table-base.sticky'), function () {

                // VARIABLE
                var _this = $(this).find('.itwpt-table-scroll table');
                var _row = _this.find('tbody tr');
                var _head = _this.find('thead tr');
                var _foot = _this.find('tfoot tr');
                var _column = '<th class="sticky-head">' + itwpt_localization.add_localization_sticky_label + '</th>';
                var _temp;

                // CONTROL EXIST COLUMN
                if (_this.find('.action_custom_column') === undefined && _this.find('.check_custom_column') === undefined) {
                    return false;
                }

                // CONTROL ENABLE STICKY
                if (!_this.hasClass('enable-sticky')) {
                    _head.prepend(_column);
                    _foot.prepend(_column);
                }

                // EACH ON ROWS
                $.each(_row, function () {

                    var _action = '';
                    var _checkbox = '';

                    // CHECK STCKY
                    if ($(this).find('.sticky-row')[0]) {
                        return;
                    }

                    // CONTROL AND GET ACTION HTML
                    if ($(this).find('.action_custom_column')[0]) {
                        _action = $(this).find('.action_custom_column').html();
                    }

                    // CONTROL AND GET COLUMN HTML
                    if ($(this).find('.check_custom_column')[0]) {
                        _checkbox = $(this).find('.check_custom_column').html();
                    }

                    // SET HTML
                    $(this).prepend('<td class="sticky-row">' + _checkbox + _action + '</td>');

                });

                // SET TAG
                _this.addClass('enable-sticky');

                // RESET CLONE
                $(this).find('.sticky-main-box').remove();
                _temp = $(this).find('.itwpt-table');
                _temp.append('<div class="sticky-main-box"></div>');
                _this.clone(true).appendTo(_temp.find('.sticky-main-box')).addClass('clone');

            });
        }

        // SET TOTAL PRICE
        function Itwpt_Total_Price(_tr) {

            // VARIABLE
            var _totalElement = _tr.find('.total-price_custom_column span'),
                _quantity = 0,
                _price = 0;

            // SET QUANTITY

            if (_tr.find('.quantity_custom_column')) {
                _quantity = Number(_tr.find('.quantity_custom_column input').val());
                _quantity = Number(_tr.find('.add-to-cart').attr("data-quantity"));
            }


            // SET PRICE
            _price = Number(_totalElement.parent().attr('data-price'));

            // PRC
            _totalElement.html(Itwpt_FormatMoney((_quantity * _price), 2, ".", ","));

        }

        // FORMAT NUMBER
        function Itwpt_FormatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
            try {
                decimalCount = Math.abs(decimalCount);
                decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

                const negativeSign = amount < 0 ? "-" : "";

                let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
                let j = (i.length > 3) ? i.length % 3 : 0;

                return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
            } catch (e) {
                console.log(e)
            }
        };

        // GET COOKIE
        function Itwpt_Get_Cookie(cname) {
            var name = cname + "=";
            var decodedCookie = decodeURIComponent(document.cookie);
            var ca = decodedCookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }

        /**
         * RESET SL
         * @param _base Object
         * @constructor
         */
        function Itwpt_Reset_Sl(_base) {

            // VARIABLES
            var _tbl = _base.find('table:not(.clone)');
            var _tblClone = _base.find('table.clone');
            var _n = 1;

            // TABLE ROW EACH
            $.each(_tbl.find('tbody tr'), function () {
                $(this).find('.sl_custom_column').html(_n++);
            });

            // TABLE CLONE
            if (_tblClone) {
                _n = 1;
                $.each(_tblClone.find('tbody tr'), function () {
                    $(this).find('.sl_custom_column').html(_n++);
                });
            }

        }

        // QUANTITY NAV
        function Itwpt_Quantity_Nav() {

            $.each($('.itwpt-quantity .nav'), function () {

                // VARIABLES
                var _this = $(this);
                var numberInterval;

                // NUMBER SPINNER MOUSE DOWN
                $(this)[0].addEventListener("mousedown", e => {
                    numberInterval = setInterval(function () {

                        var input = _this.parents('.itwpt-quantity').find('input');
                        input = _this.parents('.itwpt-quantity').find('input[name="quantity"]');
                        var val = input.val();
                        var max = input.attr('max');
                        var min = input.attr('min');

                        // CONTROL UP OR DOWN
                        if (_this.hasClass('up')) {

                            if (max !== undefined && max !== '') {

                                if (val < max) {
                                    val = Number(val) + 1;
                                    input.val(val);
                                    _this.parents('tr').find('.add-to-cart').attr('data-quantity', val);
                                    _this.parents('tr').find('.itwpt-checkbox-selector').attr('data-quantity', val);
                                }

                            } else {
                                val = Number(val) + 1;
                                input.val(val);
                                _this.parents('tr').find('.add-to-cart').attr('data-quantity', val);
                                _this.parents('tr').find('.itwpt-checkbox-selector').attr('data-quantity', val);
                            }

                        } else {

                            if (min !== undefined) {

                                if (val > min) {
                                    val = Number(val) - 1;
                                    input.val(val);
                                    _this.parents('tr').find('.add-to-cart').attr('data-quantity', val);
                                    _this.parents('tr').find('.itwpt-checkbox-selector').attr('data-quantity', val);
                                }

                            } else {
                                val = Number(val) - 1;
                                input.val(val);
                                _this.parents('tr').find('.add-to-cart').attr('data-quantity', val);
                                _this.parents('tr').find('.itwpt-checkbox-selector').attr('data-quantity', val);
                            }

                        }

                        Itwpt_Total_Price(_this.parents('tr'));

                    }, 72);

                    // STICKY RESET
                    Itwpt_Control_Sticky();

                });

                // NUMBER SPINNER MOUSE UP
                $(this)[0].addEventListener("mouseup", e => {
                    var input = _this.parents('.itwpt-quantity').find('input');
                    clearInterval(numberInterval);
                    input.focus();
                });

                // NUMBER SPINNER MOUSE UP
                $(this)[0].addEventListener("mouseleave", e => {
                    var input = _this.parents('.itwpt-quantity').find('input');
                    clearInterval(numberInterval);
                    input.focus();
                });

            });

        }

    });

})(jQuery);