<?php  

defined( 'ABSPATH' ) || die( "Can't access directly" );

// https://owlcarousel2.github.io/OwlCarousel2/demos/urlhashnav.html#one

function _product_slideshow_template($Product ) {
	$count = 0;
	ob_start();
?>

 <div id="brandt-product-slider" class="owl-carousel owl-theme">
   <?php foreach ($Product->Images as $img): ?>       
      <div class="slide"><img src="<?php echo $img['imageURL'] ?>" alt="<?php echo $img['alt'] ?>" /></div>
    <?php endforeach;?>
 </div>
     
<?php
  return ob_get_clean();
}


 