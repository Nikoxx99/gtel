<?php

namespace SiliconElementor\Modules\Section;

use SiliconElementor\Base\Module_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Module
 */
class Module extends Module_Base {

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();

		$this->add_actions();
	}

	/**
	 * Get Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'override-section';
	}

	/**
	 * Add Action.
	 *
	 * @return void
	 */
	public function add_actions() {
		add_action( 'elementor/frontend/section/after_render', [ $this, 'wrap_end' ], 20 );
		add_action( 'elementor/frontend/section/before_render', [ $this, 'before_render' ], 5 );
		add_action( 'elementor/element/section/section_advanced/before_section_end', [ $this, 'add_section_controls' ], 10, 2 );
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ $this, 'add_jarallax_controls' ], 10, 2 );
		add_filter( 'elementor/section/print_template', [ $this, 'print_template' ] );

	}

	/**
	 * Add Section Controls.
	 *
	 * @param array $element The widget.
	 * @param array $args The widget.
	 * @return void
	 */
	public function add_section_controls( $element, $args ) {
		$element->add_control(
			'container_class',
			[
				'label'       => esc_html__( 'Container CSS Classes', 'silicon-elementor' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Applied to elementor-container element. You can use additional bootstrap utility classes here.', 'silicon-elementor' ),
			]
		);
	}

	/**
	 * Add Jarallax Controls.
	 *
	 * @param array $element The widget.
	 * @param array $args The widget.
	 * @return void
	 */
	public function add_jarallax_controls( $element, $args ) {
		$element->start_controls_section(
			'_section_jarallax',
			[
				'label' => esc_html__( 'Jarallax', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_control(
			'enable_jarallax',
			[
				'label'   => esc_html__( 'Enable Jarallax', 'silicon-elementor' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
			]
		);

		$element->add_control(
			'jarallax_bg',
			[
				'label'     => esc_html__( 'Background', 'silicon-elementor' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => [
					'enable_jarallax' => 'yes',
				],
			]
		);

		$element->add_control(
			'separate_wrapper',
			[
				'label'     => esc_html__( 'Separate Wrapper for Background', 'silicon-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'condition' => [
					'enable_jarallax' => 'yes',
				],
			]
		);

		$element->add_control(
			'jarallax_speed',
			[
				'label'     => esc_html__( 'Speed', 'silicon-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => '',
				'min'       => 0,
				'step'      => 0.1,
				'condition' => [
					'enable_jarallax' => 'yes',
				],
			]
		);

		$element->add_control(
			'data_type',
			[
				'label'     => esc_html__( 'Data Type', 'silicon-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'condition' => [
					'enable_jarallax' => 'yes',
				],
			]
		);

		$element->add_control(
			'enable_jarallax_overlay',
			[
				'label'     => esc_html__( 'Enable Overlay', 'silicon-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'condition' => [
					'enable_jarallax' => 'yes',
				],
			]
		);

		$element->add_control(
			'section_overlay_opacity ',
			[
				'label'     => __( 'Overlay Opacity', 'silicon-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					],
				],
				'default'   => [ 'size' => 0.85 ],
				'selectors' => [
					'.jarallax .section-overlay-opacity' => 'opacity: {{SIZE}};',
				],
				'condition' => [ 'enable_jarallax_overlay' => 'yes' ],
			]
		);

		$element->add_control(
			'jarallax_style',
			[
				'label'       => esc_html__( 'Style', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'description' => esc_html__( 'Enter the style that you want to be inlined to .jarallax element', 'silicon-elementor' ),
				'condition'   => [
					'enable_jarallax' => 'yes',
				],
			]
		);

		$element->add_control(
			'jarallax_css',
			[
				'label'       => esc_html__( 'CSS Classes', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'dynamic'     => [
					'active' => true,
				],
				'title'       => esc_html__( 'Add your custom class WITHOUT the dot. e.g: my-class', 'silicon-elementor' ),
				'description' => esc_html__( 'Applied to .jarallax element', 'silicon-elementor' ),
				'condition'   => [
					'enable_jarallax' => 'yes',
				],
			]
		);

		$element->add_control(
			'wrapper_css',
			[
				'label'       => esc_html__( 'CSS Classes', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'dynamic'     => [
					'active' => true,
				],
				'title'       => esc_html__( 'Add your custom class WITHOUT the dot. e.g: my-class', 'silicon-elementor' ),
				'description' => esc_html__( 'Applied to the wrapper of .jarallax element', 'silicon-elementor' ),
				'condition'   => [
					'separate_wrapper' => 'yes',
				],
			]
		);

		$element->end_controls_section();
	}

	/**
	 * Wrap Start.
	 *
	 * @param array $section The widget.
	 *
	 * @return void
	 */
	public function wrap_start( $section ) {
		$settings     = $section->get_settings_for_display();
		$has_jarallax = 'yes' === $settings['enable_jarallax'] ? true : false;

		if ( $has_jarallax ) :

			if ( 'yes' === $settings['separate_wrapper'] ) {

				$section->add_render_attribute( 'jarallax_wrapper', 'class', 'position-relative' );
				$section->add_render_attribute( 'jarallax_div', 'class', 'position-absolute' );

				if ( ! empty( $settings['wrapper_css'] ) ) {
					$section->add_render_attribute( 'jarallax_wrapper', 'class', $settings['wrapper_css'] );
				}
				?><div <?php $section->print_render_attribute_string( 'jarallax_wrapper' ); ?>>
				<?php
			}

			$section->add_render_attribute( 'jarallax_div', 'class', 'jarallax' );

			if ( ! empty( $settings['jarallax_css'] ) ) {
				$section->add_render_attribute( 'jarallax_div', 'class', $settings['jarallax_css'] );
			}

			if ( ! empty( $settings['jarallax_speed'] ) ) {
				$section->add_render_attribute( 'jarallax_div', 'data-speed', $settings['jarallax_speed'] );
			}

			if ( ! empty( $settings['jarallax_style'] ) ) {
				$section->add_render_attribute( 'jarallax_div', 'style', $settings['jarallax_style'] );
			}

			if ( 'yes' === $settings['data_type'] ) {
				$section->add_render_attribute( 'jarallax_div', 'data-type', 'scale-opacity' );
			}

			?>
			<div <?php $section->print_render_attribute_string( 'jarallax_div' ); ?> data-jarallax >
			<?php
			if ( isset( $settings['jarallax_bg']['url'] ) && ! empty( $settings['jarallax_bg']['url'] ) ) {
				?>
				
				<?php if ( 'yes' === $settings['enable_jarallax_overlay'] ) : ?>
					<span class="position-absolute top-0 start-0 w-100 h-100 bg-gradient-dark-translucent"></span>
				<?php endif; ?>
				
				<div class="jarallax-img" style="background-image: url(<?php echo esc_attr( $settings['jarallax_bg']['url'] ); ?>);"></div>

				<?php

			}

			if ( 'yes' === $settings['separate_wrapper'] ) {
				?>
				</div><!-- /.jarallax -->
				<?php
			}

		endif;
	}

	/**
	 * Wrap End.
	 *
	 * @param array $section The widget.
	 *
	 * @return void
	 */
	public function wrap_end( $section ) {
		$settings     = $section->get_settings_for_display();
		$has_jarallax = 'yes' === $settings['enable_jarallax'] ? true : false;

		if ( $has_jarallax ) :
			?>
			</div><!-- /.custom-wrap -->
			<?php
		endif;
	}

	/**
	 * Before Render.
	 *
	 * @param array $element The widget.
	 *
	 * @return void
	 */
	public function before_render( $element ) {

		$settings        = $element->get_settings();
		$container_class = $settings['gap'];

		if ( 'no' === $settings['gap'] ) {
			$container_class = $settings['gap'] . ' no-gutters';
		}

		if ( isset( $settings['container_class'] ) && ! empty( $settings['container_class'] ) ) {
			$container_class .= ' ' . $settings['container_class'];
		}

		if ( ! empty( $container_class ) ) {
			$element->set_settings( 'gap', $container_class );
		}
		$this->wrap_start( $element );

	}

	/**
	 * Print Template.
	 *
	 * @return string
	 */
	public function print_template() {
		ob_start();
			$this->content_template();
		return ob_get_clean();
	}

	/**
	 * Content Template.
	 *
	 * @return void
	 */
	public function content_template() {
		?>
		<#
		if ( settings.background_video_link ) {
			let videoAttributes = 'autoplay muted playsinline';

			if ( ! settings.background_play_once ) {
				videoAttributes += ' loop';
			}

			view.addRenderAttribute( 'background-video-container', 'class', 'elementor-background-video-container' );

			if ( ! settings.background_play_on_mobile ) {
				view.addRenderAttribute( 'background-video-container', 'class', 'elementor-hidden-phone' );
			}
		#>
			<div {{{ view.getRenderAttributeString( 'background-video-container' ) }}}>
				<div class="elementor-background-video-embed"></div>
				<video class="elementor-background-video-hosted elementor-html5-video" {{ videoAttributes }}></video>
			</div>
		<# }
			if ( 'no' == settings.gap ) {
				settings.gap = `${ settings.gap } no-gutters`;
			}

			if ( '' != settings.container_class ) {
				settings.gap = `${ settings.gap } ${ settings.container_class }`;
			}
		#>
		<div class="elementor-background-overlay"></div>
		<div class="elementor-shape elementor-shape-top"></div>
		<div class="elementor-shape elementor-shape-bottom"></div>
		<div class="elementor-container elementor-column-gap-{{ settings.gap }}">
			<?php if ( ! Plugin::$instance->experiments->is_feature_active( 'e_dom_optimization' ) ) { ?>
				<div class="elementor-row"></div>
			<?php } ?>
		</div>
		<?php
	}
}
