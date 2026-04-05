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
 * Skin Imagebox Carousel
 */
class Skin_ImageBox_Carousel extends Skin_Base {

	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'imagebox-carousel';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Imagebox Carousel', 'silicon-elementor' );
	}

	/**
	 * Slides Count.
	 *
	 * @var int
	 */
	private $slide_prints_count = 0;

	/**
	 * Get the title of the skin.
	 *
	 * @return void
	 */
	protected function _register_controls_actions() {
		add_filter( 'silicon-elementor/widget/sn-imagebox-carousel/print_template', [ $this, 'skin_print_template' ], 10, 2 );
		add_action( 'elementor/element/sn-imagebox-carousel/section_additional_options/before_section_end', [ $this, 'update_section_additional_options' ], 10, 1 );
		add_action( 'elementor/element/sn-imagebox-carousel/section_slides/before_section_end', [ $this, 'update_section_slides' ], 10, 1 );

	}

	/**
	 * Update section additional options
	 *
	 * @param array $widget section additional options.
	 */
	public function update_section_additional_options( $widget ) {

		$widget->update_control(
			'center_slides',
			[

				'condition' => [
					'_skin!' => 'imagebox-carousel',
				],
			]
		);

		$widget->update_control(
			'show_arrows',
			[
				'label'   => esc_html__( 'Enable Custom Arrow', 'silicon-elementor' ),
				'default' => 'no',

			]
		);

		$this->parent->start_injection(
			[
				'at' => 'before',
				'of' => 'speed',
			]
		);

		$this->parent->add_control(
			'enable_auto_height',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Auto Height', 'silicon-elementor' ),
				'default'   => 'no',
				'label_off' => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Disable', 'silicon-elementor' ),
				'condition' => [
					'style' => 'style-v3',
				],
			]
		);

		$this->parent->end_injection();

	}

	/**
	 * Update section slides
	 *
	 * @param array $widget section slides.
	 */
	public function update_section_slides( $widget ) {

		$widget->update_control(
			'slides',
			[
				'condition' => [
					'style!' => 'style-v3',
				],
			]
		);

		$this->parent->start_injection(
			[
				'at' => 'before',
				'of' => 'slides_per_view',
			]
		);

		$this->add_control(
			'adjust_for_service',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Adjust For Service', 'silicon-elementor' ),
				'default'   => 'no',
				'label_off' => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Disable', 'silicon-elementor' ),
				'condition' => [
					'style'  => 'style-v1',
					'effect' => 'slide',
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
		$skin_settings                       = [];
		$skin_settings['adjust_for_service'] = $this->get_instance_value( 'adjust_for_service' );

		$swiper_settings = array(
			'spaceBetween' => isset( $settings['space_between'] ) ? $settings['space_between'] : 0,
		);

		if ( ! empty( $settings['pagination'] ) ) {
			$swiper_settings['pagination']['el'] = '.swiper-pagination';
		}

		if ( 'yes' === $settings['enable_auto_height'] ) {
			$swiper_settings['autoHeight'] = true;
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

		if ( 'slide' === $settings['effect'] && 'style-v1' === $settings['style'] ) {
			if ( ! empty( $skin_settings['adjust_for_service'] ) && $skin_settings['adjust_for_service'] ) {
				$swiper_settings['breakpoints']['560']['slidesPerView'] = ! empty( $settings['slides_per_view_tablet'] ) ? $settings['slides_per_view_tablet'] : 2;
				$swiper_settings['breakpoints']['992']['slidesPerView'] = ! empty( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 3;
			} else {
				$swiper_settings['breakpoints']['600']['slidesPerView']  = ! empty( $settings['slides_per_view_tablet'] ) ? $settings['slides_per_view_tablet'] : 2;
				$swiper_settings['breakpoints']['1000']['slidesPerView'] = ! empty( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 3;
			}
		}

		if ( 'slide' === $settings['effect'] && 'style-v2' === $settings['style'] ) {
			$swiper_settings['breakpoints']['500']['slidesPerView'] = ! empty( $settings['slides_per_view_tablet'] ) ? $settings['slides_per_view_tablet'] : 2;
			$swiper_settings['breakpoints']['991']['slidesPerView'] = ! empty( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 3;

		}
		if ( 'slide' === $settings['effect'] && 'style-v3' === $settings['style'] ) {
			$swiper_settings['breakpoints']['500']['slidesPerView'] = ! empty( $settings['slides_per_view_tablet'] ) ? $settings['slides_per_view_tablet'] : 2;
			$swiper_settings['breakpoints']['991']['slidesPerView'] = ! empty( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 3;

		}
		if ( $settings['loop'] ) {
			$swiper_settings['loop'] = 'true';
		}
		if ( $settings['autoplay'] && $settings['autoplay_speed'] ) {
			$swiper_settings['autoplay']['delay'] = $settings['autoplay_speed'];
		}
		if ( $settings['autoplay'] && $settings['pause_on_hover'] ) {
			$swiper_settings['autoplay']['pauseOnMouseEnter'] = true;
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
	protected function print_slide( array $settings = null ) {
		$widget = $this->parent;
		if ( 'style-v3' === $settings['style'] ) {
			$style = $settings['slides_skin'];
		} else {
			$style = $settings['slides'];
		}
		$count = 1;
		if ( null === $settings ) {
			$settings = $widget->get_settings_for_display();
		}
		$swiper_settings = $this->get_swiper_carousel_options( $settings );

		$widget->add_render_attribute(
			'slider',
			array(
				'data-swiper-options' => esc_attr( wp_json_encode( $this->get_swiper_carousel_options( $settings ) ) ),
			)
		);

		$widget->skin_loop_header( $settings );

		foreach ( $style as $slide ) :
			$widget->print_imagebox_slide( $slide, $settings, $slide['_id'], $count );
			$count++;
		endforeach;

		?>
			</div>
			<?php $this->silicon_pagination( $settings ); ?>
		</div>        
		<?php
	}

	/**
	 * Render button.
	 *
	 * @param array $settings The widget settings.
	 * @return void
	 */
	public function silicon_pagination( array $settings ) {

		$this->parent->render_swiper_pagination( $settings );
		?>
		<?php
	}

	/**
	 * Render..
	 *
	 * @return void
	 */
	public function render() {
		$widget   = $this->parent;
		$settings = $widget->get_settings_for_display();
		if ( 'style-v3' === $settings['style'] ) {
			$style = $settings['slides_skin'];
		} else {
			$style = $settings['slides'];
		}
		$slides_count = count( $style );
		$this->print_slide( $settings );
		$widget->render_script();
	}

	/**
	 * Skin print template.
	 *
	 * @param array $content the content.
	 * @param array $imagebox_carousel widget name.
	 * @return string
	 */
	public function skin_print_template( $content, $imagebox_carousel ) {

		if ( 'sn-imagebox-carousel' === $imagebox_carousel->get_name() ) {
			return '';
		}

		return $content;
	}
}
