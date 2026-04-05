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
 * Skin Testimonial Carousel V2
 */
class Skin_Testimonial_Carousel_V2 extends Skin_Base {

	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'testimonial-carousel-2';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Skin V2', 'silicon-elementor' );
	}

	/**
	 * Register Control Actions in the skin.
	 *
	 * @return void
	 */
	protected function _register_controls_actions() {
		add_filter( 'silicon-elementor/widget/sn-testimonial-carousel/print_template', [ $this, 'skin_print_template' ], 10, 2 );
		add_action( 'elementor/element/sn-testimonial-carousel/section_slides/before_section_end', [ $this, 'update_section_slides' ], 10, 1 );
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
					// '_skin!' => [ 'testimonial-carousel-2', 'testimonial-carousel-3' ],
					'_skin' => [ 'testimonial-carousel-1' ],
				],
			]
		);
	}

	/**
	 * Get carousel settings.
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
		if ( 'yes' === $settings['enable_space_between'] ) {
			$swiper_settings['breakpoints']['1000']['spaceBetween'] = isset( $settings['space_between'] ) ? $settings['space_between'] : 24;
			$swiper_settings['breakpoints']['500']['spaceBetween']  = isset( $settings['space_between_tablet'] ) ? $settings['space_between_tablet'] : 8;
			$swiper_settings['spaceBetween']                        = isset( $settings['space_between_mobile'] ) ? $settings['space_between_mobile'] : 8;
		}

		if ( $settings['center_slides'] ) {
			$swiper_settings['centeredSlides'] = 'true';
		}
		if ( 'fade' === $settings['effect'] ) {
			$swiper_settings['effect'] = 'fade';
			$swiper_settings['fadeEffect']['crossFade'] = true;
		}
		if ( 'slide' === $settings['effect'] ) {
			$swiper_settings['breakpoints']['1000']['slidesPerView'] = isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 3;
			$swiper_settings['breakpoints']['500']['slidesPerView']  = isset( $settings['slides_per_view_tablet'] ) ? $settings['slides_per_view_tablet'] : 3;
			$swiper_settings['slidesPerView']                        = isset( $settings['slides_per_view_mobile'] ) ? $settings['slides_per_view_mobile'] : 1;

		}

		if ( $settings['show_arrows'] ) {
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

		$defaults        = array( 'container_class' => 'swiper sn-elementor-main-swiper' );
		$settings        = array_merge( $defaults, $settings );
		$swiper_settings = $this->get_swiper_carousel_options( $settings );

		$widget->add_render_attribute(
			'slider',
			array(
				'class'               => 'mx-0',
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
		?>
		<div class="card border-0 shadow-sm p-4 p-xxl-5">
			<div class="d-flex justify-content-between pb-4 mb-2">
				<!-- <span class="btn btn-icon btn-primary btn-lg shadow-primary pe-none">
				<i class="bx bxs-quote-left"></i>
				</span> -->
				<?php
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
				?>
				<div class="d-flex">
				<?php
				$prev_id                       = ! empty( $settings['prev_arrow_id'] ) ? $settings['prev_arrow_id'] : '';
				$next_id                       = ! empty( $settings['next_arrow_id'] ) ? $settings['next_arrow_id'] : '';
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
			</div>
			
			<?php
			$widget->skin_loop_header( $settings );
			?>
				<?php foreach ( $settings['slides_skin'] as $slide ) : ?>                                    
					<?php
					$this->print_slide( $slide, $settings, $slide['_id'] );
					?>
				<?php endforeach; ?>
				</div>
				<!-- Pagination -->
				<?php
				$widget->render_swiper_pagination( $settings );
				?>
					
			</div>
		</div>       
		<?php
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
		$widget->add_render_attribute( 'blockquote-text-' . $element_key, 'class', $settings['blockquote_text_css'] );
		?>
			<figure <?php $widget->print_render_attribute_string( 'figure' ); ?>>
				<blockquote class="card-body p-0 mb-0">
					<p <?php $widget->print_render_attribute_string( 'blockquote-text-' . $element_key ); ?>><?php echo esc_html( $slide['blockquote'] ); ?></p>
				</blockquote>
				<?php
				$avatar_css = $settings['avatar_css'] ? 'sn-avatar ' . $settings['avatar_css'] : 'sn-avatar rounded-circle';
				$widget->add_render_attribute(
					'avatar-' . $element_key,
					[
						'class' => $avatar_css,
						'src'   => $slide['avatar']['url'],
						'alt'   => Control_Media::get_image_alt( $slide['avatar'] ),
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
				<figcaption class="card-footer border-0 d-flex align-items-center w-100 pb-2">
					<img <?php $widget->print_render_attribute_string( 'avatar-' . $element_key ); ?>>
					<div class="ps-3">
						<<?php echo esc_html( $settings['name_tag'] ); ?> <?php $widget->print_render_attribute_string( 'name-' . $element_key ); ?>><?php echo esc_html( $slide['name'] ); ?></<?php echo esc_html( $settings['name_tag'] ); ?>>
						<span <?php $widget->print_render_attribute_string( 'role-' . $element_key ); ?>><?php echo esc_html( $slide['role'] ); ?></span>
					</div>
				</figcaption>
			</figure>
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
