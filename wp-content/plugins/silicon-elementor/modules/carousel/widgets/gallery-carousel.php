<?php
namespace SiliconElementor\Modules\Carousel\Widgets;

use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Plugin;
use SiliconElementor\Modules\Carousel\Skins;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Content Carousel
 */
class Gallery_Carousel extends Base {

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'si-gallery-carousel';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Gallery Carousel', 'silicon-elementor' );
	}

	/**
	 * Get the icon for the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-carousel';
	}

	/**
	 * Get the keywords associated with the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'gallery', 'carousel', 'image', 'content', 'slider' ];
	}

	/**
	 * Get the group for this widget.
	 *
	 * @return string
	 */
	public function get_group_name() {
		return 'carousel';
	}

	/**
	 * Register controls for this widget.
	 *
	 * @return void
	 */
	protected function register_controls() {
		parent::register_controls();

		// Remove controls.
		$this->remove_control( 'slides' );
		$this->remove_control( 'pause_on_hover' );

		// Inject Controls.
		$this->start_injection(
			[
				'at' => 'before',
				'of' => 'effect',
			]
		);

		$this->add_control(
			'gallery',
			[
				'label'      => esc_html__( 'Add Images', 'silicon-elementor' ),
				'type'       => \Elementor\Controls_Manager::GALLERY,
				'show_label' => false,
				'default'    => [],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'thumbnail',
				'exclude'   => [ 'custom' ],
				'separator' => 'none',
			]
		);

		$this->add_control(
			'link_to',
			[
				'label'   => esc_html__( 'Link', 'silicon-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none'   => esc_html__( 'None', 'silicon-elementor' ),
					'file'   => esc_html__( 'Media File', 'silicon-elementor' ),
					'custom' => esc_html__( 'Custom URL', 'silicon-elementor' ),
				],
			]
		);

		$this->add_control(
			'link',
			[
				'label'       => esc_html__( 'Link', 'silicon-elementor' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'silicon-elementor' ),
				'condition'   => [
					'link_to' => 'custom',
				],
				'show_label'  => false,
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'open_lightbox',
			[
				'label'     => esc_html__( 'Lightbox', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'default',
				'options'   => [
					'default' => esc_html__( 'Default', 'silicon-elementor' ),
					'yes'     => esc_html__( 'Yes', 'silicon-elementor' ),
					'no'      => esc_html__( 'No', 'silicon-elementor' ),
				],
				'condition' => [
					'link_to' => 'file',
				],
			]
		);

		$this->add_control(
			'coverflow_effect',
			[
				'label'              => esc_html__( 'Coverflow Effect', 'silicon-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'no',
				'frontend_available' => true,
			]
		);

		$this->end_injection();

		// Inject Autoplay direction controls.
		$this->start_injection(
			[
				'at' => 'after',
				'of' => 'autoplay_speed',
			]
		);

		$this->add_control(
			'reverse_autoplay',
			[
				'label'              => esc_html__( 'Autoplay Direction', 'silicon-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'no',
				'label_on'           => 'RTL',
				'label_off'          => 'Default',
				'frontend_available' => true,
				'condition'          => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'enable_grabCursor',
			[
				'label'              => esc_html__( 'Enable Grab Cursor', 'silicon-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'no',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'enable_freeMode',
			[
				'label'              => esc_html__( 'Enable Freemode', 'silicon-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'no',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'disable_freeModeMomentum',
			[
				'label'              => esc_html__( 'Disable Freemode Momentum', 'silicon-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'no',
				'label_on'           => 'Disable',
				'label_off'          => 'Enable',
				'frontend_available' => true,
				'condition'          => [
					'enable_freeMode' => 'yes',
				],
			]
		);

		$this->end_injection();

		// Inject Arrow Button controls.
		$this->start_injection(
			[
				'at' => 'after',
				'of' => 'arrows_hover_color',
			]
		);

		$this->add_control(
			'prev_button_css',
			[
				'label'       => esc_html__( 'Previous Button CSS', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'CSS Classes added to previous <button>', 'silicon-elementor' ),
				'default'     => 'btn btn-prev btn-icon position-relative top-0 mt-0 ms-0 me-2 start-0',
				'condition'   => [
					'show_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'next_button_css',
			[
				'label'       => esc_html__( 'Next Button CSS', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'CSS Classes added to next <button>', 'silicon-elementor' ),
				'default'     => 'btn btn-next btn-icon position-relative top-0 mt-0 ms-2 me-0 end-0',
				'condition'   => [
					'show_arrows' => 'yes',
				],
			]
		);

		$this->end_injection();

		// Inject Controls in Section Navigation.
		$this->start_injection(
			[
				'at' => 'after',
				'of' => 'section_navigation',
			]
		);

		$this->add_control(
			'navigation_wrapper_css',
			[
				'label'       => esc_html__( 'Navigation Wrapper CSS', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'CSS Classes added to navigation wrapper', 'silicon-elementor' ),
				'default'     => 'd-flex justify-content-center align-items-center pb-3 pt-4 mt-3',
				'conditions'  => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'show_arrows',
							'operator' => '===',
							'value'    => 'yes',
						],
						[
							'name'     => 'pagination',
							'operator' => '!==',
							'value'    => '',
						],
					],
				],
			]
		);

		$this->end_injection();

		$this->start_injection(
			[
				'at' => 'after',
				'of' => 'pagination_css',
			]
		);

		$this->add_responsive_control(
			'gallery_dots_spacing',
			array(
				'type'      => Controls_Manager::SLIDER,
				'label'     => esc_html__( 'Dots Spacing', 'silicon-elementor' ),
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'condition'   => [
					'pagination' => 'bullets',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'margin: 0px {{SIZE}}{{UNIT}};',
				],
			)
		);

		$this->end_injection();

		// Add New Section Controls.
		$this->start_controls_section(
			'section_img_options',
			[
				'label' => esc_html__( 'Image', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'image_class',
			[
				'label'       => esc_html__( 'Image Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'title'       => esc_html__( 'Add your custom class for <img> tag  without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'     => 'rounded-3',
				'label_block' => true,

			]
		);

		$this->add_responsive_control(
			'img_width',
			array(
				'type'      => Controls_Manager::SLIDER,
				'label'     => esc_html__( 'Image Width', 'silicon-elementor' ),
				'range'     => [
					'px' => [
						'min' => 100,
						'max' => 1140,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-slide img' => 'width: {{SIZE}}{{UNIT}};',
				],
			)
		);

		$this->add_responsive_control(
			'img_height',
			array(
				'type'      => Controls_Manager::SLIDER,
				'label'     => esc_html__( 'Image Height', 'silicon-elementor' ),
				'range'     => [
					'px' => [
						'min' => 100,
						'max' => 1140,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-slide img' => 'height: {{SIZE}}{{UNIT}};',
				],
			)
		);

		$this->end_controls_section();

		// Update Spacebetween Defaults.

		$this->update_control(
			'space_between',
			array(
				'default'        => 1,
				'tablet_default' => 1,
				'mobile_default' => 1,
			)
		);

		$this->update_control(
			'effect',
			array(
				'condition' => [
					'coverflow_effect!' => 'yes',
				],
			)
		);

		$this->update_control(
			'slides_per_view',
			array(
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'coverflow_effect',
							'operator' => '===',
							'value'    => 'yes',
						],
						[
							'name'     => 'effect',
							'operator' => '===',
							'value'    => 'slide',
						],
					],
				],
				'default'        => 9,
				'tablet_default' => 9,
				'mobile_default' => 9,
			)
		);

		$this->update_control(
			'pagination',
			array(
				'default' => '',
			)
		);

		$this->update_control(
			'pagination_css',
			array(
				'default' => 'text-light opacity-70 fs-sm fw-medium position-relative top-0 w-auto',
			)
		);

		$this->update_control(
			'show_arrows',
			array(
				'default' => 'no',
			)
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

		$swiper_settings = [];
		$prev_id         = 'prev-' . $this->get_id();
		$next_id         = 'next-' . $this->get_id();

		$prev_id = ! empty( $settings['prev_arrow_id'] ) ? $settings['prev_arrow_id'] : $prev_id;
		$next_id = ! empty( $settings['next_arrow_id'] ) ? $settings['next_arrow_id'] : $next_id;
		if ( 'yes' === $settings['show_arrows'] ) {
			$swiper_settings['navigation'] = array(
				'prevEl' => '#' . $prev_id,
				'nextEl' => '#' . $next_id,

			);
		}

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

		if ( $settings['center_slides'] ) {
			$swiper_settings['centeredSlides'] = 'true';
		}
		if ( 'fade' === $settings['effect'] ) {
			$swiper_settings['effect']                  = 'fade';
			$swiper_settings['fadeEffect']['crossFade'] = true;
		}
		if ( 'slide' === $settings['effect'] || 'yes' === $settings['coverflow_effect'] ) {
			$swiper_settings['breakpoints']['1440']['slidesPerView'] = isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 3;
			$swiper_settings['breakpoints']['1024']['slidesPerView'] = isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 3;
			$swiper_settings['breakpoints']['768']['slidesPerView']  = isset( $settings['slides_per_view_tablet'] ) ? $settings['slides_per_view_tablet'] : 3;
			$swiper_settings['breakpoints']['0']['slidesPerView']    = isset( $settings['slides_per_view_mobile'] ) ? $settings['slides_per_view_mobile'] : 1;

		}

		if ( 'yes' === $settings['enable_space_between'] && ! empty( $settings['space_between'] ) ) {
			$swiper_settings['breakpoints']['1440']['spaceBetween'] = $settings['space_between'];
			$swiper_settings['breakpoints']['1024']['spaceBetween'] = $settings['space_between'];
		}

		if ( 'yes' === $settings['enable_space_between'] && ! empty( $settings['space_between_tablet'] ) ) {
			$swiper_settings['breakpoints']['768']['spaceBetween'] = $settings['space_between_tablet'];
		}

		if ( 'yes' === $settings['enable_space_between'] && ! empty( $settings['space_between_mobile'] ) ) {
			$swiper_settings['breakpoints']['0']['spaceBetween'] = $settings['space_between_mobile'];
		}

		if ( $settings['loop'] ) {
			$swiper_settings['loop'] = 'true';
		}
		if ( $settings['autoplay'] ) {
			$swiper_settings['autoplay']['delay'] = (int) $settings['autoplay_speed'] ? $settings['autoplay_speed'] : 0;
			$swiper_settings['autoplay']['disableOnInteraction'] = false;
		}

		if ( $settings['speed'] ) {
			$swiper_settings['speed'] = $settings['speed'];
		}

		if ( 'yes' === $settings['enable_grabCursor'] ) {
			$swiper_settings['grabCursor'] = true;
		}

		if ( 'yes' === $settings['enable_freeMode'] ) {
			$swiper_settings['freeMode'] = true;
			if ( 'yes' === $settings['disable_freeModeMomentum'] ) {
				$swiper_settings['freeModeMomentum'] = false;
			}
		}
		if ( 'yes' === $settings['coverflow_effect'] ) {
			$swiper_settings['effect']                          = 'coverflow';
			$swiper_settings['coverflowEffect']['rotate']       = 0;
			$swiper_settings['coverflowEffect']['stretch']      = 0;
			$swiper_settings['coverflowEffect']['depth']        = 560;
			$swiper_settings['coverflowEffect']['modifier']     = 1;
			$swiper_settings['coverflowEffect']['slideShadows'] = true;

		}

		return $swiper_settings;
	}

	/**
	 * Slider defaults.
	 *
	 * @return string
	 */
	protected function get_repeater_defaults() {

		return array();
	}

	/**
	 * Register repeater controls for this widget.
	 *
	 * @param Repeater $repeater repeater control arguments.
	 * @return void
	 */
	protected function add_repeater_controls( Repeater $repeater ) {}

	/**
	 * Render.
	 *
	 * @return void
	 */
	public function render() {
		$settings = $this->get_settings_for_display();
		$this->print_slider( $settings );
		$this->render_script();
	}

	/**
	 * Add Slider setting to .
	 *
	 * @param array $settings swiper slider.
	 *
	 * @return void
	 */
	protected function print_slider( array $settings = null ) {
		if ( null === $settings ) {
			$settings = $this->get_settings_for_display();
		}

		$defaults        = array( 'container_class' => 'swiper sn-elementor-main-swiper mx-0' );
		$settings        = array_merge( $defaults, $settings );
		$swiper_settings = $this->get_swiper_carousel_options( $settings );

		if ( 'yes' === $settings['reverse_autoplay'] ) {
			$this->add_render_attribute(
				'slider',
				array(
					'dir'               => 'rtl',
				)
			);
		}

		$this->add_render_attribute(
			'slider',
			array(
				'class'               => 'mx-0',
				'data-swiper-options' => esc_attr( wp_json_encode( $this->get_swiper_carousel_options( $settings ) ) ),
			)
		);
			$this->skin_loop_header( $settings );

		if ( ! empty( $settings['gallery'] ) ) {
			foreach ( $settings['gallery'] as $key => $image ) {
				$this->print_slide( $image, $settings, 'key-' . $key );
			}
		}
		?>
			</div>
			<?php
			if ( ! empty( $settings['gallery'] ) ) {
				$this->print_swiper_navigation( $settings );
			}
			?>
		</div>
		<?php
	}

	/**
	 * Display Carousel.
	 *
	 * @param array  $slide repeater single control arguments.
	 * @param array  $settings control arguments.
	 * @param string $element_key slider id argument.
	 * @return void
	 */
	protected function print_slide( array $slide, array $settings, $element_key ) {

		$attr = array(
			'class' => $settings['image_class'],
		);
		if ( ! isset( $slide['id'] ) && isset( $slide['url'] ) ) {
			$slide['id'] = attachment_url_to_postid( $slide['url'] );
		}

		$image_html = wp_get_attachment_image( $slide['id'], $settings['thumbnail_size'], false, $attr );

		$link_tag = '';

		$link = $this->get_link_url( $slide, $settings );

		if ( $link ) {
			$link_key = 'link_' . $element_key;

			$this->add_lightbox_data_attributes( $link_key, $slide['id'], $settings['open_lightbox'], $this->get_id() );

			if ( Plugin::$instance->editor->is_edit_mode() ) {
				$this->add_render_attribute(
					$link_key,
					[
						'class' => 'elementor-clickable',
					]
				);
			}

			$this->add_link_attributes( $link_key, $link );

			$link_tag = '<a ' . $this->get_render_attribute_string( $link_key ) . '>';
		}

		$slide_html = '<div class="swiper-slide">' . $link_tag . '<figure class="swiper-slide-inner lh-base">' . $image_html;

		$slide_html .= '</figure>';

		if ( $link ) {
			$slide_html .= '</a>';
		}

		$slide_html .= '</div>';

		echo wp_kses_post( $slide_html );

	}

	/**
	 * Retrieve image carousel link URL.
	 *
	 * @param array  $attachment attachment.
	 * @param object $instance instance.
	 *
	 * @return array|string|false An array/string containing the attachment URL, or false if no link.
	 */
	public function get_link_url( $attachment, $instance ) {
		if ( 'none' === $instance['link_to'] ) {
			return false;
		}

		if ( 'custom' === $instance['link_to'] ) {
			if ( empty( $instance['link']['url'] ) ) {
				return false;
			}

			return $instance['link'];
		}

		return [
			'url' => wp_get_attachment_url( $attachment['id'] ),
		];
	}

	/**
	 * Add Slider setting to .
	 *
	 * @param array $settings swiper slider.
	 *
	 * @return void
	 */
	protected function print_swiper_navigation( array $settings = null ) {
		$this->add_render_attribute(
			'prev_arrow_button',
			[
				'class' => $settings['prev_button_css'],
				'style' => 'transform: none;',
			]
		);
		$this->add_render_attribute(
			'next_arrow_button',
			[
				'class' => $settings['next_button_css'],
				'style' => 'transform: none;',
			]
		);
		$prev_id = 'prev-' . $this->get_id();
		$next_id = 'next-' . $this->get_id();
		$this->render_arrow_button( $settings, $prev_id, $next_id );
		?>
		<div class="<?php echo esc_attr( $settings['navigation_wrapper_css'] ); ?>">
			<?php
			if ( 'yes' === $settings['show_arrows'] ) {
				$this->print_prev_button();
			}

			$this->add_render_attribute(
				'swiper_pagination',
				[
					'style' => 'min-width: 70px;',
				]
			);
			$this->render_swiper_pagination( $settings );
			if ( 'yes' === $settings['show_arrows'] ) {
				$this->print_next_button();
			}

			?>
		</div>
		<?php
	}

}
