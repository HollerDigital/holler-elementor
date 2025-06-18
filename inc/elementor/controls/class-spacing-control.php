<?php
/**
 * Spacing Control for Elementor
 *
 * @package HollerElementor
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Holler_Spacing_Control class
 *
 * Adds custom spacing control to Elementor containers
 *
 * @since 2.2.12
 */
class Holler_Spacing_Control {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Log that the spacing control is being initialized
	//error_log('Holler Spacing Control initialized');
		
		// Hook into Elementor to add custom controls - try different hooks for compatibility
		// Main hook for container element
		add_action('elementor/element/container/section_layout/after_section_end', array($this, 'add_custom_spacing_control'), 10, 2);
		
		// Alternative hook that might work with newer Elementor versions
		add_action('elementor/element/container/section_layout_additional/after_section_end', array($this, 'add_custom_spacing_control'), 10, 2);
		
		// Fallback hook for all elements
		add_action('elementor/element/after_section_end', array($this, 'maybe_add_spacing_control'), 10, 3);
		
		// Add a debug action to check if Elementor is properly loading our control
		add_action('elementor/editor/before_enqueue_scripts', array($this, 'debug_control_registration'));
		
		// We no longer need this hook as we're using prefix_class
		// add_action('elementor/frontend/container/before_render', array($this, 'modify_container_classes'));
	}

	/**
	 * Add custom spacing control to Elementor container
	 *
	 * @param \Elementor\Element_Base $element The element.
	 * @param array                   $args    The arguments.
	 */
	public function add_custom_spacing_control($element, $args) {
		// Use a more specific section ID to avoid conflicts
		$element->start_controls_section(
			'holler_container_spacing_section',
			[
				'label' => esc_html__('Holler Container Spacing', 'holler-elementor'),
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
					'--no-padding' => esc_html__('No Padding', 'textdomain'),
					'--default-padding' => esc_html__('Default', 'textdomain'),
					'--small-padding' => esc_html__('Small Padding', 'textdomain'),
					'--medium-padding' => esc_html__('Medium Padding', 'textdomain'),
					'--large-padding' => esc_html__('Large Padding', 'textdomain'),
					'--xl-padding' => esc_html__('XL Padding', 'textdomain'),
					'--xxl-padding' => esc_html__('XXL Hero Padding', 'textdomain'),
					'' => esc_html__('Custom Padding', 'textdomain'),
				],
				'selectors' => [
					'{{WRAPPER}}.elementor-element' => ' --padding-block-start: var({{VALUE}}-block-start);  --padding-inline-end: var({{VALUE}}-inline-end);  --padding-block-end: var({{VALUE}}-block-end); --padding-inline-start: var({{VALUE}}-inline-start);',
				],
				'prefix_class' => 'holler-spacing-',
			]
		);
		
		$element->end_controls_section();
	}

	/**
	 * Modify container classes based on settings
	 *
	 * @param \Elementor\Element_Base $element The element.
	 */
	/**
	 * This method is no longer needed as we're using prefix_class
	 * which automatically adds the class to the element
	 */
	public function modify_container_classes($element) {
		// This method is kept for backward compatibility but is no longer used
		return;
	}
	
	/**
	 * Fallback method to add spacing control to container elements
	 * This is a more general hook that works with any element
	 *
	 * @param \Elementor\Element_Base $element The element.
	 * @param string                  $section_id The section ID.
	 * @param array                   $args The arguments.
	 */
	public function maybe_add_spacing_control($element, $section_id, $args) {
		// Only add to container elements
		if ($element->get_name() === 'container' && $section_id === 'section_layout') {
			error_log('Holler Spacing Control: Adding to container via fallback method');
			$this->add_custom_spacing_control($element, $args);
		}
	}
 

	/**
	 * Debug function to check if the control is being registered properly
	 */
	public function debug_control_registration() {
		error_log('Holler Spacing Control: debug_control_registration called');
		
		// Check if the container element exists in Elementor
		if (class_exists('\Elementor\Plugin')) {
			$elements = \Elementor\Plugin::$instance->elements_manager->get_element_types();
			if (isset($elements['container'])) {
				error_log('Holler Spacing Control: Container element exists in Elementor');
			} else {
				error_log('Holler Spacing Control: Container element does NOT exist in Elementor');
			}
		}
	}
}
