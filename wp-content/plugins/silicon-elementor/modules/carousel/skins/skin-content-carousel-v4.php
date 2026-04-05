<?php
namespace SiliconElementor\Modules\Carousel\Skins;

use Elementor;
use Elementor\Skin_Base;
use Elementor\Control_Media;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Controls_Manager;
use SiliconElementor\Core\Utils as SN_Utils;
use Elementor\Icons_Manager;
use Elementor\Widget_Base;
use Elementor\Repeater;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Skin Content Carousel V2
 */
class Skin_Content_Carousel_V4 extends Skin_Base {

	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'content-carousel-v4';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Skin v4', 'silicon-elementor' );
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return void
	 */
	protected function _register_controls_actions() {
		add_filter( 'silicon-elementor/widget/sn-content-carousel/print_template', [ $this, 'skin_print_template' ], 10, 2 );
		add_action( 'elementor/element/sn-content-carousel/section_additional_options/before_section_end', [ $this, 'update_section_additional_options' ], 10, 1 );
		add_action( 'elementor/element/sn-content-carousel/section_slides/before_section_end', [ $this, 'update_section_slides' ], 10, 1 );
		add_action( 'elementor/element/sn-content-carousel/section_navigation/before_section_end', [ $this, 'update_section_navigation' ], 10, 1 );
	}

	/**
	 * Update section slides
	 *
	 * @param array $widget update slides.
	 */
	public function update_section_navigation( $widget ) {
		$widget->update_control(
			'pagination_css',
			[
				'default'   => 'position-relative d-md-none pt-2 mt-5',
				'condition' => [
					'_skin' => [ 'content-carousel-v2', 'content-carousel-v3', 'content-carousel-v4' ],
				],
			]
		);

		$this->parent->start_injection(
			[
				'at' => 'before',
				'of' => 'pagination_css',
			]
		);

		$this->parent->add_control(
			'pagination_width',
			[
				'label'      => esc_html__( 'Pagination Width', 'silicon-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'default'    => [
					'size' => 28,
				],
				'selectors'  => [
					'{{WRAPPER}} .swiper-pagination' => 'width: {{SIZE}}{{UNIT}} ',
				],
			]
		);

		$this->parent->end_injection();
	}

	/**
	 * Update section slides
	 *
	 * @param array $widget update slides.
	 */
	public function update_section_slides( $widget ) {

		$update_control_ids = [ 'slides' ];

		foreach ( $update_control_ids as $update_control_id ) {
			$widget->update_control(
				$update_control_id,
				[
					'condition' => [
						'_skin!' => [ 'content-carousel-v2', 'content-carousel-v3', 'content-carousel-v4' ],
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
		$this->repeater_controls();

		$this->parent->end_injection();
	}

	/**
	 * Update section additional options
	 *
	 * @param array $widget section additional options.
	 */
	public function update_section_additional_options( $widget ) {

		$widget->update_control(
			'enable_tabs',
			[
				'condition' => [
					'_skin' => [ 'content-carousel-v1', 'content-carousel-v4' ],
				],
			]
		);

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => 'pagination',
			]
		);
		$this->parent->add_control(
			'pagination_id_v4',
			[
				'type'      => Controls_Manager::TEXT,
				'label'     => esc_html__( 'Pagination Id', 'silicon-elementor' ),
				'default'   => 'how-it-works-pagination',
				'condition' => [
					'_skin'       => [ 'content-carousel-v4' ],
					'pagination!' => '',
				],
			]
		);

		$this->parent->end_injection();

	}

	/**
	 * Get carousel settings
	 *
	 * @param array $settings The widget settings.
	 * @return array
	 */
	public function get_swiper_carousel_options( array $settings = null ) {

		if ( null === $settings ) {
			$settings = $this->get_settings_for_display();
		}

		$swiper_settings         = [];
		$swiper_settings['tabs'] = true;
		$prev_id                 = ! empty( $settings['prev_arrow_id'] ) ? $settings['prev_arrow_id'] : '';
		$next_id                 = ! empty( $settings['next_arrow_id'] ) ? $settings['next_arrow_id'] : '';
		if ( 'yes' === $settings['show_arrows'] ) {
			$swiper_settings['navigation'] = array(
				'prevEl' => '#' . $prev_id,
				'nextEl' => '#' . $next_id,

			);
		}
		if ( $settings['pagination'] && $settings['pagination_id_v4'] ) {
			$swiper_settings['pagination'] = array(
				'el' => '#' . $settings['pagination_id_v4'],
			);
		}

		if ( 'bullets' === $settings['pagination'] ) {
			$swiper_settings['pagination']['type']      = 'bullets';
			$swiper_settings['pagination']['clickable'] = true;
		}
		if ( 'fraction' === $settings['pagination'] ) {
			$swiper_settings['pagination']['type'] = 'fraction';
		}
		if ( 'progressbar' === $settings['pagination'] ) {
			$swiper_settings['pagination']['type'] = 'progressbar';
		}

		if ( $settings['center_slides'] ) {
			$swiper_settings['centeredSlides'] = 'true';
		}

		if ( 'yes' === $settings['enable_auto_height'] ) {
			$swiper_settings['autoHeight'] = true;
		}

		if ( $settings['center_slides'] ) {
			$swiper_settings['centeredSlides'] = 'true';
		}
		if ( 'yes' === $settings['enable_tabs'] ) {
			$swiper_settings['tabs'] = true;
		}
		if ( 'fade' === $settings['effect'] ) {
			$swiper_settings['effect']                  = 'fade';
			$swiper_settings['fadeEffect']['crossFade'] = true;
		}
		if ( 'slide' === $settings['effect'] ) {
			$swiper_settings['breakpoints']['1440']['slidesPerView'] = isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 3;
			$swiper_settings['breakpoints']['1024']['slidesPerView'] = isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 3;
			$swiper_settings['breakpoints']['768']['slidesPerView']  = isset( $settings['slides_per_view_tablet'] ) ? $settings['slides_per_view_tablet'] : 3;
			$swiper_settings['breakpoints']['0']['slidesPerView']    = isset( $settings['slides_per_view_mobile'] ) ? $settings['slides_per_view_mobile'] : 1;

		}

		if ( 'yes' === $settings['enable_space_between'] && ! empty( $settings['space_between'] ) ) {
			$swiper_settings['breakpoints']['1440']['spaceBetween'] = $settings['space_between'];
		}

		if ( 'yes' === $settings['enable_space_between'] && ! empty( $settings['space_between_tablet'] ) ) {
			$swiper_settings['breakpoints']['768']['spaceBetween'] = $settings['space_between_tablet'];
		}

		if ( 'yes' === $settings['enable_space_between'] && ! empty( $settings['space_between_mobile'] ) ) {
			$swiper_settings['breakpoints']['500']['spaceBetween'] = $settings['space_between_mobile'];
		}

		if ( $settings['loop'] ) {
			$swiper_settings['loop'] = 'true';
		}
		if ( $settings['autoplay'] && $settings['autoplay_speed'] ) {
			$swiper_settings['autoplay']['delay'] = $settings['autoplay_speed'];
		}
		if ( $settings['autoplay'] && $settings['pause_on_hover'] ) {
			$swiper_settings['autoplay']['pauseOnMouseEnter']    = true;
			$swiper_settings['autoplay']['disableOnInteraction'] = false;
		}
		if ( $settings['speed'] ) {
			$swiper_settings['speed'] = $settings['speed'];
		}

		return $swiper_settings;
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
		$widget = $this->parent;
		if ( 'yes' === $settings['enable_tabs'] ) {
			$widget->add_render_attribute( 'carousel_slide_css-' . $element_key, 'data-swiper-tab', '#' . $slide['tab_id'] );
		}
		$widget->add_render_attribute( 'pre_title_v4-' . $element_key, 'class', 'swiper-pre-title h5 text-primary pb-1 mb-2' );

		if ( ! empty( $settings['title_css'] ) ) {
			$widget->add_render_attribute( 'title_v4-' . $element_key, 'class', $settings['title_css'] );
		}
		if ( ! empty( $settings['desc_css'] ) ) {
			$widget->add_render_attribute( 'description_v4-' . $element_key, 'class', $settings['desc_css'] );
		}
		$widget->add_render_attribute( 'title_v4-' . $element_key, 'class', 'sn-title h1 pb-1' );
		$widget->add_render_attribute( 'description_v4-' . $element_key, 'class', 'swiper-description' );
		$widget->skin_slide_start( $settings, $element_key );?>
			<div <?php $widget->print_render_attribute_string( 'pre_title_v4-' . $element_key ); ?>>
				<?php echo wp_kses_post( $slide['pre_title'] ); ?>
			</div>
			<<?php echo esc_html( $slide['title_tag'] ); ?> <?php $widget->print_render_attribute_string( 'title_v4-' . $element_key ); ?>>
				<?php echo wp_kses_post( $slide['title'] ); ?>
			</<?php echo esc_html( $slide['title_tag'] ); ?>>
			
			<?php
			if ( ! empty( $slide['description'] ) ) {
				?>
				<div <?php $widget->print_render_attribute_string( 'description_v4-' . $element_key ); ?>>
				<?php
				echo wp_kses_post( $slide['description'] );
				?>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	}

	/**
	 * Get slider settings
	 *
	 * @param array $settings The widget settings.
	 * @return void
	 */
	protected function print_slider( array $settings = null ) {
		$widget                         = $this->parent;
		$skin_settings                  = [];
		$skin_settings['slides_tab_v4'] = $this->get_instance_value( 'slides_tab_v4' );
		if ( null === $settings ) {
			$settings = $widget->get_settings_for_display();
		}

		$defaults        = array( 'container_class' => 'swiper sn-elementor-main-swiper mx-0' );
		$settings        = array_merge( $defaults, $settings );
		$swiper_settings = $this->get_swiper_carousel_options( $settings );

		$widget->add_render_attribute(
			'slider',
			array(
				'class'               => 'mx-0',
				'data-swiper-options' => esc_attr( json_encode( $this->get_swiper_carousel_options( $settings ) ) ),
			)
		);
		$prev_id = ! empty( $settings['prev_arrow_id'] ) ? $settings['prev_arrow_id'] : '';
		$next_id = ! empty( $settings['next_arrow_id'] ) ? $settings['next_arrow_id'] : '';

			$widget->skin_loop_header( $settings );
		?>
					<?php foreach ( $skin_settings['slides_tab_v4'] as $slide ) : ?>                                    
						<?php
						$this->print_slide( $slide, $settings, $slide['_id'] );
						?>
					<?php endforeach; ?>					
			</div>
		</div>
		<?php
		if ( $settings['show_arrows'] || $settings['pagination'] ) :
			$this->print_pagination_button( $settings );
		endif;
	}

	/**
	 * Render button.
	 *
	 * @param array $settings widgets settings.
	 *
	 * @return void
	 */
	public function print_pagination_button( array $settings ) {
		$widget = $this->parent;
		if ( null === $settings ) {
			$settings = $this->get_settings_for_display();
		}
		$widget->add_render_attribute(
			'prev_arrow_button',
			[

				'class' => 'btn btn-prev btn-icon btn-sm',

			]
		);
		$widget->add_render_attribute(
			'next_arrow_button',
			[

				'class' => 'btn btn-next btn-icon btn-sm',

			]
		);
		$widget->add_render_attribute(
			'arrows_wrapper',
			[

				'class' => [ 'd-flex justify-content-center justify-content-md-start', $settings['arrows_wrapper_css'] ],

			]
		);
		$widget->render_arrow_button( $settings, 'prev-1', 'next-1' );
		?>
		<div <?php $widget->print_render_attribute_string( 'arrows_wrapper' ); ?>>
			<?php
			$widget->print_prev_button();

			if ( $settings['pagination'] ) {
				$widget->add_render_attribute(
					'swiper_pagination',
					[
						// 'style' => 'width: 1.5rem;',
						'id'    => $settings['pagination_id_v4'],
						'class' => 'swiper-pagination position-relative bottom-0 fs-sm lh-1 fw-medium mx-3',
					]
				);
				$widget->render_swiper_pagination( $settings );
			}
			$widget->print_next_button();
			?>
		</div>
		<?php

	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	public function render() {
		$widget   = $this->parent;
		$settings = $widget->get_settings_for_display();
		if ( ! empty( $settings['overall_wrapper'] ) ) :
			$widget->add_render_attribute( 'overall_wrapper', 'class', $settings['overall_wrapper'] );
			?>
			<div <?php $widget->print_render_attribute_string( 'overall_wrapper' ); ?>>
			<?php
		endif;
			$this->print_slider( $settings );
		if ( ! empty( $settings['overall_wrapper'] ) ) :
			?>
			</div>
			<?php
			endif;
		$widget->render_script();

	}

	/**
	 * Repeater Controls.
	 *
	 * @return void
	 */
	public function repeater_controls() {
		$repeater_tab = new Repeater();

		$repeater_tab->add_control(
			'tab_id',
			[
				'label' => esc_html__( 'Tabs ID', 'silicon-elementor' ),
				'type'  => Controls_Manager::TEXT,
			]
		);

		$repeater_tab->add_control(
			'pre_title',
			[
				'label'   => esc_html__( 'Pre Title', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Step', 'silicon-elementor' ),
			]
		);

		$repeater_tab->add_control(
			'title',
			[
				'label'   => esc_html__( 'Title', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Create Account', 'silicon-elementor' ),
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
			'description',
			[
				'label'     => esc_html__( 'Description', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXTAREA,
				'separator' => 'before',
				'default'   => esc_html__( 'Aenean dolor elit tempus tellus imperdiet. Elementum, ac convallis morbi sit est feugiat ultrices. Cras tortor maecenas pulvinar nec ac justo. Massa sem eget semper...', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'slides_tab_v4',
			[
				'label'     => esc_html__( 'Slides', 'silicon-elementor' ),
				'type'      => Controls_Manager::REPEATER,
				'fields'    => $repeater_tab->get_controls(),
				'condition' => [ '_skin' => 'content-carousel-v4' ],
				'default'   => $this->get_repeater_skin_defaults(),
			]
		);
	}

	/**
	 * Slider defaults.
	 *
	 * @return string
	 */
	protected function get_repeater_skin_defaults() {

		return [
			[
				'pre_title'   => esc_html( 'Step 1' ),
				'title'       => esc_html( 'Create account' ),
				'description' => wp_kses_post(
					'<ul class="list-unstyled d-table mx-auto mx-md-0">
				<li class="d-flex fs-lg text-start pb-1 mb-2">
				  <i class="bx bx-check text-primary lead pe-1 me-1" style="margin-top: .125rem;"></i>
				  Enter your email and create a password
				</li>
				<li class="d-flex fs-lg text-start pb-1 mb-2">
				  <i class="bx bx-check text-primary lead pe-1 me-1" style="margin-top: .125rem;"></i>
				  Check your inbox to find the confirmation email
				</li>
				<li class="d-flex fs-lg text-start pb-1 mb-2">
				  <i class="bx bx-check text-primary lead pe-1 me-1" style="margin-top: .125rem;"></i>
				  Follow the steps described in the email
				</li>
			  </ul>'
				),
			],
			[
				'pre_title'   => esc_html( 'Step 2' ),
				'title'       => esc_html( 'Connect account' ),
				'description' => wp_kses_post(
					'<ul class="list-unstyled d-table mx-auto mx-md-0">
				<li class="d-flex fs-lg text-start pb-1 mb-2">
				  <i class="bx bx-check text-primary lead pe-1 me-1" style="margin-top: .125rem;"></i>
				  Authorize in the application
				</li>
				<li class="d-flex fs-lg text-start pb-1 mb-2">
				  <i class="bx bx-check text-primary lead pe-1 me-1" style="margin-top: .125rem;"></i>
				  Fill application forms
				</li>
				<li class="d-flex fs-lg text-start pb-1 mb-2">
				  <i class="bx bx-check text-primary lead pe-1 me-1" style="margin-top: .125rem;"></i>
				  Take a photo of your passport and TIN
				</li>
			  </ul>'
				),
			],
			[
				'pre_title'   => esc_html( 'Step 3' ),
				'title'       => esc_html( 'Get the card' ),
				'description' => wp_kses_post(
					'<ul class="list-unstyled d-table mx-auto mx-md-0">
				<li class="d-flex fs-lg text-start pb-1 mb-2">
				  <i class="bx bx-check text-primary lead pe-1 me-1" style="margin-top: .125rem;"></i>
				  Specify the desired method of obtaining a card
				</li>
				<li class="d-flex fs-lg text-start pb-1 mb-2">
				  <i class="bx bx-check text-primary lead pe-1 me-1" style="margin-top: .125rem;"></i>
				  Receive the card within 1-2 business days 
				</li>
				<li class="d-flex fs-lg text-start pb-1 mb-2">
				  <i class="bx bx-check text-primary lead pe-1 me-1" style="margin-top: .125rem;"></i>
				  Start managing your finances
				</li>
			  </ul>'
				),
			],
		];
	}

	/**
	 * Skin print template.
	 *
	 * @param array $content the content.
	 * @param array $content_carousel widget name.
	 * @return string
	 */
	public function skin_print_template( $content, $content_carousel ) {

		if ( 'sn-content-carousel' == $content_carousel->get_name() ) {
			return '';
		}

		return $content;
	}
}
