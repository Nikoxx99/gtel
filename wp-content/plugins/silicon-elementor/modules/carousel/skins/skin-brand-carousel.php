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
 * Skin Brand Carousel
 */
class Skin_Brand_Carousel extends Skin_Base {

	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'brand-carousel';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Brand Carousel', 'silicon-elementor' );
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
		add_filter( 'silicon-elementor/widget/sn-brand-carousel/print_template', [ $this, 'skin_print_template' ], 10, 2 );
		add_action( 'elementor/element/sn-brand-carousel/section_additional_options/before_section_end', [ $this, 'update_section_additional_options' ], 10, 1 );

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
					'_skin!' => 'brand-carousel',
				],
			]
		);

		$widget->update_control(
			'prev_arrow_id',
			[
				'default'   => 'prev',
				'condition' => [
					'_skin'       => 'brand-carousel',
					'style'       => 'style-v1',
					'show_arrows' => 'yes',
				],
			]
		);

		$widget->update_control(
			'next_arrow_id',
			[
				'default'   => 'next',
				'condition' => [
					'_skin'       => 'brand-carousel',
					'style'       => 'style-v1',
					'show_arrows' => 'yes',
				],
			]
		);

		$widget->update_control(
			'show_arrows',
			[
				'label'   => esc_html__( 'Enable Arrow ID', 'silicon-elementor' ),
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
			'slidesPerView' => 2,
			'spaceBetween'  => isset( $settings['space_between'] ) ? $settings['space_between'] : 0,
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
		if ( 'yes' === $settings['enable_space_between'] && 'style-v1' === $settings['style'] ) {
			$swiper_settings['spaceBetween']                        = isset( $settings['space_between_mobile'] ) ? $settings['space_between_mobile'] : 0;
			$swiper_settings['breakpoints']['500']['spaceBetween']  = isset( $settings['space_between'] ) ? $settings['space_between'] : 8;
			$swiper_settings['breakpoints']['650']['spaceBetween']  = isset( $settings['space_between_tablet'] ) ? $settings['space_between_tablet'] : 8;
			$swiper_settings['breakpoints']['900']['spaceBetween']  = isset( $settings['space_between'] ) ? $settings['space_between'] : 8;
			$swiper_settings['breakpoints']['1100']['spaceBetween'] = isset( $settings['space_between'] ) ? $settings['space_between'] : 8;
		}
		if ( 'yes' === $settings['enable_space_between'] && 'style-v2' === $settings['style'] ) {
			$swiper_settings['spaceBetween']                        = isset( $settings['space_between_mobile'] ) ? $settings['space_between_mobile'] : 8;
			$swiper_settings['breakpoints']['500']['spaceBetween']  = isset( $settings['space_between'] ) ? $settings['space_between'] : 0;
			$swiper_settings['breakpoints']['600']['spaceBetween']  = isset( $settings['space_between_tablet'] ) ? $settings['space_between_tablet'] : 0;
			$swiper_settings['breakpoints']['768']['spaceBetween']  = isset( $settings['space_between'] ) ? $settings['space_between'] : 0;
			$swiper_settings['breakpoints']['850']['spaceBetween']  = isset( $settings['space_between'] ) ? $settings['space_between'] : 0;
			$swiper_settings['breakpoints']['1000']['spaceBetween'] = isset( $settings['space_between'] ) ? $settings['space_between'] : 0;
			$swiper_settings['breakpoints']['1200']['spaceBetween'] = isset( $settings['space_between'] ) ? $settings['space_between'] : 0;
		}
		if ( 'slide' === $settings['effect'] && 'style-v1' === $settings['style'] ) {
			$swiper_settings['breakpoints']['500']['slidesPerView']  = ! empty( $settings['slides_per_view_mobile'] ) ? $settings['slides_per_view_mobile'] : 3;
			$swiper_settings['breakpoints']['650']['slidesPerView']  = ! empty( $settings['slides_per_view_tablet'] ) ? $settings['slides_per_view_tablet'] : 4;
			$swiper_settings['breakpoints']['900']['slidesPerView']  = 5;
			$swiper_settings['breakpoints']['1100']['slidesPerView'] = ! empty( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 6;

		}
		if ( 'slide' === $settings['effect'] && 'style-v2' === $settings['style'] ) {
			$swiper_settings['breakpoints']['500']['slidesPerView']  = ! empty( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 3;
			$swiper_settings['breakpoints']['600']['slidesPerView']  = ! empty( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 4;
			$swiper_settings['breakpoints']['768']['slidesPerView']  = ! empty( $settings['slides_per_view_tablet'] ) ? $settings['slides_per_view_tablet'] : 5;
			$swiper_settings['breakpoints']['850']['slidesPerView']  = ! empty( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 6;
			$swiper_settings['breakpoints']['1000']['slidesPerView'] = 7;
			$swiper_settings['breakpoints']['1200']['slidesPerView'] = ! empty( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 8;

		}
		if ( 'yes' === $settings['show_arrows'] ) {
			$prev_id                       = ! empty( $settings['prev_arrow_id'] ) ? $settings['prev_arrow_id'] : 'prev-brand';
			$next_id                       = ! empty( $settings['next_arrow_id'] ) ? $settings['next_arrow_id'] : 'next-brand';
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
				'data-swiper-options' => esc_attr( json_encode( $this->get_swiper_carousel_options( $settings ) ) ),
			)
		);

		?>
		
			<?php $widget->skin_loop_header( $settings ); ?>
				<?php foreach ( $settings['slides'] as $slide ) : ?>                                    
					<?php
					$widget->print_image_slide( $slide, $settings, $slide['_id'], $count );
					$count++;
					?>
				<?php endforeach; ?>
			</div>
			<?php $this->parent->render_swiper_pagination( $settings ); ?>
			<?php
			$this->parent->render_arrow_button( $settings, 'prev-brand', 'next-brand' );
			?>
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

		// $pagination_class       = [ 'swiper-pagination', 'position-relative' ];
		// $pagination_class[] = $settings['pagination_class'];

		// $this->parent->add_render_attribute(
		// 'pagination',
		// [
		// 'class' => $pagination_class,
		// 'id'    => 'swiper-pagination',

		// ]
		// );
		// $this->parent->render_swiper_pagination( $settings );
		?>
		<?php
			// $this->parent->render_arrow_button( $settings, 'prev-brand', 'next-brand' );
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
		$slides_count = count( $settings['slides'] );
		?>
		
		  
		  <?php $this->print_slide( $settings ); ?>
		 
		  <?php	// $this->silicon_pagination( $settings ); ?>
		  
		<?php
		$widget->render_script();
	}

	/**
	 * Skin print template.
	 *
	 * @param array $content the content.
	 * @param array $brand_carousel widget name.
	 * @return string
	 */
	public function skin_print_template( $content, $brand_carousel ) {

		if ( 'sn-brand-carousel' == $brand_carousel->get_name() ) {
			return '';
		}

		return $content;
	}
}



