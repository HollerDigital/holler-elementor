<?php  

defined( 'ABSPATH' ) || die( "Can't access directly" );

function _product_rental_table_template($product) {
	 ob_start();
?>
 <?php if($product->Status == "Rental Only"): ?>
    <table class="table-rental-pricing">
      <tr>
          <td>Daily:</td>
<!--
          <td style="text-decoration: line-through">  
            <?php 
              if($equipType == 'K'){
                echo 'N/A';
              } else{
                echo check_rental_price( $product->RentalsRateDaily, $product->Make, false); 
              }
            ?>
          </td>
-->
           <td style="color: #e53e30; font-size: 1em  ">  
            <?php 
              if($equipType == 'K'){
                echo 'N/A';
              } else{
                 $sale =  $product->RentalsRateDaily * 0.9;
                echo check_rental_price($sale,  $product->Make, false); 
              }
            ?>
          </td>
        </tr>
        <tr>
          <td>Weekly:</td>
<!--
          <td style="text-decoration: line-through">
          <?php 
              if($equipType == 'K'){
                echo 'N/A';
              } else{
                echo check_rental_price( $product->RentalsRateWeekly,  $product->Make, false); 
              }
            ?>
          </td>
-->
           <td style="color: #e53e30;font-size: 1em; ">  
          <?php 
              if($equipType == 'K'){
                echo 'N/A';
              } else{
                $sale =  $product->RentalsRateWeekly * 0.9;
                echo check_rental_price($sale,  $product->Make, false); 
              }
            ?>
          </td>
        </tr>
        <tr>
          <td>Monthly:</td>
<!--           <td style="text-decoration: line-through"><?php echo check_rental_price( $product->RentalsRateMonthly, $product->Make, false); ?></td> -->
           <td style="color: #e53e30; font-size: 1em">  <?php $sale =  $product->RentalsRateMonthly * 0.9; echo check_rental_price($sale,  $product->Make, false); ?></td>
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
          <td colspan="2">
            <?php 
              
              if($equipType == 'K'){
                if($product->RentalsRateHourly == '0'){ echo 'Inquire';} else { echo 'Inquire'; /*echo $product->RentalsRateHourly;*/ }
              } 
              else if($equipType == 'L'){
   
                if($product->RentalsRateKM == '0'){ echo 'Included';} else { echo $product->RentalsRateKM . '	&cent;';}
              }
             ?>
            </td>
            
        </tr>
      </table>
            
          <p class="rpo">
            <?php if($equipType == 'L'): ?>
              <?php if($product->EquipmentCode =='L15'):?>
                  Minimum one day rental <br />
              <?php else: ?>
                  Minimum one week rental <br />
              <?php endif; ?>
             <?php endif; ?>
            Ask about our <span class="red"> Rental Purchase Option </span></p>
<!--           <a class="btn btn-full" target="_blank" href="https://s3.amazonaws.com/cdn.camex.com/wp-content/uploads/2018/11/08195951/Rental-Credit-Application.pdf">Rental Credit Application</a> -->
                
        <?php endif;?>

<?php  
  return ob_get_clean();
}

