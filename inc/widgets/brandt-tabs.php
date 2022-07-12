<?php
/**
 * Elementor oEmbed Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Brandt_Tabs_Widget extends \Elementor\Widget_Base {

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
		return 'Brandt Tabs';
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
		return __( 'Brandt Tabs', 'plugin-name' );
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
		return 'eicon-tabs';
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
		return [ 'brandt' ];
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
		
	 
		
		
		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'list_title', [
				'label' => __( 'Title', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'List Title' , 'plugin-domain' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'list_value', [
				'label' => __( 'Value', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => __( 'List Content' , 'plugin-domain' ),
				'label_block' => true,
			]
		);
		
					
		$this->add_control(
			'list',
			[
				'label' => __( 'Repeater List', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'list_title' => __( 'Overview', 'plugin-domain' ),
						'list_value' => __( 'Overview content.', 'plugin-domain' ),
					],
						[
						'list_title' => __( 'Description', 'plugin-domain' ),
						'list_value' => __( 'Description content.', 'plugin-domain' ),
					],
					[
						'list_title' => __( 'Options', 'plugin-domain' ),
						'list_value' => __( 'Options content.', 'plugin-domain' ),
					],
				],
				'title_field' => '{{{ list_title }}}',
			]
		);

	$this->end_controls_section();
	// $this->start_controls_section(
	// 		'section_prices',
	// 		[
	// 			'label' => __( 'Specifications', 'elementor' ),
	// 		]
	// 	);

	// 	$specsRepeater = new \Elementor\Repeater();

	// 	$specsRepeater->add_control(
	// 		'spec_title',
	// 		[
	// 			'label'       => __( 'Title', 'elementor' ),
	// 			'type'        => \Elementor\Controls_Manager::TEXT,
	// 			'default'     => __( 'category-name', 'elementor' ),
	// 			'label_block' => true,
	// 		]
	// 	);

	// 	$specsRepeater->add_control(
	// 		'spec_value',
	// 		[
	// 			'label'       => __( 'Value', 'elementor' ),
	// 			'type'        => \Elementor\Controls_Manager::TEXT,
	// 			'default'     => __( 'Service Title', 'elementor' ),
	// 			'label_block' => true,
	// 		]
	// 	);

	

	// 	$this->add_control(
	// 		'Specifications',
	// 		[
	// 			'label'       => __( 'Specifications', 'elementor' ),
	// 			'type'        => \Elementor\Controls_Manager::REPEATER,
	// 			'fields'      => $specsRepeater->get_controls(),
	// 			'title_field' => '{{{ spec_title }}}',
	// 		]
	// 	);
		
	// 	$this->end_controls_section();
	
	$this->start_controls_section(
			'downloads',
			[
				'label' => __( 'Downloads', 'elementor' ),
			]
		);

		$dlRepeater = new \Elementor\Repeater();

		$dlRepeater->add_control(
			'dl_title',
			[
				'label'       => __( 'Title', 'elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'category-name', 'elementor' ),
				'label_block' => true,
			]
		);

		$dlRepeater->add_control(
			'dl_link',
			[
				'label' => __( 'Link', 'plugin-domain' ),
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

	
		$this->add_control(
			'dl_list',
			[
				'label' => __( 'Repeater List', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $dlRepeater->get_controls(),
				'default' => [
					[
						'list_title' => __( 'Description', 'plugin-domain' ),
						'list_value' => __( 'Item content.', 'plugin-domain' ),
					] 
				],
				'title_field' => '{{{ dl_title }}}',
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
/*
  	echo "<pre>";     
	print_r($settings);
	echo "</pre>";
*/
	$output  = 	'<div id="brandt-tabs">';
 	$output .=  _tabs_heading( $settings );
 	$output .=  _tabs_body( $settings );
	//$output .= 	_tabs_specs( $settings );
	$output .=  _tabs_downloads( $settings );
	$output .= 	'</div>';
	     
    echo $output;
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

}