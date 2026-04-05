<?php
namespace SiliconElementor\Modules\Carousel\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Typography;
use Elementor\Control_Media;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use SiliconElementor\Modules\Carousel\Skins;
use SiliconElementor\Modules\HighlightedHeading\widgets;
use SiliconElementor\Core\Controls_Manager as SN_Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Mobile Carousel
 */
class Mobile_Carousel extends Base {

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
		return 'sn-mobile-carousel';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Mobile Carousel', 'silicon-elementor' );
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
		return [ 'cards', 'carousel', 'image', 'mobile' ];
	}

	/**
	 * Register the Skins with the widget.
	 *
	 * @return void
	 */
	protected function register_skins() {
		$this->add_skin( new Skins\Skin_Mobile_Carousel( $this ) );
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
	protected function print_slide( array $slide, array $settings, $element_key ) {

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
				'at' => 'before',
				'of' => 'section_slides',
			]
		);

		$this->start_controls_section(
			'section_title',
			[
				'label' => esc_html__( 'Title', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'before_title',
			[
				'label'       => esc_html__( 'Before Highlighted Text', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Enter your title', 'silicon-elementor' ),
				'default'     => 'How Does It Work?',
				'description' => esc_html__( 'Use <br> to break into two lines', 'silicon-elementor' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'highlighted_text',
			[
				'label'       => esc_html__( 'Highlighted Text', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => '',
				'label_block' => true,
			]
		);

		$this->add_control(
			'after_title',
			[
				'label'       => esc_html__( 'After Highlighted Text', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => '',
				'placeholder' => esc_html__( 'Enter your title', 'silicon-elementor' ),
				'description' => esc_html__( 'Use <br> to break into two lines', 'silicon-elementor' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'link',
			[
				'label'     => esc_html__( 'Link', 'silicon-elementor' ),
				'type'      => Controls_Manager::URL,
				'dynamic'   => [
					'active' => true,
				],
				'default'   => [
					'url' => '',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'size',
			[
				'label'   => esc_html__( 'Size', 'silicon-elementor' ),
				'type'    => SN_Controls_Manager::FONT_SIZE,
				'default' => '',
			]
		);

		$this->add_control(
			'header_size',
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
				'default' => 'h2',
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'     => esc_html__( 'Alignment', 'silicon-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'    => [
						'title' => esc_html__( 'Left', 'silicon-elementor' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'  => [
						'title' => esc_html__( 'Center', 'silicon-elementor' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'   => [
						'title' => esc_html__( 'Right', 'silicon-elementor' ),
						'icon'  => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'silicon-elementor' ),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'default'   => 'center',
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->end_injection();

		$this->start_injection(
			[
				'at' => 'before',
				'of' => 'section_slides_style',
			]
		);

		$this->start_controls_section(
			'section_highlighted_style',
			[
				'label' => esc_html__( 'Title', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Title Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .silicon-device__title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .silicon-device__title',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->add_control(
			'title_css',
			[
				'label'       => esc_html__( 'Additional CSS', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Additional CSS classes separated by space that you\'d like to apply to the title', 'silicon-elementor' ),
				'default'     => 'h1 text-center pb-2 pb-md-0 mb-4 mb-md-5',
			]
		);

		$this->add_control(
			'link_hover_color',
			[
				'label'      => esc_html__( 'Hover Color', 'silicon-elementor' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .silicon-device__title:hover, {{WRAPPER}} .silicon-device__title:focus' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .silicon-device__title:hover svg path, {{WRAPPER}} .silicon-device__title:focus svg path' => 'fill: {{VALUE}} !important;',
				],
				'conditions' => [
					'terms' => [
						[
							'name'     => 'link[url]',
							'operator' => '!==',
							'value'    => '',
						],
					],
				],
			]
		);

		$this->add_control(
			'link_css',
			[
				'label'       => esc_html__( 'Anchor Tag CSS', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Additional CSS classes separated by space that you\'d like to apply to the <a> tag', 'silicon-elementor' ),
				'conditions'  => [
					'terms' => [
						[
							'name'     => 'link[url]',
							'operator' => '!==',
							'value'    => '',
						],
					],
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Highlighted Text', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'highlight_color',
			[
				'label'     => esc_html__( 'Highlight Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .silicon-mobile-highlighted__title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'highlighted_typography',
				'selector' => '{{WRAPPER}} .silicon-mobile-highlighted__title',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->add_control(
			'highlighted_css',
			[
				'label'       => esc_html__( 'Highlighted CSS', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Additional CSS classes separated by space that you\'d like to apply to the highlighted text', 'silicon-elementor' ),
			]
		);

		$this->end_controls_section();

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
				'label' => __( 'Tab Content', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,

			]
		);

		$this->add_control(
			'tab_title_heading',
			[
				'label' => esc_html__( 'Title', 'silicon-elementor' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tab_title_typography',
				'label'    => __( 'Typography', 'silicon-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .swiper-title',
			]
		);

		$this->add_control(
			'tab_title_color',
			[
				'label'     => esc_html__( 'Text Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-title' => 'color: {{VALUE}} !important;',
				],
				'separator' => 'after',

			]
		);

		$this->add_control(
			'desc_heading',
			[
				'label' => esc_html__( 'Description', 'silicon-elementor' ),
				'type'  => Controls_Manager::HEADING,
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
				'selectors' => [ '{{WRAPPER}} .swiper-description' => 'font-size: {{SIZE}}{{UNIT}} !important;' ],
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
				'separator' => 'after',

			]
		);

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
			'image',
			[
				'label'   => esc_html__( 'Image', 'silicon-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'title',
			[
				'label'   => esc_html__( 'Title', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html( 'Step 1. Advanced budget management' ),

			]
		);

		$repeater->add_control(
			'description',
			[
				'label'   => esc_html__( 'Description', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html( 'Consequat ut cras nisl, enim purus in facilisi. Ipsum amet, lectus malesuada risus sollicitudin accumsan. Id sem elit vel vel lectus risus senectus.' ),

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
				'title'       => esc_html__( 'Step 1. Advanced budget management', 'silicon-elementor' ),
				'image'       => [
					'url' => $placeholder_image_src,
				],
				'description' => esc_html__( 'Consequat ut cras nisl, enim purus in facilisi. Ipsum amet, lectus malesuada risus sollicitudin accumsan. Id sem elit vel vel lectus risus senectus.', 'silicon-elementor' ),
			],
			[
				'title'       => esc_html__( 'Step 2. Latest transaction history', 'silicon-elementor' ),
				'image'       => [
					'url' => $placeholder_image_src,
				],
				'description' => esc_html__( 'Enim, et amet praesent pharetra. Mi non ante hendrerit amet sed. Arcu sociis tristique quisque hac in consectetur condimentum.', 'silicon-elementor' ),
			],
			[
				'title'       => esc_html__( 'Step 3. Transfers to people from your contact list', 'silicon-elementor' ),
				'image'       => [
					'url' => $placeholder_image_src,
				],
				'description' => esc_html__( 'Proin volutpat mollis egestas. Nam luctus facilisis ultrices. Pellentesque volutpat ligula est. Mattis fermentum, at nec lacus.', 'silicon-elementor' ),
			],
			[
				'title'       => esc_html__( 'Step 4. Card-to-card transfers', 'silicon-elementor' ),
				'image'       => [
					'url' => $placeholder_image_src,
				],
				'description' => esc_html__( 'A sed lorem felis, pulvinar pharetra. At tempus, vel sed faucibus amet sit elementum sed erat. Id nunc blandit pharetra facilisis.', 'silicon-elementor' ),
			],
		];
	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	public function render_title() {
		$settings = $this->get_settings_for_display();

		if ( '' === $settings['highlighted_text'] && '' === $settings['before_title'] ) {
			return;
		}

		$this->add_render_attribute( 'title', 'class', 'silicon-device__title' );

		if ( ! empty( $settings['title_css'] ) ) {
			$this->add_render_attribute( 'title', 'class', $settings['title_css'] );
		}

		if ( ! empty( $settings['size'] ) ) {
			$this->add_render_attribute( 'title', 'class', $settings['size'] );
		}

		$this->add_render_attribute( 'highlight', 'class', 'silicon-mobile-highlighted__title' );

		if ( ! empty( $settings['highlighted_css'] ) ) {
			$this->add_render_attribute( 'highlight', 'class', $settings['highlighted_css'] );
		}

		if ( ! empty( $settings['highlighted_text'] ) ) {
			$highlighted_text = '<span ' . $this->get_render_attribute_string( 'highlight' ) . '>' . $settings['highlighted_text'] . '</span>';
		} else {
			$highlighted_text = '';
		}

		$title = $settings['before_title'] . $highlighted_text . $settings['after_title'];

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'url', $settings['link'] );
			$this->add_render_attribute( 'url', 'class', [ 'silicon-device__title', 'text-decoration-none', $settings['link_css'] ] );

			$title = sprintf( '<a %1$s>%2$s</a>', $this->get_render_attribute_string( 'url' ), $title );
		}

		$title_html = sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['header_size'], $this->get_render_attribute_string( 'title' ), $title );

		echo wp_kses_post( $title_html );
	}

	/**
	 * Get slider settings
	 *
	 * @param array $slide the slides settings.
	 * @param array $settings the widget settings.
	 * @param array $element_key the element_key slider id.
	 * @return void
	 */
	public function print_image_slide( array $slide, array $settings, $element_key ) {
		$image_url = $slide['image']['url'];
		$id_int    = substr( $this->get_id_int(), 0, 3 );
		$id        = 'mobile-' . $id_int . $slide['_id'];
		// $slide_class = [ 'swiper-slide' ];
		// if ( $settings['carousel_slide_css'] ) {
		// $slide_class[] = $settings['carousel_slide_css'];
		// }
		$this->add_render_attribute(
			'image-' . $element_key,
			[
				'class' => 'd-block mx-auto',
				'src'   => $image_url,
				'width' => '328',
				'alt'   => Control_Media::get_image_alt( $slide['image'] ),
			]
		);
		$this->add_render_attribute( 'carousel_slide_css-' . $element_key, 'data-swiper-tab', '#' . $id );
		// $this->add_render_attribute(
		// 'slide-' . $element_key,
		// [
		// 'class'           => $slide_class,
		// 'data-swiper-tab' => '#' . $id,
		// ]
		// );
		if ( $image_url ) {
			$this->skin_slide_start( $settings, $element_key )
			?>
			
				<img <?php $this->print_render_attribute_string( 'image-' . $element_key ); ?>>
			</div>
			<?php
		}
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
	public function print_content_slider( array $slide, array $settings, $element_key, $count ) {
		$id_int          = substr( $this->get_id_int(), 0, 3 );
		$id              = 'mobile-' . $id_int . $slide['_id'];
		$content_wrapper = [ 'swiper-tab' ];
		if ( 1 === $count ) {
			$content_wrapper[] = 'active';
		}
		$this->add_render_attribute(
			'content-' . $element_key,
			[
				'class' => $content_wrapper,
				'id'    => $id,
			]
		);

		?>
			<div <?php $this->print_render_attribute_string( 'content-' . $element_key ); ?>>
			<?php
			if ( $slide['title'] ) :
				?>
			<h3 class="h4 pb-1 mb-2 swiper-title"><?php echo esc_html( $slide['title'] ); ?></h3>
				<?php
			endif;
			if ( $slide['description'] ) :
				?>
			<p class="mb-0 swiper-description"><?php echo esc_html( $slide['description'] ); ?></p>
			<?php endif; ?>
		</div>
			<?php

	}

}
