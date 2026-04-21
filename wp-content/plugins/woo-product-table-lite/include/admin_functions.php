<?php

/**
 * TODO CREATE MENU ADMIN
 * FOR CREATE MENU IN ADMIN WORDPRESS
 */
function Itwpt_Add_Menu()
{

    global $itwpt_svg;

    $data   = $itwpt_svg['itwpt.svg'];
    $base64 = 'data:image/svg+xml;base64,' . base64_encode($data);

    // MENU
    add_menu_page('iThemeland Woo Product Table', esc_html__('Woo Product Table', PREFIX_ITWPT_TEXTDOMAIN),
        'manage_options',
        'itwpt_list', 'Itwpt_Admin_Page', $base64);
    add_submenu_page('itwpt_list', 'Add New', esc_html__('Add New', PREFIX_ITWPT_TEXTDOMAIN), 'manage_options',
        'itwpt_add_new',
        'Itwpt_Add_Page');
    add_submenu_page('itwpt_list', 'Templates', esc_html__('Templates', PREFIX_ITWPT_TEXTDOMAIN), 'manage_options',
        'itwpt_template', 'Itwpt_template');
    add_submenu_page('itwpt_list', 'General', esc_html__('General', PREFIX_ITWPT_TEXTDOMAIN), 'manage_options',
        'itwpt_general',
        'Itwpt_general');

}

/**
 * TODO ADMIN ENQUEUE STYLE
 * ENQUEUE STYLE IN ADMIN FOR BACKEND
 */
function Itwpt_Admin_Enqueue_Style()
{

    wp_enqueue_style(PREFIX_ITWPT . '_admin_style', PREFIX_ITWPT_CSS_URL . 'admin/style.css', array(),
        '1.0.0'); // MAIN STYLE
    wp_enqueue_style('select2.min', PREFIX_ITWPT_CSS_URL . 'select2.min.css', array(),
        '1.0.0'); // SELECT
    wp_enqueue_style(PREFIX_ITWPT . '_admin_icon_z', PREFIX_ITWPT_CSS_URL . 'icons.css', array(), '1.0.0'); // ICON
    wp_enqueue_style(PREFIX_ITWPT . '_admin_icon_it', PREFIX_ITWPT_CSS_URL . 'ithemelan-co-icon-font.css', array(),
        '1.0.0'); // ICON
    wp_enqueue_style(PREFIX_ITWPT . '_bootstrap_style', PREFIX_ITWPT_CSS_URL . 'bootstrap.min.css', array(),
        '1.0.0'); // MATERIALIZE
    wp_enqueue_style(PREFIX_ITWPT . '_roboto_font',
        'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900&display=swap', array(),
        '1.0.0'); // MATERIALIZE
    wp_enqueue_style('wp-color-picker'); // STYLE COLOR PICKER
    wp_enqueue_style(PREFIX_ITWPT . '_custom_scroll_style',
        PREFIX_ITWPT_CSS_URL . 'front-end/jquery.mCustomScrollbar.min.css', array(), '1.0.0'); // SCROLL BAR STYLE

    if (function_exists('wp_enqueue_media')) { // ENQUEUE MEDIA JS
        wp_enqueue_media();
    } else {
        wp_enqueue_style('thickbox');
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
    }

}

/**
 * TODO ADMIN ENQUEUE SCRIPT
 * ENQUEUE SCRIPT IN ADMIN FOR BACKEND
 */
function Itwpt_Admin_Enqueue_Script()
{

    wp_enqueue_script(PREFIX_ITWPT . '_script', PREFIX_ITWPT_JS_URL . 'admin/script.js', array(),
        '1.0.0'); // MAIN SCRIPT
    wp_enqueue_script(PREFIX_ITWPT . '_sortable', PREFIX_ITWPT_JS_URL . 'admin/sortable.js', array(),
        '1.0.0'); // SORTABLE
    wp_enqueue_script(PREFIX_ITWPT . '_live_preview', PREFIX_ITWPT_JS_URL . 'admin/live_preview.js', array(),
        '1.0.0'); // LIVE PREVIEW
    wp_enqueue_script('select2.min', PREFIX_ITWPT_JS_URL . 'select2.min.js', array(),
        '1.0.0'); // SELECT
    wp_enqueue_script(PREFIX_ITWPT . '_bootstrap_script', PREFIX_ITWPT_JS_URL . 'bootstrap.min.js', array(),
        '1.0.0'); // MATERIALIZE
    wp_enqueue_script('wp-color-picker'); // SCRIPT COLOR PICKER
    wp_enqueue_script(PREFIX_ITWPT . '_custom_scroll', PREFIX_ITWPT_JS_URL . 'front/scroll_bar_script.js', array(),
        '1.0.0'); // SCROLL BAR
    wp_enqueue_script(PREFIX_ITWPT . '_tooltip', PREFIX_ITWPT_JS_URL . 'tooltip.js', array(), '1.0.0'); // TOOLTIP

    // WP LOCALIZE
    $localize_value =
        array(
            'nonce'                           => wp_create_nonce('itwpt'),
            'ajaxUrl'                         => admin_url('admin-ajax.php'),
            'loadingUrl'                      => esc_url(PREFIX_ITWPT_IMAGE_URL . 'loading_bs.gif'),
            'template_updated'                => esc_html__('Your data has been saved.', PREFIX_ITWPT_TEXTDOMAIN),
            'do_you_want_to_remove_template'  => esc_html__('Your Template will be deleted. Are you Sure?',
                PREFIX_ITWPT_TEXTDOMAIN),
            'template_deleted'                => esc_html__('Template has been Deleted.', PREFIX_ITWPT_TEXTDOMAIN),
            'template_did_not_deleted'        => esc_html__('Template could not be Deleted.', PREFIX_ITWPT_TEXTDOMAIN),
            'general_save'                    => esc_html__('Your data has been saved.', PREFIX_ITWPT_TEXTDOMAIN),
            'general_did_not_save'            => esc_html__('Error! Your data could not been Saved!',
                PREFIX_ITWPT_TEXTDOMAIN),
            'shortcode_updated'               => esc_html__('Your data has been saved.', PREFIX_ITWPT_TEXTDOMAIN),
            'shortcode_saved'                 => esc_html__('Your data has been saved.', PREFIX_ITWPT_TEXTDOMAIN),
            'shortcode_did_not_updated'       => esc_html__('Error! Your data could not been Saved!',
                PREFIX_ITWPT_TEXTDOMAIN),
            'shortcode_did_not_saved'         => esc_html__('Error! Your data could not been Saved!',
                PREFIX_ITWPT_TEXTDOMAIN),
            'do_you_want_to_remove_shortcuts' => esc_html__('Your shortcode will be deleted. Are you Sure?',
                PREFIX_ITWPT_TEXTDOMAIN),
            'shortcode_deleted'               => esc_html__('Your data has been Deleted.', PREFIX_ITWPT_TEXTDOMAIN),
            'shortcode_did_not_deleted'       => esc_html__('Error! Your data could not been Deleted!',
                PREFIX_ITWPT_TEXTDOMAIN),
            'shortcode_duplicated'            => esc_html__('A Copy of Shortcode Has been Generated.',
                PREFIX_ITWPT_TEXTDOMAIN),
            'shortcode_did_not_duplicated'    => esc_html__('Error! Please Try Again!', PREFIX_ITWPT_TEXTDOMAIN),
            'selectoptions'                   => esc_html__('Type select Options', PREFIX_ITWPT_TEXTDOMAIN),
            'shortcode_copy'                  => esc_html__('Copy to Clipboard!', PREFIX_ITWPT_TEXTDOMAIN),
        );
    wp_localize_script('select2.min', PREFIX_ITWPT . '_localize', $localize_value);

}

/**
 * TODO FRONT ENQUEUE STYLE
 * ENQUEUE STYLE IN FRONT FOR FRONTEND
 */
function Itwpt_Front_Enqueue_Style()
{

    wp_enqueue_style(PREFIX_ITWPT . '_front_style', PREFIX_ITWPT_CSS_URL . 'front-end/style.css', array(),
        '1.0.0'); // FRONT STYLE
    wp_enqueue_style(PREFIX_ITWPT . '_xdebug_style', PREFIX_ITWPT_CSS_URL . 'front-end/xdebug.css', array(),
        '1.0.0'); // XDEBUG STYLE
    wp_enqueue_style('select2.min', PREFIX_ITWPT_CSS_URL . 'select2.min.css', array(),
        '1.0.0'); // SELECT
    wp_enqueue_style(PREFIX_ITWPT . '_admin_icon_z', PREFIX_ITWPT_CSS_URL . 'icons.css', array(), '1.0.0'); // ICON
    wp_enqueue_style(PREFIX_ITWPT . '_admin_icon_it', PREFIX_ITWPT_CSS_URL . 'ithemelan-co-icon-font.css', array(),
        '1.0.0'); // ICON
    wp_enqueue_style(PREFIX_ITWPT . '_custom_scroll_style',
        PREFIX_ITWPT_CSS_URL . 'front-end/jquery.mCustomScrollbar.min.css', array(), '1.0.0'); // SCROLL BAR STYLE
    wp_enqueue_style(PREFIX_ITWPT . '_light_box', PREFIX_ITWPT_CSS_URL . 'front-end/lightbox.min.css', array(),
        '1.0.0'); // LIGHT BOX

}

/**
 * TODO FRONT ENQUEUE SCRIPT
 * ENQUEUE SCRIPT IN FRONT FOR FRONTEND
 */
function Itwpt_Front_Enqueue_Script()
{

    wp_enqueue_script('jquery');
    wp_enqueue_script(PREFIX_ITWPT . '_front_script', PREFIX_ITWPT_JS_URL . 'front/script.js', array(),
        '1.0.0'); // FRONT SCRIPT
    wp_enqueue_script('select2.min', PREFIX_ITWPT_JS_URL . 'select2.min.js', array(),
        '1.0.0'); // SELECT
    wp_enqueue_script(PREFIX_ITWPT . '_sort_table', PREFIX_ITWPT_JS_URL . 'front/sort_table.js', array(),
        '1.0.0'); // SORT TABLE
    wp_enqueue_script(PREFIX_ITWPT . '_custom_scroll', PREFIX_ITWPT_JS_URL . 'front/scroll_bar_script.js', array(),
        '1.0.0'); // SCROLL BAR
    wp_enqueue_script(PREFIX_ITWPT . '_light_box', PREFIX_ITWPT_JS_URL . 'front/lightbox.min.js', array(),
        '1.0.0'); // LIGHT BOX
    wp_enqueue_script(PREFIX_ITWPT . '_tooltip', PREFIX_ITWPT_JS_URL . 'tooltip.js', array(), '1.0.0'); // TOOLTIP

    // WP LOCALIZE
    $localize_value =
        array(
            'nonce'      => wp_create_nonce('itwpt'),
            'ajaxUrl'    => admin_url('admin-ajax.php'),
            'loadingUrl' => esc_url(PREFIX_ITWPT_IMAGE_URL . 'loading_bs.gif'),
            'cartUrl'    => wc_get_cart_url(),
            'checkout'   => wc_get_checkout_url(),
            'symbol_pos' => get_option("woocommerce_currency_pos"),

            'translate' => [
                'selectoptions' => esc_html__('Type select Options', PREFIX_ITWPT_TEXTDOMAIN)
            ]
        );
    wp_localize_script(PREFIX_ITWPT . '_front_script', PREFIX_ITWPT . '_localize', $localize_value);

}

/**
 * TODO ACTIVATION
 * RUN SCRIPT IN ACTIVE PLUGIN
 */
function Itwpt_Activation()
{

    /** GLOBAL VARIABLE */
    global $wpdb;
    $tble_posts_name    = $wpdb->prefix . 'itpt_posts';
    $tble_options_name  = $wpdb->prefix . 'itpt_options';
    $tble_template_name = $wpdb->prefix . 'itpt_itwpt_templates';
    $charset_collate    = $wpdb->get_charset_collate();

    $query = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($tble_template_name));
    if ( ! $wpdb->get_var($query) == $tble_template_name) {

        /** VARIABLE CREATE TABLE */
        $posts    = "CREATE TABLE IF NOT EXISTS `$tble_posts_name` (`id` int(11) NOT NULL AUTO_INCREMENT, `plugin_name` text NOT NULL, `title` text NOT NULL, `data` longtext NOT NULL, `date` text NOT NULL, `update` text NOT NULL, PRIMARY KEY (`id`)) $charset_collate;";
        $options  = "CREATE TABLE IF NOT EXISTS `$tble_options_name` (`id` int(11) NOT NULL AUTO_INCREMENT, `plugin_name` text NOT NULL, `date` text NOT NULL, `name` text NOT NULL, `data` longtext NOT NULL, PRIMARY KEY (`id`))  $charset_collate;";
        $template = "CREATE TABLE IF NOT EXISTS `$tble_template_name` (`id` int(11) NOT NULL AUTO_INCREMENT, `name` text COLLATE utf8_persian_ci NOT NULL, `image` text COLLATE utf8_persian_ci NOT NULL, `data` longtext COLLATE utf8_persian_ci NOT NULL, PRIMARY KEY (`id`))  $charset_collate;";

        /** QUERY CREATE TABLE */
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($posts);
        dbDelta($options);
        dbDelta($template);

        $insert_default = [
            [
                'iThemelandCo',
                '%5B%7B%22name%22:%22tmp_name%22,%22value%22:%22iThemelandCo%22%7D,%7B%22name%22:%22tmp_image%22,%22value%22:%22%5B%5D%22%7D,%7B%22name%22:%22cart_background_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22cart_title_color%22,%22value%22:%22#939393%22%7D,%7B%22name%22:%22cart_meta_color%22,%22value%22:%22#222222%22%7D,%7B%22name%22:%22cart_button_fixed_background_color%22,%22value%22:%22#965dc9%22%7D,%7B%22name%22:%22cart_button_fixed_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22cart_button_background_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22cart_button_text_color%22,%22value%22:%22#222%22%7D,%7B%22name%22:%22alarm_success_background_color%22,%22value%22:%22#81d742%22%7D,%7B%22name%22:%22alarm_success_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22alarm_error_background_color%22,%22value%22:%22#dd3333%22%7D,%7B%22name%22:%22alarm_error_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22crl_box_background_boxs%22,%22value%22:%22#fff%22%7D,%7B%22name%22:%22crl_box_text_color_boxs%22,%22value%22:%22#222%22%7D,%7B%22name%22:%22crl_box_text_background_btn%22,%22value%22:%22#965dc9%22%7D,%7B%22name%22:%22crl_box_text_text_color_btn%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22crl_box_text_background_btn_hover%22,%22value%22:%22#7b5dcc%22%7D,%7B%22name%22:%22crl_box_text_text_color_btn_hover%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22header_footer_background_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22header_footer_border_color%22,%22value%22:%22#f2f2f2%22%7D,%7B%22name%22:%22header_footer_text_color%22,%22value%22:%22#222222%22%7D,%7B%22name%22:%22header_footer_border_width%22,%22value%22:%221%22%7D,%7B%22name%22:%22header_footer_padding%22,%22value%22:%2214%22%7D,%7B%22name%22:%22header_footer_text_alignment%22,%22value%22:%22center%22%7D,%7B%22name%22:%22header_footer_text_transform%22,%22value%22:%22uppercase%22%7D,%7B%22name%22:%22header_footer_font_size%22,%22value%22:%2212%22%7D,%7B%22name%22:%22body_background_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22body_hover_background_color%22,%22value%22:%22#f7f7f7%22%7D,%7B%22name%22:%22body_border_color%22,%22value%22:%22#f9f9f9%22%7D,%7B%22name%22:%22body_text_color%22,%22value%22:%22#222222%22%7D,%7B%22name%22:%22body_link_color%22,%22value%22:%22#222222%22%7D,%7B%22name%22:%22body_hover_link_color%22,%22value%22:%22#965dc9%22%7D,%7B%22name%22:%22body_strip_background_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22body_strip_background_hover_color%22,%22value%22:%22#f7f7f7%22%7D,%7B%22name%22:%22body_strip_text_color%22,%22value%22:%22#222222%22%7D,%7B%22name%22:%22body_sorted_column_bg_color%22,%22value%22:%22%22%7D,%7B%22name%22:%22body_td_padding%22,%22value%22:%2212%22%7D,%7B%22name%22:%22body_td_border_width%22,%22value%22:%222%22%7D,%7B%22name%22:%22checkbox_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22checkbox_border_color%22,%22value%22:%22#965dc9%22%7D,%7B%22name%22:%22checkbox_sign_color%22,%22value%22:%22#965dc9%22%7D,%7B%22name%22:%22variation_button_background_color%22,%22value%22:%22#965dc9%22%7D,%7B%22name%22:%22variation_button_hover_background_color%22,%22value%22:%22#7b5dcc%22%7D,%7B%22name%22:%22variation_button_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22variation_button_hover_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22variation_popup_background_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22variation_popup_text_color%22,%22value%22:%22#222222%22%7D,%7B%22name%22:%22variation_popup_select_border_color%22,%22value%22:%22#ededed%22%7D,%7B%22name%22:%22variation_popup_select_text_color%22,%22value%22:%22#222222%22%7D,%7B%22name%22:%22variation_popup_button_background_color%22,%22value%22:%22#965dc9%22%7D,%7B%22name%22:%22variation_popup_button_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22variation_popup_button_background_color_hover%22,%22value%22:%22#7b5dcc%22%7D,%7B%22name%22:%22variation_popup_button_text_color_hover%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22button_add_to_cart_background_color%22,%22value%22:%22#965dc9%22%7D,%7B%22name%22:%22button_add_to_cart_hover_background_color%22,%22value%22:%22#7b5dcc%22%7D,%7B%22name%22:%22button_add_to_cart_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22button_add_to_cart_hover_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22button_add_to_cart_icon%22,%22value%22:%22only-icon%22%7D,%7B%22name%22:%22other_background_color%22,%22value%22:%22#965dc9%22%7D,%7B%22name%22:%22other_hover_background_color%22,%22value%22:%22#7b5dcc%22%7D,%7B%22name%22:%22other_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22other_hover_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22tmp_pagination_background_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22tmp_pagination_text_color%22,%22value%22:%22#c6c6c6%22%7D,%7B%22name%22:%22tmp_pagination_active_background_color%22,%22value%22:%22#965dc9%22%7D,%7B%22name%22:%22tmp_pagination_active_text_color%22,%22value%22:%22#fff%22%7D,%7B%22name%22:%22tmp_pagination_second_color%22,%22value%22:%22#965dc9%22%7D,%7B%22name%22:%22ads_column_size_fields%22,%22value%22:%223%22%7D,%7B%22name%22:%22other_qty_background_color%22,%22value%22:%22#965dc9%22%7D,%7B%22name%22:%22other_qty_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22other_in_stock_color%22,%22value%22:%22#00ff00%22%7D,%7B%22name%22:%22other_out_stock_color%22,%22value%22:%22#ff0000%22%7D,%7B%22name%22:%22other_back_order_color%22,%22value%22:%22#eac220%22%7D,%7B%22name%22:%22other_out_shadow_color%22,%22value%22:%22#f9f9f9%22%7D,%7B%22name%22:%22other_thum_shape%22,%22value%22:%22q%22%7D,%7B%22name%22:%22other_thumbs_image_size%22,%22value%22:%2260%22%7D%5D'
            ],
            [
                'Light',
                '%5B%7B%22name%22:%22tmp_name%22,%22value%22:%22Light%22%7D,%7B%22name%22:%22tmp_image%22,%22value%22:%22%5B%5D%22%7D,%7B%22name%22:%22cart_background_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22cart_title_color%22,%22value%22:%22#939393%22%7D,%7B%22name%22:%22cart_meta_color%22,%22value%22:%22#222222%22%7D,%7B%22name%22:%22cart_button_fixed_background_color%22,%22value%22:%22#222222%22%7D,%7B%22name%22:%22cart_button_fixed_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22cart_button_background_color%22,%22value%22:%22#f7f7f7%22%7D,%7B%22name%22:%22cart_button_text_color%22,%22value%22:%22#222%22%7D,%7B%22name%22:%22alarm_success_background_color%22,%22value%22:%22#81d742%22%7D,%7B%22name%22:%22alarm_success_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22alarm_error_background_color%22,%22value%22:%22#dd3333%22%7D,%7B%22name%22:%22alarm_error_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22crl_box_background_boxs%22,%22value%22:%22#fff%22%7D,%7B%22name%22:%22crl_box_text_color_boxs%22,%22value%22:%22#222%22%7D,%7B%22name%22:%22crl_box_text_background_btn%22,%22value%22:%22#f7f7f7%22%7D,%7B%22name%22:%22crl_box_text_text_color_btn%22,%22value%22:%22#222222%22%7D,%7B%22name%22:%22crl_box_text_background_btn_hover%22,%22value%22:%22#3a3a3a%22%7D,%7B%22name%22:%22crl_box_text_text_color_btn_hover%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22header_footer_background_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22header_footer_border_color%22,%22value%22:%22#f2f2f2%22%7D,%7B%22name%22:%22header_footer_text_color%22,%22value%22:%22#222222%22%7D,%7B%22name%22:%22header_footer_border_width%22,%22value%22:%221%22%7D,%7B%22name%22:%22header_footer_padding%22,%22value%22:%2214%22%7D,%7B%22name%22:%22header_footer_text_alignment%22,%22value%22:%22center%22%7D,%7B%22name%22:%22header_footer_text_transform%22,%22value%22:%22uppercase%22%7D,%7B%22name%22:%22header_footer_font_size%22,%22value%22:%2212%22%7D,%7B%22name%22:%22body_background_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22body_hover_background_color%22,%22value%22:%22#f7f7f7%22%7D,%7B%22name%22:%22body_border_color%22,%22value%22:%22#f9f9f9%22%7D,%7B%22name%22:%22body_text_color%22,%22value%22:%22#222222%22%7D,%7B%22name%22:%22body_link_color%22,%22value%22:%22#878787%22%7D,%7B%22name%22:%22body_hover_link_color%22,%22value%22:%22#69c0e5%22%7D,%7B%22name%22:%22body_strip_background_color%22,%22value%22:%22#fcfcfc%22%7D,%7B%22name%22:%22body_strip_background_hover_color%22,%22value%22:%22#f7f7f7%22%7D,%7B%22name%22:%22body_strip_text_color%22,%22value%22:%22#222222%22%7D,%7B%22name%22:%22body_sorted_column_bg_color%22,%22value%22:%22%22%7D,%7B%22name%22:%22body_td_padding%22,%22value%22:%2212%22%7D,%7B%22name%22:%22body_td_border_width%22,%22value%22:%222%22%7D,%7B%22name%22:%22checkbox_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22checkbox_border_color%22,%22value%22:%22#222222%22%7D,%7B%22name%22:%22checkbox_sign_color%22,%22value%22:%22#222222%22%7D,%7B%22name%22:%22variation_button_background_color%22,%22value%22:%22#f7f7f7%22%7D,%7B%22name%22:%22variation_button_hover_background_color%22,%22value%22:%22#3a3a3a%22%7D,%7B%22name%22:%22variation_button_text_color%22,%22value%22:%22#222222%22%7D,%7B%22name%22:%22variation_button_hover_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22variation_popup_background_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22variation_popup_text_color%22,%22value%22:%22#222222%22%7D,%7B%22name%22:%22variation_popup_select_border_color%22,%22value%22:%22#ededed%22%7D,%7B%22name%22:%22variation_popup_select_text_color%22,%22value%22:%22#222222%22%7D,%7B%22name%22:%22variation_popup_button_background_color%22,%22value%22:%22#f7f7f7%22%7D,%7B%22name%22:%22variation_popup_button_text_color%22,%22value%22:%22#222222%22%7D,%7B%22name%22:%22variation_popup_button_background_color_hover%22,%22value%22:%22#3d3d3d%22%7D,%7B%22name%22:%22variation_popup_button_text_color_hover%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22button_add_to_cart_background_color%22,%22value%22:%22#f7f7f7%22%7D,%7B%22name%22:%22button_add_to_cart_hover_background_color%22,%22value%22:%22#565656%22%7D,%7B%22name%22:%22button_add_to_cart_text_color%22,%22value%22:%22#222222%22%7D,%7B%22name%22:%22button_add_to_cart_hover_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22button_add_to_cart_icon%22,%22value%22:%22left-icon%22%7D,%7B%22name%22:%22other_background_color%22,%22value%22:%22#f7f7f7%22%7D,%7B%22name%22:%22other_hover_background_color%22,%22value%22:%22#515151%22%7D,%7B%22name%22:%22other_text_color%22,%22value%22:%22#222222%22%7D,%7B%22name%22:%22other_hover_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22tmp_pagination_background_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22tmp_pagination_text_color%22,%22value%22:%22#222222%22%7D,%7B%22name%22:%22tmp_pagination_active_background_color%22,%22value%22:%22#222222%22%7D,%7B%22name%22:%22tmp_pagination_active_text_color%22,%22value%22:%22#fff%22%7D,%7B%22name%22:%22tmp_pagination_second_color%22,%22value%22:%22#222222%22%7D,%7B%22name%22:%22ads_column_size_fields%22,%22value%22:%223%22%7D,%7B%22name%22:%22other_qty_background_color%22,%22value%22:%22#444444%22%7D,%7B%22name%22:%22other_qty_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22other_in_stock_color%22,%22value%22:%22#00ff00%22%7D,%7B%22name%22:%22other_out_stock_color%22,%22value%22:%22#ff0000%22%7D,%7B%22name%22:%22other_back_order_color%22,%22value%22:%22#eac220%22%7D,%7B%22name%22:%22other_out_shadow_color%22,%22value%22:%22#f9f9f9%22%7D,%7B%22name%22:%22other_thum_shape%22,%22value%22:%22circle%22%7D,%7B%22name%22:%22other_thumbs_image_size%22,%22value%22:%2260%22%7D%5D'
            ],
            [
                'Dark',
                '%5B%7B%22name%22:%22tmp_name%22,%22value%22:%22Dark%22%7D,%7B%22name%22:%22tmp_image%22,%22value%22:%22%5B%5D%22%7D,%7B%22name%22:%22cart_background_color%22,%22value%22:%22#222222%22%7D,%7B%22name%22:%22cart_title_color%22,%22value%22:%22#d1d1d1%22%7D,%7B%22name%22:%22cart_meta_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22cart_button_fixed_background_color%22,%22value%22:%22#2b2b2b%22%7D,%7B%22name%22:%22cart_button_fixed_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22cart_button_background_color%22,%22value%22:%22#2b2b2b%22%7D,%7B%22name%22:%22cart_button_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22alarm_success_background_color%22,%22value%22:%22#161616%22%7D,%7B%22name%22:%22alarm_success_text_color%22,%22value%22:%22#81d742%22%7D,%7B%22name%22:%22alarm_error_background_color%22,%22value%22:%22#161616%22%7D,%7B%22name%22:%22alarm_error_text_color%22,%22value%22:%22#dd3333%22%7D,%7B%22name%22:%22crl_box_background_boxs%22,%22value%22:%22#222222%22%7D,%7B%22name%22:%22crl_box_text_color_boxs%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22crl_box_text_background_btn%22,%22value%22:%22#222222%22%7D,%7B%22name%22:%22crl_box_text_text_color_btn%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22crl_box_text_background_btn_hover%22,%22value%22:%22#3a3a3a%22%7D,%7B%22name%22:%22crl_box_text_text_color_btn_hover%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22header_footer_background_color%22,%22value%22:%22#222222%22%7D,%7B%22name%22:%22header_footer_border_color%22,%22value%22:%22#2b2b2b%22%7D,%7B%22name%22:%22header_footer_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22header_footer_border_width%22,%22value%22:%221%22%7D,%7B%22name%22:%22header_footer_padding%22,%22value%22:%2214%22%7D,%7B%22name%22:%22header_footer_text_alignment%22,%22value%22:%22center%22%7D,%7B%22name%22:%22header_footer_text_transform%22,%22value%22:%22uppercase%22%7D,%7B%22name%22:%22header_footer_font_size%22,%22value%22:%2213%22%7D,%7B%22name%22:%22body_background_color%22,%22value%22:%22#222222%22%7D,%7B%22name%22:%22body_hover_background_color%22,%22value%22:%22#2b2b2b%22%7D,%7B%22name%22:%22body_border_color%22,%22value%22:%22%22%7D,%7B%22name%22:%22body_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22body_link_color%22,%22value%22:%22#f4f4f4%22%7D,%7B%22name%22:%22body_hover_link_color%22,%22value%22:%22#69c0e5%22%7D,%7B%22name%22:%22body_strip_background_color%22,%22value%22:%22#262626%22%7D,%7B%22name%22:%22body_strip_background_hover_color%22,%22value%22:%22#2b2b2b%22%7D,%7B%22name%22:%22body_strip_text_color%22,%22value%22:%22#222222%22%7D,%7B%22name%22:%22body_sorted_column_bg_color%22,%22value%22:%22%22%7D,%7B%22name%22:%22body_td_padding%22,%22value%22:%2212%22%7D,%7B%22name%22:%22body_td_border_width%22,%22value%22:%220%22%7D,%7B%22name%22:%22checkbox_color%22,%22value%22:%22#222222%22%7D,%7B%22name%22:%22checkbox_border_color%22,%22value%22:%22#424242%22%7D,%7B%22name%22:%22checkbox_sign_color%22,%22value%22:%22#222222%22%7D,%7B%22name%22:%22variation_button_background_color%22,%22value%22:%22#2b2b2b%22%7D,%7B%22name%22:%22variation_button_hover_background_color%22,%22value%22:%22#3a3a3a%22%7D,%7B%22name%22:%22variation_button_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22variation_button_hover_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22variation_popup_background_color%22,%22value%22:%22#222222%22%7D,%7B%22name%22:%22variation_popup_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22variation_popup_select_border_color%22,%22value%22:%22#333333%22%7D,%7B%22name%22:%22variation_popup_select_text_color%22,%22value%22:%22#bcbcbc%22%7D,%7B%22name%22:%22variation_popup_button_background_color%22,%22value%22:%22#2b2b2b%22%7D,%7B%22name%22:%22variation_popup_button_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22variation_popup_button_background_color_hover%22,%22value%22:%22#3d3d3d%22%7D,%7B%22name%22:%22variation_popup_button_text_color_hover%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22button_add_to_cart_background_color%22,%22value%22:%22#2b2b2b%22%7D,%7B%22name%22:%22button_add_to_cart_hover_background_color%22,%22value%22:%22#565656%22%7D,%7B%22name%22:%22button_add_to_cart_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22button_add_to_cart_hover_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22button_add_to_cart_icon%22,%22value%22:%22no-icon%22%7D,%7B%22name%22:%22other_background_color%22,%22value%22:%22#2b2b2b%22%7D,%7B%22name%22:%22other_hover_background_color%22,%22value%22:%22#515151%22%7D,%7B%22name%22:%22other_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22other_hover_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22tmp_pagination_background_color%22,%22value%22:%22#222222%22%7D,%7B%22name%22:%22tmp_pagination_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22tmp_pagination_active_background_color%22,%22value%22:%22#2b2b2b%22%7D,%7B%22name%22:%22tmp_pagination_active_text_color%22,%22value%22:%22#fff%22%7D,%7B%22name%22:%22tmp_pagination_second_color%22,%22value%22:%22#2b2b2b%22%7D,%7B%22name%22:%22ads_column_size_fields%22,%22value%22:%224%22%7D,%7B%22name%22:%22other_qty_background_color%22,%22value%22:%22#444444%22%7D,%7B%22name%22:%22other_qty_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22other_in_stock_color%22,%22value%22:%22#00ff00%22%7D,%7B%22name%22:%22other_out_stock_color%22,%22value%22:%22#ff0000%22%7D,%7B%22name%22:%22other_back_order_color%22,%22value%22:%22#eac220%22%7D,%7B%22name%22:%22other_out_shadow_color%22,%22value%22:%22#0a0a0a%22%7D,%7B%22name%22:%22other_thum_shape%22,%22value%22:%22q%22%7D,%7B%22name%22:%22other_thumbs_image_size%22,%22value%22:%2260%22%7D%5D'
            ],
            [
                'Colored 1',
                '%5B%7B%22name%22:%22tmp_name%22,%22value%22:%22Colored%201%22%7D,%7B%22name%22:%22tmp_image%22,%22value%22:%22%5B%5D%22%7D,%7B%22name%22:%22cart_background_color%22,%22value%22:%22#7014f3%22%7D,%7B%22name%22:%22cart_title_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22cart_meta_color%22,%22value%22:%22#e0e0e0%22%7D,%7B%22name%22:%22cart_button_fixed_background_color%22,%22value%22:%22#7014f3%22%7D,%7B%22name%22:%22cart_button_fixed_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22cart_button_background_color%22,%22value%22:%22#5d12e8%22%7D,%7B%22name%22:%22cart_button_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22alarm_success_background_color%22,%22value%22:%22#7014f3%22%7D,%7B%22name%22:%22alarm_success_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22alarm_error_background_color%22,%22value%22:%22#f71394%22%7D,%7B%22name%22:%22alarm_error_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22crl_box_background_boxs%22,%22value%22:%22#7014f3%22%7D,%7B%22name%22:%22crl_box_text_color_boxs%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22crl_box_text_background_btn%22,%22value%22:%22#7014f3%22%7D,%7B%22name%22:%22crl_box_text_text_color_btn%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22crl_box_text_background_btn_hover%22,%22value%22:%22#6612ed%22%7D,%7B%22name%22:%22crl_box_text_text_color_btn_hover%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22header_footer_background_color%22,%22value%22:%22#8c57fd%22%7D,%7B%22name%22:%22header_footer_border_color%22,%22value%22:%22#9d54f7%22%7D,%7B%22name%22:%22header_footer_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22header_footer_border_width%22,%22value%22:%220%22%7D,%7B%22name%22:%22header_footer_padding%22,%22value%22:%2214%22%7D,%7B%22name%22:%22header_footer_text_alignment%22,%22value%22:%22center%22%7D,%7B%22name%22:%22header_footer_text_transform%22,%22value%22:%22uppercase%22%7D,%7B%22name%22:%22header_footer_font_size%22,%22value%22:%2212%22%7D,%7B%22name%22:%22body_background_color%22,%22value%22:%22#7014f3%22%7D,%7B%22name%22:%22body_hover_background_color%22,%22value%22:%22#7325fa%22%7D,%7B%22name%22:%22body_border_color%22,%22value%22:%22#7014f3%22%7D,%7B%22name%22:%22body_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22body_link_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22body_hover_link_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22body_strip_background_color%22,%22value%22:%22#7014f3%22%7D,%7B%22name%22:%22body_strip_background_hover_color%22,%22value%22:%22#7325fa%22%7D,%7B%22name%22:%22body_strip_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22body_sorted_column_bg_color%22,%22value%22:%22#7325fa%22%7D,%7B%22name%22:%22body_td_padding%22,%22value%22:%2212%22%7D,%7B%22name%22:%22body_td_border_width%22,%22value%22:%220%22%7D,%7B%22name%22:%22checkbox_color%22,%22value%22:%22#7014f3%22%7D,%7B%22name%22:%22checkbox_border_color%22,%22value%22:%22#dbcaf7%22%7D,%7B%22name%22:%22checkbox_sign_color%22,%22value%22:%22#7014f3%22%7D,%7B%22name%22:%22variation_button_background_color%22,%22value%22:%22#9856f8%22%7D,%7B%22name%22:%22variation_button_hover_background_color%22,%22value%22:%22#8336f7%22%7D,%7B%22name%22:%22variation_button_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22variation_button_hover_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22variation_popup_background_color%22,%22value%22:%22#7014f3%22%7D,%7B%22name%22:%22variation_popup_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22variation_popup_select_border_color%22,%22value%22:%22#ededed%22%7D,%7B%22name%22:%22variation_popup_select_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22variation_popup_button_background_color%22,%22value%22:%22#9856f8%22%7D,%7B%22name%22:%22variation_popup_button_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22variation_popup_button_background_color_hover%22,%22value%22:%22#8031f7%22%7D,%7B%22name%22:%22variation_popup_button_text_color_hover%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22button_add_to_cart_background_color%22,%22value%22:%22#9856f8%22%7D,%7B%22name%22:%22button_add_to_cart_hover_background_color%22,%22value%22:%22#8940f7%22%7D,%7B%22name%22:%22button_add_to_cart_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22button_add_to_cart_hover_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22button_add_to_cart_icon%22,%22value%22:%22only-icon%22%7D,%7B%22name%22:%22other_background_color%22,%22value%22:%22#9856f8%22%7D,%7B%22name%22:%22other_hover_background_color%22,%22value%22:%22#8233f7%22%7D,%7B%22name%22:%22other_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22other_hover_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22tmp_pagination_background_color%22,%22value%22:%22#7014f3%22%7D,%7B%22name%22:%22tmp_pagination_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22tmp_pagination_active_background_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22tmp_pagination_active_text_color%22,%22value%22:%22#7014f3%22%7D,%7B%22name%22:%22tmp_pagination_second_color%22,%22value%22:%22#9856f8%22%7D,%7B%22name%22:%22ads_column_size_fields%22,%22value%22:%224%22%7D,%7B%22name%22:%22other_qty_background_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22other_qty_text_color%22,%22value%22:%22#9856f8%22%7D,%7B%22name%22:%22other_in_stock_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22other_out_stock_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22other_back_order_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22other_out_shadow_color%22,%22value%22:%22#cfb7f7%22%7D,%7B%22name%22:%22other_thum_shape%22,%22value%22:%22circle%22%7D,%7B%22name%22:%22other_thumbs_image_size%22,%22value%22:%2260%22%7D%5D'
            ],
            [
                'Sedative',
                '%5B%7B%22name%22:%22tmp_name%22,%22value%22:%22Sedative%22%7D,%7B%22name%22:%22tmp_image%22,%22value%22:%22%5B%5D%22%7D,%7B%22name%22:%22cart_background_color%22,%22value%22:%22#25bf99%22%7D,%7B%22name%22:%22cart_title_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22cart_meta_color%22,%22value%22:%22#ededed%22%7D,%7B%22name%22:%22cart_button_fixed_background_color%22,%22value%22:%22#25bf99%22%7D,%7B%22name%22:%22cart_button_fixed_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22cart_button_background_color%22,%22value%22:%22#20c9a1%22%7D,%7B%22name%22:%22cart_button_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22alarm_success_background_color%22,%22value%22:%22#25bf99%22%7D,%7B%22name%22:%22alarm_success_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22alarm_error_background_color%22,%22value%22:%22#ba1850%22%7D,%7B%22name%22:%22alarm_error_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22crl_box_background_boxs%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22crl_box_text_color_boxs%22,%22value%22:%22#25bf99%22%7D,%7B%22name%22:%22crl_box_text_background_btn%22,%22value%22:%22#25bf99%22%7D,%7B%22name%22:%22crl_box_text_text_color_btn%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22crl_box_text_background_btn_hover%22,%22value%22:%22#20c9a1%22%7D,%7B%22name%22:%22crl_box_text_text_color_btn_hover%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22header_footer_background_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22header_footer_border_color%22,%22value%22:%22#a9e8c9%22%7D,%7B%22name%22:%22header_footer_text_color%22,%22value%22:%22#25bf99%22%7D,%7B%22name%22:%22header_footer_border_width%22,%22value%22:%221%22%7D,%7B%22name%22:%22header_footer_padding%22,%22value%22:%2214%22%7D,%7B%22name%22:%22header_footer_text_alignment%22,%22value%22:%22center%22%7D,%7B%22name%22:%22header_footer_text_transform%22,%22value%22:%22uppercase%22%7D,%7B%22name%22:%22header_footer_font_size%22,%22value%22:%2212%22%7D,%7B%22name%22:%22body_background_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22body_hover_background_color%22,%22value%22:%22#effcf8%22%7D,%7B%22name%22:%22body_border_color%22,%22value%22:%22#67d3b9%22%7D,%7B%22name%22:%22body_text_color%22,%22value%22:%22#25bf99%22%7D,%7B%22name%22:%22body_link_color%22,%22value%22:%22#25bf99%22%7D,%7B%22name%22:%22body_hover_link_color%22,%22value%22:%22#22b2b5%22%7D,%7B%22name%22:%22body_strip_background_color%22,%22value%22:%22#f8fffd%22%7D,%7B%22name%22:%22body_strip_background_hover_color%22,%22value%22:%22#effcf8%22%7D,%7B%22name%22:%22body_strip_text_color%22,%22value%22:%22#25bf99%22%7D,%7B%22name%22:%22body_sorted_column_bg_color%22,%22value%22:%22#effcf8%22%7D,%7B%22name%22:%22body_td_padding%22,%22value%22:%2212%22%7D,%7B%22name%22:%22body_td_border_width%22,%22value%22:%220%22%7D,%7B%22name%22:%22checkbox_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22checkbox_border_color%22,%22value%22:%22#67d3b9%22%7D,%7B%22name%22:%22checkbox_sign_color%22,%22value%22:%22#25bf99%22%7D,%7B%22name%22:%22variation_button_background_color%22,%22value%22:%22#20c9a1%22%7D,%7B%22name%22:%22variation_button_hover_background_color%22,%22value%22:%22#67d3b9%22%7D,%7B%22name%22:%22variation_button_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22variation_button_hover_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22variation_popup_background_color%22,%22value%22:%22#25bf99%22%7D,%7B%22name%22:%22variation_popup_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22variation_popup_select_border_color%22,%22value%22:%22#ededed%22%7D,%7B%22name%22:%22variation_popup_select_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22variation_popup_button_background_color%22,%22value%22:%22#20c9a1%22%7D,%7B%22name%22:%22variation_popup_button_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22variation_popup_button_background_color_hover%22,%22value%22:%22#67d3b9%22%7D,%7B%22name%22:%22variation_popup_button_text_color_hover%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22button_add_to_cart_background_color%22,%22value%22:%22#20c9a1%22%7D,%7B%22name%22:%22button_add_to_cart_hover_background_color%22,%22value%22:%22#67d3b9%22%7D,%7B%22name%22:%22button_add_to_cart_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22button_add_to_cart_hover_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22button_add_to_cart_icon%22,%22value%22:%22only-icon%22%7D,%7B%22name%22:%22other_background_color%22,%22value%22:%22#20c9a1%22%7D,%7B%22name%22:%22other_hover_background_color%22,%22value%22:%22#67d3b9%22%7D,%7B%22name%22:%22other_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22other_hover_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22tmp_pagination_background_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22tmp_pagination_text_color%22,%22value%22:%22#25bf99%22%7D,%7B%22name%22:%22tmp_pagination_active_background_color%22,%22value%22:%22#25bf99%22%7D,%7B%22name%22:%22tmp_pagination_active_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22tmp_pagination_second_color%22,%22value%22:%22#25bf99%22%7D,%7B%22name%22:%22ads_column_size_fields%22,%22value%22:%224%22%7D,%7B%22name%22:%22other_qty_background_color%22,%22value%22:%22#25bf99%22%7D,%7B%22name%22:%22other_qty_text_color%22,%22value%22:%22#ffffff%22%7D,%7B%22name%22:%22other_in_stock_color%22,%22value%22:%22#25bf99%22%7D,%7B%22name%22:%22other_out_stock_color%22,%22value%22:%22#ba1850%22%7D,%7B%22name%22:%22other_back_order_color%22,%22value%22:%22#eae12c%22%7D,%7B%22name%22:%22other_out_shadow_color%22,%22value%22:%22#f9f9f9%22%7D,%7B%22name%22:%22other_thum_shape%22,%22value%22:%22q%22%7D,%7B%22name%22:%22other_thumbs_image_size%22,%22value%22:%2260%22%7D%5D'
            ]
        ];

        foreach ($insert_default as $item) {

            $data =
                array(
                    'name'  => sprintf($item[0]),
                    'image' => '',
                    'data'  => $item[1],
                );
            $wpdb->insert($tble_template_name, $data);

        }

        $add_post = array(
            'plugin_name' => 'itwpt@3w!',
            'title'       => 'Sample Table',
            'data'        => '%5B%7B%22name%22%3A%22add_column_shortcode_name%22%2C%22value%22%3A%22Sample+Table%22%7D%2C%7B%22name%22%3A%22add_column_table_column%22%2C%22value%22%3A%22%5B%7B%5C%22type%5C%22%3A%5C%22-%5C%22%2C%5C%22value%5C%22%3A%5C%22id%5C%22%2C%5C%22text%5C%22%3A%5C%22ID%5C%22%2C%5C%22placeholder%5C%22%3A%5C%22ID%5C%22%2C%5C%22desktop%5C%22%3A%5C%22active%5C%22%2C%5C%22laptop%5C%22%3A%5C%22%5C%22%2C%5C%22mobile%5C%22%3A%5C%22%5C%22%7D%2C%7B%5C%22type%5C%22%3A%5C%22-%5C%22%2C%5C%22value%5C%22%3A%5C%22sl%5C%22%2C%5C%22text%5C%22%3A%5C%22SL%5C%22%2C%5C%22placeholder%5C%22%3A%5C%22SL%5C%22%2C%5C%22desktop%5C%22%3A%5C%22%5C%22%2C%5C%22laptop%5C%22%3A%5C%22%5C%22%2C%5C%22mobile%5C%22%3A%5C%22%5C%22%7D%2C%7B%5C%22type%5C%22%3A%5C%22-%5C%22%2C%5C%22value%5C%22%3A%5C%22thumbnails%5C%22%2C%5C%22text%5C%22%3A%5C%22Thumbnails%5C%22%2C%5C%22placeholder%5C%22%3A%5C%22Thumbnails%5C%22%2C%5C%22desktop%5C%22%3A%5C%22active%5C%22%2C%5C%22laptop%5C%22%3A%5C%22active%5C%22%2C%5C%22mobile%5C%22%3A%5C%22%5C%22%7D%2C%7B%5C%22type%5C%22%3A%5C%22-%5C%22%2C%5C%22value%5C%22%3A%5C%22product_title%5C%22%2C%5C%22text%5C%22%3A%5C%22Product+Title%5C%22%2C%5C%22placeholder%5C%22%3A%5C%22Product+Title%5C%22%2C%5C%22desktop%5C%22%3A%5C%22active%5C%22%2C%5C%22laptop%5C%22%3A%5C%22active%5C%22%2C%5C%22mobile%5C%22%3A%5C%22active%5C%22%7D%2C%7B%5C%22type%5C%22%3A%5C%22-%5C%22%2C%5C%22value%5C%22%3A%5C%22description%5C%22%2C%5C%22text%5C%22%3A%5C%22Description%5C%22%2C%5C%22placeholder%5C%22%3A%5C%22Description%5C%22%2C%5C%22desktop%5C%22%3A%5C%22%5C%22%2C%5C%22laptop%5C%22%3A%5C%22%5C%22%2C%5C%22mobile%5C%22%3A%5C%22%5C%22%7D%2C%7B%5C%22type%5C%22%3A%5C%22-%5C%22%2C%5C%22value%5C%22%3A%5C%22category%5C%22%2C%5C%22text%5C%22%3A%5C%22Category%5C%22%2C%5C%22placeholder%5C%22%3A%5C%22Category%5C%22%2C%5C%22desktop%5C%22%3A%5C%22active%5C%22%2C%5C%22laptop%5C%22%3A%5C%22active%5C%22%2C%5C%22mobile%5C%22%3A%5C%22%5C%22%7D%2C%7B%5C%22type%5C%22%3A%5C%22-%5C%22%2C%5C%22value%5C%22%3A%5C%22tags%5C%22%2C%5C%22text%5C%22%3A%5C%22Tags%5C%22%2C%5C%22placeholder%5C%22%3A%5C%22Tags%5C%22%2C%5C%22desktop%5C%22%3A%5C%22%5C%22%2C%5C%22laptop%5C%22%3A%5C%22%5C%22%2C%5C%22mobile%5C%22%3A%5C%22%5C%22%7D%2C%7B%5C%22type%5C%22%3A%5C%22-%5C%22%2C%5C%22value%5C%22%3A%5C%22sku%5C%22%2C%5C%22text%5C%22%3A%5C%22SKU%5C%22%2C%5C%22placeholder%5C%22%3A%5C%22SKU%5C%22%2C%5C%22desktop%5C%22%3A%5C%22active%5C%22%2C%5C%22laptop%5C%22%3A%5C%22%5C%22%2C%5C%22mobile%5C%22%3A%5C%22%5C%22%7D%2C%7B%5C%22type%5C%22%3A%5C%22-%5C%22%2C%5C%22value%5C%22%3A%5C%22weight%5C%22%2C%5C%22text%5C%22%3A%5C%22Weight%28kg%29%5C%22%2C%5C%22placeholder%5C%22%3A%5C%22Weight%28kg%29%5C%22%2C%5C%22desktop%5C%22%3A%5C%22%5C%22%2C%5C%22laptop%5C%22%3A%5C%22%5C%22%2C%5C%22mobile%5C%22%3A%5C%22%5C%22%7D%2C%7B%5C%22type%5C%22%3A%5C%22-%5C%22%2C%5C%22value%5C%22%3A%5C%22length%5C%22%2C%5C%22text%5C%22%3A%5C%22Length%28cm%29%5C%22%2C%5C%22placeholder%5C%22%3A%5C%22Length%28cm%29%5C%22%2C%5C%22desktop%5C%22%3A%5C%22%5C%22%2C%5C%22laptop%5C%22%3A%5C%22%5C%22%2C%5C%22mobile%5C%22%3A%5C%22%5C%22%7D%2C%7B%5C%22type%5C%22%3A%5C%22-%5C%22%2C%5C%22value%5C%22%3A%5C%22width%5C%22%2C%5C%22text%5C%22%3A%5C%22Width%28cm%29%5C%22%2C%5C%22placeholder%5C%22%3A%5C%22Width%28cm%29%5C%22%2C%5C%22desktop%5C%22%3A%5C%22%5C%22%2C%5C%22laptop%5C%22%3A%5C%22%5C%22%2C%5C%22mobile%5C%22%3A%5C%22%5C%22%7D%2C%7B%5C%22type%5C%22%3A%5C%22-%5C%22%2C%5C%22value%5C%22%3A%5C%22height%5C%22%2C%5C%22text%5C%22%3A%5C%22Height%28cm%29%5C%22%2C%5C%22placeholder%5C%22%3A%5C%22Height%28cm%29%5C%22%2C%5C%22desktop%5C%22%3A%5C%22%5C%22%2C%5C%22laptop%5C%22%3A%5C%22%5C%22%2C%5C%22mobile%5C%22%3A%5C%22%5C%22%7D%2C%7B%5C%22type%5C%22%3A%5C%22-%5C%22%2C%5C%22value%5C%22%3A%5C%22rating%5C%22%2C%5C%22text%5C%22%3A%5C%22Rating%5C%22%2C%5C%22placeholder%5C%22%3A%5C%22Rating%5C%22%2C%5C%22desktop%5C%22%3A%5C%22active%5C%22%2C%5C%22laptop%5C%22%3A%5C%22active%5C%22%2C%5C%22mobile%5C%22%3A%5C%22%5C%22%7D%2C%7B%5C%22type%5C%22%3A%5C%22-%5C%22%2C%5C%22value%5C%22%3A%5C%22stock%5C%22%2C%5C%22text%5C%22%3A%5C%22Stock%5C%22%2C%5C%22placeholder%5C%22%3A%5C%22Stock%5C%22%2C%5C%22desktop%5C%22%3A%5C%22active%5C%22%2C%5C%22laptop%5C%22%3A%5C%22active%5C%22%2C%5C%22mobile%5C%22%3A%5C%22active%5C%22%7D%2C%7B%5C%22type%5C%22%3A%5C%22-%5C%22%2C%5C%22value%5C%22%3A%5C%22price%5C%22%2C%5C%22text%5C%22%3A%5C%22Price%5C%22%2C%5C%22placeholder%5C%22%3A%5C%22Price%5C%22%2C%5C%22desktop%5C%22%3A%5C%22active%5C%22%2C%5C%22laptop%5C%22%3A%5C%22active%5C%22%2C%5C%22mobile%5C%22%3A%5C%22active%5C%22%7D%2C%7B%5C%22type%5C%22%3A%5C%22-%5C%22%2C%5C%22value%5C%22%3A%5C%22wish-list%5C%22%2C%5C%22text%5C%22%3A%5C%22Wish+List%5C%22%2C%5C%22placeholder%5C%22%3A%5C%22Wish+List%5C%22%2C%5C%22desktop%5C%22%3A%5C%22active%5C%22%2C%5C%22laptop%5C%22%3A%5C%22active%5C%22%2C%5C%22mobile%5C%22%3A%5C%22active%5C%22%7D%2C%7B%5C%22type%5C%22%3A%5C%22-%5C%22%2C%5C%22value%5C%22%3A%5C%22quantity%5C%22%2C%5C%22text%5C%22%3A%5C%22Quantity%5C%22%2C%5C%22placeholder%5C%22%3A%5C%22Quantity%5C%22%2C%5C%22desktop%5C%22%3A%5C%22active%5C%22%2C%5C%22laptop%5C%22%3A%5C%22active%5C%22%2C%5C%22mobile%5C%22%3A%5C%22%5C%22%7D%2C%7B%5C%22type%5C%22%3A%5C%22-%5C%22%2C%5C%22value%5C%22%3A%5C%22total-price%5C%22%2C%5C%22text%5C%22%3A%5C%22Total+Price%5C%22%2C%5C%22placeholder%5C%22%3A%5C%22Total+Price%5C%22%2C%5C%22desktop%5C%22%3A%5C%22%5C%22%2C%5C%22laptop%5C%22%3A%5C%22%5C%22%2C%5C%22mobile%5C%22%3A%5C%22%5C%22%7D%2C%7B%5C%22type%5C%22%3A%5C%22-%5C%22%2C%5C%22value%5C%22%3A%5C%22short-message%5C%22%2C%5C%22text%5C%22%3A%5C%22Short+Message%5C%22%2C%5C%22placeholder%5C%22%3A%5C%22Short+Message%5C%22%2C%5C%22desktop%5C%22%3A%5C%22%5C%22%2C%5C%22laptop%5C%22%3A%5C%22%5C%22%2C%5C%22mobile%5C%22%3A%5C%22%5C%22%7D%2C%7B%5C%22type%5C%22%3A%5C%22-%5C%22%2C%5C%22value%5C%22%3A%5C%22quick-view%5C%22%2C%5C%22text%5C%22%3A%5C%22Quick+View%5C%22%2C%5C%22placeholder%5C%22%3A%5C%22Quick+View%5C%22%2C%5C%22desktop%5C%22%3A%5C%22%5C%22%2C%5C%22laptop%5C%22%3A%5C%22%5C%22%2C%5C%22mobile%5C%22%3A%5C%22%5C%22%7D%2C%7B%5C%22type%5C%22%3A%5C%22-%5C%22%2C%5C%22value%5C%22%3A%5C%22date%5C%22%2C%5C%22text%5C%22%3A%5C%22Date%5C%22%2C%5C%22placeholder%5C%22%3A%5C%22Date%5C%22%2C%5C%22desktop%5C%22%3A%5C%22%5C%22%2C%5C%22laptop%5C%22%3A%5C%22%5C%22%2C%5C%22mobile%5C%22%3A%5C%22%5C%22%7D%2C%7B%5C%22type%5C%22%3A%5C%22-%5C%22%2C%5C%22value%5C%22%3A%5C%22attributes%5C%22%2C%5C%22text%5C%22%3A%5C%22Attributes%5C%22%2C%5C%22placeholder%5C%22%3A%5C%22Attributes%5C%22%2C%5C%22desktop%5C%22%3A%5C%22%5C%22%2C%5C%22laptop%5C%22%3A%5C%22%5C%22%2C%5C%22mobile%5C%22%3A%5C%22%5C%22%7D%2C%7B%5C%22type%5C%22%3A%5C%22-%5C%22%2C%5C%22value%5C%22%3A%5C%22variations%5C%22%2C%5C%22text%5C%22%3A%5C%22Variations%5C%22%2C%5C%22placeholder%5C%22%3A%5C%22Variations%5C%22%2C%5C%22desktop%5C%22%3A%5C%22%5C%22%2C%5C%22laptop%5C%22%3A%5C%22%5C%22%2C%5C%22mobile%5C%22%3A%5C%22%5C%22%7D%2C%7B%5C%22type%5C%22%3A%5C%22-%5C%22%2C%5C%22value%5C%22%3A%5C%22action%5C%22%2C%5C%22text%5C%22%3A%5C%22Action%5C%22%2C%5C%22placeholder%5C%22%3A%5C%22Action%5C%22%2C%5C%22desktop%5C%22%3A%5C%22active%5C%22%2C%5C%22laptop%5C%22%3A%5C%22active%5C%22%2C%5C%22mobile%5C%22%3A%5C%22active%5C%22%7D%2C%7B%5C%22type%5C%22%3A%5C%22-%5C%22%2C%5C%22value%5C%22%3A%5C%22check%5C%22%2C%5C%22text%5C%22%3A%5C%22Check%5C%22%2C%5C%22placeholder%5C%22%3A%5C%22Check%5C%22%2C%5C%22desktop%5C%22%3A%5C%22active%5C%22%2C%5C%22laptop%5C%22%3A%5C%22active%5C%22%2C%5C%22mobile%5C%22%3A%5C%22active%5C%22%7D%2C%7B%5C%22type%5C%22%3A%5C%22-%5C%22%2C%5C%22value%5C%22%3A%5C%22quote-request%5C%22%2C%5C%22text%5C%22%3A%5C%22Quote+Request%5C%22%2C%5C%22placeholder%5C%22%3A%5C%22Quote+Request%5C%22%2C%5C%22desktop%5C%22%3A%5C%22%5C%22%2C%5C%22laptop%5C%22%3A%5C%22%5C%22%2C%5C%22mobile%5C%22%3A%5C%22%5C%22%7D%5D%22%7D%2C%7B%22name%22%3A%22add_query_product_include_id%22%2C%22value%22%3A%22%22%7D%2C%7B%22name%22%3A%22add_query_product_exclude_id%22%2C%22value%22%3A%22%22%7D%2C%7B%22name%22%3A%22add_query_order_by%22%2C%22value%22%3A%22ID%22%7D%2C%7B%22name%22%3A%22add_query_order%22%2C%22value%22%3A%22ASC%22%7D%2C%7B%22name%22%3A%22add_conditions_min_price%22%2C%22value%22%3A%22%22%7D%2C%7B%22name%22%3A%22add_conditions_max_price%22%2C%22value%22%3A%22%22%7D%2C%7B%22name%22%3A%22add_conditions_product_status%22%2C%22value%22%3A%22all%22%7D%2C%7B%22name%22%3A%22add_conditions_post_limit%22%2C%22value%22%3A%2210%22%7D%2C%7B%22name%22%3A%22add_checklist_description_type%22%2C%22value%22%3A%22excerpt%22%7D%2C%7B%22name%22%3A%22add_checklist_description_length%22%2C%22value%22%3A%22120%22%7D%2C%7B%22name%22%3A%22add_checklist_ajax_action%22%2C%22value%22%3A%221%22%7D%2C%7B%22name%22%3A%22add_checklist_mini_cart_position%22%2C%22value%22%3A%22tbl_header%2Cfloat%22%7D%2C%7B%22name%22%3A%22add_checklist_show_added_quantity%22%2C%22value%22%3A%221%22%7D%2C%7B%22name%22%3A%22add_checklist_column_hide_header_footer%22%2C%22value%22%3A%22%22%7D%2C%7B%22name%22%3A%22add_checklist_select_all%22%2C%22value%22%3A%22enable%22%7D%2C%7B%22name%22%3A%22add_checklist_group_add%22%2C%22value%22%3A%22enable%22%7D%2C%7B%22name%22%3A%22add_checklist_sticky_column%22%2C%22value%22%3A%22%22%7D%2C%7B%22name%22%3A%22add_checklist_title_in_one_line%22%2C%22value%22%3A%22%22%7D%2C%7B%22name%22%3A%22add_checklist_variation%22%2C%22value%22%3A%221%22%7D%2C%7B%22name%22%3A%22add_checklist_custom_class_table%22%2C%22value%22%3A%22%22%7D%2C%7B%22name%22%3A%22add_template_template%22%2C%22value%22%3A%225%22%7D%2C%7B%22name%22%3A%22add_search_status%22%2C%22value%22%3A%22enable%22%7D%2C%7B%22name%22%3A%22add_search_order%22%2C%22value%22%3A%22enable%22%7D%2C%7B%22name%22%3A%22add_search_price_options%22%2C%22value%22%3A%22enable%22%7D%2C%7B%22name%22%3A%22add_search_price_step%22%2C%22value%22%3A%22%22%7D%2C%7B%22name%22%3A%22add_search_status_options%22%2C%22value%22%3A%22enable%22%7D%2C%7B%22name%22%3A%22add_search_sku_options%22%2C%22value%22%3A%22enable%22%7D%2C%7B%22name%22%3A%22add_search_filter_pagination%22%2C%22value%22%3A%22pagination%22%7D%2C%7B%22name%22%3A%22add_search_as-brand%22%2C%22value%22%3A%22%22%7D%2C%7B%22name%22%3A%22add_search_product_cat%22%2C%22value%22%3A%22%22%7D%2C%7B%22name%22%3A%22add_search_product_tag%22%2C%22value%22%3A%22%22%7D%2C%7B%22name%22%3A%22add_search_product_shipping_class%22%2C%22value%22%3A%22%22%7D%2C%7B%22name%22%3A%22add_search_pa_color%22%2C%22value%22%3A%22%22%7D%2C%7B%22name%22%3A%22add_search_pa_size%22%2C%22value%22%3A%22%22%7D%2C%7B%22name%22%3A%22add_settings_popup_notice%22%2C%22value%22%3A%221%22%7D%2C%7B%22name%22%3A%22add_settings_light_box%22%2C%22value%22%3A%221%22%7D%2C%7B%22name%22%3A%22add_settings_product_link%22%2C%22value%22%3A%220%22%7D%2C%7B%22name%22%3A%22add_settings_product_link_type%22%2C%22value%22%3A%22new%22%7D%2C%7B%22name%22%3A%22add_settings_direct_checkout_page%22%2C%22value%22%3A%220%22%7D%2C%7B%22name%22%3A%22add_settings_enable_quick_buy_button%22%2C%22value%22%3A%220%22%7D%2C%7B%22name%22%3A%22add_settings_footer_cart_position%22%2C%22value%22%3A%22bottom-right%22%7D%2C%7B%22name%22%3A%22add_settings_footer_cart_size%22%2C%22value%22%3A%22100%22%7D%2C%7B%22name%22%3A%22add_localization_add_to_cart_text%22%2C%22value%22%3A%22Add+To+Cart%22%7D%2C%7B%22name%22%3A%22add_localization_add_to_cart_added_text%22%2C%22value%22%3A%22Added%22%7D%2C%7B%22name%22%3A%22add_localization_add_to_cart_adding_text%22%2C%22value%22%3A%22Adding...%22%7D%2C%7B%22name%22%3A%22add_localization_add_to_cart_selected_text%22%2C%22value%22%3A%22Add+To+Cart+Products%22%7D%2C%7B%22name%22%3A%22add_localization_all_check_uncheck_text%22%2C%22value%22%3A%22Select+All%22%7D%2C%7B%22name%22%3A%22add_localization_product_not_founded_text%22%2C%22value%22%3A%22Products+Not+founded%21%22%7D%2C%7B%22name%22%3A%22add_localization_load_more_text%22%2C%22value%22%3A%22Load+more%22%7D%2C%7B%22name%22%3A%22add_localization_pagination_next_text%22%2C%22value%22%3A%22Next%22%7D%2C%7B%22name%22%3A%22add_localization_pagination_prev_text%22%2C%22value%22%3A%22Prev%22%7D%2C%7B%22name%22%3A%22add_localization_search_text%22%2C%22value%22%3A%22Search%22%7D%2C%7B%22name%22%3A%22add_localization_search_keyword_text%22%2C%22value%22%3A%22Search+Keyword%22%7D%2C%7B%22name%22%3A%22add_localization_loading_button_text%22%2C%22value%22%3A%22Loading..%22%7D%2C%7B%22name%22%3A%22add_localization_item_singular_text%22%2C%22value%22%3A%22Item%22%7D%2C%7B%22name%22%3A%22add_localization_item_plural_text%22%2C%22value%22%3A%22Items%22%7D%2C%7B%22name%22%3A%22add_localization_search_box_order_bay_text%22%2C%22value%22%3A%22Order+By%22%7D%2C%7B%22name%22%3A%22add_localization_search_box_order_text%22%2C%22value%22%3A%22Order%22%7D%2C%7B%22name%22%3A%22add_localization_search_box_min_price%22%2C%22value%22%3A%22Order%22%7D%2C%7B%22name%22%3A%22add_localization_search_box_max_price%22%2C%22value%22%3A%22Order%22%7D%2C%7B%22name%22%3A%22add_localization_search_box_status%22%2C%22value%22%3A%22Order%22%7D%2C%7B%22name%22%3A%22add_localization_search_box_sku%22%2C%22value%22%3A%22Order%22%7D%2C%7B%22name%22%3A%22add_localization_type_your_message_text%22%2C%22value%22%3A%22Type+your+Message.%22%7D%2C%7B%22name%22%3A%22add_localization_sticky_label%22%2C%22value%22%3A%22Action%22%7D%2C%7B%22name%22%3A%22add_localization_fix_button_open_text%22%2C%22value%22%3A%22Open+Cart%22%7D%2C%7B%22name%22%3A%22add_localization_fix_button_close_text%22%2C%22value%22%3A%22Close+Cart%22%7D%2C%7B%22name%22%3A%22add_localization_cart_label%22%2C%22value%22%3A%22Cart%22%7D%2C%7B%22name%22%3A%22add_localization_cart_clear%22%2C%22value%22%3A%22Cart+Clear%22%7D%2C%7B%22name%22%3A%22add_localization_cart_subtotal%22%2C%22value%22%3A%22Subtotal%22%7D%2C%7B%22name%22%3A%22add_localization_cart_checkout%22%2C%22value%22%3A%22Check+Out%22%7D%2C%7B%22name%22%3A%22add_localization_cart_view_cart%22%2C%22value%22%3A%22View+Cart%22%7D%2C%7B%22name%22%3A%22add_localization_cart_item_number%22%2C%22value%22%3A%22Items+In+Cart%22%7D%2C%7B%22name%22%3A%22add_localization_cart_empty%22%2C%22value%22%3A%22Empty+Cart%22%7D%2C%7B%22name%22%3A%22add_localization_in_stock_text%22%2C%22value%22%3A%22In+Stock%22%7D%2C%7B%22name%22%3A%22add_localization_out_of_stock_text%22%2C%22value%22%3A%22Out+of+Stock%22%7D%2C%7B%22name%22%3A%22add_localization_on_back_order_text%22%2C%22value%22%3A%22On+Back+Order%22%7D%2C%7B%22name%22%3A%22add_localization_variation_not_available_text%22%2C%22value%22%3A%22On+Back+Order%22%7D%2C%7B%22name%22%3A%22add_localization_variation_is_not_set_text%22%2C%22value%22%3A%22Product+variations+is+not+set+Properly.+May+be%3A+price+is+not+inputted.+may+be%3A+Out+of+Stock.%22%7D%2C%7B%22name%22%3A%22add_localization_select_all_item_text%22%2C%22value%22%3A%22Please+select+all+items.%22%7D%2C%7B%22name%22%3A%22add_localization_out_of_stock_message_text%22%2C%22value%22%3A%22Out+of+Stock%22%7D%2C%7B%22name%22%3A%22add_localization_is_no_more_products_text%22%2C%22value%22%3A%22There+is+no+more+products+based+on+current+Query.%22%7D%2C%7B%22name%22%3A%22add_localization_no_right_combination_text%22%2C%22value%22%3A%22No+Right+Combination%22%7D%2C%7B%22name%22%3A%22add_localization_please_choose_right_combination_text%22%2C%22value%22%3A%22Sorry%2C+Please+choose+right+combination.%22%7D%2C%7B%22name%22%3A%22add_localization_cart_update%22%2C%22value%22%3A%22Cart+Updated%22%7D%2C%7B%22name%22%3A%22add_localization_can_not_cart_update%22%2C%22value%22%3A%22Can+Not+Cart+Updated%22%7D%2C%7B%22name%22%3A%22add_localization_product_added%22%2C%22value%22%3A%22Product+Added%22%7D%2C%7B%22name%22%3A%22add_localization_can_not_product_added%22%2C%22value%22%3A%22Can+Not+Product+Add%22%7D%2C%7B%22name%22%3A%22add_localization_product_deleted%22%2C%22value%22%3A%22Product+Deleted%22%7D%2C%7B%22name%22%3A%22add_localization_product_deleted_error%22%2C%22value%22%3A%22Can+Not+Product+Deleted%22%7D%2C%7B%22name%22%3A%22add_localization_yith_quick_view%22%2C%22value%22%3A%22Quick+View%22%7D%2C%7B%22name%22%3A%22add_localization_yith_wish_list%22%2C%22value%22%3A%22Wishlist%22%7D%5D',
            'date'        => date('m/d/Y h:i:s', time())
        );

        $wpdb->insert($tble_posts_name, $add_post);

    }

}

/**
 * TODO DEACTIVATION
 * RUN SCRIPT IN DEACTIVE PLUGIN
 */
function Itwpt_Deactivation()
{

    /** GLOBAL VARIABLE */
    global $wpdb;
    $tble_posts_name    = $wpdb->prefix . 'itpt_posts';
    $tble_options_name  = $wpdb->prefix . 'itpt_options';
    $tble_template_name = $wpdb->prefix . 'itpt_itwpt_templates';

    /**
     * TODO this options will be released in next versions
     * $posts    = "DROP TABLE IF EXISTS $tble_posts_name;";
     * $options  = "DROP TABLE IF EXISTS $tble_options_name;";
     * $template = "DROP TABLE IF EXISTS $tble_template_name;";
     *
     * require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
     * $wpdb->query($posts);
     * $wpdb->query($options);
     * $wpdb->query($template);
     */

}

/**
 * TODO GET DATA FROM TABLE
 * GET DATA IN TABLE DATA BASE
 */
function Itwpt_Get_Data_Table($tbl_name, $id = null)
{

    // GLOBAL VARIABLE
    global $wpdb;

    // TABLE NAME
    $table_name = $wpdb->prefix . $tbl_name;
    $row_id     = '';
    $row_order  = ' ORDER BY `id` DESC';

    if ($tbl_name == 'itpt_itwpt_templates') {
        $row_order = ' ORDER BY `id` ASC';
    }

    if ($id != null) {
        $row_id = " WHERE `" . key($id) . "` = '" . $id[key($id)] . "'";
    }

    // GET DATA
    $user = $wpdb->get_results("SELECT * FROM $table_name" . $row_id . $row_order);

    // RETURN DATA
    return $user;

}

function Itwpt_Get_Data_Product_Table()
{
    $list_table = array();
    $list       = Itwpt_Get_Data_Table('itpt_posts');
    if ( ! empty($list)) {
        foreach ($list as $NumList => $item) {
            $list_table[$item->id] = $item->title;
        }
    }

    return $list_table;
}

/**
 * TODO CONVERT OBJECT TO ARRAY
 * CONVERT OBJECT TO ARRAY
 */
function Itwpt_Object_To_Array($obj, &$arr)
{

    if ( ! is_object($obj) && ! is_array($obj)) {

        $arr = $obj;

        return $arr;

    }

    foreach ($obj as $key => $value) {

        if ( ! empty($value)) {

            $arr[$key] = array();
            Itwpt_Object_To_Array($value, $arr[$key]);

        } else {
            $arr[$key] = $value;
        }

    }

    return $arr;

}

/**
 * TODO CREATE FIELDS FORM ADMIN
 * HTML FIELDS ADMIN FORMS AND PAGE
 */
function Itwpt_Form_Fields($array)
{

    $fields     = $array['fields'];
    $temp_array = '';
    $data       = array();

    // SET VALUE DEFAULT
    if ($_GET['page'] == 'itwpt_add_new') {

        if (isset($_GET['edit'])) {
            $temp_array = json_decode(urldecode(! empty(Itwpt_Get_Data_Table('itpt_posts',
                array('id' => sanitize_text_field($_GET['edit'])))) ? Itwpt_Get_Data_Table('itpt_posts',
                array('id' => sanitize_text_field($_GET['edit'])))[0]->data : false));
        }

    } elseif ($_GET['page'] == 'itwpt_template') {

        if (isset($_GET['edit'])) {
            $temp_array = json_decode(urldecode(! empty(Itwpt_Get_Data_Table('itpt_itwpt_templates',
                array('id' =>sanitize_text_field($_GET['edit'])))) ? Itwpt_Get_Data_Table('itpt_itwpt_templates',
                array('id' => sanitize_text_field($_GET['edit'])))[0]->data : false));
        }

    } elseif ($_GET['page'] == 'itwpt_general') {

        $temp_array = json_decode(urldecode(! empty(Itwpt_Get_Data_Table('itpt_options', array(
            'plugin_name' => PREFIX_ITWPT_PLUGIN_NAME,
            'name'        => 'general'
        ))) ? Itwpt_Get_Data_Table('itpt_options',
            array(
                'plugin_name' => PREFIX_ITWPT_PLUGIN_NAME,
                'name'        => 'general'
            ))[0]->data : false));

    }

    if ( ! empty($temp_array)) {

        Itwpt_Object_To_Array($temp_array, $temp_array);
        if (isset($temp_array)) {
            foreach ($temp_array as $m_data) {
                $data[$m_data['name']] = $m_data['value'];
            }
        }

    }

    foreach ($fields as $name => $item) {

        $class           = '';
        $val             = '';
        $heading         = isset($item['heading']) ? $item['heading'] : '';
        $placeholder     = (isset($item['placeholder']) ? esc_attr($item['placeholder']) : '');
        $max             = (isset($item['max']) ? esc_attr($item['max']) : null);
        $min             = (isset($item['min']) ? esc_attr($item['min']) : null);
        $description     = (isset($item['help']) ? $item['help'] : false);
        $dependency_attr = '';

        if ( ! empty($data)) {
            if (isset($data[$name])) {
                $val = $data[$name];
            }
        } else {
            $val = isset($item['default']) ? $item['default'] : '';
        }

        // SET CLASS
        $class        .= 'itwpt-field-name-' . $name;
        if (isset($item['dependency'])) {
            $class           .= ' dependency';
            $dependency_attr .= 'data-dependency-elm="' . esc_attr($item['dependency']['element']) . '" data-dependency-value="' . esc_attr($item['dependency']['value']) . '" data-dependency-not="' . ($item['dependency']['not'] ? 'true' : 'false') . '"';
        }

        // RESPONSIVE
        if (isset($item['responsive'])) {

            if (isset($item['responsive']['desktop'])) {
                $class .= ' col-lg-' . $item['responsive']['desktop'];
            }
            if (isset($item['responsive']['laptop'])) {
                $class .= ' col-md-' . $item['responsive']['laptop'];
            }
            if (isset($item['responsive']['tablet'])) {
                $class .= ' col-sm-' . $item['responsive']['tablet'];
            }
            if (isset($item['responsive']['mobile'])) {
                $class .= ' col-' . $item['responsive']['mobile'];
            }

        } else {
            $class .= ' col-12';
        }

        // FIELDS
        switch ($item['type']) {

            case 'text-box': // TEXT BOX
                ?>
                <div class="itwpt-fields itwpt-field-text-box <?php echo esc_attr($class); ?>" <?php printf($dependency_attr); ?>>
                    <div class="heading">
                        <label for="<?php echo esc_attr($name); ?>">
                            <?php echo esc_html($heading); ?>
                            <?php
                            if ($description) {
                                ?>
                                <div class="desc"><?php echo esc_html($description); ?></div>
                                <?php
                            }
                            ?>
                        </label>
                    </div>
                    <div class="input">
                        <input class="save" type="text" id="<?php echo esc_attr($name); ?>"
                               name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($val); ?>"
                               placeholder="<?php echo esc_attr($placeholder); ?>">
                    </div>
                </div>
                <?php
                break;

            case 'number': // NUMBER
                ?>
                <div class="itwpt-fields itwpt-field-number <?php echo esc_attr($class); ?>" <?php printf($dependency_attr); ?>>
                    <div class="heading">
                        <label for="<?php echo esc_attr($name); ?>">
                            <?php echo esc_html($heading); ?>
                            <?php
                            if ($description) {
                                ?>
                                <div class="desc"><?php echo esc_html($description); ?></div>
                                <?php
                            }
                            ?>
                        </label>
                    </div>
                    <div class="input">
                        <div class="number-controler">
                            <input class="save" type="number" id="<?php echo esc_attr($name); ?>"
                                   name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($val); ?>"
                                   placeholder="<?php echo esc_attr($placeholder); ?>" <?php printf(isset($max) ? 'max="' . $max . '"' : ''); ?> <?php printf(isset($min) ? 'min="' . $min . '"' : ''); ?>>
                            <div class="number-spinner">
                                <i class="up icon-z arrow-down"></i>
                                <i class="down icon-z arrow-down"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                break;

            case 'dropdown': // DROPDOWN
                ?>
                <div class="itwpt-fields itwpt-field-dropdown <?php echo esc_attr($class); ?>" <?php printf($dependency_attr); ?>>
                    <div class="heading">
                        <label for="<?php echo esc_attr($name); ?>">
                            <?php echo esc_html($heading); ?>
                            <?php
                            if ($description) {
                                ?>
                                <div class="desc"><?php echo esc_html($description); ?></div>
                                <?php
                            }
                            ?>
                        </label>
                    </div>
                    <div class="input">
                        <div class="select-controler">
                            <select class="save" id="<?php echo esc_attr($name); ?>"
                                    name="<?php echo esc_attr($name); ?>">
                                <?php

                                foreach ($item['options'] as $value => $text) {
                                    ?>
                                    <option value="<?php echo esc_attr($value); ?>" <?php printf($val == $value ? 'selected="selected"' : ''); ?>><?php echo esc_html($text); ?></option>
                                    <?php
                                }

                                ?>
                            </select>
                            <i class="icon-z arrow-down"></i>
                        </div>
                    </div>
                </div>
                <?php
                break;

            case 'checkbox': // CHECKBOX
                ?>

                <div class="itwpt-fields itwpt-field-checkbox <?php echo esc_attr($class); ?>" <?php printf($dependency_attr); ?>>
                    <div class="heading">
                        <label for="<?php echo esc_attr($name); ?>">
                            <?php echo esc_html($heading); ?>
                            <?php
                            if ($description) {
                                ?>
                                <div class="desc"><?php echo esc_html($description); ?></div>
                                <?php
                            }
                            ?>
                        </label>
                    </div>
                    <div class="input">
                        <div class="options">
                            <?php
                            foreach ($item['options'] as $option) {

                                $active_class = '';
                                if (isset($val) && $val != '') {
                                    $active_class = in_array($option['value'], explode(',', $val)) ? 'active' : '';
                                } else {
                                    $active_class = $option['active'] ? 'active' : '';
                                }
                                ?>

                                <div class="option <?php echo esc_attr($active_class); ?>"
                                     data-val="<?php echo esc_attr($option['value']); ?>">
                                    <div class="selector-box"></div>
                                    <span><?php echo esc_html($option['text']); ?></span>
                                </div>

                                <?php
                            }
                            ?>
                        </div>
                        <input class="save" type="hidden" id="<?php echo esc_attr($name); ?>"
                               name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($val); ?>">
                    </div>
                </div>

                <?php
                break;

            case 'radio': // RADIO
                ?>

                <div class="itwpt-fields itwpt-field-radio <?php echo esc_attr($class); ?>" <?php printf($dependency_attr); ?>>
                    <div class="heading">
                        <label for="<?php echo esc_attr($name); ?>">
                            <?php echo esc_html($heading); ?>
                            <?php
                            if ($description) {
                                ?>
                                <div class="desc"><?php echo esc_html($description); ?></div>
                                <?php
                            }
                            ?>
                        </label>
                    </div>
                    <div class="input">
                        <div class="options">
                            <?php
                            foreach ($item['options'] as $valoption => $option) {

                                $active_class = '';
                                if (isset($val) && $val != '') {
                                    $active_class = in_array($valoption, explode(',', $val)) ? 'active' : '';
                                } else {
                                    $active_class = $option['active'] ? 'active' : '';
                                }
                                ?>

                                <div class="option <?php echo esc_attr($active_class); ?>"
                                     data-val="<?php echo esc_attr($valoption); ?>">
                                    <div class="selector-box"></div>
                                    <span><?php echo esc_html($option['text']); ?></span>
                                </div>

                                <?php
                            }
                            ?>
                        </div>
                        <input class="save" type="hidden" id="<?php echo esc_attr($name); ?>"
                               name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($val); ?>">
                    </div>
                </div>

                <?php
                break;

            case 'color-picker': // COLOR PICKER
                ?>

                <div class="itwpt-fields itwpt-field-color-picker <?php echo esc_attr($class); ?>" <?php printf($dependency_attr); ?>>
                    <div class="heading">
                        <label for="<?php echo esc_attr($name); ?>">
                            <?php echo esc_html($item['heading']); ?>
                            <?php
                            if ($description) {
                                ?>
                                <div class="desc"><?php echo esc_html($description); ?></div>
                                <?php
                            }
                            ?>
                        </label>
                    </div>
                    <div class="input">
                        <div class="pagebox">
                            <input class="color_field save" type="text" id="<?php echo esc_attr($name); ?>"
                                   name="<?php echo esc_attr($name); ?>" value="<?php printf($val); ?>"/>
                        </div>
                    </div>
                </div>

                <?php
                break;

            case 'multi-select': // MULTI SELECT

                if (isset($item['ajax_action'])) {
                    $class .= ' ajax-action';
                }

                $multi_select_options = array();
                if ($item['ajax_action'] === 'get_product') {

                    if ( ! empty($val)) {
                        $_a = explode(',', $val);
                        if ( ! empty($_a)) {
                            foreach ($_a as $_b) {
                                $multi_select_options[$_b] = get_the_title($_b);
                            }
                            if ( ! empty($val)) {
                                $item['options'] = $multi_select_options;
                            }
                        }
                    }

                } else {

                    if ( ! empty($val)) {
                        $_a = explode(',', $val);
                        if ( ! empty($_a)) {
                            foreach ($_a as $_b) {
                                $multi_select_options[$_b] = get_term($_b)->name;
                            }
                            if ( ! empty($val)) {
                                $item['options'] = $multi_select_options;
                            }
                        }
                    }

                }

                ?>

                <div class="itwpt-fields itwpt-field-multi-select <?php echo esc_attr($class); ?>" <?php printf(isset($item['ajax_action']) ? 'data-action="' . esc_attr($item['ajax_action']) . '""' : ''); ?>  <?php printf($dependency_attr); ?>>
                    <div class="heading">
                        <label for="<?php echo esc_attr($name); ?>">
                            <?php echo esc_html($item['heading']); ?>
                            <?php
                            if ($description) {
                                ?>
                                <div class="desc"><?php echo esc_html($description); ?></div>
                                <?php
                            }
                            ?>
                        </label>
                    </div>
                    <div class="input">
                        <select class="multi-select" multiple="multiple">
                            <?php

                            if (isset($item['options'])) {
                                foreach ($item['options'] as $value => $text) {
                                    var_dump($value);
                                    ?>

                                    <option value="<?php echo esc_attr(strtolower($value)); ?>" <?php printf(isset($item['default']) && $item['default'] == $value ? (' selected="selected"') : 'selected="selected"'); ?>><?php echo esc_html(strtolower($text)); ?></option>

                                    <?php
                                }
                            }

                            ?>
                        </select>
                        <input class="save" type="hidden" id="<?php echo esc_attr($name); ?>"
                               name="<?php echo esc_attr($name); ?>" value="<?php printf($val); ?>">
                    </div>
                </div>

                <?php
                break;

            case 'multi-select-category': // MULTI SELECT


                // CATEGORY
                $array = array();
                $args = array(
                    'taxonomy'     => $item['ajax_action'],
                    'orderby'      => 'name',
                    'show_count'   => 0,
                    'pad_counts'   => 0,
                    'hierarchical' => 1,
                    'title_li'     => '',
                    'hide_empty'   => 0
                );

                $all_categories = get_categories($args);

                $options = '';

                foreach ($all_categories as $index => $items) {
                    $selected = '';

                    if ( ! empty($val)) {
                        $_a = explode(',', $val);
                        if (in_array($items->term_id, $_a)) {
                            $selected = 'SELECTED';
                        }
                    }

                    $options .= '<option value="' . $items->term_id . '" ' . $selected . ' >' . $items->name . '</option>';

                }


                ?>

                <div class="itwpt-fields itwpt-field-multi-select <?php echo esc_attr($class); ?>" <?php printf(isset($item['ajax_action']) ? 'data-action="' . esc_attr($item['ajax_action']) . '""' : ''); ?>  <?php printf($dependency_attr); ?>>
                    <div class="heading">
                        <label for="<?php echo esc_attr($name); ?>">
                            <?php echo esc_html($item['heading']); ?>
                            <?php
                            if ($description) {
                                ?>
                                <div class="desc"><?php echo esc_html($description); ?></div>
                                <?php
                            }
                            ?>
                        </label>
                    </div>
                    <div class="input">
                        <select class="multi-select" multiple="multiple">
                            <?php

                            echo $options;

                            ?>
                        </select>
                        <input class="save" type="hidden" id="<?php echo esc_attr($name); ?>"
                               name="<?php echo esc_attr($name); ?>" value="<?php printf($val); ?>">
                    </div>
                </div>

                <?php
                break;

            case 'video-link':
                ?>

                <div class="itwpt-fields itwpt-field-video-link <?php echo esc_attr($class); ?>" <?php printf($dependency_attr); ?>>
                    <div class="itwpt-inner-box">
                        <a href="<?php echo esc_html($item['link']); ?>">
                            <img src="<?php echo esc_url(PREFIX_ITWPT_IMAGE_URL . 'video-learn-banner-mini.png') ?>">
                            <span>
                                <?php echo esc_html($item['text']); ?>
                            </span>
                        </a>
                        <a href="<?php echo esc_html($item['download']); ?>" class="download-link">
                            Download Pro Version
                        </a>
                    </div>
                </div>

                <?php
                break;

            case 'columns': // COLUMNS

                $main_item = $item;
                if ( ! empty($val)) {

                    $array_to_option = array();
                    foreach (json_decode($val) as $a) {
                        $array_to_option[$a->value] =
                            array(
                                'default'     => $a->text,
                                'placeholder' => $a->placeholder,
                                'type'        => $a->type,
                                'desktop'     => $a->desktop == 'active' ? true : false,
                                'laptop'      => $a->laptop == 'active' ? true : false,
                                'mobile'      => $a->mobile == 'active' ? true : false
                            );
                    }
                    $item['options'] = $array_to_option;

                    //Display new added column
                    $result          = array_diff_key($main_item['options'], $item['options']);
                    $item['options'] = array_merge($item['options'], $result);
                }
                ?>

                <div class="itwpt-fields itwpt-field-columns <?php echo esc_attr($class); ?>" <?php printf($dependency_attr); ?>>
                    <div class="heading">
                        <label for="<?php echo esc_attr($name); ?>">
                            <?php echo esc_html($item['heading']); ?>
                            <?php
                            if ($description) {
                                ?>
                                <div class="desc"><?php echo esc_html($description); ?></div>
                                <?php
                            }
                            ?>
                        </label>
                    </div>
                    <div class="input">

                        <div class="row">
                            <div class="col-lg-7">

                                <ul class="items">
                                    <?php

                                    foreach ($item['options'] as $value => $text) {
                                        ?>

                                        <li>
                                            <div class="item-inner <?php echo esc_attr($text['desktop'] || $text['laptop'] || $text['mobile'] ? '' : 'disable'); ?>">
                                                <div>
                                                    <div class="handle"><i class="icon-z move"></i></div>
                                                </div>
                                                <div class="input-column">
                                                    <input type="text"
                                                           id="<?php echo esc_attr($name . '_' . $value); ?>"
                                                           name="<?php echo esc_attr($name . '_' . $value); ?>" <?php printf(isset($text['default']) ? ('value="' . esc_attr($text['default']) . '"') : ''); ?> <?php printf(isset($text['placeholder']) ? ('placeholder="' . esc_attr($text['placeholder']) . '"') : ''); ?>
                                                           data-val="<?php echo esc_attr($value); ?>"
                                                           data-type="<?php echo esc_attr($text['type']); ?>">
                                                </div>
                                                <div class="responsive">

                                                    <?php

                                                    if ($text['type'] !== '-') {
                                                        ?>
                                                        <div class="remove itwpt-tooltip"
                                                             data-tooltip-text="<?php echo esc_html__('Remove Column',
                                                                 PREFIX_ITWPT_TEXTDOMAIN); ?>">
                                                            <i class="icon-z trash"></i>
                                                        </div>
                                                        <?php
                                                    }

                                                    ?>

                                                    <div class="desktop itwpt-tooltip <?php echo esc_attr($text['desktop'] ? 'active' : ''); ?>"
                                                         data-tooltip-text="<?php echo esc_html__('Desktop (>768)',
                                                             PREFIX_ITWPT_TEXTDOMAIN); ?>">
                                                        <i class="icon-z desktop"></i></div>
                                                    <div class="laptop itwpt-tooltip <?php echo esc_attr($text['laptop'] ? 'active' : ''); ?>"
                                                         data-tooltip-text="<?php echo esc_html__('Tablet (768 - 576)',
                                                             PREFIX_ITWPT_TEXTDOMAIN); ?>">
                                                        <i class="icon-z tablet"></i></div>
                                                    <div class="mobile itwpt-tooltip <?php echo esc_attr($text['mobile'] ? 'active' : ''); ?>"
                                                         data-tooltip-text="<?php echo esc_html__('Mobile (<576)',
                                                             PREFIX_ITWPT_TEXTDOMAIN); ?>">
                                                        <i class="icon-z phone"></i></div>

                                                </div>
                                            </div>
                                        </li>

                                        <?php
                                    }

                                    ?>
                                </ul>
                            </div>
                            <div class="col-lg-5 col-md-12">
                                <div class="column-add-row">
                                    <div class="item warning">
                                        <div class="icon">
                                            <i class="icon-z warning"></i>
                                        </div>
                                        <div class="text">
                                            <?php echo esc_html($item['warning']); ?>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="heading">
                                            <label><?php echo esc_html__('choose type',
                                                    PREFIX_ITWPT_TEXTDOMAIN); ?></label>
                                        </div>
                                        <div class="select-controler">
                                            <select class="type">
                                                <option value="taxonomy"><?php echo esc_html__('Custom Taxonomy',
                                                        PREFIX_ITWPT_TEXTDOMAIN); ?></option>
                                                <option value="post"><?php echo esc_html__('Custom Field',
                                                        PREFIX_ITWPT_TEXTDOMAIN); ?></option>
                                            </select>
                                            <i class="icon-z arrow-down"></i>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="heading">
                                            <label><?php echo esc_html__('keyword', PREFIX_ITWPT_TEXTDOMAIN); ?></label>
                                        </div>
                                        <input type="text" class="keyword" placeholder="eg: Color">
                                    </div>
                                    <div class="item">
                                        <div class="heading">
                                            <label><?php echo esc_html__('Select Taxonomy',
                                                    PREFIX_ITWPT_TEXTDOMAIN); ?></label>
                                        </div>
                                        <?php echo Itwpt_Get_Taxonomy_Product(); ?>
                                    </div>
                                    <div class="item">
                                        <div class="heading">
                                            <label><?php echo esc_html__('table column title/Name',
                                                    PREFIX_ITWPT_TEXTDOMAIN); ?></label>
                                        </div>
                                        <input type="text" class="title" placeholder="eg: Product Color">
                                    </div>
                                    <div class="item">
                                        <button type="button"
                                                class="btn btn-primary" style="background-color: #71766e"><?php echo esc_html__('Add Column in Pro Version',
                                                PREFIX_ITWPT_TEXTDOMAIN); ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input class="save" type="hidden" id="<?php echo esc_attr($name); ?>"
                               name="<?php echo esc_attr($name); ?>" value='<?php printf($val); ?>'>
                    </div>
                </div>

                <?php
                break;

            case 'media': // MEDIAL
                ?>

                <div class="itwpt-fields itwpt-field-media <?php echo esc_attr($class); ?>" <?php printf($dependency_attr); ?>>
                    <div class="heading">
                        <label for="<?php echo esc_attr($name); ?>">
                            <?php echo esc_html($item['heading']); ?>
                            <?php
                            if ($description) {
                                ?>
                                <div class="desc"><?php echo esc_html($description); ?></div>
                                <?php
                            }
                            ?>
                        </label>
                    </div>
                    <div class="input">
                        <div class="media">
                            <ul>
                                <li>
                                    <div class="add-image"
                                         data-select="<?php echo esc_attr(isset($item['multi-select']) ? $item['multi-select'] == 'true' ? 'true' : 'false' : 'false'); ?>">
                                        <i class="icon-z add"></i></div>
                                </li>
                                <?php
                                $item_list = json_decode($val);
                                if ( ! empty($item_list)) {
                                    foreach ($item_list as $attr) {
                                        ?>

                                        <li class="item"
                                            style="background-image: url('<?php echo esc_url($attr->url); ?>')"
                                            data-setting='<?php print_r(json_encode($attr)); ?>'>
                                            <div class="more">
                                                <i class="icon-z trash"></i>
                                            </div>
                                        </li>

                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                        <input class="save" type="hidden" id="<?php echo esc_attr($name); ?>"
                               name="<?php echo esc_attr($name); ?>" value='<?php printf($val); ?>'>
                    </div>
                </div>

                <?php
                break;

            case 'template': // TEMPLATE
                $class = isset($item['select']) ? $item['select'] == 'true' ? 'select_enable' : null : null;
                ?>

                <div class="itwpt-fields itwpt-field-template <?php echo esc_attr($class); ?>" <?php printf($dependency_attr); ?>>
                    <div class="heading">
                        <label for="<?php echo esc_attr($name); ?>">
                            <?php echo esc_html($item['heading']); ?>
                            <?php
                            if ($description) {
                                ?>
                                <div class="desc"><?php echo esc_html($description); ?></div>
                                <?php
                            }
                            ?>
                        </label>
                    </div>
                    <div class="input">
                        <div class="template-list">
                            <ul>
                                <li>
                                    <a href="<?php echo esc_url(get_admin_url() . 'admin.php?page=itwpt_template&form=true'); ?>">
                                        <div class="add-template">
                                            <div class="add-template-inner">
                                                <i class="icon-z add"></i>
                                                <span><?php echo esc_html__('Add New Template',
                                                        PREFIX_ITWPT_TEXTDOMAIN); ?></span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <?php
                                $templates = Itwpt_Get_Data_Table('itpt_itwpt_templates');
                                if ( ! empty($templates)) {
                                    foreach ($templates as $template_item) {
                                        ?>

                                        <li class="item <?php echo esc_attr($val == $template_item->id ? 'active' : ''); ?>"
                                            style="background: url('<?php echo esc_url(! empty($template_item->image) ? $template_item->image : ''); ?>')"
                                            data-id="<?php echo esc_attr($template_item->id); ?>">
                                            <div class="more">
                                                <i class="icon-z trash"></i>
                                                <a href="<?php echo esc_url(get_admin_url() . 'admin.php?page=itwpt_template&form=true&edit=' . esc_attr($template_item->id)); ?>">
                                                    <i class="icon-z edit"></i>
                                                </a>
                                                <div><?php echo esc_html($template_item->name); ?></div>
                                            </div>
                                        </li>

                                        <?php
                                    }
                                }
                                ?>

                            </ul>
                        </div>
                        <input class="save" type="hidden" id="<?php echo esc_attr($name); ?>"
                               name="<?php echo esc_attr($name); ?>" value='<?php printf($val); ?>'>
                    </div>
                </div>

                <?php
                break;

            case 'separator': // SEPARATOR
                ?>

                <div class="itwpt-fields itwpt-field-separator <?php echo esc_attr($class); ?>" <?php printf($dependency_attr); ?>>
                    <div class="separator"></div>
                </div>

                <?php
                break;

            case 'heading': // HEADING
                ?>

                <div class="itwpt-fields itwpt-field-heading <?php echo esc_attr($class); ?>" <?php printf($dependency_attr); ?>>
                    <div class="heading">
                        <?php echo esc_html($item['heading']); ?>
                    </div>
                </div>

                <?php
                break;

            default:
                ?>

                <div class="itwpt-fields itwpt-error-field col-12">
                    <div class="form-create-field-error">
                        <span><?php echo esc_html__('Error:',
                                PREFIX_ITWPT_TEXTDOMAIN) ?></span><?php echo esc_html__(' Can not create field. FIELDNAME=',
                                PREFIX_ITWPT_TEXTDOMAIN) . '<i>' . esc_html($name) . '</i>' . esc_html__(', TYPE= ',
                                PREFIX_ITWPT_TEXTDOMAIN) . '<i>' . esc_html($item['type']) . '</i>'; ?>
                    </div>
                </div>

                <?php
                break;

        }
    }

}

/**
 * TODO CREATOR HELP
 * HTML HELP - IN FORM-FIELDS
 */
function Itwpt_Form_Help($array)
{

    $fields = $array['help'];
    foreach ($fields as $name => $item) {
        ?>

        <div class="section">
            <div class="header">
                <?php echo esc_html($item['header']); ?>
            </div>
            <div class="description">
                <?php echo esc_html($item['description']); ?>
            </div>
        </div>

        <?php
    }

}

/**
 * TODO LOADING HTML
 * CREATE LOADING HTML AND CSS
 */
function Itwpt_Loading()
{
    ?>

    <div class="loading">
        <div class="loading-inner">
            <img src="<?php echo esc_url(PREFIX_ITWPT_IMAGE_URL . 'svg/itwpt_dark.svg'); ?>">
            <div class="loading-text">
                <img src="<?php echo esc_url(PREFIX_ITWPT_IMAGE_URL . 'loading_bs.gif') ?>">
                <?php echo esc_html__('Loading', PREFIX_ITWPT_TEXTDOMAIN) ?>...
            </div>
        </div>
        <style>
            .loading {
                position: fixed;
                top: 32px;
                bottom: 0;
                left: 160px;
                right: 0;
                z-index: 99999999;
                background-color: #fff;
            }

            .loading img {
                width: 160px;
                height: 160px;
            }

            .loading-inner {
                position: absolute;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
                margin: auto;
                width: 160px;
                height: 200px;
                text-align: center;
            }

            .loading-text {
                margin-top: 16px;
                font-weight: bold;
                font-size: 16px;
            }

            .loading-text img {
                display: inline-block;
                width: 20px;
                height: 20px;
                margin-right: 10px;
                vertical-align: middle;
            }

            body.folded .loading {
                left: 36px;
            }
        </style>
    </div>

    <?php
}

/**
 * TODO STRING FORMAT
 * STRING FORMAT
 */
function Itwpt_String_Format($str, $vars)
{

    // VARIABLES
    $tmp = array();

    // ADD % TO NAME ARRAY
    foreach ($vars as $index => $val) {
        $tmp['%' . $index . '%'] = $val;
    }

    return str_replace(array_keys($tmp), array_values($tmp), $str);

}

/**
 * TODO SET DATA IN GLOBAL VARIABLE
 * SET VARIABLE IN GLOBAL VARIABLE
 *
 * @param $data data in form
 */
function Itwpt_Data_query($data)
{

    global $wpdb;
    global $itwpt_query_data;
    $rdata = array();

    $rdata['post_type']      = 'product';
    $rdata['post_status']    = 'publish';
    $rdata['search_product'] = (isset($_REQUEST['search']) ? sanitize_text_field($_REQUEST['search']) : null);
    $rdata['product_status'] = (isset($_REQUEST['product_status']) ? sanitize_text_field($_REQUEST['product_status']) : 'all');
    $rdata['orderby']        = (isset($_REQUEST['product_order_by']) ? sanitize_text_field($_REQUEST['product_order_by']) : $data['add_query_order_by']);
    $rdata['order']          = (isset($_REQUEST['product_order']) ? sanitize_text_field($_REQUEST['product_order']) : $data['add_query_order']);
    $rdata['sku']            = (isset($_REQUEST['sku']) ? sanitize_text_field($_REQUEST['sku']) : null);
    $rdata['paged']          = (isset($_REQUEST['paged']) ? sanitize_text_field($_REQUEST['paged']) : 1);

    // PAGINATION
    $rdata['add_localization_pagination_next_text'] = $data['add_localization_pagination_next_text'];
    $rdata['add_localization_pagination_prev_text'] = $data['add_localization_pagination_prev_text'];

    // VARIABLE
    $rdata['add_query_product_include_id'] = $data['add_query_product_include_id'];
    $rdata['add_query_product_exclude_id'] = $data['add_query_product_exclude_id'];


    //Taxonomy Query
    $all_tax = get_object_taxonomies('product');
    if (is_array($all_tax) && count($all_tax) > 0) {

        //FETCH TAXONOMY
        foreach ($all_tax as $tax) {

            if ($tax == 'product_visibility' || $tax == 'product_type') {
                continue;
            }

            $rdata['add_query_' . $tax . '_include'] = (isset($_REQUEST['it' . $tax]) ? sanitize_text_field($_REQUEST['it' . $tax]) : (isset($data['add_query_' . $tax . '_include']) ? $data['add_query_' . $tax . '_include'] : ""));
            $rdata['add_query_' . $tax . '_exclude'] = (isset($_REQUEST['it' . $tax]) ? sanitize_text_field($_REQUEST['it' . $tax]) : (isset($data['add_query_' . $tax . '_exclude']) ? $data['add_query_' . $tax . '_exclude'] : ""));
        }
    }

    $main_price_min = floor($wpdb->get_var(
        '
        SELECT min(meta_value + 0)
        FROM ' . $wpdb->posts . ' as post
        LEFT JOIN ' . $wpdb->postmeta . ' as meta ON post.ID = meta.post_id
        WHERE ( meta_key = "_price" OR meta_key = "_min_variation_price" )
        AND meta_value != ""'

    ));

    $main_price_max = ceil($wpdb->get_var(
        '
        SELECT max(meta_value + 0)
        FROM ' . $wpdb->posts . ' as post
        LEFT JOIN ' . $wpdb->postmeta . ' as meta ON post.ID = meta.post_id
        WHERE meta_key = "_price"'

    ));

    $rdata['add_conditions_min_price']  = (empty($data['add_conditions_min_price']) ? $main_price_min : $data['add_conditions_min_price']);
    $rdata['add_conditions_max_price']  = (empty($data['add_conditions_max_price']) ? $main_price_max : $data['add_conditions_max_price']);
    $rdata['add_conditions_post_limit'] = 10;

    // PRICE CONTROL
    if (isset($_REQUEST['price_range'])) {
        $price_explode                     = explode('-', sanitize_text_field($_REQUEST['price_range']));
        $rdata['add_conditions_min_price'] = $price_explode[0];
        $rdata['add_conditions_max_price'] = $price_explode[1];
    }

    // SET DATA
    $itwpt_query_data = $rdata;

}

/**
 * TODO QUERY
 * RUN QUERY WITH ITWPT_DATA_QUERY
 *
 * @param $data $get data query
 *
 * @return WP_Query object for return query
 */
function Itwpt_Query_Front($params = [])
{

    global $itwpt_query_data;
    $data = $itwpt_query_data;
    $args = array(
        'post_type'      => $data['post_type'],
        'post_status'    => $data['post_status'],
        'posts_per_page' => ! empty($data['add_conditions_post_limit']) ? $data['add_conditions_post_limit'] : '-1',
        'paged'          => $data['paged'],
    );

    $args = array_merge($args, array(
        'meta_query' => array(),
    ));

    // POST IN
    if ( ! empty($data['add_query_product_include_id'])) {
        $args = array_merge($args, array(
            'post__in' => explode(',', $data['add_query_product_include_id']),
        ));
    }

    // POST NOT IN
    if ( ! empty($data['add_query_product_exclude_id'])) {
        $args = array_merge($args, array(
            'post__not_in' => explode(',', $data['add_query_product_exclude_id']),
        ));
    }


    // TAX QUERY
    if (1) {

        $args = array_merge($args, array(
            'relation'  => 'AND',
            'tax_query' => array(

            ),

        ));

        //Taxonomy Query
        $all_tax = get_object_taxonomies('product');
        if (is_array($all_tax) && count($all_tax) > 0) {
            //FETCH TAXONOMY
            foreach ($all_tax as $tax) {

                if ($tax == 'product_visibility' || $tax == 'product_type') {
                    continue;
                }
                if (isset($params['term']) && isset($params['term_id']) && $params['term'] == $tax) {
                    $data['add_query_' . $tax . '_include'] .= ',' . $params['term_id'];
                }
                // CATEGORY INCLUDE
                if ( ! empty($data['add_query_' . $tax . '_include'])) {
                    $args['tax_query'] = array_merge($args['tax_query'], array(
                        array(
                            'taxonomy' => $tax,
                            'field'    => 'term_id', //This is optional, as it defaults to 'term_id'
                            'terms'    => explode(',', $data['add_query_' . $tax . '_include']),
                            'operator' => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
                        ),
                    ));
                }

                // CATEGORY EXCLUDE
                if ( ! empty($data['add_query_' . $tax . '_exclude'])) {
                    $args['tax_query'] = array_merge($args['tax_query'], array(
                        array(
                            'taxonomy' => $tax,
                            'field'    => 'term_id', //This is optional, as it defaults to 'term_id'
                            'terms'    => explode(',', $data['add_query_' . $tax . '_exclude']),
                            'operator' => 'NOT IN' // Possible values are 'IN', 'NOT IN', 'AND'.
                        ),
                    ));
                }

            }
        }

    }

    // IN STOCK AND MAX AND MIN PRICE
    if ( ! empty($data['add_conditions_max_price']) || ! empty($data['add_conditions_min_price'])) {
        $args = array_merge($args, array(
            'meta_query' => array('relation' => 'AND'),
        ));

        if ( ! empty($data['add_conditions_max_price']) && ! empty($data['add_conditions_min_price'])) {
            $args['meta_query'] = array_merge($args['meta_query'], array(
                array(
                    'key'     => "_price",
                    'value'   => array(
                        $data['add_conditions_min_price'],
                        $data['add_conditions_max_price']
                    ),
                    'type'    => 'numeric',
                    'compare' => 'BETWEEN'
                ),
            ));
        } elseif (empty($data['add_conditions_max_price']) && ! empty($data['add_conditions_min_price'])) {

            $args['meta_query'] = array_merge($args['meta_query'], array(
                array(
                    'key'     => "_price",
                    'value'   => $data['add_conditions_min_price'],
                    'type'    => 'numeric',
                    'compare' => '>='
                ),
            ));
        } elseif ( ! empty($data['add_conditions_max_price']) && empty($data['add_conditions_min_price'])) {
            $args['meta_query'] = array_merge($args['meta_query'], array(
                array(
                    'key'     => "_price",
                    'value'   => $data['add_conditions_max_price'],
                    'type'    => 'numeric',
                    'compare' => '<='
                ),
            ));
        }
    }

    // SKU
    if ( ! empty($data['sku'])) {

        $args['meta_query'] = array_merge($args['meta_query'], array(
            array(
                "key"     => "_sku",
                "value"   => $data['sku'],
                "compare" => "Like"
            ),
        ));

    }

    //PRODUCT STATUS
    if ( ! empty($data['product_status'])) {
        $product_status = $data['product_status'];

        foreach (explode(',', $product_status) as $p_status) {

            if ($p_status === 'all') {
                break;
            }

            switch ($p_status) {
                case "in_stock" :
                    {
                        $args['meta_query'] = array_merge($args['meta_query'], array(
                            array(
                                'key'   => '_stock_status',
                                'value' => 'instock'
                            ),
                        ));
                    }
                    break;

                case "featured" :
                    {
                        $args['tax_query'] = array_merge($args['tax_query'], array(
                            array(
                                'taxonomy' => 'product_visibility',
                                'field'    => 'slug',
                                'terms'    => 'featured',
                            )
                        ));
                    }
                    break;

                case "on_sale":
                    {
                        $args['meta_query'] = array_merge($args['meta_query'], array(
                            array(
                                'key'     => '_sale_price',
                                'value'   => 0,
                                'compare' => '>',
                                'type'    => 'numeric'
                            )
                        ));
                    }
                    break;
            }

        }

    }



    //SEARCH TITLE
    if (isset($params['search_term'])) {

        $args['search_title'] = $params['search_term'];
    }
    if ( ! empty($data['search_product'])) {
        $args['search_title'] = $data['search_product'];
    }


    //ORDER BY
    if ( ! empty($data['orderby'])) {

        //TODO  add other order fields

        //ORDER BY
        $it_meta_key = '';
        $it_orderby  = '';
        $it_orderby  = $data['orderby'];


        $public_orders_array = array(
            'ID',
            'date',
            'author',
            'title',
            'modified',
            'rand',
            'comment_count',
            'menu_order'
        );
        if ( ! in_array($it_orderby, $public_orders_array)) {
            $it_meta_key = $it_orderby;
            $it_orderby  = 'meta_value';
            if ($it_meta_key == '_featured' || $it_meta_key == '_sku') {
                $it_orderby = 'meta_value';
            }

            if ($it_meta_key == '_price' || $it_meta_key == '_stock' || $it_meta_key == 'total_sales' || $it_meta_key == '_wc_average_rating') {
                $it_orderby        = 'meta_value_num';
                $args['mata_type'] = 'NUMERIC';
            }
        }

        $args['meta_key'] = $it_meta_key;
        $args['orderby']  = $it_orderby;
        //$args['mata_type']  = 'NUMERIC';

    }

    if ( ! empty($data['order']) && $data['order'] != 'random') {

        $args['order'] = $data['order'];
    }

    //print_r($args);

    return new WP_Query($args);

}

/**
 * TODO ROW CREATOR
 * CREATE HTML ROW IN BEFORE AND AFTER
 *
 * @param $query result wp_query
 * @param $itwptRand random variable
 *
 * @return string html <tr> table
 */
function Itwpt_Create_Row($query, $itwptRand)
{
    global $itwpt_data;
    $data      = $itwpt_data;
    $itwptRows = '';

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();

            global $product;
            global $itwpt_rand;

//            $pricing_rule_sets = WC_Dynamic_Pricing_Compatibility::get_product_meta( $product, '_pricing_rules' );
//            $sets              = array();
//            if ( $pricing_rule_sets ) {
//                foreach ( $pricing_rule_sets as $set_id => $set_data ) {
//                    $sets[ $set_id ] = new WC_Dynamic_Pricing_Adjustment_Set_Product( $set_id, $set_data );
//                }
//            }
//            print_r($sets);

            $itwpt_rand = $itwptRand;
            $itwptRows  .= '<tr data-variation-product="' . Itwpt_Get_Variation_Data_Json(get_the_ID()) . '" class="itwpt_' . esc_attr($itwptRand . get_the_ID()) . '">';

            // COLUMNS TABLE
            if ( ! empty($data['add_column_table_column'])) {
                $column = json_decode($data['add_column_table_column']);


                $col1 = $col2 = $col3 = '';


                foreach ($column as $cl) {




                    /*if($cl->value=='check'){
                        $itwptRows .= '<td class="' . esc_attr($cl->value) . '_tbl_column ' . esc_attr($cl->value) . '_custom_column ' . esc_attr($cl->value === 'product_title' && $data['add_checklist_title_in_one_line'] === 'enable' ? 'in-one-line' : '') . '">' . Itwpt_Get_Cl_Product($cl,
                                $data, $product) . '</td>';
                    }

                    if($cl->value=='id'){
                        $itwptRows .= '<td class="' . esc_attr($cl->value) . '_tbl_column ' . esc_attr($cl->value) . '_custom_column ' . esc_attr($cl->value === 'product_title' && $data['add_checklist_title_in_one_line'] === 'enable' ? 'in-one-line' : '') . '">' . Itwpt_Get_Cl_Product($cl,
                                $data, $product) . '</td>';
                    }

                    if($cl->value == 'thumbnails'){
                        $itwptRows .= '<td class="' . esc_attr($cl->value) . '_tbl_column ' . esc_attr($cl->value) . '_custom_column ' . esc_attr($cl->value === 'product_title' && $data['add_checklist_title_in_one_line'] === 'enable' ? 'in-one-line' : '') . '">' . Itwpt_Get_Cl_Product($cl,
                                $data, $product) . '</td>';
                    }


                    if($cl->value == 'product_title' || $cl->value == 'description' || $cl->value == 'category'){
                        $col2 .= Itwpt_Get_Cl_Product($cl,$data, $product);
                    }

                    if($cl->value == 'quantity' || $cl->value == 'price' || $cl->value == 'action'){
                        $col3 .= Itwpt_Get_Cl_Product($cl,$data, $product);
                    }
*/



                    if ( ! empty($cl->desktop) || ! empty($cl->laptop) || ! empty($cl->mobile)) {
                        $itwptRows .= '<td class="' . esc_attr($cl->value) . '_tbl_column ' . esc_attr($cl->value) . '_custom_column ">' . Itwpt_Get_Cl_Product($cl,
                                $data, $product) . '</td>';
                    }
                }
//                $itwptRows .= '<td class="title_tbl_column title_custom_column in-one-line ">' . $col2 . '</td>';
//                $itwptRows .= '<td class="price_tbl_column price_custom_column ">' . $col3 . '</td>';

            }

            $itwptRows .= '</tr>';

        endwhile;
    endif;

    return $itwptRows;

}

/**
 * TODO GET MAX PRICE
 * GET MAX PRICE FROM ALL PRODUCT
 */
function Itwpt_Max_Price()
{

    global $wpdb;
    $main_price_max = ceil($wpdb->get_var(
        '
        SELECT max(meta_value + 0)
        FROM ' . $wpdb->posts . ' as post
        LEFT JOIN ' . $wpdb->postmeta . ' as meta ON post.ID = meta.post_id
        WHERE meta_key = "_price"'

    ));

    return $main_price_max;

}

/**
 * TODO GET MIN PRICE
 * GET MAX PRICE FROM ALL PRODUCT
 */
function Itwpt_Min_Price()
{

    global $wpdb;
    $main_price_min = floor($wpdb->get_var(
        '
        SELECT min(meta_value + 0)
        FROM ' . $wpdb->posts . ' as post
        LEFT JOIN ' . $wpdb->postmeta . ' as meta ON post.ID = meta.post_id
        WHERE ( meta_key = "_price" OR meta_key = "_min_variation_price" )
        AND meta_value != ""'

    ));

    return $main_price_min;

}

/**
 * TODO CREATE OPTION PRICE
 * CREATE STEP PRICE
 */
function Itwpt_Price_Step($max = false)
{

    global $itwpt_data;

    $price_max     = (! empty($itwpt_data['add_conditions_max_price']) ? $itwpt_data['add_conditions_max_price'] : Itwpt_Max_Price());
    $price_min     = (! empty($itwpt_data['add_conditions_min_price']) ? $itwpt_data['add_conditions_min_price'] : Itwpt_Min_Price());
    $range         = (! empty($itwpt_data['add_search_price_step']) ? $itwpt_data['add_search_price_step'] : '1');
    $price_current = $price_min;
    $html_out      = '';

    while ($price_current < $price_max) {

        $html_out      .= '<option value="' . esc_attr($price_current) . '">' . esc_html($price_current) . '</option>';
        $price_current += $range;

    }
    $html_out .= '<option value="' . esc_attr($price_max) . '" ' . ($max ? 'selected="selected"' : '') . '>' . esc_attr($price_max) . '</option>';

    return $html_out;

}

/**
 * TODO PAGINATION QUERY
 * CREATE HTML PAGINATION
 */
function Itwpt_Pagination($query)
{

    global $itwpt_query_data;
    $data = $itwpt_query_data;

    $pagination = paginate_links(array(
        'base'         => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
        'total'        => $query->max_num_pages,
        'current'      => $data['paged'],
        'format'       => '?paged=%#%',
        'show_all'     => false,
        'type'         => 'plain',
        'end_size'     => 2,
        'mid_size'     => 1,
        'prev_next'    => true,
        'prev_text'    => sprintf('<i></i> %1$s', $data['add_localization_pagination_prev_text']),
        'next_text'    => sprintf('%1$s <i></i>', $data['add_localization_pagination_next_text']),
        'add_args'     => true,
        'add_fragment' => '',
    ));

    return $pagination;

}

/**
 * TODO TAXONOMY PRODUCT
 * GET DATA AND ECHO IN
 */
function Itwpt_Get_Taxonomy_Product()
{

    $all_tax      = get_object_taxonomies('product');
    $options      = [];
    $options_html = [];

    if (is_array($all_tax) && count($all_tax) > 0) {

        //FETCH TAXONOMY
        foreach ($all_tax as $tax) {

            $taxonomy       = get_taxonomy($tax);
            $label          = $taxonomy->label;
            $options[$tax]  = $label;
            $options_html[] = '<option value="' . esc_attr($tax) . '">' . esc_html($label) . '</option>';

        }
    }

    return '<select class="taxonomy">' . join(' ', $options_html) . '</select>';

}

/**
 * TODO FOR SHORT DESCRIPTION
 * CHANGE NUMBER SHORT MESSAGE
 */
function Itwpt_Exc_Desc($number)
{

    global $Itwpt_Number_Desc;

    return $Itwpt_Number_Desc;

}

/**
 * TODO MESSAGE SAVE
 * RUN FUNCTION FOR SAVE DATA
 */
function Itwpt_save_custom_message_field($cart_item_data, $product_id)
{

    if (isset($cart_item_data['Itwpt_custom_message'])) {

        if (isset($_REQUEST['products'])) {

            foreach (sanitize_text_field($_REQUEST['products']) as $item) {
                $message                                = $item['Itwpt_custom_message'];
                $generated_message                      = htmlspecialchars($message);
                $cart_item_data['Itwpt_custom_message'] = $generated_message;
                /* below statement make sure every add to cart action as unique line item */
                $cart_item_data['unique_key'] = $item['id'] . '_' . $generated_message;
                if ($product_id == $item['id']) {
                    return $cart_item_data;
                }
            }
        }
    }

    return $cart_item_data;
}

add_action('woocommerce_add_cart_item_data', 'Itwpt_save_custom_message_field', 10, 2);

/**
 * TODO MESSAGE IN CART
 */
function Itwpt_render_meta_on_cart_and_checkout($cart_data, $cart_item = null)
{
    $custom_items = array();
    /* Woo 2.4.2 updates */
    if ( ! empty($cart_data)) {
        $custom_items = $cart_data;
    }
    if (isset($cart_item['Itwpt_custom_message'])) {
        $custom_items[] = array(
            "name"  => esc_html__('Message', PREFIX_ITWPT_TEXTDOMAIN),
            "value" => $cart_item['Itwpt_custom_message']
        );
    }

    return $custom_items;
}

add_filter('woocommerce_get_item_data', 'Itwpt_render_meta_on_cart_and_checkout', 10, 2);

/**
 * TODO MESSAGE IN ORDER
 */
function Itwpt_order_meta_handler($item_id, $values, $cart_item_key)
{
    if (isset($values['Itwpt_custom_message'])) {
        wc_add_order_item_meta($item_id, esc_html__('Message', PREFIX_ITWPT_TEXTDOMAIN),
            $values['Itwpt_custom_message']);
    }
}

add_action('woocommerce_add_order_item_meta', 'Itwpt_order_meta_handler', 1, 3);


add_action('wpmu_new_blog', 'Itwpt_new_blog', 10, 6);
function Itwpt_new_blog($blog_id, $user_id, $domain, $path, $site_id, $meta)
{
    global $wpdb;


    if (is_plugin_active_for_network('iThemelandCo-Woo-Product-Table/index.php')) {
        $old_blog = $wpdb->blogid;
        switch_to_blog($blog_id);
        Itwpt_Activation();
        switch_to_blog($old_blog);
    }
}
