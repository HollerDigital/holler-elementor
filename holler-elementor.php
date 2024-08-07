<?php
/**
 * Plugin Name: Holler Elementor Extension
 * Description: Custom Elementor extension by Holler Digital.
 * Plugin URI:  https://hollerdigital.com/
 * Version:    	2.1.4 
 * Author:      Holler Digital
 * Author URI:  https://hollerdigital.com/
 * Text Domain: elementor-test-extension
 */

 //https://s3.ca-central-1.amazonaws.com/cdn.hollerdigital.com/holler-images/holler-icon.svg
// don't allow direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HOLLER_ELEMENTOR_DIR', plugin_dir_path( __FILE__ ) );
define( 'HOLLER_ELEMENTOR_THEME_DIR', get_template_directory() );
define( 'HOLLER_ELEMENTOR_VERSION', '2.1.4' );

// Plugin Updater
// https://github.com/YahnisElsts/plugin-update-checker
require 'inc/plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/HollerDigital/holler-elementor',
	__FILE__,
	'holler-elementor'
);
 
//Set the branch that contains the stable release.
$myUpdateChecker->setBranch('master');
$myUpdateChecker->getVcsApi()->enableReleaseAssets();

//Optional: If you're using a private repository, specify the access token like this:
//$myUpdateChecker->setAuthentication('your-token-here');


/**
 * Main Elementor Test Extension Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
final class Elementor_Test_Extension {

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
	const MINIMUM_PHP_VERSION = '7.2';

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @var Elementor_Test_Extension The single instance of the class.
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

		load_plugin_textdomain( 'elementor-test-extension' );

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
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
		add_action( 'elementor/controls/controls_registered', [ $this, 'init_controls' ] );
		add_action( 'elementor/elements/categories_registered', [ $this,'add_elementor_widget_categories'] );
		
		// Register Widget Styles
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'brandt_styles' ] , 500	);
		
		// Register Widget Scripts
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'brandt_scripts' ] );
		
		
		add_action( 'elementor/preview/enqueue_styles', [ $this, 'brandt_styles' ] );
		add_action( 'elementor/preview/enqueue_styles', [ $this, 'brandt_scripts' ] );
		
		
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'brandt_styles' ] );
		//add_action( 'elementor/frontend/before_enqueue_scripts', [ $this, 'brandt_scripts' ] );
		
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
  
	public function brandt_styles() {
    	wp_register_style( 'holler-elementor',  plugins_url( '/assets/css/styles.css', __FILE__ ), array( ),  HOLLER_ELEMENTOR_VERSION, 'all' );
    	wp_enqueue_style( 'holler-elementor');
	}
	
	public function brandt_scripts() {
    	//wp_register_script( 'brandt-elementor-plugins', plugins_url( '/assets/js/plugins.js', __FILE__ ), array('jquery'),HOLLER_ELEMENTOR_VERSION, true );
    	wp_register_script( 'holler-elementor', plugins_url( '/assets/js/holler-elementor-app.js', __FILE__ ), array('jquery'),HOLLER_ELEMENTOR_VERSION, true );
		// wp_enqueue_script("jquery-ui-core");
		// wp_enqueue_script("jquery-ui-tabs");
		//wp_enqueue_script('brandt-elementor-plugins', 1);
		wp_enqueue_script('holler-elementor', 1);
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
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'elementor-test-extension' ),
			'<strong>' . esc_html__( 'Elementor Test Extension', 'elementor-test-extension' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'elementor-test-extension' ) . '</strong>'
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
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-test-extension' ),
			'<strong>' . esc_html__( 'Elementor Test Extension', 'elementor-test-extension' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'elementor-test-extension' ) . '</strong>',
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
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-test-extension' ),
			'<strong>' . esc_html__( 'Elementor Test Extension', 'elementor-test-extension' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'elementor-test-extension' ) . '</strong>',
			 self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}
  
  /**
    Create New Widget Category
  */
  public function add_elementor_widget_categories( $elements_manager ) {

  	$elements_manager->add_category(
  		'holler',
  		[
  			'title' => __( 'Holler Widgets', 'plugin-name' ),
  			'icon' => 'fa fa-plug',
  		]
  	);
  }


  
	/**
	 * Init Widgets
	 *
	 * Include widgets files and register them
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init_widgets() {

		// Include Widget files
	 
	  require_once( __DIR__ . '/inc/widgets/holler-team.php' );
 
 
	   
	     	   
		// Register widget
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Holler_Team_Widget() );	
 
    
    
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

Elementor_Test_Extension::instance();

// Helper Functions
require_once( __DIR__ .'/inc/helpers/functions.php' );

// Layouts
require_once( __DIR__ .'/inc/layouts/holler-team.php' );

class Holler_Widgets_Manager {
    public function __construct() {
        add_action('admin_menu', array($this, 'register_my_custom_menu_page'));
        add_action('admin_init', array($this, 'register_my_custom_settings'));
        add_action('elementor/widgets/register', array($this, 'unregister_elementor_widgets_based_on_settings'), 99);
        add_action('elementor/widgets/widgets_registered', array($this, 'unregister_elementor_widgets_based_on_settings'), 15);
		add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
    }

    public function register_my_custom_menu_page() {
		add_options_page(
			__('Holler Elementor Settings', 'holler'), // Page title
			__('Holler Elementor', 'holler'), // Menu title
			'manage_options', // Capability
			'holler-elementor-settings', // Menu slug
			array($this, 'elementor_widgets_settings_page'), // Function to display the settings page
		);
    }
	public function enqueue_admin_styles() {
        // Enqueue your stylesheet here

        wp_enqueue_style('holler-admin-style',  plugins_url( '/assets/css/holler-admin.css', __FILE__ ), array( ),  null, 'all' );
    }

    public function elementor_widgets_settings_page() {
        ?>
        <div class="holler-menu-page-wrapper">
            <h2>Elementor Widgets Settings</h2>
            <form method="post" action="options.php">
                <?php
                settings_fields('elementor-widgets-settings-group');
                do_settings_sections('elementor-widgets-settings-group');
                $elementor_widget_blacklist = get_option('elementor_widget_blacklist');
                if (!is_array($elementor_widget_blacklist)) {
                    $elementor_widget_blacklist = []; // Initialize as empty array if not set
                }
                // List of all widgets
                $widgets = $this->get_all_widgets();

				echo "<div class='holler-widget-grid'>";
                foreach ($widgets as $widget) {
                    // Check if the widget is set in the array and then use its value for the checked attribute
                    $is_checked = isset($elementor_widget_blacklist[$widget]) ? $elementor_widget_blacklist[$widget] : '';
                    ?>
						<div class="holler-widget-control">
							<h2><?php echo $widget; ?></h2>
							<div class="holler-container">
								<label class="holler-switch" for="<?php echo $widget; ?>">
								<input type="checkbox" name="elementor_widget_blacklist[<?php echo $widget; ?>]" value="1" <?php checked(1, $is_checked, true); ?> id="<?php echo $widget; ?>">
									<div class="holler-switch-slider round"></div>
								</label>
							</div>
						</div>

			 
                  
                    <?php
                }
				echo "</div>";
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

	public function register_my_custom_settings() {
        register_setting(
            'elementor-widgets-settings-group', // Option group
            'elementor_widget_blacklist', // Option name
            array($this, 'sanitize_elementor_widget_blacklist') // Sanitize callback
        );
    }

	public function sanitize_elementor_widget_blacklist($input) {
        // Ensure the input is an array
        if (!is_array($input)) {
            $input = [];
        }

        // Sanitize each widget name in the array
        $input = array_map('sanitize_text_field', $input);

        // Return the sanitized array
        return $input;
    }


    public function unregister_elementor_widgets_based_on_settings($widgets_manager) {
        $elementor_widget_blacklist = get_option('elementor_widget_blacklist');
        if (!is_array($elementor_widget_blacklist)) {
            return;
        }

        $all_widgets = $this->get_all_widgets();

        foreach ($all_widgets as $widget_name) {
		 
            // if (!in_array($widget_name, $elementor_widget_blacklist)) {
			if( isset($elementor_widget_blacklist[$widget_name]) && $elementor_widget_blacklist[$widget_name] == 1){
				continue;
			}else {
				$widgets_manager->unregister($widget_name);
			}
        }
    }

    protected function get_all_widgets() {
        // Return the array of all widgets
        return [
			//'common'
			//,'heading'
			//,'image'
			//,'text-editor'
			//,'video'
			//,'button'
			'divider'
			,'spacer'
			,'image-box'
			,'google-maps'
			,'icon'
			,'icon-box'
			,'image-gallery'
			,'image-carousel'
			,'icon-list'
			,'counter'
			,'progress'
			,'testimonial'
			,'tabs'
			,'accordion'
			,'toggle'
			,'social-icons'
			,'alert'
			,'audio'
			,'shortcode'
			,'html'
			,'menu-anchor'
			,'sidebar'
		  
			// pro ----------------- //
		   	,'posts'
			,'portfolio'
			,'slides'
			,'form'
			,'login'
			,'media-carousel'
			,'testimonial-carousel'
			,'nav-menu'
			,'pricing'
			,'facebook-comment'
			,'nav-menu'
			,'animated-headline'
			,'price-list'
			,'price-table'
			,'facebook-button'
			,'facebook-comments'
			,'facebook-embed'
			,'facebook-page'
			,'add-to-cart'
			,'categories'
			,'elements'
			,'products'
			,'flip-box'
			,'carousel'
			,'countdown'
			,'share-buttons'
			,'author-box'
			,'breadcrumbs'
			,'search-form'
			,'post-navigation'
			,'post-comments'
			,'theme-elements'
			,'blockquote'
			,'template'
			,'wp-widget-audio'
			,'woocommerce'
			,'social'
			,'library'

			// wp widgets ----------------- //
			,'wp-widget-pages'
			,'wp-widget-archives'
			,'wp-widget-media_audio'
			,'wp-widget-media_image'
			,'wp-widget-media_gallery'
			,'wp-widget-media_video'
			,'wp-widget-meta'
			,'wp-widget-search'
			,'wp-widget-text'
			,'wp-widget-categories'
			,'wp-widget-recent-posts'
			,'wp-widget-recent-comments'
			,'wp-widget-rss'
			,'wp-widget-tag_cloud'
			,'wp-widget-nav_menu'
			,'wp-widget-custom_html'
			,'wp-widget-polylang'
			,'wp-widget-calendar'
			,'wp-widget-elementor-library'
			,'wp-widget-block'
        ];
    }
}

// Instantiate the class
// new Holler_Widgets_Manager();

class Holler_Elementor_Extension {
    public function __construct() {
		//require_once( __DIR__ .'/inc/helpers/functions.php' );
		add_action('elementor/editor/before_enqueue_scripts', function() {
			wp_enqueue_script(
				'holler-elementor-editor',
				plugin_dir_url(__FILE__) . 'assets/js/holler-elementor-app.js', // Adjust the path
				[], // Dependencies
				'1.0.01', // Version number
				true // In footer
			);
		});

        // Hook into Elementor to add custom controls
        add_action('elementor/element/container/section_layout/after_section_end', array($this, 'add_custom_spacing_control'), 10, 2);

        // Hook into Elementor's frontend rendering to modify container classes
        add_action('elementor/frontend/container/before_render', array($this, 'modify_container_classes'));
		add_action('elementor/element/container/before_render', array($this, 'modify_container_classes'));
    }

    public function add_custom_spacing_control($element, $args) {
        $element->start_controls_section(
            'my_custom_section',
            [
                'label' => __('Container Spacing', 'text-domain'),
                'tab' => \Elementor\Controls_Manager::TAB_LAYOUT,
            ]
        );

        $element->add_control(
            'holler_container_spacing',
            [
                'label' => __('Container Spacing', 'text-domain'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'solid',
                'options' => [
                    '' => esc_html__('Default', 'textdomain'),
                    'no-padding' => esc_html__('No Padding', 'textdomain'),
                    'xxl-hero-padding' => esc_html__('XXL Hero Padding', 'textdomain'),
                    'xl-padding' => esc_html__('XL Padding', 'textdomain'),
                    'large-padding' => esc_html__('Large Padding', 'textdomain'),
                    'medium-padding' => esc_html__('Medium Padding', 'textdomain'),
					'small-padding' => esc_html__('Small Padding', 'textdomain'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .your-class' => 'border-style: {{VALUE}};',
                ],
            ]
        );

        $element->end_controls_section();
    }

    public function modify_container_classes($element) {
        // Check if it's the container widget
        if ('container' === $element->get_name()) {
            // Get the settings
            $settings = $element->get_settings_for_display();

            // Check if your custom control has a value
            if (!empty($settings['holler_container_spacing'])) {
                // Add the value of the custom control as a class
                $element->add_render_attribute('_wrapper', 'class', 'holler-container-' . $settings['holler_container_spacing'], true);
            } else {
                $element->add_render_attribute('_wrapper', ['class' => ['holler-container-default']]);
            }
        }
    }
}

// Instantiate the class to ensure it's loaded
new Holler_Elementor_Extension();
