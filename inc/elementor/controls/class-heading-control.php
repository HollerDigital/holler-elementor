<?php
/**
 * Heading Control for Elementor
 *
 * @package HollerElementor
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Holler_Heading_Control class
 *
 * Adds custom heading size control to Elementor headings
 *
 * @since 2.2.12
 */
class Holler_Heading_Control {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Hook into Elementor to add custom controls
		add_action('elementor/element/heading/section_title/after_section_end', array($this, 'add_custom_heading_control'), 10, 2);
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
}
