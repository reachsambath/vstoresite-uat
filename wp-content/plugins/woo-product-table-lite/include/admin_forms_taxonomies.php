<?php
/**
 * TODO ADDITIONAL OPTIONS
 */
// TAXONOMY FOR QUERY
// START TAXONOMY
$all_tax      = get_object_taxonomies('product');
$options_html = [];

$options_taxonomy                                        = [];

$options_taxonomy_search_field['add_search_tax_headings'] = array(
    'type'     => 'video-link',
    'link'     => 'https://www.youtube.com/watch?v=0mD-DzYxzbk',
    'download' => 'https://codecanyon.net/item/woocommerec-product-table/25871270?s_rank=1',
    'text'     => 'More fields are available in Pro Version',
    'dependency' => array(
        'element' => 'add_search_status',
        'value'   => 'enable',
        'not'     => false
    ),
);

$options_taxonomy['add_search_tax_headings'] = array(
    'type'     => 'video-link',
    'link'     => 'https://www.youtube.com/watch?v=-gFwlEKHk1Q',
    'download' => 'https://codecanyon.net/item/woocommerec-product-table/25871270?s_rank=1',
    'text'     => 'Category/Taxonomy fields are available in Pro Version',
);


// PAGINATION IN TAB SEARCH AND PAGINATION FIELDS (FOR END ARRAY)
$options_taxonomy_search_field['add_search_pagination_heading'] = array(
    'type'       => 'heading',
    'heading'    => esc_html__('Paginations Options', PREFIX_ITWPT_TEXTDOMAIN),
);
$options_taxonomy_search_field['add_search_filter_pagination'] = array(
    'type'    => 'radio',
    'heading' => esc_html__('Pagination Mode', PREFIX_ITWPT_TEXTDOMAIN),
    'default' => 'pagination',
    'options' => array(
        'pagination' => array(
            'text' => esc_html__('Pagination', PREFIX_ITWPT_TEXTDOMAIN),
        ),
        'load_more'  => array(
            'text' => esc_html__('Load More', PREFIX_ITWPT_TEXTDOMAIN),
        ),
    ),
);

// ORDER BY AND ORDER
$options_taxonomy['add_query_order_heading'] = [
    'type'    => 'heading',
    'heading' => esc_html__('Order / OrderBy', PREFIX_ITWPT_TEXTDOMAIN),
];
$options_taxonomy['add_query_order_by']      = [
    'type'       => 'dropdown',
    'heading'    => esc_html__('OrderBy', PREFIX_ITWPT_TEXTDOMAIN),
    'options'    => array(
        'ID'                 => esc_html__('ID', PREFIX_ITWPT_TEXTDOMAIN),
        'title'              => esc_html__('Title', PREFIX_ITWPT_TEXTDOMAIN),
        '_price'             => esc_html__('Price', PREFIX_ITWPT_TEXTDOMAIN),
        '_wc_average_rating' => esc_html__('Rating', PREFIX_ITWPT_TEXTDOMAIN),
        'total_sales'        => esc_html__('Popularity', PREFIX_ITWPT_TEXTDOMAIN),
        'date'               => esc_html__('Date', PREFIX_ITWPT_TEXTDOMAIN),
        '_sku'               => esc_html__('SKU', PREFIX_ITWPT_TEXTDOMAIN),
        '_stock'             => esc_html__('Stock Quantity', PREFIX_ITWPT_TEXTDOMAIN),
        'rand'               => esc_html__('Random', PREFIX_ITWPT_TEXTDOMAIN)
    ),
    'responsive' => array(
        'desktop' => 6,
        'laptop'  => 6,
        'tablet'  => 12,
        'mobile'  => 12
    ),
];
$options_taxonomy['add_query_order']         = [
    'type'       => 'dropdown',
    'heading'    => esc_html__('Order', PREFIX_ITWPT_TEXTDOMAIN),
    'options'    => array(
        'ASC'  => esc_html__('Ascending', PREFIX_ITWPT_TEXTDOMAIN),
        'DESC' => esc_html__('Descending', PREFIX_ITWPT_TEXTDOMAIN),
    ),
    'responsive' => array(
        'desktop' => 6,
        'laptop'  => 6,
        'tablet'  => 12,
        'mobile'  => 12
    ),
];
$options_taxonomy['add_query_conditions_heading']         = [
    'type'    => 'heading',
    'heading' => esc_html__('Conditions', PREFIX_ITWPT_TEXTDOMAIN),
];
$options_taxonomy['add_conditions_min_price']         = [
    'type'        => 'number',
    'heading'     => esc_html__('Set Minimum Price', PREFIX_ITWPT_TEXTDOMAIN),
    'placeholder' => esc_html__('Enter Price', PREFIX_ITWPT_TEXTDOMAIN),
];
$options_taxonomy['add_conditions_max_price']         = [
    'type'        => 'number',
    'heading'     => esc_html__('Set Maximum Price', PREFIX_ITWPT_TEXTDOMAIN),
    'placeholder' => esc_html__('Enter Price', PREFIX_ITWPT_TEXTDOMAIN),
];
$options_taxonomy['add_conditions_product_status']         = [
    'type'     => 'video-link',
    'link'     => 'https://www.youtube.com/watch?v=-gFwlEKHk1Q',
    'download' => 'https://codecanyon.net/item/woocommerec-product-table/25871270?s_rank=1',
    'text'     => 'More conditions are available in Pro Version',
];
