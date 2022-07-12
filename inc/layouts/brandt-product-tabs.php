<?php  

defined( 'ABSPATH' ) || die( "Can't access directly" );

 
function _product_tabs( $Product ) {
	 ob_start();
?>
<!--
<pre>
  <?php print_r($Product); ?>
</pre>
-->
<ul>
  <li><a href="#tabs-specs">Specifications</a></li>
  <li><a href="#tabs-desc">Description</a></li>
  <li><a href="#tabs-options">Options</a></li>
  <li><a href="#tabs-dl">Downloads</a></li>
</ul>
<div id="tabs-specs">
  <div class="table-grid">
  <?php if($Product->className == "Trucks") {
      echo _truck_table($Product);
    } else{
      echo _trailer_table($Product);
    }
  ?>
  </div>
  <div class="table-grid">
 <p><b> Location:</b> <?php echo $Product->Location; ?></p>  
 </div>
</div>
<div id="tabs-desc"><?php echo $Product->Description; ?></div> 
<div id="tabs-options"><?php echo $Product->Options; ?></div> 
<div id="tabs-dl">
  Coming Soon
</div>     

<?php
  return ob_get_clean();
}



function _truck_table($Product){
	 ob_start();
?>

 <table>
    <tr>
      <td class="heading">Status: </td>
      <td><?php if($Product->Status == 'Rental Only'){ echo '<span class="rent">'.$Product->Status.'</span>'; }else{ echo $Product->Status; }?></td>
    </tr>
    <tr>
      <td class="heading">Serial #:   </td>
      <td><?php echo $Product->SerialNumber; ?></td>
    </tr>
    <tr>
      <td class="heading">Mileage: </td>
      <td><?php echo $Product->Mileage; ?>*</td>
    </tr>
    <tr>
      <td class="heading">Hours:</td>
      <td><?php echo $Product->Hours; ?>*</td>
    </tr>
    <tr>
      <td class="heading">Engine: </td>
      <td><?php echo $Product->EngineSize; ?> </td>
    </tr>
    <tr>
      <td class="heading">Transmission: </td>
      <td><?php echo $Product->TransmissionSize; ?></td>
    </tr>
    <tr>
      <td class="heading">Axle Ratio:</td>
      <td><?php echo $Product->AxleRatio; ?></td>
    </tr>
    <tr>
      <td class="heading">Front Axle: </td>
      <td><?php echo $Product->FrAxle; ?></td>
    </tr>
    <tr>
      <td  class="heading">Rear Axle: </td>
      <td><?php echo $Product->ReAxle; ?></td>
    </tr>
 </table>
 <table>
    <tr>
      <td class="heading">Availability </td>
    <td><?php echo $Product->Availability; ?></td>
    </tr>
     <tr>
      <td class="heading">1'st Int Axle:</td>
      <td><?php echo $Product->FirstIntAxle; ?></td>
    </tr>
    <tr>
      <td class="heading">2'nd Int Axle:</td>
      <td><?php echo $Product->SecondIntAxle; ?></td>
    </tr>
    <tr>
      <td class="heading">Suspension:</td>
      <td><?php echo $Product->SuspensionType; ?></td>
    </tr>
    <tr>
      <td class="heading">Wheelbase:  </td>
      <td><?php echo $Product->WheelBase; ?></td>
      </tr>
    <tr>
      <td class="heading">Rr Tire Size:</td>
      <td> <?php echo $Product->ReTireSize; ?></td>
    </tr>
    <tr>
      <td class="heading">Rr Tire Wear(%):</td>
      <td><?php echo $Product->ReTireWear; ?>% Remaining</td>
    </tr>
    <tr>
      <td class="heading">Fr Tire Size:</td>
      <td> <?php echo $Product->FrTireSize; ?> </td>
    </tr>
    <tr>
      <td class="heading">Fr Tire Wear(%):</td>
      <td>  <?php echo $Product->FrTireWear; ?>% Remaining</td>
    </tr>
  </table>
<?php
  return ob_get_clean();
}


function _trailer_table($Product){
	 ob_start();
?>

 <table>
                      <tr>
                        <td class="heading">Status: </td>
                        <td><?php if($Product->Status == 'Rental Only'){ echo '<span class="rent">'.$Product->Status.'</span>'; }else{ echo $Product->Status; }?></td>
                      </tr>
                      <tr>
                        <td class="heading">Serial #:   </td>
                        <td><?php echo $Product->SerialNumber; ?></td>
                      </tr>
                      <tr>
                        <td class="heading">Mileage: </td>
                        <td><?php echo $Product->Mileage; ?>*</td>
                      </tr>
                       <tr>
                        <td class="heading">Front Axle: </td>
                        <td><?php echo $Product->FrAxle; ?></td>
                      </tr>
                        <tr>
                        <td class="heading">1'st Int Axle:</td>
                        <td><?php echo $Product->FirstIntAxle; ?></td>
                      </tr>
                       <tr>
                        <td  class="heading">Rear Axle: </td>
                        <td><?php echo $Product->ReAxle; ?></td>
                      </tr>
                      <tr><td class="heading">Axle Ratio:</td><td><?php echo $Product->AxleRatio; ?></td></tr>
                           <tr>
                        <td class="heading">Suspension:</td>
                        <td><?php echo $Product->SuspensionType; ?></td>
                      </tr>
                                    
                      
                     
                     
                    </table>
              <table>
                      <tr><td class="heading">Availability </td><td><?php echo $Product->Availability; ?></td></tr>
                      <tr><td class="heading">Width: </td><td><?php echo $Product->WDWidth; ?> </td></tr>
                     <tr><td class="heading">Length: </td> <td><?php echo $Product->WDLength; ?></td></tr>
                      <tr><td class="heading">Wheelbase:  </td><td><?php echo $Product->WheelBase; ?></td></tr>
                      <tr><td class="heading">Rr Tire Size:</td><td> <?php echo $Product->ReTireSize; ?></td></tr>
                      <tr><td class="heading">Rr Tire Wear(%):</td><td><?php echo $Product->ReTireWear; ?>% Remaining</td></tr>
                      <tr><td class="heading">Fr Tire Size:</td><td> <?php echo $Product->FrTireSize; ?> </td></tr>
                      <tr><td class="heading">Fr Tire Wear(%):</td><td>  <?php echo $Product->FrTireWear; ?>% Remaining</td></tr>
                    </table>

<?php
  return ob_get_clean();
}
