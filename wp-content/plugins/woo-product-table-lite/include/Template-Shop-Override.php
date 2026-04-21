<?php

class Template_Shop_Override
{

    public static $table_id;

    public function __construct()
    {

        add_action('template_redirect', array(__CLASS__, 'template_shop_override'));
    }

    public static function template_shop_override()
    {
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

        $override = false;

        if (isset($general_data['general_label_table_display_shop_page']) && $general_data['general_label_table_display_shop_page'] == 1 && is_shop()) {
            $override = true;
        }

        if (isset($general_data['general_label_table_display_archive_page']) && $general_data['general_label_table_display_archive_page'] == 1 && is_product_category() && ! is_shop()) {
            $override = true;
        }

        if ($override == true) {
            self::$table_id = $general_data['general_label_table_display_shortcode_id'];
            add_action('woocommerce_before_shop_loop', array(__CLASS__, 'disable_default_woocommerce_loop'));
            add_action('woocommerce_after_shop_loop', array(__CLASS__, 'add_product_table_after_shop_loop'));
        }
    }

    public static function disable_default_woocommerce_loop()
    {
        $GLOBALS['woocommerce_loop']['total'] = false;
    }

    public static function add_product_table_after_shop_loop()
    {
        $shortcode = '[product_table]';

        $args = shortcode_parse_atts(str_replace(array('[product_table', ']'), '', $shortcode));
        $args = ! empty($args) && is_array($args) ? $args : array();

        if (is_product_category()) {
            // Product category archive
            $args['args']['term']    = 'product_cat';
            $args['args']['term_id'] = get_queried_object_id();
        } elseif (is_product_tag()) {
            // Product tag archive
            $args['args']['term']    = 'product_tag';
            $args['args']['term_id'] = get_queried_object_id();
        } elseif (is_product_taxonomy()) {
            // Other product taxonomy archive
            $term                    = get_queried_object();
            $args['args']['term']    = $term->taxonomy;
            $args['args']['term_id'] = $term->term_id;
        } elseif (is_post_type_archive('product') && ($search_term = get_query_var('s'))) {
            // Product search results page
            $args['args']['search_term'] = $search_term;
        }

        $args['id'] = self::$table_id;


        echo Itwpt_Shortcode($args);
    }
}

new Template_Shop_Override();
?>