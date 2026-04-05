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
 * The Grid Skin for Image Gallery widget class
 */
class Skin_Image_Gallery extends Skin_Base {
	/**
	 * Get the ID of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'si-silicon-gallery';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'About Grid', 'silicon-elementor' );
	}

	/**
	 * Register skin controls actions.
	 */
	protected function _register_controls_actions() { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
		add_filter( 'silicon-elementor/widget/image-gallery/print_template', [ $this, 'skin_print_template' ], 10, 2 );
		add_action( 'elementor/element/image-gallery/section_caption/after_section_end', [ $this, 'update_controls' ], 10 );
	}

	/**
	 * Update controls for removing column and caption controls.
	 *
	 * @param Elementor\Widget_Base $widget The Basic gallery widget.
	 */
	public function update_controls( Elementor\Widget_Base $widget ) {
		$this->parent       = $widget;
		$update_control_ids = [ 'gallery_rand', 'gallery_columns', 'section_caption' ];

		foreach ( $update_control_ids as $update_control_id ) {
			$widget->update_control(
				$update_control_id,
				[
					'condition' => [
						'_skin!' => [ 'si-silicon-gallery', 'si-profile', 'si-image-grid-parallax', 'si-parallax-gfx' ],
					],
				]
			);
		}

		$widget->update_control(
			'open_lightbox',
			[
				'condition' => [
					'_skin!'       => [ 'si-silicon-gallery', 'si-profile', 'si-parallax-gfx' ],
					'gallery_link' => 'file',
				],
			]
		);

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => 'wp_gallery',
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'gallery_index',
			[
				'label'       => esc_html__( 'Index', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '1',
				'description' => esc_html__( 'Specify the index of the gallery item for which you want the repeater settings to apply', 'silicon-elementor' ),
			]
		);

		$repeater->add_control(
			'gallery_video_url',
			[
				'label'       => esc_html__( 'Video URL', 'silicon-elementor' ),
				'type'        => Controls_Manager::URL,
				'description' => esc_html__( 'Specify the video URL if you want the gallery item links to a video.', 'silicon-elementor' ),
				'placeholder' => esc_html__( 'https://your-link.com', 'silicon-elementor' ),
				'default'     => [
					'url'               => '',
					'is_external'       => true,
					'nofollow'          => true,
					'custom_attributes' => '',
				],
			]
		);

		$this->add_control(
			'gallery_settings',
			[
				'label'       => esc_html__( 'Additional Gallery Image Settings', 'silicon-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => 'Gallery Image: {{{ gallery_index }}}',
			]
		);

		$this->add_control(
			'caption_source',
			[
				'label'   => esc_html__( 'Caption', 'silicon-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'none'       => esc_html__( 'None', 'silicon-elementor' ),
					'attachment' => esc_html__( 'Attachment Caption', 'silicon-elementor' ),
					'custom'     => esc_html__( 'Custom Caption', 'silicon-elementor' ),
				],
				'default' => 'attachment',
			]
		);

		$this->add_control(
			'caption_name',
			[
				'label'       => esc_html__( 'Custom Caption', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => esc_html__( 'Enter your image caption', 'silicon-elementor' ),
				'condition'   => [
					$this->get_control_id( 'caption_source' ) => 'custom',
				],
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$this->parent->end_injection();

		$this->parent->start_injection(
			[
				'at' => 'before',
				'of' => 'section_gallery_images',
			]
		);

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'widget_wrap',
			[
				'label'       => esc_html__( 'Widget wrap Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'pb-3 mb-3', 'silicon-elementor' ),
				'description' => esc_html__( 'CSS Classes added to <div>', 'silicon-elementor' ),

			]
		);

		$this->add_control(
			'title_class',
			[
				'label'       => esc_html__( 'Title Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'text-light mb-1', 'silicon-elementor' ),
				'description' => esc_html__( 'CSS Classes added to <h3>', 'silicon-elementor' ),

			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Title Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .silicon_wp_gallery__title' => 'color: {{VALUE}} !important',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .silicon_wp_gallery__title',
			]
		);

		$this->add_control(
			'desc_class',
			[
				'label'       => esc_html__( 'Description Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'separator'   => 'before',
				'default'     => esc_html__( 'mb-0', 'silicon-elementor' ),
				'description' => esc_html__( 'CSS Classes added to <p>', 'silicon-elementor' ),

			]
		);

		$this->add_control(
			'desc_color',
			[
				'label'     => esc_html__( 'Description Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .silicon_wp_gallery__desc' => 'color: {{VALUE}} !important',
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
				'selector' => '{{WRAPPER}} .silicon_wp_gallery__desc',
			]
		);

		$this->add_control(
			'caption_class',
			[
				'label'       => esc_html__( 'Caption Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'gallery-item-caption fs-sm fw-medium', 'silicon-elementor' ),
				'separator'   => 'before',
				'description' => esc_html__( 'CSS Classes added to <div>', 'silicon-elementor' ),

			]
		);

		$this->add_control(
			'caption_color',
			[
				'label'     => esc_html__( 'Caption Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .silicon_wp_gallery__caption' => 'color: {{VALUE}} !important',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'caption_typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .silicon_wp_gallery__caption',
			]
		);

		$this->end_controls_section();

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
				'caption_source',
				'caption_name',
				'title_class',
				'desc_class',
				'caption_class',
				'title',
				'widget_wrap',
				'gallery_settings',
			];

			$skin_settings = [];

			foreach ( $skin_control_ids as $skin_control_id ) {
				$skin_settings[ $skin_control_id ] = $this->get_instance_value( $skin_control_id );
			}
			?><div class="gallery row g-4 <?php echo esc_html( $skin_settings['widget_wrap'] ); ?>" data-thumbnails="true">
		<?php

		foreach ( $settings['wp_gallery'] as $count => $slide ) {
			if ( ! isset( $slide['id'] ) && isset( $slide['url'] ) ) {
				$slide['id'] = attachment_url_to_postid( $slide['url'] );
			}

			$caption = '';
			if ( ! empty( $skin_settings['caption_source'] ) ) {
				switch ( $skin_settings['caption_source'] ) {
					case 'attachment':
						$caption = wp_get_attachment_caption( $slide['id'] );
						break;
					case 'custom':
						$caption = ! empty( $skin_settings['caption_name'] ) ? $skin_settings['caption_name'] : '';
				}
			}

			$a_class = [ 'gallery-item', 'rounded-3', 'silicon-anchor-link' ];
			$link    = '#';
			if ( $settings['gallery_link'] ) {
				if ( 'none' === $settings['gallery_link'] ) {
					$src = wp_get_attachment_image_src( $slide['id'], $settings['thumbnail_size'] );
					if ( isset( $src[0] ) ) {
						$link = $src[0];
					}
				} else {
					$link = wp_get_attachment_url( $slide['id'], $settings['thumbnail_size'] );
				}
			} else {
				$link = wp_get_attachment_url( $slide['id'], $settings['thumbnail_size'] );
			}

			if ( 1 === $count % 5 || 3 === $count % 5 ) {
				$a_class[] = 'mb-4';
			}

			$sub_html = $caption;
			if ( 0 === $count % 5 ) {
				$image_attachment = get_post( $slide['id'] );
				$description      = apply_filters( 'the_content', $image_attachment->post_content );
				if ( ! empty( $description ) ) {
					$sub_html .= $description;
				}
			}

			$widget->add_render_attribute( 'a_link' . $count, 'class', $a_class );

			foreach ( $skin_settings['gallery_settings'] as $gallery ) {
				if ( isset( $gallery['gallery_index'] ) && $count == $gallery['gallery_index'] && ! empty( $gallery['gallery_video_url']['url'] ) ) {
					$widget->add_render_attribute( 'a_link' . $count, 'class', 'video-item is-hovered' );
					$link = $gallery['gallery_video_url']['url'];
				}
			}

			$widget->add_render_attribute( 'a_link' . $count, 'href', $link );

			$widget->add_render_attribute( 'a_link' . $count, 'data-elementor-open-lightbox', 'no' );

			if ( ! empty( trim( $sub_html ) ) ) {
				$widget->add_render_attribute( 'a_link' . $count, 'data-sub-html', '<h6 class="fs-sm text-light">' . esc_attr( $sub_html ) . '</h6>' );
			}

			if ( $skin_settings['title_class'] ) {
				$title_class = $skin_settings['title_class'];
				$widget->add_render_attribute( 'title_caption' . $count, 'class', [ 'silicon_wp_gallery__title', $title_class ] );
			}

			if ( $skin_settings['desc_class'] ) {
				$desc_class = $skin_settings['desc_class'];
				$widget->add_render_attribute( 'description' . $count, 'class', [ 'silicon_wp_gallery__desc', 'mb-0-last-child', $desc_class ] );
			}

			if ( $skin_settings['caption_class'] ) {
				$caption_class = $skin_settings['caption_class'];
				$widget->add_render_attribute( 'caption' . $count, 'class', [ 'silicon_wp_gallery__caption', $caption_class ] );
			}

			if ( 0 === $count % 5 ) :
				?>
				<div class="col-md-5">
				<?php
			endif;
			if ( 1 === $count % 5 ) :
				?>
				<div class="col-md-3 col-sm-5">
				<?php
			endif;
			if ( 3 === $count % 5 ) :
				?>
				<div class="col-md-4 col-sm-7">
			<?php endif; ?>

					<a <?php $widget->print_render_attribute_string( 'a_link' . $count ); ?>>
						<?php
						$image_html = wp_get_attachment_image( $slide['id'], $settings['thumbnail_size'] );
							echo wp_kses_post( $image_html );
						?>
						<?php if ( 0 === $count % 5 ) : ?>
							<div class="gallery-item-caption p-4">
								<h4 <?php $widget->print_render_attribute_string( 'title_caption' . $count ); ?>><?php echo wp_kses_post( $caption ); ?></h4>
								<?php if ( ! empty( $description ) ) : ?>
								<div <?php $widget->print_render_attribute_string( 'description' . $count ); ?>><?php echo wp_kses_post( $description ); ?></div>
								<?php endif; ?>
							</div>
						<?php else : ?>
							<div  <?php $widget->print_render_attribute_string( 'caption' . $count ); ?>><?php echo wp_kses_post( $caption ); ?></div>
						<?php endif; ?>
					</a>

				<?php if ( 0 === $count % 5 || 2 === $count % 5 || 4 === $count % 5 ) : ?>
				</div>
					<?php
				endif;
				$count++;
				?>
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
