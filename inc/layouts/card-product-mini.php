<?php  

defined( 'ABSPATH' ) || die( "Can't access directly" );

function _card_mini_template($product,$card_type="Rental", $equip_type="Truck") {
	 ob_start();
?>
<article class="card card-equipment card-equipment-mini  <?php echo $product->Year;?> <?php echo slugify($product->Make);?> <?php echo slugify($product->SubName); ?> <?php echo $product->NewUsed; ?>">
	<a href="<?php echo  $product->url; ?>">
    	<figure class="img-wrap">
  	    	<?php echo  get_prodcut_image( $product->EquipmentId, '', true, '', $product->EquipmentCode ); ?>
  		</figure>
  		<div class="card-content">
	        <header class="card-header">
	          <h2 class="product-name"><?php echo $product->ShortName;?></h2>
	          <h3 class="model-description"><?php echo $product->ModelDescription . " " .  $product->ModelName; ?></h3>
	        </header>
	     	<footer class="card-footer">
	        
			 	<h4 class="product-price"><?php echo check_price( $product->TopPrice , $product->Make ); ?></h4>
			 
			<button class="btn btn-ghost">View Product</button>
	      </footer>
    	</div>
    </a>
</article>
<?php
  return ob_get_clean();
}


 