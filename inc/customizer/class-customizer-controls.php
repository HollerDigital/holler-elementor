<?php
/**
 * Holler Customizer Controls
 *
 * @package Holler_Elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Holler Elementor Range Control
 * 
 * Simple version based on the theme's control but with a different class name
 * to avoid conflicts when both plugin and theme are active. Includes unit selection.
 */
if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'Holler_Elementor_Range_Control' ) ) {
    class Holler_Elementor_Range_Control extends WP_Customize_Control {
        /**
         * Control type
         *
         * @var string
         */
        public $type = 'holler-elementor-range';
        
        /**
         * Available units
         * 
         * @var array
         */
        private $available_units = array('px', '%', 'em', 'rem', 'vh');
        
        /**
         * Unit setting suffix
         */
        private $unit_setting_suffix = '_unit';
        
        /**
         * Enqueue control scripts
         */
        public function enqueue() {
            static $script_added = false;
            
            if (!$script_added) {
                // Use double quotes for the outer string to avoid escaping single quotes in JS
                wp_add_inline_script('customize-controls', "
                    jQuery(document).ready(function($) {
                        // Handle number input events and sync with range slider
                        $(document).on('input change', '.holler-number-input', function() {
                            var container = $(this).closest('.customize-control');
                            var numValue = $(this).val();
                            var rangeInput = container.find('.holler-range-input');
                            
                            // Sync the range slider with number input
                            rangeInput.val(numValue);
                            
                            // Trigger change on the range input to update the setting
                            rangeInput.trigger('change');
                        });
                        
                        // Handle unit selection
                        $(document).on('change', '.holler-unit-select', function() {
                            var container = $(this).closest('.customize-control');
                            var unitValue = $(this).val();
                            
                            // Update the hidden unit input which is linked to the unit setting
                            var unitInput = container.find('.holler-unit-value');
                            unitInput.val(unitValue);
                            unitInput.trigger('change'); // This will automatically update the setting
                            
                            // Ensure preview updates
                            wp.customize.previewer.refresh();
                        });
                        
                        // Initialize all controls
                        function initRangeControls() {
                            $('.customize-control-holler-elementor-range').each(function() {
                                var container = $(this);
                                var settingId = container.attr('id').replace('customize-control-', '');
                                var unitSettingId = settingId + '_unit';
                                
                                var rangeInput = container.find('.holler-range-input');
                                var numInput = container.find('.holler-number-input');
                                var unitSelect = container.find('.holler-unit-select');
                                var unitInput = container.find('.holler-unit-value');
                                
                                // Set the range and number input values
                                if (wp.customize && wp.customize(settingId)) {
                                    var value = wp.customize(settingId).get();
                                    rangeInput.val(value);
                                    numInput.val(value);
                                    
                                    // Update when value changes in the customizer
                                    wp.customize(settingId).bind('change', function(newValue) {
                                        rangeInput.val(newValue);
                                        numInput.val(newValue);
                                    });
                                }
                                
                                // Set the unit select value
                                if (wp.customize && wp.customize(unitSettingId)) {
                                    var unit = wp.customize(unitSettingId).get();
                                    unitSelect.val(unit);
                                    unitInput.val(unit);
                                    
                                    // Update when unit changes in the customizer
                                    wp.customize(unitSettingId).bind('change', function(newUnit) {
                                        unitSelect.val(newUnit);
                                        unitInput.val(newUnit);
                                    });
                                }
                                
                                // Set up sync from range to number input
                                rangeInput.on('input', function() {
                                    numInput.val($(this).val());
                                });
                            });
                        }
                        
                        // Initialize controls when customizer loads
                        wp.customize.bind('ready', function() {
                            initRangeControls();
                        });
                        
                        // Reinitialize when sections expand
                        $(document).on('click', '.accordion-section-title', function() {
                            setTimeout(initRangeControls, 300);
                        });
                    });
                ");
                
                $script_added = true;
            }
        }
        
        /**
         * Extract the numeric part from a value
         *
         * @param string $value The value to parse
         * @return float The numeric part
         */
        private function get_numeric_value($value) {
            // Default to 0 if no value
            if (empty($value)) {
                return 0;
            }
            
            // Try to extract just the numeric part
            $numeric = preg_replace('/[^0-9.]/', '', $value);
            return !empty($numeric) ? floatval($numeric) : 0;
        }
        
        /**
         * Detect the unit from a value
         *
         * @param string $value The value to check
         * @return string The detected unit or 'px' by default
         */
        private function get_unit($value) {
            foreach ($this->available_units as $unit) {
                if (strpos($value, $unit) !== false) {
                    return $unit;
                }
            }
            return 'px'; // Default unit
        }
        
        /**
         * Get the current unit value from the unit setting
         */
        private function get_current_unit() {
            $unit_setting_id = $this->id . $this->unit_setting_suffix;
            $unit = 'px'; // Default unit
            
            // Try to get the unit from the customizer
            if (isset($this->manager) && $this->manager->get_setting($unit_setting_id)) {
                $saved_unit = $this->manager->get_setting($unit_setting_id)->value();
                if (!empty($saved_unit)) {
                    $unit = $saved_unit;
                }
            }
            
            return $unit;
        }
        
        /**
         * Render control content
         */
        public function render_content() {
            // Get the numeric value
            $value = $this->value();
            $current_unit = $this->get_current_unit();
            
            ?>
            <label>
                <?php if (!empty($this->label)) : ?>
                    <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <?php endif; ?>
                <?php if (!empty($this->description)) : ?>
                    <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
                <?php endif; ?>
                <div class="holler-range-container" style="display: flex; align-items: center; justify-content: space-between;">
                    <!-- Using data-customize-setting-link makes the input directly connected to the setting -->
                    <input type="range" <?php $this->input_attrs(); ?> 
                        value="<?php echo esc_attr($value); ?>" 
                        class="holler-range-input"
                        data-customize-setting-link="<?php echo esc_attr($this->id); ?>"
                        style="flex-grow: 1; margin-right: 10px;" />
                        
                    <div style="display: flex; align-items: center;">
                        <input type="number" <?php $this->input_attrs(); ?> 
                            value="<?php echo esc_attr($value); ?>"
                            class="holler-number-input" 
                            style="width: 60px; text-align: center;" />
                            
                        <select class="holler-unit-select" style="margin-left: 5px; width: 55px;"
                               data-unit-setting="<?php echo esc_attr($this->id . $this->unit_setting_suffix); ?>">
                            <?php foreach ($this->available_units as $unit) : ?>
                                <option value="<?php echo esc_attr($unit); ?>" <?php selected($current_unit, $unit); ?>>
                                    <?php echo esc_html($unit); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php 
                        // Hidden field for the unit setting - this is what actually connects to the unit setting
                        $unit_setting_id = $this->id . $this->unit_setting_suffix;
                        ?>
                        <input type="hidden" id="<?php echo esc_attr($unit_setting_id); ?>_control" 
                               class="holler-unit-value"
                               data-customize-setting-link="<?php echo esc_attr($unit_setting_id); ?>"
                               value="<?php echo esc_attr($current_unit); ?>" />
                    </div>
                </div>
            </label>
            <?php
        }
    }
}

/**
 * Get the appropriate range control class to use
 * 
 * This function checks if the theme's Holler_Range_Control is available,
 * and if not, falls back to our Holler_Elementor_Range_Control.
 * 
 * @return string The class name to use
 */
function holler_elementor_get_range_control_class() {
    if ( class_exists( 'Holler_Range_Control' ) ) {
        return 'Holler_Range_Control';
    } else {
        return 'Holler_Elementor_Range_Control';
    }
}

