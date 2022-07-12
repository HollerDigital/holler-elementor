<?php
Class Elementor_Server_Var_Tag extends \Elementor\Core\DynamicTags\Tag {

	/**
	* Get Name
	*
	* Returns the Name of the tag
	*
	* @since 2.0.0
	* @access public
	*
	* @return string
	*/
	public function get_name() {
		return 'brandt-tags';
	}

	/**
	* Get Title
	*
	* Returns the title of the Tag
	*
	* @since 2.0.0
	* @access public
	*
	* @return string
	*/
	public function get_title() {
		return __( 'Brandt Tags', 'elementor-pro' );
	}
   
	/**
	* Get Group
	*
	* Returns the Group of the tag
	*
	* @since 2.0.0
	* @access public
	*
	* @return string
	*/
	public function get_group() {
		return 'brandt-tags';
	}

	/**
	* Get Categories
	*
	* Returns an array of tag categories
	*
	* @since 2.0.0
	* @access public
	*
	* @return array
	*/
	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}

	/**
	* Register Controls
	*
	* Registers the Dynamic tag controls
	*
	* @since 2.0.0
	* @access protected
	*
	* @return void
	*/
	protected function _register_controls() {

		$variables = [];

/*
		foreach ( array_keys( $_SERVER ) as $variable ) {

			$variables[ $variable ] = ucwords( str_replace( '_', ' ', $variable ) );
		}
 
*/
$variables['EquipmentId'] = "EquipmentId";
$variables['EquipmentCode'] = "EquipmentCode";
$variables['Year'] = "Year";
$variables['Make'] = "Make";
$variables['Model'] = "Model";
$variables['ShortName'] = "Model Short Name";
$variables['ModelDescription'] = "Short Model Description";
$variables['NewUsed'] = "New / Used";
$variables['TopPrice'] = "Price";
$variables['FullDescription'] = "Full ModelDescription ";

 
 

		$this->add_control(
			'param_name',
			[
				'label' => __( 'Brandt Equipment', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => $variables,
			]
		);
	}

	/**
	* Render
	*
	* Prints out the value of the Dynamic tag
	*
	* @since 2.0.0
	* @access public
	*
	* @return void
	*/
	public function render() {
  	global $wp_query;
  	
		$param_name = $this->get_settings( 'param_name' );
    
    if ( ! $param_name ) {
			return;
		}
		
    $value = 	 $wp_query->post->Product->$param_name;
		
		if ( ! isset( $wp_query->post->Product->$param_name ) ) {
			return;
		}
    
		if ( $param_name == "TopPrice"){
  		 $value = check_price($wp_query->post->Product->TopPrice , $wp_query->post->Product->Make);
		}
		
		echo wp_kses_post( $value );
	}
}