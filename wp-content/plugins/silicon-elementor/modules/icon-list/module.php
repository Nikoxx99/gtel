<?php

namespace SiliconElementor\Modules\IconList;

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
	 * Action Hooks.
	 *
	 * @return void
	 */
	public function add_actions() {
		add_action( 'elementor/element/icon-list/section_icon/before_section_end', [ $this, 'add_css_classes_controls' ], 10 );
		add_action( 'silicon-elementor/widget/icon-list/before_render_content', [ $this, 'before_render' ], 20 );
	}

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'override-icon-list';
	}

	/**
	 * Before Render.
	 *
	 * @param array $element The widget.
	 * @return void
	 */
	public function add_css_classes_controls( $element ) {

		$element->add_control(
			'list_item_wrap',
			[
				'label' => esc_html__( 'List item Wrap', 'silicon-elementor' ),
				'type'  => Controls_Manager::TEXT,
				'title' => esc_html__( 'Add your custom class for <ul> tag  without the dot. e.g: my-class', 'silicon-elementor' ),
			]
		);

		$element->add_control(
			'list_item_class',
			[
				'label' => esc_html__( 'List item Class', 'silicon-elementor' ),
				'type'  => Controls_Manager::TEXT,
				'title' => esc_html__( 'Add your custom class for <li> tag  without the dot. e.g: my-class', 'silicon-elementor' ),
			]
		);

		$element->add_control(
			'icon_classes',
			[
				'label' => esc_html__( 'Icon Class', 'silicon-elementor' ),
				'type'  => Controls_Manager::TEXT,
				'title' => esc_html__( 'Additional CSS class that you want to apply to the <i> tag', 'silicon-elementor' ),
			]
		);

		$element->add_control(
			'anchor_classes',
			[
				'label' => esc_html__( 'Anchor Class', 'silicon-elementor' ),
				'type'  => Controls_Manager::TEXT,
				'title' => esc_html__( 'Additional CSS class that you want to apply to the <i> tag', 'silicon-elementor' ),
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
		$count    = count( $settings['icon_list'] );

		if ( ! empty( $settings['list_item_wrap'] ) ) {
			$widget->add_render_attribute( 'icon_list', 'class', $settings['list_item_wrap'] );
		}

		if ( ! empty( $settings['list_item_class'] ) ) {
			$widget->add_render_attribute( 'list_item', 'class', $settings['list_item_class'] );
		}

		for ( $i = 0; $i < $count; $i++ ) {

			$icon_classes = $settings['icon_list'][ $i ]['selected_icon']['value'];

			if ( isset( $settings['icon_classes'] ) && ! empty( $settings['icon_classes'] ) ) {
				$settings['icon_list'][ $i ]['selected_icon']['value'] = $icon_classes . ' ' . $settings['icon_classes'];
			}
		}

		foreach ( $settings['icon_list'] as $index => $item ) :
			$link_key = 'link_' . $index;
			$widget->add_render_attribute( $link_key, 'class', $settings['anchor_classes'] );
		endforeach;

		$widget->set_settings(
			'icon_list',
			$settings['icon_list']
		);
	}

	/**
	 * Before Render.
	 *
	 * @param array $widget The widget.
	 * @return void
	 */
	public function render_content_css( $widget ) {

		$settings = $widget->get_settings_for_display();

		if ( ! empty( $settings['list_item_wrap'] ) ) {
			$widget->add_render_attribute( 'icon_list', 'class', $settings['list_item_wrap'] );
		}

		if ( ! empty( $settings['list_item_class'] ) ) {
			$widget->add_render_attribute( 'list_item', 'class', $settings['list_item_class'] );
		}

	}
}
