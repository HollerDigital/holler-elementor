<?php
/**
 * Elementor oEmbed Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Brandt_Product_Tabs_Widget extends \Elementor\Widget_Base {

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
		return 'Brandt Product Tabs';
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
		return __( 'Brandt Product Tabs', 'plugin-name' );
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
			'section_prices',
			[
				'label' => __( 'Specifications', 'elementor' ),
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
  	global $wp_query;
    $settings = $this->get_settings_for_display();  
    
  	$output  = 	'<div id="brandt-tabs">';
   	$output .=   _product_tabs( $wp_query->post->Product );
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