<?php
namespace SiliconElementor\Modules\Carousel\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use SiliconElementor\Modules\QueryControl\Module as Module_Query;
use SiliconElementor\Modules\QueryControl\Controls\Group_Control_Related;
use SiliconElementor\Core\Utils as AR_Utils;
use SiliconElementor\Modules\Carousel\Skins;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Post Carousel
 */
class Post_Carousel extends Base {

	/**
	 * Query
	 *
	 * @var \WP_Query
	 */
	protected $query = null;

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
		return 'sn-post-carousel';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Post Carousel', 'silicon-elementor' );
	}

	/**
	 * Get the icon for the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-posts-carousel';
	}

	/**
	 * Get the keywords associated with the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'post-carousel', 'post', 'carousel', 'cpt', 'item', 'loop', 'query', 'cards', 'custom post type' ];
	}

	/**
	 * Register the Skins with the widget.
	 *
	 * @return void
	 */
	protected function register_skins() {
		$this->add_skin( new Skins\Skin_Post_Carousel( $this ) );
		$this->add_skin( new Skins\Skin_Post_Carousel_V2( $this ) );
		$this->add_skin( new Skins\Skin_Post_Carousel_V3( $this ) );
		$this->add_skin( new Skins\Skin_Post_Carousel_V4( $this ) );
	}

	/**
	 * Get Query.
	 *
	 * @return array
	 */
	public function get_query() {
		return $this->query;
	}

	/**
	 * Get post type object on import.
	 *
	 * @param array $element settings posttype.
	 * @return array
	 */
	public function on_import( $element ) {
		if ( ! get_post_type_object( $element['settings']['posts_post_type'] ) ) {
			$element['settings']['posts_post_type'] = 'post';
		}

		return $element;
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
	 * Print the slide.
	 *
	 * @param array $slide the slide settings.
	 * @param array $settings the widget settings.
	 * @param array $element_key the element_key slider id.
	 * @return void
	 */
	public function print_slide( array $slide, array $settings, $element_key ) {}

	/**
	 * Register repeater controls for this widget.
	 *
	 * @param Repeater $repeater repeater control arguments.
	 * @return void
	 */
	protected function add_repeater_controls( Repeater $repeater ) {}

	/**
	 * Post Slider defaults.
	 *
	 * @return void
	 */
	protected function get_repeater_defaults() {}

	/**
	 * Register controls for this widget.
	 *
	 * @return void
	 */
	protected function register_controls() {

		$this->register_title_controls();
		parent::register_controls();
		$this->remove_control( 'slides' );
		$this->register_query_section_controls();

	}

	/**
	 * Get posts.
	 *
	 * @param array $settings settings.
	 * @return void
	 */
	public function query_posts( $settings ) {
		$query_args = [
			'posts_per_page' => $settings['posts_per_page'],
		];

		// @var Module_Query $elementor_query
		$elementor_query = Module_Query::instance();
		$this->query     = $elementor_query->get_query( $this, 'posts', $query_args, [] );
	}

	/**
	 * Register Query Section Controls for this widget.
	 *
	 * @return void
	 */
	protected function register_query_section_controls() {
		$this->start_controls_section(
			'section_post_carousel_query',
			[
				'label' => esc_html__( 'Query', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_group_control(
			Group_Control_Related::get_type(),
			[
				'name'    => 'posts',
				'presets' => [ 'full' ],
				'exclude' => [
					'posts_per_page', // use the one from Layout section.
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register Title Controls for this widget.
	 *
	 * @return void
	 */
	protected function register_title_controls() {
		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label'   => __( 'Posts Per Page', 'silicon-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'default' => 3,
			]
		);

		$this->add_control(
			'show_title',
			[
				'label'     => esc_html__( 'Show Title', 'silicon-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'silicon-elementor' ),
				'label_off' => esc_html__( 'Hide', 'silicon-elementor' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label'     => esc_html__( 'Title HTML Tag', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
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
				'default'   => 'h3',
				'condition' => [ 'show_title' => 'yes' ],
			]
		);

		$this->add_control(
			'show_excerpt',
			[
				'label'     => esc_html__( 'Show Excerpt', 'silicon-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'silicon-elementor' ),
				'label_off' => esc_html__( 'Hide', 'silicon-elementor' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'enable_author',
			[
				'label'     => esc_html__( 'Show Author Details', 'silicon-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'silicon-elementor' ),
				'label_off' => esc_html__( 'Hide', 'silicon-elementor' ),
				'default'   => 'yes',
				'condition' => [
					'_skin' => 'post-carousel-v1',
				],
			]
		);

		$this->add_control(
			'enable_author_desc',
			[
				'label'     => esc_html__( 'Show Author Description', 'silicon-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'silicon-elementor' ),
				'label_off' => esc_html__( 'Hide', 'silicon-elementor' ),
				'default'   => 'yes',
				'condition' => [
					'_skin'          => 'post-carousel-v1',
					'display_style!' => 'full_width',
					'enable_author'  => 'yes',
				],
			]
		);

		$this->add_control(
			'enable_category',
			[
				'label'     => esc_html__( 'Show Category', 'silicon-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'silicon-elementor' ),
				'label_off' => esc_html__( 'Hide', 'silicon-elementor' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'enable_meta',
			[
				'label'     => esc_html__( 'Show Meta Data', 'silicon-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'silicon-elementor' ),
				'label_off' => esc_html__( 'Hide', 'silicon-elementor' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'meta_data',
			[
				'label'       => esc_html__( 'Meta Data', 'silicon-elementor' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT2,
				'default'     => [ 'date' ],
				'multiple'    => true,
				'options'     => [
					'author'   => esc_html__( 'Author', 'silicon-elementor' ),
					'date'     => esc_html__( 'Date', 'silicon-elementor' ),
					'time'     => esc_html__( 'Time', 'silicon-elementor' ),
					'comments' => esc_html__( 'Comments', 'silicon-elementor' ),
					'modified' => esc_html__( 'Date Modified', 'silicon-elementor' ),
				],
				'condition'   => [
					'enable_meta' => 'yes',
					'_skin'       => [ 'post-carousel-v1' ],
				],
			]
		);

		$this->add_control(
			'meta_data_v2',
			[
				'label'       => esc_html__( 'Meta Data', 'silicon-elementor' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT2,
				'default'     => [ 'date' ],
				'multiple'    => true,
				'options'     => [
					'author'   => esc_html__( 'Author', 'silicon-elementor' ),
					'date'     => esc_html__( 'Date', 'silicon-elementor' ),
					'time'     => esc_html__( 'Time', 'silicon-elementor' ),
					'modified' => esc_html__( 'Date Modified', 'silicon-elementor' ),
				],
				'condition'   => [
					'enable_meta' => 'yes',
					'_skin'       => 'post-carousel-v2',
				],
			]
		);

		$this->add_control(
			'meta_separator',
			[
				'label'     => esc_html__( 'Separator Between', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => ',',
				'selectors' => [
					'{{WRAPPER}} .sn-elementor-post__meta-data span + span:before' => 'content: "{{VALUE}}"',
				],
				'condition' => [
					'enable_meta' => 'yes',
					'_skin'       => [ 'post-carousel-v1', 'post-carousel-v2' ],
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_meta_content',
			[
				'label'      => __( 'Meta Content', 'silicon-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition'  => [
					'enable_meta' => 'yes',
					'_skin!'      => array( 'post-carousel-v3', 'post-carousel-v4' ),
				],
			]
		);

		$this->add_responsive_control(
			'meta_align',
			[
				'label'     => esc_html__( 'Alignment', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'start'         => esc_html__( 'Left', 'silicon-elementor' ),
					'center'        => esc_html__( 'Center', 'silicon-elementor' ),
					'end'           => esc_html__( 'Right', 'silicon-elementor' ),
					'space-between' => esc_html__( 'Space Between', 'silicon-elementor' ),
					'space-around'  => esc_html__( 'Space Around', 'silicon-elementor' ),
					'space-evenly'  => esc_html__( 'Space Evenly', 'silicon-elementor' ),
				],
				'default'   => 'space-between',
				'selectors' => [
					'{{WRAPPER}} .sn-meta' => 'justify-content: {{VALUE}} !important;',
				],
				'condition' => [
					'enable_meta' => 'yes',
				],
			]
		);

		// $this->add_responsive_control(
		// 'meta_veritcal_text_align',
		// [
		// 'label'     => esc_html__( 'Vertical Alignment', 'silicon-elementor' ),
		// 'type'      => Controls_Manager::CHOOSE,
		// 'options'   => [
		// 'start'  => [
		// 'title' => esc_html__( 'Top', 'silicon-elementor' ),
		// 'icon'  => 'eicon-text-align-left',
		// ],
		// 'center' => [
		// 'title' => esc_html__( 'Middle', 'silicon-elementor' ),
		// 'icon'  => 'eicon-text-align-center',
		// ],
		// 'end'    => [
		// 'title' => esc_html__( 'Bottom', 'silicon-elementor' ),
		// 'icon'  => 'eicon-text-align-right',
		// ],
		// ],
		// 'default'   => 'start',
		// 'selectors' => [
		// '{{WRAPPER}} .sn-meta' => 'align-items: {{VALUE}} !important;',
		// ],
		// ]
		// );

		$this->add_control(
			'meta_color',
			[
				'label'     => esc_html__( 'Meta Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .custom-meta-color' => 'color: {{VALUE}} !important',
				],
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'condition' => [ 'enable_meta' => 'yes' ],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'meta_typography',
				'selector'  => '{{WRAPPER}} .silicon-elementor-meta__name',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'condition' => [ 'enable_meta' => 'yes' ],
			]
		);

		$this->add_control(
			'meta_separator_color',
			[
				'label'     => esc_html__( 'Separator Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sn-elementor-post__meta-data span:before' => 'color: {{VALUE}};',
				],
				'condition' => [
					'enable_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'meta_css',
			[
				'label'     => esc_html__( 'Meta CSS Class', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'title'     => esc_html__( 'Add your custom class for text without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'   => 'fs-sm',
				'condition' => [
					'enable_meta' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_style',
			[
				'label'      => __( 'Content', 'silicon-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'conditions' => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => '_skin',
							'operator' => '!==',
							'value'    => 'post-carousel-v3',
						],
						[
							'name'     => '_skin',
							'operator' => '!==',
							'value'    => 'post-carousel-v4',
						],
						[
							'relation' => 'or',
							'terms'    => [
								[
									'name'     => 'show_title',
									'operator' => '===',
									'value'    => 'yes',
								],
								[
									'name'     => 'show_excerpt',
									'operator' => '===',
									'value'    => 'yes',
								],
								[
									'name'     => 'enable_category',
									'operator' => '===',
									'value'    => 'yes',
								],
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'card_heading',
			[
				'label'     => esc_html__( 'Card', 'silicon-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'none',
				'condition' => [
					'_skin' => 'post-carousel-v2',
				],
			]
		);

		$this->add_control(
			'card_hover_color',
			[
				'label'     => esc_html__( 'Hover Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sn-card-hover:hover, {{WRAPPER}} .sn-card-hover:focus' => 'background-color: {{VALUE}} !important;',
					'{{WRAPPER}} .sn-card-hover:hover svg path, {{WRAPPER}} .sn-card-hover:focus svg path' => 'fill: {{VALUE}} !important;',
				],
				'condition' => [
					'_skin' => 'post-carousel-v2',
				],
			]
		);

		$this->add_control(
			'title_heading',
			[
				'label'     => esc_html__( 'Title', 'silicon-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => [ 'show_title' => 'yes' ],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Title Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .custom-title-color' => 'color: {{VALUE}}',
				],
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'condition' => [ 'show_title' => 'yes' ],
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label'     => esc_html__( 'Hover Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .custom-title-color:hover, {{WRAPPER}} .custom-title-color:focus' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .custom-title-color:hover svg path, {{WRAPPER}} .custom-title-color:focus svg path' => 'fill: {{VALUE}} !important;',
				],
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'title_typography',
				'selector'  => '{{WRAPPER}} .silicon-elementor-title__name',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'condition' => [ 'show_title' => 'yes' ],
			]
		);

		$this->add_control(
			'title_css',
			[
				'label'     => esc_html__( 'CSS Class', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'title'     => esc_html__( 'Add your custom class for text without the dot. e.g: my-class', 'silicon-elementor' ),
				'condition' => [ 'show_title' => 'yes' ],
			]
		);

		$this->add_control(
			'excerpt_heading',
			[
				'label'     => esc_html__( 'Excerpt', 'silicon-elementor' ),
				'type'      => Controls_Manager::HEADING,
				// 'separator' => 'before',
				'condition' => [ 'show_excerpt' => 'yes' ],
			]
		);

		$this->add_control(
			'excerpt_color',
			[
				'label'     => esc_html__( 'Excerpt Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .custom-excerpt-color' => 'color: {{VALUE}}',
				],
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'condition' => [ 'show_excerpt' => 'yes' ],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'excerpt_typography',
				'selector'  => '{{WRAPPER}} .silicon-elementor-excerpt__name',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'condition' => [ 'show_excerpt' => 'yes' ],
			]
		);

		$this->add_control(
			'excerpt_css',
			[
				'label'     => esc_html__( 'CSS Class', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'title'     => esc_html__( 'Add your custom class for text without the dot. e.g: my-class', 'silicon-elementor' ),
				'condition' => [ 'show_excerpt' => 'yes' ],
				'separator' => 'after',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_author_style',
			[
				'label'      => __( 'Author Content', 'silicon-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition'  => [
					'_skin' => [ 'post-carousel-v1' ],
				],

			]
		);

		$this->add_responsive_control(
			'author_align',
			[
				'label'     => esc_html__( 'Vertical Alignment', 'silicon-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'start'  => [
						'title' => esc_html__( 'Top', 'silicon-elementor' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Middle', 'silicon-elementor' ),
						'icon'  => 'eicon-text-align-center',
					],
					'end'    => [
						'title' => esc_html__( 'Bottom', 'silicon-elementor' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'start',
				'selectors' => [
					'{{WRAPPER}} .sn-author' => 'justify-content: {{VALUE}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'author_veritcal_text_align',
			[
				'label'     => esc_html__( 'Vertical Alignment', 'silicon-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'start'  => [
						'title' => esc_html__( 'Top', 'silicon-elementor' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Middle', 'silicon-elementor' ),
						'icon'  => 'eicon-text-align-center',
					],
					'end'    => [
						'title' => esc_html__( 'Bottom', 'silicon-elementor' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'center',
				'selectors' => [
					'{{WRAPPER}} .sn-author' => 'align-items: {{VALUE}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'avatar_size',
			[
				'label'      => esc_html__( 'Avatar Size', 'silicon-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 500,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .sn-avatar' => 'width: {{SIZE}}{{UNIT}} !important;',
				],

			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'silicon-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .sn-avatar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_control(
			'name_heading',
			[
				'label'     => esc_html__( 'Name', 'silicon-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'name_color',
			[
				'label'     => esc_html__( 'Name Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sn-author-name' => 'color: {{VALUE}} !important;',
				],
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'author_name_typography',
				'selector' => '{{WRAPPER}} .sn-author-name',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->add_control(
			'name_css',
			[
				'label'   => esc_html__( 'CSS Class', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'title'   => esc_html__( 'Add your custom class for text without the dot. e.g: my-class', 'silicon-elementor' ),
				'default' => 'fs-base mb-0',
			]
		);

		$this->add_control(
			'desc_heading',
			[
				'label'     => esc_html__( 'Role', 'silicon-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'desc_color',
			[
				'label'     => esc_html__( 'Role Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sn-author-role' => 'color: {{VALUE}} !important;',
				],
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'author_desc_typography',
				'selector' => '{{WRAPPER}} .sn-author-role',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->add_control(
			'desc_css',
			[
				'label'     => esc_html__( 'CSS Class', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'title'     => esc_html__( 'Add your custom class for text without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'   => 'fs-sm',
				'separator' => 'after',
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render Meta.
	 *
	 * @return void
	 */
	public function render_meta() {
		$settings = $this->get_settings_for_display();
		if ( empty( $settings['meta_data'] ) ) {
			return;
		}
		?>
		<span class="sn-elementor-post__meta-data fs-sm">
			<?php
			if ( in_array( 'author', $settings['meta_data'] ) ) {
				$this->render_author( $settings );
			}

			if ( in_array( 'date', $settings['meta_data'] ) ) {
				$this->render_date( $settings );
			}

			if ( in_array( 'time', $settings['meta_data'] ) ) {
				$this->render_time( $settings );
			}
			if ( in_array( 'comments', $settings['meta_data'] ) ) {
				$this->render_comments_number( $settings );
			}
			if ( in_array( 'modified', $settings['meta_data'] ) ) {
				$this->render_date( $settings, 'modified' );
			}
			?>
		</span>
		<?php
	}

	/**
	 * Render Meta.
	 *
	 * @return void
	 */
	public function render_meta_v2() {
		$settings = $this->get_settings_for_display();
		if ( empty( $settings['meta_data_v2'] ) ) {
			return;
		}
		?>
		<span class="sn-elementor-post__meta-data fs-sm">
			<?php
			if ( in_array( 'author', $settings['meta_data_v2'] ) ) {
				$this->render_author( $settings );
			}

			if ( in_array( 'date', $settings['meta_data_v2'] ) ) {
				$this->render_date( $settings );
			}

			if ( in_array( 'time', $settings['meta_data_v2'] ) ) {
				$this->render_time( $settings );
			}
			if ( in_array( 'modified', $settings['meta_data_v2'] ) ) {
				$this->render_date( $settings, 'modified' );
			}
			?>
		</span>
		<?php
	}

	/**
	 * Render Comments Number.
	 *
	 * @param array $settings the widget settings..
	 * @return void
	 */
	public function render_comments_number( $settings ) {
		$meta_css = $settings['meta_css'];
		?>
		<span class="text-muted custom-meta-color silicon-elementor-meta__name <?php echo esc_attr( $meta_css ); ?>">
			<?php comments_number(); ?>
		</span>
		<?php
	}

	/**
	 * Render Author.
	 *
	 * @param array $settings the widget settings..
	 * @return void
	 */
	protected function render_author( $settings ) {
		$meta_css = $settings['meta_css'];
		?>
		<span class="text-muted custom-meta-color silicon-elementor-meta__name <?php echo esc_attr( $meta_css ); ?>">
			<?php the_author(); ?>
		</span>
		<?php
	}

	/**
	 * Render Time.
	 *
	 * @param array $settings the widget settings..
	 * @return void
	 */
	protected function render_time( $settings ) {
		$meta_css = $settings['meta_css'];
		?>
		<span class="text-muted custom-meta-color silicon-elementor-meta__name <?php echo esc_attr( $meta_css ); ?>">
			<?php the_time(); ?>
		</span>
		<?php
	}

	/**
	 * Render Category.
	 *
	 * @param array  $settings the widget settings.
	 * @param string $element_key the element_key.
	 * @return void
	 */
	public function render_category( array $settings, $element_key ) {
		?>
		<div class="fs-sm">
		<?php
		silicon_the_post_categories( 'grid-podcast' );
		?>
		</div>
		<?php
	}

	/**
	 * Render Date.
	 *
	 * @param array  $settings the widget settings.
	 * @param string $type date modified.
	 * @return void
	 */
	public function render_date( array $settings, $type = 'publish' ) {

		$meta_css = $settings['meta_css'];
		switch ( $type ) :
			case 'modified':
				$date = get_the_date();
				break;
			default:
				$date = get_the_modified_date();
		endswitch;
		?>
	<span class="text-muted custom-meta-color silicon-elementor-meta__name <?php echo esc_attr( $meta_css ); ?>"><?php echo esc_html( apply_filters( 'the_date', $date, get_option( 'date_format' ), '', '' ) ); ?></span>
		<?php
	}

	/**
	 * Render Author Info.
	 *
	 * @param array  $settings the widget settings.
	 * @param string $element_key the element_key.
	 * @return void
	 */
	public function render_author_info( array $settings, $element_key ) {

		$author_name = get_the_author_meta( 'display_name' );
		$author_desc = get_the_author_meta( 'description' );
		$name_class  = [ 'fw-semibold', 'sn-author-name' ];
		$desc_class  = [ 'text-muted', 'sn-author-role' ];
		if ( $settings['name_css'] ) {
			$name_class[] = $settings['name_css'];
		}
		if ( $settings['desc_css'] ) {
			$desc_class[] = $settings['desc_css'];
		}
		$this->add_render_attribute( 'author_name' . $element_key, 'class', $name_class );
		$this->add_render_attribute( 'author_desc' . $element_key, 'class', $desc_class );
		?>
			<div class="ps-3">
			<h6 <?php $this->print_render_attribute_string( 'author_name' . $element_key ); ?>><?php echo esc_html( $author_name ); ?></h6>
			<span <?php $this->print_render_attribute_string( 'author_desc' . $element_key ); ?>><?php echo esc_html( apply_filters( 'silicon_elementor_author_description', $author_desc ) ); ?></span>
			</div>
		<?php
	}

	/**
	 * Render Meta Data.
	 *
	 * @param array $settings the widget settings.
	 * @param array $element_key the element_key slider ID.
	 * @return void
	 */
	public function render_meta_data( array $settings, $element_key ) {
		?>
		<div class="d-flex align-items-center justify-content-between mb-3 sn-meta">
			<?php
			if ( $settings['enable_category'] ) {
				$this->render_category( $settings, $element_key );
			}
			if ( $settings['enable_meta'] ) {
				$this->render_meta();
			}
			?>
		</div>
		<?php
	}

	/**
	 * Render Title.
	 *
	 * @param array $element_key the element_key slider ID.
	 * @param array $settings the widget settings.
	 * @return void
	 */
	public function render_title( $element_key, array $settings ) {

		$this->add_render_attribute( 'title_text_' . $element_key, 'class', [ 'silicon-elementor-title__name', 'h5', 'mb-0' ] );

		if ( ! empty( $settings['title_css'] ) ) {
			$this->add_render_attribute( 'title_text_' . $element_key, 'class', $settings['title_css'] );
		}

		if ( ( 'yes' === $settings['show_title'] ) ) {
			?>
		<<?php echo esc_html( $settings['title_tag'] ); ?> <?php $this->print_render_attribute_string( 'title_text_' . $element_key ); ?>>
			<a class="custom-title-color" href="<?php echo esc_url( get_permalink() ); ?>">
				<?php the_title(); ?> 
			</a>
		</<?php echo esc_html( $settings['title_tag'] ); ?>>
			<?php
		}
	}

	/**
	 * Render button.
	 *
	 * @return void
	 */
	public function render_button() {
		?>
		<button type="button" id="prev-news" class="btn btn-prev btn-icon btn-sm position-absolute top-50 start-0 translate-middle-y d-none d-xl-inline-flex">
			<?php $this->render_swiper_button( 'prev' ); ?>
		</button>
		<button type="button" id="next-news" class="btn btn-next btn-icon btn-sm position-absolute top-50 end-0 translate-middle-y d-none d-xl-inline-flex">
			<?php $this->render_swiper_button( 'next' ); ?>
		</button>
		  <?php
	}

	/**
	 * Render Avatar.
	 *
	 * @param array $settings the widget settings.
	 * @param array $element_key the element_key slider ID.
	 * @return void
	 */
	public function render_avatar( array $settings, $element_key ) {
		echo wp_kses_post( get_avatar( get_the_author_meta( 'ID' ), 48, '', '', [ 'class' => 'sn-avatar rounded-circle' ] ) );
	}

	/**
	 * Render Comments.
	 *
	 * @param array $settings the widget settings.
	 * @return void
	 */
	public function render_comments( $settings ) {

		$count = get_comments_number() ? get_comments_number() : esc_html__( 'No comments', 'silicon-elementor' );
		?>
			<div class="d-flex align-items-center">
				<i class="bx bx-comment fs-lg me-1"></i>
				<span class="fs-sm">
				<?php echo esc_html( $count ); ?>
				</span>
			</div>
			<?php
	}

}
