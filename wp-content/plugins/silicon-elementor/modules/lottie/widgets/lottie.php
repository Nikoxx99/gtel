<?php
namespace SiliconElementor\Modules\Lottie\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use SiliconElementor\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The Lottie Elementor widget.
 */
class Lottie extends Base_Widget {

	/**
	 * Get element name.
	 *
	 * Retrieve the element name.
	 *
	 * @return string The name.
	 */
	public function get_name() {
		return 'lottie';
	}

	/**
	 * The title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Lottie', 'silicon-elementor' );
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
		return 'eicon-lottie';
	}

	/**
	 * Register controls for the widget.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'lottie',
			[
				'label' => esc_html__( 'Lottie', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'source_json',
			[
				'label'              => esc_html__( 'Upload JSON File', 'silicon-elementor' ),
				'type'               => Controls_Manager::MEDIA,
				'media_type'         => 'application/json',
				'frontend_available' => true,
			]
		);

		// lottie.
		$this->end_controls_section();

		$this->start_controls_section(
			'settings',
			[
				'label' => esc_html__( 'Settings', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'loop',
			[
				'label'        => esc_html__( 'Loop', 'silicon-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'render_type'  => 'none',
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label'        => esc_html__( 'Autoplay', 'silicon-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'render_type'  => 'none',
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'play_speed',
			[
				'label'       => esc_html__( 'Play Speed', 'silicon-elementor' ) . ' (x)',
				'type'        => Controls_Manager::SLIDER,
				'render_type' => 'none',
				'default'     => [
					'unit' => 'px',
					'size' => 1,
				],
				'range'       => [
					'px' => [
						'min'  => 0.1,
						'max'  => 5,
						'step' => 0.1,
					],
				],
				'size_units'  => [ 'px' ],
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		// Settings.
		$this->end_controls_section();

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
				'label'          => esc_html__( 'Max Width', 'silicon-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
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
					'{{WRAPPER}} lottie-player' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'css_class',
			[
				'label'       => esc_html__( 'Lottie CSS', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => esc_html__( 'Additional CSS to be <lottie-player> element.', 'silicon-elementor' ),
			]
		);

		// lottie style.
		$this->end_controls_section();
	}

	/**
	 * Render the widget.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$json_src = SILICON_ELEMENTOR_MODULES_URL . 'lottie/assets/animations/default.json';
		if ( ! empty( $settings['source_json']['url'] ) ) {
			$json_src = $settings['source_json']['url'];
		}

		$this->add_render_attribute( 'lottie_player', 'background', 'transparent' );
		$this->add_render_attribute( 'lottie_player', 'src', $json_src );

		if ( ! empty( $settings['play_speed'] ) ) {
			$this->add_render_attribute( 'lottie_player', 'speed', $settings['play_speed']['size'] );
		}

		if ( ! empty( $settings['loop'] ) && 'yes' === $settings['loop'] ) {
			$this->add_render_attribute( 'lottie_player', 'loop' );
		}

		if ( ! empty( $settings['autoplay'] ) && 'yes' === $settings['autoplay'] ) {
			$this->add_render_attribute( 'lottie_player', 'autoplay' );
		}

		if ( ! empty( $settings['css_class'] ) ) {
			$this->add_render_attribute( 'lottie_player', 'class', $settings['css_class'] );
		}

		?>
		<lottie-player <?php $this->print_render_attribute_string( 'lottie_player' ); ?>></lottie-player>
		<?php
	}
}
