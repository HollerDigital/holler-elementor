<?php
/**
 * Spacing Customizer Settings
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
 * Holler_Spacing_Customizer class
 *
 * Adds custom spacing controls to WordPress Customizer
 *
 * @since 1.0.0
 */
class Holler_Spacing_Customizer {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'customize_register', array( $this, 'register_customizer_settings' ) );
		add_action( 'wp_head', array( $this, 'output_customizer_css' ), 999 );
	}

	/**
	 * Helper method to create spacing controls
	 *
	 * @param WP_Customize_Manager $wp_customize The customizer manager.
	 * @param string $setting_id The setting ID.
	 * @param string $label The control label.
	 * @param string $description The control description.
	 * @param string $section The section ID.
	 * @param array $input_attrs Optional. The input attributes.
	 */
	private function create_spacing_control( $wp_customize, $setting_id, $label, $description, $section, $input_attrs = [] ) {
		// Add a unit setting for this spacing value
		$unit_setting_id = $setting_id . '_unit';
		
		$wp_customize->add_setting(
			$unit_setting_id,
			array(
				'default'           => 'px', // Default unit is px
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'refresh',
			)
		);
		
		// Get the appropriate range control class to use
		$range_class = 'Holler_Elementor_Range_Control';
		
		// Use our range control for all spacing settings
		$wp_customize->add_control( new $range_class( $wp_customize, $setting_id, [
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
		// Add Spacing section
		$wp_customize->add_section(
			'holler_spacing_section',
			array(
				'title'       => __( 'Holler Spacing Settings', 'holler-elementor' ),
				'description' => __( 'Customize the spacing variables used throughout the site.', 'holler-elementor' ),
				'priority'    => 120,
			)
		);

		// Default settings for all spacing values
		$default_settings = [
			'transport' => 'refresh',
			'sanitize_callback' => 'absint', // Ensure numeric values
		];

		// No Padding Setting
		$wp_customize->add_setting(
			'holler_spacing_no_padding',
			array_merge( [
				'default' => '0',
			], $default_settings )
		);

		// Use the same range control for all spacing settings for consistency
		$this->create_spacing_control(
			$wp_customize,
			'holler_spacing_no_padding',
			__( 'No Padding', 'holler-elementor' ),
			__( 'Value for --no-padding variable (e.g. 0px)', 'holler-elementor' ),
			'holler_spacing_section',
			[
				'min' => 0,
				'max' => 1000,
				'step' => 1
			]
		);

		// Gutter Setting
		$wp_customize->add_setting(
			'holler_spacing_gutter',
			array_merge( [
				'default' => '16',
			], $default_settings )
		);

		// Use our custom helper method to create a range control or fallback
		$this->create_spacing_control(
			$wp_customize,
			'holler_spacing_gutter',
			__( 'Gutter', 'holler-elementor' ),
			__( 'Value for --gutter variable (e.g. 16px)', 'holler-elementor' ),
			'holler_spacing_section',
			[
				'min' => 0,
				'max' => 1000,
				'step' => 1
			]
		);

		// Spacing Small Setting
		$wp_customize->add_setting(
			'holler_spacing_small',
			array_merge( [
				'default' => '24',
			], $default_settings )
		);

		$this->create_spacing_control(
			$wp_customize,
			'holler_spacing_small',
			__( 'Small Spacing', 'holler-elementor' ),
			__( 'Value for --spacing-small variable (e.g. 24px)', 'holler-elementor' ),
			'holler_spacing_section',
			[
				'min' => 0,
				'max' => 10000,
				'step' => 1
			]
		);

		// Spacing Medium Setting
		$wp_customize->add_setting(
			'holler_spacing_medium',
			array_merge( [
				'default' => '40',
			], $default_settings )
		);

		$this->create_spacing_control(
			$wp_customize,
			'holler_spacing_medium',
			__( 'Medium Spacing', 'holler-elementor' ),
			__( 'Value for --spacing-medium variable (e.g. 40px)', 'holler-elementor' ),
			'holler_spacing_section',
			[
				'min' => 0,
				'max' => 1000,
				'step' => 1
			]
		);

		// Spacing Large Setting
		$wp_customize->add_setting(
			'holler_spacing_large',
			array_merge( [
				'default' => '80',
			], $default_settings )
		);

		$this->create_spacing_control(
			$wp_customize,
			'holler_spacing_large',
			__( 'Large Spacing', 'holler-elementor' ),
			__( 'Value for --spacing-large variable (e.g. 80px)', 'holler-elementor' ),
			'holler_spacing_section',
			[
				'min' => 0,
				'max' => 1000,
				'step' => 1
			]
		);

		// Spacing XL Setting
		$wp_customize->add_setting(
			'holler_spacing_xl',
			array_merge( [
				'default' => '100',
			], $default_settings )
		);

		$this->create_spacing_control(
			$wp_customize,
			'holler_spacing_xl',
			__( 'XL Spacing', 'holler-elementor' ),
			__( 'Value for --spacing-xl variable (e.g. 100px)', 'holler-elementor' ),
			'holler_spacing_section',
			[
				'min' => 0,
				'max' => 1000,
				'step' => 1
			]
		);

		// Spacing XXL Setting
		$wp_customize->add_setting(
			'holler_spacing_xxl',
			array_merge( [
				'default' => '200',
			], $default_settings )
		);

		$this->create_spacing_control(
			$wp_customize,
			'holler_spacing_xxl',
			__( 'XXL Spacing', 'holler-elementor' ),
			__( 'Value for --spacing-xxl variable (e.g. 200px)', 'holler-elementor' ),
			'holler_spacing_section',
			[
				'min' => 0,
				'max' => 1000,
				'step' => 1
			]
		);
	}

	/**
	 * Get a spacing value with its unit
	 * 
	 * @param string $setting_id The setting ID
	 * @param string $default_value The default value
	 * @param string $default_unit The default unit
	 * @return string The value with unit
	 */
	private function get_spacing_value_with_unit( $setting_id, $default_value = '0', $default_unit = 'px' ) {
		// Get the numeric value
		$value = get_theme_mod( $setting_id, $default_value );
		
		// Get the unit from the unit setting
		$unit = get_theme_mod( $setting_id . '_unit', $default_unit );
		
		// Return combined value
		return $value . $unit;
	}
	
	/**
	 * Output customizer CSS to wp_head
	 */
	public function output_customizer_css() {
		// Get all spacing values with their respective units
		$no_padding = $this->get_spacing_value_with_unit( 'holler_spacing_no_padding', '0' );
		$gutter = $this->get_spacing_value_with_unit( 'holler_spacing_gutter', '24' );
		$spacing_small = $this->get_spacing_value_with_unit( 'holler_spacing_small', '24' );
		$spacing_medium = $this->get_spacing_value_with_unit( 'holler_spacing_medium', '40' );
		$spacing_large = $this->get_spacing_value_with_unit( 'holler_spacing_large', '80' );
		$spacing_xl = $this->get_spacing_value_with_unit( 'holler_spacing_xl', '100' );
		$spacing_xxl = $this->get_spacing_value_with_unit( 'holler_spacing_xxl', '200' );
		?>
		<style type="text/css">
			:root {
				--no-padding: <?php echo esc_attr( $no_padding ); ?>;
				--gutter: <?php echo esc_attr( $gutter ); ?>;
				--spacing-small: <?php echo esc_attr( $spacing_small ); ?>;
				--spacing-medium: <?php echo esc_attr( $spacing_medium ); ?>;
				--spacing-large: <?php echo esc_attr( $spacing_large ); ?>;
				--spacing-xl: <?php echo esc_attr( $spacing_xl ); ?>;
				--spacing-xxl: <?php echo esc_attr( $spacing_xxl ); ?>;
			}
		</style>
		<?php
	}
}

// Initialize the class
new Holler_Spacing_Customizer();
