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
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Skin Post Carousel
 */
class Skin_Post_Carousel_V3 extends Skin_Base {

	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'post-carousel-v3';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Post Carousel v3', 'silicon-elementor' );
	}

	/**
	 * Register Control Actions in the skin.
	 *
	 * @return void
	 */
	protected function _register_controls_actions() {
		add_filter( 'silicon-elementor/widget/sn-post-carousel/print_template', [ $this, 'skin_print_template' ], 10, 2 );
		add_action( 'elementor/element/sn-post-carousel/section_layout/after_section_end', [ $this, 'update_layout_controls' ], 10, 1 );
		add_action( 'elementor/element/sn-post-carousel/section_slides/before_section_end', [ $this, 'update_section_slides' ], 10, 1 );
		add_action( 'elementor/element/sn-post-carousel/section_slides_style/after_section_end', [ $this, 'update_section_slides_style' ], 10, 1 );
	}

	/**
	 * Update Layout Section Controls.
	 *
	 * @param array $widget The widget.
	 * @return void
	 */
	public function update_layout_controls( $widget ) {
		$controls = array( 'show_title', 'title_tag', 'show_excerpt', 'enable_category', 'enable_meta' );

		foreach ( $controls as $control ) {

			$widget->update_control(
				$control,
				[
					'condition' => [
						'_skin!' => 'post-carousel-v3',
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
	public function update_section_slides( $widget ) {

		$this->parent->start_injection(
			[
				'at' => 'before',
				'of' => 'slides_per_view',
			]
		);

		$this->add_control(
			'adjust_for_service',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Adjust For Service', 'silicon-elementor' ),
				'default'   => 'no',
				'label_off' => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Disable', 'silicon-elementor' ),
				'condition' => [
					'effect' => 'slide',
				],
			]
		);

		$this->parent->end_injection();
	}

	/**
	 * Update Section Style Controls.
	 *
	 * @param Widget_Base $widget post carousel widget.
	 * @return void
	 */
	public function update_section_slides_style( Widget_Base $widget ) {
		$this->parent = $widget;
		$this->start_controls_section(
			'section_title_style',
			[
				'label'      => __( 'Title', 'silicon-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);
		$this->add_control(
			'title_heading',
			[
				'label'     => esc_html__( 'Title', 'silicon-elementor' ),
				'type'      => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Title Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .h5 a' => 'color: {{VALUE}} !important;',
				],
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'title_typography',
				'selector'  => '{{WRAPPER}} .h5 a',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);
		$this->end_controls_section();
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

		$adjust_for_service = $this->get_instance_value( 'adjust_for_service' );

		if ( 'yes' === $settings['enable_space_between'] ) {
			$swiper_settings['spaceBetween']                                      = isset( $settings['space_between'] ) && 'yes' === $settings['enable_space_between'] ? $settings['space_between'] : 8;
			$swiper_settings['breakpoints']['1000']['spaceBetween']               = isset( $settings['space_between'] ) && 'yes' === $settings['enable_space_between'] ? $settings['space_between'] : 8;
			$swiper_settings['breakpoints']['768']['spaceBetween']               = isset( $settings['space_between'] ) && 'yes' === $settings['enable_space_between'] ? $settings['space_between'] : 8;
			$swiper_settings['breakpoints']['576']['spaceBetween']               = isset( $settings['space_between_tablet'] ) && 'yes' === $settings['enable_space_between'] ? $settings['space_between_tablet'] : 8;
			$swiper_settings['breakpoints']['0']['spaceBetween']                  = isset( $settings['space_between_mobile'] ) && 'yes' === $settings['enable_space_between'] ? $settings['space_between_mobile'] : 8;

			if ( $adjust_for_service ) {
				$swiper_settings['spaceBetween']                        = isset( $settings['space_between'] ) && 'yes' === $settings['enable_space_between'] ? $settings['space_between'] : 8;
				$swiper_settings['breakpoints']['1200']['spaceBetween'] = isset( $settings['space_between'] ) && 'yes' === $settings['enable_space_between'] ? $settings['space_between'] : 8;
				$swiper_settings['breakpoints']['1000']['spaceBetween'] = isset( $settings['space_between'] ) && 'yes' === $settings['enable_space_between'] ? $settings['space_between'] : 8;
				$swiper_settings['breakpoints']['768']['spaceBetween']  = isset( $settings['space_between'] ) && 'yes' === $settings['enable_space_between'] ? $settings['space_between'] : 8;
				$swiper_settings['breakpoints']['500']['spaceBetween']  = isset( $settings['space_between_tablet'] ) && 'yes' === $settings['enable_space_between'] ? $settings['space_between_tablet'] : 8;
				$swiper_settings['breakpoints']['0']['spaceBetween']    = isset( $settings['space_between_mobile'] ) && 'yes' === $settings['enable_space_between'] ? $settings['space_between_mobile'] : 8;
			} else {
				$swiper_settings['spaceBetween']                        = isset( $settings['space_between'] ) && 'yes' === $settings['enable_space_between'] ? $settings['space_between'] : 8;
				$swiper_settings['breakpoints']['1000']['spaceBetween'] = isset( $settings['space_between'] ) && 'yes' === $settings['enable_space_between'] ? $settings['space_between'] : 8;
				$swiper_settings['breakpoints']['768']['spaceBetween']  = isset( $settings['space_between'] ) && 'yes' === $settings['enable_space_between'] ? $settings['space_between'] : 8;
				$swiper_settings['breakpoints']['576']['spaceBetween']  = isset( $settings['space_between_tablet'] ) && 'yes' === $settings['enable_space_between'] ? $settings['space_between_tablet'] : 8;
				$swiper_settings['breakpoints']['0']['spaceBetween']    = isset( $settings['space_between_mobile'] ) && 'yes' === $settings['enable_space_between'] ? $settings['space_between_mobile'] : 8;
			}
		}

		if ( $settings['center_slides'] ) {
			$swiper_settings['centeredSlides'] = 'true';
		}
		if ( 'fade' === $settings['effect'] ) {
			$swiper_settings['effect']                  = 'fade';
			$swiper_settings['fadeEffect']['crossFade'] = true;
		}

		if ( 'slide' === $settings['effect'] ) {
			if ( $adjust_for_service ) {
				$swiper_settings['slidesPerView']                        = isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 1;
				$swiper_settings['breakpoints']['1200']['slidesPerView'] = isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 4;
				$swiper_settings['breakpoints']['1000']['slidesPerView'] = isset( $settings['slides_per_view_tablet'] ) ? $settings['slides_per_view_tablet'] : 3;
				$swiper_settings['breakpoints']['768']['slidesPerView']  = isset( $settings['slides_per_view_tablet'] ) ? $settings['slides_per_view_tablet'] : 3;
				$swiper_settings['breakpoints']['500']['slidesPerView']  = 2;
				$swiper_settings['breakpoints']['0']['slidesPerView']    = isset( $settings['slides_per_view_mobile'] ) ? $settings['slides_per_view_mobile'] : 1;
			} else {
				$swiper_settings['slidesPerView']                        = isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 1;
				$swiper_settings['breakpoints']['1000']['slidesPerView'] = isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 3;
				$swiper_settings['breakpoints']['768']['slidesPerView']  = isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 3;
				$swiper_settings['breakpoints']['576']['slidesPerView']  = isset( $settings['slides_per_view_tablet'] ) ? $settings['slides_per_view_tablet'] : 2;
				$swiper_settings['breakpoints']['0']['slidesPerView']    = isset( $settings['slides_per_view_mobile'] ) ? $settings['slides_per_view_mobile'] : 1;
			}
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
			$widget->skin_loop_header( $settings );
				$element_key = 1;
		while ( $wp_query->have_posts() ) {
			$this->current_permalink = get_permalink();
			$wp_query->the_post();
			$widget->skin_slide_start( $settings, $element_key );
			get_template_part( 'templates/contents/loop-post', 'grid-podcast' );
			?>
			</div>
			<?php
			$element_key++;
		}
		wp_reset_postdata();
		?>
				</div>
				<?php
				$widget->render_swiper_pagination( $settings );
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

		$this->print_slider( $settings );
		$widget->render_script();

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
