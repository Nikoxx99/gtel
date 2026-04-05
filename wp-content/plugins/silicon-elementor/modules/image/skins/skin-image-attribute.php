<?php
namespace SiliconElementor\Modules\Image\Skins;

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
use Elementor\Group_Control_Image_Size;
use SiliconElementor\Plugin;
use Elementor\Utils;
use SiliconElementor\Core\Utils as SNUtils;

/**
 * Skin Image Attributes ( Jarallax and Parallax )
 */
class Skin_Image_Attribute extends Skin_Base {

	/**
	 * Constructor.
	 *
	 * @param Elementor\Widget_Base $parent The widget settings.
	 * @return void
	 */
	public function __construct( Elementor\Widget_Base $parent ) {
		parent::__construct( $parent );
		add_filter( 'elementor/widget/print_template', array( $this, 'skin_print_template' ), 10, 2 );
		add_action( 'elementor/element/image/section_image/after_section_end', [ $this, 'add_data_attribute_control_at_content_tab' ], 10 );
	}

	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'si-image-jp';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Image Jarallax', 'silicon-elementor' );
	}

	/**
	 * Added control of the Content tab.
	 */
	public function add_data_attribute_control_at_content_tab() {

		$disable_controls = [
			'inline_svg',
			'color',
		];

		foreach ( $disable_controls as $control ) {
			$this->parent->update_control(
				$control,
				[
					'condition' => [
						'_skin!' => 'si-image-jp',
					],
				]
			);
		}

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => 'image_class',
			]
		);

		$this->add_control(
			'data_jarallax',
			[
				'label'   => esc_html__( 'Data Jarallax Element', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			]
		);

		$this->add_control(
			'data_parallax',
			[
				'label'   => esc_html__( 'Data Parallax Element', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			]
		);

		$this->parent->end_injection();
	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	public function render() {

		$parent   = $this->parent;
		$settings = $parent->get_settings_for_display();

		$skin_control_ids = [
			'data_jarallax',
			'data_parallax',
		];

		$skin_settings = [];

		foreach ( $skin_control_ids as $skin_control_id ) {
			$skin_settings[ $skin_control_id ] = $this->get_instance_value( $skin_control_id );
		}

		$image_class = $settings['image_class'];
		$image_src   = $settings['image']['url'];

		$image_jarallax = $skin_settings['data_jarallax'];
		$image_parallax = $skin_settings['data_parallax'];

		$parent->add_render_attribute(
			'image_attribute',
			[
				'data-jarallax-element'      => $image_jarallax,
				'data-disable-parallax-down' => $image_parallax,
			]
		);

		$replace  = '<img ' . $parent->get_render_attribute_string( 'image_attribute' );
		$find     = '<img';
		$img_html = str_replace( $find, $replace, Group_Control_Image_Size::get_attachment_image_html( $settings ) );

		echo wp_kses_post( $img_html );
	}

	/**
	 * Skin print template.
	 *
	 * @param array $content the content.
	 * @param array $widget widget name.
	 * @return string
	 */
	public function skin_print_template( $content, $widget ) {
		if ( 'override-image' === $widget->get_name() ) {
			return '';
		}
		return $content;
	}
}
