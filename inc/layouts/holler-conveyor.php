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
    $force_loop    = isset($settings['force_loop']) && $settings['force_loop'] === 'yes'; // not used in CSS marquee but kept for compatibility
    $start_paused  = isset($settings['start_paused']) && $settings['start_paused'] === 'yes';
    $pause_hover   = isset($settings['pause_hover']) && $settings['pause_hover'] === 'yes';
    $item_gap      = isset($settings['item_gap']['size']) ? absint($settings['item_gap']['size']) : 24;
    $track_height  = isset($settings['track_height']['size']) ? absint($settings['track_height']['size']) : 0; // 0 => auto
    $show_separator = isset($settings['show_separator']) ? ($settings['show_separator'] === 'yes') : true;
    $separator_text = isset($settings['separator_text']) && $settings['separator_text'] !== '' ? wp_kses_post($settings['separator_text']) : '•';

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

    // Compute CSS marquee duration in seconds. Base: anim_duration is ms per 10px; approximate speed factor for continuous loop.
    // We will map it to a reasonable range by converting to seconds directly; users can fine-tune from the control.
    $duration_seconds = max(5, min(120, round($anim_duration / 10))); // clamp 5s..120s

    // Wrapper with CSS custom properties for the marquee
    $style_parts = [];
    $style_parts[] = '--marquee-duration: ' . esc_attr($duration_seconds) . 's';
    $style_parts[] = '--spaceBetweenItems: ' . esc_attr($item_gap) . 'px';
    $style_parts[] = '--marquee-animation-state: ' . ($start_paused ? 'paused' : 'running');
    if ($track_height > 0) {
        $style_parts[] = '--marquee-track-height: ' . esc_attr($track_height) . 'px';
    }
    $style_attr = ' style="' . implode('; ', $style_parts) . '"';

    // Wrapper start
    $html  = '<div class="holler-widget holler-conveyor holler-marquee ' . ($icon_position === 'right' ? 'icon-right' : 'icon-left') . ($reverse_elm ? ' is-reversed' : '') . '"'
           . ' id="holler-conveyor-' . esc_attr($unique_id) . '"'
           . $style_attr
           . ' data-pause-hover="' . ($pause_hover ? '1' : '0') . '">';

    // No label output

    // Build one lane of items as inline-flex content
    $build_lane = function() use ($items, $icon_position, $settings, $show_separator, $separator_text) {
        $lane = '';
        $count = count($items);
        foreach ($items as $index => $item) {
            $text = isset($item['item_text']) ? wp_kses_post($item['item_text']) : '';

            // Link handling
            $link_open = '';
            $link_close = '';
            $is_widget_linked = !empty($settings['link']['url']);
            if (!$is_widget_linked && !empty($item['item_link']['url'])) {
                $href = esc_url($item['item_link']['url']);
                $target = !empty($item['item_link']['is_external']) ? ' target="_blank"' : '';
                $rel_parts = [];
                if (!empty($item['item_link']['nofollow'])) { $rel_parts[] = 'nofollow'; }
                if (!empty($item['item_link']['is_external'])) { $rel_parts[] = 'noopener'; $rel_parts[] = 'noreferrer'; }
                $rel = !empty($rel_parts) ? ' rel="' . esc_attr(implode(' ', $rel_parts)) . '"' : '';
                $link_open = '<a class="holler-conveyor-link" href="' . $href . '"' . $target . $rel . '>';
                $link_close = '</a>';
            }

            // Icon (Elementor Icons control)
            $icon_html = '';
            if (!empty($item['item_icon'])) {
                $icon_inner_html = '';
                if (class_exists('Elementor\\Icons_Manager') && is_array($item['item_icon'])) {
                    ob_start();
                    \Elementor\Icons_Manager::render_icon($item['item_icon'], ['aria-hidden' => 'true', 'class' => 'holler-conveyor-icon']);
                    $icon_inner_html = ob_get_clean();
                } elseif (is_string($item['item_icon'])) {
                    $icon_inner_html = '<i class="' . esc_attr($item['item_icon']) . ' holler-conveyor-icon" aria-hidden="true"></i>';
                }
                if (!empty($icon_inner_html)) {
                    $icon_html = '<span class="holler-conveyor-icon-wrap">' . $icon_inner_html . '</span>';
                }
            }

            // Item content order
            if ($icon_position === 'right') {
                $item_inner = '<span class="holler-conveyor-item-text">' . $text . '</span>' . $icon_html;
            } else {
                $item_inner = $icon_html . '<span class="holler-conveyor-item-text">' . $text . '</span>';
            }

            $lane .= '<span class="holler-marquee-unit">' . $link_open . $item_inner . $link_close . '</span>';

            // Separator between items
            if ($show_separator && $index < $count - 1) {
                $lane .= '<span class="holler-marquee-sep" aria-hidden="true">' . $separator_text . '</span>';
            }
        }
        return $lane;
    };

    // Track with two lanes for seamless scroll; optionally wrap whole track in a link
    $html .= '<div class="holler-marquee-track" role="marquee" aria-live="off">';
    $is_widget_linked = !empty($settings['link']['url']);
    if ($is_widget_linked) {
        $href = esc_url($settings['link']['url']);
        $target = !empty($settings['link']['is_external']) ? ' target="_blank"' : '';
        $rel_parts = [];
        if (!empty($settings['link']['nofollow'])) { $rel_parts[] = 'nofollow'; }
        if (!empty($settings['link']['is_external'])) { $rel_parts[] = 'noopener'; $rel_parts[] = 'noreferrer'; }
        $rel = !empty($rel_parts) ? ' rel="' . esc_attr(implode(' ', $rel_parts)) . '"' : '';
        $html .= '<a class="holler-conveyor-widget-link" href="' . $href . '"' . $target . $rel . '>';
    }
    $lane_html = $build_lane();
    $html .= '<div class="holler-marquee-run" data-marquee-animation="' . ($reverse_elm ? 'left' : 'right') . '">' . $lane_html . '</div>';
    $html .= '<div class="holler-marquee-run" data-marquee-animation="' . ($reverse_elm ? 'left' : 'right') . '" aria-hidden="true">' . $lane_html . '</div>';
    if ($is_widget_linked) {
        $html .= '</a>';
    }
    $html .= '</div>';

    $html .= '</div>';

    return $html;
}
