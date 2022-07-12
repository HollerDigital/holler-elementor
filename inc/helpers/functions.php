<?php

function getEquipmentImages($eid,  $SubCode){
    $thumbs=[]; 
  	foreach (range('a', 'z') as $letter) : 
      $webimage   =  'https://www.camex.com/webimages2/' .  $eid . $letter.'.jpg';
      $localImage = '/mnt/images/' .  $eid . $letter.'.jpg';         
      if( file_exists($localImage) ) {
        array_push($thumbs, array('_id' => '', 'imageUrl' =>  $webimage, 'imageFileType' => "image/jpeg", 'EquipmentId' =>$eid ) );
      } 
      endforeach;
      return $thumbs;
  }

  	
/*
  function getEquipmentImages($eid,  $SubCode){
    $thumbs=""; 
    $webimage   =  'https://www.camex.com/webimages2/' .  $eid . 'a.jpg'; 
    $localImage = '/mnt/images/' .  $eid . 'a.jpg';   
    if( file_exists($localImage)) {
      $thumbs = $webimage;  
    }
    return $thumbs;
  }
  */
  
  function getEquipmentThumb($eid, $SubCode){
    $thumbs=""; 
    $webimage   =  'https://www.camex.com/webimages2/' .  $eid . 'a.jpg'; 
    $localImage = '/mnt/images/' .  $eid . 'a.jpg';   
    if( file_exists($localImage)) {
      $thumbs = $webimage;  
    }
    return $thumbs;
  }
  
  
  function check_remote_image($p_url){
    // strip https and replace with http
    $url = str_replace( 'https://', 'http://', $p_url );
      
    $result = false; 
    $header = get_headers($url);
    //print_r($header);
     if( $header[0] == 'HTTP/1.1 404 Not Found') {
        // The image doesn't exist
        $result = false;
     }
     else {
         $result = true;
     }
     return $result;
     
      return $result;
   }

   
  //  function check_remote_image($p_url){

  //     return false;
  //  }
   
function get_product_slug($product){
  $slug = $product->Year. " " .$product->Make. " " .$product->Model ." " . $product->ModelName;
  return slugify($slug);
}

function get_product_short_name($product) {
  $name = $product->Year. " " .$product->Make. " " .$product->Model;
  return $name;
}

function get_product_full_name($product) {
  $name = $product->Year. " " .$product->Make. " " .$product->Model. " " .$product->ModelDescription. " " .$product->ModelName;
  return $name;
}

function get_product_full_description($product) {
  $name = $product->ModelDescription. " " .$product->ModelName;
  return $name;
}

function get_product_web_title($product){
  $title = $product->Year. " " .$product->Make. " " .$product->Model . " | Brandt Truck Rigging & Trailers";
  return $title;  
}

function get_product_url($product, $p_type = 'sales'){
  $url = '#';
  $domain = get_site_url();
  $productCat = $product->NewUsed;
  if($product->Status ==  "Rental Only"){
    $productCat = 'rentals';
  }
  $className = $product->className;
  $subClassName = $product->EquipName;
  $name = slugify(get_product_short_name($product));
  $url = $domain . '/' .  slugify($productCat). '/' .  slugify($className) . '/' .  slugify($subClassName) . '/equipment/' . $product->EquipmentId  .'/' . $name .'/';
  
  return $url;

}

function getFlag($p_availability, $p_marketing, $p_dealPending=''){
  $output;
  $flagName;
  $flagSlug;
  if(!empty($p_dealPending) && $p_dealPending != '1900-01-01 00:00:00'){
     $flagName = 'Deal Pending';
  }
  else if(!empty($p_marketing)){
    $flagName = $p_marketing;
  }else{
    $flagName = $p_availability;
  }
  $flagSlug = slugify($flagName);

  $output  = "<div class='flag flag-{$flagSlug}'>{$flagName}</div>";
 
  return  $output;
}
function check_price($price =0 , $p_make = null, $p_showcad = true){
    $tester = number_format ($price, 0, '.', '');
    
    $output = '<span class="currency">Call for pricing</span>';
    if($tester > 1){
      $output =  '$' .number_format($price, 0, '.', ',') ;
      if($p_showcad) {
         $output .= ' <span class="currency">CAD</span> ';
      }
    }
    if( $p_make == 'Brandt' ) {
      $output = '<span class="currency">Call for pricing</span>';
    }
      
    return  $output;
} 

function check_rental_price($price =0 , $p_make = null, $p_showcad = true){
    $tester = number_format ($price, 0, '.', '');
    
    $output = '<span class="currency">Call for pricing</span>';
    if($tester > 1){
      $output =  '$' .number_format($price, 0, '.', ',') ;
      if($p_showcad) {
         $output .= ' <span class="currency">CAD</span> ';
      }
    }
          
  return  $output;
} 

function format_price( $price = 0 ){
    $tester = number_format ($price, 0, '.', '');
    $output = "";
      if($tester > 1){
        $output = number_format($price, 2, '.', ',') ;
         
      } else {
         $output = "Call for pricing";
      }
    return  $output;
} 

function get_prodcut_image($p_id, $size = 'thumb', $lazy = false, $class = '', $shadow = 'no-image'){
  
  $webimage =  CAMEX_IMG_CDN . '/thumbs/'. $p_id .'a.jpg';
  $webimage2 = CAMEX_IMG_CDN . '/thumbs/000'. $p_id.'a.jpg';
  
  $letter = 'a';
  $result = '';
  $path = '';
  $p_title='';
  $loader = esc_url( WPBF_CHILD_THEME_URI ) .'/assets/img/bx_loader.gif';
  
  if ( check_remote_image( $webimage ) ) {
    $path = $webimage;
  } else if( check_remote_image( $webimage2 ) ){
    $path = $webimage2;
  } else {
    $path = esc_url( WPBF_CHILD_THEME_URI ) .'/assets/img/shadow/'.  $shadow . '.jpg';
  }
  
  $result =  '<img ';
  $result .= 'class="' . $class;
  if($lazy){
    $result .= ' lazy"';
    $result .= ' src="' . $path  . '"  data-original="'. $path .'"';
  } else {
    $result .= ' "';
    $result .= ' src="'. $path .'"';
  }
 
  $result .= ' alt="' . $p_title . ' | Camex Equipment"';
  if($size != 'full'){
  $result .= ' width="270" height="180"';
  }
  $result .= ' />';
  
  return $result;
}  


function slugify($text) {
  // replace non letter or digits by -
  $text = preg_replace('~[^\pL\d]+~u', '-', $text);

  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);

  // trim
  $text = trim($text, '-');

  // remove duplicate -
  $text = preg_replace('~-+~', '-', $text);

  // lowercase
  $text = strtolower($text);

  if (empty($text)) {
    return 'n-a';
  }

  return $text;
}

