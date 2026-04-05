<?php
namespace SiliconElementor\Modules\Carousel\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use SiliconElementor\Modules\QueryControl\Module as Module_Query;
use SiliconElementor\Modules\QueryControl\Controls\Group_Control_Related;
use SiliconElementor\Core\Utils as AR_Utils;
use SiliconElementor\Modules\Carousel\Skins;
use SiliconElementor\Base\Base_Widget;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Post Carousel
 */
class Project_Carousel extends Base_Widget {

	/**
	 * Query
	 *
	 * @var \WP_Query
	 */
	protected $query = null;


	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'sn-project-carousel';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Project Carousel', 'silicon-elementor' );
	}

	/**
	 * Get the icon for the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-posts-carousel';
	}

	/**
	 * Get the keywords associated with the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'project-carousel', 'project', 'carousel', 'cpt', 'item', 'loop', 'query', 'cards', 'custom post type' ];
	}


	/**
	 * Get Query.
	 *
	 * @return array
	 */
	public function get_query() {
		return $this->query;
	}

	/**
	 * Get post type object on import.
	 *
	 * @param array $element settings posttype.
	 * @return array
	 */
	public function on_import( $element ) {
		if ( ! get_post_type_object( $element['settings']['posts_post_type'] ) ) {
			$element['settings']['posts_post_type'] = 'jetpack-portfolio';
		}

		return $element;
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
	 * Register the Skins with the widget.
	 *
	 * @return void
	 */
	protected function register_skins() {
		$this->add_skin( new Skins\Skin_Project_Carousel_v2( $this ) );
	}


	/**
	 * Register controls for this widget.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->register_query_section_controls();
		$this->register_swiper_controls();
	}

	/**
	 * Get posts.
	 *
	 * @param array $settings settings.
	 * @return void
	 */
	public function query_posts( $settings ) {
		$query_args = [
			'posts_per_page' => $settings['posts_per_page'],
		];

		// @var Module_Query $elementor_query.
		$elementor_query = Module_Query::instance();
		$this->query     = $elementor_query->get_query( $this, 'posts', $query_args, [] );
	}

	/**
	 * Register Query Section Controls for this widget.
	 *
	 * @return void
	 */
	protected function register_query_section_controls() {
		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label'   => esc_html__( 'Posts Per Page', 'silicon-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 3,
			]
		);

		$this->add_control(
			'enable_breadcrumb',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Breadcrumb', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Disable', 'silicon-elementor' ),
				'condition'      => [
					'_skin' => '',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_post_carousel_query',
			[
				'label' => esc_html__( 'Query', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_group_control(
			Group_Control_Related::get_type(),
			[
				'name'      => 'posts',
				'presets'   => [ 'full' ],
				'exclude'   => [
					'posts_per_page', // use the one from Layout section.
				],
				'post_type' => 'jetpack-portfolio',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register Title Controls for this widget.
	 *
	 * @return void
	 */
	protected function register_swiper_controls() {
		$this->start_controls_section(
			'section_swiper',
			[
				'label' => esc_html__( 'Swiper', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'autoplay',
			[
				'label'              => esc_html__( 'Autoplay', 'silicon-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'separator'          => 'before',
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);
		$this->add_control(
			'loop',
			[
				'label'              => esc_html__( 'Infinite Loop', 'silicon-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'frontend_available' => true,
			]
		);
		$this->add_responsive_control(
			'space_between',
			[
				'type'        => Controls_Manager::NUMBER,
				'label'       => esc_html__( 'Space Between', 'silicon-elementor' ),
				'description' => esc_html__( 'Set Space between each Slides', 'silicon-elementor' ),
				'min'         => 0,
				'max'         => 100,
				'default'     => 30,
			]
		);

		$this->add_control(
			'pagination',
			[
				'label'   => esc_html__( 'Pagination', 'silicon-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'fraction',
				'options' => [
					''            => esc_html__( 'None', 'silicon-elementor' ),
					'bullets'     => esc_html__( 'Dots', 'silicon-elementor' ),
					'fraction'    => esc_html__( 'Fraction', 'silicon-elementor' ),
					'progressbar' => esc_html__( 'Progress', 'silicon-elementor' ),
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render tabs.
	 *
	 * @param array $settings The widget settings.
	 */
	protected function swiper_tabs( $settings ) {
		$count = 1;
		$this->query_posts( $settings );
		$query = $this->get_query();

		if ( ! $query->found_posts ) {
			return;
		}

		?><div class="swiper-tabs position-md-absolute top-0 end-0 w-md-50 h-100">
		<?php

		while ( $query->have_posts() ) :
			$query->the_post();
			$id     = get_the_ID();
			$active = 1 === $count ? 'active' : '';
			if ( has_post_thumbnail() ) :
				silicon_get_template( 'templates/portfolio/loop-portfolio-slider-tabs.php', [ 'active' => $active ] );
			endif;
			$count++;
			endwhile;
		?>
		</div>
		<?php
	}

	/**
	 * Render breadcrumb.
	 */
	protected function render_breadcrumb() {
		$args = array(
			'wrap_before' => '<nav class="d-none d-md-block pt-4 mt-lg-3"><ol class="breadcrumb mb-0">',
		);
		if ( function_exists( 'silicon_breadcrumb' ) ) {
			silicon_breadcrumb( $args );
		}
	}

	/**
	 * Get carousel settings
	 *
	 * @param array $settings The widget settings.
	 * @return array
	 */
	protected function get_swiper_carousel_options( array $settings = null ) {

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
		$swiper_settings['tabs'] = 'true';

		return $swiper_settings;
	}


	/**
	 * Render Slider.
	 *
	 * @param array $settings The widget settings.
	 */
	protected function print_slider( $settings ) {
		$query = $this->get_query();
		$this->add_render_attribute(
			'slider',
			array(
				'class'               => 'swiper pt-4 pt-md-0',
				'data-swiper-options' => esc_attr( wp_json_encode( $this->get_swiper_carousel_options( $settings ) ) ),
			)
		);
		?>
		<div class="d-none d-lg-block" style="height: 160px;"></div>
		<div class="d-none d-md-block d-lg-none" style="height: 80px;"></div>
		<div <?php $this->print_render_attribute_string( 'slider' ); ?>>
			<div class="swiper-wrapper">
			<?php
			while ( $query->have_posts() ) {
				$query->the_post();

				$this->current_permalink = get_permalink();
				silicon_get_template( 'templates/portfolio/loop-portfolio-slider.php' );
			}
			?>
			</div>
		</div>
		<div class="d-none d-lg-block" style="height: 160px;"></div>
		<div class="d-none d-md-block d-lg-none" style="height: 80px;"></div>
		<div class="d-md-none" style="height: 40px;"></div>

		<!-- Prev / Next buttons + Counter -->
		<div class="d-flex align-items-center ps-2 pb-5">
			<button type="button" class="btn btn-prev btn-icon btn-sm position-static"><i class="bx bx-chevron-left"></i></button>
			<div class="swiper-pagination position-static w-auto mx-3" style="min-width: 30px;"></div>
			<button type="button" class="btn btn-next btn-icon btn-sm position-static"><i class="bx bx-chevron-right"></i></button>
		</div>
		<?php
	}

	/**
	 * Render swiper.
	 *
	 * @param string $breadcrumb The breadcrumb string.
	 */
	public function print_swiper( $breadcrumb = '' ) {
		$settings = $this->get_settings_for_display();
		?>
		<section class="position-relative pt-5 py-lg-5 mt-3 mt-md-4">
			<?php $this->swiper_tabs( $settings ); ?>
			<div class="container pt-1 pt-lg-2">
				<div class="row">
					<div class="col-lg-5 col-md-6">
					<?php echo wp_kses_post( $breadcrumb ); ?>
					<?php $this->print_slider( $settings ); ?>
					</div>
				</div>
			</div>
		</section>
		<?php
	}

	/**
	 * Render script in the editor.
	 */
	public function render_script() {
		if ( Plugin::$instance->editor->is_edit_mode() ) :
			?>
			<script type="text/javascript">
			var carousel = (() => {
				// forEach function
				let forEach = (array, callback, scope) => {
				for (let i = 0; i < array.length; i++) {
					callback.call(scope, i, array[i]); // passes back stuff we need
				}
				};

				// Carousel initialisation
				let carousels = document.querySelectorAll('.swiper');
				forEach(carousels, (index, value) => {
					let userOptions,
					pagerOptions;
				if(value.dataset.swiperOptions != undefined) userOptions = JSON.parse(value.dataset.swiperOptions);


				// Pager
				if(userOptions.pager) {
					pagerOptions = {
					pagination: {
						el: userOptions.pager,
						clickable: true,
						bulletActiveClass: 'active',
						bulletClass: 'page-item',
						renderBullet: function (index, className) {
						return '<li class="' + className + '"><a href="#" class="page-link btn-icon btn-sm">' + (index + 1) + '</a></li>';
						}
					}
					}
				}

				// Slider init
				let options = {...userOptions, ...pagerOptions};
				let swiper = new Swiper(value, options);

				// Tabs (linked content)
				if(userOptions.tabs) {

					swiper.on('activeIndexChange', (e) => {
					let targetTab = document.querySelector(e.slides[e.activeIndex].dataset.swiperTab),
						previousTab = document.querySelector(e.slides[e.previousIndex].dataset.swiperTab);

					previousTab.classList.remove('active');
					targetTab.classList.add('active');
					});
				}

				});

				})();
			</script>
			<?php
		endif;
	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	public function render() {
		$settings   = $this->get_settings_for_display();
		$breadcrumb = '';

		if ( $settings['enable_breadcrumb'] ) {
			ob_start();
			$this->render_breadcrumb();
			$breadcrumb = ob_get_clean();
		}
		$this->query_posts( $settings );

		$query = $this->get_query();

		if ( ! $query->found_posts ) {
			return;
		}

		$this->print_swiper( $breadcrumb );

		wp_reset_postdata();
		$this->render_script();

	}
}




