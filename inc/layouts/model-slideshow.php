<?php  

defined( 'ABSPATH' ) || die( "Can't access directly" );

// https://owlcarousel2.github.io/OwlCarousel2/demos/urlhashnav.html#one

function _slideshow_template( $settings ) {
	$eid = $product->EquipmentId;
	$count = 0;
	ob_start();
?>
 
	<?php if ( $settings['list'] ): ?>
	 	<div id="brandt-model-slider" class="owl-carousel owl-theme">
		 	<?php foreach (  $settings['list'] as $item ): ?>
		 		<div class="slide"><img src="<?php echo $item['image']['url']; ?>" alt="<?php echo $item['image']['id']; ?>" data-hash="<?php echo $count; ?>" /></div>
			<?php $count++; endforeach; ?>
	 	</div>
	<?php endif; ?>	
	
	
	<?php if ( $settings['list'] ): $count = 0;?>
	 	<div id="brandt-model-slider-thumbs">
		 	<?php foreach (  $settings['list'] as $item ): ?>
		 		<div class="thumb"><a href="#<?php echo $count; ?>"><img src="<?php echo $item['image']['url']; ?>" alt="<?php echo $item['image']['id']; ?>" /></a></div>
			<?php $count++; endforeach; ?>
	 	</div>
	<?php endif; ?>				     

<?php
  return ob_get_clean();
}


 