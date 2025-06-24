<?php
/**
 * Customizer Sanitization Functions
 *
 * @package Holler_Elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Sanitize checkbox
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool
 */
function holler_elementor_sanitize_checkbox( $checked ) {
    return ( ( isset( $checked ) && true == $checked ) ? true : false );
}

/**
 * Sanitize number options
 *
 * @param string $value The value to sanitize.
 * @return int
 */
function holler_elementor_sanitize_number_absint( $value ) {
    return absint( $value );
}

/**
 * Sanitize select field
 *
 * @param string $input The input to sanitize.
 * @param object $setting The setting object.
 * @return string
 */
function holler_elementor_sanitize_select( $input, $setting ) {
    $input = sanitize_key( $input );
    $choices = $setting->manager->get_control( $setting->id )->choices;
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Sanitize URL
 *
 * @param string $url The URL to sanitize.
 * @return string
 */
function holler_elementor_sanitize_url( $url ) {
    return esc_url_raw( $url );
}

/**
 * Sanitize hex color
 *
 * @param string $color The color to sanitize.
 * @return string
 */
function holler_elementor_sanitize_hex_color( $color ) {
    if ( '' === $color ) {
        return '';
    }

    // 3 or 6 hex digits, or the empty string.
    if ( preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
        return $color;
    }

    return '';
}
