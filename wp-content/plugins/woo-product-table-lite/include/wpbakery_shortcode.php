<?php
/**
 * shortcode "Itwpt_Wpbakery" and registers it to
 * the Visual Composer plugin
 *
 */

if (!class_exists('Itwpt_Wpbakery_Shortcode')) {

    class Itwpt_Wpbakery_Shortcode
    {

        /**
         * Main constructor
         *
         * @since 1.0.0
         */
        public function __construct()
        {

            // Registers the shortcode in WordPress
            add_shortcode('itwpt_wpbakery_shortcode', array('Itwpt_Wpbakery_Shortcode', 'output'));

            // Map shortcode to Visual Composer
            if (function_exists('vc_lean_map')) {
                vc_lean_map('itwpt_wpbakery_shortcode', array('Itwpt_Wpbakery_Shortcode', 'map'));
            }

        }

        /**
         * Shortcode output
         *
         * @since 1.0.0
         */
        public static function output($atts, $content = null)
        {

            // Extract shortcode attributes (based on the vc_lean_map function - see next function)
            extract(vc_map_get_attributes('itwpt_wpbakery_shortcode', $atts));

            // Define output
            $output = '';

            // Output
            $output .= do_shortcode('[it_woo_product_table id="' . $atts['table_id'] . '"]');

            // Return output
            return $output;

        }

        /**
         * Map shortcode to VC
         *
         * This is an array of all your settings which become the shortcode attributes ($atts)
         * for the output. See the link below for a description of all available parameters.
         *
         * @since 1.0.0
         * @link  https://wpbakery.atlassian.net/wiki/pages/viewpage.action?pageId=38993922
         */
        public static function map()
        {

            // MAP
            $map = array(
                'name'        => esc_html__('iThemelandCo Woo Product Table', PREFIX_ITWPT_TEXTDOMAIN),
                'description' => esc_html__('Set shortcode', PREFIX_ITWPT_TEXTDOMAIN),
                'base'        => 'itwpt_wpbakery_shortcode',
                'params'      => array(
                    array(
                        'type'       => 'dropdown',
                        'heading'    => esc_html__('Tables', PREFIX_ITWPT_TEXTDOMAIN),
                        'param_name' => 'table_id',
                        'value'      => array(
                            esc_html__('Select Table...', PREFIX_ITWPT_TEXTDOMAIN) => '',
                        ),
                    ),
                ),
            );

            $itwpt_shortcodes = Itwpt_Get_Data_Table('itpt_posts');
            if (!empty($itwpt_shortcodes)) {

                foreach ($itwpt_shortcodes as $index => $item) {

                    $map['params'][0]['value'][$item->title] = $item->id;
                }

            } else {
                $map['params'][0]['value'][esc_html__('Can Not Found ShortCode', PREFIX_ITWPT_TEXTDOMAIN)] = null;
            }

            return $map;

        }

    }

}
new Itwpt_Wpbakery_Shortcode;
