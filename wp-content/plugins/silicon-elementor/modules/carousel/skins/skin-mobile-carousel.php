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
 * Skin Mobile Carousel
 */
class Skin_Mobile_Carousel extends Skin_Base {

	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'mobile-carousel-1';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Mobile Carousel', 'silicon-elementor' );
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
		add_filter( 'silicon-elementor/widget/sn-mobile-carousel/print_template', [ $this, 'skin_print_template' ], 10, 2 );
		add_action( 'elementor/element/sn-mobile-carousel/section_slides/after_section_end', [ $this, 'update_section_slides' ], 10, 1 );
		add_action( 'elementor/element/sn-mobile-carousel/section_additional_options/after_section_end', [ $this, 'update_additional_options' ], 10, 1 );
		add_action( 'elementor/element/sn-mobile-carousel/section_navigation/before_section_end', [ $this, 'update_section_navigation' ], 10, 1 );
	}

	/**
	 * Update section slides
	 *
	 * @param array $widget update slides.
	 */
	public function update_section_slides( $widget ) {

		$controls = array( 'enable_space_between', 'space_between', 'pagination' );

		foreach ( $controls as $control ) {

			$widget->update_control(
				$control,
				[
					'condition' => [
						'_skin' => '',
					],
				]
			);

		}

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => 'slides',
			]
		);

		$this->parent->add_control(
			'enable_frame',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Frame', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Hide', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Show', 'silicon-elementor' ),
			]
		);

		$this->parent->add_control(
			'enable_container',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Container', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Disabled', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Enabled', 'silicon-elementor' ),
			]
		);

		$this->parent->end_injection();
	}

	/**
	 * Update section slides additional options
	 *
	 * @param array $widget update additional options.
	 */
	public function update_additional_options( $widget ) {

		$widget->update_control(
			'prev_arrow_id',
			[
				'default'   => 'prev-mobile',
				'condition' => [
					'show_arrows' => 'yes',
				],
			]
		);

		$widget->update_control(
			'next_arrow_id',
			[
				'default'   => 'next-mobile',
				'condition' => [
					'show_arrows' => 'yes',
				],
			]
		);

		$widget->update_control(
			'pagination',
			[
				'default' => 'progressbar',
				'_skin!'  => '',
			]
		);

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => 'pagination',
			]
		);

		$this->parent->add_control(
			'pagination_id',
			[
				'label'       => esc_html__( 'Pagination ID', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Additional CSS classes separated by space that you\'d like to apply to the highlighted text', 'silicon-elementor' ),
				'default'     => 'swiper-progress',
				'condition'   => [
					'pagination!' => '',
				],
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

		$widget->update_control(
			'pagination_css',
			[
				'default'   => 'bottom-0',
				'condition' => [
					'_skin!' => '',
				],
			]
		);

	}

	/**
	 * Render static mobile images.
	 *
	 * @param array $settings The widget settings.
	 * @return void
	 */
	public function render_static_image( array $settings ) {
		if ( $settings['enable_frame'] ) :
			?>
			<div class="position-absolute top-0 start-50 translate-middle-x h-100 w-100 w-md-33 zindex-5">
				<div class="d-flex bg-repeat-0 bg-size-cover w-100 h-100 mx-auto first_layer" style="max-width: 328px; background-image: url(<?php echo esc_url( SILICON_ELEMENTOR_ASSETS_URL . 'images/phone-frame.png' ); ?>);"></div>
			</div>
			<div class="position-absolute top-0 start-50 translate-middle-x h-100 w-100 w-md-33">
				<div class="d-flex bg-repeat-0 bg-size-cover w-100 h-100 mx-auto second_layer" style="max-width: 328px; background-image: url(<?php echo esc_url( SILICON_ELEMENTOR_ASSETS_URL . 'images/phone-screen.png' ); ?>);"></div>
			</div>
			<?php
		endif;

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
			'tabs' => true,
		);
		if ( $settings['pagination'] && $settings['pagination_id'] ) {
			$swiper_settings['pagination'] = array(
				'el' => '#' . $settings['pagination_id'],

			);
		}

		if ( $settings['show_arrows'] ) {
			$prev_id                       = ! empty( $settings['prev_arrow_id'] ) ? $settings['prev_arrow_id'] : '';
			$next_id                       = ! empty( $settings['next_arrow_id'] ) ? $settings['next_arrow_id'] : '';
			$swiper_settings['navigation'] = array(
				'prevEl' => '#' . $prev_id,
				'nextEl' => '#' . $next_id,

			);
		}

		if ( $settings['center_slides'] ) {
			$swiper_settings['centeredSlides'] = 'true';
		}
		if ( 'fade' === $settings['effect'] ) {
			$swiper_settings['effect']                  = 'fade';
			$swiper_settings['fadeEffect']['crossFade'] = true;
		}
		if ( 'slide' === $settings['effect'] ) {

			$swiper_settings['breakpoints']['1024']['slidesPerView'] = isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 3;
			$swiper_settings['breakpoints']['768']['slidesPerView']  = isset( $settings['slides_per_view_tablet'] ) ? $settings['slides_per_view_tablet'] : 1;
			$swiper_settings['breakpoints']['0']['slidesPerView']    = isset( $settings['slides_per_view_mobile'] ) ? $settings['slides_per_view_mobile'] : 1;

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

		$defaults        = array( 'container_class' => 'swiper mobile-app-slider sn-elementor-main-swiper' );
		$settings        = array_merge( $defaults, $settings );
		$swiper_settings = $this->get_swiper_carousel_options( $settings );

		$widget->add_render_attribute(
			'slider',
			array(
				'class'               => 'mobile-app-slider',
				'data-swiper-options' => esc_attr( json_encode( $this->get_swiper_carousel_options( $settings ) ) ),
			)
		);

		$widget->skin_loop_header( $settings );
		?>
				<?php foreach ( $settings['slides'] as $slide ) : ?>                                    
					<?php
					$widget->print_image_slide( $slide, $settings, $slide['_id'] );
					?>
				<?php endforeach; ?>
			</div>
		</div>        
		<?php
	}

	/**
	 * Get content
	 *
	 * @param array $settings The widget settings.
	 * @return void
	 */
	protected function print_slide_content( array $settings = null ) {
		$widget = $this->parent;
		$count  = 1;
		if ( null === $settings ) {
			$settings = $widget->get_settings_for_display();
		}
		?>
		<div class="row justify-content-center pt-4 mt-2 mt-md-3">
			<div class="swiper-tabs col-xl-6 col-lg-7 col-md-8 text-center">
			<?php
			foreach ( $settings['slides'] as $slide ) :
				$widget->print_content_slider( $slide, $settings, $slide['_id'], $count );
				$count++;
			endforeach;
			?>
			</div>
		</div>        
			<?php
	}

	/**
	 * Render button.
	 *
	 * @return void
	 */
	public function render_button() {
		?>
		<button type="button" id="prev-screen" class="btn btn-prev btn-icon position-absolute top-50 start-0 ms-n5 translate-middle-y">
			<?php $this->parent->render_swiper_button( 'prev' ); ?>
		</button>
		<button type="button" id="next-screen" class="btn btn-next btn-icon position-absolute top-50 end-0 me-n5 translate-middle-y">
			<?php $this->parent->render_swiper_button( 'next' ); ?>
		</button>
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
		<section class="position-relative bg-secondary py-5">
			<?php
			$container_class = [ 'mt-3 pt-md-2 pt-lg-4 pb-2 pb-md-4 pb-lg-5' ];
			if ( $settings['enable_container'] ) {
				$container_class[] = 'container';
			}
			$widget->add_render_attribute(
				'container',
				[

					'class' => $container_class,

				]
			);
			?>
			<div <?php $widget->print_render_attribute_string( 'container' ); ?>>
				<?php $widget->render_title(); ?>
				<div class="position-relative mx-5">
					<?php
					$prev_id = ! empty( $settings['prev_arrow_id'] ) ? $settings['prev_arrow_id'] : '';
					$next_id = ! empty( $settings['next_arrow_id'] ) ? $settings['next_arrow_id'] : '';
					$widget->render_arrow_button( $settings, $prev_id, $next_id );
					if ( $settings['show_arrows'] ) {
						$widget->add_render_attribute(
							'prev_arrow_button',
							[

								'class' => 'btn btn-prev btn-icon position-absolute top-50 start-0 ms-n5 translate-middle-y',

							]
						);
						$widget->add_render_attribute(
							'next_arrow_button',
							[

								'class' => 'btn btn-next btn-icon position-absolute top-50 end-0 me-n5 translate-middle-y',

							]
						);
						$widget->print_prev_button();
						$widget->print_next_button();
					}
					?>
				<?php $this->render_static_image( $settings ); ?>
				<?php $this->print_slider( $settings ); ?>
				</div>
				<?php
				$this->print_slide_content( $settings );
				?>
			</div>
			<?php
			$widget->add_render_attribute(
				'swiper_pagination',
				[
					'id'    => $settings['pagination_id'],
					'style' => 'top: auto;',
				]
			);
			$widget->render_swiper_pagination( $settings );
			?>
		</section>
		<?php
		$widget->render_script();
	}

	/**
	 * Skin print template.
	 *
	 * @param array $content the content.
	 * @param array $mobile_carousel widget name.
	 * @return string
	 */
	public function skin_print_template( $content, $mobile_carousel ) {

		if ( 'sn-mobile-carousel' == $mobile_carousel->get_name() ) {
			return '';
		}

		return $content;
	}
}
