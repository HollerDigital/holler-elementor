<?php
/**
 * Holler Elementor Extension
 *
 * @package Holler_Elementor
 * @subpackage Elementor
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Include control classes
require_once HOLLER_ELEMENTOR_DIR . 'inc/elementor/controls/class-heading-control.php';
require_once HOLLER_ELEMENTOR_DIR . 'inc/elementor/controls/class-spacing-control.php';

/**
 * Holler Elementor Extension Class
 * 
 * Adds custom controls to the Elementor admin interface
 *
 * @since 1.0.0
 */
class Holler_Elementor_Extension {
    /**
     * Heading control instance
     *
     * @var Holler_Heading_Control
     */
    private $heading_control;
    
    /**
     * Spacing control instance
     *
     * @var Holler_Spacing_Control
     */
    private $spacing_control;
    
    /**
     * Constructor
     */
    public function __construct() {
        // Enqueue editor scripts
        add_action('elementor/editor/before_enqueue_scripts', function() {
            wp_enqueue_script(
                'holler-elementor-editor',
                plugin_dir_url(HOLLER_ELEMENTOR_DIR . 'holler-elementor.php') . 'assets/js/holler-elementor-app.js',
                [], // Dependencies
                HOLLER_ELEMENTOR_VERSION,
                true // In footer
            );
        });

        // Initialize controls based on settings
        $this->init_controls();
    }
    
    /**
     * Initialize the controls based on settings
     */
    private function init_controls() {
        try {
            // Get plugin settings with default values
            $default_settings = array(
                'enable_heading_control' => 'off',
                'enable_spacing_control' => 'off',
            );
            $settings = wp_parse_args(get_option('holler_elementor_options', array()), $default_settings);
            
            // Initialize heading control if enabled
            // The checkbox value is saved as '1' in the database, not 'on'
            if (isset($settings['enable_heading_control']) && $settings['enable_heading_control'] == '1') {
                $this->heading_control = new Holler_Heading_Control();
            }
            
            // Initialize spacing control if enabled
            if (isset($settings['enable_spacing_control']) && $settings['enable_spacing_control'] == '1') {
                $this->spacing_control = new Holler_Spacing_Control();
            }
            
            // Process settings silently
        } catch (\Exception $e) {
            // Silently handle exceptions
        }
    }
}
