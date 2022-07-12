<?php  

defined( 'ABSPATH' ) || die( "Can't access directly" );

function _basic_card_template( $settings) { 		
	$product_url  = $settings['product_url']['url'];
  $imgSrc = $settings['image']['url'];
  $team_name = $settings['widget_name'];
  $team_title = $settings['widget_title'];
  $content =  $settings['widget_text'];
  $team_email =  $settings['widget_email'];
  $team_phone =  $settings['widget_phone'];
  $style = $settings['card_type'];

/*
 
        <a href="'. $url .'" '. $target . $nofollow .'class="btn btn-ghost btn-ghost-white">' . $buttontext . '</a>
 
*/ 


	 ob_start();
?>
<article class="card card-model <?php echo  $style; ?>">
	<a href="<?php echo $product_url; ?>"> 	
  	<figure class="img-wrap">
	     <img src="<?php echo $imgSrc; ?>" />
		</figure>
    <header class="card-header">
	    <h2 class="model-name"><?php echo $team_name;?></h2>
	    <h3 class="model-info"><?php echo $team_title; ?></h3>
	  </header>
	 </a>
</article>
<?php
  return ob_get_clean();
}


 