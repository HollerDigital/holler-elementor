<?php  

defined( 'ABSPATH' ) || die( "Can't access directly" );

function _product_flag_template($Product) {
 
  $flagName;
  $flagSlug;
  
  if(!empty($Product->DealPendingExpire) && $Product->DealPendingExpire != '1900-01-01 00:00:00'){
     $flagName = 'Deal Pending';
  }
  else if(!empty($Product->Marketing)){
    $flagName = $Product->Marketing;
  }else{
    $flagName = $Product->Availability;
  
  }
  $flagSlug = slugify($flagName);
  
  
	 ob_start();
?>
<div class='flag flag-<?php echo  $flagSlug; ?>'><?php echo $flagName; ?></div>

<?php  
  return ob_get_clean();
}


 
 
 