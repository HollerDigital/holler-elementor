<?php  

defined( 'ABSPATH' ) || die( "Can't access directly" );

function _holler_team_template( $settings) { 	
	// print_r($settings);	
	$imgSrc = $settings['team_image']['url'];
  $team_name = $settings['team_name'];
  $team_title = $settings['team_title'];
  $content =  $settings['team_bio'];
  $style = 'holler-team';
  $rand = rand(99, 999);



	 ob_start();
?>
<article class="holler-widget <?php echo  $style; ?>">
	 <a href="myModal_<?php echo $rand; ?>"> 	  
  	<figure class="img-wrap">
	     <img src="<?php echo $imgSrc; ?>"  alt='$team_name'/>
		</figure>
    <header class="team-header">
	    <h2 class="team-name"><?php echo $team_name; ?></h2>
	    <h3 class="team-title"><?php echo $team_title; ?></h3>
	  </header>
	  </a>  
</article>
<div id="myModal_<?php echo $rand; ?>" class="modal">
	<div class="holler-team-lightbox-wrap">
	<span class="close">&times;</span>
		<div class="team-lightbox-header">
		<figure class="img-wrap">
			<img src="<?php echo $imgSrc; ?>"  alt='$team_name'/>
		</figure>
		<div class="lightboox-team-header">
			<h2 class="team-name"><?php echo $team_name; ?></h2>
			<h3 class="team-title"><?php echo $team_title; ?></h3>
	</div>
		</div>
		<div class="team-lightbox-content">
		<?php echo $content;?>
		</div>
	</div>
</div>
<?php
  return ob_get_clean();
}


