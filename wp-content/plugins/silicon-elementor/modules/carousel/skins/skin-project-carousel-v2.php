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
 * Skin Lottie Carousel
 */
class Skin_Project_Carousel_V2 extends Skin_Base {

	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'skin-project-carousel-v2';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Style v2', 'silicon-elementor' );
	}

	/**
	 * Register Control Actions in the skin.
	 *
	 * @return void
	 */
	protected function __register_controls_actions() {
		add_filter( 'silicon-elementor/widget/sn-project-carousel/print_template', [ $this, 'skin_print_template' ], 10, 2 );
		add_action( 'elementor/element/sn-project-carousel/section_layout/before_section_end', [ $this, 'update_slides' ], 10, 1 );

	}

	/**
	 * Update section slides
	 */
	public function update_slides() {

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => 'posts_per_page',
			]
		);

		$this->parent->add_responsive_control(
			'slides_per_view',
			[
				'type'           => Controls_Manager::NUMBER,
				'label'          => esc_html__( 'Slides Per View', 'silicon-elementor' ),
				'min'            => 1,
				'max'            => 10,
				'default'        => 3,
				'devices'        => [ 'desktop', 'tablet', 'mobile' ],
				'default'        => 3,
				'tablet_default' => 2,
				'mobile_default' => 1,
				'condition'      => [
					'_skin!' => '',
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
	protected function get_carousel_options( array $settings = null ) {

		if ( null === $settings ) {
			$settings = $this->get_settings_for_display();
		}

		$swiper_settings               = [];
		$swiper_settings['navigation'] = array(
			'prevEl' => '.btn-prev',
			'nextEl' => '.btn-next',

		);

		if ( ! empty( $settings['pagination'] ) ) {
			$swiper_settings['pagination']['el'] = '.swiper-pagination';
		}

		$swiper_settings['breakpoints']['1000']['slidesPerView'] = isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 3;
		$swiper_settings['breakpoints']['560']['slidesPerView']  = isset( $settings['slides_per_view_tablet'] ) ? $settings['slides_per_view_tablet'] : 2;
		$swiper_settings['slidesPerView']                        = isset( $settings['slides_per_view_mobile'] ) ? $settings['slides_per_view_mobile'] : 1;

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

		if ( ! empty( $settings['space_between'] ) ) {
			$swiper_settings['spaceBetween'] = $settings['space_between'];
		}

		if ( $settings['loop'] ) {
			$swiper_settings['loop'] = 'true';
		}
		if ( $settings['autoplay'] ) {
			$swiper_settings['autoplay'] = 'true';
		}

		return $swiper_settings;
	}


	/**
	 * Render Slider.
	 *
	 * @param array $settings The widget settings.
	 */
	protected function print_swiper( $settings ) {
		$query = $this->parent->get_query();
		$this->parent->add_render_attribute(
			'slider',
			array(
				'class'               => 'swiper',
				'data-swiper-options' => esc_attr( wp_json_encode( $this->get_carousel_options( $settings ) ) ),
			)
		);
		?>
		<div <?php $this->parent->print_render_attribute_string( 'slider' ); ?>>
			<div class="swiper-wrapper">
			<?php
			while ( $query->have_posts() ) {
				$query->the_post();

				$this->current_permalink = get_permalink();
				silicon_get_template( 'templates/portfolio/loop-portfolio-slider-v2.php' );
			}
			?>
			</div>
		</div>
		<div class="swiper-pagination position-relative pt-sm-2 mt-4"></div>
		<?php
	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	public function render() {
		$settings = $this->parent->get_settings_for_display();
		$this->parent->query_posts( $settings );

		$query = $this->parent->get_query();

		if ( ! $query->found_posts ) {
			return;
		}

		$this->print_swiper( $settings );

		wp_reset_postdata();
		$this->parent->render_script();
	}

	/**
	 * Skin print template.
	 *
	 * @param array $content the content.
	 * @param array $carousel widget name.
	 * @return string
	 */
	public function skin_print_template( $content, $carousel ) {

		if ( 'sn-project-carousel' === $carousel->get_name() ) {
			return '';
		}

		return $content;
	}
}
