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
	 <a href="javascript:void(0)" data-modal="<?php echo $rand; ?>" id="myBtn_<?php echo $rand; ?>"> 	  
  	<figure class="img-wrap">
	     <img src="<?php echo $imgSrc; ?>"  alt='$team_name'/>
		</figure>
    <header class="team-header">
	    <h2 class="team-name"><?php echo $team_name; ?></h2>
	    <h3 class="team-title"><?php echo $team_title; ?></h3>
	  </header>
	  </a>  
</article>
<div id="myModal_<?php echo $rand; ?>" class="modal" data-id="<?php echo $rand; ?>">
	<div class="holler-team-lightbox-wrap">
	<span class="close close_<?php echo $rand; ?>">&times;</span>
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

<script>
	// Get the modal
var modal = document.getElementById("myModal_<?php echo $rand; ?>");

// Get the button that opens the modal
var btn = document.getElementById("myBtn_<?php echo $rand; ?>");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close_<?php echo $rand; ?>")[0];

// When the user clicks on the button, open the modal
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}

</script>
<?php
  return ob_get_clean();
}


