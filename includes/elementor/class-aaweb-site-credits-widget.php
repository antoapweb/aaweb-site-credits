<?php
/**
 * Elementor widget.
 *
 * @package AAWEB_Site_Credits
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AAWEB Site Credits Elementor widget.
 */
class AAWEB_Site_Credits_Elementor_Widget extends \Elementor\Widget_Base {

	/**
	 * Widget name.
	 *
	 * @return string
	 */
	public function get_name(): string {
		return 'aaweb_site_credits';
	}

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title(): string {
		return esc_html__( 'AAWEB Site Credits', 'aaweb-site-credits' );
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon(): string {
		return 'eicon-code';
	}

	/**
	 * Widget categories.
	 *
	 * @return array<int,string>
	 */
	public function get_categories(): array {
		return array( 'aaweb-widgets', 'general' );
	}

	/**
	 * Widget keywords.
	 *
	 * @return array<int,string>
	 */
	public function get_keywords(): array {
		return array( 'aaweb', 'credits', 'copyright', 'footer', 'powered by', 'year' );
	}

	/**
	 * Style dependencies.
	 *
	 * @return array<int,string>
	 */
	public function get_style_depends(): array {
		return array( 'aaweb-site-credits' );
	}

	/**
	 * Register controls.
	 *
	 * @return void
	 */
	protected function register_controls(): void {
		$defaults = AAWEB_Site_Credits_Core::get_options();

		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'aaweb-site-credits' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'copyright_text',
			array(
				'label'       => esc_html__( 'Copyright Text', 'aaweb-site-credits' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => $defaults['copyright_text'],
				'placeholder' => esc_html__( 'Copyright', 'aaweb-site-credits' ),
			)
		);

		$this->add_control(
			'show_symbol',
			array(
				'label'        => esc_html__( 'Show © Symbol', 'aaweb-site-credits' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'aaweb-site-credits' ),
				'label_off'    => esc_html__( 'No', 'aaweb-site-credits' ),
				'return_value' => '1',
				'default'      => $defaults['show_symbol'],
			)
		);

		$this->add_control(
			'site_name',
			array(
				'label'       => esc_html__( 'Site / Company Name', 'aaweb-site-credits' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => $defaults['site_name'],
				'placeholder' => get_bloginfo( 'name' ),
			)
		);

		$this->add_control(
			'rights_text',
			array(
				'label'       => esc_html__( 'Rights Text', 'aaweb-site-credits' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => $defaults['rights_text'],
				'placeholder' => esc_html__( 'All Rights Reserved.', 'aaweb-site-credits' ),
			)
		);

		$this->add_control(
			'separator',
			array(
				'label'       => esc_html__( 'Separator', 'aaweb-site-credits' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => $defaults['separator'],
				'placeholder' => '|',
			)
		);

		$this->add_control(
			'enable_credit',
			array(
				'label'        => esc_html__( 'Display Developer Credit', 'aaweb-site-credits' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'aaweb-site-credits' ),
				'label_off'    => esc_html__( 'No', 'aaweb-site-credits' ),
				'return_value' => '1',
				'default'      => '0',
				'description'  => esc_html__( 'Developer credit is optional and disabled by default.', 'aaweb-site-credits' ),
			)
		);

		$this->add_control(
			'powered_by_text',
			array(
				'label'       => esc_html__( 'Powered By Text', 'aaweb-site-credits' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => esc_html__( 'Powered by', 'aaweb-site-credits' ),
			)
		);

		$this->add_control(
			'developer_name',
			array(
				'label'       => esc_html__( 'Developer / Brand Name', 'aaweb-site-credits' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => esc_html__( 'AntoApWeb', 'aaweb-site-credits' ),
			)
		);

		$this->add_control(
			'developer_url',
			array(
				'label'       => esc_html__( 'Developer / Brand URL', 'aaweb-site-credits' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'placeholder' => 'https://antoapweb.gr',
				'default'     => array(
					'url'         => $defaults['developer_url'],
					'is_external' => '1' === $defaults['open_new_tab'],
					'nofollow'    => false,
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_layout',
			array(
				'label' => esc_html__( 'Layout', 'aaweb-site-credits' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => esc_html__( 'Alignment', 'aaweb-site-credits' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'aaweb-site-credits' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'aaweb-site-credits' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'aaweb-site-credits' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .aaweb-site-credits' => 'justify-content: {{VALUE}}; text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'      => esc_html__( 'Items Gap', 'aaweb-site-credits' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 40,
					),
					'em' => array(
						'min' => 0,
						'max' => 3,
					),
				),
				'default'    => array(
					'size' => 6,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .aaweb-site-credits' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'wrap',
			array(
				'label'     => esc_html__( 'Wrap on Small Screens', 'aaweb-site-credits' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'wrap',
				'options'   => array(
					'wrap'   => esc_html__( 'Wrap', 'aaweb-site-credits' ),
					'nowrap' => esc_html__( 'No Wrap', 'aaweb-site-credits' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .aaweb-site-credits' => 'flex-wrap: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_text',
			array(
				'label' => esc_html__( 'Text Style', 'aaweb-site-credits' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography',
				'selector' => '{{WRAPPER}} .aaweb-site-credits',
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'aaweb-site-credits' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .aaweb-site-credits' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'site_name_color',
			array(
				'label'     => esc_html__( 'Main Text Color', 'aaweb-site-credits' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .aaweb-site-credits__left' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'separator_color',
			array(
				'label'     => esc_html__( 'Separator Color', 'aaweb-site-credits' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .aaweb-site-credits__separator' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'powered_color',
			array(
				'label'     => esc_html__( 'Powered Text Color', 'aaweb-site-credits' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .aaweb-site-credits__powered' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_link',
			array(
				'label' => esc_html__( 'Developer Link Style', 'aaweb-site-credits' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'tabs_link_style' );

		$this->start_controls_tab( 'tab_link_normal', array( 'label' => esc_html__( 'Normal', 'aaweb-site-credits' ) ) );

		$this->add_control(
			'link_color',
			array(
				'label'     => esc_html__( 'Link Color', 'aaweb-site-credits' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .aaweb-site-credits a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'link_decoration',
			array(
				'label'     => esc_html__( 'Decoration', 'aaweb-site-credits' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'none',
				'options'   => array(
					'none'      => esc_html__( 'None', 'aaweb-site-credits' ),
					'underline' => esc_html__( 'Underline', 'aaweb-site-credits' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .aaweb-site-credits a' => 'text-decoration: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'tab_link_hover', array( 'label' => esc_html__( 'Hover', 'aaweb-site-credits' ) ) );

		$this->add_control(
			'link_hover_color',
			array(
				'label'     => esc_html__( 'Hover Color', 'aaweb-site-credits' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .aaweb-site-credits a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'link_hover_decoration',
			array(
				'label'     => esc_html__( 'Hover Decoration', 'aaweb-site-credits' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'underline',
				'options'   => array(
					'none'      => esc_html__( 'None', 'aaweb-site-credits' ),
					'underline' => esc_html__( 'Underline', 'aaweb-site-credits' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .aaweb-site-credits a:hover' => 'text-decoration: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_box',
			array(
				'label' => esc_html__( 'Box Style', 'aaweb-site-credits' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'padding',
			array(
				'label'      => esc_html__( 'Padding', 'aaweb-site-credits' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .aaweb-site-credits' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'margin',
			array(
				'label'      => esc_html__( 'Margin', 'aaweb-site-credits' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .aaweb-site-credits' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'aaweb-site-credits' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .aaweb-site-credits' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'border',
				'selector' => '{{WRAPPER}} .aaweb-site-credits',
			)
		);

		$this->add_responsive_control(
			'border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'aaweb-site-credits' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .aaweb-site-credits' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_shadow',
				'selector' => '{{WRAPPER}} .aaweb-site-credits',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget.
	 *
	 * @return void
	 */
	protected function render(): void {
		$settings = $this->get_settings_for_display();

		$developer_url = '';
		$open_new_tab  = '0';

		if ( ! empty( $settings['developer_url']['url'] ) ) {
			$developer_url = esc_url_raw( $settings['developer_url']['url'] );
			$open_new_tab  = ! empty( $settings['developer_url']['is_external'] ) ? '1' : '0';
		}

		$render_settings = array(
			'copyright_text'  => sanitize_text_field( $settings['copyright_text'] ?? 'Copyright' ),
			'show_symbol'     => sanitize_text_field( $settings['show_symbol'] ?? '1' ),
			'site_name'       => sanitize_text_field( $settings['site_name'] ?? '' ),
			'rights_text'     => sanitize_text_field( $settings['rights_text'] ?? '' ),
			'separator'       => sanitize_text_field( $settings['separator'] ?? '|' ),
			'enable_credit'   => ! empty( $settings['enable_credit'] ) ? '1' : '0',
			'powered_by_text' => sanitize_text_field( $settings['powered_by_text'] ?? '' ),
			'developer_name'  => sanitize_text_field( $settings['developer_name'] ?? '' ),
			'developer_url'   => esc_url_raw( $developer_url ),
			'open_new_tab'    => sanitize_text_field( $open_new_tab ),
		);

		$render_args = array(
			'align' => sanitize_key( $settings['align'] ?? 'center' ),
		);

		echo wp_kses_post( AAWEB_Site_Credits_Core::render_output( $render_settings, $render_args ) );
	}
}
