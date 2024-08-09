<?php  
defined('ABSPATH') || die("Can't access directly");

function _holler_team_template($settings) {
    // Get settings
    $imgSrc = esc_url($settings['team_image']['url']);
    $imgStyle = !empty($settings['team_image_style']) ? $settings['team_image_style'] : 'image-round';
    
    $team_name = esc_html($settings['team_name']);
    $team_title = esc_html($settings['team_title']);
    
    $show_bio = $settings['show_bio'] === 'yes';
	$team_url_toggle =  $settings['team_url_toggle'];
    $content = $settings['team_bio'];
    
    $style = 'holler-team';
    $rand = rand(99, 99999);
    $slug = slugify($team_name);

    $url = "javascript:void(0)";
	$class = "holler_team";
    $open_tag = "<div";
    $close_tag ="</div>";


    if( $settings['show_bio'] == "yes" ){
        $open_tag = "<a";
        $close_tag ="</a>";
        $url = "javascript:void(0)";
        $rand = rand(99, 99999);
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
    // Start output buffering
    ob_start();
    ?>
    
    <article class="holler-widget <?php echo esc_attr($style); ?>">
        <?php echo $open_tag; ?> href="<?php echo  $url; ?>" data-modal="<?php echo $slug . $rand; ?>" id="myBtn_<?php echo  $slug . $rand; ?>" class="<?php echo  $class; ?>"> 	
        
        <figure class="img-wrap">
            <img src="<?php echo esc_attr($imgSrc); ?>" alt="<?php echo esc_attr($team_name); ?>" class="<?php echo esc_attr($imgStyle); ?>" />
        </figure>
        
        <header class="team-header">
            <h2 class="team-name"><?php echo esc_html($team_name); ?></h2>
            <h3 class="team-title"><?php echo esc_html($team_title); ?></h3>
        </header>
        
        <?php  echo $close_tag; ?>
 
    </article>
    
    <?php if ($show_bio): ?>
        <div id="myModal_<?php echo esc_attr($slug . $rand); ?>" class="modal" data-id="<?php echo esc_attr($slug . $rand); ?>">
            <div class="holler-team-lightbox-wrap">
                <span class="close holler-team-close close_<?php echo esc_attr($slug . $rand); ?>" data-modal="<?php echo esc_attr($slug . $rand); ?>">&times;</span>
                <div class="team-lightbox-header">
                    <figure class="img-wrap">
                        <img src="<?php echo esc_attr($imgSrc); ?>" alt="<?php echo esc_attr($team_name); ?>" />
                    </figure>
                    <div class="lightbox-team-header">
                        <h2 class="team-name"><?php echo esc_html($team_name); ?></h2>
                        <h3 class="team-title"><?php echo esc_html($team_title); ?></h3>
                    </div>
                </div>
                <div class="team-lightbox-content modal_text_color">
                    <?php echo wp_kses_post($content); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <?php
    // Return the buffered content
    return ob_get_clean();
}
