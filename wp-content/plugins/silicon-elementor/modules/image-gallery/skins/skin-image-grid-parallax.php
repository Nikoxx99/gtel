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
 * The Grid Skin for Image Grid Parallax widget class
 */
class Skin_Image_Grid_Parallax extends Skin_Base {
	/**
	 * Get the ID of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'si-image-grid-parallax';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Image Grid Parallax', 'silicon-elementor' );
	}

	/**
	 * Register skin controls actions.
	 */
	protected function _register_controls_actions() { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
		add_filter( 'silicon-elementor/widget/image-gallery/print_template', [ $this, 'skin_print_template' ], 10, 2 );
		add_action( 'elementor/element/image-gallery/section_gallery/after_section_end', [ $this, 'update_section_gallery_controls' ], 20 );
		add_action( 'elementor/element/image-gallery/section_gallery_images/after_section_end', [ $this, 'update_section_gallery_images_styles' ], 10 );
	}

	/**
	 * Update controls for removing column and caption controls.
	 *
	 * @param Elementor\Widget_Base $widget The Basic gallery widget.
	 */
	public function update_section_gallery_controls( $widget ) {
		$update_control_ids = [ 'thumbnail_size' ];

		foreach ( $update_control_ids as $update_control_id ) {
			$widget->update_control(
				$update_control_id,
				[
					'condition' => [
						'_skin!' => 'si-image-grid-parallax',
					],
				]
			);
		}
	}

	/**
	 * Update controls for removing column and caption controls.
	 *
	 * @param Elementor\Widget_Base $widget The Basic gallery widget.
	 */
	public function update_section_gallery_images_styles( $widget ) {
		$update_control_ids = [ 'image_spacing', 'image_spacing_custom', 'image_border_radius' ];

		foreach ( $update_control_ids as $update_control_id ) {
			$widget->update_control(
				$update_control_id,
				[
					'condition' => [
						'_skin!' => [ 'si-image-grid-parallax', 'si-parallax-gfx' ],
					],
				]
			);
		}
	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	public function render() {
		$widget   = $this->parent;
		$settings = $widget->get_settings_for_display();
		$images   = $settings['wp_gallery'];

		?>
		<div class="container position-relative pt-1 pt-md-3">
		<?php
		foreach ( $images as $index => $image ) {
			$index ++;
			switch ( $index ) {
				case 1:
					$col_class             = 'col-lg-5 col-sm-6 mb-4 mb-sm-0';
					$jarallax_element      = 20;
					$disable_parallax_down = 'sm';
					$img_width             = 526;
					$img_class             = 'rounded-3';
					break;
				case 2:
					$col_class             = 'col-lg-7 col-sm-6 d-sm-flex justify-content-end pt-sm-4 pt-lg-5 mt-md-3 mb-4 mb-sm-0';
					$jarallax_element      = 40;
					$disable_parallax_down = 'sm';
					$img_width             = 416;
					$img_class             = 'rounded-3';
					break;
				case 3:
					$col_class             = 'col-lg-5 col-sm-6 d-sm-flex justify-content-lg-end pt-sm-5 mt-lg-5 mb-4 mb-sm-0';
					$jarallax_element      = 40;
					$disable_parallax_down = 'sm';
					$img_width             = 416;
					$img_class             = 'rounded-3';
					break;
				case 4:
					$col_class             = 'col-lg-7 col-sm-6 d-sm-flex justify-content-center mt-sm-n5 mb-4 mb-sm-0';
					$jarallax_element      = 10;
					$disable_parallax_down = 'sm';
					$img_width             = 526;
					$img_class             = 'd-block rounded-3 mt-xl-n5';
					break;
				case 5:
					$col_class             = 'col-lg-5 col-sm-6 offset-sm-3 offset-lg-4 mt-sm-n5';
					$jarallax_element      = -50;
					$disable_parallax_down = 'sm';
					$img_width             = 526;
					$img_class             = 'd-block rounded-3 mt-xl-n5';
					break;
			}

			$widget->add_render_attribute(
				'column-' . $index,
				[
					'class'                      => $col_class,
					'data-jarallax-element'      => $jarallax_element,
					'data-disable-parallax-down' => $disable_parallax_down,
				]
			);

			if ( $index <= 5 ) {
				if ( 1 === $index ) {
					?>
					<div class="row align-items-start">
					<?php
				}
				if ( 5 === $index ) {
					?>
					<div class="row position-sm-absolute top-50 start-0 translate-middle-sm-y w-100 d-flex mt-md-n5">
					<?php
				}
				$a_class = [ 'gallery-item', 'rounded-3', 'silicon-anchor-link' ];
				$link    = '#';
				if ( ! isset( $image['id'] ) && isset( $image['url'] ) ) {
					$image['id'] = attachment_url_to_postid( $image['url'] );
				}

				if ( $settings['gallery_link'] ) {
					if ( 'none' === $settings['gallery_link'] ) {
						$src = wp_get_attachment_image_src( $image['id'], '' );
						if ( isset( $src[0] ) ) {
							$link = $src[0];
						}
					} else {
						$link = wp_get_attachment_url( $image['id'], '' );
					}
				} else {
					$link = wp_get_attachment_url( $image['id'], '' );
				}

				if ( 'none' !== $settings['gallery_link'] ) {
					$widget->add_render_attribute( 'a_link' . $index, 'class', $a_class );
				}
				$widget->add_render_attribute( 'a_link' . $index, 'href', $link );
				$light_box = 'yes' === $settings['open_lightbox'] ? 'yes' : 'no';

				$widget->add_render_attribute( 'a_link' . $index, 'data-elementor-open-lightbox', $light_box );

				?>
				<div <?php $widget->print_render_attribute_string( 'column-' . $index ); ?>>
				<?php
				if ( 'none' !== $settings['gallery_link'] ) {
					?>
					<a <?php $widget->print_render_attribute_string( 'a_link' . $index ); ?>>
					<?php
				}
				$image_html = wp_get_attachment_image( $image['id'], array( $img_width, '' ), false, [ 'class' => $img_class ] );
				echo wp_kses_post( $image_html );
				if ( 'none' !== $settings['gallery_link'] ) {
					?>
					</a>
					<?php
				}
				?>
				</div>
				<?php
				if ( 4 === $index || 5 === $index ) {
					?>
					</div>
					<?php
				}
			}
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
