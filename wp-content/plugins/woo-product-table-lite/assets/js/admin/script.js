(function($) {
    "use strict";

    jQuery(document).ready(function($){

        /**
         * TODO GLOBAL VARIABLES
         * @type {*}
         */
        var numberCheck = false;
        var numberInterval;

        /**
         * TODO START
         */
        Itwpt_Nav_Icon(); // RESET COLOR NAV
        setTimeout(function(){$('.loading').remove();},1000); // REMOVE LOADING
        Itwpt_Analysis_Dependency(); // CONTROL DEPENDENCY

        // SORTABLE CREATE
        $.each($('.itwpt-field-columns .items'),function(){
            new Sortable(this, {
                animation: 150,
                handle: '.handle',
                ghostClass: 'blue-background-class',
                onUnchoose: function() {
                    $('.itwpt-field-columns .items input').trigger('keyup');
                }
            });
        });

        // CHECK CONTROL DROPDOWN
        $(document).on('click','.select2-selection',function(){

            setTimeout(function(){
                if($('.select2-dropdown li').length > 1){
                    $('.select2-dropdown li.loading-results').remove();
                }
            },10);

        });

        // COLORPICKER CREATE
        $('.color_field').each(function(){
            $(this).wpColorPicker();
        });

        // MULTI SELECT
        $('.itwpt-fields .multi-select').select2({
            placeholder: itwpt_localize.selectoptions,
        });

        // MULTI SELECT - AJAX
        $('.itwpt-fields.ajax-action .multi-select').select2({
            placeholder: itwpt_localize.selectoptions,
            ajax: {
                type : "post",
                url : itwpt_localize.ajaxUrl,
                data: function (params) {
                    return {
                        action: "Itwpt_multi_ajax",
                        nonce: itwpt_localize.nonce,
                        search: params.term,
                        vAction: $(this).parents('.itwpt-fields').data('action')
                    };
                },
                processResults: function (data) {
                    // Transforms the top-level key of the response object from 'items' to 'results'
                    return {
                        results: JSON.parse(data)
                    };
                }
            }
        });

        /**
         * TODO EVENTS
         * ALL PAGES
         */
        // MENU
        $(document).on('click','.add-header .menu-sb',function(){

            var _this = $(this);
            var _parent = _this.parent();

            if(_this.hasClass('active')){

                _this.removeClass('active');
                _parent.find('ul').hide();

            }else{

                _this.addClass('active');
                _parent.find('ul').show();

            }

            $(window).resize(function () {
                if ($(window).width() > 1060) {
                    _parent.find('ul').css('display', 'flex');
                }
            });

        });

        //SELECT NAVE ITEM
        $(document).on('click', '.add-header .nav-item', function (e) {

            // REMOVE ALL ACTIVE AND CURRENT ELEMENT ACTIVE
            $(this).parent().find('.nav-item a').removeClass('active');
            $(this).find('a').addClass('active');

            // RESET COLOR
            Itwpt_Nav_Icon();

            // ACTIVE FORM
            $('.dbody .itwpt-form').removeClass('active');
            $('.dbody .itwpt-form-tab-' + $(this).data('tab')).addClass('active');

            // DISABLE LINK
            e.preventDefault();

        });

        // ADD TEMPLATE
        $(document).on('click','button.add-template',function(){

            // VARIABLES
            var _this = $(this);
            var data = encodeURI(JSON.stringify(Itwpt_Get_Data()));
            var image = $('.itwpt-field-media li');

            var id = Itwpt_Get_Url_Parameter('edit');
            if (id == undefined) {
                id = _this.data('id');
            }
            var ajaxData =
                {
                    action: "Itwpt_add_template",
                    nonce: itwpt_localize.nonce,
                    id: id,
                    name: null,
                    image: null,
                    data: data
                };

            // SET DATA
            ajaxData.name = ($('input#tmp_name').val() !== '' ? $('input#tmp_name').val() : 'Template');
            ajaxData.image = image[1] ? $(image[1]).data('setting').url : null;

            // CREATE LOADING
            Itwpt_Create_Loading(_this,'small');

            // AJAX
            $.ajax({
                type: "post",
                url: itwpt_localize.ajaxUrl,
                data: ajaxData,
                success: function (resp) {

                    // SET ID
                    _this.attr('data-id', resp);

                    // REMOVE LOADING
                    Itwpt_Remove_Loading(_this);

                    // ALARM
                    console.log(ajaxData.id);
                    if (ajaxData.id !== undefined) {
                        Itwpt_Alarm({
                            type: 'success',
                            text: itwpt_localize.template_updated,
                            ok: function () {},
                            interval: 5000
                        });
                    } else {
                        location.replace(location.href + '&edit=' + resp);
                    }

                },
                error: function () {

                }
            });

        });

        // SELECT TEMPLATE
        $(document).on('click', '.itwpt-field-template.select_enable .item', function () {

            // VARIABLE
            var _this = $(this);
            var _list = _this.parent();
            var _input = _this.parents('.itwpt-fields').find('input.save');

            // CHECK ACTIVE
            if (_this.hasClass('active')) {
                _this.removeClass('active');
            } else {
                _list.find('li').removeClass('active');
                _this.addClass('active');
            }

            // SAVE
            _input.val(_list.find('li.active').data('id'));

        });

        // TEMPLATE DELETE
        $(document).on('click', '.itwpt-field-template .trash', function () {

            // VARIABLES
            var _this = $(this);
            var _parent = $(this).parents('.item');
            var ajaxData =
                {
                    action: "Itwpt_delete_form",
                    nonce: itwpt_localize.nonce,
                    id:_parent.data('id'),
                    table:"itpt_itwpt_templates"
                };

            Itwpt_Alarm({
                type: 'warning',
                text: itwpt_localize.do_you_want_to_remove_template,
                ok: function () {

                    $.ajax({
                        type: "post",
                        url: itwpt_localize.ajaxUrl,
                        data: ajaxData,
                        success: function (resp) {

                            // VARIABLE
                            _parent.remove();
                            Itwpt_Alarm({
                                type: 'success',
                                text: itwpt_localize.template_deleted,
                                ok: function () {},
                                interval: 5000
                            });

                        },
                        error: function () {

                            Itwpt_Alarm({
                                type: 'error',
                                text: itwpt_localize.template_did_not_deleted,
                                ok: function () {},
                                interval: 5000
                            });

                        }
                    });

                },
                cancel:function(){},
            });

        });

        // SAVE GENERAL
        $(document).on('click','.add-header.general button.save',function(){

            // VARIABLES
            var _this = $(this);
            var data = Itwpt_Get_Data();
            var ajaxData =
                {
                    action: "Itwpt_add_option",
                    nonce: itwpt_localize.nonce,
                    name: 'general',
                    data: ''
                };

            ajaxData.data = encodeURI(JSON.stringify(data));

            // CREATE LOADING
            Itwpt_Create_Loading(_this,'small');

            $.ajax({
                type: "post",
                url: itwpt_localize.ajaxUrl,
                data: ajaxData,
                success: function (resp) {

                    // REMOVE LOADING
                    Itwpt_Remove_Loading(_this);

                    // ALARM
                    Itwpt_Alarm({
                        type: 'success',
                        text: itwpt_localize.general_save,
                        ok: function () {},
                        interval: 5000
                    });

                },
                error: function () {

                    // ALARM
                    Itwpt_Alarm({
                        type: 'error',
                        text: itwpt_localize.general_did_not_save,
                        ok: function () {},
                        interval: 5000
                    });

                }
            });

        });

        // PREVIEW
        $(document).on('click','.btn-primary.preview',function(){
            console.log('click p');

            // VARIABLES
            var _this = $(this);
            var data = Itwpt_Get_Data();
            var id = Itwpt_Get_Url_Parameter('edit');
            if (id == undefined) {
                id = _this.data('id');
            }
            var ajaxData =
                {
                    action: "Itwpt_preview",
                    nonce: itwpt_localize.nonce,
                    id:id
                };

            $.ajax({
                type: "post",
                url: itwpt_localize.ajaxUrl,
                data: ajaxData,
                success: function (resp) {

                    $('body,html').attr('style','overflow:hidden');
                    $('body').append('<div class="preview-popup"><div class="preview-popup-header"><div class="heading">Preview</div><i class="icon-itx"></i></div><div class="preview-popup-content">'+resp+'</div></div>');

                },
                error: function () {
                    console.log('no');
                }
            });

        });

        // PREVIEW POPUP CLOSE
        $(document).on('click','.preview-popup-header i',function(){

            // REMOVE POPUP
            $(this).parents('.preview-popup').remove();
            $('body,html').attr('style','');

        });

        // SAVE DATA
        $(document).on('click', '.add-header.shortcode button.save', function (e) {

            // VARIABLES
            var _this = $(this);
            var data = Itwpt_Get_Data();
            var id = Itwpt_Get_Url_Parameter('edit');
            if (id == undefined) {
                id = _this.data('id');
            }
            var ajaxData =
                {
                    action: "Itwpt_save_data_form",
                    nonce: itwpt_localize.nonce,
                    dbAction: _this.hasClass('update') ? 'update' : 'save',
                    id: id,
                    name: ($('input#add_column_shortcode_name').val() !== '' ? $('input#add_column_shortcode_name').val() : '-'),
                    data: ''
                };

            ajaxData.data = encodeURI(JSON.stringify(data));

            // CREATE LOADING
            Itwpt_Create_Loading(_this,'small');

            $.ajax({
                type: "post",
                url: itwpt_localize.ajaxUrl,
                data: ajaxData,
                success: function (resp) {

                    // SET ID
                    _this.attr('data-id', resp);
                    _this.parent().find('.preview').attr('data-id', resp);

                    // REMOVE LOADING
                    Itwpt_Remove_Loading(_this);

                    // ALARM
                    if (_this.hasClass('update')) {
                        Itwpt_Alarm({
                            type: 'success',
                            text: itwpt_localize.shortcode_updated,
                            ok: function () {},
                            interval: 5000
                        });
                    } else {
                        _this.addClass('update');
                        _this.html('<i class="icon-itsave"></i>update');
                        location.replace(location.href+'&edit='+resp);
                        Itwpt_Alarm({
                            type: 'success',
                            text: itwpt_localize.shortcode_saved,
                            ok: function () {},
                            interval: 5000
                        });

                    }

                },
                error: function () {

                    // ALARM
                    if (_this.hasClass('update')) {
                        Itwpt_Alarm({
                            type: 'error',
                            text: itwpt_localize.shortcode_did_not_updated,
                            ok: function () {},
                            interval: 5000
                        });
                    } else {
                        Itwpt_Alarm({
                            type: 'error',
                            text: itwpt_localize.shortcode_did_not_saved,
                            ok: function () {},
                            interval: 5000
                        });
                    }

                }
            });

        });

        // CONTROL CLICK FOR ALL EVENT
        // * SHOE MORE
        $(document).on('click','*',function(e){

            // VARIABLES
            var _this = $(this);

            // SHOW MORE
            if ($('.t-more')[0]) {

                var _tMore = _this.parents('.t-more');

                if (_tMore[0]) {

                    if (_tMore.hasClass('active')) {
                        $('.table-shc .t-more').removeClass('active');
                        _tMore.removeClass('active');
                    } else if (!(_this.parents('.more-popup')[0])) {
                        $('.table-shc .t-more').removeClass('active');
                        _tMore.addClass('active');
                    }

                } else {

                    $('.table-shc .t-more').removeClass('active');
                }
                e.stopPropagation();
            }

        });

        // DELETE IN SHORTCODE LIST
        $(document).on('click', '.table-shc .t-more .delete', function () {

            // VARIABLE
            var _thia = $(this);
            var id = $(this).data('id');
            var ajaxData =
                {
                    action: "Itwpt_delete_form",
                    nonce: itwpt_localize.nonce,
                    table: 'itpt_posts',
                    id: id
                };

            Itwpt_Alarm({
                type: 'warning',
                text: itwpt_localize.do_you_want_to_remove_shortcuts,
                ok: function () {

                    // CREATE LOADING
                    Itwpt_Create_Loading($('body'));

                    $.ajax({
                        type: "post",
                        url: itwpt_localize.ajaxUrl,
                        data: ajaxData,
                        success: function (resp) {

                            // VARIABLE
                            var tbody = _thia.parents('tbody');

                            // REMOVE LOADING
                            Itwpt_Remove_Loading($('body'));

                            // ALARM
                            Itwpt_Alarm({
                                type: 'success',
                                text: itwpt_localize.shortcode_deleted,
                                ok: function(){},
                                interval:5000
                            });

                            // DELETE ROW AND CHECK EMPTY LIST
                            _thia.parents('tr').remove();
                            if (tbody.find('tr').length === 0) {
                                tbody.append(
                                    '<tr>\n' +
                                    '    <td class="empty-list" colspan="6">\n' +
                                    '        This list is empty.\n' +
                                    '    </td>\n' +
                                    '</tr>');
                            }

                        },
                        error: function () {

                            // ALARM
                            Itwpt_Alarm({
                                type: 'error',
                                text: itwpt_localize.shortcode_did_not_deleted,
                                ok: function(){},
                                interval:5000
                            });

                        }
                    });

                },
                cancel: function () {
                    // CANCEL
                }
            });

        });

        // EDIT IN SHORTCODE LIST
        $(document).on('click', '.table-shc .t-more .edit', function () {
            window.location.replace($(this).data('link'));
        });

        // CLONE IN SHORTCODE LIST
        $(document).on('click','.table-shc .t-more .clone',function(){

            // VARIABLE
            var id = $(this).data('id');
            var ajaxData =
                {
                    action: "Itwpt_copy_form",
                    nonce: itwpt_localize.nonce,
                    id: id
                };

            // CREATE LOADING
            Itwpt_Create_Loading($('body'));

            $.ajax({
                type : "post",
                url : itwpt_localize.ajaxUrl,
                data : ajaxData,
                success: function (resp) {

                    // REMOVE LOADING
                    Itwpt_Remove_Loading($('body'));

                    // ALARM
                    Itwpt_Alarm({
                        type: 'success',
                        text: itwpt_localize.shortcode_duplicated,
                        ok: function(){},
                        interval:5000
                    });

                    window.location.reload();

                },
                error: function () {

                    // ALARM
                    Itwpt_Alarm({
                        type: 'success',
                        text: itwpt_localize.shortcode_did_not_duplicated,
                        ok: function(){},
                        interval:5000
                    });

                }
            });

        });

        // CONTROL DEPENDENCY
        $(document).on('change', '.itwpt-fields input.save, .itwpt-fields select.save', function () {
            Itwpt_Analysis_Dependency();
        });

        /**
         * TODO EVENTS FIELDS
         * ALL FIELDS
         */
        // NUMBER SPINNER MOUSE DOWN
        $(document).on('mousedown', '.itwpt-fields .number-spinner i', function () {

            // VARIABLES
            var _this = $(this);
            var input = _this.parents('.number-controler').find('input');
            var val = input.val();
            var max = input.attr('max');
            var min = input.attr('min');

            // SET INTERVAL
            numberInterval = setInterval(function () {

                // CONTROL UP OR DOWN
                if (_this.hasClass('up')) {

                    if (max !== undefined) {

                        if (val < max) {
                            val = Number(val) + 1;
                            input.val(val);
                        }

                    } else {
                        val = Number(val) + 1;
                        input.val(val);
                    }

                } else {

                    if (min !== undefined) {

                        if (val > min) {
                            val = Number(val) - 1;
                            input.val(val);
                        }

                    } else {
                        val = Number(val) - 1;
                        input.val(val);
                    }

                }

            }, 50);

        });

        // NUMBER SPINNER MOUSE UP
        $(document).on('mouseup', '.itwpt-fields .number-spinner i', function () {

            // VARIABLES
            var _this = $(this);
            var input = _this.parents('.number-controler').find('input');

            // SET INTERVAL
            clearInterval(numberInterval);
            input.focus();

        });

        // CHECKBOX AND RADIO
        $(document).on('click', '.itwpt-field-checkbox .option, .itwpt-field-radio .option', function () {

            // VARIABLE
            var _this = $(this);
            var options = _this.parent().find('.option');
            var input = _this.parents('.input').find('input');
            var items = [];

            // CHECK RADIO
            if(_this.parents('.itwpt-fields').hasClass('itwpt-field-radio')){
                options.removeClass('active');
            }

            // CHECK ACTIVE
            if (_this.hasClass('active')) {
                _this.removeClass('active');
            } else {
                _this.addClass('active');
            }

            // SAVE DATA
            $.each(options, function () {
                if ($(this).hasClass('active'))
                    items.push($(this).data('val'));
            });
            input.val(items.join(','));

            // CONTROL DEPENDENCY
            Itwpt_Analysis_Dependency();

        });

        // SAVE DATA MULTI SELECT
        $('.itwpt-fields .multi-select').on('select2:select', function (e) {

            var outData = [];
            var data = $(this).select2('data');
            var input = $(this).parent().find('input[type=hidden]');

            // GET DATA
            $.each(data, function () {
                outData.push(this.id);
            });

            input.val(outData.join(','));

            // CONTROL DEPENDENCY
            Itwpt_Analysis_Dependency();

        });

        $('.itwpt-fields .multi-select').on('change', function (e) {

            var outData = [];
            var data = $(this).select2('data');
            var input = $(this).parent().find('input[type=hidden]');

            // GET DATA
            $.each(data, function () {
                outData.push(this.id);
            });

            input.val(outData.join(','));

            // CONTROL DEPENDENCY
            Itwpt_Analysis_Dependency();

        });

        // CONTROL DEPENDENCY IN ADD COLUMN BOX
        setTimeout(function () {
            $('.column-add-row .select-controler select').trigger('change');
        }, 100);
        $(document).on('change', '.column-add-row .select-controler select', function () {

            var _Parent = $(this).parents('.column-add-row');
            var _Keyword = _Parent.find('.keyword').parents('.item');
            var _Taxonomy = _Parent.find('.taxonomy').parents('.item');

            if ($(this).val() === 'taxonomy') {
                _Keyword.css({'display': 'none'});
                _Taxonomy.css({'display': 'block'});
            } else {
                _Keyword.css({'display': 'block'});
                _Taxonomy.css({'display': 'none'});
            }

        });

        $(document).on('click','.itwpt-field-columns .responsive .remove',function(){

            var _Index = $(this).parents('li').index();
            var DataField = $(this).parents('.input').find('input.save');
            var Data = JSON.parse(DataField.val());
            Data.splice(_Index,1);
            DataField.val(JSON.stringify(Data));
            $(this).parents('li').remove();

        });

        // ADD ROW IN COLUMN FIELD
        $(document).on('click', '.itwpt-field-columns .add-column', function () {

            // VARIABLES
            var _this = $(this);
            var addBox = _this.parents('.column-add-row');
            var columnsList = _this.parents('.input').find('.items');
            var html;
            var lastChild;
            var keyword = addBox.find('.keyword').val();

            if (addBox.find('.taxonomy').parents('.item').css('display') === 'block') {
                keyword = addBox.find('.taxonomy').val();
            }

            // CREATE ROW
            html =
                '<li>\n' +
                '    <div class="item-inner">\n' +
                '        <div>\n' +
                '            <div class="handle"><i class="icon-z move"></i></div>\n' +
                '        </div>\n' +
                '        <div class="input-column">\n' +
                '            <input type="text" id="columns_' + keyword + '" name="columns_' + keyword + '" value="' + addBox.find('.title').val() + '" placeholder="Enter Text" data-val="' + keyword + '" data-type="' + addBox.find('.type').val() + '">\n' +
                '        </div>\n' +
                '        <div class="responsive">\n' +
                '            <div class="remove itwpt-tooltip" data-tooltip-text="Remove Column"><i class="icon-z trash"></i></div>\n' +
                '            <div class="desktop itwpt-tooltip active" data-tooltip-text="Desktop (>768)"><i class="icon-z desktop"></i></div>\n' +
                '            <div class="laptop itwpt-tooltip" data-tooltip-text="Tablet (768 - 576)"><i class="icon-z tablet"></i></div>\n' +
                '            <div class="mobile itwpt-tooltip" data-tooltip-text="Mobile (<576)"><i class="icon-z phone"></i></div>\n' +
                '        </div>\n' +
                '    </div>\n' +
                '</li>';

            columnsList.append(html);
            lastChild = columnsList.find('li').last();
            $('html, body').animate({
                scrollTop: lastChild.offset().top - $(window).height() / 2
            }, 1500);
            $('.itwpt-field-columns .items input').trigger('keyup');

        });

        // CHECK RESPONSIVE IN COLUMN
        $(document).on('click', '.itwpt-field-columns .items .responsive > div', function () {

            if($(this).hasClass('remove')){
                return false;
            }

            // VARIABLES
            var _this = $(this);
            var parent = _this.parent();
            var desktop = parent.find('div.desktop');
            var laptop = parent.find('div.laptop');
            var mobile = parent.find('div.mobile');

            if (_this.hasClass('active')) {
                _this.removeClass('active');
            } else {
                _this.addClass('active');
            }

            if (!desktop.hasClass('active') && !laptop.hasClass('active') && !mobile.hasClass('active')) {
                _this.parents('.item-inner').addClass('disable');
            } else {
                _this.parents('.item-inner').removeClass('disable');
            }

            $('.itwpt-field-columns .items input').trigger('keyup');

        });

        // SAVE DATA WITH CHANGE INPUT VALUE
        $(document).on('keyup', '.itwpt-field-columns .items input', function () {

            // VARIABLES
            var _this = $(this);
            var items = _this.parents('ul').find('li');
            var input = _this.parents('.input').find('input.save');
            var data = [];

            // EACH ON ITEM
            $.each(items, function () {

                // VARIABLES
                var item = $(this).find('input');
                var DataCML = {
                    "type": "",
                    "value": "",
                    "text": "",
                    "placeholder": "",
                    "desktop": "",
                    "laptop": "",
                    "mobile": ""
                };

                DataCML.text = item.val();
                DataCML.value = item.attr('data-val');
                DataCML.type = item.attr('data-type');
                DataCML.placeholder = item.attr('placeholder');
                DataCML.desktop = ($(this).find('div.desktop').hasClass('active') ? "active" : "");
                DataCML.laptop = ($(this).find('div.laptop').hasClass('active') ? "active" : "");
                DataCML.mobile = ($(this).find('div.mobile').hasClass('active') ? "active" : "");

                data.push(DataCML);

            });

            input.val(JSON.stringify(data));

        });

        // MEDIA FIELD
        $(document).on('click','.itwpt-field-media .add-image',function(){

            /** VARIABLES */
            var _this = $(this);
            var multiSelect = _this.data('select');
            var list = _this.parents('.media').find('ul');
            var frame = wp.media({
                multiple: multiSelect
            });

            frame.open(); // OPEN

            /** SELECT IMAGE */
            frame.on('select', function() {

                /** VARIABLE */
                var selection = frame.state().get('selection');

                selection.each(function(attachment) {

                    /** VARIABLE */
                    var data =
                        {
                            "id":attachment.attributes.id,
                            "url":attachment.attributes.url
                        };

                    if(!multiSelect){
                        list.find('.item').remove();
                    }

                    list.append(
                        '<li class="item" style="background-image: url(\'' + data.url + '\')" data-setting=\'' + JSON.stringify(data) + '\'>\n' +
                        '    <div class="more">\n' +
                        '        <i class="icon-z trash"></i>\n' +
                        '    </div>\n' +
                        '</li>'
                    );

                });

                Itwpt_Media_Reset(_this.parents('.itwpt-fields'));

            });

        });

        // DELETE ITEM IN MEDIA
        $(document).on('click','.itwpt-field-media li .trash',function(){

            // VARIABLE
            var elm = $(this).parents('.itwpt-fields');
            $(this).parents('li').remove();
            Itwpt_Media_Reset(elm);

        });

        /**
         * TODO FUNCTIONS
         * -
         */
        // CHANGE COLOR SVG ICON
        function Itwpt_Nav_Icon() {

            // VARIABLES
            var items = $('.add-header .nav-item');

            // EACH ON ITEMS
            $.each(items, function () {

                // VARIABLES
                var item = $(this);
                var actived = item.find('a').hasClass('active');
                var codeBase64 = item.find('.icon').data('base64');
                var decode = jQuery(atob(codeBase64));

                // SET STYLE
                decode.find('path,circle').attr('style', (actived ? 'fill:#222222' : 'fill:#222222'));
                item.find('.icon').attr('style', "background-image:url('data:image/svg+xml;base64," + btoa(decode.prop('outerHTML')) + "')");

            });

        }

        // GET VARIABLE IN URL
        function Itwpt_Get_Url_Parameter(sParam) {
            var sPageURL = window.location.search.substring(1),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
                }
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
            if(typeof options.ok !== "undefined"){
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
                "btn": (button !== '') ? Itwpt_String_Format('<div class="btns">%buttons%</div>', {"buttons": button}) : ''
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

        // ANALYSIS DEPENDENCY
        function Itwpt_Analysis_Dependency(){

            // VARIABLE
            var elms = $('.itwpt-fields.dependency');

            // ANALYSIS
            $.each(elms, function () {
                Itwpt_Dependency($(this));
            });

        }

        // CONTROL DEPENDENCY
        function Itwpt_Dependency(_this) {

            // VARIABLES
            var element = $('.itwpt-field-name-' + _this.data('dependency-elm'));
            var value = _this.data('dependency-value');
            var not = _this.data('dependency-not') == 'true';
            var input = element.find('input.save,select.save');

            // CONTROL PARENT
            if (element.hasClass('dependency')) {

                var p_element = $('.itwpt-field-name-' + element.data('dependency-elm'));
                var p_val = element.data('dependency-value');
                var p_not = element.data('dependency-not') == 'true';
                var p_input = p_element.find('input.save,select.save');

                if (p_not) {

                    if ((p_input.val() == p_val) && (input.val() == value)) {
                        _this.addClass('dependency-enable');
                        _this.removeClass('dependency-disable');
                    } else {
                        _this.removeClass('dependency-enable');
                        _this.addClass('dependency-disable');
                    }

                } else {

                    if ((p_input.val() == p_val) && (input.val() == value)) {
                        _this.removeClass('dependency-enable');
                        _this.addClass('dependency-disable');
                    } else {
                        _this.addClass('dependency-enable');
                        _this.removeClass('dependency-disable');
                    }

                }

            } else {

                if (not) {

                    if (input.val() == value) {
                        _this.addClass('dependency-enable');
                        _this.removeClass('dependency-disable');
                    } else {
                        _this.removeClass('dependency-enable');
                        _this.addClass('dependency-disable');
                    }

                } else {

                    if (input.val() == value) {
                        _this.removeClass('dependency-enable');
                        _this.addClass('dependency-disable');
                    } else {
                        _this.addClass('dependency-enable');
                        _this.removeClass('dependency-disable');
                    }

                }

            }





















        }

        // GET DATA FIELDS
        function Itwpt_Get_Data(){

            // VARIABLES
            var inputs = $('input.save,select.save');
            var data = [];

            // GET ALL INPUT
            $.each(inputs, function () {

                // VARIABLES
                var DataIN = {"name": "", "value": ""};

                DataIN.name = $(this).attr('name');
                DataIN.value = $(this).val();
                data.push(DataIN);

            });

            return data;

        }

        //RESET MEDIA DATA
        function Itwpt_Media_Reset(elm){

            // VARIABLES
            var data = Array();
            var input = elm.find('input.save');

            // GET DATA
            $.each(elm.find('li.item'),function(){

                data.push($(this).data('setting'));

            });

            // SAVE IN INPUT
            input.val(JSON.stringify(data));

        }

        // FUNCTION CREATE LOADING
        function Itwpt_Create_Loading(elm,size = 'medium'){
            elm.append('<div class="itwpt-loading '+size+'"><img src="'+itwpt_localize.loadingUrl+'"></div>');
        }

        // FUNCTION REMOVE LOADING
        function Itwpt_Remove_Loading(elm){
            elm.find('.itwpt-loading').remove();
        }

        $(document).on('click','.clone-btn',function () {
            var id = $(this).data("id");
            var $temp = jQuery("<input>");
            jQuery("body").append($temp);
            $temp.val('[it_woo_product_table id="'+id+'"]').select();
            document.execCommand("copy");
            Itwpt_Alarm({
                type: 'success',
                text: itwpt_localize.shortcode_copy,
                ok: function () {
                },
                interval: 5000
            });
            $temp.remove();
        });

    });

})(jQuery);