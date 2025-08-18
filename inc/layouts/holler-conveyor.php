<?php
defined('ABSPATH') || die("Can't access directly");

/**
 * Layout renderer for Holler Conveyor Widget
 */
function _holler_conveyor_template($settings, $widget_id = '') {
    if (holler_check_memory_usage(0.8)) {
        return '<div class="holler-memory-limit-reached">Memory limit approaching. Simplified output shown.</div>';
    }

    $items = isset($settings['items']) && is_array($settings['items']) ? $settings['items'] : [];

    // Behavior settings with safe defaults
    $anim_duration = isset($settings['anim_duration']) && is_numeric($settings['anim_duration']) ? (int)$settings['anim_duration'] : 200; // ms per 10px
    $reverse_elm   = isset($settings['reverse_elm']) && $settings['reverse_elm'] === 'yes';
    $force_loop    = isset($settings['force_loop']) && $settings['force_loop'] === 'yes';
    $start_paused  = isset($settings['start_paused']) && $settings['start_paused'] === 'yes';
    $pause_hover   = isset($settings['pause_hover']) && $settings['pause_hover'] === 'yes';
    $item_gap      = isset($settings['item_gap']['size']) ? absint($settings['item_gap']['size']) : 24;
    $track_height  = isset($settings['track_height']['size']) ? absint($settings['track_height']['size']) : 0; // 0 => auto

    // Unique ID handling
    $unique_id = '';
    if (!empty($widget_id)) {
        $unique_id = $widget_id;
    } elseif (!empty($settings['_element_id'])) {
        $unique_id = $settings['_element_id'];
    } else {
        $unique_id = 'conveyor-' . substr(md5(uniqid('', true)), 0, 8);
    }

    // Label removed; reverse now only affects direction

    // Icon position class
    $icon_position = isset($settings['icon_position']) && in_array($settings['icon_position'], ['left','right'], true) ? $settings['icon_position'] : 'left';

    // Wrapper start with data attributes for JS init
    $html  = '<div class="holler-widget holler-conveyor ' . ($icon_position === 'right' ? 'icon-right' : 'icon-left') . ($reverse_elm ? ' is-reversed' : '') . '"'
           . ' id="holler-conveyor-' . esc_attr($unique_id) . '"'
           . ' data-anim-duration="' . esc_attr($anim_duration) . '"'
           . ' data-reverse-elm="' . ($reverse_elm ? '1' : '0') . '"'
           . ' data-force-loop="' . ($force_loop ? '1' : '0') . '"'
           . ' data-start-paused="' . ($start_paused ? '1' : '0') . '"'
           . ' data-pause-hover="' . ($pause_hover ? '1' : '0') . '"'
           . ' data-item-gap="' . esc_attr($item_gap) . '"'
           . ' data-track-height="' . esc_attr($track_height) . '">';

    // No label output

    // UL must be a direct child of the init element for the ticker library
    $html .= '<ul class="jcticker holler-conveyor-track">';

    if (!empty($items)) {
        foreach ($items as $item) {
            $text = isset($item['item_text']) ? wp_kses_post($item['item_text']) : '';

            // Link handling
            $link_open = '';
            $link_close = '';
            if (!empty($item['item_link']['url'])) {
                $href = esc_url($item['item_link']['url']);
                $target = !empty($item['item_link']['is_external']) ? ' target="_blank"' : '';
                $rel_parts = [];
                if (!empty($item['item_link']['nofollow'])) { $rel_parts[] = 'nofollow'; }
                // Security best practice when target=_blank
                if (!empty($item['item_link']['is_external'])) { $rel_parts[] = 'noopener'; $rel_parts[] = 'noreferrer'; }
                $rel = !empty($rel_parts) ? ' rel="' . esc_attr(implode(' ', $rel_parts)) . '"' : '';
                $link_open = '<a class="holler-conveyor-link" href="' . $href . '"' . $target . $rel . '>';
                $link_close = '</a>';
            }

            // Icon (Elementor Icons control)
            $icon_html = '';
            if (!empty($item['item_icon'])) {
                if (class_exists('Elementor\\Icons_Manager') && is_array($item['item_icon'])) {
                    ob_start();
                    \Elementor\Icons_Manager::render_icon($item['item_icon'], ['aria-hidden' => 'true', 'class' => 'holler-conveyor-icon']);
                    $icon_raw = ob_get_clean();
                    $icon_html = '<span class="holler-conveyor-icon-wrap">' . $icon_raw . '</span>';
                } else {
                    // Fallback older format string
                    if (is_string($item['item_icon'])) {
                        $icon_raw = '<i class="' . esc_attr($item['item_icon']) . ' holler-conveyor-icon" aria-hidden="true"></i>';
                        $icon_html = '<span class="holler-conveyor-icon-wrap">' . $icon_raw . '</span>';
                    }
                }
            }

            // Render depending on icon position
            if ($icon_position === 'right') {
                $item_inner = '<span class="holler-conveyor-item-text">' . $text . '</span>' . $icon_html;
            } else {
                $item_inner = $icon_html . '<span class="holler-conveyor-item-text">' . $text . '</span>';
            }

            $html .= '<li class="holler-conveyor-item">' . $link_open . $item_inner . $link_close . '</li>';
        }
    }

    $html .= '</ul>';

    $html .= '</div>';

    return $html;
}
