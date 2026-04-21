<?php
/**
 * Elementor shortcode block.
 *
 * @since      2.2.5
 * @package     Smart_Post_Show
 * @subpackage  Smart_Post_Show/admin
 */

/**
 * Smart_Post_Show_Element_Shortcode_Addons_Deprecated main class.
 */
class Smart_Post_Show_Element_Shortcode_Addons_Deprecated {
	/**
	 * Instance
	 *
	 * @since 2.2.5
	 *
	 * @access private
	 * @static
	 *
	 * @var Smart_Post_Show_Element_Shortcode_Addons_Deprecated The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Script and style suffix
	 *
	 * @since 2.0.0
	 * @access protected
	 * @var string
	 */
	protected $suffix;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 2.2.5
	 *
	 * @access public
	 * @static
	 *
	 * @return Elementor_Test_Extension An instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;

	}

	/**
	 * Constructor
	 *
	 * @since 2.2.5
	 *
	 * @access public
	 */
	public function __construct() {
		$this->on_plugins_loaded();
		add_action( 'elementor/preview/enqueue_styles', array( $this, 'smart_post_show_free_addons_enqueue_style' ) );
		add_action( 'elementor/preview/enqueue_scripts', array( $this, 'smart_post_show_free_addons_enqueue_scripts' ) );
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'smart_post_show_free_addons_icon' ) );
	}

	/**
	 * Elementor block icon.
	 *
	 * @since    2.2.5
	 * @return void
	 */
	public function smart_post_show_free_addons_icon() {
		wp_enqueue_style( 'smart_post_show_free_elementor_addons_icon', SP_PC_URL . 'admin/assets/css/fontello.min.css', array(), SP_PC_VERSION, 'all' );
	}

	/**
	 * Register the JavaScript for the elementor block area.
	 *
	 * @since    2.2.5
	 */
	public function smart_post_show_free_addons_enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Smart_Post_Show_Free_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Smart_Post_Show_Free_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( 'pcp_swiper' );
		wp_enqueue_script( 'pcp_script' );
	}

	/**
	 * Enqueue styles for the elementor block area.
	 *
	 * @since    2.2.5
	 */
	public function smart_post_show_free_addons_enqueue_style() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Smart_Post_Show_Free_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Smart_Post_Show_Free_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( 'font-awesome' );
		wp_enqueue_style( 'pcp_swiper' );
		wp_enqueue_style( 'pcp_fonttello_icon' );
		wp_enqueue_style( 'pcp-style' );
	}

	/**
	 * On Plugins Loaded
	 *
	 * Checks if Elementor has loaded, and performs some compatibility checks.
	 * If All checks pass, inits the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 2.2.5
	 *
	 * @access public
	 */
	public function on_plugins_loaded() {
		add_action( 'elementor/init', array( $this, 'init' ) );
	}

	/**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Load the files required to run the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 2.2.5
	 *
	 * @access public
	 */
	public function init() {
		// Add Plugin actions.
		add_action( 'elementor/widgets/register', array( $this, 'init_widgets' ) );
	}

	/**
	 * Init Widgets
	 *
	 * Include widgets files and register them
	 *
	 * @since 2.2.5
	 *
	 * @access public
	 */
	public function init_widgets() {
		// Register widget.
		require_once SP_PC_PATH . 'admin/ElementAddons_Deprecated/Shortcode_Widget_Deprecated.php';
		\Elementor\Plugin::instance()->widgets_manager->register( new Shortcode_Widget_Deprecated() );

	}

}

Smart_Post_Show_Element_Shortcode_Addons_Deprecated::instance();
