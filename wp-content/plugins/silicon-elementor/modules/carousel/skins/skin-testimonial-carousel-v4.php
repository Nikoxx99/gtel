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
 * Skin Testimonial Carousel V3
 */
class Skin_Testimonial_Carousel_V4 extends Skin_Base {

	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'testimonial-carousel-4';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Skin V4', 'silicon-elementor' );
	}

	/**
	 * Register Control Actions in the skin.
	 *
	 * @return void
	 */
	protected function _register_controls_actions() {
		add_filter( 'silicon-elementor/widget/sn-testimonial-carousel/print_template', [ $this, 'skin_print_template' ], 10, 2 );
		add_action( 'elementor/element/sn-testimonial-carousel/section_additional_options/before_section_end', [ $this, 'update_section_additional_options' ], 10, 1 );
		add_action( 'elementor/element/sn-testimonial-carousel/section_slides/before_section_end', [ $this, 'update_section_slides' ], 10, 1 );
		add_action( 'elementor/element/sn-testimonial-carousel/section_navigation/before_section_end', [ $this, 'update_section_navigation' ], 10, 1 );
	}

	/**
	 * Update section slides
	 *
	 * @param array $widget update slides.
	 */
	public function update_section_slides( $widget ) {

		$this->parent->start_injection(
			[
				'at' => 'before',
				'of' => 'slides_per_view',
			]
		);
		$this->parent->add_control(
			'column_order',
			[
				'type'               => Controls_Manager::SWITCHER,
				'label'              => esc_html__( 'Swiper Order', 'silicon-elementor' ),
				'default'            => 'no',
				'label_off'          => esc_html__( 'Last', 'silicon-elementor' ),
				'label_on'           => esc_html__( 'First', 'silicon-elementor' ),
				'frontend_available' => true,
				'condition' => [
					'_skin' => 'testimonial-carousel-4',
				],
			]
		);
		$this->parent->end_injection();

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => '_skin',
			]
		);
		$this->parent->add_control(
			'skin_style',
			[
				'label'   => esc_html__( 'Style', 'silicon-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'silicon-elementor' ),
					'style-v1' => esc_html__( 'Style v1', 'silicon-elementor' ),
				],
				'condition' => [
					'_skin' => 'testimonial-carousel-4',
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
				'at' => 'before',
				'of' => 'speed',
			]
		);

		$this->parent->add_control(
			'enable_tabs',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Tabs', 'silicon-elementor' ),
				'default'   => 'no',
				'label_off' => esc_html__( 'Hide', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Show', 'silicon-elementor' ),
				'condition' => [
					'_skin' => 'testimonial-carousel-4',
				],
			]
		);
		$this->parent->add_control(
			'show_pager',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Pager', 'silicon-elementor' ),
				'default'   => 'no',
				'label_off' => esc_html__( 'Hide', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Show', 'silicon-elementor' ),
				'condition' => [
					'_skin'      => 'testimonial-carousel-4',
					'pagination' => '',
					'skin_style' => 'default',
				],
			]
		);

		$this->parent->add_control(
			'pager_id',
			[
				'label'       => esc_html__( 'Pager ID', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Additional CSS classes separated by space that you\'d like to apply to the highlighted text', 'silicon-elementor' ),
				'default'     => 'pager',
				'condition'   => [
					'_skin'      => 'testimonial-carousel-4',
					'show_pager' => 'yes',
					'pagination' => '',
					'skin_style' => 'default',
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
				'condition'   => [
					'_skin'      => 'testimonial-carousel-4',
				],
			]
		);

		$this->parent->end_injection();
	}

	/**
	 * Get carousel settings..
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

		if ( 'yes' === $settings['enable_tabs'] ) {
			$swiper_settings['tabs'] = true;
		}

		if ( 'yes' === $settings['show_pager'] && ! empty( $settings['pager_id'] ) ) {
			$swiper_settings['pager'] = '#' . $settings['pager_id'];
		}

		if ( 'yes' === $settings['enable_space_between'] ) {
			$swiper_settings['breakpoints']['1000']['spaceBetween'] = isset( $settings['space_between'] ) ? $settings['space_between'] : 24;
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
			// $swiper_settings['breakpoints']['1500']['slidesPerView'] = isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 3;
			$swiper_settings['breakpoints']['1000']['slidesPerView'] = isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 3;
			$swiper_settings['breakpoints']['500']['slidesPerView']  = isset( $settings['slides_per_view_tablet'] ) ? $settings['slides_per_view_tablet'] : 3;
			$swiper_settings['slidesPerView']                        = isset( $settings['slides_per_view_mobile'] ) ? $settings['slides_per_view_mobile'] : 1;

		}

		if ( 'yes' === $settings['show_arrows'] ) {
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
	 * Render.
	 *
	 * @return void
	 */
	public function render() {

		$widget   = $this->parent;
		$settings = $widget->get_settings_for_display();
		?>
		<div class="row">
		<?php
		$this->print_slider( $settings );
		?>
		</div>
		<?php if ( 'style-v1' === $settings['skin_style'] && 'yes' === $settings['show_arrows'] && 'yes' !== $settings['show_pager'] ) : ?>
						<?php $this->render_arrows( $settings ); ?>
					<?php endif; ?>
		<?php
		$widget->render_script();

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

		$defaults        = array( 'container_class' => 'swiper sn-elementor-main-swiper mx-0 mb-md-n2 mb-xxl-n3' );
		$settings        = array_merge( $defaults, $settings );
		$swiper_settings = $this->get_swiper_carousel_options( $settings );

		$widget->add_render_attribute(
			'slider',
			array(
				'class'               => 'mx-0 mb-md-n2 mb-xxl-n3',
				'data-swiper-options' => esc_attr( json_encode( $this->get_swiper_carousel_options( $settings ) ) ),
			)
		);

		$figure_class = [ 'card', 'h-100', 'position-relative', 'border-0', 'bg-transparent', 'lh-base' ];
		if ( $settings['figure_css'] ) {
			$figure_class[] = $settings['figure_css'];
		}
		$widget->add_render_attribute(
			'figure',
			[
				'class' => $figure_class,
			]
		);
		$card_class = [ 'card', 'border-0', 'shadow-sm' ];
		if ( $settings['card_css'] ) {
			$card_class[] = $settings['card_css'];
		}
		$widget->add_render_attribute(
			'card',
			[
				'class' => $card_class,
			]
		);
		$icons_wrapper_css = [ 'sn-icon-wrap' ];
		if ( $settings['icons_wrapper_css'] ) {
			$icons_wrapper_css[] = $settings['icons_wrapper_css'];
		}
		$widget->add_render_attribute(
			'icon_wrap',
			[
				'class' => $icons_wrapper_css,
			]
		);
		$swiper_order = 'yes' === $settings['column_order'] ? 'order-first' : 'order-last';
		$widget->add_render_attribute(
			'swiper_column',
			[
				'class' => [ 'col-md-8', $swiper_order ],
			]
		);
		?>
		<div <?php $widget->print_render_attribute_string( 'swiper_column' ); ?>>
			<div <?php $widget->print_render_attribute_string( 'card' ); ?>>
			<?php if ( 'default' === $settings['skin_style'] ) : ?>
				<div <?php $widget->print_render_attribute_string( 'icon_wrap' ); ?>>
					<?php
					$this->print_blockquote_v1( $settings, $widget );
					?>
					<?php if ( 'default' === $settings['skin_style'] && 'yes' === $settings['show_arrows'] && 'yes' !== $settings['show_pager'] ) : ?>
						<?php $this->render_arrows( $settings ); ?>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<?php if ( 'style-v1' === $settings['skin_style'] ) : ?>
				<?php $this->print_blockquote_v2( $settings, $widget ); ?>
			<?php endif; ?>
				<?php
				$widget->skin_loop_header( $settings );
				?>
					<?php foreach ( $settings['slides_tabs'] as $slide ) : ?>                                    
						<?php
						$this->print_slide( $slide, $settings, $slide['_id'] );
						?>
					<?php endforeach; ?>
					</div>
					<?php
					if ( $settings['pagination'] ) {
						$widget->render_swiper_pagination( $settings );
					}
					?>
							   
				</div> 
			</div>
			<?php
			$prev_id = ! empty( $settings['prev_arrow_id'] ) ? $settings['prev_arrow_id'] : 'prev-testimonial';
			$next_id = ! empty( $settings['next_arrow_id'] ) ? $settings['next_arrow_id'] : 'next-testimonial';
			if ( 'yes' === $settings['show_pager'] ) :
				?>
				<nav class="pagination d-flex justify-content-center justify-content-md-start">
				<?php
				if ( 'yes' === $settings['show_arrows'] && 'yes' === $settings['show_pager'] ) :
					?>
					<div class="page-item me-2">
						<a id="<?php echo esc_html( $prev_id ); ?>" class="page-link page-prev btn-icon btn-sm">
						<i class="bx bx-chevron-left"></i>
						</a>
					</div>
					<?php
				endif;
				?>
				<?php
				if ( 'yes' === $settings['show_pager'] ) :
					$widget->add_render_attribute(
						'pager-pagination',
						[
							'id'    => $settings['pager_id'],
							'class' => 'list-unstyled d-flex justify-content-center w-auto mb-0',

						]
					)
					?>
					<ul <?php $widget->print_render_attribute_string( 'pager-pagination' ); ?>></ul>
				<?php endif; ?>
				<?php
				if ( 'yes' === $settings['show_arrows'] && 'yes' === $settings['show_pager'] ) :
					?>
					<div class="page-item ms-2">
						<a id="<?php echo esc_html( $next_id ); ?>" class="page-link page-next btn-icon btn-sm">
						<i class="bx bx-chevron-right"></i>
						</a>
					</div>
					<?php
				endif;
				?>
				</nav>
				<?php
			endif;
			?>
		</div>   
		<?php
		$this->print_tabs_slider( $settings );
	}

	/**
	 * Print blockquote icon version 1.
	 *
	 * @param array  $settings the widget settings.
	 * @param string $widget the widget.
	 * @return void
	 */
	public function print_blockquote_v1( array $settings, $widget ) {
		if ( 'yes' === $settings['enable_blockquote_icon'] ) {
			if ( ! isset( $settings['blockquote_icon']['value']['url'] ) ) {
				$widget->add_render_attribute( 'sn-icon', 'class', 'sn-blockquote-icon ' . $settings['blockquote_icon']['value'] );
				?>
				<span class="sn-blockquote btn btn-icon btn-primary btn-lg shadow-primary pe-none">
					<i <?php $widget->print_render_attribute_string( 'sn-icon' ); ?>></i>
				</span>
				<?php
			}
			if ( isset( $settings['blockquote_icon']['value']['url'] ) ) {
				?>
				<span class="sn-blockquote btn btn-icon btn-primary btn-lg shadow-primary pe-none">
					<?php Icons_Manager::render_icon( $settings['blockquote_icon'], [ 'aria-hidden' => 'true' ] ); ?>
				</span>
				<?php
			}
		}
	}

	/**
	 * Print blockquote icon version 2.
	 *
	 * @param array  $settings the widget settings.
	 * @param string $widget the widget.
	 * @return void
	 */
	public function print_blockquote_v2( array $settings, $widget ) {
		if ( 'yes' === $settings['enable_blockquote_icon'] ) {
			if ( ! isset( $settings['blockquote_icon']['value']['url'] ) ) {
				$widget->add_render_attribute( 'sn-icon', 'class', 'sn-blockquote-icon ' . $settings['blockquote_icon']['value'] );
				?>
				<span class="sn-blockquote btn btn-icon btn-primary btn-lg shadow-primary pe-none position-absolute top-0 translate-middle-y">
					<i <?php $widget->print_render_attribute_string( 'sn-icon' ); ?>></i>
				</span>
				<?php
			}
			if ( isset( $settings['blockquote_icon']['value']['url'] ) ) {
				?>
				<span class="sn-blockquote btn btn-icon btn-primary btn-lg shadow-primary pe-none position-absolute top-0 translate-middle-y">
					<?php Icons_Manager::render_icon( $settings['blockquote_icon'], [ 'aria-hidden' => 'true' ] ); ?>
				</span>
				<?php
			}
		}
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
		$id_int = substr( $this->parent->get_id_int(), 0, 3 );
		$id     = 'author-' . $id_int . $slide['_id'];
		$widget->add_render_attribute(
			'carousel_slide_css-' . $element_key,
			[
				'data-swiper-tab' => '#' . $id,
			]
		);
		$widget->skin_slide_start( $settings, $element_key );
		$widget->add_render_attribute( 'blockquote-text-' . $element_key, 'class', $settings['blockquote_text_css'] );
		?>
			<figure <?php $widget->print_render_attribute_string( 'figure' ); ?>>
				<blockquote class="card-body p-0">
					<p class="fs-lg mb-0"><?php echo esc_html( $slide['blockquote'] ); ?></p>
				</blockquote>
				<?php
				$avatar_css = $settings['avatar_css'] ? 'sn-avatar ' . $settings['avatar_css'] : 'sn-avatar rounded-circle';
				$widget->add_render_attribute(
					'avatar-' . $element_key,
					[
						'class' => $avatar_css,
						'src'   => $slide['responsive_avatar']['url'],
						'width' => $settings['width']['size'],
						'alt'   => Control_Media::get_image_alt( $slide['responsive_avatar'] ),
					]
				);
				$logo_css = [ 'd-block mt-2 ms-5 mt-sm-0 ms-sm-0' ];
				$widget->add_render_attribute(
					'logo-' . $element_key,
					[
						'class' => $logo_css,
						'src'   => $slide['logo']['url'],
						'width' => '160',
						'alt'   => Control_Media::get_image_alt( $slide['logo'] ),
					]
				);
				$name_css = [ 'sn-name', 'lh-base', 'mb-0' ];
				if ( $settings['name_css'] ) {
					$name_css[] = $settings['name_css'];
				}
				$widget->add_render_attribute(
					'name-' . $element_key,
					[
						'class' => $name_css,
					]
				);
				$role_css = [ 'sn-role', 'text-muted' ];
				if ( $settings['role_css'] ) {
					$role_css[] = $settings['role_css'];
				}
				$widget->add_render_attribute(
					'role-' . $element_key,
					[
						'class' => $role_css,
					]
				);
				?>
				<?php
				if ( 'default' === $settings['skin_style'] ) {
					$this->print_author_details_v1( $slide, $settings, $element_key, $widget );
				}
				if ( 'style-v1' === $settings['skin_style'] ) {
					$this->print_author_details_v2( $slide, $settings, $element_key, $widget );
				}
				?>
			</figure>
		</div>
		<?php
	}

	/**
	 * Print author details version 1.
	 *
	 * @param array  $slide the slides settings.
	 * @param array  $settings the widget settings.
	 * @param string $element_key the element_key slider id.
	 * @param string $widget the widget.
	 * @return void
	 */
	public function print_author_details_v1( array $slide, array $settings, $element_key, $widget ) {
		?>
		<figcaption class="card-footer border-0 d-sm-flex d-md-none w-100 pb-2">
			<div class="d-flex align-items-center border-end-sm pe-sm-4 me-sm-2">
				<img <?php $widget->print_render_attribute_string( 'avatar-' . $element_key ); ?>>
				<div class="ps-3">
					<<?php echo esc_html( $settings['name_tag'] ); ?> <?php $widget->print_render_attribute_string( 'name-' . $element_key ); ?>><?php echo esc_html( $slide['name'] ); ?></<?php echo esc_html( $settings['name_tag'] ); ?>>
					<span <?php $widget->print_render_attribute_string( 'role-' . $element_key ); ?>><?php echo wp_kses_post( $slide['role'] ); ?></span>
				</div>
			</div>
			<img <?php $widget->print_render_attribute_string( 'logo-' . $element_key ); ?>>
		</figcaption>
		<?php
	}

	/**
	 * Print author details version 2.
	 *
	 * @param array  $slide the slides settings.
	 * @param array  $settings the widget settings.
	 * @param string $element_key the element_key slider id.
	 * @param string $widget the widget.
	 * @return void
	 */
	public function print_author_details_v2( array $slide, array $settings, $element_key, $widget ) {
		?>
		<figcaption class="card-footer border-0 d-sm-flex w-100 pb-2">
			<div class="d-flex align-items-center border-end-sm pe-sm-4 me-sm-2">
				<img <?php $widget->print_render_attribute_string( 'avatar-' . $element_key ); ?>>
				<div class="ps-3 ps-md-0">
					<<?php echo esc_html( $settings['name_tag'] ); ?> <?php $widget->print_render_attribute_string( 'name-' . $element_key ); ?>><?php echo esc_html( $slide['name'] ); ?></<?php echo esc_html( $settings['name_tag'] ); ?>>
					<span <?php $widget->print_render_attribute_string( 'role-' . $element_key ); ?>><?php echo wp_kses_post( $slide['role'] ); ?></span>
				</div>
			</div>
			<img <?php $widget->print_render_attribute_string( 'logo-' . $element_key ); ?>>
		</figcaption>
		<?php
	}

	/**
	 * Print the slide.
	 *
	 * @param array $settings the widget settings.
	 * @return void
	 */
	public function print_tabs_slider( array $settings ) {
		$widget = $this->parent;
		$count  = 1;
		if ( null === $settings ) {
			$settings = $widget->get_settings_for_display();
		}
		$tab_order    = 'yes' === $settings['column_order'] ? 'order-last' : 'order-first';
		$widget->add_render_attribute(
			'tabs_column',
			[
				'class' => [ 'col-md-4 d-none d-md-block', $tab_order ],
			]
		);
		?>
		<div <?php $widget->print_render_attribute_string( 'tabs_column' ); ?>>
			<div class="swiper-tabs">
			<?php
			foreach ( $settings['slides_tabs'] as $slide ) :
				$this->print_tabs_slide( $slide, $settings, $slide['_id'], $count );
				$count++;
			endforeach;
			?>
			</div>
		</div>
		<?php
	}

	/**
	 * Print the slide.
	 *
	 * @param array  $slide the slides settings.
	 * @param array  $settings the widget settings.
	 * @param string $element_key the element_key slider id.
	 * @param string $count the count loop count.
	 * @return void
	 */
	public function print_tabs_slide( array $slide, array $settings, $element_key, $count ) {
		$widget          = $this->parent;
		$id_int          = substr( $this->parent->get_id_int(), 0, 3 );
		$id              = 'author-' . $id_int . $slide['_id'];
		$content_wrapper = [ 'swiper-tab', 'card bg-transparent border-0' ];
		if ( 1 === $count ) {
			$content_wrapper[] = 'active';
		}
		$widget->add_render_attribute(
			'card-' . $element_key,
			[
				'class' => $content_wrapper,
				'id'    => $id,
			]
		);

		$card_body_css = [ 'card-body p-0 rounded-3 bg-size-cover bg-repeat-0 bg-position-top-center' ];
		$widget->add_render_attribute(
			'card-body-' . $element_key,
			[
				'class' => $card_body_css,
				'style' => 'background-image: url(' . $slide['avatar']['url'] . ');',

			]
		);

		$widget->add_render_attribute(
			'tab-logo-' . $element_key,
			[
				'src'   => $slide['logo']['url'],
				'class' => 'd-none d-xl-block',
				'width' => '160',
				'alt'   => Control_Media::get_image_alt( $slide['logo'] ),

			]
		);
		?>
		<div <?php $widget->print_render_attribute_string( 'card-' . $element_key ); ?>>
			<div <?php $widget->print_render_attribute_string( 'card-body-' . $element_key ); ?>></div>
			<?php if ( 'default' === $settings['skin_style'] ) : ?>
			<div class="card-footer d-flex w-100 border-0 pb-0">
				<img <?php $widget->print_render_attribute_string( 'tab-logo-' . $element_key ); ?>>
				<div class="border-start-xl ps-xl-4 ms-xl-2">
					<<?php echo esc_html( $settings['name_tag'] ); ?> <?php $widget->print_render_attribute_string( 'name-' . $element_key ); ?>><?php echo esc_html( $slide['name'] ); ?></<?php echo esc_html( $settings['name_tag'] ); ?>>
					<span <?php $widget->print_render_attribute_string( 'role-' . $element_key ); ?>><?php echo wp_kses_post( $slide['role'] ); ?></span>
				</div>
			</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Skin print template.
	 *
	 * @param array $settings widget controls.
	 * @return void
	 */
	public function render_arrows( array $settings ) {
		$widget             = $this->parent;
		$arrows_wrapper_css = [ 'd-flex' ];
		if ( $settings['arrows_wrapper_css'] ) {
			$arrows_wrapper_css[] = $settings['arrows_wrapper_css'];
		}
		$widget->add_render_attribute( 'arrows_wrapper_css', 'class', $arrows_wrapper_css );
		?>

		<div <?php $widget->print_render_attribute_string( 'arrows_wrapper_css' ); ?>>
			<?php
			$prev_id = ! empty( $settings['prev_arrow_id'] ) ? $settings['prev_arrow_id'] : '';
			$next_id = ! empty( $settings['next_arrow_id'] ) ? $settings['next_arrow_id'] : '';
			$widget->render_arrow_button( $settings, $prev_id, $next_id );
			if ( $settings['show_arrows'] ) {
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
				$widget->print_prev_button();
				$widget->print_next_button();
			}
			?>
		</div>
		<?php
	}

	/**
	 * Skin print template.
	 *
	 * @param array $content the content.
	 * @param array $testimonial_carousel widget name.
	 * @return string
	 */
	public function skin_print_template( $content, $testimonial_carousel ) {

		if ( 'sn-testimonial-carousel' == $testimonial_carousel->get_name() ) {
			return '';
		}

		return $content;
	}

}
