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

/**
 * Holler Elementor Extension Class
 * 
 * Adds custom controls to the Elementor admin interface
 *
 * @since 1.0.0
 */
class Holler_Elementor_Extension {
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

        // Hook into Elementor to add custom controls
        add_action('elementor/element/container/section_layout/after_section_end', array($this, 'add_custom_spacing_control'), 10, 2);
        add_action('elementor/element/heading/section_title/after_section_end', array($this, 'add_custom_heading_control'), 10, 2);

        // Hook into Elementor's frontend rendering to modify container classes
        // add_action('elementor/frontend/container/before_render', array($this, 'modify_container_classes'));
        // add_action('elementor/element/container/before_render', array($this, 'modify_container_classes'));
    }
    
    /**
     * Add custom heading control to Elementor
     *
     * @param \Elementor\Element_Base $element The element.
     * @param array                   $args    The arguments.
     */
    public function add_custom_heading_control($element, $args) {
        $element->start_controls_section(
            'custom_section',
            [
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                'label' => esc_html__('Global Typography Sizes', 'holler-elementor'),
            ]
        );
    
        $element->add_control(
            'heading_size',
            [
                'label' => esc_html__('Heading Size', 'holler-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Default', 'holler-elementor'),
                    'x-small' => esc_html__('Xtra Small', 'holler-elementor'),
                    'small' => esc_html__('Small', 'holler-elementor'),
                    'medium' => esc_html__('Medium', 'holler-elementor'),
                    'large' => esc_html__('Large', 'holler-elementor'),
                    'xl' => esc_html__('XL', 'holler-elementor'),
                    'xxl' => esc_html__('XXL', 'holler-elementor'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-heading-title' => 'font-size: var(--heading-size-{{VALUE}});',
                ]
            ]
        );
    
        $element->end_controls_section();
    }
    
    /**
     * Add custom spacing control to Elementor container
     *
     * @param \Elementor\Element_Base $element The element.
     * @param array                   $args    The arguments.
     */
    public function add_custom_spacing_control($element, $args) {
        $element->start_controls_section(
            'my_custom_section',
            [
                'label' => esc_html__('Container Spacing', 'holler-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_LAYOUT,
            ]
        );

        $element->add_control(
            'holler_container_spacing',
            [
                'label' => esc_html__('Container Spacing', 'holler-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '--default-padding',
                'options' => [
                    '' => esc_html__('Default', 'holler-elementor'),
                    '--no-padding' => esc_html__('No Padding', 'holler-elementor'),
                    '--xxl-padding' => esc_html__('XXL Hero Padding', 'holler-elementor'),
                    '--xl-padding' => esc_html__('XL Padding', 'holler-elementor'),
                    '--large-padding' => esc_html__('Large Padding', 'holler-elementor'),
                    '--medium-padding' => esc_html__('Medium Padding', 'holler-elementor'),
                    '--small-padding' => esc_html__('Small Padding', 'holler-elementor'),
                ],
                'selectors' => [
                    '{{WRAPPER}}.elementor-element' => ' --padding-block-start: var({{VALUE}}-block-start);  --padding-inline-end: var({{VALUE}}-inline-end);  --padding-block-end: var({{VALUE}}-block-end); --padding-inline-start: var({{VALUE}}-inline-start);',
                ],
            ]
        );
        
        $element->end_controls_section();
    }

    /**
     * Modify container classes based on settings
     *
     * @param \Elementor\Element_Base $element The element.
     */
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
