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
 * Skin Lottie Carousel
 */
class Skin_Lottie_Carousel extends Skin_Base {

	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'lottie-carousel-v1';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Skin v1', 'silicon-elementor' );
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return void
	 */
	protected function _register_controls_actions() {
		add_filter( 'silicon-elementor/widget/sn-lottie-carousel/print_template', [ $this, 'skin_print_template' ], 10, 2 );
		add_action( 'elementor/element/sn-lottie-carousel/section_additional_options/before_section_end', [ $this, 'update_section_additional_options' ], 10, 1 );
		add_action( 'elementor/element/sn-lottie-carousel/section_meta_style/before_section_end', [ $this, 'update_section_meta_style' ], 10, 1 );
		add_action( 'elementor/element/sn-lottie-carousel/section_navigation/before_section_end', [ $this, 'update_section_navigation' ], 10, 1 );

	}

	/**
	 * Update section additional options
	 *
	 * @param array $widget section additional options.
	 */
	public function update_section_additional_options( $widget ) {

		$update_control_ids = [ 'show_arrows', 'prev_arrow_id', 'next_arrow_id' ];

		foreach ( $update_control_ids as $update_control_id ) {
			$widget->update_control(
				$update_control_id,
				[
					'condition' => [
						'_skin' => '',
					],
				]
			);
		}

		$widget->update_control(
			'loop',
			[
				'default' => 'no',

			]
		);

	}

	/**
	 * Update section swipers
	 *
	 * @param array $widget section meta style options.
	 */
	public function update_section_meta_style( $widget ) {

		$widget->update_control(
			'carousel_options_css',
			[
				'default' => 'mt-n3 mt-md-0 pt-md-4 pt-lg-5 mx-n2',

			]
		);

		$widget->update_control(
			'carousel_slide_css',
			[
				'default' => 'h-auto pb-3',

			]
		);

	}

	/**
	 * Update section navigation
	 *
	 * @param array $widget section navigation options.
	 */
	public function update_section_navigation( $widget ) {

		$widget->update_control(
			'pagination_css',
			[
				'default' => 'position-relative bottom-0 mt-2',

			]
		);

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
			$swiper_settings['breakpoints']['1000']['spaceBetween'] = isset( $settings['space_between'] ) ? $settings['space_between'] : 8;
			$swiper_settings['breakpoints']['700']['spaceBetween']  = isset( $settings['space_between_tablet'] ) ? $settings['space_between_tablet'] : 8;
			$swiper_settings['breakpoints']['500']['spaceBetween']  = isset( $settings['space_between_tablet'] ) ? $settings['space_between_tablet'] : 8;
			$swiper_settings['spaceBetween']                        = isset( $settings['space_between_mobile'] ) ? $settings['space_between_mobile'] : 8;
		}

		if ( $settings['center_slides'] ) {
			$swiper_settings['centeredSlides'] = 'true';
		}
		if ( 'fade' === $settings['effect'] ) {
			$swiper_settings['effect']                  = 'fade';
			$swiper_settings['fadeEffect']['crossFade'] = true;
		}
		if ( 'slide' === $settings['effect'] ) {
			$swiper_settings['breakpoints']['1000']['slidesPerView'] = isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 4;
			$swiper_settings['breakpoints']['700']['slidesPerView']  = isset( $settings['slides_per_view_tablet'] ) ? $settings['slides_per_view_tablet'] : 3;
			$swiper_settings['breakpoints']['500']['slidesPerView']  = 2;
			$swiper_settings['slidesPerView']                        = isset( $settings['slides_per_view_mobile'] ) ? $settings['slides_per_view_mobile'] : 1;

		}
		if ( $settings['show_arrows'] ) {
			$prev_id                       = ! empty( $settings['prev_arrow_id'] ) ? $settings['prev_arrow_id'] : '';
			$next_id                       = ! empty( $settings['next_arrow_id'] ) ? $settings['next_arrow_id'] : '';
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
			$settings = $this->get_settings_for_display();
		}

		$defaults        = array( 'container_class' => 'swiper sn-elementor-main-swiper' );
		$settings        = array_merge( $defaults, $settings );
		$swiper_settings = $this->get_swiper_carousel_options( $settings );

		$widget->add_render_attribute(
			'slider',
			array(

				'data-swiper-options' => esc_attr( wp_json_encode( $this->get_swiper_carousel_options( $settings ) ) ),
			)
		);
		$widget->skin_loop_header( $settings );
		?>
					<?php foreach ( $settings['slides'] as $slide ) : ?>                                    
						<?php
						$widget->print_slide( $slide, $settings, $slide['_id'] );
						?>
					<?php endforeach; ?>
				</div>
				<!-- Pagination -->
				<?php $widget->render_swiper_pagination( $settings ); ?>
		</div>    
		<?php
	}

	/**
	 * Render the widget.
	 */
	public function render() {
		$widget          = $this->parent;
		$settings        = $widget->get_settings_for_display();
		$swiper_settings = $this->get_swiper_carousel_options( $settings );
		$this->print_slider( $settings );
		$widget->render_script();
	}

	/**
	 * Skin print template.
	 *
	 * @param array $content the content.
	 * @param array $lottie_carousel widget name.
	 * @return string
	 */
	public function skin_print_template( $content, $lottie_carousel ) {

		if ( 'sn-lottie-carousel' === $lottie_carousel->get_name() ) {
			return '';
		}

		return $content;
	}
}
