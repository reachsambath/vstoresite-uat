<?php
function Itwpt_Shortcode_bf($atts)
{

    return $atts;
}

function Itwpt_Shortcode($atts)
{

	// SHORTCODE ATTRIBUTES
	$a = shortcode_atts(array(
		'id' => null,
		'args' => null,
	), $atts);
		
		//$a['args']=explode(',',$a['args']);
		//print_r($a);


	// GLOBAL VARIABLE
	global $itwpt_data;

	// DATA TABLE
	$data = array();
	$temp_data = json_decode(urldecode(Itwpt_Get_Data_Table('itpt_posts', array('id' => $a['id']))[0]->data));

	// CONTROL EXIST
	if (empty($temp_data)) {
		return esc_html__('iThemelandCo Woo Product Table Message: Can not found shortcode', PREFIX_ITWPT_TEXTDOMAIN);
	}

	Itwpt_Object_To_Array($temp_data, $temp_data);
	if (isset($temp_data)) {
		foreach ($temp_data as $m_data) {
			$data[$m_data['name']] = $m_data['value'];
		}
	}
    $itwpt_data = $data;

	$localize_aavalue =
		array(
			'add_localization_sticky_label'                         => esc_html($data['add_localization_sticky_label']),
			'add_localization_add_to_cart_text'                     => esc_html($data['add_localization_add_to_cart_text']),
			'add_localization_add_to_cart_added_text'               => esc_html($data['add_localization_add_to_cart_added_text']),
			'add_localization_add_to_cart_adding_text'              => esc_html($data['add_localization_add_to_cart_adding_text']),
			'add_localization_add_to_cart_selected_text'            => esc_html($data['add_localization_add_to_cart_selected_text']),
			'add_localization_all_check_uncheck_text'               => esc_html($data['add_localization_all_check_uncheck_text']),
			'add_localization_product_not_founded_text'             => esc_html($data['add_localization_product_not_founded_text']),
			'add_localization_load_more_text'                       => esc_html($data['add_localization_load_more_text']),
			'add_localization_search_text'                          => esc_html($data['add_localization_search_text']),
			'add_localization_search_keyword_text'                  => esc_html($data['add_localization_search_keyword_text']),
			'add_localization_loading_button_text'                  => esc_html($data['add_localization_loading_button_text']),
			'add_localization_item_singular_text'                   => esc_html($data['add_localization_item_singular_text']),
			'add_localization_item_plural_text'                     => esc_html($data['add_localization_item_plural_text']),
			'add_localization_search_box_order_bay_text'            => esc_html($data['add_localization_search_box_order_bay_text']),
			'add_localization_search_box_order_text'                => esc_html($data['add_localization_search_box_order_text']),
			'add_localization_in_stock_text'                        => esc_html($data['add_localization_in_stock_text']),
			'add_localization_out_of_stock_text'                    => esc_html($data['add_localization_out_of_stock_text']),
			'add_localization_on_back_order_text'                   => esc_html($data['add_localization_on_back_order_text']),
			'add_localization_variation_not_available_text'         => esc_html($data['add_localization_variation_not_available_text']),
			'add_localization_variation_is_not_set_text'            => esc_html($data['add_localization_variation_is_not_set_text']),
			'add_localization_select_all_item_text'                 => esc_html($data['add_localization_select_all_item_text']),
			'add_localization_out_of_stock_message_text'            => esc_html($data['add_localization_out_of_stock_message_text']),
			'add_localization_is_no_more_products_text'             => esc_html($data['add_localization_is_no_more_products_text']),
			'add_localization_no_right_combination_text'            => esc_html($data['add_localization_no_right_combination_text']),
			'add_localization_please_choose_right_combination_text' => esc_html($data['add_localization_please_choose_right_combination_text']),
			'add_localization_type_your_message_text'               => esc_html($data['add_localization_type_your_message_text']),
			'add_localization_yith_quick_view'                      => (isset($data['add_localization_yith_quick_view']) ? esc_html($data['add_localization_yith_quick_view']) : esc_html__('Quick View', PREFIX_ITWPT_TEXTDOMAIN)),
			'add_localization_yith_wish_list'                       => (isset($data['add_localization_yith_wish_list']) ? esc_html($data['add_localization_yith_wish_list']) : esc_html__('Wish List', PREFIX_ITWPT_TEXTDOMAIN)),
			'add_localization_yith_quote_list'                      => (isset($data['add_localization_yith_quote_list']) ? esc_html($data['add_localization_yith_quote_list']) : esc_html__('Quote List', PREFIX_ITWPT_TEXTDOMAIN)),
			'add_localization_yith_quote_button'                    => (isset($data['add_localization_yith_quote_button']) ? esc_html($data['add_localization_yith_quote_button']) : esc_html__('Quote Button', PREFIX_ITWPT_TEXTDOMAIN)),
			'add_localization_yith_adding'                          => (isset($data['add_localization_yith_adding']) ? esc_html($data['add_localization_yith_adding']) : esc_html__('Adding', PREFIX_ITWPT_TEXTDOMAIN)),
			'add_localization_yith_added'                           => (isset($data['add_localization_yith_added']) ? esc_html($data['add_localization_yith_added']) : esc_html__('Added', PREFIX_ITWPT_TEXTDOMAIN)),
			'add_localization_cart_empty'                           => esc_html($data['add_localization_cart_empty']),
			'add_localization_cart_update'                          => esc_html($data['add_localization_cart_update']),
			'add_localization_can_not_cart_update'                  => esc_html($data['add_localization_can_not_cart_update']),
			'add_localization_product_added'                        => esc_html($data['add_localization_product_added']),
			'add_localization_cart_clear'                           => esc_html($data['add_localization_cart_clear']),
			'add_localization_product_deleted'                      => esc_html($data['add_localization_product_deleted']),
			'add_localization_product_deleted_error'                => esc_html($data['add_localization_product_deleted_error']),
			'add_localization_can_not_product_added'                => esc_html($data['add_localization_can_not_product_added']),
		);
	wp_enqueue_script(PREFIX_ITWPT . '_front_script', PREFIX_ITWPT_JS_URL . 'front/script.js', array(), '1.0.0'); // FRONT SCRIPT
	wp_localize_script(PREFIX_ITWPT . '_front_script', PREFIX_ITWPT . '_localization', $localize_aavalue);

	$itwpt_localize_data = $data;
	wp_localize_script(PREFIX_ITWPT . '_front_script', PREFIX_ITWPT . '_localize_data', $itwpt_localize_data);

	/**
	 * TODO QUERY
	 **/
	Itwpt_Data_query($data); // SET DATA GLOBAL VARIABLE
	$query = Itwpt_Query_Front($a['args']);

	/**
	 * TODO OUTPUT
	 **/
	// VARIABLES
	$itwptRand = rand(10, 999999);
	$itwptCounterColumn = 1;
	$itwptDesktop = '';
	$itwptLaptop = '';
    $itwptMobile = '';
    $itwptMobileBlock = '';
	$itwptThead = '';
	$itwptRows = '';
	$itwptScript = '';
	$itwptStyle = '';
	$itwptTableHeader = '';
	$itwptTableFooter = '';
	$itwptEnd = '';
	$exit = '';
	$cls = '';
	$ajax_action = 1;

	/**
	 * TODO FUNCTIONS
	 **/
	// THEAD TABLE
	$itwptThead .= '<tr>';
	if (!empty($data['add_column_table_column'])) {
		$column = json_decode($data['add_column_table_column']);
		foreach ($column as $cl) {

			if (!empty($cl->desktop) || !empty($cl->laptop) || !empty($cl->mobile)) {
				$sort_class = '';
				if (!in_array($cl->value, array('thumbnails', 'quantity', 'short-message', 'variations', 'action', 'check'))) {
					$sort_class = 'column-sort';
				}
				$itwptThead .= '<th class="' . esc_attr($cl->value) . '_tbl_column ' . esc_attr($cl->value) . '_custom_column ' . esc_attr($sort_class) . '">' . esc_attr($cl->text) . '</th>';
			}
			if (!empty($cl->desktop) || !empty($cl->laptop) || !empty($cl->mobile)) {

				$column_index = 0;
                $column_index = $itwptCounterColumn;

				$itwptDesktop .= '#table' . esc_attr($itwptRand) . ' th:nth-child(' . esc_attr($column_index) . '){display:' . esc_attr(!empty($cl->desktop) ? 'table-cell' : 'none') . '}' . '#table' . esc_attr($itwptRand) . ' td:nth-child(' . esc_attr($column_index) . '){display:' . esc_attr(!empty($cl->desktop) ? 'table-cell' : 'none') . '}';
				$itwptLaptop .= '#table' . esc_attr($itwptRand) . ' th:nth-child(' . esc_attr($column_index) . '){display:' . esc_attr(!empty($cl->laptop) ? 'table-cell' : 'none') . '}' . '#table' . esc_attr($itwptRand) . ' td:nth-child(' . esc_attr($column_index) . '){display:' . esc_attr(!empty($cl->laptop) ? 'table-cell' : 'none') . '}';
                $itwptMobile .= '#table' . esc_attr($itwptRand) . ' th:nth-child(' . esc_attr($column_index) . '){display:' . esc_attr(!empty($cl->mobile) ? 'table-cell' : 'none') . '}' . '#table' . esc_attr($itwptRand) . ' td:nth-child(' . esc_attr($column_index) . '){display:' . esc_attr(!empty($cl->mobile) ? 'table-cell' : 'none') . '}';
                
                if(!empty($cl->mobile)){
                    $itwptMobileBlock .= '#table' . esc_attr($itwptRand) . ' td:nth-child(' . esc_attr($column_index) . '){display:block !important;}';
                }

				$itwptCounterColumn++;
			}
		}
	}
	$itwptThead .= '</tr>';





	// ROW TABLE
	$itwptRows = Itwpt_Create_Row($query, $itwptRand);
	if (empty($itwptRows)) {
		$itwptRows = '<tr><td colspan="999999" style="text-align:center;">' . esc_html($data['add_localization_product_not_founded_text']) . '</td></tr>';
	}
	global $itwpt_query_data;

	// PAGINATION
	$pagination_html = Itwpt_Pagination($query);
	if (!empty($pagination_html)) {

		if ($data['add_search_filter_pagination'] === 'pagination') {
			$itwptTableFooter .= '<div class="itwpt-pagination"><div class="itwpt-pagination-content" data-current-page="1" data-page="1">' . sprintf($pagination_html) . '</div></div>';
		} else {
			$itwptTableFooter .= '<div class="itwpt-load-more"><div class="itwpt-load-more-content" data-current-page="1" data-page="1" data-loading-text="' . esc_html($data['add_localization_loading_button_text']) . '">' . esc_html($data['add_localization_load_more_text']) . '</div></div>';
		}
	}

	// CART
	if (!empty($data['add_checklist_mini_cart_position'])) {

		$cart_out = '';
		$position = explode(',', $data['add_checklist_mini_cart_position']);

		foreach ($position as $psn) {

			if ($psn === 'tbl_header' || $psn === 'tbl_footer') {
				$cart_out = Itwpt_String_Format(
					'<div class="itwpt-cart itwpt-cart-%rand% layout-table">
                            <div class="itwpt-cart-items">
                                %items%
                            </div>
                            <div class="itwpt-cart-footer">
                                <div style="float:left;">
                                    <div class="subtotal">%subtotal%</div>
                                    <div class="qty">%qty%</div>
                                </div>
                                <div style="float:right;">
                                    <div class="btn-delete itwpt-button with-icon"><i class="icon-ittrash"></i>' . esc_html($data['add_localization_cart_clear']) . '</div>
                                    <div class="btn-checkout itwpt-button with-icon"><a href="%checkout%"><i class="icon-itcart"></i>' . esc_html($data['add_localization_cart_checkout']) . '</a></div>
                                    <div class="btn-view itwpt-button with-icon"><a href="%viewcart%"><i class="icon-itpreview"></i>' . esc_html($data['add_localization_cart_view_cart']) . '</a></div>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>',
					array(
						'rand'     => $itwptRand,
						'items'    => Itwpt_Get_Product_Cart(),
						'subtotal' => $data['add_localization_cart_subtotal'] . ': ' . Itwpt_Get_Total_Price_Cart(),
						'qty'      => '<span>' . Itwpt_Get_Total_Quantity_Cart() . '</span> ' . $data['add_localization_cart_item_number'],
						'checkout' => wc_get_checkout_url(),
						'viewcart' => wc_get_cart_url(),
					)
				);
			} else {
				global $itwpt_svg;
				$cart_out = '';
				$exit .= Itwpt_String_Format(
					'<div class="itwpt-cart itwpt-cart-%rand% %layout%" style="display: none;">
                            <div class="itwpt-cart-button-fixed">
                                <div class="itwpt-cart-button-fixed-content">
                                    <div class="subtotal">%subtotalcart%</div>
                                    <div class="status-button"><div class="open">' . esc_html($data['add_localization_fix_button_open_text']) . '</div><div class="close">' . esc_html($data['add_localization_fix_button_close_text']) . '</div></div>
                                </div>
                            </div>
                            ' . ($psn == 'side' ? '<div class="itwpt-cart-overly"></div>' : '') . '
                            <div class="itwpt-cart-popup">
                                <div class="itwpt-cart-header">
                                    <div style="float:left;"><i class="icon-itcart"></i>%textheader%</div>
                                    <div style="float:right;"><div class="btn-delete itwpt-button with-icon"><i class="icon-ittrash"></i>' . esc_html($data['add_localization_cart_clear']) . '</div></div>
                                    <div class="clear"></div>
                                </div>
                                <div class="itwpt-cart-items">
                                    %items%
                                </div>
                                <div class="itwpt-cart-footer">
                                    <div class="subtotal">%subtotal%</div>
                                    <div class="buttons">                                    
                                        <div class="btn-checkout itwpt-button with-icon"><a href="%checkout%"><i class="icon-itcart"></i>' . esc_html($data['add_localization_cart_checkout']) . '</a></div>
                                        <div class="btn-view itwpt-button with-icon"><a href="%viewcart%"><i class="icon-itpreview"></i>' . esc_html($data['add_localization_cart_view_cart']) . '<div class="qty">%qty%</div></a></div>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                ' . ($psn == 'float' ? '<div class="arrow">' . sprintf("%s", $itwpt_svg['fls_popup.svg']) . '</div>' : '<div class="close">' . sprintf("%s", $itwpt_svg['close_popup.svg']) . '</div>') . '
                            </div>
                        </div>',
					array(
						'layout'       => $psn == 'float' ? 'layout-fixed' : 'layout-side',
						'rand'         => $itwptRand,
						'textheader'   => $data['add_localization_cart_label'],
						'items'        => Itwpt_Get_Product_Cart(),
						'subtotal'     => $data['add_localization_cart_subtotal'] . ': ' . Itwpt_Get_Total_Price_Cart(),
						'subtotalcart' => Itwpt_Get_Total_Price_Cart(),
						'qty'          => '<span>' . Itwpt_Get_Total_Quantity_Cart() . '</span> ' . esc_html($data['add_localization_cart_item_number']),
						'checkout'     => wc_get_checkout_url(),
						'viewcart'     => wc_get_cart_url(),
					)
				);
			}

			if ($psn === 'tbl_header') {
				$itwptTableHeader .= $cart_out;
			} else {
				$itwptTableFooter .= $cart_out;
			}
		}
	}

	// SCRIPTS
	$header_footer_controls = explode(',', $data['add_checklist_column_hide_header_footer']);

    $cls .= '';

	// STYLE
	$itwptStyle .=
		'@media screen and (min-width:768px){' . sprintf($itwptDesktop) . '}
        @media screen and (max-width:768px){' . sprintf($itwptLaptop) . '}
        @media screen and (max-width:576px){' . sprintf($itwptMobile).
            (empty($itwptMobileBlock)? '':'
            .itwpt-table table,
            .itwpt-table table tbody,
            .itwpt-table table tr{
                display:block !important;
                width:100%;
            }
            .itwpt-table table td{
                width: 100%;
                text-align:left;
            }
            .itwpt-table table thead,
            .mCSB_scrollTools,
            .itwpt-table table tfoot{
                display:none !important;
            }
            '.sprintf($itwptMobileBlock)
            ) . '}';

	if (!empty($data['add_template_template'])) {
		$template_data = array();

		$template = Itwpt_Get_Data_Table('itpt_itwpt_templates', array('id' => $data['add_template_template']));
		$template_json = json_decode(urldecode($template[0]->data));

		foreach ($template_json as $tmp) {
			$template_data[$tmp->name] = $tmp->value;
		}

		$itwptStyle .=
			'.itwpt-cart-' . esc_attr($itwptRand) . ',
            .itwpt-cart-' . esc_attr($itwptRand) . ' .itwpt-cart-popup{
                background-color:' . sprintf($template_data['cart_background_color']) . ' !important;
            }
            .itwpt-cart-' . esc_attr($itwptRand) . ' .close svg path:nth-child(1),
            .itwpt-cart-' . esc_attr($itwptRand) . ' .arrow svg path{
                fill:' . sprintf($template_data['cart_background_color']) . ' !important;
            }
            .itwpt-cart-' . esc_attr($itwptRand) . ' .title,
            .itwpt-cart-' . esc_attr($itwptRand) . ' .itwpt-cart-header div,
            .itwpt-cart-' . esc_attr($itwptRand) . ' .subtotal,
            .itwpt-cart-' . esc_attr($itwptRand) . ' .itwpt-cart-empty,
            .itwpt-cart-' . esc_attr($itwptRand) . ' .icon-itx{
                color:' . sprintf($template_data['cart_title_color']) . ' !important;
            }
            .itwpt-cart-' . esc_attr($itwptRand) . '.layout-side .close path:last-child{
                fill:' . sprintf($template_data['cart_title_color']) . ' !important;
            }
            .itwpt-cart-' . esc_attr($itwptRand) . ' .quantity,
            .itwpt-cart-' . esc_attr($itwptRand) . ' .quantity .amount,
            .itwpt-cart-' . esc_attr($itwptRand) . ' .qty,
            .itwpt-cart-' . esc_attr($itwptRand) . ' .itwpt-short-msg-show,
            .itwpt-cart-' . esc_attr($itwptRand) . ' .subtotal span{
                color:' . sprintf($template_data['cart_meta_color']) . ' !important;
            }
            .itwpt-cart-' . esc_attr($itwptRand) . ' .itwpt-button{
                background-color:' . sprintf($template_data['cart_button_background_color']) . ';
            }
            .itwpt-cart-' . esc_attr($itwptRand) . ' .itwpt-button,
            .itwpt-cart-' . esc_attr($itwptRand) . ' .itwpt-button a{
                color:' . sprintf($template_data['cart_button_text_color']) . ' !important;
            }
            .itwpt-cart-' . esc_attr($itwptRand) . ' .itwpt-cart-button-fixed,
            .itwpt-cart-' . esc_attr($itwptRand) . ' .itwpt-cart-button-fixed:before{
                background-color:' . sprintf($template_data['cart_button_fixed_background_color']) . ' !important;
            }
            .itwpt-cart-' . esc_attr($itwptRand) . ' .itwpt-cart-button-fixed,
            .itwpt-cart-' . esc_attr($itwptRand) . ' .itwpt-cart-button-fixed *,
            .itwpt-cart-' . esc_attr($itwptRand) . ' .itwpt-cart-button-fixed .subtotal span{
                color:' . sprintf($template_data['cart_button_fixed_text_color']) . ' !important;
            }
            .alarm-box .alarm.alarm-success{
                background-color:' . sprintf($template_data['alarm_success_background_color']) . ' !important;
                color:' . sprintf($template_data['alarm_success_text_color']) . ' !important;
            }
            .alarm-box .alarm.alarm-error{
                background-color:' . sprintf($template_data['alarm_error_background_color']) . ' !important;
                color:' . sprintf($template_data['alarm_error_text_color']) . ' !important;
            }
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .itwpt-controls .itwpt-search,
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .itwpt-controls .itwpt-search input,
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .itwpt-controls .itwpt-search-option,
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .itwpt-controls .itwpt-search-option input,
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .itwpt-controls .itwpt-search-option select,
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .itwpt-controls .itwpt-select-all,
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .itwpt-controls .itwpt-search-btn,
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .itwpt-controls .itwpt-search-reset-btn,
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .itwpt-table .dataTables_length select{
                background-color:' . sprintf($template_data['crl_box_background_boxs']) . ' ;
                color:' . sprintf($template_data['crl_box_text_color_boxs']) . ' ;
            }
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .itwpt-controls .itwpt-button{
                background-color:' . sprintf($template_data['crl_box_text_background_btn']) . ' ;
                color:' . sprintf($template_data['crl_box_text_text_color_btn']) . ' ;
                transition:all 0.15s ease-in-out;
            }
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .itwpt-controls .itwpt-button:hover{
                background-color:' . sprintf($template_data['crl_box_text_background_btn_hover']) . ' ;
                color:' . sprintf($template_data['crl_box_text_text_color_btn_hover']) . ' ;
            }
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' thead th,
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' tfoot th{
                background-color:' . sprintf($template_data['header_footer_background_color']) . ' ;
                color:' . sprintf($template_data['header_footer_text_color']) . ' ;
                border-bottom:0' . esc_attr($template_data['header_footer_border_width']) . 'px solid ' . sprintf($template_data['header_footer_border_color']) . ' !important;
                padding-top:0' . esc_attr($template_data['header_footer_padding']) . 'px ;
                padding-bottom:0' . esc_attr($template_data['header_footer_padding']) . 'px ;
                text-align:' . esc_attr($template_data['header_footer_text_alignment']) . ';
                text-transform:' . esc_attr($template_data['header_footer_text_transform']) . ';
                font-size:0' . esc_attr($template_data['header_footer_font_size']) . 'px ;
            }
            #table' . esc_attr($itwptRand) . ' td{
                background-color:' . sprintf($template_data['body_background_color']) . ';
                border-bottom:0' . esc_attr($template_data['body_td_border_width']) . 'px solid ' . sprintf($template_data['body_border_color']) . ' !important;
                color:' . sprintf($template_data['body_text_color']) . ';
                padding-top:0' . esc_attr($template_data['body_td_padding']) . 'px;
                padding-bottom:0' . esc_attr($template_data['body_td_padding']) . 'px;
                transition:all 0.15s ease-in-out;
            }
            #table' . esc_attr($itwptRand) . ' tr:hover td{
                background-color:' . sprintf($template_data['body_hover_background_color']) . ';
            }
            #table' . esc_attr($itwptRand) . ' td a{
                color:' . sprintf($template_data['body_link_color']) . ' !important;
            }
            #table' . esc_attr($itwptRand) . ' td a:hover{
                color:' . sprintf($template_data['body_hover_link_color']) . ' !important;
            }
            #table' . esc_attr($itwptRand) . ' tr:nth-child(2n+0) td{
                background-color:' . sprintf($template_data['body_strip_background_color']) . ';
                color:' . sprintf($template_data['body_strip_text_color']) . ';
            }
            #table' . esc_attr($itwptRand) . ' tr:nth-child(2n+0):hover td{
                background-color:' . sprintf($template_data['body_strip_background_hover_color']) . ';
            }
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' tr th.sorted,
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' tr td.sorted{
                background-color:' . sprintf($template_data['body_sorted_column_bg_color']) . ' !important;
            }
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .itwpt-checkbox-selector{
                background-color:' . sprintf($template_data['checkbox_color']) . ';
                box-shadow: 0 0 0 1px ' . sprintf($template_data['checkbox_border_color']) . ';
            }
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .itwpt-checkbox-selector i{
                background-color:' . sprintf($template_data['checkbox_sign_color']) . ' !important;
                color: 0 0 0 1px ' . sprintf($template_data['checkbox_color']) . ' !important;
            }
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .add-to-cart.itwpt-button,
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .add-to-cart-link.itwpt-button,
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .view-cart i,
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .view-cart i{
                background-color:' . sprintf($template_data['button_add_to_cart_background_color']) . ' !important;
                color:' . sprintf($template_data['button_add_to_cart_text_color']) . ' !important;
            }
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .add-to-cart.itwpt-button:hover,
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .add-to-cart-link.itwpt-button:hover,
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .view-cart i:hover,
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .view-cart i:hover{
                background-color:' . sprintf($template_data['button_add_to_cart_hover_background_color']) . ' !important;
                color:' . sprintf($template_data['button_add_to_cart_hover_text_color']) . ' !important;
            }
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .itwpt-variation .itwpt-variation-btn{
                background-color:' . sprintf($template_data['variation_button_background_color']) . ';
                color:' . sprintf($template_data['variation_button_text_color']) . ';
            }
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .itwpt-variation .itwpt-variation-btn:hover{
                background-color:' . sprintf($template_data['variation_button_hover_background_color']) . ';
                color:' . sprintf($template_data['variation_button_hover_text_color']) . ';
            }
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' a.button,
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' a.button *,
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .add_to_wishlist,
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .add_to_wishlist *{
                background-color:' . sprintf($template_data['other_background_color']) . ' !important;
                color:' . sprintf($template_data['other_text_color']) . ' !important;
            }
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' a.button:hover,
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' a.button:hover *,
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .add_to_wishlist:hover,
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .add_to_wishlist:hover *{
                background-color:' . $template_data['other_hover_background_color'] . ' !important;
                color:' . sprintf($template_data['other_hover_text_color']) . ' !important;
            }
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .variation-popup{
                background-color:' . sprintf($template_data['variation_popup_background_color']) . ';
                color:' . sprintf($template_data['variation_popup_text_color']) . ';
            }
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .variation-popup .arrow svg path{
                fill:' . sprintf($template_data['variation_popup_background_color']) . ' !important;
            }
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .variation-popup select{
                background-color:transparent;
                box-shadow:0 0 0 1px ' . sprintf($template_data['variation_popup_select_border_color']) . ';
                color:' . sprintf($template_data['variation_popup_select_text_color']) . ';
            }
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .variation-popup .itwpt-button{
                background-color:' . sprintf($template_data['variation_popup_button_background_color']) . ';
                color:' . sprintf($template_data['variation_popup_button_text_color']) . ';
            }
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .variation-popup .itwpt-button:hover{
                background-color:' . sprintf($template_data['variation_popup_button_background_color_hover']) . ';
                color:' . sprintf($template_data['variation_popup_button_text_color_hover']) . ';
            }
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .itwpt-search-options .itwpt-search-option{
                width:calc(calc(100% / ' . esc_attr($template_data['ads_column_size_fields']) . ') - 5px);
                margin-right:5px;
            }
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .itwpt-search-options .itwpt-search-option:nth-child(' . esc_attr($template_data['ads_column_size_fields']) . 'n+0){
                margin-left:5px;
                margin-right:0;
            }
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .itwpt-quantity input,
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .itwpt-quantity svg path{
                background-color:' . $template_data['other_qty_background_color'] . ' !important;
                fill:' . sprintf($template_data['other_qty_background_color']) . ';
                color:' . sprintf($template_data['other_qty_text_color']) . ';
            }
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .itwpt-msg-in-stock{
                color:' . sprintf($template_data['other_in_stock_color']) . ';
            }
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .itwpt-msg-out-of-stock{
                color:' . sprintf($template_data['other_out_stock_color']) . ';
            }
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .itwpt-img{
                border-radius:' . ($template_data['other_thum_shape'] == 'square' ? '0px' : ($template_data['other_thum_shape'] == 'q' ? '5px' : '999px')) . ';
                width:' . esc_attr($template_data['other_thumbs_image_size']) . 'px;
                height:' . esc_attr($template_data['other_thumbs_image_size']) . 'px;
            }
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .itwpt-pagination .itwpt-pagination-content{
                background-color:' . $template_data['tmp_pagination_background_color'] . ';
                color:' . sprintf($template_data['tmp_pagination_text_color']) . ';
            }
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .itwpt-pagination a{
                color:' . sprintf($template_data['tmp_pagination_text_color']) . ';
            }
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .itwpt-pagination span.current{
                color:' . sprintf($template_data['tmp_pagination_active_text_color']) . ';
            }
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .itwpt-pagination span.current:before{
                background-color:' . sprintf($template_data['tmp_pagination_active_background_color']) . ';
            }
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .itwpt-load-more .itwpt-load-more-content{
                background-color:' . sprintf($template_data['tmp_pagination_background_color']) . ';
                color:' . sprintf($template_data['tmp_pagination_text_color']) . ';
            }
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .itwpt-load-more .itwpt-load-more-content:before,
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .itwpt-load-more .itwpt-load-more-content:after{
                background-color:' . sprintf($template_data['tmp_pagination_second_color']) . ';
            }
            .itwpt-table-base.a' . esc_attr($itwptRand) . ',
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .itwpt-footer,
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .itwpt-header{
                filter:drop-shadow(0 0 23px ' . sprintf($template_data['other_out_shadow_color']) . ');
            }
            .itwpt-table-base.a' . esc_attr($itwptRand) . '  span.current:before, .itwpt-pagination-content a.current:before{
                box-shadow:0 0 18px ' . sprintf($template_data['other_out_shadow_color']) . ';
            }
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .variation-popup{
                box-shadow:0 0 23px ' . sprintf($template_data['other_out_shadow_color']) . ';
            }
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' .itwpt-msg-back-order{
                color:' . sprintf($template_data['other_back_order_color']) . ';
            }
            .itwpt-cart-' . esc_attr($itwptRand) . ' .itwpt-cart-button-fixed{
                width:' . esc_attr($data['add_settings_footer_cart_size']) . 'px !important;
                height:' . esc_attr($data['add_settings_footer_cart_size']) . 'px !important;
            }
            .itwpt-table-base.a' . esc_attr($itwptRand) . ' tfoot th{border-bottom: none !important;}';

		// FLOATING CART
		switch ($data['add_settings_footer_cart_position']) {
			case 'bottom-right':
				$itwptStyle .=
					'.itwpt-cart-' . esc_attr($itwptRand) . '.layout-fixed .itwpt-cart-button-fixed{top:auto !important;right:50px !important;bottom:50px !important;left:auto !important;}
                    .itwpt-cart-' . esc_attr($itwptRand) . '.layout-fixed .itwpt-cart-popup{bottom:' . esc_attr((!empty($data['add_settings_footer_cart_size']) ? $data['add_settings_footer_cart_size'] : 100) + 90) . 'px !important;right:50px !important;top:auto !important;left:auto !important;}';
				break;
			case 'bottom-left':
				$itwptStyle .=
					'.itwpt-cart-' . esc_attr($itwptRand) . '.layout-fixed .itwpt-cart-button-fixed{top:auto !important;right:auto !important;bottom:50px !important;left:50px !important;}
                    .itwpt-cart-' . esc_attr($itwptRand) . '.layout-fixed .itwpt-cart-popup{bottom:' . esc_attr((!empty($data['add_settings_footer_cart_size']) ? $data['add_settings_footer_cart_size'] : 100) + 90) . 'px !important;left:50px !important;right:auto !important;top:auto !important;}
                    .itwpt-cart-' . esc_attr($itwptRand) . '.layout-fixed .itwpt-cart-popup .arrow{left:27px !important; right:auto !important;}';
				break;
			case 'top-right':
				$itwptStyle .=
					'.itwpt-cart-' . esc_attr($itwptRand) . '.layout-fixed .itwpt-cart-button-fixed{top:50px !important;right:50px !important;bottom:auto !important;left:auto !important;}
                    .itwpt-cart-' . esc_attr($itwptRand) . '.layout-fixed .itwpt-cart-popup{top:' . esc_attr((!empty($data['add_settings_footer_cart_size']) ? $data['add_settings_footer_cart_size'] : 100) + 90) . 'px !important;right:50px !important;left:auto !important;bottom:auto !important;}
                    .itwpt-cart-' . esc_attr($itwptRand) . '.layout-fixed .itwpt-cart-popup .arrow{right:27px !important; left:auto !important; top:auto !important; bottom:100% !important; transform: rotate(180deg);}';
				break;
			case 'top-left':
				$itwptStyle .=
					'.itwpt-cart-' . esc_attr($itwptRand) . '.layout-fixed .itwpt-cart-button-fixed{top:50px !important;right:auto !important;bottom:auto !important;left:50px !important;}
                    .itwpt-cart-' . esc_attr($itwptRand) . '.layout-fixed .itwpt-cart-popup{top:' . esc_attr(((!empty($data['add_settings_footer_cart_size']) ? $data['add_settings_footer_cart_size'] : 100) + 90)) . 'px !important;left:50px !important;right:auto !important;bottom:auto !important;}
                    .itwpt-cart-' . esc_attr($itwptRand) . '.layout-fixed .itwpt-cart-popup .arrow{left:27px !important; right:auto !important; top:auto !important; bottom:100% !important; transform: rotate(180deg);}';
				break;
		}

		// CONTROL ICON ADD TO CART
		if ($template_data['button_add_to_cart_icon'] === 'no-icon') {
			$itwptStyle .=
				'.itwpt-table-base.a' . esc_attr($itwptRand) . ' .add-to-cart.itwpt-button .itwpt-left-icon,
                .itwpt-table-base.a' . esc_attr($itwptRand) . ' .add-to-cart-link.itwpt-button .itwpt-left-icon,
                .itwpt-table-base.a' . esc_attr($itwptRand) . ' .add-to-cart.itwpt-button .itwpt-right-icon,
                .itwpt-table-base.a' . esc_attr($itwptRand) . ' .add-to-cart-link.itwpt-button .itwpt-right-icon{
                    display:none;
                }';
		} else if ($template_data['button_add_to_cart_icon'] === 'only-icon') {
			$itwptStyle .=
				'.itwpt-table-base.a' . esc_attr($itwptRand) . ' .add-to-cart.itwpt-button .itwpt-right-icon,
                .itwpt-table-base.a' . esc_attr($itwptRand) . ' .add-to-cart-link.itwpt-button .itwpt-right-icon,
                .itwpt-table-base.a' . esc_attr($itwptRand) . ' .add-to-cart.itwpt-button span,
                .itwpt-table-base.a' . esc_attr($itwptRand) . ' .add-to-cart-link.itwpt-button span{
                    display:none;
                }
                .itwpt-table-base.a' . esc_attr($itwptRand) . ' .add-to-cart.itwpt-button .itwpt-left-icon,
                .itwpt-table-base.a' . esc_attr($itwptRand) . ' .add-to-cart-link.itwpt-button .itwpt-left-icon{
                    display:inline-block;
                }';
		} else if ($template_data['button_add_to_cart_icon'] === 'left-icon') {
			$itwptStyle .=
				'.itwpt-table-base.a' . esc_attr($itwptRand) . ' .add-to-cart.itwpt-button .itwpt-right-icon,
                .itwpt-table-base.a' . esc_attr($itwptRand) . ' .add-to-cart-link.itwpt-button .itwpt-right-icon{
                    display:none;
                }
                .itwpt-table-base.a' . esc_attr($itwptRand) . ' .add-to-cart.itwpt-button .itwpt-left-icon,
                .itwpt-table-base.a' . esc_attr($itwptRand) . ' .add-to-cart-link.itwpt-button .itwpt-left-icon{
                    display:inline-block;
                }';
		} else if ($template_data['button_add_to_cart_icon'] === 'right-icon') {
			$itwptStyle .=
				'.itwpt-table-base.a' . esc_attr($itwptRand) . ' .add-to-cart.itwpt-button .itwpt-left-icon,
                .itwpt-table-base.a' . esc_attr($itwptRand) . ' .add-to-cart-link.itwpt-button .itwpt-left-icon{
                    display:none;
                }
                .itwpt-table-base.a' . esc_attr($itwptRand) . ' .add-to-cart.itwpt-button .itwpt-right-icon,
                .itwpt-table-base.a' . esc_attr($itwptRand) . ' .add-to-cart-link.itwpt-button .itwpt-right-icon{
                    display:inline-block;
                }';
		}
	}

	// HEADER CONTROLS
	$itwpt_controlers_content = '';
	$itwpt_controlers =
		'<div class="itwpt-controls">
            %controls%
            <div class="clear"></div>
        </div>';



	if ($data['add_search_status'] === 'enable') {

	    $toggle_active = '';
	    $toggle_style = '';
        if ($data['add_search_toggle'] === 'enable') {
            $toggle_active = 'active';
            $toggle_style = 'display:block;';
        }

		$itwpt_controlers_content .=
			'<div class="itwpt-search">
                <i class="icon-it icon-itsearch"></i>
                <input type="text" class="search" placeholder="' . esc_html($data['add_localization_search_keyword_text']) . '">
                <i class="icon-it icon-itsettings setting '.$toggle_active.'"></i>
            </div>
            <div class="itwpt-search-btn">
                <i class="icon-it icon-itsearch"></i>
                <span>
                    ' . esc_html($data['add_localization_search_text']) . '
                </span>
            </div>
            <div class="itwpt-search-reset-btn">
                <i class="icon-it icon-itrefresh"></i>
            </div>
            <div class="itwpt-search-options" style="'.$toggle_style.'">';

		if ($data['add_search_price_options'] == 'enable') {
			$itwpt_controlers_content .=
				'<div class="itwpt-search-option" data-reset="' . esc_attr(!empty($data['add_conditions_min_price']) ? $data['add_conditions_min_price'] : Itwpt_Min_Price()) . '">
                    <i class="icon-it icon-itcoin itwpt-tooltip itwpt-tooltip-right" data-tooltip-text="' . esc_html($data['add_localization_search_box_min_price']) . '"></i>
                    <select class="min-price">
                        ' . Itwpt_Price_Step() . '
                    </select>
                </div>
                <div class="itwpt-search-option" data-reset="' . esc_attr(!empty($data['add_conditions_max_price']) ? $data['add_conditions_max_price'] : Itwpt_Max_Price()) . '">
                    <i class="icon-it icon-itcoin itwpt-tooltip itwpt-tooltip-right" data-tooltip-text="' . esc_html($data['add_localization_search_box_max_price']) . '"></i>
                    <select class="max-price">
                        ' . Itwpt_Price_Step(true) . '
                    </select>
                </div>';
		}

		if ($data['add_search_status_options'] == 'enable') {

			$ex_value = $data['add_conditions_product_status'];
			if ($ex_value === 'all') {
				$ex_value = null;
			}

			$itwpt_controlers_content .=
				'<div class="itwpt-search-option" data-reset="' . esc_attr($data['add_conditions_product_status']) . '">
                    <i class="icon-it icon-itbattery itwpt-tooltip itwpt-tooltip-right" data-tooltip-text="' . esc_html($data['add_localization_search_box_status']) . '"></i>
                    <select class="status">
                        <option value="all" ' . ($data['add_conditions_product_status'] == 'all' ? 'selected' : '') . '>' . esc_html__('All', PREFIX_ITWPT_TEXTDOMAIN) . '</option>
                        <option value="' . esc_attr($ex_value !== 'featured' && !empty($ex_value) ? $ex_value . ',' : '') . 'featured" ' . ($data['add_conditions_product_status'] == 'featured' ? 'selected' : '') . '>' . esc_html__('feature', PREFIX_ITWPT_TEXTDOMAIN) . '</option>
                        <option value="' . esc_attr($ex_value !== 'on_sale' && !empty($ex_value) ? $ex_value . ',' : '') . 'on_sale" ' . ($data['add_conditions_product_status'] == 'on_sale' ? 'selected' : '') . '>' . esc_html__('On Sale', PREFIX_ITWPT_TEXTDOMAIN) . '</option>
                        <option value="' . esc_attr($ex_value !== 'in_stock' && !empty($ex_value) ? $ex_value . ',' : '') . 'in_stock" ' . ($data['add_conditions_product_status'] == 'in_stock' ? 'selected' : '') . '>' . esc_html__('In Stock', PREFIX_ITWPT_TEXTDOMAIN) . '</option>
                    </select>
                </div>';
		}

		if ($data['add_search_sku_options'] == 'enable') {
			$itwpt_controlers_content .=
				'<div class="itwpt-search-option" data-reset="">
                    <i class="icon-it icon-itdocs itwpt-tooltip itwpt-tooltip-right" data-tooltip-text="' . esc_html($data['add_localization_search_box_sku']) . '"></i>
                    <input type="text" class="sku" placeholder="' . esc_html__('Please Enter SKU', PREFIX_ITWPT_TEXTDOMAIN) . '">
                </div>';
		}

		//Taxonomy Query
		$all_tax = get_object_taxonomies('product');
		if (is_array($all_tax) && count($all_tax) > 0) {

			//FETCH TAXONOMY
			foreach ($all_tax as $tax) {

				if ($tax == 'product_visibility' || $tax == 'product_type') {
					continue;
				}

				if (isset($data['add_query_' . $tax . '_include'])) {

					// CONTROL TAX VAL
					if (!empty($data['add_query_' . $tax . '_include'])) {
						$tax_val = explode(',', $data['add_query_' . $tax . '_include']);
						if (count($tax_val) > 1) {

							$taxonomy = get_taxonomy($tax);
							$label = $taxonomy->label;

							if ($data['add_search_' . $tax] == 'enable') {

								$itwpt_controlers_content .=
									'<div class="itwpt-search-option multiselect">
<div class="itwpt-field-multi-select-label">' . esc_html($label) . '</div>
                    
                    <select class="' . esc_attr($tax) . ' multi-select" multiple="multiple" data-default="' . esc_attr($data['add_query_' . $tax . '_include']) . '" data-taxonomy="' . esc_attr($tax) . '">';

								foreach ($tax_val as $indes => $item) {
									$term_data = get_term_by('id', $item, $tax);
									$itwpt_controlers_content .= '<option value="' . esc_attr($term_data->term_id) . '">' . esc_html($term_data->name) . '</option>';
								}

								$itwpt_controlers_content .= '</select>
                </div>';
							}
						}
					} else if (empty($data['add_query_' . $tax . '_include'])) {

						$taxonomy = get_taxonomy($tax);
						$label = $taxonomy->label;

						if ($data['add_search_' . $tax] == 'enable') {

							$itwpt_controlers_content .=
								'<div class="itwpt-search-option multiselect">
                                <i class="icon-it icon-itcategory itwpt-tooltip itwpt-tooltip-right"  data-tooltip-text="' . esc_html($label) . '"></i>
                    <select class="' . esc_attr($tax) . ' multi-select" multiple="multiple" data-default="' . esc_attr($data['add_query_' . $tax . '_include']) . '" data-taxonomy="' . esc_attr($tax) . '" data-placeholder="'.esc_html($label).'">';

							$tax_args = array(
								'taxonomy'     => $tax,
								'orderby'      => 'name',
								'show_count'   => 0,
								'pad_counts'   => 0,
								'hierarchical' => 1,
								'title_li'     => '',
								'hide_empty'   => 0
							);
							$cat_option = get_categories($tax_args);
							foreach ($cat_option as $index => $item) {
								$itwpt_controlers_content .= '<option value="' . esc_attr($item->term_id) . '">' . esc_html($item->name) . '</option>';
							}

							$itwpt_controlers_content .= '</select>
                </div>';
						}
					}
				}
			}
		}

		if ($data['add_search_order'] == 'enable') {
			$itwpt_controlers_content .=
				'<div class="itwpt-search-option" data-reset="' . esc_attr($data['add_query_order_by']) . '">
                    <i class="icon-it icon-itorder itwpt-tooltip itwpt-tooltip-right" data-tooltip-text="' . esc_html($data['add_localization_search_box_order_bay_text']) . '"></i>
                    <select class="order-by">
                        <option value="ID" ' . ($data['add_query_order_by'] == 'ID' ? 'selected' : '') . '>' . esc_html__('ID', PREFIX_ITWPT_TEXTDOMAIN) . '</option>
                        <option value="title" ' . ($data['add_query_order_by'] == 'title' ? 'selected' : '') . '>' . esc_html__('Title', PREFIX_ITWPT_TEXTDOMAIN) . '</option>
                        <option value="_price" ' . ($data['add_query_order_by'] == '_price' ? 'selected' : '') . '>' . esc_html__('Price', PREFIX_ITWPT_TEXTDOMAIN) . '</option>
                        <option value="_wc_average_rating" ' . ($data['add_query_order_by'] == 'rating' ? 'selected' : '') . '>' . esc_html__('Rating', PREFIX_ITWPT_TEXTDOMAIN) . '</option>
                        <option value="popularity" ' . ($data['add_query_order_by'] == 'popularity' ? 'selected' : '') . '>' . esc_html__('Popularity', PREFIX_ITWPT_TEXTDOMAIN) . '</option>
                        <option value="date" ' . ($data['add_query_order_by'] == 'date' ? 'selected' : '') . '>' . esc_html__('Date', PREFIX_ITWPT_TEXTDOMAIN) . '</option>
                        <option value="_sku" ' . ($data['add_query_order_by'] == '_sku' ? 'selected' : '') . '>' . esc_html__('SKU', PREFIX_ITWPT_TEXTDOMAIN) . '</option>
                        <option value="_stock" ' . ($data['add_query_order_by'] == '_stock' ? 'selected' : '') . '>' . esc_html__('Stock Quantity', PREFIX_ITWPT_TEXTDOMAIN) . '</option>n>
                        <option value="rand" ' . ($data['add_query_order_by'] == 'rand' ? 'selected' : '') . '>' . esc_html__('Random', PREFIX_ITWPT_TEXTDOMAIN) . '</option>
                    </select>
                </div>
                <div class="itwpt-search-option" data-reset="' . esc_attr($data['add_query_order']) . '">
                    <i class="icon-it icon-itorder itwpt-tooltip itwpt-tooltip-right" data-tooltip-text="' . esc_html($data['add_localization_search_box_order_text']) . '"></i>
                    <select class="order">
                        <option value="ASC" ' . ($data['add_query_order'] == 'ASC' ? 'selected' : '') . '>' . esc_html__('ASCENDING', PREFIX_ITWPT_TEXTDOMAIN) . '</option>
                        <option value="DESC" ' . ($data['add_query_order'] == 'DESC' ? 'selected' : '') . '>' . esc_html__('DESCENDING', PREFIX_ITWPT_TEXTDOMAIN) . '</optio>
                    </select>
                </div>';
		}

		$itwpt_controlers_content .= '</div>';
	}

	$itwptTableHeader .= Itwpt_String_Format(
		$itwpt_controlers,
		array(
			'controls' => $itwpt_controlers_content,
		)
	);

	// CUSTOM CLASS
	$cls .= ' ' . $data['add_checklist_custom_class_table'];

	// CUSTOM SCRIPT
	if (!empty($data['add_settings_light_box'])) {
		$itwptScript .= '
        jQuery(document).ready(function($){
            lightbox.option({
              "resizeDuration": 200,
              "wrapAround": false
            })
        });
        ';
	}

	/**
	 * TODO RETURN OUTPUT
	 */
	// STYLE ENQUEUE
	Itwpt_Front_Enqueue_Style(); // HEADER - ENQUEUE STYLE


	// OUT
	$output = Itwpt_String_Format(
		'<div class="itwpt-loading-box" style="height:300px; position:relative;">
            <div class="itwpt-loading" style="position:absolute; top:0; left:0; width:100%; height:100%; z-index: 9999; user-select:none;">
                <img src="' . esc_url(PREFIX_ITWPT_IMAGE_URL . 'loading_bs.gif') . '" style="width:40px; height:40px; position:absolute; top:0; right:0; bottom:0; left:0; margin: auto;">
            </div>
        </div>
        
        <div class="itwpt-table-base a%rand% %cls%" id="%rand%" style="display:none;" data-setting="%data%">
            <div class="itwpt-header">
                %header%
            </div>
            <div class="itwpt-table">
                <div class="itwpt-table-scroll">
                    <table id="table%rand%" class="display">
                        %thead%
                        <tbody>
                            %tbody%
                        </tbody>
                        %tfooter%
                    </table>
                </div>
            </div>
            <div class="itwpt-footer %page_control%">
                %footer%
            </div>
            <div class="itwpt-end">%end%</div>
            <script>
                %script%
            </script>
            <style>
                %style%
            </style>
        </div>
        %exit%',
		array(
			'header'       => $itwptTableHeader,
			'rand'         => $itwptRand,
			'thead'        => (!strstr($data['add_checklist_column_hide_header_footer'], 'hide-header') ? '<thead>' . $itwptThead . '</thead>' : ''),
			'tfooter'      => (!strstr($data['add_checklist_column_hide_header_footer'], 'hide-footer') ? '<tfoot>' . $itwptThead . '</tfoot>' : ''),
			'tbody'        => $itwptRows,
			'footer'       => $itwptTableFooter,
			'script'       => $itwptScript,
			'style'        => $itwptStyle,
			'end'          => $itwptEnd,
			'data'         => urlencode(json_encode($data)),
			'exit'         => $exit,
			'cls'          => $cls,
			'page_control' => ($data['add_search_filter_pagination'] === 'pagination' ? 'pagination-enable' : 'load-more-enable'),
		)
	);

	// SCRIPT ENQUEUE
	Itwpt_Front_Enqueue_Script(); // FOOTER - ENQUEUE SCRIPT

	return $output;
}
