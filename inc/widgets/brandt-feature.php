<?php
/**
 * Elementor oEmbed Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Brandt_Feature_Widget extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve oEmbed widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'brandt-feature';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve oEmbed widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Brandt Feature', 'plugin-name' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve oEmbed widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-slides';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the oEmbed widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'general','brandt' ];
	}
  public function slugify($text) {
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
  public function get_product_name($product) {
  $name = $product->Year. " " .$product->Make. " " .$product->Model;
  return $name;
}
  public function get_product_url($product, $p_type = 'sales'){
    $url = '#';
    $domain = get_site_url();
    $className = $product->className;
    if($product->Status ==  "Rental Only"){
      $className = 'rentals';
    }
    $subClassName = $product->EquipName;
    $name = $this->slugify($this->get_product_name($product));
    $url = $domain . '/' .  $this->slugify($className) . '/' .  $this->slugify($subClassName) . '/equipment/' . $product->EquipmentId  .'/' . $name .'/';
    
    return $url;

}
public function check_price($price =0 , $p_make = null, $p_showcad = true){
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

public function check_remote_image($p_url){
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
}
function get_prodcut_image($p_id, $size = 'thumb', $lazy = false, $class = '', $shadow = 'no-image'){
  
  $webimage =  brandt_IMG_CDN . '/thumbs/'. $p_id .'a.jpg';
  $webimage2 = brandt_IMG_CDN . '/thumbs/000'. $p_id.'a.jpg';
  
  $letter = 'a';
  $result = '';
  $path = '';
  $p_title='';
  $loader = esc_url( WPBF_CHILD_THEME_DIR ) .'/assets/img/bx_loader.gif';
  
  if ( $this->check_remote_image( $webimage ) ) {
    $path = $webimage;
  } else if( $this->check_remote_image( $webimage2 ) ){
    $path = $webimage2;
  } else {
    $path = esc_url( WPBF_CHILD_THEME_DIR ) .'/assets/img/shadow/'.  $shadow . '.jpg';
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
 
  $result .= ' alt="' . $p_title . ' | brandt Equipment"';
  if($size != 'full'){
  $result .= ' width="270" height="180"';
  }
  $result .= ' />';
  
  return $result;
} 
	/**
	 * Register oEmbed widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'widget_title',
			[
				'label' => __( 'Title', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Default title', 'plugin-domain' ),
				'placeholder' => __( 'Type your title here', 'plugin-domain' ),
			]
		);
		$this->add_control(
			'widget_subtitle',
			[
				'label' => __( 'Sub Title', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '', 'plugin-domain' ),
				'placeholder' => __( 'Type your sub title here', 'plugin-domain' ),
			]
		);
		
		$this->add_control(
			'widget_ids',
			[
				'label' => __( 'Product IDs', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Default title', 'plugin-domain' ),
				'placeholder' => __( 'Comma Separated List of IDs', 'plugin-domain' ),
			]
		);
		
		$this->add_control(
			'hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);
    
    $this->add_control(
			'widget_heading',
			[
				'label' => __( 'Button Information', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'button_text',
			[
				'label' => __( 'Button Text', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Default title', 'plugin-domain' ),
				'placeholder' => __( 'Type your title here', 'plugin-domain' ),
			]
		);
		
		$this->add_control(
			'button_url',
			[
				'label' => __( 'Button Link', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'plugin-domain' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render oEmbed widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
 
  	global $mydb;
  	$result="<div>";
  	$settings = $this->get_settings_for_display();
  	$id_list =  $settings['widget_ids'];
  	$sql;
  	
    if(empty($id_list)){
      $sql = 'SELECT EquipmentId, EquipmentCode, EquipmentCode, Year, Make, Model, ModelDescription, ModelName, TopPrice, Status, Availability FROM tblassets WHERE WebDisplay = "Yes" ORDER BY CreationDate DESC LIMIT 12';
    } else {
       
       	$ids = '(' . $id_list . ')';
        $sql = "SELECT 
                E1.EquipmentId, 
               E1.EquipmentCode,  
               E1.Year, 
               E1.Make, 
               E1.Model,  
               E1.TopPrice, 
               E1.Description, 
               E1.Status, 
               E1.SerialNumber, 
               E1.Mileage, 
               E1.Hours, 
               E1.EngineSize, 
               E1.TransmissionSize, 
               E1.SuspensionType, 
               E1.FrAxle, 
               E1.ReAxle, 
               E1.Availability, 
               E1.DateCertified, 
               E1.AxleRatio, 
               E1.WheelBase, 
               E1.FrTireSize, 
               E1.ReTireSize, 
               E1.ReTireWear, 
               E1.FrTireWear, 
               E1.WDWidth, 
               E1.WDLength, 
               E1.UCPercent, 
               E1.PadsWidth, 
               E1.Options, 
               E1.Videos, 
               E1.ModelDescription, 
               E1.ModelName, 
               E1.RentalsRateDaily, 
               E1.RentalsRateWeekly, 
               E1.RentalsRateMonthly, 
               E1.RentalsRateKM,
               E1.NewUsed, 
               E1.WebDisplay,
               E2.EquipmentCode, 
               E2.Name as SubName, 
               E2.SubCode, 
               E3.Name as EquipName, 
               E3.ClassCode, 
               E4.Name as className 
               FROM 
               tblassets AS E1 
               INNER JOIN tblsubcode AS E2  
               INNER JOIN tblequipmentcode AS E3  
               INNER JOIN tblclasscode AS E4  
               WHERE E1.EquipmentId in $ids 
               AND E2.SubCode = E1.EquipmentCode
               AND E3.EquipmentCode = E2.EquipmentCode
               AND E3.ClassCode = E4.ClassCode
               AND E1.WebDisplay = 'Yes'  
              ORDER BY E1.CreationDate DESC LIMIT 12";
       
       
    }
    
	$mydb = new wpdb( CAMEX_DB_USER,CAMEX_DB_PASSWORD,CAMEX_DB_NAME,CAMEX_DB_HOST);
    $products = $mydb->get_results( $sql ); 
    
    $target = $settings['button_url']['is_external'] ? ' target="_blank"' : '';
	$nofollow = $settings['button_url']['nofollow'] ? ' rel="nofollow"' : '';
	$result .= '<div class="LatestListings-header">';
	$result .= '<div class="LatestListings-title"><h2>' .$settings['widget_title'] .'</h2><h5>' .$settings['widget_subtitle'] .'</h5></div>';
    
    if(!empty( $settings['button_url']['url']))  {
    	$result .= '<div class="LatestListings-button"><a href="' . $settings['button_url']['url'] . '"' . $target . $nofollow . ' class="btn btn-primary"> '. $settings['button_text'].' </a></div>';
    }
	$result .= '</div>';
		
	$result .= '<div id="brandt-feature-slider" class="owl-carousel owl-theme">';
  
    if(count($products) > 0  ){
		foreach ($products as $product) : 
        	
              $product->Slug = get_product_slug($product);
              $product->ShortName = get_product_short_name($product);
              $product->FullName =get_product_full_name($product);
              $product->webName = get_product_web_title($product);
              $product->url = get_product_url($product);
              $product->icon = "https://camex.com/wp-content/themes/brandt/assets/img/shadow/" . $product->SubCode . '.jpg';

			$myArray[ ] = $product;
			$result .=   _card_mini_template($product );
         
         endforeach; 
         $result .= "<script> var equipment_list = " . json_encode($myArray) . "</script>";
      } else {
         $result .= '<h4>No Items Found</h4>';
      }
       $result .= ' </div></div>';
 
       echo $result;
	}




}