<?php
/**
 * Responsive Spacing Customizer Settings
 *
 * @package HollerElementor
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Holler Responsive Spacing Customizer
 *
 * @package Holler_Elementor
 */

/**
 * Class Holler_Responsive_Spacing_Customizer
 * 
 * Adds responsive spacing customizer options with device-specific settings.
 */
class Holler_Responsive_Spacing_Customizer extends Holler_Customizer_Base {

	/**
	 * Device breakpoints
	 *
	 * @var array
	 */
	private $devices = array();
	
	/**
	 * Initialize device breakpoints based on theme settings
	 */
	private function init_devices() {
		// Get breakpoints from theme settings, or use defaults
		$mobile_breakpoint = get_theme_mod('holler_mobile_breakpoint', 767);
		$tablet_breakpoint = get_theme_mod('holler_tablet_breakpoint', 1024);
		
		// Ensure we have numeric values
		$mobile_breakpoint = absint($mobile_breakpoint);
		$tablet_breakpoint = absint($tablet_breakpoint);
		
		// Calculate max values for media queries (subtract 1 to prevent overlap)
		$mobile_max = $mobile_breakpoint;
		$tablet_min = $mobile_max + 1;
		$tablet_max = $tablet_breakpoint;
		$desktop_min = $tablet_max + 1;
		
		$this->devices = array(
			'desktop' => array(
				'label' => 'Desktop',
				'suffix' => '',
				'media' => sprintf('@media only screen and (min-width: %dpx)', $desktop_min),
			),
			'tablet' => array(
				'label' => 'Tablet',
				'suffix' => '_tablet',
				'media' => sprintf('@media only screen and (min-width: %dpx) and (max-width: %dpx)', $tablet_min, $tablet_max),
			),
			'mobile' => array(
				'label' => 'Mobile',
				'suffix' => '_mobile',
				'media' => sprintf('@media only screen and (max-width: %dpx)', $mobile_max),
			),
		);
	}

	/**
	 * Spacing variables
	 *
	 * @var array
	 */
	private $spacing_vars = array(
		'no_padding' => array(
			'name' => 'No Padding',
			'default' => '0px',
			'css_var' => '--no-padding',
		),
		'default_padding' => array(
			'name' => 'Default Padding',
			'default' => '24px',
			'css_var' => '--default-padding',
			'description' => 'Default padding used for containers (all sides)',
		),
		'gutter' => array(
			'name' => 'Gutter',
			'default' => '24px',
			'css_var' => '--gutter',
		),
		'spacing_small' => array(
			'name' => 'Small Spacing',
			'default' => '24px',
			'css_var' => '--spacing-small',
		),
		'spacing_medium' => array(
			'name' => 'Medium Spacing',
			'default' => '40px',
			'css_var' => '--spacing-medium',
		),
		'spacing_large' => array(
			'name' => 'Large Spacing',
			'default' => '80px',
			'css_var' => '--spacing-large',
		),
		'spacing_xl' => array(
			'name' => 'XL Spacing',
			'default' => '100px',
			'css_var' => '--spacing-xl',
		),
		'spacing_xxl' => array(
			'name' => 'XXL Spacing',
			'default' => '200px',
			'css_var' => '--spacing-xxl',
		),
	);

	/**
	 * Constructor
	 */
	public function __construct() {
		// Initialize device breakpoints from theme settings
		$this->init_devices();
		
		add_action( 'customize_register', array( $this, 'register_customizer_settings' ) );
		add_action( 'wp_head', array( $this, 'output_customizer_css' ), 999 );
	}

	/**
	 * Register customizer settings and controls
	 *
	 * @param WP_Customize_Manager $wp_customize The customizer manager.
	 */
	public function register_customizer_settings( $wp_customize ) {
		// Create parent panel if it doesn't exist
		self::ensure_parent_panel( $wp_customize );

		// Create a section for each device (Desktop, Tablet, Mobile)
		foreach ( $this->devices as $device_id => $device_data ) {
			$section_id = 'holler_spacing_' . $device_id . '_section';
			$section_title = 'Spacing: ' . $device_data['label'];
			$section_description = '';
			
			if (isset($device_data['media'])) {
				$section_description = sprintf(
					__('Spacing settings for %s devices. %s', 'holler-elementor'),
					$device_data['label'],
					$device_data['media']
				);
			}
			
			$wp_customize->add_section(
				$section_id,
				array(
					'title'       => $section_title,
					'description' => $section_description,
					'panel'       => self::PARENT_PANEL_ID,
				)
			);
			
			// Add controls for each spacing variable within this device section
			foreach ( $this->spacing_vars as $var_id => $var_data ) {
				$setting_id = 'holler_' . $var_id . $device_data['suffix'];
				$default_value = $var_data['default'];
				
				// Set specific defaults based on device and variable if needed
				if ( $device_id === 'tablet' && $var_id === 'spacing_xxl' ) {
					$default_value = '100px'; // Example of device-specific default
				}
				
				// Additional customized defaults based on device
				if ( $device_id === 'mobile' && $var_id === 'spacing_large' ) {
					$default_value = '40px';
				} else if ( $device_id === 'mobile' && $var_id === 'spacing_xl' ) {
					$default_value = '60px';
				} else if ( $device_id === 'mobile' && $var_id === 'spacing_xxl' ) {
					$default_value = '80px';
				}

				$wp_customize->add_setting(
					$setting_id,
					array(
						'default'           => $default_value,
						'sanitize_callback' => 'sanitize_text_field',
						'transport'         => 'refresh',
					)
				);

				$wp_customize->add_control(
					$setting_id,
					array(
						'label'       => $var_data['name'],
						'description' => sprintf( 
							__( 'Value for %s (e.g. %s)', 'holler-elementor' ),
							$var_data['css_var'],
							$default_value
						),
						'section'     => $section_id,
						'type'        => 'text',
					)
				);
			}
		}
	}

	/**
	 * Output customizer CSS to wp_head
	 */
	public function output_customizer_css() {
		$css = '';
		
		// Re-initialize device breakpoints to ensure we have the latest values
		$this->init_devices();
		
		// Add inline comment to show breakpoint values from the theme settings
		$mobile_breakpoint = get_theme_mod('holler_mobile_breakpoint', 767);
		$tablet_breakpoint = get_theme_mod('holler_tablet_breakpoint', 1024);
		$css = "/* Holler Responsive Spacing - Using Theme Breakpoints: Mobile: {$mobile_breakpoint}px, Tablet: {$tablet_breakpoint}px */\n";
		
		// Generate CSS for each device
		foreach ( $this->devices as $device_id => $device_data ) {
			$css_device = '';
			$has_changes = false;
			
			// Start CSS rule
			if (isset($device_data['media']) && !empty($device_data['media'])) {
				$css_device .= $device_data['media'] . " {\n\t:root {";
			} else {
				$css_device .= ':root {';
			}
			
			// Add base spacing variables
			foreach ( $this->spacing_vars as $var_id => $var_data ) {
				$setting_id = 'holler_' . $var_id . $device_data['suffix'];
				$value = get_theme_mod( $setting_id, $var_data['default'] );
				
				// Only add if it has a value
				if (!empty($value)) {
					$css_device .= "\n\t\t" . $var_data['css_var'] . ': ' . esc_attr( $value ) . ';';
					$has_changes = true;
				}
			}
			
			// Add directional padding variables based on main variables
			$this->add_directional_padding_css($css_device, 'no_padding', $device_data['suffix']);
			$this->add_directional_padding_css($css_device, 'default_padding', $device_data['suffix']);
			$this->add_directional_padding_css($css_device, 'small', $device_data['suffix'], 'spacing_small');
			$this->add_directional_padding_css($css_device, 'medium', $device_data['suffix'], 'spacing_medium');
			$this->add_directional_padding_css($css_device, 'large', $device_data['suffix'], 'spacing_large');
			$this->add_directional_padding_css($css_device, 'xl', $device_data['suffix'], 'spacing_xl');
			$this->add_directional_padding_css($css_device, 'xxl', $device_data['suffix'], 'spacing_xxl');
			
			// Close CSS rule
			if (isset($device_data['media']) && !empty($device_data['media'])) {
				$css_device .= "\n\t}\n}\n\n";
			} else {
				$css_device .= "\n}\n\n";
			}
			
			// Add to main CSS if we have changes
			if ($has_changes) {
				$css .= $css_device;
			}
		}
		
		echo '<style type="text/css">' . $css . '</style>';
	}
	
	/**
	 * Add directional padding CSS variables
	 * 
	 * @param string &$css The CSS string to append to
	 * @param string $padding_type The padding type (no_padding, default_padding, small, etc.)
	 * @param string $suffix The device suffix
	 * @param string $var_id Optional variable ID if different from padding type
	 */
	private function add_directional_padding_css( &$css, $padding_type, $suffix, $var_id = null ) {
		// Use provided var_id or construct from padding_type
		$spacing_var_id = $var_id ?: $padding_type;
		$prefix = $padding_type;
		
		// Handle special case for default padding which uses a different naming pattern
		if ($padding_type === 'default_padding') {
			$prefix = 'default';
		}
		
		// Get the main padding value
		$setting_id = 'holler_' . $spacing_var_id . $suffix;
		$main_value = get_theme_mod( $setting_id, $this->spacing_vars[$spacing_var_id]['default'] );
		
		// Get the gutter value for sides that use gutter
		$gutter_id = 'holler_gutter' . $suffix;
		$gutter_value = get_theme_mod( $gutter_id, $this->spacing_vars['gutter']['default'] );
		
		// Only proceed if we have values
		if (!empty($main_value)) {
			// For small, medium, large, xl, xxl paddings, use gutter for inline padding
			if ($padding_type !== 'no_padding') {
				$css .= "\n\t\t--{$prefix}-padding-block-start: {$main_value};";
				$css .= "\n\t\t--{$prefix}-padding-inline-end: {$gutter_value};";
				$css .= "\n\t\t--{$prefix}-padding-block-end: {$main_value};";
				$css .= "\n\t\t--{$prefix}-padding-inline-start: {$gutter_value};";
			} else {
				// For no-padding and default-padding, use the same value for all sides
				$css .= "\n\t\t--{$prefix}-padding-block-start: {$main_value};";
				$css .= "\n\t\t--{$prefix}-padding-inline-end: {$main_value};";
				$css .= "\n\t\t--{$prefix}-padding-block-end: {$main_value};";
				$css .= "\n\t\t--{$prefix}-padding-inline-start: {$main_value};";
			}
		}
	}
}

// Initialize the class
new Holler_Responsive_Spacing_Customizer();
