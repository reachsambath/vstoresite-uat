<?php
if ( ! class_exists('Elementor_Itwpt_Extension')) {
    class Elementor_Itwpt_Extension
    {

        private static $_instance = null;

        public static function instance()
        {

            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }

            return self::$_instance;

        }

        public function __construct()
        {

            add_action('init', [$this, 'i18n']);

            // Check if Elementor installed and activated
            if ( ! did_action('elementor/loaded')) {
                add_action('admin_notices', [$this, 'admin_notice_missing_main_plugin']);

                return;
            }
            add_action('elementor/widgets/widgets_registered', [$this, 'init_widgets']);
            add_action('elementor/editor/before_enqueue_scripts', [$this, 'init_before_enqueue_scripts']);

        }

        public function register_widget_scripts()
        {

            wp_enqueue_script('jquery');
            wp_enqueue_script(PREFIX_ITWPT . '_front_script', PREFIX_ITWPT_JS_URL . 'front/script.js', array(),
                '1.0.0'); // FRONT SCRIPT
            wp_enqueue_script(PREFIX_ITWPT . '_multi_select', PREFIX_ITWPT_JS_URL . 'select2.min.js', array(),
                '1.0.0'); // SELECT
            wp_enqueue_script(PREFIX_ITWPT . '_sort_table', PREFIX_ITWPT_JS_URL . 'front/sort_table.js', array(),
                '1.0.0'); // SORT TABLE
            wp_enqueue_script(PREFIX_ITWPT . '_custom_scroll', PREFIX_ITWPT_JS_URL . 'front/scroll_bar_script.js',
                array(), '1.0.0'); // SCROLL BAR
            wp_enqueue_script(PREFIX_ITWPT . '_light_box', PREFIX_ITWPT_JS_URL . 'front/lightbox.min.js', array(),
                '1.0.0'); // LIGHT BOX
            wp_enqueue_script(PREFIX_ITWPT . '_tooltip', PREFIX_ITWPT_JS_URL . 'tooltip.js', array(),
                '1.0.0'); // TOOLTIP

            // WP LOCALIZE
            $localize_value =
                array(
                    'nonce'      => wp_create_nonce('itwpt'),
                    'ajaxUrl'    => admin_url('admin-ajax.php'),
                    'loadingUrl' => esc_url(PREFIX_ITWPT_IMAGE_URL . 'loading_bs.gif'),
                    'cartUrl'    => wc_get_cart_url(),
                    'checkout'   => wc_get_checkout_url(),
                );
            wp_localize_script(PREFIX_ITWPT . '_front_script', PREFIX_ITWPT . '_localize', $localize_value);

        }

        public function init_before_enqueue_scripts()
        {

            wp_enqueue_script(PREFIX_ITWPT . '_light_box', PREFIX_ITWPT_JS_URL . 'front/lightbox.min.js', array(),
                '1.0.0'); // LIGHT BOX
        }

        public function i18n()
        {

            load_plugin_textdomain(PREFIX_ITWPT_TEXTDOMAIN);

        }

        public function init()
        {


        }

        public function admin_notice_missing_main_plugin()
        {

            if (isset($_GET['activate'])) {
                $key = sanitize_text_field($_GET['activate']);
                unset($key);
            }

            $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
                esc_html__('"%1$s" requires "%2$s" to be installed and activated.', PREFIX_ITWPT_TEXTDOMAIN),
                '<strong>' . esc_html__('Elementor iThemelandCo Woo Product Table Extension',
                    PREFIX_ITWPT_TEXTDOMAIN) . '</strong>',
                '<strong>' . esc_html__('Elementor', PREFIX_ITWPT_TEXTDOMAIN) . '</strong>'
            );

            printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);

        }

        public function init_widgets()
        {

            // Include Widget files
            require_once(PREFIX_ITPT_PATH . 'include/elementor_widget.php');
            $class = 'ItwptElementor\Widgets\Elementor_itwpt_Widget';
            if (class_exists($class)) {
                // Register widget
                \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new $class);
            }

        }

    }

    Elementor_Itwpt_Extension::instance();
}