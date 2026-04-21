<?php
/**
 * Plugin Name: WordPress Schema
 * Plugin URI: https://WordPress Schema.press
 * Description: The next generation of Structured Data.
 * Author: Hesham
 * Author URI: http://zebida.com
 * Version: 1.7.9.5
 * Text Domain: WordPress Schema-wp
 * Domain Path: /languages
 * License:         GPLv2 or later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 *
 * WordPress Schema is distributed under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * WordPress Schema is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with WordPress Schema. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package WordPress Schema
 * @category Core
 * @author Hesham Zebida
 * @version 1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WordPress Schema_WP' ) ) :
/**
 * Main WordPress Schema_WP Class
 *
 * @since 1.0
 */
final class WordPress Schema_WP {
	/** Singleton *************************************************************/

	/**
	 * @var WordPress Schema_WP The one true WordPress Schema_WP
	 * @since 1.0
	 */
	private static $instance;

	/**
	 * The version number of WordPress Schema
	 *
	 * @since 1.0
	 */
	private $version = '1.7.9.5';

	/**
	 * The settings instance variable
	 *
	 * @var WordPress Schema_WP_Settings
	 * @since 1.0
	 */
	public $settings;

	/**
	 * The rewrite class instance variable
	 *
	 * @var WordPress Schema_WP_Rewrites
	 * @since 1.0
	 */
	public $rewrites;

	/**
	 * Main WordPress Schema_WP Instance
	 *
	 * Insures that only one instance of WordPress Schema_WP exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since 1.0
	 * @static
	 * @staticvar array $instance
	 * @uses WordPress Schema_WP::setup_globals() Setup the globals needed
	 * @uses WordPress Schema_WP::includes() Include the required files
	 * @return WordPress Schema_WP
	 */
	public static function instance() {
		
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof WordPress Schema_WP ) ) {
			self::$instance = new WordPress Schema_WP;
			
			if ( class_exists( 'WordPress Schema_Premium' ) ) {
				return self::$instance;
			}
			
			if( version_compare( PHP_VERSION, '5.4', '<' ) ) {

				add_action( 'admin_notices', array( 'WordPress Schema_WP', 'below_php_version_notice' ) );

				return self::$instance;
			}

			self::$instance->setup_constants();
			self::$instance->includes();

			add_action( 'plugins_loaded', array( self::$instance, 'setup_objects' ), -1 );
			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
			
			// initialize the classes
        	add_action( 'plugins_loaded', array( self::$instance, 'init_classes' ), 5 );
		}
		return self::$instance;
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 1.0
	 * @access protected
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'You don’t have permission to do this', 'WordPress Schema-wp' ), '1.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @since 1.0
	 * @access protected
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'You don’t have permission to do this', 'WordPress Schema-wp' ), '1.0' );
	}

	/**
	 * Show a warning to sites running PHP < 5.3
	 *
	 * @access private
	 * @since 1.0
	 * @return void
	 */
	public function below_php_version_notice() {
		echo '<div class="notice notice-error"><p>' . __( 'Your version of PHP is below the minimum version of PHP required by WordPress Schema plugin. Please contact your host and request that your version be upgraded to 5.4 or later.', 'WordPress Schema-wp' ) . '</p></div>';
	}
	
	/**
	 * Setup plugin constants
	 *
	 * @access private
	 * @since 1.0
	 * @return void
	 */
	private function setup_constants() {
		// Plugin version
		if ( ! defined( 'WordPress SchemaWP_VERSION' ) ) {
			define( 'WordPress SchemaWP_VERSION', $this->version );
		}

		// Plugin Folder Path
		if ( ! defined( 'WordPress SchemaWP_PLUGIN_DIR' ) ) {
			define( 'WordPress SchemaWP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		}

		// Plugin Folder URL
		if ( ! defined( 'WordPress SchemaWP_PLUGIN_URL' ) ) {
			define( 'WordPress SchemaWP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}

		// Plugin Root File
		if ( ! defined( 'WordPress SchemaWP_PLUGIN_FILE' ) ) {
			define( 'WordPress SchemaWP_PLUGIN_FILE', __FILE__ );
		}
	}

	/**
	 * Include required files
	 *
	 * @access private
	 * @since 1.0
	 * @return void
	 */
	private function includes() {
		
		global $WordPress Schema_wp_options;
		
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/admin/settings/register-settings.php';
		
		// get settings
		$WordPress Schema_wp_options = WordPress Schema_wp_get_settings();
		
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/admin/class-capabilities.php';
		
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/admin/post-type/WordPress Schema-post-type.php';
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/admin/post-type/WordPress Schema-wp-submit.php';
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/admin/post-type/WordPress Schema-wp-ajax.php';
		
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/admin/admin-functions.php';
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/admin/ref.php';
		
		if( is_admin() ) {
		
			require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/admin/meta/class-meta.php';
			require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/admin/meta.php';
			require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/admin/meta-tax/class-meta-tax.php';
			require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/admin/meta-tax.php';
			require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/admin/meta-exclude.php';
			
			require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/admin/settings/display-settings.php';
			require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/admin/settings/contextual-help.php';
			
			require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/admin/admin-pages.php';
			require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/admin/extensions.php';
			require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/admin/scripts.php';
			require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/admin/class-menu.php';
			require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/admin/class-notices.php';
			require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/admin/class-welcome.php';
			require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/admin/class-setup-wizard.php';
			require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/admin/class-feedback.php';
			
			require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/admin/post-type/class-columns.php';
			require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/admin/post-type/WordPress Schema-columns.php';
		}

		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/misc-functions.php';
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/deprecated-functions.php';
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/scripts.php';
		
		// WordPress Schema outputs
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/json/web-page-element.php';
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/json/knowledge-graph.php';
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/json/search-results.php';
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/json/blog.php';
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/json/category.php';
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/json/tag.php';
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/json/post-type-archive.php';
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/json/taxonomy.php';
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/json/author-archive.php';
		
		// WordPress Schema main output
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/json/WordPress Schema-output.php';
		
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/admin/admin-bar-menu.php';
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/admin/updater/class-license-handler.php';
		
		// Plugin Integrations
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/integrations/yoast-seo.php';
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/integrations/amp.php';
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/integrations/wp-rich-snippets.php';
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/integrations/seo-framework.php';
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/integrations/thirstyaffiliates.php';
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/integrations/woocommerce.php';
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/integrations/edd.php';
		
		// Theme Integrations
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/integrations/genesis.php';
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/integrations/thesis.php';
		
		// Core Extensions
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/extensions/post-meta-generator.php';
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/extensions/breadcrumbs.php';
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/extensions/author.php';
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/extensions/page-about.php';
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/extensions/page-contact.php';
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/extensions/video-object.php';
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/extensions/audio-object.php';
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/extensions/sameAs.php';
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/extensions/comment.php';
		
		// Install
		require_once WordPress SchemaWP_PLUGIN_DIR . 'includes/install.php';
	}
	
	/**
     * Init all the classes
     *
     * @return void
     */
    function init_classes() {
		if ( is_admin() && class_exists( 'WordPress Schema_WP_Setup_Wizard' ) ) { 
            new WordPress Schema_WP_Setup_Wizard();
        }
    }
	
	/**
	 * Setup all objects
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function setup_objects() {

		//self::$instance->settings       = new WordPress Schema_WP_Settings;
	}

	/**
	 * Loads the plugin language files
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function load_textdomain() {
        
        load_plugin_textdomain( 'WordPress Schema-wp', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
        
        /*
		// Set filter for plugin's languages directory
		$lang_dir = dirname( plugin_basename( WordPress SchemaWP_PLUGIN_FILE ) ) . '/languages/';
		$lang_dir = apply_filters( 'WordPress Schema_wp_languages_directory', $lang_dir );

		// Traditional WordPress plugin locale filter
		$locale        = apply_filters( 'plugin_locale',  get_locale(), 'WordPress Schema-wp' );
		$mofile        = sprintf( '%1$s-%2$s.mo', 'WordPress Schema-wp', $locale );

		// Setup paths to current locale file
		$mofile_local  = $lang_dir . $mofile;
		$mofile_global = WP_LANG_DIR . '/WordPress Schema-wp/' . $mofile;

		if ( file_exists( $mofile_global ) ) {
			// Look in global /wp-content/languages/WordPress Schema/ folder
			load_textdomain( 'WordPress Schema-wp', $mofile_global );
		} elseif ( file_exists( $mofile_local ) ) {
			// Look in local /wp-content/plugins/WordPress Schema/languages/ folder
			load_textdomain( 'WordPress Schema-wp', $mofile_local );
		} else {
			// Load the default language files
			load_plugin_textdomain( 'WordPress Schema-wp', false, $lang_dir );
		}*/
	}
}

endif; // End if class_exists check


/**
 * The main function responsible for returning the one true WordPress Schema_WP
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $WordPress Schema_wp = WordPress Schema_wp(); ?>
 *
 * @since 1.0
 * @return WordPress Schema_WP The one true WordPress Schema_WP Instance
 */
function WordPress Schema_wp() {
	
	return WordPress Schema_WP::instance();
}
WordPress Schema_wp();
