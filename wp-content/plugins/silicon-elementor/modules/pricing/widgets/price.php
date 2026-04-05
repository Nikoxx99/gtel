<?php
namespace SiliconElementor\Modules\Pricing\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use SiliconElementor\Modules\Pricing\Skins;
use SiliconElementor\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Pricing Widget
 */
class Price extends Base_Widget {

	/**
	 * Register Skins for pricing widget.
	 */
	protected function register_skins() {
		$this->add_skin( new Skins\Skin_Card( $this ) );
		$this->add_skin( new Skins\Skin_List( $this ) );
	}

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
		return 'si-price-table';
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
		return esc_html__( 'Price Table', 'silicon-elementor' );
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
		return 'eicon-price-table';
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
		return [ 'pricing', 'table', 'product', 'image', 'plan', 'button' ];
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
				'label' => esc_html__( 'Header', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'heading',
			[
				'label'   => esc_html__( 'Title', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Standard', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'heading_tag',
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

		$this->add_control(
			'header_icon',
			[
				'label'   => esc_html__( 'Header Icon', 'silicon-elementor' ),
				'type'    => Controls_Manager::ICONS,
				'default' => [
					'value'   => 'bx bx-wink-smile ',
					'library' => 'solid',
				],
			]
		);

		$this->end_controls_section();

		// Pricing Content Tab Start.
		$this->start_controls_section(
			'section_pricing',
			[
				'label'     => esc_html__( 'Pricing', 'silicon-elementor' ),
				'condition' => [
					'_skin!' => [ 'sn-pricing-list' ],
				],
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
			'price',
			[
				'label'   => esc_html__( 'Monthly Price', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '12',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'monthly_sub_price',
			[
				'label'   => esc_html__( 'Monthly Sub Price', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '12',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'show_annual',
			[
				'label'     => esc_html__( 'Show Annual', 'silicon-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'silicon-elementor' ),
				'label_off' => esc_html__( 'Off', 'silicon-elementor' ),
				'default'   => '',
			]
		);

		$this->add_control(
			'annually_price',
			[
				'label'     => esc_html__( 'Annually Price', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '10',
				'condition' => [
					'show_annual' => 'yes',
				],
				'dynamic'   => [
					'active' => true,
				],

			]
		);

		$this->add_control(
			'annually_sub_price',
			[
				'label'     => esc_html__( 'Annually Sub Price', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '10',
				'condition' => [
					'show_annual' => 'yes',
				],
				'dynamic'   => [
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

		// Pricing Content Tab End.
		$this->end_controls_section();

		$this->start_controls_section(
			'section_features',
			[
				'label' => esc_html__( 'Features', 'silicon-elementor' ),
			]
		);

		$repeater = new Repeater();

		$default_icon = [
			'value'   => 'bx bx-check',
			'library' => 'solid',
		];

		$repeater->add_control(
			'selected_item_icon',
			[
				'label'            => esc_html__( 'Icon', 'silicon-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'item_icon',
				'default'          => $default_icon,
			]
		);

		$repeater->add_control(
			'item_text',
			[
				'label'   => esc_html__( 'Text', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'List Item', 'silicon-elementor' ),
			]
		);

		$repeater->add_control(
			'item_text_css',
			[
				'label' => esc_html__( 'Text Class', 'silicon-elementor' ),
				'type'  => Controls_Manager::TEXT,
			]
		);

		$repeater->add_control(
			'item_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .sn-pricing-list-text' => 'color: {{VALUE}}',
				],
			]
		);

		$repeater->add_control(
			'item_icon_color',
			[
				'label'     => esc_html__( 'Icon Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .sn-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'features_list',
			[
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'item_text'          => esc_html__( 'List Item #1', 'silicon-elementor' ),
						'selected_item_icon' => $default_icon,
					],
					[
						'item_text'          => esc_html__( 'List Item #2', 'silicon-elementor' ),
						'selected_item_icon' => $default_icon,
					],
					[
						'item_text'          => esc_html__( 'List Item #3', 'silicon-elementor' ),
						'selected_item_icon' => $default_icon,
					],
				],
				'title_field' => '{{{ item_text }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'footer',
			[
				'label' => esc_html__( 'Footer', 'silicon-elementor' ),
			]
		);

			$this->add_control(
				'button_text',
				[
					'label'       => esc_html__( 'Button Text', 'silicon-elementor' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => esc_html__( 'Start free trial', 'silicon-elementor' ),
					'placeholder' => esc_html__( 'Click here', 'silicon-elementor' ),
				]
			);

			$this->add_control(
				'link',
				[
					'label'       => esc_html__( 'Link', 'silicon-elementor' ),
					'type'        => Controls_Manager::URL,
					'placeholder' => esc_html__( 'https://your-link.com', 'silicon-elementor' ),
					'dynamic'     => array(
						'active' => true,
					),
					'default'     => [
						'url' => '#',
					],
				]
			);

			$this->add_control(
				'button_type',
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
				'button_variant',
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
				'shadow',
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
				'button_css_id',
				[
					'label'       => esc_html__( 'Button ID', 'silicon-elementor' ),
					'type'        => Controls_Manager::TEXT,
					'description' => esc_html__( 'Please make sure the ID is unique and not used elsewhere on the page   this form is displayed. This field allows A-z 0-9 & underscore chars without spaces.', 'silicon-elementor' ),
					'label_block' => true,
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_badge',
			[
				'label' => esc_html__( 'Badge', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'show_badge',
			[
				'label'     => esc_html__( 'Show', 'silicon-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'badge_icon',
			[
				'label'   => esc_html__( 'Badge Icon', 'silicon-elementor' ),
				'type'    => Controls_Manager::ICONS,
				'default' => [
					'value'   => 'bx bx-star',
					'library' => 'solid',
				],
			]
		);

		$this->add_control(
			'badge_title',
			[
				'label'     => esc_html__( 'Badge Title', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Popular', 'silicon-elementor' ),
				'condition' => [
					'show_badge' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_header_style',
			[
				'label'      => esc_html__( 'Header', 'silicon-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition'  => [
					'_skin!' => [ 'sn-pricing-list' ],
				],
			]
		);

		$this->add_control(
			'heading_heading_style',
			[
				'label'     => esc_html__( 'Title', 'silicon-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'heading_color',
			[
				'label'     => esc_html__( 'Title Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-price-table__heading' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'heading_typography',
				'selector' => '{{WRAPPER}} .si-price-table__heading',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->add_control(
			'heading_class',
			[
				'label'   => esc_html__( 'Title Class', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'h5 fw-normal text-muted mb-1',
			]
		);

		$this->add_control(
			'wrap_class',
			[
				'label' => esc_html__( 'Card Class', 'silicon-elementor' ),
				'type'  => Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'transparent_bg',
			[
				'label'        => esc_html__( 'Enable Transparent', 'silicon-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'silicon-elementor' ),
				'label_off'    => esc_html__( 'No', 'silicon-elementor' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'shadow-sm',
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
			'icon_bg_class',
			[
				'label'   => esc_html__( 'Icon Background', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'bg-faded-primary',
			]
		);

		$this->end_controls_section();

		// Pricing Style Tab Start.
		$this->start_controls_section(
			'section_pricing_element_style',
			[
				'label'      => esc_html__( 'Pricing', 'silicon-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition'  => [
					'_skin!' => [ 'sn-pricing-list' ],
				],
			]
		);

		$this->add_control(
			'currency_symbol_color',
			[
				'label'     => esc_html__( 'Currency Symbol Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-elementor-price-table__currency' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'price_color',
			[
				'label'     => esc_html__( 'Price Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-elementor-price-table__integer-part' => 'color: {{VALUE}} !important;',
				],
				'separator' => 'before',
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

		$this->start_controls_section(
			'section_features_list_style',
			[
				'label'      => esc_html__( 'Features', 'silicon-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'features_list_color',
			[
				'label'     => esc_html__( 'Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .si-price-table__features-list' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'features_list_typography',
				'selector' => '{{WRAPPER}} .si-price-table__features-list',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->add_control(
			'ul_class',
			[
				'label'   => esc_html__( 'Ul Wrap Class', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'fs-sm',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_footer_style',
			[
				'label'      => esc_html__( 'Footer', 'silicon-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'heading_footer_button',
			[
				'label'     => esc_html__( 'Button', 'silicon-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'button_text!' => '',
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
					'button_text!' => '',
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
					'{{WRAPPER}} .si-price-table__button' => 'color: {{VALUE}} !important;',
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
				'selector' => '{{WRAPPER}} .si-price-table__button',
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
					'{{WRAPPER}} .si-price-table__button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_css_class',
			[
				'label' => esc_html__( 'Button width class', 'silicon-elementor' ),
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
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label'     => esc_html__( 'Text Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-price-table__button:hover' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label'     => esc_html__( 'Background Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-price-table__button:hover' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-price-table__button:hover' => 'border-color: {{VALUE}} !important;',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_badge_style',
			[
				'label'      => esc_html__( 'Badge', 'silicon-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition'  => [
					'show_badge' => 'yes',
				],
			]
		);

		$this->add_control(
			'badge_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'default'   => '#22C55E',
				'selectors' => [
					'{{WRAPPER}} .si-price-table__badge-inner' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'badge_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .si-price-table__badge-inner' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'badge_typography',
				'selector' => '{{WRAPPER}} .si-price-table__badge-inner',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render Price Table widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$badge_icon_class = [ 'fs-base opacity-75 me-1' ];

		if ( $settings['badge_icon']['value'] ) {
			$badge_icon_class[] = $settings['badge_icon']['value'];
		}

		$title_class = [ 'si-price-table__heading' ];
		if ( $settings['heading_class'] ) {
			$title_class[] = $settings['heading_class'];
		}

		$wrap_class = [ 'card p-xxl-3' ];
		if ( 'yes' === $settings['transparent_bg'] ) {
			$wrap_class[] = 'bg-transparent';
		} else {
			$wrap_class[] = 'bg-light';
		}

		if ( 'yes' === $settings['shadow-sm'] ) {
			$wrap_class[] = 'shadow-sm';
		} else {
			$wrap_class[] = '';
		}

		if ( $settings['wrap_class'] ) {
			$wrap_class[] = $settings['wrap_class'];
		}

		$icon_bg_class = [ 'd-table rounded-circle mx-auto p-4 mb-3' ];
		if ( $settings['icon_bg_class'] ) {
			$icon_bg_class[] = $settings['icon_bg_class'];
		}

		$icon_class = [ 'text-primary display-4 fw-normal lh-1 p-1 p-sm-2' ];

		if ( $settings['header_icon']['value'] ) {
			$icon_class[] = $settings['header_icon']['value'];
		}

		$this->add_render_attribute( 'heading_class', 'class', $title_class );
		$this->add_render_attribute( 'badge_title', 'class', [ 'si-price-table__badge-inner', 'badge bg-success d-flex align-items-center fs-sm position-absolute top-0 start-0 rounded-start-0 mt-3' ] );
		$this->add_render_attribute( 'badge_icon', 'class', $badge_icon_class );
		$this->add_render_attribute( 'wrap_class', 'class', $wrap_class );
		$this->add_render_attribute( 'icon_bg_class', 'class', $icon_bg_class );
		$this->add_render_attribute( 'header_icon', 'class', $icon_class );

		$this->add_inline_editing_attributes( 'heading', 'none' );
		$this->add_inline_editing_attributes( 'button_text' );
		$this->add_inline_editing_attributes( 'badge_title' );?>

		<div <?php $this->print_render_attribute_string( 'wrap_class' ); ?> style="min-width: 18rem;">
			<?php $this->render_badge( $settings ); ?>
			<div class="card-body">
				<div <?php $this->print_render_attribute_string( 'icon_bg_class' ); ?>>
					<i <?php $this->print_render_attribute_string( 'header_icon' ); ?>></i>
				</div>
				<?php
				$this->render_header( $settings );
				$this->render_features( $settings );
				$this->render_footer( $settings );
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render the header.
	 *
	 * @param array $settings setting control.
	 */
	public function render_header( $settings ) {
		$settings    = $this->get_settings_for_display();
		$heading_tag = $settings['heading_tag'];

		?>
		<div class="text-center border-bottom pb-3 mb-3">
			<?php if ( ! empty( $settings['heading'] ) ) : ?>
				<<?php echo esc_html( $heading_tag ); ?> <?php $this->print_render_attribute_string( 'heading_class' ); ?>><?php echo esc_html( $settings['heading'] ); ?></<?php echo esc_html( $heading_tag ); ?>>	
			<?php endif; ?>
			<?php $this->render_price( $settings ); ?>
		</div>
		<?php
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
			echo wp_kses_post( '<span class="si-elementor-price-table__currency elementor-currency--' . $location . '">' . $symbol . '</span>' );
		}
	}

	/**
	 * Render the price.
	 *
	 * @param array $settings setting control.
	 */
	public function render_price( $settings ) {
		$settings = $this->get_settings_for_display();
		$symbol   = '';

		if ( ! empty( $settings['currency_symbol'] ) ) {
			if ( 'custom' !== $settings['currency_symbol'] ) {
				$symbol = $this->get_currency_symbol( $settings['currency_symbol'] );
			} else {
				$symbol = $settings['currency_symbol_custom'];
			}
		}

		$currency_format = empty( $settings['currency_format'] ) ? '.' : $settings['currency_format'];
		$price           = explode( $currency_format, $settings['price'] );

		$intpart = $price[0];

		// Pricing Table Price Content.
		ob_start();

		if ( '' !== $settings['price'] ) :
			?>
			<div class="h2 mb-0">	
				<span class="h2 mb-0 si-elementor-price-table__integer-part" data-monthly-price>
					<?php
					if ( '' !== $settings['price'] ) :
						$this->render_currency_symbol( $symbol, 'before' );
					endif;

					echo esc_html( $settings['price'] ) . '<sup><small>' . esc_html( $settings['monthly_sub_price'] ) . '</small></sup>';

					if ( '' !== $settings['price'] ) :
						$this->render_currency_symbol( $symbol, 'after' );
					endif;
					?>
				</span>
				<span class="h2 mb-0 si-elementor-price-table__integer-part d-none" data-annual-price>
					<?php
					if ( '' !== $settings['price'] ) :
						$this->render_currency_symbol( $symbol, 'before' );
					endif;

					echo esc_html( $settings['annually_price'] ) . '<sup><small>' . esc_html( $settings['annually_sub_price'] ) . '</small></sup>';

					if ( '' !== $settings['price'] ) :
						$this->render_currency_symbol( $symbol, 'after' );
					endif;
					?>
				</span>
			</div>
			<?php
		endif;
		$pricing_table_price_content = ob_get_clean();
		echo wp_kses_post( $pricing_table_price_content );
	}

	/**
	 * Render the badge.
	 *
	 * @param array $settings setting control.
	 */
	public function render_badge( $settings ) {
		$settings = $this->get_settings_for_display();

		if ( 'yes' === $settings['show_badge'] && ! empty( $settings['badge_title'] ) ) :
			?>
			<span <?php $this->print_render_attribute_string( 'badge_title' ); ?>>
				<i <?php $this->print_render_attribute_string( 'badge_icon' ); ?>></i>
				<?php echo esc_html( $settings['badge_title'] ); ?>
			</span>
			<?php
		endif;

	}

	/**
	 * Render the features.
	 *
	 * @param array $settings setting control.
	 */
	public function render_features( $settings ) {
		$migration_allowed = Icons_Manager::is_migration_allowed();
		$count             = 1;

		if ( empty( $settings['features_list'] ) ) {
			return;
		}

		$ul_class = [ 'list-unstyled pb-3 mb-3 si-price-table__features-list' ];
		if ( $settings['ul_class'] ) {
			$ul_class[] = $settings['ul_class'];
		}

		$this->add_render_attribute( 'ul_class', 'class', $ul_class );

		?>
		<ul <?php $this->print_render_attribute_string( 'ul_class' ); ?>>
			<?php
			foreach ( $settings['features_list'] as $index => $item ) :
				$repeater_setting_key = $this->get_repeater_setting_key( 'item_text', 'features_list', $index );
				$this->add_inline_editing_attributes( $repeater_setting_key );

				$migrated = isset( $item['__fa4_migrated']['selected_item_icon'] );
				// add old default.
				if ( ! isset( $item['item_icon'] ) && ! $migration_allowed ) {
					$item['item_icon'] = 'fa fa-check-circle';
				}
				$is_new = ! isset( $item['item_icon'] ) && $migration_allowed;

				$text_class = [ 'd-flex', 'mb-2', 'elementor-repeater-item-' . $item['_id'] ];
				if ( $item['item_text_css'] ) {
					$text_class[] = $item['item_text_css'];
				}
				if ( count( $settings['features_list'] ) === $count ) {
					$text_class[1] = 'mb-0';
				}
				$this->add_render_attribute( 'item_text_css-' . $item['_id'], 'class', $text_class );
				?>
				<li <?php $this->print_render_attribute_string( 'item_text_css-' . $item['_id'] ); ?>>
					<?php
					if ( ! empty( $item['item_icon'] ) || ! empty( $item['selected_item_icon'] ) ) :
						if ( $is_new || $migrated ) :
							Icons_Manager::render_icon( $item['selected_item_icon'], [ 'class' => 'fs-xl me-1 sn-icon' ] );
						else :
							?>
							<i class="<?php echo esc_attr( $item['item_icon'] ); ?> fs-xl me-1"></i>
							<?php
						endif;
					endif;
					?>
					<?php
					if ( ! empty( $item['item_text'] ) ) :
						?>
						<span class="sn-pricing-list-text">
							<?php echo esc_html( $item['item_text'] ); ?>
						</span>
						<?php
					else :
						echo '&nbsp;';
					endif;
					?>
				</li>
				<?php
				$count++;
				endforeach;
			?>
		</ul> 
		<?php
	}

	/**
	 * Render the footer.
	 *
	 * @param array $settings setting control.
	 */
	public function render_footer( $settings ) {

		$button_classes = [ 'btn', 'silicon-button', 'si-price-table__button' ];

		if ( ! empty( $settings['button_type'] ) ) {
			if ( '' === $settings['button_variant'] ) {
				$button_classes[] = 'btn-' . $settings['button_type'];
			} elseif ( 'soft' === $settings['button_variant'] ) {
				$button_classes[] = 'btn-' . $settings['button_type'] . '-' . $settings['button_variant'];
			} elseif ( 'outline' === $settings['button_variant'] ) {
				$button_classes[] = 'btn-' . $settings['button_variant'] . '-' . $settings['button_type'];
			}
		}

		if ( ! empty( $settings['button_css_class'] ) ) {
			$button_classes[] = $settings['button_css_class'];
		}

		if ( ! empty( $settings['button_size'] ) ) {
			$button_classes[] = 'btn-' . $settings['button_size'];
		}

		if ( 'yes' === $settings['shadow'] ) {
			$this->add_render_attribute( 'button_text', 'class', 'shadow-' . $settings['button_type'] );
		}

		$this->add_render_attribute( 'button_text', 'class', $button_classes );

		$this->add_inline_editing_attributes( 'button_text' );

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'button_text', $settings['link'] );
		}

		if ( ! empty( $settings['button_css_id'] ) ) {
			$this->add_render_attribute( 'button_text', 'id', $settings['button_css_id'] );
		}

		if ( ! empty( $settings['button_text'] ) ) :
			?>
			<a <?php $this->print_render_attribute_string( 'button_text' ); ?>><?php echo esc_html( $settings['button_text'] ); ?></a>
		<?php endif; ?>
		<?php
	}
}
