<?php
namespace SiliconElementor\Modules\Blockquote\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use Elementor\Utils;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use SiliconElementor\Plugin;
use SiliconElementor\Base\Base_Widget;
use SiliconElementor\Modules\Blockquote\Skins;
use SiliconElementor\Core\Utils as SN_Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly3.
}

/**
 * Widget Blockquote
 */
class Blockquote extends Base_Widget {

	/**
	 * Get the style of the widget.
	 *
	 * @return string
	 */
	public function get_style_depends() {
		if ( Icons_Manager::is_migration_allowed() ) {
			return [ 'elementor-icons-fa-brands' ];
		}

		return [];
	}

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'si-blockquote';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Blockquote', 'silicon-elementor' );
	}

	/**
	 * Get the icon for the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-blockquote';
	}


	/**
	 * Get the keyword for the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'blockquote', 'quote', 'paragraph', 'testimonial', 'text' ];
	}

	/**
	 * Register Controls.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_blockquote_content',
			[
				'label' => esc_html__( 'Blockquote', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'blockquote_content',
			[
				'label'       => esc_html__( 'Content', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'silicon-elementor' ) . esc_html__( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'silicon-elementor' ),
				'placeholder' => esc_html__( 'Enter your quote', 'silicon-elementor' ),
				'rows'        => 10,
			]
		);

		$this->add_control(
			'author_name',
			[
				'label'     => esc_html__( 'Author', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => [
					'active' => true,
				],
				'default'   => esc_html__( 'John Doe', 'silicon-elementor' ),
				'separator' => 'after',
			]
		);

		$this->add_control(
			'author_byline',
			[
				'label'     => esc_html__( 'Author Byline', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => [
					'active' => true,
				],
				'default'   => esc_html__( 'CEO of Ipsum Company', 'silicon-elementor' ),
				'separator' => 'after',
			]
		);

		$this->add_control(
			'image',
			[
				'label'   => esc_html__( 'Avatar', 'silicon-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image',
				'default'   => 'full',
				'separator' => 'none',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_style',
			[
				'label' => esc_html__( 'Content', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-blockquote__content' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'selector' => '{{WRAPPER}} .elementor-blockquote__content',
			]
		);

		$this->add_responsive_control(
			'content_gap',
			[
				'label'     => esc_html__( 'Gap', 'silicon-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .elementor-blockquote__content +footer' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'heading_author_style',
			[
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Author', 'silicon-elementor' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'author_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-blockquote__author' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'author_typography',
				'selector' => '{{WRAPPER}} .elementor-blockquote__author',
			]
		);

		$this->add_responsive_control(
			'author_gap',
			[
				'label'     => esc_html__( 'Gap', 'silicon-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-blockquote__author' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'alignment' => 'center',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_border_style',
			[
				'label' => esc_html__( 'Border', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'border_color',
			[
				'label'     => esc_html__( 'Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .blockquote__border' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'border_width',
			[
				'label'     => esc_html__( 'Width', 'silicon-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .blockquote__border' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'border_gap',
			[
				'label'     => esc_html__( 'Gap', 'silicon-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .blockquote__border' => 'margin-left: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .blockquote__border' => 'margin-right: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'border_vertical_padding',
			[
				'label'     => esc_html__( 'Vertical Padding', 'silicon-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .elementor-blockquote' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}}',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_css_classes',
			[
				'label' => esc_html__( 'CSS Classes', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'border_css',
			[
				'label'       => esc_html__( 'Border', 'silicon-elementor' ),
				'label_block' => true,
				'description' => esc_html__( 'Additional CSS classes to be applied to the border tag', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'w-3 bg-primary',
			]
		);

		$this->add_control(
			'blockquote_css',
			[
				'label'       => esc_html__( 'Blockquote', 'silicon-elementor' ),
				'label_block' => true,
				'description' => esc_html__( 'Additional CSS classes to be applied to the blockquote tag', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'ps-1 ps-sm-3',
			]
		);

		$this->add_control(
			'blockquote_content_css',
			[
				'label'       => esc_html__( 'Quote Content', 'silicon-elementor' ),
				'label_block' => true,
				'description' => esc_html__( 'Additional CSS classes to be applied to the content inside the blockquote tag', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'fs-xl fw-medium text-dark ',
			]
		);

		$this->add_control(
			'author_name_css',
			[
				'label'       => esc_html__( 'Author Name', 'silicon-elementor' ),
				'label_block' => true,
				'description' => esc_html__( 'Additional CSS classes to be applied to the author of the quote', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'h6 d-block fw-semibold lh-base mb-0',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$text = rawurlencode( $settings['blockquote_content'] );

		if ( ! empty( $settings['author_name'] ) ) {
			$text .= ' — ' . $settings['author_name'];
		}

		$this->add_render_attribute(
			[
				'blockquote'         => [ 'class' => [ 'elementor-blockquote', 'blockquote' ] ],
				'blockquote_content' => [ 'class' => 'elementor-blockquote__content' ],
				'author_name'        => [ 'class' => [ 'elementor-blockquote__author', 'fst-normal' ] ],
				'blockquote_border'  => [ 'class' => [ 'blockquote_border', 'position-absolute', 'top-0', 'start-0', 'h-100', 'blockquote__border' ] ],
			]
		);

		if ( ! empty( $settings['blockquote_css'] ) ) {
			$this->add_render_attribute( 'blockquote', 'class', $settings['blockquote_css'] );
		}

		if ( ! empty( $settings['border_css'] ) ) {
			$this->add_render_attribute( 'blockquote_border', 'class', $settings['border_css'] );
		}

		if ( ! empty( $settings['blockquote_content_css'] ) ) {
			$this->add_render_attribute( 'blockquote_content', 'class', $settings['blockquote_content_css'] );
		}

		if ( ! empty( $settings['author_name_css'] ) ) {
			$this->add_render_attribute( 'author_name', 'class', $settings['author_name_css'] );
		}

		$this->add_inline_editing_attributes( 'blockquote_content' );
		$this->add_inline_editing_attributes( 'author_name', 'none' );
		?>
		<div class="position-relative ps-4">
			<span <?php $this->print_render_attribute_string( 'blockquote_border' ); ?>></span>
			<blockquote <?php $this->print_render_attribute_string( 'blockquote' ); ?>>
				<div <?php $this->print_render_attribute_string( 'blockquote_content' ); ?>>
					<?php echo wp_kses_post( wpautop( $settings['blockquote_content'] ) ); ?>
				</div>
				<?php if ( ! empty( $settings['author_name'] ) ) : ?>
					<footer class="d-flex align-items-center pt-3">
						<?php if ( ! empty( $settings['image']['url'] ) ) : ?>
							<?php
								$image_html = Group_Control_Image_Size::get_attachment_image_html( $settings );
								Utils::print_wp_kses_extended( SN_Utils::add_class_to_image_html( $image_html, 'rounded-circle' ), [ 'image' ] );
							?>
						<div class="ps-3">
						<?php endif; ?>

						<?php if ( ! empty( $settings['author_name'] ) ) : ?>
							<cite <?php $this->print_render_attribute_string( 'author_name' ); ?>><?php echo wp_kses_post( $settings['author_name'] ); ?></cite>
							<?php if ( ! empty( $settings['author_byline'] ) ) : ?>
								<span class="fs-sm text-muted"><?php echo wp_kses_post( $settings['author_byline'] ); ?></span>
							<?php endif; ?>
						<?php endif ?>

						<?php if ( ! empty( $settings['image']['url'] ) ) : ?>
						</div>
						<?php endif; ?>
					</footer>
				<?php endif ?>
			</blockquote>
		</div>
		<?php
	}
}
