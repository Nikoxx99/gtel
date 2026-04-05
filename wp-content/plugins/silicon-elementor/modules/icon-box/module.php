<?php

namespace SiliconElementor\Modules\IconBox;

use SiliconElementor\Base\Module_Base;
use SiliconElementor\Modules\IconBox\Skins;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Module for Button
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
		return 'sn-icon-box';
	}

	/**
	 * Add Action.
	 *
	 * @return void
	 */
	public function add_actions() {
		add_action( 'elementor/widget/icon-box/skins_init', [ $this, 'init_skins' ], 10 );
		add_action( 'elementor/element/icon-box/section_style_icon/before_section_end', [ $this, 'add_css_classes_controls' ], 10 );
		add_action( 'silicon-elementor/widget/icon-box/before_render_content', [ $this, 'before_render' ], 10 );
	}

	/**
	 * Name.
	 *
	 * @param array $element The widget.
	 * @return void
	 */
	public function add_css_classes_controls( $element ) {

		$element->add_control(
			'icon_wrapper_class',
			[
				'label'       => esc_html__( 'Icon Wrapper Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'description' => esc_html__( 'Additional CSS class that you want to apply to the icon wrapper', 'silicon-elementor' ),
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
		$settings = $widget->get_settings_for_display();

		if ( isset( $settings['icon_wrapper_class'] ) && ! empty( $settings['icon_wrapper_class'] ) ) {
			$widget->add_render_attribute( 'icon', 'class', $settings['icon_wrapper_class'] );
		}
	}

	/**
	 * Add Action.
	 *
	 * @param array $widget The widget.
	 * @return void
	 */
	public function init_skins( $widget ) {
		$widget->add_skin( new Skins\Skin_Icon_Box( $widget ) );
		$widget->add_skin( new Skins\Skin_Icon_Box_v2( $widget ) );
	}
}
