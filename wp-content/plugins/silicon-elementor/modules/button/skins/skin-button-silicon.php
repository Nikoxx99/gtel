<?php

namespace SiliconElementor\Modules\Button\Skins;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor;
use Elementor\Skin_Base;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;

// Group Controls.
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

// Group Values.
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

/**
 * Skin Button Silicon
 */
class Skin_Button_Silicon extends Skin_Base {

	/**
	 * Constructor.
	 *
	 * @param Widget_Base $parent The widget settings.
	 * @return void
	 */
	public function __construct( Widget_Base $parent ) {
		parent::__construct( $parent );
	}

	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'button-silicon';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Silicon', 'silicon-elementor' );
	}

	/**
	 * Register control actions.
	 *
	 * @return void
	 */
	protected function _register_controls_actions() {
		add_filter( 'silicon-elementor/widget/button/print_template', [ $this, 'skin_print_template' ], 10, 2 );
		add_action( 'elementor/element/button/section_button/before_section_end', [ $this, 'register_button_content_controls' ], 10 );
		add_action( 'elementor/element/button/section_style/after_section_start', [ $this, 'update_button_style_controls' ], 10 );

		add_action( 'elementor/element/button/section_style/after_section_end', [ $this, 'register_button_style_controls' ], 10 );
	}

	/**
	 * Register silicon button content controls.
	 *
	 * @param array $widget The widget settings.
	 * @return void
	 */
	public function register_button_content_controls( $widget ) {

		$update_control_ids = [ 'button_type', 'size' ];

		foreach ( $update_control_ids as $update_control_id ) {
			$widget->update_control(
				$update_control_id,
				[
					'condition' => [
						'_skin' => '',
					],
				]
			);
		}

		// Get default selectors for icon indent.
		$icon_indent_selectors = $widget->get_controls( 'icon_indent' )['selectors'];

		$margin_dirs = [ 'left', 'right' ];

		foreach ( $margin_dirs as $margin_dir ) {
			$margin_value = 'left' === $margin_dir ? 'right' : 'left';
			$icon_indent_selectors[ '{{WRAPPER}} .silicon-button .elementor-align-icon-' . $margin_dir ] = 'margin-' . $margin_value . ': {{SIZE}}{{UNIT}};';
		}

		$widget->update_control( 'icon_indent', [ 'selectors' => $icon_indent_selectors ] );

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => '_skin',
			]
		);

			$this->add_control(
				'button_type',
				[
					'label'   => esc_html__( 'Type', 'silicon-elementor' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'primary',
					'options' => [
						'primary'   => esc_html__( 'Primary', 'silicon-elementor' ),
						'secondary' => esc_html__( 'Secondary', 'silicon-elementor' ),
						'success'   => esc_html__( 'Success', 'silicon-elementor' ),
						'danger'    => esc_html__( 'Danger', 'silicon-elementor' ),
						'warning'   => esc_html__( 'Warning', 'silicon-elementor' ),
						'info'      => esc_html__( 'Info', 'silicon-elementor' ),
						'light'     => esc_html__( 'Light', 'silicon-elementor' ),
						'dark'      => esc_html__( 'Dark', 'silicon-elementor' ),
						'link'      => esc_html__( 'Link', 'silicon-elementor' ),
					],
				]
			);

			$this->add_control(
				'button_variant',
				[
					'label'   => esc_html__( 'Variant', 'silicon-elementor' ),
					'type'    => Controls_Manager::SELECT,
					'default' => '',
					'options' => [
						''         => esc_html__( 'Default', 'silicon-elementor' ),
						'outline'  => esc_html__( 'Outline', 'silicon-elementor' ),
						'active'   => esc_html__( 'Active', 'silicon-elementor' ),
						'disabled' => esc_html__( 'Disabled', 'silicon-elementor' ),
					],
				]
			);

			$this->add_control(
				'button_size',
				[
					'label'   => esc_html__( 'Size', 'silicon-elementor' ),
					'type'    => Controls_Manager::SELECT,
					'default' => '',
					'options' => [
						''   => esc_html__( 'Default', 'silicon-elementor' ),
						'sm' => esc_html__( 'Small', 'silicon-elementor' ),
						'lg' => esc_html__( 'Large', 'silicon-elementor' ),
					],
				]
			);

			$this->add_control(
				'button_shape',
				[
					'label'   => esc_html__( 'Shape', 'silicon-elementor' ),
					'type'    => Controls_Manager::SELECT,
					'default' => '',
					'options' => [
						''             => esc_html__( 'Default', 'silicon-elementor' ),
						'rounded-pill' => esc_html__( 'Pill', 'silicon-elementor' ),
						'rounded-0'    => esc_html__( 'Square', 'silicon-elementor' ),
					],
				]
			);

			$this->add_control(
				'shadow',
				[
					'label'        => esc_html__( 'Enable Shadow', 'silicon-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'silicon-elementor' ),
					'label_off'    => esc_html__( 'No', 'silicon-elementor' ),
					'return_value' => 'yes',
					'default'      => 'no',
					'condition'    => [
						$this->get_control_id( 'button_variant' ) => '',
					],
				]
			);

			$this->add_control(
				'gradient',
				[
					'label'        => esc_html__( 'Enable Gradient', 'silicon-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'silicon-elementor' ),
					'label_off'    => esc_html__( 'No', 'silicon-elementor' ),
					'return_value' => 'yes',
					'default'      => 'no',
					'condition'    => [
						$this->get_control_id( 'button_variant' ) => '',
					],
				]
			);

			$this->add_control(
				'is_block_button',
				[
					'label'        => esc_html__( 'Is Block Button?', 'silicon-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'silicon-elementor' ),
					'label_off'    => esc_html__( 'No', 'silicon-elementor' ),
					'return_value' => 'yes',
					'default'      => 'no',
				]
			);

			$this->add_control(
				'is_spinner_button',
				[
					'label'        => esc_html__( 'Is Spinner Button?', 'silicon-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'silicon-elementor' ),
					'label_off'    => esc_html__( 'No', 'silicon-elementor' ),
					'return_value' => 'yes',
					'default'      => 'no',
				]
			);

			$this->add_control(
				'spinner_button',
				[
					'label'     => esc_html__( 'Spinner', 'silicon-elementor' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'spinner_border',
					'options'   => [
						'spinner_border' => esc_html__( 'Border', 'silicon-elementor' ),
						'spinner_grow'   => esc_html__( 'Grow', 'silicon-elementor' ),
					],
					'condition' => [
						$this->get_control_id( 'is_spinner_button' ) => 'yes',
					],
				]
			);

			$this->add_control(
				'button_tag',
				[
					'label'     => esc_html__( 'Enable button tag', 'silicon-elementor' ),
					'type'      => Controls_Manager::SWITCHER,
					'label_on'  => esc_html__( 'Yes', 'silicon-elementor' ),
					'label_off' => esc_html__( 'No', 'silicon-elementor' ),
					'default'   => 'no',
				]
			);

		$this->parent->end_injection();

		$this->add_control(
			'icon_size',
			[
				'label'      => esc_html__( 'Icon Size', 'silicon-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .sn-button-icon' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				],
			],
			[
				'position' => [
					'at' => 'after',
					'of' => 'icon_indent',
				],
			]
		);

		$this->add_control(
			'icon_css_classes',
			[
				'label'       => esc_html__( 'CSS Classes', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'title'       => esc_html__( 'Add your custom class WITHOUT the dot. e.g: my-class', 'silicon-elementor' ),
				'description' => esc_html__( 'Added to <i> tag', 'silicon-elementor' ),
			],
			[
				'position' => [
					'at' => 'after',
					'of' => 'icon_indent',
				],
			]
		);
	}

	/**
	 * Update silicon button content controls.
	 *
	 * @param array $widget The widget settings.
	 * @return void
	 */
	public function update_button_style_controls( $widget ) {

		$widget->update_control(
			'section_style',
			[
				'condition' => [
					'_skin' => '',
				],
			]
		);
	}

	/**
	 * Register silicon button style controls.
	 *
	 * @param Elementor\Widget_Base $widget The widget settings.
	 * @return void
	 */
	public function register_button_style_controls( Widget_Base $widget ) {
		$this->parent = $widget;
		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Button', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'typography',
					'global'   => [
						'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
					],
					'selector' => '{{WRAPPER}} .silicon-button',
				]
			);

			$this->start_controls_tabs( 'tabs_button_style' );

				$this->start_controls_tab(
					'tab_button_normal',
					[
						'label' => esc_html__( 'Normal', 'silicon-elementor' ),
					]
				);

					$this->add_control(
						'button_text_color',
						[
							'label'     => esc_html__( 'Text Color', 'silicon-elementor' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .silicon-button-text' => 'color: {{VALUE}};',
							],
						]
					);

					$this->add_control(
						'button_border_color',
						[
							'label'     => esc_html__( 'Border Color', 'silicon-elementor' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .silicon-button' => 'border-color: {{VALUE}};',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Background::get_type(),
						[
							'name'           => 'background',
							'label'          => esc_html__( 'Background', 'silicon-elementor' ),
							'types'          => [ 'classic', 'gradient' ],
							'exclude'        => [ 'image' ],
							'selector'       => '{{WRAPPER}} .silicon-button',
							'fields_options' => [
								'background' => [
									'default' => 'classic',
								],
								'color'      => [
									'global' => [
										'default' => Global_Colors::COLOR_PRIMARY,
									],
								],
							],
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_button_hover',
					[
						'label' => esc_html__( 'Hover', 'silicon-elementor' ),
					]
				);

					$this->add_control(
						'hover_color',
						[
							'label'     => esc_html__( 'Text Color', 'silicon-elementor' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .silicon-button-text:hover, {{WRAPPER}} .silicon-button-text:focus' => 'color: {{VALUE}};',
								'{{WRAPPER}} .silicon-button-text:hover svg path, {{WRAPPER}} .silicon-button-text:focus svg path' => 'fill: {{VALUE}};',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Background::get_type(),
						[
							'name'           => 'button_background_hover',
							'label'          => esc_html__( 'Background', 'silicon-elementor' ),
							'types'          => [ 'classic', 'gradient' ],
							'exclude'        => [ 'image' ],
							'selector'       => '{{WRAPPER}} .silicon-button:hover, {{WRAPPER}} .silicon-button:focus',
							'fields_options' => [
								'background' => [
									'default' => 'classic',
								],
							],
						]
					);

					$this->add_control(
						'button_hover_border_color',
						[
							'label'     => esc_html__( 'Border Color', 'silicon-elementor' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .silicon-button:hover, {{WRAPPER}} .silicon-button:focus' => 'border-color: {{VALUE}};',
							],
						]
					);

					$this->add_control(
						'hover_animation',
						[
							'label' => esc_html__( 'Hover Animation', 'silicon-elementor' ),
							'type'  => Controls_Manager::HOVER_ANIMATION,
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'           => 'border',
					'selector'       => '{{WRAPPER}} .silicon-button',
					'separator'      => 'before',
					'fields_options' => [
						'border' => [
							'default' => '',
						],
					],
				]
			);

			$this->add_control(
				'border_radius',
				[
					'label'      => esc_html__( 'Border Radius', 'silicon-elementor' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors'  => [
						'{{WRAPPER}} .silicon-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name'     => 'button_box_shadow',
					'selector' => '{{WRAPPER}} .silicon-button',
				]
			);

			$this->add_responsive_control(
				'text_padding',
				[
					'label'      => esc_html__( 'Padding', 'silicon-elementor' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .silicon-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					],
					'separator'  => 'before',
				]
			);

			$this->add_control(
				'btn_css',
				[
					'label'       => esc_html__( 'CSS Classes', 'silicon-elementor' ),
					'type'        => Controls_Manager::TEXT,
					'dynamic'     => [
						'active' => true,
					],
					'title'       => esc_html__( 'Add your custom class WITHOUT the dot. e.g: my-class', 'silicon-elementor' ),
					'description' => esc_html__( 'Added to .btn tag', 'silicon-elementor' ),
				]
			);

			$this->add_control(
				'btn_content_wrapper_css',
				[
					'label'       => esc_html__( 'CSS Classes', 'silicon-elementor' ),
					'type'        => Controls_Manager::TEXT,
					'dynamic'     => [
						'active' => true,
					],
					'title'       => esc_html__( 'Add your custom class WITHOUT the dot. e.g: my-class', 'silicon-elementor' ),
					'description' => esc_html__( 'Added to .elementor-button-content-wrapper tag', 'silicon-elementor' ),
				]
			);

		$this->end_controls_section();
	}

	/**
	 * Render Text.
	 *
	 * @return void
	 */
	public function render_text() {
		$parent   = $this->parent;
		$settings = $parent->get_settings_for_display();

		$icon_css_classes        = $this->get_instance_value( 'icon_css_classes' );
		$btn_content_wrapper_css = $this->get_instance_value( 'btn_content_wrapper_css' );

		$migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
		$is_new   = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();

		if ( ! $is_new && empty( $settings['icon_align'] ) ) {
			// @todo: remove when deprecated
			// added as bc in 2.6
			// old default
			$settings['icon_align'] = $parent->get_settings_for_display( 'icon_align' );
		}

		$order      = 'right' === $settings['icon_align'] ? 'order-last' : 'order-first';
		$text_order = 'right' === $settings['icon_align'] ? 'order-first' : 'silicon-button-text order-last';

		$parent->add_render_attribute(
			[
				'content-wrapper' => [
					'class' => 'elementor-button-content-wrapper',
				],
				'icon-align'      => [
					'class' => [
						'elementor-button-icon',
						'lh-1 elementor-align-icon-' . $settings['icon_align'],
						$order,
					],
				],
				'text'            => [
					'class' => [
						'elementor-button-text',
						$text_order,
					],
				],
			]
		);

		if ( ! empty( $btn_content_wrapper_css ) ) {
			$parent->add_render_attribute( 'content-wrapper', 'class', $btn_content_wrapper_css );
		}

		?><span <?php $parent->print_render_attribute_string( 'content-wrapper' ); ?>>
		<?php
		if ( ! empty( $settings['icon'] ) || ! empty( $settings['selected_icon']['value'] ) ) :
			?>
				<span <?php $parent->print_render_attribute_string( 'icon-align' ); ?>>
					<?php
					if ( $is_new || $migrated ) :
						$icon_atts          = [ 'aria-hidden' => 'true' ];
						$icon_atts['class'] = 'sn-button-icon ';
						if ( ! empty( $icon_css_classes ) ) {
							$icon_atts['class'] .= $icon_css_classes;
						}

						Icons_Manager::render_icon( $settings['selected_icon'], $icon_atts );
							else :
								?>
						<i class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></i>
					<?php endif; ?>
				</span>
						<?php
			endif;

		if ( ! empty( $settings['text'] ) ) {
			?>
				<span <?php $parent->print_render_attribute_string( 'text' ); ?>><?php echo esc_html( $settings['text'] ); ?></span>
				<?php
		}
		?>
		</span>
		<?php
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
			'button_type',
			'button_variant',
			'button_size',
			'button_shape',
			'is_block_button',
			'is_spinner_button',
			'spinner_button',
			'icon_css_classes',
			'hover_animation',
			'btn_css',
			'shadow',
			'gradient',
			'button_tag',
			'icon_size',
		];

		$skin_settings = [];

		foreach ( $skin_control_ids as $skin_control_id ) {
			$skin_settings[ $skin_control_id ] = $this->get_instance_value( $skin_control_id );
		}

		if ( $skin_settings['hover_animation'] ) {
			$parent->add_render_attribute( 'button', 'class', 'elementor-animation-' . $skin_settings['hover_animation'] );
		}

		if ( ! empty( $settings['button_css_id'] ) ) {
			$parent->add_render_attribute( 'button', 'id', $settings['button_css_id'] );
		}

		if ( 'yes' === $skin_settings['shadow'] ) {
			$parent->add_render_attribute( 'button', 'class', 'shadow-' . $skin_settings['button_type'] );
		}

		if ( 'yes' === $skin_settings['gradient'] ) {
			$parent->add_render_attribute( 'button', 'class', 'bg-gradient' );
		}

		if ( 'active' === $skin_settings['button_variant'] ) {
			$parent->add_render_attribute( 'button', 'class', 'active' );
		}

		if ( 'disabled' === $skin_settings['button_variant'] ) {
			$parent->add_render_attribute( 'button', 'class', 'disabled' );
		}

		if ( 'yes' === $skin_settings['is_spinner_button'] ) {
			$parent->add_render_attribute( 'button', 'class', 'pe-none' );
		}

		if ( ! empty( $settings['link']['url'] ) ) {
			$parent->add_link_attributes( 'button', $settings['link'] );
		}

		$button_class = [ 'silicon-button' ];

		if ( 'link' !== $skin_settings['button_variant'] ) {
			$button_class[] = 'btn';
		}

		if ( 'outline' === $skin_settings['button_variant'] ) {
			$button_class[] = 'btn-outline-' . $skin_settings['button_type'];
		} elseif ( 'soft' === $skin_settings['button_variant'] ) {
			$button_class[] = 'btn-' . $skin_settings['button_type'] . '-soft';
		} elseif ( 'link' === $skin_settings['button_variant'] ) {
			$button_class[] = 'btn-' . $skin_settings['button_type'] . '-link';
		} else {
			$button_class[] = 'btn-' . $skin_settings['button_type'];
		}

		if ( ! empty( $skin_settings['button_size'] ) && 'link' !== $skin_settings['button_variant'] ) {
			$button_class[] = 'btn-' . $skin_settings['button_size'];
		}

		if ( ! empty( $skin_settings['button_shape'] ) && 'link' !== $skin_settings['button_variant'] ) {
			$button_class[] = $skin_settings['button_shape'];
		}

		if ( ! empty( $skin_settings['btn_css'] ) ) {
			$button_class[] = $skin_settings['btn_css'];
		}

		$parent->add_render_attribute( 'button', 'class', $button_class );
		$parent->add_render_attribute( 'button', 'role', 'button' );

		if ( 'yes' === $skin_settings['is_block_button'] ) {
			?>
			<div class="d-grid">
			<?php
		}

		$button_tag = 'yes' === $skin_settings['button_tag'] ? 'button' : 'a';
		if ( 'yes' === $skin_settings['button_tag'] ) {
			$parent->add_render_attribute( 'button', 'type', 'button' );
		}

		?>
		<<?php echo esc_html( $button_tag ); ?> <?php $parent->print_render_attribute_string( 'button' ); ?>>
			<?php
			if ( 'yes' === $skin_settings['is_spinner_button'] ) :

				if ( 'spinner_border' === $skin_settings['spinner_button'] ) {
					$parent->add_render_attribute( 'spinner_button', 'class', 'spinner-border spinner-border-sm me-2' );
				}

				if ( 'spinner_grow' === $skin_settings['spinner_button'] ) {
					$parent->add_render_attribute( 'spinner_button', 'class', 'spinner-grow spinner-grow-sm me-2' );
				}
				?>
				<span <?php $parent->print_render_attribute_string( 'spinner_button' ); ?> role="status" aria-hidden="true">
					<span class="visually-hidden">Loading...</span>
				</span>
				<?php
			endif;
			$this->render_text();
			?>
		</<?php echo esc_html( $button_tag ); ?>>
		<?php

		if ( 'yes' === $skin_settings['is_block_button'] ) {
			?>
			</div>
			<?php
		}
	}

	/**
	 * Skin print template.
	 *
	 * @param array $content the content.
	 * @param array $button widget name.
	 * @return string
	 */
	public function skin_print_template( $content, $button ) {

		if ( 'button' === $button->get_name() ) {
			return '';
		}

		return $content;
	}
}
