<?php
/**
 * Holler Responsive Heading Size Customizer
 *
 * @package Holler_Elementor
 */

/**
 * Class Holler_Responsive_Heading_Customizer
 * 
 * Adds responsive heading size customizer options with device-specific settings.
 */
class Holler_Responsive_Heading_Customizer extends Holler_Customizer_Base {
	
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
	 * Heading size variables
	 *
	 * @var array
	 */
	private $heading_vars = array(
		'heading_size_x_small' => array(
			'name' => 'X-Small Heading Size',
			'default' => '14px',
			'css_var' => '--holler-heading-size-x-small',
		),
		'heading_size_small' => array(
			'name' => 'Small Heading Size',
			'default' => '16px',
			'css_var' => '--holler-heading-size-small',
		),
		'heading_size_medium' => array(
			'name' => 'Medium Heading Size',
			'default' => '24px',
			'css_var' => '--holler-heading-size-medium',
		),
		'heading_size_large' => array(
			'name' => 'Large Heading Size',
			'default' => '32px',
			'css_var' => '--holler-heading-size-large',
		),
		'heading_size_xl' => array(
			'name' => 'XL Heading Size',
			'default' => '48px',
			'css_var' => '--holler-heading-size-xl',
		),
		'heading_size_xxl' => array(
			'name' => 'XXL Heading Size',
			'default' => '64px',
			'css_var' => '--holler-heading-size-xxl',
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
			$section_id = 'holler_heading_size_' . $device_id . '_section';
			$section_title = 'Heading: ' . $device_data['label'];
			$section_description = '';
			
			if (isset($device_data['media'])) {
				$section_description = sprintf(
					__('Heading size settings for %s devices. %s', 'holler-elementor'),
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
			
			// Add controls for each heading size variable within this device section
			foreach ( $this->heading_vars as $var_id => $var_data ) {
				$setting_id = 'holler_' . $var_id . $device_data['suffix'];
				$default_value = $var_data['default'];
				
				// Set specific defaults based on device and variable if needed
				if ( $device_id === 'tablet' ) {
					// Adjust tablet defaults if needed
					if ( $var_id === 'heading_size_xxl' ) {
						$default_value = '54px'; // Example: slightly smaller XXL for tablets
					} else if ( $var_id === 'heading_size_xl' ) {
						$default_value = '40px';
					}
				} else if ( $device_id === 'mobile' ) {
					// Adjust mobile defaults - generally smaller
					if ( $var_id === 'heading_size_xxl' ) {
						$default_value = '42px';
					} else if ( $var_id === 'heading_size_xl' ) {
						$default_value = '32px';
					} else if ( $var_id === 'heading_size_large' ) {
						$default_value = '24px';
					} else if ( $var_id === 'heading_size_medium' ) {
						$default_value = '20px';
					}
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
		$css = "/* Holler Responsive Heading Sizes - Using Theme Breakpoints: Mobile: {$mobile_breakpoint}px, Tablet: {$tablet_breakpoint}px */\n";
		
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
			
			// Add heading size variables for this device
			foreach ( $this->heading_vars as $var_id => $var_data ) {
				$setting_id = 'holler_' . $var_id . $device_data['suffix'];
				$value = get_theme_mod( $setting_id, $var_data['default'] );
				
				// Only add if it has a value
				if (!empty($value)) {
					$css_device .= "\n\t\t" . $var_data['css_var'] . ': ' . esc_attr( $value ) . ';';
					$has_changes = true;
				}
			}
			
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
}

// Initialize the class
new Holler_Responsive_Heading_Customizer();
