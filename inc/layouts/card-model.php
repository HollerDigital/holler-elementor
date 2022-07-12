<?php  

defined( 'ABSPATH' ) || die( "Can't access directly" );

function _model_template( $settings) { 		
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
    	<header class="card-header">
	          <h2 class="model-name"><?php echo $team_name;?></h2>
	          <h3 class="model-info"><?php echo $team_title; ?></h3>
	        </header>
    	<figure class="img-wrap">
  	     <img src="<?php echo $imgSrc; ?>" />
  		</figure>
  		<div class="card-content">
	        <main class="product-detail">
		        <?php
			        if ( $settings['list'] ) {
						echo '<dl>';
						foreach (  $settings['list'] as $item ) {
							echo '<dt class="elementor-repeater-item-' . $item['_id'] . '">' . $item['list_title'] . '</dt>';
							echo '<dd>' . $item['list_value'] . '</dd>';
						}
						echo '</dl>';
					}
		        ?>
	        </main>
	     	<footer class="card-footer"></footer>
    	</div>
    </a>
</article>
<?php
  return ob_get_clean();
}


 