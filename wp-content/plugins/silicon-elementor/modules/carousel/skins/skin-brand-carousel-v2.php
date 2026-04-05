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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Skin Brand Carousel
 */
class Skin_Brand_Carousel_V2 extends Skin_Base {

	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'brand-carousel-v2';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Brand Carousel V2', 'silicon-elementor' );
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
		add_action( 'elementor/element/sn-brand-carousel/section_slides/before_section_end', [ $this, 'update_section_slides' ], 10, 1 );

	}

	/**
	 * Update section slides options
	 *
	 * @param array $widget section slides options.
	 */
	public function update_section_slides( $widget ) {

		$update_control_ids = [ 'style', 'slides' ];

		foreach ( $update_control_ids as $update_control_id ) {
			$widget->update_control(
				$update_control_id,
				[
					'condition' => [
						'_skin!' => [ 'brand-carousel-v2' ],
					],
				]
			);
		}

		$repeater = new Repeater();

		$this->add_repeater_controls( $repeater );

		$widget->start_injection(
			[
				'at' => 'after',
				'of' => 'slides',
			]
		);

		$widget->add_control(
			'image_slides',
			[
				'label'     => esc_html__( 'Image Slides', 'silicon-elementor' ),
				'type'      => Controls_Manager::REPEATER,
				'fields'    => $repeater->get_controls(),
				'default'   => $this->get_repeater_defaults(),
				'condition' => [
					'_skin' => [ 'brand-carousel-v2' ],
				],
			]
		);

		$widget->end_injection();

	}

	/**
	 * Register repeater controls for this widget.
	 *
	 * @param Repeater $repeater repeater control arguments.
	 * @return void
	 */
	protected function add_repeater_controls( Repeater $repeater ) {

		$repeater->add_control(
			'link',
			[
				'label'       => __( 'Link', 'silicon-elementor' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'silicon-elementor' ),
			]
		);

		$repeater->add_control(
			'normal_image',
			[
				'label' => esc_html__( 'Image', 'silicon-elementor' ),
				'type'  => Controls_Manager::MEDIA,
			]
		);

		$repeater->add_control(
			'normal_image_width',
			[
				'label'   => esc_html__( 'Image Width', 'silicon-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => [
					'px' => [
						'min' => 1,
						'max' => 1200,
					],
				],
				'default' => [
					'size' => 196,
				],
			]
		);

		$repeater->add_control(
			'light_hover_image',
			[
				'label' => esc_html__( 'Light Mode Hover Image', 'silicon-elementor' ),
				'type'  => Controls_Manager::MEDIA,
			]
		);

		$repeater->add_control(
			'light_hover_image_width',
			[
				'label'   => esc_html__( 'Light Image Width', 'silicon-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => [
					'px' => [
						'min' => 1,
						'max' => 1200,
					],
				],
				'default' => [
					'size' => 196,
				],
			]
		);

		$repeater->add_control(
			'dark_hover_image',
			[
				'label' => esc_html__( 'Dark Mode Hover Image', 'silicon-elementor' ),
				'type'  => Controls_Manager::MEDIA,
			]
		);

		$repeater->add_control(
			'dark_hover_image_width',
			[
				'label'   => esc_html__( 'Dark Image Width', 'silicon-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => [
					'px' => [
						'min' => 1,
						'max' => 1200,
					],
				],
				'default' => [
					'size' => 196,
				],
			]
		);

	}

	/**
	 * Slider defaults.
	 *
	 * @return string
	 */
	protected function get_repeater_defaults() {
		$placeholder_image_src = Utils::get_placeholder_image_src();

		return [
			[
				'normal_image'      => [
					'url' => $placeholder_image_src,
				],
				'light_hover_image' => [
					'url' => $placeholder_image_src,
				],
				'dark_hover_image'  => [
					'url' => $placeholder_image_src,
				],
			],
			[
				'normal_image'      => [
					'url' => $placeholder_image_src,
				],
				'light_hover_image' => [
					'url' => $placeholder_image_src,
				],
				'dark_hover_image'  => [
					'url' => $placeholder_image_src,
				],
			],
			[
				'normal_image'      => [
					'url' => $placeholder_image_src,
				],
				'light_hover_image' => [
					'url' => $placeholder_image_src,
				],
				'dark_hover_image'  => [
					'url' => $placeholder_image_src,
				],
			],
			[
				'normal_image'      => [
					'url' => $placeholder_image_src,
				],
				'light_hover_image' => [
					'url' => $placeholder_image_src,
				],
				'dark_hover_image'  => [
					'url' => $placeholder_image_src,
				],
			],
			[
				'normal_image'      => [
					'url' => $placeholder_image_src,
				],
				'light_hover_image' => [
					'url' => $placeholder_image_src,
				],
				'dark_hover_image'  => [
					'url' => $placeholder_image_src,
				],
			],
			[
				'normal_image'      => [
					'url' => $placeholder_image_src,
				],
				'light_hover_image' => [
					'url' => $placeholder_image_src,
				],
				'dark_hover_image'  => [
					'url' => $placeholder_image_src,
				],
			],
		];
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
		if ( 'yes' === $settings['enable_space_between'] ) {
			$swiper_settings['spaceBetween']                        = isset( $settings['space_between_mobile'] ) ? $settings['space_between_mobile'] : 0;
			$swiper_settings['breakpoints']['500']['spaceBetween']  = isset( $settings['space_between'] ) ? $settings['space_between'] : 8;
			$swiper_settings['breakpoints']['650']['spaceBetween']  = isset( $settings['space_between_tablet'] ) ? $settings['space_between_tablet'] : 8;
			$swiper_settings['breakpoints']['900']['spaceBetween']  = isset( $settings['space_between'] ) ? $settings['space_between'] : 8;
			$swiper_settings['breakpoints']['1100']['spaceBetween'] = isset( $settings['space_between'] ) ? $settings['space_between'] : 8;
		}

		if ( 'slide' === $settings['effect'] ) {
			$swiper_settings['breakpoints']['500']['slidesPerView']  = ! empty( $settings['slides_per_view_mobile'] ) ? $settings['slides_per_view_mobile'] : 3;
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
	 * Update section additional options
	 *
	 * @param array $widget section additional options.
	 */
	public function update_section_additional_options( $widget ) {

		$widget->update_control(
			'center_slides',
			[

				'condition' => [
					'_skin!' => array( 'brand-carousel', 'brand-carousel-v2' ),
				],
			]
		);

		$widget->update_control(
			'show_arrows',
			[
				'label'     => esc_html__( 'Enable Arrow ID', 'silicon-elementor' ),
				'default'   => 'no',
				'condition' => [
					'_skin!' => array( 'brand-carousel-v2' ),
				],

			]
		);

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
				<?php foreach ( $settings['image_slides'] as $slide ) : ?>                                    
					<?php
					$this->print_image_slide( $slide, $settings, $slide['_id'], $count );
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
	 * Get slider settings
	 *
	 * @param array $slide the slides settings.
	 * @param array $settings the widget settings.
	 * @param array $element_key the element_key slider id.
	 * @param array $count the widget settings.
	 * @return void
	 */
	public function print_image_slide( array $slide, array $settings, $element_key, $count ) {
		$widget = $this->parent;
		if ( ! empty( $slide['link']['url'] ) ) {
			$link_tag = 'a';
		} else {
			$link_tag = 'div';
		}
		$widget->add_link_attributes(
			'images_link-' . $element_key,
			$slide['link']
		);
		$widget->add_render_attribute(
			'images_link-' . $element_key,
			[
				'class' => 'swap-image',
			]
		);
		$widget->add_render_attribute(
			'light_hover_image-' . $element_key,
			[
				'src'   => $slide['light_hover_image']['url'],
				'alt'   => Control_Media::get_image_alt( $slide['light_hover_image'] ),
				'width' => $slide['light_hover_image_width']['size'],
			]
		);
		$widget->add_render_attribute(
			'hover_dark_image-' . $element_key,
			[
				'src'   => $slide['dark_hover_image']['url'],
				'alt'   => Control_Media::get_image_alt( $slide['dark_hover_image'] ),
				'width' => $slide['dark_hover_image_width']['size'],
			]
		);

		$widget->add_render_attribute(
			'normal_image-' . $element_key,
			[
				'class' => 'swap-from sn-normal',
				'src'   => $slide['normal_image']['url'],
				'alt'   => Control_Media::get_image_alt( $slide['normal_image'] ),
				'width' => $slide['normal_image_width']['size'],
			]
		);
		$widget->skin_slide_start( $settings, $element_key );
		?>
			<<?php echo esc_html( $link_tag ); ?> <?php $widget->print_render_attribute_string( 'images_link-' . $element_key ); ?>>
			<?php
			if ( ! empty( $slide['normal_image']['url'] ) ) {
				?>
				<img <?php $widget->print_render_attribute_string( 'normal_image-' . $element_key ); ?>>
				<?php
			}
			if ( ! empty( $slide['light_hover_image']['url'] ) && ! empty( $slide['dark_hover_image']['url'] ) ) {
				?>
				<div class="swap-to">
				<?php
				if ( ! empty( $slide['light_hover_image']['url'] ) ) {
					$widget->add_render_attribute(
						'light_hover_image-' . $element_key,
						[
							'class' => 'light-mode-img sn-light',
						]
					);
					?>
					<img <?php $widget->print_render_attribute_string( 'light_hover_image-' . $element_key ); ?>>
					<?php
				}
				if ( ! empty( $slide['dark_hover_image']['url'] ) ) {
					$widget->add_render_attribute(
						'hover_dark_image-' . $element_key,
						[
							'class' => 'dark-mode-img sn-dark',
						]
					);
					?>
					<img <?php $widget->print_render_attribute_string( 'hover_dark_image-' . $element_key ); ?>>
					<?php
				}
				?>
				</div>
				<?php
			} else {
				if ( ! empty( $slide['light_hover_image']['url'] ) ) {
					$widget->add_render_attribute(
						'light_hover_image-' . $element_key,
						[
							'class' => 'swap-to sn-light',
						]
					);
					?>
					<img <?php $widget->print_render_attribute_string( 'light_hover_image-' . $element_key ); ?>>
					<?php
				}
				if ( ! empty( $slide['dark_hover_image']['url'] ) ) {
					$widget->add_render_attribute(
						'hover_dark_image-' . $element_key,
						[
							'class' => 'swap-to sn-dark',
						]
					);
					?>
					<img <?php $widget->print_render_attribute_string( 'hover_dark_image-' . $element_key ); ?>>
					<?php
				}
			}
			?>
			</<?php echo esc_html( $link_tag ); ?>>
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
		// $slides_count = count( $settings['slides'] );
		?>
		
		  
		  <?php $this->print_slide( $settings ); ?>
		 
		  
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



