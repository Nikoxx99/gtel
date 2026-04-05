<?php

namespace SiliconElementor\Modules\Icon;

use SiliconElementor\Base\Module_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Module
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
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'override-icon';
	}

	/**
	 * Action Hooks.
	 *
	 * @return void
	 */
	public function add_actions() {
		add_action( 'elementor/element/icon/section_icon/before_section_end', [ $this, 'add_css_classes_controls' ], 10 );
		add_action( 'silicon-elementor/widget/icon/before_render_content', [ $this, 'before_render' ], 20 );
	}

	/**
	 * Before Render.
	 *
	 * @param array $element The widget.
	 * @return void
	 */
	public function add_css_classes_controls( $element ) {

		$element->add_control(
			'icon_classes',
			[
				'label' => esc_html__( 'Icon Class', 'silicon-elementor' ),
				'type'  => Controls_Manager::TEXT,
				'title' => esc_html__( 'Additional CSS class that you want to apply to the <i> tag. Does not apply to SVG icons.', 'silicon-elementor' ),
			]
		);

		$element->add_control(
			'wrap_class',
			[
				'label' => esc_html__( 'Wrap Class', 'silicon-elementor' ),
				'type'  => Controls_Manager::TEXT,
				'title' => esc_html__( 'Additional CSS class that you want to apply to the .elementor-icon element', 'silicon-elementor' ),
			]
		);
	}

	/**
	 * Before Render.
	 *
	 * @param array $widget The widget.
	 * @return void
	 */
	public function before_render( $widget ) {

		$settings = $widget->get_settings();

		$icon_classes = $settings['selected_icon']['value'];

		if ( empty( $settings['selected_icon']['value']['url'] ) && isset( $settings['icon_classes'] ) && ! empty( $settings['icon_classes'] ) ) {
				$settings['selected_icon']['value'] = $icon_classes . ' ' . $settings['icon_classes'];
		}
		if ( ! empty( $settings['selected_icon']['value']['url'] ) ) {
			$settings['selected_icon']['value'] = $icon_classes;
		}

		if ( ! empty( $settings['wrap_class'] ) ) {
			$widget->add_render_attribute( 'icon-wrapper', 'class', $settings['wrap_class'] );
		}

		$widget->set_settings(
			'selected_icon',
			$settings['selected_icon']
		);
	}
}
