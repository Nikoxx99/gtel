<?php
namespace SiliconElementor\Modules\Carousel\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Control_Media;
use Elementor\Utils;
use Elementor\Core\Files\File_Types\Svg;
use SiliconElementor\Modules\Carousel\Skins;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Imagebox Carousel
 */
class Imagebox_Carousel extends Base {

	/**
	 * Skip widget.
	 *
	 * @var bool
	 */
	protected $_has_template_content = false;

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'sn-imagebox-carousel';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Imagebox Carousel', 'silicon-elementor' );
	}

	/**
	 * Get the icon for the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-carousel';
	}

	/**
	 * Get the keywords associated with the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'cards', 'carousel', 'image', 'brands', 'iconbox', 'imagebox' ];
	}

	/**
	 * Register the Skins with the widget.
	 *
	 * @return void
	 */
	protected function register_skins() {
		$this->add_skin( new Skins\Skin_Imagebox_Carousel( $this ) );
	}

	/**
	 * Get the group for this widget.
	 *
	 * @return string
	 */
	public function get_group_name() {
		return 'carousel';
	}

	/**
	 * Display Carousel.
	 *
	 * @param array  $slide repeater single control arguments.
	 * @param array  $settings control arguments.
	 * @param string $element_key slider id argument.
	 * @return void
	 */
	protected function print_slide( array $slide, array $settings, $element_key ) {}


	/**
	 * Register controls for this widget.
	 *
	 * @return void
	 */
	protected function register_controls() {
		parent::register_controls();

		$this->start_injection(
			[
				'of' => 'slides',
			]
		);

		$repeater_skin = new Repeater();

		$repeater_skin->add_control(
			'image',
			[
				'label' => esc_html__( 'Image', 'silicon-elementor' ),
				'type'  => Controls_Manager::MEDIA,
			]
		);

		$repeater_skin->add_control(
			'image_link',
			[
				'label'   => esc_html__( 'Image Link', 'silicon-elementor' ),
				'type'    => Controls_Manager::URL,
				'default' => [
					'url' => '#',
				],
			]
		);

		$repeater_skin->add_control(
			'Title',
			[
				'label'   => esc_html__( 'Title', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html( 'Title1' ),
			]
		);

		$repeater_skin->add_control(
			'list',
			[
				'label'   => esc_html__( 'List ', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html( '' ),
			]
		);

		$repeater_skin->add_control(
			'button_text',
			[
				'label'   => esc_html__( 'Button Text', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Learn more', 'silicon-elementor' ),
				// 'condition' => [ 'enable_button' => 'yes' ],
			]
		);

		$repeater_skin->add_control(
			'button_link',
			[
				'label' => esc_html__( 'Link', 'silicon-elementor' ),
				'type'  => Controls_Manager::URL,
				// 'condition' => [ 'enable_button' => 'yes' ],
			]
		);

		$repeater_skin->add_control(
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
					'none'      => esc_html__( 'None', 'silicon-elementor' ),
				],
				// 'condition' => [ 'enable_button' => 'yes' ],
			]
		);

		$repeater_skin->add_control(
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
				// 'condition' => [ 'enable_button' => 'yes' ],
			]
		);

		$repeater_skin->add_control(
			'enable_shadow',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Shadow', 'silicon-elementor' ),
				'default'   => 'no',
				'label_off' => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Disable', 'silicon-elementor' ),
				// 'condition' => [ 'enable_button' => 'yes' ],
			]
		);

		$repeater_skin->add_control(
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
				// 'condition' => [ 'enable_button' => 'yes' ],
			]
		);

		$repeater_skin->add_control(
			'selected_icon',
			[
				'label'            => esc_html__( 'Icon', 'silicon-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin'             => 'inline',
				'label_block'      => false,
			]
		);

		$this->add_control(
			'slides_skin',
			[
				'label'     => esc_html__( 'Slides', 'silicon-elementor' ),
				'type'      => Controls_Manager::REPEATER,
				'fields'    => $repeater_skin->get_controls(),
				'default'   => $this->get_repeater_defaults(),
				'condition' => [
					'style' => 'style-v3',
				],
			]
		);

		$this->end_injection();

		$this->start_injection(
			[
				'at' => 'before',
				'of' => 'section_slides_style',
			]
		);

		$this->start_controls_section(
			'section_card',
			[
				'label'     => esc_html__( 'Card', 'silicon-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style' => 'style-v1',
				],
			]
		);

		$this->add_control(
			'show_card',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Card', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Hide', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Show', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'show_hover',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Hover', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Hide', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Show', 'silicon-elementor' ),
				'condition' => [
					'show_card' => 'yes',
				],
			]
		);

		$this->add_control(
			'card_class',
			[
				'label'       => esc_html__( 'Card Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'title'       => esc_html__( 'Add your custom class for <div>  without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'     => '',
				'label_block' => true,
				'description' => esc_html__( 'Additional CSS class that you want to apply to the <div>', 'silicon-elementor' ),
				'condition'   => [
					'style' => 'style-v1',
				],
			]
		);

		$this->add_control(
			'card_body_css',
			[
				'label'       => esc_html__( 'Card Body Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'title'       => esc_html__( 'Add your custom class for <div>  without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'     => '',
				'label_block' => true,
				'description' => esc_html__( 'Additional CSS class that you want to apply to the card body', 'silicon-elementor' ),
				'condition'   => [
					'style' => 'style-v1',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_image',
			[
				'label' => esc_html__( 'Image', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'show_border',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Border Enable', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Hide', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Show', 'silicon-elementor' ),
				'condition' => [
					'style' => 'style-v2',

				],
			]
		);

		$this->add_control(
			'wrap_class',
			[
				'label'       => esc_html__( 'Image Wrap Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'title'       => esc_html__( 'Add your custom class for <div> tag  without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'     => 'p-3 mb-4',
				'label_block' => true,
				'description' => esc_html__( 'Additional CSS class that you want to apply to the a tag', 'silicon-elementor' ),
				'condition'   => [
					'style' => 'style-v1',
				],
			]
		);

		$this->add_control(
			'image_class',
			[
				'label'       => esc_html__( 'Image Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'title'       => esc_html__( 'Add your custom class for image  without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'     => 'd-block mb-4 mx-auto',
				'label_block' => true,
				'description' => esc_html__( 'Additional CSS class that you want to apply to the image', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'image_style_attr',
			[
				'label'       => esc_html__( 'Image Style Attribute', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'title'       => esc_html__( 'Add your custom styles attribute for image', 'silicon-elementor' ),
				'label_block' => true,
				'description' => esc_html__( 'Add your custom styles attribute for image', 'silicon-elementor' ),
			]
		);

		$this->add_responsive_control(
			'awards_img_width',
			[
				'type'      => Controls_Manager::SLIDER,
				'label'     => esc_html__( 'Image Width', 'silicon-elementor' ),
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
				],
				'default'   => [
					'size' => 170,
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-slide img' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .swiper-slide .silicon-elementor-svg-wrapper' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .swiper-slide .silicon-elementor-svg-wrapper svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after',
				'condition' => [
					'style'      => 'style-v3',
					'style_card' => 'awards',
				],
			]
		);

		$this->add_control(
			'show_color',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable BackgroundColor', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Hide', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Show', 'silicon-elementor' ),
				'condition' => [
					'style' => 'style-v1',
				],
			]
		);

		$this->add_control(
			'image_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sn-silicon-img' => 'background-color: {{VALUE}} !important;',
				],
				'default'   => '#6366F1',
				'condition' => [
					'show_color' => 'yes',
					'style'      => 'style-v1',
				],

			]
		);

		$this->add_control(
			'background_class',
			[
				'label'       => esc_html__( 'Image Wrap Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'title'       => esc_html__( 'Add your custom class for <span> tag  without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'     => 'opacity-8',
				'label_block' => true,
				'description' => esc_html__( 'Additional CSS class that you want to apply to the a tag', 'silicon-elementor' ),
				'condition'   => [
					'show_color' => 'yes',
					'style'      => 'style-v1',
				],
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'type'      => Controls_Manager::SLIDER,
				'label'     => esc_html__( 'Image Width', 'silicon-elementor' ),
				'range'     => [
					'px' => [
						'min' => 20,
						'max' => 70,
					],
				],
				'default'   => [
					'size' => 32,
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-slide img' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .swiper-slide .silicon-elementor-svg-wrapper' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .swiper-slide .silicon-elementor-svg-wrapper svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after',
				'condition' => [
					'style!' => 'style-v3',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Content', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'     => esc_html__( 'Alignment', 'silicon-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'start'  => [
						'title' => esc_html__( 'Left', 'silicon-elementor' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'silicon-elementor' ),
						'icon'  => 'eicon-text-align-center',
					],
					'end'    => [
						'title' => esc_html__( 'Right', 'silicon-elementor' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'center',
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'style' => 'style-v2',
				],
			]
		);

		$this->add_control(
			'heading_title',
			[
				'label' => esc_html__( 'Title', 'silicon-elementor' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'title_class',
			[
				'label'       => esc_html__( 'Title Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'title'       => esc_html__( 'Add your custom class for <h> tag  without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'     => 'mb-2 pb-1',
				'label_block' => true,
				'description' => esc_html__( 'Additional CSS class that you want to apply to the a tag', 'silicon-elementor' ),

			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Title Color', 'silicon-elementor' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'title_typography',
				'label'     => __( 'Typography', 'silicon-elementor' ),
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'separator' => 'before',
				// 'selectors' => [ '{{WRAPPER}} .si-imagebox-title' => 'font-size: {{SIZE}}{{UNIT}}' ],
				'selector'  => '{{WRAPPER}} .si-imagebox-title',
			]
		);

		$this->add_control(
			'heading_description',
			[
				'label'     => esc_html__( 'Description', 'silicon-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'style!' => 'style-v3',

				],
			]
		);

		$this->add_control(
			'description_class',
			[
				'label'       => esc_html__( 'Description Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'title'       => esc_html__( 'Add your custom class for <p> tag  without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'     => 'mx-auto',
				'label_block' => true,
				'description' => esc_html__( 'Additional CSS class that you want to apply to the a tag', 'silicon-elementor' ),
				'condition'   => [
					'style!' => 'style-v3',

				],

			]
		);

		$this->add_control(
			'description_color',
			[
				'label'     => esc_html__( 'Description Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'style!' => 'style-v3',

				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'desc_typography',
				'label'     => __( 'Typography', 'silicon-elementor' ),
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'separator' => 'before',
				// 'selectors' => [ '{{WRAPPER}} .si-imagebox-description' => 'font-size: {{SIZE}}{{UNIT}}' ],
				'selector'  => '{{WRAPPER}} .si-imagebox-description',
				'condition' => [
					'style!' => 'style-v3',

				],
			]
		);

		$this->add_responsive_control(
			'description_width',
			[
				'type'      => Controls_Manager::SLIDER,
				'label'     => esc_html__( 'Description Width', 'silicon-elementor' ),
				'range'     => [
					'px' => [
						'min' => 300,
						'max' => 1000,
					],
				],
				'default'   => [
					'size' => 336,
				],
				'selectors' => [
					'{{WRAPPER}} .si-imagebox-description' => 'max-width: {{SIZE}}{{UNIT}}; ',
				],
				'separator' => 'after',
				'condition' => [
					'style' => 'style-v2',
				],
			]
		);

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
				'selector' => '{{WRAPPER}} .swiper-button',
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-button' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'button_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-button' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-button' => 'border-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label'     => esc_html__( 'Hover Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-button:hover, {{WRAPPER}} .swiper-button-hover:focus' => 'background-color: {{VALUE}} !important;',
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
			]
		);

		$this->add_control(
			'button_icon_class',
			[
				'label'       => esc_html__( 'Icon Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'title'       => esc_html__( 'Add your custom class for <h> tag  without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'     => 'fs-xl ms-2',
				'label_block' => true,

			]
		);

		$this->end_controls_section();

		$this->end_injection();

		$this->add_control(
			'style',
			[
				'label'   => esc_html__( 'Style', 'silicon-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'style-v1',
				'options' => [
					'style-v1' => 'Style v1',
					'style-v2' => 'Style v2',
					'style-v3' => 'Style v3',
				],
			],
			[
				'position' => [
					'at' => 'before',
					'of' => 'slides',
				],
			]
		);

		$this->add_control(
			'style_card',
			[
				'label'     => esc_html__( 'Card Style', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'default',
				'options'   => [
					'default' => 'Default',
					'card'    => 'Card Style',
					'awards'  => 'Awards',
				],
				'condition' => [
					'style' => 'style-v3',
				],
			],
			[
				'position' => [
					'at' => 'after',
					'of' => 'style',
				],
			]
		);

		$this->start_injection(
			[
				'of' => 'slides',
			]
		);

		$this->end_injection();
	}

	/**
	 * Register repeater controls for this widget.
	 *
	 * @param Repeater $repeater repeater control arguments.
	 * @return void
	 */
	protected function add_repeater_controls( Repeater $repeater ) {

			$repeater->add_control(
				'image',
				[
					'label' => esc_html__( 'Image', 'silicon-elementor' ),
					'type'  => Controls_Manager::MEDIA,
				]
			);

			$repeater->add_control(
				'inline_svg',
				[
					'label'        => esc_html__( 'Inline SVG', 'silicon-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'title'        => esc_html__( 'If you are uploading SVG file, it might be useful to inline the SVG files. Do not inline, if your SVG file is from unknown sources.', 'silicon-elementor' ),
					'label_on'     => esc_html__( 'Yes', 'silicon-elementor' ),
					'label_off'    => esc_html__( 'No', 'silicon-elementor' ),
					'return_value' => 'yes',
					'default'      => 'no',
				]
			);

			$repeater->add_control(
				'Title',
				[
					'label'   => esc_html__( 'Title', 'silicon-elementor' ),
					'type'    => Controls_Manager::TEXT,
					'default' => esc_html( 'Title1' ),
				]
			);

			$repeater->add_control(
				'inline_title_enable',
				[
					'label'   => esc_html__( 'Enable Inline Title', 'silicon-elementor' ),
					'type'    => Controls_Manager::SWITCHER,
					'default' => 'no',
				]
			);

			$repeater->add_control(
				'inline_title',
				[
					'label'     => esc_html__( 'Inline Title', 'silicon-elementor' ),
					'type'      => Controls_Manager::TEXT,
					'condition' => [
						'inline_title_enable' => 'yes',
					],
				]
			);

			$repeater->add_control(
				'Description',
				[
					'label'   => '',
					'type'    => Controls_Manager::WYSIWYG,
					'default' => '<p>' . esc_html__( 'Id mollis consectetur congue egestas egestas suspendisse blandit justo. Tellus augue commodo id quis tempus etiam pulvinar at maecenas.', 'silicon-elementor' ) . '</p>',
				]
			);

	}


	/**
	 * Slider defaults.
	 *
	 * @return string
	 */
	protected function get_repeater_defaults() {
		$placeholder_image_src = Utils::get_placeholder_image_src();

		return [
			[
				'image' => [
					'url' => $placeholder_image_src,
				],
			],
			[
				'image' => [
					'url' => $placeholder_image_src,
				],
			],
			[
				'image' => [
					'url' => $placeholder_image_src,
				],
			],
		];
	}




	/**
	 * Get slider settings
	 *
	 * @param array $slide the slides settings.
	 * @param array $settings the widget settings.
	 * @param array $element_key the element_key slider id.
	 * @param array $count the widget settings..
	 * @return void
	 */
	public function print_imagebox_slide( array $slide, array $settings, $element_key, $count ) {

		$swiper_class = [];
		if ( 'yes' === $settings['show_border'] ) {
			$swiper_class[] = 'border-end-lg';

		}
		$card_class = [ 'h-100' ];
		if ( 'yes' === $settings['show_card'] ) {
				$card_class[] = 'card';
		}
		if ( 'yes' === $settings['show_hover'] ) {
			if ( 'yes' === $settings['show_card'] ) {
				$card_class[] = 'card-hover';
			}
		}
		if ( $settings['card_class'] ) {
			$card_class[] = $settings['card_class'];
		}

		$body_class = [];
		if ( 'yes' === $settings['show_card'] ) {
			$body_class[] = 'card-body';
			if ( ! empty( $settings['card_body_css'] ) ) {
				$body_class[] = $settings['card_body_css'];
			}
		}

		$wrap_class = [ 'd-table position-relative' ];
		if ( $settings['wrap_class'] ) {
			$wrap_class[] = $settings['wrap_class'];
		}

		$image_url   = $slide['image']['url'];
		$image_class = [];
		if ( $settings['image_class'] ) {
			$image_class[] = $settings['image_class'];
		}

		$image_style_attr = [];
		if ( $settings['image_style_attr'] ) {
			$image_style_attr[] = $settings['image_style_attr'];
		}

		$span_class = [ '' ];
		if ( 'yes' === $settings['show_color'] ) {
			$span_class[] = 'position-absolute top-0 start-0 w-100 h-100 rounded-circle';
		}
		if ( $settings['background_class'] ) {
			$span_class[] = $settings['background_class'];
		}

		$title_class = [ 'si-imagebox-title' ];
		if ( $settings['title_class'] ) {
			$title_class[] = $settings['title_class'];
		}

		$description_class = [ 'si-imagebox-description' ];
		if ( $settings['description_class'] ) {
			$description_class[] = $settings['description_class'];
		}
		if ( 'style-v3' === $settings['style'] ) {
			$button_icon_class = [ 'silicon-icon' ];
			if ( $settings['button_icon_class'] ) {
				$button_icon_class[] = $settings['button_icon_class'];
			}
			$button_class = [ '', 'swiper-button' ];
			if ( 'none' !== $slide['button_type'] ) {
				$button_class[0] = 'btn';
				if ( $slide['button_type'] ) {
					$button_class[] = 'btn-' . $slide['button_type'];
				}
			}

			if ( $slide['button_size'] ) {
				$button_class[] = 'btn-' . $slide['button_size'];
			}
			if ( 'yes' === $slide['enable_shadow'] ) {
				$button_class[] = 'shadow-' . $slide['button_type'];
			}
			if ( $slide['button_shape'] ) {
				$button_class[] = $slide['button_shape'];
			}
			if ( $settings['button_css'] ) {
				$button_class[] = $settings['button_css'];
			}

			$this->add_render_attribute(
				'sn_button' . $element_key,
				[
					'href'  => ! empty( $slide['button_link']['url'] ) ? $slide['button_link']['url'] : '#',
					'class' => $button_class,
				]
			);
			$this->add_render_attribute(
				'buttonicon-' . $element_key,
				[
					'class' => $button_icon_class,
				]
			);
			$this->add_render_attribute(
				'buttonicon-' . $element_key,
				[
					'class' => $slide['selected_icon']['value'],
				]
			);

		}

		$this->add_render_attribute(
			'card-' . $element_key,
			[
				'class' => $card_class,
			]
		);
		$this->add_render_attribute(
			'body-' . $element_key,
			[
				'class' => $body_class,
			]
		);
		$this->add_render_attribute(
			'wrap-' . $element_key,
			[
				'class' => $wrap_class,
			]
		);
		$this->add_render_attribute(
			'image-' . $element_key,
			[
				'class' => $image_class,
				'src'   => $image_url,
				'width' => '32',
				'alt'   => Control_Media::get_image_alt( $slide['image'] ),
				'style' => $image_style_attr,

			]
		);
		if ( isset( $slide['image_link'] ) && $slide['image_link'] ) {
			$this->add_link_attributes( 'image1-link-' . $element_key, $slide['image_link'] );
		}
		$this->add_render_attribute(
			'image1-link-' . $element_key,
			[
				'class' => 'si-image-box__link',
			]
		);
		$this->add_render_attribute(
			'image1-' . $element_key,
			[
				'class' => $image_class,
				'src'   => $image_url,
				'alt'   => Control_Media::get_image_alt( $slide['image'] ),

			]
		);
		$this->add_render_attribute(
			'span-' . $element_key,
			[
				'class' => $span_class,
			]
		);
		$this->add_render_attribute(
			'title-' . $element_key,
			[
				'class' => $title_class,
			]
		);
		$this->add_render_attribute(
			'title-' . $element_key,
			[
				'style' => 'color: ' . $settings['title_color'],
			]
		);
		$this->add_render_attribute(
			'description-' . $element_key,
			[
				'class' => $description_class,

			]
		);
		$this->add_render_attribute(
			'description-' . $element_key,
			[
				'style' => 'color: ' . $settings['description_color'],
			]
		);

		if ( $image_url && 'style-v1' === $settings['style'] ) {
			$this->skin_slide_start( $settings, $element_key );
			?>
				<div <?php $this->print_render_attribute_string( 'card-' . $element_key ); ?>>
					<?php if ( 'yes' === $settings['show_card'] ) { ?>
					<div <?php $this->print_render_attribute_string( 'body-' . $element_key ); ?>>
					<?php } ?>	
						<div <?php $this->print_render_attribute_string( 'wrap-' . $element_key ); ?>>
						<?php if ( 'yes' !== $slide['inline_svg'] ) : ?>
							<img <?php $this->print_render_attribute_string( 'image-' . $element_key ); ?>>
						<?php endif; ?>
							<?php if ( 'yes' === $settings['show_color'] ) { ?>
							<span <?php $this->print_render_attribute_string( 'span-' . $element_key ); ?>></span>
							<?php } ?>
							<?php
							if ( isset( $slide['inline_svg'] ) && 'yes' === $slide['inline_svg'] && isset( $slide['image']['url'] ) ) {
								if ( isset( $settings['image_class'] ) && ! empty( $settings['image_class'] ) ) {
									$html = '<div class="silicon-elementor-svg-wrapper ' . esc_attr( $settings['image_class'] ) . '">';
								} else {
									$html = '<div class="silicon-elementor-svg-wrapper">';
								}
								$html .= Svg::get_inline_svg( $slide['image']['id'] );
								$html .= '</div>';
								echo $html; //phpcs:ignore

							}
							?>
						</div>
						<h3 <?php $this->print_render_attribute_string( 'title-' . $element_key ); ?>><?php echo esc_html( $slide['Title'] ); ?>
						<?php if ( 'yes' === $slide['inline_title_enable'] ) : ?>
							<?php if ( ! empty( $slide['inline_title'] ) ) : ?>
								<span class="h4"><?php echo esc_html( $slide['inline_title'] ); ?></span>
							<?php endif; ?>
						<?php endif; ?>
						</h3>
						<div <?php $this->print_render_attribute_string( 'description-' . $element_key ); ?>><?php echo wp_kses_post( $slide['Description'] ); ?></div>
					<?php if ( 'yes' === $settings['show_card'] ) { ?>	
					</div>
					<?php } ?>
				</div>
			</div>
			
			<?php
		}

		if ( $image_url && 'style-v2' === $settings['style'] ) {
			// $cc = ( $count % ( $settings['slides_per_view'] ) !== 0 ) ? 'border-end-lg' : '';
			$this->add_render_attribute( 'carousel_slide_css-' . $element_key, 'class', $swiper_class );
			$this->skin_slide_start( $settings, $element_key );
			?>
			
				<!-- <div <?php // $this->print_render_attribute_string( 'wrap-' . $element_key ); ?>> -->
					<?php if ( 'yes' !== $slide['inline_svg'] ) : ?>
						<img <?php $this->print_render_attribute_string( 'image-' . $element_key ); ?>>
						<?php
					endif;
					if ( isset( $slide['inline_svg'] ) && 'yes' === $slide['inline_svg'] && isset( $slide['image']['url'] ) ) {
						if ( isset( $settings['image_class'] ) && ! empty( $settings['image_class'] ) ) {
							$html = '<div class="silicon-elementor-svg-wrapper mx-auto ' . esc_attr( $settings['image_class'] ) . '">';
						} else {
							$html = '<div class="silicon-elementor-svg-wrapper mx-auto ">';
						}
						$html .= Svg::get_inline_svg( $slide['image']['id'] );
						$html .= '</div>';
						echo $html; //phpcs:ignore

					}
					?>
					
					<h4 <?php $this->print_render_attribute_string( 'title-' . $element_key ); ?>><?php echo esc_html( $slide['Title'] ); ?>
					<?php if ( 'yes' === $slide['inline_title_enable'] ) : ?>
						<?php if ( ! empty( $slide['inline_title'] ) ) : ?>
							<span class="h4"><?php echo esc_html( $slide['inline_title'] ); ?></span>
						<?php endif; ?>
					<?php endif; ?>
					</h4>
					<div <?php $this->print_render_attribute_string( 'description-' . $element_key ); ?>><?php echo wp_kses_post( $slide['Description'] ); ?></div>
				<!-- </div> -->	
			</div>
			
			<?php
		}

		if ( 'style-v3' === $settings['style'] ) {
			if ( 'card' === $settings['style_card'] ) {
				$this->skin_slide_start( $settings, $element_key );
				?>
					<div class="card h-100">
						<?php if ( ! empty( $slide['image_link']['url'] ) ) : ?>
						<a <?php $this->print_render_attribute_string( 'image1-link-' . $element_key ); ?>>
						<?php endif; ?>
							<img <?php $this->print_render_attribute_string( 'image1-' . $element_key ); ?>>
						<?php if ( ! empty( $slide['image_link']['url'] ) ) : ?>
						</a>
						<?php endif; ?>
						<div class="card-body">
							<h5 <?php $this->print_render_attribute_string( 'title-' . $element_key ); ?>><?php echo esc_html( $slide['Title'] ); ?></h5>
							<p class="card-text mb-4 pb-md-3 fs-lg"><?php echo wp_kses_post( $slide['list'] ); ?></p>
							<a <?php $this->print_render_attribute_string( 'sn_button' . $element_key ); ?>>
																		   <?php
																			echo esc_html( $slide['button_text'] );
																			if ( ! empty( $slide['selected_icon']['value'] && ! isset( $slide['selected_icon']['value']['url'] ) ) ) :
																				?>
								<i <?php $this->print_render_attribute_string( 'buttonicon-' . $element_key ); ?>></i>
																				<?php
							endif;
																			if ( ! empty( $slide['selected_icon']['value']['url'] ) ) {
																				?>
																					<?php Icons_Manager::render_icon( $slide['selected_icon'] ); ?>
																				<?php
																			}
																			?>
							</a>
						</div>
					</div>
				</div>
				<?php
			} elseif ( 'awards' === $settings['style_card'] ) {
				$this->skin_slide_start( $settings, $element_key );
				?>
				<div class="card shadow-sm card-hover position-relative h-100 py-md-1 px-md-2">
				  <div class="card-body">
				  <?php if ( ! empty( $slide['image_link']['url'] ) ) : ?>
						<a <?php $this->print_render_attribute_string( 'image1-link-' . $element_key ); ?>>
						<?php endif; ?>
							<img <?php $this->print_render_attribute_string( 'image1-' . $element_key ); ?>>
						<?php if ( ! empty( $slide['image_link']['url'] ) ) : ?>
						</a>
						<?php endif; ?>
					<div class="mt-5 pt-lg-5 pt-md-4 pt-sm-3">
						<?php if ( $slide['Title'] ) : ?>
						<div <?php $this->print_render_attribute_string( 'title-' . $element_key ); ?>><?php echo esc_html( $slide['Title'] ); ?></div>
						<?php endif; ?>
						<h3 class="h5 mt-xl-4 mb-2 pt-lg-2 pb-1 d-flex align-items-center text-nowrap">
						<a <?php $this->print_render_attribute_string( 'sn_button' . $element_key ); ?>><?php echo esc_html( $slide['button_text'] ); ?>
							<i <?php $this->print_render_attribute_string( 'buttonicon-' . $element_key ); ?>></i>
						</a>
						</h3>
						<p class="mb-0"><?php echo wp_kses_post( $slide['list'] ); ?></p>
					</div>
				  </div>
				</div>
				</div>
				<?php
			} else {
				$this->skin_slide_start( $settings, $element_key );
				?>
				<a <?php $this->print_render_attribute_string( 'image1-link-' . $element_key ); ?>><img <?php $this->print_render_attribute_string( 'image1-' . $element_key ); ?>></a>
				<div class="pt-4">
					<h3 <?php $this->print_render_attribute_string( 'title-' . $element_key ); ?>><?php echo esc_html( $slide['Title'] ); ?></h3>
					<?php echo wp_kses_post( $slide['list'] ); ?>
				</div>
				<a <?php $this->print_render_attribute_string( 'sn_button' . $element_key ); ?>><?php echo esc_html( $slide['button_text'] ); ?>
					<i <?php $this->print_render_attribute_string( 'buttonicon-' . $element_key ); ?>></i>
				</a>
				</div>
				<?php
			}
		}

	}
}
