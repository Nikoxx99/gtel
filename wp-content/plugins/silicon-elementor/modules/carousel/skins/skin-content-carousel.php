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
 * Skin Content Carousel
 */
class Skin_Content_Carousel extends Skin_Base {

	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'content-carousel-v1';
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
		add_filter( 'silicon-elementor/widget/sn-content-carousel/print_template', [ $this, 'skin_print_template' ], 10, 2 );
		add_action( 'elementor/element/sn-content-carousel/section_additional_options/before_section_end', [ $this, 'update_section_additional_options' ], 10, 1 );
		add_action( 'elementor/element/sn-content-carousel/section_navigation/before_section_end', [ $this, 'update_section_navigation' ], 10, 1 );
		add_action( 'elementor/element/sn-content-carousel/section_meta_style/before_section_end', [ $this, 'update_section_meta_style' ], 10, 1 );
	}

	/**
	 * Update section additional options
	 *
	 * @param array $widget section meta style.
	 */
	public function update_section_meta_style( $widget ) {
		$this->parent->start_injection(
			array(
				'at' => 'before',
				'of' => 'carousel_options_css',
			)
		);

		$this->parent->add_control(
			'overall_wrapper',
			array(
				'label'       => esc_html__( 'Top Wrapper CSS', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'This is added above the div with swiper class', 'silicon-elementor' ),
				'default'     => 'd-flex flex-column h-100',
				'condition'   => array(
					'_skin' => array( 'content-carousel-v1' ),
				),
			)
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
			'prev_arrow_id',
			[
				'default'   => 'prev-content',
				'condition' => [
					'_skin!'      => '',
					'show_arrows' => 'yes',
				],
			]
		);

		$widget->update_control(
			'next_arrow_id',
			[
				'default'   => 'next-content',
				'condition' => [
					'_skin!'      => '',
					'show_arrows' => 'yes',
				],
			]
		);

		$widget->update_control(
			'pagination',
			[
				'condition' => [
					'_skin!' => [ 'content-carousel-v1' ],
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
			'custom_arrows',
			[
				'type'        => Controls_Manager::SWITCHER,
				'label'       => esc_html__( 'Custom Arrows', 'silicon-elementor' ),
				'default'     => 'no',
				'description' => esc_html__( 'Enable for custom build buttons', 'silicon-elementor' ),
				'label_off'   => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_on'    => esc_html__( 'Disable', 'silicon-elementor' ),
				'condition'   => [
					'_skin' => [ 'content-carousel-v1' ],
					'show_arrows!' => 'yes',
				],
			]
		);

		$this->parent->add_control(
			'custom_prev_arrow_id',
			[
				'label'       => esc_html__( 'Prev Arrow ID', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Enter ID for Previous Button', 'silicon-elementor' ),
				'condition'   => [
					'show_arrows!' => 'yes',
					'_skin' => [ 'content-carousel-v1' ],
					'custom_arrows' => 'yes',
				],
			]
		);

		$this->parent->add_control(
			'custom_next_arrow_id',
			[
				'label'       => esc_html__( 'Next Arrow ID', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Enter ID for Next Button', 'silicon-elementor' ),
				'condition'   => [
					'show_arrows!' => 'yes',
					'_skin' => [ 'content-carousel-v1' ],
					'custom_arrows' => 'yes',
				],
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
					'_skin!' => [ 'content-carousel-v2' ],
				],
			]
		);

		$this->parent->add_control(
			'enable_tabs',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Tabs', 'silicon-elementor' ),
				'default'   => 'no',
				'label_off' => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Disable', 'silicon-elementor' ),
				'condition' => [
					'_skin' => [ 'content-carousel-v1' ],
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

		$update_control_ids = [ 'heading_pagination', 'pagination_size', 'pagination_color' ];

		foreach ( $update_control_ids as $update_control_id ) {
			$widget->update_control(
				$update_control_id,
				[
					'condition' => [
						'_skin!' => [ 'content-carousel-v1' ],
					],
				]
			);
		}

		$widget->update_control(
			'pagination_css',
			[
				'default'   => 'position-relative d-md-none pt-2 mt-5',
				'condition' => [
					'_skin' => [ 'content-carousel-v2', 'content-carousel-v3' ],
				],
			]
		);

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => 'arrows_hover_color',
			]
		);

		$this->parent->add_control(
			'arrows_wrapper_css',
			[
				'label' => esc_html__( 'Arrows Wrapper CSS', 'silicon-elementor' ),
				'type'  => Controls_Manager::TEXT,
				'title' => esc_html__( 'Add your custom class for text without the dot. e.g: my-class', 'silicon-elementor' ),
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

		$swiper_settings = [];
		$prev_id         = ! empty( $settings['prev_arrow_id'] ) ? $settings['prev_arrow_id'] : '';
		$next_id         = ! empty( $settings['next_arrow_id'] ) ? $settings['next_arrow_id'] : '';
		if ( 'yes' === $settings['show_arrows'] ) {
			$swiper_settings['navigation'] = array(
				'prevEl' => '#' . $prev_id,
				'nextEl' => '#' . $next_id,

			);
		}

		if ( 'yes' === $settings['custom_arrows'] && 'yes' !== $settings['show_arrows'] ) {
			$prev_id         = ! empty( $settings['custom_prev_arrow_id'] ) ? $settings['custom_prev_arrow_id'] : '';
			$next_id         = ! empty( $settings['custom_next_arrow_id'] ) ? $settings['custom_next_arrow_id'] : '';
			$swiper_settings['navigation'] = array(
				'prevEl' => '#' . $prev_id,
				'nextEl' => '#' . $next_id,

			);
		}

		if ( 'yes' === $settings['enable_tabs'] ) {
			$swiper_settings['tabs'] = true;
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
		$widget->skin_slide_start( $settings, $element_key );
		$link_css = 'swiper-title-hover nav-link justify-content-center justify-content-lg-start fw-bold p-0';
		if ( ! empty( $slide['link']['url'] ) ) {
			$link_css .= ' swiper-title';
		}
		$widget->add_render_attribute(
			'sn_link' . $element_key,
			[
				'href'  => ! empty( $slide['link']['url'] ) ? $slide['link']['url'] : '#',
				'class' => $link_css,
			]
		);
		$title_css = 'sn-title';
		if ( empty( $slide['link']['url'] ) ) {
			$title_css .= ' swiper-title';
		}
		if ( $settings['title_css'] ) {
			$title_css .= ' ' . $settings['title_css'];
		}
		$widget->add_render_attribute(
			'sn_title' . $element_key,
			[
				'class' => $title_css,
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
			<<?php echo esc_html( $settings['title_tag'] ); ?> <?php $widget->print_render_attribute_string( 'sn_title' . $element_key ); ?>>
					<?php if ( ! empty( $slide['link']['url'] ) ) : ?>
						<a <?php $widget->print_render_attribute_string( 'sn_link' . $element_key ); ?>>
					<?php endif; ?>
					<?php echo wp_kses_post( $slide['title'] ); ?>
					<?php
					if ( 'yes' === $settings['enable_icon'] ) :
						if ( ! isset( $settings['icon']['value']['url'] ) ) {
							$icon_css = ' sn-content-icon';
							if ( $settings['icon_css'] ) {
								$icon_css .= ' ' . $settings['icon_css'];
							}
							$widget->add_render_attribute( 'sn-icon', 'class', $settings['icon']['value'] . $icon_css );
							?>
								<i <?php $widget->print_render_attribute_string( 'sn-icon' ); ?>></i>
							<?php
						}
						if ( isset( $settings['icon']['value']['url'] ) ) {
							?>
								<?php Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); ?>
							<?php
						}
						?>
						<!-- <i class="bx bx-right-arrow-alt text-primary fs-3 fw-normal ms-2 mt-1"></i> -->
					<?php endif; ?>
					<?php if ( ! empty( $slide['link']['url'] ) ) : ?>
						</a>
					<?php endif; ?>
			</<?php echo esc_html( $settings['title_tag'] ); ?>>
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
						'href'  => ! empty( $slide['button_link']['url'] ) ? $slide['button_link']['url'] : '#',
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
		$widget->render_arrow_button( $settings, $prev_id, $next_id );
		if ( $settings['show_arrows'] && ( 'top' === $settings['button_position'] ) ) :
			$this->print_arrow_button( $settings );
		endif;
			$widget->skin_loop_header( $settings );
		?>
					<?php foreach ( $settings['slides'] as $slide ) : ?>                                    
						<?php
						$this->print_slide( $slide, $settings, $slide['_id'] );
						?>
					<?php endforeach; ?>					
			</div>
		</div>
		<?php
		if ( $settings['show_arrows'] && ( 'bottom' === $settings['button_position'] ) ) :
			$this->print_arrow_button( $settings );
		endif;
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

				'class' => 'btn btn-prev btn-icon btn-sm me-2',

			]
		);
		$widget->add_render_attribute(
			'next_arrow_button',
			[

				'class' => 'btn btn-next btn-icon btn-sm ms-2',

			]
		);
		$widget->add_render_attribute(
			'arrows_wrapper',
			[

				'class' => [ 'd-flex justify-content-center justify-content-lg-start', $settings['arrows_wrapper_css'] ],

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
