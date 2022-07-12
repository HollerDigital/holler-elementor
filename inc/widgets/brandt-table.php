<?php
/**
 * Elementor oEmbed Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Camex_Table_Widget extends \Elementor\Widget_Base {

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
		return 'camex-table';
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
		return __( 'Camex Table', 'plugin-name' );
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
		return 'fa fa-film';
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
		return [ 'general','camex' ];
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
				'default' => __( 'WINCH TRACTORS', 'plugin-domain' ),
				'placeholder' => __( 'Type your title here', 'plugin-domain' ),
			]
		);
		$this->add_control(
			'widget_subcode',
			[
				'label' => __( 'Sub Code', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'K09E', 'plugin-domain' ),
				'placeholder' => __( 'Type your sub title here', 'plugin-domain' ),
			]
		);
		
		$this->add_control(
			'widget_marketing',
			[
				'label' => __( 'Marketing', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Sales Promo', 'plugin-domain' ),
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
  	$title =  $settings['widget_title'];
  	$subcode =  $settings['widget_subcode'];
  	$marketing = $settings['widget_marketing'];
  	$sql;
  	
    if(!empty($subcode)) {
      $filter = "WHERE E1.EquipmentCode like '{$subcode}%' AND E1.WebDisplay = 'Yes' ";
      
      if(!empty($marketing)) {
                   
        $filter   .= " AND E1.Marketing = '{$marketing}'  ";  
      }
       
      $sql = 'SELECT 
               E1.EquipmentId, 
               E1.EquipmentCode,  
               E1.Year, 
               E1.Make, 
               E1.Model,  
               E1.TopPrice, 
               E1.BottomPrice,
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
               INNER JOIN tblclasscode AS E4 ' . $filter . '
               AND E2.SubCode = E1.EquipmentCode
               AND E3.EquipmentCode = E2.EquipmentCode
               AND E3.ClassCode = E4.ClassCode Order By E1.Year DESC';

       
    }
    
    $mydb = new wpdb( CAMEX_DB_USER,CAMEX_DB_PASSWORD,CAMEX_DB_NAME,CAMEX_DB_HOST);
     
    $Products = $mydb->get_results( $sql ); 
    
    $count = count($Products);
    
    if($count > 0):
      $result .="<h3>{$title}</h3>";
      $result .= "<style>
        table.brandt-table th {
          background: black !important;
          font-size: 16px !important;
        }
        table.brandt-table td a{
          color: #E53E30;
          text-decoration: underline;
        }
      </style>";
      $result .="<table class='brandt-table'>";
       $result .="<thead><tr>";
          $result .="<th>ID</td>";
          $result .="<th>Year</td>";
          $result .="<th>Make</td>";
          $result .="<th>Model</td>";
          $result .="<th>Description</td>";
          $result .="<th>KM</td>";
          $result .="<th>Hours</td>";
          $result .="<th>Price</td>";
       $result .="</tr></thead><tbody>";
      
      foreach($Products as $Product):
        $price = '$' . format_price( $Product->BottomPrice  );
        $km = number_format($Product->Mileage, 0, '.', "," );
        $hours = number_format($Product->Hours, 0, '.', "," );
        $product_url = get_product_url($Product);
        $result .="<tr>";
          $result .="<td><a href='{$product_url}' rel=”nofollow” >{$Product->EquipmentId}</a></td>";
          $result .="<td>{$Product->Year}</td>";
          $result .="<td>{$Product->Make}</td>";
          $result .="<td>{$Product->Model}</td>";
          $result .="<td>{$Product->ModelDescription}</td>";
          $result .="<td>{$km}</td>";
          $result .="<td>{$hours}</td>";
          $result .="<td>{$price}</td>";
        $result .="</tr>";
      endforeach;
      $result .=" </tbody></table>";
     
    else:
      $result .="<h3>No Items Found for {$title} </h3>";
    endif;
    
    echo $result;
	}



}