<?php
/**
 * Welcome Page Class
 *
 * @package     WordPress Schema
 * @subpackage  Admin/Welcome
 * @copyright   Copyright (c) 2016, Hesham Zebida
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * WordPress Schema_WP_Welcome Class
 *
 * A general class for About and Credits page.
 *
 * @since 1.0
 */
class WordPress Schema_WP_Welcome {

	/**
	 * @var string The capability users should have to view the page
	 */
	public $minimum_capability = 'manage_options';

	/**
	 * Get things started
	 *
	 * @since 1.0
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menus' ) );
		add_action( 'admin_head', array( $this, 'admin_head'  ) );
		add_action( 'admin_init', array( $this, 'welcome'     ), 9999 );
	}

	/**
	 * Register the Dashboard Pages which are later hidden but these pages
	 * are used to render the Welcome and Credits pages.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function admin_menus() {

		// What's New
		add_dashboard_page(
			__( 'What\'s new in WordPress Schema', 'WordPress Schema-wp' ),
			__( 'What\'s new in WordPress Schema', 'WordPress Schema-wp' ),
			$this->minimum_capability,
			'WordPress Schema-wp-what-is-new',
			array( $this, 'whats_new_screen' )
		);

		// Getting Started Page
		add_dashboard_page(
			__( 'Getting started with WordPress Schema', 'WordPress Schema-wp' ),
			__( 'Getting started with WordPress Schema', 'WordPress Schema-wp' ),
			$this->minimum_capability,
			'WordPress Schema-wp-getting-started',
			array( $this, 'getting_started_screen' )
		);

		// Credits Page
		add_dashboard_page(
			__( 'The people that build WordPress Schema', 'WordPress Schema-wp' ),
			__( 'The people that build WordPress Schema', 'WordPress Schema-wp' ),
			$this->minimum_capability,
			'WordPress Schema-wp-credits',
			array( $this, 'credits_screen' )
		);
	}

	/**
	 * Hide Individual Dashboard Pages
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function admin_head() {
		remove_submenu_page( 'index.php', 'WordPress Schema-wp-what-is-new' );
		remove_submenu_page( 'index.php', 'WordPress Schema-wp-getting-started' );
		remove_submenu_page( 'index.php', 'WordPress Schema-wp-credits' );

		$page = isset( $_GET['page'] ) ? $_GET['page'] : false;

		if ( 'WordPress Schema-wp-what-is-new' != $page  && 'WordPress Schema-wp-getting-started' != $page && 'WordPress Schema-wp-credits' != $page ) {
			return;
		}

		// Badge for welcome page
		$badge_url = WordPress SchemaWP_PLUGIN_URL . 'assets/images/WordPress Schema-badge.png';
		?>
		<style type="text/css" media="screen">
		/*<![CDATA[*/
		.WordPress Schema-wp-badge {
			height: 128px;
			width: 128px;
			position: relative;
			color: #777777;
			font-weight: bold;
			font-size: 14px;
			text-align: center;
			background: url('<?php echo esc_url( $badge_url ); ?>') no-repeat;
		}
		.WordPress Schema-wp-badge span {
			position: absolute;
			bottom: -30px;
			left: 0;
			width: 100%;
		}
		.about-wrap h2 {
			font-size: 1.6em;
			font-weight: bold;
			text-align: left;
			margin: 2em 0 1em 0;
		}
		.about-wrap .WordPress Schema-wp-badge {
			position: absolute;
			top: 0;
			right: 0;
		}
		.WordPress Schema-wp-info-notice {
			border-left: 4px solid #5b9dd9;
			display: block;
		}
		.WordPress Schema-wp-info-notice h3 {
			font-size: 1.6em !important;
		}
		.WordPress Schema-wp-info-notice i {
			color: #5b9dd9;
		}
		@media (max-width: 800px) {
    		.WordPress Schema-wp-welcome-screenshots {
				float: none;
				margin-left: 0px !important;
			}
		}
		/*]]>*/
		</style>
		<?php
	}

	/**
	 * Navigation tabs
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function tabs() {
		$selected = isset( $_GET['page'] ) ? $_GET['page'] : 'WordPress Schema-wp-getting-started';
		?>
		<h2 class="nav-tab-wrapper">
			
			<a class="nav-tab <?php echo $selected == 'WordPress Schema-wp-what-is-new' ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'WordPress Schema-wp-what-is-new' ), 'index.php' ) ) ); ?>">
				<?php _e( "What's New", 'WordPress Schema-wp' ); ?>
            
            <a class="nav-tab <?php echo $selected == 'WordPress Schema-wp-getting-started' ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'WordPress Schema-wp-getting-started' ), 'index.php' ) ) ); ?>">
				<?php _e( 'Getting Started', 'WordPress Schema-wp' ); ?>
			</a>
            
			<a class="nav-tab <?php echo $selected == 'WordPress Schema-wp-credits' ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'WordPress Schema-wp-credits' ), 'index.php' ) ) ); ?>">
				<?php _e( 'Credits', 'WordPress Schema-wp' ); ?>
			</a>
		</h2>
		<?php
	}

	/**
	 * Render About Screen
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function whats_new_screen() {
		list( $display_version ) = explode( '-', WordPress SchemaWP_VERSION );
		?>
		<div class="wrap about-wrap">
			<h1><?php printf( __( 'Welcome to WordPress Schema v%s', 'WordPress Schema-wp' ), esc_html( $display_version ) ); ?></h1>
			<div class="about-text"><?php echo __( 'Thank you for installing WordPress Schema. The best WordPress Schema.org plugin for WordPress.', 'WordPress Schema-wp'); ?></div>
			
            <div class="WordPress Schema-wp-badge">
            	<span><?php printf( __( 'Version %s', 'WordPress Schema-wp' ), esc_html( $display_version ) ); ?></span>
            </div>
			   
			<?php $this->tabs(); ?>
			
			<div class="changelog">
				
                <div class="update-nag WordPress Schema-wp-info-notice">
                 <h3><?php _e( 'First-time WordPress Schema configuration!', 'WordPress Schema-wp' );?></h3>
					<p><?php _e( 'Get started quickly with the WordPress Schema configuration wizard!', 'WordPress Schema-wp' );?></p>
                    <p>
                    	<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=WordPress Schema' ) ); ?>"><?php _e( 'Plugin Settings', 'WordPress Schema-wp' ); ?></a>
                        <a class="button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=WordPress Schema-setup' ) ); ?>"><?php _e( 'Quick Configuration Wizard', 'WordPress Schema-wp' ); ?></a>
                    </p> 
					
                </div>
                 
				<div class="feature-section sub-section">
                    
                    <h2><?php _e( 'Support More WordPress Schema Types', 'WordPress Schema-wp' );?></h2>
					<p><?php _e( 'Now, WordPress Schema plugin supports more WordPress Schema.org types.', 'WordPress Schema-wp' );?></p>
                    
                    - <a href="https://WordPress Schema.org/Article" target="_blank"></a><?php _e( 'Article', 'WordPress Schema-wp' );?>
                    <ul>
                        	<li><?php _e( 'General', 'WordPress Schema-wp' );?></li>
                        	<li><?php _e( 'BlogPosting', 'WordPress Schema-wp' );?></li>
                        	<li><?php _e( 'NewsArticle', 'WordPress Schema-wp' );?></li>
                            <li><?php _e( 'Report', 'WordPress Schema-wp' );?></li>
                        	<li><?php _e( 'ScholarlyArticle', 'WordPress Schema-wp' );?></li>
                        	<li><?php _e( 'TechArticle', 'WordPress Schema-wp' );?></li>
             		</ul>
                    
                    <br>
                    
                    - <?php _e( 'Blog', 'WordPress Schema-wp' );?> (<?php _e( 'for Blog posts list page', 'WordPress Schema-wp' );?>)
                    
                    <br>
                    
                    - <?php _e( 'WPHeader', 'WordPress Schema-wp' );?> (<?php _e( 'for Web Page Header', 'WordPress Schema-wp' );?>)
                    
                    <br>
                    
                    - <?php _e( 'WPFooter', 'WordPress Schema-wp' );?> (<?php _e( 'for Web Page Footer', 'WordPress Schema-wp' );?>)
                    
                     <br>
                    
                    - <?php _e( 'BreadcrumbList', 'WordPress Schema-wp' );?> (<?php _e( 'for Breadcrumbs', 'WordPress Schema-wp' );?>)
                    
                     <br>
                    
                    - <?php _e( 'CollectionPage', 'WordPress Schema-wp' );?> (<?php _e( 'for Categories', 'WordPress Schema-wp' );?>)
                    
                     <br>
                    
                    - <?php _e( 'CollectionPage', 'WordPress Schema-wp' );?> (<?php _e( 'for Tags', 'WordPress Schema-wp' );?>)
                    
                    <br>
                    
                    - <?php _e( 'AboutPage', 'WordPress Schema-wp' );?> (<?php _e( 'for the about page', 'WordPress Schema-wp' );?>)
                    
                    <br>
                    
                    - <?php _e( 'ContactPage', 'WordPress Schema-wp' );?> (<?php _e( 'for the contact page', 'WordPress Schema-wp' );?>)
                    
                    <br>
                    
                    - <?php _e( 'Person', 'WordPress Schema-wp' );?> (<?php _e( 'author archive', 'WordPress Schema-wp' );?>)
                    
                    <br><br>
                    
                    - <?php _e( 'New WordPress Schema Type?', 'WordPress Schema-wp' );?>
                    <ul>
                        	<li><?php _e( 'Maybe coming soon!', 'WordPress Schema-wp' );?></li>
                    </ul>
            		
                    <div class="return-to-dashboard">
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=WordPress Schema' ) ); ?>"><i class="dashicons dashicons-admin-generic"></i><?php _e( 'Go To WordPress Schema Settings', 'WordPress Schema-wp' ); ?></a>
					</div>
            		
                   
                    <h2><?php _e( 'Integration with Themes and other Plugins', 'WordPress Schema-wp' );?></h2>
                    <p><?php _e( 'WordPress Schema plays nicely  and support themes mentioned below.', 'WordPress Schema-wp' );?></p>
                    
                    <h3><?php _e( 'Play nicely with Yoast SEO', 'WordPress Schema-wp' );?></h3>
					<p><?php _e( 'Now WordPress Schema plugin plays nicely with Yoast SEO plugin, you can have both plugins active with no conflicts.', 'WordPress Schema-wp' );?></p>
                    
                    <h3><?php _e( 'Hello AMP!', 'WordPress Schema-wp' );?></h3>
					<p><?php _e( 'If you are using the AMP plugin, WordPress Schema got you covered!', 'WordPress Schema-wp' );?></p>
                    
                    <h3><?php _e( 'WPRichSnippets plugin', 'WordPress Schema-wp' );?></h3>
					<p><?php _e( 'If you are using the WPRichSnippets plugin, WordPress Schema will behave!', 'WordPress Schema-wp' );?></p>
                    
                    <h3><?php _e( 'Correct Genesis WordPress Schema Markup', 'WordPress Schema-wp' );?></h3>
					<p><?php _e( 'Using Genesis Framework? The WordPress Schema plugin will automatically indicate that and correct Genesis WordPress Schema output.', 'WordPress Schema-wp' );?></p>
                    <h3><?php _e( 'Uses Thesis Theme 2.x Post Image', 'WordPress Schema-wp' );?></h3>
					<p><?php _e( 'Using Thesis? The WordPress Schema plugin will automatically indicate and use Thesis Post Image is are presented.', 'WordPress Schema-wp' );?></p>
                    
                    <h3><?php _e( 'The SEO Framework plugin is active?', 'WordPress Schema-wp' );?></h3>
					<p><?php _e( 'No problem! The WordPress Schema plugin will automatically indicate that and show respect for SEO Framework.', 'WordPress Schema-wp' );?></p>
					
                    <h3><?php _e( 'Is Divi your Theme?', 'WordPress Schema-wp' );?></h3>
					<p><?php _e( 'If Divi theme is active, WordPress Schema plugin will clear shortcodes to be able to output the content description.', 'WordPress Schema-wp' );?></p>
                
                </div>
             	
               	<div class="WordPress Schema-types-section sub-section">
                
                	<h2><?php _e( 'WordPress Schema Post Type', 'WordPress Schema-wp' );?></h2>
					<p><?php _e( 'Now, you can create new WordPress Schema.org markup types and enable them on post type bases.', 'WordPress Schema-wp' );?></p>
                    <p><?php _e( 'Also, you can set WordPress Schema to work on specific post categories.', 'WordPress Schema-wp' );?></p>
                    
                    <img src="<?php echo esc_url( WordPress SchemaWP_PLUGIN_URL . 'assets/images/screenshot-2.png' ); ?>" class="WordPress Schema-wp-welcome-screenshots"/>
                    
                    <h2><?php _e( 'Automatically add VideoObject to oEmbed', 'WordPress Schema-wp' );?></h2>
					<p><?php _e( 'WordPress Schema allow you to enable VideoObject markup automatically whenever oEmbed is called on your page.', 'WordPress Schema-wp' );?></p>
                    <p><?php _e( 'Supported oEmbed videos: Dailymotion, TED, Vimeo, VideoPress, Vine, YouTube.', 'WordPress Schema-wp' );?></p>
                    
                    <h2><?php _e( 'Automatically add AudioObject to oEmbed', 'WordPress Schema-wp' );?></h2>
					<p><?php _e( 'WordPress Schema allow you to enable AudioObject markup automatically whenever oEmbed is called on your page.', 'WordPress Schema-wp' );?></p>
                    <p><?php _e( 'Supported oEmbed audios: SoundCloud, and Mixcloud.', 'WordPress Schema-wp' );?></p>
                    
                    
                    
                </div>    
                
			</div>

			
		</div>
		<?php
	}

	/**
	 * Render Getting Started Screen
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function getting_started_screen() {
		list( $display_version ) = explode( '-', WordPress SchemaWP_VERSION );
		?>
		<div class="wrap about-wrap">
			<h1><?php printf( __( 'Welcome to WordPress Schema v%s', 'WordPress Schema-wp' ), esc_html( $display_version ) ); ?></h1>
			<div class="about-text"><?php echo __( 'Thank you for installing WordPress Schema. The best WordPress Schema.org plugin for WordPress.', 'WordPress Schema-wp' ); ?></div>
            <div class="WordPress Schema-wp-badge"><span><?php printf( __( 'Version %s', 'WordPress Schema-wp' ), esc_html( $display_version ) ); ?></span></div>
			        
			<?php $this->tabs(); ?>

			<p class="about-description"><?php _e( 'Hang on! We are going to add more WordPress Schema integration and cool features to WordPress Schema plugin.', 'WordPress Schema-wp' ); ?></p>

			<div class="changelog">
				<h3><?php _e( 'Overview', 'WordPress Schema-wp' );?></h3>

				<div class="feature-section">
					<img src="<?php echo esc_url( WordPress SchemaWP_PLUGIN_URL . 'assets/images/serps.png' ); ?>" class="WordPress Schema-wp-welcome-screenshots"/>
					
                    <h4><?php _e( 'What is WordPress Schema markup?', 'WordPress Schema-wp' );?></h4>
					<p><?php _e( 'WordPress Schema markup is code (semantic vocabulary) that you put on your website to help the search engines return more informative results for users. So, WordPress Schema is not just for SEO reasons, it’s also for the benefit of the searcher.' ,'WordPress Schema-wp' ); ?></p>
                    
					<h4><?php _e( 'Why is Structured Data so Important?', 'WordPress Schema-wp' );?></h4>
					<p><?php _e( 'Structured Data can help you to send the right signals to search engines about your business and content.' ,'WordPress Schema-wp' ); ?></p>
                    <p><?php _e('Structured Data helps search engines to understand what the content is specifically about. Moreover, structured data will allow users to see the value of a website before they visit, via rich snippets, which are rich data that are displayed in the SERP’s.', 'WordPress Schema-wp') ?></p>
				
                	<div class="return-to-dashboard">
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=WordPress Schema' ) ); ?>"><i class="dashicons dashicons-admin-generic"></i><?php _e( 'Go To WordPress Schema Settings', 'WordPress Schema-wp' ); ?></a>
					</div>
                
                </div>
				
			</div>

			<div class="changelog">
				<h3><?php _e( 'Need Help?', 'WordPress Schema-wp' );?></h3>
				
                <div class="feature-section">
					<h4><?php _e( 'Documentation','WordPress Schema-wp' );?></h4>
					<p><?php _e( 'Docs are on its way! We will update <a href="http://WordPress Schema.press/">WordPress Schema.press</a> site with plugin documentation soon.', 'WordPress Schema-wp' );?></p>
				</div>
                
				<div class="feature-section">
					<h4><?php _e( 'Support','WordPress Schema-wp' );?></h4>
					<p><?php _e( 'We do our best to provide support we can. If you encounter a problem, report it to <a href="https://wordpress.org/support/plugin/WordPress Schema">support</a>.', 'WordPress Schema-wp' );?></p>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render Credits Screen
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function credits_screen() {
		list( $display_version ) = explode( '-', WordPress SchemaWP_VERSION );
		?>
		<div class="wrap about-wrap">
			<h1><?php printf( __( 'Welcome to WordPress Schema v%s', 'WordPress Schema-wp' ), esc_html( $display_version ) ); ?></h1>
			<div class="about-text"><?php _e( 'Thank you for updating to the latest version!', 'WordPress Schema-wp' ); ?></div>
			<div class="WordPress Schema-wp-badge"><span><?php printf( __( 'Version %s', 'WordPress Schema-wp' ), esc_html( $display_version ) ); ?></span></div>

			<?php $this->tabs(); ?>

			<p class="about-description"><?php _e( 'Here, we will be listing some of the faces that have helped build WordPress Schema.', 'WordPress Schema-wp' ); ?></p>

			<?php echo $this->contributors(); ?>
		</div>
		<?php
	}

	/**
	 * Render Contributors List
	 *
	 * @since 1.0
	 * @uses WordPress Schema_WP_Welcome::get_contributors()
	 * @return string $contributor_list HTML formatted list of all the contributors for WordPress Schema
	 */
	public function contributors() {
		$contributors = $this->get_contributors();

		if ( empty( $contributors ) ) {
			return '';
		}

		$contributor_list = '<ul class="wp-people-group">';

		foreach ( $contributors as $contributor ) {
			$contributor_list .= '<li class="wp-person">';
			$contributor_list .= sprintf( '<a href="%s" title="%s">',
				esc_url( 'https://github.com/' . $contributor->login ),
				esc_html( sprintf( __( 'View %s', 'WordPress Schema-wp' ), $contributor->login ) )
			);
			$contributor_list .= sprintf( '<img src="%s" width="64" height="64" class="gravatar" alt="%s" />', esc_url( $contributor->avatar_url ), esc_html( $contributor->login ) );
			$contributor_list .= '</a>';
			$contributor_list .= sprintf( '<a class="web" href="%s">%s</a>', esc_url( 'https://github.com/' . $contributor->login ), esc_html( $contributor->login ) );
			$contributor_list .= '</a>';
			$contributor_list .= '</li>';
		}

		$contributor_list .= '</ul>';

		return $contributor_list;
	}

	/**
	 * Retreive list of contributors from GitHub.
	 *
	 * @access public
	 * @since 1.0
	 * @return array $contributors List of contributors
	 */
	public function get_contributors() {
		
		//@ todo
		return;
	}

	/**
	 * Sends user to the Welcome page on first activation of affwp as well as each
	 * time affwp is upgraded to a new version
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function welcome() {

		// Bail if no activation redirect
		if ( ! get_transient( '_WordPress Schema_wp_activation_redirect' ) ) {
			return;
		}

		// Delete the redirect transient
		delete_transient( '_WordPress Schema_wp_activation_redirect' );

		// Bail if activating from network, or bulk
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
			return;
		}

		$upgrade = get_option( 'WordPress Schema_wp_version_upgraded_from' );

		if ( ! $upgrade ) { // First time install
			wp_safe_redirect( admin_url( 'index.php?page=WordPress Schema-wp-getting-started' ) );
			exit;
		} else { // Update
			wp_safe_redirect( admin_url( 'index.php?page=WordPress Schema-wp-what-is-new' ) );
			exit;
		}
	}
}

new WordPress Schema_WP_Welcome;
