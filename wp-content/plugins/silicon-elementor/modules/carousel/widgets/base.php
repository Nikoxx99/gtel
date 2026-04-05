<?php
namespace SiliconElementor\Modules\Carousel\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use SiliconElementor\Base\Base_Widget;
use Elementor\Plugin;
use Elementor\Group_Control_Border;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Carousel Base for Carousel Control
 */
abstract class Base extends Base_Widget {

	/**
	 * Number of Slides.
	 *
	 * @var int
	 */
	private $slide_prints_count = 0;

	/**
	 * Fetch the Scripts based on keyword.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return [ 'imagesloaded' ];
	}

	/**
	 * Add repeater controls for carousels.
	 *
	 * @param Repeater $repeater repeater arguments.
	 * @return array
	 */
	abstract protected function add_repeater_controls( Repeater $repeater );

	/**
	 * Default repeater values.
	 *
	 * @return array
	 */
	abstract protected function get_repeater_defaults();

	/**
	 * Display Carousel.
	 *
	 * @param array  $slide repeater single control arguments.
	 * @param array  $settings control arguments.
	 * @param string $element_key slider id argument.
	 * @return array
	 */
	abstract protected function print_slide( array $slide, array $settings, $element_key );

	/**
	 * Register Controls.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_slides',
			[
				'label' => esc_html__( 'Slides', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$this->add_repeater_controls( $repeater );

		$this->add_control(
			'slides',
			[
				'label'   => esc_html__( 'Slides', 'silicon-elementor' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => $this->get_repeater_defaults(),
			]
		);

		$this->add_control(
			'effect',
			[
				'type'               => Controls_Manager::SELECT,
				'label'              => esc_html__( 'Effect', 'silicon-elementor' ),
				'default'            => 'slide',
				'options'            => [
					''      => esc_html__( 'None', 'silicon-elementor' ),
					'slide' => esc_html__( 'Slide', 'silicon-elementor' ),
					'fade'  => esc_html__( 'Fade', 'silicon-elementor' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'slides_per_view',
			[
				'type'           => Controls_Manager::NUMBER,
				'label'          => esc_html__( 'Slides Per View', 'silicon-elementor' ),
				'min'            => 1,
				'max'            => 10,
				'default'        => 1,
				'condition'      => [
					'effect' => 'slide',
				],
				'devices'        => [ 'desktop', 'tablet', 'mobile' ],
				'default'        => 1,
				'tablet_default' => 1,
				'mobile_default' => 1,
			]
		);

		$this->add_control(
			'enable_space_between',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Space Between', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Hide', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Show', 'silicon-elementor' ),
			]
		);

		$this->add_responsive_control(
			'space_between',
			[
				'type'           => Controls_Manager::NUMBER,
				'label'          => esc_html__( 'Space Between', 'silicon-elementor' ),
				'description'    => esc_html__( 'Set Space between each Slides', 'silicon-elementor' ),
				'min'            => 0,
				'max'            => 100,
				'devices'        => [ 'desktop', 'tablet', 'mobile' ],
				'default'        => 8,
				'tablet_default' => 8,
				'mobile_default' => 8,
				'condition'      => [
					'enable_space_between' => 'yes',
				],
			]
		);

		$this->add_control(
			'center_slides',
			[
				'type'               => Controls_Manager::SWITCHER,
				'label'              => esc_html__( 'Center Slides', 'silicon-elementor' ),
				'default'            => 'yes',
				'label_off'          => esc_html__( 'Hide', 'silicon-elementor' ),
				'label_on'           => esc_html__( 'Show', 'silicon-elementor' ),
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_additional_options',
			[
				'label' => esc_html__( 'Additional Options', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'show_arrows',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Arrows', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Hide', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Show', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'prev_arrow_id',
			[
				'label'       => esc_html__( 'Prev Arrow ID', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Enter ID for Previous Button', 'silicon-elementor' ),
				'condition'   => [
					'show_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'next_arrow_id',
			[
				'label'       => esc_html__( 'Next Arrow ID', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Enter ID for Next Button', 'silicon-elementor' ),
				'condition'   => [
					'show_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'pagination',
			[
				'label'   => esc_html__( 'Pagination', 'silicon-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'bullets',
				'options' => [
					''            => esc_html__( 'None', 'silicon-elementor' ),
					'bullets'     => esc_html__( 'Dots', 'silicon-elementor' ),
					'fraction'    => esc_html__( 'Fraction', 'silicon-elementor' ),
					'progressbar' => esc_html__( 'Progress', 'silicon-elementor' ),
				],
			]
		);

		$this->add_control(
			'speed',
			[
				'label'              => esc_html__( 'Transition Duration', 'silicon-elementor' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 500,
				'step'               => 100,
				'render_type'        => 'none',
				'frontend_available' => true,
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
			'autoplay_speed',
			[
				'label'              => esc_html__( 'Autoplay Speed', 'silicon-elementor' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 5000,
				'condition'          => [
					'autoplay' => 'yes',
				],
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

		$this->add_control(
			'pause_on_hover',
			[
				'label'              => esc_html__( 'Pause on Hover', 'silicon-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'condition'          => [
					'autoplay' => 'yes',
				],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_slides_style',
			[
				'label' => esc_html__( 'Slides', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'slide_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sn-elementor-main-swiper .swiper-slide' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'           => 'border',
				'selector'       => '{{WRAPPER}} .sn-elementor-main-swiper .swiper-slide',
				'fields_options' => [
					'border' => [
						'default' => '',
					],
				],
			]
		);

		$this->add_control(
			'slide_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'silicon-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'%' => [
						'max' => 50,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .sn-elementor-main-swiper .swiper-slide' => 'border-radius: {{SIZE}}{{UNIT}}',
				],

			]
		);

		$this->add_control(
			'slide_padding',
			[
				'label'      => esc_html__( 'Padding', 'silicon-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .sn-elementor-main-swiper .swiper-slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
				'separator'  => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_meta_style',
			[
				'label' => __( 'Swiper Wrappers', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,

			]
		);

		$this->add_control(
			'carousel_options_css',
			[
				'label'       => esc_html__( 'Carousel Options Wrap', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'CSS Classes added to data-swiper-options attribute <div>', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'carousel_wrapper_css',
			[
				'label'       => esc_html__( 'Carousel Wrapper CSS', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'CSS Classes added to swiper-wrapper class <div>', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'carousel_slide_css',
			[
				'label'       => esc_html__( 'Carousel Slide CSS', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'CSS Classes added to swiper-slide class <div>', 'silicon-elementor' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_navigation',
			[
				'label' => esc_html__( 'Navigation', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_arrows',
			[
				'label'     => esc_html__( 'Arrows', 'silicon-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'none',
				'condition' => [
					'show_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'arrows_size',
			[
				'label'     => esc_html__( 'Size', 'silicon-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 20,
				],
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 40,
					],
				],
				'selectors' => [
					// '{{WRAPPER}} .sn-elementor-swiper-button' => 'width: {{SIZE}}{{UNIT}} !important;',
					// '{{WRAPPER}} .sn-elementor-swiper-button' => 'height: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .sn-elementor-swiper-icon' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => [
					'show_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'arrows_color',
			[
				'label'     => esc_html__( 'Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sn-elementor-swiper-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} .sn-elementor-swiper-button svg' => 'fill: {{VALUE}}',
				],
				'condition' => [
					'show_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'arrows_hover_color',
			[
				'label'     => esc_html__( 'Background Hover Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .btn-prev:hover, {{WRAPPER}} .btn-prev:focus' => 'background-color: {{VALUE}} !important;',
					'{{WRAPPER}} .btn-prev:hover svg path, {{WRAPPER}} .btn-prev:focus svg path' => 'fill: {{VALUE}} !important;',
					'{{WRAPPER}} .btn-next:hover, {{WRAPPER}} .btn-next:focus' => 'background-color: {{VALUE}} !important;',
					'{{WRAPPER}} .btn-next:hover svg path, {{WRAPPER}} .btn-next:focus svg path' => 'fill: {{VALUE}} !important;',
				],
				'condition' => [
					'show_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'heading_pagination',
			[
				'label' => esc_html__( 'Pagination', 'silicon-elementor' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'pagination_size',
			[
				'label'     => esc_html__( 'Size', 'silicon-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .swiper-container-horizontal .swiper-pagination-progressbar' => 'height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .swiper-pagination-fraction' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'pagination_color',
			[
				'label'     => esc_html__( 'Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet-active, {{WRAPPER}} .swiper-pagination-progressbar-fill' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .swiper-pagination-fraction' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pagination_css',
			[
				'label'   => esc_html__( 'Pagination CSS', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'title'   => esc_html__( 'Add your custom class for text without the dot. e.g: my-class', 'silicon-elementor' ),
				'default' => 'position-relative pt-1 pt-sm-3 mt-5',
			]
		);

		$this->end_controls_section();
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

		$default_settings = [
			'container_class' => 'sn-elementor-main-swiper',
			'video_play_icon' => true,
		];

		$settings = array_merge( $default_settings, $settings );

		$slides_count = count( $settings['slides'] );
		?>
		<div class="sn-elementor-swiper">
			<div class="<?php echo esc_attr( $settings['container_class'] ); ?> swiper-container">
				<div class="swiper-wrapper">
					<?php
					foreach ( $settings['slides'] as $index => $slide ) :
						$this->slide_prints_count++;
						?>
						<div class="swiper-slide">
							<?php $this->print_slide( $slide, $settings, 'slide-' . $index . '-' . $this->slide_prints_count ); ?>
						</div>
					<?php endforeach; ?>
				</div>
				<?php if ( 1 < $slides_count ) : ?>
					<?php if ( $settings['pagination'] ) : ?>
						<div class="swiper-pagination"></div>
					<?php endif; ?>
					<?php if ( $settings['show_arrows'] ) : ?>
						<div class="sn-elementor-swiper-button sn-elementor-swiper-button-prev">
							<?php $this->render_swiper_button( 'previous' ); ?>
							<span class="sn-elementor-screen-only"><?php echo esc_html__( 'Previous', 'silicon-elementor' ); ?></span>
						</div>
						<div class="sn-elementor-swiper-button sn-elementor-swiper-button-next">
							<?php $this->render_swiper_button( 'next' ); ?>
							<span class="sn-elementor-screen-only"><?php echo esc_html__( 'Next', 'silicon-elementor' ); ?></span>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render Button.
	 *
	 * @param string $type direction arguments.
	 * @param string $icon_class icon classes.
	 * @return void
	 */
	public function render_swiper_button( $type, $icon_class = '' ) {
		$direction = 'next' === $type ? 'right' : 'left';

		if ( is_rtl() ) {
			$direction = 'right' === $direction ? 'left' : 'right';
		}

		$icon_value = 'sn-elementor-swiper-icon bx bx-chevron-' . $direction;
		$icon_value = ! empty( $icon_class ) ? $icon_value . ' ' . $icon_class : $icon_value;

		Icons_Manager::render_icon(
			[
				'library' => 'eicons',
				'value'   => $icon_value,
			],
			[ 'aria-hidden' => 'true' ]
		);
	}

	/**
	 * Swiper Carousel Open Wrappers.
	 * Need to Close two </div> while using this method.
	 *
	 * @param array $settings widgets settings.
	 * @return void
	 */
	public function skin_loop_header( array $settings = null ) {
		if ( null === $settings ) {
			$settings = $this->get_settings_for_display();
		}
		$swiper_css = [ 'swiper sn-elementor-main-swiper' ];
		if ( $settings['carousel_options_css'] ) {
			$swiper_css[] = $settings['carousel_options_css'];
		}
		$this->add_render_attribute( 'slider', 'class', $swiper_css );
		?>
		<div <?php $this->print_render_attribute_string( 'slider' ); ?>>
			<?php
			$swiper_wrapper_css = [ 'swiper-wrapper' ];
			if ( $settings['carousel_wrapper_css'] ) {
				$swiper_wrapper_css[] = $settings['carousel_wrapper_css'];
			}
			$this->add_render_attribute( 'carousel_wrapper_css', 'class', $swiper_wrapper_css );
			?>
		<div <?php $this->print_render_attribute_string( 'carousel_wrapper_css' ); ?>>
			<?php
	}

	/**
	 * Swiper Slide Open Wrappers.
	 * Need to Close a </div> while using this method.
	 *
	 * @param array  $settings widgets settings.
	 * @param string $element_key slider id.
	 * @return void
	 */
	public function skin_slide_start( array $settings = null, $element_key ) {
		if ( null === $settings ) {
			$settings = $this->get_settings_for_display();
		}
		$this->add_render_attribute( 'carousel_slide_css-' . $element_key, 'class', 'swiper-slide' );
		if ( $settings['carousel_slide_css'] ) {
			$this->add_render_attribute( 'carousel_slide_css-' . $element_key, 'class', $settings['carousel_slide_css'] );
		}
		?>
		<div <?php $this->print_render_attribute_string( 'carousel_slide_css-' . $element_key ); ?>>
		<?php
	}

	/**
	 * Render button.
	 *
	 * @param array  $settings widgets settings.
	 * @param string $default_prev_id Previous Button ID.
	 * @param string $default_next_id Next Button ID.
	 *
	 * @return void
	 */
	public function render_arrow_button( array $settings, $default_prev_id, $default_next_id ) {
		if ( null === $settings ) {
			$settings = $this->get_settings_for_display();
		}
		$prev_id = $settings['prev_arrow_id'] ? $settings['prev_arrow_id'] : $default_prev_id;
		$next_id = $settings['next_arrow_id'] ? $settings['next_arrow_id'] : $default_next_id;
		$this->add_render_attribute(
			'prev_arrow_button',
			[
				'type' => 'button',
				'id'   => $prev_id,
			]
		);
		$this->add_render_attribute(
			'next_arrow_button',
			[
				'type' => 'button',
				'id'   => $next_id,
			]
		);

	}

	/**
	 * Print prev button.
	 *
	 * @param string $icon_class icon classes.
	 * @return void
	 */
	public function print_prev_button( $icon_class = '' ) {
		?>
		<button <?php $this->print_render_attribute_string( 'prev_arrow_button' ); ?>>
			<?php $this->render_swiper_button( 'prev', $icon_class ); ?>
		</button>
		<?php
	}

	/**
	 * Print next button.
	 *
	 * @param string $icon_class icon classes.
	 * @return void
	 */
	public function print_next_button( $icon_class = '' ) {
		?>
		<button <?php $this->print_render_attribute_string( 'next_arrow_button' ); ?>>
			<?php $this->render_swiper_button( 'next', $icon_class ); ?>
		</button>
		<?php
	}

	/**
	 * Render Pagination.
	 *
	 * @param array $settings widgets settings.
	 * @return void
	 */
	public function render_swiper_pagination( $settings ) {
		$pag_css = [ 'swiper-pagination' ];
		if ( $settings['pagination_css'] ) {
			$pag_css[] = $settings['pagination_css'];
		}
		if ( $settings['pagination'] ) {
			$this->add_render_attribute(
				'swiper_pagination',
				[
					'class' => $pag_css,
				]
			);
			?>
		<div <?php $this->print_render_attribute_string( 'swiper_pagination' ); ?>></div>        
			<?php
		}
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

}
