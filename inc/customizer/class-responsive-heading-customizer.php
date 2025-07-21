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
	 * Heading typography variables
	 *
	 * @var array
	 */
	private $heading_vars = array(
		'heading_size_pre_header' => array(
			'name' => 'Pre-Header',
			'size_default' => '12px',
			'line_height_default' => '1.4',
			'font_weight_default' => '400',
			'css_vars' => array(
				'size' => '--holler-heading-size-pre-header',
				'line_height' => '--holler-heading-line-height-pre-header',
				'font_weight' => '--holler-heading-font-weight-pre-header',
			),
		),
		'heading_size_text_small' => array(
			'name' => 'Text Small',
			'size_default' => '13px',
			'line_height_default' => '1.5',
			'font_weight_default' => '400',
			'css_vars' => array(
				'size' => '--holler-heading-size-text-small',
				'line_height' => '--holler-heading-line-height-text-small',
				'font_weight' => '--holler-heading-font-weight-text-small',
			),
		),
		'heading_size_x_small' => array(
			'name' => 'X-Small Heading',
			'size_default' => '14px',
			'line_height_default' => '1.4',
			'font_weight_default' => '600',
			'css_vars' => array(
				'size' => '--holler-heading-size-x-small',
				'line_height' => '--holler-heading-line-height-x-small',
				'font_weight' => '--holler-heading-font-weight-x-small',
			),
		),
		'heading_size_small' => array(
			'name' => 'Small Heading',
			'size_default' => '16px',
			'line_height_default' => '1.4',
			'font_weight_default' => '600',
			'css_vars' => array(
				'size' => '--holler-heading-size-small',
				'line_height' => '--holler-heading-line-height-small',
				'font_weight' => '--holler-heading-font-weight-small',
			),
		),
		'heading_size_text_big' => array(
			'name' => 'Text Big',
			'size_default' => '18px',
			'line_height_default' => '1.5',
			'font_weight_default' => '400',
			'css_vars' => array(
				'size' => '--holler-heading-size-text-big',
				'line_height' => '--holler-heading-line-height-text-big',
				'font_weight' => '--holler-heading-font-weight-text-big',
			),
		),
		'heading_size_medium' => array(
			'name' => 'Medium Heading',
			'size_default' => '24px',
			'line_height_default' => '1.3',
			'font_weight_default' => '600',
			'css_vars' => array(
				'size' => '--holler-heading-size-medium',
				'line_height' => '--holler-heading-line-height-medium',
				'font_weight' => '--holler-heading-font-weight-medium',
			),
		),
		'heading_size_large' => array(
			'name' => 'Large Heading',
			'size_default' => '32px',
			'line_height_default' => '1.2',
			'font_weight_default' => '700',
			'css_vars' => array(
				'size' => '--holler-heading-size-large',
				'line_height' => '--holler-heading-line-height-large',
				'font_weight' => '--holler-heading-font-weight-large',
			),
		),
		'heading_size_xl' => array(
			'name' => 'XL Heading',
			'size_default' => '48px',
			'line_height_default' => '1.1',
			'font_weight_default' => '700',
			'css_vars' => array(
				'size' => '--holler-heading-size-xl',
				'line_height' => '--holler-heading-line-height-xl',
				'font_weight' => '--holler-heading-font-weight-xl',
			),
		),
		'heading_size_xxl' => array(
			'name' => 'XXL Heading',
			'size_default' => '64px',
			'line_height_default' => '1.1',
			'font_weight_default' => '700',
			'css_vars' => array(
				'size' => '--holler-heading-size-xxl',
				'line_height' => '--holler-heading-line-height-xxl',
				'font_weight' => '--holler-heading-font-weight-xxl',
			),
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
			$section_title = 'Typography Sizes: ' . $device_data['label'];
			$section_description = '';
			
			if (isset($device_data['media'])) {
				$section_description = sprintf(
					__('Typography size settings for %s devices. %s', 'holler-elementor'),
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
			
			// Add controls for each heading typography variable within this device section
			foreach ( $this->heading_vars as $var_id => $var_data ) {
				// Get base defaults
				$size_default = $var_data['size_default'];
				$line_height_default = $var_data['line_height_default'];
				$font_weight_default = $var_data['font_weight_default'];
				
				// Set device-specific defaults for size
				if ( $device_id === 'tablet' ) {
					if ( $var_id === 'heading_size_xxl' ) {
						$size_default = '54px';
					} else if ( $var_id === 'heading_size_xl' ) {
						$size_default = '40px';
					} else if ( $var_id === 'heading_size_text_big' ) {
						$size_default = '17px';
					}
				} else if ( $device_id === 'mobile' ) {
					if ( $var_id === 'heading_size_xxl' ) {
						$size_default = '42px';
					} else if ( $var_id === 'heading_size_xl' ) {
						$size_default = '32px';
					} else if ( $var_id === 'heading_size_large' ) {
						$size_default = '24px';
					} else if ( $var_id === 'heading_size_medium' ) {
						$size_default = '20px';
					} else if ( $var_id === 'heading_size_text_big' ) {
						$size_default = '16px';
					} else if ( $var_id === 'heading_size_text_small' ) {
						$size_default = '12px';
					} else if ( $var_id === 'heading_size_pre_header' ) {
						$size_default = '11px';
					}
				}

				// Create settings and controls for size, line height, and font weight
				$typography_properties = array(
					'size' => array(
						'label' => $var_data['name'] . ' Size',
						'default' => $size_default,
						'type' => 'text',
						'description' => sprintf( __( 'Font size for %s (e.g. %s)', 'holler-elementor' ), $var_data['name'], $size_default ),
					),
					'line_height' => array(
						'label' => $var_data['name'] . ' Line Height',
						'default' => $line_height_default,
						'type' => 'text',
						'description' => sprintf( __( 'Line height for %s (e.g. %s)', 'holler-elementor' ), $var_data['name'], $line_height_default ),
					),
					'font_weight' => array(
						'label' => $var_data['name'] . ' Font Weight',
						'default' => $font_weight_default,
						'type' => 'select',
						'description' => sprintf( __( 'Font weight for %s', 'holler-elementor' ), $var_data['name'] ),
						'choices' => array(
							'100' => __( '100 - Thin', 'holler-elementor' ),
							'200' => __( '200 - Extra Light', 'holler-elementor' ),
							'300' => __( '300 - Light', 'holler-elementor' ),
							'400' => __( '400 - Normal', 'holler-elementor' ),
							'500' => __( '500 - Medium', 'holler-elementor' ),
							'600' => __( '600 - Semi Bold', 'holler-elementor' ),
							'700' => __( '700 - Bold', 'holler-elementor' ),
							'800' => __( '800 - Extra Bold', 'holler-elementor' ),
							'900' => __( '900 - Black', 'holler-elementor' ),
						),
					),
				);
				
				foreach ( $typography_properties as $property => $property_data ) {
					$setting_id = 'holler_' . $var_id . '_' . $property . $device_data['suffix'];
					
					$wp_customize->add_setting(
						$setting_id,
						array(
							'default'           => $property_data['default'],
							'sanitize_callback' => $property === 'font_weight' ? 'absint' : 'sanitize_text_field',
							'transport'         => 'refresh',
						)
					);

					if ( $property === 'font_weight' ) {
						$wp_customize->add_control(
							$setting_id,
							array(
								'label'       => $property_data['label'],
								'description' => $property_data['description'],
								'section'     => $section_id,
								'type'        => 'select',
								'choices'     => $property_data['choices'],
							)
						);
					} else {
						$wp_customize->add_control(
							$setting_id,
							array(
								'label'       => $property_data['label'],
								'description' => $property_data['description'],
								'section'     => $section_id,
								'type'        => $property_data['type'],
							)
						);
					}
				}
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
			
			// Add heading typography variables for this device
			foreach ( $this->heading_vars as $var_id => $var_data ) {
				// Get defaults for this device
				$size_default = $var_data['size_default'];
				$line_height_default = $var_data['line_height_default'];
				$font_weight_default = $var_data['font_weight_default'];
				
				// Adjust defaults for device if needed
				if ( $device_id === 'tablet' ) {
					if ( $var_id === 'heading_size_xxl' ) {
						$size_default = '54px';
					} else if ( $var_id === 'heading_size_xl' ) {
						$size_default = '40px';
					} else if ( $var_id === 'heading_size_text_big' ) {
						$size_default = '17px';
					}
				} else if ( $device_id === 'mobile' ) {
					if ( $var_id === 'heading_size_xxl' ) {
						$size_default = '42px';
					} else if ( $var_id === 'heading_size_xl' ) {
						$size_default = '32px';
					} else if ( $var_id === 'heading_size_large' ) {
						$size_default = '24px';
					} else if ( $var_id === 'heading_size_medium' ) {
						$size_default = '20px';
					} else if ( $var_id === 'heading_size_text_big' ) {
						$size_default = '16px';
					} else if ( $var_id === 'heading_size_text_small' ) {
						$size_default = '12px';
					} else if ( $var_id === 'heading_size_pre_header' ) {
						$size_default = '11px';
					}
				}
				
				// Get values for each typography property
				$typography_properties = array(
					'size' => array(
						'setting_id' => 'holler_' . $var_id . '_size' . $device_data['suffix'],
						'default' => $size_default,
						'css_var' => $var_data['css_vars']['size'],
					),
					'line_height' => array(
						'setting_id' => 'holler_' . $var_id . '_line_height' . $device_data['suffix'],
						'default' => $line_height_default,
						'css_var' => $var_data['css_vars']['line_height'],
					),
					'font_weight' => array(
						'setting_id' => 'holler_' . $var_id . '_font_weight' . $device_data['suffix'],
						'default' => $font_weight_default,
						'css_var' => $var_data['css_vars']['font_weight'],
					),
				);
				
				foreach ( $typography_properties as $property => $property_data ) {
					$value = get_theme_mod( $property_data['setting_id'], $property_data['default'] );
					
					// Only add if it has a value
					if (!empty($value)) {
						$css_device .= "\n\t\t" . $property_data['css_var'] . ': ' . esc_attr( $value ) . ';';
						$has_changes = true;
					}
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
