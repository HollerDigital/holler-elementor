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

// Include the custom controls
require_once plugin_dir_path( __FILE__ ) . 'class-customizer-controls.php';

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
		
		// Run migration for legacy values
		add_action( 'init', array( $this, 'migrate_legacy_values' ) );
		
		add_action( 'customize_register', array( $this, 'register_customizer_settings' ) );
		add_action( 'wp_head', array( $this, 'output_customizer_css' ), 999 );
	}
	
	/**
	 * Extract the numeric part from a value
	 *
	 * @param string $value The value to parse
	 * @return int The numeric part
	 */
	private function get_numeric_value( $value ) {
		return intval( preg_replace( '/[^0-9]/', '', $value ) );
	}
	
	/**
	 * Detect the unit from a value
	 *
	 * @param string $value The value to check
	 * @return string The detected unit or 'px' by default
	 */
	private function get_unit( $value ) {
		preg_match( '/([a-z%]+)$/i', $value, $matches );
		return ! empty( $matches[1] ) ? $matches[1] : 'px';
	}
	
	/**
	 * Sanitize unit values
	 *
	 * @param string $unit The unit to sanitize
	 * @return string The sanitized unit
	 */
	public function sanitize_unit( $unit ) {
		$allowed_units = array( 'px', '%', 'em', 'rem', 'vh', 'vw' );
		return in_array( $unit, $allowed_units, true ) ? $unit : 'px';
	}
	
	/**
	 * Migrate legacy spacing values to the new split value/unit system
	 *
	 * This method automatically converts old-style values like "20px" to separate
	 * numeric value (20) and unit (px) settings for backward compatibility.
	 */
	public function migrate_legacy_values() {
		// Check if migration has already been run
		$migration_version = get_option( 'holler_spacing_migration_version', '0' );
		$current_version = '2.3.2'; // Update this when making changes to migration logic
		
		if ( version_compare( $migration_version, $current_version, '>=' ) ) {
			return; // Migration already completed for this version
		}
		
		$migrated_count = 0;
		
		// Loop through all spacing variables and devices
		foreach ( $this->spacing_vars as $var_id => $var_data ) {
			foreach ( $this->devices as $device_id => $device_data ) {
				$setting_id = 'holler_' . $var_id . $device_data['suffix'];
				$unit_setting_id = $setting_id . '_unit';
				
				// Get the current value
				$current_value = get_theme_mod( $setting_id );
				$current_unit = get_theme_mod( $unit_setting_id );
				
				// Only migrate if we have a value but no unit setting
				if ( ! empty( $current_value ) && empty( $current_unit ) ) {
					// Check if the current value contains both number and unit (legacy format)
					if ( preg_match( '/^\d+(\.\d+)?[a-z%]+$/i', $current_value ) ) {
						// Extract numeric and unit parts
						$numeric_value = $this->get_numeric_value( $current_value );
						$unit_value = $this->get_unit( $current_value );
						
						// Update the main setting with just the numeric value
						set_theme_mod( $setting_id, $numeric_value );
						
						// Create the unit setting
						set_theme_mod( $unit_setting_id, $unit_value );
						
						$migrated_count++;
						
						// Log the migration for debugging
						if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
							error_log( sprintf(
								'Holler Spacing Migration: %s converted from "%s" to value="%s" unit="%s"',
								$setting_id,
								$current_value,
								$numeric_value,
								$unit_value
							) );
						}
					}
				}
			}
		}
		
		// Update migration version
		update_option( 'holler_spacing_migration_version', $current_version );
		
		// Log completion
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG && $migrated_count > 0 ) {
			error_log( sprintf( 'Holler Spacing Migration completed: %d values migrated', $migrated_count ) );
		}
	}

	/**
	 * Create a spacing control that uses range slider
	 * 
	 * @param WP_Customize_Manager $wp_customize The customizer instance.
	 * @param string $setting_id The setting ID.
	 * @param string $label The control label.
	 * @param string $description The control description.
	 * @param string $section The section ID.
	 * @param array $input_attrs Optional. The input attributes.
	 */
	private function create_responsive_spacing_control( $wp_customize, $setting_id, $label, $description, $section, $input_attrs = [] ) {
		// Use our range control for all spacing settings
		$wp_customize->add_control( new Holler_Elementor_Range_Control( $wp_customize, $setting_id, [
			'label' => $label,
			'description' => $description,
			'section' => $section,
			'input_attrs' => array_merge([
				'min' => 0,
				'max' => 200,
				'step' => 1,
			], $input_attrs),
		]));
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

				// Register the main setting (numeric value)
				$wp_customize->add_setting(
					$setting_id,
					array(
						'default'           => $this->get_numeric_value($default_value),
						'sanitize_callback' => 'absint',
						'transport'         => 'refresh',
					)
				);
				
				// Register the unit setting
				$unit_setting_id = $setting_id . '_unit';
				$wp_customize->add_setting(
					$unit_setting_id,
					array(
						'default'           => $this->get_unit($default_value),
						'sanitize_callback' => array($this, 'sanitize_unit'),
						'transport'         => 'refresh',
					)
				);

				// Set appropriate min/max based on the spacing variable
				$input_attrs = [];
				if (strpos($var_id, 'no_padding') !== false) {
					$input_attrs = ['min' => 0, 'max' => 10, 'step' => 1];
				} elseif (strpos($var_id, 'gutter') !== false) {
					$input_attrs = ['min' => 0, 'max' => 100, 'step' => 1];
				} elseif (strpos($var_id, 'small') !== false) {
					$input_attrs = ['min' => 0, 'max' => 100, 'step' => 1];
				} elseif (strpos($var_id, 'medium') !== false) {
					$input_attrs = ['min' => 0, 'max' => 100, 'step' => 1];
				} elseif (strpos($var_id, 'large') !== false) {
					$input_attrs = ['min' => 0, 'max' => 200, 'step' => 1];
				} elseif (strpos($var_id, 'xl') !== false) {
					$input_attrs = ['min' => 0, 'max' => 200, 'step' => 1];
				} elseif (strpos($var_id, 'xxl') !== false) {
					$input_attrs = ['min' => 0, 'max' => 4000, 'step' => 1];
				} else {
					$input_attrs = ['min' => 0, 'max' => 800, 'step' => 1];
				}

				$this->create_responsive_spacing_control(
					$wp_customize,
					$setting_id,
					$var_data['name'],
					sprintf( 
						__( 'Value for %s (e.g. %s)', 'holler-elementor' ),
						$var_data['css_var'],
						$default_value
					),
					$section_id,
					$input_attrs
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
				$unit_setting_id = $setting_id . '_unit';
				
				$value = get_theme_mod( $setting_id, $this->get_numeric_value($var_data['default']) );
				$unit = get_theme_mod( $unit_setting_id, $this->get_unit($var_data['default']) );
				
				// Only add if it has a value
				if (!empty($value)) {
					$css_device .= "\n\t\t" . $var_data['css_var'] . ': ' . esc_attr( $value . $unit ) . ';';
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
		
		// Get the main padding value and unit
		$setting_id = 'holler_' . $spacing_var_id . $suffix;
		$main_value = get_theme_mod( $setting_id, $this->get_numeric_value($this->spacing_vars[$spacing_var_id]['default']) );
		$main_unit = get_theme_mod( $setting_id . '_unit', $this->get_unit($this->spacing_vars[$spacing_var_id]['default']) );
		
		// Get the gutter value and unit for sides that use gutter
		$gutter_id = 'holler_gutter' . $suffix;
		$gutter_value = get_theme_mod( $gutter_id, $this->get_numeric_value($this->spacing_vars['gutter']['default']) );
		$gutter_unit = get_theme_mod( $gutter_id . '_unit', $this->get_unit($this->spacing_vars['gutter']['default']) );
		
		// Only proceed if we have values
		if (!empty($main_value)) {
			// For small, medium, large, xl, xxl paddings, use gutter for inline padding
			if ($padding_type !== 'no_padding') {
				$css .= "\n\t\t--{$prefix}-padding-block-start: {$main_value}{$main_unit};";
				$css .= "\n\t\t--{$prefix}-padding-inline-end: {$gutter_value}{$gutter_unit};";
				$css .= "\n\t\t--{$prefix}-padding-block-end: {$main_value}{$main_unit};";
				$css .= "\n\t\t--{$prefix}-padding-inline-start: {$gutter_value}{$gutter_unit};";
			} else {
				// For no-padding and default-padding, use the same value for all sides
				$css .= "\n\t\t--{$prefix}-padding-block-start: {$main_value}{$main_unit};";
				$css .= "\n\t\t--{$prefix}-padding-inline-end: {$main_value}{$main_unit};";
				$css .= "\n\t\t--{$prefix}-padding-block-end: {$main_value}{$main_unit};";
				$css .= "\n\t\t--{$prefix}-padding-inline-start: {$main_value}{$main_unit};";
			}
		}
	}
}

// Initialize the class
new Holler_Responsive_Spacing_Customizer();
