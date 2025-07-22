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
    
    // Social media settings
    $show_social = isset($settings['show_social']) && $settings['show_social'] === 'yes';
    $linkedin_url = '';
    $instagram_url = '';
    
    if ($show_social) {
        if (isset($settings['linkedin_url']['url']) && !empty($settings['linkedin_url']['url'])) {
            $linkedin_url = esc_url($settings['linkedin_url']['url']);
        }
        if (isset($settings['instagram_url']['url']) && !empty($settings['instagram_url']['url'])) {
            $instagram_url = esc_url($settings['instagram_url']['url']);
        }
    }
	$team_url_toggle = isset($settings['team_url_toggle']) ? $settings['team_url_toggle'] : 'no';
    
    // Sanitize bio content to prevent memory issues with long URLs or excessive HTML
    $content = '';
    if (isset($settings['team_bio'])) {
        // Remove excessive whitespace and normalize line breaks
        $clean_content = preg_replace('/\s+/', ' ', $settings['team_bio']);
        
        // Limit URL lengths to prevent memory issues
        $clean_content = preg_replace_callback('/(href|src)=(["\'])([^"\']*)(["\'])/i', function($matches) {
            // If URL is longer than 255 characters, truncate it
            if (strlen($matches[3]) > 255) {
                return $matches[1] . '=' . $matches[2] . '#long-url-removed' . $matches[4];
            }
            return $matches[0];
        }, $clean_content);
        
        // Use wp_kses to allow only specific HTML tags and attributes
        $content = wp_kses($clean_content, array(
            'a' => array('href' => array(), 'title' => array(), 'target' => array()),
            'br' => array(),
            'p' => array('style' => array()),
            'h1' => array(), 'h2' => array(), 'h3' => array(), 'h4' => array(), 'h5' => array(), 'h6' => array(),
            'ul' => array(), 'ol' => array(), 'li' => array(),
            'strong' => array(), 'em' => array(), 'b' => array(), 'i' => array(),
            'span' => array('style' => array()),
            'div' => array('class' => array(), 'style' => array()),
        ));
    }
    
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
    $class = "holler-team"; // Default class
    $open_tag = "<div";
    $close_tag ="</div>";

    if( $show_bio ){
        $open_tag = "<a";
        $close_tag ="</a>";
        $url = "javascript:void(0)";
        // Use consistent unique ID instead of random numbers
        $slug = slugify($team_name);
        // Add specific class for team members with bio modals
        $class = "holler-team holler-team-bio";
    }    
    if( $team_url_toggle == "yes" ){
        $open_tag = "<a";
        $close_tag ="</a>";
$url = $settings['team_url']['url'];
$slug = "";
$rand = "";
        $class = "holler-team holler-team-link";
}
    // Build HTML using string concatenation instead of output buffering
    $html = '';
    
    // Start article
    $html .= '<div class="holler-widget ' . esc_attr($style) . '">';
    
    // Team member container (div or link)
    $html .= $open_tag . ' href="' . $url . '" data-modal="' . $slug . '-' . $unique_id . '" ';
    $html .= 'id="myBtn_' . $slug . '-' . $unique_id . '" class="' . $class . '" data-team-modal="true">';
    
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
    
    // Social media icons (outside main link to avoid nesting)
    if ($show_social) {
        $html .= '<div class="team-social-icons">';
        
        if (!empty($linkedin_url)) {
            $html .= '<a href="' . esc_url($linkedin_url) . '" target="_blank" rel="nofollow" class="team-social-icon">';
            $html .= '<i class="fab fa-linkedin"></i>';
            $html .= '</a>';
        }
        
        if (!empty($instagram_url)) {
            $html .= '<a href="' . esc_url($instagram_url) . '" target="_blank" rel="nofollow" class="team-social-icon">';
            $html .= '<i class="fab fa-instagram"></i>';
            $html .= '</a>';
        }
        
        $html .= '</div>';
    }
    
    // Close article
    $html .= '</div>';
    
    // Add modal if bio is enabled
    if ($show_bio) {
        // Modal container with proper attributes for the global script to target
        $html .= '<div id="myModal_' . esc_attr($slug . '-' . $unique_id) . '" class="modal holler-team-modal" data-modal-id="' . esc_attr($slug . '-' . $unique_id) . '">';
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
    
    return $html;
}

// Note: Using the slugify function from helpers/functions.php
