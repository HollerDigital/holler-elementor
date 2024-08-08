<?php
/**
 * Plugin Name: Holler Elementor Extension
 * Description: Custom Elementor extension by Holler Digital.
 * Plugin URI:  https://hollerdigital.com/
 * Version:    	2.1.6 
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
define( 'HOLLER_ELEMENTOR_VERSION', '2.1.6' );

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



class Holler_Team_Settings {

    public function __construct() {
        add_action( 'admin_menu', [ $this, 'add_settings_page' ] );
        add_action( 'admin_init', [ $this, 'register_settings' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_color_picker' ] );
		add_action('wp_head', [ $this, 'output_custom_styles' ]);
    }

    public function add_settings_page() {
        add_menu_page(
            'Holler Team Settings',
            'Holler Team',
            'manage_options',
            'holler-team-settings',
            [ $this, 'holler_team_settings_html' ],
            'dashicons-admin-customizer',
            100
        );
    }

    public function register_settings() {
        register_setting( 'holler_team_settings_group', 'holler_team_settings' );

        add_settings_section(
            'holler_team_settings_section',
            __( 'Default Styles', 'plugin-domain' ),
            '__return_false',
            'holler-team-settings'
        );

        // Team Name Size
        add_settings_field(
            'team_name_size',
            __( 'Team Name Size', 'plugin-domain' ),
            [ $this, 'render_font_size_input' ],
            'holler-team-settings',
            'holler_team_settings_section',
            [
                'label_for' => 'team_name_size',
                'default'   => ['size' => '1.2', 'unit' => 'rem']
            ]
        );

        // Team Title Size
        add_settings_field(
            'team_title_size',
            __( 'Team Title Size', 'plugin-domain' ),
            [ $this, 'render_font_size_input' ],
            'holler-team-settings',
            'holler_team_settings_section',
            [
                'label_for' => 'team_title_size',
                'default'   => ['size' => '1', 'unit' => 'em']
            ]
        );

        // Modal Name Size
        add_settings_field(
            'modal_name_size',
            __( 'Modal Name Size', 'plugin-domain' ),
            [ $this, 'render_font_size_input' ],
            'holler-team-settings',
            'holler_team_settings_section',
            [
                'label_for' => 'modal_name_size',
                'default'   => ['size' => '1.5', 'unit' => 'em']
            ]
        );

        // Modal Title Size
        add_settings_field(
            'modal_title_size',
            __( 'Modal Title Size', 'plugin-domain' ),
            [ $this, 'render_font_size_input' ],
            'holler-team-settings',
            'holler_team_settings_section',
            [
                'label_for' => 'modal_title_size',
                'default'   => ['size' => '1.25', 'unit' => 'em']
            ]
        );

        // Modal Background Color
        add_settings_field(
            'modal_bg_color',
            __( 'Modal Background Color', 'plugin-domain' ),
            [ $this, 'render_color_input' ],
            'holler-team-settings',
            'holler_team_settings_section',
            [
                'label_for' => 'modal_bg_color',
                'default'   => 'rgba(8, 0, 92, 0.9)'
            ]
        );

        // Modal Text Color
        add_settings_field(
            'modal_text_color',
            __( 'Modal Text Color', 'plugin-domain' ),
            [ $this, 'render_color_input' ],
            'holler-team-settings',
            'holler_team_settings_section',
            [
                'label_for' => 'modal_text_color',
                'default'   => '#08005C'
            ]
        );
    }

    public function render_font_size_input($args) {
        $options = get_option('holler_team_settings');
        $size = isset($options[$args['label_for']]['size']) ? esc_attr($options[$args['label_for']]['size']) : esc_attr($args['default']['size']);
        $unit = isset($options[$args['label_for']]['unit']) ? esc_attr($options[$args['label_for']]['unit']) : esc_attr($args['default']['unit']);
        ?>
        <input type="number" id="<?php echo esc_attr($args['label_for']); ?>" name="holler_team_settings[<?php echo esc_attr($args['label_for']); ?>][size]" value="<?php echo $size; ?>" min="0" step="0.1" style="width: 70px;">
        <select name="holler_team_settings[<?php echo esc_attr($args['label_for']); ?>][unit]">
            <option value="px" <?php selected($unit, 'px'); ?>>px</option>
            <option value="em" <?php selected($unit, 'em'); ?>>em</option>
            <option value="rem" <?php selected($unit, 'rem'); ?>>rem</option>
            <option value="%" <?php selected($unit, '%'); ?>>%</option>
        </select>
        <?php
    }

    public function render_color_input($args) {
        $options = get_option('holler_team_settings');
        ?>
        <input type="text" class="color-picker" id="<?php echo esc_attr($args['label_for']); ?>" name="holler_team_settings[<?php echo esc_attr($args['label_for']); ?>]" value="<?php echo isset($options[$args['label_for']]) ? esc_attr($options[$args['label_for']]) : esc_attr($args['default']); ?>">
        <?php
    }

    public function enqueue_color_picker($hook_suffix) {
        if ('toplevel_page_holler-team-settings' !== $hook_suffix) {
            return;
        }

        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('holler_team_color_picker', plugins_url('color-picker.js', __FILE__), array('wp-color-picker'), false, true);

        // Inline script to initialize the color picker
        wp_add_inline_script('holler_team_color_picker', 'jQuery(document).ready(function($){$(".color-picker").wpColorPicker();});');
    }

    public function holler_team_settings_html() {
        ?>
        <div class="wrap">
            <h1><?php _e('Holler Team Settings', 'plugin-domain'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('holler_team_settings_group');
                do_settings_sections('holler-team-settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

	public function output_custom_styles() {
		$options = get_option('holler_team_settings');
	
		// Retrieve and sanitize the values
		$team_name_size = isset($options['team_name_size']) ? esc_attr($options['team_name_size']['size']) . esc_attr($options['team_name_size']['unit']) : '1.2rem';
		$team_name_color = isset($options['team_name_color']) ? esc_attr($options['team_name_color']) : '#08005C';
	
		$team_title_size = isset($options['team_title_size']) ? esc_attr($options['team_title_size']['size']) . esc_attr($options['team_title_size']['unit']) : '1em';
		$team_title_color = isset($options['team_title_color']) ? esc_attr($options['team_title_color']) : '#8C4EFD';
	
		$modal_bg_color = isset($options['modal_bg_color']) ? esc_attr($options['modal_bg_color']) : 'rgba(8, 0, 92, 0.9)';
		$modal_name_size = isset($options['modal_name_size']) ? esc_attr($options['modal_name_size']['size']) . esc_attr($options['modal_name_size']['unit']) : '1.5em';
		$modal_title_size = isset($options['modal_title_size']) ? esc_attr($options['modal_title_size']['size']) . esc_attr($options['modal_title_size']['unit']) : '1.25em';
		$modal_text_color = isset($options['modal_text_color']) ? esc_attr($options['modal_text_color']) : '#08005C';
	
		// Output the custom CSS
		echo "<style type='text/css'>
			:root {
				--holler-team-name-size: {$team_name_size};
				--holler-team-name-color: {$team_name_color};
				--holler-team-title-size: {$team_title_size};
				--holler-team-title-color: {$team_title_color};
				--holler-team-modal-bgcolor: {$modal_bg_color};
				--holler-team-modal-name-size: {$modal_name_size};
				--holler-team-modal-title-size: {$modal_title_size};
				--holler-team-modal-color: {$modal_text_color};
			}
		</style>";
	}
	
}

// Instantiate the class
//new Holler_Team_Settings();


