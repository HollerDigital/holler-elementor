<?php
/**
 * Holler Conveyor Widget
 */
class Holler_Conveyor_Widget extends \Elementor\Widget_Base {

	public function get_name() { return 'holler-conveyor'; }
	public function get_title() { return __( 'Holler Conveyor', 'holler-elementor' ); }
	public function get_icon() { return 'eicon-carousel'; }
	public function get_categories() { return [ 'holler' ]; }

	public function get_style_depends() {
		return [ 'holler-elementor' ];
	}

	public function get_script_depends() {
		return [ 'holler-elementor' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Items', 'holler-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();
		$repeater->add_control(
			'item_text',
			[
				'label' => __( 'Text', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [ 'active' => true ],
				'default' => __( 'Ticker item', 'holler-elementor' ),
			]
		);
		$repeater->add_control(
			'item_icon',
			[
				'label' => __( 'Icon', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [ 'value' => '', 'library' => 'fa-solid' ],
			]
		);
		$repeater->add_control(
			'item_link',
			[
				'label' => __( 'Link', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::URL,
				'dynamic' => [ 'active' => true ],
				'default' => [ 'url' => '', 'is_external' => true, 'nofollow' => true ],
			]
		);

		$this->add_control(
			'items',
			[
				'label' => __( 'Ticker Items', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[ 'item_text' => __( 'Latest update one', 'holler-elementor' ) ],
					[ 'item_text' => __( 'Another news item', 'holler-elementor' ) ],
					[ 'item_text' => __( 'Helpful tip goes here', 'holler-elementor' ) ],
					[ 'item_text' => __( 'Feature announcement', 'holler-elementor' ) ],
					[ 'item_text' => __( 'Follow us on social', 'holler-elementor' ) ],
				],
			]
		);

		$this->end_controls_section();

		// Behavior
		$this->start_controls_section(
			'behavior_section',
			[
				'label' => __( 'Behavior', 'holler-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'anim_duration',
			[
				'label' => __( 'Animation duration (ms per 10px)', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 200,
				'min' => 10,
				'max' => 1000,
			]
		);
		$this->add_control(
			'reverse_elm',
			[
				'label' => __( 'Reverse direction', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'no',
				'description' => __( 'Scroll right-to-left when enabled.', 'holler-elementor' ),
			]
		);
		$this->add_control(
			'force_loop',
			[
				'label' => __( 'Force Loop', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);
		$this->add_control(
			'start_paused',
			[
				'label' => __( 'Start Paused', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'no',
			]
		);
		$this->add_control(
			'pause_hover',
			[
				'label' => __( 'Pause on Hover', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);
		$this->add_control(
			'link',
			[
				'label' => __( 'Widget Link (whole section)', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::URL,
				'dynamic' => [ 'active' => true ],
				'default' => [ 'url' => '', 'is_external' => true, 'nofollow' => true ],
				'description' => __( 'If set, the entire conveyor becomes a single link and item-level links are disabled.', 'holler-elementor' ),
			]
		);
		$this->add_control(
			'item_gap',
			[
				'label' => __( 'Item Gap', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
				'default' => [ 'size' => 24, 'unit' => 'px' ],
				'selectors' => [
					'{{WRAPPER}} .holler-conveyor .holler-conveyor-item' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'show_separator',
			[
				'label' => __( 'Show Separator', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'separator_text',
			[
				'label' => __( 'Separator Text', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '•',
				'condition' => [ 'show_separator' => 'yes' ],
			]
		);
		$this->add_control(
			'track_height',
			[
				'label' => __( 'Track Height', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [ 'px' => [ 'min' => 0, 'max' => 200 ] ],
				'default' => [ 'size' => 0, 'unit' => 'px' ],
			]
		);
		$this->end_controls_section();

		// Style
		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Style', 'holler-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'items_typography',
				'selector' => '{{WRAPPER}} .holler-conveyor .holler-conveyor-item-text',
			]
		);
		$this->add_control(
			'items_color',
			[
				'label' => __( 'Text Color', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .holler-conveyor' => '--holler-conveyor-text: {{VALUE}};',
				],
				'default' => '#222222',
			]
		);

		// Icon controls
		$this->add_control(
			'icon_position',
			[
				'label' => __( 'Icon Position', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [ 'title' => __( 'Left', 'holler-elementor' ), 'icon' => 'eicon-h-align-left' ],
					'right' => [ 'title' => __( 'Right', 'holler-elementor' ), 'icon' => 'eicon-h-align-right' ],
				],
				'toggle' => false,
				'default' => 'left',
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Icon Size', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range' => [
					'px' => [ 'min' => 8, 'max' => 96 ],
					'em' => [ 'min' => 0.5, 'max' => 6 ],
					'rem' => [ 'min' => 0.5, 'max' => 6 ],
				],
				'selectors' => [
					'{{WRAPPER}} .holler-conveyor .holler-conveyor-icon-wrap' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_gap',
			[
				'label' => __( 'Icon Gap', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range' => [
					'px' => [ 'min' => 0, 'max' => 60 ],
					'em' => [ 'min' => 0, 'max' => 4 ],
					'rem' => [ 'min' => 0, 'max' => 4 ],
				],
				'default' => [ 'size' => 0.5, 'unit' => 'em' ],
				'selectors' => [
					'{{WRAPPER}} .holler-conveyor' => '--holler-conveyor-icon-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Icon Color', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .holler-conveyor .holler-conveyor-icon' => 'color: {{VALUE}}; fill: currentColor;',
				],
			]
		);
		$this->add_control(
			'track_bg',
			[
				'label' => __( 'Track Background', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .holler-conveyor .holler-conveyor-track' => 'background-color: {{VALUE}};',
				],
				'default' => 'transparent',
			]
		);
		$this->add_responsive_control(
			'track_padding',
			[
				'label' => __( 'Track Padding', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .holler-conveyor .holler-conveyor-track' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'track_radius',
			[
				'label' => __( 'Track Border Radius', 'holler-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
				'default' => [ 'size' => 0, 'unit' => 'px' ],
				'selectors' => [
					'{{WRAPPER}} .holler-conveyor .holler-conveyor-track' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$widget_id = $this->get_id();
		$rendered = holler_memory_safe_render('_holler_conveyor_template', [$settings, $widget_id], '<div class="holler-error">Unable to display conveyor.</div>');
		echo $rendered;
	}

	protected function content_template() {
		?>
		<#
		var items = settings.items || [];
		var iconPos = settings.icon_position || 'left';
		var isReversed = settings.reverse_elm === 'yes';
		var startPaused = settings.start_paused === 'yes';
		var pauseHover = settings.pause_hover === 'yes';
		var itemGap = (settings.item_gap && settings.item_gap.size) ? settings.item_gap.size : 24;
		var showSep = settings.show_separator === 'yes';
		var sepText = settings.separator_text || '•';
		var trackHeight = (settings.track_height && settings.track_height.size) ? settings.track_height.size : 0;
		var animDuration = Number(settings.anim_duration) || 200; // ms per 10px -> approximate seconds
		var durationSeconds = Math.max(5, Math.min(120, Math.round(animDuration / 10)));
		#>
		<div class="holler-widget holler-conveyor holler-marquee {{ iconPos === 'right' ? 'icon-right' : 'icon-left' }} {{ isReversed ? 'is-reversed' : '' }}"
			 style="--marquee-duration: {{ durationSeconds }}s; --spaceBetweenItems: {{ itemGap }}px; --marquee-animation-state: {{ startPaused ? 'paused' : 'running' }}; {{ trackHeight > 0 ? ('--marquee-track-height: ' + trackHeight + 'px;') : '' }}"
			 data-pause-hover="{{ pauseHover ? '1' : '0' }}">
			<div class="holler-marquee-track" role="marquee" aria-live="off">
				<# var is_widget_linked = settings.link && settings.link.url; #>
				<# if (is_widget_linked) { #>
					<a class="holler-conveyor-widget-link" href="{{ settings.link.url }}">
				<# } #>
				<div class="holler-marquee-run" data-marquee-animation="{{ isReversed ? 'left' : 'right' }}">
					<# _.each(items, function(item, idx){ #>
						<span class="holler-marquee-unit">
							<# var is_widget_linked = settings.link && settings.link.url; #>
							<# if (!is_widget_linked && item.item_link && item.item_link.url) { #>
								<a class="holler-conveyor-link" href="{{ item.item_link.url }}">
							<# } #>
								<# if (iconPos !== 'right') { #>
									<# if (item.item_icon && item.item_icon.value) { #>
										<span class="holler-conveyor-icon-wrap"><i class="{{ item.item_icon.value }} holler-conveyor-icon" aria-hidden="true"></i></span>
									<# } #>
									<span class="holler-conveyor-item-text">{{{ item.item_text }}}</span>
								<# } else { #>
									<span class="holler-conveyor-item-text">{{{ item.item_text }}}</span>
									<# if (item.item_icon && item.item_icon.value) { #>
										<span class="holler-conveyor-icon-wrap"><i class="{{ item.item_icon.value }} holler-conveyor-icon" aria-hidden="true"></i></span>
									<# } #>
								<# } #>
							<# if (item.item_link && item.item_link.url) { #>
								</a>
							<# } #>
						</span>
						<# if (showSep && idx < items.length - 1) { #>
							<span class="holler-marquee-sep" aria-hidden="true">{{ sepText }}</span>
						<# } #>
					<# }); #>
				</div>
				<div class="holler-marquee-run" data-marquee-animation="{{ isReversed ? 'left' : 'right' }}" aria-hidden="true">
					<# _.each(items, function(item, idx){ #>
						<span class="holler-marquee-unit">
							<# var is_widget_linked = settings.link && settings.link.url; #>
							<# if (!is_widget_linked && item.item_link && item.item_link.url) { #>
								<a class="holler-conveyor-link" href="{{ item.item_link.url }}">
							<# } #>
								<# if (iconPos !== 'right') { #>
									<# if (item.item_icon && item.item_icon.value) { #>
										<span class="holler-conveyor-icon-wrap"><i class="{{ item.item_icon.value }} holler-conveyor-icon" aria-hidden="true"></i></span>
									<# } #>
									<span class="holler-conveyor-item-text">{{{ item.item_text }}}</span>
								<# } else { #>
									<span class="holler-conveyor-item-text">{{{ item.item_text }}}</span>
									<# if (item.item_icon && item.item_icon.value) { #>
										<span class="holler-conveyor-icon-wrap"><i class="{{ item.item_icon.value }} holler-conveyor-icon" aria-hidden="true"></i></span>
									<# } #>
								<# } #>
							<# if (item.item_link && item.item_link.url) { #>
								</a>
							<# } #>
						</span>
						<# if (showSep && idx < items.length - 1) { #>
							<span class="holler-marquee-sep" aria-hidden="true">{{ sepText }}</span>
						<# } #>
					<# }); #>
				</div>
				<# if (is_widget_linked) { #>
					</a>
				<# } #>
			</div>
		</div>
		<?php
	}
}
