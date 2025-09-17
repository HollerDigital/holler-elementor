<?php
/**
 * Holler Team Widget.
 *
 * Elementor widget that displays team members with optional bio modal.
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
		return __( 'Holler Team', 'holler-elementor' );
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
				'label' => __( 'Content', 'holler-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'team_image',
			[
				'label' => __( 'Team Image', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'description' => __("Image Thumbnail 128px by 128px", 'holler-elementor' ),
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
					'active' => true, // Enables dynamic tags
				],
			]
		);
		
		$this->add_control(
			'team_image_style',
			[
				'label' => __( 'Image Style', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'image-round' => __( 'Round', 'holler-elementor' ),
					'image-square' => __( 'Square', 'holler-elementor' ),
				],
				'default' => 'image-round',
			]
		);

        // Use Elementor's Image Size Group Control for better integration and consistency
        $this->add_group_control(
            \Elementor\Group_Control_Image_Size::get_type(),
            [
                // Use the same base name as the media control so generated keys are
                // `team_image_size` and `team_image_custom_dimension` (backward compatible
                // with existing layout which reads `team_image_size`).
                'name' => 'team_image',
                'default' => 'medium',
                'separator' => 'none',
            ]
        );
	
		$this->add_control(
			'team_name',
			[
				'label' => __( 'Name', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Full Name', 'holler-elementor' ),
				'placeholder' => __( 'Full Name', 'holler-elementor' ),
				'dynamic' => [
					'active' => true, // Enables dynamic tags
				],
			]
		);

		// Heading tag for card name
		$this->add_control(
			'team_name_tag',
			[
				'label' => __( 'Card Name Tag', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'p'  => 'p',
					'div'=> 'div',
					'span'=> 'span',
				],
				'default' => 'h2',
			]
		);

		$this->add_control(
			'team_title',
			[
				'label' => __( 'Title', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Job Title', 'holler-elementor' ),
				'placeholder' => __( 'Job Title', 'holler-elementor' ),
				'dynamic' => [
					'active' => true, // Enables dynamic tags
				],
			]
		);

		// Heading tag for modal name
		$this->add_control(
			'modal_name_tag',
			[
				'label' => __( 'Modal Name Tag', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'p'  => 'p',
					'div'=> 'div',
					'span'=> 'span',
				],
				'default' => 'h2',
				'condition' => [
					'show_bio' => 'yes',
				],
			]
		);

		// Social Media Section
		$this->add_control(
			'show_social',
			[
				'label' => __( 'Show Social Icons', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'plugin-domain' ),
				'label_off' => __( 'Hide', 'plugin-domain' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_control(
			'linkedin_url',
			[
				'label' => __( 'LinkedIn URL', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://linkedin.com/in/username', 'plugin-domain' ),
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
				'condition' => [
					'show_social' => 'yes',
				],
			]
		);

		$this->add_control(
			'instagram_url',
			[
				'label' => __( 'Instagram URL', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://instagram.com/username', 'plugin-domain' ),
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
				'condition' => [
					'show_social' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_bio',
			[
				'label' => __( 'Show Bio', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'holler-elementor' ),
				'label_off' => __( 'Hide', 'holler-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		
		$this->add_control(
			'team_url_toggle',
			[
				'label' => __( 'Enable URL', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'holler-elementor' ),
				'label_off' => __( 'No', 'holler-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
				'condition' => [
					'show_bio' => '',
				],
			]
		);
		
		$this->add_control(
			'team_bio',
			[
				'label' => __( 'Bio', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => __( 'Bio', 'holler-elementor' ),
				'placeholder' => __( 'Bio', 'holler-elementor' ),
				'condition' => [
					'show_bio' => 'yes',
				],
			]
		);
		$this->add_control(
			'team_url',
			[
				'label' => __( 'URL', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://example.com', 'holler-elementor' ),
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
				'condition' => [
					'team_url_toggle' => 'yes',
				],
			]
		);
		
		 

		
		$this->end_controls_section();

// Social Icons Style Section
$this->start_controls_section(
	'section_social_style',
	[
		'label' => __('Social Icons', 'plugin-domain'),
		'tab' => \Elementor\Controls_Manager::TAB_STYLE,
		'condition' => [
			'show_social' => 'yes',
		],
	]
);

$this->add_control(
	'social_icons_color',
	[
		'label' => __('Icons Color', 'plugin-domain'),
		'type' => \Elementor\Controls_Manager::COLOR,
		'default' => '#333333',
		'selectors' => [
			'{{WRAPPER}} .team-social-icon i' => 'color: {{VALUE}};',
		],
	]
);

$this->add_control(
	'social_icons_hover_color',
	[
		'label' => __('Icons Hover Color', 'plugin-domain'),
		'type' => \Elementor\Controls_Manager::COLOR,
		'default' => '#0077B5',
		'selectors' => [
			'{{WRAPPER}} .team-social-icon:hover i' => 'color: {{VALUE}};',
		],
	]
);

$this->add_control(
	'social_icons_size',
	[
		'label' => __('Icons Size', 'plugin-domain'),
		'type' => \Elementor\Controls_Manager::SLIDER,
		'size_units' => ['px'],
		'range' => [
			'px' => [
				'min' => 12,
				'max' => 48,
				'step' => 1,
			],
		],
		'default' => [
			'unit' => 'px',
			'size' => 20,
		],
		'selectors' => [
			'{{WRAPPER}} .team-social-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_control(
	'social_icons_spacing',
	[
		'label' => __('Icons Spacing', 'plugin-domain'),
		'type' => \Elementor\Controls_Manager::SLIDER,
		'size_units' => ['px'],
		'range' => [
			'px' => [
				'min' => 0,
				'max' => 50,
				'step' => 1,
			],
		],
		'default' => [
			'unit' => 'px',
			'size' => 10,
		],
		'selectors' => [
			'{{WRAPPER}} .team-social-icons' => 'margin-top: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .team-social-icon' => 'margin: 0 {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_control(
	'social_icons_alignment',
	[
		'label' => __('Icons Alignment', 'plugin-domain'),
		'type' => \Elementor\Controls_Manager::CHOOSE,
		'options' => [
			'left' => [
				'title' => __('Left', 'plugin-domain'),
				'icon' => 'eicon-text-align-left',
			],
			'center' => [
				'title' => __('Center', 'plugin-domain'),
				'icon' => 'eicon-text-align-center',
			],
			'right' => [
				'title' => __('Right', 'plugin-domain'),
				'icon' => 'eicon-text-align-right',
			],
		],
		'default' => 'center',
		'selectors' => [
			'{{WRAPPER}} .team-social-icons' => 'text-align: {{VALUE}};',
		],
	]
);

$this->end_controls_section();
		  // Styles Tab
		  $this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Styles', 'holler-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
	
		$this->add_control(
			'team_name_size',
			[
				'label' => __( 'Team Name Size', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
					'em' => [
						'min' => 0.5,
						'max' => 10,
					],
					'rem' => [
						'min' => 0.5,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--holler-team-name-size: {{SIZE}}{{UNIT}};',
				],
				'default' => [
					'size' => 1.2,
					'unit' => 'rem',
				],
			]
		);
	
		$this->add_control(
			'team_name_color',
			[
				'label' => __( 'Team Name Color', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--holler-team-name-color: {{VALUE}};',
				],
				'default' => '#08005C',
			]
		);
	
		$this->add_control(
			'team_title_size',
			[
				'label' => __( 'Team Title Size', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
					'em' => [
						'min' => 0.5,
						'max' => 10,
					],
					'rem' => [
						'min' => 0.5,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--holler-team-title-size: {{SIZE}}{{UNIT}};',
				],
				'default' => [
					'size' => 1,
					'unit' => 'em',
				],
			]
		);
	
		$this->add_control(
			'team_title_color',
			[
				'label' => __( 'Team Title Color', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--holler-team-title-color: {{VALUE}};',
				],
				'default' => '#8C4EFD',
			]
		);
	
		$this->add_control(
			'modal_bg_color',
			[
				'label' => __( 'Modal Background Color', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--holler-team-modal-bgcolor: {{VALUE}};',
				],
				'default' => 'rgba(8, 0, 92, 0.9)',
			]
		);
	
		$this->add_control(
			'modal_name_size',
			[
				'label' => __( 'Modal Name Size', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
					'em' => [
						'min' => 0.5,
						'max' => 10,
					],
					'rem' => [
						'min' => 0.5,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--holler-team-modal-name-size: {{SIZE}}{{UNIT}};',
				],
				'default' => [
					'size' => 1.5,
					'unit' => 'em',
				],
			]
		);
	 
		$this->add_control(
			'modal_title_size',
			[
				'label' => __( 'Modal Title Size', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
					'em' => [
						'min' => 0.5,
						'max' => 10,
					],
					'rem' => [
						'min' => 0.5,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--holler-team-modal-title-size: {{SIZE}}{{UNIT}};',
				],
				'default' => [
					'size' => 1.25,
					'unit' => 'em',
				],
			]
		);
	
		$this->add_control(
			'modal_text_color',
			[
				'label' => __( 'Modal Text Color', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--holler-team-modal-color: {{VALUE}};',
				],
				'default' => '#08005C',
			]
		);
	
		$this->end_controls_section();

	}

	/**
	 * Get widget stylesheet dependencies.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_style_depends() {
		return ['holler-elementor'];
	}

	/**
	 * Get widget script dependencies.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_script_depends() {
		return ['holler-elementor'];
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		try {
			$settings = $this->get_settings_for_display();
			
			// Get the widget's unique ID
			$widget_id = $this->get_id();
			
			// Use memory-safe rendering with fallback
			$result = holler_memory_safe_render(
				'_holler_team_template',
				[$settings, $widget_id],
				'<div class="holler-error">Unable to display team member due to resource constraints.</div>'
			);

			echo $result;
		} catch (\Exception $e) {
			// Log the error for debugging
			// error_log('Holler Team Widget Error: ' . $e->getMessage());
			
			// Display a fallback for users
			echo '<div class="holler-team-error">Team member information could not be displayed.</div>';
		}
	}
	
	/**
	 * Render widget output in the editor.
	 *
	 * Written as a JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function content_template() {
		?>
		<# 
		// Get settings
		var imgSrc = settings.team_image.url;
		var imgStyle = settings.team_image_style || 'image-round';
		var imageSize = settings.team_image_size || 'medium';
		var teamName = settings.team_name || '';
		var teamTitle = settings.team_title || '';
		var showBio = settings.show_bio === 'yes';
		var teamUrlToggle = settings.team_url_toggle || 'no';
		// Note: Image size selection will be applied on frontend render, not in editor preview
		#>
		
		<article class="holler-widget holler-team">
			<# if (showBio) { #>
			<a href="javascript:void(0)" class="holler_team holler_team_link">
			<# } else if (teamUrlToggle === 'yes') { #>
			<a href="{{ settings.team_url.url }}" class="holler_team holler_team_link">
			<# } else { #>
			<div class="holler_team">
			<# } #>
			
			<figure class="img-wrap">
				<img src="{{ imgSrc }}" alt="{{ teamName }}" class="{{ imgStyle }}" width="800" height="800" />
			</figure>
			
			<header class="team-header">
				<# var teamNameTag = settings.team_name_tag || 'h2'; #>
				<{{ teamNameTag }} class="team-name">{{ teamName }}</{{ teamNameTag }}>
				<h3 class="team-title">{{ teamTitle }}</h3>
			</header>
			
			<# if (showBio || teamUrlToggle === 'yes') { #>
			</a>
			<# } else { #>
			</div>
			<# } #>
		</article>
		
		<# if (showBio) { #>
		<div class="modal modal-preview">
			<div class="modal-content">
				<span class="close">&times;</span>
				<div class="modal-inner">
					<div class="modal-header">
						<# var modalNameTag = settings.modal_name_tag || 'h2'; #>
						<{{ modalNameTag }}>{{ teamName }}</{{ modalNameTag }}>
						<h3>{{ teamTitle }}</h3>
					</div>
					<div class="modal-body">
						{{{ settings.team_bio }}}
					</div>
				</div>
			</div>
		</div>
		<# } #>
		<?php
	}


}
