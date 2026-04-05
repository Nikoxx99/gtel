<?php
namespace SiliconElementor\Modules\Carousel\Skins;

use Elementor;
use Elementor\Skin_Base;
use Elementor\Control_Media;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Controls_Manager;
use SiliconElementor\Core\Utils as AR_Utils;
use Elementor\Icons_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Skin Post Carousel
 */
class Skin_Post_Carousel_V2 extends Skin_Base {

	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'post-carousel-v2';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Post Carousel v2', 'silicon-elementor' );
	}

	/**
	 * Register Control Actions in the skin.
	 *
	 * @return void
	 */
	protected function _register_controls_actions() {
		add_filter( 'silicon-elementor/widget/sn-post-carousel/print_template', [ $this, 'skin_print_template' ], 10, 2 );
		// add_action( 'elementor/element/sn-post-carousel/section_layout/after_section_end', [ $this, 'update_layout_controls' ], 10, 1 );.
		add_action( 'elementor/element/sn-post-carousel/section_meta_style/before_section_end', [ $this, 'update_section_meta_style' ], 10, 1 );
	}

	/**
	 * Update section swipers
	 *
	 * @param array $widget section meta style options.
	 */
	public function update_section_meta_style( $widget ) {

		$this->parent->start_injection(
			[
				'at' => 'before',
				'of' => 'carousel_options_css',
			]
		);
		$this->parent->add_control(
			'widget_wrapper_css',
			[
				'label'       => esc_html__( 'Widget Wrapper CSS', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Additional CSS classes separated by space that you\'d like to apply to the Widget Wrapper', 'silicon-elementor' ),
				'default'     => 'position-relative mx-md-2 px-md-5',
				'condition'   => [
					'_skin'      => 'post-carousel-v2',
				],
			]
		);
		$this->parent->end_injection();

	}

	/**
	 * Update Layout Section Controls.
	 *
	 * @param array $widget The widget.
	 * @return void
	 */
	public function update_layout_controls( $widget ) {
		// added condition in widget.
		$controls = array( 'enable_author' );

		foreach ( $controls as $control ) {

			$widget->update_control(
				$control,
				[
					'condition' => [
						'_skin' => 'post-carousel-v1',
					],
				]
			);

		}
	}

	/**
	 * Get carousel settings.
	 *
	 * @param array $settings The widget settings..
	 * @return array
	 */
	public function get_swiper_carousel_options( array $settings = null ) {

		if ( null === $settings ) {
			$settings = $this->get_settings_for_display();
		}

		$swiper_settings = array(
			'slidesPerView' => 1,
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
			$swiper_settings['spaceBetween']                        = isset( $settings['space_between'] ) && 'yes' === $settings['enable_space_between'] ? $settings['space_between'] : 8;
			// $swiper_settings['breakpoints']['1440']['spaceBetween'] = isset( $settings['space_between'] ) && 'yes' === $settings['enable_space_between'] ? $settings['space_between'] : 8;
			$swiper_settings['breakpoints']['992']['spaceBetween']  = isset( $settings['space_between_tablet'] ) && 'yes' === $settings['enable_space_between'] ? $settings['space_between_tablet'] : 8;
			$swiper_settings['breakpoints']['560']['spaceBetween']  = isset( $settings['space_between_mobile'] ) && 'yes' === $settings['enable_space_between'] ? $settings['space_between_mobile'] : 8;
		}

		if ( $settings['center_slides'] ) {
			$swiper_settings['centeredSlides'] = 'true';
		}
		if ( 'fade' === $settings['effect'] ) {
			$swiper_settings['effect'] = 'fade';
			$swiper_settings['fadeEffect']['crossFade'] = true;
		}
		if ( 'slide' === $settings['effect'] ) {
			$swiper_settings['slidesPerView']                        = isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 1;
			// $swiper_settings['breakpoints']['1440']['slidesPerView'] = isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 3;
			$swiper_settings['breakpoints']['992']['slidesPerView'] = isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 3;
			$swiper_settings['breakpoints']['560']['slidesPerView']  = isset( $settings['slides_per_view_tablet'] ) ? $settings['slides_per_view_tablet'] : 3;
			$swiper_settings['breakpoints']['0']['slidesPerView']    = isset( $settings['slides_per_view_mobile'] ) ? $settings['slides_per_view_mobile'] : 1;

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

		$swiper_settings = $this->get_swiper_carousel_options( $settings );

		$widget->add_render_attribute(
			'slider',
			array(
				'data-swiper-options' => esc_attr( json_encode( $this->get_swiper_carousel_options( $settings ) ) ),
			)
		);

		$widget->query_posts( $settings );
		$wp_query = $widget->get_query();
		$widget->render_arrow_button( $settings, 'news-prev', 'news-next' );
		$widget_wrap_css = [ 'sn-swiper__wrap' ];
		if ( $settings['widget_wrapper_css'] ) {
			$widget_wrap_css = $settings['widget_wrapper_css'];
		}
		$widget->add_render_attribute( 'widget_wrapper_css', 'class', $widget_wrap_css );
		?><div <?php $widget->print_render_attribute_string( 'widget_wrapper_css' ); ?>>
		<?php
		if ( $settings['show_arrows'] ) {
			$widget->add_render_attribute(
				'prev_arrow_button',
				[

					'class' => 'btn btn-prev btn-icon btn-sm position-absolute top-50 start-0 translate-middle-y mt-n4 d-none d-md-inline-flex',

				]
			);
			$widget->add_render_attribute(
				'next_arrow_button',
				[

					'class' => 'btn btn-next btn-icon btn-sm position-absolute top-50 end-0 translate-middle-y mt-n4 d-none d-md-inline-flex',

				]
			);
			$widget->print_prev_button();
			$widget->print_next_button();
		}
			$widget->skin_loop_header( $settings );
				$element_key = 1;
		while ( $wp_query->have_posts() ) {
			$this->current_permalink = get_permalink();
			$wp_query->the_post();

			$this->print_slide( $settings, $element_key );
			$element_key++;
		}
		wp_reset_postdata();
		?>
				</div>
				<?php
				$widget->render_swiper_pagination( $settings );
				?>
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
	 * Print the slide.
	 *
	 * @param array $settings the widget settings.
	 * @param array $element_key the element_key slider id.
	 * @return void
	 */
	public function print_slide( array $settings, $element_key ) {
		$widget                = $this->parent;
		$placeholder_image_src = Utils::get_placeholder_image_src();
		$featured_image        = ! empty( get_the_post_thumbnail_url() ) ? get_the_post_thumbnail_url() : $placeholder_image_src;
		$widget->skin_slide_start( $settings, $element_key );
		?>
			<article class="card p-md-3 p-2 border-0 shadow-sm card-hover-primary h-100 mx-2 sn-card-hover">
				<div class="card-body pb-0">
				<div class="d-flex align-items-center justify-content-between mb-3 sn-meta">
					<?php
					if ( $settings['enable_category'] ) {
						?>
						<div class="fs-sm">
						<?php
						silicon_the_post_categories( 'grid-v4' );
						?>
						</div>
						<?php
					}
					?>
					<?php
					$meta_css = $settings['meta_css'];
					$posted   = get_post_time();
					$date     = $posted >= strtotime( '-1 day' ) ? human_time_diff( $posted ) . ' ago' : gmdate( 'M j, Y', $posted );
					?>
					<?php $widget->render_meta_v2(); ?>
				
				</div>
				<?php
				$widget->add_render_attribute( 'sn_title_' . $element_key, 'class', [ 'silicon-elementor-title__name', 'h4' ] );
				if ( ! empty( $settings['title_css'] ) ) {
					$widget->add_render_attribute( 'sn_title_' . $element_key, 'class', $settings['title_css'] );
				}
				$widget->add_render_attribute( 'sn_excerpt_' . $element_key, 'class', [ 'mb-0', 'custom-excerpt-color', 'silicon-elementor-excerpt__name' ] );
				if ( ! empty( $settings['excerpt_css'] ) ) {
					$widget->add_render_attribute( 'sn_excerpt_' . $element_key, 'class', $settings['excerpt_css'] );
				}
				if ( $settings['show_title'] ) :
					?>
				<<?php echo esc_html( $settings['title_tag'] ); ?> <?php $widget->print_render_attribute_string( 'sn_title_' . $element_key ); ?>>
				<a href="<?php the_permalink(); ?>" class="stretched-link custom-title-color"><?php the_title(); ?></a>
				</<?php echo esc_html( $settings['title_tag'] ); ?>>
				<?php endif; ?>
				<?php
				if ( has_excerpt() && $settings['show_excerpt'] ) :
					?>
				<p <?php $widget->print_render_attribute_string( 'sn_excerpt_' . $element_key ); ?>><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>
				</div>
				<div class="card-footer d-flex align-items-center py-4 text-muted border-top-0">
					<?php $widget->render_comments( $settings ); ?>
				</div>
			</article>
		</div>
		<?php
	}

	/**
	 * Skin print template.
	 *
	 * @param array $content the content.
	 * @param array $post_carousel widget name.
	 * @return string
	 */
	public function skin_print_template( $content, $post_carousel ) {

		if ( 'sn-post-carousel' == $post_carousel->get_name() ) {
			return '';
		}

		return $content;
	}
}
