<?php
namespace SiliconElementor\Modules\ImageGallery\Skins;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor;
use Elementor\Skin_Base;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use SiliconElementor\Plugin;
use SiliconElementor\Core\Utils as SN_Utils;
use Elementor\Repeater;

/**
 * The Grid Skin for Parallax GFX widget class
 */
class Skin_Parallax_Gfx extends Skin_Base {
	/**
	 * Get the ID of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'si-parallax-gfx';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Parallax GFX', 'silicon-elementor' );
	}

	/**
	 * Register skin controls actions.
	 */
	protected function _register_controls_actions() { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
		add_filter( 'silicon-elementor/widget/image-gallery/print_template', [ $this, 'skin_print_template' ], 10, 2 );
		add_action( 'elementor/element/image-gallery/section_gallery/after_section_end', [ $this, 'update_section_gallery_controls' ], 10 );
		add_action( 'elementor/element/image-gallery/section_gallery_images/after_section_end', [ $this, 'remove_style_controls' ], 10 );
	}

	/**
	 * Update controls for removing column and caption controls.
	 *
	 * @param Elementor\Widget_Base $widget The Basic gallery widget.
	 */
	public function update_section_gallery_controls( $widget ) {

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => 'wp_gallery',
			]
		);
		$repeater = new Repeater();

		$repeater->add_control(
			'data_depth',
			[
				'label' => esc_html__( 'Data Depth Value', 'silicon-elementor' ),
				'type'  => Controls_Manager::TEXT,
			]
		);

		$repeater->add_control(
			'index_class',
			[
				'label' => esc_html__( 'CSS Classes', 'silicon-elementor' ),
				'type'  => Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'slides',
			[
				'label'   => esc_html__( 'Data Attribute', 'silicon-elementor' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => $this->get_repeater_defaults(),
			]
		);
		$this->parent->end_injection();
	}


	/**
	 * Update controls for removing column and caption controls.
	 *
	 * @param Elementor\Widget_Base $widget The Basic gallery widget.
	 */
	public function remove_style_controls( $widget ) {

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => 'section_gallery_images',
			]
		);
		$this->add_control(
			'parallax_wrap_css',
			[
				'label' => esc_html__( 'Wrapper CSS Class', 'silicon-elementor' ),
				'type'  => Controls_Manager::TEXT,
			]
		);

		$this->parent->end_injection();
	}

	/**
	 * Slider defaults.
	 *
	 * @return string
	 */
	protected function get_repeater_defaults() {

		return [
			[
				'data_depth' => -0.15,
			],
			[
				'data_depth' => 0.12,
			],
			[
				'data_depth' => -0.12,
			],
		];
	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	public function render() {
		$widget   = $this->parent;
		$settings = $widget->get_settings_for_display();

		$skin_control_ids = [
			'slides',
			'parallax_wrap_css',
		];

		$skin_settings = [];

		foreach ( $skin_control_ids as $skin_control_id ) {
			$skin_settings[ $skin_control_id ] = $this->get_instance_value( $skin_control_id );
		}

		$wrap_class = [ 'parallax' ];
		if ( $skin_settings['parallax_wrap_css'] ) {
			$wrap_class[] = $skin_settings['parallax_wrap_css'];
		}
		$widget->add_render_attribute( 'parallax_wrap', 'class', $wrap_class );
		?><div <?php $widget->print_render_attribute_string( 'parallax_wrap' ); ?>>
				<?php
				foreach ( $settings['wp_gallery'] as $count => $slide ) {
					$widget->add_render_attribute(
						'data_depth_attribute-' . $count,
						[
							'class' => 'parallax-layer',

						]
					);
					foreach ( $skin_settings['slides'] as $data_count => $data_slide ) {
						if ( $count === $data_count && ( ! empty( $data_slide['data_depth'] ) || ! empty( $data_slide['index_class'] ) ) ) {
							if ( ! empty( $data_slide['data_depth'] ) ) {
								$widget->add_render_attribute(
									'data_depth_attribute-' . $count,
									[
										'data-depth' => $data_slide['data_depth'],
									]
								);
							}
							if ( ! empty( $data_slide['index_class'] ) ) {
								$widget->add_render_attribute(
									'data_depth_attribute-' . $count,
									[
										'class' => $data_slide['index_class'],
									]
								);
							}
							break;
						} else {
							continue;
						}
					}

					?>
					<div <?php $widget->print_render_attribute_string( 'data_depth_attribute-' . $count ); ?>>
					<?php
					if ( ! isset( $slide['id'] ) && isset( $slide['url'] ) ) {
						$slide['id'] = attachment_url_to_postid( $slide['url'] );
					}
						$image_html = wp_get_attachment_image( $slide['id'], $settings['thumbnail_size'] );
							echo wp_kses_post( $image_html );
					?>
					</div>
					<?php
				}
				?>
		</div>
		<?php

	}

	/**
	 * Skin_print_template to displaying live preview.
	 *
	 * @param array $content display content.
	 * @param array $image_gallery display content.
	 */
	public function skin_print_template( $content, $image_gallery ) {

		if ( 'image-gallery' === $image_gallery->get_name() ) {
			return '';
		}

		return $content;
	}
}
