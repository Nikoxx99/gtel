<?php
namespace SiliconElementor\Modules\Logo\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Utils;
use Elementor\Control_Media;
use SiliconElementor\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Logo Widget
 */
class Logo extends Base_Widget {

	/**
	 * Get widget name.
	 *
	 * Retrieve logo widget name.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'si-logo';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve logo widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Logo', 'silicon-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve logo widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-logo';
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 1.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'logo' ];
	}

	/**
	 * Register Controls.
	 *
	 * @return void
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_header',
			[
				'label' => esc_html__( 'Logo', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'logo_wrap_class',
			[
				'label'   => esc_html__( 'Logo Wrap CSS', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'logo_image',
			[
				'label'   => esc_html__( 'Choose Image', 'silicon-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'logo_image_class',
			[
				'label'   => esc_html__( 'Image CSS', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'logo_image_width',
			[
				'label'      => esc_html__( 'Width', 'silicon-elementor' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => '%',
					'size' => 60,
				],
				'selectors'  => [
					'{{WRAPPER}} .si-width' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'logo_title',
			[
				'label'   => esc_html__( 'Title', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Silicon', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'logo_title_class',
			[
				'label'   => esc_html__( 'Title CSS', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'fs-4',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render Logo widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$wrap_class = [ 'navbar-brand text-dark mb-lg-4 si-logo__wrap' ];
		if ( $settings['logo_wrap_class'] ) {
			$wrap_class[] = $settings['logo_wrap_class'];
		}

		$title_class = [ 'si-logo__title' ];
		if ( $settings['logo_title_class'] ) {
			$title_class[] = $settings['logo_title_class'];
		}

		if ( empty( $settings['logo_image']['url'] ) ) {
			return;
		}

		$this->add_render_attribute(
			'logo_image',
			array(
				'class' => $settings['logo_image_class'],
				'src'   => $settings['logo_image']['url'],
				'width' => $settings['logo_image_width']['size'],
				'alt'   => Control_Media::get_image_alt( $settings['logo_image'] ),
			)
		);
		$this->add_render_attribute( 'logo_wrap_class', 'class', $wrap_class );
		$this->add_render_attribute( 'logo_title_class', 'class', $title_class );
		?>
		<div <?php $this->print_render_attribute_string( 'logo_wrap_class' ); ?>>
			<img <?php $this->print_render_attribute_string( 'logo_image' ); ?>>
			<span <?php $this->print_render_attribute_string( 'logo_title_class' ); ?>><?php echo esc_html( $settings['logo_title'] ); ?></span>
		</div>
		<?php
	}
}
