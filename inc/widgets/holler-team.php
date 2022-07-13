<?php
/**
 * Elementor oEmbed Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Holler_Team_Widget extends \Elementor\Widget_Base {

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
		return 'holler-team';
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
		return __( 'Holler Team', 'plugin-name' );
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
		return [ 'general','holler' ];
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
 
  	 
  	
    
		$target = $settings['button_url']['is_external'] ? ' target="_blank"' : '';
		$nofollow = $settings['button_url']['nofollow'] ? ' rel="nofollow"' : '';
	 
	
            
		
  
    
	      
	    $result .=   _card_template($Product[0], $card_type, $equip_type);

    

 
       echo $result;
	}



}