<?php
/**
 * Elementor oEmbed Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Brandt_Product_Grid_Widget extends \Elementor\Widget_Base {

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
		return 'Brandt Product Grid';
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
		return __( 'Brandt Product Grid', 'plugin-name' );
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
		return 'eicon-posts-grid';
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
		return [  'brandt' ];
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
// 		$type = get_field('equipment_page_type');
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'data_type',
			[
				'label' => __( 'Equipment DB Query', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'dynamic' => [
                              'active' => true,
                             ],
				'default' => 'New',
				'options' => [
					'New'  => __( 'New', 'plugin-domain' ),
					'Used' => __( 'Used', 'plugin-domain' ),
					'Rental' => __( 'Rental', 'plugin-domain' ),
					'Special' => __( 'Special', 'plugin-domain' )
				],
			]
		);
		
		
		$this->add_control(
			'equip_type',
			[
				'label' => __( 'Equipment Type', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'Product',
				'dynamic' => [
                              'active' => true,
                             ],
				'options' => [
					'Truck' => __( 'Truck', 'plugin-domain' ),
					'Trailer' => __( 'Trailer', 'plugin-domain' )
					 
				],
			]
		);


		$this->add_control(
			'card_type',
			[
				'label' => __( 'Card Format', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'Product',
				'dynamic' => [
                              'active' => true,
                             ],
				'options' => [
					'Product' => __( 'Product', 'plugin-domain' ),
					'Rental' => __( 'Rental', 'plugin-domain' ),
					'Special' => __( 'Special', 'plugin-domain' )
				],
			]
		);
		
$this->add_control(
			'description_text',
			[
				'label' => __( 'No Results Message', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default' => __( 'Sorry No Listings. Please contact Brandt Truck Rigging & Trailers for equipment availability', 'elementor' ),
				'placeholder' => __( 'Enter your message', 'elementor' ),
				'separator' => 'none',
				'rows' => 5,
				'show_label' => false,
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
		// Connect to Brandt Global DB
		global $mydb;
		
		global $wp_query;
		$postID =  $wp_query->post->ID;
  
		$sql;
		$codes = array();
		$count = 0;
		$years = array();
		$makes = array();
		$prices = array();
		$cat = array();
	  	$settings = $this->get_settings_for_display();
	  	$id_list =  $settings['widget_ids'];
	  	$id_type =  $settings['data_type'];
	  	
	  	$no_results =  $settings['description_text'];
	  	
	  	$equip_type =  $settings['equip_type'];
	  	$card_type =  $settings['card_type'];
	  	
// 	  	$type = get_post_meta($wp_query->post->ID,'equipment_page_type', true );
	  	$type = $settings['data_type'];
	  	$result =null;
	  	
	  	$repeater_value = get_post_meta($postID, 'equipment_codes', true);
	  	
	  	if ($repeater_value) {
		  for ($i=0; $i < $repeater_value;  $i++) {
		    $meta_key = 'equipment_codes_'.$i.'_equipment_code';
		    $sub_field_value = get_post_meta($postID, $meta_key, true);
		    array_push($codes , "'" . $sub_field_value . "'" );
		  }
		}
		 
	  	if(empty($type)) {$type = "New";}
	  	if($type =="New Equipment") {$type = "New";}
 
			$ids = '(' . implode(', ',   $codes  ) . ')';
			$filter = "WHERE E1.EquipmentCode in $ids  AND  E1.NewUsed = '$type'  AND E1.Status != 'Rental Only' "	;
			
			if($type == 'Rental'){
				$filter = "WHERE E1.EquipmentCode in $ids   AND E1.Status = 'Rental Only' "	;
			}
			if($type == 'Used'){
				$filter = "WHERE E1.EquipmentCode in $ids  AND  E1.NewUsed = '$type'  AND E1.Status != 'Rental Only' "	;
			}
			if($type == 'Special'){
			  $ids = '(' . get_field( 'equipment_list' ) . ')';
			  $filter = "WHERE E1.EquipmentId in $ids "	;   		
			}

	  	$sql = 'SELECT 
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
               E1.Location,
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
               INNER JOIN tblclasscode AS E4 '.$filter.'
               AND E2.SubCode = E1.EquipmentCode
               AND E3.EquipmentCode = E2.EquipmentCode
               AND E3.ClassCode = E4.ClassCode
               AND E1.WebDisplay = "Yes" 
               Order By E1.Year DESC';
               
               $mydb = new wpdb(CAMEX_DB_USER,CAMEX_DB_PASSWORD,CAMEX_DB_NAME,CAMEX_DB_HOST);
               $Products =  $mydb->get_results( $sql );
			    
               $count = count($Products);
               if($count > 0):
               $result .= '	<div id="product-grid">';
			   foreach($Products as $Product):
              	
              	if(!empty( $Product->Year)){
	              array_push($years, $Product->Year);
	            }
	            if(!empty($Product->Make)){
	              array_push($makes, $Product->Make);
	            }
	            if(!empty($Product->TopPrice)){
	              array_push($prices, $Product->TopPrice);
	            }
	            if(!empty( $Product->SubName)){
	              array_push($cat,$Product->SubName);
	            }

              $Product->Slug = get_product_slug($Product);
              $Product->ShortName = get_product_short_name($Product);
              $Product->FullName =get_product_full_name($Product);
              $Product->FullDescription = get_product_full_description($Product);
              $Product->webName = get_product_web_title($Product);
              $Product->url = get_product_url($Product);
              $Product->icon = "https://camex.com/wp-content/themes/brandt/assets/img/shadow/" . $Product->SubCode . '.jpg';
		    
		 

/*
              	$thumbs = getEquipmentThumb($Product->EquipmentId, $Product->SubCode);
				$media = getEquipmentImages($Product->EquipmentId, $Product->SubCode );
		        $Product->thumb = $thumbs;
		        $Product->images = $media;
*/
				$myArray[ ] = $Product;
				$result .=  _card_template($Product, $card_type, $equip_type);
              endforeach;       
             
			  $result .= '</div>';
			  $result .= "<script> var equipment_list = " . json_encode($myArray) . "</script>";
			  $result .= "<script> var equipment_makes = " . $this->js_array($makes) . "</script>"; 
			  $result .= "<script> var equipment_years = " . $this->js_array($years) . "</script>";
			  $result .= "<script> var equipment_prices = " . $this->js_array($prices) . "</script>";
			  $result .= "<script> var equipment_cats = " . $this->js_array($cat) . "</script>";     		  
				 	//
				else:
              $result .="<h3>" . $no_results . "</h3>";
              endif;
	  
		  	echo $result;
    
	}

	protected function _content_template() {
		?>
		<#
		var target = settings.button_url.is_external ? ' target="_blank"' : '';
		var nofollow = settings.button_url.nofollow ? ' rel="nofollow"' : '';
		#>
		<h2>{{ settings.widget_title }} </h2>
		<a href="{{ settings.button_url.url }}"{{ target }}{{ nofollow }}> {{ settings.button_text }} </a>
		<?php
	}
	
	private function js_array($array) {
		$result = '[]';
		if(!empty($array)){
			//$temp = array_map('js_str', $array);
			$result = '["' . implode('","', $array) . '"]';
		}
		return $result;
	}
}