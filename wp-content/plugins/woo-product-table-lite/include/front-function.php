<?php
global $itwpt_sl;
$itwpt_sl = 1;

/**
 * TODO CREATE FRONT FIELDS
 */
function Itwpt_Get_Cl_Product($obj, $data, $product)
{

    global $itwpt_sl;


    if ($obj->type === '-') {

        if ($obj->value === 'id') {

            return get_the_ID();

        } elseif ($obj->value === 'thumbnails') {
            $image_size = isset($data['general_thumbs_img_size']) ? $data['general_thumbs_img_size'] : "woocommerce_thumbnail";

            $img_url = get_the_post_thumbnail_url(null, $image_size);
            $out     = '<div style="background:url(\'' . esc_url($img_url) . '\')" class="itwpt-img">';
            if ( ! empty($data['add_settings_light_box'])) {
                $out .= '<a href="' . esc_url($img_url) . '" data-lightbox="' . esc_html(get_the_ID()) . '" data-title="' . esc_html(get_the_title()) . '"></a>';
            }
            $out .= '</div>';

            return $out;

        } elseif ($obj->value === 'product_title') {

            $link_type = '';
            if ($data['add_settings_product_link_type'] === 'new') {
                $link_type = '_blank';
            }

            if (empty($data['add_settings_product_link'])) {
                return  esc_html(get_the_title()) ;
            } else {
                return '<a href="' . esc_url(get_permalink()) . '" target="' . esc_attr($link_type) . '">' . esc_html(get_the_title()) . '</a>';
            }

        } elseif ($obj->value === 'category') {

            $link_type = '';
            if (isset($data['add_settings_taxonomy_link_type']) && $data['add_settings_taxonomy_link_type'] === 'new') {
                $link_type = '_blank';
            }

            $cat     = get_the_terms(get_the_ID(), 'product_cat');
            $cat_out = array();
            if ( ! empty($cat)) {
                foreach ($cat as $category) {
                    if (isset($data['add_settings_taxonomy_link']) && empty($data['add_settings_taxonomy_link'])) {
                        $cat_out[] = esc_html($category->name);
                    }else{
                        $cat_out[] = '<a target="' . esc_attr($link_type) . '" href="' . get_category_link($category->term_id) . '">' . esc_html($category->name) . '</a>';
                    }
                }
            }

            return join(', ', $cat_out);

        } elseif ($obj->value === 'sl') {

            return $itwpt_sl++;

        } elseif ($obj->value === 'sku') {

            return wc_get_product(get_the_ID())->get_sku();

        } elseif ($obj->value === 'description') {

            if ($data['add_checklist_description_type'] === 'excerpt') {

                global $Itwpt_Number_Desc;
                $Itwpt_Number_Desc = $data['add_checklist_description_length'];
                add_filter('excerpt_length', 'Itwpt_Exc_Desc', $Itwpt_Number_Desc);

                ob_start();
                the_excerpt();
                $text_desc = ob_get_contents();
                ob_end_clean();

                return do_shortcode($text_desc);

            } else {
                ob_start();
                the_content();
                $text_desc = ob_get_contents();
                ob_end_clean();

                return do_shortcode($text_desc);
            }

        } elseif ($obj->value === 'tags') {

            $link_type = '';
            if (isset($data['add_settings_taxonomy_link_type']) && $data['add_settings_taxonomy_link_type'] === 'new') {
                $link_type = '_blank';
            }


            $tags    = get_the_terms(get_the_ID(), 'product_tag');
            $tag_out = array();
            if ( ! empty($tags)) {
                foreach ($tags as $tag) {

                    if (isset($data['add_settings_taxonomy_link']) && empty($data['add_settings_taxonomy_link'])) {
                        $tag_out[] = esc_html($tag->name);
                    }else{
                        $tag_out[] = '<a target="' . esc_attr($link_type) . '" href="' . get_tag_link($tag->term_id) . '">' . esc_html($tag->name) . '</a>';
                    }

                }
            }

            return join(', ', $tag_out);

        } elseif ($obj->value === 'weight') {

            return $product->get_weight();

        } elseif ($obj->value === 'length') {

            return $product->get_length();

        } elseif ($obj->value === 'width') {

            return $product->get_width();

        } elseif ($obj->value === 'height') {

            return $product->get_height();

        } elseif ($obj->value === 'rating') {

            $product      = wc_get_product(get_the_ID());
            $rating_count = $product->get_rating_count();
            $average      = $product->get_average_rating();

            return wc_get_rating_html($average, $rating_count);

        } elseif ($obj->value === 'stock') {

            if ($product->get_type() == 'variable') {
                return '';
            } else {

                if ( ! $product->managing_stock() && ! $product->is_in_stock()) {
                    return '<div class="itwpt-msg-out-of-stock">' . esc_html($data['add_localization_out_of_stock_text']) . '</div>';
                } elseif ($product->get_stock_status() === 'onbackorder') {
                    return '<div class="itwpt-msg-back-order">' . esc_html($data['add_localization_on_back_order_text']) . '</div>';
                } else {
                    return '<div class="itwpt-msg-in-stock">' . esc_html($data['add_localization_in_stock_text']) . '</div>';
                }

            }

        } elseif ($obj->value === 'price') {

            return $product->get_price_html();

        } elseif ($obj->value === 'quantity') {

            global $itwpt_svg;

            return '<div class="itwpt-quantity"><div class="nav arrow-left">' . sprintf("%s",
                    $itwpt_svg['quantity-left.svg']) . '</div>' . woocommerce_quantity_input(array(), $product,
                    false) . '<div class="nav arrow-right up">' . $itwpt_svg['quantity-right.svg'] . '</div></div>';

        } elseif ($obj->value === 'action') {

            $disable = '';
            $action  = '';
            if (Itwpt_Check_In_Stock($product) || empty($product->get_price_html())) {
                $disable = 'disable';
            }

            if (empty($data['add_checklist_ajax_action'])) {
                $action .= '<a class="itwpt-button add-to-cart-link" href="' . esc_url(get_permalink()) . '"><i class="icon-itcart itwpt-left-icon"></i><span> ' . esc_html($data['add_localization_add_to_cart_text']) . ' </span></a>';
            } else {
                $action .= '<div class="add-to-cart itwpt-button ' . esc_attr($disable) . '" data-message="" data-variation="0" data-product_id="' . get_the_ID() . '" data-quantity="1"><i class="icon-itcart itwpt-left-icon"></i><span> ' . esc_html($data['add_localization_add_to_cart_text']) . ' </span></div>';
                if ($data['add_checklist_variation']) {
                    $action .= Itwpt_Variation_Html($product, get_the_ID(), $data);
                }
            }

            return $action;

        } elseif ($obj->value === 'quick-view') {

            return do_shortcode('[yith_quick_view label="' . esc_html($data['add_localization_yith_quick_view']) . '"]');

        }elseif ($obj->value === 'ti-wish-list') {
            //Version 1.2.0
            //COMPATIBL'E WITH TI WishList
            return do_shortcode('[ti_wishlists_addtowishlist]');

        }elseif ($obj->value === 'yith-compare') {

            //Version 1.6.1
            //COMPATIBL'E WITH Yith  Compare
            return do_shortcode('[yith_compare_button type="button"]');

        }elseif ($obj->value === 'woothem-compare') {
            //Version 1.5.0
            //COMPATIBL'E WITH WooThem  Compare
            $save_output_here = '';
            ob_start();
            $a = new WC_Products_Compare_Frontend();
            $a->display_compare_button();

            $save_output_here = ob_get_contents(); // the actions output will now be stored in the variable as a string!
            ob_end_clean();
            return $save_output_here;

        } elseif ($obj->value === 'wish-list') {

            return do_shortcode('[yith_wcwl_add_to_wishlist label="' . esc_html($data['add_localization_yith_wish_list']) . '"]');

        } elseif ($obj->value === 'total-price') {

            $position = get_option('woocommerce_currency_pos');
            if ($position === 'left') {
                $total_price = '' . get_woocommerce_currency_symbol() . '<span>' . number_format($product->get_price(),
                        2, ".", ",") . '</span>';
            } elseif ($position === 'left_space') {
                $total_price = '' . get_woocommerce_currency_symbol() . ' <span>' . number_format($product->get_price(),
                        2, ".", ",") . '</span>';
            } elseif ($position === 'right') {
                $total_price = '<span>' . number_format($product->get_price(), 2, ".",
                        ",") . '</span>' . get_woocommerce_currency_symbol() . '';
            } elseif ($position === 'right_space') {
                $total_price = '<span> ' . number_format($product->get_price(), 2, ".",
                        ",") . '</span>' . get_woocommerce_currency_symbol() . '';
            }

            return '<div class="total" data-price="' . esc_attr($product->get_price()) . '">' . sprintf($total_price) . '</div>';

        } elseif ($obj->value === 'date') {

            return get_the_date();

        } elseif ($obj->value === 'attributes') {

            return Itwpt_Attributes($product, get_the_ID());

        } elseif ($obj->value === 'acf') {

            return 'ACF';

            //return Itwpt_Attributes($product, get_the_ID());

        } elseif ($obj->value === 'variations') {

            return Itwpt_Variation_Html($product, get_the_ID(), $data);

        } elseif ($obj->value === 'short-message') {

            return '<input class="itwpt-short-message" type="text" placeholder="' . esc_html($data['add_localization_type_your_message_text']) . '">';

        } elseif ($obj->value === 'check') {

            $disable = '';
            if (Itwpt_Check_In_Stock($product) || empty($product->get_price_html())) {
                $disable = 'disable';
            }

            return '<div class="itwpt-checkbox ' . esc_attr($disable) . '">
                        <div data-id="' . get_the_ID() . '" data-quantity="1" data-message="" data-variation="0" class="itwpt-checkbox-selector">
                            <i class="icon-ittick"></i>
                        </div>
                    </div>';

        }

    } elseif ($obj->type === 'acf' && class_exists('ACF') ) {

        //Version 1.1.0
        //COMPATIBLE WITH ACF


        $field_value = get_field_object($obj->value, get_the_ID());

        $field_type = $field_value['type'];
        switch ($field_type) {
            case 'text' :
            case 'number':
            case 'email':
                return $field_value['value'];
                break;

            case 'select':

                $select_html = [];
                if(is_array($field_value['value']) && count($field_value['value'])>0){
                    foreach ($field_value['value'] as $checkbox){
                        $select_html[] = $checkbox['label'];
                    }
                    return implode(',', $select_html);
                }

                if(isset($field_value['choices'][$field_value['value']]))
                    return $field_value['choices'][$field_value['value']];
                break;

            case 'radio':
                if(isset($field_value['choices'][$field_value['value']]))
                    return $field_value['choices'][$field_value['value']];
                break;

            case 'checkbox':
                $checkbox_html = [];
                if(is_array($field_value['value']) && count($field_value['value'])>0){
                    foreach ($field_value['value'] as $checkbox){
                        $checkbox_html[] = $field_value['choices'][$checkbox];
                    }
                    return implode(',', $checkbox_html);
                }

                break;

            case 'image':
                return '<img class="thumbnails_tbl_column thumbnails_custom_column" src="' . $field_value['value'] . '" />';
                break;

            case 'url':
                if($field_value['value']!='')
                    return '<a href="' . $field_value['value'] . '" target="_blank">' . $field_value['label'] . '</a>';
                break;

            case 'file':
                if($field_value['value']!='')
                    return '<a href="' . $field_value['value'] . '" target="_blank">' . $field_value['label'] . '</a>';
                break;

        }

        //  return $field_value['value'];


    } elseif ($obj->type == 'post') {

        $custom_field = get_post_meta(get_the_ID(), $obj->value);
        if ( ! empty($custom_field)) {

            $out_custom_field = '';
            foreach (get_post_meta(get_the_ID(), $obj->value) as $a) {
                $out_custom_field .= $a;
            }

            return $out_custom_field;

        } else {
            return '';
        }

    } elseif ($obj->type == 'taxonomy') {

        $terms     = get_the_terms(get_the_ID(), $obj->value);
        $term_html = [];

        if ( ! empty($terms)) {
            foreach ($terms as $object) {
                $term_html[] = $object->name;
            }
        }

        return join(', ', $term_html);

    }

    return false;
}

/**
 * TODO CHECK IN STOCK
 */
function Itwpt_Check_In_Stock($product)
{

    $check = false;
    if (( ! $product->managing_stock() && ! $product->is_in_stock()) || $product->get_type() == 'variable') {
        $check = true;
    }

    return $check;

}

/**
 * TODO GET PRODUCT IN CART
 */
function Itwpt_Get_Product_Cart($ajax = false)
{

    if (empty(WC()->cart)) {
        return false;
    }

    $out_item = '';
    $items    = WC()->cart->get_cart();

    foreach ($items as $item => $values) {
        $_product         = wc_get_product($values['data']->get_id());
        $getProductDetail = wc_get_product($values['product_id']); //product image
        $item             =
            '<div class="itwpt-cart-item" title="%title%" data-total="%total%" data-qty="%qty%" data-symbol="%symbol%">
                <div class="image" style="background:url(\'%image%\')"></div>
                <div class="cart-content">
                    <div class="title">%title%</div>
                    %message%
                    <div class="quantity">%qty% X %price%</div>
                </div>
                <div class="delete">
                    <i class="icon-itx" data-id="%id%"></i>
                </div>
            </div>';

        $out_image = get_the_post_thumbnail_url($values['product_id'], 'full'); // accepts 2 arguments ( size, attr )
        $out_qty   = $values['quantity']; // QUANTITY

        $out_item .= Itwpt_String_Format($item, array(
            'image'   => $out_image,
            'title'   => $_product->get_name(),
            'qty'     => $out_qty,
            'price'   => wc_price($values['line_subtotal'] / $out_qty),
            'id'      => $values['key'],
            'total'   => $values['line_subtotal'],
            'symbol'  => get_woocommerce_currency_symbol(),
            'message' => (! empty($values['Itwpt_custom_message']) ? '<div class="itwpt-short-msg-show"><span>' . esc_html__('Message',
                    PREFIX_ITWPT_TEXTDOMAIN) . ':</span> ' . esc_html($values['Itwpt_custom_message']) . '</div>' : '')
        ));

    }

    $out = Itwpt_String_Format('%items%<div class="clear"></div>', array(
        'items' => $out_item,
    ));

    return $out;

}

/**
 * TODO GET TOTAL PRICE CART
 */
function Itwpt_Get_Total_Price_Cart()
{

    if (empty(WC()->cart)) {
        return false;
    }

    return WC()->cart->get_cart_total();

}

/**
 * TODO GET TOTAL QUANTITY CART
 */
function Itwpt_Get_Total_Quantity_Cart()
{

    if (empty(WC()->cart)) {
        return false;
    }

    $qtyCounter = 0;
    $items      = WC()->cart->get_cart();

    foreach ($items as $item => $values) {
        $qtyCounter += $values['quantity'];
    }

    return $qtyCounter;

}

/**
 * TODO ADD TO CART PRODUCT
 */
function Itwpt_Add_To_Cart_Product($id, $_variation_id, $_quantity)
{

    $product_id        = apply_filters('woocommerce_add_to_cart_product_id', absint($id));
    $quantity          = empty($_quantity) ? 1 : wc_stock_amount($_quantity);
    $variation_id      = absint($_variation_id);
    $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
    $product_status    = get_post_status($product_id);

    if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity,
            $variation_id) && 'publish' === $product_status) {

        do_action('woocommerce_ajax_added_to_cart', $product_id);

        if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
            wc_add_to_cart_message(array($product_id => $quantity), true);
        }
        add_action('woocommerce_add_cart_item_data', 'Itwpt_save_custom_message_field', 10, 2);

    } else {

        $data = array(
            'error'       => true,
            'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id),
                $product_id)
        );

    }

}


function Itwpt_Attributes($product, $id)
{
    $attributes = $product->get_attributes();
    if ( ! $attributes) {
        return;
    }

    $display_result = '';
    foreach ($attributes as $attribute) {

        $name = $attribute->get_name();
        if ($attribute->is_taxonomy()) {
            $terms              = wp_get_post_terms($product->get_id(), $name, 'all');
            $cwtax              = $terms[0]->taxonomy;
            $cw_object_taxonomy = get_taxonomy($cwtax);
            if (isset ($cw_object_taxonomy->labels->singular_name)) {
                $tax_label = $cw_object_taxonomy->labels->singular_name;
            } elseif (isset($cw_object_taxonomy->label)) {
                $tax_label = $cw_object_taxonomy->label;
                if (0 === strpos($tax_label, 'Product ')) {
                    $tax_label = substr($tax_label, 8);
                }
            }
            $display_result .= $tax_label . ': ';
            $tax_terms      = array();
            foreach ($terms as $term) {
                $single_term = esc_html($term->name);
                array_push($tax_terms, $single_term);
            }
            $display_result .= implode(', ', $tax_terms) . '<br />';
        } else {
            $display_result .= $name . ': ';
            $display_result .= esc_html(implode(', ', $attribute->get_options())) . '<br />';
        }
    }

    return $display_result;

}

/**
 * TODO VARIATION HTML
 */
function Itwpt_Variation_Html($product, $id, $data)
{

    global $itwpt_rand;
    global $itwpt_svg;

    $output       = '';
    $data_product = $product->get_data();
    if ($product->get_type() == 'variable') {
        $variable                = new WC_Product_Variable($data_product['id']);
        $available_variations    = $variable->get_available_variations();
        $data_product_variations = htmlspecialchars(wp_json_encode($available_variations));

        $attributes         = $variable->get_variation_attributes();
        $default_attributes = $variable->get_default_attributes(); //Added at 3.9.0
        $variation_html     = itwpt_variations_attribute_to_select($attributes, $id, $default_attributes, false);
        $popup              =
            '<div class="variation-popup" data-id="itwpt_' . esc_attr($itwpt_rand) . get_the_ID() . '">
                <div class="arrow">' . sprintf("%s", $itwpt_svg['fls_popup.svg']) . '</div>
                <div class="variation-popup-data">
                    <div class="img" style="background-image:url(\'' . get_the_post_thumbnail_url(null, 'full') . '\')"></div>
                    <div class="variation-popup-tp">
                        <div class="title">' . get_the_title() . '</div>
                        <div class="price">' . esc_html__('Price:', PREFIX_ITWPT_TEXTDOMAIN) . '<span></span></div>
                        <div class="status">' . (empty($available_variations) ? '<span>' . esc_html($data['add_localization_variation_is_not_set_text']) . '</span>' : '<span>' . esc_html($data['add_localization_select_all_item_text']) . '</span>') . '</div>
                    </div>
                </div>
                <div class="variation-popup-dropdown">
                    ' . sprintf($variation_html) . '
                </div>
                <div class="itwpt-button">
                    ' . esc_html__('Set Variation', PREFIX_ITWPT_TEXTDOMAIN) . '
                </div>
            </div>';

        $output =
            '<div class="itwpt-variation">
                <div class="itwpt-button itwpt-variation-btn" data-popup="' . Itwpt_html_encode($popup) . '"><i class="icon-itvariation"></i></div>                
            </div>';
    }

    return $output;

}

/**
 * TODO VARIATION ATTRIBUTE TO SELECT
 */
function itwpt_variations_attribute_to_select(
    $attributes,
    $product_id = false,
    $default_attributes = false,
    $temp_number = false
) {

    $html = false;
    foreach ($attributes as $attribute_key_name => $options) {

        $html           .= "";
        $html_option    = "";
        $label          = wc_attribute_label($attribute_key_name);
        $attribute_name = wc_variation_attribute_name($attribute_key_name);
        $only_attribute = str_replace('attribute_', '', $attribute_name);


        $default_value = ! isset($default_attributes[$only_attribute]) ? false : $default_attributes[$only_attribute]; //Set in 3.9.0

        foreach ($options as $option) {

            $term = get_term_by('slug', $option, $attribute_key_name);
            $name = isset($term->name) ? $term->name : $option;

            $html_option .= "<option value='" . esc_attr($option) . "' " . ($default_value == $option ? 'selected' : '') . ">" . ucwords($name) . "</option>";
        }

        $html .= Itwpt_String_Format(
            "<div>
                    <select data-pid='%product_id%' name='%attr_name%'>
                        <option value='0'>%label%</option>
                        %option%
                    </select>
                </div>",
            array(
                'product_id' => $product_id,
                'attr_name'  => $attribute_name,
                'label'      => $label,
                'option'     => $html_option
            )
        );

    }

    return $html;
}

/**
 * TODO VARIATION DATA (JSON)
 */
function Itwpt_Get_Variation_Data_Json($id)
{

    $variable                = new WC_Product_Variable($id);
    $available_variations    = $variable->get_available_variations();
    $data_product_variations = htmlspecialchars(wp_json_encode($available_variations));

    return $data_product_variations;

}

/**
 * TODO PRODUCT QUANTITY BY ID
 */
function Itwpt_Get_Product_Quantity_By_Id($id)
{

    $iteddms = WC()->cart->get_cart();
    foreach ($iteddms as $key => $val) {
        if ($val['product_id'] == $id) {
            return $val['quantity'];
        }
    }

    return 0;

}

/**
 * TODO HTML ENCODE BY ITWPT
 */
function Itwpt_html_encode($html)
{

    $search  = array('<', '>', "'", '"', '=', ' ');
    $replace = array('%A0;', '%A1;', '%A2;', '%A3;', '%A4;', '%A5;');

    $html = str_replace($search, $replace, $html);

    return $html;

}

/**
 * TODO HTML DECODE BY ITWPT
 */
function Itwpt_html_decode($html)
{

    $search  = array('%A0;', '%A1;', '%A2;', '%A3;', '%A4;', '%A5;');
    $replace = array('<', '>', "'", '"', '=', ' ');

    $html = str_replace($search, $replace, $html);

    return $html;

}