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
 * Skin Content Carousel V2
 */
class Skin_Content_Carousel_V3 extends Skin_Base {

	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'content-carousel-v3';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Skin v3', 'silicon-elementor' );
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return void
	 */
	protected function _register_controls_actions() {
		add_filter( 'silicon-elementor/widget/sn-content-carousel/print_template', [ $this, 'skin_print_template' ], 10, 2 );
		add_action( 'elementor/element/sn-content-carousel/section_additional_options/before_section_end', [ $this, 'update_section_additional_options' ], 10, 1 );

	}

	/**
	 * Update section slides
	 *
	 * @param array $widget update slides.
	 */
	public function update_section_slides( $widget ) {

		$update_control_ids = [ 'slides', 'effect', 'slides_per_view', 'center_slides' ];

		foreach ( $update_control_ids as $update_control_id ) {
			$widget->update_control(
				$update_control_id,
				[
					'condition' => [
						'_skin!' => [ 'content-carousel-v2', 'content-carousel-v3' ],
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
			'enable_container',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Container', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Disable', 'silicon-elementor' ),
				'condition' => [
					'_skin' => [ 'content-carousel-v2' ],
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

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => 'pagination',
			]
		);
		$this->parent->add_control(
			'pagination_id',
			[
				'type'      => Controls_Manager::TEXT,
				'label'     => esc_html__( 'Pagination Id', 'silicon-elementor' ),
				'default'   => 'case-study-pagination',
				'condition' => [
					'_skin'       => [ 'content-carousel-v3' ],
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

		$swiper_settings               = [];
		$swiper_settings['tabs']       = true;
		$prev_id                       = ! empty( $settings['prev_arrow_id'] ) ? $settings['prev_arrow_id'] : '';
		$next_id                       = ! empty( $settings['next_arrow_id'] ) ? $settings['next_arrow_id'] : '';
		if ( 'yes' === $settings['show_arrows'] ) {
			$swiper_settings['navigation'] = array(
				'prevEl' => '#' . $prev_id,
				'nextEl' => '#' . $next_id,

			);
		}
		if ( $settings['pagination'] && $settings['pagination_id'] ) {
			$swiper_settings['pagination'] = array(
				'el' => '#' . $settings['pagination_id'],
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
			$swiper_settings['autoplay']['pauseOnMouseEnter'] = true;
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
		$id_int    = substr( $widget->get_id_int(), 0, 3 );
		$id        = 'case-study-' . $id_int . $slide['_id'];
		$widget->add_render_attribute( 'carousel_slide_css-' . $element_key, 'data-swiper-tab', '#' . $id );
		$widget->skin_slide_start( $settings, $element_key );
		$title_css = 'sn-title swiper-title';
		if ( $settings['title_css'] ) {
			$title_css .= ' ' . $settings['title_css'];
		}

		$widget->add_render_attribute(
			'sn_title' . $element_key,
			[
				'class' => $title_css,
			]
		);

		$widget->add_render_attribute(
			'sn_subtitle' . $element_key,
			[
				'class' => [ 'swiper-subtitle', $settings['subtitle_css'] ],
			]
		);

		$desc_css = 'swiper-description';
		if ( $settings['desc_css'] ) {
			$desc_css .= ' ' . $settings['desc_css'];
		}
		$widget->add_render_attribute(
			'sn_desc' . $element_key,
			[
				'class' => $desc_css,
			]
		);
			$image_class = 'd-block mb-3';
			$image_html  = wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $slide, 'thumbnail', 'image' ) );
			echo wp_kses_post( SN_Utils::add_class_to_image_html( $image_html, $image_class ) );
		?>
			<<?php echo esc_html( $slide['title_tag'] ); ?> <?php $widget->print_render_attribute_string( 'sn_title' . $element_key ); ?>>
					<?php echo wp_kses_post( $slide['title'] ); ?>
			</<?php echo esc_html( $slide['title_tag'] ); ?>>
			<p <?php $widget->print_render_attribute_string( 'sn_subtitle' . $element_key ); ?>><?php echo esc_html( $slide['subtitle'] ); ?></p>			
			<p <?php $widget->print_render_attribute_string( 'sn_desc' . $element_key ); ?>><?php echo esc_html( $slide['description'] ); ?></p>
			<?php
			if ( $slide['enable_button'] ) {
				$button_class = [ 'btn', 'swiper-button' ];
				if ( $slide['button_type'] ) {
					$button_class[] = 'btn-' . $slide['button_type'];
				}
				if ( $slide['button_size'] ) {
					$button_class[] = 'btn-' . $slide['button_size'];
				}
				if ( 'yes' === $slide['enable_shadow'] ) {
					$button_class[] = 'shadow-' . $slide['button_type'];
				}
				if ( $slide['button_shape'] ) {
					$button_class[] = $slide['button_shape'];
				}
				if ( $settings['button_css'] ) {
					$button_class[] = $settings['button_css'];
				}
				$widget->add_render_attribute(
					'sn_button' . $element_key,
					[
						'href'  => ! empty( $slide['button_link']['url'] ) ? $slide['button_link']['url'] : '',
						'class' => $button_class,
					]
				);
				?>
				<a <?php $widget->print_render_attribute_string( 'sn_button' . $element_key ); ?>><?php echo esc_html( $slide['button_text'] ); ?></a>
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
		$widget = $this->parent;
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
		<div class="container position-relative zindex-5 py-5">
		<div class="row py-2 py-md-3">
		<div class="col-xl-5 col-lg-7 col-md-9">
		<?php
		$prev_id = ! empty( $settings['prev_arrow_id'] ) ? $settings['prev_arrow_id'] : '';
		$next_id = ! empty( $settings['next_arrow_id'] ) ? $settings['next_arrow_id'] : '';
		$widget->render_arrow_button( $settings, $prev_id, $next_id );
		if ( $settings['show_arrows'] ) :
			$this->print_arrow_button( $settings );
		endif;
		?>
		<div class="card bg-white shadow-sm p-3">
			<div class="card-body">
		<?php
			$widget->skin_loop_header( $settings );
		?>
					<?php foreach ( $settings['slides_tab'] as $slide ) : ?>                                    
						<?php
						$this->print_slide( $slide, $settings, $slide['_id'] );
						?>
					<?php endforeach; ?>					
			</div>
		</div>
		</div>
		</div>
		
		<?php
		if ( $settings['pagination'] ) {
			?>
			<div class="dark-mode pt-4 mt-3">
				<?php
				$widget->add_render_attribute(
					'swiper_pagination',
					[
						'id' => $settings['pagination_id'],
					]
				);
				$widget->render_swiper_pagination( $settings );
				?>
			</div>
		
		</div>
		</div>
		</div>
			<?php
		}
	}

	/**
	 * Render button.
	 *
	 * @param array $settings widgets settings.
	 *
	 * @return void
	 */
	public function print_arrow_button( array $settings ) {
		$widget = $this->parent;
		if ( null === $settings ) {
			$settings = $this->get_settings_for_display();
		}
		$widget->add_render_attribute(
			'prev_arrow_button',
			[

				'class' => 'btn btn-prev btn-icon btn-sm bg-white me-2',

			]
		);
		$widget->add_render_attribute(
			'next_arrow_button',
			[

				'class' => 'btn btn-next btn-icon btn-sm bg-white ms-2',

			]
		);
		$widget->add_render_attribute(
			'arrows_wrapper',
			[

				'class' => [ 'd-flex justify-content-center justify-content-md-start', $settings['arrows_wrapper_css'] ],

			]
		);
		?>
		<div <?php $widget->print_render_attribute_string( 'arrows_wrapper' ); ?>>
			<?php
			$widget->print_prev_button();
			$widget->print_next_button();
			?>
		</div>
		<?php

	}

	/**
	 * Get slider settings..
	 *
	 * @param array $slide the slides settings.
	 * @param array $settings the widget settings.
	 * @param array $element_key the element_key slider id.
	 * @param array $count the widget settings.
	 * @return void
	 */
	public function print_content_slide( array $slide, array $settings, $element_key, $count ) {
		$widget          = $this->parent;
		$id_int          = substr( $widget->get_id_int(), 0, 3 );
		$id              = 'case-study-' . $id_int . $slide['_id'];
		$content_wrapper = [ 'swiper-tab', 'jarallax position-absolute top-0 start-0 w-100 h-100' ];
		if ( 1 === $count ) {
			$content_wrapper[] = 'active';
		}
		$widget->add_render_attribute(
			'content-' . $element_key,
			[
				'class'         => $content_wrapper,
				'id'            => $id,
				'data-jarallax' => '',
				'data-speed'    => $settings['data_speed']['size'],

			]
		);

		if ( $slide['bg_image']['url'] ) {

			$widget->add_render_attribute(
				'jarallax-' . $element_key,
				[
					'class' => 'jarallax-img',
					'style' => 'background-image: url(' . $slide['bg_image']['url'] . ');',
				]
			);
		}

		?>
			<div <?php $widget->print_render_attribute_string( 'content-' . $element_key ); ?>>
				<span class="position-absolute top-0 start-0 w-100 h-100 bg-dark"></span>
				<?php if ( $slide['bg_image']['url'] ) : ?>
				<div <?php $widget->print_render_attribute_string( 'jarallax-' . $element_key ); ?>></div>
				<?php endif; ?>
			</div>
			<?php

	}

	/**
	 * Get content
	 *
	 * @param array $settings The widget settings.
	 * @return void
	 */
	protected function print_content_slider( array $settings = null ) {
		$widget = $this->parent;
		$count  = 1;
		if ( null === $settings ) {
			$settings = $widget->get_settings_for_display();
		}
		?>
		<div class="swiper-tabs position-absolute top-0 start-0 w-100 h-100">
			<?php
			foreach ( $settings['slides_tab'] as $slide ) :
				$this->print_content_slide( $slide, $settings, $slide['_id'], $count );
				$count++;
			endforeach;
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
		$this->print_content_slider( $settings );
		$this->print_slider( $settings );
		$widget->render_script();
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
