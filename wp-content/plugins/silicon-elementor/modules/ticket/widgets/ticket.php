<?php
namespace SiliconElementor\Modules\Ticket\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use SiliconElementor\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Ticket Card Widget
 */
class Ticket extends Base_Widget {

	/**
	 * Get widget name.
	 *
	 * Retrieve button widget name.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'si-ticket-card';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve button widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Ticket Card', 'silicon-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve button widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-product-price';
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 1.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'ticket' ];
	}

	/**
	 * Register Controls.
	 *
	 * @return void
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_header',
			[
				'label' => esc_html__( 'Ticket Details', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'ticket_date',
			[
				'label'   => esc_html__( 'Date', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Oct 14+15', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'ticket_title',
			[
				'label'   => esc_html__( 'Title', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'NY Digital Conference', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'ticket_title_tag',
			[
				'label'   => esc_html__( 'Title HTML Tag', 'silicon-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
				],
				'default' => 'h3',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_qr',
			[
				'label' => esc_html__( 'QR Image', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'qr_image',
			[
				'label'   => esc_html__( 'QR Image', 'silicon-elementor' ),
				'type'    => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button',
			[
				'label' => esc_html__( 'Access Button', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'access_button_text',
			[
				'label'       => esc_html__( 'Button Text', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Start free trial', 'silicon-elementor' ),
				'placeholder' => esc_html__( 'Click here', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'access_button_link',
			[
				'label'       => esc_html__( 'Link', 'silicon-elementor' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'silicon-elementor' ),
				'default'     => [
					'url' => '#',
				],
			]
		);

		$this->add_control(
			'access_button_type',
			[
				'label'   => esc_html__( 'Type', 'silicon-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'primary',
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
			'access_button_variant',
			[
				'label'   => esc_html__( 'Variant', 'silicon-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'outline',
				'options' => [
					''        => esc_html__( 'Default', 'silicon-elementor' ),
					'outline' => esc_html__( 'Outline', 'silicon-elementor' ),
				],
			]
		);

		$this->add_control(
			'enable_access_shadow',
			[
				'label'        => esc_html__( 'Enable Shadow', 'silicon-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'silicon-elementor' ),
				'label_off'    => esc_html__( 'No', 'silicon-elementor' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'access_button_css_id',
			[
				'label'       => esc_html__( 'Button ID', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Please make sure the ID is unique and not used elsewhere on the page   this form is displayed. This field allows A-z 0-9 & underscore chars without spaces.', 'silicon-elementor' ),
				'label_block' => true,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_pricing',
			[
				'label' => esc_html__( 'Pricing', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'ticket_price_text',
			[
				'label'   => esc_html__( 'Price Sub Text', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'for only', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'currency_symbol',
			[
				'label'   => esc_html__( 'Currency Symbol', 'silicon-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					''             => esc_html__( 'None', 'silicon-elementor' ),
					'dollar'       => '&#36; ' . _x( 'Dollar', 'Currency Symbol', 'silicon-elementor' ),
					'euro'         => '&#128; ' . _x( 'Euro', 'Currency Symbol', 'silicon-elementor' ),
					'baht'         => '&#3647; ' . _x( 'Baht', 'Currency Symbol', 'silicon-elementor' ),
					'franc'        => '&#8355; ' . _x( 'Franc', 'Currency Symbol', 'silicon-elementor' ),
					'guilder'      => '&fnof; ' . _x( 'Guilder', 'Currency Symbol', 'silicon-elementor' ),
					'krona'        => 'kr ' . _x( 'Krona', 'Currency Symbol', 'silicon-elementor' ),
					'lira'         => '&#8356; ' . _x( 'Lira', 'Currency Symbol', 'silicon-elementor' ),
					'peseta'       => '&#8359 ' . _x( 'Peseta', 'Currency Symbol', 'silicon-elementor' ),
					'peso'         => '&#8369; ' . _x( 'Peso', 'Currency Symbol', 'silicon-elementor' ),
					'pound'        => '&#163; ' . _x( 'Pound Sterling', 'Currency Symbol', 'silicon-elementor' ),
					'real'         => 'R$ ' . _x( 'Real', 'Currency Symbol', 'silicon-elementor' ),
					'ruble'        => '&#8381; ' . _x( 'Ruble', 'Currency Symbol', 'silicon-elementor' ),
					'rupee'        => '&#8360; ' . _x( 'Rupee', 'Currency Symbol', 'silicon-elementor' ),
					'indian_rupee' => '&#8377; ' . _x( 'Rupee (Indian)', 'Currency Symbol', 'silicon-elementor' ),
					'shekel'       => '&#8362; ' . _x( 'Shekel', 'Currency Symbol', 'silicon-elementor' ),
					'yen'          => '&#165; ' . _x( 'Yen/Yuan', 'Currency Symbol', 'silicon-elementor' ),
					'won'          => '&#8361; ' . _x( 'Won', 'Currency Symbol', 'silicon-elementor' ),
					'custom'       => esc_html__( 'Custom', 'silicon-elementor' ),
				],
				'default' => 'dollar',
			]
		);

		$this->add_control(
			'currency_symbol_custom',
			[
				'label'     => esc_html__( 'Custom Symbol', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => [
					'currency_symbol' => 'custom',
				],
			]
		);

		$this->add_control(
			'ticket_price',
			[
				'label'   => esc_html__( 'Ticket Price', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '100',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'currency_format',
			[
				'label'   => esc_html__( 'Currency Format', 'silicon-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					''  => '1,234.56 (Default)',
					',' => '1.234,56',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_wrapper_style',
			[
				'label'      => esc_html__( 'Wrapper', 'silicon-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'background_wrapper_style',
			[
				'label' => esc_html__( 'Background', 'silicon-elementor' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'dark-version',
			[
				'label'              => esc_html__( 'Show dark ?', 'silicon-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'bg_image',
			[
				'label'   => esc_html__( 'Background Image', 'silicon-elementor' ),
				'type'    => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'dark-version' => 'yes',
				],
			]
		);

		$this->add_control(
			'bg_image_1',
			[
				'label'   => esc_html__( 'Image', 'silicon-elementor' ),
				'type'    => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'dark-version!' => 'yes',
				],
			]
		);

		$this->add_control(
			'bg_image_2',
			[
				'label'   => esc_html__( 'Image', 'silicon-elementor' ),
				'type'    => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'dark-version!' => 'yes',
				],
			]
		);

		$this->add_control(
			'background_wrapper_color',
			[
				'label'     => esc_html__( 'Background Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sn-ticket-bg' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'background_wrapper_class',
			[
				'label'     => esc_html__( 'Background Wrapper Class', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'bg-dark',
				'separator' => 'after',
			]
		);

		$this->add_control(
			'shadow_color_wrapper',
			[
				'label'     => esc_html__( 'Shadow Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sn-ticket-color' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'shadow_color_wrapper_class',
			[
				'label'     => esc_html__( 'Shadow Color Class', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'bg-dark',
				'separator' => 'after',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_header_style',
			[
				'label'      => esc_html__( 'Ticket Details', 'silicon-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'ticket_date_style',
			[
				'label' => esc_html__( 'Date', 'silicon-elementor' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'date_color',
			[
				'label'     => esc_html__( 'Date Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-ticket_date' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Date typography', 'silicon-elementor' ),
				'name'     => 'date_typography',
				'selector' => '{{WRAPPER}} .si-ticket_date',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->add_control(
			'ticket_date_class',
			[
				'label' => esc_html__( 'Date Class', 'silicon-elementor' ),
				'type'  => Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'ticket_title_style',
			[
				'label'     => esc_html__( 'Title', 'silicon-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Title Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-ticket_title' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Title typography', 'silicon-elementor' ),
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .si-ticket_title',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->add_control(
			'ticket_title_class',
			[
				'label' => esc_html__( 'Title Class', 'silicon-elementor' ),
				'type'  => Controls_Manager::TEXT,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_qr_style',
			[
				'label'      => esc_html__( 'QR Image', 'silicon-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'qr_image_width',
			[
				'label'      => esc_html__( 'Width', 'silicon-elementor' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => '%',
					'size' => 102,
				],
				'selectors'  => [
					'{{WRAPPER}} .si-width' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_footer_style',
			[
				'label'      => esc_html__( 'Access Button', 'silicon-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'ticket_button',
			[
				'label'     => esc_html__( 'Button', 'silicon-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'access_button_text!' => '',
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
			'border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'silicon-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .silicon-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label'     => esc_html__( 'Normal', 'silicon-elementor' ),
				'condition' => [
					'access_button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .si-ticket-button' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .si-ticket-button',
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => [
					'{{WRAPPER}} .si-ticket-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_css_class',
			[
				'label' => esc_html__( 'Button Class', 'silicon-elementor' ),
				'type'  => Controls_Manager::TEXT,
				'title' => esc_html__( 'Add your custom class for button. e.g: card-active', 'silicon-elementor' ),
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label'     => esc_html__( 'Hover', 'silicon-elementor' ),
				'condition' => [
					'access_button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label'     => esc_html__( 'Text Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-ticket-button:hover' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label'     => esc_html__( 'Background Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-ticket-button:hover' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-ticket-button:hover' => 'border-color: {{VALUE}} !important;',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Pricing Style Tab Start.
		$this->start_controls_section(
			'section_pricing_element_style',
			[
				'label'      => esc_html__( 'Pricing', 'silicon-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'ticket_sub_text_style',
			[
				'label' => esc_html__( 'Price Text', 'silicon-elementor' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'price_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-ticket_price_sub_text' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Text typography', 'silicon-elementor' ),
				'name'     => 'text_typography',
				'selector' => '{{WRAPPER}} .si-ticket_price_sub_text',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->add_control(
			'ticket_price_text_class',
			[
				'label'     => esc_html__( 'Price Sub Text Class', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'fs-lg',
				'separator' => 'after',
			]
		);

		$this->add_control(
			'ticket_price_style',
			[
				'label' => esc_html__( 'Price', 'silicon-elementor' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'price_color',
			[
				'label'     => esc_html__( 'Price Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-ticket_price' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Price typography', 'silicon-elementor' ),
				'name'     => 'price_typography',
				'selector' => '{{WRAPPER}} .si-ticket_price',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->add_control(
			'price_class',
			[
				'label' => esc_html__( 'Ticket Price Class', 'silicon-elementor' ),
				'type'  => Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'heading_currency_style',
			[
				'label'     => esc_html__( 'Currency Symbol', 'silicon-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',

			]
		);

		$this->add_control(
			'currency_symbol_color',
			[
				'label'     => esc_html__( 'Currency Symbol Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-elementor-ticket-card__currency' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'currency_position',
			[
				'label'   => esc_html__( 'Position', 'silicon-elementor' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'before',
				'options' => [
					'before' => [
						'title' => esc_html__( 'Before', 'silicon-elementor' ),
						'icon'  => 'eicon-h-align-left',
					],
					'after'  => [
						'title' => esc_html__( 'After', 'silicon-elementor' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
			]
		);

		// Pricing Style Tab End.
		$this->end_controls_section();

	}

	/**
	 * Get currency symbol.
	 *
	 * @param array $symbol_name currency symbol.
	 */
	public function get_currency_symbol( $symbol_name ) {
		$symbols = [
			'dollar'       => '&#36;',
			'euro'         => '&#128;',
			'franc'        => '&#8355;',
			'pound'        => '&#163;',
			'ruble'        => '&#8381;',
			'shekel'       => '&#8362;',
			'baht'         => '&#3647;',
			'yen'          => '&#165;',
			'won'          => '&#8361;',
			'guilder'      => '&fnof;',
			'peso'         => '&#8369;',
			'peseta'       => '&#8359',
			'lira'         => '&#8356;',
			'rupee'        => '&#8360;',
			'indian_rupee' => '&#8377;',
			'real'         => 'R$',
			'krona'        => 'kr',
		];

		return isset( $symbols[ $symbol_name ] ) ? $symbols[ $symbol_name ] : '';
	}

	/**
	 * Render currency symbol.
	 *
	 * @param array $symbol currency symbol.
	 * @param array $location currency location.
	 */
	public function render_currency_symbol( $symbol, $location ) {
		$currency_position = $this->get_settings( 'currency_position' );
		$location_setting  = ! empty( $currency_position ) ? $currency_position : 'before';
		if ( ! empty( $symbol ) && $location === $location_setting ) {
			echo wp_kses_post( '<span class="si-elementor-ticket-card__currency elementor-currency--' . $location . '">' . $symbol . '</span>' );
		}
	}

	/**
	 * Render Ticket card widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$bg_class = [ 'sn-ticket-bg', 'position-relative overflow-hidden rounded-3 zindex-5 py-5 px-4 p-sm-5' ];
		if ( $settings['background_wrapper_class'] ) {
			$bg_class[] = $settings['background_wrapper_class'];
		}

		$color_class = [ 'sn-ticket-color', 'position-absolute  bottom-0 mb-n2 d-dark-mode-none' ];
		if ( $settings['shadow_color_wrapper_class'] ) {
			$color_class[] = $settings['shadow_color_wrapper_class'];
		}

		$date_class = [ 'si-ticket_date lead fw-semibold text-uppercase mb-2' ];
		if ( $settings['ticket_date_class'] ) {
			$date_class[] = $settings['ticket_date_class'];
		}

		$title_class = [ 'si-ticket_title h1' ];
		if ( $settings['ticket_title_class'] ) {
			$title_class[] = $settings['ticket_title_class'];
		}

		$title_tag = $settings['ticket_title_tag'];

		$this->add_render_attribute( 'background_wrapper_class', 'class', $bg_class );
		$this->add_render_attribute( 'shadow_color_wrapper_class', 'class', $color_class );
		$this->add_render_attribute( 'ticket_date_class', 'class', $date_class );
		$this->add_render_attribute( 'ticket_title_class', 'class', $title_class );
		$this->add_render_attribute(
			'qr_image',
			array(
				'class' => 'si-ticket-qr',
				'src'   => $settings['qr_image']['url'],
				'width' => $settings['qr_image_width']['size'],
				'alt'   => 'QR Code',
			)
		);

		$button_classes = [ 'btn', 'si-ticket-button', 'mb-3 mb-sm-0 me-sm-3', 'silicon-button' ];

		if ( ! empty( $settings['access_button_type'] ) ) {
			if ( '' === $settings['access_button_variant'] ) {
				$button_classes[] = 'btn-' . $settings['access_button_type'];
			} elseif ( 'soft' === $settings['access_button_variant'] ) {
				$button_classes[] = 'btn-' . $settings['access_button_type'] . '-' . $settings['access_button_variant'];
			} elseif ( 'outline' === $settings['access_button_variant'] ) {
				$button_classes[] = 'btn-' . $settings['access_button_variant'] . '-' . $settings['access_button_type'];
			}
		}

		if ( ! empty( $settings['button_css_class'] ) ) {
			$button_classes[] = $settings['button_css_class'];
		}

		if ( ! empty( $settings['button_size'] ) ) {
			$button_classes[] = 'btn-' . $settings['button_size'];
		}

		if ( 'yes' === $settings['enable_access_shadow'] ) {
			$this->add_render_attribute( 'access_button_text', 'class', 'shadow-' . $settings['access_button_type'] );
		}

		$this->add_render_attribute( 'access_button_text', 'class', $button_classes );

		$this->add_inline_editing_attributes( 'access_button_text' );

		if ( ! empty( $settings['access_button_link']['url'] ) ) {
			$this->add_link_attributes( 'access_button_text', $settings['access_button_link'] );
		}

		if ( ! empty( $settings['access_button_css_id'] ) ) {
			$this->add_render_attribute( 'access_button_text', 'id', $settings['access_button_css_id'] );
		}

		$price_sub_text_class = [ 'si-ticket_price_sub_text', 'me-2' ];
		if ( $settings['ticket_price_text_class'] ) {
			$price_sub_text_class[] = $settings['ticket_price_text_class'];
		}

		$this->add_render_attribute( 'ticket_price_text_class', 'class', $price_sub_text_class );

		$symbol = '';

		if ( ! empty( $settings['currency_symbol'] ) ) {
			if ( 'custom' !== $settings['currency_symbol'] ) {
				$symbol = $this->get_currency_symbol( $settings['currency_symbol'] );
			} else {
				$symbol = $settings['currency_symbol_custom'];
			}
		}

		$currency_format = empty( $settings['currency_format'] ) ? '.' : $settings['currency_format'];
		$price           = explode( $currency_format, $settings['ticket_price'] );

		$intpart = $price[0];

		$price_class = [ 'si-ticket_price', 'h4 mb-0' ];
		if ( $settings['price_class'] ) {
			$price_class[] = $settings['price_class'];
		}

		$this->add_render_attribute( 'price_class', 'class', $price_class );
		?>
		<div class="position-relative">
			<div <?php $this->print_render_attribute_string( 'background_wrapper_class' ); ?>>
				<?php
				if ( 'yes' === $settings['dark-version'] ) {
					?>
					<span class="position-absolute top-50 start-0 translate-middle bg-light rounded-circle p-4"></span>
					<span class="position-absolute top-0 start-0 w-100 h-100 bg-repeat-0 bg-position-center-end bg-size-cover" style="background-image: url( <?php echo esc_url( $settings['bg_image']['url'] ); ?> )"></span>
					<?php
				} else {
					?>
					<span class="position-absolute top-0 start-0 w-100 h-100 bg-repeat-0 bg-position-center-start zindex-2" style="background-image: url( <?php echo esc_url( $settings['bg_image_1']['url'] ); ?> )"></span>
					<span class="position-absolute top-0 end-0 w-100 h-100 bg-repeat-0 bg-position-center-end zindex-2" style="background-image: url( <?php echo esc_url( $settings['bg_image_2']['url'] ); ?> )"></span>
					<?php
				}
				?>
				<div class="px-md-4 position-relative zindex-5">
					<div class="d-sm-flex align-items-start justify-content-between">
						<div class="text-center text-sm-start me-sm-4">
							<?php if ( ! empty( $settings['ticket_date'] ) ) : ?>
								<div <?php $this->print_render_attribute_string( 'ticket_date_class' ); ?>><?php echo esc_html( $settings['ticket_date'] ); ?></div>
							<?php endif; ?>
							<?php if ( ! empty( $settings['ticket_title'] ) ) : ?>
								<<?php echo esc_html( $title_tag ); ?> <?php $this->print_render_attribute_string( 'ticket_title_class' ); ?>><?php echo esc_html( $settings['ticket_title'] ); ?></<?php echo esc_html( $title_tag ); ?>>	
							<?php endif; ?>
						</div>
						<?php if ( ! empty( $settings['qr_image']['url'] ) ) : ?>
							<div class="d-table bg-white rounded-3 p-4 flex-shrink-0 mx-auto mx-sm-0">
								<img <?php $this->print_render_attribute_string( 'qr_image' ); ?>>
							</div>
						<?php endif; ?>
					</div>
					<div class="d-flex flex-column flex-sm-row align-items-center pt-4 mt-2">
						<?php
						if ( ! empty( $settings['access_button_text'] ) ) :
							?>
							<a <?php $this->print_render_attribute_string( 'access_button_text' ); ?>><?php echo esc_html( $settings['access_button_text'] ); ?></a>
						<?php endif; ?>
						<?php
						if ( ! empty( $settings['ticket_price_text'] || $settings['ticket_price'] ) ) :
							?>
						<div class="d-flex align-items-center">
							<?php if ( ! empty( $settings['ticket_price_text'] ) ) : ?>
								<span <?php $this->print_render_attribute_string( 'ticket_price_text_class' ); ?>><?php echo esc_html( $settings['ticket_price_text'] ); ?></span>
							<?php endif; ?>
							<?php
							if ( '' !== $settings['ticket_price'] ) :
								?>
								<span <?php $this->print_render_attribute_string( 'price_class' ); ?>><?php $this->render_currency_symbol( $symbol, 'before' ); ?><?php echo esc_html( $settings['ticket_price'] ); ?><?php $this->render_currency_symbol( $symbol, 'after' ); ?></span>
								<?php
							endif;
							?>
						</div>
						<?php endif; ?>
					</div>
				</div>
				<?php if ( 'yes' === $settings['dark-version'] ) : ?>
					<span class="position-absolute top-50 end-0 translate-middle-y bg-light rounded-circle p-4 me-n4"></span>
				<?php endif; ?>
			</div>
			<span <?php $this->print_render_attribute_string( 'shadow_color_wrapper_class' ); ?> style="left: 1.5rem; width: calc(100% - 3rem); height: 5rem; filter: blur(.625rem);"></span>
		</div>
		<?php
	}
}
