<?php
/**
 * Holler Elementor Settings Page
 *
 * @package HollerElementor
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Holler_Settings class
 *
 * Handles the settings page for Holler Elementor plugin
 *
 * @since 2.2.12
 */
class Holler_Settings {

	/**
	 * Settings page slug
	 *
	 * @var string
	 */
	private $page_slug = 'holler-elementor-settings';

	/**
	 * Settings group name
	 *
	 * @var string
	 */
	private $option_group = 'holler_elementor_settings';

	/**
	 * Settings option name
	 *
	 * @var string
	 */
	private $option_name = 'holler_elementor_options';

	/**
	 * Constructor
	 */
	public function __construct() {
		// Register hooks
		$this->register_hooks();
	}

	/**
	 * Register hooks
	 */
	private function register_hooks() {
		// Add settings page - use a later priority to ensure Elementor menu exists
		add_action( 'admin_menu', array( $this, 'add_settings_page' ), 99 );
		
		// Register settings
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		
		// Add settings link on plugins page
		add_filter( 'plugin_action_links_holler-elementor/holler-elementor.php', array( $this, 'add_settings_link' ) );
		
		// Enqueue admin scripts and styles
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
	}

	/**
	 * Add settings page to the admin menu
	 */
	public function add_settings_page() {
		// Add the settings page under the WordPress Settings menu
		add_options_page(
			esc_html__('Holler Elementor', 'holler-elementor'),   // Page title
			esc_html__('Holler Elementor', 'holler-elementor'),   // Menu title
			'manage_options',                                    // Capability
			'holler-elementor',                                  // Menu slug
			array($this, 'render_settings_page')                 // Callback function
		);
	}

	/**
	 * Register plugin settings
	 */
	public function register_settings() {
		// Register setting
		register_setting(
			$this->option_group,
			$this->option_name,
			array( $this, 'sanitize_settings' )
		);

		// Add settings section
		add_settings_section(
			'holler_elementor_general_section',
			esc_html__( 'General Settings', 'holler-elementor' ),
			array( $this, 'render_general_section' ),
			$this->page_slug
		);

		// Add Widgets section
		add_settings_section(
			'holler_elementor_widgets_section',
			esc_html__( 'Widgets', 'holler-elementor' ),
			array( $this, 'render_widgets_section' ),
			$this->page_slug
		);

		// Add settings fields for widgets
		add_settings_field(
			'enable_team_widget',
			esc_html__( 'Team Widget', 'holler-elementor' ),
			array( $this, 'render_enable_team_widget_field' ),
			$this->page_slug,
			'holler_elementor_widgets_section'
		);
		
		// Add Elementor Extensions section
		add_settings_section(
			'holler_elementor_extensions_section',
			esc_html__( 'Elementor Extensions', 'holler-elementor' ),
			array( $this, 'render_extensions_section' ),
			$this->page_slug
		);
		
		// Add settings fields for custom controls
		add_settings_field(
			'enable_heading_control',
			esc_html__( 'Heading Size Control', 'holler-elementor' ),
			array( $this, 'render_enable_heading_control_field' ),
			$this->page_slug,
			'holler_elementor_extensions_section'
		);
		
		add_settings_field(
			'enable_spacing_control',
			esc_html__( 'Container Spacing Control', 'holler-elementor' ),
			array( $this, 'render_enable_spacing_control_field' ),
			$this->page_slug,
			'holler_elementor_extensions_section'
		);
	}

	/**
	 * Sanitize settings
	 *
	 * @param array $input The input array.
	 * @return array
	 */
	public function sanitize_settings( $input ) {
		$sanitized_input = array();

		// Sanitize enable_team_widget
		$sanitized_input['enable_team_widget'] = isset( $input['enable_team_widget'] ) ? 1 : 0;
		
		// Sanitize enable_heading_control
		$sanitized_input['enable_heading_control'] = isset( $input['enable_heading_control'] ) ? 1 : 0;
		
		// Sanitize enable_spacing_control
		$sanitized_input['enable_spacing_control'] = isset( $input['enable_spacing_control'] ) ? 1 : 0;
		


		return $sanitized_input;
	}

	/**
	 * Render general section
	 */
	public function render_general_section() {
		echo '<p>' . esc_html__( 'Configure general settings for Holler Elementor.', 'holler-elementor' ) . '</p>';
	}
	
	/**
	 * Render widgets section
	 */
	public function render_widgets_section() {
		echo '<p>' . esc_html__( 'Enable or disable Holler Elementor widgets.', 'holler-elementor' ) . '</p>';
	}
	
	/**
	 * Render extensions section
	 */
	public function render_extensions_section() {
		echo '<p>' . esc_html__( 'Enable or disable Holler Elementor extensions for the Elementor editor.', 'holler-elementor' ) . '</p>';
	}
	


	/**
	 * Render enable team widget field
	 */
	public function render_enable_team_widget_field() {
		$options = get_option( $this->option_name );
		$checked = isset( $options['enable_team_widget'] ) ? $options['enable_team_widget'] : 1;
		?>
		<label for="enable_team_widget">
			<input type="checkbox" id="enable_team_widget" name="<?php echo esc_attr( $this->option_name ); ?>[enable_team_widget]" value="1" <?php checked( 1, $checked ); ?> />
			<?php esc_html_e( 'Enable Team Widget', 'holler-elementor' ); ?>
		</label>
		<p class="description"><?php esc_html_e( 'Enable or disable the Team Widget functionality.', 'holler-elementor' ); ?></p>
		<?php
	}
	
	/**
	 * Render enable heading control field
	 */
	public function render_enable_heading_control_field() {
		$options = get_option( $this->option_name );
		$checked = isset( $options['enable_heading_control'] ) ? $options['enable_heading_control'] : 1;
		?>
		<label for="enable_heading_control">
			<input type="checkbox" id="enable_heading_control" name="<?php echo esc_attr( $this->option_name ); ?>[enable_heading_control]" value="1" <?php checked( 1, $checked ); ?> />
			<?php esc_html_e( 'Enable Heading Size Control', 'holler-elementor' ); ?>
		</label>
		<p class="description"><?php esc_html_e( 'Adds a control to Elementor headings for selecting predefined heading sizes.', 'holler-elementor' ); ?></p>
		<?php
	}
	
	/**
	 * Render enable spacing control field
	 */
	public function render_enable_spacing_control_field() {
		$options = get_option( $this->option_name );
		$checked = isset( $options['enable_spacing_control'] ) ? $options['enable_spacing_control'] : 1;
		?>
		<label for="enable_spacing_control">
			<input type="checkbox" id="enable_spacing_control" name="<?php echo esc_attr( $this->option_name ); ?>[enable_spacing_control]" value="1" <?php checked( 1, $checked ); ?> />
			<?php esc_html_e( 'Enable Container Spacing Control', 'holler-elementor' ); ?>
		</label>
		<p class="description"><?php esc_html_e( 'Adds custom spacing controls to Elementor containers.', 'holler-elementor' ); ?></p>
		<?php
	}
	
	/**
	 * Enqueue admin assets
	 *
	 * @param string $hook Current admin page hook.
	 */
	public function enqueue_admin_assets( $hook ) {
		// Only enqueue on our settings page
		if ( 'settings_page_holler-elementor' !== $hook ) {
			return;
		}
		
		// Enqueue admin styles
		wp_enqueue_style(
			'holler-elementor-admin',
			plugins_url( 'assets/css/admin-settings.css', dirname( dirname( __FILE__ ) ) ),
			array(),
			HOLLER_ELEMENTOR_VERSION
		);
	}

	/**
	 * Render settings page
	 */
	public function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<div class="holler-settings-header">
				<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
				<p><?php esc_html_e( 'Configure settings for Holler Elementor widgets and features.', 'holler-elementor' ); ?></p>
				<span class="holler-version-info"><?php echo esc_html__( 'Version', 'holler-elementor' ) . ': ' . esc_html( HOLLER_ELEMENTOR_VERSION ); ?></span>
			</div>
			
			<form method="post" action="options.php" class="holler-settings-form">
				<?php
				settings_fields( $this->option_group );
				do_settings_sections( $this->page_slug );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Add settings link to plugins page
	 *
	 * @param array $links Plugin action links.
	 * @return array
	 */
	public function add_settings_link( $links ) {
		$settings_link = sprintf(
			'<a href="%s">%s</a>',
			admin_url( 'admin.php?page=holler-elementor' ),
			esc_html__( 'Settings', 'holler-elementor' )
		);
		array_unshift( $links, $settings_link );
		return $links;
	}

	/**
	 * Get option value
	 *
	 * @param string $key Option key.
	 * @param mixed  $default Default value.
	 * @return mixed
	 */
	public static function get_option( $key, $default = false ) {
		$options = get_option( 'holler_elementor_options' );
		return isset( $options[ $key ] ) ? $options[ $key ] : $default;
	}
}
