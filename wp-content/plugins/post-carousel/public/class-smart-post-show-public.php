<?php
/**
 * File for the public facing functions.
 *
 * @link        https://smartpostshow.com/
 * @since      2.2.0
 *
 * @package    Smart_Post_Show
 * @subpackage Smart_Post_Show/public
 */

/**
 * The public-facing functionality of the plugin.
 */
class Smart_Post_Show_Public {

	/**
	 * Script and style suffix
	 *
	 * @since 2.2.0
	 * @access protected
	 * @var string
	 */
	protected $suffix;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.2.0
	 */
	public function __construct() {
		$this->load_public_dependencies();
		$this->pcp_public_action();
	}

	/**
	 * Public dependencies.
	 *
	 * @return void
	 */
	private function load_public_dependencies() {
		require_once SP_PC_PATH . 'public/helpers/class-post-functions.php';
		require_once SP_PC_PATH . 'public/helpers/class-pcp-queryinside.php';
		require_once SP_PC_PATH . 'public/template/loop/class-loop-html.php';
	}

	/**
	 * Public Action
	 *
	 * @return void
	 */
	private function pcp_public_action() {
		add_shortcode( 'smart_post_show', array( $this, 'pcp_shortcode_render' ) );
		add_shortcode( 'sp_postcarousel', array( $this, 'pcp_shortcode_render' ) );
		add_shortcode( 'post-carousel', array( $this, 'pcp_shortcode_render' ) );
		add_action( 'save_post', array( $this, 'delete_page_sp_pcp_option_on_save' ) );

		$this->suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) || ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? '' : '.min';
	}

	/**
	 * Minify output
	 *
	 * @param  statement $html output.
	 * @return statement
	 */
	public static function minify_output( $html ) {
		$html = preg_replace( '/<!--(?!s*(?:[if [^]]+]|!|>))(?:(?!-->).)*-->/s', '', $html );
		$html = str_replace( array( "\r\n", "\r", "\n", "\t" ), '', $html );
		while ( stristr( $html, '  ' ) ) {
			$html = str_replace( '  ', ' ', $html );
		}
		return $html;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since 2.2.0
	 */
	public function enqueue_styles() {
		// Get the existing shortcode ids from the current page.
		$get_page_data      = self::get_page_data();
		$found_shortcode_id = $get_page_data['generator_id'];
		// Check shortcode ids are exist in the current page and then enqueue all styles.
		if ( $found_shortcode_id ) {
			wp_enqueue_style( 'font-awesome' );
			wp_enqueue_style( 'pcp_swiper' );
			wp_enqueue_style( 'pcp_fonttello_icon' );
			wp_enqueue_style( 'pcp-style' );
			$dynamic_style = self::load_dynamic_style( $found_shortcode_id );
			wp_add_inline_style( 'pcp-style', $dynamic_style['dynamic_css'] );
		}
	}

	/**
	 * Function get layout from attrs and create class depending on it.
	 *
	 * @since 2.0
	 * @param array $attribute attribute of this shortcode.
	 */
	public function pcp_shortcode_render( $attribute ) {
		$shortcode_id = esc_attr( intval( $attribute['id'] ) );
		// Check the shortcode existence and status.
		if ( empty( $shortcode_id ) || 'sp_post_carousel' !== get_post_type( $shortcode_id ) || 'trash' === get_post_status( $shortcode_id ) ) {
			return;
		}
		// Preset Layouts.
		$layout = get_post_meta( $shortcode_id, 'sp_pcp_layouts', true );
		// All the visible options for the Shortcode like – Global, Filter, Display, Popup, Typography etc.
		$view_options  = get_post_meta( $shortcode_id, 'sp_pcp_view_options', true );
		$section_title = get_the_title( $shortcode_id );

		// Get the existing shortcode id from the current page.
		$get_page_data      = self::get_page_data();
		$found_shortcode_id = $get_page_data['generator_id'];
		ob_start();
		// Check if shortcode and page ids are not exist in the current page then enqueue the stylesheet.
		if ( ! is_array( $found_shortcode_id ) || ! $found_shortcode_id || ! in_array( $shortcode_id, $found_shortcode_id ) ) {
			wp_enqueue_style( 'font-awesome' );
			wp_enqueue_style( 'pcp_swiper' );
			wp_enqueue_style( 'pcp-style' );
			$dynamic_style = self::load_dynamic_style( $shortcode_id, $view_options, $layout );
			// Add dynamic style.
			echo '<style id="sp_pcp_dynamic_style' . esc_attr( $shortcode_id ) . '">' . $dynamic_style['dynamic_css'] . '</style>'; // phpcs:ignore
		}
		// Update options if the existing shortcode id option not found.
		self::sps_db_options_update( $shortcode_id, $get_page_data );
		SP_PC_Output::pc_html_show( $view_options, $layout, $shortcode_id, $section_title );
		return ob_get_clean();
	}

	/**
	 * Register all Styles and Scripts for the public-facing side of the site.
	 *
	 * @since   2.4.17
	 */
	public function register_all_scripts() {
		$pcp_settings = get_option( 'sp_post_carousel_settings' );
		/**
		 *  Register all the styles for the public-facing side of the site.
		 */
		if ( $pcp_settings['pcp_fontawesome_css'] ) {
			wp_register_style( 'font-awesome', SP_PC_URL . 'public/assets/css/font-awesome.min.css', array(), SP_PC_VERSION, 'all' );
		}
		if ( $pcp_settings['pcp_swiper_css'] ) {
			wp_register_style( 'pcp_swiper', SP_PC_URL . 'public/assets/css/swiper-bundle' . $this->suffix . '.css', array(), SP_PC_VERSION, 'all' );
		}
		wp_register_style( 'pcp_fonttello_icon', SP_PC_URL . 'admin/assets/css/fontello' . $this->suffix . '.css', array(), SP_PC_VERSION, 'all' );

		wp_register_style( 'pcp-style', SP_PC_URL . 'public/assets/css/style' . $this->suffix . '.css', array(), SP_PC_VERSION, 'all' );

		/**
		 * Register all the Scripts for the public-facing side of the site.
		 */
		wp_register_script( 'pcp_swiper', SP_PC_URL . 'public/assets/js/swiper-bundle' . $this->suffix . '.js', array( 'jquery' ), SP_PC_VERSION, true );
		wp_register_script( 'pcp_script', SP_PC_URL . 'public/assets/js/scripts' . $this->suffix . '.js', array( 'jquery' ), SP_PC_VERSION, true );

		wp_localize_script(
			'pcp_script',
			'pcp_vars',
			array(
				'swiperEnqueue' => $pcp_settings['pcp_swiper_js'],
			)
		);
	}

	/**
	 * Gets the existing shortcode-id, page-id and option-key from the current page.
	 *
	 * @return array
	 */
	public static function get_page_data() {
		$current_page_id    = get_queried_object_id();
		$option_key         = 'sp_pcp_page_id' . $current_page_id;
		$found_generator_id = get_option( $option_key );
		if ( is_multisite() ) {
			$option_key         = 'sp_pcp_page_id' . get_current_blog_id() . $current_page_id;
			$found_generator_id = get_site_option( $option_key );
		}
		$get_page_data = array(
			'page_id'      => $current_page_id,
			'generator_id' => $found_generator_id,
			'option_key'   => $option_key,
		);
		return $get_page_data;
	}

	/**
	 * If the option does not exist, it will be created.
	 *
	 * It will be serialized before it is inserted into the database.
	 *
	 * @param  string $post_id existing shortcode id.
	 * @param  array  $get_page_data get current page-id, shortcode-id and option-key from the the current page.
	 * @return void
	 */
	public static function sps_db_options_update( $post_id, $get_page_data ) {
		$found_generator_id = $get_page_data['generator_id'];
		$option_key         = $get_page_data['option_key'];
		$current_page_id    = $get_page_data['page_id'];
		if ( $found_generator_id ) {
			$found_generator_id = is_array( $found_generator_id ) ? $found_generator_id : array( $found_generator_id );
			if ( ! in_array( $post_id, $found_generator_id ) || empty( $found_generator_id ) ) {
				// If not found the shortcode id in the page options.
				array_push( $found_generator_id, $post_id );
				if ( is_multisite() ) {
					update_site_option( $option_key, $found_generator_id );
				} else {
					update_option( $option_key, $found_generator_id );
				}
			}
		} else {
			// If option not set in current page add option.
			if ( $current_page_id ) {
				if ( is_multisite() ) {
					add_site_option( $option_key, array( $post_id ) );
				} else {
					add_option( $option_key, array( $post_id ) );
				}
			}
		}
	}

	/**
	 * Load dynamic style of the existing shortcode id.
	 *
	 * @param  mixed $found_generator_id to push id option for getting how many shortcode in the page.
	 * @param  mixed $view_options shortcode options.
	 * @param  mixed $layout all layout based options.
	 * @return array dynamic style use in the existing shortcodes in the current page.
	 */
	public static function load_dynamic_style( $found_generator_id, $view_options = array(), $layout = '' ) {
		$pcp_settings = get_option( 'sp_post_carousel_settings' );
		$custom_css   = '';
		// If multiple shortcode found in the current page.
		if ( is_array( $found_generator_id ) ) {
			foreach ( $found_generator_id  as $pcp_id ) {
				if ( $pcp_id && is_numeric( $pcp_id ) && get_post_status( $pcp_id ) !== 'trash' ) {
					$view_options = get_post_meta( $pcp_id, 'sp_pcp_view_options', true );
					$layout       = get_post_meta( $pcp_id, 'sp_pcp_layouts', true );
					if ( is_array( $view_options ) ) {
						include SP_PC_PATH . 'public/dynamic-css/dynamic-css.php';
					}
				}
			}
		} else {
			// If single shortcode found in the current page.
			$pcp_id = $found_generator_id;
			if ( is_array( $view_options ) ) {
				include SP_PC_PATH . 'public/dynamic-css/dynamic-css.php';
			}
		}
		// Custom css merge with dynamic style.
		$pcp_custom_css = isset( $pcp_settings['pcp_custom_css'] ) ? trim( html_entity_decode( $pcp_settings['pcp_custom_css'] ) ) : '';
		if ( ! empty( $pcp_custom_css ) ) {
			$custom_css .= $pcp_custom_css;
		}
		$dynamic_style = array(
			'dynamic_css' => self::minify_output( $custom_css ),
		);
		return $dynamic_style;
	}

	/**
	 * Delete page shortcode ids array option on save
	 *
	 * @param  int $post_ID current post id.
	 * @return void
	 *
	 * @since   2.4.17
	 */
	public function delete_page_sp_pcp_option_on_save( $post_ID ) {
		if ( is_multisite() ) {
			$option_key = 'sp_pcp_page_id' . get_current_blog_id() . $post_ID;
			if ( get_site_option( $option_key ) ) {
				delete_site_option( $option_key );
			}
		} elseif ( get_option( 'sp_pcp_page_id' . $post_ID ) ) {
				delete_option( 'sp_pcp_page_id' . $post_ID );
		}
	}
}
