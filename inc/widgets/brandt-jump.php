<?php
/**
 * Elementor oEmbed Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Brandt_Jump_Widget extends \Elementor\Widget_Base {

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
		return 'Brandt Product Jump Menu';
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
		return __( 'Brandt Product Jump Menu', 'plugin-name' );
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
		return 'eicon-editor-list-ul';
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

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		
		$this->add_control(
			'active',
			[
				'label' => __( 'Active Item', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'New',
				'dynamic' => [
                              'active' => true,
                             ],
				'options' => [
					'New' => __( 'New', 'plugin-domain' ),
					'Used' => __( 'Used', 'plugin-domain' ),
					'Rentals' => __( 'Rentals', 'plugin-domain' ),
					 
				],
			]
		);
		
		
		
		$this->add_control(
			'new_url',
			[
				'label' => __( 'New Equipment Page', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => '#',
					'is_external' => false,
					'nofollow' => false,
				],
				'placeholder' => __( 'Enter New Equipment Page', 'plugin-name' ),
				'separator' => 'none',
				 
			]
		);
		$this->add_control(
			'used_url',
			[
				'label' => __( 'Used Equipment Page', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => '#',
					'is_external' => false,
					'nofollow' => false,
				],
				'placeholder' => __( 'Enter Used Equipment Page', 'plugin-name' ),
				'separator' => 'none',
				 
			]
		);
		$this->add_control(
			'rental_url',
			[
				'label' => __( 'Rental Equipment Page', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => '#',
					'is_external' => false,
					'nofollow' => false,
				],
				'placeholder' => __( 'Enter Rental Equipment Page', 'plugin-name' ),
				'separator' => 'none',
				 
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
  	
  	$settings = $this->get_settings_for_display();       
	
	//$used_target = $settings['used_url']['is_external'] ? ' target="_blank"' : '';
	//$used_nofollow = $settings['used_url']['nofollow'] ? ' rel="nofollow"' : '';
	if(!empty($settings['new_url'])){
		$new_url = $settings['new_url']['url'];
	}
	$used_url = $settings['used_url']['url'];
	$rental_url = $settings['rental_url']['url'];

	$active = $settings['active'];
		     
    $output="<div id='brandt-jump-menu'>";
    if(!empty($new_url) ){// && $new_url != "#"
	    
	    $class = 'far fa-square';
	    if ($active == 'New') {
		    $class = 'fas fa-square';
	    }  
	    
	    $output .= '<a href="'. $new_url .'"  class="elementor-button elementor-size-sm brandt-jump-button" role="button">
	    <span class="elementor-button-content-wrapper">
					
					<span class="elementor-button-icon elementor-align-icon-left">
						
							<i aria-hidden="true" class="'.$class.'"></i>
						
					</span>
					
					<span class="elementor-button-text elementor-inline-editing" data-elementor-setting-key="text" data-elementor-inline-editing-toolbar="none">New</span>
				</span>
				</a>';
	    
				
			 
    }
    if(!empty($used_url)  ){ //&& $used_url != "#"
	    
	    $class = 'far fa-square';
	    if ($active == 'Used') {
		    $class = 'fas fa-square';
	    }
	    
	      $output .= '<a href="'.$used_url .'"  class="elementor-button elementor-size-sm brandt-jump-button" role="button">
	    <span class="elementor-button-content-wrapper">
					
					<span class="elementor-button-icon elementor-align-icon-left">
						
							<i aria-hidden="true" class="'.$class.'"></i>
						
					</span>
					
					<span class="elementor-button-text elementor-inline-editing" data-elementor-setting-key="text" data-elementor-inline-editing-toolbar="none">Used</span>
				</span>
				</a>';
    }
    if(!empty($rental_url) ){ // && $rental_url != "#"
	    
	    $class = 'far fa-square';
	    if ($active == 'Rentals') {
		    $class = 'fas fa-square';
	    }
	    
	    $output .= '<a href="'.$rental_url .'"  class="elementor-button elementor-size-sm brandt-jump-button" role="button">
	    <span class="elementor-button-content-wrapper">
					
					<span class="elementor-button-icon elementor-align-icon-left">
						
							<i aria-hidden="true" class="'.$class.'"></i>
						
					</span>
					
					<span class="elementor-button-text elementor-inline-editing" data-elementor-setting-key="text" data-elementor-inline-editing-toolbar="none">Rentals</span>
				</span>
				</a>';    }
	$output .= "</div>";			  
     
    echo $output;
 
	}

	protected function _content_template() {
		 
	}

}