<?php  

defined( 'ABSPATH' ) || die( "Can't access directly" );

function _card_template($product, $card_type="Rental", $equip_type="Truck") {
//   print_r($product);
	 ob_start();
?>
<article class="card card-equipment <?php echo $product->Year;?> <?php echo slugify($product->Make);?> <?php echo slugify($product->SubName); ?> <?php echo $product->NewUsed; ?>">
	<a href="<?php echo $product->url; ?>">
    	<figure class="img-wrap">
  	    	<?php echo  get_prodcut_image( $product->EquipmentId, '', true, '', $product->EquipmentCode ); ?>
  		</figure>
  		<div class="card-content">
	        <header class="card-header">
	          <h2 class="product-name"><?php echo $product->ShortName;?></h2>
	          <h3 class="model-description"><?php echo $product->ModelDescription . " " .  $product->ModelName; ?></h3>
	        </header>
	        <main class="product-detail">
	          <?php if($card_type == "Rental"): ?>
	            <table class="table-rental-pricing">
			        <tr>
			          <td>Daily:</td>
			          <td>  
			            <?php 
			              if($equipType == 'K'){
			                echo 'N/A';
			              } else{
			                echo check_rental_price($product->RentalsRateDaily, $product->Make, false); 
			              }
			            ?>
			          </td>
			        </tr>
			        <tr>
			          <td>Weekly:</td>
			          <td>
			          <?php 
			              if($equipType == 'K'){
			                echo 'N/A';
			              } else{
			                echo check_rental_price($product->RentalsRateWeekly, $product->Make, false); 
			              }
			            ?>
			          </td>
			        </tr>
			        <tr>
			          <td>Monthly:</td>
			          <td><?php echo check_rental_price($product->RentalsRateMonthly, $product->Make, false); ?></td>
			        </tr>
			        <tr>
			          <td>
			            <?php 
			              if($equipType == 'K'){
			                echo 'Hours: ';
			              } else{
			                echo 'KM: '; 
			              }
			            ?>
			          </td>
			          <td>
			            <?php 
			              
			              if($equipType == 'K'){
			                if($product->RentalsRateHourly == '0'){ echo 'Inquire';} else { echo 'Inquire'; /*echo $truck[0]->RentalsRateHourly;*/ }
			              } 
			              else if($equipType == 'L'){
			   
			                if($product->RentalsRateKM == '0'){ echo 'Included';} else { echo $product->RentalsRateKM . '	&cent;';}
			              }
			             ?>
			            </td>
			        </tr>
			      </table>
			<?php elseif($equip_type == 'Trailer' ): ?>
				<strong>EQUIPMENT ID:</strong> <?php echo $product->EquipmentId ; ?><br />
		         
		        <strong>WORKING WIDTH:</strong> <?php echo $product->WDWidth; ?><br />
		        <strong>WORKING LENGTH:</strong> <?php echo $product->WDLength; ?><br />
	            <strong>AXEL SPACING:</strong> <?php echo $product->AxleRatio; ?><br />
	            <strong>LOCATION:</strong> <?php echo $product->Location; ?>
			<?php else : ?>
				<strong>EQUIPMENT ID:</strong> <?php echo $product->EquipmentId ; ?><br />
		        <strong>MILEAGE:</strong> <?php echo $product->Mileage; ?><br />
		        <strong>HOURS:</strong> <?php echo $product->Hours; ?><br />
	            <strong>ENGINE:</strong> <?php echo $product->EngineSize; ?><br />
	             <strong>LOCATION:</strong> <?php echo $product->Location; ?>
			<?php endif; ?>
	        </main>
	     	<footer class="card-footer">
	        <?php if( $card_type == "Special"):?>
		        <h6 class="product-price-old">WAS <?php echo check_price( $product->BottomPrice, $product->Make  ); ?></h6>
				<h6 class="now-only">Now Only</h6>
				<?php if( $product->Status != "Rental Only"): ?>
					<h3 class="product-price"><?php echo check_price( $product->TopPrice, $product->Make  ); ?></h3>
				<?php endif;?>
			<?php elseif($card_type != "Rental"): ?>
			 	<h4 class="product-price"><?php echo check_price( $product->TopPrice , $product->Make ); ?></h4>
			<?php endif;?>
			<button class="btn btn-ghost">View Product</button>
	      </footer>
    	</div>
    </a>
</article>
<?php
  return ob_get_clean();
}


 