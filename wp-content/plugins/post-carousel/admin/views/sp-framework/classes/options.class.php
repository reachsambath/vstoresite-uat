<?php
/**
 * The options class of the plugin.
 *
 * @package Smart_Post_Show
 * @subpackage Smart_Post_Show/views/sp-framework/classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'SP_PC_Options' ) ) {
	/**
	 * Options Class
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_PC_Options extends SP_PC_Abstract {

		/**
		 * Unique ID/Name
		 *
		 * @var string
		 */
		public $unique = '';
		/**
		 * Notice.
		 *
		 * @var string
		 */
		public $notice = '';
		/**
		 * Abstract.
		 *
		 * @var string
		 */
		public $abstract = 'options';
		/**
		 * Setions.
		 *
		 * @var array
		 */
		public $sections = array();
		/**
		 * Options
		 *
		 * @var array
		 */
		public $options = array();

		/**
		 * Errors.
		 *
		 * @var array
		 */
		public $errors = array();

		/**
		 * Pre tabs.
		 *
		 * @var array
		 */
		public $pre_tabs = array();

		/**
		 * Pre fields.
		 *
		 * @var array
		 */
		public $pre_fields = array();

		/**
		 * Pre sections.
		 *
		 * @var array
		 */
		public $pre_sections = array();

		/**
		 * Default arguments.
		 *
		 * @var array
		 */
		public $args = array(

			// framework title.
			'framework_title'         => 'SPF Framework <small>by SP</small>',
			'framework_class'         => '',

			// menu settings.
			'menu_title'              => '',
			'menu_slug'               => '',
			'menu_type'               => 'menu',
			// 'menu_capability'         => 'manage_options', It's implemented in add_admin_menu()
			'menu_icon'               => null,
			'menu_position'           => null,
			'menu_hidden'             => false,
			'menu_parent'             => '',

			// menu extras.
			'show_bar_menu'           => true,
			'show_sub_menu'           => true,
			'show_network_menu'       => true,
			'show_in_customizer'      => false,

			'show_search'             => true,
			'show_reset_all'          => true,
			'show_reset_section'      => true,
			'show_footer'             => true,
			'show_all_options'        => true,
			'sticky_header'           => true,
			'save_defaults'           => true,
			'ajax_save'               => true,

			// admin bar menu settings.
			'admin_bar_menu_icon'     => '',
			'admin_bar_menu_priority' => 80,

			// footer.
			'footer_text'             => '',
			'footer_after'            => '',
			'footer_credit'           => '',

			// database model.
			'database'                => '', // options, transient, theme_mod, network.
			'transient_time'          => 0,

			// contextual help.
			'contextual_help'         => array(),
			'contextual_help_sidebar' => '',

			// typography options.
			'enqueue_webfont'         => true,
			'async_webfont'           => false,

			// others.
			'output_css'              => true,

			// theme.
			'theme'                   => 'dark',

			'class'                   => '',

			// external default values.
			'defaults'                => array(),

		);

		/**
		 * Run framework construct.
		 *
		 * @param string $key The filter unique key.
		 * @param array  $params The parameters.
		 */
		public function __construct( $key, $params = array() ) {

			$this->unique   = $key;
			$this->args     = apply_filters( "spf_{$this->unique}_args", wp_parse_args( $params['args'], $this->args ), $this );
			$this->sections = apply_filters( "spf_{$this->unique}_sections", $params['sections'], $this );

			// run only is admin panel options, avoid performance loss.
			$this->pre_tabs     = $this->pre_tabs( $this->sections );
			$this->pre_fields   = $this->pre_fields( $this->sections );
			$this->pre_sections = $this->pre_sections( $this->sections );

			$this->get_options();
			$this->set_options();
			$this->save_defaults();

			add_action( 'admin_menu', array( &$this, 'add_admin_menu' ) );
			add_action( 'admin_bar_menu', array( &$this, 'add_admin_bar_menu' ), $this->args['admin_bar_menu_priority'] );
			add_action( 'wp_ajax_spf_' . $this->unique . '_ajax_save', array( &$this, 'ajax_save' ) );

			if ( ! empty( $this->args['show_network_menu'] ) ) {
				add_action( 'network_admin_menu', array( &$this, 'add_admin_menu' ) );
			}

			// wp enqeueu for typography and output css.
			parent::__construct();

		}

		/**
		 * Instance.
		 *
		 * @param object $key The instance key.
		 * @param array  $params All the parameters.
		 * @return mixed
		 */
		public static function instance( $key, $params = array() ) {
			return new self( $key, $params );
		}
		/**
		 * Pre tabs.
		 *
		 * @param array $sections All the sections.
		 * @return mixed
		 */
		public function pre_tabs( $sections ) {

			$result  = array();
			$parents = array();
			$count   = 100;

			foreach ( $sections as $key => $section ) {
				if ( ! empty( $section['parent'] ) ) {
					$section['priority']             = ( isset( $section['priority'] ) ) ? $section['priority'] : $count;
					$parents[ $section['parent'] ][] = $section;
					unset( $sections[ $key ] );
				}
				$count++;
			}

			foreach ( $sections as $key => $section ) {
				$section['priority'] = ( isset( $section['priority'] ) ) ? $section['priority'] : $count;
				if ( ! empty( $section['id'] ) && ! empty( $parents[ $section['id'] ] ) ) {
					$section['subs'] = wp_list_sort( $parents[ $section['id'] ], array( 'priority' => 'ASC' ), 'ASC', true );
				}
				$result[] = $section;
				$count++;
			}

			return wp_list_sort( $result, array( 'priority' => 'ASC' ), 'ASC', true );
		}

		/**
		 * Pre fields method.
		 *
		 * @param array $sections The sections.
		 * @return $result
		 */
		public function pre_fields( $sections ) {

			$result = array();

			foreach ( $sections as $key => $section ) {
				if ( ! empty( $section['fields'] ) ) {
					foreach ( $section['fields'] as $field ) {
						$result[] = $field;
					}
				}
			}

			return $result;
		}

		/**
		 * The pre sections.
		 *
		 * @param array $sections The sections.
		 * @return mixed
		 */
		public function pre_sections( $sections ) {

			$result = array();

			foreach ( $this->pre_tabs as $tab ) {
				if ( ! empty( $tab['subs'] ) ) {
					foreach ( $tab['subs'] as $sub ) {
						$result[] = $sub;
					}
				}
				if ( empty( $tab['subs'] ) ) {
					$result[] = $tab;
				}
			}

			return $result;
		}

		/**
		 * Add admin bar menu.
		 *
		 * @param object $wp_admin_bar The admin bar.
		 * @return void
		 */
		public function add_admin_bar_menu( $wp_admin_bar ) {

			if ( ! empty( $this->args['show_bar_menu'] ) && empty( $this->args['menu_hidden'] ) ) {

				global $submenu;

				$menu_slug = $this->args['menu_slug'];
				$menu_icon = ( ! empty( $this->args['admin_bar_menu_icon'] ) ) ? '<span class="spf-ab-icon ab-icon ' . $this->args['admin_bar_menu_icon'] . '"></span>' : '';

				$wp_admin_bar->add_node(
					array(
						'id'    => $menu_slug,
						'title' => $menu_icon . $this->args['menu_title'],
						'href'  => ( is_network_admin() ) ? network_admin_url( 'admin.php?page=' . $menu_slug ) : admin_url( 'admin.php?page=' . $menu_slug ),
					)
				);

				if ( ! empty( $submenu[ $menu_slug ] ) ) {
					foreach ( $submenu[ $menu_slug ] as $key => $menu ) {
						$wp_admin_bar->add_node(
							array(
								'parent' => $menu_slug,
								'id'     => $menu_slug . '-' . $key,
								'title'  => $menu[0],
								'href'   => ( is_network_admin() ) ? network_admin_url( 'admin.php?page=' . $menu[2] ) : admin_url( 'admin.php?page=' . $menu[2] ),
							)
						);
					}
				}

				if ( ! empty( $this->args['show_network_menu'] ) ) {
					$wp_admin_bar->add_node(
						array(
							'parent' => 'network-admin',
							'id'     => $menu_slug . '-network-admin',
							'title'  => $menu_icon . $this->args['menu_title'],
							'href'   => network_admin_url( 'admin.php?page=' . $menu_slug ),
						)
					);
				}
			}

		}

		/**
		 * Ajax save method.
		 *
		 * @return void
		 */
		public function ajax_save() {

			$result = $this->set_options( true );
			if ( ! $result ) {
				wp_send_json_error(
					array(
						'success' => false,
						'error'   => esc_html__(
							'Error while saving.',
							'post-carousel'
						),
					)
				);

			} else {
				wp_send_json_success(
					array(
						'success' => true,
						'notice'  => $this->notice,
						'errors'  => $this->errors,
					)
				);
			}
		}

		/**
		 * Get default value.
		 *
		 * @param array $field The field array.
		 * @param array $options The options array.
		 * @return mixed
		 */
		public function get_default( $field, $options = array() ) {

			$default = ( isset( $this->args['defaults'][ $field['id'] ] ) ) ? $this->args['defaults'][ $field['id'] ] : '';
			$default = ( isset( $field['default'] ) ) ? $field['default'] : $default;
			$default = ( isset( $options[ $field['id'] ] ) ) ? $options[ $field['id'] ] : $default;

			return $default;

		}

		/**
		 * Save defaults and set new fields value to main options.
		 *
		 * @return void
		 */
		public function save_defaults() {

			$tmp_options = $this->options;

			foreach ( $this->pre_fields as $field ) {
				if ( ! empty( $field['id'] ) ) {
					$this->options[ $field['id'] ] = $this->get_default( $field, $this->options );
				}
			}

			if ( $this->args['save_defaults'] && empty( $tmp_options ) ) {
				$this->save_options( $this->options );
			}

		}

		/**
		 * Set options.
		 *
		 * @param boolean $ajax The ajax save.
		 *
		 * @return mixed
		 */
		public function set_options( $ajax = false ) {

			// XSS ok.
			// No worries, This "POST" requests is sanitizing in the below foreach. see #L380 - #L384.
			$response = ( $ajax && ! empty( $_POST['data'] ) ) ? json_decode( wp_unslash( trim( $_POST['data'] ) ), true ) : $_POST; // phpcs:ignore

			// Set variables.
			$data      = array();
			$noncekey  = 'spf_options_nonce' . $this->unique;
			$nonce     = ( ! empty( $response[ $noncekey ] ) ) ? $response[ $noncekey ] : '';
			$options   = ( ! empty( $response[ $this->unique ] ) ) ? $response[ $this->unique ] : array();
			$transient = ( ! empty( $response['spf_transient'] ) ) ? $response['spf_transient'] : array();

			if ( wp_verify_nonce( $nonce, 'spf_options_nonce' ) ) {

				$importing  = false;
				$section_id = ( ! empty( $transient['section'] ) ) ? $transient['section'] : '';

				// import data.
				if ( ! $ajax && ! empty( $response['spf_import_data'] ) ) {

					// XSS ok.
					// No worries, This "POST" requests is sanitizing in the below foreach. see #L380 - #L384.
					$import_data  = json_decode( wp_unslash( trim( $response['spf_import_data'] ) ), true );
					$options      = ( is_array( $import_data ) && ! empty( $import_data ) ) ? $import_data : array();
					$importing    = true;
					$this->notice = esc_html__( 'Settings successfully imported.', 'post-carousel' );

				}

				if ( ! empty( $transient['reset'] ) ) {

					foreach ( $this->pre_fields as $field ) {
						if ( ! empty( $field['id'] ) ) {
							$data[ $field['id'] ] = $this->get_default( $field );
						}
					}

					$this->notice = esc_html__( 'Default options restored.', 'post-carousel' );

				} elseif ( ! empty( $transient['reset_section'] ) && ! empty( $section_id ) ) {

					if ( ! empty( $this->pre_sections[ $section_id - 1 ]['fields'] ) ) {

						foreach ( $this->pre_sections[ $section_id - 1 ]['fields'] as $field ) {
							if ( ! empty( $field['id'] ) ) {
								$data[ $field['id'] ] = $this->get_default( $field );
							}
						}
					}

					$data         = wp_parse_args( $data, $this->options );
					$this->notice = esc_html__( 'Default options restored for only this section.', 'post-carousel' );

				} else {

					// sanitize and validate.
					foreach ( $this->pre_fields as $field ) {

						if ( ! empty( $field['id'] ) ) {

							$field_id    = $field['id'];
							$field_value = isset( $options[ $field_id ] ) ? $options[ $field_id ] : '';

							// Ajax and Importing doing wp_unslash already.
							if ( ! $ajax && ! $importing ) {
								$field_value = wp_unslash( $field_value );
							}

							// sanitize.
							if ( ! isset( $field['sanitize'] ) ) {
								if ( is_array( $field_value ) ) {

									$data[ $field_id ] = wp_kses_post_deep( $field_value );

								} else {

									$data[ $field_id ] = wp_kses_post( $field_value );
								}
							} elseif ( isset( $field['sanitize'] ) && is_callable( $field['sanitize'] ) ) {

								$data[ $field_id ] = call_user_func( $field['sanitize'], $field_value );

							} else {
								$data[ $field_id ] = $field_value;

							}

							// Validate "post" request of field.
							if ( isset( $field['validate'] ) && is_callable( $field['validate'] ) ) {

								$has_validated = call_user_func( $field['validate'], $field_value );

								if ( ! empty( $has_validated ) ) {
									$data[ $field_id ]         = ( isset( $this->options[ $field_id ] ) ) ? $this->options[ $field_id ] : '';
									$this->errors[ $field_id ] = $has_validated;
								}
							}
						}
					}
				}

				$data = apply_filters( "spf_{$this->unique}_save", $data, $this );

				do_action( "spf_{$this->unique}_save_before", $data, $this );

				$this->options = $data;

				$this->save_options( $data );

				do_action( "spf_{$this->unique}_save_after", $data, $this );

				if ( empty( $this->notice ) ) {
					$this->notice = esc_html__( 'Settings saved.', 'post-carousel' );
				}

				return true;
			}

			return false;

		}

		/**
		 * Save options database.
		 *
		 * @param array $data The options values.
		 * @return void
		 */
		public function save_options( $data ) {

			if ( 'transient' === $this->args['database'] ) {
				set_transient( $this->unique, $data, $this->args['transient_time'] );
			} elseif ( 'theme_mod' === $this->args['database'] ) {
				set_theme_mod( $this->unique, $data );
			} elseif ( 'network' === $this->args['database'] ) {
				update_site_option( $this->unique, $data );
			} else {
				update_option( $this->unique, $data );
			}

			do_action( "spf_{$this->unique}_saved", $data, $this );

		}

		/**
		 * Get options from database.
		 *
		 * @return mixed
		 */
		public function get_options() {

			if ( 'transient' === $this->args['database'] ) {
				$this->options = get_transient( $this->unique );
			} elseif ( 'theme_mod' === $this->args['database'] ) {
				$this->options = get_theme_mod( $this->unique );
			} elseif ( 'network' === $this->args['database'] ) {
				$this->options = get_site_option( $this->unique );
			} else {
				$this->options = get_option( $this->unique );
			}

			if ( empty( $this->options ) ) {
				$this->options = array();
			}

			return $this->options;

		}

		/**
		 * WP api – admin menu.
		 */
		public function add_admin_menu() {
			// Show plugin setting menu as per user role. – ShapedPlugin
			// Use the hook 'sp_pc_dashboard_capability' to change user capability.
			$menu_capability = apply_filters( 'sp_pc_dashboard_capability', 'manage_options' );
			extract( $this->args ); // phpcs:ignore

			if ( 'submenu' === $menu_type ) {

				$menu_page = call_user_func( 'add_submenu_page', $menu_parent, $menu_title, $menu_title, $menu_capability, $menu_slug, array( &$this, 'add_options_html' ) );

			} else {

				$menu_page = call_user_func( 'add_menu_page', $menu_title, $menu_title, $menu_capability, $menu_slug, array( &$this, 'add_options_html' ), $menu_icon, $menu_position );

				if ( ! empty( $this->args['show_sub_menu'] ) && count( $this->pre_tabs ) > 1 ) {

					// create submenus.
					$tab_key = 1;
					foreach ( $this->pre_tabs as $section ) {

						call_user_func( 'add_submenu_page', $menu_slug, $section['title'], $section['title'], $menu_capability, $menu_slug . '#tab=' . $tab_key, '__return_null' );

						if ( ! empty( $section['subs'] ) ) {
							$tab_key += ( count( $section['subs'] ) - 1 );
						}

						$tab_key++;

					}

					remove_submenu_page( $menu_slug, $menu_slug );

				}

				if ( ! empty( $menu_hidden ) ) {
					remove_menu_page( $menu_slug );
				}
			}

			add_action( 'load-' . $menu_page, array( &$this, 'add_page_on_load' ) );

		}

		/**
		 * Add page on load.
		 */
		public function add_page_on_load() {

			if ( ! empty( $this->args['contextual_help'] ) ) {

				$screen = get_current_screen();

				foreach ( $this->args['contextual_help'] as $tab ) {
					$screen->add_help_tab( $tab );
				}

				if ( ! empty( $this->args['contextual_help_sidebar'] ) ) {
					$screen->set_help_sidebar( $this->args['contextual_help_sidebar'] );
				}
			}

		}

		/**
		 * Error check function.
		 */
		/**
		 * Error Check function
		 *
		 * @param mixed  $sections The section.
		 * @param string $err The error.
		 * @return statement
		 */
		public function error_check( $sections, $err = '' ) {

			if ( ! $this->args['ajax_save'] ) {

				if ( ! empty( $sections['fields'] ) ) {
					foreach ( $sections['fields'] as $field ) {
						if ( ! empty( $field['id'] ) ) {
							if ( array_key_exists( $field['id'], $this->errors ) ) {
								$err = '<span class="spf-label-error">!</span>';
							}
						}
					}
				}

				if ( ! empty( $sections['subs'] ) ) {
					foreach ( $sections['subs'] as $sub ) {
						$err = $this->error_check( $sub, $err );
					}
				}

				if ( ! empty( $sections['id'] ) && array_key_exists( $sections['id'], $this->errors ) ) {
					$err = $this->errors[ $sections['id'] ];
				}
			}

			return $err;
		}

		/**
		 * Option page html output.
		 *
		 * @return void
		 */
		public function add_options_html() {

			$has_nav       = ( count( $this->pre_tabs ) > 1 ) ? true : false;
			$show_all      = ( ! $has_nav ) ? ' spf-show-all' : '';
			$show_buttons  = isset( $this->args['show_buttons'] ) ? $this->args['show_buttons'] : true;
			$menu_slug     = isset( $this->args['menu_slug'] ) ? $this->args['menu_slug'] : '';
			$ajax_class    = ( $this->args['ajax_save'] ) ? ' spf-save-ajax' : '';
			$sticky_class  = ( $this->args['sticky_header'] ) ? ' spf-sticky-header' : '';
			$wrapper_class = ( $this->args['framework_class'] ) ? ' ' . $this->args['framework_class'] : '';
			$theme         = ( $this->args['theme'] ) ? ' spf-theme-' . $this->args['theme'] : '';
			$class         = ( $this->args['class'] ) ? ' ' . $this->args['class'] : '';

			if ( 'pcp_replace_layout' === $menu_slug ) {
				echo '<div class="wrap pcp_replace_layout_notice"><p class="spf-text-desc">To unlock the Replace Layout on The Default Blog, WooCommerce Shop, Category, Tag, Taxonomy, Search, Author, Date... Pages, and Post Types, <a target="__blank" href="https://smartpostshow.com/pricing/?ref=1"><b>Upgrade To Pro</b></a></p></div>';
			}

			echo '<div class="spf spf-options' . esc_attr( $theme ) . esc_attr( $class ) . esc_attr( $wrapper_class ) . '" data-slug="' . esc_attr( $this->args['menu_slug'] ) . '" data-unique="' . esc_attr( $this->unique ) . '">';

			$notice_class = ( ! empty( $this->notice ) ) ? ' spf-form-show' : '';
			$notice_text  = ( ! empty( $this->notice ) ) ? $this->notice : '';

			// echo '<div class="spf-form-result spf-form-success' . esc_attr( $notice_class ) . '">' . esc_html( $notice_text ) . '</div>';

			$error_class = ( ! empty( $this->errors ) ) ? ' spf-form-show' : '';

			echo '<div class="spf-form-result spf-form-error' . esc_attr( $error_class ) . '">';
			if ( ! empty( $this->errors ) ) {
				foreach ( $this->errors as $error ) {
					echo '<i class="spf-label-error">!</i> ' . esc_html( $error ) . '<br />';
				}
			}
			echo '</div>';

			echo '<div class="spf-container">';

			echo '<form method="post" action="" enctype="multipart/form-data" id="spf-form" autocomplete="off">';

			echo '<input type="hidden" class="spf-section-id" name="spf_transient[section]" value="1">';
			wp_nonce_field( 'spf_options_nonce', 'spf_options_nonce' . $this->unique );

			echo '<div class="spf-header' . esc_attr( $sticky_class ) . '">';
			echo '<div class="spf-header-inner">';

			echo '<div class="spf-header-left">';
			if ( 'pcp_replace_layout' === $menu_slug ) {
				echo '<h1><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve">
				<style type="text/css">
					.st0{fill:#D64224;}
				</style>
				<path class="st0" d="M0,69.3V0h69.4v18.6H18.6v50.7L0,69.3L0,69.3z M100,100H30.6V30.7H100V100z"/>
				</svg>' . esc_html__( 'Replace Layout', 'post-carousel' ) . '</h1>';
			} elseif ( $show_buttons ) {
				echo '<h1><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="1.4em" fill="#e1624b" height="1.4em" viewBox="0 0 288 288" enable-background="new 0 0 288 288" xml:space="preserve"><path fill="#e1624b" d="M262.102,20.977H27.615c-3.195,0-6.638,2.401-6.638,5.63v234.359c0,3.229,3.443,6.057,6.638,6.057h234.487c3.187,0,4.921-2.828,4.921-6.057V26.607C267.023,23.378,265.289,20.977,262.102,20.977z M118.37,53.441h51.26v43.571h-51.26V53.441z M55.15,53.441h51.26v43.571H55.15V53.441z M135.457,235.413H55.15v-46.134h80.307V235.413z M135.457,173.047H55.15v-46.134h80.307V173.047z M235.413,235.413h-80.307v-46.134h80.307V235.413z M235.413,173.047h-80.307v-46.134h80.307V173.047z M235.413,97.012h-51.26V53.441h51.26V97.012z"></path><line fill="none" x1="-99" y1="-84" x2="-99" y2="-57"></line><line fill="none" x1="-170" y1="61" x2="-170" y2="101"></line></svg>' . esc_html__( 'Settings', 'post-carousel' ) . '</h1>';
			} else {
				echo '<h1 class="export-import"><img src="' . esc_url( SP_PC_URL ) . 'admin/assets/img/import-export.svg">' . esc_html( $this->args['framework_title'] ) . '</h1>';
			}
			echo '</div>';

			echo '<div class="spf-header-right">';

			echo ( $has_nav && $this->args['show_all_options'] ) ? '<div class="spf-expand-all" title="' . esc_html__( 'show all options', 'post-carousel' ) . '"><i class="fa fa-outdent"></i></div>' : '';

			echo ( $this->args['show_search'] ) ? '<div class="spf-search"><input type="text" name="spf-search" placeholder="' . esc_html__( 'Search option(s)', 'post-carousel' ) . '" autocomplete="off" /></div>' : '';
			if ( $show_buttons ) {
				echo '<div class="spf-buttons">';
				echo '<input type="submit" name="' . esc_attr( $this->unique ) . '[_nonce][save]" class="button button-primary spf-save' . esc_attr( $ajax_class ) . '" value="' . esc_html__( 'Save Settings', 'post-carousel' ) . '" data-save="' . esc_html__( 'Saving...', 'post-carousel' ) . '">';
				echo ( $this->args['show_reset_section'] ) ? '<input type="submit" name="spf_transient[reset_section]" class="button button-secondary spf-reset-section spf-confirm" value="' . esc_html__( 'Reset Tab', 'post-carousel' ) . '" data-confirm="' . esc_html__( 'Are you sure you want to reset all settings to default values?', 'post-carousel' ) . '">' : '';
				echo ( $this->args['show_reset_all'] ) ? '<input type="submit" name="spf_transient[reset]" class="button spf-reset-all spf-confirm" value="' . esc_html__( 'Reset All', 'post-carousel' ) . '" data-confirm="' . esc_html__( 'Are you sure to reset all options?', 'post-carousel' ) . '">' : '';
				echo '</div>';
			}
			echo '</div>';

			echo '<div class="clear"></div>';
			echo '</div>';
			echo '</div>';

			echo '<div class="spf-wrapper' . esc_attr( $show_all ) . '">';

			if ( $has_nav ) {
				echo '<div class="spf-nav spf-nav-options">';

				echo '<ul>';

				$tab_key = 1;

				foreach ( $this->pre_tabs as $tab ) {

					$tab_error = $this->error_check( $tab );
					$tab_icon  = ( ! empty( $tab['icon'] ) ) ? '<i class="' . $tab['icon'] . '"></i>' : '';

					if ( ! empty( $tab['subs'] ) ) {

						echo '<li class="spf-tab-depth-0">';

						echo '<a href="#tab=' . esc_attr( $tab_key ) . '" class="spf-arrow">' . wp_kses_post( $tab_icon ) . esc_html( $tab['title'] ) . esc_html( $tab_error ) . '</a>';

						echo '<ul>';

						foreach ( $tab['subs'] as $sub ) {

							$sub_error = $this->error_check( $sub );
							$sub_icon  = ( ! empty( $sub['icon'] ) ) ? '<i class="' . $sub['icon'] . '"></i>' : '';

							echo '<li class="spf-tab-depth-1"><a id="spf-tab-link-' . esc_attr( $tab_key ) . '" href="#tab=' . esc_attr( $tab_key ) . '">' . wp_kses_post( $sub_icon ) . esc_html( $sub['title'] ) . esc_html( $sub_error ) . '</a></li>';

							$tab_key++;
						}

						echo '</ul>';

						echo '</li>';

					} else {

						echo '<li class="spf-tab-depth-0"><a id="spf-tab-link-' . esc_attr( $tab_key ) . '" href="#tab=' . esc_attr( $tab_key ) . '">' . wp_kses_post( $tab_icon ) . esc_html( $tab['title'] ) . esc_html( $tab_error ) . '</a></li>';

						$tab_key++;
					}
				}

				echo '</ul>';

				echo '</div>';

			}

			echo '<div class="spf-content">';

			echo '<div class="spf-sections">';

			$section_key = 1;

			foreach ( $this->pre_sections as $section ) {

				$onload       = ( ! $has_nav ) ? ' spf-onload' : '';
				$section_icon = ( ! empty( $section['icon'] ) ) ? '<i class="spf-icon ' . $section['icon'] . '"></i>' : '';

				echo '<div id="spf-section-' . esc_attr( $section_key ) . '" class="spf-section' . esc_attr( $onload ) . '">';
				echo ( $has_nav ) ? '<div class="spf-section-title"><h3>' . wp_kses_post( $section_icon ) . esc_html( $section['title'] ) . '</h3></div>' : '';
				echo ( ! empty( $section['description'] ) ) ? '<div class="spf-field spf-section-description">' . esc_html( $section['description'] ) . '</div>' : '';

				if ( ! empty( $section['fields'] ) ) {

					foreach ( $section['fields'] as $field ) {

						$is_field_error = $this->error_check( $field );

						if ( ! empty( $is_field_error ) ) {
							$field['_error'] = $is_field_error;
						}

						$value = ( ! empty( $field['id'] ) && isset( $this->options[ $field['id'] ] ) ) ? $this->options[ $field['id'] ] : '';

						SP_PC::field( $field, $value, $this->unique, 'options' );

					}
				} else {

					echo '<div class="spf-no-option spf-text-muted">' . esc_html__( 'No option provided by developer.', 'post-carousel' ) . '</div>';

				}

				echo '</div>';

				$section_key++;
			}

			echo '</div>';

			echo '<div class="clear"></div>';

			echo '</div>';

			echo '<div class="spf-nav-background"></div>';

			echo '</div>';

			if ( ! empty( $this->args['show_footer'] ) ) {

				echo '<div class="spf-footer">';

				echo '<div class="spf-buttons">';
				echo '<input type="submit" name="spf_transient[save]" class="button button-primary spf-save' . esc_attr( $ajax_class ) . '" value="' . esc_html__( 'Save', 'post-carousel' ) . '" data-save="' . esc_html__( 'Saving...', 'post-carousel' ) . '">';
				echo ( $this->args['show_reset_section'] ) ? '<input type="submit" name="spf_transient[reset_section]" class="button button-secondary spf-reset-section spf-confirm" value="' . esc_html__( 'Reset Section', 'post-carousel' ) . '" data-confirm="' . esc_html__( 'Are you sure to reset this section options?', 'post-carousel' ) . '">' : '';
				echo ( $this->args['show_reset_all'] ) ? '<input type="submit" name="spf_transient[reset]" class="button spf-reset-all spf-confirm" value="' . esc_html__( 'Reset All', 'post-carousel' ) . '" data-confirm="' . esc_html__( 'Are you sure to reset all options?', 'post-carousel' ) . '">' : '';
				echo '</div>';

				echo ( ! empty( $this->args['footer_text'] ) ) ? '<div class="spf-copyright">' . esc_html( $this->args['footer_text'] ) . '</div>' : '';

				echo '<div class="clear"></div>';
				echo '</div>';

			}

			echo '</form>';

			echo '</div>';

			echo '<div class="clear"></div>';

			echo ( ! empty( $this->args['footer_after'] ) ) ? wp_kses_post( $this->args['footer_after'] ) : '';

			echo '</div>';

		}
	}
}
