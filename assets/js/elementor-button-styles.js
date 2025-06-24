/**
 * Elementor Button Styles
 * 
 * Applies custom button styles to Elementor buttons based on customizer settings
 * Migrated from theme to plugin on 2025-06-24
 */

(function($) {
    'use strict';
    
    /**
     * Setup Elementor Button Styles
     * Applies custom button styles to Elementor buttons
     */
    function setupElementorButtonStyles() {
        if (typeof hollerElementorData !== 'undefined' && hollerElementorData.elementorButtonStyle) {
            var buttonStyle = hollerElementorData.elementorButtonStyle;
            
            $('.elementor-button').each(function() {
                var $button = $(this);
                
                // Remove any existing style classes
                $button.removeClass('holler-rounded holler-pill holler-square');
                
                if (buttonStyle !== 'default') {
                    if (buttonStyle === 'custom' && typeof hollerElementorData.buttonBorderRadius !== 'undefined') {
                        $button.css('border-radius', hollerElementorData.buttonBorderRadius + 'px');
                    } else {
                        $button.addClass('holler-' + buttonStyle);
                    }
                }
            });
        }
    }
    
    /**
     * Initialize when DOM is ready
     */
    $(document).ready(function() {
        setupElementorButtonStyles();
    });
    
    /**
     * Reinitialize on Elementor frontend:init
     * This ensures our enhancements work with dynamically loaded Elementor content
     */
    $(window).on('elementor/frontend/init', function() {
        if (typeof elementorFrontend !== 'undefined') {
            elementorFrontend.hooks.addAction('frontend/element_ready/global', function() {
                setupElementorButtonStyles();
            });
        }
    });
    
})(jQuery);
