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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Skin Testimonial Carousel
 */
class Skin_Testimonial_Carousel extends Skin_Base {

	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'testimonial-carousel-1';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Skin V1', 'silicon-elementor' );
	}

	/**
	 * Register Control Actions in the skin.
	 *
	 * @return void
	 */
	protected function _register_controls_actions() {
		add_filter( 'silicon-elementor/widget/sn-testimonial-carousel/print_template', [ $this, 'skin_print_template' ], 10, 2 );
		add_action( 'elementor/element/sn-testimonial-carousel/section_slides/before_section_end', [ $this, 'update_section_slides' ], 10, 1 );
		add_action( 'elementor/element/sn-testimonial-carousel/section_additional_options/before_section_end', [ $this, 'update_section_additional_options' ], 10, 1 );
		add_action( 'elementor/element/sn-testimonial-carousel/section_navigation/before_section_end', [ $this, 'update_section_navigation' ], 10, 1 );
	}

	/**
	 * Update section slides
	 *
	 * @param array $widget update slides.
	 */
	public function update_section_slides( $widget ) {

		$widget->update_control(
			'slides_to_scroll',
			[
				'condition' => [
					'_skin!' => 'testimonial-carousel-1',
				],
			]
		);
		$this->parent->start_injection(
			[
				'at' => 'before',
				'of' => 'slides_per_view',
			]
		);
		$this->parent->add_control(
			'desktop_breakpoints',
			[
				'label' => esc_html__( 'Desktop Breakpoints', 'silicon-elementor' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1200,
				'max' => 3000,
				'step' => 100,
				'default' => 1200,
				'condition' => [
					'_skin' => 'testimonial-carousel-1',
				],
			]
		);
		$this->parent->end_injection();
	}

	/**
	 * Update section additional options
	 *
	 * @param array $widget section additional options.
	 */
	public function update_section_additional_options( $widget ) {

		$widget->update_control(
			'show_arrows',
			[
				'condition' => [
					'_skin!' => 'testimonial-carousel-1',
				],
			]
		);

		$widget->update_control(
			'prev_arrow_id',
			[
				'default'   => 'prev-testimonial',
				'condition' => [
					'_skin!' => 'testimonial-carousel-1',
					'show_arrows' => 'yes',
				],
			]
		);

		$widget->update_control(
			'next_arrow_id',
			[
				'default'   => 'next-testimonial',
				'condition' => [
					'_skin!' => 'testimonial-carousel-1',
					'show_arrows' => 'yes',
				],
			]
		);

		$this->parent->start_injection(
			[
				'at' => 'before',
				'of' => 'speed',
			]
		);
		$this->parent->add_control(
			'show_custom_arrows',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Custom Arrows ID', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Hide', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Show', 'silicon-elementor' ),
				'condition'          => [
					'_skin'     => 'testimonial-carousel-1',
				],
			]
		);

		$this->parent->add_control(
			'prev_custom_id',
			[
				'label'       => esc_html__( 'Prev Arrow ID', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Enter ID for Previous Button', 'silicon-elementor' ),
				'condition'          => [
					'show_custom_arrows' => 'yes',
					'_skin'     => 'testimonial-carousel-1',
				],
				'default'   => 'prev-testimonial',
			]
		);

		$this->parent->add_control(
			'next_custom_id',
			[
				'label'       => esc_html__( 'Next Arrow ID', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Enter ID for Next Button', 'silicon-elementor' ),
				'condition'          => [
					'show_custom_arrows' => 'yes',
					'_skin'     => 'testimonial-carousel-1',
				],
				'default'   => 'next-testimonial',
			]
		);
		$this->parent->end_injection();
	}

	/**
	 * Update section navigation
	 *
	 * @param array $widget section navigation.
	 */
	public function update_section_navigation( $widget ) {

		$update_control_ids = [ 'heading_arrows', 'arrows_size', 'arrows_color', 'arrows_hover_color', 'pagination_position' ];

		foreach ( $update_control_ids as $update_control_id ) {
			$widget->update_control(
				$update_control_id,
				[
					'condition' => [
						'_skin!' => 'testimonial-carousel-1',
					],
				]
			);
		}
	}

	/**
	 * Get carousel settings.
	 *
	 * @param array $settings The widget settings.
	 * @return array
	 */
	public function get_swiper_carousel_options( array $settings = null ) {

		if ( null === $settings ) {
			$settings = $this->get_settings_for_display();
		}

		$swiper_settings = array();

		if ( ! empty( $settings['pagination'] ) ) {
			$swiper_settings['pagination']['el'] = '.swiper-pagination';
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
		if ( 'yes' === $settings['enable_space_between'] ) {
			$swiper_settings['breakpoints'][ $settings['desktop_breakpoints'] ]['spaceBetween'] = isset( $settings['space_between'] ) ? $settings['space_between'] : 24;
			$swiper_settings['breakpoints']['1000']['spaceBetween']                             = isset( $settings['space_between_tablet'] ) ? $settings['space_between_tablet'] : 24;
			$swiper_settings['breakpoints']['500']['spaceBetween']                              = isset( $settings['space_between_tablet'] ) ? $settings['space_between_tablet'] : 24;
			$swiper_settings['spaceBetween'] = isset( $settings['space_between_mobile'] ) ? $settings['space_between_mobile'] : 8;
		}

		if ( $settings['center_slides'] ) {
			$swiper_settings['centeredSlides'] = 'true';
		}
		if ( 'fade' === $settings['effect'] ) {
			$swiper_settings['effect'] = 'fade';
			$swiper_settings['fadeEffect']['crossFade'] = true;
		}
		if ( 'slide' === $settings['effect'] ) {
			$swiper_settings['breakpoints'][ $settings['desktop_breakpoints'] ]['slidesPerView'] = isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 3;
			$swiper_settings['breakpoints']['1000']['slidesPerView'] = isset( $settings['slides_per_view_tablet'] ) ? $settings['slides_per_view_tablet'] : 3;
			$swiper_settings['breakpoints']['500']['slidesPerView']  = 2;
			$swiper_settings['slidesPerView']                        = isset( $settings['slides_per_view_mobile'] ) ? $settings['slides_per_view_mobile'] : 1;

		}

		if ( $settings['show_custom_arrows'] ) {
			$prev_id = ! empty( $settings['prev_custom_id'] ) ? $settings['prev_custom_id'] : '';
			$next_id = ! empty( $settings['next_custom_id'] ) ? $settings['next_custom_id'] : '';
			$swiper_settings['navigation'] = array(
				'prevEl' => '#' . $prev_id,
				'nextEl' => '#' . $next_id,

			);
		}

		if ( $settings['loop'] ) {
			$swiper_settings['loop'] = 'true';
		}
		if ( $settings['autoplay'] && $settings['autoplay_speed'] ) {
			$swiper_settings['autoplay']['delay'] = $settings['autoplay_speed'];
		}
		if ( $settings['autoplay'] && $settings['pause_on_hover'] ) {
			$swiper_settings['autoplay']['pauseOnMouseEnter'] = true;
			$swiper_settings['autoplay']['disableOnInteraction'] = false;
		}
		if ( $settings['speed'] ) {
			$swiper_settings['speed'] = $settings['speed'];
		}

		return $swiper_settings;
	}

	/**
	 * Get slider settings
	 *
	 * @param array $settings The widget settings.
	 * @return void
	 */
	protected function print_slider( array $settings = null ) {
		$widget = $this->parent;
		if ( null === $settings ) {
			$settings = $widget->get_settings_for_display();
		}

		$defaults        = array( 'container_class' => 'swiper sn-elementor-main-swiper' );
		$settings        = array_merge( $defaults, $settings );
		$swiper_settings = $this->get_swiper_carousel_options( $settings );

		$widget->add_render_attribute(
			'slider',
			array(
				'data-swiper-options' => esc_attr( json_encode( $this->get_swiper_carousel_options( $settings ) ) ),
			)
		);

		$figure_class = [ 'd-flex', 'flex-column', 'h-100', 'px-2', 'px-sm-0', 'mb-0', 'lh-base' ];
		if ( $settings['figure_css'] ) {
			$figure_class[] = $settings['figure_css'];
		}
		$widget->add_render_attribute(
			'figure',
			[
				'class' => $figure_class,
			]
		);

		$widget->skin_loop_header( $settings );?>
				<?php foreach ( $settings['slides'] as $slide ) : ?>                                    
					<?php
					$widget->print_slide( $slide, $settings, $slide['_id'] );
					?>
				<?php endforeach; ?>
			</div>
			<?php
			$widget->render_arrow_button( $settings, 'news-prev', 'news-next' );
			?>
			<!-- Pagination (bullets) -->
			<?php
			$widget->render_swiper_pagination( $settings );
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

		$this->print_slider( $settings );
		$widget->render_script();

	}

	/**
	 * Skin print template.
	 *
	 * @param array $content the content.
	 * @param array $testimonial_carousel widget name.
	 * @return string
	 */
	public function skin_print_template( $content, $testimonial_carousel ) {

		if ( 'sn-testimonial-carousel' == $testimonial_carousel->get_name() ) {
			return '';
		}

		return $content;
	}
}
