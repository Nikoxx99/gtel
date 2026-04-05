<?php
namespace SiliconElementor\Modules\Carousel\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use SiliconElementor\Modules\Carousel\Skins;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Content Carousel
 */
class Content_Carousel extends Base {

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
		return 'sn-content-carousel';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Content Carousel', 'silicon-elementor' );
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
		return [ 'industry', 'carousel', 'image', 'content', 'slider' ];
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
	 * Register the Skins with the widget.
	 *
	 * @return void
	 */
	protected function register_skins() {
		$this->add_skin( new Skins\Skin_Content_Carousel( $this ) );
		$this->add_skin( new Skins\Skin_Content_Carousel_V2( $this ) );
		$this->add_skin( new Skins\Skin_Content_Carousel_V3( $this ) );
		$this->add_skin( new Skins\Skin_Content_Carousel_V4( $this ) );
	}

	/**
	 * Display Carousel.
	 *
	 * @param array  $slide repeater single control arguments.
	 * @param array  $settings control arguments.
	 * @param string $element_key slider id argument.
	 * @return void
	 */
	protected function print_slide( array $slide, array $settings, $element_key ) {

	}

	/**
	 * Register controls for this widget.
	 *
	 * @return void
	 */
	protected function register_controls() {
		parent::register_controls();

		$this->start_injection(
			[
				'at' => 'before',
				'of' => 'section_additional_options',
			]
		);

		$this->start_controls_section(
			'section_layout',
			[
				'label'     => esc_html__( 'Layout', 'silicon-elementor' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => [ '_skin' => 'content-carousel-v1' ],
			]
		);

		$this->add_control(
			'enable_icon',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Icon', 'silicon-elementor' ),
				'default'   => 'no',
				'label_off' => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Disable', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'icon',
			[
				'label'       => esc_html__( 'Icon', 'silicon-elementor' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => [
					'value' => 'bx bx-right-arrow-alt',
				],
				'skin'        => 'inline',
				'label_block' => false,
				'condition'   => [ 'enable_icon' => 'yes' ],
			]
		);

		$this->add_control(
			'button_position',
			[
				'label'   => esc_html__( 'Arrows Position', 'silicon-elementor' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'bottom',
				'options' => [
					'top'    => [
						'title' => esc_html__( 'Top', 'silicon-elementor' ),
						'icon'  => 'eicon-v-align-top',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'silicon-elementor' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label'   => esc_html__( 'Title HTML Tag', 'silicon-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				],
				'default' => 'h3',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_jarallax',
			[
				'label'     => esc_html__( 'Jarallax', 'silicon-elementor' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'_skin' => [ 'content-carousel-v3' ],
				],
			]
		);

		$this->add_control(
			'opacity',
			[
				'label'     => esc_html__( 'Opacity', 'silicon-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					],
				],
				'default'   => [
					'size' => '0.35',
				],
				'selectors' => [
					'{{WRAPPER}} .jarallax span' => 'opacity: {{SIZE}} !important;',
				],
			]
		);

		$this->add_control(
			'data_speed',
			[
				'label'   => esc_html__( 'Data Speed', 'silicon-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'default' => [
					'size' => '0.4',
				],
			]
		);

		$this->end_controls_section();

		$this->end_injection();

		$this->start_injection(
			[
				'of' => 'slides',
			]
		);

		$repeater_skin = new Repeater();

		$repeater_skin->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'silicon-elementor' ),
				'type'  => Controls_Manager::TEXTAREA,
			]
		);

		$repeater_skin->add_control(
			'title_tag',
			[
				'label'   => esc_html__( 'Title HTML Tag', 'silicon-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				],
				'default' => 'h2',
			]
		);

		$repeater_skin->add_control(
			'description',
			[
				'label'     => esc_html__( 'Description', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXTAREA,
				'separator' => 'before',
			]
		);

		$repeater_skin->add_control(
			'enable_button',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Button', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Disable', 'silicon-elementor' ),
				'separator' => 'before',
			]
		);

		$repeater_skin->add_control(
			'button_text',
			[
				'label'     => esc_html__( 'Button Text', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Learn More', 'silicon-elementor' ),
				'condition' => [ 'enable_button' => 'yes' ],
			]
		);

		$repeater_skin->add_control(
			'button_link',
			[
				'label'     => esc_html__( 'Link', 'silicon-elementor' ),
				'type'      => Controls_Manager::URL,
				'condition' => [ 'enable_button' => 'yes' ],
			]
		);

		$repeater_skin->add_control(
			'button_type',
			[
				'label'     => esc_html__( 'Type', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'primary',
				'options'   => [
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
				'condition' => [ 'enable_button' => 'yes' ],
			]
		);

		$repeater_skin->add_control(
			'button_shape',
			[
				'label'     => esc_html__( 'Shape', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''             => esc_html__( 'Default', 'silicon-elementor' ),
					'rounded-pill' => esc_html__( 'Pill', 'silicon-elementor' ),
					'rounded-0'    => esc_html__( 'Square', 'silicon-elementor' ),
				],
				'condition' => [ 'enable_button' => 'yes' ],
			]
		);

		$repeater_skin->add_control(
			'enable_shadow',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Shadow', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Disable', 'silicon-elementor' ),
				'condition' => [ 'enable_button' => 'yes' ],
			]
		);

		$repeater_skin->add_control(
			'button_size',
			[
				'label'     => esc_html__( 'Size', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''   => esc_html__( 'Default', 'silicon-elementor' ),
					'sm' => esc_html__( 'Small', 'silicon-elementor' ),
					'lg' => esc_html__( 'Large', 'silicon-elementor' ),
				],
				'condition' => [ 'enable_button' => 'yes' ],
			]
		);

		$this->add_control(
			'slides_skin',
			[
				'label'     => esc_html__( 'Slides', 'silicon-elementor' ),
				'type'      => Controls_Manager::REPEATER,
				'fields'    => $repeater_skin->get_controls(),
				'default'   => $this->get_repeater_skin_defaults(),
				'condition' => [ '_skin' => 'content-carousel-v2' ],
			]
		);

		$repeater_tab = new Repeater();

		$repeater_tab->add_control(
			'image',
			[
				'label' => esc_html__( 'Image', 'silicon-elementor' ),
				'type'  => Controls_Manager::MEDIA,
			]
		);

		$repeater_tab->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
				'default'   => 'custom',
				'separator' => 'none',
			]
		);

		$repeater_tab->add_control(
			'bg_image',
			[
				'label' => esc_html__( 'Background Image', 'silicon-elementor' ),
				'type'  => Controls_Manager::MEDIA,
			]
		);

		$repeater_tab->add_control(
			'title',
			[
				'label'   => esc_html__( 'Title', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Cashless payment case study', 'silicon-elementor' ),
			]
		);

		$repeater_tab->add_control(
			'title_tag',
			[
				'label'   => esc_html__( 'Title HTML Tag', 'silicon-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				],
				'default' => 'h3',
			]
		);

		$repeater_tab->add_control(
			'subtitle',
			[
				'label'   => esc_html__( 'Subtitle', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Payment Service Provider Company', 'silicon-elementor' ),
			]
		);

		$repeater_tab->add_control(
			'description',
			[
				'label'     => esc_html__( 'Description', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXTAREA,
				'separator' => 'before',
				'default'   => esc_html__( 'Aenean dolor elit tempus tellus imperdiet. Elementum, ac convallis morbi sit est feugiat ultrices. Cras tortor maecenas pulvinar nec ac justo. Massa sem eget semper...', 'silicon-elementor' ),
			]
		);

		$repeater_tab->add_control(
			'enable_button',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Button', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Disable', 'silicon-elementor' ),
				'separator' => 'before',
			]
		);

		$repeater_tab->add_control(
			'button_text',
			[
				'label'     => esc_html__( 'Button Text', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'View case study', 'silicon-elementor' ),
				'condition' => [ 'enable_button' => 'yes' ],
			]
		);

		$repeater_tab->add_control(
			'button_link',
			[
				'label'     => esc_html__( 'Link', 'silicon-elementor' ),
				'type'      => Controls_Manager::URL,
				'condition' => [ 'enable_button' => 'yes' ],
			]
		);

		$repeater_tab->add_control(
			'button_type',
			[
				'label'     => esc_html__( 'Type', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'primary',
				'options'   =>
				[
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
				'condition' => [ 'enable_button' => 'yes' ],
			]
		);

		$repeater_tab->add_control(
			'button_shape',
			[
				'label'     => esc_html__( 'Shape', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''             => esc_html__( 'Default', 'silicon-elementor' ),
					'rounded-pill' => esc_html__( 'Pill', 'silicon-elementor' ),
					'rounded-0'    => esc_html__( 'Square', 'silicon-elementor' ),
				],
				'condition' => [ 'enable_button' => 'yes' ],
			]
		);

		$repeater_tab->add_control(
			'enable_shadow',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Shadow', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Disable', 'silicon-elementor' ),
				'condition' => [ 'enable_button' => 'yes' ],
			]
		);

		$repeater_tab->add_control(
			'button_size',
			[
				'label'     => esc_html__( 'Size', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''   => esc_html__( 'Default', 'silicon-elementor' ),
					'sm' => esc_html__( 'Small', 'silicon-elementor' ),
					'lg' => esc_html__( 'Large', 'silicon-elementor' ),
				],
				'condition' => [ 'enable_button' => 'yes' ],
			]
		);

		$this->add_control(
			'slides_tab',
			[
				'label'     => esc_html__( 'Slides', 'silicon-elementor' ),
				'type'      => Controls_Manager::REPEATER,
				'fields'    => $repeater_tab->get_controls(),
				'default'   => $this->get_repeater_tab_defaults(),
				'condition' => [ '_skin' => 'content-carousel-v3' ],
			]
		);

		$this->end_injection();

		$this->start_controls_section(
			'section_animation',
			[
				'label'     => esc_html__( 'Animation', 'silicon-elementor' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => [ '_skin' => 'content-carousel-v2' ],

			]
		);

		$this->add_control(
			'title_animation_heading',
			[
				'label'     => esc_html__( 'Title', 'silicon-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'enable_title_animation',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Animation', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Disable', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'animation_title_direction',
			[
				'label'     => esc_html__( 'Animation Direction', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'from-start',
				'options'   => [
					''            => esc_html__( 'None', 'silicon-elementor' ),
					'from-top'    => esc_html__( 'Top', 'silicon-elementor' ),
					'from-bottom' => esc_html__( 'Bottom', 'silicon-elementor' ),
					'from-start'  => esc_html__( 'Start', 'silicon-elementor' ),
					'from-end'    => esc_html__( 'End', 'silicon-elementor' ),
				],
				'condition' =>
				[
					'enable_title_animation' => 'yes',
				],
			]
		);

		$this->add_control(
			'scale_title_direction',
			[
				'label'     => esc_html__( 'Scale Direction', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   =>
				[
					''           => esc_html__( 'None', 'silicon-elementor' ),
					'scale-up'   => esc_html__( 'Up', 'silicon-elementor' ),
					'scale-down' => esc_html__( 'Down', 'silicon-elementor' ),
				],
				'condition' =>
				[
					'enable_title_animation' => 'yes',
				],
			]
		);

		$this->add_control(
			'enable_title_delay',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Delay', 'silicon-elementor' ),
				'default'   => 'no',
				'label_off' => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Disable', 'silicon-elementor' ),
				'condition' =>
				[
					'enable_title_animation' => 'yes',
				],
			]
		);

		$this->add_control(
			'animation_title_delay',
			[
				'label'     => esc_html__( 'Delay', 'silicon-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 4,
				'default'   => 1,
				'condition' =>
				[
					'enable_title_animation' => 'yes',
					'enable_title_delay'     => 'yes',
				],
			]
		);

		$this->add_control(
			'description_animation_heading',
			[
				'label'     => esc_html__( 'Description', 'silicon-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'enable_description_animation',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Animation', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Disable', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'animation_description_direction',
			[
				'label'     => esc_html__( 'Animation Direction', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'from-end',
				'options'   => [
					''            => esc_html__( 'None', 'silicon-elementor' ),
					'from-top'    => esc_html__( 'Top', 'silicon-elementor' ),
					'from-bottom' => esc_html__( 'Bottom', 'silicon-elementor' ),
					'from-start'  => esc_html__( 'Start', 'silicon-elementor' ),
					'from-end'    => esc_html__( 'End', 'silicon-elementor' ),
				],
				'condition' =>
				[
					'enable_description_animation' => 'yes',
				],
			]
		);

		$this->add_control(
			'scale_description_direction',
			[
				'label'     => esc_html__( 'Scale Direction', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   =>
				[
					''           => esc_html__( 'None', 'silicon-elementor' ),
					'scale-up'   => esc_html__( 'Up', 'silicon-elementor' ),
					'scale-down' => esc_html__( 'Down', 'silicon-elementor' ),
				],
				'condition' =>
				[
					'enable_description_animation' => 'yes',
				],
			]
		);

		$this->add_control(
			'enable_description_delay',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Delay', 'silicon-elementor' ),
				'default'   => 'no',
				'label_off' => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Disable', 'silicon-elementor' ),
				'condition' =>
				[
					'enable_description_animation' => 'yes',
				],
			]
		);

		$this->add_control(
			'animation_description_delay',
			[
				'label'     => esc_html__( 'Delay', 'silicon-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 4,
				'default'   => 1,
				'condition' =>
				[
					'enable_description_animation' => 'yes',
					'enable_description_delay'     => 'yes',
				],
			]
		);

		$this->add_control(
			'button_animation_heading',
			[
				'label'     => esc_html__( 'Title', 'silicon-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'enable_button_animation',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Animation', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Disable', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'animation_button_direction',
			[
				'label'     => esc_html__( 'Animation Direction', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''            => esc_html__( 'None', 'silicon-elementor' ),
					'from-top'    => esc_html__( 'Top', 'silicon-elementor' ),
					'from-bottom' => esc_html__( 'Bottom', 'silicon-elementor' ),
					'from-start'  => esc_html__( 'Start', 'silicon-elementor' ),
					'from-end'    => esc_html__( 'End', 'silicon-elementor' ),
				],
				'condition' =>
				[
					'enable_button_animation' => 'yes',
				],
			]
		);

		$this->add_control(
			'scale_button_direction',
			[
				'label'     => esc_html__( 'Scale Direction', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'scale-up',
				'options'   =>
				[
					''           => esc_html__( 'None', 'silicon-elementor' ),
					'scale-up'   => esc_html__( 'Up', 'silicon-elementor' ),
					'scale-down' => esc_html__( 'Down', 'silicon-elementor' ),
				],
				'condition' =>
				[
					'enable_button_animation' => 'yes',
				],
			]
		);

		$this->add_control(
			'enable_button_delay',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Delay', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Disable', 'silicon-elementor' ),
				'condition' =>
				[
					'enable_button_animation' => 'yes',
				],
			]
		);

		$this->add_control(
			'animation_button_delay',
			[
				'label'     => esc_html__( 'Delay', 'silicon-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 4,
				'default'   => 1,
				'condition' =>
				[
					'enable_button_animation' => 'yes',
					'enable_delay'            => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_injection(
			[
				'at' => 'before',
				'of' => 'section_slides_style',
			]
		);

		$this->start_controls_section(
			'repeater_content',
			[
				'label' => esc_html__( 'Content', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,

			]
		);

		$this->add_control(
			'title_heading',
			[
				'label' => esc_html__( 'Title', 'silicon-elementor' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => __( 'Typography', 'silicon-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .swiper-title',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Text Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-title' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label'     => esc_html__( 'Hover Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-title-hover:hover, {{WRAPPER}} .swiper-title-hover:focus' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'title_css',
			[
				'label'     => esc_html__( 'CSS Class', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'title'     => esc_html__( 'Add your custom class for text without the dot. e.g: my-class', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'subtitle_heading',
			[
				'label' => esc_html__( 'Subtitle', 'silicon-elementor' ),
				'type'  => Controls_Manager::HEADING,
				'condition' =>
				[
					'_skin'       => 'content-carousel-v3',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'subtitle_typography',
				'label'    => __( 'Typography', 'silicon-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .swiper-subtitle',
				'condition' =>
				[
					'_skin'       => 'content-carousel-v3',
				],
			]
		);

		$this->add_control(
			'subtitle_color',
			[
				'label'     => esc_html__( 'Text Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-subtitle' => 'color: {{VALUE}} !important;',
				],
				'condition' =>
				[
					'_skin'       => 'content-carousel-v3',
				],
			]
		);

		$this->add_control(
			'subtitle_css',
			[
				'label'     => esc_html__( 'CSS Class', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'title'     => esc_html__( 'Add your custom class for text without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'   => 'fs-sm text-muted border-bottom pb-3 mb-3',
				'condition' =>
				[
					'_skin'       => 'content-carousel-v3',
				],
			]
		);

		$this->add_control(
			'desc_heading',
			[
				'label' => esc_html__( 'Description', 'silicon-elementor' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
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
				'selector'  => '{{WRAPPER}} .swiper-description',
			]
		);

		$this->add_control(
			'desc_color',
			[
				'label'     => esc_html__( 'Text Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-description' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'desc_css',
			[
				'label'     => esc_html__( 'CSS Class', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'title'     => esc_html__( 'Add your custom class for text without the dot. e.g: my-class', 'silicon-elementor' ),
				'separator' => 'after',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'icon_style',
			[
				'label' => esc_html__( 'Icon', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' =>
				[
					'_skin'       => 'content-carousel-v1',
					'enable_icon' => 'yes',
				],

			]
		);

		$this->add_control(
			'icon_heading',
			[
				'label' => esc_html__( 'Icon', 'silicon-elementor' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'icon_size',
			[
				'label'     => esc_html__( 'Icon Size', 'silicon-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'default'   => [
					'size' => 28,
				],
				'selectors' => [
					'{{WRAPPER}} .sn-content-icon' => 'font-size: {{SIZE}}{{UNIT}} ',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => esc_html__( 'Icon Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sn-content-icon' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'icon_hover_color',
			[
				'label'     => esc_html__( 'Hover Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sn-content-icon:hover, {{WRAPPER}} .sn-content-icon:focus' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'icon_css',
			[
				'label'     => esc_html__( 'CSS Class', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'text-primary fs-3 fw-normal ms-2 mt-1',
				'title'     => esc_html__( 'Add your custom class for text without the dot. e.g: my-class', 'silicon-elementor' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'repeater_button',
			[
				'label' => esc_html__( 'Button', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,

			]
		);

		$this->add_control(
			'button_heading',
			[
				'label' => esc_html__( 'Button', 'silicon-elementor' ),
				'type'  => Controls_Manager::HEADING,
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
			'button_css',
			[
				'label'     => esc_html__( 'CSS Class', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'title'     => esc_html__( 'Add your custom class for text without the dot. e.g: my-class', 'silicon-elementor' ),
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

			$this->start_controls_tab(
				'tab_button_normal',
				[
					'label' => esc_html__( 'Normal', 'silicon-elementor' ),
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
			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_button_hover',
				[
					'label' => esc_html__( 'Hover', 'silicon-elementor' ),
				]
			);

				$this->add_control(
					'button_text_hover',
					[
						'label'     => esc_html__( 'Hover Color', 'silicon-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .swiper-button:hover, {{WRAPPER}} .swiper-button-hover:focus' => 'color: {{VALUE}} !important;',
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

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

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
			'tab_id',
			[
				'label' => esc_html__( 'Tabs ID', 'silicon-elementor' ),
				'type'  => Controls_Manager::TEXT,
			]
		);

		$repeater->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'silicon-elementor' ),
				'type'  => Controls_Manager::TEXTAREA,
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'silicon-elementor' ),
				'type'  => Controls_Manager::URL,
			]
		);

		$repeater->add_control(
			'description',
			[
				'label' => esc_html__( 'Description', 'silicon-elementor' ),
				'type'  => Controls_Manager::TEXTAREA,
			]
		);

		$repeater->add_control(
			'enable_button',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Button', 'silicon-elementor' ),
				'default'   => 'no',
				'label_off' => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Disable', 'silicon-elementor' ),
			]
		);

		$repeater->add_control(
			'button_text',
			[
				'label'   => esc_html__( 'Button Text', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Learn More', 'silicon-elementor' ),
				'condition' => [ 'enable_button' => 'yes' ],
			]
		);

		$repeater->add_control(
			'button_link',
			[
				'label' => esc_html__( 'Link', 'silicon-elementor' ),
				'type'  => Controls_Manager::URL,
				'condition' => [ 'enable_button' => 'yes' ],
			]
		);

		$repeater->add_control(
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
				'condition' => [ 'enable_button' => 'yes' ],
			]
		);

		$repeater->add_control(
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
				'condition' => [ 'enable_button' => 'yes' ],
			]
		);

		$repeater->add_control(
			'enable_shadow',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Shadow', 'silicon-elementor' ),
				'default'   => 'no',
				'label_off' => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Disable', 'silicon-elementor' ),
				'condition' => [ 'enable_button' => 'yes' ],
			]
		);

		$repeater->add_control(
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
				'condition' => [ 'enable_button' => 'yes' ],
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
				'title'       => esc_html__( 'Transportation & Logistics', 'silicon-elementor' ),
				'description' => esc_html__( 'Risus massa fames metus lectus diam maecenas dui. Nibh morbi id purus eget tellus diam, integer blandit. Ac condimentum a nisl sagittis, interdum. Et viverra maecenas quis cras sed gravida volutpat, cursus enim. Enim ut nulla netus porta lacus diam. Et enim ultrices nunc, nunc. In iaculis venenatis at sit.', 'silicon-elementor' ),
			],
			[
				'title'       => esc_html__( 'Construction & Real Estate', 'silicon-elementor' ),
				'description' => esc_html__( 'Nunc, amet et, et at habitant. Eget quis justo, metus at metus sapien. Urna quisque rutrum pharetra pulvinar vitae quam blandit non. Orci tempor cursus egestas quis orci at nisi maecenas. Enim in ultrices tortor, nibh quis sollicitudin tellus non maecenas. In libero ut semper nunc magna tortor.', 'silicon-elementor' ),
			],
			[
				'title'       => esc_html__( 'Education', 'silicon-elementor' ),
				'description' => esc_html__( 'Vivamus nisl sit volutpat laoreet ligula et. Nunc, duis est justo, cras ipsum vulputate eget tellus augue. Amet, sagittis ut enim nisl commodo, pharetra. Sapien imperdiet tristique interdum aliquet varius vitae facilisis vel. Erat convallis eget elit eget iaculis. Morbi id facilisis ligula odio sed amet suspendisse duis aliquet. Justo quam convallis id sed.', 'silicon-elementor' ),
			],
		];
	}

	/**
	 * Slider defaults.
	 *
	 * @return string
	 */
	protected function get_repeater_skin_defaults() {
		$placeholder_image_src = Utils::get_placeholder_image_src();

		return [
			[
				'title'       => esc_html__( 'The Best IT Solutions for Your Business', 'silicon-elementor' ),
				'description' => esc_html__( 'We provide the wide range of high quality IT services and best practices solutions to our customers making their business better.', 'silicon-elementor' ),
				'button_text' => esc_html__( 'Start your project', 'silicon-elementor' ),
			],
			[
				'title'       => esc_html__( 'Award-Winning Software Development', 'silicon-elementor' ),
				'description' => esc_html__( 'We build complex web, desktop and mobile applications. With us you get quality software and perfect service every time.', 'silicon-elementor' ),
				'button_text' => esc_html__( 'View case studies', 'silicon-elementor' ),
			],
			[
				'title'       => esc_html__( '24/7 Tech & Business Support', 'silicon-elementor' ),
				'description' => esc_html__( "We ensure lifetime support of all applications we've built. Our support department is a team of professionals who will assist you 24/7.", 'silicon-elementor' ),
				'button_text' => esc_html__( 'Work with us', 'silicon-elementor' ),
			],
		];
	}

	/**
	 * Slider defaults.
	 *
	 * @return string
	 */
	protected function get_repeater_tab_defaults() {
		$placeholder_image_src = Utils::get_placeholder_image_src();

		return [
			[
				'title'       => esc_html__( 'Cashless payment case study', 'silicon-elementor' ),
				'subtitle'    => esc_html__( 'Payment Service Provider Company', 'silicon-elementor' ),
				'description' => esc_html__( 'Aenean dolor elit tempus tellus imperdiet. Elementum, ac convallis morbi sit est feugiat ultrices. Cras tortor maecenas pulvinar nec ac justo. Massa sem eget semper...', 'silicon-elementor' ),
				'button_text' => esc_html__( 'View case study', 'silicon-elementor' ),
			],
			[
				'title'       => esc_html__( 'Smart tech case study', 'silicon-elementor' ),
				'subtitle'    => esc_html__( 'Data Analytics Company', 'silicon-elementor' ),
				'description' => esc_html__( 'Adipiscing quis a at ligula. Gravida gravida diam rhoncus cursus in. Turpis sagittis tempor gravida phasellus sapien. Faucibus donec consectetur sed id sit nisl.', 'silicon-elementor' ),
				'button_text' => esc_html__( 'View case study', 'silicon-elementor' ),
			],
		];
	}

}
