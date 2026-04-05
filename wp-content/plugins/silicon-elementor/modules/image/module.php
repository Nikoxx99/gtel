<?php

namespace SiliconElementor\Modules\Image;

use SiliconElementor\Base\Module_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Core\Schemes;

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
		add_action( 'elementor/element/image/section_image/before_section_end', [ $this, 'add_css_classes_controls' ], 10 );
		add_filter( 'elementor/image_size/get_attachment_image_html', [ $this, 'image_html' ], 10, 4 );
		add_action( 'silicon-elementor/widget/image/before_render_content', [ $this, 'before_render' ], 10 );
		add_action( 'elementor/widget/image/skins_init', [ $this, 'init_skins' ], 10 );
		add_filter( 'elementor/widget/render_content', [ $this, 'add_figcaption_classes' ], 10, 2 );
	}

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'override-image';
	}


	/**
	 * Add Action.
	 *
	 * @param array $widget The widget.
	 * @return void
	 */
	public function init_skins( $widget ) {
		$widget->add_skin( new Skins\Skin_Image( $widget ) );
		$widget->add_skin( new Skins\Skin_Image_Attribute( $widget ) );
	}

	/**
	 * Name.
	 *
	 * @param array $element The widget.
	 * @return void
	 */
	public function add_css_classes_controls( $element ) {

		$element->add_control(
			'image_class',
			[
				'label'       => esc_html__( 'Image Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'title'       => esc_html__( 'Add your custom class for <img> tag  without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'     => '',
				'label_block' => true,
				'description' => esc_html__( 'Additional CSS class that you want to apply to the img tag', 'silicon-elementor' ),
			]
		);

		$element->add_control(
			'image_anchor_class',
			[
				'label'       => esc_html__( 'Image Anchor Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'title'       => esc_html__( 'Add your custom class for <a> tag  without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'     => '',
				'label_block' => true,
				'description' => esc_html__( 'Additional CSS class that you want to apply to the a tag', 'silicon-elementor' ),
				'condition'   => [
					'link_to' => 'custom',
				],
			]
		);

		$element->add_control(
			'caption_css',
			[
				'label'       => esc_html__( 'Caption CSS', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'title'       => esc_html__( 'Add your custom class for <figcaption> tag  without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'     => '',
				'label_block' => true,
				'description' => esc_html__( 'Additional CSS class that you want to apply to the figcaption tag', 'silicon-elementor' ),
				'condition'   => [
					'caption_source!' => 'none',
				],
			]
		);

		$enabled = Plugin::$instance->uploads_manager->are_unfiltered_uploads_enabled();

		if ( $enabled ) {
			$element->add_control(
				'inline_svg',
				[
					'label'        => esc_html__( 'Inline SVG', 'silicon-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'title'        => esc_html__( 'If you are uploading SVG file, it might be useful to inline the SVG files. Do not inline, if your SVG file is from unknown sources.', 'silicon-elementor' ),
					'label_on'     => esc_html__( 'Yes', 'silicon-elementor' ),
					'label_off'    => esc_html__( 'No', 'silicon-elementor' ),
					'return_value' => 'yes',
					'default'      => 'no',
				]
			);

			$element->add_control(
				'color',
				[
					'label'     => esc_html__( 'Color', 'silicon-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .silicon-elementor-svg-wrapper' => 'color: {{VALUE}};',
					],
				]
			);
		}
	}

	/**
	 * Before Render.
	 *
	 * @param array $widget The widget.
	 * @return void
	 */
	public function before_render( $widget ) {
		$settings = $widget->get_settings_for_display();

		if ( 'custom' === $settings['link_to'] && isset( $settings['image_anchor_class'] ) && ! empty( $settings['image_anchor_class'] ) ) {
			$widget->add_render_attribute( 'link', 'class', $settings['image_anchor_class'] );
		}
	}

	/**
	 * Before Render.
	 *
	 * @param string $html The html.
	 * @param array  $settings The settings.
	 * @param array  $image_size_key The image size key.
	 * @param array  $image_key The image key.
	 * @return string
	 */
	public function image_html( $html, $settings, $image_size_key, $image_key ) {
		$enabled = Plugin::$instance->uploads_manager->are_unfiltered_uploads_enabled();

		if ( $enabled && isset( $settings['inline_svg'] ) && 'yes' === $settings['inline_svg'] && isset( $settings['image']['url'] ) ) {

			if ( isset( $settings['image_class'] ) && ! empty( $settings['image_class'] ) ) {
				$html = '<div class="silicon-elementor-svg-wrapper ' . esc_attr( $settings['image_class'] ) . '">';
			} else {
				$html = '<div class="silicon-elementor-svg-wrapper">';
			}

			$html .= file_get_contents( $settings['image']['url'] );
			$html .= '</div>';

		} else {

			if ( isset( $settings['image_class'] ) && ! empty( $settings['image_class'] ) ) {

				if ( strpos( $html, 'class="' ) !== false ) {
					$html = str_replace( 'class="', 'class="' . esc_attr( $settings['image_class'] ) . ' ', $html );
				} else {
					$html = str_replace( '<img', '<img class="' . esc_attr( $settings['image_class'] ) . '"', $html );
				}
			}
		}

		return $html;
	}

	/**
	 * Add classes to Figcaption tag.
	 *
	 * @param string                 $widget_content The widget HTML output.
	 * @param \Elementor\Widget_Base $widget         The widget instance.
	 */
	public function add_figcaption_classes( $widget_content, $widget ) {

		if ( 'image' === $widget->get_name() ) {
			$settings = $widget->get_settings();

			if ( ! empty( $settings['caption_css'] ) ) {
				$find    = '<figcaption class="widget-image-caption wp-caption-text">';
				$replace = '<figcaption class="widget-image-caption wp-caption-text ' . esc_attr( $settings['caption_css'] ) . '">';

				$widget_content = str_replace( $find, $replace, $widget_content );
			}
		}

		return $widget_content;
	}
}
