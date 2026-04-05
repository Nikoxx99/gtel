<?php
namespace SiliconElementor\Modules\Testimonial\Skins;

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
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Border;
use SiliconElementor\Plugin;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use SiliconElementor\Core\Utils as SN_Utils;

/**
 * Skin Testimonial Silicon
 */
class Skin_Testimonial extends Skin_Base {

	/**
	 * Constructor.
	 *
	 * @param Elementor\Widget_Base $parent The widget settings.
	 * @return void
	 */
	public function __construct( Elementor\Widget_Base $parent ) {
		parent::__construct( $parent );
		add_filter( 'silicon-elementor/widget/testimonial/print_template', [ $this, 'skin_print_template' ], 10, 2 );
		add_action( 'elementor/element/testimonial/section_testimonial/after_section_end', [ $this, 'add_content_control' ], 10 );
		add_action( 'elementor/element/testimonial/section_style_testimonial_image/after_section_end', [ $this, 'add_avatar_style_sections' ], 10 );
		add_action( 'elementor/element/testimonial/section_style_testimonial_content/after_section_end', [ $this, 'update_section_style_testimonial_content' ], 10 );
		add_action( 'elementor/element/testimonial/section_style_testimonial_name/after_section_end', [ $this, 'update_section_style_testimonial_name' ], 10 );
		add_action( 'elementor/element/testimonial/section_style_testimonial_job/after_section_end', [ $this, 'update_section_style_testimonial_job' ], 10 );
	}

	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'sn-skin-testimonial';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Skin v1', 'silicon-elementor' );
	}

	/**
	 * Added control of the Content tab...
	 */
	public function add_content_control() {

		$disable_controls = [
			'testimonial_image_position',
			'testimonial_alignment',
			'view',
			'link',
		];

		foreach ( $disable_controls as $control ) {
			$this->parent->update_control(
				$control,
				[
					'condition' => [
						'_skin!' => 'sn-skin-testimonial',
					],
				]
			);
		}

		$this->parent->start_injection(
			[
				'at' => 'before',
				'of' => 'testimonial_name',
			]
		);

		$this->add_control(
			'testimonial_logo',
			[
				'label'   => esc_html__( 'Choose Logo', 'silicon-elementor' ),
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
				'name'      => 'testimonial_logo',
				'default'   => 'full',
				'separator' => 'none',
			]
		);

		$this->parent->end_injection();

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => '_skin',
			]
		);

		$this->add_control(
			'enable_blockquote_icon',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Blockquote Icon', 'silicon-elementor' ),
				'default'   => 'no',
				'label_off' => esc_html__( 'Show', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Hide', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'blockquote_icon',
			[
				'label'     => esc_html__( 'Blockquote Icon', 'silicon-elementor' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => [
					'value' => 'bx bxs-quote-left',
				],
				'condition' => [
					$this->get_control_id( 'enable_blockquote_icon' ) => 'yes',
				],
			]
		);

		$this->parent->end_injection();
	}

	/**
	 * Adding Image Style Tab controls.
	 *
	 * @param Elementor\Widget_Base $widget pricing widget.
	 */
	public function add_avatar_style_sections( Elementor\Widget_Base $widget ) {
		$this->parent = $widget;
		$widget->update_control(
			'image_size',
			[
				'label'     => 'Avatar Size',
				'selectors' => [
					'{{WRAPPER}} .elementor-testimonial-wrapper .elementor-testimonial-image img' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .sn-avatar' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],

			]
		);

		$widget->update_control(
			'image_border_border',
			[
				'condition' => [
					'_skin!' => 'sn-skin-testimonial',
				],

			]
		);

		$widget->update_control(
			'image_border_color',
			[
				'condition' => [
					'_skin!' => 'sn-skin-testimonial',
				],

			]
		);

		$widget->update_control(
			'image_border_radius',
			[
				'condition' => [
					'_skin!' => 'sn-skin-testimonial',
				],

			]
		);

		$widget->update_control(
			'image_border_width',
			[
				'condition' => [
					'_skin!' => 'sn-skin-testimonial',
				],

			]
		);

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => 'image_size',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'image_border',
				'selector'  => '{{WRAPPER}} .sn-avatar',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'silicon-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .sn-avatar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'avatar_css',
			[
				'label'       => esc_html__( 'Avatar CSS', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'CSS Classes added to <img>', 'silicon-elementor' ),
				'default'     => 'd-md-none rounded-circle',

			]
		);

		$this->parent->end_injection();

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => 'avatar_css',
			]
		);

		$this->start_controls_section(
			'logo',
			[
				'label'     => esc_html__( 'Logo', 'silicon-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'logo_size',
			[
				'label'      => esc_html__( 'Logo Width', 'silicon-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 20,
						'max' => 200,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .sn-logo' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'logo_border',
				'selector'  => '{{WRAPPER}} .sn-logo',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'logo_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'silicon-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .sn-logo' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'logo_css',
			[
				'label'       => esc_html__( 'Logo CSS', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'CSS Classes added to <img>', 'silicon-elementor' ),
				'default'     => 'd-block mt-2 ms-5 mt-sm-0 ms-sm-0',

			]
		);

		$this->end_controls_section();

		$this->parent->end_injection();
	}

	/**
	 * Update section style content.
	 *
	 * @param array $widget the widget.
	 */
	public function update_section_style_testimonial_content( $widget ) {

		$widget->update_control(
			'content_content_color',
			[
				'selectors' => [
					'{{WRAPPER}} .elementor-testimonial-content' => 'color: {{VALUE}};',
					'{{WRAPPER}} blockquote p' => 'color: {{VALUE}};',
				],

			]
		);

		$widget->update_control(
			'content_typography_typography',
			[
				'condition' => [
					'_skin!' => 'sn-skin-testimonial',
				],

			]
		);

		$widget->update_control(
			'content_shadow_text_shadow',
			[
				'condition' => [
					'_skin!' => 'sn-skin-testimonial',
				],

			]
		);

		$widget->update_control(
			'content_shadow_text_shadow_type',
			[
				'condition' => [
					'_skin!' => 'sn-skin-testimonial',
				],

			]
		);

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => 'content_content_color',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} blockquote p',
			]
		);

		$this->parent->end_injection();

	}

	/**
	 * Update section style name.
	 *
	 * @param array $widget the widget.
	 */
	public function update_section_style_testimonial_name( $widget ) {

		$widget->update_control(
			'name_text_color',
			[
				'selectors' => [
					'{{WRAPPER}} .elementor-testimonial-name' => 'color: {{VALUE}};',
					'{{WRAPPER}} figcaption h5' => 'color: {{VALUE}};',
				],

			]
		);

		$widget->update_control(
			'name_typography_typography',
			[
				'condition' => [
					'_skin!' => 'sn-skin-testimonial',
				],

			]
		);

		$widget->update_control(
			'name_shadow_text_shadow',
			[
				'condition' => [
					'_skin!' => 'sn-skin-testimonial',
				],

			]
		);

		$widget->update_control(
			'name_shadow_text_shadow_type',
			[
				'condition' => [
					'_skin!' => 'sn-skin-testimonial',
				],

			]
		);

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => 'name_text_color',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'name',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} figcaption h5',
			]
		);

		$this->parent->end_injection();

	}

	/**
	 * Update section style name.
	 *
	 * @param array $widget the widget.
	 */
	public function update_section_style_testimonial_job( $widget ) {

		$widget->update_control(
			'job_text_color',
			[
				'selectors' => [
					'{{WRAPPER}} .elementor-testimonial-job' => 'color: {{VALUE}};',
					'{{WRAPPER}} figcaption span' => 'color: {{VALUE}} !important;',
				],

			]
		);

		$widget->update_control(
			'job_typography_typography',
			[
				'condition' => [
					'_skin!' => 'sn-skin-testimonial',
				],

			]
		);

		$widget->update_control(
			'job_shadow_text_shadow',
			[
				'condition' => [
					'_skin!' => 'sn-skin-testimonial',
				],

			]
		);

		$widget->update_control(
			'job_shadow_text_shadow_type',
			[
				'condition' => [
					'_skin!' => 'sn-skin-testimonial',
				],

			]
		);

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => 'job_text_color',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'job',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} figcaption span',
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
		$widget           = $this->parent;
		$settings         = $widget->get_settings_for_display();
		$skin_control_ids = [ 'testimonial_logo', 'testimonial_logo_size', 'testimonial_logo_custom_dimension', 'enable_blockquote_icon', 'blockquote_icon', 'avatar_css', 'logo_css' ];
		$skin_settings    = [];

		foreach ( $skin_control_ids as $skin_control_id ) {
			$skin_settings[ $skin_control_id ] = $this->get_instance_value( $skin_control_id );
		}
		?>
		<figure class="card position-relative border-0 shadow-sm py-3 p-0 p-lg-4 mt-4 mb-0 ms-xl-5">
			<?php
			if ( 'yes' === $skin_settings['enable_blockquote_icon'] ) {
				if ( ! isset( $skin_settings['blockquote_icon']['value']['url'] ) ) {
					$widget->add_render_attribute( 'sn-icon', 'class', 'sn-blockquote-icon ' . $skin_settings['blockquote_icon']['value'] );
					?>
					<span class="btn btn-icon btn-primary btn-lg shadow-primary pe-none position-absolute top-0 start-0 translate-middle-y ms-4 ms-lg-5">
						<i <?php $widget->print_render_attribute_string( 'sn-icon' ); ?>></i>
					</span>
					<?php
				}
				if ( isset( $skin_settings['blockquote_icon']['value']['url'] ) ) {
					?>
					<span class="btn btn-icon btn-primary btn-lg shadow-primary pe-none position-absolute top-0 start-0 translate-middle-y ms-4 ms-lg-5">
						<?php Icons_Manager::render_icon( $skin_settings['blockquote_icon'], [ 'aria-hidden' => 'true' ] ); ?>
					</span>
					<?php
				}
			}
			?>
			<blockquote class="card-body mt-2 mb-0">
				<p class="sn-content"><?php echo esc_html( $settings['testimonial_content'] ); ?></p>
			</blockquote>
			<figcaption class="card-footer border-0 d-sm-flex pt-0 mt-n3 mt-lg-0">
				<?php
				$widget->add_render_attribute( 'items-wrapper', 'class', 'd-flex align-items-center pe-sm-4 me-sm-4' );
				if ( isset( $skin_settings['testimonial_logo']['url'] ) && ! empty( $skin_settings['testimonial_logo']['url'] ) ) {
					$widget->add_render_attribute( 'items-wrapper', 'class', 'border-end-sm' );
				}
				?>
				<div <?php $widget->print_render_attribute_string( 'items-wrapper' ); ?>>
				<?php
				$avatar_class = 'sn-avatar ' . $skin_settings['avatar_css'];
				$avatar_html  = wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, 'testimonial_image', 'testimonial_image' ) );
				echo wp_kses_post( SN_Utils::add_class_to_image_html( $avatar_html, $avatar_class ) );
				?>
				<div class="ps-3 ps-md-0">
					<h5 class="fw-semibold lh-base mb-0"><?php echo esc_html( $settings['testimonial_name'] ); ?></h5>
					<span class="text-muted"><?php echo esc_html( $settings['testimonial_job'] ); ?></span>
				</div>
				</div>
				<?php
				$logo_class = 'sn-logo ' . $skin_settings['logo_css'];
				$logo_html  = wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $skin_settings, 'testimonial_logo', 'testimonial_logo' ) );
				echo wp_kses_post( SN_Utils::add_class_to_image_html( $logo_html, $logo_class ) );
				?>
			</figcaption>
		</figure>
		<?php

	}

	/**
	 * Skin print template.
	 *
	 * @param array $content the content.
	 * @param array $widget widget name.
	 * @return string
	 */
	public function skin_print_template( $content, $widget ) {

		if ( 'testimonial' === $widget->get_name() ) {
			return '';
		}

		return $content;
	}
}
