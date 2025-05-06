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
		// Hook into Elementor to add custom controls
		add_action('elementor/element/container/section_layout/after_section_end', array($this, 'add_custom_spacing_control'), 10, 2);
		
		// Hook into Elementor's frontend rendering to modify container classes
		add_action('elementor/frontend/container/before_render', array($this, 'modify_container_classes'));
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
