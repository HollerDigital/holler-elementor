<?php  

defined( 'ABSPATH' ) || die( "Can't access directly" );

function _holler_team_template( $settings) { 		
	$imgSrc = $settings['team_image']['url'];
  $team_name = $settings['team_name'];
  $team_title = $settings['team_title'];
  $content =  $settings['team_bio'];
  $style = 'holler-team';



	 ob_start();
?>
<article class="holler-widget <?php echo  $style; ?>">
	<!-- <a href="<?php echo $product_url; ?>"> 	 -->
  	<figure class="img-wrap">
	     <img src="<?php echo $imgSrc; ?>"  alt='$team_name'/>
		</figure>
    <header class="team-header">
	    <h2 class="team-name"><?php echo $team_name; ?></h2>
	    <h3 class="team-title"><?php echo $team_title; ?></h3>
	  </header>
	 <!-- </a> -->
</article>
<!-- <div><?php echo $content;?></div> -->
<?php
  return ob_get_clean();
}


