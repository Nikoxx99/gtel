<?php
namespace SiliconElementor\Modules\Carousel\Widgets;

use Elementor\Controls_Manager;
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
 * Brand Carousel
 */
class Iconbox_Carousel extends Base {

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
		return 'sn-iconbox-carousel';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Iconbox Carousel', 'silicon-elementor' );
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
		return [ 'cards', 'carousel', 'image', 'brands', 'iconbox' ];
	}

	/**
	 * Register the Skins with the widget.
	 *
	 * @return void
	 */
	protected function register_skins() {
		$this->add_skin( new Skins\Skin_Iconbox_Carousel( $this ) );
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
				'at' => 'after',
				'of' => 'section_slides',
			]
		);

		$this->add_control(
			'style',
			[
				'label'   => esc_html__( 'Style', 'silicon-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default'  => 'Default',
					'style-v1' => 'Style v1',
				],
			]
		);

		$repeater_style = new Repeater();

		$repeater_style->add_control(
			'icon',
			[
				'label'   => esc_html__( 'Icon', 'silicon-elementor' ),
				'type'    => Controls_Manager::ICONS,
				'default' => [
					'value' => 'bx bx-rocket',
				// 'library' => 'fa-solid',
				],
			]
		);

		$repeater_style->add_control(
			'Title',
			[
				'label'   => esc_html__( 'Title', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html( 'Title1' ),
			]
		);

		$repeater_style->add_control(
			'Description',
			[
				'label'   => esc_html__( 'Description', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html( 'Id mollis consectetur congue egestas egestas suspendisse blandit justo. Tellus augue commodo id quis tempus etiam pulvinar at maecenas.' ),
			]
		);

		$repeater_style->add_control(
			'wrap_class',
			[
				'label'       => esc_html__( 'Wrap Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter your class', 'silicon-elementor' ),
			]
		);

		$repeater_style->add_control(
			'link_class',
			[
				'label'       => esc_html__( 'Link', 'silicon-elementor' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => esc_html__( 'Enter your Link', 'silicon-elementor' ),
				'default'     => [
					'url' => '#',
				],
			]
		);

		$this->add_control(
			'slides_style',
			[
				'label'     => esc_html__( 'Slides', 'silicon-elementor' ),
				'type'      => Controls_Manager::REPEATER,
				'fields'    => $repeater_style->get_controls(),
				'default'   => $this->get_repeater_defaults(),
				'condition' => [
					'style' => 'style-v1',
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
			'repeater_content',
			[
				'label' => __( 'Content', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,

			]
		);

		$this->add_control(
			'heading_icon',
			[
				'label'     => esc_html__( 'Icon', 'silicon-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_class',
			[
				'label'       => esc_html__( 'Icon Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'title'       => esc_html__( 'Add your custom class for icon  without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'     => 'display-5 fw-normal card-icon',
				'label_block' => true,
				'description' => esc_html__( 'Additional CSS class that you want to apply to the icon', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'   => esc_html__( 'Icon Color', 'silicon-elementor' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#b4b7c9',
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label'     => esc_html__( 'Button Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-icon-wrap__elementor:hover' => 'background-color: {{VALUE}} !important;',
				],
				'condition' => [
					'style' => 'style-v1',
				],
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-icon-wrap__elementor' => 'border-color: {{VALUE}} !important;',
				],
				'condition' => [
					'style' => 'style-v1',
				],
			]
		);

		$this->add_control(
			'icon_hover_color',
			[
				'label'     => esc_html__( 'Icon Hover Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .card-hover:hover .card-icon,{{WRAPPER}} .sn-icon-hover:focus' => 'color: {{VALUE}} !important;',
				],
				'default'   => '#6366f1',
				'separator' => 'after',
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label'     => esc_html__( 'Size', 'silicon-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .silicon-icon' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				],
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
			'card_hover_color',
			[
				'label'     => esc_html__( 'Hover Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .card-hover:hover, {{WRAPPER}} .sn-card-hover:focus' => 'background-color: {{VALUE}} !important;',
				],
				'condition' => [
					'show_hover' => 'yes',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'card_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .card' => 'background-color: {{VALUE}} !important;',
				],

			]
		);

		$this->add_control(
			'card_class',
			[
				'label'       => esc_html__( 'Card Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'title'       => esc_html__( 'Add your custom class for <div>  without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'     => 'mx-2',
				'label_block' => true,
				'description' => esc_html__( 'Additional CSS class that you want to apply to the <div>', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'heading_title',
			[
				'label'     => esc_html__( 'Title', 'silicon-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_class',
			[
				'label'       => esc_html__( 'Title Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'title'       => esc_html__( 'Add your custom class for <h> tag  without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'     => 'h5 pt-3 pb-1 mb-2',
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
				'selector'  => '{{WRAPPER}} .si-iconbox-title',
			]
		);

		$this->add_control(
			'heading_description',
			[
				'label'     => esc_html__( 'Description', 'silicon-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'description_class',
			[
				'label'       => esc_html__( 'Description Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'title'       => esc_html__( 'Add your custom class for <p> tag  without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'     => 'mb-0',
				'label_block' => true,
				'description' => esc_html__( 'Additional CSS class that you want to apply to the a tag', 'silicon-elementor' ),

			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => esc_html__( 'Description Color', 'silicon-elementor' ),
				'type'  => Controls_Manager::COLOR,
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
				'selector'  => '{{WRAPPER}} .si-iconbox-description',
			]
		);

		$this->end_controls_section();

		$this->end_injection();

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
			'icon',
			[
				'label'   => esc_html__( 'Icon', 'silicon-elementor' ),
				'type'    => Controls_Manager::ICONS,
				'default' => [
					'value' => 'bx bx-rocket',
				// 'library' => 'fa-solid',
				],
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
			'Description',
			[
				'label'   => esc_html__( 'Description', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html( 'Id mollis consectetur congue egestas egestas suspendisse blandit justo. Tellus augue commodo id quis tempus etiam pulvinar at maecenas.' ),
			]
		);

	}

	/**
	 * Slider defaults.
	 *
	 * @return string
	 */
	protected function get_repeater_defaults() {
		// $placeholder_image_src = Utils::get_placeholder_image_src();

		return [
			[
				'icon' => [
					'value' => 'bx bx-rocket',
				],

			],
			[
				'icon' => [
					'value' => 'bx bx-like',
				],
			],
			[
				'icon' => [
					'value' => 'bx bx-time-five',
				],
			],
			[
				'icon' => [
					'value' => 'bx bx-group',
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
	 * @param array $count the widget settings.
	 * @return void
	 */
	public function print_box_slide( array $slide, array $settings, $element_key, $count ) {

		$card_class = [ 'card h-100 card-body' ];
		if ( 'yes' === $settings['show_hover'] ) {
			$card_class[] = 'card-hover';
		}
		if ( $settings['card_class'] ) {
			$card_class[] = $settings['card_class'];
		}

		$icon_class = [ 'silicon-icon' ];
		if ( $settings['icon_class'] ) {
			$icon_class[] = $settings['icon_class'];
		}

		$title_class = [ 'si-iconbox-title' ];
		if ( $settings['title_class'] ) {
			$title_class[] = $settings['title_class'];
		}

		$description_class = [ 'si-iconbox-description' ];
		if ( $settings['description_class'] ) {
			$description_class[] = $settings['description_class'];
		}

		$wrap_class = [ 'btn btn-icon stretched-link si-icon-wrap__elementor' ];
		if ( ! empty( $slide['wrap_class'] ) ) {
			$wrap_class[] = $slide['wrap_class'];
		}

		$this->add_render_attribute(
			'card-' . $element_key,
			[
				'class' => $card_class,
			]
		);
		$this->add_render_attribute(
			'icon-' . $element_key,
			[
				'class' => $icon_class,
			]
		);
		$this->add_render_attribute(
			'icon-' . $element_key,
			[
				'class' => $slide['icon']['value'],
			]
		);

		if ( $settings['icon_color'] ) {
			$this->add_render_attribute(
				'icon-' . $element_key,
				[
					'style' => 'color: ' . $settings['icon_color'],
				]
			);
		}

		$this->add_render_attribute(
			'title-' . $element_key,
			[
				'class' => $title_class,
			]
		);

		if ( $settings['title_color'] ) {
			$this->add_render_attribute(
				'title-' . $element_key,
				[
					'style' => 'color: ' . $settings['title_color'],
				]
			);
		}
		$this->add_render_attribute(
			'description-' . $element_key,
			[
				'class' => $description_class,
			]
		);

		if ( $settings['description_color'] ) {
			$this->add_render_attribute(
				'description-' . $element_key,
				[
					'style' => 'color: ' . $settings['description_color'],
				]
			);
		}

		if ( 'style-v1' === $settings['style'] ) {
			if ( ! empty( $slide['link_class'] ) ) {
				$this->add_link_attributes(
					'link_wrap-' . $element_key,
					$slide['link_class']
				);
			}

			$this->add_render_attribute(
				'link_wrap-' . $element_key,
				[
					'class' => $wrap_class,
				]
			);
		}

		?>
			<?php $this->skin_slide_start( $settings, $element_key ); ?>
			<!-- <div class="swiper-slide h-auto py-3"> -->
			<?php if ( 'style-v1' !== $settings['style'] ) : ?>
				<div <?php $this->print_render_attribute_string( 'card-' . $element_key ); ?>>
					<i <?php $this->print_render_attribute_string( 'icon-' . $element_key ); ?>></i>
					<h3 <?php $this->print_render_attribute_string( 'title-' . $element_key ); ?>><?php echo esc_html( $slide['Title'] ); ?></h3>
					<p <?php $this->print_render_attribute_string( 'description-' . $element_key ); ?>><?php echo esc_html( $slide['Description'] ); ?></p>
				</div>
			<?php endif; ?>
			<?php
			if ( 'style-v1' === $settings['style'] ) :

				?>
			<div class="position-relative text-center border-end mx-n1">
				<a <?php $this->print_render_attribute_string( 'link_wrap-' . $element_key ); ?>>
					<i <?php $this->print_render_attribute_string( 'icon-' . $element_key ); ?>></i>
				</a>
				<div class="pt-4">
					<h6 <?php $this->print_render_attribute_string( 'title-' . $element_key ); ?>><?php echo esc_html( $slide['Title'] ); ?></h6>
					<p <?php $this->print_render_attribute_string( 'description-' . $element_key ); ?>><?php echo esc_html( $slide['Description'] ); ?></p>
				</div>
			</div>
				<?php
			endif;
			?>
			</div>
			<?php

	}
}

