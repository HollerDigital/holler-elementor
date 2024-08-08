<?php
/**
 * Elementor oEmbed Widget.
 *
 * Elementor widget that inserts an embeddable content into the page, from any given URL.
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
		return [ 'holler' ];
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
			'team_image',
			[
				'label' => __( 'Team Image', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'description' => __("Image Thumbnail 128px by 128px", 'plugin-domain' ),
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);
		
		$this->add_control(
			'team_image_style',
			[
				'label' => __( 'Image Style', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'image-round' => __( 'Round', 'plugin-domain' ),
					'image-square' => __( 'Square', 'plugin-domain' ),
				],
				'default' => 'image-round',
			]
		);
	
		$this->add_control(
			'team_name',
			[
				'label' => __( 'Name', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Full Name', 'plugin-domain' ),
				'placeholder' => __( 'Full Name', 'plugin-domain' ),
			]
		);

		$this->add_control(
			'team_title',
			[
				'label' => __( 'Title', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Job Title', 'plugin-domain' ),
				'placeholder' => __( 'Job Title', 'plugin-domain' ),
			]
		);

		$this->add_control(
			'show_bio',
			[
				'label' => __( 'Show Bio', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'plugin-domain' ),
				'label_off' => __( 'Hide', 'plugin-domain' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'team_bio',
			[
				'label' => __( 'Bio', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => __( 'Bio', 'plugin-domain' ),
				'placeholder' => __( 'Bio', 'plugin-domain' ),
				'condition' => [
					'show_bio' => 'yes',
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
 
		$settings = $this->get_settings_for_display();
	    
	    $result = _holler_team_template($settings);

       echo $result;
	}


}
