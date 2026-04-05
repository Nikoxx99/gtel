<?php
namespace SiliconElementor\Modules\IconBox\Skins;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor;
use Elementor\Skin_Base;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use SiliconElementor\Plugin;
use Elementor\Repeater;
use Elementor\Utils;
use SiliconElementor\Core\Utils as SNUtils;
use Elementor\Widget_Base;

/**
 * Skin IconBox Silicon
 */
class Skin_Icon_Box_V2 extends Skin_Base {

	/**
	 * Constructor.
	 *
	 * @param Elementor\Widget_Base $parent The widget settings.
	 * @return void
	 */
	public function __construct( Elementor\Widget_Base $parent ) {
		parent::__construct( $parent );
		add_filter( 'elementor/widget/print_template', array( $this, 'skin_print_template' ), 10, 2 );
		add_action( 'elementor/element/icon-box/section_icon/after_section_end', [ $this, 'add_content_control' ], 10 );
		add_action( 'elementor/element/icon-box/section_style_content/after_section_end', [ $this, 'modifying_style_sections' ], 10 );
	}

	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'si-icon-box-2';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Style 2', 'silicon-elementor' );
	}

	/**
	 * Added control of the Content tab.
	 */
	public function add_content_control() {

		$disable_controls = [
			// 'view',
			'link',
			// 'position',
			'selected_icon',
		];

		foreach ( $disable_controls as $control ) {
			$this->parent->update_control(
				$control,
				[
					'condition' => [
						'_skin!' => 'si-icon-box-2',
					],
				]
			);
		}

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => '_skin',
			]
		);

		$this->add_control(
			'icon',
			[
				'label'   => esc_html__( 'Icon', 'silicon-elementor' ),
				'type'    => Controls_Manager::ICONS,
				'default' => [
					'value' => 'bx bxs-star',
				],
			]
		);

		$this->parent->end_injection();

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => 'description_text',
			]
		);

		$this->add_control(
			'button',
			[
				'label'     => esc_html__( 'Button', 'silicon-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_type',
			[
				'label'   => esc_html__( 'Type', 'silicon-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'link',
				'options' => [
					'primary'   => esc_html__( 'Primary', 'silicon-elementor' ),
					'secondary' => esc_html__( 'Secondary', 'silicon-elementor' ),
					'success'   => esc_html__( 'Success', 'silicon-elementor' ),
					'danger'    => esc_html__( 'Danger', 'silicon-elementor' ),
					'warning'   => esc_html__( 'Warning', 'silicon-elementor' ),
					'info'      => esc_html__( 'Info', 'silicon-elementor' ),
					'light'     => esc_html__( 'Light', 'silicon-elementor' ),
					'dark'      => esc_html__( 'Dark', 'silicon-elementor' ),
					'link'      => esc_html__( 'Link', 'silicon-elementor' ),
				],
			]
		);

		$this->add_control(
			'button_variant',
			[
				'label'   => esc_html__( 'Variant', 'silicon-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''         => esc_html__( 'Default', 'silicon-elementor' ),
					'outline'  => esc_html__( 'Outline', 'silicon-elementor' ),
					'active'   => esc_html__( 'Active', 'silicon-elementor' ),
					'disabled' => esc_html__( 'Disabled', 'silicon-elementor' ),
				],
			]
		);

		$this->add_control(
			'button_shape',
			[
				'label'   => esc_html__( 'Shape', 'silicon-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''             => esc_html__( 'Default', 'silicon-elementor' ),
					'rounded-pill' => esc_html__( 'Pill', 'silicon-elementor' ),
					'rounded-0'    => esc_html__( 'Square', 'silicon-elementor' ),
				],

			]
		);

		$this->add_control(
			'button_size',
			[
				'label'   => esc_html__( 'Size', 'silicon-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''   => esc_html__( 'Default', 'silicon-elementor' ),
					'sm' => esc_html__( 'Small', 'silicon-elementor' ),
					'lg' => esc_html__( 'Large', 'silicon-elementor' ),
				],

			]
		);

		$this->add_control(
			'enable_shadow',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Shadow', 'silicon-elementor' ),
				'default'   => 'no',
				'label_off' => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Disable', 'silicon-elementor' ),

			]
		);

		$this->add_control(
			'button_text',
			[
				'label'   => esc_html__( 'Button Text', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Learn more', 'silicon-elementor' ),

			]
		);

		$this->add_control(
			'button_link',
			[
				'label' => esc_html__( 'Link', 'silicon-elementor' ),
				'type'  => Controls_Manager::URL,

			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label'            => esc_html__( 'Button Icon', 'silicon-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin'             => 'inline',
				'label_block'      => false,
				'default'          => [
					'value' => 'bx bx-right-arrow-alt',
				],
			]
		);

		$this->parent->end_injection();
	}

	/**
	 * Update control of the style tab.
	 *
	 * @param Elementor\Widget_Base $widget The widget settings.
	 */
	public function modifying_style_sections( Widget_Base $widget ) {
		$this->parent = $widget;
		$disable_controls = [
			'section_style_icon',
			'text_align',
			'content_vertical_alignment',
			'title_bottom_space',
			'description_shadow',
			'text_stroke',
			'title_shadow',
		];

		foreach ( $disable_controls as $control ) {
			$this->parent->update_control(
				$control,
				[
					'condition' => [
						'_skin!' => [ 'si-icon-box-2', 'si-icon-box' ],
					],
				]
			);
		}

		$this->parent->update_control(
			'title_color',
			[
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-box-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .sn-title' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->parent->update_control(
			'title_typography_typography',
			[

				'selector' => [ '{{WRAPPER}} .elementor-icon-box-title' ],
				'selector' => [

					'{{WRAPPER}} .sn-title',
				],

			]
		);

		$this->parent->update_control(
			'description_color',
			[
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-box-description' => 'color: {{VALUE}};',
					'{{WRAPPER}} .sn-description' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->parent->update_control(
			'description_typography_typography',
			[

				'selector' => [ '{{WRAPPER}} .elementor-icon-box-description' ],
				'selector' => [

					'{{WRAPPER}} .sn-description',
				],

			]
		);

		$this->parent->start_injection(
			[
				'of' => 'section_style_content',
				'at' => 'before',
			]
		);

		$this->start_controls_section(
			'section_style_2_icon',
			[
				'label' => esc_html__( 'Icon', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'primary_color',
			[
				'label'     => esc_html__( 'Backgroud Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .sn-elementor-icon-box-icon' => 'background-color: {{VALUE}} !important;',
				],
				'default'   => '#f3f6ff',
			]
		);

		$this->add_control(
			'secondary_color',
			[
				'label'     => esc_html__( 'Icon Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .sn-elementor-icon' => 'color: {{VALUE}} !important;',
				],
				'default'   => '#6366f1',
			]
		);

		$this->add_control(
			'icon_size',
			[
				'label'     => esc_html__( 'Size', 'silicon-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'default'   => [
					'size' => 28,
				],
				'selectors' => [
					'{{WRAPPER}} .sn-elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_class',
			[
				'label'       => esc_html__( 'Icon Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'title'       => esc_html__( 'Add your custom class for icon  without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'     => ' text-primary d-inline-block rounded-circle p-3',
				'label_block' => true,
				'description' => esc_html__( 'Additional CSS class that you want to apply to the icon', 'silicon-elementor' ),
			]
		);

		$this->parent->end_injection();

		$this->parent->start_injection(
			[
				'of' => 'section_style_content',
			]
		);
		$this->add_control(
			'heading_card',
			[
				'label'     => esc_html__( 'Card', 'silicon-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'card_class',
			[
				'label'       => esc_html__( 'Card Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'mb-3',
				'description' => esc_html__( 'Additional CSS class that you want to apply to the title', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'show_hover',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'CardHover', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Hide', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Show', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'card_body_class',
			[
				'label'       => esc_html__( 'Cardbody Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'd-flex align-items-start',
				'description' => esc_html__( 'Additional CSS class that you want to apply to the title', 'silicon-elementor' ),
			]
		);

		$this->parent->end_injection();

		$this->parent->start_injection(
			[
				'of' => 'heading_title',
			]
		);

		$this->add_control(
			'title_class',
			[
				'label'       => esc_html__( 'Title Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'h4 pb-1 mb-2',
				'description' => esc_html__( 'Additional CSS class that you want to apply to the title', 'silicon-elementor' ),
			]
		);

		$this->parent->end_injection();

		$this->parent->start_injection(
			[
				'of' => 'heading_description',
			]
		);

		$this->add_control(
			'description_class',
			[
				'label'       => esc_html__( 'Description Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'pb-1 mb-2',
				'description' => esc_html__( 'Additional CSS class that you want to apply to the description', 'silicon-elementor' ),
			]
		);

		$this->parent->end_injection();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button',
			[
				'label' => esc_html__( 'Button', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'label'    => __( 'Typography', 'silicon-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .sn-button',
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sn-button' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'button_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sn-button' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sn-button' => 'border-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label'     => esc_html__( 'Hover Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sn-button:hover, {{WRAPPER}} .sn-button-hover:focus' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'button_css',
			[
				'label'     => esc_html__( 'CSS Class', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'title'     => esc_html__( 'Add your custom class for text without the dot. e.g: my-class', 'silicon-elementor' ),
				'separator' => 'after',
				'default'   => 'fs-base px-0',
			]
		);

		$this->add_control(
			'button_icon_class',
			[
				'label'       => esc_html__( 'Icon Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'title'       => esc_html__( 'Add your custom class for <h> tag  without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'     => 'ms-1',
				'label_block' => true,

			]
		);

		$this->add_control(
			'button_icon_color',
			[
				'label'     => esc_html__( 'Button Icon Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .si-elementor-icon' => 'color: {{VALUE}} !important;',
				],
				'default'   => '#6366f1',
			]
		);

		$this->add_control(
			'button_icon_size',
			[
				'label'     => esc_html__( 'Size', 'silicon-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 300,
					],
				],
				'default'   => [
					'size' => 24,
				],
				'selectors' => [
					'{{WRAPPER}} .si-elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	public function render() {

		$parent   = $this->parent;
		$settings = $parent->get_settings_for_display();

		$skin_control_ids = [
			'card_class',
			'card_body_class',
			'show_hover',
			'button_css',
			'button_icon_class',
			'title_class',
			'description_class',
			'icon',
			'icon_class',
			'selected_icon',
			'button_text',
			'button_type',
			'button_size',
			'button_shape',
			'button_link',
			'button_css',
			'enable_shadow',
		];

		$skin_settings = [];

		foreach ( $skin_control_ids as $skin_control_id ) {
			$skin_settings[ $skin_control_id ] = $this->get_instance_value( $skin_control_id );
		}

		$card_class = [ 'card' ];

		if ( 'yes' === $skin_settings['show_hover'] ) {
				$card_class[] = 'card-hover';
		}

		if ( $skin_settings['card_class'] ) {
			$card_class[] = $skin_settings['card_class'];
		}

		$parent->add_render_attribute(
			'card_attribute',
			[
				'class' => $card_class,

			]
		);

		$card_body_class = [ 'card-body' ];

		if ( $skin_settings['card_body_class'] ) {
			$card_body_class[] = $skin_settings['card_body_class'];
		}

		$parent->add_render_attribute(
			'card_body_attribute',
			[
				'class' => $card_body_class,

			]
		);

		$icon_class   = [ 'sn-elementor-icon-box-icon', 'sn-elementor-icon' ];
		$icon_class[] = $skin_settings['icon_class'];
		$icon_class[] = $skin_settings['icon']['value'];

		$parent->add_render_attribute(
			'icon_attribute',
			[

				'class' => $icon_class,

			]
		);

		$button_icon_class   = [ 'si-elementor-icon' ];
		$button_icon_class[] = $skin_settings['button_icon_class'];
		$button_icon_class[] = $skin_settings['selected_icon']['value'];

		$parent->add_render_attribute(
			'button_icon_attribute',
			[

				'class' => $button_icon_class,

			]
		);

		$title_class   = [ 'sn-title', 'elementor-icon-box-title' ];
		$title_class[] = $skin_settings['title_class'];

		$parent->add_render_attribute(
			'title_attribute',
			[
				'class' => $title_class,

			]
		);

		$description_class   = [ 'sn-description', 'elementor-icon-box-description' ];
		$description_class[] = $skin_settings['description_class'];

		$parent->add_render_attribute(
			'description_attribute',
			[
				'class' => $description_class,

			]
		);

			$button_class = [ 'btn', 'sn-button' ];
		if ( $skin_settings['button_type'] ) {
			$button_class[] = 'btn-' . $skin_settings['button_type'];
		}
		if ( $skin_settings['button_size'] ) {
			$button_class[] = 'btn-' . $skin_settings['button_size'];
		}
		if ( 'yes' === $skin_settings['enable_shadow'] ) {
			$button_class[] = 'shadow-' . $skin_settings['button_type'];
		}
		if ( $skin_settings['button_shape'] ) {
			$button_class[] = $skin_settings['button_shape'];
		}
		if ( $skin_settings['button_css'] ) {
			$button_class[] = $skin_settings['button_css'];
		}

			$parent->add_render_attribute(
				'button_attribute',
				[
					'href'  => ! empty( $skin_settings['button_link']['url'] ) ? $skin_settings['button_link']['url'] : '#',
					'class' => $button_class,

				]
			);

		?>
		<div <?php $parent->print_render_attribute_string( 'card_attribute' ); ?>>
			  <div <?php $parent->print_render_attribute_string( 'card_body_attribute' ); ?>>
				<i <?php $parent->print_render_attribute_string( 'icon_attribute' ); ?>></i>
				<div class="ps-4">
				  <<?php echo esc_html( $settings['title_size'] ); ?> 
					<?php $parent->print_render_attribute_string( 'title_attribute' ); ?>><?php echo esc_html( $settings['title_text'] ); ?>
				  </<?php echo esc_html( $settings['title_size'] ); ?>>
				  <p <?php $parent->print_render_attribute_string( 'description_attribute' ); ?>><?php echo wp_kses_post( $settings['description_text'] ); ?></p>
				  <a <?php $parent->print_render_attribute_string( 'button_attribute' ); ?>>
					<?php echo wp_kses_post( $skin_settings['button_text'] ); ?>
					<i <?php $parent->print_render_attribute_string( 'button_icon_attribute' ); ?>></i>
				  </a>
				</div>
			  </div>
			</div>
			<?php

	}

	/**
	 * Skin print template.
	 *
	 * @param array $content the content.
	 * @param array $widget widget name.
	 * @return string
	 */
	public function skin_print_template( $content, $widget ) {
		if ( 'icon-box' === $widget->get_name() ) {
			return '';
		}
		return $content;
	}

}
