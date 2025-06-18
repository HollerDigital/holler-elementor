<?php
/**
 * Holler Customizer Base
 *
 * @package Holler_Elementor
 */

/**
 * Class Holler_Customizer_Base
 * 
 * Base class for shared customizer functionality
 */
class Holler_Customizer_Base {
    
    /**
     * Parent panel ID for all Holler Elementor customizer panels
     */
    const PARENT_PANEL_ID = 'holler_elementor_panel';
    
    /**
     * Create the parent panel if it doesn't exist
     *
     * @param WP_Customize_Manager $wp_customize The customizer instance.
     */
    public static function ensure_parent_panel( $wp_customize ) {
        // Check if the panel already exists
        if ( ! $wp_customize->get_panel( self::PARENT_PANEL_ID ) ) {
            $wp_customize->add_panel(
                self::PARENT_PANEL_ID,
                array(
                    'title'       => __( 'Holler Elementor', 'holler-elementor' ),
                    'description' => __( 'Customize Holler Elementor plugin settings.', 'holler-elementor' ),
                    'priority'    => 120,
                )
            );
        }
    }
}
