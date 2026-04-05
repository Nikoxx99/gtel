<?php

namespace SiliconElementor\Modules\Sidebar;

use SiliconElementor\Base\Module_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The Sidebar module class.
 */
class Module extends Module_Base {

	/**
	 * Return the name of the module.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'override-sidebar';
	}

	/**
	 * Initialize the column module object.
	 */
	public function __construct() {
		parent::__construct();
		$this->add_actions();
	}

	/**
	 * Add actions to override the sidebar widget.
	 */
	public function add_actions() {
		add_action( 'elementor/element/sidebar/section_sidebar/before_section_end', array( $this, 'add_sidebar_controls' ), 10, 2 );
		add_filter( 'elementor/widget/render_content', array( $this, 'wrap_offcanvas' ), 10, 2 );
	}

	/**
	 * Adds a canvas toggler control to the sidebar widget controls.
	 *
	 * @param \Elementor\Controls_Stack $element    The element stack.
 	 * @param array                     $args       Section arguments.
 	 */
	public function add_sidebar_controls( $element, $args ) {
		$element->add_control(
			'is_offcanvas', [
				'label'     => esc_html__( 'Off-Canvas?', 'silicon-elementor' ),
				'description' => esc_html__( 'Should show this sidebar off-canvas in mobile view?', 'silicon-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Yes', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'No', 'silicon-elementor' ),
			]
		);
	}

	/**
	 * Wrap sidebar for offcanvas setting.
	 *
	 * @param string                 $widget_content The widget HTML output.
 	 * @param \Elementor\Widget_Base $widget         The widget instance.
 	 * @return string
 	 */
	public function wrap_offcanvas( $widget_content, $widget ) {
		
		if ( 'sidebar' === $widget->get_name() ) {
			$settings = $widget->get_settings();

			if ( 'yes' === $settings['is_offcanvas'] ) {

				$toggle = '<button type="button" data-bs-toggle="offcanvas" data-bs-target="#blog-sidebar" aria-controls="blog-sidebar" class="btn btn-sm btn-primary fixed-bottom d-lg-none w-100 rounded-0"><i class="bx bx-sidebar fs-xl me-2"></i>' . esc_html__( 'Sidebar', 'silicon' ) . '</button>';
				$before = '<div class="offcanvas offcanvas-end offcanvas-expand-lg" id="blog-sidebar" tabindex="-1"><div class="offcanvas-header border-bottom"><h3 class="offcanvas-title fs-lg">' . esc_html__( 'Sidebar', 'silicon' ) . '</h3><button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button></div><div class="offcanvas-body">';
				$after  = '</div></div>';

				$widget_content = $toggle . $before . $widget_content . $after;
			}
		}

		return $widget_content;
	}
}
