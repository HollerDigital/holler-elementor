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
		// Use a more specific section ID to avoid conflicts
		$element->start_controls_section(
			'holler_heading_size_section',
			[
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'label' => esc_html__('Holler Typography Sizes', 'holler-elementor'),
			]
		);
	
		$element->add_control(
			'holler_heading_size',
			[
				'label' => esc_html__('Heading Size', 'holler-elementor'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',  // Empty string for no selection by default
				'options' => [
					'' => esc_html__('Theme Default', 'holler-elementor'),
					'pre-header' => esc_html__('Pre-Header Text', 'holler-elementor'),
					'text-small' => esc_html__('Text Small', 'holler-elementor'),
					'text-big' => esc_html__('Text Big', 'holler-elementor'),
					'x-small' => esc_html__('Xtra Small Header', 'holler-elementor'),
					'small' => esc_html__('Small Header', 'holler-elementor'),
					'medium' => esc_html__('Medium Header', 'holler-elementor'),
					'large' => esc_html__('Large Header', 'holler-elementor'),
					'xl' => esc_html__('XL Header', 'holler-elementor'),
					'xxl' => esc_html__('XXL Header', 'holler-elementor'),
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-heading-title' => 'font-size: var(--holler-heading-size-{{VALUE}}, inherit); line-height: var(--holler-heading-line-height-{{VALUE}}, inherit); font-weight: var(--holler-heading-font-weight-{{VALUE}}, inherit);',
				],
				'prefix_class' => 'holler-heading-size-',
			]
		);
	
		$element->end_controls_section();
	}
}
