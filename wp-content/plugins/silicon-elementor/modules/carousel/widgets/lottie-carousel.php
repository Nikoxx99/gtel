<?php
namespace SiliconElementor\Modules\Carousel\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use SiliconElementor\Modules\Carousel\Skins;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Lottie Carousel
 */
class Lottie_Carousel extends Base {

	/**
	 * Skip widget.
	 *
	 * @var bool
	 */
	protected $_has_template_content = false;

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'sn-lottie-carousel';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Lottie Carousel', 'silicon-elementor' );
	}

	/**
	 * Get the script dependencies for this widget.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return [ 'lottie-player' ];
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
		return [ 'lottie', 'carousel', 'image', 'json' ];
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
		$this->add_skin( new Skins\Skin_Lottie_Carousel( $this ) );
	}

	/**
	 * Register controls for the widget.
	 */
	protected function register_controls() {
		parent::register_controls();
		$this->start_injection(
			[
				'at' => 'after',
				'of' => 'section_slides',
			]
		);
		$this->start_controls_section(
			'style',
			[
				'label' => esc_html__( 'Lottie', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'label'          => esc_html__( 'Width', 'silicon-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => [
					'size' => 180,
					'unit' => 'px',
				],
				'tablet_default' => [
					'unit' => 'px',
				],
				'mobile_default' => [
					'unit' => 'px',
				],
				'size_units'     => [ '%', 'px', 'vw' ],
				'range'          => [
					'%'  => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'      => [
					'{{WRAPPER}} .--lottie-container-width' => 'width: {{SIZE}}{{UNIT}} important;',
				],
			]
		);

		$this->add_control(
			'play_speed',
			[
				'label'              => esc_html__( 'Play Speed', 'silicon-elementor' ) . ' (x)',
				'type'               => Controls_Manager::SLIDER,
				'render_type'        => 'none',
				'default'            => [
					'unit' => 'px',
					'size' => 1.25,
				],
				'range'              => [
					'px' => [
						'min'  => 0.1,
						'max'  => 5,
						'step' => 0.1,
					],
				],
				'size_units'         => [ 'px' ],
			]
		);
		$this->end_controls_section();
		$this->end_injection();
	}

	/**
	 * Register repeater controls for this widget.
	 *
	 * @param Repeater $repeater repeater control arguments.
	 * @return void
	 */
	protected function add_repeater_controls( Repeater $repeater ) {

		$repeater->add_control(
			'source',
			[
				'label'              => esc_html__( 'Source', 'silicon-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'media_file',
				'options'            => [
					'media_file'   => esc_html__( 'Media File', 'silicon-elementor' ),
					'external_url' => esc_html__( 'External URL', 'silicon-elementor' ),
				],
				'frontend_available' => true,
			]
		);

		$repeater->add_control(
			'light_source_external_url',
			[
				'label'              => esc_html__( 'External URL', 'silicon-elementor' ),
				'type'               => Controls_Manager::URL,
				'condition'          => [
					'source' => 'external_url',
				],
				'dynamic'            => [
					'active' => true,
				],
				'placeholder'        => esc_html__( 'Enter your URL', 'silicon-elementor' ),
				'frontend_available' => true,
			]
		);

		$repeater->add_control(
			'light_source_json',
			[
				'label'              => esc_html__( 'Upload JSON File', 'silicon-elementor' ),
				'type'               => Controls_Manager::MEDIA,
				'media_type'         => 'application/json',
				'frontend_available' => true,
				'condition'          => [
					'source' => 'media_file',
				],
			]
		);

		$repeater->add_control(
			'dark_source_external_url',
			[
				'label'              => esc_html__( 'Dark External URL', 'silicon-elementor' ),
				'type'               => Controls_Manager::URL,
				'condition'          => [
					'source' => 'external_url',
				],
				'dynamic'            => [
					'active' => true,
				],
				'placeholder'        => esc_html__( 'Enter your URL', 'silicon-elementor' ),
				'frontend_available' => true,
			]
		);

		$repeater->add_control(
			'dark_source_json',
			[
				'label'              => esc_html__( 'Upload JSON File', 'silicon-elementor' ),
				'type'               => Controls_Manager::MEDIA,
				'media_type'         => 'application/json',
				'frontend_available' => true,
				'condition'          => [
					'source' => 'media_file',
				],
			]
		);

		$repeater->add_control(
			'caption',
			[
				'label'              => esc_html__( 'Text', 'silicon-elementor' ),
				'type'               => Controls_Manager::TEXT,
				'render_type'        => 'none',
				'dynamic'            => [
					'active' => true,
				],
				'frontend_available' => true,
			]
		);

		$repeater->add_control(
			'link_to',
			[
				'label'              => esc_html__( 'Link', 'silicon-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'render_type'        => 'none',
				'default'            => 'none',
				'options'            => [
					'none'   => esc_html__( 'None', 'silicon-elementor' ),
					'custom' => esc_html__( 'Custom URL', 'silicon-elementor' ),
				],
				'frontend_available' => true,
			]
		);

		$repeater->add_control(
			'custom_link',
			[
				'label'              => esc_html__( 'Link', 'silicon-elementor' ),
				'type'               => Controls_Manager::URL,
				'render_type'        => 'none',
				'placeholder'        => esc_html__( 'Enter your URL', 'silicon-elementor' ),
				'condition'          => [
					'link_to' => 'custom',
				],
				'dynamic'            => [
					'active' => true,
				],
				'default'            => [
					'url' => '',
				],
				'show_label'         => false,
				'frontend_available' => true,
			]
		);

	}

	/**
	 * Slider defaults.
	 *
	 * @return string
	 */
	protected function get_repeater_defaults() {

		return [
			[
				'light_source_json' => [ 'url' => get_template_directory_uri() . '/assets/json/animation-feature-1-light.json' ],
				'dark_source_json'  => [ 'url' => get_template_directory_uri() . '/assets/json/animation-feature-1-dark.json' ],
				'caption'           => esc_html__( 'Light / Dark Mode', 'silicon-elementor' ),
			],
			[
				'light_source_json' => [ 'url' => get_template_directory_uri() . '/assets/json/animation-feature-2-light.json' ],
				'dark_source_json'  => [ 'url' => get_template_directory_uri() . '/assets/json/animation-feature-2-dark.json' ],
				'caption'           => esc_html__( 'Figma Files Included', 'silicon-elementor' ),
			],
			[
				'light_source_json' => [ 'url' => get_template_directory_uri() . '/assets/json/animation-feature-3-light.json' ],
				'dark_source_json'  => [ 'url' => get_template_directory_uri() . '/assets/json/animation-feature-3-dark.json' ],
				'caption'           => esc_html__( '50+ UI Flexible Components', 'silicon-elementor' ),
			],
			[
				'light_source_json' => [ 'url' => get_template_directory_uri() . '/assets/json/animation-feature-4-light.json' ],
				'dark_source_json'  => [ 'url' => get_template_directory_uri() . '/assets/json/animation-feature-4-dark.json' ],
				'caption'           => esc_html__( 'Free Lifetime Updates', 'silicon-elementor' ),
			],
		];
	}

	/**
	 * Display Carousel.
	 *
	 * @param array  $slide repeater single control arguments.
	 * @param array  $settings control arguments.
	 * @param string $element_key slider id argument.
	 * @return void
	 */
	public function print_slide( array $slide, array $settings, $element_key ) {
		$this->skin_slide_start( $settings, $element_key );
		$caption        = $slide['caption'];
		$widget_caption = $caption ? '<div class="card-body fs-lg fw-semibold text-center"> ' . esc_html( $caption ) . '</div>' : '';

		// Light Mode Render.
		if ( 'media_file' === $slide['source'] ) {
			$light_json = $slide['light_source_json']['url'];
		} else {
			$light_json = $slide['light_source_external_url']['url'];
		}
		$this->add_render_attribute(
			'light_mode-' . $element_key,
			[
				'class'      => 'd-dark-mode-none mx-auto mt-4 mb-2 --lottie-container-width',
				'background' => 'transparent',
				'speed'      => $settings['play_speed']['size'],
				'loop'       => '',
				'style'      => 'width: 180px;',
			]
		);
		$this->add_render_attribute( 'light_mode-' . $element_key, 'src', $light_json );

		// Dark Mode Render.
		if ( 'media_file' === $slide['source'] ) {
			$dark_json = $slide['dark_source_json']['url'];
		} else {
			$dark_json = $slide['dark_source_external_url']['url'];
		}
		$this->add_render_attribute(
			'dark_mode-' . $element_key,
			[
				'class'      => 'd-none d-dark-mode-block mx-auto mt-4 mb-2 --lottie-container-width',
				'background' => 'transparent',
				'speed'      => $settings['play_speed']['size'],
				'loop'       => '',
				'style'      => 'width: 180px;',
			]
		);
		$this->add_render_attribute( 'dark_mode-' . $element_key, 'src', $dark_json );

		$light_lottie   = sprintf( '<lottie-player %1$s></lottie-player>', $this->get_render_attribute_string( 'light_mode-' . $element_key ) );
		$dark_lottie    = sprintf( '<lottie-player %1$s></lottie-player>', $this->get_render_attribute_string( 'dark_mode-' . $element_key ) );
		$widget_caption = '<div class="card-body fs-lg fw-semibold text-center"> ' . esc_html( $caption ) . '</div>';
		$widget       = '';
		if ( $light_json ) {
			$widget .= $light_lottie;
		}
		if ( $dark_json ) {
			$widget .= $dark_lottie;
		}
		if ( $caption ) {
			$widget .= $widget_caption;
		}
		if ( ! empty( $slide['custom_link']['url'] ) && 'custom' === $slide['link_to'] ) {
			$this->add_link_attributes( 'url-' . $element_key, $slide['custom_link'] );
			$widget = sprintf( '<a class="e-lottie__container__link" %1$s>%2$s</a>', $this->get_render_attribute_string( 'url-' . $element_key ), $widget );
		}
		$widget_container = sprintf( '<div class="card card-hover bg-light border-0 animation-on-hover h-100 mx-2">%1$s</div>', $widget );

		// PHPCS - XSS ok. Everything that should be escaped in the way is escaped.
		echo $widget_container; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?> 
		</div>
		
		<?php
	}

}
