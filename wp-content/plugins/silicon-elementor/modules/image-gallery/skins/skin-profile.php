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
 * The Grid Skin for Profile widget class
 */
class Skin_Profile extends Skin_Base {
	/**
	 * Get the ID of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'si-profile';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Profile', 'silicon-elementor' );
	}

	/**
	 * Register skin controls actions.
	 */
	protected function _register_controls_actions() { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
		add_filter( 'silicon-elementor/widget/image-gallery/print_template', [ $this, 'skin_print_template' ], 10, 2 );
		add_action( 'elementor/element/image-gallery/section_caption/after_section_end', [ $this, 'remove_controls' ], 10 );
		add_action( 'elementor/element/image-gallery/section_gallery_images/after_section_end', [ $this, 'remove_style_controls' ], 10 );
	}

	/**
	 * Update controls for removing column and caption controls.
	 *
	 * @param Elementor\Widget_Base $widget The Basic gallery widget.
	 */
	public function remove_controls( $widget ) {
		$update_control_ids = [ 'gallery_link' ];

		foreach ( $update_control_ids as $update_control_id ) {
			$widget->update_control(
				$update_control_id,
				[
					'condition' => [
						'_skin!' => [ 'si-profile', 'si-parallax-gfx' ],
					],
				]
			);
		}

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => 'wp_gallery',
			]
		);
		$this->add_control(
			'image_class',
			[
				'label'       => esc_html__( 'Image Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter Your Class', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'description',
			[
				'label'       => esc_html__( 'Description', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Enter Your Details', 'silicon-elementor' ),
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
		$update_control_ids = [ 'image_spacing', 'image_border_border', 'image_border_radius' ];

		foreach ( $update_control_ids as $update_control_id ) {
			$widget->update_control(
				$update_control_id,
				[
					'condition' => [
						'_skin!' => [ 'si-profile', 'si-parallax-gfx' ],
					],
				]
			);
		}

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => 'section_gallery_images',
			]
		);

		$this->add_control(
			'wrap_spacing',
			[
				'label'     => esc_html__( 'Wrap Spacing', 'silicon-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .si_img_wrap'        => 'width: {{SIZE}}{{UNIT}}!important;',
					'{{WRAPPER}} .si_img_wrap_height' => 'height: {{SIZE}}{{UNIT}}!important;',
				],
			]
		);

		$this->add_control(
			'image_spacing',
			[
				'label'     => esc_html__( 'Image Spacing', 'silicon-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .si_img_avatar'        => 'width: {{SIZE}}{{UNIT}}!important;',
					'{{WRAPPER}} .si_img_avatar height' => 'height: {{SIZE}}{{UNIT}}!important;',
				],
			]
		);

		$this->add_control(
			'desc_class',
			[
				'label'       => esc_html__( 'Description Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter Your Class', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'desc_color',
			[
				'label'     => esc_html__( 'Description Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si_elementor_desc' => 'color: {{VALUE}} !important',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'Description_typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .si_elementor_desc',
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
		$widget   = $this->parent;
		$settings = $widget->get_settings_for_display();

		$skin_control_ids = [
			'description',
			'image_class',
			'desc_class',
		];

		$skin_settings = [];

		foreach ( $skin_control_ids as $skin_control_id ) {
			$skin_settings[ $skin_control_id ] = $this->get_instance_value( $skin_control_id );
		}
		$desc_class = [ 'si_elementor_desc' ];

		if ( $skin_settings['desc_class'] ) {
			$desc_class = $skin_settings['desc_class'];
		}

		?><div class="d-flex align-items-center justify-content-center justify-content-lg-start text-start pb-2 pt-lg-2 pb-xl-0 pt-xl-5 mt-xxl-5 ">
			<div class="d-flex me-3">
				<?php
				foreach ( $settings['wp_gallery'] as $count => $slide ) {

					$img_wrap_class = [ 'si_img_wrap', 'si_img_wrap_height', 'd-flex', 'align-items-center', 'justify-content-center', 'bg-white', 'rounded-circle' ];

					if ( 0 !== $count ) {
						$img_wrap_class[] = 'ms-n3';
					}
					$image_class = [];

					if ( $skin_settings['image_class'] ) {
						$image_class['class'] = 'si_img_avatar si_img_avatar height ' . $skin_settings['image_class'];
					}

					$widget->add_render_attribute( 'image_wrap' . $count, 'class', $img_wrap_class );

					?>
					<div <?php $widget->print_render_attribute_string( 'image_wrap' . $count ); ?>>
					<?php
					if ( ! isset( $slide['id'] ) && isset( $slide['url'] ) ) {
						$slide['id'] = attachment_url_to_postid( $slide['url'] );
					}
					$image_html = wp_get_attachment_image( $slide['id'], $settings['thumbnail_size'], false, $image_class );
							echo wp_kses_post( $image_html );
					?>
					</div>
					<?php
				}
				$widget->add_render_attribute( 'desc', 'class', $desc_class );
				?>
			</div>
			<?php if ( ! empty( $skin_settings['description'] ) ) : ?>
			<span <?php $widget->print_render_attribute_string( 'desc' ); ?>><?php echo wp_kses_post( $skin_settings['description'] ); ?></span>
			<?php endif; ?>
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
