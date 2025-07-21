<?php
/**
 * Elementor Enhancements Customizer
 *
 * @package Holler_Elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Include the custom controls
require_once plugin_dir_path( __FILE__ ) . 'class-customizer-controls.php';

/**
 * Class Holler_Elementor_Enhancements_Customizer
 * 
 * Handles customizer settings for Elementor enhancements
 */
class Holler_Elementor_Enhancements_Customizer extends Holler_Customizer_Base {

    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'customize_register', array( $this, 'register_customizer_settings' ) );
    }

    /**
     * Register customizer settings
     *
     * @param WP_Customize_Manager $wp_customize The customizer instance.
     */
    public function register_customizer_settings( $wp_customize ) {
        // Ensure parent panel exists
        self::ensure_parent_panel( $wp_customize );
        
        // Add section for Elementor enhancements
        $wp_customize->add_section( 'holler_elementor_enhancements_section', array(
            'title'       => __( 'Elementor Enhancements', 'holler-elementor' ),
            'description' => __( 'Settings to enhance Elementor functionality.', 'holler-elementor' ),
            'panel'       => self::PARENT_PANEL_ID,
            'priority'    => 10,
        ));
        
        // Default Template Settings
        $post_types = get_post_types( array( 'public' => true ), 'objects' );
        $elementor_templates = get_posts( array(
            'post_type'      => 'elementor_library',
            'posts_per_page' => -1,
            'meta_query'     => array(
                array(
                    'key'     => '_elementor_template_type',
                    'value'   => 'page',
                    'compare' => '=',
                ),
            ),
        ));
        
        $templates_options = array(
            '' => __( 'None (Use Elementor Default)', 'holler-elementor' ),
        );
        
        if ( !empty( $elementor_templates ) ) {
            foreach ( $elementor_templates as $template ) {
                $templates_options[$template->ID] = $template->post_title;
            }
        }
        
        $priority = 10;
        foreach ( $post_types as $post_type ) {
            // Skip attachments and Elementor-specific post types
            if ( 'attachment' === $post_type->name || 'elementor_library' === $post_type->name ) {
                continue;
            }
            
            $wp_customize->add_setting( "holler_elementor_default_template_{$post_type->name}", array(
                'default'           => '',
                'sanitize_callback' => 'holler_elementor_sanitize_number_absint',
                'transport'         => 'refresh',
            ));
            
            $wp_customize->add_control( "holler_elementor_default_template_{$post_type->name}", array(
                'label'       => sprintf( __( 'Default Template for %s', 'holler-elementor' ), $post_type->label ),
                'description' => sprintf( __( 'Set default Elementor template for %s post type.', 'holler-elementor' ), $post_type->label ),
                'section'     => 'holler_elementor_enhancements_section',
                'type'        => 'select',
                'choices'     => $templates_options,
                'priority'    => $priority,
            ));
            
            $priority += 10;
        }
        
        // Custom Element Styling
        $wp_customize->add_setting( 'holler_elementor_button_style', array(
            'default'           => 'default',
            'sanitize_callback' => 'holler_elementor_sanitize_select',
            'transport'         => 'refresh',
        ));
        
        $wp_customize->add_control( 'holler_elementor_button_style', array(
            'label'       => __( 'Button Style', 'holler-elementor' ),
            'description' => __( 'Override default Elementor button styling.', 'holler-elementor' ),
            'section'     => 'holler_elementor_enhancements_section',
            'type'        => 'select',
            'choices'     => array(
                'default'       => __( 'Elementor Default', 'holler-elementor' ),
                'rounded'       => __( 'Rounded', 'holler-elementor' ),
                'pill'          => __( 'Pill', 'holler-elementor' ),
                'square'        => __( 'Square', 'holler-elementor' ),
                'custom'        => __( 'Custom', 'holler-elementor' ),
            ),
            'priority'    => $priority,
        ));
        
        // Button Border Radius (Custom)
        $wp_customize->add_setting( 'holler_elementor_button_radius', array(
            'default'           => '3',
            'sanitize_callback' => 'holler_elementor_sanitize_number_absint',
            'transport'         => 'refresh',
        ));
        
        // Use our custom range control for the button radius
        $wp_customize->add_control( new Holler_Elementor_Range_Control( $wp_customize, 'holler_elementor_button_radius', array(
            'label'       => __( 'Button Border Radius (px)', 'holler-elementor' ),
            'description' => __( 'Adjust border radius between 0-50px.', 'holler-elementor' ),
            'section'     => 'holler_elementor_enhancements_section',
            'priority'    => $priority + 10,
            'input_attrs' => array(
                'min'   => 0,
                'max'   => 50,
                'step'  => 1,
            ),
            'active_callback' => function() use ( $wp_customize ) {
                return ( 'custom' === $wp_customize->get_setting( 'holler_elementor_button_style' )->value() );
            },
        )));
    }


}

// Initialize class
new Holler_Elementor_Enhancements_Customizer();
