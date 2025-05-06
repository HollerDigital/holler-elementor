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
     * Initialize controls based on settings
     */
    private function init_controls() {
        // Get plugin settings
        $options = get_option('holler_elementor_options', array());
        
        // Initialize heading control if enabled (default is enabled)
        if (!isset($options['enable_heading_control']) || $options['enable_heading_control']) {
            $this->heading_control = new Holler_Heading_Control();
        }
        
        // Initialize spacing control if enabled (default is enabled)
        if (!isset($options['enable_spacing_control']) || $options['enable_spacing_control']) {
            $this->spacing_control = new Holler_Spacing_Control();
        }
    }
}
