<?php
/**
 * Holler Team Settings
 *
 * @package Holler_Elementor
 * @subpackage Admin
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Holler Team Settings Class
 *
 * Handles the admin settings for team members widget
 *
 * @since 1.0.0
 */
class Holler_Team_Settings {

    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'admin_menu', [ $this, 'add_settings_page' ] );
        add_action( 'admin_init', [ $this, 'register_settings' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_color_picker' ] );
        add_action( 'wp_head', [ $this, 'output_custom_styles' ] );
    }

    /**
     * Add settings page to admin menu
     */
    public function add_settings_page() {
        add_menu_page(
            esc_html__( 'Holler Team Settings', 'holler-elementor' ),
            esc_html__( 'Holler Team', 'holler-elementor' ),
            'manage_options',
            'holler-team-settings',
            [ $this, 'holler_team_settings_html' ],
            'dashicons-admin-customizer',
            100
        );
    }

    /**
     * Register settings
     */
    public function register_settings() {
        register_setting( 'holler_team_settings_group', 'holler_team_settings' );

        add_settings_section(
            'holler_team_settings_section',
            esc_html__( 'Default Styles', 'holler-elementor' ),
            '__return_false',
            'holler-team-settings'
        );

        // Team Name Size
        add_settings_field(
            'team_name_size',
            esc_html__( 'Team Name Size', 'holler-elementor' ),
            [ $this, 'render_font_size_input' ],
            'holler-team-settings',
            'holler_team_settings_section',
            [
                'label_for' => 'team_name_size',
                'default'   => ['size' => '1.2', 'unit' => 'rem']
            ]
        );

        // Team Title Size
        add_settings_field(
            'team_title_size',
            esc_html__( 'Team Title Size', 'holler-elementor' ),
            [ $this, 'render_font_size_input' ],
            'holler-team-settings',
            'holler_team_settings_section',
            [
                'label_for' => 'team_title_size',
                'default'   => ['size' => '1', 'unit' => 'em']
            ]
        );

        // Modal Name Size
        add_settings_field(
            'modal_name_size',
            esc_html__( 'Modal Name Size', 'holler-elementor' ),
            [ $this, 'render_font_size_input' ],
            'holler-team-settings',
            'holler_team_settings_section',
            [
                'label_for' => 'modal_name_size',
                'default'   => ['size' => '1.5', 'unit' => 'em']
            ]
        );

        // Modal Title Size
        add_settings_field(
            'modal_title_size',
            esc_html__( 'Modal Title Size', 'holler-elementor' ),
            [ $this, 'render_font_size_input' ],
            'holler-team-settings',
            'holler_team_settings_section',
            [
                'label_for' => 'modal_title_size',
                'default'   => ['size' => '1.25', 'unit' => 'em']
            ]
        );

        // Modal Background Color
        add_settings_field(
            'modal_bg_color',
            esc_html__( 'Modal Background Color', 'holler-elementor' ),
            [ $this, 'render_color_input' ],
            'holler-team-settings',
            'holler_team_settings_section',
            [
                'label_for' => 'modal_bg_color',
                'default'   => 'rgba(8, 0, 92, 0.9)'
            ]
        );

        // Modal Text Color
        add_settings_field(
            'modal_text_color',
            esc_html__( 'Modal Text Color', 'holler-elementor' ),
            [ $this, 'render_color_input' ],
            'holler-team-settings',
            'holler_team_settings_section',
            [
                'label_for' => 'modal_text_color',
                'default'   => '#08005C'
            ]
        );
    }

    /**
     * Render font size input field
     *
     * @param array $args Field arguments.
     */
    public function render_font_size_input( $args ) {
        $options = get_option( 'holler_team_settings' );
        $size = isset( $options[$args['label_for']]['size'] ) ? esc_attr( $options[$args['label_for']]['size'] ) : esc_attr( $args['default']['size'] );
        $unit = isset( $options[$args['label_for']]['unit'] ) ? esc_attr( $options[$args['label_for']]['unit'] ) : esc_attr( $args['default']['unit'] );
        ?>
        <input type="number" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="holler_team_settings[<?php echo esc_attr( $args['label_for'] ); ?>][size]" value="<?php echo $size; ?>" min="0" step="0.1" style="width: 70px;">
        <select name="holler_team_settings[<?php echo esc_attr( $args['label_for'] ); ?>][unit]">
            <option value="px" <?php selected( $unit, 'px' ); ?>>px</option>
            <option value="em" <?php selected( $unit, 'em' ); ?>>em</option>
            <option value="rem" <?php selected( $unit, 'rem' ); ?>>rem</option>
            <option value="%" <?php selected( $unit, '%' ); ?>>%</option>
        </select>
        <?php
    }

    /**
     * Render color input field
     *
     * @param array $args Field arguments.
     */
    public function render_color_input( $args ) {
        $options = get_option( 'holler_team_settings' );
        ?>
        <input type="text" class="color-picker" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="holler_team_settings[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo isset( $options[$args['label_for']] ) ? esc_attr( $options[$args['label_for']] ) : esc_attr( $args['default'] ); ?>">
        <?php
    }

    /**
     * Enqueue color picker scripts and styles
     *
     * @param string $hook_suffix The current admin page.
     */
    public function enqueue_color_picker( $hook_suffix ) {
        if ( 'toplevel_page_holler-team-settings' !== $hook_suffix ) {
            return;
        }

        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'holler-team-color-picker', plugins_url( 'assets/js/admin/color-picker.js', HOLLER_ELEMENTOR_DIR ), array( 'wp-color-picker' ), HOLLER_ELEMENTOR_VERSION, true );

        // Inline script to initialize the color picker
        wp_add_inline_script( 'holler-team-color-picker', 'jQuery(document).ready(function($){$(".color-picker").wpColorPicker();});' );
    }

    /**
     * Render settings page HTML
     */
    public function holler_team_settings_html() {
        // Check user capabilities
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'holler_team_settings_group' );
                do_settings_sections( 'holler-team-settings' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Output custom styles in header
     */
    public function output_custom_styles() {
        $options = get_option( 'holler_team_settings' );
    
        // Retrieve and sanitize the values
        $team_name_size = isset( $options['team_name_size'] ) ? esc_attr( $options['team_name_size']['size'] ) . esc_attr( $options['team_name_size']['unit'] ) : '1.2rem';
        $team_name_color = isset( $options['team_name_color'] ) ? esc_attr( $options['team_name_color'] ) : '#08005C';
    
        $team_title_size = isset( $options['team_title_size'] ) ? esc_attr( $options['team_title_size']['size'] ) . esc_attr( $options['team_title_size']['unit'] ) : '1em';
        $team_title_color = isset( $options['team_title_color'] ) ? esc_attr( $options['team_title_color'] ) : '#8C4EFD';
    
        $modal_bg_color = isset( $options['modal_bg_color'] ) ? esc_attr( $options['modal_bg_color'] ) : 'rgba(8, 0, 92, 0.9)';
        $modal_name_size = isset( $options['modal_name_size'] ) ? esc_attr( $options['modal_name_size']['size'] ) . esc_attr( $options['modal_name_size']['unit'] ) : '1.5em';
        $modal_title_size = isset( $options['modal_title_size'] ) ? esc_attr( $options['modal_title_size']['size'] ) . esc_attr( $options['modal_title_size']['unit'] ) : '1.25em';
        $modal_text_color = isset( $options['modal_text_color'] ) ? esc_attr( $options['modal_text_color'] ) : '#08005C';
    
        // Output the custom CSS
        echo "<style type='text/css'>
            :root {
                --holler-team-name-size: {$team_name_size};
                --holler-team-name-color: {$team_name_color};
                --holler-team-title-size: {$team_title_size};
                --holler-team-title-color: {$team_title_color};
                --holler-team-modal-bgcolor: {$modal_bg_color};
                --holler-team-modal-name-size: {$modal_name_size};
                --holler-team-modal-title-size: {$modal_title_size};
                --holler-team-modal-color: {$modal_text_color};
            }
        </style>";
    }
}
