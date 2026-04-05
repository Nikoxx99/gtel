<?php
namespace SiliconElementor\Modules\Breadcrumb\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use SiliconElementor\Base\Base_Widget;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Breadcrumb Widget
 */
class Breadcrumb extends Base_Widget {
	/**
	 * Get widget name.
	 *
	 * Retrieve button widget name.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'si-silicon-breadcrumb';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve button widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Breadcrumb', 'silicon-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve button widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-product-breadcrumbs';
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
		return [ 'shop', 'store', 'breadcrumbs', 'internal links', 'product' ];
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
				'label' => esc_html__( 'Section', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'nav_class',
			[
				'label'   => esc_html__( 'Nav Class', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'container py-4 mb-2 my-lg-3 ', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'ol_class',
			[
				'label'   => esc_html__( 'Ordered List Class', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
			]
		);

		$this->end_controls_section();
	}
	/**
	 * Render the widget.
	 */
	protected function render() {

		$settings  = $this->get_settings_for_display();
		$nav_class = 'container pt-4 mt-lg-3';
		if ( ! empty( $settings ) ) {
			$nav_class = $settings['nav_class'];
		}

		$ol_class = ! empty( $settings['ol_class'] ) ? ' ' . $settings['ol_class'] : '';

		$args = array(
			'wrap_before' => '<nav aria-label="breadcrumb" class="' . $nav_class . '"><ol class="breadcrumb mb-0' . $ol_class . '">',
		);
		silicon_breadcrumb( $args );
	}

}
