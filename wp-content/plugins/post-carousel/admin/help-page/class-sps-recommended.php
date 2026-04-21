<?php
/**
 * The help page for the Smart Post Show Free
 *
 * @package Smart Post Show Free
 * @subpackage post-carousel/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access.

/**
 * The help class for the Smart Post Show Free
 */
class SPS_Recommended {

	/**
	 * Single instance of the class
	 *
	 * @var null
	 */
	protected static $_instance = null;

	/**
	 * Plugins Path variable.
	 *
	 * @var array
	 */
	protected static $plugins = array(
		'woo-product-slider'             => 'main.php',
		'gallery-slider-for-woocommerce' => 'woo-gallery-slider.php',
		'post-carousel'                  => 'main.php',
		'easy-accordion-free'            => 'plugin-main.php',
		'logo-carousel-free'             => 'main.php',
		'location-weather'               => 'main.php',
		'woo-quickview'                  => 'woo-quick-view.php',
		'wp-expand-tabs-free'            => 'plugin-main.php',

	);

	/**
	 * Welcome pages
	 *
	 * @var array
	 */
	public $pages = array(
		'pcp_help',
	);


	/**
	 * Not show this plugin list.
	 *
	 * @var array
	 */
	protected static $not_show_plugin_list = array( 'aitasi-coming-soon', 'latest-posts', 'widget-post-slider', 'post-carousel', 'easy-lightbox-wp' );

	/**
	 * Easy_Accordion_Free_Help construct function.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'help_admin_menu' ), 80 );

        $page   = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';// @codingStandardsIgnoreLine
		if ( 'pcp_help' !== $page ) {
			return;
		}
		add_action( 'admin_print_scripts', array( $this, 'disable_admin_notices' ) );
		add_action( 'spf_enqueue', array( $this, 'help_page_enqueue_scripts' ) );
	}

	/**
	 * Main help page Instance
	 *
	 * @static
	 * @return self Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Help_page_enqueue_scripts function.
	 *
	 * @return void
	 */
	public function help_page_enqueue_scripts() {
		wp_enqueue_style( 'sp-post-carousel-help', SP_PC_URL . 'admin/help-page/css/help-page.min.css', array(), SP_PC_VERSION );
		wp_enqueue_style( 'sp-post-carousel-help-fontello', SP_PC_URL . 'admin/help-page/css/fontello.min.css', array(), SP_PC_VERSION );

		wp_enqueue_script( 'sp-post-carousel-help', SP_PC_URL . 'admin/help-page/js/help-page.min.js', array(), SP_PC_VERSION, true );
	}

	/**
	 * Add admin menu.
	 *
	 * @return void
	 */
	public function help_admin_menu() {
		add_submenu_page(
			'edit.php?post_type=sp_post_carousel',
			__( 'Post Carousel', 'post-carousel' ),
			__( 'Recommended', 'post-carousel' ),
			'manage_options',
			'edit.php?post_type=sp_post_carousel&page=pcp_help#recommended'
		);
		add_submenu_page(
			'edit.php?post_type=sp_post_carousel',
			__( 'Post Carousel', 'post-carousel' ),
			__( 'Lite vs Pro', 'post-carousel' ),
			'manage_options',
			'edit.php?post_type=sp_post_carousel&page=pcp_help#lite-to-pro'
		);
		add_submenu_page(
			'edit.php?post_type=sp_post_carousel',
			__( 'Smart Post Show Help', 'post-carousel' ),
			__( 'Get Help', 'post-carousel' ),
			'manage_options',
			'pcp_help',
			array(
				$this,
				'help_page_callback',
			)
		);
	}

	/**
	 * Spspc_ajax_help_page function.
	 *
	 * @return void
	 */
	public function spspc_plugins_info_api_help_page() {
		$plugins_arr = get_transient( 'spspc_plugins' );
		if ( false === $plugins_arr ) {
			$args    = (object) array(
				'author'   => 'shapedplugin',
				'per_page' => '120',
				'page'     => '1',
				'fields'   => array(
					'slug',
					'name',
					'version',
					'downloaded',
					'active_installs',
					'last_updated',
					'rating',
					'num_ratings',
					'short_description',
					'author',
				),
			);
			$request = array(
				'action'  => 'query_plugins',
				'timeout' => 30,
				'request' => serialize( $args ),
			);
			// https://codex.wordpress.org/WordPress.org_API.
			$url      = 'http://api.wordpress.org/plugins/info/1.0/';
			$response = wp_remote_post( $url, array( 'body' => $request ) );

			if ( ! is_wp_error( $response ) ) {

				$plugins_arr = array();
				$plugins     = unserialize( $response['body'] );

				if ( isset( $plugins->plugins ) && ( count( $plugins->plugins ) > 0 ) ) {
					foreach ( $plugins->plugins as $pl ) {
						if ( ! in_array( $pl->slug, self::$not_show_plugin_list, true ) ) {
							$plugins_arr[] = array(
								'slug'              => $pl->slug,
								'name'              => $pl->name,
								'version'           => $pl->version,
								'downloaded'        => $pl->downloaded,
								'active_installs'   => $pl->active_installs,
								'last_updated'      => strtotime( $pl->last_updated ),
								'rating'            => $pl->rating,
								'num_ratings'       => $pl->num_ratings,
								'short_description' => $pl->short_description,
							);
						}
					}
				}

				set_transient( 'spspc_plugins', $plugins_arr, 24 * HOUR_IN_SECONDS );
			}
		}

		if ( is_array( $plugins_arr ) && ( count( $plugins_arr ) > 0 ) ) {
			array_multisort( array_column( $plugins_arr, 'active_installs' ), SORT_DESC, $plugins_arr );

			foreach ( $plugins_arr as $plugin ) {
				$plugin_slug = $plugin['slug'];
				$image_type  = 'png';
				if ( isset( self::$plugins[ $plugin_slug ] ) ) {
					$plugin_file = self::$plugins[ $plugin_slug ];
				} else {
					$plugin_file = $plugin_slug . '.php';
				}

				switch ( $plugin_slug ) {
					case 'styble':
						$image_type = 'jpg';
						break;
					case 'location-weather':
					case 'testimonial-free':
					case 'easy-accordion-free':
					case 'gallery-slider-for-woocommerce':
						$image_type = 'gif';
						break;
				}

				$details_link = network_admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin=' . $plugin['slug'] . '&amp;TB_iframe=true&amp;width=745&amp;height=550' );
				?>
				<div class="plugin-card <?php echo esc_attr( $plugin_slug ); ?>" id="<?php echo esc_attr( $plugin_slug ); ?>">
					<div class="plugin-card-top">
						<div class="name column-name">
							<h3>
								<a class="thickbox" title="<?php echo esc_attr( $plugin['name'] ); ?>" href="<?php echo esc_url( $details_link ); ?>">
						<?php echo esc_html( $plugin['name'] ); ?>
									<img src="<?php echo esc_url( 'https://ps.w.org/' . $plugin_slug . '/assets/icon-256x256.' . $image_type ); ?>" class="plugin-icon"/>
								</a>
							</h3>
						</div>
						<div class="action-links">
							<ul class="plugin-action-buttons">
								<li>
						<?php
						if ( $this->is_plugin_installed( $plugin_slug, $plugin_file ) ) {
							if ( $this->is_plugin_active( $plugin_slug, $plugin_file ) ) {
								?>
										<button type="button" class="button button-disabled" disabled="disabled">Active</button>
									<?php
							} else {
								?>
											<a href="<?php echo esc_url( $this->activate_plugin_link( $plugin_slug, $plugin_file ) ); ?>" class="button button-primary activate-now">
									<?php esc_html_e( 'Activate', 'post-carousel' ); ?>
											</a>
									<?php
							}
						} else {
							?>
										<a href="<?php echo esc_url( $this->install_plugin_link( $plugin_slug ) ); ?>" class="button install-now">
								<?php esc_html_e( 'Install Now', 'post-carousel' ); ?>
										</a>
								<?php } ?>
								</li>
								<li>
									<a href="<?php echo esc_url( $details_link ); ?>" class="thickbox open-plugin-details-modal" aria-label="<?php echo esc_attr( 'More information about ' . $plugin['name'] ); ?>" title="<?php echo esc_attr( $plugin['name'] ); ?>">
								<?php esc_html_e( 'More Details', 'post-carousel' ); ?>
									</a>
								</li>
							</ul>
						</div>
						<div class="desc column-description">
							<p><?php echo esc_html( isset( $plugin['short_description'] ) ? $plugin['short_description'] : '' ); ?></p>
							<p class="authors"> <cite>By <a href="https://shapedplugin.com/">ShapedPlugin LLC</a></cite></p>
						</div>
					</div>
					<?php
					echo '<div class="plugin-card-bottom">';

					if ( isset( $plugin['rating'], $plugin['num_ratings'] ) ) {
						?>
						<div class="vers column-rating">
							<?php
							wp_star_rating(
								array(
									'rating' => $plugin['rating'],
									'type'   => 'percent',
									'number' => $plugin['num_ratings'],
								)
							);
							?>
							<span class="num-ratings">(<?php echo esc_html( number_format_i18n( $plugin['num_ratings'] ) ); ?>)</span>
						</div>
						<?php
					}
					if ( isset( $plugin['version'] ) ) {
						?>
						<div class="column-updated">
							<strong><?php esc_html_e( 'Version:', 'post-carousel' ); ?></strong>
							<span><?php echo esc_html( $plugin['version'] ); ?></span>
						</div>
							<?php
					}

					if ( isset( $plugin['active_installs'] ) ) {
						?>
						<div class="column-downloaded">
						<?php echo esc_html( number_format_i18n( $plugin['active_installs'] ) ) . esc_html__( '+ Active Installations', 'post-carousel' ); ?>
						</div>
									<?php
					}

					if ( isset( $plugin['last_updated'] ) ) {
						?>
						<div class="column-compatibility">
							<strong><?php esc_html_e( 'Last Updated:', 'post-carousel' ); ?></strong>
							<span><?php echo esc_html( human_time_diff( $plugin['last_updated'] ) ) . ' ' . esc_html__( 'ago', 'post-carousel' ); ?></span>
						</div>
									<?php
					}

					echo '</div>';
					?>
				</div>
				<?php
			}
		}
	}

	/**
	 * Check plugins installed function.
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @param string $plugin_file Plugin file.
	 * @return boolean
	 */
	public function is_plugin_installed( $plugin_slug, $plugin_file ) {
		return file_exists( WP_PLUGIN_DIR . '/' . $plugin_slug . '/' . $plugin_file );
	}

	/**
	 * Check active plugin function
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @param string $plugin_file Plugin file.
	 * @return boolean
	 */
	public function is_plugin_active( $plugin_slug, $plugin_file ) {
		return is_plugin_active( $plugin_slug . '/' . $plugin_file );
	}

	/**
	 * Install plugin link.
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @return string
	 */
	public function install_plugin_link( $plugin_slug ) {
		return wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=' . $plugin_slug ), 'install-plugin_' . $plugin_slug );
	}

	/**
	 * Active Plugin Link function
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @param string $plugin_file Plugin file.
	 * @return string
	 */
	public function activate_plugin_link( $plugin_slug, $plugin_file ) {
		return wp_nonce_url( admin_url( 'edit.php?post_type=sp_post_carousel&page=pcp_help&action=activate&plugin=' . $plugin_slug . '/' . $plugin_file . '#recommended' ), 'activate-plugin_' . $plugin_slug . '/' . $plugin_file );
	}

	/**
	 * Making page as clean as possible
	 */
	public function disable_admin_notices() {

		global $wp_filter;

		if ( isset( $_GET['post_type'] ) && isset( $_GET['page'] ) && 'sp_post_carousel' === wp_unslash( $_GET['post_type'] ) && in_array( wp_unslash( $_GET['page'] ), $this->pages ) ) { // @codingStandardsIgnoreLine

			if ( isset( $wp_filter['user_admin_notices'] ) ) {
				unset( $wp_filter['user_admin_notices'] );
			}
			if ( isset( $wp_filter['admin_notices'] ) ) {
				unset( $wp_filter['admin_notices'] );
			}
			if ( isset( $wp_filter['all_admin_notices'] ) ) {
				unset( $wp_filter['all_admin_notices'] );
			}
		}
	}

	/**
	 * The Smart Post Show Help Callback.
	 *
	 * @return void
	 */
	public function help_page_callback() {
		add_thickbox();

		$action   = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : '';
		$plugin   = isset( $_GET['plugin'] ) ? sanitize_text_field( wp_unslash( $_GET['plugin'] ) ) : '';
		$_wpnonce = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';

		if ( isset( $action, $plugin ) && ( 'activate' === $action ) && wp_verify_nonce( $_wpnonce, 'activate-plugin_' . $plugin ) ) {
			activate_plugin( $plugin, '', false, true );
		}

		if ( isset( $action, $plugin ) && ( 'deactivate' === $action ) && wp_verify_nonce( $_wpnonce, 'deactivate-plugin_' . $plugin ) ) {
			deactivate_plugins( $plugin, '', false, true );
		}

		?>
		<div class="sp-smart-post__help">
			<!-- Header section start -->
			<section class="spspc__help header">
				<div class="spspc-header-area-top">
					<p>You’re currently using <b>Smart Post Show Lite</b>. To access additional features, consider <a target="_blank" href="https://smartpostshow.com/pricing/?ref=1" ><b>upgrading to Pro!</b></a> 🚀</p>
				</div>
				<div class="spspc-header-area">
					<div class="spspc-container">
						<div class="spspc-header-logo">
							<img src="<?php echo esc_url( SP_PC_URL . 'admin/help-page/img/logo.svg' ); ?>" alt="">
							<span><?php echo esc_html( SP_PC_VERSION ); ?></span>
						</div>
					</div>
					<div class="spspc-header-logo-shape">
						<img src="<?php echo esc_url( SP_PC_URL . 'admin/help-page/img/logo-shape.svg' ); ?>" alt="">
					</div>
				</div>
				<div class="spspc-header-nav">
					<div class="spspc-container">
						<div class="spspc-header-nav-menu">
							<ul>
								<li><a class="active" data-id="get-start-tab"  href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=sp_post_carousel&page=pcp_help#get-start' ); ?>"><i class="spspc-icon-play"></i> Get Started</a></li>
								<li><a href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=sp_post_carousel&page=pcp_help#recommended' ); ?>" data-id="recommended-tab"><i class="spspc-icon-recommended"></i> Recommended</a></li>
								<li><a href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=sp_post_carousel&page=pcp_help#lite-to-pro' ); ?>" data-id="lite-to-pro-tab"><i class="spspc-icon-lite-to-pro-icon"></i> Lite Vs Pro</a></li>
								<li><a href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=sp_post_carousel&page=pcp_help#about-us' ); ?>" data-id="about-us-tab"><i class="spspc-icon-info-circled-alt"></i> About Us</a></li>
							</ul>
						</div>
					</div>
				</div>
			</section>
			<!-- Header section end -->

			<!-- Start Page -->
			<section class="spspc__help start-page" id="get-start-tab">
				<div class="spspc-container">
					<div class="spspc-start-page-wrap">
						<div class="spspc-video-area">
							<h2 class='spspc-section-title'>Welcome to Smart Post Show!</h2>
							<span class='spspc-normal-paragraph'>Thank you for installing Smart Post Show! This video will help you get started with the plugin. Enjoy!</span>
							<iframe width="724" height="405" src="https://www.youtube.com/embed/Zd3cSnlEA_Y?si=jZY-sPMZnol03ems" title="YouTube video player" frameborder="0" allowfullscreen></iframe>
							<ul>
								<li><a class='spspc-medium-btn' href="<?php echo esc_url( home_url( '/' ) . 'wp-admin/post-new.php?post_type=sp_post_carousel' ); ?>">Create a Show</a></li>
								<li><a target="_blank" class='spspc-medium-btn' href="https://smartpostshow.com/demo/lite/">Live Demo</a></li>
								<li><a target="_blank" class='spspc-medium-btn arrow-btn' href="https://smartpostshow.com/">Explore Smart Post Show <i class="spspc-icon-button-arrow-icon"></i></a></li>
							</ul>
						</div>
						<div class="spspc-start-page-sidebar">
							<div class="spspc-start-page-sidebar-info-box">
								<div class="spspc-info-box-title">
									<h4><i class="spspc-icon-doc-icon"></i> Documentation</h4>
								</div>
								<span class='spspc-normal-paragraph'>Explore Smart Post Show plugin capabilities in our enriched documentation.</span>
								<a target="_blank" class='spspc-small-btn' href="https://docs.shapedplugin.com/docs/post-carousel/overview/">Browse Now</a>
							</div>
							<div class="spspc-start-page-sidebar-info-box">
								<div class="spspc-info-box-title">
									<h4><i class="spspc-icon-support"></i> Technical Support</h4>
								</div>
								<span class='spspc-normal-paragraph'>For personalized assistance, reach out to our skilled support team for prompt help.</span>
								<a target="_blank" class='spspc-small-btn' href="https://shapedplugin.com/create-new-ticket/">Ask Now</a>
							</div>
							<div class="spspc-start-page-sidebar-info-box">
								<div class="spspc-info-box-title">
									<h4><i class="spspc-icon-team-icon"></i> Join The Community</h4>
								</div>
								<span class='spspc-normal-paragraph'>Join the official ShapedPlugin Facebook group to share your experiences, thoughts, and ideas.</span>
								<a target="_blank" class='spspc-small-btn' href="https://www.facebook.com/groups/ShapedPlugin/">Join Now</a>
							</div>
						</div>
					</div>
				</div>
			</section>

			<!-- Lite To Pro Page -->
			<section class="spspc__help lite-to-pro-page" id="lite-to-pro-tab">
				<div class="spspc-container">
					<div class="spspc-call-to-action-top">
						<h2 class="spspc-section-title">Lite vs Pro Comparison</h2>
						<a target="_blank" href="https://smartpostshow.com/pricing/?ref=1" class='spspc-big-btn'>Upgrade to Pro Now!</a>
					</div>
					<div class="spspc-lite-to-pro-wrap">
						<div class="spspc-features">
							<ul>
								<li class='spspc-header'>
									<span class='spspc-title'>FEATURES</span>
									<span class='spspc-free'>Lite</span>
									<span class='spspc-pro'><i class='spspc-icon-pro'></i> PRO</span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>All Free Version Features</span>
									<span class='spspc-free spspc-check-icon'></span>
									<span class='spspc-pro spspc-check-icon'></span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>Amazing Layouts (Carousel, Grid, List, Isotope, Timeline, ZigZag, Accordion, etc.)</span>
									<span class='spspc-free'>4</span>
									<span class='spspc-pro'>13+</span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>Create a Show/View with Posts, Pages, Products, Media, Custom Post Types, and Multiple Post Types (All/Post, Page, Product, etc.) <i class="spspc-hot">Hot</i></span>
									<span class='spspc-free spspc-close-icon'></span>
									<span class='spspc-pro spspc-check-icon'></span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>WooCommerce Advanced Products Filtering Types (On Sale, Best Selling, Top Rated, etc) <i class="spspc-new">New</i></span>
									<span class='spspc-free'>1</span>
									<span class='spspc-pro'>6+</span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>Display Multiple Post Types in one Show/shortcode (e.g., posts, pages, products, portfolios in a layout). <i class="spspc-hot">Hot</i></span>
									<span class='spspc-free spspc-close-icon'></span>
									<span class='spspc-pro spspc-check-icon'></span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>Choose a Position for Sticky Post</span>
									<span class='spspc-free spspc-close-icon'></span>
									<span class='spspc-pro spspc-check-icon'></span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>Advanced WordPress Content Filtering Options (Taxonomy, Author, Sort By, Custom Fields, Status, Date, and Keyword)</span>
									<span class='spspc-free'>5</span>
									<span class='spspc-pro'>7</span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>Turn any Content Filtering into Ajax Live Filters and Multiple Filters Simultaneously</span>
									<span class='spspc-free spspc-close-icon'></span>
									<span class='spspc-pro spspc-check-icon'></span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>Turn any Layout into an Isotope(shuffle) Filter</span>
									<span class='spspc-free spspc-close-icon'></span>
									<span class='spspc-pro spspc-check-icon'></span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>Supports ACF, WooCommerce, EDD, CPT UI, The Events Calendar, Events Manager, Pods, Toolset, Membership, etc. <i class="spspc-hot">Hot</i></span>
									<span class='spspc-free spspc-close-icon'></span>
									<span class='spspc-pro spspc-check-icon'></span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>Multiple Ajax Pagination Types (Ajax Number, Load More, Infinite Scrolling, etc.)</span>
									<span class='spspc-free spspc-close-icon'></span>
									<span class='spspc-pro spspc-check-icon'></span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>Isotope Shuffle Filters Posts by Taxonomy Terms</span>
									<span class='spspc-free spspc-close-icon'></span>
									<span class='spspc-pro spspc-check-icon'></span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>Live Filters Options (Filter type: Dropdown, Radio, Button, Label, Hide empty terms, Show post count, Alignment, etc.)</span>
									<span class='spspc-free spspc-close-icon'></span>
									<span class='spspc-pro spspc-check-icon'></span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>Ajax Search Field on the Isotope or any Layout</span>
									<span class='spspc-free spspc-close-icon'></span>
									<span class='spspc-pro spspc-check-icon'></span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>Content Orientations (default classic, left image, right image, overlay, card, overlay box, etc.), and eight positions. <i class="spspc-hot">Hot</i></span>
									<span class='spspc-free'><b>1</b></span>
									<span class='spspc-pro'><b>6</b></span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>30+ Animation types for the overlay content on hover</span>
									<span class='spspc-free spspc-close-icon'></span>
									<span class='spspc-pro spspc-check-icon'></span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>12 Social sharing media, Alignment, margin. Icon shape, custom color, and many more.</span>
									<span class='spspc-free spspc-close-icon'></span>
									<span class='spspc-pro spspc-check-icon'></span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>Drag and Drop Sorting content fields (thumbnail, title, content, meta fields, social share, custom fields).</span>
									<span class='spspc-free spspc-close-icon'></span>
									<span class='spspc-pro spspc-check-icon'></span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>Margin for Every Content Field (thumbnail, title, meta fields, content, social share, custom fields)</span>
									<span class='spspc-free spspc-close-icon'></span>
									<span class='spspc-pro spspc-check-icon'></span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>Replace Post Thumbnail with Video and Audio</span>
									<span class='spspc-free spspc-close-icon'></span>
									<span class='spspc-pro spspc-check-icon'></span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>Thumbnails Custom Dimensions and Retina Ready Supported</span>
									<span class='spspc-free spspc-close-icon'></span>
									<span class='spspc-pro spspc-check-icon'></span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>Display Post Meta Fields Author, Date, Taxonomy, Comment Count, View Count, Like, Reading Time <i class="spspc-hot">Hot</i></span>
									<span class='spspc-free spspc-close-icon'></span>
									<span class='spspc-pro spspc-check-icon'></span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>Customize Meta Fields Position, Icon, Margin, Separator, etc.</span>
									<span class='spspc-free spspc-close-icon'></span>
									<span class='spspc-pro spspc-check-icon'></span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>Center and Ticker Carousel Modes</span>
									<span class='spspc-free spspc-close-icon'></span>
									<span class='spspc-pro spspc-check-icon'></span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>20+ Powerful Carousel Settings</span>
									<span class='spspc-free spspc-check-icon'></span>
									<span class='spspc-pro spspc-check-icon'></span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>Multi-row Carousel and Transition Effects</span>
									<span class='spspc-free spspc-close-icon'></span>
									<span class='spspc-pro spspc-check-icon'></span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>Detail Page Link Type (Popup, Single Page, None)</span>
									<span class='spspc-free spspc-close-icon'></span>
									<span class='spspc-pro spspc-check-icon'></span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>Customize Isotope Filter Type, Button Color, Margin between Buttons, Taxonomies, Alignment, etc.</span>
									<span class='spspc-free spspc-close-icon'></span>
									<span class='spspc-pro spspc-check-icon'></span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>Individual Configuration Tabs for Isotope, Timeline, and Accordion Layout</span>
									<span class='spspc-free spspc-close-icon'></span>
									<span class='spspc-pro spspc-check-icon'></span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>Customize Details Page Popup Type, Maximum Width, Maximum Height, Color, Background, Close button, etc. </span>
									<span class='spspc-free spspc-close-icon'></span>
									<span class='spspc-pro spspc-check-icon'></span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>Content Field Drag and Drop Sorting for the Detail Page</span>
									<span class='spspc-free spspc-close-icon'></span>
									<span class='spspc-pro spspc-check-icon'></span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>Replace the Standard Archive (Post, Search, Author, Date) Layout with the Smart Post Show Layouts <i class="spspc-hot">Hot</i></span>
									<span class='spspc-free spspc-close-icon'></span>
									<span class='spspc-pro spspc-check-icon'></span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>Replace Taxonomy Archives (Post Category, Tag, Product Category, Tag, Shipping) Layouts <i class="spspc-new">New</i> <i class="spspc-hot">Hot</i></span>
									<span class='spspc-free spspc-close-icon'></span>
									<span class='spspc-pro spspc-check-icon'></span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>Replace Shop/Product Page Archive with Smart Post Show Layouts <i class="spspc-new">New</i></span>
									<span class='spspc-free spspc-close-icon'></span>
									<span class='spspc-pro spspc-check-icon'></span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>Export and Import Post Shows</span>
									<span class='spspc-free spspc-check-icon'></span>
									<span class='spspc-pro spspc-check-icon'></span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>Stylize your Product Slider/Grid Typography with 1500+ Google Fonts.</span>
									<span class='spspc-free spspc-close-icon'></span>
									<span class='spspc-pro spspc-check-icon'></span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>All Premium Features, Security Enhancements, and Compatibility</span>
									<span class='spspc-free spspc-close-icon'></span>
									<span class='spspc-pro spspc-check-icon'></span>
								</li>
								<li class='spspc-body'>
									<span class='spspc-title'>Priority Top-notch Support</span>
									<span class='spspc-free spspc-close-icon'></span>
									<span class='spspc-pro spspc-check-icon'></span>
								</li>
							</ul>
						</div>
						<div class="spspc-upgrade-to-pro">
							<h2 class='spspc-section-title'>Upgrade To PRO & Enjoy Advanced Features!</h2>
							<span class='spspc-section-subtitle'>Already, <b>21000+</b> people are using Smart Post Show on their websites to create beautiful showcase, why won’t you!</span>
							<div class="spspc-upgrade-to-pro-btn">
								<div class="spspc-action-btn">
									<a target="_blank" href="https://smartpostshow.com/pricing/?ref=1" class='spspc-big-btn'>Upgrade to Pro Now!</a>
									<span class='spspc-small-paragraph'>14-Day No-Questions-Asked <a target="_blank" href="https://shapedplugin.com/refund-policy/">Refund Policy</a></span>
								</div>
								<a target="_blank" href="https://smartpostshow.com/" class='spspc-big-btn-border'>See All Features</a>
								<a target="_blank" href="https://smartpostshow.com/demo/" class='spspc-big-btn-border spspc-live-pro-demo'>Pro Live Demo</a>
							</div>
						</div>
					</div>
					<div class="spspc-testimonial">
						<div class="spspc-testimonial-title-section">
							<span class='spspc-testimonial-subtitle'>NO NEED TO TAKE OUR WORD FOR IT</span>
							<h2 class="spspc-section-title">Our Users Love Smart Post Show Pro!</h2>
						</div>
						<div class="spspc-testimonial-wrap">
							<div class="spspc-testimonial-area">
								<div class="spspc-testimonial-content">
									<p>Very easy to set up and nice options even in the free version which I’m using on a NFP horse club site. Very professional look. I’m new to WordPress and still getting the hang of it so finding a plugin...</p>
								</div>
								<div class="spspc-testimonial-info">
									<div class="spspc-img">
										<img src="<?php echo esc_url( SP_PC_URL . 'admin/help-page/img/comlodge.png' ); ?>" alt="">
									</div>
									<div class="spspc-info">
										<h3>Comlodge</h3>
										<div class="spspc-star">
											<i>★★★★★</i>
										</div>
									</div>
								</div>
							</div>
							<div class="spspc-testimonial-area">
								<div class="spspc-testimonial-content">
									<p>The support is excellent !!,Five stars for the plugin and five stars for the support service. We have had some doubts and they have been resolved all of them at the moment.We are very happy with the...</p>
								</div>
								<div class="spspc-testimonial-info">
									<div class="spspc-img">
										<img src="<?php echo esc_url( SP_PC_URL . 'admin/help-page/img/testimonial-avatar.svg' ); ?>" alt="">
									</div>
									<div class="spspc-info">
										<h3>Annamaspons</h3>
										<div class="spspc-star">
											<i>★★★★★</i>
										</div>
									</div>
								</div>
							</div>
							<div class="spspc-testimonial-area">
								<div class="spspc-testimonial-content">
									<p>This is the third site on which I’ve used the free version of what seems to be a pretty unique product. It’s easy to get a good carousel going quickly from either pages or posts. A couple more navigatio...</p>
								</div>
								<div class="spspc-testimonial-info">
									<div class="spspc-img">
										<img src="<?php echo esc_url( SP_PC_URL . 'admin/help-page/img/testimonial-avatar.svg' ); ?>" alt="">
									</div>
									<div class="spspc-info">
										<h3>Glenn Watson</h3>
										<div class="spspc-star">
											<i>★★★★★</i>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>

			<!-- Recommended Page -->
			<section id="recommended-tab" class="spspc-recommended-page">
				<div class="spspc-container">
					<h2 class="spspc-section-title">Enhance your Website with our Free Robust Plugins</h2>
					<div class="spspc-wp-list-table plugin-install-php">
						<div class="spspc-recommended-plugins" id="the-list">
							<?php
								$this->spspc_plugins_info_api_help_page();
							?>
						</div>
					</div>
				</div>
			</section>

			<!-- About Page -->
			<section id="about-us-tab" class="spspc__help about-page">
				<div class="spspc-container">
					<div class="spspc-about-box">
						<div class="spspc-about-info">
							<h3>The Most Powerful Post Showcase plugin for WordPress by the Smart Posts Show Team, ShapedPlugin, LLC</h3>
							<p>At <b>ShapedPlugin LLC</b>, we have been searching for the best way to filter and display any posts (any post type), pages, taxonomy, custom taxonomy, and custom fields in beautiful layouts without coding! Unfortunately, we couldn't find any suitable plugin that met our needs. Therefore, we decided to develop a powerful WordPress post showcase plugin that is both user-friendly and efficient. </p>
							<p>We are introducing the Smart Post Show plugin - a simple and convenient way to showcase posts on your WordPress website. Check it out now!</p>
							<div class="spspc-about-btn">
								<a target="_blank" href="https://smartpostshow.com/" class='spspc-medium-btn'>Explore Smart Post Show</a>
								<a target="_blank" href="https://shapedplugin.com/about-us/" class='spspc-medium-btn spspc-arrow-btn'>More About Us <i class="spspc-icon-button-arrow-icon"></i></a>
							</div>
						</div>
						<div class="spspc-about-img">
							<img src="https://shapedplugin.com/wp-content/uploads/2024/01/shapedplugin-team.jpg" alt="">
							<span>Team ShapedPlugin LLC at WordCamp Sylhet</span>
						</div>
					</div>
					<div class="spspc-our-plugin-list">
						<h3 class="spspc-section-title">Upgrade your Website with our High-quality Plugins!</h3>
						<div class="spspc-our-plugin-list-wrap">
							<a target="_blank" class="spspc-our-plugin-list-box" href="https://wpcarousel.io/">
								<i class="spspc-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/wp-carousel-free/assets/icon-256x256.png" alt="">
								<h4>WP Carousel</h4>
								<p>The most powerful and user-friendly multi-purpose carousel, slider, & gallery plugin for WordPress.</p>
							</a>
							<a target="_blank" class="spspc-our-plugin-list-box" href="https://realtestimonials.io/">
								<i class="spspc-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/testimonial-free/assets/icon-256x256.gif" alt="">
								<h4>Real Testimonials</h4>
								<p>Simply collect, manage, and display Testimonials on your website and boost conversions.</p>
							</a>
							<a target="_blank" class="spspc-our-plugin-list-box" href="https://smartpostshow.com/">
								<i class="spspc-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/post-carousel/assets/icon-256x256.png" alt="">
								<h4>Smart Post Show</h4>
								<p>Filter and display posts (any post types), pages, taxonomy, custom taxonomy, and custom field, in beautiful layouts.</p>
							</a>
							<a target="_blank" href="https://wooproductslider.io/" class="spspc-our-plugin-list-box">
								<i class="spspc-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/woo-product-slider/assets/icon-256x256.png" alt="">
								<h4>Product Slider for WooCommerce</h4>
								<p>Boost sales by interactive product Slider, Grid, and Table in your WooCommerce website or store.</p>
							</a>
							<a target="_blank" class="spspc-our-plugin-list-box" href="https://woogallery.io/">
								<i class="spspc-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/gallery-slider-for-woocommerce/assets/icon-256x256.gif" alt="">
								<h4>WooGallery</h4>
								<p>Product gallery slider and additional variation images gallery for WooCommerce and boost your sales.</p>
							</a>
							<a target="_blank" class="spspc-our-plugin-list-box" href="https://getwpteam.com/">
								<i class="spspc-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/team-free/assets/icon-256x256.png" alt="">
								<h4>WP Team</h4>
								<p>Display your team members smartly who are at the heart of your company or organization!</p>
							</a>
							<a target="_blank" class="spspc-our-plugin-list-box" href="https://logocarousel.com/">
								<i class="spspc-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/logo-carousel-free/assets/icon-256x256.png" alt="">
								<h4>Logo Carousel</h4>
								<p>Showcase a group of logo images with Title, Description, Tooltips, Links, and Popup as a grid or in a carousel.</p>
							</a>
							<a target="_blank" class="spspc-our-plugin-list-box" href="https://easyaccordion.io/">
								<i class="spspc-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/easy-accordion-free/assets/icon-256x256.gif" alt="">
								<h4>Easy Accordion</h4>
								<p>Minimize customer support by offering comprehensive FAQs and increasing conversions.</p>
							</a>
							<a target="_blank" class="spspc-our-plugin-list-box" href="https://shapedplugin.com/woocategory/">
								<i class="spspc-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/woo-category-slider-grid/assets/icon-256x256.png" alt="">
								<h4>WooCategory</h4>
								<p>Display by filtering the list of categories aesthetically and boosting sales.</p>
							</a>
							<a target="_blank" class="spspc-our-plugin-list-box" href="https://wptabs.com/">
								<i class="spspc-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/wp-expand-tabs-free/assets/icon-256x256.png" alt="">
								<h4>WP Tabs</h4>
								<p>Display tabbed content smartly & quickly on your WordPress site without coding skills.</p>
							</a>
							<a target="_blank" class="spspc-our-plugin-list-box" href="https://shapedplugin.com/plugin/woocommerce-quick-view-pro/">
								<i class="spspc-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/woo-quickview/assets/icon-256x256.png" alt="">
								<h4>Quick View for WooCommerce</h4>
								<p>Quickly view product information with smooth animation via AJAX in a nice Modal without opening the product page.</p>
							</a>
							<a target="_blank" class="spspc-our-plugin-list-box" href="https://shapedplugin.com/plugin/smart-brands-for-woocommerce/">
								<i class="spspc-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/smart-brands-for-woocommerce/assets/icon-256x256.png" alt="">
								<h4>Smart Brands for WooCommerce</h4>
								<p>Smart Brands for WooCommerce Pro helps you display product brands in an attractive way on your online store.</p>
							</a>
						</div>
					</div>
				</div>
			</section>

			<!-- Footer Section -->
			<section class="spspc-footer">
				<div class="spspc-footer-top">
					<p><span>Made With <i class="spspc-icon-heart"></i> </span> By the <a target="_blank" href="https://shapedplugin.com/">ShapedPlugin LLC</a> Team</p>
					<p>Get connected with</p>
					<ul>
						<li><a target="_blank" href="https://www.facebook.com/ShapedPlugin/"><i class="spspc-icon-fb"></i></a></li>
						<li><a target="_blank" href="https://twitter.com/intent/follow?screen_name=ShapedPlugin"><i class="spspc-icon-x"></i></a></li>
						<li><a target="_blank" href="https://profiles.wordpress.org/shapedplugin/#content-plugins"><i class="spspc-icon-wp-icon"></i></a></li>
						<li><a target="_blank" href="https://youtube.com/@ShapedPlugin?sub_confirmation=1"><i class="spspc-icon-youtube-play"></i></a></li>
					</ul>
				</div>
			</section>
		</div>
		<?php
	}

}
