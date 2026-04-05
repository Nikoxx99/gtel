<?php
namespace SiliconElementor\Modules\Carousel\Widgets;

use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use SiliconElementor\Modules\Carousel\Skins;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Testimonial Carousel
 */
class Testimonial_Carousel extends Base {

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
		return 'sn-testimonial-carousel';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Testimonial Carousel', 'silicon-elementor' );
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
		return [ 'cards', 'carousel', 'image', 'testimonial' ];
	}

	/**
	 * Register the Skins with the widget.
	 *
	 * @return void
	 */
	protected function register_skins() {
		$this->add_skin( new Skins\Skin_Testimonial_Carousel( $this ) );
		$this->add_skin( new Skins\Skin_Testimonial_Carousel_V2( $this ) );
		$this->add_skin( new Skins\Skin_Testimonial_Carousel_V3( $this ) );
		$this->add_skin( new Skins\Skin_Testimonial_Carousel_V4( $this ) );

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
	 * Register controls for this widget.
	 *
	 * @return void
	 */
	protected function register_controls() {
		parent::register_controls();
		// $this->remove_control( 'section_navigation' );

		$this->start_injection(
			[
				'of' => 'slides',
			]
		);

		$repeater_skin = new Repeater();

		$repeater_skin->add_control(
			'blockquote',
			[
				'label'   => esc_html__( 'Blockquote', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html( 'Id mollis consectetur congue egestas egestas suspendisse blandit justo. Tellus augue commodo id quis tempus etiam pulvinar at maecenas.' ),

			]
		);

		$repeater_skin->add_control(
			'avatar',
			[
				'label'   => esc_html__( 'Avatar', 'silicon-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater_skin->add_control(
			'name',
			[
				'label'   => esc_html__( 'Name', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html( 'Fannie Summers' ),

			]
		);

		$repeater_skin->add_control(
			'role',
			[
				'label'   => esc_html__( 'Role', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html( 'QA Engineer' ),

			]
		);

		$this->add_control(
			'slides_skin',
			[
				'label'      => esc_html__( 'Slides', 'silicon-elementor' ),
				'type'       => Controls_Manager::REPEATER,
				'fields'     => $repeater_skin->get_controls(),
				'default'    => $this->get_repeater_defaults(),
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => '_skin',
							'operator' => '===',
							'value'    => 'testimonial-carousel-2',
						],
						[
							'name'     => '_skin',
							'operator' => '===',
							'value'    => 'testimonial-carousel-3',
						],
					],
				],
			]
		);

		$repeater_tab = new Repeater();

		$repeater_tab->add_control(
			'blockquote',
			[
				'label'   => esc_html__( 'Blockquote', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html( 'Id mollis consectetur congue egestas egestas suspendisse blandit justo. Tellus augue commodo id quis tempus etiam pulvinar at maecenas.' ),

			]
		);

		$repeater_tab->add_control(
			'avatar',
			[
				'label' => esc_html__( 'Avatar', 'silicon-elementor' ),
				'type'  => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater_tab->add_control(
			'responsive_avatar',
			[
				'label' => esc_html__( 'Responsive View Avatar', 'silicon-elementor' ),
				'type'  => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater_tab->add_control(
			'logo',
			[
				'label'   => esc_html__( 'Logo', 'silicon-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater_tab->add_control(
			'name',
			[
				'label'   => esc_html__( 'Name', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html( 'Fannie Summers' ),

			]
		);

		$repeater_tab->add_control(
			'role',
			[
				'label'   => esc_html__( 'Role', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html( 'QA Engineer' ),

			]
		);

		$this->add_control(
			'slides_tabs',
			[
				'label'     => esc_html__( 'Slides', 'silicon-elementor' ),
				'type'      => Controls_Manager::REPEATER,
				'fields'    => $repeater_tab->get_controls(),
				'default'   => $this->get_tab_repeater_defaults(),
				'condition' => [
					'_skin' => 'testimonial-carousel-4',
				],
			]
		);

		$this->add_control(
			'enable_blockquote_icon',
			[
				'type'               => Controls_Manager::SWITCHER,
				'label'              => esc_html__( 'Enable Blockquote Icon', 'silicon-elementor' ),
				'default'            => 'no',
				'label_off'          => esc_html__( 'Show', 'silicon-elementor' ),
				'label_on'           => esc_html__( 'Hide', 'silicon-elementor' ),
				// 'conditions'         => [
				// 'relation' => 'or',
				// 'terms'    => [
				// [
				// 'name'     => '_skin',
				// 'operator' => '===',
				// 'value'    => 'testimonial-carousel-1',
				// ],
				// [
				// 'name'     => '_skin',
				// 'operator' => '===',
				// 'value'    => 'testimonial-carousel-3',
				// ],
				// ],
				// ],
			]
		);

		$this->add_control(
			'blockquote_icon',
			[
				'label'     => esc_html__( 'Blockquote Icon', 'silicon-elementor' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => [
					'value'   => 'bx bxs-quote-left',
				],
				'condition' => [
					'enable_blockquote_icon' => 'yes',
					// '_skin' => [ 'testimonial-carousel-1', 'testimonial-carousel-3' ],
				],
			]
		);

		$this->add_control(
			'enable_rating_icon',
			[
				'type'               => Controls_Manager::SWITCHER,
				'label'              => esc_html__( 'Enable Rating Icon', 'silicon-elementor' ),
				'default'            => 'no',
				'label_off'          => esc_html__( 'Show', 'silicon-elementor' ),
				'label_on'           => esc_html__( 'Hide', 'silicon-elementor' ),
				'frontend_available' => true,
				'conditions'         => [
					'relation' => 'or',
					'terms'    =>
					[
						[
							'name'     => '_skin',
							'operator' => '===',
							'value'    => 'testimonial-carousel-1',
						],
					],
				],
			]
		);

		// $this->add_control(
		// 'rating_icon',
		// [
		// 'label'     => esc_html__( 'Rating Icon', 'silicon-elementor' ),
		// 'type'      => Controls_Manager::ICONS,
		// 'default'   => [
		// 'value'   => 'fas fa-star',
		// 'library' => 'fa-solid',
		// ],
		// 'condition' => [
		// 'enable_rating_icon' => 'yes',
		// '_skin'              => 'testimonial-carousel-1',
		// ],
		// ]
		// );

		// $this->add_responsive_control(
		// 'space_between',
		// [
		// 'type'           => Controls_Manager::NUMBER,
		// 'label'          => esc_html__( 'Space Between', 'silicon-elementor' ),
		// 'description'    => esc_html__( 'Set Space between each Slides', 'silicon-elementor' ),
		// 'min'            => 1,
		// 'max'            => 100,
		// 'devices'        => [ 'desktop', 'tablet', 'mobile' ],
		// 'default'        => 24,
		// 'tablet_default' => 24,
		// 'mobile_default' => 24,
		// ]
		// );

		$this->end_injection();

		$this->start_injection(
			[
				'at' => 'before',
				'of' => 'section_slides_style',
			]
		);

		$this->start_controls_section(
			'repeater_content',
			[
				'label' => __( 'Content', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,

			]
		);

		$this->add_control(
			'blockquote',
			[
				'label' => esc_html__( 'Blockquote', 'silicon-elementor' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'blockquote_text',
				'label'    => __( 'Blockquote Text', 'silicon-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .swiper-slide blockquote p',
			]
		);

		$this->add_control(
			'blockquote_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-slide blockquote p' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'blockquote_text_css',
			[
				'label'       => esc_html__( 'Blockquote CSS', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'CSS Classes added to <p> in <blockquote>', 'silicon-elementor' ),
				'default'     => 'mb-0',

			]
		);

		$this->add_control(
			'blockquote_icon_heading',
			[
				'label' => esc_html__( 'Blockquote Icon', 'silicon-elementor' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'blockquote_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sn-blockquote' => 'background-color: {{VALUE}} !important;',
				],
				'condition' => [
					'enable_blockquote_icon' => 'yes',
				],
			]
		);

		$this->add_control(
			'blockquote_icon_color',
			[
				'label'     => esc_html__( 'Icon Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sn-blockquote-icon' => 'color: {{VALUE}} !important;',
				],
				'condition' => [
					'enable_blockquote_icon' => 'yes',
				],
			]
		);

		$this->add_control(
			'blockquote_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sn-blockquote' => 'border-color: {{VALUE}} !important;',
				],
				'condition' => [
					'enable_blockquote_icon' => 'yes',
				],
			]
		);

		$this->add_control(
			'rating_heading',
			[
				'label'      => esc_html__( 'Rating', 'silicon-elementor' ),
				'type'       => Controls_Manager::HEADING,
				'separator'  => 'before',
				'conditions' => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => '_skin',
							'operator' => '===',
							'value'    => 'testimonial-carousel-1',
						],
						[
							'name'     => 'enable_rating_icon',
							'operator' => '===',
							'value'    => 'yes',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'rating_icon_size',
			[
				'label'      => esc_html__( 'Rating Size', 'silicon-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 50,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .swiper-slide .sn-rating-icon' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				],
				'conditions' => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => '_skin',
							'operator' => '===',
							'value'    => 'testimonial-carousel-1',
						],
						[
							'name'     => 'enable_rating_icon',
							'operator' => '===',
							'value'    => 'yes',
						],
					],
				],
			]
		);

		$this->add_control(
			'avatar_heading',
			[
				'label'     => esc_html__( 'Avatar', 'silicon-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'width',
			[
				'type'      => Controls_Manager::SLIDER,
				'label'     => esc_html__( 'Image Width', 'silicon-elementor' ),
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 500,
					],
				],
				'default'   => [
					'size' => 48,
				],
				'selectors' => [
					'{{WRAPPER}} .sn-avatar' => 'width: {{SIZE}}{{UNIT}} !important;',
				],
				// 'separator' => 'after',
			]
		);

		$this->add_control(
			'avatar_css',
			[
				'label' => esc_html__( 'CSS classes', 'silicon-elementor' ),
				'type'  => Controls_Manager::TEXT,

			]
		);

		$this->add_control(
			'name_heading',
			[
				'label'     => esc_html__( 'Author Name', 'silicon-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'name',
				'label'    => __( 'Typography', 'silicon-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .sn-name',
			]
		);

		$this->add_control(
			'name_color',
			[
				'label'     => esc_html__( 'Text Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sn-name' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'name_tag',
			[
				'label'   => esc_html__( 'HTML Tag', 'silicon-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'h1'    => 'H1',
					'h2'    => 'H2',
					'h3'    => 'H3',
					'h4'    => 'H4',
					'h5'    => 'H5',
					'h6'    => 'H6',
					'div'   => 'div',
					'span'  => 'span',
					'small' => 'small',
					'p'     => 'p',
				],
				'default' => 'h6',
			]
		);

		$this->add_control(
			'name_css',
			[
				'label'       => esc_html__( 'Name CSS', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,

			]
		);

		$this->add_control(
			'role_heading',
			[
				'label'     => esc_html__( 'Author Role', 'silicon-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'role',
				'label'    => __( 'Typography', 'silicon-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .sn-role',
			]
		);

		$this->add_control(
			'role_color',
			[
				'label'     => esc_html__( 'Text Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sn-role' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'role_css',
			[
				'label'       => esc_html__( 'Role CSS', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'separator' => 'after',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_wrapper_css',
			[
				'label' => __( 'CSS Classes', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition'   => [
					'_skin'      => 'testimonial-carousel-4',
				],

			]
		);

		$this->add_control(
			'card_css',
			[
				'label'       => esc_html__( 'Card CSS', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'CSS Classes added to data-swiper-options attribute <div>', 'silicon-elementor' ),
				'default'     => 'p-4 p-xxl-5 mb-4 me-xxl-4',
			]
		);

		$this->add_control(
			'icons_wrapper_css',
			[
				'label'       => esc_html__( 'Icons Wrapper CSS', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'CSS Classes added to swiper-wrapper class <div>', 'silicon-elementor' ),
				'default'     => 'd-flex justify-content-between pb-4 mb-2',
				'condition'   => [
					'_skin'      => 'testimonial-carousel-4',
					'skin_style' => 'default',
				],

			]
		);

		$this->end_injection();

		$this->start_injection(
			[
				'at' => 'after',
				'of' => 'carousel_slide_css',
			]
		);

		$this->add_control(
			'figure_css',
			[
				'label'       => esc_html__( 'Figure CSS', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'CSS Classes added to <figure> in swiper-slide', 'silicon-elementor' ),

			]
		);

		$this->add_control(
			'figcaption_css',
			[
				'label'       => esc_html__( 'Card Footer CSS', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'CSS Classes added to <figcaption> in swiper-slide', 'silicon-elementor' ),
				'default'     => 'ps-4 pt-4',
				'condition'   => [
					'_skin'      => 'testimonial-carousel-1',
				],

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
			'blockquote',
			[
				'label'   => esc_html__( 'Blockquote', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html( 'Id mollis consectetur congue egestas egestas suspendisse blandit justo. Tellus augue commodo id quis tempus etiam pulvinar at maecenas.' ),

			]
		);

		$repeater->add_control(
			'rating_star',
			[
				'label'   => esc_html__( 'Rating Star', 'silicon-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
				],
				'default' => '5',
			]
		);

		$repeater->add_control(
			'avatar',
			[
				'label'   => esc_html__( 'Avatar', 'silicon-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'name',
			[
				'label'   => esc_html__( 'Name', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html( 'Fannie Summers' ),

			]
		);

		$repeater->add_control(
			'role',
			[
				'label'   => esc_html__( 'Role', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html( 'QA Engineer' ),

			]
		);

	}

	/**
	 * Slider defaults.
	 *
	 * @return string
	 */
	protected function get_tab_repeater_defaults() {
		$placeholder_image_src = Utils::get_placeholder_image_src();

		return [
			[
				'blockquote' => esc_html__( 'Dolor, a eget elementum, integer nulla volutpat, nunc, sit. Quam iaculis varius mauris magna sem. Egestas sed sed suscipit dolor faucibus dui imperdiet at eget. Tincidunt imperdiet quis hendrerit aliquam feugiat neque cras sed. Dictum quam integer volutpat tellus, faucibus platea. Pulvinar turpis proin faucibus at mauris. Sagittis gravida vitae porta enim, nulla arcu fermentum massa. Tortor ullamcorper lacus. Pellentesque lectus adipiscing aenean volutpat tortor habitant.', 'silicon-elementor' ),
				'avatar'     => [
					'url' => $placeholder_image_src,
				],
				'responsive_avatar'     => [
					'url' => $placeholder_image_src,
				],
				'logo'     => [
					'url' => $placeholder_image_src,
				],
				'name'   => esc_html__( 'Ralph Edwards', 'silicon-elementor' ),
				'role'   => esc_html__( 'Head of Marketing', 'silicon-elementor' ),
			],
			[
				'blockquote' => esc_html__( 'Mi semper risus ultricies orci pulvinar in at enim orci. Quis facilisis nunc pellentesque in ullamcorper sit. Lorem blandit arcu sapien, senectus libero, amet dapibus cursus quam. Eget pellentesque eu purus volutpat adipiscing malesuada. Purus nisi, tortor vel lacus. Donec diam molestie ultrices vitae eget pulvinar fames. Velit lacus mi purus velit justo, amet. Nascetur lobortis diam, duis orci.', 'silicon-elementor' ),
				'avatar'     => [
					'url' => $placeholder_image_src,
				],
				'responsive_avatar'     => [
					'url' => $placeholder_image_src,
				],
				'logo'     => [
					'url' => $placeholder_image_src,
				],
				'name'   => esc_html__( 'Annette Black', 'silicon-elementor' ),
				'role'   => esc_html__( 'Strategic Advisor', 'silicon-elementor' ),
			],
			[
				'blockquote' => esc_html__( 'Ac at sed sit senectus massa. Massa ante amet ultrices magna porta tempor. Aliquet diam in et magna ultricies mi at. Lectus enim, vel enim egestas nam pellentesque et leo. Elit mi faucibus laoreet aliquam pellentesque sed aliquet integer massa. Orci leo tortor ornare id mattis auctor aliquam volutpat aliquet. Odio lectus viverra eu blandit nunc malesuada vitae eleifend pulvinar. In ac fermentum sit in orci.', 'silicon-elementor' ),
				'avatar'     => [
					'url' => $placeholder_image_src,
				],
				'responsive_avatar'     => [
					'url' => $placeholder_image_src,
				],
				'logo'     => [
					'url' => $placeholder_image_src,
				],
				'name'   => esc_html__( 'Darrell Steward', 'silicon-elementor' ),
				'role'   => esc_html__( 'Project Manager', 'silicon-elementor' ),
			],
		];
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
				'blockquote' => esc_html__( 'Pellentesque finibus congue egestas egestas suspendisse blandit justo. Tellus augue commodo id quis tempus etiam pulvinar at maecenas.', 'silicon-elementor' ),
				'avatar'     => [
					'url' => $placeholder_image_src,
				],
				'name'   => esc_html__( 'Albert Flores', 'silicon-elementor' ),
				'role'   => esc_html__( 'PR Director', 'silicon-elementor' ),
			],
			[
				'blockquote' => esc_html__( 'Vivamus iaculis facilisis pretium. Pellentesque vitae mi odio. Donec imperdiet pellentesque ipsum quis pharetra. Nullam dolor sem.', 'silicon-elementor' ),
				'avatar' => [
					'url' => $placeholder_image_src,
				],
				'name'   => esc_html__( 'Wade Warren', 'silicon-elementor' ),
				'role'   => esc_html__( 'Illustrator', 'silicon-elementor' ),
			],
			[
				'blockquote' => esc_html__( 'Id mollis consectetur congue egestas egestas suspendisse blandit justo. Tellus augue commodo id quis tempus etiam pulvinar at maecenas.', 'silicon-elementor' ),
				'avatar' => [
					'url' => $placeholder_image_src,
				],
				'name'   => esc_html__( 'Fannie Summers', 'silicon-elementor' ),
				'role'   => esc_html__( 'Designer', 'silicon-elementor' ),
			],
			[
				'blockquote' => esc_html__( 'Phasellus luctus nisi id orci condimentum, at cursus nisl vestibulum. Orci varius natoque penatibus et magnis dis parturient montes.', 'silicon-elementor' ),
				'avatar' => [
					'url' => $placeholder_image_src,
				],
				'name'   => esc_html__( 'Robert Fox', 'silicon-elementor' ),
				'role'   => esc_html__( 'QA Engineer', 'silicon-elementor' ),
			],
		];
	}

	/**
	 * Print the stars in star rating.
	 *
	 * @param int    $rating The given rating.
	 * @param array  $settings the widget settings.
	 * @param string $element_key the element_key slider id.
	 */
	public function print_star_rating( $rating = 5, array $settings = array(), $element_key = '' ) {
		?>
			<div class="card-footer border-0 text-nowrap pt-0">
				<?php
				for ( $i = 0; $i < 5; $i++ ) {
					$icon_class = 'sn-rating-icon';
					$diff       = $rating - $i;
					if ( $diff > 0.5 ) {
						$icon_class = $icon_class . ' bx bxs-star text-warning';
					} else {
						$icon_class = $icon_class . ' bx bx-star text-muted opacity-75';
					}
					?>
						<i class="<?php echo esc_attr( $icon_class ); ?>"></i>
				<?php } ?>
			</div>
			<?php
	}

	/**
	 * Print the Blockquote.
	 *
	 * @param array $slide the slides settings.
	 * @param array $settings the widget settings.
	 * @param array $element_key the element_key slider id.
	 * @return void
	 */
	public function print_blockquote( array $slide, array $settings, $element_key ) {
		$this->add_render_attribute( 'blockquote-text-' . $element_key, 'class', $settings['blockquote_text_css'] );
		?>
		<blockquote class="card-body pb-3 mb-0">
			<p <?php $this->print_render_attribute_string( 'blockquote-text-' . $element_key ); ?>><?php echo esc_html( $slide['blockquote'] ); ?></p>
		</blockquote>
		<?php
	}

	/**
	 * Print the stars in star rating.
	 *
	 * @param array $slide the slides settings.
	 * @param array $settings the widget settings.
	 * @param array $element_key the element_key slider id.
	 * @return void
	 */
	public function print_blockquote_icon( array $slide, array $settings, $element_key ) {
		if ( ! isset( $settings['blockquote_icon']['value']['url'] ) ) {
			$this->add_render_attribute( 'icon-' . $element_key, 'class', 'sn-blockquote-icon ' . $settings['blockquote_icon']['value'] );
			?>
			<span class="sn-blockquote btn btn-icon btn-primary shadow-primary pe-none position-absolute top-0 start-0 translate-middle-y ms-4">
				<i <?php $this->print_render_attribute_string( 'icon-' . $element_key ); ?>></i>
			</span>
			<?php
		}
		if ( isset( $settings['blockquote_icon']['value']['url'] ) ) {
			?>
			<span class="sn-blockquote btn btn-icon btn-primary shadow-primary pe-none position-absolute top-0 start-0 translate-middle-y ms-4">
				<?php Icons_Manager::render_icon( $settings['blockquote_icon'], [ 'aria-hidden' => 'true' ] ); ?>
			</span>
			<?php
		}
	}

	/**
	 * Print the author.
	 *
	 * @param array $slide the slides settings.
	 * @param array $settings the widget settings.
	 * @param array $element_key the element_key slider id.
	 * @return void
	 */
	public function print_author( array $slide, array $settings, $element_key ) {
		$avatar_css = $settings['avatar_css'] ? 'sn-avatar ' . $settings['avatar_css'] : 'sn-avatar rounded-circle';
		$this->add_render_attribute(
			'avatar-' . $element_key,
			[
				'class' => $avatar_css,
				'src'   => $slide['avatar']['url'],
				'alt'   => Control_Media::get_image_alt( $slide['avatar'] ),
			]
		);
		$name_css = [ 'sn-name', 'mb-0' ];
		if ( $settings['name_css'] ) {
			$name_css[] = $settings['name_css'];
		}
		$this->add_render_attribute(
			'name-' . $element_key,
			[
				'class' => $name_css,
			]
		);
		$role_css = [ 'sn-role', 'text-muted' ];
		if ( $settings['role_css'] ) {
			$role_css[] = $settings['role_css'];
		}
		$this->add_render_attribute(
			'role-' . $element_key,
			[
				'class' => $role_css,
			]
		);
		$figcaption_css = $settings['figcaption_css'];
		if ( ! empty( $slide['avatar']['url'] ) ) {
			$figcaption_css .= ' d-flex align-items-center';
		}

		$this->add_render_attribute(
			'fig-caption-' . $element_key,
			[
				'class' => $figcaption_css,
			]
		);
		?>
		<figcaption <?php $this->print_render_attribute_string( 'fig-caption-' . $element_key ); ?>>
			<?php if ( ! empty( $slide['avatar']['url'] ) ) : ?>
			<img <?php $this->print_render_attribute_string( 'avatar-' . $element_key ); ?>>
			<div class="ps-3">
			<?php endif; ?>
				<<?php echo esc_html( $settings['name_tag'] ); ?> <?php $this->print_render_attribute_string( 'name-' . $element_key ); ?>><?php echo esc_html( $slide['name'] ); ?></<?php echo esc_html( $settings['name_tag'] ); ?>>
				<span <?php $this->print_render_attribute_string( 'role-' . $element_key ); ?>><?php echo esc_html( $slide['role'] ); ?></span>
			<?php if ( ! empty( $slide['avatar']['url'] ) ) : ?>
			</div>
			<?php endif; ?>
		</figcaption>
		<?php
	}

	/**
	 * Print the slide card.
	 *
	 * @param array $slide the slides settings.
	 * @param array $settings the widget settings.
	 * @param array $element_key the element_key slider id.
	 * @return void
	 */
	public function print_slide_card( array $slide, array $settings, $element_key ) {
		?>
		<div class="card h-100 position-relative border-0 shadow-sm pt-4">
		<?php
		if ( 'yes' === $settings['enable_blockquote_icon'] ) {
			$this->print_blockquote_icon( $slide, $settings, $element_key );
		}

			$this->print_blockquote( $slide, $settings, $element_key );

		if ( 'yes' === $settings['enable_rating_icon'] ) {
			$this->print_star_rating( $slide['rating_star'], $settings, $element_key );
		}
		?>
		</div>
		<?php
	}

	/**
	 * Print the slide.
	 *
	 * @param array $slide the slides settings.
	 * @param array $settings the widget settings.
	 * @param array $element_key the element_key slider id.
	 * @return void
	 */
	public function print_slide( array $slide, array $settings, $element_key ) {
		$this->add_render_attribute( 'carousel_slide_css-' . $element_key, 'class', 'h-auto pt-4' );
		$this->skin_slide_start( $settings, $element_key );
		?>
			<figure <?php $this->print_render_attribute_string( 'figure' ); ?>>
			<?php
			$this->print_slide_card( $slide, $settings, $element_key );
			$this->print_author( $slide, $settings, $element_key );
			?>
			</figure>
		</div>
		<?php
	}

}
