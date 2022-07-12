<?php  

defined( 'ABSPATH' ) || die( "Can't access directly" );

function _product_title_template($product) {
	 ob_start();
?>
  <h5 style="margin-bottom: 0px;">Unit ID # <?php echo $product->EquipmentId; ?></h5>
  <h1 class="product-name"><?php echo $product->ShortName ?></h1>
  <h2 class="model-description"><?php echo $product->ModelDescription ?> <?php echo $product->ModelName ?></h2>

<?php  
  return ob_get_clean();
}

