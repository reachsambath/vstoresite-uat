<?php
global $admin_form;
global $admin_template_form;
global $admin_template_selector;
global $general;

// GET DATA FROM GENERAL
$general_data = array();
$general_tbl  = Itwpt_Get_Data_Table('itpt_options', array(
    'plugin_name' => PREFIX_ITWPT_PLUGIN_NAME,
    'name'        => 'general'
));
if ( ! empty($general_tbl)) {
    foreach (json_decode(urldecode($general_tbl[0]->data)) as $item) {
        $general_data[$item->name] = $item->value;
    }
}

//Version 1.1.0
//COMPATIBLE WITH ACF
$acf_default = '';
if (class_exists('ACF')) :
    $groups = acf_get_field_groups(array('post_type' => 'product'));
//print_r($groups);
    foreach ($groups as $group) {
        $group_key = $group['key'];
        $fields    = acf_get_fields($group_key);
        foreach ($fields as $field) {
            $field_key   = $field['key'];
            $field_label = $field['label'];
            $acf_default .= ',{"type": "acf","value": "' . $field_key . '","text": "' . $field_label . '","placeholder": "' . $field_label . '","desktop": "","laptop": "","mobile": ""}';
        }
    }
endif;

/**
 * TODO PAGE ADD NEW FORM
 */
$admin_form = array(
    'columns'      => array(
        'fields' => array(
            'add_column_shortcode_name' =>
                array(
                    'type'        => 'text-box',
                    'heading'     => esc_html__('Shortcode Name', PREFIX_ITWPT_TEXTDOMAIN),
                    'placeholder' => esc_html__('Enter Shortcode Name', PREFIX_ITWPT_TEXTDOMAIN),
                ),

            'add_column_table_column'   => array(
                'type'    => 'columns',
//                'dependency' => array(
//                    'element' => 'add_column_type',
//                    'value'   => 'custom',
//                    'not'     => false
//                ),
                'heading' => esc_html__('columns', PREFIX_ITWPT_TEXTDOMAIN),
                'warning' => esc_html__('To add taxonomy or Custom_Field as table column for your table, try from following bottom section before [Publish/Update] your post.',
                    PREFIX_ITWPT_TEXTDOMAIN),
                'default' => '[{"type":"-","value":"id","text":"ID","placeholder":"ID","desktop":"active","laptop":"","mobile":""},{"type":"-","value":"thumbnails","text":"Thumbnails","placeholder":"Thumbnails","desktop":"active","laptop":"active","mobile":""},{"type":"-","value":"product_title","text":"Product Title","placeholder":"Product Title","desktop":"active","laptop":"active","mobile":"active"},{"type":"-","value":"sku","text":"SKU","placeholder":"SKU","desktop":"active","laptop":"","mobile":""},{"type":"-","value":"price","text":"Price","placeholder":"Price","desktop":"active","laptop":"active","mobile":"active"},{"type":"-","value":"quantity","text":"Quantity","placeholder":"Quantity","desktop":"active","laptop":"active","mobile":""},{"type":"-","value":"action","text":"Action","placeholder":"Action","desktop":"active","laptop":"active","mobile":"active"},{"type":"-","value":"check","text":"Check","placeholder":"Check","desktop":"active","laptop":"active","mobile":"active"}' . $acf_default . ']',
                'options' => array(
                    'id'            =>
                        array(
                            'default'     => esc_html__('ID', PREFIX_ITWPT_TEXTDOMAIN),
                            'placeholder' => esc_html__('ID', PREFIX_ITWPT_TEXTDOMAIN),
                            "type"        => "-",
                            "desktop"     => true,
                            "laptop"      => false,
                            "mobile"      => false
                        ),
                    'thumbnails'    => array(
                        'default'     => esc_html__('Thumbnails', PREFIX_ITWPT_TEXTDOMAIN),
                        'placeholder' => esc_html__('Thumbnails', PREFIX_ITWPT_TEXTDOMAIN),
                        "type"        => "-",
                        "desktop"     => true,
                        "laptop"      => true,
                        "mobile"      => false
                    ),
                    'product_title' => array(
                        'default'     => esc_html__('Product Title', PREFIX_ITWPT_TEXTDOMAIN),
                        'placeholder' => esc_html__('Product Title', PREFIX_ITWPT_TEXTDOMAIN),
                        "type"        => "-",
                        "desktop"     => true,
                        "laptop"      => true,
                        "mobile"      => true
                    ),

                    'sku'           => array(
                        'default'     => esc_html__('SKU', PREFIX_ITWPT_TEXTDOMAIN),
                        'placeholder' => esc_html__('SKU', PREFIX_ITWPT_TEXTDOMAIN),
                        "type"        => "-",
                        "desktop"     => true,
                        "laptop"      => false,
                        "mobile"      => false
                    ),

                    'price'         => array(
                        'default'     => esc_html__('Price', PREFIX_ITWPT_TEXTDOMAIN),
                        'placeholder' => esc_html__('Price', PREFIX_ITWPT_TEXTDOMAIN),
                        "type"        => "-",
                        "desktop"     => true,
                        "laptop"      => true,
                        "mobile"      => true
                    ),
                    'quantity'      => array(
                        'default'     => esc_html__('Quantity', PREFIX_ITWPT_TEXTDOMAIN),
                        'placeholder' => esc_html__('Quantity', PREFIX_ITWPT_TEXTDOMAIN),
                        "type"        => "-",
                        "desktop"     => true,
                        "laptop"      => true,
                        "mobile"      => false
                    ),

                    'action'        => array(
                        'default'     => esc_html__('Action', PREFIX_ITWPT_TEXTDOMAIN),
                        'placeholder' => esc_html__('Action', PREFIX_ITWPT_TEXTDOMAIN),
                        "type"        => "-",
                        "desktop"     => true,
                        "laptop"      => true,
                        "mobile"      => true
                    ),
                    'check'         => array(
                        'default'     => esc_html__('Check', PREFIX_ITWPT_TEXTDOMAIN),
                        'placeholder' => esc_html__('Check', PREFIX_ITWPT_TEXTDOMAIN),
                        "type"        => "-",
                        "desktop"     => true,
                        "laptop"      => true,
                        "mobile"      => true
                    ),
                ),
            ),
            'video-link'                => array(
                'type'     => 'video-link',
                'link'     => 'https://www.youtube.com/watch?v=MD1nl_Eflqs',
                'download' => 'https://codecanyon.net/item/woocommerec-product-table/25871270?s_rank=1',
                'text'     => 'More Columns are available in Pro Version',
            )
        ),
        'help'   => array(
            'section' => array(
                'header'      => esc_html__('help?', PREFIX_ITWPT_TEXTDOMAIN),
                'description' => esc_html__('You can enable/disable product fields as table columns.
Here you can do some actions:
1-	Show/Hide column for different devices (Desktop, Tablet or Mobile)
2-	Set Custom Title for each Column
3-	Set Custom Order for Columns
4-	Add New Custom Field
5-	Add Custom Taxonomy
', PREFIX_ITWPT_TEXTDOMAIN),
            ),
        )
    ),
    'query'        => array(
        'fields' => array(
            'add_query_product_heading'    => array(
                'type'    => 'heading',
                'heading' => esc_html__('Product', PREFIX_ITWPT_TEXTDOMAIN),
            ),
            'add_query_product_include_id' => array(
                'type'        => 'multi-select',
                'heading'     => esc_html__('Product ID Include', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Example: 1,2,3,4', PREFIX_ITWPT_TEXTDOMAIN),
                'ajax_action' => 'get_product',
                'options'     => array(),
                'responsive'  => array(
                    'desktop' => 6,
                    'laptop'  => 6,
                    'tablet'  => 12,
                    'mobile'  => 12
                ),
            ),
            'add_query_product_exclude_id' => array(
                'type'        => 'multi-select',
                'heading'     => esc_html__('Product ID Exclude', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Example: 1,2,3,4', PREFIX_ITWPT_TEXTDOMAIN),
                'ajax_action' => 'get_product',
                'options'     => array(),
                'responsive'  => array(
                    'desktop' => 6,
                    'laptop'  => 6,
                    'tablet'  => 12,
                    'mobile'  => 12
                ),
            ),
            //ALL OF TAXONOMIES WILL BE ADDED HERE DYNAMICALLY
        ),
        'help'   => array(
            'section' => array(
                'header'      => esc_html__('help?', PREFIX_ITWPT_TEXTDOMAIN),
                'description' => esc_html__('In this tab you can build your query, this mean you can fetch specific products and display them as table.
There are a lot of options for that, for example you can just display products from specific “Category(s)” or “Custom Taxonomy(s)”, Featured Products, On-Sale products, In custom Price Range and etc.
	All of options divided by fields type:
1-	Product: You can Show/Hide individual products.
2-	Category/Taxonomy/Tags/Variations: There are all of Product’s Taxonomies and you can set your custom items for each one.
3-	Order / Order By: Products will be sort by specific field in Ascending/Descending mode.
4-	Conditions: Set Extra condition over your query.', PREFIX_ITWPT_TEXTDOMAIN),
            ),
        )
    ),
    'search'       => array(
        'fields' => array(
            'add_search_status'         => array(
                'type'    => 'checkbox',
                'heading' => esc_html__('Search Enable/Disable', PREFIX_ITWPT_TEXTDOMAIN),
                'options' => array(
                    array(
                        'text'   => esc_html__('Enable', PREFIX_ITWPT_TEXTDOMAIN),
                        'value'  => 'enable',
                        'active' => false
                    ),
                ),
            ),
            'add_search_toggle'         => array(
                'type'    => 'checkbox',
                'heading' => esc_html__('Always Show Search Form', PREFIX_ITWPT_TEXTDOMAIN),
                'options' => array(
                    array(
                        'text'   => esc_html__('Enable', PREFIX_ITWPT_TEXTDOMAIN),
                        'value'  => 'enable',
                        'active' => false
                    ),
                ),
                'dependency' => array(
                    'element' => 'add_search_status',
                    'value'   => 'enable',
                    'not'     => false
                ),
            ),
            'add_search_option_heading' => array(
                'type'       => 'heading',
                'heading'    => esc_html__('Fields Options', PREFIX_ITWPT_TEXTDOMAIN),
                'dependency' => array(
                    'element' => 'add_search_status',
                    'value'   => 'enable',
                    'not'     => false
                ),
            ),
            'add_search_order'          => array(
                'type'       => 'checkbox',
                'heading'    => esc_html__('Order options', PREFIX_ITWPT_TEXTDOMAIN),
                'options'    => array(
                    array(
                        'text'   => esc_html__('Enable', PREFIX_ITWPT_TEXTDOMAIN),
                        'value'  => 'enable',
                        'active' => false
                    ),
                ),
                'dependency' => array(
                    'element' => 'add_search_status',
                    'value'   => 'enable',
                    'not'     => false
                ),
            ),
            //ALL OF TAXONOMIES WILL BE ADDED HERE DYNAMICALLY

        ),
        'help'   => array(
            'section' => array(
                'header'      => esc_html__('help?', PREFIX_ITWPT_TEXTDOMAIN),
                'description' => esc_html__('If you need to have a search form above your table, you should enable “Search Form”, after that you can customize the search form and Show/Hide search fields. For example you can enable “Category” fields. In this case, user can search for specific “Category”.

You can choose your pagination type too.
-	Load More
-	Numeric', PREFIX_ITWPT_TEXTDOMAIN),
            ),
        )
    ),
    'template'     => array(
        'fields' => array(
            'add_template_template' => array(
                'type'    => 'template',
                'heading' => esc_html__('Select Template', PREFIX_ITWPT_TEXTDOMAIN),
                'select'  => true,
            ),
        ),
        'help'   => array(
            'section' => array(
                'header'      => esc_html__('help?', PREFIX_ITWPT_TEXTDOMAIN),
                'description' => esc_html__('Template and style is the most important section of this plugin. We provided some pre template that you can select them for your table Otherwise there is a special section for “Add Templates”',
                    PREFIX_ITWPT_TEXTDOMAIN),
            ),
        )
    ),
    'settings'     => array(
        'fields' => array(
            'add_settings_product_option_heading' => array(
                'type'    => 'heading',
                'heading' => esc_html__('Product Options', PREFIX_ITWPT_TEXTDOMAIN),
            ),

            'add_settings_product_link'           => array(
                'type'    => 'dropdown',
                'heading' => esc_html__('Enable Product Link', PREFIX_ITWPT_TEXTDOMAIN),
                'default' => (! empty($general_data) ? (isset($general_data['general_disable_product_link']) ? $general_data['general_disable_product_link'] : '0') : '0'),
                'options' => array(
                    '0' => esc_html__('No', PREFIX_ITWPT_TEXTDOMAIN),
                    '1' => esc_html__('Yes', PREFIX_ITWPT_TEXTDOMAIN),
                ),
            ),
            'add_settings_product_link_type'      => array(
                'type'       => 'dropdown',
                'heading'    => esc_html__('Product Link Open Type', PREFIX_ITWPT_TEXTDOMAIN),
                'default'    => (! empty($general_data) ? (isset($general_data['general_product_link_open_type']) ? $general_data['general_product_link_open_type'] : 'new') : 'new'),
                'options'    => array(
                    'new'  => esc_html__('New Tab', PREFIX_ITWPT_TEXTDOMAIN),
                    'self' => esc_html__('Self Tab', PREFIX_ITWPT_TEXTDOMAIN),
                ),
                'dependency' => array(
                    'element' => 'add_settings_product_link',
                    'value'   => '1',
                    'not'     => false
                ),
            ),



            'add_settings_light_box'              => array(
                'type'    => 'dropdown',
                'heading' => esc_html__('Thumbs Image LightBox', PREFIX_ITWPT_TEXTDOMAIN),
                'default' => (! empty($general_data) ? (isset($general_data['general_thumbs_img_light_box']) ? $general_data['general_thumbs_img_light_box'] : '1') : '1'),
                'options' => array(
                    '0' => esc_html__('Disable', PREFIX_ITWPT_TEXTDOMAIN),
                    '1' => esc_html__('Enable', PREFIX_ITWPT_TEXTDOMAIN),
                ),
            ),


            'general_thumbs_img_size' => array(
                'type'    => 'dropdown',
                'heading' => esc_html__('Thumbs Image Size', PREFIX_ITWPT_TEXTDOMAIN),
                'default' => (! empty($general_data) ? (isset($general_data['general_thumbs_img_size']) ? $general_data['general_thumbs_img_size'] : 'woocommerce_thumbnail') : 'woocommerce_thumbnail'),
                'options' => array(
                    'woocommerce_thumbnail'         => esc_html__('Thumbnail - Use in Shop page',
                        PREFIX_ITWPT_TEXTDOMAIN),
                    'woocommerce_single'            => esc_html__('Single - Use in single page',
                        PREFIX_ITWPT_TEXTDOMAIN),
                    'woocommerce_gallery_thumbnail' => esc_html__('Gallery Thumbnail - Use in single page',
                        PREFIX_ITWPT_TEXTDOMAIN),
                    'full'                          => esc_html__('Full', PREFIX_ITWPT_TEXTDOMAIN),
                ),
            ),


            'add_settings_mini_cart_op_heading'       => array(
                'type'    => 'heading',
                'heading' => esc_html__('Mini Cart Options', PREFIX_ITWPT_TEXTDOMAIN),
            ),
            'add_checklist_mini_cart_position'        => array(
                'type'    => 'checkbox',
                'heading' => esc_html__('Min Cart Positions', PREFIX_ITWPT_TEXTDOMAIN),
                'options' => array(
                    array(
                        'text'   => esc_html__('Table Header', PREFIX_ITWPT_TEXTDOMAIN),
                        'value'  => 'tbl_header',
                        'active' => false
                    ),
                    array(
                        'text'   => esc_html__('Table Footer', PREFIX_ITWPT_TEXTDOMAIN),
                        'value'  => 'tbl_footer',
                        'active' => false
                    ),
                ),
            ),

            'add_settings_other_op_heading'           => array(
                'type'    => 'heading',
                'heading' => esc_html__('Other Options', PREFIX_ITWPT_TEXTDOMAIN),
            ),

            'add_checklist_column_hide_header_footer' => array(
                'type'    => 'checkbox',
                'heading' => esc_html__('Column Title In Header/Footer', PREFIX_ITWPT_TEXTDOMAIN),
                'options' => array(
                    array(
                        'text'   => esc_html__('Hide Table Head', PREFIX_ITWPT_TEXTDOMAIN),
                        'value'  => 'hide-header',
                        'active' => false
                    ),
                    array(
                        'text'   => esc_html__('Hide Table Footer', PREFIX_ITWPT_TEXTDOMAIN),
                        'value'  => 'hide-footer',
                        'active' => false
                    ),
                ),
            ),

            'add_settings_popup_notice'               => array(
                'type'    => 'dropdown',
                'heading' => esc_html__('Popup Notice [New]', PREFIX_ITWPT_TEXTDOMAIN),
                'default' => (! empty($general_data) ? (isset($general_data['general_popup_notice']) ? $general_data['general_popup_notice'] : '1') : '1'),
                'options' => array(
                    '0' => esc_html__('Disable', PREFIX_ITWPT_TEXTDOMAIN),
                    '1' => esc_html__('Enable', PREFIX_ITWPT_TEXTDOMAIN),
                ),
            ),

            'video-link'                => array(
                'type'     => 'video-link',
                'link'     => 'https://www.youtube.com/watch?v=iDzRlCXoxoU',
                'download' => 'https://codecanyon.net/item/woocommerec-product-table/25871270?s_rank=1',
                'text'     => 'Variations/Group Add to cart are available in Pro Version',
            ),

            'add_checklist_custom_class_table'        => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('Custom Class', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Example: ClassA ClassB', PREFIX_ITWPT_TEXTDOMAIN),
            ),
        ),
        'help'   => array(
            'section' => array(
                'header'      => esc_html__('help?', PREFIX_ITWPT_TEXTDOMAIN),
                'description' => esc_html__('1- Product Options: We have some options for each table row, such as:
                a.	Description Type: You can show “Excerpt” or “Content” of product as description.
b.	Description Length: How much content to display?
c.	Product Link: Set product title be linkable or not.
d.	Thumbs Image Light Box: Display product image in LightBox
e.	Product Title in One Line: Display wrapped product title
2-	Mini Cart Options: 
There are 4 types of Mini Cart.
a-	Header: Display Cart above table.
b-	Footer: Display Cart below table.
c-	Floating Cart: Display cart as floating mode beside (you can set position) table.
d-	Side Mode: Display cart as side mode.
You can set “Floating Cart Position” and “Cart Size” too.
3-	Other Options: There are some remaining options for table management.
a-	Ajax Action: If you “Enable” it, “Add to Cart” action will be done as Ajax mode, otherwise It will be redirect to Product Single page.
b-	Show/Hide table title in Header and Footer
c-	Show “Select All Checkbox”: We have a check box for each row, User have to select one by one, if user want select all products, but user could click this check box (Select All) and select all of products at same time.
d-	“Group Add to Cart” Button: User can add all of selected product to cart, by one click.
e-	Show Variation in Action Column: There is an amazing popup for variable products, after click it appears all of variations. User can select variation set and then add to cart.
f-	Popup Notice: Show a notice when a new item is added to the card.
g-	Direct Checkout Page: If “Enable”, after click on “Add to Cart “, it will be redirect to checkout page directly.
h-	Quick Buy:  After Click On Product Add to Cart Redirect to Checkout Page.
i-	Sticky Column: If You Enable Sticky, "Add To Cart" And "Check" Columns Will Be Appeared In Sticky Area.',
                    PREFIX_ITWPT_TEXTDOMAIN),
            ),
        )
    ),
    'localization' => array(
        'fields' => array(
            'add_localization_add_to_cart_text'                     => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('Add to cart Text', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_add_to_cart_text']) ? $general_data['general_add_to_cart_text'] : esc_html__('Add To Cart',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Add To Cart', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_add_to_cart_added_text'               => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('Add to cart (Added) Text', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_add_to_cart_added_text']) ? $general_data['general_add_to_cart_added_text'] : esc_html__('Added',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Added', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_add_to_cart_adding_text'              => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('Add to cart (Adding..) Text', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_add_to_cart_adding_text']) ? $general_data['general_add_to_cart_adding_text'] : esc_html__('Adding...',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Adding...', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_add_to_cart_selected_text'            => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('Add to cart (Selected) Text', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_add_to_cart_selected_text']) ? $general_data['general_add_to_cart_selected_text'] : esc_html__('Add To Cart Products',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Add To Cart Products', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_all_check_uncheck_text'               => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('All Check/Uncheck Text', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_all_check_uncheck_text']) ? $general_data['general_all_check_uncheck_text'] : esc_html__('Select All',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Select All', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_label_text_heading'                   => array(
                'type'    => 'heading',
                'heading' => esc_html__('Label Text', PREFIX_ITWPT_TEXTDOMAIN),
            ),
            'add_localization_load_more_text'                       => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('[Load More] - Button Text', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_load_more_text']) ? $general_data['general_load_more_text'] : esc_html__('Load more',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Load more', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_pagination_next_text'                 => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('Next Pagination Button Text', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_pagination_next_text']) ? $general_data['general_pagination_next_text'] : esc_html__('Next',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Next', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_pagination_prev_text'                 => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('Previous Pagination Button Text', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_pagination_prev_text']) ? $general_data['general_pagination_prev_text'] : esc_html__('Previous',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Previous', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_search_text'                          => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('[Search] - Button Text', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_search_text']) ? $general_data['general_search_text'] : esc_html__('Search',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Search', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_search_keyword_text'                  => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('[Search Keyword] - Text', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_search_keyword_text']) ? $general_data['general_search_keyword_text'] : esc_html__('Search Keyword',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Search Keyword', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_loading_button_text'                  => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('[Loading..] - Button Text', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_loading_button_text']) ? $general_data['general_loading_button_text'] : esc_html__('Loading..',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Loading..', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_item_singular_text'                   => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('Item [for Singular]', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_item_singular_text']) ? $general_data['general_item_singular_text'] : esc_html__('Item',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Item', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_item_plural_text'                     => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('Item [for Plural]', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_item_plural_text']) ? $general_data['general_item_plural_text'] : esc_html__('Items',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Items', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_search_box_order_bay_text'            => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('SearchBox Order By text', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_search_box_order_bay_text']) ? $general_data['general_search_box_order_bay_text'] : esc_html__('Order By',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Order By', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_search_box_order_text'                => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('SearchBox Order text', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_search_box_order_text']) ? $general_data['general_search_box_order_text'] : esc_html__('Order',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Order', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_search_box_min_price'                 => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('SearchBox Min Price', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_search_box_min_price']) ? $general_data['general_search_box_min_price'] : esc_html__('Min Price',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Min Price', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_search_box_max_price'                 => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('SearchBox Max Price', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_search_box_max_price']) ? $general_data['general_search_box_max_price'] : esc_html__('Max Price',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Max Price', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_search_box_status'                    => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('SearchBox Status', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_search_box_status']) ? $general_data['general_search_box_status'] : 'Status') : 'Status'),
            ),
            'add_localization_search_box_sku'                       => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('SearchBox SKU Text', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_search_box_sku']) ? $general_data['general_search_box_sku'] : esc_html__('SKU',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('SKU', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_type_your_message_text'               => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('[Type your Message.] Message', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_type_your_message_text']) ? $general_data['general_type_your_message_text'] : esc_html__('Type your Message.',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Type your Message.', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_sticky_label'                         => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('Sticky Column Label Text', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_label_sticky']) ? $general_data['general_label_sticky'] : esc_html__('Action',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Action', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_fix_button_open_text'                 => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('Fixed Cart - Open Cart Text', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_fix_button_open_text']) ? $general_data['general_fix_button_open_text'] : esc_html__('Open Cart',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Open Cart', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_fix_button_close_text'                => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('Fixed Cart - Close Cart Text', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_fix_button_close_text']) ? $general_data['general_fix_button_close_text'] : esc_html__('Close Cart',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Close Cart', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_cart_label'                           => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('Cart Label Text', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_cart_label']) ? $general_data['general_cart_label'] : esc_html__('Cart',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Cart', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_cart_clear'                           => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('Clear Cart Button Text', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_cart_clear']) ? $general_data['general_cart_clear'] : esc_html__('Cart Clear',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Cart Clear', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_cart_subtotal'                        => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('Cart Subtotal Text', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_cart_subtotal']) ? $general_data['general_cart_subtotal'] : esc_html__('Subtotal',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Subtotal', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_cart_checkout'                        => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('Cart Check Out Text', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_cart_checkout']) ? $general_data['general_cart_checkout'] : esc_html__('Check Out',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Check Out', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_cart_view_cart'                       => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('View Cart Text', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_cart_view_cart']) ? $general_data['general_cart_view_cart'] : esc_html__('View Cart',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('View Cart', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_cart_item_number'                     => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('Items Number Cart Text', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_cart_item_number']) ? $general_data['general_cart_item_number'] : esc_html__('Items In Cart',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Items In Cart', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_cart_empty'                           => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('Empty Cart Message', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_cart_empty']) ? $general_data['general_cart_empty'] : esc_html__('Empty Cart',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Empty Cart', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_table_default_content_heading'        => array(
                'type'    => 'heading',
                'heading' => esc_html__('TABLE\'S DEFAULT CONTENT ', PREFIX_ITWPT_TEXTDOMAIN),
            ),
            'add_localization_in_stock_text'                        => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('[In Stock] for Table Column', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_in_stock_text']) ? $general_data['general_in_stock_text'] : esc_html__('In Stock',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('In Stock', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_out_of_stock_text'                    => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('[Out of Stock] for Table Column', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_out_of_stock_text']) ? $general_data['general_out_of_stock_text'] : esc_html__('Out of Stock',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Out of Stock', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_on_back_order_text'                   => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('[On Back Order] for Table Column	', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_on_back_order_text']) ? $general_data['general_on_back_order_text'] : esc_html__('On Back Order',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('On Back Order', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_all_messages_heading'                 => array(
                'type'    => 'heading',
                'heading' => esc_html__('ALL MESSAGES', PREFIX_ITWPT_TEXTDOMAIN),
            ),
            'add_localization_product_not_founded_text'             => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('[Products Not founded!] - Message Text', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_product_not_founded_text']) ? $general_data['general_product_not_founded_text'] : esc_html__('Products Not founded!',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Products Not founded!', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_variation_not_available_text'         => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('Variations [Not available] Message', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_on_back_order_text']) ? $general_data['general_on_back_order_text'] : esc_html__('On Back Order',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('On Back Order', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_variation_is_not_set_text'            => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('[Product variations is not set Properly. May be: price is not inputted. may be: Out of Stock.] Message',
                    PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_variation_is_not_set_text']) ? $general_data['general_variation_is_not_set_text'] : esc_html__('Product variations is not set Properly. May be: price is not inputted. may be: Out of Stock.',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Product variations is not set Properly. May be: price is not inputted. may be: Out of Stock.',
                    PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_select_all_item_text'                 => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('[Please select all items.] Message', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_select_all_item_text']) ? $general_data['general_select_all_item_text'] : esc_html__('Please select all items.',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Please select all items.', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_out_of_stock_message_text'            => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('[Out of Stock] Message', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_out_of_stock_message_text']) ? $general_data['general_out_of_stock_message_text'] : esc_html__('Out of Stock',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Out of Stock', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_is_no_more_products_text'             => array(
                'type'        => 'text-box',
                'heading'     => esc_html__(
                    '[There is no more products based on current Query.] Message',
                    PREFIX_ITWPT_TEXTDOMAIN
                ),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_is_no_more_products_text']) ? $general_data['general_is_no_more_products_text'] : esc_html__('There is no more products based on current Query.',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('There is no more products based on current Query.',
                    PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_no_right_combination_text'            => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('[ No Right Combination ] Message', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_no_right_combination_text']) ? $general_data['general_no_right_combination_text'] : esc_html__('No Right Combination',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('No Right Combination', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_please_choose_right_combination_text' => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('[ Sorry, Please choose right combination. ] Message',
                    PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_please_choose_right_combination_text']) ? $general_data['general_please_choose_right_combination_text'] : esc_html__('Sorry, Please choose right combination.',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Sorry, Please choose right combination.',
                    PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_cart_update'                          => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('Cart Update Message', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_cart_update']) ? $general_data['general_cart_update'] : esc_html__('Cart has been Updated',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Cart has been Updated', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_can_not_cart_update'                  => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('Error in Cart Update Message', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_can_not_cart_update']) ? $general_data['general_can_not_cart_update'] : esc_html__('Error! Cart Could not be Updated',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Error! Cart Could not be Updated',
                    PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_product_added'                        => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('Product Added Message', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_product_added']) ? $general_data['general_product_added'] : esc_html__('Product has been Added',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Product has been Added', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_can_not_product_added'                => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('Error in Add Product Message', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_can_not_product_added']) ? $general_data['general_can_not_product_added'] : esc_html__('Error! Product Could not be Added',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Error! Product Could not be Added',
                    PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_product_deleted'                      => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('Product Deleted Message', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_product_deleted']) ? $general_data['general_product_deleted'] : esc_html__('Product has been Deleted',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Product has been Deleted', PREFIX_ITWPT_TEXTDOMAIN)),
            ),
            'add_localization_product_deleted_error'                => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('Error in Delete Product Message', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_product_deleted_error']) ? $general_data['general_product_deleted_error'] : esc_html__('Error! Product Could not be Deleted',
                    PREFIX_ITWPT_TEXTDOMAIN)) : esc_html__('Error! Product Could not be Deleted',
                    PREFIX_ITWPT_TEXTDOMAIN)),
            ),
        ),
        'help'   => array(
            'section' => array(
                'header'      => esc_html__('help?', PREFIX_ITWPT_TEXTDOMAIN),
                'description' => esc_html__('Translate determine fields directly without WPML plugins.',
                    PREFIX_ITWPT_TEXTDOMAIN),
            ),
        )
    ),
);

/**
 * TODO PAGE TEMPLATE FORM
 */
$admin_template_form     = array(
    'fields' => array(
        // TODO TEMPLATE MAIN
        'tmp_name'                                      => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('Template Name', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Name', PREFIX_ITWPT_TEXTDOMAIN),
            'responsive'  => array(
                'desktop' => 12,
                'laptop'  => 12,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'tmp_image'                                     => array(
            'type'         => 'media',
            'heading'      => esc_html__('Template Image', PREFIX_ITWPT_TEXTDOMAIN),
            'multi-select' => false,
            'responsive'   => array(
                'desktop' => 12,
                'laptop'  => 12,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ), // TODO TEMPLATE - CART
        'cart_heading'                                  => array(
            'type'    => 'heading',
            'heading' => esc_html__('Cart', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'cart_background_color'                         => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Background Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#ffffff',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'cart_title_color'                              => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Title Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#222',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'cart_meta_color'                               => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Meta Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#222',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'cart_button_fixed_background_color'            => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Button Fixed Background Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#222',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'cart_button_fixed_text_color'                  => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Button Fixed Text Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#ffffff',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'cart_button_background_color'                  => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Button Background Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#f4f4f4',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'cart_button_text_color'                        => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Button Text Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#222',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ), // TODO TEMPLATE - ALARM
        'alarm_heading'                                 => array(
            'type'    => 'heading',
            'heading' => esc_html__('Alarm', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'alarm_success_background_color'                => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Alarm Success Background Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#81d742',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'alarm_success_text_color'                      => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Alarm Success Text Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#ffffff',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'alarm_error_background_color'                  => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Alarm Error Background Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#dd3333',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'alarm_error_text_color'                        => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Alarm Error Text Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#ffffff',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ), // TODO TEMPLATE - CONTROL BOX
        'crl_box_heading'                               => array(
            'type'    => 'heading',
            'heading' => esc_html__('Control Box', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'crl_box_background_boxs'                       => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Background For Box', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#fff',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'crl_box_text_color_boxs'                       => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Text Color For Box', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#222',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'crl_box_text_background_btn'                   => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Background Buttons', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#f4f4f4',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'crl_box_text_text_color_btn'                   => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Text Color Buttons', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#222',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'crl_box_text_background_btn_hover'             => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Background Buttons(Hover)', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#e2e2e2',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'crl_box_text_text_color_btn_hover'             => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Text Color Buttons(Hover)', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#222',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ), // TODO TEMPLATE - TABLET HEADER AND FOOTER
        'header_footer_heading'                         => array(
            'type'    => 'heading',
            'heading' => esc_html__('Table Header', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'header_footer_background_color'                => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Background Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#ffffff',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'header_footer_border_color'                    => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Border Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#f2f2f2',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'header_footer_text_color'                      => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Text Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#666666',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'header_footer_border_width'                    => array(
            'type'        => 'number',
            'heading'     => esc_html__('Border Width', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Set Border Width', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => 1,
            'min'         => 0,
            'responsive'  => array(
                'desktop' => 12,
                'laptop'  => 12,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'header_footer_padding'                         => array(
            'type'        => 'number',
            'heading'     => esc_html__('Padding', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Set Padding', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => 14,
            'min'         => 0,
            'responsive'  => array(
                'desktop' => 12,
                'laptop'  => 12,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'header_footer_text_alignment'                  => array(
            'type'       => 'dropdown',
            'heading'    => esc_html__('Text Alignment', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => 'center',
            'options'    => array(
                'inherit' => esc_html__('Inherit', PREFIX_ITWPT_TEXTDOMAIN),
                'initial' => esc_html__('Initial', PREFIX_ITWPT_TEXTDOMAIN),
                'left'    => esc_html__('Left', PREFIX_ITWPT_TEXTDOMAIN),
                'center'  => esc_html__('Center', PREFIX_ITWPT_TEXTDOMAIN),
                'right'   => esc_html__('Right', PREFIX_ITWPT_TEXTDOMAIN),
                'justify' => esc_html__('Justify', PREFIX_ITWPT_TEXTDOMAIN),
            ),
            'responsive' => array(
                'desktop' => 12,
                'laptop'  => 12,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'header_footer_text_transform'                  => array(
            'type'       => 'dropdown',
            'heading'    => esc_html__('Text Transform', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => 'uppercase',
            'options'    => array(
                'inherit'    => esc_html__('Inherit', PREFIX_ITWPT_TEXTDOMAIN),
                'initial'    => esc_html__('Initial', PREFIX_ITWPT_TEXTDOMAIN),
                'capitalize' => esc_html__('Capitalize', PREFIX_ITWPT_TEXTDOMAIN),
                'uppercase'  => esc_html__('Uppercase', PREFIX_ITWPT_TEXTDOMAIN),
                'lowercase'  => esc_html__('Lowercase', PREFIX_ITWPT_TEXTDOMAIN),
                'unset'      => esc_html__('Unset', PREFIX_ITWPT_TEXTDOMAIN),
                'none'       => esc_html__('None', PREFIX_ITWPT_TEXTDOMAIN),
            ),
            'responsive' => array(
                'desktop' => 12,
                'laptop'  => 12,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'header_footer_font_size'                       => array(
            'type'        => 'number',
            'heading'     => esc_html__('Font Size', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Set ', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => 12,
            'min'         => 0,
            'responsive'  => array(
                'desktop' => 12,
                'laptop'  => 12,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ), // TODO :: TEMPLATE - TABLET BODY
        'body_heading'                                  => array(
            'type'    => 'heading',
            'heading' => esc_html__('Table Body', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'body_background_color'                         => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Background Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#ffffff',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'body_hover_background_color'                   => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Hover Background Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#f7f7f7',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'body_border_color'                             => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Border Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#f9f9f9',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'body_text_color'                               => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Text Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#666666',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'body_link_color'                               => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Link Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#666666',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'body_hover_link_color'                         => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Hover Link Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#1e73be',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'body_strip_background_color'                   => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Strip Background Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#fcfcfc',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'body_strip_background_hover_color'             => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Strip Background Color(Hover)', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#f7f7f7',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'body_strip_text_color'                         => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Strip Text Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#666666',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'body_sorted_column_bg_color'                   => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Sorted Column BG Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#fcfcfc',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'body_td_padding'                               => array(
            'type'        => 'number',
            'heading'     => esc_html__('TD Padding', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Set Padding', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => 12,
            'min'         => 0,
            'responsive'  => array(
                'desktop' => 12,
                'laptop'  => 12,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'body_td_border_width'                          => array(
            'type'        => 'number',
            'heading'     => esc_html__('Border Width', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Set Width', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => 0,
            'min'         => 0,
            'responsive'  => array(
                'desktop' => 12,
                'laptop'  => 12,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ), // TODO TEMPLATE - CHECKBOX STYLE
        'checkbox_heading'                              => array(
            'type'    => 'heading',
            'heading' => esc_html__('Table Checkbox Style', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'checkbox_color'                                => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Checkbox Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#ffffff',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'checkbox_border_color'                         => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Checkbox Border Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#353535',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'checkbox_sign_color'                           => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Checkbox Sign Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#353535',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ), // TODO TEMPLATE - BUTTON VARIATION STYLE
        'variation_button_heading'                      => array(
            'type'    => 'heading',
            'heading' => esc_html__('Variation Button Style', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'variation_button_background_color'             => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Background Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#f4f4f4',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'variation_button_hover_background_color'       => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Hover Background Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#e2e2e2',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'variation_button_text_color'                   => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Text Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#000000',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'variation_button_hover_text_color'             => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Hover Text Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#000000',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ), // TODO TEMPLATE - BUTTON VARIATION STYLE
        'variation_popup_heading'                       => array(
            'type'    => 'heading',
            'heading' => esc_html__('Variation Popup', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'variation_popup_background_color'              => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Background Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#ffffff',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'variation_popup_text_color'                    => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Text Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#161616',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'variation_popup_select_border_color'           => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Select Border Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#e2e2e2',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'variation_popup_select_text_color'             => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Select Text Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#161616',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'variation_popup_button_background_color'       => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Button Background Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#f4f4f4',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'variation_popup_button_text_color'             => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Button Text Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#0a0a0a',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'variation_popup_button_background_color_hover' => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Button Background Color(Hover)', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#e2e2e2',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'variation_popup_button_text_color_hover'       => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Button Text Color(Hover)', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#0a0a0a',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ), // TODO TEMPLATE - ADD TO CART AND VIEW BUTTON
        'button_add_to_cart_heading'                    => array(
            'type'    => 'heading',
            'heading' => esc_html__('Add Button And View Cart Style', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'button_add_to_cart_background_color'           => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Background Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#f4f4f4',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'button_add_to_cart_hover_background_color'     => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Hover Background Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#e2e2e2',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'button_add_to_cart_text_color'                 => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Text Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#000',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'button_add_to_cart_hover_text_color'           => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Hover Text Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#000',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'button_add_to_cart_icon'                       => array(
            'type'    => 'dropdown',
            'heading' => esc_html__('Add to cart icon', PREFIX_ITWPT_TEXTDOMAIN),
            'default' => 'left-icon',
            'options' => array(
                'no-icon'    => esc_html__('No Icon', PREFIX_ITWPT_TEXTDOMAIN),
                'only-icon'  => esc_html__('Only Icon', PREFIX_ITWPT_TEXTDOMAIN),
                'left-icon'  => esc_html__('Left Icon And Text', PREFIX_ITWPT_TEXTDOMAIN),
                'right-icon' => esc_html__('Text And Right Icon', PREFIX_ITWPT_TEXTDOMAIN),
            ),
        ), // TODO TEMPLATE - OTHER BUTTON
        'other_button_heading'                          => array(
            'type'    => 'heading',
            'heading' => esc_html__('Other Button Style', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'other_background_color'                        => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Background Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#f4f4f4',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'other_hover_background_color'                  => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Hover Background Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#e2e2e2',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'other_text_color'                              => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Text Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#0a0a0a',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'other_hover_text_color'                        => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Hover Text Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#0a0a0a',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ), // TODO TEMPLATE - PAGINATION AND LOAD MORE
        'tmp_table_pagination_style'                    => array(
            'type'    => 'heading',
            'heading' => esc_html__('Table Pagination And Load More Style', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'tmp_pagination_background_color'               => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Background Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#ffffff',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'tmp_pagination_text_color'                     => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Text Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#222',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'tmp_pagination_active_background_color'        => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Background Color(Current Page For Pagination)', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#222',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'tmp_pagination_active_text_color'              => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Text Color(Current Page For Pagination)', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#fff',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'tmp_pagination_second_color'                   => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Second Color(For Load More)', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#ededed',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ), // TODO TEMPLATE - SEARCHBOX
        'ads_heading'                                   => array(
            'type'    => 'heading',
            'heading' => esc_html__('Advance Searchbox Field Control', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'ads_column_size_fields'                        => array(
            'type'       => 'dropdown',
            'heading'    => esc_html__('Column Fields', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '2',
            'options'    => array(
                '1'  => esc_html__('1', PREFIX_ITWPT_TEXTDOMAIN),
                '2'  => esc_html__('2', PREFIX_ITWPT_TEXTDOMAIN),
                '3'  => esc_html__('3', PREFIX_ITWPT_TEXTDOMAIN),
                '4'  => esc_html__('4', PREFIX_ITWPT_TEXTDOMAIN),
                '5'  => esc_html__('5', PREFIX_ITWPT_TEXTDOMAIN),
                '6'  => esc_html__('6', PREFIX_ITWPT_TEXTDOMAIN),
                '7'  => esc_html__('7', PREFIX_ITWPT_TEXTDOMAIN),
                '8'  => esc_html__('8', PREFIX_ITWPT_TEXTDOMAIN),
                '9'  => esc_html__('9', PREFIX_ITWPT_TEXTDOMAIN),
                '10' => esc_html__('10', PREFIX_ITWPT_TEXTDOMAIN),
                '11' => esc_html__('11', PREFIX_ITWPT_TEXTDOMAIN),
                '12' => esc_html__('12', PREFIX_ITWPT_TEXTDOMAIN),
            ),
            'responsive' => array(
                'desktop' => 12,
                'laptop'  => 12,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ), // TODO TEMPLATE - OTHERS
        'tmp_table_others'                              => array(
            'type'    => 'heading',
            'heading' => esc_html__('Others', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'other_qty_background_color'                    => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Qty Background Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#2d2d2d',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'other_qty_text_color'                          => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Qty Text Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#ffffff',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'other_in_stock_color'                          => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('In Stock Text Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#00ff00',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'other_out_stock_color'                         => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Out Stock Text Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#ff0000',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'other_back_order_color'                        => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('On Back Order', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#eac220',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'other_out_shadow_color'                        => array(
            'type'       => 'color-picker',
            'heading'    => esc_html__('Shadow Color', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => '#f9f9f9',
            'responsive' => array(
                'desktop' => 6,
                'laptop'  => 6,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'other_thum_shape'                              => array(
            'type'       => 'dropdown',
            'heading'    => esc_html__('Thumbnail Shape', PREFIX_ITWPT_TEXTDOMAIN),
            'default'    => 'q',
            'options'    => array(
                'square' => esc_html__('square', PREFIX_ITWPT_TEXTDOMAIN),
                'q'      => esc_html__('square with radius', PREFIX_ITWPT_TEXTDOMAIN),
                'circle' => esc_html__('circle', PREFIX_ITWPT_TEXTDOMAIN),
            ),
            'responsive' => array(
                'desktop' => 12,
                'laptop'  => 12,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
        'other_thumbs_image_size'                       => array(
            'type'        => 'number',
            'heading'     => esc_html__('Thumbs Image Size [Only Int]', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Size', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => 60,
            'responsive'  => array(
                'desktop' => 12,
                'laptop'  => 12,
                'tablet'  => 12,
                'mobile'  => 12
            ),
        ),
    )
);
$admin_template_selector = array(
    'fields' => array(
        'tmp_template' => array(
            'type'    => 'template',
            'heading' => esc_html__('Select', PREFIX_ITWPT_TEXTDOMAIN),
            'select'  => false,
        ),
    )
);

/**
 * TODO PAGE GENERAL FORM
 */
$general = array(
    'fields' => array(
        'general_footer_cart_position' => array(
            'type'    => 'dropdown',
            'heading' => esc_html__('Floating Cart Position', PREFIX_ITWPT_TEXTDOMAIN),
            'default' => 'bottom-right',
            'options' => array(
                'bottom-right' => esc_html__('Bottom Right', PREFIX_ITWPT_TEXTDOMAIN),
                'bottom-left'  => esc_html__('Bottom Left', PREFIX_ITWPT_TEXTDOMAIN),
                'top-right'    => esc_html__('Top Right', PREFIX_ITWPT_TEXTDOMAIN),
                'top-left'     => esc_html__('Top Left', PREFIX_ITWPT_TEXTDOMAIN),
            ),
        ),
        'general_footer_cart_size'     => array(
            'type'    => 'number',
            'heading' => esc_html__('Floating Cart Size [Only Int]', PREFIX_ITWPT_TEXTDOMAIN),
            'default' => 100,
            'max'     => 120,
            'min'     => 70,
        ),
        'general_popup_notice'         => array(
            'type'    => 'dropdown',
            'heading' => esc_html__('Popup Notice [New]', PREFIX_ITWPT_TEXTDOMAIN),
            'default' => '1',
            'options' => array(
                '0' => esc_html__('Disable', PREFIX_ITWPT_TEXTDOMAIN),
                '1' => esc_html__('Enable', PREFIX_ITWPT_TEXTDOMAIN),
            ),
        ),
        'general_thumbs_img_light_box' => array(
            'type'    => 'dropdown',
            'heading' => esc_html__('Thumbs Image LightBox', PREFIX_ITWPT_TEXTDOMAIN),
            'default' => '1',
            'options' => array(
                '0' => esc_html__('Disable', PREFIX_ITWPT_TEXTDOMAIN),
                '1' => esc_html__('Enable', PREFIX_ITWPT_TEXTDOMAIN),
            ),
        ),

        'general_thumbs_img_size' => array(
            'type'    => 'dropdown',
            'heading' => esc_html__('Thumbs Image Size', PREFIX_ITWPT_TEXTDOMAIN),
            'default' => 'woocommerce_thumbnail',
            'options' => array(
                'woocommerce_thumbnail'         => esc_html__('Thumbnail - Use in Shop page', PREFIX_ITWPT_TEXTDOMAIN),
                'woocommerce_single'            => esc_html__('Single - Use in single page', PREFIX_ITWPT_TEXTDOMAIN),
                'woocommerce_gallery_thumbnail' => esc_html__('Gallery Thumbnail - Use in single page',
                    PREFIX_ITWPT_TEXTDOMAIN),
                'full'                          => esc_html__('Full', PREFIX_ITWPT_TEXTDOMAIN),
            ),
        ),

        'general_disable_product_link'                 => array(
            'type'    => 'dropdown',
            'heading' => esc_html__('Enable Product Link', PREFIX_ITWPT_TEXTDOMAIN),
            'default' => '0',
            'options' => array(
                '0' => esc_html__('No', PREFIX_ITWPT_TEXTDOMAIN),
                '1' => esc_html__('Yes', PREFIX_ITWPT_TEXTDOMAIN),
            ),
        ),
        'general_product_link_open_type'               => array(
            'type'    => 'dropdown',
            'heading' => esc_html__('Product Link Open Type', PREFIX_ITWPT_TEXTDOMAIN),
            'default' => 'new',
            'dependency' => array(
                'element' => 'general_disable_product_link',
                'value'   => '1',
                'not'     => false
            ),
            'options' => array(
                'new'  => esc_html__('New Tab', PREFIX_ITWPT_TEXTDOMAIN),
                'self' => esc_html__('Self Tab', PREFIX_ITWPT_TEXTDOMAIN),
            ),
        ),

        //Added in version 1.4.0
        'general_disable_taxonomy_link'                 => array(
            'type'    => 'dropdown',
            'heading' => esc_html__('Enable Category/Tag Link', PREFIX_ITWPT_TEXTDOMAIN),
            'default' => '0',
            'options' => array(
                '0' => esc_html__('No', PREFIX_ITWPT_TEXTDOMAIN),
                '1' => esc_html__('Yes', PREFIX_ITWPT_TEXTDOMAIN),
            ),
        ),
        'general_taxonomy_link_open_type'               => array(
            'type'    => 'dropdown',
            'heading' => esc_html__('Category/Tag Link Open Type', PREFIX_ITWPT_TEXTDOMAIN),
            'default' => 'new',
            'dependency' => array(
                'element' => 'general_disable_taxonomy_link',
                'value'   => '1',
                'not'     => false
            ),
            'options' => array(
                'new'  => esc_html__('New Tab', PREFIX_ITWPT_TEXTDOMAIN),
                'self' => esc_html__('Self Tab', PREFIX_ITWPT_TEXTDOMAIN),
            ),
        ),

        'general_direct_checkout_page'                 => array(
            'type'    => 'dropdown',
            'heading' => esc_html__('Direct Checkout Page[for Add to cart Selected]', PREFIX_ITWPT_TEXTDOMAIN),
            'default' => '0',
            'options' => array(
                '0' => esc_html__('No', PREFIX_ITWPT_TEXTDOMAIN),
                '1' => esc_html__('Yes', PREFIX_ITWPT_TEXTDOMAIN),
            ),
        ),
        'general_enable_quick_buy_button'              => array(
            'type'    => 'dropdown',
            'heading' => esc_html__('Enable Quick Buy Button [Direct Checkout Page for each product]',
                PREFIX_ITWPT_TEXTDOMAIN),
            'default' => '0',
            'options' => array(
                '0' => esc_html__('No', PREFIX_ITWPT_TEXTDOMAIN),
                '1' => esc_html__('Yes', PREFIX_ITWPT_TEXTDOMAIN),
            ),
        ),
        'general_label_table_display_heading'          => array(
            'type'    => 'heading',
            'heading' => esc_html__('Table display', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_label_table_display_shop_page'        => array(
            'type'    => 'dropdown',
            'heading' => esc_html__('Shop page', PREFIX_ITWPT_TEXTDOMAIN),
            'default' => '0',
            'options' => array(
                '0' => esc_html__('No', PREFIX_ITWPT_TEXTDOMAIN),
                '1' => esc_html__('Yes', PREFIX_ITWPT_TEXTDOMAIN),
            ),
        ),
        'general_label_table_display_archive_page'     => array(
            'type'    => 'dropdown',
            'heading' => esc_html__('Product category archives', PREFIX_ITWPT_TEXTDOMAIN),
            'default' => '0',
            'options' => array(
                '0' => esc_html__('No', PREFIX_ITWPT_TEXTDOMAIN),
                '1' => esc_html__('Yes', PREFIX_ITWPT_TEXTDOMAIN),
            ),
        ),
        'general_label_table_display_shortcode_id'     => array(
            'type'    => 'dropdown',
            'heading' => esc_html__('Select Product Table', PREFIX_ITWPT_TEXTDOMAIN),
            'default' => '0',
            'options' => Itwpt_Get_Data_Product_Table(),
        ),
        'general_label_text_heading'                   => array(
            'type'    => 'heading',
            'heading' => esc_html__('Label Text', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_add_to_cart_text'                     => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('Add to cart Text', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Add To Cart', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_add_to_cart_added_text'               => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('Add to cart (Added) Text', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Added', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_add_to_cart_adding_text'              => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('Add to cart (Adding..) Text', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Adding...', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_add_to_cart_selected_text'            => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('Add to cart (Selected) Text', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Add To Cart Products', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_all_check_uncheck_text'               => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('All Check/Uncheck Text', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Select All', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_load_more_text'                       => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('[Load More] - Button Text', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Load more', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_pagination_next_text'                 => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('Next Pagination Button Text', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Next', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_pagination_prev_text'                 => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('Previous Pagination Button Text', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Previous', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_search_text'                          => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('[Search] - Button Text', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Search', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_search_keyword_text'                  => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('[Search Keyword] - Text', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Search Keyword', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_loading_button_text'                  => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('[Loading..] - Button Text', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Loading..', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_item_singular_text'                   => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('Item [for Singular]', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Item', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_item_plural_text'                     => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('Item [for Plural]', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Items', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_search_box_order_bay_text'            => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('SearchBox Order By text', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Order By', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_search_box_order_text'                => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('SearchBox Order text', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Order', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_search_box_min_price'                 => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('SearchBox Min Price', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Min Price', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_search_box_max_price'                 => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('SearchBox Max Price', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Max Price', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_search_box_status'                    => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('SearchBox Status', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Status', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_search_box_sku'                       => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('SearchBox SKU Text', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('SKU', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_type_your_message_text'               => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('[Type your Message.] Message', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Type your Message.', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_label_sticky'                         => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('Sticky Column Label Text', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Action', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_fix_button_open_text'                 => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('Open Cart Text Fixed Button Text', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Open Cart', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_fix_button_close_text'                => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('Close Cart Text Fixed Button Text', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Close Cart', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_cart_label'                           => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('Cart Label Text', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Cart', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_cart_clear'                           => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('Clear Cart Button Text', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Clear Cart', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_cart_subtotal'                        => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('Cart Subtotal Text', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Subtotal', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_cart_checkout'                        => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('Cart Check Out Text', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Check Out', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_cart_view_cart'                       => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('View Cart Text', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('View Cart', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_cart_item_number'                     => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('Items Number Cart Text', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Items In Cart', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_cart_empty'                           => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('Empty Cart Message', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Empty Cart', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_cart_update'                          => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('Cart Update Message', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Cart Updated', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_can_not_cart_update'                  => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('Can Not Cart Update Message', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Can Not Cart Updated', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_product_added'                        => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('Product Added Message', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Product Added', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_can_not_product_added'                => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('Can Not Product Add Message', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Can Not Product Add', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_product_deleted'                      => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('Product Deleted Message', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Product Deleted', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_product_deleted_error'                => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('Con Not Product Deleted Message', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Con Not Product Deleted', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_yith_heading'                         => array(
            'type'    => 'heading',
            'heading' => esc_html__('YITH PLUGINS ', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_yith_quick_view'                      => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('[Quick View] - Button Text', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Quick View', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_yith_wish_list'                       => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('[Wishlist] - Button Text', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Wish list', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_table_default_content_heading'        => array(
            'type'    => 'heading',
            'heading' => esc_html__('TABLE\'S DEFAULT CONTENT ', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_in_stock_text'                        => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('[In Stock] for Table Column', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('In Stock', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_out_of_stock_text'                    => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('[Out of Stock] for Table Column', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Out of Stock', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_on_back_order_text'                   => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('[On Back Order] for Table Column	', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('On Back Order', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_all_messages_heading'                 => array(
            'type'    => 'heading',
            'heading' => esc_html__('ALL MESSAGES', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_product_not_founded_text'             => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('[Products Not founded!] - Message Text', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Products Not founded!', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_variation_not_available_text'         => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('Variations [Not available] Message', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Not available', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_variation_is_not_set_text'            => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('[Product variations is not set Properly. May be: price is not inputted. may be: Out of Stock.] Message',
                PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Product variations is not set Properly. May be: price is not inputted. may be: Out of Stock.',
                PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_select_all_item_text'                 => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('[Please select all items.] Message', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Please select all items.', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_out_of_stock_message_text'            => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('[Out of Stock] Message', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Out of Stock', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_is_no_more_products_text'             => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('[There is no more products based on current Query.] Message',
                PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('There is no more products based on current Query.', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_no_right_combination_text'            => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('[ No Right Combination ] Message', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('No Right Combination', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'general_please_choose_right_combination_text' => array(
            'type'        => 'text-box',
            'heading'     => esc_html__('[ Sorry, Please choose right combination. ] Message', PREFIX_ITWPT_TEXTDOMAIN),
            'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
            'default'     => esc_html__('Sorry, Please choose right combination.', PREFIX_ITWPT_TEXTDOMAIN),
        ),
    ),
);

/**
 * TODO FORM YITH LOCALIZE
 */
if (defined('YITH_WCQV_VERSION') || defined('YITH_WCWL') || defined("TINVWL_URL") || class_exists('ACF') || class_exists('YITH_Woocompare') || class_exists('WC_Products_Compare')) {

    $yith_compare       = '';
    $quick_view_default = '';
    $wishlist_default   = '';
    $quote_default      = '';

    $admin_form['localization']['fields'] = array_merge($admin_form['localization']['fields'], array(
        'add_localization_yith_heading' => array(
            'type'    => 'heading',
            'heading' => esc_html__('EXTERNAL PLUGIN\'S [YITH]', PREFIX_ITWPT_TEXTDOMAIN),
        ),
    ));

    if (defined('YITH_WCQV_VERSION')) {

        $admin_form['localization']['fields'] = array_merge($admin_form['localization']['fields'], array(
            'add_localization_yith_quick_view' => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('[Quick View] - Button Text', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_yith_quick_view']) ? $general_data['general_yith_quick_view'] : 'Quick View') : 'Quick View'),
            ),
        ));

        $admin_form['columns']['fields']['add_column_table_column']['options'] =
            array_merge(
                $admin_form['columns']['fields']['add_column_table_column']['options'],
                array(
                    'quick-view' => array(
                        'default'     => esc_html__('Quick View', PREFIX_ITWPT_TEXTDOMAIN),
                        'placeholder' => esc_html__('Quick View', PREFIX_ITWPT_TEXTDOMAIN),
                        "type"        => "-",
                        "desktop"     => false,
                        "laptop"      => false,
                        "mobile"      => false
                    ),
                )
            );

        $quick_view_default = ',{"type": "-","value": "quick-view","text": "Quick View","placeholder": "Quick View","desktop": "","laptop": "","mobile": ""}';
    }

    //Version 1.6.1
    //COMPATIBL'E WITH Yith  Compare
    if (class_exists('YITH_Woocompare')) {

        $admin_form['localization']['fields'] = array_merge($admin_form['localization']['fields'], array(
            'add_localization_yith_compare' => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('[Compare] - Button Text', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_yith_compare']) ? $general_data['general_compare'] : 'yith Compare') : 'yith Compare'),
            ),
        ));

        $admin_form['columns']['fields']['add_column_table_column']['options'] =
            array_merge(
                $admin_form['columns']['fields']['add_column_table_column']['options'],
                array(
                    'yith-compare' => array(
                        'default'     => esc_html__('yith Compare', PREFIX_ITWPT_TEXTDOMAIN),
                        'placeholder' => esc_html__('yith Compare', PREFIX_ITWPT_TEXTDOMAIN),
                        "type"        => "-",
                        "desktop"     => false,
                        "laptop"      => false,
                        "mobile"      => false
                    ),
                )
            );

        $yith_compare = ',{"type": "-","value": "yith-compare","text": "yith Compare","placeholder": "yith Compare","desktop": "active","laptop": "active","mobile": "active"}';
    }

    //Version 1.5.0
    //COMPATIBL'E WITH WooThem  Compare
    if (class_exists('WC_Products_Compares')) {

        $admin_form['localization']['fields'] = array_merge($admin_form['localization']['fields'], array(
            'add_localization_woothem_compare' => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('[Compare] - Button Text', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_woothem_compare']) ? $general_data['general_compare'] : 'Woo Compare') : 'Woo Compare'),
            ),
        ));

        $admin_form['columns']['fields']['add_column_table_column']['options'] =
            array_merge(
                $admin_form['columns']['fields']['add_column_table_column']['options'],
                array(
                    'woothem-compare' => array(
                        'default'     => esc_html__('Woo Compare', PREFIX_ITWPT_TEXTDOMAIN),
                        'placeholder' => esc_html__('Woo Compare', PREFIX_ITWPT_TEXTDOMAIN),
                        "type"        => "-",
                        "desktop"     => false,
                        "laptop"      => false,
                        "mobile"      => false
                    ),
                )
            );

        $yith_compare = ',{"type": "-","value": "woothem-compare","text": "Woo Compare","placeholder": "Woo Compare","desktop": "active","laptop": "active","mobile": "active"}';
    }

    if (defined('YITH_WCWL')) {

        $admin_form['localization']['fields'] = array_merge($admin_form['localization']['fields'], array(
            'add_localization_yith_wish_list' => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('[Wishlist] - Button Text', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_yith_wish_list']) ? $general_data['general_yith_wish_list'] : 'Wishlist') : 'Wishlist'),
            ),
        ));

        $admin_form['columns']['fields']['add_column_table_column']['options'] =
            array_merge(
                $admin_form['columns']['fields']['add_column_table_column']['options'],
                array(
                    'wish-list' => array(
                        'default'     => esc_html__('Wish List', PREFIX_ITWPT_TEXTDOMAIN),
                        'placeholder' => esc_html__('Wish List', PREFIX_ITWPT_TEXTDOMAIN),
                        "type"        => "-",
                        "desktop"     => true,
                        "laptop"      => true,
                        "mobile"      => true
                    ),
                )
            );

        $wishlist_default = ',{"type": "-","value": "wish-list","text": "Wish List","placeholder": "Wish List","desktop": "active","laptop": "active","mobile": "active"}';
    }

    if (false) {

        $admin_form['localization']['fields'] = array_merge($admin_form['localization']['fields'], array(
            'add_localization_yith_quote_list'   => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('[Browse Quote list] - text', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_yith_quote_list']) ? $general_data['general_yith_quote_list'] : 'Quote List') : 'Quote List'),
            ),
            'add_localization_yith_quote_button' => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('[Add to Quote] - button text', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_yith_quote_button']) ? $general_data['general_yith_quote_button'] : 'Quote Button') : 'Quote Button'),
            ),
            'add_localization_yith_adding'       => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('[Quote Adding] - text', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_yith_adding']) ? $general_data['general_yith_adding'] : 'Adding') : 'Adding'),
            ),
            'add_localization_yith_added'        => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('[Quote Added] - text', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_yith_added']) ? $general_data['general_yith_added'] : 'Added') : 'Added')
            ),
        ));

        $admin_form['columns']['fields']['add_column_table_column']['options'] =
            array_merge(
                $admin_form['columns']['fields']['add_column_table_column']['options'],
                array(
                    'quote-request' => array(
                        'default'     => esc_html__('Quote Request', PREFIX_ITWPT_TEXTDOMAIN),
                        'placeholder' => esc_html__('Quote Request', PREFIX_ITWPT_TEXTDOMAIN),
                        "type"        => "-",
                        "desktop"     => false,
                        "laptop"      => false,
                        "mobile"      => false
                    ),
                )
            );

        $quote_default = ',{"type":"-","value":"quote-request","text":"Quote Request","placeholder":"Quote Request","desktop":"","laptop":"","mobile":""}';
    }

    //Version 1.2.0
    //COMPATIBL'E WITH TI WishList
    $ti_wishlist_default = '';
    if (defined("TINVWL_URL")) {
        $admin_form['localization']['fields'] = array_merge($admin_form['localization']['fields'], array(
            'add_localization_ti_wish_list' => array(
                'type'        => 'text-box',
                'heading'     => esc_html__('[Wishlist] - Button Text', PREFIX_ITWPT_TEXTDOMAIN),
                'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                'default'     => (! empty($general_data) ? (isset($general_data['general_ti_wish_list']) ? $general_data['general_ti_wish_list'] : 'Wishlist') : 'Wishlist'),
            ),
        ));

        $admin_form['columns']['fields']['add_column_table_column']['options'] =
            array_merge(
                $admin_form['columns']['fields']['add_column_table_column']['options'],
                array(
                    'ti-wish-list' => array(
                        'default'     => esc_html__('Wish List', PREFIX_ITWPT_TEXTDOMAIN),
                        'placeholder' => esc_html__('Wish List', PREFIX_ITWPT_TEXTDOMAIN),
                        "type"        => "-",
                        "desktop"     => true,
                        "laptop"      => true,
                        "mobile"      => true
                    ),
                )
            );

        $ti_wishlist_default = ',{"type": "-","value": "ti-wish-list","text": "Wish List","placeholder": "Wish List","desktop": "active","laptop": "active","mobile": "active"}';
    }


    //Version 1.1.0
    //COMPATIBLE WITH ACF
    $acf_default = '';
    if (class_exists('ACF')) :
        $groups = acf_get_field_groups(array('post_type' => 'product'));
        //print_r($groups);
        foreach ($groups as $group) {
            $group_key = $group['key'];
            $fields    = acf_get_fields($group_key);
            foreach ($fields as $field) {
                $field_key                            = $field['key'];
                $field_label                          = $field['label'];
                $admin_form['localization']['fields'] = array_merge($admin_form['localization']['fields'], array(
                    'add_localization_' . $field_key => array(
                        'type'        => 'text-box',
                        'heading'     => $field_label,
                        'placeholder' => esc_html__('Enter Text', PREFIX_ITWPT_TEXTDOMAIN),
                        'default'     => (! empty($general_data) ? (isset($general_data['general_' . $field_key]) ? $general_data['general_' . $field_key] : $field_label) : $field_label),
                    ),
                ));

                $admin_form['columns']['fields']['add_column_table_column']['options'] =
                    array_merge(
                        $admin_form['columns']['fields']['add_column_table_column']['options'],
                        array(
                            $field_key => array(
                                'default'     => $field_label,
                                'placeholder' => $field_label,
                                "type"        => "acf",
                                "desktop"     => true,
                                "laptop"      => true,
                                "mobile"      => true
                            ),
                        )
                    );

                $acf_default .= ',{"type": "acf","value": "' . $field_key . '","text": "' . $field_label . '","placeholder": "' . $field_label . '","desktop": "","laptop": "","mobile": ""}';
            }
        }
    endif;

    $admin_form['columns']['fields']['add_column_table_column']['default'] = '[{"type":"-","value":"id","text":"ID","placeholder":"ID","desktop":"active","laptop":"","mobile":""},{"type":"-","value":"sl","text":"SL","placeholder":"SL","desktop":"","laptop":"","mobile":""},{"type":"-","value":"thumbnails","text":"Thumbnails","placeholder":"Thumbnails","desktop":"active","laptop":"active","mobile":""},{"type":"-","value":"product_title","text":"Product Title","placeholder":"Product Title","desktop":"active","laptop":"active","mobile":"active"},{"type":"-","value":"description","text":"Description","placeholder":"Description","desktop":"","laptop":"","mobile":""},{"type":"-","value":"category","text":"Category","placeholder":"Category","desktop":"active","laptop":"active","mobile":""},{"type":"-","value":"tags","text":"Tags","placeholder":"Tags","desktop":"","laptop":"","mobile":""},{"type":"-","value":"sku","text":"SKU","placeholder":"SKU","desktop":"active","laptop":"","mobile":""},{"type":"-","value":"weight","text":"Weight(kg)","placeholder":"Weight(kg)","desktop":"","laptop":"","mobile":""},{"type":"-","value":"length","text":"Length(cm)","placeholder":"Length(cm)","desktop":"","laptop":"","mobile":""},{"type":"-","value":"width","text":"Width(cm)","placeholder":"Width(cm)","desktop":"","laptop":"","mobile":""},{"type":"-","value":"height","text":"Height(cm)","placeholder":"Height(cm)","desktop":"","laptop":"","mobile":""},{"type":"-","value":"rating","text":"Rating","placeholder":"Rating","desktop":"active","laptop":"active","mobile":""},{"type":"-","value":"stock","text":"Stock","placeholder":"Stock","desktop":"active","laptop":"active","mobile":"active"},{"type":"-","value":"price","text":"Price","placeholder":"Price","desktop":"active","laptop":"active","mobile":"active"},{"type":"-","value":"quantity","text":"Quantity","placeholder":"Quantity","desktop":"active","laptop":"active","mobile":""},{"type":"-","value":"total-price","text":"Total Price","placeholder":"Total Price","desktop":"","laptop":"","mobile":""},{"type":"-","value":"short-message","text":"Short Message","placeholder":"Short Message","desktop":"","laptop":"","mobile":""},{"type":"-","value":"date","text":"Date","placeholder":"Date","desktop":"","laptop":"","mobile":""},{"type":"-","value":"attributes","text":"Attributes","placeholder":"Attributes","desktop":"","laptop":"","mobile":""},{"type":"-","value":"variations","text":"Variations","placeholder":"Variations","desktop":"","laptop":"","mobile":""},{"type":"-","value":"action","text":"Action","placeholder":"Action","desktop":"active","laptop":"active","mobile":"active"},{"type":"-","value":"check","text":"Check","placeholder":"Check","desktop":"active","laptop":"active","mobile":"active"}' . $quote_default . '' . $yith_compare . $quick_view_default . '' . $wishlist_default . '' . $ti_wishlist_default . $acf_default . ']';
}
