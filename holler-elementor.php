<?php
/**
 * Plugin Name: Holler Elementor Extension
 * Description: Custom Elementor extension by Holler Digital.
 * Plugin URI:  https://hollerdigital.com/
 * Version:    	2.3.1
 * Author:      Holler Digital
 * Author URI:  https://hollerdigital.com/
 * Text Domain: holler-elementor
 */

// don't allow direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define plugin constants
define( 'HOLLER_ELEMENTOR_DIR', plugin_dir_path( __FILE__ ) );
define( 'HOLLER_ELEMENTOR_THEME_DIR', get_template_directory() );
define( 'HOLLER_ELEMENTOR_VERSION', '2.3.1' );

// Include the plugin updater class
require_once HOLLER_ELEMENTOR_DIR . 'inc/admin/class-plugin-updater.php';

// Initialize the plugin updater
$updater = new Holler_Plugin_Updater(
    'https://github.com/HollerDigital/holler-elementor',
    __FILE__,
    'holler-elementor',
    'master'
);

// Optional: If you're using a private repository, specify the access token like this:
// $updater->set_authentication('your-token-here');


/**
 * Main Holler Elementor Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
final class Holler_Elementor {

	/**
	 * Plugin Version
	 *
	 * @since 1.0.0
	 *
	 * @var string The plugin version.
	 */
	const VERSION = HOLLER_ELEMENTOR_VERSION;

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '3.0.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '8.1';

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @var Holler_Elementor The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return Holler_Elementor An instance of the class.
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
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {

		add_action( 'init', [ $this, 'i18n' ] );
		add_action( 'plugins_loaded', [ $this, 'init' ] );
	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 *
	 * Fired by `init` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function i18n() {

		load_plugin_textdomain( 'holler-elementor' );

	}

	public function holler_elementor_settings(){
		global $cm_options;
		$cm_options = get_option('holler_signup_cm_settings');
	}

	/**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed load the files required to run the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init() {

		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
			return;
		}
    
    add_action( 'elementor/dynamic_tags/register_tags', function( $dynamic_tags ) {
    	// In our Dynamic Tag we use a group named request-variables so we need 
    	// To register that group as well before the tag
    	\Elementor\Plugin::$instance->dynamic_tags->register_group( 'brandt-tags', [
    		'title' => 'Holler Tags' 
    	]);
    
    	 
    	//require_once( __DIR__ . '/inc/tags/brandt-tags.php' );
    
    	// Finally register the tag
    	//$dynamic_tags->register_tag( 'Elementor_Server_Var_Tag' );
    });

    
		// Add Plugin actions
		// These actions are now handled by the Plugin Loader class
		add_action( 'elementor/controls/controls_registered', [ $this, 'init_controls' ] );
		
		// Styles and scripts are now handled by the Plugin Loader class
		
		// add_action( 'elementor/frontend/before_enqueue_scripts', function() {
		//    wp_enqueue_script(
		//    	'brandt-elementor-plugins',
		//    	plugins_url( '/assets/js/plugins.js', __FILE__ ),
		//    	[
		//    		'elementor-frontend', // dependency
		//    	],
		//    	HOLLER_ELEMENTOR_VERSION,
		//    	true // in_footer
		//    );
		// } );
		
		add_action( 'elementor/frontend/before_enqueue_scripts', function() {
		   wp_enqueue_script(
		   	'holler-elementor',
		  plugins_url( '/assets/js/holler-elementor-app.js', __FILE__ ),
		   	[
		   		'elementor-frontend', // dependency
		   	],
		   	HOLLER_ELEMENTOR_VERSION,
		   	true // in_footer
		   );
		} );
		
	// add_menu_page( 'Holler Elementor', 'Holler E',  'manage_options' , 'holler-elementor',  [ $this, 'holler_elementor_settings' ]  ,  'https://s3.ca-central-1.amazonaws.com/cdn.hollerdigital.com/holler-images/holler-icon.svg', 40 );
	
				
	}
  
	/**
	 * Register styles for the plugin
	 *
	 * @deprecated This method is now handled by the Plugin Loader class
	 */
	public function holler_styles() {
		// This functionality has been moved to the Plugin Loader class
		return;
	}
	
	/**
	 * Register scripts for the plugin
	 *
	 * @deprecated This method is now handled by the Plugin Loader class
	 */
	public function holler_scripts() {
		// This functionality has been moved to the Plugin Loader class
		return;
	}

  
	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'holler-elementor' ),
			'<strong>' . esc_html__( 'Holler Elementor Extension', 'holler-elementor' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'holler-elementor' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'holler-elementor' ),
			'<strong>' . esc_html__( 'Holler Elementor Extension', 'holler-elementor' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'holler-elementor' ) . '</strong>',
			 self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'holler-elementor' ),
			'<strong>' . esc_html__( 'Holler Elementor Extension', 'holler-elementor' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'holler-elementor' ) . '</strong>',
			 self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}
  
  /**
   * Create New Widget Category
   *
   * @deprecated This method is now handled by the Plugin Loader class
   * @param object $elements_manager Elementor elements manager.
   */
  public function add_elementor_widget_categories( $elements_manager ) {
    // This functionality has been moved to the Plugin Loader class
    return;
  }


  
	/**
	 * Init Widgets
	 *
	 * Include widgets files and register them
	 *
	 * @deprecated This method is now handled by the Plugin Loader class
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init_widgets() {
		// This functionality has been moved to the Plugin Loader class
		return;
	}

	/**
	 * Init Controls
	 *
	 * Include controls files and register them
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init_controls() {

		// Include Control files
		//require_once( __DIR__ . '/controls/test-control.php' );

		// Register control
		//\Elementor\Plugin::$instance->controls_manager->register_control( 'control-type-', new \Test_Control() );

	}

}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.0
 */
function run_holler_elementor() {
    // Include the plugin loader class
    require_once HOLLER_ELEMENTOR_DIR . 'inc/class-plugin-loader.php';
    
    // Initialize the main plugin class
    $holler_elementor = Holler_Elementor::instance();
    
    // Initialize the plugin loader
    $loader = new Holler_Plugin_Loader();
}

// Run the plugin
add_action('plugins_loaded', 'run_holler_elementor', 11); // Priority 11 to ensure it runs after Elementor

/**
 * Flush rewrite rules on plugin activation.
 *
 * @since 2.3.1
 */
function holler_elementor_activate() {
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'holler_elementor_activate');
