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
 * Holler_Responsive_Spacing_Customizer class
 *
 * Adds responsive custom spacing controls to WordPress Customizer
 *
 * @since 1.0.0
 */
class Holler_Responsive_Spacing_Customizer {

	/**
	 * Device breakpoints
	 *
	 * @var array
	 */
	private $devices = array(
		'desktop' => array(
			'label' => 'Desktop',
			'suffix' => '',
		),
		'tablet' => array(
			'label' => 'Tablet',
			'suffix' => '_tablet',
			'media' => '@media only screen and (min-width: 767px) and (max-width: 1023px)',
		),
		'mobile' => array(
			'label' => 'Mobile',
			'suffix' => '_mobile',
			'media' => '@media only screen and (max-width: 766px)',
		),
	);

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
		add_action( 'customize_register', array( $this, 'register_customizer_settings' ) );
		add_action( 'wp_head', array( $this, 'output_customizer_css' ), 999 );
	}

	/**
	 * Register customizer settings and controls
	 *
	 * @param WP_Customize_Manager $wp_customize The customizer manager.
	 */
	public function register_customizer_settings( $wp_customize ) {
		// Add Spacing panel
		$wp_customize->add_panel(
			'holler_spacing_panel',
			array(
				'title'       => __( 'Holler Spacing Settings', 'holler-elementor' ),
				'description' => __( 'Customize the responsive spacing variables used throughout the site.', 'holler-elementor' ),
				'priority'    => 120,
			)
		);

		// Create a section for each device (Desktop, Tablet, Mobile)
		foreach ( $this->devices as $device_id => $device_data ) {
			$section_id = 'holler_spacing_' . $device_id . '_section';
			$section_title = $device_data['label'] . ' Spacing';
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
					'panel'       => 'holler_spacing_panel',
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
		
		// Generate desktop CSS (no media query)
		$css .= ':root {';
		foreach ( $this->spacing_vars as $var_id => $var_data ) {
			$setting_id = 'holler_' . $var_id;
			$value = get_theme_mod( $setting_id, $var_data['default'] );
			$css .= "\n\t" . $var_data['css_var'] . ': ' . esc_attr( $value ) . ';';
		}
		$css .= "\n}\n\n";
		
		// Generate tablet CSS
		$css .= $this->devices['tablet']['media'] . " {\n\t:root {";
		foreach ( $this->spacing_vars as $var_id => $var_data ) {
			$setting_id = 'holler_' . $var_id . $this->devices['tablet']['suffix'];
			$value = get_theme_mod( $setting_id, $var_data['default'] );
			$css .= "\n\t\t" . $var_data['css_var'] . ': ' . esc_attr( $value ) . ';';
		}
		$css .= "\n\t}\n}\n\n";
		
		// Generate mobile CSS
		$css .= $this->devices['mobile']['media'] . " {\n\t:root {";
		foreach ( $this->spacing_vars as $var_id => $var_data ) {
			$setting_id = 'holler_' . $var_id . $this->devices['mobile']['suffix'];
			$value = get_theme_mod( $setting_id, $var_data['default'] );
			$css .= "\n\t\t" . $var_data['css_var'] . ': ' . esc_attr( $value ) . ';';
		}
		$css .= "\n\t}\n}\n";
		
		echo '<style type="text/css">' . $css . '</style>';
	}
}

// Initialize the class
new Holler_Responsive_Spacing_Customizer();
