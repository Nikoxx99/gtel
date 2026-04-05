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
 * Skin Iconbox Carousel
 */
class Skin_Iconbox_Carousel extends Skin_Base {

	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'iconbox-carousel';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Iconbox Carousel', 'silicon-elementor' );
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
		add_filter( 'silicon-elementor/widget/sn-iconbox-carousel/print_template', [ $this, 'skin_print_template' ], 10, 2 );
		add_action( 'elementor/element/sn-iconbox-carousel/section_additional_options/before_section_end', [ $this, 'update_section_additional_options' ], 10, 1 );
		add_action( 'elementor/element/sn-iconbox-carousel/section_slides/after_section_end', [ $this, 'update_section_slides' ], 10, 1 );

	}

	/**
	 * Update section slides
	 *
	 * @param array $widget update slides.
	 */
	public function update_section_slides( $widget ) {

		$widget->update_control(
			'slides',
			[
				'condition' => [
					'style' => 'default',
				],
			]
		);
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
					'_skin!' => 'iconbox-carousel',
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

		$swiper_settings = array(
			'slidesPerView' => 1,
			'spaceBetween'  => isset( $settings['space_between'] ) ? $settings['space_between'] : 8,
		);
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
		if ( 'yes' === $settings['enable_space_between'] && 'default' === $settings['style'] ) {

			$swiper_settings['breakpoints']['500']['spaceBetween']  = isset( $settings['space_between'] ) ? $settings['space_between'] : 8;
			$swiper_settings['breakpoints']['800']['spaceBetween']  = isset( $settings['space_between'] ) ? $settings['space_between'] : 8;
			$swiper_settings['breakpoints']['1200']['spaceBetween'] = isset( $settings['space_between'] ) ? $settings['space_between'] : 8;
		}
		if ( 'slide' === $settings['effect'] ) {
			$swiper_settings['breakpoints']['500']['slidesPerView']  = ! empty( $settings['slides_per_view_tablet'] ) ? $settings['slides_per_view_tablet'] : 2;
			$swiper_settings['breakpoints']['800']['slidesPerView']  = ! empty( $settings['slides_per_view_laptop'] ) ? $settings['slides_per_view_laptop'] : 3;
			$swiper_settings['breakpoints']['1200']['slidesPerView'] = ! empty( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 4;
		}

		if ( 'slide' === $settings['effect'] && 'style-v1' === $settings['style'] ) {
			$swiper_settings['slidesPerView']                        = 2;
			$swiper_settings['breakpoints']['500']['slidesPerView']  = ! empty( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 3;
			$swiper_settings['breakpoints']['650']['slidesPerView']  = ! empty( $settings['slides_per_view_tablet'] ) ? $settings['slides_per_view_tablet'] : 4;
			$swiper_settings['breakpoints']['900']['slidesPerView']  = 5;
			$swiper_settings['breakpoints']['1100']['slidesPerView'] = ! empty( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 6;

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
		$count  = 1;
		if ( null === $settings ) {
			$settings = $widget->get_settings_for_display();
		}

		$swiper_settings = $this->get_swiper_carousel_options( $settings );

		$widget->add_render_attribute(
			'slider',
			array(
				// 'class'               => $settings['container_class'],
				'data-swiper-options' => esc_attr( json_encode( $this->get_swiper_carousel_options( $settings ) ) ),
			)
		);
		$slides = 'style-v1' === $settings['style'] ? $settings['slides_style'] : $settings['slides'];

		?>
		
			<?php $widget->skin_loop_header( $settings ); ?>
				<?php foreach ( $slides as $slide ) : ?>                                    
					<?php
					$widget->print_box_slide( $slide, $settings, $slide['_id'], $count );
					$count++;
					?>
				<?php endforeach; ?>
			</div>
			<?php $this->parent->render_swiper_pagination( $settings ); ?>
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

		// $pagination_class = [ 'swiper-pagination', 'position-relative' ];
		// if ( $settings['pagination_class'] ) {
		// $pagination_class[] = $settings['pagination_class'];
		// }

		// $this->parent->add_render_attribute(
		// 'pagination',
		// [
		// 'class' => $pagination_class,

		// ]
		// );
		// $this->parent->render_swiper_pagination( $settings );
		?>
		<?php
	}


	/**
	 * Render.
	 *
	 * @return void
	 */
	public function render() {
		$widget       = $this->parent;
		$settings     = $widget->get_settings_for_display();
		$slides       = 'style-v1' === $settings['style'] ? $settings['slides_style'] : $settings['slides'];
		$slides_count = count( $slides );

		?>
		
		  
		  <?php $this->print_slide( $settings ); ?>
		  
		<?php
		$widget->render_script();
	}

	/**
	 * Skin print template.
	 *
	 * @param array $content the content.
	 * @param array $iconbox_carousel widget name.
	 * @return string
	 */
	public function skin_print_template( $content, $iconbox_carousel ) {

		if ( 'sn-iconbox-carousel' == $iconbox_carousel->get_name() ) {
			return '';
		}

		return $content;
	}
}
