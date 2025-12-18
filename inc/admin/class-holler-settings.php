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
		
		// Handle license activation/deactivation
		add_action( 'admin_init', array( $this, 'handle_license_action' ) );
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
		
		// Add License section
		add_settings_section(
			'holler_elementor_license_section',
			esc_html__( 'License', 'holler-elementor' ),
			array( $this, 'render_license_section' ),
			$this->page_slug
		);
		
		// Add license key field
		add_settings_field(
			'license_key',
			esc_html__( 'License Key', 'holler-elementor' ),
			array( $this, 'render_license_key_field' ),
			$this->page_slug,
			'holler_elementor_license_section'
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
	 * Render license section
	 */
	public function render_license_section() {
		echo '<p>' . esc_html__( 'Enter your license key to receive automatic updates and support.', 'holler-elementor' ) . '</p>';
	}
	
	/**
	 * Render license key field
	 */
	public function render_license_key_field() {
		$license_key    = get_option( 'holler-elementor_license_key', '' );
		$license_status = get_option( 'holler-elementor_license' );
		$status         = isset( $license_status->license ) ? $license_status->license : 'inactive';
		$is_valid       = in_array( $status, array( 'valid', 'active' ), true );
		
		// Mask the license key if it exists and is valid
		$display_key = $license_key;
		if ( ! empty( $license_key ) && $is_valid ) {
			$display_key = substr( $license_key, 0, 4 ) . str_repeat( '*', max( 0, strlen( $license_key ) - 8 ) ) . substr( $license_key, -4 );
		}
		?>
		</table>
		</form>
		
		<form method="post" action="<?php echo esc_url( admin_url( 'options-general.php?page=holler-elementor' ) ); ?>" class="holler-license-form">
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><?php esc_html_e( 'License Key', 'holler-elementor' ); ?></th>
					<td>
						<div class="holler-license-field-wrap">
							<input 
								type="text" 
								id="holler_license_key" 
								name="holler_license_key" 
								value="<?php echo esc_attr( $display_key ); ?>" 
								class="regular-text" 
								<?php echo $is_valid ? 'readonly' : ''; ?>
								placeholder="<?php esc_attr_e( 'Enter your license key', 'holler-elementor' ); ?>"
							/>
							
							<?php if ( $is_valid ) : ?>
								<span class="holler-license-status holler-license-status--valid">
									<span class="dashicons dashicons-yes-alt"></span>
									<?php esc_html_e( 'Active', 'holler-elementor' ); ?>
								</span>
							<?php elseif ( ! empty( $license_key ) ) : ?>
								<span class="holler-license-status holler-license-status--invalid">
									<span class="dashicons dashicons-warning"></span>
									<?php esc_html_e( 'Inactive', 'holler-elementor' ); ?>
								</span>
							<?php endif; ?>
						</div>
						
						<p class="holler-license-actions">
							<?php if ( $is_valid ) : ?>
								<?php wp_nonce_field( 'holler_license_deactivate', 'holler_license_nonce' ); ?>
								<button type="submit" name="holler_license_action" value="deactivate" class="button button-secondary">
									<?php esc_html_e( 'Deactivate License', 'holler-elementor' ); ?>
								</button>
							<?php else : ?>
								<?php wp_nonce_field( 'holler_license_activate', 'holler_license_nonce' ); ?>
								<button type="submit" name="holler_license_action" value="activate" class="button button-primary">
									<?php esc_html_e( 'Activate License', 'holler-elementor' ); ?>
								</button>
							<?php endif; ?>
						</p>
						
						<?php if ( ! empty( $license_status->expires ) && 'lifetime' !== $license_status->expires && $is_valid ) : ?>
							<p class="description">
								<?php
								printf(
									esc_html__( 'License expires: %s', 'holler-elementor' ),
									date_i18n( get_option( 'date_format' ), strtotime( $license_status->expires ) )
								);
								?>
							</p>
						<?php elseif ( ! empty( $license_status->expires ) && 'lifetime' === $license_status->expires && $is_valid ) : ?>
							<p class="description">
								<?php esc_html_e( 'Lifetime license', 'holler-elementor' ); ?>
							</p>
						<?php endif; ?>
					</td>
				</tr>
		<?php
	}
	
	/**
	 * Handle license activation/deactivation
	 */
	public function handle_license_action() {
		if ( ! isset( $_POST['holler_license_action'] ) || ! isset( $_POST['holler_license_nonce'] ) ) {
			return;
		}
		
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		
		$action = sanitize_text_field( $_POST['holler_license_action'] );
		
		if ( 'activate' === $action ) {
			if ( ! wp_verify_nonce( $_POST['holler_license_nonce'], 'holler_license_activate' ) ) {
				return;
			}
			$this->activate_license();
		} elseif ( 'deactivate' === $action ) {
			if ( ! wp_verify_nonce( $_POST['holler_license_nonce'], 'holler_license_deactivate' ) ) {
				return;
			}
			$this->deactivate_license();
		}
	}
	
	/**
	 * Activate license
	 */
	private function activate_license() {
		$license_key = isset( $_POST['holler_license_key'] ) ? sanitize_text_field( $_POST['holler_license_key'] ) : '';
		
		if ( empty( $license_key ) ) {
			add_settings_error(
				'holler_license',
				'license_empty',
				esc_html__( 'Please enter a license key.', 'holler-elementor' ),
				'error'
			);
			return;
		}
		
		$api_params = array(
			'edd_action' => 'activate_license',
			'license'    => $license_key,
			'item_id'    => HOLLER_ELEMENTOR_EDD_ITEM_ID,
			'url'        => home_url(),
		);
		
		$response = wp_remote_post(
			HOLLER_ELEMENTOR_EDD_STORE_URL,
			array(
				'timeout'   => 15,
				'sslverify' => true,
				'body'      => $api_params,
			)
		);
		
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			add_settings_error(
				'holler_license',
				'license_error',
				esc_html__( 'There was an error connecting to the license server. Please try again.', 'holler-elementor' ),
				'error'
			);
			return;
		}
		
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
		
		if ( empty( $license_data->success ) || 'valid' !== $license_data->license ) {
			$message = $this->get_license_error_message( $license_data );
			add_settings_error(
				'holler_license',
				'license_invalid',
				$message,
				'error'
			);
			return;
		}
		
		// Save license key and status
		update_option( 'holler-elementor_license_key', $license_key );
		update_option( 'holler-elementor_license', $license_data );
		
		add_settings_error(
			'holler_license',
			'license_activated',
			esc_html__( 'License activated successfully!', 'holler-elementor' ),
			'success'
		);
	}
	
	/**
	 * Deactivate license
	 */
	private function deactivate_license() {
		$license_key = get_option( 'holler-elementor_license_key', '' );
		
		if ( empty( $license_key ) ) {
			return;
		}
		
		$api_params = array(
			'edd_action' => 'deactivate_license',
			'license'    => $license_key,
			'item_id'    => HOLLER_ELEMENTOR_EDD_ITEM_ID,
			'url'        => home_url(),
		);
		
		$response = wp_remote_post(
			HOLLER_ELEMENTOR_EDD_STORE_URL,
			array(
				'timeout'   => 15,
				'sslverify' => true,
				'body'      => $api_params,
			)
		);
		
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			add_settings_error(
				'holler_license',
				'license_error',
				esc_html__( 'There was an error connecting to the license server. Please try again.', 'holler-elementor' ),
				'error'
			);
			return;
		}
		
		// Clear license data
		delete_option( 'holler-elementor_license' );
		
		add_settings_error(
			'holler_license',
			'license_deactivated',
			esc_html__( 'License deactivated successfully.', 'holler-elementor' ),
			'success'
		);
	}
	
	/**
	 * Get license error message
	 *
	 * @param object $license_data License data from API.
	 * @return string
	 */
	private function get_license_error_message( $license_data ) {
		if ( empty( $license_data->error ) ) {
			return esc_html__( 'An error occurred. Please try again.', 'holler-elementor' );
		}
		
		switch ( $license_data->error ) {
			case 'expired':
				return esc_html__( 'Your license key has expired.', 'holler-elementor' );
			case 'disabled':
			case 'revoked':
				return esc_html__( 'Your license key has been disabled.', 'holler-elementor' );
			case 'missing':
				return esc_html__( 'Invalid license key.', 'holler-elementor' );
			case 'invalid':
			case 'site_inactive':
				return esc_html__( 'Your license is not active for this URL.', 'holler-elementor' );
			case 'item_name_mismatch':
				return esc_html__( 'This license key is not valid for this product.', 'holler-elementor' );
			case 'no_activations_left':
				return esc_html__( 'Your license key has reached its activation limit.', 'holler-elementor' );
			default:
				return esc_html__( 'An error occurred. Please try again.', 'holler-elementor' );
		}
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
			
			<?php settings_errors( 'holler_license' ); ?>
			
			<form method="post" action="options.php" class="holler-settings-form">
				<?php
				settings_fields( $this->option_group );
				do_settings_sections( $this->page_slug );
				submit_button();
				?>
			</table>
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
