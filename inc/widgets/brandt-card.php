<?php
/**
 * Elementor oEmbed Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Brandt_Card_Widget extends \Elementor\Widget_Base {

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
		return 'brandt-card';
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
		return __( 'Brandt Card', 'plugin-name' );
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
		return 'eicon-info-box';
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
			'widget_ids',
			[
				'label' => __( 'Product ID', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Default title', 'plugin-domain' ),
				'placeholder' => __( 'Comma Separated List of IDs', 'plugin-domain' ),
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
  	$result = '';
  	$settings = $this->get_settings_for_display();
  	$id_list =  $settings['widget_ids'];
  	$equip_type =  $settings['equip_type'];
	$card_type =  $settings['card_type'];
  	
  	$sql;
  	
    if(!empty($id_list)) {
      
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
                WHERE E1.EquipmentId = $ids 
               AND E2.SubCode = E1.EquipmentCode
               AND E3.EquipmentCode = E2.EquipmentCode
               AND E3.ClassCode = E4.ClassCode
               AND E1.WebDisplay = 'Yes'  
              ORDER BY E1.CreationDate DESC LIMIT 1";
       
       
    }
    
    $mydb = new wpdb( CAMEX_DB_USER,CAMEX_DB_PASSWORD,CAMEX_DB_NAME,CAMEX_DB_HOST);
    $Product = $mydb->get_results( $sql ); 
		$target = $settings['button_url']['is_external'] ? ' target="_blank"' : '';
		$nofollow = $settings['button_url']['nofollow'] ? ' rel="nofollow"' : '';
	 
	
             $Product->Slug = get_product_slug($Product);
              $Product->ShortName = get_product_short_name($Product);
              $Product->FullName =get_product_full_name($Product);
              $Product->FullDescription = get_product_full_description($Product);
              $Product->webName = get_product_web_title($Product);
              $Product->url = get_product_url($Product);
              $Product->icon = "https://camex.com/wp-content/themes/brandt/assets/img/shadow/" . $Product->SubCode . '.jpg';

		
  
    if( !empty($Product[0]->EquipmentId) ){
	      
	    $result .=   _card_template($Product[0], $card_type, $equip_type);

      } else {
         $result .= '<h4>No Items Found</h4>';
      }

 
       echo $result;
	}



}