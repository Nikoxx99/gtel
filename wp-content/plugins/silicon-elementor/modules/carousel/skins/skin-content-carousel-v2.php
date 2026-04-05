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
class Skin_Content_Carousel_V2 extends Skin_Base {

	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'content-carousel-v2';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Skin v2', 'silicon-elementor' );
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return void
	 */
	protected function _register_controls_actions() {
		add_filter( 'silicon-elementor/widget/sn-content-carousel/print_template', [ $this, 'skin_print_template' ], 10, 2 );
		add_action( 'elementor/element/sn-content-carousel/section_slides/before_section_end', [ $this, 'update_section_slides' ], 10, 1 );
		add_action( 'elementor/element/sn-content-carousel/section_additional_options/before_section_end', [ $this, 'update_section_additional_options' ], 10, 1 );
		add_action( 'elementor/element/sn-content-carousel/section_navigation/before_section_end', [ $this, 'update_section_navigation' ], 10, 1 );
		add_action( 'elementor/element/sn-content-carousel/repeater_content/before_section_end', [ $this, 'update_content_styles' ], 10, 1 );
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
			'enable_fade',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Fade', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Disable', 'silicon-elementor' ),
				'condition' => [
					'_skin' => [ 'content-carousel-v2' ],
				],
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
			'disable_on_interaction',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Disable On Interaction', 'silicon-elementor' ),
				'default'   => 'no',
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
	 * Update section navigation
	 *
	 * @param array $widget section navigation.
	 */
	public function update_section_navigation( $widget ) {

		$update_control_ids = [ 'arrows_wrapper_css' ];

		foreach ( $update_control_ids as $update_control_id ) {
			$widget->update_control(
				$update_control_id,
				[
					'condition' => [
						'_skin!' => [ 'content-carousel-v2' ],
					],
				]
			);
		}
	}

	/**
	 * Update section slides
	 *
	 * @param array $widget update slides.
	 */
	public function update_content_styles( $widget ) {

		$update_control_ids = [ 'title_hover_color' ];

		foreach ( $update_control_ids as $update_control_id ) {
			$widget->update_control(
				$update_control_id,
				[
					'condition' => [
						'_skin' => [ 'content-carousel-v1' ],
					],
				]
			);
		}

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

		$prev_id                       = ! empty( $settings['prev_arrow_id'] ) ? $settings['prev_arrow_id'] : '';
		$next_id                       = ! empty( $settings['next_arrow_id'] ) ? $settings['next_arrow_id'] : '';
		if ( 'yes' === $settings['show_arrows'] ) {
			$swiper_settings['navigation'] = array(
				'prevEl' => '#' . $prev_id,
				'nextEl' => '#' . $next_id,

			);
		}

		if ( 'yes' === $settings['enable_fade'] ) {
			$swiper_settings['effect']                  = 'fade';
			$swiper_settings['fadeEffect']['crossFade'] = true;
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
		if ( $settings['autoplay'] && $settings['disable_on_interaction'] ) {
			$swiper_settings['autoplay']['disableOnInteraction'] = true;
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
		$widget->skin_slide_start( $settings, $element_key );
		$title_css = 'sn-title swiper-title';
		if ( $settings['title_css'] ) {
			$title_css .= ' ' . $settings['title_css'];
		}
		// Title animation ...
		$title_animation_class[] = $title_css;
		if ( $settings['animation_title_direction'] ) {
			$title_animation_class[] = $settings['animation_title_direction'];
		}
		if ( $settings['scale_title_direction'] ) {
			$title_animation_class[] = $settings['scale_title_direction'];
		}
		if ( 'yes' === $settings['enable_title_delay'] ) {
			$title_animation_class[] = 'delay-' . $settings['animation_title_delay'];
		}
		$widget->add_render_attribute(
			'sn_title' . $element_key,
			[
				'class' => $title_animation_class,
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
		?>
			<<?php echo esc_html( $slide['title_tag'] ); ?> <?php $widget->print_render_attribute_string( 'sn_title' . $element_key ); ?>>
					<?php echo wp_kses_post( $slide['title'] ); ?>
			</<?php echo esc_html( $slide['title_tag'] ); ?>>
			<?php
			// Description animation ...
			$description_animation_class = [ 'sn-desc-animation' ];
			if ( $settings['animation_description_direction'] ) {
				$description_animation_class[] = $settings['animation_description_direction'];
			}
			if ( $settings['scale_description_direction'] ) {
				$description_animation_class[] = $settings['scale_description_direction'];
			}
			if ( 'yes' === $settings['enable_description_delay'] ) {
				$description_animation_class[] = 'delay-' . $settings['animation_description_delay'];
			}
			$widget->add_render_attribute(
				'sn_description_animation' . $element_key,
				[
					'class' => $description_animation_class,
				]
			);
			if ( $settings['enable_description_animation'] ) :
				?>
				<div <?php $widget->print_render_attribute_string( 'sn_description_animation' . $element_key ); ?>>
			<?php endif; ?>
				<p <?php $widget->print_render_attribute_string( 'sn_desc' . $element_key ); ?>><?php echo esc_html( $slide['description'] ); ?></p>
			<?php
			if ( $settings['enable_description_animation'] ) :
				?>
				</div>
			<?php endif; ?>
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
				// Button animation ...
				$button_animation_class = [ 'sn-button-animation' ];
				if ( $settings['animation_button_direction'] ) {
					$button_animation_class[] = $settings['animation_button_direction'];
				}
				if ( $settings['scale_button_direction'] ) {
					$button_animation_class[] = $settings['scale_button_direction'];
				}
				if ( $settings['enable_button_delay'] ) {
					$button_animation_class[] = 'delay-' . $settings['animation_button_delay'];
				}
				$widget->add_render_attribute(
					'sn_button_animation' . $element_key,
					[
						'class' => $button_animation_class,
					]
				);
				if ( $settings['enable_button_animation'] ) :
					?>
				<div <?php $widget->print_render_attribute_string( 'sn_button_animation' . $element_key ); ?>>
				<?php endif; ?>
					<a <?php $widget->print_render_attribute_string( 'sn_button' . $element_key ); ?>><?php echo esc_html( $slide['button_text'] ); ?></a>
				<?php
				if ( $settings['enable_button_animation'] ) :
					?>
					</div>
					<?php
					endif;
			}
			?>
		</div>
		<?php
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

				'class' => 'btn btn-prev btn-icon btn-xl bg-transparent shadow-none position-absolute top-50 start-0 translate-middle-y d-none d-md-inline-flex ms-n3 ms-lg-2',

			]
		);
		$widget->add_render_attribute(
			'next_arrow_button',
			[

				'class' => 'btn btn-next btn-icon btn-xl bg-transparent shadow-none position-absolute top-50 end-0 translate-middle-y d-none d-md-inline-flex me-n3 me-lg-2',

			]
		);
		?>
			<?php
			$widget->print_prev_button( 'fs-1' );
			$widget->print_next_button( 'fs-1' );
			?>
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

		// $defaults        = array( 'container_class' => 'swiper sn-elementor-main-swiper mx-0' );
		// $settings        = array_merge( $defaults, $settings );
		$swiper_settings = $this->get_swiper_carousel_options( $settings );

		$widget->add_render_attribute(
			'slider',
			array(
				'data-swiper-options' => esc_attr( json_encode( $this->get_swiper_carousel_options( $settings ) ) ),
			)
		);
		$prev_id = ! empty( $settings['prev_arrow_id'] ) ? $settings['prev_arrow_id'] : '';
		$next_id = ! empty( $settings['next_arrow_id'] ) ? $settings['next_arrow_id'] : '';
		$widget->render_arrow_button( $settings, $prev_id, $next_id );
		?>
		<div class="position-relative text-center zindex-5 overflow-hidden pt-4 py-md-5">
		<?php
		if ( $settings['show_arrows'] ) :
			$this->print_arrow_button( $settings );
		endif;
		$container_class = [ 'text-center', 'py-xl-5' ];
		if ( 'yes' === $settings['enable_container'] ) {
			$container_class[] = 'container';
		}
		$widget->add_render_attribute( 'container_class', 'class', $container_class );
		?>
			<div <?php $widget->print_render_attribute_string( 'container_class' ); ?>>
				<div class="row justify-content-center pt-lg-5">
					<div class="col-xl-8 col-lg-9 col-md-10 col-11">
					<?php
						$widget->skin_loop_header( $settings );
					?>
								<?php foreach ( $settings['slides_skin'] as $slide ) : ?>                                    
									<?php
									$this->print_slide( $slide, $settings, $slide['_id'] );
									?>
								<?php endforeach; ?>					
							</div>
							<?php
								$widget->render_swiper_pagination( $settings );
							?>
						</div>
					</div>
				</div>
			</div>
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
