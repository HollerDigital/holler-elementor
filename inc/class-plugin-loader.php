<?php
/**
 * Plugin Loader
 *
 * @package Holler_Elementor
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Holler Plugin Loader Class
 *
 * Handles loading all plugin components and hooks
 *
 * @since 1.0.0
 */
class Holler_Plugin_Loader {

    /**
     * Constructor
     */
    public function __construct() {
        $this->includes();
        $this->init_hooks();
        $this->init_components();
    }

    /**
     * Include required files
     */
    private function includes() {
        // Helper functions
        require_once HOLLER_ELEMENTOR_DIR . 'inc/helpers/functions.php';
        
        // Layouts
        require_once HOLLER_ELEMENTOR_DIR . 'inc/layouts/holler-team.php';
        require_once HOLLER_ELEMENTOR_DIR . 'inc/layouts/team-modal-script.php';
        
        // Elementor extension class
        require_once HOLLER_ELEMENTOR_DIR . 'inc/elementor/class-holler-elementor-extension.php';
        
        // Admin classes
        require_once HOLLER_ELEMENTOR_DIR . 'inc/admin/class-holler-team-settings.php';
        require_once HOLLER_ELEMENTOR_DIR . 'inc/admin/class-holler-settings.php';
        
        // Customizer classes
        require_once HOLLER_ELEMENTOR_DIR . 'inc/customizer/class-holler-customizer-base.php';
        require_once HOLLER_ELEMENTOR_DIR . 'inc/customizer/class-responsive-spacing-customizer.php';
        require_once HOLLER_ELEMENTOR_DIR . 'inc/customizer/class-responsive-heading-customizer.php';
        require_once HOLLER_ELEMENTOR_DIR . 'inc/customizer/sanitization.php';
        require_once HOLLER_ELEMENTOR_DIR . 'inc/customizer/class-elementor-enhancements-customizer.php';

        // Flush rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Register custom image size for team members
        add_action('after_setup_theme', array($this, 'register_image_sizes'));
        
        // Register Elementor widgets
        add_action('elementor/widgets/widgets_registered', array($this, 'register_widgets'));
        
        // Register Elementor categories
        add_action('elementor/elements/categories_registered', array($this, 'register_widget_categories'));
        
        // Register styles and scripts
        add_action('wp_enqueue_scripts', array($this, 'register_styles'));
        add_action('wp_enqueue_scripts', array($this, 'register_scripts'));
        add_action('elementor/editor/after_enqueue_styles', array($this, 'register_styles'));
        add_action('elementor/frontend/after_enqueue_styles', array($this, 'register_styles'));
        add_action('elementor/frontend/after_register_scripts', array($this, 'register_scripts'));
        add_action('elementor/preview/enqueue_styles', array($this, 'register_styles'));
        add_action('elementor/preview/enqueue_scripts', array($this, 'register_scripts'));
        
        // Add memory monitoring hook for debugging
        if (defined('WP_DEBUG') && WP_DEBUG) {
            add_action('shutdown', array($this, 'log_memory_usage'));
        }
    }

    /**
     * Initialize plugin components
     */
    private function init_components() {
        // Initialize Elementor extension
        new Holler_Elementor_Extension();
        
        // Initialize Settings page
        new Holler_Settings();
    }

    /**
     * Register custom image sizes
     */
    public function register_image_sizes() {
        add_image_size('holler-team-member', 800, 800, true); // Hard crop for consistent dimensions
    }
    
    /**
     * Register Elementor widget categories
     * 
     * @param \Elementor\Elements_Manager $elements_manager Elementor elements manager.
     */
    public function register_widget_categories($elements_manager) {
        $elements_manager->add_category(
            'holler',
            [
                'title' => esc_html__('Holler Widgets', 'holler-elementor'),
                'icon' => 'fa fa-plug',
            ]
        );
    }
    
    /**
     * Register Elementor widgets
     */
    public function register_widgets() {
        // Include Widget files
        require_once HOLLER_ELEMENTOR_DIR . 'inc/widgets/holler-team.php';
        
        // Register widget
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Holler_Team_Widget());
    }

    /**
     * Register styles for the plugin
     */
    public function register_styles() {
        // Get the main plugin file path
        $plugin_file = trailingslashit(dirname(dirname(__FILE__))) . 'holler-elementor.php';
        
        // Register the main stylesheet
        wp_register_style(
            'holler-elementor', 
            plugins_url('assets/css/styles.css', $plugin_file),
            array(),
            HOLLER_ELEMENTOR_VERSION,
            'all'
        );
        
        // Only enqueue on frontend or in Elementor editor
        if (!is_admin() || isset($_GET['elementor-preview'])) {
            wp_enqueue_style('holler-elementor');
        }
        
        // Register style silently
    }
    
    /**
     * Register scripts for the plugin
     */
    public function register_scripts() {
        // Get the main plugin file path
        $plugin_file = trailingslashit(dirname(dirname(__FILE__))) . 'holler-elementor.php';
        
        // Register the main script with cache busting
        wp_register_script(
            'holler-elementor',
            plugins_url('assets/js/holler-elementor-app.js', $plugin_file) . '?ver=' . time(),
            array('jquery'),
            HOLLER_ELEMENTOR_VERSION,
            true
        );
        
        // Register the elementor button styles script
        wp_register_script(
            'holler-elementor-button-styles',
            plugins_url('assets/js/elementor-button-styles.js', $plugin_file) . '?ver=' . time(),
            array('jquery'),
            HOLLER_ELEMENTOR_VERSION,
            true
        );
        
        // Only enqueue on frontend or in Elementor editor
        if (!is_admin() || isset($_GET['elementor-preview'])) {
            wp_enqueue_script('holler-elementor');
            wp_enqueue_script('holler-elementor-button-styles');
            
            // Localize script with Elementor customizer settings
            wp_localize_script(
                'holler-elementor-button-styles',
                'hollerElementorData',
                array(
                    'elementorButtonStyle' => esc_attr(get_theme_mod('holler_elementor_button_style', 'default')),
                    'buttonBorderRadius' => absint(get_theme_mod('holler_elementor_button_radius', 3))
                )
            );
        }
    }
    
    /**
     * Log memory usage for debugging
     */
    public function log_memory_usage() {
        // No longer logging memory usage
    }
}
