<?php  
defined('ABSPATH') || die("Can't access directly");

function _holler_team_template($settings, $widget_id = '') {
    // Check memory usage before processing
    if (holler_check_memory_usage(0.8)) {
        // Return simplified output if memory is running low
        return '<div class="holler-memory-limit-reached">Memory limit approaching. Simplified output shown.</div>';
    }
    // Get settings with validation
    // Use the custom image size if available, otherwise fall back to the original URL
    $imgSrc = '';
    if (isset($settings['team_image']['id'])) {
        // Get the optimized image URL using the custom size
        $image_data = wp_get_attachment_image_src($settings['team_image']['id'], 'holler-team-member');
        if ($image_data && !empty($image_data[0])) {
            $imgSrc = $image_data[0];
        }
    }
    
    // Fallback to original URL if optimized version isn't available
    if (empty($imgSrc) && isset($settings['team_image']['url'])) {
        $imgSrc = esc_url($settings['team_image']['url']);
    }
    $imgStyle = !empty($settings['team_image_style']) ? $settings['team_image_style'] : 'image-round';
    
    $team_name = isset($settings['team_name']) ? esc_html($settings['team_name']) : '';
    $team_title = isset($settings['team_title']) ? esc_html($settings['team_title']) : '';
    
    $show_bio = isset($settings['show_bio']) && $settings['show_bio'] === 'yes';
	$team_url_toggle = isset($settings['team_url_toggle']) ? $settings['team_url_toggle'] : 'no';
    $content = isset($settings['team_bio']) ? $settings['team_bio'] : '';
    
    // Use Elementor widget ID or element ID if available
    $unique_id = '';
    if (!empty($widget_id)) {
        $unique_id = $widget_id;
    } elseif (!empty($settings['_element_id'])) {
        $unique_id = $settings['_element_id'];
    } else {
        // Fallback to a more efficient unique ID generation
        $unique_id = 'team-' . substr(md5(uniqid($team_name, true)), 0, 8);
    }
    
    $style = 'holler-team';
    $slug = slugify($team_name);

    $url = "javascript:void(0)";
	$class = "holler_team";
    $open_tag = "<div";
    $close_tag ="</div>";


    if( $show_bio ){
        $open_tag = "<a";
        $close_tag ="</a>";
        $url = "javascript:void(0)";
        // Use consistent unique ID instead of random numbers
        $slug = slugify($team_name);
        // $style = 'holler-team-link';
		$class = "holler_team holler_team_link";
    }    
    if( $team_url_toggle == "yes" ){
        $open_tag = "<a";
        $close_tag ="</a>";
		$url = $settings['team_url']['url'];
		$slug = "";
		$rand = "";
		// $style = 'holler-team-link';
		$class = "holler_team holler_team_link";
	}
    // Build HTML using string concatenation instead of output buffering
    $html = '';
    
    // Start article
    $html .= '<article class="holler-widget ' . esc_attr($style) . '">';
    
    // Team member container (div or link)
    $html .= $open_tag . ' href="' . $url . '" data-modal="' . $slug . '-' . $unique_id . '" ';
    $html .= 'id="myBtn_' . $slug . '-' . $unique_id . '" class="' . $class . '">';
    
    // Image with lazy loading and dimensions for better performance
    $html .= '<figure class="img-wrap">';
    $html .= '<img src="' . esc_attr($imgSrc) . '" alt="' . esc_attr($team_name) . '" class="' . esc_attr($imgStyle) . '" loading="lazy" width="800" height="800" />';
    $html .= '</figure>';
    
    // Name and title
    $html .= '<header class="team-header">';
    $html .= '<h2 class="team-name">' . esc_html($team_name) . '</h2>';
    $html .= '<h3 class="team-title">' . esc_html($team_title) . '</h3>';
    $html .= '</header>';
    
    // Close container
    $html .= $close_tag;
    
    // Close article
    $html .= '</article>';
    
    // Add modal if bio is enabled
    if ($show_bio) {
        $html .= '<div id="myModal_' . esc_attr($slug . '-' . $unique_id) . '" class="modal" data-id="' . esc_attr($slug . '-' . $unique_id) . '">';
        $html .= '<div class="holler-team-lightbox-wrap">';
        $html .= '<span class="close holler-team-close close_' . esc_attr($slug . '-' . $unique_id) . '" data-modal="' . esc_attr($slug . '-' . $unique_id) . '">&times;</span>';
        
        // Modal header
        $html .= '<div class="team-lightbox-header">';
        $html .= '<figure class="img-wrap">';
        
        // Use the same optimized image in the modal
        $html .= '<img src="' . esc_attr($imgSrc) . '" alt="' . esc_attr($team_name) . '" loading="lazy" width="800" height="800" />';
        $html .= '</figure>';
        
        // Modal name and title
        $html .= '<div class="lightbox-team-header">';
        $html .= '<h2 class="team-name">' . esc_html($team_name) . '</h2>';
        $html .= '<h3 class="team-title">' . esc_html($team_title) . '</h3>';
        $html .= '</div>';
        $html .= '</div>';
        
        // Modal content
        $html .= '<div class="team-lightbox-content modal_text_color">';
        $html .= wp_kses_post($content);
        $html .= '</div>';
        
        // Close modal
        $html .= '</div>';
        $html .= '</div>';
    }
    
    // Return the HTML string
    return $html;
}
